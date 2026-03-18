<?php

/**
 * SMS Utility Functions for PhilSMS API Integration
 */

/**
 * Normalize Philippine phone number to E.164 format
 */
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

/**
 * Send SMS via PhilSMS API
 */
function sendSMS($recipient, $message, $type = 'plain') {
    $normalizedPhone = normalizePhilippinesPhone($recipient);

    $payload = [
        'recipient' => $normalizedPhone,
        'sender_id' => PHILSMS_SENDER_ID,
        'type' => $type,
        'message' => $message
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

    $decodedResponse = $response ? json_decode($response, true) : null;

    if ($response === false || $httpStatus < 200 || $httpStatus >= 300) {
        $errorMsg = 'Failed to send SMS.';
        if ($curlError) {
            $errorMsg .= ' ' . $curlError;
        } elseif (is_array($decodedResponse)) {
            if (isset($decodedResponse['message'])) {
                $errorMsg .= ' ' . $decodedResponse['message'];
            } elseif (isset($decodedResponse['error'])) {
                $errorMsg .= ' ' . $decodedResponse['error'];
            }
        }
        return ['success' => false, 'message' => $errorMsg, 'api_response' => $decodedResponse];
    }

    return ['success' => true, 'message' => 'SMS sent successfully', 'api_response' => $decodedResponse];
}

/**
 * Generate a random 6-digit code
 */
function generateVerificationCode() {
    return str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);
}

/**
 * Send verification code SMS
 */
function sendVerificationCode($phone, $code) {
    $message = "FloodGuard Verification Code: $code. This code will expire in 10 minutes.";
    return sendSMS($phone, $message);
}

/**
 * Send OTP for password reset
 */
function sendOTP($phone, $otp) {
    $message = "FloodGuard Password Reset OTP: $otp. This OTP will expire in 10 minutes.";
    return sendSMS($phone, $message);
}

/**
 * Send flood alert notification
 */
function sendFloodAlert($phone, $status, $location = '', $description = '') {
    // Use a standard message format:
    // FloodGuard Alert:
    //
    // Location: {location}
    // Status: {status}
    // {description}

    $statusText = ucfirst($status);

    // Default descriptions based on status
    $defaultDescriptions = [
        'Warning' => 'Water levels are rising in your area. Stay alert and prepare for possible evacuation.',
        'Alert' => 'Flood warning issued for your area. Monitor local news and be ready to evacuate if necessary.',
        'Danger' => 'Flood danger in your area. Emergency support is on the way. Please follow safety instructions and evacuate immediately if advised.',
        'Critical' => 'Flood conditions are critical. Evacuate immediately if advised and seek higher ground.'
    ];

    $description = $description ?: ($defaultDescriptions[$statusText] ?? "Flood status changed to $statusText in your area. Stay safe.");

    $message = "FloodGuard Alert:\n\n" .
               "Location: " . ($location ?: 'Your Area') . "\n" .
               "Status: $statusText\n" .
               "$description";

    return sendSMS($phone, $message);
}

/**
 * Send flood alert with custom description to all verified users
 * Used when admin posts a flood alert via the admin panel
 */
function sendFloodAlertWithDescription($description, $location, $status, $conn) {
    $stmt = $conn->prepare("SELECT phone FROM users WHERE phone_verified = 1 AND phone != ''");
    if (!$stmt) {
        return ['success_count' => 0, 'fail_count' => 0, 'error' => 'Database query failed'];
    }
    
    $stmt->execute();
    $result = $stmt->get_result();

    $successCount = 0;
    $failCount = 0;

    // Construct the SMS message with alert details
    $sms_message = "FloodGuard Alert - $location ($status): $description";
    
    while ($row = $result->fetch_assoc()) {
        $sms_result = sendSMS($row['phone'], $sms_message);
        if ($sms_result['success']) {
            $successCount++;
        } else {
            $failCount++;
        }
    }

    $stmt->close();
    return ['success_count' => $successCount, 'fail_count' => $failCount];
}

/**
 * Send safety advisory/news SMS to all users
 */
function sendSafetyAdvisoryToAll($message, $conn) {
    $stmt = $conn->prepare("SELECT phone FROM users WHERE phone_verified = 1 AND phone != ''");
    $stmt->execute();
    $result = $stmt->get_result();

    $successCount = 0;
    $failCount = 0;

    while ($row = $result->fetch_assoc()) {
        $result = sendSMS($row['phone'], "FloodGuard Safety Advisory: $message");
        if ($result['success']) {
            $successCount++;
        } else {
            $failCount++;
        }
    }

    $stmt->close();
    return ['success_count' => $successCount, 'fail_count' => $failCount];
}

?>