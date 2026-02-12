<?php
include '../controllers/affected-areas-controller.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Affected Areas - Water Level Monitoring</title>

    <link rel="stylesheet" href="../assets/css/useraffectedareas.css">
</head>

<body>
<?php include 'include/user-sidebar.php'; ?>

<main class="affected-areas-page">
<div class="container">

    <div class="page-header">
        <h1 class="page-title">Affected Areas</h1>
        <p class="page-subtitle">Creaks / Bridges</p>
    </div>

     <!-- Alert Status Cards -->
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

    <!-- ===== BRIDGE LIST FROM CONTROLLER ===== -->
    <div class="bridge-grid">

    <?php foreach($bridges as $bridge): ?>

        <div class="bridge-card">

            <h2 class="bridge-name">
                <?= htmlspecialchars($bridge['name']) ?>
            </h2>

            <p class="bridge-location">
                <?= htmlspecialchars($bridge['location']) ?>
            </p>

            <div class="level-info">
                <span>Current Level:</span>

                <span>
                    <?= $bridge['current_level'] ?>m /
                    <?= $bridge['max_level'] ?>m
                </span>
            </div>

            <div class="progress-bar">
                <div class="progress-fill"
                    style="
                    width: <?= getPercentage($bridge['current_level'], $bridge['max_level']) ?>%;
                    background-color: <?= getStatusColor($bridge['status']) ?>;">
                </div>
            </div>

            <p class="speed-info">
                Speed: <?= $bridge['speed'] ?> m/min
            </p>

            <div class="button-group">
                <a href="user-water-level-data.php" class="btn btn-outline">
                    Water Level Data
                </a>

                <a href="user-view-map.php" class="btn btn-primary">
                    More Details
                </a>
            </div>

        </div>

    <?php endforeach; ?>

    </div>


    <!-- PAGINATION UI -->
    <div class="pagination"> 
        <button class="page-btn">Previous</button> 
        <button class="page-btn active">1</button> 
        <button class="page-btn">2</button> 
        <button class="page-btn">3</button> 
        <button class="page-btn">4</button> 
        <button class="page-btn">5</button> 
        <span class="page-dots">......</span> 
        <button class="page-btn">9</button> 
        <button class="page-btn">Next</button> 
    </div>

</div>
</main>

</body>
</html>
