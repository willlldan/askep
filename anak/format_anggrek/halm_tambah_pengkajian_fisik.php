<?php
require_once "koneksi.php";
require_once "utils.php";

$form_id       = 8;
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
            'keadaan_umum'           => $_POST['keadaan_umum'] ?? '',
            'kesadaran'              => $_POST['kesadaran'] ?? '',
            'tekanan_darah'          => $_POST['tekanan_darah'] ?? '',
            'nadi'                   => $_POST['nadi'] ?? '',
            'suhu'                   => $_POST['suhu'] ?? '',
            'pernapasan'             => $_POST['pernapasan'] ?? '',
            'bb'                     => $_POST['bb'] ?? '',
            'tb'                     => $_POST['tb'] ?? '',
            'rambut'                 => $_POST['rambut'] ?? '',
            'warna_rambut'           => $_POST['warna_rambut'] ?? '',
            'penyebaran'             => $_POST['penyebaran'] ?? '',
            'rontok'                 => $_POST['rontok'] ?? '',
            'kebersihan_rambut'      => $_POST['kebersihan_rambut'] ?? '',
            'benjolan'               => $_POST['benjolan'] ?? '',
            'benjolan_keterangan'    => $_POST['benjolan_keterangan'] ?? '',
            'nyeri_tekan'            => $_POST['nyeri_tekan'] ?? '',
            'nyeri_tekan_keterangan' => $_POST['nyeri_tekan_keterangan'] ?? '',
            'tekstur_rambut'         => $_POST['tekstur_rambut'] ?? '',
            'tekstur_rambut_keterangan' => $_POST['tekstur_rambut_keterangan'] ?? '',
            'simetris'               => $_POST['simetris'] ?? '',
            'simetris_keterangan'    => $_POST['simetris_keterangan'] ?? '',
            'bentuk_wajah'           => $_POST['bentuk_wajah'] ?? '',
            'nyeri_wajah'            => $_POST['nyeri_wajah'] ?? '',
            'nyeri_wajah_keterangan' => $_POST['nyeri_wajah_keterangan'] ?? '',
            'data_wajah'             => $_POST['data_wajah'] ?? '',
            'edema_palpera'                  => $_POST['edema_palpera'] ?? '',
            'radang_palpebra'        => $_POST['radang_palpebra'] ?? '',
            'sclera'                 => $_POST['sclera'] ?? '',
            'radang_conjungtiva'     => $_POST['radang_conjungtiva'] ?? '',
            'anemis'                 => $_POST['anemis'] ?? '',
            'pupil_bentuk'           => $_POST['pupil_bentuk'] ?? '',
            'pupil_ukuran'           => $_POST['pupil_ukuran'] ?? '',
            'posisi_mata'            => $_POST['posisi_mata'] ?? '',
            'posisi_mata_keterangan' => $_POST['posisi_mata_keterangan'] ?? '',
            'gerakan_mata'           => $_POST['gerakan_mata'] ?? '',
            'kelopak'                => $_POST['kelopak'] ?? '',
            'bulu_mata'              => $_POST['bulu_mata'] ?? '',
            'kabur'                  => $_POST['kabur'] ?? '',
            'diplopia'               => $_POST['diplopia'] ?? '',
            'data_mata'              => $_POST['data_mata'] ?? '',
            'bentuk_hidung'          => $_POST['bentuk_hidung'] ?? '',
            'septum'                 => $_POST['septum'] ?? '',
            'secret'                 => $_POST['secret'] ?? '',
            'data_hidung'            => $_POST['data_hidung'] ?? '',
            'telinga'                => $_POST['telinga'] ?? '',
            'nyeri_telinga'          => $_POST['nyeri_telinga'] ?? '',
            'keadaan_gigi'           => $_POST['keadaan_gigi'] ?? '',
            'karies'                 => $_POST['karies'] ?? '',
            'gusi'                   => $_POST['gusi'] ?? '',
            'gusi_keterangan'        => $_POST['gusi_keterangan'] ?? '',
            'lidah'                  => $_POST['lidah'] ?? '',
            'bibir_warna'            => $_POST['bibir_warna'] ?? '',
            'bibir_warna_keterangan' => $_POST['bibir_warna_keterangan'] ?? '',
            'bibir_kondisi'          => $_POST['bibir_kondisi'] ?? '',
            'bibir_kondisi_keterangan' => $_POST['bibir_kondisi_keterangan'] ?? '',
            'bau_mulut'              => $_POST['bau_mulut'] ?? '',
            'bau_mulut_keterangan'   => $_POST['bau_mulut_keterangan'] ?? '',
            'bicara'                 => $_POST['bicara'] ?? '',
            'data_mulut'             => $_POST['data_mulut'] ?? '',
            'mukosa'                 => $_POST['mukosa'] ?? '',
            'nyeri_tenggorokan'      => $_POST['nyeri_tenggorokan'] ?? '',
            'menelan'                => $_POST['menelan'] ?? '',
            'limfe'                  => $_POST['limfe'] ?? '',
            'data_leher'             => $_POST['data_leher'] ?? '',
            'bentuk_dada'            => $_POST['bentuk_dada'] ?? '',
            'irama_nafas'            => $_POST['irama_nafas'] ?? '',
            'pengembangan'           => $_POST['pengembangan'] ?? '',
            'tipe_nafas'             => $_POST['tipe_nafas'] ?? '',
            'suara_auskultas'           => $_POST['suara_auskultas'] ?? '',
            'suara_tambahan'        => $_POST['suara_tambahan'] ?? '',
            'perkusiauskultasiii'     => $_POST['perkusiauskultasiii'] ?? '',
                    // PERKUSI
            'perkusi_redup'      => $_POST['perkusi_redup'] ?? '',
            'perkusi_peyak'      => $_POST['perkusi_peyak'] ?? '',
            'perkusi_hypersonor' => $_POST['perkusi_hypersonor'] ?? '',
            'perkusi_tympani'    => $_POST['perkusi_tympani'] ?? '',

            // JANTUNG
            'ictus_cordis'       => $_POST['ictus_cordis'] ?? '',
            'pembesaran_jantung' => $_POST['pembesaran_jantung'] ?? '',
            'bj1'                => $_POST['bj1'] ?? '',
            'bj2'                => $_POST['bj2'] ?? '',
            'bj3'                => $_POST['bj3'] ?? '',
            'bunyi_tambahan'     => $_POST['bunyi_tambahan'] ?? '',
            'data_lain_jantung'  => $_POST['data_lain_jantung'] ?? '',

            // ABDOMEN
            'membuncit'          => $_POST['membuncit'] ?? '',
            'perkusi'                => $_POST['perkusi'] ?? '',
            'hepar'                  => $_POST['hepar'] ?? '',
            'lien'                   => $_POST['lien'] ?? '',
            'nyeri'                  => $_POST['nyeri'] ?? '',
            'peristaltik'           => $_POST['peristaltik'] ?? '',
            'luka_abdomen'          => $_POST['luka_abdomen'] ?? '',
            'luka_abdomen_lain'     => $_POST['luka_abdomen_lain'] ?? '',
            'tympani'               => $_POST['tympani'] ?? '',
            'redup'                 => $_POST['redup'] ?? '',
            'data_abdomen'          => $_POST['data_abdomen'] ?? '',
            'fistula_pria'          => $_POST['fistula_pria'] ?? '',
            'uretra'                => $_POST['uretra'] ?? '',
            'skrotum'               => $_POST['skrotum'] ?? '',
            'genital_ganda'         => $_POST['genital_ganda'] ?? '',
            'hidrokel_pria'         => $_POST['hidrokel_pria'] ?? '',
            'labia'                 => $_POST['labia'] ?? '',
            'fistula_wanita'        => $_POST['fistula_wanita'] ?? '',
            'hidrokel_wanita'       => $_POST['hidrokel_wanita'] ?? '',
            'anus_paten'            => $_POST['anus_paten'] ?? '',
            'mekonium'              => $_POST['mekonium'] ?? '',
                'gerak_atas'            => $_POST['gerak_atas'] ?? '',
                'abnormal_atas'         => $_POST['abnormal_atas'] ?? '',
                'kekuatan_atas'         => $_POST['kekuatan_atas'] ?? '',
                'koordinasi_atas'       => $_POST['koordinasi_atas'] ?? '',
            'nyeri_atas'            => $_POST['nyeri_atas'] ?? '',
            'suhu_atas'             => $_POST['suhu_atas'] ?? '',
            'raba_atas'             => $_POST['raba_atas'] ?? '',
            'gaya_jalan'            => $_POST['gaya_jalan'] ?? '',
            'kekuatan_bawah'        => $_POST['kekuatan_bawah'] ?? '',
            'tonus_bawah'           => $_POST['tonus_bawah'] ?? '',
            'nyeri_bawah'           => $_POST['nyeri_bawah'] ?? '',
            'suhu_bawah'            => $_POST['suhu_bawah'] ?? '',
            'raba_bawah'            => $_POST['raba_bawah'] ?? '',
            'kaku_kuduk'            => $_POST['kaku_kuduk'] ?? '',
            'kernig'                => $_POST['kernig'] ?? '',
            'brudzinski'            => $_POST['brudzinski'] ?? '',
            'refleks_bayi'          => $_POST['refleks_bayi'] ?? '',
            'iddol'                 => $_POST['iddol'] ?? '',
            'startel'               => $_POST['startel'] ?? '',
            'sucking'               => $_POST['sucking'] ?? '',
            'rooting'               => $_POST['rooting'] ?? '',
            'gawn'                  => $_POST['gawn'] ?? '',
            'grabella'              => $_POST['grabella'] ?? '',
            'ekruction'             => $_POST['ekruction'] ?? '',
            'moro'                  => $_POST['moro'] ?? '',
            'grasping'              => $_POST['grasping'] ?? '',
            'peres'                 => $_POST['peres'] ?? '',
            'kremaster'             => $_POST['kremaster'] ?? '',
            // INTEGUMEN
            'turgor'            => $_POST['turgor'] ?? '',
            'finger_print'      => $_POST['finger_print'] ?? '',
            'lesi'              => $_POST['lesi'] ?? '',
            'kebersihan'        => $_POST['kebersihan'] ?? '',
            'kelembaban'        => $_POST['kelembaban'] ?? '',
            'warna_kulit'       => $_POST['warna_kulit'] ?? '',
            // Motorik Kasar
            'motorik_kasar_input'  => $_POST['motorik_kasar_input'] ?? '',
            
            // Motorik Halus
            'motorik_halus_input'  => $_POST['motorik_halus_input'] ?? '',
            
            // Bahasa
            'bahasa_input'         => $_POST['bahasa_input'] ?? '',
            
            // Personal Social
            'personal_social_input'=> $_POST['personal_social_input'] ?? '',

            // TEST DIAGNOSTIK
            'diagnostik'        => $_POST['diagnostik'] ?? '',

            // LABORATORIUM
            'laboratorium'      => $_POST['laboratorium'] ?? '',

            // PENUNJANG
            'penunjang'         => $_POST['penunjang'] ?? '', // Link drive Laboratorium
            'penunjang_lain'    => $_POST['penunjang_lain'] ?? '', // Pemeriksaan Penunjang

            // TERAPI
            'terapi'            => $_POST['terapi'] ?? '', // Terapi Saat Ini
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
              <h5 class="card-title mb-1"><strong>14. Pemeriksaan Fisik</strong></h5>

                <!-- General Form Elements -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

