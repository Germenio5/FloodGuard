<?php
/**
 * Download Report as PDF
 * Generates and downloads a flood report in PDF format using FPDF library
 */

session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
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
$user_email = $_SESSION['user_email'] ?? '';

// Get report details
$report = get_report_by_id($conn, $report_id);

// Check if report exists
if (!$report) {
    http_response_code(404);
    die('Report not found.');
}

// Check if user has permission to download this report (must be the owner)
if (strtolower($report['user_email']) !== strtolower($user_email)) {
    http_response_code(403);
    die('You do not have permission to download this report.');
}

// Helper function to format date
function formatDate($date) {
    return date('M d, Y h:i A', strtotime($date));
}

// Create PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetMargins(15, 15, 15); // Set left, top, right margins
$pdf->SetAutoPageBreak(true, 20); // Auto page break with 20mm bottom margin
$pdf->SetFont('Arial', '', 12);

// Set colors
$pdf->SetFillColor(0, 102, 204); // Blue header
$pdf->SetTextColor(255, 255, 255); // White text

// Header
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 15, 'FloodGuard Report', 0, 1, 'C', true);

// Spacing after header
$pdf->Ln(5);

// Subtitle
$pdf->SetTextColor(0, 0, 0); // Back to black
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 8, 'Flood Report Summary', 0, 1);

// Spacing after subtitle
$pdf->Ln(3);

// Report Details Header with grey background
$pdf->SetFillColor(230, 230, 230);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(0, 7, 'Report Details', 0, 1, 'L', true);

// Spacing after Report Details header
$pdf->Ln(2);

// Report Details Section
$pdf->SetFont('Arial', '', 10);

// Report ID as a detail line
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(50, 6, 'Report ID:', 0, 0);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 6, '#' . $report['id'], 0, 1);

// Submitted By
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(50, 6, 'Submitted By:', 0, 0);
$pdf->SetFont('Arial', '', 10);
$submitted_by = trim($report['first_name'] . ' ' . $report['last_name']);
if (empty($submitted_by)) {
    $submitted_by = $report['user_email'];
}
$pdf->Cell(0, 6, htmlspecialchars($submitted_by), 0, 1);

// Contact Phone
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(50, 6, 'Contact Phone:', 0, 0);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 6, !empty($report['phone']) ? htmlspecialchars($report['phone']) : 'Not provided', 0, 1);

// Location
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(50, 6, 'Location:', 0, 0);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 6, htmlspecialchars($report['location']), 0, 1);

// Status
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(50, 6, 'Status:', 0, 0);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 6, htmlspecialchars($report['status']), 0, 1);

// Date Submitted
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(50, 6, 'Date Submitted:', 0, 0);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 6, formatDate($report['created_at']), 0, 1);

// Email
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(50, 6, 'Email:', 0, 0);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 6, htmlspecialchars($report['user_email']), 0, 1);

// Spacing before description section
$pdf->Ln(5);

// Description Section Header
$pdf->SetFont('Arial', 'B', 11);
$pdf->SetFillColor(230, 230, 230);
$pdf->Cell(0, 7, 'Description', 0, 1, 'L', true);

// Spacing before description content
$pdf->Ln(3);

// Description Content
$pdf->SetFont('Arial', '', 10);
if (!empty($report['description'])) {
    $pdf->MultiCell(0, 5, htmlspecialchars($report['description']));
} else {
    $pdf->Cell(0, 5, 'No description provided', 0, 1);
}

// Spacing before images section
if (!empty($report['image'])) {
    $pdf->Ln(5);
    
    // Report Image Section Header
    $pdf->SetFont('Arial', 'B', 11);
    $pdf->SetFillColor(230, 230, 230);
    $pdf->Cell(0, 7, 'Report Image', 0, 1, 'L', true);
    
    $pdf->Ln(3);
    
    // Save image to temporary file
    $temp_image_path = tempnam(sys_get_temp_dir(), 'report_image') . '.jpg';
    file_put_contents($temp_image_path, $report['image']);
    
    // Add image to PDF (smaller size for better layout)
    $pdf->Image($temp_image_path, 15, $pdf->GetY(), 120, 80, 'JPG');
    $pdf->SetY($pdf->GetY() + 85); // Position below the image
    
    // Clean up temp file
    unlink($temp_image_path);
}

// Spacing before admin response section
$pdf->Ln(5);

// Admin Response Section Header
$pdf->SetFont('Arial', 'B', 11);
$pdf->SetFillColor(230, 230, 230);
$pdf->Cell(0, 7, 'Admin Response', 0, 1, 'L', true);

$pdf->Ln(3);

// Admin Response Content
$pdf->SetFont('Arial', '', 10);
if (!empty($report['admin_response'])) {
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(50, 6, 'Response Status:', 0, 0);
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(0, 6, 'Responded', 0, 1);
    
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(50, 6, 'Response Date:', 0, 0);
    $pdf->SetFont('Arial', '', 10);
    $responseDate = !empty($report['admin_response_date']) ? formatDate($report['admin_response_date']) : 'Date not available';
    $pdf->Cell(0, 6, $responseDate, 0, 1);
    
    $pdf->Ln(3);
    $pdf->SetFont('Arial', '', 10);
    $pdf->MultiCell(0, 5, htmlspecialchars($report['admin_response']));
} else {
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(0, 10, 'Admin has not yet responded to this report.', 0, 1);
}

// Footer
$pdf->Ln(10);
$pdf->SetFont('Arial', 'I', 8);
$pdf->SetTextColor(128, 128, 128);
$pdf->Cell(0, 10, 'Generated on ' . date('M d, Y h:i A') . ' | FloodGuard System', 0, 0, 'C');

// Output PDF
$filename = 'Report_' . $report['id'] . '_' . date('YmdHis') . '.pdf';
$pdf->Output('D', $filename);
exit();

?>
