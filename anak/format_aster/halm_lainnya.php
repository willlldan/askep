<?php

$form_id       = 5;
$section_name  = 'lainnya';
$section_label = 'Lainnya';
include dirname(__DIR__, 2) . '/partials/init_section.php';

// Load existing dynamic rows
$existing_diagnosa      = $existing_data['diagnosa']      ?? [];
$existing_rencana       = $existing_data['rencana']       ?? [];
$existing_implementasi  = $existing_data['implementasi']  ?? [];
$existing_evaluasi      = $existing_data['evaluasi']      ?? [];


// =============================================
// HANDLE POST - MAHASISWA
// =============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $level === 'Mahasiswa') {

    if (isLocked($submission)) {
        redirectWithMessage($_SERVER['REQUEST_URI'], 'error', 'Data tidak dapat diubah karena sedang dalam proses review.');
    }

    // Diagnosa Keperawatan Prioritas
    $diagnosa = parse_dynamic_rows($_POST['diagnosa'] ?? [], ['dods', 'diagnosa_kep']);
    $rencana = parse_dynamic_rows($_POST['rencana'] ?? [], ['diagnosa', 'tujuan', 'intervensi']);
    $implementasi = parse_dynamic_rows($_POST['implementasi'] ?? [], ['no_dx', 'hari_tgl', 'jam', 'implementasi_hasil']);
    $evaluasi = parse_dynamic_rows($_POST['evaluasi'] ?? [], ['no_dx', 'hari_tgl', 'jam', 'evaluasi_soap']);

    $data = [
        'diagnosa'     => $diagnosa,
        'rencana'      => $rencana,
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
    <?php include "anak/format_aster/tab.php"; ?>

    <section class="section dashboard">

        <?php include dirname(__DIR__, 2) . '/partials/notifikasi.php'; ?>
        <?php include dirname(__DIR__, 2) . '/partials/status_section.php'; ?>

        <form class="needs-validation" novalidate action="" method="POST">

            <!-- ===================== DIAGNOSA KEPERAWATAN PRIORITAS ===================== -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><strong>Diagnosa Keperawatan Prioritas</strong></h5>

                    <table class="table table-bordered" id="tabel-diagnosa">
                        <thead>
                            <tr>
                                <th class="text-center" style="width:40px">No</th>
                                <th class="text-center" style="width:20%">DO/DS</th>
                                <th class="text-center">Diagnosa Keperawatan</th>
                                <?php if (!$is_readonly): ?>
                                    <th class="text-center" style="width:60px">Aksi</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody id="tbody-diagnosa"></tbody>
                    </table>

                    <?php if (!$is_readonly): ?>
                        <div class="row mb-3">
                            <div class="col-sm-12 d-flex justify-content-end">
                                <button type="button" class="btn btn-primary btn-sm"
                                    onclick="tambahRowDiagnosa()">+ Tambah Baris</button>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- ===================== RENCANA KEPERAWATAN ===================== -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><strong>Rencana Keperawatan</strong></h5>

                    <table class="table table-bordered" id="tabel-rencana">
                        <thead>
                            <tr>
                                <th class="text-center" style="width:40px">No</th>
                                <th class="text-center">Diagnosa Keperawatan</th>
                                <th class="text-center">Tujuan & Kriteria Hasil</th>
                                <th class="text-center">Intervensi</th>
                                <?php if (!$is_readonly): ?>
                                    <th class="text-center" style="width:60px">Aksi</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody id="tbody-rencana"></tbody>
                    </table>

                    <?php if (!$is_readonly): ?>
                        <div class="row mb-3">
                            <div class="col-sm-12 d-flex justify-content-end">
                                <button type="button" class="btn btn-primary btn-sm"
                                    onclick="tambahRowRencana()">+ Tambah Baris</button>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- ===================== IMPLEMENTASI ===================== -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><strong>Implementasi</strong></h5>

                    <table class="table table-bordered" id="tabel-implementasi">
                        <thead>
                            <tr>
                                <th class="text-center" style="width:60px">No DX</th>
                                <th class="text-center" style="width:120px">Hari/Tgl</th>
                                <th class="text-center" style="width:80px">Jam</th>
                                <th class="text-center">Implementasi &amp; Hasil</th>
                                <?php if (!$is_readonly): ?>
                                    <th class="text-center" style="width:60px">Aksi</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody id="tbody-implementasi"></tbody>
                    </table>

                    <?php if (!$is_readonly): ?>
                        <div class="row mb-3">
                            <div class="col-sm-12 d-flex justify-content-end">
                                <button type="button" class="btn btn-primary btn-sm"
                                    onclick="tambahRowImplementasi()">+ Tambah Baris</button>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- ===================== EVALUASI ===================== -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><strong>Evaluasi</strong></h5>

                    <table class="table table-bordered" id="tabel-evaluasi">
                        <thead>
                            <tr>
                                <th class="text-center" style="width:60px">No DX</th>
                                <th class="text-center" style="width:120px">Hari/Tgl</th>
                                <th class="text-center" style="width:80px">Jam</th>
                                <th class="text-center">Evaluasi (SOAP)</th>
                                <?php if (!$is_readonly): ?>
                                    <th class="text-center" style="width:60px">Aksi</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody id="tbody-evaluasi"></tbody>
                    </table>

                    <?php if (!$is_readonly): ?>
                        <div class="row mb-3">
                            <div class="col-sm-12 d-flex justify-content-end">
                                <button type="button" class="btn btn-primary btn-sm"
                                    onclick="tambahRowEvaluasi()">+ Tambah Baris</button>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- TOMBOL SIMPAN -->
                    <?php if (!$is_dosen): ?>
                        <div class="row mb-3">
                            <div class="col-sm-12 d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary" <?= $ro_disabled ?>>Simpan Data</button>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </form>

        <?php include dirname(__DIR__, 2) . '/partials/footer_form.php'; ?>


    </section>
</main>

<script>
    const isReadonly = <?= json_encode($is_readonly) ?>;
    const existingDiagnosa = <?= json_encode($existing_diagnosa) ?>;
    const existingRencana = <?= json_encode($existing_rencana) ?>;
    const existingImplementasi = <?= json_encode($existing_implementasi) ?>;
    const existingEvaluasi = <?= json_encode($existing_evaluasi) ?>;

    let rowDiagnosaCount = 1;
    let rowRencanaCount = 1;
    let rowImplementasiCount = 1;
    let rowEvaluasiCount = 1;

    function hapusRow(btn) {
        btn.closest('tr').remove();
    }

    function mkTextarea(name, value, rows = 2) {
        return `<textarea class="form-control form-control-sm"
        name="${name}" rows="${rows}"
        style="resize:none; overflow:hidden;"
        oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
        ${isReadonly ? 'readonly' : ''}
    >${value ?? ''}</textarea>`;
    }

    function mkInput(name, value, type = 'text') {
        return `<input type="${type}" class="form-control form-control-sm"
        name="${name}" value="${value ?? ''}"
        ${isReadonly ? 'readonly' : ''}>`;
    }

    const aksiCol = isReadonly ? '' : `
    <td class="text-center align-middle">
        <button type="button" class="btn btn-danger btn-sm" onclick="hapusRow(this)">x</button>
    </td>`;

    // =============================================
    // DIAGNOSA KEPERAWATAN PRIORITAS
    // =============================================
    function tambahRowDiagnosa(data = null) {
        const tbody = document.getElementById('tbody-diagnosa');
        const i = rowDiagnosaCount;
        const row = document.createElement('tr');
        row.innerHTML = `
        <td class="text-center align-middle">${i}</td>
        <td>${mkTextarea(`diagnosa[${i}][dods]`, data?.dods)}</td>
        <td>${mkTextarea(`diagnosa[${i}][diagnosa_kep]`, data?.diagnosa_kep)}</td>
        ${aksiCol}
    `;
        tbody.appendChild(row);
        rowDiagnosaCount++;
    }

    // =============================================
    // RENCANA KEPERAWATAN
    // =============================================
    function tambahRowRencana(data = null) {
        const tbody = document.getElementById('tbody-rencana');
        const i = rowRencanaCount;
        const row = document.createElement('tr');
        row.innerHTML = `
        <td class="text-center align-middle">${i}</td>
        <td>${mkTextarea(`rencana[${i}][diagnosa]`, data?.diagnosa)}</td>
        <td>${mkTextarea(`rencana[${i}][tujuan]`, data?.tujuan)}</td>
        <td>${mkTextarea(`rencana[${i}][intervensi]`, data?.intervensi)}</td>
        ${aksiCol}
    `;
        tbody.appendChild(row);
        rowRencanaCount++;
    }

    // =============================================
    // IMPLEMENTASI
    // =============================================
    function tambahRowImplementasi(data = null) {
        const tbody = document.getElementById('tbody-implementasi');
        const i = rowImplementasiCount;
        const row = document.createElement('tr');
        row.innerHTML = `
        <td>${mkInput(`implementasi[${i}][no_dx]`, data?.no_dx)}</td>
        <td>${mkInput(`implementasi[${i}][hari_tgl]`, data?.hari_tgl, 'date')}</td>
        <td>${mkInput(`implementasi[${i}][jam]`, data?.jam, 'time')}</td>
        <td>${mkTextarea(`implementasi[${i}][implementasi_hasil]`, data?.implementasi_hasil, 3)}</td>
        ${aksiCol}
    `;
        tbody.appendChild(row);
        rowImplementasiCount++;
    }

    // =============================================
    // EVALUASI
    // =============================================
    function tambahRowEvaluasi(data = null) {
        const tbody = document.getElementById('tbody-evaluasi');
        const i = rowEvaluasiCount;
        const row = document.createElement('tr');

        // Format SOAP sebagai textarea dengan placeholder
        const soapVal = data?.evaluasi_soap ?? '';
        const soapPlaceholder = 'S:\nO:\nA:\nP:';

        row.innerHTML = `
        <td>${mkInput(`evaluasi[${i}][no_dx]`, data?.no_dx)}</td>
        <td>${mkInput(`evaluasi[${i}][hari_tgl]`, data?.hari_tgl, 'date')}</td>
        <td>${mkInput(`evaluasi[${i}][jam]`, data?.jam, 'time')}</td>
        <td>
            <textarea class="form-control form-control-sm"
                name="evaluasi[${i}][evaluasi_soap]"
                rows="4" style="resize:none; overflow:hidden; font-family:monospace;"
                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                placeholder="${soapPlaceholder}"
                ${isReadonly ? 'readonly' : ''}
            >${soapVal}</textarea>
        </td>
        ${aksiCol}
    `;
        tbody.appendChild(row);
        rowEvaluasiCount++;
    }

    // =============================================
    // LOAD EXISTING DATA ON PAGE LOAD
    // =============================================
    window.addEventListener('load', function() {
        // Diagnosa
        if (existingDiagnosa && existingDiagnosa.length > 0) {
            existingDiagnosa.forEach(row => tambahRowDiagnosa(row));
        } else if (!isReadonly) {
            tambahRowDiagnosa();
        }

        // Rencana
        if (existingRencana && existingRencana.length > 0) {
            existingRencana.forEach(row => tambahRowRencana(row));
        } else if (!isReadonly) {
            tambahRowRencana();
        }

        // Implementasi
        if (existingImplementasi && existingImplementasi.length > 0) {
            existingImplementasi.forEach(row => tambahRowImplementasi(row));
        } else if (!isReadonly) {
            tambahRowImplementasi();
        }

        // Evaluasi
        if (existingEvaluasi && existingEvaluasi.length > 0) {
            existingEvaluasi.forEach(row => tambahRowEvaluasi(row));
        } else if (!isReadonly) {
            tambahRowEvaluasi();
        }
    });
</script>