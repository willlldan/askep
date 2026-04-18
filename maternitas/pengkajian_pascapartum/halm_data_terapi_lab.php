<?php
require_once "koneksi.php";
require_once "utils.php";

$form_id       = 3;
$level         = $_SESSION['level'];
$user_id       = $_SESSION['id_user'];
$section_name  = 'program_terapi_lab';
$section_label = 'Program Terapi dan Laboratorium';

if ($level === 'Dosen') {
    $submission_id_param = $_GET['submission_id'] ?? null;
    if (!$submission_id_param) { echo "<div class='alert alert-danger'>Submission tidak ditemukan.</div>"; exit; }
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

// =============================================
// HANDLE POST - DOSEN APPROVE / REVISI / KOMENTAR
// =============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $level === 'Dosen') {
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

// Load komentar section (untuk dosen & mahasiswa)
$comments = $submission ? getSectionComments($submission['id'], $section_name, $mysqli) : [];

// Readonly jika mahasiswa + locked, atau jika dosen
$is_dosen    = $level === 'Dosen';
$is_readonly = $is_dosen || isLocked($submission);
$ro          = $is_readonly ? 'readonly' : '';
$ro_select   = $is_readonly ? 'disabled' : '';
?>

<main id="main" class="main">

    <?php include "navbar_maternitas.php"; ?>

    <section class="section dashboard">

        <!-- NOTIFIKASI -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>


        <!-- Info status section (untuk dosen) -->
        <?php if  ($section_status): ?>
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
                                <th class="text-center" >No</th>
                                <th class="text-center" >Data Subjektif (DS)</th>
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
let rowObatCount        = 1;
let rowLabCount         = 1;
let rowKlasifikasiCount = 1;
let rowAnalisaCount     = 1;

const existingObat        = <?= json_encode($existing_obat) ?>;
const existingLab         = <?= json_encode($existing_lab) ?>;
const existingKlasifikasi = <?= json_encode($existing_klasifikasi) ?>;
const existingAnalisa     = <?= json_encode($existing_analisa) ?>;
const isReadonly          = <?= json_encode($is_readonly) ?>;

// ---- OBAT ----
function tambahRowObat(data = null) {
    const tbody = document.getElementById('tbody-obat');
    const index = rowObatCount++;
    const row = document.createElement('tr');
    row.innerHTML = `
        <td class="text-center">${index}</td>
        <td><input type="text" name="obat[${index}][jenis_obat]" class="form-control" value="${data?.jenis_obat ?? ''}" ${isReadonly?'readonly':''}></td>
        <td><input type="text" name="obat[${index}][dosis]" class="form-control" value="${data?.dosis ?? ''}" ${isReadonly?'readonly':''}></td>
        <td><input type="text" name="obat[${index}][kegunaan]" class="form-control" value="${data?.kegunaan ?? ''}" ${isReadonly?'readonly':''}></td>
        <td><input type="text" name="obat[${index}][cara_pemberian]" class="form-control" value="${data?.cara_pemberian ?? ''}" ${isReadonly?'readonly':''}></td>
        <td><button type="button" class="btn btn-danger btn-sm" onclick="hapusRow(this)" ${isReadonly?'disabled':''}>x</button></td>
    `;
    tbody.appendChild(row);
}

// ---- LAB ----
function tambahRowLab(data = null) {
    const tbody = document.getElementById('tbody-lab');
    const index = rowLabCount++;
    const row = document.createElement('tr');
    row.innerHTML = `
        <td class="text-center">${index}</td>
        <td><input type="text" name="lab[${index}][pemeriksaan]" class="form-control" value="${data?.pemeriksaan ?? ''}" ${isReadonly?'readonly':''}></td>
        <td><input type="text" name="lab[${index}][hasil]" class="form-control" value="${data?.hasil ?? ''}" ${isReadonly?'readonly':''}></td>
        <td><input type="text" name="lab[${index}][nilai_normal]" class="form-control" value="${data?.nilai_normal ?? ''}" ${isReadonly?'readonly':''}></td>
        <td><button type="button" class="btn btn-danger btn-sm" onclick="hapusRow(this)" ${isReadonly?'disabled':''}>x</button></td>
    `;
    tbody.appendChild(row);
}

// ---- KLASIFIKASI ----
function tambahRowKlasifikasi(data = null) {
    const tbody = document.getElementById('tbody-klasifikasi');
    const index = rowKlasifikasiCount++;
    const row = document.createElement('tr');
    row.innerHTML = `
        <td class="text-center">${index}</td>
        <td><input type="text" name="klasifikasi[${index}][data_subjektif]" class="form-control" value="${data?.data_subjektif ?? ''}" ${isReadonly?'readonly':''}></td>
        <td><input type="text" name="klasifikasi[${index}][data_objektif]" class="form-control" value="${data?.data_objektif ?? ''}" ${isReadonly?'readonly':''}></td>
        <td><button type="button" class="btn btn-danger btn-sm" onclick="hapusRow(this)" ${isReadonly?'disabled':''}>x</button></td>
    `;
    tbody.appendChild(row);
}

// ---- ANALISA ----
function tambahRowAnalisa(data = null) {
    const tbody = document.getElementById('tbody-analisa');
    const index = rowAnalisaCount++;
    const row = document.createElement('tr');
    row.innerHTML = `
        <td class="text-center">${index}</td>
        <td><input type="text" name="analisa[${index}][ds_do]" class="form-control" value="${data?.ds_do ?? ''}" ${isReadonly?'readonly':''}></td>
        <td><input type="text" name="analisa[${index}][etiologi]" class="form-control" value="${data?.etiologi ?? ''}" ${isReadonly?'readonly':''}></td>
        <td><input type="text" name="analisa[${index}][masalah]" class="form-control" value="${data?.masalah ?? ''}" ${isReadonly?'readonly':''}></td>
        <td><button type="button" class="btn btn-danger btn-sm" onclick="hapusRow(this)" ${isReadonly?'disabled':''}>x</button></td>
    `;
    tbody.appendChild(row);
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

                        const existingData = <?= json_encode($existing_data) ?>;
                    </script>
                </form>
               
            </div>
        </div>

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

    </section> <?php include "tab_navigasi.php"; ?>
</main>