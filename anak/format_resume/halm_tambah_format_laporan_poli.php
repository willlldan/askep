<?php
require_once "koneksi.php";
require_once "utils.php";

$form_id       = 6;
$section_name  = 'poli_imunisasi';
$section_label = 'Format Laporan Poli Imunisasi';
include dirname(__DIR__, 2) . '/partials/init_section.php';

// =============================================
// HANDLE POST - MAHASISWA SIMPAN DATA
// =============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $level === 'Mahasiswa') {

    if (isLocked($submission)) {
        redirectWithMessage($_SERVER['REQUEST_URI'], 'error', 'Data tidak dapat diubah karena sedang dalam proses review.');
    }

    $data = [
        'nama_anak'                   => $_POST['nama_anak'] ?? '',
        'jenis_kelamin'               => $_POST['jenis_kelamin'] ?? '',
        'umur'                        => $_POST['umur'] ?? '',
        'agama'                       => $_POST['agama'] ?? '',
        'alamat'                      => $_POST['alamat'] ?? '',
        'nama_ayah'                   => $_POST['nama_ayah'] ?? '',
        'umur_ayah'                   => $_POST['umur_ayah'] ?? '',
        'pendidikan_ayah'             => $_POST['pendidikan_ayah'] ?? '',
        'pekerjaan_ayah'              => $_POST['pekerjaan_ayah'] ?? '',
        'nama_ibu'                    => $_POST['nama_ibu'] ?? '',
        'umur_ibu'                    => $_POST['umur_ibu'] ?? '',
        'pendidikan_ibu'              => $_POST['pendidikan_ibu'] ?? '',
        'pekerjaan_ibu'               => $_POST['pekerjaan_ibu'] ?? '',
        'imunisasi_saat_ini'          => $_POST['imunisasi_saat_ini'] ?? '',
        'dosis_pemberian'             => $_POST['dosis_pemberian'] ?? '',
        'cara_pemberian'              => $_POST['cara_pemberian'] ?? '',
        'reaksi_anak'                 => $_POST['reaksi_anak'] ?? '',
        'rencana_imunisasi'           => $_POST['rencana_imunisasi'] ?? '',
        'imunisasi_didapatkan'        => $_POST['imunisasi_didapatkan'] ?? '',
        'efek_dirumah'                => $_POST['efek_dirumah'] ?? '',
        'pemberian_imunisasi'         => $_POST['pemberian_imunisasi'] ?? '',
        'riwayat_penyakit'           => $_POST['riwayat_penyakit'] ?? '',
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

                    <h5 class="card-title"><strong>FORMAT LAPORAN POLI IMUNISASI</strong></h5>

                    <!-- 1. Biodata Klien -->

                    <form class="needs-validation" novalidate action="" method="POST">

                        <!-- 1. Biodata Klien -->
                        <div class="row mb-2">
                            <label class="col-sm-12 text-primary"><strong>1. Biodata Klien</strong></label>
                        </div>

                        <!-- Nama Anak -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Nama Anak</strong></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="nama_anak"
                                    value="<?= val('nama_anak', $existing_data) ?>" <?= $ro ?>>
                            </div>
                        </div>

                        <!-- Jenis Kelamin -->
                        <div class="row mb-3">
                            <label for="jenis_kelamin" class="col-sm-2 col-form-label"><strong>Jenis Kelamin</strong></label>

                            <div class="col-sm-10">
                                <select class="form-select" name="jenis_kelamin" <?= $ro_select ?>>
                                    <option value="">Pilih</option>
                                    <option value="Laki-laki" <?= val('jenis_kelamin', $existing_data) === 'Laki-laki' ? 'selected' : '' ?>>Laki-laki</option>
                                    <option value="Perempuan" <?= val('jenis_kelamin', $existing_data) === 'Perempuan' ? 'selected' : '' ?>>Perempuan</option>

                                </select>
                            </div>
                        </div>

                        <!-- Umur -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Umur</strong></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="umur"
                                    value="<?= val('umur', $existing_data) ?>" <?= $ro ?>>
                            </div>
                        </div>

                        <!-- Agama -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Agama</strong></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="agama"
                                    value="<?= val('agama', $existing_data) ?>" <?= $ro ?>>
                            </div>
                        </div>

                        <!-- Alamat -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Alamat</strong></label>
                            <div class="col-sm-10">
                                <textarea name="alamat" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                    <?= $ro ?>><?= val('alamat', $existing_data) ?></textarea>
                            </div>
                        </div>

                        <!-- 2. Biodata Orangtua -->
                        <div class="row mb-2">
                            <label class="col-sm-12 text-primary"><strong>2. Biodata Orangtua</strong></label>
                        </div>

                        <!-- Nama Ayah -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Nama Ayah</strong></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="nama_ayah"
                                    value="<?= val('nama_ayah', $existing_data) ?>" <?= $ro ?>>
                            </div>
                        </div>

                        <!-- Umur Ayah -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Umur Ayah</strong></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="umur_ayah"
                                    value="<?= val('umur_ayah', $existing_data) ?>" <?= $ro ?>>
                            </div>
                        </div>

                        <!-- Pendidikan Ayah -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Pendidikan Ayah</strong></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="pendidikan_ayah"
                                    value="<?= val('pendidikan_ayah', $existing_data) ?>" <?= $ro ?>>
                            </div>
                        </div>

                        <!-- Pekerjaan Ayah -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Pekerjaan Ayah</strong></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="pekerjaan_ayah"
                                    value="<?= val('pekerjaan_ayah', $existing_data) ?>" <?= $ro ?>>
                            </div>
                        </div>

                        <!-- Nama Ibu -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Nama Ibu</strong></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="nama_ibu"
                                    value="<?= val('nama_ibu', $existing_data) ?>" <?= $ro ?>>
                            </div>
                        </div>

                        <!-- Umur Ibu -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Umur Ibu</strong></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="umur_ibu"
                                    value="<?= val('umur_ibu', $existing_data) ?>" <?= $ro ?>>
                            </div>
                        </div>

                        <!-- Pendidikan Ibu -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Pendidikan Ibu</strong></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="pendidikan_ibu"
                                    value="<?= val('pendidikan_ibu', $existing_data) ?>" <?= $ro ?>>
                            </div>
                        </div>

                        <!-- Pekerjaan Ibu -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Pekerjaan Ibu</strong></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="pekerjaan_ibu"
                                    value="<?= val('pekerjaan_ibu', $existing_data) ?>" <?= $ro ?>>
                            </div>
                        </div>

                        <!-- B. Pemberian Imunisasi -->
                        <div class="row mb-2">
                            <label class="col-sm-12 text-primary"><strong>B. Pemberian Imunisasi</strong></label>
                        </div>

                        <!-- Imunisasi saat ini -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Imunisasi saat ini</strong></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="imunisasi_saat_ini"
                                    value="<?= val('imunisasi_saat_ini', $existing_data) ?>" <?= $ro ?>>
                            </div>
                        </div>

                        <!-- Dosis -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Dosis pemberian</strong></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="dosis_pemberian"
                                    value="<?= val('dosis_pemberian', $existing_data) ?>" <?= $ro ?>>
                            </div>
                        </div>

                        <!-- Cara -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Cara pemberian</strong></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="cara_pemberian"
                                    value="<?= val('cara_pemberian', $existing_data) ?>" <?= $ro ?>>
                            </div>
                        </div>

                        <!-- Reaksi anak -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Reaksi anak</strong></label>
                            <div class="col-sm-10">
                                <textarea name="reaksi_anak" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                    <?= $ro ?>><?= val('reaksi_anak', $existing_data) ?></textarea>
                            </div>
                        </div>

                        <!-- Rencana berikut -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Rencana imunisasi pada kunjungan berikutnya</strong></label>
                            <div class="col-sm-10">
                                <textarea name="rencana_imunisasi" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                    <?= $ro ?>><?= val('rencana_imunisasi', $existing_data) ?></textarea>
                            </div>
                        </div>

                        <!-- Riwayat imunisasi -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Imunisasi yang sudah didapatkan</strong></label>
                            <div class="col-sm-10">
                                <textarea name="imunisasi_didapatkan" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                    <?= $ro ?>><?= val('imunisasi_didapatkan', $existing_data) ?></textarea>
                            </div>
                        </div>

                        <!-- Efek di rumah -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Efek yang dirasakan anak di rumah setelah pemberian imunisasi</strong></label>
                            <div class="col-sm-10">
                                <textarea name="efek_dirumah" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                    <?= $ro ?>><?= val('efek_dirumah', $existing_data) ?></textarea>
                            </div>
                        </div>

                        <!-- Keluhan orang tua -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Hal yang dikeluhkan orang tua setelah pemberian imunisasi</strong></label>
                            <div class="col-sm-10">
                                <textarea name="pemberian_imunisasi" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                    <?= $ro ?>><?= val('pemberian_imunisasi', $existing_data) ?></textarea>
                            </div>
                        </div>

                        <!-- Riwayat penyakit -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Riwayat penyakit / pengobatan yang pernah didapatkan</strong></label>
                            <div class="col-sm-10">
                                <textarea name="riwayat_penyakit" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                    <?= $ro ?>><?= val('riwayat_penyakit', $existing_data) ?></textarea>
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

<script>
    const existingData = <?= json_encode($existing_data) ?>;
</script>