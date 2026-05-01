<?php
require_once "koneksi.php";
require_once "utils.php";

$form_id       = 17;
$level         = $_SESSION['level'];
$user_id       = $_SESSION['id_user'];
$section_name  = 'pemeriksaan_fisik';
$section_label = 'Pemeriksaan Fisik';

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
    'bentuk_kepala'            => $_POST['bentuk_kepala'] ?? '',
    'nyeri_tekan_dada'         => $_POST['nyeri_tekan_dada'] ?? '',
    'benjolan_dada'            => $_POST['benjolan_dada'] ?? '',
    'penyebaran_merata'        => $_POST['penyebaran_merata'] ?? '',
    'warna_rambut'             => $_POST['warna_rambut'] ?? '',
    'rambut_dicabut'           => $_POST['rambut_dicabut'] ?? '',
    'kelainan_rambut'          => $_POST['kelainan_rambut'] ?? '',
    'ekspresi_wajah'           => $_POST['ekspresi_wajah'] ?? '',
    'simetris_wajah'           => $_POST['simetris_wajah'] ?? '',
    'udema_wajah'              => $_POST['udema_wajah'] ?? '',
    'kelainan_wajah'           => $_POST['kelainan_wajah'] ?? '',
    'penglihatan'              => $_POST['penglihatan'] ?? '',
    'kanan'                    => $_POST['kanan'] ?? '',
    'kiri'                     => $_POST['kiri'] ?? '',
    'lapang_pandang'           => $_POST['lapang_pandang'] ?? '',
    'keadaan_mata'             => $_POST['keadaan_mata'] ?? '',
    'konjungtiva'              => $_POST['konjungtiva'] ?? '',
    'lesi_mata'                => $_POST['lesi_mata'] ?? '',
    'sclera'                   => $_POST['sclera'] ?? '',
    'pupil'                    => $_POST['pupil'] ?? '',
    'bola_mata'                => $_POST['bola_mata'] ?? '',
    'kelainan_mata'            => $_POST['kelainan_mata'] ?? '',
    'pendengaran_kiri'         => $_POST['pendengaran_kiri'] ?? '',
    'pendengaran_kanan'        => $_POST['pendengaran_kanan'] ?? '',
    'nyeri_kiri'               => $_POST['nyeri_kiri'] ?? '',
    'nyeri_kanan'              => $_POST['nyeri_kanan'] ?? '',
    'serumen'                  => $_POST['serumen'] ?? '',
    'kelainan_telinga'         => $_POST['kelainan_telinga'] ?? '',
    'bau'                      => $_POST['bau'] ?? '',
    'sekresi'                  => $_POST['sekresi'] ?? '',
    'warna_hidung'             => $_POST['warna_hidung'] ?? '',
    'mukosa_hidung'            => $_POST['mukosa_hidung'] ?? '',
    'pembengkakan'             => $_POST['pembengkakan'] ?? '',
    'cuping_hidung'            => $_POST['cuping_hidung'] ?? '',
    'kelainan_hidung'          => $_POST['kelainan_hidung'] ?? '',
    'bibir'                    => $_POST['bibir'] ?? '',
    'simetris'                 => $_POST['simetris'] ?? '',
    'kelembaban'               => $_POST['kelembaban'] ?? '',
    'caries'                   => $_POST['caries'] ?? '',
    'caries1'                   => $_POST['caries1'] ?? '',
    'jumlah_gigi'              => $_POST['jumlah_gigi'] ?? '',
    'warna_gigi'               => $_POST['warna_gigi'] ?? '',
    'frekuensi'                => $_POST['frekuensi'] ?? '',
    'letak'                    => $_POST['letak'] ?? '',
    'lidah'                    => $_POST['lidah'] ?? '',
    'lesi_lidah'               => $_POST['lesi_lidah'] ?? '',
    'panas_dingin'             => $_POST['panas_dingin'] ?? '',
    'asampahit'                => $_POST['asampahit'] ?? '',
    'manis'                    => $_POST['manis'] ?? '',
    'refleks'                  => $_POST['refleks'] ?? '',
    'tonsil'                   => $_POST['tonsil'] ?? '',
    'bau_mulut'                => $_POST['bau_mulut'] ?? [],
    'sekresi_hidung'           => $_POST['sekresi_hidung'] ?? '',
    'warna'                    => $_POST['warna'] ?? '',
    'leher_simetris'           => $_POST['leher_simetris'] ?? '',
    'kelenjar'                 => $_POST['kelenjar'] ?? '',
    'jvp'                      => $_POST['jvp'] ?? '',
    'refleks_menelan'          => $_POST['refleks_menelan'] ?? '',
    'kelainan_leher'           => $_POST['kelainan_leher'] ?? '',
    'bentuk_dada'              => $_POST['bentuk_dada'] ?? '',
    'pengembangan_dada'        => $_POST['pengembangan_dada'] ?? '',
    'perbandingan_dada'        => $_POST['perbandingan_dada'] ?? '',
    'otot_pernafasan'          => $_POST['otot_pernafasan'] ?? '',
    'frekuensi_nafas'          => $_POST['frekuensi_nafas'] ?? '',
    'teratur_nafas1'            => $_POST['teratur_nafas1'] ?? '',
    'teratur_nafas'            => $_POST['teratur_nafas'] ?? '',
    'irama_nafas'              => $_POST['irama_nafas'] ?? '',
    'sesak_nafas'              => $_POST['sesak_nafas'] ?? '',
    'taktil_fremitus'          => $_POST['taktil_fremitus'] ?? '',
    'perkusi_paru'             => $_POST['perkusi_paru'] ?? '',
    'bunyi_abnormal'           => $_POST['bunyi_abnormal'] ?? '',
    'abnormal'                 => $_POST['abnormal'] ?? '',
    's1_jantung'               => $_POST['s1_jantung'] ?? '',
    's2_jantung'               => $_POST['s2_jantung'] ?? '',
    'bunyi_jantung'            => $_POST['bunyi_jantung'] ?? '',
    'bunyi_tambahan'           => $_POST['bunyi_tambahan'] ?? '',
    'pulsasi_jantung'          => $_POST['pulsasi_jantung'] ?? '',
    'irama_jantung'            => $_POST['irama_jantung'] ?? '',
    'bentuk_abdomen'           => $_POST['bentuk_abdomen'] ?? [],
    'keadaan_abdomen'          => $_POST['keadaan_abdomen'] ?? [],
    'bising_usus'              => $_POST['bising_usus'] ?? '',
    'frekuensi1'                => $_POST['frekuensi1'] ?? '',
    'benjolan_abdomen'         => $_POST['benjolan_abdomen'] ?? '',
    'letak1' => $_POST['letak1'] ?? '',
    'nyeri_abdomen'            => $_POST['nyeri_abdomen'] ?? '',
    'frekuensi_tekan'            => $_POST['frekuensi_tekan'] ?? '',
    'perkusi_abdomen'          => $_POST['perkusi_abdomen'] ?? '',
    'kelainan_abdomen'         => $_POST['kelainan_abdomen'] ?? '',
    'bentuk_genetalia'         => $_POST['bentuk_genetalia'] ?? '',
    'radang_genetalia'         => $_POST['radang_genetalia'] ?? '',
    'sekret_genetalia'         => $_POST['sekret_genetalia'] ?? '',
    'skrotum_bengkak'          => $_POST['skrotum_bengkak'] ?? '',
    'rektum_benjolan'          => $_POST['rektum_benjolan'] ?? '',
    'lesi_genetalia'           => $_POST['lesi_genetalia'] ?? '',
    'kelainan_genetalia'       => $_POST['kelainan_genetalia'] ?? '',
    'atas_simetris'            => $_POST['atas_simetris'] ?? '',
    'sensasi_halus'            => $_POST['sensasi_halus'] ?? '',
    'sensasi_tajam'            => $_POST['sensasi_tajam'] ?? '',
    'sensasi_panas'            => $_POST['sensasi_panas'] ?? '',
    'sensasi_dingin'           => $_POST['sensasi_dingin'] ?? '',
    'rom_atas'                 => $_POST['rom_atas'] ?? '',
    'refleks_bisep'                 => $_POST['refleks_bisep'] ?? '',
    'refleks_trisep'                 => $_POST['refleks_trisep'] ?? '',
    'refleks_babinski'         => $_POST['refleks_babinski'] ?? '',
    'pembengkakan2'             => $_POST['pembengkakan2'] ?? '',
    'varises'                  => $_POST['varises'] ?? '',
    'kelembaban1'               => $_POST['kelembaban1'] ?? '',
    'temperatur'               => $_POST['temperatur'] ?? '',
    'kanan1'                    => $_POST['kanan1'] ?? '',
    'kiri1'                     => $_POST['kiri1'] ?? '',
    'kelainan_genetalia1'       => $_POST['kelainan_genetalia1'] ?? '',
    
    'clubbing_finger'          => $_POST['clubbing_finger'] ?? '',
    'capillary_refill_time'    => $_POST['capillary_refill_time'] ?? '',
    'keadaan_kuku'             => $_POST['keadaan_kuku'] ?? '',
    'nervus1_penciuman'        => $_POST['nervus1_penciuman'] ?? '',
    'nervus2_penglihatan'      => $_POST['nervus2_penglihatan'] ?? '',
    'konstriksi_pupil'         => $_POST['konstriksi_pupil'] ?? '',
    'gerakan_kelopak'          => $_POST['gerakan_kelopak'] ?? '',
    'gerakan_bola_mata'        => $_POST['gerakan_bola_mata'] ?? '',
    'gerakan_mata_bawah'       => $_POST['gerakan_mata_bawah'] ?? '',
    'refleks_dagu'             => $_POST['refleks_dagu'] ?? '',
    'refleks_cornea'           => $_POST['refleks_cornea'] ?? '',
    'pengecapan_depan'         => $_POST['pengecapan_depan'] ?? '',
    'fungsi_pendengaran'       => $_POST['fungsi_pendengaran'] ?? '',
    'refleks_menelan1'          => $_POST['refleks_menelan1'] ?? '',
    'refleks_muntah'           => $_POST['refleks_muntah'] ?? '',
    'pengecapan_belakang'      => $_POST['pengecapan_belakang'] ?? '',
    'suara_pasien'             => $_POST['suara_pasien'] ?? '',
    'gerakan_kepala'           => $_POST['gerakan_kepala'] ?? '',
    'angkat_bahu'              => $_POST['angkat_bahu'] ?? '',
    'deviasi_lidah'            => $_POST['deviasi_lidah'] ?? '',
    'kaku_kuduk'               => $_POST['kaku_kuduk'] ?? '',
    'kernig_sign'              => $_POST['kernig_sign'] ?? '',
    'refleks_brudzinski'       => $_POST['refleks_brudzinski'] ?? '',
    'warna_kulit'            => $_POST['warna_kulit'] ?? '',
    'turgor_kulit'           => $_POST['turgor_kulit'] ?? '',
    'kelembaban2'             => $_POST['kelembaban2'] ?? '',
    'edema_kulit'            => $_POST['edema_kulit'] ?? '',
    'pada_daerah'            => $_POST['pada_daerah'] ?? '',
    'luka_kulit'             => $_POST['luka_kulit'] ?? '',
    'karakteristik_luka'     => $_POST['karakteristik_luka'] ?? '',
    'tekstur_kulit'          => $_POST['tekstur_kulit'] ?? '',
    'kelainan_kulit'         => $_POST['kelainan_kulit'] ?? '',
     'luka_kulit1'             => $_POST['luka_kulit1'] ?? '',
     'pada_daerah1'             => $_POST['pada_daerah1'] ?? '',
    'bawah_simetris'           => $_POST['bawah_simetris'] ?? '',
    'sensasi_bawah'            => $_POST['sensasi_bawah'] ?? '',
    'bawah_tajam'            => $_POST['sensasi_tajam'] ?? '',
    'sensasi_panasb'           => $_POST['sensasi_panasb'] ?? '',
    'sensasi_dinginb'          => $_POST['sensasi_dinginb'] ?? '',
    'rom_bawah'                => $_POST['rom_bawah'] ?? '',
    'refleks_babinski1'         => $_POST['refleks_babinski1'] ?? '',
    'pembengkakan2'            => $_POST['pembengkakan2'] ?? '',
    'varises1'                  => $_POST['varises1'] ?? '',
    'kelembaban3'              => $_POST['kelembaban3'] ?? '',
    'temperaturb'              => $_POST['temperaturb'] ?? '',
    'kanankaki'                    => $_POST['kanankaki'] ?? '',
    'kirikaki'                     => $_POST['kirikaki'] ?? '',
    'kelainan_genetalia2'      => $_POST['kelainan_genetalia2'] ?? ''
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
$ro_disabled = $is_readonly ? 'disabled' : '';
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

            <h5 class="card-title"><strong>4. Pemeriksaan fisik</strong></h5>
