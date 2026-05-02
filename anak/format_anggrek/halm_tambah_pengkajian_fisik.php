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


// =============================================
// HANDLE POST - MAHASISWA SIMPAN DATA
// =============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $level === 'Mahasiswa') {

    if (isLocked($submission)) {
        redirectWithMessage($_SERVER['REQUEST_URI'], 'error', 'Data tidak dapat diubah karena sedang dalam proses review.');
    }

    $text_fields = [
        // Keadaan Umum & Vital Signs
        'keadaan_umum',
        'kesadaran',
        'tekanan_darah',
        'nadi',
        'suhu',
        'pernapasan',
        'bb',
        'tb',

        // Kepala 
        'rambut',
        'warna_rambut',
        'penyebaran',
        'rontok',
        'kebersihan_rambut',
        'benjolan',
        'benjolan_keterangan',
        'nyeri_tekan',
        'nyeri_tekan_keterangan',
        'tekstur_rambut',
        'tekstur_rambut_keterangan',
        // wajah
        'simetris',
        'simetris_keterangan',
        'bentuk_wajah',
        'nyeri_wajah',
        'nyeri_wajah_keterangan',
        'data_wajah',




        // Mata
        'edema_palpebra',
        'radang_palpebra',
        'sclera',
        'radang_conjungtiva',
        'anemis',
        'pupil_bentuk',
        'pupil_ukuran',
        'posisi_mata',
        'posisi_mata_keterangan',
        'gerakan_mata',
        'kelopak',
        'bulu_mata',
        'kabur',
        'diplopia',
        'data_mata',

        // Hidung & Sinus
        'bentuk_hidung',
        'septum',
        'secret',
        'data_hidung',

        // Telinga
        'telinga',
        'nyeri_telinga',

        // Mulut
        'keadaan_gigi',
        'karies',
        'gusi',
        'bibir_warna',
        'gusi_keterangan',
        'lidah',
        'bibir_warna_keterangan',
        'bibir_kondisi',
        'bibir_kondisi_keterangan',
        'bau_mulut',
        'bau_mulut_keterangan',
        'bicara',
        'data_mulut',

        // Tenggorokan
        'mukosa',
        'nyeri_tenggorokan',
        'menelan',

        // Leher
        'limfe',
        'data_leher',

        // Thorax & Pernapasan
        'bentuk_dada',
        'irama_nafas',
        'pengembangan',
        'tipe_nafas',
        'suara_auskultas',
        'suara_tambahan',
        'perkusi',
        'perkusi_redup',
        'perkusi_peka',
        'perkusi_hypersonor',
        'perkusi_tympani',

        // Jantung
        'ictus_cordis',
        'pembesaran_jantung',
        'bj1',
        'bj2',
        'bj3',
        'bunyi_tambahan',
        'data_lain_jantung',

        // Abdomen
        'membuncit',
        'luka_abdomen',
        'luka_abdomen_lain',
        'peristaltik',
        'hepar',
        'lien',
        'nyeri',
        'tympani',
        'redup',
        'data_abdomen',

        // Genitalia Laki-laki
        'fistula_pria',
        'uretra',
        'skrotum',
        'genital_ganda',
        'hidrokel_pria',

        // Genitalia Perempuan
        'labia',
        'fistula_wanita',
        'hidrokel_wanita',

        // Anus
        'anus_paten',
        'mekonium',

        // Ekstremitas Atas
        'gerak_atas',
        'abnormal_atas',
        'kekuatan_atas',
        'koordinasi_atas',
        'nyeri_atas',
        'suhu_atas',
        'raba_atas',

        // Ekstremitas Bawah
        'gaya_jalan',
        'kekuatan_bawah',
        'tonus_bawah',
        'nyeri_bawah',
        'suhu_bawah',
        'raba_bawah',

        // Refleks
        'kaku_kuduk',
        'kernig',
        'brudzinski',
        'refleks_bayi',
        'iddol',
        'startel',
        'sucking',
        'rooting',
        'gawn',
        'grabella',
        'ekruction',
        'moro',
        'grasping',
        'peres',
        'kremaster',

        // Integumen
        'turgor',
        'finger_print',
        'lesi',
        'kebersihan',
        'kelembaban',
        'warna_kulit',

        // Perkembangan
        'motorik_kasar_input',
        'motorik_halus_input',
        'bahasa_input',
        'personal_social_input',

        // Test Diagnostik & Laboratorium
        'diagnostik',
        'laboratorium',
        'penunjang_link',
        'penunjang',
        'terapi',
    ];

    $data = [];
    foreach ($text_fields as $f) {
        $data[$f] = $_POST[$f] ?? '';
    }

    // Checkbox fields
    $checkbox_fields = ['bibir_warna', 'bibir_kondisi'];

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
$ro_disabled = $is_readonly ? 'disabled' : '';
?>

