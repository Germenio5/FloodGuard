<?php
session_start();

// Get POST data
$email    = trim($_POST['email'] ?? "");
$password = trim($_POST['password'] ?? "");

// Validation: Check if fields are empty
if ($email == "" || $password == "") {
    $qs = http_build_query(['error' => 'empty', 'email' => $email]);
    header("Location: ../views/login-user.php?$qs");
    exit();
}

// Include database connection and user model
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/user.php';

// Normalize email
$email = strtolower($email);

// Authenticate user
$user = authenticate_user($conn, $email, $password);

if ($user) {
    // Allow bypass for built-in test/admin accounts
    $bypassVerifiedEmails = [
        'user@floodguard.com',
        'admin@floodguard.com'
    ];

    $shouldVerifyPhone = !in_array(strtolower($email), $bypassVerifiedEmails, true);

    // Check if phone is verified (unless bypassed)
    if ($shouldVerifyPhone && $user['phone_verified'] != 1) {
        header("Location: ../views/verify-phone.php?email=" . urlencode($email) . "&error=phone_not_verified");
        exit();
    }

    // Login successful - create session
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_email'] = $user['email'];
    $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
    $_SESSION['user_role'] = $user['role'];

    // Redirect based on role
    if ($user['role'] === 'admin') {
        header("Location: ../views/admin-dashboard.php");
    } else {
        header("Location: ../views/user-dashboard.php");
    }
    exit();
} else {
    // Login failed - redirect back with error
    $qs = http_build_query(['error' => 'invalid', 'email' => $email]);
    header("Location: ../views/login-user.php?$qs");
    exit();
}

?>
