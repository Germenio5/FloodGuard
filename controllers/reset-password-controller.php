<?php
$userEmail = $_GET['email'] ?? '';
$errorMessage = $_GET['error'] ?? '';

if (!$userEmail) {
    header("Location: ../views/login-user.php");
    exit();
}

// Convert error codes to user-friendly messages
if ($errorMessage) {
    switch ($errorMessage) {
        case 'empty':
            $errorMessage = 'Please fill in all fields.';
            break;
        case 'password_mismatch':
            $errorMessage = 'Passwords do not match.';
            break;
        case 'password_short':
            $errorMessage = 'Password must be at least 8 characters long.';
            break;
        case 'passweak':
            $errorMessage = 'Password must contain at least one uppercase letter, one lowercase letter, and one number.';
            break;
    }
}
?>