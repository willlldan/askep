<?php
$form_id       = 18;
$section_name  = 'psikososial_spiritual';
$section_label = 'Psikososial & Spiritual';
include dirname(__DIR__) . '/partials/init_section.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $level === 'Mahasiswa') {
    if (isLocked($submission)) redirectWithMessage($_SERVER['REQUEST_URI'], 'error', 'Data tidak dapat diubah karena sedang dalam proses review.');

    $data = [];
    foreach ([
        'kondisi_lansia', 'penyesuaian_lansia', 'prolanis_lansia', 'periksa_kesehatan_lansia', 'posyandu_lansia', 'kegiatan_rt_lansia',
        'dukungan_gerontik', 'ingatkan_pantangan', 'senang_berkumpul', 'rutin_ibadah', 'bersyukur', 'berkembang_usia'
    ] as $field) {
        $data[$field] = $_POST[$field] ?? '';
    }

    $submission_id = $submission ? $submission['id'] : createSubmission($user_id, $form_id, null, null, $mysqli);
    saveSection($submission_id, $section_name, $section_label, $data, $mysqli);
    updateSubmissionStatus($submission_id, $form_id, $mysqli);
    redirectWithMessage($_SERVER['REQUEST_URI'], 'success', 'Data berhasil disimpan.');
}
?>
<main id="main" class="main">
<?php include "tab.php"; ?><section class="section dashboard">
<?php include dirname(__DIR__) . '/partials/notifikasi.php'; ?><?php include dirname(__DIR__) . '/partials/status_section.php'; ?>
<form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">
<div class="card"><div class="card-body">
<h5 class="card-title"><strong>5. Pengkajian Psikososial dan Spiritual</strong></h5>
<?php foreach ([
    'kondisi_lansia' => 'Lansia menyadari dan menerima kondisinya / kesehatannya tidak seperti saat muda',
    'penyesuaian_lansia' => 'Lansia menyesuaikan / tidak memaksakan pekerjaan / aktivitas yang dilakukan',
    'prolanis_lansia' => 'Lansia rutin mengikuti kegiatan Prolanis',
    'periksa_kesehatan_lansia' => 'Lansia rutin memeriksakan kesehatannya di praktik dokter / puskesmas',
    'posyandu_lansia' => 'Lansia masih mengikuti kegiatan pemeriksaan kesehatan di Posyandu lansia',
    'kegiatan_rt_lansia' => 'Lansia masih sempat mengikuti kegiatan-kegiatan yang dilaksanakan oleh RT',
    'dukungan_gerontik' => 'Keluarga memberikan dukungan dan peduli terhadap kesehatan lansia',
    'ingatkan_pantangan' => 'Keluarga mengingatkan pantangan makanan bagi kesehatan lansia',
    'senang_berkumpul' => 'Lansia merasa senang bila sedang berkumpul dengan anak dan cucu',
    'rutin_ibadah' => 'Lansia masih rutin menjalankan ibadah',
    'bersyukur' => 'Lansia merasa bersyukur kepada Tuhan YME dengan kondisinya saat ini',
    'berkembang_usia' => 'Lansia menganggap bahwa semakin bertambahnya usia semakin mendekatkan diri kepada Tuhan YME',
] as $field => $label): ?>
<div class="row mb-3"><label class="col-sm-9 col-form-label"><strong><?= $label ?></strong></label><div class="col-sm-3"><select class="form-select" name="<?= $field ?>" <?= $ro_select ?>><option value="">-- Pilih --</option><option value="Y" <?= val($field, $existing_data) === 'Y' ? 'selected' : '' ?>>Ya</option><option value="T" <?= val($field, $existing_data) === 'T' ? 'selected' : '' ?>>Tidak</option></select></div></div>
<?php endforeach; ?>
<?php if (!$is_dosen): ?><div class="row mb-3"><div class="col-sm-12 d-flex justify-content-end"><button type="submit" class="btn btn-primary">Simpan Data</button></div></div><?php endif; ?>
</div></div></form>
<?php include dirname(__DIR__) . '/partials/footer_form.php'; ?></div></div></section></main>
