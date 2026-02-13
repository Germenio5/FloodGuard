<?php session_start(); ?>
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
            
            <form method="post" action="../controllers/process-report.php">
                <?php if(isset($_GET['success'])): ?>
                    <p class="success-message">Report submitted successfully.</p>
                <?php elseif(isset($_GET['error'])): ?>
                    <p class="error-message">There was a problem submitting your report. Please try again.</p>
                <?php endif; ?>

                <div class="form-group">
                    <label>Location</label>
                    <input type="text" name="location" placeholder="Enter location of the flood" class="text-input" required />
                </div>

                <div class="form-group">
                    <label>Status</label>
                    <div class="badge-group">
                        <button type="button" class="badge" onclick="selectStatus(this)">Safe</button>
                        <button type="button" class="badge" onclick="selectStatus(this)">In Danger</button>
                    </div>
                    <input type="hidden" name="status" id="statusInput" value="" />
                </div>
                
                <div class="form-group">
                    <button type="button" class="upload-btn">Upload Picture</button>
                </div>

                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" placeholder="Provide additional details about the flood"></textarea>
                </div>

                <div class="form-group">
                    <label class="checkbox-container">
                        <input type="checkbox" name="post_discussion" value="1" />
                        <span>Post to discussion page (Optional)</span>
                    </label>
                </div>

                <button type="submit" class="submit-btn">Submit Report</button>
            </form>
            <script>
                // simple check before submission
                document.querySelector('form').addEventListener('submit', function(e) {
                    var statusVal = document.getElementById('statusInput').value.trim();
                    if (statusVal === '') {
                        alert('Please select a status (Safe or In Danger)');
                        e.preventDefault();
                    }
                });
            </script>
        </div>
    </div>
</main>

<script>
function selectStatus(button) {
    // Remove 'selected' class from all status badges
    const badges = button.parentElement.querySelectorAll('.badge');
    badges.forEach(badge => badge.classList.remove('selected'));
    
    // Add 'selected' class to clicked button
    button.classList.add('selected');
}

    // update hidden status field when selecting badge
    function selectStatus(button) {
        // Remove 'selected' class from all status badges
        const badges = button.parentElement.querySelectorAll('.badge');
        badges.forEach(badge => badge.classList.remove('selected'));
        
        // Add 'selected' class to clicked button
        button.classList.add('selected');

        // store value in hidden input
        document.getElementById('statusInput').value = button.textContent.trim();
    }
</script>

  </body>
</html>