<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>a. Kepala</strong></label>
</div>

<div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>Bentuk Kepala</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="bentuk_kepala" value="<?= val('bentuk_kepala', $existing_data) ?>" <?= $ro ?>>
    
    </div>
</div><!-- Nyeri Dada -->
<div class="row mb-2">
    <div class="col-sm-2"><strong>Ada nyeri tekan:</strong></div>
    <div class="col-sm-4">
        <select class="form-select" name="nyeri_tekan_dada" style="max-width:200px" <?= $ro_select ?>>
            <option value="">Pilih</option>
            <option value="ya" <?= ($existing_data['nyeri_tekan_dada'] ?? '') === 'ya' ? 'selected' : '' ?>>Ya</option>
            <option value="tidak" <?= ($existing_data['nyeri_tekan_dada'] ?? '') === 'tidak' ? 'selected' : '' ?>>Tidak</option>
        </select>
    </div>
</div>

<!-- Benjolan Dada -->
<div class="row mb-2">
    <div class="col-sm-2"><strong>Ada benjolan:</strong></div>
    <div class="col-sm-4">
        <select class="form-select" name="benjolan_dada" style="max-width:200px" <?= $ro_select ?>>
            <option value="">Pilih</option>
            <option value="ya" <?= ($existing_data['benjolan_dada'] ?? '') === 'ya' ? 'selected' : '' ?>>Ya</option>
            <option value="tidak" <?= ($existing_data['benjolan_dada'] ?? '') === 'tidak' ? 'selected' : '' ?>>Tidak</option>
        </select>
    </div>
</div>
<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>b. Rambut</strong></label>
</div>

<div class="row mb-2">
    <div class="col-sm-2"><strong>Penyebaran Merata</strong></div>
    <div class="col-sm-4">
        <select class="form-select" name="penyebaran_merata" style="max-width:200px" <?= $ro_select ?>>
            <option value="">Pilih</option>
            <option value="ya" <?= ($existing_data['penyebaran_merata'] ?? '') === 'ya' ? 'selected' : '' ?>>Ya</option>
            <option value="tidak" <?= ($existing_data['penyebaran_merata'] ?? '') === 'tidak' ? 'selected' : '' ?>>Tidak</option>
        </select>
    </div>
</div>

<div class="row mb-2">
    <div class="col-sm-2"><strong>Warna</strong></div>
    <div class="col-sm-4">
        <select class="form-select" name="warna_rambut" style="max-width:200px" <?= $ro_select ?>>
            <option value="">Pilih</option>
            <option value="hitam" <?= ($existing_data['warna_rambut'] ?? '') === 'hitam' ? 'selected' : '' ?>>Hitam</option>
            <option value="coklat" <?= ($existing_data['warna_rambut'] ?? '') === 'coklat' ? 'selected' : '' ?>>Coklat</option>
            <option value="blonde" <?= ($existing_data['warna_rambut'] ?? '') === 'blonde' ? 'selected' : '' ?>>Blonde</option>
            <option value="putih" <?= ($existing_data['warna_rambut'] ?? '') === 'putih' ? 'selected' : '' ?>>Putih</option>
        </select>
    </div>
</div>

<div class="row mb-2">
    <div class="col-sm-2"><strong>Mudah Dicabut</strong></div>
    <div class="col-sm-4">
        <select class="form-select" name="rambut_dicabut" style="max-width:200px" <?= $ro_select ?>>
            <option value="">Pilih</option>
            <option value="ya" <?= ($existing_data['rambut_dicabut'] ?? '') === 'ya' ? 'selected' : '' ?>>Ya</option>
            <option value="tidak" <?= ($existing_data['rambut_dicabut'] ?? '') === 'tidak' ? 'selected' : '' ?>>Tidak</option>
        </select>
    </div>
</div>

<div class="row mb-3">
    <div class="col-sm-2 col-form-label"><strong>Kelainan</strong></div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="kelainan_rambut" value="<?= $existing_data['kelainan_rambut'] ?? '' ?>" <?= $ro ?>>
    </div>
</div>
    <div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>c. Wajah</strong></label>
</div>

<div class="row mb-3">
    <div class="col-sm-2 col-form-label"><strong>Ekspresi Wajah</strong></div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="ekspresi_wajah" value="<?= $existing_data['ekspresi_wajah'] ?? '' ?>" <?= $ro ?>>
    </div>
</div>

<!-- Kesimetrisan Wajah -->
<div class="row mb-2">
    <div class="col-sm-2"><strong>Kesimetrisan Wajah</strong></div>
    <div class="col-sm-4">
        <select class="form-select" name="simetris_wajah" style="max-width:200px" <?= $ro_select ?>>
            <option value="">Pilih</option>
            <option value="ya" <?= ($existing_data['simetris_wajah'] ?? '') === 'ya' ? 'selected' : '' ?>>Ya</option>
            <option value="tidak" <?= ($existing_data['simetris_wajah'] ?? '') === 'tidak' ? 'selected' : '' ?>>Tidak</option>
        </select>
    </div>
</div>

<!-- Udema Wajah -->
<div class="row mb-2">
    <div class="col-sm-2"><strong>Terdapat Udema</strong></div>
    <div class="col-sm-4">
        <select class="form-select" name="udema_wajah" style="max-width:200px" <?= $ro_select ?>>
            <option value="">Pilih</option>
            <option value="ya" <?= ($existing_data['udema_wajah'] ?? '') === 'ya' ? 'selected' : '' ?>>Ya</option>
            <option value="tidak" <?= ($existing_data['udema_wajah'] ?? '') === 'tidak' ? 'selected' : '' ?>>Tidak</option>
        </select>
    </div>
</div>

<!-- Kelainan Wajah -->
<div class="row mb-3">
    <div class="col-sm-2 col-form-label"><strong>Kelainan</strong></div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="kelainan_wajah" value="<?= $existing_data['kelainan_wajah'] ?? '' ?>" <?= $ro ?>>
    </div>
</div>
                <div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>d. Mata</strong></label>
</div>

<!-- Penglihatan -->
<div class="row mb-2">
    <div class="col-sm-2"><strong>Penglihatan</strong></div>
    <div class="col-sm-4">
        <select class="form-select" name="penglihatan" style="max-width:200px" <?= $ro_select ?>>
            <option value="">Pilih</option>
            <option value="jelas" <?= ($existing_data['penglihatan'] ?? '') === 'jelas' ? 'selected' : '' ?>>Jelas</option>
            <option value="kabur" <?= ($existing_data['penglihatan'] ?? '') === 'kabur' ? 'selected' : '' ?>>Kabur</option>
            <option value="rabun" <?= ($existing_data['penglihatan'] ?? '') === 'rabun' ? 'selected' : '' ?>>Rabun</option>
            <option value="berkunang" <?= ($existing_data['penglihatan'] ?? '') === 'berkunang' ? 'selected' : '' ?>>Berkunang</option>
        </select>
    </div>
</div>

<!-- Visus -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Visus</strong></label>
    <div class="col-sm-9">
        <div class="row">
            <!-- Kanan -->
            <div class="col-md-4 d-flex align-items-center">
                <label class="me-2"><strong>Kanan</strong></label>
                <input type="text" class="form-control" name="kanan" value="<?= $existing_data['kanan'] ?? '' ?>" <?= $ro ?>>
            </div>

            <!-- Kiri -->
            <div class="col-md-4 d-flex align-items-center">
                <label class="me-2"><strong>Kiri</strong></label>
                <input type="text" class="form-control" name="kiri" value="<?= $existing_data['kiri'] ?? '' ?>" <?= $ro ?>>
            </div>
        </div>
    </div>
</div>

<!-- Lapang Pandang -->
<div class="row mb-3">
    <div class="col-sm-2 col-form-label"><strong>Lapang Pandang</strong></div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="lapang_pandang" value="<?= $existing_data['lapang_pandang'] ?? '' ?>" <?= $ro ?>>
    </div>
</div>

<!-- Keadaan Mata -->
<div class="row mb-3">
    <div class="col-sm-2 col-form-label"><strong>Keadaan Mata</strong></div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="keadaan_mata" value="<?= $existing_data['keadaan_mata'] ?? '' ?>" <?= $ro ?>>
    </div>
</div>

<!-- Konjungtiva -->
<div class="row mb-2">
    <div class="col-sm-2"><strong>Konjungtiva</strong></div>
    <div class="col-sm-4">
        <select class="form-select" name="konjungtiva" style="max-width:200px" <?= $ro_select ?>>
            <option value="">Pilih</option>
            <option value="anemis" <?= ($existing_data['konjungtiva'] ?? '') === 'anemis' ? 'selected' : '' ?>>Anemis</option>
            <option value="ananenmis" <?= ($existing_data['konjungtiva'] ?? '') === 'ananenmis' ? 'selected' : '' ?>>Ananenmis</option>
        </select>
    </div>
</div>

<!-- Lesi Mata -->
<div class="row mb-2">
    <div class="col-sm-2"><strong>Lesi</strong></div>
    <div class="col-sm-4">
        <select class="form-select" name="lesi_mata" style="max-width:200px" <?= $ro_select ?>>
            <option value="">Pilih</option>
            <option value="ada" <?= ($existing_data['lesi_mata'] ?? '') === 'ada' ? 'selected' : '' ?>>Ada</option>
            <option value="tidak" <?= ($existing_data['lesi_mata'] ?? '') === 'tidak' ? 'selected' : '' ?>>Tidak</option>
        </select>
    </div>
</div>

<!-- Sclera -->
<div class="row mb-3">
    <div class="col-sm-2 col-form-label"><strong>Sclera</strong></div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="sclera" value="<?= $existing_data['sclera'] ?? '' ?>" <?= $ro ?>>
    </div>
</div>

