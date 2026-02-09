<?php

$user = [
    'name' => 'User',
    'status' => 'You Are Safe'
];

$waterLevel = [
    'bridge' => 'Mandalagan Bridge',
    'location' => 'Brgy. Mandalagan',
    'current' => 7.5,
    'max' => 14.2,
    'percentage' => (7.5 / 14.2) * 100,
    'trend' => 'Rising',
    'speed' => '0.9 meters / hour',
    'last_update' => '6 minutes ago',
    'date' => 'Apr 26, 2025',
    'status' => 'Alert'
];

$alertTips = [
    'Stay alert and monitor updates regularly',
    'Prepare for possible evacuation if levels rise further',
    'Keep emergency supplies ready'
];

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

$hotline = [
    'phone' => '(034) 432-3871 to 73',
    'tel' => '0344323871',
    'note' => '(24/7 Roxas Emergency Dispatch)'
];

function formatLevelText($current, $max) {
    return $current . "m / " . $max . "m";
}

function getProgressWidth($percent) {
    return "width: " . $percent . "%";
}
?>
