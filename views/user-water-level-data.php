<?php
include '../controllers/water-level-data-controller.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Water Level Data History</title>
    <link rel="stylesheet" href="../assets/css/userwaterleveldata.css">
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
        <div class="section-header">
            <h2>Recent Monitoring Data</h2>
            
            <!-- Bridge Filter -->
            <div class="filter-box">
                <label for="bridgeFilter">Filter by Bridge:</label>
                <select id="bridgeFilter" name="bridge" onchange="filterByBridge(this.value)">
                    <option value="">All Bridges</option>
                    <?php foreach ($allAreas as $area): ?>
                        <option value="<?= htmlspecialchars($area) ?>" <?= ($selectedBridge === $area) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($area) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

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

        <!-- Pagination Info -->
        <div class="pagination-info">
            <p>Showing page <strong><?php echo $currentPage; ?></strong> of <strong><?php echo $totalPages; ?></strong> | Total records: <strong><?php echo $totalRecords; ?></strong></p>
        </div>

        <!-- Pagination and Download -->
        <div class="table-footer">
            <div class="pagination">
                <?php foreach ($paginationButtons as $btn): ?>
                    <?php if ($btn['disabled']): ?>
                        <button class="page-btn disabled" disabled><?php echo $btn['label']; ?></button>
                    <?php elseif ($btn['active']): ?>
                        <button class="page-btn active"><?php echo $btn['label']; ?></button>
                    <?php else: ?>
                        <a href="?page=<?php echo $btn['page']; ?><?php echo $selectedBridge ? '&bridge=' . urlencode($selectedBridge) : ''; ?>" class="page-btn"><?php echo $btn['label']; ?></a>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>

            <div class="download-actions">
                <button class="download-btn" title="PDF download feature coming soon">📄 Download PDF</button>
            </div>
        </div>

    </div>
</div>
</main>

<script>
function filterByBridge(bridgeValue) {
    if (bridgeValue) {
        window.location.href = '?bridge=' + encodeURIComponent(bridgeValue);
    } else {
        window.location.href = '?';
    }
}
</script>

</body>
</html>
