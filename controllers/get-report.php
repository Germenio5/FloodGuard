<?php
// Return JSON report by ID for admin panel (AJAX)
session_start();
header('Content-Type: application/json; charset=utf-8');

// Check user session (any logged-in user can view)
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(['error' => 'forbidden']);
    exit();
}

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/reports.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'invalid_id']);
    exit();
}

$report = get_report_by_id($conn, $id);
if (!$report) {
    http_response_code(404);
    echo json_encode(['error' => 'not_found']);
    exit();
}

// Normalize output keys
$output = [
    'id' => (int)$report['id'],
    'user_email' => $report['user_email'] ?? null,
    'location' => $report['location'] ?? null,
    'status' => $report['status'] ?? null,
    'description' => $report['description'] ?? null,
    'image' => isset($report['image']) ? base64_encode($report['image']) : null,
    'created_at' => $report['created_at'] ?? null
];

echo json_encode($output);
exit();
?>