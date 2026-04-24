<?php
require_once "koneksi.php";
require_once "utils.php";

$form_id       = 17;
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
            'nama_klien'                            => $_POST['nama_klien'] ?? '',
            'ttl_umur'                              => $_POST['ttl_umur'] ?? '',
            'jenis_kelamin'                         => $_POST['jenis_kelamin'] ?? '',
            'status_perkawinan'                     => $_POST['status_perkawinan'] ?? '',
            'agama'                                 => $_POST['agama'] ?? '',
            'pendidikan'                            => $_POST['pendidikan'] ?? '',
            'pekerjaan'                             => $_POST['pekerjaan'] ?? '',
            'alamat'                                => $_POST['alamat'] ?? '',
            'tgl_pengkajian'                        => $_POST['tgl_masuk_rs'] ?? '',

            'diagnosa_medik'                        => $_POST['diagnosa_medik'] ?? '',
            'golongan_darah'                        => $_POST['golongan_darah'] ?? '',
            'no_registrasi'                         => $_POST['no_registrasi'] ?? '',
            'ruangan'                               => $_POST['ruangan'] ?? '',
            'nama_klienpj'                          => $_POST['nama_klienpj'] ?? '',
            'ttl_umurpj'                            => $_POST['ttl_umurpj'] ?? '',
            'jenis_kelaminpj'                       => $_POST['jenis_kelaminpj'] ?? '',
            'hubungan_klien'                        => $_POST['hubungan_klien'] ?? '',
            'agamapj'                               => $_POST['agamapj'] ?? '',
            'pendidikanpj'                          => $_POST['pendidikanpj'] ?? '',
            'pekerjaanpj'                           => $_POST['pekerjaanpj'] ?? '',
            'alamatpj'                              => $_POST['alamatpj'] ?? '',
            'nadi_prehd'                            => $_POST['nadi_prehd'] ?? '',
            'pernafasan_prehd'                      => $_POST['pernafasan_prehd'] ?? '',
            'td_prehd'                              => $_POST['td_prehd'] ?? '',
            'suhu_prehd'                             => $_POST['suhu_prehd'] ?? '',
            'nadi'                                  => $_POST['nadi'] ?? '',
            'pernafasan'                            => $_POST['pernafasan'] ?? '',
            'td'                                    => $_POST['td'] ?? '',
            'suhu'                                  => $_POST['suhu'] ?? '',
            'm'                                     => $_POST['m'] ?? '',
            'v'                                     => $_POST['v'] ?? '',
            'e'                                     => $_POST['e'] ?? '',
            'bb_prehd'                              => $_POST['bb_prehd'] ?? '',
             'bb_posthd'                            => $_POST['bb_posthd'] ?? '',
            'kenaikanbb'                            => $_POST['kenaikanbb'] ?? '',
            'alasan_masuk_rs'                       => $_POST['alasan_masuk_rs'] ?? '',
            'keluhan_utama'                         => $_POST['keluhan_utama'] ?? '',
            'riwayat_keluhan_utama'                 => $_POST['riwayat_keluhan_utama'] ?? '',
            'riwayat_kesehatan_lalu'                => $_POST['riwayat_kesehatan_lalu'] ?? '',
            'riwayat_kesehatan_keluarga'            => $_POST['riwayat_kesehatan_keluarga'] ?? '',
            'genogram'                              => $_POST['genogram'] ?? '',
            'kesadaran'                             => $_POST['kesadaran'] ?? '',
            'bbkering'                              => $_POST['bbkering'] ?? '',
            'bb_hd'                                  => $_POST['bb_hd'] ?? '',
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
        <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

            <h5 class="card-title"><strong>4. Pemeriksaan fisik</strong></h5>
<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>a. Kepala</strong></label>
</div>

<div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>Bentuk Kepala</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="bentuk_kepala" value="<?= val('bentuk_kepala', $existing_data) ?>" <?= $ro ?>>
    
    </div>
</div>

                    <!-- Nyeri Dada -->
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Apa ada nyeri tekan	:</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="nyeridada" value="ya" >
                            <label class="form-check-label">Ya</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="nyeridada" value="tidak">
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>
                
                    <!-- Nyeri Dada -->
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Apa ada benjolan:</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="nyeridada" value="ya">
                            <label class="form-check-label">Ya</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="nyeridada" value="tidak">
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>



                
            <div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>b. Rambut</strong></label>
</div>

                    <!-- Nyeri Dada -->
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Penyebaran Merata</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="nyeridada" value="ya">
                            <label class="form-check-label">Ya</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="nyeridada" value="tidak">
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>


        <div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>Warna</strong>
    </div>
    <div class="col-sm-9">
      
    </div>
</div>
   

                    <!-- Nyeri Dada -->
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Mudah Dicabut</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="rambut_dicabut" value="ya">
                            <label class="form-check-label">Ya</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="rambut_dicabut" value="tidak">
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>
            <div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>Kelainan</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="kelainan_rambut">
        
</div>
</div>
        

        
            <div class="row mb-2">
                <label class="col-sm-12 text-primary"><strong>c. Wajah</strong></label>
            </div>
            <div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>Ekspresi Wajah</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="ekspresi_wajah">
       
