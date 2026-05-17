<?php

require_once "koneksi.php";
require_once "utils.php";
require_once __DIR__ . "/../../utils/form_helpers.php";


$form_id       = 1;
$level         = $_SESSION['level'];
$user_id       = $_SESSION['id_user'];
$section_name  = 'analisa_data';
$section_label = 'Analisa Data';

// =============================================
// DOSEN: ambil submission berdasarkan ?submission_id=
// MAHASISWA: ambil submission milik sendiri
// =============================================
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
$existing_klasifikasi = $existing_data['klasifikasi'] ?? [];
$existing_analisa     = $existing_data['analisa']     ?? [];

// =============================================
// HANDLE POST - MAHASISWA SIMPAN DATA
// =============================================

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $level === 'Mahasiswa') {
    if (isLocked($submission)) {
        redirectWithMessage($_SERVER['REQUEST_URI'], 'error', 'Data tidak dapat diubah karena sedang dalam proses review.');
    }

    $klasifikasi = parse_dynamic_rows($_POST['klasifikasi'] ?? [], ['ds', 'do']);
    $analisa     = parse_dynamic_rows($_POST['analisa'] ?? [], ['ds_do', 'etiologi', 'masalah']);

    $data = [
        'klasifikasi' => $klasifikasi,
        'analisa'     => $analisa,
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

// =============================================
// HANDLE POST - DOSEN APPROVE / REVISI / KOMENTAR
// =============================================

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $level === 'Dosen') {
    $submission_id = $submission['id'];
    $dosen_id      = $user_id;
    $action        = $_POST['action'] ?? '';
    $comment       = $_POST['comment'] ?? '';

    $err = handle_dosen_action($submission_id, $section_name, $action, $comment, $dosen_id, $mysqli);
    if (isset($err['error'])) {
        redirectWithMessage($_SERVER['REQUEST_URI'], 'error', $err['error']);
    }
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

    <?php include "maternitas/pengkajian_antenatal_care/tab.php"; ?>

    <section class="section dashboard">

        <?php include "partials/notifikasi.php"; ?>
        <?php include "partials/status_section.php"; ?>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><strong>ANALISA DATA</strong></h5>
                <form class="needs-validation" novalidate action="" method="POST">
                    <!-- ===================== TABEL KLASIFIKASI DATA ===================== -->
                    <p class="text-primary fw-bold mb-2">Klasifikasi Data</p>
                    <table class="table table-bordered" id="tabel-klasifikasi">
                        <thead>
                            <tr>
                                <th class="text-center" style="width:40px">No</th>
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
                            <button type="button" class="btn btn-primary btn-sm" id="btn-tambah-klasifikasi" onclick="tambahRowKlasifikasi()">+ Tambah Baris</button>
                        </div>
                    </div>
                    <!-- ===================== TABEL ANALISA DATA ===================== -->
                    <p class="text-primary fw-bold mb-2">Analisa Data</p>
                    <table class="table table-bordered" id="tabel-analisa">
                        <thead>
                            <tr>
                                <th class="text-center" style="width:40px">No</th>
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
                            <button type="button" class="btn btn-primary btn-sm" id="btn-tambah-analisa" onclick="tambahRowAnalisa()">+ Tambah Baris</button>
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
                        let rowKlasifikasiCount = 1;
                        let rowAnalisaCount = 1;
                        const existingKlasifikasi = <?= json_encode($existing_klasifikasi) ?>;
                        const existingAnalisa = <?= json_encode($existing_analisa) ?>;
                        const isReadonly = <?= json_encode($is_readonly) ?>;
                        // Import helper
                        const script = document.createElement('script');
                        script.src = '/assets/js/form_row_helpers.js';
                        document.head.appendChild(script);

                        function tambahRowKlasifikasi(data = null) {
                            tambahRowKlasifikasi({
                                tbodyId: 'tbody-klasifikasi',
                                rowCountVar: 'rowKlasifikasiCount',
                                isReadonly,
                                data
                            });
                        }

                        function tambahRowAnalisa(data = null) {
                            tambahRowAnalisa({
                                tbodyId: 'tbody-analisa',
                                rowCountVar: 'rowAnalisaCount',
                                isReadonly,
                                data
                            });
                        }

                        // hapusRow sudah otomatis dari helper
                        // Load existing rows on page load
                        window.addEventListener('load', function() {
                            if (existingKlasifikasi && existingKlasifikasi.length > 0) {
                                existingKlasifikasi.forEach(row => tambahRowKlasifikasi(row));
                            } else {
                                tambahRowKlasifikasi(); // default 1 row kosong
                            }
                            if (existingAnalisa && existingAnalisa.length > 0) {
                                existingAnalisa.forEach(row => tambahRowAnalisa(row));
                            } else {
                                tambahRowAnalisa(); // default 1 row kosong
                            }
                            // Disable add buttons if readonly
                            if (isReadonly) {
                                document.getElementById('btn-tambah-klasifikasi').setAttribute('disabled', 'disabled');
                                document.getElementById('btn-tambah-analisa').setAttribute('disabled', 'disabled');
                            }
                        });
                    </script>
                </form>

            </div>
        </div>

        <?php include "partials/footer_form.php"; ?>

    </section>
</main>