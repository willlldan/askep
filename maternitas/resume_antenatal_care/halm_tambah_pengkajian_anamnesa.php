<?php
require_once "koneksi.php";
require_once "utils.php";

$form_id       = 4;
$level         = $_SESSION['level'];
$user_id       = $_SESSION['id_user'];
$section_name  = 'anamnesa_antropometri';
$section_label = 'Anamnesa Antropometri';

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

// =============================================
// HANDLE POST - MAHASISWA SIMPAN DATA
// =============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $level === 'Mahasiswa') {

    if (isLocked($submission)) {
        redirectWithMessage($_SERVER['REQUEST_URI'], 'error', 'Data tidak dapat diubah karena sedang dalam proses review.');
    }

    $data = [
        'hpht'                => $_POST['hpht'] ?? '',
        'g'                   => $_POST['g'] ?? '',
        'p'                   => $_POST['p'] ?? '',
        'a'                   => $_POST['a'] ?? '',
        'usia_kehamilan'      => $_POST['usiakehamilan'] ?? '',
        'tapsiran_partus'     => $_POST['tapsiranpartus'] ?? '',
        'riwayati_munisasi'   => $_POST['riwayatimunisasi'] ?? '',
        'riwayat_kehamilan'   => $_POST['riwayatkehamilan'] ?? '',
        'riwayat_penyakit'    => $_POST['riwayatpenyakit'] ?? '',
        'tb'                  => $_POST['tb'] ?? '',
        'bb'                  => $_POST['bb'] ?? '',
        'lila'                => $_POST['lila'] ?? '',
    ];

    if (!$submission) {
        $submission_id = createSubmission($user_id, $form_id, null, null, $mysqli);
    } else {
        $submission_id = $submission['id'];
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

                <h5 class="card-title mb-1"><strong>Pengkajian</strong></h5>
                <!-- General Form Elements -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

                    <!-- Bagian Kepala dan Rambut -->

                    <div class="row mb-2">
                        <label class="col-sm-2 col-form-label text-primary">
                            <strong>a. Anamnesa</strong>
                    </div>

                    <!-- HPHT -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>HPHT</strong></label>

                        <div class="col-sm-9">
                            <textarea name="hpht" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('hpht', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- Status Gravida -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Status Gravida</strong></label>
                    </div>

                    <!-- Bagian G -->

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>G</strong></label>

                        <div class="col-sm-9">
                            <textarea name="g" class="form-control" rows="2" <?= $ro ?>><?= val('g', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- Bagian P -->

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>P</strong></label>

                        <div class="col-sm-9">
                            <textarea name="p" class="form-control" rows="2" <?= $ro ?>><?= val('p', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- Bagian A -->

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>A</strong></label>

                        <div class="col-sm-9">
                            <textarea name="a" class="form-control" rows="2" <?= $ro ?>><?= val('a', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- Usia Kehamilan -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Usia Kehamilan</strong></label>

                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="usiakehamilan" value="<?= val('usia_kehamilan', $existing_data) ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <!-- Tapsiran Partus -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Tapsiran Partus</strong></label>

                        <div class="col-sm-9">
                            <textarea name="tapsiranpartus" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('tapsiran_partus', $existing_data) ?></textarea></textarea>
                        </div>
                    </div>

                    <!-- Riwayat Imunisasi TT (Saat Ini) -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Riwayat Imunisasi TT (Saat Ini)</strong></label>

                        <div class="col-sm-9">
                            <textarea name="riwayatimunisasi" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('riwayati_munisasi', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- Riwayat Kehamilan Saat Ini -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Riwayat Kehamilan Saat Ini</strong></label>

                        <div class="col-sm-9">
                            <textarea name="riwayatkehamilan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('riwayat_kehamilan', $existing_data) ?></textarea>

                        </div>
                    </div>

                    <!-- Riwayat Penyakit Ibu dan Keluarga -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Riwayat Penyakit Ibu dan Keluarga</strong></label>

                        <div class="col-sm-9">
                            <textarea name="riwayatpenyakit" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('riwayat_penyakit', $existing_data) ?></textarea>
                        </div>
                    </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">

                <div class="row mb-2">
                    <label class="col-sm-8 col-form-label text-primary">
                        <strong>b. Pemeriksaan Antropometri</strong>
                </div>

                <!-- TB -->
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><strong>TB</strong></label>

                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="tb" value="<?= val('tb', $existing_data) ?>" <?= $ro ?>>
                    </div>
                </div>

                <!-- BB -->
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><strong>BB</strong></label>

                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="bb" value="<?= val('bb', $existing_data) ?>" <?= $ro ?>>
                    </div>
                </div>

                <!-- LILA -->
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><strong>LILA</strong></label>

                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="lila" value="<?= val('lila', $existing_data) ?>" <?= $ro ?>>
                    </div>
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
        </div>

        </form>
        </div>
        </div>

        <?php include dirname(__DIR__, 2) . '/partials/footer_form.php'; ?>

    </section>
</main>