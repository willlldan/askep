<?php

$form_id       = 30;
$section_name  = 'lainnya';
$section_label = 'Lainnya';
include dirname(__DIR__, 2) . '/partials/init_section.php';

// Load existing dynamic rows
$existing_diagnosa     = $existing_data['diagnosa']     ?? [];
$existing_kriteria     = $existing_data['kriteria']     ?? [];
$existing_rencana      = $existing_data['rencana']      ?? [];
$existing_implementasi = $existing_data['implementasi'] ?? [];
$existing_evaluasi     = $existing_data['evaluasi']     ?? [];


// =============================================
// HANDLE POST - MAHASISWA
// =============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $level === 'Mahasiswa') {

    if (isLocked($submission)) {
        redirectWithMessage($_SERVER['REQUEST_URI'], 'error', 'Data tidak dapat diubah karena sedang dalam proses review.');
    }

    $namakk        = $_POST['namakk'] ?? '';
    $tglpengkajian = $_POST['tglpengkajian'] ?? '';
    $umur          = $_POST['umur'] ?? '';
    $alamat        = $_POST['alamat'] ?? '';

    // Diagnosa Keperawatan Prioritas
    $diagnosa = parse_dynamic_rows($_POST['diagnosa'] ?? [], ['masalahkeperawatan', 'keluargayangsakit']);
    $kriteria = parse_dynamic_rows($_POST['kriteria'] ?? [], ['sifatmasalah', 'pembenaransifatmasalah', 'masalahdapatdiubah', 'pembenaranmasalahdapatdiubah',
    'masalahdapatdicegah', 'pembenaranmasalahdapatdicegah', 'menonjolnyamasalah', 'pembenaranmenonjolnyamasalah', 'jumlahskoring']);
    $rencana = parse_dynamic_rows($_POST['rencana'] ?? [], ['diagnosakeperawatan', 'tujuanjangkapanjang', 'tujuanjangkapendek', 'kriteria', 'standar', 'intervensi']);
    $implementasi = parse_dynamic_rows($_POST['implementasi'] ?? [], ['pertemuan', 'haritgl', 'jam', 'implementasi', 'hasil']);
    $evaluasi = parse_dynamic_rows($_POST['evaluasi'] ?? [], ['pertemuan', 'haritgl', 'jam', 'evaluasi_soap']);

    $data = [

            'namakk'        => $namakk,
            'tglpengkajian' => $tglpengkajian,
            'umur'          => $umur,
            'alamat'        => $alamat,

            'diagnosa'     => $diagnosa,
            'kriteria'     => $kriteria,
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
    <?php include "keluarga/format_keluarga/tab.php"; ?>

    <section class="section dashboard">

        <?php include dirname(__DIR__, 2) . '/partials/notifikasi.php'; ?>
        <?php include dirname(__DIR__, 2) . '/partials/status_section.php'; ?>

        <form class="needs-validation" novalidate action="" method="POST">

            <!-- ===================== DIAGNOSA KEPERAWATAN PRIORITAS ===================== -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><strong>B. Diagnosa Keperawatan</strong></h5>

                    <table class="table table-bordered" id="tabel-diagnosa">
                        <thead>
                            <tr>
                                <th class="text-center" style="width:40px">No</th>
                                <th class="text-center">Masalah Keperawatan</th>
                                <th class="text-center">Nama Anggota Keluarga yang Sakit</th>
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

            <!-- ===================== KRITERIA ===================== -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><strong>Kriteria</strong></h5>

                    <table class="table table-bordered" id="tabel-kriteria">
                        <thead>
                            <tr>
                                <th class="text-center" style="width:40px">No</th>
                                <th class="text-center align-middle">Sifat Masalah (Bobot 1)</th>
                                <th class="text-center align-middle">Pembenaran</th>
                                <th class="text-center align-middle">Kemungkinan Masalah Dapat diubah (Bobot 2)</th>
                                <th class="text-center align-middle">Pembenaran</th>
                                <th class="text-center align-middle">Potensial Masalah Dapat dicegah (Bobot 1)</th>
                                <th class="text-center align-middle">Pembenaran</th>
                                <th class="text-center align-middle">Menonjolnya Masalah (Bobot 1)</th>
                                <th class="text-center align-middle">Pembenaran</th>
                                <th class="text-center align-middle">Jumlah Skoring</th>
                                <?php if (!$is_readonly): ?>
                                    <th class="text-center" style="width:60px">Aksi</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody id="tbody-kriteria"></tbody>
                    </table>

                    <?php if (!$is_readonly): ?>
                        <div class="row mb-3">
                            <div class="col-sm-12 d-flex justify-content-end">
                                <button type="button" class="btn btn-primary btn-sm"
                                    onclick="tambahRowKriteria()">+ Tambah Baris</button>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- ===================== RENCANA KEPERAWATAN ===================== -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><strong>C. Rencana Keperawatan</strong></h5>

                    <!-- Nama KK -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Nama KK</strong></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="namakk"
                            value="<?= val('namakk', $existing_data) ?>" <?= $ro ?>></div>
                    </div>

                    <!-- Tanggal Pengkajian -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Tanggal Pengkajian</strong></label>
                        <div class="col-sm-10">
                            <input type="datetime-local" class="form-control" name="tglpengkajian"
                            value="<?= val('tglpengkajian', $existing_data) ?>" <?= $ro ?>></div>
                    </div>

                    <!-- Umur -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Umur</strong></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="umur"
                            value="<?= val('umur', $existing_data) ?>" <?= $ro ?>></div>
                    </div>

                    <!-- Alamat -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Alamat</strong></label>
                        <div class="col-sm-10">
                            <textarea name="alamat" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('alamat',$existing_data) ?></textarea></div>
                    </div>

                    <table class="table table-bordered" id="tabel-rencana">
                        <thead>
                            <tr>
                                <th class="text-center" style="width:40px">No</th>
                                <th class="text-center align-middle">Diagnosa Keperawatan</th>
                                <th class="text-center align-middle">Tujuan Jangka Panjang (Umum)</th>
                                <th class="text-center align-middle">Tujuan Jangka Pendek (Khusus)</th>
                                <th class="text-center align-middle">Kriteria</th>
                                <th class="text-center align-middle">Standar</th>
                                <th class="text-center align-middle">Intervensi</th>
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
                    <h5 class="card-title"><strong>D. Implementasi Keperawatan</strong></h5>

                    <table class="table table-bordered" id="tabel-implementasi">
                        <thead>
                            <tr>
                                <th class="text-center">Pertemuan</th>
                                <th class="text-center">Hari/Tgl</th>
                                <th class="text-center">Jam</th>
                                <th class="text-center">Implementasi</th>
                                <th class="text-center">Hasil</th>
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
                    <h5 class="card-title"><strong>E. Evaluasi Keperawatan</strong></h5>

                    <table class="table table-bordered" id="tabel-evaluasi">
                        <thead>
                            <tr>
                                <th class="text-center" style="width:60px">Pertemuan</th>
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
    const existingKriteria = <?= json_encode($existing_kriteria) ?>;
    const existingRencana = <?= json_encode($existing_rencana) ?>;
    const existingImplementasi = <?= json_encode($existing_implementasi) ?>;
    const existingEvaluasi = <?= json_encode($existing_evaluasi) ?>;

    let rowDiagnosaCount = 1;
    let rowKriteriaCount = 1;
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

    function autoResizeTextarea(el) {
        el.style.height = 'auto';
        el.style.height = el.scrollHeight + 'px';
    }

    function tambahRowDiagnosa(data = null) {
        const tbody = document.getElementById('tbody-diagnosa');
        const i = rowDiagnosaCount;
        const row = document.createElement('tr');

        row.innerHTML = `
            <td class="text-center align-middle">${i}</td>

            <td>
                ${
                    isReadonly
                        ? `<div class="readonly-text">${data?.masalahkeperawatan ?? ''}</div>`
                        : `<textarea
                                class="form-control form-control-sm auto-resize"
                                name="diagnosa[${i}][masalahkeperawatan]"
                                rows="2"
                                style="resize:none; overflow:hidden;"
                                oninput="autoResizeTextarea(this)"
                            >${data?.masalahkeperawatan ?? ''}</textarea>`
                }
            </td>

            <td>
                ${
                    isReadonly
                        ? `<div class="readonly-text">${data?.keluargayangsakit ?? ''}</div>`
                        : `<textarea
                                class="form-control form-control-sm auto-resize"
                                name="diagnosa[${i}][keluargayangsakit]"
                                rows="2"
                                style="resize:none; overflow:hidden;"
                                oninput="autoResizeTextarea(this)"
                            >${data?.keluargayangsakit ?? ''}</textarea>`
                }
            </td>

            ${aksiCol}
        `;

        tbody.appendChild(row);

        // Auto resize textarea yang baru ditambahkan
        row.querySelectorAll('.auto-resize').forEach(autoResizeTextarea);

        rowDiagnosaCount++;
    }

    // =============================================
    // KRITERIA
    // =============================================

    function autoResizeTextarea(el) {
        el.style.height = 'auto';
        el.style.height = el.scrollHeight + 'px';
    }

    function tambahRowKriteria(data = null) {
                            const tbody = document.getElementById('tbody-kriteria');
                            const index = rowKriteriaCount;
                            const row = document.createElement('tr');
                            const isReadonly = <?= json_encode($is_readonly) ?>;
                            row.innerHTML = `
                                <td class="text-center align-middle">${index}</td>
                                <td>
                                    <select
                                        class="form-control form-control-sm"
                                        style="
                                            min-height: 50px;
                                            white-space: normal;
                                            word-wrap: break-word;
                                        "
                                        name="kriteria[${index}][sifatmasalah]"
                                        ${isReadonly ? 'disabled' : ''}
                                    >
                                        <option value="">Pilih</option>
                                        <option value="Actual (3)" ${data?.sifatmasalah === 'Actual (3)' ? 'selected' : ''}>
                                            Actual (3)
                                        </option>
                                        <option value="Resiko (2)" ${data?.sifatmasalah === 'Resiko (2)' ? 'selected' : ''}>
                                            Resiko (2)
                                        </option>
                                        <option value="Sejahtera (1)" ${data?.sifatmasalah === 'Sejahtera (1)' ? 'selected' : ''}>
                                            Sejahtera (1)
                                        </option>
                                    </select>
                                </td>
                                ${
                                    isReadonly
                                        ? `<div class="readonly-text" style="white-space: pre-wrap;">${data?.pembenaransifatmasalah ?? ''}</div>`
                                        : `<textarea
                                                class="form-control form-control-sm auto-resize"
                                                name="kriteria[${index}][pembenaransifatmasalah]"
                                                rows="2"
                                                style="resize:none; overflow:hidden;"
                                                oninput="autoResizeTextarea(this)"
                                            >${data?.pembenaransifatmasalah ?? ''}</textarea>`
                                }
                                <td>
                                    <select
                                        class="form-control form-control-sm"
                                        style="
                                            min-height: 50px;
                                            white-space: normal;
                                        "
                                        name="kriteria[${index}][masalahdapatdiubah]"
                                        ${isReadonly ? 'disabled' : ''}
                                    >
                                        <option value="">Pilih</option>
                                        <option value="Mudah (2)" ${data?.masalahdapatdiubah === 'Mudah (2)' ? 'selected' : ''}>
                                            Mudah (2)
                                        </option>
                                        <option value="Sebagian (1)" ${data?.masalahdapatdiubah === 'Sebagian (1)' ? 'selected' : ''}>
                                            Sebagian (1)
                                        </option>
                                        <option value="Tidak Dapat (0)" ${data?.masalahdapatdiubah === 'Tidak Dapat (0)' ? 'selected' : ''}>
                                            Tidak Dapat (0)
                                        </option>
                                    </select>
                                </td>
                                ${
                                    isReadonly
                                        ? `<div class="readonly-text" style="white-space: pre-wrap;">${data?.pembenaranmasalahdapatdiubah ?? ''}</div>`
                                        : `<textarea
                                                class="form-control form-control-sm auto-resize"
                                                name="kriteria[${index}][pembenaranmasalahdapatdiubah]"
                                                rows="2"
                                                style="resize:none; overflow:hidden;"
                                                oninput="autoResizeTextarea(this)"
                                            >${data?.pembenaranmasalahdapatdiubah ?? ''}</textarea>`
                                }
                                <td>
                                    <select
                                        class="form-control form-control-sm"
                                        style="
                                            min-height: 50px;
                                            white-space: normal;
                                        "
                                        name="kriteria[${index}][masalahdapatdicegah]"
                                        ${isReadonly ? 'disabled' : ''}
                                    >
                                        <option value="">Pilih</option>
                                        <option value="Tinggi (3)" ${data?.masalahdapatdicegah === 'Tinggi (3)' ? 'selected' : ''}>
                                            Tinggi (3)
                                        </option>
                                        <option value="Cukup (2)" ${data?.masalahdapatdicegah === 'Cukup (2)' ? 'selected' : ''}>
                                            Cukup (2)
                                        </option>
                                        <option value="Rendah (1)" ${data?.masalahdapatdicegah === 'Rendah (1)' ? 'selected' : ''}>
                                            Rendah (1)
                                        </option>
                                    </select>
                                </td>
                                <td>
                                ${
                                    isReadonly
                                        ? `<div class="readonly-text" style="white-space: pre-wrap;">${data?.pembenaranmasalahdapatdicegah ?? ''}</div>`
                                        : `<textarea
                                                class="form-control form-control-sm auto-resize"
                                                name="kriteria[${index}][pembenaranmasalahdapatdicegah]"
                                                rows="2"
                                                style="resize:none; overflow:hidden;"
                                                oninput="autoResizeTextarea(this)"
                                            >${data?.pembenaranmasalahdapatdicegah ?? ''}</textarea>`
                                }
                               </td>            
                               <td>
                                    <select
                                        class="form-control form-control-sm"
                                        style="
                                            min-height: 50px;
                                            white-space: normal;
                                            word-wrap: break-word;
                                        "
                                        name="kriteria[${index}][menonjolnyamasalah]"
                                        ${isReadonly ? 'disabled' : ''}
                                    >
                                        <option value="">Pilih</option>
                                        <option value="Masalah dirasakan dan harus segera ditangani (2)" ${data?.menonjolnyamasalah === 'Masalah dirasakan dan harus segera ditangani (2)' ? 'selected' : ''}>
                                            Masalah dirasakan dan harus segera ditangani (2)
                                        </option>
                                        <option value="Ada masalah tidak segera ditangani (1)" ${data?.menonjolnyamasalah === 'Ada masalah tidak segera ditangani (1)' ? 'selected' : ''}>
                                            Ada masalah tidak segera ditangani (1)
                                        </option>
                                        <option value="Tidak dirasakan (0)" ${data?.menonjolnyamasalah === 'Tidak dirasakan (0)' ? 'selected' : ''}>
                                            Tidak dirasakan (0)
                                        </option>
                                    </select>
                                </td>
                                <td>
                                 ${
                                    isReadonly
                                        ? `<div class="readonly-text" style="white-space: pre-wrap;">${data?.pembenaranmenonjolnyamasalah ?? ''}</div>`
                                        : `<textarea
                                                class="form-control form-control-sm auto-resize"
                                                name="kriteria[${index}][pembenaranmenonjolnyamasalah]"
                                                rows="2"
                                                style="resize:none; overflow:hidden;"
                                                oninput="autoResizeTextarea(this)"
                                            >${data?.pembenaranmenonjolnyamasalah ?? ''}</textarea>`
                                }
                                </td>
                                <td>
                                    <textarea
                                        class="form-control form-control-sm"
                                        name="kriteria[${index}][jumlahskoring]"
                                        rows="2"
                                        style="resize:none; overflow:hidden;"
                                        oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                        ${isReadonly ? 'readonly' : ''}
                                    >${data?.jumlahskoring ?? ''}</textarea>
                                </td>
                                <td class="text-center align-middle">
                                    ${!isReadonly ? `<button type="button" class="btn btn-danger btn-sm" onclick="hapusRow(this)">x</button>` : ''}
                                </td>
                            `;
                            tbody.appendChild(row);

                            // Auto resize textarea yang baru ditambahkan
                            row.querySelectorAll('.auto-resize').forEach(autoResizeTextarea);

                            rowKriteriaCount++;
                        }

    // =============================================
    // RENCANA KEPERAWATAN
    // =============================================
   
    function autoResizeTextarea(el) {
        el.style.height = 'auto';
        el.style.height = el.scrollHeight + 'px';
    }

    function tambahRowRencana(data = null) {
        const tbody = document.getElementById('tbody-rencana');
        const i = rowRencanaCount;

        const row = document.createElement('tr');

        row.innerHTML = `
            <td class="text-center align-middle">${i}</td>

            <td>
                ${
                    isReadonly
                        ? `<div class="readonly-text">${data?.diagnosakeperawatan ?? ''}</div>`
                        : `<textarea
                                class="form-control form-control-sm auto-resize"
                                name="rencana[${i}][diagnosakeperawatan]"
                                rows="2"
                                style="resize:none; overflow:hidden;"
                                oninput="autoResizeTextarea(this)"
                            >${data?.diagnosakeperawatan ?? ''}</textarea>`
                }
            </td>

            <td>
                ${
                    isReadonly
                        ? `<div class="readonly-text">${data?.tujuanjangkapanjang ?? ''}</div>`
                        : `<textarea
                                class="form-control form-control-sm auto-resize"
                                name="rencana[${i}][tujuanjangkapanjang]"
                                rows="2"
                                style="resize:none; overflow:hidden;"
                                oninput="autoResizeTextarea(this)"
                            >${data?.tujuanjangkapanjang ?? ''}</textarea>`
                }
            </td>

            <td>
                ${
                    isReadonly
                        ? `<div class="readonly-text">${data?.tujuanjangkapendek ?? ''}</div>`
                        : `<textarea
                                class="form-control form-control-sm auto-resize"
                                name="rencana[${i}][tujuanjangkapendek]"
                                rows="2"
                                style="resize:none; overflow:hidden;"
                                oninput="autoResizeTextarea(this)"
                            >${data?.tujuanjangkapendek ?? ''}</textarea>`
                }
            </td>

            <td>
                ${
                    isReadonly
                        ? `<div class="readonly-text">${data?.kriteria ?? ''}</div>`
                        : `<textarea
                                class="form-control form-control-sm auto-resize"
                                name="rencana[${i}][kriteria]"
                                rows="2"
                                style="resize:none; overflow:hidden;"
                                oninput="autoResizeTextarea(this)"
                            >${data?.kriteria ?? ''}</textarea>`
                }
            </td>

            <td>
                ${
                    isReadonly
                        ? `<div class="readonly-text">${data?.standar ?? ''}</div>`
                        : `<textarea
                                class="form-control form-control-sm auto-resize"
                                name="rencana[${i}][standar]"
                                rows="2"
                                style="resize:none; overflow:hidden;"
                                oninput="autoResizeTextarea(this)"
                            >${data?.standar ?? ''}</textarea>`
                }
            </td>

            <td>
                ${
                    isReadonly
                        ? `<div class="readonly-text">${data?.intervensi ?? ''}</div>`
                        : `<textarea
                                class="form-control form-control-sm auto-resize"
                                name="rencana[${i}][intervensi]"
                                rows="2"
                                style="resize:none; overflow:hidden;"
                                oninput="autoResizeTextarea(this)"
                            >${data?.intervensi ?? ''}</textarea>`
                }
            </td>

            ${aksiCol}
        `;

        tbody.appendChild(row);

        // Auto resize textarea yang baru dibuat
        row.querySelectorAll('.auto-resize').forEach(autoResizeTextarea);

        rowRencanaCount++;
    }

    // =============================================
    // IMPLEMENTASI
    // =============================================
    function autoResizeTextarea(el) {
    el.style.height = 'auto';
    el.style.height = el.scrollHeight + 'px';
    }                    

    function tambahRowImplementasi(data = null) {
        const tbody = document.getElementById('tbody-implementasi');
        const i = rowImplementasiCount;

        const row = document.createElement('tr');

        row.innerHTML = `
            <td>${mkInput(`implementasi[${i}][pertemuan]`, data?.pertemuan)}</td>

            <td>${mkInput(`implementasi[${i}][haritgl]`, data?.haritgl, 'date')}</td>

            <td>${mkInput(`implementasi[${i}][jam]`, data?.jam, 'time')}</td>

            <td>
                ${
                    isReadonly
                        ? `<div class="readonly-text">${data?.implementasi ?? ''}</div>`
                        : `<textarea
                                class="form-control form-control-sm auto-resize"
                                name="implementasi[${i}][implementasi]"
                                rows="2"
                                style="resize:none; overflow:hidden;"
                                oninput="autoResizeTextarea(this)"
                            >${data?.implementasi ?? ''}</textarea>`
                }
            </td>

            <td>
                ${
                    isReadonly
                        ? `<div class="readonly-text">${data?.hasil ?? ''}</div>`
                        : `<textarea
                                class="form-control form-control-sm auto-resize"
                                name="implementasi[${i}][hasil]"
                                rows="2"
                                style="resize:none; overflow:hidden;"
                                oninput="autoResizeTextarea(this)"
                            >${data?.hasil ?? ''}</textarea>`
                }
            </td>

            ${aksiCol}
        `;

        tbody.appendChild(row);

        // Auto resize textarea yang baru dibuat
        row.querySelectorAll('.auto-resize').forEach(autoResizeTextarea);

        rowImplementasiCount++;
    }

    // =============================================
    // EVALUASI
    // =============================================

   function autoResizeTextarea(el) {
    el.style.height = 'auto';
    el.style.height = el.scrollHeight + 'px';
    }

    function tambahRowEvaluasi(data = null) {
        const tbody = document.getElementById('tbody-evaluasi');
        const i = rowEvaluasiCount;
        const row = document.createElement('tr');

        const soapVal = data?.evaluasi_soap ?? '';
        const soapPlaceholder = 'S:\nO:\nA:\nP:';

        row.innerHTML = `
            <td>${mkInput(`evaluasi[${i}][pertemuan]`, data?.pertemuan)}</td>
            <td>${mkInput(`evaluasi[${i}][haritgl]`, data?.haritgl, 'date')}</td>
            <td>${mkInput(`evaluasi[${i}][jam]`, data?.jam, 'time')}</td>

            <td>
                ${
                    isReadonly
                        ? `<div class="readonly-text" style="white-space: pre-wrap;">${soapVal}</div>`
                        : `<textarea
                                class="form-control form-control-sm auto-resize"
                                name="evaluasi[${i}][evaluasi_soap]"
                                rows="4"
                                style="resize:none; overflow:hidden; font-family:monospace;"
                                placeholder="${soapPlaceholder}"
                                oninput="autoResizeTextarea(this)"
                            >${soapVal}</textarea>`
                }
            </td>

            ${aksiCol}
        `;

        tbody.appendChild(row);

        row.querySelectorAll('.auto-resize').forEach(autoResizeTextarea);

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

        // Kriteria
        if (existingKriteria && existingKriteria.length > 0) {
            existingKriteria.forEach(row => tambahRowKriteria(row));
        } else if (!isReadonly) {
            tambahRowKriteria();
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