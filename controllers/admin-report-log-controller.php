<?php

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/reports.php';
require_once __DIR__ . '/../models/affected_areas.php';

// Get selected barangay filter
$selectedBarangay = isset($_GET['barangay']) ? trim($_GET['barangay']) : '';

// Pagination setup
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$limit = 10; // Items per page
$offset = ($page - 1) * $limit;

// Fetch all reports and get unique barangays
$all_reports = get_all_reports($conn);
$barangays = [];
$filtered_reports_all = [];

if ($all_reports) {
    foreach ($all_reports as $report) {
        // Extract barangay from location (usually formatted as "Brgy. Barangay Name")
        $location = $report['location'];
        preg_match('/^Brgy\.\s*([^,]+)/i', $location, $matches);
        $barangay = $matches[1] ?? trim($location);
        $barangay = preg_replace('/^Brgy\.\s*/i', '', $barangay);
        
        // Add to unique barangays list
        if (!in_array($barangay, $barangays)) {
            $barangays[] = $barangay;
        }
        
        // Filter by selected barangay if specified
        if (!$selectedBarangay || stripos($location, $barangay) !== false && $barangay === $selectedBarangay) {
            $filtered_reports_all[] = $report;
        } elseif (!$selectedBarangay) {
            $filtered_reports_all[] = $report;
        }
    }
}

sort($barangays);

// Get total count and apply pagination
$total_reports = count($filtered_reports_all);
$total_pages = $total_reports > 0 ? ceil($total_reports / $limit) : 1;

// Get paginated data
$reports_data = array_slice($filtered_reports_all, $offset, $limit);

// Fetch all affected areas (bridges) for the news modal dropdown
$affected_areas_list = get_all_affected_areas($conn);
$bridges_for_news = [];
if ($affected_areas_list) {
    foreach ($affected_areas_list as $area) {
        $bridges_for_news[] = $area['name'];
    }
}

// Generate minimal pagination buttons
function generatePaginationButtons($page, $total_pages, $selectedBarangay) {
    $buttons = [];
    $baseUrl = $_SERVER['PHP_SELF'];
    $query = $selectedBarangay ? ('?barangay=' . urlencode($selectedBarangay)) : '?';
    $query .= ($selectedBarangay ? '&' : '') . 'page=';

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
        // normalize any "No Response" status to avoid that category
        $statusText = $report['status'];
        if (strcasecmp($statusText, 'No Response') === 0) {
            $statusText = 'Safe';
        }

        $reports[] = [
            'id' => (int)$report['id'],
            'name' => htmlspecialchars($report['user_email'] ?: 'Unknown'),
            'user_email' => htmlspecialchars($report['user_email']),
            'area' => htmlspecialchars($report['location']),
            'location' => htmlspecialchars($report['location']),
            'status' => htmlspecialchars($statusText),
            'last_updated' => $report['created_at'],
            'created_at' => $report['created_at']
        ];
    }
}

// For view
$pagination_buttons = generatePaginationButtons($page, $total_pages, $selectedBarangay);

/**
 * Get CSS badge class based on status
 * 
 * @param string $status Report status
 * @return string CSS class name
 */
function getBadgeClass($status) {
    switch ($status) {
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