<?php
require_once "koneksi.php";
require_once "utils.php";

$form_id       = 1;
$user_id       = $_SESSION['id_user'];
$section_name  = 'catatan_keperawatan';
$section_label = 'Catatan Keperawatan';

$submission    = getSubmission($user_id, $form_id, $mysqli);
$existing_data = $submission ? getSectionData($submission['id'], $section_name, $mysqli) : [];

// Load existing dynamic rows
$existing_diagnosa     = $existing_data['diagnosa']     ?? [];
$existing_intervensi   = $existing_data['intervensi']   ?? [];
$existing_implementasi = $existing_data['implementasi'] ?? [];
$existing_evaluasi     = $existing_data['evaluasi']     ?? [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isLocked($submission)) {
        redirectWithMessage($_SERVER['REQUEST_URI'], 'error', 'Data tidak dapat diubah karena sedang dalam proses review.');
    }

    // Proses dynamic rows diagnosa
    $diagnosa = [];
    if (!empty($_POST['diagnosa'])) {
        foreach ($_POST['diagnosa'] as $index => $row) {
            if (empty($row['diagnosa']) && empty($row['tgl_ditemukan']) && empty($row['tgl_teratasi'])) {
                continue;
            }
            $diagnosa[] = [
                'diagnosa'      => $row['diagnosa']      ?? '',
                'tgl_ditemukan' => $row['tgl_ditemukan'] ?? '',
                'tgl_teratasi'  => $row['tgl_teratasi']  ?? '',
            ];
        }
    }

    // Proses dynamic rows intervensi
    $intervensi = [];
    if (!empty($_POST['intervensi'])) {
        foreach ($_POST['intervensi'] as $index => $row) {
            if (empty($row['diagnosa']) && empty($row['tujuan_kriteria']) && empty($row['intervensi'])) {
                continue;
            }
            $intervensi[] = [
                'diagnosa'        => $row['diagnosa']        ?? '',
                'tujuan_kriteria' => $row['tujuan_kriteria'] ?? '',
                'intervensi'      => $row['intervensi']      ?? '',
            ];
        }
    }

    // Proses dynamic rows implementasi
    $implementasi = [];
    if (!empty($_POST['implementasi'])) {
        foreach ($_POST['implementasi'] as $index => $row) {
            if (empty($row['no_dx']) && empty($row['hari_tgl']) && empty($row['implementasi'])) {
                continue;
            }
            $implementasi[] = [
                'no_dx'        => $row['no_dx']        ?? '',
                'hari_tgl'     => $row['hari_tgl']      ?? '',
                'jam'          => $row['jam']            ?? '',
                'implementasi' => $row['implementasi']  ?? '',
            ];
        }
    }

    // Proses dynamic rows evaluasi
    $evaluasi = [];
    if (!empty($_POST['evaluasi'])) {
        foreach ($_POST['evaluasi'] as $index => $row) {
            if (empty($row['no_dx']) && empty($row['hari_tgl']) && empty($row['evaluasi_s'])) {
                continue;
            }
            $evaluasi[] = [
                'no_dx'      => $row['no_dx']      ?? '',
                'hari_tgl'   => $row['hari_tgl']   ?? '',
                'jam'        => $row['jam']         ?? '',
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

    <?php include "maternitas/pengkajian_antenatal_care/tab.php"; ?>

    <section class="section dashboard">

        <!-- NOTIFIKASI -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?= $_SESSION['success'];
                                                unset($_SESSION['success']); ?></div>
        <?php endif; ?>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= $_SESSION['error'];
                                            unset($_SESSION['error']); ?></div>
        <?php endif; ?>

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
                                <th class="text-center">Diagnosa</th>
                                <th class="text-center" style="width:180px">Tanggal Ditemukan</th>
                                <th class="text-center" style="width:180px">Tanggal Teratasi</th>
                                <th class="text-center" style="width:60px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="tbody-diagnosa">
                            <!-- Dynamic rows masuk sini -->
                        </tbody>
                    </table>

                    <div class="row mb-4">
                        <div class="col-sm-12 d-flex justify-content-end">
                            <button type="button" class="btn btn-primary btn-sm" onclick="tambahRowDiagnosa()">+ Tambah Diagnosa</button>
                        </div>
                    </div>

                    <!-- ===================== TABEL INTERVENSI ===================== -->
                    <p class="text-primary fw-bold mb-2">Intervensi Keperawatan</p>

                    <table class="table table-bordered" id="tabel-intervensi">
                        <thead>
                            <tr>
                                <th class="text-center" style="width:40px">No</th>
                                <th class="text-center">Diagnosa</th>
                                <th class="text-center">Tujuan dan Kriteria Hasil</th>
                                <th class="text-center">Intervensi</th>
                                <th class="text-center" style="width:60px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="tbody-intervensi">
                            <!-- Dynamic rows masuk sini -->
                        </tbody>
                    </table>

                    <div class="row mb-4">
                        <div class="col-sm-12 d-flex justify-content-end">
                            <button type="button" class="btn btn-primary btn-sm" onclick="tambahRowIntervensi()">+ Tambah Intervensi</button>
                        </div>
                    </div>

                    <!-- ===================== TABEL IMPLEMENTASI ===================== -->
                    <p class="text-primary fw-bold mb-2">Implementasi Keperawatan</p>

                    <table class="table table-bordered" id="tabel-implementasi">
                        <thead>
                            <tr>
                                <th class="text-center" style="width:70px">No. Dx</th>
                                <th class="text-center" style="width:150px">Hari/Tanggal</th>
                                <th class="text-center" style="width:110px">Jam</th>
                                <th class="text-center">Implementasi</th>
                                <th class="text-center" style="width:60px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="tbody-implementasi">
                            <!-- Dynamic rows masuk sini -->
                        </tbody>
                    </table>

                    <div class="row mb-4">
                        <div class="col-sm-12 d-flex justify-content-end">
                            <button type="button" class="btn btn-primary btn-sm" onclick="tambahRowImplementasi()">+ Tambah Implementasi</button>
                        </div>
                    </div>

                    <!-- ===================== TABEL EVALUASI ===================== -->
                    <p class="text-primary fw-bold mb-2">Evaluasi Keperawatan</p>

                    <table class="table table-bordered" id="tabel-evaluasi">
                        <thead>
                            <tr>
                                <th class="text-center" style="width:70px">No. Dx</th>
                                <th class="text-center" style="width:150px">Hari/Tanggal</th>
                                <th class="text-center" style="width:110px">Jam</th>
                                <th class="text-center">Evaluasi (SOAP)</th>
                                <th class="text-center" style="width:60px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="tbody-evaluasi">
                            <!-- Dynamic rows masuk sini -->
                        </tbody>
                    </table>

                    <div class="row mb-4">
                        <div class="col-sm-12 d-flex justify-content-end">
                            <button type="button" class="btn btn-primary btn-sm" onclick="tambahRowEvaluasi()">+ Tambah Evaluasi</button>
                        </div>
                    </div>

                    <!-- TOMBOL SIMPAN -->
                    <div class="row mb-3">
                        <div class="col-sm-12 d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">Simpan Data</button>
                        </div>
                    </div>

                    <script>
                        let rowDiagnosaCount = 1;
                        let rowIntervensiCount = 1;
                        let rowImplementasiCount = 1;
                        let rowEvaluasiCount = 1;

                        const existingDiagnosa = <?= json_encode($existing_diagnosa) ?>;
                        const existingIntervensi = <?= json_encode($existing_intervensi) ?>;
                        const existingImplementasi = <?= json_encode($existing_implementasi) ?>;
                        const existingEvaluasi = <?= json_encode($existing_evaluasi) ?>;

                        // ---- DIAGNOSA ----
                        function tambahRowDiagnosa(data = null) {
                            const tbody = document.getElementById('tbody-diagnosa');
                            const index = rowDiagnosaCount;
                            const row = document.createElement('tr');

                            row.innerHTML = `
                                <td class="text-center align-middle">${index}</td>
                                <td>
                                    <textarea
                                        class="form-control form-control-sm"
                                        name="diagnosa[${index}][diagnosa]"
                                        rows="2"
                                        style="resize:none; overflow:hidden;"
                                        oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                    >${data?.diagnosa ?? ''}</textarea>
                                </td>
                                <td>
                                    <input
                                        type="date"
                                        class="form-control form-control-sm"
                                        name="diagnosa[${index}][tgl_ditemukan]"
                                        value="${data?.tgl_ditemukan ?? ''}"
                                    >
                                </td>
                                <td>
                                    <input
                                        type="date"
                                        class="form-control form-control-sm"
                                        name="diagnosa[${index}][tgl_teratasi]"
                                        value="${data?.tgl_teratasi ?? ''}"
                                    >
                                </td>
                                <td class="text-center align-middle">
                                    <button type="button" class="btn btn-danger btn-sm" onclick="hapusRow(this)">x</button>
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

                            row.innerHTML = `
                                <td class="text-center align-middle">${index}</td>
                                <td>
                                    <textarea
                                        class="form-control form-control-sm"
                                        name="intervensi[${index}][diagnosa]"
                                        rows="2"
                                        style="resize:none; overflow:hidden;"
                                        oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                    >${data?.diagnosa ?? ''}</textarea>
                                </td>
                                <td>
                                    <textarea
                                        class="form-control form-control-sm"
                                        name="intervensi[${index}][tujuan_kriteria]"
                                        rows="2"
                                        style="resize:none; overflow:hidden;"
                                        oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                    >${data?.tujuan_kriteria ?? ''}</textarea>
                                </td>
                                <td>
                                    <textarea
                                        class="form-control form-control-sm"
                                        name="intervensi[${index}][intervensi]"
                                        rows="2"
                                        style="resize:none; overflow:hidden;"
                                        oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                    >${data?.intervensi ?? ''}</textarea>
                                </td>
                                <td class="text-center align-middle">
                                    <button type="button" class="btn btn-danger btn-sm" onclick="hapusRow(this)">x</button>
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

                            row.innerHTML = `
                                <td>
                                    <input
                                        type="text"
                                        class="form-control form-control-sm"
                                        name="implementasi[${index}][no_dx]"
                                        value="${data?.no_dx ?? ''}"
                                    >
                                </td>
                                <td>
                                    <input
                                        type="date"
                                        class="form-control form-control-sm"
                                        name="implementasi[${index}][hari_tgl]"
                                        value="${data?.hari_tgl ?? ''}"
                                    >
                                </td>
                                <td>
                                    <input
                                        type="time"
                                        class="form-control form-control-sm"
                                        name="implementasi[${index}][jam]"
                                        value="${data?.jam ?? ''}"
                                    >
                                </td>
                                <td>
                                    <textarea
                                        class="form-control form-control-sm"
                                        name="implementasi[${index}][implementasi]"
                                        rows="2"
                                        style="resize:none; overflow:hidden;"
                                        oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                    >${data?.implementasi ?? ''}</textarea>
                                </td>
                                <td class="text-center align-middle">
                                    <button type="button" class="btn btn-danger btn-sm" onclick="hapusRow(this)">x</button>
                                </td>
                            `;

                            tbody.appendChild(row);
                            rowImplementasiCount++;
                        }

                        // ---- EVALUASI ----
                        function tambahRowEvaluasi(data = null) {
                            const tbody = document.getElementById('tbody-evaluasi');
                            const index = rowEvaluasiCount;
                            const row = document.createElement('tr');

                            row.innerHTML = `
                                <td>
                                    <input
                                        type="text"
                                        class="form-control form-control-sm"
                                        name="evaluasi[${index}][no_dx]"
                                        value="${data?.no_dx ?? ''}"
                                    >
                                </td>
                                <td>
                                    <input
                                        type="date"
                                        class="form-control form-control-sm"
                                        name="evaluasi[${index}][hari_tgl]"
                                        value="${data?.hari_tgl ?? ''}"
                                    >
                                </td>
                                <td>
                                    <input
                                        type="time"
                                        class="form-control form-control-sm"
                                        name="evaluasi[${index}][jam]"
                                        value="${data?.jam ?? ''}"
                                    >
                                </td>
                                <td>
                                <div class="mb-1 d-flex align-items-start gap-2">
                                    <label class="form-label form-label-sm fw-bold mb-0" style="width:20px;">S</label>
                                    <textarea
                                    class="form-control form-control-sm"
                                    name="evaluasi[${index}][evaluasi_s]"
                                    rows="2"
                                    style="resize:none; overflow:hidden;"
                                    oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                    >${data?.evaluasi_s ?? ''}</textarea>
                                </div>

                                <div class="mb-1 d-flex align-items-start gap-2">
                                    <label class="form-label form-label-sm fw-bold mb-0" style="width:20px;">O</label>
                                    <textarea
                                    class="form-control form-control-sm"
                                    name="evaluasi[${index}][evaluasi_o]"
                                    rows="2"
                                    style="resize:none; overflow:hidden;"
                                    oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                    >${data?.evaluasi_o ?? ''}</textarea>
                                </div>

                                <div class="mb-1 d-flex align-items-start gap-2">
                                    <label class="form-label form-label-sm fw-bold mb-0" style="width:20px;">A</label>
                                    <textarea
                                    class="form-control form-control-sm"
                                    name="evaluasi[${index}][evaluasi_a]"
                                    rows="2"
                                    style="resize:none; overflow:hidden;"
                                    oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                    >${data?.evaluasi_a ?? ''}</textarea>
                                </div>

                                <div class="d-flex align-items-start gap-2">
                                    <label class="form-label form-label-sm fw-bold mb-0" style="width:20px;">P</label>
                                    <textarea
                                    class="form-control form-control-sm"
                                    name="evaluasi[${index}][evaluasi_p]"
                                    rows="2"
                                    style="resize:none; overflow:hidden;"
                                    oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                    >${data?.evaluasi_p ?? ''}</textarea>
                                </div>
                                </td>
                                <td class="text-center align-middle">
                                    <button type="button" class="btn btn-danger btn-sm" onclick="hapusRow(this)">x</button>
                                </td>
                            `;

                            tbody.appendChild(row);
                            rowEvaluasiCount++;
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

                <?php include "tab_navigasi.php"; ?>

            </div>
        </div>

    </section>
</main>