</div>
</div>
                        <!-- Nyeri Dada -->
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Kesimetrisan Wajah</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="simetris_wajah" value="ya">
                            <label class="form-check-label">Ya</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="simetris_wajah" value="tidak">
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>
                      <div class="row mb-2">
                        <div class="col-sm-2"><strong>Terdapat Udema</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="udema_wajah" value="ya">
                            <label class="form-check-label">Ya</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="udema_wajah" value="tidak">
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>
                
               

                     <div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>Kelainan</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="kelainan_wajah">
        
</div>
</div>
           
                
<div class="row mb-2">
                <label class="col-sm-12 text-primary"><strong>d. Mata</strong></label>
            </div>
           
              
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Penglihatan</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="penglihatan" value="jelas">
                            <label class="form-check-label">Jelas</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="penglihatan" value="kabur">
                            <label class="form-check-label">Kabur</label>
                        </div>
                    </div>
           
                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="penglihatan" value="rabun">
                            <label class="form-check-label">Rabun</label>
                        </div>
                    </div>
               
                
                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="penglihatan" value="berkunang">
                            <label class="form-check-label">Berkunang</label>
                        </div>
                        </div>
                    </div>
                   
                    
                        <div class="row mb-3">

                        <label class="col-sm-2 col-form-label"><strong>Visus</strong></label>
                            <div class="col-sm-9">
                                <div class="row">

                        <!-- E -->
                        <div class="col-md-4 d-flex align-items-center">
                            <label class="me-2"><strong>Kanan</strong></label>
                            <input type="text" class="form-control" name="kanan">
                        </div>

                        <!-- M -->
                        <div class="col-md-4 d-flex align-items-center">
                            <label class="me-2"><strong>Kiri</strong></label>
                            <input type="text" class="form-control" name="kiri">
                        </div>

                      
                        
                    </div>

                        
                         </div>

                    </div>
                    
             
       
<div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>Lapang Pandang</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="lapang_pandang">
       
        </div>
</div>
<div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>Keadaan Mata</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="Keadaan Mata">
    
        </div>
</div>
                <div class="row mb-2">
                        <div class="col-sm-2"><strong>Konjungtiva</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="konjungtiva" value="anemis">
                            <label class="form-check-label">Anemis</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="konjungtiva" value="ananenmis">
                            <label class="form-check-label">Ananenmis</label>
                        </div>
                    </div>
                </div>
                <div class="row mb-2">
                        <div class="col-sm-2"><strong>Lesi</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="lesi_mata" value="ada">
                            <label class="form-check-label">Ada</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="lesi_mata" value="tidak">
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>
                    <div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>Sclera</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="sclera">
        
        </div>
</div>
                  
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Reaksi Pupil</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="pupil" value="isokor">
                            <label class="form-check-label">Isokor</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="pupil" value="anisokor">
                            <label class="form-check-label">Anisokor</label>
                        </div>
                    </div>
                </div>
                    
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Bola Mata</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="bola_mata" value="simetris">
                            <label class="form-check-label">Simetris</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="bola_mata" value="tidak">
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>
                   
                     <div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>Kelainan</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="kelainan_mata">
      
</div>
</div>

            <div class="row mb-2">
                <label class="col-sm-12 text-primary"><strong>e. Telinga</strong></label>
            </div>
            <div class="row mb-2">
                        <div class="col-sm-2"><strong>Pendengaran Kiri</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="pendengaran_kiri" value="jelas">
                            <label class="form-check-label">Jelas</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="pendengaran_kiri" value="berkurang">
                            <label class="form-check-label">Berkurang</label>
                        </div>
                    </div>
                </div>
                        <div class="row mb-2">
                        <div class="col-sm-2"><strong>Pendengaran Kanan</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="pendengaran_kanan" value="jelas">
                            <label class="form-check-label">Jelas</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="pendengaran_kanan" value="berkurang">
                            <label class="form-check-label">Berkurang</label>
                        </div>
                    </div>
                </div>
                    
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Nyeri Kiri</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="nyeri_Kiri" value="ada">
                            <label class="form-check-label">Ada</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="nyeri_Kiri" value="tidak">
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>
                    
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Nyeri Kanan</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="nyeri_kanan" value="ada">
                            <label class="form-check-label">Ada</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="nyeri_kanan" value="tidak">
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>
                    
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Serumen</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="serumen" value="ada">
                            <label class="form-check-label">Ada</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="serumen" value="tidak">
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>
                                        <div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>Kelainan</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="kelainan_telinga">

</div>
</div>
                    
<div class="row mb-2">
                <label class="col-sm-12 text-primary"><strong>f. Hidung</strong></label>
            </div>
            <div class="row mb-2">
                        <div class="col-sm-2"><strong>Membedakan Bau</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="bau" value="dapat">
                            <label class="form-check-label">Dapat</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="bau" value="tidak">
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>
         <!-- Pupil -->
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-2 col-form-label"><strong>Sekresi</strong></label>
                        <div class="col-sm-3">
                                <input type="text" class="form-control" name="sekresi">
                        </div>    
                                
                    <!-- Ukuran -->
                    <label class="col-sm-2 col-form-label"><strong>Warna</strong></label>
                    <div class="col-sm-3">
                        <input type="text" class="form-control" name="warna">
                      

                    
                    </div>   
                </div>

                
                    
              
                    <div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>Mukosa</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="mukosa_hidung">
   
        </div>
