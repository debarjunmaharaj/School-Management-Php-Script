<?php
// FILE: /ss/admin/manage_users.php

$page_title = 'Manage Users & Roles';
include '../includes/admin_header.php'; // Includes session check and database connection

$message = '';

// --- FORM HANDLING (ADD, DELETE, UPDATE ROLE) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // --- DELETE USER ---
    if (isset($_POST['delete'])) {
        $user_id_to_delete = $_POST['id'];
        if ($user_id_to_delete == 1) { // Prevent deleting the Super Admin
            $message = '
            <div class="p-4 bg-amber-50 border border-amber-100 rounded-xl flex items-start gap-3 text-amber-800 text-sm mb-6 shadow-sm">
                <i class="ri-alert-line text-lg text-amber-600 shrink-0"></i>
                <div>
                    <p class="font-semibold">Operation Restricted</p>
                    <p class="text-amber-600/90 text-xs mt-0.5">The primary Super Admin account is protected by core rules and cannot be deleted.</p>
                </div>
            </div>';
        } else {
            $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
            $stmt->bind_param("i", $user_id_to_delete);
            if ($stmt->execute()) {
                $message = '
                <div class="p-4 bg-emerald-50 border border-emerald-100 rounded-xl flex items-start gap-3 text-emerald-800 text-sm mb-6 shadow-sm">
                    <i class="ri-checkbox-circle-line text-lg text-emerald-600 shrink-0"></i>
                    <div>
                        <p class="font-semibold">User Deleted</p>
                        <p class="text-emerald-600/90 text-xs mt-0.5">The selected administrative account has been erased from the register.</p>
                    </div>
                </div>';
            }
        }
    }
    // --- ADD NEW USER ---
    elseif (isset($_POST['add_user'])) {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $role_id = $_POST['role_id'];
        $password = $_POST['password'];

        if (empty($password)) {
             $message = '
             <div class="p-4 bg-rose-50 border border-rose-100 rounded-xl flex items-start gap-3 text-rose-800 text-sm mb-6 shadow-sm">
                 <i class="ri-error-warning-line text-lg text-rose-600 shrink-0"></i>
                 <div>
                     <p class="font-semibold">Security Requirement</p>
                     <p class="text-rose-600/90 text-xs mt-0.5">A secure custom password is required to generate new login credentials.</p>
                 </div>
             </div>';
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (username, email, password, role_id) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("sssi", $username, $email, $hashed_password, $role_id);
            if ($stmt->execute()) {
                $message = '
                <div class="p-4 bg-emerald-50 border border-emerald-100 rounded-xl flex items-start gap-3 text-emerald-800 text-sm mb-6 shadow-sm">
                    <i class="ri-checkbox-circle-line text-lg text-emerald-600 shrink-0"></i>
                    <div>
                        <p class="font-semibold">User Account Activated</p>
                        <p class="text-emerald-600/90 text-xs mt-0.5">The user account has been successfully formatted and is ready for use.</p>
                    </div>
                </div>';
            } else {
                $message = '
                <div class="p-4 bg-rose-50 border border-rose-100 rounded-xl flex items-start gap-3 text-rose-800 text-sm mb-6 shadow-sm">
                    <i class="ri-error-warning-line text-lg text-rose-600 shrink-0"></i>
                    <div>
                        <p class="font-semibold">Conflict Detected</p>
                        <p class="text-rose-600/90 text-xs mt-0.5">The username or email address may already exist in our active directories.</p>
                    </div>
                </div>';
            }
        }
    }
}

// --- DATA FETCHING ---
// Get all users and their assigned roles
$users_result = $conn->query("SELECT u.id, u.username, u.email, r.role_name FROM users u JOIN roles r ON u.role_id = r.id ORDER BY u.username");
// Get all available roles for the dropdown menu
$roles_result = $conn->query("SELECT * FROM roles ORDER BY role_name");
?>

