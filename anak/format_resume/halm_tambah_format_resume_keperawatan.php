<?php
require_once "koneksi.php";
require_once "utils.php";

$form_id       = 6;
$section_name  = 'resume_keperawatan';
$section_label = 'Format Resume Keperawatan Poli Anak';
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
        'nama_anak'                   => $_POST['nama_anak'] ?? '',
        'jenis_kelamin'               => $_POST['jenis_kelamin'] ?? '',
        'umur'                        => $_POST['umur'] ?? '',
        'agama'                       => $_POST['agama'] ?? '',
        'alamat'                      => $_POST['alamat'] ?? '',
        'diagnosa_medis'              => $_POST['diagnosa_medis'] ?? '',
        'nama_ayah'                   => $_POST['nama_ayah'] ?? '',
        'umur_ayah'                   => $_POST['umur_ayah'] ?? '',
        'pendidikan_ayah'             => $_POST['pendidikan_ayah'] ?? '',
        'pekerjaan_ayah'              => $_POST['pekerjaan_ayah'] ?? '',
        'nama_ibu'                    => $_POST['nama_ibu'] ?? '',
        'umur_ibu'                    => $_POST['umur_ibu'] ?? '',
        'pendidikan_ibu'              => $_POST['pendidikan_ibu'] ?? '',
        'pekerjaan_ibu'               => $_POST['pekerjaan_ibu'] ?? '',
        'keluhan_utama'               => $_POST['keluhan_utama'] ?? '',
        'riwayat_keluhan_utama'       => $_POST['riwayat_keluhan_utama'] ?? '',
        'keluhan'                     => $_POST['keluhan'] ?? '',
        'riwayat_kesehatan_yang_lalu' => $_POST['riwayat_kesehatan_yang_lalu'] ?? '',
        'pemeriksaan_fisik'           => $_POST['pemeriksaan_fisik'] ?? '',
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

    <?php include "anak/format_resume/tab.php"; ?>

    <section class="section dashboard">

        <?php include dirname(__DIR__, 2) . '/partials/notifikasi.php'; ?>
        <?php include dirname(__DIR__, 2) . '/partials/status_section.php'; ?>
        <div class="card mt-3">
            <div class="card-body">
                <form class="needs-validation" novalidate action="" method="POST">

                    <div class="row mb-3 mt-3">
                        <label class="col-sm-2 col-form-label"><strong>Tanggal Pengkajian</strong></label>
                        <div class="col-sm-10">
                            <input type="date" class="form-control" name="tglpengkajian"
                                value="<?= htmlspecialchars($tgl_pengkajian) ?>" <?= $ro ?> required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>RS/Ruangan</strong></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="rsruangan"
                                value="<?= htmlspecialchars($rs_ruangan) ?>" <?= $ro ?> required>
                        </div>
                    </div>


                    <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

                        <h5 class="card-title"><strong>FORMAT RESUME KEPERAWATAN POLI ANAK</strong></h5>


                        <!-- 1. Biodata Klien -->
                        <div class="row mb-2">
                            <label class="col-sm-12"><strong>1. Biodata Klien</strong></label>
                        </div>

                        <!-- Nama Anak -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Nama Anak</strong></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="nama_anak"
                                    value="<?= val('nama_anak', $existing_data) ?>" <?= $ro ?>>
                            </div>
                        </div>

                        <!-- JENIS KELAMIN -->
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
                            <div class="col-sm-1 d-flex align-items-start">
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

                        <!-- Diagnosa Medis -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Diagnosa Medis</strong></label>
                            <div class="col-sm-10">
                                <textarea name="diagnosa_medis" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                    <?= $ro ?>><?= val('diagnosa_medis', $existing_data) ?></textarea>
                            </div>
                        </div>

                        <!-- 2. Biodata Orangtua -->
                        <div class="row mb-2">
                            <label class="col-sm-12"><strong>2. Biodata Orangtua</strong></label>
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

                        <!-- 3. Keluhan Utama -->
                        <div class="row mb-3 align-items-start">
                            <label class="col-sm-2 col-form-label"><strong>3. Keluhan Utama</strong></label>
                            <div class="col-sm-10">
                                <textarea name="keluhan_utama" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                    <?= $ro ?>><?= val('keluhan_utama', $existing_data) ?></textarea>
                            </div>
                        </div>

                        <!-- 4. Riwayat Keluhan Utama -->
                        <div class="row mb-3 align-items-start">
                            <label class="col-sm-2 col-form-label"><strong>4. Riwayat Keluhan Utama</strong></label>
                            <div class="col-sm-10">
                                <textarea name="riwayat_keluhan_utama" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                    <?= $ro ?>><?= val('riwayat_keluhan_utama', $existing_data) ?></textarea>
                            </div>
                        </div>

                        <!-- 5. Keluhan yang Menyertai -->
                        <div class="row mb-3 align-items-start">
                            <label class="col-sm-2 col-form-label"><strong>5. Keluhan yang Menyertai</strong></label>
                            <div class="col-sm-10">
                                <textarea name="keluhan" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                    <?= $ro ?>><?= val('keluhan', $existing_data) ?></textarea>
                            </div>
                        </div>

                        <!-- 6. Riwayat Kesehatan yang Lalu -->
                        <div class="row mb-3 align-items-start">
                            <label class="col-sm-2 col-form-label"><strong>6. Riwayat Kesehatan yang Lalu</strong></label>
                            <div class="col-sm-10">
                                <textarea name="riwayat_kesehatan_yang_lalu" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                    <?= $ro ?>><?= val('riwayat_kesehatan_yang_lalu', $existing_data) ?></textarea>
                            </div>
                        </div>

                        <!-- 7. Pemeriksaan Fisik -->
                        <div class="row mb-3 align-items-start">
                            <label class="col-sm-2 col-form-label"><strong>7. Pemeriksaan Fisik</strong></label>
                            <div class="col-sm-10">
                                <small class="form-text text-danger">(secara umum dan singkat, berat badan, tinggi badan, status gizi anak)</small>
                                <textarea name="pemeriksaan_fisik" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                    <?= $ro ?>><?= val('pemeriksaan_fisik', $existing_data) ?></textarea>
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