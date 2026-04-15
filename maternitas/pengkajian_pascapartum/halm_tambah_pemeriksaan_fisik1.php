<?php
require_once "koneksi.php";
require_once "utils.php";
$form_id       = 3;
$level         = $_SESSION['level'];
$user_id       = $_SESSION['id_user'];
$section_name  = 'pemeriksaan_fisik';
$section_label = 'Pemeriksaan Fisik';

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
        'inspeksi_kepala'           => $_POST['inspeksikepala'] ?? '',
        'palpasi_kepala'            => $_POST['palpasikepala'] ?? '',
        'masalah_khusus_kepala'     => $_POST['masalahkhususkepala'] ?? '',
        'inspeksi_wajah'            => $_POST['inspeksiwajah'] ?? '',
        'palpasi_wajah'             => $_POST['palpasiwajah'] ?? '',
        'masalah_khusus_wajah'      => $_POST['masalahkhususwajah'] ?? '',
        'inspeksik_mata'            => $_POST['inspeksikmata'] ?? '',
        'palpasi_kelopak_mata'      => $_POST['palpasikelopakmata'] ?? '',
        'masalah_khusus_mata'       => $_POST['masalahkhususmata'] ?? '',
        'inspeksi_hidung'           => $_POST['inspeksihidung'] ?? '',
        'palpasi_hidung'            => $_POST['palpasihidung'] ?? '',
        'masalah_khusus_hidung'     => $_POST['masalahkhusushidung'] ?? '',
        'inspeksi_bibir'            => $_POST['inspeksibibir'] ?? '',
        'inspeksi_gigi'             => $_POST['inspeksigigi'] ?? '',
        'inspeksi_gusi'             => $_POST['inspeksigusi'] ?? '',
        'inspeksi_lidah'            => $_POST['inspeksilidah'] ?? '',
        'inspeksi_bau_mulut'        => $_POST['inspeksibaumulut'] ?? '',
        'palpasi_mulut'             => $_POST['palpasimulut'] ?? '',
        'masalah_khusus_mulut'      => $_POST['masalahkhususmulut'] ?? '',
        'inspeksi_telinga'          => $_POST['inspeksitelinga'] ?? '',
        'palpasi_nyeri_tekan'       => $_POST['palpasinyeritekan'] ?? '',
        'palpasi_gangguan'          => $_POST['palpasigangguan'] ?? '',
        'masalah_khusus_telinga'    => $_POST['masalahkhusustelinga'] ?? '',
        'inspeksi_leher'            => $_POST['inspeksileher'] ?? '',
        'palpasi_kelenjar'          => $_POST['palpasikelenjar'] ?? '',
        'palpasi_trakea'            => $_POST['palpasitrakea'] ?? '',
        'palpasi_nyeri_menelan'     => $_POST['palpasinyerimenelan'] ?? '',
        'masalah_khusus_leher'      => $_POST['masalahkhususleher'] ?? '',
        'inspeksi_axilia'           => $_POST['inspeksiaxilia'] ?? '',
        'palpasi_axilia'            => $_POST['palpasiaxilia'] ?? '',
        'masalah_khusus_axilia'     => $_POST['masalahkhususaxilia'] ?? '',
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

              <h5 class="card-title mb-1"><strong>Pengkajian</strong></h5>
               <form class="needs-validation" novalidate action="" method="POST">

                
                <!-- Bagian Kepala dan Rambut -->

                    <div class="row mb-2">
                        <label class="col-sm-2 col-form-label text-primary">
                            <strong>Kepala dan Rambut</strong>
                    </div>
                    
                    <!-- Inspeksi -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Inspeksi</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Bentuk kepala, Penyebaran, Kebersihan, Warna Rambut. Hasil:</small>
                            <textarea name="inspeksikepala" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('inspeksi_kepala', $existing_data) ?></textarea>

                         </div>
                    </div>        

                    <!-- Palpasi -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Palpasi</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Apakah terdapat benjolan, pembengkakan, nyeri tekan. Hasil:</small>
                            <textarea name="palpasikepala" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('palpasi_kepala', $existing_data) ?></textarea>

                 
                         </div>
                    </div> 

                     <!-- Masalah Khusus -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Masalah Khusus</strong></label>

                            <div class="col-sm-9">
                                <textarea name="masalahkhususkepala" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('masalah_khusus_kepala', $existing_data) ?></textarea>

                  
                         </div>
                    </div> 
                            
                    <!-- Bagian Wajah -->

                    <div class="row mb-2">
                        <label class="col-sm-2 col-form-label text-primary">
                            <strong>Wajah</strong>
                    </div>
                    
                    <!-- Inspeksi -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Inspeksi</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Bentuk, adakah hiperpigmentasi/cloasma gravidarum, area jika ada cloasma. Hasil:</small>
                            <textarea name="inspeksiwajah" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('inspeksi_wajah', $existing_data) ?></textarea>

                    
                         </div>
                    </div> 

                    <!-- Palpasi -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Palpasi</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Adakah nyeri tekan/tidak ada. Hasil:</small>
                            <textarea name="palpasiwajah" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('palpasi_wajah', $existing_data) ?></textarea>

                     
                         </div>
                    </div> 

                     <!-- Masalah Khusus -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Masalah Khusus</strong></label>

                            <div class="col-sm-9">
                                <textarea name="masalahkhususwajah" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('masalah_khusus_wajah', $existing_data) ?></textarea>

                    
                         </div>
                    </div> 
                    
                    <!-- Bagian Mata -->

                    <div class="row mb-2">
                        <label class="col-sm-2 col-form-label text-primary">
                            <strong>Mata</strong>
                    </div>
                    
                    <!-- Inspeksi  -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Inspeksi</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Konjungtiva: Apakah anemis/an-anemis. Hasil:</small>
                           <textarea name="inspeksikmata" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                           <?= $ro ?>><?= val('inspeksik_mata', $existing_data) ?></textarea>

                  
                         </div>
                    </div> 

                    <!-- Palpasi -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Palpasi</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Kelopak mata: Nyeri tekan/tidak. Hasil:</small>
                            <textarea name="palpasikelopakmata" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('palpasi_kelopak_mata', $existing_data) ?></textarea>

                   
                         </div>
                    </div> 

                     <!-- Masalah Khusus -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Masalah Khusus</strong></label>

                            <div class="col-sm-9">
                                <textarea name="masalahkhususmata" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('masalah_khusus_mata', $existing_data) ?></textarea>

                         </div>
                    </div>   

                    <!-- Bagian Hidung -->

                    <div class="row mb-2">
                        <label class="col-sm-2 col-form-label text-primary">
                            <strong>Hidung</strong>
                    </div>
                    
                    <!-- Inspeksi -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Inspeksi</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Apakah ada pembengkakan/tidak ada pembengkakan, kesimetrisan lubang hidung, kebersihan, septum utuh/tidak utuh. Hasil:</small>
                            <textarea name="inspeksihidung" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('inspeksi_hidung', $existing_data) ?></textarea>

                   
                         </div>
                    </div> 

                    <!-- Palpasi -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Palpasi</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Nyeri tekan/tidak ada. Hasil:</small>
                           <textarea name="palpasihidung" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                           <?= $ro ?>><?= val('palpasi_hidung', $existing_data) ?></textarea>

                    
                         </div>
                    </div> 

                     <!-- Riwayat Masalah Khusus -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Masalah Khusus</strong></label>

                            <div class="col-sm-9">
                                <textarea name="masalahkhusushidung" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('masalah_khusus_hidung', $existing_data) ?></textarea>

                    
                         </div>
                    </div> 

                    <!-- Bagian Mulut -->

                    <div class="row mb-2">
                        <label class="col-sm-2 col-form-label text-primary">
                            <strong>Mulut</strong>
                    </div>
                    
                    <!-- Inspeksi Bibir -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Inspeksi Bibir</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Warna, kesimertrisan, kelembapan, bibir sumbing, ulkus. Hasil:</small>
                            <textarea name="inspeksibibir" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('inspeksi_bibir', $existing_data) ?></textarea>

                     
                         </div>
                    </div> 

                    <!-- Inspeksi Gigi -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Inspeksi Gigi</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Amati jumlah, warna, kebersihan, karies. Hasil:</small>
                            <textarea name="inspeksigigi" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('inspeksi_gigi', $existing_data) ?></textarea>

                   
                         </div>
                    </div> 

                    <!-- Inspeksi Gusi -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Inspeksi Gusi</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Adakah atau tidak lesi/pembengkakan? Hasil:</small>
                            <textarea name="inspeksigusi" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('inspeksi_gusi', $existing_data) ?></textarea>

                   
                         </div>
                    </div> 

                    <!-- Inspeksi Lidah -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Inspeksi Lidah</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Amati warna dan kebersihan. Hasil:</small>
                            <textarea name="inspeksilidah" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('inspeksi_lidah', $existing_data) ?></textarea>

                   
                         </div>
                    </div> 

                    <!-- Inspeksi Bau Mulut -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Inspeksi Bau Mulut</strong></label>

                        <div class="col-sm-9">
                            <textarea name="inspeksibaumulut" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('inspeksi_bau_mulut', $existing_data) ?></textarea>

                 
                         </div>
                    </div> 

                    <!-- Palpasi -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Palpasi</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Apakah ada nyeri tekan atau tidak ada? Hasil:</small>
                            <textarea name="palpasimulut" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('palpasi_mulut', $existing_data) ?></textarea>

                    
                         </div>
                    </div> 

                     <!-- Masalah Khusus -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Masalah Khusus</strong></label>

                            <div class="col-sm-9">
                                <textarea name="masalahkhususmulut" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('masalah_khusus_mulut', $existing_data) ?></textarea>

                   
                         </div>
                    </div>  
                            
                     <!-- Bagian Telinga -->

                    <div class="row mb-2">
                        <label class="col-sm-2 col-form-label text-primary">
                            <strong>Telinga</strong>
                    </div>
                    
                    <!-- Inspeksi -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Inspeksi</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Bentuk: simetris/tidak. <br> Kebersihan: apakah ada perdarahan, peradangan, kotoran/serumen atau tidak ada? Hasil:</small>
                            <textarea name="inspeksitelinga" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('inspeksi_telinga', $existing_data) ?></textarea>

                         </div>
                    </div> 

                    <!-- Palpasi -->
                    <!-- Palpasi Nyeri Tekan -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Palpasi</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Nyeri Tekanan: Apakah ada pembengkakan, nyeri tekan atau tidak ada? Hasil:</small>
                            <textarea name="palpasinyeritekan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('palpasi_nyeri_tekan', $existing_data) ?></textarea>

                    
                         </div>
                    </div> 
                    
                    <!-- Palpasi Gangguan -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Gangguan pendengaran: apakah ada ganguan atau tidak? Hasil:</small>
                            <textarea name="palpasigangguan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('palpasi_gangguan', $existing_data) ?></textarea>

                    
                         </div>
                    </div>

                     <!-- Riwayat Masalah Khusus -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Masalah Khusus</strong></label>

                            <div class="col-sm-9">
                                <textarea name="masalahkhusustelinga" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('masalah_khusus_telinga', $existing_data) ?></textarea>

                    
                         </div>
                    </div> 
                            
                     <!-- Bagian Leher -->

                    <div class="row mb-2">
                        <label class="col-sm-2 col-form-label text-primary">
                            <strong>Leher</strong>
                    </div>
                    
                    <!-- Inspeksi -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Inspeksi</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Bentuk leher, ada massa dan benjolan atau tidak. Adakah Distensi vena jugularis/tidak ada. Hasil:</small>
                            <textarea name="inspeksileher" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('inspeksi_leher', $existing_data) ?></textarea>

                    
                         </div>
                    </div> 

                    <!-- Palpasi -->
                    <!-- Palpasi Kelenjar Tiroid -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Palpasi</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Kelenjar Tiroid: Apakah ada pembesaran kelenjar tiroid atau tidak. Hasil:</small>
                            <textarea name="palpasikelenjar" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('palpasi_kelenjar', $existing_data) ?></textarea>

                     
                         </div>
                    </div> 
                    
                    <!-- Palpasi Trakea -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Trakea: Apakah ada pergeseran/tidak. Hasil:</small>
                            <textarea name="palpasitrakea" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('palpasi_trakea', $existing_data) ?></textarea>

                    
                         </div>
                    </div> 

                     <!-- Palpasi Nyeri Menelan -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Nyeri menelan: ya/tidak. Hasil:</small>
                            <textarea name="palpasinyerimenelan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('palpasi_nyeri_menelan', $existing_data) ?></textarea>

                    
                         </div>
                    </div> 

                     <!-- Riwayat Masalah Khusus -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Masalah Khusus</strong></label>

                            <div class="col-sm-9">
                                <textarea name="masalahkhususleher" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('masalah_khusus_leher', $existing_data) ?></textarea>

                     
                         </div>
                    </div> 
                            
                     <!-- Bagian Axila -->

                    <div class="row mb-2">
                        <label class="col-sm-2 col-form-label text-primary">
                            <strong>Axila</strong>
                    </div>
                    
                    <!-- Inspeksi -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Inspeksi</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Warna, Pembengkakan. Hasil:</small>
                            <textarea name="inspeksiaxilia" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('inspeksi_axilia', $existing_data) ?></textarea>

                   
                         </div>
                    </div> 

                    <!-- Palpasi -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Palpasi</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Pembesaran kelenjar limfe: Ya/Tidak? Hasil:</small>
                            <textarea name="palpasiaxilia" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('palpasi_axilia', $existing_data) ?></textarea>

                     
                         </div>
                    </div> 

                     <!-- Masalah Khusus -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Masalah Khusus</strong></label>

                            <div class="col-sm-9">
                                <textarea name="masalahkhususaxilia" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('masalah_khusus_axilia', $existing_data) ?></textarea>

                    
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
            