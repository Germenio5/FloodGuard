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
<script>
const TYPE = {
    bridges:  { color: '#6b7280', emoji: '🌉', label: 'Bridges'        },
    normal:   { color: '#10b981', emoji: '✅', label: 'Normal'         },
    warning:  { color: '#f97316', emoji: '⚠️', label: 'Warning'        },
    danger:   { color: '#dc2626', emoji: '❗', label: 'Danger'         },
    critical: { color: '#b91c1c', emoji: '🚨', label: 'Critical'       },
    flooded:  { color: '#3b82f6', emoji: '🌊', label: 'Flooded Areas'  },
    location: { color: '#10b981', emoji: '📍', label: 'Your Location'  },
};

// helper to translate any legacy/old type values into the current schema
function normalizeType(type) {
    if (TYPE[type]) return type;
    const map = {
        alert: 'warning',
        evacuation: 'bridges',
        road_closure: 'warning',
        rainfall: 'warning'
    };
    return map[type] || 'normal';
} 

// location tracking state
let locationMarker = null;
let locationLastUpdated = null;
let locationWatchId = null;

function formatTimeAgo(ts) {
    const diff = Date.now() - ts;
    const seconds = Math.floor(diff / 1000);
    if (seconds < 60) return `${seconds} second${seconds !== 1 ? 's' : ''} ago`;
    const minutes = Math.floor(seconds / 60);
    if (minutes < 60) return `${minutes} minute${minutes !== 1 ? 's' : ''} ago`;
    const hours = Math.floor(minutes / 60);
    return `${hours} hour${hours !== 1 ? 's' : ''} ago`;
}

function updateLocationMarker(lat, lng) {
    locationLastUpdated = Date.now();
    const locObj = { title: 'Your Location', type: 'location', description: '', updated_at: locationLastUpdated };
    const content = markerPopupContent(locObj);
    if (locationMarker) {
        locationMarker.setLatLng([lat, lng]);
        locationMarker.setPopupContent(content);
    } else {
        locationMarker = L.marker([lat, lng], { icon: makeIcon('location') }).addTo(map);
        locationMarker.bindPopup(content, { autoPan: false });
        locationMarker.on('click', () => {
            locationMarker.getPopup().setContent(markerPopupContent(locObj));
        });
    }
}

// helper to create popup HTML for a marker including updated time
function markerPopupContent(m) {
    const typeInfo = TYPE[ normalizeType(m.type) ] || TYPE.normal;
    const updated = m.updated_at ? formatTimeAgo(new Date(m.updated_at).getTime()) : '';
    return `
        <div style="min-width: 280px; font-family: Arial, sans-serif;">
            <div style="padding: 12px 14px; border-bottom: 3px solid ${typeInfo.color}; margin-bottom: 10px;">
                <h3 style="margin: 0 0 8px 0; font-size: 16px; color: #1f2937;">${m.title}</h3>
                <div style="display: flex; align-items: center; gap: 8px; font-size: 13px;">
                    <span style="font-size: 18px;">${typeInfo.emoji}</span>
                    <strong style="color: ${typeInfo.color};">${typeInfo.label}</strong>
                </div>
            </div>
            ${m.description ? `<div style="padding: 0 14px 12px 14px; font-size: 13px; color: #4b5563; line-height: 1.5;">${m.description}</div>` : ''}
            ${updated ? `<div style="padding: 0 14px 8px 14px; font-size:12px; color:#6b7280;"><em>Updated: ${updated}</em></div>` : ''}
        </div>
    `;
}

function startGeolocation() {
    if (!navigator.geolocation) {
        console.warn('Geolocation not supported');
        return;
    }
    locationWatchId = navigator.geolocation.watchPosition(
        pos => updateLocationMarker(pos.coords.latitude, pos.coords.longitude),
        err => console.error('Location error', err),
        { enableHighAccuracy: true, maximumAge: 60000 }
    );
}

setInterval(() => {
    if (locationMarker && locationMarker.getPopup().isOpen()) {
        const locObj = { title: 'Your Location', type: 'location', description: '', updated_at: locationLastUpdated };
        locationMarker.getPopup().setContent(markerPopupContent(locObj));
    }
}, 30000);


// Map centered on Bacolod City
const map = L.map('map', { center: [10.6765, 122.9509], zoom: 14 });

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19
}).addTo(map);

function makeIcon(type) {
    const t = normalizeType(type);
    const c = TYPE[t] || TYPE.normal;
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
                    // give each marker a timestamp if not present
                    if (!m.updated_at) m.updated_at = new Date().toISOString();
                    const lm = L.marker([m.lat, m.lng], {
                        icon: makeIcon(m.type)
                    }).addTo(map);

                    // Show popup with marker information
                    lm.bindPopup(markerPopupContent(m), { maxWidth: 300 });
                    
                    const labelType = normalizeType(m.type);
                    lm.bindTooltip(
                        `<strong>${m.title}</strong><br><em>${TYPE[labelType].label}</em>`,
                        { direction: 'top', offset: [0,-38] }
                    );
                });
            }
        })
        .catch(err => console.error('Failed to load markers:', err));
}

// Load markers when page loads
document.addEventListener('DOMContentLoaded', () => {
    loadMarkersFromDatabase();
    startGeolocation();
});
</script>

</body>
</html>