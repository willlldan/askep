<?php
$form_id       = 11;
$section_name  = 'resume';
$section_label = 'Format Resume Ruang OK';
include dirname(__DIR__, 2) . '/partials/init_section.php';

// Load existing dynamic rows
$existing_terapi = !empty($existing_data['terapi'])
    ? json_decode($existing_data['terapi'], true)
    : [];
  
$existing_penunjang = !empty($existing_data['penunjang'])
    ? json_decode($existing_data['penunjang'], true)
    : [];
   

// =============================================
// HANDLE POST - MAHASISWA SIMPAN DATA
// =============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $level === 'Mahasiswa') {
    if (isLocked($submission)) {
        redirectWithMessage($_SERVER['REQUEST_URI'], 'error', 'Data tidak dapat diubah karena sedang dalam proses review.');
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

    $data = [
        'terapi'     => $terapi,
        'penunjang'  => $penunjang,

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
    ];

    $rows_penunjang = [];
    foreach ($_POST['penunjang'] ?? [] as $row) {
        if (!empty($row['tipe']) || !empty($row['tanggal']) || !empty($row['hasil']) || !empty($row['satuan']) || !empty($row['nilai_rujukan'])) {
            $rows_penunjang[] = [
                'tipe'          => $row['tipe']          ?? '',
                'tanggal'       => $row['tanggal']       ?? '',
                'hasil'         => $row['hasil']         ?? '',
                'satuan'        => $row['satuan']        ?? '',
                'nilai_rujukan' => $row['nilai_rujukan'] ?? '',
            ];
        }
    }
    $data['penunjang'] = json_encode($rows_penunjang);

    $rows_terapi = [];
    foreach ($_POST['terapi'] ?? [] as $row) {
        if (!empty($row['jenis_obat']) || !empty($row['dosis']) || !empty($row['kegunaan']) || !empty($row['cara_pemberian'])) {
            $rows_terapi[] = [
                'jenis_obat'     => $row['jenis_obat']     ?? '',
                'dosis'          => $row['dosis']          ?? '',
                'kegunaan'       => $row['kegunaan']       ?? '',
                'cara_pemberian' => $row['cara_pemberian'] ?? '',
            ];
        }
    }
    $data['terapi'] = json_encode($rows_terapi);

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

    <?php include "kmb/pengkajian_ruang_ok/tab.php"; ?>

    <section class="section dashboard">

        <?php include dirname(__DIR__, 2) . '/partials/notifikasi.php'; ?>
        <?php include dirname(__DIR__, 2) . '/partials/status_section.php'; ?>

        <div class="card">
            <div class="card-body">

                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

                    <h5 class="card-title mb-1"><strong>FORMAT RESUME KEPERAWATAN <br>
                            PRAKTIK KLINIK KEPERAWATAN MEDIKAL BEDAH II DI RUANG OK
                        </strong></h5>

                    <!-- 1. Biodata Klien -->
                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary"><strong>1. Biodata Klien</strong></label>
                    </div>


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
                        <textarea name="alamat" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('alamat',$existing_data) ?></textarea>    
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

                    <!-- 2. Keluhan Utama -->
                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary"><strong>2. Keluhan Utama</strong></label>
                    </div>


                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Keluhan Utama</strong></label>

                        <div class="col-sm-10">
                        <textarea name="keluhan_utama" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('keluhan_utama',$existing_data) ?></textarea>    
                            </div>
                    </div>

                    <!-- 3. Tanda-tanda Vital -->
                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary"><strong>3. Tanda-tanda Vital</strong></label>
                    </div>


                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Tanda-tanda Vital </strong></label>

                        <div class="col-sm-10">
                        <textarea name="tanda_vital" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('tanda_vital',$existing_data) ?></textarea>    
                            </div>
                    </div>

                    <!-- 4. Pengkajian  Data Fokus (Data yang Bermasalah) -->
                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary"><strong>4. Pengkajian Data Fokus (Data yang Bermasalah)</strong></label>
                    </div>


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

                    <!-- 5. Pemeriksaan Penunjang -->
                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary"><strong>5. Pemeriksaan Penunjang</strong></label>
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

                    <!-- 6. Terapi Saat Ini -->
                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary"><strong>6. Terapi Saat Ini</strong></label>
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
                        
