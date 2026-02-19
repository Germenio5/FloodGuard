<?php
include '../controllers/admin-map-edit-controller.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>FloodGuard – Map Edit</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="../assets/css/adminmapedit.css">
</head>
<body>

<?php include 'include/admin-sidebar.php'; ?>

<main>
<div class="main-wrapper">

    <!-- Page Header -->
    <div class="page-top">
        <h1><?= htmlspecialchars($pageTitle) ?></h1>
        <p><?= htmlspecialchars($pageDescription) ?></p>
    </div>

    <!-- Toolbar (admin only) -->
    <div class="map-toolbar">
        <div class="toolbar-left">
            <span class="toolbar-label">Add Marker:</span>
            <button class="tool-btn" data-type="danger">🌊 Danger Zone</button>
            <button class="tool-btn" data-type="alert">⚠️ Alert Area</button>
            <button class="tool-btn" data-type="normal">✅ Normal</button>
            <button class="tool-btn" data-type="evacuation">🏠 Evacuation</button>
            <button class="tool-btn" data-type="road_closure">🚫 Road Closure</button>
            <button class="tool-btn" data-type="rainfall">☔ Rainfall</button>
        </div>
        <div class="toolbar-right">
            <span id="mode-indicator" class="mode-indicator">Mode: View</span>
            <button id="cancel-add" class="btn-cancel" style="display:none">✕ Cancel</button>
        </div>
    </div>

    <!-- Map + Legend side by side (same as user view) -->
    <div class="map-container">

        <!-- Map Area -->
        <div class="map-area">
            <div id="map"></div>
        </div>

        <!-- Legend Sidebar -->
        <div class="legend-area">
            <div class="legend-item">
                <span class="icon blue">🌊</span>
                <div><h4>Flooded Areas</h4><p>Areas currently affected by flooding (color coded)</p></div>
            </div>
            <div class="legend-item">
                <span class="icon orange">⚠️</span>
                <div><h4>High Risk Zones</h4><p>Likely to flood or already experiencing deep water</p></div>
            </div>
            <div class="legend-item">
                <span class="icon gray">☔</span>
                <div><h4>Heavy Rainfall</h4><p>Areas with ongoing or expected heavy rain</p></div>
            </div>
            <div class="legend-item">
                <span class="icon blue">📈</span>
                <div><h4>Water Level Rising</h4><p>Rivers or streets with increasing water levels</p></div>
            </div>
            <div class="legend-item">
                <span class="icon red">🏠</span>
                <div><h4>Evacuation Centers</h4><p>Safe locations for temporary shelter</p></div>
            </div>
            <div class="legend-item">
                <span class="icon red">🚫</span>
                <div><h4>Road Closures</h4><p>Roads not passable due to flooding</p></div>
            </div>
            <div class="legend-item">
                <span class="icon green">📍</span>
                <div><h4>Your Location</h4><p>Shows where you are on the map</p></div>
            </div>
            <div class="legend-item">
                <span class="icon gray">🕒</span>
                <div><h4>Last Updated</h4><p>Time of the latest map update</p></div>
            </div>
        </div>

    </div>

    <!-- Buttons -->
    <div class="button-row">
        <a href="<?= htmlspecialchars($buttons['water_level']) ?>" class="btn-white">Water Level Status</a>
    </div>

</div>
</main>

<!-- Add/Edit Marker Modal -->
<div id="marker-modal" class="modal-overlay" style="display:none">
    <div class="modal-box">
        <h3 id="modal-title">Add Marker</h3>

        <input type="hidden" id="marker-id">
        <input type="hidden" id="marker-lat">
        <input type="hidden" id="marker-lng">

        <label>Title *</label>
        <input type="text" id="marker-title-input" placeholder="e.g. Flooded area near creek" maxlength="150">

        <label>Description</label>
        <textarea id="marker-desc-input" placeholder="Additional details..." rows="3"></textarea>

        <label>Type</label>
        <select id="marker-type-select">
            <option value="danger">🌊 Danger Zone</option>
            <option value="alert">⚠️ Alert Area</option>
            <option value="normal">✅ Normal</option>
            <option value="evacuation">🏠 Evacuation Center</option>
            <option value="road_closure">🚫 Road Closure</option>
            <option value="rainfall">☔ Heavy Rainfall</option>
        </select>

        <div class="modal-actions">
            <button id="modal-delete" class="btn-delete" style="display:none">🗑 Delete</button>
            <div style="display:flex;gap:10px;margin-left:auto">
                <button id="modal-cancel" class="btn-modal-cancel">Cancel</button>
                <button id="modal-save"   class="btn-modal-save">Save Marker</button>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="../assets/js/admin-map.js"></script>

</body>
</html>