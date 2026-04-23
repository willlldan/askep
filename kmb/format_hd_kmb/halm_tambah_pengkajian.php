<?php
require_once "koneksi.php";
require_once "utils.php";

$form_id       = 17;
$level         = $_SESSION['level'];
$user_id       = $_SESSION['id_user'];
$section_name  = 'pengkajian';
$section_label = 'Pengkajian';

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
 <section class="section dashboard">
        <div class="card">
            <div class="card-body">
              <h5 class="card-title mb-1"><strong>A. PENGKAJIAN</strong></h5>
                   <!-- General Form Elements -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data ">
              <h5 class="card-title mb-1"><strong>1. Identitas</strong></h5>

    <div class="row mb-3">
        <label class="col-sm-2 col-form-label text-primary"><strong>a. Klien </strong></label>
    </div>

    <div class="row mb-3">
        <label for= "nama_klien" class="col-sm-2 col-form-label"><strong> Nama (Inisial) </strong></label>
        <div class="col-sm-9">
            <input type="text" class="form-control" name="nama_klien" value="<?= val('nama_klien', $existing_data) ?>" <?= $ro ?>>
 
        </div>
    </div>

    <div class="row mb-3">
        <label for="ttl_umur" class="col-sm-2 col-form-label"><strong>Tempat/Tgl Lahir/Umur</strong></label>
        <div class="col-sm-9">
            <input type="text" class="form-control" name="ttl_umur" value="<?= val('ttl_umur', $existing_data) ?>" <?= $ro ?>>
  
        </div>
    </div>

    <div class="row mb-3">
        <label for="jenis_kelamin" class="col-sm-2 col-form-label"><strong>Jenis Kelamin</strong></label>
        <div class="col-sm-9">
            <select class="form-control" name="jenis_kelamin"<?= $ro_select ?>>
                <option value="">Pilih </option>
                <option value="Laki-laki" <?= val('jenis_kelamin', $existing_data) === 'Laki-laki' ? 'selected' : '' ?>>Laki-laki</option>
                <option value="Perempuan" <?= val('jenis_kelamin', $existing_data) === 'Perempuan' ? 'selected' : '' ?>>Perempuan</option>
            </select>
            
        </div>
    </div>
    <div class="row mb-3">
        <label for="status_perkawinan" class="col-sm-2 col-form-label"><strong>Status Perkawinan</strong></label>
        <div class="col-sm-9">
            <input type="text" class="form-control" name="status_perkawinan" value="<?= val('status_perkawinan', $existing_data) ?>" <?= $ro ?>>
      
        </div>
    </div>

    <div class="row mb-3">
        <label for="agama" class="col-sm-2 col-form-label"><strong>Agama</strong></label>
        <div class="col-sm-9">
            <input type="text" class="form-control" name="agama" value="<?= val('agama', $existing_data) ?>" <?= $ro ?>>
       
        </div>
    </div>

    <div class="row mb-3">
        <label for="pendidikan" class="col-sm-2 col-form-label"><strong>Pendidikan</strong></label>
        <div class="col-sm-9">
            <input type="text" class="form-control" name="pendidikan" value="<?= val('pendidikan', $existing_data) ?>" <?= $ro ?>>
        
        </div>
    </div>

    <div class="row mb-3">
        <label for="pekerjaan" class="col-sm-2 col-form-label"><strong>Pekerjaan</strong></label>
        <div class="col-sm-9">
            <input type="text" class="form-control" name="pekerjaan" value="<?= val('pekerjaan', $existing_data) ?>" <?= $ro ?>>
            
      
        </div>
    </div>
    <div class="row mb-3">
        <label for="pekerjaan" class="col-sm-2 col-form-label"><strong>Alamat</strong></label>
        <div class="col-sm-9">
            <input type="text" class="form-control" name="alamat" value="<?= val('alamat', $existing_data) ?>" <?= $ro ?>>
     
        </div>
    </div>

 
  

    <div class="row mb-3">
        <label for="tgl_masuk_rs" class="col-sm-2 col-form-label"><strong>Tanggal Masuk RS</strong></label>
        <div class="col-sm-9">
            <input type="date" class="form-control" name="tgl_masuk_rs" value="<?= val('tgl_masuk_rs', $existing_data) ?>" <?= $ro ?>>
      
        </div>
    </div>

    <div class="row mb-3">
        <label for="tgl_pengkajian" class="col-sm-2 col-form-label"><strong>Tanggal Pengkajian</strong></label>
        <div class="col-sm-9"> 
            <input type="date" class="form-control" name="tgl_pengkajian" value="<?= val('tgl_pengkajian', $existing_data) ?>" <?= $ro ?>>
       
        </div>
    </div>

    <div class="row mb-3">
        <label for="diagnosa_medik" class="col-sm-2 col-form-label"><strong>Diagnosa Medik</strong></label>
        <div class="col-sm-9">
            <input type="text" class="form-control" name="diagnosa_medik" value="<?= val('diagnosa_medik', $existing_data) ?>" <?= $ro ?>>
      
        </div>
    </div>

    <div class="row mb-3">
        <label for="golongan_darah" class="col-sm-2 col-form-label"><strong>Golongan Darah</strong></label>
        <div class="col-sm-9">
            <input type="text" class="form-control" name="golongan_darah" value="<?= val('golongan_darah', $existing_data) ?>" <?= $ro ?>>
        
        </div>
    </div>

    <div class="row mb-3">
        <label for="no_registrasi" class="col-sm-2 col-form-label"><strong>No Registrasi</strong></label>
        <div class="col-sm-9">
            <input type="text" class="form-control" name="no_registrasi" value="<?= val('no_registrasi', $existing_data) ?>" <?= $ro ?>>
       
        </div>
    </div>

    <div class="row mb-3">
        <label for="ruangan" class="col-sm-2 col-form-label"><strong>Ruangan</strong></label>
        <div class="col-sm-9">
            <input type="text" class="form-control" name="ruangan" value="<?= val('ruangan', $existing_data) ?>" <?= $ro ?>>
        
        </div>
    </div>


    <div class="row mb-2">
        <label class="col-sm-12 text-primary"><strong>b. Identitas Penanggung Jawab</strong></label>
    </div>

    <div class="row mb-3">
        <label for= "nama_klien" class="col-sm-2 col-form-label"><strong> Nama (Inisial) </strong></label>
        <div class="col-sm-9">
            <input type="text" class="form-control" name="nama_klienpj" value="<?= val('nama_klienpj', $existing_data) ?>" <?= $ro ?>>
   
        </div>
    </div>

    <div class="row mb-3">
        <label for="ttl_umur" class="col-sm-2 col-form-label"><strong>Tempat/Tgl Lahir/Umur</strong></label>
        <div class="col-sm-9">
            <input type="text" class="form-control" name="ttl_umurpj" value="<?= val('ttl_umurpj', $existing_data) ?>" <?= $ro ?>>
   
        </div>
    </div>

    <div class="row mb-3">
        <label for="jenis_kelamin" class="col-sm-2 col-form-label"><strong>Jenis Kelamin</strong></label>
        <div class="col-sm-9">
            <select class="form-control" name="jenis_kelaminpj"<?= $ro_select ?>>
                <option value="">Pilih </option>
                <option value="Laki-laki" <?= val('jenis_kelaminpj', $existing_data) === 'Laki-laki' ? 'selected' : '' ?>>Laki-laki</option>
                <option value="Perempuan" <?= val('jenis_kelaminpj', $existing_data) === 'Perempuan' ? 'selected' : '' ?>>Perempuan</option>
            </select>
            
        </div>
    </div>

    <div class="row mb-3">
        <label for="hubungan_klien" class="col-sm-2 col-form-label"><strong>Hubungan dengan Klien</strong></label>
        <div class="col-sm-9">
            <input type="text" class="form-control" name="hubungan_klien" value="<?= val('hubungan_klien', $existing_data) ?>" <?= $ro ?>">
      
        </div>
    </div>
