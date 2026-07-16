<?php
// FILE: /index.php (Router for Home Page Templates)
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/functions.php';

$settings = get_all_settings($conn);
$active_homepage = isset($settings['active_homepage']) ? $settings['active_homepage'] : 'home1.php';

// Allowed homepage templates
$allowed_homepages = ['home1.php', 'home2.php', 'home3.php'];

if (in_array($active_homepage, $allowed_homepages) && file_exists(__DIR__ . '/' . $active_homepage)) {
    include __DIR__ . '/' . $active_homepage;
} else {
    include __DIR__ . '/home1.php';
}
?>