<?php
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Not authenticated']);
    exit();
}

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/user.php';

$userId = intval($_SESSION['user_id']);

$status = null;
// Accept form POST or JSON
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['status'])) {
        $status = trim($_POST['status']);
    } else {
        // Try JSON body
        $body = json_decode(file_get_contents('php://input'), true);
        if (isset($body['status'])) $status = trim($body['status']);
    }
}

if ($status === null) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Missing status']);
    exit();
}

$allowed = ['Safe', 'In Danger', 'Alert'];
if (!in_array($status, $allowed, true)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid status']);
    exit();
}

$update = ['status' => $status];
$ok = update_user($conn, $userId, $update);

if ($ok) {
    // Optionally update session
    $_SESSION['user_status'] = $status;
    echo json_encode(['success' => true, 'status' => $status]);
    exit();
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error']);
    exit();
}

?>
