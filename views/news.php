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

<body data-allow-anonymous="<?= isset($allowAnonymousReportDetails) && $allowAnonymousReportDetails ? '1' : '0' ?>">

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
                    <?php
                        $areaText = $event['area'];
                        // if the alert was posted by FloodGuard (admin) treat location as bridge/area
                        if (isset($event['name']) && $event['name'] === 'FloodGuard') {
                            $areaText = preg_replace('/^Brgy\.\s*/i', '', $areaText);
                        }
                    ?>
                    <?= htmlspecialchars($areaText) ?>
                </span>
            </div>

        </div>

        <div class="menu" data-report-id="<?= isset($event['id']) ? $event['id'] : '' ?>">•••</div>

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

<!-- Pagination Info -->
<div class="pagination-info">
    <p>Showing page <strong><?php echo $currentPage; ?></strong> of <strong><?php echo $totalPages; ?></strong> | Total news: <strong><?php echo $totalItems; ?></strong></p>
</div>

<!-- Pagination Footer (same pattern as water-level-data) -->
<div class="table-footer">
    <div class="pagination">
        <?php foreach ($paginationButtons as $btn): ?>
            <?php
                $isPrev = $btn['label'] === 'Previous';
                $isNext = $btn['label'] === 'Next';
                $extraClass = ($isPrev || $isNext) ? '' : ' page-num';
            ?>
            <?php if ($btn['disabled']): ?>
                <button class="page-btn disabled<?php echo $extraClass; ?>" disabled><?php echo $btn['label']; ?></button>
            <?php elseif ($btn['active']): ?>
                <button class="page-btn active<?php echo $extraClass; ?>"><?php echo $btn['label']; ?></button>
            <?php else: ?>
                <a href="?page=<?php echo $btn['page']; ?>" class="page-btn<?php echo $extraClass; ?>"><?php echo $btn['label']; ?></a>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</div>

<!-- Report Detail Modal -->
<div id="reportModal" class="modal">
    <div class="modal-content">
        <span class="modal-close" onclick="closeModal()">&times;</span>
        <div class="modal-header">Report Details</div>
        <div id="modalBody"></div>
    </div>
</div>

<script src="../assets/js/news.js"></script>

</div>

</div>
</main>

<?php include 'include/footer.php'; ?>

</body>
</html>
