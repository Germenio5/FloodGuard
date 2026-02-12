<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Water Level Data History</title>
    <link rel="stylesheet" href="../assets/css/admineditwaterlevel.css">
</head>
<body>
<?php include 'include/admin-sidebar.php'; ?>
<main>
    <div class="main-wrapper">
        
        <!-- Page Header -->
        <div class="page-header">
            <h1>Edit Water Level Data</h1>
            <p>Water level information provides insight into the current state of rivers, creeks, and other waterways. It helps residents and authorities understand potential flood risks, track changes over time, and make informed decisions to stay safe.</p>
        </div>

        <!-- Recent Data Section -->
        <div class="data-section">
            <h2>Water Level Data</h2>

            <!-- Data Table -->
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>AREA</th>
                            <th>TREND</th>
                            <th>DATE</th>
                            <th>HEIGHT (M)</th>
                            <th>SPEED (M/HR)</th>
                            <th>STATUS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Eroreco Bridge</td>
                            <td><span class="badge badge-steady">🟢 Steady</span></td>
                            <td>11/26/2026</td>
                            <td>2.3m</td>
                            <td>0.3</td>
                            <td><span class="status status-normal">● Normal</span></td>
                        </tr>
                        <tr>
                            <td>Eroreco Bridge</td>
                            <td><span class="badge badge-rising">📈 Rising</span></td>
                            <td>11/26/2026</td>
                            <td>2.3m</td>
                            <td>6.7</td>
                            <td><span class="status status-danger">● Danger</span></td>
                        </tr>
                        <tr>
                            <td>Eroreco Bridge</td>
                            <td><span class="badge badge-falling">📉 Falling</span></td>
                            <td>11/26/2026</td>
                            <td>67m</td>
                            <td>0.1</td>
                            <td><span class="status status-alert">● Alert</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination and Download -->
            <div class="table-footer">
                <div class="pagination">
                    <button class="page-btn">Previous</button>
                    <button class="page-btn active">1</button>
                    <button class="page-btn">2</button>
                    <button class="page-btn">3</button>
                    <button class="page-btn">4</button>
                    <button class="page-btn">5</button>
                    <span>...</span>
                    <button class="page-btn">9</button>
                    <button class="page-btn">Next</button>
                </div>
                <div class="button-row">
                <button class="download-btn">Download Data</button>
                <button class="edit-btn">Edit Data</button>
                </div>
            </div>

        </div>

    </div>
</main>
</body>
</html>
