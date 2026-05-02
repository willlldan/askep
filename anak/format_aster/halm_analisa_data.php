<?php
require_once "koneksi.php";
require_once "utils.php";

$form_id       = 6;
$level         = $_SESSION['level'];
$user_id       = $_SESSION['id_user'];
$section_name  = 'analisa_data';
$section_label = 'Analisa Data';

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

$existing_klasifikasi = $existing_data['klasifikasi'] ?? [];
$existing_analisa     = $existing_data['analisa']     ?? [];

$is_dosen    = $level === 'Dosen';
$is_readonly = $is_dosen || isLocked($submission);
$ro          = $is_readonly ? 'readonly' : '';
$ro_disabled = $is_readonly ? 'disabled' : '';

// =============================================
// HANDLE POST - MAHASISWA
// =============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $level === 'Mahasiswa') {
    if (isLocked($submission)) {
        redirectWithMessage($_SERVER['REQUEST_URI'], 'error', 'Data tidak dapat diubah karena sedang dalam proses review.');
    }

    // Klasifikasi Data (DS / DO)
    $klasifikasi = [];
    if (!empty($_POST['klasifikasi'])) {
        foreach ($_POST['klasifikasi'] as $row) {
            if (empty($row['ds']) && empty($row['do'])) continue;
            $klasifikasi[] = [
                'ds' => $row['ds'] ?? '',
                'do' => $row['do'] ?? '',
            ];
        }
    }

    // Analisa Data (DS/DO + Data + Etiologi + Masalah)
    $analisa = [];
    if (!empty($_POST['analisa'])) {
        foreach ($_POST['analisa'] as $row) {
            if (empty($row['dsdo']) && empty($row['data']) && empty($row['etiologi']) && empty($row['masalah'])) continue;
            $analisa[] = [
                'dsdo'     => $row['dsdo']     ?? '',
                'data'     => $row['data']     ?? '',
                'etiologi' => $row['etiologi'] ?? '',
                'masalah'  => $row['masalah']  ?? '',
            ];
        }
    }

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
// HANDLE POST - DOSEN
// =============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $level === 'Dosen') {
    $submission_id = $submission['id'];
    $dosen_id      = $user_id;
    $action        = $_POST['action'] ?? '';
    $comment       = $_POST['comment'] ?? '';

    if ($action === 'approve') {
        updateSectionStatus($submission_id, $section_name, 'approved', $mysqli);
        if (!empty($comment)) saveComment($submission_id, $section_name, $comment, $dosen_id, $mysqli);
    } elseif ($action === 'revision') {
        if (empty($comment)) redirectWithMessage($_SERVER['REQUEST_URI'], 'error', 'Komentar wajib diisi saat meminta revisi.');
        updateSectionStatus($submission_id, $section_name, 'revision', $mysqli);
        saveComment($submission_id, $section_name, $comment, $dosen_id, $mysqli);
    }

    updateReviewer($submission_id, $dosen_id, $mysqli);
    updateSubmissionStatusByDosen($submission_id, $form_id, $mysqli);
    redirectWithMessage($_SERVER['REQUEST_URI'], 'success', 'Berhasil disimpan.');
}

$comments = $submission ? getSectionComments($submission['id'], $section_name, $mysqli) : [];
?>

<main id="main" class="main">
    <?php include "anak/format_aster/tab.php"; ?>

    <section class="section dashboard">

        <?php include dirname(__DIR__, 2) . '/partials/notifikasi.php'; ?>
        <?php include dirname(__DIR__, 2) . '/partials/status_section.php'; ?>

        <form class="needs-validation" novalidate action="" method="POST">

            <!-- ===================== KLASIFIKASI DATA ===================== -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><strong>Format Klasifikasi Data</strong></h5>

                    <p class="text-primary fw-bold mb-2">Klasifikasi Data</p>

                    <style>
                        .table-klasifikasidata {
                            table-layout: fixed;
                            width: 100%;
                        }

                        .table-klasifikasidata td,
                        .table-klasifikasidata th {
                            word-wrap: break-word;
                            white-space: normal;
                            vertical-align: top;
                        }
                    </style>

                    <table class="table table-bordered table-klasifikasidata" id="tabel-klasifikasi">
                        <thead>
                            <tr>
                                <th class="text-center" style="width:40px">No</th>
                                <th class="text-center">Data Subjektif (DS)</th>
                                <th class="text-center">Data Objektif (DO)</th>
                                <?php if (!$is_readonly): ?>
                                    <th class="text-center" style="width:60px">Aksi</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody id="tbody-klasifikasi"></tbody>
                    </table>

                    <?php if (!$is_readonly): ?>
                        <div class="row mb-4">
                            <div class="col-sm-12 d-flex justify-content-end">
                                <button type="button" class="btn btn-primary btn-sm" id="btn-tambah-klasifikasi"
                                    onclick="tambahRowKlasifikasi()">+ Tambah Baris</button>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <?php include dirname(__DIR__, 2) . '/partials/footer_form.php'; ?>

    </section>
