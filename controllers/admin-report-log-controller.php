<?php

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/reports.php';
require_once __DIR__ . '/../models/affected_areas.php';

// Get selected filters
$selectedBarangay = isset($_GET['barangay']) ? trim($_GET['barangay']) : '';
$selectedBridge = isset($_GET['bridge']) ? trim($_GET['bridge']) : '';

// Pagination setup
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$limit = 10; // Items per page
$offset = ($page - 1) * $limit;

// Fetch all reports and get unique barangays
$query = "SELECT r.id, r.user_email, r.location, r.status, r.description, r.image, r.post_news, r.sms_sent_at, r.created_at, 
          u.first_name, u.last_name 
          FROM reports r 
          LEFT JOIN users u ON r.user_email = u.email 
          ORDER BY r.created_at DESC";
$all_reports_result = $conn->query($query);
$all_reports = [];
if ($all_reports_result) {
    while ($row = $all_reports_result->fetch_assoc()) {
        $all_reports[] = $row;
    }
    $all_reports_result->free();
}
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

        // Determine if this report should be included based on filters
        $include = true;
        if ($selectedBarangay) {
            if (stripos($location, $selectedBarangay) === false && strcasecmp($barangay, $selectedBarangay) !== 0) {
                $include = false;
            }
        }
        if ($selectedBridge) {
            if (stripos($location, $selectedBridge) === false) {
                $include = false;
            }
        }

        if ($include) {
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

// Fetch all affected areas (bridges) for the news modal dropdown and report filter
$affected_areas_list = get_all_affected_areas($conn);
$bridges_for_news = [];
$bridgeFilterOptions = [];
if ($affected_areas_list) {
    foreach ($affected_areas_list as $area) {
        $bridges_for_news[] = $area['name'];
        $bridgeFilterOptions[] = $area['name'];
    }
}
// ensure filter options are unique & sorted
$bridgeFilterOptions = array_unique($bridgeFilterOptions);
sort($bridgeFilterOptions);

// Generate minimal pagination buttons
function generatePaginationButtons($page, $total_pages, $selectedBarangay, $selectedBridge) {
    $buttons = [];
    $baseUrl = $_SERVER['PHP_SELF'];

    // build query prefix
    $params = [];
    if ($selectedBarangay) {
        $params[] = 'barangay=' . urlencode($selectedBarangay);
    }
    if ($selectedBridge) {
        $params[] = 'bridge=' . urlencode($selectedBridge);
    }
    $query = $params ? ('?' . implode('&', $params) . '&page=') : '?page=';

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

        $userName = trim(($report['first_name'] ?? '') . ' ' . ($report['last_name'] ?? ''));
        if (empty($userName)) {
            $userName = $report['user_email'] ?: 'Unknown';
        }

        $reports[] = [
            'id' => (int)$report['id'],
            'name' => htmlspecialchars($userName),
            'user_email' => htmlspecialchars($report['user_email']),
            'area' => htmlspecialchars($report['location']),
            'location' => htmlspecialchars($report['location']),
            'status' => htmlspecialchars($statusText),
            'sms_sent_at' => $report['sms_sent_at'] ?? null,
            'last_updated' => $report['created_at'],
            'created_at' => $report['created_at']
        ];
    }
}

// For view
$pagination_buttons = generatePaginationButtons($page, $total_pages, $selectedBarangay, $selectedBridge);
// expose bridge options for view dropdown
$bridges = $bridgeFilterOptions;

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