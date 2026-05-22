<?php
$form_id       = 1;
$section_name  = 'analisa_data';
$section_label = 'Analisa Data';
include dirname(__DIR__, 2) . '/partials/init_section.php';

// Load existing dynamic rows
$existing_klasifikasi = $existing_data['klasifikasi'] ?? [];
$existing_analisa     = $existing_data['analisa']     ?? [];

// =============================================
// HANDLE POST - MAHASISWA SIMPAN DATA
// =============================================

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $level === 'Mahasiswa') {
    if (isLocked($submission)) {
        redirectWithMessage($_SERVER['REQUEST_URI'], 'error', 'Data tidak dapat diubah karena sedang dalam proses review.');
    }

    $klasifikasi = parse_dynamic_rows($_POST['klasifikasi'] ?? [], ['ds', 'do']);
    $analisa     = parse_dynamic_rows($_POST['analisa'] ?? [], ['ds_do', 'etiologi', 'masalah']);

    $data = [
        'klasifikasi' => $klasifikasi,
        'analisa'     => $analisa,
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

    <?php include "maternitas/pengkajian_antenatal_care/tab.php"; ?>

    <section class="section dashboard">

        <?php include "partials/notifikasi.php"; ?>
        <?php include "partials/status_section.php"; ?>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><strong>ANALISA DATA</strong></h5>
                <form class="needs-validation" novalidate action="" method="POST">
                    <!-- ===================== TABEL KLASIFIKASI DATA ===================== -->
                    <p class="text-primary fw-bold mb-2">Klasifikasi Data</p>
                    <table class="table table-bordered" id="tabel-klasifikasi">
                        <thead>
                            <tr>
                                <th class="text-center" style="width:40px">No</th>
                                <th class="text-center">Data Subjektif (DS)</th>
                                <th class="text-center">Data Objektif (DO)</th>
                                <th class="text-center" style="width:60px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="tbody-klasifikasi">
                            <!-- Dynamic rows masuk sini -->
                        </tbody>
                    </table>
                    <div class="row mb-4">
                        <div class="col-sm-12 d-flex justify-content-end">
                            <button type="button" class="btn btn-primary btn-sm" id="btn-tambah-klasifikasi" onclick="tambahRowKlasifikasi({tbodyId: 'tbody-klasifikasi', rowCountVar: 'rowKlasifikasiCount', isReadonly: <?= json_encode($is_readonly) ?>})">+ Tambah Baris</button>
                        </div>
                    </div>
                    <!-- ===================== TABEL ANALISA DATA ===================== -->
                    <p class="text-primary fw-bold mb-2">Analisa Data</p>
                    <table class="table table-bordered" id="tabel-analisa">
                        <thead>
                            <tr>
                                <th class="text-center" style="width:40px">No</th>
                                <th class="text-center">DS/DO</th>
                                <th class="text-center">Etiologi</th>
                                <th class="text-center">Masalah</th>
                                <th class="text-center" style="width:60px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="tbody-analisa">
                            <!-- Dynamic rows masuk sini -->
                        </tbody>
                    </table>
                    <div class="row mb-4">
                        <div class="col-sm-12 d-flex justify-content-end">
                            <button type="button" class="btn btn-primary btn-sm" id="btn-tambah-analisa" onclick="tambahRowAnalisa({tbodyId: 'tbody-analisa', rowCountVar: 'rowAnalisaCount', isReadonly: <?= json_encode($is_readonly) ?>})">+ Tambah Baris</button>
                        </div>
                    </div>
                    <!-- TOMBOL SIMPAN -->
                    <?php if (!$is_dosen): ?>
                        <div class="row mb-3">
                            <div class="col-sm-12 d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary" <?= $ro ?>>Simpan Data</button>
                            </div>
                        </div>
                    <?php endif; ?>
                    <script>
                        let rowKlasifikasiCount = 1;
                        let rowAnalisaCount = 1;
                        const existingKlasifikasi = <?= json_encode($existing_klasifikasi) ?>;
                        const existingAnalisa = <?= json_encode($existing_analisa) ?>;
                        const isReadonly = <?= json_encode($is_readonly) ?>;
                        // Import helper
                        const script = document.createElement('script');
                        script.src = '/assets/js/form_row_helpers.js';
                        document.head.appendChild(script);

                        // hapusRow sudah otomatis dari helper
                        // Load existing rows on page load
                        window.addEventListener('load', function() {
                            if (existingKlasifikasi && existingKlasifikasi.length > 0) {
                                existingKlasifikasi.forEach(row => tambahRowKlasifikasi({
                                    tbodyId: 'tbody-klasifikasi',
                                    rowCountVar: 'rowKlasifikasiCount',
                                    isReadonly,
                                    data: row
                                }));
                            } else {
                                tambahRowKlasifikasi({
                                    tbodyId: 'tbody-klasifikasi',
                                    rowCountVar: 'rowKlasifikasiCount',
                                    isReadonly
                                });
                            }
                            if (existingAnalisa && existingAnalisa.length > 0) {
                                existingAnalisa.forEach(row => tambahRowAnalisa({
                                    tbodyId: 'tbody-analisa',
                                    rowCountVar: 'rowAnalisaCount',
                                    isReadonly,
                                    data: row
                                }));
                            } else {
                                tambahRowAnalisa({
                                    tbodyId: 'tbody-analisa',
                                    rowCountVar: 'rowAnalisaCount',
                                    isReadonly
                                });
                            }
                            // Disable add buttons if readonly
                            if (isReadonly) {
                                document.getElementById('btn-tambah-klasifikasi').setAttribute('disabled', 'disabled');
                                document.getElementById('btn-tambah-analisa').setAttribute('disabled', 'disabled');
                            }
                        });
                    </script>
                </form>

            </div>
        </div>

        <?php include "partials/footer_form.php"; ?>

    </section>
</main>