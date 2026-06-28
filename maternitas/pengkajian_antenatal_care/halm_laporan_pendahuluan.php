<?php
$form_id       = $form_id ?? 1;
$section_name  = $section_name ?? 'laporan_pendahuluan';
$section_label = $section_label ?? 'Laporan Pendahuluan';
$tab_include   = $tab_include ?? 'maternitas/pengkajian_antenatal_care/tab.php';
include dirname(__DIR__, 2) . '/partials/init_section.php';

$tgl_pengkajian  = $submission['tanggal_pengkajian'] ?? '';
$rs_ruangan      = $submission['rs_ruangan']         ?? '';

// Load existing dynamic rows
$existing_daftar_pustaka = $existing_data['daftar_pustaka'] ?? [];
$existing_perencanaan    = $existing_data['perencanaan']      ?? [];

// =============================================
// HANDLE POST - MAHASISWA SIMPAN DATA
// =============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $level === 'Mahasiswa') {

    if (isLocked($submission)) {
        redirectWithMessage($_SERVER['REQUEST_URI'], 'error', 'Data tidak dapat diubah karena sedang dalam proses review.');
    }

    $tgl_pengkajian = $_POST['tgl_pengkajian'] ?? '';
    $rs_ruangan     = $_POST['rs_ruangan']     ?? '';

    // Proses dynamic list daftar pustaka
    $daftar_pustaka = [];
    if (!empty($_POST['daftar_pustaka'])) {
        foreach ($_POST['daftar_pustaka'] as $item) {
            if (empty(trim($item))) continue;
            $daftar_pustaka[] = trim($item);
        }
    }
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


    $data = [
        'perencanaan'            => $perencanaan,
        // A. Konsep Dasar Medis
        'pengertian_anc'         => $_POST['pengertian_anc']         ?? '',
        'etiologi_anc'           => $_POST['etiologi_anc']           ?? '',
        // Manifestasi Klinis
        'tanda_presumtif'        => $_POST['tanda_presumtif']        ?? '',
        'tanda_probable'         => $_POST['tanda_probable']         ?? '',
        'tanda_pasti'            => $_POST['tanda_pasti']            ?? '',
        // Perubahan Fisik
        'perubahan_fisik_tm1'    => $_POST['perubahan_fisik_tm1']    ?? '',
        'perubahan_fisik_tm2'    => $_POST['perubahan_fisik_tm2']    ?? '',
        'perubahan_fisik_tm3'    => $_POST['perubahan_fisik_tm3']    ?? '',
        // Adaptasi Fisiologis - Perubahan Psikologis
        'psikologis_tm1'         => $_POST['psikologis_tm1']         ?? '',
        'psikologis_tm2'         => $_POST['psikologis_tm2']         ?? '',
        'psikologis_tm3'         => $_POST['psikologis_tm3']         ?? '',
        // Komplikasi & Penatalaksanaan
        'komplikasi'             => $_POST['komplikasi']             ?? '',
        'penatalaksanaan_medis'  => $_POST['penatalaksanaan_medis']  ?? '',
        // B. Konsep Dasar Keperawatan
        'pengkajian_keperawatan' => $_POST['pengkajian_keperawatan'] ?? '',
        'diagnosa_keperawatan'         => $_POST['diagnosa_keperawatan']         ?? '',
        // C. Daftar Pustaka
        'daftar_pustaka'         => $daftar_pustaka,
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

    <?php include $tab_include; ?>

    <section class="section dashboard">

        <?php include "partials/notifikasi.php"; ?>
        <?php include "partials/status_section.php"; ?>

        <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

            <!-- ===================== HEADER ===================== -->
            <div class="card">
                <div class="card-body">

                    <div class="row mb-3 mt-3">
                        <label class="col-sm-2 col-form-label"><strong>Tanggal Pengkajian</strong></label>
                        <div class="col-sm-10">
                            <input type="date" class="form-control" name="tgl_pengkajian"
                                value="<?= htmlspecialchars($tgl_pengkajian) ?>" <?= $ro ?> required>
                            <div class="invalid-feedback">Harap isi Tanggal Pengkajian.</div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>RS/Ruangan</strong></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="rs_ruangan"
                                value="<?= htmlspecialchars($rs_ruangan) ?>" <?= $ro ?> required>
                            <div class="invalid-feedback">Harap isi RS/Ruangan.</div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- ===================== A. KONSEP DASAR MEDIS ===================== -->
            <div class="card">
                <div class="card-body">

                    <h5 class="card-title"><strong>A. Konsep Dasar Medis</strong></h5>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Pengertian Antenatal Care</strong></label>
                        <div class="col-sm-10">
                        <textarea name="pengertian_anc" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('pengertian_anc',$existing_data) ?></textarea>    
                            </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Etiologi Antenatal Care</strong></label>
                        <div class="col-sm-10">
                            <textarea name="etiologi_anc" class="form-control" rows="3"
                                style="overflow:hidden; resize:none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('etiologi_anc', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- Manifestasi Klinis -->
                    <p class="text-primary fw-bold mb-2">Manifestasi Klinis Antenatal Care</p>
                    <p class="text-muted mb-3"><small>Tanda kehamilan pada wanita disebabkan oleh adanya janin dan perubahan hormon sehingga memunculkan 3 diagnosis kehamilan yaitu:</small></p>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Tanda Presumtif / Dugaan</label>
                        <div class="col-sm-10">
                            <textarea name="tanda_presumtif" class="form-control" rows="3"
                                style="overflow:hidden; resize:none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                placeholder="Jelaskan tanda presumtif/dugaan kehamilan..."
                                <?= $ro ?>><?= val('tanda_presumtif', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Probable (Tanda Mungkin)</label>
                        <div class="col-sm-10">
                            <textarea name="tanda_probable" class="form-control" rows="3"
                                style="overflow:hidden; resize:none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                placeholder="Jelaskan tanda probable/tanda mungkin kehamilan..."
                                <?= $ro ?>><?= val('tanda_probable', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Tanda Pasti (Positif Sign)</label>
                        <div class="col-sm-10">
                            <textarea name="tanda_pasti" class="form-control" rows="3"
                                style="overflow:hidden; resize:none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                placeholder="Jelaskan tanda pasti/positif sign kehamilan..."
                                <?= $ro ?>><?= val('tanda_pasti', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- Perubahan Fisik -->
                    <p class="text-primary fw-bold mb-2">Perubahan Fisik Pada Masa Kehamilan</p>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Perubahan Fisik Pada Trimester I meliputi :</label>
                        <div class="col-sm-10">
                            <textarea name="perubahan_fisik_tm1" class="form-control" rows="3"
                                style="overflow:hidden; resize:none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                placeholder="Jelaskan perubahan fisik pada trimester I..."
                                <?= $ro ?>><?= val('perubahan_fisik_tm1', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Perubahan Fisik Pada Trimester II meliputi :</label>
                        <div class="col-sm-10">
                            <textarea name="perubahan_fisik_tm2" class="form-control" rows="3"
                                style="overflow:hidden; resize:none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                placeholder="Jelaskan perubahan fisik pada trimester II..."
                                <?= $ro ?>><?= val('perubahan_fisik_tm2', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Perubahan Fisik Pada Trimester III meliputi :</label>
                        <div class="col-sm-10">
                            <textarea name="perubahan_fisik_tm3" class="form-control" rows="3"
                                style="overflow:hidden; resize:none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                placeholder="Jelaskan perubahan fisik pada trimester III..."
                                <?= $ro ?>><?= val('perubahan_fisik_tm3', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- Adaptasi Fisiologis -->
                    <p class="text-primary fw-bold mb-2">Adaptasi Psikologis pada Masa Kehamilan</p>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Perubahan Psikologis Pada Trimester I
                            <br><small class="text-muted">(Periode Penyesuaian)</small>
                        </label>
                        <div class="col-sm-10">
                            <textarea name="psikologis_tm1" class="form-control" rows="3"
                                style="overflow:hidden; resize:none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                placeholder="Jelaskan perubahan psikologis pada trimester I (periode penyesuaian)..."
                                <?= $ro ?>><?= val('psikologis_tm1', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Perubahan Psikologis Pada Trimester II
                            <br><small class="text-muted">(Periode Kesehatan Yang Baik)</small>
                        </label>
                        <div class="col-sm-10">
                            <textarea name="psikologis_tm2" class="form-control" rows="3"
                                style="overflow:hidden; resize:none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                placeholder="Jelaskan perubahan psikologis pada trimester II (periode kesehatan yang baik)..."
                                <?= $ro ?>><?= val('psikologis_tm2', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Perubahan Psikologis Pada Trimester III</label>
                        <div class="col-sm-10">
                            <textarea name="psikologis_tm3" class="form-control" rows="3"
                                style="overflow:hidden; resize:none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                placeholder="Jelaskan perubahan psikologis pada trimester III..."
                                <?= $ro ?>><?= val('psikologis_tm3', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- Komplikasi -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Komplikasi Dalam Antenatal Care</strong></label>
                        <div class="col-sm-10">
                            <textarea name="komplikasi" class="form-control" rows="3"
                                style="overflow:hidden; resize:none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                placeholder="Jelaskan komplikasi dalam antenatal care..."
                                <?= $ro ?>><?= val('komplikasi', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- Penatalaksanaan Medis -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Penatalaksanaan Medis</strong></label>
                        <div class="col-sm-10">
                            <textarea name="penatalaksanaan_medis" class="form-control" rows="3"
                                style="overflow:hidden; resize:none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                placeholder="Jelaskan penatalaksanaan medis antenatal care..."
                                <?= $ro ?>><?= val('penatalaksanaan_medis', $existing_data) ?></textarea>
                        </div>
                    </div>

                </div>
            </div>

            <!-- ===================== B. KONSEP DASAR KEPERAWATAN ===================== -->
            <div class="card">
                <div class="card-body">

                    <h5 class="card-title"><strong>B. Konsep Dasar Keperawatan</strong></h5>

                    <!-- Pengkajian -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Pengkajian</strong></label>
                        <div class="col-sm-10">
                            <textarea name="pengkajian_keperawatan" class="form-control" rows="3"
                                style="overflow:hidden; resize:none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                placeholder="Jelaskan pengkajian keperawatan antenatal care..."
                                <?= $ro ?>><?= val('pengkajian_keperawatan', $existing_data) ?></textarea>
                        </div>
                    </div>
                       <div class="row mb-3">
            <label class="col-sm-2 col-form-label"><strong>Diagnosa Keperawatan</strong></label>
            <div class="col-sm-10">
                <textarea name="diagnosa_keperawatan" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('diagnosa_keperawatan',$existing_data) ?></textarea>
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
                </div>
            </div>
<style>
    /* Memastikan tabel menggunakan lebar penuh */
    #tabel-perencanaan {
        width: 100%;
        table-layout: fixed; /* Membuat kolom proporsional */
    }

    /* Membuat textarea memenuhi seluruh sel tabel dan terlihat luas */
    #tabel-perencanaan textarea {
        min-height: 80px; /* Tinggi minimum agar terlihat luas */
        width: 100%;
        resize: vertical; /* Pengguna bisa menarik manual jika perlu */
    }
</style>
            <!-- ===================== C. DAFTAR PUSTAKA ===================== -->
            <div class="card">
                <div class="card-body">

                    <h5 class="card-title"><strong>C. Daftar Pustaka</strong></h5>

                    <div id="list-pustaka"></div>

                    <?php if (!$is_readonly): ?>
                        <div class="row mb-3">
                            <div class="col-sm-12 d-flex justify-content-end">
                                <button type="button" class="btn btn-primary btn-sm" onclick="tambahPustaka()">+ Tambah Pustaka</button>
                            </div>
                        </div>
                    <?php endif; ?>

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

           <script>
    let rowPerencanaanCount = 1;
    let pustakCount = 0;
    
    // Konfigurasi dari PHP
    const isReadonly = <?= $is_readonly ? 'true' : 'false' ?>;
    const existingPerencanaan = <?= json_encode($existing_perencanaan ?? []) ?>;
    const existingDaftarPustaka = <?= json_encode($existing_daftar_pustaka ?? []) ?>;
    const existingData = <?= json_encode($existing_data ?? []) ?>;

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
                <textarea class="form-control form-control-sm" name="perencanaan[${index}][diagnosa]" rows="2" 
                    style="resize:none; overflow:hidden;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                    ${isReadonly ? 'readonly' : ''}>${data?.diagnosa ?? ''}</textarea>
            </td>
            <td>
                <textarea class="form-control form-control-sm" name="perencanaan[${index}][tujuan_kriteria]" rows="2" 
                    style="resize:none; overflow:hidden;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                    ${isReadonly ? 'readonly' : ''}>${data?.tujuan_kriteria ?? ''}</textarea>
            </td>
            <td>
                <textarea class="form-control form-control-sm" name="perencanaan[${index}][intervensi]" rows="2" 
                    style="resize:none; overflow:hidden;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                    ${isReadonly ? 'readonly' : ''}>${data?.intervensi ?? ''}</textarea>
            </td>
            ${aksiCol}
        `;

        tbody.appendChild(row);
        rowPerencanaanCount++;
    }

    function hapusRow(btn) {
        btn.closest('tr').remove();
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
                    <input type="text" class="form-control" name="daftar_pustaka[]" value="${value}" placeholder="Masukkan referensi pustaka...">
                    <button type="button" class="btn btn-danger" onclick="hapusPustaka(this)">x</button>
                </div>
            </div>`;

        container.appendChild(div);
        pustakCount++;
    }

    function hapusPustaka(btn) {
        btn.closest('.pustaka-item').remove();
        // Update penomoran ulang setelah ada yang dihapus
        document.querySelectorAll('.pustaka-item').forEach((item, i) => {
            item.querySelector('span.text-muted').textContent = (i + 1) + '.';
        });
        pustakCount = document.querySelectorAll('.pustaka-item').length;
    }

    // ---- LOAD DATA ----
    window.addEventListener('load', function() {
        // Load Perencanaan
        if (existingPerencanaan && existingPerencanaan.length > 0) {
            existingPerencanaan.forEach(row => tambahRowPerencanaan(row));
        } else if (!isReadonly) {
            tambahRowPerencanaan();
        }

        // Load Daftar Pustaka
        if (existingDaftarPustaka && existingDaftarPustaka.length > 0) {
            existingDaftarPustaka.forEach(v => tambahPustaka(v));
        } else if (!isReadonly) {
            tambahPustaka();
        }
    });
</script>

        </form>

        <?php include dirname(__DIR__, 2) . '/partials/footer_form.php'; ?>
        </div>
        </div>

    </section>
</main>