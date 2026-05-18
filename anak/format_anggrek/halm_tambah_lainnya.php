<?php
$form_id       = 4;
$section_name  = 'catatan_keperawatan';
$section_label = 'Catatan Keperawatan';
include dirname(__DIR__, 2) . '/partials/init_section.php';

$existing_diagnosa     = $existing_data['diagnosa']     ?? [];
$existing_intervensi   = $existing_data['intervensi']   ?? [];
$existing_implementasi = $existing_data['implementasi'] ?? [];
$existing_evaluasi     = $existing_data['evaluasi']     ?? [];


if ($_SERVER['REQUEST_METHOD'] === 'POST' && $level === 'Mahasiswa') {
    if (isLocked($submission)) {
        redirectWithMessage($_SERVER['REQUEST_URI'], 'error', 'Data tidak dapat diubah karena sedang dalam proses review.');
    }

    $diagnosa = parse_dynamic_rows($_POST['diagnosa'] ?? [], ['diagnosa', 'tgl_ditemukan', 'tgl_teratasi']);
    $intervensi = parse_dynamic_rows($_POST['intervensi'] ?? [], ['diagnosa', 'tujuan_kriteria', 'intervensi']);
    $implementasi = parse_dynamic_rows($_POST['implementasi'] ?? [], ['no_dx', 'hari_tgl', 'jam', 'implementasi']);
    $evaluasi = parse_dynamic_rows($_POST['evaluasi'] ?? [], ['no_dx', 'hari_tgl', 'jam', 'evaluasi_s', 'evaluasi_o', 'evaluasi_a', 'evaluasi_p']);

    $data = [
        'diagnosa'     => $diagnosa,
        'intervensi'   => $intervensi,
        'implementasi' => $implementasi,
        'evaluasi'     => $evaluasi,
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

    <?php include "anak/format_anggrek/tab.php"; ?>
    <section class="section dashboard">
        <?php include dirname(__DIR__, 2) . '/partials/notifikasi.php'; ?>
        <?php include dirname(__DIR__, 2) . '/partials/status_section.php'; ?>
        <form class="needs-validation" novalidate action="" method="POST">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><strong>Catatan KEPERAWATAN</strong></h5>
                    <!-- ===================== TABEL DIAGNOSA ===================== -->
                    <p class="text-primary fw-bold mb-2">Diagnosa Keperawatan</p>
                    <table class="table table-bordered" id="tabel-diagnosa">
                        <thead>
                            <tr>
                                <th class="text-center" style="width:40px">No</th>
                                <th class="text-center">Diagnosa</th>
                                <th class="text-center" style="width:180px">Tanggal Ditemukan</th>
                                <th class="text-center" style="width:180px">Tanggal Teratasi</th>
                                <?php if (!$is_readonly): ?>
                                    <th class="text-center" style="width:60px">Aksi</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody id="tbody-diagnosa"></tbody>
                    </table>
                    <?php if (!$is_readonly): ?>
                        <div class="row mb-4">
                            <div class="col-sm-12 d-flex justify-content-end">
                                <button type="button" class="btn btn-primary btn-sm" id="btn-tambah-diagnosa"
                                    onclick="tambahRowDiagnosa({tbodyId: 'tbody-diagnosa', rowCountVar: 'rowDiagnosaCount', isReadonly: <?= json_encode($is_readonly) ?>})">+ Tambah Diagnosa</button>
                            </div>
                        </div>
                    <?php endif; ?>
                    <!-- ===================== TABEL INTERVENSI ===================== -->
                    <p class="text-primary fw-bold mb-2">Intervensi Keperawatan</p>
                    <table class="table table-bordered" id="tabel-intervensi">
                        <thead>
                            <tr>
                                <th class="text-center" style="width:40px">No</th>
                                <th class="text-center">Diagnosa</th>
                                <th class="text-center">Tujuan dan Kriteria Hasil</th>
                                <th class="text-center">Intervensi</th>
                                <?php if (!$is_readonly): ?>
                                    <th class="text-center" style="width:60px">Aksi</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody id="tbody-intervensi"></tbody>
                    </table>
                    <?php if (!$is_readonly): ?>
                        <div class="row mb-4">
                            <div class="col-sm-12 d-flex justify-content-end">
                                <button type="button" class="btn btn-primary btn-sm" id="btn-tambah-intervensi"
                                    onclick="tambahRowIntervensi({tbodyId: 'tbody-intervensi', rowCountVar: 'rowIntervensiCount', isReadonly: <?= json_encode($is_readonly) ?>})">+ Tambah Intervensi</button>
                            </div>
                        </div>
                    <?php endif; ?>
                    <!-- ===================== TABEL IMPLEMENTASI ===================== -->
                    <p class="text-primary fw-bold mb-2">Implementasi Keperawatan</p>
                    <table class="table table-bordered" id="tabel-implementasi">
                        <thead>
                            <tr>
                                <th class="text-center" style="width:70px">No. Dx</th>
                                <th class="text-center" style="width:150px">Hari/Tanggal</th>
                                <th class="text-center" style="width:110px">Jam</th>
                                <th class="text-center">Implementasi</th>
                                <?php if (!$is_readonly): ?>
                                    <th class="text-center" style="width:60px">Aksi</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody id="tbody-implementasi"></tbody>
                    </table>
                    <?php if (!$is_readonly): ?>
                        <div class="row mb-4">
                            <div class="col-sm-12 d-flex justify-content-end">
                                <button type="button" class="btn btn-primary btn-sm" id="btn-tambah-implementasi"
                                    onclick="tambahRowImplementasi({tbodyId: 'tbody-implementasi', rowCountVar: 'rowImplementasiCount', isReadonly: <?= json_encode($is_readonly) ?>})">+ Tambah Implementasi</button>
                            </div>
                        </div>
                    <?php endif; ?>
                    <!-- ===================== TABEL EVALUASI ===================== -->
                    <p class="text-primary fw-bold mb-2">Evaluasi Keperawatan</p>
                    <table class="table table-bordered" id="tabel-evaluasi">
                        <thead>
                            <tr>
                                <th class="text-center" style="width:70px">No. Dx</th>
                                <th class="text-center" style="width:150px">Hari/Tanggal</th>
                                <th class="text-center" style="width:110px">Jam</th>
                                <th class="text-center">Evaluasi (SOAP)</th>
                                <?php if (!$is_readonly): ?>
                                    <th class="text-center" style="width:60px">Aksi</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody id="tbody-evaluasi"></tbody>
                    </table>
                    <?php if (!$is_readonly): ?>
                        <div class="row mb-4">
                            <div class="col-sm-12 d-flex justify-content-end">
                                <button type="button" class="btn btn-primary btn-sm" id="btn-tambah-evaluasi"
                                    onclick="tambahRowEvaluasi({tbodyId: 'tbody-evaluasi', rowCountVar: 'rowEvaluasiCount', isReadonly: <?= json_encode($is_readonly) ?>})">+ Tambah Evaluasi</button>
                            </div>
                        </div>
                    <?php endif; ?>
                
                <!-- TOMBOL SIMPAN (hanya mahasiswa) -->
                <?php if (!$is_dosen): ?>
                    <div class="row mb-3">
                        <div class="col-sm-12 d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">Simpan Data</button>
                        </div>
                    </div>
                <?php endif; ?>
                </div>
            </div>
            <?php include dirname(__DIR__, 2) . '/partials/footer_form.php'; ?>
        </form>
    </section>
</main>
<script>
    let rowDiagnosaCount = 1;
    let rowIntervensiCount = 1;
    let rowImplementasiCount = 1;
    let rowEvaluasiCount = 1;
    const isReadonly = <?= json_encode($is_readonly) ?>;
    const existingDiagnosa = <?= json_encode($existing_diagnosa) ?>;
    const existingIntervensi = <?= json_encode($existing_intervensi) ?>;
    const existingImplementasi = <?= json_encode($existing_implementasi) ?>;
    const existingEvaluasi = <?= json_encode($existing_evaluasi) ?>;
    // Import helper
    const script = document.createElement('script');
    script.src = '/assets/js/form_row_helpers.js';
    document.head.appendChild(script);
    // Load existing data on page load
    window.addEventListener('load', function() {
        if (existingDiagnosa && existingDiagnosa.length > 0) {
            existingDiagnosa.forEach(row => {
                tambahRowDiagnosa({
                    tbodyId: 'tbody-diagnosa',
                    rowCountVar: 'rowDiagnosaCount',
                    isReadonly,
                    data: row
                });
            });
        } else if (!isReadonly) {
            tambahRowDiagnosa({
                tbodyId: 'tbody-diagnosa',
                rowCountVar: 'rowDiagnosaCount',
                isReadonly
            });
        }
        if (existingIntervensi && existingIntervensi.length > 0) {
            existingIntervensi.forEach(row => {
                tambahRowIntervensi({
                    tbodyId: 'tbody-intervensi',
                    rowCountVar: 'rowIntervensiCount',
                    isReadonly,
                    data: row
                });
            });
        } else if (!isReadonly) {
            tambahRowIntervensi({
                tbodyId: 'tbody-intervensi',
                rowCountVar: 'rowIntervensiCount',
                isReadonly
            });
        }
        if (existingImplementasi && existingImplementasi.length > 0) {
            existingImplementasi.forEach(row => {
                tambahRowImplementasi({
                    tbodyId: 'tbody-implementasi',
                    rowCountVar: 'rowImplementasiCount',
                    isReadonly,
                    data: row
                });
            });
        } else if (!isReadonly) {
            tambahRowImplementasi({
                tbodyId: 'tbody-implementasi',
                rowCountVar: 'rowImplementasiCount',
                isReadonly
            });
        }
        if (existingEvaluasi && existingEvaluasi.length > 0) {
            existingEvaluasi.forEach(row => {
                tambahRowEvaluasi({
                    tbodyId: 'tbody-evaluasi',
                    rowCountVar: 'rowEvaluasiCount',
                    isReadonly,
                    data: row
                });
            });
        } else if (!isReadonly) {
            tambahRowEvaluasi({
                tbodyId: 'tbody-evaluasi',
                rowCountVar: 'rowEvaluasiCount',
                isReadonly
            });
        }
        // Disable tombol tambah jika readonly
        if (isReadonly) {
            ['btn-tambah-diagnosa', 'btn-tambah-intervensi', 'btn-tambah-implementasi', 'btn-tambah-evaluasi'].forEach(id => {
                const btn = document.getElementById(id);
                if (btn) btn.setAttribute('disabled', 'disabled');
            });
        }
    });
</script>