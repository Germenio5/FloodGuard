<?php
// Sample water level data
$waterLevelData = [
    'bridge_name' => 'Eroreco Bridge',
    'trend' => 'Steady',
    'height_level' => '7.5m',
    'speed' => '0.3+',
    'status' => 'Alert',
    'max_height' => '14.2m',
    'location' => 'Brgy Mandalagan',
    'last_updated' => '42 minutes ago',
    'status_messages' => [
        'Stay alert and monitor updates regularly.',
        'Prepare emergency kits and important documents.',
        'Know your evacuation routes.'
    ]
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Current Water Level - <?= htmlspecialchars($waterLevelData['bridge_name']) ?></title>
    <link rel="stylesheet" href="../css/monitorarea.css">
</head>
<body>
    <?php include 'include/header.php'; ?>
    <main class="water-level-page">
        <div class="container">
            <!-- Header Section -->
            <div class="page-header">
                <p class="bridge-label"><?= htmlspecialchars($waterLevelData['bridge_name']) ?></p>
                <h1 class="page-title">Current Water Level</h1>
                <p class="page-subtitle">Monitor the latest water levels across different locations</p>
            </div>

            <!-- Refresh Button -->
            <button class="refresh-btn" onclick="location.reload()">Refresh Data</button>

            <!-- Data Grid -->
            <div class="data-grid">
                <!-- Trend Card -->
                <div class="data-card">
                    <p class="data-label">Trend</p>
                    <p class="data-value trend-value"><?= htmlspecialchars($waterLevelData['trend']) ?></p>
                </div>

                <!-- Height Level Card -->
                <div class="data-card">
                    <p class="data-label">Height level</p>
                    <p class="data-value height-value"><?= htmlspecialchars($waterLevelData['height_level']) ?></p>
                    <p class="data-meta">Last updated: <?= htmlspecialchars($waterLevelData['last_updated']) ?></p>
                </div>

                <!-- Speed Card -->
                <div class="data-card">
                    <p class="data-label">Speed</p>
                    <p class="data-value speed-value"><?= htmlspecialchars($waterLevelData['speed']) ?></p>
                </div>

                <!-- Status Card -->
                <div class="data-card status-card">
                    <p class="data-label">Status</p>
                    <p class="data-value status-value"><?= htmlspecialchars($waterLevelData['status']) ?></p>
                    <ul class="status-messages">
                        <?php foreach($waterLevelData['status_messages'] as $message): ?>
                            <li><?= htmlspecialchars($message) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <!-- Max Height Card -->
                <div class="data-card">
                    <p class="data-label">Max Height</p>
                    <p class="data-value max-height-value"><?= htmlspecialchars($waterLevelData['max_height']) ?></p>
                </div>

                <!-- Location Card -->
                <div class="data-card">
                    <p class="data-label">Location</p>
                    <p class="data-value location-value"><?= htmlspecialchars($waterLevelData['location']) ?></p>
                    <a href="view-map.php" class="view-map-link">View Map</a>
                </div>
            </div>
        </div>
    </main>
</body>
</html>