<!-- Keadaan Umum -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Keadaan Umum</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="keadaan_umum" value="<?= val('keadaan_umum', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>

<!-- Kesadaran -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Kesadaran</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="kesadaran" value="<?= val('kesadaran', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>

<!-- Tanda Vital -->
<div class="row mb-2">
    <label class="col-sm-12"><strong>Tanda – Tanda Vital</strong></label>
</div>

<!-- Tekanan Darah -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Tekanan Darah</strong></label>
    <div class="col-sm-9">
        <div class="input-group">
            <input type="text" class="form-control" name="tekanan_darah" value="<?= val('tekanan_darah', $existing_data) ?>" <?= $ro ?>>
            <span class="input-group-text">mmHg</span>
        </div>
    </div>
</div>

<!-- Denyut Nadi -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Denyut Nadi</strong></label>
    <div class="col-sm-9">
        <div class="input-group">
            <input type="text" class="form-control" name="nadi" value="<?= val('nadi', $existing_data) ?>" <?= $ro ?>>
            <span class="input-group-text">x/menit</span>
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

<!-- Pernapasan -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Pernapasan</strong></label>
    <div class="col-sm-9">
        <div class="input-group">
            <input type="text" class="form-control" name="pernapasan" value="<?= val('pernapasan', $existing_data) ?>" <?= $ro ?>>
            <span class="input-group-text">x/menit</span>
        </div>
    </div>
