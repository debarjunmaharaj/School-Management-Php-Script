<?php
// FILE: /ss/admin/manage_users.php (THE CORRECT, FULLY-FEATURED VERSION)

$page_title = 'Manage Users & Roles';
include '../includes/admin_header.php'; // This is the stable header

$message = '';

// --- FORM HANDLING (ADD, DELETE, UPDATE ROLE) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // --- DELETE USER ---
    if (isset($_POST['delete'])) {
        $user_id_to_delete = $_POST['id'];
        if ($user_id_to_delete == 1) { // Prevent deleting the Super Admin
            $message = '<div class="bg-yellow-100 p-3 rounded mb-4">Error: The Super Admin account cannot be deleted.</div>';
        } else {
            $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
            $stmt->bind_param("i", $user_id_to_delete);
            if ($stmt->execute()) {
                $message = '<div class="bg-green-100 p-3 rounded mb-4">User has been deleted.</div>';
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
             $message = '<div class="bg-red-100 p-3 rounded mb-4">Error: Password is required for new users.</div>';
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (username, email, password, role_id) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("sssi", $username, $email, $hashed_password, $role_id);
            if($stmt->execute()) {
                $message = '<div class="bg-green-100 p-3 rounded mb-4">New user created successfully.</div>';
            } else {
                $message = '<div class="bg-red-100 p-3 rounded mb-4">Error: That username or email may already exist.</div>';
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

<?= $message ?>

<!-- Add New User Form -->
<div class="bg-white p-6 rounded-lg shadow-md mb-6">
    <h3 class="font-bold text-xl mb-4">Add New User</h3>
    <p class="text-gray-600 mb-4 text-sm">Create new accounts and assign them a role. Note: This simple version does not have a user edit feature, only add and delete.</p>
    <form method="POST" action="manage_users.php" class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <input type="text" name="username" placeholder="Username" class="w-full p-2 border rounded-md" required>
            <input type="email" name="email" placeholder="Email Address" class="w-full p-2 border rounded-md" required>
            <input type="password" name="password" placeholder="Password" class="w-full p-2 border rounded-md" required>
            <select name="role_id" class="w-full p-2 border rounded-md" required>
                <option value="" disabled selected>Select a Role</option>
                <?php while($role = $roles_result->fetch_assoc()): ?>
                    <option value="<?= $role['id'] ?>"><?= htmlspecialchars($role['role_name']) ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <button type="submit" name="add_user" class="bg-indigo-600 text-white px-5 py-2 rounded-md hover:bg-indigo-700">Add User</button>
    </form>
</div>

<!-- Existing Users List -->
<div class="bg-white p-6 rounded-lg shadow-md">
    <h3 class="font-bold text-xl mb-4">Existing Users</h3>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Username</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Action</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php while($user = $users_result->fetch_assoc()): ?>
                    <tr>
                        <td class="px-6 py-4 font-medium"><?= htmlspecialchars($user['username']) ?></td>
                        <td class="px-6 py-4"><?= htmlspecialchars($user['email']) ?></td>
                        <td class="px-6 py-4">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                <?php 
                                    if ($user['role_name'] == 'Super Admin') echo 'bg-red-100 text-red-800';
                                    elseif ($user['role_name'] == 'Teacher') echo 'bg-green-100 text-green-800';
                                    else echo 'bg-blue-100 text-blue-800';
                                ?>">
                                <?= htmlspecialchars($user['role_name']) ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <?php if ($user['id'] != 1): // Prevent the Super Admin from being deleted ?>
                                <form action="manage_users.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                    <input type="hidden" name="id" value="<?= $user['id'] ?>">
                                    <button type="submit" name="delete" class="text-red-600 hover:text-red-800 font-medium">Delete</button>
                                </form>
                            <?php else: ?>
                                <span class="text-gray-400 text-sm">Cannot Delete</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php 
include '../includes/admin_footer.php'; 
?>