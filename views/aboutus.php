<?php
include '../controllers/aboutus-controller.php';
?>

<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FloodGuard - Flood Monitoring System</title>
    <link rel="stylesheet" href="../assets/css/aboutus.css">
</head>

<body>

<?php include 'include/header.php'; ?>

<main>

<!-- HERO SECTION -->
<section class="hero">
    <div class="container">
        <h1>About FloodGuard</h1>

        <p>
        FloodGuard offers several features through its web application. These include an interactive flood map that shows affected areas, real-time updates on flood depth and water movement, and alerts to inform users of changing conditions. The platform also provides safety tips, emergency contact details, and travel guidance to help users avoid flooded routes.
        </p>

    </div>
</section>



<!-- FEATURES SECTION -->
<section class="features">
<div class="container">
<div class="features-grid">

<?php foreach($featureList as $feature): ?>

    <div class="feature-card">

        <h2><?= $feature['title'] ?></h2>

        <p><?= $feature['description'] ?></p>

        <a href="<?= $feature['link'] ?>">
            <?= $feature['button'] ?>
        </a>

    </div>

<?php endforeach; ?>

</div>
</div>
</section>



<!-- MISSION SECTION -->
<section class="mission">
<div class="container">

    <h2>Our Mission</h2>

    <p class="mission-subtitle">
        Our mission is to empower communities with timely and accurate flood information.
    </p>


<div class="mission-grid">

<?php foreach($missionList as $mission): ?>

<div class="mission-card">

    <div class="mission-icon">
        <img src="<?= $mission['image'] ?>"/>
    </div>

    <div class="mission-content">

        <h3><?= $mission['title'] ?></h3>

        <p><?= $mission['description'] ?></p>

    </div>

</div>

<?php endforeach; ?>

</div>
</div>
</section>


</main>

<?php include 'include/footer.php'; ?>

</body>
</html>
