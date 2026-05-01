<?php

require_once "koneksi.php";
require_once "utils.php";

$form_id       = 3;
$level         = $_SESSION['level'];
$user_id       = $_SESSION['id_user'];
$section_name  = 'lainnya';
$section_label = 'Lainnya';

// Ambil submission sesuai role
if ($level === 'Dosen') {
    $submission_id_param = $_GET['submission_id'] ?? null;
    if (!$submission_id_param) {
        echo "<div class='alert alert-danger'>Submission tidak ditemukan.</div>";
        exit;
    }
    $stmt = $mysqli->prepare("
        SELECT s.*, r.nama as dosen_name
        FROM submissions s
        LEFT JOIN tbl_user r ON s.reviewed_by = r.id_user
        WHERE s.id = ?
    ");
    $stmt->bind_param("i", $submission_id_param);
    $stmt->execute();
    $submission = $stmt->get_result()->fetch_assoc();
} else {
    $submission = getSubmission($user_id, $form_id, $mysqli);
}

$existing_data  = $submission ? getSectionData($submission['id'], $section_name, $mysqli) : [];
$section_status = $submission ? getSectionStatus($submission['id'], $section_name, $mysqli) : null;

// Load existing dynamic rows
$existing_diagnosa     = $existing_data['diagnosa']     ?? [];
$existing_intervensi   = $existing_data['intervensi']   ?? [];
$existing_implementasi = $existing_data['implementasi'] ?? [];
$existing_evaluasi     = $existing_data['evaluasi']     ?? [];

// Komentar section
$comments = $submission ? getSectionComments($submission['id'], $section_name, $mysqli) : [];

// Readonly jika mahasiswa + locked, atau jika dosen
$is_dosen    = $level === 'Dosen';
$is_readonly = $is_dosen || isLocked($submission);
$ro          = $is_readonly ? 'readonly' : '';
$ro_select   = $is_readonly ? 'disabled' : '';

$can_submit = false;
if ($submission && !$is_dosen && $submission['status'] === 'draft') {

    // Ambil count_section dari forms
    $stmt = $mysqli->prepare("SELECT count_section FROM forms WHERE id = ?");
    $stmt->bind_param("i", $form_id);
    $stmt->execute();
    $count_section = $stmt->get_result()->fetch_assoc()['count_section'];

    // Ambil total section yang sudah diisi
    $stmt = $mysqli->prepare("SELECT COUNT(*) as filled FROM submission_sections WHERE submission_id = ?");
    $stmt->bind_param("i", $submission['id']);
    $stmt->execute();
    $total_filled = $stmt->get_result()->fetch_assoc()['filled'];
    
    echo "ini total filled: " . $total_filled;

    $can_submit = $total_filled >= $count_section;
}


// POST handler
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'submit_to_dosen') {
        $result = submitSubmission($submission['id'], $mysqli);
        if ($result['success']) {
            redirectWithMessage($_SERVER['REQUEST_URI'], 'success', 'Data berhasil disubmit ke dosen!');
        } else {
            redirectWithMessage($_SERVER['REQUEST_URI'], 'error', $result['message']);
        }
    }
    // Mahasiswa: simpan data
    if ($level === 'Mahasiswa') {
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
    // Dosen: approve/revisi/komentar
    if ($level === 'Dosen') {
        $submission_id = $submission['id'];
        $dosen_id      = $user_id;
        $action        = $_POST['action'] ?? '';
        $comment       = $_POST['comment'] ?? '';

        if ($action === 'approve') {
            updateSectionStatus($submission_id, $section_name, 'approved', $mysqli);
            if (!empty($comment)) {
                saveComment($submission_id, $section_name, $comment, $dosen_id, $mysqli);
            }
        } elseif ($action === 'revision') {
            if (empty($comment)) {
                redirectWithMessage($_SERVER['REQUEST_URI'], 'error', 'Komentar wajib diisi saat meminta revisi.');
            }
            updateSectionStatus($submission_id, $section_name, 'revision', $mysqli);
            saveComment($submission_id, $section_name, $comment, $dosen_id, $mysqli);
        }

        updateReviewer($submission_id, $dosen_id, $mysqli);
        updateSubmissionStatusByDosen($submission_id, $form_id, $mysqli);
        redirectWithMessage($_SERVER['REQUEST_URI'], 'success', 'Berhasil disimpan.');
    }
}

?>

