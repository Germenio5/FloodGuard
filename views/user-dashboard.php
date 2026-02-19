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

        <?php $badgeClass = (stripos($user['status'], 'danger') !== false || stripos($user['status'], 'danger') !== false) ? 'status-badge danger' : 'status-badge safe'; ?>
        <div id="statusBadge" class="<?= $badgeClass ?>">
            <div>
                <div class="status-left">
                    <span class="label-text"><strong>Current Status:</strong></span>
                    <strong id="currentStatusText"><?= htmlspecialchars($user['status']) ?></strong>
                </div>
                <button id="editStatusBtn" class="report-btn edit-status-btn" type="button" aria-haspopup="dialog">Edit Status</button>
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

            <a href="user-report-flood.php" class="report-btn">Report Now</a>
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

<!-- Status Edit Modal -->
<div id="statusModal" class="status-modal" aria-hidden="true">
    <div class="status-modal-overlay"></div>
    <div class="status-modal-panel" role="dialog" aria-modal="true" aria-labelledby="statusModalTitle">
        <h3 id="statusModalTitle">Update Your Status</h3>
        <p class="muted">Select your current safety status.</p>

        <div class="status-options" data-current="<?= (stripos($user['status'], 'danger') !== false) ? 'In Danger' : 'Safe' ?>">
            <button type="button" class="status-option" data-status="Safe">Safe</button>
            <button type="button" class="status-option" data-status="In Danger">In Danger</button>
        </div>

        <div class="status-actions">
            <button id="saveStatusBtn" class="btn primary">Save</button>
            <button id="closeStatusBtn" class="btn">Cancel</button>
        </div>
    </div>
</div>

<script src="../assets/js/status-modal.js"></script>

</body>
</html>
