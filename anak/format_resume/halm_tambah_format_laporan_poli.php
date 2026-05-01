<?php
require_once "koneksi.php";
require_once "utils.php";

$form_id       = 10;
$level         = $_SESSION['level'];
$user_id       = $_SESSION['id_user'];
$section_name  = 'poli_imunisasi';
$section_label = 'Format Laporan Poli Imunisasi';

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
        'nama_anak'                   => $_POST['nama_anak'] ?? '',
        'jenis_kelamin'               => $_POST['jenis_kelamin'] ?? '',
        'umur'                        => $_POST['umur'] ?? '',
        'agama'                       => $_POST['agama'] ?? '',
        'alamat'                      => $_POST['alamat'] ?? '',
        'nama_ayah'                   => $_POST['nama_ayah'] ?? '',
        'umur_ayah'                   => $_POST['umur_ayah'] ?? '',
        'pendidikan_ayah'             => $_POST['pendidikan_ayah'] ?? '',
        'pekerjaan_ayah'              => $_POST['pekerjaan_ayah'] ?? '',
        'nama_ibu'                    => $_POST['nama_ibu'] ?? '',
        'umur_ibu'                    => $_POST['umur_ibu'] ?? '',
        'pendidikan_ibu'              => $_POST['pendidikan_ibu'] ?? '',
        'pekerjaan_ibu'               => $_POST['pekerjaan_ibu'] ?? '',
        'imunisasi_saat_ini'          => $_POST['imunisasi_saat_ini'] ?? '',
        'dosis_pemberian'             => $_POST['dosis_pemberian'] ?? '',
        'cara_pemberian'              => $_POST['cara_pemberian'] ?? '',
        'reaksi_anak'                 => $_POST['reaksi_anak'] ?? '',
        'rencana_imunisasi'           => $_POST['rencana_imunisasi'] ?? '',
        'imunisasi_didapatkan'        => $_POST['imunisasi_didapatkan'] ?? '',
        'efek_dirumah'                => $_POST['efek_dirumah'] ?? '',
        'pemberian_imunisasi'         => $_POST['pemberian_imunisasi'] ?? '',
        'riwayat_penyakit'           => $_POST['riwayat_penyakit'] ?? '',
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

    <?php include "anak/format_resume/tab.php"; ?>

    <section class="section dashboard">

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?= $_SESSION['success'];
                                                unset($_SESSION['success']); ?></div>
        <?php endif; ?>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= $_SESSION['error'];
                                            unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <!-- Info status section (untuk dosen) -->
        <?php if  ($section_status): ?>
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
                    | Reviewed by: <strong><?php echo $submission['dosen_name'] ? htmlspecialchars($submission['dosen_name']) : '-'; ?></strong>       
            </div>
        <?php endif; ?>
       
<div class="card">
             <div class="card-body">
                                
        <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

            <h5 class="card-title"><strong>FORMAT LAPORAN POLI IMUNISASI</strong></h5>

            <!-- 1. Biodata Klien -->
         
        <form class="needs-validation" novalidate action="" method="POST">

           <!-- 1. Biodata Klien -->
<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>1. Biodata Klien</strong></label>
</div>

<!-- Nama Anak -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Nama Anak</strong></label>
    <div class="col-sm-10">
        <input type="text" class="form-control" name="nama_anak"
        value="<?= val('nama_anak', $existing_data) ?>" <?= $ro ?>></div>
</div>

                <!-- Jenis Kelamin -->
                <div class="row mb-3">
                <label for="jenis_kelamin" class="col-sm-2 col-form-label"><strong>Jenis Kelamin</strong></label>

                <div class="col-sm-10">
                    <select class="form-select" name="jenis_kelamin" <?= $ro_select ?>>
                        <option value="">Pilih</option>
                        <option value="Laki-laki" <?= val('jenis_kelamin', $existing_data) === 'Laki-laki' ? 'selected' : '' ?>>Laki-laki</option>
                        <option value="Perempuan" <?= val('jenis_kelamin', $existing_data) === 'Perempuan' ? 'selected' : '' ?>>Perempuan</option>
                                        
                    </select>
                         </div>
                    </div>

<!-- Umur -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Umur</strong></label>
    <div class="col-sm-10">
        <input type="text" class="form-control" name="umur"
        value="<?= val('umur', $existing_data) ?>" <?= $ro ?>></div>
</div>

<!-- Agama -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Agama</strong></label>
    <div class="col-sm-10">
        <input type="text" class="form-control" name="agama"
        value="<?= val('agama', $existing_data) ?>" <?= $ro ?>></div>
</div>

<!-- Alamat -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Alamat</strong></label>
    <div class="col-sm-10">
        <textarea name="alamat" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
        <?= $ro ?>><?= val('alamat',$existing_data) ?></textarea></div>
</div>

<!-- 2. Biodata Orangtua -->
<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>2. Biodata Orangtua</strong></label>
</div>

<!-- Nama Ayah -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Nama Ayah</strong></label>
    <div class="col-sm-10">
        <input type="text" class="form-control" name="nama_ayah"
        value="<?= val('nama_ayah', $existing_data) ?>" <?= $ro ?>></div>
        </div>

<!-- Umur Ayah -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Umur Ayah</strong></label>
    <div class="col-sm-10">
        <input type="text" class="form-control" name="umur_ayah"
        value="<?= val('umur_ayah', $existing_data) ?>" <?= $ro ?>></div>
        </div>

<!-- Pendidikan Ayah -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Pendidikan Ayah</strong></label>
    <div class="col-sm-10">
        <input type="text" class="form-control" name="pendidikan_ayah"
        value="<?= val('pendidikan_ayah', $existing_data) ?>" <?= $ro ?>></div>
        </div>

