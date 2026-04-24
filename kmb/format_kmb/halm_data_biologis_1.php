<?php
require_once "koneksi.php";
require_once "utils.php";

$form_id       = 15;
$level         = $_SESSION['level'];
$user_id       = $_SESSION['id_user'];
$section_name  = 'data_biologis_1';
$section_label = 'Data Biologis 1';

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

// Decode checkbox fields
$checkbox_fields = ['bau_mulut'];
foreach ($checkbox_fields as $cf) {
    $existing_data[$cf] = isset($existing_data[$cf])
        ? (json_decode($existing_data[$cf], true) ?? [])
        : [];
}

$is_dosen    = $level === 'Dosen';
$is_readonly = $is_dosen || isLocked($submission);
$ro          = $is_readonly ? 'readonly' : '';
$ro_disabled = $is_readonly ? 'disabled' : '';

// =============================================
// HANDLE POST - MAHASISWA
// =============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $level === 'Mahasiswa') {
    if (isLocked($submission)) {
        redirectWithMessage($_SERVER['REQUEST_URI'], 'error', 'Data tidak dapat diubah karena sedang dalam proses review.');
    }
    $data = [];
    $text_fields = ['bentuk_kepala', 'nyeridada', 'benjolan_kepala', 'penyebaran_merata', 'warna_rambut', 'rambut_dicabut', 'kelainan_rambut', 'ekspresi_wajah', 'simetris_wajah', 'udema_wajah', 'kelainan_wajah', 'penglihatan', 'visus_kanan', 'visus_kiri', 'lapang_pandang', 'keadaan_mata', 'konjungtiva', 'lesi_mata', 'sclera', 'pupil', 'bola_mata', 'kelainan_mata', 'pendengaran_kiri', 'pendengaran_kanan', 'nyeri_Kiri', 'nyeri_kanan', 'serumen', 'kelainan_telinga', 'bau', 'sekresi', 'warna_hidung', 'mukosa_hidung', 'pembengkakan', 'cuping_hidung', 'kelainan_hidung', 'bibir', 'simetris', 'kelembaban', 'caries', 'jumlah_gigi', 'warna_gigi', 'gigi_palsu_jumlah', 'letak', 'lidah', 'lesi_lidah', 'panas/dingin', 'asampahit', 'manis', 'refleks', 'tonsil', 'sekret_mulut', 'sekret_mulut_warna', 'leher_simetris', 'kelenjar', 'jvp', 'refleks_menelan', 'kelainan_leher'];
    foreach ($text_fields as $f) {
        $data[$f] = $_POST[$f] ?? '';
    }
    $checkbox_fields = ['bau_mulut'];
    foreach ($checkbox_fields as $cf) {
        $data[$cf] = json_encode(isset($_POST[$cf]) ? (array)$_POST[$cf] : []);
    }

    if (!$submission) {
        $submission_id = createSubmission($user_id, $form_id, null, null, $mysqli);
    } else {
        $submission_id = $submission['id'];
    }
    saveSection($submission_id, $section_name, $section_label, $data, $mysqli);
    updateSubmissionStatus($submission_id, $form_id, $mysqli);
    redirectWithMessage($_SERVER['REQUEST_URI'], 'success', 'Data berhasil disimpan.');
}

// =============================================
// HANDLE POST - DOSEN
// =============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $level === 'Dosen') {
    $submission_id = $submission['id'];
    $dosen_id = $user_id;
    $action = $_POST['action'] ?? '';
    $comment = $_POST['comment'] ?? '';
    if ($action === 'approve') {
        updateSectionStatus($submission_id, $section_name, 'approved', $mysqli);
        if (!empty($comment)) saveComment($submission_id, $section_name, $comment, $dosen_id, $mysqli);
    } elseif ($action === 'revision') {
        if (empty($comment)) redirectWithMessage($_SERVER['REQUEST_URI'], 'error', 'Komentar wajib diisi saat meminta revisi.');
        updateSectionStatus($submission_id, $section_name, 'revision', $mysqli);
        saveComment($submission_id, $section_name, $comment, $dosen_id, $mysqli);
    }
    updateReviewer($submission_id, $dosen_id, $mysqli);
    updateSubmissionStatusByDosen($submission_id, $form_id, $mysqli);
    redirectWithMessage($_SERVER['REQUEST_URI'], 'success', 'Berhasil disimpan.');
}

