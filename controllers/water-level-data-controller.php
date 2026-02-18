<?php
/**
 * Water Level Data Controller
 * Handles pagination and display of water level history
 */

// Load database connection
require_once __DIR__ . '/../config/config.php';

// Pagination settings
$itemsPerPage = 10;
$currentPage = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
if ($currentPage < 1) $currentPage = 1;

// Get total count of water level records
$countQuery = "SELECT COUNT(*) as total FROM water_level_history";
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

// Fetch water level history with pagination
$waterLevels = [];
$query = "SELECT id, area, trend, record_time, height, speed, status
          FROM water_level_history
          ORDER BY record_time DESC
          LIMIT $itemsPerPage OFFSET $offset";

if ($result = $conn->query($query)) {
    while ($row = $result->fetch_assoc()) {
        // Create a user-friendly date string
        $row['date'] = date('m/d/Y H:i', strtotime($row['record_time']));
        // Convert numeric fields to appropriate types
        $row['height'] = (float) $row['height'];
        $row['speed'] = (float) $row['speed'];
        $waterLevels[] = $row;
    }
    $result->free();
} else {
    error_log("Water level query failed: " . $conn->error);
}

// Fallback example if no data available
if (empty($waterLevels)) {
    $waterLevels = [
        [
            "id" => 1,
            "area" => "Eroreco Bridge",
            "trend" => "steady",
            "date" => "02/19/2026 08:00",
            "height" => "2.3",
            "speed" => "0.3",
            "status" => "normal",
            "record_time" => "2026-02-19 08:00:00"
        ],
        [
            "id" => 2,
            "area" => "Eroreco Bridge",
            "trend" => "rising",
            "date" => "02/19/2026 09:00",
            "height" => "2.6",
            "speed" => "0.5",
            "status" => "alert",
            "record_time" => "2026-02-19 09:00:00"
        ],
        [
            "id" => 3,
            "area" => "Eroreco Bridge",
            "trend" => "rising",
            "date" => "02/19/2026 10:00",
            "height" => "3.1",
            "speed" => "0.7",
            "status" => "danger",
            "record_time" => "2026-02-19 10:00:00"
        ]
    ];
    $totalRecords = 1;
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
        case "normal":
            return '<span class="status status-normal">● Normal</span>';
        case "alert":
            return '<span class="status status-alert">● Alert</span>';
        case "danger":
            return '<span class="status status-danger">● Danger</span>';
        default:
            return '<span class="status">Unknown</span>';
    }
}
?>

