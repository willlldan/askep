<?php
require_once "koneksi.php";
require_once "utils.php";

$form_id       = 30;
$section_name  = 'pengkajian';
$section_label = 'Pengkajian';
include dirname(__DIR__, 2) . '/partials/init_section.php';

$tgl_pengkajian = $submission['tanggal_pengkajian'] ?? '';
$puskesmas      = $submission['rs_ruangan'] ?? '';

// Load existing dynamic rows
$existing_keluarga     = $existing_data['keluarga']     ?? [];

// =============================================
// HANDLE POST - MAHASISWA SIMPAN DATA
// =============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $level === 'Mahasiswa') {
    

    if (isLocked($submission)) {
        redirectWithMessage($_SERVER['REQUEST_URI'], 'error', 'Data tidak dapat diubah karena sedang dalam proses review.');
    }

    $tgl_pengkajian = $_POST['tglpengkajian'] ?? '';
    $puskesmas      = $_POST['rs_ruangan'] ?? ''; 

    // Tabel Keluarga
    $keluarga = parse_dynamic_rows($_POST['keluarga'] ?? [], ['nama_inisial', 'jenis_kelamin', 'hub_dengankk', 'umur','pendidikan',
    'status_gizi_bb', 'status_gizi_tb', 'status_gizi_imt', 'status_imunisasi', 'kondisi_kesehatan', 'ttv_td', 'ttv_n', 'ttv_s', 'ttv_rr']);
    
    $data = [
        'namakk'                   => $_POST['namakk'] ?? '',
        'tempattgllahir'           => $_POST['tempattgllahir'] ?? '',
        'alamat'                   => $_POST['alamat'] ?? '',
        'pendidikankk'             => $_POST['pendidikankk'] ?? '',
        'tipekk'                   => $_POST['tipekk'] ?? '',
        'sukubangsa'               => $_POST['sukubangsa'] ?? '',
        'agama'                    => $_POST['agama'] ?? '',
        'statussosialekonomi'      => $_POST['statussosialekonomi'] ?? '',
        'aktivitasrekreasi'        => $_POST['aktivitasrekreasi'] ?? '',
        'komposisikeluarga'        => $_POST['komposisikeluarga'] ?? '',
        'genogram'                 => $_POST['genogram'] ?? '',
        'g1'                       => $_POST['g1'] ?? '',
        'g2'                       => $_POST['g2'] ?? '',
        'g3'                       => $_POST['g3'] ?? '',
        'perkembangankeluarga'     => $_POST['perkembangankeluarga'] ?? '',
        'belumterpenuhi'           => $_POST['belumterpenuhi'] ?? '',
        'kondisikesehatankeluarga' => $_POST['kondisikesehatankeluarga'] ?? '',
        'anggotasakitdankeluhan'   => $_POST['anggotasakitdankeluhan'] ?? '',
        'keluhansakit'             => $_POST['keluhansakit'] ?? '',
        'penyakitkronik'           => $_POST['penyakitkronik'] ?? '',
        'penyakitdiderita'         => $_POST['penyakitdiderita'] ?? '',

        'keluarga'                 => $keluarga,
        
    ];

    
     if (!$submission) {
        $submission_id = createSubmission($user_id, $form_id, $puskesmas, $rs_ruangan, $mysqli);
    } else {
        $submission_id = $submission['id'];
        updateSubmissionHeader($submission_id, $tgl_pengkajian, $puskesmas, $mysqli);
    }

    saveSection($submission_id, $section_name, $section_label, $data, $mysqli);
    updateSubmissionStatus($submission_id, $form_id, $mysqli);
    redirectWithMessage($_SERVER['REQUEST_URI'], 'success', 'Data berhasil disimpan.');
}
?>

