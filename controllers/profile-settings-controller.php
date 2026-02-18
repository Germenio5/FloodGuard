<?php
/**
 * User Profile Settings Controller
 * Fetches and prepares user profile data
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

// Get current user ID from session
$userId = $_SESSION['user_id'];

// Fetch user data from database
$userFromDb = get_user_by_id($conn, $userId);

if (!$userFromDb) {
    // User not found, logout
    session_destroy();
    header("Location: ../views/login-user.php?error=session");
    exit();
}

// Initialize form data with database values
$userData = [
    'id' => $userFromDb['id'],
    'first_name' => $userFromDb['first_name'],
    'last_name' => $userFromDb['last_name'],
    'email' => $userFromDb['email'],
    'phone' => $userFromDb['phone'],
    'address' => $userFromDb['address'],
    'profile_photo' => $userFromDb['profile_photo'] ?? '../assets/images/placeholder-profile.png',
    'created_at' => $userFromDb['created_at']
];

// Handle success/error messages from profile update
$successMessage = "";
$errorMessage = "";

if (isset($_GET['success'])) {
    $successMessage = "Profile updated successfully!";
}

if (isset($_GET['error'])) {
    $error = $_GET['error'];
    switch ($error) {
        case 'required':
            $errorMessage = "Please fill in all required fields.";
            break;
        case 'email_invalid':
            $errorMessage = "Invalid email address format.";
            break;
        case 'phone_invalid':
            $errorMessage = "Invalid phone number format.";
            break;
        case 'email_taken':
            $errorMessage = "This email is already registered.";
            break;
        case 'db':
            $errorMessage = "Database error. Please try again later.";
            break;
        case 'file_too_large':
            $errorMessage = "Profile photo is too large. Maximum size is 2MB.";
            break;
        case 'invalid_file_type':
            $errorMessage = "Invalid photo format. Please upload JPG, PNG, or GIF.";
            break;
        case 'no_changes':
            $errorMessage = "No changes were made to your profile.";
            break;
        default:
            $errorMessage = "An error occurred. Please try again.";
            break;
    }
}

?>