<!-- Reaksi Pupil -->
<div class="row mb-2">
    <div class="col-sm-2"><strong>Reaksi Pupil</strong></div>
    <div class="col-sm-4">
        <select class="form-select" name="pupil" style="max-width:200px" <?= $ro_select ?>>
            <option value="">Pilih</option>
            <option value="isokor" <?= ($existing_data['pupil'] ?? '') === 'isokor' ? 'selected' : '' ?>>Isokor</option>
            <option value="anisokor" <?= ($existing_data['pupil'] ?? '') === 'anisokor' ? 'selected' : '' ?>>Anisokor</option>
        </select>
    </div>
</div>

<!-- Bola Mata -->
<div class="row mb-2">
    <div class="col-sm-2"><strong>Bola Mata</strong></div>
    <div class="col-sm-4">
        <select class="form-select" name="bola_mata" style="max-width:200px" <?= $ro_select ?>>
            <option value="">Pilih</option>
            <option value="simetris" <?= ($existing_data['bola_mata'] ?? '') === 'simetris' ? 'selected' : '' ?>>Simetris</option>
            <option value="tidak" <?= ($existing_data['bola_mata'] ?? '') === 'tidak' ? 'selected' : '' ?>>Tidak</option>
        </select>
    </div>
</div>

<!-- Kelainan Mata -->
<div class="row mb-3">
    <div class="col-sm-2 col-form-label"><strong>Kelainan</strong></div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="kelainan_mata" value="<?= $existing_data['kelainan_mata'] ?? '' ?>" <?= $ro ?>>
    </div>
</div>
 <div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>e. Telinga</strong></label>
</div>

<!-- Pendengaran Kiri -->
<div class="row mb-2">
    <div class="col-sm-2"><strong>Pendengaran Kiri</strong></div>
    <div class="col-sm-4">
        <select class="form-select" name="pendengaran_kiri" style="max-width:200px" <?= $ro_select ?>>
            <option value="">Pilih</option>
            <option value="jelas" <?= ($existing_data['pendengaran_kiri'] ?? '') === 'jelas' ? 'selected' : '' ?>>Jelas</option>
            <option value="berkurang" <?= ($existing_data['pendengaran_kiri'] ?? '') === 'berkurang' ? 'selected' : '' ?>>Berkurang</option>
        </select>
    </div>
</div>

<!-- Pendengaran Kanan -->
<div class="row mb-2">
    <div class="col-sm-2"><strong>Pendengaran Kanan</strong></div>
    <div class="col-sm-4">
        <select class="form-select" name="pendengaran_kanan" style="max-width:200px" <?= $ro_select ?>>
            <option value="">Pilih</option>
            <option value="jelas" <?= ($existing_data['pendengaran_kanan'] ?? '') === 'jelas' ? 'selected' : '' ?>>Jelas</option>
            <option value="berkurang" <?= ($existing_data['pendengaran_kanan'] ?? '') === 'berkurang' ? 'selected' : '' ?>>Berkurang</option>
        </select>
    </div>
</div>

<!-- Nyeri Kiri -->
<div class="row mb-2">
    <div class="col-sm-2"><strong>Nyeri Kiri</strong></div>
    <div class="col-sm-4">
        <select class="form-select" name="nyeri_kiri" style="max-width:200px" <?= $ro_select ?>>
            <option value="">Pilih</option>
            <option value="ada" <?= ($existing_data['nyeri_kiri'] ?? '') === 'ada' ? 'selected' : '' ?>>Ada</option>
            <option value="tidak" <?= ($existing_data['nyeri_kiri'] ?? '') === 'tidak' ? 'selected' : '' ?>>Tidak</option>
        </select>
    </div>
</div>

<!-- Nyeri Kanan -->
<div class="row mb-2">
    <div class="col-sm-2"><strong>Nyeri Kanan</strong></div>
    <div class="col-sm-4">
        <select class="form-select" name="nyeri_kanan" style="max-width:200px" <?= $ro_select ?>>
            <option value="">Pilih</option>
            <option value="ada" <?= ($existing_data['nyeri_kanan'] ?? '') === 'ada' ? 'selected' : '' ?>>Ada</option>
            <option value="tidak" <?= ($existing_data['nyeri_kanan'] ?? '') === 'tidak' ? 'selected' : '' ?>>Tidak</option>
        </select>
    </div>
</div>

<!-- Serumen -->
<div class="row mb-2">
    <div class="col-sm-2"><strong>Serumen</strong></div>
    <div class="col-sm-4">
        <select class="form-select" name="serumen" style="max-width:200px" <?= $ro_select ?>>
            <option value="">Pilih</option>
            <option value="ada" <?= ($existing_data['serumen'] ?? '') === 'ada' ? 'selected' : '' ?>>Ada</option>
            <option value="tidak" <?= ($existing_data['serumen'] ?? '') === 'tidak' ? 'selected' : '' ?>>Tidak</option>
        </select>
    </div>
</div>

<!-- Kelainan -->
<div class="row mb-3">
    <div class="col-sm-2 col-form-label"><strong>Kelainan</strong></div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="kelainan_telinga" value="<?= $existing_data['kelainan_telinga'] ?? '' ?>" <?= $ro ?>>
    </div>
</div>
             <div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>f. Hidung</strong></label>
</div>

<!-- Membedakan Bau -->
<div class="row mb-2">
    <div class="col-sm-2"><strong>Membedakan Bau</strong></div>
    <div class="col-sm-4">
        <select class="form-select" name="bau" style="max-width:200px" <?= $ro_select ?>>
            <option value="">Pilih</option>
            <option value="dapat" <?= ($existing_data['bau'] ?? '') === 'dapat' ? 'selected' : '' ?>>Dapat</option>
            <option value="tidak" <?= ($existing_data['bau'] ?? '') === 'tidak' ? 'selected' : '' ?>>Tidak</option>
        </select>
    </div>
</div>

<!-- Sekresi -->
<div class="row mb-3 align-items-center">
    <label class="col-sm-2 col-form-label"><strong>Sekresi</strong></label>
    <div class="col-sm-3">
        <input type="text" class="form-control" name="sekresi" value="<?= $existing_data['sekresi'] ?? '' ?>" <?= $ro ?>>
    </div>

<!-- Warna -->

    <label class="col-sm-2 col-form-label"><strong>Warna</strong></label>
    <div class="col-sm-3">
        <input type="text" class="form-control" name="warna_hidung" value="<?= $existing_data['warna_hidung'] ?? '' ?>" <?= $ro ?>>
    </div>
</div>

<!-- Mukosa -->
<div class="row mb-3">
    <div class="col-sm-2 col-form-label"><strong>Mukosa</strong></div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="mukosa_hidung" value="<?= $existing_data['mukosa_hidung'] ?? '' ?>" <?= $ro ?>>
    </div>
</div>

<!-- Pembengkakan -->
<div class="row mb-2">
    <div class="col-sm-2"><strong>Pembengkakan</strong></div>
    <div class="col-sm-4">
        <select class="form-select" name="pembengkakan" style="max-width:200px" <?= $ro_select ?>>
            <option value="">Pilih</option>
            <option value="ya" <?= ($existing_data['pembengkakan'] ?? '') === 'ya' ? 'selected' : '' ?>>Ya</option>
            <option value="tidak" <?= ($existing_data['pembengkakan'] ?? '') === 'tidak' ? 'selected' : '' ?>>Tidak</option>
        </select>
    </div>
</div>

<!-- Pernafasan Cuping Hidung -->
<div class="row mb-2">
    <div class="col-sm-2"><strong>Pernafasan Cuping Hidung</strong></div>
    <div class="col-sm-4">
        <select class="form-select" name="cuping_hidung" style="max-width:200px" <?= $ro_select ?>>
            <option value="">Pilih</option>
            <option value="ya" <?= ($existing_data['cuping_hidung'] ?? '') === 'ya' ? 'selected' : '' ?>>Ya</option>
            <option value="tidak" <?= ($existing_data['cuping_hidung'] ?? '') === 'tidak' ? 'selected' : '' ?>>Tidak</option>
        </select>
    </div>
</div>

<!-- Kelainan -->
<div class="row mb-3">
    <div class="col-sm-2 col-form-label"><strong>Kelainan</strong></div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="kelainan_hidung" value="<?= $existing_data['kelainan_hidung'] ?? '' ?>" <?= $ro ?>>
    </div>
</div>
  <div class="row mb-3">
    <label class="col-sm-12 text-primary"><strong>g. Mulut</strong></label>
</div>

<!-- Bibir (Warna) -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Bibir</strong></label>
    <div class="col-sm-9">
        <div class="input-group">
            <input type="text" class="form-control" name="bibir" value="<?= $existing_data['bibir'] ?? '' ?>" <?= $ro ?>>
            <span class="input-group-text">Warna</span>
        </div>
    </div>
</div>

<!-- Simetris -->
<div class="row mb-2">
    <div class="col-sm-2"><strong>Simetris</strong></div>
    <div class="col-sm-4">
        <select class="form-select" name="simetris" style="max-width:200px" <?= $ro_select ?>>
            <option value="">Pilih</option>
            <option value="ya" <?= ($existing_data['simetris'] ?? '') === 'ya' ? 'selected' : '' ?>>Ya</option>
            <option value="tidak" <?= ($existing_data['simetris'] ?? '') === 'tidak' ? 'selected' : '' ?>>Tidak</option>
        </select>
    </div>
</div>

<!-- Kelembaban -->
<div class="row mb-2">
    <div class="col-sm-2"><strong>Kelembaban</strong></div>
    <div class="col-sm-4">
        <select class="form-select" name="kelembaban" style="max-width:200px" <?= $ro_select ?>>
            <option value="">Pilih</option>
            <option value="basah" <?= ($existing_data['kelembaban'] ?? '') === 'basah' ? 'selected' : '' ?>>Basah</option>
            <option value="kering" <?= ($existing_data['kelembaban'] ?? '') === 'kering' ? 'selected' : '' ?>>Kering</option>
        </select>
    </div>
</div>

<!-- Gigi Caries -->
<div class="row mb-2">
    <div class="col-sm-2"><strong>Gigi</strong></div>
    <div class="col-sm-2"><strong>Caries:</strong></div>
    <div class="col-sm-4">
        <select class="form-select" name="caries" style="max-width:200px" <?= $ro_select ?>>
            <option value="">Pilih</option>
            <option value="ada" <?= ($existing_data['caries'] ?? '') === 'ada' ? 'selected' : '' ?>>Ada</option>
            <option value="tidak" <?= ($existing_data['caries'] ?? '') === 'tidak' ? 'selected' : '' ?>>Tidak</option>
        </select>
    </div>
</div>

<!-- Jumlah Gigi -->
<div class="row mb-2">
    <div class="col-sm-2"><strong></strong></div>
    <div class="col-sm-4">
        <label><strong>Jumlah</strong></label>
        <input type="text" class="form-control" name="jumlah_gigi" value="<?= $existing_data['jumlah_gigi'] ?? '' ?>" <?= $ro ?>>
    </div>
</div>

<!-- Warna Gigi -->
<div class="row mb-3">
    <div class="col-sm-2 col-form-label"><strong>Warna</strong></div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="warna_gigi" value="<?= $existing_data['warna_gigi'] ?? '' ?>" <?= $ro ?>>
    </div>
</div>

