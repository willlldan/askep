<?php
require_once "koneksi.php";
require_once "utils.php";

$form_id       = 19;
$level         = $_SESSION['level'];
$user_id       = $_SESSION['id_user'];
$section_name  = 'resume';
$section_label = 'Format Resume Ruang OK';

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
        'nama_klien'           => $_POST['nama_klien'] ?? '',
        'jenis_kelamin'        => $_POST['jenis_kelamin'] ?? '',
        'umur'                 => $_POST['umur'] ?? '',
        'agama'                => $_POST['agama'] ?? '',
        'status_perkawinan'    => $_POST['status_perkawinan'] ?? '',
        'pendidikan'           => $_POST['pendidikan'] ?? '',
        'pekerjaan'            => $_POST['pekerjaan'] ?? '',
        'alamat'               => $_POST['alamat'] ?? '',
        'diagnosa_medis'       => $_POST['diagnosa_medis'] ?? '',
        'keluhan_utama'        => $_POST['keluhan_utama'] ?? '',
        'tanda_vital'          => $_POST['tanda_vital'] ?? '',
        'pre_operasi'          => $_POST['pre_operasi'] ?? '',
        'pos_operasi'          => $_POST['pos_operasi'] ?? '',
        'pemeriksaan_penunjang'=> $_POST['pemeriksaan_penunjang'] ?? '',
        'terapi_saat_ini'      => $_POST['terapi_saat_ini'] ?? '',
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

    <?php include "kmb/pengkajian_ruang_ok/tab.php"; ?>

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

              <h5 class="card-title mb-1"><strong>1. Biodata Klien</strong></h5>

                <!-- 1 BIODATA KLIEN -->


<!-- NAMA KLIEN -->
<div class="row mb-3">
    <label for="nama_klien" class="col-sm-2 col-form-label"><strong>Nama Klien</strong></label>

            <div class="col-sm-10">
                           <input type="text" class="form-control" name="nama_klien"
                                value="<?= val('nama_klien', $existing_data) ?>" <?= $ro ?>>
                       
                         </div>
                    </div>

<!-- JENIS KELAMIN -->
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

<!-- UMUR -->
<div class="row mb-3">
    <label for="umur" class="col-sm-2 col-form-label"><strong>Umur</strong></label>

    <div class="col-sm-10">
        <input type="text" class="form-control" name="umur"
                                value="<?= val('umur', $existing_data) ?>" <?= $ro ?>>

       
                         </div>
                    </div>

<!-- AGAMA -->
<div class="row mb-3">
    <label for="agama" class="col-sm-2 col-form-label"><strong>Agama</strong></label>

    <div class="col-sm-10">
        <input type="text" class="form-control" name="agama"
                                value="<?= val('agama', $existing_data) ?>" <?= $ro ?>>

                         </div>
                    </div>

<!-- STATUS PERKAWINAN -->
<div class="row mb-3">
    <label for="status_perkawinan" class="col-sm-2 col-form-label"><strong>Status Perkawinan</strong></label>

    <div class="col-sm-10">
        <input type="text" class="form-control" name="status_perkawinan"
                                value="<?= val('status_perkawinan', $existing_data) ?>" <?= $ro ?>>

    </div>
</div>

<!-- PENDIDIKAN -->
<div class="row mb-3">
    <label for="pendidikan" class="col-sm-2 col-form-label"><strong>Pendidikan</strong></label>

    <div class="col-sm-10">
       <input type="text" class="form-control" name="pendidikan"
                                value="<?= val('pendidikan', $existing_data) ?>" <?= $ro ?>>
        </div>

</div>

<!-- PEKERJAAN -->
<div class="row mb-3">
    <label for="pekerjaan" class="col-sm-2 col-form-label"><strong>Pekerjaan</strong></label>

    <div class="col-sm-10">
        <input type="text" class="form-control" name="pekerjaan"
                                value="<?= val('pekerjaan', $existing_data) ?>" <?= $ro ?>>

    </div>
</div>

<!-- ALAMAT -->
<div class="row mb-3">
    <label for="alamat" class="col-sm-2 col-form-label"><strong>Alamat</strong></label>

    <div class="col-sm-10">
        <textarea name="alamat" class="form-control" rows="3"
        style="display:block; overflow:hidden; resize: none;"
        oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

    </div>
</div>

<!-- DIAGNOSA MEDIS -->
<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>Diagnosa Medis</strong></label>

<div class="col-sm-10">
<textarea name="diagnosa_medis" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('diagnosa_medis',$existing_data) ?></textarea>

</div>
</div>

             
              <h5 class="card-title mb-1">
<!-- 2 KELUHAN UTAMA -->
<strong>2. Keluhan Utama</strong></h5>


<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>Keluhan Utama</strong></label>

<div class="col-sm-10">
<textarea name="keluhan_utama" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('keluhan_utama',$existing_data) ?></textarea>                        

</div>
</div>

             
              <h5 class="card-title mb-1">

<strong>3.  Tanda-tanda Vital </strong></h5>


<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>Tanda-tanda Vital </strong></label>

<div class="col-sm-10">
<textarea name="tanda_vital" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('tanda_vital',$existing_data) ?></textarea>      

</div>
</div>
        </div>

            <div class="card-body">
             
              <h5 class="card-title mb-1">
<strong>4. Pengkajian  Data Fokus (Data yang Bermasalah) </strong></h5>


<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>Pre Operasi</strong></label>

<div class="col-sm-10">
<textarea name="pre_operasi" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('pre_operasi',$existing_data) ?></textarea>

</div>
</div>


<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>Pos Operasi</strong></label>

<div class="col-sm-10">
<textarea name="pos_operasi" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('pos_operasi',$existing_data) ?></textarea>

</div>
</div>

<!-- 4 PEMERIKSAAN PENUNJANG -->

             
              <h5 class="card-title mb-1">
<strong>5. Pemeriksaan Penunjang</strong></h5>
</label>

<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>Pemeriksaan Penunjang</strong>
</label>

<div class="col-sm-10">
<textarea name="pemeriksaan_penunjang" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('pemeriksaan_penunjang',$existing_data) ?></textarea>

</div>
</div>

<!-- 5 TERAPI SAAT INI -->

             
              <h5 class="card-title mb-1">
<strong>6. Terapi Saat Ini</strong></h5>


<div class="row mb-3">
<label class="col-sm-2 col-form-label" ><strong>Terapi Saat Ini</strong>
</label>

<div class="col-sm-10">
<textarea name="terapi_saat_ini" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('terapi_saat_ini',$existing_data) ?></textarea>
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
                            <div class="col-sm-9">
                                <textarea name="comment" class="form-control" rows="3"
                                    placeholder="Tulis komentar (wajib jika meminta revisi)..."></textarea>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-11 d-flex justify-content-end gap-2">
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