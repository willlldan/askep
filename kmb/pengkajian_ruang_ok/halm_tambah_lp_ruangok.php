<?php
$form_id       = 11;
$section_name  = 'lp_ruangok';
$section_label = 'Format Laporan Pendahuluan Ruang OK';
include dirname(__DIR__, 2) . '/partials/init_section.php';

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
        'pengertian_kamar_operasi'  => $_POST['pengertian_kamar_operasi'] ?? '',
        'ruang_kamar_operasi'       => $_POST['ruang_kamar_operasi'] ?? '',
        'kamar_operasi'             => $_POST['kamar_operasi'] ?? '',
        'persyaratan'               => $_POST['persyaratan'] ?? '',
        'tata_cara'                 => $_POST['tata_cara'] ?? '',
        'denah'                     => $_POST['denah'] ?? '',
        'daftar_pustaka'            => $_POST['daftar_pustaka'] ?? '',
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
?>

<main id="main" class="main">
<?php include "kmb/pengkajian_ruang_ok/tab.php"; ?>
    

    <section class="section dashboard">

        <?php include dirname(__DIR__, 2) . '/partials/notifikasi.php'; ?>
        <?php include dirname(__DIR__, 2) . '/partials/status_section.php'; ?>
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
                    
                <h5 class="card-title"><strong>A.	KONSEP DASAR KAMAR BEDAH</strong></h5>

               
                <!-- General Form Elements -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">
                
                    <!-- Bagian Inisial Pasien -->

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>1.	Pengertian Kamar Operasi</strong></label>

                        <div class="col-sm-10">
                        <textarea name="pengertian_kamar_operasi" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('pengertian_kamar_operasi',$existing_data) ?></textarea>    
                        </div>
                    </div>

                <!-- Bagian Usia -->
                <div class="row mb-3">
                    <label for="ruang_kamar_operasi" class="col-sm-2 col-form-label"><strong>2.	Pembagian Ruangan Kamar Operasi</strong></label>
                    <div class="col-sm-10">
                    <textarea name="ruang_kamar_operasi" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('ruang_kamar_operasi',$existing_data) ?></textarea>    
                        </div>
                </div>

                <!-- Bagian Pekerjaan -->
                <div class="row mb-3">
                    <label for="kamar_operasi" class="col-sm-2 col-form-label "><strong>3.	Bagian-bagian Kamar Operasi</strong></label>
                    <div class="col-sm-10">
                        <textarea name="kamar_operasi" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('kamar_operasi',$existing_data) ?></textarea>
                        </div>
                    </div>

                <!-- Bagian Pendidikan Terakhir -->
                <div class="row mb-3">
                    <label for="persyaratan" class="col-sm-2 col-form-label"><strong>4.	Persyaratan Kamar Operasi</strong></label>
                    <div class="col-sm-10">
                        <textarea name="persyaratan" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('persyaratan',$existing_data) ?></textarea>
                        </div>
                    </div>
             
              <h5 class="card-title mb-1"><strong>B. TATA CARA KERJA DAN PENGELOLAAN KAMAR OPERASI</strong></h5>
               
                <div class="row mb-3">
                    <label for="tata_cara" class="col-sm-2 col-form-label"><strong>Tata Cara Kerja dan Pengelolaan Kamar Operasi</strong></label>
                    <div class="col-sm-10">
                    <textarea name="tata_cara" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('tata_cara',$existing_data) ?></textarea>    
                       </div>
                    </div>
             
              <h5 class="card-title mb-1">
                            <strong> C.	DENAH RUANGAN KAMAR OPERASI</strong></h5>
                <div class="row mb-3">
                    <label for="denah" class="col-sm-2 col-form-label"><strong>Denah Ruangan Kamar Operasi</strong></label>
                    <div class="col-sm-10">
                        <textarea name="denah" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('denah',$existing_data) ?></textarea>
                       </div>
                    </div>
                  
              <h5 class="card-title mb-1">
                            <strong> D.	DAFTAR PUSTAKA</strong></h5>
                <div class="row mb-3">
                    <label for="daftar_pustaka" class="col-sm-2 col-form-label"><strong>Daftar Pustaka</strong></label>
                    <div class="col-sm-10">
                        <textarea name="daftar_pustaka" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('daftar_pustaka',$existing_data) ?></textarea>
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

        <?php include dirname(__DIR__, 2) . '/partials/footer_form.php'; ?>

       

    </section>
</main>