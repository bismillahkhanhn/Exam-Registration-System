<?php
require('config.php');
require('TCPDF-main/tcpdf.php');
requireAuth();

$student_id = $_SESSION['student_id'];

// Fetch student details
$stmt = $pdo->prepare("SELECT * FROM students WHERE id = ?");
$stmt->execute([$student_id]);
$student = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$student) {
    die("Student record not found");
}

// Fetch registered subjects
$stmt = $pdo->prepare("
    SELECT s.semester, s.subject_code, s.name AS subject_name, s.exam_date
    FROM registered_subjects rs
    JOIN subjects s ON rs.subject_id = s.id
    WHERE rs.student_id = ?");
$stmt->execute([$student_id]);
$subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Create PDF
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'A4', true, 'UTF-8', false);

// Document settings
$pdf->SetCreator('Exam Portal');
$pdf->SetAuthor('University System');
$pdf->SetTitle('Hall Ticket - ' . $student['first_name']);
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->SetMargins(15, 15, 15);
$pdf->SetAutoPageBreak(true, 15);
$pdf->AddPage();
$pdf->SetFont('helvetica', '', 10);

// Header with modern styling
$pdf->SetFillColor(63, 81, 181); // Dark blue
$pdf->SetTextColor(255);
$pdf->Cell(0, 15, 'EXAMINATION HALL TICKET', 0, 1, 'C', 1);
$pdf->Ln(5);

// University logo and info
$pdf->SetTextColor(100, 100, 100);
$pdf->SetFont('helvetica', 'B', 8);

$pdf->Ln(8);

// Student information section
$pdf->SetTextColor(0);
$full_name = $student['first_name'] . ' ' . $student['last_name'];
$birth_date = date("d-M-Y", strtotime($student['birth_date']));

// Image handling
$image_path = !empty($student['image_path']) ? $student['image_path'] : '';
$image_html = '';

if (!empty($image_path) && file_exists($image_path)) {
    $image_html = '<img src="' . $image_path . '" width="90" height="110" style="border:1px solid #ddd; object-fit:cover; border-radius:4px;">';
} else {
    $image_html = '<div style="width:90px; height:110px; border:1px solid #ddd; background:#f5f5f5; display:flex; align-items:center; justify-content:center; color:#999; border-radius:4px;">No Photo</div>';
}

$html = '<table cellpadding="5" border="0">
    <tr>
        <td width="65%" valign="top">
            <table cellpadding="4">
               
                <tr><td><strong>Name:</strong></td><td style="font-weight:bold;">' . $full_name . '</td></tr>
                <tr><td><strong>Date of Birth:</strong></td><td>' . $birth_date . '</td></tr>
                <tr><td><strong>Department:</strong></td><td>' . $student['department'] . '</td></tr>
                <tr><td><strong>Semester:</strong></td><td>' . $student['semester'] . '</td></tr>
            </table>
        </td>
        <td width="35%" align="right" valign="top">' . $image_html . '</td>
    </tr>
</table>';

$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Ln(12);

// Registered subjects section
$pdf->SetFont('helvetica', 'B', 11);
$pdf->SetFillColor(245, 245, 245);
$pdf->Cell(0, 8, 'REGISTERED EXAMINATION SUBJECTS', 0, 1, 'L', 1);
$pdf->Ln(3);

// Subject count
$pdf->SetFont('helvetica', 'I', 9);
$pdf->Cell(0, 6, 'Total Subjects: ' . count($subjects), 0, 1, 'R');
$pdf->Ln(2);

// Subjects Table (Corrected)
$table = '
<table border="1" cellpadding="5" cellspacing="0" style="border-collapse: collapse; width: 100%; font-size:9pt;">
    <thead>
        <tr style="background-color: #3f51b5; color: #ffffff;">
            <th style="width: 15%; text-align: center; font-weight: bold;">Semester</th>
            <th style="width: 20%; text-align: center; font-weight: bold;">Subject Code</th>
            <th style="width: 45%; text-align: center; font-weight: bold;">Subject Name</th>
            <th style="width: 20%; text-align: center; font-weight: bold;">Exam Date</th>
        </tr>
    </thead>
    <tbody>';

foreach ($subjects as $s) {
    $exam_date = !empty($s['exam_date']) ? date("d-M-Y", strtotime($s['exam_date'])) : 'TBD';
    $table .= '
        <tr>
            <td style="width: 15%; text-align: center; font-family: courier; font-weight: bold;">' . htmlspecialchars($s['semester']) . '</td>
            <td style="width: 20%; text-align: center;">' . htmlspecialchars($s['subject_code']) . '</td>
            <td style="width: 45%;">' . htmlspecialchars($s['subject_name']) . '</td>
            <td style="width: 20%; text-align: center;">' . $exam_date . '</td>
        </tr>';
}

$table .= '</tbody></table>';

$pdf->writeHTML($table, true, false, true, false, '');
$pdf->Ln(15);

// Instructions section
$pdf->SetFont('helvetica', 'B', 10);
$pdf->SetFillColor(245, 245, 245);
$pdf->Cell(0, 8, 'EXAMINATION GUIDELINES', 0, 1, 'L', 1);
$pdf->Ln(3);

$pdf->SetFont('helvetica', '', 9);
$instructions = '
<ul style="padding-left: 5px; margin-left:10px;">
    <li><strong>Mandatory:</strong> This hall ticket and a valid photo ID must be presented for admission to the examination hall.</li>
    <li><strong>Reporting Time:</strong> Candidates must report 30 minutes before the scheduled examination time.</li>
    <li><strong>Prohibited Items:</strong> Mobile phones, smart watches, and any electronic devices are strictly prohibited.</li>
    <li><strong>Dress Code:</strong> Maintain proper dress code - casual wear not permitted.</li>
    <li><strong>COVID Guidelines:</strong> Wear mask and maintain social distancing as per university guidelines.</li>
    <li><strong>Validity:</strong> This hall ticket is valid only for the exams mentioned above.</li>
</ul>';

$pdf->writeHTML($instructions, true, false, true, false, '');
$pdf->Ln(10);

// Footer note
$pdf->SetFont('helvetica', 'I', 8);
$pdf->SetTextColor(100, 100, 100);
$pdf->Cell(0, 5, 'Note: This is a computer generated document. No signature required.', 0, 1, 'C');
$pdf->Cell(0, 5, 'Generated on: ' . date('d-M-Y h:i A'), 0, 1, 'C');
$pdf->Cell(0, 5, '© ' . date('Y') . ' Examination System', 0, 1, 'C');

// Output the PDF to download
$pdf->Output('HallTicket_' . $student['usn'] . '.pdf', 'D');
?>
