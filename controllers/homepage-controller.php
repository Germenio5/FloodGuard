<?php

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/reports.php';

// Fetch latest 2 reports from database
$reports_data = get_all_reports($conn, 2, 0);

// Transform database records to match view format
$reports = [];
if ($reports_data) {
    foreach ($reports_data as $report) {
        $reports[] = [
            'title' => htmlspecialchars($report['location']),
            'location' => htmlspecialchars($report['location']),
            'description' => htmlspecialchars($report['description']),
            'date' => date('Y-m-d', strtotime($report['created_at']))
        ];
    }
}

// Fallback to empty array if no reports found
if (empty($reports)) {
    $reports = [];
}

?>