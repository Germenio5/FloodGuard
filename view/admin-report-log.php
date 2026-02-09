<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Reports Made - FloodGuard</title>
    <link rel="stylesheet" href="../css/adminreportlog.css">
</head>
<body>
<?php include 'include/header.php'; ?>
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
                    <!-- Sample Data - Remove when connecting to database -->
                    <tr>
                        <td>01</td>
                        <td>Resident #1</td>
                        <td>Mandalagan</td>
                        <td><span class="status-badge badge-no-response">No Response</span></td>
                        <td>11-11-11</td>
                    </tr>
                    <tr>
                        <td>02</td>
                        <td>Resident #2</td>
                        <td>Brgy Mandalagan</td>
                        <td><span class="status-badge badge-danger">Danger</span></td>
                        <td>11-11-11</td>
                    </tr>
                    <tr>
                        <td>03</td>
                        <td>Resident #3</td>
                        <td>Brgy Mandalagan</td>
                        <td><span class="status-badge badge-alert">Alert</span></td>
                        <td>11-11-11</td>
                    </tr>
                    <tr>
                        <td>03</td>
                        <td>Resident #3</td>
                        <td>Brgy Mandalagan</td>
                        <td><span class="status-badge badge-alert">Alert</span></td>
                        <td>11-11-11</td>
                    </tr>

                    <!-- para sa database -->
                    <!-- 
                    When you connect to database, use this format:
                    
                    <?php
                    // Database connection example
                    // $sql = "SELECT * FROM reports ORDER BY id DESC";
                    // $result = mysqli_query($conn, $sql);
                    // while($row = mysqli_fetch_assoc($result)) {
                    //     // Determine badge class based on status
                    //     $badgeClass = '';
                    //     if($row['status'] == 'No Response') $badgeClass = 'badge-no-response';
                    //     elseif($row['status'] == 'Danger') $badgeClass = 'badge-danger';
                    //     elseif($row['status'] == 'Alert') $badgeClass = 'badge-alert';
                    //     elseif($row['status'] == 'Safe') $badgeClass = 'badge-safe';
                    ?>
                        <tr>
                            <td><?php // echo $row['id']; ?></td>
                            <td><?php // echo $row['name']; ?></td>
                            <td><?php // echo $row['area']; ?></td>
                            <td>
                                <span class="status-badge <?php // echo $badgeClass; ?>">
                                    <?php // echo $row['status']; ?>
                                </span>
                            </td>
                            <td><?php // echo $row['last_updated']; ?></td>
                        </tr>
                    <?php
                    // }
                    ?>
                    -->
                </tbody>
            </table>
        </div>

        <!-- Upload News Button -->
        <div class="button-section">
            <button class="upload-btn">Upload News</button>
        </div>

    </div>
</main>
<?php include 'include/footer.php'; ?>
</body>
</html>