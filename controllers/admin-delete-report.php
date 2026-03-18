<?php
session_start();

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header("Location: ../views/login-user.php?error=login_required");
    exit();
}

if (isset($_SESSION['user_role']) && $_SESSION['user_role'] !== 'admin') {
    header("Location: ../views/admin-dashboard.php");
    exit();
}

require_once __DIR__ . '/../config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['report_id'])) {
    $report_id = intval($_POST['report_id']);

    // Delete the report
    $stmt = $conn->prepare("DELETE FROM reports WHERE id = ?");
    $stmt->bind_param("i", $report_id);

    if ($stmt->execute()) {
        header("Location: ../views/admin-report-log.php?message=report_deleted");
    } else {
        header("Location: ../views/admin-report-log.php?error=delete_failed");
    }

    $stmt->close();
} else {
    header("Location: ../views/admin-report-log.php");
}

$conn->close();
?>