<?php
require_once "koneksi.php";
require_once "utils.php";

$form_id       = 3;
$level         = $_SESSION['level'];
$user_id       = $_SESSION['id_user'];
$section_name  = 'data_biologis';
$section_label = 'Data Biologis';

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
        'biologis_fisiologis'       => $_POST['biologisfisiologis'] ?? '',
        'nh'                        => $_POST['nh'] ?? '',
        'p'                         => $_POST['p'] ?? '',
        'a'                         => $_POST['a'] ?? '',
        'bayi_rawat_gabung'         => $_POST['bayirawatgabung'] ?? '',
        'tidak_ada_alasan'          => $_POST['tidakadaalasan'] ?? '',
        'keadaan_umum'               => $_POST['keadaanumum'] ?? '',
        'kesadaran'                 => $_POST['kesadaran'] ?? '',
        'bb_tb'                     => $_POST['bbtb'] ?? '',
        'tekanan_darah'             => $_POST['tekanandarah'] ?? '',
        'nadi'                      => $_POST['nadi'] ?? '',
        'suhu'                      => $_POST['suhu'] ?? '',
        'pernapasan'                => $_POST['pernapasan'] ?? '',
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
    <?php include "navbar_maternitas.php"; ?>
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
     <section class="section dashboard">
        <div class="card">
         

            
            <div class="card">
            <div class="card-body">

                <h5 class="card-title"><strong>DATA UMUM KESEHATAN SAAT INI</strong></h5>
                
                <!-- General Form Elements -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

                <div class="row mb-3">
                        <label class="col-sm-9 col-form-label text-primary">
                            <strong>Data Biologis/Fisiologis</strong>
                        </label>    
                    </div>
                
                <!-- Bagian Data Biologis/Fisiologis -->
                        <div class="row mb-3">
                            <label for="biologisfisiologis" class="col-sm-2 col-form-label"><strong>Data Biologis/Fisiologis</strong></label>
                            <div class="col-sm-9">
                               <textarea name="biologisfisiologis" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                           <?= $ro ?>><?= val('biologis_fisiologis', $existing_data) ?></textarea>

                  
                         </div>
                    </div>
                    
                <!-- Bagian Status Obstetrik -->

                        <div class="row mb-3">

                        <label class="col-sm-2 col-form-label"><strong>Status Obstetrik</strong></label>
                            <div class="col-sm-9">
                                <div class="row">

                        <!-- NH -->
                        <div class="col-md-4 d-flex align-items-center">
                            <label class="me-2"><strong>NH</strong></label>
                            <input type="text" class="form-control" name="nh"
                            value="<?= val('nh', $existing_data) ?>" <?= $ro ?>>
                        </div>

                        <!-- p -->
                        <div class="col-md-4 d-flex align-items-center">
                            <label class="me-2"><strong>P</strong></label>
                            <input type="text" class="form-control" name="p"
                            value="<?= val('p', $existing_data) ?>" <?= $ro ?>>
                        </div>

                        <!-- A -->
                        <div class="col-md-4 d-flex align-items-center">
                            <label class="me-2"><strong>A</strong></label>
                            <input type="text" class="form-control" name="a"
                            value="<?= val('a', $existing_data) ?>" <?= $ro ?>>
                        </div>
                    </div>

                         <!-- comment -->
                        
                         </div>
</div>    
                    
                <!-- Bagian Bayi Rawat Gabung -->
                    <div class="row mb-3">
                        <label for="bayirawatgabung" class="col-sm-2 col-form-label"><strong>Bayi Rawat Gabung</strong></label> 
                        <div class="col-sm-9">
                        <select class="form-select" name="bayirawatgabung" <?= $ro_select ?>>
                                <option value="">Pilih</option>
                                <option value="ya" <?= val('bayi_rawat_gabung', $existing_data) === 'ya' ? 'selected' : '' ?>>Ya</option>
                                <option value="Tidak" <?= val('bayi_rawat_gabung', $existing_data) === 'tidak' ? 'selected' : '' ?>>Tidak</option>
                                </select>

                         </div>
                      
                    </div> 
                    
                    <!-- Bagian Jika Tidak Ada Alasan -->
                        <div class="row mb-3">
                            <label for="tidakadaalasan" class="col-sm-2 col-form-label"><strong>Jika Tidak Ada Alasan</strong></label>
                            <div class="col-sm-9">
                                
                            <textarea name="tidakadaalasan" class="form-control" rows="5" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        
                            <?= $ro ?>><?= val('tidak_ada_alasan', $existing_data) ?> </textarea>
                         </div>
                    </div>
                       
                        <!-- Bagian Keadaan Umum -->
                        <div class="row mb-3">
                            <label for="keadaanumum" class="col-sm-2 col-form-label"><strong>Keadaan Umum</strong></label>
                            <div class="col-sm-9">
                                
                            <textarea name="keadaanumum" class="form-control" rows="5" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('keadaan_umum', $existing_data) ?> </textarea>
                            

                         </div>
                    </div>
                            
                        <!-- Bagian Kesadaran -->
                        <div class="row mb-3">
                            <label for="kesadaran" class="col-sm-2 col-form-label"><strong>Kesadaran</strong></label>
                            <div class="col-sm-9">
                                <textarea name="kesadaran" class="form-control" rows="5" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                           <?= $ro ?>><?= val('kesadaran', $existing_data) ?></textarea>
 
                         </div>
                    </div> 

                     <!-- Bagian BB/TB -->
                        <div class="row mb-3">
                            <label for="bbtb" class="col-sm-2 col-form-label"><strong>BB/TB</strong></label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="bbtb"value="<?= val('bb_tb', $existing_data) ?>" <?= $ro ?>>
                                    <span class="input-group-text">kg/cm</span>
                

                         </div>
                        </div>

                    <!-- Bagian Tanda-tanda Vital -->

                    <div class="row mb-3">
                        <label class="col-sm-9 col-form-label">
                            <strong>Tanda Vital</strong>
                        </label>    
                    </div>

                    <!-- Tekanan Darah -->
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-2 col-form-label"><strong>Tekanan Darah</strong></label>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="text" class="form-control" name="tekanandarah" value="<?= val('tekanan_darah', $existing_data) ?>" <?= $ro ?>>
                                <span class="input-group-text">mmHg</span>
                        </div>    
                    </div>
                                
                    <!-- Nadi -->
                    <label class="col-sm-2 col-form-label"><strong>Nadi</strong></label>
                    <div class="col-sm-3">
                        <div class="input-group">
                                <input type="text" class="form-control" name="nadi" value="<?= val('nadi', $existing_data) ?>" <?= $ro ?>>
                                <span class="input-group-text">x/I</span>
                        </div> 
                    </div>
                </div>

                    <!-- Suhu -->
                        <div class="row mb-3 align-items-center">
                            <label class="col-sm-2 col-form-label"><strong>Suhu</strong></label>
                            <div class="col-sm-3">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="suhu" value="<?= val('suhu', $existing_data) ?>" <?= $ro ?>>
                                    <span class="input-group-text">°C</span>
                            </div>    
                        </div>

                    <!-- Pernapasan -->
                    <label class="col-sm-2 col-form-label"><strong>Pernapasan</strong></label>
                    <div class="col-sm-3">
                        <div class="input-group">
                                <input type="text" class="form-control" name="pernapasan" value="<?= val('pernapasan', $existing_data) ?>" <?= $ro ?>>
                                <span class="input-group-text">x/i</span>
                            </div>
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