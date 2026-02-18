<?php
include '../controllers/admin-dashboard-controller.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/admindashboard.css">
</head>

<body>

<?php include 'include/admin-sidebar.php'; ?>

<main>
<div class="main-wrapper">
    
    <!-- Page Header -->
    <div class="page-header">
        <h1>Admin Panel Flood Monitoring System</h1>
        <p>Real-time Flood Monitoring, Map & Warning System.</p>
    </div>

    <!-- Residents Status Section -->
    <div class="status-section">
        <h2>Residents Status</h2>

        <!-- Area Dropdown Form -->
        <form method="POST" class="area-filter-form">
            <div class="area-select">
                <label for="areaFilter">Filter by Area</label>
                <select id="areaFilter" name="area" onchange="document.getElementsByClassName('area-filter-form')[0].submit()">
                    <option value="">All Areas</option>
                    <?php foreach ($areas as $area): ?>
                        <option value="<?= $area ?>" <?= ($selectedArea === $area) ? 'selected' : '' ?>>
                            <?= $area ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </form>

        <!-- Status Cards -->
        <div class="status-cards">

            <div class="status-card card-safe">
                <p>Safe Residents</p>
                <h3><?= $stats["safe"] ?></h3>
            </div>

            <div class="status-card card-danger">
                <p>In Danger Residents</p>
                <h3><?= $stats["danger"] ?></h3>
            </div>

            <div class="status-card card-registered">
                <p>Registered Residents</p>
                <h3><?= $stats["registered"] ?></h3>
            </div>

            <div class="status-card card-no-response">
                <p>No Response</p>
                <h3><?= $stats["no_response"] ?></h3>
            </div>

        </div>
    </div>

    <!-- Resident List Section -->
    <div class="list-section">
        <h2>Resident List</h2>
        
        <!-- Pagination Info -->
        <div class="pagination-info">
            <p>Page <span class="current-page"><?= $currentPage ?></span> of <span class="total-pages"><?= $totalPages ?></span> / Total residents: <span class="total-records"><?= $totalRecords ?></span></p>
        </div>
        
        <?php if (empty($residents)): ?>
            <div class="no-residents">
                <p>No residents found<?php if (!empty($selectedArea)): ?> in <?= htmlspecialchars($selectedArea) ?><?php endif; ?>.</p>
            </div>
        <?php else: ?>
        
        <!-- Table -->
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Address</th>
                        <th>Phone Number</th>
                        <th>Email</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>

                <?php foreach ($residents as $r): ?>

                    <tr>
                        <td><?= $r["id"] ?></td>
                        <td><?= $r["name"] ?></td>
                        <td><?= $r["address"] ?></td>
                        <td><?= $r["phone"] ?></td>
                        <td><?= $r["email"] ?></td>

                        <td>
                            <span class="status-badge <?= getStatusClass($r["status"]) ?>">
                                <?= $r["status"] ?>
                            </span>
                        </td>
                    </tr>

                <?php endforeach; ?>

                </tbody>
            </table>
        </div>
        
        <!-- Pagination Buttons -->
        <div class="pagination-buttons">
            <?php foreach ($paginationButtons as $btn): ?>
                <?php if ($btn['disabled']): ?>
                    <button class="pagination-btn disabled" disabled><?= $btn['label'] ?></button>
                <?php else: ?>
                    <a href="?page=<?= $btn['page'] ?><?= !empty($selectedArea) ? '&area=' . urlencode($selectedArea) : '' ?>" 
                       class="pagination-btn <?= $btn['active'] ? 'active' : '' ?>">
                        <?= $btn['label'] ?>
                    </a>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
        
        <?php endif; ?>
    </div>

</div>
</main>

</body>
</html>
