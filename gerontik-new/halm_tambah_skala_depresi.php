<?php
$form_id       = 99;
$section_name  = 'skala_depresi';
$section_label = 'Skala Depresi';
include dirname(__DIR__) . '/partials/init_section.php';

$questions = [
    1  => 'Apakah pada dasarnya Anda puas dengan kehidupan Anda?',
    2  => 'Sudahkah Anda banyak menghentikan aktivitas dan minat Anda?',
    3  => 'Apakah Anda merasa bahwa hidup Anda kosong?',
    4  => 'Apakah Anda sering bosan?',
    5  => 'Apakah Anda banyak berharap pada masa depan?',
    6  => 'Apakah Anda takut sesuatu akan terjadi pada Anda?',
    7  => 'Apakah Anda merasa terganggu dengan pemikiran bahwa Anda tidak bisa lepas dari pikiran yang sama?',
    8  => 'Apakah Anda takut bahwa suatu hal yang buruk akan menimpa Anda?',
    9  => 'Apakah Anda merasa gembira dalam sebagian besar waktu Anda?',
    10 => 'Apakah Anda merasa tidak mungkin tertolong?',
    11 => 'Apakah Anda sering menjadi gelisah atau sering / mudah terkejut?',
    12 => 'Apakah anda lebih suka tinggal di rumah pada malam hari dari pada pergi dan melakukan sesuatu yang baru?',
    13 => 'Apakah Anda sering mengkhawatirkan masa depan?',
    14 => 'Apakah anda merasa bahwa anda mempunyai lebih banyak masalah dengan ingatan anda dari pada yang lainnya?',
    15 => 'Apakah anda berfikir sangat menyenangkan hidup sekarang ini ?',
    16 => 'Apakah Anda sering merasa tidak enak hati atau sedih ?',
    17 => 'Apakah anda sering merasa benar-benar tidak berharga saat ini?',
    18 => 'Apakah Anda cukup sering khawatir mengenai masa lampau?',
    19 => 'Apakah Anda merasa kehidupan itu menyenangkan?',
    20 => 'Apakah sulit bagi Anda memulai hal yang baru?',
    21 => 'Apakah anda merasa penuh berenergi / semangat?',
    22 => 'Apakah anda berfikir bahwa situasi anda menggambarkan keputusasaan/ tidak ada harapan ?',
    23 => 'Apakah anda berfikir bahwa banyak orang yang lebih baik dari pada anda ?',
    24 => 'Apakah Anda sering menjadi kesal dikarenakan hal kecil?',
    25 => 'Apakah anda sering merasakan menangis?',
    26 => 'Apakah Anda merasa kesulitan untuk berkonsentrasi?',
    27 => 'APakah Anda menikmati bangun pagi setiap hari?',
    28 => 'Apakah Anda lebih menghindar dari perkumpulan sosial ?',
    29 => 'Apakah mudah bagi Anda membuat keputusan ?',
    30 => 'Apakah pemikiran / benak Anda sejernih di masa-masa lalu?',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $level === 'Mahasiswa') {
    if (isLocked($submission)) redirectWithMessage($_SERVER['REQUEST_URI'], 'error', 'Data tidak dapat diubah karena sedang dalam proses review.');
    $data = [];
    foreach (range(1, 30) as $i) $data['q' . $i] = $_POST['q' . $i] ?? '';
    $data['kesimpulan'] = $_POST['kesimpulan'] ?? '';
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
<h5 class="card-title"><strong>7. Skala Depresi</strong></h5>
<h6>Pengkajian ini menggunakan skala depresi geriatric bentuk singkat dari Yesavage (1983).</h6>
<?php foreach ($questions as $num => $text): ?>
    <div class="row mb-3">
        <label class="col-sm-10 col-form-label"><strong><?= $num . '. ' . htmlspecialchars($text) ?></strong></label>
        <div class="col-sm-2">
            <select class="form-select" name="q<?= $num ?>" <?= $ro_select ?>>
                <option value="">-- Pilih --</option>
                <option value="Ya" <?= val('q'.$num, $existing_data) === 'Ya' ? 'selected' : '' ?>>Ya</option>
                <option value="Tidak" <?= val('q'.$num, $existing_data) === 'Tidak' ? 'selected' : '' ?>>Tidak</option>
            </select>
        </div>
    </div>
<?php endforeach; ?>
<div class="row mb-3"><label class="col-sm-2 col-form-label"><strong>Kesimpulan</strong></label><div class="col-sm-9"><textarea class="form-control" name="kesimpulan" rows="4" <?= $ro ?>><?= val('kesimpulan', $existing_data) ?></textarea></div></div>
<?php if (!$is_dosen): ?><div class="row mb-3"><div class="col-sm-12 d-flex justify-content-end"><button type="submit" class="btn btn-primary">Simpan Data</button></div></div><?php endif; ?>
</div></div></form>
<?php include dirname(__DIR__) . '/partials/footer_form.php'; ?></div></div></section></main>
