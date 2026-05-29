<?php
$form_id       = 99;
$section_name  = 'pemeriksaan_fisik';
$section_label = 'Pemeriksaan Fisik';
include dirname(__DIR__) . '/partials/init_section.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $level === 'Mahasiswa') {
    if (isLocked($submission)) redirectWithMessage($_SERVER['REQUEST_URI'], 'error', 'Data tidak dapat diubah karena sedang dalam proses review.');

    $data = [
        'td' => $_POST['td'] ?? '', 'nadi' => $_POST['nadi'] ?? '', 'rr' => $_POST['rr'] ?? '', 'suhu' => $_POST['suhu'] ?? '', 'tingkat_kesadaran' => $_POST['tingkat_kesadaran'] ?? '',
        'kepala' => $_POST['kepala'] ?? '', 'mata' => $_POST['mata'] ?? '', 'telinga' => $_POST['telinga'] ?? '', 'hidung_sinus' => $_POST['hidung_sinus'] ?? '',
        'mulut_tenggorokan' => $_POST['mulut_tenggorokan'] ?? '', 'leher' => $_POST['leher'] ?? '', 'pernapasan' => $_POST['pernapasan'] ?? '', 'kardiovaskuler' => $_POST['kardiovaskuler'] ?? '',
        'gastrointestinal' => $_POST['gastrointestinal'] ?? '', 'perkemihan' => $_POST['perkemihan'] ?? '', 'muskuloskeletal' => $_POST['muskuloskeletal'] ?? '',
        'endokrin' => $_POST['endokrin'] ?? '', 'neuro_motorik_sensoris' => $_POST['neuro_motorik_sensoris'] ?? '', 'integumen' => $_POST['integumen'] ?? '',
    ];
    $submission_id = $submission ? $submission['id'] : createSubmission($user_id, $form_id, null, null, $mysqli);
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
<div class="card"><div class="card-body">
<h5 class="card-title"><strong>3. Pemeriksaan Fisik</strong></h5>
<div class="row mb-3"><label class="col-sm-2 col-form-label"><strong>TD</strong></label><div class="col-sm-9"><input class="form-control" name="td" value="<?= htmlspecialchars(val('td', $existing_data)) ?>" <?= $ro ?>></div></div>
<div class="row mb-3"><label class="col-sm-2 col-form-label"><strong>Nadi</strong></label><div class="col-sm-9"><input class="form-control" name="nadi" value="<?= htmlspecialchars(val('nadi', $existing_data)) ?>" <?= $ro ?>></div></div>
<div class="row mb-3"><label class="col-sm-2 col-form-label"><strong>RR</strong></label><div class="col-sm-9"><input class="form-control" name="rr" value="<?= htmlspecialchars(val('rr', $existing_data)) ?>" <?= $ro ?>></div></div>
<div class="row mb-3"><label class="col-sm-2 col-form-label"><strong>Suhu</strong></label><div class="col-sm-9"><input class="form-control" name="suhu" value="<?= htmlspecialchars(val('suhu', $existing_data)) ?>" <?= $ro ?>></div></div>
<div class="row mb-3"><label class="col-sm-2 col-form-label"><strong>Tingkat Kesadaran</strong></label><div class="col-sm-9"><input class="form-control" name="tingkat_kesadaran" value="<?= htmlspecialchars(val('tingkat_kesadaran', $existing_data)) ?>" <?= $ro ?>></div></div>

<?php foreach ([
    'kepala' => 'Kepala',
    'mata' => 'Mata',
    'telinga' => 'Telinga',
    'hidung_sinus' => 'Hidung dan Sinus',
    'mulut_tenggorokan' => 'Mulut dan Tenggorokan',
    'leher' => 'Leher',
    'pernapasan' => 'Pernapasan',
    'kardiovaskuler' => 'Kardiovaskuler',
    'gastrointestinal' => 'Gastrointestinal',
    'perkemihan' => 'Perkemihan',
    'muskuloskeletal' => 'Muskuloskeletal',
    'endokrin' => 'Endokrin',
    'neuro_motorik_sensoris' => 'Neuro Motorik Sensoris',
    'integumen' => 'Integumen',
] as $field => $label): ?>
<div class="row mb-3"><label class="col-sm-2 col-form-label"><strong><?= $label ?></strong></label><div class="col-sm-9"><textarea class="form-control" rows="3" name="<?= $field ?>" <?= $ro ?>><?= val($field, $existing_data) ?></textarea></div></div>
<?php endforeach; ?>

<?php if (!$is_dosen): ?><div class="row mb-3"><div class="col-sm-12 d-flex justify-content-end"><button type="submit" class="btn btn-primary">Simpan Data</button></div></div><?php endif; ?>
</div></div></form>
<?php include dirname(__DIR__) . '/partials/footer_form.php'; ?>
</div></div></section></main>