</div>

                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Pembengkakan</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="pembengkakan" value="ya">
                            <label class="form-check-label">Ya</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="pembengkakan" value="tidak">
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>
                    
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Pernafasan Cuping Hidung</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="cuping_hidung" value="ya">
                            <label class="form-check-label">Ya</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="cuping_hidung" value="tidak">
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>
               
                                        <div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>Kelainan</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="kelainan_hidung">
        
</div>
</div>
            

            <div class="row mb-3">
<label class="col-sm-12 text-primary"><strong>g. Mulut</strong></label>
</div>
 <!-- Frekuensi -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Bibir</strong></label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="bibir">
                                    <span class="input-group-text">Warna</span>
                                    </div>
                           
                                    </div>
                                </div>  

<div class="row mb-2">
                        <div class="col-sm-2"><strong>Simetris</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="simetris" value="ya">
                            <label class="form-check-label">Ya</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="simetris" value="tidak">
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>

<div class="row mb-2">
                        <div class="col-sm-2"><strong>Kelembaban</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="kelembaban" value="basah">
                            <label class="form-check-label">Basah</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="kelembaban" value="kering">
                            <label class="form-check-label">Kering</label>
                        </div>
                    </div>
                </div>

<!-- Suara Jantung -->
                        <div class="row mb-2">
                            <div class="col-sm-2">
                                <strong>Gigi</strong>
                       </div>
                        
                            
                        
                        <div class="col-sm-2">
                                <strong>Caries :</strong>
                            </div>

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="caries" value="ada">
                                        <label class="form-check-label">Ada</label>
                                    </div>
                                </div>

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="caries" value="tidak">
                                        <label class="form-check-label">Tidak</label>
                                    </div>
                                </div>
                             
                            <div class="col-sm-2">
                                <div class="form-check">
                                   
                                        <label class="form-check-label"></label>
                                    </div>
                                </div>  
                                
                            <div class="col-sm-2">
                                <div class="form-check">
                                  
                                        <label class="form-check-label"></label>
                                        </div>
                                     
                        </div> 
                        <div class="col-sm-2"></div>
                         <!-- Lainnya -->
                            <div class="col-sm-9">
                                <label><strong>Jumlah</strong></label>
                                <input type="text" class="form-control" name="jumlah_gigi">

                               
                                    </div>
                                </div> 
                                                    <div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>Warna</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="warna">
  
</div>
</div>


<!-- Pupil -->
                    <div class="row mb-3 align-items-center">
    <label class="col-sm-2 col-form-label"><strong>Gigi Palsu</strong></label>
    <div class="col-sm-4">
        <div class="input-group">
            <input type="text" class="form-control" name="frekuensi">
            <span class="input-group-text">buah</span> 
        </div>
    </div>

    <!-- Letak Gigi Palsu -->
    <label class="col-sm-2 col-form-label"><strong>Letak</strong></label>
    <div class="col-sm-2">
        <input type="text" class="form-control" name="letak">
    </div>
</div>



   <!-- Frekuensi -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Lidah</strong></label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="lidah">
                                    <span class="input-group-text">Warna</span>
                            </div>

                              
                                    </div>
                                </div>  
                                

<div class="row mb-2">
                        <div class="col-sm-2"><strong>Lesi</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="lesi_lidah" value="ada">
                            <label class="form-check-label">Ada</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="lesi_lidah" value="tidak">
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>
                <!-- Perabaan -->

                        <div class="row mb-2">
                            <div class="col-sm-2"><strong>Sensasi Rasa</strong>
                        </div>
                        
                        <!-- Panas -->

                        <div class="col-sm-2">
                                <strong>Panas/Dingin</strong>
                            </div>

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="panas/dingin" value="ada">
                                        <label class="form-check-label">Ada</label>
                                    </div>
                                </div>

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="panas/dingin" value="tidak">
                                        <label class="form-check-label">tidak</label>
                                    </div>
                                </div>  
                        </div>
                        
                        <!-- Dingin -->
                         
                        <div class="row mb-2">
                            <div class="col-sm-2">
                        </div>

                        <div class="col-sm-2">
                                <strong>Asam / Pahit </strong>
                            </div>

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="asampahit" value="ada">
                                        <label class="form-check-label">Ada</label>
                                    </div>
                                </div>

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="asampahit" value="tidak">
                                        <label class="form-check-label">Tidak</label>
                                    </div>
                                </div>
                        </div>

                        <!-- Tekan -->
                         
                        <div class="row mb-2">
                            <div class="col-sm-2">
                        </div>

                        <div class="col-sm-2">
                                <strong>Manis </strong>
                            </div>

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="manis " value="ada">
                                        <label class="form-check-label">Ada</label>
                                    </div>
                                </div>

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="manis" value="tidak">
                                        <label class="form-check-label">Tidak</label>
                                    </div>
                                </div>
                        </div>
                        <div class="row mb-2">
                        <div class="col-sm-2"><strong>Refleks Mengunyah</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="refleks" value="dapat">
                            <label class="form-check-label">Dapat</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="refleks" value="tidak">
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>
<div class="row mb-2">
                        <div class="col-sm-2"><strong>Pembesaran Tonsil</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="tonsil" value="ya">
                            <label class="form-check-label">Ya</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="tonsil" value="tidak">
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>
<div class="row mb-2">
                        <div class="col-sm-2"><strong>Bau Mulut</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="bau_mulut" value="uranium">
                            <label class="form-check-label">Uranium + / -</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="bau_mulut" value="amoniak">
                            <label class="form-check-label">Amoniak + / - </label>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="bau_mulut" value="aceton">
                            <label class="form-check-label">Aceton + / -</label>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="bau_mulut" value="busuk">
                            <label class="form-check-label">Busuk + / - </label>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="bau_mulut" value="alkohol">
                            <label class="form-check-label">Alkohol + / -</label>
                        </div>
                    </div>
                </div>

 <div class="row mb-2">
                            <div class="col-sm-2">
                                <strong>Sekret</strong>
                       </div>
                        
                            
                        
                       

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="caries" value="ada">
                                        <label class="form-check-label">Ada</label>
                                    </div>
                                </div>

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="caries" value="tidak">
                                        <label class="form-check-label">Tidak</label>
                                    </div>
                                </div>
                              <div class="col-sm-2">
                                <strong></strong>
