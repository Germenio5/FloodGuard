<?php
include '../controllers/user-dashboard-controller.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>FloodGuard Dashboard</title>

    <link rel="stylesheet" href="../assets/css/user-dashboard.css">
</head>

<body>  

<?php include 'include/user-sidebar.php'; ?>

<main>
<div class="main-wrapper">
        
    <!-- ===== TOP HEADER ===== -->
    <div class="top-section">
        <h1>Hello <?= htmlspecialchars($user['name']) ?>!</h1>

        <div class="status-badge">
            <div>
                <strong><?= htmlspecialchars($user['status']) ?></strong>
                <a href="#">Edit Status</a>
            </div>
        </div>
    </div>


    <!-- ===== WATER LEVEL CARD ===== -->
    <div class="water-card">

        <div class="card-header">
            <h2>Water Level Data</h2>
            <p>Updated <?= $waterLevel['date'] ?></p>
        </div>

        <div class="location-section">
            <h3><?= $waterLevel['bridge'] ?></h3>
            <p><?= $waterLevel['location'] ?></p>

            <p class="label">Current Level:</p>
                
            <div class="progress-bar">
                <div class="progress-fill"
                     style="<?= getProgressWidth($waterLevel['percentage']) ?>">
                </div>
            </div>

            <p class="level-text">
                <?= formatLevelText($waterLevel['current'], $waterLevel['max']) ?>
            </p>
        </div>


        <!-- INFO GRID -->
        <div class="info-grid">

            <div class="info-item">
                <p>Trend</p>
                <h4 class="orange">
                    <?= $waterLevel['trend'] ?>
                </h4>
            </div>

            <div class="info-item">
                <p>Speed</p>
                <h4><?= $waterLevel['speed'] ?></h4>
            </div>

            <div class="info-item">
                <p>Last Update</p>
                <h4><?= $waterLevel['last_update'] ?></h4>
            </div>

        </div>


        <!-- ALERT BOX -->
        <div class="alert-box">

            <div class="alert-header">
                <span>Status</span>
                <strong><?= $waterLevel['status'] ?></strong>
            </div>

            <ul>
            <?php foreach($alertTips as $tip): ?>
                <li><?= htmlspecialchars($tip) ?></li>
            <?php endforeach; ?>
            </ul>

            <button class="report-btn">Report Now</button>
        </div>

    </div>


    <!-- GRAPH -->
    <div class="graph-box">
        <p>GRAPH PLACEHOLDER</p>
    </div>


    <!-- ACTION CARDS -->
    <div class="action-row">

    <?php foreach($actions as $act): ?>
        <div class="action-box">
            <h3><?= $act['title'] ?></h3>

            <p><?= $act['description'] ?></p>

            <a href="<?= $act['link'] ?>">
                <?= $act['label'] ?>
            </a>
        </div>
    <?php endforeach; ?>

    </div>


    <!-- HOTLINE -->
    <div class="hotline-box">
        <span class="big-icon">⚠️</span>

        <div>
            <h3>Emergency Hotline</h3>

            <p>
            You can also reach the Disaster Risk Reduction & Management Office (DRRMO)
            for help with emergencies and rescue services.
            </p>

            <a href="tel:<?= $hotline['tel'] ?>" class="phone">
                <?= $hotline['phone'] ?>
            </a>

            <p class="small">
                <?= $hotline['note'] ?>
            </p>
        </div>
    </div>


</div>
</main>

</body>
</html>
