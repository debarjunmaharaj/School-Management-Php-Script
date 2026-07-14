<?php
// --- LOGIN PAGE LOGIC --- //
$portal = $_GET['portal'] ?? 'student'; // Default to student
$portal_details = [
    'student' => ['title' => 'Student Portal', 'color' => 'blue'],
    'teacher' => ['title' => 'Teacher Portal', 'color' => 'green'],
    'parent'  => ['title' => 'Parent Portal',  'color' => 'purple']
];

// Get the correct details, or default if the portal name is invalid
$current_portal = $portal_details[$portal] ?? $portal_details['student'];
$page_title = $current_portal['title'];
include 'includes/header.php';
?>

<!-- The <main> tag is opened in header.php -->
<div class="flex items-center justify-center py-12">
    <div class="w-full max-w-md p-8 space-y-6 bg-white rounded-lg shadow-xl">
        <div class="text-center">
             <div class="w-16 h-16 mx-auto bg-<?= $current_portal['color'] ?>-500 text-white rounded-full flex items-center justify-center mb-4">
                <i class="ri-user-3-line text-4xl"></i>
            </div>
            <h2 class="text-3xl font-bold text-gray-900"><?= htmlspecialchars($current_portal['title']) ?></h2>
            <p class="text-gray-600">Please sign in to continue</p>
        </div>
        
        <!-- The form action should point to a real authentication script -->
        <form action="#" method="POST" class="space-y-6">
            <div>
                <label for="username" class="block font-medium text-sm text-gray-700">Username</label>
                <input type="text" name="username" class="w-full mt-1 p-3 border rounded-lg focus:ring-2 focus:ring-<?= $current_portal['color'] ?>-500" required>
            </div>
            <div>
                <label for="password" class="block font-medium text-sm text-gray-700">Password</label>
                <input type="password" name="password" class="w-full mt-1 p-3 border rounded-lg focus:ring-2 focus:ring-<?= $current_portal['color'] ?>-500" required>
            </div>
            <button type="submit" class="w-full bg-<?= $current_portal['color'] ?>-600 text-white py-3 rounded-lg font-semibold hover:bg-<?= $current_portal['color'] ?>-700 transition-colors">
                Login
            </button>
        </form>
    </div>
</div>

<?php
include 'includes/footer.php';
?>