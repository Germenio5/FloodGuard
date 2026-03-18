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

// Create new OTP for password reset
$otp = create_verification_code($conn, $email, $user['phone'], 'password_reset');

if ($otp) {
    // Send OTP SMS
    $smsResult = sendOTP($user['phone'], $otp);

    if ($smsResult['success']) {
        header("Location: ../views/enter-otp.php?email=" . urlencode($email));
    } else {
        header("Location: ../views/enter-otp.php?email=" . urlencode($email) . "&error=sms_failed");
    }
} else {
    header("Location: ../views/login-user.php?error=resend_failed");
}
exit();
?>