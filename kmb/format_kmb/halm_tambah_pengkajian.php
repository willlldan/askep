<?php
require_once "koneksi.php";
require_once "utils.php";

$form_id       = 15;
$level         = $_SESSION['level'];
$user_id       = $_SESSION['id_user'];
$section_name  = 'pengkajian';
$section_label = 'Pengkajian';

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

$kesadaran_checked = isset($existing_data['kesadaran'])
    ? (array)$existing_data['kesadaran']
    : [];
$existing_genogram = $existing_data['genogram'] ?? '';

// Readonly flags
$is_dosen    = $level === 'Dosen';
$is_readonly = $is_dosen || isLocked($submission);
$ro          = $is_readonly ? 'readonly' : '';
$ro_select   = $is_readonly ? 'disabled' : '';
$ro_check    = $is_readonly ? 'disabled' : '';

// =============================================
// HANDLE POST - MAHASISWA SIMPAN DATA
// =============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $level === 'Mahasiswa') {

    if (isLocked($submission)) {
        redirectWithMessage($_SERVER['REQUEST_URI'], 'error', 'Data tidak dapat diubah karena sedang dalam proses review.');
    }

    // Upload genogram
    $path_genogram = $existing_data['genogram'] ?? '';
    if (!empty($_FILES['genogram']['name'])) {
        $upload = uploadImage($_FILES['genogram'], 'uploads/genogram/', 50);
        if ($upload['success']) {
            if (!empty($path_genogram) && file_exists($path_genogram)) {
                unlink($path_genogram);
            }
            $path_genogram = $upload['path'];
        } else {
            redirectWithMessage($_SERVER['REQUEST_URI'], 'error', $upload['error']);
            exit;
        }
    }

    $data = [
        // 1a. Identitas Klien
        'nama_klien'                 => $_POST['nama_klien']                 ?? '',
        'ttl_umur'                   => $_POST['ttl_umur']                   ?? '',
        'jenis_kelamin'              => $_POST['jenis_kelamin']              ?? '',
        'status_perkawinan'          => $_POST['status_perkawinan']          ?? '',
        'agama'                      => $_POST['agama']                      ?? '',
        'pendidikan'                 => $_POST['pendidikan']                 ?? '',
        'pekerjaan'                  => $_POST['pekerjaan']                  ?? '',
        'alamat'                     => $_POST['alamat']                     ?? '',
        'tgl_masuk_rs'               => $_POST['tgl_masuk_rs']               ?? '',
        'diagnosa_medik'             => $_POST['diagnosa_medik']             ?? '',
        'golongan_darah'             => $_POST['golongan_darah']             ?? '',
        'no_registrasi'              => $_POST['no_registrasi']              ?? '',
        'ruangan'                    => $_POST['ruangan']                    ?? '',
        // 1b. Identitas Penanggung Jawab
        'pj_nama'                    => $_POST['pj_nama']                    ?? '',
        'pj_ttl_umur'                => $_POST['pj_ttl_umur']                ?? '',
        'pj_jenis_kelamin'           => $_POST['pj_jenis_kelamin']           ?? '',
        'pj_hubungan_klien'          => $_POST['pj_hubungan_klien']          ?? '',
        'pj_agama'                   => $_POST['pj_agama']                   ?? '',
        'pj_pendidikan'              => $_POST['pj_pendidikan']              ?? '',
        'pj_pekerjaan'               => $_POST['pj_pekerjaan']               ?? '',
        'pj_alamat'                  => $_POST['pj_alamat']                  ?? '',
        // 2. Keadaan Umum
        'nadi'                       => $_POST['nadi']                       ?? '',
        'pernafasan'                 => $_POST['pernafasan']                 ?? '',
        'td'                         => $_POST['td']                         ?? '',
        'suhu'                       => $_POST['suhu']                       ?? '',
        'gcs_m'                      => $_POST['gcs_m']                      ?? '',
        'gcs_v'                      => $_POST['gcs_v']                      ?? '',
        'gcs_e'                      => $_POST['gcs_e']                      ?? '',
        'kesadaran'                  => isset($_POST['kesadaran']) ? (array)$_POST['kesadaran'] : [],
        'bb_sebelum'                 => $_POST['bb_sebelum']                 ?? '',
        'bb_saat_sakit'              => $_POST['bb_saat_sakit']              ?? '',
        'lingkar_lengan'             => $_POST['lingkar_lengan']             ?? '',
        'tinggi_badan'               => $_POST['tinggi_badan']               ?? '',
        'imt'                        => $_POST['imt']                        ?? '',
        // 3. Riwayat Kesehatan
        'alasan_masuk_rs'            => $_POST['alasan_masuk_rs']            ?? '',
        'keluhan_utama'              => $_POST['keluhan_utama']              ?? '',
        'riwayat_keluhan_utama'      => $_POST['riwayat_keluhan_utama']      ?? '',
        'riwayat_kesehatan_lalu'     => $_POST['riwayat_kesehatan_lalu']     ?? '',
        'riwayat_kesehatan_keluarga' => $_POST['riwayat_kesehatan_keluarga'] ?? '',
        'genogram'                   => $path_genogram,
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

// Load komentar section
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

        <!-- Status badge -->
        <?php if ($section_status): ?>
            <?php $badge = ['draft' => 'secondary', 'submitted' => 'primary', 'revision' => 'warning', 'approved' => 'success']; ?>
            <div class="alert alert-<?= $badge[$section_status] ?>">
                Status: <strong><?= ucfirst($section_status) ?></strong>
                | Reviewed by: <strong><?= $submission['dosen_name'] ? htmlspecialchars($submission['dosen_name']) : '-' ?></strong>
            </div>
        <?php endif; ?>

        <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

            <!-- ===================== 1. IDENTITAS ===================== -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-1"><strong>A. PENGKAJIAN</strong></h5>
                    <h5 class="card-title mb-1"><strong>1. Identitas</strong></h5>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label text-primary"><strong>a. Klien</strong></label>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Nama (Inisial)</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="nama_klien"
                                value="<?= htmlspecialchars($existing_data['nama_klien'] ?? '') ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Tempat/Tgl Lahir/Umur</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="ttl_umur"
                                value="<?= htmlspecialchars($existing_data['ttl_umur'] ?? '') ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Jenis Kelamin</strong></label>
                        <div class="col-sm-9">
                            <select class="form-control" name="jenis_kelamin" <?= $ro_select ?>>
                                <option value="">Pilih</option>
                                <option value="Laki-laki" <?= ($existing_data['jenis_kelamin'] ?? '') === 'Laki-laki' ? 'selected' : '' ?>>Laki-laki</option>
                                <option value="Perempuan" <?= ($existing_data['jenis_kelamin'] ?? '') === 'Perempuan' ? 'selected' : '' ?>>Perempuan</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Status Perkawinan</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="status_perkawinan"
                                value="<?= htmlspecialchars($existing_data['status_perkawinan'] ?? '') ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Agama</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="agama"
                                value="<?= htmlspecialchars($existing_data['agama'] ?? '') ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Pendidikan</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="pendidikan"
                                value="<?= htmlspecialchars($existing_data['pendidikan'] ?? '') ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Pekerjaan</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="pekerjaan"
                                value="<?= htmlspecialchars($existing_data['pekerjaan'] ?? '') ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Alamat</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="alamat"
                                value="<?= htmlspecialchars($existing_data['alamat'] ?? '') ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Tanggal Masuk RS</strong></label>
                        <div class="col-sm-9">
                            <input type="date" class="form-control" name="tgl_masuk_rs"
                                value="<?= htmlspecialchars($existing_data['tgl_masuk_rs'] ?? '') ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Diagnosa Medik</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="diagnosa_medik"
                                value="<?= htmlspecialchars($existing_data['diagnosa_medik'] ?? '') ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Golongan Darah</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="golongan_darah"
                                value="<?= htmlspecialchars($existing_data['golongan_darah'] ?? '') ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>No Registrasi</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="no_registrasi"
                                value="<?= htmlspecialchars($existing_data['no_registrasi'] ?? '') ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Ruangan</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="ruangan"
                                value="<?= htmlspecialchars($existing_data['ruangan'] ?? '') ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary"><strong>b. Identitas Penanggung Jawab</strong></label>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Nama (Inisial)</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="pj_nama"
                                value="<?= htmlspecialchars($existing_data['pj_nama'] ?? '') ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Tempat/Tgl Lahir/Umur</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="pj_ttl_umur"
                                value="<?= htmlspecialchars($existing_data['pj_ttl_umur'] ?? '') ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Jenis Kelamin</strong></label>
                        <div class="col-sm-9">
                            <select class="form-control" name="pj_jenis_kelamin" <?= $ro_select ?>>
                                <option value="">Pilih</option>
                                <option value="Laki-laki" <?= ($existing_data['pj_jenis_kelamin'] ?? '') === 'Laki-laki' ? 'selected' : '' ?>>Laki-laki</option>
                                <option value="Perempuan" <?= ($existing_data['pj_jenis_kelamin'] ?? '') === 'Perempuan' ? 'selected' : '' ?>>Perempuan</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Hubungan dengan Klien</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="pj_hubungan_klien"
                                value="<?= htmlspecialchars($existing_data['pj_hubungan_klien'] ?? '') ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Agama</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="pj_agama"
                                value="<?= htmlspecialchars($existing_data['pj_agama'] ?? '') ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Pendidikan</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="pj_pendidikan"
                                value="<?= htmlspecialchars($existing_data['pj_pendidikan'] ?? '') ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Pekerjaan</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="pj_pekerjaan"
                                value="<?= htmlspecialchars($existing_data['pj_pekerjaan'] ?? '') ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Alamat</strong></label>
                        <div class="col-sm-9">
                            <textarea class="form-control" rows="3" name="pj_alamat"
                                style="overflow:hidden; resize:none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= htmlspecialchars($existing_data['pj_alamat'] ?? '') ?></textarea>
                        </div>
                    </div>

                </div>
            </div>

            <!-- ===================== 2. KEADAAN UMUM ===================== -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><strong>2. Keadaan Umum</strong></h5>

                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary"><strong>a. Tanda Vital</strong></label>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Nadi</strong></label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <input type="text" class="form-control" name="nadi"
                                    value="<?= htmlspecialchars($existing_data['nadi'] ?? '') ?>" <?= $ro ?>>
                                <span class="input-group-text">/menit</span>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Pernafasan</strong></label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <input type="text" class="form-control" name="pernafasan"
                                    value="<?= htmlspecialchars($existing_data['pernafasan'] ?? '') ?>" <?= $ro ?>>
                                <span class="input-group-text">x/menit</span>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>TD (Tekanan Darah)</strong></label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <input type="text" class="form-control" name="td"
                                    value="<?= htmlspecialchars($existing_data['td'] ?? '') ?>" <?= $ro ?>>
                                <span class="input-group-text">mmHg</span>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Suhu</strong></label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <input type="text" class="form-control" name="suhu"
                                    value="<?= htmlspecialchars($existing_data['suhu'] ?? '') ?>" <?= $ro ?>>
                                <span class="input-group-text">°C</span>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary"><strong>b. Kesadaran</strong></label>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Glasgow Coma Scale (GCS)</strong></label>
                        <div class="col-sm-9">
                            <div class="row">
                                <div class="col-md-4 d-flex align-items-center">
                                    <label class="me-2"><strong>M</strong></label>
                                    <input type="text" class="form-control" name="gcs_m"
                                        value="<?= htmlspecialchars($existing_data['gcs_m'] ?? '') ?>" <?= $ro ?>>
                                </div>
                                <div class="col-md-4 d-flex align-items-center">
                                    <label class="me-2"><strong>V</strong></label>
                                    <input type="text" class="form-control" name="gcs_v"
                                        value="<?= htmlspecialchars($existing_data['gcs_v'] ?? '') ?>" <?= $ro ?>>
                                </div>
                                <div class="col-md-4 d-flex align-items-center">
                                    <label class="me-2"><strong>E</strong></label>
                                    <input type="text" class="form-control" name="gcs_e"
                                        value="<?= htmlspecialchars($existing_data['gcs_e'] ?? '') ?>" <?= $ro ?>>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Tingkat Kesadaran</strong></label>
                        <div class="col-sm-9">
                            <?php
                            $kesadaran_options = ['Kompos Mentis', 'Apatis', 'Somnolent', 'Stupor / Suppor', 'Semikoma', 'Koma'];
                            $kesadaran_values  = ['Kompos Mentis', 'Apatis', 'Somnolent', 'Stupor', 'Semikoma', 'Koma'];
                            foreach ($kesadaran_options as $i => $label):
                                $val = $kesadaran_values[$i];
                            ?>
                                <div class="form-check-inline">
                                    <input class="form-check-input" type="checkbox"
                                        name="kesadaran[]"
                                        id="kesadaran_<?= $i ?>"
                                        value="<?= $val ?>"
                                        <?= in_array($val, $kesadaran_checked) ? 'checked' : '' ?>
                                        <?= $ro_check ?>>
                                    <label class="form-check-label" for="kesadaran_<?= $i ?>"><?= $label ?></label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>BB Sebelum Sakit</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="bb_sebelum"
                                value="<?= htmlspecialchars($existing_data['bb_sebelum'] ?? '') ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>BB Saat Sakit</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="bb_saat_sakit"
                                value="<?= htmlspecialchars($existing_data['bb_saat_sakit'] ?? '') ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Lingkar Lengan Atas</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="lingkar_lengan"
                                value="<?= htmlspecialchars($existing_data['lingkar_lengan'] ?? '') ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Tinggi Badan</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="tinggi_badan"
                                value="<?= htmlspecialchars($existing_data['tinggi_badan'] ?? '') ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Indeks Massa Tubuh (IMT)</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="imt"
                                value="<?= htmlspecialchars($existing_data['imt'] ?? '') ?>" <?= $ro ?>>
                        </div>
                    </div>

                </div>
            </div>

            <!-- ===================== 3. RIWAYAT KESEHATAN ===================== -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><strong>3. Riwayat Kesehatan</strong></h5>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>a. Alasan Masuk Rumah Sakit</strong></label>
                        <div class="col-sm-9">
                            <textarea class="form-control" rows="3" name="alasan_masuk_rs"
                                style="overflow:hidden; resize:none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= htmlspecialchars($existing_data['alasan_masuk_rs'] ?? '') ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>b. Keluhan Utama</strong></label>
                        <div class="col-sm-9">
                            <textarea class="form-control" rows="3" name="keluhan_utama"
                                style="overflow:hidden; resize:none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= htmlspecialchars($existing_data['keluhan_utama'] ?? '') ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>c. Riwayat Keluhan Utama</strong></label>
                        <div class="col-sm-9">
                            <textarea class="form-control" rows="4" name="riwayat_keluhan_utama"
                                style="overflow:hidden; resize:none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= htmlspecialchars($existing_data['riwayat_keluhan_utama'] ?? '') ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>d. Riwayat Kesehatan yang Lalu</strong></label>
                        <div class="col-sm-9">
                            <textarea class="form-control" rows="4" name="riwayat_kesehatan_lalu"
                                style="overflow:hidden; resize:none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= htmlspecialchars($existing_data['riwayat_kesehatan_lalu'] ?? '') ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>e. Riwayat Kesehatan Keluarga</strong></label>
                        <div class="col-sm-9">
                            <textarea class="form-control" rows="4" name="riwayat_kesehatan_keluarga"
                                style="overflow:hidden; resize:none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= htmlspecialchars($existing_data['riwayat_kesehatan_keluarga'] ?? '') ?></textarea>
                        </div>
                    </div>

                    <!-- f. Genogram -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>f. Genogram</strong></label>
                        <div class="col-sm-9">
                            <?php if (!empty($existing_genogram)): ?>
                                <img src="<?= htmlspecialchars($existing_genogram) ?>"
                                    class="img-fluid rounded border mb-2"
                                    style="max-height:400px;">
                            <?php endif; ?>
                            <?php if (!$is_readonly): ?>
                                <input type="file" class="form-control" name="genogram"
                                    accept="image/jpeg,image/png,image/webp">
                                <small class="text-muted">Format: JPG, PNG, WebP. Maks 2MB.</small>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- TOMBOL SIMPAN (mahasiswa only) -->
                    <?php if (!$is_dosen): ?>
                        <div class="row mb-3">
                            <div class="col-sm-12 d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">Simpan Data</button>
                            </div>
                        </div>
                    <?php endif; ?>

                </div>
            </div>

        </form>

        <!-- ================================ -->
        <!-- SECTION KOMENTAR & ACTION DOSEN -->
        <!-- ================================ -->
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

        <script>
            const existingData = <?= json_encode($existing_data) ?>;
        </script>

    </section>
</main>