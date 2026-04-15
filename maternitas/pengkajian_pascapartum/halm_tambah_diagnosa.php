<?php
require_once "koneksi.php";
require_once "utils.php";

$form_id       = 3;
$level         = $_SESSION['level'];
$user_id       = $_SESSION['id_user'];
$section_name  = 'identitas';
$section_label = 'Identitas';

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
$tgl_pengkajian = $submission['tanggal_pengkajian'] ?? '';
$rs_ruangan     = $submission['rs_ruangan'] ?? '';

// =============================================
// HANDLE POST - MAHASISWA SIMPAN DATA
// =============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $level === 'Mahasiswa') {

    if (isLocked($submission)) {
        redirectWithMessage($_SERVER['REQUEST_URI'], 'error', 'Data tidak dapat diubah karena sedang dalam proses review.');
    }

    $tgl_pengkajian = $_POST['tglpengkajian'] ?? '';
    $rs_ruangan     = $_POST['rsruangan'] ?? '';

    $data = [
        'inisial_pasien'            => $_POST['inisialpasien'] ?? '',
        'usia_istri'                => $_POST['usiaistri'] ?? '',
        'pekerjaan_istri'           => $_POST['pekerjaanistri'] ?? '',
        'pendidikan_terakhir_istri' => $_POST['pendidikanterakhiristri'] ?? '',
        'agama_istri'               => $_POST['agamaistri'] ?? '',
        'suku_bangsa'               => $_POST['sukubangsa'] ?? '',
        'status_perkawinan'         => $_POST['statusperkawinan'] ?? '',
        'alamat'                    => $_POST['keterangan'] ?? '',
        'diagnosa_medik'            => $_POST['diagnosamedik'] ?? '',
        'nama_suami'                => $_POST['namasuami'] ?? '',
        'usia_suami'                => $_POST['usiasuami'] ?? '',
        'pekerjaan_suami'           => $_POST['pekerjaansuami'] ?? '',
        'pendidikan_terakhir_suami' => $_POST['pendidikanterakhirsuami'] ?? '',
        'agama_suami'               => $_POST['agamasuami'] ?? '',
        'keluhan_utama'             => $_POST['keluhanutama'] ?? '',
        'riwayat_keluhan_utama'     => $_POST['riwayatkeluhanutama'] ?? '',
    ];

    if (!$submission) {
        $submission_id = createSubmission($user_id, $form_id, $tgl_pengkajian, $rs_ruangan, $mysqli);
    } else {
        $submission_id = $submission['id'];
        updateSubmissionHeader($submission_id, $tgl_pengkajian, $rs_ruangan, $mysqli);
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
       <?php include "navbar_maternitas.php"; ?>


    <section class="section dashboard">
        <div class="card">
            <div class="card-body">
              <h5 class="card-title mb-1"><strong>Diagnosa Keperawatan</strong></h5>

                <!-- General Form Elements -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

                <!-- Bagian Diagnosa -->

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Diagnosa</strong></label>

                        <div class="col-sm-9">
                           <textarea name="diagnona" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                         </div>
                    </div> 

                    <!-- Bagian Tanggal Ditemukan -->
                    <div class="row mb-3">
                        <label for="tgl_ditemukan" class="col-sm-2 col-form-label"><strong>Tanggal Ditemukan</strong></label>

                        <div class="col-sm-9">
                            <input type="datetime-local" class="form-control" id="tgl_ditemukan" name="tgl_ditemukan">

                         </div>
                    </div>

                    <!-- Bagian Tanggal Teratasi -->
                    <div class="row mb-3">
                        <label for="tgl_teratasi" class="col-sm-2 col-form-label"><strong>Tanggal Teratasi</strong></label>

                        <div class="col-sm-9">
                            <input type="datetime-local" class="form-control" id="tgl_teratasi" name="tgl_teratasi">

                         </div>
                    </div>

                    <!-- Bagian Button -->    
                    <div class="row mb-3">
                        <div class="col-sm-11 justify-content-end d-flex">
                            <button type="submit" name="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </div> 

                    <h5 class="card-title mt-2"><strong>Diagnosa Keperawatan</strong></h5>

                    <style>
                    .table-pemeriksaan {
                        table-layout: fixed;
                        width:100%
                    }

                    .table-pemeriksaan td,
                    .table-pemeriksaan th {
                        word-wrap: break-word;
                        white-space: normal;
                        vertical-align: top;
                    }
                    </style>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center">Diagnosa</th>
                                <th class="text-center">Tanggal Ditemukan</th>
                                <th class="text-center">Tanggal Teratasi</th>
                        </tr>
                        </thead>

                    <tbody>

                    <?php
                    if(!empty($data)){
                        foreach($data as $row){
                            echo "<tr>
                            <td>".$row['diagnosa']."</td>
                            <td>".$row['tgl_ditemukan']."</td>
                            <td>".$row['tgl_teratasi']."</td>
                            </tr>";
                        }
                    }
                    ?>

                    </tbody>
                    </table>

                    <?php include "tab_navigasi.php"; ?>
                    
</section>

</main>