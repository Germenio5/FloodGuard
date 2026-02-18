<?php
session_start();

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header("Location: ../views/login-user.php?error=login_required");
    exit();
}

if (isset($_SESSION['user_role']) && $_SESSION['user_role'] !== 'admin') {
    header("Location: ../views/user-dashboard.php");
    exit();
}

require_once __DIR__ . '/../config/config.php';

// Pagination settings
$itemsPerPage = 10;
$currentPage = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
if ($currentPage < 1) $currentPage = 1;

// Get selected area from filter
$selectedArea = isset($_POST['area']) ? trim($_POST['area']) : '';

// Build count query based on area selection
$countQuery = "SELECT COUNT(*) as total FROM users WHERE role = 'user'";
if (!empty($selectedArea)) {
    $selectedArea_escaped = $conn->real_escape_string($selectedArea);
    $countQuery .= " AND address LIKE '%$selectedArea_escaped%'";
}

$countResult = $conn->query($countQuery);
$countRow = $countResult->fetch_assoc();
$totalRecords = (int)$countRow['total'];
$totalPages = max(1, ceil($totalRecords / $itemsPerPage));

// Ensure current page doesn't exceed total pages
if ($currentPage > $totalPages) {
    $currentPage = $totalPages;
}

// Calculate offset
$offset = ($currentPage - 1) * $itemsPerPage;

// Build query based on area selection with pagination
$query = "SELECT id, first_name, last_name, email, phone, address FROM users WHERE role = 'user'";
if (!empty($selectedArea)) {
    $selectedArea = $conn->real_escape_string($selectedArea);
    $query .= " AND address LIKE '%$selectedArea%'";
}
$query .= " ORDER BY first_name ASC LIMIT $itemsPerPage OFFSET $offset";

// Fetch residents from database
$residents = [];
$result = $conn->query($query);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $residents[] = [
            'id' => $row['id'],
            'name' => htmlspecialchars($row['first_name'] . ' ' . $row['last_name']),
            'address' => htmlspecialchars($row['address']),
            'phone' => htmlspecialchars($row['phone']),
            'email' => htmlspecialchars($row['email']),
            'status' => 'Safe' // Default status (can be extended with status column)
        ];
    }
    $result->free();
}

// Get all unique areas for dropdown
$areaQuery = "SELECT DISTINCT address FROM users WHERE role = 'user' ORDER BY address ASC";
$areaResult = $conn->query($areaQuery);
$areas = [];

if ($areaResult && $areaResult->num_rows > 0) {
    while ($row = $areaResult->fetch_assoc()) {
        if (!empty($row['address'])) {
            $areas[] = htmlspecialchars($row['address']);
        }
    }
    $areaResult->free();
}

// Calculate statistics
$totalResidents = count($residents);

// Get total registered users
$countQuery = "SELECT COUNT(*) as total FROM users WHERE role = 'user'";
$countResult = $conn->query($countQuery);
$countRow = $countResult->fetch_assoc();
$registeredCount = (int)$countRow['total'];

// For now, we'll use a basic distribution (can be enhanced with actual status column)
$stats = [
    "safe" => max(0, floor($registeredCount * 0.6)),
    "danger" => max(0, floor($registeredCount * 0.2)),
    "registered" => $registeredCount,
    "no_response" => max(0, $registeredCount - floor($registeredCount * 0.8))
];

// Helper function for status badge
function getStatusClass($status) {
    $status = strtolower(trim($status));
    if (strpos($status, 'danger') !== false || strpos($status, 'alert') !== false) {
        return "status-danger";
    }
    return "status-safe";
}

// Generate pagination buttons
function generatePaginationButtons($currentPage, $totalPages) {
    $buttons = [];
    
    // Previous button
    if ($currentPage > 1) {
        $buttons[] = ['page' => $currentPage - 1, 'label' => 'Previous', 'active' => false, 'disabled' => false];
    } else {
        $buttons[] = ['page' => 1, 'label' => 'Previous', 'active' => false, 'disabled' => true];
    }
    
    // Page buttons (show up to 5 pages and dots)
    $startPage = max(1, $currentPage - 2);
    $endPage = min($totalPages, $currentPage + 2);
    
    if ($startPage > 1) {
        $buttons[] = ['page' => 1, 'label' => '1', 'active' => false, 'disabled' => false];
        if ($startPage > 2) {
            $buttons[] = ['page' => null, 'label' => '...', 'active' => false, 'disabled' => true];
        }
    }
    
    for ($i = $startPage; $i <= $endPage; $i++) {
        $buttons[] = ['page' => $i, 'label' => $i, 'active' => ($i == $currentPage), 'disabled' => false];
    }
    
    if ($endPage < $totalPages) {
        if ($endPage < $totalPages - 1) {
            $buttons[] = ['page' => null, 'label' => '...', 'active' => false, 'disabled' => true];
        }
        $buttons[] = ['page' => $totalPages, 'label' => $totalPages, 'active' => false, 'disabled' => false];
    }
    
    // Next button
    if ($currentPage < $totalPages) {
        $buttons[] = ['page' => $currentPage + 1, 'label' => 'Next', 'active' => false, 'disabled' => false];
    } else {
        $buttons[] = ['page' => $totalPages, 'label' => 'Next', 'active' => false, 'disabled' => true];
    }
    
    return $buttons;
}

$paginationButtons = generatePaginationButtons($currentPage, $totalPages);

?>

