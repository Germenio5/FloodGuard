<?php
/**
 * Download Water Level Data as PDF
 * Generates a PDF report with water level data table and statistics
 */

session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header("HTTP/1.0 403 Forbidden");
    die('Access denied. Please login first.');
}

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/water_level.php';
require_once __DIR__ . '/../lib/fpdf/fpdf.php';

// Get selected bridge
$bridge = isset($_GET['bridge']) ? trim($_GET['bridge']) : '';

if (empty($bridge)) {
    header("HTTP/1.0 400 Bad Request");
    die('Bridge name is required.');
}

// Fetch water level data for the bridge (max 1000 records)
$waterLevelData = get_water_levels_by_area($conn, $bridge, 1000);

if (empty($waterLevelData)) {
    header("HTTP/1.0 404 Not Found");
    die('No data found for the selected bridge.');
}

// Create PDF
$pdf = new FPDF('P', 'mm', 'A4');
$pdf->SetMargins(15, 15, 15);
$pdf->SetAutoPageBreak(false, 0); // Disable auto page break

// ===== SINGLE PAGE LAYOUT =====
$pdf->AddPage();

// Set logo/title
$pdf->SetFont('Arial', 'B', 28);
$pdf->SetTextColor(69, 125, 138);
$pdf->Cell(0, 15, 'FloodGuard', 0, 1, 'L');
$pdf->SetTextColor(0, 0, 0);

// Line break
$pdf->Ln(3);

// Bridge information header
$pdf->SetFont('Arial', 'B', 16);
$pdf->SetTextColor(69, 125, 138);
$pdf->Cell(0, 10, 'Bridge Name: ' . htmlspecialchars($bridge), 0, 1, 'L');
$pdf->SetTextColor(0, 0, 0);

// Report generation info
$pdf->SetFont('Arial', '', 10);
$pdf->SetTextColor(100, 100, 100);
$pdf->Cell(0, 6, 'Report Generated: ' . date('M d, Y h:i A'), 0, 1, 'L');
$pdf->Cell(0, 6, 'Total Records: ' . count($waterLevelData), 0, 1, 'L');
$pdf->SetTextColor(0, 0, 0);

// Line break
$pdf->Ln(5);

// ===== TABLE OF WATER LEVEL DATA =====
$pdf->SetFont('Arial', 'B', 16);
$pdf->SetTextColor(69, 125, 138);
$pdf->Cell(0, 10, 'Table of Water Level Data', 0, 1, 'L');
$pdf->SetTextColor(0, 0, 0);
$pdf->Ln(5); // Add space below header

// Table Header
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetFillColor(69, 125, 138);
$pdf->SetTextColor(255, 255, 255);

// Column widths
$colDate = 40;
$colHeight = 30;
$colSpeed = 30;
$colTrend = 30;
$colStatus = 30;

$pdf->Cell($colDate, 12, 'Date/Time', 1, 0, 'C', true);
$pdf->Cell($colHeight, 12, 'Height (m)', 1, 0, 'C', true);
$pdf->Cell($colSpeed, 12, 'Speed (m/h)', 1, 0, 'C', true);
$pdf->Cell($colTrend, 12, 'Trend', 1, 0, 'C', true);
$pdf->Cell($colStatus, 12, 'Status', 1, 1, 'C', true);

// Table Data
$pdf->SetFont('Arial', '', 11);
$pdf->SetTextColor(0, 0, 0);

foreach ($waterLevelData as $row) {
    // Format data
    $date = date('m/d/Y H:i', strtotime($row['record_time']));
    $height = number_format((float)$row['height'], 2);
    $speed = number_format((float)$row['speed'], 2);
    $trend = ucfirst($row['trend']);
    $status = ucfirst($row['status']);
    
    // Status color coding
    $statusColor = [0, 0, 0]; // Default black
    $statusFill = false;
    
    if (strtolower($row['status']) === 'critical') {
        $statusColor = [255, 255, 255]; // White text
        $statusFill = true;
        $pdf->SetFillColor(244, 67, 54); // Red background
    } elseif (strtolower($row['status']) === 'danger') {
        $statusColor = [255, 255, 255]; // White text
        $statusFill = true;
        $pdf->SetFillColor(255, 152, 0); // Orange background
    } elseif (strtolower($row['status']) === 'warning') {
        $statusColor = [0, 0, 0]; // Black text
        $statusFill = true;
        $pdf->SetFillColor(255, 235, 59); // Yellow background
    } elseif (strtolower($row['status']) === 'normal') {
        $statusColor = [0, 0, 0]; // Black text
        $statusFill = true;
        $pdf->SetFillColor(76, 175, 80); // Green background
    }
    
    // Regular cells (no fill)
    $pdf->SetTextColor(0, 0, 0);
    $pdf->Cell($colDate, 10, $date, 1, 0, 'C', false);
    $pdf->Cell($colHeight, 10, $height, 1, 0, 'C', false);
    $pdf->Cell($colSpeed, 10, $speed, 1, 0, 'C', false);
    $pdf->Cell($colTrend, 10, $trend, 1, 0, 'C', false);
    
    // Status cell with color
    $pdf->SetTextColor($statusColor[0], $statusColor[1], $statusColor[2]);
    $pdf->Cell($colStatus, 10, $status, 1, 1, 'C', $statusFill);
}

