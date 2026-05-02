<?php
require_once "koneksi.php";
require_once "utils.php";
$form_id       = 3;
$level         = $_SESSION['level'];
$user_id       = $_SESSION['id_user'];
$section_name  = 'pemeriksaan_fisik3';
$section_label = 'Pemeriksaan Fisik3';

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
        'inspeksi_istirahat'            => $_POST['inspeksiistirahat'] ?? '',
        'inspeksi_kenyamanan'           => $_POST['inspeksikenyamanan'] ?? '',
        'masalah_khusus_istirahat'       => $_POST['masalahkhususistirahat'] ?? '',
        'inspeksi_mobilisasi'           => $_POST['inspeksimobilisasi'] ?? '',
        'masalah_khusus_mobilisasi'      => $_POST['masalahkhususmobilisasi'] ?? '',
        'jenis_makanan'                 => $_POST['jenismakanan'] ?? '',
        'frekuensi'                    => $_POST['frekuensi'] ?? '',
        'konsumsi_snack'                => $_POST['konsumsisnack'] ?? '',
        'nafsu_makan'                   => $_POST['nafsumakan'] ?? '',
        'polaminum'                    => $_POST['polaminum'] ?? '',
        'frekuensi2'                   => $_POST['frekuensi2'] ?? '',
        'pantangan_makanan'             => $_POST['pantanganmakanan'] ?? '',
        'kemampuan_menyusui'            => $_POST['kemampuanmenyusui'] ?? '',
        'kemampuan_menyusui1'           => $_POST['kemampuanmenyusui1'] ?? '',
        'posisi_menyusui'               => $_POST['posisimenyusui'] ?? '',
        'posisi_menyusui1'               => $_POST['posisimenyusui1'] ?? '',
        'penyimpanan_asi'               => $_POST['penyimpananasi'] ?? '',
        'penyimpanan_asi1'               => $_POST['penyimpananasi1'] ?? '',
        'perawatan_payudara'            => $_POST['perawatanpayudara'] ?? '',
        'perawatan_payudara1'             => $_POST['perawatanpayudara1'] ?? '',
        'produksi_asi'                  => $_POST['produksiasi'] ?? '',
        'produksi_asi1'                  => $_POST['produksiasi1'] ?? '',
        'jenis_kb'                      => $_POST['jeniskb'] ?? '',
        'pengetahuan_kb'                => $_POST['pengetahuankb'] ?? '',
        'rencana_penggunaan_kb'          => $_POST['rencanapenggunaankb'] ?? '',
        'jenis_obat'                    => $_POST['jenisobat'] ?? '',
        'dosis'                        => $_POST['dosis'] ?? '',
        'kegunaan'                     => $_POST['kegunaan'] ?? '',
        'cara_pemberian'                => $_POST['carapemberian'] ?? '',
        'pemeriksaan'                  => $_POST['pemeriksaan'] ?? '',
        'hasil'                        => $_POST['hasil'] ?? '',
        'nilai_normal'                  => $_POST['nilainormal'] ?? '',
        'data_subjektif'                => $_POST['datasubjektif'] ?? '',
        'data_objektif'                 => $_POST['dataobjektif'] ?? '',
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

                        <!-- Jenis Makanan -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Jenis Makanan</strong></label>

                            <div class="col-sm-9">
                                <textarea name="jenismakanan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                    <?= $ro ?>><?= val('jenis_makanan', $existing_data) ?></textarea>

                            </div>
                        </div>

                        <!-- Frekuensi -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Frekuensi</strong></label>

                            <div class="col-sm-9">
                                <textarea name="frekuensi" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                    <?= $ro ?>><?= val('frekuensi', $existing_data) ?></textarea>


                            </div>
                        </div>

                        <!-- Konsumsi Snack -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Konsumsi Snack</strong></label>

                            <div class="col-sm-9">
                                <textarea name="konsumsisnack" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                    <?= $ro ?>><?= val('konsumsi_snack', $existing_data) ?></textarea>

                            </div>
                        </div>

                        <!-- Nafsu Makan -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Nafsu Makan</strong></label>

                            <div class="col-sm-9">
                                <textarea name="nafsumakan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                    <?= $ro ?>><?= val('nafsu_makan', $existing_data) ?></textarea>


                            </div>
                        </div>

                        <!-- Pola Minum -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Pola Minum</strong></label>

                            <div class="col-sm-9">
                                <textarea name="polaminum" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                    <?= $ro ?>><?= val('polaminum', $existing_data) ?></textarea></textarea>

                            </div>
                        </div>

                        <!-- Frekuensi -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Frekuensi</strong></label>

                            <div class="col-sm-9">
                                <textarea name="frekuensi2" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                    <?= $ro ?>><?= val('frekuensi2', $existing_data) ?></textarea>


                            </div>
                        </div>

                        <!-- Pantangan Makanan -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Pantangan Makanan</strong></label>

                            <div class="col-sm-9">
                                <textarea name="pantanganmakanan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                    <?= $ro ?>><?= val('pantangan_makanan', $existing_data) ?></textarea>


                            </div>
                        </div>

                        <!-- Pengetahuan Menyusui -->

                        <div class="row mb-2">
                            <label class="col-sm-8 col-form-label text-primary">
                                <strong>Pengetahuan Menyusui</strong>
                        </div>

                        <!-- Kemampuan Menyusui -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Kemampuan Menyusui</strong></label>

                            <div class="col-sm-9">
                                <small class="form-text" style="color: red;">Ya/Tidak</small>
                                <select class="form-select" name="kemampuanmenyusui" <?= $ro_select ?>>
                                    <option value="ada" <?= val('kemampuan_menyusui', $existing_data) === 'ada' ? 'selected' : '' ?>>Ada</option>
                                    <option value="tidak" <?= val('kemampuan_menyusui', $existing_data) === 'tidak' ? 'selected' : '' ?>>Tidak</option>
                                </select>

                                <small class="form-text" style="color: red;">Hasil:</small>
                                <textarea name="kemampuanmenyusui1" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                    <?= $ro ?>><?= val('kemampuan_menyusui1', $existing_data) ?></textarea>


                            </div>

                            <!-- Posisi Menyusui -->
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label"><strong>Mengetahui Posisi Menyusui yang Benar</strong></label>

                                <div class="col-sm-9">
                                    <small class="form-text" style="color: red;">Ya/Tidak</small>
                                    <select class="form-select" name="posisimenyusui" <?= $ro_select ?>>
                                        <option value="ada" <?= val('posisi_menyusui', $existing_data) === 'ada' ? 'selected' : '' ?>>Ada</option>
                                        <option value="tidak" <?= val('posisi_menyusui', $existing_data) === 'tidak' ? 'selected' : '' ?>>Tidak</option>
                                    </select>

                                    <small class="form-text" style="color: red;">Hasil:</small>
                                    <textarea name="posisimenyusui1" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                        <?= $ro ?>><?= val('posisi_menyusui1', $existing_data) ?></textarea>


                                </div>
                            </div>

                            <!-- Penyimpanan Asi -->
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label"><strong>Mengetahui Cara Penyimpanan ASI</strong></label>

                                <div class="col-sm-9">
                                    <small class="form-text" style="color: red;">Ya/Tidak</small>
                                    <select class="form-select" name="penyimpananasi" <?= $ro_select ?>>
                                        <option value="ada" <?= val('penyimpanan_asi', $existing_data) === 'ada' ? 'selected' : '' ?>>Ada</option>
                                        <option value="tidak" <?= val('penyimpanan_asi', $existing_data) === 'tidak' ? 'selected' : '' ?>>Tidak</option>
                                    </select>

                                    <small class="form-text" style="color: red;">Hasil:</small>
                                    <textarea name="penyimpananasi1" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                        <?= $ro ?>><?= val('penyimpanan_asi1', $existing_data) ?></textarea>

                                </div>
                            </div>

                            <!-- Perawatan Payudara -->
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label"><strong>Mengetahui Perawatan Payudara</strong></label>

                                <div class="col-sm-9">
                                    <small class="form-text" style="color: red;">Ya/Tidak</small>
                                    <select class="form-select" name="perawatanpayudara" <?= $ro_select ?>>
                                        <option value="ada" <?= val('perawatan_payudara', $existing_data) === 'ada' ? 'selected' : '' ?>>Ada</option>
                                        <option value="tidak" <?= val('perawatan_payudara', $existing_data) === 'tidak' ? 'selected' : '' ?>>Tidak</option>
                                    </select>

                                    <small class="form-text" style="color: red;">Hasil:</small>
                                    <textarea name="perawatanpayudara1" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                        <?= $ro ?>><?= val('perawatan_payudara1', $existing_data) ?></textarea>

                                </div>
                            </div>

                            <!-- Produksi ASI -->
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label"><strong>Mengetahui Cara Meningkatkan Produksi ASI</strong></label>

                                <div class="col-sm-9">
                                    <small class="form-text" style="color: red;">Ya/Tidak</small>
                                    <select class="form-select" name="produksiasi" <?= $ro_select ?>>
                                        <option value="ada" <?= val('produksi_asi', $existing_data) === 'ada' ? 'selected' : '' ?>>Ada</option>
                                        <option value="tidak" <?= val('produksi_asi', $existing_data) === 'tidak' ? 'selected' : '' ?>>Tidak</option>
                                    </select>

                                    <small class="form-text" style="color: red;">Hasil:</small>
                                    <textarea name="produksiasi1" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                        <?= $ro ?>><?= val('produksi_asi1', $existing_data) ?></textarea>

                                </div>
                            </div>

                            <!-- Kontrasepsi -->

                            <div class="row mb-2">
                                <label class="col-sm-8 col-form-label text-primary">
                                    <strong>Kontrasepsi (KB)</strong>
                            </div>

                            <!-- Jenis KB -->
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label"><strong>Kontrasepsi (KB)</strong></label>

                                <div class="col-sm-9">
                                    <small class="form-text" style="color: red;">Pernah menggunakan kontrasepsi dan jenis kontrasepsi yang pernah digunakan. Hasil:</small>
                                    <textarea name="jeniskb" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                        <?= $ro ?>><?= val('jenis_kb', $existing_data) ?></textarea>

                                </div>
                            </div>

                            <!-- Pengetahuan KB -->
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label"></label>

                                <div class="col-sm-9">
                                    <small class="form-text" style="color: red;">Pengetahuan tentang kontrasepsi (jenis, manfaat, kelebihan, dan kekurangan). Hasil:</small>
                                    <textarea name="pengetahuankb" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                        <?= $ro ?>><?= val('pengetahuan_kb', $existing_data) ?></textarea>

                                </div>
                            </div>

                            <!-- Rencana Penggunakan KB -->
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label"></label>

                                <div class="col-sm-9">
                                    <small class="form-text" style="color: red;">Rencana penggunaan kontrasepsi. Hasil:</small>
                                    <small class="form-text" style="color: red;">Ya/Tidak</small>
                                    <select class="form-select" name="rencanapenggunaankb" <?= $ro_select ?>>

                                        <option value="ya" <?= val('rencana_penggunaan_kb', $existing_data) === 'ya' ? 'selected' : '' ?>>Ya</option>
                                        <option value="tidak" <?= val('rencana_penggunaan_kb', $existing_data) === 'tidak' ? 'selected' : '' ?>>Tidak</option>
                                    </select>


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
                            </div>

        <?php include dirname(__DIR__, 2) . '/partials/footer_form.php'; ?>

    </section>
</main>