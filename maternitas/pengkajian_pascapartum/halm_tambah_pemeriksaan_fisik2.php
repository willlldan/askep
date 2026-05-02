<?php
require_once "koneksi.php";
require_once "utils.php";
$form_id       = 3;
$level         = $_SESSION['level'];
$user_id       = $_SESSION['id_user'];
$section_name  = 'pemeriksaan_fisik2';
$section_label = 'Pemeriksaan Fisik2';

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
        'bunyinapas'                => $_POST['bunyinapas'] ?? '',
        'suarajantung'              => $_POST['suarajantung'] ?? '',
        'masalah_khusus_dada'       => $_POST['masalahkhususdada'] ?? '',
        'inspeksi_bentuk'           => $_POST['inspeksibentuk'] ?? '',
        'inspeksi_colostum'         => $_POST['inspeksicolostum'] ?? '',
        'inspeksi_puting'           => $_POST['inspeksiputing'] ?? '',
        'inspeksi_pembengkakan'     => $_POST['inspeksipembengkakan'] ?? '',
        'palpasi_raba'              => $_POST['palpasiraba'] ?? '',
        'palpasi_benjolan'          => $_POST['palpasibenjolan'] ?? '',
        'masalah'                   => $_POST['masalah'] ?? '',
        'inspeksiabdomen'           => $_POST['inspeksiabdomen'] ?? '',
        'auskultasi_bising_usus'    => $_POST['auskultasibisingusus'] ?? '',
        'perkusi'                   => $_POST['perkusi'] ?? '',
        'palpasi_involusi'          => $_POST['palpasiinvolusi'] ?? '',
        'palpasi_kandung_kemih'     => $_POST['palpasikandungkemih'] ?? '',
        'masalah_khusus_abdomen'    => $_POST['masalahkhususabdomen'] ?? '',
        'vagina'                    => $_POST['vagina'] ?? '',
        'perineum'                  => $_POST['perineum'] ?? '',
        'redness'                   => $_POST['redness'] ?? '',
        'edema'                     => $_POST['edema'] ?? '',
        'echimosis'                 => $_POST['echimosis'] ?? '',
        'discharge'                 => $_POST['discharge'] ?? '',
        'aprroximate'               => $_POST['aprroximate'] ?? '',
        'lochea'                    => $_POST['lochea'] ?? '',
        'jumlah'                    => $_POST['jumlah'] ?? '',
        'jenis_warna'               => $_POST['jeniswarna'] ?? '',
        'konsistensi'               => $_POST['konsistensi'] ?? '',
        'bau'                       => $_POST['bau'] ?? '',
        'hemorrhoid'                => $_POST['hemorrhoid'] ?? '',
        'data_tambahan'             => $_POST['datatambahan'] ?? '',
        'inspeksi_ekstremitas_atas' => $_POST['inspeksiekstremitasatas'] ?? '',
        'inspeksi_ekstremitas_bawah' => $_POST['inspeksiekstremitasbawah'] ?? '',
        'masalah_khusus_eksremitas' => $_POST['masalahkhususekstremitas'] ?? '',
        'inspeksi_integumen'        => $_POST['inspeksiintegumen'] ?? '',
        'palpasi_integumen'         => $_POST['palpasiintegumen'] ?? '',
        'inspeksi_bak'              => $_POST['inspeksibak'] ?? '',
        'inspeksi_bab'              => $_POST['inspeksibab'] ?? '',
        'masalah_khusus_eliminasi'  => $_POST['masalahkhususeliminasi'] ?? '',
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
    <?php include "tab.php"; ?>
    <section class="section dashboard">

        <?php include dirname(__DIR__, 2) . '/partials/notifikasi.php'; ?>
        <?php include dirname(__DIR__, 2) . '/partials/status_section.php'; ?>

        <div class="card">
            <div class="card">
                <div class="card-body">

                    <h5 class="card-title mb-1"><strong>Pengkajian</strong></h5>
                    <form class="needs-validation" novalidate action="" method="POST">
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
                                    <?= $ro ?>><?= val('bunyinapas', $existing_data) ?></textarea>

                            </div>
                        </div>

                        <!-- Auskultasi Suara Jantung -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"></label>

                            <div class="col-sm-9">
                                <small class="form-text" style="color: red;">Suara Jantung (Apakah ada mur-mur dan gallop). Hasil:</small>
                                <textarea name="suarajantung" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                    <?= $ro ?>><?= val('suarajantung', $existing_data) ?></textarea>


                            </div>
                        </div>

                        <!-- Masalah Khusus -->
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

                        <!-- Inspeksi Bentuk-->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Inspeksi Bentuk</strong></label>

                            <div class="col-sm-9">
                                <small class="form-text" style="color: red;">Bentuk, Lesi, Kebersihan. Hasil:</small>
                                <textarea name="inspeksibentuk" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                    <?= $ro ?>><?= val('inspeksi_bentuk', $existing_data) ?></textarea>

                            </div>
                        </div>

                        <!-- Inspeksi Colostum-->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Inspeksi Colostum dan ASI</strong></label>

                            <div class="col-sm-9">
                                <small class="form-text" style="color: red;">Colostim dan ASI (Ada atau tidak). Hasil:</small>
                                <textarea name="inspeksicolostum" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                    <?= $ro ?>><?= val('inspeksi_colostum', $existing_data) ?></textarea>


                            </div>
                        </div>

                        <!-- Inspeksi Puting -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Inspeksi Puting</strong></label>

                            <div class="col-sm-9">
                                <small class="form-text" style="color: red;">Eksverted/Inverted/Plat nipple. Hasil:</small>
                                <textarea name="inspeksiputing" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                    <?= $ro ?>><?= val('inspeksi_puting', $existing_data) ?></textarea>


                            </div>
                        </div>

                        <!-- Inspeksi Tanda Pembengkakan -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Inspeksi Tanda Pembengkakan</strong></label>

                            <div class="col-sm-9">
                                <small class="form-text" style="color: red;">Tanda Pembengkakan: Ya/Tidak. Hasil:</small>
                                <textarea name="inspeksipembengkakan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                    <?= $ro ?>><?= val('inspeksi_pembengkakan', $existing_data) ?></textarea>


                            </div>
                        </div>

                        <!-- Palpasi Raba -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Palpasi Raba</strong></label>

                            <div class="col-sm-9">
                                <small class="form-text" style="color: red;">Teraba hangat: Ya/Tidak. Hasil:</small>
                                <select class="form-select" name="palpasiraba" required <?= $ro ?>>
                                    <option value="Ya" <?= val('palpasi_raba', $existing_data) === 'ya' ? 'selected' : '' ?>>Ya</option>
                                    <option value="Tidak" <?= val('palpasi_raba', $existing_data) === 'Tidak' ? 'selected' : '' ?>>Tidak</option>
                                </select>


                            </div>
                        </div>

                        <!-- Palpasi Benjolan -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Palpasi Benjolan</strong></label>

                            <div class="col-sm-9">
                                <small class="form-text" style="color: red;">Ada/Tidak Ada. Hasil:</small>
                                <select class="form-select" name="palpasibenjolan" required <?= $ro ?>>
                                    <option value="Ada" <?= val('palpasi_benjolan', $existing_data) === 'Ada' ? 'selected' : '' ?>>Ada</option>
                                    <option value="Tidak Ada" <?= val('palpasi_benjolan', $existing_data) === 'Tidak Ada' ? 'selected' : '' ?>>Tidak Ada</option>
                                </select>

                            </div>
                        </div>

                        <!-- Masalah Khusus -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Masalah Khusus</strong></label>

                            <div class="col-sm-9">
                                <textarea name="masalah" class="form-control" rows="5" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                    <?= $ro ?>><?= val('masalah', $existing_data) ?></textarea>
                            </div>
                        </div>

                        <!-- Bagian Abdomen -->

                        <div class="row mb-3">
                            <label class="col-sm-9 col-form-label text-primary">
                                <strong>Abdomen</strong>
                            </label>
                        </div>

                        <!-- Inspeksi -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Inspeksi</strong></label>

                            <div class="col-sm-9">
                                <small class="form-text" style="color: red;">Bentuk, Warna Kulit, Jaringan Perut (ada/tidak), Strie (ada/tidak), Luka (ada/tidak). Hasil:</small>
                                <textarea name="inspeksiabdomen" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                    <?= $ro ?>><?= val('inspeksiabdomen', $existing_data) ?></textarea>


                            </div>
                        </div>

                        <!-- Auskultasi -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Auskultasi</strong></label>

                            <div class="col-sm-9">
                                <small class="form-text" style="color: red;">Bising Usus. Hasil:</small>
                                <textarea name="auskultasibisingusus" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                    <?= $ro ?>><?= val('auskultasi_bising_usus', $existing_data) ?></textarea>


                            </div>
                        </div>

                        <!-- Perkusi -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Perkusi</strong></label>

                            <div class="col-sm-9">
                                <small class="form-text" style="color: red;">Bunyi (Pekak, redup, sonor, hipersonor, timpani). Hasil:</small>
                                <textarea name="perkusi" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                    <?= $ro ?>><?= val('perkusi', $existing_data) ?></textarea>


                            </div>
                        </div>

                        <!-- Palpasi -->
                        <!-- Palpasi Involusi Uterus -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Palpasi</strong></label>

                            <div class="col-sm-9">
                                <small class="form-text" style="color: red;">Involusi Uterus: Tinggi Fundus dan Kontraksi. Hasil:</small>
                                <textarea name="palpasiinvolusi" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                    <?= $ro ?>><?= val('palpasi_involusi', $existing_data) ?></textarea></textarea>


                            </div>
                        </div>

                        <!-- Palpasi Kandung Kemih -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"></label>

                            <div class="col-sm-9">
                                <small class="form-text" style="color: red;">Kandung Kemih: teraba/tidak, penuh/tidak. Hasil:</small>
                                <textarea name="palpasikandungkemih" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                    <?= $ro ?>><?= val('palpasi_kandung_kemih', $existing_data) ?></textarea></textarea>


                            </div>
                        </div>

                        <!-- Masalah Khusus -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Masalah Khusus</strong></label>

                            <div class="col-sm-9">
                                <textarea name="masalahkhususabdomen" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                    <?= $ro ?>><?= val('masalah_khusus_abdomen', $existing_data) ?></textarea></textarea>


                            </div>
                        </div>

                        <!-- Bagian Perineum dan Genetalia -->

                        <div class="row mb-2">
                            <label class="col-sm-8 col-form-label text-primary">
                                <strong>Perineum dan Genital</strong>
                        </div>

                        <!-- Inspeksi Vagina-->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Vagina</strong></label>

                            <div class="col-sm-9">
                                <small class="form-text" style="color: red;">Vagina: Integritas kulit, edema (ya/tidak), memar (ya/tidak), dan hematom (ya/tidak). Hasil:</small>
                                <textarea name="vagina" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                    <?= $ro ?>><?= val('vagina', $existing_data) ?></textarea>

                            </div>
                        </div>

                        <!-- Perineum -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"></label>

                            <div class="col-sm-9">
                                <small class="form-text" style="color: red;">Perineum: Utuh/Episiotomi/Ruptur. Hasil:</small>
                                <textarea name="perineum" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                    <?= $ro ?>><?= val('perineum', $existing_data) ?></textarea>
                            </div>
                        </div>

                        <!-- Tanda REEDA R-->

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Tanda REEDA</strong></label>

                            <div class="col-sm-9">
                                <small class="form-text" style="color: red;">R: Redness Kemerahan</small>
                                <select class="form-select" name="redness" required<?= $ro_select ?>>
                                    <option value="Ya" <?= val('redness', $existing_data) === 'ya' ? 'selected' : '' ?>>Ya</option>
                                    <option value="Tidak" <?= val('redness', $existing_data) === 'tidak' ? 'selected' : '' ?>>Tidak</option>
                                </select>


                            </div>
                        </div>

                        <!-- E -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"></label>

                            <div class="col-sm-9">
                                <small class="form-text" style="color: red;">E: Edema</small>
                                <select class="form-select" name="edema" required<?= $ro_select ?>>
                                    <option value="Ya" <?= val('edema', $existing_data) === 'ya' ? 'selected' : '' ?>>Ya</option>
                                    <option value="Tidak" <?= val('edema', $existing_data) === 'tidak' ? 'selected' : '' ?>>Tidak</option>
                                </select>


                            </div>
                        </div>

                        <!-- E -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"></label>

                            <div class="col-sm-9">
                                <small class="form-text" style="color: red;">E: Echimosis</small>
                                <select class="form-select" name="echimosis" required <?= $ro_select ?>>
                                    <option value="Ya" <?= val('echimosis', $existing_data) === 'ya' ? 'selected' : '' ?>>Ya</option>
                                    <option value="Tidak" <?= val('echimosis', $existing_data) === 'tidak' ? 'selected' : '' ?>>Tidak</option>
                                </select>
                            </div>
                        </div>



                        <!-- D -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"></label>

                            <div class="col-sm-9">
                                <small class="form-text" style="color: red;">D: Discharge/Pelepasan</small>
                                <select class="form-select" name="discharge" required <?= $ro_select ?>>
                                    <option value="" <?= val('discharge', $existing_data) === 'Serum' ? 'selected' : '' ?>>Serum</option>
                                    <option value="Pus" <?= val('discharge', $existing_data) === 'Pus' ? 'selected' : '' ?>>Pus</option>
                                    <option value="Darah" <?= val('discharge', $existing_data) === 'Darah' ? 'selected' : '' ?>>Darah</option>
                                    <option value="Tidak Ada" <?= val('discharge', $existing_data) === 'Tidak Ada' ? 'selected' : '' ?>>Tidak Ada</option>
                                </select>
                            </div>
                        </div>



                        <!-- A -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"></label>

                            <div class="col-sm-9">
                                <small class="form-text" style="color: red;">A: Approximate/Perkiraan</small>
                                <select class="form-select" name="aprroximate" required <?= $ro_select ?>>
                                    <option value="baik" <?= val('aprroximate', $existing_data) === 'baik' ? 'selected' : '' ?>>Baik</option>
                                    <option value="tidak" <?= val('aprroximate', $existing_data) === 'tidak' ? 'selected' : '' ?>>Tidak</option>
                                </select>
                            </div>
                        </div>

                        <!-- Pengeluaran Darah -->

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Pengeluaran Darah</strong></label>

                            <div class="col-sm-9">
                                <small class="form-text" style="color: red;">Lochea</small>
                                <textarea name="lochea" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                    <?= $ro ?>><?= val('lochea', $existing_data) ?></textarea>
                            </div>


                        </div>



                        <!-- Jumlah -->
                        <div class="row mb-3 align-items-center">
                            <label class="col-sm-2 col-form-label"></label>
                            <div class="col-sm-3">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="jumlah" value="<?= val('jumlah', $existing_data) ?>" <?= $ro ?>>
                                    <span class="input-group-text">Jumlah</span>
                                </div>
                            </div>

                            <!-- Jenis Warna -->
                            <label class="col-sm-2 col-form-label"></label>
                            <div class="col-sm-3">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="jeniswarna" value="<?= val('jenis_warna', $existing_data) ?>" <?= $ro ?>>
                                    <span class="input-group-text">Jenis Warna</span>
                                </div>
                            </div>
                        </div>

                        <!-- Konsistensi -->
                        <div class="row mb-3 align-items-center">
                            <label class="col-sm-2 col-form-label"></label>
                            <div class="col-sm-3">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="konsistensi" value="<?= val('konsistensi', $existing_data) ?>" <?= $ro ?>>
                                    <span class="input-group-text">Konsistensi</span>
                                </div>
                            </div>

                            <!-- Bau -->
                            <label class="col-sm-2 col-form-label"></label>
                            <div class="col-sm-3">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="bau" value="<?= val('bau', $existing_data) ?>" <?= $ro ?>>
                                    <span class="input-group-text">Bau</span>
                                </div>
                            </div>
                        </div>



                        <!-- Hemorrhoid -->

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Hemorrhoid</strong></label>

                            <div class="col-sm-9">
                                <select class="form-select" name="hemorrhoid" required <?= $ro_select ?>>
                                    <option value="ya" <?= val('hemorrhoid', $existing_data) === 'ya' ? 'selected' : '' ?>>Ya</option>
                                    <option value="tidak" <?= val('hemorrhoid', $existing_data) === 'tidak' ? 'selected' : '' ?>>Tidak</option>
                                </select>


                            </div>
                        </div>

                        <!-- Data Tambahan -->

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Data Tambahan</strong></label>

                            <div class="col-sm-9">
                                <textarea name="datatambahan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                    <?= $ro ?>><?= val('data_tambahan', $existing_data) ?></textarea></textarea>


                            </div>
                        </div>

                        <!-- Bagian Ekstremitas -->

                        <div class="row mb-2">
                            <label class="col-sm-8 col-form-label text-primary">
                                <strong>Ekstremitas</strong>
                        </div>

                        <!-- Inspeksi Ekstremitas Atas-->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Ekstremitas Atas</strong></label>

                            <div class="col-sm-9">
                                <small class="form-text" style="color: red;">Apakah terdapat edema (Ya/Tidak), rasa kesemutan/baal (Ya/Tidak), Kekuatan otot. Hasil:</small>
                                <textarea name="inspeksiekstremitasatas" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                    <?= $ro ?>><?= val('inspeksi_ekstremitas_atas', $existing_data) ?></textarea></textarea>


                            </div>
                        </div>

                        <!-- Inspeksi Ekstremitas Bawah -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Ekstremitas Bawah</strong></label>

                            <div class="col-sm-9">
                                <small class="form-text" style="color: red;">Apakah terdapat edema (Ya/Tidak), Varises (Ya/Tidak), Tanda
                                    Homan untuk melihat adanya tromboflebitis (+/-), Refleks Patella (+/-), apakah terdapat
                                    kekakuan sendi, dan kekuatan otot. Hasil:</small>
                                <textarea name="inspeksiekstremitasbawah" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"

                                    <?= $ro ?>><?= val('inspeksi_ekstremitas_bawah', $existing_data) ?></textarea>


                            </div>
                        </div>

                        <!-- Masalah Khusus -->

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Masalah Khusus</strong></label>

                            <div class="col-sm-9">
                                <textarea name="masalahkhususekstremitas" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('masalah_khusus_eksremitas', $existing_data) ?></textarea>


                            </div>
                        </div>

                        <!-- Bagian Integumen -->

                        <div class="row mb-2">
                            <label class="col-sm-2 col-form-label text-primary">
                                <strong>Integumen</strong>
                        </div>

                        <!-- Inspeksi -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Inspeksi</strong></label>

                            <div class="col-sm-9">
                                <small class="form-text" style="color: red;">Warna, turgor, elastisitas, ulkus. Hasil:</small>
                                <textarea name="inspeksiintegumen" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                    <?= $ro ?>><?= val('inspeksi_integumen', $existing_data) ?></textarea>


                            </div>
                        </div>

                        <!-- Palpasi -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Palpasi</strong></label>

                            <div class="col-sm-9">
                                <small class="form-text" style="color: red;">Akral, CRT, dan Nyeri. Hasil:</small>
                                <textarea name="palpasiintegumen" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                    <?= $ro ?>><?= val('palpasi_integumen', $existing_data) ?></textarea>


                            </div>
                        </div>

                        <!-- Bagian Eliminasi -->

                        <div class="row mb-2">
                            <label class="col-sm-2 col-form-label text-primary">
                                <strong>Eliminasi</strong>
                        </div>

                        <!-- Inspeksi BAK -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Urin</strong></label>

                            <div class="col-sm-9">
                                <small class="form-text" style="color: red;">BAK saat ini: nyeri (ya/tidak), frekuensi, jumlah. Hasil:</small>
                                <textarea name="inspeksibak" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                    <?= $ro ?>><?= val('inspeksi_bak', $existing_data) ?></textarea>

                            </div>
                        </div>

                        <!-- Inspeksi BAB -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"></label>

                            <div class="col-sm-9">
                                <small class="form-text" style="color: red;">BAB saat ini: Konstipasi (Ya/Tidak), Frekuensi. Hasil:</small>
                                <textarea name="inspeksibab" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                    <?= $ro ?>><?= val('inspeksi_bab', $existing_data) ?></textarea></textarea>


                            </div>
                        </div>

                        <!-- Masalah Khusus -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Masalah Khusus</strong></label>

                            <div class="col-sm-9">
                                <textarea name="masalahkhususeliminasi" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                    <?= $ro ?>><?= val('masalah_khusus_eliminasi', $existing_data) ?></textarea></textarea>


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
        </div>
        


            <?php include dirname(__DIR__, 2) . '/partials/footer_form.php'; ?>
    </section>
</main>