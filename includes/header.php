<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/functions.php';

if (!isset($settings)) {
    $settings = get_all_settings($conn);
}

$active_theme = $settings['active_homepage'] ?? 'home1.php';
$pages_nav_result = mysqli_query($conn, "SELECT title, slug FROM pages LIMIT 5");

$is_homepage = $is_homepage ?? false;
$page_title = $page_title ?? ($settings['school_name'] ?? 'School');
$font_family_url = str_replace(' ', '+', $settings['primary_font'] ?? 'Roboto');

$script_name = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
$base_path = rtrim(dirname($script_name), '/\\') . '/';
if ($base_path === '//') {
    $base_path = '/';
}
?>
<!DOCTYPE html>
<html lang="<?= ($active_theme === 'home3.php') ? 'bn' : 'en' ?>">
<head>
    <meta charset="UTF-8">
    <base href="<?= htmlspecialchars($base_path) ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title ?? $settings['school_name'] ?? 'School Portal') ?> - <?= htmlspecialchars($settings['school_name'] ?? 'School') ?></title>
    <link rel="icon" type="image/x-icon" href="<?= htmlspecialchars($settings['site_favicon'] ?? '') ?>">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com/3.4.16"></script>
    <!-- Remix Icon & FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <?php if ($active_theme === 'home3.php'): ?>
        <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <?php elseif ($active_theme === 'home2.php'): ?>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <?php else: ?>
        <link href="https://fonts.googleapis.com/css2?family=<?= $font_family_url ?>:wght@400;500;700&display=swap" rel="stylesheet">
    <?php endif; ?>

    <style>
        <?php if ($active_theme === 'home3.php'): ?>
            :root {
                --primary-green: #15803d;
                --primary-dark: #14532d;
                --primary-light: #22c55e;
                --accent-orange: #ea580c;
                --accent-yellow: #f59e0b;
                --soft-yellow: #fef08a;
                --text-color: #1e293b;
                --text-muted: #64748b;
                --bg-light: #f8fafc;
                --white: #ffffff;
                --border-color: #e2e8f0;
                --radius: 8px;
                --transition: all 0.3s ease;
            }
            body { 
                font-family: 'Hind Siliguri', sans-serif; 
                background-color: #f1f5f9; 
                color: var(--text-color);
                margin: 0;
            }
            .theme3-top-bar {
                background-color: var(--primary-dark);
                color: var(--white);
                padding: 8px 5%;
                font-size: 0.85rem;
                display: flex;
                justify-content: space-between;
                align-items: center;
                border-bottom: 2px solid var(--accent-orange);
            }
            .theme3-top-bar a, .theme3-top-bar span {
                color: var(--white);
                margin-right: 15px;
                text-decoration: none;
            }
            .theme3-header {
                background-color: var(--white);
                box-shadow: 0 4px 6px -1px rgba(0,0,0,0.07);
                position: sticky;
                top: 0;
                z-index: 50;
            }
            .theme3-header-container {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 12px 5%;
                max-width: 1300px;
                margin: 0 auto;
            }
            .theme3-logo-section {
                display: flex;
                align-items: center;
                gap: 12px;
                text-decoration: none;
                color: inherit;
            }
            .theme3-logo-box {
                width: 48px;
                height: 48px;
                background-color: var(--primary-green);
                color: var(--white);
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.5rem;
                overflow: hidden;
            }
            .theme3-logo-box img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }
            .theme3-logo-text h1 {
                font-size: 1.15rem;
                font-weight: 700;
                color: var(--primary-dark);
                margin: 0;
            }
            .theme3-logo-text p {
                font-size: 0.75rem;
                color: var(--accent-orange);
                margin: 0;
                font-weight: 500;
            }
            .theme3-nav ul {
                display: flex;
                list-style: none;
                gap: 6px;
                margin: 0;
                padding: 0;
                align-items: center;
            }
            .theme3-nav ul li a {
                color: var(--text-color);
                font-weight: 600;
                font-size: 0.9rem;
                padding: 7px 12px;
                border-radius: var(--radius);
                transition: var(--transition);
                text-decoration: none;
            }
            .theme3-nav ul li a:hover, .theme3-nav ul li a.active {
                background-color: var(--primary-green);
                color: var(--white);
            }
            .theme3-menu-toggle {
                display: none;
                font-size: 1.5rem;
                cursor: pointer;
                color: var(--primary-dark);
            }
            @media (max-width: 768px) {
                .theme3-nav ul {
                    display: none;
                    flex-direction: column;
                    background-color: var(--white);
                    position: absolute;
                    top: 75px;
                    left: 0;
                    width: 100%;
                    padding: 20px;
                    box-shadow: 0 10px 15px rgba(0,0,0,0.1);
                }
                .theme3-nav ul.active { display: flex; }
                .theme3-menu-toggle { display: block; }
            }

        <?php elseif ($active_theme === 'home2.php'): ?>
            :root {
                --primary-blue: #0b2240;
                --primary-light: #1e3a5f;
                --accent-gold: #c5a059;
                --accent-gold-hover: #b08c46;
                --secondary-red: #8b0000;
                --bg-light: #f4f6f9;
                --text-dark: #222222;
                --text-muted: #666666;
                --white: #ffffff;
                --transition: all 0.3s ease-in-out;
            }
            body { 
                font-family: 'Poppins', sans-serif; 
                background-color: var(--bg-light); 
                color: var(--text-dark);
                margin: 0;
            }
            .theme2-top-bar {
                background-color: var(--primary-blue);
                color: var(--white);
                padding: 8px 40px;
                font-size: 0.85rem;
                display: flex;
                justify-content: space-between;
                align-items: center;
                border-bottom: 2px solid var(--accent-gold);
            }
            .theme2-top-bar a, .theme2-top-bar span {
                color: #e2e8f0;
                text-decoration: none;
                margin-right: 15px;
                font-size: 0.8rem;
            }
            .theme2-ugc-badge {
                background: var(--accent-gold);
                color: #0b2240 !important;
                padding: 2px 8px;
                border-radius: 4px;
                font-weight: 700;
                font-size: 0.75rem !important;
            }
            .theme2-header {
                background-color: var(--white);
                box-shadow: 0 4px 15px rgba(0,0,0,0.06);
                position: sticky;
                top: 0;
                z-index: 50;
            }
            .theme2-nav-container {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 12px 40px;
                max-width: 1400px;
                margin: 0 auto;
            }
            .theme2-logo-area {
                display: flex;
                align-items: center;
                gap: 12px;
                text-decoration: none;
                color: inherit;
            }
            .theme2-logo-area img {
                width: 50px;
                height: 50px;
                border-radius: 50%;
                background: white;
                padding: 2px;
                border: 2px solid var(--accent-gold);
                object-fit: contain;
            }
            .theme2-logo-icon {
                width: 48px;
                height: 48px;
                background-color: var(--primary-blue);
                color: var(--accent-gold);
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.5rem;
                border: 2px solid var(--accent-gold);
            }
            .theme2-logo-text h1 {
                font-size: 1.15rem;
                font-weight: 800;
                color: var(--primary-blue);
                margin: 0;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }
            .theme2-logo-text p {
                font-size: 0.75rem;
                color: var(--accent-gold-hover);
                font-weight: 600;
                margin: 0;
            }
            .theme2-nav ul {
                display: flex;
                list-style: none;
                gap: 15px;
                margin: 0;
                padding: 0;
                align-items: center;
            }
            .theme2-nav ul li a {
                color: var(--primary-blue);
                font-weight: 600;
                font-size: 0.88rem;
                text-decoration: none;
                transition: var(--transition);
                padding: 6px 10px;
            }
            .theme2-nav ul li a:hover {
                color: var(--accent-gold-hover);
            }
            .theme2-btn-apply {
                background-color: var(--secondary-red);
                color: var(--white) !important;
                padding: 8px 18px !important;
                border-radius: 50px;
                font-weight: 700 !important;
                font-size: 0.85rem !important;
            }
            .theme2-btn-apply:hover {
                background-color: #6b0000 !important;
            }
            .theme2-menu-toggle {
                display: none;
                font-size: 1.5rem;
                cursor: pointer;
                color: var(--primary-blue);
            }
            @media (max-width: 900px) {
                .theme2-top-bar { padding: 8px 20px; }
                .theme2-nav-container { padding: 12px 20px; }
                .theme2-nav ul {
                    display: none;
                    flex-direction: column;
                    background-color: var(--white);
                    position: absolute;
                    top: 75px;
                    left: 0;
                    width: 100%;
                    padding: 20px;
                    box-shadow: 0 10px 15px rgba(0,0,0,0.1);
                }
                .theme2-nav ul.active { display: flex; }
                .theme2-menu-toggle { display: block; }
            }

        <?php else: ?>
            body { 
                font-family: '<?= htmlspecialchars($settings['primary_font'] ?? 'Roboto') ?>', sans-serif; 
            }
            .banner-bg { 
                background-image: url('<?= htmlspecialchars($settings['hero_background_image'] ?? '') ?>'); 
                background-size: cover; 
                background-position: center; 
            }
            .hero-overlay { 
                background: linear-gradient(90deg, rgba(30, 58, 138, 0.85) 0%, rgba(30, 58, 138, 0.4) 100%); 
            }
            .subpage-header { 
                background-color: #1e3a8a; 
            }
        <?php endif; ?>
    </style>
