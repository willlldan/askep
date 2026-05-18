<?php
require_once "koneksi.php";
require_once "utils.php";

$form_id       = 20;
$level         = $_SESSION['level'];
$user_id       = $_SESSION['id_user'];
$section_name  = 'resume_keperawatan';
$section_label = 'Format Resume Keperawatan Poli Anak';

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
    // 1. Biodata Klien
    'nama_anak'                     => $_POST['nama_anak'] ?? '',
    'jenis_kelamin'                 => $_POST['jenis_kelamin'] ?? '',
    'umur'                          => $_POST['umur'] ?? '',
    'agama'                         => $_POST['agama'] ?? '',
    'status'                        => $_POST['status'] ?? '',
    'pendidikan'                     => $_POST['pendidikan'] ?? '',
    'pekerjaan'                     => $_POST['pekerjaan'] ?? '',
    'alamat'                        => $_POST['alamat'] ?? '',
    'kunjungan'                      => $_POST['kunjungan'] ?? '',

    // 2. Diagnosa Medis
    'diagnosa_medis'                => $_POST['diagnosa_medis'] ?? '',

    // 3. Keluhan Utama & Riwayat
    'keluhan_utama'                 => $_POST['keluhan_utama'] ?? '',
    'riwayat_keluhan_saat_ini'      => $_POST['riwayat_keluhan_saat_ini'] ?? '',

    // 4. Tanda Vital
    'tekanan_darah'                 => $_POST['tekanan_darah'] ?? '',
    'nadi'                          => $_POST['nadi'] ?? '',
    'suhu'                          => $_POST['suhu'] ?? '',
    'pernapasan'                    => $_POST['pernapasan'] ?? '',

    // 5. Pemeriksaan Antropometri
    'tb'                            => $_POST['tb'] ?? '',
    'bb'                            => $_POST['bb'] ?? '',
    'IMT'                           => $_POST['IMT'] ?? '',

    // 6. Pemeriksaan Fisik
    'pemeriksaan_fisik'             => $_POST['pemeriksaan_fisik'] ?? '',

    // 7. Riwayat Kesehatan yang Lalu
    'riwayat_kesehatan_yang_lalu'  => $_POST['riwayat_kesehatan_yang_lalu'] ?? '',

    // 8. Pemeriksaan Penunjang
    'pemeriksaan'                   => $_POST['pemeriksaan'] ?? '',

    // 9. Terapi / Obat
    'terapi'                        => $_POST['terapi'] ?? '',
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

    <?php include "keperawatan/dasar/tab.php"; ?>

    <section class="section dashboard">

        <?php include dirname(__DIR__, 2) . '/partials/notifikasi.php'; ?>
        <?php include dirname(__DIR__, 2) . '/partials/status_section.php'; ?>
        <div class="card mt-3">
            <div class="card-body">
                <form class="needs-validation" novalidate action="" method="POST">

                    <div class="row mb-3 mt-3">
                        <label class="col-sm-2 col-form-label"><strong>Tanggal Pengkajian</strong></label>
                        <div class="col-sm-10">
                            <input type="date" class="form-control" name="tglpengkajian"
                                value="<?= htmlspecialchars($tgl_pengkajian) ?>" <?= $ro ?> required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>RS/Ruangan</strong></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="rsruangan"
                                value="<?= htmlspecialchars($rs_ruangan) ?>" <?= $ro ?> required>
                        </div>
                    </div>

                                
        <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

            <h5 class="card-title"><strong>Format Resume Keperawatan</strong></h5>


           <!-- 1. Biodata Klien -->
            <div class="row mb-2">
                <label class="col-sm-12 text-primary"><strong>1. Biodata Klien</strong></label>
            </div>

            <!-- Nama Anak -->
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label"><strong>Nama Anak</strong></label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="nama_anak"
                    value="<?= val('nama_anak', $existing_data) ?>" <?= $ro ?>></div>
            </div>

            <!-- JENIS KELAMIN -->
            <div class="row mb-3">
                <label for="jenis_kelamin" class="col-sm-2 col-form-label"><strong>Jenis Kelamin</strong></label>

                <div class="col-sm-10">
                    <select class="form-select" name="jenis_kelamin" <?= $ro_select ?>>
                        <option value="">Pilih</option>
                        <option value="Laki-laki" <?= val('jenis_kelamin', $existing_data) === 'Laki-laki' ? 'selected' : '' ?>>Laki-laki</option>
                        <option value="Perempuan" <?= val('jenis_kelamin', $existing_data) === 'Perempuan' ? 'selected' : '' ?>>Perempuan</option>
                                        
                    </select>
                         </div>
                    </div>

