<?php


// PENTING: Semua partial, helper, dan koneksi WAJIB pakai require_once!
// Jangan pernah pakai include/include_once untuk koneksi.php atau partial DB
// Dilarang keras membuat koneksi baru di file ini atau file lain selain koneksi.php
require_once __DIR__ . "/../koneksi.php";
require_once __DIR__ . "/../utils.php";
require_once dirname(__DIR__) . "/utils/form_helpers.php";

// partials/init_section.php
// Usage: sebelum include, set $form_id, $section_name, $section_label
if (!isset($form_id, $section_name, $section_label, $mysqli, $_SESSION['level'], $_SESSION['id_user'])) {
    throw new Exception('init_section.php: Missing required variables.');
}
$level   = $_SESSION['level'];
$user_id = $_SESSION['id_user'];

if (in_array($level, ['Dosen', 'Preceptor'], true)) {
    $submission_id_param = $_GET['submission_id'] ?? null;
    if (!$submission_id_param) {
        echo "<div class='alert alert-danger'>Submission tidak ditemukan.</div>";
        exit;
    }
    $stmt = $mysqli->prepare("
        SELECT s.*, rd.nama as dosen_name, rp.nama as preceptor_name
        FROM submissions s
        LEFT JOIN tbl_user rd ON s.dosen_reviewed_by = rd.id_user
        LEFT JOIN tbl_user rp ON s.preceptor_reviewed_by = rp.id_user
        WHERE s.id = ?
    ");
    $stmt->bind_param("i", $submission_id_param);
    $stmt->execute();
    $submission = $stmt->get_result()->fetch_assoc();
} else {
    $submission = getSubmission($user_id, $form_id, $mysqli);
}

$existing_data  = $submission ? getSectionData($submission['id'], $section_name, $mysqli) : [];
$section_review_state = $submission ? getSectionReviewState($submission['id'], $section_name, $mysqli) : null;
$section_status = $section_review_state['status'] ?? ($submission ? getSectionStatus($submission['id'], $section_name, $mysqli) : null);
$section_dosen_status = $section_review_state['dosen_review_status'] ?? null;
$section_preceptor_status = $section_review_state['preceptor_review_status'] ?? null;

// Komentar section
$comments = $submission ? getSectionComments($submission['id'], $section_name, $mysqli) : [];

// Inisialisasi role & readonly

$is_dosen    = in_array($level, ['Dosen', 'Preceptor'], true);
$is_readonly = $is_dosen || isLocked($submission);
$ro          = $is_readonly ? 'readonly' : '';
$ro_select   = $is_readonly ? 'disabled' : '';
$ro_disabled = $is_readonly ? 'disabled' : '';
$ro_check    = $is_readonly ? 'disabled' : '';

// =============================================
// HANDLE POST - DOSEN (DRY, otomatis di semua section)
// =============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && in_array($level, ['Dosen', 'Preceptor'], true)) {
    $submission_id = $submission['id'];
    $dosen_id      = $user_id;
    $action        = $_POST['action'] ?? '';
    $comment       = $_POST['comment'] ?? '';
    

    if (function_exists('handle_dosen_action')) {
        $err = handle_dosen_action($submission_id, $section_name, $action, $comment, $dosen_id, $level, $mysqli);
        if (isset($err['error'])) {
            redirectWithMessage($_SERVER['REQUEST_URI'], 'error', $err['error']);
        }
    } else {
        // fallback legacy logic
        if ($action === 'approve') {
            updateSectionStatus($submission_id, $section_name, 'approved', $mysqli);
            if (!empty($comment)) saveComment($submission_id, $section_name, $comment, $dosen_id, $mysqli);
        } elseif ($action === 'revision') {
            if (empty($comment)) redirectWithMessage($_SERVER['REQUEST_URI'], 'error', 'Komentar wajib diisi saat meminta revisi.');
            updateSectionStatus($submission_id, $section_name, 'revision', $mysqli);
            saveComment($submission_id, $section_name, $comment, $dosen_id, $mysqli);
        }
        updateReviewer($submission_id, $dosen_id, $mysqli, $level, $action);
    }
    updateSubmissionStatusByDosen($submission_id, $form_id, $mysqli);
    redirectWithMessage($_SERVER['REQUEST_URI'], 'success', 'Berhasil disimpan.');
}

//Helper radio button dan checkbox

function ed($key, $data)
{
    return htmlspecialchars($data[$key] ?? '');
}

// Helper: render radio row untuk tabel APGAR/Ballard
function radioVal($field, $val, $existing, $disabled)
{
    $checked = (isset($existing[$field]) && (string)$existing[$field] === (string)$val) ? 'checked' : '';
    return "<input type='radio' name='{$field}' value='{$val}' {$checked} {$disabled}>";
}


// Helper cek bisa submit atau belum

$can_submit = false;
if ($submission && !$is_dosen && $submission['status'] === 'draft') {

    // Ambil count_section dari forms
    $stmt = $mysqli->prepare("SELECT count_section FROM forms WHERE id = ?");
    $stmt->bind_param("i", $form_id);
    $stmt->execute();
    $count_section = $stmt->get_result()->fetch_assoc()['count_section'];

    // Ambil total section yang sudah diisi
    $stmt = $mysqli->prepare("SELECT COUNT(*) as filled FROM submission_sections WHERE submission_id = ?");
    $stmt->bind_param("i", $submission['id']);
    $stmt->execute();
    $total_filled = $stmt->get_result()->fetch_assoc()['filled'];

    $can_submit = $total_filled >= 1;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'submit_to_dosen' && $level === 'Mahasiswa') {
    $result = submitSubmission($submission['id'], $mysqli);
    if ($result['success']) {
        redirectWithMessage($_SERVER['REQUEST_URI'], 'success', 'Data berhasil disubmit ke dosen!');
    } else {
        redirectWithMessage($_SERVER['REQUEST_URI'], 'error', $result['message']);
    }
}
