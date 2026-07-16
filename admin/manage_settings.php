<?php
$page_title = 'General Settings';
include '../includes/admin_header.php';
require_once '../includes/functions.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // --- FILE UPLOAD HANDLING ---
    $upload_fields = [
        'site_logo' => ['dir' => '../uploads/logos/', 'name' => 'logo'],
        'site_favicon' => ['dir' => '../uploads/site_meta/', 'name' => 'favicon'],
        'hero_background_image' => ['dir' => '../uploads/site_meta/', 'name' => 'hero_bg'],
        'infra_c1_img' => ['dir' => '../uploads/site_meta/', 'name' => 'infra_c1'],
        'infra_c2_img' => ['dir' => '../uploads/site_meta/', 'name' => 'infra_c2'],
        'infra_c3_img' => ['dir' => '../uploads/site_meta/', 'name' => 'infra_c3'],
        'principal_photo_url' => ['dir' => '../uploads/teachers/', 'name' => 'principal'],
        'minister_photo_url' => ['dir' => '../uploads/site_meta/', 'name' => 'minister'],
        'best_student_image' => ['dir' => '../uploads/site_meta/', 'name' => 'best_student']
    ];

    foreach ($upload_fields as $field_name => $config) {
        if (isset($_FILES[$field_name]) && $_FILES[$field_name]['error'] == 0) {
            // Create a unique filename with the original extension
            $filename = $config['name'] . '.' . pathinfo($_FILES[$field_name]['name'], PATHINFO_EXTENSION);
            $target_file = $config['dir'] . $filename;
            
            if (move_uploaded_file($_FILES[$field_name]['tmp_name'], $target_file)) {
                // We store the relative path from the root ss/ folder
                $db_path = str_replace('../', '', $target_file);
                $stmt_file = $conn->prepare("UPDATE settings SET setting_value = ? WHERE setting_key = ?");
                $stmt_file->bind_param("ss", $db_path, $field_name);
                $stmt_file->execute();
                $stmt_file->close();
            }
        }
    }
    
    // Convert array inputs for footer links to JSON
    if (isset($_POST['footer_academic_units_title']) && is_array($_POST['footer_academic_units_title'])) {
        $units = [];
        for ($i = 0; $i < count($_POST['footer_academic_units_title']); $i++) {
            if (!empty(trim($_POST['footer_academic_units_title'][$i]))) {
                $units[] = [
                    'title' => trim($_POST['footer_academic_units_title'][$i]),
                    'url' => trim($_POST['footer_academic_units_url'][$i] ?? '#')
                ];
            }
        }
        $_POST['footer_academic_units'] = json_encode($units);
        unset($_POST['footer_academic_units_title']);
        unset($_POST['footer_academic_units_url']);
    }

    if (isset($_POST['footer_quick_portals_title']) && is_array($_POST['footer_quick_portals_title'])) {
        $portals = [];
        for ($i = 0; $i < count($_POST['footer_quick_portals_title']); $i++) {
            if (!empty(trim($_POST['footer_quick_portals_title'][$i]))) {
                $portals[] = [
                    'title' => trim($_POST['footer_quick_portals_title'][$i]),
                    'url' => trim($_POST['footer_quick_portals_url'][$i] ?? '#')
                ];
            }
        }
        $_POST['footer_quick_portals'] = json_encode($portals);
        unset($_POST['footer_quick_portals_title']);
        unset($_POST['footer_quick_portals_url']);
    }
    
    // --- TEXT-BASED SETTINGS HANDLING ---
    $stmt = mysqli_prepare($conn, "INSERT INTO settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = ?");
    foreach ($_POST as $key => $value) {
        if ($key == 'submit') continue;
        mysqli_stmt_bind_param($stmt, "sss", $key, $value, $value);
        mysqli_stmt_execute($stmt);
    }
    mysqli_stmt_close($stmt);
    
    $message = '
    <div class="p-4 bg-emerald-50 border border-emerald-100 rounded-xl flex items-start gap-3 text-emerald-800 text-sm mb-6 shadow-sm">
        <i class="ri-checkbox-circle-line text-lg text-emerald-600 shrink-0"></i>
        <div>
            <p class="font-semibold">Settings Consolidated</p>
            <p class="text-emerald-600/90 text-xs mt-0.5">All configuration records and active asset resources have been compiled successfully.</p>
        </div>
    </div>';
}

$settings = get_all_settings($conn);
$google_fonts = ['Roboto', 'Open Sans', 'Lato', 'Montserrat', 'Poppins', 'Nunito'];
?>

