<?php
session_start();

$userEmail = $_GET['email'] ?? '';
$smsError = isset($_GET['sms_error']);
$verificationError = $_GET['error'] ?? '';

$userPhone = '';
if ($userEmail) {
    require_once __DIR__ . '/../config/config.php';
    require_once __DIR__ . '/../models/user.php';

    $user = get_user_by_email($conn, $userEmail);

    // If the user does not exist yet, look for pending registration data in session
    $pending = $_SESSION['pending_registration'] ?? null;
    if (!$user && $pending && isset($pending['email']) && strtolower($pending['email']) === strtolower($userEmail)) {
        $userPhone = $pending['phone'];
    }

    if ($user) {
        $userPhone = $user['phone'];
    }

    // Ensure a verification code exists and send one automatically if missing
    if ($userPhone) {
        require_once __DIR__ . '/sms-utils.php';
        require_once __DIR__ . '/../models/verification_codes.php';

        $existingCode = get_latest_verification_code($conn, $userEmail, 'phone_verification');
        if (!$existingCode) {
            $newCode = create_verification_code($conn, $userEmail, $userPhone, 'phone_verification');
            if ($newCode) {
                $smsResult = sendVerificationCode($userPhone, $newCode);
                if (!$smsResult['success']) {
                    $smsError = true;
                }
            } else {
                $smsError = true;
            }
        }
    }
}

// Convert error codes to user-friendly messages
if ($verificationError) {
    switch ($verificationError) {
        case 'empty':
            $verificationError = 'Please enter the verification code.';
            break;
        case 'invalid_user':
            $verificationError = 'Invalid user account.';
            break;
        case 'invalid_code':
            $verificationError = 'Invalid verification code. Please check and try again.';
            break;
        case 'phone_not_verified':
            $verificationError = 'Your phone number is not verified. Please verify your phone first.';
            break;
        default:
            $verificationError = 'An error occurred. Please try again.';
            break;
    }
}
?>