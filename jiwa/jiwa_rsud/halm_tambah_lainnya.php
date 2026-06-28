<?php
$form_id       = 7;
$section_name  = 'lainnya';
$section_label = 'lainnya';
include dirname(__DIR__, 2) . '/partials/init_section.php';

// Load existing dynamic rows
$existing_diagnosa     = $existing_data['diagnosa']     ?? [];
$existing_intervensi   = $existing_data['intervensi']   ?? [];
$existing_implementasi = $existing_data['implementasi'] ?? [];
$existing_evaluasi     = $existing_data['evaluasi']     ?? [];

// POST handler
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $level === 'Mahasiswa') {
    if (isLocked($submission)) {
        redirectWithMessage($_SERVER['REQUEST_URI'], 'error', 'Data tidak dapat diubah karena sedang dalam proses review.');
    }

    // Proses dynamic rows diagnosa
    $diagnosa = [];
    if (!empty($_POST['diagnosa'])) {
        foreach ($_POST['diagnosa'] as $index => $row) {
            if (empty($row['diagnosa'])) {
                continue;
            }
            $diagnosa[] = [
                'diagnosa'      => $row['diagnosa']      ?? '',

            ];
        }
    }

    // Proses dynamic rows intervensi
    $intervensi = [];
    if (!empty($_POST['intervensi'])) {
        foreach ($_POST['intervensi'] as $index => $row) {
            if (empty($row['diagnosa']) && empty($row['tujuan']) && empty($row['intervensi'])) {
                continue;
            }
            $intervensi[] = [
                'diagnosa'        => $row['diagnosa']        ?? '',
                'tujuan' => $row['tujuan'] ?? '',
                'kriteria' => $row['kriteria'] ?? '',
                'intervensi'      => $row['intervensi']      ?? '',
            ];
        }
    }

    // Proses dynamic rows implementasi
    $implementasi = [];
    if (!empty($_POST['implementasi'])) {
        foreach ($_POST['implementasi'] as $index => $row) {
            if (empty($row['hari_tgl']) && empty($row['implementasi']) && empty($row['diagnosa'])) {
                continue;
            }
            $implementasi[] = [
                'hari_tgl'        => $row['hari_tgl']        ?? '',
                'implementasi'     => $row['implementasi']      ?? '',
                'diagnosa'          => $row['diagnosa']            ?? '',
                'evaluasi_s' => $row['evaluasi_s']  ?? '',
                'evaluasi_o' => $row['evaluasi_o']  ?? '',
                'evaluasi_a' => $row['evaluasi_a']  ?? '',
                'evaluasi_p' => $row['evaluasi_p']  ?? '',
            ];
        }
    }



    $data = [
        'diagnosa'     => $diagnosa,
        'intervensi'   => $intervensi,
        'implementasi' => $implementasi,

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

    <?php include "jiwa/jiwa_rsud/tab.php"; ?>

    <section class="section dashboard">

               <?php include dirname(__DIR__, 2) . '/partials/notifikasi.php'; ?>
        <?php include dirname(__DIR__, 2) . '/partials/status_section.php'; ?>
  <div class="card">
                <div class="card-body">
                <h5 class="card-title"><strong>Catatan KEPERAWATAN</strong></h5>

                <form class="needs-validation" novalidate action="" method="POST">

                    <!-- ===================== TABEL DIAGNOSA ===================== -->
                    <p class="text-primary fw-bold mb-2">Diagnosa Keperawatan</p>

                    <table class="table table-bordered" id="tabel-diagnosa">
                        <thead>
                            <tr>
                                <th class="text-center" style="width:40px">No</th>
                                <th class="text-center">Diagnosa Keperawatan</th>
                                <th class="text-center" style="width:60px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="tbody-diagnosa">
                            <!-- Dynamic rows masuk sini -->
                        </tbody>
                    </table>

                    <?php if (!$is_readonly): ?>
                        <div class="row mb-4">
                            <div class="col-sm-12 d-flex justify-content-end">
                                <button type="button" class="btn btn-primary btn-sm" onclick="tambahRowDiagnosa()">+ Tambah Diagnosa</button>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- ===================== TABEL INTERVENSI ===================== -->
                    <p class="text-primary fw-bold mb-2">Intervensi Keperawatan</p>

                    <table class="table table-bordered" id="tabel-intervensi">
                        <thead>
                            <tr>
                                <th class="text-center" style="width:40px">No</th>
                                <th class="text-center">Diagnosa Keperawatan</th>
                                <th class="text-center">Tujuan</th>
                                <th class="text-center">Kriteria Hasil </th>
                                <th class="text-center">Intervensi</th>
                                <th class="text-center" style="width:60px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="tbody-intervensi">
                            <!-- Dynamic rows masuk sini -->
                        </tbody>
                    </table>

                    <?php if (!$is_readonly): ?>
                        <div class="row mb-4">
                            <div class="col-sm-12 d-flex justify-content-end">
                                <button type="button" class="btn btn-primary btn-sm" onclick="tambahRowIntervensi()">+ Tambah Intervensi</button>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- ===================== TABEL IMPLEMENTASI ===================== -->
                    <p class="text-primary fw-bold mb-2">Implementasi dan Evaluasi</p>

                    <table class="table table-bordered" id="tabel-implementasi">
                        <thead>
                            <tr>
                                <th class="text-center" style="width:40px">No</th>
                                <th class="text-center" style="width:150px">Hari/Tanggal/Jam</th>
                                <th class="text-center" style="width:110px">Diagnosa Keperawatan</th>
                                <th class="text-center">Implementasi</th>
                                <th class="text-center">Evaluasi (SOAP)</th>
                                <th class="text-center" style="width:60px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="tbody-implementasi">
                            <!-- Dynamic rows masuk sini -->
                        </tbody>
                    </table>

                    <?php if (!$is_readonly): ?>
                        <div class="row mb-4">
                            <div class="col-sm-12 d-flex justify-content-end">
                                <button type="button" class="btn btn-primary btn-sm" onclick="tambahRowImplementasi()">+ Tambah Implementasi</button>
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

                    <script>
                        let rowDiagnosaCount = 1;
                        let rowIntervensiCount = 1;
                        let rowImplementasiCount = 1;


                        const existingDiagnosa = <?= json_encode($existing_diagnosa) ?>;
                        const existingIntervensi = <?= json_encode($existing_intervensi) ?>;
                        const existingImplementasi = <?= json_encode($existing_implementasi) ?>;


                        // ---- DIAGNOSA ----
                        function tambahRowDiagnosa(data = null) {
                            const tbody = document.getElementById('tbody-diagnosa');
                            const index = rowDiagnosaCount;
                            const row = document.createElement('tr');
                            const isReadonly = <?= json_encode($is_readonly) ?>;
                            row.innerHTML = `
                                <td class="text-center align-middle">${index}</td>
                                <td>
                                    <textarea
                                        class="form-control form-control-sm"
                                        name="diagnosa[${index}][diagnosa]"
                                        rows="2"
                                        style="resize:none; overflow:hidden;"
                                        oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                        ${isReadonly ? 'readonly' : ''}
                                    >${data?.diagnosa ?? ''}</textarea>
                                </td>
                              
                                <td class="text-center align-middle">
                                    ${!isReadonly ? `<button type="button" class="btn btn-danger btn-sm" onclick="hapusRow(this)">x</button>` : ''}
                                </td>
                            `;
                            tbody.appendChild(row);
                            rowDiagnosaCount++;
                        }

                        // ---- INTERVENSI ----
                        function tambahRowIntervensi(data = null) {
                            const tbody = document.getElementById('tbody-intervensi');
                            const index = rowIntervensiCount;
                            const row = document.createElement('tr');
                            const isReadonly = <?= json_encode($is_readonly) ?>;
                            row.innerHTML = `
                                <td class="text-center align-middle">${index}</td>
                                <td>
                                    <textarea
                                        class="form-control form-control-sm"
                                        name="intervensi[${index}][diagnosa]"
                                        rows="2"
                                        style="resize:none; overflow:hidden;"
                                        oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                        ${isReadonly ? 'readonly' : ''}
                                    >${data?.diagnosa ?? ''}</textarea>
                                </td>
                                <td>
                                    <textarea
                                        class="form-control form-control-sm"
                                        name="intervensi[${index}][tujuan]"
                                        rows="2"
                                        style="resize:none; overflow:hidden;"
                                        oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                        ${isReadonly ? 'readonly' : ''}
                                    >${data?.tujuan ?? ''}</textarea>
                                </td>
                                <td>
                                    <textarea
                                        class="form-control form-control-sm"
                                        name="intervensi[${index}][kriteria]"
                                        rows="2"
                                        style="resize:none; overflow:hidden;"
                                        oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                        ${isReadonly ? 'readonly' : ''}
                                    >${data?.kriteria ?? ''}</textarea>
                                </td>
                                <td>
                                    <textarea
                                        class="form-control form-control-sm"
                                        name="intervensi[${index}][intervensi]"
                                        rows="2"
                                        style="resize:none; overflow:hidden;"
                                        oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                        ${isReadonly ? 'readonly' : ''}
                                    >${data?.intervensi ?? ''}</textarea>
                                </td>
                                <td class="text-center align-middle">
                                    ${!isReadonly ? `<button type="button" class="btn btn-danger btn-sm" onclick="hapusRow(this)">x</button>` : ''}
                                </td>
                            `;
                            tbody.appendChild(row);
                            rowIntervensiCount++;
                        }

                        // ---- IMPLEMENTASI ----
                        function tambahRowImplementasi(data = null) {
                            const tbody = document.getElementById('tbody-implementasi');
                            const index = rowImplementasiCount;
                            const row = document.createElement('tr');
                            const isReadonly = <?= json_encode($is_readonly) ?>;
                            row.innerHTML = `
                             <td class="text-center align-middle">${index}</td>
                                <td>
                                    <input
                                        type="datetime-local"
                                        class="form-control form-control-sm"
                                        name="implementasi[${index}][hari_tgl]"
                                        value="${data?.hari_tgl ?? ''}"
                                        ${isReadonly ? 'readonly' : ''}
                                    >
                                </td>
                                <td>
                                    <input
                                        type="text"
                                        class="form-control form-control-sm"
                                        name="implementasi[${index}][diagnosa]"
                                        value="${data?.diagnosa ?? ''}"
                                        ${isReadonly ? 'readonly' : ''}
                                    >
                                </td>
                              
                                <td>
                                    <textarea
                                        class="form-control form-control-sm"
                                        name="implementasi[${index}][implementasi]"
                                        rows="2"
                                        style="resize:none; overflow:hidden;"
                                        oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                        ${isReadonly ? 'readonly' : ''}
                                    >${data?.implementasi ?? ''}</textarea>
                                </td>
                                 <td>
                                <div class="mb-1 d-flex align-items-start gap-2">
                                    <label class="form-label form-label-sm fw-bold mb-0" style="width:20px;">S</label>
                                    <textarea
                                    class="form-control form-control-sm"
                                    name="implementasi[${index}][evaluasi_s]"
                                    rows="2"
                                    style="resize:none; overflow:hidden;"
                                    oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                    ${isReadonly ? 'readonly' : ''}
                                    >${data?.evaluasi_s ?? ''}</textarea>
                                </div>

                                <div class="mb-1 d-flex align-items-start gap-2">
                                    <label class="form-label form-label-sm fw-bold mb-0" style="width:20px;">O</label>
                                    <textarea
                                    class="form-control form-control-sm"
                                    name="implementasi[${index}][evaluasi_o]"
                                    rows="2"
                                    style="resize:none; overflow:hidden;"
                                    oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                    ${isReadonly ? 'readonly' : ''}
                                    >${data?.evaluasi_o ?? ''}</textarea>
                                </div>

                                <div class="mb-1 d-flex align-items-start gap-2">
                                    <label class="form-label form-label-sm fw-bold mb-0" style="width:20px;">A</label>
                                    <textarea
                                    class="form-control form-control-sm"
                                    name="implementasi[${index}][evaluasi_a]"
                                    rows="2"
                                    style="resize:none; overflow:hidden;"
                                    oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                    ${isReadonly ? 'readonly' : ''}
                                    >${data?.evaluasi_a ?? ''}</textarea>
                                </div>

                                <div class="d-flex align-items-start gap-2">
                                    <label class="form-label form-label-sm fw-bold mb-0" style="width:20px;">P</label>
                                    <textarea
                                    class="form-control form-control-sm"
                                    name="implementasi[${index}][evaluasi_p]"
                                    rows="2"
                                    style="resize:none; overflow:hidden;"
                                    oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                    ${isReadonly ? 'readonly' : ''}
                                    >${data?.evaluasi_p ?? ''}</textarea>
                                </div>
                                </td>

                                <td class="text-center align-middle">
                                    ${!isReadonly ? `<button type="button" class="btn btn-danger btn-sm" onclick="hapusRow(this)">x</button>` : ''}
                                </td>
                            `;
                            tbody.appendChild(row);
                            rowImplementasiCount++;
                        }



                        function hapusRow(btn) {
                            btn.closest('tr').remove();
                        }

                        // Load existing rows on page load
                        window.addEventListener('load', function() {
                            if (existingDiagnosa && existingDiagnosa.length > 0) {
                                existingDiagnosa.forEach(row => tambahRowDiagnosa(row));
                            } else {
                                tambahRowDiagnosa();
                            }

                            if (existingIntervensi && existingIntervensi.length > 0) {
                                existingIntervensi.forEach(row => tambahRowIntervensi(row));
                            } else {
                                tambahRowIntervensi();
                            }

                            if (existingImplementasi && existingImplementasi.length > 0) {
                                existingImplementasi.forEach(row => tambahRowImplementasi(row));
                            } else {
                                tambahRowImplementasi();
                            }

                            if (existingEvaluasi && existingEvaluasi.length > 0) {
                                existingEvaluasi.forEach(row => tambahRowEvaluasi(row));
                            } else {
                                tambahRowEvaluasi();
                            }
                        });

                        const existingData = <?= json_encode($existing_data) ?>;
                    </script>


                </form>

                

            </div>
        </div>
        <?php include dirname(__DIR__, 2) . '/partials/footer_form.php'; ?>

    </section>
</main>