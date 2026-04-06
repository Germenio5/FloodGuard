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

    <!-- Filter Box -->
    <div class="filter-box">
        <form method="GET" class="filter-form">
            <label for="locationFilter">Filter by Barangay:</label>
            <select id="locationFilter" name="location" onchange="this.form.submit()">
                <option value="">All Barangays</option>
                <?php foreach ($barangays as $barangay): ?>
                    <option value="<?= htmlspecialchars($barangay) ?>" <?= $selectedLocation === $barangay ? 'selected' : '' ?>>
                        <?= 'Brgy. ' . htmlspecialchars($barangay) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="statusFilter">Status:</label>
            <select id="statusFilter" name="status" onchange="this.form.submit()">
                <?php foreach ($statusOptions as $key => $label): ?>
                    <option value="<?= htmlspecialchars($key) ?>" <?= ($selectedStatus === $key) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($label) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>
    </div>

    <!-- Bridge Cards Grid -->
    <div class="bridge-grid">

        <?php if (empty($waterStatusData)): ?>
            <div class="no-data-message">
                <p>No affected areas data available.</p>
            </div>
        <?php else: ?>

        <?php foreach($waterStatusData as $bridge): ?>

        <div class="bridge-card" data-area-id="<?= $bridge['id'] ?>">

            <h2 class="bridge-name">
                <?= $bridge['bridge_name'] ?>
            </h2>

            <p class="bridge-location">
                <?= $bridge['location'] ?>
            </p>

            <div class="level-info">
                <span class="level-label">Current Level:</span>

                <span class="level-value">
                    <span class="current-value"><?= $bridge['current_level'] ?></span>
                    /
                    <span class="max-value"><?= $bridge['max_level'] ?></span>
                </span>
            </div>

            <div class="progress-bar">
                <div class="progress-fill <?= getProgressClass($bridge['status']) ?>"
                     style="width: <?= $bridge['percentage'] ?>%"
                     data-percent="<?= $bridge['percentage'] ?>">
                </div>
            </div>

            <div class="progress-info-row">
                <div class="status-display">
                    <span class="status-badge-wrapper"><?= getStatusBadge($bridge['status']) ?></span>
                </div>
                <p class="progress-percentage">
                    <span class="percent-value"><?= $bridge['percentage'] ?></span>%
                </p>
            </div>

            <p class="speed-info">
                Speed: <span class="speed-value"><?= $bridge['speed'] ?></span>
            </p>

                    <div class="button-group">
                <a href="admin-map-edit.php?bridge=<?php echo urlencode($bridge['bridge_name']); ?>" class="btn btn-outline">
                    View Map
                </a>

                <button class="btn btn-primary edit-data-btn"
                        type="button"
                        data-area-id="<?= htmlspecialchars($bridge['id'], ENT_QUOTES) ?>"
                        data-bridge="<?= htmlspecialchars($bridge['bridge_name'], ENT_QUOTES) ?>"
                        data-location="<?= htmlspecialchars($bridge['location'], ENT_QUOTES) ?>"
                        data-current="<?= floatval($bridge['current_level']) ?>"
                        data-max="<?= floatval($bridge['max_level']) ?>"
                        data-speed="<?= htmlspecialchars($bridge['speed'], ENT_QUOTES) ?>"
                        data-status="<?= htmlspecialchars($bridge['status'], ENT_QUOTES) ?>"
                        data-percent="<?= htmlspecialchars($bridge['percentage'], ENT_QUOTES) ?>">
                    Edit Data
                </button>
            </div>

        </div>

        <?php endforeach; ?>

        <?php endif; ?>

    </div>

    <!-- Pagination -->
    <div class="pagination-info">
        <p>Showing page <strong><?php echo $currentPage; ?></strong> of <strong><?php echo $totalPages; ?></strong> | Total areas: <strong><?php echo $totalRecords; ?></strong></p>
    </div>

    <div class="pagination-section" style="margin: 20px 0; text-align: center;">
        <?php foreach ($paginationButtons as $btn): ?>
            <?php if ($btn['active']): ?>
                <span class="pagination-btn current"><?php echo $btn['label']; ?></span>
            <?php else: ?>
                <a href="?page=<?php echo $btn['page']; ?><?php echo $selectedLocation ? '&location=' . urlencode($selectedLocation) : ''; ?><?php echo $selectedStatus ? '&status=' . urlencode($selectedStatus) : ''; ?>" class="pagination-btn"><?php echo $btn['label']; ?></a>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>

    </div>
</main>

<!-- Edit Data Modal -->
<div id="editDataModal" class="modal">
    <div class="modal-content">
        <span class="modal-close" onclick="closeEditModal()">&times;</span>
        <div class="modal-header">Edit Water Level Data</div>
            <form id="editDataForm" method="POST" action="../controllers/update-affected-area.php">
                <input type="hidden" name="area_id" id="modalAreaId" />

                <div class="modal-body">
                    <div class="modal-row"><strong>Bridge:</strong> <span id="modalBridge"></span></div>
                    <div class="modal-row"><strong>Location:</strong> <span id="modalLocation"></span></div>
                    <div class="modal-row"><strong>Current Level:</strong> <input id="modalCurrent" name="current_level" type="number" step="0.01" min="0" required class="modal-input" /></div>
                    <div class="modal-row"><strong>Max Level:</strong> <span id="modalMax"></span></div>
                    <div class="modal-row"><strong>Speed:</strong> <span id="modalSpeed"></span></div>
                    <div class="modal-row"><strong>Status:</strong> <span id="modalStatus"></span></div>
                    <div class="modal-row"><strong>Percentage:</strong> <span id="modalPercent"></span></div>
                </div>
                <div class="modal-actions">
                    <button class="btn btn-secondary" type="button" onclick="closeEditModal()">Close</button>
                    <button class="btn btn-primary" type="submit">Save</button>
                </div>
            </form>
    </div>
</div>

<script src="../assets/js/admin-water-status.js"></script>

</body>
</html>
