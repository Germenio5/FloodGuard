<?php

/**
 * Map Markers Model
 * Handles database operations for map markers
 */

/**
 * Create a new marker
 * 
 * @param mysqli $conn Database connection
 * @param float $lat Latitude
 * @param float $lng Longitude
 * @param string $title Marker title
 * @param string $description Marker description
 * @param string $type Marker type (bridges, normal, warning, danger, critical, flooded)
 * @return int|bool Marker ID if successful, false otherwise
 */
function create_marker($conn, $lat, $lng, $title, $description, $type) {
    $lat = floatval($lat);
    $lng = floatval($lng);
    $title = $conn->real_escape_string(trim($title));
    $description = $conn->real_escape_string(trim($description));
    $type = $conn->real_escape_string(trim($type));
    
    $query = "INSERT INTO map_markers (lat, lng, title, description, type, created_at, updated_at) 
              VALUES ($lat, $lng, '$title', '$description', '$type', NOW(), NOW())";
    
    if ($conn->query($query) === TRUE) {
        return $conn->insert_id;
    } else {
        error_log("Database error in create_marker: " . $conn->error);
        return false;
    }
}

/**
 * Update an existing marker
 * 
 * @param mysqli $conn Database connection
 * @param int $id Marker ID
 * @param float $lat Latitude
 * @param float $lng Longitude
 * @param string $title Marker title
 * @param string $description Marker description
 * @param string $type Marker type (bridges, normal, warning, danger, critical, flooded)
 * @return bool True if successful, false otherwise
 */
function update_marker($conn, $id, $lat, $lng, $title, $description, $type) {
    $id = intval($id);
    $lat = floatval($lat);
    $lng = floatval($lng);
    $title = $conn->real_escape_string(trim($title));
    $description = $conn->real_escape_string(trim($description));
    $type = $conn->real_escape_string(trim($type));
    
    $query = "UPDATE map_markers SET lat = $lat, lng = $lng, title = '$title', 
              description = '$description', type = '$type', updated_at = NOW() 
              WHERE id = $id";
    
    if ($conn->query($query) === TRUE) {
        return true;
    } else {
        error_log("Database error in update_marker: " . $conn->error);
        return false;
    }
}

/**
 * Delete a marker
 * 
 * @param mysqli $conn Database connection
 * @param int $id Marker ID
 * @return bool True if successful, false otherwise
 */
function delete_marker($conn, $id) {
    $id = intval($id);
    
    $query = "DELETE FROM map_markers WHERE id = $id";
    
    if ($conn->query($query) === TRUE) {
        return true;
    } else {
        error_log("Database error in delete_marker: " . $conn->error);
        return false;
    }
}

/**
 * Get all markers
 * 
 * @param mysqli $conn Database connection
 * @return array Array of markers
 */
function get_all_markers($conn) {
    $markers = [];
    
    $query = "SELECT id, lat, lng, title, description, type, created_at, updated_at 
              FROM map_markers 
              ORDER BY updated_at DESC";
    
    $result = $conn->query($query);
    
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $markers[] = $row;
        }
        $result->free();
    }
    
    return $markers;
}

/**
 * Get marker by ID
 * 
 * @param mysqli $conn Database connection
 * @param int $id Marker ID
 * @return array|bool Marker data or false if not found
 */
function get_marker_by_id($conn, $id) {
    $id = intval($id);
    
    $query = "SELECT id, lat, lng, title, description, type, created_at, updated_at 
              FROM map_markers 
              WHERE id = $id LIMIT 1";
    
    $result = $conn->query($query);
    
    if ($result && $result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    return false;
}

?>
