<?php
$form_id       = 99;
$section_name  = 'catatan_keperawatan';
$section_label = 'Catatan Keperawatan';
include dirname(__DIR__) . '/partials/init_section.php';

$existing_diagnosa = $existing_data['diagnosa'] ?? [];
$existing_rencana = $existing_data['rencana'] ?? [];
$existing_implementasi = $existing_data['implementasi'] ?? [];
$existing_evaluasi = $existing_data['evaluasi'] ?? [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $level === 'Mahasiswa') {
    if (isLocked($submission)) redirectWithMessage($_SERVER['REQUEST_URI'], 'error', 'Data tidak dapat diubah karena sedang dalam proses review.');

    $diagnosa = [];
    foreach (($_POST['diagnosa'] ?? []) as $row) {
        if (empty($row['diagnosa']) && empty($row['tgl_ditemukan']) && empty($row['tgl_teratasi'])) continue;
        $diagnosa[] = [
            'diagnosa' => $row['diagnosa'] ?? '',
            'tgl_ditemukan' => $row['tgl_ditemukan'] ?? '',
            'tgl_teratasi' => $row['tgl_teratasi'] ?? '',
        ];
    }

    $rencana = [];
    foreach (($_POST['rencana'] ?? []) as $row) {
        if (empty($row['diagnosa']) && empty($row['tujuan_kriteria']) && empty($row['rencana'])) continue;
        $rencana[] = [
            'diagnosa' => $row['diagnosa'] ?? '',
            'tujuan_kriteria' => $row['tujuan_kriteria'] ?? '',
            'rencana' => $row['rencana'] ?? '',
        ];
    }

    $implementasi = [];
    foreach (($_POST['implementasi'] ?? []) as $row) {
        if (empty($row['no_dx']) && empty($row['hari_tgl']) && empty($row['implementasi'])) continue;
        $implementasi[] = [
            'no_dx' => $row['no_dx'] ?? '',
            'hari_tgl' => $row['hari_tgl'] ?? '',
            'jam' => $row['jam'] ?? '',
            'implementasi' => $row['implementasi'] ?? '',
        ];
    }

    $evaluasi = [];
    foreach (($_POST['evaluasi'] ?? []) as $row) {
        if (empty($row['no_dx']) && empty($row['hari_tgl']) && empty($row['evaluasi_s'])) continue;
        $evaluasi[] = [
            'no_dx' => $row['no_dx'] ?? '',
            'hari_tgl' => $row['hari_tgl'] ?? '',
            'jam' => $row['jam'] ?? '',
            'evaluasi_s' => $row['evaluasi_s'] ?? '',
            'evaluasi_o' => $row['evaluasi_o'] ?? '',
            'evaluasi_a' => $row['evaluasi_a'] ?? '',
            'evaluasi_p' => $row['evaluasi_p'] ?? '',
        ];
    }

    $data = ['diagnosa' => $diagnosa, 'rencana' => $rencana, 'implementasi' => $implementasi, 'evaluasi' => $evaluasi];
    $submission_id = $submission ? $submission['id'] : createSubmission($user_id, $form_id, null, null, $mysqli);
    saveSection($submission_id, $section_name, $section_label, $data, $mysqli);
    updateSubmissionStatus($submission_id, $form_id, $mysqli);
    redirectWithMessage($_SERVER['REQUEST_URI'], 'success', 'Data berhasil disimpan.');
}
?>
<main id="main" class="main">
<?php include "tab.php"; ?><section class="section dashboard">
<?php include dirname(__DIR__) . '/partials/notifikasi.php'; ?><?php include dirname(__DIR__) . '/partials/status_section.php'; ?>
<div class="card"><div class="card-body"><h5 class="card-title"><strong>9. Catatan Keperawatan</strong></h5>
<form class="needs-validation" novalidate action="" method="POST">
<p class="text-primary fw-bold mb-2">Diagnosa Keperawatan</p>
<table class="table table-bordered"><thead><tr><th>No</th><th>Diagnosa</th><th>Tanggal Ditemukan</th><th>Tanggal Teratasi</th></tr></thead><tbody id="tbody-diagnosa"></tbody></table>
<div class="row mb-3"><div class="col-sm-12 d-flex justify-content-end"><button type="button" class="btn btn-primary btn-sm" onclick="tambahRowDiagnosa()">+ Tambah Diagnosa</button></div></div>

<p class="text-primary fw-bold mb-2">Rencana Keperawatan</p>
<table class="table table-bordered"><thead><tr><th>No</th><th>Diagnosa</th><th>Tujuan dan Kriteria Hasil</th><th>Rencana</th></tr></thead><tbody id="tbody-rencana"></tbody></table>
<div class="row mb-3"><div class="col-sm-12 d-flex justify-content-end"><button type="button" class="btn btn-primary btn-sm" onclick="tambahRowRencana()">+ Tambah Rencana</button></div></div>

<p class="text-primary fw-bold mb-2">Implementasi Keperawatan</p>
<table class="table table-bordered"><thead><tr><th>No. Dx</th><th>Hari/Tanggal</th><th>Jam</th><th>Implementasi</th></tr></thead><tbody id="tbody-implementasi"></tbody></table>
<div class="row mb-3"><div class="col-sm-12 d-flex justify-content-end"><button type="button" class="btn btn-primary btn-sm" onclick="tambahRowImplementasi()">+ Tambah Implementasi</button></div></div>

