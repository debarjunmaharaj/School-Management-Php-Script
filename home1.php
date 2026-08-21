<?php
// FILE: /home1.php (Original Home Page)

// This variable tells the header to render the homepage banner.
$is_homepage = true;
$page_title = "Homepage"; // Set a title for the header
include 'includes/header.php'; // Includes DB connection, settings, and header HTML

// --- HOMEPAGE SPECIFIC DATA --- //
$posts_result = mysqli_query($conn, "SELECT * FROM posts ORDER BY created_at DESC LIMIT 3");
$events_result = mysqli_query($conn, "SELECT * FROM events WHERE event_date >= CURDATE() ORDER BY event_date ASC LIMIT 3");
$featured_teachers_result = mysqli_query($conn, "SELECT * FROM teachers WHERE is_featured = 1 ORDER BY id ASC LIMIT 3");
$downloads_result = mysqli_query($conn, "SELECT * FROM downloads WHERE file_type = 'pdf' ORDER BY id DESC LIMIT 3");
$videos_result = mysqli_query($conn, "SELECT * FROM videos ORDER BY id DESC LIMIT 4");
?>

<style>
    /* Playful Animations for Kids & Elements */
    @keyframes kidFloat {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-12px) rotate(3deg); }
    }
    @keyframes balloonSway {
        0%, 100% { transform: translateY(0) rotate(-4deg); }
        50% { transform: translateY(-18px) rotate(4deg); }
    }
    @keyframes rocketSwoop {
        0% { transform: translate(0, 0) rotate(0deg); }
        50% { transform: translate(15px, -15px) rotate(5deg); }
        100% { transform: translate(0, 0) rotate(0deg); }
    }
    @keyframes sunSpinSlow {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    @keyframes starTwinkle {
        0%, 100% { transform: scale(1); opacity: 0.8; }
        50% { transform: scale(1.3); opacity: 1; filter: drop-shadow(0 0 6px rgba(250,204,21,0.8)); }
    }
    @keyframes pencilWiggle {
        0%, 100% { transform: rotate(0deg); }
        25% { transform: rotate(-10deg); }
        75% { transform: rotate(10deg); }
    }

    .anim-float { animation: kidFloat 4s ease-in-out infinite; }
    .anim-balloon { animation: balloonSway 5s ease-in-out infinite; }
    .anim-rocket { animation: rocketSwoop 6s ease-in-out infinite; }
    .anim-sun { animation: sunSpinSlow 20s linear infinite; }
    .anim-star { animation: starTwinkle 2.5s ease-in-out infinite; }
    .anim-pencil { animation: pencilWiggle 3s ease-in-out infinite; }

    .hover-pop {
        transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.3s ease;
    }
    .hover-pop:hover {
        transform: translateY(-6px) scale(1.02);
    }
</style>

<div class="mt-[-4rem] relative z-10"> <!-- Pulls content up over the banner -->
    
    <!-- Floating Playful Kids Stickers / Elements in Hero -->
    <div class="relative max-w-7xl mx-auto pointer-events-none">
        <!-- Floating Sun -->
        <div class="absolute -top-12 right-4 md:right-16 z-20 anim-float hidden sm:block">
            <div class="w-16 h-16 md:w-20 md:h-20 bg-gradient-to-tr from-amber-400 to-yellow-300 rounded-full shadow-lg flex items-center justify-center border-2 border-white/60">
                <span class="text-3xl md:text-4xl anim-sun">☀️</span>
            </div>
        </div>
        <!-- Floating Rocket / Plane -->
        <div class="absolute -top-6 left-6 z-20 anim-rocket hidden sm:block">
            <div class="bg-white/90 backdrop-blur-md px-3 py-1.5 rounded-full shadow-md border border-sky-200 flex items-center gap-1.5 text-xs font-bold text-sky-700">
                <span class="text-xl">🚀</span> <span>Learning Fun!</span>
            </div>
        </div>
    </div>

    <div class="flex flex-col lg:flex-row gap-6">
        <div class="flex-1 space-y-8">
            
            <!-- Portal Links (Animated) -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4" data-aos="fade-up" data-aos-delay="100">
                <a href="login.php?portal=student" class="bg-gradient-to-r from-blue-500 to-blue-600 text-white p-6 rounded-2xl shadow-lg hover-pop block relative overflow-hidden group">
                    <div class="absolute -right-4 -bottom-4 text-white/15 text-6xl group-hover:scale-125 transition-transform duration-300">
                        <i class="ri-user-smile-line"></i>
                    </div>
                    <div class="flex items-center gap-3 mb-2">
                        <span class="text-2xl anim-pencil">🎒</span>
                        <h4 class="text-lg font-bold">Student Portal</h4>
                    </div>
                    <p class="text-xs opacity-90">Grades, Fun & Assignments</p>
                </a>

                <a href="login.php?portal=teacher" class="bg-gradient-to-r from-green-500 to-green-600 text-white p-6 rounded-2xl shadow-lg hover-pop block relative overflow-hidden group">
                    <div class="absolute -right-4 -bottom-4 text-white/15 text-6xl group-hover:scale-125 transition-transform duration-300">
                        <i class="ri-chalkboard-line"></i>
                    </div>
                    <div class="flex items-center gap-3 mb-2">
                        <span class="text-2xl anim-float">📚</span>
                        <h4 class="text-lg font-bold">Teacher Portal</h4>
                    </div>
                    <p class="text-xs opacity-90">Manage Classes & Notes</p>
                </a>

                <a href="login.php?portal=parent" class="bg-gradient-to-r from-purple-500 to-purple-600 text-white p-6 rounded-2xl shadow-lg hover-pop block relative overflow-hidden group">
                    <div class="absolute -right-4 -bottom-4 text-white/15 text-6xl group-hover:scale-125 transition-transform duration-300">
                        <i class="ri-parent-line"></i>
                    </div>
                    <div class="flex items-center gap-3 mb-2">
                        <span class="text-2xl anim-balloon">🎈</span>
                        <h4 class="text-lg font-bold">Parent Portal</h4>
                    </div>
                    <p class="text-xs opacity-90">Track Student Progress</p>
                </a>

                <a href="library.php" class="bg-gradient-to-r from-orange-500 to-orange-600 text-white p-6 rounded-2xl shadow-lg hover-pop block relative overflow-hidden group">
                    <div class="absolute -right-4 -bottom-4 text-white/15 text-6xl group-hover:scale-125 transition-transform duration-300">
                        <i class="ri-book-open-line"></i>
                    </div>
                    <div class="flex items-center gap-3 mb-2">
                        <span class="text-2xl anim-star">⭐</span>
                        <h4 class="text-lg font-bold">Kids Library</h4>
                    </div>
                    <p class="text-xs opacity-90">Digital Books & Photos</p>
                </a>
            </div>

            <!-- News & Events Section (Animated) -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Latest News -->
                <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100" data-aos="fade-right" data-aos-delay="200">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                            <span class="text-blue-600"><i class="ri-notification-3-line"></i></span> Latest News
                        </h3>
                        <span class="text-xs font-bold text-blue-600 bg-blue-50 px-2.5 py-1 rounded-full">School Feed</span>
                    </div>
                    <div class="space-y-4">
                        <?php while($post = mysqli_fetch_assoc($posts_result)): ?>
                        <div class="border-l-4 border-blue-500 pl-4 hover:bg-slate-50 p-2 rounded-r-lg transition">
                            <h4 class="font-medium text-gray-800"><?= htmlspecialchars($post['title']) ?></h4>
                            <p class="text-xs text-gray-500 mt-1">Posted: <?= date('F j, Y', strtotime($post['created_at'])) ?></p>
                            <a href="post/<?= htmlspecialchars($post['slug']) ?>" class="text-sm text-blue-600 hover:underline font-semibold mt-1 inline-block">Read More →</a>
                        </div>
                        <?php endwhile; ?>
                    </div>
                </div>

                <!-- Upcoming Events -->
                <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100" data-aos="fade-left" data-aos-delay="200">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                            <span class="text-amber-500"><i class="ri-calendar-event-line"></i></span> Upcoming Events
                        </h3>
                        <span class="text-xs font-bold text-amber-600 bg-amber-50 px-2.5 py-1 rounded-full">Calendar</span>
                    </div>
                    <div class="space-y-4">
                         <?php if (mysqli_num_rows($events_result) > 0): ?>
                            <?php while($event = mysqli_fetch_assoc($events_result)): ?>
                            <div class="flex items-start space-x-4 hover:bg-slate-50 p-2 rounded-xl transition">
                                <div class="bg-gradient-to-br from-blue-700 to-blue-900 text-white rounded-xl p-3 text-center min-w-[64px] shadow-sm">
                                    <div class="text-xs font-bold uppercase"><?= strtoupper(date('M', strtotime($event['event_date']))) ?></div>
                                    <div class="text-2xl font-black"><?= date('d', strtotime($event['event_date'])) ?></div>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-800"><?= htmlspecialchars($event['title']) ?></h4>
                                    <p class="text-sm text-gray-600 mt-0.5"><i class="ri-map-pin-line align-middle text-blue-500"></i> <?= htmlspecialchars($event['location']) ?></p>
                                    <p class="text-xs text-gray-500 mt-0.5"><i class="ri-time-line align-middle text-blue-500"></i> <?= date('g:i A', strtotime($event['event_date'])) ?></p>
                                </div>
                            </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <p class="text-gray-500">No upcoming events found.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Principal's Message (Animated) -->
            <section class="bg-white rounded-2xl shadow-sm p-8 flex flex-col md:flex-row items-center gap-8 border border-gray-100 relative overflow-hidden" data-aos="zoom-in">
                <div class="absolute -right-8 -top-8 w-24 h-24 bg-blue-50 rounded-full blur-2xl pointer-events-none"></div>
                <img src="<?= htmlspecialchars($settings['principal_photo_url']) ?>" alt="Principal Photo" class="w-32 h-32 md:w-44 md:h-44 rounded-full object-cover flex-shrink-0 border-4 border-blue-200 shadow-md">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-blue-600 bg-blue-50 px-3 py-1 rounded-full inline-block mb-2">Message from Headmaster</span>
                    <h2 class="text-2xl font-bold text-gray-800"><?= htmlspecialchars($settings['principal_message_title']) ?></h2>
                    <p class="text-gray-600 mt-3 leading-relaxed">"<?= htmlspecialchars($settings['principal_message_content']) ?>"</p>
                    <p class="text-right mt-4 font-bold text-blue-900">— <?= htmlspecialchars($settings['principal_message_name']) ?></p>
                </div>
            </section>

            <!-- Featured Teachers (Animated) -->
            <section class="bg-white rounded-2xl shadow-sm p-8 border border-gray-100" data-aos="fade-up">
                <div class="text-center mb-8">
                    <span class="text-xs font-bold uppercase tracking-wider text-emerald-600 bg-emerald-50 px-3 py-1 rounded-full">Dedicated Faculty</span>
                    <h2 class="text-2xl font-extrabold text-gray-800 mt-2">Meet Our Inspiring Teachers</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <?php while($teacher = mysqli_fetch_assoc($featured_teachers_result)): ?>
                    <div class="text-center p-4 rounded-2xl bg-gray-50 border border-gray-100 hover-pop transition">
                        <img src="<?= htmlspecialchars($teacher['image_url']) ?>" alt="Teacher" class="w-28 h-28 rounded-full object-cover mx-auto mb-4 border-4 border-white shadow-md">
                        <h4 class="font-bold text-lg text-gray-900"><?= htmlspecialchars($teacher['name']) ?></h4>
                        <p class="text-sm text-blue-700 font-semibold mt-0.5"><?= htmlspecialchars($teacher['subject']) ?></p>
                        <p class="text-xs text-gray-500 mt-1"><?= htmlspecialchars($teacher['experience']) ?></p>
                    </div>
                    <?php endwhile; ?>
                </div>
            </section>

            <!-- Latest News & Blog Posts Grid Section (Animated) -->
            <section class="bg-white rounded-2xl shadow-sm p-8 border border-gray-100" data-aos="fade-up">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 pb-4 border-b border-gray-100">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-indigo-600 bg-indigo-50 px-3 py-1 rounded-full">School Bulletin</span>
                        <h2 class="text-2xl font-bold text-gray-800 mt-1">Latest News & Blog Posts</h2>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <?php 
                    $posts_grid_res = mysqli_query($conn, "SELECT * FROM posts ORDER BY created_at DESC LIMIT 3");
                    if ($posts_grid_res && mysqli_num_rows($posts_grid_res) > 0): 
                        while($p = mysqli_fetch_assoc($posts_grid_res)): 
                    ?>
                        <article class="bg-gray-50 rounded-2xl overflow-hidden border border-gray-200 flex flex-col justify-between hover-pop transition group">
                            <div>
                                <div class="h-44 bg-blue-50 overflow-hidden relative">
                                    <?php if (!empty($p['image_url'])): ?>
                                        <img src="<?= htmlspecialchars($p['image_url']) ?>" alt="<?= htmlspecialchars($p['title']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                    <?php else: ?>
                                        <div class="w-full h-full flex items-center justify-center text-blue-300 text-5xl">
                                            <i class="ri-newspaper-line"></i>
                                        </div>
                                    <?php endif; ?>
                                    <span class="absolute top-3 left-3 bg-blue-800/90 backdrop-blur-sm text-white text-[11px] font-bold px-2.5 py-1 rounded-full shadow-sm">
                                        <?= date('M d, Y', strtotime($p['created_at'])) ?>
                                    </span>
                                </div>
                                <div class="p-5">
                                    <h3 class="font-bold text-gray-900 text-base leading-snug line-clamp-2 group-hover:text-blue-700 transition-colors">
                                        <a href="post/<?= htmlspecialchars($p['slug']) ?>">
                                            <?= htmlspecialchars($p['title']) ?>
                                        </a>
                                    </h3>
                                    <p class="text-xs text-gray-600 mt-2 line-clamp-3 leading-relaxed">
                                        <?= htmlspecialchars(mb_substr(strip_tags($p['content']), 0, 110)) ?>...
                                    </p>
                                </div>
                            </div>
                            <div class="px-5 pb-5 pt-2">
                                <a href="post/<?= htmlspecialchars($p['slug']) ?>" class="text-xs font-bold text-blue-600 hover:text-blue-800 inline-flex items-center gap-1">
                                    Read Article <i class="ri-arrow-right-line"></i>
                                </a>
                            </div>
                        </article>
                    <?php 
                        endwhile; 
                    else: 
                    ?>
                        <p class="text-gray-500 col-span-full text-center py-6">No news posts available at the moment.</p>
                    <?php endif; ?>
                </div>
            </section>

            <!-- Minister's Message (Testimonial) & Quick Downloads (Animated) -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Minister's Message -->
                <div class="bg-gradient-to-br from-blue-800 to-indigo-900 text-white rounded-2xl shadow-sm p-8" data-aos="fade-right">
                     <h2 class="text-2xl font-bold mb-4"><?= htmlspecialchars($settings['minister_message_title']) ?></h2>
                     <div class="flex items-center gap-4">
                        <img src="<?= htmlspecialchars($settings['minister_photo_url']) ?>" alt="Minister Photo" class="w-24 h-24 rounded-full object-cover flex-shrink-0 border-4 border-blue-400/50 shadow-md">
                        <p class="italic text-sm leading-relaxed">"<?= htmlspecialchars($settings['minister_message_content']) ?>"</p>
                     </div>
                     <p class="text-right mt-4 font-bold text-sky-200">— <?= htmlspecialchars($settings['minister_message_name']) ?></p>
                </div>
                <!-- Quick Downloads -->
                <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100" data-aos="fade-left">
                    <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                        <span class="text-blue-600"><i class="ri-download-cloud-2-line"></i></span> Quick Downloads
                    </h3>
                    <div class="space-y-3">
                        <?php while($download = mysqli_fetch_assoc($downloads_result)): ?>
                        <a href="<?= htmlspecialchars($download['file_path']) ?>" download class="flex items-center justify-between p-3.5 bg-gray-50 rounded-xl hover:bg-blue-50 transition border border-gray-100 group">
                            <div class="font-medium text-gray-700 text-sm group-hover:text-blue-700"><?= htmlspecialchars($download['title']) ?></div>
                            <i class="ri-download-2-line text-blue-600 group-hover:scale-110 transition-transform"></i>
                        </a>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>

             <!-- Video Gallery (Animated) -->
            <section class="bg-gray-900 text-white rounded-2xl p-6 shadow-md" data-aos="fade-up">
                <h3 class="text-xl font-semibold mb-4 flex items-center gap-2">
                    <span class="text-red-500"><i class="ri-video-line"></i></span> Video Gallery
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <?php while($video = mysqli_fetch_assoc($videos_result)): ?>
                    <a href="https://www.youtube.com/watch?v=<?= htmlspecialchars($video['youtube_video_id']) ?>" target="_blank" class="relative group block rounded-xl overflow-hidden">
                        <img src="https://img.youtube.com/vi/<?= htmlspecialchars($video['youtube_video_id']) ?>/mqdefault.jpg" alt="Video Thumbnail" class="w-full h-32 object-cover rounded-xl group-hover:scale-105 transition-transform duration-300">
                        <div class="absolute inset-0 bg-black/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity"><i class="ri-play-circle-line text-white text-5xl"></i></div>
                        <h4 class="text-xs font-medium mt-2 text-slate-300 group-hover:text-white line-clamp-1"><?= htmlspecialchars($video['title']) ?></h4>
                    </a>
                    <?php endwhile; ?>
                </div>
            </section>
        </div>

        <!-- DYNAMIC SIDEBAR -->
        <div data-aos="fade-left">
            <?php include 'includes/sidebar.php'; ?>
        </div>
    </div>
</div>

<?php
include 'includes/footer.php';
?>