<!-- Gigi Palsu -->
<div class="row mb-3 align-items-center">
    <label class="col-sm-2 col-form-label"><strong>Gigi Palsu</strong></label>
    <div class="col-sm-4">
        <div class="input-group">
            <input type="text" class="form-control" name="frekuensi" value="<?= $existing_data['frekuensi'] ?? '' ?>" <?= $ro ?>>
            <span class="input-group-text">buah</span>
        </div>
    </div>

    <!-- Letak Gigi Palsu -->
    <label class="col-sm-2 col-form-label"><strong>Letak</strong></label>
    <div class="col-sm-2">
        <input type="text" class="form-control" name="letak" value="<?= $existing_data['letak'] ?? '' ?>" <?= $ro ?>>
    </div>
</div>
<!-- Frekuensi -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Lidah</strong></label>
    <div class="col-sm-9">
        <div class="input-group">
            <input type="text" class="form-control" name="lidah" value="<?= $existing_data['lidah'] ?? '' ?>" <?= $ro ?>>
            <span class="input-group-text">Warna</span>
        </div>
    </div>
</div>

<!-- Lesi Lidah -->
<div class="row mb-2">
    <div class="col-sm-2"><strong>Lesi</strong></div>
    <div class="col-sm-4">
        <select class="form-select" name="lesi_lidah" style="max-width:200px" <?= $ro_select ?>>
            <option value="">Pilih</option>
            <option value="ada" <?= ($existing_data['lesi_lidah'] ?? '') === 'ada' ? 'selected' : '' ?>>Ada</option>
            <option value="tidak" <?= ($existing_data['lesi_lidah'] ?? '') === 'tidak' ? 'selected' : '' ?>>Tidak</option>
        </select>
    </div>
</div>
<div class="row mb-2">
    <div class="col-sm-2"><strong>Sensasi Rasa</strong></div>
<!-- Sensasi Rasa: Panas/Dingin -->
<div class="row mb-2">
    <div class="col-sm-2"><strong>Panas/Dingin</strong></div>
    <div class="col-sm-4">
        <select class="form-select" name="panas_dingin" style="max-width:200px" <?= $ro_select ?>>
            <option value="">Pilih</option>
            <option value="ada" <?= ($existing_data['panas_dingin'] ?? '') === 'ada' ? 'selected' : '' ?>>Ada</option>
            <option value="tidak" <?= ($existing_data['panas_dingin'] ?? '') === 'tidak' ? 'selected' : '' ?>>Tidak</option>
        </select>
    </div>
</div>

<!-- Sensasi Rasa: Asam/Pahit -->
<div class="row mb-2">
    <div class="col-sm-2"><strong>Asam/Pahit</strong></div>
    <div class="col-sm-4">
        <select class="form-select" name="asampahit" style="max-width:200px" <?= $ro_select ?>>
            <option value="">Pilih</option>
            <option value="ada" <?= ($existing_data['asampahit'] ?? '') === 'ada' ? 'selected' : '' ?>>Ada</option>
            <option value="tidak" <?= ($existing_data['asampahit'] ?? '') === 'tidak' ? 'selected' : '' ?>>Tidak</option>
        </select>
    </div>
</div>

<!-- Sensasi Rasa: Manis -->
<div class="row mb-2">
    <div class="col-sm-2"><strong>Manis</strong></div>
    <div class="col-sm-4">
        <select class="form-select" name="manis" style="max-width:200px" <?= $ro_select ?>>
            <option value="">Pilih</option>
            <option value="ada" <?= ($existing_data['manis'] ?? '') === 'ada' ? 'selected' : '' ?>>Ada</option>
            <option value="tidak" <?= ($existing_data['manis'] ?? '') === 'tidak' ? 'selected' : '' ?>>Tidak</option>
        </select>
    </div>
</div>

<!-- Refleks Mengunyah -->
<div class="row mb-2">
    <div class="col-sm-2"><strong>Refleks Mengunyah</strong></div>
    <div class="col-sm-4">
        <select class="form-select" name="refleks" style="max-width:200px" <?= $ro_select ?>>
            <option value="">Pilih</option>
            <option value="dapat" <?= ($existing_data['refleks'] ?? '') === 'dapat' ? 'selected' : '' ?>>Dapat</option>
            <option value="tidak" <?= ($existing_data['refleks'] ?? '') === 'tidak' ? 'selected' : '' ?>>Tidak</option>
        </select>
    </div>
</div>

<!-- Pembesaran Tonsil -->
<div class="row mb-2">
    <div class="col-sm-2"><strong>Pembesaran Tonsil</strong></div>
    <div class="col-sm-4">
        <select class="form-select" name="tonsil" style="max-width:200px" <?= $ro_select ?>>
            <option value="">Pilih</option>
            <option value="ya" <?= ($existing_data['tonsil'] ?? '') === 'ya' ? 'selected' : '' ?>>Ya</option>
            <option value="tidak" <?= ($existing_data['tonsil'] ?? '') === 'tidak' ? 'selected' : '' ?>>Tidak</option>
        </select>
    </div>
<!-- </div>Bau Mulut -->
<div class="row mb-2">
    <div class="col-sm-2"><strong>Bau Mulut</strong></div>
    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="bau_mulut[]" value="uranium"<?= $ro_disabled ?> <?= ($existing_data['bau_mulut'] ?? '') === 'uranium' ? 'checked' : '' ?>>
            <label class="form-check-label">Uranium + / -</label>
        </div>
    </div>
    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="bau_mulut[]" value="amoniak" <?= $ro_disabled ?> <?= ($existing_data['bau_mulut'] ?? '') === 'amoniak' ? 'checked' : '' ?>>
            <label class="form-check-label">Amoniak + / -</label>
        </div>
    </div>
    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="bau_mulut[]" value="aceton" <?= $ro_disabled ?> <?= ($existing_data['bau_mulut'] ?? '') === 'aceton' ? 'checked' : '' ?>>
            <label class="form-check-label">Aceton + / -</label>
        </div>
    </div>
    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="bau_mulut[]" value="busuk" <?= $ro_disabled ?> <?= ($existing_data['bau_mulut'] ?? '') === 'busuk' ? 'checked' : '' ?>>
            <label class="form-check-label">Busuk + / -</label>
        </div>
    </div>
    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="bau_mulut[]" value="alkohol" <?= $ro_disabled ?> <?= ($existing_data['bau_mulut'] ?? '') === 'alkohol' ? 'checked' : '' ?>>
            <label class="form-check-label">Alkohol + / -</label>
        </div>
    </div>
</div>
<!-- Sekret -->
<div class="row mb-2">
    <div class="col-sm-2"><strong>Sekret</strong></div>
    <div class="col-sm-4">
        <select class="form-select" name="caries1" style="max-width:200px" <?= $ro_select ?>>
            <option value="">Pilih</option>
            <option value="ada" <?= (isset($existing_data['caries1']) && $existing_data['caries'] === 'ada') ? 'selected' : '' ?>>Ada</option>
            <option value="tidak" <?= (isset($existing_data['caries1']) && $existing_data['caries'] === 'tidak') ? 'selected' : '' ?>>Tidak</option>
        </select>
    </div>
    
    <div class="col-sm-2"><strong>Warna</strong></div>
    <div class="col-sm-4">

        <input type="text" class="form-control" name="warna" value="<?= $existing_data['warna'] ?? '' ?>" <?= $ro ?>>
    </div>
</div>
<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>h. Leher</strong></label>
</div>

<!-- Bentuk Simetris -->
<div class="row mb-2">
    <div class="col-sm-2"><strong>Bentuk Simetris</strong></div>
    <div class="col-sm-4">
        <select class="form-select" name="leher_simetris" style="max-width:200px" <?= $ro_disabled ?>>
            <option value="">Pilih</option>
            <option value="ya" <?= ($existing_data['leher_simetris'] ?? '') === 'ya' ? 'selected' : '' ?>>Ya</option>
            <option value="tidak" <?= ($existing_data['leher_simetris'] ?? '') === 'tidak' ? 'selected' : '' ?>>Tidak</option>
        </select>
    </div>
</div>

<!-- Pembesaran Kelenjar -->
<div class="row mb-2">
    <div class="col-sm-2"><strong>Pembesaran Kelenjar</strong></div>
    <div class="col-sm-4">
        <select class="form-select" name="kelenjar" style="max-width:200px" <?= $ro_disabled ?>>
            <option value="">Pilih</option>
            <option value="ada" <?= ($existing_data['kelenjar'] ?? '') === 'ada' ? 'selected' : '' ?>>Ada</option>
            <option value="tidak" <?= ($existing_data['kelenjar'] ?? '') === 'tidak' ? 'selected' : '' ?>>Tidak</option>
        </select>
    </div>
</div>

<!-- Peninggian JVP -->
<div class="row mb-2">
    <div class="col-sm-2"><strong>Peninggian JVP</strong></div>
    <div class="col-sm-4">
        <select class="form-select" name="jvp" style="max-width:200px" <?= $ro_disabled ?>>
            <option value="">Pilih</option>
            <option value="ada" <?= ($existing_data['jvp'] ?? '') === 'ada' ? 'selected' : '' ?>>Ada</option>
            <option value="tidak" <?= ($existing_data['jvp'] ?? '') === 'tidak' ? 'selected' : '' ?>>Tidak</option>
        </select>
    </div>
</div>

<!-- Refleks Menelan -->
<div class="row mb-2">
    <div class="col-sm-2"><strong>Refleks Menelan</strong></div>
    <div class="col-sm-4">
        <select class="form-select" name="refleks_menelan" style="max-width:200px" <?= $ro_disabled ?>>
            <option value="">Pilih</option>
            <option value="dapat" <?= ($existing_data['refleks_menelan'] ?? '') === 'dapat' ? 'selected' : '' ?>>Dapat</option>
            <option value="tidak" <?= ($existing_data['refleks_menelan'] ?? '') === 'tidak' ? 'selected' : '' ?>>Tidak</option>
        </select>
    </div>
</div>

<!-- Kelainan Leher -->
<div class="row mb-3">
    <div class="col-sm-2 col-form-label"><strong>Kelainan</strong></div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="kelainan_leher" value="<?= val('kelainan_leher', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>
  <div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>i. Paru-Paru</strong></label>
</div>

<!-- Frekuensi Nafas -->
<div class="row mb-3 align-items-center">
    <label class="col-sm-2 col-form-label"><strong>Frekuensi Nafas</strong></label>
    <div class="col-sm-4">
        <div class="input-group">
            <input type="text" class="form-control" name="frekuensi" value="<?= val('frekuensi', $existing_data) ?>" <?= $ro_select ?>>
            <span class="input-group-text">x/menit</span>
        </div>
    </div>
    <div class="col-sm-2"><strong></strong></div>
    <div class="col-sm-4">
        <select class="form-select" name="teratur_nafas1" style="max-width:200px" <?= $ro_select ?>>
            <option value="">Pilih</option>
            <option value="teratur" <?= val('teratur_nafas1', $existing_data) === 'teratur' ? 'selected' : '' ?>>Teratur</option>
            <option value="tidak" <?= val('teratur_nafas1', $existing_data) === 'tidak' ? 'selected' : '' ?>>Tidak</option>
        </select>
    </div>
</div>

<!-- Irama Pernafasan -->
<div class="row mb-2">
    <div class="col-sm-2"><strong>Irama Pernafasan</strong></div>
    <div class="col-sm-4">
        <select class="form-select" name="irama_nafas" style="max-width:200px" <?= $ro_select ?>>
            <option value="">Pilih</option>
            <option value="dangkal" <?= val('irama_nafas', $existing_data) === 'dangkal' ? 'selected' : '' ?>>Dangkal</option>
            <option value="dalam" <?= val('irama_nafas', $existing_data) === 'dalam' ? 'selected' : '' ?>>Dalam</option>
        </select>
    </div>
