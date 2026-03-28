<?php
require_once 'lib/fpdf/fpdf.php';

// Test PDF generation with image
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);
$pdf->Cell(40,10,'Test PDF with Image');

// Test if Image method exists
if (method_exists($pdf, 'Image')) {
    echo "Image method exists!\n";
} else {
    echo "Image method does not exist!\n";
}

echo "FPDF version: " . FPDF::VERSION . "\n";
echo "PDF generation test completed successfully!\n";
?>