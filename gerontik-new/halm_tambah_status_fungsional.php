<?php
$form_id       = 99;
$section_name  = 'status_fungsional';
$section_label = 'Status Fungsional';
include dirname(__DIR__) . '/partials/init_section.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $level === 'Mahasiswa') {
    if (isLocked($submission)) redirectWithMessage($_SERVER['REQUEST_URI'], 'error', 'Data tidak dapat diubah karena sedang dalam proses review.');
    $data = [];
    foreach (['makan','kontinen','berpindah','kamar_kecil','berpakaian','mandi','kesimpulan_status_fungsional'] as $field) {
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
<h5 class="card-title"><strong>6. Pengkajian Status Fungsional</strong></h5>
<table class="table table-bordered">
<thead><tr><th>Kegiatan</th><th>Status Fungsional</th></tr></thead>
<tbody>
<?php foreach (['makan'=>'Makan','kontinen'=>'Kontinen (Defekasi/Berkemih)','berpindah'=>'Berpindah','kamar_kecil'=>'Ke kamar kecil','berpakaian'=>'Berpakaian','mandi'=>'Mandi'] as $field => $label): ?>
<tr><td><?= $label ?></td><td><select class="form-select" name="<?= $field ?>" <?= $ro_select ?>><option value="">-- Pilih --</option><option value="mandiri" <?= val($field, $existing_data)==='mandiri'?'selected':'' ?>>Mandiri</option><option value="tergantung" <?= val($field, $existing_data)==='tergantung'?'selected':'' ?>>Tergantung</option></select></td></tr>
<?php endforeach; ?>
<tr><td><strong>Kesimpulan Status Fungsional</strong></td><td><input class="form-control" name="kesimpulan_status_fungsional" value="<?= htmlspecialchars(val('kesimpulan_status_fungsional', $existing_data)) ?>" <?= $ro ?>></td></tr>
</tbody></table>
<?php if (!$is_dosen): ?><div class="row mb-3"><div class="col-sm-12 d-flex justify-content-end"><button type="submit" class="btn btn-primary">Simpan Data</button></div></div><?php endif; ?>
</div></div></form>
<?php include dirname(__DIR__) . '/partials/footer_form.php'; ?></div></div></section></main>
