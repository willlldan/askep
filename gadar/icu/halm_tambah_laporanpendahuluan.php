<?php
$form_id       = 21;
$section_name  = 'laporan_pendahuluan';
$section_label = 'Laporan Pendahuluan';
include dirname(__DIR__, 2) . '/partials/init_section.php';

$tgl_pengkajian  = $submission['tanggal_pengkajian'] ?? '';
$rs_ruangan      = $submission['rs_ruangan']         ?? '';

// Load existing dynamic rows
$existing_perencanaan    = $existing_data['perencanaan']      ?? [];
$existing_daftar_pustaka = $existing_data['daftar_pustaka']   ?? [];
$existing_kdm            = $existing_data['penyimpangan_kdm'] ?? '';

// =============================================
// HANDLE POST - MAHASISWA SIMPAN DATA
// =============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $level === 'Mahasiswa') {

    if (isLocked($submission)) {
        redirectWithMessage($_SERVER['REQUEST_URI'], 'error', 'Data tidak dapat diubah karena sedang dalam proses review.');
    }

    $tgl_pengkajian = $_POST['tgl_pengkajian'] ?? '';
    $rs_ruangan     = $_POST['rs_ruangan']     ?? '';

    // Proses dynamic rows perencanaan
    $perencanaan = [];
    if (!empty($_POST['perencanaan'])) {
        foreach ($_POST['perencanaan'] as $index => $row) {
            if (empty($row['diagnosa']) && empty($row['tujuan_kriteria']) && empty($row['intervensi'])) {
                continue;
            }
            $perencanaan[] = [
                'diagnosa'        => $row['diagnosa']        ?? '',
                'tujuan_kriteria' => $row['tujuan_kriteria'] ?? '',
                'intervensi'      => $row['intervensi']      ?? '',
            ];
        }
    }

  

    // Proses upload penyimpangan KDM
    $path_kdm = $existing_data['penyimpangan_kdm'] ?? '';
    if (!empty($_FILES['penyimpangan_kdm']['name'])) {
        $upload = uploadImage($_FILES['penyimpangan_kdm'], 'uploads/kdm/', 50);
        if ($upload['success']) {
            if (!empty($path_kdm) && file_exists($path_kdm)) {
                unlink($path_kdm);
            }
            $path_kdm = $upload['path'];
        } else {
            redirectWithMessage($_SERVER['REQUEST_URI'], 'error', $upload['error']);
            exit;
        }
    }

    $data = [
        'perencanaan'            => $perencanaan,
        // A. Konsep Dasar Medis
        'pengertian'             => $_POST['pengertian']             ?? '',
        'klasifikasi'            => $_POST['klasifikasi']            ?? '',
        'etiologi'               => $_POST['etiologi']               ?? '',
        'patofisiologi'          => $_POST['patofisiologi']          ?? '',
        'manifestasiklinik'      => $_POST['manifestasiklinik']      ?? '',
        'pemeriksaandiagnostik'  => $_POST['pemeriksaandiagnostik']  ?? '',
        'penatalaksanaan'        => $_POST['penatalaksanaan']        ?? '',
        'komplikasi'             => $_POST['komplikasi']             ?? '',

        // B. Konsep Dasar Keperawatan
        'pengkajiankeperawatan'  => $_POST['pengkajiankeperawatan']  ?? '',
        'link_penyimpangan'      => $_POST['link_penyimpangan']      ?? '',
        'diagnosakeperawatan'    => $_POST['diagnosakeperawatan']    ?? '',
        'tujuandankriteriahasil' => $_POST['tujuandankriteriahasil'] ?? '',
        'intervensi'             => $_POST['intervensi']             ?? '',

        // C. Daftar Pustaka
        'daftarpustaka'          => $_POST['daftarpustaka']          ?? '',
        'diagnosa_keperawatan'          => $_POST['diagnosa_keperawatan']          ?? '',
        'pathway'          => $_POST['pathway']          ?? '',

   
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

    <?php include "gadar/icu/tab.php"; ?>

  
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
        
  <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

            <!-- ===================== HEADER ===================== -->
            <div class="card">
                <div class="card-body">

                    <div class="row mb-3 mt-3">
                        <label class="col-sm-2 col-form-label"><strong>Tanggal Pengkajian</strong></label>
                        <div class="col-sm-9">
                            <input type="date" class="form-control" name="tgl_pengkajian"
                                value="<?= htmlspecialchars($tgl_pengkajian) ?>" <?= $ro ?> required>
                            <div class="invalid-feedback">Harap isi Tanggal Pengkajian.</div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>RS/Ruangan</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="rs_ruangan"
                                value="<?= htmlspecialchars($rs_ruangan) ?>" <?= $ro ?> required>
                            <div class="invalid-feedback">Harap isi RS/Ruangan.</div>
                        </div>
                    </div>

                </div>
            </div>
     <div class="card">
    <div class="card-body">
        <h5 class="card-title mb-4 mt-2"><strong>A. KONSEP DASAR MEDIS</strong></h5>

        <div class="row mb-3">
            <label class="col-sm-2 col-form-label"><strong>Pengertian</strong></label>
            <div class="col-sm-9">
                <textarea name="pengertian" class="form-control" rows="3"
                    style="overflow:hidden; resize:none;"
                    oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                    <?= $ro ?>><?= val('pengertian', $existing_data) ?></textarea>
            </div>
        </div>

        <div class="row mb-3">
            <label class="col-sm-2 col-form-label"><strong>Klasifikasi</strong></label>
            <div class="col-sm-9">
                <textarea name="klasifikasi" class="form-control" rows="3"
                    style="overflow:hidden; resize:none;"
                    oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                    <?= $ro ?>><?= val('klasifikasi', $existing_data) ?></textarea>
            </div>
        </div>

        <div class="row mb-3">
            <label class="col-sm-2 col-form-label"><strong>Etiologi</strong></label>
            <div class="col-sm-9">
                <textarea name="etiologi" class="form-control" rows="3"
                    style="overflow:hidden; resize:none;"
                    oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                    <?= $ro ?>><?= val('etiologi', $existing_data) ?></textarea>
            </div>
        </div>

        <div class="row mb-3">
            <label class="col-sm-2 col-form-label"><strong>Patofisiologi</strong></label>
            <div class="col-sm-9">
                <textarea name="patofisiologi" class="form-control" rows="3"
                    style="overflow:hidden; resize:none;"
                    oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                    <?= $ro ?>><?= val('patofisiologi', $existing_data) ?></textarea>
            </div>
        </div>
        <div class="row mb-3">
            <label class="col-sm-2 col-form-label"><strong>Pathway</strong></label>
            <div class="col-sm-9">
                <textarea name="pathway" class="form-control" rows="3"
                    style="overflow:hidden; resize:none;"
                    oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                    <?= $ro ?>><?= val('pathway', $existing_data) ?></textarea>
            </div>
        </div>

        <div class="row mb-3">
            <label class="col-sm-2 col-form-label"><strong>Manifestasi Klinik</strong></label>
            <div class="col-sm-9">
                <textarea name="manifestasiklinik" class="form-control" rows="3"
                    style="overflow:hidden; resize:none;"
                    oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                    <?= $ro ?>><?= val('manifestasiklinik', $existing_data) ?></textarea>
            </div>
        </div>

        <div class="row mb-3">
            <label class="col-sm-2 col-form-label"><strong>Pemeriksaan Diagnostik</strong></label>
            <div class="col-sm-9">
                <textarea name="pemeriksaandiagnostik" class="form-control" rows="3"
                    style="overflow:hidden; resize:none;"
                    oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                    <?= $ro ?>><?= val('pemeriksaandiagnostik', $existing_data) ?></textarea>
            </div>
        </div>

        <div class="row mb-3">
            <label class="col-sm-2 col-form-label"><strong>Penatalaksanaan</strong></label>
            <div class="col-sm-9">
                <textarea name="penatalaksanaan" class="form-control" rows="3"
                    style="overflow:hidden; resize:none;"
                    oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                    <?= $ro ?>><?= val('penatalaksanaan', $existing_data) ?></textarea>
            </div>
        </div>

        <div class="row mb-3">
            <label class="col-sm-2 col-form-label"><strong>Komplikasi</strong></label>
            <div class="col-sm-9">
                <textarea name="komplikasi" class="form-control" rows="3"
                    style="overflow:hidden; resize:none;"
                    oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                    <?= $ro ?>><?= val('komplikasi', $existing_data) ?></textarea>
            </div>
        </div>

    </div>
</div>

      <div class="card mt-4">
    <div class="card-body">
        <h5 class="card-title mb-4 mt-2"><strong>B. KONSEP DASAR KEPERAWATAN</strong></h5>

        <div class="row mb-3">
            <label class="col-sm-2 col-form-label"><strong>Pengkajian Keperawatan</strong></label>
            <div class="col-sm-9">
                <textarea name="pengkajiankeperawatan" class="form-control" rows="3"
                    style="overflow:hidden; resize:none;"
                    oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                    <?= $ro ?>><?= val('pengkajiankeperawatan', $existing_data) ?></textarea>
            </div>
        </div>

        <div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Penyimpangan KDM</strong></label>
    <div class="col-sm-9">
        <textarea name="link_penyimpangan" class="form-control" rows="3" 
            placeholder="Tempel link file Google Drive di sini" 
            style="overflow:hidden; resize:none;" 
            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" 
            <?= $ro ?>><?= val('link_penyimpangan', $existing_data) ?></textarea>
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Diagnosa Keperawatan</strong></label>
    <div class="col-sm-9">
        <textarea name="diagnosa_keperawatan" class="form-control" rows="3" 
            style="overflow:hidden; resize:none;" 
            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" 
            <?= $ro ?>><?= val('diagnosa_keperawatan', $existing_data) ?></textarea>
    </div>
</div>
        
                    <!-- TABEL PERENCANAAN -->
                    <p class="text-primary fw-bold mb-2">Intervensi</p>

                    <table class="table table-bordered" id="tabel-perencanaan">
                        <thead>
                            <tr>
                                <th class="text-center" style="width:40px">No</th>
                                <th class="text-center">Diagnosa</th>
                                <th class="text-center">Tujuan dan Kriteria Hasil</th>
                                <th class="text-center">Intervensi</th>
                                <?php if (!$is_readonly): ?>
                                    <th class="text-center" style="width:60px">Aksi</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody id="tbody-perencanaan"></tbody>
                    </table>

                    <?php if (!$is_readonly): ?>
                        <div class="row mb-4">
                            <div class="col-sm-12 d-flex justify-content-end">
                                <button type="button" class="btn btn-primary btn-sm" onclick="tambahRowPerencanaan()">+ Tambah Intervensi</button>
                            </div>
                        </div>
                    <?php endif; ?>

                </div>
            </div>

  <div class="card mt-4">
    <div class="card-body">
        <h5 class="card-title mb-4 mt-2"><strong>C. DAFTAR PUSTAKA</strong></h5>

        <div class="row mb-3">
            <label class="col-sm-2 col-form-label"><strong>Daftar Pustaka</strong></label>
            <div class="col-sm-9">
                <textarea name="daftarpustaka" class="form-control" rows="3"
                    style="overflow:hidden; resize:none;"
                    oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                    <?= $ro ?>><?= val('daftarpustaka', $existing_data) ?></textarea>
            </div>
        </div>

    
 <!-- TOMBOL SIMPAN (mahasiswa only) -->
                    <?php if (!$is_dosen): ?>

                        <div class="row mb-3">
                            <div class="col-sm-12 d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">Simpan Data</button>
                            </div>
                        </div>

                    <?php endif; ?>

                </div>
            </div>
    </div>
</div>

            <script>
                let rowPerencanaanCount = 1;
                let pustakCount = 0;
                const isReadonly = <?= $is_readonly ? 'true' : 'false' ?>;

                const existingPerencanaan = <?= json_encode($existing_perencanaan) ?>;
                const existingDaftarPustaka = <?= json_encode($existing_daftar_pustaka) ?>;

                // ---- PERENCANAAN ----
                function tambahRowPerencanaan(data = null) {
                    const tbody = document.getElementById('tbody-perencanaan');
                    const index = rowPerencanaanCount;
                    const row = document.createElement('tr');
                    const aksiCol = isReadonly ? '' : `
                        <td class="text-center align-middle">
                            <button type="button" class="btn btn-danger btn-sm" onclick="hapusRow(this)">x</button>
                        </td>`;

                    row.innerHTML = `
                        <td class="text-center align-middle">${index}</td>
                        <td>
                            <textarea class="form-control form-control-sm"
                                name="perencanaan[${index}][diagnosa]"
                                rows="2" style="resize:none; overflow:hidden;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                ${isReadonly ? 'readonly' : ''}
                            >${data?.diagnosa ?? ''}</textarea>
                        </td>
                        <td>
                            <textarea class="form-control form-control-sm"
                                name="perencanaan[${index}][tujuan_kriteria]"
                                rows="2" style="resize:none; overflow:hidden;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                ${isReadonly ? 'readonly' : ''}
                            >${data?.tujuan_kriteria ?? ''}</textarea>
                        </td>
                        <td>
                            <textarea class="form-control form-control-sm"
                                name="perencanaan[${index}][intervensi]"
                                rows="2" style="resize:none; overflow:hidden;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                ${isReadonly ? 'readonly' : ''}
                            >${data?.intervensi ?? ''}</textarea>
                        </td>
                        ${aksiCol}
                    `;

                    tbody.appendChild(row);
                    rowPerencanaanCount++;
                }

                // ---- DAFTAR PUSTAKA ----
                function tambahPustaka(value = '') {
                    const container = document.getElementById('list-pustaka');
                    const index = pustakCount;
                    const div = document.createElement('div');

                    div.className = 'row mb-2 pustaka-item';
                    div.innerHTML = isReadonly ?
                        `<div class="col-sm-11 d-flex align-items-center gap-2">
                                <span class="text-muted fw-bold" style="min-width:24px;">${index + 1}.</span>
                                <input type="text" class="form-control" value="${value}" readonly>
                           </div>` :
                        `<div class="col-sm-11 d-flex align-items-center gap-2">
                                <span class="text-muted fw-bold" style="min-width:24px;">${index + 1}.</span>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="daftar_pustaka[]"
                                        value="${value}" placeholder="Masukkan referensi pustaka...">
                                    <button type="button" class="btn btn-danger" onclick="hapusPustaka(this)">x</button>
                                </div>
                           </div>`;

                    container.appendChild(div);
                    pustakCount++;
                }

                function hapusRow(btn) {
                    btn.closest('tr').remove();
                }

                function hapusPustaka(btn) {
                    btn.closest('.pustaka-item').remove();
                    document.querySelectorAll('.pustaka-item').forEach((item, i) => {
                        item.querySelector('span.text-muted').textContent = (i + 1) + '.';
                    });
                }

                // Load existing data on page load
                window.addEventListener('load', function() {
                    if (existingPerencanaan && existingPerencanaan.length > 0) {
                        existingPerencanaan.forEach(row => tambahRowPerencanaan(row));
                    } else if (!isReadonly) {
                        tambahRowPerencanaan();
                    }

                    if (existingDaftarPustaka && existingDaftarPustaka.length > 0) {
                        existingDaftarPustaka.forEach(v => tambahPustaka(v));
                    } else if (!isReadonly) {
                        tambahPustaka();
                    }
                });

                const existingData = <?= json_encode($existing_data) ?>;
            </script>

        </form>

        <?php include dirname(__DIR__, 2) . '/partials/footer_form.php'; ?>
        </div>
        </div>

    </section>
</main>
                        


