<?php
$page_title = 'Contact Form Submissions';
include '../includes/admin_header.php'; // Includes session check and database connection

$message = '';

// Handle the deletion of a submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $id_to_delete = $_POST['id'];

    if (!empty($id_to_delete)) {
        $stmt = $conn->prepare("DELETE FROM contact_submissions WHERE id = ?");
        // Bind the integer parameter
        $stmt->bind_param("i", $id_to_delete);
        
        if ($stmt->execute()) {
            $message = '<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">Submission deleted successfully.</div>';
        } else {
            $message = '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">Error: Could not delete the submission.</div>';
        }
        $stmt->close();
    }
}

// Fetch all contact submissions, newest first
$submissions_result = $conn->query("SELECT * FROM contact_submissions ORDER BY submitted_at DESC");
?>

<!-- Display feedback message if one exists -->
<?= $message ?>

<div class="bg-white p-6 rounded-lg shadow-md">
    <h3 class="text-xl font-bold mb-4">Inbox</h3>

    <div class="space-y-6">
        <?php if ($submissions_result && $submissions_result->num_rows > 0): ?>
            <?php while ($sub = $submissions_result->fetch_assoc()): ?>
                <div class="border border-gray-200 rounded-lg p-4 transition-shadow hover:shadow-lg">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="font-semibold text-gray-800"><?= htmlspecialchars($sub['subject']) ?></p>
                            <p class="text-sm text-gray-600">
                                From: <span class="font-medium"><?= htmlspecialchars($sub['name']) ?></span> 
                                <<a href="mailto:<?= htmlspecialchars($sub['email']) ?>" class="text-blue-600 hover:underline"><?= htmlspecialchars($sub['email']) ?></a>>
                            </p>
                            <p class="text-xs text-gray-400 mt-1">
                                Received on: <?= date('F j, Y, g:i A', strtotime($sub['submitted_at'])) ?>
                            </p>
                        </div>
                        <div>
                            <!-- Delete Form for each submission -->
                            <form action="view_contact.php" method="POST" onsubmit="return confirm('Are you sure you want to permanently delete this message?');">
                                <input type="hidden" name="id" value="<?= $sub['id'] ?>">
                                <button type="submit" name="delete" class="text-red-500 hover:text-red-700 font-medium text-sm flex items-center gap-1">
                                    <i class="ri-delete-bin-line"></i> Delete
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <p class="text-gray-700 whitespace-pre-wrap">
                            <?php 
                                // nl2br converts newline characters (\n) into <br> tags
                                // htmlspecialchars prevents XSS attacks
                                echo nl2br(htmlspecialchars($sub['message'])); 
                            ?>
                        </p>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="text-center py-10">
                <i class="ri-inbox-2-line text-5xl text-gray-300"></i>
                <p class="text-gray-500 mt-2">Your inbox is empty.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include '../includes/admin_footer.php'; ?>