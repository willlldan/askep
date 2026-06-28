<?php
$form_id       = 16;
$section_name  = 'data_biologis_3';
$section_label = 'Data Biologis 3';
include dirname(__DIR__, 2) . '/partials/init_section.php';

// Load existing dynamic rows
$existing_terapi = !empty($existing_data['terapi'])
    ? json_decode($existing_data['terapi'], true)
    : [];
  
$existing_penunjang = !empty($existing_data['penunjang'])
    ? json_decode($existing_data['penunjang'], true)
    : [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $level === 'Mahasiswa') {
    if (isLocked($submission)) {
        redirectWithMessage($_SERVER['REQUEST_URI'], 'error', 'Data tidak dapat diubah karena sedang dalam proses review.');
    }
    $text_fields = [
        'harapan_klien',
        'rendah_diri',
        'pendapat_keadaan',
        'status_rumah',
        'hubungan_keluarga',
        'pengambil_keputusan',
        'ekonomi_cukup',
        'hubungan_keluarga_baik',
        'kelainan_mata'
    ];

    $data = [];
    foreach ($text_fields as $f) {
        $data[$f] = $_POST[$f] ?? '';
    }

    // Proses dynamic rows terapi
    $terapi = [];
    if (!empty($_POST['terapi'])) {
        foreach ($_POST['terapi'] as $index => $row) {
            if (empty($row['jenis_obat']) && empty($row['dosis']) && empty($row['kegunaan']) && empty($row['cara_pemberian']) ) {
                continue;
            }
            $terapi[] = [
                'jenis_obat'     => $row['jenis_obat']     ?? '',
                'dosis'          => $row['dosis']           ?? '',
                'kegunaan'       => $row['kegunaan']        ?? '',
                'cara_pemberian' => $row['cara_pemberian']  ?? '',
            ];
        }
    }

    // Proses dynamic rows penunjang
    $penunjang = [];
    if (!empty($_POST['penunjang'])) {
        foreach ($_POST['penunjang'] as $index => $row) {
            if (empty($row['tipe']) && empty($row['tanggal']) && empty($row['hasil']) && empty($row['satuan']) && empty($row['nilai_rujukan'])) {
                continue;
            }
            $penunjang[] = [
                'tipe'          => $row['tipe']  ?? '',
                'tanggal'       => $row['tanggal']         ?? '',
                'hasil'         => $row['hasil']         ?? '',
                'satuan'        => $row['satuan']  ?? '',
                'nilai_rujukan' => $row['nilai_rujukan']  ?? '',
            ];
        }
    }

    
    $data['terapi'] = json_encode($terapi);
    $data['penunjang'] = json_encode($penunjang);
    
    if (!$submission) {
    $submission_id = createSubmission($user_id, $form_id, null, null, $mysqli);
    } else {
        $submission_id = $submission['id'];
    }
  
    saveSection($submission_id, $section_name, $section_label, $data, $mysqli);
    updateSubmissionStatus($submission_id, $form_id, $mysqli);
    redirectWithMessage($_SERVER['REQUEST_URI'], 'success', 'Data berhasil disimpan.');
}

