<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

// Only admins can trigger SMS notifications
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'admin') {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Forbidden: admin access required']);
    exit();
}

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/reports.php';

$reportId = isset($_POST['report_id']) ? intval($_POST['report_id']) : 0;
$forceSend = isset($_POST['force']) && $_POST['force'] === '1';

if ($reportId <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid report id']);
    exit();
}

$report = get_report_by_id($conn, $reportId);
if (!$report) {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'Report not found']);
    exit();
}

$status = trim((string)($report['status'] ?? ''));
if (!in_array(strtolower($status), ['danger', 'in danger'], true)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'SMS notifications can only be sent for reports marked as "Danger".']);
    exit();
}

$userPhone = trim((string)($report['phone'] ?? ''));
if ($userPhone === '') {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'No user phone number is available for this report']);
    exit();
}

// Normalize phone number to PhilSMS expected format (Philippines, E.164 style)
function normalizePhilippinesPhone($phone) {
    // Remove all non-digit characters
    $digits = preg_replace('/\D+/', '', $phone);

    // If it starts with '0' (local format, e.g., 09091234567) -> replace with '63'
    if (strlen($digits) === 11 && strpos($digits, '0') === 0) {
        return '63' . substr($digits, 1);
    }

    // If it starts with '9' and is 10 digits, assume mobile without leading 0
    if (strlen($digits) === 10 && strpos($digits, '9') === 0) {
        return '63' . $digits;
    }

    // If already starts with country code 63 and length is 12, accept it
    if (strlen($digits) === 12 && strpos($digits, '63') === 0) {
        return $digits;
    }

    // Otherwise return digits as-is; let API validate
    return $digits;
}

$normalizedPhone = normalizePhilippinesPhone($userPhone);

if (!$forceSend && !empty($report['sms_sent_at'])) {
    echo json_encode(['success' => false, 'message' => 'An SMS notification has already been sent for this report. Use resend to send it again.']);
    exit();
}

// Build SMS payload
$payload = [
    'recipient' => $normalizedPhone,
    'sender_id' => PHILSMS_SENDER_ID,
    'type' => 'plain',
    'message' => "FloodGuard Update: Your report has been confirmed. Emergency support will be sent to your area. Please stay alert and follow safety instructions."
];

$ch = curl_init(PHILSMS_API_URL);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . PHILSMS_API_TOKEN,
    'Content-Type: application/json',
    'Accept: application/json',
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

$response = curl_exec($ch);
$httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
curl_close($ch);

if ($response === false || $httpStatus < 200 || $httpStatus >= 300) {
    $errorMsg = 'Failed to send SMS.';
    if ($curlError) {
        $errorMsg .= ' ' . $curlError;
    } elseif ($response) {
        $decoded = json_decode($response, true);
        if (isset($decoded['message'])) {
            $errorMsg .= ' ' . $decoded['message'];
        } elseif (isset($decoded['error'])) {
            $errorMsg .= ' ' . $decoded['error'];
        }
    }

    http_response_code(502);
    echo json_encode(['success' => false, 'message' => $errorMsg]);
    exit();
}

// Update report with sms_sent_at timestamp, mark as responded, and store SMS as admin response (GMT+8)
$conn->query("SET time_zone = '+08:00'");
$smsMessage = $payload['message'];
$updateStmt = $conn->prepare("UPDATE reports SET sms_sent_at = NOW(), is_responded = 1, admin_response = ?, admin_response_date = NOW() WHERE id = ?");
if ($updateStmt) {
    $updateStmt->bind_param('si', $smsMessage, $reportId);
    $updateStmt->execute();
    $updateStmt->close();
}

echo json_encode(['success' => true, 'message' => 'SMS notification sent successfully']);
exit();
