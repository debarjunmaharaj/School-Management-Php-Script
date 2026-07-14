<?php
$page_title = 'Manage News / Blog Posts';
include '../includes/admin_header.php'; // Includes session check and database connection

// Helper function to create a URL-friendly "slug"
function create_slug($string){
   $slug = preg_replace('/[^A-Za-z0-9-]+/', '-', strtolower($string));
   return trim($slug, '-');
}

$message = '';

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Delete post
    if (isset($_POST['delete'])) {
        $id = $_POST['id'];
        $stmt = $conn->prepare("DELETE FROM posts WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $message = '
            <div class="p-4 bg-emerald-50 border border-emerald-100 rounded-xl flex items-start gap-3 text-emerald-800 text-sm mb-6 shadow-sm">
                <i class="ri-checkbox-circle-line text-lg text-emerald-600 shrink-0"></i>
                <div>
                    <p class="font-semibold">Post Erased</p>
                    <p class="text-emerald-600/90 text-xs mt-0.5">The selected blog post has been successfully removed from live registries.</p>
                </div>
            </div>';
        }
    } 
    // Add or Update post
    else {
        $id = $_POST['id'];
        $title = $_POST['title'];
        $content = $_POST['content'];
        $slug = create_slug($title);

        if (empty($id)) { // Add New
            $stmt = $conn->prepare("INSERT INTO posts (title, slug, content) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $title, $slug, $content);
        } else { // Update
            $stmt = $conn->prepare("UPDATE posts SET title = ?, slug = ?, content = ? WHERE id = ?");
            $stmt->bind_param("sssi", $title, $slug, $content, $id);
        }
        if ($stmt->execute()) {
            $message = '
            <div class="p-4 bg-emerald-50 border border-emerald-100 rounded-xl flex items-start gap-3 text-emerald-800 text-sm mb-6 shadow-sm">
                <i class="ri-checkbox-circle-line text-lg text-emerald-600 shrink-0"></i>
                <div>
                    <p class="font-semibold">Post Saved</p>
                    <p class="text-emerald-600/90 text-xs mt-0.5">Your article details have been successfully consolidated and updated.</p>
                </div>
            </div>';
        }
    }
}

// Fetch data for editing (Parameterized for SQL safety)
$edit_post = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $stmt = $conn->prepare("SELECT * FROM posts WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $edit_post = $result->fetch_assoc();
    $stmt->close();
}

$posts = $conn->query("SELECT id, title, slug, created_at FROM posts ORDER BY created_at DESC");
?>

<!-- Include CKEditor 5 CDN for Post Editing -->
<script src="https://cdn.ckeditor.com/ckeditor5/41.1.0/classic/ckeditor.js"></script>
<style>
    /* Styling alignment for the CKEditor wrapper */
    .ck-editor__editable_inline {
        min-height: 280px;
        background-color: #f8fafc !important; /* Matches slate-50 styling */
        border-bottom-left-radius: 0.75rem !important;
        border-bottom-right-radius: 0.75rem !important;
        padding: 0px 18px !important;
    }
    .ck-toolbar {
        background-color: #f1f5f9 !important;
        border-top-left-radius: 0.75rem !important;
        border-top-right-radius: 0.75rem !important;
        border-color: #cbd5e1 !important;
    }
    .ck-editor__editable {
        border-color: #cbd5e1 !important;
    }
    .ck-editor__editable_inline:focus {
        background-color: #ffffff !important;
    }
</style>

