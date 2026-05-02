<?php
require_once "koneksi.php";
require_once "utils.php";

$form_id       = 4;
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

    $data = [
        'inspeksi_wajah'                => $_POST['inspeksiwajah'] ?? '',
        'inspeksi_konjungtiva'          => $_POST['inspeksikonjungtiva'] ?? '',
        'inspeksi_sklera'               => $_POST['inspeksisklera'] ?? '',
        'inspeksi_gigi'                 => $_POST['inspeksigigi'] ?? '',
        'inspeksi_gusi'                 => $_POST['inspeksigusi'] ?? '',
        'masalah_khusus_mulut'          => $_POST['masalahkhususmulut'] ?? '',
        'inspeksi_leher'                => $_POST['inspeksileher'] ?? '',
        'bunyi_napas'                   => $_POST['bunyinapas'] ?? '',
        'suara_jantung'                 => $_POST['suarajantung'] ?? '',
        'masalah_khusus_dada'           => $_POST['masalahkhususdada'] ?? '',
        'payudara'                      => $_POST['payudara'] ?? '',
        'puting'                        => $_POST['puting'] ?? '',
        'masalahkhususpayudara'         => $_POST['masalahkhususpayudara'] ?? '',
        'lineanigra'                    => $_POST['lineanigra'] ?? '',
        'inspeksikontraksi'             => $_POST['inspeksikontraksi'] ?? '',
        'dindingperut'                  => $_POST['dindingperut'] ?? '',
        'inspeksitfu'                   => $_POST['masalahkhususdada'] ?? '',
        'inspeksikontraksi1'            => $_POST['inspeksikontraksi1'] ?? '',
        'leopoldi'                      => $_POST['leopoldi'] ?? '',
        'kanan'                         => $_POST['kanan'] ?? '',
        'kiri'                          => $_POST['kiri'] ?? '',
        'leopoldiii'                    => $_POST['leopoldiii'] ?? '',
        'leopoldiv'                     => $_POST['leopoldiv'] ?? '',
        'pemeriksaandjj'                => $_POST['pemeriksaandjj'] ?? '',
        'intensitas'                    => $_POST['intensitas'] ?? '',
        'keteraturan'                   => $_POST['keteraturan'] ?? '',
        'linea_nigra1'                  => $_POST['linea_nigra1'] ?? '',
        'inspeksi_keputihan'             => $_POST['inspeksikeputihan'] ?? '',
        'inspeksi_hemoroid'              => $_POST['inspeksihemoroid'] ?? '',
        'masalah_khusus_perineum'         => $_POST['masalahkhususperineum'] ?? '',
        'inspeksi_ekstremitas_bawah'      => $_POST['inspeksiekstremitasbawah'] ?? '',
        'inspeksi_istirahat'             => $_POST['inspeksiistirahat'] ?? '',
        'inspeksi_kenyamanan'            => $_POST['inspeksikenyamanan'] ?? '',
        'masalah_khusus_istirahat'        => $_POST['masalahkhususistirahat'] ?? '',
        'inspeksi_mobilisasi'            => $_POST['inspeksimobilisasi'] ?? '',
        'masalah_khusus_mobilisasi'       => $_POST['masalahkhususmobilisasi'] ?? '',
        'inspeksi_nutrisi'               => $_POST['inspeksinutrisi'] ?? '',
        'inspeksi_cairan'                => $_POST['inspeksicairan'] ?? '',
        'inspeksi_pantangan'             => $_POST['inspeksipantangan'] ?? '',
        'masalah_khusus_polanutrisi'      => $_POST['masalahkhususpolanutrisi'] ?? '',
        'tanda_melahirkan'               => $_POST['tandamelahirkan'] ?? '',
        'nyeri_melahirkan'               => $_POST['nyerimelahirkan'] ?? '',
        'persalinan'                    => $_POST['persalinan'] ?? '',
        'asi_dan_payudara'                => $_POST['asidanpayudara'] ?? '',
    ];

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
$ro_select   = $is_readonly ? 'disabled' : '';
?>

