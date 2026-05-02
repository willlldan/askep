<?php
require_once "koneksi.php";
require_once "utils.php";

$form_id       = 8;
$level         = $_SESSION['level'];
$user_id       = $_SESSION['id_user'];
$section_name  = 'pengkajian_riwayat';
$section_label = 'Pengkajian Riwayat';

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
        // 7. Riwayat Imunisasi
        'bcg_frekuensi'          => $_POST['bcg_frekuensi'] ?? '',
        'bcg_reaksi'             => $_POST['bcg_reaksi'] ?? '',
        'dpt_frekuensi'          => $_POST['dpt_frekuensi'] ?? '',
        'dpt_reaksi'             => $_POST['dpt_reaksi'] ?? '',
        'polio_frekuensi'        => $_POST['polio_frekuensi'] ?? '',
        'polio_reaksi'           => $_POST['polio_reaksi'] ?? '',
        'campak_frekuensi'       => $_POST['campak_frekuensi'] ?? '',
        'campak_reaksi'          => $_POST['campak_reaksi'] ?? '',
        'hepatitis_frekuensi'    => $_POST['hepatitis_frekuensi'] ?? '',
        'hepatitis_reaksi'       => $_POST['hepatitis_reaksi'] ?? '',

        // 8. Riwayat Tumbuh Kembang
        'bb'                      => $_POST['bb'] ?? '',
        'tb'                      => $_POST['tb'] ?? '',
        'gigi'                    => $_POST['gigi'] ?? '',
        'gigi_tanggal'            => $_POST['gigi_tanggal'] ?? '',
        'gigi_jumlah'             => $_POST['gigi_jumlah'] ?? '',

        // 9. Riwayat Nutrisi
        'asi'                     => $_POST['asi'] ?? '',
        'alasan_susu'             => $_POST['alasan_susu'] ?? '',
        'jumlah_susu'             => $_POST['jumlah_susu'] ?? '',

        // 10. Riwayat Psikososial
        'tinggal_bersama'         => $_POST['tinggal_bersama'] ?? '',
        'tinggal_di'              => $_POST['tinggal_di'] ?? '',
        'rumah_dekat'             => $_POST['rumah_dekat'] ?? '',
        'tempat_bermain'          => $_POST['tempat_bermain'] ?? '',
        'kamar_klien'             => $_POST['kamar_klien'] ?? '',

        // 11. Reaksi Hospitalisasi
        'alasan_rs'               => $_POST['alasan_rs'] ?? '',
        'penjelasan_dokter'       => $_POST['penjelasan_dokter'] ?? '',
        'perasaan'                => $_POST['perasaan'] ?? '',
        'kunjungan'               => $_POST['kunjungan'] ?? '',
        'pendamping'              => $_POST['pendamping'] ?? '',

        // 12. Reaksi Anak Selama Dirawat
        'reaksi_anak'             => $_POST['reaksi_anak'] ?? '',
        // Nutrisi
        'selera_sebelum'      => $_POST['selera_sebelum'] ?? '',
        'selera_saat'         => $_POST['selera_saat'] ?? '',
        'porsi_sebelum'       => $_POST['porsi_sebelum'] ?? '',
        'porsi_saat'          => $_POST['porsi_saat'] ?? '',
        'menu_sebelum'        => $_POST['menu_sebelum'] ?? '',
        'menu_saat'           => $_POST['menu_saat'] ?? '',

        // Cairan
        'jenis_minum_sebelum' => $_POST['jenis_minum_sebelum'] ?? '',
        'jenis_minum_saat'    => $_POST['jenis_minum_saat'] ?? '',
        'frekuensi_minum_sebelum' => $_POST['frekuensi_minum_sebelum'] ?? '',
        'frekuensi_minum_saat'    => $_POST['frekuensi_minum_saat'] ?? '',
        'kebutuhan_cairan_sebelum' => $_POST['kebutuhan_cairan_sebelum'] ?? '',
        'kebutuhan_cairan_saat'    => $_POST['kebutuhan_cairan_saat'] ?? '',
        'cara_cairan_sebelum'      => $_POST['cara_cairan_sebelum'] ?? '',
        'cara_cairan_saat'         => $_POST['cara_cairan_saat'] ?? '',

        // Eliminasi (BAK)
        'bak_tempat_sebelum'     => $_POST['bak_tempat_sebelum'] ?? '',
        'bak_tempat_saat'        => $_POST['bak_tempat_saat'] ?? '',
        'bak_frekuensi_sebelum'  => $_POST['bak_frekuensi_sebelum'] ?? '',
        'bak_frekuensi_saat'     => $_POST['bak_frekuensi_saat'] ?? '',
        'bak_karakteristik_sebelum' => $_POST['bak_karakteristik_sebelum'] ?? '',
        'bak_karakteristik_saat'    => $_POST['bak_karakteristik_saat'] ?? '',

        // Eliminasi (BAB)
        'bab_tempat_sebelum'     => $_POST['bab_tempat_sebelum'] ?? '',
        'bab_tempat_saat'        => $_POST['bab_tempat_saat'] ?? '',
        'bab_frekuensi_sebelum'  => $_POST['bab_frekuensi_sebelum'] ?? '',
        'bab_frekuensi_saat'     => $_POST['bab_frekuensi_saat'] ?? '',
        'bab_karakteristik_sebelum' => $_POST['bab_karakteristik_sebelum'] ?? '',
        'bab_karakteristik_saat'    => $_POST['bab_karakteristik_saat'] ?? '',

        // Istirahat Tidur
        'tidur_siang_sebelum'     => $_POST['tidur_siang_sebelum'] ?? '',
        'tidur_siang_sekarang'    => $_POST['tidur_siang_sekarang'] ?? '',
        'tidur_malam_sebelum'     => $_POST['tidur_malam_sebelum'] ?? '',
        'tidur_malam_sekarang'    => $_POST['tidur_malam_sekarang'] ?? '',
        'kesulitan_tidur_sebelum' => $_POST['kesulitan_tidur_sebelum'] ?? '',
        'kesulitan_tidur_sekarang' => $_POST['kesulitan_tidur_sekarang'] ?? '',
        'kebiasaan_tidur_sebelum' => $_POST['kebiasaan_tidur_sebelum'] ?? '',
        'kebiasaan_tidur_sekarang' => $_POST['kebiasaan_tidur_sekarang'] ?? '',
        'pola_tidur_sebelum'      => $_POST['pola_tidur_sebelum'] ?? '',
        'pola_tidur_sekarang'     => $_POST['pola_tidur_sekarang'] ?? '',

        // Pola Personal Hygiene
        'mandi_frekuensi_sebelum' => $_POST['mandi_frekuensi_sebelum'] ?? '',
        'mandi_frekuensi_sekarang' => $_POST['mandi_frekuensi_sekarang'] ?? '',
        'mandi_cara_sebelum'      => $_POST['mandi_cara_sebelum'] ?? '',
        'mandi_cara_sekarang'     => $_POST['mandi_cara_sekarang'] ?? '',
        'mandi_tempat_sebelum'    => $_POST['mandi_tempat_sebelum'] ?? '',
        'mandi_tempat_sekarang'   => $_POST['mandi_tempat_sekarang'] ?? '',
        'rambut_frekuensi_sebelum' => $_POST['rambut_frekuensi_sebelum'] ?? '',
        'rambut_frekuensi_sekarang' => $_POST['rambut_frekuensi_sekarang'] ?? '',
        'rambut_cara_sebelum'     => $_POST['rambut_cara_sebelum'] ?? '',
        'rambut_cara_sekarang'    => $_POST['rambut_cara_sekarang'] ?? '',
        'kuku_frekuensi_sebelum'  => $_POST['kuku_frekuensi_sebelum'] ?? '',
        'kuku_frekuensi_sekarang' => $_POST['kuku_frekuensi_sekarang'] ?? '',
        'kuku_cara_sebelum'       => $_POST['kuku_cara_sebelum'] ?? '',
        'kuku_cara_sekarang'      => $_POST['kuku_cara_sekarang'] ?? '',
        'gigi_frekuensi_sebelum'  => $_POST['gigi_frekuensi_sebelum'] ?? '',
        'gigi_frekuensi_sekarang' => $_POST['gigi_frekuensi_sekarang'] ?? '',
        'gigi_cara_sebelum'       => $_POST['gigi_cara_sebelum'] ?? '',
        'gigi_cara_sekarang'      => $_POST['gigi_cara_sekarang'] ?? ''
    ];

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
$ro_select   = $is_readonly ? 'disabled' : '';
?>

