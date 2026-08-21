<?php
$page_title = 'Manage News / Blog Posts';
include '../includes/admin_header.php';

// Helper function to create a URL-friendly "slug"
function create_slug($string) {
    $slug = preg_replace('/[^A-Za-z0-9-]+/', '-', strtolower($string));
    return trim($slug, '-');
}

$message = '';

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // --- DELETE POST ---
    if (isset($_POST['delete'])) {
        $id = intval($_POST['id']);
        
        // Fetch image to delete from disk
        $stmt_img = $conn->prepare("SELECT image_url FROM posts WHERE id = ?");
        $stmt_img->bind_param("i", $id);
        $stmt_img->execute();
        $res = $stmt_img->get_result()->fetch_assoc();
        $stmt_img->close();

        if ($res && !empty($res['image_url']) && file_exists('../' . $res['image_url'])) {
            unlink('../' . $res['image_url']);
        }

        $stmt = $conn->prepare("DELETE FROM posts WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $message = '
            <div class="p-4 bg-emerald-50 border border-emerald-100 rounded-2xl flex items-start gap-3 text-emerald-800 text-sm mb-6 shadow-sm">
                <i class="ri-checkbox-circle-fill text-lg text-emerald-600 shrink-0 mt-0.5"></i>
                <div>
                    <p class="font-bold text-slate-900">Post Deleted Successfully</p>
                    <p class="text-emerald-700 text-xs mt-0.5">The article has been permanently removed from the website.</p>
                </div>
            </div>';
        }
        $stmt->close();
    } 
    // --- ADD or UPDATE POST ---
    else {
        $id = !empty($_POST['id']) ? intval($_POST['id']) : null;
        $title = trim($_POST['title'] ?? '');
        $author = trim($_POST['author'] ?? ($_SESSION['admin_username'] ?? 'Admin'));
        $content = trim($_POST['content'] ?? '');
        $custom_slug = trim($_POST['slug'] ?? '');
        $slug = !empty($custom_slug) ? create_slug($custom_slug) : create_slug($title);
        $image_url = $_POST['existing_image'] ?? '';

        // Handle Image Upload
        if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] == 0) {
            $target_dir = '../uploads/posts/';
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            $file_ext = strtolower(pathinfo($_FILES['featured_image']['name'], PATHINFO_EXTENSION));
            $allowed_exts = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
            
            if (in_array($file_ext, $allowed_exts)) {
                $filename = uniqid('post_') . '.' . $file_ext;
                $target_file = $target_dir . $filename;
                if (move_uploaded_file($_FILES['featured_image']['tmp_name'], $target_file)) {
                    if (!empty($image_url) && file_exists('../' . $image_url)) {
                        unlink('../' . $image_url);
                    }
                    $image_url = 'uploads/posts/' . $filename;
                }
            }
        }

        if (empty($title) || empty($content)) {
            $message = '
            <div class="p-4 bg-rose-50 border border-rose-100 rounded-2xl flex items-start gap-3 text-rose-800 text-sm mb-6 shadow-sm">
                <i class="ri-error-warning-fill text-lg text-rose-600 shrink-0 mt-0.5"></i>
                <div>
                    <p class="font-bold text-slate-900">Missing Information</p>
                    <p class="text-rose-700 text-xs mt-0.5">Please provide both an article title and content body.</p>
                </div>
            </div>';
        } else {
            if (empty($id)) {
                // Add New
                $stmt = $conn->prepare("INSERT INTO posts (title, slug, content, author, image_url) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("sssss", $title, $slug, $content, $author, $image_url);
                $success_text = "New blog article published successfully.";
            } else {
                // Update
                $stmt = $conn->prepare("UPDATE posts SET title = ?, slug = ?, content = ?, author = ?, image_url = ? WHERE id = ?");
                $stmt->bind_param("sssssi", $title, $slug, $content, $author, $image_url, $id);
                $success_text = "Article details have been updated.";
            }

            if ($stmt->execute()) {
                $message = '
                <div class="p-4 bg-emerald-50 border border-emerald-100 rounded-2xl flex items-start gap-3 text-emerald-800 text-sm mb-6 shadow-sm">
                    <i class="ri-checkbox-circle-fill text-lg text-emerald-600 shrink-0 mt-0.5"></i>
                    <div>
                        <p class="font-bold text-slate-900">' . htmlspecialchars($success_text) . '</p>
                        <p class="text-emerald-700 text-xs mt-0.5">Live route: <code class="bg-emerald-100/70 text-emerald-900 px-1.5 py-0.5 rounded font-mono">/post/' . htmlspecialchars($slug) . '</code></p>
                    </div>
                </div>';
            }
            $stmt->close();
        }
    }
}