<main id="main" class="main">

                 <?php include "navbar_maternitas.php"; ?>


    <section class="section dashboard">

        
        
        <div class="card">
            <div class="card-body">
                <!-- Info status section (untuk dosen) -->
                <?php if ($section_status): ?>
                    <?php
                    $badge = [
                        'draft'     => 'secondary',
                        'submitted' => 'primary',
                        'revision'  => 'warning',
                        'approved'  => 'success',
                    ];
                    ?>
                    <div class="alert alert-<?= $badge[$section_status] ?>">
                        Status: <strong><?= ucfirst($section_status) ?></strong>
                        | Reviewed by: <strong><?php echo $submission['dosen_name'] ? htmlspecialchars($submission['dosen_name']) : '-'; ?></strong>
                    </div>
                <?php endif; ?>

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

                    <?php if (!$is_readonly): ?>
                        <div class="row mb-4">
                            <div class="col-sm-12 d-flex justify-content-end">
                                <button type="button" class="btn btn-primary btn-sm" onclick="tambahRowIntervensi()">+ Tambah Intervensi</button>
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

                    <?php if (!$is_readonly): ?>
                        <div class="row mb-4">
                            <div class="col-sm-12 d-flex justify-content-end">
                                <button type="button" class="btn btn-primary btn-sm" onclick="tambahRowEvaluasi()">+ Tambah Evaluasi</button>
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
                                <td>
                                    <input
                                        type="date"
                                        class="form-control form-control-sm"
                                        name="diagnosa[${index}][tgl_ditemukan]"
                                        value="${data?.tgl_ditemukan ?? ''}"
                                        ${isReadonly ? 'readonly' : ''}
                                    >
                                </td>
                                <td>
                                    <input
                                        type="date"
                                        class="form-control form-control-sm"
                                        name="diagnosa[${index}][tgl_teratasi]"
                                        value="${data?.tgl_teratasi ?? ''}"
                                        ${isReadonly ? 'readonly' : ''}
                                    >
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
                                        name="intervensi[${index}][tujuan_kriteria]"
                                        rows="2"
                                        style="resize:none; overflow:hidden;"
                                        oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                        ${isReadonly ? 'readonly' : ''}
                                    >${data?.tujuan_kriteria ?? ''}</textarea>
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
                                <td>
                                    <input
                                        type="text"
                                        class="form-control form-control-sm"
                                        name="implementasi[${index}][no_dx]"
                                        value="${data?.no_dx ?? ''}"
                                        ${isReadonly ? 'readonly' : ''}
                                    >
                                </td>
                                <td>
                                    <input
                                        type="date"
                                        class="form-control form-control-sm"
                                        name="implementasi[${index}][hari_tgl]"
                                        value="${data?.hari_tgl ?? ''}"
                                        ${isReadonly ? 'readonly' : ''}
                                    >
                                </td>
                                <td>
                                    <input
                                        type="time"
                                        class="form-control form-control-sm"
                                        name="implementasi[${index}][jam]"
                                        value="${data?.jam ?? ''}"
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
                                <td class="text-center align-middle">
                                    ${!isReadonly ? `<button type="button" class="btn btn-danger btn-sm" onclick="hapusRow(this)">x</button>` : ''}
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
                            const isReadonly = <?= json_encode($is_readonly) ?>;
                            row.innerHTML = `
                                <td>
                                    <input
                                        type="text"
                                        class="form-control form-control-sm"
                                        name="evaluasi[${index}][no_dx]"
                                        value="${data?.no_dx ?? ''}"
                                        ${isReadonly ? 'readonly' : ''}
                                    >
                                </td>
                                <td>
                                    <input
                                        type="date"
                                        class="form-control form-control-sm"
                                        name="evaluasi[${index}][hari_tgl]"
                                        value="${data?.hari_tgl ?? ''}"
                                        ${isReadonly ? 'readonly' : ''}
                                    >
                                </td>
                                <td>
                                    <input
                                        type="time"
                                        class="form-control form-control-sm"
                                        name="evaluasi[${index}][jam]"
                                        value="${data?.jam ?? ''}"
                                        ${isReadonly ? 'readonly' : ''}
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
                                    ${isReadonly ? 'readonly' : ''}
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
                                    ${isReadonly ? 'readonly' : ''}
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
                                    ${isReadonly ? 'readonly' : ''}
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
                                    ${isReadonly ? 'readonly' : ''}
                                    >${data?.evaluasi_p ?? ''}</textarea>
                                </div>
                                </td>
                                <td class="text-center align-middle">
                                    ${!isReadonly ? `<button type="button" class="btn btn-danger btn-sm" onclick="hapusRow(this)">x</button>` : ''}
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

                <!-- ================================ -->
                <!-- SECTION KOMENTAR & ACTION DOSEN -->
                <!-- ================================ -->
                <div class="card mt-3">
                    <div class="card-body">
                        <h5 class="card-title"><strong>Komentar</strong></h5>

                        <!-- List komentar -->
                        <?php if (!empty($comments)): ?>
                            <?php foreach ($comments as $cmt): ?>
                                <div class="alert alert-warning">
                                    <strong><?= htmlspecialchars($cmt['dosen_name']) ?></strong>
                                    <small class="text-muted ms-2"><?= date('d/m/Y H:i', strtotime($cmt['created_at'])) ?></small>
                                    <p class="mb-0 mt-1"><?= htmlspecialchars($cmt['comment']) ?></p>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-muted">Belum ada komentar.</p>
                        <?php endif; ?>

                        <!-- Form komentar + action (khusus dosen) -->
                        <?php if ($is_dosen && $section_status !== 'approved'): ?>
                            <form action="" method="POST">
                                <div class="row mb-3">
                                    <label class="col-sm-2 col-form-label"><strong>Komentar</strong></label>
                                    <div class="col-sm-9">
                                        <textarea name="comment" class="form-control" rows="3"
                                            placeholder="Tulis komentar (wajib jika meminta revisi)..."></textarea>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-11 d-flex justify-content-end gap-2">
                                        <button type="submit" name="action" value="revision" class="btn btn-warning">
                                            Minta Revisi
                                        </button>
                                        <button type="submit" name="action" value="approve" class="btn btn-success">
                                            Approve
                                        </button>
                                    </div>
                                </div>
                            </form>
                        <?php elseif ($is_dosen && $section_status === 'approved'): ?>
                            <div class="alert alert-success">
                                Section ini sudah di-approve.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <?php include "tab_navigasi.php"; ?>

            </div>
        </div>

    </section>
</main>