<?php

session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header("Location: login-user.php?error=login_required");
    exit();
}

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/affected_areas.php';


// Filter by barangay
$selectedBarangay = isset($_GET['barangay']) ? trim($_GET['barangay']) : '';

// Pagination settings
$itemsPerPage = 6;
$currentPage = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
if ($currentPage < 1) $currentPage = 1;

if ($selectedBarangay) {
    $allAreas = get_affected_areas_by_location($conn, $selectedBarangay);
    $totalRecords = count($allAreas);
    $totalPages = max(1, ceil($totalRecords / $itemsPerPage));
    $offset = ($currentPage - 1) * $itemsPerPage;
    $bridges_data = array_slice($allAreas, $offset, $itemsPerPage);
} else {
    $totalRecords = get_affected_areas_count($conn);
    $totalPages = max(1, ceil($totalRecords / $itemsPerPage));
    $offset = ($currentPage - 1) * $itemsPerPage;
    $bridges_data = get_affected_areas_paginated($conn, $itemsPerPage, $offset);
}

// Transform data for view
$bridges = [];
if ($bridges_data) {
    foreach ($bridges_data as $area) {
        $current = (float)$area['current_level'];
        $max = (float)$area['max_level'];
        $percentage = ($max > 0) ? min(100, ($current / $max) * 100) : 0;
        
        // Determine status class based on percentage thresholds
        if ($percentage < 30) {
            $statusClass = 'warning';
        } elseif ($percentage < 75) {
            $statusClass = 'danger';
        } else {
            $statusClass = 'critical';
        }
        
        $bridges[] = [
            'name' => htmlspecialchars($area['name']),
            'location' => htmlspecialchars($area['location']),
            'current_level' => $current,
            'max_level' => $max,
            'speed' => (float)$area['speed'],
            'status' => $statusClass,
            'percentage' => round($percentage, 1)
        ];
    }
}

// Fallback to empty array if no data
if (empty($bridges)) {
    $bridges = [];
    $totalRecords = 0;
    $totalPages = 1;
}

$barangays = get_unique_barangays($conn);

// Generate admin-report-log style pagination buttons
function generatePaginationButtons($currentPage, $totalPages, $selectedBarangay) {
    $buttons = [];
    $baseUrl = $_SERVER['PHP_SELF'];
    $query = $selectedBarangay ? ('?barangay=' . urlencode($selectedBarangay)) : '?';
    $query .= ($selectedBarangay ? '&' : '') . 'page=';

    // Previous
    if ($currentPage > 1) {
        $buttons[] = ['page' => $currentPage - 1, 'label' => 'Previous', 'active' => false, 'disabled' => false];
    } else {
        $buttons[] = ['page' => 1, 'label' => 'Previous', 'active' => false, 'disabled' => true];
    }
    // Current
    $buttons[] = ['page' => $currentPage, 'label' => $currentPage, 'active' => true, 'disabled' => false];
    // Next
    if ($currentPage < $totalPages) {
        $buttons[] = ['page' => $currentPage + 1, 'label' => 'Next', 'active' => false, 'disabled' => false];
    } else {
        $buttons[] = ['page' => $totalPages, 'label' => 'Next', 'active' => false, 'disabled' => true];
    }
    return $buttons;
}

$paginationButtons = generatePaginationButtons($currentPage, $totalPages, $selectedBarangay);


/**
 * Map database status values to CSS class names
 * 
 * @param string $status Status from database (normal, alert, danger)
 * @return string CSS class name (warning, danger, critical)
 */
function mapStatusClass($status) {
    $status = strtolower(trim($status));
    switch ($status) {
        case 'danger':
            return 'critical';
        case 'alert':
            return 'danger';
        case 'normal':
        default:
            return 'warning';
    }
}

/**
 * Get progress fill class for styling
 * 
 * @param string $status Status class name
 * @return string CSS class
 */
function getProgressClass($status) {
    return "progress-" . $status;
}

/**
 * Get status label for display
 * 
 * @param string $status Status class name
 * @return string Display label
 */
function getStatusLabel($status) {
    switch ($status) {
        case 'warning':
            return 'Warning';
        case 'danger':
            return 'Danger';
        case 'critical':
            return 'Critical';
        default:
            return 'Warning';
    }
}

/**
 * Calculate water level percentage
 * 
 * @param float $current Current water level
 * @param float $max Maximum water level
 * @return float Percentage value
 */
function getPercentage($current, $max) {
    return ($max > 0) ? ($current / $max) * 100 : 0;
}

?>