<?php
/**
 * Change User Password Controller
 * Handles password change requests with validation
 */

session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header("Location: ../views/login-user.php?error=login_required");
    exit();
}

// Include database connection and user model
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/user.php';

// Initialize variables
$userId = $_SESSION['user_id'];
$errors = [];

// Validate form input
$currentPassword = isset($_POST['current_password']) ? $_POST['current_password'] : '';
$newPassword = isset($_POST['new_password']) ? $_POST['new_password'] : '';
$confirmPassword = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';

// Required field validation
if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
    $errors[] = 'required';
}

// Check if passwords match
if ($newPassword !== $confirmPassword) {
    $errors[] = 'password_mismatch';
}

// Validate password strength (min 8 chars, at least one uppercase, one lowercase, one number)
if (!empty($newPassword) && !preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/', $newPassword)) {
    $errors[] = 'weak_password';
}

// If there are validation errors, redirect
if (!empty($errors)) {
    $errorCode = reset($errors);
    header("Location: ../views/user-profile-settings.php?error=" . urlencode($errorCode) . "#passwordModal");
    exit();
}

// Attempt to change password
try {
    $result = change_user_password($conn, $userId, $currentPassword, $newPassword);
    
    if ($result) {
        // Redirect with success message
        header("Location: ../views/user-profile-settings.php?success=password");
        exit();
    } else {
        // Password change failed (likely wrong current password)
        header("Location: ../views/user-profile-settings.php?error=wrong_password#passwordModal");
        exit();
    }
} catch (Exception $e) {
    // Log error
    error_log("Password change error for user " . $userId . ": " . $e->getMessage());
    
    // Redirect with error message
    header("Location: ../views/user-profile-settings.php?error=db#passwordModal");
    exit();
}

?>
