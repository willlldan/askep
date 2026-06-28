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


                      <div class="row mb-2">
    <label class="col-sm-12"><strong>1. Biodata Klien</strong></label>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Nama Anak</strong></label>
    <div class="col-sm-10">
        <textarea name="nama_anak" class="form-control" rows="1" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('nama_anak', $existing_data) ?></textarea>
    </div>
</div>

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

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Umur</strong></label>
    <div class="col-sm-10">
        <textarea name="umur" class="form-control" rows="1" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('umur', $existing_data) ?></textarea>
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Agama</strong></label>
    <div class="col-sm-10">
        <textarea name="agama" class="form-control" rows="1" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('agama', $existing_data) ?></textarea>
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Alamat</strong></label>
    <div class="col-sm-10">
        <textarea name="alamat" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('alamat', $existing_data) ?></textarea>
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Diagnosa Medis</strong></label>
    <div class="col-sm-10">
        <textarea name="diagnosa_medis" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('diagnosa_medis', $existing_data) ?></textarea>
    </div>
</div>

<div class="row mb-2">
    <label class="col-sm-12"><strong>2. Biodata Orangtua</strong></label>
</div>

<?php
// Array untuk mempermudah perulangan field orang tua agar kodenya lebih ringkas
$data_ortu = [
    ['label' => 'Nama Ayah', 'name' => 'nama_ayah'],
    ['label' => 'Umur Ayah', 'name' => 'umur_ayah'],
    ['label' => 'Pendidikan Ayah', 'name' => 'pendidikan_ayah'],
    ['label' => 'Pekerjaan Ayah', 'name' => 'pekerjaan_ayah'],
    ['label' => 'Nama Ibu', 'name' => 'nama_ibu'],
    ['label' => 'Umur Ibu', 'name' => 'umur_ibu'],
    ['label' => 'Pendidikan Ibu', 'name' => 'pendidikan_ibu'],
    ['label' => 'Pekerjaan Ibu', 'name' => 'pekerjaan_ibu'],
];

foreach ($data_ortu as $field): ?>
    <div class="row mb-3">
        <label class="col-sm-2 col-form-label"><strong><?= $field['label'] ?></strong></label>
        <div class="col-sm-10">
            <textarea name="<?= $field['name'] ?>" class="form-control" rows="1" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val($field['name'], $existing_data) ?></textarea>
        </div>
    </div>
<?php endforeach; ?>

<?php
$data_keluhan = [
    ['label' => '3. Keluhan Utama', 'name' => 'keluhan_utama'],
    ['label' => '4. Riwayat Keluhan Utama', 'name' => 'riwayat_keluhan_utama'],
    ['label' => '5. Keluhan yang Menyertai', 'name' => 'keluhan'],
    ['label' => '6. Riwayat Kesehatan yang Lalu', 'name' => 'riwayat_kesehatan_yang_lalu'],
];

foreach ($data_keluhan as $field): ?>
    <div class="row mb-3 align-items-start">
        <label class="col-sm-2 col-form-label"><strong><?= $field['label'] ?></strong></label>
        <div class="col-sm-10">
            <textarea name="<?= $field['name'] ?>" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val($field['name'], $existing_data) ?></textarea>
        </div>
    </div>
<?php endforeach; ?>

<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>7. Pemeriksaan Fisik</strong></label>
    <div class="col-sm-10">
        <small class="form-text text-danger">(secara umum dan singkat, berat badan, tinggi badan, status gizi anak)</small>
        <textarea name="pemeriksaan_fisik" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('pemeriksaan_fisik', $existing_data) ?></textarea>
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