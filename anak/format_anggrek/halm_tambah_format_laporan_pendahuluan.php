<?php
require_once "koneksi.php";
require_once "utils.php";

$form_id       = 8;
$level         = $_SESSION['level'];
$user_id       = $_SESSION['id_user'];
$section_name  = 'format_lp';
$section_label = 'Format Laporan Pendahuluan';

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
$existing_obat = $existing_data['obat'] ?? [];


// =============================================
// HANDLE POST - MAHASISWA SIMPAN DATA
// =============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $level === 'Mahasiswa') {

    if (isLocked($submission)) {
        redirectWithMessage($_SERVER['REQUEST_URI'], 'error', 'Data tidak dapat diubah karena sedang dalam proses review.');
    }

    $tgl_pengkajian = $_POST['tglpengkajian'] ?? '';
    $rs_ruangan     = $_POST['rsruangan'] ?? '';
        // Proses dynamic rows obat
    $obat = [];
    if (!empty($_POST['obat'])) {
        foreach ($_POST['obat'] as $index => $row) {
            if (empty($row['diagnosa']) && empty($row['tujuan']) && empty($row['intervensi'])) {
                continue;
            }
            $obat[] = [
                'diagnosa'          => $row['diagnosa']           ?? '',
                'tujuan'            => $row['tujuan']        ?? '',
                'intervensi'        => $row['intervensi']  ?? '',
            ];
        }
    }
    $data = [
                'obat' => $obat,
            'tglpengkajian'             => $_POST['tglpengkajian'] ?? '',
            'rsruangan'                 => $_POST['rsruangan'] ?? '',
            'pengertian'                => $_POST['pengertian'] ?? '',
            'etiologi'                => $_POST['etiologi'] ?? '',
            'patofisiologi'            => $_POST['patofisiologi'] ?? '',
            'manifestasi_klinik'        => $_POST['manifestasi_klinik'] ?? '',
            'pemeriksaan_diagnostic'    => $_POST['pemeriksaan_diagnostic'] ?? '',
            'penatalaksanaan'           => $_POST['penatalaksanaan'] ?? '',
            'komplikasi'                => $_POST['komplikasi'] ?? '',
            'pengkajian_keperawatan'    => $_POST['pengkajian_keperawatan'] ?? '',
            'penyimpangan_kdm'          => $_POST['penyimpangan_kdm'] ?? '',
            'diagnosa_keperawatan'      => $_POST['diagnosa_keperawatan'] ?? '',
            'daftar_pustaka'            => $_POST['daftar_pustaka'] ?? '',
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

    <?php include "anak/format_anggrek/tab.php"; ?>

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
        <div class="card mt-3">
            <div class="card-body">
                <form class="needs-validation" novalidate action="" method="POST">

                    <div class="row mb-3 mt-3">
                        <label class="col-sm-2 col-form-label"><strong>Tanggal Pengkajian</strong></label>
                        <div class="col-sm-9">
                            <input type="date" class="form-control" name="tglpengkajian"
                                value="<?= htmlspecialchars($tgl_pengkajian) ?>" <?= $ro ?> required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>RS/Ruangan</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="rsruangan"
                                value="<?= htmlspecialchars($rs_ruangan) ?>" <?= $ro ?> required>
                        </div>
                    </div>

                                
                     <!-- General Form Elements -->

<!-- FORMAT LAPORAN PENDAHULUAN -->
<h5 class="card-title"><strong>FORMAT LAPORAN PENDAHULUAN</strong></h5>

<!-- A. Konsep Dasar Medis -->
<div class="row mb-2">
    <label class="col-sm-3 col-form-label text-primary">
        <strong>A. Konsep Dasar Medis</strong>
    </label>
</div>

<!-<!-- A. Landasan Teori -->
<!-- 1. Pengertian -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>1. Pengertian</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="pengertian" value="<?= val('pengertian', $existing_data) ?>" <?= $ro ?> required>
    </div>
</div>

<!-- 2. Etiologi -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>2. Etiologi</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="etiologi" value="<?= val('etiologi', $existing_data) ?>" <?= $ro ?> required>
    </div>
</div>

<!-- 3. Patofisiologi -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>3. Patofisiologi</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="patofisiologi" value="<?= val('patofisiologi', $existing_data) ?>" <?= $ro ?> required>
    </div>
</div>

<!-- 4. Manifestasi Klinik -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>4. Manifestasi Klinik</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="manifestasi_klinik" value="<?= val('manifestasi_klinik', $existing_data) ?>" <?= $ro ?> required>
    </div>
</div>

<!-- 5. Pemeriksaan Diagnostic -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>5. Pemeriksaan Diagnostic</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="pemeriksaan_diagnostic" value="<?= val('pemeriksaan_diagnostic', $existing_data) ?>" <?= $ro ?> required>
    </div>
</div>

<!-- 6. Penatalaksanaan -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>6. Penatalaksanaan</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="penatalaksanaan" value="<?= val('penatalaksanaan', $existing_data) ?>" <?= $ro ?> required>
    </div>
</div>

<!-- 7. Komplikasi -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>7. Komplikasi</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="komplikasi" value="<?= val('komplikasi', $existing_data) ?>" <?= $ro ?> required>
    </div>
</div>

<!-- B. Konsep Dasar Keperawatan -->
<div class="row mb-2">
    <label class="col-sm-5 col-form-label text-primary"><strong>B. Konsep Dasar Keperawatan</strong></label>
</div>

<!-- 1. Pengkajian Keperawatan -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>1. Pengkajian Keperawatan</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="pengkajian_keperawatan" value="<?= val('pengkajian_keperawatan', $existing_data) ?>" <?= $ro ?> required>
    </div>
</div>

<!-- 2. Penyimpangan KDM -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>2. Penyimpangan KDM</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="penyimpangan_kdm" value="<?= val('penyimpangan_kdm', $existing_data) ?>" <?= $ro ?> required>
    </div>
</div>

<!-- 3. Diagnosa Keperawatan -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>3. Diagnosa Keperawatan</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="diagnosa_keperawatan" value="<?= val('diagnosa_keperawatan', $existing_data) ?>" <?= $ro ?> required>
    </div>
</div>

<!-- 4. Perencanaan -->
<p class="text-primary fw-bold mb-2">4. Perencanaan</p>
<table class="table table-bordered" id="tabel-obat">
    <thead>
        <tr>
            <th class="text-center" style="width:40px">No</th>
            <th class="text-center">Diagnosa Keperawatan</th>
            <th class="text-center">Tujuan & Kriteria Hasil</th>
            <th class="text-center">Intervensi</th>
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

<!-- C. Daftar Pustaka -->
<div class="row mt-4 mb-2">
    <label class="col-sm-3 col-form-label text-primary"><strong>C. Daftar Pustaka</strong></label>
</div>
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="daftar_pustaka" value="<?= val('daftar_pustaka', $existing_data) ?>" <?= $ro ?> required>
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
                        let rowObatCount = 1;
                  
                        const existingObat = <?= json_encode($existing_obat) ?>;
                        
                        const isReadonly = <?= json_encode($is_readonly) ?>;
                        // ---- OBAT ----
                        function tambahRowObat(data = null) {
                            const tbody = document.getElementById('tbody-obat');
                            const index = rowObatCount;
                            const row   = document.createElement('tr');
                            row.innerHTML = `
                                <td class="text-center align-middle">${index}</td>
                                <td><input type="text" class="form-control form-control-sm" name="obat[${index}][diagnosa]" value="${data?.diagnosa ?? ''}" ${isReadonly ? 'readonly' : ''}></td>
                                <td><input type="text" class="form-control form-control-sm" name="obat[${index}][tujuan]" value="${data?.tujuan ?? ''}" ${isReadonly ? 'readonly' : ''}></td>
                                <td><input type="text" class="form-control form-control-sm" name="obat[${index}][intervensi]" value="${data?.intervensi ?? ''}" ${isReadonly ? 'readonly' : ''}></td>
                                <td class="text-center align-middle">
                                    <button type="button" class="btn btn-danger btn-sm" onclick="hapusRow(this)" ${isReadonly ? 'disabled' : ''}>x</button>
                                </td>
                            `;
                            tbody.appendChild(row);
                            rowObatCount++;
                        }
                       
                        function hapusRow(btn) {
                            btn.closest('tr').remove();
                        }
                        // Load existing rows on page load
                        window.addEventListener('load', function () {
                            if (existingObat && existingObat.length > 0) {
                                existingObat.forEach(row => tambahRowObat(row));
                            } else {
                                tambahRowObat(); // default 1 row kosong
                            }
                            
                            
                            // Disable add buttons if readonly
                            if (isReadonly) {
                                document.getElementById('btn-tambah-obat').setAttribute('disabled', 'disabled');
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



    


