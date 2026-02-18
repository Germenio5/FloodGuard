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

$loginTitle    = "Log In";
$loginSubtitle = "Input your credentials below.";

$errorMessage = "";
$oldEmail = $_GET['email'] ?? "";

if (isset($_GET['error'])) {
    $error = $_GET['error'];
    
    switch ($error) {
        case "invalid":
            $errorMessage = "Invalid email or password. Please try again.";
            break;
        
        case "empty":
            $errorMessage = "Please fill out all fields.";
            break;
        
        case "session":
            $errorMessage = "Session error. Please log in again.";
            break;
        
        default:
            $errorMessage = "An error occurred. Please try again.";
            break;
    }
}

$formAction = "../controllers/process-login.php";
$userRole   = "user";

$dbConnected = false;
if (file_exists(__DIR__ . '/../config/config.php')) {
    @require_once __DIR__ . '/../config/config.php';
    if (isset($conn) && $conn->ping()) {
        $dbConnected = true;
    }
}

?>
