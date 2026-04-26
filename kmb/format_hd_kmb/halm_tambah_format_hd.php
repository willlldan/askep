<?php
require_once "koneksi.php";
require_once "utils.php";

$form_id       = 17;
$level         = $_SESSION['level'];
$user_id       = $_SESSION['id_user'];
$section_name  = 'format_hermodalisa';
$section_label = 'Format Hermodalisa';

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
// $tgl_pengkajian = $submission['tanggal_pengkajian'] ?? '';
// $rs_ruangan     = $submission['rs_ruangan'] ?? '';
// Load existing dynamic rows

$existing_lab  = $existing_data['lab']  ?? [];
$existing_pemeriksaan = $existing_data['pemeriksaan'] ?? [];
// =============================================
// HANDLE POST - MAHASISWA SIMPAN DATA
// =============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $level === 'Mahasiswa') {

    if (isLocked($submission)) {
        redirectWithMessage($_SERVER['REQUEST_URI'], 'error', 'Data tidak dapat diubah karena sedang dalam proses review.');
    }

    // $tgl_pengkajian = $_POST['tglpengkajian'] ?? '';
    // $rs_ruangan     = $_POST['rsruangan'] ?? '';
        // Proses dynamic rows lab
    $lab = [];
    if (!empty($_POST['lab'])) {
        foreach ($_POST['lab'] as $index => $row) {
            if (empty($row['tanggal_pemeriksaan']) && empty($row['nama_pemeriksaan']) && empty($row['hasil']) && empty($row['satuan']) && empty($row['nilai_rujukkan'])) {
                continue;
            }
            $lab[] = [
                'tanggal_pemeriksaan'  => $row['tanggal_pemeriksaan'] ?? '',
                'nama_pemeriksaan'        => $row['nama_pemeriksaan'] ?? '',
                'hasil' => $row['hasil']  ?? '',
                'satuan' => $row['satuan']  ?? '',
                'nilai_rujukkan' => $row['nilai_rujukkan']  ?? '',
            ];
        }
    } 
     // Proses dynamic rows pemeriksaan
    $pemeriksaan = [];
    if (!empty($_POST['pemeriksaan'])) {
        foreach ($_POST['pemeriksaan'] as $index => $row) {
            if (empty($row['jam']) && empty($row['td']) && empty($row['nadi'])) {
                continue;
            }
            $pemeriksaan[] = [
                'jam'     => $row['jam']     ?? '',
                'td'          => $row['td']           ?? '',
                'nadi'       => $row['nadi']        ?? '',
                'qb' => $row['qb']  ?? '',
                'tmp' => $row['tmp']  ?? '',
                'teka' => $row['teka']  ?? '',
                 'tekv' => $row['tekv']  ?? '',
                 'hp' => $row['hp']  ?? '',
            ];
        }
    }


    $data = [
        'pemeriksaan' => $pemeriksaan,
        'lab'  => $lab,
         'nama_mahasiswa'            => $_POST['nama_mahasiswa'] ?? '',
            'nim'                       => $_POST['nim'] ?? '',
            'kelompok'                  => $_POST['kelompok'] ?? '',
            'tempat_dinas'              => $_POST['tempat_dinas'] ?? '',
            'nama_klien'                => $_POST['nama_klien'] ?? '',
            'umur'                      => $_POST['umur'] ?? '',
            'pekerjaan'                 => $_POST['pekerjaan'] ?? '',
            'agama'                     => $_POST['agama'] ?? '',
            'diagnosa_medis'            => $_POST['diagnosa_medis'] ?? '',
            'tgl_pertama_hd'            => $_POST['tgl_pertama_hd'] ?? '',
            'tgl_operasi'               => $_POST['tgl_operasi'] ?? '',
            'pukul_mulai'               => $_POST['pukul_mulai'] ?? '',
            'pukul_selesai'             => $_POST['pukul_selesai'] ?? '',
            'status_emosional'          => $_POST['status_emosional'] ?? '',
            'riwayat_komplikasi'        => $_POST['riwayat_komplikasi'] ?? '',
            'lingkungan'                => $_POST['lingkungan'] ?? '',
            'mesin_hd'                  => $_POST['mesin_hd'] ?? '',
            'pengukuran'                => $_POST['pengukuran'] ?? '',
            'tekanandarah'              => $_POST['tekanandarah'] ?? '',
            'nadi'                      => $_POST['nadi'] ?? '',
            'suhu'                      => $_POST['suhu'] ?? '',
            'rr'                        => $_POST['rr'] ?? '',
            'alat'                      => $_POST['alat'] ?? '',
            'kelainan_mata'             => $_POST['kelainan_mata'] ?? '',
            'pre'                       => $_POST['pre'] ?? '',
            'kelainan_pre'             => $_POST['kelainan_pre'] ?? '',
            'pos'                       => $_POST['pos'] ?? '',
            'kelainan_pos'              => $_POST['kelainan_pos'] ?? '',
            'observasi'                 => $_POST['observasi'] ?? '',
            'kelainan_observasi'        => $_POST['kelainan_observasi'] ?? '',
            'respon'                    => $_POST['respon'] ?? '',
            'kelainan'                  => $_POST['kelainan'] ?? '',
            'health_education'          => $_POST['health_education'] ?? '',
            'hd'                        => $_POST['hd'] ?? '',
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

    

    <section class="section dashboard">
        <div class="card">
            <div class="card-body">
              <h5 class="card-title mb-1"><strong>Format Hermodalisa (HD)</strong></h5>
              <form class="needs-validation" novalidate action="" method="POST">

<!-- NAMA MAHASISWA -->
 <div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>Nama Mahasiswa</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="nama_mahasiswa" value="<?= val('nama_mahasiswa', $existing_data) ?>" <?= $ro ?>>
     
        </div>
        </div>

<div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>NIM</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="nim" value="<?= val('nim', $existing_data) ?>" <?= $ro ?>>
    
        </div>
        </div>


<div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>Kelompok</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="kelompok" value="<?= val('kelompok', $existing_data) ?>" <?= $ro ?>>
     
        </div>
        </div>


<div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>Tempat Dinas</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="tempat_dinas" value="<?= val('tempat_dinas', $existing_data) ?>" <?= $ro ?>>
    
        </div>
        </div>


<!-- A IDENTITAS KLIEN -->
   <div class="row mb-2">
                <label class="col-sm-12 text-primary">
                    <strong>A. IDENTITAS KLIEN</strong>
                </label>
            </div>
 <div class="row mb-3">
                <label class="col-sm-2 col-form-label"><strong>Nama (inisial)</strong></label>

                <div class="col-sm-9">
                    <input type="text" class="form-control" name="nama_klien" value="<?= val('nama_klien', $existing_data) ?>" <?= $ro ?>>

              
        </div>
        </div>


            <!-- UMUR -->
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label"><strong>Umur</strong></label>

                <div class="col-sm-9">
                    <input type="number" class="form-control" name="umur" value="<?= val('djj', $existing_data) ?>" <?= $ro ?>>

                  
                </div>
            </div>

            <!-- PEKERJAAN -->
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label"><strong>Pekerjaan</strong></label>

                <div class="col-sm-9">
                    <input type="text" class="form-control" name="pekerjaan" value="<?= val('pekerjaan', $existing_data) ?>" <?= $ro ?>>

                    
                </div>
            </div>

            <!-- AGAMA -->
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label"><strong>Agama</strong></label>

                <div class="col-sm-9">
                    <input type="text" class="form-control" name="agama" value="<?= val('agama', $existing_data) ?>" <?= $ro ?>>

                    
                </div>
            </div>

     

            <!-- DIAGNOSA MEDIS -->
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label"><strong>Diagnosa Medis</strong></label>

                <div class="col-sm-9">
                    <input type="text" class="form-control" name="diagnosa_medis" value="<?= val('diagnosa_medis', $existing_data) ?>" <?= $ro ?>>

                   
                         </div>
                    </div>



       <!-- TGL MASUK RS -->
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label"><strong>Tanggal Pertama HD</strong></label>

                <div class="col-sm-9">
                    <input type="date" class="form-control" name="tgl_pertama_hd" value="<?= val('tgl_pertama_hd', $existing_data) ?>" <?= $ro ?>> 

              
                         </div>
                    </div>

<div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>HD ke berapa</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="hd" value="<?= val('hd', $existing_data) ?>" <?= $ro ?>>

        </div>
        </div>

<div class="row mb-3">
                <label class="col-sm-2 col-form-label"><strong>Waktu Operasi</strong></label>

                <div class="col-sm-9">
                    <div class="row">
                        <div class="col-md-4">
                            <input type="date" class="form-control" name="tgl_operasi" value="<?= val('tgl_operasi', $existing_data) ?>" <?= $ro ?>>
                        </div>
                        <div class="col-md-4">
                            <input type="time" class="form-control" name="pukul_mulai" value="<?= val('pukul_mulai', $existing_data) ?>" <?= $ro ?>>
                        </div>
                        <div class="col-md-4">
                            <input type="time" class="form-control" name="pukul_selesai" value="<?= val('pukul_selesai', $existing_data) ?>" <?= $ro ?>>
                        </div>
                    </div>

                  
                         </div>
                    </div>
<!-- C TINDAKAN -->
<div class="row mb-2">
<label class="col-sm-12 text-primary"><strong>B.Status Emosional Klien dan Keluarga</strong></label>
</div>
<div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>Status Emosional Klien dan Keluarga</strong>
    </div>
    <div class="col-sm-9">
    <textarea name="status_emosional" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" 
    <?= $ro ?>><?= val('status_emosional', $existing_data) ?></textarea>

        </div>
        </div>

<!-- C TINDAKAN -->
<div class="row mb-2">
<label class="col-sm-12 text-primary"><strong>C.Riwayat komplikasi HD Sebelumnya (Narasikan komplikasi yang di alami pasien pada HD sebelumnya)</strong></label>
</div>
<div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>C.Riwayat komplikasi HD Sebelumnya (Narasikan komplikasi yang di alami pasien pada HD sebelumnya)</strong>
    </div>
    <div class="col-sm-9">
    <textarea name="riwayat_komplikasi" class="form-control" rows="8" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
    <?= $ro ?>><?= val('riwayat_komplikasi', $existing_data) ?></textarea>

        </div>
        </div>



            <!-- ===================== TABEL LAB ===================== -->
                    <p class="text-primary fw-bold mb-2">D.	Nilai Laboratorium Terakhir</p>
                    <table class="table table-bordered" id="tabel-lab">
                        <thead>
                            <tr>
                                <th class="text-center" style="width:40px">No</th>
                                <th class="text-center">Tanggal pemeriksaan</th>
                                <th class="text-center">Nama Pemeriksaan</th>
                                <th class="text-center">Hasil</th>
                                <th class="text-center">Satuan</th>
                                <th class="text-center">Nilai Rujukan</th>
                                <th class="text-center" style="width:60px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="tbody-lab">
                            <!-- Dynamic rows masuk sini -->
                        </tbody>
                    </table>
                    <div class="row mb-4">
                        <div class="col-sm-12 d-flex justify-content-end">
                            <button type="button" class="btn btn-primary btn-sm" id="btn-tambah-lab" onclick="tambahRowLab()">+ Tambah Pemeriksaan</button>
                        </div>
                    </div>


<div class="row mb-2">
<label class="col-sm-12 text-primary"><strong>E.	Persiapan </strong></label>
</div>
<div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>1. Lingkungan</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="lingkungan" value="<?= val('lingkungan', $existing_data) ?>" <?= $ro ?>>
      
        </div>
        </div>

<div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>2. Mesin HD</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="mesin_hd" value="<?= val('mesin_hd', $existing_data) ?>" <?= $ro ?>>
        
        </div>
        </div>

<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>3. Klien  </strong></label>
</div>

<div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>a. Pengukuran Berat Badan</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="pengukuran" value="<?= val('pengukuran', $existing_data) ?>" <?= $ro ?>>
        
        </div>
        </div>

<div class="row mb-3">
                        <label class="col-sm-9 col-form-label">
                            <strong>TTV</strong>
                        </label>    
                    </div>

                    <!-- Tekanan Darah -->
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-2 col-form-label"><strong>TD</strong></label>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="text" class="form-control" name="tekanandarah" value="<?= val('tekanandarah', $existing_data) ?>" <?= $ro ?>>
                                <span class="input-group-text">mmHg</span>
                        </div>    
                    </div>
                                
                    <!-- Nadi -->
                    <label class="col-sm-2 col-form-label"><strong>N</strong></label>
                    <div class="col-sm-3">
                        <div class="input-group">
                                <input type="text" class="form-control" name="nadi" value="<?= val('nadi', $existing_data) ?>" <?= $ro ?>>
                                <span class="input-group-text">x/menit</span>
                        </div> 
                    </div>
                    </div>

                   
              
                <!-- Suhu -->
                <div class="row mb-3 align-items-center">
                    <label class="col-sm-2 col-form-label"><strong>S</strong></label>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="text" class="form-control" name="suhu" value="<?= val('suhu', $existing_data) ?>" <?= $ro ?>>
                                <span class="input-group-text">°C</span>
                        </div>    
                    </div>

                <!-- RR -->
                <label class="col-sm-2 col-form-label"><strong>RR</strong></label>
                    <div class="col-sm-3">
                        <div class="input-group">
                            <input type="text" class="form-control" name="rr" value="<?= val('rr', $existing_data) ?>" <?= $ro ?>>
                            <span class="input-group-text">x/menit</span>
                        </div>
                    </div>
                    </div>

                
                    <div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>4.	Alat</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="alat" value="<?= val('alat', $existing_data) ?>" <?= $ro ?>>
    
        </div>
        </div>

<div class="row mb-2">
<label class="col-sm-12 text-primary"><strong>F. Prosedur Kerja</strong></label>
</div>
<p>(Tuliskan suatu tindakan yang diberikan mulai dari persiapan sampai selesai melakukan HD)</p>
<div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>Kelainan</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="kelainan_mata" value="<?= val('kelainan_mata', $existing_data) ?>" <?= $ro ?>>
       
        </div>
        </div>
<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>1.	Pre HD</strong></label>

<div class="col-sm-9">
<input type="text" class="form-control" name="pre" value="<?= val('pre', $existing_data) ?>" <?= $ro ?>>


</div>
</div>
<div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>Kelainan</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="kelainan_pre" value="<?= val('kelainan_pre', $existing_data) ?>" <?= $ro ?>>
    
        </div>
        </div>
<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>2.	Post HD</strong></label>

<div class="col-sm-9">
<input type="text" class="form-control" name="pos" value="<?= val('pos', $existing_data) ?>" <?= $ro ?>>


</div>
</div>
<div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>Kelainan</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="kelainan_pos" value="<?= val('kelainan_pos', $existing_data) ?>" <?= $ro ?>>
       
        </div>
        </div>
<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>3.	Observasi</strong></label>

<div class="col-sm-9">
<input type="text" class="form-control" name="observasi" value="<?= val('observasi', $existing_data) ?>" <?= $ro ?>>


</div>
</div>
<div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>Kelainan</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="kelainan_observasi" value="<?= val('djj', $existing_data) ?>" <?= $ro ?>>
      
        </div>
        </div>
<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>4.	Respon terhadap tindakan HD</strong></label>

<div class="col-sm-9">
<input type="text" class="form-control" name="respon" value="<?= val('respon', $existing_data) ?>" <?= $ro ?>>
 
</div>
</div>
<div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>Kelainan</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="kelainan" value="<?= val('kelainan', $existing_data) ?>" <?= $ro ?>>
    
        </div>
        </div>
<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>5.	Hasil yang diperoleh</strong></label>

             <!-- ===================== TABEL Pemeriksaan===================== -->
                    <p class="text-primary fw-bold mb-2">Hasil Pemeriksaan Penunjang dan Laboratorium</p>
                    <table class="table table-bordered" id="tabel-Pemeriksaan">
                        <thead>
                            <tr>
                                <th class="text-center" style="width:40px">No</th>
                                <th class="text-center">Jam</th>
                                <th class="text-center">TD</th>
                                <th class="text-center">Nadi</th>
                                <th class="text-center">Qb</th>
                                <th class="text-center">TMP</th>
                                <th class="text-center">Tek. A</th>
                                <th class="text-center">Tek. V</th>
                                <th class="text-center">Hp</th>
                                <th class="text-center" style="width:60px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="tbody-Pemeriksaan">
                            <!-- Dynamic rows masuk sini -->
                        </tbody>
                    </table>
                    <div class="row mb-4">
                        <div class="col-sm-12 d-flex justify-content-end">
                            <button type="button" class="btn btn-primary btn-sm" id="btn-tambah-Pemeriksaan" onclick="tambahRowPemeriksaan()">+ Tambah Pemeriksaan</button>
                        </div>
                    </div>

                        </div>
                    
<div class="row mb-2">
<label class="col-sm-12 text-primary"><strong>G.	Health Education (HE) yang diberikan sebelum meninggalkan HD:</strong></label>
</div>
<div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>Health Education (HE) yang diberikan sebelum meninggalkan HD</strong>
    </div>
    <div class="col-sm-9">
        <textarea name="health_education" class="form-control" rows="7" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
        <?= $ro ?>><?= val('health_education', $existing_data) ?></textarea>

      
        </div>
        </div>


</div>
        </div>
         <script>
                        let rowPemeriksaanCount = 1;
                        let rowLabCount  = 1;
                        const existingPemeriksaan = <?= json_encode($existing_pemeriksaan) ?>;
                        const existingLab  = <?= json_encode($existing_lab) ?>;
                        const isReadonly = <?= json_encode($is_readonly) ?>;
                        // ---- pemeriksaan ----
                        function tambahRowPemeriksaan(data = null) {
                            const tbody = document.getElementById('tbody-Pemeriksaan');
                            const index = rowPemeriksaanCount;
                            const row   = document.createElement('tr');
                            row.innerHTML = `
                                <td class="text-center align-middle">${index}</td>
                                <td><input type="text" class="form-control form-control-sm" name="pemeriksaan[${index}][jam]" value="${data?.jam ?? ''}" ${isReadonly ? 'readonly' : ''}></td>
                                <td><input type="text" class="form-control form-control-sm" name="pemeriksaan[${index}][td]" value="${data?.td ?? ''}" ${isReadonly ? 'readonly' : ''}></td>
                                <td><input type="text" class="form-control form-control-sm" name="pemeriksaan[${index}][nadi]" value="${data?.nadi ?? ''}" ${isReadonly ? 'readonly' : ''}></td>
                                <td><input type="text" class="form-control form-control-sm" name="pemeriksaan[${index}][qb]" value="${data?.qb ?? ''}" ${isReadonly ? 'readonly' : ''}></td>
                                <td><input type="text" class="form-control form-control-sm" name="pemeriksaan[${index}][tmp]" value="${data?.tmp ?? ''}" ${isReadonly ? 'readonly' : ''}></td>
                                <td><input type="text" class="form-control form-control-sm" name="pemeriksaan[${index}][teka]" value="${data?.teka ?? ''}" ${isReadonly ? 'readonly' : ''}></td>
                                <td><input type="text" class="form-control form-control-sm" name="pemeriksaan[${index}][tekv]" value="${data?.tekv ?? ''}" ${isReadonly ? 'readonly' : ''}></td>
                                <td><input type="text" class="form-control form-control-sm" name="pemeriksaan[${index}][hp]" value="${data?.hp ?? ''}" ${isReadonly ? 'readonly' : ''}></td>
                                <td class="text-center align-middle">
                                    <button type="button" class="btn btn-danger btn-sm" onclick="hapusRow(this)" ${isReadonly ? 'disabled' : ''}>x</button>
                                </td>
                            `;
                            tbody.appendChild(row);
                            rowPemeriksaanCount++;
                        }
                        // ---- LAB ----
                        function tambahRowLab(data = null) {
                            const tbody = document.getElementById('tbody-lab');
                            const index = rowLabCount;
                            const row   = document.createElement('tr');
                            row.innerHTML = `
                                <td class="text-center align-middle">${index}</td>
                                <td><input type="text" class="form-control form-control-sm" name="lab[${index}][tanggal_pemeriksaan]" value="${data?.tanggal_pemeriksaan ?? ''}" ${isReadonly ? 'readonly' : ''}></td>
                                <td><input type="text" class="form-control form-control-sm" name="lab[${index}][nama_pemeriksaan]" value="${data?.nama_pemeriksaan ?? ''}" ${isReadonly ? 'readonly' : ''}></td>
                                <td><input type="text" class="form-control form-control-sm" name="lab[${index}][hasil]" value="${data?.hasil ?? ''}" ${isReadonly ? 'readonly' : ''}></td>
                                <td><input type="text" class="form-control form-control-sm" name="lab[${index}][satuan]" value="${data?.satuan ?? ''}" ${isReadonly ? 'readonly' : ''}></td>
                                <td><input type="text" class="form-control form-control-sm" name="lab[${index}][nilai_rujukkan]" value="${data?.nilai_rujukkan ?? ''}" ${isReadonly ? 'readonly' : ''}></td>
                                <td class="text-center align-middle">
                                    <button type="button" class="btn btn-danger btn-sm" onclick="hapusRow(this)" ${isReadonly ? 'disabled' : ''}>x</button>
                                </td>
                            `;
                            tbody.appendChild(row);
                            rowLabCount++;
                        }
                        function hapusRow(btn) {
                            btn.closest('tr').remove();
                        }
                        // Load existing rows on page load
                        window.addEventListener('load', function () {
                            if (existingPemeriksaan && existingPemeriksaan.length > 0) {
                                existingPemeriksaan.forEach(row => tambahRowPemeriksaan(row));
                            } else {
                                tambahRowPemeriksaan(); // default 1 row kosong
                            }
                            if (existingLab && existingLab.length > 0) {
                                existingLab.forEach(row => tambahRowLab(row));
                            } else {
                                tambahRowLab(); // default 1 row kosong
                            }
                            // Disable add buttons if readonly
                            if (isReadonly) {
                                document.getElementById('btn-tambah-pemeriksaan').setAttribute('disabled', 'disabled');
                                document.getElementById('btn-tambah-lab').setAttribute('disabled', 'disabled');
                            }
                        });
                        const existingData = <?= json_encode($existing_data) ?>;
                    </script>
       

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
                 