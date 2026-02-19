<?php
session_start();

if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header("Location: login-user.php?error=login_required");
    exit();
}

$pageTitle       = "Map";
$pageDescription = "Current Flood Forecast to get an idea of current flooding";
?>