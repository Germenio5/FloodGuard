<?php
include '../controller/admin-map-edit-controller.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>FloodGuard Map</title>
    <link rel="stylesheet" href="../css/adminmapedit.css">
</head>

<body>

<?php include 'include/admin-sidebar.php'; ?>
<main>
<div class="main-wrapper">
    
    <!-- Page Header -->
    <div class="page-top">
        <h1><?= htmlspecialchars($pageTitle) ?></h1>
        <p><?= htmlspecialchars($pageDescription) ?></p>
    </div>

    <!-- Map and Legend -->
    <div class="map-container">
        
        <!-- Map Area -->
        <div class="map-area">
            <img src="<?= htmlspecialchars($mapImage) ?>" 
                 alt="Map Placeholder" 
                 class="map-placeholder">
        </div>

        <!-- Legend Sidebar -->
        <div class="legend-area">

            <?php foreach ($legendItems as $item): ?>

            <div class="legend-item">
                <span class="icon <?= htmlspecialchars($item['color']) ?>">
                    <?= htmlspecialchars($item['icon']) ?>
                </span>

                <div>
                    <h4><?= htmlspecialchars($item['title']) ?></h4>
                    <p><?= htmlspecialchars($item['description']) ?></p>
                </div>
            </div>

            <?php endforeach; ?>

        </div>
    </div>

    <!-- Buttons -->
    <div class="button-row">
        <a href="<?= htmlspecialchars($buttons['water_level']) ?>" 
           class="btn-white">
            Water Level Status
        </a>

        <a href="<?= htmlspecialchars($buttons['edit_map']) ?>" 
           class="btn-teal">
            Edit Map
        </a>
    </div>

</div>
</main>
</body>
</html>
