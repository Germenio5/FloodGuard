<?php
include '../controller/map-controller.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>FloodGuard Map</title>
    <link rel="stylesheet" href="../css/viewmap.css">
</head>

<body>
<<<<<<< HEAD

<?php include 'include/sidebar.php'; ?>

=======
>>>>>>> c87642a718ea0cea7d8663560bb0185f722a8ee2
<main>
<div class="main-wrapper">

    <!-- Page Header -->
    <div class="page-top">
        <h1><?= $pageTitle ?></h1>
        <p><?= $pageDescription ?></p>
    </div>

    <!-- Map and Legend -->
    <div class="map-container">

        <!-- Map Area -->
        <div class="map-area">
            <img src="<?= $mapImage ?>" alt="Map Placeholder" class="map-placeholder">
        </div>

        <!-- Legend Sidebar -->
        <div class="legend-area">

            <?php foreach ($legendItems as $item): ?>
            <div class="legend-item">
                <span class="icon <?= $item['color'] ?>">
                    <?= $item['icon'] ?>
                </span>

                <div>
                    <h4><?= $item['title'] ?></h4>
                    <p><?= $item['desc'] ?></p>
                </div>
            </div>
            <?php endforeach; ?>

        </div>
    </div>

    <!-- Buttons -->
    <div class="button-row">
        <a href="waterleveldata.php" class="btn-white">Water Level Data</a>
        <a href="affectedareas.php" class="btn-teal">View Affected Areas</a>
    </div>

</div>
</main>

</body>
</html>
