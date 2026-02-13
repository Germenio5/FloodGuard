<?php
session_start();
require_once '../config/config.php';

$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $location = $conn->real_escape_string($_POST['location']);
    $status = $conn->real_escape_string($_POST['status']);
    $description = $conn->real_escape_string($_POST['description']);
    $post_to_discussion = isset($_POST['post_to_discussion']) ? 1 : 0;
    
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
        $sql = "INSERT INTO flood_reports (location, status, photo, description, post_to_discussion, created_at) 
                VALUES (?, ?, ?, ?, ?, NOW())";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $location, $status, $photo_path, $description, $post_to_discussion);
        
        if ($stmt->execute()) {
            $success_message = "Flood report submitted successfully!";
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
                <div class="form-group">
                    <label>Location *</label>
                    <input type="text" name="location" placeholder="Enter location of the flood" class="text-input" required />
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

                <div class="form-group">
                    <label>Post to Discussion Page <span class="optional-label">(Optional)</span></label>
                    <div class="checkbox-group">
                        <div class="checkbox-item">
                            <input type="checkbox" name="post_to_discussion" id="postToDiscussion" value="1">
                            <label for="postToDiscussion">Share this report on the discussion page</label>
                        </div>
                    </div>
                </div>

                <button type="submit" class="submit-btn">Submit Report</button>
            </form>
        </div>
    </div>
</main>

<script>
    // Status buttons
    document.querySelectorAll('.status-btn').forEach(button => {
        button.addEventListener('click', function() {
            document.querySelectorAll('.status-btn').forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            document.getElementById('statusInput').value = this.getAttribute('data-value');
        });
    });

    // Display selected file name
    function displayFileName(input) {
        const fileName = input.files[0] ? input.files[0].name : '';
        document.getElementById('fileName').textContent = fileName ? 'Selected: ' + fileName : '';
    }

    // Form validation
    document.getElementById('floodReportForm').addEventListener('submit', function(e) {
        if (!document.getElementById('statusInput').value) {
            e.preventDefault();
            alert('Please select Status');
            return false;
        }
    });
</script>
  </body>
</html>