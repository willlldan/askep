<?php
require_once "koneksi.php";
require_once "utils.php";

$form_id       = 7;
$level         = $_SESSION['level'];
$user_id       = $_SESSION['id_user'];
$section_name  = 'pengkajian';
$section_label = 'Format Pengkajian';

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

$existing_analisa     = $existing_data['analisa']     ?? [];

// Decode checkbox fields
$checkbox_fields = ['penampilan','pembicaraan','motorik','alam_perasaan','afek','interaksi_wawancara','persepsi_sensorik','ilusi','proses_pikir','isi_pikir','tingkat_kesadaran','memori','konsentrasi_berhitung',
    'kemampuan_penilaian','daya_tilik_diri','psikososial','pengetahuan'];
foreach ($checkbox_fields as $cf) {
    $existing_data[$cf] = isset($existing_data[$cf])
        ? (json_decode($existing_data[$cf], true) ?? [])
        : [];
}
// =============================================
// HANDLE POST - MAHASISWA SIMPAN DATA
// =============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $level === 'Mahasiswa') {

    if (isLocked($submission)) {
        redirectWithMessage($_SERVER['REQUEST_URI'], 'error', 'Data tidak dapat diubah karena sedang dalam proses review.');
    }

   // Proses dynamic rows evaluasi
         $analisa = [];
    if (!empty($_POST['analisa'])) {
        foreach ($_POST['analisa'] as $index => $row) {
            if (empty($row['data_subjektif_analisa']) && empty($row['data_objektif_analisa']) && empty($row['masalah'])) {
                continue;
            }
            $analisa[] = [
                'data_subjektif_analisa'   => $row['data_subjektif_analisa']   ?? '',
                'data_objektif_analisa' => $row['data_objektif_analisa'] ?? '',
                'masalah'  => $row['masalah']  ?? '',
            ];
        }
    }

    $data = [
    'ruang_rawat' => $_POST['ruang_rawat'],
    'tanggal_rawat' => $_POST['tanggal_rawat'],
    'nama_klien' => $_POST['nama_klien'],
    'jenis_kelamin' => $_POST['jenis_kelamin'],
    'tanggal_pengkajian' => $_POST['tanggal_pengkajian'],
    'umur' => $_POST['umur'],
    'rm' => $_POST['rm'],
    'informasi' => $_POST['informasi'],
    'alasanmasuk' => $_POST['alasanmasuk'],
    'gangguan_jiwa' => $_POST['gangguan_jiwa'],
    'pengobatan' => $_POST['pengobatan'],
    'aniaya_fisik_pelaku' => $_POST['aniaya_fisik_pelaku'],
    'aniaya_fisik_korban' => $_POST['aniaya_fisik_korban'],
    'aniaya_fisik_saksi' => $_POST['aniaya_fisik_saksi'],
    'aniaya_seksual_pelaku' => $_POST['aniaya_seksual_pelaku'],
    'aniaya_seksual_korban' => $_POST['aniaya_seksual_korban'],
    'aniaya_seksual_saksi' => $_POST['aniaya_seksual_saksi'],
    'penolakan_pelaku' => $_POST['penolakan_pelaku'],
    'penolakan_korban' => $_POST['penolakan_korban'],
    'penolakan_saksi' => $_POST['penolakan_saksi'],
    'kekerasan_keluarga_pelaku' => $_POST['kekerasan_keluarga_pelaku'],
    'kekerasan_keluarga_korban' => $_POST['kekerasan_keluarga_korban'],
    'kekerasan_keluarga_saksi' => $_POST['kekerasan_keluarga_saksi'],
    'tindakan_kriminal_pelaku' => $_POST['tindakan_kriminal_pelaku'],
    'tindakan_kriminal_korban' => $_POST['tindakan_kriminal_korban'],
    'tindakan_kriminal_saksi' => $_POST['tindakan_kriminal_saksi'],
    'penjelasan_kejadian' => $_POST['penjelasan_kejadian'],
    'gangguan_jiwa_keluarga' => $_POST['gangguan_jiwa_keluarga'],
    'pengalaman_masa_lalu' => $_POST['pengalaman_masa_lalu'],
    'genogram' => $_POST['genogram'],
    'gambaran_diri' => $_POST['gambaran_diri'],
    'identitas_diri' => $_POST['identitas_diri'],
    'peran' => $_POST['peran'],
    'ideal_diri' => $_POST['ideal_diri'],
    'harga_diri' => $_POST['harga_diri'],
    'orang_berarti' => $_POST['orang_berarti'],
    'kegiatan_kelompok' => $_POST['kegiatan_kelompok'],
    'hambatan_hubungan' => $_POST['hambatan_hubungan'],
    'nilai_keyakinan' => $_POST['nilai_keyakinan'],
    'kegiatan_ibadah' => $_POST['kegiatan_ibadah'],
    'psikososial' => isset($_POST['psikososial']) ? implode(',', $_POST['psikososial']) : null,
    'dukungan_kelompok' => $_POST['dukungan_kelompok'],
    'masalah_lingkungan' => $_POST['masalah_lingkungan'],
    'masalah_pendidikan' => $_POST['masalah_pendidikan'],
    'masalah_pekerjaan' => $_POST['masalah_pekerjaan'],
    'masalah_perumahan' => $_POST['masalah_perumahan'],
    'masalah_ekonomi' => $_POST['masalah_ekonomi'],
    'masalah_pelayanan_kesehatan' => $_POST['masalah_pelayanan_kesehatan'],
    'masalah_lain' => $_POST['masalah_lain'],
    'pengetahuan' => isset($_POST['pengetahuan']) ? implode(',', $_POST['pengetahuan']) : null,
    'penjelasan_status' => $_POST['penjelasan_status'],
    'Hubungan_keluarga1' => $_POST['Hubungan_keluarga1'],
    'Gejala' => $_POST['Gejala'],
    'Riwayat' => $_POST['Riwayat'],
    'Pengobatan_perawatan' => $_POST['Pengobatan_perawatan'],
    'td' => $_POST['td'],
    'nadi' => $_POST['nadi'],
    'suhu' => $_POST['suhu'],
    'pernafasan' => $_POST['pernafasan'],
    'tb' => $_POST['tb'],
    'bb' => $_POST['bb'],
    'keluhan_fisik' => $_POST['keluhan_fisik'],
    'penjelasan' => $_POST['penjelasan'],
    'persiapan_makan1' => $_POST['persiapan_makan1'],
    'bab1' => $_POST['bab1'],
    'mandi1' => $_POST['mandi1'],
    'berpakian1' => $_POST['berpakian1'],
    'tidur_siang' => $_POST['tidur_siang'],
    'tidur_siang_sampai' => $_POST['tidur_siang_sampai'],
    'tidur_malam' => $_POST['tidur_malam'],
    'tidur_malam_sampai' => $_POST['tidur_malam_sampai'],
    'tidur' => $_POST['tidur'],
    'obat' => $_POST['obat'],
    'perawatanlanjutan' => $_POST['perawatanlanjutan'],
    'perawatanpendukung1' => $_POST['perawatanpendukung1'],
    'memasak1' => $_POST['memasak1'],
    'menjaga_kerapian1' => $_POST['menjaga_kerapian1'],
    'mencuci_pakaian1' => $_POST['mencuci_pakaian1'],
    'pengaturan_keuangan1' => $_POST['pengaturan_keuangan1'],
    'belanja1' => $_POST['belanja1'],
    'transportasi1' => $_POST['transportasi1'],
    'lain_lain1' => $_POST['lain_lain1'],
];
$checkbox_fields = ['penampilan','pembicaraan','motorik','alam_perasaan','afek','interaksi_wawancara','persepsi_sensorik','ilusi','proses_pikir','isi_pikir','tingkat_kesadaran','memori','konsentrasi_berhitung',
    'kemampuan_penilaian','daya_tilik_diri','psikososial','pengetahuan'];
    foreach ($checkbox_fields as $cf) {
        $data[$cf] = json_encode(isset($_POST[$cf]) ? (array)$_POST[$cf] : []);
    }
    if (!$submission) {
        $submission_id = createSubmission($user_id, $form_id, null, null, $mysqli);
    } else {
        $submission_id = $submission['id'];
        updateSubmissionHeader($submission_id, null, null, $mysqli);
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
$ro_disabled   = $is_readonly ? 'disabled' : '';
?>

<main id="main" class="main">

    <?php include "jiwa/jiwa_rsud/tab.php"; ?>

    <section class="section dashboard">

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

<form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

<h5 class="card-title"><strong>FORMAT PENGKAJIAN KEPERAWATAN JIWA</strong></h5>
<!-- RUANG RAWAT -->
<div class="row mb-3">
  <label class="col-sm-2 col-form-label"><strong>Ruang Rawat</strong></label>
  <div class="col-sm-10">
    <input type="text" class="form-control" name="ruang_rawat" value="<?= val('ruang_rawat', $existing_data) ?>" <?= $ro ?>>
  </div>
</div>

<!-- TANGGAL RAWAT -->
<div class="row mb-3">
  <label class="col-sm-2 col-form-label"><strong>Tanggal Rawat</strong></label>
  <div class="col-sm-10">
    <input type="date" class="form-control" name="tanggal_rawat" value="<?= val('tanggal_rawat', $existing_data) ?>" <?= $ro ?>>
  </div>
</div>

 <div class="row mb-2">
                        <label class="col-sm-3 col-form-label text-primary">
                            <strong>I. IDENTITAS KLIEN</strong>
                    </div>
<!-- NAMA KLIEN -->
<div class="row mb-3">
  <label class="col-sm-2 col-form-label"><strong>Nama Klien</strong></label>
  <div class="col-sm-10">
    <input type="text" class="form-control" name="nama_klien" value="<?= val('nama_klien', $existing_data) ?>" <?= $ro ?>>
  </div>
</div>

<!-- JENIS KELAMIN -->
<div class="row mb-3">
  <label class="col-sm-2 col-form-label"><strong>Jenis Kelamin</strong></label>
  <div class="col-sm-10">
    <select class="form-control" name="jenis_kelamin" <?= $ro ?>>
      <option value="">-- Pilih --</option>
      <option value="Laki-laki" <?= val('jenis_kelamin', $existing_data) == 'Laki-laki' ? 'selected' : '' ?>>Laki-laki</option>
      <option value="Perempuan" <?= val('jenis_kelamin', $existing_data) == 'Perempuan' ? 'selected' : '' ?>>Perempuan</option>
    </select>
  </div>
</div>

<!-- TANGGAL PENGKAJIAN -->
<div class="row mb-3">
  <label class="col-sm-2 col-form-label"><strong>Tanggal Pengkajian</strong></label>
  <div class="col-sm-10">
    <input type="date" class="form-control" name="tanggal_pengkajian" value="<?= val('tanggal_pengkajian', $existing_data) ?>" <?= $ro ?>>
  </div>
</div>

<!-- UMUR -->
<div class="row mb-3">
  <label class="col-sm-2 col-form-label"><strong>Umur</strong></label>
  <div class="col-sm-10">
    <input type="text" class="form-control" name="umur" value="<?= val('umur', $existing_data) ?>" <?= $ro ?>>
  </div>
</div>

<!-- RM -->
<div class="row mb-3">
  <label class="col-sm-2 col-form-label"><strong>RM</strong></label>
  <div class="col-sm-10">
    <input type="text" class="form-control" name="rm" value="<?= val('rm', $existing_data) ?>" <?= $ro ?>>
  </div>
</div>

<!-- INFORMASI -->
<div class="row mb-3">
  <label class="col-sm-2 col-form-label"><strong>Informasi</strong></label>
  <div class="col-sm-10">
    <input type="text" class="form-control" name="informasi" value="<?= val('informasi', $existing_data) ?>" <?= $ro ?>>
  </div>
</div>

<div class="row mb-2">
                        <label class="col-sm-3 col-form-label text-primary">
                            <strong>II. ALASAN MASUK</strong>
                    </div>

<!-- NAMA KLIEN -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Alasan Masuk</strong></label>

    <div class="col-sm-10">
        <textarea name="alasanmasuk" class="form-control" rows="3"
                  style="overflow:hidden; resize:none;"
                  oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"<?= $ro ?>><?= val('alasanmasuk', $existing_data) ?></textarea>
    </div>
</div>


 <div class="row mb-2">
                        <label class="col-sm-4 col-form-label text-primary">
                            <strong>III. FAKTOR PREDISPOSISI</strong>
                    </div>

<div class="row mb-3">
    <label class="col-sm-5 col-form-label"><strong>1. Pernah mengalami gangguan jiwa di masa lalu?</strong></label>
    <div class="col-sm-3">
        <select class="form-select" name="gangguan_jiwa" <?= $ro_select ?>>
            <option value="">Pilih</option>
            <option value="ya" <?= (val('gangguan_jiwa', $existing_data) == 'ya') ? 'selected' : '' ?>>Ya</option>
            <option value="tidak" <?= (val('gangguan_jiwa', $existing_data) == 'tidak') ? 'selected' : '' ?>>Tidak</option>
        </select>
    </div>
</div>
<div class="row mb-3">
    <label class="col-sm-5 col-form-label"><strong>2. Pengobatan sebelumnya</strong></label>
    <div class="col-sm-3">
        <select class="form-select" name="pengobatan" <?= $ro_select ?>>
            <option value="">Pilih</option>
            <option value="berhasil" <?= (val('pengobatan', $existing_data) == 'berhasil') ? 'selected' : '' ?>>Berhasil</option>
            <option value="tidak_berhasil" <?= (val('pengobatan', $existing_data) == 'tidak_berhasil') ? 'selected' : '' ?>>Tidak berhasil</option>
        </select>
    </div>
</div>
<!-- 3 TABEL -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>3. Riwayat Kejadian</strong></label>
    <div class="col-sm-10">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Jenis Kejadian</th>
                    <th>Pelaku / Usia</th>
                    <th>Korban / Usia</th>
                    <th>Saksi / Usia</th>
                </tr>
            </thead>
           <tbody>
    <tr>
        <td>Aniaya Fisik</td>
        <td><input type="text" name="aniaya_fisik_pelaku" class="form-control" value="<?= val('aniaya_fisik_pelaku', $existing_data) ?>" <?= $ro ?>></td>
        <td><input type="text" name="aniaya_fisik_korban" class="form-control" value="<?= val('aniaya_fisik_korban', $existing_data) ?>" <?= $ro ?>></td>
        <td><input type="text" name="aniaya_fisik_saksi" class="form-control" value="<?= val('aniaya_fisik_saksi', $existing_data) ?>" <?= $ro ?>></td>
    </tr>
    <tr>
        <td>Aniaya Seksual</td>
        <td><input type="text" name="aniaya_seksual_pelaku" class="form-control" value="<?= val('aniaya_seksual_pelaku', $existing_data) ?>" <?= $ro ?>></td>
        <td><input type="text" name="aniaya_seksual_korban" class="form-control" value="<?= val('aniaya_seksual_korban', $existing_data) ?>" <?= $ro ?>></td>
        <td><input type="text" name="aniaya_seksual_saksi" class="form-control" value="<?= val('aniaya_seksual_saksi', $existing_data) ?>" <?= $ro ?>></td>
    </tr>
    <tr>
        <td>Penolakan</td>
        <td><input type="text" name="penolakan_pelaku" class="form-control" value="<?= val('penolakan_pelaku', $existing_data) ?>" <?= $ro ?>></td>
        <td><input type="text" name="penolakan_korban" class="form-control" value="<?= val('penolakan_korban', $existing_data) ?>" <?= $ro ?>></td>
        <td><input type="text" name="penolakan_saksi" class="form-control" value="<?= val('penolakan_saksi', $existing_data) ?>" <?= $ro ?>></td>
    </tr>
    <tr>
        <td>Kekerasan dalam keluarga</td>
        <td><input type="text" name="kekerasan_keluarga_pelaku" class="form-control" value="<?= val('kekerasan_keluarga_pelaku', $existing_data) ?>" <?= $ro ?>></td>
        <td><input type="text" name="kekerasan_keluarga_korban" class="form-control" value="<?= val('kekerasan_keluarga_korban', $existing_data) ?>" <?= $ro ?>></td>
        <td><input type="text" name="kekerasan_keluarga_saksi" class="form-control" value="<?= val('kekerasan_keluarga_saksi', $existing_data) ?>" <?= $ro ?>></td>
    </tr>
    <tr>
        <td>Tindakan Kriminal</td>
        <td><input type="text" name="tindakan_kriminal_pelaku" class="form-control" value="<?= val('tindakan_kriminal_pelaku', $existing_data) ?>" <?= $ro ?>></td>
        <td><input type="text" name="tindakan_kriminal_korban" class="form-control" value="<?= val('tindakan_kriminal_korban', $existing_data) ?>" <?= $ro ?>></td>
        <td><input type="text" name="tindakan_kriminal_saksi" class="form-control" value="<?= val('tindakan_kriminal_saksi', $existing_data) ?>" <?= $ro ?>></td>
    </tr>
</tbody>
        </table>
    </div>
</div>

<!-- PENJELASAN -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Jelaskan No 1,2,3</strong></label>
    <div class="col-sm-10">
        <textarea name="penjelasan_kejadian" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"<?= $ro ?>><?= val('penjelasan_kejadian', $existing_data) ?></textarea>
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-5 col-form-label"><strong>4. Adakah anggota keluarga yang mengalami gangguan jiwa</strong></label>
    <div class="col-sm-3">
        <select class="form-select" name="gangguan_jiwa_keluarga" <?= $ro_select ?>>
            <option value="">Pilih</option>
            <option value="ya" <?= (val('gangguan_jiwa_keluarga', $existing_data) == 'ya') ? 'selected' : '' ?>>Ya</option>
            <option value="tidak" <?= (val('gangguan_jiwa_keluarga', $existing_data) == 'tidak') ? 'selected' : '' ?>>Tidak</option>
        </select>
    </div>
</div>
<!-- INFORMASI -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Hubungan keluarga</strong></label>
    <div class="col-sm-10">
        <input type="text" class="form-control" name="Hubungan_keluarga1" value="<?= val('Hubungan_keluarga1', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>

<!-- INFORMASI -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Gejala</strong></label>
    <div class="col-sm-10">
        <input type="text" class="form-control" name="Gejala" value="<?= val('Gejala', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>

<!-- INFORMASI -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Riwayat</strong></label>
    <div class="col-sm-10">
        <input type="text" class="form-control" name="Riwayat" value="<?= val('Riwayat', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>

<!-- INFORMASI -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Pengobatan/ perawatan</strong></label>
    <div class="col-sm-10">
        <input type="text" class="form-control" name="Pengobatan_perawatan" value="<?= val('Pengobatan_perawatan', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>

<!-- 5 -->
<div class="row mb-2">
    <label class="col-sm-5 col-form-label"><strong>5. Pengalaman masa lalu yang tidak menyenangkan</strong></label>
</div>

<!-- Pengalaman Masa Lalu -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Pengalaman masa lalu yang tidak menyenangkan</strong></label>
    <div class="col-sm-10">
        <textarea name="pengalaman_masa_lalu" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"<?= $ro ?>><?= val('pengalaman_masa_lalu', $existing_data) ?></textarea>
    </div>
</div>

        <div class="row mb-2">
    <label class="col-sm-3 col-form-label text-primary">
        <strong>IV. PEMERIKSAAN FISIK</strong>
    </label>
</div>
<!-- 1. Tanda Vital -->
<div class="row mb-3">
    <label class="col-sm-3 col-form-label"><strong>1. Tanda Vital</strong></label>
</div>

<!-- TD -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Tekanan Darah</strong></label>
    <div class="col-sm-10">
        <div class="input-group">
            <input type="text" class="form-control" name="td" value="<?= val('td', $existing_data) ?>" <?= $ro ?>>
            <span class="input-group-text">mmHg</span>
        </div>
    </div>
</div>

<!-- Nadi -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Nadi</strong></label>
    <div class="col-sm-10">
        <div class="input-group">
            <input type="text" class="form-control" name="nadi" value="<?= val('nadi', $existing_data) ?>" <?= $ro ?>>
            <span class="input-group-text">x/mnt</span>
        </div>
    </div>
</div>

<!-- Suhu -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Suhu</strong></label>
    <div class="col-sm-10">
        <div class="input-group">
            <input type="text" class="form-control" name="suhu" value="<?= val('suhu', $existing_data) ?>" <?= $ro ?>>
            <span class="input-group-text">°C</span>
        </div>
    </div>
</div>

<!-- Pernafasan -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Pernapasan</strong></label>
    <div class="col-sm-10">
        <div class="input-group">
            <input type="text" class="form-control" name="pernafasan" value="<?= val('pernafasan', $existing_data) ?>" <?= $ro ?>>
            <span class="input-group-text">x/mnt</span>
        </div>
    </div>
</div>

<!-- 2. Pengukuran -->
<div class="row mb-3">
    <label class="col-sm-3 col-form-label"><strong>2. Pengukuran</strong></label>
</div>

<!-- TB -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>TB</strong></label>
    <div class="col-sm-10">
        <div class="input-group">
            <input type="text" class="form-control" name="tb" value="<?= val('tb', $existing_data) ?>" <?= $ro ?>>
            <span class="input-group-text">cm</span>
        </div>
    </div>
</div>

<!-- BB -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>BB</strong></label>
    <div class="col-sm-10">
        <div class="input-group">
            <input type="text" class="form-control" name="bb" value="<?= val('bb', $existing_data) ?>" <?= $ro ?>>
            <span class="input-group-text">kg</span>
        </div>
    </div>
</div>

<!-- 3. Keluhan Fisik -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>3. Keluhan Fisik</strong></label>
    <div class="col-sm-10">
        <select class="form-select" name="keluhan_fisik" <?= $ro_select ?>>
            <option value="">Pilih</option>
            <option value="ya" <?= (val('keluhan_fisik', $existing_data) == 'ya') ? 'selected' : '' ?>>Ya</option>
            <option value="tidak" <?= (val('keluhan_fisik', $existing_data) == 'tidak') ? 'selected' : '' ?>>Tidak</option>
        </select>
    </div>
</div>

<!-- Penjelasan -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Penjelasan</strong></label>
    <div class="col-sm-10">
        <textarea name="penjelasan" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"<?= $ro ?>><?= val('penjelasan', $existing_data) ?></textarea>
    </div>
</div>

            <!-- IV. PSIKOSOSIAL -->
<div class="row mb-2">
    <label class="col-sm-3 col-form-label text-primary">
        <strong>IV. PSIKOSOSIAL</strong>
    </label>
</div> 

<!-- 1. Genogram -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>1. Genogram</strong></label>
    <div class="col-sm-10">
        <textarea name="genogram" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"<?= $ro ?>><?= val('genogram', $existing_data) ?></textarea>
    </div>
</div>

<!-- 2. Konsep Diri -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>2. Konsep Diri</strong></label>
</div>

<!-- a. Gambaran Diri -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>a. Gambaran Diri</strong></label>
    <div class="col-sm-10">
        <textarea name="gambaran_diri" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"<?= $ro ?>><?= val('gambaran_diri', $existing_data) ?></textarea>
    </div>
</div>

<!-- b. Identitas Diri -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>b. Identitas Diri</strong></label>
    <div class="col-sm-10">
        <textarea name="identitas_diri" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"<?= $ro ?>><?= val('identitas_diri', $existing_data) ?></textarea>
    </div>
</div>

<!-- c. Peran -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>c. Peran</strong></label>
    <div class="col-sm-10">
        <textarea name="peran" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"<?= $ro ?>><?= val('peran', $existing_data) ?></textarea>
    </div>
</div>

<!-- d. Ideal Diri -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>d. Ideal Diri</strong></label>
    <div class="col-sm-10">
        <textarea name="ideal_diri" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"<?= $ro ?>><?= val('ideal_diri', $existing_data) ?></textarea>
    </div>
</div>

<!-- e. Harga Diri -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>e. Harga Diri</strong></label>
    <div class="col-sm-10">
        <textarea name="harga_diri" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"<?= $ro ?>><?= val('harga_diri', $existing_data) ?></textarea>
    </div>
</div>

<!-- 3. Hubungan Sosial -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>3. Hubungan Sosial</strong></label>
</div>

<!-- a. Orang yang Berarti -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>a. Orang yang Berarti</strong></label>
    <div class="col-sm-10">
        <textarea name="orang_berarti" class="form-control" rows="3" style="overflow:hidden; resize: none;"
                  oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('orang_berarti', $existing_data) ?></textarea>
    </div>
</div>

<!-- b. Kegiatan Kelompok -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>b. Peran serta dalam kegiatan kelompok/ <br>masyarakat</strong></label>
    <div class="col-sm-10">
        <textarea name="kegiatan_kelompok" class="form-control" rows="3" style="overflow:hidden; resize: none;"
                  oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('kegiatan_kelompok', $existing_data) ?></textarea>
    </div>
</div>

<!-- c. Hambatan -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>c. Hambatan dalam hubungan dengan orang lain</strong></label>
    <div class="col-sm-10">
        <textarea name="hambatan_hubungan" class="form-control" rows="3" style="overflow:hidden; resize: none;"
                  oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('hambatan_hubungan', $existing_data) ?></textarea>
    </div>
</div>

<!-- 4. Spiritual -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>4. Spiritual</strong></label>
</div>



<!-- Nilai dan Keyakinan -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>a. Nilai dan Keyakinan</strong></label>
    <div class="col-sm-10">
        <textarea name="nilai_keyakinan" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"<?= $ro ?>><?= val('nilai_keyakinan', $existing_data) ?></textarea>
    </div>
</div>

<!-- Kegiatan Ibadah -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>b. Kegiatan Ibadah</strong></label>
    <div class="col-sm-10">
        <textarea name="kegiatan_ibadah" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"<?= $ro ?>><?= val('kegiatan_ibadah', $existing_data) ?></textarea>
    </div>
</div>          
                       <div class="row mb-2">
                        <label class="col-sm-3 col-form-label text-primary">
                            <strong>VI. STATUS MENTAL</strong>
                    </div> 
<!-- 1 Penampilan -->
<div class="row mb-2">
    <div class="col-sm-2"><strong>1. Penampilan</strong></div>

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="penampilan[]" value="tidak_rapi" id="cb_penampilan_tidak_rapi" <?= $ro_disabled ?> <?= in_array('tidak_rapi', (array)($existing_data['penampilan'] ?? [])) ? 'checked' : '' ?>>
            <label class="form-check-label" for="cb_penampilan_tidak_rapi">Tidak Rapi</label>
        </div>
    </div>

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="penampilan[]" value="pakaian_tidak_sesuai" id="cb_penampilan_pakaian_tidak_sesuai" <?= $ro_disabled ?> <?= in_array('pakaian_tidak_sesuai', (array)($existing_data['penampilan'] ?? [])) ? 'checked' : '' ?>>
            <label class="form-check-label" for="cb_penampilan_pakaian_tidak_sesuai">Penggunaan Pakaian Tidak Sesuai</label>
        </div>
    </div>

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="penampilan[]" value="berpakaian_tidak_biasa" id="cb_penampilan_berpakaian_tidak_biasa" <?= $ro_disabled ?> <?= in_array('berpakaian_tidak_biasa', (array)($existing_data['penampilan'] ?? [])) ? 'checked' : '' ?>>
            <label class="form-check-label" for="cb_penampilan_berpakaian_tidak_biasa">Cara Berpakaian Tidak Seperti Biasanya</label>
        </div>
    </div>

</div>
<br>

<!-- 2. Pembicaraan -->
<div class="row mb-2">
    <div class="col-sm-2"><strong>2. Pembicaraan</strong></div>

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="pembicaraan[]" value="cepat" id="cb_pembicaraan_cepat" <?= $ro_disabled ?> <?= in_array('cepat', (array)($existing_data['pembicaraan'] ?? [])) ? 'checked' : '' ?>>
            <label class="form-check-label" for="cb_pembicaraan_cepat">Cepat</label>
        </div>
    </div>

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="pembicaraan[]" value="keras" id="cb_pembicaraan_keras" <?= $ro_disabled ?> <?= in_array('keras', (array)($existing_data['pembicaraan'] ?? [])) ? 'checked' : '' ?>>
            <label class="form-check-label" for="cb_pembicaraan_keras">Keras</label>
        </div>
    </div>

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="pembicaraan[]" value="gagap" id="cb_pembicaraan_gagap" <?= $ro_disabled ?> <?= in_array('gagap', (array)($existing_data['pembicaraan'] ?? [])) ? 'checked' : '' ?>>
            <label class="form-check-label" for="cb_pembicaraan_gagap">Gagap</label>
        </div>
    </div>

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="pembicaraan[]" value="inkoheren" id="cb_pembicaraan_inkoheren" <?= $ro_disabled ?> <?= in_array('inkoheren', (array)($existing_data['pembicaraan'] ?? [])) ? 'checked' : '' ?>>
            <label class="form-check-label" for="cb_pembicaraan_inkoheren">Inkoheren</label>
        </div>
    </div>

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="pembicaraan[]" value="apatis" id="cb_pembicaraan_apatis" <?= $ro_disabled ?> <?= in_array('apatis', (array)($existing_data['pembicaraan'] ?? [])) ? 'checked' : '' ?>>
            <label class="form-check-label" for="cb_pembicaraan_apatis">Apatis</label>
        </div>
    </div> 
<div class="col-sm-2">
        <div class="form-check">
            </label>
        </div>
    </div>
    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="pembicaraan[]" value="lambat" id="cb_pembicaraan_lambat" <?= $ro_disabled ?> <?= in_array('lambat', (array)($existing_data['pembicaraan'] ?? [])) ? 'checked' : '' ?>>
            <label class="form-check-label" for="cb_pembicaraan_lambat">Lambat</label>
        </div>
    </div>

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="pembicaraan[]" value="membisu" id="cb_pembicaraan_membisu" <?= $ro_disabled ?> <?= in_array('membisu', (array)($existing_data['pembicaraan'] ?? [])) ? 'checked' : '' ?>>
            <label class="form-check-label" for="cb_pembicaraan_membisu">Membisu</label>
        </div>
    </div>

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="pembicaraan[]" value="tidak_memulai" id="cb_pembicaraan_tidak_memulai" <?= $ro_disabled ?> <?= in_array('tidak_memulai', (array)($existing_data['pembicaraan'] ?? [])) ? 'checked' : '' ?>>
            <label class="form-check-label" for="cb_pembicaraan_tidak_memulai">Tidak Mampu Memulai Pembicaraan</label>
        </div>
    </div>

</div>
<br>
<!-- 3. Aktivitas Motorik -->
<div class="row mb-2">
    <div class="col-sm-2"><strong>3. Aktivitas Motorik</strong></div>

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="motorik[]" value="lesu" id="cb_motorik_lesu" <?= $ro_disabled ?> <?= in_array('lesu', (array)($existing_data['motorik'] ?? [])) ? 'checked' : '' ?>>
            <label class="form-check-label" for="cb_motorik_lesu">Lesu</label>
        </div>
    </div>

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="motorik[]" value="tegang" id="cb_motorik_tegang" <?= $ro_disabled ?> <?= in_array('tegang', (array)($existing_data['motorik'] ?? [])) ? 'checked' : '' ?>>
            <label class="form-check-label" for="cb_motorik_tegang">Tegang</label>
        </div>
    </div>

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="motorik[]" value="gelisah" id="cb_motorik_gelisah" <?= $ro_disabled ?> <?= in_array('gelisah', (array)($existing_data['motorik'] ?? [])) ? 'checked' : '' ?>>
            <label class="form-check-label" for="cb_motorik_gelisah">Gelisah</label>
        </div>
    </div>

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="motorik[]" value="agitasi" id="cb_motorik_agitasi" <?= $ro_disabled ?> <?= in_array('agitasi', (array)($existing_data['motorik'] ?? [])) ? 'checked' : '' ?>>
            <label class="form-check-label" for="cb_motorik_agitasi">Agitasi</label>
        </div>
    </div>

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="motorik[]" value="tik" id="cb_motorik_tik" <?= $ro_disabled ?> <?= in_array('tik', (array)($existing_data['motorik'] ?? [])) ? 'checked' : '' ?>>
            <label class="form-check-label" for="cb_motorik_tik">TIK</label>
        </div>
    </div>
    <div class="col-sm-2">
        <div class="form-check">
            
        </div>
    </div>

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="motorik[]" value="grimasen" id="cb_motorik_grimasen" <?= $ro_disabled ?> <?= in_array('grimasen', (array)($existing_data['motorik'] ?? [])) ? 'checked' : '' ?>>
            <label class="form-check-label" for="cb_motorik_grimasen">Grimasen</label>
        </div>
    </div>

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="motorik[]" value="tremor" id="cb_motorik_tremor" <?= $ro_disabled ?> <?= in_array('tremor', (array)($existing_data['motorik'] ?? [])) ? 'checked' : '' ?>>
            <label class="form-check-label" for="cb_motorik_tremor">Tremor</label>
        </div>
    </div>

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="motorik[]" value="kompulsif" id="cb_motorik_kompulsif" <?= $ro_disabled ?> <?= in_array('kompulsif', (array)($existing_data['motorik'] ?? [])) ? 'checked' : '' ?>>
            <label class="form-check-label" for="cb_motorik_kompulsif">Kompulsif</label>
        </div>
    </div>

</div>
<br>
<!-- 4. Alam Perasaan -->
<div class="row mb-2">
    <div class="col-sm-2"><strong>4. Alam Perasaan</strong></div>

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="alam_perasaan[]" value="sedih" id="cb_alam_perasaan_sedih" <?= $ro_disabled ?> <?= in_array('sedih', (array)($existing_data['alam_perasaan'] ?? [])) ? 'checked' : '' ?>>
            <label class="form-check-label" for="cb_alam_perasaan_sedih">Sedih</label>
        </div>
    </div>

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="alam_perasaan[]" value="ketakutan" id="cb_alam_perasaan_ketakutan" <?= $ro_disabled ?> <?= in_array('ketakutan', (array)($existing_data['alam_perasaan'] ?? [])) ? 'checked' : '' ?>>
            <label class="form-check-label" for="cb_alam_perasaan_ketakutan">Ketakutan</label>
        </div>
    </div>

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="alam_perasaan[]" value="putus_asa" id="cb_alam_perasaan_putus_asa" <?= $ro_disabled ?> <?= in_array('putus_asa', (array)($existing_data['alam_perasaan'] ?? [])) ? 'checked' : '' ?>>
            <label class="form-check-label" for="cb_alam_perasaan_putus_asa">Putus Asa</label>
        </div>
    </div>

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="alam_perasaan[]" value="khawatir" id="cb_alam_perasaan_khawatir" <?= $ro_disabled ?> <?= in_array('khawatir', (array)($existing_data['alam_perasaan'] ?? [])) ? 'checked' : '' ?>>
            <label class="form-check-label" for="cb_alam_perasaan_khawatir">Khawatir</label>
        </div>
    </div>

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="alam_perasaan[]" value="gembira_berlebihan" id="cb_alam_perasaan_gembira_berlebihan" <?= $ro_disabled ?> <?= in_array('gembira_berlebihan', (array)($existing_data['alam_perasaan'] ?? [])) ? 'checked' : '' ?>>
            <label class="form-check-label" for="cb_alam_perasaan_gembira_berlebihan">Gembira Berlebihan</label>
        </div>
    </div>

</div>
<br>
<!-- 5. Afek -->
<div class="row mb-2">
    <div class="col-sm-2"><strong>5. Afek</strong></div>

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="afek[]" value="datar" id="cb_afek_datar" <?= $ro_disabled ?> <?= in_array('datar', (array)($existing_data['afek'] ?? [])) ? 'checked' : '' ?>>
            <label class="form-check-label" for="cb_afek_datar">Datar</label>
        </div>
    </div>

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="afek[]" value="tumpul" id="cb_afek_tumpul" <?= $ro_disabled ?> <?= in_array('tumpul', (array)($existing_data['afek'] ?? [])) ? 'checked' : '' ?>>
            <label class="form-check-label" for="cb_afek_tumpul">Tumpul</label>
        </div>
    </div>

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="afek[]" value="tidak_sesuai" id="cb_afek_tidak_sesuai" <?= $ro_disabled ?> <?= in_array('tidak_sesuai', (array)($existing_data['afek'] ?? [])) ? 'checked' : '' ?>>
            <label class="form-check-label" for="cb_afek_tidak_sesuai">Tidak Sesuai</label>
        </div>
    </div>

</div>
<br>
<!-- 6. Interaksi Selama Wawancara -->
<div class="row mb-2">
    <div class="col-sm-2"><strong>6. Interaksi Selama Wawancara</strong></div>

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="interaksi_wawancara[]" value="bermusuhan" id="cb_interaksi_wawancara_bermusuhan" <?= $ro_disabled ?> <?= in_array('bermusuhan', (array)($existing_data['interaksi_wawancara'] ?? [])) ? 'checked' : '' ?>>
            <label class="form-check-label" for="cb_interaksi_wawancara_bermusuhan">Bermusuhan</label>
        </div>
    </div>

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="interaksi_wawancara[]" value="tidak_kooperatif" id="cb_interaksi_wawancara_tidak_kooperatif" <?= $ro_disabled ?> <?= in_array('tidak_kooperatif', (array)($existing_data['interaksi_wawancara'] ?? [])) ? 'checked' : '' ?>>
            <label class="form-check-label" for="cb_interaksi_wawancara_tidak_kooperatif">Tidak Kooperatif</label>
        </div>
    </div>

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="interaksi_wawancara[]" value="mudah_tersinggung" id="cb_interaksi_wawancara_mudah_tersinggung" <?= $ro_disabled ?> <?= in_array('mudah_tersinggung', (array)($existing_data['interaksi_wawancara'] ?? [])) ? 'checked' : '' ?>>
            <label class="form-check-label" for="cb_interaksi_wawancara_mudah_tersinggung">Mudah Tersinggung</label>
        </div>
    </div>

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="interaksi_wawancara[]" value="kontak_mata" id="cb_interaksi_wawancara_kontak_mata" <?= $ro_disabled ?> <?= in_array('kontak_mata', (array)($existing_data['interaksi_wawancara'] ?? [])) ? 'checked' : '' ?>>
            <label class="form-check-label" for="cb_interaksi_wawancara_kontak_mata">Kontak Mata Kurang</label>
        </div>
    </div>

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="interaksi_wawancara[]" value="defensif" id="cb_interaksi_wawancara_defensif" <?= $ro_disabled ?> <?= in_array('defensif', (array)($existing_data['interaksi_wawancara'] ?? [])) ? 'checked' : '' ?>>
            <label class="form-check-label" for="cb_interaksi_wawancara_defensif">Defensif</label>
        </div>
    </div>
    <div class="col-sm-2">
        <div class="form-check">
          
        </div>
    </div>

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="interaksi_wawancara[]" value="curiga" id="cb_interaksi_wawancara_curiga" <?= $ro_disabled ?> <?= in_array('curiga', (array)($existing_data['interaksi_wawancara'] ?? [])) ? 'checked' : '' ?>>
            <label class="form-check-label" for="cb_interaksi_wawancara_curiga">Curiga</label>
        </div>
    </div>

</div>
<br>

<!-- 7. Persepsi - Sensorik -->
<div class="row mb-2">
    <div class="col-sm-2"><strong>7. Persepsi - Sensorik</strong></div>

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="persepsi_sensorik[]" value="pendengaran" id="cb_persepsi_sensorik_pendengaran" <?= $ro_disabled ?> <?= in_array('pendengaran', (array)($existing_data['persepsi_sensorik'] ?? [])) ? 'checked' : '' ?>>
            <label class="form-check-label" for="cb_persepsi_sensorik_pendengaran">Pendengaran</label>
        </div>
    </div>

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="persepsi_sensorik[]" value="pengecapan" id="cb_persepsi_sensorik_pengecapan" <?= $ro_disabled ?> <?= in_array('pengecapan', (array)($existing_data['persepsi_sensorik'] ?? [])) ? 'checked' : '' ?>>
            <label class="form-check-label" for="cb_persepsi_sensorik_pengecapan">Pengecapan</label>
        </div>
    </div>

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="persepsi_sensorik[]" value="penglihatan" id="cb_persepsi_sensorik_penglihatan" <?= $ro_disabled ?> <?= in_array('penglihatan', (array)($existing_data['persepsi_sensorik'] ?? [])) ? 'checked' : '' ?>>
            <label class="form-check-label" for="cb_persepsi_sensorik_penglihatan">Penglihatan</label>
        </div>
    </div>

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="persepsi_sensorik[]" value="perabaan" id="cb_persepsi_sensorik_perabaan" <?= $ro_disabled ?> <?= in_array('perabaan', (array)($existing_data['persepsi_sensorik'] ?? [])) ? 'checked' : '' ?>>
            <label class="form-check-label" for="cb_persepsi_sensorik_perabaan">Perabaan</label>
        </div>
    </div>

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="persepsi_sensorik[]" value="penghidu" id="cb_persepsi_sensorik_penghidu" <?= $ro_disabled ?> <?= in_array('penghidu', (array)($existing_data['persepsi_sensorik'] ?? [])) ? 'checked' : '' ?>>
            <label class="form-check-label" for="cb_persepsi_sensorik_penghidu">Penghidu</label>
        </div>
    </div>
 <div class="col-sm-2">
        <div class="form-check">
      
        </div>
    </div>
    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="ilusi[]" value="ada" id="cb_ilusi_ada" <?= $ro_disabled ?> <?= in_array('ada', (array)($existing_data['ilusi'] ?? [])) ? 'checked' : '' ?>>
            <label class="form-check-label" for="cb_ilusi_ada">Ilusi Ada</label>
        </div>
    </div>

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="ilusi[]" value="tidak_ada" id="cb_ilusi_tidak_ada" <?= $ro_disabled ?> <?= in_array('tidak_ada', (array)($existing_data['ilusi'] ?? [])) ? 'checked' : '' ?>>
            <label class="form-check-label" for="cb_ilusi_tidak_ada">Ilusi Tidak Ada</label>
        </div>
    </div>

</div>
<br>
<!-- 8. Proses Pikir -->
<div class="row mb-2">
    <div class="col-sm-2"><strong>8. Proses Pikir</strong></div>

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="proses_pikir[]" value="sirkumtansial" id="cb_proses_pikir_sirkumtansial" <?= $ro_disabled ?> <?= in_array('sirkumtansial', (array)($existing_data['proses_pikir'] ?? [])) ? 'checked' : '' ?>>
            <label class="form-check-label" for="cb_proses_pikir_sirkumtansial">Sirkumtansial</label>
        </div>
    </div>

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="proses_pikir[]" value="tangensial" id="cb_proses_pikir_tangensial" <?= $ro_disabled ?> <?= in_array('tangensial', (array)($existing_data['proses_pikir'] ?? [])) ? 'checked' : '' ?>>
            <label class="form-check-label" for="cb_proses_pikir_tangensial">Tangensial</label>
        </div>
    </div>

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="proses_pikir[]" value="kehilangan_asosiasi" id="cb_proses_pikir_kehilangan_asosiasi" <?= $ro_disabled ?> <?= in_array('kehilangan_asosiasi', (array)($existing_data['proses_pikir'] ?? [])) ? 'checked' : '' ?>>
            <label class="form-check-label" for="cb_proses_pikir_kehilangan_asosiasi">Kehilangan Asosiasi</label>
        </div>
    </div>

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="proses_pikir[]" value="inkoheren" id="cb_proses_pikir_inkoheren" <?= $ro_disabled ?> <?= in_array('inkoheren', (array)($existing_data['proses_pikir'] ?? [])) ? 'checked' : '' ?>>
            <label class="form-check-label" for="cb_proses_pikir_inkoheren">Inkoheren</label>
        </div>
    </div>

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="proses_pikir[]" value="flight_of_idea" id="cb_proses_pikir_flight_of_idea" <?= $ro_disabled ?> <?= in_array('flight_of_idea', (array)($existing_data['proses_pikir'] ?? [])) ? 'checked' : '' ?>>
            <label class="form-check-label" for="cb_proses_pikir_flight_of_idea">Flight of Idea</label>
        </div>
    </div>
<div class="col-sm-2">
        <div class="form-check">
          </div>
    </div>
    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="proses_pikir[]" value="blocking" id="cb_proses_pikir_blocking" <?= $ro_disabled ?> <?= in_array('blocking', (array)($existing_data['proses_pikir'] ?? [])) ? 'checked' : '' ?>>
            <label class="form-check-label" for="cb_proses_pikir_blocking">Blocking</label>
        </div>
    </div>

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="proses_pikir[]" value="pengulangan_pembicaraan" id="cb_proses_pikir_pengulangan_pembicaraan" <?= $ro_disabled ?> <?= in_array('pengulangan_pembicaraan', (array)($existing_data['proses_pikir'] ?? [])) ? 'checked' : '' ?>>
            <label class="form-check-label" for="cb_proses_pikir_pengulangan_pembicaraan">Pengulangan Pembicaraan</label>
        </div>
    </div>

</div>
<br>
<!-- 9. Isi Pikir -->
<div class="row mb-2">
    <div class="col-sm-2"><strong>9. Isi Pikir</strong></div>

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="isi_pikir[]" value="obsesi" id="cb_isi_pikir_obsesi" <?= $ro_disabled ?> <?= in_array('obsesi', (array)($existing_data['isi_pikir'] ?? [])) ? 'checked' : '' ?>>
            <label class="form-check-label" for="cb_isi_pikir_obsesi">Obsesi</label>
        </div>
    </div>

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="isi_pikir[]" value="fobia" id="cb_isi_pikir_fobia" <?= $ro_disabled ?> <?= in_array('fobia', (array)($existing_data['isi_pikir'] ?? [])) ? 'checked' : '' ?>>
            <label class="form-check-label" for="cb_isi_pikir_fobia">Fobia</label>
        </div>
    </div>

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="isi_pikir[]" value="hipokondria" id="cb_isi_pikir_hipokondria" <?= $ro_disabled ?> <?= in_array('hipokondria', (array)($existing_data['isi_pikir'] ?? [])) ? 'checked' : '' ?>>
            <label class="form-check-label" for="cb_isi_pikir_hipokondria">Hipokondria</label>
        </div>
    </div>

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="isi_pikir[]" value="depersonalisasi" id="cb_isi_pikir_depersonalisasi" <?= $ro_disabled ?> <?= in_array('depersonalisasi', (array)($existing_data['isi_pikir'] ?? [])) ? 'checked' : '' ?>>
            <label class="form-check-label" for="cb_isi_pikir_depersonalisasi">Depersonalisasi</label>
        </div>
    </div>

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="isi_pikir[]" value="ide_terkait" id="cb_isi_pikir_ide_terkait" <?= $ro_disabled ?> <?= in_array('ide_terkait', (array)($existing_data['isi_pikir'] ?? [])) ? 'checked' : '' ?>>
            <label class="form-check-label" for="cb_isi_pikir_ide_terkait">Ide yang Terkait</label>
        </div>
    </div>
<div class="col-sm-2">
        <div class="form-check">
           
        </div>
    </div>
    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="isi_pikir[]" value="pikiran_magis" id="cb_isi_pikir_pikiran_magis" <?= $ro_disabled ?> <?= in_array('pikiran_magis', (array)($existing_data['isi_pikir'] ?? [])) ? 'checked' : '' ?>>
            <label class="form-check-label" for="cb_isi_pikir_pikiran_magis">Pikiran Magis</label>
        </div>
    </div>

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="isi_pikir[]" value="waham" id="cb_isi_pikir_waham" <?= $ro_disabled ?> <?= in_array('waham', (array)($existing_data['isi_pikir'] ?? [])) ? 'checked' : '' ?>>
            <label class="form-check-label" for="cb_isi_pikir_waham">Waham</label>
        </div>
    </div>

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="isi_pikir[]" value="agama" id="cb_isi_pikir_agama" <?= $ro_disabled ?> <?= in_array('agama', (array)($existing_data['isi_pikir'] ?? [])) ? 'checked' : '' ?>>
            <label class="form-check-label" for="cb_isi_pikir_agama">Agama</label>
        </div>
    </div>

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="isi_pikir[]" value="somatik" id="cb_isi_pikir_somatik" <?= $ro_disabled ?> <?= in_array('somatik', (array)($existing_data['isi_pikir'] ?? [])) ? 'checked' : '' ?>>
            <label class="form-check-label" for="cb_isi_pikir_somatik">Somatik</label>
        </div>
    </div>

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="isi_pikir[]" value="kebesaran" id="cb_isi_pikir_kebesaran" <?= $ro_disabled ?> <?= in_array('kebesaran', (array)($existing_data['isi_pikir'] ?? [])) ? 'checked' : '' ?>>
            <label class="form-check-label" for="cb_isi_pikir_kebesaran">Kebesaran</label>
        </div>
    </div>
  <div class="col-sm-2">
        <div class="form-check">
            
        </div>
    </div>

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="isi_pikir[]" value="curiga" id="cb_isi_pikir_curiga" <?= $ro_disabled ?> <?= in_array('curiga', (array)($existing_data['isi_pikir'] ?? [])) ? 'checked' : '' ?>>
            <label class="form-check-label" for="cb_isi_pikir_curiga">Curiga</label>
        </div>
    </div>

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="isi_pikir[]" value="nihilistik" id="cb_isi_pikir_nihilistik" <?= $ro_disabled ?> <?= in_array('nihilistik', (array)($existing_data['isi_pikir'] ?? [])) ? 'checked' : '' ?>>
            <label class="form-check-label" for="cb_isi_pikir_nihilistik">Nihilistik</label>
        </div>
    </div>

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="isi_pikir[]" value="sisip_pikir" id="cb_isi_pikir_sisip_pikir" <?= $ro_disabled ?> <?= in_array('sisip_pikir', (array)($existing_data['isi_pikir'] ?? [])) ? 'checked' : '' ?>>
            <label class="form-check-label" for="cb_isi_pikir_sisip_pikir">Sisip Pikir</label>
        </div>
    </div>

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="isi_pikir[]" value="siar_pikir" id="cb_isi_pikir_siar_pikir" <?= $ro_disabled ?> <?= in_array('siar_pikir', (array)($existing_data['isi_pikir'] ?? [])) ? 'checked' : '' ?>>
            <label class="form-check-label" for="cb_isi_pikir_siar_pikir">Siar Pikir</label>
        </div>
    </div>

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="isi_pikir[]" value="kontrol_pikir" id="cb_isi_pikir_kontrol_pikir" <?= $ro_disabled ?> <?= in_array('kontrol_pikir', (array)($existing_data['isi_pikir'] ?? [])) ? 'checked' : '' ?>>
            <label class="form-check-label" for="cb_isi_pikir_kontrol_pikir">Kontrol Pikir</label>
        </div>
    </div>

</div>
<br>
<!-- 10. Tingkat Kesadaran -->
<div class="row mb-2">
    <div class="col-sm-2"><strong>10. Tingkat Kesadaran</strong></div>

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="tingkat_kesadaran[]" value="bingung" id="cb_tingkat_kesadaran_bingung" <?= $ro_disabled ?> <?= in_array('bingung', (array)($existing_data['tingkat_kesadaran'] ?? [])) ? 'checked' : '' ?>>
            <label class="form-check-label" for="cb_tingkat_kesadaran_bingung">Bingung</label>
        </div>
    </div>

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="tingkat_kesadaran[]" value="sedasi" id="cb_tingkat_kesadaran_sedasi" <?= $ro_disabled ?> <?= in_array('sedasi', (array)($existing_data['tingkat_kesadaran'] ?? [])) ? 'checked' : '' ?>>
            <label class="form-check-label" for="cb_tingkat_kesadaran_sedasi">Sedasi</label>
        </div>
    </div>

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="tingkat_kesadaran[]" value="disorientasi_waktu" id="cb_tingkat_kesadaran_disorientasi_waktu" <?= $ro_disabled ?> <?= in_array('disorientasi_waktu', (array)($existing_data['tingkat_kesadaran'] ?? [])) ? 'checked' : '' ?>>
            <label class="form-check-label" for="cb_tingkat_kesadaran_disorientasi_waktu">Disorientasi Waktu</label>
        </div>
    </div>

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="tingkat_kesadaran[]" value="disorientasi_orang" id="cb_tingkat_kesadaran_disorientasi_orang" <?= $ro_disabled ?> <?= in_array('disorientasi_orang', (array)($existing_data['tingkat_kesadaran'] ?? [])) ? 'checked' : '' ?>>
            <label class="form-check-label" for="cb_tingkat_kesadaran_disorientasi_orang">Disorientasi Orang</label>
        </div>
    </div>

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="tingkat_kesadaran[]" value="disorientasi_tempat" id="cb_tingkat_kesadaran_disorientasi_tempat" <?= $ro_disabled ?> <?= in_array('disorientasi_tempat', (array)($existing_data['tingkat_kesadaran'] ?? [])) ? 'checked' : '' ?>>
            <label class="form-check-label" for="cb_tingkat_kesadaran_disorientasi_tempat">Disorientasi Tempat</label>
        </div>
    </div>

</div>
<br>
<!-- 11. Memori -->
<div class="row mb-2">
    <div class="col-sm-2"><strong>11. Memori</strong></div>

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="memori[]" value="gangguan_daya_ingat_jangka_panjang" id="cb_memori_gangguan_daya_ingat_jangka_panjang" <?= $ro_disabled ?> <?= in_array('gangguan_daya_ingat_jangka_panjang', (array)($existing_data['memori'] ?? [])) ? 'checked' : '' ?>>
            <label class="form-check-label" for="cb_memori_gangguan_daya_ingat_jangka_panjang">Gangguan Daya Ingat Jangka Panjang</label>
        </div>
    </div>

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="memori[]" value="gangguan_daya_ingat_jangka_pendek" id="cb_memori_gangguan_daya_ingat_jangka_pendek" <?= $ro_disabled ?> <?= in_array('gangguan_daya_ingat_jangka_pendek', (array)($existing_data['memori'] ?? [])) ? 'checked' : '' ?>>
            <label class="form-check-label" for="cb_memori_gangguan_daya_ingat_jangka_pendek">Gangguan Daya Ingat Jangka Pendek</label>
        </div>
    </div>

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="memori[]" value="gangguan_daya_ingat_saat_ini" id="cb_memori_gangguan_daya_ingat_saat_ini" <?= $ro_disabled ?> <?= in_array('gangguan_daya_ingat_saat_ini', (array)($existing_data['memori'] ?? [])) ? 'checked' : '' ?>>
            <label class="form-check-label" for="cb_memori_gangguan_daya_ingat_saat_ini">Gangguan Daya Ingat Saat Ini</label>
        </div>
    </div>

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="memori[]" value="konfabulasi" id="cb_memori_konfabulasi" <?= $ro_disabled ?> <?= in_array('konfabulasi', (array)($existing_data['memori'] ?? [])) ? 'checked' : '' ?>>
            <label class="form-check-label" for="cb_memori_konfabulasi">Konfabulasi</label>
        </div>
    </div>

</div>
<br>
<!-- 12. Tingkat Konsentrasi dan Berhitung -->
<div class="row mb-2">
    <div class="col-sm-2"><strong>12. Tingkat Konsentrasi dan Berhitung</strong></div>

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="konsentrasi_berhitung[]" value="mudah_beralih" id="cb_konsentrasi_berhitung_mudah_beralih" <?= $ro_disabled ?> <?= in_array('mudah_beralih', (array)($existing_data['konsentrasi_berhitung'] ?? [])) ? 'checked' : '' ?>>
            <label class="form-check-label" for="cb_konsentrasi_berhitung_mudah_beralih">Mudah Beralih</label>
        </div>
    </div>

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="konsentrasi_berhitung[]" value="tidak_berkonsentrasi" id="cb_konsentrasi_berhitung_tidak_berkonsentrasi" <?= $ro_disabled ?> <?= in_array('tidak_berkonsentrasi', (array)($existing_data['konsentrasi_berhitung'] ?? [])) ? 'checked' : '' ?>>
            <label class="form-check-label" for="cb_konsentrasi_berhitung_tidak_berkonsentrasi">Tidak Mampu Berkonsentrasi</label>
        </div>
    </div>

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="konsentrasi_berhitung[]" value="tidak_berhitung" id="cb_konsentrasi_berhitung_tidak_berhitung" <?= $ro_disabled ?> <?= in_array('tidak_berhitung', (array)($existing_data['konsentrasi_berhitung'] ?? [])) ? 'checked' : '' ?>>
            <label class="form-check-label" for="cb_konsentrasi_berhitung_tidak_berhitung">Tidak Mampu Berhitung Sederhana</label>
        </div>
    </div>

</div>
<br>
<!-- 13. Kemampuan Penilaian -->
<div class="row mb-2">
    <div class="col-sm-2"><strong>13. Kemampuan Penilaian</strong></div>

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="kemampuan_penilaian[]" value="gangguan_ringan" id="cb_kemampuan_penilaian_gangguan_ringan" <?= $ro_disabled ?> <?= in_array('gangguan_ringan', (array)($existing_data['kemampuan_penilaian'] ?? [])) ? 'checked' : '' ?>>
            <label class="form-check-label" for="cb_kemampuan_penilaian_gangguan_ringan">Gangguan Ringan</label>
        </div>
    </div>

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="kemampuan_penilaian[]" value="gangguan_bermakna" id="cb_kemampuan_penilaian_gangguan_bermakna" <?= $ro_disabled ?> <?= in_array('gangguan_bermakna', (array)($existing_data['kemampuan_penilaian'] ?? [])) ? 'checked' : '' ?>>
            <label class="form-check-label" for="cb_kemampuan_penilaian_gangguan_bermakna">Gangguan Bermakna</label>
        </div>
    </div>

</div>
<br>
<!-- 14. Daya Tilik Diri -->
<div class="row mb-2">
    <div class="col-sm-2"><strong>14. Daya Tilik Diri</strong></div>

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="daya_tilik_diri[]" value="mengingkari_penyakit" id="cb_daya_tilik_diri_mengingkari_penyakit" <?= $ro_disabled ?> <?= in_array('mengingkari_penyakit', (array)($existing_data['daya_tilik_diri'] ?? [])) ? 'checked' : '' ?>>
            <label class="form-check-label" for="cb_daya_tilik_diri_mengingkari_penyakit">Mengingkari Penyakit yang Diderita</label>
        </div>
    </div>

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="daya_tilik_diri[]" value="menyalahkan_diluar_diri" id="cb_daya_tilik_diri_menyalahkan_diluar_diri" <?= $ro_disabled ?> <?= in_array('menyalahkan_diluar_diri', (array)($existing_data['daya_tilik_diri'] ?? [])) ? 'checked' : '' ?>>
            <label class="form-check-label" for="cb_daya_tilik_diri_menyalahkan_diluar_diri">Menyalahkan Hal-hal di Luar Dirinya</label>
        </div>
    </div>

</div>
 <div class="row mb-2">
                        <label class="col-sm-5 col-form-label text-primary">
                            <strong>VII. KEBUTUHAN PERSIAPAN PULANG</strong>
                    </div>
              
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>1. Makan</strong></label>
    <div class="col-sm-10">
        <select class="form-select" name="persiapan_makan1" <?= $ro_select ?>>
            <option value="">Pilih</option>
            <option value="bantuan_minimal" <?= (val('persiapan_makan1', $existing_data) == 'bantuan_minimal') ? 'selected' : '' ?>>Bantuan minimal</option>
            <option value="bantuan_partial" <?= (val('persiapan_makan1', $existing_data) == 'bantuan_partial') ? 'selected' : '' ?>>Bantuan partial</option>
            <option value="bantuan_total" <?= (val('persiapan_makan1', $existing_data) == 'bantuan_total') ? 'selected' : '' ?>>Bantuan total</option>
        </select>
    </div>
</div>

            <!-- BAB/BAK -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>2. BAB/BAK</strong></label>
    <div class="col-sm-10">
        <select class="form-select" name="bab1" <?= $ro_select ?>>
             <option value="">Pilih</option>
            <option value="bantuan_minimal" <?= (val('bab1', $existing_data) == 'bantuan_minimal') ? 'selected' : '' ?>>Bantuan minimal</option>
            <option value="bantuan_partial" <?= (val('bab1', $existing_data) == 'bantuan_partial') ? 'selected' : '' ?>>Bantuan partial</option>
            <option value="bantuan_total" <?= (val('bab1', $existing_data) == 'bantuan_total') ? 'selected' : '' ?>>Bantuan total</option>
        </select>
    </div>
</div>

 <!-- Mandi -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>3. Mandi</strong></label>
    <div class="col-sm-10">
        <select class="form-select" name="mandi1" <?= $ro_select ?>>
            <option value="">Pilih</option>
            <option value="bantuan_minimal" <?= (val('mandi1', $existing_data) === 'bantuan_minimal') ? 'selected' : '' ?>>Bantuan minimal</option>
            <option value="bantuan_partial" <?= (val('mandi1', $existing_data) === 'bantuan_partial') ? 'selected' : '' ?>>Bantuan partial</option>
            <option value="bantuan_total" <?= (val('mandi1', $existing_data) === 'bantuan_total') ? 'selected' : '' ?>>Bantuan total</option>
        </select>
    </div>
</div>

<!-- Berpakian/berhias -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>4. Berpakian/berhias</strong></label>
    <div class="col-sm-10">
        <select class="form-select" name="berpakian1" <?= $ro_select ?>>
            <option value="">Pilih</option>
            <option value="bantuan_minimal" <?= (val('berpakian1', $existing_data) === 'bantuan_minimal') ? 'selected' : '' ?>>Bantuan minimal</option>
            <option value="bantuan_partial" <?= (val('berpakian1', $existing_data) === 'bantuan_partial') ? 'selected' : '' ?>>Bantuan partial</option>
            <option value="bantuan_total" <?= (val('berpakian1', $existing_data) === 'bantuan_total') ? 'selected' : '' ?>>Bantuan total</option>
        </select>
    </div>
</div>
<!-- Istirahat/tidur -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>5. Istirahat/tidur</strong></label>
    <div class="col-sm-10">
        <label class="form-check-label me-4">Tidur siang: </label>
        <input type="text" class="form-control d-inline" name="tidur_siang" style="width: 100px;" value="<?= val('tidur_siang', $existing_data) ?>" <?= $ro ?>>
        <label class="form-check-label ms-4">s/d</label>
        <input type="text" class="form-control d-inline" name="tidur_siang_sampai" style="width: 100px;" value="<?= val('tidur_siang_sampai', $existing_data) ?>" <?= $ro ?>><br> <br>

        <label class="form-check-label me-4">Tidur malam: </label>
        <input type="text" class="form-control d-inline" name="tidur_malam" style="width: 100px;" value="<?= val('tidur_malam', $existing_data) ?>" <?= $ro ?>>
        <label class="form-check-label ms-4">s/d</label>
        <input type="text" class="form-control d-inline" name="tidur_malam_sampai" style="width: 100px;" value="<?= val('tidur_malam_sampai', $existing_data) ?>" <?= $ro ?>> <br> <br>

        <label class="form-check-label me-4">Kegiatan sebelum/sesudah tidur: </label>
        <select class="form-select" name="tidur" <?= $ro_select ?>>
            <option value="">Pilih</option>
            <option value="ya" <?= (val('tidur', $existing_data) === 'ya') ? 'selected' : '' ?>>Ya</option>
            <option value="tidak" <?= (val('tidur', $existing_data) === 'tidak') ? 'selected' : '' ?>>Tidak</option>
        </select>
    </div>
</div>

<!-- Penggunaan obat -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>6. Penggunaan obat</strong></label>
    <div class="col-sm-10">
        <select class="form-select" name="obat" <?= $ro_select ?>>
            <option value="">Pilih</option>
            <option value="bantuan_minimal" <?= (val('obat', $existing_data) === 'bantuan_minimal') ? 'selected' : '' ?>>Bantuan minimal</option>
            <option value="bantuan_partial" <?= (val('obat', $existing_data) === 'bantuan_partial') ? 'selected' : '' ?>>Bantuan partial</option>
            <option value="bantuan_total" <?= (val('obat', $existing_data) === 'bantuan_total') ? 'selected' : '' ?>>Bantuan total</option>
        </select>
    </div>
</div>
<!-- Pemeliharaan kesehatan -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>7. Pemeliharaan kesehatan</strong></label>
    <div class="col-sm-10">
        <label class="form-check-label me-3">Perawatan lanjutan: </label>
        <select class="form-select" name="perawatanlanjutan" <?= $ro_select ?>>
            <option value="">Pilih</option>
            <option value="ya" <?= (val('perawatanlanjutan', $existing_data) === 'ya') ? 'selected' : '' ?>>Ya</option>
            <option value="tidak" <?= (val('perawatanlanjutan', $existing_data) === 'tidak') ? 'selected' : '' ?>>Tidak</option>
        </select>

        <br>

        <label class="form-check-label me-3">Perawatan pendukung: </label>
        <select class="form-select" name="perawatanpendukung1" <?= $ro_select ?>>
            <option value="">Pilih</option>
            <option value="ya" <?= (val('perawatanpendukung1', $existing_data) === 'ya') ? 'selected' : '' ?>>Ya</option>
            <option value="tidak" <?= (val('perawatanpendukung1', $existing_data) === 'tidak') ? 'selected' : '' ?>>Tidak</option>
        </select>
    </div>
</div>
<!-- Kegiatan di dalam rumah -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>8. Kegiatan di dalam rumah</strong></label>
    <div class="col-sm-10">
        <label class="form-check-label me-3">Mempersiapkan makanan: </label>
        <select class="form-select" name="memasak1" <?= $ro_select ?>>
            <option value="">Pilih</option>
            <option value="ya" <?= (val('memasak1', $existing_data) === 'ya') ? 'selected' : '' ?>>Ya</option>
            <option value="tidak" <?= (val('memasak1', $existing_data) === 'tidak') ? 'selected' : '' ?>>Tidak</option>
        </select>

        <br>

        <label class="form-check-label me-3">Menjaga kerapian di rumah: </label>
        <select class="form-select" name="menjaga_kerapian1" <?= $ro_select ?>>
            <option value="">Pilih</option>
            <option value="ya" <?= (val('menjaga_kerapian1', $existing_data) === 'ya') ? 'selected' : '' ?>>Ya</option>
            <option value="tidak" <?= (val('menjaga_kerapian1', $existing_data) === 'tidak') ? 'selected' : '' ?>>Tidak</option>
        </select>

        <br>

        <label class="form-check-label me-3">Mencuci pakaian: </label>
        <select class="form-select" name="mencuci_pakaian1" <?= $ro_select ?>>
            <option value="">Pilih</option>
            <option value="ya" <?= (val('mencuci_pakaian1', $existing_data) === 'ya') ? 'selected' : '' ?>>Ya</option>
            <option value="tidak" <?= (val('mencuci_pakaian1', $existing_data) === 'tidak') ? 'selected' : '' ?>>Tidak</option>
        </select>

        <br>

        <label class="form-check-label me-3">Pengaturan keuangan: </label>
        <select class="form-select" name="pengaturan_keuangan1" <?= $ro_select ?>>
            <option value="">Pilih</option>
            <option value="ya" <?= (val('pengaturan_keuangan1', $existing_data) === 'ya') ? 'selected' : '' ?>>Ya</option>
            <option value="tidak" <?= (val('pengaturan_keuangan1', $existing_data) === 'tidak') ? 'selected' : '' ?>>Tidak</option>
        </select>
    </div>
</div>

 <!-- Kegiatan di luar rumah -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>9. Kegiatan di luar rumah</strong></label>
    <div class="col-sm-10">
        <label class="form-check-label me-3">Belanja: </label>
        <select class="form-select" name="belanja1" <?= $ro_select ?>>
            <option value="">Pilih</option>
            <option value="ya" <?= (val('belanja1', $existing_data) === 'ya') ? 'selected' : '' ?>>Ya</option>
            <option value="tidak" <?= (val('belanja1', $existing_data) === 'tidak') ? 'selected' : '' ?>>Tidak</option>
        </select>

        <br>

        <label class="form-check-label me-3">Transportasi: </label>
        <select class="form-select" name="transportasi1" <?= $ro_select ?>>
            <option value="">Pilih</option>
            <option value="ya" <?= (val('transportasi1', $existing_data) === 'ya') ? 'selected' : '' ?>>Ya</option>
            <option value="tidak" <?= (val('transportasi1', $existing_data) === 'tidak') ? 'selected' : '' ?>>Tidak</option>
        </select>

        <br>

        <label class="form-check-label me-3">Lain-lain: </label>
        <select class="form-select" name="lain_lain1" <?= $ro_select ?>>
            <option value="">Pilih</option>
            <option value="ya" <?= (val('lain_lain1', $existing_data) === 'ya') ? 'selected' : '' ?>>Ya</option>
            <option value="tidak" <?= (val('lain_lain1', $existing_data) === 'tidak') ? 'selected' : '' ?>>Tidak</option>
        </select>
    </div>
</div>
            <!-- Penjelasan -->
             
            <div class="row mb-3">
                    <label for="penjelasan" class="col-sm-2 col-form-label"><strong>Penjelasan</strong></label>
                    <div class="col-sm-10">
                        <textarea name="penjelasan_status" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('penjelasan_status', $existing_data) ?></textarea>
                         </div>
                    </div>

 <div class="row mb-2">
                        <label class="col-sm-5 col-form-label text-primary">
                            <strong>VIII. Mekanisme Koping</strong>
                    </div>
<div class="row mb-2">
    <div class="col-sm-2"><strong>Mekanisme Koping</strong></div>

    <div class="col-sm-3">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="psikososial[]" value="adaptif" id="cb_psikososial_adaptif" <?= $ro_disabled ?> <?= in_array('adaptif', (array)($existing_data['psikososial'] ?? [])) ? 'checked' : '' ?>>
            <label class="form-check-label" for="cb_psikososial_adaptif">Adaptif</label>
        </div>
    </div>

    <div class="col-sm-3">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="psikososial[]" value="maladaptif" id="cb_psikososial_maladaptif" <?= $ro_disabled ?> <?= in_array('maladaptif', (array)($existing_data['psikososial'] ?? [])) ? 'checked' : '' ?>>
            <label class="form-check-label" for="cb_psikososial_maladaptif">Maladaptif</label>
        </div>
    </div>

    <div class="col-sm-3">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="psikososial[]" value="bicara_dengan_orang_lain" id="cb_psikososial_bicara_dengan_orang_lain" <?= $ro_disabled ?> <?= in_array('bicara_dengan_orang_lain', (array)($existing_data['psikososial'] ?? [])) ? 'checked' : '' ?>>
            <label class="form-check-label" for="cb_psikososial_bicara_dengan_orang_lain">Bicara dengan orang lain</label>
        </div>
    </div>
</div>

<div class="row mb-2">
    <div class="col-sm-2"></div> <div class="col-sm-3">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="psikososial[]" value="minum_alcohol" id="cb_psikososial_minum_alcohol" <?= $ro_disabled ?> <?= in_array('minum_alcohol', (array)($existing_data['psikososial'] ?? [])) ? 'checked' : '' ?>>
            <label class="form-check-label" for="cb_psikososial_minum_alcohol">Minum alcohol</label>
        </div>
    </div>

    <div class="col-sm-3">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="psikososial[]" value="mampu_menyelesaikan_masalah" id="cb_psikososial_mampu_menyelesaikan_masalah" <?= $ro_disabled ?> <?= in_array('mampu_menyelesaikan_masalah', (array)($existing_data['psikososial'] ?? [])) ? 'checked' : '' ?>>
            <label class="form-check-label" for="cb_psikososial_mampu_menyelesaikan_masalah">Mampu menyelesaikan masalah</label>
        </div>
    </div>

    <div class="col-sm-3">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="psikososial[]" value="reaksi_lambat_berlebih" id="cb_psikososial_reaksi_lambat_berlebih" <?= $ro_disabled ?> <?= in_array('reaksi_lambat_berlebih', (array)($existing_data['psikososial'] ?? [])) ? 'checked' : '' ?>>
            <label class="form-check-label" for="cb_psikososial_reaksi_lambat_berlebih">Reaksi lambat / berlebih</label>
        </div>
    </div>
</div>

<div class="row mb-2">
    <div class="col-sm-2"></div> <div class="col-sm-3">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="psikososial[]" value="teknik_relaksasi" id="cb_psikososial_teknik_relaksasi" <?= $ro_disabled ?> <?= in_array('teknik_relaksasi', (array)($existing_data['psikososial'] ?? [])) ? 'checked' : '' ?>>
            <label class="form-check-label" for="cb_psikososial_teknik_relaksasi">Teknik relaksasi</label>
        </div>
    </div>

    <div class="col-sm-3">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="psikososial[]" value="bekerja_berlebihan" id="cb_psikososial_bekerja_berlebihan" <?= $ro_disabled ?> <?= in_array('bekerja_berlebihan', (array)($existing_data['psikososial'] ?? [])) ? 'checked' : '' ?>>
            <label class="form-check-label" for="cb_psikososial_bekerja_berlebihan">Bekerja berlebihan</label>
        </div>
    </div>

    <div class="col-sm-3">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="psikososial[]" value="aktivitas_konstruktif" id="cb_psikososial_aktivitas_konstruktif" <?= $ro_disabled ?> <?= in_array('aktivitas_konstruktif', (array)($existing_data['psikososial'] ?? [])) ? 'checked' : '' ?>>
            <label class="form-check-label" for="cb_psikososial_aktivitas_konstruktif">Aktivitas konstruktif</label>
        </div>
    </div>
</div>

<div class="row mb-2">
    <div class="col-sm-2"></div> <div class="col-sm-3">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="psikososial[]" value="menghindar" id="cb_psikososial_menghindar" <?= $ro_disabled ?> <?= in_array('menghindar', (array)($existing_data['psikososial'] ?? [])) ? 'checked' : '' ?>>
            <label class="form-check-label" for="cb_psikososial_menghindar">Menghindar</label>
        </div>
    </div>

    <div class="col-sm-3">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="psikososial[]" value="olahraga" id="cb_psikososial_olahraga" <?= $ro_disabled ?> <?= in_array('olahraga', (array)($existing_data['psikososial'] ?? [])) ? 'checked' : '' ?>>
            <label class="form-check-label" for="cb_psikososial_olahraga">Olahraga</label>
        </div>
    </div>

    <div class="col-sm-3">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="psikososial[]" value="mencederai_diri" id="cb_psikososial_mencederai_diri" <?= $ro_disabled ?> <?= in_array('mencederai_diri', (array)($existing_data['psikososial'] ?? [])) ? 'checked' : '' ?>>
            <label class="form-check-label" for="cb_psikososial_mencederai_diri">Mencederai diri</label>
        </div>
    </div>
</div>

 <div class="row mb-2">
    <label class="col-sm-5 col-form-label text-primary">
        <strong>IX. Masalah Psikososial dan Lingkungan</strong>
    </label>
</div>

<!-- Dukungan Kelompok -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Masalah dengan dukungan kelompok, Uraikan</strong></label>
    <div class="col-sm-10">
        <textarea name="dukungan_kelompok" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('dukungan_kelompok', $existing_data) ?></textarea>
    </div>
</div>

<!-- Lingkungan -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Masalah dengan lingkungan, Uraikan</strong></label>
    <div class="col-sm-10">
        <textarea name="masalah_lingkungan" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('masalah_lingkungan', $existing_data) ?></textarea>
    </div>
</div>

<!-- Pendidikan -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Masalah dengan pendidikan, Uraikan</strong></label>
    <div class="col-sm-10">
        <textarea name="masalah_pendidikan" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('masalah_pendidikan', $existing_data) ?></textarea>
    </div>
</div>

<!-- Pekerjaan -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Masalah dengan pekerjaan, Uraikan</strong></label>
    <div class="col-sm-10">
        <textarea name="masalah_pekerjaan" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('masalah_pekerjaan', $existing_data) ?></textarea>
    </div>
</div>

<!-- Perumahan -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Masalah dengan perumahan, Uraikan</strong></label>
    <div class="col-sm-10">
        <textarea name="masalah_perumahan" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('masalah_perumahan', $existing_data) ?></textarea>
    </div>
</div>

<!-- Ekonomi -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Masalah dengan ekonomi, Uraikan</strong></label>
    <div class="col-sm-10">
        <textarea name="masalah_ekonomi" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('masalah_ekonomi', $existing_data) ?></textarea>
    </div>
</div>

<!-- Pelayanan Kesehatan -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Masalah dengan pelayanan kesehatan, Uraikan</strong></label>
    <div class="col-sm-10">
        <textarea name="masalah_pelayanan_kesehatan" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('masalah_pelayanan_kesehatan', $existing_data) ?></textarea>
    </div>
</div>

<!-- Masalah Lain -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Masalah lain, Uraikan</strong></label>
    <div class="col-sm-10">
        <textarea name="masalah_lain" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('masalah_lain', $existing_data) ?></textarea>
    </div>
</div><div class="row mb-3">
 <div class="row mb-2">
                        <label class="col-sm-5 col-form-label text-primary">
                            <strong>X. Pengetahuan Kurang Tentang</strong>
                    </div>
<div class="row mb-2">
    <div class="col-sm-2"><strong>Pengetahuan Kurang Tentang</strong></div>

    <div class="col-sm-3">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="pengetahuan[]" value="penyakit_jiwa" id="cb_pengetahuan_penyakit_jiwa" <?= $ro_disabled ?> <?= in_array('penyakit_jiwa', (array)($existing_data['pengetahuan'] ?? [])) ? 'checked' : '' ?>>
            <label class="form-check-label" for="cb_pengetahuan_penyakit_jiwa">Penyakit Jiwa</label>
        </div>
    </div>

    <div class="col-sm-3">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="pengetahuan[]" value="sistem_pendukung" id="cb_pengetahuan_sistem_pendukung" <?= $ro_disabled ?> <?= in_array('sistem_pendukung', (array)($existing_data['pengetahuan'] ?? [])) ? 'checked' : '' ?>>
            <label class="form-check-label" for="cb_pengetahuan_sistem_pendukung">Sistem Pendukung</label>
        </div>
    </div>

    <div class="col-sm-3">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="pengetahuan[]" value="faktor_presipitasi" id="cb_pengetahuan_faktor_presipitasi" <?= $ro_disabled ?> <?= in_array('faktor_presipitasi', (array)($existing_data['pengetahuan'] ?? [])) ? 'checked' : '' ?>>
            <label class="form-check-label" for="cb_pengetahuan_faktor_presipitasi">Faktor Presipitasi</label>
        </div>
    </div>
</div>

<div class="row mb-2">
    <div class="col-sm-2"></div> <div class="col-sm-3">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="pengetahuan[]" value="penyakit_fisik" id="cb_pengetahuan_penyakit_fisik" <?= $ro_disabled ?> <?= in_array('penyakit_fisik', (array)($existing_data['pengetahuan'] ?? [])) ? 'checked' : '' ?>>
            <label class="form-check-label" for="cb_pengetahuan_penyakit_fisik">Penyakit Fisik</label>
        </div>
    </div>

    <div class="col-sm-3">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="pengetahuan[]" value="koping" id="cb_pengetahuan_koping" <?= $ro_disabled ?> <?= in_array('koping', (array)($existing_data['pengetahuan'] ?? [])) ? 'checked' : '' ?>>
            <label class="form-check-label" for="cb_pengetahuan_koping">Koping</label>
        </div>
    </div>

    <div class="col-sm-3">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="pengetahuan[]" value="obat_obatan" id="cb_pengetahuan_obat_obatan" <?= $ro_disabled ?> <?= in_array('obat_obatan', (array)($existing_data['pengetahuan'] ?? [])) ? 'checked' : '' ?>>
            <label class="form-check-label" for="cb_pengetahuan_obat_obatan">Obat-obatan</label>
        </div>
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