<main id="main" class="main">
    <?php include "maternitas/resume_antenatal_care/tab.php"; ?>

    <section class="section dashboard">
        <?php include dirname(__DIR__, 2) . '/partials/notifikasi.php'; ?>
        <?php include dirname(__DIR__, 2) . '/partials/status_section.php'; ?>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title"> <strong>e. Pemeriksaan Fisik</strong></h5>



                <!-- General Form Elements -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

                    <!-- Bagian Wajah -->

                    <div class="row mb-2">
                        <label class="col-sm-2 col-form-label text-primary">
                            <strong>Wajah</strong>
                    </div>

                    <!-- Inspeksi -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Wajah</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Adakah pembengkakan, hiperpigmentasi/cloasma gravidarum, area jika ada cloasma. Hasil:</small>
                            <textarea name="inspeksiwajah" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('inspeksi_wajah', $existing_data) ?></textarea>

                        </div>
                    </div>

                    <!-- Bagian Mata -->

                    <div class="row mb-2">
                        <label class="col-sm-2 col-form-label text-primary">
                            <strong>Mata</strong>
                    </div>

                    <!-- Inspeksi -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Konjungtiva</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Apakah anemis/an-anemis. Hasil:</small>
                            <textarea name="inspeksikonjungtiva" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('inspeksi_konjungtiva', $existing_data) ?></textarea>

                        </div>
                    </div>

                    <!-- Inspeksi Sklera -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Sklera</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Ikterik/An-ikterik. Hasil:</small>
                            <textarea name="inspeksisklera" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('inspeksi_sklera', $existing_data) ?></textarea>

                        </div>
                    </div>

                    <!-- Bagian Mulut -->

                    <div class="row mb-2">
                        <label class="col-sm-2 col-form-label text-primary">
                            <strong>Mulut</strong>
                    </div>

                    <!-- Inspeksi Gigi -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Gigi Gigi</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Amati jumlah, warna, kebersihan, karies. Hasil:</small>
                            <textarea name="inspeksigigi" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('inspeksi_gigi', $existing_data) ?></textarea>

                        </div>
                    </div>

                    <!-- Inspeksi Gusi -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Gusi</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Adakah atau tidak lesi/pembengkakan? Hasil:</small>
                            <textarea name="inspeksigusi" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('inspeksi_gusi', $existing_data) ?></textarea>

                        </div>
                    </div>

                    <!-- Keluhan / Masalah Khusus -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Keluhan/Masalah Khusus</strong></label>

                        <div class="col-sm-9">
                            <textarea name="masalahkhususmulut" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('masalah_khusus_mulut', $existing_data) ?></textarea>

                        </div>
                    </div>

                    <!-- Bagian Leher -->

                    <div class="row mb-2">
                        <label class="col-sm-2 col-form-label text-primary">
                            <strong>Leher</strong>
                    </div>

                    <!-- Inspeksi -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Leher</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Adakah Distensi vena jugularis/tidak ada. Hasil:</small>
                            <textarea name="inspeksileher" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('inspeksi_leher', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- Bagian Dada -->

                    <div class="row mb-2">
                        <label class="col-sm-8 col-form-label text-primary">
                            <strong>Dada; Sistem Pernapasan dan Kardiovaskuler</strong>
                    </div>

                    <!-- Auskultasi Bunyi Napas-->

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Auskultasi</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Bunyi Napas. Hasil:</small>
                            <textarea name="bunyinapas" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('bunyi_napas', $existing_data) ?></textarea>

                        </div>
                    </div>

                    <!-- Auskultasi Suara Jantung -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Suara Jantung (Apakah ada mur-mur dan gallop). Hasil:</small>
                            <textarea name="suarajantung" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('suara_jantung', $existing_data) ?></textarea></textarea>


                        </div>
                    </div>

                    <!-- Masalah Khusus -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Masalah Khusus</strong></label>

                        <div class="col-sm-9">
                            <textarea name="masalahkhususdada" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('masalah_khusus_dada', $existing_data) ?></textarea>

                        </div>
                    </div>

                    <!-- Bagian Payudara -->

                    <div class="row mb-2">
                        <label class="col-sm-2 col-form-label text-primary">
                            <strong>Payudara</strong>
                    </div>

                    <!-- Payudara -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Payudara</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Hyperpigmentasi pada areola mammae, pengeluaran cairan:</small>
                            <textarea name="payudara" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('payudara', $existing_data) ?></textarea>

                        </div>
                    </div>

                    <!--  Puting -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Puting</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Bentuk puting:</small>
                            <textarea name="puting" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('puting', $existing_data) ?></textarea>

                        </div>
                    </div>

                    <!-- Masalah Khusus -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Masalah Khusus</strong></label>

                        <div class="col-sm-9">
                            <textarea name="masalahkhususpayudara" class="form-control" rows="5" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('masalahkhususpayudara', $existing_data) ?></textarea>

                        </div>
                    </div>

                    <!-- Bagian Abdomen -->

                    <div class="row mb-3">
                        <label class="col-sm-9 col-form-label text-primary">
                            <strong>Abdomen</strong>
                        </label>
                    </div>


                    <!-- Linea Nigra -->
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-2 col-form-label"><strong>Linea Nigra</strong></label>
                        <div class="col-sm-3">
                            <select class="form-select" name="lineanigra" <?= $ro_select ?>>
                                <option value="">Pilih</option>
                                <option value="Ya" <?= val('lineanigra', $existing_data) === 'Ya' ? 'selected' : '' ?>>Ya</option>
                                <option value="Tidak" <?= val('lineanigra', $existing_data) === 'Tidak' ? 'selected' : '' ?>>Tidak</option>
                            </select>
                        </div>

                        <!-- Linea Alba -->
                        <label class="col-sm-2 col-form-label"><strong>Linea Alba</strong></label>
                        <div class="col-sm-3">
                            <select class="form-select" name="inspeksikontraksi1" <?= $ro_select ?>>
                                <option value="">Pilih</option>
                                <option value="Ya" <?= val('inspeksikontraksi1', $existing_data) === 'Ya' ? 'selected' : '' ?>>Ya</option>
                                <option value="Tidak" <?= val('inspeksikontraksi1', $existing_data) === 'Tidak' ? 'selected' : '' ?>>Tidak</option>
                            </select>
                        </div>

                    </div>

                    <!-- Keadaan Dinding Perut -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Keadaan Dinding Perut</strong></label>

                        <div class="col-sm-9">
                            <textarea name="dindingperut" class="form-control" rows="5" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('dindingperut', $existing_data) ?></textarea>

                        </div>
                    </div>


                    <!-- TFU -->
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-2 col-form-label"><strong>TFU</strong></label>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="text" class="form-control" name="inspeksitfu" value="<?= val('inspeksitfu', $existing_data) ?>" <?= $ro ?>>
                                <span class="input-group-text">cm</span>
                            </div>
                        </div>

                        <!-- Kontraksi -->
                        <label class="col-sm-2 col-form-label"><strong>Kontraksi</strong></label>
                        <div class="col-sm-3">
                            <select class="form-select" name="inspeksikontraksi" <?= $ro_select ?>>
                                <option value="">Pilih</option>
                                <option value="Ya" <?= val('inspeksikontraksi1', $existing_data) === 'Ya' ? 'selected' : '' ?>>Ya</option>
                                <option value="Tidak" <?= val('inspeksikontraksi1', $existing_data) === 'Tidak' ? 'selected' : '' ?>>Tidak</option>
                            </select>
                        </div>
                    </div>

                    <!-- Leopold I -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Leopold I</strong></label>

                        <div class="col-sm-9">
                            <select class="form-select" name="leopoldi" <?= $ro_select ?>>
                                <option value="">Pilih</option>
                                <option value="Kepala" <?= val('leopoldi', $existing_data) === 'Kepala' ? 'selected' : '' ?>>Kepala</option>
                                <option value="Bokong" <?= val('leopoldi', $existing_data) === 'Bokong' ? 'selected' : '' ?>>Bokong</option>
                                <option value="Kosong" <?= val('leopoldi', $existing_data) === 'Kosong' ? 'selected' : '' ?>>Kosong</option>
                            </select>
                        </div>
                    </div>

                    <!-- Leopold II -->

                    <div class="row mb-2">
                        <label class="col-sm-2 col-form-label">
                            <strong>Leopold II</strong>
                    </div>

                    <!-- Kanan -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Kanan</strong></label>

                        <div class="col-sm-9">
                            <select class="form-select" name="kanan" <?= $ro_select ?>>
                                <option value="">Pilih</option>
                                <option value="Punggung" <?= val('kanan', $existing_data) === 'Punggung' ? 'selected' : '' ?>>Punggung</option>
                                <option value="Bagian Kecil" <?= val('kanan', $existing_data) === 'Bagian Kecil' ? 'selected' : '' ?>>Bagian Kecil</option>
                                <option value="Kepala" <?= val('kanan', $existing_data) === 'Kepala' ? 'selected' : '' ?>>Kepala</option>
                            </select>

                        </div>
                    </div>

                    <!-- Kiri -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Kiri</strong></label>

                        <div class="col-sm-9">
                            <select class="form-select" name="kiri" <?= $ro_select ?>>
                                <option value="">Pilih</option>
                                <option value="Punggung" <?= val('kiri', $existing_data) === 'Punggung' ? 'selected' : '' ?>>Punggung</option>
                                <option value="Bagian Kecil" <?= val('kiri', $existing_data) === 'Bagian Kecil' ? 'selected' : '' ?>>Bagian Kecil</option>
                                <option value="Kepala" <?= val('kiri', $existing_data) === 'Kepala' ? 'selected' : '' ?>>Kepala</option>
                            </select>

                        </div>
                    </div>

                    <!-- Leopold III -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Leopold III</strong></label>

                        <div class="col-sm-9">
                            <select class="form-select" name="leopoldiii" <?= $ro_select ?>>
                                <option value="">Pilih</option>
                                <option value="Kepala" <?= val('leopoldiii', $existing_data) === 'Kepala' ? 'selected' : '' ?>>Kepala</option>
                                <option value="Bokong" <?= val('leopoldiii', $existing_data) === 'Bokong' ? 'selected' : '' ?>>Bokong</option>
                                <option value="Kosong" <?= val('leopoldiii', $existing_data) === 'Kosong' ? 'selected' : '' ?>>Kosong</option>
                            </select>

                        </div>
                    </div>

                    <!-- Leopold IV Penurunan Kepala-->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Leopold IV Penurunan Kepala</strong></label>

                        <div class="col-sm-9">
                            <select class="form-select" name="leopoldiv" <?= $ro_select ?>>
                                <option value="">Pilih</option>
                                <option value="Sudah" <?= val('leopoldiv', $existing_data) === 'Sudah' ? 'selected' : '' ?>>Sudah</option>
                                <option value="Belum" <?= val('leopoldiv', $existing_data) === 'Belum' ? 'selected' : '' ?>>Belum</option>
                            </select>

                        </div>
                    </div>


                    <!-- Pemeriksaan DJJ -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Pemeriksaan DJJ</strong></label>

                        <div class="col-sm-9">
                            <div class="input-group">
                                <input type="text" class="form-control" name="pemeriksaandjj" value="<?= val('pemeriksaandjj', $existing_data) ?>" <?= $ro ?>>
                                <span class="input-group-text">Frek</span>
                            </div>
                            <small class="form-text" style="color: red;">(Normal 120-160/bradikardi, 160-180/tachikardi < 120) </small>

                        </div>
                    </div>

                    <!-- Intensitas -->
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-2 col-form-label"><strong>Intensitas</strong></label>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="text" class="form-control" name="intensitas" value="<?= val('intensitas', $existing_data) ?>" <?= $ro ?>>
                                <span class="input-group-text">Intensitas</span>
                            </div>
                        </div>

                        <!-- Keteraturan -->
                        <label class="col-sm-2 col-form-label"><strong>Keteraturan</strong></label>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="text" class="form-control" name="keteraturan" value="<?= val('keteraturan', $existing_data) ?>" <?= $ro ?>>
                                <span class="input-group-text">Keteraturan</span>
                            </div>
                        </div>
                    </div>

                    <!-- Pigmentasi -->

                    <div class="row mb-2">
                        <label class="col-sm-2 col-form-label">
                            <strong>Pigmentasi</strong>
                    </div>

                    <!-- Linea Nigra -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Linea Nigra</strong></label>

                        <div class="col-sm-9">
                            <select class="form-select" name="linea_nigra1" <?= $ro_select ?>>
                                <option value="">Pilih</option>
                                <option value="Ada" <?= val('linea_nigra1', $existing_data) === 'Ada' ? 'selected' : '' ?>>Ada</option>
                                <option value="Tidak" <?= val('linea_nigra1', $existing_data) === 'Tidak' ? 'selected' : '' ?>>Tidak</option>
                            </select>

                        </div>
                    </div>



                    <!-- Bagian Perineum dan Genetalia -->

                    <div class="row mb-2">
                        <label class="col-sm-8 col-form-label text-primary">
                            <strong>Vagina dan Perineum</strong>
                    </div>

                    <!-- Keputihan -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Keputihan</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Keputihan (Ya/Tidak): warna, konsistensi, bau, dan gatal. Hasil:</small>
                            <textarea name="inspeksikeputihan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('inspeksi_keputihan', $existing_data) ?></textarea></textarea>

                        </div>
                    </div>

                    <!-- Hemoroid -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Hemoroid</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">hemoroid (Ya/Tidak). Jika Ya sebutkan (derajat, sudah berapa lama nyeri?). Hasil:</small>
                            <textarea name="inspeksihemoroid" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('inspeksi_hemoroid', $existing_data) ?></textarea></textarea>

                        </div>
                    </div>

                    <!-- Masalah Khusus -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Masalah Khusus</strong></label>

                        <div class="col-sm-9">
                            <textarea name="masalahkhususperineum" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('masalah_khusus_perineum', $existing_data) ?></textarea>

                        </div>
                    </div>

                    <!-- Bagian Ekstremitas -->

                    <div class="row mb-2">
                        <label class="col-sm-8 col-form-label text-primary">
                            <strong>Ekstremitas</strong>
                    </div>

                    <!-- Inspeksi Ekstremitas Bawah -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Ekstremitas Bawah</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Apakah terdapat edema (Ya/Tidak), Varises (Ya/Tidak). Hasil:</small>
                            <textarea name="inspeksiekstremitasbawah" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('inspeksi_ekstremitas_bawah', $existing_data) ?></textarea></textarea>

                        </div>
                    </div>

                    <!-- Bagian Istirahat dan Kenyamanan -->

                    <div class="row mb-2">
                        <label class="col-sm-6 col-form-label text-primary">
                            <strong>Istirahat dan Kenyamanan</strong>
                    </div>

                    <!-- Inspeksi Istirahat -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Pola Tidur Saat Ini</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Kebiasaan tidur, lama dalam hitungan jam, frekuensi. Hasil:</small>
                            <textarea name="inspeksiistirahat" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('inspeksi_istirahat', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- Inspeksi Kenyamanan -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Keluhan ketidaknyamanan (Ya/Tidak), lokasi. Hasil:</small>
                            <textarea name="inspeksikenyamanan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('inspeksi_kenyamanan', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- Masalah Khusus -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Masalah Khusus</strong></label>

                        <div class="col-sm-9">
                            <textarea name="masalahkhususistirahat" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('masalah_khusus_istirahat', $existing_data) ?></textarea>

                        </div>
                    </div>

                    <!-- Bagian Mobilisasi dan Latihan -->

                    <div class="row mb-2">
                        <label class="col-sm-6 col-form-label text-primary">
                            <strong>Mobilisasi dan Latihan</strong>
                    </div>

                    <!-- Inspeksi -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Tingkat Mobilisasi</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Apakah mandiri, parsial, total. Hasil:</small>
                            <textarea name="inspeksimobilisasi" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('inspeksi_mobilisasi', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- Masalah Khusus -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Masalah Khusus</strong></label>

                        <div class="col-sm-9">
                            <textarea name="masalahkhususmobilisasi" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('masalah_khusus_mobilisasi', $existing_data) ?></textarea>

                        </div>
                    </div>

                    <!-- Bagian Pola Nutrisi dan Cairan -->

                    <div class="row mb-2">
                        <label class="col-sm-6 col-form-label text-primary">
                            <strong>Pola Nutrisi dan Cairan</strong>
                    </div>

                    <!-- Inspeksi Nutrisi -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Pola Nutrisi dan Cairan</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Asupan nutrisi (nafsu makan: baik, kurang atau tidak nafsu makan). Hasil:</small>
                            <textarea name="inspeksinutrisi" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('inspeksi_nutrisi', $existing_data) ?></textarea>

                        </div>
                    </div>

                    <!-- Inspeksi Cairan -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Asupan cairan (cukup/kurang). Hasil:</small>
                            <textarea name="inspeksicairan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('inspeksi_cairan', $existing_data) ?></textarea>

                        </div>
                    </div>

                    <!-- Inspeksi Pantangan Makan -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Pantangan Makan. Hasil:</small>
                            <textarea name="inspeksipantangan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('inspeksi_pantangan', $existing_data) ?></textarea>

                        </div>
                    </div>

                    <!-- Masalah Khusus -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Masalah Khusus</strong></label>

                        <div class="col-sm-9">
                            <textarea name="masalahkhususpolanutrisi" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('masalah_khusus_polanutrisi', $existing_data) ?></textarea>

                        </div>
                    </div>

                    <!-- Bagian Pengetahuan -->

                    <div class="row mb-2">
                        <label class="col-sm-2 col-form-label text-primary">
                            <strong>Pengetahuan</strong>
                    </div>

                    <!-- Inspeksi Tanda Melahirkan -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Apakah mengetahui tanda-tanda melahirkan?</strong></label>

                        <div class="col-sm-9">
                            <textarea name="tandamelahirkan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('tanda_melahirkan', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- Inspeksi Nyeri Melahirkan -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Apakah mengetahui cara menangani nyeri saat melahirkan?</strong></label>

                        <div class="col-sm-9">
                            <textarea name="nyerimelahirkan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('nyeri_melahirkan', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- Inspeksi Persalinan -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Cara mengejan saat persalinan?</strong></label>

                        <div class="col-sm-9">
                            <textarea name="persalinan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('persalinan', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- Inspeksi Asi dan Payudara -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Apakah mengetahuai manfaat ASI dan cara perawatan payudara?</strong></label>

                        <div class="col-sm-9">
                            <textarea name="asidanpayudara" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('asi_dan_payudara', $existing_data) ?></textarea>
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
            </div>
        </div>

        </form>
        </div>
        </div>

        <?php include dirname(__DIR__, 2) . '/partials/footer_form.php'; ?>

    </section>
</main>