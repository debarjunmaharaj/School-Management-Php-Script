<?php
// FILE: /home2.php (Dhaka Metropolitan University Style Home Page - Complete)
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/functions.php';

$settings = get_all_settings($conn);

// Get pages for navigation
$pages_nav_result = mysqli_query($conn, "SELECT title, slug FROM pages LIMIT 5");

// Notices
$notices_result = mysqli_query($conn, "SELECT * FROM notices ORDER BY post_date DESC, id DESC LIMIT 5");

// Events
$events_result = mysqli_query($conn, "SELECT * FROM events WHERE event_date >= CURDATE() ORDER BY event_date ASC LIMIT 3");

// Posts (Latest News)
$posts_result = mysqli_query($conn, "SELECT * FROM posts ORDER BY created_at DESC LIMIT 3");

// Featured Teachers
$featured_teachers_result = mysqli_query($conn, "SELECT * FROM teachers WHERE is_featured = 1 ORDER BY id ASC LIMIT 3");

// Downloads
$downloads_result = mysqli_query($conn, "SELECT * FROM downloads WHERE file_type = 'pdf' ORDER BY id DESC LIMIT 3");

// Videos
$videos_result = mysqli_query($conn, "SELECT * FROM videos ORDER BY id DESC LIMIT 4");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($settings['school_name'] ?? 'Dhaka Metropolitan University') ?> | Excellence in Higher Education</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Remixicon Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-blue: #0b2240; /* Prestigious Deep Blue */
            --primary-light: #1e3a5f;
            --accent-gold: #c5a059; /* Rich Gold Accent */
            --accent-gold-hover: #b08c46;
            --secondary-red: #8b0000; /* Subtle contrast red */
            --text-dark: #222d3a;
            --text-light: #f4f6f9;
            --gray-bg: #f8fafc;
            --gray-border: #e2e8f0;
            --transition: all 0.3s ease-in-out;
            --max-width: 1200px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            color: var(--text-dark);
            background-color: #ffffff;
            line-height: 1.6;
        }

        /* Top Bar Info */
        .top-bar {
            background-color: var(--primary-blue);
            color: var(--text-light);
            font-size: 0.8rem;
            padding: 10px 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .top-bar-left span {
            margin-right: 20px;
        }

        .top-bar-left i {
            color: var(--accent-gold);
            margin-right: 6px;
        }

        .top-bar-right {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .top-bar-right a {
            color: var(--text-light);
            text-decoration: none;
            transition: var(--transition);
        }

        .top-bar-right a:hover {
            color: var(--accent-gold);
        }

        .ugc-badge {
            background-color: var(--accent-gold);
            color: var(--primary-blue);
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 0.75rem;
        }

        /* Navigation Header */
        header {
            background-color: #ffffff;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .nav-container {
            max-width: var(--max-width);
            margin: 0 auto;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo-area {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .logo-icon {
            width: 55px;
            height: 55px;
            background-color: var(--primary-blue);
            border: 2px solid var(--accent-gold);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--accent-gold);
            font-size: 1.6rem;
        }

        .logo-text h1 {
            font-size: 1.3rem;
            font-weight: 800;
            color: var(--primary-blue);
            line-height: 1.1;
        }

        .logo-text p {
            font-size: 0.75rem;
            color: var(--accent-gold);
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        /* Nav Menu Links */
        nav ul {
            display: flex;
            list-style: none;
            gap: 22px;
        }

        nav ul li a {
            color: var(--primary-blue);
            font-weight: 600;
            font-size: 0.9rem;
            text-decoration: none;
            padding: 8px 12px;
            border-radius: 4px;
            transition: var(--transition);
        }

        nav ul li a:hover {
            background-color: var(--primary-blue);
            color: var(--text-light);
        }

        .btn-apply-nav {
            background-color: var(--accent-gold);
            color: var(--primary-blue) !important;
        }

        .btn-apply-nav:hover {
            background-color: var(--accent-gold-hover) !important;
            color: #ffffff !important;
        }

        .menu-toggle {
            display: none;
            font-size: 1.5rem;
            color: var(--primary-blue);
            cursor: pointer;
        }

        /* Hero Showcase Section */
        .hero {
            position: relative;
            background: linear-gradient(rgba(11, 34, 64, 0.9), rgba(11, 34, 64, 0.85)), url('<?= htmlspecialchars($settings['hero_background_image'] ?? 'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?q=80&w=1200&auto=format&fit=crop') ?>') center/cover;
            color: var(--text-light);
            padding: 120px 20px 140px;
            text-align: center;
        }

        .hero-inner {
            max-width: 850px;
            margin: 0 auto;
        }

        .hero-inner h2 {
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 20px;
            line-height: 1.2;
        }

        .hero-inner p {
            font-size: 1.1rem;
            margin-bottom: 35px;
            opacity: 0.9;
        }

        .hero-ctas {
            display: flex;
            justify-content: center;
            gap: 15px;
            flex-wrap: wrap;
        }

        .btn-large {
            padding: 14px 32px;
            border-radius: 4px;
            font-weight: 600;
            text-decoration: none;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .btn-gold {
            background-color: var(--accent-gold);
            color: var(--primary-blue);
        }

        .btn-gold:hover {
            background-color: var(--accent-gold-hover);
            color: #ffffff;
        }

        .btn-outline {
            border: 2px solid #ffffff;
            color: #ffffff;
        }

        .btn-outline:hover {
            background-color: #ffffff;
            color: var(--primary-blue);
        }

        /* Quick Portal Icons Section */
        .portals-section {
            max-width: var(--max-width);
            margin: -60px auto 50px;
            padding: 0 20px;
            position: relative;
            z-index: 10;
        }

        .portals-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 20px;
        }

        .portal-item {
            background: #ffffff;
            padding: 30px 20px;
            border-radius: 6px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            border-bottom: 4px solid var(--accent-gold);
            transition: var(--transition);
            text-align: center;
        }

        .portal-item:hover {
            transform: translateY(-5px);
        }

        .portal-item i {
            font-size: 2.2rem;
            color: var(--primary-blue);
            margin-bottom: 15px;
        }

        .portal-item h3 {
            font-size: 1.1rem;
            margin-bottom: 10px;
            color: var(--primary-blue);
        }

        .portal-item p {
            font-size: 0.85rem;
            color: #64748b;
        }

        /* Stats Strip */
        .stats-section {
            background-color: var(--primary-blue);
            color: var(--text-light);
            padding: 50px 20px;
        }

        .stats-grid {
            max-width: var(--max-width);
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 30px;
            text-align: center;
        }

        .stat-card h3 {
            font-size: 2.5rem;
            color: var(--accent-gold);
            font-weight: 800;
            margin-bottom: 5px;
        }

        .stat-card p {
            font-size: 0.9rem;
            opacity: 0.85;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Main Content Split Layout */
        .main-content {
            max-width: var(--max-width);
            margin: 60px auto;
            padding: 0 20px;
            display: grid;
            grid-template-columns: 8fr 4fr;
            gap: 40px;
        }

        /* Common Card Panel */
        .panel-card {
            background: #ffffff;
            border-radius: 6px;
            padding: 35px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.03);
            border: 1px solid var(--gray-border);
            margin-bottom: 30px;
        }

        .panel-heading {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-blue);
            margin-bottom: 25px;
            padding-bottom: 12px;
            border-bottom: 2px solid var(--gray-border);
            position: relative;
        }

        .panel-heading::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: -2px;
            width: 60px;
            height: 2px;
            background-color: var(--accent-gold);
        }

        /* VC Message Styles */
        .vc-profile {
            display: flex;
            gap: 25px;
            align-items: flex-start;
            flex-wrap: wrap;
        }

        .vc-img {
            width: 180px;
            height: 220px;
            object-fit: cover;
            border-radius: 6px;
            border: 3px solid var(--gray-border);
        }

        .vc-message {
            flex: 1;
            min-width: 280px;
        }

        .vc-message p {
            margin-bottom: 15px;
            font-size: 0.95rem;
            color: #475569;
        }

        .vc-sign {
            margin-top: 20px;
        }

        .vc-sign h4 {
            font-size: 1.05rem;
            color: var(--primary-blue);
            font-weight: 700;
        }

        .vc-sign span {
            font-size: 0.85rem;
            color: var(--accent-gold);
            font-weight: 500;
        }

        /* News Sections */
        .news-box {
            border-left: 4px solid var(--accent-gold);
            padding-left: 15px;
            margin-bottom: 20px;
        }

        .news-box h4 {
            font-size: 1.05rem;
            color: var(--primary-blue);
            font-weight: 600;
        }

        .news-box span {
            font-size: 0.75rem;
            color: #94a3b8;
            display: block;
            margin: 4px 0 8px;
        }

        .news-box a {
            font-size: 0.85rem;
            color: var(--accent-gold);
            text-decoration: none;
            font-weight: 600;
        }

        /* Academic Faculties Grid */
        .faculties-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .faculty-box {
            background-color: var(--gray-bg);
            border: 1px solid var(--gray-border);
            border-radius: 6px;
            padding: 20px;
            transition: var(--transition);
        }

        .faculty-box:hover {
            border-color: var(--accent-gold);
            background-color: #ffffff;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }

        .faculty-box i {
            font-size: 1.8rem;
            color: var(--accent-gold);
            margin-bottom: 12px;
        }

        .faculty-box h4 {
            font-size: 1rem;
            font-weight: 700;
            color: var(--primary-blue);
            margin-bottom: 8px;
        }

        .faculty-box p {
            font-size: 0.8rem;
            color: #64748b;
        }

        /* Research Section */
        .research-item {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--gray-border);
        }

        .research-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .research-meta {
            background-color: var(--gray-bg);
            padding: 10px;
            border-radius: 4px;
            text-align: center;
            min-width: 80px;
            height: fit-content;
        }

        .research-meta span {
            display: block;
            font-size: 0.75rem;
            color: var(--accent-gold);
            font-weight: 600;
        }

        .research-meta strong {
            font-size: 1.2rem;
            color: var(--primary-blue);
        }

        .research-info h4 {
            font-size: 0.95rem;
            color: var(--primary-blue);
            margin-bottom: 5px;
            font-weight: 600;
        }

        .research-info p {
            font-size: 0.8rem;
            color: #64748b;
        }

        /* Sidebar Widgets */
        .sidebar-widget {
            background: #ffffff;
            border-radius: 6px;
            padding: 25px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.03);
            border: 1px solid var(--gray-border);
            margin-bottom: 30px;
        }

        .widget-title {
            font-size: 1.15rem;
            font-weight: 700;
            color: var(--primary-blue);
            margin-bottom: 20px;
            padding-bottom: 8px;
            border-bottom: 2px solid var(--primary-blue);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Notice Board Widget styling */
        .notice-board-list {
            list-style: none;
        }

        .notice-board-item {
            padding: 15px 0;
            border-bottom: 1px dashed var(--gray-border);
        }

        .notice-board-item:last-child {
            border-bottom: none;
        }

        .notice-label {
            font-size: 0.7rem;
            background-color: var(--secondary-red);
            color: #ffffff;
            padding: 2px 6px;
            border-radius: 3px;
            font-weight: 600;
            display: inline-block;
            margin-bottom: 6px;
        }

        .notice-title {
            font-size: 0.88rem;
            font-weight: 600;
            color: var(--text-dark);
            line-height: 1.4;
            display: block;
            transition: var(--transition);
        }

        .notice-title:hover {
            color: var(--accent-gold);
        }

        .notice-date {
            font-size: 0.75rem;
            color: #94a3b8;
            margin-top: 5px;
            display: block;
        }

        /* Events Widget styling */
        .event-card-small {
            display: flex;
            gap: 15px;
            margin-bottom: 15px;
        }

        .event-date-box {
            background-color: var(--primary-blue);
            color: #ffffff;
            padding: 8px;
            border-radius: 4px;
            text-align: center;
            min-width: 60px;
        }

        .event-date-box span {
            display: block;
            font-size: 0.7rem;
            text-transform: uppercase;
        }

        .event-date-box strong {
            font-size: 1.1rem;
        }

        .event-details h4 {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--primary-blue);
        }

        .event-details p {
            font-size: 0.75rem;
            color: #64748b;
        }

        /* Admission Fee Calculator Callout */
        .calculator-widget {
            background: linear-gradient(135deg, var(--primary-blue), var(--primary-light));
            color: #ffffff;
            padding: 30px 25px;
            border-radius: 6px;
            text-align: center;
        }

        .calculator-widget h3 {
            font-size: 1.25rem;
            color: var(--accent-gold);
            margin-bottom: 12px;
        }

        .calculator-widget p {
            font-size: 0.85rem;
            margin-bottom: 20px;
            opacity: 0.9;
        }

        /* Downloads styling */
        .download-btn {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 16px;
            background-color: var(--gray-bg);
            border: 1px solid var(--gray-border);
            border-radius: 6px;
            color: var(--primary-blue);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            margin-bottom: 10px;
            transition: var(--transition);
        }

        .download-btn:hover {
            background-color: var(--primary-blue);
            color: #ffffff;
            border-color: var(--primary-blue);
        }

        /* Meet Our Teachers Styles */
        .teachers-section {
            background-color: var(--gray-bg);
            padding: 60px 20px;
            border-top: 1px solid var(--gray-border);
        }

        .teachers-inner {
            max-width: var(--max-width);
            margin: 0 auto;
        }

        .teachers-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 30px;
            margin-top: 35px;
        }

        .teacher-card {
            background-color: #ffffff;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.02);
            text-align: center;
            border: 1px solid var(--gray-border);
            transition: var(--transition);
        }

        .teacher-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
            border-color: var(--accent-gold);
        }

        .teacher-card img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            margin: 0 auto 20px;
            border: 4px solid var(--gray-border);
        }

        .teacher-card h4 {
            font-size: 1.15rem;
            color: var(--primary-blue);
            font-weight: 700;
        }

        .teacher-card .subject {
            font-size: 0.9rem;
            color: var(--accent-gold);
            font-weight: 600;
            margin: 5px 0 10px;
        }

        .teacher-card .exp {
            font-size: 0.8rem;
            color: #64748b;
        }

        /* Video Gallery Section */
        .video-section {
            background-color: var(--primary-blue);
            color: #ffffff;
            padding: 60px 20px;
        }

        .video-inner {
            max-width: var(--max-width);
            margin: 0 auto;
        }

        .video-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-top: 35px;
        }

        .video-card {
            position: relative;
            border-radius: 6px;
            overflow: hidden;
            background-color: #000000;
            display: block;
            text-decoration: none;
            color: #ffffff;
        }

        .video-card img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            opacity: 0.8;
            transition: var(--transition);
        }

        .video-card:hover img {
            opacity: 0.6;
        }

        .video-card .play-btn {
            position: absolute;
            top: 75px;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 3rem;
            color: var(--accent-gold);
            text-shadow: 0 2px 10px rgba(0,0,0,0.5);
            pointer-events: none;
        }

        .video-card h4 {
            padding: 15px;
            font-size: 0.9rem;
            font-weight: 600;
            background-color: rgba(11, 34, 64, 0.95);
        }

        /* Campus Facilities Grid */
        .facilities-layout {
            background-color: #ffffff;
            padding: 60px 20px;
            border-top: 1px solid var(--gray-border);
        }

        .facilities-inner {
            max-width: var(--max-width);
            margin: 0 auto;
        }

        .facilities-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
            margin-top: 35px;
        }

        .facility-card {
            background-color: #ffffff;
            border-radius: 6px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.02);
            transition: var(--transition);
            border: 1px solid var(--gray-border);
        }

        .facility-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
        }

        .facility-img {
            height: 180px;
            width: 100%;
            object-fit: cover;
        }

        .facility-desc {
            padding: 20px;
        }

        .facility-desc h4 {
            font-size: 1.05rem;
            color: var(--primary-blue);
            margin-bottom: 10px;
        }

        .facility-desc p {
            font-size: 0.85rem;
            color: #64748b;
        }

        /* Footer */
        footer {
            background-color: var(--primary-blue);
            color: #cbd5e1;
            padding: 70px 20px 30px;
        }

        .footer-inner {
            max-width: var(--max-width);
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
            gap: 40px;
        }

        .footer-column h3 {
            color: #ffffff;
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 25px;
            position: relative;
            padding-bottom: 10px;
        }

        .footer-column h3::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 35px;
            height: 2px;
            background-color: var(--accent-gold);
        }

        .footer-column p {
            font-size: 0.85rem;
            line-height: 1.6;
            margin-bottom: 15px;
        }

        .footer-links {
            list-style: none;
        }

        .footer-links li {
            margin-bottom: 10px;
        }

        .footer-links li a {
            color: #cbd5e1;
            text-decoration: none;
            font-size: 0.85rem;
            transition: var(--transition);
        }

        .footer-links li a:hover {
            color: var(--accent-gold);
            padding-left: 5px;
        }

        .social-row {
            display: flex;
            gap: 12px;
            margin-top: 20px;
        }

        .social-row a {
            width: 36px;
            height: 36px;
            border-radius: 4px;
            background-color: rgba(255,255,255,0.08);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-size: 1rem;
            transition: var(--transition);
        }

        .social-row a:hover {
            background-color: var(--accent-gold);
            color: var(--primary-blue);
        }

        .footer-bottom {
            max-width: var(--max-width);
            margin: 50px auto 0;
            padding-top: 25px;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
            font-size: 0.8rem;
        }

        /* Mobile Adjustments */
        @media (max-width: 992px) {
            .main-content {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            nav ul {
                display: none;
                flex-direction: column;
                background-color: #ffffff;
                position: absolute;
                top: 85px;
                left: 0;
                width: 100%;
                padding: 20px;
                box-shadow: 0 10px 15px rgba(0,0,0,0.05);
            }

            nav ul.active {
                display: flex;
            }

            .menu-toggle {
                display: block;
            }

            .hero {
                padding: 80px 20px 100px;
            }

            .hero-inner h2 {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>

    <!-- Upper Information Row -->
    <div class="top-bar">
        <div class="top-bar-left">
            <span><i class="fa-solid fa-phone"></i> <?= htmlspecialchars($settings['contact_phone'] ?? '+880 2-55554444') ?></span>
            <span><i class="fa-solid fa-envelope"></i> <?= htmlspecialchars($settings['contact_email'] ?? 'admissions@dmu.edu.bd') ?></span>
        </div>
        <div class="top-bar-right">
            <span class="ugc-badge">UGC Approved</span>
            <a href="login.php?portal=student"><i class="fa-solid fa-user-graduate"></i> Student Portal</a>
            <a href="login.php?portal=teacher"><i class="fa-solid fa-chalkboard-user"></i> Teacher Portal</a>
            <a href="login.php?portal=parent"><i class="fa-solid fa-users"></i> Parent Portal</a>
        </div>
    </div>

    <!-- Sticky Navigation Brand Header -->
    <header>
        <div class="nav-container">
            <div class="logo-area">
                <?php if (!empty($settings['site_logo'])): ?>
                    <img src="<?= htmlspecialchars($settings['site_logo']) ?>" alt="logo" style="width: 55px; height: 55px; border-radius: 50%; background: white; padding: 2px; border: 2px solid var(--accent-gold); object-fit: contain;">
                <?php else: ?>
                    <div class="logo-icon">
                        <i class="fa-solid fa-graduation-cap"></i>
                    </div>
                <?php endif; ?>
                <div class="logo-text">
                    <h1><?= htmlspecialchars($settings['school_name'] ?? 'ঢাকা মেট্রোপলিটন ইউনিভার্সিটি') ?></h1>
                    <p><?= htmlspecialchars($settings['school_tagline'] ?? 'Dhaka Metropolitan University') ?></p>
                </div>
            </div>
            <nav>
                <ul id="nav-list">
                    <li><a href="index.php">Home</a></li>
                    <?php while($page = mysqli_fetch_assoc($pages_nav_result)): ?>
                        <li><a href="page.php?slug=<?= htmlspecialchars($page['slug']) ?>"><?= htmlspecialchars($page['title']) ?></a></li>
                    <?php endwhile; ?>
                    <li><a href="library.php">Library</a></li>
                    <li><a href="contact.php">Contact Us</a></li>
                    <li><a href="admission.php" class="btn-apply-nav">Admission Open</a></li>
                </ul>
            </nav>
            <div class="menu-toggle" onclick="toggleNavigationMenu()">
                <i class="fa-solid fa-bars"></i>
            </div>
        </div>
    </header>

    <!-- Visual Hero Area -->
    <section class="hero">
        <div class="hero-inner">
            <h2>Shaping Leaders of Tomorrow</h2>
            <p>Welcome to <?= htmlspecialchars($settings['school_name'] ?? 'Dhaka Metropolitan University') ?>, a hub for research, multi-disciplinary studies, and specialized professional growth.</p>
            <div class="hero-ctas">
                <a href="admission.php" class="btn-large btn-gold"><?= htmlspecialchars($settings['hero_button_text'] ?? 'Explore Programs') ?></a>
                <a href="#" class="btn-large btn-outline">Tuition Fee Waiver Guidelines</a>
            </div>
        </div>
    </section>

    <!-- Fast Pathway Portals -->
    <div class="portals-section">
        <div class="portals-grid">
            <div class="portal-item">
                <i class="fa-solid fa-book-bookmark"></i>
                <h3>Undergraduate Degrees</h3>
                <p>Explore engineering, business administration, law, and liberal arts pathways.</p>
            </div>
            <div class="portal-item">
                <i class="fa-solid fa-user-tie"></i>
                <h3>Postgraduate Studies</h3>
                <p>Master degree opportunities designed for executive and specialized roles.</p>
            </div>
            <div class="portal-item">
                <i class="fa-solid fa-wallet"></i>
                <h3>Scholarships & Aids</h3>
                <p>Waiver schedules based on SSC/HSC exam scores and specialized performance metrics.</p>
            </div>
        </div>
    </div>

    <!-- Institutional Key Statistics Strip -->
    <section class="stats-section">
        <div class="stats-grid">
            <div class="stat-card">
                <h3>14,000+</h3>
                <p>Enrolled Students</p>
            </div>
            <div class="stat-card">
                <h3>480+</h3>
                <p>Faculty Members</p>
            </div>
            <div class="stat-card">
                <h3>32</h3>
                <p>State-Of-The-Art Labs</p>
            </div>
            <div class="stat-card">
                <h3>45</h3>
                <p>International Affiliates</p>
            </div>
        </div>
    </section>

    <!-- Main Dynamic Layout Container -->
    <main class="main-content">
        
        <!-- Left Side Primary Columns -->
        <div class="primary-col">
            
            <!-- Welcome Address from Vice Chancellor -->
            <div class="panel-card">
                <h2 class="panel-heading"><?= htmlspecialchars($settings['principal_message_title'] ?? "Vice Chancellor's Welcome Message") ?></h2>
                <div class="vc-profile">
                    <img class="vc-img" src="<?= htmlspecialchars($settings['principal_photo_url'] ?? 'https://images.unsplash.com/photo-1560250097-0b93528c311a?q=80&w=200&auto=format&fit=crop') ?>" alt="Vice Chancellor">
                    <div class="vc-message">
                        <p><?= htmlspecialchars($settings['principal_message_content'] ?? 'Welcome to Dhaka Metropolitan University. As we look ahead, we remain focused on fostering an ecosystem of learning, exploration, and research.') ?></p>
                        <div class="vc-sign">
                            <h4><?= htmlspecialchars($settings['principal_message_name'] ?? 'Prof. Dr. M. Masum Chowdhury') ?></h4>
                            <span>Vice Chancellor, <?= htmlspecialchars($settings['school_name'] ?? 'Dhaka Metropolitan University') ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Latest News (Posts) -->
            <div class="panel-card">
                <h2 class="panel-heading">Latest News</h2>
                <?php if ($posts_result && mysqli_num_rows($posts_result) > 0): ?>
                    <?php while($post = mysqli_fetch_assoc($posts_result)): ?>
                    <div class="news-box">
                        <h4><?= htmlspecialchars($post['title']) ?></h4>
                        <span>Posted on: <?= date('F j, Y', strtotime($post['created_at'])) ?></span>
                        <a href="post.php?slug=<?= htmlspecialchars($post['slug']) ?>">Read More &rarr;</a>
                    </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p style="font-size: 0.9rem; color: #64748b;">No recent news found.</p>
                <?php endif; ?>
            </div>

            <!-- Dynamic Faculties Section -->
            <div class="panel-card">
                <h2 class="panel-heading">Academic Faculties</h2>
                <p>Our undergraduate and postgraduate degree operations span multiple core faculties authorized by the UGC:</p>
                <div class="faculties-grid">
                    <div class="faculty-box">
                        <i class="fa-solid fa-microchip"></i>
                        <h4>Science & Engineering</h4>
                        <p>Featuring Computer Science, EEE, Civil Engineering, and Robotics labs.</p>
                    </div>
                    <div class="faculty-box">
                        <i class="fa-solid fa-chart-line"></i>
                        <h4>Business Studies</h4>
                        <p>Professional degrees covering BBA, MBA, Finance, and Supply Chain structures.</p>
                    </div>
                    <div class="faculty-box">
                        <i class="fa-solid fa-scale-balanced"></i>
                        <h4>Faculty of Law</h4>
                        <p>Structured LLB & LLM tracks emphasizing legal research and mock court cases.</p>
                    </div>
                    <div class="faculty-box">
                        <i class="fa-solid fa-language"></i>
                        <h4>Arts & Social Sciences</h4>
                        <p>Offering English language training, economics and development studies.</p>
                    </div>
                </div>
            </div>

            <!-- Research & Journals -->
            <div class="panel-card">
                <h2 class="panel-heading">Research & Publications</h2>
                <p style="margin-bottom: 20px;">Highlighting recent scientific and humanitarian work led by our dedicated researchers:</p>
                <?php
                $research_query = mysqli_query($conn, "SELECT * FROM research_publications ORDER BY id DESC LIMIT 3");
                if ($research_query && mysqli_num_rows($research_query) > 0):
                    while ($r_pub = mysqli_fetch_assoc($research_query)):
                ?>
                    <a href="research_details.php?id=<?= $r_pub['id'] ?>" class="research-item" style="text-decoration: none; color: inherit; display: flex; margin-bottom: 15px;">
                        <div class="research-meta">
                            <span><?= htmlspecialchars($r_pub['volume'] ?? 'Vol. --') ?></span>
                            <strong><?= htmlspecialchars($r_pub['year'] ?? '----') ?></strong>
                        </div>
                        <div class="research-info">
                            <h4><?= htmlspecialchars($r_pub['title']) ?></h4>
                            <p><?= htmlspecialchars(substr(strip_tags($r_pub['content']), 0, 120)) ?>...</p>
                        </div>
                    </a>
                <?php 
                    endwhile;
                else: 
                ?>
                    <p style="color: #666; font-size: 0.9rem;">No research publications found.</p>
                <?php endif; ?>
            </div>

        </div>

        <!-- Right Side Sidebar Widgets -->
        <div class="sidebar-col">
            
            <!-- Real-time Notice Board -->
            <div class="sidebar-widget">
                <h3 class="widget-title"><i class="fa-solid fa-bell"></i> Notice Board</h3>
                <ul class="notice-board-list">
                    <?php if ($notices_result && mysqli_num_rows($notices_result) > 0): ?>
                        <?php while($notice = mysqli_fetch_assoc($notices_result)): ?>
                        <li class="notice-board-item">
                            <span class="notice-label">Notice</span>
                            <a href="page.php?slug=notices" class="notice-title"><?= htmlspecialchars($notice['title']) ?></a>
                            <span class="notice-date"><?= date('M d, Y', strtotime($notice['post_date'])) ?></span>
                        </li>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <li class="notice-board-item">No notices posted.</li>
                    <?php endif; ?>
                </ul>
                <a href="#" style="display: block; text-align: center; margin-top: 15px; font-size: 0.8rem; font-weight: 600; color: var(--primary-blue); text-decoration: none;">Browse Notice Archives &rarr;</a>
            </div>

            <!-- Quick Downloads -->
            <div class="sidebar-widget">
                <h3 class="widget-title"><i class="fa-solid fa-download"></i> Quick Downloads</h3>
                <?php if ($downloads_result && mysqli_num_rows($downloads_result) > 0): ?>
                    <?php while($download = mysqli_fetch_assoc($downloads_result)): ?>
                    <a href="<?= htmlspecialchars($download['file_path']) ?>" download class="download-btn">
                        <span><?= htmlspecialchars($download['title']) ?></span>
                        <i class="ri-download-2-line"></i>
                    </a>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p style="font-size: 0.85rem; color: #64748b;">No downloads available.</p>
                <?php endif; ?>
            </div>

            <!-- Tuition Waiver Estimator Callout -->
            <div class="sidebar-widget calculator-widget">
                <h3>Waiver Calculator</h3>
                <p>Calculate your tuition discount automatically based on your SSC and HSC GPA results.</p>
                <a href="#" class="btn-large btn-gold" style="font-size: 0.85rem; padding: 10px 20px; display: inline-block;">Estimate Discount</a>
            </div>

            <!-- Academic Calendar & Event schedules -->
            <div class="sidebar-widget">
                <h3 class="widget-title"><i class="fa-solid fa-calendar-days"></i> Upcoming Events</h3>
                
                <?php if ($events_result && mysqli_num_rows($events_result) > 0): ?>
                    <?php while($event = mysqli_fetch_assoc($events_result)): ?>
                    <div class="event-card-small">
                        <div class="event-date-box">
                            <span><?= strtoupper(date('M', strtotime($event['event_date']))) ?></span>
                            <strong><?= date('d', strtotime($event['event_date'])) ?></strong>
                        </div>
                        <div class="event-details">
                            <h4><?= htmlspecialchars($event['title']) ?></h4>
                            <p><i class="fa-regular fa-clock"></i> <?= date('g:i A', strtotime($event['event_date'])) ?> | <?= htmlspecialchars($event['location']) ?></p>
                        </div>
                    </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p style="font-size: 0.85rem; color: #64748b;">No upcoming events found.</p>
                <?php endif; ?>
            </div>

            <!-- Central Services & Facilities -->
            <div class="sidebar-widget">
                <h3 class="widget-title"><i class="fa-solid fa-briefcase"></i> Career Services</h3>
                <p style="font-size: 0.85rem; color: #64748b; margin-bottom: 12px;">Helping our graduates transit smoothly into target corporate pathways through counseling, mock interviews, and job fairs.</p>
                <a href="#" style="font-size: 0.85rem; font-weight: 600; color: var(--accent-gold); text-decoration: none;">Career Placement Portal &rarr;</a>
            </div>

        </div>

    </main>

    <!-- Meet Our Teachers Section -->
    <section class="teachers-section">
        <div class="teachers-inner">
            <h2 class="panel-heading" style="text-align: center; margin-bottom: 10px;">Meet Our Teachers</h2>
            <p style="text-align: center;">Our highly qualified faculty members are committed to educational excellence:</p>
            <div class="teachers-grid">
                <?php if ($featured_teachers_result && mysqli_num_rows($featured_teachers_result) > 0): ?>
                    <?php while($teacher = mysqli_fetch_assoc($featured_teachers_result)): ?>
                    <div class="teacher-card">
                        <img src="<?= htmlspecialchars($teacher['image_url']) ?>" alt="<?= htmlspecialchars($teacher['name']) ?>">
                        <h4><?= htmlspecialchars($teacher['name']) ?></h4>
                        <div class="subject"><?= htmlspecialchars($teacher['subject']) ?></div>
                        <div class="exp"><?= htmlspecialchars($teacher['experience']) ?></div>
                    </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p style="text-align: center; width: 100%; color: #64748b;">No featured teachers found.</p>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Video Gallery Section -->
    <section class="video-section">
        <div class="video-inner">
            <h2 class="panel-heading" style="margin-bottom: 10px; color: #ffffff;">Video Gallery</h2>
            <p>A glimpse into life at <?= htmlspecialchars($settings['school_name'] ?? 'Dhaka Metropolitan University') ?>:</p>
            <div class="video-grid">
                <?php if ($videos_result && mysqli_num_rows($videos_result) > 0): ?>
                    <?php while($video = mysqli_fetch_assoc($videos_result)): ?>
                    <a href="https://www.youtube.com/watch?v=<?= htmlspecialchars($video['youtube_video_id']) ?>" target="_blank" class="video-card">
                        <img src="https://img.youtube.com/vi/<?= htmlspecialchars($video['youtube_video_id']) ?>/mqdefault.jpg" alt="<?= htmlspecialchars($video['title']) ?>">
                        <div class="play-btn"><i class="ri-play-circle-fill"></i></div>
                        <h4><?= htmlspecialchars($video['title']) ?></h4>
                    </a>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p style="color: #cbd5e1;">No videos available.</p>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Additional Infrastructure Layout -->
    <section class="facilities-layout">
        <div class="facilities-inner">
            <h2 class="panel-heading" style="margin-bottom: 10px;">Our Campus Infrastructure</h2>
            <p>Developing robust learning spaces to support complete research and development goals:</p>
            <div class="facilities-grid">
                <div class="facility-card">
                    <img class="facility-img" src="<?= htmlspecialchars($settings['infra_c1_img'] ?? 'https://images.unsplash.com/photo-1541339907198-e08756dedf3f?q=80&w=600&auto=format&fit=crop') ?>" alt="Library">
                    <div class="facility-desc">
                        <h4><?= htmlspecialchars($settings['infra_c1_title'] ?? 'Central Digital Library') ?></h4>
                        <p><?= htmlspecialchars($settings['infra_c1_desc'] ?? 'Housing over 50,000 reference volumes alongside access to premium research databases.') ?></p>
                    </div>
                </div>
                <div class="facility-card">
                    <img class="facility-img" src="<?= htmlspecialchars($settings['infra_c2_img'] ?? 'https://images.unsplash.com/photo-1581092160607-ee22621dd758?q=80&w=600&auto=format&fit=crop') ?>" alt="Labs">
                    <div class="facility-desc">
                        <h4><?= htmlspecialchars($settings['infra_c2_title'] ?? 'Modern Physics & CSE Labs') ?></h4>
                        <p><?= htmlspecialchars($settings['infra_c2_desc'] ?? 'Fully updated instrumentation arrays designed for practical academic evaluations.') ?></p>
                    </div>
                </div>
                <div class="facility-card">
                    <img class="facility-img" src="<?= htmlspecialchars($settings['infra_c3_img'] ?? 'https://images.unsplash.com/photo-1577896851231-70ef18881754?q=80&w=600&auto=format&fit=crop') ?>" alt="Campus">
                    <div class="facility-desc">
                        <h4><?= htmlspecialchars($settings['infra_c3_title'] ?? 'Sports & Physical Rec Center') ?></h4>
                        <p><?= htmlspecialchars($settings['infra_c3_desc'] ?? 'Fostering teamwork, health, and leadership skills through competitive tournaments.') ?></p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer Area -->
    <footer>
        <div class="footer-inner">
            <div class="footer-column">
                <h3>About <?= htmlspecialchars($settings['school_name'] ?? 'DMU') ?></h3>
                <p><?= htmlspecialchars($settings['footer_about_text'] ?? 'Authorized by the Government of Bangladesh and recognized by the University Grants Commission (UGC), DMU emphasizes research, innovation, and ethical development.') ?></p>
                <div class="social-row">
                    <?php if(!empty($settings['social_facebook'])): ?>
                        <a href="<?= htmlspecialchars($settings['social_facebook']) ?>"><i class="fa-brands fa-facebook-f"></i></a>
                    <?php endif; ?>
                    <?php if(!empty($settings['social_twitter'])): ?>
                        <a href="<?= htmlspecialchars($settings['social_twitter']) ?>"><i class="fa-brands fa-x-twitter"></i></a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="footer-column">
                <h3>Academic Units</h3>
                <ul class="footer-links">
                    <?php 
                    $academic_units = json_decode($settings['footer_academic_units'] ?? '[]', true);
                    if (is_array($academic_units) && !empty($academic_units)): 
                        foreach ($academic_units as $unit):
                    ?>
                        <li><a href="<?= htmlspecialchars($unit['url'] ?? '#') ?>"><?= htmlspecialchars($unit['title']) ?></a></li>
                    <?php 
                        endforeach;
                    else: 
                    ?>
                        <li><a href="#">Computer Science & Engineering</a></li>
                        <li><a href="#">Electrical & Electronic Engineering</a></li>
                        <li><a href="#">School of Business Administration</a></li>
                        <li><a href="#">Department of Law & Legal Studies</a></li>
                        <li><a href="#">Department of Humanities & English</a></li>
                    <?php endif; ?>
                </ul>
            </div>
            <div class="footer-column">
                <h3>Quick Portals</h3>
                <ul class="footer-links">
                    <?php 
                    $quick_portals = json_decode($settings['footer_quick_portals'] ?? '[]', true);
                    if (is_array($quick_portals) && !empty($quick_portals)): 
                        foreach ($quick_portals as $portal):
                    ?>
                        <li><a href="<?= htmlspecialchars($portal['url'] ?? '#') ?>"><?= htmlspecialchars($portal['title']) ?></a></li>
                    <?php 
                        endforeach;
                    else: 
                    ?>
                        <li><a href="#">Student Online Registration</a></li>
                        <li><a href="#">UGC-IQAC Directives</a></li>
                        <li><a href="#">Faculty Directory</a></li>
                        <li><a href="#">Journal Publication Portal</a></li>
                        <li><a href="#">Library Catalogs</a></li>
                    <?php endif; ?>
                </ul>
            </div>
            <div class="footer-column">
                <h3>Contact Address</h3>
                <p><i class="fa-solid fa-location-dot" style="color: var(--accent-gold); margin-right: 8px;"></i> <?= htmlspecialchars($settings['contact_address'] ?? 'Permanent Campus: Plot-44, Sector-15, Uttara, Dhaka-1230, Bangladesh') ?></p>
                <p><i class="fa-solid fa-phone" style="color: var(--accent-gold); margin-right: 8px;"></i> Phone: <?= htmlspecialchars($settings['contact_phone'] ?? '+880-2-55554444') ?></p>
                <p><i class="fa-solid fa-envelope" style="color: var(--accent-gold); margin-right: 8px;"></i> Email: <?= htmlspecialchars($settings['contact_email'] ?? 'info@dmu.edu.bd') ?></p>
            </div>
        </div>
        <div class="footer-bottom">
            <p><?= htmlspecialchars($settings['footer_copyright_text'] ?? '© 2026 Dhaka Metropolitan University. All Rights Reserved.') ?></p>
            <p><?= htmlspecialchars($settings['footer_designed_text'] ?? 'Designed and monitored by the ICT Cell, Dhaka Metropolitan University.') ?></p>
        </div>
    </footer>

    <!-- Responsive Navigation JavaScript -->
    <script>
        function toggleNavigationMenu() {
            var menuList = document.getElementById("nav-list");
            if (menuList.classList.contains("active")) {
                menuList.classList.remove("active");
            } else {
                menuList.classList.add("active");
            }
        }
    </script>
</body>
</html>
