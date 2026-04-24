<?php
require_once "koneksi.php";
require_once "utils.php";

$form_id       = 17;
$level         = $_SESSION['level'];
$user_id       = $_SESSION['id_user'];
$section_name  = 'lp_ruanghd';
$section_label = 'Format Laporan Pendahuluan Ruang HD';

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

// =============================================
// HANDLE POST - MAHASISWA SIMPAN DATA
// =============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $level === 'Mahasiswa') {

    if (isLocked($submission)) {
        redirectWithMessage($_SERVER['REQUEST_URI'], 'error', 'Data tidak dapat diubah karena sedang dalam proses review.');
    }

    $tgl_pengkajian = $_POST['tglpengkajian'] ?? '';
    $rs_ruangan     = $_POST['rsruangan'] ?? '';

    $data = [
            'definisi'                      => $_POST['definisi'] ?? '',
            'klasifikasi'                   => $_POST['klasifikasi'] ?? '',
            'etiologi'                      => $_POST['etiologi'] ?? '',
            'manifestasi_klinik'             => $_POST['manifestasiklinik'] ?? '',
            'patofisiologi'                 => $_POST['patofisiologi'] ?? '',
            'penunjang'                     => $_POST['penunjang'] ?? '',
            'penatalaksanaan'               => $_POST['penatalaksanaan'] ?? '',
            'komplikasi'                    => $_POST['komplikasi'] ?? '',
            'pengertian'                    => $_POST['pengertian'] ?? '',
            'tujuan'                        => $_POST['tujuan'] ?? '',
            'proses_hemodialisa'            => $_POST['proses_hemodialisa'] ?? '',
            'alasan_hemodialisa'            => $_POST['alasanhemodialisa'] ?? '',
            'indikasi_hemodialisa'          => $_POST['indikasihemodialisa'] ?? '',
            'kontraindikasi_hemodialisa'    => $_POST['kontraindikasihemodialisa'] ?? '',
            'frekuensi_hemodialisa'         => $_POST['frekuensihemodialisa'] ?? '',
            'komplikasi1'                    => $_POST['komplikasi1'] ?? '',
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
    <?php include "kmb/format_hd_kmb/tab.php"; ?>
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
                                
                     <!-- General Form Elements -->
               <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">
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

                    <h5 class="card-title"><strong>A.  Konsep Dasar Penyakit (Chronic Kidney Disease (CKD))</strong></h5>
                                 <!-- A KONSEP DASAR MEDIS -->

                            <div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>1. Definisi</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="definisi" value="<?= val('definisi', $existing_data) ?>" <?= $ro ?>>
        </div>
       
</div>

                    <div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>2. Klasifikasi</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="klasifikasi" value="<?= val('klasifikasi', $existing_data) ?>" <?= $ro ?>>
</div>
</div>

<div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>3. Etiologi</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="etiologi" value="<?= val('etiologi', $existing_data) ?>" <?= $ro ?>>
  
        </div>
        </div>

<div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>4. Manifestasi Klinik</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="manifestasiklinik" value="<?= val('manifestasi_klinik', $existing_data) ?>" <?= $ro ?>>
    
         </div>
        </div>

<div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>5. Patofisiologi</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="patofisiologi" value="<?= val('patofisiologi', $existing_data) ?>" <?= $ro ?>>
   
         </div>
        </div>

<div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>6. Pemeriksaan penunjang</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="penunjang" value="<?= val('penunjang', $existing_data) ?>" <?= $ro ?>>
   
         </div>
        </div>

<div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>7. Penatalaksanaan</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="penatalaksanaan" value="<?= val('penatalaksanaan', $existing_data) ?>" <?= $ro ?>>
     
         </div>
        </div>

<div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>8. Komplikasi</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="komplikasi" value="<?= val('komplikasi', $existing_data) ?>" <?= $ro ?>>
     
         </div>
        </div>
  
        </div>
        </div>
        </div>
</div>
</section>
        
        
                <div class="card">
             <div class="card-body">
                    <div class="row mb-2">
                        <label class="col-sm-4 col-form-label text-primary">
                            <strong>B.  Konsep Dasar Hemodialisa</strong>
                    </div>
<div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>1. Pengertian</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="pengertian" value="<?= val('pengertian', $existing_data) ?>" <?= $ro ?>>
        
         </div>
        </div>
            
<div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>2. Tujuan</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="tujuan" value="<?= val('tujuan', $existing_data) ?>" <?= $ro ?>>
      
         </div>
        </div>
          
<div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>3. Proses Hemodialisa</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="proses_hemodialisa" value="<?= val('proses_hemodialisa', $existing_data) ?>" <?= $ro ?>>
       
         </div>
        </div>
          
<div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>4. Alasan dilakukan Hemodialisa</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="alasanhemodialisa" value="<?= val('alasan_hemodialisa', $existing_data) ?>" <?= $ro ?>>
       
         </div>
        </div>
<div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>5. Indikasi Hemodialisa</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="indikasihemodialisa" value="<?= val('indikasi_hemodialisa', $existing_data) ?>" <?= $ro ?>>
      
         </div>
        </div>
<div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>6. Kontraindikasi Hemodialisa</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="kontraindikasihemodialisa" value="<?= val('kontraindikasi_hemodialisa', $existing_data) ?>" <?= $ro ?>>
       
          </div>
        </div>
                <div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>7.   Frekuensi Hemodialisa</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="frekuensihemodialisa" value="<?= val('frekuensi_hemodialisa', $existing_data) ?>" <?= $ro ?>>
        
          </div>
        </div>
                <div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>8.   Komplikasi Hemodialisa</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="komplikasi1" value="<?= val('komplikasi1', $existing_data) ?>" <?= $ro ?>>
       
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
        

        <?php include "tab_navigasi.php"; ?>

    </section>
</main>

                        



