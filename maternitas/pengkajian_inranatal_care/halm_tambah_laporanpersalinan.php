<?php
require_once "koneksi.php";
require_once "utils.php";

$form_id       = 5;
$level         = $_SESSION['level'];
$user_id       = $_SESSION['id_user'];
$section_name  = 'laporan_persalinan';
$section_label = 'Laporan Persalinan';

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
// Load existing dynamic rows
$existing_vt = $existing_data['vt'] ?? [];
$existing_his  = $existing_data['his']  ?? [];
$existing_klasifikasi = $existing_data['klasifikasi'] ?? [];
$existing_analisa     = $existing_data['analisa'] ?? [];
// =============================================
// HANDLE POST - MAHASISWA SIMPAN DATA
// =============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $level === 'Mahasiswa') {
    if (isLocked($submission)) {
        redirectWithMessage($_SERVER['REQUEST_URI'], 'error', 'Data tidak dapat diubah karena sedang dalam proses review.');
    }

    // Proses dynamic rows vt
    $vt = [];
    if (!empty($_POST['vt'])) {
        foreach ($_POST['vt'] as $index => $row) {
            if (empty($row['pemeriksaaan']) && empty($row['jam']) && empty($row['hasil'])) {
                continue;
            }
            $vt[] = [
                'pemeriksaaan'     => $row['pemeriksaaan']     ?? '',
                'jam'              => $row['jam']           ?? '',
                'hasil'            => $row['hasil']        ?? '',
                
            ];
        }
    }

    // Proses dynamic rows his
    $his = [];
    if (!empty($_POST['his'])) {
        foreach ($_POST['his'] as $index => $row) {
            if (empty($row['tanggal']) && empty($row['kontraksiuterus']) && empty($row['djj'])&& empty($row['nilai'])) {
                continue;
            }
            $his[] = [
                'tanggal'                => $row['tanggal']  ?? '',
                'kontraksiuterus'        => $row['kontraksiuterus']  ?? '',
                'djj'                    => $row['djj']  ?? '',
                'nilai'                  => $row['nilai']  ?? '',
            ];
        }
    }

     // KLASIFIKASI
    $klasifikasi = [];
    if (!empty($_POST['klasifikasi'])) {
        foreach ($_POST['klasifikasi'] as $row) {
            if (empty($row['data_subjektif']) && empty($row['data_objektif'])) {
                continue;
            }
            $klasifikasi[] = [
                'data_subjektif' => $row['data_subjektif'] ?? '',
                'data_objektif'  => $row['data_objektif'] ?? '',
            ];
        }
    }

    // ANALISA
    $analisa = [];
    if (!empty($_POST['analisa'])) {
        foreach ($_POST['analisa'] as $row) {
            if (empty($row['ds_do']) && empty($row['etiologi']) && empty($row['masalah'])) {
                continue;
            }
            $analisa[] = [
                'ds_do'    => $row['ds_do'] ?? '',
                'etiologi' => $row['etiologi'] ?? '',
                'masalah'  => $row['masalah'] ?? '',
            ];
        }
    }

    $tgl_pengkajian = $_POST['tglpengkajian'] ?? '';
    $rs_ruangan     = $_POST['rsruangan'] ?? '';

    $data = [
        'vt'                                => $vt,
        'his'                               => $his,
        'klasifikasi'                       => $klasifikasi,
        'analisa'                           => $analisa,
        'mulai_persalinan_dan_akhir'        => $_POST['mulaipersalinandanakhir'] ?? '',
        'tanda_dan_gejala1'                  => $_POST['tandadangejala1'] ?? '',
        'tekanan_darah'                     => $_POST['tekanandarah'] ?? '',
        'nadi'                              => $_POST['nadi'] ?? '',
        'suhu'                              => $_POST['suhu'] ?? '',
        'rr'                                => $_POST['rr'] ?? '',
        'keluhan_lain'                      => $_POST['keluhanlain'] ?? '',
        'lama_kalai'                        => $_POST['lamakalai'] ?? '',
        'tindakan_khusus'                   => $_POST['tindakankhusus'] ?? '',
        'pemeriksaan_ke'                    => $_POST['pemeriksaanke'] ?? '',
        'jam'                               => $_POST['jam'] ?? '',
        'hasil'                             => $_POST['hasil'] ?? '',
        'observasi'                         => $_POST['observasi'] ?? '',
        'tanggal_jam'                       => $_POST['tanggaljam'] ?? '',
        'kontraksi_uterus'                  => $_POST['kontraksiuterus'] ?? '',
        'keterangan'                        => $_POST['keterangan'] ?? '',
        'mulai'                             => $_POST['mulaipersalinandanakhir1'] ?? '',
        'tekanan_darah2'                    => $_POST['tekanandarah2'] ?? '',
        'nadi2'                             => $_POST['nadi2'] ?? '',
        'suhu2'                             => $_POST['suhu2'] ?? '',
        'rr2'                               => $_POST['rr2'] ?? '',
        'tanda_dan_gejalaII'                => $_POST['tandadangejalaII'] ?? '',
        'keluhan_tambahan'                  => $_POST['keluhantambahan'] ?? '',
        'tanda_mengejan'                    => $_POST['tandamengejan'] ?? '',
        'kebutuhan_atau_keluhan'            => $_POST['kebutuhanataukeluhan'] ?? '',
        'tanda_dan_gejala'                  => $_POST['tandadangejala'] ?? '',
        'lahir_jam_berapa'                  => $_POST['lahirjamberapa'] ?? '',
        'nilai_apgar'                       => $_POST['nilaiapgar'] ?? '',
        'nilai_apgarv'                      => $_POST['nilaiapgarv'] ?? '',
        'bonding_ibu_dan_bayi'              => $_POST['bondingibudanbayi'] ?? '',
        'pengobatan'                        => $_POST['pengobatan'] ?? '',

        'placenta'                           => $_POST['placenta'] ?? '',
        'tanda_dan_gejalaiii'                => $_POST['tandadangejalaiii'] ?? '',
        'keluhan_lain1'                       => $_POST['keluhanlain1'] ?? '',
        'panjang_tali_pusat'                 => $_POST['panjangtalipusat'] ?? '',
        'jumlah_pengeluaran_darah'           => $_POST['jumlahpengeluarandarah'] ?? '',
        'karakteristik_darah'                => $_POST['karakteristikdarah'] ?? '',
        'tindakan_kebutuhan_khusus'          => $_POST['tindakankebutuhankhusus'] ?? '',
        'pengobatan1'                         => $_POST['pengobatan1'] ?? '',
        'kalaiv'                             => $_POST['kalaiv'] ?? '',
        'tekanandarah4'                      => $_POST['tekanandarah4'] ?? '',
        'nadi4'                              => $_POST['nadi4'] ?? '',
        'suhu4'                              => $_POST['suhu4'] ?? '',
        'rr4'                                => $_POST['rr4'] ?? '',
        'tanda_dan_gejalaiv'                 => $_POST['tandadangejalaiv'] ?? '',
        'keluhan'                            => $_POST['keluhan'] ?? '',
        'jumlah_pengeluaran_darah1'           => $_POST['jumlahpengeluarandarah1'] ?? '',
        'karakteristik_darah1'                => $_POST['karakteristikdarah1'] ?? '',
        'bonding_ibu_dan_bayi1'              => $_POST['bondingibudanbayi1'] ?? '',
        'tindakan_kebutuhankhusus1'           => $_POST['tindakankebutuhankhusus1'] ?? '',
        'ukuran'           => $_POST['ukuran'] ?? '',
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
            
                <!-- General Form Elements -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

              <h5 class="card-title mb-1"><strong>Persalinan Kala I</strong></h5>

                <!-- Bagian Persalinan Kala I -->
                    
                    <!-- Mulai Persalinan dan Akhir -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Persalinan Kala I</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Mulai Persalinan dan Akhir (Tuliskan tanggal dan Jam). Hasil:</small>
                            <textarea name="mulaipersalinandanakhir" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('mulai_persalinan_dan_akhir', $existing_data) ?></textarea></textarea>
                         </div>
                    </div>        

                    <!-- Tanda dan Gejala -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Tanda dan Gejala</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Tanda dan Gejala (Keluhan mules-mules, ada darah keluar dan lendir tapi baru sedikit melalui kemaluan). Hasil:</small>
                            <textarea name="tandadangejala1" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('tanda_dan_gejala1', $existing_data) ?></textarea>
                         </div>
                    </div>
                    
                    <!-- Bagian Tanda-tanda Vital -->

                    <div class="row mb-3">
                        <label class="col-sm-9 col-form-label">
                            <strong>Tanda-tanda Vital</strong>
                        </label>    
                    </div>

                    <!-- Tekanan Darah -->
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-2 col-form-label"><strong>Tekanan Darah</strong></label>
                        <div class="col-sm-4">
                            <div class="input-group">
                                <input type="text" class="form-control" name="tekanandarah" value="<?= val('tekanan_darah', $existing_data) ?>" <?= $ro ?>>
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
                
                <!-- Keluhan Lain -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Keluhan Lain</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Keluhan lainnya yang dirasakan (nyeri, cemas). Hasil:</small>
                            <textarea name="keluhanlain" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('keluhan_lain', $existing_data) ?></textarea>
                         </div>
                    </div>

                <!-- Lama Kala I -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Lama Kala I</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">(Tuliskan berapa lama dalam hitungan jam dan menitnya). Hasil:</small>
                            <textarea name="lamakalai" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('lama_kalai', $existing_data) ?></textarea>
                         </div>
                    </div>

                    <!-- Tindakan Khusus -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Tindakan Khusus</strong></label>

                        <div class="col-sm-10">
                            <textarea name="tindakankhusus" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('tindakan_khusus', $existing_data) ?></textarea>
                         </div>
                    </div>
                     <!-- ===================== TABEL LAB ===================== -->
                    <p class="text-primary fw-bold mb-2">Pemeriksaan Dalam (VT)</p>
                    <table class="table table-bordered" id="tabel-vt">
                        <thead>
                            <tr>
                                <th class="text-center" style="width:40px">No</th>
                                <th class="text-center">Pemeriksaan Ke</th>
                                <th class="text-center">Jam</th>
                                <th class="text-center">Hasil Normal</th>
                                <th class="text-center" style="width:60px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="tbody-vt">
                            <!-- Dynamic rows masuk sini -->
                        </tbody>
                    </table>
                    <div class="row mb-4">
                        <div class="col-sm-12 d-flex justify-content-end">
                            <button type="button" class="btn btn-primary btn-sm" id="btn-tambah-vt" onclick="tambahRowVt()">+ Tambah Pemeriksaan</button>
                        </div>
                    </div>


                                   
                    <!-- Bagian Observasi  -->
                
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Observasi</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Observasi kemajuan persalinan menggunakan patograf</small>
                            <textarea name="observasi" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('observasi', $existing_data) ?></textarea>
                         </div>
                    </div> 
                     <!-- ===================== TABEL LAB ===================== -->
                    <p class="text-primary fw-bold mb-2">Pementauan HIS</p>
                    <table class="table table-bordered" id="tabel-his">
                        <thead>
                            <tr>
                                <th class="text-center" style="width:40px">No</th>
                                <th class="text-center">Tanggal/Jam</th>
                                <th class="text-center">kontraksiuterus</th>
                                <th class="text-center">Nilai DJJ</th>
                                <th class="text-center">Nilai keterangan</th>
                                <th class="text-center" style="width:60px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="tbody-his">
                            <!-- Dynamic rows masuk sini -->
                        </tbody>
                    </table>
                    <div class="row mb-4">
                        <div class="col-sm-12 d-flex justify-content-end">
                            <button type="button" class="btn btn-primary btn-sm" id="btn-tambah-his" onclick="tambahRowHis()">+ Tambah Pemeriksaan</button>
                        </div>
                    </div>

                
             </div>
        </div>    


                <div class="card">
            <div class="card-body">
              <h5 class="card-title mb-1"><strong>Persalinan Kala II</strong></h5>

                <!-- General Form Elements -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

                <!-- Bagian Persalinan Kala II -->
                    
                    <!-- Mulai Persalinan dan Akhir -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Persalinan Kala II</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Mulai dan berakhir kala II (Tuliskan jam berapa mulai masuk ke kala II). Hasil:</small>
                            <textarea name="mulaipersalinandanakhir1" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('mulai', $existing_data) ?></textarea>
                         </div>
                    </div>        

                    <!-- Bagian Tanda-tanda Vital -->

                    <div class="row mb-3">
                        <label class="col-sm-9 col-form-label">
                            <strong>Tanda-tanda Vital</strong>
                        </label>    
                    </div>

                    <!-- Tekanan Darah -->
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-2 col-form-label"><strong>Tekanan Darah</strong></label>
                        <div class="col-sm-4">
                            <div class="input-group">
                                <input type="text" class="form-control" name="tekanandarah2" value="<?= val('tekanan_darah2', $existing_data) ?>" <?= $ro ?>>
                                <span class="input-group-text">mmHg</span>
                        </div>    
                    </div>
                                
                    <!-- Nadi -->
                    <label class="col-sm-2 col-form-label"><strong>Nadi</strong></label>
                    <div class="col-sm-4">
                        <div class="input-group">
                                <input type="text" class="form-control" name="nadi2" value="<?= val('nadi2', $existing_data) ?>" <?= $ro ?>>
                                <span class="input-group-text">x/menit</span>
                        </div> 
                    </div>

                    </div>
              
                    <!-- Suhu -->
                        <div class="row mb-3 align-items-center">
                            <label class="col-sm-2 col-form-label"><strong>Suhu</strong></label>
                            <div class="col-sm-4">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="suhu2" value="<?= val('suhu2', $existing_data) ?>" <?= $ro ?>>
                                    <span class="input-group-text">°C</span>
                            </div>    
                        </div>

                    <!-- RR -->
                    <label class="col-sm-2 col-form-label"><strong>RR</strong></label>
                    <div class="col-sm-4">
                        <div class="input-group">
                                <input type="text" class="form-control" name="rr2" value="<?= val('rr2', $existing_data) ?>" <?= $ro ?>>
                                <span class="input-group-text">x/menit</span>
                            </div>
                        </div>
                    </div>
            
                <!-- Tanda dan Gejala Kala II -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Tanda dan Gejala Kala II</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">(Ibu merasa semakin sakit, keringat lebih banyak, merasa mules dan ingin BAB, HIS semakin sering dan meningkat,
                                terjadi pengeluaran pervagina semakin banyak, vulva membuka, perineum meregang, anus mengembang dan membentu huruf D). Hasil:</small>
                            <textarea name="tandadangejalaII" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('tanda_dan_gejalaII', $existing_data) ?></textarea></textarea>
                         </div>
                    </div>

                    <!-- Keluhan Tambahan -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Keluhan Tambahan</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">(pqrst). Hasil:</small>
                            <textarea name="keluhantambahan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('keluhan_tambahan', $existing_data) ?></textarea>
                         </div>
                    </div>

                    <!-- Jelaskan Tanda/Tata Cara Mengejan -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Jelaskan Tanda/Tata Cara Mengejan</strong></label>

                        <div class="col-sm-10">
                            <textarea name="tandamengejan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('tanda_mengejan', $existing_data) ?></textarea></textarea>
                         </div>
                    </div>

                    <!-- Kebutuhan atau Tindakan -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Kebutuhan atau tindakan khusus yang dilakukan</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Hasil:</small>
                            <textarea name="kebutuhanataukeluhan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('kebutuhan_atau_keluhan', $existing_data) ?></textarea></textarea>
                         </div>
                    </div>

                    <!-- Lama Kala II -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Lama Kala II</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">(Catat berapa lama kala II berlangsung). Hasil:</small>
                            <textarea name="tandadangejala" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('tanda_dan_gejala', $existing_data) ?></textarea>
                         </div>
                    </div>
                   
                    <!-- Bagian Catatan Kelahiran Bayi -->

                    <div class="row mb-2">
                        <label class="col-sm-3 col-form-label text-primary">
                            <strong>Catatan Kelahiran Bayi</strong>
                    </div>
                    
                    <!-- Jam  -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Bayi lahir jam berapa</strong></label>

                        <div class="col-sm-10">
                            <input type="datetime-local" class="form-control" name="lahirjamberapa" value="<?= val('lahir_jam_berapa', $existing_data) ?>" <?= $ro ?>>
                         </div>
                    </div> 

                    <!-- Bagian Nilai APGAR -->
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-2 col-form-label"><strong>Nilai APGAR Menit I</strong></label>
                        <div class="col-sm-4">
                            <div class="input-group">
                                <input type="text" class="form-control" name="nilaiapgar" value="<?= val('nilai_apgar', $existing_data) ?>" <?= $ro ?>>
                                <span class="input-group-text">menit</span>
                        </div>    
                    </div>
                                
                    <!-- V -->
                    <label class="col-sm-2 col-form-label"><strong>V</strong></label>
                    <div class="col-sm-4">
                        <div class="input-group">
                                <input type="text" class="form-control" name="nilaiapgarv" value="<?= val('nilai_apgarv', $existing_data) ?>" <?= $ro ?>>
                        </div>  
                    </div>    
                </div>

                    <!-- Bonding Ibu dan Bayi -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Bonding Ibu dan Bayi</strong></label>

                        <div class="col-sm-10">
                            <textarea name="bondingibudanbayi" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('bonding_ibu_dan_bayi', $existing_data) ?></textarea>
                         </div>
                    </div> 

                     <!-- Pengobatan -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Pengobatan</strong></label>

                            <div class="col-sm-10">
                                <textarea name="pengobatan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('pengobatan', $existing_data) ?></textarea></textarea>
                    </div> 
                </div>
            </div>  
        </div>

        <div class="card">
            <div class="card-body">
              <h5 class="card-title mb-1"><strong>Persalinan Kala III</strong></h5>

                <!-- General Form Elements -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

                <!-- Bagian Persalinan Kala III -->
                    
                    <!--Placenta -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Plancenta lahir jam berapa</strong></label>

                        <div class="col-sm-10">
                            <input type="time" class="form-control" name="placenta" value=" <?= val('placenta', $existing_data) ?>" <?= $ro ?>>
                         </div>
                    </div>        

                    <!-- Tanda dan Gejala III -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Tanda dan Gejala III</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">(Rahim membulat, lebih mengeras, keluar darah tiba-tiba, tali pusat menjulur keluar.
                                TFU setinggi pusat, kontraksi rahim baik, kandung kemih kosong, uterus nampak bulat dan keras) dan (Perhatikan
                                keluhan pusing, mual, pendarahan, robekan perineum dan kondisi psikologis). Hasil:</small>
                            <textarea name="tandadangejalaiii" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('tanda_dan_gejalaiii', $existing_data) ?></textarea>
                         </div>
                    </div>

                    <!-- Keluhan Lain yang dirasakan -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Keluhan Lain yang dirasakan</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Hasil:</small>
                            <textarea name="keluhanlain1" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('keluhan_lain1', $existing_data) ?></textarea>
                         </div>
                    </div>
                    
                    
                    <!-- Bagian Karakteristik Placenta -->

                    <div class="row mb-3">
                        <label class="col-sm-9 col-form-label">
                            <strong>Karakteristik Placenta</strong>
                        </label>    
                    </div>

                    <!-- Ukuran -->
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-2 col-form-label"><strong>Ukuran</strong></label>
                        <div class="col-sm-4">
                            <div class="input-group">
                                <input type="text" class="form-control" name="ukuran"value="<?= val('ukuran', $existing_data) ?>" <?= $ro ?>>
                        </div>    
                    </div>
                                
                    <!-- Panjang Tali Pusat -->
                    <label class="col-sm-2 col-form-label"><strong>Panjang Tali Pusat</strong></label>
                    <div class="col-sm-4">
                        <div class="input-group">
                                <input type="text" class="form-control" name="panjangtalipusat" value="<?= val('panjang_tali_pusat', $existing_data) ?>" <?= $ro ?>>
                        </div>  
                    </div>
                </div>

                    <!-- Bagian Jumlah Pengeluaran Darah -->
                 
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Jumlah Pengeluaran Darah</strong></label>

                        <div class="col-sm-10">
                            <div class="input-group">
                                <input type="text" class="form-control" name="jumlahpengeluarandarah" value="<?= val('jumlah_pengeluaran_darah', $existing_data) ?>" <?= $ro ?>>
                                <span class="input-group-text">ml</span>
                        </div>  
                         </div>
                    </div> 

                    <!-- Karakteristik Darah -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Karakteristik Darah</strong></label>

                        <div class="col-sm-10">
                            <textarea name="karakteristikdarah" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('karakteristik_darah', $existing_data) ?></textarea>
                         </div>
                    </div> 

                     <!-- Tindakan/Kebutuhan Khusus -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Tindakan/Kebutuhan Khusus</strong></label>

                            <div class="col-sm-10">
                                <small class="form-text" style="color: red;">Hasil:</small>
                                <textarea name="tindakankebutuhankhusus" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('tindakan_kebutuhan_khusus', $existing_data) ?></textarea></textarea>
                         </div>
                    </div> 

                    <!-- Pengobatan -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Pengobatan</strong></label>

                        <div class="col-sm-10">
                            <textarea name="pengobatan1" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('pengobatan1', $existing_data) ?></textarea>
                         </div>
                    </div>
                </div>
        </div>
                    
            <div class="card">
            <div class="card-body">
              <h5 class="card-title mb-1"><strong>Persalinan Kala IV</strong></h5>

                <!-- General Form Elements -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

                <!-- Bagian Persalinan Kala IV -->
                    
                    <!-- Mulai Persalinan dan Akhir -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Mulai Jam Berapa Masuk Kala IV</strong></label>

                        <div class="col-sm-10">
                            <input type="time" class="form-control" name="kalaiv" value="<?= val('kalaiv', $existing_data) ?>" <?= $ro ?>>
                         </div>
                    </div>        
                    
                    <!-- Bagian Tanda-tanda Vital -->

                    <div class="row mb-3">
                        <label class="col-sm-9 col-form-label">
                            <strong>Tanda-tanda Vital</strong>
                        </label>    
                    </div>

                    <!-- Tekanan Darah -->
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-2 col-form-label"><strong>Tekanan Darah</strong></label>
                        <div class="col-sm-4">
                            <div class="input-group">
                                <input type="text" class="form-control" name="tekanandarah4" value="<?= val('tekanandarah4', $existing_data) ?>" <?= $ro ?>>
                                <span class="input-group-text">mmHg</span>
                        </div>    
                    </div>
                                
                    <!-- Nadi -->
                    <label class="col-sm-2 col-form-label"><strong>Nadi</strong></label>
                    <div class="col-sm-4">
                        <div class="input-group">
                                <input type="text" class="form-control" name="nadi4" value="<?= val('nadi4', $existing_data) ?>" <?= $ro ?>>
                                <span class="input-group-text">x/menit</span>
                        </div> 
                    </div>

                    </div>
              
                    <!-- Suhu -->
                        <div class="row mb-3 align-items-center">
                            <label class="col-sm-2 col-form-label"><strong>Suhu</strong></label>
                            <div class="col-sm-4">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="suhu4" value="<?= val('suhu4', $existing_data) ?>" <?= $ro ?>>
                                    <span class="input-group-text">°C</span>
                            </div>    
                        </div>

                    <!-- RR -->
                    <label class="col-sm-2 col-form-label"><strong>RR</strong></label>
                    <div class="col-sm-4">
                        <div class="input-group">
                                <input type="text" class="form-control" name="rr4" value="<?= val('rr4', $existing_data) ?>" <?= $ro ?>>
                                <span class="input-group-text">x/menit</span>
                            </div>
                        </div>
                    </div>

                <!-- Tanda dan Gejala IV -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Tanda dan Gejala IV</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">(Observasi keadaan umum, keluhan pusing, mual, mata kunang-kunang, TTV,
                                kontraksi uterus, perdarahan (jumlah, warna, karakteristik, dan bau), pengosongan kandung kemih (setiap 15 menit pada 1 jam pertama dst),
                                periksa perineum, bersihkan ibu). Hasil:</small>
                            <textarea name="tandadangejalaiv" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('tanda_dan_gejalaiv', $existing_data) ?></textarea>
                         </div>
                    </div>
                    
                    <!-- Keluhan Lain yang dirasakan -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Keluhan Lain yang dirasakan</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Hasil:</small>
                            <textarea name="keluhan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('keluhan', $existing_data) ?></textarea>
                         </div>
                    </div> 

                    <!-- Bagian Jumlah Pengeluaran Darah -->
                 
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Jumlah Pengeluaran Darah</strong></label>

                        <div class="col-sm-10">
                            <div class="input-group">
                                <input type="text" class="form-control" name="jumlahpengeluarandarah1" value="<?= val('jumlah_pengeluaran_darah1', $existing_data) ?>" <?= $ro ?>>
                                <span class="input-group-text">ml</span>
                        </div>  
                         </div>
                    </div> 

                    <!-- Karakteristik Darah -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Karakteristik Darah</strong></label>

                        <div class="col-sm-10">
                            <textarea name="karakteristikdarah1" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('karakteristik_darah1', $existing_data) ?></textarea></textarea>
                         </div>
                    </div> 

                    <!-- Bonding Bayi dan Ibu -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Bonding Bayi dan Ibu</strong></label>

                        <div class="col-sm-10">
                            <textarea name="bondingibudanbayi1" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('bonding_ibu_dan_bayi1', $existing_data) ?></textarea></textarea>
                         </div>
                    </div> 

                    <!-- Tindakan/Kebutuhan Khusus -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Tindakan/Kebutuhan Khusus</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Hasil:</small>
                            <textarea name="tindakankebutuhankhusus1" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('tindakan_kebutuhankhusus1', $existing_data) ?></textarea>
                         </div>
                    </div> 

                 <!-- ===================== TABEL LAB ===================== -->
                    <p class="text-primary fw-bold mb-2">Klasifikasi Data</p>
                    <table class="table table-bordered" id="tabel-klasifikasi_data">
                        <thead>
                            <tr>
                                <th class="text-center" >No</th>
                                <th class="text-center" >Data Subjektif (DS)</th>
                                <th class="text-center">Data Objektif (DO)</th>
                                <th class="text-center" style="width:60px">Aksi</th>
                                
                            </tr>
                        </thead>
                        <tbody id="tbody-klasifikasi">
                            <!-- Dynamic rows masuk sini -->
                        </tbody>
                    </table>
                    <div class="row mb-4">
                        <div class="col-sm-12 d-flex justify-content-end">
                            <button type="button" class="btn btn-primary btn-sm" id="btn-tambah-klasifikasi" onclick="tambahRowKlasifikasi()">+ Tambah data</button>
                        </div>
                    </div>
                     <!-- ===================== TABEL LAB ===================== -->
                    <p class="text-primary fw-bold mb-2">Analisa Data</p>
                    <table class="table table-bordered" id="tabel-analisa">
                        <thead>
                            <tr>
                                <th class="text-center" style="width:40px">NO</th>
                                <th class="text-center">DS/DO</th>
                                <th class="text-center">Etiologi</th>
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
                            <button type="button" class="btn btn-primary btn-sm" id="btn-tambah-analisa" onclick="tambahRowAnalisa()">+ Tambah data</button>
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
<script>
                        let rowVtCount = 1;
                        let rowHisCount  = 1;
                         let rowKlasifikasiCount = 1;
                    let rowAnalisaCount     = 1;
                      
                        const existingVt = <?= json_encode($existing_vt) ?>;
                        const existingHis  = <?= json_encode($existing_his) ?>;
                         const existingKlasifikasi = <?= json_encode($existing_klasifikasi) ?>;
                    const existingAnalisa     = <?= json_encode($existing_analisa) ?>;
                       
                        const isReadonly = <?= json_encode($is_readonly) ?>;
                        // ---- VT ----
                        function tambahRowVt(data = null) {
                            const tbody = document.getElementById('tbody-vt');
                            const index = rowVtCount;
                            const row   = document.createElement('tr');
                            row.innerHTML = `
                                <td class="text-center align-middle">${index}</td>
                                <td><input type="text" class="form-control form-control-sm" name="vt[${index}][pemeriksaaan]" value="${data?.pemeriksaaan ?? ''}" ${isReadonly ? 'readonly' : ''}></td>
                                <td><input type="time" class="form-control form-control-sm" name="vt[${index}][jam]" value="${data?.jam ?? ''}" ${isReadonly ? 'readonly' : ''}></td>
                                <td><input type="text" class="form-control form-control-sm" name="vt[${index}][hasil]" value="${data?.hasil ?? ''}" ${isReadonly ? 'readonly' : ''}></td>
                                <td class="text-center align-middle">
                                    <button type="button" class="btn btn-danger btn-sm" onclick="hapusRow(this)" ${isReadonly ? 'disabled' : ''}>x</button>
                                </td>
                            `;
                            tbody.appendChild(row);
                            rowVtCount++;
                        }
                        // ---- HIS ----
                        function tambahRowHis(data = null) {
                            const tbody = document.getElementById('tbody-his');
                            const index = rowHisCount;
                            const row   = document.createElement('tr');
                            row.innerHTML = `
                                <td class="text-center align-middle">${index}</td>
                                <td><input type="datetime-local" class="form-control form-control-sm" name="his[${index}][tanggal]" value="${data?.tanggal ?? ''}" ${isReadonly ? 'readonly' : ''}></td>
                                <td><input type="text" class="form-control form-control-sm" name="his[${index}][kontraksiuterus]" value="${data?.kontraksiuterus ?? ''}" ${isReadonly ? 'readonly' : ''}></td>
                                <td><input type="text" class="form-control form-control-sm" name="his[${index}][djj]" value="${data?.djj ?? ''}" ${isReadonly ? 'readonly' : ''}></td>
                                <td><input type="text" class="form-control form-control-sm" name="his[${index}][nilai]" value="${data?.nilai ?? ''}" ${isReadonly ? 'readonly' : ''}></td>

                                <td class="text-center align-middle">
                                    <button type="button" class="btn btn-danger btn-sm" onclick="hapusRow(this)" ${isReadonly ? 'disabled' : ''}>x</button>
                                </td>
                            `;
                            tbody.appendChild(row);
                            rowHisCount++;
                        }
                         
                    // ---- KLASIFIKASI ----
                    function tambahRowKlasifikasi(data = null) {
                        const tbody = document.getElementById('tbody-klasifikasi');
                        const index = rowKlasifikasiCount++;
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td class="text-center">${index}</td>
                            <td><input type="text" name="klasifikasi[${index}][data_subjektif]" class="form-control" value="${data?.data_subjektif ?? ''}" ${isReadonly?'readonly':''}></td>
                            <td><input type="text" name="klasifikasi[${index}][data_objektif]" class="form-control" value="${data?.data_objektif ?? ''}" ${isReadonly?'readonly':''}></td>
                            <td><button type="button" class="btn btn-danger btn-sm" onclick="hapusRow(this)" ${isReadonly?'disabled':''}>x</button></td>
                        `;
                        tbody.appendChild(row);
                    }
                      // ---- ANALISA ----
                    function tambahRowAnalisa(data = null) {
                        const tbody = document.getElementById('tbody-analisa');
                        const index = rowAnalisaCount++;
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td class="text-center">${index}</td>
                            <td><input type="text" name="analisa[${index}][ds_do]" class="form-control" value="${data?.ds_do ?? ''}" ${isReadonly?'readonly':''}></td>
                            <td><input type="text" name="analisa[${index}][etiologi]" class="form-control" value="${data?.etiologi ?? ''}" ${isReadonly?'readonly':''}></td>
                            <td><input type="text" name="analisa[${index}][masalah]" class="form-control" value="${data?.masalah ?? ''}" ${isReadonly?'readonly':''}></td>
                            <td><button type="button" class="btn btn-danger btn-sm" onclick="hapusRow(this)" ${isReadonly?'disabled':''}>x</button></td>
                        `;
                        tbody.appendChild(row);
                    }
                     
                        function hapusRow(btn) {
                            btn.closest('tr').remove();
                        }
                        // Load existing rows on page load
                       window.addEventListener('load', () => {
    // VT
    if (existingVt && existingVt.length > 0) {
        existingVt.forEach(row => tambahRowVt(row));
    } else {
        tambahRowVt();
    }

    // HIS
    if (existingHis && existingHis.length > 0) {
        existingHis.forEach(row => tambahRowHis(row));
    } else {
        tambahRowHis();
    }

    // Klasifikasi
    if (existingKlasifikasi && existingKlasifikasi.length > 0) {
        existingKlasifikasi.forEach(row => tambahRowKlasifikasi(row));
    } else {
        tambahRowKlasifikasi();
    }

    // Analisa
    if (existingAnalisa && existingAnalisa.length > 0) {
        existingAnalisa.forEach(row => tambahRowAnalisa(row));
    } else {
        tambahRowAnalisa();
    }

    // Disable buttons jika readonly
    if (isReadonly) {
        document.getElementById('btn-tambah-vt').setAttribute('disabled', 'disabled');
        document.getElementById('btn-tambah-his').setAttribute('disabled', 'disabled');
        document.getElementById('btn-tambah-klasifikasi').setAttribute('disabled', 'disabled');
        document.getElementById('btn-tambah-analisa').setAttribute('disabled', 'disabled');
    }
});
                        const existingData = <?= json_encode($existing_data) ?>;
                        </script>
               


    
</main>
