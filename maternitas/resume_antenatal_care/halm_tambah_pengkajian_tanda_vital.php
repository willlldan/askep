<?php
$form_id       = 3;
$section_name  = 'ttv_pemeriksaan_umum';
$section_label = 'TTV & Pemeriksaan Umum';
include dirname(__DIR__, 2) . '/partials/init_section.php';

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
        'tekanan_darah'    => $_POST['tekanandarah'] ?? '',
        'nadi'             => $_POST['nadi'] ?? '',
        'suhu'             => $_POST['suhu'] ?? '',
        'pernapasan'       => $_POST['pernapasan'] ?? '',
        'keluhan_utama'          => $_POST['keluhan_utama'] ?? '',
        'riwayat_keluhan_utama'   => $_POST['riwayat_keluhan_utama'] ?? '',
    ];

    if (!$submission) {
        $submission_id = createSubmission($user_id, $form_id, $tgl_pengkajian, $rs_ruangan, $mysqli);
    } else {
        $submission_id = $submission['id'];
    }


    saveSection($submission_id, $section_name, $section_label, $data, $mysqli);
    updateSubmissionStatus($submission_id, $form_id, $mysqli);
    redirectWithMessage($_SERVER['REQUEST_URI'], 'success', 'Data berhasil disimpan.');
}
?>

<main id="main" class="main">
    <?php include "maternitas/resume_antenatal_care/tab.php"; ?>

    <section class="section dashboard">
        <?php include dirname(__DIR__, 2) . '/partials/notifikasi.php'; ?>
        <?php include dirname(__DIR__, 2) . '/partials/status_section.php'; ?>
        <div class="card">
            <div class="card-body">

                <h5 class="card-title"> <strong>c. Tanda-tanda Vital</strong></h5>

                <!-- General Form Elements -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

                    <!-- Tekanan Darah -->
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-2 col-form-label"><strong>Tekanan Darah</strong></label>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="text" class="form-control" name="tekanandarah" value="<?= val('tekanan_darah', $existing_data) ?>" <?= $ro ?>>
                            </div>
                        </div>

                        <!-- Nadi -->
                        <label class="col-sm-2 col-form-label"><strong>Nadi</strong></label>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="text" class="form-control" name="nadi" value="<?= val('nadi', $existing_data) ?>" <?= $ro ?>>
                            </div>
                        </div>
                    </div>

                    <!-- Suhu -->
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-2 col-form-label"><strong>Suhu</strong></label>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="text" class="form-control" name="suhu" value="<?= val('suhu', $existing_data) ?>" <?= $ro ?>>
                            </div>
                        </div>

                        <!-- Pernapasan -->
                        <label class="col-sm-2 col-form-label"><strong>Pernapasan</strong></label>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="text" class="form-control" name="pernapasan" value="<?= val('pernapasan', $existing_data) ?>" <?= $ro ?>>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
            <div class="card">
                <div class="card-body">

                    <h5 class="card-title"> <strong>d. Pemeriksaan Umum</strong></h5>
                    <div class="row mb-3">
                        <label for="pemeriksaan" class="col-sm-2 col-form-label"><strong> Keluhan Utama</strong></label>
                        <div class="col-sm-9">
                            <textarea name="keluhan_utama" class="form-control" rows="5" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('keluhan_utama', $existing_data) ?></textarea>


                        </div>
                    </div>
                    <!-- Bagian Pemeriksaan -->
                    <div class="row mb-3">
                        <label for="pemeriksaan" class="col-sm-2 col-form-label"><strong>Riwayat Keluhan Utama</strong></label>
                        <div class="col-sm-9">
                            <textarea name="riwayat_keluhan_utama" class="form-control" rows="5" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('riwayat_keluhan_utama', $existing_data) ?></textarea>


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

        </div>

        </form>
        </div>
        </div>

        <?php include dirname(__DIR__, 2) . '/partials/footer_form.php'; ?>

    </section>
</main>