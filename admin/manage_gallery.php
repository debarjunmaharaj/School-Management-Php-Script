<?php
$page_title = 'Manage Photo Gallery';
include '../includes/admin_header.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete'])) {
        $id = $_POST['id'];
        $stmt_img = $conn->prepare("SELECT image_path FROM gallery WHERE id = ?");
        $stmt_img->bind_param("i", $id);
        $stmt_img->execute();
        $res = $stmt_img->get_result()->fetch_assoc();
        $stmt_img->close();

        if ($res && !empty($res['image_path']) && file_exists('../' . $res['image_path'])) {
            unlink('../' . $res['image_path']);
        }

        $stmt = $conn->prepare("DELETE FROM gallery WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    } else {
        $caption = $_POST['caption'] ?? '';
        
        if (isset($_FILES['gallery_image']) && $_FILES['gallery_image']['error'] == 0) {
            $target_dir = '../uploads/gallery/';
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            $filename = uniqid('gal_') . '.' . pathinfo($_FILES['gallery_image']['name'], PATHINFO_EXTENSION);
            $target_file = $target_dir . $filename;
            if (move_uploaded_file($_FILES['gallery_image']['tmp_name'], $target_file)) {
                $image_path = 'uploads/gallery/' . $filename;
                $stmt = $conn->prepare("INSERT INTO gallery (caption, image_path) VALUES (?, ?)");
                $stmt->bind_param("ss", $caption, $image_path);
                $stmt->execute();
                $stmt->close();
            }
        }
    }
}

$gallery_result = $conn->query("SELECT * FROM gallery ORDER BY id DESC");
?>

<div class="max-w-6xl mx-auto space-y-8 py-4">
    <!-- Add Photo -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 lg:p-8 relative">
        <div class="absolute top-0 left-0 right-0 h-1 bg-indigo-600 rounded-t-2xl"></div>
        <div class="mb-6">
            <h3 class="text-lg font-bold text-slate-900">Add New Photo to Gallery</h3>
            <p class="text-xs text-slate-500 mt-1">Upload images with descriptive captions for the homepage slideshow / gallery</p>
        </div>

        <form action="manage_gallery.php" method="POST" enctype="multipart/form-data" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-1.5">
                    <label for="caption" class="text-xs font-semibold text-slate-600 uppercase tracking-wider">Photo Caption</label>
                    <input type="text" name="caption" id="caption" placeholder="e.g. Science Fair 2026 Winners" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm">
                </div>
                <div class="space-y-1.5">
                    <label for="gallery_image" class="text-xs font-semibold text-slate-600 uppercase tracking-wider">Choose Image</label>
                    <input type="file" name="gallery_image" id="gallery_image" class="block w-full text-sm text-slate-500 file:mr-4 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer" required>
                </div>
            </div>
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold py-2.5 px-6 rounded-xl transition">Upload Photo</button>
        </form>
    </div>

    <!-- Gallery Grid -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 lg:p-8">
        <div class="mb-6">
            <h3 class="text-lg font-bold text-slate-900">Current Gallery Photos</h3>
            <p class="text-xs text-slate-500 mt-1">Review or delete photo assets in the gallery</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <?php if ($gallery_result && $gallery_result->num_rows > 0): ?>
                <?php while ($photo = $gallery_result->fetch_assoc()): ?>
                    <div class="border border-slate-150 rounded-2xl overflow-hidden bg-white shadow-sm flex flex-col justify-between">
                        <div class="aspect-video bg-slate-900 overflow-hidden">
                            <img src="../<?= htmlspecialchars($photo['image_path']) ?>" class="w-full h-full object-cover">
                        </div>
                        <div class="p-4 flex-grow flex flex-col justify-between">
                            <p class="text-sm font-semibold text-slate-700 truncate"><?= htmlspecialchars($photo['caption'] ?: 'No Caption') ?></p>
                            <div class="flex items-center justify-end mt-4 pt-2 border-t border-slate-100">
                                <form action="manage_gallery.php" method="POST" onsubmit="return confirm('Delete this photo?');">
                                    <input type="hidden" name="id" value="<?= $photo['id'] ?>">
                                    <button type="submit" name="delete" class="p-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-lg transition"><i class="ri-delete-bin-line text-sm"></i></button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-span-full py-12 text-center text-slate-400">No photos uploaded yet.</div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include '../includes/admin_footer.php'; ?>