<!-- Umur -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Umur</strong></label>
    <div class="col-sm-10">
        <input type="text" class="form-control" name="umur"
        value="<?= val('umur', $existing_data) ?>" <?= $ro ?>></div>
</div>

        <!-- Agama -->
        <div class="row mb-3">
            <label class="col-sm-2 col-form-label"><strong>Agama</strong></label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="agama"
                value="<?= val('agama', $existing_data) ?>" <?= $ro ?>></div>
            <div class="col-sm-1 d-flex align-items-start">
        </div>
        </div>
<!-- Status Perkawinan -->
        <div class="row mb-3">
            <label class="col-sm-2 col-form-label"><strong>Status Perkawinan</strong></label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="status"
                value="<?= val('status', $existing_data) ?>" <?= $ro ?>></div>
            <div class="col-sm-1 d-flex align-items-start">
        </div>
        </div>
        <!-- pendidikan -->
        <div class="row mb-3">
            <label class="col-sm-2 col-form-label"><strong>Pendidikan</strong></label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="pendidikan"
                value="<?= val('pendidikan', $existing_data) ?>" <?= $ro ?>></div>
            <div class="col-sm-1 d-flex align-items-start">
        </div>
        </div>
        <!-- Pekerjaan -->
        <div class="row mb-3">
            <label class="col-sm-2 col-form-label"><strong>Pekerjaan</strong></label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="pekerjaan"
                value="<?= val('pekerjaan', $existing_data) ?>" <?= $ro ?>></div>
            <div class="col-sm-1 d-flex align-items-start">
        </div>
        </div>
<!-- Alamat -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Alamat</strong></label>
    <div class="col-sm-10">
        <textarea name="alamat" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
        <?= $ro ?>><?= val('alamat',$existing_data) ?></textarea></div>
</div>
<div class="row mb-3">
            <label class="col-sm-2 col-form-label"><strong>Kunjungan Ke</strong></label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="kunjungan"
                value="<?= val('kunjungan', $existing_data) ?>" <?= $ro ?>></div>
            <div class="col-sm-1 d-flex align-items-start">
        </div>
        </div>

<!-- Diagnosa Medis -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Diagnosa Medis</strong></label>
    <div class="col-sm-10">
        <textarea name="diagnosa_medis" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
        <?= $ro ?>><?= val('diagnosa_medis',$existing_data) ?></textarea></div>
</div>

<!-- 3. Keluhan Utama -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label text-primary"><strong>2. Keluhan Utama</strong></label>
    <div class="col-sm-10">
        <textarea name="keluhan_utama" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
        <?= $ro ?>><?= val('keluhan_utama',$existing_data) ?></textarea></div>
</div>

<!-- 4. Riwayat Keluhan Utama -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label text-primary"><strong>3.	Riwayat Kesehatan Saat ini</strong></label>
    <div class="col-sm-10">
        <textarea name="riwayat_keluhan_saat_ini" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
        <?= $ro ?>><?= val('riwayat_keluhan_saat_ini',$existing_data) ?></textarea></div>
