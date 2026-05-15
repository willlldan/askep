<?php
require_once "koneksi.php";
require_once "utils.php";

$form_id       = 20;
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

    // =========================
    // A. IDENTITAS KLIEN
    // =========================
    'nama_klien'        => $_POST['nama_klien'] ?? '',
    'ttl_umur'          => $_POST['ttl_umur'] ?? '',
    'jenis_kelamin'     => $_POST['jenis_kelamin'] ?? '',
    'status_perkawinan' => $_POST['status_perkawinan'] ?? '',
    'agama'             => $_POST['agama'] ?? '',
    'pendidikan'        => $_POST['pendidikan'] ?? '',
    'alamat'            => $_POST['alamat'] ?? '',
    'tgl_masuk_rs'      => $_POST['tgl_masuk_rs'] ?? '',
    'diagnosa_medis'    => $_POST['diagnosa_medis'] ?? '',
    'tgl_pengkajian1'   => $_POST['tgl_pengkajian1'] ?? '',
    'golongan_darah'    => $_POST['golongan_darah'] ?? '',
    'no_registrasi'     => $_POST['no_registrasi'] ?? '',
    'ruangan'           => $_POST['ruangan'] ?? '',


    // =========================
    // B. IDENTITAS PENANGGUNG JAWAB
    // =========================
    'nama_klienpj'      => $_POST['nama_klienpj'] ?? '',
    'ttl_umurpj'        => $_POST['ttl_umurpj'] ?? '',
    'jenis_kelaminpj'   => $_POST['jenis_kelaminpj'] ?? '',
    'hubungan_klien'    => $_POST['hubungan_klien'] ?? '',
    'agamapj'           => $_POST['agamapj'] ?? '',
    'pendidikanpj'      => $_POST['pendidikanpj'] ?? '',
    'pekerjaanpj'       => $_POST['pekerjaanpj'] ?? '',
    'alamatpj'          => $_POST['alamatpj'] ?? '',


    // =========================
    // C. KEADAAN UMUM
    // =========================

    // Tanda Vital
    'nadi'              => $_POST['nadi'] ?? '',
    'pernafasan'        => $_POST['pernafasan'] ?? '',
    'td'                => $_POST['td'] ?? '',
    'suhu'              => $_POST['suhu'] ?? '',

    // Glasgow Coma Scale (GCS)
    'm'                 => $_POST['m'] ?? '',
    'v'                 => $_POST['v'] ?? '',
    'e'                 => $_POST['e'] ?? '',



    // Antropometri
    'bb_sebelum'        => $_POST['bb_sebelum'] ?? '',
    'bb_saat_sakit'     => $_POST['bb_saat_sakit'] ?? '',
    'lingkar_lengan'    => $_POST['lingkar_lengan'] ?? '',
    'tinggi_badan'      => $_POST['tinggi_badan'] ?? '',
    'imt'               => $_POST['imt'] ?? '',


    // =========================
    // D. RIWAYAT KESEHATAN
    // =========================
    'alasan_masuk_rs'           => $_POST['alasan_masuk_rs'] ?? '',
    'keluhan_utama'             => $_POST['keluhan_utama'] ?? '',
    'riwayat_penyakit_sekarang' => $_POST['riwayat_penyakit_sekarang'] ?? '',
    'riwayat_pernah_dialami'    => $_POST['riwayat_pernah_dialami'] ?? '',
    'riwayat_kesehatan_keluarga'=> $_POST['riwayat_kesehatan_keluarga'] ?? '',
    'genogram'                  => $_POST['genogram'] ?? '',
    'kesadaran'                  => isset($_POST['kesadaran']) ? (array)$_POST['kesadaran'] : [],

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
$kesadaran_checked = isset($existing_data['kesadaran'])
    ? (array)$existing_data['kesadaran']
    : [];
// Readonly jika mahasiswa + locked, atau jika dosen
$is_dosen    = $level === 'Dosen';
$is_readonly = $is_dosen || isLocked($submission);
$ro          = $is_readonly ? 'readonly' : '';
$ro_select   = $is_readonly ? 'disabled' : '';
$ro_check    = $is_readonly ? 'disabled' : '';
?>

<main id="main" class="main">
    <?php include "keperawatan/dasar/tab.php"; ?>
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
              <h5 class="card-title mb-1"><strong>1. Pengumpulan Data</strong></h5>
                   <!-- General Form Elements -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data ">
              

    <div class="row mb-3">
        <label class="col-sm-2 col-form-label text-primary"><strong>a. Identitas Klien </strong></label>
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
        <label for="diagnosa_medis" class="col-sm-2 col-form-label"><strong>Diagnosa Medis</strong></label>
        <div class="col-sm-9">
            <input type="text" class="form-control" name="diagnosa_medis" value="<?= val('diagnosa_medis', $existing_data) ?>" <?= $ro ?>>
      
        </div>
    </div>

    <div class="row mb-3">
        <label for="tgl_pengkajian" class="col-sm-2 col-form-label"><strong>Tanggal Pengkajian</strong></label>
        <div class="col-sm-9"> 
            <input type="date" class="form-control" name="tgl_pengkajian1" value="<?= val('tgl_pengkajian1', $existing_data) ?>" <?= $ro ?>>
       
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
        <label for="ruangan" class="col-sm-2 col-form-label"><strong>Ruang Perawatan</strong></label>
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

        <div class="card">
             <div class="card-body">