<div class="max-w-6xl mx-auto space-y-8 py-4">

    <!-- Render Response Notifications -->
    <?= $message ?>

    <!-- Add/Edit Form -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 lg:p-8 relative">
        <div class="absolute top-0 left-0 right-0 h-1 bg-indigo-600 rounded-t-2xl"></div>
        
        <div class="mb-6">
            <h3 class="text-lg font-bold text-slate-900"><?= $edit_post ? 'Edit Blog Article' : 'Draft New Blog Article' ?></h3>
            <p class="text-xs text-slate-500 mt-1">Publish press releases, news updates, and student achievements onto the blog feed</p>
        </div>

        <form action="manage_posts.php" method="POST" class="space-y-6">
            <input type="hidden" name="id" value="<?= $edit_post['id'] ?? '' ?>">
            
            <!-- Title -->
            <div class="space-y-1.5">
                <label for="title" class="text-xs font-semibold text-slate-600 uppercase tracking-wider">Article Title</label>
                <input 
                    type="text" 
                    name="title" 
                    id="title"
                    value="<?= htmlspecialchars($edit_post['title'] ?? '') ?>" 
                    placeholder="e.g. Science Fair Competition Winners"
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:bg-white text-sm transition font-semibold" 
                    required
                >
            </div>

            <!-- Content Area (CKEditor) -->
            <div class="space-y-1.5">
                <label for="content" class="text-xs font-semibold text-slate-600 uppercase tracking-wider">Article Body Content</label>
                <textarea 
                    name="content" 
                    id="content" 
                    rows="10" 
                    placeholder="Write your article copy here..."
                    class="w-full"
                ><?= htmlspecialchars($edit_post['content'] ?? '') ?></textarea>
            </div>

            <div class="flex items-center gap-2 pt-2">
                <button 
                    type="submit" 
                    class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold py-2.5 px-6 rounded-xl transition shadow-sm active:scale-[0.98]"
                >
                    Publish Post
                </button>
                <?php if ($edit_post): ?>
                    <a 
                        href="manage_posts.php" 
                        class="bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold py-2.5 px-4 rounded-xl transition"
                    >
                        Cancel Edit
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Existing Posts List -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 lg:p-8 overflow-hidden">
        <div class="mb-6">
            <h3 class="text-lg font-bold text-slate-900">Existing Posts</h3>
            <p class="text-xs text-slate-500 mt-1">Review live blog feeds, dates, or manage publishing routes</p>
        </div>

        <div class="overflow-x-auto -mx-6 lg:-mx-8">
            <table class="min-w-full divide-y divide-slate-150">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 lg:px-8 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Article Title & Route</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Publish Date</th>
                        <th class="px-6 lg:px-8 py-3.5 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-100">
                    <?php if ($posts && $posts->num_rows > 0): ?>
                        <?php while ($row = $posts->fetch_assoc()): ?>
                        <tr class="hover:bg-slate-50/40 transition">
                            <!-- Title & Subtitle route info -->
                            <td class="px-6 lg:px-8 py-4">
                                <div class="text-sm font-semibold text-slate-900 max-w-sm truncate" title="<?= htmlspecialchars($row['title']) ?>">
                                    <?= htmlspecialchars($row['title']) ?>
                                </div>
                                <div class="mt-1">
                                    <span class="font-mono text-[10px] text-indigo-600 bg-indigo-50 border border-indigo-100/50 px-2 py-0.5 rounded-md inline-flex items-center gap-1">
                                        <i class="ri-link text-indigo-400"></i>
                                        /post/<?= htmlspecialchars($row['slug']) ?>
                                    </span>
                                </div>
                            </td>
                            <!-- Created Date Tag -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-700">
                                    <i class="ri-calendar-line text-slate-400"></i>
                                    <?= date('M j, Y', strtotime($row['created_at'])) ?>
                                </span>
                            </td>
                            <!-- Action buttons -->
                            <td class="px-6 lg:px-8 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-3">
                                    <!-- View Live -->
                                    <a 
                                        href="../post.php?slug=<?= htmlspecialchars($row['slug']) ?>" 
                                        target="_blank" 
                                        class="p-2 bg-slate-50 hover:bg-slate-100 text-slate-600 rounded-lg transition inline-flex items-center justify-center border border-slate-200/50"
                                        title="View Live Article"
                                    >
                                        <i class="ri-external-link-line text-base"></i>
                                    </a>
                                    <!-- Edit -->
                                    <a 
                                        href="manage_posts.php?edit=<?= $row['id'] ?>" 
                                        class="p-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-600 rounded-lg transition inline-flex items-center justify-center"
                                        title="Edit Article"
                                    >
                                        <i class="ri-pencil-line text-base"></i>
                                    </a>
                                    <!-- Delete -->
                                    <form 
                                        action="manage_posts.php" 
                                        method="POST" 
                                        class="inline-block" 
                                        onsubmit="return confirm('Permanently delete this blog article?');"
                                    >
                                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                        <button 
                                            type="submit" 
                                            name="delete" 
                                            class="p-2 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-lg transition inline-flex items-center justify-center"
                                            title="Delete Article"
                                        >
                                            <i class="ri-delete-bin-line text-base"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="px-6 py-12 text-center text-slate-400 text-sm">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <i class="ri-article-line text-3xl text-slate-300"></i>
                                    <p>No blog posts found. Draft and publish a post to populate the live feed.</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- Initialize CKEditor 5 Script -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        ClassicEditor
            .create(document.querySelector('#content'), {
                toolbar: [
                    'heading', '|', 
                    'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', '|',
                    'insertTable', 'undo', 'redo'
                ]
            })
            .catch(error => {
                console.error(error);
            });
    });
</script>

<?php include '../includes/admin_footer.php'; ?>