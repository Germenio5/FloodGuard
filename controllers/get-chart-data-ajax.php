<?php
/**
 * AJAX Endpoint for getting chart data
 * Returns JSON formatted chart data based on period selection
 */

session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

// Check if it's an AJAX request
if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest') {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request']);
    exit();
}

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/user.php';
require_once __DIR__ . '/../models/affected_areas.php';
require_once __DIR__ . '/../models/water_level.php';

$userId = $_SESSION['user_id'];

// Get period from request
$chartPeriod = isset($_GET['chart_period']) ? $_GET['chart_period'] : 'daily';
$validPeriods = ['daily', 'weekly', 'monthly'];
if (!in_array($chartPeriod, $validPeriods)) {
    $chartPeriod = 'daily';
}

// Get area name from request (or from user's barangay)
$areaName = isset($_GET['area_name']) ? trim($_GET['area_name']) : '';

// If no area name provided, get from user's address
if (!$areaName) {
    $userFromDb = get_user_by_id($conn, $userId);
    $barangay = extract_barangay_from_address($userFromDb['address'] ?? '');
    
    if ($barangay !== '') {
        $areaForUser = get_latest_affected_area_by_location($conn, $barangay);
        if ($areaForUser) {
            $areaName = $areaForUser['name'];
        }
    }
    
    if (!$areaName) {
        $latestArea = get_latest_affected_area($conn);
        $areaName = $latestArea ? $latestArea['name'] : 'Default Area';
    }
}

// Fetch chart data based on period
$chartData = get_user_dashboard_chart_data($conn, $userId, $areaName, $chartPeriod);

if (!$chartData) {
    // If no cached data, generate it from water_level_history
    $chartData = generate_chart_data_for_period($conn, $areaName, $chartPeriod);

    // Cache the generated data
    if (!empty($chartData['labels']) && !empty($chartData['heights'])) {
        upsert_user_dashboard_chart_data($conn, $userId, $areaName, $chartPeriod, $chartData['labels'], $chartData['heights']);
    }
}

// Fallback to sample data if no data available
if (empty($chartData['labels']) || empty($chartData['heights'])) {
    switch ($chartPeriod) {
        case 'daily':
            $sampleTimes = [
                '00:00', '01:00', '02:00', '03:00', '04:00', '05:00',
                '06:00', '07:00', '08:00', '09:00', '10:00', '11:00',
                '12:00', '13:00', '14:00', '15:00', '16:00', '17:00',
                '18:00', '19:00', '20:00', '21:00', '22:00', '23:00'
            ];
            $sampleHeights = [
                4.2, 4.5, 4.3, 4.1, 3.9, 3.8,
                4.0, 4.3, 4.6, 5.0, 5.3, 5.5,
                5.4, 5.2, 5.1, 5.3, 5.5, 5.7,
                5.8, 5.6, 5.4, 5.2, 4.8, 4.5
            ];
            break;
        case 'weekly':
            $sampleTimes = ['Day 1', 'Day 2', 'Day 3', 'Day 4', 'Day 5', 'Day 6', 'Day 7'];
            $sampleHeights = [4.2, 4.5, 4.3, 4.1, 3.9, 4.0, 4.3];
            break;
        case 'monthly':
            $sampleTimes = ['Week 1', 'Week 2', 'Week 3', 'Week 4'];
            $sampleHeights = [4.2, 4.5, 4.3, 4.1];
            break;
    }
    $chartData = ['labels' => $sampleTimes, 'heights' => $sampleHeights];
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode([
    'success' => true,
    'data' => [
        'labels' => $chartData['labels'],
        'heights' => $chartData['heights'],
        'period' => $chartPeriod,
        'area_name' => $areaName
    ]
]);
