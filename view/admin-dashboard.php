<?php
include '../controller/admin-dashboard-controller.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../css/admindashboard.css">
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

        <!-- Area Dropdown -->
        <div class="area-select">
            <label>Area</label>
            <select>
                <option>Select Location</option>
                <option>Bacolod City</option>
                <option>Mandalagan</option>
                <option>Taculing</option>
            </select>
        </div>

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
    </div>

</div>
</main>

</body>
</html>
