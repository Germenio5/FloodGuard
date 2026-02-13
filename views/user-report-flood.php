<?php
session_start();
require_once '../config/config.php';

$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $location = $conn->real_escape_string($_POST['location']);
    $proximity = $conn->real_escape_string($_POST['proximity']);
    $status = $conn->real_escape_string($_POST['status']);
    $description = $conn->real_escape_string($_POST['description']);
    $submission_type = $conn->real_escape_string($_POST['submission_type']); // 'admin' or 'discussion'
    
    $photo_path = null;
    
    // Handle file upload
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['photo']['name'];
        $filetype = pathinfo($filename, PATHINFO_EXTENSION);
        
        if (in_array(strtolower($filetype), $allowed)) {
            $new_filename = uniqid() . '.' . $filetype;
            $upload_path = '../uploads/' . $new_filename;
            
            // Create uploads directory if it doesn't exist
            if (!file_exists('../uploads/')) {
                mkdir('../uploads/', 0777, true);
            }
            
            if (move_uploaded_file($_FILES['photo']['tmp_name'], $upload_path)) {
                $photo_path = $new_filename;
            } else {
                $error_message = "Failed to upload image.";
            }
        } else {
            $error_message = "Invalid file type. Only JPG, JPEG, PNG & GIF allowed.";
        }
    }
    
    // Insert into database
    if (empty($error_message)) {
        $sql = "INSERT INTO flood_reports (location, proximity, status, photo, description, submission_type, created_at) 
                VALUES (?, ?, ?, ?, ?, ?, NOW())";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssss", $location, $proximity, $status, $photo_path, $description, $submission_type);
        
        if ($stmt->execute()) {
            if ($submission_type == 'discussion') {
                $success_message = "Flood report posted to discussion page successfully!";
            } else {
                $success_message = "Flood report submitted to admin successfully!";
            }
        } else {
            $error_message = "Error: " . $stmt->error;
        }
        $stmt->close();
    }
}
?>
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
            <h1>Incident Details</h1>
            <p class="subtitle">Please provide as much as possible about the flood incident</p>
            
            <?php if ($success_message): ?>
                <div class="alert alert-success"><?php echo $success_message; ?></div>
            <?php endif; ?>
            
            <?php if ($error_message): ?>
                <div class="alert alert-error"><?php echo $error_message; ?></div>
            <?php endif; ?>
            
            <form action="" method="POST" enctype="multipart/form-data" id="floodReportForm">
                <input type="hidden" name="submission_type" id="submissionType">
                
                <div class="form-group">
                    <label>Location *</label>
                    <input type="text" name="location" placeholder="Enter location of the flood" class="text-input" required />
                </div>

                <div class="form-group">
                    <label>Proximity to Water *</label>
                    <input type="hidden" name="proximity" id="proximityInput" required>
                    <div class="badge-group">
                        <button type="button" class="badge proximity-btn" data-value="Very Close">Very Close</button>
                        <button type="button" class="badge proximity-btn" data-value="Nearby">Nearby</button>
                        <button type="button" class="badge proximity-btn" data-value="Far Away">Far Away</button>
                    </div>
                </div>

                <div class="form-group">
                    <label>Status *</label>
                    <input type="hidden" name="status" id="statusInput" required>
                    <div class="badge-group">
                        <button type="button" class="badge status-btn" data-value="Safe">Safe</button>
                        <button type="button" class="badge status-btn" data-value="In Danger">In Danger</button>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="file-input-wrapper">
                        <button type="button" class="upload-btn" onclick="document.getElementById('photoInput').click()">Upload Picture</button>
                        <input type="file" name="photo" id="photoInput" accept="image/*" onchange="displayFileName(this)">
                    </div>
                    <div class="file-name" id="fileName"></div>
                </div>

                <div class="form-group">
                    <label>Description *</label>
                    <textarea name="description" placeholder="Provide additional details about the flood" required></textarea>
                </div>

                <div class="submit-buttons">
                    <button type="button" class="submit-btn submit-btn-admin" onclick="submitReport('admin')">Submit to Admin Only</button>
                    <button type="button" class="submit-btn submit-btn-discussion" onclick="submitReport('discussion')">Submit and Post to Discussion</button>
                </div>
            </form>
        </div>
    </div>
</main>
    <script src="../assets/js/flood-report.js"></script>
  </body>
</html>