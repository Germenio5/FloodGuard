<?php
session_start();

// Set timezone to GMT+8 (Asia/Manila)
date_default_timezone_set('Asia/Manila');

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

// Update last active timestamp
update_user_last_active($conn, $userId);
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

// Determine nearest affected area based on user's barangay location
$latestArea = false;

// determine barangay using helper from user model
$barangay = extract_barangay_from_address($user['address']);
// expose for view
$userBarangay = $barangay;
$bridgeMatchedUser = false;
if ($barangay !== '') {
    // try to load the latest area for this barangay
    $areaForUser = get_latest_affected_area_by_location($conn, $barangay);
    if ($areaForUser) {
        $latestArea = $areaForUser;
        $bridgeMatchedUser = true;
    }
}

// fall back to most recent area overall if none found for user
if (!$latestArea) {
    $latestArea = get_latest_affected_area($conn);
}

// header variables for water level card
$cardHeaderTitle = 'Water Level Data';
$cardHeaderUpdated = $latestArea ? date('M d, Y', strtotime($latestArea['updated_at'])) : date('M d, Y');

if ($latestArea) {
    $current = (float)$latestArea['current_level'];
    $max = (float)$latestArea['max_level'];
    $percentage = $max > 0 ? ($current / $max) * 100 : 0;
    
    // Determine status based on water level percentage
    // normal: 0-24.9%, warning: 25-49.9%, danger: 50-74.9%, critical: >= 75%
    if ($percentage < 25) {
        $levelStatus = 'normal';
    } elseif ($percentage < 50) {
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
} elseif (strtolower($waterLevel['status']) === 'normal') {
    $alertTips = [
        'Water levels are normal - conditions are safe',
        'Continue to monitor flood alerts for any updates',
        'Keep emergency contact numbers accessible',
        'Review evacuation routes periodically'
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

// Fetch last 24 hours water level data for chart based on current location
$areaName = $waterLevel['bridge'] ?? 'Default Area';
$areaLocation = $waterLevel['location'] ?? '';
$last24hData = get_water_level_last_24h($conn, $areaName);

// Prepare chart data from water level history table
$chartLabels = [];
$chartHeights = [];

if (!empty($last24hData)) {
    foreach ($last24hData as $record) {
        // Format time as HH:MM for chart label
        $chartLabels[] = date('H:i', strtotime($record['record_time']));
        $chartHeights[] = (float)$record['height'];
    }
} else {
    // Sample data for demonstration if no database records exist
    $sampleTimes = [
        '00:00', '01:00', '02:00', '03:00', '04:00', '05:00',
        '06:00', '07:00', '08:00', '09:00', '10:00', '11:00',
        '12:00', '13:00', '14:00', '15:00', '16:00', '17:00',
        '18:00', '19:00', '20:00', '21:00', '22:00', '23:00'
    ];
    $sampleHeights = [
        4.2, 4.5, 4.3, 4.1, 3.9, 3.8,
        4.0, 4.3, 4.6, 5.0, 5.3, 5.5,
        5.4, 5.2, 5.1, 5.3, 5.5, 5.7,
        5.8, 5.6, 5.4, 5.2, 4.8, 4.5
    ];
    $chartLabels = $sampleTimes;
    $chartHeights = $sampleHeights;
    error_log("No water level history data found for area: " . $areaName . ". Using sample data for display.");
}

$chartDataJson = json_encode([
    'labels' => !empty($chartLabels) ? $chartLabels : ['No Data'],
    'heights' => !empty($chartHeights) ? $chartHeights : [0],
    'area' => $areaName,
    'location' => $areaLocation
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