<div class="row mb-3">
        <label for="agama" class="col-sm-2 col-form-label"><strong>Agama</strong></label>
        <div class="col-sm-9">
            <input type="text" class="form-control" name="agamapj" value="<?= val('agamapj', $existing_data) ?>" <?= $ro ?>>
       
        </div>
    </div>

    <div class="row mb-3">
        <label for="pendidikan" class="col-sm-2 col-form-label"><strong>Pendidikan</strong></label>
        <div class="col-sm-9">
            <input type="text" class="form-control" name="pendidikanpj" value="<?= val('pendidikanpj', $existing_data) ?>" <?= $ro ?>>
       
        </div>
    </div>

    <div class="row mb-3">
        <label for="pekerjaan" class="col-sm-2 col-form-label"><strong>Pekerjaan</strong></label>
        <div class="col-sm-9">
            <input type="text" class="form-control" name="pekerjaanpj" value="<?= val('pekerjaanpj', $existing_data) ?>" <?= $ro ?>>
      
    </div>
    </div>

    <div class="row mb-3">
        <label for="alamat" class="col-sm-2 col-form-label"><strong>Alamat</strong></label>
        <div class="col-sm-9">
            <textarea class="form-control" rows="3" name="alamatpj"style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" 
           <?= $ro ?>><?= val('alamatpj', $existing_data) ?></textarea>
        </div>
  
    </div>

        </div>
        </div>
        </section>
         <section class="section dashboard">
        <div class="card">
             <div class="card-body">
                                
