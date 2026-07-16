<?php
// Fetch all notices and downloads for dynamic loop integration
$notices_query = mysqli_query($conn, "SELECT * FROM notices ORDER BY id DESC LIMIT 5");
$downloads_query = mysqli_query($conn, "SELECT * FROM downloads WHERE file_type = 'pdf' ORDER BY id DESC LIMIT 5");
$teachers_query = mysqli_query($conn, "SELECT * FROM teachers WHERE is_leadership = 0 ORDER BY id DESC LIMIT 3");
$committee_query = mysqli_query($conn, "SELECT * FROM teachers WHERE is_leadership = 1 ORDER BY id DESC LIMIT 3");
$marquee_notice = mysqli_query($conn, "SELECT title FROM notices ORDER BY id DESC LIMIT 1");
$latest_notice_title = ($marquee_notice && mysqli_num_rows($marquee_notice) > 0) ? mysqli_fetch_assoc($marquee_notice)['title'] : '২০২৬ শিক্ষাবর্ষে প্রাক-প্রাথমিক থেকে ৫ম শ্রেণিতে ভর্তি কার্যক্রম চলছে।';
?>
<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($settings['school_name'] ?? 'সূর্যমুখী সরকারি প্রাথমিক বিদ্যালয়') ?> | <?= htmlspecialchars($settings['school_tagline'] ?? 'জ্ঞানের আলোয় গড়বো দেশ') ?></title>
    <!-- Google Fonts for Bengali -->
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-green: #15803d; /* প্রাতিষ্ঠানিক গাঢ় সবুজ */
            --primary-light: #22c55e;
            --accent-orange: #f97316; /* আকর্ষণীয় কমলা রঙ */
            --sky-blue: #0ea5e9;
            --soft-yellow: #fef08a;
            --text-dark: #1e293b;
            --text-muted: #64748b;
            --white: #ffffff;
            --bg-light: #f8fafc;
            --border-color: #e2e8f0;
            --transition: all 0.3s ease;
            --radius: 8px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Hind Siliguri', sans-serif;
        }

        body {
            background-color: var(--bg-light);
            color: var(--text-dark);
            line-height: 1.7;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        /* ১. টপ হেডার বার */
        .top-bar {
            background-color: var(--primary-green);
            color: var(--white);
            font-size: 0.9rem;
            padding: 8px 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
            border-bottom: 3px solid var(--accent-orange);
        }

        .top-info span {
            margin-right: 20px;
        }

        .top-info i {
            margin-right: 6px;
            color: var(--soft-yellow);
        }

        .top-meta {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .eiin-badge {
            background-color: var(--accent-orange);
            color: var(--white);
            padding: 1px 8px;
            border-radius: 4px;
            font-weight: 600;
            font-size: 0.8rem;
        }

        /* ২. প্রধান নেভিগেশন ও লোগো এরিয়া */
        header {
            background-color: var(--white);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 5%;
            max-width: 1280px;
            margin: 0 auto;
        }

        .logo-section {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .logo-box {
            width: 60px;
            height: 60px;
            background-color: var(--primary-green);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--soft-yellow);
            font-size: 1.8rem;
            border: 2px solid var(--accent-orange);
        }

        .logo-box img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            border-radius: 50%;
        }

        .logo-text h1 {
            font-size: 1.4rem;
            color: var(--primary-green);
            font-weight: 700;
            line-height: 1.2;
        }

        .logo-text p {
            font-size: 0.85rem;
            color: var(--text-muted);
            font-weight: 500;
        }

        nav ul {
            display: flex;
            list-style: none;
            gap: 15px;
        }

        nav ul li a {
            font-weight: 600;
            font-size: 0.95rem;
            color: var(--text-dark);
            padding: 8px 15px;
            border-radius: var(--radius);
            transition: var(--transition);
        }

        nav ul li a:hover, nav ul li a.active {
            background-color: var(--primary-green);
            color: var(--white);
        }

        .menu-toggle {
            display: none;
            font-size: 1.5rem;
            color: var(--primary-green);
            cursor: pointer;
        }

        /* ৩. জরুরী নোটিশ স্ক্রলার */
        .ticker-section {
            background-color: #fef3c7;
            border-bottom: 1px solid #fde68a;
            padding: 8px 5%;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .ticker-title {
            background-color: var(--secondary-red, #dc2626);
            color: var(--white);
            padding: 3px 12px;
            border-radius: 4px;
            font-weight: 700;
            font-size: 0.85rem;
            white-space: nowrap;
        }

        .ticker-content {
            overflow: hidden;
            width: 100%;
        }

        .ticker-content marquee {
            font-weight: 600;
            color: #9a3412;
            font-size: 0.95rem;
        }

        /* ৪. হিরো ব্যানার স্লাইডার */
        .hero {
            background: linear-gradient(rgba(21, 128, 61, 0.85), rgba(15, 23, 42, 0.9)), url('<?= !empty($settings['hero_background_image']) ? htmlspecialchars($settings['hero_background_image']) : 'https://images.unsplash.com/photo-1577896851231-70ef18881754?q=80&w=1200&auto=format&fit=crop' ?>') center/cover;
            color: var(--white);
            padding: 100px 5%;
            text-align: center;
        }

        .hero-content {
            max-width: 800px;
            margin: 0 auto;
        }

        .hero-badge {
            background-color: var(--accent-orange);
            color: var(--white);
            padding: 6px 18px;
            border-radius: 20px;
            font-weight: 700;
            font-size: 0.9rem;
            display: inline-block;
            margin-bottom: 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .hero h2 {
            font-size: 2.6rem;
            font-weight: 800;
            margin-bottom: 15px;
            line-height: 1.3;
        }

        .hero p {
            font-size: 1.1rem;
            margin-bottom: 30px;
            opacity: 0.9;
        }

        .btn {
            padding: 12px 28px;
            border-radius: var(--radius);
            font-weight: 600;
            font-size: 1rem;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: var(--transition);
        }

        .btn-accent {
            background-color: var(--accent-orange);
            color: var(--white);
        }

        .btn-accent:hover {
            background-color: #ea580c;
        }

        /* ৫. কুইক পোর্টাল ও সেবা বক্স */
        .portals-container {
            max-width: 1200px;
            margin: -50px auto 40px;
            padding: 0 20px;
            position: relative;
            z-index: 10;
        }

        .portals-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .portal-card {
            background-color: var(--white);
            padding: 30px 20px;
            border-radius: var(--radius);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            text-align: center;
            border-top: 4px solid var(--accent-orange);
            transition: var(--transition);
        }

        .portal-card:hover {
            transform: translateY(-5px);
        }

        .portal-card i {
            font-size: 2.2rem;
            color: var(--primary-green);
            margin-bottom: 15px;
        }

        .portal-card h3 {
            font-size: 1.2rem;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .portal-card p {
            font-size: 0.9rem;
            color: var(--text-muted);
        }

        /* মূল কন্টেন্ট লেআউট */
        .main-container {
            max-width: 1200px;
            margin: 0 auto 50px;
            padding: 0 20px;
            display: grid;
            grid-template-columns: 8fr 4fr;
            gap: 30px;
        }

        @media (max-width: 992px) {
            .main-container {
                grid-template-columns: 1fr;
            }
        }

        /* সাধারণ প্যানেল কার্ড ডিজাইন */
        .section-card {
            background-color: var(--white);
            border-radius: var(--radius);
            padding: 30px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
            border: 1px solid var(--border-color);
        }

        .section-title {
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--primary-green);
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #edf2f7;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* ৬. বিদ্যালয়ের সংক্ষিপ্ত পরিচিতি */
        .intro-text p {
            margin-bottom: 15px;
            font-size: 1rem;
            color: var(--text-dark);
            text-align: justify;
        }

        /* ৭. প্রধান শিক্ষকের বাণী */
        .leader-message {
            display: flex;
            gap: 20px;
            align-items: flex-start;
            flex-wrap: wrap;
        }

        .leader-img {
            width: 150px;
            height: 180px;
            object-fit: cover;
            border-radius: var(--radius);
            border: 3px solid var(--border-color);
        }

        .leader-text {
            flex: 1;
            min-width: 250px;
        }

        /* ৮. ম্যানেজিং কমিটির সভাপতির বাণী */
        .chairman-message {
            background-color: #f0fdf4;
            border-left: 4px solid var(--primary-green);
            padding: 20px;
            border-radius: 0 var(--radius) var(--radius) 0;
            display: flex;
            gap: 15px;
            align-items: flex-start;
            flex-wrap: wrap;
        }

        .chairman-message img {
            width: 100px;
            height: 120px;
            object-fit: cover;
            border-radius: var(--radius);
            border: 2px solid var(--border-color);
        }

        /* ৯. বিদ্যালয়ের মূল পরিসংখ্যান */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(130px, 1fr));
            gap: 15px;
            text-align: center;
            margin-top: 15px;
        }

        .stat-item {
            background-color: var(--bg-light);
            padding: 20px 10px;
            border-radius: var(--radius);
            border: 1px solid var(--border-color);
        }

        .stat-item h3 {
            font-size: 1.8rem;
            color: var(--primary-green);
            margin-bottom: 5px;
            font-weight: 700;
        }

        .stat-item p {
            font-size: 0.85rem;
            color: var(--text-muted);
            font-weight: 600;
        }

        /* ১০. নোটিশ বোর্ড (ডানদিকের সাইডবার) */
        .notice-list {
            list-style: none;
        }

        .notice-item {
            padding: 12px 0;
            border-bottom: 1px dashed var(--border-color);
        }

        .notice-item:last-child {
            border-bottom: none;
        }

        .notice-date-badge {
            background-color: #f1f5f9;
            color: var(--text-dark);
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-block;
            margin-bottom: 5px;
        }

        .notice-item a {
            font-size: 0.95rem;
            font-weight: 600;
            color: var(--text-dark);
            display: block;
            transition: var(--transition);
        }

        .notice-item a:hover {
            color: var(--accent-orange);
        }

        /* ১১. শ্রেণিভিত্তিক শিক্ষা কার্যক্রম */
        .class-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 15px;
        }

        .class-item {
            background-color: var(--white);
            border: 1px solid var(--border-color);
            border-radius: var(--radius);
            padding: 15px;
            text-align: center;
            transition: var(--transition);
        }

        .class-item:hover {
            border-color: var(--primary-green);
            background-color: #f8fafc;
        }

        .class-item h4 {
            color: var(--primary-green);
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 5px;
        }

        /* ১২. সাপ্তাহিক ক্লাস রুটিন সেকশন */
        .routine-btn {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: #f8fafc;
            border: 1px solid var(--border-color);
            padding: 12px 20px;
            border-radius: var(--radius);
            margin-bottom: 10px;
            font-weight: 600;
            transition: var(--transition);
        }

        .routine-btn:hover {
            background-color: var(--primary-green);
            color: var(--white);
        }

        /* সহ-শিক্ষা কার্যক্রম */
        .co-curricular-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }

        .co-curricular-card {
            border: 1px solid var(--border-color);
            border-radius: var(--radius);
            padding: 15px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .co-curricular-card i {
            font-size: 1.5rem;
            color: var(--accent-orange);
        }

        /* মুক্তিযুদ্ধ কর্নার */
        .muktijoddha-corner {
            background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.75)), url('https://images.unsplash.com/photo-1541339907198-e08756dedf3f?q=80&w=800&auto=format&fit=crop') center/cover;
            color: var(--white);
            padding: 25px;
            border-radius: var(--radius);
            text-align: center;
        }

        .muktijoddha-corner img {
            width: 80px;
            margin-bottom: 10px;
        }

        .muktijoddha-corner h4 {
            font-size: 1.2rem;
            margin-bottom: 10px;
        }

        /* ১৫. শিক্ষক ও স্টাফ প্রোফাইল */
        .teacher-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 20px;
        }

        .teacher-card {
            text-align: center;
            border: 1px solid var(--border-color);
            padding: 20px 15px;
            border-radius: var(--radius);
            transition: var(--transition);
        }

        .teacher-card:hover {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .teacher-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background-color: #f1f5f9;
            margin: 0 auto 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            color: var(--text-muted);
            border: 2px solid var(--primary-green);
        }

        .teacher-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }

        /* ১৬. ম্যানেজিং কমিটি পরিচিতি */
        .committee-list {
            list-style: none;
        }

        .committee-member {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid var(--border-color);
            font-size: 0.95rem;
        }

        /* ১৭. সেরা শিক্ষার্থী কর্নার */
        .star-student-card {
            background-color: #fffbeb;
            border: 1px solid #fde68a;
            padding: 20px;
            border-radius: var(--radius);
            text-align: center;
        }

        .star-student-card i {
            color: #fbbf24;
            font-size: 2rem;
            margin-bottom: 10px;
        }

        .star-student-card img {
            width: 90px;
            height: 90px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid #fbbf24;
            margin: 0 auto 10px;
            display: block;
        }

        /* ১৮. ফটো ও ভিডিও গ্যালারি */
        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(130px, 1fr));
            gap: 10px;
        }

        .gallery-item {
            width: 100%;
            height: 110px;
            object-fit: cover;
            border-radius: var(--radius);
            cursor: pointer;
            transition: var(--transition);
        }

        .gallery-item:hover {
            transform: scale(1.03);
        }

        /* ১৯. প্রয়োজনীয় ডাউনলোড ও ফরম কর্নার */
        .download-box {
            background-color: var(--bg-light);
            border: 1px solid var(--border-color);
            border-radius: var(--radius);
            padding: 20px;
        }

        /* ২০. যোগাযোগ ও ফুটার */
        footer {
            background-color: #0f172a;
            color: #94a3b8;
            padding: 60px 5% 30px;
            font-size: 0.95rem;
            border-top: 5px solid var(--accent-orange);
        }

        .footer-grid {
            max-width: 1200px;
            margin: 0 auto 40px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 30px;
        }

        .footer-col h3 {
            color: var(--white);
            font-size: 1.15rem;
            margin-bottom: 20px;
            border-left: 4px solid var(--accent-orange);
            padding-left: 10px;
        }

        .footer-map-placeholder {
            width: 100%;
            height: 150px;
            background-color: #1e293b;
            border-radius: var(--radius);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
            color: #64748b;
        }

        .footer-bottom {
            max-width: 1200px;
            margin: 0 auto;
            padding-top: 25px;
            border-top: 1px solid #1e293b;
            text-align: center;
            font-size: 0.85rem;
        }

        /* মোবাইল স্ক্রিন সেটিংস */
        @media (max-width: 768px) {
            nav ul {
                display: none;
                flex-direction: column;
                background-color: var(--white);
                position: absolute;
                top: 85px;
                left: 0;
                width: 100%;
                padding: 20px;
                box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
            }

            nav ul.active {
                display: flex;
            }

            .menu-toggle {
                display: block;
            }

            .hero h2 {
                font-size: 1.8rem;
            }
        }
    </style>
