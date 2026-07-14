<?php
$page_title = 'Manage Announcements';
include '../includes/admin_header.php';

$message = '';

// Handle POST requests (Add/Edit/Delete)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Delete
    if (isset($_POST['delete'])) {
        $id = $_POST['id'];
        $stmt = $conn->prepare("DELETE FROM announcements WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $message = '
            <div class="p-4 bg-emerald-50 border border-emerald-100 rounded-xl flex items-start gap-3 text-emerald-800 text-sm mb-6 shadow-sm">
                <i class="ri-checkbox-circle-line text-lg text-emerald-600 shrink-0"></i>
                <div>
                    <p class="font-semibold">Deleted Successfully</p>
                    <p class="text-emerald-600/90 text-xs mt-0.5">The announcement record has been removed from the system.</p>
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
    }
    // Add or Update
    else {
        $title = $_POST['title'];
        $content = $_POST['content'];
        $id = $_POST['id'];

        if (empty($id)) { // Add New
            $stmt = $conn->prepare("INSERT INTO announcements (title, content) VALUES (?, ?)");
            $stmt->bind_param("ss", $title, $content);
            $success_msg = 'Announcement added successfully!';
        } else { // Update
            $stmt = $conn->prepare("UPDATE announcements SET title = ?, content = ? WHERE id = ?");
            $stmt->bind_param("ssi", $title, $content, $id);
            $success_msg = 'Announcement updated successfully!';
        }

        if ($stmt->execute()) {
            $message = '
            <div class="p-4 bg-emerald-50 border border-emerald-100 rounded-xl flex items-start gap-3 text-emerald-800 text-sm mb-6 shadow-sm">
                <i class="ri-checkbox-circle-line text-lg text-emerald-600 shrink-0"></i>
                <div>
                    <p class="font-semibold">Operation Completed</p>
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
    }
}

// Fetch data for editing if an ID is provided in URL
$edit_announcement = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    // Sanitizing variable path safely
    $stmt = $conn->prepare("SELECT * FROM announcements WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $edit_announcement = $result->fetch_assoc();
}

// Fetch all announcements
$announcements = $conn->query("SELECT * FROM announcements ORDER BY post_date DESC");
?>

<div class="max-w-6xl mx-auto space-y-8 py-4">
    
    <!-- Render Response Notifications -->
    <?= $message ?>

    <!-- Layout Grid: Two columns (Form left/top, Announcements list right/bottom) -->
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-8 items-start">
        
        <!-- Add/Edit Form Column (2/5 size) -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-100 shadow-sm p-6 relative">
            <div class="absolute top-0 left-0 right-0 h-1 bg-indigo-600 rounded-t-2xl"></div>
            
            <div class="mb-5">
                <h3 class="text-lg font-bold text-slate-900"><?= $edit_announcement ? 'Edit' : 'Create' ?> Announcement</h3>
                <p class="text-xs text-slate-500 mt-1">Broadcast new details onto the main notice boards</p>
            </div>

            <form action="manage_announcements.php" method="POST" class="space-y-4">
                <input type="hidden" name="id" value="<?= $edit_announcement['id'] ?? '' ?>">
                
                <!-- Title -->
                <div class="space-y-1.5">
                    <label for="title" class="text-xs font-semibold text-slate-600 uppercase tracking-wider">Title</label>
                    <input 
                        type="text" 
                        name="title" 
                        id="title" 
                        value="<?= htmlspecialchars($edit_announcement['title'] ?? '') ?>" 
                        placeholder="e.g. Scheduled Maintenance"
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:bg-white text-sm transition" 
                        required
                    >
                </div>

                <!-- Content Text Area -->
                <div class="space-y-1.5">
                    <label for="content" class="text-xs font-semibold text-slate-600 uppercase tracking-wider">Content Details</label>
                    <textarea 
                        name="content" 
                        id="content" 
                        rows="5" 
                        placeholder="Write the details of the announcement here..."
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:bg-white text-sm transition" 
                        required
                    ><?= htmlspecialchars($edit_announcement['content'] ?? '') ?></textarea>
                </div>

                <!-- Control Buttons -->
                <div class="flex items-center gap-2 pt-2">
                    <button 
                        type="submit" 
                        class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold py-2.5 px-5 rounded-xl transition shadow-sm active:scale-[0.98]"
                    >
                        <?= $edit_announcement ? 'Update' : 'Publish' ?>
                    </button>
                    <?php if ($edit_announcement): ?>
                        <a 
                            href="manage_announcements.php" 
                            class="bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold py-2.5 px-4 rounded-xl transition"
                        >
                            Cancel
                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <!-- Announcements Table/List Column (3/5 size) -->
        <div class="lg:col-span-3 bg-white rounded-2xl border border-slate-100 shadow-sm p-6 overflow-hidden">
            <div class="mb-5">
                <h3 class="text-lg font-bold text-slate-900">Existing Announcements</h3>
                <p class="text-xs text-slate-500 mt-1">Review, modify, or eliminate published statements</p>
            </div>

            <!-- Modern Card Table Interface -->
            <div class="overflow-x-auto -mx-6">
                <table class="min-w-full divide-y divide-slate-150">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Title</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Posted Date</th>
                            <th class="px-6 py-3.5 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-100">
                        <?php if ($announcements->num_rows > 0): ?>
                            <?php while ($row = $announcements->fetch_assoc()): ?>
                            <tr class="hover:bg-slate-50/40 transition">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-semibold text-slate-900 truncate max-w-xs md:max-w-sm" title="<?= htmlspecialchars($row['title']) ?>">
                                        <?= htmlspecialchars($row['title']) ?>
                                    </div>
                                    <p class="text-xs text-slate-400 mt-0.5 truncate max-w-xs" title="<?= htmlspecialchars($row['content']) ?>">
                                        <?= htmlspecialchars($row['content']) ?>
                                    </p>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-700">
                                        <i class="ri-calendar-line text-slate-400"></i>
                                        <?= date('M j, Y', strtotime($row['post_date'])) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-3">
                                        <a 
                                            href="manage_announcements.php?edit=<?= $row['id'] ?>" 
                                            class="p-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-600 rounded-lg transition inline-flex items-center justify-center"
                                            title="Edit Announcement"
                                        >
                                            <i class="ri-pencil-line text-base"></i>
                                        </a>
                                        <form 
                                            action="manage_announcements.php" 
                                            method="POST" 
                                            class="inline-block" 
                                            onsubmit="return confirm('Are you sure you want to delete this announcement?');"
                                        >
                                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                            <button 
                                                type="submit" 
                                                name="delete" 
                                                class="p-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-lg transition inline-flex items-center justify-center"
                                                title="Delete Announcement"
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
                                        <i class="ri-notification-off-line text-3xl text-slate-300"></i>
                                        <p>No announcements found.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<?php include '../includes/admin_footer.php'; ?>