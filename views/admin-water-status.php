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

<script>
function openEditModal(button) {
    const areaId = button.dataset.areaId;
    const bridge = button.dataset.bridge;
    const location = button.dataset.location;
    const current = parseFloat(button.dataset.current) || 0;
    const max = parseFloat(button.dataset.max) || 0;
    const speed = button.dataset.speed;
    const status = button.dataset.status;
    const percent = parseFloat(button.dataset.percent) || 0;

    document.getElementById('modalAreaId').value = areaId;
    document.getElementById('modalBridge').textContent = bridge;
    document.getElementById('modalLocation').textContent = location;
    document.getElementById('modalCurrent').value = current;
    document.getElementById('modalMax').textContent = max + 'm';
    document.getElementById('modalSpeed').textContent = speed;
    document.getElementById('modalStatus').textContent = status;
    document.getElementById('modalPercent').textContent = percent.toFixed(1) + '%';

    document.getElementById('editDataModal').style.display = 'block';
}

function closeEditModal() {
    document.getElementById('editDataModal').style.display = 'none';
}

// Attach listeners after DOM loads
window.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.edit-data-btn').forEach(btn => {
        btn.addEventListener('click', () => openEditModal(btn));
    });

    const form = document.getElementById('editDataForm');
    if (form) {
        form.addEventListener('submit', async (event) => {
            event.preventDefault();

            const formData = new FormData(form);

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const data = await response.json();
                if (data.success) {
                    // Update card DOM
                    const card = document.querySelector(`.bridge-card[data-area-id="${data.area_id}"]`);
                    if (card) {
                        const currentEl = card.querySelector('.current-value');
                        const maxEl = card.querySelector('.max-value');
                        const percentEl = card.querySelector('.percent-value');
                        const speedEl = card.querySelector('.speed-value');
                        const progressFill = card.querySelector('.progress-fill');
                        const statusWrapper = card.querySelector('.status-badge-wrapper');

                        if (currentEl) currentEl.textContent = data.current_level;
                        if (maxEl) maxEl.textContent = data.max_level;
                        if (percentEl) percentEl.textContent = data.percentage;
                        if (progressFill) {
                            progressFill.style.width = data.percentage + '%';
                            progressFill.className = 'progress-fill progress-' + data.status;
                        }
                        if (statusWrapper) {
                            statusWrapper.innerHTML = getStatusBadgeHtml(data.status);
                        }

                        // Keep the edit button dataset in sync (so reopening modal shows updated values)
                        const editBtn = card.querySelector('.edit-data-btn');
                        if (editBtn) {
                            editBtn.dataset.current = parseFloat(data.current_level) || 0;
                            editBtn.dataset.max = parseFloat(data.max_level) || 0;
                            editBtn.dataset.percent = parseFloat(data.percentage) || 0;
                            editBtn.dataset.status = data.status;
                        }

                        // Update modal values too
                        document.getElementById('modalCurrent').value = parseFloat(data.current_level) || 0;
                        document.getElementById('modalMax').textContent = data.max_level;
                        document.getElementById('modalStatus').textContent = data.status;
                        document.getElementById('modalPercent').textContent = data.percentage + '%';
                    }

                    closeEditModal();
                    alert('Water level data updated successfully.');
                } else {
                    alert('Update failed. Please try again.');
                }
            } catch (err) {
                console.error(err);
                alert('Something went wrong while updating.');
            }
        });
    }

    // Close modal when clicking outside of it
    window.addEventListener('click', (event) => {
        const modal = document.getElementById('editDataModal');
        if (event.target === modal) {
            closeEditModal();
        }
    });
});

function getStatusBadgeHtml(status) {
    switch (status.toLowerCase()) {
        case 'normal':
            return '<span class="status-badge status-normal">● Normal</span>';
        case 'warning':
            return '<span class="status-badge status-warning">● Warning</span>';
        case 'danger':
            return '<span class="status-badge status-danger">● Danger</span>';
        case 'critical':
            return '<span class="status-badge status-critical">● Critical</span>';
        default:
            return '<span class="status-badge">Unknown</span>';
    }
}
</script>

</body>
</html>
