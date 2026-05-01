<?php
require_once "koneksi.php";
require_once "utils.php";

$form_id       = 8;
$level         = $_SESSION['level'];
$user_id       = $_SESSION['id_user'];
$section_name  = 'pengkajian_anak';
$section_label = 'Pengkajian Anak';

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
$existing_obat = $existing_data['obat'] ?? [];


// =============================================
// HANDLE POST - MAHASISWA SIMPAN DATA
// =============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $level === 'Mahasiswa') {

    if (isLocked($submission)) {
        redirectWithMessage($_SERVER['REQUEST_URI'], 'error', 'Data tidak dapat diubah karena sedang dalam proses review.');
    }

        // Proses dynamic rows obat
    $obat = [];
    if (!empty($_POST['obat'])) {
        foreach ($_POST['obat'] as $index => $row) {
            if (empty($row['nama']) && empty($row['usia']) && empty($row['hubungan'])) {
                continue;
            }
            $obat[] = [
                'nama'     => $row['nama']     ?? '',
                'usia'          => $row['usia']           ?? '',
                'hubungan'       => $row['hubungan']        ?? '',
                'status' => $row['status']  ?? '',
            ];
        }
    }

    $tgl_pengkajian = $_POST['tglpengkajian'] ?? '';
    $rs_ruangan     = $_POST['rsruangan'] ?? '';

    $data = [
                'obat' => $obat,
       // ==============================
    // Identitas Anak
    // ==============================
    'nama_anak'        => $_POST['nama_anak'] ?? '',
    'ttl_umur'         => $_POST['ttl_umur'] ?? '',
    'jenis_kelamin'    => $_POST['jenis_kelamin'] ?? '',
    'agama'            => $_POST['agama'] ?? '',
    'alamat'           => $_POST['alamat'] ?? '',
    'tgl_masuk'        => $_POST['tgl_masuk'] ?? '',
    'jam_masuk'        => $_POST['jam_masuk'] ?? '',
    'tgl_pengkajian'   => $_POST['tgl_pengkajian'] ?? '',
    'diagnosa_medik'   => $_POST['diagnosa_medik'] ?? '',

    // ==============================
    // Orang Tua - Ayah
    // ==============================
    'nama_ayah'        => $_POST['nama_ayah'] ?? '',
    'usia_ayah'        => $_POST['usia_ayah'] ?? '',
    'pendidikan_ayah'  => $_POST['pendidikan_ayah'] ?? '',
    'pekerjaan_ayah'   => $_POST['pekerjaan_ayah'] ?? '',
    'agama_ayah'       => $_POST['agama_ayah'] ?? '',
    'alamat_ayah'      => $_POST['alamat_ayah'] ?? '',

    // Ibu
    'nama_ibu'         => $_POST['nama_ibu'] ?? '',
    'usia_ibu'         => $_POST['usia_ibu'] ?? '',
    'pendidikan_ibu'   => $_POST['pendidikan_ibu'] ?? '',
    'pekerjaan_ibu'    => $_POST['pekerjaan_ibu'] ?? '',
    'agama_ibu'        => $_POST['agama_ibu'] ?? '',
    'alamat_ibu'       => $_POST['alamat_ibu'] ?? '',

    // ==============================
    // Riwayat Masuk RS
    // ==============================
    'alasan_masuk'     => $_POST['alasan_masuk'] ?? '',
    'keluhan_utama'    => $_POST['keluhan_utama'] ?? '',
    'riwayat_sekarang' => $_POST['riwayat_sekarang'] ?? '',

    // ==============================
    // Riwayat Kesehatan Lalu
    // ==============================
    'prenatal_periksa' => $_POST['prenatal_periksa'] ?? '',
    'prenatal_keluhan' => $_POST['prenatal_keluhan'] ?? '',
    'prenatal_bb'      => $_POST['prenatal_bb'] ?? '',
    'prenatal_tt'      => $_POST['prenatal_tt'] ?? '',
    'goldar_ibu'       => $_POST['goldar_ibu'] ?? '',
    'goldar_ayah'      => $_POST['goldar_ayah'] ?? '',

    // Intra Natal
    'tempat_lahir'     => $_POST['tempat_lahir'] ?? '',
    'jenis_persalinan' => $_POST['jenis_persalinan'] ?? '',
    'penolong'         => $_POST['penolong'] ?? '',
    'komplikasi'       => $_POST['komplikasi'] ?? '',

    // Post Natal
    'kondisi_bayi'     => $_POST['kondisi_bayi'] ?? '',
    'gangguan_lahir'   => $_POST['gangguan_lahir'] ?? '',

    // ==============================
    // Riwayat Kesehatan Pribadi
    // ==============================
    'penyakit'         => $_POST['penyakit'] ?? '',
    'umur_penyakit'    => $_POST['umur_penyakit'] ?? '',
    'obat_oleh'        => $_POST['obat_oleh'] ?? '',
    'kecelakaan'       => $_POST['kecelakaan'] ?? '',
    'zat_berbahaya'    => $_POST['zat_berbahaya'] ?? '',
    'perkembangan'     => $_POST['perkembangan'] ?? '',

    // ==============================
    // Riwayat Kesehatan Keluarga
    // ==============================
    'genogram'         => $_POST['genogram'] ?? '',
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

    <?php include "anak/format_anggrek/tab.php"; ?>

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
              <h5 class="card-title mb-1"><strong>Format Pengkajian Anak</strong></h5>

                <!-- General Form Elements -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

         
<!-- A. Identitas -->
<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>A. Identitas</strong></label>
</div>

<!-- 1. Identitas klien -->
<div class="row mb-2">
    <label class="col-sm-12"><strong>1. Identitas klien</strong></label>
</div>

<!-- a. Nama Anak -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>a. Nama Anak :</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="nama_anak" value="<?= val('nama_anak', $existing_data) ?>" <?= $ro ?> >
    </div>
</div>

<!-- b. TTL / Umur -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>b. Tempat, Tgl Lahir / Usia :</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="ttl_umur" value="<?= val('ttl_umur', $existing_data) ?>" <?= $ro ?> >
    </div>
</div>

<!-- c. Jenis Kelamin -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>c. Jenis Kelamin :</strong></label>
    <div class="col-sm-9">
        <select class="form-control" name="jenis_kelamin" <?= $ro_select ?> >
            <option value="">-- Pilih --</option>
            <option value="Laki-laki" <?= (val('jenis_kelamin', $existing_data) == 'Laki-laki')?'selected':'' ?>>Laki-laki</option>
            <option value="Perempuan" <?= (val('jenis_kelamin', $existing_data) == 'Perempuan')?'selected':'' ?>>Perempuan</option>
        </select>
    </div>
</div>

<!-- d. Agama -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>d. Agama :</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="agama" value="<?= val('agama', $existing_data) ?>" <?= $ro ?> >
    </div>
</div>

<!-- e. Alamat -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>e. Alamat :</strong></label>
    <div class="col-sm-9">
        <textarea class="form-control" name="alamat" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('alamat', $existing_data) ?></textarea>
    </div>
</div>

<!-- f. Tanggal Masuk -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>f. Tgl Masuk :</strong></label>
    <div class="col-sm-9 d-flex align-items-center gap-2">
        <input type="date" class="form-control" name="tgl_masuk" value="<?= val('tgl_masuk', $existing_data) ?>" <?= $ro ?> >
        <span>jam</span>
        <input type="time" class="form-control" name="jam_masuk" value="<?= val('jam_masuk', $existing_data) ?>" <?= $ro ?> >
    </div>
</div>

<!-- g. Tanggal Pengkajian -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>g. Tanggal Pengkajian :</strong></label>
    <div class="col-sm-9">
        <input type="date" class="form-control" name="tgl_pengkajian" value="<?= val('tgl_pengkajian', $existing_data) ?>" <?= $ro ?> >
    </div>
</div>

<!-- h. Diagnosa Medik -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>h. Diagnosa Medik :</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="diagnosa_medik" value="<?= val('diagnosa_medik', $existing_data) ?>" <?= $ro ?> >
    </div>
</div>

<!-- 2. Identitas Orang Tua -->
<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>2. Identitas Orang Tua</strong></label>
</div>

<!-- Ayah -->
<div class="row mb-2"><label class="col-sm-12"><strong>Ayah</strong></label></div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>a. Nama :</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="nama_ayah" value="<?= val('nama_ayah', $existing_data) ?>" <?= $ro ?> >
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>b. Usia :</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="usia_ayah" value="<?= val('usia_ayah', $existing_data) ?>" <?= $ro ?> >
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>c. Pendidikan :</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="pendidikan_ayah" value="<?= val('pendidikan_ayah', $existing_data) ?>" <?= $ro ?> >
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>d. Pekerjaan/Sumber Penghasilan :</strong></label>
    <div class="col-sm-9">
        <textarea name="pekerjaan_ayah" class="form-control" rows="4" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('pekerjaan_ayah', $existing_data) ?></textarea>
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>e. Agama :</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="agama_ayah" value="<?= val('agama_ayah', $existing_data) ?>" <?= $ro ?> >
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>f. Alamat :</strong></label>
    <div class="col-sm-9">
        <textarea name="alamat_ayah" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('alamat_ayah', $existing_data) ?></textarea>
    </div>
</div>

<!-- Ibu -->
<div class="row mb-2"><label class="col-sm-12"><strong>Ibu</strong></label></div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>a. Nama :</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="nama_ibu" value="<?= val('nama_ibu', $existing_data) ?>" <?= $ro ?> >
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>b. Usia :</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="usia_ibu" value="<?= val('usia_ibu', $existing_data) ?>" <?= $ro ?> >
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>c. Pendidikan :</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="pendidikan_ibu" value="<?= val('pendidikan_ibu', $existing_data) ?>" <?= $ro ?> >
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>d. Pekerjaan/Sumber Penghasilan :</strong></label>
    <div class="col-sm-9">
        <textarea name="pekerjaan_ibu" class="form-control" rows="4" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('pekerjaan_ibu', $existing_data) ?></textarea>
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>e. Agama :</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="agama_ibu" value="<?= val('agama_ibu', $existing_data) ?>" <?= $ro ?> >
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>f. Alamat :</strong></label>
    <div class="col-sm-9">
        <textarea name="alamat_ibu" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('alamat_ibu', $existing_data) ?></textarea>
    </div>
</div>
   <p class="text-primary fw-bold mb-2">3.	Identitas Saudara Kandung	</p>
                    <table class="table table-bordered" id="tabel-obat">
                        <thead>
                            <tr>
                                <th class="text-center" style="width:40px">No</th>
                                <th class="text-center">Nama</th>
                                <th class="text-center">Usia</th>
                                <th class="text-center">Hubungan</th>
                                <th class="text-center">Status Kesehatan</th>
                                <th class="text-center" style="width:60px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="tbody-obat">
                            <!-- Dynamic rows masuk sini -->
                        </tbody>
                    </table>
                    <div class="row mb-4">
                        <div class="col-sm-12 d-flex justify-content-end">
                            <button type="button" class="btn btn-primary btn-sm" id="btn-tambah-obat" onclick="tambahRowObat()">+ Tambah Saudara</button>
                        </div>
                    </div>

<!-- Alasan Masuk RS -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Alasan Masuk RS :</strong></label>
    <div class="col-sm-9">
        <textarea class="form-control" rows="3" name="alasan_masuk" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('alasan_masuk', $existing_data) ?></textarea>
    </div>
</div>

<!-- Keluhan Utama -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Keluhan Utama :</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="keluhan_utama" value="<?= val('keluhan_utama', $existing_data) ?>" <?= $ro ?> >
    </div>
</div>

<!-- Riwayat Kesehatan Sekarang -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Riwayat Kesehatan Sekarang :</strong></label>
    <div class="col-sm-9">
        <textarea class="form-control" rows="4" name="riwayat_sekarang" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('riwayat_sekarang', $existing_data) ?></textarea>
    </div>
</div>

<!-- 5. Riwayat Kesehatan Lalu (0 – 5 Tahun) -->
<div class="row mb-2">
    <label class="col-sm-12 col-form-label text-primary"><strong>5. Riwayat Kesehatan Lalu (0 – 5 Tahun)</strong></label>
</div>

<!-- Prenatal Care -->
<div class="row mb-2"><label class="col-sm-12"><strong>Prenatal Care</strong></label></div>

<!-- a. Pemeriksaan kehamilan -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>a. Ibu memeriksakan kehamilannya setiap minggu di :</strong></label>
    <div class="col-sm-9">
        <textarea name="prenatal_periksa" class="form-control" rows="4" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('prenatal_periksa', $existing_data) ?></textarea>
    </div>
</div>

<!-- b. Keluhan selama hamil -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>b. Keluhan selama hamil yang dirasakan oleh ibu :</strong></label>
    <div class="col-sm-9">
        <textarea name="prenatal_keluhan" class="form-control" rows="6" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('prenatal_keluhan', $existing_data) ?></textarea>
    </div>
</div>

<!-- c. Riwayat berat badan -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>c. Riwayat berat badan selama hamil :</strong></label>
    <div class="col-sm-9">
        <textarea name="prenatal_bb" class="form-control" rows="4" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('prenatal_bb', $existing_data) ?></textarea>
    </div>
</div>

<!-- d. Riwayat Imunisasi TT -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>d. Riwayat Imunisasi TT :</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="prenatal_tt" value="<?= val('prenatal_tt', $existing_data) ?>" <?= $ro ?> >
    </div>
</div>

<!-- e. Golongan darah ibu -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>e. Golongan darah ibu :</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="goldar_ibu" value="<?= val('goldar_ibu', $existing_data) ?>" <?= $ro ?> >
    </div>
</div>

<!-- f. Golongan darah ayah -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>f. Golongan Darah Ayah :</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="goldar_ayah" value="<?= val('goldar_ayah', $existing_data) ?>" <?= $ro ?> >
    </div>
</div>

<!-- Intra Natal -->
<div class="row mb-2"><label class="col-sm-12"><strong>Intra Natal</strong></label></div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>a. Tempat Melahirkan :</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="tempat_lahir" value="<?= val('tempat_lahir', $existing_data) ?>" <?= $ro ?> >
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>b. Jenis Persalinan :</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="jenis_persalinan" value="<?= val('jenis_persalinan', $existing_data) ?>" <?= $ro ?> >
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>c. Penolong Persalinan :</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="penolong" value="<?= val('penolong', $existing_data) ?>" <?= $ro ?> >
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>d. Komplikasi yang dialami oleh ibu pada saat melahirkan dan setelah melahirkan :</strong></label>
    <div class="col-sm-9">
        <textarea name="komplikasi" class="form-control" rows="7" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('komplikasi', $existing_data) ?></textarea>
    </div>
</div>

<!-- Post Natal -->
<div class="row mb-2"><label class="col-sm-12"><strong>Post Natal</strong></label></div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>a. Kondisi Bayi :</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="kondisi_bayi" value="<?= val('kondisi_bayi', $existing_data) ?>" <?= $ro ?> >
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>b. Apakah Anak pada saat lahir mengalami gangguan :</strong></label>
    <div class="col-sm-9">
        <textarea name="gangguan_lahir" class="form-control" rows="4" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('gangguan_lahir', $existing_data) ?></textarea>
    </div>
</div>

<!-- Umum (Untuk Semua Usia) -->
<div class="row mb-2"><label class="col-sm-12 text-primary"><strong>(Untuk Semua Usia)</strong></label></div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>a. Klien pernah mengalami penyakit :</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="penyakit" value="<?= val('penyakit', $existing_data) ?>" <?= $ro ?> >
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Pada Umur :</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="umur_penyakit" value="<?= val('umur_penyakit', $existing_data) ?>" <?= $ro ?> >
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Obat yang diberikan oleh :</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="obat_oleh" value="<?= val('obat_oleh', $existing_data) ?>" <?= $ro ?> >
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>b. Riwayat Kecelakaan :</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="kecelakaan" value="<?= val('kecelakaan', $existing_data) ?>" <?= $ro ?> >
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>c. Riwayat mengkonsumsi obat-obatan berbahaya / zat kimia berbahaya :</strong></label>
    <div class="col-sm-9">
        <textarea name="zat_berbahaya" class="form-control" rows="10" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('zat_berbahaya', $existing_data) ?></textarea>
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>d. Perkembangan anak dibanding saudara-saudaranya :</strong></label>
    <div class="col-sm-9">
        <textarea name="perkembangan" class="form-control" rows="4" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('perkembangan', $existing_data) ?></textarea>
    </div>
</div>

<!-- 6. Riwayat Kesehatan Keluarga -->
<div class="row mb-3">
    <label class="col-sm-12 col-form-label text-primary"><strong>6. Riwayat Kesehatan Keluarga</strong></label>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Genogram :</strong></label>
    <div class="col-sm-9">
        <textarea name="genogram" class="form-control" rows="2" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('genogram', $existing_data) ?></textarea>
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
          <script>
                        let rowObatCount = 1;
                       
                        const existingObat = <?= json_encode($existing_obat) ?>;
                        
                        const isReadonly = <?= json_encode($is_readonly) ?>;
                        // ---- OBAT ----
                        function tambahRowObat(data = null) {
                            const tbody = document.getElementById('tbody-obat');
                            const index = rowObatCount;
                            const row   = document.createElement('tr');
                            row.innerHTML = `
                                <td class="text-center align-middle">${index}</td>
                                <td><input type="text" class="form-control form-control-sm" name="obat[${index}][nama]" value="${data?.nama ?? ''}" ${isReadonly ? 'readonly' : ''}></td>
                                <td><input type="text" class="form-control form-control-sm" name="obat[${index}][usia]" value="${data?.usia ?? ''}" ${isReadonly ? 'readonly' : ''}></td>
                                <td><input type="text" class="form-control form-control-sm" name="obat[${index}][hubungan]" value="${data?.hubungan ?? ''}" ${isReadonly ? 'readonly' : ''}></td>
                                <td><input type="text" class="form-control form-control-sm" name="obat[${index}][status]" value="${data?.status ?? ''}" ${isReadonly ? 'readonly' : ''}></td>
                                <td class="text-center align-middle">
                                    <button type="button" class="btn btn-danger btn-sm" onclick="hapusRow(this)" ${isReadonly ? 'disabled' : ''}>x</button>
                                </td>
                            `;
                            tbody.appendChild(row);
                            rowObatCount++;
                        }
                     
                        function hapusRow(btn) {
                            btn.closest('tr').remove();
                        }
                        // Load existing rows on page load
                        window.addEventListener('load', function () {
                            if (existingObat && existingObat.length > 0) {
                                existingObat.forEach(row => tambahRowObat(row));
                            } else {
                                tambahRowObat(); // default 1 row kosong
                            }
                            
                            // Disable add buttons if readonly
                            if (isReadonly) {
                                document.getElementById('btn-tambah-obat').setAttribute('disabled', 'disabled');
                              
                            }
                        });
                        const existingData = <?= json_encode($existing_data) ?>;
                    </script>

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