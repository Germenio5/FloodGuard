<?php
include '../controllers/admin-report-log-controller.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Reports Made - FloodGuard</title>
    <link rel="stylesheet" href="../assets/css/adminreportlog.css">
</head>

<body>

<?php include 'include/admin-sidebar.php'; ?>

<main>
<div class="main-wrapper">
    
    <!-- Page Title -->
    <h1>Reports Made</h1>

    <!-- Reports Table -->
    <div class="table-container">
        <table>

            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Location</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>

            <?php if (empty($reports)): ?>
                <tr>
                    <td colspan="6" style="text-align: center;">No reports found</td>
                </tr>
            <?php else: ?>

                <?php foreach ($reports as $r): ?>

                <tr>
                    <td><?= htmlspecialchars($r["id"]) ?></td>

                    <td><?= htmlspecialchars($r["name"]) ?></td>

                    <td><?= htmlspecialchars($r["area"]) ?></td>

                    <td>
                        <span class="status-badge <?= getBadgeClass($r["status"]) ?>">
                            <?= htmlspecialchars($r["status"]) ?>
                        </span>
                    </td>

                    <td><?= htmlspecialchars(date('Y-m-d H:i', strtotime($r["last_updated"]))) ?></td>

                    <td>
                        <button class="view-details-btn" onclick='viewReport(<?= json_encode($r) ?>)'>
                            View Details
                        </button>
                    </td>
                </tr>

                <?php endforeach; ?>

            <?php endif; ?>

            </tbody>

        </table>
    </div>

    <!-- Upload News Button -->
    <div class="button-section">
        <button class="upload-btn">Upload News</button>
    </div>

</div>
</main>

<!-- Modal for viewing report details -->
<div id="reportModal" class="modal">
    <div class="modal-content">
        <span class="modal-close" onclick="closeModal()">&times;</span>
        <div class="modal-header">Report Details</div>
        <div id="modalBody"></div>
    </div>
</div>
<script src="../assets/js/admin-flood-report.js"></script>
</body>
</html>