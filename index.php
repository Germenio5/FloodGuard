<?php
include 'controllers/homepage-controller.php';
?>

<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FloodGuard - Home</title>
    <link rel="stylesheet" href="assets/css/index.css">
</head>
<body>

<?php include 'views/include/header.php'; ?>

<main>

<!-- Hero Section -->
<section id="hero" class="hero-section">
    <div class="hero-content">
        <h1>FloodGuard</h1>
        <p>A flood monitoring website that helps communities track rising water levels near bridges and flood-prone areas.</p>
        <div class="hero-buttons">
            <a href="#about" class="btn btn-learn">Learn More</a>
            <a href="views/user-dashboard.php" class="btn btn-status">View Dashboard</a>
        </div>
    </div>
</section>

<!-- About Section -->
<section id="about" class="about-section">
    <div class="about-inner">
        <h2 class="section-heading">About FloodGuard</h2>
        <p>FloodGuard helps communities stay prepared by monitoring water levels at key bridge locations and delivering timely alerts through SMS. Our goal is to reduce risk, improve situational awareness, and support safer decision making when flood conditions change.</p>

        <div class="about-grid">
            <div class="about-card">
                <img src="assets/images/community.png" alt="Community icon" class="about-icon">
                <h3>Purpose</h3>
                <p>Provide a reliable way to track rising water levels and share real-time flood status with residents and responders.</p>
            </div>

            <div class="about-card">
                <img src="assets/images/insights.png" alt="Insights icon" class="about-icon">
                <h3>Who it helps</h3>
                <p>Communities living near bridges and flood-prone areas, local responders, and anyone seeking early warning of dangerous water levels.</p>
            </div>

            <div class="about-card">
                <img src="assets/images/sustainability.png" alt="Impact icon" class="about-icon">
                <h3>Community Impact</h3>
                <p>By centralizing water level data and automating alerts, FloodGuard helps people take action sooner and stay safer during flood events.</p>
            </div>
        </div>
    </div>
</section>

<!-- How It Works -->
<section id="how" class="how-it-works">
    <h2 class="section-heading">How It Works</h2>
    <p class="section-subtitle">A simple, reliable process that turns water readings into actionable alerts.</p>

    <div class="steps-grid">
        <div class="step-card">
            <div class="step-number">1</div>
            <h3>Water level monitoring</h3>
            <p>Sensors collect water height data at bridges and key locations in real time.</p>
        </div>

        <div class="step-card">
            <div class="step-number">2</div>
            <h3>Data processing</h3>
            <p>The system analyzes readings and compares them against flood thresholds for each site.</p>
        </div>

        <div class="step-card">
            <div class="step-number">3</div>
            <h3>Alert classification</h3>
            <p>Water levels are classified into Warning, Alert, or Danger categories based on set thresholds.</p>
        </div>

        <div class="step-card">
            <div class="step-number">4</div>
            <h3>SMS notification</h3>
            <p>When thresholds are exceeded, residents receive SMS alerts so they can take precautionary actions.</p>
        </div>
    </div>
</section>

<!-- Key Features -->
<section id="features" class="features-section">
    <h2 class="section-heading">Key Features</h2>
    <p class="section-subtitle">Tools and capabilities that help you stay informed and safe.</p>

    <div class="features-grid">
        <div class="feature-card">
            <h3>Real-time monitoring</h3>
            <p>See current water levels across monitored bridges and areas instantly.</p>
        </div>
        <div class="feature-card">
            <h3>Three-level alert system</h3>
            <p>Warning, Alert, and Danger statuses help you understand how serious conditions are.</p>
        </div>
        <div class="feature-card">
            <h3>SMS notifications</h3>
            <p>Automated messages ensure people get alerts even without opening the app.</p>
        </div>
        <div class="feature-card">
            <h3>Monitoring dashboard</h3>
            <p>Use the dashboard to view live bridge status and recent flood reports.</p>
        </div>
    </div>
</section>

<!-- Latest Flood Reports -->
<section class="reports-section">
    <h2 class="section-heading">Latest Flood Reports</h2>
    <p class="subtitle">Get details about recent flood levels.</p>
    <a href="views/news.php" class="btn">View Reports</a>
</section>

</main>

<?php include 'views/include/footer.php'; ?>

</body>
</html>