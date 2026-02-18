<?php

// Check if user is logged in
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header("Location: login-user.php?error=login_required");
    exit();
}

$current_page = basename($_SERVER['PHP_SELF']);
?>

<link rel="stylesheet" href="../assets/css/sidebar.css">
<script src="../assets/js/sidebar.js" defer></script>

<aside class="sidebar" id="sidebar">

    <div class="sidebar-header">
        <div class="brand" id="logoToggle">
            <img src="../assets/images/FloodGuard_logo.png" class="logo" alt="FloodGuard Logo">
            <span class="logo-text">FLOODGUARD</span>
        </div>
    </div>

    <ul class="menu">

        <li class="menu-title">Main</li>

        <li>
            <a href="../index.php"
               class="<?= $current_page == 'index.php' ? 'active' : '' ?>">
                <span class="icon">🏠</span>
                <span class="text">Home</span>
            </a>
        </li>

        <li>
            <a href="aboutus.php"
               class="<?= $current_page == 'aboutus.php' ? 'active' : '' ?>">
                <span class="icon">ℹ️</span>
                <span class="text">About Us</span>
            </a>
        </li>

        <li>
            <a href="news.php"
               class="<?= $current_page == 'news.php' ? 'active' : '' ?>">
                <span class="icon">📰</span>
                <span class="text">News</span>
            </a>
        </li>

        <li class="menu-title">Services</li>

        <li>
            <a href="user-dashboard.php"
               class="<?= $current_page == 'user-dashboard.php' ? 'active' : '' ?>">
                <span class="icon">📊</span>
                <span class="text">Dashboard</span>
            </a>
        </li>

        <li>
            <a href="user-affected-areas.php"
               class="<?= $current_page == 'user-affected-areas.php' ? 'active' : '' ?>">
                <span class="icon">📍</span>
                <span class="text">Monitor Areas</span>
            </a>
        </li>

        <li>
            <a href="user-water-level-data.php"
               class="<?= $current_page == 'user-water-level-data.php' ? 'active' : '' ?>">
                <span class="icon">💧</span>
                <span class="text">Water Level Data</span>
            </a>
        </li>

        <li>
            <a href="user-view-map.php"
               class="<?= $current_page == 'user-view-map.php' ? 'active' : '' ?>">
                <span class="icon">🗺️</span>
                <span class="text">Map</span>
            </a>
        </li>

        <li>
            <a href="user-report-flood.php"
               class="<?= $current_page == 'user-report-flood.php' ? 'active' : '' ?>">
                <span class="icon">⚠️</span>
                <span class="text">Report a Flood</span>
            </a>
        </li>

        <li>
            <a href="user-profile-settings.php"
               class="<?= $current_page == 'user-profile-settings.php' ? 'active' : '' ?>">
                <span class="icon">👤</span>
                <span class="text">Profile Settings</span>
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
