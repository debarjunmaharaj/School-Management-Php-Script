<?php
$page_title = 'Manage Managing Committee';
include '../includes/admin_header.php';

$message = '';

// Handle CRUD operations
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // --- DELETE MEMBER ---
    if (isset($_POST['delete'])) {
        $id = intval($_POST['id']);
        
        $stmt_img = $conn->prepare("SELECT image_url FROM managing_committee WHERE id = ?");
        $stmt_img->bind_param("i", $id);
        $stmt_img->execute();
        $res = $stmt_img->get_result()->fetch_assoc();
        $stmt_img->close();

        if ($res && !empty($res['image_url']) && file_exists('../' . $res['image_url'])) {
            unlink('../' . $res['image_url']);
        }

        $stmt = $conn->prepare("DELETE FROM managing_committee WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $message = '
            <div class="p-4 bg-emerald-50 border border-emerald-100 rounded-xl flex items-start gap-3 text-emerald-800 text-sm mb-6 shadow-sm">
                <i class="ri-checkbox-circle-line text-lg text-emerald-600 shrink-0"></i>
                <div>
                    <p class="font-semibold">Member Removed</p>
                    <p class="text-emerald-600/90 text-xs mt-0.5">The managing committee member record has been removed.</p>
                </div>
            </div>';
        } else {
            $message = '
            <div class="p-4 bg-rose-50 border border-rose-100 rounded-xl flex items-start gap-3 text-rose-800 text-sm mb-6 shadow-sm">
                <i class="ri-error-warning-line text-lg text-rose-600 shrink-0"></i>
                <div>
                    <p class="font-semibold">Error Deleting Record</p>
                    <p class="text-rose-600/90 text-xs mt-0.5">Could not process query. Try again later.</p>
                </div>
            </div>';
        }
        $stmt->close();
    }
    // --- ADD or UPDATE MEMBER ---
    else {
        $id = !empty($_POST['id']) ? intval($_POST['id']) : null;
        $name = trim($_POST['name'] ?? '');
        $designation = trim($_POST['designation'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $image_url = $_POST['existing_image'] ?? '';

        // Handle Image Upload
        if (isset($_FILES['committee_image']) && $_FILES['committee_image']['error'] == 0) {
            $target_dir = '../uploads/committee/';
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            $filename = uniqid('comm_') . '.' . pathinfo($_FILES['committee_image']['name'], PATHINFO_EXTENSION);
            $target_file = $target_dir . $filename;
            if (move_uploaded_file($_FILES['committee_image']['tmp_name'], $target_file)) {
                if (!empty($image_url) && file_exists('../' . $image_url)) {
                    unlink('../' . $image_url);
                }
                $image_url = 'uploads/committee/' . $filename;
            }
        }

        if (empty($name) || empty($designation)) {
            $message = '
            <div class="p-4 bg-rose-50 border border-rose-100 rounded-xl flex items-start gap-3 text-rose-800 text-sm mb-6 shadow-sm">
                <i class="ri-error-warning-line text-lg text-rose-600 shrink-0"></i>
                <div>
                    <p class="font-semibold">Missing Properties</p>
                    <p class="text-rose-600/90 text-xs mt-0.5">Member Name and Designation/Role are required.</p>
                </div>
            </div>';
        } else {
            if (empty($id)) {
                // Add new
                $stmt = $conn->prepare("INSERT INTO managing_committee (name, designation, phone, email, image_url) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("sssss", $name, $designation, $phone, $email, $image_url);
                $success_msg = "Committee member added successfully.";
            } else {
                // Update existing
                $stmt = $conn->prepare("UPDATE managing_committee SET name = ?, designation = ?, phone = ?, email = ?, image_url = ? WHERE id = ?");
                $stmt->bind_param("sssssi", $name, $designation, $phone, $email, $image_url, $id);
                $success_msg = "Committee member updated successfully.";
            }

            if ($stmt->execute()) {
                $message = '
                <div class="p-4 bg-emerald-50 border border-emerald-100 rounded-xl flex items-start gap-3 text-emerald-800 text-sm mb-6 shadow-sm">
                    <i class="ri-checkbox-circle-line text-lg text-emerald-600 shrink-0"></i>
                    <div>
                        <p class="font-semibold">Record Updated</p>
                        <p class="text-emerald-600/90 text-xs mt-0.5">' . htmlspecialchars($success_msg) . '</p>
                    </div>
                </div>';
            } else {
                $message = '
                <div class="p-4 bg-rose-50 border border-rose-100 rounded-xl flex items-start gap-3 text-rose-800 text-sm mb-6 shadow-sm">
                    <i class="ri-error-warning-line text-lg text-rose-600 shrink-0"></i>
                    <div>
                        <p class="font-semibold">Database Error</p>
                        <p class="text-rose-600/90 text-xs mt-0.5">Could not save values into database.</p>
                    </div>
                </div>';
            }
            $stmt->close();
        }
    }
}

// Fetch member for editing if requested
$edit_member = null;
if (isset($_GET['edit'])) {
    $id_to_edit = intval($_GET['edit']);
    $stmt = $conn->prepare("SELECT * FROM managing_committee WHERE id = ?");
    $stmt->bind_param("i", $id_to_edit);
    $stmt->execute();
    $edit_member = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}

$committee_result = $conn->query("SELECT * FROM managing_committee ORDER BY id ASC");
?>

<div class="max-w-6xl mx-auto space-y-8 py-4">

    <?= $message ?>

    <!-- Add/Edit Form -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 lg:p-8 relative">
        <div class="absolute top-0 left-0 right-0 h-1 bg-emerald-600 rounded-t-2xl"></div>
        
        <div class="mb-6">
            <h3 class="text-lg font-bold text-slate-900"><?= $edit_member ? 'Edit Committee Member (ম্যানেজিং কমিটি সদস্য সম্পাদনা)' : 'Add Managing Committee Member (ম্যানেজিং কমিটি সদস্য যুক্ত করুন)' ?></h3>
            <p class="text-xs text-slate-500 mt-1">Configure governing body and managing committee members displayed on the school website</p>
        </div>

        <form action="manage_committee.php" method="POST" enctype="multipart/form-data" class="space-y-6">
            <input type="hidden" name="id" value="<?= $edit_member['id'] ?? '' ?>">
            <input type="hidden" name="existing_image" value="<?= $edit_member['image_url'] ?? '' ?>">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div class="space-y-1.5">
                    <label for="name" class="text-xs font-semibold text-slate-600 uppercase tracking-wider">Member Name (সদস্যের নাম) *</label>
                    <input 
                        type="text" 
                        name="name" 
                        id="name" 
                        value="<?= htmlspecialchars($edit_member['name'] ?? '') ?>" 
                        placeholder="e.g. মোঃ আব্দুর রহমান"
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white text-sm transition font-medium" 
                        required
                    >
                </div>

                <!-- Designation -->
                <div class="space-y-1.5">
                    <label for="designation" class="text-xs font-semibold text-slate-600 uppercase tracking-wider">Designation / Role (পদবী) *</label>
                    <input 
                        type="text" 
                        name="designation" 
                        id="designation" 
                        value="<?= htmlspecialchars($edit_member['designation'] ?? '') ?>" 
                        placeholder="e.g. সভাপতি / সদস্য সচিব / অভিভাবক সদস্য"
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white text-sm transition font-medium" 
                        required
                    >
                </div>

                <!-- Phone -->
                <div class="space-y-1.5">
                    <label for="phone" class="text-xs font-semibold text-slate-600 uppercase tracking-wider">Phone Number (মোবাইল নম্বর)</label>
                    <input 
                        type="text" 
                        name="phone" 
                        id="phone" 
                        value="<?= htmlspecialchars($edit_member['phone'] ?? '') ?>" 
                        placeholder="e.g. +৮৮০১৭১১-XXXXXX"
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white text-sm transition" 
                    >
                </div>

                <!-- Email -->
                <div class="space-y-1.5">
                    <label for="email" class="text-xs font-semibold text-slate-600 uppercase tracking-wider">Email (ইমেইল)</label>
                    <input 
                        type="email" 
                        name="email" 
                        id="email" 
                        value="<?= htmlspecialchars($edit_member['email'] ?? '') ?>" 
                        placeholder="e.g. member@school.edu.bd"
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white text-sm transition" 
                    >
                </div>

                <!-- Image -->
                <div class="space-y-1.5 md:col-span-2">
                    <label for="committee_image" class="text-xs font-semibold text-slate-600 uppercase tracking-wider block">Photo (ছবি - Optional)</label>
                    <input 
                        type="file" 
                        name="committee_image" 
                        id="committee_image"
                        class="block w-full text-sm text-slate-500 file:mr-4 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 transition cursor-pointer"
                    >
                    <?php if(!empty($edit_member['image_url'])): ?>
                    <div class="mt-2 h-16 w-16 rounded-xl overflow-hidden shadow-sm border border-slate-200">
                        <img src="../<?= htmlspecialchars($edit_member['image_url']) ?>" class="w-full h-full object-cover">
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="flex items-center gap-2 pt-2">
                <button 
                    type="submit" 
                    class="bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold py-2.5 px-6 rounded-xl transition shadow-sm active:scale-[0.98]"
                >
                    <?= $edit_member ? 'Update Member (সংরক্ষণ করুন)' : 'Add Member (যুক্ত করুন)' ?>
                </button>
                <?php if ($edit_member): ?>
                    <a 
                        href="manage_committee.php" 
                        class="bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold py-2.5 px-4 rounded-xl transition"
                    >
                        Cancel Edit
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Committee Members List -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 lg:p-8">
        <div class="mb-6">
            <h3 class="text-lg font-bold text-slate-900">Managing Committee Members (ম্যানেজিং কমিটি তালিকা)</h3>
            <p class="text-xs text-slate-500 mt-1">Review, update, or remove active committee members</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="border-b border-slate-100 text-slate-400 font-semibold">
                        <th class="py-3 px-4">Photo</th>
                        <th class="py-3 px-4">Name</th>
                        <th class="py-3 px-4">Designation</th>
                        <th class="py-3 px-4">Contact</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700 font-medium">
                    <?php if ($committee_result && $committee_result->num_rows > 0): ?>
                        <?php while ($member = $committee_result->fetch_assoc()): ?>
                            <tr>
                                <td class="py-3 px-4">
                                    <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center overflow-hidden border border-slate-200">
                                        <?php if(!empty($member['image_url'])): ?>
                                            <img src="../<?= htmlspecialchars($member['image_url']) ?>" class="w-full h-full object-cover">
                                        <?php else: ?>
                                            <i class="ri-user-line text-slate-400"></i>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="py-3 px-4 font-bold text-slate-800">
                                    <?= htmlspecialchars($member['name']) ?>
                                </td>
                                <td class="py-3 px-4 text-emerald-700 font-semibold">
                                    <span class="inline-block px-2.5 py-1 bg-emerald-50 text-emerald-700 rounded-lg text-xs font-semibold">
                                        <?= htmlspecialchars($member['designation']) ?>
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-slate-500 text-xs">
                                    <?= htmlspecialchars($member['phone'] ?: ($member['email'] ?: 'N/A')) ?>
                                </td>
                                <td class="py-3 px-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <!-- Edit -->
                                        <a href="manage_committee.php?edit=<?= $member['id'] ?>" class="p-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-600 rounded-lg transition inline-flex items-center justify-center" title="Edit">
                                            <i class="ri-pencil-line text-sm"></i>
                                        </a>
                                        <!-- Delete -->
                                        <form action="manage_committee.php" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this member?');">
                                            <input type="hidden" name="id" value="<?= $member['id'] ?>">
                                            <button type="submit" name="delete" class="p-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-lg transition inline-flex items-center justify-center" title="Delete">
                                                <i class="ri-delete-bin-line text-sm"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="py-12 text-center text-slate-400">
                                No committee members found.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../includes/admin_footer.php'; ?>