<main id="main" class="main">

    <?php include "anak/format_anggrek/tab.php"; ?>

    <section class="section dashboard">
        <?php include dirname(__DIR__, 2) . '/partials/notifikasi.php'; ?>
        <?php include dirname(__DIR__, 2) . '/partials/status_section.php'; ?>

        <section class="section dashboard">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-1"><strong>14. Pemeriksaan Fisik</strong></h5>

                    <!-- General Form Elements -->
                    <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">
                        <!-- KEADAAN UMUM -->
                        <div class="row mb-3 align-items-start">
                            <label class="col-sm-2 col-form-label"><strong>Keadaan Umum</strong></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="keadaan_umum" value="<?= htmlspecialchars($existing_data['keadaan_umum'] ?? '') ?>" <?= $ro ?>>
                            </div>
                        </div>

                        <!-- KESADARAN -->
                        <div class="row mb-3 align-items-start">
                            <label class="col-sm-2 col-form-label"><strong>Kesadaran</strong></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="kesadaran" value="<?= htmlspecialchars($existing_data['kesadaran'] ?? '') ?>" <?= $ro ?>>
                            </div>
                        </div>

                        <!-- TANDA VITAL -->
                        <div class="row mb-2">
                            <label class="col-sm-12"><strong>Tanda – Tanda Vital</strong></label>
                        </div>

                        <!-- Tekanan Darah -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Tekanan Darah</strong></label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="tekanan_darah" value="<?= htmlspecialchars($existing_data['tekanan_darah'] ?? '') ?>" <?= $ro ?>>
                                    <span class="input-group-text">mmHg</span>
                                </div>
                            </div>
                        </div>

                        <!-- Denyut Nadi -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Denyut Nadi</strong></label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="nadi" value="<?= htmlspecialchars($existing_data['nadi'] ?? '') ?>" <?= $ro ?>>
                                    <span class="input-group-text">x/menit</span>
                                </div>
                            </div>
                        </div>

                        <!-- Suhu -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Suhu</strong></label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="suhu" value="<?= htmlspecialchars($existing_data['suhu'] ?? '') ?>" <?= $ro ?>>
                                    <span class="input-group-text">°C</span>
                                </div>
                            </div>
                        </div>

                        <!-- Pernapasan -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Pernapasan</strong></label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="pernapasan" value="<?= htmlspecialchars($existing_data['pernapasan'] ?? '') ?>" <?= $ro ?>>
                                    <span class="input-group-text">x/menit</span>
                                </div>
                            </div>
                        </div>

                        <!-- Berat Badan -->
                        <div class="row mb-3 align-items-start">
                            <label class="col-sm-2 col-form-label"><strong>Berat Badan</strong></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="bb" value="<?= htmlspecialchars($existing_data['bb'] ?? '') ?>" <?= $ro ?>>
                            </div>
                        </div>

                        <!-- Tinggi Badan -->
                        <div class="row mb-3 align-items-start">
                            <label class="col-sm-2 col-form-label"><strong>Tinggi Badan</strong></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="tb" value="<?= htmlspecialchars($existing_data['tb'] ?? '') ?>" <?= $ro ?>>
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
                                <input type="text" class="form-control" name="rambut" value="<?= htmlspecialchars($existing_data['rambut'] ?? '') ?>" <?= $ro ?>>
                            </div>
                        </div>

                        <!-- Warna Rambut -->
                        <div class="row mb-3 align-items-start">
                            <label class="col-sm-2 col-form-label"><strong>Warna Rambut</strong></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="warna_rambut" value="<?= htmlspecialchars($existing_data['warna_rambut'] ?? '') ?>" <?= $ro ?>>
                            </div>
                        </div>

                        <!-- Penyebaran -->
                        <div class="row mb-3 align-items-start">
                            <label class="col-sm-2 col-form-label"><strong>Penyebaran</strong></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="penyebaran" value="<?= htmlspecialchars($existing_data['penyebaran'] ?? '') ?>" <?= $ro ?>>
                            </div>
                        </div>

                        <!-- Mudah Rontok -->
                        <div class="row mb-3 align-items-start">
                            <label class="col-sm-2 col-form-label"><strong>Mudah Rontok</strong></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="rontok" value="<?= htmlspecialchars($existing_data['rontok'] ?? '') ?>" <?= $ro ?>>
                            </div>
                        </div>

                        <!-- Kebersihan Rambut -->
                        <div class="row mb-3 align-items-start">
                            <label class="col-sm-2 col-form-label"><strong>Kebersihan Rambut</strong></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="kebersihan_rambut" value="<?= htmlspecialchars($existing_data['kebersihan_rambut'] ?? '') ?>" <?= $ro ?>>
                            </div>
                        </div>
                        <!-- Benjolan -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Benjolan</strong></label>
                            <div class="col-sm-9 d-flex align-items-center gap-3 flex-wrap">
                                <select class="form-select" name="benjolan" style="max-width:200px" <?= $ro_disabled ?>>
                                    <option value="">Pilih</option>
                                    <option value="ada" <?= ($existing_data['benjolan'] ?? '') === 'ada' ? 'selected' : '' ?>>Ada</option>
                                    <option value="tidak" <?= ($existing_data['benjolan'] ?? '') === 'tidak' ? 'selected' : '' ?>>Tidak ada</option>
                                </select>
                                <input type="text" class="form-control" style="max-width:425px" name="benjolan_keterangan" value="<?= htmlspecialchars($existing_data['benjolan_keterangan'] ?? '') ?>" <?= $ro ?> placeholder="">
                            </div>
                        </div>

                        <!-- Nyeri Tekan -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Nyeri Tekan</strong></label>
                            <div class="col-sm-9 d-flex align-items-center gap-3 flex-wrap">
                                <select class="form-select" name="nyeri_tekan" style="max-width:200px" <?= $ro_disabled ?>>
                                    <option value="">Pilih</option>
                                    <option value="ada" <?= ($existing_data['nyeri_tekan'] ?? '') === 'ada' ? 'selected' : '' ?>>Ada</option>
                                    <option value="tidak" <?= ($existing_data['nyeri_tekan'] ?? '') === 'tidak' ? 'selected' : '' ?>>Tidak ada</option>
                                </select>
                                <input type="text" class="form-control" style="max-width:425px" name="nyeri_tekan_keterangan" value="<?= htmlspecialchars($existing_data['nyeri_tekan_keterangan'] ?? '') ?>" <?= $ro ?> placeholder="">
                            </div>
                        </div>

                        <!-- Tekstur Rambut -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Tekstur Rambut</strong></label>
                            <div class="col-sm-9 d-flex align-items-center gap-3 flex-wrap">
                                <select class="form-select" name="tekstur_rambut" style="max-width:200px" <?= $ro_disabled ?>>
                                    <option value="">Pilih</option>
                                    <option value="kasar" <?= ($existing_data['tekstur_rambut'] ?? '') === 'kasar' ? 'selected' : '' ?>>Kasar</option>
                                    <option value="halus" <?= ($existing_data['tekstur_rambut'] ?? '') === 'halus' ? 'selected' : '' ?>>Halus</option>
                                </select>
                                <input type="text" class="form-control" style="max-width:425px" name="tekstur_rambut_keterangan" value="<?= htmlspecialchars($existing_data['tekstur_rambut_keterangan'] ?? '') ?>" <?= $ro ?> placeholder="">
                            </div>
                        </div>
                        <!-- Wajah -->
                        <div class="row mb-2">
                            <label class="col-sm-12 text-primary"><strong>Wajah</strong></label>
                        </div>

                        <div class="row mb-2">
                            <label class="col-sm-12"><strong>Inspeksi</strong></label>
                        </div>
                        <!-- Simetris -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong> Simetris</strong></label>
                            <div class="col-sm-9 d-flex align-items-center gap-3 flex-wrap">
                                <select class="form-select" name="simetris" style="max-width:200px" <?= $ro_disabled ?>>
                                    <option value="">Pilih</option>
                                    <option value="ya" <?= ($existing_data['simetris'] ?? '') === 'ya' ? 'selected' : '' ?>>Simetris</option>
                                    <option value="tidak" <?= ($existing_data['simetris'] ?? '') === 'tidak' ? 'selected' : '' ?>>Tidak</option>
                                </select>
                                <input type="text" class="form-control" style="max-width:425px" name="simetris_keterangan" value="<?= htmlspecialchars($existing_data['simetris_keterangan'] ?? '') ?>" <?= $ro ?>>
                            </div>
                        </div>
                        <!-- Bentuk Wajah -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Bentuk Wajah</strong></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="bentuk_wajah" value="<?= htmlspecialchars($existing_data['bentuk_wajah'] ?? '') ?>" <?= $ro ?>>
                            </div>
                        </div>

                        <!-- Palpasi -->
                        <div class="row mb-2">
                            <label class="col-sm-12"><strong>Palpasi</strong></label>
                        </div>

                        <!-- Nyeri Tekan -->
                        <!-- Nyeri Tekan / Nyeri Wajah -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Nyeri Tekan</strong></label>
                            <div class="col-sm-9 d-flex align-items-center gap-3 flex-wrap">
                                <select class="form-select" name="nyeri_wajah" style="max-width:200px" <?= $ro_disabled ?>>
                                    <option value="">Pilih</option>
                                    <option value="ya" <?= ($existing_data['nyeri_wajah'] ?? '') === 'ya' ? 'selected' : '' ?>>Ada</option>
                                    <option value="tidak" <?= ($existing_data['nyeri_wajah'] ?? '') === 'tidak' ? 'selected' : '' ?>>Tidak</option>
                                </select>
                                <input type="text" class="form-control" style="max-width:425px" name="nyeri_wajah_keterangan" value="<?= htmlspecialchars($existing_data['nyeri_wajah_keterangan'] ?? '') ?>" <?= $ro ?>>
                            </div>
                        </div>

                        <!-- Data Lain -->
                        <div class="row mb-3 align-items-start">
                            <label class="col-sm-2 col-form-label"><strong>Data Lain</strong></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="data_wajah" value="<?= htmlspecialchars($existing_data['data_wajah'] ?? '') ?>" <?= $ro ?>>
                            </div>
                        </div>
                        <!-- MATA -->
                        <div class="row mb-2">
                            <label class="col-sm-12 text-primary"><strong>Mata</strong></label>
                        </div>

                        <div class="row mb-2">
                            <label class="col-sm-12"><strong>Inspeksi</strong></label>
                        </div>
                        <!-- Palpebra -->
                        <!-- Palpebra -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Palpebra</strong></label>
                            <div class="col-sm-9 d-flex flex-column gap-3">

                                <!-- Edema -->
                                <div class="d-flex gap-3 flex-wrap align-items-center">
                                    <span><strong>Edema:</strong></span>
                                    <select class="form-select" name="edema_palpebra" style="max-width:200px" <?= $ro_disabled ?>>
                                        <option value="">Pilih</option>
                                        <option value="ya" <?= ($existing_data['edema_palpebra'] ?? '') === 'ya' ? 'selected' : '' ?>>Ya</option>
                                        <option value="tidak" <?= ($existing_data['edema_palpebra'] ?? '') === 'tidak' ? 'selected' : '' ?>>Tidak</option>
                                    </select>
                                </div>

                                <!-- Radang -->
                                <div class="d-flex gap-3 flex-wrap align-items-center">
                                    <span><strong>Radang:</strong></span>
                                    <select class="form-select" name="radang_palpebra" style="max-width:200px" <?= $ro_disabled ?>>
                                        <option value="">Pilih</option>
                                        <option value="ya" <?= ($existing_data['radang_palpebra'] ?? '') === 'ya' ? 'selected' : '' ?>>Ya</option>
                                        <option value="tidak" <?= ($existing_data['radang_palpebra'] ?? '') === 'tidak' ? 'selected' : '' ?>>Tidak</option>
                                    </select>
                                </div>

                            </div>
                        </div>

                        <!-- Sclera -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Sclera</strong></label>
                            <div class="col-sm-9 d-flex align-items-center gap-3 flex-wrap">
                                <select class="form-select" name="sclera" style="max-width:200px" <?= $ro_disabled ?>>
                                    <option value="">Pilih</option>
                                    <option value="icterus" <?= ($existing_data['sclera'] ?? '') === 'icterus' ? 'selected' : '' ?>>Icterus</option>
                                    <option value="tidak" <?= ($existing_data['sclera'] ?? '') === 'tidak' ? 'selected' : '' ?>>Tidak</option>
                                </select>
                            </div>
                        </div>

                        <!-- Conjungtiva - Radang -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Conjungtiva</strong></label>
                            <div class="col-sm-9 d-flex align-items-center gap-3 flex-wrap">
                                <select class="form-select" name="radang_conjungtiva" style="max-width:200px" <?= $ro_disabled ?>>
                                    <option value="">Pilih</option>
                                    <option value="radang" <?= ($existing_data['radang_conjungtiva'] ?? '') === 'radang' ? 'selected' : '' ?>>Radang</option>
                                    <option value="tidak" <?= ($existing_data['radang_conjungtiva'] ?? '') === 'tidak' ? 'selected' : '' ?>>Tidak</option>
                                </select>
                            </div>
                        </div>

                        <!-- Conjungtiva - Anemis -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong></strong></label>
                            <div class="col-sm-9 d-flex align-items-center gap-3 flex-wrap">
                                <select class="form-select" name="anemis" style="max-width:200px" <?= $ro_disabled ?>>
                                    <option value="">Pilih</option>
                                    <option value="anemis" <?= ($existing_data['anemis'] ?? '') === 'anemis' ? 'selected' : '' ?>>Anemis</option>
                                    <option value="tidak" <?= ($existing_data['anemis'] ?? '') === 'tidak' ? 'selected' : '' ?>>Tidak</option>
                                </select>
                            </div>
                        </div>

                        <!-- Pupil - Bentuk -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Pupil</strong></label>
                            <div class="col-sm-9 d-flex align-items-center gap-3 flex-wrap">
                                <select class="form-select" name="pupil_bentuk" style="max-width:200px" <?= $ro_disabled ?>>
                                    <option value="">Pilih</option>
                                    <option value="isokor" <?= ($existing_data['pupil_bentuk'] ?? '') === 'isokor' ? 'selected' : '' ?>>Isokor</option>
                                    <option value="anisokor" <?= ($existing_data['pupil_bentuk'] ?? '') === 'anisokor' ? 'selected' : '' ?>>Anisokor</option>
                                </select>
                            </div>
                        </div>

                        <!-- Pupil - Ukuran -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong></strong></label>
                            <div class="col-sm-9 d-flex align-items-center gap-3 flex-wrap">
                                <select class="form-select" name="pupil_ukuran" style="max-width:200px" <?= $ro_disabled ?>>
                                    <option value="">Pilih</option>
                                    <option value="myosis" <?= ($existing_data['pupil_ukuran'] ?? '') === 'myosis' ? 'selected' : '' ?>>Myosis</option>
                                    <option value="midriasis" <?= ($existing_data['pupil_ukuran'] ?? '') === 'midriasis' ? 'selected' : '' ?>>Midriasis</option>
                                </select>
                            </div>
                        </div>

                        <!-- Refleks Cahaya -->
                        <div class="row mb-3 align-items-start">
                            <label class="col-sm-5 col-form-label"><strong>Refleks pupil terhadap cahaya</strong></label>
                        </div>
                        <!-- Posisi Mata -->

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Posisi Mata</strong></label>
                            <div class="col-sm-9 d-flex align-items-center gap-3 flex-wrap">
                                <select class="form-select" name="posisi_mata" style="max-width:200px" <?= $ro_disabled ?>>
                                    <option value="">Pilih</option>
                                    <option value="ya" <?= ($existing_data['posisi_mata'] ?? '') === 'ya' ? 'selected' : '' ?>>Simetris</option>
                                    <option value="tidak" <?= ($existing_data['posisi_mata'] ?? '') === 'tidak' ? 'selected' : '' ?>>Tidak</option>
                                </select>

                                <input type="text" class="form-control" style="max-width:425px" name="posisi_mata_keterangan" value="<?= htmlspecialchars($existing_data['posisi_mata_keterangan'] ?? '') ?>" <?= $ro ?>>
                            </div>
                        </div>

                        <!-- Gerakan Bola Mata -->
                        <div class="row mb-3 align-items-start">
                            <label class="col-sm-2 col-form-label"><strong>Gerakan Bola Mata</strong></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="gerakan_mata" value="<?= htmlspecialchars($existing_data['gerakan_mata'] ?? '') ?>" <?= $ro ?>>
                            </div>
                        </div>

                        <!-- Penutupan Kelopak -->
                        <div class="row mb-3 align-items-start">
                            <label class="col-sm-2 col-form-label"><strong>Penutupan Kelopak Mata</strong></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="kelopak" value="<?= htmlspecialchars($existing_data['kelopak'] ?? '') ?>" <?= $ro ?>>
                            </div>
                        </div>

                        <!-- Bulu Mata -->
                        <div class="row mb-3 align-items-start">
                            <label class="col-sm-2 col-form-label"><strong>Keadaan Bulu Mata</strong></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="bulu_mata" value="<?= htmlspecialchars($existing_data['bulu_mata'] ?? '') ?>" <?= $ro ?>>
                            </div>
                        </div>

                        <!-- Penglihatan -->
                        <!-- Penglihatan -->
                        <div class="row mb-3 align-items-start">
                            <label class="col-sm-2 col-form-label"><strong>Penglihatan</strong></label>
                            <div class="col-sm-9 d-flex flex-column gap-2">

                                <!-- Kabur -->
                                <div class="d-flex gap-3 flex-wrap align-items-center">
                                    <span><strong></strong></span>
                                    <select class="form-select" name="kabur" style="max-width:200px" <?= $ro_disabled ?>>
                                        <option value="">Pilih</option>
                                        <option value="kabur" <?= ($existing_data['kabur'] ?? '') === 'kabur' ? 'selected' : '' ?>>Kabur</option>
                                        <option value="tidak" <?= ($existing_data['kabur'] ?? '') === 'tidak' ? 'selected' : '' ?>>Tidak</option>
                                    </select>
                                </div>

                                <!-- Diplopia -->
                                <div class="d-flex gap-3 flex-wrap align-items-center">
                                    <span><strong></strong></span>
                                    <select class="form-select" name="diplopia" style="max-width:200px" <?= $ro_disabled ?>>
                                        <option value="">Pilih</option>
                                        <option value="diplopia" <?= ($existing_data['diplopia'] ?? '') === 'diplopia' ? 'selected' : '' ?>>Diplopia</option>
                                        <option value="tidak" <?= ($existing_data['diplopia'] ?? '') === 'tidak' ? 'selected' : '' ?>>Tidak</option>
                                    </select>
                                </div>

                            </div>
                        </div>
                        <!-- Data Lain -->
                        <div class="row mb-3 align-items-start">
                            <label class="col-sm-2 col-form-label"><strong>Data Lain</strong></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="data_mata" value="<?= htmlspecialchars($existing_data['data_mata'] ?? '') ?>" <?= $ro ?>>
                            </div>
                        </div>
                        <!-- HIDUNG & SINUS -->
                        <div class="row mb-2">
                            <label class="col-sm-12 text-primary"><strong>Hidung & Sinus</strong></label>
                        </div>

                        <div class="row mb-2">
                            <label class="col-sm-12"><strong>Inspeksi</strong></label>
                        </div>

                        <!-- Bentuk Hidung -->
                        <div class="row mb-3 align-items-start">
                            <label class="col-sm-2 col-form-label"><strong>Bentuk Hidung</strong></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="bentuk_hidung" value="<?= htmlspecialchars($existing_data['bentuk_hidung'] ?? '') ?>" <?= $ro ?>>
                            </div>
                        </div>

                        <!-- Septum -->
                        <div class="row mb-3 align-items-start">
                            <label class="col-sm-2 col-form-label"><strong>Septum</strong></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="septum" value="<?= htmlspecialchars($existing_data['septum'] ?? '') ?>" <?= $ro ?>>
                            </div>
                        </div>

                        <!-- Secret / Cairan -->
                        <div class="row mb-3 align-items-start">
                            <label class="col-sm-2 col-form-label"><strong>Secret / Cairan</strong></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="secret" value="<?= htmlspecialchars($existing_data['secret'] ?? '') ?>" <?= $ro ?>>
                            </div>
                        </div>

                        <!-- Data Lain -->
                        <div class="row mb-3 align-items-start">
                            <label class="col-sm-2 col-form-label"><strong>Data Lain</strong></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="data_hidung" value="<?= htmlspecialchars($existing_data['data_hidung'] ?? '') ?>" <?= $ro ?>>
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
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Lubang Telinga</strong></label>
                            <div class="col-sm-9 d-flex gap-3 flex-wrap">
                                <select class="form-select" name="telinga" style="max-width:200px" <?= $ro_disabled ?>>
                                    <option value="">Pilih</option>
                                    <option value="bersih" <?= ($existing_data['telinga'] ?? '') === 'bersih' ? 'selected' : '' ?>>Bersih</option>
                                    <option value="serumen" <?= ($existing_data['telinga'] ?? '') === 'serumen' ? 'selected' : '' ?>>Serumen</option>
                                    <option value="nanah" <?= ($existing_data['telinga'] ?? '') === 'nanah' ? 'selected' : '' ?>>Nanah</option>
                                </select>
                            </div>
                        </div>

                        <!-- Palpasi -->
                        <div class="row mb-2">
                            <label class="col-sm-12"><strong>Palpasi</strong></label>
                        </div>

                        <!-- Nyeri Tekan -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Nyeri Tekan</strong></label>
                            <div class="col-sm-9 d-flex gap-3 flex-wrap">
                                <div class="form-check form-check-inline">
                                    <input type="radio" class="form-check-input" name="nyeri_telinga" value="ya" <?= $ro_disabled ?> <?= ($existing_data['nyeri_telinga'] ?? '') === 'ya' ? 'checked' : '' ?>>
                                    <label class="form-check-label">Ya</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" class="form-check-input" name="nyeri_telinga" value="tidak" <?= $ro_disabled ?> <?= ($existing_data['nyeri_telinga'] ?? '') === 'tidak' ? 'checked' : '' ?>>
                                    <label class="form-check-label">Tidak</label>
                                </div>
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

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Keadaan Gigi</strong></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="keadaan_gigi" value="<?= htmlspecialchars($existing_data['keadaan_gigi'] ?? '') ?>" <?= $ro ?>>
                            </div>
                        </div>

                        <!-- Karang / Karies -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Karang Gigi / Karies</strong></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="karies" value="<?= htmlspecialchars($existing_data['karies'] ?? '') ?>" <?= $ro ?>>
                            </div>
                        </div><!-- Gusi -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Gusi</strong></label>
                            <div class="col-sm-9 d-flex align-items-center gap-3 flex-wrap">

                                <!-- Dropdown -->
                                <select class="form-select" name="gusi" style="max-width:200px" <?= $ro_disabled ?>>
                                    <option value="">Pilih</option>
                                    <option value="merah" <?= ($existing_data['gusi'] ?? '') === 'merah' ? 'selected' : '' ?>>Merah</option>
                                    <option value="radang" <?= ($existing_data['gusi'] ?? '') === 'radang' ? 'selected' : '' ?>>Radang</option>
                                    <option value="tidak" <?= ($existing_data['gusi'] ?? '') === 'tidak' ? 'selected' : '' ?>>Tidak</option>
                                </select>

                                <!-- Keterangan -->
                                <input type="text" class="form-control" style="max-width:425px"
                                    name="gusi_keterangan"
                                    value="<?= htmlspecialchars($existing_data['gusi_keterangan'] ?? '') ?>"
                                    <?= $ro ?>>
                            </div>
                        </div><!-- Lidah -->
                        <div class="row mb-3 align-items-start">
                            <label class="col-sm-2 col-form-label"><strong>Lidah</strong></label>
                            <div class="col-sm-9 d-flex gap-3 flex-wrap">
                                <select class="form-select" name="lidah" style="max-width:200px" <?= $ro_disabled ?>>
                                    <option value="">Pilih</option>
                                    <option value="ya" <?= ($existing_data['lidah'] ?? '') === 'ya' ? 'selected' : '' ?>>Kotor</option>
                                    <option value="tidak" <?= ($existing_data['lidah'] ?? '') === 'tidak' ? 'selected' : '' ?>>Tidak</option>
                                </select>
                            </div>
                        </div>

                        <!-- Bibir - Warna -->
                        <div class="row mb-3 align-items-start">
                            <label class="col-sm-2 col-form-label"><strong>Bibir (Warna)</strong></label>
                            <div class="col-sm-9 d-flex gap-3 flex-wrap align-items-center">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="bibir_warna[]" value="cianosis" <?= $ro_disabled ?> <?= ($existing_data['bibir_warna'] ?? '') === 'cianosis' ? 'checked' : '' ?>>
                                    <label class="form-check-label">Cianosis</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="bibir_warna[]" value="pucat" <?= $ro_disabled ?> <?= ($existing_data['bibir_warna'] ?? '') === 'pucat' ? 'checked' : '' ?>>
                                    <label class="form-check-label">Pucat</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="bibir_warna[]" value="tidak" <?= $ro_disabled ?> <?= ($existing_data['bibir_warna'] ?? '') === 'tidak' ? 'checked' : '' ?>>
                                    <label class="form-check-label">Tidak</label>
                                </div>
                                <input type="text" class="form-control" style="max-width:200px" name="bibir_warna_keterangan" value="<?= htmlspecialchars($existing_data['bibir_warna_keterangan'] ?? '') ?>" <?= $ro ?>>
                            </div>
                        </div>

                        <!-- Bibir - Kondisi -->
                        <div class="row mb-3 align-items-start">
                            <label class="col-sm-2 col-form-label"><strong>Bibir (Kondisi)</strong></label>
                            <div class="col-sm-9 d-flex gap-3 flex-wrap align-items-center">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="bibir_kondisi[]" value="basah" <?= $ro_disabled ?> <?= ($existing_data['bibir_kondisi'] ?? '') === 'basah' ? 'checked' : '' ?>>
                                    <label class="form-check-label">Basah</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="bibir_kondisi[]" value="kering" <?= $ro_disabled ?> <?= ($existing_data['bibir_kondisi'] ?? '') === 'kering' ? 'checked' : '' ?>>
                                    <label class="form-check-label">Kering</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="bibir_kondisi[]" value="pecah" <?= $ro_disabled ?> <?= ($existing_data['bibir_kondisi'] ?? '') === 'pecah' ? 'checked' : '' ?>>
                                    <label class="form-check-label">Pecah</label>
                                </div>
                                <input type="text" class="form-control" style="max-width:200px" name="bibir_kondisi_keterangan" value="<?= htmlspecialchars($existing_data['bibir_kondisi_keterangan'] ?? '') ?>" <?= $ro ?>>
                            </div>
                        </div>
                        <!-- Bau Mulut -->
                        <div class="row mb-3 align-items-center">
                            <label class="col-sm-2 col-form-label"><strong>Mulut Berbau</strong></label>
                            <div class="col-sm-9 d-flex gap-3 align-items-center">
                                <!-- Dropdown -->
                                <select class="form-select" name="bau_mulut" style="max-width:200px" <?= $ro_disabled ?>>
                                    <option value="">Pilih</option>
                                    <option value="ya" <?= ($existing_data['bau_mulut'] ?? '') === 'ya' ? 'selected' : '' ?>>Ya</option>
                                    <option value="tidak" <?= ($existing_data['bau_mulut'] ?? '') === 'tidak' ? 'selected' : '' ?>>Tidak</option>
                                </select>

                                <!-- Keterangan -->
                                <input type="text" class="form-control" style="max-width:425px"
                                    name="bau_mulut_keterangan"
                                    value="<?= htmlspecialchars($existing_data['bau_mulut_keterangan'] ?? '') ?>"
                                    <?= $ro ?>>
                            </div>
                        </div>
                        <!-- Kemampuan Bicara -->
                        <div class="row mb-3 align-items-start">
                            <label class="col-sm-2 col-form-label"><strong>Kemampuan Bicara</strong></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="bicara" value="<?= htmlspecialchars($existing_data['bicara'] ?? '') ?>" <?= $ro ?>>
                            </div>
                        </div>

                        <!-- Data Lain -->
                        <div class="row mb-3 align-items-start">
                            <label class="col-sm-2 col-form-label"><strong>Data Lain</strong></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="data_mulut" value="<?= htmlspecialchars($existing_data['data_mulut'] ?? '') ?>" <?= $ro ?>>
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
                                <input type="text" class="form-control" name="mukosa" value="<?= htmlspecialchars($existing_data['mukosa'] ?? '') ?>" <?= $ro ?>>
                            </div>
                        </div>

                        <!-- Nyeri Tekan -->
                        <div class="row mb-3 align-items-start">
                            <label class="col-sm-2 col-form-label"><strong>Nyeri Tekan</strong></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="nyeri_tenggorokan" value="<?= htmlspecialchars($existing_data['nyeri_tenggorokan'] ?? '') ?>" <?= $ro ?>>
                            </div>
                        </div>

                        <!-- Nyeri Menelan -->
                        <div class="row mb-3 align-items-start">
                            <label class="col-sm-2 col-form-label"><strong>Nyeri Menelan</strong></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="menelan" value="<?= htmlspecialchars($existing_data['menelan'] ?? '') ?>" <?= $ro ?>>
                            </div>
                        </div>

                        <!-- LEHER --><!-- Leher -->
                        <div class="row mb-2">
                            <label class="col-sm-12 text-primary"><strong>Leher</strong></label>
                        </div>

                        <div class="row mb-2">
                            <label class="col-sm-12"><strong>Palpasi</strong></label>
                        </div>
                        <!-- Kelenjar Limfe -->
                        <div class="row mb-3 align-items-start">
                            <label class="col-sm-2 col-form-label"><strong>Kelenjar Limfe</strong></label>
                            <div class="col-sm-9 d-flex gap-3 flex-wrap">
                                <select class="form-select" name="limfe" style="max-width:200px" <?= $ro_disabled ?>>
                                    <option value="">Pilih</option>
                                    <option value="membesar" <?= ($existing_data['limfe'] ?? '') === 'membesar' ? 'selected' : '' ?>>Membesar</option>
                                    <option value="tidak" <?= ($existing_data['limfe'] ?? '') === 'tidak' ? 'selected' : '' ?>>Tidak</option>
                                </select>
                            </div>
                        </div>

                        <!-- Data Lain -->
                        <div class="row mb-3 align-items-start">
                            <label class="col-sm-2 col-form-label"><strong>Data Lain</strong></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="data_leher" value="<?= htmlspecialchars($existing_data['data_leher'] ?? '') ?>" <?= $ro ?>>
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
                                <input type="text" class="form-control" name="bentuk_dada" value="<?= htmlspecialchars($existing_data['bentuk_dada'] ?? '') ?>" <?= $ro ?>>
                            </div>
                        </div>

                        <!-- Irama Pernapasan -->
                        <div class="row mb-3 align-items-start">
                            <label class="col-sm-2 col-form-label"><strong>Irama Pernapasan</strong></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="irama_nafas" value="<?= htmlspecialchars($existing_data['irama_nafas'] ?? '') ?>" <?= $ro ?>>
                            </div>
                        </div>

                        <!-- Pengembangan -->
                        <div class="row mb-3 align-items-start">
                            <label class="col-sm-2 col-form-label"><strong>Pengembangan di Waktu Bernapas</strong></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="pengembangan" value="<?= htmlspecialchars($existing_data['pengembangan'] ?? '') ?>" <?= $ro ?>>
                            </div>
                        </div>

                        <!-- Tipe Pernapasan -->
                        <div class="row mb-3 align-items-start">
                            <label class="col-sm-2 col-form-label"><strong>Tipe Pernapasan</strong></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="tipe_nafas" value="<?= htmlspecialchars($existing_data['tipe_nafas'] ?? '') ?>" <?= $ro ?>>
                            </div>
                        </div>
                        <!-- AUSKULTASI -->
                        <div class="row mb-2">
                            <label class="col-sm-12"><strong>Auskultasi</strong></label>
                        </div>
                        <!-- Suara Nafas -->
                        <div class="row mb-3 align-items-start">
                            <label class="col-sm-2 col-form-label"><strong>Suara Nafas</strong></label>
                            <div class="col-sm-9 d-flex gap-3 flex-wrap">
                                <select class="form-select" name="suara_auskultas" style="max-width:200px" <?= $ro_disabled ?>>
                                    <option value="">Pilih</option>
                                    <option value="vesikuler" <?= ($existing_data['suara_auskultas'] ?? '') === 'vesikuler' ? 'selected' : '' ?>>Vesikuler</option>
                                    <option value="bronchial" <?= ($existing_data['suara_auskultas'] ?? '') === 'bronchial' ? 'selected' : '' ?>>Bronchial</option>
                                    <option value="bronchovesikuler" <?= ($existing_data['suara_auskultas'] ?? '') === 'bronchovesikuler' ? 'selected' : '' ?>>Bronchovesikuler</option>
                                </select>
                            </div>
                        </div>

                        <!-- Suara Tambahan -->
                        <div class="row mb-3 align-items-start">
                            <label class="col-sm-2 col-form-label"><strong>Suara Tambahan</strong></label>
                            <div class="col-sm-9 d-flex gap-3 flex-wrap">
                                <select class="form-select" name="suara_tambahan" style="max-width:200px" <?= $ro_disabled ?>>
                                    <option value="">Pilih</option>
                                    <option value="ronchi" <?= ($existing_data['suara_tambahan'] ?? '') === 'ronchi' ? 'selected' : '' ?>>Ronchi</option>
                                    <option value="wheezing" <?= ($existing_data['suara_tambahan'] ?? '') === 'wheezing' ? 'selected' : '' ?>>Wheezing</option>
                                    <option value="rales" <?= ($existing_data['suara_tambahan'] ?? '') === 'rales' ? 'selected' : '' ?>>Rales</option>
                                </select>
                            </div>
                        </div>

                        <!-- Perkusi -->
                        <div class="row mb-3 align-items-start">
                            <label class="col-sm-2 col-form-label"><strong>Perkusi</strong></label>
                            <div class="col-sm-9 d-flex gap-3 flex-wrap">
                                <select class="form-select" name="perkusi" style="max-width:200px" <?= $ro_disabled ?>>
                                    <option value="">Pilih</option>
                                    <option value="redup" <?= ($existing_data['perkusi'] ?? '') === 'redup' ? 'selected' : '' ?>>Redup</option>
                                    <option value="pekak" <?= ($existing_data['perkusi'] ?? '') === 'pekak' ? 'selected' : '' ?>>Pekak</option>
                                    <option value="hypersonor" <?= ($existing_data['perkusi'] ?? '') === 'hypersonor' ? 'selected' : '' ?>>Hypersonor</option>
                                    <option value="tympani" <?= ($existing_data['perkusi'] ?? '') === 'tympani' ? 'selected' : '' ?>>Tympani</option>
                                </select>
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
                                <input type="text" class="form-control" name="ictus_cordis" value="<?= htmlspecialchars($existing_data['ictus_cordis'] ?? '') ?>" <?= $ro ?>>
                            </div>
                        </div>

                        <!-- Pembesaran -->
                        <div class="row mb-3 align-items-start">
                            <label class="col-sm-2 col-form-label"><strong>Pembesaran Jantung</strong></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="pembesaran_jantung" value="<?= htmlspecialchars($existing_data['pembesaran_jantung'] ?? '') ?>" <?= $ro ?>>
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
                                <input type="text" class="form-control" name="bj1" value="<?= htmlspecialchars($existing_data['bj1'] ?? '') ?>" <?= $ro ?>>
                            </div>
                        </div>

                        <!-- BJ II -->
                        <div class="row mb-3 align-items-start">
                            <label class="col-sm-2 col-form-label"><strong>BJ II</strong></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="bj2" value="<?= htmlspecialchars($existing_data['bj2'] ?? '') ?>" <?= $ro ?>>
                            </div>
                        </div>

                        <!-- BJ III -->
                        <div class="row mb-3 align-items-start">
                            <label class="col-sm-2 col-form-label"><strong>BJ III</strong></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="bj3" value="<?= htmlspecialchars($existing_data['bj3'] ?? '') ?>" <?= $ro ?>>
                            </div>
                        </div>

                        <!-- Bunyi Tambahan -->
                        <div class="row mb-3 align-items-start">
                            <label class="col-sm-2 col-form-label"><strong>Bunyi Tambahan</strong></label>
                            <div class="col-sm-9">
                                <textarea class="form-control" name="bunyi_tambahan" <?= $ro ?>><?= htmlspecialchars($existing_data['bunyi_tambahan'] ?? '') ?></textarea>
                            </div>
                        </div>

                        <!-- Data Lain -->
                        <div class="row mb-3 align-items-start">
                            <label class="col-sm-2 col-form-label"><strong>Data Lain</strong></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="data_lain_jantung" value="<?= htmlspecialchars($existing_data['data_lain_jantung'] ?? '') ?>" <?= $ro ?>>
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
                                <input type="text" class="form-control" name="membuncit" value="<?= htmlspecialchars($existing_data['membuncit'] ?? '') ?>" <?= $ro ?>>
                            </div>
                        </div>

                        <!-- Luka -->
                        <!-- Luka Abdomen -->
                        <div class="row mb-3 align-items-start">
                            <label class="col-sm-2 col-form-label"><strong>Ada Luka</strong></label>
                            <div class="col-sm-9 d-flex gap-3 flex-wrap align-items-center">
                                <!-- Dropdown -->
                                <select class="form-select" name="luka_abdomen" style="max-width:200px" <?= $ro_disabled ?>>
                                    <option value="">Pilih</option>
                                    <option value="ada" <?= ($existing_data['luka_abdomen'] ?? '') === 'ada' ? 'selected' : '' ?>>Ada</option>
                                    <option value="tidak" <?= ($existing_data['luka_abdomen'] ?? '') === 'tidak' ? 'selected' : '' ?>>Tidak</option>
                                </select>

                                <!-- Keterangan -->
                                <input type="text" class="form-control" style="max-width:425px"
                                    name="luka_abdomen_lain"
                                    value="<?= htmlspecialchars($existing_data['luka_abdomen_lain'] ?? '') ?>"
                                    <?= $ro ?>>
                            </div>
                        </div>

                        <!-- AUSKULTASI -->
                        <div class="row mb-2">
                            <label class="col-sm-12"><strong>Auskultasi</strong></label>
                        </div>

                        <div class="row mb-3 align-items-start">
                            <label class="col-sm-2 col-form-label"><strong>Peristaltik</strong></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="peristaltik" value="<?= htmlspecialchars($existing_data['peristaltik'] ?? '') ?>" <?= $ro ?>>
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
                                <input type="text" class="form-control" name="hepar" value="<?= htmlspecialchars($existing_data['hepar'] ?? '') ?>" <?= $ro ?>>
                            </div>
                        </div>

                        <!-- Lien -->
                        <div class="row mb-3 align-items-start">
                            <label class="col-sm-2 col-form-label"><strong>Lien</strong></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="lien" value="<?= htmlspecialchars($existing_data['lien'] ?? '') ?>" <?= $ro ?>>
                            </div>
                        </div>

                        <!-- Nyeri Tekan -->
                        <div class="row mb-3 align-items-start">
                            <label class="col-sm-2 col-form-label"><strong>Nyeri Tekan</strong></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="nyeri" value="<?= htmlspecialchars($existing_data['nyeri'] ?? '') ?>" <?= $ro ?>>
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
                                <input type="text" class="form-control" name="tympani" value="<?= htmlspecialchars($existing_data['tympani'] ?? '') ?>" <?= $ro ?>>
                            </div>
                        </div>

                        <!-- Redup -->
                        <div class="row mb-3 align-items-start">
                            <label class="col-sm-2 col-form-label"><strong>Redup</strong></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="redup" value="<?= htmlspecialchars($existing_data['redup'] ?? '') ?>" <?= $ro ?>>
                            </div>
                        </div>

                        <!-- Data Lain -->
                        <div class="row mb-3 align-items-start">
                            <label class="col-sm-2 col-form-label"><strong>Data Lain</strong></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="data_abdomen" value="<?= htmlspecialchars($existing_data['data_abdomen'] ?? '') ?>" <?= $ro ?>>
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
                                <input type="text" class="form-control" name="fistula_pria" value="<?= htmlspecialchars($existing_data['fistula_pria'] ?? '') ?>" <?= $ro ?>>
                            </div>
                        </div>

                        <!-- Uretra -->
                        <div class="row mb-3 align-items-start">
                            <label class="col-sm-2 col-form-label"><strong>Lubang Uretra</strong></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="uretra" value="<?= htmlspecialchars($existing_data['uretra'] ?? '') ?>" <?= $ro ?>>
                            </div>
                        </div>

                        <!-- Skrotum -->
                        <div class="row mb-3 align-items-start">
                            <label class="col-sm-2 col-form-label"><strong>Skrotum</strong></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="skrotum" value="<?= htmlspecialchars($existing_data['skrotum'] ?? '') ?>" <?= $ro ?>>
                            </div>
                        </div>

                        <!-- Genital Ganda -->
                        <div class="row mb-3 align-items-start">
                            <label class="col-sm-2 col-form-label"><strong>Genitalia Ganda</strong></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="genital_ganda" value="<?= htmlspecialchars($existing_data['genital_ganda'] ?? '') ?>" <?= $ro ?>>
                            </div>
                        </div>

                        <!-- Hidrokel -->
                        <div class="row mb-3 align-items-start">
                            <label class="col-sm-2 col-form-label"><strong>Hidrokel</strong></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="hidrokel_pria" value="<?= htmlspecialchars($existing_data['hidrokel_pria'] ?? '') ?>" <?= $ro ?>>
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
                                <input type="text" class="form-control" name="labia" value="<?= htmlspecialchars($existing_data['labia'] ?? '') ?>" <?= $ro ?>>
                            </div>
                        </div>

                        <!-- Fistula Wanita -->
                        <div class="row mb-3 align-items-start">
                            <label class="col-sm-2 col-form-label"><strong>Fistula Urogenital (Perempuan)</strong></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="fistula_wanita" value="<?= htmlspecialchars($existing_data['fistula_wanita'] ?? '') ?>" <?= $ro ?>>
                            </div>
                        </div>

                        <!-- Hidrokel Wanita -->
                        <div class="row mb-3 align-items-start">
                            <label class="col-sm-2 col-form-label"><strong>Hidrokel</strong></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="hidrokel_wanita" value="<?= htmlspecialchars($existing_data['hidrokel_wanita'] ?? '') ?>" <?= $ro ?>>
                            </div>
                        </div>
                        <!-- ANUS -->
                        <div class="row mb-2">
                            <label class="col-sm-12 text-primary"><strong>Anus</strong></label>
                        </div>
                        <!-- Lubang Anal Paten -->
                        <div class="row mb-3 align-items-start">
                            <label class="col-sm-2 col-form-label"><strong>Lubang Anal Paten</strong></label>
                            <div class="col-sm-9 d-flex gap-3 flex-wrap">
                                <select class="form-select" name="anus_paten" style="max-width:200px" <?= $ro_disabled ?>>
                                    <option value="">Pilih</option>
                                    <option value="ya" <?= ($existing_data['anus_paten'] ?? '') === 'ya' ? 'selected' : '' ?>>Ya</option>
                                    <option value="tidak" <?= ($existing_data['anus_paten'] ?? '') === 'tidak' ? 'selected' : '' ?>>Tidak</option>
                                </select>
                            </div>
                        </div>

                        <!-- Lintasan Mekonium -->
                        <div class="row mb-3 align-items-start">
                            <label class="col-sm-2 col-form-label"><strong>Lintasan Mekonium (36 jam)</strong></label>
                            <div class="col-sm-9 d-flex gap-3 flex-wrap">
                                <select class="form-select" name="mekonium" style="max-width:200px" <?= $ro_disabled ?>>
                                    <option value="">Pilih</option>
                                    <option value="ada" <?= ($existing_data['mekonium'] ?? '') === 'ada' ? 'selected' : '' ?>>Ada</option>
                                    <option value="tidak" <?= ($existing_data['mekonium'] ?? '') === 'tidak' ? 'selected' : '' ?>>Tidak</option>
                                </select>
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
                                <input type="text" class="form-control" name="gerak_atas" value="<?= htmlspecialchars($existing_data['gerak_atas'] ?? '') ?>" <?= $ro ?>>
                            </div>
                        </div>

                        <!-- Abnormal -->
                        <div class="row mb-3 align-items-start">
                            <label class="col-sm-2 col-form-label"><strong>Pergerakan Abnormal</strong></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="abnormal_atas" value="<?= htmlspecialchars($existing_data['abnormal_atas'] ?? '') ?>" <?= $ro ?>>
                            </div>
                        </div>

                        <!-- Kekuatan -->
                        <div class="row mb-3 align-items-start">
                            <label class="col-sm-2 col-form-label"><strong>Kekuatan Otot Kanan/Kiri</strong></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="kekuatan_atas" value="<?= htmlspecialchars($existing_data['kekuatan_atas'] ?? '') ?>" <?= $ro ?>>
                            </div>
                        </div>

                        <!-- Koordinasi -->
                        <div class="row mb-3 align-items-start">
                            <label class="col-sm-2 col-form-label"><strong>Koordinasi Gerak</strong></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="koordinasi_atas" value="<?= htmlspecialchars($existing_data['koordinasi_atas'] ?? '') ?>" <?= $ro ?>>
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
                                <input type="text" class="form-control" name="nyeri_atas" value="<?= htmlspecialchars($existing_data['nyeri_atas'] ?? '') ?>" <?= $ro ?>>
                            </div>
                        </div>

                        <!-- Suhu -->
                        <div class="row mb-3 align-items-start">
                            <label class="col-sm-2 col-form-label"><strong>Rangsang Suhu</strong></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="suhu_atas" value="<?= htmlspecialchars($existing_data['suhu_atas'] ?? '') ?>" <?= $ro ?>>
                            </div>
                        </div>

                        <!-- Raba -->
                        <div class="row mb-3 align-items-start">
                            <label class="col-sm-2 col-form-label"><strong>Rasa Raba</strong></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="raba_atas" value="<?= htmlspecialchars($existing_data['raba_atas'] ?? '') ?>" <?= $ro ?>>
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
                                <input type="text" class="form-control" name="gaya_jalan" value="<?= htmlspecialchars($existing_data['gaya_jalan'] ?? '') ?>" <?= $ro ?>>
                            </div>
                        </div>

                        <!-- Kekuatan -->
                        <div class="row mb-3 align-items-start">
                            <label class="col-sm-2 col-form-label"><strong>Kekuatan Kanan/Kiri</strong></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="kekuatan_bawah" value="<?= htmlspecialchars($existing_data['kekuatan_bawah'] ?? '') ?>" <?= $ro ?>>
                            </div>
                        </div>

                        <!-- Tonus -->
                        <div class="row mb-3 align-items-start">
                            <label class="col-sm-2 col-form-label"><strong>Tonus Otot Kanan/Kiri</strong></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="tonus_bawah" value="<?= htmlspecialchars($existing_data['tonus_bawah'] ?? '') ?>" <?= $ro ?>>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <label class="col-sm-12"><strong>Sensori</strong></label>
                        </div>

                        <!-- Nyeri -->
                        <div class="row mb-3 align-items-start">
                            <label class="col-sm-2 col-form-label"><strong>Nyeri</strong></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="nyeri_bawah" value="<?= htmlspecialchars($existing_data['nyeri_bawah'] ?? '') ?>" <?= $ro ?>>
                            </div>
                        </div>

                        <!-- Suhu -->
                        <div class="row mb-3 align-items-start">
                            <label class="col-sm-2 col-form-label"><strong>Rangsang Suhu</strong></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="suhu_bawah" value="<?= htmlspecialchars($existing_data['suhu_bawah'] ?? '') ?>" <?= $ro ?>>
                            </div>
                        </div>

                        <!-- Raba -->
                        <div class="row mb-3 align-items-start">
                            <label class="col-sm-2 col-form-label"><strong>Rasa Raba</strong></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="raba_bawah" value="<?= htmlspecialchars($existing_data['raba_bawah'] ?? '') ?>" <?= $ro ?>>
                            </div>
                        </div>
                        <!-- REFLEKS -->
                        <div class="row mb-2">
                            <label class="col-sm-12 text-primary"><strong>Tanda Perangsangan Selaput Otak & Refleks Bayi</strong></label>
                        </div>

                        <!-- Kaku Kuduk -->
                        <div class="row mb-3 align-items-start">
                            <label class="col-sm-2 col-form-label"><strong>Kaku Kuduk</strong></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="kaku_kuduk" value="<?= htmlspecialchars($existing_data['kaku_kuduk'] ?? '') ?>" <?= $ro ?>>
                            </div>
                        </div>

                        <!-- Kernig -->
                        <div class="row mb-3 align-items-start">
                            <label class="col-sm-2 col-form-label"><strong>Kernig Sign</strong></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="kernig" value="<?= htmlspecialchars($existing_data['kernig'] ?? '') ?>" <?= $ro ?>>
                            </div>
                        </div>

                        <!-- Brudzinski -->
                        <div class="row mb-3 align-items-start">
                            <label class="col-sm-2 col-form-label"><strong>Refleks Brudzinski</strong></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="brudzinski" value="<?= htmlspecialchars($existing_data['brudzinski'] ?? '') ?>" <?= $ro ?>>
                            </div>
                        </div>

                        <!-- Refleks pada bayi -->
                        <div class="row mb-3 align-items-start">
                            <label class="col-sm-2 col-form-label"><strong>Refleks pada Bayi</strong></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="refleks_bayi" value="<?= htmlspecialchars($existing_data['refleks_bayi'] ?? '') ?>" <?= $ro ?>>
                            </div>
                        </div>

                        <!-- Iddol -->
                        <div class="row mb-3 align-items-start">
                            <label class="col-sm-2 col-form-label"><strong>Refleks Iddol</strong></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="iddol" value="<?= htmlspecialchars($existing_data['iddol'] ?? '') ?>" <?= $ro ?>>
                            </div>
                        </div>

                        <!-- Startel -->
                        <div class="row mb-3 align-items-start">
                            <label class="col-sm-2 col-form-label"><strong>Refleks Startel</strong></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="startel" value="<?= htmlspecialchars($existing_data['startel'] ?? '') ?>" <?= $ro ?>>
                            </div>
                        </div>

                        <!-- Sucking -->
                        <div class="row mb-3 align-items-start">
                            <label class="col-sm-2 col-form-label"><strong>Refleks Sucking (Isap)</strong></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="sucking" value="<?= htmlspecialchars($existing_data['sucking'] ?? '') ?>" <?= $ro ?>>
                            </div>
                        </div>

                        <!-- Rooting -->
                        <div class="row mb-3 align-items-start">
                            <label class="col-sm-2 col-form-label"><strong>Refleks Rooting (Menoleh)</strong></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="rooting" value="<?= htmlspecialchars($existing_data['rooting'] ?? '') ?>" <?= $ro ?>>
                            </div>
                        </div>

                        <!-- Gawn -->
                        <div class="row mb-3 align-items-start">
                            <label class="col-sm-2 col-form-label"><strong>Refleks Gawn</strong></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="gawn" value="<?= htmlspecialchars($existing_data['gawn'] ?? '') ?>" <?= $ro ?>>
                            </div>
                        </div>

                        <!-- Grabella -->
                        <div class="row mb-3 align-items-start">
                            <label class="col-sm-2 col-form-label"><strong>Refleks Grabella</strong></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="grabella" value="<?= htmlspecialchars($existing_data['grabella'] ?? '') ?>" <?= $ro ?>>
                            </div>
                        </div>

                        <!-- Ekruction -->
                        <div class="row mb-3 align-items-start">
                            <label class="col-sm-2 col-form-label"><strong>Refleks Ekruction</strong></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="ekruction" value="<?= htmlspecialchars($existing_data['ekruction'] ?? '') ?>" <?= $ro ?>>
                            </div>
                        </div>

                        <!-- Moro -->
                        <div class="row mb-3 align-items-start">
                            <label class="col-sm-2 col-form-label"><strong>Refleks Moro</strong></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="moro" value="<?= htmlspecialchars($existing_data['moro'] ?? '') ?>" <?= $ro ?>>
                            </div>
                        </div>

                        <!-- Grasping -->
                        <div class="row mb-3 align-items-start">
                            <label class="col-sm-2 col-form-label"><strong>Refleks Grasping</strong></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="grasping" value="<?= htmlspecialchars($existing_data['grasping'] ?? '') ?>" <?= $ro ?>>
                            </div>
                        </div>

                        <!-- Peres -->
                        <div class="row mb-3 align-items-start">
                            <label class="col-sm-2 col-form-label"><strong>Refleks Peres</strong></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="peres" value="<?= htmlspecialchars($existing_data['peres'] ?? '') ?>" <?= $ro ?>>
                            </div>
                        </div>

                        <!-- Kremaster -->
                        <div class="row mb-3 align-items-start">
                            <label class="col-sm-2 col-form-label"><strong>Refleks Kremaster</strong></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="kremaster" value="<?= htmlspecialchars($existing_data['kremaster'] ?? '') ?>" <?= $ro ?>>
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
                                <input type="text" class="form-control" name="turgor" value="<?= htmlspecialchars($existing_data['turgor'] ?? '') ?>" <?= $ro ?>>
                            </div>
                        </div>

                        <!-- Finger Print -->
                        <div class="row mb-3 align-items-start">
                            <label class="col-sm-2 col-form-label"><strong>Finger Print di Dahi</strong></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="finger_print" value="<?= htmlspecialchars($existing_data['finger_print'] ?? '') ?>" <?= $ro ?>>
                            </div>
                        </div>

                        <!-- Lesi -->
                        <div class="row mb-3 align-items-start">
                            <label class="col-sm-2 col-form-label"><strong>Adanya Lesi</strong></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="lesi" value="<?= htmlspecialchars($existing_data['lesi'] ?? '') ?>" <?= $ro ?>>
                            </div>
                        </div>

                        <!-- Kebersihan -->
                        <div class="row mb-3 align-items-start">
                            <label class="col-sm-2 col-form-label"><strong>Kebersihan Kulit</strong></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="kebersihan" value="<?= htmlspecialchars($existing_data['kebersihan'] ?? '') ?>" <?= $ro ?>>
                            </div>
                        </div>

                        <!-- Kelembaban -->
                        <div class="row mb-3 align-items-start">
                            <label class="col-sm-2 col-form-label"><strong>Kelembaban Kulit</strong></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="kelembaban" value="<?= htmlspecialchars($existing_data['kelembaban'] ?? '') ?>" <?= $ro ?>>
                            </div>
                        </div>

                        <!-- Warna -->
                        <div class="row mb-3 align-items-start">
                            <label class="col-sm-2 col-form-label"><strong>Warna Kulit</strong></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="warna_kulit" value="<?= htmlspecialchars($existing_data['warna_kulit'] ?? '') ?>" <?= $ro ?>>
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
                                <input type="text" class="form-control" name="motorik_kasar_input" value="<?= htmlspecialchars($existing_data['motorik_kasar_input'] ?? '') ?>" <?= $ro ?>>
                            </div>
                        </div>

                        <!-- Motorik Halus -->
                        <div class="row mb-3 align-items-start">
                            <label class="col-sm-2 col-form-label"><strong>Motorik Halus</strong></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="motorik_halus_input" value="<?= htmlspecialchars($existing_data['motorik_halus_input'] ?? '') ?>" <?= $ro ?>>
                            </div>
                        </div>

                        <!-- Bahasa -->
                        <div class="row mb-3 align-items-start">
                            <label class="col-sm-2 col-form-label"><strong>Bahasa</strong></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="bahasa_input" value="<?= htmlspecialchars($existing_data['bahasa_input'] ?? '') ?>" <?= $ro ?>>
                            </div>
                        </div>

                        <!-- Personal Social -->
                        <div class="row mb-3 align-items-start">
                            <label class="col-sm-2 col-form-label"><strong>Personal Social</strong></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="personal_social_input" value="<?= htmlspecialchars($existing_data['personal_social_input'] ?? '') ?>" <?= $ro ?>>
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
                                <textarea class="form-control" rows="3" name="diagnostik" <?= $ro ?>><?= htmlspecialchars($existing_data['diagnostik'] ?? '') ?></textarea>
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
                                <textarea class="form-control" rows="3" name="laboratorium" <?= $ro ?>><?= htmlspecialchars($existing_data['laboratorium'] ?? '') ?></textarea>
                            </div>
                        </div>

                        <!-- PENUNJANG LINK -->
                        <div class="row mb-3 align-items-start">
                            <label class="col-sm-2 col-form-label"><strong>Link drive Laboratorium</strong></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="penunjang_link" value="<?= htmlspecialchars($existing_data['penunjang_link'] ?? '') ?>" <?= $ro ?>>
                            </div>
                        </div>

                        <!-- PENUNJANG -->
                        <div class="row mb-3 align-items-start">
                            <label class="col-sm-2 col-form-label"><strong>Pemeriksaan Penunjang</strong></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="penunjang" placeholder="Foto Rontgen, CT Scan, MRI, USG, EEG, ECG" value="<?= htmlspecialchars($existing_data['penunjang'] ?? '') ?>" <?= $ro ?>>
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
                                <textarea class="form-control" rows="4" name="terapi" <?= $ro ?>><?= htmlspecialchars($existing_data['terapi'] ?? '') ?></textarea>
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

            <?php include dirname(__DIR__, 2) . '/partials/footer_form.php'; ?>


        </section>
</main>