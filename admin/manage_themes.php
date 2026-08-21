<?php
$page_title = 'Theme Management';
include '../includes/admin_header.php';
require_once '../includes/functions.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['active_homepage'])) {
    $selected_theme = trim($_POST['active_homepage']);
    $allowed_themes = ['home1.php', 'home2.php', 'home3.php'];
    
    if (in_array($selected_theme, $allowed_themes)) {
        $stmt = $conn->prepare("INSERT INTO settings (setting_key, setting_value) VALUES ('active_homepage', ?) ON DUPLICATE KEY UPDATE setting_value = ?");
        $stmt->bind_param("ss", $selected_theme, $selected_theme);
        if ($stmt->execute()) {
            $message = '
            <div class="p-4 bg-emerald-50 border border-emerald-100 rounded-xl flex items-start gap-3 text-emerald-800 text-sm mb-6 shadow-sm">
                <i class="ri-checkbox-circle-line text-lg text-emerald-600 shrink-0"></i>
                <div>
                    <p class="font-semibold">Homepage Theme Activated</p>
                    <p class="text-emerald-600/90 text-xs mt-0.5">The selected template is now active on your website homepage.</p>
                </div>
            </div>';
        } else {
            $message = '
            <div class="p-4 bg-rose-50 border border-rose-100 rounded-xl flex items-start gap-3 text-rose-800 text-sm mb-6 shadow-sm">
                <i class="ri-error-warning-line text-lg text-rose-600 shrink-0"></i>
                <div>
                    <p class="font-semibold">Activation Failed</p>
                    <p class="text-rose-600/90 text-xs mt-0.5">Could not update theme setting. Please try again.</p>
                </div>
            </div>';
        }
        $stmt->close();
    }
}

$settings = get_all_settings($conn);
$active_theme = $settings['active_homepage'] ?? 'home1.php';

$themes = [
    [
        'id' => 'home1.php',
        'title' => 'Theme 1: Standard Modern School',
        'subtitle' => 'Default Clean & Modern K-12 Template',
        'badge' => 'Classic Blue',
        'badge_color' => 'bg-blue-100 text-blue-800',
        'border_color' => 'border-blue-500',
        'icon' => 'ri-school-line',
        'desc' => 'A clean and professional layout designed for general schools, featuring high-visibility news, event schedules, portal access, and downloadable library resources.',
        'features' => [
            'Hero Banner with quick admission call-to-action',
            'Portal Quick Links (Student, Teacher, Parent, Library)',
            'Latest News & Upcoming Events calendar',
            'Featured Teacher Profiles & PDF Downloads'
        ],
        'preview_url' => '../home1.php'
    ],
    [
        'id' => 'home2.php',
        'title' => 'Theme 2: Prestigious College / University',
        'subtitle' => 'Dhaka Metropolitan University Style Design',
        'badge' => 'Navy & Gold',
        'badge_color' => 'bg-amber-100 text-amber-800',
        'border_color' => 'border-amber-500',
        'icon' => 'ri-government-line',
        'desc' => 'An elite and authoritative university layout with dedicated research publication feeds, multi-card campus infrastructure showcases, and comprehensive departmental lists.',
        'features' => [
            'Prestigious Top Bar & Gold Brand Styling',
            'Integrated Research & Publications Archive feed',
            'Campus Infrastructure 3-Card Showcase',
            'Academic Units & Quick Portals Footer'
        ],
        'preview_url' => '../home2.php'
    ],
    [
        'id' => 'home3.php',
        'title' => 'Theme 3: Primary Bengali School (প্রাথমিক বিদ্যালয়)',
        'subtitle' => 'সরকারি প্রাথমিক বিদ্যালয় থিম',
        'badge' => 'Green & Orange',
        'badge_color' => 'bg-emerald-100 text-emerald-800',
        'border_color' => 'border-emerald-500',
        'icon' => 'ri-book-read-line',
        'desc' => 'A vibrant localized Bengali primary school template complete with official EIIN badge, rolling notice ticker, headmaster/chairman messages, star student recognition, and managing committee rosters.',
        'features' => [
            'EIIN Badge & Real-time Marquee Notice Ticker',
            'Head Teacher & Managing Committee Chairman speeches',
            'Best Student of the Month Corner (সেরা শিক্ষার্থী কর্নার)',
            'Managing Committee roster (ম্যানেজিং কমিটি) & Photo Gallery Popup'
        ],
        'preview_url' => '../home3.php'
    ]
];
?>

