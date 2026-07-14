<?php
$page_title = 'Manage Upcoming Events';
include '../includes/admin_header.php'; // Includes session check and database connection

$message = '';

// Handle form submissions (Add, Edit, Delete)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // --- DELETE EVENT ---
    if (isset($_POST['delete'])) {
        $id = $_POST['id'];
        $stmt = $conn->prepare("DELETE FROM events WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $message = '
            <div class="p-4 bg-emerald-50 border border-emerald-100 rounded-xl flex items-start gap-3 text-emerald-800 text-sm mb-6 shadow-sm">
                <i class="ri-checkbox-circle-line text-lg text-emerald-600 shrink-0"></i>
                <div>
                    <p class="font-semibold">Event Deleted</p>
                    <p class="text-emerald-600/90 text-xs mt-0.5">The calendar event has been successfully removed from the schedule.</p>
                </div>
            </div>';
        } else {
            $message = '
            <div class="p-4 bg-rose-50 border border-rose-100 rounded-xl flex items-start gap-3 text-rose-800 text-sm mb-6 shadow-sm">
                <i class="ri-error-warning-line text-lg text-rose-600 shrink-0"></i>
                <div>
                    <p class="font-semibold">Error Deleting Event</p>
                    <p class="text-rose-600/90 text-xs mt-0.5">Please check your query configurations or database status.</p>
                </div>
            </div>';
        }
        $stmt->close();
    }
    // --- ADD or UPDATE EVENT ---
    else {
        $id = $_POST['id'];
        $title = $_POST['title'];
        $event_date = $_POST['event_date']; // Format is Y-m-d\TH:i from the input
        $location = $_POST['location'];

        // Basic validation
        if (empty($title) || empty($event_date) || empty($location)) {
             $message = '
             <div class="p-4 bg-rose-50 border border-rose-100 rounded-xl flex items-start gap-3 text-rose-800 text-sm mb-6 shadow-sm">
                 <i class="ri-error-warning-line text-lg text-rose-600 shrink-0"></i>
                 <div>
                     <p class="font-semibold">Validation Error</p>
                     <p class="text-rose-600/90 text-xs mt-0.5">All configuration parameters are required to define calendar events.</p>
                 </div>
             </div>';
        } else {
            if (empty($id)) {
                // Add new event
                $stmt = $conn->prepare("INSERT INTO events (title, event_date, location) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $title, $event_date, $location);
                $success_msg = "Event added successfully.";
            } else {
                // Update existing event
                $stmt = $conn->prepare("UPDATE events SET title = ?, event_date = ?, location = ? WHERE id = ?");
                $stmt->bind_param("sssi", $title, $event_date, $location, $id);
                $success_msg = "Event updated successfully.";
            }

            if ($stmt->execute()) {
                $message = '
                <div class="p-4 bg-emerald-50 border border-emerald-100 rounded-xl flex items-start gap-3 text-emerald-800 text-sm mb-6 shadow-sm">
                    <i class="ri-checkbox-circle-line text-lg text-emerald-600 shrink-0"></i>
                    <div>
                        <p class="font-semibold">Calendar Updated</p>
                        <p class="text-emerald-600/90 text-xs mt-0.5">' . htmlspecialchars($success_msg) . '</p>
                    </div>
                </div>';
            } else {
                $message = '
                <div class="p-4 bg-rose-50 border border-rose-100 rounded-xl flex items-start gap-3 text-rose-800 text-sm mb-6 shadow-sm">
                    <i class="ri-error-warning-line text-lg text-rose-600 shrink-0"></i>
                    <div>
                        <p class="font-semibold">Execution Failure</p>
                        <p class="text-rose-600/90 text-xs mt-0.5">Failed to write event parameters to database registry.</p>
                    </div>
                </div>';
            }
            $stmt->close();
        }
    }
}

// Fetch a single event for editing if an ID is provided in the URL
$edit_event = null;
if (isset($_GET['edit'])) {
    $id_to_edit = $_GET['edit'];
    $stmt = $conn->prepare("SELECT * FROM events WHERE id = ?");
    $stmt->bind_param("i", $id_to_edit);
    $stmt->execute();
    $result = $stmt->get_result();
    $edit_event = $result->fetch_assoc();
    $stmt->close();
}

// Fetch all events to display in the list, newest first
$events_result = $conn->query("SELECT * FROM events ORDER BY event_date DESC");
?>

