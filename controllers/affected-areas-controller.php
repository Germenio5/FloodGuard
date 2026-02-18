<?php

session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header("Location: login-user.php?error=login_required");
    exit();
}

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/affected_areas.php';

// Fetch affected areas from database using model
$bridges_data = get_all_affected_areas($conn);

// Transform data for view
$bridges = [];
if ($bridges_data) {
    foreach ($bridges_data as $area) {
        $bridges[] = [
            'name' => htmlspecialchars($area['name']),
            'location' => htmlspecialchars($area['location']),
            'current_level' => (float)$area['current_level'],
            'max_level' => (float)$area['max_level'],
            'speed' => (float)$area['speed'],
            'status' => htmlspecialchars($area['status'])
        ];
    }
}

// Fallback to empty array if no data
if (empty($bridges)) {
    $bridges = [];
}


/**
 * Get color code for water status
 * 
 * @param string $status Water level status
 * @return string Hex color code
 */
function getStatusColor($status) {
    switch($status) {
        case 'normal':
            return '#22c55e';
        case 'alert':
            return '#f97316';
        case 'danger':
            return '#dc2626';
        default:
            return '#22c55e';
    }
}

/**
 * Calculate water level percentage
 * 
 * @param float $current Current water level
 * @param float $max Maximum water level
 * @return float Percentage value
 */
function getPercentage($current, $max) {
    return ($current / $max) * 100;
}

?>