</div>

<!-- Berat Badan -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Berat Badan</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="bb" value="<?= val('bb', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>

<!-- Tinggi Badan -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Tinggi Badan</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="tb" value="<?= val('tb', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>

<!-- KEPALA -->
<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>Kepala</strong></label>
</div>

<div class="row mb-2">
    <label class="col-sm-12"><strong>Inspeksi</strong></label>
</div>

<!-- Rambut & Hygiene -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Keadaan Rambut & Hygiene</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="rambut" value="<?= val('rambut', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>

<!-- Warna -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Warna Rambut</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="warna_rambut" value="<?= val('warna_rambut', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>

<!-- Penyebaran -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Penyebaran</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="penyebaran" value="<?= val('penyebaran', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>

<!-- Mudah Rontok -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Mudah Rontok</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="rontok" value="<?= val('rontok', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>
<!-- Kebersihan Rambut -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Kebersihan Rambut</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="kebersihan_rambut" value="<?= val('kebersihan_rambut', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>
<!-- Benjolan -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>- Benjolan</strong></label>
    <div class="col-sm-9 d-flex align-items-center gap-3 flex-wrap">
        <strong>:</strong>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="benjolan" value="ada" <?= val('benjolan', $existing_data)=='ada' ? 'checked' : '' ?> <?= $ro ?>>
            <label class="form-check-label"><strong>Ada</strong></label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="benjolan" value="tidak" <?= val('benjolan', $existing_data)=='tidak' ? 'checked' : '' ?> <?= $ro ?>>
            <label class="form-check-label"><strong>Tidak ada</strong></label>
        </div>
        <input type="text" class="form-control" style="max-width:200px" name="benjolan_keterangan" value="<?= val('benjolan_keterangan', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>

<!-- Nyeri Tekan -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>- Nyeri Tekan</strong></label>
    <div class="col-sm-9 d-flex align-items-center gap-3 flex-wrap">
        <strong>:</strong>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="nyeri_tekan" value="ada" <?= val('nyeri_tekan', $existing_data)=='ada' ? 'checked' : '' ?> <?= $ro ?>>
            <label class="form-check-label"><strong>Ada</strong></label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="nyeri_tekan" value="tidak" <?= val('nyeri_tekan', $existing_data)=='tidak' ? 'checked' : '' ?> <?= $ro ?>>
            <label class="form-check-label"><strong>Tidak ada</strong></label>
        </div>
        <input type="text" class="form-control" style="max-width:200px" name="nyeri_tekan_keterangan" value="<?= val('nyeri_tekan_keterangan', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>

<!-- Tekstur Rambut -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>- Tekstur Rambut</strong></label>
    <div class="col-sm-9 d-flex align-items-center gap-3 flex-wrap">
        <strong>:</strong>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="tekstur_rambut" value="kasar" <?= val('tekstur_rambut', $existing_data)=='kasar' ? 'checked' : '' ?> <?= $ro ?>>
            <label class="form-check-label"><strong>Kasar</strong></label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="tekstur_rambut" value="halus" <?= val('tekstur_rambut', $existing_data)=='halus' ? 'checked' : '' ?> <?= $ro ?>>
            <label class="form-check-label"><strong>Halus</strong></label>
        </div>
        <input type="text" class="form-control" style="max-width:200px" name="tekstur_rambut_keterangan" value="<?= val('tekstur_rambut_keterangan', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>

<!-- Wajah -->
<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>Wajah</strong></label>
</div>

<div class="row mb-2">
    <label class="col-sm-12"><strong>Inspeksi</strong></label>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>- Simetris</strong></label>
    <div class="col-sm-9 d-flex align-items-center gap-3 flex-wrap">
        <strong>:</strong>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="simetris" value="ya" <?= val('simetris', $existing_data)=='ya' ? 'checked' : '' ?> <?= $ro ?>>
            <label class="form-check-label"><strong>Simetris</strong></label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="simetris" value="tidak" <?= val('simetris', $existing_data)=='tidak' ? 'checked' : '' ?> <?= $ro ?>>
            <label class="form-check-label"><strong>Tidak</strong></label>
        </div>
        <input type="text" class="form-control" style="max-width:200px" name="simetris_keterangan" value="<?= val('simetris_keterangan', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>

<!-- Bentuk Wajah -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>- Bentuk Wajah</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="bentuk_wajah" value="<?= val('bentuk_wajah', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>



<!-- Palpasi -->
<div class="row mb-2">
    <label class="col-sm-12"><strong>Palpasi</strong></label>
</div>

<!-- Nyeri Tekan -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>- Nyeri Tekan</strong></label>
    <div class="col-sm-9 d-flex align-items-center gap-3 flex-wrap">
        <strong>:</strong>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="nyeri_wajah" value="ya" <?= val('nyeri_wajah', $existing_data)=='ya' ? 'checked' : '' ?> <?= $ro ?>>
            <label class="form-check-label"><strong>Ada</strong></label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="nyeri_wajah" value="tidak" <?= val('nyeri_wajah', $existing_data)=='tidak' ? 'checked' : '' ?> <?= $ro ?>>
            <label class="form-check-label"><strong>Tidak</strong></label>
        </div>
        <input type="text" class="form-control" style="max-width:200px" name="nyeri_wajah_keterangan" value="<?= val('nyeri_wajah_keterangan', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>