<div class="max-w-6xl mx-auto space-y-8 py-4">

    <!-- Display feedback message -->
    <?= $message ?>

    <!-- Add/Edit Form -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 lg:p-8 relative">
        <div class="absolute top-0 left-0 right-0 h-1 bg-indigo-600 rounded-t-2xl"></div>
        
        <div class="mb-6">
            <h3 class="text-lg font-bold text-slate-900"><?= $edit_event ? 'Edit Event Details' : 'Add New Scheduled Event' ?></h3>
            <p class="text-xs text-slate-500 mt-1">Populate details for assemblies, sports events, orientations, and holidays</p>
        </div>

        <form action="manage_events.php" method="POST" class="space-y-6">
            <input type="hidden" name="id" value="<?= $edit_event['id'] ?? '' ?>">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Title -->
                <div class="space-y-1.5">
                    <label for="title" class="text-xs font-semibold text-slate-600 uppercase tracking-wider">Event Title</label>
                    <input 
                        type="text" 
                        name="title" 
                        id="title" 
                        value="<?= htmlspecialchars($edit_event['title'] ?? '') ?>" 
                        placeholder="e.g. Annual Sports Day"
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:bg-white text-sm transition" 
                        required
                    >
                </div>
                
                <!-- Date and Time -->
                <div class="space-y-1.5">
                    <label for="event_date" class="text-xs font-semibold text-slate-600 uppercase tracking-wider">Date and Time</label>
                    <?php
                        // Format the date for the datetime-local input, which requires Y-m-d\TH:i format
                        $date_for_input = $edit_event ? date('Y-m-d\TH:i', strtotime($edit_event['event_date'])) : '';
                    ?>
                    <input 
                        type="datetime-local" 
                        name="event_date" 
                        id="event_date" 
                        value="<?= $date_for_input ?>" 
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:bg-white text-sm transition" 
                        required
                    >
                </div>
            </div>
            
            <!-- Location -->
            <div class="space-y-1.5">
                <label for="location" class="text-xs font-semibold text-slate-600 uppercase tracking-wider">Location</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <i class="ri-map-pin-line text-lg"></i>
                    </div>
                    <input 
                        type="text" 
                        name="location" 
                        id="location" 
                        value="<?= htmlspecialchars($edit_event['location'] ?? '') ?>" 
                        class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:bg-white text-sm transition" 
                        placeholder="e.g., Main Auditorium, Block B" 
                        required
                    >
                </div>
            </div>
            
            <div class="flex items-center gap-2 pt-2">
                <button 
                    type="submit" 
                    class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold py-2.5 px-6 rounded-xl transition shadow-sm active:scale-[0.98]"
                >
                    <?= $edit_event ? 'Update Calendar Event' : 'Schedule Event' ?>
                </button>
                <?php if ($edit_event): ?>
                    <a 
                        href="manage_events.php" 
                        class="bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold py-2.5 px-4 rounded-xl transition"
                    >
                        Cancel Edit
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Existing Events List -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 lg:p-8 overflow-hidden">
        <div class="mb-6">
            <h3 class="text-lg font-bold text-slate-900">Scheduled Events</h3>
            <p class="text-xs text-slate-500 mt-1">Review, reschedule, or cancel active upcoming school dates</p>
        </div>

        <div class="overflow-x-auto -mx-6 lg:-mx-8">
            <table class="min-w-full divide-y divide-slate-150">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 lg:px-8 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Event Date</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Title</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Location</th>
                        <th class="px-6 lg:px-8 py-3.5 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-100">
                    <?php if ($events_result && $events_result->num_rows > 0): ?>
                        <?php while ($event = $events_result->fetch_assoc()): ?>
                        <tr class="hover:bg-slate-50/40 transition">
                            <!-- Custom Calendar Date Badge Design -->
                            <td class="px-6 lg:px-8 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <!-- Dynamic Date Icon Badge -->
                                    <div class="w-10 h-10 rounded-xl bg-indigo-50 border border-indigo-100/50 flex flex-col items-center justify-center text-center p-1 shrink-0">
                                        <span class="text-[10px] uppercase font-bold text-indigo-500 leading-none"><?= date('M', strtotime($event['event_date'])) ?></span>
                                        <span class="text-sm font-bold text-indigo-750 leading-tight"><?= date('j', strtotime($event['event_date'])) ?></span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-slate-900"><?= date('D, M j, Y', strtotime($event['event_date'])) ?></p>
                                        <p class="text-xs text-slate-400 mt-0.5 inline-flex items-center gap-1">
                                            <i class="ri-time-line text-slate-400"></i>
                                            <?= date('g:i A', strtotime($event['event_date'])) ?>
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <!-- Event Title -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-slate-800">
                                <?= htmlspecialchars($event['title']) ?>
                            </td>
                            <!-- Event Location with map-pin icon -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                <span class="inline-flex items-center gap-1.5">
                                    <i class="ri-map-pin-2-line text-slate-400"></i>
                                    <?= htmlspecialchars($event['location']) ?>
                                </span>
                            </td>
                            <!-- Operations -->
                            <td class="px-6 lg:px-8 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-3">
                                    <a 
                                        href="manage_events.php?edit=<?= $event['id'] ?>" 
                                        class="p-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-600 rounded-lg transition inline-flex items-center justify-center"
                                        title="Edit Event"
                                    >
                                        <i class="ri-pencil-line text-base"></i>
                                    </a>
                                    <form 
                                        action="manage_events.php" 
                                        method="POST" 
                                        class="inline-block" 
                                        onsubmit="return confirm('Are you sure you want to delete this event?');"
                                    >
                                        <input type="hidden" name="id" value="<?= $event['id'] ?>">
                                        <button 
                                            type="submit" 
                                            name="delete" 
                                            class="p-2 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-lg transition inline-flex items-center justify-center"
                                            title="Delete Event"
                                        >
                                            <i class="ri-delete-bin-line text-base"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-slate-400 text-sm">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <i class="ri-calendar-todo-line text-3xl text-slate-300"></i>
                                    <p>No events found. Create one using the form configuration tool.</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<?php include '../includes/admin_footer.php'; ?>