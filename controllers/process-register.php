<?php
$first    = trim($_POST['first_name'] ?? "");
$last     = trim($_POST['last_name'] ?? "");
$email    = trim($_POST['email'] ?? "");
$phone    = trim($_POST['phone'] ?? "");
$addr     = trim($_POST['address'] ?? "");
$pass     = trim($_POST['password'] ?? "");
$confirm  = trim($_POST['confirm_password'] ?? "");

if($first=="" || $last=="" || $email=="" ||
   $phone=="" || $addr=="" || $pass=="" || $confirm=="") {

    $qs = http_build_query([
        'error'      => 'empty',
        'first_name' => $first,
        'last_name'  => $last,
        'email'      => $email,
        'phone'      => $phone,
        'address'    => $addr
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
        'address'    => $addr
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
        'address'    => $addr
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
        'address'    => $addr
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
        'address'    => $addr
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
        'address'    => $addr
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
        'address'    => $addr
    ]);
    header("Location: ../views/register-user.php?$qs");
    exit();
}

$first = htmlspecialchars($first);
$last  = htmlspecialchars($last);
$email = htmlspecialchars($email);
$addr  = htmlspecialchars($addr);

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
        'address'    => $addr
    ]);
    header("Location: ../views/register-user.php?$qs");
    exit();
}

// try to insert new user
$created = create_user($conn, $first, $last, $email, $phone, $addr, $pass);
if ($created) {
    header("Location: ../views/register-user.php?success=created");
} else {
    $qs = http_build_query([
        'error' => 'db',
        'first_name' => $first,
        'last_name'  => $last,
        'email'      => $email,
        'phone'      => $phone,
        'address'    => $addr
    ]);
    header("Location: ../views/register-user.php?$qs");
}
exit();

?>
