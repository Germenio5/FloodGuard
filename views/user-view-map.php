<?php
include '../controllers/map-controller.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>FloodGuard Map</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="../assets/css/userviewmap.css">
</head>
<body>

<?php include 'include/user-sidebar.php'; ?>

<main>
<div class="main-wrapper">

    <!-- Page Header -->
    <div class="page-top">
        <h1><?= htmlspecialchars($pageTitle) ?></h1>
        <p><?= htmlspecialchars($pageDescription) ?></p>
    </div>

    <!-- Map + Legend side by side -->
    <div class="map-container">

        <!-- Map Area -->
        <div class="map-area">
            <div id="map"></div>
        </div>

        <!-- Legend Sidebar -->
        <div class="legend-area">
            <div class="legend-item">
                <span class="icon gray">🌉</span>
                <div>
                    <h4>Bridges</h4>
                    <p>Marked bridge locations on the map</p>
                </div>
            </div>
            <div class="legend-item">
                <span class="icon green">✅</span>
                <div>
                    <h4>Normal</h4>
                    <p>Areas with no reported issues</p>
                </div>
            </div>
            <div class="legend-item">
                <span class="icon orange">⚠️</span>
                <div>
                    <h4>Warning</h4>
                    <p>Regions under caution for possible flooding</p>
                </div>
            </div>
            <div class="legend-item">
                <span class="icon red">❗</span>
                <div>
                    <h4>Danger</h4>
                    <p>Hazardous areas with confirmed flooding</p>
                </div>
            </div>
            <div class="legend-item">
                <span class="icon red">🚨</span>
                <div>
                    <h4>Critical</h4>
                    <p>Severe situations requiring immediate attention</p>
                </div>
            </div>
            <div class="legend-item">
                <span class="icon blue">🌊</span>
                <div>
                    <h4>Flooded Areas</h4>
                    <p>Locations currently submerged by water</p>
                </div>
            </div>
            <div class="legend-item">
                <span class="icon green">📍</span>
                <div>
                    <h4>Your Location</h4>
                    <p>Shows where you are on the map</p>
                </div>
            </div>
            <div class="legend-item">
                <span class="icon gray">🕒</span>
                <div>
                    <h4>Last Updated</h4>
                    <p>Time of the latest map update</p>
                </div>
            </div>
        </div>

    </div>

    <!-- Buttons -->
    <div class="button-row">
        <a href="user-water-level-data.php" class="btn-white">Water Level Data</a>
        <a href="user-affected-areas.php"   class="btn-teal">View Affected Areas</a>
    </div>

</div>
</main>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="../assets/js/user-view-map.js"></script>

</body>
</html>