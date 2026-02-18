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

// Fetch water status data from affected_areas table
$waterStatusData = [];
$query = "SELECT id, name, location, current_level, max_level, speed, status, updated_at 
          FROM affected_areas 
          ORDER BY name ASC";

$result = $conn->query($query);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Calculate percentage
        $maxLevel = (float)$row['max_level'];
        $currentLevel = (float)$row['current_level'];
        $percentage = ($maxLevel > 0) ? min(100, ($currentLevel / $maxLevel) * 100) : 0;
        
        // Map database status to CSS classes
        $statusClass = mapStatusClass($row['status']);
        
        $waterStatusData[] = [
            'id' => $row['id'],
            'bridge_name' => htmlspecialchars($row['name']),
            'location' => htmlspecialchars($row['location']),
            'current_level' => number_format($currentLevel, 2) . 'm',
            'max_level' => number_format($maxLevel, 2) . 'm',
            'speed' => number_format((float)$row['speed'], 2) . 'm/min',
            'status' => $statusClass,
            'percentage' => round($percentage, 1),
            'raw_status' => $row['status'],
            'updated_at' => $row['updated_at']
        ];
    }
    $result->free();
}

// If no data in database, use sample data
if (empty($waterStatusData)) {
    $waterStatusData = [
        [
            'id' => 1,
            'bridge_name' => 'Eroreco Bridge',
            'location' => 'Brgy Mandalagan',
            'current_level' => '7.50m',
            'max_level' => '14.20m',
            'speed' => '0.30m/min',
            'status' => 'normal',
            'percentage' => 53
        ],
        [
            'id' => 2,
            'bridge_name' => 'Mandalagan Bridge',
            'location' => 'Brgy Mandalagan',
            'current_level' => '10.50m',
            'max_level' => '14.20m',
            'speed' => '0.50m/min',
            'status' => 'warning',
            'percentage' => 74
        ]
    ];
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

