<?php
session_start();

$email    = $_POST['email'] ?? "";
$password = $_POST['password'] ?? "";

if($email == "" || $password == "") {
    header("Location: login.php?error=empty");
    exit();
}

// ADMIN CHECK (No Database Yet)
$adminEmail = "admin@floodguard.com";
$adminPass  = "admin123";

if($email === $adminEmail && $password === $adminPass) {

    $_SESSION['role']  = "admin";
    $_SESSION['email'] = $adminEmail;

    header("Location: ../admin/dashboard.php"); // ADMIN DASHBOARD
    exit();
}

// USER CHECK (No Database Yet)
if($email === "user" && $password === "1234") {

    $_SESSION['role']  = "user";
    $_SESSION['email'] = $email;

    header("Location: ../view/user-dashboard.php"); // USER DASHBOARD
    exit();

}
else {

    header("Location: ../view/login-user.php?error=invalid");
    exit();

}

?>