<!-- Data Lain -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Data Lain</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="data_wajah" value="<?= val('data_wajah', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>
<!-- MATA -->
<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>Mata</strong></label>
</div>

<div class="row mb-2">
    <label class="col-sm-12"><strong>Inspeksi</strong></label>
</div>

<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Palpebra</strong></label>
    <div class="col-sm-9">
        <div class="mb-2">
            Edema:
            <input type="radio" name="edema_palpera" value="ya" <?= val('edema_palpera', $existing_data)=='ya' ? 'checked' : '' ?> <?= $ro ?>> Ya
            <input type="radio" name="edema_palpera" value="tidak" <?= val('edema_palpera', $existing_data)=='tidak' ? 'checked' : '' ?> <?= $ro ?>> Tidak
        </div>
        <div class="mb-2">
            Radang:
            <input type="radio" name="radang_palpebra" value="ya" <?= val('radang_palpebra', $existing_data)=='ya' ? 'checked' : '' ?> <?= $ro ?>> Ya
            <input type="radio" name="radang_palpebra" value="tidak" <?= val('radang_palpebra', $existing_data)=='tidak' ? 'checked' : '' ?> <?= $ro ?>> Tidak
        </div>
    </div>
</div>

<!-- Sclera -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Sclera</strong></label>
    <div class="col-sm-9">
        <input type="radio" name="sclera" value="icterus" <?= val('sclera', $existing_data)=='icterus' ? 'checked' : '' ?> <?= $ro ?>> Icterus
        <input type="radio" name="sclera" value="tidak" <?= val('sclera', $existing_data)=='tidak' ? 'checked' : '' ?> <?= $ro ?>> Tidak
    </div>
</div>

<!-- Conjungtiva -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Conjungtiva</strong></label>
    <div class="col-sm-9">
        <div class="mb-2">
            <input type="radio" name="radang_conjungtiva" value="radang" <?= val('radang_conjungtiva', $existing_data)=='radang' ? 'checked' : '' ?> <?= $ro ?>> Radang
            <input type="radio" name="radang_conjungtiva" value="tidak" <?= val('radang_conjungtiva', $existing_data)=='tidak' ? 'checked' : '' ?> <?= $ro ?>> Tidak
        </div>
        <div class="mb-2">
            <input type="radio" name="anemis" value="anemis" <?= val('anemis', $existing_data)=='anemis' ? 'checked' : '' ?> <?= $ro ?>> Anemis
            <input type="radio" name="anemis" value="tidak" <?= val('anemis', $existing_data)=='tidak' ? 'checked' : '' ?> <?= $ro ?>> Tidak
        </div>
    </div>
</div>

<!-- Pupil -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Pupil</strong></label>
    <div class="col-sm-9">
        <div class="mb-2">
            <input type="radio" name="pupil_bentuk" value="isokor" <?= val('pupil_bentuk', $existing_data)=='isokor' ? 'checked' : '' ?> <?= $ro ?>> Isokor
            <input type="radio" name="pupil_bentuk" value="anisokor" <?= val('pupil_bentuk', $existing_data)=='anisokor' ? 'checked' : '' ?> <?= $ro ?>> Anisokor
        </div>
        <div class="mb-2">
            <input type="radio" name="pupil_ukuran" value="myosis" <?= val('pupil_ukuran', $existing_data)=='myosis' ? 'checked' : '' ?> <?= $ro ?>> Myosis
            <input type="radio" name="pupil_ukuran" value="midriasis" <?= val('pupil_ukuran', $existing_data)=='midriasis' ? 'checked' : '' ?> <?= $ro ?>> Midriasis
        </div>
    </div>
</div>

<!-- Refleks Cahaya -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-5 col-form-label"><strong>Refleks pupil terhadap cahaya</strong></label>
</div>

<!-- Posisi Mata -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Posisi Mata</strong></label>
    <div class="col-sm-9">
        <input type="radio" name="posisi_mata" value="simetris" <?= val('posisi_mata', $existing_data)=='simetris' ? 'checked' : '' ?> <?= $ro ?>> Simetris
        <input type="radio" name="posisi_mata" value="tidak" <?= val('posisi_mata', $existing_data)=='tidak' ? 'checked' : '' ?> <?= $ro ?>> Tidak
        <input type="text" class="form-control mt-2" name="posisi_mata_keterangan" value="<?= val('posisi_mata_keterangan', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>

<!-- Gerakan Bola Mata -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Gerakan Bola Mata</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="gerakan_mata" value="<?= val('gerakan_mata', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>

<!-- Penutupan Kelopak -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Penutupan Kelopak mata</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="kelopak" value="<?= val('kelopak', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>

<!-- Bulu Mata -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Keadaan Bulu Mata</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="bulu_mata" value="<?= val('bulu_mata', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>

<!-- Penglihatan -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Penglihatan</strong></label>
    <div class="col-sm-9">
        <div class="mb-2">
            <input type="radio" name="kabur" value="kabur" <?= val('kabur', $existing_data)=='kabur' ? 'checked' : '' ?> <?= $ro ?>> Kabur
            <input type="radio" name="kabur" value="tidak" <?= val('kabur', $existing_data)=='tidak' ? 'checked' : '' ?> <?= $ro ?>> Tidak
        </div>
        <div class="mb-2">
            <input type="radio" name="diplopia" value="diplopia" <?= val('diplopia', $existing_data)=='diplopia' ? 'checked' : '' ?> <?= $ro ?>> Diplopia
            <input type="radio" name="diplopia" value="tidak" <?= val('diplopia', $existing_data)=='tidak' ? 'checked' : '' ?> <?= $ro ?>> Tidak
        </div>
    </div>
</div>

<!-- Data Lain -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Data Lain</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="data_mata" value="<?= val('data_mata', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>

<!-- HIDUNG & SINUS -->
<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>Hidung & Sinus</strong></label>
</div>

<div class="row mb-2">
    <label class="col-sm-12"><strong>Inspeksi</strong></label>
</div>

<!-- Bentuk -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Bentuk Hidung</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="bentuk_hidung"value="<?= val('bentuk_hidung', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>

