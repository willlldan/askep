<?php
$form_id       = 12;
$section_name  = 'program_terapi_lab';
$section_label = 'Program Terapi dan Laboratorium';
include dirname(__DIR__, 2) . '/partials/init_section.php';

$existing_obat        = $existing_data['obat'] ?? [];
$existing_lab         = $existing_data['lab'] ?? [];
$existing_klasifikasi = $existing_data['klasifikasi'] ?? [];
$existing_analisa     = $existing_data['analisa'] ?? [];

$is_dosen    = $level === 'Dosen';
$is_readonly = $is_dosen || isLocked($submission);
$ro          = $is_readonly ? 'readonly' : '';
$ro_select   = $is_readonly ? 'disabled' : '';

// ===================== POST HANDLER =====================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $level === 'Mahasiswa') {
    if (isLocked($submission)) redirectWithMessage($_SERVER['REQUEST_URI'], 'error', 'Data tidak dapat diubah karena sedang dalam proses review.');

    // OBAT
    $obat = [];
    if (!empty($_POST['obat'])) {
        foreach ($_POST['obat'] as $row) {
            if (empty($row['jenis_obat']) && empty($row['dosis']) && empty($row['kegunaan'])) continue;
            $obat[] = [
                'jenis_obat'     => $row['jenis_obat'] ?? '',
                'dosis'          => $row['dosis'] ?? '',
                'kegunaan'       => $row['kegunaan'] ?? '',
                'cara_pemberian' => $row['cara_pemberian'] ?? '',
            ];
        }
    }

    // LAB
    $lab = [];
    if (!empty($_POST['lab'])) {
        foreach ($_POST['lab'] as $row) {
            if (empty($row['pemeriksaan']) && empty($row['hasil']) && empty($row['nilai_normal'])) continue;
            $lab[] = [
                'pemeriksaan'  => $row['pemeriksaan'] ?? '',
                'hasil'        => $row['hasil'] ?? '',
                'nilai_normal' => $row['nilai_normal'] ?? '',
            ];
        }
    }

    // KLASIFIKASI
    $klasifikasi = [];
    if (!empty($_POST['klasifikasi'])) {
        foreach ($_POST['klasifikasi'] as $row) {
            if (empty($row['data_subjektif']) && empty($row['data_objektif'])) continue;
            $klasifikasi[] = [
                'data_subjektif' => $row['data_subjektif'] ?? '',
                'data_objektif'  => $row['data_objektif'] ?? '',
            ];
        }
    }

    // ANALISA
    $analisa = [];
    if (!empty($_POST['analisa'])) {
        foreach ($_POST['analisa'] as $row) {
            if (empty($row['ds_do']) && empty($row['etiologi']) && empty($row['masalah'])) continue;
            $analisa[] = [
                'ds_do'    => $row['ds_do'] ?? '',
                'etiologi' => $row['etiologi'] ?? '',
                'masalah'  => $row['masalah'] ?? '',
            ];
        }
    }

    $data = [
        'obat'        => $obat,
        'lab'         => $lab,
        'klasifikasi' => $klasifikasi,
        'analisa'     => $analisa,
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
        <?php include dirname(__DIR__, 2) . '/partials/notifikasi.php'; ?>
        <?php include dirname(__DIR__, 2) . '/partials/status_section.php'; ?>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><strong>PROGRAM TERAPI & LABORATORIUM</strong></h5>
                <form class="needs-validation" novalidate action="" method="POST">
                    <!-- ===================== TABEL OBAT ===================== -->
                    <p class="text-primary fw-bold mb-2">Obat-obatan yang Dikonsumsi Saat Ini</p>
                    <table class="table table-bordered" id="tabel-obat">
                        <thead>
                            <tr>
                                <th class="text-center" style="width:40px">No</th>
                                <th class="text-center">Jenis Obat</th>
                                <th class="text-center">Dosis</th>
                                <th class="text-center">Kegunaan</th>
                                <th class="text-center">Cara Pemberian</th>
                                <th class="text-center" style="width:60px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="tbody-obat">
                            <!-- Dynamic rows masuk sini -->
                        </tbody>
                    </table>
                    <div class="row mb-4">
                        <div class="col-sm-12 d-flex justify-content-end">
                            <button type="button" class="btn btn-primary btn-sm" id="btn-tambah-obat" onclick="tambahRowObat()">+ Tambah Obat</button>
                        </div>
                    </div>
                    <!-- ===================== TABEL LAB ===================== -->
                    <p class="text-primary fw-bold mb-2">Hasil Pemeriksaan Penunjang dan Laboratorium</p>
                    <table class="table table-bordered" id="tabel-lab">
                        <thead>
                            <tr>
                                <th class="text-center" style="width:40px">No</th>
                                <th class="text-center">Pemeriksaan</th>
                                <th class="text-center">Hasil</th>
                                <th class="text-center">Nilai Normal</th>
                                <th class="text-center" style="width:60px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="tbody-lab">
                            <!-- Dynamic rows masuk sini -->
                        </tbody>
                    </table>
                    <div class="row mb-4">
                        <div class="col-sm-12 d-flex justify-content-end">
                            <button type="button" class="btn btn-primary btn-sm" id="btn-tambah-lab" onclick="tambahRowLab()">+ Tambah Pemeriksaan</button>
                        </div>
                    </div>
                    <!-- ===================== TABEL LAB ===================== -->
                    <p class="text-primary fw-bold mb-2">Klasifikasi Data</p>
                    <table class="table table-bordered" id="tabel-klasifikasi_data">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Data Subjektif (DS)</th>
                                <th class="text-center">Data Objektif (DO)</th>
                                <th class="text-center" style="width:60px">Aksi</th>

                            </tr>
                        </thead>
                        <tbody id="tbody-klasifikasi">
                            <!-- Dynamic rows masuk sini -->
                        </tbody>
                    </table>
                    <div class="row mb-4">
                        <div class="col-sm-12 d-flex justify-content-end">
                            <button type="button" class="btn btn-primary btn-sm" id="btn-tambah-klasifikasi" onclick="tambahRowKlasifikasi()">+ Tambah data</button>
                        </div>
                    </div>
                    <!-- ===================== TABEL LAB ===================== -->
                    <p class="text-primary fw-bold mb-2">Analisa Data</p>
                    <table class="table table-bordered" id="tabel-analisa">
                        <thead>
                            <tr>
                                <th class="text-center" style="width:40px">NO</th>
                                <th class="text-center">DS/DO</th>
                                <th class="text-center">Etiologi</th>
                                <th class="text-center">Masalah</th>
                                <th class="text-center" style="width:60px">Aksi</th>

                            </tr>
                        </thead>
                        <tbody id="tbody-analisa">
                            <!-- Dynamic rows masuk sini -->
                        </tbody>
                    </table>
                    <div class="row mb-4">
                        <div class="col-sm-12 d-flex justify-content-end">
                            <button type="button" class="btn btn-primary btn-sm" id="btn-tambah-analisa" onclick="tambahRowAnalisa()">+ Tambah data</button>
                        </div>
                    </div>
                    <!-- TOMBOL SIMPAN -->
                    <?php if (!$is_dosen): ?>
                        <div class="row mb-3">
                            <div class="col-sm-12 d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary" <?= $ro ?>>Simpan Data</button>
                            </div>
                        </div>
                    <?php endif; ?>
                    <script>
                        let rowObatCount = 1;
                        let rowLabCount = 1;
                        let rowKlasifikasiCount = 1;
                        let rowAnalisaCount = 1;

                        const existingObat = <?= json_encode($existing_obat) ?>;
                        const existingLab = <?= json_encode($existing_lab) ?>;
                        const existingKlasifikasi = <?= json_encode($existing_klasifikasi) ?>;
                        const existingAnalisa = <?= json_encode($existing_analisa) ?>;
                        const isReadonly = <?= json_encode($is_readonly) ?>;

                        // ---- OBAT ----

                         function autoResizeTextarea(el) {
                            el.style.height = 'auto';
                            el.style.height = el.scrollHeight + 'px';
                            }

                        function tambahRowObat(data = null) {
                            const tbody = document.getElementById('tbody-obat');
                            const index = rowObatCount++;
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td class="text-center">${index}</td>
                                <td>
                                    ${
                                    isReadonly
                                    ? `<div class="readonly-text">${data?.jenis_obat ?? ''}</div>`
                                    : `<textarea
                                    class="form-control form-control-sm auto-resize"
                                    name="obat[${index}][jenis_obat]"
                                    rows="2"
                                    style="resize:none; overflow:hidden;"
                                    oninput="autoResizeTextarea(this)"
                                    >${data?.jenis_obat ?? ''}</textarea>`
                                    }
                                </td>
                                <td>
                                    ${
                                    isReadonly
                                    ? `<div class="readonly-text">${data?.dosis ?? ''}</div>`
                                    : `<textarea
                                    class="form-control form-control-sm auto-resize"
                                    name="obat[${index}][dosis]"
                                    rows="2"
                                    style="resize:none; overflow:hidden;"
                                    oninput="autoResizeTextarea(this)"
                                    >${data?.dosis ?? ''}</textarea>`
                                    }
                                </td>
                                <td>
                                    ${
                                    isReadonly
                                    ? `<div class="readonly-text">${data?.kegunaan ?? ''}</div>`
                                    : `<textarea
                                    class="form-control form-control-sm auto-resize"
                                    name="obat[${index}][kegunaan]"
                                    rows="2"
                                    style="resize:none; overflow:hidden;"
                                    oninput="autoResizeTextarea(this)"
                                    >${data?.kegunaan ?? ''}</textarea>`
                                    }
                                </td>
                                <td>
                                    ${
                                    isReadonly
                                    ? `<div class="readonly-text">${data?.cara_pemberian ?? ''}</div>`
                                    : `<textarea
                                    class="form-control form-control-sm auto-resize"
                                    name="obat[${index}][cara_pemberian]"
                                    rows="2"
                                    style="resize:none; overflow:hidden;"
                                    oninput="autoResizeTextarea(this)"
                                    >${data?.cara_pemberian ?? ''}</textarea>`
                                    }
                                </td>
                               <td><button type="button" class="btn btn-danger btn-sm" onclick="hapusRow(this)" ${isReadonly?'disabled':''}>x</button></td>
                            `;
                            tbody.appendChild(row);

                            row.querySelectorAll('.auto-resize').forEach(autoResizeTextarea);
                        }

                        // ---- LAB ----
                        function tambahRowLab(data = null) {
                            const tbody = document.getElementById('tbody-lab');
                            const index = rowLabCount++;
                            const row = document.createElement('tr');
                            row.innerHTML = `
                            <td class="text-center">${index}</td>
                            <td>
                                ${
                                isReadonly
                                ? `<div class="readonly-text">${data?.pemeriksaan ?? ''}</div>`
                                : `<textarea
                                class="form-control form-control-sm auto-resize"
                                name="lab[${index}][pemeriksaan]"
                                rows="2"
                                style="resize:none; overflow:hidden;"
                                oninput="autoResizeTextarea(this)"
                                >${data?.pemeriksaan ?? ''}</textarea>`
                                }
                            </td>
                            <td>
                                ${
                                isReadonly
                                ? `<div class="readonly-text">${data?.hasil ?? ''}</div>`
                                : `<textarea
                                class="form-control form-control-sm auto-resize"
                                name="lab[${index}][hasil]"
                                rows="2"
                                style="resize:none; overflow:hidden;"
                                oninput="autoResizeTextarea(this)"
                                >${data?.hasil ?? ''}</textarea>`
                                }
                            </td>
                            <td>
                                ${
                                isReadonly
                                ? `<div class="readonly-text">${data?.nilai_normal ?? ''}</div>`
                                : `<textarea
                                class="form-control form-control-sm auto-resize"
                                name="lab[${index}][nilai_normal]"
                                rows="2"
                                style="resize:none; overflow:hidden;"
                                oninput="autoResizeTextarea(this)"
                                >${data?.nilai_normal ?? ''}</textarea>`
                                }
                            </td>
                           <td><button type="button" class="btn btn-danger btn-sm" onclick="hapusRow(this)" ${isReadonly?'disabled':''}>x</button></td>
                        `;
                            tbody.appendChild(row);

                            row.querySelectorAll('.auto-resize').forEach(autoResizeTextarea);
                        }

                        // ---- KLASIFIKASI ----
                        function tambahRowKlasifikasi(data = null) {
                            const tbody = document.getElementById('tbody-klasifikasi');
                            const index = rowKlasifikasiCount++;
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td class="text-center">${index}</td>
                                <td>
                                    ${
                                    isReadonly
                                    ? `<div class="readonly-text">${data?.data_subjektif ?? ''}</div>`
                                    : `<textarea
                                    class="form-control form-control-sm auto-resize"
                                    name="klasifikasi[${index}][data_subjektif]"
                                    rows="2"
                                    style="resize:none; overflow:hidden;"
                                    oninput="autoResizeTextarea(this)"
                                    >${data?.data_subjektif ?? ''}</textarea>`
                                    }
                                </td>
                                <td>
                                    ${
                                    isReadonly
                                    ? `<div class="readonly-text">${data?.data_objektif ?? ''}</div>`
                                    : `<textarea
                                    class="form-control form-control-sm auto-resize"
                                    name="klasifikasi[${index}][data_objektif]"
                                    rows="2"
                                    style="resize:none; overflow:hidden;"
                                    oninput="autoResizeTextarea(this)"
                                    >${data?.data_objektif ?? ''}</textarea>`
                                    }
                                </td>
                                <td><button type="button" class="btn btn-danger btn-sm" onclick="hapusRow(this)" ${isReadonly?'disabled':''}>x</button></td>
                            `;
                            tbody.appendChild(row);

                            row.querySelectorAll('.auto-resize').forEach(autoResizeTextarea);
                        }

                        // ---- ANALISA ----
                        function tambahRowAnalisa(data = null) {
                            const tbody = document.getElementById('tbody-analisa');
                            const index = rowAnalisaCount++;
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td class="text-center">${index}</td>
                                <td>
                                    ${
                                    isReadonly
                                    ? `<div class="readonly-text">${data?.ds_do ?? ''}</div>`
                                    : `<textarea
                                    class="form-control form-control-sm auto-resize"
                                    name="analisa[${index}][ds_do]"
                                    rows="2"
                                    style="resize:none; overflow:hidden;"
                                    oninput="autoResizeTextarea(this)"
                                    >${data?.ds_do ?? ''}</textarea>`
                                    }
                                </td>
                                <td>
                                    ${
                                    isReadonly
                                    ? `<div class="readonly-text">${data?.etiologi ?? ''}</div>`
                                    : `<textarea
                                    class="form-control form-control-sm auto-resize"
                                    name="analisa[${index}][etiologi]"
                                    rows="2"
                                    style="resize:none; overflow:hidden;"
                                    oninput="autoResizeTextarea(this)"
                                    >${data?.etiologi ?? ''}</textarea>`
                                    }
                                </td>
                                <td>
                                    ${
                                    isReadonly
                                    ? `<div class="readonly-text">${data?.masalah ?? ''}</div>`
                                    : `<textarea
                                    class="form-control form-control-sm auto-resize"
                                    name="analisa[${index}][masalah]"
                                    rows="2"
                                    style="resize:none; overflow:hidden;"
                                    oninput="autoResizeTextarea(this)"
                                    >${data?.masalah ?? ''}</textarea>`
                                    }
                                </td>
                                <td><button type="button" class="btn btn-danger btn-sm" onclick="hapusRow(this)" ${isReadonly?'disabled':''}>x</button></td>
                            `;
                            tbody.appendChild(row);

                            row.querySelectorAll('.auto-resize').forEach(autoResizeTextarea);
                        }

                        // Hapus row
                        function hapusRow(btn) {
                            btn.closest('tr').remove();
                        }

                        // Load existing rows
                        window.addEventListener('load', () => {
                            existingObat.length > 0 ? existingObat.forEach(r => tambahRowObat(r)) : tambahRowObat();
                            existingLab.length > 0 ? existingLab.forEach(r => tambahRowLab(r)) : tambahRowLab();
                            existingKlasifikasi.length > 0 ? existingKlasifikasi.forEach(r => tambahRowKlasifikasi(r)) : tambahRowKlasifikasi();
                            existingAnalisa.length > 0 ? existingAnalisa.forEach(r => tambahRowAnalisa(r)) : tambahRowAnalisa();
                        });
                    </script>
                </form>

            </div>
        </div>
        <?php include dirname(__DIR__, 2) . '/partials/footer_form.php'; ?>
    </section>
</main>