</head>
<body class="antialiased min-h-screen flex flex-col justify-between">

    <!-- ==================== HEADER SECTION ==================== -->
    <?php if ($active_theme === 'home3.php'): ?>
        <!-- Theme 3: Primary Bengali School Header -->
        <div class="theme3-top-bar">
            <div>
                <span><i class="fa-solid fa-phone"></i> <?= htmlspecialchars($settings['contact_phone'] ?? '+৮৮০১৭১১-XXXXXX') ?></span>
                <span><i class="fa-solid fa-envelope"></i> <?= htmlspecialchars($settings['contact_email'] ?? 'mail@school.edu.bd') ?></span>
            </div>
            <div>
                <span class="bg-amber-400 text-slate-900 px-2 py-0.5 rounded text-xs font-bold mr-3">EIIN: <?= htmlspecialchars($settings['school_eiin'] ?? '১০২২৩৪') ?></span>
                <span style="font-weight: 600; color: #fef08a;">বাংলা | EN</span>
            </div>
        </div>

        <header class="theme3-header">
            <div class="theme3-header-container">
                <a href="index.php" class="theme3-logo-section">
                    <?php if(!empty($settings['site_logo'])): ?>
                    <div class="theme3-logo-box">
                        <img src="<?= htmlspecialchars($settings['site_logo']) ?>" alt="Logo">
                    </div>
                    <?php else: ?>
                    <div class="theme3-logo-box">
                        <i class="fa-solid fa-book-open-reader"></i>
                    </div>
                    <?php endif; ?>
                    <div class="theme3-logo-text">
                        <h1><?= htmlspecialchars($settings['school_name'] ?? 'সূর্যমুখী সরকারি প্রাথমিক বিদ্যালয়') ?></h1>
                        <p><?= htmlspecialchars($settings['school_tagline'] ?? 'জ্ঞানই আলো, শিক্ষাই প্রগতি') ?></p>
                    </div>
                </a>
                <nav class="theme3-nav">
                    <ul id="theme3-nav-menu">
                        <li><a href="index.php">হোম</a></li>
                        <li><a href="page/about-us">পরিচিতি</a></li>
                        <li><a href="page/teachers">শিক্ষকবৃন্দ</a></li>
                        <li><a href="page/notices">নোটিশ</a></li>
                        <?php while($p = mysqli_fetch_assoc($pages_nav_result)): ?>
                            <li><a href="page/<?= htmlspecialchars($p['slug']) ?>"><?= htmlspecialchars($p['title']) ?></a></li>
                        <?php endwhile; ?>
                        <li><a href="library.php">ফটো গ্যালারি</a></li>
                        <li><a href="contact.php">যোগাযোগ</a></li>
                    </ul>
                </nav>
                <div class="theme3-menu-toggle" onclick="document.getElementById('theme3-nav-menu').classList.toggle('active')">
                    <i class="fa-solid fa-bars"></i>
                </div>
            </div>
        </header>

        <?php if (!$is_homepage): ?>
        <div style="background: linear-gradient(135deg, #15803d 0%, #14532d 100%); padding: 35px 5%; color: #fff; text-align: center; border-bottom: 4px solid #ea580c;">
            <div style="max-width: 1200px; margin: 0 auto;">
                <h1 style="font-size: 2rem; font-weight: 700; color: #fff; margin-bottom: 6px;"><?= htmlspecialchars($page_title) ?></h1>
                <p style="font-size: 0.85rem; color: #fde047;"><a href="index.php" style="color: #fde047; text-decoration: none;">হোম</a> <i class="fa-solid fa-angle-right" style="font-size: 0.75rem; margin: 0 6px;"></i> <?= htmlspecialchars($page_title) ?></p>
            </div>
        </div>
        <?php endif; ?>

    <?php elseif ($active_theme === 'home2.php'): ?>
        <!-- Theme 2: Prestigious College / University Header -->
        <div class="theme2-top-bar">
            <div>
                <span><i class="fa-solid fa-phone"></i> <?= htmlspecialchars($settings['contact_phone'] ?? '+880 2-55554444') ?></span>
                <span><i class="fa-solid fa-envelope"></i> <?= htmlspecialchars($settings['contact_email'] ?? 'admissions@dmu.edu.bd') ?></span>
            </div>
            <div>
                <span class="theme2-ugc-badge">UGC Approved</span>
                <a href="login.php?portal=student"><i class="fa-solid fa-user-graduate"></i> Student Portal</a>
                <a href="login.php?portal=teacher"><i class="fa-solid fa-chalkboard-user"></i> Teacher Portal</a>
                <a href="login.php?portal=parent"><i class="fa-solid fa-users"></i> Parent Portal</a>
            </div>
        </div>

        <header class="theme2-header">
            <div class="theme2-nav-container">
                <a href="index.php" class="theme2-logo-area">
                    <?php if (!empty($settings['site_logo'])): ?>
                        <img src="<?= htmlspecialchars($settings['site_logo']) ?>" alt="logo">
                    <?php else: ?>
                        <div class="theme2-logo-icon">
                            <i class="fa-solid fa-graduation-cap"></i>
                        </div>
                    <?php endif; ?>
                    <div class="theme2-logo-text">
                        <h1><?= htmlspecialchars($settings['school_name'] ?? 'ঢাকা মেট্রোপলিটন ইউনিভার্সিটি') ?></h1>
                        <p><?= htmlspecialchars($settings['school_tagline'] ?? 'Dhaka Metropolitan University') ?></p>
                    </div>
                </a>
                <nav class="theme2-nav">
                    <ul id="theme2-nav-list">
                        <li><a href="index.php">Home</a></li>
                        <?php while($p = mysqli_fetch_assoc($pages_nav_result)): ?>
                            <li><a href="page/<?= htmlspecialchars($p['slug']) ?>"><?= htmlspecialchars($p['title']) ?></a></li>
                        <?php endwhile; ?>
                        <li><a href="library.php">Library</a></li>
                        <li><a href="contact.php">Contact Us</a></li>
                        <li><a href="admission.php" class="theme2-btn-apply">Admission Open</a></li>
                    </ul>
                </nav>
                <div class="theme2-menu-toggle" onclick="document.getElementById('theme2-nav-list').classList.toggle('active')">
                    <i class="fa-solid fa-bars"></i>
                </div>
            </div>
        </header>

        <?php if (!$is_homepage): ?>
        <div style="background: linear-gradient(135deg, #0b2240 0%, #1e3a5f 100%); padding: 40px 20px; color: #fff; text-align: center; border-bottom: 4px solid #c5a059;">
            <div style="max-width: 1200px; margin: 0 auto;">
                <h1 style="font-size: 2.2rem; font-weight: 800; color: #fff; margin-bottom: 6px;"><?= htmlspecialchars($page_title) ?></h1>
                <p style="font-size: 0.85rem; color: #c5a059; font-weight: 600;"><a href="index.php" style="color: #c5a059; text-decoration: none;">Home</a> <i class="fa-solid fa-angle-right" style="font-size: 0.75rem; margin: 0 6px;"></i> <?= htmlspecialchars($page_title) ?></p>
            </div>
        </div>
        <?php endif; ?>

    <?php else: ?>
        <!-- Theme 1: Standard Modern School Header -->
        <header class="bg-blue-800 text-white shadow-md">
            <div class="container mx-auto px-4 py-3">
                <div class="flex items-center justify-between">
                    <a href="index.php" class="flex items-center space-x-4">
                        <img src="<?= htmlspecialchars($settings['site_logo'] ?? '') ?>" alt="logo" class="h-16 w-auto bg-white p-1 rounded-full">
                        <div>
                            <h1 class="text-2xl font-bold"><?= htmlspecialchars($settings['school_name'] ?? 'School') ?></h1>
                            <p class="text-blue-200 text-sm"><?= htmlspecialchars($settings['school_tagline'] ?? '') ?></p>
                        </div>
                    </a>
                    <div class="hidden md:flex items-center space-x-6">
                        <div class="text-right">
                            <p class="text-sm">Contact: <?= htmlspecialchars($settings['contact_phone'] ?? '') ?></p>
                            <p class="text-sm">Email: <?= htmlspecialchars($settings['contact_email'] ?? '') ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <nav class="bg-blue-900">
                <div class="container mx-auto px-4 py-2 flex items-center space-x-6">
                    <a href="index.php" class="text-white hover:bg-blue-700 px-3 py-2 rounded">Home</a>
                    <?php while($p = mysqli_fetch_assoc($pages_nav_result)): ?>
                        <a href="page/<?= htmlspecialchars($p['slug']) ?>" class="text-white hover:bg-blue-700 px-3 py-2 rounded"><?= htmlspecialchars($p['title']) ?></a>
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
                    <h2 class="text-4xl font-bold text-white mb-4">Welcome to <?= htmlspecialchars($settings['school_name'] ?? 'School') ?></h2>
                    <a href="<?= htmlspecialchars($settings['hero_button_url'] ?? 'admission.php') ?>" class="bg-green-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-green-700"><?= htmlspecialchars($settings['hero_button_text'] ?? 'Apply Now') ?></a>
                </div>
            </div>
        </section>
        <?php else: ?>
        <div class="subpage-header py-8">
            <div class="container mx-auto px-4"><h1 class="text-3xl font-bold text-white"><?= htmlspecialchars($page_title) ?></h1></div>
        </div>
        <?php endif; ?>

    <?php endif; ?>

    <!-- Main Content Container -->
    <main class="container mx-auto px-4 py-8 max-w-6xl flex-grow">