<!-- Septum -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Septum</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="septum"value="<?= val('septum', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>

<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Secret / Cairan</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="secret" value="<?= val('secret', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>

<!-- Data Lain -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Data Lain</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="data_hidung" value="<?= val('data_hidung', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>


<!-- TELINGA -->
<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>Telinga</strong></label>
</div>

<div class="row mb-2">
    <label class="col-sm-12"><strong>Inspeksi</strong></label>
</div>

<!-- Lubang Telinga -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Lubang Telinga</strong></label>
    <div class="col-sm-9">
        <div class="mb-2">
            <input type="radio" name="telinga" value="bersih" <?= val('telinga', $existing_data)=='bersih' ? 'checked' : '' ?> <?= $ro ?>> Bersih
            <input type="radio" name="telinga" value="serumen" <?= val('telinga', $existing_data)=='serumen' ? 'checked' : '' ?> <?= $ro ?>> Serumen
            <input type="radio" name="telinga" value="nanah" <?= val('telinga', $existing_data)=='nanah' ? 'checked' : '' ?> <?= $ro ?>> Nanah
        </div>
    </div>
</div>

<div class="row mb-2">
    <label class="col-sm-12"><strong>Palpasi</strong></label>
</div>

<!-- Nyeri Tekan -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Nyeri Tekan</strong></label>
    <div class="col-sm-9">
        <input type="radio" name="nyeri_telinga" value="ya" <?= val('nyeri_telinga', $existing_data)=='ya' ? 'checked' : '' ?> <?= $ro ?>> Ya
        <input type="radio" name="nyeri_telinga" value="tidak" <?= val('nyeri_telinga', $existing_data)=='tidak' ? 'checked' : '' ?> <?= $ro ?>> Tidak
    </div>
</div>

<!-- MULUT -->
<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>Mulut</strong></label>
</div>

<div class="row mb-2">
    <label class="col-sm-12"><strong>Inspeksi</strong></label>
</div>

<!-- Gigi - Keadaan -->
 <div class="row mb-2">
    <label class="col-sm-12"><strong>Gigi</strong></label>
</div>
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Keadaan Gigi</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="keadaan_gigi" value="<?= val('keadaan_gigi', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>

<!-- Karang / Karies -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Karang Gigi / Karies</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="karies" value="<?= val('karies', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>

<!-- Gusi -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Gusi</strong></label>
    <div class="col-sm-9 d-flex align-items-center gap-3 flex-wrap">
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="gusi" value="merah" <?= strpos(val('gusi', $existing_data),'merah')!==false ? 'checked' : '' ?> <?= $ro ?>>
            <label class="form-check-label">Merah</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="gusi" value="radang" <?= strpos(val('gusi', $existing_data),'radang')!==false ? 'checked' : '' ?> <?= $ro ?>>
            <label class="form-check-label">Radang</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="gusi" value="tidak" <?= strpos(val('gusi', $existing_data),'tidak')!==false ? 'checked' : '' ?> <?= $ro ?>>
            <label class="form-check-label">Tidak</label>
        </div>
        <input type="text" class="form-control" style="max-width:200px" name="gusi_keterangan" value="<?= val('gusi_keterangan', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>

<!-- Lidah -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Lidah</strong></label>
    <div class="col-sm-9">
        <input type="radio" name="lidah" value="kotor" <?= val('lidah', $existing_data)=='kotor' ? 'checked' : '' ?> <?= $ro ?>> Kotor
        <input type="radio" name="lidah" value="tidak" <?= val('lidah', $existing_data)=='tidak' ? 'checked' : '' ?> <?= $ro ?>> Tidak
    </div>
</div>
<!-- Bibir - Warna -->
<div class="row mb-3 d-flex align-items-center gap-3 flex-wrap">
    <label class="col-sm-2 col-form-label"><strong>Bibir (Warna)</strong></label>
    <div class="col-sm-9">
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="bibir_warna" value="cianosis" <?= strpos(val('bibir_warna', $existing_data),'cianosis')!==false ? 'checked' : '' ?> <?= $ro ?>>
            <label class="form-check-label">Cianosis</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="bibir_warna" value="pucat" <?= strpos(val('bibir_warna', $existing_data),'pucat')!==false ? 'checked' : '' ?> <?= $ro ?>>
            <label class="form-check-label">Pucat</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="bibir_warna" value="tidak" <?= strpos(val('bibir_warna', $existing_data),'tidak')!==false ? 'checked' : '' ?> <?= $ro ?>>
            <label class="form-check-label">Tidak</label>
        </div>
        <input type="text" class="form-control" style="max-width:200px" name="bibir_warna_keterangan" value="<?= val('bibir_warna_keterangan', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>

<!-- Bibir - Kondisi -->
<div class="row mb-3 d-flex align-items-center gap-3 flex-wrap">
    <label class="col-sm-2 col-form-label"><strong>Bibir (Kondisi)</strong></label>
    <div class="col-sm-9">
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="bibir_kondisi" value="basah" <?= strpos(val('bibir_kondisi', $existing_data),'basah')!==false ? 'checked' : '' ?> <?= $ro ?>>
            <label class="form-check-label">Basah</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="bibir_kondisi" value="kering" <?= strpos(val('bibir_kondisi', $existing_data),'kering')!==false ? 'checked' : '' ?> <?= $ro ?>>
            <label class="form-check-label">Kering</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="bibir_kondisi" value="pecah" <?= strpos(val('bibir_kondisi', $existing_data),'pecah')!==false ? 'checked' : '' ?> <?= $ro ?>>
            <label class="form-check-label">Pecah</label>
        </div>
        <input type="text" class="form-control" style="max-width:200px" name="bibir_kondisi_keterangan" value="<?= val('bibir_kondisi_keterangan', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>

<!-- Bau Mulut -->
<div class="row mb-3 d-flex align-items-center gap-3 flex-wrap">
    <label class="col-sm-2 col-form-label"><strong>Mulut Berbau</strong></label>
    <div class="col-sm-9">
        <input type="radio" name="bau_mulut" value="ya" <?= val('bau_mulut', $existing_data)=='ya' ? 'checked' : '' ?> <?= $ro ?>> Ya
        <input type="radio" name="bau_mulut" value="tidak" <?= val('bau_mulut', $existing_data)=='tidak' ? 'checked' : '' ?> <?= $ro ?>> Tidak
        <input type="text" class="form-control" style="max-width:200px" name="bau_mulut_keterangan" value="<?= val('bau_mulut_keterangan', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>

