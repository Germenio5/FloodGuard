<?php
session_start();

/**
 * Masks a phone number for display while keeping the first 2 digits and last digit.
 * Example:
 *   09171234567 -> 09*********7
 */
function maskPhoneNumber($phone) {
    // Keep only digits for masking logic
    $clean = preg_replace('/\D+/', '', $phone);
    $len = strlen($clean);

    // If there are too few digits, just return original
    if ($len <= 3) {
        return $phone;
    }

    $prefix = substr($clean, 0, 2);
    $suffix = substr($clean, -1);
    $maskedMiddle = str_repeat('*', max(0, $len - 3));

    $masked = $prefix . $maskedMiddle . $suffix;

    // Preserve leading + if present
    if (strpos($phone, '+') === 0) {
        return '+' . $masked;
    }

    return $masked;
}

$userEmail = $_GET['email'] ?? '';
$smsError = isset($_GET['sms_error']);
$verificationError = $_GET['error'] ?? '';

$userPhone = '';
$maskedPhone = ''; 
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

    // Mask the phone number for display
    if ($userPhone) {
        $maskedPhone = maskPhoneNumber($userPhone);
    }

    // Ensure a verification code exists and send one automatically (once per session)
    if ($userPhone) {
        require_once __DIR__ . '/sms-utils.php';
        require_once __DIR__ . '/../models/verification_codes.php';

        // Prevent resending on every page refresh; only send once per session/email
        $sessionKey = 'verification_sms_sent_' . md5(strtolower($userEmail));
        $alreadySent = $_SESSION[$sessionKey] ?? false;

        if (!$alreadySent) {
            // Always create a fresh code for first-time visit
            $newCode = create_verification_code($conn, $userEmail, $userPhone, 'phone_verification');
            if ($newCode) {
                $smsResult = sendVerificationCode($userPhone, $newCode);
                if ($smsResult['success']) {
                    $_SESSION[$sessionKey] = true;
                } else {
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
        case 'phone_in_use':
            $verificationError = 'This phone number is already verified by another account.';
            break;
        default:
            $verificationError = 'An error occurred. Please try again.';
            break;
    }
}
?>