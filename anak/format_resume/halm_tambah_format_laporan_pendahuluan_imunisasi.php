<?php
require_once "koneksi.php";
require_once "utils.php";

$form_id       = 6;
$section_name  = 'lp_imunisasi';
$section_label = 'Format Laporan Pendahuluan Imunisasi';
include dirname(__DIR__, 2) . '/partials/init_section.php';


// =============================================
// HANDLE POST - MAHASISWA SIMPAN DATA
// =============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $level === 'Mahasiswa') {

    if (isLocked($submission)) {
        redirectWithMessage($_SERVER['REQUEST_URI'], 'error', 'Data tidak dapat diubah karena sedang dalam proses review.');
    }

    $data = [
        'pengertian_imunisasi'  => $_POST['pengertian_imunisasi'] ?? '',
        'manfaat_imunisasi'     => $_POST['manfaat_imunisasi'] ?? '',
        'jenis_kekebalan_tubuh' => $_POST['jenis_kekebalan_tubuh'] ?? '',
        'jenis_imunisasi_dasar' => $_POST['jenis_imunisasi_dasar'] ?? '',
        'imunisasi_lanjutan'    => $_POST['imunisasi_lanjutan'] ?? '',
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
?>

<main id="main" class="main">

    <?php include "anak/format_resume/tab.php"; ?>

    <section class="section dashboard">

        <?php include dirname(__DIR__, 2) . '/partials/notifikasi.php'; ?>
        <?php include dirname(__DIR__, 2) . '/partials/status_section.php'; ?>

        <div class="card">
            <div class="card-body">

                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

                    <h5 class="card-title"><strong>FORMAT LAPORAN PENDAHULUAN IMUNISASI</strong></h5>
                    <div class="row mb-2">
                        <label class="col-sm-6 col-form-label text-primary">
                            <strong>Konsep Dasar Imunisasi (secara keseluruhan)</strong>
                    </div>

                    <!-- Bagian Pengertian -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>1. Pengertian Imnuniasi</strong></label>
                        <div class="col-sm-10">
                            <textarea name="pengertian_imunisasi" class="form-control"
                                rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('pengertian_imunisasi', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- 2 -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>2. Manfaat / Tujuan Imunisasi</strong></label>
                        <div class="col-sm-10">
                            <textarea name="manfaat_imunisasi" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('manfaat_imunisasi', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- 3 -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>3. Jenis-jenis Kekebalan Tubuh</strong></label>
                        <div class="col-sm-10">
                            <textarea name="jenis_kekebalan_tubuh" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('jenis_kekebalan_tubuh', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- 4 -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>4. Jenis-jenis Imunisasi Dasar</strong></label>
                        <div class="col-sm-10">
                            <small style="color:red;">
                                Penjelasan meliputi nama imunisasi, sasaran umur, dosis, cara pemberian, frekuensi pemberian, ,efek samping, penyakit yang dicegah dengan pemberian imunisasi (definisi, penyebab, tanda dan gejala)
                            </small>
                            <textarea name="jenis_imunisasi_dasar" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('jenis_imunisasi_dasar', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- 5 -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>5. Jenis-jenis Imunisasi Lanjutan</strong></label>
                        <div class="col-sm-10">
                            <small style="color:red;">
                                Penjelasan meliputi nama imunisasi, sasaran umur, dosis, cara pemberian, frekuensi pemberian, efek samping
                            </small>

                            <textarea name="imunisasi_lanjutan" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('imunisasi_lanjutan', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- TOMBOL MAHASISWA -->
                    <?php if (!$is_dosen): ?>
                        <div class="row mb-3">
                            <div class="col-sm-12 d-flex justify-content-end">
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