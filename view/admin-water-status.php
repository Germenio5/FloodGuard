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
        'percentage' => 53 // (7.5/14.2) * 100
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Water Status By Area</title>
    <link rel="stylesheet" href="../css/adminwaterstatus.css">
</head>
<body>
    <main class="water-status-page">
        <div class="container">
            <!-- Page Header -->
            <div class="page-header">
                <h1 class="page-title">Water Status By Area</h1>
            </div>

            <!-- Bridge Cards Grid -->
            <div class="bridge-grid">
                <?php foreach($waterStatusData as $bridge): ?>
                <div class="bridge-card">
                    <h2 class="bridge-name"><?= htmlspecialchars($bridge['bridge_name']) ?></h2>
                    <p class="bridge-location"><?= htmlspecialchars($bridge['location']) ?></p>

                    <div class="level-info">
                        <span class="level-label">Current Level:</span>
                        <span class="level-value"><?= htmlspecialchars($bridge['current_level']) ?> / <?= htmlspecialchars($bridge['max_level']) ?></span>
                    </div>

                    <div class="progress-bar">
                        <div class="progress-fill progress-<?= htmlspecialchars($bridge['status']) ?>" style="width: <?= $bridge['percentage'] ?>%"></div>
                    </div>

                    <p class="speed-info">Speed: <?= htmlspecialchars($bridge['speed']) ?></p>

                    <div class="button-group">
                        <a href="waterleveldata.php" class="btn btn-outline">Water Level Data</a>
                        <button class="btn btn-primary">Send Alert</button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </main>
</body>
</html>