<?php
/**
 * Process Flood Report Controller
 * Handles flood report submission with photo upload
 * Part of MVC pattern - this is the Controller layer
 */

session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header("Location: ../views/login-user.php?error=login_required");
    exit();
}

// Include database connection
require_once __DIR__ . '/../config/config.php';

// Get form data from POST request
$location      = trim($_POST['location'] ?? '');
$status        = trim($_POST['status'] ?? '');
$description   = trim($_POST['description'] ?? '');
$postNews      = isset($_POST['post_discussion']) ? 1 : 0;
$imagePath     = null;

// Get user email from session
$userEmail     = $_SESSION['user_email'] ?? null;

// Validate required fields
if ($location === '' || $status === '' || $description === '') {
    $qs = http_build_query([
        'error' => 'required',
        'location' => $location,
        'status' => $status,
        'description' => $description
    ]);
    header("Location: ../views/user-report-flood.php?$qs");
    exit();
}

// Validate status field - must be one of the allowed values
$validStatuses = ['Safe', 'In Danger', 'Danger'];
if (!in_array($status, $validStatuses)) {
    $qs = http_build_query([
        'error' => 'invalid_status',
        'location' => $location,
        'status' => $status,
        'description' => $description
    ]);
    header("Location: ../views/user-report-flood.php?$qs");
    exit();
}

// Handle photo upload if provided
if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
    
    // File upload configuration
    $uploadDir = __DIR__ . '/../assets/images/reports/';
    $maxFileSize = 5 * 1024 * 1024; // 5MB
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
    
    // Create upload directory if it doesn't exist
    if (!is_dir($uploadDir)) {
        @mkdir($uploadDir, 0755, true);
    }
    
    $file = $_FILES['photo'];
    $fileName = $file['name'];
    $fileTmpPath = $file['tmp_name'];
    $fileSize = $file['size'];
    $fileType = mime_content_type($fileTmpPath);
    $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    
    // Validate file size
    if ($fileSize > $maxFileSize) {
        $qs = http_build_query([
            'error' => 'file_too_large',
            'location' => $location,
            'status' => $status,
            'description' => $description
        ]);
        header("Location: ../views/user-report-flood.php?$qs");
        exit();
    }
    
    // Validate file type
    if (!in_array($fileType, $allowedTypes) || !in_array($fileExtension, $allowedExtensions)) {
        $qs = http_build_query([
            'error' => 'invalid_file_type',
            'location' => $location,
            'status' => $status,
            'description' => $description
        ]);
        header("Location: ../views/user-report-flood.php?$qs");
        exit();
    }
    
    // Generate unique filename to prevent overwriting
    $newFileName = 'report_' . $_SESSION['user_id'] . '_' . time() . '.' . $fileExtension;
    $uploadPath = $uploadDir . $newFileName;
    
    // Move uploaded file
    if (move_uploaded_file($fileTmpPath, $uploadPath)) {
        $imagePath = 'assets/images/reports/' . $newFileName;
    } else {
        $qs = http_build_query([
            'error' => 'file_upload_failed',
            'location' => $location,
            'status' => $status,
            'description' => $description
        ]);
        header("Location: ../views/user-report-flood.php?$qs");
        exit();
    }
}

// Sanitize inputs
$location    = htmlspecialchars($location, ENT_QUOTES, 'UTF-8');
$status      = htmlspecialchars($status, ENT_QUOTES, 'UTF-8');
$description = htmlspecialchars($description, ENT_QUOTES, 'UTF-8');

// Prepare and execute INSERT statement with prepared statement for security
$stmt = $conn->prepare("INSERT INTO reports (user_email, location, status, description, image_path, post_news, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");

if (!$stmt) {
    error_log("Database prepare error: " . $conn->error);
    header("Location: ../views/user-report-flood.php?error=db");
    exit();
}

// Bind parameters: email (s), location (s), status (s), description (s), image_path (s), post_news (i)
$stmt->bind_param('sssssi', $userEmail, $location, $status, $description, $imagePath, $postNews);

// Execute the prepared statement
if ($stmt->execute()) {
    $stmt->close();
    
    // Report submitted successfully
    header("Location: ../views/user-report-flood.php?success=report_submitted");
    exit();
} else {
    error_log("Database execution error: " . $stmt->error);
    $stmt->close();
    
    // Database error during insertion
    header("Location: ../views/user-report-flood.php?error=db");
    exit();
}
