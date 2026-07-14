<?php
// FILE: /ss/admin/manage_pages.php

$page_title = 'Manage Pages';
include '../includes/admin_header.php'; // Includes session check and database connection

$message = '';

function create_slug($string) {
   $slug = strtolower($string);
   $slug = preg_replace('/[^a-z0-9-]+/', '-', $slug);
   $slug = preg_replace('/-+/', '-', $slug);
   return trim($slug, '-');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete'])) {
        $id = $_POST['id'];
        $stmt = $conn->prepare("DELETE FROM pages WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $message = '
            <div class="p-4 bg-emerald-50 border border-emerald-100 rounded-xl flex items-start gap-3 text-emerald-800 text-sm mb-6 shadow-sm">
                <i class="ri-checkbox-circle-line text-lg text-emerald-600 shrink-0"></i>
                <div>
                    <p class="font-semibold">Page Deleted</p>
                    <p class="text-emerald-600/90 text-xs mt-0.5">The custom webpage layout and slug records have been permanently erased.</p>
                </div>
            </div>';
        }
    } else {
        $id = $_POST['id'];
        $title = $_POST['title'];
        $content = $_POST['content'];
        $slug = create_slug($title);
        if (empty($id)) {
            $stmt = $conn->prepare("INSERT INTO pages (title, slug, content) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $title, $slug, $content);
            if ($stmt->execute()) {
                $message = '
                <div class="p-4 bg-emerald-50 border border-emerald-100 rounded-xl flex items-start gap-3 text-emerald-800 text-sm mb-6 shadow-sm">
                    <i class="ri-checkbox-circle-line text-lg text-emerald-600 shrink-0"></i>
                    <div>
                        <p class="font-semibold">Page Created Successfully</p>
                        <p class="text-emerald-600/90 text-xs mt-0.5">The new customized route is now globally active.</p>
                    </div>
                </div>';
            }
        } else {
            $stmt = $conn->prepare("UPDATE pages SET title = ?, slug = ?, content = ? WHERE id = ?");
            $stmt->bind_param("sssi", $title, $slug, $content, $id);
            if ($stmt->execute()) {
                $message = '
                <div class="p-4 bg-emerald-50 border border-emerald-100 rounded-xl flex items-start gap-3 text-emerald-800 text-sm mb-6 shadow-sm">
                    <i class="ri-checkbox-circle-line text-lg text-emerald-600 shrink-0"></i>
                    <div>
                        <p class="font-semibold">Page Updated Successfully</p>
                        <p class="text-emerald-600/90 text-xs mt-0.5">All layout alterations have been successfully published.</p>
                    </div>
                </div>';
            }
        }
    }
}

$edit_page = null;
if (isset($_GET['edit'])) {
    $id_to_edit = $_GET['edit'];
    $stmt = $conn->prepare("SELECT * FROM pages WHERE id = ?");
    $stmt->bind_param("i", $id_to_edit);
    $stmt->execute();
    $result = $stmt->get_result();
    $edit_page = $result->fetch_assoc();
}

$pages_result = $conn->query("SELECT id, title, slug FROM pages ORDER BY title ASC");
?>

