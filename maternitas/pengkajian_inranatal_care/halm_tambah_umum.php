<?php
require_once "koneksi.php";
require_once "utils.php";

$form_id       = 4;
$level         = $_SESSION['level'];
$user_id       = $_SESSION['id_user'];
$section_name  = 'data_umum';
$section_label = 'Data Umum';

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
    'inisial_pasien' => $_POST['inisialpasien'] ?? '',
    'usia_istri' => $_POST['usiaistri'] ?? '',
    'pekerjaan_istri' => $_POST['pekerjaanistri'] ?? '',
    'pendidikan_terakhir_istri' => $_POST['pendidikanterakhiristri'] ?? '',
    'agama_istri' => $_POST['agamaistri'] ?? '',
    'suku_bangsa' => $_POST['sukubangsa'] ?? '',
    'status_perkawinan' => $_POST['statusperkawinan'] ?? '',
    'alamat' => $_POST['alamat'] ?? '',
    'diagnosa_medik' => $_POST['diagnosamedik'] ?? '',

    'nama_suami' => $_POST['namasuami'] ?? '',
    'usia_suami' => $_POST['usiasuami'] ?? '',
    'pekerjaan_suami' => $_POST['pekerjaansuami'] ?? '',
    'pendidikan_terakhir_suami' => $_POST['pendidikanterakhirsuami'] ?? '',
    'agama_suami' => $_POST['agamasuami'] ?? '',

    'bbtb' => $_POST['bbtb'] ?? '',
    'bb_sebelum_hamil' => $_POST['bbsebelumhamil'] ?? '',
    'lila' => $_POST['lila'] ?? '',
    'rencana_kehamilan' => $_POST['rencanakehamilan'] ?? '',

    'g' => $_POST['g'] ?? '',
    'p' => $_POST['p'] ?? '',
    'a' => $_POST['a'] ?? '',

    'hpht' => $_POST['hpht'] ?? '',
    'tp' => $_POST['tp'] ?? '',

    'obat_dikonsumsi' => $_POST['obatdikonsumsi'] ?? '',
    'alergi' => $_POST['alergi'] ?? '',
    'alat_bantu' => $_POST['alatbantu'] ?? '',
    'bak_terakhir' => $_POST['bakterakhir'] ?? '',
    'bab_terakhir' => $_POST['babterakhir'] ?? '',

    'tidur_siang' => $_POST['siang'] ?? '',
    'tidur_malam' => $_POST['malam'] ?? '',

    'riwayat_kesehatan' => $_POST['riwayatkesehatan'] ?? ''
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

    <?php include "maternitas/pengkajian_inranatal_care/tab.php"; ?>

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
        <div class="card mt-3">
            <div class="card-body">
                <form class="needs-validation" novalidate action="" method="POST">

                  <div class="row mb-3 mt-3">
                        <label class="col-sm-2 col-form-label"><strong>Tanggal Pengkajian</strong></label>
                        <div class="col-sm-10">
                            <input type="date" class="form-control" name="tglpengkajian"
                                value="<?= htmlspecialchars($tgl_pengkajian) ?>" <?= $ro ?> required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>RS/Ruangan</strong></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="rsruangan"
                                value="<?= htmlspecialchars($rs_ruangan) ?>" <?= $ro ?> required>
                        </div>
                    </div>

                <h5 class="card-title"><strong>DATA UMUM</strong></h5>

                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><strong>Inisial Pasien</strong></label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="inisialpasien"
                            value="<?= val('inisial_pasien',$existing_data) ?>" <?= $ro ?>>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><strong>Usia</strong></label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="usiaistri"
                            value="<?= val('usia',$existing_data) ?>" <?= $ro ?>>
                    </div>
                </div>

               <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><strong>Pekerjaan</strong></label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="pekerjaanistri"
                            value="<?= val('pekerjaan',$existing_data) ?>" <?= $ro ?>>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><strong>Pendidikan Terakhir</strong></label>
                    <div class="col-sm-10">
                        <select class="form-select" name="pendidikanterakhiristri" <?= $ro_select ?>>
                            <option value="">Pilih</option>
                            <option value="TK" <?= val('pendidikan',$existing_data)=='TK'?'selected':'' ?>>TK</option>
                            <option value="SD" <?= val('pendidikan',$existing_data)=='SD'?'selected':'' ?>>SD</option>
                            <option value="SMP" <?= val('pendidikan',$existing_data)=='SMP'?'selected':'' ?>>SMP</option>
                            <option value="SMASMKSEDERAJAT" <?= val('pendidikan',$existing_data)=='S1'?'selected':'' ?>>SMA/SMK/Sederajat</option>
                            <option value="D3" <?= val('pendidikan',$existing_data)=='D3'?'selected':'' ?>>D3</option>
                            <option value="S1" <?= val('pendidikan',$existing_data)=='S1'?'selected':'' ?>>S1</option>
                            <option value="S2" <?= val('pendidikan',$existing_data)=='S1'?'selected':'' ?>>S2</option>
                            <option value="S3" <?= val('pendidikan',$existing_data)=='S1'?'selected':'' ?>>S3</option>
                            <option value="Lainnya" <?= val('pendidikan',$existing_data)=='S1'?'selected':'' ?>>Lainnya</option>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><strong>Agama</strong></label>
                    <div class="col-sm-10">
                         <select class="form-select" name="agamaistri" <?= $ro_select ?>>
                            <option value="">Pilih</option>
                            <option value="Islam" <?= val('agama',$existing_data)=='Islam'?'selected':'' ?>>Islam</option>
                            <option value="Kristenprotestan" <?= val('agama',$existing_data)=='Kristenprotestan'?'selected':'' ?>>Kristen Protestan</option>
                            <option value="Katolik" <?= val('agama',$existing_data)=='Katolik'?'selected':'' ?>>Katolik</option>
                            <option value="Hindu" <?= val('agama',$existing_data)=='Hindu'?'selected':'' ?>>Hindu</option>
                            <option value="Buddha" <?= val('agama',$existing_data)=='Buddha'?'selected':'' ?>>Buddha</option>
                            <option value="Konghucu" <?= val('agama',$existing_data)=='Konghucu'?'selected':'' ?>>Konghucu</option>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><strong>Suku</strong></label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="sukubangsa"
                            value="<?= val('suku',$existing_data) ?>" <?= $ro ?>>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><strong>Status Perkawinan</strong></label>
                    <div class="col-sm-10">
                        <select class="form-select" name="statusperkawinan" <?= $ro_select ?>>
                            <option value="">Pilih</option>
                            <option value="Menikah" <?= val('status_perkawinan',$existing_data)=='Menikah'?'selected':'' ?>>Menikah</option>
                            <option value="Belummenikah" <?= val('status_perkawinan',$existing_data)=='Belummenikah'?'selected':'' ?>>Belum Menikah</option>
                            <option value="Duda" <?= val('status_perkawinan',$existing_data)=='Duda'?'selected':'' ?>>Duda</option>
                            <option value="Janda" <?= val('status_perkawinan',$existing_data)=='Janda'?'selected':'' ?>>Janda</option>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><strong>Alamat</strong></label>
                    <div class="col-sm-10">
                        <textarea name="alamat" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('alamat',$existing_data) ?></textarea>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><strong>Diagnosa Medik</strong></label>
                    <div class="col-sm-10">
                        <textarea name="diagnosamedik" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('alamat',$existing_data) ?></textarea></div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><strong>Nama Suami</strong></label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="namasuami"
                            value="<?= val('nama_suami',$existing_data) ?>" <?= $ro ?>>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><strong>Usia</strong></label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="usiasuami"
                            value="<?= val('usia_suami',$existing_data) ?>" <?= $ro ?>>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><strong>Pekerjaan</strong></label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="pekerjaansuami"
                            value="<?= val('pekerjaan',$existing_data) ?>" <?= $ro ?>>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><strong>Pendidikan Terakhir</strong></label>
                    <div class="col-sm-10">
                        <select class="form-select" name="pendidikanterakhirsuami" <?= $ro_select ?>>
                            <option value="">Pilih</option>
                            <option value="TK" <?= val('pendidikan',$existing_data)=='TK'?'selected':'' ?>>TK</option>
                            <option value="SD" <?= val('pendidikan',$existing_data)=='SD'?'selected':'' ?>>SD</option>
                            <option value="SMP" <?= val('pendidikan',$existing_data)=='SMP'?'selected':'' ?>>SMP</option>
                            <option value="SMASMKSEDERAJAT" <?= val('pendidikan',$existing_data)=='S1'?'selected':'' ?>>SMA/SMK/Sederajat</option>
                            <option value="D3" <?= val('pendidikan',$existing_data)=='D3'?'selected':'' ?>>D3</option>
                            <option value="S1" <?= val('pendidikan',$existing_data)=='S1'?'selected':'' ?>>S1</option>
                            <option value="S2" <?= val('pendidikan',$existing_data)=='S1'?'selected':'' ?>>S2</option>
                            <option value="S3" <?= val('pendidikan',$existing_data)=='S1'?'selected':'' ?>>S3</option>
                            <option value="Lainnya" <?= val('pendidikan',$existing_data)=='S1'?'selected':'' ?>>Lainnya</option>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><strong>Agama</strong></label>
                    <div class="col-sm-10">
                         <select class="form-select" name="agamasuami" <?= $ro_select ?>>
                            <option value="">Pilih</option>
                            <option value="Islam" <?= val('agama',$existing_data)=='Islam'?'selected':'' ?>>Islam</option>
                            <option value="Kristenprotestan" <?= val('agama',$existing_data)=='Kristenprotestan'?'selected':'' ?>>Kristen Protestan</option>
                            <option value="Katolik" <?= val('agama',$existing_data)=='Katolik'?'selected':'' ?>>Katolik</option>
                            <option value="Hindu" <?= val('agama',$existing_data)=='Hindu'?'selected':'' ?>>Hindu</option>
                            <option value="Buddha" <?= val('agama',$existing_data)=='Buddha'?'selected':'' ?>>Buddha</option>
                            <option value="Konghucu" <?= val('agama',$existing_data)=='Konghucu'?'selected':'' ?>>Konghucu</option>
                        </select>
                    </div>
                </div>

                <h5 class="card-title"><strong>DATA UMUM KESEHATAN</strong></h5>

                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><strong>BB/TB</strong></label>
                    <div class="col-sm-10">
                        <div class="input-group">
                        <input type="text" class="form-control" name="bbtb"
                            value="<?= val('bbtb',$existing_data) ?>" <?= $ro ?>>
                        <span class="input-group-text">kg/cm</span>  
                        </div>  
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><strong>BB Sebelum Hamil</strong></label>
                    <div class="col-sm-10">
                        <div class="input-group">
                        <input type="text" class="form-control" name="bbsebelumhamil"
                            value="<?= val('bbtb',$existing_data) ?>" <?= $ro ?>>
                        <span class="input-group-text">kg</span>  
                        </div>  
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><strong>LILA</strong></label>
                    <div class="col-sm-10">
                        <div class="input-group">
                        <input type="text" class="form-control" name="lila"
                            value="<?= val('lila',$existing_data) ?>" <?= $ro ?>>
                            <span class="input-group-text">cm</span>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><strong>Kehamilan Sekarang direncana</strong></label>
                    <div class="col-sm-10">
                        <select class="form-select" name="rencanakehamilan" <?= $ro_select ?>>
                            <option value="">Pilih</option>
                            <option value="Ya" <?= val('rencanakehamilan',$existing_data)=='Ya'?'selected':'' ?>>Ya</option>
                            <option value="Tidak" <?= val('rencanakehamilan',$existing_data)=='Tidak'?'selected':'' ?>>Tidak</option>
                            </select>
                    </div>
                </div>

                        <div class="row mb-3">

                        <label class="col-sm-2 col-form-label"><strong>Status Obstetrik</strong></label>
                            <div class="col-sm-10">
                                <div class="row">

                        <!-- G -->
                        <div class="col-md-4 d-flex align-items-center">
                            <label class="me-2"><strong>G</strong></label>
                            <input type="text" class="form-control" name="g"
                                value="<?= val('g',$existing_data) ?>" <?= $ro ?>>
                        </div>

                        <!-- p -->
                        <div class="col-md-4 d-flex align-items-center">
                            <label class="me-2"><strong>P</strong></label>
                            <input type="text" class="form-control" name="p"
                                 value="<?= val('p',$existing_data) ?>" <?= $ro ?>>
                        </div>

                        <!-- A -->
                        <div class="col-md-4 d-flex align-items-center">
                            <label class="me-2"><strong>A</strong></label>
                            <input type="text" class="form-control" name="a"
                                 value="<?= val('a',$existing_data) ?>" <?= $ro ?>>
                        </div>
                    </div>
                         </div>
                    </div>
                    
                <!-- HPHT -->
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-2 col-form-label"><strong>HPHT</strong></label>
                        <div class="col-sm-4">
                            <div class="input-group">
                                <input type="text" class="form-control" name="hpht"
                                    value="<?= val('hpht',$existing_data) ?>" <?= $ro ?>>
                        </div>    
                    </div>
                                
                    <!-- TP -->
                    <label class="col-sm-2 col-form-label"><strong>TP</strong></label>
                    <div class="col-sm-4">
                        <div class="input-group">
                                <input type="text" class="form-control" name="tp"
                                    value="<?= val('hpht',$existing_data) ?>" <?= $ro ?>>
                        </div>  
                    </div>    
                </div>  

                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><strong>Obat-obatan yang dikonsumsi</strong></label>
                    <div class="col-sm-10">
                        <textarea name="obatdikonsumsi" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('obatdikonsumsi',$existing_data) ?></textarea>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><strong>Aoakah Ada Alergi Terhadap Sesuatu</strong></label>
                    <div class="col-sm-10">
                        <textarea name="alergi" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('alergi',$existing_data) ?></textarea>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><strong>Alat Bantu</strong></label>
                    <div class="col-sm-10">
                        <small class="form-text" style="color: red;">Gigi tiruan, kacamata/lensa kontak, alat dengar, dan lain-lain. Sebutkan</small>
                        <textarea name="alatbantu" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('alatbantu',$existing_data) ?></textarea>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><strong>Bak Terakhir</strong></label>
                    <div class="col-sm-10">
                        <small class="form-text" style="color: red;">Masalah:</small>
                        <textarea name="bakterakhir" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('bakterakhir',$existing_data) ?></textarea>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><strong>Bab Terakhir</strong></label>
                    <div class="col-sm-10">
                        <small class="form-text" style="color: red;">Masalah:</small>
                        <textarea name="babterakhir" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('babterakhir',$existing_data) ?></textarea>
                    </div>
                </div>

                <!-- Bagian Kebiasaan Waktu Tidur -->
                <div class="row mb-3">

                    <label class="col-sm-2 col-form-label"><strong>Kebiasaan Waktu Tidur</strong></label>

                    <!-- Siang -->
                    <label class="col-sm-1 col-form-label"><strong>Siang</strong></label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" name="siang"
                            value="<?= val('siang',$existing_data) ?>" <?= $ro ?>>
                    </div>

                    <!-- Malam -->
                    <label class="col-sm-1 col-form-label"><strong>Malam</strong></label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" name="malam"
                        value="<?= val('malam',$existing_data) ?>" <?= $ro ?>>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><strong>Riwayat Kesehatan yang Lalu</strong></label>
                    <div class="col-sm-10">
                        <textarea name="riwayatkesehatan" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('riwayatkesehatan',$existing_data) ?></textarea>
                    </div>
                </div>

                    <?php if (!$is_dosen): ?>
                    <div class="row mb-3">
                        <div class="col-sm-12 d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php include "tab_navigasi.php"; ?>

        </form>              
    </section>             
</main>

                        


