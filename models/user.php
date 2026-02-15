<?php

/**
 * @param mysqli $conn
 * @param string $email
 * @return bool
 */
function user_exists($conn, $email) {
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    if (!$stmt) {
        return false;
    }
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->store_result();
    $exists = $stmt->num_rows > 0;
    $stmt->close();
    return $exists;
}

/**
 * insert a new user record into the database
 * returns true on success, false otherwise
 */
function create_user($conn, $first, $last, $email, $phone, $address, $password) {
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, email, phone, address, password_hash) VALUES (?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        return false;
    }
    $stmt->bind_param('ssssss', $first, $last, $email, $phone, $address, $hash);
    $res = $stmt->execute();
    $stmt->close();
    return $res;
}

/**
 * authenticate an identifier (email or phone) and password
 * returns array with user data (including role) or false on failure
 */
function authenticate_user($conn, $identifier, $password) {
    $stmt = $conn->prepare("SELECT password_hash, role, email FROM users WHERE email = ? OR phone = ?");
    if (!$stmt) {
        return false;
    }
    $stmt->bind_param('ss', $identifier, $identifier);
    $stmt->execute();
    $stmt->bind_result($hash, $role, $email);
    if ($stmt->fetch()) {
        $stmt->close();
        if (password_verify($password, $hash)) {
            return ['role' => $role, 'email' => $email];
        }
    }
    $stmt->close();
    return false;
}
