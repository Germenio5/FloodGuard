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
        <a href="user-water-level-data.php" class="btn-white">Water Level Data</a>
        <a href="user-affected-areas.php"   class="btn-teal">View Affected Areas</a>
    </div>

</div>
</main>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
const TYPE = {
    danger:       { color: '#dc2626', emoji: '🌊', label: 'Danger Zone'       },
    alert:        { color: '#f97316', emoji: '⚠️',  label: 'Alert Area'        },
    normal:       { color: '#10b981', emoji: '✅',  label: 'Normal'            },
    evacuation:   { color: '#3b82f6', emoji: '🏠',  label: 'Evacuation Center' },
    road_closure: { color: '#6b7280', emoji: '🚫',  label: 'Road Closure'      },
    rainfall:     { color: '#6366f1', emoji: '☔',  label: 'Heavy Rainfall'    },
};

// Map centered on Bacolod City
const map = L.map('map', { center: [10.6765, 122.9509], zoom: 14 });

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19
}).addTo(map);

function makeIcon(type) {
    const c = TYPE[type] || TYPE.danger;
    return L.divIcon({
        className: '',
        html: `<div class="cm" style="background:${c.color}"><span>${c.emoji}</span></div>`,
        iconSize: [36,36], iconAnchor: [18,36], popupAnchor: [0,-40]
    });
}

// Load markers from database
function loadMarkersFromDatabase() {
    fetch('../controllers/marker-api.php?action=list')
        .then(res => res.json())
        .then(result => {
            if (result.success && result.markers) {
                // Display all markers on the user map
                result.markers.forEach(m => {
                    const lm = L.marker([m.lat, m.lng], {
                        icon: makeIcon(m.type)
                    }).addTo(map);

                    // Show popup with marker information
                    const typeInfo = TYPE[m.type] || TYPE.danger;
                    const popupContent = `
                        <div style="min-width: 280px; font-family: Arial, sans-serif;">
                            <div style="padding: 12px 14px; border-bottom: 3px solid ${typeInfo.color}; margin-bottom: 10px;">
                                <h3 style="margin: 0 0 8px 0; font-size: 16px; color: #1f2937;">${m.title}</h3>
                                <div style="display: flex; align-items: center; gap: 8px; font-size: 13px;">
                                    <span style="font-size: 18px;">${typeInfo.emoji}</span>
                                    <strong style="color: ${typeInfo.color};">${typeInfo.label}</strong>
                                </div>
                            </div>
                            ${m.description ? `<div style="padding: 0 14px 12px 14px; font-size: 13px; color: #4b5563; line-height: 1.5;">${m.description}</div>` : ''}
                        </div>
                    `;
                    lm.bindPopup(popupContent, { maxWidth: 300 });
                    
                    lm.bindTooltip(
                        `<strong>${m.title}</strong><br><em>${TYPE[m.type]?.label}</em>`,
                        { direction: 'top', offset: [0,-38] }
                    );
                });
            }
        })
        .catch(err => console.error('Failed to load markers:', err));
}

// Load markers when page loads
document.addEventListener('DOMContentLoaded', loadMarkersFromDatabase);
</script>

</body>
</html>