<!-- A TANDA VITAL -->
 <div class="row mb-2">
<label class="col-sm-12 text-primary"><strong>c. Keadaan Umum</strong></label>
</div>
<div class="row mb-2">
<label class="col-sm-12"><strong>Tanda Vital</strong></label>
</div>

<!-- TD -->
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
    <label class="col-sm-2 col-form-label"><strong>Tekanan Darah</strong></label>

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
<label class="col-sm-12"><strong>Kesadaran</strong></label>
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
                        <label class="col-sm-2 col-form-label"><strong>Tingkat Kesadaran</strong></label>
                        <div class="col-sm-9">
                            <?php
                            $kesadaran_options = ['Kompos Mentis', 'Apatis', 'Somnolent', 'Stupor / Suppor', 'Semikoma', 'Koma'];
                            $kesadaran_values  = ['Kompos Mentis', 'Apatis', 'Somnolent', 'Stupor', 'Semikoma', 'Koma'];
                            foreach ($kesadaran_options as $i => $label):
                                $val = $kesadaran_values[$i];
                            ?>
                                <div class="form-check-inline">
                                    <input class="form-check-input" type="checkbox"
                                        name="kesadaran[]"
                                        id="kesadaran_<?= $i ?>"
                                        value="<?= $val ?>"
                                        <?= in_array($val, $kesadaran_checked) ? 'checked' : '' ?>
                                        <?= $ro_check ?>>
                                    <label class="form-check-label" for="kesadaran_<?= $i ?>"><?= $label ?></label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="row mb-2">
<label class="col-sm-12"><strong>Antropomentri</strong></label>
</div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>BB Sebelum Sakit</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="bb_sebelum"
                                value="<?= htmlspecialchars($existing_data['bb_sebelum'] ?? '') ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>BB Saat Sakit</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="bb_saat_sakit"
                                value="<?= htmlspecialchars($existing_data['bb_saat_sakit'] ?? '') ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Lingkar Lengan Atas</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="lingkar_lengan"
                                value="<?= htmlspecialchars($existing_data['lingkar_lengan'] ?? '') ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Tinggi Badan</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="tinggi_badan"
                                value="<?= htmlspecialchars($existing_data['tinggi_badan'] ?? '') ?>" <?= $ro ?>>
                        </div>
                    </div>
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Indeks Massa Tubuh (IMT)</strong></label>

    <div class="col-sm-9">
        <div class="input-group">
            <input type="text" class="form-control" name="imt" value="<?= val('imt', $existing_data) ?>" <?= $ro ?>>
            <span class="input-group-text">Kg/m2</span>
        </div>
    </div>
    </div>


             

<!-- A ALASAN MASUK RS -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label">
        <strong>Alasan Masuk Rumah Sakit</strong>
    </label>

    <div class="col-sm-9">
        <textarea class="form-control" rows="3"   name="alasan_masuk_rs" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
        <?= $ro ?>><?= val('alasan_masuk_rs', $existing_data) ?></textarea>

       
    </div>
</div>

<!-- B KELUHAN UTAMA -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label">
        <strong>Keluhan Utama</strong>
    </label>

    <div class="col-sm-9">
        <textarea class="form-control" rows="3"  name="keluhan_utama" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
        <?= $ro ?>><?= val('keluhan_utama', $existing_data) ?></textarea>

        
    </div>
</div>

<!-- C RIWAYAT KELUHAN UTAMA -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label">
        <strong>Riwayat Penyakit Sekarang</strong>
    </label>

    <div class="col-sm-9">
        <textarea class="form-control" rows="4" 
        name="riwayat_penyakit_sekarang"
        style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
        <?= $ro ?>><?= val('riwayat_penyakit_sekarang', $existing_data) ?></textarea>

        
    </div>
</div>

<!-- D RIWAYAT KESEHATAN YANG LALU -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label">
        <strong>Riwayat dan Kecelakaan Yang Pernah Dialami</strong>
    </label>

    <div class="col-sm-9">
        <textarea class="form-control" rows="4" name="riwayat_pernah_dialami"
        style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
        <?= $ro ?>><?= val('riwayat_pernah_dialami', $existing_data) ?></textarea>

        
    </div>
</div>

<!-- E RIWAYAT KESEHATAN KELUARGA -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label">
        <strong>Riwayat Kesehatan Keluarga</strong>
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
        <textarea class="form-control" rows="2" name="genogram"
        style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
        <?= $ro ?>><?= val('genogram', $existing_data) ?></textarea>

       
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
                
                 