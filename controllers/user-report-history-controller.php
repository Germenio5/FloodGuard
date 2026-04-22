<?php
/**
 * User Report History View Controller
 * Prepares data for the report history page showing all user-submitted reports
 */

session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header("Location: ../views/login-user.php?error=login_required");
    exit();
}

// Load database and models
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/reports.php';
require_once __DIR__ . '/../models/user.php';

// Get user info from session
$user_id = $_SESSION['user_id'];
$user_email = $_SESSION['user_email'] ?? '';
$user_name = $_SESSION['user_name'] ?? 'User';

// Get user details from database
$user_from_db = get_user_by_id($conn, $user_id);

// Page metadata
$pageTitle = "Report History";
$pageSubtitle = "View all your submitted flood reports and admin responses";

// Pagination setup
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$limit = 10; // Reports per page
$offset = ($page - 1) * $limit;

// Get user's report history
$user_reports = get_user_report_history($conn, $user_email, $limit, $offset);
$total_reports = get_user_report_count($conn, $user_email);
$total_pages = ceil($total_reports / $limit);

// Get report statistics
$report_stats = get_user_report_stats($conn, $user_email);

// Handle error messages
$errorMessage = "";
$successMessage = "";

if (isset($_GET['error'])) {
    switch ($_GET['error']) {
        case 'not_found':
            $errorMessage = "Report not found.";
            break;
        case 'unauthorized':
            $errorMessage = "You do not have permission to view this report.";
            break;
        case 'pdf_error':
            $errorMessage = "Error generating PDF. Please try again.";
            break;
        default:
            $errorMessage = "An error occurred.";
    }
}

if (isset($_GET['success'])) {
    switch ($_GET['success']) {
        case 'pdf_downloaded':
            $successMessage = "PDF report downloaded successfully.";
            break;
        default:
            $successMessage = "Operation completed successfully.";
    }
}

// Function to format date in GMT+8 timezone
function formatDate($date) {
    $gmt8 = new DateTimeZone('Asia/Manila');
    $dt = new DateTime($date, new DateTimeZone('UTC'));
    $dt->setTimezone($gmt8);
    return $dt->format('M d, Y h:i A');
}

// Function to get badge class based on response status
function getResponseBadgeClass($is_responded) {
    return $is_responded ? 'badge-responded' : 'badge-pending';
}

// Function to get response status text
function getResponseStatusText($is_responded) {
    return $is_responded ? 'Responded' : 'Pending';
}

// Function to get status badge class
function getStatusBadgeClass($status) {
    switch (strtolower($status)) {
        case 'danger':
            return 'status-danger';
        case 'alert':
            return 'status-alert';
        case 'safe':
            return 'status-safe';
        default:
            return 'status-default';
    }
}

?>
