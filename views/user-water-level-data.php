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
            <h3>Water Level Trend</h3>
            <canvas id="waterLevelChart"></canvas>
        </div>

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

<script>
// Initialize water level chart
document.addEventListener('DOMContentLoaded', function() {
    const chartCanvas = document.getElementById('waterLevelChart');
    
    <?php if ($selectedBridge): ?>
    // Prepare chart data from PHP water levels data
    const waterLevelData = <?php echo json_encode($waterLevels); ?>;
    
    if (waterLevelData && waterLevelData.length > 0) {
        // Sort data by date (oldest first for better visualization)
        waterLevelData.sort((a, b) => new Date(a.date) - new Date(b.date));
        
        // Extract times and heights for chart
        const labels = waterLevelData.map(data => {
            // Extract time from date string (format: "m/d/Y H:i" -> "H:i")
            const parts = data.date.split(' ');
            return parts[1]; // Get the time part
        });
        const heights = waterLevelData.map(data => data.height);
        
        // Get color based on status
        const getStatusColor = (status) => {
            switch(status) {
                case 'normal': return 'rgba(16, 185, 129, 0.7)';
                case 'warning': return 'rgba(255, 209, 71, 0.7)';
                case 'danger': return 'rgba(255, 128, 0, 0.7)';
                case 'critical': return 'rgba(244, 67, 54, 0.7)';
                default: return 'rgba(69, 125, 138, 0.7)';
            }
        };
        
        // Create line chart
        new Chart(chartCanvas, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Water Level (m)',
                    data: heights,
                    borderColor: '#457d8a',
                    backgroundColor: 'rgba(69, 125, 138, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 5,
                    pointBackgroundColor: '#457d8a',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointHoverRadius: 7,
                    pointHoverBackgroundColor: '#3d6b77'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            font: {
                                size: 14,
                                weight: '600'
                            },
                            color: '#333',
                            padding: 15,
                            usePointStyle: true
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleFont: {
                            size: 14,
                            weight: 'bold'
                        },
                        bodyFont: {
                            size: 13
                        },
                        callbacks: {
                            label: function(context) {
                                return 'Height: ' + context.parsed.y + ' m';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: false,
                        title: {
                            display: true,
                            text: 'Water Level (meters)'
                        },
                        ticks: {
                            font: {
                                size: 12
                            },
                            color: '#666',
                            callback: function(value) {
                                return value + ' m';
                            }
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        ticks: {
                            font: {
                                size: 12
                            },
                            color: '#666',
                            maxRotation: 45,
                            minRotation: 0
                        },
                        grid: {
                            display: true,
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    }
                }
            }
        });
    }
    <?php endif; ?>
});
</script>

</body>
</html>