<main id="main" class="main">

    <?php include "keluarga/format_keluarga/tab.php"; ?>

    <section class="section dashboard">

        <?php include dirname(__DIR__, 2) . '/partials/notifikasi.php'; ?>
        <?php include dirname(__DIR__, 2) . '/partials/status_section.php'; ?>

        <div class="card">
            <div class="card-body">

                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

                <div class="row mb-3 mt-3">
                        <label class="col-sm-2 col-form-label"><strong>Tanggal Pengkajian</strong></label>
                        <div class="col-sm-10">
                            <input type="date" class="form-control" name="tglpengkajian"
                                value="<?= htmlspecialchars($tgl_pengkajian) ?>" <?= $ro ?> required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Puskesmas</strong></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="rs_ruangan"
                                value="<?= htmlspecialchars($puskesmas) ?>" <?= $ro ?> required>
                        </div>
                    </div>

                <h5 class="card-title"><strong>A. PENGKAJIAN</strong></h5>

                <h5 class="card-title mb-1"><strong>I. Data Umum</strong></h5>   

                    <!-- Bagian Nama -->

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Nama KK</strong></label>

                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="namakk"
                                value="<?= val('namakk', $existing_data) ?>" <?= $ro ?>>
                         </div>
                    </div>

                    <!-- Bagian Tempat/ Tgl Lahir -->

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Tempat/Tanggal Lahir</strong></label>

                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="tempattgllahir"
                                value="<?= val('tempattgllahir', $existing_data) ?>" <?= $ro ?>>
                         </div>
                    </div>    
                    
                <!-- Bagian Alamat -->
                <div class="row mb-3">
                    <label for="alamat" class="col-sm-2 col-form-label"><strong>Alamat</strong></label>
                    <div class="col-sm-10">
                      <textarea name="alamat" class="form-control auto-resize" style="overflow:hidden; resize:none;"
                        oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('alamat',$existing_data) ?></textarea>
                         </div>
                    </div>    

                 

                <!-- Bagian Pendidikan KK -->
                <div class="row mb-3">
                    <label for="pendidikankk" class="col-sm-2 col-form-label"><strong>Pendidikan KK</strong></label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="pendidikankk"
                                value="<?= val('pendidikankk', $existing_data) ?>" <?= $ro ?>>
                         </div>
                    </div>

                <!-- Bagian Tipe Keluarga -->
                <div class="row mb-3">
                    <label for="tipekeluarga" class="col-sm-2 col-form-label"><strong>Tipe Keluarga</strong></label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="tipekk"
                                value="<?= val('tipekk', $existing_data) ?>" <?= $ro ?>>
                         </div>
                    </div>  
                    
                <!-- Bagian Suku Bangsa -->
                <div class="row mb-3">
                    <label for="sukubangsa" class="col-sm-2 col-form-label"><strong>Suku Bangsa</strong></label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="sukubangsa"
                                value="<?= val('sukubangsa', $existing_data) ?>" <?= $ro ?>>
                         </div>
                    </div>   
                    
                 <!-- Bagian Agama -->
                <div class="row mb-3">
                    <label for="agama" class="col-sm-2 col-form-label"><strong>Agama</strong></label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="agama"
                                value="<?= val('agama', $existing_data) ?>" <?= $ro ?>>
                         </div>
                    </div>    

                <!-- Bagian Status Sosial Ekomoni -->
                <div class="row mb-3">
                    <label for="statussosialekonomi" class="col-sm-2 col-form-label"><strong>Status Sosial Ekonomi</strong></label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="statussosialekonomi"
                                value="<?= val('statussosialekonomi', $existing_data) ?>" <?= $ro ?>>
                         </div>
                    </div>

                <!-- Bagian Aktivitas Rekreasi -->
                <div class="row mb-3">
                    <label for="aktivitasrekreasi" class="col-sm-2 col-form-label"><strong>Aktivitas Rekreasi</strong></label>
                    <div class="col-sm-10">
                        <textarea name="aktivitasrekreasi" class="form-control auto-resize" style="overflow:hidden; resize:none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('aktivitasrekreasi',$existing_data) ?></textarea>
                         </div>
                    </div>

                <!-- Bagian Komposisi Keluarga -->
                <div class="row mb-3">
                    <label for="komposisikeluarga" class="col-sm-2 col-form-label"><strong>Komposisi Keluarga</strong></label>

                    <div class="col-sm-10">
                        <input type="text" name="komposisikeluarga" class="form-control" placeholder="Lampirkan link Google Drive upload KK"
                            value="<?= val('komposisikeluarga', $existing_data) ?>"
                            <?= $ro ?>>
                    </div>
                </div>
    
                <!-- Tabel Keluarga -->

                <table class="table table-bordered" id="tabel-keluarga">
                        <thead>
                            <tr>
                                <th class="text-center" style="width:40px">No</th>
                                <th class="text-center align-middle">Nama (Inisial)</th>
                                <th class="text-center align-middle">Jenis Kelamin</th>
                                <th class="text-center align-middle">Hub. dengan KK</th>
                                <th class="text-center align-middle">Umur</th>
                                <th class="text-center align-middle">Pendidikan</th>
                                <th class="text-center align-middle">Status Gizi</th>
                                <th class="text-center align-middle">Status Imunisasi</th>
                                <th class="text-center align-middle">Kondisi Kesehatan</th>
                                <th class="text-center align-middle">TTV</th>
                                <?php if (!$is_readonly): ?>
                                    <th class="text-center" style="width:60px">Aksi</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody id="tbody-keluarga"></tbody>
                    </table>

                    <?php if (!$is_readonly): ?>
                        <div class="row mb-3">
                            <div class="col-sm-12 d-flex justify-content-end">
                                <button type="button" class="btn btn-primary btn-sm"
                                    onclick="tambahRowKeluarga()">+ Tambah Baris</button>
                            </div>
                        </div>
                    <?php endif; ?>
               

                <!-- Bagian Genogram -->
                <div class="row mb-3">
                    <label for="genogram" class="col-sm-2 col-form-label"><strong>Genogram dan Keterangan Gambar</strong></label>

                    <div class="col-sm-10">
                        <input type="text" name="genogram" class="form-control" placeholder="Lampirkan link Google Drive upload Genogram"
                            value="<?= val('genogram', $existing_data) ?>"
                            <?= $ro ?>>
                    </div>
                </div>
                            
                     <!-- Bagian G1 G2 G3 -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>G1 G2 G3</strong></label>

                        <div class="col-sm-10">
                            <div class="row">
                                
                                <div class="col-md-4">
                                    <small>G1</small>
                                    <input type="text" class="form-control" name="g1"
                                    value="<?= val('g1', $existing_data) ?>" <?= $ro ?>>
                                </div>

                                <div class="col-md-4">
                                    <small>G2</small>
                                    <input type="text" class="form-control" name="g2"
                                    value="<?= val('g2', $existing_data) ?>" <?= $ro ?>>
                                </div>

                                <div class="col-md-4">
                                    <small>G3</small>
                                    <input type="text" class="form-control" name="g3"
                                    value="<?= val('g3', $existing_data) ?>" <?= $ro ?>>
                                </div>

                            </div>
                        </div>
                    </div>
    
            <h5 class="card-title"><strong>II. Riwayat dan Tahap Perkembangan Keluarga</strong></h5>    
                
                <!-- Bagian Tahap Perkembangan Keluarga Saat ini -->
               <div class="row mb-3">
                <label class="col-sm-3 col-form-label">
                    <strong>a. Tahap Perkembangan Keluarga</strong>
                </label>
                
                <div class="col-sm-9">

                    <div class="form-check">
                        <input class="form-check-input" type="radio" id="tahap1" name="perkembangankeluarga" value="tahap1" required>
                        <label class="form-check-label" for="tahap1">
                            Tahap 1: Keluarga pasangan baru
                        </label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="radio" id="tahap2" name="perkembangankeluarga" value="tahap2">
                        <label class="form-check-label" for="tahap2">
                            Tahap 2: Keluarga dengan kelahiran anak pertama
                        </label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="radio" id="tahap3" name="perkembangankeluarga" value="tahap3" required>
                        <label class="form-check-label" for="tahap3">
                            Tahap 3: Keluarga dengan anak pra sekolah
                        </label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="radio" id="tahap4" name="perkembangankeluarga" value="tahap4">
                        <label class="form-check-label" for="tahap4">
                            Tahap 4: Keluarga dengan anak sekolah
                        </label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="radio" id="tahap5" name="perkembangankeluarga" value="tahap5" required>
                        <label class="form-check-label" for="tahap5">
                            Tahap 5: Keluarga dengan anak remaja
                        </label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="radio" id="tahap6" name="perkembangankeluarga" value="tahap6">
                        <label class="form-check-label" for="tahap6">
                            Tahap 6: Keluarga dengan usia dewasa/pertengahan
                        </label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="radio" id="tahap7" name="perkembangankeluarga" value="tahap7" required>
                        <label class="form-check-label" for="tahap7">
                            Tahap 7: Keluarga dengan usia pelepasan
                        </label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="radio" id="tahap8" name="perkembangankeluarga" value="tahap8">
                        <label class="form-check-label" for="tahap8">
                            Tahap 8: Keluarga dengan lanjut usia
                        </label>
                    </div>
                    </div>
            </div>

            <!-- Bagian Tahap Perkembangan Keluarga yang Belum Terpenuhi -->

             <div class="row mb-3">
                <label for="belumterpenuhi" class="col-sm-2 col-form-label"><strong>b. Tahap Perkembangan Keluarga yang Belum Terpenuhi</strong></label>
                    <div class="col-sm-10">
                        <textarea name="belumterpenuhi" class="form-control auto-resize" style="overflow:hidden; resize:none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('belumterpenuhi',$existing_data) ?></textarea></div>
                    </div>  
                    
            <h5 class="card-title"><strong>III. Riwayat Kesehatan Keluarga Inti</strong></h5> 
            
            <!-- Bagian Gambaran Umum Kondisi Kesehatan Seluruh Anggota Keluarga -->

             <div class="row mb-3">
                <label for="kondisikesehatankeluarga" class="col-sm-2 col-form-label"><strong>Gambaran Umum Kondisi Kesehatan Seluruh Anggota Keluarga</strong></label>
                    <div class="col-sm-10">
                       <textarea name="kondisikesehatankeluarga" class="form-control auto-resize" style="overflow:hidden; resize:none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('kondisikesehatankeluarga',$existing_data) ?></textarea></div>
                    </div> 
                    
                 <!-- Bagian Saat Ini Anggota Keluarga yang Sakit dan Keluhan -->

                <div class="row mb-3">
                    <label for="anggotasakitdankeluhan" class="col-sm-2 col-form-label"><strong>Saat Ini Anggota Keluarga yang Sakit dan Keluhan</strong></label>
                        <div class="col-sm-10">
                        <textarea name="anggotasakitdankeluhan" class="form-control auto-resize" style="overflow:hidden; resize:none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('anggotasakitdankeluhan',$existing_data) ?></textarea></div>
                        </div>    
                        
            <!-- Bagian Keluhan/Sakit yang Sering Dialami Berulang Dalam Keluarga -->

             <div class="row mb-3">
                <label for="keluhansakit" class="col-sm-2 col-form-label"><strong>Keluhan/Sakit yang Sering Dialami Berulang Dalam Keluarga</strong></label>
                    <div class="col-sm-10">
                       <textarea name="keluhansakit" class="form-control auto-resize" style="overflow:hidden; resize:none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('keluhansakit',$existing_data) ?></textarea></div>
                    </div> 
                    
             <!-- Bagian Anggota Keluarga yang Menderita Penyakit Kronik Membutuhkan Penanganan/Perawatan -->

             <div class="row mb-3">
                <label for="penyakitkronik" class="col-sm-2 col-form-label"><strong>Anggota Keluarga yang Menderita Penyakit Kronik Membutuhkan Penanganan/ Perawatan</strong></label>
                    <div class="col-sm-10">
                       <textarea name="penyakitkronik" class="form-control auto-resize" style="overflow:hidden; resize:none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('penyakitkronik',$existing_data) ?></textarea></div>
                    </div>    

            <h5 class="card-title"><strong>IV. Riwayat Kesehatan Keluarga Sebelumnya</strong></h5>   
             
             <!-- Bagian Anggota Keluarga yang Pernah Dirawat dan Penyakit yang Diderita -->

             <div class="row mb-3">
                <label for="penyakitdiderita" class="col-sm-2 col-form-label"><strong>Anggota Keluarga yang Pernah Dirawat dan Penyakit yang Diderita</strong></label>
                    <div class="col-sm-10">
                       <textarea name="penyakitdiderita" class="form-control auto-resize" style="overflow:hidden; resize:none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('penyakitdiderita',$existing_data) ?></textarea></div>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.auto-resize').forEach(function(el) {
        el.style.height = 'auto';
        el.style.height = el.scrollHeight + 'px';
    });
});
</script>

