<?php
$form_id       = 9;
$section_name  = 'format_hermodalisa';
$section_label = 'Format Hermodalisa';
include dirname(__DIR__, 2) . '/partials/init_section.php';

// Load existing dynamic rows
$existing_lab  = $existing_data['lab']  ?? [];
$existing_pemeriksaan = $existing_data['pemeriksaan'] ?? [];
// =============================================
// HANDLE POST - MAHASISWA SIMPAN DATA
// =============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $level === 'Mahasiswa') {

    if (isLocked($submission)) {
        redirectWithMessage($_SERVER['REQUEST_URI'], 'error', 'Data tidak dapat diubah karena sedang dalam proses review.');
    }


    // Proses dynamic rows lab
    $lab = [];
    if (!empty($_POST['lab'])) {
        foreach ($_POST['lab'] as $index => $row) {
            if (empty($row['tanggal_pemeriksaan']) && empty($row['nama_pemeriksaan']) && empty($row['hasil']) && empty($row['satuan']) && empty($row['nilai_rujukkan'])) {
                continue;
            }
            $lab[] = [
                'tanggal_pemeriksaan'  => $row['tanggal_pemeriksaan'] ?? '',
                'nama_pemeriksaan'        => $row['nama_pemeriksaan'] ?? '',
                'hasil' => $row['hasil']  ?? '',
                'satuan' => $row['satuan']  ?? '',
                'nilai_rujukkan' => $row['nilai_rujukkan']  ?? '',
            ];
        }
    }
    // Proses dynamic rows pemeriksaan
    $pemeriksaan = [];
    if (!empty($_POST['pemeriksaan'])) {
        foreach ($_POST['pemeriksaan'] as $index => $row) {
            if (empty($row['jam']) && empty($row['td']) && empty($row['nadi'])) {
                continue;
            }
            $pemeriksaan[] = [
                'jam'     => $row['jam']     ?? '',
                'td'          => $row['td']           ?? '',
                'nadi'       => $row['nadi']        ?? '',
                'qb' => $row['qb']  ?? '',
                'tmp' => $row['tmp']  ?? '',
                'teka' => $row['teka']  ?? '',
                'tekv' => $row['tekv']  ?? '',
                'hp' => $row['hp']  ?? '',
            ];
        }
    }


    $data = [
        'pemeriksaan' => $pemeriksaan,
        'lab'  => $lab,
        'nama_mahasiswa'            => $_POST['nama_mahasiswa'] ?? '',
        'nim'                       => $_POST['nim'] ?? '',
        'kelompok'                  => $_POST['kelompok'] ?? '',
        'tempat_dinas'              => $_POST['tempat_dinas'] ?? '',
        'nama_klien'                => $_POST['nama_klien'] ?? '',
        'umur'                      => $_POST['umur'] ?? '',
        'pekerjaan'                 => $_POST['pekerjaan'] ?? '',
        'agama'                     => $_POST['agama'] ?? '',
        'diagnosa_medis'            => $_POST['diagnosa_medis'] ?? '',
        'tgl_pertama_hd'            => $_POST['tgl_pertama_hd'] ?? '',
        'tgl_operasi'               => $_POST['tgl_operasi'] ?? '',
        'pukul_mulai'               => $_POST['pukul_mulai'] ?? '',
        'pukul_selesai'             => $_POST['pukul_selesai'] ?? '',
        'status_emosional'          => $_POST['status_emosional'] ?? '',
        'riwayat_komplikasi'        => $_POST['riwayat_komplikasi'] ?? '',
        'lingkungan'                => $_POST['lingkungan'] ?? '',
        'mesin_hd'                  => $_POST['mesin_hd'] ?? '',
        'pengukuran'                => $_POST['pengukuran'] ?? '',
        'tekanandarah'              => $_POST['tekanandarah'] ?? '',
        'nadi'                      => $_POST['nadi'] ?? '',
        'suhu'                      => $_POST['suhu'] ?? '',
        'rr'                        => $_POST['rr'] ?? '',
        'alat'                      => $_POST['alat'] ?? '',
        'kelainan_mata'             => $_POST['kelainan_mata'] ?? '',
        'pre'                       => $_POST['pre'] ?? '',
        'kelainan_pre'             => $_POST['kelainan_pre'] ?? '',
        'pos'                       => $_POST['pos'] ?? '',
        'kelainan_pos'              => $_POST['kelainan_pos'] ?? '',
        'observasi'                 => $_POST['observasi'] ?? '',
        'kelainan_observasi'        => $_POST['kelainan_observasi'] ?? '',
        'respon'                    => $_POST['respon'] ?? '',
        'kelainan'                  => $_POST['kelainan'] ?? '',
        'health_education'          => $_POST['health_education'] ?? '',
        'hd'                        => $_POST['hd'] ?? '',
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

?>

<main id="main" class="main">
    <?php include "kmb/format_hd_kmb/tab.php"; ?>
    <?php include dirname(__DIR__, 2) . '/partials/notifikasi.php'; ?>
    <?php include dirname(__DIR__, 2) . '/partials/status_section.php'; ?>



    <section class="section dashboard">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-1"><strong>Laporan Hemodialisa (HD)</strong></h5>
                <form class="needs-validation" novalidate action="" method="POST">

                    <!-- NAMA MAHASISWA -->
                    <div class="row mb-3">
                        <div class="col-sm-2 col-form-label">
                            <strong>Nama Mahasiswa</strong>
                        </div>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="nama_mahasiswa" value="<?= val('nama_mahasiswa', $existing_data) ?>" <?= $ro ?>>

                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-2 col-form-label">
                            <strong>NIM</strong>
                        </div>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="nim" value="<?= val('nim', $existing_data) ?>" <?= $ro ?>>

                        </div>
                    </div>


                    <div class="row mb-3">
                        <div class="col-sm-2 col-form-label">
                            <strong>Kelompok</strong>
                        </div>
                        <div class="col-sm-10">
                            <textarea name="kelompok" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('kelompok',$existing_data) ?></textarea>
                            </div>
                    </div>


                    <div class="row mb-3">
                        <div class="col-sm-2 col-form-label">
                            <strong>Tempat Dinas</strong>
                        </div>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="tempat_dinas" value="<?= val('tempat_dinas', $existing_data) ?>" <?= $ro ?>>

                        </div>
                    </div>


                    <!-- A IDENTITAS KLIEN -->
                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary">
                            <strong>A. IDENTITAS KLIEN</strong>
                        </label>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Nama (inisial)</strong></label>

                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="nama_klien" value="<?= val('nama_klien', $existing_data) ?>" <?= $ro ?>>


                        </div>
                    </div>


                    <!-- UMUR -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Umur</strong></label>

                        <div class="col-sm-10">
                            <input type="number" class="form-control" name="umur" value="<?= val('djj', $existing_data) ?>" <?= $ro ?>>


                        </div>
                    </div>

                    <!-- PEKERJAAN -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Pekerjaan</strong></label>

                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="pekerjaan" value="<?= val('pekerjaan', $existing_data) ?>" <?= $ro ?>>


                        </div>
                    </div>

                    <!-- AGAMA -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Agama</strong></label>

                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="agama" value="<?= val('agama', $existing_data) ?>" <?= $ro ?>>


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
                 


                    <!-- TGL MASUK RS -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Tanggal Pertama HD</strong></label>

                        <div class="col-sm-10">
                            <input type="date" class="form-control" name="tgl_pertama_hd" value="<?= val('tgl_pertama_hd', $existing_data) ?>" <?= $ro ?>>


                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Waktu HD</strong></label>

                        <div class="col-sm-10">
                            <div class="row">
                                <div class="col-md-4">
                                    <input type="date" class="form-control" name="tgl_operasi" value="<?= val('tgl_operasi', $existing_data) ?>" <?= $ro ?>>
                                </div>
                                <div class="col-md-4">
                                    <input type="time" class="form-control" name="pukul_mulai" value="<?= val('pukul_mulai', $existing_data) ?>" <?= $ro ?>>
                                </div>
                                <div class="col-md-4">
                                    <input type="time" class="form-control" name="pukul_selesai" value="<?= val('pukul_selesai', $existing_data) ?>" <?= $ro ?>>
                                </div>
                            </div>


                        </div>
                    </div>

                    <!-- C TINDAKAN -->
                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary"><strong>B.Status Emosional Klien dan Keluarga</strong></label>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-2 col-form-label">
                            <strong>Status Emosional Klien dan Keluarga</strong>
                        </div>
                        <div class="col-sm-10">
                            <textarea name="status_emosional" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('status_emosional',$existing_data) ?></textarea>

                           </div>
                    </div>

                    <!-- C TINDAKAN -->
                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary"><strong>C.Riwayat komplikasi HD Sebelumnya (Narasikan komplikasi yang di alami pasien pada HD sebelumnya)</strong></label>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-2 col-form-label">
                            <strong>C.Riwayat komplikasi HD Sebelumnya (Narasikan komplikasi yang di alami pasien pada HD sebelumnya)</strong>
                        </div>
                        <div class="col-sm-10">

                        <textarea name="riwayat_komplikasi" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('riwayat_komplikasi',$existing_data) ?></textarea>

                           </div>
                    </div>



                    <!-- ===================== TABEL LAB ===================== -->
                    <p class="text-primary fw-bold mb-2">D. Nilai Laboratorium Terakhir</p>
                    <table class="table table-bordered" id="tabel-lab">
                        <thead>
                            <tr>
                                <th class="text-center" style="width:40px">No</th>
                                <th class="text-center">Tanggal pemeriksaan</th>
                                <th class="text-center">Nama Pemeriksaan</th>
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


                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary"><strong>E. Persiapan </strong></label>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-2 col-form-label">
                            <strong>1. Lingkungan</strong>
                        </div>
                        <div class="col-sm-10">
                        <textarea name="lingkungan" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('lingkungan',$existing_data) ?></textarea>
                            </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-2 col-form-label">
                            <strong>2. Mesin HD</strong>
                        </div>
                        <div class="col-sm-10">
                        <textarea name="mesin_hd" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('mesin_hd',$existing_data) ?></textarea>
                            </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>3. Klien </strong></label>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-2 col-form-label">
                            <strong>a. Pengukuran Berat Badan</strong>
                        </div>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="pengukuran" value="<?= val('pengukuran', $existing_data) ?>" <?= $ro ?>>

                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-9 col-form-label">
                            <strong>Tanda-tanda Vital</strong>
                        </label>
                    </div>

                    <!-- Tekanan Darah -->
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-2 col-form-label"><strong>TD</strong></label>
                        <div class="col-sm-4">
                            <div class="input-group">
                                <input type="text" class="form-control" name="tekanandarah" value="<?= val('tekanandarah', $existing_data) ?>" <?= $ro ?>>
                                <span class="input-group-text">mmHg</span>
                            </div>
                        </div>

                        <!-- Nadi -->
                        <label class="col-sm-2 col-form-label"><strong>N</strong></label>
                        <div class="col-sm-4">
                            <div class="input-group">
                                <input type="text" class="form-control" name="nadi" value="<?= val('nadi', $existing_data) ?>" <?= $ro ?>>
                                <span class="input-group-text">x/menit</span>
                            </div>
                        </div>
                    </div>



                    <!-- Suhu -->
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-2 col-form-label"><strong>S</strong></label>
                        <div class="col-sm-4">
                            <div class="input-group">
                                <input type="text" class="form-control" name="suhu" value="<?= val('suhu', $existing_data) ?>" <?= $ro ?>>
                                <span class="input-group-text">°C</span>
                            </div>
                        </div>

                        <!-- RR -->
                        <label class="col-sm-2 col-form-label"><strong>RR</strong></label>
                        <div class="col-sm-4">
                            <div class="input-group">
                                <input type="text" class="form-control" name="rr" value="<?= val('rr', $existing_data) ?>" <?= $ro ?>>
                                <span class="input-group-text">x/menit</span>
                            </div>
                        </div>
                    </div>


                    <div class="row mb-3">
                        <div class="col-sm-2 col-form-label">
                            <strong>4. Alat</strong>
                        </div>
                        <div class="col-sm-10">
                            <textarea name="alat" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('alat',$existing_data) ?></textarea>
                            </div>
                    </div>

                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary"><strong>F. Prosedur Kerja</strong></label>
                    </div>
                    <p>(Tuliskan suatu tindakan yang diberikan mulai dari persiapan sampai selesai melakukan HD)</p>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>1. Pre HD</strong></label>

                        <div class="col-sm-10">
                            <textarea name="pre" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('pre',$existing_data) ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>2. Post HD</strong></label>

                        <div class="col-sm-10">
                            <textarea name="pos" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('pos',$existing_data) ?></textarea>
                            </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>3. Observasi</strong></label>

                        <div class="col-sm-10">
                            <textarea name="observasi" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('observasi',$existing_data) ?></textarea>
                            </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>4. Respon terhadap tindakan HD</strong></label>

                        <div class="col-sm-10">
                            <textarea name="respon" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('respon',$existing_data) ?></textarea>
                            </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>5. Hasil yang diperoleh</strong></label>

                        <!-- ===================== TABEL Pemeriksaan===================== -->
                        <table class="table table-bordered" id="tabel-Pemeriksaan">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width:40px">No</th>
                                    <th class="text-center">Jam</th>
                                    <th class="text-center">TD</th>
                                    <th class="text-center">Nadi</th>
                                    <th class="text-center">Qb</th>
                                    <th class="text-center">TMP</th>
                                    <th class="text-center">Tek. A</th>
                                    <th class="text-center">Tek. V</th>
                                    <th class="text-center">Hp</th>
                                    <th class="text-center" style="width:60px">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="tbody-Pemeriksaan">
                                <!-- Dynamic rows masuk sini -->
                            </tbody>
                        </table>
                        <div class="row mb-4">
                            <div class="col-sm-12 d-flex justify-content-end">
                                <button type="button" class="btn btn-primary btn-sm" id="btn-tambah-Pemeriksaan" onclick="tambahRowPemeriksaan()">+ Tambah Pemeriksaan</button>
                            </div>
                        </div>

                    </div>

                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary"><strong>G. Health Education (HE) yang diberikan sebelum meninggalkan HD:</strong></label>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-2 col-form-label">
                            <strong>Health Education (HE) yang diberikan sebelum meninggalkan HD</strong>
                        </div>
                        <div class="col-sm-10">
                        <textarea name="health_education" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('health_education',$existing_data) ?></textarea>

                        </div>
                    </div>


                    <script>
                        let rowPemeriksaanCount = 1;
                        let rowLabCount = 1;
                        const existingPemeriksaan = <?= json_encode($existing_pemeriksaan) ?>;
                        const existingLab = <?= json_encode($existing_lab) ?>;
                        const isReadonly = <?= json_encode($is_readonly) ?>;
                        // ---- pemeriksaan ----

                        function autoResizeTextarea(el) {
                            el.style.height = 'auto';
                            el.style.height = el.scrollHeight + 'px';
                        }

                        function tambahRowPemeriksaan(data = null) {
                            const tbody = document.getElementById('tbody-Pemeriksaan');
                            const index = rowPemeriksaanCount;
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td class="text-center align-middle">${index}</td>
                                <td><input type="date" class="form-control form-control-sm" name="pemeriksaan[${index}][jam]" value="${data?.jam ?? ''}" ${isReadonly ? 'readonly' : ''}></td>
                                <td><input type="text" class="form-control form-control-sm" name="pemeriksaan[${index}][td]" value="${data?.td ?? ''}" ${isReadonly ? 'readonly' : ''}></td>
                                <td><input type="text" class="form-control form-control-sm" name="pemeriksaan[${index}][nadi]" value="${data?.nadi ?? ''}" ${isReadonly ? 'readonly' : ''}></td>
                                <td><input type="text" class="form-control form-control-sm" name="pemeriksaan[${index}][qb]" value="${data?.qb ?? ''}" ${isReadonly ? 'readonly' : ''}></td>
                                <td><input type="text" class="form-control form-control-sm" name="pemeriksaan[${index}][tmp]" value="${data?.tmp ?? ''}" ${isReadonly ? 'readonly' : ''}></td>
                                <td><input type="text" class="form-control form-control-sm" name="pemeriksaan[${index}][teka]" value="${data?.teka ?? ''}" ${isReadonly ? 'readonly' : ''}></td>
                                <td><input type="text" class="form-control form-control-sm" name="pemeriksaan[${index}][tekv]" value="${data?.tekv ?? ''}" ${isReadonly ? 'readonly' : ''}></td>
                                <td><input type="text" class="form-control form-control-sm" name="pemeriksaan[${index}][hp]" value="${data?.hp ?? ''}" ${isReadonly ? 'readonly' : ''}></td>
                                <td class="text-center align-middle">
                                    <button type="button" class="btn btn-danger btn-sm" onclick="hapusRow(this)" ${isReadonly ? 'disabled' : ''}>x</button>
                                </td>
                            `;
                            tbody.appendChild(row);
                            rowPemeriksaanCount++;
                        }
                        // ---- LAB ----

                        function autoResizeTextarea(el) {
                            el.style.height = 'auto';
                            el.style.height = el.scrollHeight + 'px';
                        }

                        function tambahRowLab(data = null) {
                            const tbody = document.getElementById('tbody-lab');
                            const index = rowLabCount;
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td class="text-center align-middle">${index}</td>
                                 <td>
                                    ${
                                        isReadonly
                                            ? `<div class="readonly-text">${data?.tanggal_pemeriksaan ?? ''}</div>`
                                            : `<textarea
                                                    class="form-control form-control-sm auto-resize"
                                                    name="lab[${index}][tanggal_pemeriksaan]"
                                                    rows="2"
                                                    style="resize:none; overflow:hidden;"
                                                    oninput="autoResizeTextarea(this)"
                                                >${data?.tanggal_pemeriksaan ?? ''}</textarea>`
                                    }
                                </td>
                                 <td>
                                    ${
                                        isReadonly
                                            ? `<div class="readonly-text">${data?.nama_pemeriksaan ?? ''}</div>`
                                            : `<textarea
                                                    class="form-control form-control-sm auto-resize"
                                                    name="lab[${index}][nama_pemeriksaan]"
                                                    rows="2"
                                                    style="resize:none; overflow:hidden;"
                                                    oninput="autoResizeTextarea(this)"
                                                >${data?.nama_pemeriksaan ?? ''}</textarea>`
                                    }
                                </td>
                                 <td>
                                    ${
                                        isReadonly
                                            ? `<div class="readonly-text">${data?.hasil ?? ''}</div>`
                                            : `<textarea
                                                    class="form-control form-control-sm auto-resize"
                                                    name="lab[${index}][hasil]"
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
                                                    name="lab[${index}][satuan]"
                                                    rows="2"
                                                    style="resize:none; overflow:hidden;"
                                                    oninput="autoResizeTextarea(this)"
                                                >${data?.satuan ?? ''}</textarea>`
                                    }
                                </td>
                                 <td>
                                    ${
                                        isReadonly
                                            ? `<div class="readonly-text">${data?.nilai_rujukkan ?? ''}</div>`
                                            : `<textarea
                                                    class="form-control form-control-sm auto-resize"
                                                    name="lab[${index}][nilai_rujukkan]"
                                                    rows="2"
                                                    style="resize:none; overflow:hidden;"
                                                    oninput="autoResizeTextarea(this)"
                                                >${data?.nilai_rujukkan ?? ''}</textarea>`
                                    }
                                </td>
                               <td class="text-center align-middle">
                                    <button type="button" class="btn btn-danger btn-sm" onclick="hapusRow(this)" ${isReadonly ? 'disabled' : ''}>x</button>
                                </td>
                            `;
                            tbody.appendChild(row);

                            row.querySelectorAll('.auto-resize').forEach(autoResizeTextarea);
                            
                            rowLabCount++;
                        }

                        function hapusRow(btn) {
                            btn.closest('tr').remove();
                        }
                        // Load existing rows on page load
                        window.addEventListener('load', function() {
                            if (existingPemeriksaan && existingPemeriksaan.length > 0) {
                                existingPemeriksaan.forEach(row => tambahRowPemeriksaan(row));
                            } else {
                                tambahRowPemeriksaan(); // default 1 row kosong
                            }
                            if (existingLab && existingLab.length > 0) {
                                existingLab.forEach(row => tambahRowLab(row));
                            } else {
                                tambahRowLab(); // default 1 row kosong
                            }
                            // Disable add buttons if readonly
                            if (isReadonly) {
                                document.getElementById('btn-tambah-pemeriksaan').setAttribute('disabled', 'disabled');
                                document.getElementById('btn-tambah-lab').setAttribute('disabled', 'disabled');
                            }
                        });
                    </script>


                    <!-- TOMBOL SUBMIT -->
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