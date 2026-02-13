<?php
session_start();
require_once __DIR__ . '/../config/config.php';

// grab fields from POST, sanitize basic input
$location      = trim($_POST['location'] ?? '');
$status        = trim($_POST['status'] ?? '');
$description   = trim($_POST['description'] ?? '');
$postNews      = isset($_POST['post_discussion']) ? 1 : 0;
$userEmail     = $_SESSION['email'] ?? null; // may be null if not logged in

// validate required fields
if ($location === '' || $status === '') {
    header('Location: ../views/user-report-flood.php?error=required');
    exit();
}

// prepare and insert into database
$stmt = $conn->prepare("INSERT INTO reports (user_email, location, status, description, post_news) VALUES (?, ?, ?, ?, ?)");
if (!$stmt) {
    // log error or handle
    header('Location: ../views/user-report-flood.php?error=db');
    exit();
}
$stmt->bind_param('ssssi', $userEmail, $location, $status, $description, $postNews);
if ($stmt->execute()) {
    // if the user asked for posting to news we could also insert a record in a
    // news table or rely on the news page to read from reports where post_news=1
    header('Location: ../views/user-report-flood.php?success=1');
} else {
    header('Location: ../views/user-report-flood.php?error=db');
}
exit();
