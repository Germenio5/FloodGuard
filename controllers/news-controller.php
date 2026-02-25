<?php
session_start();

date_default_timezone_set('Asia/Manila');

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/reports.php';
require_once __DIR__ . '/../models/user.php';

// Handle POST request for uploading new news
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    
    // Check if user is authenticated (admin)
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Unauthorized - Please log in']);
        exit();
    }
    
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $area = isset($_POST['area']) ? trim($_POST['area']) : '';
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';
    $status = isset($_POST['status']) ? trim($_POST['status']) : 'Alert';
    
    // Validate input
    if (empty($area) || empty($description)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Missing required fields: area and description']);
        exit();
    }
    
    // Handle file upload
    $imageData = null;
    if (isset($_FILES['picture']) && $_FILES['picture']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['picture']['tmp_name'];
        $fileName = $_FILES['picture']['name'];
        $fileSize = $_FILES['picture']['size'];
        $fileType = $_FILES['picture']['type'];
        
        // Validate file
        $allowedMimes = ['image/jpeg', 'image/png'];
        if (!in_array($fileType, $allowedMimes)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid file type. Only images allowed.']);
            exit();
        }
        
        // Validate file size (15MB max)
        if ($fileSize > 15 * 1024 * 1024) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'File size exceeds 15MB limit']);
            exit();
        }
        
        // Read file as binary
        $imageData = file_get_contents($fileTmpPath);
        if ($imageData === false) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to read uploaded file']);
            exit();
        }
    } else {
        $fileError = $_FILES['picture']['error'] ?? 'Unknown';
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'No image file provided (Error: ' . $fileError . ')']);
        exit();
    }
    
    // Get user email from session
    $userEmail = isset($_SESSION['user_email']) ? $_SESSION['user_email'] : 'admin@floodguard.local';
    
    // Insert into reports table with post_news = 1
    $query = "INSERT INTO reports (
        user_email, location, description, status, image, post_news, created_at
    ) VALUES (?, ?, ?, ?, ?, 1, NOW())";
    
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $conn->error]);
        exit();
    }
    
    // Bind parameters: sssss for user_email, location, description, status, and image as string
    $stmt->bind_param('sssss', $userEmail, $area, $description, $status, $imageData);
    
    // For BLOB data, use send_long_data
    $stmt->send_long_data(4, $imageData);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'News posted successfully']);
        $stmt->close();
        exit();
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Failed to post news: ' . $stmt->error]);
        $stmt->close();
        exit();
    }
}

// summary counts could also be derived from the database if desired
$dangerCount = 0;
$alertCount  = 0;

$lastUpdated = [
    'date' => date('F j, Y'),
    'time' => date('g:i A')
];

// start with FloodGuard system entry
$eventList = [
    [
        'name'        => 'FloodGuard',
        'avatar'      => '../assets/images/FloodGuard_logo.png',
        'time'        => '2 weeks ago',
        'area'        => 'Mandalagan Bridge',
        'picture'     => '../assets/images/Sample.png',
        'description' => 'Water rising rapidly, might flood soon. Stay safe and avoid the area.',
        'status'      => 'Danger'
    ]
];

// Load additional events from reports table using model
// Fetch all reported news items
$reports_data = get_all_reports($conn);

// Filter reports that should be posted as news (post_news = 1)
if ($reports_data) {
    foreach ($reports_data as $row) {
        // Only include reports marked for news posting
        if (isset($row['post_news']) && $row['post_news'] == 1) {
            // Check if this is a system alert (admin posted via upload modal)
            $isSystemAlert = stripos($row['user_email'], 'admin') !== false || 
                            $row['user_email'] === 'admin@floodguard.local' ||
                            $row['user_email'] === 'system@floodguard.local';
            
            if ($isSystemAlert) {
                // Use FloodGuard branding for system alerts
                $fullName = 'FloodGuard';
                $avatarSrc = '../assets/images/FloodGuard_logo.png';
            } else {
                // derive full name from email if possible
                $fullName = 'Anonymous';
                $avatarSrc = '../assets/images/placeholder-image.png';
                if (!empty($row['user_email'])) {
                    // look up user record
                    $userInfo = get_user_by_email($conn, $row['user_email']);
                    if ($userInfo) {
                        $fullName = trim($userInfo['first_name'] . ' ' . $userInfo['last_name']);
                        if (!empty($userInfo['profile_photo'])) {
                            $avatarSrc = 'data:image/jpeg;base64,' . base64_encode($userInfo['profile_photo']);
                        }
                    } else {
                        $fullName = $row['user_email'];
                    }
                }
            }
            // compute "time ago"
            $created = strtotime($row['created_at']);
            $diff = time() - $created;
            if ($diff < 60) {
                $timeStr = $diff . ' seconds ago';
            } elseif ($diff < 3600) {
                $timeStr = floor($diff/60) . ' minutes ago';
            } elseif ($diff < 86400) {
                $timeStr = floor($diff/3600) . ' hours ago';
            } else {
                $timeStr = floor($diff/86400) . ' days ago';
            }
            // extract only barangay portion and prefix with 'Brgy.'
            $parts = explode(',', $row['location']);
            $raw = trim($parts[0]);
            // remove existing prefix if any
            $raw = preg_replace('/^\s*(Brgy\.|Barangay)\s*/i', '', $raw);
            $areaName = 'Brgy. ' . $raw;
            // prepare picture from blob if exists
            $pictureSrc = '../assets/images/placeholder-image.png';
            if (!empty($row['image'])) {
                $pictureSrc = 'data:image/jpeg;base64,' . base64_encode($row['image']);
            }
            $eventList[] = [
                'id'          => $row['id'],
                'name'        => htmlspecialchars($fullName),
                'avatar'      => $avatarSrc,
                'time'        => $timeStr,
                'area'        => htmlspecialchars($areaName),
                'picture'     => $pictureSrc,
                'description' => htmlspecialchars($row['description']),
                'status'      => htmlspecialchars($row['status'])
            ];
        }
    }
}

// compute simple summary counts based on the loaded events
foreach ($eventList as $e) {
    if ($e['status'] === 'Danger') {
        $dangerCount++;
    } elseif ($e['status'] === 'Alert') {
        $alertCount++;
    }
}

?>
