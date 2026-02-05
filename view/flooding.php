<?php
include '../controller/flooding-controller.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flooding Information - FloodGuard</title>
    <link rel="stylesheet" href="../css/flooding.css">
</head>
<body>

<?php include 'include/header.php'; ?>

<main class="flooding-page">
<div class="container">

<!-- ALERT STATUS CARDS -->
<section class="alert-section">

<div class="alert-card alert-green">
    <div class="alert-icon"><span>⚠</span></div>
    <div class="alert-content">
        <h3 class="alert-level">Normal</h3>
        <p class="alert-message">Water levels are stable. No immediate action required.</p>
    </div>
</div>

<div class="alert-card alert-orange">
    <div class="alert-icon"><span>⚠</span></div>
    <div class="alert-content">
        <h3 class="alert-level">Alert</h3>
        <p class="alert-message">Water levels are rising. Stay vigilant.</p>
    </div>
</div>

<div class="alert-card alert-red">
    <div class="alert-icon"><span>⚠</span></div>
    <div class="alert-content">
        <h3 class="alert-level">Danger</h3>
        <p class="alert-message">Flooding is imminent. Take precautionary measures.</p>
    </div>
</div>

</section>

<!-- MAIN GRID -->
<div class="content-grid">

<!-- METRICS SECTION -->
<section class="metrics-section">

<h2 class="section-title">Flood Impact Metrics</h2>
<p class="section-subtitle">Important metrics regarding the flood situation</p>

<div class="metrics-buttons">
    <button class="metric-btn active">Water Level Data</button>
    <button class="metric-btn">View Affected Areas</button>
</div>

<div class="metrics-grid">
<?php foreach ($floodMetrics as $metric): ?>
<div class="metric-card">
    <p class="metric-label"><?= htmlspecialchars($metric['label']) ?></p>

    <p class="metric-value <?= isset($metric['is_critical']) ? 'status-value' : '' ?>">
        <?= htmlspecialchars($metric['value']) ?>
    </p>
</div>
<?php endforeach; ?>
</div>

<div class="description">
<p>
Our flood maps provide a clear view of areas that may be affected by flooding,
including rivers, coastal zones, and surface water. They are a public resource
designed to help residents and authorities make informed decisions and stay safe
during flood events.
</p>
</div>

</section>

<!-- MAP SECTION -->
<section class="map-section">
<div class="map-placeholder">
    <p>Map Placeholder</p>
</div>

<a href="<?= BASE_URL ?>view/map.php" class="view-map-btn">
    View Map
</a>
</section>

</div><!-- content-grid -->
</div><!-- container -->
</main>

<?php include 'include/footer.php'; ?>

</body>
</html>


