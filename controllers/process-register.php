<?php
session_start();

$first    = trim($_POST['first_name'] ?? "");
$last     = trim($_POST['last_name'] ?? "");
$email    = trim($_POST['email'] ?? "");
$phone    = trim($_POST['phone'] ?? "");
$addr     = trim($_POST['address'] ?? "");
$specific_addr = trim($_POST['specific_address'] ?? "");
$pass     = trim($_POST['password'] ?? "");
$confirm  = trim($_POST['confirm_password'] ?? "");

if($first=="" || $last=="" || $email=="" ||
   $phone=="" || $addr=="" || $specific_addr=="" || $pass=="" || $confirm=="") {

    $qs = http_build_query([
        'error'      => 'empty',
        'first_name' => $first,
        'last_name'  => $last,
        'email'      => $email,
        'phone'      => $phone,
        'address'    => $addr,
        'specific_address' => $specific_addr
    ]);
    header("Location: ../views/register-user.php?$qs");
    exit();
}

if(!preg_match("/^[a-zA-Z ]+$/", $first) ||
   !preg_match("/^[a-zA-Z ]+$/", $last)) {

    $qs = http_build_query([
        'error'      => 'name',
        'first_name' => $first,
        'last_name'  => $last,
        'email'      => $email,
        'phone'      => $phone,
        'address'    => $addr,
        'specific_address' => $specific_addr
    ]);
    header("Location: ../views/register-user.php?$qs");
    exit();
}

if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {

    $qs = http_build_query([
        'error'      => 'email',
        'first_name' => $first,
        'last_name'  => $last,
        'email'      => $email,
        'phone'      => $phone,
        'address'    => $addr,
        'specific_address' => $specific_addr
    ]);
    header("Location: ../views/register-user.php?$qs");
    exit();
}

if(!preg_match("/^(09|\+639)[0-9]{9}$/", $phone)) {

    $qs = http_build_query([
        'error'      => 'phone',
        'first_name' => $first,
        'last_name'  => $last,
        'email'      => $email,
        'phone'      => $phone,
        'address'    => $addr,
        'specific_address' => $specific_addr
    ]);
    header("Location: ../views/register-user.php?$qs");
    exit();
}

if($pass !== $confirm) {
    $qs = http_build_query([
        'error'      => 'passmatch',
        'first_name' => $first,
        'last_name'  => $last,
        'email'      => $email,
        'phone'      => $phone,
        'address'    => $addr,
        'specific_address' => $specific_addr
    ]);
    header("Location: ../views/register-user.php?$qs");
    exit();
}

if(strlen($pass) < 8) {

    $qs = http_build_query([
        'error'      => 'passlength',
        'first_name' => $first,
        'last_name'  => $last,
        'email'      => $email,
        'phone'      => $phone,
        'address'    => $addr,
        'specific_address' => $specific_addr
    ]);
    header("Location: ../views/register-user.php?$qs");
    exit();
}

if(!preg_match("/[A-Z]/", $pass) ||
   !preg_match("/[a-z]/", $pass) ||
   !preg_match("/[0-9]/", $pass)) {

    $qs = http_build_query([
        'error'      => 'passweak',
        'first_name' => $first,
        'last_name'  => $last,
        'email'      => $email,
        'phone'      => $phone,
        'address'    => $addr,
        'specific_address' => $specific_addr
    ]);
    header("Location: ../views/register-user.php?$qs");
    exit();
}

$first = htmlspecialchars($first);
$last  = htmlspecialchars($last);
$email = htmlspecialchars($email);
$addr  = htmlspecialchars($addr);
$specific_addr = htmlspecialchars($specific_addr);

// if the barangay doesn't already start with "Brgy." or "Barangay" add the prefix
if (!preg_match('/^\s*(Brgy\.|Barangay)/i', $addr)) {
    $addr = 'Brgy. ' . $addr;
}

// Combine barangay and specific address
$full_address = $addr . ', ' . $specific_addr;

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/user.php';

// lowercase the email for consistency
$email = strtolower($email);

// check for existing account with same email
if (user_exists($conn, $email)) {
    // send back previous values
    $qs = http_build_query([
        'error' => 'emailtaken',
        'first_name' => $first,
        'last_name'  => $last,
        'email'      => $email,
        'phone'      => $phone,
        'address'    => $addr,
        'specific_address' => $specific_addr
    ]);
    header("Location: ../views/register-user.php?$qs");
    exit();
}

// Store pending registration in session (user created after phone verification)
$_SESSION['pending_registration'] = [
    'first_name' => $first,
    'last_name' => $last,
    'email' => $email,
    'phone' => $phone,
    'address' => $full_address,
    'password_hash' => password_hash($pass, PASSWORD_BCRYPT),
];

// Create verification code in separate table
require_once __DIR__ . '/sms-utils.php';
require_once __DIR__ . '/../models/verification_codes.php';

$verificationCode = create_verification_code($conn, $email, $phone, 'phone_verification');

if ($verificationCode) {
    // Send verification SMS
    $smsResult = sendVerificationCode($phone, $verificationCode);

    if ($smsResult['success']) {
        header("Location: ../views/verify-phone.php?email=" . urlencode($email));
    } else {
        // SMS failed, but pending registration saved - user can try resending
        header("Location: ../views/verify-phone.php?email=" . urlencode($email) . "&sms_error=1");
    }
} else {
    // Failed to create verification code
    $qs = http_build_query([
        'error' => 'db',
        'first_name' => $first,
        'last_name'  => $last,
        'email'      => $email,
        'phone'      => $phone,
        'address'    => $addr,
        'specific_address' => $specific_addr
    ]);
    header("Location: ../views/register-user.php?$qs");
}
exit();

?>
