<?php
$userEmail = $_GET['email'] ?? '';
$errorMessage = $_GET['error'] ?? '';

if (!$userEmail) {
    header("Location: ../views/login-user.php");
    exit();
}

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/user.php';

$user = get_user_by_email($conn, $userEmail);
if (!$user) {
    header("Location: ../views/login-user.php?error=reset_invalid_user");
    exit();
}

// Censor the phone number for security (show first 2 and last 1 digits)
$phone = $user['phone'];
if (strlen($phone) >= 3) {
    $censoredPhone = substr($phone, 0, 2) . str_repeat('*', strlen($phone) - 3) . substr($phone, -1);
} else {
    $censoredPhone = $phone; // fallback if phone is too short
}

// Convert error codes to user-friendly messages
if ($errorMessage) {
    switch ($errorMessage) {
        case 'empty':
            $errorMessage = 'Please enter the OTP code.';
            break;
        case 'invalid_otp':
            $errorMessage = 'Invalid OTP code. Please check and try again.';
            break;
        case 'otp_expired':
            $errorMessage = 'OTP has expired. Please request a new one.';
            break;
        default:
            $errorMessage = 'An error occurred. Please try again.';
            break;
    }
}
?>