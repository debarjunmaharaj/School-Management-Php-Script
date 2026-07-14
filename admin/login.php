<?php
// FILE: /ss/admin/login.php

require_once '../config/db.php';

if (isset($_SESSION["admin_loggedin"]) && $_SESSION["admin_loggedin"] === true) {
    header("location: index.php");
    exit;
}

$login_err = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);
    $sql = "SELECT id, username, password FROM admins WHERE username = ?";
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $username);
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_store_result($stmt);
            if (mysqli_stmt_num_rows($stmt) == 1) {
                mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
                if (mysqli_stmt_fetch($stmt)) {
                    if (password_verify($password, $hashed_password)) {
                        $_SESSION["admin_loggedin"] = true;
                        $_SESSION["admin_id"] = $id;
                        $_SESSION["admin_username"] = $username;
                        header("location: index.php");
                        exit();
                    }
                }
            }
        }
        $login_err = "Invalid username or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com/3.4.16"></script>
    <!-- Remix Icon -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.css" rel="stylesheet">
    <!-- Inter Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md bg-white rounded-2xl border border-slate-100 shadow-xl p-8 space-y-6 relative overflow-hidden">
        
        <!-- Subtle Top Aesthetic Accent Bar -->
        <div class="absolute top-0 left-0 right-0 h-1.5 bg-indigo-600"></div>

        <!-- Brand/Icon and Header -->
        <div class="text-center space-y-2">
            <div class="mx-auto w-12 h-12 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-600 border border-indigo-100/50">
                <i class="ri-shield-user-line text-2xl"></i>
            </div>
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Admin Portal</h1>
                <p class="text-sm text-slate-500">Sign in to manage your system</p>
            </div>
        </div>

        <!-- Error Notification -->
        <?php if (!empty($login_err)): ?>
            <div class="p-4 bg-rose-50 border border-rose-100 rounded-xl flex items-start gap-3 text-rose-800 text-sm">
                <i class="ri-error-warning-line text-lg shrink-0 text-rose-600 mt-0.5"></i>
                <div>
                    <span class="font-semibold">Authentication failed</span>
                    <p class="text-rose-600/90 text-xs mt-0.5"><?= htmlspecialchars($login_err); ?></p>
                </div>
            </div>
        <?php endif; ?>

        <!-- Form Block -->
        <form action="login.php" method="post" class="space-y-4">
            
            <!-- Username Input Group -->
            <div class="space-y-1.5">
                <label for="username" class="text-xs font-semibold text-slate-600 uppercase tracking-wider">Username</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <i class="ri-user-line text-lg"></i>
                    </div>
                    <input 
                        id="username"
                        name="username" 
                        type="text" 
                        placeholder="Enter username" 
                        class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:bg-white text-sm transition"
                        required
                    >
                </div>
            </div>

            <!-- Password Input Group -->
            <div class="space-y-1.5">
                <div class="flex items-center justify-between">
                    <label for="password" class="text-xs font-semibold text-slate-600 uppercase tracking-wider">Password</label>
                </div>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <i class="ri-lock-line text-lg"></i>
                    </div>
                    <input 
                        id="password"
                        name="password" 
                        type="password" 
                        placeholder="Enter password" 
                        class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:bg-white text-sm transition"
                        required
                    >
                </div>
            </div>

            <!-- Submit Button -->
            <button 
                type="submit" 
                class="w-full mt-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 px-4 rounded-xl shadow-lg shadow-indigo-600/15 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 transition active:scale-[0.98]"
            >
                Sign In
            </button>
        </form>

        <!-- Footer Note -->
        <div class="text-center pt-2">
            <p class="text-xs text-slate-400">Protected administrative area</p>
        </div>
    </div>

</body>
</html>