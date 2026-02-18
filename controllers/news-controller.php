<?php
session_start();

date_default_timezone_set('Asia/Manila');

require_once __DIR__ . '/../config/config.php';

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

// load additional events from reports table where post_news flag is set
$sql = "SELECT user_email AS name, location AS area, status, description, created_at
        FROM reports
        WHERE post_news = 1
        ORDER BY created_at DESC";
if ($result = $conn->query($sql)) {
    while ($row = $result->fetch_assoc()) {
        $eventList[] = [
            'name'        => $row['name'] ?: 'Anonymous',
            'avatar'      => '../assets/images/placeholder-image.png',
            'time'        => date('g:i A', strtotime($row['created_at'])),
            'area'        => $row['area'],
            'picture'     => '../assets/images/placeholder-image.png',
            'description' => $row['description'],
            'status'      => $row['status']
        ];
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
