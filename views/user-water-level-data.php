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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>

<?php include 'include/user-sidebar.php'; ?>

<main>
<div class="main-wrapper">

    <!-- Page Header -->
    <div class="page-header">
        <h1>Water Level Data History</h1>
    </div>

    <!-- Recent Data Section -->
    <div class="data-section">
        <div class="section-header">
            <!-- Bridge Filter -->
            <div class="filter-box">
                <label for="bridgeFilter">Select Bridge:</label>
                <select id="bridgeFilter" name="bridge" onchange="filterByBridge(this.value)">
                    <option value="" selected>Select Bridges</option>
                    <?php foreach ($allAreas as $area): ?>
                        <option value="<?= htmlspecialchars($area) ?>" <?= ($selectedBridge === $area) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($area) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <?php if ($selectedBridge): ?>
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
                    <?php
                        $isPrev = $btn['label'] === 'Previous';
                        $isNext = $btn['label'] === 'Next';
                        $extraClass = ($isPrev || $isNext) ? '' : ' page-num';
                    ?>
                    <?php if ($btn['disabled']): ?>
                        <button class="page-btn disabled<?php echo $extraClass; ?>" disabled><?php echo $btn['label']; ?></button>
                    <?php elseif ($btn['active']): ?>
                        <button class="page-btn active<?php echo $extraClass; ?>"><?php echo $btn['label']; ?></button>
                    <?php else: ?>
                        <a href="?page=<?php echo $btn['page']; ?>&bridge=<?php echo urlencode($selectedBridge); ?>" class="page-btn<?php echo $extraClass; ?>"><?php echo $btn['label']; ?></a>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>

            <div class="download-actions">
                <a href="../controllers/download-water-level-pdf.php?bridge=<?php echo urlencode($selectedBridge); ?>" class="download-btn" title="Download water level data as PDF">📄 Download PDF</a>
            </div>
        </div>

        <!-- Water Level Chart -->
        <div class="chart-container">
            <h3>Water Level History</h3>
            <canvas id="waterLevelChart"></canvas>
        </div>

        <?php if ($selectedBridge): ?>
        <script>
        const waterLevelData = <?php echo json_encode($waterLevels); ?>;
        </script>
        <?php endif; ?>

        <?php else: ?>
        <!-- No Bridge Selected Message -->
        <div class="empty-state">
            <p class="empty-message">Select a bridge to display water level data</p>
        </div>
        <?php endif; ?>

    </div>
</div>
</main>

<script src="../assets/js/filter-bridge.js"></script>
<script src="../assets/js/user-water-level-data.js"></script>

</body>
</html>
