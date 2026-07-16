<?php
require_once 'config/db.php';
require_once 'includes/functions.php';

if (!isset($_GET['id'])) {
    http_response_code(404);
    $page_title = "Error 404";
    include 'includes/header.php';
    echo '<div class="text-center py-20"><h2 class="text-2xl font-bold">Research Not Found</h2><p>No research was specified.</p></div>';
    include 'includes/footer.php';
    exit();
}

$id = intval($_GET['id']);

$stmt = $conn->prepare("SELECT * FROM research_publications WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$pub = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$pub) {
    http_response_code(404);
    $page_title = "Error 404";
    include 'includes/header.php';
    echo '<div class="text-center py-20"><h2 class="text-2xl font-bold">Research Not Found</h2><p>The research publication you are looking for does not exist.</p><a href="/" class="text-blue-600 hover:underline mt-4 inline-block">← Go to Homepage</a></div>';
    include 'includes/footer.php';
    exit();
}

$page_title = htmlspecialchars($pub['title']);
include 'includes/header.php';
?>

<div class="max-w-4xl mx-auto bg-white p-8 md:p-12 rounded-2xl shadow-sm border border-slate-100 mt-6 mb-12">
    <!-- Header -->
    <div class="border-b border-slate-100 pb-6 mb-8">
        <div class="flex items-center gap-3 text-xs font-bold text-amber-600 uppercase tracking-wider mb-2">
            <span><?= htmlspecialchars($pub['volume'] ?? 'Research Journal') ?></span>
            <?php if (!empty($pub['year'])): ?>
                <span class="w-1.5 h-1.5 rounded-full bg-slate-300"></span>
                <span><?= htmlspecialchars($pub['year']) ?></span>
            <?php endif; ?>
        </div>
        <h1 class="text-3xl md:text-4xl font-extrabold text-slate-900 leading-tight"><?= htmlspecialchars($pub['title']) ?></h1>
        <p class="text-xs text-slate-400 mt-2">Published on <?= date('F j, Y', strtotime($pub['created_at'])) ?></p>
    </div>

    <!-- Featured Image -->
    <?php if (!empty($pub['image_path'])): ?>
        <div class="rounded-2xl overflow-hidden mb-8 border border-slate-100 shadow-sm">
            <img src="<?= htmlspecialchars($pub['image_path']) ?>" alt="Featured Image" class="w-full h-auto object-cover max-h-[400px]">
        </div>
    <?php endif; ?>

    <!-- Content -->
    <article class="prose lg:prose-lg max-w-none text-slate-700 leading-relaxed space-y-6">
        <?= nl2br(htmlspecialchars($pub['content'])) ?>
    </article>

    <!-- Back Button -->
    <div class="mt-12 pt-6 border-t border-slate-100">
        <a href="/" class="text-indigo-600 hover:text-indigo-800 font-semibold inline-flex items-center gap-2 transition">
            <i class="fa-solid fa-arrow-left"></i> Back to Homepage
        </a>
    </div>
</div>

<?php
include 'includes/footer.php';
?>
