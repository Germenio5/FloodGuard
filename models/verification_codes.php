<?php

/**
 * Verification Codes Model
 * Handles all database operations related to verification codes
 */

// Set timezone to GMT+8 (Asia/Manila)
date_default_timezone_set('Asia/Manila');

/**
 * Create a new verification code
 *
 * @param mysqli $conn Database connection
 * @param string $email User email
 * @param string $phone User phone
 * @param string $code_type Type of code ('phone_verification' or 'password_reset')
 * @return string|bool Generated code on success, false on failure
 */
function create_verification_code($conn, $email, $phone, $code_type = 'phone_verification') {
    // Normalize inputs for consistent matching
    $email = strtolower(trim($email));
    $phone = trim($phone);

    $code = generateVerificationCode();

    // Use database time for expiration to avoid timezone mismatch issues
    $stmt = $conn->prepare("INSERT INTO verification_codes (email, phone, verification_code, code_type, expires_at) VALUES (?, ?, ?, ?, DATE_ADD(NOW(), INTERVAL 10 MINUTE))");
    $stmt->bind_param('ssss', $email, $phone, $code, $code_type);

    if ($stmt->execute()) {
        $stmt->close();
        return $code;
    } else {
        $stmt->close();
        return false;
    }
}

/**
 * Verify a code
 *
 * @param mysqli $conn Database connection
 * @param string $email User email
 * @param string $code Verification code
 * @param string $code_type Type of code
 * @return bool True if code is valid and not used
 */
function verify_code($conn, $email, $code, $code_type = 'phone_verification') {
    // Normalize input values for consistent matching
    $email = strtolower(trim($email));
    $code = trim($code);

    // Clean up any expired codes before verifying
    cleanup_expired_codes($conn);

    $stmt = $conn->prepare("SELECT id FROM verification_codes WHERE email = ? AND verification_code = ? AND code_type = ? AND used = 0 AND expires_at > NOW() ORDER BY created_at DESC LIMIT 1");
    $stmt->bind_param('sss', $email, $code, $code_type);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $stmt->close();

        // Mark code as used
        $updateStmt = $conn->prepare("UPDATE verification_codes SET used = 1 WHERE id = ?");
        $updateStmt->bind_param('i', $row['id']);
        $updateStmt->execute();
        $updateStmt->close();

        return true;
    }

    $stmt->close();
    return false;
}

/**
 * Get latest unused verification code for a user
 *
 * @param mysqli $conn Database connection
 * @param string $email User email
 * @param string $code_type Type of code
 * @return array|bool Code data or false if not found
 */
function get_latest_verification_code($conn, $email, $code_type = 'phone_verification') {
    // Clean up expired codes before fetching the latest one
    cleanup_expired_codes($conn);

    $stmt = $conn->prepare("SELECT * FROM verification_codes WHERE email = ? AND code_type = ? AND used = 0 AND expires_at > NOW() ORDER BY created_at DESC LIMIT 1");
    $stmt->bind_param('ss', $email, $code_type);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $code = $result->fetch_assoc();
        $stmt->close();
        return $code;
    }

    $stmt->close();
    return false;
}

/**
 * Clean up expired codes
 *
 * @param mysqli $conn Database connection
 * @return bool True on success
 */
function cleanup_expired_codes($conn) {
    $stmt = $conn->prepare("DELETE FROM verification_codes WHERE expires_at < NOW()");
    $stmt->execute();
    $stmt->close();
    return true;
}

/**
 * Mark all codes for a user as used (for security)
 *
 * @param mysqli $conn Database connection
 * @param string $email User email
 * @param string $code_type Type of code
 * @return bool True on success
 */
function invalidate_user_codes($conn, $email, $code_type = null) {
    if ($code_type) {
        $stmt = $conn->prepare("UPDATE verification_codes SET used = 1 WHERE email = ? AND code_type = ?");
        $stmt->bind_param('ss', $email, $code_type);
    } else {
        $stmt = $conn->prepare("UPDATE verification_codes SET used = 1 WHERE email = ?");
        $stmt->bind_param('s', $email);
    }

    $stmt->execute();
    $stmt->close();
    return true;
}

?>