<?php
session_start();

if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
    $role = $_SESSION['user_role'] ?? 'user';

    if ($role === 'admin') {
        header("Location: ../views/admin-dashboard.php");
    } else {
        header("Location: ../views/user-dashboard.php");
    }
    exit();
}

$oldEmail = $_GET['email'] ?? "";
$errorMessage = "";

// Handle error messages
if (isset($_GET['error'])) {
    $error = $_GET['error'];

    switch ($error) {
        case "forgot_empty":
            $errorMessage = "Please enter your email address.";
            break;

        case "forgot_not_found":
            $errorMessage = "No account found with that email address.";
            break;

        case "forgot_unverified":
            $errorMessage = "Your phone number is not verified. Please verify your phone first.";
            break;

        case "forgot_sms_failed":
            $errorMessage = "Failed to send OTP. Please try again later.";
            break;

        default:
            $errorMessage = "An error occurred. Please try again.";
            break;
    }
}

$formAction = "../controllers/forgot-password.php";
?>