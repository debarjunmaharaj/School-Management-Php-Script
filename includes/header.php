<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/functions.php';

$settings = get_all_settings($conn);
$pages_nav_result = mysqli_query($conn, "SELECT title, slug FROM pages LIMIT 5");

$is_homepage = $is_homepage ?? false;
$font_family_url = str_replace(' ', '+', $settings['primary_font'] ?? 'Roboto');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title ?? $settings['school_name'] ?? 'School Portal') ?></title>
    <link rel="icon" type="image/x-icon" href="<?= htmlspecialchars($settings['site_favicon']) ?>">
    <script src="https://cdn.tailwindcss.com/3.4.16"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=<?= $font_family_url ?>:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: '<?= htmlspecialchars($settings['primary_font'] ?? 'Roboto') ?>', sans-serif; }
        .banner-bg { background-image: url('<?= htmlspecialchars($settings['hero_background_image']) ?>'); background-size: cover; background-position: center; }
        .hero-overlay { background: linear-gradient(90deg, rgba(30, 58, 138, 0.85) 0%, rgba(30, 58, 138, 0.4) 100%); }
        .subpage-header { background-color: #1e3a8a; }
    </style>
</head>
<body class="bg-gray-100">
    <header class="bg-blue-800 text-white shadow-md">
        <div class="container mx-auto px-4 py-3">
            <div class="flex items-center justify-between">
                <a href="/" class="flex items-center space-x-4">
                    <img src="<?= htmlspecialchars($settings['site_logo']) ?>" alt="logo" class="h-16 w-auto bg-white p-1 rounded-full">
                    <div>
                        <h1 class="text-2xl font-bold"><?= htmlspecialchars($settings['school_name']) ?></h1>
                        <p class="text-blue-200 text-sm"><?= htmlspecialchars($settings['school_tagline']) ?></p>
                    </div>
                </a>
                <div class="hidden md:flex items-center space-x-6">
                    <div class="text-right">
                        <p class="text-sm">Contact: <?= htmlspecialchars($settings['contact_phone']) ?></p>
                        <p class="text-sm">Email: <?= htmlspecialchars($settings['contact_email']) ?></p>
                    </div>
                </div>
            </div>
        </div>
        <nav class="bg-blue-900">
            <div class="container mx-auto px-4 py-2 flex items-center space-x-6">
                <a href="/" class="text-white hover:bg-blue-700 px-3 py-2 rounded">Home</a>
                <?php while($page = mysqli_fetch_assoc($pages_nav_result)): ?>
                    <a href="page.php?slug=<?= htmlspecialchars($page['slug']) ?>" class="text-white hover:bg-blue-700 px-3 py-2 rounded"><?= htmlspecialchars($page['title']) ?></a>
                <?php endwhile; ?>
                <a href="admission.php" class="text-white hover:bg-blue-700 px-3 py-2 rounded">Admissions</a>
                <a href="contact.php" class="text-white hover:bg-blue-700 px-3 py-2 rounded">Contact Us</a>
                <a href="library.php" class="text-white hover:bg-blue-700 px-3 py-2 rounded">Library</a>
            </div>
        </nav>
    </header>

    <?php if ($is_homepage): ?>
    <section class="banner-bg h-96 relative">
        <div class="hero-overlay absolute inset-0 flex items-center">
            <div class="container mx-auto px-4">
                <h2 class="text-4xl font-bold text-white mb-4">Welcome to <?= htmlspecialchars($settings['school_name']) ?></h2>
                <a href="<?= htmlspecialchars($settings['hero_button_url']) ?>" class="bg-green-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-green-700"><?= htmlspecialchars($settings['hero_button_text']) ?></a>
            </div>
        </div>
    </section>
    <?php else: ?>
    <div class="subpage-header py-8">
        <div class="container mx-auto px-4"><h1 class="text-3xl font-bold text-white"><?= htmlspecialchars($page_title) ?></h1></div>
    </div>
    <?php endif; ?>

    <main class="container mx-auto py-8">