<script>
    const isReadonly = <?= json_encode($is_readonly) ?>;
    const existingKeluarga = <?= json_encode($existing_keluarga) ?>;

    let rowKeluargaCount = 1;

    function hapusRow(btn) {
        btn.closest('tr').remove();
    }

    function mkTextarea(name, value, rows = 2) {
        return `<textarea class="form-control form-control-sm"
        name="${name}" rows="${rows}"
        style="resize:none; overflow:hidden;"
        oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
        ${isReadonly ? 'readonly' : ''}
    >${value ?? ''}</textarea>`;
    }

    function mkInput(name, value, type = 'text') {
        return `<input type="${type}" class="form-control form-control-sm"
        name="${name}" value="${value ?? ''}"
        ${isReadonly ? 'readonly' : ''}>`;
    }

    const aksiCol = isReadonly ? '' : `
    <td class="text-center align-middle">
        <button type="button" class="btn btn-danger btn-sm" onclick="hapusRow(this)">x</button>
    </td>`;

    // =============================================
    // Keluarga
    // =============================================
    function tambahRowKeluarga(data = null) {
        const tbody = document.getElementById('tbody-keluarga');
        const i = rowKeluargaCount;
        const row = document.createElement('tr');
        row.innerHTML = `
        <tr>
            <td>${i}</td>

            <td>${mkTextarea(`keluarga[${i}][nama_inisial]`, data?.nama_inisial)}</td>

            <td>${mkTextarea(`keluarga[${i}][jenis_kelamin]`, data?.jenis_kelamin)}</td>

            <td>${mkTextarea(`keluarga[${i}][hub_dengankk]`, data?.hub_dengankk)}</td>

            <td>${mkTextarea(`keluarga[${i}][umur]`, data?.umur)}</td>

            <td>${mkTextarea(`keluarga[${i}][pendidikan]`, data?.pendidikan)}</td>

            <td>
                <div class="mb-1">
                    <label>BB</label>
                    ${mkTextarea(`keluarga[${i}][status_gizi_bb]`, data?.status_gizi_bb)}
                </div>

                <div class="mb-1">
                    <label>TB</label>
                    ${mkTextarea(`keluarga[${i}][status_gizi_tb]`, data?.status_gizi_tb)}
                </div>

                <div>
                    <label>IMT</label>
                    ${mkTextarea(`keluarga[${i}][status_gizi_imt]`, data?.status_gizi_imt)}
                </div>
            </td>

            <td>${mkTextarea(`keluarga[${i}][status_imunisasi]`, data?.status_imunisasi)}</td>

            <td>${mkTextarea(`keluarga[${i}][kondisi_kesehatan]`, data?.kondisi_kesehatan)}</td>

            <td>
                <div class="mb-1">
                    <label>TD</label>
                    ${mkTextarea(`keluarga[${i}][ttv_td]`, data?.ttv_td)}
                </div>

                <div class="mb-1">
                    <label>N</label>
                    ${mkTextarea(`keluarga[${i}][ttv_n]`, data?.ttv_n)}
                </div>

                <div class="mb-1">
                    <label>S</label>
                    ${mkTextarea(`keluarga[${i}][ttv_s]`, data?.ttv_s)}
                </div>

                <div>
                    <label>RR</label>
                    ${mkTextarea(`keluarga[${i}][ttv_rr]`, data?.ttv_rr)}
                </div>
            </td>

            ${aksiCol}
        </tr>
        `;
        tbody.appendChild(row);
        rowKeluargaCount++;
    }

    window.addEventListener('load', () => {

    if (existingKeluarga && existingKeluarga.length > 0) {
        existingKeluarga.forEach(item => tambahRowKeluarga(item));
    } else {
        tambahRowKeluarga();
    }

    });

</script>