<?php
require_once "koneksi.php";
require_once "utils.php";

$form_id       = 6;
$level         = $_SESSION['level'];
$user_id       = $_SESSION['id_user'];
$section_name  = 'identitas_riwayat';
$section_label = 'Identitas & Riwayat';

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

    $text_fields = [
        // Header
        'no_registrasi',
        'hari_tanggal',
        'waktu_pengkajian',
        'tempat_pengkajian',
        // Identitas Klien
        'nama_klien',
        'umur',
        'jk',
        'tgl_lahir',
        'apgar',
        'bb_lahir',
        'bb_sekarang',
        'alamat',
        'usia_gestasi',
        // Identitas Ayah
        'nama_ayah',
        'usia_ayah',
        'pekerjaan_ayah',
        'alamat_ayah',
        // Identitas Ibu
        'nama_ibu',
        'usia_ibu',
        'pekerjaan_ibu',
        'status_gravida',
        'pemeriksaan_kehamilan',
        // Riwayat Kehamilan
        'status_gpa',
        'obat_kehamilan',
        'imunisasi_tt',
        'komplikasi_kehamilan',
        // Riwayat Persalinan
        'riwayat_persalinan',
        'tempat_persalinan',
        'jenis_persalinan',
        'persentasi',
        'air_ketuban',
        'lama_persalinan',
        // Tali Pusat
        'tali_pusat_panjang',
        'tali_pusat_vena',
        'tali_pusat_arteri',
        'tali_pusat_warna',
        'tali_pusat_kelainan',
    ];

    $data = [];
    foreach ($text_fields as $f) {
        $data[$f] = $_POST[$f] ?? '';
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

// Helper shorthand
function ed($key, $data)
{
    return htmlspecialchars($data[$key] ?? '');
}
?>

<main id="main" class="main">
    <?php include "anak/format_aster/tab.php"; ?>

    <section class="section dashboard">

        <?php include dirname(__DIR__, 2) . '/partials/notifikasi.php'; ?>
        <?php include dirname(__DIR__, 2) . '/partials/status_section.php'; ?>

        <form class="needs-validation" novalidate action="" method="POST">

            <!-- ===================== HEADER PENGKAJIAN ===================== -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><strong>Format Pengkajian Bayi Baru Lahir</strong></h5>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>No. Registrasi</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="no_registrasi"
                                value="<?= ed('no_registrasi', $existing_data) ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Hari / Tanggal</strong></label>
                        <div class="col-sm-9">
                            <input type="date" class="form-control" name="hari_tanggal"
                                value="<?= ed('hari_tanggal', $existing_data) ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Waktu Pengkajian</strong></label>
                        <div class="col-sm-9">
                            <input type="time" class="form-control" name="waktu_pengkajian"
                                value="<?= ed('waktu_pengkajian', $existing_data) ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Tempat Pengkajian</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="tempat_pengkajian"
                                value="<?= ed('tempat_pengkajian', $existing_data) ?>" <?= $ro ?>>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ===================== I. IDENTITAS KLIEN ===================== -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><strong>I. Identitas</strong></h5>

                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary"><strong>Identitas Klien</strong></label>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Nama</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="nama_klien"
                                value="<?= ed('nama_klien', $existing_data) ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Umur</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="umur"
                                value="<?= ed('umur', $existing_data) ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Jenis Kelamin</strong></label>
                        <div class="col-sm-9">
                            <div class="d-flex gap-4 mt-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="jk" value="Laki-laki"
                                        id="jk_laki" <?= $ro_disabled ?>
                                        <?= (ed('jk', $existing_data) === 'Laki-laki') ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="jk_laki">Laki-laki</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="jk" value="Perempuan"
                                        id="jk_perempuan" <?= $ro_disabled ?>
                                        <?= (ed('jk', $existing_data) === 'Perempuan') ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="jk_perempuan">Perempuan</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Tanggal Lahir</strong></label>
                        <div class="col-sm-9">
                            <input type="date" class="form-control" name="tgl_lahir"
                                value="<?= ed('tgl_lahir', $existing_data) ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Apgar Score</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="apgar"
                                value="<?= ed('apgar', $existing_data) ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Berat Badan Lahir</strong></label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <input type="text" class="form-control" name="bb_lahir"
                                    value="<?= ed('bb_lahir', $existing_data) ?>" <?= $ro ?>>
                                <span class="input-group-text">gram</span>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Berat Badan Saat Pengkajian</strong></label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <input type="text" class="form-control" name="bb_sekarang"
                                    value="<?= ed('bb_sekarang', $existing_data) ?>" <?= $ro ?>>
                                <span class="input-group-text">gram</span>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Alamat</strong></label>
                        <div class="col-sm-9">
                            <textarea class="form-control" name="alamat" rows="2"
                                style="overflow:hidden; resize:none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= ed('alamat', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Usia Gestasi</strong></label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <input type="text" class="form-control" name="usia_gestasi"
                                    value="<?= ed('usia_gestasi', $existing_data) ?>" <?= $ro ?>>
                                <span class="input-group-text">minggu</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ===================== IDENTITAS ORANG TUA ===================== -->
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary"><strong>Identitas Orang Tua</strong></label>
                    </div>

                    <!-- AYAH -->
                    <div class="row mb-2">
                        <label class="col-sm-12"><strong>Ayah</strong></label>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Nama</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="nama_ayah"
                                value="<?= ed('nama_ayah', $existing_data) ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Usia</strong></label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <input type="text" class="form-control" name="usia_ayah"
                                    value="<?= ed('usia_ayah', $existing_data) ?>" <?= $ro ?>>
                                <span class="input-group-text">tahun</span>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Pekerjaan</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="pekerjaan_ayah"
                                value="<?= ed('pekerjaan_ayah', $existing_data) ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Alamat</strong></label>
                        <div class="col-sm-9">
                            <textarea class="form-control" name="alamat_ayah" rows="2"
                                style="overflow:hidden; resize:none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= ed('alamat_ayah', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- IBU -->
                    <div class="row mb-2 mt-3">
                        <label class="col-sm-12"><strong>Ibu</strong></label>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Nama</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="nama_ibu"
                                value="<?= ed('nama_ibu', $existing_data) ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Usia</strong></label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <input type="text" class="form-control" name="usia_ibu"
                                    value="<?= ed('usia_ibu', $existing_data) ?>" <?= $ro ?>>
                                <span class="input-group-text">tahun</span>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Pekerjaan</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="pekerjaan_ibu"
                                value="<?= ed('pekerjaan_ibu', $existing_data) ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Status Gravida</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="status_gravida"
                                value="<?= ed('status_gravida', $existing_data) ?>"
                                placeholder="Contoh: G2P1A0" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Pemeriksaan Kehamilan</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="pemeriksaan_kehamilan"
                                value="<?= ed('pemeriksaan_kehamilan', $existing_data) ?>" <?= $ro ?>>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ===================== RIWAYAT KEHAMILAN ===================== -->
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary"><strong>Riwayat Kehamilan</strong></label>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Status GPA</strong></label>
                        <div class="col-sm-9">
                            <div class="row g-2">
                                <div class="col-sm-4">
                                    <div class="input-group">
                                        <span class="input-group-text">G</span>
                                        <input type="text" class="form-control" name="gpa_g"
                                            value="<?= ed('gpa_g', $existing_data) ?>"
                                            placeholder="Gravida" <?= $ro ?>>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="input-group">
                                        <span class="input-group-text">P</span>
                                        <input type="text" class="form-control" name="gpa_p"
                                            value="<?= ed('gpa_p', $existing_data) ?>"
                                            placeholder="Partus" <?= $ro ?>>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="input-group">
                                        <span class="input-group-text">A</span>
                                        <input type="text" class="form-control" name="gpa_a"
                                            value="<?= ed('gpa_a', $existing_data) ?>"
                                            placeholder="Abortus" <?= $ro ?>>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Penggunaan Obat-obatan selama kehamilan</strong></label>
                        <div class="col-sm-9">
                            <textarea class="form-control" name="obat_kehamilan" rows="2"
                                style="overflow:hidden; resize:none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= ed('obat_kehamilan', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Imunisasi TT</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="imunisasi_tt"
                                value="<?= ed('imunisasi_tt', $existing_data) ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Komplikasi penyakit selama kehamilan</strong></label>
                        <div class="col-sm-9">
                            <textarea class="form-control" name="komplikasi_kehamilan" rows="2"
                                style="overflow:hidden; resize:none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= ed('komplikasi_kehamilan', $existing_data) ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ===================== RIWAYAT PERSALINAN ===================== -->
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary"><strong>Riwayat Persalinan Sekarang</strong></label>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Riwayat Persalinan</strong></label>
                        <div class="col-sm-9">
                            <textarea class="form-control" name="riwayat_persalinan" rows="2"
                                style="overflow:hidden; resize:none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= ed('riwayat_persalinan', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Tempat Persalinan</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="tempat_persalinan"
                                value="<?= ed('tempat_persalinan', $existing_data) ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Jenis Persalinan</strong></label>
                        <div class="col-sm-9">
                            <div class="d-flex gap-4 mt-2 flex-wrap">
                                <?php
                                $jenis_options = ['Spontan', 'SC (Sectio Caesaria)', 'Vakum', 'Forcep'];
                                foreach ($jenis_options as $opt):
                                    $id = 'jp_' . strtolower(preg_replace('/\W+/', '_', $opt));
                                ?>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="jenis_persalinan"
                                            value="<?= $opt ?>" id="<?= $id ?>" <?= $ro_disabled ?>
                                            <?= (ed('jenis_persalinan', $existing_data) === $opt) ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="<?= $id ?>"><?= $opt ?></label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Persentasi</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="persentasi"
                                value="<?= ed('persentasi', $existing_data) ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Air Ketuban</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="air_ketuban"
                                value="<?= ed('air_ketuban', $existing_data) ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Lama Persalinan Kala II</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="lama_persalinan"
                                value="<?= ed('lama_persalinan', $existing_data) ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <!-- Tali Pusat -->
                    <div class="row mb-2 mt-3">
                        <label class="col-sm-12"><strong>Keadaan Tali Pusat</strong></label>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Panjang</strong></label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <input type="text" class="form-control" name="tali_pusat_panjang"
                                    value="<?= ed('tali_pusat_panjang', $existing_data) ?>" <?= $ro ?>>
                                <span class="input-group-text">cm</span>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Jumlah Vena</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="tali_pusat_vena"
                                value="<?= ed('tali_pusat_vena', $existing_data) ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Jumlah Arteri</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="tali_pusat_arteri"
                                value="<?= ed('tali_pusat_arteri', $existing_data) ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Warna</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="tali_pusat_warna"
                                value="<?= ed('tali_pusat_warna', $existing_data) ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Kelainan</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="tali_pusat_kelainan"
                                value="<?= ed('tali_pusat_kelainan', $existing_data) ?>" <?= $ro ?>>
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

                </div>
            </div>

        </form>

        <?php include dirname(__DIR__, 2) . '/partials/footer_form.php'; ?>
    </section>
</main>