<?php
require_once "koneksi.php";
require_once "utils.php";

$form_id       = 15;
$level         = $_SESSION['level'];
$user_id       = $_SESSION['id_user'];
$section_name  = 'gordon';
$section_label = 'Pola Pengkajian FX Gordon';

// Dosen: ambil submission by ?submission_id=, Mahasiswa: milik sendiri
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
$rs_ruangan     = $submission['rs_ruangan']         ?? '';

// Readonly jika dosen atau submission terkunci
$is_dosen    = $level === 'Dosen';
$is_readonly = $is_dosen || isLocked($submission);
$ro          = $is_readonly ? 'readonly' : '';
$ro_disabled = $is_readonly ? 'disabled' : '';

// =============================================
// HANDLE POST - MAHASISWA SIMPAN DATA
// =============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $level === 'Mahasiswa') {

    if (isLocked($submission)) {
        redirectWithMessage($_SERVER['REQUEST_URI'], 'error', 'Data tidak dapat diubah karena sedang dalam proses review.');
    }

    $data = [
        // a. Persepsi Kesehatan
        'merokok'               => $_POST['merokok']               ?? '',
        'pemeriksaan_rutin'     => $_POST['pemeriksaan_rutin']     ?? '',
        'pendapat_kesehatan'    => $_POST['pendapat_kesehatan']    ?? '',
        'persepsi_penyakit'     => $_POST['persepsi_penyakit']     ?? '',
        'tingkat_kesembuhan'    => $_POST['tingkat_kesembuhan']    ?? '',
        // b. Pola Aktivitas
        'rutinitas_mandi'       => $_POST['rutinitas_mandi']       ?? '',
        'kebersihan'            => $_POST['kebersihan']            ?? '',
        'aktivitas'             => $_POST['aktivitas']             ?? '',
        // Kemampuan Perawatan Diri
        'mandi'                 => $_POST['mandi']                 ?? '',
        'berpakaian'            => $_POST['berpakaian']            ?? '',
        'mobilisasi'            => $_POST['mobilisasi']            ?? '',
        'pindah'                => $_POST['pindah']                ?? '',
        'ambulasi'              => $_POST['ambulasi']              ?? '',
        'makan'                 => $_POST['makan']                 ?? '',
        // c. Pola Kognitif
        'nyeri'                 => $_POST['nyeri']                 ?? '',
        'panca_indra'           => $_POST['panca_indra']           ?? '',
        'berbicara'             => $_POST['berbicara']             ?? '',
        'membaca'               => $_POST['membaca']               ?? '',
        // d. Pola Konsep Diri
        'konsep_diri'           => $_POST['konsep_diri']           ?? '',
        'hal_disukai'           => $_POST['hal_disukai']           ?? '',
        'kekuatan_kelemahan'    => $_POST['kekuatan_kelemahan']    ?? '',
        'kemampuan_baik'        => $_POST['kemampuan_baik']        ?? '',
        // e. Pola Koping
        'masalah_rs'            => $_POST['masalah_rs']            ?? '',
        'kehilangan'            => $_POST['kehilangan']            ?? '',
        'takut_kekerasan'       => $_POST['takut_kekerasan']       ?? '',
        'masa_depan'            => $_POST['masa_depan']            ?? '',
        'mekanisme_koping'      => $_POST['mekanisme_koping']      ?? '',
        // f. Pola Seksual
        'masalah_menstruasi'    => $_POST['masalah_menstruasi']    ?? '',
        'papsmear'              => $_POST['papsmear']              ?? '',
        'perawatan_payudara'    => $_POST['perawatan_payudara']    ?? '',
        'kesulitan_seksual'     => $_POST['kesulitan_seksual']     ?? '',
        'gangguan_seksual'      => $_POST['gangguan_seksual']      ?? '',
        // g. Pola Peran
        'peran_pasien'          => $_POST['peran_pasien']          ?? '',
        'teman_dekat'           => $_POST['teman_dekat']           ?? '',
        'orang_terpercaya'      => $_POST['orang_terpercaya']      ?? '',
        'kegiatan_masyarakat'   => $_POST['kegiatan_masyarakat']   ?? '',
        // h. Pola Nilai & Kepercayaan
        'agama_klien'           => $_POST['agama_klien']           ?? '',
        'hubungan_tuhan'        => $_POST['hubungan_tuhan']        ?? '',
        'hambatan_ibadah'       => $_POST['hambatan_ibadah']       ?? '',
        // i. Nutrisi
        'frekuensi_makan_sebelum'   => $_POST['frekuensi_makan_sebelum']   ?? '',
        'frekuensi_makan_sekarang'  => $_POST['frekuensi_makan_sekarang']  ?? '',
        'selera_makan_sebelum'      => $_POST['selera_makan_sebelum']      ?? '',
        'selera_makan_sekarang'     => $_POST['selera_makan_sekarang']     ?? '',
        'menu_makan_sebelum'        => $_POST['menu_makan_sebelum']        ?? '',
        'menu_makan_sekarang'       => $_POST['menu_makan_sekarang']       ?? '',
        'ritual_makan_sebelum'      => $_POST['ritual_makan_sebelum']      ?? '',
        'ritual_makan_sekarang'     => $_POST['ritual_makan_sekarang']     ?? '',
        'bantuan_makan_sebelum'     => $_POST['bantuan_makan_sebelum']     ?? '',
        'bantuan_makan_sekarang'    => $_POST['bantuan_makan_sekarang']    ?? '',
        // j. Cairan
        'jenis_minum_sebelum'       => $_POST['jenis_minum_sebelum']       ?? '',
        'jenis_minum_sekarang'      => $_POST['jenis_minum_sekarang']      ?? '',
        'jumlah_cairan_sebelum'     => $_POST['jumlah_cairan_sebelum']     ?? '',
        'jumlah_cairan_sekarang'    => $_POST['jumlah_cairan_sekarang']    ?? '',
        'bantuan_cairan_sebelum'    => $_POST['bantuan_cairan_sebelum']    ?? '',
        'bantuan_cairan_sekarang'   => $_POST['bantuan_cairan_sekarang']   ?? '',
        // k. BAB
        'bab_frekuensi_sebelum'     => $_POST['bab_frekuensi_sebelum']     ?? '',
        'bab_frekuensi_sekarang'    => $_POST['bab_frekuensi_sekarang']    ?? '',
        'bab_konsistensi_sebelum'   => $_POST['bab_konsistensi_sebelum']   ?? '',
        'bab_konsistensi_sekarang'  => $_POST['bab_konsistensi_sekarang']  ?? '',
        'bab_warna_sebelum'         => $_POST['bab_warna_sebelum']         ?? '',
        'bab_warna_sekarang'        => $_POST['bab_warna_sekarang']        ?? '',
        'bab_bau_sebelum'           => $_POST['bab_bau_sebelum']           ?? '',
        'bab_bau_sekarang'          => $_POST['bab_bau_sekarang']          ?? '',
        'bab_kesulitan_sebelum'     => $_POST['bab_kesulitan_sebelum']     ?? '',
        'bab_kesulitan_sekarang'    => $_POST['bab_kesulitan_sekarang']    ?? '',
        'bab_obat_sebelum'          => $_POST['bab_obat_sebelum']          ?? '',
        'bab_obat_sekarang'         => $_POST['bab_obat_sekarang']         ?? '',
        // l. BAK
        'bak_frekuensi_sebelum'     => $_POST['bak_frekuensi_sebelum']     ?? '',
        'bak_frekuensi_sekarang'    => $_POST['bak_frekuensi_sekarang']    ?? '',
        'bak_warna_sebelum'         => $_POST['bak_warna_sebelum']         ?? '',
        'bak_warna_sekarang'        => $_POST['bak_warna_sekarang']        ?? '',
        'bak_bau_sebelum'           => $_POST['bak_bau_sebelum']           ?? '',
        'bak_bau_sekarang'          => $_POST['bak_bau_sekarang']          ?? '',
        'bak_kesulitan_sebelum'     => $_POST['bak_kesulitan_sebelum']     ?? '',
        'bak_kesulitan_sekarang'    => $_POST['bak_kesulitan_sekarang']    ?? '',
        'bak_obat_sebelum'          => $_POST['bak_obat_sebelum']          ?? '',
        'bak_obat_sekarang'         => $_POST['bak_obat_sekarang']         ?? '',
        // m. Tidur
        'tidur_siang_sebelum'       => $_POST['tidur_siang_sebelum']       ?? '',
        'tidur_siang_sekarang'      => $_POST['tidur_siang_sekarang']      ?? '',
        'tidur_malam_sebelum'       => $_POST['tidur_malam_sebelum']       ?? '',
        'tidur_malam_sekarang'      => $_POST['tidur_malam_sekarang']      ?? '',
        'kesulitan_tidur_sebelum'   => $_POST['kesulitan_tidur_sebelum']   ?? '',
        'kesulitan_tidur_sekarang'  => $_POST['kesulitan_tidur_sekarang']  ?? '',
        'kebiasaan_tidur_sebelum'   => $_POST['kebiasaan_tidur_sebelum']   ?? '',
        'kebiasaan_tidur_sekarang'  => $_POST['kebiasaan_tidur_sekarang']  ?? '',
        // n. Personal Hygiene
        'mandi_frekuensi_sebelum'   => $_POST['mandi_frekuensi_sebelum']   ?? '',
        'mandi_frekuensi_sekarang'  => $_POST['mandi_frekuensi_sekarang']  ?? '',
        'mandi_cara_sebelum'        => $_POST['mandi_cara_sebelum']        ?? '',
        'mandi_cara_sekarang'       => $_POST['mandi_cara_sekarang']       ?? '',
        'mandi_tempat_sebelum'      => $_POST['mandi_tempat_sebelum']      ?? '',
        'mandi_tempat_sekarang'     => $_POST['mandi_tempat_sekarang']     ?? '',
        'rambut_frekuensi_sebelum'  => $_POST['rambut_frekuensi_sebelum']  ?? '',
        'rambut_frekuensi_sekarang' => $_POST['rambut_frekuensi_sekarang'] ?? '',
        'rambut_cara_sebelum'       => $_POST['rambut_cara_sebelum']       ?? '',
        'rambut_cara_sekarang'      => $_POST['rambut_cara_sekarang']      ?? '',
        'kuku_frekuensi_sebelum'    => $_POST['kuku_frekuensi_sebelum']    ?? '',
        'kuku_frekuensi_sekarang'   => $_POST['kuku_frekuensi_sekarang']   ?? '',
        'kuku_cara_sebelum'         => $_POST['kuku_cara_sebelum']         ?? '',
        'kuku_cara_sekarang'        => $_POST['kuku_cara_sekarang']        ?? '',
        'gigi_frekuensi_sebelum'    => $_POST['gigi_frekuensi_sebelum']    ?? '',
        'gigi_frekuensi_sekarang'   => $_POST['gigi_frekuensi_sekarang']   ?? '',
        'gigi_cara_sebelum'         => $_POST['gigi_cara_sebelum']         ?? '',
        'gigi_cara_sekarang'        => $_POST['gigi_cara_sekarang']        ?? '',
    ];

    if (!$submission) {
        $submission_id = createSubmission($user_id, $form_id, $tgl_pengkajian, $rs_ruangan, $mysqli);
    } else {
        $submission_id = $submission['id'];
    }

    saveSection($submission_id, $section_name, $section_label, $data, $mysqli);
    updateSubmissionStatus($submission_id, $form_id, $mysqli);
    redirectWithMessage($_SERVER['REQUEST_URI'], 'success', 'Data berhasil disimpan.');
}

