<?php
$form_id       = 18;
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

<h5 class="card-title"><strong>Penjelasan</strong></h5>
<p>Kemandirian berarti tanpa pengawasan, pengarahan, atau bantuan pribadi aktif, kecuali secara spesifik akan digambarkan di bawah ini.</p>
<p>Pengkajian ini didasarkan pada kondisi aktual klien dan bukan pada kemampuan. Artinya, jika klien menolak untuk melakukan suatu fungsi, dianggap sebagai tidak melakukan fungsi meskipun ia sebenarnya mampu.</p>

<table class="table table-bordered">
<thead><tr><th>Kegiatan</th><th>Mandiri</th><th>Tergantung</th></tr></thead>
<tbody>
<tr><td>Makan</td><td>Memilih makanan dari piring dan menyuapi sendiri</td><td>Bantuan dalam hal mengambil makanan dan menyuapinya, tidak makan sama sekali, makan parenteral/enteral.</td></tr>
<tr><td>Kontinen (Defekasi/Berkemih)</td><td>Berkemih dan defekasi sepenuhnya dikendalikan sendiri</td><td>Inkontinensia parsial atau total, penggunaan kateter, pispot, enema, pembalut (diapers)</td></tr>
<tr><td>Berpindah</td><td>Berpindah ke dan dari tempat tidur, bangkit dari kursi sendiri</td><td>Berpindah dengan bantuan minimal, sedang, atau total</td></tr>
<tr><td>Ke kamar kecil</td><td>Masuk dan keluar kamar kecil sendiri</td><td>Membutuhkan bantuan, tidak dapat masuk atau keluar kamar kecil</td></tr>
<tr><td>Berpakaian</td><td>Memakai dan melepas pakaian sendiri, mengikat tali sepatu, memasang kancing</td><td>Memerlukan bantuan sebagian atau total dalam berpakaian</td></tr>
<tr><td>Mandi</td><td>Mandi sendiri sepenuhnya</td><td>Memerlukan bantuan sebagian atau total untuk mandi</td></tr>
<tr><td>Kesimpulan</td><td>A-B-C-D-E-F mandiri sesuai jumlah fungsi yang masih mandiri</td><td>G tergantung penuh pada keenam fungsi</td></tr>
</tbody></table>

<?php if (!$is_dosen): ?><div class="row mb-3"><div class="col-sm-12 d-flex justify-content-end"><button type="submit" class="btn btn-primary">Simpan Data</button></div></div><?php endif; ?>
</div></div></form>
<?php include dirname(__DIR__) . '/partials/footer_form.php'; ?></div></div></section></main>