// Fetch data for editing
$edit_post = null;
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $stmt = $conn->prepare("SELECT * FROM posts WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $edit_post = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}

$posts_res = $conn->query("SELECT id, title, slug, content, author, image_url, created_at FROM posts ORDER BY created_at DESC");
$total_posts_cnt = $posts_res ? $posts_res->num_rows : 0;
?>

<!-- CKEditor 5 CDN -->
<script src="https://cdn.ckeditor.com/ckeditor5/41.1.0/classic/ckeditor.js"></script>
<style>
    .ck-editor__editable_inline {
        min-height: 320px;
        background-color: #ffffff !important;
        border-bottom-left-radius: 0.85rem !important;
        border-bottom-right-radius: 0.85rem !important;
        padding: 15px 20px !important;
        font-size: 0.95rem;
        line-height: 1.6;
    }
    .ck-toolbar {
        background-color: #f8fafc !important;
        border-top-left-radius: 0.85rem !important;
        border-top-right-radius: 0.85rem !important;
        border-color: #e2e8f0 !important;
        padding: 6px 10px !important;
    }
    .ck.ck-editor__main>.ck-editor__editable {
        border-color: #e2e8f0 !important;
    }
</style>

<div class="max-w-7xl mx-auto space-y-8 py-2">

    <!-- Notification Message -->
    <?= $message ?>

    <!-- KPI Summary Row -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Articles</p>
                <h3 class="text-2xl font-black text-slate-900 mt-1"><?= $total_posts_cnt ?></h3>
                <p class="text-[11px] text-emerald-600 font-medium mt-0.5"><i class="ri-check-line"></i> Published & active</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-2xl">
                <i class="ri-article-line"></i>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Live URL Structure</p>
                <h3 class="text-sm font-bold text-slate-800 font-mono mt-2 bg-slate-100 px-2.5 py-1 rounded-lg">/post/{slug}</h3>
                <p class="text-[11px] text-slate-500 font-medium mt-1">SEO-friendly clean routes</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl">
                <i class="ri-links-line"></i>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Editor Engine</p>
                <h3 class="text-base font-bold text-slate-900 mt-1">CKEditor 5 Pro</h3>
                <p class="text-[11px] text-indigo-600 font-medium mt-0.5"><i class="ri-sparkling-fill"></i> Rich format enabled</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-2xl">
                <i class="ri-edit-2-line"></i>
            </div>
        </div>
    </div>

    <!-- Form Section -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 lg:p-8 relative">
        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-indigo-500 via-indigo-600 to-sky-500 rounded-t-2xl"></div>
        
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 pb-4 border-b border-slate-100">
            <div>
                <h3 class="text-xl font-extrabold text-slate-900">
                    <?= $edit_post ? 'Edit Article: <span class="text-indigo-600">#' . $edit_post['id'] . '</span>' : 'Create New News / Blog Post' ?>
                </h3>
                <p class="text-xs text-slate-500 mt-1">Write and publish announcements, press releases, achievements, and events</p>
            </div>
            <?php if ($edit_post): ?>
                <a href="manage_posts.php" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition">
                    <i class="ri-add-line"></i> New Article Mode
                </a>
            <?php endif; ?>
        </div>

        <form action="manage_posts.php" method="POST" enctype="multipart/form-data" class="space-y-6">
            <input type="hidden" name="id" value="<?= $edit_post['id'] ?? '' ?>">
            <input type="hidden" name="existing_image" value="<?= $edit_post['image_url'] ?? '' ?>">

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Main Form Body (2 Cols) -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Title -->
                    <div class="space-y-1.5">
                        <label for="post_title" class="text-xs font-bold text-slate-700 uppercase tracking-wider flex items-center justify-between">
                            <span>Article Title *</span>
                            <span class="text-[11px] text-slate-400 font-normal normal-case">Headline of your post</span>
                        </label>
                        <input 
                            type="text" 
                            name="title" 
                            id="post_title"
                            value="<?= htmlspecialchars($edit_post['title'] ?? '') ?>" 
                            placeholder="e.g. Annual Science Fair Competition Winners Announced"
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:bg-white text-base transition font-semibold text-slate-900" 
                            required
                            oninput="updateSlugPreview(this.value)"
                        >
                    </div>

                    <!-- Slug Preview & Custom Slug -->
                    <div class="p-4 bg-slate-50 rounded-xl border border-slate-200/80 space-y-2">
                        <div class="flex items-center justify-between text-xs font-semibold text-slate-600">
                            <span class="flex items-center gap-1.5">
                                <i class="ri-link text-indigo-600"></i> Permalink / Route:
                            </span>
                            <span id="slug-preview" class="font-mono text-indigo-600 font-bold">
                                /post/<?= htmlspecialchars($edit_post['slug'] ?? 'your-article-title') ?>
                            </span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-xs text-slate-400">Custom Slug:</span>
                            <input 
                                type="text" 
                                name="slug" 
                                id="custom_slug"
                                value="<?= htmlspecialchars($edit_post['slug'] ?? '') ?>" 
                                placeholder="optional-custom-slug"
                                class="flex-1 px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs font-mono text-slate-700 focus:outline-none focus:border-indigo-500"
                            >
                        </div>
                    </div>

                    <!-- Content Area (CKEditor) -->
                    <div class="space-y-1.5">
                        <label for="content" class="text-xs font-bold text-slate-700 uppercase tracking-wider block">
                            Article Body Content *
                        </label>
                        <textarea 
                            name="content" 
                            id="content" 
                            rows="12" 
                            placeholder="Write your article copy here..."
                            class="w-full"
                        ><?= htmlspecialchars($edit_post['content'] ?? '') ?></textarea>
                    </div>
                </div>

                <!-- Meta Sidebar (1 Col) -->
                <div class="space-y-6">
                    
                    <!-- Author Information -->
                    <div class="p-5 bg-slate-50 rounded-2xl border border-slate-200/80 space-y-3">
                        <label for="author" class="text-xs font-bold text-slate-700 uppercase tracking-wider block">
                            Author Name
                        </label>
                        <div class="relative">
                            <i class="ri-user-3-line absolute left-3.5 top-3 text-slate-400"></i>
                            <input 
                                type="text" 
                                name="author" 
                                id="author"
                                value="<?= htmlspecialchars($edit_post['author'] ?? ($_SESSION['admin_username'] ?? 'Principal Office')) ?>" 
                                placeholder="e.g. Editorial Board"
                                class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:border-indigo-500"
                            >
                        </div>
                    </div>

                    <!-- Featured Thumbnail Image -->
                    <div class="p-5 bg-slate-50 rounded-2xl border border-slate-200/80 space-y-3">
                        <label class="text-xs font-bold text-slate-700 uppercase tracking-wider block">
                            Featured Image / Cover
                        </label>

                        <?php if (!empty($edit_post['image_url'])): ?>
                            <div class="relative rounded-xl overflow-hidden border border-slate-200 h-36 bg-slate-900 group">
                                <img src="../<?= htmlspecialchars($edit_post['image_url']) ?>" class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-slate-900/60 opacity-0 group-hover:opacity-100 transition flex items-center justify-center text-white text-xs font-semibold">
                                    Current Featured Cover
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="border-2 border-dashed border-slate-200 hover:border-indigo-400 rounded-xl p-4 text-center bg-white transition cursor-pointer relative">
                            <input 
                                type="file" 
                                name="featured_image" 
                                id="featured_image"
                                accept="image/*"
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                onchange="previewThumbnail(this)"
                            >
                            <div id="upload-placeholder" class="space-y-1">
                                <i class="ri-image-add-line text-2xl text-slate-400"></i>
                                <p class="text-xs font-bold text-slate-700">Click to upload cover image</p>
                                <p class="text-[10px] text-slate-400">PNG, JPG, WebP up to 5MB</p>
                            </div>
                            <img id="image-preview" class="hidden w-full h-32 object-cover rounded-lg mt-2">
                        </div>
                    </div>

                    <!-- Publish Button Box -->
                    <div class="p-5 bg-slate-900 rounded-2xl text-white space-y-3 shadow-sm">
                        <div class="flex items-center justify-between text-xs text-slate-400">
                            <span>Status:</span>
                            <span class="font-bold text-emerald-400 flex items-center gap-1">
                                <span class="w-2 h-2 rounded-full bg-emerald-400"></span> Ready to Publish
                            </span>
                        </div>

                        <button 
                            type="submit" 
                            class="w-full bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-bold py-3 px-4 rounded-xl transition shadow-lg shadow-indigo-600/30 flex items-center justify-center gap-2 active:scale-[0.98]"
                        >
                            <i class="<?= $edit_post ? 'ri-save-line' : 'ri-send-plane-fill' ?>"></i>
                            <?= $edit_post ? 'Update Article' : 'Publish Article Now' ?>
                        </button>

                        <?php if ($edit_post): ?>
                            <a 
                                href="manage_posts.php" 
                                class="w-full bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-semibold py-2 px-4 rounded-xl transition text-center block"
                            >
                                Cancel Editing
                            </a>
                        <?php endif; ?>
                    </div>

                </div>

            </div>
        </form>
    </div>

    <!-- Posts Directory Table -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 lg:p-8">
        
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <div>
                <h3 class="text-lg font-bold text-slate-900">Published Articles Library</h3>
                <p class="text-xs text-slate-500 mt-0.5">Filter, search, review, and manage live articles</p>
            </div>
            
            <!-- Live Search Bar -->
            <div class="relative w-full sm:w-72">
                <i class="ri-search-line absolute left-3.5 top-2.5 text-slate-400 text-sm"></i>
                <input 
                    type="text" 
                    id="table-search" 
                    placeholder="Search articles by title..." 
                    class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:bg-white focus:border-indigo-500 transition"
                    onkeyup="filterTable()"
                >
            </div>
        </div>

        <div class="overflow-x-auto rounded-xl border border-slate-100">
            <table class="min-w-full divide-y divide-slate-100 text-left text-sm" id="posts-table">
                <thead class="bg-slate-50/80 text-xs font-bold text-slate-400 uppercase tracking-wider">
                    <tr>
                        <th class="py-3.5 px-4">Article</th>
                        <th class="py-3.5 px-4">Author</th>
                        <th class="py-3.5 px-4">Publish Date</th>
                        <th class="py-3.5 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    <?php if ($posts_res && $posts_res->num_rows > 0): ?>
                        <?php while ($row = $posts_res->fetch_assoc()): ?>
                        <tr class="hover:bg-slate-50/60 transition post-row">
                            <!-- Article Info -->
                            <td class="py-4 px-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-xl bg-slate-100 overflow-hidden shrink-0 border border-slate-200 flex items-center justify-center">
                                        <?php if (!empty($row['image_url'])): ?>
                                            <img src="../<?= htmlspecialchars($row['image_url']) ?>" class="w-full h-full object-cover">
                                        <?php else: ?>
                                            <i class="ri-article-line text-xl text-slate-400"></i>
                                        <?php endif; ?>
                                    </div>
                                    <div class="space-y-1 max-w-md">
                                        <h4 class="font-bold text-slate-900 text-sm line-clamp-1 post-title-text" title="<?= htmlspecialchars($row['title']) ?>">
                                            <?= htmlspecialchars($row['title']) ?>
                                        </h4>
                                        <div class="flex items-center gap-2">
                                            <span class="inline-flex items-center gap-1 font-mono text-[10px] text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-md border border-indigo-100">
                                                <i class="ri-link text-indigo-400"></i> /post/<?= htmlspecialchars($row['slug']) ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Author -->
                            <td class="py-4 px-4 whitespace-nowrap">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700">
                                    <i class="ri-user-3-line text-slate-400"></i>
                                    <?= htmlspecialchars($row['author'] ?? 'Admin') ?>
                                </span>
                            </td>

                            <!-- Publish Date -->
                            <td class="py-4 px-4 whitespace-nowrap text-xs text-slate-500 font-medium">
                                <div class="flex items-center gap-1.5">
                                    <i class="ri-calendar-event-line text-slate-400"></i>
                                    <?= date('M j, Y', strtotime($row['created_at'])) ?>
                                </div>
                            </td>

                            <!-- Actions -->
                            <td class="py-4 px-4 whitespace-nowrap text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <!-- View Live Article -->
                                    <a 
                                        href="../post/<?= htmlspecialchars($row['slug']) ?>" 
                                        target="_blank" 
                                        class="p-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl transition inline-flex items-center justify-center shadow-sm"
                                        title="View Live Article"
                                    >
                                        <i class="ri-external-link-line text-sm"></i>
                                    </a>

                                    <!-- Edit -->
                                    <a 
                                        href="manage_posts.php?edit=<?= $row['id'] ?>" 
                                        class="p-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-600 rounded-xl transition inline-flex items-center justify-center shadow-sm"
                                        title="Edit Article"
                                    >
                                        <i class="ri-pencil-line text-sm"></i>
                                    </a>

                                    <!-- Delete -->
                                    <form 
                                        action="manage_posts.php" 
                                        method="POST" 
                                        class="inline-block" 
                                        onsubmit="return confirm('Are you sure you want to permanently delete this article?');"
                                    >
                                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                        <button 
                                            type="submit" 
                                            name="delete" 
                                            class="p-2 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-xl transition inline-flex items-center justify-center shadow-sm"
                                            title="Delete Article"
                                        >
                                            <i class="ri-delete-bin-line text-sm"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="py-12 text-center text-slate-400">
                                <i class="ri-article-line text-4xl text-slate-300 block mb-2"></i>
                                <p class="text-sm font-medium">No blog articles found. Draft your first post above.</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- Interactive Scripts -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        ClassicEditor
            .create(document.querySelector('#content'), {
                toolbar: [
                    'heading', '|', 
                    'bold', 'italic', 'underline', 'link', 'bulletedList', 'numberedList', 'blockQuote', '|',
                    'insertTable', 'undo', 'redo'
                ]
            })
            .catch(error => {
                console.error(error);
            });
    });

    function updateSlugPreview(val) {
        var slug = val.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, '');
        document.getElementById('slug-preview').innerText = '/post/' + (slug || 'your-article-title');
    }

    function previewThumbnail(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                var img = document.getElementById('image-preview');
                img.src = e.target.result;
                img.classList.remove('hidden');
                document.getElementById('upload-placeholder').classList.add('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function filterTable() {
        var input = document.getElementById("table-search");
        var filter = input.value.toLowerCase();
        var rows = document.getElementsByClassName("post-row");

        for (var i = 0; i < rows.length; i++) {
            var titleEl = rows[i].querySelector(".post-title-text");
            if (titleEl) {
                var text = titleEl.textContent || titleEl.innerText;
                if (text.toLowerCase().indexOf(filter) > -1) {
                    rows[i].style.display = "";
                } else {
                    rows[i].style.display = "none";
                }
            }
        }
    }
</script>

<?php include '../includes/admin_footer.php'; ?>