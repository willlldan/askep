<?php
require_once "koneksi.php";
require_once "utils.php";

$form_id       = 15;
$level         = $_SESSION['level'];
$user_id       = $_SESSION['id_user'];
$section_name  = 'data_biologis';
$section_label = 'Data Biologis';

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

// Decode checkbox fields yang disimpan sebagai JSON array
$checkbox_fields = ['bau_mulut', 'bunyi_tambahan', 'bentuk_abdomen', 'keadaan_abdomen'];
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

    $text_fields = [
        'bentuk_kepala','nyeridada','benjolan_kepala','penyebaran_merata','warna_rambut',
        'rambut_dicabut','kelainan_rambut','ekspresi_wajah','simetris_wajah','udema_wajah',
        'kelainan_wajah','penglihatan','visus_kanan','visus_kiri','lapang_pandang','keadaan_mata',
        'konjungtiva','lesi_mata','sclera','pupil','bola_mata','kelainan_mata',
        'pendengaran_kiri','pendengaran_kanan','nyeri_Kiri','nyeri_kanan','serumen','kelainan_telinga',
        'bau','sekresi','warna_hidung','mukosa_hidung','pembengkakan','cuping_hidung','kelainan_hidung',
        'bibir','simetris','kelembaban','caries','jumlah_gigi','warna_gigi','gigi_palsu_jumlah','letak',
        'lidah','lesi_lidah','panas/dingin','asampahit','manis','refleks','tonsil',
        'sekret_mulut','sekret_mulut_warna',
        'leher_simetris','kelenjar','jvp','refleks_menelan','kelainan_leher',
        'bentuk_dada','pengembangan_dada','perbandingan_dada','otot_pernafasan',
        'frekuensi_nafas','teratur_nafas','irama_nafas','sesak_nafas',
        'taktil_fremitus','perkusi_paru','suara_nafas_uraian','bunyi_abnormal','abnormal',
        's1_jantung','s2_jantung','bunyi_jantung','pulsasi_jantung','irama_jantung',
        'bising_usus','bising_usus_kali','benjolan_abdomen','benjolan_letak',
        'nyeri_abdomen','nyeri_letak','perkusi_abdomen','kelainan_abdomen',
        'bentuk_genetalia','radang_genetalia','sekret_genetalia','skrotum_bengkak',
        'rektum_benjolan','lesi_genetalia','kelainan_genetalia',
        'atas_simetris','sensasi_halus','sensasi_tajam','sensasi_panas','sensasi_dingin','rom_atas',
        'refleks_bisep','refleks_trisep','pembengkakan_atas','kelembaban_atas','temperatur_atas',
        'otot_tangan_kanan','otot_tangan_kiri','kelainan_ekstremitas_atas',
        'bawah_simetris','bawah_sensasi_halus','bawah_sensasi_tajam','bawah_sensasi_panas',
        'bawah_sensasi_dingin','rom_bawah','refleks_babinski','pembengkakan_bawah',
        'varises','kelembaban_bawah','temperatur_bawah',
        'otot_kaki_kanan','otot_kaki_kiri','kelainan_ekstremitas_bawah',
        'warna_kulit','turgor_kulit','kelembaban_kulit','edema_kulit','pada_daerah',
        'luka_kulit','luka_pada_daerah','karakteristik_luka','tekstur_kulit','kelainan_kulit',
        'clubbing_finger','capillary_refill_time',
        'nervus1_penciuman','nervus2_penglihatan','konstriksi_pupil','gerakan_kelopak',
        'gerakan_bola_mata','gerakan_mata_bawah','refleks_dagu','refleks_cornea',
        'pengecapan_depan','fungsi_pendengaran','refleks_muntah','pengecapan_belakang',
        'suara_pasien','gerakan_kepala','angkat_bahu','deviasi_lidah',
        'kaku_kuduk','kernig_sign','refleks_brudzinski',
        'harapan_klien','rendah_diri','pendapat_keadaan','status_rumah',
        'hubungan_keluarga','pengambil_keputusan','ekonomi_cukup',
        'hubungan_keluarga_baik','kegiatan_kemasyarakatan',
        'tanggal_pemeriksaan','nama_pemeriksaan','hasil','satuan','nilai_rujukan',
        'radiologi','data_penunjang_lain',
    ];

    $data = [];
    foreach ($text_fields as $f) {
        $data[$f] = $_POST[$f] ?? '';
    }
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
                                <input class="form-check-input" type="checkbox" name="bunyi_tambahan" value="murmur">
                                <label class="form-check-label">Murmur</label>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="bunyi_tambahan" value="tidak">
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
                                <input class="form-check-input" type="checkbox" name="bentuk_abdomen" value="datar">
                                <label class="form-check-label">Datar</label>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="bentuk_abdomen" value="membuncit">
                                <label class="form-check-label">Membuncit</label>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="bentuk_abdomen" value="cekung">
                                <label class="form-check-label">Cekung</label>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="bentuk_abdomen" value="tegang">
                                <label class="form-check-label">Tegang</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Keadaan</strong>
                        </div>



                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="keadaan_abdomen" value="parut">
                                <label class="form-check-label">Parut</label>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="keadaan_abdomen" value="lesi">
                                <label class="form-check-label">Lesi</label>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="keadaan_abdomen" value=" bercak_merah">
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


                            <div class="row mb-2">
                                <label class="col-sm-12 text-primary"><strong>b. Data Psikologis</strong></label>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-2 col-form-label">
                                    <strong>1) Apakah yang diharapkan klien saat ini</strong>
                                </div>
                                <div class="col-sm-9">
                                    <textarea class="form-control" rows="3" cols="30" name="harapan_klien" style="display:block; overflow:hidden; resize: none;"
                                        oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-2 col-form-label">
                                    <strong>2) Apakah klien merasa rendah diri dengan keadaannya saat ini</strong>
                                </div>
                                <div class="col-sm-9">
                                    <textarea class="form-control" rows="4" cols="30" name="rendah_diri" style="display:block; overflow:hidden; resize: none;"
                                        oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-sm-2 col-form-label">
                                    <strong>3) Bagaimana menurut klien dengan keadaannya saat ini</strong>
                                </div>
                                <div class="col-sm-9">
                                    <textarea class="form-control" rows="5" cols="30" name="pendapat_keadaan" style="display:block; overflow:hidden; resize: none;"
                                        oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-sm-2 col-form-label">
                                    <strong>4) Apakah klien tinggal di rumah sendiri atau rumah kontrakan</strong>
                                </div>
                                <div class="col-sm-9">
                                    <textarea class="form-control" rows="5" cols="30" name="status_rumah" style="display:block; overflow:hidden; resize: none;"
                                        oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-sm-2 col-form-label">
                                    <strong>5) Apakah hubungan antar keluarga harmonis atau berjauhan</strong>
                                </div>
                                <div class="col-sm-9">
                                    <textarea class="form-control" rows="5" cols="30" name="hubungan_keluarga" style="display:block; overflow:hidden; resize: none;"
                                        oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-sm-2 col-form-label">
                                    <strong>6) Siapakah yang mengambil keputusan dalam keluarga</strong>
                                </div>
                                <div class="col-sm-9">
                                    <textarea class="form-control" rows="4" cols="30" name="pengambil_keputusan" style="display:block; overflow:hidden; resize: none;"
                                        oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-sm-2 col-form-label">
                                    <strong>7) Apakah klien merasa cukup dengan keadaan ekonomi keluarganya saat ini</strong>
                                </div>
                                <div class="col-sm-9">
                                    <textarea class="form-control" rows="5" cols="30" name="ekonomi_cukup" style="display:block; overflow:hidden; resize: none;"
                                        oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-sm-2 col-form-label">
                                    <strong>8) Apakah hubungan antar keluarga baik</strong>
                                </div>
                                <div class="col-sm-9">
                                    <textarea class="form-control" rows="3" cols="30" name="hubungan_keluarga_baik" style="display:block; overflow:hidden; resize: none;"
                                        oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-sm-2 col-form-label">

                                    <strong>9) Apakah klien aktif mengikuti kegiatan kemasyarakatan di sekitar tempat tinggalnya</strong>
                                </div>
                                <div class="col-sm-9">
                                    <textarea class="form-control" rows="7" cols="30" name="kelainan_mata" style="display:block; overflow:hidden; resize: none;"
                                        oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                                </div>
                            </div>



                            <div class="row mb-2 mt-4">
                                <label class="col-sm-12 text-primary"><strong>c. Data Penunjang</strong></label>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label"><strong>1) Laboratorium </strong></label>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-2 col-form-label">
                                    <strong>Tanggal Pemeriksaan</strong>
                                </div>
                                <div class="col-sm-9">
                                    <input type="date" class="form-control" name="tanggal_pemeriksaan" value="<?= htmlspecialchars($existing_data['tanggal_pemeriksaan'] ?? '') ?>" <?= $ro ?>>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-2 col-form-label">
                                    <strong>Nama Pemeriksaan</strong>
                                </div>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="nama_pemeriksaan" value="<?= htmlspecialchars($existing_data['nama_pemeriksaan'] ?? '') ?>" <?= $ro ?>>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-2 col-form-label">
                                    <strong>Hasil</strong>
                                </div>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="hasil" value="<?= htmlspecialchars($existing_data['hasil'] ?? '') ?>" <?= $ro ?>>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-2 col-form-label">
                                    <strong>Satuan</strong>
                                </div>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="satuan" value="<?= htmlspecialchars($existing_data['satuan'] ?? '') ?>" <?= $ro ?>>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-2 col-form-label">
                                    <strong>Nilai Rujukan</strong>
                                </div>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="nilai_rujukan" value="<?= htmlspecialchars($existing_data['nilai_rujukan'] ?? '') ?>" <?= $ro ?>>
                                </div>
                            </div>


                            <h5 class="card-title"><strong>1) Laboratorium</strong></h5>

                            <style>
                                .table-laboratorium {
                                    table-layout: fixed;
                                    width: 100%
                                }

                                .table-laboratorium td,
                                .table-laboratorium th {
                                    word-wrap: break-word;
                                    white-space: normal;
                                    vertical-align: top;
                                }
                            </style>

                            <table class="table table-bordered table-laboratorium">
                                <thead>
                                    <tr>
                                        <th class="text-center">Nama Pemeriksaan</th>
                                        <th class="text-center">Hasil</th>
                                        <th class="text-center">Satuan</th>
                                        <th class="text-center">Nilai Rujukan</th>
                                    </tr>
                                </thead>
                            </table>

                            <div class="row mb-3">
                                <div class="col-sm-2 col-form-label">
                                    <strong>2) Radiologi (Tgl Pemeriksaan & Hasil)</strong>
                                </div>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="radiologi" value="<?= htmlspecialchars($existing_data['radiologi'] ?? '') ?>" <?= $ro ?>>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-sm-2 col-form-label">
                                    <strong>3) Lainnya (USG, CT Scan, dll)</strong>
                                </div>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="data_penunjang_lain" value="<?= htmlspecialchars($existing_data['data_penunjang_lain'] ?? '') ?>" <?= $ro ?>>
                                </div>
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