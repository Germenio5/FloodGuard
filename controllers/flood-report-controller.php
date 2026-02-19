<?php
/**
 * Flood Report View Controller
 * Prepares data for the flood report submission form
 * Part of MVC pattern - this is the Controller layer
 */

session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header("Location: ../views/login-user.php?error=login_required");
    exit();
}

// Load database and user model to get user's address
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/user.php';

// Get user's address from database
$userFromDb = get_user_by_id($conn, $_SESSION['user_id']);
$userAddress = $userFromDb && !empty($userFromDb['address']) ? htmlspecialchars($userFromDb['address']) : '';

// Page metadata
$pageTitle = "Report Flood";
$pageSubtitle = "Help emergency responders by reporting flood incidents";

// Initialize form data and error variables
$formData = [
    'location' => $_GET['location'] ?? ($userAddress ? "Brgy. " . $userAddress : ''),
    'status' => $_GET['status'] ?? '',
    'description' => $_GET['description'] ?? '',
    'post_discussion' => isset($_GET['post_discussion']) ? 1 : 0
];

$errorMessage = "";
$successMessage = "";
$showForm = true;

// Handle error messages from POST redirect
if (isset($_GET['error'])) {
    $error = $_GET['error'];
    
    switch ($error) {
        case 'required':
            $errorMessage = "Please fill in all required fields (Location and Status).";
            break;
        
        case 'invalid_status':
            $errorMessage = "Please select a valid status (Safe or In Danger).";
            break;
        
        case 'db':
            $errorMessage = "Database error occurred while submitting your report. Please try again later.";
            break;
        
        case 'login_required':
            $errorMessage = "You must be logged in to submit a report.";
            break;
        
        case 'file_too_large':
            $errorMessage = "Photo file is too large. Maximum size is 5MB.";
            break;
        
        case 'invalid_file_type':
            $errorMessage = "Invalid photo format. Please upload JPG, PNG, or GIF.";
            break;
        
        case 'file_upload_failed':
            $errorMessage = "Failed to upload photo. Please try again.";
            break;
        
        default:
            $errorMessage = "An error occurred while processing your report.";
            break;
    }
}

// Handle success message
if (isset($_GET['success'])) {
    $successMessage = "Your flood report has been submitted successfully! Thank you for helping keep our community informed.";
    $showForm = false; // Hide form when success
}

// Valid status options
$statusOptions = [
    'Safe' => 'Safe',
    'In Danger' => 'In Danger',
    'Alert' => 'Alert',
    'Danger' => 'Danger'
];

// User information (from session)
$userName = $_SESSION['user_name'] ?? 'User';
$userEmail = $_SESSION['user_email'] ?? '';

// File upload configuration
$photoUploadDir = __DIR__ . '/../assets/images/reports/';
$maxFileSize = 5 * 1024 * 1024; // 5MB
$allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
$allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

// Create upload directory if it doesn't exist
if (!is_dir($photoUploadDir)) {
    @mkdir($photoUploadDir, 0755, true);
}

?>