</div>

<!-- Kesukaran Bernafas -->
<div class="row mb-2">
    <div class="col-sm-2"><strong>Kesukaran Bernafas</strong></div>
    <div class="col-sm-4">
        <select class="form-select" name="sesak_nafas" style="max-width:200px" <?= $ro_select ?>>
            <option value="">Pilih</option>
            <option value="ya" <?= val('sesak_nafas', $existing_data) === 'ya' ? 'selected' : '' ?>>Ya</option>
            <option value="tidak" <?= val('sesak_nafas', $existing_data) === 'tidak' ? 'selected' : '' ?>>Tidak</option>
        </select>
    </div>
</div>

<!-- Taktil Fremitus -->
<div class="row mb-3">
    <div class="col-sm-2 col-form-label"><strong>Taktil Fremitus</strong></div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="taktil_fremitus" value="<?= val('taktil_fremitus', $existing_data) ?>" <?= $ro_select ?>>
    </div>
</div>

<!-- Bunyi Perkusi Paru -->
<div class="row mb-3">
    <div class="col-sm-2 col-form-label"><strong>Bunyi Perkusi Paru</strong></div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="perkusi_paru" value="<?= val('perkusi_paru', $existing_data) ?>" <?= $ro_select ?>>
    </div>
</div>


<!-- Frekuensi Nafas (again for "Suara Nafas") -->
<div class="row mb-3">
    <label class="col-sm-2"><strong>Suara Nafas</strong></label>
    <div class="col-sm-4">
        <select class="form-select" name="teratur_nafas" style="max-width:200px" <?= $ro_select ?>>
            <option value="teratur" <?= val('teratur_nafas', $existing_data) === 'teratur' ? 'selected' : '' ?>>Normal</option>
        </select>
    </div>

    <div class="col-sm-6">
        <div class="input-group">
            <input type="text" class="form-control" name="frekuensi_nafas" value="<?= val('frekuensi_nafas', $existing_data) ?>" <?= $ro_select ?>>
            <span class="input-group-text">uraikan</span>
        </div>
    </div>
</div>

<!-- Bunyi Nafas Abnormal -->
<div class="row mb-2">
    <div class="col-sm-2"><strong>Bunyi Nafas Abnormal</strong></div>
    <div class="col-sm-4">
        <select class="form-select" name="bunyi_abnormal" style="max-width:200px" <?= $ro_select ?>>
            <option value="">Pilih</option>
            <option value="wheezing" <?= val('bunyi_abnormal', $existing_data) === 'wheezing' ? 'selected' : '' ?>>Wheezing</option>
            <option value="ronchi" <?= val('bunyi_abnormal', $existing_data) === 'ronchi' ? 'selected' : '' ?>>Ronchi</option>
        </select>
    </div>
    <div class="col-sm-9">
        <label><strong>Lainnya</strong></label>
        <input type="text" class="form-control" name="abnormal" value="<?= val('abnormal', $existing_data) ?>" <?= $ro_select ?>>
    </div>
</div>
<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>j.antung</strong></label>
</div>

<!-- S1 -->
<div class="row mb-3">
    <div class="col-sm-2 col-form-label"><strong>S1</strong></div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="s1_jantung" value="<?= val('s1_jantung', $existing_data) ?>" <?= $ro_select ?>>
    </div>
</div>

<!-- S2 -->
<div class="row mb-3">
    <div class="col-sm-2 col-form-label"><strong>S2</strong></div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="s2_jantung" value="<?= val('s2_jantung', $existing_data) ?>" <?= $ro_select ?>>
    </div>
</div>

<!-- Bunyi Teratur -->
<div class="row mb-2">
    <div class="col-sm-2"><strong>Bunyi Teratur</strong></div>
    <div class="col-sm-4">
        <select class="form-select" name="bunyi_jantung" style="max-width:200px" <?= $ro_select ?>>
            <option value="">Pilih</option>
            <option value="ya" <?= val('bunyi_jantung', $existing_data) === 'ya' ? 'selected' : '' ?>>Ya</option>
            <option value="tidak" <?= val('bunyi_jantung', $existing_data) === 'tidak' ? 'selected' : '' ?>>Tidak</option>
        </select>
    </div>
</div>

<!-- Bunyi Tambahan -->
<div class="row mb-2">
    <div class="col-sm-2"><strong>Bunyi Tambahan</strong></div>
    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="bunyi_tambahan" value="murmur" <?= ($existing_data['bunyi_tambahan'] ?? '') === 'murmur' ? 'checked' : '' ?> <?= $ro_select ?>>
            <label class="form-check-label">Murmur</label>
        </div>
    </div>
    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="bunyi_tambahan" value="tidak" <?= ($existing_data['bunyi_tambahan'] ?? '') === 'tidak' ? 'checked' : '' ?> <?= $ro_select ?>>
            <label class="form-check-label">Tidak</label>
        </div>
    </div>
</div>

<!-- Pulsasi Jantung -->
<div class="row mb-3">
    <div class="col-sm-2 col-form-label"><strong>Pulsasi Jantung</strong></div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="pulsasi_jantung" value="<?= val('pulsasi_jantung', $existing_data) ?>" <?= $ro_select ?>>
    </div>
</div>

<!-- Irama -->
<div class="row mb-2">
    <div class="col-sm-2"><strong>Irama</strong></div>
    <div class="col-sm-4">
        <select class="form-select" name="irama_jantung" style="max-width:200px" <?= $ro_select ?>>
            <option value="">Pilih</option>
            <option value="teratur" <?= val('irama_jantung', $existing_data) === 'teratur' ? 'selected' : '' ?>>Teratur</option>
            <option value="tidak_teratur" <?= val('irama_jantung', $existing_data) === 'tidak_teratur' ? 'selected' : '' ?>>Tidak Teratur</option>
        </select>
    </div>
</div><!-- Abdomen -->
<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>k. Abdomen</strong></label>
</div>

<!-- Bentuk -->
<div class="row mb-2">
    <div class="col-sm-2"><strong>Bentuk</strong></div>    

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="bentuk_abdomen" value="datar" <?= ($existing_data['bentuk_abdomen'] ?? '') === 'datar' ? 'checked' : '' ?> <?= $ro_select ?>>
            <label class="form-check-label">Datar</label>
        </div>
    </div>

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="bentuk_abdomen" value="membuncit" <?= ($existing_data['bentuk_abdomen'] ?? '') === 'membuncit' ? 'checked' : '' ?> <?= $ro_select ?>>
            <label class="form-check-label">Membuncit</label>
        </div>
    </div>

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="bentuk_abdomen" value="cekung" <?= ($existing_data['bentuk_abdomen'] ?? '') === 'cekung' ? 'checked' : '' ?> <?= $ro_select ?>>
            <label class="form-check-label">Cekung</label>
        </div>
    </div>

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="bentuk_abdomen" value="tegang" <?= ($existing_data['bentuk_abdomen'] ?? '') === 'tegang' ? 'checked' : '' ?> <?= $ro_select ?>>
            <label class="form-check-label">Tegang</label>
        </div>
    </div>
</div>

<!-- Keadaan -->
<div class="row mb-2">
    <div class="col-sm-2"><strong>Keadaan</strong></div>    

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="keadaan_abdomen" value="parut" <?= ($existing_data['keadaan_abdomen'] ?? '') === 'parut' ? 'checked' : '' ?> <?= $ro_select ?>>
            <label class="form-check-label">Parut</label>
        </div>
    </div>

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="keadaan_abdomen" value="lesi" <?= ($existing_data['keadaan_abdomen'] ?? '') === 'lesi' ? 'checked' : '' ?> <?= $ro_select ?>>
            <label class="form-check-label">Lesi</label>
        </div>
    </div>

    <div class="col-sm-3">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="keadaan_abdomen" value="bercak_merah" <?= ($existing_data['keadaan_abdomen'] ?? '') === 'bercak_merah' ? 'checked' : '' ?> <?= $ro_select ?>>
            <label class="form-check-label"> Bercak Merah</label>
        </div>
    </div>
</div>

<!-- Bising Usus -->
<div class="row mb-3 align-items-center">
    <label class="col-sm-2 col-form-label"><strong>Bising Usus</strong></label>
    <div class="col-sm-4">
        <select class="form-select" name="bising_usus" style="max-width:200px" <?= $ro_select ?>>
            <option value="">Pilih</option>
            <option value="ada" <?= (isset($existing_data['bising_usus']) && $existing_data['bising_usus'] === 'ada') ? 'selected' : '' ?>>Ada</option>
            <option value="tidak" <?= (isset($existing_data['bising_usus']) && $existing_data['bising_usus'] === 'tidak') ? 'selected' : '' ?>>Tidak</option>
        </select>
    </div>
    <div class="col-sm-4">
        <div class="input-group">
            <input type="text" class="form-control" name="frekuensi1" value="<?= val('frekuensi1', $existing_data) ?>" <?= $ro_select ?>>
            <span class="input-group-text">kali</span> 
        </div>
    </div>
</div>

<!-- Benjolan -->
<div class="row mb-3 align-items-center">
    <label class="col-sm-2 col-form-label"><strong>Benjolan</strong></label>
    <div class="col-sm-4">
        <select class="form-select" name="benjolan_abdomen" style="max-width:200px" <?= $ro_select ?>>
            <option value="">Pilih</option>
            <option value="ada" <?= (isset($existing_data['benjolan_abdomen']) && $existing_data['benjolan_abdomen'] === 'ada') ? 'selected' : '' ?>>Ada</option>
            <option value="tidak" <?= (isset($existing_data['benjolan_abdomen']) && $existing_data['benjolan_abdomen'] === 'tidak') ? 'selected' : '' ?>>Tidak</option>
        </select>
    </div>
    <div class="col-sm-4">
    <div class="input-group">
        <input type="text" class="form-control" name="letak1" value="<?= val('letak1', $existing_data) ?>" <?= $ro_select ?>>
        <span class="input-group-text">letak</span> 
    </div>
</div>
</div>

<!-- Nyeri Tekan -->
<div class="row mb-3 align-items-center">
    <label class="col-sm-2 col-form-label"><strong>Nyeri Tekan</strong></label>
    <div class="col-sm-4">
        <select class="form-select" name="nyeri_abdomen" style="max-width:200px" <?= $ro_select ?>>
            <option value="">Pilih</option>
            <option value="ada" <?= (isset($existing_data['nyeri_abdomen']) && $existing_data['nyeri_abdomen'] === 'ada') ? 'selected' : '' ?>>Ada</option>
            <option value="tidak" <?= (isset($existing_data['nyeri_abdomen']) && $existing_data['nyeri_abdomen'] === 'tidak') ? 'selected' : '' ?>>Tidak</option>
        </select>
    </div>
    <div class="col-sm-4">
        <div class="input-group">
            <input type="text" class="form-control" name="frekuensi_tekan" value="<?= val('frekuensi_tekan', $existing_data) ?>" <?= $ro_select ?>>
            <span class="input-group-text">letak</span> 
        </div>
    </div>
</div>

<!-- Perkusi Abdomen -->
<div class="row mb-3">
    <div class="col-sm-2 col-form-label"><strong>Perkusi Abdomen</strong></div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="perkusi_abdomen" value="<?= val('perkusi_abdomen', $existing_data) ?>" <?= $ro_select ?>>
    </div>
