<?php
include '../controller/water-level-data-controller.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Water Level Data History</title>
    <link rel="stylesheet" href="../css/userwaterleveldata.css">
</head>

<body>

<?php include 'include/user-sidebar.php'; ?>

<main>
<div class="main-wrapper">

    <!-- Page Header -->
    <div class="page-header">
        <h1>Water Level Data History</h1>

        <p>
        Water level information provides insight into the current state of rivers,
        creeks, and other waterways. It helps residents and authorities understand
        potential flood risks, track changes over time, and make informed decisions
        to stay safe.
        </p>
    </div>

    <!-- Recent Data Section -->
    <div class="data-section">
        <h2>Recent Monitoring Data</h2>

        <!-- Data Table -->
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>AREA</th>
                        <th>TREND</th>
                        <th>DATE</th>
                        <th>HEIGHT (M)</th>
                        <th>SPEED (M/HR)</th>
                        <th>STATUS</th>
                    </tr>
                </thead>

                <tbody>

                <?php foreach ($waterLevels as $data): ?>
                    <tr>
                        <td><?= $data["area"] ?></td>

                        <td>
                            <?= getTrendBadge($data["trend"]) ?>
                        </td>

                        <td><?= $data["date"] ?></td>

                        <td><?= $data["height"] ?>m</td>

                        <td><?= $data["speed"] ?></td>

                        <td>
                            <?= getStatusBadge($data["status"]) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>

                </tbody>
            </table>
        </div>

        <!-- Pagination and Download -->
        <div class="table-footer">
            <div class="pagination">
                <button class="page-btn">Previous</button>
                <button class="page-btn active">1</button>
                <button class="page-btn">2</button>
                <button class="page-btn">3</button>
                <button class="page-btn">4</button>
                <button class="page-btn">5</button>
                <span>...</span>
                <button class="page-btn">9</button>
                <button class="page-btn">Next</button>
            </div>

            <button class="download-btn">Download Data</button>
        </div>

    </div>
</div>
</main>

</body>
</html>
