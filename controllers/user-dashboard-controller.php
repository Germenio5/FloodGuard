<?php
session_start();

if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header("Location: ../views/login-user.php");
    exit();
}

if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
    header("Location: ../views/admin-dashboard.php");
    exit();
}

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/user.php';
require_once __DIR__ . '/../models/affected_areas.php';
require_once __DIR__ . '/../models/water_level.php';

$userId = $_SESSION['user_id'];
$userEmail = $_SESSION['user_email'] ?? '';
$userFromDb = get_user_by_id($conn, $userId);

if (!$userFromDb) {
    session_destroy();
    header("Location: ../views/login-user.php?error=session");
    exit();
}

// Set user data from database
$dbStatus = isset($userFromDb['status']) && !empty($userFromDb['status']) ? $userFromDb['status'] : null;

$user = [
    'id' => $userFromDb['id'],
    'name' => $userFromDb['first_name'] . ' ' . $userFromDb['last_name'],
    'first_name' => $userFromDb['first_name'],
    'email' => $userFromDb['email'],
    'phone' => $userFromDb['phone'],
    'address' => $userFromDb['address'],
    'status' => $dbStatus ?? 'Safe' // Default to Safe if no status saved
];

// Get latest water level data for monitoring using model
$latestArea = get_latest_affected_area($conn);

if ($latestArea) {
    $current = (float)$latestArea['current_level'];
    $max = (float)$latestArea['max_level'];
    $percentage = $max > 0 ? ($current / $max) * 100 : 0;
    
    // Determine status based on water level percentage (same as affected-areas)
    // warning: < 30%, danger: 30-74%, critical: >= 75%
    if ($percentage < 30) {
        $levelStatus = 'warning';
    } elseif ($percentage < 75) {
        $levelStatus = 'danger';
    } else {
        $levelStatus = 'critical';
    }
    
    $waterLevel = [
        'area_id' => (int)$latestArea['id'],
        'bridge' => htmlspecialchars($latestArea['name']),
        'location' => htmlspecialchars($latestArea['location']),
        'current' => $current,
        'max' => $max,
        'percentage' => $percentage,
        'trend' => 'steady', // Default trend
        'speed' => number_format((float)$latestArea['speed'], 2) . ' meters/hour',
        'last_update' => 'Just now',
        'date' => date('M d, Y', strtotime($latestArea['updated_at'])),
        'status' => ucfirst($levelStatus),
        'updated_at' => $latestArea['updated_at']
    ];
} else {
    // Fallback if no data available
    $waterLevel = [
        'area_id' => 0,
        'bridge' => 'No monitoring area',
        'location' => 'N/A',
        'current' => 0,
        'max' => 0,
        'percentage' => 0,
        'trend' => 'Unknown',
        'speed' => '0 meters/hour',
        'last_update' => 'N/A',
        'date' => date('M d, Y'),
        'status' => 'Normal',
        'updated_at' => null
    ];
}

// Alert tips based on water level status
$alertTips = [];
if (strtolower($waterLevel['status']) === 'critical') {
    $alertTips = [
        'IMMEDIATE ACTION REQUIRED: Critical flood conditions detected',
        'Evacuate immediately if advised by local authorities',
        'Keep emergency supplies and important documents ready',
        'Follow local emergency broadcasts and updates'
    ];
} elseif (strtolower($waterLevel['status']) === 'danger') {
    $alertTips = [
        'HIGH ALERT: Dangerous water levels - prepare for evacuation',
        'Pack essential items and documents in an accessible location',
        'Monitor emergency channels continuously for updates',
        'Avoid flood-prone areas - do not attempt to cross flooded roads'
    ];
} elseif (strtolower($waterLevel['status']) === 'warning') {
    $alertTips = [
        'Stay alert and monitor flood levels regularly',
        'Prepare emergency supplies and evacuation route',
        'Keep important documents and valuables in safe place',
        'Avoid low-lying areas and stay informed through official channels'
    ];
} else {
    $alertTips = [
        'Monitor water level status and stay informed',
        'Keep emergency supplies ready at all times',
        'Know your evacuation route',
        'Stay in contact with local authorities'
    ];
}

// Action links
$actions = [
    [
        'title' => 'Monitor',
        'description' => 'Discover the latest flood conditions, area-specific alerts, and safety information to help you stay aware and prepared.',
        'link' => 'user-affected-areas.php',
        'label' => 'Monitor Areas →'
    ],
    [
        'title' => 'Report',
        'description' => 'Report flood incidents in your area to help provide timely updates, improve response efforts, and keep the community informed and safe.',
        'link' => 'user-report-flood.php',
        'label' => 'Report Now →'
    ]
];

// Emergency hotline
$hotline = [
    'phone' => '(034) 432-3871 to 73',
    'tel' => '0344323871',
    'note' => '(24/7 Roxas Emergency Dispatch)'
];

// Fetch last 24 hours water level data for chart
$areaName = $waterLevel['bridge'] ?? 'Default Area';
$last24hData = get_water_level_last_24h($conn, $areaName);

// Prepare chart data from water level history table
$chartLabels = [];
$chartHeights = [];

if (!empty($last24hData)) {
    foreach ($last24hData as $record) {
        $chartLabels[] = date('H:i', strtotime($record['record_time']));
        $chartHeights[] = (float)$record['height'];
    }
}

$chartDataJson = json_encode([
    'labels' => $chartLabels,
    'heights' => $chartHeights
]);

/**
 * Helper function to format water level display
 * 
 * @param float $current Current water level
 * @param float $max Maximum water level
 * @return string Formatted level text
 */
function formatLevelText($current, $max) {
    return round($current, 2) . "m / " . round($max, 2) . "m";
}

/**
 * Helper function to calculate progress bar width
 * 
 * @param float $percent Percentage value
 * @return string CSS width style
 */
function getProgressWidth($percent) {
    $percent = min(100, max(0, $percent)); // Clamp between 0-100
    return "width: " . round($percent, 1) . "%";
}

?>
