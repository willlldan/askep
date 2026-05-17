<?php

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

if ($level === 'Dosen') {
    $submission_id_param = $_GET['submission_id'] ?? null;
    if (!$submission_id_param) {
        echo "<div class='alert alert-danger'>Submission tidak ditemukan.</div>";
        exit;
    }
    $stmt = $mysqli->prepare("
        SELECT s.*, r.nama as dosen_name
        FROM submissions s
        LEFT JOIN tbl_user r ON s.reviewed_by = r.id_user
        WHERE s.id = ?
    ");
    $stmt->bind_param("i", $submission_id_param);
    $stmt->execute();
    $submission = $stmt->get_result()->fetch_assoc();
} else {
    $submission = getSubmission($user_id, $form_id, $mysqli);
}

$existing_data  = $submission ? getSectionData($submission['id'], $section_name, $mysqli) : [];
$section_status = $submission ? getSectionStatus($submission['id'], $section_name, $mysqli) : null;

// Komentar section
$comments = $submission ? getSectionComments($submission['id'], $section_name, $mysqli) : [];

// Inisialisasi role & readonly

$is_dosen    = $level === 'Dosen';
$is_readonly = $is_dosen || isLocked($submission);
$ro          = $is_readonly ? 'readonly' : '';
$ro_select   = $is_readonly ? 'disabled' : '';
$ro_disabled = $is_readonly ? 'disabled' : '';

// =============================================
// HANDLE POST - DOSEN (DRY, otomatis di semua section)
// =============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $level === 'Dosen') {
    $submission_id = $submission['id'];
    $dosen_id      = $user_id;
    $action        = $_POST['action'] ?? '';
    $comment       = $_POST['comment'] ?? '';

    if (function_exists('handle_dosen_action')) {
        $err = handle_dosen_action($submission_id, $section_name, $action, $comment, $dosen_id, $mysqli);
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
        updateReviewer($submission_id, $dosen_id, $mysqli);
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

    $can_submit = $total_filled >= $count_section;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'submit_to_dosen' && $level === 'Mahasiswa') {
    $result = submitSubmission($submission['id'], $mysqli);
    if ($result['success']) {
        redirectWithMessage($_SERVER['REQUEST_URI'], 'success', 'Data berhasil disubmit ke dosen!');
    } else {
        redirectWithMessage($_SERVER['REQUEST_URI'], 'error', $result['message']);
    }
}
