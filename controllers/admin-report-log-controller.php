<?php
require_once '../config/config.php';

// Fetch all flood reports
$sql = "SELECT 
            id,
            location as name,
            location as area,
            status,
            DATE_FORMAT(created_at, '%Y-%m-%d %H:%i:%s') as last_updated,
            proximity,
            photo,
            description
        FROM flood_reports 
        ORDER BY created_at DESC";

$result = $conn->query($sql);

$reports = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $reports[] = $row;
    }
}

// Function to get badge class based on status
function getBadgeClass($status) {
    switch (strtolower($status)) {
        case 'safe':
            return 'status-safe';
        case 'in danger':
            return 'status-danger';
        default:
            return 'status-unknown';
    }
}
?>