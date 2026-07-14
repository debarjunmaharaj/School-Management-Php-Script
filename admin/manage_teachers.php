<?php
$page_title = 'Manage Teachers';
include '../includes/admin_header.php';
$upload_dir = '../uploads/teachers/';

$message = '';

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Delete
    if (isset($_POST['delete'])) {
        $id = $_POST['id'];
        // First, get the image path to delete the file
        $stmt = $conn->prepare("SELECT image_url FROM teachers WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            if (file_exists('../' . $row['image_url'])) {
                unlink('../' . $row['image_url']);
            }
        }
        $stmt->close();

        $stmt = $conn->prepare("DELETE FROM teachers WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) $message = '<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">Teacher deleted successfully.</div>';
        else $message = '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">Error deleting record.</div>';
        $stmt->close();
    } 
    // Add/Update
    else {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $subject = $_POST['subject'];
        $experience = $_POST['experience'];
        $education = $_POST['education'];
        $is_featured = isset($_POST['is_featured']) ? 1 : 0;
        $is_leadership = isset($_POST['is_leadership']) ? 1 : 0;
        $image_url = $_POST['existing_image']; // Keep existing image by default

        // Handle file upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $filename = time() . '_' . basename($_FILES["image"]["name"]);
            $target_file = $upload_dir . $filename;
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $image_url = 'uploads/teachers/' . $filename;
                // Delete old image if updating
                if (!empty($id) && !empty($_POST['existing_image']) && file_exists('../' . $_POST['existing_image'])) {
                     unlink('../' . $_POST['existing_image']);
                }
            } else {
                 $message = '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">Error uploading image.</div>';
            }
        }

        if (empty($message)) {
            if (empty($id)) { // Add
                $stmt = $conn->prepare("INSERT INTO teachers (name, subject, experience, education, image_url, is_featured, is_leadership) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("sssssii", $name, $subject, $experience, $education, $image_url, $is_featured, $is_leadership);
                $success_msg = "Teacher added successfully.";
            } else { // Update
                $stmt = $conn->prepare("UPDATE teachers SET name=?, subject=?, experience=?, education=?, image_url=?, is_featured=?, is_leadership=? WHERE id=?");
                $stmt->bind_param("sssssiii", $name, $subject, $experience, $education, $image_url, $is_featured, $is_leadership, $id);
                $success_msg = "Teacher updated successfully.";
            }

            if ($stmt->execute()) $message = '<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">'.$success_msg.'</div>';
            else $message = '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">Error: '.$stmt->error.'</div>';
            $stmt->close();
        }
    }
}

// Fetch data for editing
$edit_teacher = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $result = $conn->query("SELECT * FROM teachers WHERE id = $id");
    $edit_teacher = $result->fetch_assoc();
}

$teachers = $conn->query("SELECT * FROM teachers ORDER BY name ASC");
?>

<?= $message ?>

<!-- Add/Edit Form -->
<div class="bg-white p-8 rounded-lg shadow-md mb-6">
    <h3 class="text-xl font-bold mb-4"><?= $edit_teacher ? 'Edit' : 'Add New' ?> Teacher</h3>
    <form action="manage_teachers.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $edit_teacher['id'] ?? '' ?>">
        <input type="hidden" name="existing_image" value="<?= $edit_teacher['image_url'] ?? '' ?>">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                <input type="text" name="name" id="name" value="<?= htmlspecialchars($edit_teacher['name'] ?? '') ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
            </div>
            <div>
                <label for="subject" class="block text-sm font-medium text-gray-700">Subject / Role</label>
                <input type="text" name="subject" id="subject" value="<?= htmlspecialchars($edit_teacher['subject'] ?? '') ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
            </div>
            <div>
                <label for="experience" class="block text-sm font-medium text-gray-700">Experience</label>
                <input type="text" name="experience" id="experience" value="<?= htmlspecialchars($edit_teacher['experience'] ?? '') ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>
            <div>
                <label for="education" class="block text-sm font-medium text-gray-700">Education</label>
                <input type="text" name="education" id="education" value="<?= htmlspecialchars($edit_teacher['education'] ?? '') ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>
            <div>
                <label for="image" class="block text-sm font-medium text-gray-700">Photo</label>
                <input type="file" name="image" id="image" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-violet-50 file:text-violet-700 hover:file:bg-violet-100">
                <?php if(isset($edit_teacher['image_url'])): ?>
                    <img src="../<?= htmlspecialchars($edit_teacher['image_url']) ?>" alt="Current Image" class="h-16 w-16 mt-2 rounded-full object-cover">
                <?php endif; ?>
            </div>
            <div class="flex items-center space-x-6">
                <div class="flex items-center">
                    <input id="is_featured" name="is_featured" type="checkbox" <?= (isset($edit_teacher['is_featured']) && $edit_teacher['is_featured']) ? 'checked' : '' ?> class="h-4 w-4 text-indigo-600 border-gray-300 rounded">
                    <label for="is_featured" class="ml-2 block text-sm text-gray-900">Featured on Homepage</label>
                </div>
                 <div class="flex items-center">
                    <input id="is_leadership" name="is_leadership" type="checkbox" <?= (isset($edit_teacher['is_leadership']) && $edit_teacher['is_leadership']) ? 'checked' : '' ?> class="h-4 w-4 text-indigo-600 border-gray-300 rounded">
                    <label for="is_leadership" class="ml-2 block text-sm text-gray-900">Is Leadership</label>
                </div>
            </div>
        </div>
        <div class="mt-6">
            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                <?= $edit_teacher ? 'Update' : 'Add' ?> Teacher
            </button>
            <?php if ($edit_teacher): ?>
            <a href="manage_teachers.php" class="ml-2 text-gray-600 hover:text-gray-900">Cancel</a>
            <?php endif; ?>
        </div>
    </form>
</div>

<!-- Teachers List -->
<div class="bg-white p-8 rounded-lg shadow-md">
    <h3 class="text-xl font-bold mb-4">Existing Teachers</h3>
    <table class="min-w-full divide-y divide-gray-200">
        <thead>
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Photo</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            <?php while ($row = $teachers->fetch_assoc()): ?>
            <tr>
                <td class="px-6 py-4"><img src="../<?= htmlspecialchars($row['image_url']) ?>" class="h-10 w-10 rounded-full object-cover"></td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= htmlspecialchars($row['name']) ?></td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($row['subject']) ?></td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    <?php if($row['is_featured']): ?><span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Featured</span><?php endif; ?>
                    <?php if($row['is_leadership']): ?><span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Leadership</span><?php endif; ?>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <a href="manage_teachers.php?edit=<?= $row['id'] ?>" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                    <form action="manage_teachers.php" method="POST" class="inline-block ml-4" onsubmit="return confirm('Are you sure you want to delete this teacher?');">
                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                        <button type="submit" name="delete" class="text-red-600 hover:text-red-900">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include '../includes/admin_footer.php'; ?>