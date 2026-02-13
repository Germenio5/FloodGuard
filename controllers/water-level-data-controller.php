<?php

// load database connection
require_once __DIR__ . '/../config/config.php';

// fetch hourly water level history per area from database
$waterLevels = [];

$query = "SELECT area, trend, record_time, height, speed, status
          FROM water_level_history
          ORDER BY record_time DESC";

if ($result = $conn->query($query)) {
    while ($row = $result->fetch_assoc()) {
        // create a user-friendly date string
        $row['date'] = date('m/d/Y H:i', strtotime($row['record_time']));
        // convert numeric fields to appropriate types
        $row['height'] = (float) $row['height'];
        $row['speed'] = (float) $row['speed'];
        $waterLevels[] = $row;
    }
    $result->free();
} else {
    error_log("Water level query failed: " . $conn->error);
}

// fallback example if no data available
if (empty($waterLevels)) {
    $waterLevels = [
        [
            "area" => "Eroreco Bridge",
            "trend" => "steady",
            "date" => "11/26/2026 08:00",
            "height" => "2.3",
            "speed" => "0.3",
            "status" => "normal"
        ],
        [
            "area" => "Eroreco Bridge",
            "trend" => "rising",
            "date" => "11/26/2026 09:00",
            "height" => "2.6",
            "speed" => "0.5",
            "status" => "alert"
        ],
        [
            "area" => "Eroreco Bridge",
            "trend" => "rising",
            "date" => "11/26/2026 10:00",
            "height" => "3.1",
            "speed" => "0.7",
            "status" => "danger"
        ]
    ];
}



function getTrendBadge($trend) {
    switch ($trend) {
        case "steady":
            return '<span class="badge badge-steady">🟢 Steady</span>';
        case "rising":
            return '<span class="badge badge-rising">📈 Rising</span>';
        case "falling":
            return '<span class="badge badge-falling">📉 Falling</span>';
        default:
            return '<span class="badge">Unknown</span>';
    }
}

function getStatusBadge($status) {
    switch ($status) {
        case "normal":
            return '<span class="status status-normal">● Normal</span>';
        case "alert":
            return '<span class="status status-alert">● Alert</span>';
        case "danger":
            return '<span class="status status-danger">● Danger</span>';
        default:
            return '<span class="status">Unknown</span>';
    }
}
?>
