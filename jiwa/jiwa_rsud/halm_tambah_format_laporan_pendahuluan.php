<?php
require_once "koneksi.php";
require_once "utils.php";

$form_id       = 14;
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
$existing_evaluasi     = $existing_data['evaluasi']     ?? [];

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
        $evaluasi = [];
        if (!empty($_POST['evaluasi'])) {
            foreach ($_POST['evaluasi'] as $index => $row) {
                if (empty($row['masalah']) && empty($row['data_dikaji'])) {
                    continue;
                }
                $evaluasi[] = [
                    'masalah'      => $row['masalah']      ?? '',
                    'data_dikaji_subjektif'         => $row['data_dikaji_subjektif']   ?? '',
                    'data_dikaji_objektif'          => $row['data_dikaji_objektif']         ?? '',

                ];
            }
        }
    $data = [
        'evaluasi'     => $evaluasi,
        'masalah_keperawatan_utama' => $_POST['masalah_keperawatan_utama'] ?? '',
        'pengertian'                => $_POST['pengertian'] ?? '',
        'gejala_tanda'              => $_POST['gejala_tanda'] ?? '',
        'rentang_respons'           => $_POST['rentang_respons'] ?? '',
        'faktor_predisposisi'      => $_POST['faktor_predisposisi'] ?? '',
        'faktor_presipitasi'       => $_POST['faktor_presipitasi'] ?? '',
        'sumber_koping'            => $_POST['sumber_koping'] ?? '',
        'mekanisme_koping'         => $_POST['mekanisme_koping'] ?? '',
        'pohon_masalah'            => $_POST['pohon_masalah'] ?? '',
        'masalah_keperawatan_muncul'=> $_POST['masalah_keperawatan_muncul'] ?? '',
        'masalah_keperawatan'      => $_POST['masalah_keperawatan'] ?? '',
        'subjektif'                => $_POST['subjektif'] ?? '',
        'objektif'                 => $_POST['objektif'] ?? '',
        'diagnosa_muncul'          => $_POST['diagnosa_muncul'] ?? '',
        'rencana_tindakan'         => $_POST['rencana_tindakan'] ?? '',
        'daftar_pustaka'           => $_POST['daftar_pustaka'] ?? ''
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

    <?php include "jiwa/jiwa_rsud/tab.php"; ?>

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
        <div class="card-body mt-3">

        <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">
    
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


                <h5 class="card-title"><strong>FORMAT LAPORAN PENDAHULUAN PRAKTIK KLINIK KEPERAWATAN JIWA</strong></h5>

                <div class="row mb-2">
                    <label class="col-sm-4 col-form-label text-primary">
                        <strong>A. Masalah Keperawatan Utama</strong>
                    </label>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><strong>Masalah Keperawatan Utama</strong></label>
                    <div class="col-sm-10">
                        <textarea name="masalah_keperawatan_utama" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= val('masalah_keperawatan_utama', $existing_data) ?></textarea>
                    </div>
                </div>

                <div class="row mb-2">
                    <label class="col-sm-4 col-form-label text-primary">
                        <strong>B. Proses Terjadinya Masalah</strong>
                    </label>
                </div>

               <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><strong>1. Pengertian</strong></label>
                    <div class="col-sm-10">
                        <textarea name="pengertian" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= val('pengertian', $existing_data) ?></textarea>
                    </div>
                </div>

             
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><strong>2. Tanda dan Gejala</strong></label>
                    <div class="col-sm-10">
                        <textarea name="gejala_tanda" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= val('gejala_tanda', $existing_data) ?></textarea>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><strong>3. Rentang Respons</strong></label>
                    <div class="col-sm-10">
                        <textarea name="rentang_respons" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= val('rentang_respons', $existing_data) ?></textarea>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><strong>4. Faktor Predisposisi</strong></label>
                    <div class="col-sm-10">
                        <textarea name="faktor_predisposisi" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= val('faktor_predisposisi', $existing_data) ?></textarea>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><strong>5. Faktor Presipitasi</strong></label>
                    <div class="col-sm-10">
                        <textarea name="faktor_presipitasi" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= val('faktor_presipitasi', $existing_data) ?></textarea>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><strong>6. Sumber Koping</strong></label>
                    <div class="col-sm-10">
                        <textarea name="sumber_koping" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= val('sumber_koping', $existing_data) ?></textarea>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><strong>7. Mekanisme Koping</strong></label>
                    <div class="col-sm-10">
                        <textarea name="mekanisme_koping" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= val('mekanisme_koping', $existing_data) ?></textarea>
                    </div>
                </div>

               <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><strong>8. Pohon Masalah</strong></label>
                    <div class="col-sm-10">
                        <textarea name="pohon_masalah" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= val('pohon_masalah', $existing_data) ?></textarea>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><strong>9. Masalah Keperawatan yang Mungkin Muncul</strong></label>
                    <div class="col-sm-10">
                        <textarea name="masalah_keperawatan_muncul" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= val('masalah_keperawatan_muncul', $existing_data) ?></textarea>
                    </div>
                </div>
                <!-- ===================== TABEL EVALUASI ===================== -->
                    <p class="text-primary fw-bold mb-2">10. Data yang perlu dikaji</p>

                    <table class="table table-bordered" id="tabel-evaluasi">
                        <thead>
                            <tr>
                                <th class="text-center" style="width:70px">Masalah Keperawatan</th>
                                <th class="text-center" style="width:150px">Data yang Perlu Dikaji</th>
                                
                            </tr>
                        </thead>
                        <tbody id="tbody-evaluasi">
                            <!-- Dynamic rows masuk sini -->
                        </tbody>
                    </table>

                    <?php if (!$is_readonly): ?>
                        <div class="row mb-4">
                            <div class="col-sm-12 d-flex justify-content-end">
                                <button type="button" class="btn btn-primary btn-sm" onclick="tambahRowEvaluasi()">+ Tambah Data</button>
                            </div>
                        </div>
                    <?php endif; ?>


                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><strong>11. Diagnosa Keperawatan yang Mungkin Muncul</strong></label>
                    <div class="col-sm-10">
                        <textarea name="diagnosa_muncul" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= val('diagnosa_muncul', $existing_data) ?></textarea>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><strong>12. Rencana Tindakan Keperawatan</strong></label>
                    <div class="col-sm-10">
                        <textarea name="rencana_tindakan" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= val('rencana_tindakan', $existing_data) ?></textarea>
                    </div>
                </div>

                <div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>13. Daftar Pustaka</strong></label>
    <div class="col-sm-10">
        <textarea name="daftar_pustaka" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= val('daftar_pustaka', $existing_data) ?></textarea>
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
    let rowEvaluasiCount = 1;

    const existingEvaluasi = <?= json_encode($existing_evaluasi) ?>;

    // ---- EVALUASI ----
    function tambahRowEvaluasi(data = null) {
        const tbody = document.getElementById('tbody-evaluasi');
        const index = rowEvaluasiCount;
        const row = document.createElement('tr');
        const isReadonly = <?= json_encode($is_readonly) ?>;

        row.innerHTML = `
            <td class="col-6">
                <input type="text" class="form-control form-control-sm" name="evaluasi[${index}][masalah]" value="${data?.masalah ?? ''}" ${isReadonly ? 'readonly' : ''}>
            </td>

            <td class="col-6">
                <div class="d-flex flex-column">
                    <!-- Evaluasi Subjektif -->
                    <div class="mb-1 d-flex align-items-start gap-2">
                        <label class="form-label form-label-sm fw-bold mb-0" style="width:20px;">S</label>
                        <input
                            type="text"
                            class="form-control form-control-sm"
                            name="evaluasi[${index}][data_dikaji_subjektif]"
                            value="${data?.data_dikaji_subjektif ?? ''}"
                            ${isReadonly ? 'readonly' : ''}
                        >
                    </div>

                    <!-- Evaluasi Objektif -->
                    <div class="mb-1 d-flex align-items-start gap-2">
                        <label class="form-label form-label-sm fw-bold mb-0" style="width:20px;">O</label>
                        <input
                            type="text"
                            class="form-control form-control-sm"
                            name="evaluasi[${index}][data_dikaji_objektif]"
                            value="${data?.data_dikaji_objektif ?? ''}"
                            ${isReadonly ? 'readonly' : ''}
                        >
                    </div>
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
        if (existingEvaluasi && existingEvaluasi.length > 0) {
            existingEvaluasi.forEach(row => tambahRowEvaluasi(row));
        } else {
            tambahRowEvaluasi();
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
        </div>
    </div>
   
</section>
</main>