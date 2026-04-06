<?php
include '../controllers/user-report-history-controller.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>FloodGuard - Report History</title>

    <link rel="stylesheet" href="../assets/css/userreporthistory.css">
</head>

<body>

<?php include 'include/user-sidebar.php'; ?>

<main>

    <!-- Page Header -->
    <div class="page-header">
        <h1><?= htmlspecialchars($pageTitle) ?></h1>
        <p><?= htmlspecialchars($pageSubtitle) ?></p>
    </div>

    <div class="data-section">
        
        <!-- ===== ERROR/SUCCESS MESSAGES ===== -->
        <?php if (!empty($errorMessage)): ?>
            <div class="alert alert-error" role="alert">
                <span class="alert-icon">⚠️</span>
                <span><?= htmlspecialchars($errorMessage) ?></span>
                <button class="alert-close" onclick="this.parentElement.style.display='none';">×</button>
            </div>
        <?php endif; ?>

        <?php if (!empty($successMessage)): ?>
            <div class="alert alert-success" role="alert">
                <span class="alert-icon">✓</span>
                <span><?= htmlspecialchars($successMessage) ?></span>
                <button class="alert-close" onclick="this.parentElement.style.display='none';">×</button>
            </div>
        <?php endif; ?>

        <!-- ===== SECTION HEADER ===== -->
        <div class="section-header">
            <h2>Your Submitted Reports</h2>
        </div>

        <!-- ===== REPORTS TABLE ===== -->
        <div class="table-container">
            <?php if (count($user_reports) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Report ID</th>
                            <th>Location</th>
                            <th>Status</th>
                            <th>Submitted Date</th>
                            <th>Admin Response</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($user_reports as $report): ?>
                            <tr data-report-id="<?= $report['id'] ?>">
                                <td class="report-id">#<?= htmlspecialchars($report['id']) ?></td>
                                <td class="location">
                                    <span class="location-text"><?= htmlspecialchars($report['location']) ?></span>
                                </td>
                                <td class="status">
                                    <span class="status-badge <?= getStatusBadgeClass($report['status']) ?>">
                                        <?= htmlspecialchars($report['status']) ?>
                                    </span>
                                </td>
                                <td class="date">
                                    <?= formatDate($report['created_at']) ?>
                                </td>
                                <td class="response">
                                    <span class="response-badge <?= getResponseBadgeClass($report['is_responded']) ?>">
                                        <?= getResponseStatusText($report['is_responded']) ?>
                                    </span>
                                </td>
                                <td class="actions">
                                    <button class="btn-action btn-details" 
                                            onclick="showReportDetails(<?= $report['id'] ?>)">
                                        Details
                                    </button>
                                    <a href="../controllers/download-report-pdf.php?id=<?= $report['id'] ?>" 
                                       class="btn-action btn-pdf">
                                        Download PDF
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="empty-state">
                    <p>You haven't submitted any flood reports yet.</p>
                    <a href="user-report-flood.php" class="btn-primary">Submit a Report</a>
                </div>
            <?php endif; ?>
        </div>

        <!-- ===== PAGINATION INFO ===== -->
        <?php if ($total_pages > 1): ?>
            <div class="pagination-info">
                <p>Showing page <strong><?php echo $page; ?></strong> of <strong><?php echo $total_pages; ?></strong> | Total reports: <strong><?php echo $total_reports; ?></strong></p>
            </div>

            <!-- ===== PAGINATION ===== -->
            <div class="pagination">
                <?php if ($page > 1): ?>
                    <a href="?page=1" class="page-btn">« First</a>
                    <a href="?page=<?= $page - 1 ?>" class="page-btn">‹ Previous</a>
                <?php else: ?>
                    <span class="page-btn disabled">« First</span>
                    <span class="page-btn disabled">‹ Previous</span>
                <?php endif; ?>

                <?php
                    $start_page = max(1, $page - 2);
                    $end_page = min($total_pages, $page + 2);
                    for ($i = $start_page; $i <= $end_page; $i++):
                ?>
                    <?php if ($i == $page): ?>
                        <span class="page-btn active"><?= $i ?></span>
                    <?php else: ?>
                        <a href="?page=<?= $i ?>" class="page-btn"><?= $i ?></a>
                    <?php endif; ?>
                <?php endfor; ?>

                <?php if ($page < $total_pages): ?>
                    <a href="?page=<?= $page + 1 ?>" class="page-btn">Next ›</a>
                    <a href="?page=<?= $total_pages ?>" class="page-btn">Last »</a>
                <?php else: ?>
                    <span class="page-btn disabled">Next ›</span>
                    <span class="page-btn disabled">Last »</span>
                <?php endif; ?>
            </div>
        <?php endif; ?>

    </div>

</main>

<!-- ===== REPORT DETAILS MODAL ===== -->
<div id="reportDetailsModal" class="modal">
    <div class="modal-content modal-large">
        <div class="modal-header">
            <h2>Report Details</h2>
            <button class="modal-close" onclick="closeReportDetails()">×</button>
        </div>
        <div class="modal-body" id="reportDetailsBody">
            <div class="loading">
                <div class="spinner"></div>
                <p>Loading details...</p>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-secondary" onclick="closeReportDetails()">Close</button>
            <a id="downloadPdfBtn" href="#" class="btn-primary">Download PDF</a>
        </div>
    </div>
</div>

<!-- ===== SCRIPTS ===== -->
<script src="../assets/js/user-report-history.js"></script>

</body>
</html>
