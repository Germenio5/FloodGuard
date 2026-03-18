<?php
$email = trim($_POST['email'] ?? '');
$otp = trim($_POST['otp'] ?? '');
$newPassword = trim($_POST['new_password'] ?? '');
$confirmPassword = trim($_POST['confirm_password'] ?? '');

if (empty($email) || empty($otp) || empty($newPassword) || empty($confirmPassword)) {
    header("Location: ../views/reset-password.php?email=" . urlencode($email) . "&error=empty");
    exit();
}

if ($newPassword !== $confirmPassword) {
    header("Location: ../views/reset-password.php?email=" . urlencode($email) . "&error=password_mismatch");
    exit();
}

if (strlen($newPassword) < 8) {
    header("Location: ../views/reset-password.php?email=" . urlencode($email) . "&error=password_short");
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
    header("Location: ../views/reset-password.php?email=" . urlencode($email) . "&error=invalid_otp");
    exit();
}

// Update password
$hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
$updateStmt = $conn->prepare("UPDATE users SET password_hash = ? WHERE email = ?");
$updateStmt->bind_param('ss', $hashedPassword, $email);
$updateStmt->execute();
$updateStmt->close();

// Send confirmation SMS
require_once __DIR__ . '/sms-utils.php';
$confirmationMessage = "FloodGuard: Your password has been successfully reset! You can now log in with your new password.";
$smsResult = sendSMS($user['phone'], $confirmationMessage);

// Clean up used OTP codes for this user
invalidate_user_codes($conn, $email, 'password_reset');

header("Location: ../views/login-user.php?message=password_reset_success");
exit();
?>