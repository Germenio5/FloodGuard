<?php

// Sample water level data for multiple bridges
$waterStatusData = [
    [
        'id' => 1,
        'bridge_name' => 'Eroreco Bridge',
        'location' => 'Brgy Mandalagan',
        'current_level' => '7.5m',
        'max_level' => '14.2m',
        'speed' => '0.3m/min',
        'status' => 'normal', // normal, warning, critical
        'percentage' => 53
    ],
    [
        'id' => 2,
        'bridge_name' => 'Eroreco Bridge',
        'location' => 'Brgy Mandalagan',
        'current_level' => '7.5m',
        'max_level' => '14.2m',
        'speed' => '0.3m/min',
        'status' => 'critical',
        'percentage' => 53
    ],
    [
        'id' => 3,
        'bridge_name' => 'Eroreco Bridge',
        'location' => 'Brgy Mandalagan',
        'current_level' => '7.5m',
        'max_level' => '14.2m',
        'speed' => '0.3m/min',
        'status' => 'critical',
        'percentage' => 53
    ],
    [
        'id' => 4,
        'bridge_name' => 'Eroreco Bridge',
        'location' => 'Brgy Mandalagan',
        'current_level' => '7.5m',
        'max_level' => '14.2m',
        'speed' => '0.3m/min',
        'status' => 'normal',
        'percentage' => 53
    ],
    [
        'id' => 5,
        'bridge_name' => 'Eroreco Bridge',
        'location' => 'Brgy Mandalagan',
        'current_level' => '7.5m',
        'max_level' => '14.2m',
        'speed' => '0.3m/min',
        'status' => 'critical',
        'percentage' => 53
    ],
    [
        'id' => 6,
        'bridge_name' => 'Eroreco Bridge',
        'location' => 'Brgy Mandalagan',
        'current_level' => '7.5m',
        'max_level' => '14.2m',
        'speed' => '0.3m/min',
        'status' => 'critical',
        'percentage' => 53
    ]
];

function getProgressClass($status) {
    return "progress-" . $status;
}
?>