<!-- 2 KEADAAN UMUM -->


                    <h5 class="card-title"><strong>2. Keadaan Umum</strong></h5>



<!-- A TANDA VITAL -->
<div class="row mb-2">
<label class="col-sm-12 text-primary"><strong>a. Tanda Vital</strong></label>
</div>
<div class="row mb-2">
<label class="col-sm-12 "><strong>Pre HD</strong></label>
</div>
<!-- TD -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Nadi</strong></label>

    <div class="col-sm-9">
        <div class="input-group">
            <input type="text" class="form-control" name="nadi_prehd" value="<?= val('nadi_prehd', $existing_data) ?>" <?= $ro ?>>
            <span class="input-group-text">/menit</span>
        </div>

     
        </div>
        </div>



<!-- Pernafasan -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Pernafasan</strong></label>

    <div class="col-sm-9">
        <div class="input-group">
            <input type="text" class="form-control" name="pernafasan_prehd" value="<?= val('pernafasan_prehd', $existing_data) ?>" <?= $ro ?>>
            <span class="input-group-text">x/menit</span>
        </div>

      
        </div>
        </div>
<!-- TD (Tekanan Darah) -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>TD (Tekanan Darah)</strong></label>

    <div class="col-sm-9">
        <div class="input-group">
            <input type="text" class="form-control" name="td_prehd" value="<?= val('td_prehd', $existing_data) ?>" <?= $ro ?>>
            <span class="input-group-text">mmHg</span>
        </div>

     
        </div>
        </div>

<!-- Suhu -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Suhu</strong></label>

    <div class="col-sm-9">
        <div class="input-group">
            <input type="text" class="form-control" name="suhu_prehd" value="<?= val('suhu_prehd', $existing_data) ?>" <?= $ro ?>>
            <span class="input-group-text">°C</span>
        </div>

       
        </div>
        </div>
<div class="row mb-2">
<label class="col-sm-12 "><strong>Post HD</strong></label>
</div>

<!-- Nadi -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Nadi</strong></label>

    <div class="col-sm-9">
        <div class="input-group">
            <input type="text" class="form-control" name="nadi" value="<?= val('nadi', $existing_data) ?>" <?= $ro ?>>
            <span class="input-group-text">/menit</span>
        </div>

        
        </div>
        </div>

<!-- Pernafasan -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Pernafasan</strong></label>

    <div class="col-sm-9">
        <div class="input-group">
            <input type="text" class="form-control" name="pernafasan" value="<?= val('pernafasan', $existing_data) ?>" <?= $ro ?>>
            <span class="input-group-text">x/menit</span>
        </div>

        
        </div>
        </div>

<!-- TD (Tekanan Darah) -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>TD (Tekanan Darah)</strong></label>

    <div class="col-sm-9">
        <div class="input-group">
            <input type="text" class="form-control" name="td" value="<?= val('td', $existing_data) ?>" <?= $ro ?>>
            <span class="input-group-text">mmHg</span>
        </div>

        
        </div>
        </div>

<!-- Suhu -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Suhu</strong></label>

    <div class="col-sm-9">
        <div class="input-group">
            <input type="text" class="form-control" name="suhu" value="<?= val('suhu', $existing_data) ?>" <?= $ro ?>>
            <span class="input-group-text">°C</span>
        </div>

        
        </div>
        </div>

<!-- B KESADARAN -->
<div class="row mb-2">
<label class="col-sm-12 text-primary"><strong>b. Kesadaran</strong></label>
</div>
<!-- GCS -->

                        <div class="row mb-3">

                        <label class="col-sm-2 col-form-label"><strong>Glasgow Coma Scale (GCS)</strong></label>
                            <div class="col-sm-9">
                                <div class="row">

                        <!-- E -->
                        <div class="col-md-4 d-flex align-items-center">
                            <label class="me-2"><strong>M</strong></label>
                            <input type="text" class="form-control" name="m" value="<?= val('m', $existing_data) ?>" <?= $ro ?>>
                        </div>

                        <!-- M -->
                        <div class="col-md-4 d-flex align-items-center">
                            <label class="me-2"><strong>V</strong></label>
                            <input type="text" class="form-control" name="v" value="<?= val('v', $existing_data) ?>" <?= $ro ?>>
                        </div>

                        <!-- V -->
                        <div class="col-md-4 d-flex align-items-center">
                            <label class="me-2"><strong>E</strong></label>
                            <input type="text" class="form-control" name="e" value="<?= val('e', $existing_data) ?>" <?= $ro ?>>
                        </div>
                    </div>

                       
                  
        </div>
        </div>
