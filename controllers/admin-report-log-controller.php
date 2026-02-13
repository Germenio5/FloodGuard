<?php

// ----- fetch reports from database -----
require_once __DIR__ . '/../config/config.php';

$reports = [];
$sql = "SELECT id, user_email, location AS area, status, created_at AS last_updated FROM reports ORDER BY created_at DESC";
if ($result = $conn->query($sql)) {
    while ($row = $result->fetch_assoc()) {
        // user_email may be null; use placeholder if necessary
        $row['name'] = $row['user_email'] ?: 'Unknown';
        $reports[] = $row;
    }
}


function getBadgeClass($status) {
    switch ($status) {
        case "No Response":
            return "badge-no-response";

        case "Danger":
            return "badge-danger";

        case "Alert":
            return "badge-alert";

        case "Safe":
            return "badge-safe";

        default:
            return "";
    }
}

?>
