<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>FloodGuard Dashboard</title>
    <link rel="stylesheet" href="../css/userdashboard.css">
</head>
<body>
<main>
    <div class="main-wrapper">
        
        <!-- Top Header Section -->
        <div class="top-section">
            <h1>Hello User!</h1>
            <div class="status-badge">
                <div>
                    <strong>You Are Safe</strong>
                    <a href="#">Edit Status</a>
                </div>
            </div>
        </div>

        <!-- Water Level Card -->
        <div class="water-card">
            <div class="card-header">
                <h2>Water Level Data</h2>
                <p>Updated Apr 26, 2025</p>
            </div>

            <div class="location-section">
                <h3>Eroreco Bridge</h3>
                <p>Brgy. Mondagñon</p>
                <p class="label">Current Level:</p>
                
                <div class="progress-bar">
                    <div class="progress-fill" style="width: 52.8%"></div>
                </div>
                <p class="level-text">7.5m / 14.2m</p>
            </div>

            <div class="info-grid">
                <div class="info-item">
                    <p>Trend</p>
                    <h4 class="orange">Rising</h4>
                </div>
                <div class="info-item">
                    <p>Speed</p>
                    <h4>0.9 meters / hour</h4>
                </div>
                <div class="info-item">
                    <p>Last Update</p>
                    <h4>6 minutes ago</h4>
                </div>
            </div>

            <div class="alert-box">
                <div class="alert-header">
                    <span>Status</span>
                    <strong>Alert</strong>
                </div>
                <ul>
                    <li>Stay alert and monitor updates regularly</li>
                    <li>Prepare for possible evacuation if levels rise further</li>
                    <li>Keep emergency supplies ready</li>
                </ul>
                <button class="report-btn">Report Now</button>
            </div>
        </div>

        <!-- Graph Placeholder -->
        <div class="graph-box">
            <p>GRAPH PLACEHOLDER</p>
        </div>

        <!-- Action Cards -->
        <div class="action-row">
            <div class="action-box">
                <h3>Flooding</h3>
                <p>Discover the latest flood conditions, area-specific alerts, and safety information to help you stay aware and prepared.</p>
                <a href="#">View Flood →</a>
            </div>
            <div class="action-box">
                <h3>Report</h3>
                <p>Report flood incidents in your area to help provide timely updates, improve response efforts, and keep the community informed and safe.</p>
                <a href="#">Report Now →</a>
            </div>
        </div>

        <!-- Emergency Hotline -->
        <div class="hotline-box">
            <span class="big-icon">⚠️</span>
            <div>
                <h3>Emergency Hotline</h3>
                <p>You can also reach the Disaster Risk Reduction & Management Office (DRRMO) for help with emergencies and rescue services.</p>
                <a href="tel:034-432-3871" class="phone">(034) 432-3871 to 73</a>
                <p class="small">(24/7 Roxas Emergency Dispatch)</p>
            </div>
        </div>

    </div>
</main>
</body>
</html>