// Reset text color
$pdf->SetTextColor(0, 0, 0);

// ===== PAGE 2: WATER LEVEL DATA STATISTICS =====
$pdf->AddPage();

$pdf->SetFont('Arial', 'B', 16);
$pdf->SetTextColor(69, 125, 138);
$pdf->Cell(0, 10, 'Water Level Data Statistics', 0, 1, 'L');
$pdf->SetTextColor(0, 0, 0);
$pdf->Ln(5); // Add space below header

// Calculate statistics
$heights = array_map(function($row) { return (float)$row['height']; }, $waterLevelData);
$speeds = array_map(function($row) { return (float)$row['speed']; }, $waterLevelData);

$minHeight = min($heights);
$maxHeight = max($heights);
$avgHeight = array_sum($heights) / count($heights);
$minSpeed = min($speeds);
$maxSpeed = max($speeds);
$avgSpeed = array_sum($speeds) / count($speeds);

// Status counts
$statusCounts = array_count_values(array_map(function($row) { return strtolower($row['status']); }, $waterLevelData));

// Trend counts
$trendCounts = array_count_values(array_map(function($row) { return $row['trend']; }, $waterLevelData));

// Statistics boxes
$pdf->SetFont('Arial', 'B', 13);
$pdf->SetFillColor(245, 245, 245);
$pdf->Cell(0, 10, 'Height Statistics', 0, 1, 'L', true);

$pdf->SetFont('Arial', '', 12);
$pdf->Ln(2);
$pdf->Cell(80, 8, 'Minimum Height:', 0, 0);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 8, number_format($minHeight, 2) . ' m', 0, 1);

$pdf->SetFont('Arial', '', 12);
$pdf->Cell(80, 8, 'Maximum Height:', 0, 0);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 8, number_format($maxHeight, 2) . ' m', 0, 1);

$pdf->SetFont('Arial', '', 12);
$pdf->Cell(80, 8, 'Average Height:', 0, 0);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 8, number_format($avgHeight, 2) . ' m', 0, 1);

$pdf->Ln(5);

$pdf->SetFont('Arial', 'B', 13);
$pdf->SetFillColor(245, 245, 245);
$pdf->Cell(0, 10, 'Speed Statistics', 0, 1, 'L', true);

$pdf->SetFont('Arial', '', 12);
$pdf->Ln(2);
$pdf->Cell(80, 8, 'Minimum Speed:', 0, 0);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 8, number_format($minSpeed, 2) . ' m/h', 0, 1);

$pdf->SetFont('Arial', '', 12);
$pdf->Cell(80, 8, 'Maximum Speed:', 0, 0);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 8, number_format($maxSpeed, 2) . ' m/h', 0, 1);

$pdf->SetFont('Arial', '', 12);
$pdf->Cell(80, 8, 'Average Speed:', 0, 0);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 8, number_format($avgSpeed, 2) . ' m/h', 0, 1);

$pdf->Ln(5);

$pdf->SetFont('Arial', 'B', 13);
$pdf->SetFillColor(245, 245, 245);
$pdf->Cell(0, 10, 'Status Distribution', 0, 1, 'L', true);

$pdf->SetFont('Arial', '', 12);
$pdf->Ln(2);
foreach (['normal' => 'Normal', 'warning' => 'Warning', 'danger' => 'Danger', 'critical' => 'Critical'] as $key => $label) {
    $count = isset($statusCounts[$key]) ? $statusCounts[$key] : 0;
    $percentage = ($count / count($waterLevelData)) * 100;
    $pdf->Cell(80, 8, ucfirst($label) . ':', 0, 0);
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 8, $count . ' (' . number_format($percentage, 1) . '%)', 0, 1);
    $pdf->SetFont('Arial', '', 12);
}

$pdf->Ln(5);

$pdf->SetFont('Arial', 'B', 13);
$pdf->SetFillColor(245, 245, 245);
$pdf->Cell(0, 10, 'Trend Distribution', 0, 1, 'L', true);

$pdf->SetFont('Arial', '', 12);
$pdf->Ln(2);
foreach (['steady' => 'Steady', 'rising' => 'Rising', 'falling' => 'Falling'] as $key => $label) {
    $count = isset($trendCounts[$key]) ? $trendCounts[$key] : 0;
    $percentage = ($count / count($waterLevelData)) * 100;
    $pdf->Cell(80, 8, $label . ':', 0, 0);
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 8, $count . ' (' . number_format($percentage, 1) . '%)', 0, 1);
    $pdf->SetFont('Arial', '', 12);
}

// ===== FOOTER =====
$pdf->SetY(-20);
$pdf->SetFont('Arial', '', 9);
$pdf->SetTextColor(150, 150, 150);
$pdf->Cell(0, 6, 'FloodGuard - Water Level Monitoring System', 0, 1, 'C');
$pdf->Cell(0, 6, 'Generated on ' . date('M d, Y H:i A'), 0, 1, 'C');

// Output PDF
$filename = 'Water_Level_' . str_replace(' ', '_', $bridge) . '_' . date('YmdHis') . '.pdf';
$pdf->Output('D', $filename);
exit();
?>
