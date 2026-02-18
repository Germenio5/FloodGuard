<?php
session_start();

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header("Location: ../views/login-user.php?error=login_required");
    exit();
}

if (isset($_SESSION['user_role']) && $_SESSION['user_role'] !== 'admin') {
    header("Location: ../views/user-dashboard.php");
    exit();
}

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/affected_areas.php';

// Fetch affected areas data using model
$affected_areas_data = get_all_affected_areas($conn);

// Transform data for view
$waterStatusData = [];
if ($affected_areas_data) {
    foreach ($affected_areas_data as $row) {
        // Calculate percentage
        $maxLevel = (float)$row['max_level'];
        $currentLevel = (float)$row['current_level'];
        $percentage = ($maxLevel > 0) ? min(100, ($currentLevel / $maxLevel) * 100) : 0;
        
        // Map database status to CSS classes
        $statusClass = mapStatusClass($row['status']);
        
        $waterStatusData[] = [
            'id' => (int)$row['id'],
            'bridge_name' => htmlspecialchars($row['name']),
            'location' => htmlspecialchars($row['location']),
            'current_level' => number_format($currentLevel, 2) . 'm',
            'max_level' => number_format($maxLevel, 2) . 'm',
            'speed' => number_format((float)$row['speed'], 2) . 'm/min',
            'status' => $statusClass,
            'percentage' => round($percentage, 1),
            'raw_status' => htmlspecialchars($row['status']),
            'updated_at' => $row['updated_at']
        ];
    }
}

// Fallback to empty array if no data
if (empty($waterStatusData)) {
    $waterStatusData = [];
}

/**
 * Map database status values to CSS class names
 * @param string $status Status from database (normal, alert, danger)
 * @return string CSS class name (normal, warning, critical)
 */
function mapStatusClass($status) {
    $status = strtolower(trim($status));
    switch ($status) {
        case 'danger':
            return 'critical';
        case 'alert':
            return 'warning';
        case 'normal':
        default:
            return 'normal';
    }
}

function getProgressClass($status) {
    return "progress-" . $status;
}

?>

