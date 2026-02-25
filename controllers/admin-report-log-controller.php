<?php

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/reports.php';

// Get selected location filter
$selectedLocation = isset($_GET['location']) ? trim($_GET['location']) : '';

// Fetch reports from database using model with filter

// Pagination setup
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$limit = 10; // Items per page
$offset = ($page - 1) * $limit;

if ($selectedLocation) {
    $reports_data = get_reports_by_location($conn, $selectedLocation, $limit, $offset);
    $total_reports = get_reports_count_by_location($conn, $selectedLocation);
} else {
    $reports_data = get_all_reports($conn, $limit, $offset);
    $total_reports = get_reports_count($conn);
}

$total_pages = $total_reports > 0 ? ceil($total_reports / $limit) : 1;

// Generate minimal pagination buttons
function generatePaginationButtons($page, $total_pages, $selectedLocation) {
    $buttons = [];
    $baseUrl = $_SERVER['PHP_SELF'];
    $query = $selectedLocation ? ('?location=' . urlencode($selectedLocation)) : '?';
    $query .= ($selectedLocation ? '&' : '') . 'page=';

    // Previous
    if ($page > 1) {
        $buttons[] = '<a class="pagination-btn" href="' . $baseUrl . $query . ($page - 1) . '">Previous</a>';
    }
    // Current
    $buttons[] = '<span class="pagination-btn current">' . $page . '</span>';
    // Next
    if ($page < $total_pages) {
        $buttons[] = '<a class="pagination-btn" href="' . $baseUrl . $query . ($page + 1) . '">Next</a>';
    }
    return $buttons;
}

// Transform data for view
$reports = [];
if ($reports_data) {
    foreach ($reports_data as $report) {
        $reports[] = [
            'id' => (int)$report['id'],
            'name' => htmlspecialchars($report['user_email'] ?: 'Unknown'),
            'user_email' => htmlspecialchars($report['user_email']),
            'area' => htmlspecialchars($report['location']),
            'location' => htmlspecialchars($report['location']),
            'status' => htmlspecialchars($report['status']),
            'last_updated' => $report['created_at'],
            'created_at' => $report['created_at']
        ];
    }
}

// Get all unique locations for filter dropdown
$locations = get_unique_report_locations($conn);
sort($locations);

// For view
$pagination_buttons = generatePaginationButtons($page, $total_pages, $selectedLocation);

/**
 * Get CSS badge class based on status
 * 
 * @param string $status Report status
 * @return string CSS class name
 */
function getBadgeClass($status) {
    switch ($status) {
        case "No Response":
            return "badge-no-response";
        case "Danger":
            return "badge-danger";
        case "Alert":
            return "badge-alert";
        case "Safe":
            return "badge-safe";
        default:
            return "";
    }
}

?>