<main id="main" class="main">

    <?php include "anak/format_anggrek/tab.php"; ?>

    <section class="section dashboard">
        <?php include dirname(__DIR__, 2) . '/partials/notifikasi.php'; ?>
        <?php include dirname(__DIR__, 2) . '/partials/status_section.php'; ?>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-1"><strong>7. Riwayat Imunisasi (Imunisasi Lengkap)</strong></h5>

                <!-- General Form Elements -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">






                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="text-center">
                                <tr>
                                    <th style="width:5%">No</th>
                                    <th style="width:35%">Jenis Imunisasi</th>
                                    <th style="width:25%">Frekuensi</th>
                                    <th style="width:35%">Reaksi Setelah Pemberian</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-center">1</td>
                                    <td>BCG</td>
                                    <td><input type="text" class="form-control" name="bcg_frekuensi" value="<?= val('bcg_frekuensi', $existing_data) ?>" <?= $ro ?>></td>
                                    <td><input type="text" class="form-control" name="bcg_reaksi" value="<?= val('bcg_reaksi', $existing_data) ?>" <?= $ro ?>></td>
                                </tr>
                                <tr>
                                    <td class="text-center">2</td>
                                    <td>DPT Hb Hib (I, II, III)</td>
                                    <td><input type="text" class="form-control" name="dpt_frekuensi" value="<?= val('dpt_frekuensi', $existing_data) ?>" <?= $ro ?>></td>
                                    <td><input type="text" class="form-control" name="dpt_reaksi" value="<?= val('dpt_reaksi', $existing_data) ?>" <?= $ro ?>></td>
                                </tr>
                                <tr>
                                    <td class="text-center">3</td>
                                    <td>Polio (I, II, III, IV)</td>
                                    <td><input type="text" class="form-control" name="polio_frekuensi" value="<?= val('polio_frekuensi', $existing_data) ?>" <?= $ro ?>></td>
                                    <td><input type="text" class="form-control" name="polio_reaksi" value="<?= val('polio_reaksi', $existing_data) ?>" <?= $ro ?>></td>
                                </tr>
                                <tr>
                                    <td class="text-center">4</td>
                                    <td>Campak</td>
                                    <td><input type="text" class="form-control" name="campak_frekuensi" value="<?= val('campak_frekuensi', $existing_data) ?>" <?= $ro ?>></td>
                                    <td><input type="text" class="form-control" name="campak_reaksi" value="<?= val('campak_reaksi', $existing_data) ?>" <?= $ro ?>></td>
                                </tr>
                                <tr>
                                    <td class="text-center">5</td>
                                    <td>Hepatitis</td>
                                    <td><input type="text" class="form-control" name="hepatitis_frekuensi" value="<?= val('hepatitis_frekuensi', $existing_data) ?>" <?= $ro ?>></td>
                                    <td><input type="text" class="form-control" name="hepatitis_reaksi" value="<?= val('hepatitis_reaksi', $existing_data) ?>" <?= $ro ?>></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- 8. RIWAYAT TUMBUH KEMBANG -->
                    <div class="row mb-3">
                        <label class="col-sm-12 col-form-label text-primary">
                            <strong>8. Riwayat Tumbuh Kembang</strong>
                        </label>
                    </div>
                    <div class="row mb-2">
                        <label class="col-sm-12"><strong>Pertumbuhan Fisik</strong></label>
                    </div>

                    <!-- Berat Badan -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Berat Badan (kg)</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="bb" value="<?= val('bb', $existing_data) ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Tinggi Badan (cm)</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="tb" value="<?= val('tb', $existing_data) ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Waktu Tumbuh Gigi</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="gigi" value="<?= val('gigi', $existing_data) ?>" <?= $ro ?>>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Gigi Tanggal</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="gigi_tanggal" value="<?= val('gigi_tanggal', $existing_data) ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-3 ">
                        <label class="col-sm-2 col-form-label"><strong>Gigi Jumlah</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="gigi_jumlah" value="<?= val('gigi_jumlah', $existing_data) ?>" <?= $ro ?>>
                        </div>
                        <!-- 9. RIWAYAT NUTRISI -->
                        <div class="row mb-3">
                            <label class="col-sm-12 col-form-label text-primary">
                                <strong>9. Riwayat Nutrisi</strong>
                            </label>
                        </div>
                        <!-- ASI -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Pemberian ASI sampai usia<< /strong></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="asi" value="<?= val('asi', $existing_data) ?>" <?= $ro ?>>
                            </div>
                        </div>

                        <!-- Susu Formula -->
                        <div class="row mb-2">
                            <label class="col-sm-12"><strong>Pemberian Susu Formula</strong></label>
                        </div>

                        <!-- Alasan -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Alasan pemberian</strong></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="alasan_susu" value="<?= val('alasan_susu', $existing_data) ?>" <?= $ro ?>>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Jumlah pemberian sehari</strong></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="jumlah_susu" value="<?= val('jumlah_susu', $existing_data) ?>" <?= $ro ?>>
                            </div>
                        </div>

                    </div>
                    <!-- 10. RIWAYAT PSIKOSOSIAL -->
                    <div class="row mb-3">
                        <label class="col-sm-12 col-form-label text-primary">
                            <strong>10. Riwayat Psikososial</strong>
                        </label>
                    </div>

                    <!-- Anak tinggal -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Anak tinggal bersama</strong></label>
                        <div class="col-sm-9 d-flex gap-2">
                            <input type="text" class="form-control" name="tinggal_bersama" value="<?= val('tinggal_bersama', $existing_data) ?>" <?= $ro ?>>
                            <span>di</span>
                            <input type="text" class="form-control" name="tinggal_di" value="<?= val('tinggal_di', $existing_data) ?>" <?= $ro ?> style="min-width:150px;">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Rumah Dekat Dengan</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="rumah_dekat" value="<?= val('rumah_dekat', $existing_data) ?>" <?= $ro ?>>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Tempat Anak Bermain</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="tempat_bermain" value="<?= val('tempat_bermain', $existing_data) ?>" <?= $ro ?>>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Kamar klien</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="kamar_klien" value="<?= val('kamar_klien', $existing_data) ?>" <?= $ro ?>>
                        </div>
                    </div>


                    <!-- 11. REAKSI HOSPITALISASI -->
                    <div class="row mb-3">
                        <label class="col-sm-12 col-form-label text-primary">
                            <strong>11. Reaksi Hospitalisasi</strong>
                        </label>
                    </div>
                    <div class="row mb-2">
                        <label class="col-sm-12"><strong>a. Pengalaman keluarga tentang sakit dan rawat inap</strong></label>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Ibu membawa anak ke RS karena</strong></label>
                        <div class="col-sm-9">
                            <textarea class="form-control" rows="3" name="alasan_rs" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('alasan_rs', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Dokter menceritakan kondisi anak</strong></label>
                        <div class="col-sm-9">
                            <textarea class="form-control" rows="3" name="penjelasan_dokter" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('penjelasan_dokter', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Perasaan orang tua saat ini</strong></label>
                        <div class="col-sm-9">
                            <textarea class="form-control" rows="3" name="perasaan" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('perasaan', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Orang tua selalu berkunjung ke RS</strong></label>
                        <div class="col-sm-9">
                            <textarea class="form-control" rows="2" name="kunjungan" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('kunjungan', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Yang akan tinggal menemani anak di rumah sakit</strong></label>
                        <div class="col-sm-9">
                            <textarea class="form-control" rows="4" name="pendamping" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('pendamping', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- 12. REAKSI ANAK -->
                    <div class="row mb-3">
                        <label class="col-sm-12 col-form-label text-primary">
                            <strong>12. Reaksi Anak Selama Dirawat</strong>
                        </label>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Reaksi Anak selama dirawat</strong></label>
                        <div class="col-sm-9">
                            <textarea class="form-control" rows="2" name="reaksi_anak" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('reaksi_anak', $existing_data) ?></textarea>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-12 col-form-label text-primary">
                            <strong>13. Aktivitas sehari-hari</strong>
                        </label>
                    </div>
                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary">
                            <strong>Nutrisi</strong>
                        </label>
                    </div>

                    <div class="row mb-4">
                        <div class="col-sm-12">

                            <div class="table-responsive">
                                <table class="table table-bordered mb-0">
                                    <thead class="table-light text-center">
                                        <tr>
                                            <th style="width:40%">Kondisi</th>
                                            <th style="width:30%">Sebelum Sakit</th>
                                            <th style="width:30%">Saat Sakit</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><strong>1. Selera Makan</strong></td>
                                            <td><input type="text" class="form-control" name="selera_sebelum" value="<?= val('selera_sebelum', $existing_data) ?>" <?= $ro ?>></td>
                                            <td><input type="text" class="form-control" name="selera_saat" value="<?= val('selera_saat', $existing_data) ?>" <?= $ro ?>></td>
                                        </tr>

                                        <tr>
                                            <td><strong>2. Porsi Makan</strong></td>
                                            <td><input type="text" class="form-control" name="porsi_sebelum" value="<?= val('porsi_sebelum', $existing_data) ?>" <?= $ro ?>></td>
                                            <td><input type="text" class="form-control" name="porsi_saat" value="<?= val('porsi_saat', $existing_data) ?>" <?= $ro ?>></td>
                                        </tr>

                                        <tr>
                                            <td><strong>3. Menu Makanan</strong></td>
                                            <td><input type="text" class="form-control" name="menu_sebelum" value="<?= val('menu_sebelum', $existing_data) ?>" <?= $ro ?>></td>
                                            <td><input type="text" class="form-control" name="menu_saat" value="<?= val('menu_saat', $existing_data) ?>" <?= $ro ?>></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary">
                            <strong>Cairan</strong>
                        </label>
                    </div>

                    <div class="row mb-4">
                        <div class="col-sm-12">

                            <div class="table-responsive">
                                <table class="table table-bordered mb-0">
                                    <thead class="table-light text-center">
                                        <tr>
                                            <th style="width:40%">Kondisi</th>
                                            <th style="width:30%">Sebelum Sakit</th>
                                            <th style="width:30%">Saat Sakit</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <tr>
                                            <td><strong>1. Jenis Minuman</strong></td>
                                            <td><input type="text" class="form-control" name="jenis_minum_sebelum" value="<?= val('jenis_minum_sebelum', $existing_data) ?>" <?= $ro ?>></td>
                                            <td><input type="text" class="form-control" name="jenis_minum_saat" value="<?= val('jenis_minum_saat', $existing_data) ?>" <?= $ro ?>></td>
                                        </tr>

                                        <tr>
                                            <td><strong>2. Frekuensi Minum</strong></td>
                                            <td><input type="text" class="form-control" name="frekuensi_minum_sebelum" value="<?= val('frekuensi_minum_sebelum', $existing_data) ?>" <?= $ro ?>></td>
                                            <td><input type="text" class="form-control" name="frekuensi_minum_saat" value="<?= val('frekuensi_minum_saat', $existing_data) ?>" <?= $ro ?>></td>
                                        </tr>

                                        <tr>
                                            <td><strong>3. Kebutuhan Cairan</strong></td>
                                            <td><input type="text" class="form-control" name="kebutuhan_cairan_sebelum" value="<?= val('kebutuhan_cairan_sebelum', $existing_data) ?>" <?= $ro ?>></td>
                                            <td><input type="text" class="form-control" name="kebutuhan_cairan_saat" value="<?= val('kebutuhan_cairan_saat', $existing_data) ?>" <?= $ro ?>></td>
                                        </tr>

                                        <tr>
                                            <td><strong>4. Cara Pemenuhan</strong></td>
                                            <td><input type="text" class="form-control" name="cara_cairan_sebelum" value="<?= val('cara_cairan_sebelum', $existing_data) ?>" <?= $ro ?>></td>
                                            <td><input type="text" class="form-control" name="cara_cairan_saat" value="<?= val('cara_cairan_saat', $existing_data) ?>" <?= $ro ?>></td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary">
                            <strong>Eliminasi (BAK)</strong>
                        </label>
                    </div>

                    <div class="row mb-4">
                        <div class="col-sm-12">

                            <div class="table-responsive">
                                <table class="table table-bordered mb-0">
                                    <thead class="table-light text-center">
                                        <tr>
                                            <th style="width:40%">Kondisi</th>
                                            <th style="width:30%">Sebelum Sakit</th>
                                            <th style="width:30%">Saat Sakit</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <tr>
                                            <td><strong>1. Tempat Pembuangan</strong></td>
                                            <td><input type="text" class="form-control" name="bak_tempat_sebelum" value="<?= val('bak_tempat_sebelum', $existing_data) ?>" <?= $ro ?>></td>
                                            <td><input type="text" class="form-control" name="bak_tempat_saat" value="<?= val('bak_tempat_saat', $existing_data) ?>" <?= $ro ?>></td>
                                        </tr>

                                        <tr>
                                            <td><strong>2. Frekuensi (Waktu)</strong></td>
                                            <td><input type="text" class="form-control" name="bak_frekuensi_sebelum" value="<?= val('bak_frekuensi_sebelum', $existing_data) ?>" <?= $ro ?>></td>
                                            <td><input type="text" class="form-control" name="bak_frekuensi_saat" value="<?= val('bak_frekuensi_saat', $existing_data) ?>" <?= $ro ?>></td>
                                        </tr>

                                        <tr>
                                            <td><strong>3. Karakteristik</strong></td>
                                            <td><input type="text" class="form-control" name="bak_karakteristik_sebelum" value="<?= val('bak_karakteristik_sebelum', $existing_data) ?>" <?= $ro ?>></td>
                                            <td><input type="text" class="form-control" name="bak_karakteristik_saat" value="<?= val('bak_karakteristik_saat', $existing_data) ?>" <?= $ro ?>></td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>

                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary">
                            <strong>Eliminasi (BAB)</strong>
                        </label>
                    </div>

                    <div class="row mb-4">
                        <div class="col-sm-12">

                            <div class="table-responsive">
                                <table class="table table-bordered mb-0">
                                    <thead class="table-light text-center">
                                        <tr>
                                            <th style="width:40%">Kondisi</th>
                                            <th style="width:30%">Sebelum Sakit</th>
                                            <th style="width:30%">Saat Sakit</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <tr>
                                            <td><strong>1. Tempat Pembuangan</strong></td>
                                            <td><input type="text" class="form-control" name="bab_tempat_sebelum" value="<?= val('bab_tempat_sebelum', $existing_data) ?>" <?= $ro ?>></td>
                                            <td><input type="text" class="form-control" name="bab_tempat_saat" value="<?= val('bab_tempat_saat', $existing_data) ?>" <?= $ro ?>></td>
                                        </tr>

                                        <tr>
                                            <td><strong>2. Frekuensi (Waktu)</strong></td>
                                            <td><input type="text" class="form-control" name="bab_frekuensi_sebelum" value="<?= val('bab_frekuensi_sebelum', $existing_data) ?>" <?= $ro ?>></td>
                                            <td><input type="text" class="form-control" name="bab_frekuensi_saat" value="<?= val('bab_frekuensi_saat', $existing_data) ?>" <?= $ro ?>></td>
                                        </tr>

                                        <tr>
                                            <td><strong>3. Karakteristik</strong></td>
                                            <td><input type="text" class="form-control" name="bab_karakteristik_sebelum" value="<?= val('bab_karakteristik_sebelum', $existing_data) ?>" <?= $ro ?>></td>
                                            <td><input type="text" class="form-control" name="bab_karakteristik_saat" value="<?= val('bab_karakteristik_saat', $existing_data) ?>" <?= $ro ?>></td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>


                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary"><strong>Istirahat Tidur</strong></label>
                    </div>

                    <div class="row mb-4">
                        <div class="col-sm-12">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th><strong>No</strong></th>
                                            <th><strong>Kondisi</strong></th>
                                            <th><strong>Sebelum Sakit</strong></th>
                                            <th><strong>Saat Ini</strong></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td rowspan="2">1</td>
                                            <td><strong>Jam Tidur - Siang</strong></td>
                                            <td><input type="text" class="form-control" name="tidur_siang_sebelum" value="<?= val('tidur_siang_sebelum', $existing_data) ?>" <?= $ro ?>></td>
                                            <td><input type="text" class="form-control" name="tidur_siang_sekarang" value="<?= val('tidur_siang_sekarang', $existing_data) ?>" <?= $ro ?>></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Jam Tidur - Malam</strong></td>
                                            <td><input type="text" class="form-control" name="tidur_malam_sebelum" value="<?= val('tidur_malam_sebelum', $existing_data) ?>" <?= $ro ?>></td>
                                            <td><input type="text" class="form-control" name="tidur_malam_sekarang" value="<?= val('tidur_malam_sekarang', $existing_data) ?>" <?= $ro ?>></td>
                                        </tr>

                                        <tr>
                                            <td>2</td>
                                            <td><strong>Kesulitan Tidur</strong></td>
                                            <td><input type="text" class="form-control" name="kesulitan_tidur_sebelum" value="<?= val('kesulitan_tidur_sebelum', $existing_data) ?>" <?= $ro ?>></td>
                                            <td><input type="text" class="form-control" name="kesulitan_tidur_sekarang" value="<?= val('kesulitan_tidur_sekarang', $existing_data) ?>" <?= $ro ?>></td>
                                        </tr>

                                        <tr>
                                            <td>3</td>
                                            <td><strong>Kebiasaan Sebelum Tidur</strong></td>
                                            <td><input type="text" class="form-control" name="kebiasaan_tidur_sebelum" value="<?= val('kebiasaan_tidur_sebelum', $existing_data) ?>" <?= $ro ?>></td>
                                            <td><input type="text" class="form-control" name="kebiasaan_tidur_sekarang" value="<?= val('kebiasaan_tidur_sekarang', $existing_data) ?>" <?= $ro ?>></td>
                                        </tr>

                                        <tr>
                                            <td>4</td>
                                            <td><strong>Pola Tidur</strong></td>
                                            <td><input type="text" class="form-control" name="pola_tidur_sebelum" value="<?= val('pola_tidur_sebelum', $existing_data) ?>" <?= $ro ?>></td>
                                            <td><input type="text" class="form-control" name="pola_tidur_sekarang" value="<?= val('pola_tidur_sekarang', $existing_data) ?>" <?= $ro ?>></td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary"><strong>Pola Personal Hygiene</strong></label>
                    </div>
                    <div class="row mb-4">
                        <div class="col-sm-12">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th><strong>No</strong></th>
                                            <th><strong>Kondisi</strong></th>
                                            <th><strong>Sebelum Sakit</strong></th>
                                            <th><strong>Saat Ini</strong></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td rowspan="3">1</td>
                                            <td><strong>Mandi - Frekuensi</strong></td>
                                            <td><input type="text" class="form-control" name="mandi_frekuensi_sebelum" value="<?= val('mandi_frekuensi_sebelum', $existing_data) ?>" <?= $ro ?>></td>
                                            <td><input type="text" class="form-control" name="mandi_frekuensi_sekarang" value="<?= val('mandi_frekuensi_sekarang', $existing_data) ?>" <?= $ro ?>></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Mandi - Cara</strong></td>
                                            <td><input type="text" class="form-control" name="mandi_cara_sebelum" value="<?= val('mandi_cara_sebelum', $existing_data) ?>" <?= $ro ?>></td>
                                            <td><input type="text" class="form-control" name="mandi_cara_sekarang" value="<?= val('mandi_cara_sekarang', $existing_data) ?>" <?= $ro ?>></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Mandi - Alat Mandi</strong></td>
                                            <td><input type="text" class="form-control" name="mandi_tempat_sebelum" value="<?= val('mandi_tempat_sebelum', $existing_data) ?>" <?= $ro ?>></td>
                                            <td><input type="text" class="form-control" name="mandi_tempat_sekarang" value="<?= val('mandi_tempat_sekarang', $existing_data) ?>" <?= $ro ?>></td>
                                        </tr>
                                        <tr>
                                            <td rowspan="2">2</td>
                                            <td><strong>Cuci Rambut - Frekuensi</strong></td>
                                            <td><input type="text" class="form-control" name="rambut_frekuensi_sebelum" value="<?= val('rambut_frekuensi_sebelum', $existing_data) ?>" <?= $ro ?>></td>
                                            <td><input type="text" class="form-control" name="rambut_frekuensi_sekarang" value="<?= val('rambut_frekuensi_sekarang', $existing_data) ?>" <?= $ro ?>></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Cuci Rambut - Cara</strong></td>
                                            <td><input type="text" class="form-control" name="rambut_cara_sebelum" value="<?= val('rambut_cara_sebelum', $existing_data) ?>" <?= $ro ?>></td>
                                            <td><input type="text" class="form-control" name="rambut_cara_sekarang" value="<?= val('rambut_cara_sekarang', $existing_data) ?>" <?= $ro ?>></td>
                                        </tr>
                                        <tr>
                                            <td rowspan="2">3</td>
                                            <td><strong>Gunting Kuku - Frekuensi</strong></td>
                                            <td><input type="text" class="form-control" name="kuku_frekuensi_sebelum" value="<?= val('kuku_frekuensi_sebelum', $existing_data) ?>" <?= $ro ?>></td>
                                            <td><input type="text" class="form-control" name="kuku_frekuensi_sekarang" value="<?= val('kuku_frekuensi_sekarang', $existing_data) ?>" <?= $ro ?>></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Gunting Kuku - Cara</strong></td>
                                            <td><input type="text" class="form-control" name="kuku_cara_sebelum" value="<?= val('kuku_cara_sebelum', $existing_data) ?>" <?= $ro ?>></td>
                                            <td><input type="text" class="form-control" name="kuku_cara_sekarang" value="<?= val('kuku_cara_sekarang', $existing_data) ?>" <?= $ro ?>></td>
                                        </tr>
                                        <tr>
                                            <td rowspan="2">4</td>
                                            <td><strong>Gosok Gigi - Frekuensi</strong></td>
                                            <td><input type="text" class="form-control" name="gigi_frekuensi_sebelum" value="<?= val('gigi_frekuensi_sebelum', $existing_data) ?>" <?= $ro ?>></td>
                                            <td><input type="text" class="form-control" name="gigi_frekuensi_sekarang" value="<?= val('gigi_frekuensi_sekarang', $existing_data) ?>" <?= $ro ?>></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Gosok Gigi - Cara</strong></td>
                                            <td><input type="text" class="form-control" name="gigi_cara_sebelum" value="<?= val('gigi_cara_sebelum', $existing_data) ?>" <?= $ro ?>></td>
                                            <td><input type="text" class="form-control" name="gigi_cara_sekarang" value="<?= val('gigi_cara_sekarang', $existing_data) ?>" <?= $ro ?>></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

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