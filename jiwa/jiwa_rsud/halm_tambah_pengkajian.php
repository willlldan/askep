<?php
require_once "koneksi.php";
require_once "utils.php";

$form_id       = 14;
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
$tgl_pengkajian = $submission['tanggal_pengkajian'] ?? '';
$rs_ruangan     = $submission['rs_ruangan'] ?? '';
$existing_evaluasi     = $existing_data['evaluasi']     ?? [];

// =============================================
// HANDLE POST - MAHASISWA SIMPAN DATA
// =============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $level === 'Mahasiswa') {

    if (isLocked($submission)) {
        redirectWithMessage($_SERVER['REQUEST_URI'], 'error', 'Data tidak dapat diubah karena sedang dalam proses review.');
    }

    $tgl_pengkajian = $_POST['tglpengkajian'] ?? '';
    $rs_ruangan     = $_POST['rsruangan'] ?? '';
   // Proses dynamic rows evaluasi
        
    $data = [
        // I. Identitas Klien
    'ruang_rawat'         => $_POST['ruang_rawat'] ?? '',
    'tanggal_rawat'       => $_POST['tanggal_rawat'] ?? '',
    'nama_klien'          => $_POST['nama_klien'] ?? '',
    'jenis_kelamin'       => $_POST['jenis_kelamin'] ?? '',
    'tanggal_pengkajian'  => $_POST['tanggal_pengkajian'] ?? '',
    'umur'                => $_POST['umur'] ?? '',
    'rm'                  => $_POST['rm'] ?? '',
    'informasi'           => $_POST['informasi'] ?? '',
    
    // II. Alasan Masuk
    'alasanmasuk'         => $_POST['alasanmasuk'] ?? '',

    // III. Faktor Predisposisi
        'aniaya_fisik_pelaku'   => $_POST['aniaya_fisik_pelaku'] ?? '',
        'aniaya_fisik_korban'   => $_POST['aniaya_fisik_korban'] ?? '',
        'aniaya_fisik_saksi'    => $_POST['aniaya_fisik_saksi'] ?? '',

        'aniaya_seksual_pelaku' => $_POST['aniaya_seksual_pelaku'] ?? '',
        'aniaya_seksual_korban' => $_POST['aniaya_seksual_korban'] ?? '',
        'aniaya_seksual_saksi'  => $_POST['aniaya_seksual_saksi'] ?? '',

        'penolakan_pelaku'      => $_POST['penolakan_pelaku'] ?? '',
        'penolakan_korban'      => $_POST['penolakan_korban'] ?? '',
        'penolakan_saksi'       => $_POST['penolakan_saksi'] ?? '',

        'kekerasan_keluarga_pelaku' => $_POST['kekerasan_keluarga_pelaku'] ?? '',
        'kekerasan_keluarga_korban' => $_POST['kekerasan_keluarga_korban'] ?? '',
        'kekerasan_keluarga_saksi'  => $_POST['kekerasan_keluarga_saksi'] ?? '',

        'tindakan_kriminal_pelaku'  => $_POST['tindakan_kriminal_pelaku'] ?? '',
        'tindakan_kriminal_korban'  => $_POST['tindakan_kriminal_korban'] ?? '',
        'tindakan_kriminal_saksi'   => $_POST['tindakan_kriminal_saksi'] ?? '',
        'penjelasan_kejadian'          => $_POST['penjelasan_kejadian'] ?? '',
        'Hubungan_keluarga'   => $_POST['Hubungan_keluarga'] ?? '',
        'Gejala'              => $_POST['Gejala'] ?? '',
        'Riwayat'             => $_POST['Riwayat'] ?? '',
        'Pengobatan_perawatan'=> $_POST['Pengobatan_perawatan'] ?? '',
        'pengalaman_masa_lalu'=> $_POST['pengalaman_masa_lalu'] ?? '',

    // IV. Pemeriksaan Fisik
    'td'  => $_POST['td'] ?? '',
    'nadi'=> $_POST['nadi'] ?? '',
    'suhu'=> $_POST['suhu'] ?? '',
    'pernafasan'=> $_POST['pernafasan'] ?? '',
    'tb'  => $_POST['tb'] ?? '',
    'bb'  => $_POST['bb'] ?? '',
    'keluhan_fisik' => $_POST['keluhan_fisik'] ?? '',

    // IV. Psikososial
    'genogram'       => $_POST['genogram'] ?? '',
    'gambaran_diri'  => $_POST['gambaran_diri'] ?? '',
    'identitas_diri' => $_POST['identitas_diri'] ?? '',
    'peran'          => $_POST['peran'] ?? '',
    'ideal_diri'     => $_POST['ideal_diri'] ?? '',
    'harga_diri'     => $_POST['harga_diri'] ?? '',
    'orang_berarti'  => $_POST['orang_berarti'] ?? '',
    'kegiatan_kelompok'=> $_POST['kegiatan_kelompok'] ?? '',
    'hambatan_hubungan'=> $_POST['hambatan_hubungan'] ?? '',
    'nilai_keyakinan'=> $_POST['nilai_keyakinan'] ?? '',
    'kegiatan_ibadah'=> $_POST['kegiatan_ibadah'] ?? '',

    // VI. Status Mental
    'penampilan'      => $_POST['penampilan'] ?? [],
    'pembicaraan'     => $_POST['pembicaraan'] ?? [],
    'motorik'         => $_POST['motorik'] ?? [],
    'alam_perasaan'   => $_POST['alam_perasaan'] ?? [],
    'afek'            => $_POST['afek'] ?? [],
    'interaksi_wawancara'=> $_POST['interaksi_wawancara'] ?? [],
    'persepsi_sensorik'=> $_POST['persepsi_sensorik'] ?? [],
    'ilusi'           => $_POST['ilusi'] ?? [],
    'proses_pikir'    => $_POST['proses_pikir'] ?? [],
    'isi_pikir'       => $_POST['isi_pikir'] ?? [],
    'tingkat_kesadaran'=> $_POST['tingkat_kesadaran'] ?? [],
    'memori'          => $_POST['memori'] ?? [],
    'konsentrasi_berhitung'=> $_POST['konsentrasi_berhitung'] ?? [],
    'kemampuan_penilaian'=> $_POST['kemampuan_penilaian'] ?? [],
    'daya_tilik_diri'=> $_POST['daya_tilik_diri'] ?? [],

    // VII. Status Mental
    'makan'          => $_POST['makan'] ?? [],
    'bab_bak'        => $_POST['bab_bak'] ?? [],
    'mandi'          => $_POST['mandi'] ?? [],
    'berpakian'      => $_POST['berpakian'] ?? [],
    'tidur_siang'    => $_POST['tidur_siang'] ?? '',
    'tidur_siang_sampai'=> $_POST['tidur_siang_sampai'] ?? '',
    'tidur_malam'    => $_POST['tidur_malam'] ?? '',
    'tidur_malam_sampai'=> $_POST['tidur_malam_sampai'] ?? '',
    'kegiatan_tidur' => $_POST['kegiatan_tidur'] ?? [],
    'penggunaan_obat'=> $_POST['penggunaan_obat'] ?? [],
    'perawatan_lanjutan'=> $_POST['perawatan_lanjutan'] ?? [],
    'perawatan_pendukung'=> $_POST['perawatan_pendukung'] ?? [],
    'memasak'        => $_POST['memasak'] ?? [],
    'menjaga_kerapian'=> $_POST['menjaga_kerapian'] ?? [],
    'mencuci_pakaian'=> $_POST['mencuci_pakaian'] ?? [],
    'pengaturan_keuangan'=> $_POST['pengaturan_keuangan'] ?? [],
    'belanja'        => $_POST['belanja'] ?? [],
    'transportasi'   => $_POST['transportasi'] ?? [],
    'lain_lain'      => $_POST['lain_lain'] ?? [],
    'penjelasan_status'=> $_POST['penjelasan_status'] ?? [],

    // VIII. Mekanisme Koping
    'psikososial'    => $_POST['psikososial'] ?? [],

    // IX. Masalah Psikososial dan Lingkungan
    'dukungan_kelompok'=> $_POST['dukungan_kelompok'] ?? '',
    'masalah_lingkungan'=> $_POST['masalah_lingkungan'] ?? '',
    'masalah_pendidikan'=> $_POST['masalah_pendidikan'] ?? '',
    'masalah_pekerjaan'=> $_POST['masalah_pekerjaan'] ?? '',
    'masalah_perumahan'=> $_POST['masalah_perumahan'] ?? '',
    'masalah_ekonomi'=> $_POST['masalah_ekonomi'] ?? '',
    'masalah_pelayanan_kesehatan'=> $_POST['masalah_pelayanan_kesehatan'] ?? '',
    'masalah_lain'=> $_POST['masalah_lain'] ?? '',

    // X. Pengetahuan Kurang Tentang
    'pengetahuan'    => $_POST['pengetahuan'] ?? [],
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
                  oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= htmlspecialchars($existing_data['alasanmasuk'] ?? '') ?></textarea>
    </div>
</div>


 <div class="row mb-2">
                        <label class="col-sm-4 col-form-label text-primary">
                            <strong>III. FAKTOR PREDISPOSISI</strong>
                    </div>

<!-- 1 -->
<div class="row mb-3">
    <label class="col-sm-5 col-form-label"><strong>1. Pernah mengalami gangguan jiwa di masa lalu?</strong></label>
    <div class="col-sm-3">
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="gangguan_jiwa" value="ya" <?= (val('gangguan_jiwa', $existing_data) == 'ya') ? 'checked' : '' ?> <?= $ro ?>>
            <label class="form-check-label">Ya</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="gangguan_jiwa" value="tidak" <?= (val('gangguan_jiwa', $existing_data) == 'tidak') ? 'checked' : '' ?> <?= $ro ?>>
            <label class="form-check-label">Tidak</label>
        </div>
    </div>
</div>

<!-- 2 -->
<div class="row mb-3">
    <label class="col-sm-5 col-form-label"><strong>2. Pengobatan sebelumnya</strong></label>
    <div class="col-sm-3">
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="pengobatan" value="berhasil" <?= (val('pengobatan', $existing_data) == 'berhasil') ? 'checked' : '' ?> <?= $ro ?>>
            <label class="form-check-label">Berhasil</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="pengobatan" value="tidak_berhasil" <?= (val('pengobatan', $existing_data) == 'tidak_berhasil') ? 'checked' : '' ?> <?= $ro ?>>
            <label class="form-check-label">Tidak berhasil</label>
        </div>
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
        <textarea name="penjelasan_kejadian" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= val('penjelasan_kejadian', $existing_data) ?></textarea>
    </div>
</div>

<!-- 4 -->
<div class="row mb-3">
    <label class="col-sm-5 col-form-label"><strong>4. Adakah anggota keluarga yang mengalami gangguan jiwa</strong></label>
    <div class="col-sm-3">
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="gangguan_jiwa" value="ya" <?= (val('gangguan_jiwa', $existing_data) == 'ya') ? 'checked' : '' ?> <?= $ro ?>>
            <label class="form-check-label">Ya</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="gangguan_jiwa" value="tidak" <?= (val('gangguan_jiwa', $existing_data) == 'tidak') ? 'checked' : '' ?> <?= $ro ?>>
            <label class="form-check-label">Tidak</label>
        </div>
    </div>
</div>

<!-- INFORMASI -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Hubungan keluarga</strong></label>
    <div class="col-sm-10">
        <input type="text" class="form-control" name="Hubungan_keluarga" value="<?= val('Hubungan_keluarga', $existing_data) ?>" <?= $ro ?>>
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
        <textarea name="pengalaman_masa_lalu" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= val('pengalaman_masa_lalu', $existing_data) ?></textarea>
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
    <label class="col-sm-2 col-form-label"><strong>TD</strong></label>
    <div class="col-sm-10">
        <div class="input-group">
            <input type="text" class="form-control" name="td" value="<?= val('td', $existing_data) ?>" <?= $ro ?>>
            <span class="input-group-text">mmHg</span>
        </div>
    </div>
</div>

<!-- Nadi -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>N</strong></label>
    <div class="col-sm-10">
        <div class="input-group">
            <input type="text" class="form-control" name="nadi" value="<?= val('nadi', $existing_data) ?>" <?= $ro ?>>
            <span class="input-group-text">x/mnt</span>
        </div>
    </div>
</div>

<!-- Suhu -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>S</strong></label>
    <div class="col-sm-10">
        <div class="input-group">
            <input type="text" class="form-control" name="suhu" value="<?= val('suhu', $existing_data) ?>" <?= $ro ?>>
            <span class="input-group-text">°C</span>
        </div>
    </div>
</div>

<!-- Pernafasan -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>P</strong></label>
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
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="keluhan_fisik" value="ya" <?= (val('keluhan_fisik', $existing_data) == 'ya') ? 'checked' : '' ?> <?= $ro ?>>
            <label class="form-check-label">Ya</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="keluhan_fisik" value="tidak" <?= (val('keluhan_fisik', $existing_data) == 'tidak') ? 'checked' : '' ?> <?= $ro ?>>
            <label class="form-check-label">Tidak</label>
        </div>
    </div>
</div>

<!-- Penjelasan -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Penjelasan</strong></label>
    <div class="col-sm-10">
        <textarea name="penjelasan" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= val('penjelasan', $existing_data) ?></textarea>
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
        <textarea name="genogram" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= val('genogram', $existing_data) ?></textarea>
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
        <textarea name="gambaran_diri" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= val('gambaran_diri', $existing_data) ?></textarea>
    </div>
</div>

<!-- b. Identitas Diri -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>b. Identitas Diri</strong></label>
    <div class="col-sm-10">
        <textarea name="identitas_diri" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= val('identitas_diri', $existing_data) ?></textarea>
    </div>
</div>

<!-- c. Peran -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>c. Peran</strong></label>
    <div class="col-sm-10">
        <textarea name="peran" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= val('peran', $existing_data) ?></textarea>
    </div>
</div>

<!-- d. Ideal Diri -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>d. Ideal Diri</strong></label>
    <div class="col-sm-10">
        <textarea name="ideal_diri" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= val('ideal_diri', $existing_data) ?></textarea>
    </div>
</div>

<!-- e. Harga Diri -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>e. Harga Diri</strong></label>
    <div class="col-sm-10">
        <textarea name="harga_diri" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= val('harga_diri', $existing_data) ?></textarea>
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

<!-- 4. Spiritual -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>4. Spiritual</strong></label>
</div>

<!-- Nilai dan Keyakinan -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>a. Nilai dan Keyakinan</strong></label>
    <div class="col-sm-10">
        <textarea name="nilai_keyakinan" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= val('nilai_keyakinan', $existing_data) ?></textarea>
    </div>
</div>

<!-- Kegiatan Ibadah -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>b. Kegiatan Ibadah</strong></label>
    <div class="col-sm-10">
        <textarea name="kegiatan_ibadah" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= val('kegiatan_ibadah', $existing_data) ?></textarea>
    </div>
</div>          
                       <div class="row mb-2">
                        <label class="col-sm-3 col-form-label text-primary">
                            <strong>VI. STATUS MENTAL</strong>
                    </div> 
<!-- 1 Penampilan -->
<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>1. Penampilan</strong></label>

<div class="col-sm-10">

<div class="form-check form-check-inline">
<input class="form-check-input" type="checkbox" name="penampilan" value="tidak_rapi">
<label class="form-check-label">Tidak rapi</label>
</div>

<div class="form-check form-check-inline">
<input class="form-check-input" type="checkbox" name="penampilan" value="pakaian_tidak_sesuai">
<label class="form-check-label">Penggunaan pakaian tidak sesuai</label>
</div>

<div class="form-check form-check-inline">
<input class="form-check-input" type="checkbox" name="penampilan" value="berpakaian_tidak_biasa">
<label class="form-check-label">Cara berpakaian tidak seperti biasanya</label>
</div>
</div>
</div>


<!-- 2 Pembicaraan -->
<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>2. Pembicaraan</strong></label>

<div class="col-sm-10">

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="pembicaraan" value="cepat"> Cepat
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="pembicaraan" value="keras"> Keras
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="pembicaraan" value="gagap"> Gagap
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="pembicaraan" value="inkoheren"> Inkoheren
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="pembicaraan" value="apatis"> Apatis
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="pembicaraan" value="lambat"> Lambat
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="pembicaraan" value="membisu"> Membisu
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="pembicaraan" value="tidak_memulai"> Tidak mampu memulai pembicaraan
</label>
</div>
</div>


<!-- 3 Aktivitas Motorik -->
<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>3. Aktivitas Motorik</strong></label>

<div class="col-sm-10">

<label class="form-check-label me-3"><input class="form-check-input" type="checkbox" name="motorik[]" value="lesu"> Lesu</label>
<label class="form-check-label me-3"><input class="form-check-input" type="checkbox" name="motorik[]" value="tegang"> Tegang</label>
<label class="form-check-label me-3"><input class="form-check-input" type="checkbox" name="motorik[]" value="gelisah"> Gelisah</label>
<label class="form-check-label me-3"><input class="form-check-input" type="checkbox" name="motorik[]" value="agitasi"> Agitasi</label>
<label class="form-check-label me-3"><input class="form-check-input" type="checkbox" name="motorik[]" value="tik"> TIK</label>
<label class="form-check-label me-3"><input class="form-check-input" type="checkbox" name="motorik[]" value="grimasen"> Grimasen</label>
<label class="form-check-label me-3"><input class="form-check-input" type="checkbox" name="motorik[]" value="tremor"> Tremor</label>
<label class="form-check-label me-3"><input class="form-check-input" type="checkbox" name="motorik[]" value="kompulsif"> Kompulsif</label>
</div>
</div>

           <div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>4. Alam Perasaan</strong></label>

<div class="col-sm-10">

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="alam_perasaan[]" value="sedih"> Sedih
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="alam_perasaan[]" value="ketakutan"> Ketakutan
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="alam_perasaan[]" value="putus_asa"> Putus asa
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="alam_perasaan[]" value="khawatir"> Khawatir
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="alam_perasaan[]" value="gembira_berlebihan"> Gembira berlebihan
</label>
</div>

</div>

          
        <div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>5. Afek</strong></label>

<div class="col-sm-10">

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="afek[]" value="datar"> Datar
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="afek[]" value="tumpul"> Tumpul
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="afek[]" value="tidak_sesuai"> Tidak sesuai
</label>

</div>

</div>

<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>6. Interaksi selama wawancara</strong></label>

<div class="col-sm-10">

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="interaksi_wawancara[]" value="bermusuhan"> Bermusuhan
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="interaksi_wawancara[]" value="tidak_kooperatif"> Tidak kooperatif
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="interaksi_wawancara[]" value="mudah_tersinggung"> Mudah tersinggung
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="interaksi_wawancara[]" value="kontak_mata"> Kontak mata kurang
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="interaksi_wawancara[]" value="defensif"> Defensif
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="interaksi_wawancara[]" value="curiga"> Curiga
</label>
</div>

</div>
<div class="row mb-3">

<label class="col-sm-2 col-form-label"><strong>7. Persepsi - Sensorik</strong></label>

<div class="col-sm-10">

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="persepsi_sensorik[]" value="pendengaran"> Pendengaran
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="persepsi_sensorik[]" value="pengecapan"> Pengecapan
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="persepsi_sensorik[]" value="penglihatan"> Penglihatan
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="persepsi_sensorik[]" value="perabaan"> Perabaan
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="persepsi_sensorik[]" value="penghidu"> Penghidu
</label>

<br>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="ilusi[]" value="ada"> Ilusi Ada
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="ilusi[]" value="tidak_ada"> Ilusi Tidak Ada
</label>

</div>

</div>
            <!-- 8. Proses pikir -->
            <div class="row mb-3">

<label class="col-sm-2 col-form-label"><strong>8. Proses Pikir</strong></label>

<div class="col-sm-10">

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="proses_pikir[]" value="sirkumtansial"> Sirkumtansial
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="proses_pikir[]" value="tangensial"> Tangensial
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="proses_pikir[]" value="kehilangan_asosiasi"> Kehilangan asosiasi
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="proses_pikir[]" value="inkoheren"> Inkoheren
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="proses_pikir[]" value="flight_of_idea"> Flight of idea
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="proses_pikir[]" value="blocking"> Blocking
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="proses_pikir[]" value="pengulangan_pembicaraan"> Pengulangan pembicaraan
</label>

</div>

</div>

            <!-- 9. Isi pikir -->
            <div class="row mb-3">

<label class="col-sm-2 col-form-label"><strong>9. Isi Pikir</strong></label>

<div class="col-sm-10">

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="isi_pikir[]" value="obsesi"> Obsesi
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="isi_pikir[]" value="fobia"> Fobia
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="isi_pikir[]" value="hipokondria"> Hipokondria
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="isi_pikir[]" value="depersonalisasi"> Depersonalisasi
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="isi_pikir[]" value="ide_terkait"> Ide yang terkait
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="isi_pikir[]" value="pikiran_magis"> Pikiran magis
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="isi_pikir[]" value="waham"> Waham
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="isi_pikir[]" value="agama"> Agama
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="isi_pikir[]" value="somatik"> Somatik
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="isi_pikir[]" value="kebesaran"> Kebesaran
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="isi_pikir[]" value="curiga"> Curiga
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="isi_pikir[]" value="nihilistik"> Nihilistik
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="isi_pikir[]" value="sisip_pikir"> Sisip Pikir
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="isi_pikir[]" value="siar_pikir"> Siar Pikir
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="isi_pikir[]" value="kontrol_pikir"> Kontrol Pikir
</label>

</div>

</div>

            <!-- 10. Tingkat Kesadaran -->
           <div class="row mb-3">

<label class="col-sm-2 col-form-label"><strong>10. Tingkat Kesadaran</strong></label>

<div class="col-sm-10">

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="tingkat_kesadaran[]" value="bingung"> Bingung
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="tingkat_kesadaran[]" value="sedasi"> Sedasi
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="tingkat_kesadaran[]" value="disorientasi_waktu"> Disorientasi waktu
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="tingkat_kesadaran[]" value="disorientasi_orang"> Disorientasi orang
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="tingkat_kesadaran[]" value="disorientasi_tempat"> Disorientasi tempat
</label>
</div>

</div>
            <!-- 11. Memori -->
       <div class="row mb-3">

<label class="col-sm-2 col-form-label"><strong>11. Memori</strong></label>

<div class="col-sm-10">

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="memori[]" value="gangguan_daya_ingat_jangka_panjang"> Gangguan daya ingat jangka panjang
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="memori[]" value="gangguan_daya_ingat_jangka_pendek"> Gangguan daya ingat jangka pendek
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="memori[]" value="gangguan_daya_ingat_saat_ini"> Gangguan daya ingat saat ini
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="memori[]" value="konfabulasi"> Konfabulasi
</label>
</div>

</div>

            <!-- 12. Tingkat konsentrasi dan berhitung -->
           <div class="row mb-3">

<label class="col-sm-2 col-form-label"><strong>12. Tingkat Konsentrasi dan Berhitung</strong></label>

<div class="col-sm-10">

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="konsentrasi_berhitung[]" value="mudah_beralih"> Mudah beralih
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="konsentrasi_berhitung[]" value="tidak_berkonsentrasi"> Tidak mampu berkonsentrasi
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="konsentrasi_berhitung[]" value="tidak_berhitung"> Tidak mampu berhitung sederhana
</label>
</div>

</div>

            <!-- 13. Kemampuan penilaian -->
           <div class="row mb-3">

<label class="col-sm-2 col-form-label"><strong>13. Kemampuan Penilaian</strong></label>

<div class="col-sm-10">

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="kemampuan_penilaian[]" value="gangguan_ringan"> Gangguan ringan
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="kemampuan_penilaian[]" value="gangguan_bermakna"> Gangguan bermakna
</label>
</div>

</div>

            <!-- 14. Daya tilik diri -->
           <div class="row mb-3">

<label class="col-sm-2 col-form-label"><strong>14. Daya Tilik Diri</strong></label>

<div class="col-sm-10">

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="daya_tilik_diri[]" value="mengingkari_penyakit"> Mengingkari penyakit yang diderita
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="daya_tilik_diri[]" value="menyalahkan_diluar_diri"> Menyalahkan hal-hal di luar dirinya
</label>
</div>

</div>
 <div class="row mb-2">
                        <label class="col-sm-3 col-form-label text-primary">
                            <strong>VII.	STATUS MENTAL</strong>
                    </div>
              

            <!-- Makan -->
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label"><strong>1. Makan</strong></label>
                <div class="col-sm-9">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="makan" value="bantuan_minimal">
                        <label class="form-check-label">Bantuan minimal</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="makan" value="bantuan_partial">
                        <label class="form-check-label">Bantuan partial</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="makan" value="bantuan_total">
                        <label class="form-check-label">Bantuan total</label>
                    </div>
                </div>
            </div>

            <!-- BAB/BAK -->
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label"><strong>2. BAB/BAK</strong></label>
                <div class="col-sm-9">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="bab_bak" value="bantuan_minimal">
                        <label class="form-check-label">Bantuan minimal</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="bab_bak" value="bantuan_partial">
                        <label class="form-check-label">Bantuan partial</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="bab_bak" value="bantuan_total">
                        <label class="form-check-label">Bantuan total</label>
                    </div>
                </div>
            </div>

            <!-- Mandi -->
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label"><strong>3. Mandi</strong></label>
                <div class="col-sm-9">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="mandi" value="bantuan_minimal">
                        <label class="form-check-label">Bantuan minimal</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="mandi" value="bantuan_partial">
                        <label class="form-check-label">Bantuan partial</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="mandi" value="bantuan_total">
                        <label class="form-check-label">Bantuan total</label>
                    </div>
                </div>
            </div>

            <!-- Berpakian/berhias -->
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label"><strong>4. Berpakian/berhias</strong></label>
                <div class="col-sm-9">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="berpakian" value="bantuan_minimal">
                        <label class="form-check-label">Bantuan minimal</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="berpakian" value="bantuan_partial">
                        <label class="form-check-label">Bantuan partial</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="berpakian" value="bantuan_total">
                        <label class="form-check-label">Bantuan total</label>
                    </div>
                </div>
            </div>

            <!-- Istirahat/tidur -->
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label"><strong>5. Istirahat/tidur</strong></label>
                <div class="col-sm-9">
                    <label class="form-check-label me-4">Tidur siang: </label>
                    <input type="text" class="form-control d-inline" name="tidur_siang" style="width: 100px;" value="<?= val('tidur_siang', $existing_data) ?>" <?= $ro ?>>
                    <label class="form-check-label ms-4">s/d</label>
                    <input type="text" class="form-control d-inline" name="tidur_siang_sampai" style="width: 100px;" value="<?= val('tidur_siang_sampai', $existing_data) ?>" <?= $ro ?>><br> <br>

                    
                    <label class="form-check-label me-4">Tidur malam: </label>
                    <input type="text" class="form-control d-inline" name="tidur_malam" style="width: 100px;" value="<?= val('tidur_malam', $existing_data) ?>" <?= $ro ?>>
                    <label class="form-check-label ms-4">s/d</label>
                    <input type="text" class="form-control d-inline" name="tidur_malam_sampai" style="width: 100px;" value="<?= val('tidur_malam_sampai', $existing_data) ?>" <?= $ro ?>> <br> <br>
                   
                    <label class="form-check-label me-4">Kegiatan sebelum/sesudah tidur: </label>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="kegiatan_tidur" value="tidak">
                        <label class="form-check-label">Ya</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="kegiatan_tidur" value="tidak">
                        <label class="form-check-label">Tidak</label>
                    </div>
                </div>
            </div>

            <!-- Penggunaan obat -->
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label"><strong>6. Penggunaan obat</strong></label>
                <div class="col-sm-9">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="penggunaan_obat" value="bantuan_minimal">
                        <label class="form-check-label">Bantuan minimal</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="penggunaan_obat" value="bantuan_partial">
                        <label class="form-check-label">Bantuan partial</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="penggunaan_obat" value="bantuan_total">
                        <label class="form-check-label">Bantuan total</label>
                    </div>
                </div>
            </div>

            <!-- Pemeliharaan kesehatan -->
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label"><strong>7. Pemeliharaan kesehatan</strong></label>
                <div class="col-sm-9">
                    <label class="form-check-label me-3">Perawatan lanjutan: </label>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="perawatan_lanjutan" value="ya">
                        <label class="form-check-label">Ya</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="perawatan_lanjutan" value="tidak">
                        <label class="form-check-label">Tidak</label>
                    </div>

                    <br>

                    <label class="form-check-label me-3">Perawatan pendukung: </label>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="perawatan_pendukung" value="ya">
                        <label class="form-check-label">Ya</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="perawatan_pendukung" value="tidak">
                        <label class="form-check-label">Tidak</label>
                    </div>
                </div>
            </div>

            <!-- Kegiatan di dalam rumah -->
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label"><strong>8. Kegiatan di dalam rumah</strong></label>
                <div class="col-sm-9">
                    <label class="form-check-label me-3">Mempersiapkan makanan: </label>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="memasak" value="ya">
                        <label class="form-check-label">Ya</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="memasak" value="tidak">
                        <label class="form-check-label">Tidak</label>
                    </div>

                    <br>

                    <label class="form-check-label me-3">Menjaga kerapian di rumah: </label>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="menjaga_kerapian" value="ya">
                        <label class="form-check-label">Ya</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="menjaga_kerapian" value="tidak">
                        <label class="form-check-label">Tidak</label>
                    </div>

                    <br>

                    <label class="form-check-label me-3">Mencuci pakaian: </label>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="mencuci_pakaian" value="ya">
                        <label class="form-check-label">Ya</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="mencuci_pakaian" value="tidak">
                        <label class="form-check-label">Tidak</label>
                    </div>

                    <br>

                    <label class="form-check-label me-3">Pengaturan keuangan: </label>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="pengaturan_keuangan" value="ya">
                        <label class="form-check-label">Ya</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="pengaturan_keuangan" value="tidak">
                        <label class="form-check-label">Tidak</label>
                    </div>
                </div>
            </div>

            <!-- Kegiatan di luar rumah -->
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label"><strong>9. Kegiatan di luar rumah</strong></label>
                <div class="col-sm-9">
                    <label class="form-check-label me-3">Belanja: </label>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="belanja" value="ya">
                        <label class="form-check-label">Ya</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="belanja" value="tidak">
                        <label class="form-check-label">Tidak</label>
                    </div>

                    <br>

                    <label class="form-check-label me-3">Transportasi: </label>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="transportasi" value="ya">
                        <label class="form-check-label">Ya</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="transportasi" value="tidak">
                        <label class="form-check-label">Tidak</label>
                    </div>

                    <br>

                    <label class="form-check-label me-3">Lain-lain: </label>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="lain_lain" value="ya">
                        <label class="form-check-label">Ya</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="lain_lain" value="tidak">
                        <label class="form-check-label">Tidak</label>
                    </div>
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
<div class="row mb-3">
 <div class="row mb-2">
                        <label class="col-sm-5 col-form-label text-primary">
                            <strong>VIII. Mekanisme Koping</strong>
                    </div>
 <div class="row mb-3">
                    <label for="agamaistri" class="col-sm-2 col-form-label"><strong>Mekanisme Koping</strong></label>
                    <div class="col-sm-10">
                       

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="psikososial[]" value="adaptif"> Adaptif
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="psikososial[]" value="maladaptif"> Maladaptif
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="psikososial[]" value="bicara_dengan_orang_lain"> Bicara dengan orang lain
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="psikososial[]" value="minum_alcohol"> Minum alcohol
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="psikososial[]" value="mampu_menyelesaikan_masalah"> Mampu menyelesaikan masalah
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="psikososial[]" value="reaksi_lambat_berlebih"> Reaksi lambat / berlebih
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="psikososial[]" value="teknik_relaksasi"> Teknik relaksasi
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="psikososial[]" value="bekerja_berlebihan"> Bekerja berlebihan
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="psikososial[]" value="aktivitas_konstruktif"> Aktivitas konstruktif
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="psikososial[]" value="menghindar"> Menghindar
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="psikososial[]" value="olahraga"> Olahraga
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="psikososial[]" value="mencederai_diri"> Mencederai diri
</label>
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
 <div class="row mb-3">
                    <label for="agamaistri" class="col-sm-2 col-form-label"><strong>Pengetahuan Kurang Tentang</strong></label>
                    <div class="col-sm-10">
                       

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="pengetahuan[]" value="penyakit_jiwa">
Penyakit Jiwa
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="pengetahuan[]" value="sistem_pendukung">
Sistem Pendukung
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="pengetahuan[]" value="faktor_presipitasi">
Faktor Presipitasi
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="pengetahuan[]" value="penyakit_fisik">
Penyakit Fisik
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="pengetahuan[]" value="koping">
Koping
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="pengetahuan[]" value="obat_obatan">
Obat-obatan
</label>

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