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
        <div class="alert-card alert-warning">
            <div class="alert-icon"><span>⚠</span></div>
            <div class="alert-content">
                <h3 class="alert-level">Warning</h3>
                <p class="alert-message">Water levels are within normal range. Remain aware and continue monitoring.</p>
            </div>
        </div>

        <div class="alert-card alert-danger">
            <div class="alert-icon"><span>⚠</span></div>
            <div class="alert-content">
                <h3 class="alert-level">Danger</h3>
                <p class="alert-message">Water levels are rising rapidly. Be prepared to evacuate at any moment.</p>
            </div>
        </div>

        <div class="alert-card alert-critical">
            <div class="alert-icon"><span>⚠</span></div>
            <div class="alert-content">
                <h3 class="alert-level">Critical</h3>
                <p class="alert-message">Flooding is imminent. Evacuate immediately to higher ground or shelters.</p>
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
                    <?= number_format($bridge['current_level'], 2) ?>m /
                    <?= number_format($bridge['max_level'], 2) ?>m
                </span>
            </div>

            <div class="progress-bar">
                <div class="progress-fill <?= getProgressClass($bridge['status']) ?>"
                    style="width: <?= $bridge['percentage'] ?>%;">
                </div>
            </div>

            <p class="status-label">
                Status: <?= getStatusLabel($bridge['status']) ?> (<?= $bridge['percentage'] ?>%)
            </p>

            <p class="speed-info">
                Speed: <?= number_format($bridge['speed'], 2) ?> m/h
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


    <!-- Pagination Info -->
    <div class="pagination-info">
        <p>Showing page <strong><?php echo $currentPage; ?></strong> of <strong><?php echo $totalPages; ?></strong> | Total areas: <strong><?php echo $totalRecords; ?></strong></p>
    </div>

    <!-- PAGINATION UI -->
    <div class="pagination">
        <?php foreach ($paginationButtons as $btn): ?>
            <?php if ($btn['disabled']): ?>
                <button class="page-btn disabled" disabled><?php echo $btn['label']; ?></button>
            <?php elseif ($btn['active']): ?>
                <button class="page-btn active"><?php echo $btn['label']; ?></button>
            <?php else: ?>
                <a href="?page=<?php echo $btn['page']; ?>" class="page-btn"><?php echo $btn['label']; ?></a>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>

</div>
</main>

</body>
</html>
