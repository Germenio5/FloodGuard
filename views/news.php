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

<script>
function escapeHtml(str) {
    if (!str) return '';
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}
function getBadgeClassJS(status) {
    if (status === 'Safe') return 'status-safe';
    if (status === 'At-Risk' || status === 'Danger') return 'status-danger';
    return 'status-unknown';
}
function viewReport(reportId) {
    const modal = document.getElementById('reportModal');
    const modalBody = document.getElementById('modalBody');
    fetch(`../controllers/get-report.php?id=${encodeURIComponent(reportId)}`)
        .then(res=>res.json())
        .then(report=>{
            let statusBadgeClass = getBadgeClassJS(report.status);
            modalBody.innerHTML = `
                <div class="detail-row"><div class="detail-label">Location:</div><div class="detail-value">${escapeHtml(report.location)}</div></div>
                <div class="detail-row"><div class="detail-label">Status:</div><div class="detail-value"><span class="status-badge ${statusBadgeClass}">${escapeHtml(report.status)}</span></div></div>
                <div class="detail-row"><div class="detail-label">Description:</div><div class="detail-value">${escapeHtml(report.description)}</div></div>
                <div class="detail-row"><div class="detail-label">Date Submitted:</div><div class="detail-value">${escapeHtml(report.created_at)}</div></div>
                <div class="image-row">${report.image ? `<img src="data:image/jpeg;base64,${report.image}" class="report-image">` : ''}</div>
            `;
            modal.style.display='block';
        })
        .catch(err=>console.error(err));
}
function closeModal(){document.getElementById('reportModal').style.display='none';}
document.addEventListener('click',function(e){
    if(e.target.classList.contains('menu')){
        const id=e.target.getAttribute('data-report-id');
        if(id) viewReport(id);
    }
    if(e.target==document.getElementById('reportModal')) closeModal();
});
</script>

</div>

</div>
</main>

<?php include 'include/footer.php'; ?>

</body>
</html>
