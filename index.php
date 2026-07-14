<?php
// FILE: /ss/index.php (THE COMPLETE, FEATURE-RICH HOMEPAGE)

// This variable tells the header to render the homepage banner.
$is_homepage = true;
$page_title = "Homepage"; // Set a title for the header
include 'includes/header.php'; // Includes DB connection, settings, and header HTML

// --- HOMEPAGE SPECIFIC DATA --- //
$posts_result = mysqli_query($conn, "SELECT * FROM posts ORDER BY created_at DESC LIMIT 3");
$events_result = mysqli_query($conn, "SELECT * FROM events WHERE event_date >= CURDATE() ORDER BY event_date ASC LIMIT 3");
$featured_teachers_result = mysqli_query($conn, "SELECT * FROM teachers WHERE is_featured = 1 ORDER BY id ASC LIMIT 3");
$downloads_result = mysqli_query($conn, "SELECT * FROM downloads ORDER BY id DESC LIMIT 3");
$videos_result = mysqli_query($conn, "SELECT * FROM videos ORDER BY id DESC LIMIT 4");
?>

<!-- The <main> tag is opened in header.php -->

<div class="mt-[-4rem] relative z-10"> <!-- Pulls content up over the banner -->
    <div class="flex flex-col lg:flex-row gap-6">
        <div class="flex-1 space-y-8">
            <!-- Portal Links -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <a href="login.php?portal=student" class="bg-gradient-to-r from-blue-500 to-blue-600 text-white p-6 rounded-lg shadow-lg hover:scale-105 transition-transform block"><h4 class="text-lg font-semibold">Student Portal</h4><p class="text-sm opacity-90">Grades & Assignments</p></a>
                <a href="login.php?portal=teacher" class="bg-gradient-to-r from-green-500 to-green-600 text-white p-6 rounded-lg shadow-lg hover:scale-105 transition-transform block"><h4 class="text-lg font-semibold">Teacher Portal</h4><p class="text-sm opacity-90">Manage Classes</p></a>
                <a href="login.php?portal=parent" class="bg-gradient-to-r from-purple-500 to-purple-600 text-white p-6 rounded-lg shadow-lg hover:scale-105 transition-transform block"><h4 class="text-lg font-semibold">Parent Portal</h4><p class="text-sm opacity-90">Track Progress</p></a>
                <a href="library.php" class="bg-gradient-to-r from-orange-500 to-orange-600 text-white p-6 rounded-lg shadow-lg hover:scale-105 transition-transform block"><h4 class="text-lg font-semibold">Library</h4><p class="text-sm opacity-90">Digital Resources</p></a>
            </div>

            <!-- News & Events Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Latest News -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Latest News</h3>
                    <div class="space-y-4">
                        <?php while($post = mysqli_fetch_assoc($posts_result)): ?>
                        <div class="border-l-4 border-blue-500 pl-4">
                            <h4 class="font-medium text-gray-800"><?= htmlspecialchars($post['title']) ?></h4>
                            <p class="text-xs text-gray-500">Posted: <?= date('F j, Y', strtotime($post['created_at'])) ?></p>
                            <a href="post.php?slug=<?= htmlspecialchars($post['slug']) ?>" class="text-sm text-blue-600 hover:underline">Read More →</a>
                        </div>
                        <?php endwhile; ?>
                    </div>
                </div>
                <!-- Upcoming Events (FIXED) -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Upcoming Events</h3>
                    <div class="space-y-4">
                         <?php if (mysqli_num_rows($events_result) > 0): ?>
                            <?php while($event = mysqli_fetch_assoc($events_result)): ?>
                            <div class="flex items-start space-x-4">
                                <div class="bg-blue-800 text-white rounded-lg p-3 text-center min-w-[64px]">
                                    <div class="text-xs font-bold uppercase"><?= strtoupper(date('M', strtotime($event['event_date']))) ?></div>
                                    <div class="text-2xl font-bold"><?= date('d', strtotime($event['event_date'])) ?></div>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-800"><?= htmlspecialchars($event['title']) ?></h4>
                                    <p class="text-sm text-gray-600"><i class="ri-map-pin-line align-middle"></i> <?= htmlspecialchars($event['location']) ?></p>
                                    <p class="text-sm text-gray-600"><i class="ri-time-line align-middle"></i> <?= date('g:i A', strtotime($event['event_date'])) ?></p>
                                </div>
                            </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <p class="text-gray-500">No upcoming events found.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Principal's Message -->
            <section class="bg-white rounded-lg shadow-sm p-8 flex flex-col md:flex-row items-center gap-8">
                <img src="<?= htmlspecialchars($settings['principal_photo_url']) ?>" alt="Principal Photo" class="w-32 h-32 md:w-48 md:h-48 rounded-full object-cover flex-shrink-0 border-4 border-blue-200">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800"><?= htmlspecialchars($settings['principal_message_title']) ?></h2>
                    <p class="text-gray-600 mt-4 leading-relaxed">"<?= htmlspecialchars($settings['principal_message_content']) ?>"</p>
                    <p class="text-right mt-4 font-bold text-gray-700">— <?= htmlspecialchars($settings['principal_message_name']) ?></p>
                </div>
            </section>

            <!-- Featured Teachers -->
            <section class="bg-white rounded-lg shadow-sm p-8">
                <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Meet Our Teachers</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <?php while($teacher = mysqli_fetch_assoc($featured_teachers_result)): ?>
                    <div class="text-center">
                        <img src="<?= htmlspecialchars($teacher['image_url']) ?>" alt="Teacher" class="w-32 h-32 rounded-full object-cover mx-auto mb-4 border-4 border-gray-200">
                        <h4 class="font-bold text-lg text-gray-900"><?= htmlspecialchars($teacher['name']) ?></h4>
                        <p class="text-sm text-blue-700 font-semibold"><?= htmlspecialchars($teacher['subject']) ?></p>
                        <p class="text-xs text-gray-500 mt-1"><?= htmlspecialchars($teacher['experience']) ?></p>
                    </div>
                    <?php endwhile; ?>
                </div>
            </section>

            <!-- Minister's Message (Testimonial) & Quick Downloads -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Minister's Message -->
                <div class="bg-blue-800 text-white rounded-lg shadow-sm p-8">
                     <h2 class="text-2xl font-bold mb-4"><?= htmlspecialchars($settings['minister_message_title']) ?></h2>
                     <div class="flex items-center gap-4">
                        <img src="<?= htmlspecialchars($settings['minister_photo_url']) ?>" alt="Minister Photo" class="w-24 h-24 rounded-full object-cover flex-shrink-0 border-4 border-blue-400">
                        <p class="italic">"<?= htmlspecialchars($settings['minister_message_content']) ?>"</p>
                     </div>
                     <p class="text-right mt-4 font-bold opacity-80">— <?= htmlspecialchars($settings['minister_message_name']) ?></p>
                </div>
                <!-- Quick Downloads -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Quick Downloads</h3>
                    <div class="space-y-3">
                        <?php while($download = mysqli_fetch_assoc($downloads_result)): ?>
                        <a href="<?= htmlspecialchars($download['file_path']) ?>" download class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <div class="font-medium text-gray-700"><?= htmlspecialchars($download['title']) ?></div>
                            <i class="ri-download-2-line text-blue-600"></i>
                        </a>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>

             <!-- Video Gallery -->
            <section class="bg-gray-900 text-white rounded-lg p-6">
                <h3 class="text-xl font-semibold mb-4">Video Gallery</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <?php while($video = mysqli_fetch_assoc($videos_result)): ?>
                    <a href="https://www.youtube.com/watch?v=<?= htmlspecialchars($video['youtube_video_id']) ?>" target="_blank" class="relative group block">
                        <img src="https://img.youtube.com/vi/<?= htmlspecialchars($video['youtube_video_id']) ?>/mqdefault.jpg" alt="Video Thumbnail" class="w-full h-32 object-cover rounded">
                        <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity rounded"><i class="ri-play-circle-line text-white text-5xl"></i></div>
                        <h4 class="text-sm font-medium mt-2"><?= htmlspecialchars($video['title']) ?></h4>
                    </a>
                    <?php endwhile; ?>
                </div>
            </section>
        </div>

        <!-- DYNAMIC SIDEBAR -->
        <?php include 'includes/sidebar.php'; ?>
    </div>
</div>

<?php
include 'includes/footer.php';
?>