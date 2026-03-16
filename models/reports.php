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
    $query = "SELECT id, user_email, location, status, description, image, post_news, created_at 
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

    // Join with users to include the report author's phone number and name
    $query = "SELECT r.id, r.user_email, r.location, r.status, r.description, r.image, r.post_news, r.sms_sent_at, r.created_at, 
              u.first_name, u.last_name, u.phone 
              FROM reports r 
              LEFT JOIN users u ON r.user_email = u.email 
              WHERE r.id = $report_id LIMIT 1";
    
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
 * @param string|resource|null $image Binary image data or null
 * @param int $post_news Whether to post to news (0 or 1)
 * @return bool True if successful, false otherwise
 */
function create_report($conn, $user_email, $location, $status, $description, $image = null, $post_news = 0) {
    $user_email = $conn->real_escape_string(trim($user_email));
    $location = $conn->real_escape_string(trim($location));
    $status = $conn->real_escape_string(trim($status));
    $description = $conn->real_escape_string(trim($description));
    $post_news = intval($post_news);

    // Prepare statement to insert blob data
    $stmt = $conn->prepare(
        "INSERT INTO reports (user_email, location, status, description, image, post_news, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())"
    );
    if (!$stmt) {
        error_log("Prepare failed in create_report: " . $conn->error);
        return false;
    }

    // bind parameters; 'b' for blob placeholder
    $null = NULL;
    $stmt->bind_param('ssssbi', $user_email, $location, $status, $description, $null, $post_news);

    if ($image !== null) {
        // send long blob data
        $stmt->send_long_data(4, $image);
    }

    $success = $stmt->execute();
    if (!$success) {
        error_log("Execute failed in create_report: " . $stmt->error);
    }
    $stmt->close();
    return $success;
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
    
    $query = "SELECT id, user_email, location, status, description, image, post_news, created_at 
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
    
    $query = "SELECT id, user_email, location, status, description, image, post_news, created_at 
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
function get_reports_by_location($conn, $location, $limit = null, $offset = 0) {
    $reports = [];
    $location = $conn->real_escape_string(trim($location));
    $query = "SELECT id, user_email, location, status, description, image, post_news, created_at 
              FROM reports 
              WHERE location = '$location'
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
 * Get total count of reports for a location
 * @param mysqli $conn Database connection
 * @param string $location Location/barangay name
 * @return int Total number of reports for location
 */
function get_reports_count_by_location($conn, $location) {
    $location = $conn->real_escape_string(trim($location));
    $query = "SELECT COUNT(*) as total FROM reports WHERE location = '$location'";
    $result = $conn->query($query);
    if ($result) {
        $row = $result->fetch_assoc();
        return (int)$row['total'];
    }
    return 0;
}

?>
