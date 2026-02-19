<?php

/**
 * Reports Model
 * Handles all database operations related to flood reports
 */

/**
 * Get all reports
 * 
 * @param mysqli $conn Database connection
 * @param int $limit Number of records to retrieve
 * @param int $offset Offset for pagination
 * @return array Array of reports
 */
function get_all_reports($conn, $limit = null, $offset = 0) {
    $reports = [];
    $query = "SELECT id, user_email, location, status, description, image_path, post_news, created_at 
              FROM reports 
              ORDER BY created_at DESC";
    
    if ($limit !== null) {
        $limit = intval($limit);
        $offset = intval($offset);
        $query .= " LIMIT $limit OFFSET $offset";
    }
    
    $result = $conn->query($query);
    
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $reports[] = $row;
        }
        $result->free();
    }
    
    return $reports;
}

/**
 * Get report by ID
 * 
 * @param mysqli $conn Database connection
 * @param int $report_id Report ID
 * @return array|bool Report data or false if not found
 */
function get_report_by_id($conn, $report_id) {
    $report_id = intval($report_id);
    $query = "SELECT id, user_email, location, status, description, image_path, post_news, created_at 
              FROM reports 
              WHERE id = $report_id LIMIT 1";
    
    $result = $conn->query($query);
    
    if ($result && $result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    return false;
}

/**
 * Create new flood report
 * 
 * @param mysqli $conn Database connection
 * @param string $user_email User email
 * @param string $location Report location
 * @param string $status Flood status
 * @param string $description Report description
 * @param string $image_path Path to report image
 * @param int $post_news Whether to post to news (0 or 1)
 * @return bool True if successful, false otherwise
 */
function create_report($conn, $user_email, $location, $status, $description, $image_path = null, $post_news = 0) {
    $user_email = $conn->real_escape_string(trim($user_email));
    $location = $conn->real_escape_string(trim($location));
    $status = $conn->real_escape_string(trim($status));
    $description = $conn->real_escape_string(trim($description));
    $image_path = $image_path ? $conn->real_escape_string(trim($image_path)) : null;
    $post_news = intval($post_news);
    
    $image_clause = $image_path ? "'$image_path'" : "NULL";
    
    $query = "INSERT INTO reports (user_email, location, status, description, image_path, post_news, created_at) 
              VALUES ('$user_email', '$location', '$status', '$description', $image_clause, $post_news, NOW())";
    
    if ($conn->query($query) === TRUE) {
        return true;
    } else {
        error_log("Database error in create_report: " . $conn->error);
        return false;
    }
}

/**
 * Get total count of reports
 * 
 * @param mysqli $conn Database connection
 * @return int Total number of reports
 */
function get_reports_count($conn) {
    $query = "SELECT COUNT(*) as total FROM reports";
    $result = $conn->query($query);
    
    if ($result) {
        $row = $result->fetch_assoc();
        return (int)$row['total'];
    }
    return 0;
}

/**
 * Get reports by user email
 * 
 * @param mysqli $conn Database connection
 * @param string $email User email
 * @return array Array of reports
 */
function get_reports_by_user($conn, $email) {
    $reports = [];
    $email = $conn->real_escape_string(strtolower(trim($email)));
    
    $query = "SELECT id, user_email, location, status, description, image_path, post_news, created_at 
              FROM reports 
              WHERE LOWER(user_email) = '$email'
              ORDER BY created_at DESC";
    
    $result = $conn->query($query);
    
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $reports[] = $row;
        }
        $result->free();
    }
    
    return $reports;
}

/**
 * Get reports by status
 * 
 * @param mysqli $conn Database connection
 * @param string $status Filter by status
 * @return array Array of reports
 */
function get_reports_by_status($conn, $status) {
    $reports = [];
    $status = $conn->real_escape_string(trim($status));
    
    $query = "SELECT id, user_email, location, status, description, image_path, post_news, created_at 
              FROM reports 
              WHERE status = '$status'
              ORDER BY created_at DESC";
    
    $result = $conn->query($query);
    
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $reports[] = $row;
        }
        $result->free();
    }
    
    return $reports;
}

/**
 * Get unique locations/barangays from reports
 * 
 * @param mysqli $conn Database connection
 * @return array Array of unique locations
 */
function get_unique_report_locations($conn) {
    $locations = [];
    $query = "SELECT DISTINCT location FROM reports 
              WHERE location IS NOT NULL AND location != '' 
              ORDER BY location ASC";
    
    $result = $conn->query($query);
    
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $locations[] = $row['location'];
        }
        $result->free();
    }
    
    return $locations;
}

/**
 * Get reports filtered by location
 * 
 * @param mysqli $conn Database connection
 * @param string $location Location/barangay name
 * @return array Array of reports
 */
function get_reports_by_location($conn, $location) {
    $reports = [];
    $location = $conn->real_escape_string(trim($location));
    
    $query = "SELECT id, user_email, location, status, description, image_path, post_news, created_at 
              FROM reports 
              WHERE location = '$location'
              ORDER BY created_at DESC";
    
    $result = $conn->query($query);
    
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $reports[] = $row;
        }
        $result->free();
    }
    
    return $reports;
}

?>
