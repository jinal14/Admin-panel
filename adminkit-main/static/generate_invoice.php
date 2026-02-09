<?php
require('fpdf.php');
include 'config.php';

/* ===============================
   VALIDATE PROJECT ID
================================ */
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die('Project ID missing');
}

$project_id = $_GET['id'];

/* ===============================
   FETCH PROJECT + CLIENT DATA
================================ */
$stmt = mysqli_prepare(
    $conn,
    "
    SELECT
        p.id,
        p.name AS project_name,
        p.description,
        p.status,
        p.currency,
        p.total_estimated_cost,
        p.created_at,

        u.name AS client_name,
        u.email AS client_email,
        u.phone AS client_phone
    FROM project p
    JOIN user u ON p.user_id = u.id
    WHERE p.id = ?
    "
);

mysqli_stmt_bind_param($stmt, "s", $project_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$data = mysqli_fetch_assoc($result);

if (!$data) {
    die('Project not found');
}

/* ===============================
   CREATE PDF
================================ */
$pdf = new FPDF();
$pdf->AddPage();

/* ---------- HEADER ---------- */
$pdf->SetFont('Arial','B',16);
$pdf->Cell(0,10,'INVOICE',0,1,'C');

$pdf->Ln(5);

/* ---------- COMPANY INFO ---------- */
$pdf->SetFont('Arial','',10);
$pdf->Cell(0,6,'VibeUp Interiors',0,1);
$pdf->Cell(0,6,'Email: support@vibeup.com',0,1);
$pdf->Cell(0,6,'Phone: +91 98765 43210',0,1);

$pdf->Ln(8);

/* ---------- CLIENT INFO ---------- */
$pdf->SetFont('Arial','B',12);
$pdf->Cell(0,8,'Bill To:',0,1);

$pdf->SetFont('Arial','',11);
$pdf->Cell(0,6,'Name: '.$data['client_name'],0,1);
$pdf->Cell(0,6,'Email: '.$data['client_email'],0,1);
$pdf->Cell(0,6,'Phone: '.$data['client_phone'],0,1);

$pdf->Ln(8);

/* ---------- PROJECT INFO ---------- */
$pdf->SetFont('Arial','B',12);
$pdf->Cell(0,8,'Project Details:',0,1);

$pdf->SetFont('Arial','',11);
$pdf->Cell(0,6,'Project Name: '.$data['project_name'],0,1);
$pdf->Cell(0,6,'Status: '.ucfirst(str_replace('_',' ',$data['status'])),0,1);
$pdf->Cell(0,6,'Created On: '.date('d M Y', strtotime($data['created_at'])),0,1);

$pdf->Ln(6);

/* ---------- DESCRIPTION ---------- */
$pdf->MultiCell(0,6,'Description: '.$data['description']);

$pdf->Ln(8);

/* ---------- COST ---------- */
$pdf->SetFont('Arial','B',12);
$pdf->Cell(140,8,'Total Amount',1,0);
$pdf->Cell(50,8,$data['currency'].' '.number_format($data['total_estimated_cost'],2),1,1,'R');

/* ---------- FOOTER ---------- */
$pdf->Ln(15);
$pdf->SetFont('Arial','I',9);
$pdf->Cell(0,6,'Thank you for choosing VibeUp Interiors!',0,1,'C');

/* ===============================
   OUTPUT PDF
================================ */
$pdf->Output('D', 'Invoice_'.$data['project_name'].'.pdf');
exit;
