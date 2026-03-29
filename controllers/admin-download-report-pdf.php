<?php
/**
 * Admin Download Report as PDF
 * Generates and downloads a flood report in PDF format for admin use
 */

session_start();

// Check if user is logged in (admin only)
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id']) || !isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    http_response_code(401);
    die('Unauthorized access.');
}

// Check if report ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    http_response_code(400);
    die('Report ID is required.');
}

// Load database and models
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/reports.php';
require_once __DIR__ . '/../lib/fpdf/fpdf.php';

$report_id = intval($_GET['id']);

// Get report details
$report = get_report_by_id($conn, $report_id);

// Check if report exists
if (!$report) {
    http_response_code(404);
    die('Report not found.');
}

// Helper function to format date
function formatDate($date) {
    return date('M d, Y h:i A', strtotime($date));
}

// Create PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetMargins(15, 15, 15);
$pdf->SetAutoPageBreak(true, 20);

// Header - FloodGuard Report with colored background
$pdf->SetFillColor(0, 102, 204); // blue
$pdf->SetTextColor(255, 255, 255);
$pdf->SetFont('Arial', 'B', 20);
$pdf->Cell(0, 18, 'FloodGuard Report', 0, 1, 'C', true);

// Spacing
$pdf->Ln(8);

// Report No. [Number] with grey background, left aligned with spacing
$pdf->SetFillColor(200, 200, 200); // grey
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(175, 12, 'Report No. ' . $report['id'], 0, 1, 'L', true);

// Spacing after grey background
$pdf->Ln(8);

// Details section
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(40, 8, 'Name:', 0, 0);
$pdf->SetFont('Arial', '', 12);
$name = trim($report['first_name'] . ' ' . $report['last_name']);
if (empty($name)) {
    $name = $report['user_email'];
}
$pdf->Cell(0, 8, htmlspecialchars($name), 0, 1);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(40, 8, 'Location:', 0, 0);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 8, htmlspecialchars($report['location']), 0, 1);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(40, 8, 'Status:', 0, 0);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 8, htmlspecialchars($report['status']), 0, 1);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(40, 8, 'Description:', 0, 0);
$pdf->SetFont('Arial', '', 12);
$description = trim($report['description'] ?? '');
if (empty($description)) {
    $description = 'No description provided';
}
$pdf->MultiCell(0, 8, htmlspecialchars($description));

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(40, 8, 'Date:', 0, 0);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 8, formatDate($report['created_at']), 0, 1);

// Image section
if (!empty($report['image'])) {
    $pdf->Ln(15);
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 8, 'Image:', 0, 1);

    // Save image to temporary file
    $temp_image_path = tempnam(sys_get_temp_dir(), 'report_image') . '.jpg';
    file_put_contents($temp_image_path, $report['image']);

    // Add image to PDF
    $pdf->Image($temp_image_path, 15, $pdf->GetY(), 120, 80, 'JPG');
    $pdf->SetY($pdf->GetY() + 85);

    // Clean up temp file
    unlink($temp_image_path);
}

// Footer
$pdf->Ln(20);
$pdf->SetFont('Arial', 'I', 8);
$pdf->SetTextColor(128, 128, 128);
$pdf->Cell(0, 10, 'Generated on ' . date('M d, Y h:i A') . ' | FloodGuard Admin System', 0, 0, 'C');

// Output PDF
$filename = 'FloodGuard_Report_' . $report['id'] . '_' . date('YmdHis') . '.pdf';
$pdf->Output('D', $filename);
exit();

?>