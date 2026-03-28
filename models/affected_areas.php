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
        // Trigger flood alerts if status changed to warning/danger/critical
        trigger_flood_alerts($conn, $area_id, $data);
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
 * Trigger flood alerts when water levels reach warning/danger/critical status
 * 
 * @param mysqli $conn Database connection
 * @param int $area_id Area ID that was updated
 * @param array $update_data The data that was updated
 */
function trigger_flood_alerts($conn, $area_id, $update_data) {
    // Only trigger if current_level was updated
    if (!isset($update_data['current_level'])) {
        return;
    }

    // Get the updated area data
    $area = get_affected_area_by_id($conn, $area_id);
    if (!$area) {
        return;
    }

    // Calculate status based on percentage
    $maxLevel = (float)$area['max_level'];
    $currentLevel = (float)$area['current_level'];
    $percentage = ($maxLevel > 0) ? min(100, ($currentLevel / $maxLevel) * 100) : 0;

    // Determine status based on new thresholds:
    // 0-24.9% = normal, 25-49.9% = warning, 50-74.9% = danger, 75-100% = critical
    $status = 'normal';
    if ($percentage < 25) {
        $status = 'normal';
    } elseif ($percentage < 50) {
        $status = 'warning';
    } elseif ($percentage < 75) {
        $status = 'danger';
    } else {
        $status = 'critical';
    }

    // Only send alerts for warning, danger, or critical
    if (!in_array($status, ['warning', 'danger', 'critical'])) {
        return;
    }

    // Check if the status changed compared to the last alert sent for this area.
    // If the last sent status is the same as the new status, no need to resend.
    $area_name = $conn->real_escape_string($area['name']);
    $status_escaped = $conn->real_escape_string($status);

    $check_query = "SELECT alert_status FROM flood_alerts_sent 
                    WHERE area_name = '$area_name' 
                    ORDER BY sent_at DESC 
                    LIMIT 1";

    $check_result = $conn->query($check_query);
    if ($check_result) {
        $row = $check_result->fetch_assoc();
        if ($row && isset($row['alert_status']) && strtolower($row['alert_status']) === strtolower($status)) {
            $check_result->close();
            return; // Status did not change since last alert
        }
        $check_result->close();
    }

    // Send SMS alerts to all verified users
    require_once __DIR__ . '/../controllers/sms-utils.php';
    // Use bridge name as the location in the SMS.
    $result = sendFloodAlertToAllUsers($conn, $status, $area['name']);

    // Log the alert
    $log_query = "INSERT INTO flood_alerts_sent (area_name, alert_status, sent_at, recipients_count) 
                  VALUES ('$area_name', '$status_escaped', NOW(), {$result['total_sent']})";
    $conn->query($log_query);
}

/**
 * Send flood alert SMS to all verified users
 * 
 * @param mysqli $conn Database connection
 * @param string $status Alert status (warning, danger, critical)
 * @param string $location Location/area name
 * @return array Result with total_sent count
 */
function sendFloodAlertToAllUsers($conn, $status, $location) {
    require_once __DIR__ . '/../controllers/sms-utils.php';
    
    $stmt = $conn->prepare("SELECT phone FROM users WHERE phone_verified = 1 AND phone != ''");
    $stmt->execute();
    $result = $stmt->get_result();

    $total_sent = 0;
    while ($row = $result->fetch_assoc()) {
        $sms_result = sendFloodAlert($row['phone'], ucfirst($status), $location);
        if ($sms_result['success']) {
            $total_sent++;
        }
    }

    $stmt->close();
    return ['total_sent' => $total_sent];
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

/**
 * Get unique locations/barangays
 * 
 * @param mysqli $conn Database connection
 * @return array Array of unique locations
 */
function get_unique_barangays($conn) {
    $barangays = [];
    $query = "SELECT DISTINCT location FROM affected_areas 
              WHERE location IS NOT NULL AND location != ''";
    
    $result = $conn->query($query);
    
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $loc = trim($row['location']);
            if ($loc === '') continue;
            // strip any Brgy. or Barangay prefix for consistency
            $loc = preg_replace('/^\s*(Brgy\.|Barangay)\s*/i', '', $loc);
            $barangays[] = $loc;
        }
        $result->free();
    }

    // ensure unique and sorted
    $barangays = array_unique($barangays);
    sort($barangays, SORT_STRING | SORT_FLAG_CASE);
    return array_values($barangays);
}

/**
 * Get affected areas filtered by barangay/location
 * 
 * @param mysqli $conn Database connection
 * @param string $location Barangay/location name
 * @return array Array of affected areas
 */
function get_affected_areas_by_location($conn, $location) {
    $areas = [];
    $location = $conn->real_escape_string(trim($location));
    if ($location === '') {
        return $areas;
    }
    // match plain location or with common prefixes
    $prefixed1 = 'Brgy. ' . $location;
    $prefixed2 = 'Barangay ' . $location;
    $prefixed1 = $conn->real_escape_string($prefixed1);
    $prefixed2 = $conn->real_escape_string($prefixed2);

    $query = "SELECT id, name, location, current_level, max_level, speed, updated_at 
              FROM affected_areas 
              WHERE location = '$location' OR location = '$prefixed1' OR location = '$prefixed2'
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
 * Get the latest affected area record for a specific location
 *
 * @param mysqli $conn Database connection
 * @param string $location Barangay/location name
 * @return array|bool Latest affected area record or false if not found
 */
function get_latest_affected_area_by_location($conn, $location) {
    $location = $conn->real_escape_string(trim($location));
    if ($location === '') {
        return false;
    }

    // match plain location or with common prefixes
    $prefixed1 = 'Brgy. ' . $location;
    $prefixed2 = 'Barangay ' . $location;
    $prefixed1 = $conn->real_escape_string($prefixed1);
    $prefixed2 = $conn->real_escape_string($prefixed2);

    $query = "SELECT id, name, location, current_level, max_level, speed, updated_at 
              FROM affected_areas 
              WHERE location = '$location' OR location = '$prefixed1' OR location = '$prefixed2'
              ORDER BY updated_at DESC LIMIT 1";

    $result = $conn->query($query);
    if ($result && $result->num_rows > 0) {
        return $result->fetch_assoc();
    }

    return false;
}

?>