// =============================================
// HANDLE POST - DOSEN APPROVE / REVISI
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

// Load komentar (untuk dosen & mahasiswa)
$comments = $submission ? getSectionComments($submission['id'], $section_name, $mysqli) : [];
?>

<main id="main" class="main">
    <?php include "kmb/format_kmb/tab.php"; ?>

    <section class="section dashboard">

        <!-- NOTIFIKASI -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?= $_SESSION['success'];
                                                unset($_SESSION['success']); ?></div>
        <?php endif; ?>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= $_SESSION['error'];
                                            unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <!-- Info status section -->
        <?php if ($section_status): ?>
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
                | Reviewed by: <strong><?= $submission['dosen_name'] ? htmlspecialchars($submission['dosen_name']) : '-' ?></strong>
            </div>
        <?php endif; ?>

        <!-- 4 POLA PENGKAJIAN FX GORDON -->

        <div class="card">
            <div class="card-body">

                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

                    <h5 class="card-title"><strong>4. Pola Pengkajian FX Gordon</strong></h5>
                    <!-- A PERSEPSI KESEHATAN -->
                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary"><strong>a. Persepsi terhadap kesehatan dan manajemen kesehatan</strong></label>
                    </div>

                    <!-- 1 -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>1. Merokok / Alkohol?</strong></label>
                        <div class="col-sm-9">
                            <textarea class="form-control" rows="3" name="merokok" style="display:block; overflow:hidden; resize: none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= htmlspecialchars($existing_data['merokok'] ?? '') ?></textarea>
                        </div>
                    </div>

                    <!-- 2 -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>2. Pemeriksaan kesehatan rutin?</strong></label>
                        <div class="col-sm-9">
                            <textarea class="form-control" rows="3" name="pemeriksaan_rutin" style="display:block; overflow:hidden; resize: none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= htmlspecialchars($existing_data['pemeriksaan_rutin'] ?? '') ?></textarea>
                        </div>
                    </div>

                    <!-- 3 -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>3. Pendapat pasien tentang keadaan kesehatannya saat ini</strong></label>
                        <div class="col-sm-9">
                            <textarea class="form-control" rows="4" name="pendapat_kesehatan" style="display:block; overflow:hidden; resize: none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= htmlspecialchars($existing_data['pendapat_kesehatan'] ?? '') ?></textarea>
                        </div>
                    </div>

                    <!-- 4 -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>4. Persepsi pasien tentang berat ringannya penyakit</strong></label>
                        <div class="col-sm-9">
                            <textarea class="form-control" rows="4" name="persepsi_penyakit" style="display:block; overflow:hidden; resize: none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= htmlspecialchars($existing_data['persepsi_penyakit'] ?? '') ?></textarea>
                        </div>
                    </div>

                    <!-- 5 -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>5. Persepsi tentang tingkat kesembuhan</strong></label>
                        <div class="col-sm-9">
                            <textarea class="form-control" rows="3" name="tingkat_kesembuhan" style="display:block; overflow:hidden; resize: none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= htmlspecialchars($existing_data['tingkat_kesembuhan'] ?? '') ?></textarea>
                        </div>
                    </div>

                    <!-- B POLA AKTIVITAS -->
                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary"><strong>b. Pola Aktivitas dan Latihan</strong></label>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>1. Rutinitas mandi</strong></label>
                        <div class="col-sm-9">
                            <small class="form-text" style="color:red;">kapan, bagaimana, dimana, sabun yang digunakan</small>
                            <input class="form-control" name="rutinitas_mandi" <?= $ro ?> value="<?= htmlspecialchars($existing_data['rutinitas_mandi'] ?? '') ?>">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>2. Kebersihan sehari-hari</strong></label>
                        <div class="col-sm-9">
                            <small class="form-text" style="color:red;">pakaian dll</small>
                            <input class="form-control" name="kebersihan" <?= $ro ?> value="<?= htmlspecialchars($existing_data['kebersihan'] ?? '') ?>">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>3. Aktivitas sehari-hari</strong></label>
                        <div class="col-sm-9">
                            <small class="form-text" style="color:red;">jenis pekerjaan, lamanya, dll</small>
                            <input class="form-control" name="aktivitas" <?= $ro ?> value="<?= htmlspecialchars($existing_data['aktivitas'] ?? '') ?>">
                        </div>
                    </div>

                    <!-- TABEL KEMAMPUAN PERAWATAN DIRI -->
                    <div class="row mb-3">
                        <label class="col-sm-12"><strong>4. Kemampuan Perawatan Diri</strong></label>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th><strong>Aktivitas</strong></th>
                                    <th class="text-center"><strong>0</strong></th>
                                    <th class="text-center"><strong>1</strong></th>
                                    <th class="text-center"><strong>2</strong></th>
                                    <th class="text-center"><strong>3</strong></th>
                                    <th class="text-center"><strong>4</strong></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $perawatan_fields = [
                                    'mandi'      => 'Mandi',
                                    'berpakaian' => 'Berpakaian / Berdandan',
                                    'mobilisasi' => 'Mobilisasi di TT',
                                    'pindah'     => 'Pindah',
                                    'ambulasi'   => 'Ambulasi',
                                    'makan'      => 'Makan / Minum',
                                ];
                                foreach ($perawatan_fields as $name => $label): ?>
                                    <tr>
                                        <td><strong><?= $label ?></strong></td>
                                        <?php for ($i = 0; $i <= 4; $i++): ?>
                                            <td class="text-center"><input type="radio" name="<?= $name ?>" value="<?= $i ?>" <?= $ro_disabled ?>
                                                    <?= ($existing_data[$name] ?? '') == $i ? 'checked' : '' ?>></td>
                                        <?php endfor; ?>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>

                        <small class="text-muted d-block text-end">
                            Skor 0 = Mandiri &nbsp;|&nbsp; Skor 1 = Dibantu sebagian &nbsp;|&nbsp; Skor 2 = Perlu bantuan orang lain <br>
                            Skor 3 = Bantuan orang lain dan alat &nbsp;|&nbsp; Skor 4 = Tergantung
                        </small>


                        <div class="row mb-2">
                            <label class="col-sm-12 text-primary"><strong>c. Pola Kognitif dan Perceptual</strong></label>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">
                                <strong>1. Nyeri (kualitas, intensitas, durasi, skala nyeri, cara mengurangi nyeri)</strong>
                            </label>
                            <div class="col-sm-9">
                                <textarea class="form-control" rows="4" cols="30" name="nyeri" style="display:block; overflow:hidden; resize: none;" <?= $ro ?>
                                    oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= htmlspecialchars($existing_data["nyeri"] ?? "") ?></textarea>

                            </div>
                        </div>


                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">
                                <strong>2. Fungsi panca indra (penglihatan, pendengaran, pengecapan, penghidu, perasa) menggunakan alat bantu?</strong>
                            </label>
                            <div class="col-sm-9">
                                <textarea class="form-control" rows="7" cols="30" name="panca_indra" style="display:block; overflow:hidden; resize: none;" <?= $ro ?>
                                    oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= htmlspecialchars($existing_data["panca_indra"] ?? "") ?></textarea>

                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">
                                <strong>3. Kemampuan berbicara</strong>
                            </label>
                            <div class="col-sm-9">
                                <textarea class="form-control" rows="2" cols="30" name="berbicara" style="display:block; overflow:hidden; resize: none;" <?= $ro ?>
                                    oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= htmlspecialchars($existing_data["berbicara"] ?? "") ?></textarea>

                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">
                                <strong>4. Kemampuan membaca</strong>
                            </label>
                            <div class="col-sm-9">
                                <textarea class="form-control" rows="2" cols="30" name="membaca" style="display:block; overflow:hidden; resize: none;" <?= $ro ?>
                                    oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= htmlspecialchars($existing_data["membaca"] ?? "") ?></textarea>

                            </div>
                        </div>


                        <div class="row mb-2">
                            <label class="col-sm-12 text-primary"><strong>d. Pola Konsep Diri</strong></label>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">
                                <strong>1. Bagaimana klien memandang dirinya</strong>
                            </label>
                            <div class="col-sm-9">
                                <textarea class="form-control" rows="3" cols="30" name="konsep_diri" style="display:block; overflow:hidden; resize: none;" <?= $ro ?>
                                    oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= htmlspecialchars($existing_data["konsep_diri"] ?? "") ?></textarea>

                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">
                                <strong>2. Hal-hal yang disukai klien mengenai dirinya</strong>
                            </label>
                            <div class="col-sm-9">
                                <textarea class="form-control" rows="3" cols="30" name="hal_disukai" style="display:block; overflow:hidden; resize: none;" <?= $ro ?>
                                    oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= htmlspecialchars($existing_data["hal_disukai"] ?? "") ?></textarea>

                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">
                                <strong>3. Apakah klien dapat mengidentifikasi kekuatan dan kelemahan dirinya</strong>
                            </label>
                            <div class="col-sm-9">
                                <textarea class="form-control" rows="6" cols="30" name="kekuatan_kelemahan" style="display:block; overflow:hidden; resize: none;" <?= $ro ?>
                                    oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= htmlspecialchars($existing_data["kekuatan_kelemahan"] ?? "") ?></textarea>

                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">
                                <strong>4. Hal-hal yang dapat dilakukan klien secara baik</strong>
                            </label>
                            <div class="col-sm-9">
                                <textarea class="form-control" rows="3" cols="30" name="kemampuan_baik" style="display:block; overflow:hidden; resize: none;" <?= $ro ?>
                                    oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= htmlspecialchars($existing_data["kemampuan_baik"] ?? "") ?></textarea>

                            </div>
                        </div>



                        <div class="row mb-2">
                            <label class="col-sm-12 text-primary"><strong>e. Pola Koping</strong></label>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">
                                <strong>1. Masalah utama selama masuk RS (keuangan, dll)</strong>
                            </label>
                            <div class="col-sm-9">
                                <textarea class="form-control" rows="3" cols="30" name="masalah_rs" style="display:block; overflow:hidden; resize: none;" <?= $ro ?>
                                    oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= htmlspecialchars($existing_data["masalah_rs"] ?? "") ?></textarea>

                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">
                                <strong>2. Kehilangan atau perubahan yang terjadi sebelumnya</strong>
                            </label>
                            <div class="col-sm-9">
                                <textarea class="form-control" rows="4" cols="30" name="kehilangan" style="display:block; overflow:hidden; resize: none;" <?= $ro ?>
                                    oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= htmlspecialchars($existing_data["kehilangan"] ?? "") ?></textarea>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">
                                <strong>3. Takut terhadap kekerasan</strong>
                            </label>
                            <div class="col-sm-9">
                                <textarea class="form-control" rows="2" cols="30" name="takut_kekerasan" style="display:block; overflow:hidden; resize: none;" <?= $ro ?>
                                    oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= htmlspecialchars($existing_data["takut_kekerasan"] ?? "") ?></textarea>

                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">
                                <strong>4. Pandangan terhadap masa depan</strong>
                            </label>
                            <div class="col-sm-9">
                                <textarea class="form-control" rows="3" cols="30" name="masa_depan" style="display:block; overflow:hidden; resize: none;" <?= $ro ?>
                                    oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= htmlspecialchars($existing_data["masa_depan"] ?? "") ?></textarea>

                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">
                                <strong>5. Mekanisme koping saat menghadapi masalah</strong>
                            </label>
                            <div class="col-sm-9">
                                <textarea class="form-control" rows="4" cols="30" name="mekanisme_koping" style="display:block; overflow:hidden; resize: none;" <?= $ro ?>
                                    oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= htmlspecialchars($existing_data["mekanisme_koping"] ?? "") ?></textarea>

                            </div>
                        </div>
                        <div class="row mb-2">
                            <label class="col-sm-12 text-primary"><strong>f. Pola Seksual - Reproduksi</strong></label>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">
                                <strong>1. Masalah menstruasi</strong>
                            </label>
                            <div class="col-sm-9">
                                <textarea class="form-control" rows="2" cols="30" name="masalah_menstruasi" style="display:block; overflow:hidden; resize: none;" <?= $ro ?>
                                    oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= htmlspecialchars($existing_data["masalah_menstruasi"] ?? "") ?></textarea>

                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">
                                <strong>2. Papsmear terakhir</strong>
                            </label>
                            <div class="col-sm-9">
                                <textarea class="form-control" rows="2" cols="30" name="papsmear" style="display:block; overflow:hidden; resize: none;" <?= $ro ?>
                                    oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= htmlspecialchars($existing_data["papsmear"] ?? "") ?></textarea>

                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">
                                <strong>3. Perawatan payudara setiap bulan</strong>
                            </label>
                            <div class="col-sm-9">
                                <textarea class="form-control" rows="3" cols="30" name="perawatan_payudara" style="display:block; overflow:hidden; resize: none;" <?= $ro ?>
                                    oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= htmlspecialchars($existing_data["perawatan_payudara"] ?? "") ?></textarea>

                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">
                                <strong>4. Apakah ada kesukaran dalam berhubungan seksual</strong>
                            </label>
                            <div class="col-sm-9">
                                <textarea class="form-control" rows="4" cols="30" name="kesulitan_seksual" style="display:block; overflow:hidden; resize: none;" <?= $ro ?>
                                    oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= htmlspecialchars($existing_data["kesulitan_seksual"] ?? "") ?></textarea>

                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">
                                <strong>5. Apakah penyakit sekarang mengganggu fungsi seksual</strong>
                            </label>
                            <div class="col-sm-9">
                                <textarea class="form-control" rows="5" cols="30" name="gangguan_seksual" style="display:block; overflow:hidden; resize: none;" <?= $ro ?>
                                    oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= htmlspecialchars($existing_data["gangguan_seksual"] ?? "") ?></textarea>

                            </div>
                        </div>

                        <div class="row mb-2">
                            <label class="col-sm-12 text-primary"><strong>g. Pola Peran Berhubungan</strong></label>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">
                                <strong>1. Peran pasien dalam keluarga dan masyarakat</strong>
                            </label>
                            <div class="col-sm-9">
                                <textarea class="form-control" rows="3" cols="30" name="peran_pasien" style="display:block; overflow:hidden; resize: none;" <?= $ro ?>
                                    oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= htmlspecialchars($existing_data["peran_pasien"] ?? "") ?></textarea>

                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">
                                <strong>2. Apakah klien punya teman dekat</strong>
                            </label>
                            <div class="col-sm-9">
                                <textarea class="form-control" rows="3" cols="30" name="teman_dekat" style="display:block; overflow:hidden; resize: none;" <?= $ro ?>
                                    oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= htmlspecialchars($existing_data["teman_dekat"] ?? "") ?></textarea>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">
                                <strong>3. Siapa yang dipercaya membantu klien saat kesulitan</strong>
                            </label>
                            <div class="col-sm-9">
                                <textarea class="form-control" rows="4" cols="30" name="orang_terpercaya" style="display:block; overflow:hidden; resize: none;" <?= $ro ?>
                                    oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= htmlspecialchars($existing_data["orang_terpercaya"] ?? "") ?></textarea>

                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">
                                <strong>4. Apakah klien ikut kegiatan masyarakat? Bagaimana keterlibatannya</strong>
                            </label>
                            <div class="col-sm-9">
                                <textarea class="form-control" rows="5" cols="30" name="kegiatan_masyarakat" style="display:block; overflow:hidden; resize: none;" <?= $ro ?>
                                    oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= htmlspecialchars($existing_data["kegiatan_masyarakat"] ?? "") ?></textarea>

                            </div>
                        </div>

                        <div class="row mb-2">
                            <label class="col-sm-12 text-primary"><strong>h. Pola Nilai dan Kepercayaan</strong></label>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">
                                <strong>1. Apakah klien menganut suatu agama?</strong>
                            </label>
                            <div class="col-sm-9">
                                <textarea class="form-control" rows="3" cols="30" name="agama_klien" style="display:block; overflow:hidden; resize: none;" <?= $ro ?>
                                    oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= htmlspecialchars($existing_data["agama_klien"] ?? "") ?></textarea>

                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">
                                <strong>2. Menurut agama klien bagaimana hubungan manusia dengan pencipta-Nya?</strong>
                            </label>
                            <div class="col-sm-9">
                                <textarea class="form-control" rows="5" cols="30" name="hubungan_tuhan" style="display:block; overflow:hidden; resize: none;" <?= $ro ?>
                                    oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= htmlspecialchars($existing_data["hubungan_tuhan"] ?? "") ?></textarea>

                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">
                                <strong>3. Dalam keadaan sakit apakah klien mengalami hambatan dalam ibadah?</strong>
                            </label>
                            <div class="col-sm-9">
                                <textarea class="form-control" rows="5" cols="30" name="hambatan_ibadah" style="display:block; overflow:hidden; resize: none;" <?= $ro ?>
                                    oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= htmlspecialchars($existing_data["hambatan_ibadah"] ?? "") ?></textarea>

                            </div>
                        </div>

                        <div class="row mb-2">
                            <label class="col-sm-12 text-primary"><strong>i. Pola Nutrisi</strong></label>
                        </div>

                        <div class="row mb-4">
                            <div class="col-sm-11">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th><strong>No</strong></th>
                                                <th><strong>Kondisi</strong></th>
                                                <th><strong>Sebelum</strong></th>
                                                <th><strong>Saat Ini</strong></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td><strong>Frekuensi Makan</strong></td>
                                                <td><input type="text" class="form-control" name="frekuensi_makan_sebelum" value="<?= htmlspecialchars($existing_data["frekuensi_makan_sebelum"] ?? "") ?>"></td>
                                                <td><input type="text" class="form-control" name="frekuensi_makan_sekarang" value="<?= htmlspecialchars($existing_data["frekuensi_makan_sekarang"] ?? "") ?>"></td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td><strong>Selera Makan</strong></td>
                                                <td><input type="text" class="form-control" name="selera_makan_sebelum" value="<?= htmlspecialchars($existing_data["selera_makan_sebelum"] ?? "") ?>"></td>
                                                <td><input type="text" class="form-control" name="selera_makan_sekarang" value="<?= htmlspecialchars($existing_data["selera_makan_sekarang"] ?? "") ?>"></td>
                                            </tr>
                                            <tr>
                                                <td>3</td>
                                                <td><strong>Menu Makanan</strong></td>
                                                <td><input type="text" class="form-control" name="menu_makan_sebelum" value="<?= htmlspecialchars($existing_data["menu_makan_sebelum"] ?? "") ?>"></td>
                                                <td><input type="text" class="form-control" name="menu_makan_sekarang" value="<?= htmlspecialchars($existing_data["menu_makan_sekarang"] ?? "") ?>"></td>
                                            </tr>
                                            <tr>
                                                <td>4</td>
                                                <td><strong>Ritual Saat Makan</strong></td>
                                                <td><input type="text" class="form-control" name="ritual_makan_sebelum" value="<?= htmlspecialchars($existing_data["ritual_makan_sebelum"] ?? "") ?>"></td>
                                                <td><input type="text" class="form-control" name="ritual_makan_sekarang" value="<?= htmlspecialchars($existing_data["ritual_makan_sekarang"] ?? "") ?>"></td>
                                            </tr>
                                            <tr>
                                                <td>5</td>
                                                <td><strong>Bantuan Makan Parenteral</strong></td>
                                                <td><input type="text" class="form-control" name="bantuan_makan_sebelum" value="<?= htmlspecialchars($existing_data["bantuan_makan_sebelum"] ?? "") ?>"></td>
                                                <td><input type="text" class="form-control" name="bantuan_makan_sekarang" value="<?= htmlspecialchars($existing_data["bantuan_makan_sekarang"] ?? "") ?>"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>


                        <div class="row mb-2">
                            <label class="col-sm-12 text-primary"><strong>j. Cairan</strong></label>
                        </div>

                        <div class="row mb-4">
                            <div class="col-sm-11">
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
                                                <td>1</td>
                                                <td><strong>Jenis Minuman</strong></td>
                                                <td><input type="text" class="form-control" name="jenis_minum_sebelum" value="<?= htmlspecialchars($existing_data["jenis_minum_sebelum"] ?? "") ?>"></td>
                                                <td><input type="text" class="form-control" name="jenis_minum_sekarang" value="<?= htmlspecialchars($existing_data["jenis_minum_sekarang"] ?? "") ?>"></td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td><strong>Jumlah Cairan</strong></td>
                                                <td><input type="text" class="form-control" name="jumlah_cairan_sebelum" value="<?= htmlspecialchars($existing_data["jumlah_cairan_sebelum"] ?? "") ?>"></td>
                                                <td><input type="text" class="form-control" name="jumlah_cairan_sekarang" value="<?= htmlspecialchars($existing_data["jumlah_cairan_sekarang"] ?? "") ?>"></td>
                                            </tr>
                                            <tr>
                                                <td>3</td>
                                                <td><strong>Bantuan Cairan Parenteral</strong></td>
                                                <td><input type="text" class="form-control" name="bantuan_cairan_sebelum" value="<?= htmlspecialchars($existing_data["bantuan_cairan_sebelum"] ?? "") ?>"></td>
                                                <td><input type="text" class="form-control" name="bantuan_cairan_sekarang" value="<?= htmlspecialchars($existing_data["bantuan_cairan_sekarang"] ?? "") ?>"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                        <div class="row mb-2">
                            <label class="col-sm-12 text-primary"><strong>k. Pola Eliminasi BAB</strong></label>
                        </div>
                        <div class="row mb-4">
                            <div class="col-sm-11">
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
                                                <td>1</td>
                                                <td><strong>Frekuensi (Waktu)</strong></td>
                                                <td><input type="text" class="form-control" name="bab_frekuensi_sebelum" value="<?= htmlspecialchars($existing_data["bab_frekuensi_sebelum"] ?? "") ?>"></td>
                                                <td><input type="text" class="form-control" name="bab_frekuensi_sekarang" value="<?= htmlspecialchars($existing_data["bab_frekuensi_sekarang"] ?? "") ?>"></td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td><strong>Konsistensi</strong></td>
                                                <td><input type="text" class="form-control" name="bab_konsistensi_sebelum" value="<?= htmlspecialchars($existing_data["bab_konsistensi_sebelum"] ?? "") ?>"></td>
                                                <td><input type="text" class="form-control" name="bab_konsistensi_sekarang" value="<?= htmlspecialchars($existing_data["bab_konsistensi_sekarang"] ?? "") ?>"></td>
                                            </tr>
                                            <tr>
                                                <td>3</td>
                                                <td><strong>Warna</strong></td>
                                                <td><input type="text" class="form-control" name="bab_warna_sebelum" value="<?= htmlspecialchars($existing_data["bab_warna_sebelum"] ?? "") ?>"></td>
                                                <td><input type="text" class="form-control" name="bab_warna_sekarang" value="<?= htmlspecialchars($existing_data["bab_warna_sekarang"] ?? "") ?>"></td>
                                            </tr>
                                            <tr>
                                                <td>4</td>
                                                <td><strong>Bau</strong></td>
                                                <td><input type="text" class="form-control" name="bab_bau_sebelum" value="<?= htmlspecialchars($existing_data["bab_bau_sebelum"] ?? "") ?>"></td>
                                                <td><input type="text" class="form-control" name="bab_bau_sekarang" value="<?= htmlspecialchars($existing_data["bab_bau_sekarang"] ?? "") ?>"></td>
                                            </tr>
                                            <tr>
                                                <td>5</td>
                                                <td><strong>Kesulitan saat BAB</strong></td>
                                                <td><input type="text" class="form-control" name="bab_kesulitan_sebelum" value="<?= htmlspecialchars($existing_data["bab_kesulitan_sebelum"] ?? "") ?>"></td>
                                                <td><input type="text" class="form-control" name="bab_kesulitan_sekarang" value="<?= htmlspecialchars($existing_data["bab_kesulitan_sekarang"] ?? "") ?>"></td>
                                            </tr>
                                            <tr>
                                                <td>6</td>
                                                <td><strong>Penggunaan Obat Pencahar</strong></td>
                                                <td><input type="text" class="form-control" name="bab_obat_sebelum" value="<?= htmlspecialchars($existing_data["bab_obat_sebelum"] ?? "") ?>"></td>
                                                <td><input type="text" class="form-control" name="bab_obat_sekarang" value="<?= htmlspecialchars($existing_data["bab_obat_sekarang"] ?? "") ?>"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>

                        <hr>

                        <div class="row mb-2">
                            <label class="col-sm-12 text-primary"><strong>l. Pola Eliminasi BAK</strong></label>
                        </div>
                        <div class="row mb-4">
                            <div class="col-sm-11">
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
                                                <td>1</td>
                                                <td><strong>Frekuensi (Waktu)</strong></td>
                                                <td><input type="text" class="form-control" name="bak_frekuensi_sebelum" value="<?= htmlspecialchars($existing_data["bak_frekuensi_sebelum"] ?? "") ?>"></td>
                                                <td><input type="text" class="form-control" name="bak_frekuensi_sekarang" value="<?= htmlspecialchars($existing_data["bak_frekuensi_sekarang"] ?? "") ?>"></td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td><strong>Warna</strong></td>
                                                <td><input type="text" class="form-control" name="bak_warna_sebelum" value="<?= htmlspecialchars($existing_data["bak_warna_sebelum"] ?? "") ?>"></td>
                                                <td><input type="text" class="form-control" name="bak_warna_sekarang" value="<?= htmlspecialchars($existing_data["bak_warna_sekarang"] ?? "") ?>"></td>
                                            </tr>
                                            <tr>
                                                <td>3</td>
                                                <td><strong>Bau</strong></td>
                                                <td><input type="text" class="form-control" name="bak_bau_sebelum" value="<?= htmlspecialchars($existing_data["bak_bau_sebelum"] ?? "") ?>"></td>
                                                <td><input type="text" class="form-control" name="bak_bau_sekarang" value="<?= htmlspecialchars($existing_data["bak_bau_sekarang"] ?? "") ?>"></td>
                                            </tr>
                                            <tr>
                                                <td>4</td>
                                                <td><strong>Kesulitan saat BAK</strong></td>
                                                <td><input type="text" class="form-control" name="bak_kesulitan_sebelum" value="<?= htmlspecialchars($existing_data["bak_kesulitan_sebelum"] ?? "") ?>"></td>
                                                <td><input type="text" class="form-control" name="bak_kesulitan_sekarang" value="<?= htmlspecialchars($existing_data["bak_kesulitan_sekarang"] ?? "") ?>"></td>
                                            </tr>
                                            <tr>
                                                <td>5</td>
                                                <td><strong>Penggunaan Obat Diuretik</strong></td>
                                                <td><input type="text" class="form-control" name="bak_obat_sebelum" value="<?= htmlspecialchars($existing_data["bak_obat_sebelum"] ?? "") ?>"></td>
                                                <td><input type="text" class="form-control" name="bak_obat_sekarang" value="<?= htmlspecialchars($existing_data["bak_obat_sekarang"] ?? "") ?>"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>



                        <div class="row mb-2">
                            <label class="col-sm-12 text-primary"><strong>m. Pola Tidur</strong></label>
                        </div>
                        <div class="row mb-4">
                            <div class="col-sm-11">
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
                                                <td><input type="text" class="form-control" name="tidur_siang_sebelum" value="<?= htmlspecialchars($existing_data["tidur_siang_sebelum"] ?? "") ?>"></td>
                                                <td><input type="text" class="form-control" name="tidur_siang_sekarang" value="<?= htmlspecialchars($existing_data["tidur_siang_sekarang"] ?? "") ?>"></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Jam Tidur - Malam</strong></td>
                                                <td><input type="text" class="form-control" name="tidur_malam_sebelum" value="<?= htmlspecialchars($existing_data["tidur_malam_sebelum"] ?? "") ?>"></td>
                                                <td><input type="text" class="form-control" name="tidur_malam_sekarang" value="<?= htmlspecialchars($existing_data["tidur_malam_sekarang"] ?? "") ?>"></td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td><strong>Kesulitan Tidur</strong></td>
                                                <td><input type="text" class="form-control" name="kesulitan_tidur_sebelum" value="<?= htmlspecialchars($existing_data["kesulitan_tidur_sebelum"] ?? "") ?>"></td>
                                                <td><input type="text" class="form-control" name="kesulitan_tidur_sekarang" value="<?= htmlspecialchars($existing_data["kesulitan_tidur_sekarang"] ?? "") ?>"></td>
                                            </tr>
                                            <tr>
                                                <td>3</td>
                                                <td><strong>Kebiasaan Sebelum Tidur</strong></td>
                                                <td><input type="text" class="form-control" name="kebiasaan_tidur_sebelum" value="<?= htmlspecialchars($existing_data["kebiasaan_tidur_sebelum"] ?? "") ?>"></td>
                                                <td><input type="text" class="form-control" name="kebiasaan_tidur_sekarang" value="<?= htmlspecialchars($existing_data["kebiasaan_tidur_sekarang"] ?? "") ?>"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>



                        <div class="row mb-2">
                            <label class="col-sm-12 text-primary"><strong>n. Pola Personal Hygiene</strong></label>
                        </div>
                        <div class="row mb-4">
                            <div class="col-sm-11">
                                <div class="table">
                                    <table class="table table-bordered table-hover mb-1">
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
                                                <td><input type="text" class="form-control" name="mandi_frekuensi_sebelum" value="<?= htmlspecialchars($existing_data["mandi_frekuensi_sebelum"] ?? "") ?>"></td>
                                                <td><input type="text" class="form-control" name="mandi_frekuensi_sekarang" value="<?= htmlspecialchars($existing_data["mandi_frekuensi_sekarang"] ?? "") ?>"></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Mandi - Cara</strong></td>
                                                <td><input type="text" class="form-control" name="mandi_cara_sebelum" value="<?= htmlspecialchars($existing_data["mandi_cara_sebelum"] ?? "") ?>"></td>
                                                <td><input type="text" class="form-control" name="mandi_cara_sekarang" value="<?= htmlspecialchars($existing_data["mandi_cara_sekarang"] ?? "") ?>"></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Mandi - Tempat</strong></td>
                                                <td><input type="text" class="form-control" name="mandi_tempat_sebelum" value="<?= htmlspecialchars($existing_data["mandi_tempat_sebelum"] ?? "") ?>"></td>
                                                <td><input type="text" class="form-control" name="mandi_tempat_sekarang" value="<?= htmlspecialchars($existing_data["mandi_tempat_sekarang"] ?? "") ?>"></td>
                                            </tr>
                                            <tr>
                                                <td rowspan="2">2</td>
                                                <td><strong>Cuci Rambut - Frekuensi</strong></td>
                                                <td><input type="text" class="form-control" name="rambut_frekuensi_sebelum" value="<?= htmlspecialchars($existing_data["rambut_frekuensi_sebelum"] ?? "") ?>"></td>
                                                <td><input type="text" class="form-control" name="rambut_frekuensi_sekarang" value="<?= htmlspecialchars($existing_data["rambut_frekuensi_sekarang"] ?? "") ?>"></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Cuci Rambut - Cara</strong></td>
                                                <td><input type="text" class="form-control" name="rambut_cara_sebelum" value="<?= htmlspecialchars($existing_data["rambut_cara_sebelum"] ?? "") ?>"></td>
                                                <td><input type="text" class="form-control" name="rambut_cara_sekarang" value="<?= htmlspecialchars($existing_data["rambut_cara_sekarang"] ?? "") ?>"></td>
                                            </tr>
                                            <tr>
                                                <td rowspan="2">3</td>
                                                <td><strong>Gunting Kuku - Frekuensi</strong></td>
                                                <td><input type="text" class="form-control" name="kuku_frekuensi_sebelum" value="<?= htmlspecialchars($existing_data["kuku_frekuensi_sebelum"] ?? "") ?>"></td>
                                                <td><input type="text" class="form-control" name="kuku_frekuensi_sekarang" value="<?= htmlspecialchars($existing_data["kuku_frekuensi_sekarang"] ?? "") ?>"></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Gunting Kuku - Cara</strong></td>
                                                <td><input type="text" class="form-control" name="kuku_cara_sebelum" value="<?= htmlspecialchars($existing_data["kuku_cara_sebelum"] ?? "") ?>"></td>
                                                <td><input type="text" class="form-control" name="kuku_cara_sekarang" value="<?= htmlspecialchars($existing_data["kuku_cara_sekarang"] ?? "") ?>"></td>
                                            </tr>
                                            <tr>
                                                <td rowspan="2">4</td>
                                                <td><strong>Gosok Gigi - Frekuensi</strong></td>
                                                <td><input type="text" class="form-control" name="gigi_frekuensi_sebelum" value="<?= htmlspecialchars($existing_data["gigi_frekuensi_sebelum"] ?? "") ?>"></td>
                                                <td><input type="text" class="form-control" name="gigi_frekuensi_sekarang" value="<?= htmlspecialchars($existing_data["gigi_frekuensi_sekarang"] ?? "") ?>"></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Gosok Gigi - Cara</strong></td>
                                                <td><input type="text" class="form-control" name="gigi_cara_sebelum" value="<?= htmlspecialchars($existing_data["gigi_cara_sebelum"] ?? "") ?>"></td>
                                                <td><input type="text" class="form-control" name="gigi_cara_sekarang" value="<?= htmlspecialchars($existing_data["gigi_cara_sekarang"] ?? "") ?>"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>



                        <!-- TOMBOL SIMPAN (hanya mahasiswa) -->
                        <?php if (!$is_dosen): ?>
                            <div class="row mb-3">
                                <div class="col-sm-12 d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary" <?= $ro_disabled ?>>Simpan Data</button>
                                </div>
                            </div>
                        <?php endif; ?>



                </form>


                <?php include "tab_navigasi.php"; ?>
            </div>
        </div>

        <!-- SECTION KOMENTAR & ACTION DOSEN -->
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

    </section>
</main>