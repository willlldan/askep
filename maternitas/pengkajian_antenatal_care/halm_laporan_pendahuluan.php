<?php
$form_id       = 1;
$section_name  = 'laporan_pendahuluan';
$section_label = 'Laporan Pendahuluan';
include dirname(__DIR__, 2) . '/partials/init_section.php';

$tgl_pengkajian  = $submission['tanggal_pengkajian'] ?? '';
$rs_ruangan      = $submission['rs_ruangan']         ?? '';

// Load existing dynamic rows
$existing_daftar_pustaka = $existing_data['daftar_pustaka'] ?? [];

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

    $data = [
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
        // Diagnosa Keperawatan (a-k)
        'diagnosa_a'             => $_POST['diagnosa_a']             ?? '',
        'diagnosa_b'             => $_POST['diagnosa_b']             ?? '',
        'diagnosa_c'             => $_POST['diagnosa_c']             ?? '',
        'diagnosa_d'             => $_POST['diagnosa_d']             ?? '',
        'diagnosa_e'             => $_POST['diagnosa_e']             ?? '',
        'diagnosa_f'             => $_POST['diagnosa_f']             ?? '',
        'diagnosa_g'             => $_POST['diagnosa_g']             ?? '',
        'diagnosa_h'             => $_POST['diagnosa_h']             ?? '',
        'diagnosa_i'             => $_POST['diagnosa_i']             ?? '',
        'diagnosa_j'             => $_POST['diagnosa_j']             ?? '',
        'diagnosa_k'             => $_POST['diagnosa_k']             ?? '',
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

    <?php include "maternitas/pengkajian_antenatal_care/tab.php"; ?>

    <section class="section dashboard">

        <?php include "partials/notifikasi.php"; ?>
        <?php include "partials/status_section.php"; ?>

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

            <!-- ===================== A. KONSEP DASAR MEDIS ===================== -->
            <div class="card">
                <div class="card-body">

                    <h5 class="card-title"><strong>A. Konsep Dasar Medis</strong></h5>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Pengertian Antenatal Care</strong></label>
                        <div class="col-sm-9">
                            <textarea name="pengertian_anc" class="form-control" rows="3"
                                style="overflow:hidden; resize:none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('pengertian_anc', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Etiologi Antenatal Care</strong></label>
                        <div class="col-sm-9">
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
                        <div class="col-sm-9">
                            <textarea name="tanda_presumtif" class="form-control" rows="3"
                                style="overflow:hidden; resize:none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                placeholder="Jelaskan tanda presumtif/dugaan kehamilan..."
                                <?= $ro ?>><?= val('tanda_presumtif', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Probable (Tanda Mungkin)</label>
                        <div class="col-sm-9">
                            <textarea name="tanda_probable" class="form-control" rows="3"
                                style="overflow:hidden; resize:none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                placeholder="Jelaskan tanda probable/tanda mungkin kehamilan..."
                                <?= $ro ?>><?= val('tanda_probable', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Tanda Pasti (Positif Sign)</label>
                        <div class="col-sm-9">
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
                        <div class="col-sm-9">
                            <textarea name="perubahan_fisik_tm1" class="form-control" rows="3"
                                style="overflow:hidden; resize:none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                placeholder="Jelaskan perubahan fisik pada trimester I..."
                                <?= $ro ?>><?= val('perubahan_fisik_tm1', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Perubahan Fisik Pada Trimester II meliputi :</label>
                        <div class="col-sm-9">
                            <textarea name="perubahan_fisik_tm2" class="form-control" rows="3"
                                style="overflow:hidden; resize:none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                placeholder="Jelaskan perubahan fisik pada trimester II..."
                                <?= $ro ?>><?= val('perubahan_fisik_tm2', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Perubahan Fisik Pada Trimester III meliputi :</label>
                        <div class="col-sm-9">
                            <textarea name="perubahan_fisik_tm3" class="form-control" rows="3"
                                style="overflow:hidden; resize:none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                placeholder="Jelaskan perubahan fisik pada trimester III..."
                                <?= $ro ?>><?= val('perubahan_fisik_tm3', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- Adaptasi Fisiologis -->
                    <p class="text-primary fw-bold mb-2">Adaptasi Fisiologis pada Masa Kehamilan</p>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Perubahan Psikologis Pada Trimester I
                            <br><small class="text-muted">(Periode Penyesuaian)</small>
                        </label>
                        <div class="col-sm-9">
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
                        <div class="col-sm-9">
                            <textarea name="psikologis_tm2" class="form-control" rows="3"
                                style="overflow:hidden; resize:none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                placeholder="Jelaskan perubahan psikologis pada trimester II (periode kesehatan yang baik)..."
                                <?= $ro ?>><?= val('psikologis_tm2', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Perubahan Psikologis Pada Trimester III</label>
                        <div class="col-sm-9">
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
                        <div class="col-sm-9">
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
                        <div class="col-sm-9">
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
                        <div class="col-sm-9">
                            <textarea name="pengkajian_keperawatan" class="form-control" rows="3"
                                style="overflow:hidden; resize:none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                placeholder="Jelaskan pengkajian keperawatan antenatal care..."
                                <?= $ro ?>><?= val('pengkajian_keperawatan', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- Diagnosa Keperawatan -->
                    <p class="text-primary fw-bold mb-1">Diagnosa Keperawatan</p>
                    <p class="text-muted mb-3"><small>Diagnosa keperawatan yang mungkin muncul trimester I &ndash; III</small></p>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">a. Gangguan Rasa Nyaman</label>
                        <div class="col-sm-9">
                            <textarea name="diagnosa_a" class="form-control" rows="2"
                                style="overflow:hidden; resize:none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                placeholder="Trimester I, II, dan III"
                                <?= $ro ?>><?= val('diagnosa_a', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">b. Ansietas Berhubungan Dengan Krisis Situasional</label>
                        <div class="col-sm-9">
                            <textarea name="diagnosa_b" class="form-control" rows="2"
                                style="overflow:hidden; resize:none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                placeholder="Trimester I"
                                <?= $ro ?>><?= val('diagnosa_b', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">c. Defisit Nutrisi Berhubungan Dengan Peningkatan Kebutuhan Metabolisme</label>
                        <div class="col-sm-9">
                            <textarea name="diagnosa_c" class="form-control" rows="2"
                                style="overflow:hidden; resize:none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                placeholder="Trimester I"
                                <?= $ro ?>><?= val('diagnosa_c', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">d. Defisit Pengetahuan Berhubungan Dengan Kurang Terpapar Informasi</label>
                        <div class="col-sm-9">
                            <textarea name="diagnosa_d" class="form-control" rows="2"
                                style="overflow:hidden; resize:none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                placeholder="Trimester I"
                                <?= $ro ?>><?= val('diagnosa_d', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">e. Perfusi Perifer Tidak Efektif Berhubungan Dengan Penurunan Konsentrasi Hemoglobin</label>
                        <div class="col-sm-9">
                            <textarea name="diagnosa_e" class="form-control" rows="2"
                                style="overflow:hidden; resize:none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                placeholder="Trimester I"
                                <?= $ro ?>><?= val('diagnosa_e', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">f. Nyeri Akut Berhubungan Dengan Agen Pencedera Fisiologis</label>
                        <div class="col-sm-9">
                            <textarea name="diagnosa_f" class="form-control" rows="2"
                                style="overflow:hidden; resize:none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                placeholder="Trimester I dan III"
                                <?= $ro ?>><?= val('diagnosa_f', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">g. Inkontinensia Urine Stres Berhubungan Dengan Peningkatan Tekanan Intra Abdomen</label>
                        <div class="col-sm-9">
                            <textarea name="diagnosa_g" class="form-control" rows="2"
                                style="overflow:hidden; resize:none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                placeholder="Trimester I dan III"
                                <?= $ro ?>><?= val('diagnosa_g', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">h. Gangguan Pertukaran Gas Berhubungan Dengan Perubahan Membran Alveolus-Kapiler</label>
                        <div class="col-sm-9">
                            <textarea name="diagnosa_h" class="form-control" rows="2"
                                style="overflow:hidden; resize:none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                placeholder="Trimester III"
                                <?= $ro ?>><?= val('diagnosa_h', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">i. Kesiapan Peningkatan Menjadi Orang Tua</label>
                        <div class="col-sm-9">
                            <textarea name="diagnosa_i" class="form-control" rows="2"
                                style="overflow:hidden; resize:none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                placeholder="Trimester I dan III"
                                <?= $ro ?>><?= val('diagnosa_i', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">j. Risiko Cedera Pada Ibu</label>
                        <div class="col-sm-9">
                            <textarea name="diagnosa_j" class="form-control" rows="2"
                                style="overflow:hidden; resize:none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                placeholder="Trimester I, II, dan III"
                                <?= $ro ?>><?= val('diagnosa_j', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">k. Risiko Cedera Pada Janin</label>
                        <div class="col-sm-9">
                            <textarea name="diagnosa_k" class="form-control" rows="2"
                                style="overflow:hidden; resize:none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                placeholder="Trimester I, II, dan III"
                                <?= $ro ?>><?= val('diagnosa_k', $existing_data) ?></textarea>
                        </div>
                    </div>

                </div>
            </div>

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
                let pustakCount = 0;
                const isReadonly = <?= $is_readonly ? 'true' : 'false' ?>;
                const existingDaftarPustaka = <?= json_encode($existing_daftar_pustaka) ?>;

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

                function hapusPustaka(btn) {
                    btn.closest('.pustaka-item').remove();
                    document.querySelectorAll('.pustaka-item').forEach((item, i) => {
                        item.querySelector('span.text-muted').textContent = (i + 1) + '.';
                    });
                }

                window.addEventListener('load', function () {
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