</div>

<!-- Kelainan -->
<div class="row mb-3">
    <div class="col-sm-2 col-form-label"><strong>Kelainan</strong></div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="kelainan_abdomen" value="<?= val('kelainan_abdomen', $existing_data) ?>" <?= $ro_select ?>>
    </div>
</div>
  <div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>m. Genetalia</strong></label>
</div>

<!-- Bentuk -->
<div class="row mb-2">
    <div class="col-sm-2"><strong>Bentuk</strong></div>
    <div class="col-sm-4">
        <select class="form-select" name="bentuk_genetalia" style="max-width:200px" <?= $ro_select ?>>
            <option value="">Pilih</option>
            <option value="utuh" <?= val('bentuk_genetalia', $existing_data) === 'utuh' ? 'selected' : '' ?>>Utuh</option>
            <option value="tidak" <?= val('bentuk_genetalia', $existing_data) === 'tidak' ? 'selected' : '' ?>>Tidak</option>
        </select>
    </div>
</div>

<!-- Radang -->
<div class="row mb-2">
    <div class="col-sm-2"><strong>Radang</strong></div>
    <div class="col-sm-4">
        <select class="form-select" name="radang_genetalia" style="max-width:200px" <?= $ro_select ?>>
            <option value="">Pilih</option>
            <option value="ada" <?= val('radang_genetalia', $existing_data) === 'ada' ? 'selected' : '' ?>>Ada</option>
            <option value="tidak" <?= val('radang_genetalia', $existing_data) === 'tidak' ? 'selected' : '' ?>>Tidak</option>
        </select>
    </div>
</div>

<!-- Sekret -->
<div class="row mb-2">
    <div class="col-sm-2"><strong>Sekret</strong></div>
    <div class="col-sm-4">
        <select class="form-select" name="sekret_genetalia" style="max-width:200px" <?= $ro_select ?>>
            <option value="">Pilih</option>
            <option value="ada" <?= val('sekret_genetalia', $existing_data) === 'ada' ? 'selected' : '' ?>>Ada</option>
            <option value="tidak" <?= val('sekret_genetalia', $existing_data) === 'tidak' ? 'selected' : '' ?>>Tidak</option>
        </select>
    </div>
</div>

<!-- Pembengkakan Skrotum -->
<div class="row mb-2">
    <div class="col-sm-2"><strong>Pembengkakan Skrotum</strong></div>
    <div class="col-sm-4">
        <select class="form-select" name="skrotum_bengkak" style="max-width:200px" <?= $ro_select ?>>
            <option value="">Pilih</option>
            <option value="ada" <?= val('skrotum_bengkak', $existing_data) === 'ada' ? 'selected' : '' ?>>Ada</option>
            <option value="tidak" <?= val('skrotum_bengkak', $existing_data) === 'tidak' ? 'selected' : '' ?>>Tidak</option>
        </select>
    </div>
</div>

<!-- Rektum -->
<div class="row mb-2">
    <div class="col-sm-2"><strong>Rektum</strong></div>
    <div class="col-sm-4">
        <select class="form-select" name="rektum_benjolan" style="max-width:200px" <?= $ro_select ?>>
            <option value="">Pilih</option>
            <option value="benjolan" <?= val('rektum_benjolan', $existing_data) === 'benjolan' ? 'selected' : '' ?>>Benjolan</option>
            <option value="tidak" <?= val('rektum_benjolan', $existing_data) === 'tidak' ? 'selected' : '' ?>>Tidak</option>
        </select>
    </div>
</div>

<!-- Lesi -->
<div class="row mb-2">
    <div class="col-sm-2"><strong>Lesi</strong></div>
    <div class="col-sm-4">
        <select class="form-select" name="lesi_genetalia" style="max-width:200px" <?= $ro_select ?>>
            <option value="">Pilih</option>
            <option value="ya" <?= val('lesi_genetalia', $existing_data) === 'ya' ? 'selected' : '' ?>>Ya</option>
            <option value="tidak" <?= val('lesi_genetalia', $existing_data) === 'tidak' ? 'selected' : '' ?>>Tidak</option>
        </select>
    </div>
</div>

<!-- Kelainan -->
<div class="row mb-3">
    <div class="col-sm-2 col-form-label"><strong>Kelainan</strong></div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="kelainan_genetalia" value="<?= val('kelainan_genetalia', $existing_data) ?>" <?= $ro_select ?>>
    </div>
</div>
<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>n. Ekstremitas</strong></label>
</div>

<!-- Atas -->
<div class="row mb-2">
    <label class="col-sm-12"><strong>1) Atas</strong></label>
</div>

<!-- Bentuk Simetris -->
<div class="row mb-2">
    <div class="col-sm-2"><strong>Bentuk Simetris</strong></div>
    <div class="col-sm-4">
        <select class="form-select" name="atas_simetris" style="max-width:200px" <?= $ro_select ?>>
            <option value="">Pilih</option>
            <option value="ya" <?= val('atas_simetris', $existing_data) === 'ya' ? 'selected' : '' ?>>Ya</option>
            <option value="tidak" <?= val('atas_simetris', $existing_data) === 'tidak' ? 'selected' : '' ?>>Tidak</option>
        </select>
    </div>
</div>

<!-- Sensasi Halus -->
<div class="row mb-2">
    <div class="col-sm-2"><strong>Sensasi Halus</strong></div>
    <div class="col-sm-4">
        <select class="form-select" name="sensasi_halus" style="max-width:200px" <?= $ro_select ?>>
            <option value="">Pilih</option>
            <option value="ada" <?= val('sensasi_halus', $existing_data) === 'ada' ? 'selected' : '' ?>>Ada</option>
            <option value="tidak" <?= val('sensasi_halus', $existing_data) === 'tidak' ? 'selected' : '' ?>>Tidak</option>
        </select>
    </div>
</div>

<!-- Sensasi Tajam -->
<div class="row mb-2">
    <div class="col-sm-2"><strong>Sensasi Tajam</strong></div>
    <div class="col-sm-4">
        <select class="form-select" name="sensasi_tajam" style="max-width:200px" <?= $ro_select ?>>
            <option value="">Pilih</option>
            <option value="ada" <?= val('sensasi_tajam', $existing_data) === 'ada' ? 'selected' : '' ?>>Ada</option>
            <option value="tidak" <?= val('sensasi_tajam', $existing_data) === 'tidak' ? 'selected' : '' ?>>Tidak</option>
        </select>
    </div>
</div>

<!-- Sensasi Panas -->
<div class="row mb-2">
    <div class="col-sm-2"><strong>Sensasi Panas</strong></div>
    <div class="col-sm-4">
        <select class="form-select" name="sensasi_panas" style="max-width:200px" <?= $ro_select ?>>
            <option value="">Pilih</option>
            <option value="ada" <?= val('sensasi_panas', $existing_data) === 'ada' ? 'selected' : '' ?>>Ada</option>
            <option value="tidak" <?= val('sensasi_panas', $existing_data) === 'tidak' ? 'selected' : '' ?>>Tidak</option>
        </select>
    </div>
</div>

<!-- Sensasi Dingin -->
<div class="row mb-2">
    <div class="col-sm-2"><strong>Sensasi Dingin</strong></div>
    <div class="col-sm-4">
        <select class="form-select" name="sensasi_dingin" style="max-width:200px" <?= $ro_select ?>>
            <option value="">Pilih</option>
            <option value="ada" <?= val('sensasi_dingin', $existing_data) === 'ada' ? 'selected' : '' ?>>Ada</option>
            <option value="tidak" <?= val('sensasi_dingin', $existing_data) === 'tidak' ? 'selected' : '' ?>>Tidak</option>
        </select>
    </div>
</div>

<!-- Gerakan ROM -->
<div class="row mb-2">
    <div class="col-sm-2"><strong>Gerakan ROM</strong></div>
    <div class="col-sm-4">
        <select class="form-select" name="rom_atas" style="max-width:200px" <?= $ro_select ?>>
            <option value="">Pilih</option>
            <option value="dapat" <?= val('rom_atas', $existing_data) === 'dapat' ? 'selected' : '' ?>>Dapat</option>
            <option value="tidak" <?= val('rom_atas', $existing_data) === 'tidak' ? 'selected' : '' ?>>Tidak</option>
        </select>
    </div>
</div>

<!-- Refleks Bisep -->
<div class="row mb-2">
    <div class="col-sm-2"><strong>Refleks Bisep</strong></div>
    <div class="col-sm-4">
        <select class="form-select" name="refleks_bisep" style="max-width:200px" <?= $ro_select ?>>
            <option value="">Pilih</option>
            <option value="ada" <?= val('refleks_bisep', $existing_data) === 'ada' ? 'selected' : '' ?>>Ada</option>
            <option value="tidak" <?= val('refleks_bisep', $existing_data) === 'tidak' ? 'selected' : '' ?>>Tidak</option>
        </select>
    </div>
</div>

<!-- Refleks Trisep -->
<div class="row mb-2">
    <div class="col-sm-2"><strong>Refleks Trisep</strong></div>
    <div class="col-sm-4">
        <select class="form-select" name="refleks_trisep" style="max-width:200px" <?= $ro_select ?>>
            <option value="">Pilih</option>
            <option value="ada" <?= val('refleks_trisep', $existing_data) === 'ada' ? 'selected' : '' ?>>Ada</option>
            <option value="tidak" <?= val('refleks_trisep', $existing_data) === 'tidak' ? 'selected' : '' ?>>Tidak</option>
        </select>
    </div>
</div>

<!-- Pembengkakan -->
<div class="row mb-2">
    <div class="col-sm-2"><strong>Pembengkakan</strong></div>
    <div class="col-sm-4">
        <select class="form-select" name="pembengkakan" style="max-width:200px" <?= $ro_select ?>>
            <option value="">Pilih</option>
            <option value="ya" <?= val('pembengkakan', $existing_data) === 'ya' ? 'selected' : '' ?>>Ya</option>
            <option value="tidak" <?= val('pembengkakan', $existing_data) === 'tidak' ? 'selected' : '' ?>>Tidak</option>
        </select>
    </div>
</div>

<!-- Kelembaban -->
<div class="row mb-2">
    <div class="col-sm-2"><strong>Kelembaban</strong></div>
    <div class="col-sm-4">
        <select class="form-select" name="kelembaban" style="max-width:200px" <?= $ro_select ?>>
            <option value="">Pilih</option>
            <option value="lembab" <?= val('kelembaban', $existing_data) === 'lembab' ? 'selected' : '' ?>>Lembab</option>
            <option value="kering" <?= val('kelembaban', $existing_data) === 'kering' ? 'selected' : '' ?>>Kering</option>
        </select>
    </div>
</div>

<!-- Temperatur -->
<div class="row mb-2">
    <div class="col-sm-2"><strong>Temperatur</strong></div>
    <div class="col-sm-4">
        <select class="form-select" name="temperatur" style="max-width:200px" <?= $ro_select ?>>
            <option value="">Pilih</option>
            <option value="panas" <?= val('temperatur', $existing_data) === 'panas' ? 'selected' : '' ?>>Panas</option>
            <option value="dingin" <?= val('temperatur', $existing_data) === 'dingin' ? 'selected' : '' ?>>Dingin</option>
        </select>
    </div>
</div>

