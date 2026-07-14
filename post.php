<?php
// FILE: /ss/post.php
// This file displays a single news/blog post.

// --- CORE SETUP ---
// We need to fetch the post content BEFORE the header to set the page title correctly.
require_once 'config/db.php';
require_once 'includes/functions.php';

// Check if a 'slug' is provided in the URL, otherwise, show an error.
if (!isset($_GET['slug'])) {
    http_response_code(404);
    $page_title = "Error 404";
    include 'includes/header.php';
    echo '<div class="text-center py-20"><h2 class="text-2xl font-bold">Post Not Found</h2><p>No post was specified.</p></div>';
    include 'includes/footer.php';
    exit(); // Stop the script
}

$slug = $_GET['slug'];

// Prepare and execute a query to fetch the post content safely.
$stmt = $conn->prepare("SELECT title, content, author, created_at FROM posts WHERE slug = ?");
$stmt->bind_param("s", $slug);
$stmt->execute();
$result = $stmt->get_result();
$post = $result->fetch_assoc();
$stmt->close();

// If no post is found with that slug, show a 404 error.
if (!$post) {
    http_response_code(404);
    $page_title = "Error 404";
    include 'includes/header.php';
    echo '<div class="text-center py-20"><h2 class="text-2xl font-bold">Post Not Found</h2><p>The news post you are looking for does not exist.</p><a href="/" class="text-blue-600 hover:underline mt-4 inline-block">← Go to Homepage</a></div>';
    include 'includes/footer.php';
    exit();
}

// Set the page title for the header.
$page_title = htmlspecialchars($post['title']);
include 'includes/header.php';
?>

<!-- The <main> tag is opened in header.php -->

<div class="bg-white p-8 md:p-12 rounded-lg shadow-md">
    
    <!-- Post Header -->
    <div class="border-b pb-4 mb-6">
        <h1 class="text-4xl font-bold text-gray-900"><?= htmlspecialchars($post['title']) ?></h1>
        <div class="text-sm text-gray-500 mt-2">
            <span>Posted on <?= date('F j, Y', strtotime($post['created_at'])) ?></span>
            <?php if (!empty($post['author'])): ?>
                <span>by <?= htmlspecialchars($post['author']) ?></span>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Post Content -->
    <!-- Add "prose" for beautiful default styling of the HTML content from your admin panel -->
    <article class="prose lg:prose-xl max-w-none">
        <?php
            // The content is saved as HTML, so we output it directly.
            echo $post['content'];
        ?>
    </article>

    <!-- Back Button -->
    <div class="mt-12 pt-6 border-t">
        <a href="/" class="text-blue-600 hover:underline">← Back to News & Announcements</a>
    </div>
</div>

<?php
// The footer file closes the <main> tag and the rest of the document.
include 'includes/footer.php'; 
?>