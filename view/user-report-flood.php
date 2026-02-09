<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>FloodGuard Report</title>
    <link rel="stylesheet" href="../css/userreportflood.css">
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
            
            <form action="#">
                <div class="form-group">
                    <label>Location</label>
                    <input type="text" placeholder="Enter location of the flood" class="text-input" />
                </div>

                <div class="form-group">
                    <label>Proximity to Water</label>
                    <div class="badge-group">
                        <button type="button" class="badge">Very Close</button>
                        <button type="button" class="badge">Nearby</button>
                        <button type="button" class="badge">Far Away</button>
                    </div>
                </div>

                <div class="form-group">
                    <label>Status</label>
                    <div class="badge-group">
                        <button type="button" class="badge">Safe</button>
                        <button type="button" class="badge">In Danger</button>
                    </div>
                </div>
                
                <div class="form-group">
                    <button type="button" class="upload-btn">Upload Picture</button>
                </div>

                <div class="form-group">
                    <label>Description</label>
                    <textarea placeholder="Provide additional details about the flood"></textarea>
                </div>

                <button type="submit" class="submit-btn">Submit Report</button>
            </form>
        </div>
    </div>
</main>
  </body>
</html>