<!-- Include CKEditor 5 CDN for Rich text editing capabilities -->
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
            <h3 class="text-lg font-bold text-slate-900"><?= $edit_page ? 'Edit Page Layout' : 'Create New Custom Page' ?></h3>
            <p class="text-xs text-slate-500 mt-1">Design structured pages containing full layout copy, HTML parameters, or system descriptions</p>
        </div>

        <form action="manage_pages.php" method="POST" class="space-y-6">
            <input type="hidden" name="id" value="<?= $edit_page['id'] ?? '' ?>">
            
            <!-- Page Title -->
            <div class="space-y-1.5">
                <label for="title" class="text-xs font-semibold text-slate-600 uppercase tracking-wider">Page Title</label>
                <input 
                    type="text" 
                    name="title" 
                    id="title" 
                    value="<?= htmlspecialchars($edit_page['title'] ?? '') ?>" 
                    placeholder="e.g. Terms of Service"
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:bg-white text-sm transition font-medium" 
                    required
                >
            </div>

            <!-- Content (WYSIWYG Rich Editor) -->
            <div class="space-y-1.5">
                <label for="content" class="text-xs font-semibold text-slate-600 uppercase tracking-wider">Content Editor</label>
                <!-- Target element for rich text parser initialization -->
                <textarea 
                    name="content" 
                    id="content" 
                    rows="12" 
                    placeholder="Populate page markup here..."
                    class="w-full"
                ><?= htmlspecialchars($edit_page['content'] ?? '') ?></textarea>
            </div>

            <!-- Control Buttons -->
            <div class="flex items-center gap-2 pt-2">
                <button 
                    type="submit" 
                    class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold py-2.5 px-6 rounded-xl transition shadow-sm active:scale-[0.98]"
                >
                    <?= $edit_page ? 'Update Page Design' : 'Publish New Page' ?>
                </button>
                <?php if ($edit_page): ?>
                    <a 
                        href="manage_pages.php" 
                        class="bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold py-2.5 px-4 rounded-xl transition"
                    >
                        Cancel Edit
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Existing Pages List -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 lg:p-8 overflow-hidden">
        <div class="mb-6">
            <h3 class="text-lg font-bold text-slate-900">Existing Custom Pages</h3>
            <p class="text-xs text-slate-500 mt-1">Review live routes, visual patterns, or terminate legacy URLs</p>
        </div>

        <div class="overflow-x-auto -mx-6 lg:-mx-8">
            <table class="min-w-full divide-y divide-slate-150">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 lg:px-8 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Page Title</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Route URL Slug</th>
                        <th class="px-6 lg:px-8 py-3.5 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-100">
                    <?php if ($pages_result && $pages_result->num_rows > 0): ?>
                        <?php while ($page = $pages_result->fetch_assoc()): ?>
                        <tr class="hover:bg-slate-50/40 transition">
                            <!-- Page Title -->
                            <td class="px-6 lg:px-8 py-4 whitespace-nowrap text-sm font-semibold text-slate-900">
                                <?= htmlspecialchars($page['title']) ?>
                            </td>
                            <!-- Page URL Preview Badge -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                <span class="font-mono text-xs text-indigo-600 bg-indigo-50 border border-indigo-100/50 px-2.5 py-1 rounded-lg inline-flex items-center gap-1.5">
                                    <i class="ri-link text-indigo-400"></i>
                                    /page.php?slug=<?= htmlspecialchars($page['slug']) ?>
                                </span>
                            </td>
                            <!-- Actions Panel -->
                            <td class="px-6 lg:px-8 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-3">
                                    <!-- View Page -->
                                    <a 
                                        href="../page.php?slug=<?= htmlspecialchars($page['slug']) ?>" 
                                        target="_blank" 
                                        class="p-2 bg-slate-50 hover:bg-slate-100 text-slate-600 rounded-lg transition inline-flex items-center justify-center border border-slate-200/50"
                                        title="View Page on Website"
                                    >
                                        <i class="ri-external-link-line text-base"></i>
                                    </a>
                                    <!-- Edit Page -->
                                    <a 
                                        href="manage_pages.php?edit=<?= $page['id'] ?>" 
                                        class="p-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-600 rounded-lg transition inline-flex items-center justify-center"
                                        title="Modify Configuration"
                                    >
                                        <i class="ri-pencil-line text-base"></i>
                                    </a>
                                    <!-- Delete Page Form -->
                                    <form 
                                        action="manage_pages.php" 
                                        method="POST" 
                                        class="inline-block" 
                                        onsubmit="return confirm('Delete this page permanently?');"
                                    >
                                        <input type="hidden" name="id" value="<?= $page['id'] ?>">
                                        <button 
                                            type="submit" 
                                            name="delete" 
                                            class="p-2 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-lg transition inline-flex items-center justify-center"
                                            title="Permanently Delete Page"
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
                                    <i class="ri-pages-line text-3xl text-slate-300"></i>
                                    <p>No pages configured. Publish a new page to activate additional dynamic routes.</p>
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

<?php
include '../includes/admin_footer.php'; // Includes standard layout footer
?>