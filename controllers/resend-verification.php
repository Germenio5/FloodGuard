<?php
$email = trim($_POST['email'] ?? '');

if (empty($email)) {
    header("Location: ../views/login-user.php?error=resend_failed");
    exit();
}

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/user.php';
require_once __DIR__ . '/sms-utils.php';
require_once __DIR__ . '/../models/verification_codes.php';

$user = get_user_by_email($conn, $email);
if (!$user) {
    header("Location: ../views/login-user.php?error=resend_failed");
    exit();
}

if ($user['phone_verified'] == 1) {
    header("Location: ../views/login-user.php?message=already_verified");
    exit();
}

// Create new verification code
$verificationCode = create_verification_code($conn, $email, $user['phone'], 'phone_verification');

if ($verificationCode) {
    // Send verification SMS
    $smsResult = sendVerificationCode($user['phone'], $verificationCode);

    if ($smsResult['success']) {
        header("Location: ../views/verify-phone.php?email=" . urlencode($email));
    } else {
        header("Location: ../views/verify-phone.php?email=" . urlencode($email) . "&sms_error=1");
    }
} else {
    header("Location: ../views/login-user.php?error=resend_failed");
}
exit();
?>