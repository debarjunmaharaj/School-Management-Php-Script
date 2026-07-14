<?php
$page_title = "Library & Downloads";
include 'includes/header.php'; // Use the new header

// --- LIBRARY SPECIFIC DATA --- //
$downloads_result = mysqli_query($conn, "SELECT * FROM downloads ORDER BY title ASC");
$file_icons = [
    'pdf' => ['icon' => 'ri-file-pdf-2-line', 'color' => 'text-red-500'],
    'excel' => ['icon' => 'ri-file-excel-2-line', 'color' => 'text-green-500'],
    'word' => ['icon' => 'ri-file-word-2-line', 'color' => 'text-blue-500'],
];
?>

<!-- The <main> tag is opened in header.php -->
<div class="bg-white p-8 rounded-lg shadow-md">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php if (mysqli_num_rows($downloads_result) > 0): ?>
            <?php while($download = mysqli_fetch_assoc($downloads_result)):
                $icon_class = $file_icons[$download['file_type']] ?? $file_icons['pdf'];
            ?>
            <div class="border rounded-lg p-4 flex flex-col items-center text-center hover:shadow-lg transition-shadow">
                <i class="<?= $icon_class['icon'] ?> <?= $icon_class['color'] ?> text-6xl mb-3"></i>
                <h3 class="font-bold text-lg text-gray-800"><?= htmlspecialchars($download['title']) ?></h3>
                <p class="text-sm text-gray-500 mb-4"><?= htmlspecialchars($download['file_size']) ?></p>
                <a href="<?= htmlspecialchars($download['file_path']) ?>" download class="mt-auto w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition-colors font-semibold">
                    <i class="ri-download-2-line align-middle"></i> Download
                </a>
            </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="text-gray-500 col-span-full">There are no files available for download at the moment.</p>
        <?php endif; ?>
    </div>
</div>

<?php
include 'includes/footer.php'; // Use the new footer
?>