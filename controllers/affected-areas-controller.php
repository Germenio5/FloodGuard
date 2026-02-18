<?php

session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header("Location: login-user.php?error=login_required");
    exit();
}

require_once __DIR__ . '/../config/config.php';

$bridges = [];

$query = "SELECT name, location, current_level, max_level, speed, status
          FROM affected_areas
          ORDER BY updated_at DESC";

if ($result = $conn->query($query)) {
    while ($row = $result->fetch_assoc()) {
        $row['current_level'] = (float) $row['current_level'];
        $row['max_level'] = (float) $row['max_level'];
        $row['speed'] = (float) $row['speed'];
        $bridges[] = $row;
    }
    $result->free();
} else {
    error_log("Database query failed: " . $conn->error);
}

if (empty($bridges)) {
    $bridges = [
        [
            'name' => 'Mandalagan Bridge',
            'location' => 'Brgy Mandalagan',
            'current_level' => 2.5,
            'max_level' => 14.2,
            'speed' => 0.3,
            'status' => 'normal'
        ],
        [
            'name' => 'Mandalagan Bridge',
            'location' => 'Brgy Mandalagan',
            'current_level' => 9.5,
            'max_level' => 14.2,
            'speed' => 0.3,
            'status' => 'alert'
        ],
        [
            'name' => 'Mandalagan Bridge',
            'location' => 'Brgy Mandalagan',
            'current_level' => 13.5,
            'max_level' => 14.2,
            'speed' => 0.3,
            'status' => 'danger'
        ]
    ];
}


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

function getPercentage($current, $max) {
    return ($current / $max) * 100;
}

?>