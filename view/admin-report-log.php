<?php
include '../controller/admin-report-log-controller.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Reports Made - FloodGuard</title>
    <link rel="stylesheet" href="../css/adminreportlog.css">
</head>

<body>

<?php include 'include/admin-sidebar.php'; ?>

<main>
<div class="main-wrapper">
    
    <!-- Page Title -->
    <h1>Reports Made</h1>

    <!-- Reports Table -->
    <div class="table-container">
        <table>

            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>AREA</th>
                    <th>STATUS</th>
                    <th>LAST UPDATED</th>
                </tr>
            </thead>

            <tbody>

            <?php foreach ($reports as $r): ?>

                <tr>
                    <td><?= htmlspecialchars($r["id"]) ?></td>

                    <td><?= htmlspecialchars($r["name"]) ?></td>

                    <td><?= htmlspecialchars($r["area"]) ?></td>

                    <td>
                        <span class="status-badge <?= getBadgeClass($r["status"]) ?>">
                            <?= htmlspecialchars($r["status"]) ?>
                        </span>
                    </td>

                    <td><?= htmlspecialchars($r["last_updated"]) ?></td>
                </tr>

            <?php endforeach; ?>

            </tbody>

        </table>
    </div>

    <!-- Upload News Button -->
    <div class="button-section">
        <button class="upload-btn">Upload News</button>
    </div>

</div>
</main>

</body>
</html>
