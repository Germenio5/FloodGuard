<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FloodGuard - Flood Monitoring System</title>
    <link rel="stylesheet" href="../css/aboutus.css">
</head>

<body>
<?php include 'include/header.php'; ?>
<main>
    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <h1>About FloodGuard</h1>
            <p>FloodGuard offers several features through its web application. These include an interactive flood map that shows affected areas, real-time updates on flood depth and water movement, and alerts to inform users of changing conditions. The platform also provides safety tips, emergency contact details, and travel guidance to help users avoid flooded routes.</p>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features">
        <div class="container">
            <div class="features-grid">
                <?php
                $data = [
                    ['FLOOD MAPS', 'View all locations currently impacted by flooding, including flood levels on certain areas, road conditions, and safe routes for residents and commuters.','index.php','View Flood Map'],
                    ['REPORT FLOOD', 'Submit real-time flood reports from your area, including photos and location, to help keep the community informed and safe.','index.php','View Report Flood'],
                    ['WATER LEVEL DATA', 'Access up-to-date information on creek and river water levels to monitor risks and prepare for potential flooding.','index.php','View Flood Map']
                ];

                foreach($data as $row) {
                    echo '<div class="feature-card">';
                    echo '<h2>'  . $row[0] . '</h2>';
                    echo '<p>'   . $row[1] . '</p>';
                    echo '<a href="' . $row[2] . '">' . $row[3] . '</a>';                    
                    echo '</div>';
                }
                ?>
            </div>
        </div>
    </section>

    <!-- Mission Section -->
    <section class="mission">
        <div class="container">
            <h2>Our Mission</h2>
            <p class="mission-subtitle">Our mission is to empower communities with timely and accurate flood information.</p>
            
            <div class="mission-grid">
                <?php
                $data = [
                    ['img.png', 'Data-Driven Insights', 'We leverage advanced technology to deliver comprehensive data on flood risks.'],
                    ['img.png', 'Community Engagement', 'We collaborate with local agencies and communities to enhance awareness and preparedness.'],
                    ['img.png', 'Sustainability Focus', 'We prioritize sustainable practices in our operations and strive to minimize environmental impacts.']
                ];
                foreach($data as $row) {
                    echo '<div class="mission-card">';
                    echo ' <div class="mission-icon"><img src="' . $row[0] . '"/></div>';
                    echo '<div class="mission-content"><h3>' . $row[1] . '</h3>';
                    echo '<p>' . $row[2] . '</p></div>';
                    echo '<br>';
                    echo '</div>';
                }
                ?>
            </div>
        </div>
    </section>
</main>
<?php include 'include/footer.php'; ?>
</body>
</html>