<p class="text-primary fw-bold mb-2">Evaluasi Keperawatan</p>
<table class="table table-bordered"><thead><tr><th>No. Dx</th><th>Hari/Tanggal</th><th>Jam</th><th>Evaluasi (SOAP)</th></tr></thead><tbody id="tbody-evaluasi"></tbody></table>
<div class="row mb-3"><div class="col-sm-12 d-flex justify-content-end"><button type="button" class="btn btn-primary btn-sm" onclick="tambahRowEvaluasi()">+ Tambah Evaluasi</button></div></div>

<?php if (!$is_dosen): ?><div class="row mb-3"><div class="col-sm-12 d-flex justify-content-end"><button type="submit" class="btn btn-primary">Simpan Data</button></div></div><?php endif; ?>

<script>
let rowDiagnosaCount = 1, rowRencanaCount = 1, rowImplementasiCount = 1, rowEvaluasiCount = 1;
const existingDiagnosa = <?= json_encode($existing_diagnosa) ?>;
const existingRencana = <?= json_encode($existing_rencana) ?>;
const existingImplementasi = <?= json_encode($existing_implementasi) ?>;
const existingEvaluasi = <?= json_encode($existing_evaluasi) ?>;
const isReadonly = <?= json_encode($is_readonly) ?>;
function tambahRowDiagnosa(data=null){const tbody=document.getElementById('tbody-diagnosa');const index=rowDiagnosaCount++;const row=document.createElement('tr');row.innerHTML=`<td>${index}</td><td><textarea class="form-control" name="diagnosa[${index}][diagnosa]" ${isReadonly?'readonly':''}>${data?.diagnosa??''}</textarea></td><td><input type="date" class="form-control" name="diagnosa[${index}][tgl_ditemukan]" value="${data?.tgl_ditemukan??''}" ${isReadonly?'readonly':''}></td><td><input type="date" class="form-control" name="diagnosa[${index}][tgl_teratasi]" value="${data?.tgl_teratasi??''}" ${isReadonly?'readonly':''}></td>`;tbody.appendChild(row)}
function tambahRowRencana(data=null){const tbody=document.getElementById('tbody-rencana');const index=rowRencanaCount++;const row=document.createElement('tr');row.innerHTML=`<td>${index}</td><td><textarea class="form-control" name="rencana[${index}][diagnosa]" ${isReadonly?'readonly':''}>${data?.diagnosa??''}</textarea></td><td><textarea class="form-control" name="rencana[${index}][tujuan_kriteria]" ${isReadonly?'readonly':''}>${data?.tujuan_kriteria??''}</textarea></td><td><textarea class="form-control" name="rencana[${index}][rencana]" ${isReadonly?'readonly':''}>${data?.rencana??''}</textarea></td>`;tbody.appendChild(row)}
function tambahRowImplementasi(data=null){const tbody=document.getElementById('tbody-implementasi');const index=rowImplementasiCount++;const row=document.createElement('tr');row.innerHTML=`<td><input type="text" class="form-control" name="implementasi[${index}][no_dx]" value="${data?.no_dx??''}" ${isReadonly?'readonly':''}></td><td><input type="datetime-local" class="form-control" name="implementasi[${index}][hari_tgl]" value="${data?.hari_tgl??''}" ${isReadonly?'readonly':''}></td><td><input type="time" class="form-control" name="implementasi[${index}][jam]" value="${data?.jam??''}" ${isReadonly?'readonly':''}></td><td><textarea class="form-control" name="implementasi[${index}][implementasi]" ${isReadonly?'readonly':''}>${data?.implementasi??''}</textarea></td>`;tbody.appendChild(row)}
function tambahRowEvaluasi(data=null){const tbody=document.getElementById('tbody-evaluasi');const index=rowEvaluasiCount++;const row=document.createElement('tr');row.innerHTML=`<td><input type="text" class="form-control" name="evaluasi[${index}][no_dx]" value="${data?.no_dx??''}" ${isReadonly?'readonly':''}></td><td><input type="datetime-local" class="form-control" name="evaluasi[${index}][hari_tgl]" value="${data?.hari_tgl??''}" ${isReadonly?'readonly':''}></td><td><input type="time" class="form-control" name="evaluasi[${index}][jam]" value="${data?.jam??''}" ${isReadonly?'readonly':''}></td><td><textarea class="form-control" name="evaluasi[${index}][evaluasi_s]" ${isReadonly?'readonly':''}>${data?.evaluasi_s??''}</textarea></td>`;tbody.appendChild(row)}
window.addEventListener('load',function(){if(existingDiagnosa.length)existingDiagnosa.forEach(v=>tambahRowDiagnosa(v));else if(!isReadonly)tambahRowDiagnosa();if(existingRencana.length)existingRencana.forEach(v=>tambahRowRencana(v));else if(!isReadonly)tambahRowRencana();if(existingImplementasi.length)existingImplementasi.forEach(v=>tambahRowImplementasi(v));else if(!isReadonly)tambahRowImplementasi();if(existingEvaluasi.length)existingEvaluasi.forEach(v=>tambahRowEvaluasi(v));else if(!isReadonly)tambahRowEvaluasi();});
</script>
</form></div></div>
<?php include dirname(__DIR__) . '/partials/footer_form.php'; ?></div></div></section></main>
