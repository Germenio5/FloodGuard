<?php
session_start();

// Only admin should update water level data
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: ../views/login-user.php?error=login_required');
    exit();
}

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/affected_areas.php';

// Expect POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../views/admin-water-status.php');
    exit();
}

$areaId = isset($_POST['area_id']) ? intval($_POST['area_id']) : 0;
$currentLevel = isset($_POST['current_level']) ? trim($_POST['current_level']) : '';

if ($areaId <= 0 || $currentLevel === '') {
    header('Location: ../views/admin-water-status.php?error=missing');
    exit();
}

$currentLevel = floatval($currentLevel);

// Update only current level; max level is fixed in DB
$success = update_affected_area($conn, $areaId, [
    'current_level' => $currentLevel
]);

// Determine response type (AJAX vs normal)
$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

if ($success) {
    // Fetch updated data for response
    $updatedArea = get_affected_area_by_id($conn, $areaId);

    if ($isAjax) {
        // Recalculate percentage and status for response using fixed max level from DB
        $maxLevel = (float)$updatedArea['max_level'];
        $currentLevel = (float)$updatedArea['current_level'];
        $percentage = ($maxLevel > 0) ? min(100, ($currentLevel / $maxLevel) * 100) : 0;

        $status = 'normal';
        if ($percentage < 25) {
            $status = 'normal';
        } elseif ($percentage < 50) {
            $status = 'warning';
        } elseif ($percentage < 75) {
            $status = 'danger';
        } else {
            $status = 'critical';
        }

        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'area_id' => $areaId,
            'current_level' => number_format($currentLevel, 2) . 'm',
            'max_level' => number_format($maxLevel, 2) . 'm',
            'percentage' => round($percentage, 1),
            'status' => $status,
        ]);
        exit();
    }

    header('Location: ../views/admin-water-status.php?success=updated');
    exit();
} else {
    if ($isAjax) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Update failed']);
        exit();
    }

    header('Location: ../views/admin-water-status.php?error=update_failed');
    exit();
}
