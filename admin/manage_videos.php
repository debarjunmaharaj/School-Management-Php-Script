<?php
$page_title = 'Manage Video Gallery';
include '../includes/admin_header.php'; // Includes session check and database connection

$message = '';

// Handle form submissions (Add, Edit, Delete)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // --- DELETE VIDEO ---
    if (isset($_POST['delete'])) {
        $id = $_POST['id'];
        $stmt = $conn->prepare("DELETE FROM videos WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $message = '
            <div class="p-4 bg-emerald-50 border border-emerald-100 rounded-xl flex items-start gap-3 text-emerald-800 text-sm mb-6 shadow-sm">
                <i class="ri-checkbox-circle-line text-lg text-emerald-600 shrink-0"></i>
                <div>
                    <p class="font-semibold">Video Deleted</p>
                    <p class="text-emerald-600/90 text-xs mt-0.5">The video record has been removed from the directory.</p>
                </div>
            </div>';
        } else {
            $message = '
            <div class="p-4 bg-rose-50 border border-rose-100 rounded-xl flex items-start gap-3 text-rose-800 text-sm mb-6 shadow-sm">
                <i class="ri-error-warning-line text-lg text-rose-600 shrink-0"></i>
                <div>
                    <p class="font-semibold">Error Deleting Video</p>
                    <p class="text-rose-600/90 text-xs mt-0.5">Please check system connection configurations and try again.</p>
                </div>
            </div>';
        }
        $stmt->close();
    }
    // --- ADD or UPDATE VIDEO ---
    else {
        $id = $_POST['id'];
        $title = $_POST['title'];
        $youtube_video_id = $_POST['youtube_video_id'];

        // Basic validation to ensure fields are not empty
        if (empty($title) || empty($youtube_video_id)) {
             $message = '
             <div class="p-4 bg-rose-50 border border-rose-100 rounded-xl flex items-start gap-3 text-rose-800 text-sm mb-6 shadow-sm">
                 <i class="ri-error-warning-line text-lg text-rose-600 shrink-0"></i>
                 <div>
                     <p class="font-semibold">Missing Properties</p>
                     <p class="text-rose-600/90 text-xs mt-0.5">Both Video Title and YouTube Video ID parameter codes are required.</p>
                 </div>
             </div>';
        } else {
            if (empty($id)) {
                // Add new video
                $stmt = $conn->prepare("INSERT INTO videos (title, youtube_video_id) VALUES (?, ?)");
                $stmt->bind_param("ss", $title, $youtube_video_id);
                $success_msg = "Video added successfully.";
            } else {
                // Update existing video
                $stmt = $conn->prepare("UPDATE videos SET title = ?, youtube_video_id = ? WHERE id = ?");
                $stmt->bind_param("ssi", $title, $youtube_video_id, $id);
                $success_msg = "Video updated successfully.";
            }

            if ($stmt->execute()) {
                $message = '
                <div class="p-4 bg-emerald-50 border border-emerald-100 rounded-xl flex items-start gap-3 text-emerald-800 text-sm mb-6 shadow-sm">
                    <i class="ri-checkbox-circle-line text-lg text-emerald-600 shrink-0"></i>
                    <div>
                        <p class="font-semibold">Video Archive Updated</p>
                        <p class="text-emerald-600/90 text-xs mt-0.5">' . htmlspecialchars($success_msg) . '</p>
                    </div>
                </div>';
            } else {
                $message = '
                <div class="p-4 bg-rose-50 border border-rose-100 rounded-xl flex items-start gap-3 text-rose-800 text-sm mb-6 shadow-sm">
                    <i class="ri-error-warning-line text-lg text-rose-600 shrink-0"></i>
                    <div>
                        <p class="font-semibold">Database Error</p>
                        <p class="text-rose-600/90 text-xs mt-0.5">Could not save video parameter values into SQL registers.</p>
                    </div>
                </div>';
            }
            $stmt->close();
        }
    }
}

// Fetch a single video for editing if an ID is provided in the URL
$edit_video = null;
if (isset($_GET['edit'])) {
    $id_to_edit = $_GET['edit'];
    $stmt = $conn->prepare("SELECT * FROM videos WHERE id = ?");
    $stmt->bind_param("i", $id_to_edit);
    $stmt->execute();
    $result = $stmt->get_result();
    $edit_video = $result->fetch_assoc();
    $stmt->close();
}

// Fetch all videos to display in the list
$videos_result = $conn->query("SELECT * FROM videos ORDER BY id DESC");
?>

