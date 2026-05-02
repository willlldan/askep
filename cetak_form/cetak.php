<?php
require_once __DIR__ . "/../koneksi.php";
require_once __DIR__ . "/../utils.php";
require_once __DIR__ . '/../libs/dompdf/autoload.inc.php';

use Dompdf\Dompdf;
use Dompdf\Options;

set_time_limit(0);

$submission_id = $_GET['submission_id'] ?? null;

if (!$submission_id) {
    die("Submission tidak ditemukan.");
}

// Ambil data submission
$stmt = $mysqli->prepare("
    SELECT s.*, u.nama as mahasiswa_name, u.npm, f.slug, f.form_name
    FROM submissions s
    JOIN tbl_user u ON s.user_id = u.id_user
    JOIN forms f ON s.form_id = f.id
    WHERE s.id = ?
");

$stmt->bind_param("i", $submission_id);
$stmt->execute();
$submission = $stmt->get_result()->fetch_assoc();

if (!$submission) {
    die("Submission tidak ditemukan.");
}

$form_name = $submission['slug'];
$form_name_readable = strtolower(str_replace(' ', '_', $submission['form_name']));

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
    case 'pengkajian_ruang_ok':
        include 'template_pdf_ruang_ok.php';
        break;
    case 'pengkajian_ginekologi':
        include 'template_pdf_ginekologi.php';
        break;
    case 'pengkajian_inranatal_care':
        include 'template_pdf_inranatal_care.php';
        break;
    case 'poli_jiwa':
        include 'template_pdf_poli_jiwa.php';
        break;
    case 'format_anggrek':
        include 'template_pdf_format_anggrek.php';
        break;
    case 'jiwa_rsud':
        include 'template_pdf_jiwa_rsud.php';
        break;
    case 'format_hd_kmb':
        include 'template_pdf_hd_kmb.php';
        break;
    case 'format_ressume':
        include 'template_pdf_format_resume.php';
        break;
    case 'resume_antenatal_care':
        include 'template_pdf_resume_anc.php';
        break;
    case 'format_kmb':
        include 'template_pdf_kmb.php';
        break;
    case 'format_aster':
        include 'template_pdf_format_aster.php';
        break;
    default:
        include 'template_pdf_format_resume.php';
        break;
}

$html = ob_get_clean();

// Generate PDF
$options = new Options();
$options->set('isHtml5ParserEnabled', false);
$options->set('isRemoteEnabled', true);
$options->set('defaultFont', 'Arial');
$options->set('chunkSize', 512);

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html, 'UTF-8');
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream($form_name_readable . '_' . $submission['npm'] . '.pdf', ['Attachment' => true]);
exit;