<?php
// FILE: /ss/admin/view_admissions.php

$page_title = 'Admission Applications';
include '../includes/admin_header.php'; // Includes session check and database connection

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
        $message = '
        <div class="p-4 bg-emerald-50 border border-emerald-100 rounded-xl flex items-start gap-3 text-emerald-800 text-sm mb-6 shadow-sm">
            <i class="ri-checkbox-circle-line text-lg text-emerald-600 shrink-0"></i>
            <div>
                <p class="font-semibold">Application Removed</p>
                <p class="text-emerald-600/90 text-xs mt-0.5">The student admission application registry and photo have been completely deleted.</p>
            </div>
        </div>';
    } else {
        $message = '
        <div class="p-4 bg-rose-50 border border-rose-100 rounded-xl flex items-start gap-3 text-rose-800 text-sm mb-6 shadow-sm">
            <i class="ri-error-warning-line text-lg text-rose-600 shrink-0"></i>
            <div>
                <p class="font-semibold">Operation Error</p>
                <p class="text-rose-600/90 text-xs mt-0.5">Could not clean application records from system repositories.</p>
            </div>
        </div>';
    }
    $stmt_delete->close();
}

$admissions_result = $conn->query("SELECT * FROM admissions ORDER BY submitted_at DESC");
?>

<div class="max-w-6xl mx-auto space-y-8 py-4">

    <!-- Render Response Notifications -->
    <?= $message ?>

    <!-- Applications List Wrapper -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 lg:p-8 overflow-hidden">
        
        <div class="mb-6">
            <h3 class="text-lg font-bold text-slate-900">Admission Applications</h3>
            <p class="text-xs text-slate-500 mt-1">Review, manage, or clear inbound student enrollment requests</p>
        </div>

        <div class="overflow-x-auto -mx-6 lg:-mx-8">
            <table class="min-w-full divide-y divide-slate-150">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 lg:px-8 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Photo</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Student Name</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Grade Applying</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Guardian Details</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Submitted On</th>
                        <th class="px-6 lg:px-8 py-3.5 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-100">
                    <?php if (mysqli_num_rows($admissions_result) > 0): ?>
                        <?php while ($app = $admissions_result->fetch_assoc()): ?>
                        <tr class="hover:bg-slate-50/40 transition">
                            
                            <!-- Student Profile Picture -->
                            <td class="px-6 lg:px-8 py-4 whitespace-nowrap">
                                <?php if (!empty($app['profile_pic_path']) && file_exists('../' . $app['profile_pic_path'])): ?>
                                    <img src="../<?= htmlspecialchars($app['profile_pic_path']) ?>" class="h-11 w-11 rounded-full object-cover border-2 border-slate-200 shadow-sm" alt="Student Portrait">
                                <?php else: ?>
                                    <div class="h-11 w-11 rounded-full bg-slate-100 border border-slate-200/50 flex items-center justify-center text-slate-400 font-bold text-xs uppercase" title="No photo uploaded">
                                        <?= substr(htmlspecialchars($app['student_name']), 0, 2) ?>
                                    </div>
                                <?php endif; ?>
                            </td>

                            <!-- Student Name -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-slate-900">
                                <?= htmlspecialchars($app['student_name']) ?>
                            </td>

                            <!-- Grade Applying (Styled Badge) -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <span class="px-2.5 py-1 inline-flex items-center text-xs font-bold rounded-full bg-indigo-50 text-indigo-700 border border-indigo-100/50">
                                    <?= htmlspecialchars($app['grade_applying']) ?>
                                </span>
                            </td>

                            <!-- Guardian details -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                                <div class="font-medium text-slate-800"><?= htmlspecialchars($app['guardian_name']) ?></div>
                                <div class="text-xs text-slate-400 mt-1 flex items-center gap-1">
                                    <i class="ri-phone-line text-slate-400"></i>
                                    <?= htmlspecialchars($app['guardian_phone']) ?>
                                </div>
                            </td>

                            <!-- Submitted Date with clock icon -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                <div class="font-medium text-slate-700"><?= date('M j, Y', strtotime($app['submitted_at'])) ?></div>
                                <div class="text-xs text-slate-400 mt-1 flex items-center gap-1">
                                    <i class="ri-time-line text-slate-400"></i>
                                    <?= date('g:i A', strtotime($app['submitted_at'])) ?>
                                </div>
                            </td>

                            <!-- Deletion Form Action -->
                            <td class="px-6 lg:px-8 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-3">
                                    <form 
                                        action="view_admissions.php" 
                                        method="POST" 
                                        class="inline-block"
                                        onsubmit="return confirm('Are you sure you want to delete this application? This cannot be undone.');"
                                    >
                                        <input type="hidden" name="id" value="<?= $app['id'] ?>">
                                        <button 
                                            type="submit" 
                                            name="delete" 
                                            class="p-2 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-lg transition inline-flex items-center justify-center"
                                            title="Delete Application"
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
                            <td colspan="6" class="px-6 py-12 text-center text-slate-400 text-sm">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <i class="ri-folder-user-line text-3xl text-slate-300"></i>
                                    <p>No admission applications have been logged yet.</p>
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