</head>
<body>

    <!-- ১. টপ হেডার বার -->
    <div class="top-bar">
        <div class="top-info">
            <span><i class="fa-solid fa-phone"></i> <?= htmlspecialchars($settings['contact_phone'] ?? '+৮৮০১৭১১-XXXXXX') ?></span>
            <span><i class="fa-solid fa-envelope"></i> <?= htmlspecialchars($settings['contact_email'] ?? 'mail@surjomukhigps.edu.bd') ?></span>
        </div>
        <div class="top-meta">
            <span class="eiin-badge">EIIN: <?= htmlspecialchars($settings['school_eiin'] ?? '১০২২৩৪') ?></span>
            <span style="font-weight: 600; color: var(--soft-yellow);">বাংলা | EN</span>
        </div>
    </div>

    <!-- ২. প্রধান নেভিগেশন ও লোগো এরিয়া -->
    <header>
        <div class="header-container">
            <div class="logo-section">
                <?php if(!empty($settings['site_logo'])): ?>
                <div class="logo-box">
                    <img src="<?= htmlspecialchars($settings['site_logo']) ?>" alt="Logo">
                </div>
                <?php else: ?>
                <div class="logo-box">
                    <i class="fa-solid fa-book-open-reader"></i>
                </div>
                <?php endif; ?>
                <div class="logo-text">
                    <h1><?= htmlspecialchars($settings['school_name'] ?? 'সূর্যমুখী সরকারি প্রাথমিক বিদ্যালয়') ?></h1>
                    <p><?= htmlspecialchars($settings['school_tagline'] ?? 'জ্ঞানই আলো, শিক্ষাই প্রগতি') ?></p>
                </div>
            </div>
            <nav>
                <ul id="nav-menu">
                    <li><a href="#" class="active">হোম</a></li>
                    <li><a href="page.php?slug=about-us">পরিচিতি</a></li>
                    <li><a href="page.php?slug=teachers">শিক্ষকবৃন্দ</a></li>
                    <li><a href="page.php?slug=notices">নোটিশ</a></li>
                    <li><a href="library.php">ফটো গ্যালারি</a></li>
                    <li><a href="contact.php">যোগাযোগ</a></li>
                </ul>
            </nav>
            <div class="menu-toggle" onclick="toggleMenu()">
                <i class="fa-solid fa-bars"></i>
            </div>
        </div>
    </header>

    <!-- ৩. জরুরী নোটিশ স্ক্রলার -->
    <div class="ticker-section">
        <div class="ticker-title"><i class="fa-solid fa-circle-exclamation"></i> জরুরী নোটিশ</div>
        <div class="ticker-content">
            <marquee direction="left" scrollamount="4"><?= htmlspecialchars($latest_notice_title) ?></marquee>
        </div>
    </div>

    <!-- ৪. হিরো ব্যানার স্লাইডার -->
    <section class="hero">
        <div class="hero-content">
            <span class="hero-badge"><?= htmlspecialchars($settings['hero_button_text'] ?? 'ভর্তি চলছে - সেশন ২০২৬') ?></span>
            <h2><?= htmlspecialchars($settings['school_name'] ?? 'সূর্যমুখী সরকারি প্রাথমিক বিদ্যালয়') ?></h2>
            <p><?= htmlspecialchars($settings['school_tagline'] ?? 'আপনার সন্তানের প্রাথমিক শিক্ষার মজবুত ভিত্তি গড়ে তুলতে আমরা বদ্ধপরিকর।') ?></p>
            <a href="<?= htmlspecialchars($settings['hero_button_url'] ?? 'admission.php') ?>" class="btn btn-accent"><i class="fa-solid fa-file-signature"></i> অনলাইনে ভর্তি আবেদন</a>
        </div>
    </section>

    <!-- ৫. কুইক পোর্টাল ও সেবা বক্স -->
    <div class="portals-container">
        <div class="portals-grid">
            <div class="portal-card">
                <i class="fa-solid fa-child"></i>
                <h3>শিক্ষার্থী কর্নার</h3>
                <p>শ্রেণি রুটিন, বার্ষিক সিলেবাস এবং ফলাফল দেখুন এখানে।</p>
            </div>
            <div class="portal-card">
                <i class="fa-solid fa-hands-holding-child"></i>
                <h3>অভিভাবক জোন</h3>
                <p>সন্তানের উপস্থিতি, প্রগতি এবং ছুটির আবেদনের তথ্য।</p>
            </div>
            <div class="portal-card">
                <i class="fa-solid fa-chalkboard-user"></i>
                <h3>শিক্ষক পোর্টাল</h3>
                <p>ডিজিটাল হাজিরা, ডায়েরি ও পাঠ পরিকল্পনা মডিউল।</p>
            </div>
        </div>
    </div>

    <!-- মূল দুই কলাম বিশিষ্ট কন্টেন্ট এরিয়া -->
    <main class="main-container">
        
        <!-- বামদিকের প্রধান সেকশনসমূহ -->
        <div class="left-column">
            
            <!-- ৬. বিদ্যালয়ের সংক্ষিপ্ত পরিচিতি -->
            <div class="section-card">
                <h2 class="section-title"><i class="fa-solid fa-school"></i> আমাদের স্কুল পরিচিতি</h2>
                <div class="intro-text">
                    <p><?= htmlspecialchars($settings['footer_about_text'] ?? 'সূর্যমুখী সরকারি প্রাথমিক বিদ্যালয়টি দেশের অন্যতম প্রাচীন ও ঐতিহ্যবাহী শিক্ষাপ্রতিষ্ঠান। শিক্ষার্থীদের সুপ্ত প্রতিভা বিকাশ ও আদর্শ নাগরিক হিসেবে গড়ে তোলার লক্ষ্যে প্রতিষ্ঠানটি কাজ করে যাচ্ছে। আমাদের এখানে রয়েছে আধুনিক মাল্টিমিডিয়া ক্লাসরুম, সুবিশাল খেলার মাঠ ও নিবেদিতপ্রাণ শিক্ষক মণ্ডলী।') ?></p>
                </div>
            </div>

            <!-- ৭. প্রধান শিক্ষকের বাণী -->
            <div class="section-card">
                <h2 class="section-title"><i class="fa-solid fa-signature"></i> <?= htmlspecialchars($settings['principal_message_title'] ?? 'প্রধান শিক্ষকের বাণী') ?></h2>
                <div class="leader-message">
                    <img class="leader-img" src="<?= !empty($settings['principal_photo_url']) ? htmlspecialchars($settings['principal_photo_url']) : 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?q=80&w=200&auto=format&fit=crop' ?>" alt="প্রধান শিক্ষক">
                    <div class="leader-text">
                        <p><?= htmlspecialchars($settings['principal_message_content'] ?? 'প্রিয় সুধী, কোমলমতি শিশুদের মেধা ও মননের পরিপূর্ণ বিকাশের জন্য দরকার একটি সুস্থ ও সুন্দর পরিবেশ। আমরা কেবল পুথিগত বিদ্যা নয়, বরং নৈতিকতা ও সুশৃঙ্খল জীবনযাপনের শিক্ষা প্রদানের মাধ্যমে শিশুদের ভবিষ্যতের যোগ্য চালিকাশক্তি হিসেবে গড়ে তুলতে সচেষ্ট রয়েছি।') ?></p>
                        <h4 style="margin-top: 15px; color: var(--primary-green);"><?= htmlspecialchars($settings['principal_message_name'] ?? 'মোসাম্মৎ রেহানা বেগম') ?></h4>
                        <span style="font-size: 0.85rem; color: var(--text-muted);"><?= htmlspecialchars($settings['principal_message_title'] ?? 'প্রধান শিক্ষক, সূর্যমুখী সরকারি প্রাথমিক বিদ্যালয়') ?></span>
                    </div>
                </div>
            </div>

            <!-- ৮. ম্যানেজিং কমিটির সভাপতির বাণী -->
            <div class="section-card">
                <h2 class="section-title"><i class="fa-solid fa-user-tie"></i> <?= htmlspecialchars($settings['minister_message_title'] ?? 'সভাপতির বাণী') ?></h2>
                <div class="chairman-message">
                    <?php if(!empty($settings['minister_photo_url'])): ?>
                    <img src="<?= htmlspecialchars($settings['minister_photo_url']) ?>" alt="Chairman">
                    <?php endif; ?>
                    <div style="flex: 1;">
                        <p><?= htmlspecialchars($settings['minister_message_content'] ?? 'বিদ্যালয়ের সার্বিক পরিবেশের উন্নয়ন ও মানসম্মত প্রাথমিক শিক্ষা নিশ্চিত করতে ম্যানেজিং কমিটি সব সময় শিক্ষকদের পাশে রয়েছে। অভিভাবকদের সাথে নিয়মিত যোগাযোগের মাধ্যমে আমরা একটি সুন্দর শিক্ষা বান্ধব পরিমণ্ডল ধরে রাখতে সফল হয়েছি।') ?></p>
                        <h5 style="margin-top: 10px; font-weight: 700;"><?= htmlspecialchars($settings['minister_message_name'] ?? 'আলহাজ্ব মোঃ আব্দুর রহমান') ?></h5>
                        <span style="font-size: 0.8rem; color: var(--text-muted);">সভাপতি, ম্যানেজিং কমিটি</span>
                    </div>
                </div>
            </div>

            <!-- ৯. বিদ্যালয়ের মূল পরিসংখ্যান -->
            <div class="section-card">
                <h2 class="section-title"><i class="fa-solid fa-chart-simple"></i> এক নজরে আমাদের স্কুল</h2>
                <div class="stats-grid">
                    <div class="stat-item">
                        <h3><?= htmlspecialchars($settings['stats_students'] ?? '৪৫০+') ?></h3>
                        <p>ছাত্র-ছাত্রী</p>
                    </div>
                    <div class="stat-item">
                        <h3><?= htmlspecialchars($settings['stats_teachers'] ?? '১২ জন') ?></h3>
                        <p>শিক্ষক-শিক্ষিকা</p>
                    </div>
                    <div class="stat-item">
                        <h3><?= htmlspecialchars($settings['stats_pass_rate'] ?? '১০০%') ?></h3>
                        <p>সমাপনী পাসের হার</p>
                    </div>
                    <div class="stat-item">
                        <h3><?= htmlspecialchars($settings['stats_classrooms'] ?? '১০টি') ?></h3>
                        <p>শ্রেণিকক্ষ</p>
                    </div>
                </div>
            </div>

            <!-- ১১. শ্রেণিভিত্তিক শিক্ষা কার্যক্রম -->
            <div class="section-card">
                <h2 class="section-title"><i class="fa-solid fa-graduation-cap"></i> শিক্ষা কার্যক্রম</h2>
                <p style="margin-bottom: 15px;">জাতীয় শিক্ষাক্রম ও পাঠ্যপুস্তক বোর্ড (NCTB) কর্তৃক প্রণীত প্রাথমিক স্তরের পাঠ্যসূচী অত্যন্ত দক্ষতার সাথে অনুসরণ করা হয়:</p>
                <div class="class-grid">
                    <div class="class-item">
                        <h4>প্রাক-প্রাথমিক</h4>
                        <p>খেলার ছলে আনন্দদায়ক শিখন</p>
                    </div>
                    <div class="class-item">
                        <h4>প্রথম-দ্বিতীয় শ্রেণি</h4>
                        <p>বর্ণ ও ভাষার বনিয়াদি দক্ষতা</p>
                    </div>
                    <div class="class-item">
                        <h4>তৃতীয়-পঞ্চম শ্রেণি</h4>
                        <p>বিষয়ভিত্তিক গভীর শিখন ও গণিত</p>
                    </div>
                </div>
            </div>

            <!-- সহ-শিক্ষা কার্যক্রম -->
            <div class="section-card">
                <h2 class="section-title"><i class="fa-solid fa-masks-theater"></i> সহ-শিক্ষা কার্যক্রম</h2>
                <div class="co-curricular-list">
                    <div class="co-curricular-card">
                        <i class="fa-solid fa-volleyball"></i>
                        <span>বার্ষিক ক্রীড়া ও দৌড় প্রতিযোগিতা</span>
                    </div>
                    <div class="co-curricular-card">
                        <i class="fa-solid fa-guitar"></i>
                        <span>সাংস্কৃতিক উৎসব ও সঙ্গীত ক্লাব</span>
                    </div>
                    <div class="co-curricular-card">
                        <i class="fa-solid fa-campground"></i>
                        <span>কাব স্কাউটিং কার্যক্রম</span>
                    </div>
                </div>
            </div>

            <!-- ১৫. শিক্ষক ও স্টাফ প্রোফাইল -->
            <div class="section-card">
                <h2 class="section-title"><i class="fa-solid fa-users"></i> আমাদের শিক্ষকবৃন্দ</h2>
                <div class="teacher-grid">
                    <?php if ($teachers_query && mysqli_num_rows($teachers_query) > 0): ?>
                        <?php while ($teacher = mysqli_fetch_assoc($teachers_query)): ?>
                            <div class="teacher-card">
                                <div class="teacher-avatar">
                                    <?php if(!empty($teacher['image_url'])): ?>
                                        <img src="<?= htmlspecialchars($teacher['image_url']) ?>" alt="<?= htmlspecialchars($teacher['name']) ?>">
                                    <?php else: ?>
                                        <i class="fa-solid fa-chalkboard-user"></i>
                                    <?php endif; ?>
                                </div>
                                <h4><?= htmlspecialchars($teacher['name']) ?></h4>
                                <p style="font-size: 0.8rem; color: var(--text-muted);"><?= htmlspecialchars($teacher['subject']) ?></p>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="teacher-card">
                            <div class="teacher-avatar"><i class="fa-solid fa-user-nurse"></i></div>
                            <h4>আফসানা আক্তার</h4>
                            <p style="font-size: 0.8rem; color: var(--text-muted);">সহকারী শিক্ষক</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>

        <!-- ডানদিকের সাইডবার এরিয়া (বাকি ৭টি সেকশন) -->
        <div class="right-sidebar">
            
            <!-- ১০. নোটিশ বোর্ড সেকশন -->
            <div class="section-card">
                <h2 class="section-title"><i class="fa-solid fa-bullhorn"></i> নোটিশ বোর্ড</h2>
                <ul class="notice-list">
                    <?php if ($notices_query && mysqli_num_rows($notices_query) > 0): ?>
                        <?php while ($notice = mysqli_fetch_assoc($notices_query)): ?>
                            <li class="notice-item">
                                <span class="notice-date-badge"><?= date('d F, Y', strtotime($notice['post_date'])) ?></span>
                                <a href="page.php?slug=notices"><?= htmlspecialchars($notice['title']) ?></a>
                            </li>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <li class="notice-item">
                            <span class="notice-date-badge">১০ জানুয়ারি, ২০২৬</span>
                            <a href="#">২০২৬ শিক্ষাবর্ষের নতুন বই বিতরণ সংক্রান্ত নোটিশ</a>
                        </li>
                    <?php endif; ?>
                </ul>
                <a href="page.php?slug=notices" style="display: block; text-align: center; margin-top: 15px; font-weight: 600; color: var(--accent-orange); font-size: 0.85rem;">সকল নোটিশ দেখুন &rarr;</a>
            </div>

            <!-- ১২. সাপ্তাহিক ক্লাস রুটিন সেকশন -->
            <div class="section-card">
                <h2 class="section-title"><i class="fa-solid fa-calendar-week"></i> শ্রেণি রুটিন / প্রয়োজনীয় ফাইল</h2>
                <?php if ($downloads_query && mysqli_num_rows($downloads_query) > 0): ?>
                    <?php while ($dl = mysqli_fetch_assoc($downloads_query)): ?>
                        <a href="<?= htmlspecialchars($dl['file_path']) ?>" class="routine-btn" download>
                            <span><?= htmlspecialchars($dl['title']) ?></span>
                            <i class="fa-solid fa-download"></i>
                        </a>
                    <?php endwhile; ?>
                <?php else: ?>
                    <a href="#" class="routine-btn">
                        <span>১ম থেকে ৩য় শ্রেণির রুটিন (PDF)</span>
                        <i class="fa-solid fa-download"></i>
                    </a>
                <?php endif; ?>
            </div>

            <!-- মুক্তিযুদ্ধ কর্নার -->
            <div class="section-card" style="padding: 0;">
                <div class="muktijoddha-corner">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/8/84/Government_Seal_of_Bangladesh.svg" alt="BD Government Seal">
                    <h4> মুক্তিযুদ্ধ কর্নার</h4>
                    <p style="font-size: 0.85rem; opacity: 0.9; margin-bottom: 15px;">আমাদের history ও বীরত্বগাঁথার স্মারক সমৃদ্ধ বিশেষ কর্নার।</p>
                    <a href="page.php?slug=about-us" class="btn btn-accent" style="font-size: 0.8rem; padding: 6px 15px;">ভিজিট করুন</a>
                </div>
            </div>

            <!-- ১৬. ম্যানেজিং কমিটি পরিচিতি -->
            <div class="section-card">
                <h2 class="section-title"><i class="fa-solid fa-sitemap"></i> ম্যানেজিং কমিটি</h2>
                <ul class="committee-list">
                    <?php if ($committee_query && mysqli_num_rows($committee_query) > 0): ?>
                        <?php while ($comm = mysqli_fetch_assoc($committee_query)): ?>
                            <li class="committee-member">
                                <strong><?= htmlspecialchars($comm['name']) ?></strong>
                                <span><?= htmlspecialchars($comm['subject']) ?></span>
                            </li>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <li class="committee-member">
                            <strong>মোঃ আব্দুর রহমান</strong>
                            <span>সভাপতি</span>
                        </li>
                        <li class="committee-member">
                            <strong>মোসাম্মৎ রেহানা বেগম</strong>
                            <span>সদস্য সচিব</span>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>

            <!-- ১৭. সেরা শিক্ষার্থী কর্নার -->
            <div class="section-card">
                <h2 class="section-title"><i class="fa-solid fa-star"></i> মাসের সেরা শিক্ষার্থী</h2>
                <div class="star-student-card">
                    <?php if(!empty($settings['best_student_image'])): ?>
                        <img src="<?= htmlspecialchars($settings['best_student_image']) ?>" alt="Star Student">
                    <?php else: ?>
                        <i class="fa-solid fa-trophy"></i>
                    <?php endif; ?>
                    <h4 style="color: var(--primary-green); font-weight: 700;"><?= htmlspecialchars($settings['best_student_name'] ?? 'সুমাইয়া জাহান মিম') ?></h4>
                    <p style="font-size: 0.85rem; color: var(--text-muted);"><?= htmlspecialchars($settings['best_student_class_roll'] ?? 'শ্রেণি: পঞ্চম, রোল: ০১') ?></p>
                    <p style="font-size: 0.85rem; margin-top: 10px; font-style: italic;"><?= htmlspecialchars($settings['best_student_desc'] ?? 'চমৎকার উপস্থিতি ও ক্লাসে মনোযোগী থাকার জন্য এ মাসের সেরা নির্বাচিত হয়েছে।') ?></p>
                </div>
            </div>

            <!-- ফটো ও ভিডিও গ্যালারি -->
            <div class="section-card">
                <h2 class="section-title"><i class="fa-solid fa-images"></i> ফটো গ্যালারি</h2>
                <div class="gallery-grid">
                    <?php 
                    $gallery_query = mysqli_query($conn, "SELECT * FROM gallery ORDER BY id DESC LIMIT 8");
                    if ($gallery_query && mysqli_num_rows($gallery_query) > 0):
                        while ($photo = mysqli_fetch_assoc($gallery_query)):
                    ?>
                        <img class="gallery-item" src="<?= htmlspecialchars($photo['image_path']) ?>" alt="<?= htmlspecialchars($photo['caption'] ?? '') ?>" onclick="openGalleryModal(this.src, this.alt)">
                    <?php 
                        endwhile;
                    else: 
                    ?>
                        <img class="gallery-item" src="https://images.unsplash.com/photo-1577896851231-70ef18881754?q=80&w=200&auto=format&fit=crop" alt="School Life" onclick="openGalleryModal(this.src, this.alt)">
                        <img class="gallery-item" src="https://images.unsplash.com/photo-1541339907198-e08756dedf3f?q=80&w=200&auto=format&fit=crop" alt="Classroom" onclick="openGalleryModal(this.src, this.alt)">
                        <img class="gallery-item" src="https://images.unsplash.com/photo-1523050854058-8df90110c9f1?q=80&w=200&auto=format&fit=crop" alt="Lab" onclick="openGalleryModal(this.src, this.alt)">
                    <?php endif; ?>
                </div>
            </div>

            <!-- Gallery Modal Popup -->
            <div id="galleryModal" style="display: none; position: fixed; z-index: 9999; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.9); justify-content: center; align-items: center; flex-direction: column;">
                <span onclick="closeGalleryModal()" style="position: absolute; top: 20px; right: 35px; color: #f1f1f1; font-size: 40px; font-weight: bold; cursor: pointer; transition: 0.3s;">&times;</span>
                <img id="galleryModalImg" style="margin: auto; display: block; max-width: 80%; max-height: 80%; border-radius: 8px; box-shadow: 0 4px 20px rgba(0,0,0,0.3);">
                <div id="galleryModalCaption" style="margin: auto; display: block; width: 80%; text-align: center; color: #ccc; padding: 10px 0; font-size: 1.2rem; font-weight: 600;"></div>
            </div>
            
            <script>
                function openGalleryModal(src, alt) {
                    var modal = document.getElementById("galleryModal");
                    var modalImg = document.getElementById("galleryModalImg");
                    var captionText = document.getElementById("galleryModalCaption");
                    modal.style.display = "flex";
                    modalImg.src = src;
                    captionText.innerHTML = alt;
                }
                function closeGalleryModal() {
                    var modal = document.getElementById("galleryModal");
                    modal.style.display = "none";
                }
            </script>

        </div>

    </main>

    <!-- ২০. যোগাযোগ ও ফুটার সেকশন -->
    <footer>
        <div class="footer-grid">
            <div class="footer-col">
                <h3>আমাদের বিদ্যালয়</h3>
                <p><?= htmlspecialchars($settings['footer_about_text'] ?? 'সূর্যমুখী সরকারি প্রাথমিক বিদ্যালয় শিশুদের আধুনিক শিক্ষা ও নৈতিক মানদণ্ড বজায় রেখে আদর্শ মানুষ হিসেবে গড়ে তুলতে কাজ করে যাচ্ছে।') ?></p>
                <div class="social-links" style="display: flex; gap: 10px; margin-top: 15px;">
                    <?php if(!empty($settings['social_facebook'])): ?>
                        <a href="<?= htmlspecialchars($settings['social_facebook']) ?>" style="font-size: 1.2rem; color: #white;"><i class="fa-brands fa-facebook-f"></i></a>
                    <?php endif; ?>
                    <?php if(!empty($settings['social_youtube'])): ?>
                        <a href="<?= htmlspecialchars($settings['social_youtube']) ?>" style="font-size: 1.2rem; color: #white;"><i class="fa-brands fa-youtube"></i></a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="footer-col">
                <h3>গুরুত্বপূর্ণ লিংক</h3>
                <ul style="list-style: none;">
                    <?php 
                    $quick_portals = json_decode($settings['footer_quick_portals'] ?? '[]', true);
                    if (is_array($quick_portals) && !empty($quick_portals)): 
                        foreach ($quick_portals as $portal):
                    ?>
                        <li style="margin-bottom: 8px;"><a href="<?= htmlspecialchars($portal['url'] ?? '#') ?>"><?= htmlspecialchars($portal['title']) ?></a></li>
                    <?php 
                        endforeach;
                    else: 
                    ?>
                        <li style="margin-bottom: 8px;"><a href="http://www.dpe.gov.bd/" target="_blank">প্রাথমিক শিক্ষা অধিদপ্তর</a></li>
                        <li style="margin-bottom: 8px;"><a href="https://mopme.gov.bd/" target="_blank">প্রাথমিক ও গণশিক্ষা মন্ত্রণালয়</a></li>
                        <li style="margin-bottom: 8px;"><a href="http://www.nctb.gov.bd/" target="_blank">এনসিটিবি (NCTB) পাঠ্যবই</a></li>
                    <?php endif; ?>
                </ul>
            </div>
            <div class="footer-col">
                <h3>যোগাযোগের ঠিকানা</h3>
                <p><i class="fa-solid fa-location-dot" style="color: var(--accent-orange); margin-right: 8px;"></i> <?= htmlspecialchars($settings['contact_address'] ?? 'দিনাজপুর, বাংলাদেশ।') ?></p>
                <p><i class="fa-solid fa-phone" style="color: var(--accent-orange); margin-right: 8px;"></i> ফোন: <?= htmlspecialchars($settings['contact_phone'] ?? '+৮৮০১৭১১-XXXXXX') ?></p>
            </div>
            <div class="footer-col">
                <h3>আমাদের অবস্থান</h3>
                <div class="footer-map-placeholder">
                    <span><?= htmlspecialchars($settings['school_name'] ?? 'সূর্যমুখী সরকারি প্রাথমিক বিদ্যালয়') ?></span>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p><?= htmlspecialchars($settings['footer_copyright_text'] ?? '&copy; ২০২৬ সূর্যমুখী সরকারি প্রাথমিক বিদ্যালয়। সর্বস্বত্ব সংরক্ষিত।') ?></p>
            <p style="font-size: 0.75rem; margin-top: 5px; color: #64748b;"><?= htmlspecialchars($settings['footer_designed_text'] ?? 'প্রাথমিক ও গণশিক্ষা মন্ত্রণালয়, গণপ্রজাতন্ত্রী বাংলাদেশ সরকার কর্তৃক অনুমোদিত।') ?></p>
        </div>
    </footer>

    <!-- মোবাইল মেনু টগল স্ক্রিপ্ট -->
    <script>
        function toggleMenu() {
            var menu = document.getElementById("nav-menu");
            if (menu.classList.contains("active")) {
                menu.classList.remove("active");
            } else {
                menu.classList.add("active");
            }
        }
    </script>
</body>
</html>
