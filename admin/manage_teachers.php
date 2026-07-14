<?php
$page_title = 'Manage Teachers';
include '../includes/admin_header.php'; // Includes session check and database connection
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
        if ($stmt->execute()) {
            $message = '
            <div class="p-4 bg-emerald-50 border border-emerald-100 rounded-xl flex items-start gap-3 text-emerald-800 text-sm mb-6 shadow-sm">
                <i class="ri-checkbox-circle-line text-lg text-emerald-600 shrink-0"></i>
                <div>
                    <p class="font-semibold">Teacher Removed</p>
                    <p class="text-emerald-600/90 text-xs mt-0.5">The profile has been successfully deleted from active directory modules.</p>
                </div>
            </div>';
        } else {
            $message = '
            <div class="p-4 bg-rose-50 border border-rose-100 rounded-xl flex items-start gap-3 text-rose-800 text-sm mb-6 shadow-sm">
                <i class="ri-error-warning-line text-lg text-rose-600 shrink-0"></i>
                <div>
                    <p class="font-semibold">Error Deleting Record</p>
                    <p class="text-rose-600/90 text-xs mt-0.5">Please check system permissions or try again.</p>
                </div>
            </div>';
        }
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
                 $message = '
                 <div class="p-4 bg-rose-50 border border-rose-100 rounded-xl flex items-start gap-3 text-rose-800 text-sm mb-6 shadow-sm">
                     <i class="ri-error-warning-line text-lg text-rose-600 shrink-0"></i>
                     <div>
                         <p class="font-semibold">Media Upload Error</p>
                         <p class="text-rose-600/90 text-xs mt-0.5">An error occurred while moving the uploaded profile file to storage directories.</p>
                     </div>
                 </div>';
            }
        }

        if (empty($message)) {
            if (empty($id)) { // Add
                $stmt = $conn->prepare("INSERT INTO teachers (name, subject, experience, education, image_url, is_featured, is_leadership) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("sssssii", $name, $subject, $experience, $education, $image_url, $is_featured, $is_leadership);
                $success_msg = "Teacher profile added successfully.";
            } else { // Update
                $stmt = $conn->prepare("UPDATE teachers SET name=?, subject=?, experience=?, education=?, image_url=?, is_featured=?, is_leadership=? WHERE id=?");
                $stmt->bind_param("sssssiii", $name, $subject, $experience, $education, $image_url, $is_featured, $is_leadership, $id);
                $success_msg = "Teacher profile updated successfully.";
            }

            if ($stmt->execute()) {
                $message = '
                <div class="p-4 bg-emerald-50 border border-emerald-100 rounded-xl flex items-start gap-3 text-emerald-800 text-sm mb-6 shadow-sm">
                    <i class="ri-checkbox-circle-line text-lg text-emerald-600 shrink-0"></i>
                    <div>
                        <p class="font-semibold">Directory Updated</p>
                        <p class="text-emerald-600/90 text-xs mt-0.5">' . htmlspecialchars($success_msg) . '</p>
                    </div>
                </div>';
            } else {
                $message = '
                <div class="p-4 bg-rose-50 border border-rose-100 rounded-xl flex items-start gap-3 text-rose-800 text-sm mb-6 shadow-sm">
                    <i class="ri-error-warning-line text-lg text-rose-600 shrink-0"></i>
                    <div>
                        <p class="font-semibold">Database Error</p>
                        <p class="text-rose-600/90 text-xs mt-0.5">' . htmlspecialchars($stmt->error) . '</p>
                    </div>
                </div>';
            }
            $stmt->close();
        }
    }
}

// Fetch data for editing (Parameterized for SQL safety)
$edit_teacher = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $stmt = $conn->prepare("SELECT * FROM teachers WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $edit_teacher = $result->fetch_assoc();
    $stmt->close();
}

$teachers = $conn->query("SELECT * FROM teachers ORDER BY name ASC");
?>