</main>

<script>
    let rowKlasifikasiCount = 1;
    let rowAnalisaCount = 1;
    const isReadonly = <?= json_encode($is_readonly) ?>;
    const existingKlasifikasi = <?= json_encode($existing_klasifikasi) ?>;
    const existingAnalisa = <?= json_encode($existing_analisa) ?>;

    // =============================================
    // KLASIFIKASI DATA
    // =============================================
    function tambahRowKlasifikasi(data = null) {
        const tbody = document.getElementById('tbody-klasifikasi');
        const index = rowKlasifikasiCount;
        const row = document.createElement('tr');

        const aksiCol = isReadonly ? '' : `
        <td class="text-center align-middle">
            <button type="button" class="btn btn-danger btn-sm" onclick="hapusRow(this)">x</button>
        </td>`;

        row.innerHTML = `
        <td class="text-center align-middle">${index}</td>
        <td>
            <textarea class="form-control form-control-sm"
                name="klasifikasi[${index}][ds]"
                rows="2" style="resize:none; overflow:hidden;"
                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                ${isReadonly ? 'readonly' : ''}
            >${data?.ds ?? ''}</textarea>
        </td>
        <td>
            <textarea class="form-control form-control-sm"
                name="klasifikasi[${index}][do]"
                rows="2" style="resize:none; overflow:hidden;"
                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                ${isReadonly ? 'readonly' : ''}
            >${data?.do ?? ''}</textarea>
        </td>
        ${aksiCol}
    `;

        tbody.appendChild(row);
        rowKlasifikasiCount++;
    }

    // =============================================
    // ANALISA DATA
    // =============================================
    function tambahRowAnalisa(data = null) {
        const tbody = document.getElementById('tbody-analisa');
        const index = rowAnalisaCount;
        const row = document.createElement('tr');

        const aksiCol = isReadonly ? '' : `
        <td class="text-center align-middle">
            <button type="button" class="btn btn-danger btn-sm" onclick="hapusRow(this)">x</button>
        </td>`;

        row.innerHTML = `
        <td class="text-center align-middle">${index}</td>
        <td>
            <textarea class="form-control form-control-sm"
                name="analisa[${index}][dsdo]"
                rows="2" style="resize:none; overflow:hidden;"
                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                ${isReadonly ? 'readonly' : ''}
            >${data?.dsdo ?? ''}</textarea>
        </td>
        <td>
            <textarea class="form-control form-control-sm"
                name="analisa[${index}][data]"
                rows="2" style="resize:none; overflow:hidden;"
                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                ${isReadonly ? 'readonly' : ''}
            >${data?.data ?? ''}</textarea>
        </td>
        <td>
            <textarea class="form-control form-control-sm"
                name="analisa[${index}][etiologi]"
                rows="2" style="resize:none; overflow:hidden;"
                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                ${isReadonly ? 'readonly' : ''}
            >${data?.etiologi ?? ''}</textarea>
        </td>
        <td>
            <textarea class="form-control form-control-sm"
                name="analisa[${index}][masalah]"
                rows="2" style="resize:none; overflow:hidden;"
                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                ${isReadonly ? 'readonly' : ''}
            >${data?.masalah ?? ''}</textarea>
        </td>
        ${aksiCol}
    `;

        tbody.appendChild(row);
        rowAnalisaCount++;
    }

    function hapusRow(btn) {
        btn.closest('tr').remove();
    }

    // Load existing data on page load
    window.addEventListener('load', function() {
        if (existingKlasifikasi && existingKlasifikasi.length > 0) {
            existingKlasifikasi.forEach(row => tambahRowKlasifikasi(row));
        } else if (!isReadonly) {
            tambahRowKlasifikasi();
        }

        if (existingAnalisa && existingAnalisa.length > 0) {
            existingAnalisa.forEach(row => tambahRowAnalisa(row));
        } else if (!isReadonly) {
            tambahRowAnalisa();
        }

        // Disable tombol tambah jika readonly
        if (isReadonly) {
            const btnK = document.getElementById('btn-tambah-klasifikasi');
            const btnA = document.getElementById('btn-tambah-analisa');
            if (btnK) btnK.setAttribute('disabled', 'disabled');
            if (btnA) btnA.setAttribute('disabled', 'disabled');
        }
    });
</script>