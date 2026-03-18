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

    <!-- Filter Box (Barangay + Bridge) -->
    <div class="filter-box">
        <form method="GET" class="filter-form">
            <label for="barangayFilter">Filter by Barangay:</label>
            <select id="barangayFilter" name="barangay" onchange="this.form.submit()">
                <option value="">All Barangays</option>
                <?php foreach ($barangays as $barangay): ?>
                    <option value="<?= htmlspecialchars($barangay) ?>" <?= $selectedBarangay === $barangay ? 'selected' : '' ?>>
                        <?= 'Brgy. ' . htmlspecialchars($barangay) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="bridgeFilter" style="margin-left: 20px;">Filter by Bridge/Location:</label>
            <select id="bridgeFilter" name="bridge" onchange="this.form.submit()">
                <option value="">All Bridges</option>
                <?php foreach ($bridges as $bridgeOption): ?>
                    <option value="<?= htmlspecialchars($bridgeOption) ?>" <?= isset($selectedBridge) && $selectedBridge === $bridgeOption ? 'selected' : '' ?>>
                        <?= htmlspecialchars($bridgeOption) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>
    </div>

    <!-- Messages -->
    <?php if (!empty($message)): ?>
        <div class="message-box success" style="margin: 20px 0; padding: 15px 20px; border-radius: 5px; font-weight: 500; display: flex; align-items: center; background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb;">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
        <div class="message-box error" style="margin: 20px 0; padding: 15px 20px; border-radius: 5px; font-weight: 500; display: flex; align-items: center; background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb;">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <!-- Reports Table -->
    <div class="table-container">
        <table>

            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Location</th>
                    <th>Status</th>
                    <th>SMS Sent</th>
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

                    <td>
                        <?php if (!empty($r['sms_sent_at'])): ?>
                            <?= htmlspecialchars(date('Y-m-d H:i', strtotime($r['sms_sent_at']))) ?>
                        <?php else: ?>
                            <span style="color:#6b7280;">No</span>
                        <?php endif; ?>
                    </td>

                    <td><?= htmlspecialchars(date('Y-m-d H:i', strtotime($r["last_updated"]))) ?></td>

                    <td>
                        <div style="display: flex; gap: 8px; align-items: center; flex-wrap: nowrap;">
                            <button class="view-details-btn" onclick="viewReport(<?= (int)$r['id'] ?>)">
                                View Details
                            </button>
                            <form method="POST" action="../controllers/admin-delete-report.php" style="display: inline; margin: 0;" onsubmit="return confirm('Are you sure you want to delete this report? This action cannot be undone.')">
                                <input type="hidden" name="report_id" value="<?= (int)$r['id'] ?>">
                                <button type="submit" class="delete-btn">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>

                <?php endforeach; ?>

            <?php endif; ?>

            </tbody>

        </table>
            <!-- Pagination Controls -->
            <div class="pagination-section" style="margin: 20px 0; text-align: center;">
                <?php if (!empty($pagination_buttons)): ?>
                    <?php foreach ($pagination_buttons as $btn): ?>
                        <?= $btn ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
    </div>

    <!-- Upload News Button -->
    <div class="button-section">
        <button class="upload-btn" onclick="openNewsModal()">Upload News</button>
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

<!-- Modal for uploading news -->
<div id="newsModal" class="modal">
    <div class="modal-content news-modal-content">
        <span class="modal-close" onclick="closeNewsModal()">&times;</span>
        
        <div class="news-modal-header">
            <div class="news-modal-icon">⚠️</div>
            <div class="news-modal-title-section">
                <h2>Create Flood Alert</h2>
                <p>Post a new alert to notify users about flooding situation</p>
            </div>
        </div>

        <form id="newsForm" enctype="multipart/form-data" class="news-form">
            
            <!-- Bridge/Location Selection -->
            <div class="form-group">
                <label for="bridgeSelect">
                    <span class="required">*</span> Affected Location
                </label>
                <div class="select-wrapper">
                    <select id="bridgeSelect" name="area" required>
                        <option value="">Select a Bridge or Area</option>
                        <?php foreach ($bridges_for_news as $bridge): ?>
                            <?php $displayName = preg_replace('/^Brgy\.\s*/i', '', $bridge); ?>
                            <option value="<?= htmlspecialchars($bridge) ?>">
                                📍 <?= htmlspecialchars($displayName) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="select-arrow">▼</div>
                </div>
            </div>

            <!-- Description -->
            <div class="form-group">
                <label for="newsDescription">
                    <span class="required">*</span> Description
                </label>
                <textarea id="newsDescription" name="description" 
                    placeholder="Describe the current flood situation, water level, evacuation info..." 
                    required rows="5" maxlength="500"></textarea>
                <div class="char-count">
                    <span id="charCount">0</span>/500
                </div>
            </div>

            <!-- Photo Upload -->
            <div class="form-group">
                <label for="newsPhoto">
                    <span class="required">*</span> Upload Photo
                </label>
                <div class="file-upload-wrapper">
                    <input type="file" id="newsPhoto" name="picture" accept="image/jpeg,image/png" required>
                    <div class="file-upload-area" id="fileUploadArea">
                        <div class="upload-icon">📸</div>
                        <div class="upload-text">
                            <p class="upload-main">Drag and drop your image here</p>
                            <p class="upload-sub">or click to select a file</p>
                        </div>
                        <div class="upload-hint">Supported: JPG, PNG (Max 15MB)</div>
                    </div>
                    <div id="filePreview" class="file-preview"></div>
                </div>
            </div>

            <!-- Hidden Fields -->
            <input type="hidden" id="newsName" name="name" value="FloodGuard">
            <input type="hidden" id="newsLogo" name="avatar" value="../assets/images/FloodGuard_logo.png">
            <input type="hidden" id="newsStatus" name="status" value="Alert">

            <!-- Form Actions -->
            <div class="modal-actions">
                <button type="button" class="btn-secondary" onclick="closeNewsModal()">
                    ✕ Cancel
                </button>
                <button type="submit" class="btn-primary">
                    ✓ Post Alert
                </button>
            </div>
        </form>
    </div>
</div>
<script src="../assets/js/admin-flood-report.js"></script>
<script src="../assets/js/admin-upload-news.js"></script>
</body>
</html>