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
function create_user($conn, $first_name, $last_name, $email, $phone, $address, $password) {
    // Sanitize inputs
    $first_name = $conn->real_escape_string(trim($first_name));
    $last_name = $conn->real_escape_string(trim($last_name));
    $email = $conn->real_escape_string(strtolower(trim($email)));
    $phone = $conn->real_escape_string(trim($phone));
    $address = $conn->real_escape_string(trim($address));
    
    // Hash the password using bcrypt
    $password_hash = password_hash($password, PASSWORD_BCRYPT);
    
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
    $query = "SELECT id, first_name, last_name, email, role, password_hash FROM users WHERE LOWER(email) = '$email' LIMIT 1";
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
 * Get user by ID
 * 
 * @param mysqli $conn Database connection
 * @param int $user_id User ID
 * @return array|bool User data if found, false otherwise
 */
function get_user_by_id($conn, $user_id) {
    $user_id = intval($user_id);
    $query = "SELECT id, first_name, last_name, email, phone, address, role, created_at FROM users WHERE id = $user_id LIMIT 1";
    $result = $conn->query($query);
    
    if ($result && $result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    return false;
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
    $allowed_fields = ['first_name', 'last_name', 'phone', 'address'];
    
    $set_clause = [];
    foreach ($data as $key => $value) {
        if (in_array($key, $allowed_fields)) {
            $value = $conn->real_escape_string(trim($value));
            $set_clause[] = "`$key` = '$value'";
        }
    }
    
    if (empty($set_clause)) {
        return false;
    }
    
    $query = "UPDATE users SET " . implode(", ", $set_clause) . " WHERE id = $user_id";
    
    if ($conn->query($query) === TRUE) {
        return true;
    } else {
        error_log("Database error in update_user: " . $conn->error);
        return false;
    }
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

?>
