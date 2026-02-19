<?php
/**
 * Water Level Data Controller
 * Handles pagination and display of water level history
 */

session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header("Location: login-user.php?error=login_required");
    exit();
}

// Load database connection
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/water_level.php';

// Pagination settings
$itemsPerPage = 10;
$currentPage = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
if ($currentPage < 1) $currentPage = 1;

// Get selected bridge filter
$selectedBridge = isset($_GET['bridge']) ? trim($_GET['bridge']) : '';

// Get total count and calculate pages
$totalRecords = $selectedBridge ? get_water_levels_count($conn, $selectedBridge) : get_water_levels_count($conn);
$totalPages = max(1, ceil($totalRecords / $itemsPerPage));

// Ensure current page doesn't exceed total pages
if ($currentPage > $totalPages) {
    $currentPage = $totalPages;
}

// Calculate offset
$offset = ($currentPage - 1) * $itemsPerPage;

// Fetch water level history using model
$waterLevels_data = $selectedBridge 
    ? get_water_levels_by_area($conn, $selectedBridge, 1000) // Get all for filtering
    : get_water_levels_paginated($conn, null, $itemsPerPage, $offset);

// Transform data for view
$waterLevels = [];
if ($waterLevels_data) {
    // For filtered results, slice to current page
    if ($selectedBridge && is_array($waterLevels_data)) {
        $waterLevels_data = array_slice($waterLevels_data, $offset, $itemsPerPage);
    }
    
    foreach ($waterLevels_data as $row) {
        // Create a user-friendly date string
        $row['date'] = date('m/d/Y H:i', strtotime($row['record_time']));
        // Convert numeric fields to appropriate types
        $row['height'] = (float) $row['height'];
        $row['speed'] = (float) $row['speed'];
        $row['area'] = htmlspecialchars($row['area']);
        $row['status'] = htmlspecialchars($row['status']);
        $row['trend'] = htmlspecialchars($row['trend'] ?? 'steady');
        $waterLevels[] = $row;
    }
}

// Get unique areas/bridges for filter dropdown
$allAreas = [];
$allAreasData = get_water_levels_paginated($conn, null, 1000, 0);
if ($allAreasData) {
    foreach ($allAreasData as $row) {
        $area = $row['area'];
        if (!in_array($area, $allAreas)) {
            $allAreas[] = $area;
        }
    }
    sort($allAreas);
}

// Fallback to empty array if no data
if (empty($waterLevels)) {
    $waterLevels = [];
    $totalRecords = 0;
    $totalPages = 1;
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

function getTrendBadge($trend) {
    switch ($trend) {
        case "steady":
            return '<span class="badge badge-steady">🟢 Steady</span>';
        case "rising":
            return '<span class="badge badge-rising">📈 Rising</span>';
        case "falling":
            return '<span class="badge badge-falling">📉 Falling</span>';
        default:
            return '<span class="badge">Unknown</span>';
    }
}

function getStatusBadge($status) {
    switch ($status) {
        case "warning":
            return '<span class="status status-warning">● Warning</span>';
        case "danger":
            return '<span class="status status-danger">● Danger</span>';
        case "critical":
            return '<span class="status status-critical">● Critical</span>';
        default:
            return '<span class="status">Unknown</span>';
    }
}
?>

