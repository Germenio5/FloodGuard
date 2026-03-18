<?php
$email = trim($_POST['email'] ?? '');

if (empty($email)) {
    header("Location: ../views/reset-password-modal.php?error=forgot_empty");
    exit();
}

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/user.php';
require_once __DIR__ . '/sms-utils.php';
require_once __DIR__ . '/../models/verification_codes.php';

$user = get_user_by_email($conn, $email);
if (!$user) {
    header("Location: ../views/reset-password-modal.php?error=forgot_not_found&email=" . urlencode($email));
    exit();
}

// Allow bypass for built-in test/admin accounts
$bypassVerifiedEmails = [
    'user@floodguard.com',
    'admin@floodguard.com'
];

if (!in_array(strtolower($email), $bypassVerifiedEmails, true) && $user['phone_verified'] != 1) {
    header("Location: ../views/reset-password-modal.php?error=forgot_unverified&email=" . urlencode($email));
    exit();
}

// Create OTP for password reset
$otp = create_verification_code($conn, $email, $user['phone'], 'password_reset');

if ($otp) {
    // Send OTP SMS
    $smsResult = sendOTP($user['phone'], $otp);

    if ($smsResult['success']) {
        header("Location: ../views/reset-password.php?email=" . urlencode($email));
    } else {
        header("Location: ../views/reset-password-modal.php?error=forgot_sms_failed&email=" . urlencode($email));
    }
} else {
    header("Location: ../views/reset-password-modal.php?error=forgot_failed&email=" . urlencode($email));
}
exit();
?>