$comments = $submission ? getSectionComments($submission['id'], $section_name, $mysqli) : [];

?>

<main id="main" class="main">
    <?php include "kmb/format_kmb/tab.php"; ?>

    <section class="section dashboard">

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?= $_SESSION['success'];
                                                unset($_SESSION['success']); ?></div>
        <?php endif; ?>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= $_SESSION['error'];
                                            unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <?php if ($section_status): ?>
            <?php $badge = ['draft' => 'secondary', 'submitted' => 'primary', 'revision' => 'warning', 'approved' => 'success']; ?>
            <div class="alert alert-<?= $badge[$section_status] ?>">
                Status: <strong><?= ucfirst($section_status) ?></strong>
                | Reviewed by: <strong><?= $submission['dosen_name'] ? htmlspecialchars($submission['dosen_name']) : '-' ?></strong>
            </div>
        <?php endif; ?>

        <div class="card">
            <div class="card-body">
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

                    <h5 class="card-title"><strong>5. Data Biologis</strong></h5>
                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary"><strong>a. Kepala</strong></label>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-2 col-form-label">
                            <strong>Bentuk Kepala</strong>
                        </div>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="bentuk_kepala" value="<?= htmlspecialchars($existing_data['bentuk_kepala'] ?? '') ?>" <?= $ro ?>>

                        </div>
                    </div>

                    <!-- Nyeri Dada -->
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Apa ada nyeri tekan :</strong>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="nyeridada" value="ya" id="nyeridada_ya">
                                <label class="form-check-label" for="nyeridada_ya">Ya</label>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="nyeridada" value="tidak" id="nyeridada_tidak">
                                <label class="form-check-label" for="nyeridada_tidak">Tidak</label>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Apa ada benjolan:</strong>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="benjolan_kepala" value="ya" id="benjolan_kepala_ya">
                                <label class="form-check-label" for="benjolan_kepala_ya">Ya</label>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="benjolan_kepala" value="tidak" id="benjolan_kepala_tidak">
                                <label class="form-check-label" for="benjolan_kepala_tidak">Tidak</label>
                            </div>
                        </div>
                    </div>




                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary"><strong>b. Rambut</strong></label>
                    </div>

                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Penyebaran Merata</strong>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="penyebaran_merata" value="ya" id="penyebaran_merata_ya">
                                <label class="form-check-label" for="penyebaran_merata_ya">Ya</label>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="penyebaran_merata" value="tidak" id="penyebaran_merata_tidak">
                                <label class="form-check-label" for="penyebaran_merata_tidak">Tidak</label>
                            </div>
                        </div>
                    </div>


                    <div class="row mb-3">
                        <div class="col-sm-2 col-form-label">
                            <strong>Warna</strong>
                        </div>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="warna_rambut" value="<?= htmlspecialchars($existing_data['warna_rambut'] ?? '') ?>" <?= $ro ?>>

                        </div>
                    </div>


                    <!-- Nyeri Dada -->
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Mudah Dicabut</strong>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="rambut_dicabut" value="ya" id="rambut_dicabut_ya" <?= $ro_disabled ?> <?= ($existing_data['rambut_dicabut'] ?? '') === 'ya' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="rambut_dicabut_ya">Ya</label>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="rambut_dicabut" value="tidak" id="rambut_dicabut_tidak" <?= $ro_disabled ?> <?= ($existing_data['rambut_dicabut'] ?? '') === 'tidak' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="rambut_dicabut_tidak">Tidak</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-2 col-form-label">
                            <strong>Kelainan</strong>
                        </div>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="kelainan_rambut" value="<?= htmlspecialchars($existing_data['kelainan_rambut'] ?? '') ?>" <?= $ro ?>>

                        </div>
                    </div>



                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary"><strong>c. Wajah</strong></label>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-2 col-form-label">
                            <strong>Ekspresi Wajah</strong>
                        </div>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="ekspresi_wajah" value="<?= htmlspecialchars($existing_data['ekspresi_wajah'] ?? '') ?>" <?= $ro ?>>

                        </div>
                    </div>
                    <!-- Nyeri Dada -->
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Kesimetrisan Wajah</strong>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="simetris_wajah" value="ya" id="simetris_wajah_ya" <?= $ro_disabled ?> <?= ($existing_data['simetris_wajah'] ?? '') === 'ya' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="simetris_wajah_ya">Ya</label>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="simetris_wajah" value="tidak" id="simetris_wajah_tidak" <?= $ro_disabled ?> <?= ($existing_data['simetris_wajah'] ?? '') === 'tidak' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="simetris_wajah_tidak">Tidak</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Terdapat Udema</strong>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="udema_wajah" value="ya" id="udema_wajah_ya" <?= $ro_disabled ?> <?= ($existing_data['udema_wajah'] ?? '') === 'ya' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="udema_wajah_ya">Ya</label>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="udema_wajah" value="tidak" id="udema_wajah_tidak" <?= $ro_disabled ?> <?= ($existing_data['udema_wajah'] ?? '') === 'tidak' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="udema_wajah_tidak">Tidak</label>
                            </div>
                        </div>
                    </div>



                    <div class="row mb-3">
                        <div class="col-sm-2 col-form-label">
                            <strong>Kelainan</strong>
                        </div>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="kelainan_wajah" value="<?= htmlspecialchars($existing_data['kelainan_wajah'] ?? '') ?>" <?= $ro ?>>

                        </div>
                    </div>




                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary"><strong>d. Mata</strong></label>
                    </div>


                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Penglihatan</strong>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="penglihatan" value="jelas" id="penglihatan_jelas" <?= $ro_disabled ?> <?= ($existing_data['penglihatan'] ?? '') === 'jelas' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="penglihatan_jelas">Jelas</label>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="penglihatan" value="kabur" id="penglihatan_kabur" <?= $ro_disabled ?> <?= ($existing_data['penglihatan'] ?? '') === 'kabur' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="penglihatan_kabur">Kabur</label>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="penglihatan" value="rabun" id="penglihatan_rabun" <?= $ro_disabled ?> <?= ($existing_data['penglihatan'] ?? '') === 'rabun' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="penglihatan_rabun">Rabun</label>
                            </div>
                        </div>


                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="penglihatan" value="berkunang" id="penglihatan_berkunang" <?= $ro_disabled ?> <?= ($existing_data['penglihatan'] ?? '') === 'berkunang' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="penglihatan_berkunang">Berkunang</label>
                            </div>
                        </div>
                    </div>


                    <div class="row mb-3">

                        <label class="col-sm-2 col-form-label"><strong>Visus</strong></label>
                        <div class="col-sm-9">
                            <div class="row">

                                <!-- E -->
                                <div class="col-md-4 d-flex align-items-center">
                                    <label class="me-2"><strong>Kanan</strong></label>
                                    <input type="text" class="form-control" name="visus_kanan" value="<?= htmlspecialchars($existing_data['visus_kanan'] ?? '') ?>" <?= $ro ?>>
                                </div>

                                <!-- M -->
                                <div class="col-md-4 d-flex align-items-center">
                                    <label class="me-2"><strong>Kiri</strong></label>
                                    <input type="text" class="form-control" name="visus_kiri" value="<?= htmlspecialchars($existing_data['visus_kiri'] ?? '') ?>" <?= $ro ?>>
                                </div>



                            </div>


                        </div>

                    </div>



                    <div class="row mb-3">
                        <div class="col-sm-2 col-form-label">
                            <strong>Lapang Pandang</strong>
                        </div>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="lapang_pandang" value="<?= htmlspecialchars($existing_data['lapang_pandang'] ?? '') ?>" <?= $ro ?>>

                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-2 col-form-label">
                            <strong>Keadaan Mata</strong>
                        </div>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="keadaan_mata" value="<?= htmlspecialchars($existing_data['keadaan_mata'] ?? '') ?>" <?= $ro ?>>

                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Konjungtiva</strong>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="konjungtiva" value="anemis" id="konjungtiva_anemis" <?= $ro_disabled ?> <?= ($existing_data['konjungtiva'] ?? '') === 'anemis' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="konjungtiva_anemis">Anemis</label>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="konjungtiva" value="ananenmis" id="konjungtiva_ananenmis" <?= $ro_disabled ?> <?= ($existing_data['konjungtiva'] ?? '') === 'ananenmis' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="konjungtiva_ananenmis">Ananenmis</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Lesi</strong>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="lesi_mata" value="ada" id="lesi_mata_ada" <?= $ro_disabled ?> <?= ($existing_data['lesi_mata'] ?? '') === 'ada' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="lesi_mata_ada">Ada</label>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="lesi_mata" value="tidak" id="lesi_mata_tidak" <?= $ro_disabled ?> <?= ($existing_data['lesi_mata'] ?? '') === 'tidak' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="lesi_mata_tidak">Tidak</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-2 col-form-label">
                            <strong>Sclera</strong>
                        </div>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="sclera" value="<?= htmlspecialchars($existing_data['sclera'] ?? '') ?>" <?= $ro ?>>

                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Reaksi Pupil</strong>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="pupil" value="isokor" id="pupil_isokor" <?= $ro_disabled ?> <?= ($existing_data['pupil'] ?? '') === 'isokor' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="pupil_isokor">Isokor</label>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="pupil" value="anisokor" id="pupil_anisokor" <?= $ro_disabled ?> <?= ($existing_data['pupil'] ?? '') === 'anisokor' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="pupil_anisokor">Anisokor</label>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Bola Mata</strong>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="bola_mata" value="simetris" id="bola_mata_simetris" <?= $ro_disabled ?> <?= ($existing_data['bola_mata'] ?? '') === 'simetris' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="bola_mata_simetris">Simetris</label>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="bola_mata" value="tidak" id="bola_mata_tidak" <?= $ro_disabled ?> <?= ($existing_data['bola_mata'] ?? '') === 'tidak' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="bola_mata_tidak">Tidak</label>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-2 col-form-label">
                            <strong>Kelainan</strong>
                        </div>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="kelainan_mata" value="<?= htmlspecialchars($existing_data['kelainan_mata'] ?? '') ?>" <?= $ro ?>>

                        </div>
                    </div>

                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary"><strong>e. Telinga</strong></label>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Pendengaran Kiri</strong>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="pendengaran_kiri" value="jelas" id="pendengaran_kiri_jelas" <?= $ro_disabled ?> <?= ($existing_data['pendengaran_kiri'] ?? '') === 'jelas' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="pendengaran_kiri_jelas">Jelas</label>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="pendengaran_kiri" value="berkurang" id="pendengaran_kiri_berkurang" <?= $ro_disabled ?> <?= ($existing_data['pendengaran_kiri'] ?? '') === 'berkurang' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="pendengaran_kiri_berkurang">Berkurang</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Pendengaran Kanan</strong>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="pendengaran_kanan" value="jelas" id="pendengaran_kanan_jelas" <?= $ro_disabled ?> <?= ($existing_data['pendengaran_kanan'] ?? '') === 'jelas' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="pendengaran_kanan_jelas">Jelas</label>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="pendengaran_kanan" value="berkurang" id="pendengaran_kanan_berkurang" <?= $ro_disabled ?> <?= ($existing_data['pendengaran_kanan'] ?? '') === 'berkurang' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="pendengaran_kanan_berkurang">Berkurang</label>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Nyeri Kiri</strong>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="nyeri_Kiri" value="ada" id="nyeri_Kiri_ada" <?= $ro_disabled ?> <?= ($existing_data['nyeri_Kiri'] ?? '') === 'ada' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="nyeri_Kiri_ada">Ada</label>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="nyeri_Kiri" value="tidak" id="nyeri_Kiri_tidak" <?= $ro_disabled ?> <?= ($existing_data['nyeri_Kiri'] ?? '') === 'tidak' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="nyeri_Kiri_tidak">Tidak</label>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Nyeri Kanan</strong>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="nyeri_kanan" value="ada" id="nyeri_kanan_ada" <?= $ro_disabled ?> <?= ($existing_data['nyeri_kanan'] ?? '') === 'ada' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="nyeri_kanan_ada">Ada</label>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="nyeri_kanan" value="tidak" id="nyeri_kanan_tidak" <?= $ro_disabled ?> <?= ($existing_data['nyeri_kanan'] ?? '') === 'tidak' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="nyeri_kanan_tidak">Tidak</label>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Serumen</strong>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="serumen" value="ada" id="serumen_ada" <?= $ro_disabled ?> <?= ($existing_data['serumen'] ?? '') === 'ada' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="serumen_ada">Ada</label>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="serumen" value="tidak" id="serumen_tidak" <?= $ro_disabled ?> <?= ($existing_data['serumen'] ?? '') === 'tidak' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="serumen_tidak">Tidak</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-2 col-form-label">
                            <strong>Kelainan</strong>
                        </div>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="kelainan_telinga" value="<?= htmlspecialchars($existing_data['kelainan_telinga'] ?? '') ?>" <?= $ro ?>>

                        </div>
                    </div>

                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary"><strong>f. Hidung</strong></label>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Membedakan Bau</strong>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="bau" value="dapat" id="bau_dapat" <?= $ro_disabled ?> <?= ($existing_data['bau'] ?? '') === 'dapat' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="bau_dapat">Dapat</label>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="bau" value="tidak" id="bau_tidak" <?= $ro_disabled ?> <?= ($existing_data['bau'] ?? '') === 'tidak' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="bau_tidak">Tidak</label>
                            </div>
                        </div>
                    </div>
                    <!-- Pupil -->
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-2 col-form-label"><strong>Sekresi</strong></label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control" name="sekresi" value="<?= htmlspecialchars($existing_data['sekresi'] ?? '') ?>" <?= $ro ?>>
                        </div>

                        <!-- Ukuran -->
                        <label class="col-sm-2 col-form-label"><strong>Warna</strong></label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control" name="warna_hidung" value="<?= htmlspecialchars($existing_data['warna_hidung'] ?? '') ?>" <?= $ro ?>>
                        </div>


                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-2 col-form-label">
                            <strong>Mukosa</strong>
                        </div>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="mukosa_hidung" value="<?= htmlspecialchars($existing_data['mukosa_hidung'] ?? '') ?>" <?= $ro ?>>

                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Pembengkakan</strong>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="pembengkakan" value="ya" id="pembengkakan_ya" <?= $ro_disabled ?> <?= ($existing_data['pembengkakan'] ?? '') === 'ya' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="pembengkakan_ya">Ya</label>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="pembengkakan" value="tidak" id="pembengkakan_tidak" <?= $ro_disabled ?> <?= ($existing_data['pembengkakan'] ?? '') === 'tidak' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="pembengkakan_tidak">Tidak</label>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Pernafasan Cuping Hidung</strong>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="cuping_hidung" value="ya" id="cuping_hidung_ya" <?= $ro_disabled ?> <?= ($existing_data['cuping_hidung'] ?? '') === 'ya' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="cuping_hidung_ya">Ya</label>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="cuping_hidung" value="tidak" id="cuping_hidung_tidak" <?= $ro_disabled ?> <?= ($existing_data['cuping_hidung'] ?? '') === 'tidak' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="cuping_hidung_tidak">Tidak</label>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-2 col-form-label">
                            <strong>Kelainan</strong>
                        </div>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="kelainan_hidung" value="<?= htmlspecialchars($existing_data['kelainan_hidung'] ?? '') ?>" <?= $ro ?>>

                        </div>
                    </div>


                    <div class="row mb-3">
                        <label class="col-sm-12 text-primary"><strong>g. Mulut</strong></label>
                    </div>
                    <!-- Frekuensi -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Bibir</strong></label>

                        <div class="col-sm-9">
                            <div class="input-group">
                                <input type="text" class="form-control" name="bibir" value="<?= htmlspecialchars($existing_data['bibir'] ?? '') ?>" <?= $ro ?>>
                                <span class="input-group-text">Warna</span>
                            </div>


                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Simetris</strong>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="simetris" value="ya" id="simetris_ya" <?= $ro_disabled ?> <?= ($existing_data['simetris'] ?? '') === 'ya' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="simetris_ya">Ya</label>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="simetris" value="tidak" id="simetris_tidak" <?= $ro_disabled ?> <?= ($existing_data['simetris'] ?? '') === 'tidak' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="simetris_tidak">Tidak</label>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Kelembaban</strong>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="kelembaban" value="basah" id="kelembaban_basah" <?= $ro_disabled ?> <?= ($existing_data['kelembaban'] ?? '') === 'basah' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="kelembaban_basah">Basah</label>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="kelembaban" value="kering" id="kelembaban_kering" <?= $ro_disabled ?> <?= ($existing_data['kelembaban'] ?? '') === 'kering' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="kelembaban_kering">Kering</label>
                            </div>
                        </div>
                    </div>

                    <!-- Suara Jantung -->
                    <div class="row mb-2">
                        <div class="col-sm-2">
                            <strong>Gigi</strong>
                        </div>



                        <div class="col-sm-2">
                            <strong>Caries :</strong>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="caries" value="ada" id="caries_ada" <?= $ro_disabled ?> <?= ($existing_data['caries'] ?? '') === 'ada' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="caries_ada">Ada</label>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="caries" value="tidak" id="caries_tidak" <?= $ro_disabled ?> <?= ($existing_data['caries'] ?? '') === 'tidak' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="caries_tidak">Tidak</label>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">

                                <label class="form-check-label"></label>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">

                                <label class="form-check-label"></label>
                            </div>

                        </div>
                        <div class="col-sm-2"></div>
                        <!-- Lainnya -->
                        <div class="col-sm-9">
                            <label><strong>Jumlah</strong></label>
                            <input type="text" class="form-control" name="jumlah_gigi" value="<?= htmlspecialchars($existing_data['jumlah_gigi'] ?? '') ?>" <?= $ro ?>>


                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-2 col-form-label">
                            <strong>Warna</strong>
                        </div>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="warna_gigi" value="<?= htmlspecialchars($existing_data['warna_gigi'] ?? '') ?>" <?= $ro ?>>

                        </div>
                    </div>


                    <!-- Pupil -->
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-2 col-form-label"><strong>Gigi Palsu</strong></label>
                        <div class="col-sm-4">
                            <div class="input-group">
                                <input type="text" class="form-control" name="gigi_palsu_jumlah" value="<?= htmlspecialchars($existing_data['gigi_palsu_jumlah'] ?? '') ?>" <?= $ro ?>>
                                <span class="input-group-text">buah</span>
                            </div>
                        </div>

                        <!-- Letak Gigi Palsu -->
                        <label class="col-sm-2 col-form-label"><strong>Letak</strong></label>
                        <div class="col-sm-2">
                            <input type="text" class="form-control" name="letak" value="<?= htmlspecialchars($existing_data['letak'] ?? '') ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <!-- Frekuensi -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Lidah</strong></label>

                        <div class="col-sm-9">
                            <div class="input-group">
                                <input type="text" class="form-control" name="lidah" value="<?= htmlspecialchars($existing_data['lidah'] ?? '') ?>" <?= $ro ?>>
                                <span class="input-group-text">Warna</span>
                            </div>
                        </div>
                    </div>


                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Lesi</strong>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="lesi_lidah" value="ada" id="lesi_lidah_ada" <?= $ro_disabled ?> <?= ($existing_data['lesi_lidah'] ?? '') === 'ada' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="lesi_lidah_ada">Ada</label>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="lesi_lidah" value="tidak" id="lesi_lidah_tidak" <?= $ro_disabled ?> <?= ($existing_data['lesi_lidah'] ?? '') === 'tidak' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="lesi_lidah_tidak">Tidak</label>
                            </div>
                        </div>
                    </div>
                    <!-- Perabaan -->

                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Sensasi Rasa</strong>
                        </div>

                        <!-- Panas -->

                        <div class="col-sm-2">
                            <strong>Panas/Dingin</strong>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="panas/dingin" value="ada" id="panas_dingin_ada" <?= $ro_disabled ?> <?= ($existing_data['panas/dingin'] ?? '') === 'ada' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="panas_dingin_ada">Ada</label>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="panas/dingin" value="tidak" id="panas_dingin_tidak" <?= $ro_disabled ?> <?= ($existing_data['panas/dingin'] ?? '') === 'tidak' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="panas_dingin_tidak">tidak</label>
                            </div>
                        </div>
                    </div>

                    <!-- Dingin -->

                    <div class="row mb-2">
                        <div class="col-sm-2">
                        </div>

                        <div class="col-sm-2">
                            <strong>Asam / Pahit </strong>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="asampahit" value="ada" id="asampahit_ada" <?= $ro_disabled ?> <?= ($existing_data['asampahit'] ?? '') === 'ada' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="asampahit_ada">Ada</label>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="asampahit" value="tidak" id="asampahit_tidak" <?= $ro_disabled ?> <?= ($existing_data['asampahit'] ?? '') === 'tidak' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="asampahit_tidak">Tidak</label>
                            </div>
                        </div>
                    </div>

                    <!-- Tekan -->

                    <div class="row mb-2">
                        <div class="col-sm-2">
                        </div>

                        <div class="col-sm-2">
                            <strong>Manis </strong>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="manis" value="ada" id="manis_ada" <?= $ro_disabled ?> <?= ($existing_data['manis'] ?? '') === 'ada' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="manis_ada">Ada</label>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="manis" value="tidak" id="manis_tidak" <?= $ro_disabled ?> <?= ($existing_data['manis'] ?? '') === 'tidak' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="manis_tidak">Tidak</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Refleks Mengunyah</strong>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="refleks" value="dapat" id="refleks_dapat" <?= $ro_disabled ?> <?= ($existing_data['refleks'] ?? '') === 'dapat' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="refleks_dapat">Dapat</label>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="refleks" value="tidak" id="refleks_tidak" <?= $ro_disabled ?> <?= ($existing_data['refleks'] ?? '') === 'tidak' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="refleks_tidak">Tidak</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Pembesaran Tonsil</strong>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="tonsil" value="ya" id="tonsil_ya" <?= $ro_disabled ?> <?= ($existing_data['tonsil'] ?? '') === 'ya' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="tonsil_ya">Ya</label>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="tonsil" value="tidak" id="tonsil_tidak" <?= $ro_disabled ?> <?= ($existing_data['tonsil'] ?? '') === 'tidak' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="tonsil_tidak">Tidak</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Bau Mulut</strong>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="bau_mulut" value="uranium">
                                <label class="form-check-label">Uranium + / -</label>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="bau_mulut" value="amoniak">
                                <label class="form-check-label">Amoniak + / - </label>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="bau_mulut" value="aceton">
                                <label class="form-check-label">Aceton + / -</label>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="bau_mulut" value="busuk">
                                <label class="form-check-label">Busuk + / - </label>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="bau_mulut" value="alkohol">
                                <label class="form-check-label">Alkohol + / -</label>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-sm-2">
                            <strong>Sekret</strong>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="sekret_mulut" value="ada" id="sekret_mulut_ada" <?= $ro_disabled ?> <?= ($existing_data['sekret_mulut'] ?? '') === 'ada' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="sekret_mulut_ada">Ada</label>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="sekret_mulut" value="tidak" id="sekret_mulut_tidak" <?= $ro_disabled ?> <?= ($existing_data['sekret_mulut'] ?? '') === 'tidak' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="sekret_mulut_tidak">Tidak</label>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <strong></strong>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-check">

                                <label class="form-check-label"></label>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">

                                <label class="form-check-label"></label>
                            </div>

                        </div>
                        <div class="col-sm-2"></div>
                        <!-- Lainnya -->
                        <div class="col-sm-9">
                            <label><strong>Warna</strong></label>
                            <input type="text" class="form-control" name="sekret_mulut_warna" value="<?= htmlspecialchars($existing_data['sekret_mulut_warna'] ?? '') ?>" <?= $ro ?>>
                        </div>
                    </div>



                    <div>
                        <label class="col-sm-12 text-primary"><strong>h. Leher</strong></label>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Bentuk Simetris</strong>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="leher_simetris" value="ya" id="leher_simetris_ya" <?= $ro_disabled ?> <?= ($existing_data['leher_simetris'] ?? '') === 'ya' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="leher_simetris_ya">Ya</label>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="leher_simetris" value="tidak" id="leher_simetris_tidak" <?= $ro_disabled ?> <?= ($existing_data['leher_simetris'] ?? '') === 'tidak' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="leher_simetris_tidak">Tidak</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Pembesaran Kelenjar</strong>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="kelenjar" value="ada" id="kelenjar_ada" <?= $ro_disabled ?> <?= ($existing_data['kelenjar'] ?? '') === 'ada' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="kelenjar_ada">Ada</label>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="kelenjar" value="tidak" id="kelenjar_tidak" <?= $ro_disabled ?> <?= ($existing_data['kelenjar'] ?? '') === 'tidak' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="kelenjar_tidak">Tidak</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Peninggian JVP</strong>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="jvp" value="ada" id="jvp_ada" <?= $ro_disabled ?> <?= ($existing_data['jvp'] ?? '') === 'ada' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="jvp_ada">Ada</label>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="jvp" value="tidak" id="jvp_tidak" <?= $ro_disabled ?> <?= ($existing_data['jvp'] ?? '') === 'tidak' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="jvp_tidak">Tidak</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Refleks Menelan</strong>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="refleks_menelan" value="dapat" id="refleks_menelan_dapat" <?= $ro_disabled ?> <?= ($existing_data['refleks_menelan'] ?? '') === 'dapat' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="refleks_menelan_dapat">Dapat</label>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="refleks_menelan" value="tidak" id="refleks_menelan_tidak" <?= $ro_disabled ?> <?= ($existing_data['refleks_menelan'] ?? '') === 'tidak' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="refleks_menelan_tidak">Tidak</label>
                            </div>
                        </div>
                    </div>


                    <div class="row mb-3">
                        <div class="col-sm-2 col-form-label">
                            <strong>Kelainan</strong>
                        </div>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="kelainan_leher" value="<?= htmlspecialchars($existing_data['kelainan_leher'] ?? '') ?>" <?= $ro ?>>
                        </div>
                    </div>
                    <!-- TOMBOL SIMPAN -->
                    <?php if (!$is_dosen): ?>
                        <div class="row mb-3">
                            <div class="col-sm-12 d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary" <?= $ro_disabled ?>>Simpan Data</button>
                            </div>
                        </div>
                    <?php endif; ?>

                </form>
            </div>
        </div>

        <!-- KOMENTAR & ACTION DOSEN -->
        <div class="card mt-3">
            <div class="card-body">
                <h5 class="card-title"><strong>Komentar</strong></h5>
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
                                <button type="submit" name="action" value="revision" class="btn btn-warning">Minta Revisi</button>
                                <button type="submit" name="action" value="approve" class="btn btn-success">Approve</button>
                            </div>
                        </div>
                    </form>
                <?php elseif ($is_dosen && $section_status === 'approved'): ?>
                    <div class="alert alert-success">Section ini sudah di-approve.</div>
                <?php endif; ?>
            </div>
        </div>

        <?php include "tab_navigasi.php"; ?>

    </section>
</main>