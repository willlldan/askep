<?php
$form_id       = 1;
$section_name  = 'data_demografi';
$section_label = 'Data Demografi';
include dirname(__DIR__, 2) . '/partials/init_section.php';
// =============================================
// HANDLE POST - MAHASISWA SIMPAN DATA
// =============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $level === 'Mahasiswa') {

    if (isLocked($submission)) {
        redirectWithMessage($_SERVER['REQUEST_URI'], 'error', 'Data tidak dapat diubah karena sedang dalam proses review.');
    }

    $data = [
        'inisial_pasien'            => $_POST['inisialpasien'] ?? '',
        'usia_istri'                => $_POST['usiaistri'] ?? '',
        'pekerjaan_istri'           => $_POST['pekerjaanistri'] ?? '',
        'pendidikan_terakhir_istri' => $_POST['pendidikanterakhiristri'] ?? '',
        'agama_istri'               => $_POST['agamaistri'] ?? '',
        'suku_bangsa'               => $_POST['sukubangsa'] ?? '',
        'status_perkawinan'         => $_POST['statusperkawinan'] ?? '',
        'alamat'                    => $_POST['alamat'] ?? '',
        'diagnosamedik'             => $_POST['diagnosamedik'] ?? '',
        'nama_suami'                => $_POST['namasuami'] ?? '',
        'usia_suami'                => $_POST['usiasuami'] ?? '',
        'pekerjaan_suami'           => $_POST['pekerjaansuami'] ?? '',
        'pendidikan_terakhir_suami' => $_POST['pendidikanterakhirsuami'] ?? '',
        'agama_suami'               => $_POST['agamasuami'] ?? '',
        'keluhanutama'              => $_POST['keluhanutama'] ?? '',
        'riwayatkeluhanutama'       => $_POST['riwayatkeluhanutama'] ?? '',
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

    <?php include "maternitas/pengkajian_antenatal_care/tab.php"; ?>

    <section class="section dashboard">
        <?php include "partials/notifikasi.php"; ?>
        <?php include "partials/status_section.php"; ?>
        <div class="card mt-3">
            <div class="card-body">
                <form class="needs-validation" novalidate action="" method="POST">

                    <h5 class="card-title"><strong>DATA DEMOGRAFI</strong></h5>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Inisial Pasien</strong></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="inisialpasien"
                                value="<?= val('inisial_pasien', $existing_data) ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Usia</strong></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="usiaistri"
                                value="<?= val('usia_istri', $existing_data) ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Pekerjaan</strong></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="pekerjaanistri"
                                value="<?= val('pekerjaan_istri', $existing_data) ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Pendidikan Terakhir</strong></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="pendidikanterakhiristri"
                                value="<?= val('pendidikan_terakhir_istri', $existing_data) ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Agama</strong></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="agamaistri"
                                value="<?= val('agama_istri', $existing_data) ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Suku Bangsa</strong></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="sukubangsa"
                                value="<?= val('suku_bangsa', $existing_data) ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Status Perkawinan</strong></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="statusperkawinan"
                                value="<?= val('status_perkawinan', $existing_data) ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Alamat</strong></label>
                        <div class="col-sm-10">
                        <textarea name="alamat" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('alamat',$existing_data) ?></textarea>    
                            </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Diagnosa Medik</strong></label>
                        <div class="col-sm-10">
                        <textarea name="diagnosamedik" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('diagnosamedik',$existing_data) ?></textarea>    
                            </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Nama Suami</strong></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="namasuami"
                                value="<?= val('nama_suami', $existing_data) ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Usia Suami</strong></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="usiasuami"
                                value="<?= val('usia_suami', $existing_data) ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Pekerjaan Suami</strong></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="pekerjaansuami"
                                value="<?= val('pekerjaan_suami', $existing_data) ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Pendidikan Terakhir Suami</strong></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="pendidikanterakhirsuami"
                                value="<?= val('pendidikan_terakhir_suami', $existing_data) ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Agama Suami</strong></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="agamasuami"
                                value="<?= val('agama_suami', $existing_data) ?>" <?= $ro ?>>
                        </div>
                    </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><strong>DATA BIOLOGIS / PSIKOLOGIS</strong></h5>

                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><strong>Keluhan Utama</strong></label>
                    <div class="col-sm-10">
                    <textarea name="keluhanutama" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('keluhanutama',$existing_data) ?></textarea>    
                        </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><strong>Riwayat Keluhan Utama</strong></label>
                    <div class="col-sm-10">
                    <textarea name="riwayatkeluhanutama" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('riwayatkeluhanutama',$existing_data) ?></textarea>    
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
        <?php include "partials/footer_form.php"; ?>
    </section>
</main>