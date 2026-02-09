<?php
include 'controller/homepage-controller.php';
?>

<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FloodGuard - Home</title>
    <link rel="stylesheet" href="css/index.css">
</head>
<body>

<?php include 'view/include/header.php'; ?>

<main>

<!-- Hero Section -->
<section class="hero-section">
    <div class="hero-content">
        <h1>Are You in Danger?</h1>
        <p>Find out if you're in a flood danger zone and get immediate assistance.</p>
        <div class="hero-buttons">
            <a href="view/aboutus.php" class="btn btn-learn">Learn More</a>
            <a href="view/user-dashboard.php" class="btn btn-status">Check My Status</a>
        </div>
    </div>
</section>

<!-- About Section -->
<section class="about-section">
    <h2>About FloodGuard</h2>
    <p>FloodGuard offers several features through its web application. These include an interactive flood map that shows affected areas, real-time updates on flood depth and water movement, and alerts to inform users of changing conditions.</p>
    <a href="view/aboutus.php">Learn more about our website</a>
</section>

<!-- Cards Section -->
<section class="cards-container">
    <div class="card">
        <h3>Monitor Area</h3>
        <p>Discover the latest flood conditions, area-specific alerts, and safety information.</p>
        <a href="view/user-view-map.php">View Area →</a>
    </div>

    <div class="card">
        <h3>Report</h3>
        <p>Report flood incidents in your area to help the community stay informed.</p>
        <a href="view/user-report-flood.php">Report Now →</a>
    </div>
</section>

<!-- Reports Section -->
<section class="reports-section">
    <h2>Latest Flood Reports</h2>
    <p class="subtitle">Get details about recent flood levels.</p>
    <a href="view/news.php" class="btn">View Reports</a>

    <br><br><br><br>
</section>

</main>

<?php include 'view/include/footer.php'; ?>

</body>
</html>