<?php
$form_id       = 22;
$section_name  = 'pemeriksaan_penunjang';
$section_label = 'Pemeriksaan Penunjang';
include dirname(__DIR__, 2) . '/partials/init_section.php';

// Load existing dynamic rows
$existing_obat = $existing_data['obat'] ?? [];
$existing_lab  = $existing_data['lab']  ?? [];

// =============================================
// HANDLE POST - MAHASISWA SIMPAN DATA
// =============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $level === 'Mahasiswa') {

    if (isLocked($submission)) {
        redirectWithMessage($_SERVER['REQUEST_URI'], 'error', 'Data tidak dapat diubah karena sedang dalam proses review.');
    }
 // Proses dynamic rows obat
    $obat = [];
    if (!empty($_POST['obat'])) {
        foreach ($_POST['obat'] as $index => $row) {
            if (empty($row['nama_obat']) && empty($row['dosis']) && empty($row['rute'])) {
                continue;
            }
            $obat[] = [
                'nama_obat'     => $row['nama_obat']     ?? '',
                'dosis'          => $row['dosis']           ?? '',
                'rute'       => $row['rute']        ?? '',
                'pemberian' => $row['pemberian']  ?? '',
            ];
        }
    }

    // Proses dynamic rows lab
    $lab = [];
    if (!empty($_POST['lab'])) {
        foreach ($_POST['lab'] as $index => $row) {
            if (empty($row['pemeriksaan']) && empty($row['hasil']) && empty($row['satuan'])) {
                continue;
            }
            $lab[] = [
                'pemeriksaan'  => $row['pemeriksaan']  ?? '',
                'hasil'        => $row['hasil']         ?? '',
                'satuan' => $row['satuan']  ?? '',
                'rujukan' => $row['rujukan']  ?? '',
            ];
        }
    }

    $data = [
        'obat' => $obat,
        'lab'  => $lab,
        'tgllaboratorium'                 => $_POST['tgllaboratorium']                 ?? '',
        'radiologi'                 => $_POST['radiologi']                 ?? '',
        'tglradiologi'                 => $_POST['tglradiologi']                 ?? '',
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

?>

<main id="main" class="main">

    <?php include "gadar/igd/tab.php"; ?>

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
   
    <section class="section dashboard">
        <div class="card">
            <div class="card-body">
            
                <!-- General Form Elements -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">
        
                <!-- Pemeriksan Penunjunang -->
                    <div class="row mb-2">
                        <label class="col-sm-10 col-form-label text-primary">
                            <strong>k. Pemeriksaan Penunjang </strong>
                    </div> 
                    
                    <!-- Laboratorium -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Laboratorium</strong></label>

                        <div class="col-sm-9">
                            <input type="date" class="form-control" name="tgllaboratorium">
                        </div>
                    </div>
                              <!-- ===================== TABEL LAB ===================== -->
                    <p class="text-primary fw-bold mb-2">Hasil Pemeriksaan Penunjang dan Laboratorium</p>
                    <table class="table table-bordered" id="tabel-lab">
                        <thead>
                            <tr>
                                <th class="text-center" style="width:40px">No</th>
                                <th class="text-center">Pemeriksaan</th>
                                <th class="text-center">Hasil</th>
                                <th class="text-center">Satuan</th>
                                <th class="text-center">Nilai Rujukan</th>
                                <th class="text-center" style="width:60px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="tbody-lab">
                            <!-- Dynamic rows masuk sini -->
                        </tbody>
                    </table>
                    <div class="row mb-4">
                        <div class="col-sm-12 d-flex justify-content-end">
                            <button type="button" class="btn btn-primary btn-sm" id="btn-tambah-lab" onclick="tambahRowLab()">+ Tambah Pemeriksaan</button>
                        </div>
                    </div>
                            
                    
                    <!-- Radiologi -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Radiologi</strong></label>

                        <div class="col-sm-9">
                            <input type="date" class="form-control" name="tglradiologi">
                            <small class="form-text" style="color: red;"> Hasil:</small>
                            <textarea name="radiologi" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            value="<?= val('radiologi', $existing_data) ?>" <?= $ro ?>></textarea>

                        </div>
                    </div>  

                <!-- Pengobatan -->
                    <div class="row mb-2">
                        <label class="col-sm-10 col-form-label text-primary">
                            <strong>9. Pengobatan</strong>
                    </div>
                       <!-- ===================== TABEL OBAT ===================== -->
                    
                    <table class="table table-bordered" id="tabel-obat">
                        <thead>
                            <tr>
                                <th class="text-center" style="width:40px">No</th>
                                <th class="text-center">Nama Obat</th>
                                <th class="text-center">Dosis</th>
                                <th class="text-center">Rute Pemberian</th>
                                <th class="text-center">Berapa Kali Pemberian/hari</th>
                                <th class="text-center" style="width:60px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="tbody-obat">
                            <!-- Dynamic rows masuk sini -->
                        </tbody>
                    </table>
                    <div class="row mb-4">
                        <div class="col-sm-12 d-flex justify-content-end">
                            <button type="button" class="btn btn-primary btn-sm" id="btn-tambah-obat" onclick="tambahRowObat()">+ Tambah Obat</button>
                        </div>
                    </div>
               
              



     <!-- TOMBOL SIMPAN -->
                    <?php if (!$is_dosen): ?>
                        <div class="row mb-3">
                            <div class="col-sm-12 d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary" <?= $ro ?>>Simpan Data</button>
                            </div>
                        </div>
                    <?php endif; ?>
                    <script>
                        let rowObatCount = 1;
                        let rowLabCount = 1;
                        const existingObat = <?= json_encode($existing_obat) ?>;
                        const existingLab = <?= json_encode($existing_lab) ?>;
                        const isReadonly = <?= json_encode($is_readonly) ?>;
                        // ---- OBAT ----
                        function tambahRowObat(data = null) {
                            const tbody = document.getElementById('tbody-obat');
                            const index = rowObatCount;
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td class="text-center align-middle">${index}</td>
                                <td><input type="text" class="form-control form-control-sm" name="obat[${index}][nama_obat]" value="${data?.nama_obat ?? ''}" ${isReadonly ? 'readonly' : ''}></td>
                                <td><input type="text" class="form-control form-control-sm" name="obat[${index}][dosis]" value="${data?.dosis ?? ''}" ${isReadonly ? 'readonly' : ''}></td>
                                <td><input type="text" class="form-control form-control-sm" name="obat[${index}][rute]" value="${data?.rute ?? ''}" ${isReadonly ? 'readonly' : ''}></td>
                                <td><input type="text" class="form-control form-control-sm" name="obat[${index}][pemberian]" value="${data?.pemberian ?? ''}" ${isReadonly ? 'readonly' : ''}></td>
                                <td class="text-center align-middle">
                                    <button type="button" class="btn btn-danger btn-sm" onclick="hapusRow(this)" ${isReadonly ? 'disabled' : ''}>x</button>
                                </td>
                            `;
                            tbody.appendChild(row);
                            rowObatCount++;
                        }
                        // ---- LAB ----
                        function tambahRowLab(data = null) {
                            const tbody = document.getElementById('tbody-lab');
                            const index = rowLabCount;
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td class="text-center align-middle">${index}</td>
                                <td><input type="text" class="form-control form-control-sm" name="lab[${index}][pemeriksaan]" value="${data?.pemeriksaan ?? ''}" ${isReadonly ? 'readonly' : ''}></td>
                                <td><input type="text" class="form-control form-control-sm" name="lab[${index}][hasil]" value="${data?.hasil ?? ''}" ${isReadonly ? 'readonly' : ''}></td>
                                <td><input type="text" class="form-control form-control-sm" name="lab[${index}][satuan]" value="${data?.satuan ?? ''}" ${isReadonly ? 'readonly' : ''}></td>\
                                <td><input type="text" class="form-control form-control-sm" name="lab[${index}][rujukan]" value="${data?.rujukan ?? ''}" ${isReadonly ? 'readonly' : ''}></td>

                                <td class="text-center align-middle">
                                    <button type="button" class="btn btn-danger btn-sm" onclick="hapusRow(this)" ${isReadonly ? 'disabled' : ''}>x</button>
                                </td>
                            `;
                            tbody.appendChild(row);
                            rowLabCount++;
                        }

                        function hapusRow(btn) {
                            btn.closest('tr').remove();
                        }
                        // Load existing rows on page load
                        window.addEventListener('load', function() {
                            if (existingObat && existingObat.length > 0) {
                                existingObat.forEach(row => tambahRowObat(row));
                            } else {
                                tambahRowObat(); // default 1 row kosong
                            }
                            if (existingLab && existingLab.length > 0) {
                                existingLab.forEach(row => tambahRowLab(row));
                            } else {
                                tambahRowLab(); // default 1 row kosong
                            }
                            // Disable add buttons if readonly
                            if (isReadonly) {
                                document.getElementById('btn-tambah-obat').setAttribute('disabled', 'disabled');
                                document.getElementById('btn-tambah-lab').setAttribute('disabled', 'disabled');
                            }
                        });
                        const existingData = <?= json_encode($existing_data) ?>;
                    </script>
                </form>

            </div>
        </div>

        <?php include "partials/footer_form.php" ?>

    </section>
</main>
                        


