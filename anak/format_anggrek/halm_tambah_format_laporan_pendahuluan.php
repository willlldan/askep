<?php
$form_id       = 4;
$section_name  = 'format_lp';
$section_label = 'Format Laporan Pendahuluan';
include dirname(__DIR__, 2) . '/partials/init_section.php';


$tgl_pengkajian = $submission['tanggal_pengkajian'] ?? '';
$rs_ruangan     = $submission['rs_ruangan'] ?? '';
$existing_obat = $existing_data['obat'] ?? [];

// =============================================
// HANDLE POST - MAHASISWA SIMPAN DATA
// =============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $level === 'Mahasiswa') {

    if (isLocked($submission)) {
        redirectWithMessage($_SERVER['REQUEST_URI'], 'error', 'Data tidak dapat diubah karena sedang dalam proses review.');
    }

    $tgl_pengkajian = $_POST['tglpengkajian'] ?? '';
    $rs_ruangan     = $_POST['rsruangan'] ?? '';
    // Proses dynamic rows obat
    $obat = [];
    if (!empty($_POST['obat'])) {
        foreach ($_POST['obat'] as $index => $row) {
            if (empty($row['diagnosa']) && empty($row['tujuan']) && empty($row['intervensi'])) {
                continue;
            }
            $obat[] = [
                'diagnosa'          => $row['diagnosa']           ?? '',
                'tujuan'            => $row['tujuan']        ?? '',
                'intervensi'        => $row['intervensi']  ?? '',
            ];
        }
    }
    $data = [
        'obat' => $obat,
        'tglpengkajian'             => $_POST['tglpengkajian'] ?? '',
        'rsruangan'                 => $_POST['rsruangan'] ?? '',
        'pengertian'                => $_POST['pengertian'] ?? '',
        'etiologi'                => $_POST['etiologi'] ?? '',
        'patofisiologi'            => $_POST['patofisiologi'] ?? '',
        'manifestasi_klinik'        => $_POST['manifestasi_klinik'] ?? '',
        'pemeriksaan_diagnostic'    => $_POST['pemeriksaan_diagnostic'] ?? '',
        'penatalaksanaan'           => $_POST['penatalaksanaan'] ?? '',
        'komplikasi'                => $_POST['komplikasi'] ?? '',
        'pengkajian_keperawatan'    => $_POST['pengkajian_keperawatan'] ?? '',
        'penyimpangan_kdm'          => $_POST['penyimpangan_kdm'] ?? '',
        'diagnosa_keperawatan'      => $_POST['diagnosa_keperawatan'] ?? '',
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

    <?php include "anak/format_anggrek/tab.php"; ?>

    <section class="section dashboard">

        <?php include dirname(__DIR__, 2) . '/partials/notifikasi.php'; ?>
        <?php include dirname(__DIR__, 2) . '/partials/status_section.php'; ?>
        <div class="card mt-3">
            <div class="card-body">
                <form class="needs-validation" novalidate action="" method="POST">

                    <div class="row mb-3 mt-3">
                        <label class="col-sm-2 col-form-label"><strong>Tanggal Pengkajian</strong></label>
                        <div class="col-sm-9">
                            <input type="date" class="form-control" name="tglpengkajian"
                                value="<?= htmlspecialchars($tgl_pengkajian) ?>" <?= $ro ?> required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>RS/Ruangan</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="rsruangan"
                                value="<?= htmlspecialchars($rs_ruangan) ?>" <?= $ro ?> required>
                        </div>
                    </div>


                    <!-- General Form Elements -->

                    <!-- FORMAT LAPORAN PENDAHULUAN -->
                    <h5 class="card-title"><strong>FORMAT LAPORAN PENDAHULUAN</strong></h5>

                    <!-- A. Konsep Dasar Medis -->
                    <div class="row mb-2">
                        <label class="col-sm-3 col-form-label text-primary">
                            <strong>A. Konsep Dasar Medis</strong>
                        </label>
                    </div>

                    <!-<!-- A. Landasan Teori -->
                        <!-- 1. Pengertian -->
                                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><strong>1. Pengertian</strong></label>
                    <div class="col-sm-9">
                        <textarea class="form-control" name="pengertian" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?> required><?= val('pengertian', $existing_data) ?></textarea>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><strong>2. Etiologi</strong></label>
                    <div class="col-sm-9">
                        <textarea class="form-control" name="etiologi" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?> required><?= val('etiologi', $existing_data) ?></textarea>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><strong>3. Patofisiologi</strong></label>
                    <div class="col-sm-9">
                        <textarea class="form-control" name="patofisiologi" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?> required><?= val('patofisiologi', $existing_data) ?></textarea>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><strong>4. Manifestasi Klinik</strong></label>
                    <div class="col-sm-9">
                        <textarea class="form-control" name="manifestasi_klinik" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?> required><?= val('manifestasi_klinik', $existing_data) ?></textarea>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><strong>5. Pemeriksaan Diagnostic</strong></label>
                    <div class="col-sm-9">
                        <textarea class="form-control" name="pemeriksaan_diagnostic" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?> required><?= val('pemeriksaan_diagnostic', $existing_data) ?></textarea>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><strong>6. Penatalaksanaan</strong></label>
                    <div class="col-sm-9">
                        <textarea class="form-control" name="penatalaksanaan" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?> required><?= val('penatalaksanaan', $existing_data) ?></textarea>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><strong>7. Komplikasi</strong></label>
                    <div class="col-sm-9">
                        <textarea class="form-control" name="komplikasi" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?> required><?= val('komplikasi', $existing_data) ?></textarea>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><strong>1. Pengkajian Keperawatan</strong></label>
                    <div class="col-sm-9">
                        <textarea class="form-control" name="pengkajian_keperawatan" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?> required><?= val('pengkajian_keperawatan', $existing_data) ?></textarea>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><strong>2. Penyimpangan KDM</strong></label>
                    <div class="col-sm-9">
                        <textarea class="form-control" name="penyimpangan_kdm" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?> required><?= val('penyimpangan_kdm', $existing_data) ?></textarea>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><strong>3. Diagnosa Keperawatan</strong></label>
                    <div class="col-sm-9">
                        <textarea class="form-control" name="diagnosa_keperawatan" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?> required><?= val('diagnosa_keperawatan', $existing_data) ?></textarea>
                    </div>
                </div>

               

                        <!-- 4. Perencanaan -->
                        <p class="text-primary fw-bold mb-2">4. Perencanaan</p>
                        <table class="table table-bordered" id="tabel-obat">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width:40px">No</th>
                                    <th class="text-center">Diagnosa Keperawatan</th>
                                    <th class="text-center">Tujuan & Kriteria Hasil</th>
                                    <th class="text-center">Intervensi</th>
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

                        <!-- C. Daftar Pustaka -->
                        <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><strong>C. Daftar Pustaka</strong></label>
                    <div class="col-sm-9">
                        <textarea class="form-control" name="daftar_pustaka" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?> required><?= val('daftar_pustaka', $existing_data) ?></textarea>
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
        
      <script>
    let rowObatCount = 1;
    const existingObat = <?= json_encode($existing_obat) ?>;
    const isReadonly = <?= json_encode($is_readonly) ?>;

    // 1. Fungsi auto-resize
    function autoExpand(textarea) {
        textarea.style.height = 'auto';
        textarea.style.height = textarea.scrollHeight + 'px';
    }

    // ---- OBAT ----
    function tambahRowObat(data = null) {
        const tbody = document.getElementById('tbody-obat');
        const index = rowObatCount;
        const row = document.createElement('tr');
        
        // Menggunakan textarea dengan oninput dan gaya yang tepat
        row.innerHTML = `
            <td class="text-center align-middle">${index}</td>
            <td>
                <textarea class="form-control form-control-sm" name="obat[${index}][diagnosa]" rows="1" 
                style="resize:none; overflow:hidden;" oninput="autoExpand(this)" ${isReadonly ? 'readonly' : ''}>${data?.diagnosa ?? ''}</textarea>
            </td>
            <td>
                <textarea class="form-control form-control-sm" name="obat[${index}][tujuan]" rows="1" 
                style="resize:none; overflow:hidden;" oninput="autoExpand(this)" ${isReadonly ? 'readonly' : ''}>${data?.tujuan ?? ''}</textarea>
            </td>
            <td>
                <textarea class="form-control form-control-sm" name="obat[${index}][intervensi]" rows="1" 
                style="resize:none; overflow:hidden;" oninput="autoExpand(this)" ${isReadonly ? 'readonly' : ''}>${data?.intervensi ?? ''}</textarea>
            </td>
            <td class="text-center align-middle">
                <button type="button" class="btn btn-danger btn-sm" onclick="hapusRow(this)" ${isReadonly ? 'disabled' : ''}>x</button>
            </td>
        `;
        tbody.appendChild(row);

        // 2. Penting: Paksa perhitungan tinggi segera setelah row dibuat
        // Ini memastikan data dari database langsung rapi saat halaman dibuka
        row.querySelectorAll('textarea').forEach(el => autoExpand(el));
        
        rowObatCount++;
    }

    function hapusRow(btn) {
        btn.closest('tr').remove();
    }

    // Load existing rows on page load
    window.addEventListener('load', function() {
        if (existingObat && existingObat.length > 0) {
            existingObat.forEach(row => tambahRowObat(row));
        } else {
            tambahRowObat();
        }

        if (isReadonly) {
            document.getElementById('btn-tambah-obat').setAttribute('disabled', 'disabled');
        }
    });
</script>

        <?php include dirname(__DIR__, 2) . '/partials/footer_form.php'; ?>

    </section>
</main>