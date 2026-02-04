<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Water Levels - Latest Happenings</title>
    <link rel="stylesheet" href="../css/news.css">
</head>
<body>
    <?php include 'include/header.php'; ?>
    <main>
    <div class="container">
        <div class="warning-box">
            <div class="box-left">
                <h1>WATER LEVELS</h1>
                <h2>Latest Happenings</h2>
            </div>
            <div class="box-right">
                <div class="warnings">
                    <div class="warning-item">
                        <div class="warning-icon danger">
                            ⚠
                        </div>
                        <span class="danger-text">0 Danger Warnings</span>
                    </div>
                    <div class="warning-item">
                        <div class="warning-icon alert">
                            ⚠
                        </div>
                        <span class="alert-text">0 Alert Warnings</span>
                    </div>
                </div>
                <div class="info-section">
                    <div class="last-updated">
                        Last updated<br>
                        January 14, 2025<br>
                        at 14:23
                    </div>
                    <a href="#" class="view-history">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <rect x="2" y="3" width="12" height="11" rx="1" stroke="currentColor" fill="none" stroke-width="1.5"/>
                            <line x1="2" y1="6" x2="14" y2="6" stroke="currentColor" stroke-width="1.5"/>
                            <line x1="5" y1="1" x2="5" y2="4" stroke="currentColor" stroke-width="1.5"/>
                            <line x1="11" y1="1" x2="11" y2="4" stroke="currentColor" stroke-width="1.5"/>
                        </svg>
                        <span>View History</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="grid-container">
            <?php
                $events = [
                    ['Rise in Banago', 'Sudden Rise of water on Banago...'],
                    ['Eroreco Bridge1', 'Eroreco Bridge full of garbage may pos...'],
                    ['Eroreco Bridge2', 'Eroreco Bridge full of garbage may pos...'],
                    ['Eroreco Bridge3', 'Eroreco Bridge full of garbage may pos...'],
                    ['Eroreco Bridge4', 'Eroreco Bridge full of garbage may pos...'],
                    ['Rise in Banago', 'Sudden Rise of water on Banago...'],
                    ['Eroreco Bridge5', 'Eroreco Bridge full of garbage may pos...'],
                    ['Eroreco Bridge6', 'Eroreco Bridge full of garbage may pos...'],
                    ['Eroreco Bridge7', 'Eroreco Bridge full of garbage may pos...'],
                    ['Eroreco Bridge8', 'Eroreco Bridge full of garbage may pos...'],
                    ['Eroreco Bridge9', 'Eroreco Bridge full of garbage may pos...'],
                    ['Eroreco Bridge11', 'Eroreco Bridge full of garbage may pos...']
                ];

                foreach($events as $event) {
                    echo '<div class="event-card">';
                    echo '<h3>' . htmlspecialchars($event[0]) . '</h3>';
                    echo '<div class="event-image"></div>';
                    echo '<p class="event-description">' . htmlspecialchars($event[1]) . '</p>';
                    echo '</div>';
                }
            ?>
        </div>

    </div>
    </main>
    <?php include 'include/footer.php'; ?>
</body>
</html>
