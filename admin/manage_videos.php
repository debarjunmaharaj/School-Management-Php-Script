<?php
$page_title = 'Manage Video Gallery';
include '../includes/admin_header.php'; // Includes session check and database connection

$message = '';

// Handle form submissions (Add, Edit, Delete)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // --- DELETE VIDEO ---
    if (isset($_POST['delete'])) {
        $id = $_POST['id'];
        $stmt = $conn->prepare("DELETE FROM videos WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $message = '<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">Video deleted successfully.</div>';
        } else {
            $message = '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">Error deleting video.</div>';
        }
        $stmt->close();
    }
    // --- ADD or UPDATE VIDEO ---
    else {
        $id = $_POST['id'];
        $title = $_POST['title'];
        $youtube_video_id = $_POST['youtube_video_id'];

        // Basic validation to ensure fields are not empty
        if (empty($title) || empty($youtube_video_id)) {
             $message = '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">Title and YouTube Video ID are required.</div>';
        } else {
            if (empty($id)) {
                // Add new video
                $stmt = $conn->prepare("INSERT INTO videos (title, youtube_video_id) VALUES (?, ?)");
                $stmt->bind_param("ss", $title, $youtube_video_id);
                $success_msg = "Video added successfully.";
            } else {
                // Update existing video
                $stmt = $conn->prepare("UPDATE videos SET title = ?, youtube_video_id = ? WHERE id = ?");
                $stmt->bind_param("ssi", $title, $youtube_video_id, $id);
                $success_msg = "Video updated successfully.";
            }

            if ($stmt->execute()) {
                $message = '<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">' . $success_msg . '</div>';
            } else {
                $message = '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">Error saving video.</div>';
            }
            $stmt->close();
        }
    }
}

// Fetch a single video for editing if an ID is provided in the URL
$edit_video = null;
if (isset($_GET['edit'])) {
    $id_to_edit = $_GET['edit'];
    $stmt = $conn->prepare("SELECT * FROM videos WHERE id = ?");
    $stmt->bind_param("i", $id_to_edit);
    $stmt->execute();
    $result = $stmt->get_result();
    $edit_video = $result->fetch_assoc();
    $stmt->close();
}

// Fetch all videos to display in the list
$videos_result = $conn->query("SELECT * FROM videos ORDER BY id DESC");
?>

<!-- Display feedback message -->
<?= $message ?>

<!-- Add/Edit Form -->
<div class="bg-white p-8 rounded-lg shadow-md mb-6">
    <h3 class="text-xl font-bold mb-4"><?= $edit_video ? 'Edit Video' : 'Add New Video' ?></h3>
    <form action="manage_videos.php" method="POST" class="space-y-4">
        <input type="hidden" name="id" value="<?= $edit_video['id'] ?? '' ?>">
        
        <div>
            <label for="title" class="block text-sm font-medium text-gray-700">Video Title</label>
            <input type="text" name="title" id="title" value="<?= htmlspecialchars($edit_video['title'] ?? '') ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
        </div>
        
        <div>
            <label for="youtube_video_id" class="block text-sm font-medium text-gray-700">YouTube Video ID</label>
            <input type="text" name="youtube_video_id" id="youtube_video_id" value="<?= htmlspecialchars($edit_video['youtube_video_id'] ?? '') ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="e.g., dQw4w9WgXcQ" required>
            <p class="text-xs text-gray-500 mt-1">From a URL like https://www.youtube.com/watch?v=<strong class="text-red-500">dQw4w9WgXcQ</strong>, the ID is the part in bold.</p>
        </div>
        
        <div>
            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                <?= $edit_video ? 'Update Video' : 'Add Video' ?>
            </button>
            <?php if ($edit_video): ?>
                <a href="manage_videos.php" class="ml-2 text-gray-600 hover:text-gray-900">Cancel Edit</a>
            <?php endif; ?>
        </div>
    </form>
</div>


<!-- Existing Videos List -->
<div class="bg-white p-8 rounded-lg shadow-md">
    <h3 class="text-xl font-bold mb-4">Video Gallery</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        <?php if ($videos_result && $videos_result->num_rows > 0): ?>
            <?php while ($video = $videos_result->fetch_assoc()): ?>
                <div class="border rounded-lg overflow-hidden transition-shadow hover:shadow-xl">
                    <img src="https://img.youtube.com/vi/<?= htmlspecialchars($video['youtube_video_id']) ?>/mqdefault.jpg" alt="Thumbnail" class="w-full h-32 object-cover">
                    <div class="p-4">
                        <p class="font-semibold text-gray-800 truncate"><?= htmlspecialchars($video['title']) ?></p>
                        <p class="text-xs text-gray-500">ID: <?= htmlspecialchars($video['youtube_video_id']) ?></p>
                        <div class="flex justify-end gap-2 mt-3">
                            <a href="manage_videos.php?edit=<?= $video['id'] ?>" class="text-sm text-blue-600 hover:text-blue-900 font-medium">Edit</a>
                            <form action="manage_videos.php" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this video?');">
                                <input type="hidden" name="id" value="<?= $video['id'] ?>">
                                <button type="submit" name="delete" class="text-sm text-red-600 hover:text-red-900 font-medium">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="col-span-full text-center text-gray-500">No videos have been added to the gallery yet.</p>
        <?php endif; ?>
    </div>
</div>

<?php include '../includes/admin_footer.php'; ?>