<div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>Tingkat Kesadaran</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="kesadaran" value="<?= val('kesadaran', $existing_data) ?>" <?= $ro ?>>
        
        </div>
    </div>




<!-- C ANTROPOMETRI -->
<div class="row mb-2">
<label class="col-sm-12 text-primary"><strong>Berat badan (BB)</strong></label>
</div>
<!-- BB Sebelum Sakit -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>BB HD sebelumnya</strong></label>

    <div class="col-sm-9">
        <input type="text" class="form-control" name="bb_hd" value="<?= val('bb_hd', $existing_data) ?>" <?= $ro ?>>

       
        </div>
        </div>
<div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>BB Pre HD (sebelum HD)</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="bb_prehd" value="<?= val('bb_prehd', $existing_data) ?>" <?= $ro ?>>
     
        </div>
        </div>


<div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>BB Post HD (setelah HD)</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="bb_posthd" value="<?= val('bb_posthd', $existing_data) ?>" <?= $ro ?>>
       
        </div>
        </div>


<div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>BB Kering</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="bbkering" value="<?= val('bbkering', $existing_data) ?>" <?= $ro ?>>
        
        </div>
        </div>


<div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>Kenaikan BB</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="kenaikanbb" value="<?= val('kenaikanbb', $existing_data) ?>" <?= $ro ?>>
        
        </div>
        </div>

        
       
        </div>
        </div>

<!-- 3 RIWAYAT KESEHATAN -->
 <div class="card">
             <div class="card-body">

                    <h5 class="card-title"><strong>3. Riwayat Kesehatan</strong></h5>


<!-- A ALASAN MASUK RS -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label">
        <strong>a. Alasan Masuk Rumah Sakit</strong>
    </label>

    <div class="col-sm-9">
        <textarea class="form-control" rows="3"   name="alasan_masuk_rs" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
        <?= $ro ?>><?= val('alasan_masuk_rs', $existing_data) ?></textarea>

       
    </div>
</div>

<!-- B KELUHAN UTAMA -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label">
        <strong>b. Keluhan Utama</strong>
    </label>

    <div class="col-sm-9">
        <textarea class="form-control" rows="3"  name="keluhan_utama" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
        <?= $ro ?>><?= val('keluhan_utama', $existing_data) ?></textarea>

        
    </div>
</div>

<!-- C RIWAYAT KELUHAN UTAMA -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label">
        <strong>c. Riwayat Keluhan Utama</strong>
    </label>

    <div class="col-sm-9">
        <textarea class="form-control" rows="4" 
        name="riwayat_keluhan_utama"
        style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
        <?= $ro ?>><?= val('riwayat_keluhan_utama', $existing_data) ?></textarea>

        
    </div>
</div>

<!-- D RIWAYAT KESEHATAN YANG LALU -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label">
        <strong>d. Riwayat Kesehatan yang Lalu</strong>
    </label>

    <div class="col-sm-9">

        <small class="form-text" style="color: red;">Bentuk kepala, Penyebaran, Kebersihan, Warna Rambut. Hasil:</small>

        <textarea class="form-control" rows="4" name="riwayat_kesehatan_lalu"
        style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
        <?= $ro ?>><?= val('riwayat_kesehatan_lalu', $existing_data) ?></textarea>

        
    </div>
</div>

<!-- E RIWAYAT KESEHATAN KELUARGA -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label">
        <strong>e. Riwayat Kesehatan Keluarga</strong>
    </label>

    <div class="col-sm-9">
        <textarea class="form-control" rows="4" name="riwayat_kesehatan_keluarga"
        style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
        <?= $ro ?>><?= val('riwayat_kesehatan_keluarga', $existing_data) ?></textarea>

       
    </div>
</div>
  <!-- Bagian Genogram -->
                <div class="row mb-3">
                    <label for="genogram" class="col-sm-2 col-form-label"><strong>Genogram</strong></label>
                    <div class="col-sm-9">

                        <!-- Link Google Drive -->
                         <div class="form-control d-flex justify-content-between align-items-center">
                            <span>Upload Gambar Genogram pada link Google Drive yang tersedia</span>
                            <a href="<?= $genogram ?>" target="_blank" class="btn btn-sm btn-primary" <?= $ro ?>><?= val('genogram', $existing_data) ?>Upload</a>
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
                
                 