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
require_once __DIR__ . '/../models/user.php';

// Pagination settings
$itemsPerPage = 10;
$currentPage = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
if ($currentPage < 1) $currentPage = 1;

// Get selected barangay from filter. We accept POST (form submission) or GET (pagination links).
$selectedBarangay = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['barangay'])) {
    $selectedBarangay = trim($_POST['barangay']);
} elseif (isset($_GET['barangay'])) {
    $selectedBarangay = trim($_GET['barangay']);
}

// Get total count using model (filters by barangay substring)
$totalRecords = get_users_count($conn, $selectedBarangay);
$totalPages = max(1, ceil($totalRecords / $itemsPerPage));

// Ensure current page doesn't exceed total pages
if ($currentPage > $totalPages) {
    $currentPage = $totalPages;
}

// Calculate offset
$offset = ($currentPage - 1) * $itemsPerPage;

// Fetch residents using model
$users_data = get_users_paginated($conn, $itemsPerPage, $offset, $selectedBarangay);

// Transform data for view
$residents = [];
if ($users_data) {
    foreach ($users_data as $row) {
        $residents[] = [
            'id' => (int)$row['id'],
            'name' => htmlspecialchars($row['first_name'] . ' ' . $row['last_name']),
            'address' => htmlspecialchars($row['address']),
            'phone' => htmlspecialchars($row['phone']),
            'email' => htmlspecialchars($row['email']),
            'status' => !empty($row['status']) ? htmlspecialchars($row['status']) : 'Safe'
        ];
    }
}

// Get all unique barangays for dropdown using model
$barangays_data = get_unique_user_addresses($conn);
$barangays = array_map(function($addr) { return htmlspecialchars($addr); }, $barangays_data);

// Calculate statistics
$totalResidents = count($residents);
$registeredCount = get_users_count($conn);

// For now, use basic distribution (can be enhanced with actual status column)
$stats = [
    "safe" => max(0, floor($registeredCount * 0.6)),
    "danger" => max(0, floor($registeredCount * 0.2)),
    "registered" => $registeredCount
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

    // Only show the current page button
    $buttons[] = ['page' => $currentPage, 'label' => $currentPage, 'active' => true, 'disabled' => false];

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

