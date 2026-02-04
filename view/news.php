<?php
include '../controller/news-controller.php';
?>

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

<!-- WARNING BOX -->
<div class="warning-box">

    <div class="box-left">
        <h1>WATER LEVELS</h1>
        <h2>Latest Happenings</h2>
    </div>


    <div class="box-right">

        <div class="warnings">

            <div class="warning-item">
                <div class="warning-icon danger">⚠</div>
                <span class="danger-text">
                    <?= $dangerCount ?> Danger Warnings
                </span>
            </div>

            <div class="warning-item">
                <div class="warning-icon alert">⚠</div>
                <span class="alert-text">
                    <?= $alertCount ?> Alert Warnings
                </span>
            </div>

        </div>



        <div class="info-section">

            <div class="last-updated">
                Last updated<br>
                <?= $lastUpdated['date'] ?><br>
                at <?= $lastUpdated['time'] ?>
            </div>


            <a href="#" class="view-history">

                <svg width="16" height="16" viewBox="0 0 16 16"
                     fill="currentColor">

                    <rect x="2" y="3" width="12" height="11"
                          rx="1" stroke="currentColor"
                          fill="none" stroke-width="1.5"/>

                    <line x1="2" y1="6" x2="14" y2="6"
                          stroke="currentColor"
                          stroke-width="1.5"/>

                    <line x1="5" y1="1" x2="5" y2="4"
                          stroke="currentColor"
                          stroke-width="1.5"/>

                    <line x1="11" y1="1" x2="11" y2="4"
                          stroke="currentColor"
                          stroke-width="1.5"/>
                </svg>

                <span>View History</span>

            </a>

        </div>

    </div>
</div>



<!-- EVENT GRID -->
<div class="grid-container">

<?php foreach($eventList as $event): ?>

<div class="event-card">

    <h3>
        <?= htmlspecialchars($event['title']) ?>
    </h3>

    <div class="event-image"></div>

    <p class="event-description">
        <?= htmlspecialchars($event['description']) ?>
    </p>

</div>

<?php endforeach; ?>

</div>



</div>
</main>

<?php include 'include/footer.php'; ?>

</body>
</html>