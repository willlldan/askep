<?php
$form_id       = 18;
$section_name  = 'riwayat_kesehatan';
$section_label = 'Riwayat Kesehatan';
include dirname(__DIR__) . '/partials/init_section.php';

$existing_genogram = $existing_data['genogram'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $level === 'Mahasiswa') {
    if (isLocked($submission)) {
        redirectWithMessage($_SERVER['REQUEST_URI'], 'error', 'Data tidak dapat diubah karena sedang dalam proses review.');
    }

    $genogram = $existing_genogram;
    if (!empty($_FILES['genogram']['name'])) {
        $upload = uploadImage($_FILES['genogram'], 'uploads/gerontik_new/', 50);
        if ($upload['success']) {
            if (!empty($genogram) && file_exists($genogram)) unlink($genogram);
            $genogram = $upload['path'];
        } else {
            redirectWithMessage($_SERVER['REQUEST_URI'], 'error', $upload['error']);
            exit;
        }
    }

    $data = [
        'keluhan_utama'            => $_POST['keluhan_utama']            ?? '',
        'riwayat_kesehatan_saat_ini' => $_POST['riwayat_kesehatan_saat_ini'] ?? '',
        'berkualitas'              => $_POST['berkualitas']              ?? '',
        'sehat'                    => $_POST['sehat']                    ?? '',
        'aktif'                    => $_POST['aktif']                    ?? '',
        'produktif'                => $_POST['produktif']                ?? '',
        'sakit_perawatan'          => $_POST['sakit_perawatan']          ?? '',
        'sakit_tanpa_perawatan'    => $_POST['sakit_tanpa_perawatan']    ?? '',
        'imunisasi'                => $_POST['imunisasi']                ?? '',
        'alergi_obat'              => $_POST['alergi_obat']              ?? '',
        'kecelakaan'               => $_POST['kecelakaan']               ?? '',
        'merokok'                  => $_POST['merokok']                  ?? '',
        'dirawat_rs'               => $_POST['dirawat_rs']               ?? '',
        'penyakit_1_tahun'         => $_POST['penyakit_1_tahun']         ?? '',
        'obat_2_minggu'            => $_POST['obat_2_minggu']            ?? '',
        'teratur_konsumsi'         => $_POST['teratur_konsumsi']         ?? '',
        'resep_dokter'             => $_POST['resep_dokter']             ?? '',
        'genogram'                 => $genogram,
        'G1'                       => $_POST['G1']                       ?? '',
        'G2'                       => $_POST['G2']                       ?? '',
        'G3'                       => $_POST['G3']                       ?? '',
    ];

    if (!$submission) $submission_id = createSubmission($user_id, $form_id, null, null, $mysqli);
    else $submission_id = $submission['id'];

    saveSection($submission_id, $section_name, $section_label, $data, $mysqli);
    updateSubmissionStatus($submission_id, $form_id, $mysqli);
    redirectWithMessage($_SERVER['REQUEST_URI'], 'success', 'Data berhasil disimpan.');
}
?>

<main id="main" class="main">
    <?php include "tab.php"; ?>
    <section class="section dashboard">
        <?php include dirname(__DIR__) . '/partials/notifikasi.php'; ?>
        <?php include dirname(__DIR__) . '/partials/status_section.php'; ?>

        <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><strong>2. Riwayat Kesehatan</strong></h5>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Keluhan Utama</strong></label>
                        <div class="col-sm-9"><textarea name="keluhan_utama" class="form-control" rows="3" <?= $ro ?>><?= val('keluhan_utama', $existing_data) ?></textarea></div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Riwayat Kesehatan Saat Ini</strong></label>
                        <div class="col-sm-9"><textarea name="riwayat_kesehatan_saat_ini" class="form-control" rows="3" <?= $ro ?>><?= val('riwayat_kesehatan_saat_ini', $existing_data) ?></textarea></div>
                    </div>

                    <?php
                    $ynFields = [
                        'berkualitas' => 'Berkualitas',
                        'sehat' => 'Sehat',
                        'aktif' => 'Aktif',
                        'produktif' => 'Produktif',
                        'sakit_perawatan' => 'Sakit dengan perawatan',
                        'sakit_tanpa_perawatan' => 'Sakit tanpa perawatan',
                    ];
                    ?>
                    <div class="row mb-2"><label class="col-sm-12 text-primary"><strong>Status Lanjut Usia</strong></label></div>
                    <?php foreach ($ynFields as $field => $label): ?>
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong><?= $label ?></strong></label>
                            <div class="col-sm-9">
                                <select class="form-select" name="<?= $field ?>" <?= $ro_select ?>>
                                    <option value="">-- Pilih --</option>
                                    <option value="Y" <?= val($field, $existing_data) === 'Y' ? 'selected' : '' ?>>Ya</option>
                                    <option value="T" <?= val($field, $existing_data) === 'T' ? 'selected' : '' ?>>Tidak</option>
                                </select>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <div class="row mb-2"><label class="col-sm-12 text-primary"><strong>IV. Riwayat Kesehatan Masa Lalu</strong></label></div>
                    <?php
