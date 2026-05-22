<?php
$form_id       = 2;
$section_name  = 'data_biologis';
$section_label = 'Data Biologis';
include dirname(__DIR__, 2) . '/partials/init_section.php';

// =============================================
// HANDLE POST - MAHASISWA SIMPAN DATA
// =============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $level === 'Mahasiswa') {

    if (isLocked($submission)) {
        redirectWithMessage($_SERVER['REQUEST_URI'], 'error', 'Data tidak dapat diubah karena sedang dalam proses review.');
    }

    $data = [
        'biologis_fisiologis'       => $_POST['biologisfisiologis'] ?? '',
        'nh'                        => $_POST['nh'] ?? '',
        'p'                         => $_POST['p'] ?? '',
        'a'                         => $_POST['a'] ?? '',
        'bayi_rawat_gabung'         => $_POST['bayirawatgabung'] ?? '',
        'tidak_ada_alasan'          => $_POST['tidakadaalasan'] ?? '',
        'keadaan_umum'               => $_POST['keadaanumum'] ?? '',
        'kesadaran'                 => $_POST['kesadaran'] ?? '',
        'bb_tb'                     => $_POST['bbtb'] ?? '',
        'tekanan_darah'             => $_POST['tekanandarah'] ?? '',
        'nadi'                      => $_POST['nadi'] ?? '',
        'suhu'                      => $_POST['suhu'] ?? '',
        'pernapasan'                => $_POST['pernapasan'] ?? '',
    ];


    // print_r($data);
    // echo "<hr>";
    // echo "submission : ";
    // print_r($submission);
    // die;

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
    <?php include "tab.php"; ?>

    <section class="section dashboard">
        <?php include dirname(__DIR__, 2) . '/partials/notifikasi.php'; ?>
        <?php include dirname(__DIR__, 2) . '/partials/status_section.php'; ?>
        <div class="card">
            <div class="card">
                <div class="card-body">

                    <h5 class="card-title"><strong>DATA UMUM KESEHATAN SAAT INI</strong></h5>

                    <!-- General Form Elements -->
                    <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

                        <div class="row mb-3">
                            <label class="col-sm-9 col-form-label text-primary">
                                <strong>Data Biologis/Fisiologis</strong>
                            </label>
                        </div>

                        <!-- Bagian Data Biologis/Fisiologis -->
                        <div class="row mb-3">
                            <label for="biologisfisiologis" class="col-sm-2 col-form-label"><strong>Data Biologis/Fisiologis</strong></label>
                            <div class="col-sm-9">
                                <textarea name="biologisfisiologis" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                    <?= $ro ?>><?= val('biologis_fisiologis', $existing_data) ?></textarea>


                            </div>
                        </div>

                        <!-- Bagian Status Obstetrik -->

                        <div class="row mb-3">

                            <label class="col-sm-2 col-form-label"><strong>Status Obstetrik</strong></label>
                            <div class="col-sm-9">
                                <div class="row">

                                    <!-- NH -->
                                    <div class="col-md-4 d-flex align-items-center">
                                        <label class="me-2"><strong>NH</strong></label>
                                        <input type="text" class="form-control" name="nh"
                                            value="<?= val('nh', $existing_data) ?>" <?= $ro ?>>
                                    </div>

                                    <!-- p -->
                                    <div class="col-md-4 d-flex align-items-center">
                                        <label class="me-2"><strong>P</strong></label>
                                        <input type="text" class="form-control" name="p"
                                            value="<?= val('p', $existing_data) ?>" <?= $ro ?>>
                                    </div>

                                    <!-- A -->
                                    <div class="col-md-4 d-flex align-items-center">
                                        <label class="me-2"><strong>A</strong></label>
                                        <input type="text" class="form-control" name="a"
                                            value="<?= val('a', $existing_data) ?>" <?= $ro ?>>
                                    </div>
                                </div>

                                <!-- comment -->

                            </div>
                        </div>

                        <!-- Bagian Bayi Rawat Gabung -->
                        <div class="row mb-3">
                            <label for="bayirawatgabung" class="col-sm-2 col-form-label"><strong>Bayi Rawat Gabung</strong></label>
                            <div class="col-sm-9">
                                <select class="form-select" name="bayirawatgabung" <?= $ro_select ?>>
                                    <option value="">Pilih</option>
                                    <option value="ya" <?= val('bayi_rawat_gabung', $existing_data) === 'ya' ? 'selected' : '' ?>>Ya</option>
                                    <option value="Tidak" <?= val('bayi_rawat_gabung', $existing_data) === 'tidak' ? 'selected' : '' ?>>Tidak</option>
                                </select>

                            </div>

                        </div>

                        <!-- Bagian Jika Tidak Ada Alasan -->
                        <div class="row mb-3">
                            <label for="tidakadaalasan" class="col-sm-2 col-form-label"><strong>Jika Tidak Ada Alasan</strong></label>
                            <div class="col-sm-9">

                                <textarea name="tidakadaalasan" class="form-control" rows="5" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"

                                    <?= $ro ?>><?= val('tidak_ada_alasan', $existing_data) ?> </textarea>
                            </div>
                        </div>

                        <!-- Bagian Keadaan Umum -->
                        <div class="row mb-3">
                            <label for="keadaanumum" class="col-sm-2 col-form-label"><strong>Keadaan Umum</strong></label>
                            <div class="col-sm-9">

                                <textarea name="keadaanumum" class="form-control" rows="5" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                    <?= $ro ?>><?= val('keadaan_umum', $existing_data) ?> </textarea>


                            </div>
                        </div>

                        <!-- Bagian Kesadaran -->
                        <div class="row mb-3">
                            <label for="kesadaran" class="col-sm-2 col-form-label"><strong>Kesadaran</strong></label>
                            <div class="col-sm-9">
                                <textarea name="kesadaran" class="form-control" rows="5" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                    <?= $ro ?>><?= val('kesadaran', $existing_data) ?></textarea>

                            </div>
                        </div>

                        <!-- Bagian BB/TB -->
                        <div class="row mb-3">
                            <label for="bbtb" class="col-sm-2 col-form-label"><strong>BB/TB</strong></label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="bbtb" value="<?= val('bb_tb', $existing_data) ?>" <?= $ro ?>>
                                    <span class="input-group-text">kg/cm</span>


                                </div>
                            </div>

                            <!-- Bagian Tanda-tanda Vital -->

                            <div class="row mb-3">
                                <label class="col-sm-9 col-form-label">
                                    <strong>Tanda Vital</strong>
                                </label>
                            </div>

                            <!-- Tekanan Darah -->
                            <div class="row mb-3 align-items-center">
                                <label class="col-sm-2 col-form-label"><strong>Tekanan Darah</strong></label>
                                <div class="col-sm-3">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="tekanandarah" value="<?= val('tekanan_darah', $existing_data) ?>" <?= $ro ?>>
                                        <span class="input-group-text">mmHg</span>
                                    </div>
                                </div>

                                <!-- Nadi -->
                                <label class="col-sm-2 col-form-label"><strong>Nadi</strong></label>
                                <div class="col-sm-3">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="nadi" value="<?= val('nadi', $existing_data) ?>" <?= $ro ?>>
                                        <span class="input-group-text">x/I</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Suhu -->
                            <div class="row mb-3 align-items-center">
                                <label class="col-sm-2 col-form-label"><strong>Suhu</strong></label>
                                <div class="col-sm-3">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="suhu" value="<?= val('suhu', $existing_data) ?>" <?= $ro ?>>
                                        <span class="input-group-text">°C</span>
                                    </div>
                                </div>

                                <!-- Pernapasan -->
                                <label class="col-sm-2 col-form-label"><strong>Pernapasan</strong></label>
                                <div class="col-sm-3">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="pernapasan" value="<?= val('pernapasan', $existing_data) ?>" <?= $ro ?>>
                                        <span class="input-group-text">x/i</span>
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
                    </form>
                </div>
            </div>
        </div>
        </div>
            
        <?php include dirname(__DIR__, 2) . '/partials/footer_form.php'; ?>

    </section>
</main>