<?php
date_default_timezone_set('Asia/Manila');

$dangerCount = 2;
$alertCount  = 1;

$lastUpdated = [
    'date' => date('F j, Y'),
    'time' => date('g:i A')
];

$eventList = [
    
    [
        'name'        => 'FloodGuard',
        'avatar'      => '../images/FloodGuard_logo.png',
        'time'        => '2 weeks ago',
        'area'        => 'Mandalagan Bridge',
        'picture'     => '../images/Sample.png',
        'description' => 'Water rising rapidly, might flood soon. Stay safe and avoid the area.',
        'proximity'   => '',
        'status'      => 'Danger'
    ],
    
     [
        'name'        => 'Tim Chavez',
        'avatar'      => '../images/avatar1.jpg',
        'time'        => '9 hours ago',
        'area'        => 'La Salle Avenue',
        'picture'     => '../images/sample1.jpg',
        'description' => 'Slow rising of water, might flood or not',
        'proximity'   => 'Far Away',
        'status'      => 'Safe'
    ],

    [
        'name'        => 'Abdul Rahman',
        'avatar'      => '../images/avatar3.jpg',
        'time'        => '12 hours ago',
        'area'        => 'Mandalagan Bridge',
        'picture'     => '../images/sample1.jpg',
        'description' => 'Water rising but still safe',
        'proximity'   => 'Close',
        'status'      => 'Safe'
    ],

    [
        'name'        => 'Juan Dela Cruz',
        'avatar'      => '../images/avatar2.jpg',
        'time'        => '2 hours ago',
        'area'        => 'Banago Road',
        'picture'     => '../images/sample2.jpg',
        'description' => 'Water increasing near bridge',
        'proximity'   => 'Near You',
        'status'      => 'Danger'
    ]
];
?>
