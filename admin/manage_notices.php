<?php
$page_title = 'Manage Notice Board';
include '../includes/admin_header.php'; // Includes session check and database connection

$message = '';

// Handle form submissions (Add, Edit, Delete)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // --- DELETE NOTICE ---
    if (isset($_POST['delete'])) {
        $id = $_POST['id'];
        $stmt = $conn->prepare("DELETE FROM notices WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $message = '
            <div class="p-4 bg-emerald-50 border border-emerald-100 rounded-xl flex items-start gap-3 text-emerald-800 text-sm mb-6 shadow-sm">
                <i class="ri-checkbox-circle-line text-lg text-emerald-600 shrink-0"></i>
                <div>
                    <p class="font-semibold">Notice Deleted</p>
                    <p class="text-emerald-600/90 text-xs mt-0.5">The board posting was successfully pulled down and erased.</p>
                </div>
            </div>';
        } else {
            $message = '
            <div class="p-4 bg-rose-50 border border-rose-100 rounded-xl flex items-start gap-3 text-rose-800 text-sm mb-6 shadow-sm">
                <i class="ri-error-warning-line text-lg text-rose-600 shrink-0"></i>
                <div>
                    <p class="font-semibold">Removal Failed</p>
                    <p class="text-rose-600/90 text-xs mt-0.5">An error occurred while cleaning notice parameters from the registry.</p>
                </div>
            </div>';
        }
        $stmt->close();
    }
    // --- ADD or UPDATE NOTICE ---
    else {
        $id = $_POST['id'];
        $title = $_POST['title'];
        $content = $_POST['content'];
        $post_date = $_POST['post_date'];

        // Basic validation
        if (empty($title) || empty($content) || empty($post_date)) {
             $message = '
             <div class="p-4 bg-rose-50 border border-rose-100 rounded-xl flex items-start gap-3 text-rose-800 text-sm mb-6 shadow-sm">
                 <i class="ri-error-warning-line text-lg text-rose-600 shrink-0"></i>
                 <div>
                     <p class="font-semibold">Incomplete Parameters</p>
                     <p class="text-rose-600/90 text-xs mt-0.5">Please fill out all input fields before updating the notice board.</p>
                 </div>
             </div>';
        } else {
            if (empty($id)) {
                // Add new notice
                $stmt = $conn->prepare("INSERT INTO notices (title, content, post_date) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $title, $content, $post_date);
                $success_msg = "Notice added successfully.";
            } else {
                // Update existing notice
                $stmt = $conn->prepare("UPDATE notices SET title = ?, content = ?, post_date = ? WHERE id = ?");
                $stmt->bind_param("sssi", $title, $content, $post_date, $id);
                $success_msg = "Notice updated successfully.";
            }

            if ($stmt->execute()) {
                $message = '
                <div class="p-4 bg-emerald-50 border border-emerald-100 rounded-xl flex items-start gap-3 text-emerald-800 text-sm mb-6 shadow-sm">
                    <i class="ri-checkbox-circle-line text-lg text-emerald-600 shrink-0"></i>
                    <div>
                        <p class="font-semibold">Notice Board Updated</p>
                        <p class="text-emerald-600/90 text-xs mt-0.5">' . htmlspecialchars($success_msg) . '</p>
                    </div>
                </div>';
            } else {
                $message = '
                <div class="p-4 bg-rose-50 border border-rose-100 rounded-xl flex items-start gap-3 text-rose-800 text-sm mb-6 shadow-sm">
                    <i class="ri-error-warning-line text-lg text-rose-600 shrink-0"></i>
                    <div>
                        <p class="font-semibold">Database Execution Error</p>
                        <p class="text-rose-600/90 text-xs mt-0.5">Notice properties could not be updated in the system database.</p>
                    </div>
                </div>';
            }
            $stmt->close();
        }
    }
}

// Fetch a single notice for editing if an ID is provided in the URL
$edit_notice = null;
if (isset($_GET['edit'])) {
    $id_to_edit = $_GET['edit'];
    $stmt = $conn->prepare("SELECT * FROM notices WHERE id = ?");
    $stmt->bind_param("i", $id_to_edit);
    $stmt->execute();
    $result = $stmt->get_result();
    $edit_notice = $result->fetch_assoc();
    $stmt->close();
}

// Fetch all notices to display in the list
$notices_result = $conn->query("SELECT * FROM notices ORDER BY post_date DESC");
?>

