<?php
$email = trim($_POST['email'] ?? '');
$otp = trim($_POST['otp'] ?? '');

if (empty($email) || empty($otp)) {
    header("Location: ../views/enter-otp.php?email=" . urlencode($email) . "&error=empty");
    exit();
}

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/user.php';
require_once __DIR__ . '/../models/verification_codes.php';

$user = get_user_by_email($conn, $email);
if (!$user) {
    header("Location: ../views/login-user.php?error=reset_invalid_user");
    exit();
}

// Verify the OTP using the verification_codes table
if (!verify_code($conn, $email, $otp, 'password_reset')) {
    header("Location: ../views/enter-otp.php?email=" . urlencode($email) . "&error=invalid_otp");
    exit();
}

// OTP verified successfully, redirect to reset password page
header("Location: ../views/reset-password.php?email=" . urlencode($email));
exit();
?>