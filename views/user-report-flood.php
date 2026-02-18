<?php include '../controllers/flood-report-controller.php'; ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>FloodGuard Report</title>
    <link rel="stylesheet" href="../assets/css/userreportflood.css">
  </head>
  <body>
<?php include 'include/user-sidebar.php'; ?>
  <main>

    <div class="main-wrapper">
        
        <div class="content-left">
            <h1>Flood Report</h1>
            <p>Fill in the incident details to help emergency responders assess the situation and coordinate an effective response. Your accurate reporting helps save lives.</p>
        </div>
        
        <div class="content-right">
            <?php if (!$showForm && $successMessage): ?>
                <!-- SUCCESS LAYOUT -->
                <div class="success-container">
                    <div class="success-icon">✓</div>
                    <h2><?php echo htmlspecialchars($successMessage, ENT_QUOTES); ?></h2>
                    <p>Your report has been recorded and will help emergency responders coordinate a faster response.</p>
                    <div class="success-button-group">
                        <a href="user-affected-areas.php" class="success-btn success-btn-primary">View Affected Areas</a>
                        <a href="user-dashboard.php" class="success-btn success-btn-secondary">Return to Dashboard</a>
                    </div>
                </div>
            <?php else: ?>
                <!-- FORM LAYOUT -->
                <h1>Incident Details</h1>
                <p class="subtitle">Please provide as much as possible about the flood incident</p>
                
                <form method="post" action="../controllers/process-report.php" enctype="multipart/form-data">
                    <?php if ($errorMessage): ?>
                        <div class="error-message">
                            <strong>⚠ Error</strong>
                            <p><?php echo htmlspecialchars($errorMessage, ENT_QUOTES); ?></p>
                        </div>
                    <?php endif; ?>

                    <div class="form-group">
                        <label>Location <span style="color: red;">*</span></label>
                        <input type="text" name="location" placeholder="Enter location of the flood (e.g., Brgy. Mandalagan, Bacolod City)" class="text-input" value="<?php echo htmlspecialchars($formData['location'], ENT_QUOTES); ?>" required />
                    </div>

                    <div class="form-group">
                        <label>Status <span style="color: red;">*</span></label>
                        <div class="badge-group">
                            <button type="button" class="badge <?php if ($formData['status'] === 'Safe') echo 'selected'; ?>" onclick="selectStatus(this)">Safe</button>
                            <button type="button" class="badge <?php if ($formData['status'] === 'In Danger') echo 'selected'; ?>" onclick="selectStatus(this)">In Danger</button>
                            <button type="button" class="badge <?php if ($formData['status'] === 'Danger') echo 'selected'; ?>" onclick="selectStatus(this)">Danger</button>
                        </div>
                        <input type="hidden" name="status" id="statusInput" value="<?php echo htmlspecialchars($formData['status'], ENT_QUOTES); ?>" />
                    </div>
                    
                    <div class="form-group">
                        <label>Photo (Optional)</label>
                        <div class="file-input-wrapper">
                            <input type="file" id="photoInput" name="photo" accept="image/jpeg,image/png,image/gif" onchange="previewPhoto(this)">
                            <button type="button" class="upload-btn" onclick="document.getElementById('photoInput').click()">
                                📷 Click to upload or drag & drop photo
                            </button>
                        </div>
                        <div class="photo-preview" id="photoPreview">
                            <img id="previewImage" src="" alt="Preview">
                            <div class="photo-preview-name" id="fileName"></div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Description <span style="color: red;">*</span></label>
                        <textarea name="description" placeholder="Provide additional details about the flood (water level, area affected, injuries, etc.)" required><?php echo htmlspecialchars($formData['description'], ENT_QUOTES); ?></textarea>
                    </div>

                    <div class="form-group">
                        <label class="checkbox-container">
                            <input type="checkbox" name="post_discussion" value="1" <?php if ($formData['post_discussion']) echo 'checked'; ?> />
                            <span>Post this report to the community discussion page (Optional)</span>
                        </label>
                    </div>

                    <button type="submit" class="submit-btn">Submit Report</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</main>

  <script src="../assets/js/flood-report.js"></script>
  </body>
</html>