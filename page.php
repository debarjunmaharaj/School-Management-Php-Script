<?php
// This file is responsible for displaying the content of a single dynamic page.

// The header file now handles the database connection and settings.
// We just need to define the $page_title before including it.

include 'includes/header.php'; // This will include the database connection and start the HTML document.

// --- PAGE-SPECIFIC LOGIC ---

// Check if a 'slug' is provided in the URL, otherwise, show an error.
if (!isset($_GET['slug'])) {
    http_response_code(404);
    $page_title = "Error 404";
    echo '<div class="text-center py-20"><h2 class="text-2xl font-bold">Page Not Found</h2><p>No page was specified.</p></div>';
    include 'includes/footer.php';
    exit(); // Stop the script
}

$slug = $_GET['slug'];

// Prepare and execute a query to fetch the page content safely.
$stmt = $conn->prepare("SELECT title, content FROM pages WHERE slug = ?");
$stmt->bind_param("s", $slug);
$stmt->execute();
$result = $stmt->get_result();
$page = $result->fetch_assoc();
$stmt->close();

// If no page is found with that slug, show a 404 error.
if (!$page) {
    http_response_code(404);
    $page_title = "Error 404"; // Update the page title for the error page.
    // We need to re-include the header to show the correct title, or just output the error message.
    echo '<div class="text-center py-20"><h2 class="text-2xl font-bold">Page Not Found</h2><p>The page you are looking for does not exist.</p><a href="/" class="text-blue-600 hover:underline mt-4 inline-block">← Go to Homepage</a></div>';
    include 'includes/footer.php';
    exit();
}

// Set the page title for the header, which has already been included.
// This is a bit of a workaround since the header is included first.
// A more advanced (framework-based) approach would handle this differently, but this is effective.
echo "<script>document.title = '" . htmlspecialchars($page['title']) . " - " . htmlspecialchars($settings['school_name']) . "';</script>";
echo "<script>document.querySelector('.subpage-header h1').textContent = '" . htmlspecialchars($page['title']) . "';</script>";

?>

<!-- The <main> tag is opened in header.php -->

<div class="bg-white p-8 md:p-12 rounded-lg shadow-md">
    
    <!-- Add a "prose" class from Tailwind's typography plugin for beautiful default styling of HTML content -->
    <article class="prose lg:prose-xl max-w-none">
        <?php
            // The content is saved as HTML from the admin panel, so we output it directly.
            // Be aware of XSS risks if a non-admin user could ever edit this content.
            echo $page['content'];
        ?>
    </article>

    <div class="mt-12 pt-6 border-t">
        <a href="/" class="text-blue-600 hover:underline">← Back to Homepage</a>
    </div>
</div>

<?php
// The footer file closes the <main> tag and the rest of the document.
include 'includes/footer.php'; 
?>