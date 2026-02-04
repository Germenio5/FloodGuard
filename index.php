<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FloodGuard - Home</title>
    <link rel="stylesheet" href="css/index.css">
</head>
<body>
    <?php include 'view/include/header.php'; ?>
    
    <main>
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-content">
            <h1>Are You in Danger?</h1>
            <p>Find out if you're in a flood danger zone and get immediate assistance.</p>
            <div class="hero-buttons">
                <a href="#" class="btn btn-learn">Learn More</a>
                <a href="#" class="btn btn-status">Check My Status</a>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="about-section">
        <h2>About FloodGuard</h2>
        <p>FloodGuard offers several features through its web application. These include an interactive flood map that shows affected areas, real-time updates on flood depth and water movement, and alerts to inform users of changing conditions. The platform also provides safety tips, emergency contact details, and travel guidance to help users avoid flooded routes.</p>
        <a href="#">Learn more about our website</a>
    </section>

    <!-- Cards Section -->
        <section class="cards-container">
            <div class="card">
                <h3>Flooding</h3>
                <p>Discover the latest flood conditions, area-specific alerts, and safety information to help you stay aware and prepared.</p>
                <a href="#">View Flood →</a>
            </div>
            <div class="card">
                <h3>Report</h3>
                <p>Report flood incidents in your area to help provide timely updates, improve response efforts, and keep the community informed and safe.</p>
                <a href="#">Report Now →</a>
            </div>
        </section>
        
    <section class="reports-section">
            <h2>Latest Flood Reports</h2>
            <p class="subtitle">Get details about recent flood levels.</p>
            <a href="#" class="btn">View Reports</a>

        <br><br><br><br>

            <div class="reports-grid">
                <?php
                $reports = [
                    [
                        'title' => 'Flood in La Salle Bridge',
                        'location' => 'La Salle Avenue',
                        'description' => 'Significant flooding in La Salle Avenue resulted in evacuations.',
                        'date' => '2023-10-01'
                    ],
                    [
                        'title' => 'Flood in Eroreco Bridge',
                        'location' => 'Eroreco Bridge',
                        'description' => 'Sudden Rise of Water in Eroreco Bridge. Residents be wary of possible flooding.',
                        'date' => '2023-12-12'
                    ]
                ];
                
                foreach($reports as $report) {
                    echo '<div class="report-card">';
                    echo '<div class="report-image"></div>';
                    echo '<div class="report-content">';
                    echo '<h3>' . $report['title'] . '</h3>';
                    echo '<p class="date">Reported on ' . $report['date'] . '</p>';
                    echo '<p>' . $report['description'] . '</p>';
                    echo '</div>';
                    echo '</div>';
                }
                ?>
            </div>
        </section>

    </main>
    <?php include 'view/include/footer.php'; ?>
</body>
</html>