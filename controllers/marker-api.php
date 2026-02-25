<?php

/**
 * Map Markers API Controller
 * Handles AJAX requests for marker operations
 */

header('Content-Type: application/json');

session_start();

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/map_markers.php';

// Only authenticated users can save markers
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

$action = isset($_GET['action']) ? trim($_GET['action']) : '';
$response = ['success' => false, 'message' => 'Invalid request'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if ($action === 'create') {
        // Create new marker
        if (isset($data['lat'], $data['lng'], $data['title'], $data['type'])) {
            $id = create_marker(
                $conn,
                $data['lat'],
                $data['lng'],
                $data['title'],
                $data['description'] ?? '',
                $data['type']
            );
            
            if ($id !== false) {
                $response = [
                    'success' => true,
                    'message' => 'Marker created',
                    'id' => $id
                ];
            } else {
                $response = ['success' => false, 'message' => 'Failed to create marker'];
            }
        }
    } elseif ($action === 'update') {
        // Update existing marker
        if (isset($data['id'], $data['lat'], $data['lng'], $data['title'], $data['type'])) {
            $success = update_marker(
                $conn,
                $data['id'],
                $data['lat'],
                $data['lng'],
                $data['title'],
                $data['description'] ?? '',
                $data['type']
            );
            
            if ($success) {
                $response = ['success' => true, 'message' => 'Marker updated'];
            } else {
                $response = ['success' => false, 'message' => 'Failed to update marker'];
            }
        }
    } elseif ($action === 'delete') {
        // Delete marker
        if (isset($data['id'])) {
            $success = delete_marker($conn, $data['id']);
            
            if ($success) {
                $response = ['success' => true, 'message' => 'Marker deleted'];
            } else {
                $response = ['success' => false, 'message' => 'Failed to delete marker'];
            }
        }
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if ($action === 'list') {
        // Get all markers
        $markers = get_all_markers($conn);
        $response = [
            'success' => true,
            'markers' => $markers
        ];
    }
}

http_response_code(200);
echo json_encode($response);

?>
