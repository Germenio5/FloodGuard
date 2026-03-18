<?php
$userEmail = $_GET['email'] ?? '';
$errorMessage = $_GET['error'] ?? '';

if (!$userEmail) {
    header("Location: ../views/login-user.php");
    exit();
}

// Convert error codes to user-friendly messages
if ($errorMessage) {
    switch ($errorMessage) {
        case 'empty':
            $errorMessage = 'Please fill in all fields.';
            break;
        case 'password_mismatch':
            $errorMessage = 'Passwords do not match.';
            break;
        case 'password_short':
            $errorMessage = 'Password must be at least 8 characters long.';
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