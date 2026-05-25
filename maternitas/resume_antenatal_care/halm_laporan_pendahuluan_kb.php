<?php
$form_id       = 3;
$section_name  = 'laporan_pendahuluan_kb';
$section_label = 'Laporan Pendahuluan KB';
include dirname(__DIR__, 2) . '/partials/init_section.php';

$existing_daftar_pustaka = $existing_data['daftar_pustaka'] ?? [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $level === 'Mahasiswa') {
    if (isLocked($submission)) {
        redirectWithMessage($_SERVER['REQUEST_URI'], 'error', 'Data tidak dapat diubah karena sedang dalam proses review.');
    }

    $daftar_pustaka = [];
    if (!empty($_POST['daftar_pustaka'])) {
        foreach ($_POST['daftar_pustaka'] as $item) {
            if (empty(trim($item))) continue;
            $daftar_pustaka[] = trim($item);
        }
    }

    $data = [
        'definisi_kb'                              => $_POST['definisi_kb']                              ?? '',
        'ruang_lingkup_program_kb'                 => $_POST['ruang_lingkup_program_kb']                 ?? '',
        'manfaat_kb_kesehatan'                     => $_POST['manfaat_kb_kesehatan']                     ?? '',
        'akseptor_kb'                              => $_POST['akseptor_kb']                              ?? '',
        'definisi_kontrasepsi'                     => $_POST['definisi_kontrasepsi']                     ?? '',
        'faktor_pemilihan_metode_kontrasepsi'      => $_POST['faktor_pemilihan_metode_kontrasepsi']      ?? '',
        'akseptor_kb_sasaran_pemakaian'            => $_POST['akseptor_kb_sasaran_pemakaian']            ?? '',
        'syarat_kontrasepsi'                       => $_POST['syarat_kontrasepsi']                       ?? '',
        'metode_kontrasepsi_jangka_pendek_panjang' => $_POST['metode_kontrasepsi_jangka_pendek_panjang'] ?? '',
        'jenis_kontrasepsi'                        => $_POST['jenis_kontrasepsi']                        ?? '',
        'daftar_pustaka'                           => $daftar_pustaka,
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

    <?php include "tab.php"; ?>

    <section class="section dashboard">

        <?php include dirname(__DIR__, 2) . '/partials/notifikasi.php'; ?>
        <?php include dirname(__DIR__, 2) . '/partials/status_section.php'; ?>

        <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

            <div class="card">
                <div class="card-body">

                    <h5 class="card-title"><strong>A. Konsep Dasar Medis</strong></h5>

                    <p class="text-primary fw-bold mb-2">Keluarga Berencana (KB)</p>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Definisi Keluarga Berencana (KB)</strong></label>
                        <div class="col-sm-9">
                            <textarea name="definisi_kb" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" placeholder="Jelaskan definisi keluarga berencana..." <?= $ro ?>><?= val('definisi_kb', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Ruang Lingkup Program KB</strong></label>
                        <div class="col-sm-9">
                            <textarea name="ruang_lingkup_program_kb" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" placeholder="Jelaskan ruang lingkup program KB..." <?= $ro ?>><?= val('ruang_lingkup_program_kb', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Manfaat Usaha KB Dipandang Dari Segi Kesehatan</strong></label>
                        <div class="col-sm-9">
                            <textarea name="manfaat_kb_kesehatan" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" placeholder="Jelaskan manfaat KB dari segi kesehatan..." <?= $ro ?>><?= val('manfaat_kb_kesehatan', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Akseptor Keluarga Berencana</strong></label>
                        <div class="col-sm-9">
                            <textarea name="akseptor_kb" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" placeholder="Jelaskan akseptor keluarga berencana..." <?= $ro ?>><?= val('akseptor_kb', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <p class="text-primary fw-bold mb-2">Kontrasepsi</p>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Definisi Kontrasepsi</strong></label>
                        <div class="col-sm-9">
                            <textarea name="definisi_kontrasepsi" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" placeholder="Jelaskan definisi kontrasepsi..." <?= $ro ?>><?= val('definisi_kontrasepsi', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Faktor-faktor Pemilihan Metode Kontrasepsi</strong></label>
                        <div class="col-sm-9">
                            <textarea name="faktor_pemilihan_metode_kontrasepsi" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" placeholder="Jelaskan faktor pemilihan metode kontrasepsi..." <?= $ro ?>><?= val('faktor_pemilihan_metode_kontrasepsi', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Akseptor KB Menurut Sasaran Pemakaian Kontrasepsi</strong></label>
                        <div class="col-sm-9">
                            <textarea name="akseptor_kb_sasaran_pemakaian" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" placeholder="Jelaskan akseptor KB menurut sasaran pemakaian kontrasepsi..." <?= $ro ?>><?= val('akseptor_kb_sasaran_pemakaian', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Syarat-syarat Kontrasepsi</strong></label>
                        <div class="col-sm-9">
                            <textarea name="syarat_kontrasepsi" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" placeholder="Jelaskan syarat-syarat kontrasepsi..." <?= $ro ?>><?= val('syarat_kontrasepsi', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Metode Kontrasepsi Jangka Pendek dan Jangka Panjang</strong></label>
                        <div class="col-sm-9">
                            <textarea name="metode_kontrasepsi_jangka_pendek_panjang" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" placeholder="Jelaskan metode kontrasepsi jangka pendek dan jangka panjang..." <?= $ro ?>><?= val('metode_kontrasepsi_jangka_pendek_panjang', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Jenis Kontrasepsi</strong><br><small class="text-muted">(definisi, kelebihan, dan kekurangan tiap jenis)</small></label>
                        <div class="col-sm-9">
                            <textarea name="jenis_kontrasepsi" class="form-control" rows="4" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" placeholder="Jelaskan jenis kontrasepsi beserta definisi, kelebihan, dan kekurangan..." <?= $ro ?>><?= val('jenis_kontrasepsi', $existing_data) ?></textarea>
                        </div>
                    </div>

                </div>
            </div>

            <div class="card">
                <div class="card-body">

                    <h5 class="card-title"><strong>B. Daftar Pustaka</strong></h5>

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
                        ? `<div class="col-sm-11 d-flex align-items-center gap-2"><span class="text-muted fw-bold" style="min-width:24px;">${index + 1}.</span><input type="text" class="form-control" value="${value}" readonly></div>`
                        : `<div class="col-sm-11 d-flex align-items-center gap-2"><span class="text-muted fw-bold" style="min-width:24px;">${index + 1}.</span><div class="input-group"><input type="text" class="form-control" name="daftar_pustaka[]" value="${value}" placeholder="Masukkan referensi pustaka..."><button type="button" class="btn btn-danger" onclick="hapusPustaka(this)">x</button></div></div>`;

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
