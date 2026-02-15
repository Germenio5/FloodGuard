<?php
session_start();

$email    = trim($_POST['email'] ?? "");
$password = $_POST['password'] ?? "";

if($email == "" || $password == "") {
    header("Location: ../views/login-user.php?error=empty");
    exit();
}

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/user.php';

// attempt authentication (email or phone)
$user = authenticate_user($conn, $email, $password);
if ($user) {
    $_SESSION['role']  = $user['role'];
    $_SESSION['email'] = $user['email'];

    if ($user['role'] === 'admin') {
        header("Location: ../views/admin-dashboard.php");
    } else {
        header("Location: ../views/user-dashboard.php");
    }
    exit();
} else {
    $qs = http_build_query([
        'error' => 'invalid',
        'email' => $email
    ]);
    header("Location: ../views/login-user.php?$qs");
    exit();
}

?>
