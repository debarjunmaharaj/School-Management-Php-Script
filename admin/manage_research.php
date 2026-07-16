<?php
$page_title = 'Manage Research & Publications';
include '../includes/admin_header.php';

$message = '';

// Handle CRUD operations
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // --- DELETE RESEARCH ---
    if (isset($_POST['delete'])) {
        $id = $_POST['id'];
        
        // Fetch image path to delete it from disk
        $stmt_img = $conn->prepare("SELECT image_path FROM research_publications WHERE id = ?");
        $stmt_img->bind_param("i", $id);
        $stmt_img->execute();
        $res = $stmt_img->get_result()->fetch_assoc();
        $stmt_img->close();

        if ($res && !empty($res['image_path']) && file_exists('../' . $res['image_path'])) {
            unlink('../' . $res['image_path']);
        }

        $stmt = $conn->prepare("DELETE FROM research_publications WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $message = '
            <div class="p-4 bg-emerald-50 border border-emerald-100 rounded-xl flex items-start gap-3 text-emerald-800 text-sm mb-6 shadow-sm">
                <i class="ri-checkbox-circle-line text-lg text-emerald-600 shrink-0"></i>
                <div>
                    <p class="font-semibold">Publication Deleted</p>
                    <p class="text-emerald-600/90 text-xs mt-0.5">The publication record has been removed.</p>
                </div>
            </div>';
        } else {
            $message = '
            <div class="p-4 bg-rose-50 border border-rose-100 rounded-xl flex items-start gap-3 text-rose-800 text-sm mb-6 shadow-sm">
                <i class="ri-error-warning-line text-lg text-rose-600 shrink-0"></i>
                <div>
                    <p class="font-semibold">Error Deleting Publication</p>
                    <p class="text-rose-600/90 text-xs mt-0.5">Could not process query. Try again later.</p>
                </div>
            </div>';
        }
        $stmt->close();
    }
    // --- ADD or UPDATE RESEARCH ---
    else {
        $id = $_POST['id'];
        $title = $_POST['title'];
        $content = $_POST['content'];
        $volume = $_POST['volume'];
        $year = $_POST['year'];
        $image_path = $_POST['existing_image'] ?? '';

        // Handle Image Upload
        if (isset($_FILES['research_image']) && $_FILES['research_image']['error'] == 0) {
            $target_dir = '../uploads/research/';
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            $filename = uniqid('res_') . '.' . pathinfo($_FILES['research_image']['name'], PATHINFO_EXTENSION);
            $target_file = $target_dir . $filename;
            if (move_uploaded_file($_FILES['research_image']['tmp_name'], $target_file)) {
                // Delete old image if updating
                if (!empty($image_path) && file_exists('../' . $image_path)) {
                    unlink('../' . $image_path);
                }
                $image_path = 'uploads/research/' . $filename;
            }
        }

        if (empty($title) || empty($content)) {
            $message = '
            <div class="p-4 bg-rose-50 border border-rose-100 rounded-xl flex items-start gap-3 text-rose-800 text-sm mb-6 shadow-sm">
                <i class="ri-error-warning-line text-lg text-rose-600 shrink-0"></i>
                <div>
                    <p class="font-semibold">Missing Properties</p>
                    <p class="text-rose-600/90 text-xs mt-0.5">Both Title and Content details are required.</p>
                </div>
            </div>';
        } else {
            if (empty($id)) {
                // Add new publication
                $stmt = $conn->prepare("INSERT INTO research_publications (title, content, volume, year, image_path) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("sssss", $title, $content, $volume, $year, $image_path);
                $success_msg = "Research publication added successfully.";
            } else {
                // Update existing publication
                $stmt = $conn->prepare("UPDATE research_publications SET title = ?, content = ?, volume = ?, year = ?, image_path = ? WHERE id = ?");
                $stmt->bind_param("sssssi", $title, $content, $volume, $year, $image_path, $id);
                $success_msg = "Research publication updated successfully.";
            }

            if ($stmt->execute()) {
                $message = '
                <div class="p-4 bg-emerald-50 border border-emerald-100 rounded-xl flex items-start gap-3 text-emerald-800 text-sm mb-6 shadow-sm">
                    <i class="ri-checkbox-circle-line text-lg text-emerald-600 shrink-0"></i>
                    <div>
                        <p class="font-semibold">Publication Archive Updated</p>
                        <p class="text-emerald-600/90 text-xs mt-0.5">' . htmlspecialchars($success_msg) . '</p>
                    </div>
                </div>';
            } else {
                $message = '
                <div class="p-4 bg-rose-50 border border-rose-100 rounded-xl flex items-start gap-3 text-rose-800 text-sm mb-6 shadow-sm">
                    <i class="ri-error-warning-line text-lg text-rose-600 shrink-0"></i>
                    <div>
                        <p class="font-semibold">Database Error</p>
                        <p class="text-rose-600/90 text-xs mt-0.5">Could not save values into SQL registers.</p>
                    </div>
                </div>';
            }
            $stmt->close();
        }
    }
}

// Fetch a single publication for editing
$edit_pub = null;
if (isset($_GET['edit'])) {
    $id_to_edit = $_GET['edit'];
    $stmt = $conn->prepare("SELECT * FROM research_publications WHERE id = ?");
    $stmt->bind_param("i", $id_to_edit);
    $stmt->execute();
    $edit_pub = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}

// Fetch all publications
$publications_result = $conn->query("SELECT * FROM research_publications ORDER BY id DESC");
?>

