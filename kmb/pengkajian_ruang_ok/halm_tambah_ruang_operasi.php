<?php
require_once "koneksi.php";
require_once "utils.php";

$form_id       = 19;
$level         = $_SESSION['level'];
$user_id       = $_SESSION['id_user'];
$section_name  = 'ruang_operasi';
$section_label = 'Ruang Operasi';

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

// =============================================
// HANDLE POST - MAHASISWA SIMPAN DATA
// =============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $level === 'Mahasiswa') {
    if (isLocked($submission)) {
        redirectWithMessage($_SERVER['REQUEST_URI'], 'error', 'Data tidak dapat diubah karena sedang dalam proses review.');
    }
    $data = [
        'nama_mahasiswa'       => $_POST['nama_mahasiswa'] ?? '',
        'nim'                  => $_POST['nim'] ?? '',
        'kelompok'             => $_POST['kelompok'] ?? '',
        'tempat_dinas'         => $_POST['tempat_dinas'] ?? '',
        'nama_klien'           => $_POST['nama_klien'] ?? '',
        'umur'                 => $_POST['umur'] ?? '',
        'pekerjaan'            => $_POST['pekerjaan'] ?? '',
        'agama'                => $_POST['agama'] ?? '',
        'tgl_masuk_rs'         => $_POST['tgl_masuk_rs'] ?? '',
        'diagnosa_medis'       => $_POST['diagnosa_medis'] ?? '',
        'jenis_operasi'        => $_POST['jenis_operasi'] ?? '',
        'tgl_operasi'          => $_POST['tgl_operasi'] ?? '',
        'pukul_mulai'          => $_POST['pukul_mulai'] ?? '',
        'pukul_selesai'        => $_POST['pukul_selesai'] ?? '',
        'steril'               => $_POST['steril'] ?? '',
        'non_steril'           => $_POST['non_steril'] ?? '',
        'anestesi'             => $_POST['anestesi'] ?? '',
        'jenis_anestesi'       => $_POST['jenis_anestesi'] ?? '',
        'lingkungan'           => $_POST['lingkungan'] ?? '',
        'pemeriksaan_lab'      => $_POST['pemeriksaan_lab'] ?? '',
        'tindakan'             => $_POST['tindakan'] ?? '',
        'kesimpulan'           => $_POST['kesimpulan'] ?? '',
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

    <?php include "kmb/pengkajian_ruang_ok/tab.php"; ?>

    <section class="section dashboard">

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

            <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">
                

              <h5 class="card-title mb-1"><strong>LAPORAN RUANG OPERASI</strong></h5>

           <div class="row mb-3">
                <label class="col-sm-2 col-form-label"><strong>Nama Mahasiswa </strong></label>

                <div class="col-sm-10">
                     <input type="text" class="form-control" name="nama_mahasiswa"
                                value="<?= val('nama_mahasiswa', $existing_data) ?>" <?= $ro ?>>

                    
                </div>
            </div>
               <div class="row mb-3">
                <label class="col-sm-2 col-form-label"><strong>NIM</strong></label>

                <div class="col-sm-10">
                   <input type="text" class="form-control" name="nim"
                                value="<?= val('nim', $existing_data) ?>" <?= $ro ?>>

                    
                </div>
            </div>
               <div class="row mb-3">
                <label class="col-sm-2 col-form-label"><strong>Kelompok 	</strong></label>

                <div class="col-sm-10">
                    <textarea name="kelompok" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('kelompok',$existing_data) ?></textarea>
                    
                </div>
            </div>
               <div class="row mb-3">
                <label class="col-sm-2 col-form-label"><strong>Tempat Dinas </strong></label>

                <div class="col-sm-10">
                    <input type="text" class="form-control" name="tempat_dinas"
                                value="<?= val('tempat_dinas', $existing_data) ?>" <?= $ro ?>>

                   
                </div>
            </div>

            <!-- A IDENTITAS KLIEN -->
             <h5 class="card-title"><strong>A.	IDENTITAS KLIEN</strong></h5>
           

            <!-- NAMA -->
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label"><strong>Nama Klien</strong></label>

                <div class="col-sm-10">
                    <input type="text" class="form-control" name="nama_klien"
                                value="<?= val('nama_klien', $existing_data) ?>" <?= $ro ?>>
                </div>
            </div>

            <!-- UMUR -->
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label"><strong>Umur</strong></label>

                <div class="col-sm-10">
                    <input type="text" class="form-control" name="umur"
                                value="<?= val('umur', $existing_data) ?>" <?= $ro ?>>
                </div>
            </div>

            <!-- PEKERJAAN -->
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label"><strong>Pekerjaan</strong></label>

                <div class="col-sm-10">
                    <input type="text" class="form-control" name="pekerjaan"
                                value="<?= val('pekerjaan', $existing_data) ?>" <?= $ro ?>>

                    
                </div>
            </div>

            <!-- AGAMA -->
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label"><strong>Agama</strong></label>

                <div class="col-sm-10">
                    <input type="text" class="form-control" name="agama"
                                value="<?= val('agama', $existing_data) ?>" <?= $ro ?>>

                    
                </div>
            </div>

            <!-- TGL MASUK RS -->
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label"><strong>Tgl Masuk RS</strong></label>

                <div class="col-sm-10">
                   <input type="date" class="form-control" name="tgl_masuk_rs"
                                value="<?= val('tgl_masuk_rs', $existing_data) ?>" <?= $ro ?>>

                    
                         </div>
                    </div>

            <!-- DIAGNOSA MEDIS -->
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label"><strong>Diagnosa Medis</strong></label>

                <div class="col-sm-10">
                    <textarea name="diagnosa_medis" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('diagnosa_medis',$existing_data) ?></textarea>

                    
                         </div>
                    </div>

            <!-- JENIS OPERASI -->
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label"><strong>Jenis Operasi</strong></label>

                <div class="col-sm-10">
                    <textarea name="jenis_operasi" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('jenis_operasi',$existing_data) ?></textarea>
                         </div>
                    </div>
            <!-- WAKTU OPERASI -->
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label"><strong>Waktu Operasi</strong></label>

                <div class="col-sm-10">
                    <div class="row">
                        <div class="col-md-4">
                            <small>Tgl</small>
                            <input type="date" class="form-control" name="tgl_operasi"
                                value="<?= val('tgl_operasi', $existing_data) ?>" <?= $ro ?>>
                        </div>
                        <div class="col-md-4">
                            <small>Pukul</small>
                            <input type="time" class="form-control" name="pukul_mulai"
                                value="<?= val('pukul_mulai', $existing_data) ?>" <?= $ro ?>>
                        </div>
                        <div class="col-md-4">
                            <small>s/d Pukul</small>
                            <input type="time" class="form-control" name="pukul_selesai"
                                value="<?= val('pukul_selesai', $existing_data) ?>" <?= $ro ?>>
                        </div>
                    </div>
                </div>
            </div>

            <!-- B PERSIAPAN -->
        
            <h5 class="card-title"><strong>B.	PERSIAPAN</strong></h5>

            <!-- ALAT -->
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label"><strong>1. Alat</strong></label>
            </div>

            <!-- STERIL -->
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label"><strong>a. Steril</strong></label>

                <div class="col-sm-10">
                    <textarea name="steril" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('steril',$existing_data) ?></textarea>
                  
                         </div>
                    </div> 
            <!-- NON STERIL -->
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label"><strong>b. Non Steril</strong></label>

                <div class="col-sm-10">
                    <textarea name="non_steril" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('non_steril',$existing_data) ?></textarea>
                  
                         </div>
                    </div> 
               
            <!-- ANESTESI -->
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label"><strong>c. Anestesi</strong></label>

                <div class="col-sm-10">
                   <textarea name="anestesi" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('anestesi',$existing_data) ?></textarea>
        </div>

                </div>

            <!-- JENIS ANESTESI -->
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label"><strong>2. Jenis Anestesi</strong></label>

                <div class="col-sm-10">
                    <textarea name="jenis_anestesi" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('jenis_anestesi',$existing_data) ?></textarea>
                         </div>
                    </div> 

            <!-- LINGKUNGAN -->
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label"><strong>3. Lingkungan</strong></label>

                <div class="col-sm-10">
                   <textarea name="lingkungan" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('lingkungan',$existing_data) ?></textarea>
                  
                         </div>
            </div>

            <!-- HASIL LAB -->
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">
                    <strong>4. Hasil Pemeriksaan Laboratorium</strong>
                </label>

                <div class="col-sm-10">
                   <textarea name="pemeriksaan_lab" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('pemeriksaan_lab',$existing_data) ?></textarea>
                         </div>
            </div>

            <!-- C TINDAKAN -->
         
            <h5 class="card-title"><strong>C.	TINDAKAN</strong></h5>

            <div class="row mb-3">
                <label class="col-sm-2 col-form-label"><strong>Tindakan</strong></label>

                <div class="col-sm-10">
                   <textarea name="tindakan" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('tindakan',$existing_data) ?></textarea>
                         </div>
                    </div> 

            <!-- D KESIMPULAN -->

            <h5 class="card-title"><strong>D.	KESIMPULAN</strong></h5>

            <div class="row mb-3">
                <label class="col-sm-2 col-form-label"><strong>Kesimpulan</strong></label>

                <div class="col-sm-10">
                    <textarea name="kesimpulan" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('kesimpulan',$existing_data) ?></textarea>                         </div>
                    </div> 
            

            
               <!-- TOMBOL MAHASISWA -->
                <?php if (!$is_dosen): ?>
                    <div class="row mb-3">
                        <div class="col-sm-12 d-flex justify-content-end">
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
                            <div class="col-sm-10">
                                <textarea name="comment" class="form-control" rows="3"
                                    placeholder="Tulis komentar (wajib jika meminta revisi)..."></textarea>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-12 d-flex justify-content-end gap-2">
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

                 <?php include "tab_navigasi.php"; ?>

            </div>
        </div>

       

    </section>
</main>

   

