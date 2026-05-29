<?php
$form_id       = 99;
$section_name  = 'kebiasaan_harian';
$section_label = 'Pola Kebiasaan Harian';
include dirname(__DIR__) . '/partials/init_section.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $level === 'Mahasiswa') {
    if (isLocked($submission)) redirectWithMessage($_SERVER['REQUEST_URI'], 'error', 'Data tidak dapat diubah karena sedang dalam proses review.');
    $data = [
        'frekuensi_makan' => $_POST['frekuensi_makan'] ?? '', 'nafsu_makan' => $_POST['nafsu_makan'] ?? '', 'jenis_makanan' => $_POST['jenis_makanan'] ?? '',
        'makanan_tidak_disukai' => $_POST['makanan_tidak_disukai'] ?? '', 'kebiasaan_sebelum_makan' => $_POST['kebiasaan_sebelum_makan'] ?? '',
        'berat_tinggi_badan' => $_POST['berat_tinggi_badan'] ?? '', 'jenis_minuman' => $_POST['jenis_minuman'] ?? '', 'jumlah_cairan' => $_POST['jumlah_cairan'] ?? '',
        'kesulitan_makan_minum' => $_POST['kesulitan_makan_minum'] ?? '', 'makan_minum_bantu' => $_POST['makan_minum_bantu'] ?? '',
        'warna_bak' => $_POST['warna_bak'] ?? '', 'keluhan_bak' => $_POST['keluhan_bak'] ?? '', 'dibantu_bak' => $_POST['dibantu_bak'] ?? '', 'mandiri_bak' => $_POST['mandiri_bak'] ?? '',
        'frekuensi_bab' => $_POST['frekuensi_bab'] ?? '', 'bau_bab' => $_POST['bau_bab'] ?? '', 'warna_bab' => $_POST['warna_bab'] ?? '', 'konsistensi_bab' => $_POST['konsistensi_bab'] ?? '', 'keluhan_bab' => $_POST['keluhan_bab'] ?? '', 'pengalaman_laksatif' => $_POST['pengalaman_laksatif'] ?? '', 'dibantu_bab' => $_POST['dibantu_bab'] ?? '', 'mandiri_bab' => $_POST['mandiri_bab'] ?? '',
        'frekuensi_mandi' => $_POST['frekuensi_mandi'] ?? '', 'dibantu_mandi' => $_POST['dibantu_mandi'] ?? '', 'mandiri_mandi' => $_POST['mandiri_mandi'] ?? '',
        'frekuensi_hygiene_oral' => $_POST['frekuensi_hygiene_oral'] ?? '', 'dibantu_hygiene_oral' => $_POST['dibantu_hygiene_oral'] ?? '', 'mandiri_hygiene_oral' => $_POST['mandiri_hygiene_oral'] ?? '',
        'frekuensi_cuci_rambut' => $_POST['frekuensi_cuci_rambut'] ?? '', 'dibantu_cuci_rambut' => $_POST['dibantu_cuci_rambut'] ?? '', 'mandiri_cuci_rambut' => $_POST['mandiri_cuci_rambut'] ?? '',
        'frekuensi_gunting_kuku' => $_POST['frekuensi_gunting_kuku'] ?? '', 'dibantu_gunting_kuku' => $_POST['dibantu_gunting_kuku'] ?? '', 'mandiri_gunting_kuku' => $_POST['mandiri_gunting_kuku'] ?? '',
        'lama_tidur' => $_POST['lama_tidur'] ?? '', 'kesulitan_tidur' => $_POST['kesulitan_tidur'] ?? '', 'tidur_siang' => $_POST['tidur_siang'] ?? '',
        'olahraga_ringan' => $_POST['olahraga_ringan'] ?? '', 'jenis_frekuensi_olahraga' => $_POST['jenis_frekuensi_olahraga'] ?? '', 'kegiatan_waktu_luang' => $_POST['kegiatan_waktu_luang'] ?? '', 'keluhan_aktivitas' => $_POST['keluhan_aktivitas'] ?? '', 'kesulitan_pergerakan' => $_POST['kesulitan_pergerakan'] ?? '', 'sesak_nafas' => $_POST['sesak_nafas'] ?? '',
    ];
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
<div class="card"><div class="card-body"><h5 class="card-title"><strong>4. Pola Kebiasaan Sehari-Hari</strong></h5>
<?php foreach ([
    'frekuensi_makan'=>'Frekuensi Makan','nafsu_makan'=>'Nafsu Makan','jenis_makanan'=>'Jenis Makanan','makanan_tidak_disukai'=>'Makanan Tidak Disukai','kebiasaan_sebelum_makan'=>'Kebiasaan / Ritual Sebelum Makan','berat_tinggi_badan'=>'Berat / Tinggi Badan','jenis_minuman'=>'Jenis Minuman','jumlah_cairan'=>'Jumlah Cairan',
    'warna_bak'=>'Warna BAK','keluhan_bak'=>'Keluhan BAK','frekuensi_bab'=>'Frekuensi BAB','bau_bab'=>'Bau BAB','warna_bab'=>'Warna BAB','konsistensi_bab'=>'Konsistensi BAB','keluhan_bab'=>'Keluhan BAB','pengalaman_laksatif'=>'Pengalaman Memakai Laksatif',
    'frekuensi_mandi'=>'Frekuensi Mandi','frekuensi_hygiene_oral'=>'Frekuensi Hygiene Oral','frekuensi_cuci_rambut'=>'Frekuensi Cuci Rambut','frekuensi_gunting_kuku'=>'Frekuensi Gunting Kuku','lama_tidur'=>'Lama Tidur','jenis_frekuensi_olahraga'=>'Jenis dan Frekuensi Olahraga','kegiatan_waktu_luang'=>'Kegiatan Waktu Luang'
] as $field => $label): ?>
<div class="row mb-3"><label class="col-sm-2 col-form-label"><strong><?= $label ?></strong></label><div class="col-sm-9"><input class="form-control" name="<?= $field ?>" value="<?= htmlspecialchars(val($field, $existing_data)) ?>" <?= $ro ?>></div></div>
<?php endforeach; ?>
<?php foreach ([
    'kesulitan_makan_minum'=>'Kesulitan Makan dan Minum','makan_minum_bantu'=>'Untuk Makan dan Minum','dibantu_bak'=>'Dibantu BAK','mandiri_bak'=>'Mandiri BAK','dibantu_bab'=>'Dibantu BAB','mandiri_bab'=>'Mandiri BAB','dibantu_mandi'=>'Dibantu Mandi','mandiri_mandi'=>'Mandiri Mandi','dibantu_hygiene_oral'=>'Dibantu Hygiene Oral','mandiri_hygiene_oral'=>'Mandiri Hygiene Oral','dibantu_cuci_rambut'=>'Dibantu Cuci Rambut','mandiri_cuci_rambut'=>'Mandiri Cuci Rambut','dibantu_gunting_kuku'=>'Dibantu Gunting Kuku','mandiri_gunting_kuku'=>'Mandiri Gunting Kuku','kesulitan_tidur'=>'Kesulitan Tidur','tidur_siang'=>'Tidur Siang','olahraga_ringan'=>'Melakukan Olahraga Ringan','keluhan_aktivitas'=>'Keluhan Beraktifitas','kesulitan_pergerakan'=>'Kesulitan Pergerakan Tubuh','sesak_nafas'=>'Sesak Nafas Setelah Aktivitas'
] as $field => $label): ?>
<div class="row mb-3"><label class="col-sm-2 col-form-label"><strong><?= $label ?></strong></label><div class="col-sm-9"><select class="form-select" name="<?= $field ?>" <?= $ro_select ?>><option value="">-- Pilih --</option><option value="Y" <?= val($field, $existing_data) === 'Y' ? 'selected' : '' ?>>Ya</option><option value="T" <?= val($field, $existing_data) === 'T' ? 'selected' : '' ?>>Tidak</option></select></div></div>
<?php endforeach; ?>
<?php if (!$is_dosen): ?><div class="row mb-3"><div class="col-sm-12 d-flex justify-content-end"><button type="submit" class="btn btn-primary">Simpan Data</button></div></div><?php endif; ?>
</div></div></form>
<?php include dirname(__DIR__) . '/partials/footer_form.php'; ?></div></div></section></main>
