<?php
require_once __DIR__ . "/../koneksi.php";
require_once __DIR__ . "/../utils.php";
require_once __DIR__ . '/../libs/dompdf/autoload.inc.php';

use Dompdf\Dompdf;
use Dompdf\Options;


$submission_id = $_GET['submission_id'] ?? null;

if (!$submission_id) {
    die("Submission tidak ditemukan.");
}

// Ambil data submission
$stmt = $mysqli->prepare("
    SELECT s.*, u.nama as mahasiswa_name, u.npm, f.slug
    FROM submissions s
    JOIN tbl_user u ON s.user_id = u.id_user
    JOIN forms f ON s.form_id = f.id
    WHERE s.id = ?
");

$stmt->bind_param("i", $submission_id);
$stmt->execute();
$submission = $stmt->get_result()->fetch_assoc();
$form_name = $submission['slug'];

if (!$submission) {
    die("Submission tidak ditemukan.");
}

// Ambil semua section data
$sections = [];
$stmt = $mysqli->prepare("SELECT section_name, data FROM submission_sections WHERE submission_id = ?");
$stmt->bind_param("i", $submission_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $sections[$row['section_name']] = json_decode($row['data'], true);
}

// Helper
function p($val) {
    return htmlspecialchars($val ?? '-');
}

// Render HTML
ob_start();

switch ($form_name) {
    case 'pengkajian_antenatal_care':
        include 'template_pdf_anc.php';
        break;
    case 'pengkajian_pascapartum':
         include 'template_pdf_pascapartum.php';
         break;
    case 'pengkajian_ginekologi':
        include 'template_pdf_ginekologi.php'; // Template untuk ginekologi
        break;
    case 'pengkajian_inranatal_care':
        include 'template_pdf_inranatal_care.php'; // Template untuk inranatal
        break;
    case 'resume_antenatal_care':
        include 'template_pdf_resume_anc.php';
        break;
    default:
        include 'template_pdf_postpartum.php';
        break;
}

$html = ob_get_clean();

// Generate PDF
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', false);
$options->set('defaultFont', 'Arial');
// $options->set('margin_top', 20);
// $options->set('margin_bottom', 20);
// $options->set('margin_left', 20);
// $options->set('margin_right', 20);

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html, 'UTF-8');
$dompdf->setPaper('A4', 'portrait');
// Tambahin ini setelah setPaper
$dompdf->render();
$dompdf->stream('Pengkajian_ANC_' . $submission['npm'] . '.pdf', ['Attachment' => true]);
exit;