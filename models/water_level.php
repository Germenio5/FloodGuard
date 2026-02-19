<?php

/**
 * Water Level Model
 * Handles all database operations related to water level monitoring
 */

/**
 * Get water level data with pagination
 * 
 * @param mysqli $conn Database connection
 * @param string $area Filter by area name (optional)
 * @param int $limit Items per page
 * @param int $offset Pagination offset
 * @return array Array of water level records
 */
function get_water_levels_paginated($conn, $area = null, $limit = 10, $offset = 0) {
    $levels = [];
    $limit = intval($limit);
    $offset = intval($offset);
    
    $query = "SELECT id, area, height, speed, status, record_time, trend 
              FROM water_level_history";
    
    if ($area) {
        $area = $conn->real_escape_string(trim($area));
        $query .= " WHERE area = '$area'";
    }
    
    $query .= " ORDER BY record_time DESC LIMIT $limit OFFSET $offset";
    
    $result = $conn->query($query);
    
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $levels[] = $row;
        }
        $result->free();
    }
    
    return $levels;
}

/**
 * Get water level record by ID
 * 
 * @param mysqli $conn Database connection
 * @param int $record_id Water level record ID
 * @return array|bool Record data or false if not found
 */
function get_water_level_by_id($conn, $record_id) {
    $record_id = intval($record_id);
    
    $query = "SELECT id, area, height, speed, status, record_time, trend 
              FROM water_level_history 
              WHERE id = $record_id LIMIT 1";
    
    $result = $conn->query($query);
    
    if ($result && $result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    return false;
}

/**
 * Get latest water level for an area
 * 
 * @param mysqli $conn Database connection
 * @param string $area Area name
 * @return array|bool Latest record or false if not found
 */
function get_latest_water_level($conn, $area) {
    $area = $conn->real_escape_string(trim($area));
    
    $query = "SELECT id, area, height, speed, status, record_time, trend 
              FROM water_level_history 
              WHERE area = '$area'
              ORDER BY record_time DESC LIMIT 1";
    
    $result = $conn->query($query);
    
    if ($result && $result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    return false;
}

/**
 * Get water levels by area
 * 
 * @param mysqli $conn Database connection
 * @param string $area Area name
 * @param int $limit Number of records
 * @return array Array of water level records
 */
function get_water_levels_by_area($conn, $area, $limit = 100) {
    $levels = [];
    $area = $conn->real_escape_string(trim($area));
    $limit = intval($limit);
    
    $query = "SELECT id, area, height, speed, status, record_time, trend 
              FROM water_level_history 
              WHERE area = '$area'
              ORDER BY record_time DESC LIMIT $limit";
    
    $result = $conn->query($query);
    
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $levels[] = $row;
        }
        $result->free();
    }
    
    return $levels;
}

/**
 * Get total count of water level records
 * 
 * @param mysqli $conn Database connection
 * @param string $area Filter by area name (optional)
 * @return int Total count
 */
function get_water_levels_count($conn, $area = null) {
    $query = "SELECT COUNT(*) as total FROM water_level_history";
    
    if ($area) {
        $area = $conn->real_escape_string(trim($area));
        $query .= " WHERE area = '$area'";
    }
    
    $result = $conn->query($query);
    
    if ($result) {
        $row = $result->fetch_assoc();
        return (int)$row['total'];
    }
    return 0;
}

/**
 * Get water level data for the last 24 hours for a specific area
 * 
 * @param mysqli $conn Database connection
 * @param string $area Area name
 * @return array Array of water level records (time-ordered, oldest to newest)
 */
function get_water_level_last_24h($conn, $area) {
    $levels = [];
    $area = $conn->real_escape_string(trim($area));
    
    $query = "SELECT id, area, height, speed, status, record_time, trend 
              FROM water_level_history 
              WHERE area = '$area' 
              AND record_time >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
              ORDER BY record_time ASC
              LIMIT 100";
    
    $result = $conn->query($query);
    
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $levels[] = $row;
        }
        $result->free();
    }
    
    return $levels;
}

/**
 * Create new water level record
 * 
 * @param mysqli $conn Database connection
 * @param string $area Area name
 * @param float $height Water level value in meters
 * @param string $status Water level status (normal/alert/danger)
 * @param float $speed Water rising speed (optional)
 * @param string $trend Water trend (rising/falling/steady) (optional)
 * @return bool True if successful, false otherwise
 */
function create_water_level_record($conn, $area, $height, $status, $speed = 0.0, $trend = 'steady') {
    $area = $conn->real_escape_string(trim($area));
    $height = floatval($height);
    $speed = floatval($speed);
    $status = $conn->real_escape_string(trim($status));
    $trend = $conn->real_escape_string(trim($trend));
    
    $query = "INSERT INTO water_level_history (area, height, speed, status, trend, record_time) 
              VALUES ('$area', $height, $speed, '$status', '$trend', NOW())";
    
    if ($conn->query($query) === TRUE) {
        return true;
    } else {
        error_log("Database error in create_water_level_record: " . $conn->error);
        return false;
    }
}

/**
 * Get water levels by status
 * 
 * @param mysqli $conn Database connection
 * @param string $status Water level status filter
 * @return array Array of water level records
 */
function get_water_levels_by_status($conn, $status) {
    $levels = [];
    $status = $conn->real_escape_string(trim($status));
    
    $query = "SELECT id, area, height, speed, status, record_time, trend 
              FROM water_level_history 
              WHERE status = '$status'
              ORDER BY record_time DESC";
    
    $result = $conn->query($query);
    
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $levels[] = $row;
        }
        $result->free();
    }
    
    return $levels;
}

?>
