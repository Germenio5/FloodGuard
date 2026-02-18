<?php
/**
 * Download Water Level Data as CSV
 * Generates and downloads water level history as CSV file
 */

session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header("Location: ../views/login-user.php?error=login_required");
    exit();
}

// Load database connection
require_once __DIR__ . '/../config/config.php';

// Fetch all water level history (not paginated for download)
$waterLevels = [];
$query = "SELECT area, trend, record_time, height, speed, status
          FROM water_level_history
          ORDER BY record_time DESC";

if ($result = $conn->query($query)) {
    while ($row = $result->fetch_assoc()) {
        $waterLevels[] = $row;
    }
    $result->free();
}

// If no data, use sample data
if (empty($waterLevels)) {
    $waterLevels = [
        [
            "area" => "Eroreco Bridge",
            "trend" => "steady",
            "record_time" => "2026-02-19 08:00:00",
            "height" => "2.3",
            "speed" => "0.3",
            "status" => "normal"
        ]
    ];
}

// Generate CSV filename with timestamp
$filename = 'water_level_data_' . date('Y-m-d_H-i-s') . '.csv';

// Set headers for CSV download
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Pragma: no-cache');
header('Expires: 0');

// Open PHP output as CSV
$output = fopen('php://output', 'w');

// Write BOM for proper UTF-8 encoding in Excel
fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

// Write CSV header row
fputcsv($output, ['Area', 'Trend', 'Date/Time', 'Height (M)', 'Speed (M/HR)', 'Status']);

// Format trend and status for display
function formatTrend($trend) {
    $trends = [
        'steady' => 'Steady',
        'rising' => 'Rising',
        'falling' => 'Falling'
    ];
    return isset($trends[$trend]) ? $trends[$trend] : ucfirst($trend);
}

function formatStatus($status) {
    $statuses = [
        'normal' => 'Normal',
        'alert' => 'Alert',
        'danger' => 'Danger'
    ];
    return isset($statuses[$status]) ? $statuses[$status] : ucfirst($status);
}

// Write data rows
foreach ($waterLevels as $row) {
    $recordDate = date('m/d/Y H:i', strtotime($row['record_time']));
    fputcsv($output, [
        $row['area'],
        formatTrend($row['trend']),
        $recordDate,
        round($row['height'], 2),
        round($row['speed'], 2),
        formatStatus($row['status'])
    ]);
}

// Add summary row
fputcsv($output, []);
fputcsv($output, ['Summary Information']);
fputcsv($output, ['Total Records', count($waterLevels)]);
fputcsv($output, ['Download Date', date('m/d/Y H:i:s')]);
fputcsv($output, ['Downloaded by', $_SESSION['user_email'] ?? 'User']]);

fclose($output);
exit();
?>
