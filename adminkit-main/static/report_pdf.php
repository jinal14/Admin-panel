<?php
require('fpdf.php');
include 'config.php';

$client   = $_GET['client']   ?? '';
$designer = $_GET['designer'] ?? '';
$date     = $_GET['date']     ?? '';

$searchType = null;
if ($client !== '') $searchType = 'client';
elseif ($designer !== '') $searchType = 'designer';
elseif ($date !== '') $searchType = 'date';

$pdf = new FPDF('L','mm','A4');
$pdf->AddPage();

/* ================= HEADER ================= */
$pdf->SetFont('Arial','B',16);
$pdf->Cell(0,10,'VibeUp Interiors',0,1,'C');

$pdf->SetFont('Arial','',10);
$pdf->Cell(0,6,'Email: Harin14@vibeup.com | Phone: +91 98765 43210',0,1,'C');

$pdf->Ln(3);
$pdf->Cell(0,0,'','T',1); // line
$pdf->Ln(6);

/* ================= REPORT TITLE ================= */
$pdf->SetFont('Arial','B',14);

if ($searchType === 'client') {
    $pdf->Cell(0,8,'Client Report',0,1,'C');
}
elseif ($searchType === 'designer') {
    $pdf->Cell(0,8,'Designer Report',0,1,'C');
}
else {
    $pdf->Cell(0,8,'Project Report (By Date)',0,1,'C');
}

$pdf->Ln(3);

/* ================= DATE INFO ================= */
$pdf->SetFont('Arial','',10);
$pdf->Cell(0,6,'Generated On: '.date('d M Y, h:i A'),0,1,'R');
$pdf->Ln(4);

$pdf->SetFont('Arial','B',10);

/* ================= CLIENT REPORT ================= */
if ($searchType === 'client') {

    $stmt = $conn->prepare("
        SELECT
            u.name, u.phone, u.dob,
            du.name AS designer_name,
            p.total_estimated_cost,
            p.status, p.created_at
        FROM user u
        LEFT JOIN project p ON p.user_id = u.id
        LEFT JOIN user du ON p.designer_id = du.id
        WHERE u.name LIKE ?
    ");
    $like = "%$client%";
    $stmt->bind_param("s", $like);
    $stmt->execute();
    $res = $stmt->get_result();

    $headers = ['Client','Phone','DOB','Designer','Cost','Status','Date'];
    $widths  = [45,30,30,45,30,30,35];
}

/* ================= DESIGNER REPORT ================= */
elseif ($searchType === 'designer') {

    $stmt = $conn->prepare("
        SELECT
            du.name, du.phone,
            d.experience_years, d.rating,
            q.qualification_name,
            cu.name AS client_name,
            p.status, p.created_at
        FROM user du
        LEFT JOIN designer d ON du.id=d.user_id
        LEFT JOIN designer_qualification q ON d.id=q.designer_id
        LEFT JOIN project p ON p.designer_id=du.id
        LEFT JOIN user cu ON p.user_id=cu.id
        WHERE du.name LIKE ?
    ");
    $like = "%$designer%";
    $stmt->bind_param("s", $like);
    $stmt->execute();
    $res = $stmt->get_result();

    $headers = ['Designer','Phone','Exp','Rating','Qualification','Client','Status','Date'];
    $widths  = [40,30,20,20,40,40,30,35];
}

/* ================= DATE REPORT ================= */
elseif ($searchType === 'date') {

    $stmt = $conn->prepare("
        SELECT
            cu.name AS client,
            du.name AS designer,
            p.name AS project,
            p.status, p.total_estimated_cost, p.created_at
        FROM project p
        LEFT JOIN user cu ON p.user_id=cu.id
        LEFT JOIN user du ON p.designer_id=du.id
        WHERE DATE(p.created_at)=?
    ");
    $stmt->bind_param("s", $date);
    $stmt->execute();
    $res = $stmt->get_result();

    $headers = ['Client','Designer','Project','Status','Cost','Date'];
    $widths  = [45,45,50,30,30,40];
}

/* ================= TABLE HEADER ================= */
foreach ($headers as $i => $head) {
    $pdf->Cell($widths[$i],8,$head,1,0,'C');
}
$pdf->Ln();

/* ================= TABLE DATA ================= */
$pdf->SetFont('Arial','',9);

while ($row = $res->fetch_assoc()) {

    if ($searchType === 'client') {
        $pdf->Cell(45,8,$row['name'],1);
        $pdf->Cell(30,8,$row['phone'],1);
        $pdf->Cell(30,8,$row['dob'],1);
        $pdf->Cell(45,8,$row['designer_name'],1);
        $pdf->Cell(30,8,'₹'.$row['total_estimated_cost'],1);
        $pdf->Cell(30,8,$row['status'],1);
        $pdf->Cell(35,8,date('d M Y', strtotime($row['created_at'])),1);
    }

    elseif ($searchType === 'designer') {
        $pdf->Cell(40,8,$row['name'],1);
        $pdf->Cell(30,8,$row['phone'],1);
        $pdf->Cell(20,8,$row['experience_years'].' yrs',1);
        $pdf->Cell(20,8,$row['rating'],1);
        $pdf->Cell(40,8,$row['qualification_name'],1);
        $pdf->Cell(40,8,$row['client_name'],1);
        $pdf->Cell(30,8,$row['status'],1);
        $pdf->Cell(35,8,date('d M Y', strtotime($row['created_at'])),1);
    }

    elseif ($searchType === 'date') {
        $pdf->Cell(45,8,$row['client'],1);
        $pdf->Cell(45,8,$row['designer'],1);
        $pdf->Cell(50,8,$row['project'],1);
        $pdf->Cell(30,8,$row['status'],1);
        $pdf->Cell(30,8,'₹'.$row['total_estimated_cost'],1);
        $pdf->Cell(40,8,date('d M Y', strtotime($row['created_at'])),1);
    }

    $pdf->Ln();
}

/* ================= FOOTER ================= */
$pdf->Ln(8);
$pdf->SetFont('Arial','I',9);
$pdf->Cell(0,6,'This is a system generated report – VibeUp Interiors',0,1,'C');

/* ================= DOWNLOAD ================= */
$pdf->Output('D','VibeUp_Report_'.date('d-m-Y').'.pdf');