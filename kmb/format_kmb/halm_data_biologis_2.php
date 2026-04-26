<?php
require_once "koneksi.php";
require_once "utils.php";

$form_id       = 15;
$level         = $_SESSION['level'];
$user_id       = $_SESSION['id_user'];
$section_name  = 'data_biologis_2';
$section_label = 'Data Biologis 2';

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
$checkbox_fields = ['bunyi_tambahan', 'bentuk_abdomen', 'keadaan_abdomen'];
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
    $text_fields = ['bentuk_dada', 'pengembangan_dada', 'perbandingan_dada', 'otot_pernafasan', 'frekuensi_nafas', 'teratur_nafas', 'irama_nafas', 'sesak_nafas', 'taktil_fremitus', 'perkusi_paru', 'suara_nafas_uraian', 'bunyi_abnormal', 'abnormal', 's1_jantung', 's2_jantung', 'bunyi_jantung', 'pulsasi_jantung', 'irama_jantung', 'bising_usus', 'bising_usus_kali', 'benjolan_abdomen', 'benjolan_letak', 'nyeri_abdomen', 'nyeri_letak', 'perkusi_abdomen', 'kelainan_abdomen', 'bentuk_genetalia', 'radang_genetalia', 'sekret_genetalia', 'skrotum_bengkak', 'rektum_benjolan', 'lesi_genetalia', 'kelainan_genetalia', 'atas_simetris', 'sensasi_halus', 'sensasi_tajam', 'sensasi_panas', 'sensasi_dingin', 'rom_atas', 'refleks_bisep', 'refleks_trisep', 'pembengkakan_atas', 'kelembaban_atas', 'temperatur_atas', 'otot_tangan_kanan', 'otot_tangan_kiri', 'kelainan_ekstremitas_atas', 'bawah_simetris', 'bawah_sensasi_halus', 'bawah_sensasi_tajam', 'bawah_sensasi_panas', 'bawah_sensasi_dingin', 'rom_bawah', 'refleks_babinski', 'varises', 'pembengkakan_bawah', 'kelembaban_bawah', 'temperatur_bawah', 'otot_kaki_kanan', 'otot_kaki_kiri', 'kelainan_ekstremitas_bawah', 'warna_kulit', 'turgor_kulit', 'edema_kulit', 'pada_daerah', 'luka_kulit', 'karakteristik_luka', 'tekstur_kulit', 'kelainan_kulit', 'clubbing_finger', 'capillary_refill_time', 'nervus1_penciuman', 'nervus2_penglihatan', 'konstriksi_pupil', 'gerakan_kelopak', 'gerakan_bola_mata', 'gerakan_mata_bawah', 'refleks_dagu', 'refleks_cornea', 'pengecapan_depan', 'fungsi_pendengaran', 'refleks_menelan', 'refleks_muntah', 'pengecapan_belakang', 'suara_pasien', 'gerakan_kepala', 'angkat_bahu', 'deviasi_lidah', 'kaku_kuduk', 'kernig_sign', 'refleks_brudzinski'];
    $data = [];
    foreach ($text_fields as $f) { $data[$f] = $_POST[$f] ?? ''; }
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
    $dosen_id      = $user_id;
    $action        = $_POST['action'] ?? '';
    $comment       = $_POST['comment'] ?? '';
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
            <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <?php if ($section_status): ?>
            <?php $badge = ['draft'=>'secondary','submitted'=>'primary','revision'=>'warning','approved'=>'success']; ?>
            <div class="alert alert-<?= $badge[$section_status] ?>">
                Status: <strong><?= ucfirst($section_status) ?></strong>
                | Reviewed by: <strong><?= $submission['dosen_name'] ? htmlspecialchars($submission['dosen_name']) : '-' ?></strong>
            </div>
        <?php endif; ?>

        <div class="card">
            <div class="card-body">
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

                    <h5 class="card-title"><strong>5. Data Biologis 2</strong></h5>

                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary"><strong>i. Dada</strong></label>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-2 col-form-label">
                            <strong>Bentuk Dada</strong>
                        </div>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="bentuk_dada" value="<?= htmlspecialchars($existing_data['bentuk_dada'] ?? '') ?>" <?= $ro ?>>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-2 col-form-label">
                            <strong>Pengembangan Dada</strong>
                        </div>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="pengembangan_dada" value="<?= htmlspecialchars($existing_data['pengembangan_dada'] ?? '') ?>" <?= $ro ?>>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-2 col-form-label">
                            <strong>Perbandingan ukuran anterior-posterior dengan transversal</strong>
                        </div>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="perbandingan_dada" value="<?= htmlspecialchars($existing_data['perbandingan_dada'] ?? '') ?>" <?= $ro ?>>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Penggunaan Otot Pernafasan Tambahan</strong>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="otot_pernafasan" value="ya" id="otot_pernafasan_ya" <?= $ro_disabled ?> <?= ($existing_data['otot_pernafasan'] ?? '') === 'ya' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="otot_pernafasan_ya">Ya</label>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="otot_pernafasan" value="tidak" id="otot_pernafasan_tidak" <?= $ro_disabled ?> <?= ($existing_data['otot_pernafasan'] ?? '') === 'tidak' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="otot_pernafasan_tidak">Tidak</label>
                            </div>
                        </div>
                    </div>



                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary"><strong>j. Paru</strong></label>
                    </div>
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-2 col-form-label"><strong>Frekuensi Nafas</strong></label>
                        <div class="col-sm-4">
                            <div class="input-group">
                                <input type="text" class="form-control" name="frekuensi_nafas" value="<?= htmlspecialchars($existing_data['frekuensi_nafas'] ?? '') ?>" <?= $ro ?>>
                                <span class="input-group-text">x/menit</span>
                            </div>
                        </div>

                        <!-- Letak Gigi Palsu -->
                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="teratur_nafas" value="teratur" id="teratur_nafas_teratur" <?= $ro_disabled ?> <?= ($existing_data['teratur_nafas'] ?? '') === 'teratur' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="teratur_nafas_teratur">Teratur</label>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="teratur_nafas" value="tidak" id="teratur_nafas_tidak" <?= $ro_disabled ?> <?= ($existing_data['teratur_nafas'] ?? '') === 'tidak' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="teratur_nafas_tidak">Tidak</label>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Irama Pernafasan</strong>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="irama_nafas" value="dangkal" id="irama_nafas_dangkal" <?= $ro_disabled ?> <?= ($existing_data['irama_nafas'] ?? '') === 'dangkal' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="irama_nafas_dangkal">Dangkal</label>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="irama_nafas" value="dalam" id="irama_nafas_dalam" <?= $ro_disabled ?> <?= ($existing_data['irama_nafas'] ?? '') === 'dalam' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="irama_nafas_dalam">Dalam</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Kesukaran Bernafas</strong>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="sesak_nafas" value="ya" id="sesak_nafas_ya" <?= $ro_disabled ?> <?= ($existing_data['sesak_nafas'] ?? '') === 'ya' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="sesak_nafas_ya">Ya</label>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="sesak_nafas" value="tidak" id="sesak_nafas_tidak" <?= $ro_disabled ?> <?= ($existing_data['sesak_nafas'] ?? '') === 'tidak' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="sesak_nafas_tidak">Tidak</label>
                            </div>
                        </div>
                    </div>



                    <div class="row mb-3">
                        <div class="col-sm-2 col-form-label">
                            <strong>Taktil Fremitus</strong>
                        </div>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="taktil_fremitus" value="<?= htmlspecialchars($existing_data['taktil_fremitus'] ?? '') ?>" <?= $ro ?>>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-2 col-form-label">
                            <strong>Bunyi Perkusi Paru</strong>
                        </div>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="perkusi_paru" value="<?= htmlspecialchars($existing_data['perkusi_paru'] ?? '') ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary"><strong>Suara Nafas</strong></label>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-2 col-form-label"><strong>Frekuensi Nafas</strong></label>
                        <!-- Letak Gigi Palsu -->
                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="teratur_nafas" value="teratur" id="teratur_nafas_teratur" <?= $ro_disabled ?> <?= ($existing_data['teratur_nafas'] ?? '') === 'teratur' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="teratur_nafas_teratur">Normal</label>
                            </div>
                        </div>
                        <div class="col-sm-7">
                            <div class="input-group">
                                <input type="text" class="form-control" name="suara_nafas_uraian" value="<?= htmlspecialchars($existing_data['suara_nafas_uraian'] ?? '') ?>" <?= $ro ?>>
                                <span class="input-group-text">uraikan</span>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <strong>Bunyi Nafas Abnormal</strong>
                        </div>





                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="bunyi_abnormal" value="wheezing" id="bunyi_abnormal_wheezing" <?= $ro_disabled ?> <?= ($existing_data['bunyi_abnormal'] ?? '') === 'wheezing' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="bunyi_abnormal_wheezing">Wheezing</label>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="bunyi_abnormal" value="ronchi" id="bunyi_abnormal_ronchi" <?= $ro_disabled ?> <?= ($existing_data['bunyi_abnormal'] ?? '') === 'ronchi' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="bunyi_abnormal_ronchi">Ronchi</label>
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
                            <label><strong>Lainnya</strong></label>
                            <input type="text" class="form-control" name="abnormal" value="<?= htmlspecialchars($existing_data['abnormal'] ?? '') ?>" <?= $ro ?>>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary"><strong>k. Jantung</strong></label>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-2 col-form-label">
                            <strong>S1</strong>
                        </div>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="s1_jantung" value="<?= htmlspecialchars($existing_data['s1_jantung'] ?? '') ?>" <?= $ro ?>>

                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-2 col-form-label">
                            <strong>S2</strong>
                        </div>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="s2_jantung" value="<?= htmlspecialchars($existing_data['s2_jantung'] ?? '') ?>" <?= $ro ?>>

                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Bunyi Teratur</strong>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="bunyi_jantung" value="ya" id="bunyi_jantung_ya" <?= $ro_disabled ?> <?= ($existing_data['bunyi_jantung'] ?? '') === 'ya' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="bunyi_jantung_ya">Ya</label>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="bunyi_jantung" value="tidak" id="bunyi_jantung_tidak" <?= $ro_disabled ?> <?= ($existing_data['bunyi_jantung'] ?? '') === 'tidak' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="bunyi_jantung_tidak">Tidak</label>
                            </div>
                        </div>
                    </div>


                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Bunyi Tambahan</strong>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="bunyi_tambahan[]" value="murmur" id="cb_bunyi_tambahan_murmur" <?= $ro_disabled ?> <?= in_array('murmur', (array)($existing_data['bunyi_tambahan'] ?? [])) ? 'checked' : '' ?>>
                                <label class="form-check-label">Murmur</label>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="bunyi_tambahan[]" value="tidak" id="cb_bunyi_tambahan_tidak" <?= $ro_disabled ?> <?= in_array('tidak', (array)($existing_data['bunyi_tambahan'] ?? [])) ? 'checked' : '' ?>>
                                <label class="form-check-label">Tidak</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-2 col-form-label">
                            <strong>Pulsasi Jantung</strong>
                        </div>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="pulsasi_jantung" value="<?= htmlspecialchars($existing_data['pulsasi_jantung'] ?? '') ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Irama</strong>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="irama_jantung" value="teratur" id="irama_jantung_teratur" <?= $ro_disabled ?> <?= ($existing_data['irama_jantung'] ?? '') === 'teratur' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="irama_jantung_teratur">Teratur</label>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="irama_jantung" value="tidak_teratur" id="irama_jantung_tidak_teratur" <?= $ro_disabled ?> <?= ($existing_data['irama_jantung'] ?? '') === 'tidak_teratur' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="irama_jantung_tidak_teratur">Tidak Teratur</label>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary"><strong>l. Abdomen</strong></label>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Bentuk</strong>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="bentuk_abdomen[]" value="datar" id="cb_bentuk_abdomen_datar" <?= $ro_disabled ?> <?= in_array('datar', (array)($existing_data['bentuk_abdomen'] ?? [])) ? 'checked' : '' ?>>
                                <label class="form-check-label">Datar</label>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="bentuk_abdomen[]" value="membuncit" id="cb_bentuk_abdomen_membuncit" <?= $ro_disabled ?> <?= in_array('membuncit', (array)($existing_data['bentuk_abdomen'] ?? [])) ? 'checked' : '' ?>>
                                <label class="form-check-label">Membuncit</label>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="bentuk_abdomen[]" value="cekung" id="cb_bentuk_abdomen_cekung" <?= $ro_disabled ?> <?= in_array('cekung', (array)($existing_data['bentuk_abdomen'] ?? [])) ? 'checked' : '' ?>>
                                <label class="form-check-label">Cekung</label>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="bentuk_abdomen[]" value="tegang" id="cb_bentuk_abdomen_tegang" <?= $ro_disabled ?> <?= in_array('tegang', (array)($existing_data['bentuk_abdomen'] ?? [])) ? 'checked' : '' ?>>
                                <label class="form-check-label">Tegang</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Keadaan</strong>
                        </div>



                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="keadaan_abdomen[]" value="parut" id="cb_keadaan_abdomen_parut" <?= $ro_disabled ?> <?= in_array('parut', (array)($existing_data['keadaan_abdomen'] ?? [])) ? 'checked' : '' ?>>
                                <label class="form-check-label">Parut</label>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="keadaan_abdomen[]" value="lesi" id="cb_keadaan_abdomen_lesi" <?= $ro_disabled ?> <?= in_array('lesi', (array)($existing_data['keadaan_abdomen'] ?? [])) ? 'checked' : '' ?>>
                                <label class="form-check-label">Lesi</label>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="keadaan_abdomen[]" value=" bercak_merah" id="cb_keadaan_abdomen_bercak_merah" <?= $ro_disabled ?> <?= in_array(' bercak_merah', (array)($existing_data['keadaan_abdomen'] ?? [])) ? 'checked' : '' ?>>
                                <label class="form-check-label"> Bercak Merah</label>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-2 col-form-label"><strong>Bising Usus</strong></label>
                        <!-- Letak Gigi Palsu -->
                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="bising_usus" value="ada" id="bising_usus_ada" <?= $ro_disabled ?> <?= ($existing_data['bising_usus'] ?? '') === 'ada' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="bising_usus_ada">Ada</label>
                            </div>

                        </div>
                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="bising_usus" value="tidak" id="bising_usus_tidak" <?= $ro_disabled ?> <?= ($existing_data['bising_usus'] ?? '') === 'tidak' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="bising_usus_tidak">Tidak</label>
                            </div>

                        </div>
                        <div class="col-sm-5">
                            <div class="input-group">
                                <input type="text" class="form-control" name="bising_usus_kali" value="<?= htmlspecialchars($existing_data['bising_usus_kali'] ?? '') ?>" <?= $ro ?>>
                                <span class="input-group-text">kali</span>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-2 col-form-label"><strong>Benjolan</strong></label>
                        <!-- Letak Gigi Palsu -->
                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="benjolan_abdomen" value="ada" id="benjolan_abdomen_ada" <?= $ro_disabled ?> <?= ($existing_data['benjolan_abdomen'] ?? '') === 'ada' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="benjolan_abdomen_ada">Ada</label>
                            </div>

                        </div>
                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="benjolan_abdomen" value="tidak" id="benjolan_abdomen_tidak" <?= $ro_disabled ?> <?= ($existing_data['benjolan_abdomen'] ?? '') === 'tidak' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="benjolan_abdomen_tidak">Tidak</label>
                            </div>

                        </div>
                        <div class="col-sm-5">
                            <div class="input-group">
                                <input type="text" class="form-control" name="benjolan_letak" value="<?= htmlspecialchars($existing_data['benjolan_letak'] ?? '') ?>" <?= $ro ?>>
                                <span class="input-group-text">letak</span>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-2 col-form-label"><strong>Nyeri Tekan</strong></label>
                        <!-- Letak Gigi Palsu -->
                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="nyeri_abdomen" value="ada" id="nyeri_abdomen_ada" <?= $ro_disabled ?> <?= ($existing_data['nyeri_abdomen'] ?? '') === 'ada' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="nyeri_abdomen_ada">Ada</label>
                            </div>

                        </div>
                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="nyeri_abdomen" value="tidak" id="nyeri_abdomen_tidak" <?= $ro_disabled ?> <?= ($existing_data['nyeri_abdomen'] ?? '') === 'tidak' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="nyeri_abdomen_tidak">Tidak</label>
                            </div>

                        </div>
                        <div class="col-sm-5">
                            <div class="input-group">
                                <input type="text" class="form-control" name="nyeri_letak" value="<?= htmlspecialchars($existing_data['nyeri_letak'] ?? '') ?>" <?= $ro ?>>
                                <span class="input-group-text">letak</span>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-2 col-form-label">
                            <strong>Perkusi Abdomen</strong>
                        </div>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="perkusi_abdomen" value="<?= htmlspecialchars($existing_data['perkusi_abdomen'] ?? '') ?>" <?= $ro ?>>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-2 col-form-label">
                            <strong>Kelainan</strong>
                        </div>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="kelainan_abdomen" value="<?= htmlspecialchars($existing_data['kelainan_abdomen'] ?? '') ?>" <?= $ro ?>>
                        </div>
                    </div>


                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary"><strong>m. Genetalia</strong></label>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Bentuk</strong>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="bentuk_genetalia" value="utuh" id="bentuk_genetalia_utuh" <?= $ro_disabled ?> <?= ($existing_data['bentuk_genetalia'] ?? '') === 'utuh' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="bentuk_genetalia_utuh">Utuh</label>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="bentuk_genetalia" value="tidak" id="bentuk_genetalia_tidak" <?= $ro_disabled ?> <?= ($existing_data['bentuk_genetalia'] ?? '') === 'tidak' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="bentuk_genetalia_tidak">Tidak</label>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Radang</strong>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="radang_genetalia" value="ada" id="radang_genetalia_ada" <?= $ro_disabled ?> <?= ($existing_data['radang_genetalia'] ?? '') === 'ada' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="radang_genetalia_ada">Ada</label>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="radang_genetalia" value="tidak" id="radang_genetalia_tidak" <?= $ro_disabled ?> <?= ($existing_data['radang_genetalia'] ?? '') === 'tidak' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="radang_genetalia_tidak">Tidak</label>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Sekret</strong>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="sekret_genetalia" value="ada" id="sekret_genetalia_ada" <?= $ro_disabled ?> <?= ($existing_data['sekret_genetalia'] ?? '') === 'ada' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="sekret_genetalia_ada">Ada</label>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="sekret_genetalia" value="tidak" id="sekret_genetalia_tidak" <?= $ro_disabled ?> <?= ($existing_data['sekret_genetalia'] ?? '') === 'tidak' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="sekret_genetalia_tidak">Tidak</label>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Pembengkakan Skrotum</strong>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="skrotum_bengkak" value="ada" id="skrotum_bengkak_ada" <?= $ro_disabled ?> <?= ($existing_data['skrotum_bengkak'] ?? '') === 'ada' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="skrotum_bengkak_ada">Ada</label>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="skrotum_bengkak" value="tidak" id="skrotum_bengkak_tidak" <?= $ro_disabled ?> <?= ($existing_data['skrotum_bengkak'] ?? '') === 'tidak' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="skrotum_bengkak_tidak">Tidak</label>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Rektum</strong>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="rektum_benjolan" value="benjolan" id="rektum_benjolan_benjolan" <?= $ro_disabled ?> <?= ($existing_data['rektum_benjolan'] ?? '') === 'benjolan' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="rektum_benjolan_benjolan">Benjolan</label>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="rektum_benjolan" value="tidak" id="rektum_benjolan_tidak" <?= $ro_disabled ?> <?= ($existing_data['rektum_benjolan'] ?? '') === 'tidak' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="rektum_benjolan_tidak">Tidak</label>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Lesi</strong>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="lesi_genetalia" value="ya" id="lesi_genetalia_ya" <?= $ro_disabled ?> <?= ($existing_data['lesi_genetalia'] ?? '') === 'ya' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="lesi_genetalia_ya">Ya</label>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="lesi_genetalia" value="tidak" id="lesi_genetalia_tidak" <?= $ro_disabled ?> <?= ($existing_data['lesi_genetalia'] ?? '') === 'tidak' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="lesi_genetalia_tidak">Tidak</label>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-2 col-form-label">
                            <strong>Kelainan</strong>
                        </div>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="kelainan_ekstremitas_atas" value="<?= htmlspecialchars($existing_data['kelainan_ekstremitas_atas'] ?? '') ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary"><strong>n. Ekstremitas</strong></label>
                    </div>


                    <div class="row mb-2">
                        <label class="col-sm-12"><strong>1) Atas</strong></label>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Bentuk Simetris</strong>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="atas_simetris" value="ya" id="atas_simetris_ya" <?= $ro_disabled ?> <?= ($existing_data['atas_simetris'] ?? '') === 'ya' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="atas_simetris_ya">Ya</label>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="atas_simetris" value="tidak" id="atas_simetris_tidak" <?= $ro_disabled ?> <?= ($existing_data['atas_simetris'] ?? '') === 'tidak' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="atas_simetris_tidak">Tidak</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Sensasi Halus</strong>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="sensasi_halus" value="ada" id="sensasi_halus_ada" <?= $ro_disabled ?> <?= ($existing_data['sensasi_halus'] ?? '') === 'ada' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="sensasi_halus_ada">Ada</label>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="sensasi_halus" value="tidak" id="sensasi_halus_tidak" <?= $ro_disabled ?> <?= ($existing_data['sensasi_halus'] ?? '') === 'tidak' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="sensasi_halus_tidak">Tidak</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Sensasi Tajam</strong>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="sensasi_tajam" value="ada" id="sensasi_tajam_ada" <?= $ro_disabled ?> <?= ($existing_data['sensasi_tajam'] ?? '') === 'ada' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="sensasi_tajam_ada">Ada</label>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="sensasi_tajam" value="tidak" id="sensasi_tajam_tidak" <?= $ro_disabled ?> <?= ($existing_data['sensasi_tajam'] ?? '') === 'tidak' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="sensasi_tajam_tidak">Tidak</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Sensasi Panas</strong>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="sensasi_panas" value="ada" id="sensasi_panas_ada" <?= $ro_disabled ?> <?= ($existing_data['sensasi_panas'] ?? '') === 'ada' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="sensasi_panas_ada">Ada</label>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="sensasi_panas" value="tidak" id="sensasi_panas_tidak" <?= $ro_disabled ?> <?= ($existing_data['sensasi_panas'] ?? '') === 'tidak' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="sensasi_panas_tidak">Tidak</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Sensasi Dingin</strong>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="sensasi_dingin" value="ada" id="sensasi_dingin_ada" <?= $ro_disabled ?> <?= ($existing_data['sensasi_dingin'] ?? '') === 'ada' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="sensasi_dingin_ada">Ada</label>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="sensasi_dingin" value="tidak" id="sensasi_dingin_tidak" <?= $ro_disabled ?> <?= ($existing_data['sensasi_dingin'] ?? '') === 'tidak' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="sensasi_dingin_tidak">Tidak</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Gerakan ROM</strong>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="rom_atas" value="dapat" id="rom_atas_dapat" <?= $ro_disabled ?> <?= ($existing_data['rom_atas'] ?? '') === 'dapat' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="rom_atas_dapat">Dapat</label>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="rom_atas" value="tidak" id="rom_atas_tidak" <?= $ro_disabled ?> <?= ($existing_data['rom_atas'] ?? '') === 'tidak' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="rom_atas_tidak">Tidak</label>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Refleks Bisep</strong>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="refleks_bisep" value="ada" id="refleks_bisep_ada" <?= $ro_disabled ?> <?= ($existing_data['refleks_bisep'] ?? '') === 'ada' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="refleks_bisep_ada">Ada</label>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="refleks_bisep" value="tidak" id="refleks_bisep_tidak" <?= $ro_disabled ?> <?= ($existing_data['refleks_bisep'] ?? '') === 'tidak' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="refleks_bisep_tidak">Tidak</label>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Refleks Trisep</strong>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="refleks_trisep" value="ada" id="refleks_trisep_ada" <?= $ro_disabled ?> <?= ($existing_data['refleks_trisep'] ?? '') === 'ada' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="refleks_trisep_ada">Ada</label>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="refleks_trisep" value="tidak" id="refleks_trisep_tidak" <?= $ro_disabled ?> <?= ($existing_data['refleks_trisep'] ?? '') === 'tidak' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="refleks_trisep_tidak">Tidak</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Pembengkakan</strong></div>
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
                        <div class="col-sm-2"><strong>Kelembaban</strong></div>
                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="kelembaban" value="lembab" id="kelembaban_lembab" <?= $ro_disabled ?> <?= ($existing_data['kelembaban'] ?? '') === 'lembab' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="kelembaban_lembab">Lembab</label>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="kelembaban" value="kering" id="kelembaban_kering" <?= $ro_disabled ?> <?= ($existing_data['kelembaban'] ?? '') === 'kering' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="kelembaban_kering">Kering</label>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Temperatur</strong></div>
                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="temperatur" value="panas" id="temperatur_panas" <?= $ro_disabled ?> <?= ($existing_data['temperatur'] ?? '') === 'panas' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="temperatur_panas">Panas</label>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="temperatur" value="dingin" id="temperatur_dingin" <?= $ro_disabled ?> <?= ($existing_data['temperatur'] ?? '') === 'dingin' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="temperatur_dingin">Dingin</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">

                        <label class="col-sm-2 col-form-label"><strong>Kekuatan Otot Tangan</strong></label>
                        <div class="col-sm-9">
                            <div class="row">

                                <!-- E -->
                                <div class="col-md-4 d-flex align-items-center">
                                    <label class="me-2"><strong>Kanan</strong></label>
                                    <input type="text" class="form-control" name="otot_tangan_kanan" value="<?= htmlspecialchars($existing_data['otot_tangan_kanan'] ?? '') ?>" <?= $ro ?>>
                                </div>

                                <!-- M -->
                                <div class="col-md-4 d-flex align-items-center">
                                    <label class="me-2"><strong>Kiri</strong></label>
                                    <input type="text" class="form-control" name="otot_tangan_kiri" value="<?= htmlspecialchars($existing_data['otot_tangan_kiri'] ?? '') ?>" <?= $ro ?>>
                                </div>


                            </div>
                        </div>

                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-2 col-form-label">
                            <strong>Kelainan</strong>
                        </div>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="kelainan_ekstremitas_bawah" value="<?= htmlspecialchars($existing_data['kelainan_ekstremitas_bawah'] ?? '') ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-3">

                        <div class="row mb-2">
                            <label class="col-sm-12"><strong>2) Bawah</strong></label>
                        </div>

                        <div class="row mb-2">
                            <div class="col-sm-2"><strong>Bentuk Simetris</strong>
                            </div>

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="bawah_simetris" value="ya" id="bawah_simetris_ya" <?= $ro_disabled ?> <?= ($existing_data['bawah_simetris'] ?? '') === 'ya' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="bawah_simetris_ya">Ya</label>
                                </div>
                            </div>

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="bawah_simetris" value="tidak" id="bawah_simetris_tidak" <?= $ro_disabled ?> <?= ($existing_data['bawah_simetris'] ?? '') === 'tidak' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="bawah_simetris_tidak">Tidak</label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-2"><strong>Sensasi Halus</strong>
                            </div>

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="bawah_sensasi_halus" value="ada" id="bawah_sensasi_halus_ada" <?= $ro_disabled ?> <?= ($existing_data['bawah_sensasi_halus'] ?? '') === 'ada' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="bawah_sensasi_halus_ada">Ada</label>
                                </div>
                            </div>

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="bawah_sensasi_halus" value="tidak" id="bawah_sensasi_halus_tidak" <?= $ro_disabled ?> <?= ($existing_data['bawah_sensasi_halus'] ?? '') === 'tidak' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="bawah_sensasi_halus_tidak">Tidak</label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-2"><strong>Sensasi Tajam</strong>
                            </div>

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="bawah_sensasi_tajam" value="ada" id="bawah_sensasi_tajam_ada" <?= $ro_disabled ?> <?= ($existing_data['bawah_sensasi_tajam'] ?? '') === 'ada' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="bawah_sensasi_tajam_ada">Ada</label>
                                </div>
                            </div>

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="bawah_sensasi_tajam" value="tidak" id="bawah_sensasi_tajam_tidak" <?= $ro_disabled ?> <?= ($existing_data['bawah_sensasi_tajam'] ?? '') === 'tidak' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="bawah_sensasi_tajam_tidak">Tidak</label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-2"><strong>Sensasi Panas</strong>
                            </div>

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="bawah_sensasi_panas" value="ada" id="bawah_sensasi_panas_ada" <?= $ro_disabled ?> <?= ($existing_data['bawah_sensasi_panas'] ?? '') === 'ada' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="bawah_sensasi_panas_ada">Ada</label>
                                </div>
                            </div>

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="bawah_sensasi_panas" value="tidak" id="bawah_sensasi_panas_tidak" <?= $ro_disabled ?> <?= ($existing_data['bawah_sensasi_panas'] ?? '') === 'tidak' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="bawah_sensasi_panas_tidak">Tidak</label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-2"><strong>Sensasi Dingin</strong>
                            </div>

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="bawah_sensasi_dingin" value="ada" id="bawah_sensasi_dingin_ada" <?= $ro_disabled ?> <?= ($existing_data['bawah_sensasi_dingin'] ?? '') === 'ada' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="bawah_sensasi_dingin_ada">Ada</label>
                                </div>
                            </div>

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="bawah_sensasi_dingin" value="tidak" id="bawah_sensasi_dingin_tidak" <?= $ro_disabled ?> <?= ($existing_data['bawah_sensasi_dingin'] ?? '') === 'tidak' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="bawah_sensasi_dingin_tidak">Tidak</label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-2"><strong>Gerakan ROM</strong>
                            </div>

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="rom_bawah" value="dapat" id="rom_bawah_dapat" <?= $ro_disabled ?> <?= ($existing_data['rom_bawah'] ?? '') === 'dapat' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="rom_bawah_dapat">Dapat</label>
                                </div>
                            </div>

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="rom_bawah" value="tidak" id="rom_bawah_tidak" <?= $ro_disabled ?> <?= ($existing_data['rom_bawah'] ?? '') === 'tidak' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="rom_bawah_tidak">Tidak</label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-2"><strong>Refleks Babinski</strong>
                            </div>

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="refleks_babinski" value="ada" id="refleks_babinski_ada" <?= $ro_disabled ?> <?= ($existing_data['refleks_babinski'] ?? '') === 'ada' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="refleks_babinski_ada">Ada</label>
                                </div>
                            </div>

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="refleks_babinski" value="tidak" id="refleks_babinski_tidak" <?= $ro_disabled ?> <?= ($existing_data['refleks_babinski'] ?? '') === 'tidak' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="refleks_babinski_tidak">Tidak</label>
                                </div>
                            </div>
                        </div>

                        <!-- Pembengkakan -->
                        <div class="row mb-2">
                            <div class="col-sm-2"><strong>Pembengkakan</strong></div>

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

                        <!-- Varises -->
                        <div class="row mb-2">
                            <div class="col-sm-2"><strong>Varises</strong></div>
                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="varises" value="ada" id="varises_ada" <?= $ro_disabled ?> <?= ($existing_data['varises'] ?? '') === 'ada' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="varises_ada">Ada</label>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="varises" value="tidak" id="varises_tidak" <?= $ro_disabled ?> <?= ($existing_data['varises'] ?? '') === 'tidak' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="varises_tidak">Tidak</label>
                                </div>
                            </div>
                        </div>

                        <!-- Kelembaban -->
                        <div class="row mb-2">
                            <div class="col-sm-2"><strong>Kelembaban</strong></div>
                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="kelembaban" value="lembab" id="kelembaban_lembab" <?= $ro_disabled ?> <?= ($existing_data['kelembaban'] ?? '') === 'lembab' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="kelembaban_lembab">Lembab</label>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="kelembaban" value="kering" id="kelembaban_kering" <?= $ro_disabled ?> <?= ($existing_data['kelembaban'] ?? '') === 'kering' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="kelembaban_kering">Kering</label>
                                </div>
                            </div>
                        </div>

                        <!-- Temperatur -->
                        <div class="row mb-2">
                            <div class="col-sm-2"><strong>Temperatur</strong></div>
                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="temperatur" value="panas" id="temperatur_panas" <?= $ro_disabled ?> <?= ($existing_data['temperatur'] ?? '') === 'panas' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="temperatur_panas">Panas</label>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="temperatur" value="dingin" id="temperatur_dingin" <?= $ro_disabled ?> <?= ($existing_data['temperatur'] ?? '') === 'dingin' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="temperatur_dingin">Dingin</label>
                                </div>
                            </div>
                        </div>

                        <!-- Kekuatan Otot Tangan -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Kekuatan Otot kaki</strong></label>
                            <div class="col-sm-9">
                                <div class="row">
                                    <div class="col-md-4 d-flex align-items-center">
                                        <label class="me-2"><strong>Kanan</strong></label>
                                        <input type="text" class="form-control" name="otot_kaki_kanan" value="<?= htmlspecialchars($existing_data['otot_kaki_kanan'] ?? '') ?>" <?= $ro ?>>
                                    </div>
                                    <div class="col-md-4 d-flex align-items-center">
                                        <label class="me-2"><strong>Kiri</strong></label>
                                        <input type="text" class="form-control" name="otot_kaki_kiri" value="<?= htmlspecialchars($existing_data['otot_kaki_kiri'] ?? '') ?>" <?= $ro ?>>
                                    </div>
                                </div>

                            </div>

                            <!-- Kelainan -->
                            <div class="row mb-3">
                                <div class="col-sm-2 col-form-label"><strong>Kelainan</strong></div>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="kelainan_genetalia" value="<?= htmlspecialchars($existing_data['kelainan_genetalia'] ?? '') ?>" <?= $ro ?>>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <label class="col-sm-12 text-primary"><strong>o. Kulit</strong></label>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-2 col-form-label">
                                    <strong>Warna</strong>
                                </div>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="warna_kulit" value="<?= htmlspecialchars($existing_data['warna_kulit'] ?? '') ?>" <?= $ro ?>>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-2"><strong>Turgor</strong>
                                </div>

                                <div class="col-sm-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="turgor_kulit" value="elastis" id="turgor_kulit_elastis" <?= $ro_disabled ?> <?= ($existing_data['turgor_kulit'] ?? '') === 'elastis' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="turgor_kulit_elastis">Elastis</label>
                                    </div>
                                </div>

                                <div class="col-sm-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="turgor_kulit" value="menurun" id="turgor_kulit_menurun" <?= $ro_disabled ?> <?= ($existing_data['turgor_kulit'] ?? '') === 'menurun' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="turgor_kulit_menurun">Tidak</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-2"><strong>Kelembaban</strong>
                                </div>

                                <div class="col-sm-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="kelembaban" value="lembab" id="kelembaban_lembab" <?= $ro_disabled ?> <?= ($existing_data['kelembaban'] ?? '') === 'lembab' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="kelembaban_lembab">Lembab</label>
                                    </div>
                                </div>

                                <div class="col-sm-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="kelembaban" value="kering" id="kelembaban_kering" <?= $ro_disabled ?> <?= ($existing_data['kelembaban'] ?? '') === 'kering' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="kelembaban_kering">Kering</label>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3 align-items-center">
                                <label class="col-sm-2 col-form-label"><strong>Edema</strong></label>
                                <!-- Letak Gigi Palsu -->
                                <div class="col-sm-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="edema_kulit" value="ada" id="edema_kulit_ada" <?= $ro_disabled ?> <?= ($existing_data['edema_kulit'] ?? '') === 'ada' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="edema_kulit_ada">Ada</label>
                                    </div>

                                </div>
                                <div class="col-sm-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="edema_kulit" value="tidak" id="edema_kulit_tidak" <?= $ro_disabled ?> <?= ($existing_data['edema_kulit'] ?? '') === 'tidak' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="edema_kulit_tidak">Tidak</label>
                                    </div>

                                </div>

                                <div class="col-sm-5">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="pada_daerah" value="<?= htmlspecialchars($existing_data['pada_daerah'] ?? '') ?>" <?= $ro ?>>
                                        <span class="input-group-text">Pada Daerah</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3 align-items-center">
                                <label class="col-sm-2 col-form-label"><strong>Luka</strong></label>
                                <!-- Letak Gigi Palsu -->
                                <div class="col-sm-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="luka_kulit" value="ada" id="luka_kulit_ada" <?= $ro_disabled ?> <?= ($existing_data['luka_kulit'] ?? '') === 'ada' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="luka_kulit_ada">Ada</label>
                                    </div>

                                </div>
                                <div class="col-sm-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="luka_kulit" value="tidak" id="luka_kulit_tidak" <?= $ro_disabled ?> <?= ($existing_data['luka_kulit'] ?? '') === 'tidak' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="luka_kulit_tidak">Tidak</label>
                                    </div>

                                </div>

                                <div class="col-sm-5">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="pada_daerah" value="<?= htmlspecialchars($existing_data['pada_daerah'] ?? '') ?>" <?= $ro ?>>
                                        <span class="input-group-text">Pada Daerah</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-2 col-form-label">
                                    <strong>Karakteristik Luka</strong>
                                </div>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="karakteristik_luka" value="<?= htmlspecialchars($existing_data['karakteristik_luka'] ?? '') ?>" <?= $ro ?>>

                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-2"><strong>Tekstur</strong>
                                </div>

                                <div class="col-sm-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="tekstur_kulit" value="licin" id="tekstur_kulit_licin" <?= $ro_disabled ?> <?= ($existing_data['tekstur_kulit'] ?? '') === 'licin' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="tekstur_kulit_licin">Licin</label>
                                    </div>
                                </div>

                                <div class="col-sm-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="tekstur_kulit" value="keriput" id="tekstur_kulit_keriput" <?= $ro_disabled ?> <?= ($existing_data['tekstur_kulit'] ?? '') === 'keriput' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="tekstur_kulit_keriput">Keriput</label>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="tekstur_kulit" value="kasar" id="tekstur_kulit_kasar" <?= $ro_disabled ?> <?= ($existing_data['tekstur_kulit'] ?? '') === 'kasar' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="tekstur_kulit_kasar">Kasar</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-2 col-form-label">
                                    <strong>Kelainan</strong>
                                </div>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="kelainan_kulit" value="<?= htmlspecialchars($existing_data['kelainan_kulit'] ?? '') ?>" <?= $ro ?>>

                                </div>
                            </div>
                            <div class="row mb-2">
                                <label class="col-sm-12 text-primary"><strong>p. Kuku</strong></label>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-2"><strong>Clubbing Finger</strong>
                                </div>

                                <div class="col-sm-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="clubbing_finger" value="ya" id="clubbing_finger_ya" <?= $ro_disabled ?> <?= ($existing_data['clubbing_finger'] ?? '') === 'ya' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="clubbing_finger_ya">Ya</label>
                                    </div>
                                </div>

                                <div class="col-sm-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="clubbing_finger" value="tidak" id="clubbing_finger_tidak" <?= $ro_disabled ?> <?= ($existing_data['clubbing_finger'] ?? '') === 'tidak' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="clubbing_finger_tidak">Tidak</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-2 col-form-label">
                                    <strong>Capillary Refill Time</strong>
                                </div>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="capillary_refill_time" value="<?= htmlspecialchars($existing_data['capillary_refill_time'] ?? '') ?>" <?= $ro ?>>

                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-2 col-form-label">
                                    <strong>Keadaan</strong>
                                </div>
                            </div>


                            <div class="row mb-2">
                                <label class="col-sm-12 text-primary"><strong>q. Status Neurologi</strong></label>
                            </div>




                            <div class="row mb-2">
                                <label class="col-sm-12"><strong>1) Saraf-saraf Kranial</strong></label>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-2 col-form-label">
                                    <strong>a) Nervus I (Olfactorius) - Penciuman</strong>
                                </div>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="nervus1_penciuman" value="<?= htmlspecialchars($existing_data['nervus1_penciuman'] ?? '') ?>" <?= $ro ?>>

                                </div>
                            </div>


                            <div class="row mb-3">
                                <div class="col-sm-2 col-form-label">
                                    <strong>b) Nervus II (Opticus) - Penglihatan</strong>
                                </div>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="nervus2_penglihatan" value="<?= htmlspecialchars($existing_data['nervus2_penglihatan'] ?? '') ?>" <?= $ro ?>>

                                </div>
                            </div>


                            <div class="row mb-2">
                                <label class="col-sm-12"><strong>c) Nervus III, IV, VI (Oculomotorius, Trochlearis, Abducens)</strong></label>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-2 col-form-label">
                                    <strong>Konstriksi Pupil</strong>
                                </div>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="konstriksi_pupil" value="<?= htmlspecialchars($existing_data['konstriksi_pupil'] ?? '') ?>" <?= $ro ?>>
                                </div>
                            </div>


                            <div class="row mb-3">
                                <div class="col-sm-2 col-form-label">
                                    <strong> <label class="col-sm-11 col-form-label"><strong>Gerakan Kelopak Mata</strong></label>
                                    </strong>
                                </div>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="gerakan_kelopak" value="<?= htmlspecialchars($existing_data['gerakan_kelopak'] ?? '') ?>" <?= $ro ?>>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-sm-2 col-form-label">
                                    <strong>Pergerakan Bola Mata</strong>
                                </div>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="gerakan_bola_mata" value="<?= htmlspecialchars($existing_data['gerakan_bola_mata'] ?? '') ?>" <?= $ro ?>>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-sm-2 col-form-label">
                                    <strong>Pergerakan Mata ke Bawah & Dalam</strong>
                                </div>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="gerakan_mata_bawah" value="<?= htmlspecialchars($existing_data['gerakan_mata_bawah'] ?? '') ?>" <?= $ro ?>>
                                </div>
                            </div>



                            <div class="row mb-2">
                                <label class="col-sm-12"><strong>d) Nervus V (Trigeminus)</strong></label>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-2 col-form-label">
                                    <strong>Refleks Dagu</strong>
                                </div>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="refleks_dagu" value="<?= htmlspecialchars($existing_data['refleks_dagu'] ?? '') ?>" <?= $ro ?>>
                                </div>
                            </div>


                            <div class="row mb-3">
                                <div class="col-sm-2 col-form-label">
                                    <strong>Refleks Cornea</strong>
                                </div>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="refleks_cornea" value="<?= htmlspecialchars($existing_data['refleks_cornea'] ?? '') ?>" <?= $ro ?>>
                                </div>
                            </div>


                            <div class="row mb-2">
                                <label class="col-sm-12"><strong>e) Nervus VII (Facialis)</strong></label>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-2 col-form-label">
                                    <strong>Pengecapan 2/3 Lidah Bagian Depan</strong>
                                </div>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="pengecapan_depan" value="<?= htmlspecialchars($existing_data['pengecapan_depan'] ?? '') ?>" <?= $ro ?>>
                                </div>
                            </div>


                            <div class="row mb-2">
                                <label class="col-sm-12"><strong>f) Nervus VIII (Acusticus)</strong></label>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-2 col-form-label">
                                    <strong>Fungsi Pendengaran</strong>
                                </div>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="fungsi_pendengaran" value="<?= htmlspecialchars($existing_data['fungsi_pendengaran'] ?? '') ?>" <?= $ro ?>>
                                </div>
                            </div>


                            <div class="row mb-2">
                                <label class="col-sm-12"><strong>g) Nervus IX & X (Glossopharyngeus dan Vagus)</strong></label>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-2 col-form-label">
                                    <strong>Refleks Menelan</strong>
                                </div>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="refleks_menelan" value="<?= htmlspecialchars($existing_data['refleks_menelan'] ?? '') ?>" <?= $ro ?>>

                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-sm-2 col-form-label">
                                    <strong>Refleks Muntah</strong>
                                </div>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="refleks_muntah" value="<?= htmlspecialchars($existing_data['refleks_muntah'] ?? '') ?>" <?= $ro ?>>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-2 col-form-label">
                                    <strong>Pengecapan 1/3 Lidah Belakang</strong>
                                </div>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="pengecapan_belakang" value="<?= htmlspecialchars($existing_data['pengecapan_belakang'] ?? '') ?>" <?= $ro ?>>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-sm-2 col-form-label">
                                    <strong>Suara</strong>
                                </div>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="suara_pasien" value="<?= htmlspecialchars($existing_data['suara_pasien'] ?? '') ?>" <?= $ro ?>>
                                </div>
                            </div>


                            <div class="row mb-2">
                                <label class="col-sm-12"><strong>h) Nervus XI (Assesorius)</strong></label>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-2 col-form-label">
                                    <strong>Memalingkan Kepala</strong>
                                </div>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="gerakan_kepala" value="<?= htmlspecialchars($existing_data['gerakan_kepala'] ?? '') ?>" <?= $ro ?>>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-sm-2 col-form-label">
                                    <strong>Mengangkat Bahu</strong>
                                </div>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="angkat_bahu" value="<?= htmlspecialchars($existing_data['angkat_bahu'] ?? '') ?>" <?= $ro ?>>
                                </div>
                            </div>


                            <div class="row mb-2">
                                <label class="col-sm-12"><strong>i) Nervus XII (Hypoglossus)</strong></label>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-2 col-form-label">
                                    <strong>Deviasi Lidah</strong>
                                </div>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="deviasi_lidah" value="<?= htmlspecialchars($existing_data['deviasi_lidah'] ?? '') ?>" <?= $ro ?>>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <label class="col-sm-12"><strong>2) Tanda-tanda Peradangan Selaput Otak</strong></label>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-2 col-form-label">
                                    <strong>Kaku Kuduk</strong>
                                </div>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="kaku_kuduk" value="<?= htmlspecialchars($existing_data['kaku_kuduk'] ?? '') ?>" <?= $ro ?>>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-sm-2 col-form-label">
                                    <strong>Kernig Sign</strong>
                                </div>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="kernig_sign" value="<?= htmlspecialchars($existing_data['kernig_sign'] ?? '') ?>" <?= $ro ?>>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-sm-2 col-form-label">
                                    <strong>Refleks Brudzinski</strong>
                                </div>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="refleks_brudzinski" value="<?= htmlspecialchars($existing_data['refleks_brudzinski'] ?? '') ?>" <?= $ro ?>>

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