?>
<main id="main" class="main">
    <?php include "kmb/format_kmb_r_angsana/tab.php"; ?>

    <section class="section dashboard">

        <?php include dirname(__DIR__, 2) . '/partials/notifikasi.php'; ?>
        <?php include dirname(__DIR__, 2) . '/partials/status_section.php'; ?>

        <div class="card">
            <div class="card-body">
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

                    <h5 class="card-title"><strong>5. Data Biologis 3</strong></h5>

                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary"><strong>b. Data Psikologis</strong></label>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-2 col-form-label">
                            <strong>1) Apakah yang diharapkan klien saat ini</strong>
                        </div>
                        <div class="col-sm-10">
                        <textarea name="harapan_klien" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('harapan_klien',$existing_data) ?></textarea>    
                            </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-2 col-form-label">
                            <strong>2) Apakah klien merasa rendah diri dengan keadaannya saat ini</strong>
                        </div>
                        <div class="col-sm-10">
                        <textarea name="rendah_diri" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('rendah_diri',$existing_data) ?></textarea>    
                            </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-2 col-form-label">
                            <strong>3) Bagaimana menurut klien dengan keadaannya saat ini</strong>
                        </div>
                        <div class="col-sm-10">
                        <textarea name="pendapat_keadaan" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('pendapat_keadaan',$existing_data) ?></textarea>    
                            </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-2 col-form-label">
                            <strong>4) Apakah klien tinggal di rumah sendiri atau rumah kontrakan</strong>
                        </div>
                        <div class="col-sm-10">
                        <textarea name="status_rumah" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('status_rumah',$existing_data) ?></textarea>    
                            </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-2 col-form-label">
                            <strong>5) Apakah hubungan antar keluarga harmonis atau berjauhan</strong>
                        </div>
                        <div class="col-sm-10">
                        <textarea name="hubungan_keluarga" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('hubungan_keluarga',$existing_data) ?></textarea>    
                            </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-2 col-form-label">
                            <strong>6) Siapakah yang mengambil keputusan dalam keluarga</strong>
                        </div>
                        <div class="col-sm-10">
                        <textarea name="pengambil_keputusan" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('pengambil_keputusan',$existing_data) ?></textarea>    
                            </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-2 col-form-label">
                            <strong>7) Apakah klien merasa cukup dengan keadaan ekonomi keluarganya saat ini</strong>
                        </div>
                        <div class="col-sm-10">
                        <textarea name="ekonomi_cukup" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('ekonomi_cukup',$existing_data) ?></textarea>    
                            </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-2 col-form-label">
                            <strong>8) Apakah hubungan antar keluarga baik</strong>
                        </div>
                        <div class="col-sm-10">
                        <textarea name="hubungan_keluarga_baik" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('hubungan_keluarga_baik',$existing_data) ?></textarea>    
                            </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-2 col-form-label">

                            <strong>9) Apakah klien aktif mengikuti kegiatan kemasyarakatan di sekitar tempat tinggalnya</strong>
                        </div>
                        <div class="col-sm-10">
                        <textarea name="kelainan_mata" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('kelainan_mata',$existing_data) ?></textarea>    
                            </div>
                    </div>


                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary"><strong>c. Data Penunjang</strong></label>
                    </div>

                        <table class="table table-bordered" id="tabel-penunjang">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width:40px">No</th>
                                    <th class="text-center">Tipe Pemeriksaan</th>
                                    <th class="text-center">Tanggal Pemeriksaan</th>
                                    <th class="text-center">Hasil</th>
                                    <th class="text-center">Satuan</th>
                                    <th class="text-center">Nilai Rujukan</th>
                                    <th class="text-center" style="width:60px">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="tbody-penunjang">
                                 <!-- Dynamic rows masuk sini -->
                        </tbody>
                    </table>
                    <div class="row mb-4">
                        <div class="col-sm-12 d-flex justify-content-end">
                            <button type="button" class="btn btn-primary btn-sm" id="btn-tambah-penunjang" onclick="tambahRowPenunjang()">+ Tambah Baris</button>
                        </div>
                    </div>

                        <small class="text-muted d-block mb-3">
                            Tipe pemeriksaan: <strong>Laboratorium</strong> = hasil lab darah/urin/dll &nbsp;|&nbsp;
                            <strong>Radiologi</strong> = Rontgen, MRI, dll &nbsp;|&nbsp;
                            <strong>Lainnya</strong> = USG, CT Scan, dll
                        </small>

                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary"><strong>d. Terapi/Obat</strong></label>
                    </div>

                        <table class="table table-bordered" id="tabel-terapi">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width:40px">No</th>
                                    <th class="text-center">Jenis Obat</th>
                                    <th class="text-center">Dosis</th>
                                    <th class="text-center">Kegunaan</th>
                                    <th class="text-center">Cara Pemberian</th>
                                    <th class="text-center" style="width:60px">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="tbody-terapi">
                            <!-- Dynamic rows masuk sini -->
                        </tbody>
                        </table>
                        <div class="row mb-4">
                            <div class="col-sm-12 d-flex justify-content-end">
                                <button type="button" class="btn btn-primary btn-sm" id="btn-tambah-terapi" onclick="tambahRowTerapi()">+ Tambah Baris</button>
                            </div>
                        </div>

                            <small class="text-muted">
                                Isi terapi atau obat yang sedang dikonsumsi saat ini.
                            </small>
    

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

           
                    <script>
                        let rowTerapiCount      = 1;
                        let rowPenunjangCount   = 1;
                        const existingTerapi    = <?= json_encode($existing_terapi) ?>;
                        const existingPenunjang = <?= json_encode($existing_penunjang) ?>;
                        const isReadonly = <?= json_encode($is_readonly) ?>;

                        // ---- PENUNJANG ----

                        function autoResizeTextarea(el) {
                            el.style.height = 'auto';
                            el.style.height = el.scrollHeight + 'px';
                        }

                        function tambahRowPenunjang(data = null) {
                            const tbody = document.getElementById('tbody-penunjang');
                            const index = rowPenunjangCount;
                            const row = document.createElement('tr');

                            const opts = ['Laboratorium', 'Radiologi', 'Lainnya']
                                .map(o => `<option value="${o}" ${data?.tipe === o ? 'selected' : ''}>${o}</option>`)
                                .join('');

                            row.innerHTML = `
                                <td class="text-center align-middle">${index}</td>

                                <td>
                                    ${
                                        isReadonly
                                        ? `<div class="readonly-text">${data?.tipe ?? ''}</div>`
                                        : `<select class="form-select form-select-sm" name="penunjang[${index}][tipe]">
                                                <option value="">Pilih</option>
                                                ${opts}
                                        </select>`
                                    }
                                </td>

                                 <td>
                                    <input
                                        type="date"
                                        class="form-control form-control-sm"
                                        name="penunjang[${index}][tanggal]"
                                        value="${data?.tanggal ?? ''}"
                                        ${isReadonly ? 'readonly' : ''}
                                    >
                                </td>

                                <td>
                                    ${
                                        isReadonly
                                        ? `<div class="readonly-text">${data?.hasil ?? ''}</div>`
                                        : `<textarea
                                            class="form-control form-control-sm auto-resize"
                                            name="penunjang[${index}][hasil]"
                                            rows="2"
                                            style="resize:none; overflow:hidden;"
                                            oninput="autoResizeTextarea(this)"
                                        >${data?.hasil ?? ''}</textarea>`
                                    }
                                </td>

                                <td>
                                    ${
                                        isReadonly
                                        ? `<div class="readonly-text">${data?.satuan ?? ''}</div>`
                                        : `<textarea
                                            class="form-control form-control-sm auto-resize"
                                            name="penunjang[${index}][satuan]"
                                            rows="2"
                                            style="resize:none; overflow:hidden;"
                                            oninput="autoResizeTextarea(this)"
                                        >${data?.satuan ?? ''}</textarea>`
                                    }
                                </td>

                                <td>
                                    ${
                                        isReadonly
                                        ? `<div class="readonly-text">${data?.nilai_rujukan ?? ''}</div>`
                                        : `<textarea
                                            class="form-control form-control-sm auto-resize"
                                            name="penunjang[${index}][nilai_rujukan]"
                                            rows="2"
                                            style="resize:none; overflow:hidden;"
                                            oninput="autoResizeTextarea(this)"
                                        >${data?.nilai_rujukan ?? ''}</textarea>`
                                    }
                                </td>

                                <td class="text-center align-middle">
                                    <button type="button" class="btn btn-danger btn-sm" onclick="hapusRow(this)" ${isReadonly ? 'disabled' : ''}>x</button>
                                </td>
                            `;

                            tbody.appendChild(row);

                            row.querySelectorAll('.auto-resize').forEach(autoResizeTextarea);

                            rowPenunjangCount++;
                        }

                        // ---- TERAPI ----

                        function tambahRowTerapi(data = null) {
                        const tbody = document.getElementById('tbody-terapi');
                        const index = rowTerapiCount;
                        const row = document.createElement('tr');

                        row.innerHTML = `
                            <td class="text-center align-middle">${index}</td>
                                <td>
                                    ${
                                    isReadonly
                                    ? `<div class="readonly-text">${data?.jenis_obat ?? ''}</div>`
                                    : `<textarea
                                    class="form-control form-control-sm auto-resize"
                                    name="terapi[${index}][jenis_obat]"
                                    rows="2"
                                    style="resize:none; overflow:hidden;"
                                    oninput="autoResizeTextarea(this)"
                                    >${data?.jenis_obat ?? ''}</textarea>`
                                    }
                                </td>
                                <td>
                                    ${
                                    isReadonly
                                    ? `<div class="readonly-text">${data?.dosis ?? ''}</div>`
                                    : `<textarea
                                    class="form-control form-control-sm auto-resize"
                                    name="terapi[${index}][dosis]"
                                    rows="2"
                                    style="resize:none; overflow:hidden;"
                                    oninput="autoResizeTextarea(this)"
                                    >${data?.dosis ?? ''}</textarea>`
                                    }
                                </td>
                                <td>
                                    ${
                                    isReadonly
                                    ? `<div class="readonly-text">${data?.kegunaan ?? ''}</div>`
                                    : `<textarea
                                    class="form-control form-control-sm auto-resize"
                                    name="terapi[${index}][kegunaan]"
                                    rows="2"
                                    style="resize:none; overflow:hidden;"
                                    oninput="autoResizeTextarea(this)"
                                    >${data?.kegunaan ?? ''}</textarea>`
                                    }
                                </td>
                                <td>
                                    ${
                                    isReadonly
                                    ? `<div class="readonly-text">${data?.cara_pemberian ?? ''}</div>`
                                    : `<textarea
                                    class="form-control form-control-sm auto-resize"
                                    name="terapi[${index}][cara_pemberian]"
                                    rows="2"
                                    style="resize:none; overflow:hidden;"
                                    oninput="autoResizeTextarea(this)"
                                    >${data?.cara_pemberian ?? ''}</textarea>`
                                    }
                                </td>
                            <td class="text-center align-middle">
                                    <button type="button" class="btn btn-danger btn-sm" onclick="hapusRow(this)" ${isReadonly ? 'disabled' : ''}>x</button>
                            </td>
                        `;

                        tbody.appendChild(row);

                        row.querySelectorAll('.auto-resize').forEach(autoResizeTextarea);

                        rowTerapiCount++;
                    }

                    function hapusRow(btn) {
                            btn.closest('tr').remove();
                        }
                        // Load existing rows on page load
                        window.addEventListener('load', function() {
                            if (existingTerapi && existingTerapi.length > 0) {
                                existingTerapi.forEach(row => tambahRowTerapi(row));
                            } else {
                                tambahRowTerapi(); // default 1 row kosong
                            }
                            if (existingPenunjang && existingPenunjang.length > 0) {
                                existingPenunjang.forEach(row => tambahRowPenunjang(row));
                            } else {
                                tambahRowPenunjang(); // default 1 row kosong
                            }
                            // Disable add buttons if readonly
                            if (isReadonly) {
                                document.getElementById('btn-tambah-terapi').setAttribute('disabled', 'disabled');
                                document.getElementById('btn-tambah-penunjang').setAttribute('disabled', 'disabled');
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
                        