</div>
                            <div class="col-sm-2">
                                <div class="form-check">
                                   
                                        <label class="form-check-label"></label>
                                    </div>
                                </div>  
                                
                            <div class="col-sm-2">
                                <div class="form-check">
                                  
                                        <label class="form-check-label"></label>
                                        </div>
                                     
                        </div> 
                        <div class="col-sm-2"></div>
                         <!-- Lainnya -->
                            <div class="col-sm-9">
                                <label><strong>Warna</strong></label>
                                <input type="text" class="form-control" name="warna">

                                    </div>
                                </div> 



<div>
<label class="col-sm-12 text-primary"><strong>h. Leher</strong></label>
            </div>
            <div class="row mb-2">
                        <div class="col-sm-2"><strong>Bentuk Simetris</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="leher_simetris" value="ya">
                            <label class="form-check-label">Ya</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="leher_simetris" value="tidak">
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>
             <div class="row mb-2">
                        <div class="col-sm-2"><strong>Pembesaran Kelenjar</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="kelenjar" value="ada">
                            <label class="form-check-label">Ada</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="kelenjar" value="tidak">
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>
                <div class="row mb-2">
                        <div class="col-sm-2"><strong>Peninggian JVP</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="jvp" value="ada">
                            <label class="form-check-label">Ada</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="jvp" value="tidak">
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>
                <div class="row mb-2">
                        <div class="col-sm-2"><strong>Refleks Menelan</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="refleks_menelan" value="dapat">
                            <label class="form-check-label">Dapat</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="refleks_menelan" value="tidak">
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>
                  
                  
                                        <div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>Kelainan</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="kelainan_leher">
        
</div>
</div>
                    

            <div class="row mb-2">
                <label class="col-sm-12 text-primary"><strong>i. Dada</strong></label>
            </div>
            <div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>Bentuk Dada</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="bentuk_dada">
       
</div>
</div>
                    <div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>Pengembangan Dada</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="pengembangan_dada">
       
</div>
</div>
<div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>Perbandingan ukuran anterior-posterior dengan transversal</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="perbandingan_dada">
      
</div>
</div>
<div class="row mb-2">
                        <div class="col-sm-2"><strong>Penggunaan Otot Pernafasan Tambahan</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="otot_pernafasan" value="ya">
                            <label class="form-check-label">Ya</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="otot_pernafasan" value="tidak">
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>
            
                    
                   
<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>j. Paru</strong></label>
</div>
 <div class="row mb-3 align-items-center">
    <label class="col-sm-2 col-form-label"><strong>Frekuensi Nafas</strong></label>
    <div class="col-sm-4">
        <div class="input-group">
            <input type="text" class="form-control" name="frekuensi">
            <span class="input-group-text">x/menit</span> 
        </div>
    </div>

    <!-- Letak Gigi Palsu -->
    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="teratur_nafas" value="teratur">
                            <label class="form-check-label">Teratur</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="teratur_nafas" value="tidak">
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
</div>

<div class="row mb-2">
                        <div class="col-sm-2"><strong>Irama Pernafasan</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="irama_nafas" value="dangkal">
                            <label class="form-check-label">Dangkal</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="irama_nafas" value="dalam">
                            <label class="form-check-label">Dalam</label>
                        </div>
                    </div>
                </div>
                <div class="row mb-2">
                        <div class="col-sm-2"><strong>Kesukaran Bernafas</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="sesak_nafas" value="ya">
                            <label class="form-check-label">Ya</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="sesak_nafas" value="tidak">
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>
 

    
                                <div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>Taktil Fremitus</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="taktil_fremitus">
    
</div>
</div>
<div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>Bunyi Perkusi Paru</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="perkusi_paru">
       
</div>
</div>
                       
