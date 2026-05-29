<?php
$form_id       = 99;
$section_name  = 'apgar_spmsq_risiko_jatuh';
$section_label = 'APGAR / SPMSQ / Risiko Jatuh';
include dirname(__DIR__) . '/partials/init_section.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $level === 'Mahasiswa') {
    if (isLocked($submission)) redirectWithMessage($_SERVER['REQUEST_URI'], 'error', 'Data tidak dapat diubah karena sedang dalam proses review.');
    $data = [];
    foreach (['A','P','G','A2','R','kesimpulan_apgar','q1','q2','q3','q4','q5','q6','q7','q8','q9','q10','kesimpulan_spmsq','riwayat_jatuh','status_mental','penglihatan','berkemih','transfer','mobilitas','kesimpulan_penilaian'] as $field) {
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
<h5 class="card-title"><strong>8. APGAR / SPMSQ / Risiko Jatuh</strong></h5>

<div class="subsection-title">APGAR Gerontik</div>
<?php foreach ([
    'A' => 'Saya puas bisa kembali pada teman saya untuk membantu saya bila suatu waktu ada kondisi yang menyusahkan saya.',
    'P' => 'Saya puas dengan cara teman saya membicarakan sesuatu dan mengungkapkan masalah dengan saya.',
    'G' => 'Saya puas bahwa teman saya menerima dan mendukung keinginan untuk melakukan aktivitas.',
    'A2' => 'Saya puas dengan cara teman saya mengekspresikan afek dan berespon terhadap emosi saya.',
    'R' => 'Saya puas dengan cara teman saya menyediakan waktu secara bersama-sama.',
] as $field => $label): ?>
<div class="row mb-3"><label class="col-sm-10 col-form-label"><strong><?= $label ?></strong></label><div class="col-sm-2"><select class="form-select" name="<?= $field ?>" <?= $ro_select ?>><option value="">-- Pilih --</option><option value="2" <?= val($field, $existing_data)==='2'?'selected':'' ?>>Selalu</option><option value="1" <?= val($field, $existing_data)==='1'?'selected':'' ?>>Kadang</option><option value="0" <?= val($field, $existing_data)==='0'?'selected':'' ?>>Tidak pernah</option></select></div></div>
<?php endforeach; ?>
<div class="row mb-3"><label class="col-sm-2 col-form-label"><strong>Kesimpulan APGAR</strong></label><div class="col-sm-9"><textarea class="form-control" name="kesimpulan_apgar" rows="3" <?= $ro ?>><?= val('kesimpulan_apgar', $existing_data) ?></textarea></div></div>

<div class="subsection-title">SPMSQ</div>
<?php for ($i=1; $i<=10; $i++): ?>
<div class="row mb-3"><label class="col-sm-10 col-form-label"><strong>Pertanyaan <?= $i ?></strong></label><div class="col-sm-2"><select class="form-select" name="q<?= $i ?>" <?= $ro_select ?>><option value="">-- Pilih --</option><option value="B" <?= val('q'.$i, $existing_data)==='B'?'selected':'' ?>>Benar</option><option value="S" <?= val('q'.$i, $existing_data)==='S'?'selected':'' ?>>Salah</option></select></div></div>
<?php endfor; ?>
<div class="row mb-3"><label class="col-sm-2 col-form-label"><strong>Kesimpulan SPMSQ</strong></label><div class="col-sm-9"><textarea class="form-control" name="kesimpulan_spmsq" rows="3" <?= $ro ?>><?= val('kesimpulan_spmsq', $existing_data) ?></textarea></div></div>

<div class="subsection-title">Risiko Jatuh</div>
<?php foreach ([
    'riwayat_jatuh' => 'Riwayat jatuh dalam 6 bulan terakhir',
    'status_mental' => 'Status mental',
    'penglihatan' => 'Penglihatan',
    'berkemih' => 'Berkemih',
    'transfer' => 'Transfer',
    'mobilitas' => 'Mobilitas',
] as $field => $label): ?>
<div class="row mb-3"><label class="col-sm-10 col-form-label"><strong><?= $label ?></strong></label><div class="col-sm-2"><select class="form-select" name="<?= $field ?>" <?= $ro_select ?>><option value="">-- Pilih --</option><option value="Y" <?= val($field, $existing_data)==='Y'?'selected':'' ?>>Ya</option><option value="T" <?= val($field, $existing_data)==='T'?'selected':'' ?>>Tidak</option></select></div></div>
<?php endforeach; ?>
<div class="row mb-3"><label class="col-sm-2 col-form-label"><strong>Kesimpulan Penilaian</strong></label><div class="col-sm-9"><textarea class="form-control" name="kesimpulan_penilaian" rows="3" <?= $ro ?>><?= val('kesimpulan_penilaian', $existing_data) ?></textarea></div></div>

<?php if (!$is_dosen): ?><div class="row mb-3"><div class="col-sm-12 d-flex justify-content-end"><button type="submit" class="btn btn-primary">Simpan Data</button></div></div><?php endif; ?>
</div></div></form>
<?php include dirname(__DIR__) . '/partials/footer_form.php'; ?></div></div></section></main>
