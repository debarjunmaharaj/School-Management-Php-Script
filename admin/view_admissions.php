<?php
$page_title = 'Admission Applications';
include '../includes/admin_header.php';

$message = '';

// Handle deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $id = $_POST['id'];
    
    // First, get the image path to delete the file from the server
    $stmt_select = $conn->prepare("SELECT profile_pic_path FROM admissions WHERE id = ?");
    $stmt_select->bind_param("i", $id);
    $stmt_select->execute();
    $result = $stmt_select->get_result();
    if ($row = $result->fetch_assoc()) {
        if (!empty($row['profile_pic_path']) && file_exists('../' . $row['profile_pic_path'])) {
            unlink('../' . $row['profile_pic_path']);
        }
    }
    $stmt_select->close();

    // Now, delete the record from the database
    $stmt_delete = $conn->prepare("DELETE FROM admissions WHERE id = ?");
    $stmt_delete->bind_param("i", $id);
    if ($stmt_delete->execute()) {
        $message = '<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">Application deleted successfully.</div>';
    } else {
        $message = '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">Error deleting record.</div>';
    }
    $stmt_delete->close();
}

$admissions_result = $conn->query("SELECT * FROM admissions ORDER BY submitted_at DESC");
?>

<?= $message ?>

<div class="bg-white p-6 rounded-lg shadow-md">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Photo</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Student Name</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Grade Applying</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Guardian</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Submitted On</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if (mysqli_num_rows($admissions_result) > 0): ?>
                    <?php while ($app = $admissions_result->fetch_assoc()): ?>
                    <tr>
                        <td class="px-4 py-4"><img src="../<?= htmlspecialchars($app['profile_pic_path']) ?>" class="h-12 w-12 rounded-full object-cover"></td>
                        <td class="px-4 py-4 font-medium text-gray-900"><?= htmlspecialchars($app['student_name']) ?></td>
                        <td class="px-4 py-4 text-sm text-gray-500"><?= htmlspecialchars($app['grade_applying']) ?></td>
                        <td class="px-4 py-4 text-sm text-gray-500"><?= htmlspecialchars($app['guardian_name']) ?><br><span class="text-xs text-gray-400"><?= htmlspecialchars($app['guardian_phone']) ?></span></td>
                        <td class="px-4 py-4 text-sm text-gray-500"><?= date('M j, Y, g:i A', strtotime($app['submitted_at'])) ?></td>
                        <td class="px-4 py-4 text-right">
                            <!-- In a real app, a "View Details" button would open a modal or new page -->
                            <form action="view_admissions.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this application? This cannot be undone.');">
                                <input type="hidden" name="id" value="<?= $app['id'] ?>">
                                <button type="submit" name="delete" class="text-red-600 hover:text-red-900 font-medium">Delete</button>
                            </form>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="6" class="text-center py-8 text-gray-500">No admission applications found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../includes/admin_footer.php'; ?>