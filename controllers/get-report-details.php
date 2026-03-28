<?php
/**
 * Get Report Details (via AJAX)
 * Returns HTML content for a specific report to be displayed in modal
 */

session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    http_response_code(401);
    echo '<div class="alert alert-error">Unauthorized access.</div>';
    exit();
}

// Check if report ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    http_response_code(400);
    echo '<div class="alert alert-error">Report ID is required.</div>';
    exit();
}

// Load database and models
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/reports.php';

$report_id = intval($_GET['id']);
$user_email = $_SESSION['user_email'] ?? '';

// Get report details
$report = get_report_by_id($conn, $report_id);

// Check if report exists
if (!$report) {
    http_response_code(404);
    echo '<div class="alert alert-error">Report not found.</div>';
    exit();
}

// Check if user has permission to view this report (must be the owner)
if (strtolower($report['user_email']) !== strtolower($user_email)) {
    http_response_code(403);
    echo '<div class="alert alert-error">You do not have permission to view this report.</div>';
    exit();
}

// Function to format date
function formatDate($date) {
    return date('M d, Y h:i A', strtotime($date));
}

// Function to get status badge class
function getStatusBadgeClass($status) {
    switch (strtolower($status)) {
        case 'danger':
            return 'status-danger';
        case 'alert':
            return 'status-alert';
        case 'safe':
            return 'status-safe';
        default:
            return 'status-default';
    }
}

?>

<!-- Detail Rows - Admin Design Pattern -->
<div class="detail-row">
    <div class="detail-label">Location:</div>
    <div class="detail-value"><?= htmlspecialchars($report['location']) ?></div>
</div>

<div class="detail-row">
    <div class="detail-label">Status:</div>
    <div class="detail-value">
        <span class="status-badge <?= getStatusBadgeClass($report['status']) ?>">
            <?= htmlspecialchars($report['status']) ?>
        </span>
    </div>
</div>

<div class="detail-row">
    <div class="detail-label">Description:</div>
    <div class="detail-value"><?= !empty($report['description']) ? htmlspecialchars($report['description']) : '<em>No description provided</em>' ?></div>
</div>

<div class="detail-row">
    <div class="detail-label">Date:</div>
    <div class="detail-value"><?= formatDate($report['created_at']) ?></div>
</div>

<div class="detail-row">
    <div class="detail-label">Contact Phone:</div>
    <div class="detail-value"><?= !empty($report['phone']) ? htmlspecialchars($report['phone']) : 'Not provided' ?></div>
</div>

<!-- Submitted By -->
<div class="detail-row">
    <div class="detail-label">Submitted By:</div>
    <div class="detail-value"><?= htmlspecialchars($report['first_name'] ?? '') ?> <?= htmlspecialchars($report['last_name'] ?? '') ?></div>
</div>

<!-- Admin Response Section -->
<?php if (!empty($report['admin_response']) || $report['is_responded']): ?>
    <div class="detail-row">
        <div class="detail-label">Admin Response:</div>
        <div class="detail-value">
            <?php if (!empty($report['admin_response'])): ?>
                <div class="response-box">
                    <p><strong>✓ Response received on <?= formatDate($report['admin_response_date']) ?></strong></p>
                    <p><?= htmlspecialchars($report['admin_response']) ?></p>
                </div>
            <?php else: ?>
                <div class="no-response">⏳ Awaiting admin response</div>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>

<!-- Report Image -->
<?php if (!empty($report['image'])): ?>
    <div class="image-row">
        <img src="data:image/jpeg;base64,<?= base64_encode($report['image']) ?>" 
             alt="Report Image" 
             class="report-image">
    </div>
<?php endif; ?>

<!-- Post to News Info -->
<?php if ($report['post_news'] == 1): ?>
    <div class="detail-row news-status-row">
        <div class="detail-label">News Status:</div>
        <div class="detail-value">📰 This report was posted to the News section</div>
    </div>
<?php endif; ?>

</div>

<style>
    .report-details {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .details-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        padding-bottom: 15px;
        border-bottom: 1px solid #e0e0e0;
    }

    .header-left h3 {
        margin: 0 0 5px 0;
        color: #333;
    }

    .submitted-by {
        margin: 0;
        font-size: 0.95rem;
        color: #666;
    }

    .details-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
    }

    .detail-item label {
        display: block;
        font-weight: 600;
        color: #555;
        margin-bottom: 5px;
        font-size: 0.9rem;
    }

    .detail-item p {
        margin: 0;
        color: #333;
        padding: 8px 0;
    }

    .detail-section {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .detail-section label {
        font-weight: 600;
        color: #555;
        font-size: 0.9rem;
    }

    .description-box {
        background: #f5f5f5;
        padding: 15px;
        border-radius: 6px;
        border-left: 3px solid #007bff;
        line-height: 1.6;
        color: #333;
    }

    .image-container {
        text-align: center;
    }

    .report-image {
        max-width: 100%;
        max-height: 400px;
        border-radius: 6px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .admin-response-section {
        background: #f9f9f9;
        padding: 15px;
        border-radius: 6px;
        border: 1px solid #e8e8e8;
    }

    .response-box {
        background: white;
        border-left: 4px solid #28a745;
        padding: 15px;
        border-radius: 4px;
    }

    .response-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
        padding-bottom: 10px;
        border-bottom: 1px solid #f0f0f0;
    }

    .response-status {
        color: #28a745;
        font-weight: 600;
        font-size: 0.9rem;
    }

    .response-date {
        color: #999;
        font-size: 0.85rem;
    }

    .response-content {
        color: #333;
        line-height: 1.6;
    }

    .no-response {
        background: #fff3cd;
        border-left: 4px solid #ffc107;
        padding: 15px;
        border-radius: 4px;
    }

    .no-response p {
        margin: 0 0 8px 0;
        color: #856404;
    }

    .small-text {
        font-size: 0.9rem;
        color: #999;
    }

    .info-box {
        padding: 12px 15px;
        border-radius: 6px;
        text-align: center;
        font-size: 0.95rem;
    }

    .info-posted {
        background: #e3f2fd;
        color: #1976d2;
        border: 1px solid #90caf9;
    }

    .status-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.85rem;
    }

    .status-danger {
        background: #f8d7da;
        color: #721c24;
    }

    .status-alert {
        background: #fff3cd;
        color: #856404;
    }

    .status-safe {
        background: #d4edda;
        color: #155724;
    }

    .loading {
        text-align: center;
        padding: 40px 20px;
    }

    .spinner {
        border: 4px solid #f3f3f3;
        border-top: 4px solid #007bff;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        animation: spin 1s linear infinite;
        margin: 0 auto 15px;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>
