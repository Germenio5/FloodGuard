<?php
session_start();

date_default_timezone_set('Asia/Manila');

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/reports.php';
require_once __DIR__ . '/../models/user.php';

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
            // derive full name from email if possible
            $fullName = 'Anonymous';
            $avatarSrc = '../assets/images/placeholder-image.png';
            if (!empty($row['user_email'])) {
                // look up user record
                $userInfo = get_user_by_email($conn, $row['user_email']);
                if ($userInfo) {
                    $fullName = trim($userInfo['first_name'] . ' ' . $userInfo['last_name']);
                    if (!empty($userInfo['profile_photo'])) {
                        $avatarSrc = 'data:image/jpeg;base64,' . base64_encode($userInfo['profile_photo']);
                    }
                } else {
                    $fullName = $row['user_email'];
                }
            }
            // compute "time ago"
            $created = strtotime($row['created_at']);
            $diff = time() - $created;
            if ($diff < 60) {
                $timeStr = $diff . ' seconds ago';
            } elseif ($diff < 3600) {
                $timeStr = floor($diff/60) . ' minutes ago';
            } elseif ($diff < 86400) {
                $timeStr = floor($diff/3600) . ' hours ago';
            } else {
                $timeStr = floor($diff/86400) . ' days ago';
            }
            // extract only barangay portion and prefix with 'Brgy.'
            $parts = explode(',', $row['location']);
            $raw = trim($parts[0]);
            // remove existing prefix if any
            $raw = preg_replace('/^\s*(Brgy\.|Barangay)\s*/i', '', $raw);
            $areaName = 'Brgy. ' . $raw;
            // prepare picture from blob if exists
            $pictureSrc = '../assets/images/placeholder-image.png';
            if (!empty($row['image'])) {
                $pictureSrc = 'data:image/jpeg;base64,' . base64_encode($row['image']);
            }
            $eventList[] = [
                'id'          => $row['id'],
                'name'        => htmlspecialchars($fullName),
                'avatar'      => $avatarSrc,
                'time'        => $timeStr,
                'area'        => htmlspecialchars($areaName),
                'picture'     => $pictureSrc,
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
