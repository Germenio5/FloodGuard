<?php
session_start();

$email = trim($_POST['email'] ?? '');
$code = trim($_POST['verification_code'] ?? '');

if (empty($email) || empty($code)) {
    header("Location: ../views/verify-phone.php?email=" . urlencode($email) . "&error=empty");
    exit();
}

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/user.php';
require_once __DIR__ . '/../models/verification_codes.php';

// Load pending registration data from session (if present)
$pendingRegistration = $_SESSION['pending_registration'] ?? null;
if ($pendingRegistration && isset($pendingRegistration['email'])) {
    $pendingRegistration['email'] = strtolower(trim($pendingRegistration['email']));
}

$user = get_user_by_email($conn, $email);

// If user doesn't exist, make sure we have pending registration data
if (!$user && (!$pendingRegistration || $pendingRegistration['email'] !== strtolower($email))) {
    header("Location: ../views/verify-phone.php?email=" . urlencode($email) . "&error=invalid_user");
    exit();
}

// Allow bypass for built-in test/admin accounts
$bypassVerifiedEmails = [
    'user@floodguard.com',
    'admin@floodguard.com'
];

if (in_array(strtolower($email), $bypassVerifiedEmails, true)) {
    if ($user) {
        $updateStmt = $conn->prepare("UPDATE users SET phone_verified = 1 WHERE email = ?");
        $updateStmt->bind_param('s', $email);
        $updateStmt->execute();
        $updateStmt->close();
    }

    header("Location: ../views/login-user.php?message=verification_success");
    exit();
}

// If user exists and already verified, redirect
if ($user && $user['phone_verified'] == 1) {
    header("Location: ../views/login-user.php?message=already_verified");
    exit();
}

// Verify the code using the verification_codes table
if (!verify_code($conn, $email, $code, 'phone_verification')) {
    header("Location: ../views/verify-phone.php?email=" . urlencode($email) . "&error=invalid_code");
    exit();
}

// Prevent phone reuse: if the phone number is already verified on another account, stop.
$targetPhone = $user ? $user['phone'] : ($pendingRegistration['phone'] ?? '');
if ($targetPhone && is_phone_verified_by_other($conn, $targetPhone, $email)) {
    header("Location: ../views/verify-phone.php?email=" . urlencode($email) . "&error=phone_in_use");
    exit();
}

// If user does not exist yet, create account now that phone is verified
if (!$user && $pendingRegistration) {
    $created = create_user(
        $conn,
        $pendingRegistration['first_name'],
        $pendingRegistration['last_name'],
        $pendingRegistration['email'],
        $pendingRegistration['phone'],
        $pendingRegistration['address'],
        // password already hashed
        $pendingRegistration['password_hash'],
        true // pass a flag that this is already hashed
    );

    // Clear pending registration from session
    unset($_SESSION['pending_registration']);

    if (!$created) {
        header("Location: ../views/register-user.php?error=db");
        exit();
    }

    // Mark newly created user as verified
    $updateStmt = $conn->prepare("UPDATE users SET phone_verified = 1 WHERE email = ?");
    $updateStmt->bind_param('s', $email);
    $updateStmt->execute();
    $updateStmt->close();
}

// Send confirmation SMS
require_once __DIR__ . '/sms-utils.php';
$confirmationMessage = "FloodGuard: Your phone number has been successfully verified! You can now access all features of your account.";
$recipientPhone = $user ? $user['phone'] : ($pendingRegistration['phone'] ?? '');
$smsResult = sendSMS($recipientPhone, $confirmationMessage);

// Clean up used codes for this user
invalidate_user_codes($conn, $email, 'phone_verification');

header("Location: ../views/login-user.php?message=verification_success");
exit();
?>