<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>Suara Nafas</strong></label>
</div>

 <div class="row mb-3 align-items-center">
    <label class="col-sm-2 col-form-label"><strong>Frekuensi Nafas</strong></label>
     <!-- Letak Gigi Palsu -->
    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="teratur_nafas" value="teratur">
                            <label class="form-check-label">Normal</label>
                        </div>
                    </div>
    <div class="col-sm-7">
        <div class="input-group">
            <input type="text" class="form-control" name="frekuensi">
            <span class="input-group-text">uraikan</span> 
        </div>
    </div>

                            <div class="col-sm-2">
                                <strong>Bunyi Nafas Abnormal</strong>
                       </div>
                        
                            
                        
                       

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="bunyi_abnormal" value="wheezing">
                                        <label class="form-check-label">Wheezing</label>
                                    </div>
                                </div>

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="bunyi_abnormal" value="ronchi">
                                        <label class="form-check-label">Ronchi</label>
                                    </div>
                                </div>
                              <div class="col-sm-2">
                                <strong></strong>
</div>
                            <div class="col-sm-2">
                                <div class="form-check">
                                   
                                        <label class="form-check-label"></label>
                                    </div>
                                </div>  
                                
                            <div class="col-sm-2">
                                <div class="form-check">
                                  
                                        <label class="form-check-label"></label>
                                        </div>
                                     
                        </div> 
                        <div class="col-sm-2"></div>
                         <!-- Lainnya -->
                            <div class="col-sm-9">
                                <label><strong>Lainnya</strong></label>
                                <input type="text" class="form-control" name="abnormal">

                                
                                </div> 
<div class="row mb-2">
                <label class="col-sm-12 text-primary"><strong>k. Jantung</strong></label>
            </div>
                                <div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>S1</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="s1_jantung">
      
</div>
</div>
                                <div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>S2</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="s2_jantung">
     
</div>
</div>
<div class="row mb-2">
                        <div class="col-sm-2"><strong>Bunyi Teratur</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="bunyi_jantung" value="ya">
                            <label class="form-check-label">Ya</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="bunyi_jantung" value="tidak">
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>
            
                 
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Bunyi Tambahan</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="bunyi_tambahan" value="murmur">
                            <label class="form-check-label">Murmur</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="bunyi_tambahan" value="tidak">
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>
                                      <div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>Pulsasi Jantung</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="pulsasi_jantung">
</div>
</div>

                     <div class="row mb-2">
                        <div class="col-sm-2"><strong>Irama</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="irama_jantung" value="teratur">
                            <label class="form-check-label">Teratur</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="irama_jantung" value="tidak_teratur">
                            <label class="form-check-label">Tidak Teratur</label>
                        </div>
                    </div>
                </div>   

            <div class="row mb-2">
                <label class="col-sm-12 text-primary"><strong>l. Abdomen</strong></label>
            </div>
            <div class="row mb-2">
                        <div class="col-sm-2"><strong>Bentuk</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="bentuk_abdomen" value="datar">
                            <label class="form-check-label">Datar</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="bentuk_abdomen" value="membuncit">
                            <label class="form-check-label">Membuncit</label>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="bentuk_abdomen" value="cekung">
                            <label class="form-check-label">Cekung</label>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="bentuk_abdomen" value="tegang">
                            <label class="form-check-label">Tegang</label>
                        </div>
                    </div>
                </div>
                 <div class="row mb-2">
                        <div class="col-sm-2"><strong>Keadaan</strong>
                    </div>    

                   

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="keadaan_abdomen" value="parut">
                            <label class="form-check-label">Parut</label>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="keadaan_abdomen" value="lesi">
                            <label class="form-check-label">Lesi</label>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="keadaan_abdomen" value=" bercak_merah">
                            <label class="form-check-label"> Bercak Merah</label>
                        </div>
                    </div>
                </div>
         
 <div class="row mb-3 align-items-center">
    <label class="col-sm-2 col-form-label"><strong>Bising Usus</strong></label>
     <!-- Letak Gigi Palsu -->
    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="bising_usus" value="ada">
                            <label class="form-check-label">Ada</label>
                        </div>
                        
                    </div>
                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="bising_usus" value="tidak">
                            <label class="form-check-label">Tidak</label>
                        </div>
                        
                    </div>
    <div class="col-sm-5">
        <div class="input-group">
            <input type="text" class="form-control" name="frekuensi">
            <span class="input-group-text">kali</span> 
        </div>
    </div>
 </div>
  <div class="row mb-3 align-items-center">
    <label class="col-sm-2 col-form-label"><strong>Benjolan</strong></label>
     <!-- Letak Gigi Palsu -->
    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="benjolan_abdomen" value="ada">
                            <label class="form-check-label">Ada</label>
                        </div>
                        
                    </div>
                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="benjolan_abdomen" value="tidak">
                            <label class="form-check-label">Tidak</label>
                        </div>
                        
                    </div>
    <div class="col-sm-5">
        <div class="input-group">
            <input type="text" class="form-control" name="frekuensi">
            <span class="input-group-text">letak</span> 
        </div>
    </div>
 </div>
 <div class="row mb-3 align-items-center">
    <label class="col-sm-2 col-form-label"><strong>Nyeri Tekan</strong></label>
     <!-- Letak Gigi Palsu -->
    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="nyeri_abdomen" value="ada">
                            <label class="form-check-label">Ada</label>
                        </div>
                        
                    </div>
                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="nyeri_abdomen" value="tidak">
                            <label class="form-check-label">Tidak</label>
                        </div>
                        
                    </div>
    <div class="col-sm-5">
        <div class="input-group">
            <input type="text" class="form-control" name="frekuensi">
            <span class="input-group-text">letak</span> 
        </div>
    </div>
 </div>

                                       <div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>Perkusi Abdomen</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="perkusi_abdomen">
