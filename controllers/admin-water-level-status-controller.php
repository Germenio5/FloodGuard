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

// Get selected barangay/location and status filters
$selectedLocation = isset($_GET['location']) ? trim($_GET['location']) : '';
$selectedStatus = isset($_GET['status']) ? trim($_GET['status']) : '';

// Fetch affected areas data using model with filter
$affected_areas_data = $selectedLocation 
    ? get_affected_areas_by_location($conn, $selectedLocation)
    : get_all_affected_areas($conn);

$statusOptions = [
    '' => 'All Statuses',
    'warning' => 'Warning',
    'danger' => 'Danger',
    'critical' => 'Critical'
];

// Transform data for view
$waterStatusData = [];
if ($affected_areas_data) {
    foreach ($affected_areas_data as $row) {
        // Calculate percentage
        $maxLevel = (float)$row['max_level'];
        $currentLevel = (float)$row['current_level'];
        $percentage = ($maxLevel > 0) ? min(100, ($currentLevel / $maxLevel) * 100) : 0;
        
        // Determine status class based on percentage thresholds
        if ($percentage < 30) {
            $statusClass = 'warning';
        } elseif ($percentage < 75) {
            $statusClass = 'danger';
        } else {
            $statusClass = 'critical';
        }

        // Apply status filter if selected
        if ($selectedStatus !== '' && $statusClass !== $selectedStatus) {
            continue;
        }

        $waterStatusData[] = [
            'id' => (int)$row['id'],
            'bridge_name' => htmlspecialchars($row['name']),
            'location' => htmlspecialchars($row['location']),
            'current_level' => number_format($currentLevel, 2) . 'm',
            'max_level' => number_format($maxLevel, 2) . 'm',
            'speed' => number_format((float)$row['speed'], 2) . 'm/min',
            'status' => $statusClass,
            'percentage' => round($percentage, 1),
            'updated_at' => $row['updated_at']
        ];
    }
}

// Fallback to empty array if no data
if (empty($waterStatusData)) {
    $waterStatusData = [];
}

// Get all unique barangays for filter dropdown
$barangays = get_unique_barangays($conn);
sort($barangays);

function getProgressClass($status) {
    return "progress-" . $status;
}

function getStatusBadge($status) {
    switch (strtolower($status)) {
        case 'warning':
            return '<span class="status-badge status-warning">● Warning</span>';
        case 'danger':
            return '<span class="status-badge status-danger">● Danger</span>';
        case 'critical':
            return '<span class="status-badge status-critical">● Critical</span>';
        default:
            return '<span class="status-badge">Unknown</span>';
    }
}

?>

