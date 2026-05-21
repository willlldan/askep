<?php
require_once "koneksi.php";
require_once "utils.php";

$form_id       = 9;
$section_name  = 'pengkajian_kebutuhan';
$section_label = 'Pengkajian Kebutuhan';
include dirname(__DIR__, 2) . '/partials/init_section.php';

$existing_diagnosa     = $existing_data['diagnosa']     ?? [];

// =============================================
// HANDLE POST - MAHASISWA SIMPAN DATA
// =============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $level === 'Mahasiswa') {

    if (isLocked($submission)) {
        redirectWithMessage($_SERVER['REQUEST_URI'], 'error', 'Data tidak dapat diubah karena sedang dalam proses review.');
    }


    // Proses dynamic rows diagnosa
    $diagnosa = [];
    if (!empty($_POST['diagnosa'])) {
        foreach ($_POST['diagnosa'] as $index => $row) {
            if (empty($row['pemeriksaan']) && empty($row['hasil']) && empty($row['satuan'])) {
                continue;
            }
            $diagnosa[] = [
                'pemeriksaan'      => $row['pemeriksaan']      ?? '',
                'hasil' => $row['hasil'] ?? '',
                'satuan'  => $row['satuan']  ?? '',
                'nilai'  => $row['nilai']  ?? '',
            ];
        }
    }
    $data = [
        'diagnosa'     => $diagnosa,
        'mandi'                     => $_POST['mandi'] ?? '',
        'berpakaian'                => $_POST['berpakaian'] ?? '',
        'mobilisasi'                => $_POST['mobilisasi'] ?? '',
        'pindah'                    => $_POST['pindah'] ?? '',
        'ambulasi'                  => $_POST['ambulasi'] ?? '',
        'makan'                     => $_POST['makan'] ?? '',
        
        'tanggal_pemeriksaan'       => $_POST['tanggal_pemeriksaan'] ?? '',
        'radiologi'                 => $_POST['radiologi'] ?? '',
        'data_penunjang_lain'       => $_POST['data_penunjang_lain'] ?? '',
        'kognitif'       => $_POST['kognitif'] ?? '',
        'pola_nutrisi'       => $_POST['pola_nutrisi'] ?? '',
        'cairan'       => $_POST['cairan'] ?? '',
        'bab'       => $_POST['bab'] ?? '',
        'bak'       => $_POST['bak'] ?? '',
        'tidur'       => $_POST['tidur'] ?? '',
        'hygiene'       => $_POST['hygiene'] ?? '',
       
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
    <?php include "kmb/format_hd_kmb/tab.php"; ?>
    <?php include dirname(__DIR__, 2) . '/partials/notifikasi.php'; ?>
    <?php include dirname(__DIR__, 2) . '/partials/status_section.php'; ?>

    <div class="card">
        <div class="card-body">

            <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

                <h5 class="card-title"><strong>5. Pengkajian kebutuhan </strong></h5>
                <div class="row mb-2">
                    <label class="col-sm-12 text-primary"><strong>a. Pola Aktivitas</strong></label>
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
                        <label class="col-sm-12 text-primary"><strong>b. Pola Kognitif dan Perceptual</strong></label>
                    </div>
                      <div class="row mb-3">
                                <div class="col-sm-2 col-form-label">
                                    <strong>Pola Kognitif dan Perceptual</strong>
                                </div>
                                <div class="col-sm-9">
                                    <textarea name="kognitif" class="form-control" rows="1" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                        <?= $ro ?>><?= val('kognitif', $existing_data) ?></textarea>


                                </div>
                        </div>

                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary"><strong>c. Pola Nutrisi</strong></label>
                    </div>
                    <div class="row mb-3">
                                <div class="col-sm-2 col-form-label">
                                    <strong>Pola Nutrisi</strong>
                                </div>
                                <div class="col-sm-9">
                                    <textarea name="pola_nutrisi" class="form-control" rows="1" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                        <?= $ro ?>><?= val('pola_nutrisi', $existing_data) ?></textarea>


                                </div>
                        </div>

                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary"><strong>d. Cairan</strong></label>
                    </div>
                    <div class="row mb-3">
                                <div class="col-sm-2 col-form-label">
                                    <strong>Cairan</strong>
                                </div>
                                <div class="col-sm-9">
                                    <textarea name="cairan" class="form-control" rows="1" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                        <?= $ro ?>><?= val('cairan', $existing_data) ?></textarea>


                                </div>
                        </div>


                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary"><strong>e. Pola Eliminasi BAB</strong></label>
                    </div>
                    <div class="row mb-3">
                                <div class="col-sm-2 col-form-label">
                                    <strong>Pola Eliminasi BAB</strong>
                                </div>
                                <div class="col-sm-9">
                                    <textarea name="bab" class="form-control" rows="1" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                        <?= $ro ?>><?= val('bab', $existing_data) ?></textarea>


                                </div>
                        </div>


                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary"><strong>f. Pola Eliminasi BAK</strong></label>
                    </div>
                    <div class="row mb-3">
                                <div class="col-sm-2 col-form-label">
                                    <strong>Pola Eliminasi BAK</strong>
                                </div>
                                <div class="col-sm-9">
                                    <textarea name="bak" class="form-control" rows="1" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                        <?= $ro ?>><?= val('bak', $existing_data) ?></textarea>


                                </div>
                        </div>


                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary"><strong>g. Pola Tidur</strong></label>
                    </div>
                    <div class="row mb-3">
                                <div class="col-sm-2 col-form-label">
                                    <strong>Pola Tidur</strong>
                                </div>
                                <div class="col-sm-9">
                                    <textarea name="tidur" class="form-control" rows="1" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                        <?= $ro ?>><?= val('tidur', $existing_data) ?></textarea>


                                </div>
                        </div>



                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary"><strong>n. Pola Personal Hygiene</strong></label>
                    </div>
                    <div class="row mb-3">
                                <div class="col-sm-2 col-form-label">
                                    <strong>Pola Personal Hygiene</strong>
                                </div>
                                <div class="col-sm-9">
                                    <textarea name="hygiene" class="form-control" rows="1" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                        <?= $ro ?>><?= val('hygiene', $existing_data) ?></textarea>


                                </div>
                        </div>
                 


                    <div class="row mb-2 mt-4">
                        <label class="col-sm-12 text-primary"><strong>c. Data Penunjang</strong></label>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-2 col-form-label">
                            <strong>Tanggal Pemeriksaan</strong>
                        </div>
                        <div class="col-sm-9">
                            <input type="date" class="form-control" name="tanggal_pemeriksaan" value="<?= val('tanggal_pemeriksaan', $existing_data) ?>" <?= $ro ?>>

                        </div>
                    </div>
                    <p class="text-primary fw-bold mb-2">1) Laboratorium</p>

                    <table class="table table-bordered" id="tabel-diagnosa">
                        <thead>
                            <tr>
                                <th class="text-center" style="width:40px">No</th>
                                <th class="text-center">Nama Pemeriksaan</th>
                                <th class="text-center" style="width:180px">Hasil</th>
                                <th class="text-center" style="width:180px">Satuan</th>
                                <th class="text-center" style="width:180px">Nilai Rujukan</th>
                                <th class="text-center" style="width:60px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="tbody-diagnosa">
                            <!-- Dynamic rows masuk sini -->
                        </tbody>
                    </table>

                    <?php if (!$is_readonly): ?>
                        <div class="row mb-4">
                            <div class="col-sm-12 d-flex justify-content-end">
                                <button type="button" class="btn btn-primary btn-sm" onclick="tambahRowDiagnosa()">+ Tambah Diagnosa</button>
                            </div>
                        </div>
                    <?php endif; ?>


                    <div class="row mb-3">
                        <div class="col-sm-2 col-form-label">
                            <strong>2) Radiologi (Tgl Pemeriksaan & Hasil)</strong>
                        </div>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="radiologi" value="<?= val('radiologi', $existing_data) ?>" <?= $ro ?>>

                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-2 col-form-label">
                            <strong>3) Lainnya (USG, CT Scan, dll)</strong>
                        </div>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="data_penunjang_lain" value="<?= val('data_penunjang_lain', $existing_data) ?>" <?= $ro ?>>

                        </div>
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
    </div>

    <script>
        let rowDiagnosaCount = 1;


        const existingDiagnosa = <?= json_encode($existing_diagnosa) ?>;


        // ---- DIAGNOSA ----
        function tambahRowDiagnosa(data = null) {
            const tbody = document.getElementById('tbody-diagnosa');
            const index = rowDiagnosaCount;
            const row = document.createElement('tr');
            const isReadonly = <?= json_encode($is_readonly) ?>;
            row.innerHTML = `
                                <td class="text-center align-middle">${index}</td>
                                <td>
                                    <textarea
                                        class="form-control form-control-sm"
                                        name="diagnosa[${index}][pemeriksaan]"
                                        rows="2"
                                        style="resize:none; overflow:hidden;"
                                        oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                        ${isReadonly ? 'readonly' : ''}
                                    >${data?.pemeriksaan ?? ''}</textarea>
                                </td>
                                <td>
                                    <input
                                        type="text"
                                        class="form-control form-control-sm"
                                        name="diagnosa[${index}][hasil]"
                                        value="${data?.hasil ?? ''}"
                                        ${isReadonly ? 'readonly' : ''}
                                    >
                                </td>
                                <td>
                                    <input
                                        type="text"
                                        class="form-control form-control-sm"
                                        name="diagnosa[${index}][satuan]"
                                        value="${data?.satuan ?? ''}"
                                        ${isReadonly ? 'readonly' : ''}
                                    >
                                </td>
                                <td>
                                    <input
                                        type="text"
                                        class="form-control form-control-sm"
                                        name="diagnosa[${index}][nilai]"
                                        value="${data?.nilai ?? ''}"
                                        ${isReadonly ? 'readonly' : ''}
                                    >
                                </td>
                                <td class="text-center align-middle">
                                    ${!isReadonly ? `<button type="button" class="btn btn-danger btn-sm" onclick="hapusRow(this)">x</button>` : ''}
                                </td>
                            `;
            tbody.appendChild(row);
            rowDiagnosaCount++;
        }



        function hapusRow(btn) {
            btn.closest('tr').remove();
        }

        // Load existing rows on page load
        window.addEventListener('load', function() {
            if (existingDiagnosa && existingDiagnosa.length > 0) {
                existingDiagnosa.forEach(row => tambahRowDiagnosa(row));
            } else {
                tambahRowDiagnosa();
            }
        });
    </script>

    <?php include dirname(__DIR__, 2) . '/partials/footer_form.php'; ?>
    </div>
    </div>

    </section>
</main>