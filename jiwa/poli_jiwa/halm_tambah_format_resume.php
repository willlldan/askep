<?php
require_once "koneksi.php";
require_once "utils.php";

$form_id       = 15;
$level         = $_SESSION['level'];
$user_id       = $_SESSION['id_user'];
$section_name  = 'format_resume';
$section_label = 'Format Resume Keperawatan Jiwa';

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
$tgl_pengkajian = $submission['tanggal_pengkajian'] ?? '';
$rs_ruangan     = $submission['rs_ruangan'] ?? '';
$existing_analisa     = $existing_data['analisa']     ?? [];

// =============================================
// HANDLE POST - MAHASISWA SIMPAN DATA
// =============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $level === 'Mahasiswa') {

    if (isLocked($submission)) {
        redirectWithMessage($_SERVER['REQUEST_URI'], 'error', 'Data tidak dapat diubah karena sedang dalam proses review.');
    }

    $tgl_pengkajian = $_POST['tglpengkajian'] ?? '';
    $rs_ruangan     = $_POST['rsruangan'] ?? '';
    // Proses dynamic rows evaluasi
         $analisa = [];
    if (!empty($_POST['analisa'])) {
        foreach ($_POST['analisa'] as $index => $row) {
            if (empty($row['data_subjektif_analisa']) && empty($row['data_objektif_analisa']) && empty($row['masalah'])) {
                continue;
            }
            $analisa[] = [
                'data_subjektif_analisa'   => $row['data_subjektif_analisa']   ?? '',
                'data_objektif_analisa' => $row['data_objektif_analisa'] ?? '',
                'masalah'  => $row['masalah']  ?? '',
            ];
        }
    }
    $data = [
        'analisa'     => $analisa,
    'nama'                 => $_POST['nama'] ?? '',
    'umur'                 => $_POST['umur'] ?? '',
    'agama'                => $_POST['agama'] ?? '',
    'status_perkawinan'    => $_POST['status_perkawinan'] ?? '',
    'pekerjaan'            => $_POST['pekerjaan'] ?? '',
    'alamat'               => $_POST['alamat'] ?? '',
    'kunjungan_ke'         => $_POST['kunjungan_ke'] ?? '',
    'diagnosa_medis'       => $_POST['diagnosa_medis'] ?? '',
    'tanggal_pengkajian'   => $_POST['tanggal_pengkajian'] ?? '',
    'alasan_masuk'                  => $_POST['alasan_masuk'] ?? '',
    'riwayat_kesehatan_masa_lalu'  => $_POST['riwayat_kesehatan_masa_lalu'] ?? '',
    'data_subjektif'                => $_POST['data_subjektif'] ?? '',
    'data_objektif'                 => $_POST['data_objektif'] ?? '',
    'daftar_masalah'                => $_POST['daftar_masalah'] ?? '',
    'efek'                          => $_POST['efek'] ?? '',
    'cara_problem'                  => $_POST['cara_problem'] ?? '',
    'etilogoi'                      => $_POST['etilogoi'] ?? ''
];

    if (!$submission) {
        $submission_id = createSubmission($user_id, $form_id, $tgl_pengkajian, $rs_ruangan, $mysqli);
    } else {
        $submission_id = $submission['id'];
        updateSubmissionHeader($submission_id, $tgl_pengkajian, $rs_ruangan, $mysqli);
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

    <?php include "jiwa/poli_jiwa/tab.php"; ?>

    <section class="section dashboard">

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?= $_SESSION['success'];
                                                unset($_SESSION['success']); ?></div>
        <?php endif; ?>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= $_SESSION['error'];
                                            unset($_SESSION['error']); ?></div>
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
            <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">
                
                <h5 class="card-title"><strong>FORMAT LAPORAN PENDAHULUAN PRAKTIK KLINIK KEPERAWATAN JIWA</strong></h5>

   <div class="row mb-3">
    <div class="col-sm-12">
        <label class="text-primary"><strong>A. PENGKAJIAN</strong></label>
    </div>
</div>

<!-- IDENTITAS KLIEN -->
<div class="row mb-3">
    <div class="col-sm-12"><label><strong>1. IDENTITAS KLIEN</strong></label></div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Nama</strong></label>
    <div class="col-sm-10">
        <input type="text" name="nama" class="form-control"
               value="<?= val('nama', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Umur</strong></label>
    <div class="col-sm-10">
        <input type="number" name="umur" class="form-control"
               value="<?= val('umur', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Agama</strong></label>
    <div class="col-sm-10">
        <input type="text" name="agama" class="form-control"
               value="<?= val('agama', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Status Perkawinan</strong></label>
    <div class="col-sm-10">
        <input type="text" name="status_perkawinan" class="form-control"
               value="<?= val('status_perkawinan', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Pekerjaan</strong></label>
    <div class="col-sm-10">
        <input type="text" name="pekerjaan" class="form-control"
               value="<?= val('pekerjaan', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Alamat</strong></label>
    <div class="col-sm-10">
        <textarea name="alamat" class="form-control" rows="3"
                  style="overflow:hidden; resize:none;"
                  oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                  <?= $ro ?>><?= val('alamat', $existing_data) ?></textarea>
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Kunjungan ke</strong></label>
    <div class="col-sm-10">
        <input type="text" name="kunjungan_ke" class="form-control"
               value="<?= val('kunjungan_ke', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Diagnosa Medis</strong></label>
    <div class="col-sm-10">
        <textarea name="diagnosa_medis" class="form-control" rows="3"
                  style="overflow:hidden; resize:none;"
                  oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                  <?= $ro ?>><?= val('diagnosa_medis', $existing_data) ?></textarea>
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Tanggal Pengkajian</strong></label>
    <div class="col-sm-10">
        <input type="date" name="tanggal_pengkajian" class="form-control"
               value="<?= val('tanggal_pengkajian', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>
<!-- 2. Alasan Masuk -->
<div class="row mb-2">
    <label class="col-sm-3 col-form-label">
        <strong>2. Alasan Masuk</strong>
    </label>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Alasan Masuk</strong></label>
    <div class="col-sm-10">
        <textarea name="alasan_masuk" class="form-control" rows="3"
                  style="overflow:hidden; resize:none;"
                  oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                  <?= $ro ?>><?= val('alasan_masuk', $existing_data) ?></textarea>
    </div>
</div>

<!-- 3. Riwayat Kesehatan Masa Lalu -->
<div class="row mb-2">
    <label class="col-sm-5 col-form-label">
        <strong>3. Riwayat Kesehatan Masa Lalu</strong>
    </label>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Riwayat Kesehatan Masa Lalu</strong></label>
    <div class="col-sm-10">
        <textarea name="riwayat_kesehatan_masa_lalu" class="form-control" rows="3"
                  style="overflow:hidden; resize:none;"
                  oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                  <?= $ro ?>><?= val('riwayat_kesehatan_masa_lalu', $existing_data) ?></textarea>
    </div>
</div>

<!-- 4. Data Fokus -->
<div class="row mb-2">
    <label class="col-sm-4 col-form-label">
        <strong>4. Data Fokus</strong>
    </label>
</div>

<!-- Data Subjektif -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Data Subjektif</strong></label>
    <div class="col-sm-10">
        <textarea name="data_subjektif" class="form-control" rows="3"
                  style="overflow:hidden; resize:none;"
                  oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                  <?= $ro ?>><?= val('data_subjektif', $existing_data) ?></textarea>
    </div>
</div>

<!-- Data Objektif -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Data Objektif</strong></label>
    <div class="col-sm-10">
        <textarea name="data_objektif" class="form-control" rows="3"
                  style="overflow:hidden; resize:none;"
                  oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                  <?= $ro ?>><?= val('data_objektif', $existing_data) ?></textarea>
    </div>
</div>
           <!-- ===================== TABEL ANALISA DATA ===================== -->
                    <p class="text-primary fw-bold mb-2">XIII. Analisa Data</p>
                    <table class="table table-bordered" id="tabel-analisa">
                        <thead>
                            <tr>
                                <th class="text-center" style="width:40px">No</th>
                                <th class="text-center">Data Subjektif</th>
                                <th class="text-center">Data Objektif</th>
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
 <!-- 6. Daftar Masalah Keperawatan -->
<div class="row mb-2">
    <label class="col-sm-5 col-form-label">
        <strong>6. Daftar Masalah Keperawatan</strong>
    </label>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Daftar Masalah Keperawatan</strong></label>
    <div class="col-sm-10">
        <textarea name="daftar_masalah" class="form-control" rows="3"
                  style="overflow:hidden; resize:none;"
                  oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                  <?= $ro ?>><?= val('daftar_masalah', $existing_data) ?></textarea>
    </div>
</div>

<!-- 7. Pohon Masalah -->
<div class="row mb-2">
    <label class="col-sm-5 col-form-label">
        <strong>7. Pohon Masalah</strong>
    </label>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Efek</strong></label>
    <div class="col-sm-10">
        <input type="text" class="form-control" name="efek"
               value="<?= val('efek', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Cara Problem</strong></label>
    <div class="col-sm-9">
        <textarea name="cara_problem" class="form-control" rows="3"
                  style="overflow:hidden; resize:none;"
                  oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                  <?= $ro ?>><?= val('cara_problem', $existing_data) ?></textarea>
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Etiologi</strong></label>
    <div class="col-sm-9">
        <textarea name="etilogoi" class="form-control" rows="3"
                  style="overflow:hidden; resize:none;"
                  oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                  <?= $ro ?>><?= val('etilogoi', $existing_data) ?></textarea>
    </div>
</div>
            
           
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
        <script>
                        let rowAnalisaCount     = 1;
                        const existingAnalisa     = <?= json_encode($existing_analisa) ?>;
                        const isReadonly = <?= json_encode($is_readonly) ?>;
                        
                        // ---- ANALISA DATA ----
                        function tambahRowAnalisa(data = null) {
                            const tbody = document.getElementById('tbody-analisa');
                            const index = rowAnalisaCount;
                            const row   = document.createElement('tr');
                            row.innerHTML = `
                                <td class="text-center align-middle">${index}</td>
                                <td>
                                    <textarea class="form-control form-control-sm" name="analisa[${index}][data_subjektif_analisa]" rows="2" style="resize:none; overflow:hidden;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" ${isReadonly ? 'readonly' : ''}>${data?.data_subjektif_analisa ?? ''}</textarea>
                                </td>
                                <td>
                                    <textarea class="form-control form-control-sm" name="analisa[${index}][data_objektif_analisa]" rows="2" style="resize:none; overflow:hidden;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" ${isReadonly ? 'readonly' : ''}>${data?.data_objektif_analisa ?? ''}</textarea>
                                </td>
                                <td>
                                    <textarea class="form-control form-control-sm" name="analisa[${index}][masalah]" rows="2" style="resize:none; overflow:hidden;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" ${isReadonly ? 'readonly' : ''}>${data?.masalah ?? ''}</textarea>
                                </td>
                                <td class="text-center align-middle">
                                    <button type="button" class="btn btn-danger btn-sm" onclick="hapusRow(this)" ${isReadonly ? 'disabled' : ''}>x</button>
                                </td>
                            `;
                            tbody.appendChild(row);
                            rowAnalisaCount++;
                        }
                        function hapusRow(btn) {
                            btn.closest('tr').remove();
                        }
                        // Load existing rows on page load
                        window.addEventListener('load', function () {
                           
                            if (existingAnalisa && existingAnalisa.length > 0) {
                                existingAnalisa.forEach(row => tambahRowAnalisa(row));
                            } else {
                                tambahRowAnalisa(); // default 1 row kosong
                            }
                            // Disable add buttons if readonly
                            if (isReadonly) {
                                document.getElementById('btn-tambah-analisa').setAttribute('disabled', 'disabled');
                            }
                        });
                        const existingData = <?= json_encode($existing_data) ?>;
                    </script>

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

    </section>
</main>