<div class="max-w-6xl mx-auto space-y-8 py-4">

    <!-- Render Response Notifications -->
    <?= $message ?>

    <!-- Add/Edit Form -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 lg:p-8 relative">
        <div class="absolute top-0 left-0 right-0 h-1 bg-indigo-600 rounded-t-2xl"></div>
        
        <div class="mb-6">
            <h3 class="text-lg font-bold text-slate-900"><?= $edit_teacher ? 'Edit Teacher Profile' : 'Add New Teacher' ?></h3>
            <p class="text-xs text-slate-500 mt-1">Configure subjects, educational credentials, role profiles, and homepage status for faculty members</p>
        </div>

        <form action="manage_teachers.php" method="POST" enctype="multipart/form-data" class="space-y-6">
            <input type="hidden" name="id" value="<?= $edit_teacher['id'] ?? '' ?>">
            <input type="hidden" name="existing_image" value="<?= $edit_teacher['image_url'] ?? '' ?>">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div class="space-y-1.5">
                    <label for="name" class="text-xs font-semibold text-slate-600 uppercase tracking-wider">Full Name</label>
                    <input 
                        type="text" 
                        name="name" 
                        id="name" 
                        value="<?= htmlspecialchars($edit_teacher['name'] ?? '') ?>" 
                        placeholder="e.g. Dr. Arthur Pendelton"
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:bg-white text-sm transition" 
                        required
                    >
                </div>

                <!-- Subject / Role -->
                <div class="space-y-1.5">
                    <label for="subject" class="text-xs font-semibold text-slate-600 uppercase tracking-wider">Subject / Role</label>
                    <input 
                        type="text" 
                        name="subject" 
                        id="subject" 
                        value="<?= htmlspecialchars($edit_teacher['subject'] ?? '') ?>" 
                        placeholder="e.g. Mathematics Instructor"
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:bg-white text-sm transition" 
                        required
                    >
                </div>

                <!-- Experience -->
                <div class="space-y-1.5">
                    <label for="experience" class="text-xs font-semibold text-slate-600 uppercase tracking-wider">Experience</label>
                    <input 
                        type="text" 
                        name="experience" 
                        id="experience" 
                        value="<?= htmlspecialchars($edit_teacher['experience'] ?? '') ?>" 
                        placeholder="e.g. 8 Years Academic Practice"
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:bg-white text-sm transition"
                    >
                </div>

                <!-- Education -->
                <div class="space-y-1.5">
                    <label for="education" class="text-xs font-semibold text-slate-600 uppercase tracking-wider">Education</label>
                    <input 
                        type="text" 
                        name="education" 
                        id="education" 
                        value="<?= htmlspecialchars($edit_teacher['education'] ?? '') ?>" 
                        placeholder="e.g. Ph.D. in Applied Mathematics"
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:bg-white text-sm transition"
                    >
                </div>

                <!-- Photo Upload Slot -->
                <div class="space-y-3 p-4 rounded-xl border border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="space-y-1.5 flex-1">
                        <label for="image" class="text-xs font-semibold text-slate-600 uppercase tracking-wider block">Faculty Photo</label>
                        <input 
                            type="file" 
                            name="image" 
                            id="image" 
                            class="block w-full text-sm text-slate-500 file:mr-4 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition cursor-pointer"
                        >
                    </div>
                    <?php if (isset($edit_teacher['image_url']) && !empty($edit_teacher['image_url'])): ?>
                        <div class="shrink-0">
                            <img src="../<?= htmlspecialchars($edit_teacher['image_url']) ?>" alt="Current Avatar" class="h-14 w-14 rounded-full object-cover border-2 border-indigo-500 shadow-sm">
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Featured / Leadership Checkboxes -->
                <div class="flex items-center gap-6 p-4 rounded-xl border border-slate-100 bg-slate-50/50">
                    <!-- Featured -->
                    <div class="flex items-center">
                        <input 
                            id="is_featured" 
                            name="is_featured" 
                            type="checkbox" 
                            <?= (isset($edit_teacher['is_featured']) && $edit_teacher['is_featured']) ? 'checked' : '' ?> 
                            class="h-4 w-4 text-indigo-600 focus:ring-indigo-500/20 border-slate-300 rounded accent-indigo-600"
                        >
                        <label for="is_featured" class="ml-2 block text-sm font-semibold text-slate-700 select-none">Featured on Homepage</label>
                    </div>
                    <!-- Leadership -->
                    <div class="flex items-center">
                        <input 
                            id="is_leadership" 
                            name="is_leadership" 
                            type="checkbox" 
                            <?= (isset($edit_teacher['is_leadership']) && $edit_teacher['is_leadership']) ? 'checked' : '' ?> 
                            class="h-4 w-4 text-indigo-600 focus:ring-indigo-500/20 border-slate-300 rounded accent-indigo-600"
                        >
                        <label for="is_leadership" class="ml-2 block text-sm font-semibold text-slate-700 select-none">Is Leadership</label>
                    </div>
                </div>
            </div>

            <!-- Controls -->
            <div class="flex items-center gap-2 pt-2">
                <button 
                    type="submit" 
                    class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold py-2.5 px-6 rounded-xl transition shadow-sm active:scale-[0.98]"
                >
                    <?= $edit_teacher ? 'Update Faculty Profile' : 'Add Teacher Profile' ?>
                </button>
                <?php if ($edit_teacher): ?>
                    <a 
                        href="manage_teachers.php" 
                        class="bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold py-2.5 px-4 rounded-xl transition"
                    >
                        Cancel
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Teachers List -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 lg:p-8 overflow-hidden">
        <div class="mb-6">
            <h3 class="text-lg font-bold text-slate-900">Existing Teachers</h3>
            <p class="text-xs text-slate-500 mt-1">Review, categorize, or delete registered school faculty member records</p>
        </div>

        <div class="overflow-x-auto -mx-6 lg:-mx-8">
            <table class="min-w-full divide-y divide-slate-150">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 lg:px-8 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Photo</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Role / Subject</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 lg:px-8 py-3.5 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-100">
                    <?php if ($teachers && $teachers->num_rows > 0): ?>
                        <?php while ($row = $teachers->fetch_assoc()): ?>
                        <tr class="hover:bg-slate-50/40 transition">
                            <!-- Image/Photo -->
                            <td class="px-6 lg:px-8 py-4 whitespace-nowrap">
                                <?php if (!empty($row['image_url'])): ?>
                                    <img src="../<?= htmlspecialchars($row['image_url']) ?>" class="h-10 w-10 rounded-full object-cover border-2 border-slate-200 shadow-sm" alt="Faculty Avatar">
                                <?php else: ?>
                                    <div class="h-10 w-10 rounded-full bg-slate-100 border border-slate-200/50 flex items-center justify-center text-slate-400 font-bold text-sm uppercase">
                                        <?= substr(htmlspecialchars($row['name']), 0, 2) ?>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <!-- Name -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-slate-900">
                                <?= htmlspecialchars($row['name']) ?>
                            </td>
                            <!-- Subject -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                <?= htmlspecialchars($row['subject']) ?>
                            </td>
                            <!-- Status Badges -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <div class="flex items-center gap-1.5">
                                    <?php if ($row['is_featured']): ?>
                                        <span class="px-2.5 py-0.5 inline-flex items-center gap-1 text-xs font-semibold rounded-full bg-emerald-50 text-emerald-700 border border-emerald-100/50 uppercase tracking-wide">
                                            Featured
                                        </span>
                                    <?php endif; ?>
                                    <?php if ($row['is_leadership']): ?>
                                        <span class="px-2.5 py-0.5 inline-flex items-center gap-1 text-xs font-semibold rounded-full bg-blue-50 text-blue-700 border border-blue-100/50 uppercase tracking-wide">
                                            Leadership
                                        </span>
                                    <?php endif; ?>
                                    <?php if (!$row['is_featured'] && !$row['is_leadership']): ?>
                                        <span class="text-xs font-medium text-slate-400">—</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <!-- Actions Panel -->
                            <td class="px-6 lg:px-8 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-3">
                                    <a 
                                        href="manage_teachers.php?edit=<?= $row['id'] ?>" 
                                        class="p-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-600 rounded-lg transition inline-flex items-center justify-center"
                                        title="Edit Profile"
                                    >
                                        <i class="ri-pencil-line text-base"></i>
                                    </a>
                                    <form 
                                        action="manage_teachers.php" 
                                        method="POST" 
                                        class="inline-block" 
                                        onsubmit="return confirm('Are you sure you want to delete this teacher?');"
                                    >
                                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                        <button 
                                            type="submit" 
                                            name="delete" 
                                            class="p-2 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-lg transition inline-flex items-center justify-center"
                                            title="Delete Profile"
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
                            <td colspan="5" class="px-6 py-12 text-center text-slate-400 text-sm">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <i class="ri-contacts-book-line text-3xl text-slate-300"></i>
                                    <p>No teachers listed in directory indexes.</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<?php include '../includes/admin_footer.php'; ?>