<!-- Bicara -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Kemampuan Bicara</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="bicara" value="<?= val('bicara', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>

<!-- Data Lain -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Data Lain</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="data_mulut" value="<?= val('data_mulut', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>


<!-- TENGGOROKAN -->
<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>Tenggorokan</strong></label>
</div>

<!-- Warna Mukosa -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Warna Mukosa</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="mukosa" value="<?= val('mukosa', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>

<!-- Nyeri Tekan -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Nyeri Tekan</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="nyeri_tenggorokan" value="<?= val('nyeri_tenggorokan', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>

<!-- Nyeri Menelan -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Nyeri Menelan</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="menelan" value="<?= val('menelan', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>
<!-- LEHER -->
<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>Leher</strong></label>
</div>

<div class="row mb-2">
    <label class="col-sm-12"><strong>Palpasi</strong></label>
</div>

<!-- Kelenjar Limfe -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Kelenjar Limfe</strong></label>
    <div class="col-sm-9">
        <input type="radio" name="limfe" value="membesar" <?= val('limfe', $existing_data)=='membesar' ? 'checked' : '' ?> <?= $ro ?>> Membesar
        <input type="radio" name="limfe" value="tidak" <?= val('limfe', $existing_data)=='tidak' ? 'checked' : '' ?> <?= $ro ?>> Tidak
    </div>
</div>

<!-- Data Lain -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Data Lain</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="data_leher" value="<?= val('data_leher', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>

<!-- THORAX -->
<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>Thorax dan Pernapasan</strong></label>
</div>

<!-- Bentuk Dada -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Bentuk Dada</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="bentuk_dada" value="<?= val('bentuk_dada', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>

<!-- Irama Pernapasan -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Irama Pernapasan</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="irama_nafas" value="<?= val('irama_nafas', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>

<!-- Pengembangan -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Pengembangan di Waktu Bernapas</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="pengembangan" value="<?= val('pengembangan', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>

<!-- Tipe Pernapasan -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Tipe Pernapasan</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="tipe_nafas" value="<?= val('tipe_nafas', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>

<!-- AUSKULTASI -->
<div class="row mb-2">
    <label class="col-sm-12"><strong>Auskultasi</strong></label>
</div>

<!-- Suara Nafas -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Suara Nafas</strong></label>
    <div class="col-sm-9">
        <input type="radio" name="=suara_auskultas" value="vesikuler" <?= val('suara_auskultas', $existing_data)=='vesikuler' ? 'checked' : '' ?> <?= $ro ?>> Vesikuler
        <input type="radio" name="suara_auskultas" value="bronchial" <?= val('suara_auskultas', $existing_data)=='bronchial' ? 'checked' : '' ?> <?= $ro ?>> Bronchial
        <input type="radio" name="suara_auskultas" value="bronchovesikuler" <?= val('suara_auskultas', $existing_data)=='bronchovesikuler' ? 'checked' : '' ?> <?= $ro ?>> Bronchovesikuler
    </div>
</div>

<!-- Suara Tambahan -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-3 col-form-label"><strong>Suara Tambahan</strong></label>
    <div class="col-sm-9">
        <input type="radio" name="suara_tambahan" value="ronchi" <?= val('suara_tambahan', $existing_data)=='ronchi' ? 'checked' : '' ?> <?= $ro ?>> Ronchi
        <input type="radio" name="suara_tambahan" value="wheezing" <?= val('suara_tambahan', $existing_data)=='wheezing' ? 'checked' : '' ?> <?= $ro ?>> Wheezing
        <input type="radio" name="suara_tambahan" value="rales" <?= val('suara_tambahan', $existing_data)=='rales' ? 'checked' : '' ?> <?= $ro ?>> Rales
    </div>
</div>
<!-- PERKUSI -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Perkusi</strong></label>
    <div class="col-sm-9">
        <!-- Redup -->
        <input type="radio" name="perkusiauskultasiii" value="redup" <?= val('perkusiauskultasiii', $existing_data) == 'redup' ? 'checked' : '' ?> <?= $ro ?>> Redup

        <!-- Pekak -->
        <input type="radio" name="perkusiauskultasiii" value="pekak" <?= val('perkusiauskultasiii', $existing_data) == 'pekak' ? 'checked' : '' ?> <?= $ro ?>> Pekak

        <!-- Hypersonor -->
        <input type="radio" name="perkusiauskultasiii" value="hypersonor" <?= val('perkusiauskultasiii', $existing_data) == 'hypersonor' ? 'checked' : '' ?> <?= $ro ?>> Hypersonor

        <!-- Tympani -->
        <input type="radio" name="perkusiauskultasiii" value="tympani" <?= val('perkusiauskultasiii', $existing_data) == 'tympani' ? 'checked' : '' ?> <?= $ro ?>> Tympani
    </div>
</div>

<!-- JANTUNG -->
<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>Jantung</strong></label>
</div>

<div class="row mb-2">
    <label class="col-sm-12"><strong>Palpasi</strong></label>
</div>

<!-- Ictus Cordis -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Ictus Cordis</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="ictus_cordis" value="<?= val('ictus_cordis', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>

<!-- Pembesaran -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Pembesaran Jantung</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="pembesaran_jantung" value="<?= val('pembesaran_jantung', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>

<!-- Auskultasi -->
<div class="row mb-2">
    <label class="col-sm-12"><strong>Auskultasi</strong></label>
</div>

<!-- BJ I -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>BJ I</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="bj1" value="<?= val('bj1', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>

<!-- BJ II -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>BJ II</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="bj2" value="<?= val('bj2', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>

<!-- BJ III -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>BJ III</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="bj3" value="<?= val('bj3', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>

