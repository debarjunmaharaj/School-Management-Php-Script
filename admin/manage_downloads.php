<?php
$page_title = 'Manage Downloads';
include '../includes/admin_header.php';
require_once '../includes/functions.php';
$upload_dir = '../uploads/documents/';

$message = '';

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Delete
    if (isset($_POST['delete'])) {
        $id = $_POST['id'];
        $stmt = $conn->prepare("SELECT file_path FROM downloads WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            if (file_exists('../' . $row['file_path'])) {
                unlink('../' . $row['file_path']);
            }
        }
        $stmt->close();
        $stmt = $conn->prepare("DELETE FROM downloads WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $message = '
            <div class="p-4 bg-emerald-50 border border-emerald-100 rounded-xl flex items-start gap-3 text-emerald-800 text-sm mb-6 shadow-sm">
                <i class="ri-checkbox-circle-line text-lg text-emerald-600 shrink-0"></i>
                <div>
                    <p class="font-semibold">Deleted Successfully</p>
                    <p class="text-emerald-600/90 text-xs mt-0.5">The document has been permanently deleted from the physical drive and database.</p>
                </div>
            </div>';
        }
        $stmt->close();
    } 
    // Add (Update is not implemented for simplicity)
    else {
        $title = $_POST['title'];
        $file_type = $_POST['file_type'];
        
        if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
            $filename = time() . '_' . basename($_FILES["file"]["name"]);
            $target_file = $upload_dir . $filename;
            if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
                $file_path = 'uploads/documents/' . $filename;
                $file_size = format_file_size($_FILES['file']['size']);

                $stmt = $conn->prepare("INSERT INTO downloads (title, file_path, file_size, file_type) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssss", $title, $file_path, $file_size, $file_type);
                if ($stmt->execute()) {
                    $message = '
                    <div class="p-4 bg-emerald-50 border border-emerald-100 rounded-xl flex items-start gap-3 text-emerald-800 text-sm mb-6 shadow-sm">
                        <i class="ri-checkbox-circle-line text-lg text-emerald-600 shrink-0"></i>
                        <div>
                            <p class="font-semibold">Upload Complete</p>
                            <p class="text-emerald-600/90 text-xs mt-0.5">Your downloadable file has been compiled and cataloged successfully.</p>
                        </div>
                    </div>';
                } else {
                    $message = '
                    <div class="p-4 bg-rose-50 border border-rose-100 rounded-xl flex items-start gap-3 text-rose-800 text-sm mb-6 shadow-sm">
                        <i class="ri-error-warning-line text-lg text-rose-600 shrink-0"></i>
                        <div>
                            <p class="font-semibold">Database Error</p>
                            <p class="text-rose-600/90 text-xs mt-0.5">An error occurred while cataloging document entries into the registry.</p>
                        </div>
                    </div>';
                }
                $stmt->close();
            } else {
                $message = '
                <div class="p-4 bg-rose-50 border border-rose-100 rounded-xl flex items-start gap-3 text-rose-800 text-sm mb-6 shadow-sm">
                    <i class="ri-error-warning-line text-lg text-rose-600 shrink-0"></i>
                    <div>
                        <p class="font-semibold">Storage Failure</p>
                        <p class="text-rose-600/90 text-xs mt-0.5">Failed to write resources onto the directory target folder. Check system write properties.</p>
                    </div>
                </div>';
            }
        } else {
            $message = '
            <div class="p-4 bg-rose-50 border border-rose-100 rounded-xl flex items-start gap-3 text-rose-800 text-sm mb-6 shadow-sm">
                <i class="ri-error-warning-line text-lg text-rose-600 shrink-0"></i>
                <div>
                    <p class="font-semibold">Missing File Parameters</p>
                    <p class="text-rose-600/90 text-xs mt-0.5">No file selected or encountered an upload limitation error from PHP config configurations.</p>
                </div>
            </div>';
        }
    }
}

$downloads = $conn->query("SELECT * FROM downloads ORDER BY title ASC");
?>

