<?php
require_once "koneksi.php";
require_once "utils.php";

$form_id       = 7;
$level         = $_SESSION['level'];
$user_id       = $_SESSION['id_user'];
$section_name  = 'riwayat_persalinan';
$section_label = 'Riwayat Persalinan';

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
// Load existing riwayat persalinan (array)
   $existing_persalinan = $existing_data['riwayat_persalinan'] ?? [];

    // =============================================
    // HANDLE POST - MAHASISWA SIMPAN DATA
    // =============================================
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $level === 'Mahasiswa') {
        if (isLocked($submission)) {
            redirectWithMessage($_SERVER['REQUEST_URI'], 'error', 'Data tidak dapat diubah karena sedang dalam proses review.');
        }
        // Proses dynamic rows persalinan
        $persalinan = [];
        if (!empty($_POST['persalinan'])) {
            foreach ($_POST['persalinan'] as $index => $row) {
                // Skip row kalau semua field kosong
                if (empty($row['jenis']) && empty($row['cara_lahir'])  && empty($row['bb'])&& empty($row['keadaan'])&& empty($row['umur'])) {
                    continue;
                }
                $persalinan[] = [
                    'no'              => $index,
                    'jenis'           => $row['jenis'] ?? '',
                    'cara_lahir'      => $row['cara_lahir'] ?? '',
                    'bb'              => $row['bb'] ?? '',
                    'keadaan'         => $row['keadaan'] ?? '',
                    'umur'            => $row['umur'] ?? '',
                ];
            }
        }

    $tgl_pengkajian = $_POST['tglpengkajian'] ?? '';
    $rs_ruangan     = $_POST['rsruangan'] ?? '';

    $data = [
        'riwayat_persalinan'    => $persalinan,
        'anc'                                       => $_POST['anc'] ?? '',
        'masalah_kehamilanlalu'                     => $_POST['masalahkehamilanlalu'] ?? '',
        'masalah_kehamilan_sekarang'                => $_POST['masalahkehamilansekarang'] ?? '',
        'rencana_kb'                                => $_POST['rencanakb'] ?? '',
        'makanan_bayi'                              => $_POST['makananbayi'] ?? '',
        'bayi_lahir'                                => $_POST['bayilahir'] ?? '',
        'masalah_persalinan'                        => $_POST['masalahpersalinan'] ?? '',
        'keluhan_utama'                             => $_POST['keluhanutama'] ?? '',
        'riwayat_keluhan_utama'                     => $_POST['riwayatkeluhanutama'] ?? '',
        'mulai_persalinan'                          => $_POST['mulaipersalinan'] ?? '',
        'tekana_ndarah'                             => $_POST['tekanandarah'] ?? '',
        'nadi'                                      => $_POST['nadi'] ?? '',
        'suhu'                                      => $_POST['suhu'] ?? '',
        'rr'                                        => $_POST['rr'] ?? '',
        'kepala_dan_rambut'                         => $_POST['kepaladanrambut'] ?? '',
        'inspeksi_wajah'                            => $_POST['inspeksiwajah'] ?? '',
        'masalah_wajah'                             => $_POST['masalahwajah'] ?? '',
        'konjungtiva'                               => $_POST['konjungtiva'] ?? '',
        'sklerag'                                   => $_POST['sklera'] ?? '',
        'mukosa_bibir'                              => $_POST['mukosabibir'] ?? '',
        'sariawan'                                  => $_POST['sariawan'] ?? '',
        'gigi_berlubang'                            => $_POST['gigiberlubang'] ?? '',
        'masalah_khusus_mulut'                      => $_POST['masalahkhususmulut'] ?? '',
        'distensi'                                  => $_POST['distensi'] ?? '',
        'kelenjar_tiroid'                           => $_POST['kelenjartiroid'] ?? '',
        'nyeri_menelan'                             => $_POST['nyerimenelan'] ?? '',
        'masalah_khusus_leher'                      => $_POST['masalahkhususleher'] ?? '',
        'bunyi_jantung'                             => $_POST['bunyijantung'] ?? '',
        'masalah_khusus_bunyi_jantung'              => $_POST['masalahkhususbunyijantung'] ?? '',
        'sistem_pernapasan'                         => $_POST['sistempernapasan'] ?? '',
        'masalah_khusus_sistem_pernapasan'          => $_POST['masalahkhusussistempernapasan'] ?? '',
        'pengeluaran_asi'                           => $_POST['pengeluaranasi'] ?? '',
        'puting'                                    => $_POST['puting'] ?? '',
        'masalah_khusus_payudadra'                  => $_POST['masalahkhususpayudadra'] ?? '',
        'abdomen'                                   => $_POST['abdomen'] ?? '',
        'pemeriksaan_palpasi_abdomen'               => $_POST['pemeriksaanpalpasiabdomen'] ?? '',
        'inspeksitfu'                               => $_POST['inspeksitfu'] ?? '',
        'inspeksi_kontraksi'                        => $_POST['inspeksikontraksi'] ?? '',
        'leopoldi'                                  => $_POST['leopoldi'] ?? '',
        'kanan'                                     => $_POST['kanan'] ?? '',
        'kiri'                                      => $_POST['kiri'] ?? '',
        'leopoldiii'                                => $_POST['leopoldiii'] ?? '',
        'leopoldiv'                                 => $_POST['leopoldiv'] ?? '',
        'pemeriksaandjj'                            => $_POST['pemeriksaandjj'] ?? '',
        'intensitas'                                => $_POST['intensitas'] ?? '',
        'keteraturan'                               => $_POST['keteraturan'] ?? '',
        'jumlah'                                    => $_POST['jumlah'] ?? '',
        'ekstremitas_atas'                          => $_POST['ekstremitasatas'] ?? '',
        'ekstremitas_bawah'                         => $_POST['ekstremitasbawah'] ?? '',
        'persiapan_perineum'                        => $_POST['persiapanperineum'] ?? '',
        'pengeluaran_pervaginam'                    => $_POST['pengeluaranpervaginam'] ?? '',
        'status_janin'                               => $_POST['statusjanin'] ?? '',
        'kelas_prenatal'                    => $_POST['kelasprenatal'] ?? '',
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
    <?php include "maternitas/pengkajian_inranatal_care/tab.php"; ?>
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
            <div class="card-body">
              <h5 class="card-title mb-1"><strong>RIWAYAT PERSALINAN</strong></h5>

             <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">
                       <table class="table table-bordered" id="tabel-persalinan">
                           <thead>
                               <tr>
                                   <th class="text-center">No</th>
                                   <th class="text-center">Jenis Kelamin</th>
                                   <th class="text-center">Cara Lahir</th>
                                   <th class="text-center">BB Lahir (gram)</th>
                                   <th class="text-center">Keadaan</th>
                                   <th class="text-center">Umur </th>
                                   <th class="text-center">Aksi</th>
                               </tr>
                           </thead>
                           <tbody id="tbody-persalinan">
                               <!-- Row dinamis masuk sini -->
                           </tbody>
                       </table>

                        <?php if (!$is_dosen): ?>
                        <div class="row mb-3">
                            <div class="col-sm-11 d-flex justify-content-end">
                                <button type="button" class="btn btn-primary" onclick="tambahRow()">Tambah Data</button>
                            </div>
                        </div>
                        <?php endif; ?>

           

                <!-- Bagian Kelas Prenatal -->
                        <div class="row mb-3">

                        <label class="col-sm-2 col-form-label"><strong>Kelas Prenatal</strong></label>

                        <!-- Field -->
                        <div class="col-sm-10">
                            <select class="form-select" name="kelasprenatal"<?= $ro_select ?>>
                                <option value="">Pilih</option>
                                <option value="Ya" <?= val('kelas_prenatal', $existing_data) === 'Ya' ? 'selected' : '' ?>>Ya</option>
                                <option value="Tidak" <?= val('kelas_prenatal', $existing_data) === 'Tidak' ? 'selected' : '' ?>>Tidak</option>
                            </select>
                            </div>  
                        </div>  

            <!-- Bagian Kunjungan ANC -->
                <div class="row mb-3">
                    <label for="anc" class="col-sm-2 col-form-label"><strong>Jumlah Kunjungan ANC pada kehamilan ini</strong></label>
                    <div class="col-sm-10">
                        <div class="input-group">
                            <input type="text" class="form-control" name="anc" value="<?= val('anc', $existing_data) ?>" <?= $ro ?>>
                            <span class="input-group-text">kali</span>
                    </div>
                         </div>
                    </div>   
                    
            <!-- Bagian Masalah Kehamilan Yang lalu -->
                        <div class="row mb-3">
                            <label for="masalahkehamilanlalu" class="col-sm-2 col-form-label"><strong>Masalah Kehamilan yang Lalu</strong></label>
                            <div class="col-sm-10">
                               <textarea name="masalahkehamilanlalu" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                               <?= $ro ?>><?= val('masalah_kehamilanlalu', $existing_data) ?></textarea>
                         </div>
                    </div>   
                    
            <!-- Bagian Masalah Kehamilan Sekarang -->
                        <div class="row mb-3">
                            <label for="masalahkehamilansekarang" class="col-sm-2 col-form-label"><strong>Masalah Kehamilan Sekarang</strong></label>
                            <div class="col-sm-10">
                               <textarea name="masalahkehamilansekarang" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                               <?= $ro ?>><?= val('masalah_kehamilan_sekarang', $existing_data) ?></textarea></textarea>
                         </div>
                    </div>
                    
            <!-- Bagian Rencana KB -->
                        <div class="row mb-3">
                            <label for="rencanakb" class="col-sm-2 col-form-label"><strong>Rencana KB</strong></label>
                            <div class="col-sm-10">
                               <input type="text" class="form-control" name="rencanakb" value="<?= val('rencana_kb', $existing_data) ?>" <?= $ro ?>>
                         </div>
                    </div>
                    
            <!-- Bagian Makanan Bayi -->
                        <div class="row mb-3">

                        <label class="col-sm-2 col-form-label"><strong>Makanan Bayi Sebelumnya</strong></label>

                        <!-- Field -->
                        <div class="col-sm-10">
                            <select class="form-select" name="makananbayi"<?= $ro_select ?>>
                                <option value="">Pilih</option>
                                <option value="ASI"<?= val('makanan_bayi', $existing_data) === 'ASI' ? 'selected' : '' ?>>ASI</option>
                                <option value="PASI<?= val('makanan_bayi', $existing_data) === 'PASI' ? 'selected' : '' ?>">PASI</option>
                                <option value="Lainnya"<?= val('makanan_bayi', $existing_data) === 'Lainnya' ? 'selected' : '' ?>>Lainnya</option>
                            </select>
                        </div>

                    </div>  
                    
            <!-- Bagian Bayi Lahir -->
                        <div class="row mb-3">
                            <label for="bayilahir" class="col-sm-2 col-form-label"><strong>Setelah bayi lahir, siapa yang diharapkan membantu?</strong></label>
                            <div class="col-sm-10">
                                <small class="form-text" style="color: red;">Suami/Teman/Orang Tua? Hasil:</small>
                               <textarea name="bayilahir" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                               <?= $ro ?>><?= val('bayi_lahir', $existing_data) ?></textarea>
                         </div>
                    </div>  
                    
            <!-- Bagian Masalah Persalinan -->
                        <div class="row mb-3">
                            <label for="masalahpersalinan" class="col-sm-2 col-form-label"><strong>Masalah Persalinan</strong></label>
                            <div class="col-sm-10">
                                <small class="form-text" style="color: red;">Ada/Tidak? Jelaskan.</small>
                               <textarea name="masalahpersalinan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                               <?= $ro ?>><?= val('masalah_persalinan', $existing_data) ?></textarea></textarea>
                    
                </div>
            </div>
                    

                <h5 class="card-title"><strong>RIWAYAT PERSALINAN SEKARANG</strong></h5>
            
                <!-- Bagian Keluhan Utama -->
                        <div class="row mb-3">
                            <label for="keluhanutama" class="col-sm-2 col-form-label"><strong>Keluhan Utama</strong></label>
                            <div class="col-sm-10">
                               <textarea name="keluhanutama" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                               <?= $ro ?>><?= val('keluhan_utama', $existing_data) ?></textarea></textarea>
                         </div>
                    </div> 

                <!-- Bagian Riwayat Keluhan Utama -->
                        <div class="row mb-3">
                            <label for="riwayatkeluhanutama" class="col-sm-2 col-form-label"><strong>Riwayat Keluhan Utama</strong></label>
                            <div class="col-sm-10">
                               <textarea name="riwayatkeluhanutama" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                               <?= $ro ?>><?= val('riwayat_keluhan_utama', $existing_data) ?></textarea></textarea>
                         </div>
                    </div>  

                <!-- Bagian Mulai Persalinan -->
                        <div class="row mb-3">
                            <label for="mulaipersalinan" class="col-sm-2 col-form-label"><strong>Mulai Persalinan</strong></label>
                            <div class="col-sm-10">
                                <small class="form-text" style="color: red;">Kontraksi (teratur/tidak), interval, lama. Hasil:</small>
                               <textarea name="mulaipersalinan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('mulai_persalinan', $existing_data) ?></textarea></textarea>
                         </div>
                    </div>  
                    
                <!-- Bagian Tanda-tanda Vital -->

                    <div class="row mb-3">
                        <label class="col-sm-9 col-form-label text-primary">
                            <strong>Tanda-tanda Vital</strong>
                        </label>    
                    </div>

                    <!-- Tekanan Darah -->
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-2 col-form-label"><strong>Tekanan Darah</strong></label>
                        <div class="col-sm-4">
                            <div class="input-group">
                                <input type="text" class="form-control" name="tekanandarah" value="<?= val('tekana_ndarah', $existing_data) ?>" <?= $ro ?>>
                                <span class="input-group-text">mmHg</span>
                        </div>    
                    </div>
                                
                    <!-- Nadi -->
                    <label class="col-sm-2 col-form-label"><strong>Nadi</strong></label>
                    <div class="col-sm-4">
                        <div class="input-group">
                                <input type="text" class="form-control" name="nadi" value="<?= val('nadi', $existing_data) ?>" <?= $ro ?>>
                                <span class="input-group-text">x/menit</span>
                        </div> 
                    </div>

                    </div>
              
                    <!-- Suhu -->
                        <div class="row mb-3 align-items-center">
                            <label class="col-sm-2 col-form-label"><strong>Suhu</strong></label>
                            <div class="col-sm-4">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="suhu" value="<?= val('suhu', $existing_data) ?>" <?= $ro ?>>
                                    <span class="input-group-text">°C</span>
                            </div>    
                        </div>

                    <!-- RR -->
                    <label class="col-sm-2 col-form-label"><strong>RR</strong></label>
                    <div class="col-sm-4">
                        <div class="input-group">
                                <input type="text" class="form-control" name="rr" value="<?= val('rr', $existing_data) ?>" <?= $ro ?>>
                                <span class="input-group-text">x/menit</span>
                            </div>
                        </div>
                    </div>

                <!-- Bagian Kepala dan Rambut -->

                    <div class="row mb-2">
                        <label class="col-sm-2 col-form-label text-primary">
                            <strong>Kepala dan Rambut</strong>
                    </div>
                    
                    <!-- Kepala dan Rambut -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Kepala dan Rambut</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Rontok (Ya/Tidak), Kulit Kepala (Bersih/Kotor), Nyeri Tekan (Ya/Tidak). Hasil:</small>
                            <textarea name="kepaladanrambut" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('kepala_dan_rambut', $existing_data) ?></textarea>
                         </div>
                    </div>        
  
                    <!-- Bagian Wajah -->

                    <div class="row mb-2">
                        <label class="col-sm-2 col-form-label text-primary">
                            <strong>Wajah</strong>
                    </div>
                    
                    <!-- Hiperpigmentasi -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Hiperpigmentasi (Cloasma Gravidarum)</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Ya/Tidak, Area ...</small>
                            <textarea name="inspeksiwajah" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('inspeksi_wajah', $existing_data) ?></textarea></textarea>
                         </div>
                    </div> 

                    <!-- Masalah Khusus -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Masalah Khusus</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Ekspresi wajah, apakah pucat dan bengkak. Hasil:</small>
                            <textarea name="masalahwajah" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('masalah_wajah', $existing_data) ?></textarea>
                         </div>
                    </div> 

                    <!-- Bagian Mata -->

                    <div class="row mb-2">
                        <label class="col-sm-2 col-form-label text-primary">
                            <strong>Mata</strong>
                    </div>
                    
                    <!-- Konjungtiva  -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Konjungtiva</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Anemis/An-anemis. Hasil:</small>
                           <textarea name="konjungtiva" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                           <?= $ro ?>><?= val('konjungtiva', $existing_data) ?></textarea></textarea>
                         </div>
                    </div> 

                    <!-- Sklera -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Sklera</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Ikterik/An-ikterik. Hasil:</small>
                            <textarea name="sklera" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('sklerag', $existing_data) ?></textarea></textarea>
                         </div>
                    </div> 

                <!-- Bagian Mulut -->

                    <div class="row mb-2">
                        <label class="col-sm-2 col-form-label text-primary">
                            <strong>Mulut</strong>
                    </div>
                    
                    <!-- Mukosa Bibir -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Mukosa Bibir</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Lembab/Kering. Hasil:</small>
                            <textarea name="mukosabibir" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('mukosa_bibir', $existing_data) ?></textarea></textarea>
                         </div>
                    </div> 

                    <!-- Sariawan -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Sariawan</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">(Ada/Tidak Ada). Hasil:</small>
                            <textarea name="sariawan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('sariawan', $existing_data) ?></textarea></textarea>
                         </div>
                    </div> 

                    <!-- Gigi Berlubang -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Gigi Berlubang</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Ya/Tidak. Hasil:</small>
                            <textarea name="gigiberlubang" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('gigi_berlubang', $existing_data) ?></textarea></textarea>
                         </div>
                    </div> 

                     <!-- Masalah Khusus -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Masalah Khusus</strong></label>

                            <div class="col-sm-10">
                                <textarea name="masalahkhususmulut" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('masalah_khusus_mulut', $existing_data) ?></textarea></textarea>
                         </div>
                    </div>  
                                    
                     <!-- Bagian Leher -->

                    <div class="row mb-2">
                        <label class="col-sm-2 col-form-label text-primary">
                            <strong>Leher</strong>
                    </div>
                    
                    <!-- Distensi -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Distensi Vena Jugularis</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Ya/Tidak. Hasil:</small>
                            <textarea name="distensi" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('distensi', $existing_data) ?></textarea></textarea>
                         </div>
                    </div> 

                    <!-- Kelenjar Tiroid -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Pembesaran Kelenjar Tiroid</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Ya/Tidak. Hasil:</small>
                            <textarea name="kelenjartiroid" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('kelenjar_tiroid', $existing_data) ?></textarea>
                         </div>
                    </div> 

                    <!-- Nyeri Menelan -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Nyeri Menelan</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Ya/Tidak. Hasil:</small>
                            <textarea name="nyerimenelan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('nyeri_menelan', $existing_data) ?></textarea></textarea>
                         </div>
                    </div> 

                     <!-- Riwayat Masalah Khusus -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Masalah Khusus</strong></label>

                            <div class="col-sm-10">
                                <textarea name="masalahkhususleher" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('masalah_khusus_leher', $existing_data) ?></textarea></textarea>
                         </div>
                    </div>  
                            
                     <!-- Bagian Dada -->

                    <div class="row mb-2">
                        <label class="col-sm-8 col-form-label text-primary">
                            <strong>Dada/Thorax</strong>
                    </div>
                    
                    <!-- Bunyi Jantung -->
                  
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Bunyi Jantung</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Normal atau apakah terdapat Mur-mur dan Gallop. Hasil:</small>
                            <textarea name="bunyijantung" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('bunyi_jantung', $existing_data) ?></textarea></textarea>
                         </div>
                    </div> 

                    <!-- Masalah Khusus -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Masalah Khusus</strong></label>

                            <div class="col-sm-10">
                                <textarea name="masalahkhususbunyijantung" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('masalah_khusus_bunyi_jantung', $existing_data) ?></textarea></textarea>
                         </div>
                    </div>

                    <!-- Sistem Pernapasan -->
                  
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Sistem Pernapasan</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Suara Napas (Vesikuler/Wheezing/Ronkhi). Hasil:</small>
                            <textarea name="sistempernapasan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('sistem_pernapasan', $existing_data) ?></textarea></textarea>
                         </div>
                    </div> 

                    <!-- Masalah Khusus -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Masalah Khusus</strong></label>

                            <div class="col-sm-10">
                                <textarea name="masalahkhusussistempernapasan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('masalah_khusus_sistem_pernapasan', $existing_data) ?></textarea></textarea>
                         </div>
                    </div>
                            
                    <!-- Bagian Payudara -->

                    <div class="row mb-2">
                        <label class="col-sm-2 col-form-label text-primary">
                            <strong>Payudara</strong>
                    </div>
                    
                    <!-- Asi -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Pengeluaran ASI/Kolostum</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Ya/Tidak. Hasil:</small>
                            <textarea name="pengeluaranasi" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('pengeluaran_asi', $existing_data) ?></textarea></textarea>
                         </div>
                    </div> 

                    <!-- Inspeksi Puting -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Puting</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">(Eksverted/Inverted/Platnipple). Hasil:</small>
                            <textarea name="puting" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('puting', $existing_data) ?></textarea></textarea>
                         </div>
                    </div>

                     <!-- Masalah Khusus -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Masalah Khusus</strong></label>

                            <div class="col-sm-10">
                                <textarea name="masalahkhususpayudadra" class="form-control" rows="5" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('masalah_khusus_payudadra', $existing_data) ?></textarea></textarea>
                         </div>
                    </div>
                            
                <!-- Bagian Abdomen -->

                    <div class="row mb-3">
                        <label class="col-sm-9 col-form-label text-primary">
                            <strong>Abdomen</strong>
                        </label>    
                    </div>

                    <!-- Abdomen  -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Abdomen</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Apakah terdapat: Lignea Nigra/Striae Nigra/Striae Alba, Bekas Operasi. Hasil:</small>
                            <textarea name="abdomen" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('abdomen', $existing_data) ?></textarea></textarea>
                         </div>
                    </div>

                     <!-- Pemeriksaan Palpasi Abdomen  -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Pemeriksaan Palpasi Abdomen</strong></label>

                        <div class="col-sm-10">
                            <textarea name="pemeriksaanpalpasiabdomen" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('pemeriksaan_palpasi_abdomen', $existing_data) ?></textarea>
                         </div>
                    </div>

                     <div class="row mb-3">
                        <label class="col-sm-9 col-form-label text-primary">
                            <strong>Uterus</strong>
                        </label>    
                    </div>

                    <!-- TFU -->
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-2 col-form-label"><strong>TFU</strong></label>
                        <div class="col-sm-4">
                            <div class="input-group">
                                <input type="text" class="form-control" name="inspeksitfu" value="<?= val('inspeksitfu', $existing_data) ?>" <?= $ro ?>>
                                <span class="input-group-text">cm</span>
                        </div>    
                    </div>
                                
                    <!-- Kontraksi -->
                    <label class="col-sm-2 col-form-label"><strong>Kontraksi</strong></label>
                    <div class="col-sm-4">
                        <select class="form-select" name="inspeksikontraksi"<?= $ro_select ?>>
                            <option value="">Pilih</option>
                            <option value="Ya"<?= val('inspeksi_kontraksi', $existing_data) === 'Ya' ? 'selected' : '' ?>>Ya</option>
                            <option value="Tidak"<?= val('inspeksi_kontraksi', $existing_data) === 'Tidak' ? 'selected' : '' ?>>Tidak</option> 
                        </select>
                    </div>    
                </div>

                    <!-- Leopold I -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Leopold I</strong></label>

                        <div class="col-sm-10">
                            <select class="form-select" name="leopoldi"<?= $ro_select ?>>
                                <option value="">Pilih</option>
                                <option value="Kepala" <?= val('leopoldi', $existing_data) === 'Kepala' ? 'selected' : '' ?>>Kepala</option>
                                <option value="Bokong" <?= val('leopoldi', $existing_data) === 'Bokong' ? 'selected' : '' ?>>Bokong</option>
                                <option value="Kosong"<?= val('leopoldi', $existing_data) === 'Kosong' ? 'selected' : '' ?>>Kosong</option>
                            </select>
                         </div>
                    </div>

                    <!-- Leopold II -->

                    <div class="row mb-2">
                        <label class="col-sm-2 col-form-label">
                            <strong>Leopold II</strong>
                    </div>
                    
                    <!-- Kanan -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Kanan</strong></label>

                       <div class="col-sm-10">
                            <select class="form-select" name="kanan"<?= $ro_select ?>>
                                <option value="">Pilih</option>
                                <option value="Punggung" <?= val('kanan', $existing_data) === 'Punggung' ? 'selected' : '' ?>>Punggung</option>
                                <option value="Bagian Kecil" <?= val('kanan', $existing_data) === 'Bagian Kecil' ? 'selected' : '' ?>>Bagian Kecil</option>
                                <option value="Kepala" <?= val('kanan', $existing_data) === 'Kepala' ? 'selected' : '' ?>>Kepala</option>
                            </select>
                         </div>
                    </div>

                    <!-- Kiri -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Kiri</strong></label>

                       <div class="col-sm-10">
                            <select class="form-select" name="kiri" <?= $ro_select ?>>
                                <option value="">Pilih</option>
                                <option value="Punggung" <?= val('kiri', $existing_data) === 'Punggung' ? 'selected' : '' ?>>Punggung</option>
                                <option value="Bagian Kecil" <?= val('kiri', $existing_data) === 'Bagian Kecil' ? 'selected' : '' ?>>Bagian Kecil</option>
                                <option value="Kepala" <?= val('kiri', $existing_data) === 'Kepala' ? 'selected' : '' ?>>Kepala</option>
                            </select>
                         </div>
                    </div>        

                    <!-- Leopold III -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Leopold III</strong></label>

                       <div class="col-sm-10">
                            <select class="form-select" name="leopoldiii" <?= $ro_select ?>>
                                <option value="">Pilih</option>    
                                <option value="Kepala" <?= val('leopoldiii', $existing_data) === 'Kepala' ? 'selected' : '' ?>>Kepala</option>
                                <option value="Bokong" <?= val('leopoldiii', $existing_data) === 'Bokong' ? 'selected' : '' ?>>Bokong</option>
                                <option value="Kosong" <?= val('leopoldiii', $existing_data) === 'Kosong' ? 'selected' : '' ?>>Kosong</option>
                            </select>
                         </div>
                    </div>        

                    <!-- Leopold IV Penurunan Kepala-->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Leopold IV Penurunan Kepala</strong></label>

                       <div class="col-sm-10">
                            <select class="form-select" name="leopoldiv" <?= $ro_select ?>>
                                <option value="">Pilih</option>
                                <option value="Sudah" <?= val('leopoldiv', $existing_data) === 'Sudah' ? 'selected' : '' ?>>Sudah</option>
                                <option value="Belum" <?= val('leopoldiv', $existing_data) === 'Belum' ? 'selected' : '' ?>>Belum</option>
                            </select>
                         </div>
                    </div>        

                    <!-- Pemeriksaan DJJ -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Pemeriksaan DJJ</strong></label>

                        <div class="col-sm-10">
                            <div class="input-group">
                                <input type="text" class="form-control" name="pemeriksaandjj" value="<?= val('pemeriksaandjj', $existing_data) ?>" <?= $ro ?>>
                                <span class="input-group-text">Frek</span>
                            </div>
                                <small class="form-text" style="color: red;">(Normal 120-160/bradikardi, 160-180/tachikardi < 120) </small>
                            </div>
                    </div>
                    
                    <!-- Intensitas -->
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-2 col-form-label"><strong>Intensitas</strong></label>
                        <div class="col-sm-4">
                            <div class="input-group">
                                <input type="text" class="form-control" name="intensitas" value="<?= val('intensitas', $existing_data) ?>" <?= $ro ?>>
                                <span class="input-group-text">Intensitas</span>
                        </div>    
                    </div>
                                
                    <!-- Keteraturan -->
                    <label class="col-sm-2 col-form-label"><strong>Keteraturan</strong></label>
                    <div class="col-sm-4">
                            <div class="input-group">
                                <input type="text" class="form-control" name="keteraturan" value="<?= val('keteraturan', $existing_data) ?>" <?= $ro ?>>
                                <span class="input-group-text">Keteraturan</span>
                        </div>    
                    </div>  
                </div> 
                    
                    <!-- Status Janin -->
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-2 col-form-label"><strong>Status Janin</strong></label>
                        <div class="col-sm-4">
                                <select class="form-select" name="statusjanin" <?= $ro_select ?>>
                                <option value="">Pilih</option>
                                <option value="Hidup" <?= val('status_janin', $existing_data) === 'Hidup' ? 'selected' : '' ?>>Hidup</option>
                                <option value="Tidak" <?= val('status_janin', $existing_data) === 'Tidak' ? 'selected' : '' ?>>Tidak</option> 
                            </select>
                        </div>    
                                
                    <!-- Jumlah -->
                    <label class="col-sm-2 col-form-label"><strong>Jumlah</strong></label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" name="jumlah" value="<?= val('jumlah', $existing_data) ?>" <?= $ro ?>>
                    </div>    
                </div>

                    <style>
                    .table-abdomen {
                        table-layout: fixed;
                        width:100%
                    }

                    .table-abdomen td,
                    .table-abdomen th {
                        word-wrap: break-word;
                        white-space: normal;
                        vertical-align: top;
                    }

                
                    </style>

                    <table class="table table-bordered table-abdomen">

                    <tbody>
                        <tr>
                            <td colspan="2"><strong>Uterus</strong></td>
                        </tr>

                        <tr>
                            <td><strong>TFU</strong></td>
                            <td><?= $row['tfu'] ?? ''; ?> cm</td>
                        </tr>

                        <tr>
                            <td></td>
                            <td>Kontraksi: <?= $row['kontraksi'] ?? ''; ?></td>
                        </tr>

                        <tr>
                            <td><strong>Leopold I<strong></td>
                            <td><?= $row['leopoldi'] ?? ''; ?></td>
                        </tr>

                        <tr>
                            <td><strong>Leopold II<strong></td>
                            <td>Kanan: <?= $row['kanan'] ?? ''; ?></td>
                        </tr>

                        <tr>
                            <td></td>
                            <td>Kiri: <?= $row['kiri'] ?? ''; ?></td>
                        </tr>

                        <tr>
                            <td><strong>Leopold III<strong></td>
                            <td><?= $row['leopoldiii'] ?? ''; ?></td>
                        </tr>

                        <tr>
                            <td><strong>Leopold IV Penurunan Kepala<strong></td>
                            <td><?= $row['leopoldiv'] ?? ''; ?></td>
                        </tr>

                        <tr>
                            <td><strong>Leopold I<strong></td>
                            <td><?= $row['leopoldi'] ?? ''; ?></td>
                        </tr>

                        <tr>
                            <td><strong>Pemeriksaan DJJ</strong></td>
                        <td>
                            <?= $row['pemeriksaandjj'] ?? ''; ?> Frek (normal 120-160),
                            <?= $row['intensitas'] ?? ''; ?> Intensitas,
                            <?= $row['keteraturan'] ?? ''; ?> Keteraturan
                        </td>
                        </tr>

                        <tr>
                            <td><strong>Status Janin</strong></td>
                            <td><?= $row['statusjanin'] ?? ''; ?></td>
                        </tr>

                        <tr>
                            <td></td>
                            <td>Jumlah: <?= $row['jumlah'] ?? ''; ?></td>
                        </tr>

                    </tbody>
                    </table>

                    <!-- Bagian Ekstremitas -->

                    <div class="row mb-2">
                        <label class="col-sm-8 col-form-label text-primary">
                            <strong>Ekstremitas</strong>
                    </div>
                    
                    <!-- Ekstremitas Atas -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Ekstremitas Atas</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Apakah terdapat edema (ya/tidak), rasa kesemutan/baal (ya/tidak). Hasil:</small>
                            <textarea name="ekstremitasatas" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('ekstremitas_atas', $existing_data) ?></textarea>
                         </div>
                    </div>

                    <!-- Ekstremitas Bawah -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Ekstremitas Bawah</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Apakah terdapat edema (ya/tidak), varises (ya/tidak), refleks pattela (+/-). Hasil:</small>
                            <textarea name="ekstremitasbawah" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('ekstremitas_bawah', $existing_data) ?></textarea></textarea>
                         </div>
                    </div>

                    <!-- Bagian Vagina -->

                    <div class="row mb-2">
                        <label class="col-sm-8 col-form-label text-primary">
                            <strong>Vagina</strong>
                    </div>
                    
                    <!-- Persiapan Perineum -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Vagina</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Persiapan perineum. Hasil:</small>
                            <textarea name="persiapanperineum" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('persiapan_perineum', $existing_data) ?></textarea></textarea>
                         </div>
                    </div> 

                    <!-- Pengeluaran Pervaginam -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Pengeluaran Pervaginam. Hasil:</small>
                            <textarea name="pengeluaranpervaginam" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('pengeluaran_pervaginam', $existing_data) ?></textarea></textarea>
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
 <script>
            let rowCount = 1;
            // Load existing data persalinan dari PHP
            const existingPersalinan = <?= json_encode($existing_persalinan) ?>;
            function tambahRow(data = null) {
                const tbody = document.getElementById('tbody-persalinan');
                const row = document.createElement('tr');
                const index = rowCount;
                row.innerHTML = `
                    <td>${index}</td>
                    <td>
                        <select class="form-select form-select-sm" name="persalinan[${index}][jenis_kelamin]" <?= $ro_select ?> >
                            <option value="">Pilih</option>
                            <option value="Perempuan" ${data?.jenis_kelamin === 'Perempuan' ? 'selected' : ''}>Perempuan</option>
                            <option value="Laki-laki" ${data?.jenis_kelamin === 'Laki-laki' ? 'selected' : ''}>Laki-laki</option>
                        </select>
                    </td>
                    <td><input type="text" class="form-control form-control-sm" name="persalinan[${index}][cara_lahir]" value="${data?.cara_lahir ?? ''}" <?= $ro ?>></td>
                    <td><input type="text" class="form-control form-control-sm" name="persalinan[${index}][bb]" value="${data?.bb ?? ''}" <?= $ro ?>></td>
                    <td><input type="text" class="form-control form-control-sm" name="persalinan[${index}][keadaan]" value="${data?.keadaan ?? ''}" <?= $ro ?>></td>
                    
                    <td><input type="text" class="form-control form-control-sm" name="persalinan[${index}][umur]" value="${data?.umur ?? ''}" <?= $ro ?>></td>
                    <td>${!<?= json_encode($is_dosen) ?> && !<?= json_encode($is_readonly) ?> ? `<button type="button" class="btn btn-danger btn-sm" onclick="hapusRow(this)">x</button>` : ''}</td>
                `;
                tbody.appendChild(row);
                rowCount++;
            }
            function hapusRow(btn) {
                btn.closest('tr').remove();
            }
            // Load existing rows kalau ada
            window.addEventListener('load', function() {
                if (existingPersalinan && existingPersalinan.length > 0) {
                    existingPersalinan.forEach(row => tambahRow(row));
                } else {
                    tambahRow(); // default 1 row kosong
                }
            });
            const existingData = <?= json_encode($existing_data) ?>;
        </script>
        </script>
    </section>
</main>