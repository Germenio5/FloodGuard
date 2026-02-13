<?php
include '../controllers/news-controller.php';
?>

<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Water Levels - Latest Happenings</title>
    <link rel="stylesheet" href="../assets/css/news.css">
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
        </div>
    </div>
</div>

<!-- EVENT GRID -->
<div class="grid-container">

<?php foreach($eventList as $event): ?>

<div class="event-card">

    <!-- HEADER -->
    <div class="card-header">

        <div class="profile">
            <div class="avatar">
            <img src="<?= htmlspecialchars($event['avatar']) ?>">
            </div>

            <div class="user-info">
                <strong><?= htmlspecialchars($event['name']) ?></strong>

                <span class="meta">
                    <?= htmlspecialchars($event['time']) ?> • 
                    <?= htmlspecialchars($event['area']) ?>
                </span>
            </div>

        </div>

        <div class="menu">•••</div>

    </div>

    <!-- IMAGE -->
    <div class="event-image">
        <img src="<?= htmlspecialchars($event['picture']) ?>" alt="event">
    </div>

    <!-- DESCRIPTION -->
    <div class="content">

        <h4><?= htmlspecialchars($event['description']) ?></h4>


        <span class="status <?= strtolower($event['status']) ?>">
            <?= htmlspecialchars($event['status']) ?>
        </span>

    </div>

</div>

<?php endforeach; ?>

</div>

</div>
</main>

<?php include 'include/footer.php'; ?>

</body>
</html>
