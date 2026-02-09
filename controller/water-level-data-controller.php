<?php

// Sample data array (this can later come from database)
$waterLevels = [
    [
        "area" => "Eroreco Bridge",
        "trend" => "steady",
        "date" => "11/26/2026",
        "height" => "2.3",
        "speed" => "0.3",
        "status" => "normal"
    ],
    [
        "area" => "Eroreco Bridge",
        "trend" => "rising",
        "date" => "11/26/2026",
        "height" => "2.3",
        "speed" => "6.7",
        "status" => "danger"
    ],
    [
        "area" => "Eroreco Bridge",
        "trend" => "falling",
        "date" => "11/26/2026",
        "height" => "6.7",
        "speed" => "0.1",
        "status" => "alert"
    ]
];


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