<!-- Pekerjaan Ayah -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Pekerjaan Ayah</strong></label>
    <div class="col-sm-10">
        <input type="text" class="form-control" name="pekerjaan_ayah"
        value="<?= val('pekerjaan_ayah', $existing_data) ?>" <?= $ro ?>></div>
    </div>

<!-- Nama Ibu -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Nama Ibu</strong></label>
    <div class="col-sm-10">
        <input type="text" class="form-control" name="nama_ibu"
        value="<?= val('nama_ibu', $existing_data) ?>" <?= $ro ?>></div>
        </div>

<!-- Umur Ibu -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Umur Ibu</strong></label>
    <div class="col-sm-10">
        <input type="text" class="form-control" name="umur_ibu"
        value="<?= val('umur_ibu', $existing_data) ?>" <?= $ro ?>>
        </div>
        </div>

<!-- Pendidikan Ibu -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Pendidikan Ibu</strong></label>
    <div class="col-sm-10">
        <input type="text" class="form-control" name="pendidikan_ibu"
        value="<?= val('pendidikan_ibu', $existing_data) ?>" <?= $ro ?>></div>
        </div>

<!-- Pekerjaan Ibu -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Pekerjaan Ibu</strong></label>
    <div class="col-sm-10">
        <input type="text" class="form-control" name="pekerjaan_ibu"
        value="<?= val('pekerjaan_ibu', $existing_data) ?>" <?= $ro ?>>
            </div>
        </div>

<!-- B. Pemberian Imunisasi -->
<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>B. Pemberian Imunisasi</strong></label>
</div>

<!-- Imunisasi saat ini -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Imunisasi saat ini</strong></label>
    <div class="col-sm-10">
        <input type="text" class="form-control" name="imunisasi_saat_ini"
        value="<?= val('imunisasi_saat_ini', $existing_data) ?>" <?= $ro ?>></div>
</div>

<!-- Dosis -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Dosis pemberian</strong></label>
    <div class="col-sm-10">
        <input type="text" class="form-control" name="dosis_pemberian"
        value="<?= val('dosis_pemberian', $existing_data) ?>" <?= $ro ?>></div>
</div>

<!-- Cara -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Cara pemberian</strong></label>
    <div class="col-sm-10">
        <input type="text" class="form-control" name="cara_pemberian"
        value="<?= val('cara_pemberian', $existing_data) ?>" <?= $ro ?>></div>
</div>

<!-- Reaksi anak -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Reaksi anak</strong></label>
    <div class="col-sm-10">
        <textarea name="reaksi_anak" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
        <?= $ro ?>><?= val('reaksi_anak',$existing_data) ?></textarea></div>
</div>

<!-- Rencana berikut -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Rencana imunisasi pada kunjungan berikutnya</strong></label>
    <div class="col-sm-10">
        <textarea name="rencana_imunisasi" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
        <?= $ro ?>><?= val('rencana_imunisasi',$existing_data) ?></textarea></div>
</div>

<!-- Riwayat imunisasi -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Imunisasi yang sudah didapatkan</strong></label>
    <div class="col-sm-10">
    <textarea name="imunisasi_didapatkan" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
    <?= $ro ?>><?= val('imunisasi_didapatkan',$existing_data) ?></textarea></div>
</div>

<!-- Efek di rumah -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Efek yang dirasakan anak di rumah setelah pemberian imunisasi</strong></label>
    <div class="col-sm-10">
        <textarea name="efek_dirumah" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
        <?= $ro ?>><?= val('efek_dirumah',$existing_data) ?></textarea></div>
</div>

<!-- Keluhan orang tua -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Hal yang dikeluhkan orang tua setelah pemberian imunisasi</strong></label>
    <div class="col-sm-10">
       <textarea name="pemberian_imunisasi" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
       <?= $ro ?>><?= val('pemberian_imunisasi',$existing_data) ?></textarea></div>
</div>

<!-- Riwayat penyakit -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Riwayat penyakit / pengobatan yang pernah didapatkan</strong></label>
    <div class="col-sm-10">
        <textarea name="riwayat_penyakit" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
        <?= $ro ?>><?= val('riwayat_penyakit',$existing_data) ?></textarea>
</div>
</div>
<!-- TOMBOL MAHASISWA -->
                <?php if (!$is_dosen): ?>
                    <div class="row mb-3">
                        <div class="col-sm-12 d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </div>
                <?php endif; ?>

                </form>
            </div>
        </div>

        <!-- ================================ -->
        <!-- SECTION KOMENTAR & ACTION DOSEN -->
        <!-- ================================ -->
        <div class="card mt-3">
            <div class="card-body">
                <h5 class="card-title"><strong>Komentar</strong></h5>

                <!-- List komentar -->
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

                <!-- Form komentar + action (khusus dosen) -->
                <?php if ($is_dosen && $section_status !== 'approved'): ?>
                    <form action="" method="POST">
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Komentar</strong></label>
                            <div class="col-sm-10">
                                <textarea name="comment" class="form-control" rows="3"
                                    placeholder="Tulis komentar (wajib jika meminta revisi)..."></textarea>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-12 d-flex justify-content-end gap-2">
                                <button type="submit" name="action" value="revision" class="btn btn-warning">
                                    Minta Revisi
                                </button>
                                <button type="submit" name="action" value="approve" class="btn btn-success">
                                    Approve
                                </button>
                            </div>
                        </div>
                    </form>
                <?php elseif ($is_dosen && $section_status === 'approved'): ?>
                    <div class="alert alert-success">
                        Section ini sudah di-approve.
                    </div>
                <?php endif; ?>

                 <?php include "tab_navigasi.php"; ?>

            </div>
        </div>

    </section>
</main>

<script>
    const existingData = <?= json_encode($existing_data) ?>;
</script>