<!-- Kekuatan Otot Tangan -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Kekuatan Otot Tangan</strong></label>
    <div class="col-sm-9">
        <div class="row">
            <!-- Kanan -->
            <div class="col-md-4 d-flex align-items-center">
                <label class="me-2"><strong>Kanan</strong></label>
                <input type="text" class="form-control" name="kanan1" value="<?= val('kanan1', $existing_data) ?>" <?= $ro_select ?>>
            </div>

            <!-- Kiri -->
            <div class="col-md-4 d-flex align-items-center">
                <label class="me-2"><strong>Kiri</strong></label>
                <input type="text" class="form-control" name="kiri1" value="<?= val('kiri1', $existing_data) ?>" <?= $ro_select ?>>
            </div>
        </div>
    </div>
</div>

<!-- Kelainan -->
<div class="row mb-3">
    <div class="col-sm-2 col-form-label"><strong>Kelainan</strong></div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="kelainan_genetalia1" value="<?= val('kelainan_genetalia1', $existing_data) ?>" <?= $ro_select ?>>
    </div>
</div>
                <div class="row mb-3">
               <div class="row mb-2">
    <label class="col-sm-12"><strong>2) Bawah</strong></label>
</div>

<div class="row mb-2">
    <div class="col-sm-2"><strong>Bentuk Simetris</strong></div>
    <div class="col-sm-4">
        <select class="form-select" name="bawah_simetris" style="max-width:200px" <?= $ro_select ?>>
            <option value="">Pilih</option>
            <option value="ya" <?= val('bawah_simetris', $existing_data) === 'ya' ? 'selected' : '' ?>>Ya</option>
            <option value="tidak" <?= val('bawah_simetris', $existing_data) === 'tidak' ? 'selected' : '' ?>>Tidak</option>
        </select>
    </div>
</div>

<div class="row mb-2">
    <div class="col-sm-2"><strong>Sensasi Halus</strong></div>
    <div class="col-sm-4">
        <select class="form-select" name="sensasi_bawah" style="max-width:200px" <?= $ro_select ?>>
            <option value="">Pilih</option>
            <option value="ada" <?= val('sensasi_bawah', $existing_data) === 'ada' ? 'selected' : '' ?>>Ada</option>
            <option value="tidak" <?= val('sensasi_bawah', $existing_data) === 'tidak' ? 'selected' : '' ?>>Tidak</option>
        </select>
    </div>
</div>

<div class="row mb-2">
    <div class="col-sm-2"><strong>Sensasi Tajam</strong></div>
    <div class="col-sm-4">
        <select class="form-select" name="bawah_tajam" style="max-width:200px" <?= $ro_select ?>>
            <option value="">Pilih</option>
            <option value="ada" <?= val('sensasi_tajam', $existing_data) === 'ada' ? 'selected' : '' ?>>Ada</option>
            <option value="tidak" <?= val('sensasi_tajam', $existing_data) === 'tidak' ? 'selected' : '' ?>>Tidak</option>
        </select>
    </div>
</div>

<div class="row mb-2">
    <div class="col-sm-2"><strong>Sensasi Panas</strong></div>
    <div class="col-sm-4">
        <select class="form-select" name="sensasi_panasb" style="max-width:200px" <?= $ro_select ?>>
            <option value="">Pilih</option>
            <option value="ada" <?= val('sensasi_panasb', $existing_data) === 'ada' ? 'selected' : '' ?>>Ada</option>
            <option value="tidak" <?= val('sensasi_panasb', $existing_data) === 'tidak' ? 'selected' : '' ?>>Tidak</option>
        </select>
    </div>
</div>

<div class="row mb-2">
    <div class="col-sm-2"><strong>Sensasi Dingin</strong></div>
    <div class="col-sm-4">
        <select class="form-select" name="sensasi_dinginb" style="max-width:200px" <?= $ro_select ?>>
            <option value="">Pilih</option>
            <option value="ada" <?= val('sensasi_dinginb', $existing_data) === 'ada' ? 'selected' : '' ?>>Ada</option>
            <option value="tidak" <?= val('sensasi_dinginb', $existing_data) === 'tidak' ? 'selected' : '' ?>>Tidak</option>
        </select>
    </div>
</div>

<div class="row mb-2">
    <div class="col-sm-2"><strong>Gerakan ROM</strong></div>
    <div class="col-sm-4">
        <select class="form-select" name="rom_bawah" style="max-width:200px" <?= $ro_select ?>>
            <option value="">Pilih</option>
            <option value="dapat" <?= val('rom_bawah', $existing_data) === 'dapat' ? 'selected' : '' ?>>Dapat</option>
            <option value="tidak" <?= val('rom_bawah', $existing_data) === 'tidak' ? 'selected' : '' ?>>Tidak</option>
        </select>
    </div>
</div>

<div class="row mb-2">
    <div class="col-sm-2"><strong>Refleks Babinski</strong></div>
    <div class="col-sm-4">
        <select class="form-select" name="refleks_babinski1" style="max-width:200px" <?= $ro_select ?>>
            <option value="">Pilih</option>
            <option value="ada" <?= val('refleks_babinski1', $existing_data) === 'ada' ? 'selected' : '' ?>>Ada</option>
            <option value="tidak" <?= val('refleks_babinski1', $existing_data) === 'tidak' ? 'selected' : '' ?>>Tidak</option>
        </select>
    </div>
</div>

<div class="row mb-2">
    <div class="col-sm-2"><strong>Pembengkakan</strong></div>
    <div class="col-sm-4">
        <select class="form-select" name="pembengkakan2" style="max-width:200px" <?= $ro_select ?>>
            <option value="">Pilih</option>
            <option value="ya" <?= val('pembengkakan2', $existing_data) === 'ya' ? 'selected' : '' ?>>Ya</option>
            <option value="tidak" <?= val('pembengkakan2', $existing_data) === 'tidak' ? 'selected' : '' ?>>Tidak</option>
        </select>
    </div>
</div>

<div class="row mb-2">
    <div class="col-sm-2"><strong>Varises</strong></div>
    <div class="col-sm-4">
        <select class="form-select" name="varises1" style="max-width:200px" <?= $ro_select ?>>
            <option value="">Pilih</option>
            <option value="ada" <?= val('varises1', $existing_data) === 'ada' ? 'selected' : '' ?>>Ada</option>
            <option value="tidak" <?= val('varises1', $existing_data) === 'tidak' ? 'selected' : '' ?>>Tidak</option>
        </select>
    </div>
</div>

<div class="row mb-2">
    <div class="col-sm-2"><strong>Kelembaban</strong></div>
    <div class="col-sm-4">
        <select class="form-select" name="kelembaban3" style="max-width:200px" <?= $ro_select ?>>
            <option value="">Pilih</option>
            <option value="lembab" <?= val('kelembaban3', $existing_data) === 'lembab' ? 'selected' : '' ?>>Lembab</option>
            <option value="kering" <?= val('kelembaban3', $existing_data) === 'kering' ? 'selected' : '' ?>>Kering</option>
        </select>
    </div>
</div>

<div class="row mb-2">
    <div class="col-sm-2"><strong>Temperatur</strong></div>
    <div class="col-sm-4">
        <select class="form-select" name="temperaturb" style="max-width:200px" <?= $ro_select ?>>
            <option value="">Pilih</option>
            <option value="panas" <?= val('temperaturb', $existing_data) === 'panas' ? 'selected' : '' ?>>Panas</option>
            <option value="dingin" <?= val('temperaturb', $existing_data) === 'dingin' ? 'selected' : '' ?>>Dingin</option>
        </select>
    </div>
</div>
<!-- Kekuatan Otot Kaki -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Kekuatan Otot Kaki</strong></label>
    <div class="col-sm-9">
        <div class="row">
            <!-- Kanan -->
            <div class="col-md-4 d-flex align-items-center">
                <label class="me-2"><strong>Kanan</strong></label>
                <input type="text" class="form-control" name="kanankaki" value="<?= val('kanankaki', $existing_data) ?>" <?= $ro_select ?>>
            </div>

            <!-- Kiri -->
            <div class="col-md-4 d-flex align-items-center">
                <label class="me-2"><strong>Kiri</strong></label>
                <input type="text" class="form-control" name="kirikaki" value="<?= val('kirikaki', $existing_data) ?>" <?= $ro_select ?>>
            </div>
        </div>
    </div>
</div>

<!-- Kelainan -->
<div class="row mb-3">
    <div class="col-sm-2 col-form-label"><strong>Kelainan</strong></div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="kelainan_genetalia2" value="<?= val('kelainan_genetalia', $existing_data) ?>" <?= $ro_select ?>>
    </div>
</div>
        <!-- Kulit -->
<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>o. Kulit</strong></label>
</div>

<!-- Warna -->
<div class="row mb-3">
    <div class="col-sm-2 col-form-label"><strong>Warna</strong></div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="warna_kulit" value="<?= val('warna_kulit', $existing_data) ?>" <?= $ro_select ?>>
    </div>
</div>

<!-- Turgor -->
<div class="row mb-2">
    <div class="col-sm-2"><strong>Turgor</strong></div>
    <div class="col-sm-4">
        <select class="form-select" name="turgor_kulit" style="max-width:200px" <?= $ro_select ?>>
            <option value="">Pilih</option>
            <option value="elastis" <?= val('turgor_kulit', $existing_data) === 'elastis' ? 'selected' : '' ?>>Elastis</option>
            <option value="menurun" <?= val('turgor_kulit', $existing_data) === 'menurun' ? 'selected' : '' ?>>Tidak</option>
        </select>
    </div>
</div>

<!-- Kelembaban -->
<div class="row mb-2">
    <div class="col-sm-2"><strong>Kelembaban</strong></div>
    <div class="col-sm-4">
        <select class="form-select" name="kelembaban2" style="max-width:200px" <?= $ro_select ?>>
            <option value="">Pilih</option>
            <option value="lembab" <?= val('kelembaban2', $existing_data) === 'lembab' ? 'selected' : '' ?>>Lembab</option>
            <option value="kering" <?= val('kelembaban2', $existing_data) === 'kering' ? 'selected' : '' ?>>Kering</option>
        </select>
    </div>
</div>

<!-- Edema -->
<div class="row mb-3 align-items-center">
    <label class="col-sm-2 col-form-label"><strong>Edema</strong></label>
    <div class="col-sm-4">
        <select class="form-select" name="edema_kulit" style="max-width:200px" <?= $ro_select ?>>
            <option value="">Pilih</option>
            <option value="ada" <?= val('edema_kulit', $existing_data) === 'ada' ? 'selected' : '' ?>>Ada</option>
            <option value="tidak" <?= val('edema_kulit', $existing_data) === 'tidak' ? 'selected' : '' ?>>Tidak</option>
        </select>
    </div>
    <div class="col-sm-5">
        <div class="input-group">
            <input type="text" class="form-control" name="pada_daerah" value="<?= val('pada_daerah', $existing_data) ?>" <?= $ro_select ?>>
            <span class="input-group-text">Pada Daerah</span>
        </div>
    </div>
</div>

