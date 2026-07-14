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
    $message = '<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">Settings updated successfully!</div>';
}

$settings = get_all_settings($conn);
$google_fonts = ['Roboto', 'Open Sans', 'Lato', 'Montserrat', 'Poppins', 'Nunito'];
?>

<?= $message ?>
<div class="bg-white p-8 rounded-lg shadow-md">
    <!-- The enctype is crucial for file uploads -->
    <form action="manage_settings.php" method="POST" enctype="multipart/form-data">
        
        <fieldset class="border p-4 rounded-lg mb-6">
            <legend class="text-xl font-semibold px-2">Branding & Appearance</legend>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                <div>
                    <label for="school_name" class="block text-sm font-medium text-gray-700">School Name</label>
                    <input type="text" name="school_name" value="<?= htmlspecialchars($settings['school_name']) ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
                 <div>
                    <label for="primary_font" class="block text-sm font-medium text-gray-700">Primary Font</label>
                    <select name="primary_font" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <?php foreach($google_fonts as $font): ?>
                            <option value="<?= $font ?>" <?= ($settings['primary_font'] == $font) ? 'selected' : '' ?>><?= $font ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label for="site_logo" class="block text-sm font-medium text-gray-700">Site Logo</label>
                    <input type="file" name="site_logo" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-violet-50 hover:file:bg-violet-100">
                    <img src="../<?= htmlspecialchars($settings['site_logo']) ?>" class="mt-2 h-16 bg-gray-200 p-1 rounded">
                </div>
                <div>
                    <label for="site_favicon" class="block text-sm font-medium text-gray-700">Site Favicon (.ico, .png)</label>
                    <input type="file" name="site_favicon" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-violet-50 hover:file:bg-violet-100">
                     <img src="../<?= htmlspecialchars($settings['site_favicon']) ?>" class="mt-2 h-16 bg-gray-200 p-1 rounded">
                </div>
            </div>
        </fieldset>

         <fieldset class="border p-4 rounded-lg mb-6">
            <legend class="text-xl font-semibold px-2">Homepage Hero Section</legend>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                <div>
                    <label for="hero_button_text" class="block text-sm font-medium text-gray-700">Hero Button Text</label>
                    <input type="text" name="hero_button_text" value="<?= htmlspecialchars($settings['hero_button_text']) ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
                <div>
                    <label for="hero_button_url" class="block text-sm font-medium text-gray-700">Hero Button URL</label>
                    <input type="text" name="hero_button_url" value="<?= htmlspecialchars($settings['hero_button_url']) ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
                <div class="md:col-span-2">
                    <label for="hero_background_image" class="block text-sm font-medium text-gray-700">Hero Background Image</label>
                    <input type="file" name="hero_background_image" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-violet-50 hover:file:bg-violet-100">
                    <img src="../<?= htmlspecialchars($settings['hero_background_image']) ?>" class="mt-2 h-24 w-full object-cover rounded">
                </div>
            </div>
        </fieldset>

         <fieldset class="border p-4 rounded-lg mb-6">
            <legend class="text-xl font-semibold px-2">Contact & Social Media</legend>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                <input type="text" name="social_facebook" placeholder="Facebook URL" value="<?= htmlspecialchars($settings['social_facebook']) ?>" class="block w-full rounded-md border-gray-300 shadow-sm">
                <input type="text" name="social_twitter" placeholder="Twitter URL" value="<?= htmlspecialchars($settings['social_twitter']) ?>" class="block w-full rounded-md border-gray-300 shadow-sm">
                <input type="text" name="social_instagram" placeholder="Instagram URL" value="<?= htmlspecialchars($settings['social_instagram']) ?>" class="block w-full rounded-md border-gray-300 shadow-sm">
                <input type="text" name="social_youtube" placeholder="YouTube URL" value="<?= htmlspecialchars($settings['social_youtube']) ?>" class="block w-full rounded-md border-gray-300 shadow-sm">
            </div>
        </fieldset>
        
        <fieldset class="border p-4 rounded-lg mb-6">
            <legend class="text-xl font-semibold px-2">Footer & Ads</legend>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                 <div class="md:col-span-2">
                    <label for="footer_copyright_text" class="block text-sm font-medium text-gray-700">Footer Copyright Text</label>
                    <input type="text" name="footer_copyright_text" value="<?= htmlspecialchars($settings['footer_copyright_text']) ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
                <div class="md:col-span-2">
                    <label for="ad_sidebar_code" class="block text-sm font-medium text-gray-700">Sidebar Ad Code (HTML)</label>
                    <textarea name="ad_sidebar_code" rows="4" class="font-mono mt-1 block w-full rounded-md border-gray-300 shadow-sm"><?= htmlspecialchars($settings['ad_sidebar_code']) ?></textarea>
                </div>
            </div>
        </fieldset>

        <div class="mt-6">
            <button type="submit" name="submit" class="inline-flex py-2 px-4 border shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">Save All Settings</button>
        </div>
    </form>
</div>
<?php include '../includes/admin_footer.php'; ?>