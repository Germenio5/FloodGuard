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

// Barangay list (reuse from registration logic or define here)
$barangays = [
    'Alijis', 'Banago', 'Bata', 'Barangay 1', 'Barangay 2', 'Barangay 3', 'Barangay 4', 'Barangay 5',
    'Barangay 6', 'Barangay 7', 'Barangay 8', 'Barangay 9', 'Barangay 10', 'Barangay 11', 'Barangay 12',
    'Barangay 13', 'Barangay 14', 'Barangay 15', 'Barangay 16', 'Barangay 17', 'Barangay 18', 'Barangay 19',
    'Barangay 20', 'Barangay 21', 'Barangay 22', 'Barangay 23', 'Barangay 24', 'Barangay 25', 'Barangay 26',
    'Barangay 27', 'Barangay 28', 'Barangay 29', 'Barangay 30', 'Barangay 31', 'Barangay 32', 'Barangay 33',
    'Estefania', 'Granada', 'Handumanan', 'Mandalagan', 'Mansilingan', 'Montevista', 'Punta Taytay',
    'Sum-ag', 'Taculing', 'Tangub', 'Villamonte', 'Vista Alegre'
];

// Split address for form compatibility
$barangay = '';
$specific_address = '';
if (!empty($userFromDb['address'])) {
    if (preg_match('/^Brgy\.\s*([^,]+),?\s*(.*)$/i', $userFromDb['address'], $matches)) {
        $barangay = trim($matches[1]);
        $specific_address = trim($matches[2]);
    } else {
        $specific_address = $userFromDb['address'];
    }
}

// Convert profile_photo blob to data URI if exists
$profilePhotoSrc = '../assets/images/placeholder-profile.png';
if (!empty($userFromDb['profile_photo'])) {
    $profilePhotoSrc = 'data:image/jpeg;base64,' . base64_encode($userFromDb['profile_photo']);
}

$userData = [
    'id' => $userFromDb['id'],
    'first_name' => $userFromDb['first_name'],
    'last_name' => $userFromDb['last_name'],
    'email' => $userFromDb['email'],
    'phone' => $userFromDb['phone'],
    'address' => $userFromDb['address'],
    'barangay' => $barangay,
    'specific_address' => $specific_address,
    'profile_photo' => $profilePhotoSrc,
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
            $errorMessage = "Profile photo is too large. Maximum size is 15MB.";
            break;
        case 'invalid_file_type':
            $errorMessage = "Invalid photo format. Please upload JPG or PNG.";
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
