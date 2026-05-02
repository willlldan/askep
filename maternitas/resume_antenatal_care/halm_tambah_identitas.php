<?php
require_once "koneksi.php";
require_once "utils.php";

$form_id       = 4;
$level         = $_SESSION['level'];
$user_id       = $_SESSION['id_user'];
$section_name  = 'identitas';
$section_label = 'Identitas';

// =============================================
// DOSEN: ambil submission berdasarkan ?submission_id=
// MAHASISWA: ambil submission milik sendiri
// =============================================
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
$tgl_pengkajian = $submission['tanggal_pengkajian'] ?? '';
$rs_ruangan     = $submission['rs_ruangan'] ?? '';

// =============================================
// HANDLE POST - MAHASISWA SIMPAN DATA
// =============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $level === 'Mahasiswa') {

    if (isLocked($submission)) {
        redirectWithMessage($_SERVER['REQUEST_URI'], 'error', 'Data tidak dapat diubah karena sedang dalam proses review.');
    }

    $tgl_pengkajian = $_POST['tglpengkajian'] ?? '';
    $rs_ruangan     = $_POST['rsruangan'] ?? '';

    $data = [
        'inisial_pasien'            => $_POST['inisialpasien'] ?? '',
        'usia_istri'                => $_POST['usiaistri'] ?? '',
        'pekerjaan_istri'           => $_POST['pekerjaanistri'] ?? '',
        'pendidikan_terakhir_istri' => $_POST['pendidikanterakhiristri'] ?? '',
        'agama_istri'               => $_POST['agamaistri'] ?? '',
        'suku_bangsa'               => $_POST['sukubangsa'] ?? '',
        'status_perkawinan'         => $_POST['statusperkawinan'] ?? '',
        'alamat'                    => $_POST['keterangan'] ?? '',
    ];

    if (!$submission) {
        $submission_id = createSubmission($user_id, $form_id, $tgl_pengkajian, $rs_ruangan, $mysqli);
    } else {
        $submission_id = $submission['id'];
        updateSubmissionHeader($submission_id, $tgl_pengkajian, $rs_ruangan, $mysqli);
    }


    saveSection($submission_id, $section_name, $section_label, $data, $mysqli);
    updateSubmissionStatus($submission_id, $form_id, $mysqli);
    redirectWithMessage($_SERVER['REQUEST_URI'], 'success', 'Data berhasil disimpan.');
}

// =============================================
// HANDLE POST - DOSEN APPROVE / REVISI / KOMENTAR
// =============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $level === 'Dosen') {
    $submission_id = $submission['id'];
    $dosen_id      = $user_id;
    $action        = $_POST['action'] ?? '';
    $comment       = $_POST['comment'] ?? '';

    if ($action === 'approve') {
        updateSectionStatus($submission_id, $section_name, 'approved', $mysqli);
        if (!empty($comment)) {
            saveComment($submission_id, $section_name, $comment, $dosen_id, $mysqli);
        }
    } elseif ($action === 'revision') {
        if (empty($comment)) {
            redirectWithMessage($_SERVER['REQUEST_URI'], 'error', 'Komentar wajib diisi saat meminta revisi.');
        }
        updateSectionStatus($submission_id, $section_name, 'revision', $mysqli);
        saveComment($submission_id, $section_name, $comment, $dosen_id, $mysqli);
    }

    updateReviewer($submission_id, $dosen_id, $mysqli);
    updateSubmissionStatusByDosen($submission_id, $form_id, $mysqli);
    redirectWithMessage($_SERVER['REQUEST_URI'], 'success', 'Berhasil disimpan.');
}

// Load komentar section (untuk dosen & mahasiswa)
$comments = $submission ? getSectionComments($submission['id'], $section_name, $mysqli) : [];

// Readonly jika mahasiswa + locked, atau jika dosen
$is_dosen    = $level === 'Dosen';
$is_readonly = $is_dosen || isLocked($submission);
$ro          = $is_readonly ? 'readonly' : '';
$ro_select   = $is_readonly ? 'disabled' : '';
?>

<main id="main" class="main">
    <?php include "maternitas/resume_antenatal_care/tab.php"; ?>

    <section class="section dashboard">
        <?php include dirname(__DIR__, 2) . '/partials/notifikasi.php'; ?>
        <?php include dirname(__DIR__, 2) . '/partials/status_section.php'; ?>
        <div class="card">
            <div class="card-body">

                <!-- General Form Elements -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">
                    <div class="row mb-3 mt-3">
                        <label class="col-sm-2 col-form-label"><strong>Tanggal Pengkajian</strong></label>
                        <div class="col-sm-9">
                            <input type="date" class="form-control" name="tglpengkajian"
                                value="<?= htmlspecialchars($tgl_pengkajian) ?>" <?= $ro ?> required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>RS/Ruangan</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="rsruangan"
                                value="<?= htmlspecialchars($rs_ruangan) ?>" <?= $ro ?> required>
                        </div>
                    </div>

                    <h5 class="card-title"><strong>DATA UMUM</strong></h5>

                    <!-- Bagian Inisial Pasien -->

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Inisial Pasien</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="inisialpasien"
                                value="<?= val('inisial_pasien', $existing_data) ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <!-- Bagian Usia -->
                    <div class="row mb-3">
                        <label for="usiaistri" class="col-sm-2 col-form-label"><strong>Usia</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="usiaistri"
                                value="<?= val('usia_istri', $existing_data) ?>" <?= $ro ?>>


                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Pekerjaan</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="pekerjaanistri"
                                value="<?= val('pekerjaan_istri', $existing_data) ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <!-- Bagian Pendidikan Terakhir -->
                    <div class="row mb-3">
                        <label for="pendidikanterakhiristri" class="col-sm-2 col-form-label"><strong>Pendidikan Terakhir</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="pendidikanterakhiristri"
                                value="<?= val('pendidikan_terakhir_istri', $existing_data) ?>" <?= $ro ?>>



                        </div>
                    </div>

                    <!-- Bagian Agama -->
                    <div class="row mb-3">
                        <label for="agamaistri" class="col-sm-2 col-form-label"><strong>Agama</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="agamaistri"
                                value="<?= val('agama_istri', $existing_data) ?>" <?= $ro ?>>



                        </div>
                    </div>

                    <!-- Bagian Suku Bangsa -->
                    <div class="row mb-3">
                        <label for="sukubangsa" class="col-sm-2 col-form-label"><strong>Suku Bangsa</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="sukubangsa"
                                value="<?= val('suku_bangsa', $existing_data) ?>" <?= $ro ?>>



                        </div>
                    </div>

                    <!-- Bagian Status Perkawinan -->
                    <div class="row mb-3">
                        <label for="statusperkawinan" class="col-sm-2 col-form-label"><strong>Status Perkawinan</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="statusperkawinan"
                                value="<?= val('status_perkawinan', $existing_data) ?>" <?= $ro ?>>



                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Alamat</strong></label>
                        <div class="col-sm-9">
                            <textarea name="keterangan" class="form-control" rows="5" <?= $ro ?>><?= val('alamat', $existing_data) ?></textarea>
                        </div>
                    </div>
                    <!-- TOMBOL SUBMIT -->
                    <?php if (!$is_dosen): ?>
                        <div class="row mb-3">
                            <div class="col-sm-11 d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </div>
                    <?php endif; ?>
                </form>
            </div>
        </div>

        <?php include dirname(__DIR__, 2) . '/partials/footer_form.php'; ?>

    </section>
</main>