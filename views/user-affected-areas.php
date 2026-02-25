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

    <!-- Filter Box -->
    <div class="filter-box" style="margin-bottom: 30px;">
        <form method="GET" class="filter-form">
            <label for="barangayFilter">Filter by Barangay:</label>
            <select id="barangayFilter" name="barangay" onchange="this.form.submit()">
                <option value="">All Barangays</option>
                <?php foreach ($barangays as $barangay): ?>
                    <option value="<?= htmlspecialchars($barangay) ?>" <?= $selectedBarangay === $barangay ? 'selected' : '' ?>>
                        <?= 'Brgy. ' . htmlspecialchars($barangay) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>
    </div>
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
    <div class="pagination-section" style="margin: 20px 0; text-align: center;">
        <?php if (!empty($paginationButtons)): ?>
            <?php foreach ($paginationButtons as $btn): ?>
                <?php if ($btn['active']): ?>
                    <span class="pagination-btn current"><?php echo $btn['label']; ?></span>
                <?php elseif ($btn['disabled']): ?>
                    <span class="pagination-btn disabled" style="opacity:0.5;cursor:not-allowed;"><?php echo $btn['label']; ?></span>
                <?php else: ?>
                    <a href="?page=<?php echo $btn['page']; ?>" class="pagination-btn"><?php echo $btn['label']; ?></a>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

</div>
</main>

</body>
</html>
