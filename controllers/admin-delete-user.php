<?php
session_start();

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header("Location: ../views/login-user.php?error=login_required");
    exit();
}

if (isset($_SESSION['user_role']) && $_SESSION['user_role'] !== 'admin') {
    header("Location: ../views/user-dashboard.php");
    exit();
}

require_once __DIR__ . '/../config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'])) {
    $user_id = intval($_POST['user_id']);

    // Prevent admin from deleting themselves
    if ($user_id === $_SESSION['user_id']) {
        header("Location: ../views/admin-dashboard.php?error=cannot_delete_self");
        exit();
    }

    // Delete the user
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        // Also delete any related verification codes
        $stmt2 = $conn->prepare("DELETE FROM verification_codes WHERE email = (SELECT email FROM users WHERE id = ?)");
        $stmt2->bind_param("i", $user_id);
        $stmt2->execute();
        $stmt2->close();

        header("Location: ../views/admin-dashboard.php?message=user_deleted");
    } else {
        header("Location: ../views/admin-dashboard.php?error=delete_failed");
    }

    $stmt->close();
} else {
    header("Location: ../views/admin-dashboard.php");
}

$conn->close();
?>