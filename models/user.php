<?php

/**
 * User Model - Handles all user-related database operations
 * Implements MVC pattern for user management
 */

/**
 * Check if user exists by email
 * 
 * @param mysqli $conn Database connection
 * @param string $email User email
 * @return bool True if user exists, false otherwise
 */
function user_exists($conn, $email) {
    $email = $conn->real_escape_string(strtolower($email));
    $query = "SELECT id FROM users WHERE LOWER(email) = '$email' LIMIT 1";
    $result = $conn->query($query);
    
    if ($result && $result->num_rows > 0) {
        return true;
    }
    return false;
}

/**
 * Fetch user record by email address (case insensitive)
 *
 * @param mysqli $conn
 * @param string $email
 * @return array|bool User row or false if not found
 */
function get_user_by_email($conn, $email) {
    $email = $conn->real_escape_string(strtolower(trim($email)));
    $query = "SELECT id, first_name, last_name, email, phone, address, profile_photo, role, phone_verified, last_active, created_at, status FROM users WHERE LOWER(email) = '$email' LIMIT 1";
    $result = $conn->query($query);
    if ($result && $result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    return false;
}

/**
 * Fetch user record by phone number
 *
 * @param mysqli $conn
 * @param string $phone
 * @return array|bool User row or false if not found
 */
function get_user_by_phone($conn, $phone) {
    $phone = $conn->real_escape_string(trim($phone));
    $query = "SELECT id, first_name, last_name, email, phone, address, profile_photo, role, phone_verified, last_active, created_at, status FROM users WHERE phone = '$phone' LIMIT 1";
    $result = $conn->query($query);
    if ($result && $result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    return false;
}


/**
/**
 * Fetch user record by ID
 *
 * @param mysqli $conn Database connection
 * @param int $user_id User ID
 * @return array|bool User data if found, false otherwise
 */
function get_user_by_id($conn, $user_id) {
    $user_id = intval($user_id);
    $query = "SELECT id, first_name, last_name, email, phone, address, profile_photo, role, phone_verified, last_active, created_at, status FROM users WHERE id = $user_id LIMIT 1";
    $result = $conn->query($query);
    
    if ($result && $result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    return false;
}
/**
 * Create a new user account
 * 
 * @param mysqli $conn Database connection
 * @param string $first_name User's first name
 * @param string $last_name User's last name
 * @param string $email User's email
 * @param string $phone User's phone number
 * @param string $address User's address
 * @param string $password Plain text password (will be hashed)
 * @return bool True if user created successfully, false otherwise
 */
function create_user($conn, $first_name, $last_name, $email, $phone, $address, $password, $passwordIsHashed = false) {
    // Sanitize inputs
    $first_name = $conn->real_escape_string(trim($first_name));
    $last_name = $conn->real_escape_string(trim($last_name));
    $email = $conn->real_escape_string(strtolower(trim($email)));
    $phone = $conn->real_escape_string(trim($phone));
    $address = $conn->real_escape_string(trim($address));
    
    // Hash the password if it isn't already hashed
    $password_hash = $passwordIsHashed ? $password : password_hash($password, PASSWORD_BCRYPT);
    
    // Insert new user
    $query = "INSERT INTO users (first_name, last_name, email, phone, address, password_hash, role) 
              VALUES ('$first_name', '$last_name', '$email', '$phone', '$address', '$password_hash', 'user')";
    
    if ($conn->query($query) === TRUE) {
        return true;
    } else {
        error_log("Database error in create_user: " . $conn->error);
        return false;
    }
}

/**
 * Authenticate user login
 * 
 * @param mysqli $conn Database connection
 * @param string $email User email
 * @param string $password Plain text password
 * @return array|bool User data array if successful, false if authentication fails
 */
function authenticate_user($conn, $email, $password) {
    $email = $conn->real_escape_string(strtolower(trim($email)));
    
    // Fetch user from database
    $query = "SELECT id, first_name, last_name, email, role, phone_verified, password_hash FROM users WHERE LOWER(email) = '$email' LIMIT 1";
    $result = $conn->query($query);
    
    if (!$result || $result->num_rows === 0) {
        return false; // User not found
    }
    
    $user = $result->fetch_assoc();
    
    // Verify password using bcrypt
    if (password_verify($password, $user['password_hash'])) {
        // Remove password hash from returned data
        unset($user['password_hash']);
        return $user;
    }
    
    return false; // Password incorrect
}

/**
 * Update user's last active timestamp
 *
 * @param mysqli $conn
 * @param int $user_id
 * @return bool
 */
function update_user_last_active($conn, $user_id) {
    $user_id = intval($user_id);
    $query = "UPDATE users SET last_active = CURRENT_TIMESTAMP WHERE id = $user_id";
    return $conn->query($query);
}

/**
 * Update user profile
 * 
 * @param mysqli $conn Database connection
 * @param int $user_id User ID
 * @param array $data Associative array of fields to update
 * @return bool True if updated successfully, false otherwise
 */
function update_user($conn, $user_id, $data) {
    $user_id = intval($user_id);
    $allowed_fields = ['first_name', 'last_name', 'phone', 'address', 'email', 'profile_photo', 'status'];
    
    $set_clause = [];
    $types = '';
    $params = [];
    $paramNames = [];
    
    foreach ($data as $key => $value) {
        if (in_array($key, $allowed_fields)) {
            $set_clause[] = "`$key` = ?";
            if ($key === 'profile_photo' && is_string($value) && !empty($value)) {
                // Binary data for profile_photo
                $types .= 'b';
            } else {
                // String data for other fields
                $types .= 's';
            }
            $params[] = $value;
            $paramNames[] = $key;
        }
    }
    
    if (empty($set_clause)) {
        return false;
    }
    
    // Add user_id parameter
    $types .= 'i';
    $params[] = $user_id;
    
    $query = "UPDATE users SET " . implode(", ", $set_clause) . " WHERE id = ?";
    
    $stmt = $conn->prepare($query);
    
    if (!$stmt) {
        error_log("Prepare failed: " . $conn->error);
        return false;
    }
    
    // Build bind_param call dynamically
    $bindParams = [$types];
    foreach ($params as &$param) {
        $bindParams[] = &$param;
    }
    
    if (!call_user_func_array([$stmt, 'bind_param'], $bindParams)) {
        error_log("Bind param failed: " . $stmt->error);
        $stmt->close();
        return false;
    }
    
    // Handle blob data with send_long_data if present
    $profilePhotoIndex = -1;
    foreach ($data as $key => $value) {
        if ($key === 'profile_photo' && is_string($value) && !empty($value)) {
            $profilePhotoIndex = array_search('profile_photo', $paramNames);
            if ($profilePhotoIndex !== false) {
                $stmt->send_long_data($profilePhotoIndex, $value);
            }
            break;
        }
    }
    
    if (!$stmt->execute()) {
        error_log("Execute failed: " . $stmt->error);
        $stmt->close();
        return false;
    }
    
    $stmt->close();
    return true;
}

/**
 * Change user password
 * 
 * @param mysqli $conn Database connection
 * @param int $user_id User ID
 * @param string $old_password Current password for verification
 * @param string $new_password New password
 * @return bool|string True if changed successfully, error message otherwise
 */
function change_user_password($conn, $user_id, $old_password, $new_password) {
    $user_id = intval($user_id);
    
    // Get current password hash
    $query = "SELECT password_hash FROM users WHERE id = $user_id LIMIT 1";
    $result = $conn->query($query);
    
    if (!$result || $result->num_rows === 0) {
        return "User not found";
    }
    
    $user = $result->fetch_assoc();
    
    // Verify old password
    if (!password_verify($old_password, $user['password_hash'])) {
        return "Current password is incorrect";
    }
    
    // Hash new password
    $new_hash = password_hash($new_password, PASSWORD_BCRYPT);
    
    // Update password
    $update_query = "UPDATE users SET password_hash = '$new_hash' WHERE id = $user_id";
    if ($conn->query($update_query) === TRUE) {
        return true;
    } else {
        error_log("Database error in change_user_password: " . $conn->error);
        return "Database error occurred";
    }
}

/**
 * Get all regular users with pagination
 * 
 * @param mysqli $conn Database connection
 * @param int $limit Items per page
 * @param int $offset Pagination offset
 * @param string $search_area Optional area filter
 * @return array Array of user records
 */
function get_users_paginated($conn, $limit = 10, $offset = 0, $search_area = '') {
    $users = [];
    $limit = intval($limit);
    $offset = intval($offset);
    
    $query = "SELECT id, first_name, last_name, email, phone, address, phone_verified, last_active, status FROM users WHERE role = 'user'";
    
    if (!empty($search_area)) {
        $search_area = $conn->real_escape_string(trim($search_area));
        $query .= " AND address LIKE '%$search_area%'";
    }
    
    $query .= " ORDER BY first_name ASC LIMIT $limit OFFSET $offset";
    
    $result = $conn->query($query);
    
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
        $result->free();
    }
    
    return $users;
}

/**
 * Get total count of regular users
 * 
 * @param mysqli $conn Database connection
 * @param string $search_area Optional area filter
 * @return int Total number of users
 */
function get_users_count($conn, $search_area = '') {
    $query = "SELECT COUNT(*) as total FROM users WHERE role = 'user'";
    
    if (!empty($search_area)) {
        $search_area = $conn->real_escape_string(trim($search_area));
        $query .= " AND address LIKE '%$search_area%'";
    }
    
    $result = $conn->query($query);
    
    if ($result) {
        $row = $result->fetch_assoc();
        return (int)$row['total'];
    }
    return 0;
}

/**
 * Get unique user addresses for dropdown filtering
 * 
 * @param mysqli $conn Database connection
 * @return array Array of unique addresses
 */
function get_unique_user_addresses($conn) {
    // Return a list of distinct barangays extracted from users' addresses.
    // Addresses are assumed to store the barangay as the first comma-separated
    // segment (e.g. "Brgy. Something, City, Province").
    $barangays = [];
    $query = "SELECT DISTINCT address FROM users WHERE role = 'user' AND address IS NOT NULL AND address != ''";

    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $addr = trim($row['address']);
            if ($addr === '') {
                continue;
            }
            // take the first component before comma as barangay
            $parts = explode(',', $addr);
            $brgy = trim($parts[0]);
            if ($brgy !== '') {
                // remove any existing "Brgy." or "Barangay" prefix so we don't double up
                $brgy = preg_replace('/^\s*(Brgy\.|Barangay)\s*/i', '', $brgy);
                $barangays[] = $brgy;
            }
        }
        $result->free();
    }

    // ensure unique and alphabetically sorted
    $barangays = array_unique($barangays);
    sort($barangays, SORT_STRING | SORT_FLAG_CASE);
    return array_values($barangays);
}

/**
 * Extract barangay segment from a full address string.
 *
 * The address is expected to contain the barangay as the first
 * comma-separated component, optionally prefixed with "Brgy." or
 * "Barangay". This helper returns the barangay name without prefix.
 *
 * @param string $address Full address stored for the user
 * @return string Cleaned barangay name (empty string if none)
 */
function extract_barangay_from_address($address) {
    $address = trim($address);
    if ($address === '') {
        return '';
    }
    $parts = explode(',', $address);
    $brgy = trim($parts[0]);
    // strip any leading prefix
    $brgy = preg_replace('/^\s*(Brgy\.|Barangay)\s*/i', '', $brgy);
    return $brgy;
}

?>