<!-- Bunyi Tambahan -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Bunyi Tambahan</strong></label>
    <div class="col-sm-9">
        <textarea class="form-control mb-2" name="bunyi_tambahan" <?= $ro ?>><?= val('bunyi_tambahan', $existing_data) ?></textarea>
    </div>
</div>

<!-- Data Lain -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Data Lain</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="data_lain_jantung" value="<?= val('data_lain_jantung', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>

<!-- ABDOMEN -->
<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>Abdomen</strong></label>
</div>

<div class="row mb-2">
    <label class="col-sm-12"><strong>Inspeksi</strong></label>
</div>

<!-- Membuncit -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Membuncit</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="membuncit" value="<?= val('membuncit', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>

<!-- Luka -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label">
        <strong>- Ada luka</strong>
    </label>

    <div class="col-sm-9 d-flex align-items-center gap-3 flex-wrap">
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="luka_abdomen" value="ada" <?= val('luka_abdomen', $existing_data) == 'ada' ? 'checked' : '' ?> <?= $ro ?>>
            <label class="form-check-label">Ada</label>
        </div>

        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="luka_abdomen" value="tidak" <?= val('luka_abdomen', $existing_data) == 'tidak' ? 'checked' : '' ?> <?= $ro ?>>
            <label class="form-check-label">Tidak</label>
        </div>

        <input type="text" class="form-control" style="max-width:200px;" name="luka_abdomen_lain" value="<?= val('luka_abdomen_lain', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>

<!-- AUSKULTASI -->
<div class="row mb-2">
    <label class="col-sm-12"><strong>Auskultasi</strong></label>
</div>

<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Peristaltik</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="peristaltik" value="<?= val('peristaltik', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>
<!-- PALPASI -->
<div class="row mb-2">
    <label class="col-sm-12"><strong>Palpasi</strong></label>
</div>

<!-- Hepar -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Hepar</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="hepar" value="<?= val('hepar', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>

<!-- Lien -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Lien</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="lien" value="<?= val('lien', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>

<!-- Nyeri Tekan -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Nyeri Tekan</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="nyeri" value="<?= val('nyeri', $existing_data) ?>" <?= $ro ?>>
   </div>
</div>
<!-- PERKUSI -->
<div class="row mb-2">
    <label class="col-sm-12"><strong>Perkusi</strong></label>
</div>

<!-- Tympani -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Tympani</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="tympani" value="<?= val('tympani', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>

<!-- Redup -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Redup</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="redup" value="<?= val('redup', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>

<!-- Data Lain -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Data Lain</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="data_abdomen" value="<?= val('data_abdomen', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>
<!-- GENITALIA -->
<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>Genitalia</strong></label>
</div>

<!-- LAKI-LAKI -->
<div class="row mb-2">
    <label class="col-sm-12"><strong>Anak Laki-laki</strong></label>
</div>

<!-- Fistula -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Fistula Urinari (Laki-laki)</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="fistula_pria" value="<?= val('fistula_pria', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>

<!-- Uretra -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Lubang Uretra</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="uretra" value="<?= val('uretra', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>

<!-- Skrotum -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Skrotum</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="skrotum" value="<?= val('skrotum', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>

<!-- Genital Ganda -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Genitalia Ganda</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="genital_ganda" value="<?= val('genital_ganda', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>

<!-- Hidrokel -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Hidrokel</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="hidrokel_pria" value="<?= val('hidrokel_pria', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>
<!-- PEREMPUAN -->
<div class="row mb-2">
    <label class="col-sm-12"><strong>Anak Perempuan</strong></label>
</div>

<!-- Labia -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Labia & Klitoris</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="labia" value="<?= val('labia', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>

<!-- Fistula Wanita -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Fistula Urogenital (Perempuan)</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="fistula_wanita" value="<?= val('fistula_wanita', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>

<!-- Hidrokel Wanita -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Hidrokel</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="hidrokel_wanita" value="<?= val('hidrokel_wanita', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>


<!-- ANUS -->
<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>Anus</strong></label>
</div>
<!-- Lubang Anal -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Lubang Anal Paten</strong></label>
    <div class="col-sm-9">
        <input type="radio" name="anus_paten" value="ya" <?= val('anus_paten', $existing_data) == 'ya' ? 'checked' : '' ?> <?= $ro ?>> Ya
        <input type="radio" name="anus_paten" value="tidak" <?= val('anus_paten', $existing_data) == 'tidak' ? 'checked' : '' ?> <?= $ro ?>> Tidak
    </div>
</div>

<!-- Mekonium -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label">
        <strong>Lintasan Mekonium (36 jam)</strong>
    </label>
    <div class="col-sm-9">
        <input type="radio" name="mekonium" value="ada" <?= val('mekonium', $existing_data) == 'ada' ? 'checked' : '' ?> <?= $ro ?>> Ada
        <input type="radio" name="mekonium" value="tidak" <?= val('mekonium', $existing_data) == 'tidak' ? 'checked' : '' ?> <?= $ro ?>> Tidak
    </div>
</div>
<!-- EKSTREMITAS ATAS -->
<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>Ekstremitas Atas</strong></label>
</div>

<div class="row mb-2">
    <label class="col-sm-12"><strong>Motorik</strong></label>
</div>

<!-- Pergerakan -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Pergerakan Kanan/Kiri</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="gerak_atas" value="<?= val('gerak_atas', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>

<!-- Abnormal -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Pergerakan Abnormal</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="abnormal_atas" value="<?= val('abnormal_atas', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>

<!-- Kekuatan -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Kekuatan Otot Kanan/Kiri</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="kekuatan_atas" value="<?= val('kekuatan_atas', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>

<!-- Koordinasi -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Koordinasi Gerak</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="koordinasi_atas" value="<?= val('koordinasi_atas', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>

<!-- SENSORI -->
<div class="row mb-2">
    <label class="col-sm-12"><strong>Sensori</strong></label>
</div>

<!-- Nyeri -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Nyeri</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="nyeri_atas" value="<?= val('nyeri_atas', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>

<!-- Suhu -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Rangsang Suhu</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="suhu_atas" value="<?= val('suhu_atas', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>

<!-- Raba -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Rasa Raba</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="raba_atas" value="<?= val('raba_atas', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>


