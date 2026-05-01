<?php
require_once "koneksi.php";
require_once "utils.php";

$form_id       = 17;
$level         = $_SESSION['level'];
$user_id       = $_SESSION['id_user'];
$section_name  = 'pengkajian_kebutuhan';
$section_label = 'Pengkajian Kebutuhan';

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
$ro_disabled = $is_readonly ? 'disabled' : '';
$existing_diagnosa     = $existing_data['diagnosa']     ?? [];



// =============================================
// HANDLE POST - MAHASISWA SIMPAN DATA
// =============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $level === 'Mahasiswa') {

    if (isLocked($submission)) {
        redirectWithMessage($_SERVER['REQUEST_URI'], 'error', 'Data tidak dapat diubah karena sedang dalam proses review.');
    }

    $tgl_pengkajian = $_POST['tglpengkajian'] ?? '';
    $rs_ruangan     = $_POST['rsruangan'] ?? '';
      // Proses dynamic rows diagnosa
        $diagnosa = [];
        if (!empty($_POST['diagnosa'])) {
            foreach ($_POST['diagnosa'] as $index => $row) {
                if (empty($row['pemeriksaan']) && empty($row['hasil']) && empty($row['satuan'])) {
                    continue;
                }
                $diagnosa[] = [
                    'pemeriksaan'      => $row['pemeriksaan']      ?? '',
                    'hasil' => $row['hasil'] ?? '',
                    'satuan'  => $row['satuan']  ?? '',
                    'nilai'  => $row['nilai']  ?? '',
                ];
            }
        } 
    $data = [
             'diagnosa'     => $diagnosa,
            'mandi'                     => $_POST['mandi'] ?? '',
    'berpakaian'                => $_POST['berpakaian'] ?? '',
    'mobilisasi'                => $_POST['mobilisasi'] ?? '',
    'pindah'                    => $_POST['pindah'] ?? '',
    'ambulasi'                  => $_POST['ambulasi'] ?? '',
    'makan'                     => $_POST['makan'] ?? '',
    'nyeri'                     => $_POST['nyeri'] ?? '',
    'panca_indra'               => $_POST['panca_indra'] ?? '',
    'berbicara'                 => $_POST['berbicara'] ?? '',
    'membaca'                   => $_POST['membaca'] ?? '',
    'frekuensi_makan_sebelum'   => $_POST['frekuensi_makan_sebelum'] ?? '',
    'frekuensi_makan_sekarang'  => $_POST['frekuensi_makan_sekarang'] ?? '',
    'selera_makan_sebelum'      => $_POST['selera_makan_sebelum'] ?? '',
    'selera_makan_sekarang'     => $_POST['selera_makan_sekarang'] ?? '',
    'menu_makan_sebelum'        => $_POST['menu_makan_sebelum'] ?? '',
    'menu_makan_sekarang'       => $_POST['menu_makan_sekarang'] ?? '',
    'ritual_makan_sebelum'      => $_POST['ritual_makan_sebelum'] ?? '',
    'ritual_makan_sekarang'     => $_POST['ritual_makan_sekarang'] ?? '',
    'bantuan_makan_sebelum'     => $_POST['bantuan_makan_sebelum'] ?? '',
    'bantuan_makan_sekarang'    => $_POST['bantuan_makan_sekarang'] ?? '',
    'jenis_minum_sebelum'       => $_POST['jenis_minum_sebelum'] ?? '',
    'jenis_minum_sekarang'      => $_POST['jenis_minum_sekarang'] ?? '',
    'jumlah_cairan_sebelum'     => $_POST['jumlah_cairan_sebelum'] ?? '',
    'jumlah_cairan_sekarang'    => $_POST['jumlah_cairan_sekarang'] ?? '',
    'bantuan_cairan_sebelum'    => $_POST['bantuan_cairan_sebelum'] ?? '',
    'bantuan_cairan_sekarang'   => $_POST['bantuan_cairan_sekarang'] ?? '',
    'bab_frekuensi_sebelum'     => $_POST['bab_frekuensi_sebelum'] ?? '',
    'bab_frekuensi_sekarang'    => $_POST['bab_frekuensi_sekarang'] ?? '',
    'bab_konsistensi_sebelum'   => $_POST['bab_konsistensi_sebelum'] ?? '',
    'bab_konsistensi_sekarang'  => $_POST['bab_konsistensi_sekarang'] ?? '',
    'bab_warna_sebelum'         => $_POST['bab_warna_sebelum'] ?? '',
    'bab_warna_sekarang'        => $_POST['bab_warna_sekarang'] ?? '',
    'bab_bau_sebelum'           => $_POST['bab_bau_sebelum'] ?? '',
    'bab_bau_sekarang'          => $_POST['bab_bau_sekarang'] ?? '',
    'bab_kesulitan_sebelum'     => $_POST['bab_kesulitan_sebelum'] ?? '',
    'bab_kesulitan_sekarang'    => $_POST['bab_kesulitan_sekarang'] ?? '',
    'bab_obat_sebelum'          => $_POST['bab_obat_sebelum'] ?? '',
    'bab_obat_sekarang'         => $_POST['bab_obat_sekarang'] ?? '',
    'bak_frekuensi_sebelum'     => $_POST['bak_frekuensi_sebelum'] ?? '',
    'bak_frekuensi_sekarang'    => $_POST['bak_frekuensi_sekarang'] ?? '',
    'bak_warna_sebelum'         => $_POST['bak_warna_sebelum'] ?? '',
    'bak_warna_sekarang'        => $_POST['bak_warna_sekarang'] ?? '',
    'bak_bau_sebelum'           => $_POST['bak_bau_sebelum'] ?? '',
    'bak_bau_sekarang'          => $_POST['bak_bau_sekarang'] ?? '',
    'bak_kesulitan_sebelum'     => $_POST['bak_kesulitan_sebelum'] ?? '',
    'bak_kesulitan_sekarang'    => $_POST['bak_kesulitan_sekarang'] ?? '',
    'bak_obat_sebelum'          => $_POST['bak_obat_sebelum'] ?? '',
    'bak_obat_sekarang'         => $_POST['bak_obat_sekarang'] ?? '',
    'tidur_siang_sebelum'       => $_POST['tidur_siang_sebelum'] ?? '',
    'tidur_siang_sekarang'      => $_POST['tidur_siang_sekarang'] ?? '',
    'tidur_malam_sebelum'       => $_POST['tidur_malam_sebelum'] ?? '',
    'tidur_malam_sekarang'      => $_POST['tidur_malam_sekarang'] ?? '',
    'kesulitan_tidur_sebelum'   => $_POST['kesulitan_tidur_sebelum'] ?? '',
    'kesulitan_tidur_sekarang'  => $_POST['kesulitan_tidur_sekarang'] ?? '',
    'kebiasaan_tidur_sebelum'   => $_POST['kebiasaan_tidur_sebelum'] ?? '',
    'kebiasaan_tidur_sekarang'  => $_POST['kebiasaan_tidur_sekarang'] ?? '',
    'mandi_frekuensi_sebelum'   => $_POST['mandi_frekuensi_sebelum'] ?? '',
    'mandi_frekuensi_sekarang'  => $_POST['mandi_frekuensi_sekarang'] ?? '',
    'mandi_cara_sebelum'        => $_POST['mandi_cara_sebelum'] ?? '',
    'mandi_cara_sekarang'       => $_POST['mandi_cara_sekarang'] ?? '',
    'mandi_tempat_sebelum'      => $_POST['mandi_tempat_sebelum'] ?? '',
    'mandi_tempat_sekarang'     => $_POST['mandi_tempat_sekarang'] ?? '',
    'rambut_frekuensi_sebelum'  => $_POST['rambut_frekuensi_sebelum'] ?? '',
    'rambut_frekuensi_sekarang' => $_POST['rambut_frekuensi_sekarang'] ?? '',
    'rambut_cara_sebelum'       => $_POST['rambut_cara_sebelum'] ?? '',
    'rambut_cara_sekarang'      => $_POST['rambut_cara_sekarang'] ?? '',
    'kuku_frekuensi_sebelum'    => $_POST['kuku_frekuensi_sebelum'] ?? '',
    'kuku_frekuensi_sekarang'   => $_POST['kuku_frekuensi_sekarang'] ?? '',
    'kuku_cara_sebelum'         => $_POST['kuku_cara_sebelum'] ?? '',
    'kuku_cara_sekarang'        => $_POST['kuku_cara_sekarang'] ?? '',
    'gigi_frekuensi_sebelum'    => $_POST['gigi_frekuensi_sebelum'] ?? '',
    'gigi_frekuensi_sekarang'   => $_POST['gigi_frekuensi_sekarang'] ?? '',
    'gigi_cara_sebelum'         => $_POST['gigi_cara_sebelum'] ?? '',
    'gigi_cara_sekarang'        => $_POST['gigi_cara_sekarang'] ?? '',
    'tanggal_pemeriksaan'       => $_POST['tanggal_pemeriksaan'] ?? '',
    'radiologi'                 => $_POST['radiologi'] ?? '',
    'data_penunjang_lain'       => $_POST['data_penunjang_lain'] ?? '',
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

<h5 class="card-title"><strong>5.	Pengkajian kebutuhan </strong></h5>
<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>a. Pola Aktivitas</strong></label>
</div>

 <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th><strong>Aktivitas</strong></th>
                                    <th class="text-center"><strong>0</strong></th>
                                    <th class="text-center"><strong>1</strong></th>
                                    <th class="text-center"><strong>2</strong></th>
                                    <th class="text-center"><strong>3</strong></th>
                                    <th class="text-center"><strong>4</strong></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $perawatan_fields = [
                                    'mandi'      => 'Mandi',
                                    'berpakaian' => 'Berpakaian / Berdandan',
                                    'mobilisasi' => 'Mobilisasi di TT',
                                    'pindah'     => 'Pindah',
                                    'ambulasi'   => 'Ambulasi',
                                    'makan'      => 'Makan / Minum',
                                ];
                                foreach ($perawatan_fields as $name => $label): ?>
                                    <tr>
                                        <td><strong><?= $label ?></strong></td>
                                        <?php for ($i = 0; $i <= 4; $i++): ?>
                                            <td class="text-center"><input type="radio" name="<?= $name ?>" value="<?= $i ?>" <?= $ro_disabled ?>
                                                    <?= ($existing_data[$name] ?? '') == $i ? 'checked' : '' ?>></td>
                                        <?php endfor; ?>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>

                        <small class="text-muted d-block text-end">
                            Skor 0 = Mandiri &nbsp;|&nbsp; Skor 1 = Dibantu sebagian &nbsp;|&nbsp; Skor 2 = Perlu bantuan orang lain <br>
                            Skor 3 = Bantuan orang lain dan alat &nbsp;|&nbsp; Skor 4 = Tergantung
                        </small>


                <div class="row mb-2">
                    <label class="col-sm-12 text-primary"><strong>b. Pola Kognitif dan Perceptual</strong></label>
                </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">
                                <strong>1. Nyeri (kualitas, intensitas, durasi, skala nyeri, cara mengurangi nyeri)</strong>
                            </label>
                            <div class="col-sm-9">
                                <textarea class="form-control" rows="4" cols="30" name="nyeri" style="display:block; overflow:hidden; resize: none;" <?= $ro ?>
                                    oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= htmlspecialchars($existing_data["nyeri"] ?? "") ?></textarea>

                            </div>
                        </div>


                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">
                                <strong>2. Fungsi panca indra (penglihatan, pendengaran, pengecapan, penghidu, perasa) menggunakan alat bantu?</strong>
                            </label>
                            <div class="col-sm-9">
                                <textarea class="form-control" rows="7" cols="30" name="panca_indra" style="display:block; overflow:hidden; resize: none;" <?= $ro ?>
                                    oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= htmlspecialchars($existing_data["panca_indra"] ?? "") ?></textarea>

                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">
                                <strong>3. Kemampuan berbicara</strong>
                            </label>
                            <div class="col-sm-9">
                                <textarea class="form-control" rows="2" cols="30" name="berbicara" style="display:block; overflow:hidden; resize: none;" <?= $ro ?>
                                    oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= htmlspecialchars($existing_data["berbicara"] ?? "") ?></textarea>

                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">
                                <strong>4. Kemampuan membaca</strong>
                            </label>
                            <div class="col-sm-9">
                                <textarea class="form-control" rows="2" cols="30" name="membaca" style="display:block; overflow:hidden; resize: none;" <?= $ro ?>
                                    oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= htmlspecialchars($existing_data["membaca"] ?? "") ?></textarea>

                            </div>
                        </div>

<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>c. Pola Nutrisi</strong></label>
</div>

<div class="row mb-4">
    <div class="col-sm-11">
        <div class="table-responsive">
            <table class="table table-bordered table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Kondisi</th>
                        <th>Sebelum</th>
                        <th>Saat Ini</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td><strong>Frekuensi Makan</strong> </td>
                        <td>
                            <input type="text" class="form-control" name="frekuensi_makan_sebelum"
                                   value="<?= val('frekuensi_makan_sebelum', $existing_data) ?>" <?= $ro ?>>
                        </td>
                        <td>
                            <input type="text" class="form-control" name="frekuensi_makan_sekarang"
                                   value="<?= val('frekuensi_makan_sekarang', $existing_data) ?>" <?= $ro ?>>
                        </td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td><strong>Selera Makan</strong> </td>
                        <td>
                            <input type="text" class="form-control" name="selera_makan_sebelum"
                                   value="<?= val('selera_makan_sebelum', $existing_data) ?>" <?= $ro ?>>
                        </td>
                        <td>
                            <input type="text" class="form-control" name="selera_makan_sekarang"
                                   value="<?= val('selera_makan_sekarang', $existing_data) ?>" <?= $ro ?>>
                        </td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td><strong>Menu Makanan</strong> </td>
                        <td>
                            <input type="text" class="form-control" name="menu_makan_sebelum"
                                   value="<?= val('menu_makan_sebelum', $existing_data) ?>" <?= $ro ?>>
                        </td>
                        <td>
                            <input type="text" class="form-control" name="menu_makan_sekarang"
                                   value="<?= val('menu_makan_sekarang', $existing_data) ?>" <?= $ro ?>>
                        </td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <td><strong>Ritual Saat Makan</strong> </td>
                        <td>
                            <input type="text" class="form-control" name="ritual_makan_sebelum"
                                   value="<?= val('ritual_makan_sebelum', $existing_data) ?>" <?= $ro ?>>
                        </td>
                        <td>
                            <input type="text" class="form-control" name="ritual_makan_sekarang"
                                   value="<?= val('ritual_makan_sekarang', $existing_data) ?>" <?= $ro ?>>
                        </td>
                    </tr>
                    <tr>
                        <td>5</td>
                        <td><strong>Bantuan Makan Parenteral</strong> </td>
                        <td>
                            <input type="text" class="form-control" name="bantuan_makan_sebelum"
                                   value="<?= val('bantuan_makan_sebelum', $existing_data) ?>" <?= $ro ?>>
                        </td>
                        <td>
                            <input type="text" class="form-control" name="bantuan_makan_sekarang"
                                   value="<?= val('bantuan_makan_sekarang', $existing_data) ?>" <?= $ro ?>>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>d. Cairan</strong></label>
</div>

<div class="row mb-4">
    <div class="col-sm-11">
        <div class="table-responsive">
            <table class="table table-bordered table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Kondisi</th>
                        <th>Sebelum Sakit</th>
                        <th>Saat Ini</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td><strong>Jenis Minuman</strong> </td>
                        <td>
                            <input type="text" class="form-control" name="jenis_minum_sebelum"
                                   value="<?= val('jenis_minum_sebelum', $existing_data) ?>" <?= $ro ?>>
                        </td>
                        <td>
                            <input type="text" class="form-control" name="jenis_minum_sekarang"
                                   value="<?= val('jenis_minum_sekarang', $existing_data) ?>" <?= $ro ?>>
                        </td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td> <strong>Jumlah Cairan</strong></td>
                        <td>
                            <input type="text" class="form-control" name="jumlah_cairan_sebelum"
                                   value="<?= val('jumlah_cairan_sebelum', $existing_data) ?>" <?= $ro ?>>
                        </td>
                        <td>
                            <input type="text" class="form-control" name="jumlah_cairan_sekarang"
                                   value="<?= val('jumlah_cairan_sekarang', $existing_data) ?>" <?= $ro ?>>
                        </td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td><strong>Cairan Parenteral</strong> </td>
                        <td>
                            <input type="text" class="form-control" name="bantuan_cairan_sebelum"
                                   value="<?= val('bantuan_cairan_sebelum', $existing_data) ?>" <?= $ro ?>>
                        </td>
                        <td>
                            <input type="text" class="form-control" name="bantuan_cairan_sekarang"
                                   value="<?= val('bantuan_cairan_sekarang', $existing_data) ?>" <?= $ro ?>>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

   <div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>e. Pola Eliminasi BAB</strong></label>
</div>
<div class="row mb-4">
    <div class="col-sm-11">
        <div class="table-responsive">
            <table class="table table-bordered table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th><strong>No</strong></th>
                        <th><strong>Kondisi</strong></th>
                        <th><strong>Sebelum Sakit</strong></th>
                        <th><strong>Saat Ini</strong></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td><strong>Frekuensi (Waktu)</strong></td>
                        <td>
                            <input type="text" class="form-control" name="bab_frekuensi_sebelum"
                                   value="<?= val('bab_frekuensi_sebelum', $existing_data) ?>" <?= $ro ?>>
                        </td>
                        <td>
                            <input type="text" class="form-control" name="bab_frekuensi_sekarang"
                                   value="<?= val('bab_frekuensi_sekarang', $existing_data) ?>" <?= $ro ?>>
                        </td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td><strong>Konsistensi</strong></td>
                        <td>
                            <input type="text" class="form-control" name="bab_konsistensi_sebelum"
                                   value="<?= val('bab_konsistensi_sebelum', $existing_data) ?>" <?= $ro ?>>
                        </td>
                        <td>
                            <input type="text" class="form-control" name="bab_konsistensi_sekarang"
                                   value="<?= val('bab_konsistensi_sekarang', $existing_data) ?>" <?= $ro ?>>
                        </td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td><strong>Warna</strong></td>
                        <td>
                            <input type="text" class="form-control" name="bab_warna_sebelum"
                                   value="<?= val('bab_warna_sebelum', $existing_data) ?>" <?= $ro ?>>
                        </td>
                        <td>
                            <input type="text" class="form-control" name="bab_warna_sekarang"
                                   value="<?= val('bab_warna_sekarang', $existing_data) ?>" <?= $ro ?>>
                        </td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <td><strong>Bau</strong></td>
                        <td>
                            <input type="text" class="form-control" name="bab_bau_sebelum"
                                   value="<?= val('bab_bau_sebelum', $existing_data) ?>" <?= $ro ?>>
                        </td>
                        <td>
                            <input type="text" class="form-control" name="bab_bau_sekarang"
                                   value="<?= val('bab_bau_sekarang', $existing_data) ?>" <?= $ro ?>>
                        </td>
                    </tr>
                    <tr>
                        <td>5</td>
                        <td><strong>Kesulitan saat BAB</strong></td>
                        <td>
                            <input type="text" class="form-control" name="bab_kesulitan_sebelum"
                                   value="<?= val('bab_kesulitan_sebelum', $existing_data) ?>" <?= $ro ?>>
                        </td>
                        <td>
                            <input type="text" class="form-control" name="bab_kesulitan_sekarang"
                                   value="<?= val('bab_kesulitan_sekarang', $existing_data) ?>" <?= $ro ?>>
                        </td>
                    </tr>
                    <tr>
                        <td>6</td>
                        <td><strong>Penggunaan Obat Pencahar</strong></td>
                        <td>
                            <input type="text" class="form-control" name="bab_obat_sebelum"
                                   value="<?= val('bab_obat_sebelum', $existing_data) ?>" <?= $ro ?>>
                        </td>
                        <td>
                            <input type="text" class="form-control" name="bab_obat_sekarang"
                                   value="<?= val('bab_obat_sekarang', $existing_data) ?>" <?= $ro ?>>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>f. Pola Eliminasi BAK</strong></label>
</div>
<div class="row mb-4">
    <div class="col-sm-11">
        <div class="table-responsive">
            <table class="table table-bordered table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th><strong>No</strong></th>
                        <th><strong>Kondisi</strong></th>
                        <th><strong>Sebelum Sakit</strong></th>
                        <th><strong>Saat Ini</strong></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td><strong>Frekuensi (Waktu)</strong></td>
                        <td>
                            <input type="text" class="form-control" name="bak_frekuensi_sebelum"
                                   value="<?= val('bak_frekuensi_sebelum', $existing_data) ?>" <?= $ro ?>>
                        </td>
                        <td>
                            <input type="text" class="form-control" name="bak_frekuensi_sekarang"
                                   value="<?= val('bak_frekuensi_sekarang', $existing_data) ?>" <?= $ro ?>>
                        </td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td><strong>Warna</strong></td>
                        <td>
                            <input type="text" class="form-control" name="bak_warna_sebelum"
                                   value="<?= val('bak_warna_sebelum', $existing_data) ?>" <?= $ro ?>>
                        </td>
                        <td>
                            <input type="text" class="form-control" name="bak_warna_sekarang"
                                   value="<?= val('', $existing_data) ?>" <?= $ro ?>>
                        </td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td><strong>Bau</strong></td>
                        <td>
                            <input type="text" class="form-control" name="bak_bau_sebelum"
                                   value="<?= val('bak_bau_sebelum', $existing_data) ?>" <?= $ro ?>>
                        </td>
                        <td>
                            <input type="text" class="form-control" name="bak_bau_sekarang"
                                   value="<?= val('bak_bau_sekarang', $existing_data) ?>" <?= $ro ?>>
                        </td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <td><strong>Kesulitan saat BAK</strong></td>
                        <td>
                            <input type="text" class="form-control" name="bak_kesulitan_sebelum"
                                   value="<?= val('bak_kesulitan_sebelum', $existing_data) ?>" <?= $ro ?>>
                        </td>
                        <td>
                            <input type="text" class="form-control" name="bak_kesulitan_sekarang"
                                   value="<?= val('bak_kesulitan_sekarang', $existing_data) ?>" <?= $ro ?>>
                        </td>
                    </tr>
                    <tr>
                        <td>5</td>
                        <td><strong>Penggunaan Obat Diuretik</strong></td>
                        <td>
                            <input type="text" class="form-control" name="bak_obat_sebelum"
                                   value="<?= val('bak_obat_sebelum', $existing_data) ?>" <?= $ro ?>>
                        </td>
                        <td>
                            <input type="text" class="form-control" name="bak_obat_sekarang"
                                   value="<?= val('bak_obat_sekarang', $existing_data) ?>" <?= $ro ?>>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>


<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>g. Pola Tidur</strong></label>
</div>
<div class="row mb-4">
    <div class="col-sm-11">
        <div class="table-responsive">
            <table class="table table-bordered table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th><strong>No</strong></th>
                        <th><strong>Kondisi</strong></th>
                        <th><strong>Sebelum Sakit</strong></th>
                        <th><strong>Saat Ini</strong></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td rowspan="2">1</td>
                        <td><strong>Jam Tidur - Siang</strong></td>
                        <td>
                            <input type="text" class="form-control" name="tidur_siang_sebelum"
                                   value="<?= val('tidur_siang_sebelum', $existing_data) ?>" <?= $ro ?>>
                        </td>
                        <td>
                            <input type="text" class="form-control" name="tidur_siang_sekarang"
                                   value="<?= val('tidur_siang_sekarang', $existing_data) ?>" <?= $ro ?>>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Jam Tidur - Malam</strong></td>
                        <td>
                            <input type="text" class="form-control" name="tidur_malam_sebelum"
                                   value="<?= val('tidur_malam_sebelum', $existing_data) ?>" <?= $ro ?>>
                        </td>
                        <td>
                            <input type="text" class="form-control" name="tidur_malam_sekarang"
                                   value="<?= val('tidur_malam_sekarang', $existing_data) ?>" <?= $ro ?>>
                        </td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td><strong>Kesulitan Tidur</strong></td>
                        <td>
                            <input type="text" class="form-control" name="kesulitan_tidur_sebelum"
                                   value="<?= val('kesulitan_tidur_sebelum', $existing_data) ?>" <?= $ro ?>>
                        </td>
                        <td>
                            <input type="text" class="form-control" name="kesulitan_tidur_sekarang"
                                   value="<?= val('kesulitan_tidur_sekarang', $existing_data) ?>" <?= $ro ?>>
                        </td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td><strong>Kebiasaan Sebelum Tidur</strong></td>
                        <td>
                            <input type="text" class="form-control" name="kebiasaan_tidur_sebelum"
                                   value="<?= val('kebiasaan_tidur_sebelum', $existing_data) ?>" <?= $ro ?>>
                        </td>
                        <td>
                            <input type="text" class="form-control" name="kebiasaan_tidur_sekarang"
                                   value="<?= val('kebiasaan_tidur_sekarang', $existing_data) ?>" <?= $ro ?>>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>


<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>n. Pola Personal Hygiene</strong></label>
</div>
<div class="row mb-4">
    <div class="col-sm-11">
        <div class="table">
            <table class="table table-bordered table-hover mb-1">
                <thead class="table-light">
                    <tr>
                        <th><strong>No</strong></th>
                        <th><strong>Kondisi</strong></th>
                        <th><strong>Sebelum Sakit</strong></th>
                        <th><strong>Saat Ini</strong></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td rowspan="3">1</td>
                        <td><strong>Mandi - Frekuensi</strong></td>
                        <td><input type="text" class="form-control" name="mandi_frekuensi_sebelum"
                                   value="<?= val('mandi_frekuensi_sebelum', $existing_data) ?>" <?= $ro ?>></td>
                        <td><input type="text" class="form-control" name="mandi_frekuensi_sekarang"
                                   value="<?= val('mandi_frekuensi_sekarang', $existing_data) ?>" <?= $ro ?>></td>
                    </tr>
                    <tr>
                        <td><strong>Mandi - Cara</strong></td>
                        <td><input type="text" class="form-control" name="mandi_cara_sebelum"
                                   value="<?= val('mandi_cara_sebelum', $existing_data) ?>" <?= $ro ?>></td>
                        <td><input type="text" class="form-control" name="mandi_cara_sekarang"
                                   value="<?= val('mandi_cara_sekarang', $existing_data) ?>" <?= $ro ?>></td>
                    </tr>
                    <tr>
                        <td><strong>Mandi - Tempat</strong></td>
                        <td><input type="text" class="form-control" name="mandi_tempat_sebelum"
                                   value="<?= val('mandi_tempat_sebelum', $existing_data) ?>" <?= $ro ?>></td>
                        <td><input type="text" class="form-control" name="mandi_tempat_sekarang"
                                   value="<?= val('mandi_tempat_sekarang', $existing_data) ?>" <?= $ro ?>></td>
                    </tr>
                    <tr>
                        <td rowspan="2">2</td>
                        <td><strong>Cuci Rambut - Frekuensi</strong></td>
                        <td><input type="text" class="form-control" name="rambut_frekuensi_sebelum"
                                   value="<?= val('rambut_frekuensi_sebelum', $existing_data) ?>" <?= $ro ?>></td>
                        <td><input type="text" class="form-control" name="rambut_frekuensi_sekarang"
                                   value="<?= val('rambut_frekuensi_sekarang', $existing_data) ?>" <?= $ro ?>></td>
                    </tr>
                    <tr>
                        <td><strong>Cuci Rambut - Cara</strong></td>
                        <td><input type="text" class="form-control" name="rambut_cara_sebelum"
                                   value="<?= val('rambut_cara_sebelum', $existing_data) ?>" <?= $ro ?>></td>
                        <td><input type="text" class="form-control" name="rambut_cara_sekarang"
                                   value="<?= val('rambut_cara_sekarang', $existing_data) ?>" <?= $ro ?>></td>
                    </tr>
                    <tr>
                        <td rowspan="2">3</td>
                        <td><strong>Gunting Kuku - Frekuensi</strong></td>
                        <td><input type="text" class="form-control" name="kuku_frekuensi_sebelum"
                                   value="<?= val('kuku_frekuensi_sebelum', $existing_data) ?>" <?= $ro ?>></td>
                        <td><input type="text" class="form-control" name="kuku_frekuensi_sekarang"
                                   value="<?= val('kuku_frekuensi_sekarang', $existing_data) ?>" <?= $ro ?>></td>
                    </tr>
                    <tr>
                        <td><strong>Gunting Kuku - Cara</strong></td>
                        <td><input type="text" class="form-control" name="kuku_cara_sebelum"
                                   value="<?= val('kuku_cara_sebelum', $existing_data) ?>" <?= $ro ?>></td>
                        <td><input type="text" class="form-control" name="kuku_cara_sekarang"
                                   value="<?= val('kuku_cara_sekarang', $existing_data) ?>" <?= $ro ?>></td>
                    </tr>
                    <tr>
                        <td rowspan="2">4</td>
                        <td><strong>Gosok Gigi - Frekuensi</strong></td>
                        <td><input type="text" class="form-control" name="gigi_frekuensi_sebelum"
                                   value="<?= val('gigi_frekuensi_sebelum', $existing_data) ?>" <?= $ro ?>></td>
                        <td><input type="text" class="form-control" name="gigi_frekuensi_sekarang"
                                   value="<?= val('gigi_frekuensi_sekarang', $existing_data) ?>" <?= $ro ?>></td>
                    </tr>
                    <tr>
                        <td><strong>Gosok Gigi - Cara</strong></td>
                        <td><input type="text" class="form-control" name="gigi_cara_sebelum"
                                   value="<?= val('gigi_cara_sebelum', $existing_data) ?>" <?= $ro ?>></td>
                        <td><input type="text" class="form-control" name="gigi_cara_sekarang"
                                   value="<?= val('gigi_cara_sekarang', $existing_data) ?>" <?= $ro ?>></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>


<div class="row mb-2 mt-4">
    <label class="col-sm-12 text-primary"><strong>c. Data Penunjang</strong></label>
</div>
   <div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>Tanggal Pemeriksaan</strong>
    </div>
    <div class="col-sm-9">
        <input type="date" class="form-control" name="tanggal_pemeriksaan" value="<?= val('tanggal_pemeriksaan', $existing_data) ?>" <?= $ro ?>>
       
</div>
</div>
 <p class="text-primary fw-bold mb-2">1) Laboratorium</p>

                    <table class="table table-bordered" id="tabel-diagnosa">
                        <thead>
                            <tr>
                                <th class="text-center" style="width:40px">No</th>
                                <th class="text-center">Nama Pemeriksaan</th>
                                <th class="text-center" style="width:180px">Hasil</th>
                                <th class="text-center" style="width:180px">Satuan</th>
                                <th class="text-center" style="width:180px">Nilai Rujukan</th>
                                <th class="text-center" style="width:60px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="tbody-diagnosa">
                            <!-- Dynamic rows masuk sini -->
                        </tbody>
                    </table>

                    <?php if (!$is_readonly): ?>
                        <div class="row mb-4">
                            <div class="col-sm-12 d-flex justify-content-end">
                                <button type="button" class="btn btn-primary btn-sm" onclick="tambahRowDiagnosa()">+ Tambah Diagnosa</button>
                            </div>
                        </div>
                    <?php endif; ?>