</div>
<div class="row mb-3">
                       <label class="col-sm-9 col-form-label text-primary">
                           <strong>4. Tanda-tanda Vital</strong>
                       </label>
                   </div>

                   <!-- Tekanan Darah -->
                   <div class="row mb-3 align-items-center">
                       <label class="col-sm-2 col-form-label"><strong>Tekanan Darah</strong></label>
                       <div class="col-sm-4">
                           <div class="input-group">
                               <input type="text" class="form-control" name="tekanan_darah" value="<?= val('tekanan_darah', $existing_data) ?>" <?= $ro ?>>
                           </div>
                       </div>

                       <!-- Nadi -->
                       <label class="col-sm-2 col-form-label"><strong>Frekuensi Nadi</strong></label>
                       <div class="col-sm-4">
                           <div class="input-group">
                               <input type="text" class="form-control" name="nadi" value="<?= val('nadi', $existing_data) ?>" <?= $ro ?>>
                           </div>
                       </div>

                   </div>

                   <!-- Suhu -->
                   <div class="row mb-3 align-items-center">
                       <label class="col-sm-2 col-form-label"><strong>Suhu Tubuh</strong></label>
                       <div class="col-sm-4">
                           <div class="input-group">
                               <input type="text" class="form-control" name="suhu" value="<?= val('suhu', $existing_data) ?>" <?= $ro ?>>
                           </div>
                       </div>

                       <!-- RR -->
                       <label class="col-sm-2 col-form-label"><strong>Frekuensi Pernapasan</strong></label>
                       <div class="col-sm-4">
                           <div class="input-group">
                               <input type="text" class="form-control" name="pernapasan" value="<?= val('pernapasan', $existing_data) ?>" <?= $ro ?>>
                           </div>
                       </div>
                   </div>
<div class="row mb-3">
                       <label class="col-sm-9 col-form-label text-primary">
                           <strong>5.	Pemeriksaan Antropometri </strong>
                       </label>
                   </div>

                   <!-- TB -->
                   <div class="row mb-3 align-items-center">
                       <label class="col-sm-2 col-form-label"><strong>Tinggi Badan</strong></label>
                       <div class="col-sm-4">
                           <div class="input-group">
                               <input type="text" class="form-control" name="tb" value="<?= val('tb', $existing_data) ?>" <?= $ro ?>>
                           </div>
                       </div>

                       <!-- BB -->
                       <label class="col-sm-2 col-form-label"><strong>Berat Badan</strong></label>
                       <div class="col-sm-4">
                           <div class="input-group">
                               <input type="text" class="form-control" name="bb" value="<?= val('bb', $existing_data) ?>" <?= $ro ?>>
                           </div>
                       </div>

                   </div>

                   <!-- IMT -->
                   <div class="row mb-3 align-items-center">
                       <label class="col-sm-2 col-form-label"><strong>IMT</strong></label>
                       <div class="col-sm-4">
                           <div class="input-group">
                               <input type="text" class="form-control" name="IMT" value="<?= val('IMT', $existing_data) ?>" <?= $ro ?>>
                           </div>
                       </div>

 <!-- 6. Pemeriksaan Fisik -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label text-primary"><strong>6. Pemeriksaan Fisik</strong></label>
    <div class="col-sm-10">
        <small class="form-text text-danger">(secara umum dan singkat)</small>
        <textarea name="pemeriksaan_fisik" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
        <?= $ro ?>><?= val('pemeriksaan_fisik',$existing_data) ?></textarea></div>
</div>
                   
<!-- 7. Riwayat Kesehatan yang Lalu -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label text-primary"><strong>7. Riwayat Kesehatan yang Lalu</strong></label>
    <div class="col-sm-10">
        <textarea name="riwayat_kesehatan_yang_lalu" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
        <?= $ro ?>><?= val('riwayat_kesehatan_yang_lalu',$existing_data) ?></textarea></div>
</div>
<!-- 8. Pemeriksaan Penunjang -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label text-primary"><strong>8. Pemeriksaan Penunjang</strong></label>
    <div class="col-sm-10">
       <textarea name="pemeriksaan" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
        <?= $ro ?>><?= val('pemeriksaan',$existing_data) ?></textarea></div>
</div>
<!-- 9. Terapi/obat -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label text-primary"><strong>9. Terapi/obat</strong></label>
    <div class="col-sm-10">
       <textarea name="terapi" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
        <?= $ro ?>><?= val('terapi',$existing_data) ?></textarea></div>
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

        <?php include dirname(__DIR__, 2) . '/partials/footer_form.php'; ?>

       

    </section>
</main>

<script>
    const existingData = <?= json_encode($existing_data) ?>;
</script>