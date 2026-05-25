<?php
$form_id       = 13;
$section_name  = 'data_umum';
$section_label = 'Data Umum';
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
        'alamat'                    => $_POST['keterangan'] ?? '',
        'diagnosa_medik'            => $_POST['diagnosamedik'] ?? '',
        'nama_suami'                => $_POST['namasuami'] ?? '',
        'usia_suami'                => $_POST['usiasuami'] ?? '',
        'pekerjaan_suami'           => $_POST['pekerjaansuami'] ?? '',
        'pendidikan_terakhir_suami' => $_POST['pendidikanterakhirsuami'] ?? '',
        'agama_suami'               => $_POST['agamasuami'] ?? '',
        'bbtb'                      => $_POST['bbtb'] ?? '',
        'bb_sebelum_hamil'          => $_POST['bbsebelumhamil'] ?? '',
        'lila'                      => $_POST['lila'] ?? '',
        'rencana_kehamilan'          => $_POST['rencanakehamilan'] ?? '',
        'g'                         => $_POST['g'] ?? '',
        'p'                         => $_POST['p'] ?? '',
        'a'                         => $_POST['a'] ?? '',
        'hpht'                      => $_POST['hpht'] ?? '',
        'tp'                        => $_POST['tp'] ?? '',
        'obat_dikonsumsi'            => $_POST['obatdikonsumsi'] ?? '',
        'alergi'                    => $_POST['alergi'] ?? '',
        'alat_bantu'                 => $_POST['alatbantu'] ?? '',
        'bak_terakhir'               => $_POST['bakterakhir'] ?? '',
        'bab_terakhir'               => $_POST['babterakhir'] ?? '',
        'siang'                     => $_POST['siang'] ?? '',
        'malam'                     => $_POST['malam'] ?? '',
        'riwayat'         => $_POST['riwayat'] ?? '',
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
    <?php include "maternitas/pengkajian_inranatal_care/tab.php"; ?>
    <section class="section dashboard">
        <?php include "partials/notifikasi.php"; ?>
        <?php include "partials/status_section.php"; ?>

        <!-- General Form Elements -->
        <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">
            <div class="card mt-3">
                <div class="card-body">
                    <form class="needs-validation" novalidate action="" method="POST">
                        <h5 class="card-title"><strong>DATA UMUM</strong></h5>

                        <!-- General Form Elements -->
                        <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

                            <!-- Bagian Inisial Pasien -->

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label"><strong>Inisial Pasien</strong></label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="inisialpasien"
                                        value="<?= val('inisial_pasien', $existing_data) ?>" <?= $ro ?>>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label"><strong>Usia</strong></label>
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

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label"><strong>Pendidikan Terakhir</strong></label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="pendidikanterakhiristri"
                                        value="<?= val('pendidikan_terakhir_istri', $existing_data) ?>" <?= $ro ?>>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label"><strong>Agama</strong></label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="agamaistri"
                                        value="<?= val('agama_istri', $existing_data) ?>" <?= $ro ?>>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label"><strong>Suku Bangsa</strong></label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="sukubangsa"
                                        value="<?= val('suku_bangsa', $existing_data) ?>" <?= $ro ?>>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label"><strong>Status Perkawinan</strong></label>
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

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label"><strong>Diagnosa Medik</strong></label>
                                <div class="col-sm-9">
                                    <textarea name="diagnosamedik" class="form-control" rows="5" <?= $ro ?>><?= val('diagnosa_medik', $existing_data) ?></textarea>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label"><strong>Nama Suami</strong></label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="namasuami"
                                        value="<?= val('nama_suami', $existing_data) ?>" <?= $ro ?>>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label"><strong>Usia Suami</strong></label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="usiasuami"
                                        value="<?= val('usia_suami', $existing_data) ?>" <?= $ro ?>>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label"><strong>Pekerjaan Suami</strong></label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="pekerjaansuami"
                                        value="<?= val('pekerjaan_suami', $existing_data) ?>" <?= $ro ?>>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label"><strong>Pendidikan Terakhir Suami</strong></label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="pendidikanterakhirsuami"
                                        value="<?= val('pendidikan_terakhir_suami', $existing_data) ?>" <?= $ro ?>>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label"><strong>Agama Suami</strong></label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="agamasuami"
                                        value="<?= val('agama_suami', $existing_data) ?>" <?= $ro ?>>
                                </div>
                            </div>

                            <h5 class="card-title"><strong>DATA UMUM KESEHATAN</strong></h5>

                            <!-- General Form Elements -->
                            <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

                                <!-- Bagian BB/TB -->
                                <div class="row mb-3">
                                    <label for="bbtb" class="col-sm-2 col-form-label"><strong>BB/TB</strong></label>
                                    <div class="col-sm-10">
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="bbtb" value="<?= val('bbtb', $existing_data) ?>" <?= $ro ?>>
                                            <span class="input-group-text">kg/cm</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Bagian BB Sebelum Hamil -->
                                <div class="row mb-3">
                                    <label for="bbtb" class="col-sm-2 col-form-label"><strong>BB Sebelum Hamil</strong></label>
                                    <div class="col-sm-10">
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="bbsebelumhamil" value="<?= val('bb_sebelum_hamil', $existing_data) ?>" <?= $ro ?>>
                                            <span class="input-group-text">kg</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Bagian LILA -->
                                <div class="row mb-3">
                                    <label for="bbtb" class="col-sm-2 col-form-label"><strong>LILA</strong></label>
                                    <div class="col-sm-10">
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="lila" value="<?= val('lila', $existing_data) ?>" <?= $ro ?>>
                                            <span class="input-group-text">cm</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Bagian Rencana Kehamilan -->
                                <div class="row mb-3">
                                    <label for="jeniskelamin" class="col-sm-2 col-form-label"><strong>Kehamilan Sekarang direncanakan</strong></label>
                                    <div class="col-sm-10">
                                        <select class="form-select" name="rencanakehamilan" <?= $ro_select ?>>
                                            <option value="">Pilih</option>
                                            <option value="Ya" <?= val('rencana_kehamilan', $existing_data) === 'Ya' ? 'selected' : '' ?>>Ya</option>
                                            <option value="Tidak" <?= val('rencana_kehamilan', $existing_data) === 'Tidak' ? 'selected' : '' ?>>Tidak</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Bagian Status Obstetrik -->

                                <div class="row mb-3">

                                    <label class="col-sm-2 col-form-label"><strong>Status Obstetrik</strong></label>
                                    <div class="col-sm-10">
                                        <div class="row">

                                            <!-- G -->
                                            <div class="col-md-4 d-flex align-items-center">
                                                <label class="me-2"><strong>G</strong></label>
                                                <input type="text" class="form-control" name="g" value="<?= val('g', $existing_data) ?>" <?= $ro ?>>
                                            </div>

                                            <!-- p -->
                                            <div class="col-md-4 d-flex align-items-center">
                                                <label class="me-2"><strong>P</strong></label>
                                                <input type="text" class="form-control" name="p" value="<?= val('p', $existing_data) ?>" <?= $ro ?>>
                                            </div>

                                            <!-- A -->
                                            <div class="col-md-4 d-flex align-items-center">
                                                <label class="me-2"><strong>A</strong></label>
                                                <input type="text" class="form-control" name="a" value="<?= val('a', $existing_data) ?>" <?= $ro ?>>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- HPHT -->
                                <div class="row mb-3 align-items-center">
                                    <label class="col-sm-2 col-form-label"><strong>HPHT</strong></label>
                                    <div class="col-sm-4">
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="hpht" value="<?= val('hpht', $existing_data) ?>" <?= $ro ?>>
                                        </div>
                                    </div>

                                    <!-- TP -->
                                    <label class="col-sm-2 col-form-label"><strong>TP</strong></label>
                                    <div class="col-sm-4">
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="tp" value="<?= val('tp', $existing_data) ?>" <?= $ro ?>>
                                        </div>
                                    </div>
                                </div>

                                <!-- Bagian Obat Dikonsumsi -->
                                <div class="row mb-3">
                                    <label for="obatdikonsumsi" class="col-sm-2 col-form-label"><strong>Obat-obatan yang dikonsumsi</strong></label>
                                    <div class="col-sm-10">
                                        <textarea name="obatdikonsumsi" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                            <?= $ro ?>><?= val('obat_dikonsumsi', $existing_data) ?></textarea></textarea>
                                    </div>
                                </div>

                                <!-- Bagian Alergi -->
                                <div class="row mb-3">
                                    <label for="alergi" class="col-sm-2 col-form-label"><strong>Apakah ada alergi terhadap sesuatu</strong></label>
                                    <div class="col-sm-10">
                                        <textarea name="alergi" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                            <?= $ro ?>><?= val('alergi', $existing_data) ?></textarea></textarea>
                                    </div>
                                </div>

                                <!-- Bagian Alat Bantu -->
                                <div class="row mb-3">
                                    <label for="alatbantu" class="col-sm-2 col-form-label"><strong>Alat Bantu yang Digunakan</strong></label>
                                    <div class="col-sm-10">
                                        <small class="form-text" style="color: red;">Gigi tiruan, kacamata/lensa kontak, alat dengar, dan lain-lain. Sebutkan</small>
                                        <textarea name="alatbantu" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                            <?= $ro ?>><?= val('alat_bantu', $existing_data) ?></textarea>
                                    </div>
                                </div>

                                <!-- Bagian BAK -->
                                <div class="row mb-3">
                                    <label for="bakterakhir" class="col-sm-2 col-form-label"><strong>BAK Terakhir</strong></label>
                                    <div class="col-sm-10">
                                        <small class="form-text" style="color: red;">Masalah:</small>
                                        <textarea name="bakterakhir" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                            <?= $ro ?>><?= val('bak_terakhir', $existing_data) ?></textarea>
                                    </div>
                                </div>

                                <!-- Bagian BAB Terakhir -->
                                <div class="row mb-3">
                                    <label for="babterakhir" class="col-sm-2 col-form-label"><strong>BAB Terakhir</strong></label>
                                    <div class="col-sm-10">
                                        <small class="form-text" style="color: red;">Masalah:</small>
                                        <textarea name="babterakhir" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                            <?= $ro ?>><?= val('bab_terakhir', $existing_data) ?></textarea>
                                    </div>
                                </div>

                                <!-- Bagian Kebiasaan Waktu Tidur -->

                                <!-- Bagian Kebiasaan Waktu Tidur -->
                                <div class="row mb-3">

                                    <label class="col-sm-2 col-form-label"><strong>Kebiasaan Waktu Tidur</strong></label>

                                    <!-- Siang -->
                                    <label class="col-sm-1 col-form-label"><strong>Siang</strong></label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" name="siang" value="<?= val('siang', $existing_data) ?>" <?= $ro ?>>
                                    </div>

                                    <!-- Malam -->
                                    <label class="col-sm-1 col-form-label"><strong>Malam</strong></label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" name="malam" value="<?= val('malam', $existing_data) ?>" <?= $ro ?>>
                                    </div>
                                </div>

                                <!-- Bagian Riwayat Kesehatan yang lalu -->
                                <div class="row mb-3">
                                    <label for="riwayatkesehatan" class="col-sm-2 col-form-label"><strong>Riwayat Kesehatan yang Lalu</strong></label>
                                    <div class="col-sm-10">
                                        <textarea name="riwayat" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                            <?= $ro ?>><?= val('riwayat', $existing_data) ?></textarea>
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

            <?php include "partials/footer_form.php"; ?>

    </section>
</main>