$data_riwayat = [
    ['label' => 'Imunisasi', 'name' => 'imunisasi'],
    ['label' => 'Alergi Obat', 'name' => 'alergi_obat'],
    ['label' => 'Kecelakaan', 'name' => 'kecelakaan'],
    ['label' => 'Kebiasaan Merokok', 'name' => 'merokok'],
    ['label' => 'Dirawat di Rumah Sakit', 'name' => 'dirawat_rs'],
    ['label' => 'Penyakit 1 Tahun Terakhir', 'name' => 'penyakit_1_tahun'],
    ['label' => 'Nama Obat (2 Minggu Terakhir)', 'name' => 'obat_2_minggu'],
];

foreach ($data_riwayat as $field): ?>
    <div class="row mb-3">
        <label class="col-sm-2 col-form-label"><strong><?= $field['label'] ?></strong></label>
        <div class="col-sm-10">
            <textarea name="<?= $field['name'] ?>" 
                      class="form-control" 
                      rows="1" 
                      style="overflow:hidden; resize:none;" 
                      oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" 
                      <?= $ro ?>><?= val($field['name'], $existing_data) ?></textarea>
        </div>
    </div>
<?php endforeach; ?>
                    <div class="row mb-3"><label class="col-sm-2 col-form-label"><strong>Teratur Dikonsumsi</strong></label>
                        <div class="col-sm-9"><select class="form-select" name="teratur_konsumsi" <?= $ro_select ?>>
                                <option value="">-- Pilih --</option>
                                <option value="Y" <?= val('teratur_konsumsi', $existing_data) === 'Y' ? 'selected' : '' ?>>Ya</option>
                                <option value="T" <?= val('teratur_konsumsi', $existing_data) === 'T' ? 'selected' : '' ?>>Tidak</option>
                            </select></div>
                    </div>
                    <div class="row mb-3"><label class="col-sm-2 col-form-label"><strong>Obat Diresepkan Dokter</strong></label>
                        <div class="col-sm-9"><select class="form-select" name="resep_dokter" <?= $ro_select ?>>
                                <option value="">-- Pilih --</option>
                                <option value="Y" <?= val('resep_dokter', $existing_data) === 'Y' ? 'selected' : '' ?>>Ya</option>
                                <option value="T" <?= val('resep_dokter', $existing_data) === 'T' ? 'selected' : '' ?>>Tidak</option>
                            </select></div>
                    </div>

                    <div class="row mb-2"><label class="col-sm-12 text-primary"><strong>V. Riwayat Gerontik</strong></label></div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Genogram</strong></label>
                        <div class="col-sm-9">
                            <?php if (!empty($existing_genogram)): ?><img src="<?= htmlspecialchars($existing_genogram) ?>" class="img-fluid rounded border mb-2" style="max-height:260px;"><?php endif; ?>
                            <?php if (!$is_readonly): ?><input type="file" name="genogram" class="form-control" accept="image/jpeg,image/png,image/webp"><?php endif; ?>
                        </div>
                    </div>

                    <div class="row mb-3"><label class="col-sm-12 text-primary"><strong>Keterangan</strong></label></div>
                    <div class="row mb-3"><label class="col-sm-2 col-form-label"><strong>G1</strong></label>
                        <div class="col-sm-9"><textarea name="G1" class="form-control" rows="3" <?= $ro ?>><?= val('G1', $existing_data) ?></textarea></div>
                    </div>
                    <div class="row mb-3"><label class="col-sm-2 col-form-label"><strong>G2</strong></label>
                        <div class="col-sm-9"><textarea name="G2" class="form-control" rows="3" <?= $ro ?>><?= val('G2', $existing_data) ?></textarea></div>
                    </div>
                    <div class="row mb-3"><label class="col-sm-2 col-form-label"><strong>G3</strong></label>
                        <div class="col-sm-9"><textarea name="G3" class="form-control" rows="3" <?= $ro ?>><?= val('G3', $existing_data) ?></textarea></div>
                    </div>

                    <?php if (!$is_dosen): ?><div class="row mb-3">
                            <div class="col-sm-12 d-flex justify-content-end"><button type="submit" class="btn btn-primary">Simpan Data</button></div>
                        </div><?php endif; ?>
                </div>
            </div>
        </form>

        <?php include dirname(__DIR__) . '/partials/footer_form.php'; ?>
        </div>
        </div>
    </section>
</main>