<?php
// FILE: /ss/admin/view_contact.php

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
            $message = '
            <div class="p-4 bg-emerald-50 border border-emerald-100 rounded-xl flex items-start gap-3 text-emerald-800 text-sm mb-6 shadow-sm">
                <i class="ri-checkbox-circle-line text-lg text-emerald-600 shrink-0"></i>
                <div>
                    <p class="font-semibold">Message Erased</p>
                    <p class="text-emerald-600/90 text-xs mt-0.5">The selected contact submission record has been cleared from active registers.</p>
                </div>
            </div>';
        } else {
            $message = '
            <div class="p-4 bg-rose-50 border border-rose-100 rounded-xl flex items-start gap-3 text-rose-800 text-sm mb-6 shadow-sm">
                <i class="ri-error-warning-line text-lg text-rose-600 shrink-0"></i>
                <div>
                    <p class="font-semibold">Error Deleting Record</p>
                    <p class="text-rose-600/90 text-xs mt-0.5">Please check system database permissions or try again.</p>
                </div>
            </div>';
        }
        $stmt->close();
    }
}

// Fetch all contact submissions, newest first
$submissions_result = $conn->query("SELECT * FROM contact_submissions ORDER BY submitted_at DESC");
?>

<div class="max-w-6xl mx-auto space-y-8 py-4">

    <!-- Display feedback message if one exists -->
    <?= $message ?>

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 lg:p-8">
        
        <div class="mb-6">
            <h3 class="text-lg font-bold text-slate-900">Contact Submissions Inbox</h3>
            <p class="text-xs text-slate-500 mt-1">Review feedback, query messages, or complaints submitted via your public contact form</p>
        </div>

        <div class="space-y-6">
            <?php if ($submissions_result && $submissions_result->num_rows > 0): ?>
                <?php while ($sub = $submissions_result->fetch_assoc()): ?>
                    <div class="border border-slate-200/60 rounded-2xl p-5 hover:shadow-md transition duration-200 bg-white">
                        <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4">
                            <!-- Sender & Meta Info -->
                            <div class="space-y-1">
                                <span class="px-2.5 py-0.5 inline-flex items-center text-[10px] font-bold rounded-full bg-slate-150 text-slate-700 uppercase tracking-wide">
                                    Subject
                                </span>
                                <p class="text-base font-bold text-slate-900 mt-1"><?= htmlspecialchars($sub['subject']) ?></p>
                                
                                <div class="flex flex-wrap items-center gap-x-4 gap-y-1 pt-1.5 text-xs text-slate-500">
                                    <span class="inline-flex items-center gap-1">
                                        <i class="ri-user-line text-slate-400"></i>
                                        Sender: <strong class="font-semibold text-slate-800"><?= htmlspecialchars($sub['name']) ?></strong>
                                    </span>
                                    <span class="inline-flex items-center gap-1.5">
                                        <i class="ri-mail-line text-slate-400"></i>
                                        <a href="mailto:<?= htmlspecialchars($sub['email']) ?>" class="text-indigo-600 hover:underline inline-flex items-center gap-0.5 font-medium">
                                            <?= htmlspecialchars($sub['email']) ?>
                                            <i class="ri-arrow-right-up-line text-[10px]"></i>
                                        </a>
                                    </span>
                                </div>
                                <p class="text-[10px] text-slate-400 font-medium inline-flex items-center gap-1 pt-1">
                                    <i class="ri-calendar-line text-slate-300"></i>
                                    Received on: <?= date('F j, Y, g:i A', strtotime($sub['submitted_at'])) ?>
                                </p>
                            </div>

                            <!-- Delete Action -->
                            <div class="shrink-0 self-start">
                                <form 
                                    action="view_contact.php" 
                                    method="POST" 
                                    onsubmit="return confirm('Are you sure you want to permanently delete this message?');"
                                >
                                    <input type="hidden" name="id" value="<?= $sub['id'] ?>">
                                    <button 
                                        type="submit" 
                                        name="delete" 
                                        class="p-2 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-lg transition inline-flex items-center justify-center gap-1 text-xs font-semibold"
                                        title="Delete Message"
                                    >
                                        <i class="ri-delete-bin-line text-base"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Message Body block container -->
                        <div class="mt-4 pt-4 border-t border-slate-100">
                            <div class="p-4 bg-slate-50 border border-slate-150 rounded-xl text-slate-700 text-sm leading-relaxed whitespace-pre-wrap">
                                <?php 
                                    // nl2br converts newline characters (\n) into <br> tags
                                    // htmlspecialchars prevents XSS attacks
                                    echo nl2br(htmlspecialchars($sub['message'])); 
                                ?>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <!-- Empty inbox state -->
                <div class="py-16 text-center text-slate-400 text-sm border border-dashed border-slate-200 rounded-2xl">
                    <div class="flex flex-col items-center justify-center gap-2">
                        <i class="ri-inbox-archive-line text-4xl text-slate-300"></i>
                        <p class="font-semibold text-slate-500">Inbox Empty</p>
                        <p class="text-slate-400 text-xs">There are no contact form submissions currently logged.</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

</div>

<?php include '../includes/admin_footer.php'; ?>