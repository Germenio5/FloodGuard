<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Residents Status - FloodGuard</title>
    <link rel="stylesheet" href="../css/admindashboard.css">
</head>
<body>
<main>
    <div class="main-wrapper">
        
        <!-- Page Header -->
        <div class="page-header">
            <h1>Bacolod City Flood Monitoring System</h1>
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
                    <h3>520</h3>
                </div>
                <div class="status-card card-danger">
                    <p>In Danger Residents</p>
                    <h3>670</h3>
                </div>
                <div class="status-card card-registered">
                    <p>Registered Residents</p>
                    <h3>9102</h3>
                </div>
                <div class="status-card card-no-response">
                    <p>No Response</p>
                    <h3>7112</h3>
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
                        <!-- Sample Data - Remove when connecting to database -->
                        <tr>
                            <td>01</td>
                            <td>Resident #1</td>
                            <td>Somewhere sa Bacolod</td>
                            <td>09292284383</td>
                            <td>resident1@example.com</td>
                            <td><span class="status-badge status-safe">Safe</span></td>
                        </tr>
                        <tr>
                            <td>02</td>
                            <td>Resident #2</td>
                            <td>Balay ni Christian</td>
                            <td>09292284383</td>
                            <td>resident2@example.com</td>
                            <td><span class="status-badge status-danger">In Danger</span></td>
                        </tr>
                        <tr>
                            <td>03</td>
                            <td>Resident #3</td>
                            <td>Brgy Mandalagan</td>
                            <td>09292284383</td>
                            <td>resident3@example.com</td>
                            <td><span class="status-badge status-safe">Safe</span></td>
                        </tr>
                        
                        <!-- pang database nga part ni grrr -->
                        <!-- 
                        Amo ni format gamiton ta lezgo cutiepinksies
                        
                        <?php
                        // Database connection example
                        // $sql = "SELECT * FROM residents";
                        // $result = mysqli_query($conn, $sql);
                        // while($row = mysqli_fetch_assoc($result)) {
                        ?>
                            <tr>
                                <td><?php // echo $row['id']; ?></td>
                                <td><?php // echo $row['name']; ?></td>
                                <td><?php // echo $row['address']; ?></td>
                                <td><?php // echo $row['phone']; ?></td>
                                <td><?php // echo $row['email']; ?></td>
                                <td>
                                    <span class="status-badge <?php // echo $row['status'] == 'Safe' ? 'status-safe' : 'status-danger'; ?>">
                                        <?php // echo $row['status']; ?>
                                    </span>
                                </td>
                            </tr>
                        <?php
                        // }
                        ?>
                        -->
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</main>
</body>
</html>