<div class="max-w-6xl mx-auto space-y-8 py-4">

    <!-- Render Response Notifications -->
    <?= $message ?>

    <!-- Add Form -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 lg:p-8 relative">
        <div class="absolute top-0 left-0 right-0 h-1 bg-indigo-600 rounded-t-2xl"></div>
        
        <div class="mb-6">
            <h3 class="text-lg font-bold text-slate-900">Add New Downloadable File</h3>
            <p class="text-xs text-slate-500 mt-1">Upload syllabus records, study material PDFs, or Excel trackers for student directories</p>
        </div>

        <form action="manage_downloads.php" method="POST" enctype="multipart/form-data" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- File Title Input -->
                <div class="space-y-1.5">
                    <label for="title" class="text-xs font-semibold text-slate-600 uppercase tracking-wider">File Title</label>
                    <input 
                        type="text" 
                        name="title" 
                        id="title" 
                        placeholder="e.g. Final Syllabus 2026" 
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:bg-white text-sm transition"
                        required
                    >
                </div>

                <!-- File Type Select Input -->
                <div class="space-y-1.5">
                    <label for="file_type" class="text-xs font-semibold text-slate-600 uppercase tracking-wider">File Type</label>
                    <select 
                        name="file_type" 
                        id="file_type" 
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:bg-white text-sm transition appearance-none"
                        required
                    >
                        <option value="pdf">PDF Document</option>
                        <option value="word">Word Document</option>
                        <option value="excel">Excel Sheet</option>
                    </select>
                </div>

                <!-- Custom Drag / Choose File Selector -->
                <div class="space-y-1.5">
                    <label for="file" class="text-xs font-semibold text-slate-600 uppercase tracking-wider">Select File</label>
                    <input 
                        type="file" 
                        name="file" 
                        id="file" 
                        class="block w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition duration-150 cursor-pointer"
                        required
                    >
                </div>
            </div>

            <div class="pt-2">
                <button 
                    type="submit" 
                    class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold py-2.5 px-6 rounded-xl transition shadow-sm active:scale-[0.98]"
                >
                    Upload File
                </button>
            </div>
        </form>
    </div>

    <!-- Downloads List -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 lg:p-8 overflow-hidden">
        <div class="mb-6">
            <h3 class="text-lg font-bold text-slate-900">Existing Downloads</h3>
            <p class="text-xs text-slate-500 mt-1">Review, test-download, or delete current assets stored in the media index</p>
        </div>

        <div class="overflow-x-auto -mx-6 lg:-mx-8">
            <table class="min-w-full divide-y divide-slate-150">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 lg:px-8 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Title</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Size</th>
                        <th class="px-6 lg:px-8 py-3.5 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-100">
                    <?php if ($downloads->num_rows > 0): ?>
                        <?php while ($row = $downloads->fetch_assoc()): 
                            // Conditional badges mapping based on document type
                            $type_classes = "bg-slate-100 text-slate-700";
                            $type_icon = "ri-file-line";
                            switch(strtolower($row['file_type'])) {
                                case 'pdf':
                                    $type_classes = "bg-rose-50 text-rose-700 border border-rose-100/50";
                                    $type_icon = "ri-file-pdf-line";
                                    break;
                                case 'word':
                                    $type_classes = "bg-blue-50 text-blue-700 border border-blue-100/50";
                                    $type_icon = "ri-file-word-line";
                                    break;
                                case 'excel':
                                    $type_classes = "bg-emerald-50 text-emerald-700 border border-emerald-100/50";
                                    $type_icon = "ri-file-excel-line";
                                    break;
                            }
                        ?>
                        <tr class="hover:bg-slate-50/40 transition">
                            <td class="px-6 lg:px-8 py-4 whitespace-nowrap text-sm font-semibold text-slate-900">
                                <?= htmlspecialchars($row['title']) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <span class="px-2.5 py-1 inline-flex items-center gap-1.5 text-xs font-semibold rounded-full uppercase tracking-wide <?= $type_classes ?>">
                                    <i class="<?= $type_icon ?> text-sm"></i>
                                    <?= htmlspecialchars($row['file_type']) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                <?= htmlspecialchars($row['file_size']) ?>
                            </td>
                            <td class="px-6 lg:px-8 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-3">
                                    <!-- Download Button with Icon -->
                                    <a 
                                        href="../<?= htmlspecialchars($row['file_path']) ?>" 
                                        download 
                                        class="p-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-600 rounded-lg transition inline-flex items-center justify-center"
                                        title="Download Asset"
                                    >
                                        <i class="ri-download-2-line text-base"></i>
                                    </a>
                                    <!-- Delete Button Form with Confirmation Dialog -->
                                    <form 
                                        action="manage_downloads.php" 
                                        method="POST" 
                                        class="inline-block" 
                                        onsubmit="return confirm('Are you sure you want to delete this file?');"
                                    >
                                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                        <button 
                                            type="submit" 
                                            name="delete" 
                                            class="p-2 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-lg transition inline-flex items-center justify-center"
                                            title="Delete Asset"
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
                                    <i class="ri-folder-open-line text-3xl text-slate-300"></i>
                                    <p>No downloadable files configured.</p>
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