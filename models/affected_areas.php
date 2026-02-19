<?php

/**
 * Affected Areas Model
 * Handles all database operations related to affected areas
 */

/**
 * Get all affected areas
 * 
 * @param mysqli $conn Database connection
 * @return array Array of affected areas
 */
function get_all_affected_areas($conn) {
    $areas = [];
    $query = "SELECT id, name, location, current_level, max_level, speed, updated_at 
              FROM affected_areas 
              ORDER BY name ASC";
    
    $result = $conn->query($query);
    
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $areas[] = $row;
        }
        $result->free();
    }
    
    return $areas;
}

/**
 * Get affected areas with pagination
 * 
 * @param mysqli $conn Database connection
 * @param int $limit Items per page
 * @param int $offset Pagination offset
 * @return array Array of affected areas
 */
function get_affected_areas_paginated($conn, $limit = 10, $offset = 0) {
    $areas = [];
    $limit = intval($limit);
    $offset = intval($offset);
    
    $query = "SELECT id, name, location, current_level, max_level, speed, updated_at 
              FROM affected_areas 
              ORDER BY name ASC LIMIT $limit OFFSET $offset";
    
    $result = $conn->query($query);
    
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $areas[] = $row;
        }
        $result->free();
    }
    
    return $areas;
}

/**
 * Get affected area by ID
 * 
 * @param mysqli $conn Database connection
 * @param int $area_id Area ID
 * @return array|bool Area data or false if not found
 */
function get_affected_area_by_id($conn, $area_id) {
    $area_id = intval($area_id);
    $query = "SELECT id, name, location, current_level, max_level, speed, updated_at 
              FROM affected_areas 
              WHERE id = $area_id LIMIT 1";
    
    $result = $conn->query($query);
    
    if ($result && $result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    return false;
}

/**
 * Update affected area status
 * 
 * @param mysqli $conn Database connection
 * @param int $area_id Area ID
 * @param array $data Data to update (name, location, current_level, max_level, speed, status)
 * @return bool True if successful, false otherwise
 */
function update_affected_area($conn, $area_id, $data) {
    $area_id = intval($area_id);
    $allowed_fields = ['name', 'location', 'current_level', 'max_level', 'speed', 'status'];
    
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
    
    $query = "UPDATE affected_areas SET " . implode(", ", $set_clause) . " WHERE id = $area_id";
    
    if ($conn->query($query) === TRUE) {
        return true;
    } else {
        error_log("Database error in update_affected_area: " . $conn->error);
        return false;
    }
}

/**
 * Get total count of affected areas
 * 
 * @param mysqli $conn Database connection
 * @return int Total number of affected areas
 */
function get_affected_areas_count($conn) {
    $query = "SELECT COUNT(*) as total FROM affected_areas";
    $result = $conn->query($query);
    
    if ($result) {
        $row = $result->fetch_assoc();
        return (int)$row['total'];
    }
    return 0;
}

/**
 * Get affected areas with filters
 * 
 * @param mysqli $conn Database connection
 * @param string $status Filter by status (normal, alert, danger)
 * @return array Array of affected areas
 */
function get_affected_areas_by_status($conn, $status) {
    $areas = [];
    $status = $conn->real_escape_string(strtolower(trim($status)));
    
    // Status is now calculated dynamically based on percentage, not stored in DB
    // This function returns all areas (status filtering done in controller)
    $query = "SELECT id, name, location, current_level, max_level, speed, updated_at 
              FROM affected_areas 
              ORDER BY name ASC";
    
    $result = $conn->query($query);
    
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $areas[] = $row;
        }
        $result->free();
    }
    
    return $areas;
}

/**
 * Get latest affected area by most recent update
 * 
 * @param mysqli $conn Database connection
 * @return array|bool Latest affected area record or false if not found
 */
function get_latest_affected_area($conn) {
    $query = "SELECT id, name, location, current_level, max_level, speed, updated_at 
              FROM affected_areas 
              ORDER BY updated_at DESC LIMIT 1";
    
    $result = $conn->query($query);
    
    if ($result && $result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    return false;
}

/**
 * Get affected area by name
 * 
 * @param mysqli $conn Database connection
 * @param string $name Area name
 * @return array|bool Affected area record or false if not found
 */
function get_affected_area_by_name($conn, $name) {
    $name = $conn->real_escape_string(trim($name));
    
    $query = "SELECT id, name, location, current_level, max_level, speed, updated_at 
              FROM affected_areas 
              WHERE name = '$name' LIMIT 1";
    
    $result = $conn->query($query);
    
    if ($result && $result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    return false;
}

?>
