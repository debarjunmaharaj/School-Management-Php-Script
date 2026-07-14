<?php
$page_title = 'Manage News / Blog Posts';
include '../includes/admin_header.php';

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
        if($stmt->execute()) $message = '<div class="bg-green-100 text-green-700 p-3 rounded mb-4">Post deleted.</div>';
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
        if($stmt->execute()) $message = '<div class="bg-green-100 text-green-700 p-3 rounded mb-4">Post saved.</div>';
    }
}

// Fetch data for editing
$edit_post = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $result = $conn->query("SELECT * FROM posts WHERE id = $id");
    $edit_post = $result->fetch_assoc();
}

$posts = $conn->query("SELECT id, title, slug, created_at FROM posts ORDER BY created_at DESC");
?>

<?= $message ?>

<!-- Add/Edit Form -->
<div class="bg-white p-8 rounded-lg shadow-md mb-6">
    <h3 class="text-xl font-bold mb-4"><?= $edit_post ? 'Edit' : 'Add New' ?> Post</h3>
    <form action="manage_posts.php" method="POST">
        <input type="hidden" name="id" value="<?= $edit_post['id'] ?? '' ?>">
        <div class="mb-4">
            <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
            <input type="text" name="title" value="<?= htmlspecialchars($edit_post['title'] ?? '') ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
        </div>
        <div class="mb-4">
            <label for="content" class="block text-sm font-medium text-gray-700">Content (HTML allowed)</label>
            <textarea name="content" rows="10" class="font-mono mt-1 block w-full rounded-md border-gray-300 shadow-sm" required><?= htmlspecialchars($edit_post['content'] ?? '') ?></textarea>
        </div>
        <div>
            <button type="submit" class="bg-indigo-600 text-white py-2 px-4 rounded hover:bg-indigo-700">Save Post</button>
        </div>
    </form>
</div>

<!-- Existing Posts List -->
<div class="bg-white p-8 rounded-lg shadow-md">
    <h3 class="text-xl font-bold mb-4">Existing Posts</h3>
    <?php while ($row = $posts->fetch_assoc()): ?>
    <div class="flex justify-between items-center p-3 border-b">
        <div>
            <a href="../post.php?slug=<?= $row['slug'] ?>" target="_blank" class="font-bold text-indigo-600 hover:underline"><?= htmlspecialchars($row['title']) ?></a>
            <p class="text-xs text-gray-500">URL: /post/<?= $row['slug'] ?></p>
        </div>
        <div>
            <a href="manage_posts.php?edit=<?= $row['id'] ?>" class="text-green-600 hover:text-green-900 mr-4">Edit</a>
            <form action="manage_posts.php" method="POST" class="inline-block" onsubmit="return confirm('Delete this post?');">
                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                <button type="submit" name="delete" class="text-red-600 hover:text-red-900">Delete</button>
            </form>
        </div>
    </div>
    <?php endwhile; ?>
</div>

<?php include '../includes/admin_footer.php'; ?>