</div>
</div>
                    <div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>Kelainan</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="kelainan_abdomen">

</div>
</div>
  

            <div class="row mb-2">
                <label class="col-sm-12 text-primary"><strong>m. Genetalia</strong></label>
            </div>
            <div class="row mb-2">
                        <div class="col-sm-2"><strong>Bentuk</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="bentuk_genetalia" value="utuh">
                            <label class="form-check-label">Utuh</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="bentuk_genetalia" value="tidak">
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>
            
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Radang</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="radang_genetalia" value="ada">
                            <label class="form-check-label">Ada</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="radang_genetalia" value="tidak">
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>
                  
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Sekret</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="sekret_genetalia" value="ada">
                            <label class="form-check-label">Ada</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="sekret_genetalia" value="tidak">
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>
                    
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Pembengkakan Skrotum</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="skrotum_bengkak" value="ada">
                            <label class="form-check-label">Ada</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="skrotum_bengkak" value="tidak">
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>
                 
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Rektum</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="rektum_benjolan" value="benjolan">
                            <label class="form-check-label">Benjolan</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="rektum_benjolan" value="tidak">
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>
                    
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Lesi</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="lesi_genetalia" value="ya">
                            <label class="form-check-label">Ya</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="lesi_genetalia" value="tidak">
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>
                    
                                        <div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>Kelainan</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="kelainan_genetalia">
       
</div>
</div>

<div class="row mb-2">
                <label class="col-sm-12 text-primary"><strong>n. Ekstremitas</strong></label>
            </div>

         
                    <div class="row mb-2">
                        <label class="col-sm-12"><strong>1) Atas</strong></label>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Bentuk Simetris</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="atas_simetris" value="ya">
                            <label class="form-check-label">Ya</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="atas_simetris" value="tidak">
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>
                  <div class="row mb-2">
                        <div class="col-sm-2"><strong>Sensasi Halus</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="sensasi_halus" value="ada">
                            <label class="form-check-label">Ada</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="sensasi_halus" value="tidak">
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>
                <div class="row mb-2">
                        <div class="col-sm-2"><strong>Sensasi Tajam</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="sensasi_tajam" value="ada">
                            <label class="form-check-label">Ada</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="sensasi_tajam" value="tidak">
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>
                <div class="row mb-2">
                        <div class="col-sm-2"><strong>Sensasi Panas</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="sensasi_panas" value="ada">
                            <label class="form-check-label">Ada</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="sensasi_panas" value="tidak">
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>
                <div class="row mb-2">
                        <div class="col-sm-2"><strong>Sensasi Dingin</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="sensasi_dingin" value="ada">
                            <label class="form-check-label">Ada</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="sensasi_dingin" value="tidak">
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>
                <div class="row mb-2">
                        <div class="col-sm-2"><strong>Gerakan ROM</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="rom_atas" value="dapat">
                            <label class="form-check-label">Dapat</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="rom_atas" value="tidak">
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>

                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Refleks Bisep</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="refleks_bisep" value="ada">
                            <label class="form-check-label">Ada</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="refleks_bisep" value="tidak">
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>
                       
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Refleks Trisep</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="refleks_trisep" value="ada">
                            <label class="form-check-label">Ada</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="refleks_trisep" value="tidak">
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>
                <div class="row mb-2">
    <div class="col-sm-2"><strong>Pembengkakan</strong></div>
    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="pembengkakan" value="ya">
            <label class="form-check-label">Ya</label>
        </div>
    </div>
    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="pembengkakan" value="tidak">
            <label class="form-check-label">Tidak</label>
        </div>
    </div>
</div>

<div class="row mb-2">
    <div class="col-sm-2"><strong>Kelembaban</strong></div>
    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="kelembaban" value="lembab">
            <label class="form-check-label">Lembab</label>
        </div>
    </div>
    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="kelembaban" value="kering">
            <label class="form-check-label">Kering</label>
        </div>
    </div>
</div>

<div class="row mb-2">
    <div class="col-sm-2"><strong>Temperatur</strong></div>
    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="temperatur" value="panas">
            <label class="form-check-label">Panas</label>
        </div>
    </div>
    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="temperatur" value="dingin">
            <label class="form-check-label">Dingin</label>
        </div>
    </div>
</div>
<div class="row mb-3">

                        <label class="col-sm-2 col-form-label"><strong>Kekuatan Otot Tangan</strong></label>
                            <div class="col-sm-9">
                                <div class="row">

                        <!-- E -->
                        <div class="col-md-4 d-flex align-items-center">
                            <label class="me-2"><strong>Kanan</strong></label>
                            <input type="text" class="form-control" name="kanan">
                        </div>

                        <!-- M -->
                        <div class="col-md-4 d-flex align-items-center">
                            <label class="me-2"><strong>Kiri</strong></label>
                            <input type="text" class="form-control" name="kiri">
                        </div>

                        
                    </div>
                    </div>

     <div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>Kelainan</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="kelainan_genetalia">
  
