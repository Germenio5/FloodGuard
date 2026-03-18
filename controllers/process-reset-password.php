<?php
$email = trim($_POST['email'] ?? '');
$newPassword = trim($_POST['new_password'] ?? '');
$confirmPassword = trim($_POST['confirm_password'] ?? '');

if (empty($email) || empty($newPassword) || empty($confirmPassword)) {
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

if(!preg_match("/[A-Z]/", $newPassword) ||
   !preg_match("/[a-z]/", $newPassword) ||
   !preg_match("/[0-9]/", $newPassword)) {
    header("Location: ../views/reset-password.php?email=" . urlencode($email) . "&error=passweak");
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

// Update password
$hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
$updateStmt = $conn->prepare("UPDATE users SET password_hash = ? WHERE email = ?");
$updateStmt->bind_param('ss', $hashedPassword, $email);
$updateStmt->execute();
$updateStmt->close();

// Clean up used OTP codes for this user
invalidate_user_codes($conn, $email, 'password_reset');

header("Location: ../views/login-user.php?message=password_reset_success");
exit();
?>