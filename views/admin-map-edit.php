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
            <button class="tool-btn" data-type="bridges">🌉 Bridges</button>
            <button class="tool-btn" data-type="normal">✅ Normal</button>
            <button class="tool-btn" data-type="warning">⚠️ Warning</button>
            <button class="tool-btn" data-type="danger">❗ Danger</button>
            <button class="tool-btn" data-type="critical">🚨 Critical</button>
            <button class="tool-btn" data-type="flooded">🌊 Flooded Area</button>
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
                <span class="icon gray">🌉</span>
                <div><h4>Bridges</h4><p>Marked bridge locations on the map</p></div>
            </div>
            <div class="legend-item">
                <span class="icon green">✅</span>
                <div><h4>Normal</h4><p>Areas with no reported issues</p></div>
            </div>
            <div class="legend-item">
                <span class="icon orange">⚠️</span>
                <div><h4>Warning</h4><p>Regions under caution for possible flooding</p></div>
            </div>
            <div class="legend-item">
                <span class="icon red">❗</span>
                <div><h4>Danger</h4><p>Hazardous areas with confirmed flooding</p></div>
            </div>
            <div class="legend-item">
                <span class="icon red">🚨</span>
                <div><h4>Critical</h4><p>Severe situations requiring immediate attention</p></div>
            </div>
            <div class="legend-item">
                <span class="icon blue">🌊</span>
                <div><h4>Flooded Areas</h4><p>Locations currently submerged by water</p></div>
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
            <option value="bridges">🌉 Bridges</option>
            <option value="normal">✅ Normal</option>
            <option value="warning">⚠️ Warning</option>
            <option value="danger">❗ Danger</option>
            <option value="critical">🚨 Critical</option>
            <option value="flooded">🌊 Flooded Areas</option>
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