<div class="max-w-6xl mx-auto space-y-8 py-4">

    <?= $message ?>

    <!-- Add/Edit Form -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 lg:p-8 relative">
        <div class="absolute top-0 left-0 right-0 h-1 bg-indigo-600 rounded-t-2xl"></div>
        
        <div class="mb-6">
            <h3 class="text-lg font-bold text-slate-900"><?= $edit_pub ? 'Edit Research Publication' : 'Add New Research Publication' ?></h3>
            <p class="text-xs text-slate-500 mt-1">Publish research journals, thesis works, and scientific accomplishments</p>
        </div>

        <form action="manage_research.php" method="POST" enctype="multipart/form-data" class="space-y-6">
            <input type="hidden" name="id" value="<?= $edit_pub['id'] ?? '' ?>">
            <input type="hidden" name="existing_image" value="<?= $edit_pub['image_path'] ?? '' ?>">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Title -->
                <div class="space-y-1.5 md:col-span-2">
                    <label for="title" class="text-xs font-semibold text-slate-600 uppercase tracking-wider">Research Title</label>
                    <input 
                        type="text" 
                        name="title" 
                        id="title" 
                        value="<?= htmlspecialchars($edit_pub['title'] ?? '') ?>" 
                        placeholder="e.g. Impact of Machine Learning on Climate Data Processing"
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:bg-white text-sm transition font-medium" 
                        required
                    >
                </div>

                <!-- Volume -->
                <div class="space-y-1.5">
                    <label for="volume" class="text-xs font-semibold text-slate-600 uppercase tracking-wider">Volume / Journal Name</label>
                    <input 
                        type="text" 
                        name="volume" 
                        id="volume" 
                        value="<?= htmlspecialchars($edit_pub['volume'] ?? '') ?>" 
                        placeholder="e.g. Vol. 14 or DMU Journal"
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:bg-white text-sm transition font-medium" 
                    >
                </div>

                <!-- Year -->
                <div class="space-y-1.5">
                    <label for="year" class="text-xs font-semibold text-slate-600 uppercase tracking-wider">Publication Year</label>
                    <input 
                        type="text" 
                        name="year" 
                        id="year" 
                        value="<?= htmlspecialchars($edit_pub['year'] ?? '') ?>" 
                        placeholder="e.g. 2026"
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:bg-white text-sm transition font-medium" 
                    >
                </div>

                <!-- Image -->
                <div class="space-y-1.5 md:col-span-2">
                    <label for="research_image" class="text-xs font-semibold text-slate-600 uppercase tracking-wider block">Featured Image</label>
                    <input 
                        type="file" 
                        name="research_image" 
                        id="research_image"
                        class="block w-full text-sm text-slate-500 file:mr-4 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition cursor-pointer"
                    >
                    <?php if(!empty($edit_pub['image_path'])): ?>
                    <div class="mt-2 h-20 w-32 rounded overflow-hidden shadow-sm border border-slate-200">
                        <img src="../<?= htmlspecialchars($edit_pub['image_path']) ?>" class="w-full h-full object-cover">
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Content -->
                <div class="space-y-1.5 md:col-span-2">
                    <label for="content" class="text-xs font-semibold text-slate-600 uppercase tracking-wider block font-bold">Research Content Details</label>
                    <textarea 
                        name="content" 
                        id="content" 
                        rows="8" 
                        placeholder="Provide details of the research publications, full thesis writeups, abstracts, or external publication links."
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:bg-white text-sm transition"
                        required
                    ><?= htmlspecialchars($edit_pub['content'] ?? '') ?></textarea>
                </div>
            </div>
            
            <div class="flex items-center gap-2 pt-2">
                <button 
                    type="submit" 
                    class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold py-2.5 px-6 rounded-xl transition shadow-sm active:scale-[0.98]"
                >
                    <?= $edit_pub ? 'Update Publication' : 'Publish Research' ?>
                </button>
                <?php if ($edit_pub): ?>
                    <a 
                        href="manage_research.php" 
                        class="bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold py-2.5 px-4 rounded-xl transition"
                    >
                        Cancel Edit
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Publications List -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 lg:p-8">
        <div class="mb-6">
            <h3 class="text-lg font-bold text-slate-900">Existing Publications</h3>
            <p class="text-xs text-slate-500 mt-1">Review active research items, or remove old archive posts</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="border-b border-slate-100 text-slate-400 font-semibold">
                        <th class="py-3 px-4">Title</th>
                        <th class="py-3 px-4">Volume</th>
                        <th class="py-3 px-4">Year</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700 font-medium">
                    <?php if ($publications_result && $publications_result->num_rows > 0): ?>
                        <?php while ($pub = $publications_result->fetch_assoc()): ?>
                            <tr>
                                <td class="py-4 px-4 font-bold text-slate-800">
                                    <?= htmlspecialchars($pub['title']) ?>
                                </td>
                                <td class="py-4 px-4 text-slate-500">
                                    <?= htmlspecialchars($pub['volume'] ?? 'N/A') ?>
                                </td>
                                <td class="py-4 px-4 text-slate-500">
                                    <?= htmlspecialchars($pub['year'] ?? 'N/A') ?>
                                </td>
                                <td class="py-4 px-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <!-- Edit -->
                                        <a href="manage_research.php?edit=<?= $pub['id'] ?>" class="p-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-600 rounded-lg transition inline-flex items-center justify-center">
                                            <i class="ri-pencil-line text-sm"></i>
                                        </a>
                                        <!-- Delete -->
                                        <form action="manage_research.php" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this publication?');">
                                            <input type="hidden" name="id" value="<?= $pub['id'] ?>">
                                            <button type="submit" name="delete" class="p-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-lg transition inline-flex items-center justify-center">
                                                <i class="ri-delete-bin-line text-sm"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="py-12 text-center text-slate-400">
                                No research publications found.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../includes/admin_footer.php'; ?>
