<?php

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
        'current_level' => 9.5,
        'max_level' => 14.2,
        'speed' => 0.3,
        'status' => 'danger'
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