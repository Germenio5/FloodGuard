<?php
$phone = trim($_POST['phone'] ?? '');

if (empty($phone)) {
    header("Location: ../views/reset-password-modal.php?error=forgot_empty");
    exit();
}

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/user.php';
require_once __DIR__ . '/sms-utils.php';
require_once __DIR__ . '/../models/verification_codes.php';

// Find user by phone number
$user = get_user_by_phone($conn, $phone);
if (!$user) {
    header("Location: ../views/reset-password-modal.php?error=forgot_not_found&phone=" . urlencode($phone));
    exit();
}

// Allow bypass for built-in test/admin accounts
$bypassVerifiedEmails = [
    'user@floodguard.com',
    'admin@floodguard.com'
];

if (!in_array(strtolower($user['email']), $bypassVerifiedEmails, true) && $user['phone_verified'] != 1) {
    header("Location: ../views/reset-password-modal.php?error=forgot_unverified&phone=" . urlencode($phone));
    exit();
}

// Create OTP for password reset
$otp = create_verification_code($conn, $user['email'], $phone, 'password_reset');

if ($otp) {
    // Send OTP SMS
    $smsResult = sendOTP($phone, $otp);

    if ($smsResult['success']) {
        header("Location: ../views/enter-otp.php?email=" . urlencode($user['email']));
    } else {
        header("Location: ../views/reset-password-modal.php?error=forgot_sms_failed&phone=" . urlencode($phone));
    }
} else {
    header("Location: ../views/reset-password-modal.php?error=forgot_failed&phone=" . urlencode($phone));
}
exit();
?>