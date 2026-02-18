<?php
/**
 * Update User Profile Controller
 * Handles profile form submissions including photo uploads
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
$profilePhotoPath = null;

// Validate form input
$firstName = isset($_POST['first_name']) ? trim($_POST['first_name']) : '';
$lastName = isset($_POST['last_name']) ? trim($_POST['last_name']) : '';
$address = isset($_POST['address']) ? trim($_POST['address']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';

// Required field validation
if (empty($firstName) || empty($lastName) || empty($address) || empty($email) || empty($phone)) {
    $errors[] = 'required';
}

// Email validation
if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'email_invalid';
}

// Phone validation (basic PHP format check)
if (!empty($phone) && !preg_match('/^(\+?\d{1,3}[\s.-]?)?\d{9,10}$/', $phone)) {
    $errors[] = 'phone_invalid';
}

// Handle photo upload if file is provided
if (!empty($_FILES['profile_photo']['name'])) {
    $file = $_FILES['profile_photo'];
    $maxSize = 2 * 1024 * 1024; // 2MB
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    
    // File size validation
    if ($file['size'] > $maxSize) {
        $errors[] = 'file_too_large';
    }
    // File type validation
    elseif (!in_array($file['type'], $allowedTypes)) {
        $errors[] = 'invalid_file_type';
    }
    // If no errors, process the file
    else {
        // Create uploads directory if it doesn't exist
        $uploadDir = __DIR__ . '/../assets/images/profiles/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        // Generate unique filename with timestamp
        $fileExt = pathinfo($file['name'], PATHINFO_EXTENSION);
        $newFileName = 'profile_' . $userId . '_' . time() . '.' . $fileExt;
        $uploadPath = $uploadDir . $newFileName;
        
        // Move uploaded file
        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            // Store relative path for database
            $profilePhotoPath = '../assets/images/profiles/' . $newFileName;
        } else {
            $errors[] = 'upload_failed';
        }
    }
}

// If there are errors, redirect with error message
if (!empty($errors)) {
    $errorCode = reset($errors);
    header("Location: ../views/user-profile-settings.php?error=" . urlencode($errorCode));
    exit();
}

// Fetch current user data to check for changes
$currentUser = get_user_by_id($conn, $userId);

if (!$currentUser) {
    header("Location: ../views/user-profile-settings.php?error=db");
    exit();
}

// Check if any data has changed
$hasChanges = false;
if ($firstName !== $currentUser['first_name'] || 
    $lastName !== $currentUser['last_name'] || 
    $address !== $currentUser['address'] || 
    $email !== $currentUser['email'] || 
    $phone !== $currentUser['phone'] || 
    !empty($profilePhotoPath)) {
    $hasChanges = true;
}

// If no changes were made, redirect with message
if (!$hasChanges) {
    header("Location: ../views/user-profile-settings.php?error=no_changes");
    exit();
}

// Prepare data for update
$updateData = [
    'first_name' => $firstName,
    'last_name' => $lastName,
    'address' => $address,
    'email' => $email,
    'phone' => $phone
];

// Add profile photo to data if uploaded
if (!empty($profilePhotoPath)) {
    $updateData['profile_photo'] = $profilePhotoPath;
}

// Update user in database
try {
    $result = update_user($conn, $userId, $updateData);
    
    if ($result) {
        // Update session variables with new data
        $_SESSION['user_email'] = $email;
        $_SESSION['user_name'] = $firstName . ' ' . $lastName;
        
        // Redirect with success message
        header("Location: ../views/user-profile-settings.php?success=true");
        exit();
    } else {
        throw new Exception("Update failed");
    }
} catch (Exception $e) {
    // Log error
    error_log("Profile update error for user " . $userId . ": " . $e->getMessage());
    
    // Redirect with error message
    header("Location: ../views/user-profile-settings.php?error=db");
    exit();
}

?>
