<?php
require_once "koneksi.php";
require_once "utils.php";

$form_id       = 20;
$level         = $_SESSION['level'];
$user_id       = $_SESSION['id_user'];
$section_name  = 'klasifikasi_analisa_data';
$section_label = 'Klasifikasi Analisa Data';

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
$existing_obat     = $existing_data['obat']     ?? [];

// =============================================
// HANDLE POST - MAHASISWA SIMPAN DATA
// =============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $level === 'Mahasiswa') {
    if (isLocked($submission)) {
        redirectWithMessage($_SERVER['REQUEST_URI'], 'error', 'Data tidak dapat diubah karena sedang dalam proses review.');
    }

    // Proses dynamic rows klasifikasi data
    $klasifikasi = [];
    if (!empty($_POST['klasifikasi'])) {
        foreach ($_POST['klasifikasi'] as $index => $row) {
            if (empty($row['ds']) && empty($row['do'])) {
                continue;
            }
            $klasifikasi[] = [
                'ds' => $row['ds'] ?? '',
                'do' => $row['do'] ?? '',
            ];
        }
    }
     $obat = [];
    if (!empty($_POST['obat'])) {
        foreach ($_POST['obat'] as $index => $row) {
            if (empty($row['jenis_obat']) && empty($row['dosis']) && empty($row['kegunaan'])) {
                continue;
            }
            $obat[] = [
                'jenis_obat'     => $row['jenis_obat']     ?? '',
                'dosis'          => $row['dosis']           ?? '',
                'kegunaan'       => $row['kegunaan']        ?? '',
                'cara_pemberian' => $row['cara_pemberian']  ?? '',
            ];
        }
    }


    // Proses dynamic rows analisa data
    $analisa = [];
    if (!empty($_POST['analisa'])) {
        foreach ($_POST['analisa'] as $index => $row) {
            if (empty($row['ds_do']) && empty($row['etiologi']) && empty($row['masalah'])) {
                continue;
            }
            $analisa[] = [
                'ds_do'    => $row['ds_do']    ?? '',
                'etiologi' => $row['etiologi'] ?? '',
                'masalah'  => $row['masalah']  ?? '',
            ];
        }
    }

    $data = [
        'klasifikasi' => $klasifikasi,
        'analisa'     => $analisa,
        'obat'        => $obat,
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

     <?php include "keperawatan/dasar/tab.php"; ?>

    <section class="section dashboard">

        <!-- ========================= -->
        <!-- NOTIFIKASI -->
        <!-- ========================= -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?= $_SESSION['success']; unset($_SESSION['success']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>


        <!-- ========================= -->
        <!-- STATUS SECTION -->
        <!-- ========================= -->
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
                Status :
                <strong><?= ucfirst($section_status) ?></strong>

                | Reviewed by :
                <strong>
                    <?= !empty($submission['dosen_name'])
                        ? htmlspecialchars($submission['dosen_name'])
                        : '-'; ?>
                </strong>
            </div>

        <?php endif; ?>


        <!-- ========================= -->
        <!-- CARD -->
        <!-- ========================= -->
        <div class="card">

            <div class="card-body">

                <!-- JUDUL -->
                <h5 class="card-title">
                    <strong>PROGRAM TERAPI & LABORATORIUM</strong>
                </h5>

                <!-- FORM -->
                <form class="needs-validation" novalidate method="POST">

                    <!-- ========================= -->
                    <!-- TERAPI / OBAT -->
                    <!-- ========================= -->
                    <p class="text-primary fw-bold mb-2">
                        Terapi / Obat
                    </p>

                    <table class="table table-bordered align-middle" id="tabel-obat">

                        <thead class="table-light">
                            <tr>
                                <th class="text-center" width="40">No</th>
                                <th class="text-center">Jenis Obat</th>
                                <th class="text-center">Dosis</th>
                                <th class="text-center">Manfaat</th>
                                <th class="text-center">Cara Pemberian</th>
                                <th class="text-center" width="60">Aksi</th>
                            </tr>
                        </thead>

                        <tbody id="tbody-obat"></tbody>

                    </table>

                    <?php if (!$is_readonly): ?>
                        <div class="text-end mb-4">
                            <button type="button"
                                class="btn btn-primary btn-sm"
                                onclick="tambahRowObat()">

                                + Tambah Obat

                            </button>
                        </div>
                    <?php endif; ?>


                    <!-- ========================= -->
                    <!-- KLASIFIKASI ANALISA -->
                    <!-- ========================= -->
                    <h5 class="card-title mt-4">
                        <strong>KLASIFIKASI ANALISA DATA</strong>
                    </h5>


                    <!-- ========================= -->
                    <!-- KLASIFIKASI DATA -->
                    <!-- ========================= -->
                    <p class="text-primary fw-bold mb-2">
                        Klasifikasi Data
                    </p>

                    <table class="table table-bordered align-middle" id="tabel-klasifikasi">

                        <thead class="table-light">
                            <tr>
                                <th class="text-center" width="40">No</th>
                                <th class="text-center">Data Subjektif (DS)</th>
                                <th class="text-center">Data Objektif (DO)</th>
                                <th class="text-center" width="60">Aksi</th>
                            </tr>
                        </thead>

                        <tbody id="tbody-klasifikasi"></tbody>

                    </table>

                    <?php if (!$is_readonly): ?>
                        <div class="text-end mb-4">
                            <button type="button"
                                class="btn btn-primary btn-sm"
                                onclick="tambahRowKlasifikasi()">

                                + Tambah Baris

                            </button>
                        </div>
                    <?php endif; ?>


                    <!-- ========================= -->
                    <!-- ANALISA DATA -->
                    <!-- ========================= -->
                    <p class="text-primary fw-bold mb-2">
                        Analisa Data
                    </p>

                    <table class="table table-bordered align-middle" id="tabel-analisa">

                        <thead class="table-light">
                            <tr>
                                <th class="text-center" width="40">No</th>
                                <th class="text-center">DS / DO</th>
                                <th class="text-center">Etiologi</th>
                                <th class="text-center">Masalah</th>
                                <th class="text-center" width="60">Aksi</th>
                            </tr>
                        </thead>

                        <tbody id="tbody-analisa"></tbody>

                    </table>

                  <!-- TOMBOL SUBMIT -->
                    <?php if (!$is_dosen): ?>
                    <div class="row mb-3">
                        <div class="col-sm-11 d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </div>
                    <?php endif; ?>
                </form>
            </div>
        </div>

        <?php include dirname(__DIR__, 2) . '/partials/footer_form.php'; ?>

    </section>
</main>

        <!-- ========================= -->
        <!-- JAVASCRIPT -->
        <!-- ========================= -->
        <script>

            // =========================
            // EXISTING DATA
            // =========================
            const existingObat        = <?= json_encode($existing_obat) ?>;
            const existingKlasifikasi = <?= json_encode($existing_klasifikasi) ?>;
            const existingAnalisa     = <?= json_encode($existing_analisa) ?>;
            const isReadonly          = <?= json_encode($is_readonly) ?>;

            let rowObatCount        = 1;
            let rowKlasifikasiCount = 1;
            let rowAnalisaCount     = 1;


            // =========================
            // HAPUS ROW
            // =========================
            function hapusRow(button) {
                button.closest('tr').remove();
            }


            // =========================
            // TAMBAH ROW OBAT
            // =========================
            function tambahRowObat(data = {}) {

                const tbody = document.getElementById('tbody-obat');

                const row = document.createElement('tr');

                row.innerHTML = `
                    <td class="text-center">${rowObatCount}</td>

                    <td>
                        <input type="text"
                            class="form-control form-control-sm"
                            name="obat[${rowObatCount}][jenis_obat]"
                            value="${data.jenis_obat ?? ''}"
                            ${isReadonly ? 'readonly' : ''}>
                    </td>

                    <td>
                        <input type="text"
                            class="form-control form-control-sm"
                            name="obat[${rowObatCount}][dosis]"
                            value="${data.dosis ?? ''}"
                            ${isReadonly ? 'readonly' : ''}>
                    </td>

                    <td>
                        <input type="text"
                            class="form-control form-control-sm"
                            name="obat[${rowObatCount}][[P]]"
                            value="${data.kegunaan ?? ''}"
                            ${isReadonly ? 'readonly' : ''}>
                    </td>

                    <td>
                        <input type="text"
                            class="form-control form-control-sm"
                            name="obat[${rowObatCount}][cara_pemberian]"
                            value="${data.cara_pemberian ?? ''}"
                            ${isReadonly ? 'readonly' : ''}>
                    </td>

                    <td class="text-center">
                        ${
                            !isReadonly
                            ? `<button type="button"
                                    class="btn btn-danger btn-sm"
                                    onclick="hapusRow(this)">
                                    x
                               </button>`
                            : ''
                        }
                    </td>
                `;

                tbody.appendChild(row);
                rowObatCount++;
            }


            // =========================
            // TAMBAH ROW KLASIFIKASI
            // =========================
            function tambahRowKlasifikasi(data = {}) {

                const tbody = document.getElementById('tbody-klasifikasi');

                const row = document.createElement('tr');

                row.innerHTML = `
                    <td class="text-center">${rowKlasifikasiCount}</td>

                    <td>
                        <textarea
                            class="form-control form-control-sm"
                            name="klasifikasi[${rowKlasifikasiCount}][ds]"
                            rows="2"
                            ${isReadonly ? 'readonly' : ''}>${data.ds ?? ''}</textarea>
                    </td>

                    <td>
                        <textarea
                            class="form-control form-control-sm"
                            name="klasifikasi[${rowKlasifikasiCount}][do]"
                            rows="2"
                            ${isReadonly ? 'readonly' : ''}>${data.do ?? ''}</textarea>
                    </td>

                    <td class="text-center">
                        ${
                            !isReadonly
                            ? `<button type="button"
                                    class="btn btn-danger btn-sm"
                                    onclick="hapusRow(this)">
                                    x
                               </button>`
                            : ''
                        }
                    </td>
                `;

                tbody.appendChild(row);
                rowKlasifikasiCount++;
            }


            // =========================
            // TAMBAH ROW ANALISA
            // =========================
            function tambahRowAnalisa(data = {}) {

                const tbody = document.getElementById('tbody-analisa');

                const row = document.createElement('tr');

                row.innerHTML = `
                    <td class="text-center">${rowAnalisaCount}</td>

                    <td>
                        <textarea
                            class="form-control form-control-sm"
                            name="analisa[${rowAnalisaCount}][ds_do]"
                            rows="2"
                            ${isReadonly ? 'readonly' : ''}>${data.ds_do ?? ''}</textarea>
                    </td>

                    <td>
                        <textarea
                            class="form-control form-control-sm"
                            name="analisa[${rowAnalisaCount}][etiologi]"
                            rows="2"
                            ${isReadonly ? 'readonly' : ''}>${data.etiologi ?? ''}</textarea>
                    </td>

                    <td>
                        <textarea
                            class="form-control form-control-sm"
                            name="analisa[${rowAnalisaCount}][masalah]"
                            rows="2"
                            ${isReadonly ? 'readonly' : ''}>${data.masalah ?? ''}</textarea>
                    </td>

                    <td class="text-center">
                        ${
                            !isReadonly
                            ? `<button type="button"
                                    class="btn btn-danger btn-sm"
                                    onclick="hapusRow(this)">
                                    x
                               </button>`
                            : ''
                        }
                    </td>
                `;

                tbody.appendChild(row);
                rowAnalisaCount++;
            }


            // =========================
            // LOAD EXISTING DATA
            // =========================
            window.addEventListener('DOMContentLoaded', () => {

                if (existingObat.length > 0) {
                    existingObat.forEach(item => tambahRowObat(item));
                } else {
                    tambahRowObat();
                }

                if (existingKlasifikasi.length > 0) {
                    existingKlasifikasi.forEach(item => tambahRowKlasifikasi(item));
                } else {
                    tambahRowKlasifikasi();
                }

                if (existingAnalisa.length > 0) {
                    existingAnalisa.forEach(item => tambahRowAnalisa(item));
                } else {
                    tambahRowAnalisa();
                }

            });

        </script>

    </section>

</main>



      
    </section>
</main>