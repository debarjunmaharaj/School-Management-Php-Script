<?php
// This file is included by index.php, so it has access to the $conn and $settings variables.

// Fetch top 3 recent notices for the notice board
$notices_result = mysqli_query($conn, "SELECT title, content, post_date FROM notices ORDER BY post_date DESC LIMIT 3");
?>

<aside class="w-full lg:w-80 p-6 bg-white shrink-0">
    <!-- Quick Login -->
    <div class="bg-primary text-white rounded-lg p-4 mb-6">
        <h3 class="font-semibold mb-3">Portal Login</h3>
        <div class="space-y-3">
             <a href="login.php?portal=student" class="block w-full text-center bg-blue-500 text-white py-2 !rounded-button font-medium hover:bg-blue-600 transition-colors">
                <i class="ri-user-line align-middle mr-2"></i>Student Portal
            </a>
            <a href="login.php?portal=teacher" class="block w-full text-center bg-green-500 text-white py-2 !rounded-button font-medium hover:bg-green-600 transition-colors">
                <i class="ri-graduation-cap-line align-middle mr-2"></i>Teacher Portal
            </a>
            <a href="login.php?portal=parent" class="block w-full text-center bg-purple-500 text-white py-2 !rounded-button font-medium hover:bg-purple-600 transition-colors">
                <i class="ri-parent-line align-middle mr-2"></i>Parent Portal
            </a>
        </div>
    </div>

    <!-- Notice Board -->
    <div class="bg-gray-50 rounded-lg p-4 mb-6">
        <h3 class="font-semibold text-gray-800 mb-3">Notice Board</h3>
        <div class="space-y-3">
            <?php if (mysqli_num_rows($notices_result) > 0): ?>
                <?php while($notice = mysqli_fetch_assoc($notices_result)): ?>
                <div class="text-sm border-b border-gray-200 pb-2 last:border-b-0">
                    <h4 class="font-medium text-gray-800"><?= htmlspecialchars($notice['title']) ?></h4>
                    <p class="text-gray-600 text-xs"><?= htmlspecialchars($notice['content']) ?></p>
                    <span class="text-xs text-gray-400"><?= date('F j, Y', strtotime($notice['post_date'])) ?></span>
                </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="text-xs text-gray-500">No notices at the moment.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Emergency Contact -->
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 rounded-lg p-4 mb-6">
        <h3 class="font-semibold mb-2">Emergency Contact</h3>
        <div class="space-y-1 text-sm">
            <div class="flex items-center"><i class="ri-phone-line mr-2"></i><span><?= htmlspecialchars($settings['emergency_phone'] ?? 'Not Set') ?></span></div>
            <div class="flex items-center"><i class="ri-mail-line mr-2"></i><span><?= htmlspecialchars($settings['emergency_email'] ?? 'Not Set') ?></span></div>
        </div>
    </div>
    
    <!-- Advertisement Space -->
    <?php if (!empty($settings['ad_sidebar_code'])): ?>
        <div class="mt-6">
             <?= $settings['ad_sidebar_code'] // This renders raw HTML from the database, use with caution ?>
        </div>
    <?php endif; ?>

</aside>