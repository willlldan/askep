<?php
$form_id       = 13;
$section_name  = 'laporan_pendahuluan';
$section_label = 'Laporan Pendahuluan';
include dirname(__DIR__, 2) . '/partials/init_section.php';

$tgl_pengkajian  = $submission['tanggal_pengkajian'] ?? '';
$rs_ruangan      = $submission['rs_ruangan']         ?? '';

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

    $daftar_pustaka = [];
    if (!empty($_POST['daftar_pustaka'])) {
        foreach ($_POST['daftar_pustaka'] as $item) {
            if (empty(trim($item))) continue;
            $daftar_pustaka[] = trim($item);
        }
    }

    $data = [
        // A. Konsep Dasar Medis
        'pengertian_persalinan'     => $_POST['pengertian_persalinan']     ?? '',
        'tanda_persalinan'          => $_POST['tanda_persalinan']          ?? '',
        'onset_persalinan'          => $_POST['onset_persalinan']          ?? '',
        // Tahapan Persalinan
        'tahapan_kala1'             => $_POST['tahapan_kala1']             ?? '',
        'tahapan_kala2'             => $_POST['tahapan_kala2']             ?? '',
        'tahapan_kala3'             => $_POST['tahapan_kala3']             ?? '',
        'tahapan_kala4'             => $_POST['tahapan_kala4']             ?? '',
        // Mekanisme Persalinan
        'mekanisme_engagement'      => $_POST['mekanisme_engagement']      ?? '',
        'mekanisme_desensus'        => $_POST['mekanisme_desensus']        ?? '',
        'mekanisme_fleksi'          => $_POST['mekanisme_fleksi']          ?? '',
        'mekanisme_rotasi_internal' => $_POST['mekanisme_rotasi_internal'] ?? '',
        'mekanisme_ekstensi'        => $_POST['mekanisme_ekstensi']        ?? '',
        'mekanisme_rotasi_eksternal'=> $_POST['mekanisme_rotasi_eksternal']?? '',
        'mekanisme_ekspulsi'        => $_POST['mekanisme_ekspulsi']        ?? '',
        // Faktor-Faktor yang Mempengaruhi
        'faktor_power'              => $_POST['faktor_power']              ?? '',
        'faktor_passage'            => $_POST['faktor_passage']            ?? '',
        'faktor_passenger'          => $_POST['faktor_passenger']          ?? '',
        'faktor_psikologi'          => $_POST['faktor_psikologi']          ?? '',
        'faktor_posisi'             => $_POST['faktor_posisi']             ?? '',
        // B. Konsep Keperawatan - Pengkajian
        'pengkajian_kala1'          => $_POST['pengkajian_kala1']          ?? '',
        'pengkajian_kala2'          => $_POST['pengkajian_kala2']          ?? '',
        'pengkajian_kala3'          => $_POST['pengkajian_kala3']          ?? '',
        'pengkajian_kala4'          => $_POST['pengkajian_kala4']          ?? '',
        // Diagnosa Keperawatan
        'diagnosa_kala1'            => $_POST['diagnosa_kala1']            ?? '',
        'diagnosa_kala2'            => $_POST['diagnosa_kala2']            ?? '',
        'diagnosa_kala3'            => $_POST['diagnosa_kala3']            ?? '',
        'diagnosa_kala4'            => $_POST['diagnosa_kala4']            ?? '',
        // C. Daftar Pustaka
        'daftar_pustaka'            => $daftar_pustaka,
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

    <?php include "maternitas/pengkajian_inranatal_care/tab.php"; ?>

    <section class="section dashboard">

        <?php include "partials/notifikasi.php"; ?>
        <?php include "partials/status_section.php"; ?>

        <form class="needs-validation" novalidate action="" method="POST">

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

                    <!-- Pengertian -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Pengertian Persalinan</strong></label>
                        <div class="col-sm-9">
                            <textarea name="pengertian_persalinan" class="form-control" rows="3"
                                style="overflow:hidden; resize:none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                placeholder="Jelaskan pengertian persalinan..."
                                <?= $ro ?>><?= val('pengertian_persalinan', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- Tanda-Tanda Persalinan -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Tanda – Tanda Persalinan</strong></label>
                        <div class="col-sm-9">
                            <textarea name="tanda_persalinan" class="form-control" rows="3"
                                style="overflow:hidden; resize:none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                placeholder="Jelaskan tanda-tanda persalinan..."
                                <?= $ro ?>><?= val('tanda_persalinan', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- Onset Persalinan -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Onset Persalinan</strong></label>
                        <div class="col-sm-9">
                            <textarea name="onset_persalinan" class="form-control" rows="3"
                                style="overflow:hidden; resize:none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                placeholder="Jelaskan onset persalinan..."
                                <?= $ro ?>><?= val('onset_persalinan', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- Tahapan Persalinan -->
                    <p class="text-primary fw-bold mb-2">Tahapan Persalinan</p>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Kala I</label>
                        <div class="col-sm-9">
                            <textarea name="tahapan_kala1" class="form-control" rows="3"
                                style="overflow:hidden; resize:none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                placeholder="Jelaskan tahapan persalinan Kala I..."
                                <?= $ro ?>><?= val('tahapan_kala1', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Kala II</label>
                        <div class="col-sm-9">
                            <textarea name="tahapan_kala2" class="form-control" rows="3"
                                style="overflow:hidden; resize:none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                placeholder="Jelaskan tahapan persalinan Kala II..."
                                <?= $ro ?>><?= val('tahapan_kala2', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Kala III</label>
                        <div class="col-sm-9">
                            <textarea name="tahapan_kala3" class="form-control" rows="3"
                                style="overflow:hidden; resize:none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                placeholder="Jelaskan tahapan persalinan Kala III..."
                                <?= $ro ?>><?= val('tahapan_kala3', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Kala IV</label>
                        <div class="col-sm-9">
                            <textarea name="tahapan_kala4" class="form-control" rows="3"
                                style="overflow:hidden; resize:none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                placeholder="Jelaskan tahapan persalinan Kala IV..."
                                <?= $ro ?>><?= val('tahapan_kala4', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- Mekanisme Persalinan -->
                    <p class="text-primary fw-bold mb-2">Mekanisme Persalinan</p>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Engagement</label>
                        <div class="col-sm-9">
                            <textarea name="mekanisme_engagement" class="form-control" rows="3"
                                style="overflow:hidden; resize:none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                placeholder="Jelaskan mekanisme engagement..."
                                <?= $ro ?>><?= val('mekanisme_engagement', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Desensus</label>
                        <div class="col-sm-9">
                            <textarea name="mekanisme_desensus" class="form-control" rows="3"
                                style="overflow:hidden; resize:none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                placeholder="Jelaskan mekanisme desensus..."
                                <?= $ro ?>><?= val('mekanisme_desensus', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Fleksi</label>
                        <div class="col-sm-9">
                            <textarea name="mekanisme_fleksi" class="form-control" rows="3"
                                style="overflow:hidden; resize:none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                placeholder="Jelaskan mekanisme fleksi..."
                                <?= $ro ?>><?= val('mekanisme_fleksi', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Rotasi Internal</label>
                        <div class="col-sm-9">
                            <textarea name="mekanisme_rotasi_internal" class="form-control" rows="3"
                                style="overflow:hidden; resize:none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                placeholder="Jelaskan mekanisme rotasi internal..."
                                <?= $ro ?>><?= val('mekanisme_rotasi_internal', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Ekstensi</label>
                        <div class="col-sm-9">
                            <textarea name="mekanisme_ekstensi" class="form-control" rows="3"
                                style="overflow:hidden; resize:none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                placeholder="Jelaskan mekanisme ekstensi..."
                                <?= $ro ?>><?= val('mekanisme_ekstensi', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Rotasi Eksternal</label>
                        <div class="col-sm-9">
                            <textarea name="mekanisme_rotasi_eksternal" class="form-control" rows="3"
                                style="overflow:hidden; resize:none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                placeholder="Jelaskan mekanisme rotasi eksternal..."
                                <?= $ro ?>><?= val('mekanisme_rotasi_eksternal', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Ekspulsi</label>
                        <div class="col-sm-9">
                            <textarea name="mekanisme_ekspulsi" class="form-control" rows="3"
                                style="overflow:hidden; resize:none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                placeholder="Jelaskan mekanisme ekspulsi..."
                                <?= $ro ?>><?= val('mekanisme_ekspulsi', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- Faktor-Faktor yang Mempengaruhi -->
                    <p class="text-primary fw-bold mb-2">Faktor – Faktor Yang Mempengaruhi Persalinan</p>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Power (Kekuatan)</label>
                        <div class="col-sm-9">
                            <textarea name="faktor_power" class="form-control" rows="3"
                                style="overflow:hidden; resize:none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                placeholder="Jelaskan faktor power/kekuatan dalam persalinan..."
                                <?= $ro ?>><?= val('faktor_power', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Faktor <em>Passage</em></label>
                        <div class="col-sm-9">
                            <textarea name="faktor_passage" class="form-control" rows="3"
                                style="overflow:hidden; resize:none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                placeholder="Jelaskan faktor passage dalam persalinan..."
                                <?= $ro ?>><?= val('faktor_passage', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Faktor <em>Passenger</em></label>
                        <div class="col-sm-9">
                            <textarea name="faktor_passenger" class="form-control" rows="3"
                                style="overflow:hidden; resize:none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                placeholder="Jelaskan faktor passenger dalam persalinan..."
                                <?= $ro ?>><?= val('faktor_passenger', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><em>Psycology</em> (Psikologi Ibu)</label>
                        <div class="col-sm-9">
                            <textarea name="faktor_psikologi" class="form-control" rows="3"
                                style="overflow:hidden; resize:none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                placeholder="Jelaskan faktor psikologi ibu dalam persalinan..."
                                <?= $ro ?>><?= val('faktor_psikologi', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Posisi Ibu Bersalin</label>
                        <div class="col-sm-9">
                            <textarea name="faktor_posisi" class="form-control" rows="3"
                                style="overflow:hidden; resize:none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                placeholder="Jelaskan posisi ibu bersalin..."
                                <?= $ro ?>><?= val('faktor_posisi', $existing_data) ?></textarea>
                        </div>
                    </div>

                </div>
            </div>

            <!-- ===================== B. KONSEP KEPERAWATAN ===================== -->
            <div class="card">
                <div class="card-body">

                    <h5 class="card-title"><strong>B. Konsep Keperawatan</strong></h5>

                    <!-- Pengkajian -->
                    <p class="text-primary fw-bold mb-2">Pengkajian</p>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Pengkajian Kala I</label>
                        <div class="col-sm-9">
                            <textarea name="pengkajian_kala1" class="form-control" rows="3"
                                style="overflow:hidden; resize:none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                placeholder="Jelaskan pengkajian keperawatan Kala I..."
                                <?= $ro ?>><?= val('pengkajian_kala1', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Pengkajian Kala II</label>
                        <div class="col-sm-9">
                            <textarea name="pengkajian_kala2" class="form-control" rows="3"
                                style="overflow:hidden; resize:none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                placeholder="Jelaskan pengkajian keperawatan Kala II..."
                                <?= $ro ?>><?= val('pengkajian_kala2', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Pengkajian Kala III</label>
                        <div class="col-sm-9">
                            <textarea name="pengkajian_kala3" class="form-control" rows="3"
                                style="overflow:hidden; resize:none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                placeholder="Jelaskan pengkajian keperawatan Kala III..."
                                <?= $ro ?>><?= val('pengkajian_kala3', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Pengkajian Kala IV</label>
                        <div class="col-sm-9">
                            <textarea name="pengkajian_kala4" class="form-control" rows="3"
                                style="overflow:hidden; resize:none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                placeholder="Jelaskan pengkajian keperawatan Kala IV..."
                                <?= $ro ?>><?= val('pengkajian_kala4', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- Diagnosa Keperawatan -->
                    <p class="text-primary fw-bold mb-2">Diagnosa Keperawatan</p>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Kala I</label>
                        <div class="col-sm-9">
                            <textarea name="diagnosa_kala1" class="form-control" rows="3"
                                style="overflow:hidden; resize:none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                placeholder="Tuliskan diagnosa keperawatan Kala I..."
                                <?= $ro ?>><?= val('diagnosa_kala1', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Kala II</label>
                        <div class="col-sm-9">
                            <textarea name="diagnosa_kala2" class="form-control" rows="3"
                                style="overflow:hidden; resize:none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                placeholder="Tuliskan diagnosa keperawatan Kala II..."
                                <?= $ro ?>><?= val('diagnosa_kala2', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Kala III</label>
                        <div class="col-sm-9">
                            <textarea name="diagnosa_kala3" class="form-control" rows="3"
                                style="overflow:hidden; resize:none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                placeholder="Tuliskan diagnosa keperawatan Kala III..."
                                <?= $ro ?>><?= val('diagnosa_kala3', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Kala IV</label>
                        <div class="col-sm-9">
                            <textarea name="diagnosa_kala4" class="form-control" rows="3"
                                style="overflow:hidden; resize:none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                placeholder="Tuliskan diagnosa keperawatan Kala IV..."
                                <?= $ro ?>><?= val('diagnosa_kala4', $existing_data) ?></textarea>
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