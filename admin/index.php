<?php
$page_title = 'Dashboard';
include '../includes/admin_header.php';
require_once '../includes/functions.php';

// Fetch dynamic stats from database
$admissions_cnt = $conn->query("SELECT COUNT(*) as cnt FROM admissions")->fetch_assoc()['cnt'] ?? 0;
$contact_cnt = $conn->query("SELECT COUNT(*) as cnt FROM contact_submissions")->fetch_assoc()['cnt'] ?? 0;
$posts_cnt = $conn->query("SELECT COUNT(*) as cnt FROM posts")->fetch_assoc()['cnt'] ?? 0;
$teachers_cnt = $conn->query("SELECT COUNT(*) as cnt FROM teachers")->fetch_assoc()['cnt'] ?? 0;
$gallery_cnt = $conn->query("SELECT COUNT(*) as cnt FROM gallery")->fetch_assoc()['cnt'] ?? 0;
$committee_cnt = $conn->query("SELECT COUNT(*) as cnt FROM managing_committee")->fetch_assoc()['cnt'] ?? 0;

$settings = get_all_settings($conn);
$active_theme = $settings['active_homepage'] ?? 'home1.php';
?>

<div class="space-y-8">
    
    <!-- Welcome Banner Card -->
    <div class="bg-gradient-to-r from-indigo-600 via-indigo-700 to-slate-900 text-white rounded-2xl p-6 lg:p-8 shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 bg-white/10 rounded-full text-xs font-semibold mb-3 border border-white/15">
                <span class="w-2 h-2 rounded-full bg-emerald-400"></span> Active Homepage: <strong class="text-amber-300"><?= htmlspecialchars($active_theme) ?></strong>
            </div>
            <h2 class="text-2xl font-bold mb-1">Welcome back, <?= htmlspecialchars($_SESSION["admin_username"] ?? 'Administrator'); ?>!</h2>
            <p class="text-indigo-100 text-xs md:text-sm">Manage website content, change themes, update teachers & committees, and adjust site settings.</p>
        </div>
        <div class="flex flex-wrap gap-2.5 shrink-0">
            <a href="manage_themes.php" class="bg-amber-500 hover:bg-amber-600 text-slate-950 text-xs font-bold py-2.5 px-4 rounded-xl transition shadow-sm flex items-center gap-1.5">
                <i class="ri-palette-line text-sm"></i> Theme Management
            </a>
            <a href="manage_posts.php" class="bg-white/15 hover:bg-white/25 text-white text-xs font-semibold py-2.5 px-4 rounded-xl transition border border-white/20">
                <i class="ri-article-line mr-1"></i> New Post
            </a>
            <a href="manage_settings.php" class="bg-white text-indigo-700 hover:bg-slate-50 text-xs font-bold py-2.5 px-4 rounded-xl transition shadow-sm">
                <i class="ri-settings-3-line mr-1"></i> Settings
            </a>
        </div>
    </div>

    <!-- Stats Overview Row -->
    <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Card 1: Admissions -->
        <a href="view_admissions.php" class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition flex items-center justify-between group">
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Admissions</p>
                <h3 class="text-3xl font-extrabold text-slate-900 mt-2"><?= $admissions_cnt; ?></h3>
                <p class="text-xs text-indigo-600 font-medium mt-1 group-hover:underline">Applications received</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600 text-2xl group-hover:scale-110 transition">
                <i class="ri-user-add-line"></i>
            </div>
        </a>

        <!-- Card 2: Contact Messages -->
        <a href="view_contact.php" class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition flex items-center justify-between group">
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Contact Inquiries</p>
                <h3 class="text-3xl font-extrabold text-slate-900 mt-2"><?= $contact_cnt; ?></h3>
                <p class="text-xs text-emerald-600 font-medium mt-1 group-hover:underline">Messages submitted</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 text-2xl group-hover:scale-110 transition">
                <i class="ri-mail-line"></i>
            </div>
        </a>

        <!-- Card 3: Teachers -->
        <a href="manage_teachers.php" class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition flex items-center justify-between group">
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Teachers</p>
                <h3 class="text-3xl font-extrabold text-slate-900 mt-2"><?= $teachers_cnt; ?></h3>
                <p class="text-xs text-sky-600 font-medium mt-1 group-hover:underline">Faculty profiles</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-sky-50 flex items-center justify-center text-sky-600 text-2xl group-hover:scale-110 transition">
                <i class="ri-user-star-line"></i>
            </div>
        </a>

        <!-- Card 4: News & Blog -->
        <a href="manage_posts.php" class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition flex items-center justify-between group">
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">News / Blog</p>
                <h3 class="text-3xl font-extrabold text-slate-900 mt-2"><?= $posts_cnt; ?></h3>
                <p class="text-xs text-amber-600 font-medium mt-1 group-hover:underline">Published articles</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600 text-2xl group-hover:scale-110 transition">
                <i class="ri-article-line"></i>
            </div>
        </a>
    </section>

    <!-- Content Modules Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left 2 Columns: Quick Navigation Panels -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-100 shadow-sm p-6 space-y-6">
            <div>
                <h3 class="text-lg font-bold text-slate-900">Key Management Modules</h3>
                <p class="text-xs text-slate-500 mt-0.5">Quickly navigate to frequently accessed administrative tools</p>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Module: Theme Management -->
                <a href="manage_themes.php" class="p-4 rounded-xl border border-indigo-100 bg-indigo-50/40 hover:bg-indigo-50 transition flex items-start gap-4 group">
                    <div class="w-10 h-10 rounded-lg bg-indigo-600 text-white flex items-center justify-center shrink-0 shadow-sm">
                        <i class="ri-palette-line text-lg"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-slate-900 group-hover:text-indigo-600 transition">Theme Management</h4>
                        <p class="text-xs text-slate-500 mt-1">Switch between Home 1, Home 2 (University), and Home 3 (Primary Bengali).</p>
                        <span class="text-xs font-semibold text-indigo-600 inline-flex items-center gap-1 mt-2">
                            Select Themes <i class="ri-arrow-right-line"></i>
                        </span>
                    </div>
                </a>

                <!-- Module: Managing Committee -->
                <a href="manage_committee.php" class="p-4 rounded-xl border border-slate-100 bg-slate-50/50 hover:bg-slate-50 transition flex items-start gap-4 group">
                    <div class="w-10 h-10 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                        <i class="ri-team-line text-lg"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-slate-900 group-hover:text-emerald-600 transition">Managing Committee (ম্যানেজিং কমিটি)</h4>
                        <p class="text-xs text-slate-500 mt-1">Manage president, secretary, member rosters, and photos.</p>
                        <span class="text-xs font-semibold text-emerald-600 inline-flex items-center gap-1 mt-2">
                            Manage Committee <i class="ri-arrow-right-line"></i>
                        </span>
                    </div>
                </a>

                <!-- Module: Photo Gallery -->
                <a href="manage_gallery.php" class="p-4 rounded-xl border border-slate-100 bg-slate-50/50 hover:bg-slate-50 transition flex items-start gap-4 group">
                    <div class="w-10 h-10 rounded-lg bg-sky-100 text-sky-600 flex items-center justify-center shrink-0">
                        <i class="ri-image-line text-lg"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-slate-900 group-hover:text-sky-600 transition">Photo Gallery (ফটো গ্যালারি)</h4>
                        <p class="text-xs text-slate-500 mt-1">Upload school photo album images with captions for popup view.</p>
                        <span class="text-xs font-semibold text-sky-600 inline-flex items-center gap-1 mt-2">
                            Upload Photos <i class="ri-arrow-right-line"></i>
                        </span>
                    </div>
                </a>

                <!-- Module: Research & Publications -->
                <a href="manage_research.php" class="p-4 rounded-xl border border-slate-100 bg-slate-50/50 hover:bg-slate-50 transition flex items-start gap-4 group">
                    <div class="w-10 h-10 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center shrink-0">
                        <i class="ri-book-open-line text-lg"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-slate-900 group-hover:text-amber-600 transition">Research & Publications</h4>
                        <p class="text-xs text-slate-500 mt-1">Publish university research journals, volume numbers, and papers.</p>
                        <span class="text-xs font-semibold text-amber-600 inline-flex items-center gap-1 mt-2">
                            Manage Research <i class="ri-arrow-right-line"></i>
                        </span>
                    </div>
                </a>
            </div>
        </div>

        <!-- Right 1 Column: System Status & Quick Links -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 flex flex-col justify-between space-y-6">
            <div>
                <h3 class="text-lg font-bold text-slate-900">Active Theme Status</h3>
                <p class="text-xs text-slate-500 mt-0.5">Current layout running on index.php</p>

                <div class="mt-4 p-4 rounded-xl bg-slate-50 border border-slate-200/70 space-y-3">
                    <div class="flex items-center justify-between text-xs">
                        <span class="font-semibold text-slate-600">Selected Template:</span>
                        <span class="font-bold text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded border border-indigo-100"><?= htmlspecialchars($active_theme) ?></span>
                    </div>
                    <div class="flex items-center justify-between text-xs">
                        <span class="font-semibold text-slate-600">Database Engine:</span>
                        <span class="font-semibold text-emerald-600 flex items-center gap-1"><i class="ri-checkbox-circle-fill"></i> Connected</span>
                    </div>
                    <div class="flex items-center justify-between text-xs">
                        <span class="font-semibold text-slate-600">Committee Members:</span>
                        <span class="font-bold text-slate-800"><?= $committee_cnt ?></span>
                    </div>
                    <div class="flex items-center justify-between text-xs">
                        <span class="font-semibold text-slate-600">Gallery Photos:</span>
                        <span class="font-bold text-slate-800"><?= $gallery_cnt ?></span>
                    </div>
                </div>
            </div>

            <div class="space-y-2 pt-4 border-t border-slate-100">
                <a href="manage_themes.php" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold py-2.5 px-4 rounded-xl flex items-center justify-center gap-2 transition shadow-sm shadow-indigo-600/15">
                    <i class="ri-palette-line"></i> Change Homepage Theme
                </a>
                <a href="../index.php" target="_blank" class="w-full bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold py-2.5 px-4 rounded-xl flex items-center justify-center gap-2 transition">
                    <i class="ri-external-link-line"></i> Visit Live Website
                </a>
            </div>
        </div>

    </div>

</div>

<?php include '../includes/admin_footer.php'; ?>