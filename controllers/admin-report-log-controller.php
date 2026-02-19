<?php

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/reports.php';

// Get selected location filter
$selectedLocation = isset($_GET['location']) ? trim($_GET['location']) : '';

// Fetch reports from database using model with filter
$reports_data = $selectedLocation 
    ? get_reports_by_location($conn, $selectedLocation)
    : get_all_reports($conn);

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