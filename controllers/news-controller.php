<?php
session_start();

date_default_timezone_set('Asia/Manila');

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/reports.php';

// summary counts could also be derived from the database if desired
$dangerCount = 0;
$alertCount  = 0;

$lastUpdated = [
    'date' => date('F j, Y'),
    'time' => date('g:i A')
];

// start with some hard‑coded sample events so the page is never empty
$eventList = [
    [
        'name'        => 'FloodGuard',
        'avatar'      => '../assets/images/FloodGuard_logo.png',
        'time'        => '2 weeks ago',
        'area'        => 'Mandalagan Bridge',
        'picture'     => '../assets/images/Sample.png',
        'description' => 'Water rising rapidly, might flood soon. Stay safe and avoid the area.',
        'status'      => 'Danger'
    ],
    [
        'name'        => 'Tim Chavez',
        'avatar'      => '../assets/images/placeholder-image.png',
        'time'        => '9 hours ago',
        'area'        => 'La Salle Avenue',
        'picture'     => '../assets/images/placeholder-image.png',
        'description' => 'Slow rising of water, might flood or not',
        'status'      => 'Safe'
    ],
    [
        'name'        => 'Abdul Rahman',
        'avatar'      => '../assets/images/placeholder-image.png',
        'time'        => '12 hours ago',
        'area'        => 'Mandalagan Bridge',
        'picture'     => '../assets/images/placeholder-image.png',
        'description' => 'Water rising but still safe',
        'status'      => 'Safe'
    ],
    [
        'name'        => 'Juan Dela Cruz',
        'avatar'      => '../assets/images/placeholder-image.png',
        'time'        => '2 hours ago',
        'area'        => 'Banago Road',
        'picture'     => '../assets/images/placeholder-image.png',
        'description' => 'Water increasing near bridge',
        'status'      => 'Danger'
    ]
];

// Load additional events from reports table using model
// Fetch all reported news items
$reports_data = get_all_reports($conn);

// Filter reports that should be posted as news (post_news = 1)
if ($reports_data) {
    foreach ($reports_data as $row) {
        // Only include reports marked for news posting
        if (isset($row['post_news']) && $row['post_news'] == 1) {
            $eventList[] = [
                'name'        => htmlspecialchars($row['user_email'] ?: 'Anonymous'),
                'avatar'      => '../assets/images/placeholder-image.png',
                'time'        => date('g:i A', strtotime($row['created_at'])),
                'area'        => htmlspecialchars($row['location']),
                'picture'     => !empty($row['image_path']) ? $row['image_path'] : '../assets/images/placeholder-image.png',
                'description' => htmlspecialchars($row['description']),
                'status'      => htmlspecialchars($row['status'])
            ];
        }
    }
}

// compute simple summary counts based on the loaded events
foreach ($eventList as $e) {
    if ($e['status'] === 'Danger') {
        $dangerCount++;
    } elseif ($e['status'] === 'Alert') {
        $alertCount++;
    }
}

?>