<div class="max-w-6xl mx-auto space-y-8 py-4">

    <!-- Render Response Notifications -->
    <?= $message ?>

    <!-- Add New User Form -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 lg:p-8 relative">
        <div class="absolute top-0 left-0 right-0 h-1 bg-indigo-600 rounded-t-2xl"></div>
        
        <div class="mb-6">
            <h3 class="text-lg font-bold text-slate-900">Add New User</h3>
            <p class="text-xs text-slate-500 mt-1">Deploy additional administrative credentials and assign active security access groups</p>
        </div>

        <form method="POST" action="manage_users.php" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Username -->
                <div class="space-y-1.5">
                    <label for="username" class="text-xs font-semibold text-slate-600 uppercase tracking-wider">Username</label>
                    <input 
                        type="text" 
                        name="username" 
                        id="username"
                        placeholder="e.g. jcooper" 
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:bg-white text-sm transition"
                        required
                    >
                </div>

                <!-- Email -->
                <div class="space-y-1.5">
                    <label for="email" class="text-xs font-semibold text-slate-600 uppercase tracking-wider">Email Address</label>
                    <input 
                        type="email" 
                        name="email" 
                        id="email"
                        placeholder="e.g. cooper@school.com" 
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:bg-white text-sm transition"
                        required
                    >
                </div>

                <!-- Password -->
                <div class="space-y-1.5">
                    <label for="password" class="text-xs font-semibold text-slate-600 uppercase tracking-wider">Password</label>
                    <input 
                        type="password" 
                        name="password" 
                        id="password"
                        placeholder="Minimum 8 characters" 
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:bg-white text-sm transition"
                        required
                    >
                </div>

                <!-- Role -->
                <div class="space-y-1.5">
                    <label for="role_id" class="text-xs font-semibold text-slate-600 uppercase tracking-wider">Select Access Role</label>
                    <select 
                        name="role_id" 
                        id="role_id"
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:bg-white text-sm transition appearance-none"
                        required
                    >
                        <option value="" disabled selected>Choose a Role</option>
                        <?php while($role = $roles_result->fetch_assoc()): ?>
                            <option value="<?= $role['id'] ?>"><?= htmlspecialchars($role['role_name']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
            </div>

            <div class="pt-2">
                <button 
                    type="submit" 
                    name="add_user" 
                    class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold py-2.5 px-6 rounded-xl transition shadow-sm active:scale-[0.98]"
                >
                    Create User Account
                </button>
            </div>
        </form>
    </div>

    <!-- Existing Users List -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 lg:p-8 overflow-hidden">
        <div class="mb-6">
            <h3 class="text-lg font-bold text-slate-900">Existing Users</h3>
            <p class="text-xs text-slate-500 mt-1">Review active operator registers, credentials, and access categories</p>
        </div>

        <div class="overflow-x-auto -mx-6 lg:-mx-8">
            <table class="min-w-full divide-y divide-slate-150">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 lg:px-8 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Username</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Email Address</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Security Access Group</th>
                        <th class="px-6 lg:px-8 py-3.5 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-100">
                    <?php if ($users_result && $users_result->num_rows > 0): ?>
                        <?php while($user = $users_result->fetch_assoc()): ?>
                        <tr class="hover:bg-slate-50/40 transition">
                            <!-- Username -->
                            <td class="px-6 lg:px-8 py-4 whitespace-nowrap text-sm font-semibold text-slate-900">
                                <?= htmlspecialchars($user['username']) ?>
                            </td>
                            <!-- Email -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                <?= htmlspecialchars($user['email']) ?>
                            </td>
                            <!-- Access Badge with custom category styles -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold">
                                <?php 
                                    $role_badge_classes = 'bg-slate-100 text-slate-700';
                                    if ($user['role_name'] == 'Super Admin') {
                                        $role_badge_classes = 'bg-rose-50 text-rose-700 border border-rose-100/50';
                                    } elseif ($user['role_name'] == 'Teacher') {
                                        $role_badge_classes = 'bg-emerald-50 text-emerald-700 border border-emerald-100/50';
                                    } else {
                                        $role_badge_classes = 'bg-blue-50 text-blue-700 border border-blue-100/50';
                                    }
                                ?>
                                <span class="px-2.5 py-1 text-xs font-bold rounded-full uppercase tracking-wider <?= $role_badge_classes ?>">
                                    <?= htmlspecialchars($user['role_name']) ?>
                                </span>
                            </td>
                            <!-- Delete action with check validation logic -->
                            <td class="px-6 lg:px-8 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <?php if ($user['id'] != 1): // Prevent Super Admin from deletion ?>
                                    <form 
                                        action="manage_users.php" 
                                        method="POST" 
                                        class="inline-block"
                                        onsubmit="return confirm('Are you sure you want to delete this user? This cannot be undone.');"
                                    >
                                        <input type="hidden" name="id" value="<?= $user['id'] ?>">
                                        <button 
                                            type="submit" 
                                            name="delete" 
                                            class="p-2 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-lg transition inline-flex items-center justify-center"
                                            title="Delete User"
                                        >
                                            <i class="ri-delete-bin-line text-base"></i>
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <!-- Secure protection badge for super user -->
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-slate-100 text-slate-500 border border-slate-200 text-xs font-semibold rounded-lg select-none">
                                        <i class="ri-lock-line text-slate-400"></i> Protected
                                    </span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-slate-400 text-sm">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <i class="ri-group-line text-3xl text-slate-300"></i>
                                    <p>No user records exist in register indexes.</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<?php 
include '../includes/admin_footer.php'; 
?>