<?php
$form_id       = 9;
$section_name  = 'lp_ruanghd';
$section_label = 'Format Laporan Pendahuluan Ruang HD';
include dirname(__DIR__, 2) . '/partials/init_section.php';

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
        'definisi'                      => $_POST['definisi'] ?? '',
        'klasifikasi'                   => $_POST['klasifikasi'] ?? '',
        'etiologi'                      => $_POST['etiologi'] ?? '',
        'manifestasi_klinik'             => $_POST['manifestasiklinik'] ?? '',
        'patofisiologi'                 => $_POST['patofisiologi'] ?? '',
        'penunjang'                     => $_POST['penunjang'] ?? '',
        'penatalaksanaan'               => $_POST['penatalaksanaan'] ?? '',
        'komplikasi'                    => $_POST['komplikasi'] ?? '',
        'pengertian'                    => $_POST['pengertian'] ?? '',
        'tujuan'                        => $_POST['tujuan'] ?? '',
        'proses_hemodialisa'            => $_POST['proses_hemodialisa'] ?? '',
        'alasan_hemodialisa'            => $_POST['alasanhemodialisa'] ?? '',
        'indikasi_hemodialisa'          => $_POST['indikasihemodialisa'] ?? '',
        'kontraindikasi_hemodialisa'    => $_POST['kontraindikasihemodialisa'] ?? '',
        'frekuensi_hemodialisa'         => $_POST['frekuensihemodialisa'] ?? '',
        'komplikasi1'                    => $_POST['komplikasi1'] ?? '',
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
?>

<main id="main" class="main">
    <?php include "kmb/format_hd_kmb/tab.php"; ?>
    <section class="section dashboard">

        <?php include "partials/notifikasi.php"; ?>
        <?php include "partials/status_section.php"; ?>
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

                    <h5 class="card-title"><strong>A. Konsep Dasar Penyakit (Chronic Kidney Disease (CKD))</strong></h5>
                    <!-- A KONSEP DASAR MEDIS -->

                    <div class="row mb-3">
                        <div class="col-sm-2 col-form-label">
                            <strong>1. Definisi</strong>
                        </div>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="definisi" value="<?= val('definisi', $existing_data) ?>" <?= $ro ?>>
                        </div>

                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-2 col-form-label">
                            <strong>2. Klasifikasi</strong>
                        </div>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="klasifikasi" value="<?= val('klasifikasi', $existing_data) ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-2 col-form-label">
                            <strong>3. Etiologi</strong>
                        </div>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="etiologi" value="<?= val('etiologi', $existing_data) ?>" <?= $ro ?>>

                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-2 col-form-label">
                            <strong>4. Manifestasi Klinik</strong>
                        </div>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="manifestasiklinik" value="<?= val('manifestasi_klinik', $existing_data) ?>" <?= $ro ?>>

                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-2 col-form-label">
                            <strong>5. Patofisiologi</strong>
                        </div>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="patofisiologi" value="<?= val('patofisiologi', $existing_data) ?>" <?= $ro ?>>

                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-2 col-form-label">
                            <strong>6. Pemeriksaan penunjang</strong>
                        </div>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="penunjang" value="<?= val('penunjang', $existing_data) ?>" <?= $ro ?>>

                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-2 col-form-label">
                            <strong>7. Penatalaksanaan</strong>
                        </div>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="penatalaksanaan" value="<?= val('penatalaksanaan', $existing_data) ?>" <?= $ro ?>>

                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-2 col-form-label">
                            <strong>8. Komplikasi</strong>
                        </div>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="komplikasi" value="<?= val('komplikasi', $existing_data) ?>" <?= $ro ?>>

                        </div>
                    </div>

            </div>
        </div>
        </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="row mb-2">
                    <label class="col-sm-4 col-form-label text-primary">
                        <strong>B. Konsep Dasar Hemodialisa</strong>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-2 col-form-label">
                        <strong>1. Pengertian</strong>
                    </div>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="pengertian" value="<?= val('pengertian', $existing_data) ?>" <?= $ro ?>>

                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-2 col-form-label">
                        <strong>2. Tujuan</strong>
                    </div>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="tujuan" value="<?= val('tujuan', $existing_data) ?>" <?= $ro ?>>

                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-2 col-form-label">
                        <strong>3. Proses Hemodialisa</strong>
                    </div>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="proses_hemodialisa" value="<?= val('proses_hemodialisa', $existing_data) ?>" <?= $ro ?>>

                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-2 col-form-label">
                        <strong>4. Alasan dilakukan Hemodialisa</strong>
                    </div>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="alasanhemodialisa" value="<?= val('alasan_hemodialisa', $existing_data) ?>" <?= $ro ?>>

                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-2 col-form-label">
                        <strong>5. Indikasi Hemodialisa</strong>
                    </div>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="indikasihemodialisa" value="<?= val('indikasi_hemodialisa', $existing_data) ?>" <?= $ro ?>>

                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-2 col-form-label">
                        <strong>6. Kontraindikasi Hemodialisa</strong>
                    </div>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="kontraindikasihemodialisa" value="<?= val('kontraindikasi_hemodialisa', $existing_data) ?>" <?= $ro ?>>

                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-2 col-form-label">
                        <strong>7. Frekuensi Hemodialisa</strong>
                    </div>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="frekuensihemodialisa" value="<?= val('frekuensi_hemodialisa', $existing_data) ?>" <?= $ro ?>>

                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-2 col-form-label">
                        <strong>8. Komplikasi Hemodialisa</strong>
                    </div>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="komplikasi1" value="<?= val('komplikasi1', $existing_data) ?>" <?= $ro ?>>

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
        </div>

        <?php include dirname(__DIR__, 2) . '/partials/footer_form.php'; ?>

    </section>
</main>