<!-- EKSTREMITAS BAWAH -->
<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>Ekstremitas Bawah</strong></label>
</div>

<div class="row mb-2">
    <label class="col-sm-12"><strong>Motorik</strong></label>
</div>

<!-- Gaya Berjalan -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Gaya Berjalan</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="gaya_jalan" value="<?= val('gaya_jalan', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>

<!-- Kekuatan -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Kekuatan Kanan/Kiri</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="kekuatan_bawah" value="<?= val('kekuatan_bawah', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>

<!-- Tonus -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Tonus Otot Kanan/Kiri</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="tonus_bawah" value="<?= val('tonus_bawah', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>

<div class="row mb-2">
    <label class="col-sm-12"><strong>Sensori</strong></label>
</div>

<!-- Nyeri -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Nyeri</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="nyeri_bawah" value="<?= val('nyeri_bawah', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>

<!-- Suhu -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Rangsang Suhu</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="suhu_bawah" value="<?= val('suhu_bawah', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>

<!-- Raba -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Rasa Raba</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="raba_bawah" value="<?= val('raba_bawah', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>

<!-- REFLEKS -->
<div class="row mb-2">
    <label class="col-sm-12 "><strong>Tanda Perangsangan Selaput Otak</strong></label>
</div>

<!-- Kaku Kuduk -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Kaku kuduk</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="kaku_kuduk" value="<?= val('kaku_kuduk', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>

<!-- Kernig -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Kernig Sign</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="kernig" value="<?= val('kernig', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>

<!-- Brudzinski -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Refleks Brudzinski</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="brudzinski" value="<?= val('brudzinski', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>

<!-- Refleks Bayi -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Refleks pada bayi</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="refleks_bayi" value="<?= val('refleks_bayi', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>

<!-- Iddol -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Refleks Iddol</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="iddol" value="<?= val('iddol', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>

<!-- Startel -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Refleks Startel</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="startel" value="<?= val('startel', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>

<!-- Sucking -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Refleks sucking (isap)</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="sucking" value="<?= val('sucking', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>

<!-- Rooting -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Refleks rooting (menoleh)</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="rooting" value="<?= val('rooting', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>

<!-- Gawn -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Refleks Gawn</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="gawn" value="<?= val('gawn', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>

<!-- Grabella -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Refleks grabella</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="grabella" value="<?= val('grabella', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>

<!-- Ekruction -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Refleks ekruction</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="ekruction" value="<?= val('ekruction', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>

<!-- Moro -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Refleks moro</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="moro" value="<?= val('moro', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>

<!-- Grasping -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Refleks garsping</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="grasping" value="<?= val('grasping', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>

<!-- Peres -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Refleks peres</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="peres" value="<?= val('peres', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>

<!-- Kremaster -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Refleks kremaster</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="kremaster" value="<?= val('kremaster', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>
<!-- INTEGUMEN -->
<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>Integumen</strong></label>
</div>

<!-- Turgor -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Turgor Kulit</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="turgor" value="<?= val('turgor', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>

<!-- Finger Print -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Finger Print di Dahi</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="finger_print" value="<?= val('finger_print', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>

<!-- Lesi -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Adanya Lesi</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="lesi" value="<?= val('lesi', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>

<!-- Kebersihan -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Kebersihan Kulit</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="kebersihan" value="<?= val('kebersihan', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>

<!-- Kelembaban -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Kelembaban Kulit</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="kelembaban" value="<?= val('kelembaban', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>

<!-- Warna -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Warna Kulit</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="warna_kulit" value="<?= val('warna_kulit', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>
<!-- 15. PERKEMBANGAN -->
<div class="row mb-2">
    <label class="col-sm-12 text-primary">
        <strong>15. Pemeriksaan Tingkat Perkembangan (0 – 6 Tahun)</strong>
    </label>
    <label class="col-sm-12"><em>Dengan menggunakan DDST</em></label>
</div>


<!-- Motorik Kasar -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Motorik Kasar</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="motorik_kasar_input" value="<?= val('motorik_kasar_input', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>

<!-- Motorik Halus -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Motorik Halus</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="motorik_halus_input" value="<?= val('motorik_halus_input', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>

<!-- Bahasa -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Bahasa</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="bahasa_input" value="<?= val('bahasa_input', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>

<!-- Personal Social -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Personal Social</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="personal_social_input" value="<?= val('personal_social_input', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>
<!-- 16. TEST DIAGNOSTIK -->
<div class="row mb-2">
    <label class="col-sm-12 text-primary">
        <strong>16. Test Diagnostik</strong>
    </label>
</div>

<div class="row mb-3 align-items-start">
    <div class="col-sm-11">
        <textarea class="form-control" rows="3" name="diagnostik" <?= $ro ?>><?= val('diagnostik', $existing_data) ?></textarea>
    </div>
</div>

<!-- 17. LABORATORIUM -->
<div class="row mb-2">
    <label class="col-sm-12 text-primary">
        <strong>17. Laboratorium</strong>
    </label>
</div>

<div class="row mb-3 align-items-start">
    <div class="col-sm-11">
        <textarea class="form-control mb-2" rows="3" name="laboratorium" <?= $ro ?>><?= val('laboratorium', $existing_data) ?></textarea>
    </div>
</div>

<!-- PENUNJANG -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Link drive Laboratorium</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="penunjang" value="<?= val('penunjang', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>

<!-- PENUNJANG -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Pemeriksaan Penunjang</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="penunjang" placeholder="Foto Rontgen, CT Scan, MRI, USG, EEG, ECG" value="<?= val('penunjang', $existing_data) ?>" <?= $ro ?>>
    </div>
</div>

<!-- TERAPI -->
<div class="row mb-2">
    <label class="col-sm-12 text-primary">
        <strong>Terapi Saat Ini (ditulis dengan rinci)</strong>
    </label>
</div>

<div class="row mb-3 align-items-start">
    <div class="col-sm-11">
        <textarea class="form-control mb-2" rows="4" name="terapi" <?= $ro ?>><?= val('terapi', $existing_data) ?></textarea>
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
                
                 
