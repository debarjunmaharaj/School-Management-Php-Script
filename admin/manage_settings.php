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
        'hero_background_image' => ['dir' => '../uploads/site_meta/', 'name' => 'hero_bg']
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
    
    // --- TEXT-BASED SETTINGS HANDLING ---
    $stmt = mysqli_prepare($conn, "UPDATE settings SET setting_value = ? WHERE setting_key = ?");
    foreach ($_POST as $key => $value) {
        if ($key == 'submit') continue;
        mysqli_stmt_bind_param($stmt, "ss", $value, $key);
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
                            <option value="<?= $font ?>" <?= ($settings['primary_font'] == $font) ? 'selected' : '' ?>><?= $font ?></option>
                        <?php endforeach; ?>
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