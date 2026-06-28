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

                    <div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>1. Biodata Klien</strong></label>
</div>

<?php
$data_klien = [
    ['label' => 'Nama Anak', 'name' => 'nama_anak'],
    ['label' => 'Umur', 'name' => 'umur'],
    ['label' => 'Agama', 'name' => 'agama'],
];

foreach ($data_klien as $field): ?>
    <div class="row mb-3">
        <label class="col-sm-2 col-form-label"><strong><?= $field['label'] ?></strong></label>
        <div class="col-sm-10">
            <textarea name="<?= $field['name'] ?>" class="form-control" rows="1" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val($field['name'], $existing_data) ?></textarea>
        </div>
    </div>
<?php endforeach; ?>

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
    <label class="col-sm-2 col-form-label"><strong>Alamat</strong></label>
    <div class="col-sm-10">
        <textarea name="alamat" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('alamat', $existing_data) ?></textarea>
    </div>
</div>

<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>2. Biodata Orangtua</strong></label>
</div>

<?php
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

<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>B. Pemberian Imunisasi</strong></label>
</div>

<?php
$data_imunisasi = [
    ['label' => 'Imunisasi saat ini', 'name' => 'imunisasi_saat_ini'],
    ['label' => 'Dosis pemberian', 'name' => 'dosis_pemberian'],
    ['label' => 'Cara pemberian', 'name' => 'cara_pemberian'],
    ['label' => 'Reaksi anak', 'name' => 'reaksi_anak'],
    ['label' => 'Rencana imunisasi pada kunjungan berikutnya', 'name' => 'rencana_imunisasi'],
    ['label' => 'Imunisasi yang sudah didapatkan', 'name' => 'imunisasi_didapatkan'],
    ['label' => 'Efek yang dirasakan anak di rumah setelah pemberian imunisasi', 'name' => 'efek_dirumah'],
    ['label' => 'Hal yang dikeluhkan orang tua setelah pemberian imunisasi', 'name' => 'pemberian_imunisasi'],
    ['label' => 'Riwayat penyakit / pengobatan yang pernah didapatkan', 'name' => 'riwayat_penyakit'],
];

foreach ($data_imunisasi as $field): ?>
    <div class="row mb-3">
        <label class="col-sm-2 col-form-label"><strong><?= $field['label'] ?></strong></label>
        <div class="col-sm-10">
            <textarea name="<?= $field['name'] ?>" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val($field['name'], $existing_data) ?></textarea>
        </div>
    </div>
<?php endforeach; ?>
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