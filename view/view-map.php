<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>FloodGuard Map</title>
    <link rel="stylesheet" href="../css/viewmap.css">
</head>
<body>
<main>
    <div class="main-wrapper">
        
        <!-- Page Header -->
        <div class="page-top">
            <h1>Map</h1>
            <p>Current Flood Forecast to get an idea of current flooding</p>
        </div>

        <!-- Map and Legend -->
        <div class="map-container">
            
            <!-- Map Area -->
            <div class="map-area">
                <img src="../images/placeholderlngdnay.png" alt="Map Placeholder" class="map-placeholder">
            </div>

            <!-- Legend Sidebar -->
            <div class="legend-area">
                
                <div class="legend-item">
                    <span class="icon blue">🌊</span>
                    <div>
                        <h4>Flooded Areas</h4>
                        <p>Areas currently affected by flooding (color coded)</p>
                    </div>
                </div>

                <div class="legend-item">
                    <span class="icon orange">⚠️</span>
                    <div>
                        <h4>High Risk Zones</h4>
                        <p>Likely to flood or already experiencing deep water</p>
                    </div>
                </div>

                <div class="legend-item">
                    <span class="icon gray">☔</span>
                    <div>
                        <h4>Heavy Rainfall</h4>
                        <p>Areas with ongoing or expected heavy rain</p>
                    </div>
                </div>

                <div class="legend-item">
                    <span class="icon blue">📈</span>
                    <div>
                        <h4>Water Level Rising</h4>
                        <p>Rivers or streets with increasing water levels</p>
                    </div>
                </div>

                <div class="legend-item">
                    <span class="icon red">🏠</span>
                    <div>
                        <h4>Evacuation Centers</h4>
                        <p>Safe locations for temporary shelter</p>
                    </div>
                </div>

                <div class="legend-item">
                    <span class="icon red">🚫</span>
                    <div>
                        <h4>Road Closures</h4>
                        <p>Roads not passable due to flooding</p>
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
            <a href="waterleveldata.php" class="btn-white">Water Level Data</a>
            <a href="affected-areas.php" class="btn-teal">View Affected Areas</a>
        </div>
    </div>
</main>
</body>
</html>