<div class="max-w-6xl mx-auto space-y-8 py-4">

    <!-- Render Response Notifications -->
    <?= $message ?>

    <!-- Configuration Form -->
    <form action="manage_settings.php" method="POST" enctype="multipart/form-data" class="space-y-8">
        
        <!-- CARD 1: Branding & Appearance -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 lg:p-8 relative">
            <div class="absolute top-0 left-0 right-0 h-1 bg-indigo-600 rounded-t-2xl"></div>
            
            <div class="mb-6">
                <h3 class="text-lg font-bold text-slate-900">Branding & Appearance</h3>
                <p class="text-xs text-slate-500 mt-1">Configure your primary system titles, active typography properties, and branding logos</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- School Name -->
                <div class="space-y-1.5">
                    <label for="school_name" class="text-xs font-semibold text-slate-600 uppercase tracking-wider">School Name</label>
                    <input 
                        type="text" 
                        name="school_name" 
                        id="school_name"
                        value="<?= htmlspecialchars($settings['school_name'] ?? '') ?>" 
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:bg-white text-sm transition"
                    >
                </div>

                <!-- Primary Font -->
                <div class="space-y-1.5">
                    <label for="primary_font" class="text-xs font-semibold text-slate-600 uppercase tracking-wider">Primary Font</label>
                    <select 
                        name="primary_font" 
                        id="primary_font"
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:bg-white text-sm transition appearance-none"
                    >
                        <?php foreach($google_fonts as $font): ?>
                            <option value="<?= $font ?>" <?= (($settings['primary_font'] ?? '') == $font) ? 'selected' : '' ?>><?= $font ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Active Homepage Template -->
                <div class="space-y-1.5">
                    <label for="active_homepage" class="text-xs font-semibold text-slate-600 uppercase tracking-wider">Active Homepage Template</label>
                    <select 
                        name="active_homepage" 
                        id="active_homepage"
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:bg-white text-sm transition appearance-none"
                    >
                        <option value="home1.php" <?= (($settings['active_homepage'] ?? 'home1.php') == 'home1.php') ? 'selected' : '' ?>>Default Template (home1.php)</option>
                        <option value="home2.php" <?= (($settings['active_homepage'] ?? '') == 'home2.php') ? 'selected' : '' ?>>DMU Template (home2.php)</option>
                        <option value="home3.php" <?= (($settings['active_homepage'] ?? '') == 'home3.php') ? 'selected' : '' ?>>Bengali Primary School Template (home3.php)</option>
                    </select>
                </div>

                <!-- Site Logo -->
                <div class="space-y-3 p-4 rounded-xl border border-slate-100 bg-slate-50/50">
                    <label for="site_logo" class="text-xs font-semibold text-slate-600 uppercase tracking-wider block">Site Logo</label>
                    <input 
                        type="file" 
                        name="site_logo" 
                        id="site_logo"
                        class="block w-full text-sm text-slate-500 file:mr-4 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition cursor-pointer"
                    >
                    <div class="flex items-center gap-3">
                        <img src="../<?= htmlspecialchars($settings['site_logo']) ?>" class="h-12 bg-white border border-slate-200/60 p-2.5 rounded-xl object-contain shadow-sm max-w-[150px]">
                        <span class="text-[10px] text-slate-400 font-medium">Active logo used in headers</span>
                    </div>
                </div>

                <!-- Site Favicon -->
                <div class="space-y-3 p-4 rounded-xl border border-slate-100 bg-slate-50/50">
                    <label for="site_favicon" class="text-xs font-semibold text-slate-600 uppercase tracking-wider block">Site Favicon (.ico, .png)</label>
                    <input 
                        type="file" 
                        name="site_favicon" 
                        id="site_favicon"
                        class="block w-full text-sm text-slate-500 file:mr-4 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition cursor-pointer"
                    >
                    <div class="flex items-center gap-3">
                        <img src="../<?= htmlspecialchars($settings['site_favicon']) ?>" class="w-12 h-12 bg-white border border-slate-200/60 p-2.5 rounded-xl object-contain shadow-sm">
                        <span class="text-[10px] text-slate-400 font-medium">Browser tab representation</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- CARD 2: Homepage Hero Section -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 lg:p-8 relative">
            <div class="absolute top-0 left-0 right-0 h-1 bg-violet-650 bg-violet-600 rounded-t-2xl"></div>
            
            <div class="mb-6">
                <h3 class="text-lg font-bold text-slate-900">Homepage Hero Section</h3>
                <p class="text-xs text-slate-500 mt-1">Configure active layout buttons, URLs, and general media backdrops</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Hero Button Text -->
                <div class="space-y-1.5">
                    <label for="hero_button_text" class="text-xs font-semibold text-slate-600 uppercase tracking-wider">Hero Button Text</label>
                    <input 
                        type="text" 
                        name="hero_button_text" 
                        id="hero_button_text"
                        value="<?= htmlspecialchars($settings['hero_button_text'] ?? '') ?>" 
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:bg-white text-sm transition"
                    >
                </div>

                <!-- Hero Button URL -->
                <div class="space-y-1.5">
                    <label for="hero_button_url" class="text-xs font-semibold text-slate-600 uppercase tracking-wider">Hero Button URL</label>
                    <input 
                        type="text" 
                        name="hero_button_url" 
                        id="hero_button_url"
                        value="<?= htmlspecialchars($settings['hero_button_url'] ?? '') ?>" 
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:bg-white text-sm transition"
                    >
                </div>

                <!-- Hero Background Image -->
                <div class="md:col-span-2 space-y-3 p-4 rounded-xl border border-slate-100 bg-slate-50/50">
                    <label for="hero_background_image" class="text-xs font-semibold text-slate-600 uppercase tracking-wider block">Hero Background Image</label>
                    <input 
                        type="file" 
                        name="hero_background_image" 
                        id="hero_background_image"
                        class="block w-full text-sm text-slate-500 file:mr-4 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition cursor-pointer"
                    >
                    <div class="rounded-xl overflow-hidden border border-slate-200/60 shadow-sm max-h-48 bg-white p-2">
                        <img src="../<?= htmlspecialchars($settings['hero_background_image']) ?>" class="w-full h-full object-cover rounded-lg max-h-44">
                    </div>
                </div>
            </div>
        </div>

        <!-- CARD 3: Contact & Social Media -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 lg:p-8 relative">
            <div class="absolute top-0 left-0 right-0 h-1 bg-emerald-600 rounded-t-2xl"></div>
            
            <div class="mb-6">
                <h3 class="text-lg font-bold text-slate-900">Contact & Social Media</h3>
                <p class="text-xs text-slate-500 mt-1">Anchor your direct digital layout references and platforms</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Facebook -->
                <div class="space-y-1.5">
                    <label for="social_facebook" class="text-xs font-semibold text-slate-600 uppercase tracking-wider block">Facebook URL</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-blue-600">
                            <i class="ri-facebook-circle-fill text-lg"></i>
                        </div>
                        <input 
                            type="text" 
                            name="social_facebook" 
                            id="social_facebook"
                            placeholder="https://facebook.com/..." 
                            value="<?= htmlspecialchars($settings['social_facebook'] ?? '') ?>" 
                            class="w-full pl-11 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:bg-white text-sm transition"
                        >
                    </div>
                </div>

                <!-- Twitter / X -->
                <div class="space-y-1.5">
                    <label for="social_twitter" class="text-xs font-semibold text-slate-600 uppercase tracking-wider block">Twitter / X URL</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-900">
                            <i class="ri-twitter-x-fill text-lg"></i>
                        </div>
                        <input 
                            type="text" 
                            name="social_twitter" 
                            id="social_twitter"
                            placeholder="https://twitter.com/..." 
                            value="<?= htmlspecialchars($settings['social_twitter'] ?? '') ?>" 
                            class="w-full pl-11 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:bg-white text-sm transition"
                        >
                    </div>
                </div>

                <!-- Instagram -->
                <div class="space-y-1.5">
                    <label for="social_instagram" class="text-xs font-semibold text-slate-600 uppercase tracking-wider block">Instagram URL</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-pink-600">
                            <i class="ri-instagram-line text-lg font-bold"></i>
                        </div>
                        <input 
                            type="text" 
                            name="social_instagram" 
                            id="social_instagram"
                            placeholder="https://instagram.com/..." 
                            value="<?= htmlspecialchars($settings['social_instagram'] ?? '') ?>" 
                            class="w-full pl-11 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:bg-white text-sm transition"
                        >
                    </div>
                </div>

                <!-- YouTube -->
                <div class="space-y-1.5">
                    <label for="social_youtube" class="text-xs font-semibold text-slate-600 uppercase tracking-wider block">YouTube URL</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-red-600">
                            <i class="ri-youtube-fill text-lg"></i>
                        </div>
                        <input 
                            type="text" 
                            name="social_youtube" 
                            id="social_youtube"
                            placeholder="https://youtube.com/..." 
                            value="<?= htmlspecialchars($settings['social_youtube'] ?? '') ?>" 
                            class="w-full pl-11 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:bg-white text-sm transition"
                        >
                    </div>
                </div>

                <!-- Contact Phone -->
                <div class="space-y-1.5">
                    <label for="contact_phone" class="text-xs font-semibold text-slate-600 uppercase tracking-wider block">Contact Phone</label>
                    <input 
                        type="text" 
                        name="contact_phone" 
                        id="contact_phone"
                        value="<?= htmlspecialchars($settings['contact_phone'] ?? '') ?>" 
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:bg-white text-sm transition"
                    >
                </div>

                <!-- Contact Email -->
                <div class="space-y-1.5">
                    <label for="contact_email" class="text-xs font-semibold text-slate-600 uppercase tracking-wider block">Contact Email</label>
                    <input 
                        type="email" 
                        name="contact_email" 
                        id="contact_email"
                        value="<?= htmlspecialchars($settings['contact_email'] ?? '') ?>" 
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:bg-white text-sm transition"
                    >
                </div>

                <!-- Contact Address -->
                <div class="md:col-span-2 space-y-1.5">
                    <label for="contact_address" class="text-xs font-semibold text-slate-600 uppercase tracking-wider block">Contact Address</label>
                    <input 
                        type="text" 
                        name="contact_address" 
                        id="contact_address"
                        value="<?= htmlspecialchars($settings['contact_address'] ?? '') ?>" 
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:bg-white text-sm transition"
                    >
                </div>
            </div>
        </div>
        
        <!-- CARD 4: Footer & Ads -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 lg:p-8 relative">
            <div class="absolute top-0 left-0 right-0 h-1 bg-slate-700 rounded-t-2xl"></div>
            
            <div class="mb-6">
                <h3 class="text-lg font-bold text-slate-900">Footer & Ads</h3>
                <p class="text-xs text-slate-500 mt-1">Adjust copyright labels, structural configurations, and ad-code script injections</p>
            </div>

            <div class="grid grid-cols-1 gap-6">
                <!-- Footer Copyright Text -->
                <div class="space-y-1.5">
                    <label for="footer_copyright_text" class="text-xs font-semibold text-slate-600 uppercase tracking-wider">Footer Copyright Text</label>
                    <input 
                        type="text" 
                        name="footer_copyright_text" 
                        id="footer_copyright_text"
                        value="<?= htmlspecialchars($settings['footer_copyright_text'] ?? '') ?>" 
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:bg-white text-sm transition font-medium"
                    >
                </div>

                <!-- Sidebar Ad Code -->
                <div class="space-y-1.5">
                    <label for="ad_sidebar_code" class="text-xs font-semibold text-slate-600 uppercase tracking-wider">Sidebar Ad Code (HTML)</label>
                    <textarea 
                        name="ad_sidebar_code" 
                        id="ad_sidebar_code"
                        rows="5" 
                        placeholder="<!-- Insert AdSense or HTML block ad here -->"
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:bg-white text-sm transition font-mono"
                    ><?= htmlspecialchars($settings['ad_sidebar_code'] ?? '') ?></textarea>
                </div>
            </div>
        </div>

        <!-- CARD 5: Homepage Content & Footer Customization -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 lg:p-8 relative">
            <div class="absolute top-0 left-0 right-0 h-1 bg-amber-600 rounded-t-2xl"></div>
            
            <div class="mb-6">
                <h3 class="text-lg font-bold text-slate-900">Homepage Content & Footer Customization</h3>
                <p class="text-xs text-slate-500 mt-1">Configure homepage infrastructure sections, research logs, and footer resource areas</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Infrastructure Card 1 -->
                <div class="space-y-3 p-4 rounded-xl border border-slate-100 bg-slate-50/50 md:col-span-2">
                    <h4 class="text-sm font-bold text-slate-800">Campus Infrastructure - Card 1</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label for="infra_c1_title" class="text-xs font-semibold text-slate-600 uppercase tracking-wider">Title</label>
                            <input type="text" name="infra_c1_title" id="infra_c1_title" value="<?= htmlspecialchars($settings['infra_c1_title'] ?? 'Central Digital Library') ?>" class="w-full px-4 py-2 bg-white border border-slate-200 rounded-xl text-sm">
                        </div>
                        <div class="space-y-1.5">
                            <label for="infra_c1_img" class="text-xs font-semibold text-slate-600 uppercase tracking-wider">Upload Image</label>
                            <input type="file" name="infra_c1_img" id="infra_c1_img" class="block w-full text-sm text-slate-500 file:mr-4 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition cursor-pointer">
                            <?php if(!empty($settings['infra_c1_img'])): ?>
                            <div class="mt-2 h-16 w-16 rounded overflow-hidden shadow-sm border border-slate-200">
                                <img src="../<?= htmlspecialchars($settings['infra_c1_img']) ?>" class="w-full h-full object-cover">
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="space-y-1.5 md:col-span-2">
                            <label for="infra_c1_desc" class="text-xs font-semibold text-slate-600 uppercase tracking-wider">Description</label>
                            <textarea name="infra_c1_desc" id="infra_c1_desc" rows="2" class="w-full px-4 py-2 bg-white border border-slate-200 rounded-xl text-sm"><?= htmlspecialchars($settings['infra_c1_desc'] ?? 'Housing over 50,000 reference volumes alongside access to premium research databases.') ?></textarea>
                        </div>
                    </div>
                </div>

                <!-- Infrastructure Card 2 -->
                <div class="space-y-3 p-4 rounded-xl border border-slate-100 bg-slate-50/50 md:col-span-2">
                    <h4 class="text-sm font-bold text-slate-800">Campus Infrastructure - Card 2</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label for="infra_c2_title" class="text-xs font-semibold text-slate-600 uppercase tracking-wider">Title</label>
                            <input type="text" name="infra_c2_title" id="infra_c2_title" value="<?= htmlspecialchars($settings['infra_c2_title'] ?? 'Modern Physics & CSE Labs') ?>" class="w-full px-4 py-2 bg-white border border-slate-200 rounded-xl text-sm">
                        </div>
                        <div class="space-y-1.5">
                            <label for="infra_c2_img" class="text-xs font-semibold text-slate-600 uppercase tracking-wider">Upload Image</label>
                            <input type="file" name="infra_c2_img" id="infra_c2_img" class="block w-full text-sm text-slate-500 file:mr-4 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition cursor-pointer">
                            <?php if(!empty($settings['infra_c2_img'])): ?>
                            <div class="mt-2 h-16 w-16 rounded overflow-hidden shadow-sm border border-slate-200">
                                <img src="../<?= htmlspecialchars($settings['infra_c2_img']) ?>" class="w-full h-full object-cover">
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="space-y-1.5 md:col-span-2">
                            <label for="infra_c2_desc" class="text-xs font-semibold text-slate-600 uppercase tracking-wider">Description</label>
                            <textarea name="infra_c2_desc" id="infra_c2_desc" rows="2" class="w-full px-4 py-2 bg-white border border-slate-200 rounded-xl text-sm"><?= htmlspecialchars($settings['infra_c2_desc'] ?? 'Fully updated instrumentation arrays designed for practical academic evaluations.') ?></textarea>
                        </div>
                    </div>
                </div>

                <!-- Infrastructure Card 3 -->
                <div class="space-y-3 p-4 rounded-xl border border-slate-100 bg-slate-50/50 md:col-span-2">
                    <h4 class="text-sm font-bold text-slate-800">Campus Infrastructure - Card 3</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label for="infra_c3_title" class="text-xs font-semibold text-slate-600 uppercase tracking-wider">Title</label>
                            <input type="text" name="infra_c3_title" id="infra_c3_title" value="<?= htmlspecialchars($settings['infra_c3_title'] ?? 'Sports & Physical Rec Center') ?>" class="w-full px-4 py-2 bg-white border border-slate-200 rounded-xl text-sm">
                        </div>
                        <div class="space-y-1.5">
                            <label for="infra_c3_img" class="text-xs font-semibold text-slate-600 uppercase tracking-wider">Upload Image</label>
                            <input type="file" name="infra_c3_img" id="infra_c3_img" class="block w-full text-sm text-slate-500 file:mr-4 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition cursor-pointer">
                            <?php if(!empty($settings['infra_c3_img'])): ?>
                            <div class="mt-2 h-16 w-16 rounded overflow-hidden shadow-sm border border-slate-200">
                                <img src="../<?= htmlspecialchars($settings['infra_c3_img']) ?>" class="w-full h-full object-cover">
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="space-y-1.5 md:col-span-2">
                            <label for="infra_c3_desc" class="text-xs font-semibold text-slate-600 uppercase tracking-wider">Description</label>
                            <textarea name="infra_c3_desc" id="infra_c3_desc" rows="2" class="w-full px-4 py-2 bg-white border border-slate-200 rounded-xl text-sm"><?= htmlspecialchars($settings['infra_c3_desc'] ?? 'Fostering teamwork, health, and leadership skills through competitive tournaments.') ?></textarea>
                        </div>
                    </div>
                </div>

                <!-- Footer Column 1: About text -->
                <div class="space-y-1.5 md:col-span-2">
                    <label for="footer_about_text" class="text-xs font-semibold text-slate-600 uppercase tracking-wider block">Footer About Text</label>
                    <textarea name="footer_about_text" id="footer_about_text" rows="3" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm"><?= htmlspecialchars($settings['footer_about_text'] ?? 'Authorized by the Government of Bangladesh and recognized by the University Grants Commission (UGC), Springfield Elementary emphasizes research, innovation, and ethical development.') ?></textarea>
                </div>

                <!-- Footer Column 2: Academic Units Dynamic List -->
                <div class="space-y-3 md:col-span-2 p-4 rounded-xl border border-slate-100 bg-slate-50/50">
                    <label class="text-xs font-semibold text-slate-600 uppercase tracking-wider block">Footer Academic Units</label>
                    <div id="academic_units_container" class="space-y-2">
                        <?php 
                        $academic_units = json_decode($settings['footer_academic_units'] ?? '[]', true);
                        if (!is_array($academic_units) || empty($academic_units)) {
                            // Default layout
                            $academic_units = [
                                ['title' => 'Computer Science & Engineering', 'url' => '#'],
                                ['title' => 'Electrical & Electronic Engineering', 'url' => '#']
                            ];
                        }
                        foreach ($academic_units as $unit): ?>
                        <div class="flex gap-2 items-center">
                            <input type="text" name="footer_academic_units_title[]" value="<?= htmlspecialchars($unit['title']) ?>" placeholder="Link Title" class="w-1/2 px-4 py-2 bg-white border border-slate-200 rounded-xl text-sm">
                            <input type="text" name="footer_academic_units_url[]" value="<?= htmlspecialchars($unit['url']) ?>" placeholder="URL (e.g. # or /page)" class="w-1/2 px-4 py-2 bg-white border border-slate-200 rounded-xl text-sm">
                            <button type="button" onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-700 p-2"><i class="ri-delete-bin-line text-lg"></i></button>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <button type="button" onclick="addFooterLink('academic_units_container', 'footer_academic_units_title[]', 'footer_academic_units_url[]')" class="mt-2 text-sm text-indigo-600 font-semibold hover:text-indigo-800"><i class="ri-add-line"></i> Add Another Link</button>
                </div>

                <!-- Footer Column 3: Quick Portals Dynamic List -->
                <div class="space-y-3 md:col-span-2 p-4 rounded-xl border border-slate-100 bg-slate-50/50">
                    <label class="text-xs font-semibold text-slate-600 uppercase tracking-wider block">Footer Quick Portals</label>
                    <div id="quick_portals_container" class="space-y-2">
                        <?php 
                        $quick_portals = json_decode($settings['footer_quick_portals'] ?? '[]', true);
                        if (!is_array($quick_portals) || empty($quick_portals)) {
                            $quick_portals = [
                                ['title' => 'Student Online Registration', 'url' => '#'],
                                ['title' => 'Faculty Directory', 'url' => '#']
                            ];
                        }
                        foreach ($quick_portals as $portal): ?>
                        <div class="flex gap-2 items-center">
                            <input type="text" name="footer_quick_portals_title[]" value="<?= htmlspecialchars($portal['title']) ?>" placeholder="Link Title" class="w-1/2 px-4 py-2 bg-white border border-slate-200 rounded-xl text-sm">
                            <input type="text" name="footer_quick_portals_url[]" value="<?= htmlspecialchars($portal['url']) ?>" placeholder="URL (e.g. # or /page)" class="w-1/2 px-4 py-2 bg-white border border-slate-200 rounded-xl text-sm">
                            <button type="button" onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-700 p-2"><i class="ri-delete-bin-line text-lg"></i></button>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <button type="button" onclick="addFooterLink('quick_portals_container', 'footer_quick_portals_title[]', 'footer_quick_portals_url[]')" class="mt-2 text-sm text-indigo-600 font-semibold hover:text-indigo-800"><i class="ri-add-line"></i> Add Another Link</button>
                </div>

                <!-- Footer Bottom: Designed By text -->
                <div class="space-y-1.5 md:col-span-2">
                    <label for="footer_designed_text" class="text-xs font-semibold text-slate-600 uppercase tracking-wider block">Footer Designed By / Monitored By Text</label>
                    <input type="text" name="footer_designed_text" id="footer_designed_text" value="<?= htmlspecialchars($settings['footer_designed_text'] ?? 'Designed and monitored by the ICT Cell, Dhaka Metropolitan University.') ?>" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm">
                </div>
                
                <script>
                function addFooterLink(containerId, titleName, urlName) {
                    const container = document.getElementById(containerId);
                    const div = document.createElement('div');
                    div.className = 'flex gap-2 items-center';
                    div.innerHTML = `
                        <input type="text" name="${titleName}" placeholder="Link Title" class="w-1/2 px-4 py-2 bg-white border border-slate-200 rounded-xl text-sm">
                        <input type="text" name="${urlName}" placeholder="URL (e.g. # or /page)" class="w-1/2 px-4 py-2 bg-white border border-slate-200 rounded-xl text-sm">
                        <button type="button" onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-700 p-2"><i class="ri-delete-bin-line text-lg"></i></button>
                    `;
                    container.appendChild(div);
                }
                </script>
            </div>
        </div>

        <!-- CARD 6: Bengali Template & Leader Messages -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 lg:p-8 relative">
            <div class="absolute top-0 left-0 right-0 h-1 bg-emerald-500 rounded-t-2xl"></div>
            
            <div class="mb-6">
                <h3 class="text-lg font-bold text-slate-900">Bengali Template Settings & Leaders Message</h3>
                <p class="text-xs text-slate-500 mt-1">Configure specific options for home3.php template, EIIN, stats, leaders message and best student corner</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- EIIN Number -->
                <div class="space-y-1.5 md:col-span-2">
                    <label for="school_eiin" class="text-xs font-semibold text-slate-600 uppercase tracking-wider block">EIIN Number</label>
                    <input type="text" name="school_eiin" id="school_eiin" value="<?= htmlspecialchars($settings['school_eiin'] ?? '১০২২৩৪') ?>" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm">
                </div>

                <!-- Principal Message Settings -->
                <div class="space-y-3 p-4 rounded-xl border border-slate-100 bg-slate-50/50 md:col-span-2">
                    <h4 class="text-sm font-bold text-slate-800">Principal's / Head Teacher's Message</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label for="principal_message_title" class="text-xs font-semibold text-slate-600 uppercase tracking-wider">Title / Header</label>
                            <input type="text" name="principal_message_title" id="principal_message_title" value="<?= htmlspecialchars($settings['principal_message_title'] ?? 'প্রধান শিক্ষকের বাণী') ?>" class="w-full px-4 py-2 bg-white border border-slate-200 rounded-xl text-sm">
                        </div>
                        <div class="space-y-1.5">
                            <label for="principal_message_name" class="text-xs font-semibold text-slate-600 uppercase tracking-wider">Name</label>
                            <input type="text" name="principal_message_name" id="principal_message_name" value="<?= htmlspecialchars($settings['principal_message_name'] ?? 'মোসাম্মৎ রেহানা বেগম') ?>" class="w-full px-4 py-2 bg-white border border-slate-200 rounded-xl text-sm">
                        </div>
                        <div class="space-y-1.5">
                            <label for="principal_photo_url" class="text-xs font-semibold text-slate-600 uppercase tracking-wider">Upload Principal Photo</label>
                            <input type="file" name="principal_photo_url" id="principal_photo_url" class="block w-full text-sm text-slate-500 file:mr-4 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition cursor-pointer">
                            <?php if(!empty($settings['principal_photo_url'])): ?>
                            <div class="mt-2 h-16 w-16 rounded overflow-hidden shadow-sm border border-slate-200">
                                <img src="../<?= htmlspecialchars($settings['principal_photo_url']) ?>" class="w-full h-full object-cover">
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="space-y-1.5 md:col-span-2">
                            <label for="principal_message_content" class="text-xs font-semibold text-slate-600 uppercase tracking-wider">Message Content</label>
                            <textarea name="principal_message_content" id="principal_message_content" rows="4" class="w-full px-4 py-2 bg-white border border-slate-200 rounded-xl text-sm"><?= htmlspecialchars($settings['principal_message_content'] ?? '') ?></textarea>
                        </div>
                    </div>
                </div>

                <!-- Minister / Chairman Message Settings -->
                <div class="space-y-3 p-4 rounded-xl border border-slate-100 bg-slate-50/50 md:col-span-2">
                    <h4 class="text-sm font-bold text-slate-800">Chairman's / Minister's Message</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label for="minister_message_title" class="text-xs font-semibold text-slate-600 uppercase tracking-wider">Title / Header</label>
                            <input type="text" name="minister_message_title" id="minister_message_title" value="<?= htmlspecialchars($settings['minister_message_title'] ?? 'সভাপতির বাণী') ?>" class="w-full px-4 py-2 bg-white border border-slate-200 rounded-xl text-sm">
                        </div>
                        <div class="space-y-1.5">
                            <label for="minister_message_name" class="text-xs font-semibold text-slate-600 uppercase tracking-wider">Name</label>
                            <input type="text" name="minister_message_name" id="minister_message_name" value="<?= htmlspecialchars($settings['minister_message_name'] ?? 'আলহাজ্ব মোঃ আব্দুর রহমান') ?>" class="w-full px-4 py-2 bg-white border border-slate-200 rounded-xl text-sm">
                        </div>
                        <div class="space-y-1.5">
                            <label for="minister_photo_url" class="text-xs font-semibold text-slate-600 uppercase tracking-wider">Upload Chairman Photo</label>
                            <input type="file" name="minister_photo_url" id="minister_photo_url" class="block w-full text-sm text-slate-500 file:mr-4 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition cursor-pointer">
                            <?php if(!empty($settings['minister_photo_url'])): ?>
                            <div class="mt-2 h-16 w-16 rounded overflow-hidden shadow-sm border border-slate-200">
                                <img src="../<?= htmlspecialchars($settings['minister_photo_url']) ?>" class="w-full h-full object-cover">
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="space-y-1.5 md:col-span-2">
                            <label for="minister_message_content" class="text-xs font-semibold text-slate-600 uppercase tracking-wider">Message Content</label>
                            <textarea name="minister_message_content" id="minister_message_content" rows="4" class="w-full px-4 py-2 bg-white border border-slate-200 rounded-xl text-sm"><?= htmlspecialchars($settings['minister_message_content'] ?? '') ?></textarea>
                        </div>
                    </div>
                </div>

                <!-- Best Student Corner Settings -->
                <div class="space-y-3 p-4 rounded-xl border border-slate-100 bg-slate-50/50 md:col-span-2">
                    <h4 class="text-sm font-bold text-slate-800">Best Student Corner (সেরা শিক্ষার্থী কর্নার)</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label for="best_student_name" class="text-xs font-semibold text-slate-600 uppercase tracking-wider">Student Name</label>
                            <input type="text" name="best_student_name" id="best_student_name" value="<?= htmlspecialchars($settings['best_student_name'] ?? 'সুমাইয়া জাহান মিম') ?>" class="w-full px-4 py-2 bg-white border border-slate-200 rounded-xl text-sm">
                        </div>
                        <div class="space-y-1.5">
                            <label for="best_student_class_roll" class="text-xs font-semibold text-slate-600 uppercase tracking-wider">Class & Roll</label>
                            <input type="text" name="best_student_class_roll" id="best_student_class_roll" value="<?= htmlspecialchars($settings['best_student_class_roll'] ?? 'শ্রেণি: পঞ্চম, রোল: ০১') ?>" class="w-full px-4 py-2 bg-white border border-slate-200 rounded-xl text-sm">
                        </div>
                        <div class="space-y-1.5">
                            <label for="best_student_image" class="text-xs font-semibold text-slate-600 uppercase tracking-wider">Upload Student Image</label>
                            <input type="file" name="best_student_image" id="best_student_image" class="block w-full text-sm text-slate-500 file:mr-4 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition cursor-pointer">
                            <?php if(!empty($settings['best_student_image'])): ?>
                            <div class="mt-2 h-16 w-16 rounded overflow-hidden shadow-sm border border-slate-200">
                                <img src="../<?= htmlspecialchars($settings['best_student_image']) ?>" class="w-full h-full object-cover">
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="space-y-1.5 md:col-span-2">
                            <label for="best_student_desc" class="text-xs font-semibold text-slate-600 uppercase tracking-wider">Reason/Description</label>
                            <textarea name="best_student_desc" id="best_student_desc" rows="3" class="w-full px-4 py-2 bg-white border border-slate-200 rounded-xl text-sm"><?= htmlspecialchars($settings['best_student_desc'] ?? 'চমৎকার উপস্থিতি ও ক্লাসে মনোযোগী থাকার জন্য এ মাসের সেরা নির্বাচিত হয়েছে।') ?></textarea>
                        </div>
                    </div>
                </div>

                <!-- Statistics Settings -->
                <div class="space-y-3 p-4 rounded-xl border border-slate-100 bg-slate-50/50 md:col-span-2">
                    <h4 class="text-sm font-bold text-slate-800">School Statistics</h4>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="space-y-1.5">
                            <label for="stats_students" class="text-xs font-semibold text-slate-600 uppercase tracking-wider">Students Count</label>
                            <input type="text" name="stats_students" id="stats_students" value="<?= htmlspecialchars($settings['stats_students'] ?? '৪৫০+') ?>" class="w-full px-4 py-2 bg-white border border-slate-200 rounded-xl text-sm">
                        </div>
                        <div class="space-y-1.5">
                            <label for="stats_teachers" class="text-xs font-semibold text-slate-600 uppercase tracking-wider">Teachers Count</label>
                            <input type="text" name="stats_teachers" id="stats_teachers" value="<?= htmlspecialchars($settings['stats_teachers'] ?? '১২ জন') ?>" class="w-full px-4 py-2 bg-white border border-slate-200 rounded-xl text-sm">
                        </div>
                        <div class="space-y-1.5">
                            <label for="stats_pass_rate" class="text-xs font-semibold text-slate-600 uppercase tracking-wider">Pass Rate</label>
                            <input type="text" name="stats_pass_rate" id="stats_pass_rate" value="<?= htmlspecialchars($settings['stats_pass_rate'] ?? '১০০%') ?>" class="w-full px-4 py-2 bg-white border border-slate-200 rounded-xl text-sm">
                        </div>
                        <div class="space-y-1.5">
                            <label for="stats_classrooms" class="text-xs font-semibold text-slate-600 uppercase tracking-wider">Classrooms Count</label>
                            <input type="text" name="stats_classrooms" id="stats_classrooms" value="<?= htmlspecialchars($settings['stats_classrooms'] ?? '১০টি') ?>" class="w-full px-4 py-2 bg-white border border-slate-200 rounded-xl text-sm">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Submit Button -->
        <div class="pt-2">
            <button 
                type="submit" 
                name="submit" 
                class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold py-3 px-6 rounded-xl transition shadow-lg shadow-indigo-600/10 active:scale-[0.98]"
            >
                Save All Settings
            </button>
        </div>
    </form>
</div>

<?php include '../includes/admin_footer.php'; ?>