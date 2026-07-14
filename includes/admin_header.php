<?php
// FILE: /ss/includes/admin_header.php

require_once '../config/db.php';

if (!isset($_SESSION["admin_loggedin"]) || $_SESSION["admin_loggedin"] !== true) {
    header("location: login.php");
    exit;
}
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title ?? 'Admin Panel') ?></title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com/3.4.16"></script>
    <!-- Remix Icon -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.css" rel="stylesheet">
    <!-- Inter Font (Clean SaaS Typography) -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen flex">

    <!-- Modern Sidebar -->
    <aside class="w-64 bg-slate-900 text-slate-300 flex flex-col shrink-0 border-r border-slate-800">
        
        <!-- Logo / Brand Header -->
        <div class="p-6 flex items-center gap-3 border-b border-slate-800 shrink-0">
            <div class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center text-white">
                <i class="ri-dashboard-3-line text-lg"></i>
            </div>
            <div>
                <h1 class="text-sm font-bold text-white tracking-wide">Control Center</h1>
                <p class="text-[10px] text-slate-500 uppercase font-semibold">School CMS</p>
            </div>
        </div>

        <!-- Navigation Menu -->
        <nav class="flex-grow p-4 space-y-6 overflow-y-auto text-sm">
            <!-- Core Link -->
            <div>
                <a href="index.php" class="flex items-center gap-3 py-2.5 px-4 rounded-xl transition font-medium <?= $current_page == 'index.php' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/10' : 'hover:bg-slate-800 hover:text-white' ?>">
                    <i class="ri-dashboard-line text-lg"></i> Dashboard
                </a>
            </div>

            <!-- Content Management Group -->
            <div>
                <h2 class="px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Content</h2>
                <div class="space-y-1">
                    <a href="manage_pages.php" class="flex items-center gap-3 py-2 px-4 rounded-xl transition <?= $current_page == 'manage_pages.php' ? 'bg-indigo-600 text-white font-medium' : 'hover:bg-slate-800 hover:text-white' ?>">
                        <i class="ri-pages-line text-lg text-slate-400"></i> Pages
                    </a>
                    <a href="manage_posts.php" class="flex items-center gap-3 py-2 px-4 rounded-xl transition <?= $current_page == 'manage_posts.php' ? 'bg-indigo-600 text-white font-medium' : 'hover:bg-slate-800 hover:text-white' ?>">
                        <i class="ri-article-line text-lg text-slate-400"></i> News / Blog
                    </a>
                    <a href="manage_events.php" class="flex items-center gap-3 py-2 px-4 rounded-xl transition <?= $current_page == 'manage_events.php' ? 'bg-indigo-600 text-white font-medium' : 'hover:bg-slate-800 hover:text-white' ?>">
                        <i class="ri-calendar-event-line text-lg text-slate-400"></i> Events
                    </a>
                    <a href="manage_notices.php" class="flex items-center gap-3 py-2 px-4 rounded-xl transition <?= $current_page == 'manage_notices.php' ? 'bg-indigo-600 text-white font-medium' : 'hover:bg-slate-800 hover:text-white' ?>">
                        <i class="ri-notification-badge-line text-lg text-slate-400"></i> Notice Board
                    </a>
                    <a href="manage_announcements.php" class="flex items-center gap-3 py-2 px-4 rounded-xl transition <?= $current_page == 'manage_announcements.php' ? 'bg-indigo-600 text-white font-medium' : 'hover:bg-slate-800 hover:text-white' ?>">
                        <i class="ri-speaker-3-line text-lg text-slate-400"></i> Announcements
                    </a>
                    <a href="manage_teachers.php" class="flex items-center gap-3 py-2 px-4 rounded-xl transition <?= $current_page == 'manage_teachers.php' ? 'bg-indigo-600 text-white font-medium' : 'hover:bg-slate-800 hover:text-white' ?>">
                        <i class="ri-user-star-line text-lg text-slate-400"></i> Teachers
                    </a>
                    <a href="manage_downloads.php" class="flex items-center gap-3 py-2 px-4 rounded-xl transition <?= $current_page == 'manage_downloads.php' ? 'bg-indigo-600 text-white font-medium' : 'hover:bg-slate-800 hover:text-white' ?>">
                        <i class="ri-download-cloud-line text-lg text-slate-400"></i> Library / Downloads
                    </a>
                    <a href="manage_videos.php" class="flex items-center gap-3 py-2 px-4 rounded-xl transition <?= $current_page == 'manage_videos.php' ? 'bg-indigo-600 text-white font-medium' : 'hover:bg-slate-800 hover:text-white' ?>">
                        <i class="ri-video-line text-lg text-slate-400"></i> Video Gallery
                    </a>
                </div>
            </div>

            <!-- Inbound Submissions Group -->
            <div>
                <h2 class="px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Submissions</h2>
                <div class="space-y-1">
                    <a href="view_admissions.php" class="flex items-center gap-3 py-2 px-4 rounded-xl transition <?= $current_page == 'view_admissions.php' ? 'bg-indigo-600 text-white font-medium' : 'hover:bg-slate-800 hover:text-white' ?>">
                        <i class="ri-user-add-line text-lg text-slate-400"></i> Admissions
                    </a>
                    <a href="view_contact.php" class="flex items-center gap-3 py-2 px-4 rounded-xl transition <?= $current_page == 'view_contact.php' ? 'bg-indigo-600 text-white font-medium' : 'hover:bg-slate-800 hover:text-white' ?>">
                        <i class="ri-mail-line text-lg text-slate-400"></i> Contact Forms
                    </a>
                </div>
            </div>

            <!-- System Configuration Group -->
            <div>
                <h2 class="px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">System</h2>
                <div class="space-y-1">
                    <a href="manage_settings.php" class="flex items-center gap-3 py-2 px-4 rounded-xl transition <?= $current_page == 'manage_settings.php' ? 'bg-indigo-600 text-white font-medium' : 'hover:bg-slate-800 hover:text-white' ?>">
                        <i class="ri-settings-3-line text-lg text-slate-400"></i> General Settings
                    </a>
                    <a href="manage_users.php" class="flex items-center gap-3 py-2 px-4 rounded-xl transition <?= $current_page == 'manage_users.php' ? 'bg-indigo-600 text-white font-medium' : 'hover:bg-slate-800 hover:text-white' ?>">
                        <i class="ri-group-line text-lg text-slate-400"></i> Manage Users
                    </a>
                </div>
            </div>
        </nav>

        <!-- Sidebar Footer Action (Log Out) -->
        <div class="p-4 border-t border-slate-800 shrink-0">
            <a href="logout.php" class="flex items-center gap-3 py-2.5 px-4 rounded-xl text-rose-400 hover:bg-rose-500/10 hover:text-rose-300 transition text-sm">
                <i class="ri-logout-box-r-line text-lg"></i> Sign Out
            </a>
        </div>
    </aside>

    <!-- Main Section Workspace Wrapper -->
    <div class="flex-1 flex flex-col min-h-screen overflow-x-hidden">
        
        <!-- Standard Header Area -->
        <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-8 md:px-10 shrink-0">
            <div class="flex items-center gap-2 text-slate-400 text-xs font-medium">
                <span class="hover:text-slate-600">Admin</span>
                <span class="text-[10px] text-slate-300"><i class="ri-arrow-right-s-line"></i></span>
                <span class="text-slate-800 font-semibold"><?= htmlspecialchars($page_title ?? 'Dashboard') ?></span>
            </div>
            
            <!-- Quick Profile Overview -->
            <div class="flex items-center gap-3">
                <div class="text-right hidden sm:block">
                    <p class="text-xs font-bold text-slate-900"><?= htmlspecialchars($_SESSION["admin_username"] ?? 'Admin'); ?></p>
                    <p class="text-[10px] font-medium text-slate-400 uppercase tracking-wide">System Admin</p>
                </div>
                <div class="w-9 h-9 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 border border-slate-200 text-xs font-bold uppercase">
                    <?= substr(htmlspecialchars($_SESSION["admin_username"] ?? 'Ad'), 0, 2); ?>
                </div>
            </div>
        </header>

        <!-- Dynamic Body Page Content Frame -->
        <div class="p-8 md:p-10 space-y-8 flex-grow">
            <!-- Dynamic Content Page header element -->
            <div class="shrink-0 flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-5">
                <div>
                    <h2 class="text-2xl font-bold tracking-tight text-slate-950"><?= htmlspecialchars($page_title ?? 'Dashboard') ?></h2>
                    <p class="text-xs text-slate-500 mt-1">Configure and manage directory parameters dynamically</p>
                </div>
            </div>