</div>
</div>
    
                <div class="row mb-3">
               
                    <div class="row mb-2">
                        <label class="col-sm-12"><strong>2)	Bawah</strong></label>
                    </div>

                   <div class="row mb-2">
                        <div class="col-sm-2"><strong>Bentuk Simetris</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="atas_simetris" value="ya">
                            <label class="form-check-label">Ya</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="atas_simetris" value="tidak">
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>
                  <div class="row mb-2">
                        <div class="col-sm-2"><strong>Sensasi Halus</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="sensasi_halus" value="ada">
                            <label class="form-check-label">Ada</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="sensasi_halus" value="tidak">
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>
                <div class="row mb-2">
                        <div class="col-sm-2"><strong>Sensasi Tajam</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="sensasi_tajam" value="ada">
                            <label class="form-check-label">Ada</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="sensasi_tajam" value="tidak">
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>
                <div class="row mb-2">
                        <div class="col-sm-2"><strong>Sensasi Panas</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="sensasi_panas" value="ada">
                            <label class="form-check-label">Ada</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="sensasi_panas" value="tidak">
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>
                <div class="row mb-2">
                        <div class="col-sm-2"><strong>Sensasi Dingin</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="sensasi_dingin" value="ada">
                            <label class="form-check-label">Ada</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="sensasi_dingin" value="tidak">
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>
                <div class="row mb-2">
                        <div class="col-sm-2"><strong>Gerakan ROM</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="rom_atas" value="dapat">
                            <label class="form-check-label">Dapat</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="rom_atas" value="tidak">
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>
                  <div class="row mb-2">
                        <div class="col-sm-2"><strong>Refleks Babinski</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="refleks_babinski" value="ada">
                            <label class="form-check-label">Ada</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="refleks_babinski" value="tidak">
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>
                
<!-- Pembengkakan -->
<div class="row mb-2">
    <div class="col-sm-2"><strong>Pembengkakan</strong></div>

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="pembengkakan" value="ya">
            <label class="form-check-label">Ya</label>
        </div>
    </div>
    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="pembengkakan" value="tidak">
            <label class="form-check-label">Tidak</label>
        </div>
    </div>
</div>

<!-- Varises -->
<div class="row mb-2">
    <div class="col-sm-2"><strong>Varises</strong></div>
    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="varises" value="ada">
            <label class="form-check-label">Ada</label>
        </div>
    </div>
    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="varises" value="tidak">
            <label class="form-check-label">Tidak</label>
        </div>
    </div>
</div>

<!-- Kelembaban -->
<div class="row mb-2">
    <div class="col-sm-2"><strong>Kelembaban</strong></div>
    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="kelembaban" value="lembab">
            <label class="form-check-label">Lembab</label>
        </div>
    </div>
    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="kelembaban" value="kering">
            <label class="form-check-label">Kering</label>
        </div>
    </div>
</div>

<!-- Temperatur -->
<div class="row mb-2">
    <div class="col-sm-2"><strong>Temperatur</strong></div>
    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="temperatur" value="panas">
            <label class="form-check-label">Panas</label>
        </div>
    </div>
    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="temperatur" value="dingin">
            <label class="form-check-label">Dingin</label>
        </div>
    </div>
</div>

<!-- Kekuatan Otot Tangan -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Kekuatan Otot kaki</strong></label>
    <div class="col-sm-9">
        <div class="row">
            <div class="col-md-4 d-flex align-items-center">
                <label class="me-2"><strong>Kanan</strong></label>
                <input type="text" class="form-control" name="kanan">
            </div>
            <div class="col-md-4 d-flex align-items-center">
                <label class="me-2"><strong>Kiri</strong></label>
                <input type="text" class="form-control" name="kiri">
            </div>
        </div>
     
    </div>
</div>

<!-- Kelainan -->
<div class="row mb-3">
    <div class="col-sm-2 col-form-label"><strong>Kelainan</strong></div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="kelainan_genetalia">
    
    </div>
</div>
            
            <div class="row mb-2">
                <label class="col-sm-12 text-primary"><strong>o. Kulit</strong></label>
            </div>
                                <div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>Warna</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="warna_kulit">
       
</div>
</div>
                        <div class="row mb-2">
                        <div class="col-sm-2"><strong>Turgor</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="turgor_kulit" value="elastis">
                            <label class="form-check-label">Elastis</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="turgor_kulit" value="menurun">
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>
                       <div class="row mb-2">
                        <div class="col-sm-2"><strong>Kelembaban</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="kelembaban" value="lembab">
                            <label class="form-check-label">Lembab</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="kelembaban" value="kering">
                            <label class="form-check-label">Kering</label>
                        </div>
                    </div>
                </div>
                       
 <div class="row mb-3 align-items-center">
    <label class="col-sm-2 col-form-label"><strong>Edema</strong></label>
     <!-- Letak Gigi Palsu -->
    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="edema_kulit" value="ada">
                            <label class="form-check-label">Ada</label>
                        </div>
                        
                    </div>
                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="edema_kulit" value="tidak">
                            <label class="form-check-label">Tidak</label>
                        </div>
                        
                    </div>
                     
    <div class="col-sm-5">
        <div class="input-group">
            <input type="text" class="form-control" name="pada daerah">
            <span class="input-group-text">Pada Daerah</span> 
        </div>
    </div>
 </div>
                    <div class="row mb-3 align-items-center">
    <label class="col-sm-2 col-form-label"><strong>Luka</strong></label>
     <!-- Letak Gigi Palsu -->
    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="luka_kulit" value="ada">
                            <label class="form-check-label">Ada</label>
                        </div>
                        
                    </div>
                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="luka_kulit" value="tidak">
                            <label class="form-check-label">Tidak</label>
                        </div>
                        
                    </div>
                     
    <div class="col-sm-5">
        <div class="input-group">
            <input type="text" class="form-control" name="pada daerah">
            <span class="input-group-text">Pada Daerah</span> 
        </div>
    </div>
 </div>
                                          <div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>Karakteristik Luka</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="karakteristik_luka">