<div class="max-w-6xl mx-auto space-y-8 py-4">

    <!-- Display feedback message -->
    <?= $message ?>

    <!-- Add/Edit Form -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 lg:p-8 relative">
        <div class="absolute top-0 left-0 right-0 h-1 bg-indigo-600 rounded-t-2xl"></div>
        
        <div class="mb-6">
            <h3 class="text-lg font-bold text-slate-900"><?= $edit_video ? 'Edit Video Details' : 'Add New Video to Gallery' ?></h3>
            <p class="text-xs text-slate-500 mt-1">Embed lectures, campus tours, and dynamic tutorial videos from YouTube</p>
        </div>

        <form action="manage_videos.php" method="POST" class="space-y-6">
            <input type="hidden" name="id" value="<?= $edit_video['id'] ?? '' ?>">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Video Title -->
                <div class="space-y-1.5">
                    <label for="title" class="text-xs font-semibold text-slate-600 uppercase tracking-wider">Video Title</label>
                    <input 
                        type="text" 
                        name="title" 
                        id="title" 
                        value="<?= htmlspecialchars($edit_video['title'] ?? '') ?>" 
                        placeholder="e.g. Science Fair Highlights"
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:bg-white text-sm transition font-medium" 
                        required
                    >
                </div>
                
                <!-- YouTube Video ID -->
                <div class="space-y-1.5">
                    <label for="youtube_video_id" class="text-xs font-semibold text-slate-600 uppercase tracking-wider">YouTube Video ID</label>
                    <input 
                        type="text" 
                        name="youtube_video_id" 
                        id="youtube_video_id" 
                        value="<?= htmlspecialchars($edit_video['youtube_video_id'] ?? '') ?>" 
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:bg-white text-sm transition" 
                        placeholder="e.g. dQw4w9WgXcQ" 
                        required
                    >
                </div>
            </div>
            
            <!-- Context Callout Information -->
            <div class="p-4 bg-slate-50 border border-slate-150 rounded-xl flex items-start gap-3 text-slate-600 text-xs leading-relaxed">
                <i class="ri-information-line text-slate-500 text-lg shrink-0"></i>
                <div>
                    <span class="font-bold text-slate-700">Identifying YouTube IDs:</span>
                    <p class="text-slate-500/90 mt-0.5">The ID is the unique string of letters, numbers, and symbols found at the end of the video URL. For example, in <code class="bg-indigo-50 text-indigo-700 font-semibold px-1.5 py-0.5 rounded font-mono">youtube.com/watch?v=dQw4w9WgXcQ</code>, the video ID is <strong class="text-indigo-600 font-bold">dQw4w9WgXcQ</strong>.</p>
                </div>
            </div>
            
            <div class="flex items-center gap-2 pt-2">
                <button 
                    type="submit" 
                    class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold py-2.5 px-6 rounded-xl transition shadow-sm active:scale-[0.98]"
                >
                    <?= $edit_video ? 'Update Video Gallery' : 'Publish Video' ?>
                </button>
                <?php if ($edit_video): ?>
                    <a 
                        href="manage_videos.php" 
                        class="bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold py-2.5 px-4 rounded-xl transition"
                    >
                        Cancel Edit
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Existing Videos List -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 lg:p-8">
        <div class="mb-6">
            <h3 class="text-lg font-bold text-slate-900">Video Gallery</h3>
            <p class="text-xs text-slate-500 mt-1">Review active embedded visual resources, video channels, or delete outdated assets</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <?php if ($videos_result && $videos_result->num_rows > 0): ?>
                <?php while ($video = $videos_result->fetch_assoc()): ?>
                    <div class="group border border-slate-150 rounded-2xl overflow-hidden bg-white shadow-sm hover:shadow-md transition duration-200 flex flex-col justify-between">
                        <!-- Thumbnail Area with custom play overlay icon on hover -->
                        <div class="relative overflow-hidden aspect-video bg-slate-900 shrink-0">
                            <!-- Fixed dynamic Youtube Thumbnail Tag -->
                            <img src="https://img.youtube.com/vi/<?= htmlspecialchars($video['youtube_video_id']) ?>/mqdefault.jpg" alt="Video Thumbnail" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                            <div class="absolute inset-0 bg-slate-950/20 group-hover:bg-slate-950/40 transition flex items-center justify-center">
                                <a href="https://www.youtube.com/watch?v=<?= htmlspecialchars($video['youtube_video_id']) ?>" target="_blank" class="w-12 h-12 rounded-full bg-white/95 text-rose-600 shadow-lg flex items-center justify-center opacity-90 hover:opacity-100 group-hover:scale-110 transition shrink-0" title="Play Video">
                                    <i class="ri-play-fill text-2xl"></i>
                                </a>
                            </div>
                        </div>

                        <!-- Card Meta Information -->
                        <div class="p-4 flex-grow flex flex-col justify-between space-y-4">
                            <div>
                                <p class="font-bold text-sm text-slate-800 truncate" title="<?= htmlspecialchars($video['title']) ?>"><?= htmlspecialchars($video['title']) ?></p>
                                <p class="text-[10px] font-mono text-slate-400 mt-1 uppercase tracking-wider">ID: <?= htmlspecialchars($video['youtube_video_id']) ?></p>
                            </div>
                            <!-- Operations Panel inside cards -->
                            <div class="flex items-center justify-between pt-3 border-t border-slate-100 shrink-0">
                                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md text-[10px] font-bold bg-rose-50 text-rose-700 uppercase tracking-wide">
                                    YouTube
                                </span>
                                <div class="flex items-center gap-2">
                                    <!-- Edit -->
                                    <a 
                                        href="manage_videos.php?edit=<?= $video['id'] ?>" 
                                        class="p-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-600 rounded-lg transition inline-flex items-center justify-center"
                                        title="Edit Video"
                                    >
                                        <i class="ri-pencil-line text-sm"></i>
                                    </a>
                                    <!-- Delete -->
                                    <form 
                                        action="manage_videos.php" 
                                        method="POST" 
                                        class="inline" 
                                        onsubmit="return confirm('Are you sure you want to delete this video?');"
                                    >
                                        <input type="hidden" name="id" value="<?= $video['id'] ?>">
                                        <button 
                                            type="submit" 
                                            name="delete" 
                                            class="p-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-lg transition inline-flex items-center justify-center"
                                            title="Delete Video"
                                        >
                                            <i class="ri-delete-bin-line text-sm"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-span-full py-16 text-center text-slate-400 text-sm">
                    <div class="flex flex-col items-center justify-center gap-2">
                        <i class="ri-video-off-line text-4xl text-slate-300"></i>
                        <p>No video assets found. Publish video embed IDs to construct the gallery feed.</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

</div>

<?php include '../includes/admin_footer.php'; ?>