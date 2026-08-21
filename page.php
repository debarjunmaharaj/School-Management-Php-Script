<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/functions.php';

$settings = get_all_settings($conn);
$slug = $_GET['slug'] ?? '';

if (empty($slug)) {
    http_response_code(404);
    $page_title = "Error 404";
    include 'includes/header.php';
    echo '<div class="text-center py-20 bg-white rounded-2xl p-8 shadow-sm"><h2 class="text-2xl font-bold text-slate-800">Page Not Found</h2><p class="text-slate-500 mt-2">No page was specified.</p><a href="index.php" class="text-indigo-600 hover:underline mt-4 inline-block font-semibold">← Return to Homepage</a></div>';
    include 'includes/footer.php';
    exit();
}

if ($slug === 'notices') {
    $page = [
        'title' => (($settings['active_homepage'] ?? '') === 'home3.php') ? 'নোটিশ বোর্ড' : 'General Notices',
        'content' => ''
    ];
    $all_notices_result = mysqli_query($conn, "SELECT * FROM notices ORDER BY post_date DESC, id DESC");
    $notices_html = '<div class="space-y-4">';
    if ($all_notices_result && mysqli_num_rows($all_notices_result) > 0) {
        while ($n = mysqli_fetch_assoc($all_notices_result)) {
            $notices_html .= '
            <div class="p-6 bg-slate-50 border border-slate-200 rounded-xl shadow-sm">
                <span class="text-xs bg-red-800 text-white font-bold px-2.5 py-1 rounded inline-block">' . date('M d, Y', strtotime($n['post_date'])) . '</span>
                <h3 class="text-xl font-bold text-slate-900 mt-3">' . htmlspecialchars($n['title']) . '</h3>
                <p class="text-sm text-slate-700 mt-2 leading-relaxed">' . nl2br(htmlspecialchars($n['content'])) . '</p>
            </div>';
        }
    } else {
        $notices_html .= '<p class="text-slate-500">No notices found.</p>';
    }
    $notices_html .= '</div>';
    $page['content'] = $notices_html;
} elseif ($slug === 'teachers') {
    $page = [
        'title' => (($settings['active_homepage'] ?? '') === 'home3.php') ? 'আমাদের সম্মানিত শিক্ষকবৃন্দ' : 'Our Teachers & Faculty',
        'content' => ''
    ];
    $teachers_res = mysqli_query($conn, "SELECT * FROM teachers ORDER BY id ASC");
    $teachers_html = '<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">';
    if ($teachers_res && mysqli_num_rows($teachers_res) > 0) {
        while ($t = mysqli_fetch_assoc($teachers_res)) {
            $img = !empty($t['image_url']) ? htmlspecialchars($t['image_url']) : '';
            $teachers_html .= '
            <div class="bg-slate-50 border border-slate-200 rounded-xl p-6 text-center shadow-sm hover:shadow-md transition">
                <div class="w-24 h-24 rounded-full mx-auto mb-4 overflow-hidden bg-slate-200 border-2 border-indigo-500/30 flex items-center justify-center">';
            if (!empty($img)) {
                $teachers_html .= '<img src="' . $img . '" class="w-full h-full object-cover">';
            } else {
                $teachers_html .= '<i class="ri-user-star-line text-4xl text-slate-400"></i>';
            }
            $teachers_html .= '</div>
                <h3 class="text-lg font-bold text-slate-900">' . htmlspecialchars($t['name']) . '</h3>
                <p class="text-sm text-indigo-600 font-semibold mt-1">' . htmlspecialchars($t['subject']) . '</p>
                ' . (!empty($t['education']) ? '<p class="text-xs text-slate-500 mt-1">' . htmlspecialchars($t['education']) . '</p>' : '') . '
            </div>';
        }
    } else {
        $teachers_html .= '<p class="text-slate-500 col-span-full">No teacher profiles available.</p>';
    }
    $teachers_html .= '</div>';
    $page['content'] = $teachers_html;
} elseif ($slug === 'committee' || $slug === 'managing-committee') {
    $page = [
        'title' => (($settings['active_homepage'] ?? '') === 'home3.php') ? 'ম্যানেজিং কমিটি' : 'Managing Committee',
        'content' => ''
    ];
    $comm_res = mysqli_query($conn, "SELECT * FROM managing_committee ORDER BY id ASC");
    $comm_html = '<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">';
    if ($comm_res && mysqli_num_rows($comm_res) > 0) {
        while ($c = mysqli_fetch_assoc($comm_res)) {
            $img = !empty($c['image_url']) ? htmlspecialchars($c['image_url']) : '';
            $comm_html .= '
            <div class="bg-slate-50 border border-slate-200 rounded-xl p-6 text-center shadow-sm hover:shadow-md transition">
                <div class="w-24 h-24 rounded-full mx-auto mb-4 overflow-hidden bg-slate-200 border-2 border-emerald-500/30 flex items-center justify-center">';
            if (!empty($img)) {
                $comm_html .= '<img src="' . $img . '" class="w-full h-full object-cover">';
            } else {
                $comm_html .= '<i class="ri-team-line text-4xl text-slate-400"></i>';
            }
            $comm_html .= '</div>
                <h3 class="text-lg font-bold text-slate-900">' . htmlspecialchars($c['name']) . '</h3>
                <p class="text-sm text-emerald-700 font-semibold mt-1">' . htmlspecialchars($c['designation']) . '</p>
                ' . (!empty($c['phone']) ? '<p class="text-xs text-slate-500 mt-1">' . htmlspecialchars($c['phone']) . '</p>' : '') . '
            </div>';
        }
    } else {
        $comm_html .= '<p class="text-slate-500 col-span-full">No committee member records available.</p>';
    }
    $comm_html .= '</div>';
    $page['content'] = $comm_html;
} else {
    $stmt = $conn->prepare("SELECT title, content FROM pages WHERE slug = ?");
    $stmt->bind_param("s", $slug);
    $stmt->execute();
    $result = $stmt->get_result();
    $page = $result->fetch_assoc();
    $stmt->close();
}

if (!$page) {
    http_response_code(404);
    $page_title = "Error 404";
    include 'includes/header.php';
    echo '<div class="text-center py-20 bg-white rounded-2xl p-8 shadow-sm"><h2 class="text-2xl font-bold text-slate-800">Page Not Found</h2><p class="text-slate-500 mt-2">The page you are looking for does not exist.</p><a href="index.php" class="text-indigo-600 hover:underline mt-4 inline-block font-semibold">← Return to Homepage</a></div>';
    include 'includes/footer.php';
    exit();
}

$page_title = $page['title'];
include 'includes/header.php';
?>

<div class="bg-white p-8 md:p-12 rounded-2xl shadow-sm border border-slate-100 mb-8">
    <article class="prose lg:prose-xl max-w-none text-slate-700 leading-relaxed">
        <?= $page['content'] ?>
    </article>

    <div class="mt-12 pt-6 border-t border-slate-100 flex items-center justify-between text-sm">
        <a href="index.php" class="text-indigo-600 hover:text-indigo-800 font-semibold inline-flex items-center gap-1.5 transition">
            <i class="fa-solid fa-arrow-left"></i> <?= (($settings['active_homepage'] ?? '') === 'home3.php') ? 'হোমপেজে ফিরে যান' : 'Back to Homepage' ?>
        </a>
    </div>
</div>

<?php
include 'includes/footer.php';
?>