</div>
</div>                                    
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Tekstur</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="tekstur_kulit" value="licin">
                            <label class="form-check-label">Licin</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="tekstur_kulit" value="keriput">
                            <label class="form-check-label">Keriput</label>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="tekstur_kulit" value="kasar">
                            <label class="form-check-label">Kasar</label>
                        </div>
                    </div>
                </div>
                                        <div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>Kelainan</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="kelainan_kulit">

</div>
<div class="row mb-2">
                <label class="col-sm-12 text-primary"><strong>p. Kuku</strong></label>
            </div>
            <div class="row mb-2">
                        <div class="col-sm-2"><strong>Clubbing Finger</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="clubbing_finger" value="ya">
                            <label class="form-check-label">Ya</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="clubbing_finger" value="tidak">
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>
                                    <div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>Capillary Refill Time</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="capillary_refill_time">

</div>
</div>
                                <div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>Keadaan</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="keadaan_kuku">

</div>
</div>


            <div class="row mb-2">
                <label class="col-sm-12 text-primary"><strong>q. Status Neurologi</strong></label>
            </div>
            

      
          
                    <div class="row mb-2">
                        <label class="col-sm-12"><strong>1) Saraf-saraf Kranial</strong></label>
                    </div>
                                        <div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>a) Nervus I (Olfactorius) - Penciuman</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="nervus1_penciuman">
</div>
</div>

                    
                                        <div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>b) Nervus II (Opticus) - Penglihatan</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="nervus2_penglihatan">

</div>
</div>


                    <div class="row mb-2">
                        <label class="col-sm-12"><strong>c) Nervus III, IV, VI (Oculomotorius, Trochlearis, Abducens)</strong></label>
                    </div>
                                        <div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>Konstriksi Pupil</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="konstriksi_pupil">
 
</div>
</div>

                 
                                        <div class="row mb-3">
    <div class="col-sm-2 col-form-label">

        <strong>                        <label class="col-sm-11 col-form-label"><strong>Gerakan Kelopak Mata</strong></label>
</strong>
    </div>
    <div class="col-sm-9">
        <textarea name="gerakan_kelopak" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
       
</div>
</div>

                                        <div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>Pergerakan Bola Mata</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="gerakan_bola_mata">

</div>
</div>

                                        <div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>Pergerakan Mata ke Bawah & Dalam</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="gerakan_mata_bawah">

</div>
</div>

                   

                    <div class="row mb-2">
                        <label class="col-sm-12"><strong>d) Nervus V (Trigeminus)</strong></label>
                    </div>
                                        <div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>Refleks Dagu</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="refleks_dagu">

</div>
</div>

                    
                                        <div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>Refleks Cornea</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="refleks_cornea">

</div>
</div>


                    <div class="row mb-2">
                        <label class="col-sm-12"><strong>e) Nervus VII (Facialis)</strong></label>
                    </div>
                                        <div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>Pengecapan 2/3 Lidah Bagian Depan</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="pengecapan_depan">

</div>
</div>


                    <div class="row mb-2">
                        <label class="col-sm-12"><strong>f) Nervus VIII (Acusticus)</strong></label>
                    </div>
                                        <div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>Fungsi Pendengaran</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="fungsi_pendengaran">

</div>
</div>


                    <div class="row mb-2">
                        <label class="col-sm-12"><strong>g) Nervus IX & X (Glossopharyngeus dan Vagus)</strong></label>
                    </div>
                                        <div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>Refleks Menelan</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="refleks_menelan">

</div>
</div>

                                        <div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>Refleks Muntah</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="refleks_muntah">
 
</div>
                    </div>
                                        <div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>Pengecapan 1/3 Lidah Belakang</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="pengecapan_belakang">

</div>
</div>

                                        <div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>Suara</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="suara_pasien">

</div>
</div>


                    <div class="row mb-2">
                        <label class="col-sm-12"><strong>h) Nervus XI (Assesorius)</strong></label>
                    </div>
                                        <div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>Memalingkan Kepala</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="gerakan_kepala">

</div>
</div>

                                        <div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>Mengangkat Bahu</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="angkat_bahu">

</div>
</div>


                    <div class="row mb-2">
                        <label class="col-sm-12"><strong>i) Nervus XII (Hypoglossus)</strong></label>
                    </div>
                                        <div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>Deviasi Lidah</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="deviasi_lidah">

</div>
</div>

<div class="row mb-2">
                <label class="col-sm-12"><strong>2) Tanda-tanda Peradangan Selaput Otak</strong></label>
            </div>
                                <div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>Kaku Kuduk</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="kaku_kuduk">

</div>
</div>

                                        <div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>Kernig Sign</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="kernig_sign">

</div>
</div>

                                        <div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>Refleks Brudzinski</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="refleks_brudzinski">
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