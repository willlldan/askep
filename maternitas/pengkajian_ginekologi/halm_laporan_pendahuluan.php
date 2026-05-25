<?php
$form_id       = 12;
$section_name  = 'laporan_pendahuluan';
$section_label = 'Laporan Pendahuluan';
include dirname(__DIR__, 2) . '/partials/init_section.php';

$tgl_pengkajian = $submission['tanggal_pengkajian'] ?? '';
$rs_ruangan     = $submission['rs_ruangan'] ?? '';

$existing_daftar_pustaka = $existing_data['daftar_pustaka'] ?? [];

// =============================================
// HANDLE POST - MAHASISWA SIMPAN DATA
// =============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $level === 'Mahasiswa') {

    if (isLocked($submission)) {
        redirectWithMessage($_SERVER['REQUEST_URI'], 'error', 'Data tidak dapat diubah karena sedang dalam proses review.');
    }

    $tgl_pengkajian = $_POST['tgl_pengkajian'] ?? '';
    $rs_ruangan     = $_POST['rs_ruangan'] ?? '';

    $daftar_pustaka = [];
    if (!empty($_POST['daftar_pustaka'])) {
        foreach ($_POST['daftar_pustaka'] as $item) {
            if (empty(trim($item))) continue;
            $daftar_pustaka[] = trim($item);
        }
    }

    $data = [
        'definisi_nifas'                           => $_POST['definisi_nifas']                           ?? '',
        'anatomi_sistem_reproduksi_wanita'         => $_POST['anatomi_sistem_reproduksi_wanita']         ?? '',
        'tahapan_masa_nifas'                       => $_POST['tahapan_masa_nifas']                       ?? '',
        'perubahan_fisiologis_organ_pada_masa_nifas' => $_POST['perubahan_fisiologis_organ_pada_masa_nifas'] ?? '',
        'adaptasi_psikologis_masa_nifas'           => $_POST['adaptasi_psikologis_masa_nifas']           ?? '',
        'komplikasi_masa_nifas'                    => $_POST['komplikasi_masa_nifas']                    ?? '',
        'pengkajian_keperawatan'                   => $_POST['pengkajian_keperawatan']                   ?? '',
        'daftar_pustaka'                           => $daftar_pustaka,
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

    <?php include "maternitas/pengkajian_ginekologi/tab.php"; ?>

    <section class="section dashboard">

        <?php include dirname(__DIR__, 2) . '/partials/notifikasi.php'; ?>
        <?php include dirname(__DIR__, 2) . '/partials/status_section.php'; ?>

        <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

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

                    <h5 class="card-title"><strong>A. Konsep Dasar Medis</strong></h5>

                    <p class="text-primary fw-bold mb-2">Konsep Dasar Nifas</p>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Definisi Nifas</strong></label>
                        <div class="col-sm-9">
                            <textarea name="definisi_nifas" class="form-control" rows="3"
                                style="overflow:hidden; resize:none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                placeholder="Jelaskan definisi nifas..."
                                <?= $ro ?>><?= val('definisi_nifas', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Anatomi Sistem Reproduksi Wanita</strong></label>
                        <div class="col-sm-9">
                            <textarea name="anatomi_sistem_reproduksi_wanita" class="form-control" rows="3"
                                style="overflow:hidden; resize:none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                placeholder="Jelaskan anatomi sistem reproduksi wanita..."
                                <?= $ro ?>><?= val('anatomi_sistem_reproduksi_wanita', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Tahapan Masa Nifas</strong></label>
                        <div class="col-sm-9">
                            <textarea name="tahapan_masa_nifas" class="form-control" rows="3"
                                style="overflow:hidden; resize:none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                placeholder="Jelaskan tahapan masa nifas..."
                                <?= $ro ?>><?= val('tahapan_masa_nifas', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Perubahan Fisiologis Organ pada Masa Nifas</strong></label>
                        <div class="col-sm-9">
                            <textarea name="perubahan_fisiologis_organ_pada_masa_nifas" class="form-control" rows="3"
                                style="overflow:hidden; resize:none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                placeholder="Jelaskan perubahan fisiologis organ pada masa nifas..."
                                <?= $ro ?>><?= val('perubahan_fisiologis_organ_pada_masa_nifas', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Adaptasi Psikologis Masa Nifas</strong></label>
                        <div class="col-sm-9">
                            <textarea name="adaptasi_psikologis_masa_nifas" class="form-control" rows="3"
                                style="overflow:hidden; resize:none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                placeholder="Jelaskan adaptasi psikologis masa nifas..."
                                <?= $ro ?>><?= val('adaptasi_psikologis_masa_nifas', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Komplikasi Masa Nifas</strong></label>
                        <div class="col-sm-9">
                            <textarea name="komplikasi_masa_nifas" class="form-control" rows="3"
                                style="overflow:hidden; resize:none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                placeholder="Jelaskan komplikasi masa nifas..."
                                <?= $ro ?>><?= val('komplikasi_masa_nifas', $existing_data) ?></textarea>
                        </div>
                    </div>

                </div>
            </div>

            <div class="card">
                <div class="card-body">

                    <h5 class="card-title"><strong>B. Konsep Dasar Keperawatan</strong></h5>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Pengkajian</strong></label>
                        <div class="col-sm-9">
                            <textarea name="pengkajian_keperawatan" class="form-control" rows="3"
                                style="overflow:hidden; resize:none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                placeholder="Jelaskan pengkajian keperawatan pada masa nifas..."
                                <?= $ro ?>><?= val('pengkajian_keperawatan', $existing_data) ?></textarea>
                        </div>
                    </div>

                </div>
            </div>

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
                    div.innerHTML = isReadonly
                        ? `<div class="col-sm-11 d-flex align-items-center gap-2">
                            <span class="text-muted fw-bold" style="min-width:24px;">${index + 1}.</span>
                            <input type="text" class="form-control" value="${value}" readonly>
                        </div>`
                        : `<div class="col-sm-11 d-flex align-items-center gap-2">
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

        </div>
        </div>

        <?php include dirname(__DIR__, 2) . '/partials/footer_form.php'; ?>

    </section>
</main>
