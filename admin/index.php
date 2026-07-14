<?php
// FILE: /ss/admin/index.php

require_once '../config/db.php';

// Simple security check. If not logged in, redirect to login page.
if (!isset($_SESSION["admin_loggedin"]) || $_SESSION["admin_loggedin"] !== true) {
    header("location: login.php");
    exit;
}

// Optional placeholder stats (You can replace these with actual database queries later)
// Example:
// $stmt = $pdo->query("SELECT COUNT(*) FROM admissions WHERE status = 'pending'");
// $pending_admissions = $stmt->fetchColumn();
$pending_admissions = 12; 
$unread_messages = 5;
$total_posts = 28;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com/3.4.16"></script>
    <!-- Remix Icon -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.css" rel="stylesheet">
    <!-- Inter Font for a modern aesthetic -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen flex">

    <!-- Sidebar -->
    <aside class="w-64 bg-slate-900 text-slate-300 flex flex-col shrink-0 border-r border-slate-800">
        <!-- Logo / Brand Section -->
        <div class="p-6 flex items-center gap-3 border-b border-slate-800">
            <div class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center text-white">
                <i class="ri-dashboard-3-line text-lg"></i>
            </div>
            <div>
                <h1 class="text-base font-bold text-white tracking-wide">Admin Panel</h1>
                <p class="text-xs text-slate-500">Site Management</p>
            </div>
        </div>

        <!-- Navigation Menu -->
        <nav class="flex-grow p-4 space-y-6 overflow-y-auto text-sm">
            <!-- Group 1 -->
            <div>
                <a href="index.php" class="flex items-center gap-3 py-2.5 px-4 rounded-xl bg-indigo-600 text-white font-medium shadow-lg shadow-indigo-600/10 transition">
                    <i class="ri-dashboard-line text-lg"></i> Dashboard
                </a>
            </div>

            <!-- Group 2: Content -->
            <div>
                <h2 class="px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Content</h2>
                <div class="space-y-1">
                    <a href="manage_pages.php" class="flex items-center gap-3 py-2 px-4 rounded-xl hover:bg-slate-800 hover:text-white transition">
                        <i class="ri-pages-line text-lg text-slate-400"></i> Pages
                    </a>
                    <a href="manage_posts.php" class="flex items-center gap-3 py-2 px-4 rounded-xl hover:bg-slate-800 hover:text-white transition">
                        <i class="ri-article-line text-lg text-slate-400"></i> News / Blog
                    </a>
                    <a href="manage_events.php" class="flex items-center gap-3 py-2 px-4 rounded-xl hover:bg-slate-800 hover:text-white transition">
                        <i class="ri-calendar-event-line text-lg text-slate-400"></i> Events
                    </a>
                    <a href="manage_notices.php" class="flex items-center gap-3 py-2 px-4 rounded-xl hover:bg-slate-800 hover:text-white transition">
                        <i class="ri-notification-badge-line text-lg text-slate-400"></i> Notice Board
                    </a>
                    <a href="manage_teachers.php" class="flex items-center gap-3 py-2 px-4 rounded-xl hover:bg-slate-800 hover:text-white transition">
                        <i class="ri-user-star-line text-lg text-slate-400"></i> Teachers
                    </a>
                    <a href="manage_downloads.php" class="flex items-center gap-3 py-2 px-4 rounded-xl hover:bg-slate-800 hover:text-white transition">
                        <i class="ri-download-cloud-line text-lg text-slate-400"></i> Library / Downloads
                    </a>
                    <a href="manage_videos.php" class="flex items-center gap-3 py-2 px-4 rounded-xl hover:bg-slate-800 hover:text-white transition">
                        <i class="ri-video-line text-lg text-slate-400"></i> Video Gallery
                    </a>
                </div>
            </div>

            <!-- Group 3: Submissions -->
            <div>
                <h2 class="px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Submissions</h2>
                <div class="space-y-1">
                    <a href="view_admissions.php" class="flex items-center justify-between py-2 px-4 rounded-xl hover:bg-slate-800 hover:text-white transition">
                        <span class="flex items-center gap-3">
                            <i class="ri-user-add-line text-lg text-slate-400"></i> Admission Apps
                        </span>
                        <?php if ($pending_admissions > 0): ?>
                            <span class="bg-indigo-500/20 text-indigo-400 text-xs font-semibold px-2 py-0.5 rounded-full"><?= $pending_admissions; ?></span>
                        <?php endif; ?>
                    </a>
                    <a href="view_contact.php" class="flex items-center justify-between py-2 px-4 rounded-xl hover:bg-slate-800 hover:text-white transition">
                        <span class="flex items-center gap-3">
                            <i class="ri-mail-line text-lg text-slate-400"></i> Contact Messages
                        </span>
                        <?php if ($unread_messages > 0): ?>
                            <span class="bg-emerald-500/20 text-emerald-400 text-xs font-semibold px-2 py-0.5 rounded-full"><?= $unread_messages; ?></span>
                        <?php endif; ?>
                    </a>
                </div>
            </div>
        </nav>

        <!-- Sidebar Footer -->
        <div class="p-4 border-t border-slate-800 space-y-1 text-sm">
            <a href="manage_settings.php" class="flex items-center gap-3 py-2 px-4 rounded-xl hover:bg-slate-800 hover:text-white transition">
                <i class="ri-settings-3-line text-lg text-slate-400"></i> Settings
            </a>
            <a href="logout.php" class="flex items-center gap-3 py-2 px-4 rounded-xl text-rose-400 hover:bg-rose-500/10 hover:text-rose-300 transition">
                <i class="ri-logout-box-r-line text-lg"></i> Logout
            </a>
        </div>
    </aside>

    <!-- Main Content Area -->
    <main class="flex-1 flex flex-col min-h-screen overflow-x-hidden">
        
        <!-- Top Navbar -->
        <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-8 shrink-0">
            <div>
                <h1 class="text-xl font-bold text-slate-950">Dashboard</h1>
            </div>
            <!-- Profile Info -->
            <div class="flex items-center gap-3">
                <div class="text-right hidden sm:block">
                    <p class="text-sm font-semibold text-slate-900"><?= htmlspecialchars($_SESSION["admin_username"]); ?></p>
                    <p class="text-xs text-slate-500">Administrator</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 border border-slate-200 font-bold uppercase">
                    <?= substr(htmlspecialchars($_SESSION["admin_username"]), 0, 2); ?>
                </div>
            </div>
        </header>

        <!-- Dashboard Content Grid -->
        <div class="p-8 space-y-8 flex-grow">
            
            <!-- Welcome Alert Card -->
            <div class="bg-gradient-to-r from-indigo-550 to-indigo-650 bg-indigo-600 text-white rounded-2xl p-6 shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                <div>
                    <h2 class="text-xl font-bold mb-1">Welcome back, <?= htmlspecialchars($_SESSION["admin_username"]); ?>!</h2>
                    <p class="text-indigo-100 text-sm">Use the left sidebar navigation to update web content, review forms, and adjust system settings.</p>
                </div>
                <div class="flex gap-2">
                    <a href="manage_posts.php" class="bg-white/10 hover:bg-white/20 text-white text-xs font-semibold py-2 px-4 rounded-lg transition">
                        New Post
                    </a>
                    <a href="manage_settings.php" class="bg-white text-indigo-600 hover:bg-indigo-50 text-xs font-bold py-2 px-4 rounded-lg transition shadow-sm">
                        Site Settings
                    </a>
                </div>
            </div>

            <!-- Stats Overview Row -->
            <section class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Card 1 -->
                <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-500">Pending Applications</p>
                        <h3 class="text-3xl font-bold text-slate-900 mt-2"><?= $pending_admissions; ?></h3>
                        <p class="text-xs text-indigo-600 font-medium mt-1">Admission requests pending review</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600">
                        <i class="ri-user-add-line text-2xl"></i>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-500">Unread Messages</p>
                        <h3 class="text-3xl font-bold text-slate-900 mt-2"><?= $unread_messages; ?></h3>
                        <p class="text-xs text-emerald-600 font-medium mt-1">New submissions via Contact form</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600">
                        <i class="ri-mail-line text-2xl"></i>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-500">Published News / Blog</p>
                        <h3 class="text-3xl font-bold text-slate-900 mt-2"><?= $total_posts; ?></h3>
                        <p class="text-xs text-sky-600 font-medium mt-1">Live blog posts and events</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-sky-50 flex items-center justify-center text-sky-600">
                        <i class="ri-article-line text-2xl"></i>
                    </div>
                </div>
            </section>

            <!-- Bottom Split Row -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Section: Today's Overview (2/3 Column) -->
                <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-100 shadow-sm p-6 space-y-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-bold text-slate-900">Content Overview</h3>
                            <p class="text-xs text-slate-500">Quickly jump to content management modules</p>
                        </div>
                    </div>
                    
                    <!-- Quick Actions Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="p-4 rounded-xl border border-slate-100 bg-slate-50/50 hover:bg-slate-50 transition flex items-start gap-4">
                            <div class="w-10 h-10 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center shrink-0">
                                <i class="ri-pages-line text-lg"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-900">Manage Website Pages</h4>
                                <p class="text-xs text-slate-500 mt-1">Edit custom text, navigation structures and homepage details.</p>
                                <a href="manage_pages.php" class="text-xs font-semibold text-indigo-600 inline-flex items-center gap-1 mt-2 hover:underline">
                                    Open Pages <i class="ri-arrow-right-line"></i>
                                </a>
                            </div>
                        </div>

                        <div class="p-4 rounded-xl border border-slate-100 bg-slate-50/50 hover:bg-slate-50 transition flex items-start gap-4">
                            <div class="w-10 h-10 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center shrink-0">
                                <i class="ri-notification-badge-line text-lg"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-900">Post a Notice</h4>
                                <p class="text-xs text-slate-500 mt-1">Broadcast important announcements directly to the dashboard noticeboard.</p>
                                <a href="manage_notices.php" class="text-xs font-semibold text-amber-600 inline-flex items-center gap-1 mt-2 hover:underline">
                                    Open Noticeboard <i class="ri-arrow-right-line"></i>
                                </a>
                            </div>
                        </div>

                        <div class="p-4 rounded-xl border border-slate-100 bg-slate-50/50 hover:bg-slate-50 transition flex items-start gap-4">
                            <div class="w-10 h-10 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                                <i class="ri-user-star-line text-lg"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-900">Teacher Directory</h4>
                                <p class="text-xs text-slate-500 mt-1">Add profiles, profile photos, and assign classes for school teachers.</p>
                                <a href="manage_teachers.php" class="text-xs font-semibold text-emerald-600 inline-flex items-center gap-1 mt-2 hover:underline">
                                    View Teachers <i class="ri-arrow-right-line"></i>
                                </a>
                            </div>
                        </div>

                        <div class="p-4 rounded-xl border border-slate-100 bg-slate-50/50 hover:bg-slate-50 transition flex items-start gap-4">
                            <div class="w-10 h-10 rounded-lg bg-sky-100 text-sky-600 flex items-center justify-center shrink-0">
                                <i class="ri-download-cloud-line text-lg"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-900">Upload Resources</h4>
                                <p class="text-xs text-slate-500 mt-1">Manage public resources, syllabus files, and library PDFs.</p>
                                <a href="manage_downloads.php" class="text-xs font-semibold text-sky-600 inline-flex items-center gap-1 mt-2 hover:underline">
                                    Manage Files <i class="ri-arrow-right-line"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Section: System Actions (1/3 Column) -->
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 flex flex-col justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 mb-1">Database & System</h3>
                        <p class="text-xs text-slate-500 mb-4">Core engine configuration panel</p>
                        
                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 text-slate-700">
                                <div class="flex items-center gap-3">
                                    <i class="ri-database-2-line text-lg text-slate-500"></i>
                                    <span class="text-sm font-medium">Database Status</span>
                                </div>
                                <span class="bg-emerald-100 text-emerald-700 text-xs font-semibold px-2 py-0.5 rounded-full">Connected</span>
                            </div>
                            
                            <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 text-slate-700">
                                <div class="flex items-center gap-3">
                                    <i class="ri-shield-check-line text-lg text-slate-500"></i>
                                    <span class="text-sm font-medium">HTTPS Protocol</span>
                                </div>
                                <span class="bg-emerald-100 text-emerald-700 text-xs font-semibold px-2 py-0.5 rounded-full">Secure</span>
                            </div>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-slate-100 mt-6">
                        <a href="manage_settings.php" class="w-full bg-slate-900 hover:bg-slate-800 text-white text-sm font-semibold py-2.5 px-4 rounded-xl flex items-center justify-center gap-2 transition">
                            <i class="ri-equalizer-line"></i> Global Configurations
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </main>
</body>
</html>