<div class="max-w-6xl mx-auto space-y-8 py-4">

    <?= $message ?>

    <!-- Header Description Card -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 lg:p-8 relative">
        <div class="absolute top-0 left-0 right-0 h-1 bg-indigo-600 rounded-t-2xl"></div>
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h3 class="text-xl font-bold text-slate-900">Homepage Theme Selector</h3>
                <p class="text-xs text-slate-500 mt-1">Select and switch between your available homepage themes in one click</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-xs font-semibold text-slate-500">Active Homepage:</span>
                <span class="px-3 py-1 bg-indigo-50 text-indigo-700 font-bold rounded-lg text-xs border border-indigo-100">
                    <?= htmlspecialchars($active_theme) ?>
                </span>
            </div>
        </div>
    </div>

    <!-- Theme Cards Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <?php foreach ($themes as $theme): ?>
            <?php $is_active = ($active_theme === $theme['id']); ?>
            <div class="bg-white rounded-2xl border <?= $is_active ? 'border-2 border-indigo-600 ring-4 ring-indigo-50 shadow-md' : 'border-slate-200/80 shadow-sm hover:shadow-md' ?> transition flex flex-col justify-between overflow-hidden relative">
                
                <?php if ($is_active): ?>
                    <div class="absolute top-4 right-4 z-10">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-indigo-600 text-white rounded-full text-xs font-bold shadow-sm">
                            <i class="ri-checkbox-circle-fill"></i> Active Theme
                        </span>
                    </div>
                <?php endif; ?>

                <div class="p-6 space-y-4">
                    <!-- Theme Badge & Icon -->
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-xl bg-slate-100 flex items-center justify-center text-slate-700 text-2xl">
                            <i class="<?= $theme['icon'] ?>"></i>
                        </div>
                        <div>
                            <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider <?= $theme['badge_color'] ?>">
                                <?= $theme['badge'] ?>
                            </span>
                            <h4 class="text-base font-bold text-slate-900 mt-0.5"><?= htmlspecialchars($theme['title']) ?></h4>
                        </div>
                    </div>

                    <p class="text-xs text-slate-500 font-medium"><?= htmlspecialchars($theme['subtitle']) ?></p>

                    <p class="text-xs text-slate-600 leading-relaxed"><?= htmlspecialchars($theme['desc']) ?></p>

                    <!-- Features List -->
                    <div class="pt-3 border-t border-slate-100 space-y-2">
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Key Features:</p>
                        <ul class="space-y-1.5 text-xs text-slate-600">
                            <?php foreach ($theme['features'] as $feat): ?>
                                <li class="flex items-start gap-2">
                                    <i class="ri-check-line text-emerald-600 text-sm shrink-0 mt-0.5"></i>
                                    <span><?= htmlspecialchars($feat) ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>

                <!-- Action Footer -->
                <div class="p-6 bg-slate-50/70 border-t border-slate-100 flex items-center gap-3">
                    <a 
                        href="<?= $theme['preview_url'] ?>" 
                        target="_blank" 
                        class="flex-1 text-center py-2.5 px-3 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-xs font-semibold rounded-xl transition shadow-sm"
                    >
                        <i class="ri-external-link-line align-middle mr-1"></i> Live Preview
                    </a>

                    <?php if ($is_active): ?>
                        <button 
                            type="button" 
                            disabled 
                            class="flex-1 py-2.5 px-3 bg-emerald-600 text-white text-xs font-bold rounded-xl cursor-default flex items-center justify-center gap-1 shadow-sm"
                        >
                            <i class="ri-check-double-line"></i> Selected
                        </button>
                    <?php else: ?>
                        <form action="manage_themes.php" method="POST" class="flex-1">
                            <input type="hidden" name="active_homepage" value="<?= htmlspecialchars($theme['id']) ?>">
                            <button 
                                type="submit" 
                                class="w-full py-2.5 px-3 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl transition shadow-sm active:scale-[0.98]"
                            >
                                Activate Theme
                            </button>
                        </form>
                    <?php endif; ?>
                </div>

            </div>
        <?php endforeach; ?>
    </div>

</div>

<?php include '../includes/admin_footer.php'; ?>