<div class="max-w-6xl mx-auto space-y-8 py-4">

    <!-- Display feedback message -->
    <?= $message ?>

    <!-- Add/Edit Form -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 lg:p-8 relative">
        <div class="absolute top-0 left-0 right-0 h-1 bg-indigo-600 rounded-t-2xl"></div>
        
        <div class="mb-6">
            <h3 class="text-lg font-bold text-slate-900"><?= $edit_notice ? 'Edit Active Notice' : 'Post New Notice' ?></h3>
            <p class="text-xs text-slate-500 mt-1">Publish bulletins, structural rule updates, and semester notifications</p>
        </div>

        <form action="manage_notices.php" method="POST" class="space-y-6">
            <input type="hidden" name="id" value="<?= $edit_notice['id'] ?? '' ?>">
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Title (2 cols) -->
                <div class="md:col-span-2 space-y-1.5">
                    <label for="title" class="text-xs font-semibold text-slate-600 uppercase tracking-wider">Notice Title</label>
                    <input 
                        type="text" 
                        name="title" 
                        id="title" 
                        value="<?= htmlspecialchars($edit_notice['title'] ?? '') ?>" 
                        placeholder="e.g. Mid-Term Examination Schedule"
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:bg-white text-sm transition" 
                        required
                    >
                </div>
                
                <!-- Date (1 col) -->
                <div class="space-y-1.5">
                    <label for="post_date" class="text-xs font-semibold text-slate-600 uppercase tracking-wider">Publish Date</label>
                    <input 
                        type="date" 
                        name="post_date" 
                        id="post_date" 
                        value="<?= htmlspecialchars($edit_notice['post_date'] ?? date('Y-m-d')) ?>" 
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:bg-white text-sm transition" 
                        required
                    >
                </div>
            </div>

            <!-- Content -->
            <div class="space-y-1.5">
                <label for="content" class="text-xs font-semibold text-slate-600 uppercase tracking-wider">Notice Content Details</label>
                <textarea 
                    name="content" 
                    id="content" 
                    rows="4" 
                    placeholder="Provide full description details about this bulletin..."
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:bg-white text-sm transition" 
                    required
                ><?= htmlspecialchars($edit_notice['content'] ?? '') ?></textarea>
            </div>
            
            <div class="flex items-center gap-2 pt-2">
                <button 
                    type="submit" 
                    class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold py-2.5 px-6 rounded-xl transition shadow-sm active:scale-[0.98]"
                >
                    <?= $edit_notice ? 'Update Notice Bulletin' : 'Publish Bulletin' ?>
                </button>
                <?php if ($edit_notice): ?>
                    <a 
                        href="manage_notices.php" 
                        class="bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold py-2.5 px-4 rounded-xl transition"
                    >
                        Cancel Edit
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Existing Notices List -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 lg:p-8 overflow-hidden">
        <div class="mb-6">
            <h3 class="text-lg font-bold text-slate-900">Current Notices</h3>
            <p class="text-xs text-slate-500 mt-1">Review active bulletins running across homepage noticeboard interfaces</p>
        </div>

        <div class="overflow-x-auto -mx-6 lg:-mx-8">
            <table class="min-w-full divide-y divide-slate-150">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 lg:px-8 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Title</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Details</th>
                        <th class="px-6 lg:px-8 py-3.5 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-100">
                    <?php if ($notices_result && $notices_result->num_rows > 0): ?>
                        <?php while ($notice = $notices_result->fetch_assoc()): ?>
                        <tr class="hover:bg-slate-50/40 transition">
                            <!-- Date Tag -->
                            <td class="px-6 lg:px-8 py-4 whitespace-nowrap text-sm text-slate-500">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-700">
                                    <i class="ri-calendar-line text-slate-400"></i>
                                    <?= date('M j, Y', strtotime($notice['post_date'])) ?>
                                </span>
                            </td>
                            <!-- Notice Title -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-slate-900 max-w-xs truncate" title="<?= htmlspecialchars($notice['title']) ?>">
                                <?= htmlspecialchars($notice['title']) ?>
                            </td>
                            <!-- Notice Content -->
                            <td class="px-6 py-4 text-sm text-slate-500 max-w-md truncate" title="<?= htmlspecialchars($notice['content']) ?>">
                                <?= htmlspecialchars($notice['content']) ?>
                            </td>
                            <!-- Actions Row -->
                            <td class="px-6 lg:px-8 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-3">
                                    <a 
                                        href="manage_notices.php?edit=<?= $notice['id'] ?>" 
                                        class="p-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-600 rounded-lg transition inline-flex items-center justify-center"
                                        title="Edit Notice"
                                    >
                                        <i class="ri-pencil-line text-base"></i>
                                    </a>
                                    <form 
                                        action="manage_notices.php" 
                                        method="POST" 
                                        class="inline-block" 
                                        onsubmit="return confirm('Are you sure you want to delete this notice?');"
                                    >
                                        <input type="hidden" name="id" value="<?= $notice['id'] ?>">
                                        <button 
                                            type="submit" 
                                            name="delete" 
                                            class="p-2 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-lg transition inline-flex items-center justify-center"
                                            title="Delete Notice"
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
                            <td colspan="4" class="px-6 py-12 text-center text-slate-400 text-sm">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <i class="ri-pushpin-line text-3xl text-slate-300"></i>
                                    <p>No notices found. Publish an item to display on noticeboards.</p>
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