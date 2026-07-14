<?php
function get_all_settings($conn) {
    $settings = [];
    $sql = "SELECT setting_key, setting_value FROM settings";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        while($row = mysqli_fetch_assoc($result)){
            $settings[$row['setting_key']] = $row['setting_value'];
        }
    }
    return $settings;
}

function format_file_size($bytes) {
    if ($bytes >= 1048576) return number_format($bytes / 1048576, 2) . ' MB';
    if ($bytes >= 1024) return number_format($bytes / 1024, 2) . ' KB';
    return $bytes . ' bytes';
}
?>