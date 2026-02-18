<?php
include '../controllers/admin-water-level-status-controller.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Water Status By Area</title>
    <link rel="stylesheet" href="../assets/css/adminwaterstatus.css">
</head>

<body>

<?php include 'include/admin-sidebar.php'; ?>

<main class="water-status-page">
<div class="container">

    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Water Status By Area</h1>
    </div>

    <!-- Bridge Cards Grid -->
    <div class="bridge-grid">

        <?php if (empty($waterStatusData)): ?>
            <div class="no-data-message">
                <p>No affected areas data available.</p>
            </div>
        <?php else: ?>

        <?php foreach($waterStatusData as $bridge): ?>

        <div class="bridge-card">

            <h2 class="bridge-name">
                <?= $bridge['bridge_name'] ?>
            </h2>

            <p class="bridge-location">
                <?= $bridge['location'] ?>
            </p>

            <div class="level-info">
                <span class="level-label">Current Level:</span>

                <span class="level-value">
                    <?= $bridge['current_level'] ?>
                    /
                    <?= $bridge['max_level'] ?>
                </span>
            </div>

            <div class="progress-bar">
                <div class="progress-fill <?= getProgressClass($bridge['status']) ?>"
                     style="width: <?= $bridge['percentage'] ?>%">
                </div>
            </div>

            <p class="progress-percentage">
                <?= $bridge['percentage'] ?>%
            </p>

            <p class="speed-info">
                Speed: <?= $bridge['speed'] ?>
            </p>

            <div class="button-group">
                <a href="admin-edit-water-level-data.php" class="btn btn-outline">
                    Water Level Data
                </a>

                <button class="btn btn-primary">
                    Send Alert
                </button>
            </div>

        </div>

        <?php endforeach; ?>

        <?php endif; ?>

    </div>

</div>
</main>

</body>
</html>
