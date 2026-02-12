<?php 
$current_page = basename($_SERVER['PHP_SELF']); 
include '../controllers/admin-sidebar-controller.php'; 
?>

<link rel="stylesheet" href="../assets/css/sidebar.css">
<script src="../assets/js/sidebar.js" defer></script>

<aside class="sidebar" id="sidebar">

    <div class="sidebar-header">
        <div class="brand" id="logoToggle"><!-- ITO NA ANG BUTTON -->
            <img src="../assets/images/FloodGuard_logo.png" class="logo" alt="FloodGuard Logo">
            <span class="logo-text">FLOODGUARD</span>
        </div>
    </div>

    <ul class="menu">

        <li class="menu-title">Administration</li>

        <li>
            <a href="admin-dashboard.php"
               class="<?= $current_page == 'admin-dashboard.php' ? 'active' : '' ?>">
                <span class="icon">👥</span>
                <span class="text">Residents</span>
            </a>
        </li>

        <li>
            <a href="admin-water-status.php"
            class="<?= in_array($current_page, $water_pages) ? 'active' : '' ?>">
                <span class="icon">💧</span>
                <span class="text">Water Level Status</span>
            </a>
        </li>

        <li>
            <a href="admin-map-edit.php"
               class="<?= $current_page == 'admin-map-edit.php' ? 'active' : '' ?>">
                <span class="icon">🗺️</span>
                <span class="text">Map</span>
            </a>
        </li>

        <li>
            <a href="admin-report-log.php"
               class="<?= $current_page == 'admin-report-log.php' ? 'active' : '' ?>">
                <span class="icon">📑</span>
                <span class="text">Report Logs</span>
            </a>
        </li>

        <li>
            <a href="../controllers/log-out.php">
                <span class="icon">🚪</span>
                <span class="text">Log Out</span>
            </a>
        </li>

    </ul>
</aside>