<div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>2) Radiologi (Tgl Pemeriksaan & Hasil)</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="radiologi" value="<?= val('radiologi', $existing_data) ?>" <?= $ro ?>>
        
</div>
</div>

 <div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>3) Lainnya (USG, CT Scan, dll)</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="data_penunjang_lain" value="<?= val('data_penunjang_lain', $existing_data) ?>" <?= $ro ?>>
        
</div>
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

         <script>
                        let rowDiagnosaCount = 1;


                        const existingDiagnosa = <?= json_encode($existing_diagnosa) ?>;


                        // ---- DIAGNOSA ----
                        function tambahRowDiagnosa(data = null) {
                            const tbody = document.getElementById('tbody-diagnosa');
                            const index = rowDiagnosaCount;
                            const row = document.createElement('tr');
                            const isReadonly = <?= json_encode($is_readonly) ?>;
                            row.innerHTML = `
                                <td class="text-center align-middle">${index}</td>
                                <td>
                                    <textarea
                                        class="form-control form-control-sm"
                                        name="diagnosa[${index}][pemeriksaan]"
                                        rows="2"
                                        style="resize:none; overflow:hidden;"
                                        oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                        ${isReadonly ? 'readonly' : ''}
                                    >${data?.pemeriksaan ?? ''}</textarea>
                                </td>
                                <td>
                                    <input
                                        type="text"
                                        class="form-control form-control-sm"
                                        name="diagnosa[${index}][hasil]"
                                        value="${data?.hasil ?? ''}"
                                        ${isReadonly ? 'readonly' : ''}
                                    >
                                </td>
                                <td>
                                    <input
                                        type="text"
                                        class="form-control form-control-sm"
                                        name="diagnosa[${index}][satuan]"
                                        value="${data?.satuan ?? ''}"
                                        ${isReadonly ? 'readonly' : ''}
                                    >
                                </td>
                                <td>
                                    <input
                                        type="text"
                                        class="form-control form-control-sm"
                                        name="diagnosa[${index}][nilai]"
                                        value="${data?.nilai ?? ''}"
                                        ${isReadonly ? 'readonly' : ''}
                                    >
                                </td>
                                <td class="text-center align-middle">
                                    ${!isReadonly ? `<button type="button" class="btn btn-danger btn-sm" onclick="hapusRow(this)">x</button>` : ''}
                                </td>
                            `;
                            tbody.appendChild(row);
                            rowDiagnosaCount++;
                        }

                    

                        function hapusRow(btn) {
                            btn.closest('tr').remove();
                        }

                        // Load existing rows on page load
                        window.addEventListener('load', function() {
                            if (existingDiagnosa && existingDiagnosa.length > 0) {
                                existingDiagnosa.forEach(row => tambahRowDiagnosa(row));
                            } else {
                                tambahRowDiagnosa();
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