<!-- Luka -->
<div class="row mb-3 align-items-center">
    <label class="col-sm-2 col-form-label"><strong>Luka</strong></label>
    <div class="col-sm-4">
        <select class="form-select" name="luka_kulit1" style="max-width:200px" <?= $ro_select ?>>
            <option value="">Pilih</option>
            <option value="ada" <?= val('luka_kulit1', $existing_data) === 'ada' ? 'selected' : '' ?>>Ada</option>
            <option value="tidak" <?= val('luka_kulit1', $existing_data) === 'tidak' ? 'selected' : '' ?>>Tidak</option>
        </select>
    </div>
    <div class="col-sm-5">
        <div class="input-group">
            <input type="text" class="form-control" name="pada_daerah1" value="<?= val('pada_daerah1', $existing_data) ?>" <?= $ro_select ?>>
            <span class="input-group-text">Pada Daerah</span>
        </div>
    </div>
</div>

<!-- Karakteristik Luka -->
<div class="row mb-3">
    <div class="col-sm-2 col-form-label"><strong>Karakteristik Luka</strong></div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="karakteristik_luka" value="<?= val('karakteristik_luka', $existing_data) ?>" <?= $ro_select ?>>
    </div>
</div>

<!-- Tekstur -->
<div class="row mb-2">
    <div class="col-sm-2"><strong>Tekstur</strong></div>
    <div class="col-sm-4">
        <select class="form-select" name="tekstur_kulit" style="max-width:200px" <?= $ro_select ?>>
            <option value="">Pilih</option>
            <option value="licin" <?= val('tekstur_kulit', $existing_data) === 'licin' ? 'selected' : '' ?>>Licin</option>
            <option value="keriput" <?= val('tekstur_kulit', $existing_data) === 'keriput' ? 'selected' : '' ?>>Keriput</option>
            <option value="kasar" <?= val('tekstur_kulit', $existing_data) === 'kasar' ? 'selected' : '' ?>>Kasar</option>
        </select>
    </div>
</div>

<!-- Kelainan -->
<div class="row mb-3">
    <div class="col-sm-2 col-form-label"><strong>Kelainan</strong></div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="kelainan_kulit" value="<?= val('kelainan_kulit', $existing_data) ?>" <?= $ro_select ?>>
    </div>
</div>
<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>p. Kuku</strong></label>
</div>

<!-- Clubbing Finger -->
<div class="row mb-2">
    <div class="col-sm-2"><strong>Clubbing Finger</strong></div>
    <div class="col-sm-4">
        <select class="form-select" name="clubbing_finger" style="max-width:200px" <?= $ro_select ?>>
            <option value="">Pilih</option>
            <option value="ya" <?= val('clubbing_finger', $existing_data) === 'ya' ? 'selected' : '' ?>>Ya</option>
            <option value="tidak" <?= val('clubbing_finger', $existing_data) === 'tidak' ? 'selected' : '' ?>>Tidak</option>
        </select>
    </div>
</div>

<!-- Capillary Refill Time -->
<div class="row mb-3">
    <div class="col-sm-2 col-form-label"><strong>Capillary Refill Time</strong></div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="capillary_refill_time" value="<?= val('capillary_refill_time', $existing_data) ?>" <?= $ro_select ?>>
    </div>
</div>

<!-- Keadaan Kuku -->
<div class="row mb-3">
    <div class="col-sm-2 col-form-label"><strong>Keadaan</strong></div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="keadaan_kuku" value="<?= val('keadaan_kuku', $existing_data) ?>" <?= $ro_select ?>>
    </div>
</div>
<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>q. Status Neurologi</strong></label>
</div>

<!-- Saraf-saraf Kranial -->
<div class="row mb-2">
    <label class="col-sm-12"><strong>1) Saraf-saraf Kranial</strong></label>
</div>

<!-- Nervus I (Olfactorius) - Penciuman -->
<div class="row mb-3">
    <div class="col-sm-2 col-form-label"><strong>a) Nervus I (Olfactorius) - Penciuman</strong></div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="nervus1_penciuman" value="<?= val('nervus1_penciuman', $existing_data) ?>" <?= $ro_select ?>>
    </div>
</div>

<!-- Nervus II (Opticus) - Penglihatan -->
<div class="row mb-3">
    <div class="col-sm-2 col-form-label"><strong>b) Nervus II (Opticus) - Penglihatan</strong></div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="nervus2_penglihatan" value="<?= val('nervus2_penglihatan', $existing_data) ?>" <?= $ro_select ?>>
    </div>
</div>

<!-- Nervus III, IV, VI (Oculomotorius, Trochlearis, Abducens) -->
<div class="row mb-2">
    <label class="col-sm-12"><strong>c) Nervus III, IV, VI (Oculomotorius, Trochlearis, Abducens)</strong></label>
</div>

<!-- Konstriksi Pupil -->
<div class="row mb-3">
    <div class="col-sm-2 col-form-label"><strong>Konstriksi Pupil</strong></div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="konstriksi_pupil" value="<?= val('konstriksi_pupil', $existing_data) ?>" <?= $ro_select ?>>
    </div>
</div>

<!-- Gerakan Kelopak Mata -->
<div class="row mb-3">
    <div class="col-sm-2 col-form-label"><strong>Gerakan Kelopak Mata</strong></div>
    <div class="col-sm-9">
        <textarea name="gerakan_kelopak" class="form-control" rows="3" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= val('gerakan_kelopak', $existing_data) ?></textarea>
    </div>
</div>

<!-- Pergerakan Bola Mata -->
<div class="row mb-3">
    <div class="col-sm-2 col-form-label"><strong>Pergerakan Bola Mata</strong></div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="gerakan_bola_mata" value="<?= val('gerakan_bola_mata', $existing_data) ?>" <?= $ro_select ?>>
    </div>
</div>

<!-- Pergerakan Mata ke Bawah & Dalam -->
<div class="row mb-3">
    <div class="col-sm-2 col-form-label"><strong>Pergerakan Mata ke Bawah & Dalam</strong></div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="gerakan_mata_bawah" value="<?= val('gerakan_mata_bawah', $existing_data) ?>" <?= $ro_select ?>>
    </div>
</div>

<!-- Nervus V (Trigeminus) -->
<div class="row mb-2">
    <label class="col-sm-12"><strong>d) Nervus V (Trigeminus)</strong></label>
</div>

<!-- Refleks Dagu -->
<div class="row mb-3">
    <div class="col-sm-2 col-form-label"><strong>Refleks Dagu</strong></div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="refleks_dagu" value="<?= val('refleks_dagu', $existing_data) ?>" <?= $ro_select ?>>
    </div>
</div>

<!-- Refleks Cornea -->
<div class="row mb-3">
    <div class="col-sm-2 col-form-label"><strong>Refleks Cornea</strong></div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="refleks_cornea" value="<?= val('refleks_cornea', $existing_data) ?>" <?= $ro_select ?>>
    </div>
</div>

<!-- Nervus VII (Facialis) -->
<div class="row mb-2">
    <label class="col-sm-12"><strong>e) Nervus VII (Facialis)</strong></label>
</div>

<!-- Pengecapan 2/3 Lidah Bagian Depan -->
<div class="row mb-3">
    <div class="col-sm-2 col-form-label"><strong>Pengecapan 2/3 Lidah Bagian Depan</strong></div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="pengecapan_depan" value="<?= val('pengecapan_depan', $existing_data) ?>" <?= $ro_select ?>>
    </div>
</div>

<!-- Nervus VIII (Acusticus) -->
<div class="row mb-2">
    <label class="col-sm-12"><strong>f) Nervus VIII (Acusticus)</strong></label>
</div>

<!-- Fungsi Pendengaran -->
<div class="row mb-3">
    <div class="col-sm-2 col-form-label"><strong>Fungsi Pendengaran</strong></div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="fungsi_pendengaran" value="<?= val('fungsi_pendengaran', $existing_data) ?>" <?= $ro_select ?>>
    </div>
</div>

<!-- Nervus IX & X (Glossopharyngeus dan Vagus) -->
<div class="row mb-2">
    <label class="col-sm-12"><strong>g) Nervus IX & X (Glossopharyngeus dan Vagus)</strong></label>
</div>

<!-- Refleks Menelan -->
<div class="row mb-3">
    <div class="col-sm-2 col-form-label"><strong>Refleks Menelan</strong></div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="refleks_menelan1" value="<?= val('refleks_menelan1', $existing_data) ?>" <?= $ro_select ?>>
    </div>
</div>

<!-- Refleks Muntah -->
<div class="row mb-3">
    <div class="col-sm-2 col-form-label"><strong>Refleks Muntah</strong></div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="refleks_muntah" value="<?= val('refleks_muntah', $existing_data) ?>" <?= $ro_select ?>>
    </div>
</div>

<!-- Pengecapan 1/3 Lidah Belakang -->
<div class="row mb-3">
    <div class="col-sm-2 col-form-label"><strong>Pengecapan 1/3 Lidah Belakang</strong></div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="pengecapan_belakang" value="<?= val('pengecapan_belakang', $existing_data) ?>" <?= $ro_select ?>>
    </div>
</div>

<!-- Suara -->
<div class="row mb-3">
    <div class="col-sm-2 col-form-label"><strong>Suara</strong></div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="suara_pasien" value="<?= val('suara_pasien', $existing_data) ?>" <?= $ro_select ?>>
    </div>
</div>

<!-- Nervus XI (Assesorius) -->
<div class="row mb-2">
    <label class="col-sm-12"><strong>h) Nervus XI (Assesorius)</strong></label>
</div>

<!-- Memalingkan Kepala -->
<div class="row mb-3">
    <div class="col-sm-2 col-form-label"><strong>Memalingkan Kepala</strong></div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="gerakan_kepala" value="<?= val('gerakan_kepala', $existing_data) ?>" <?= $ro_select ?>>
    </div>
</div>

<!-- Mengangkat Bahu -->
<div class="row mb-3">
    <div class="col-sm-2 col-form-label"><strong>Mengangkat Bahu</strong></div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="angkat_bahu" value="<?= val('angkat_bahu', $existing_data) ?>" <?= $ro_select ?>>
    </div>
</div>

<!-- Nervus XII (Hypoglossus) -->
<div class="row mb-2">
    <label class="col-sm-12"><strong>i) Nervus XII (Hypoglossus)</strong></label>
</div>

<!-- Deviasi Lidah -->
<div class="row mb-3">
    <div class="col-sm-2 col-form-label"><strong>Deviasi Lidah</strong></div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="deviasi_lidah" value="<?= val('deviasi_lidah', $existing_data) ?>" <?= $ro_select ?>>
    </div>
</div>

<!-- Tanda-tanda Peradangan Selaput Otak -->
<div class="row mb-2">
    <label class="col-sm-12"><strong>2) Tanda-tanda Peradangan Selaput Otak</strong></label>
</div>

<!-- Kaku Kuduk -->
<div class="row mb-3">
    <div class="col-sm-2 col-form-label"><strong>Kaku Kuduk</strong></div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="kaku_kuduk" value="<?= val('kaku_kuduk', $existing_data) ?>" <?= $ro_select ?>>
    </div>
</div>

<!-- Kernig Sign -->
<div class="row mb-3">
    <div class="col-sm-2 col-form-label"><strong>Kernig Sign</strong></div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="kernig_sign" value="<?= val('kernig_sign', $existing_data) ?>" <?= $ro_select ?>>
    </div>
</div>

<!-- Refleks Brudzinski -->
<div class="row mb-3">
    <div class="col-sm-2 col-form-label"><strong>Refleks Brudzinski</strong></div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="refleks_brudzinski" value="<?= val('refleks_brudzinski', $existing_data) ?>" <?= $ro_select ?>>
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