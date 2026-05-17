<?php
require_once "koneksi.php";
require_once "utils.php";

$form_id       = 7;
$section_name  = 'pengkajian_lanjut';
$section_label = 'Format Pengkajian Lanjutan';
include dirname(__DIR__, 2) . '/partials/init_section.php';


$existing_analisa     = $existing_data['analisa']     ?? [];

// =============================================
// HANDLE POST - MAHASISWA SIMPAN DATA
// =============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $level === 'Mahasiswa') {

    if (isLocked($submission)) {
        redirectWithMessage($_SERVER['REQUEST_URI'], 'error', 'Data tidak dapat diubah karena sedang dalam proses review.');
    }

    // Proses dynamic rows evaluasi
    $analisa = [];
    if (!empty($_POST['analisa'])) {
        foreach ($_POST['analisa'] as $index => $row) {
            if (empty($row['data_subjektif_analisa']) && empty($row['data_objektif_analisa']) && empty($row['masalah'])) {
                continue;
            }
            $analisa[] = [
                'data_subjektif_analisa'   => $row['data_subjektif_analisa']   ?? '',
                'data_objektif_analisa' => $row['data_objektif_analisa'] ?? '',
                'masalah'  => $row['masalah']  ?? '',
            ];
        }
    }

    $data = [
        'analisa'     => $analisa,
        // XI. Aspek Medis
        'diagnosa_medis'            => $_POST['diagnosa_medis'] ?? '',
        'terapi_medik'              => $_POST['terapi_medik'] ?? '',

        // XII. Data Fokus
        'data_subjektif'            => $_POST['data_subjektif'] ?? '',
        'data_objektif'             => $_POST['data_objektif'] ?? '',

        // XIV. Daftar Masalah Keperawatan
        'daftar_masalah_keperawatan' => $_POST['daftar_masalah_keperawatan'] ?? '',

        // XV. Pohon Masalah
        'efek'                      => $_POST['efek'] ?? '',
        'cara_problem'              => $_POST['cara_problem'] ?? '',
        'etiologi'                  => $_POST['etiologi'] ?? '',
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

    <?php include "jiwa/jiwa_rsud/tab.php"; ?>

    <section class="section dashboard">

        <?php include dirname(__DIR__, 2) . '/partials/notifikasi.php'; ?>
        <?php include dirname(__DIR__, 2) . '/partials/status_section.php'; ?>
        <div class="card">
            <div class="card-body">

                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

                    <h5 class="card-title"><strong>FORMAT PENGKAJIAN ANALISA KEPERAWATAN JIWA</strong></h5>
                    <!-- XI. Aspek Medis -->
                    <div class="row mb-2">
                        <label class="col-sm-4 col-form-label text-primary">
                            <strong>XI. Aspek Medis</strong>
                        </label>
                    </div>

                    <!-- Diagnosa Medis -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Diagnosa Medis</strong></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="diagnosa_medis" value="<?= val('diagnosa_medis', $existing_data) ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <!-- Terapi Medik -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Terapi Medik</strong></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="terapi_medik" value="<?= val('terapi_medik', $existing_data) ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <!-- XII. Data Fokus -->
                    <div class="row mb-2">
                        <label class="col-sm-4 col-form-label text-primary">
                            <strong>XII. Data Fokus</strong>
                        </label>
                    </div>

                    <!-- Data Subjektif -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Data Subjektif</strong></label>
                        <div class="col-sm-10">
                            <textarea name="data_subjektif" class="form-control" rows="3" <?= $ro ?>><?= val('data_subjektif', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- Data Objektif -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Data Objektif</strong></label>
                        <div class="col-sm-10">
                            <textarea name="data_objektif" class="form-control" rows="3" <?= $ro ?>><?= val('data_objektif', $existing_data) ?></textarea>
                        </div>
                    </div>
                    <!-- ===================== TABEL ANALISA DATA ===================== -->
                    <p class="text-primary fw-bold mb-2">XIII. Analisa Data</p>
                    <table class="table table-bordered" id="tabel-analisa">
                        <thead>
                            <tr>
                                <th class="text-center" style="width:40px">No</th>
                                <th class="text-center">Data Subjektif</th>
                                <th class="text-center">Data Objektif</th>
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
                            <button type="button" class="btn btn-primary btn-sm" id="btn-tambah-analisa" onclick="tambahRowAnalisa()">+ Tambah Baris</button>
                        </div>
                    </div>
                    <!-- TOMBOL SIMPAN -->


                    <!-- XIV. Daftar Masalah Keperawatan -->
                    <div class="row mb-2">
                        <label class="col-sm-5 col-form-label text-primary">
                            <strong>XIV. DAFTAR MASALAH KEPERAWATAN</strong>
                        </label>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Daftar Masalah Keperawatan</strong></label>
                        <div class="col-sm-10">
                            <textarea name="daftar_masalah_keperawatan" class="form-control" rows="3" <?= $ro ?>><?= val('daftar_masalah_keperawatan', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- XV. Pohon Masalah -->
                    <div class="row mb-2">
                        <label class="col-sm-5 col-form-label text-primary">
                            <strong>XV. POHON MASALAH</strong>
                        </label>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Efek</strong></label>
                        <div class="col-sm-10">
                            <textarea name="efek" class="form-control" rows="3" <?= $ro ?>><?= val('efek', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Cara Problem</strong></label>
                        <div class="col-sm-10">
                            <textarea name="cara_problem" class="form-control" rows="3" <?= $ro ?>><?= val('cara_problem', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Etiologi</strong></label>
                        <div class="col-sm-10">
                            <textarea name="etiologi" class="form-control" rows="3" <?= $ro ?>><?= val('etiologi', $existing_data) ?></textarea>
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
            let rowAnalisaCount = 1;
            const existingAnalisa = <?= json_encode($existing_analisa) ?>;
            const isReadonly = <?= json_encode($is_readonly) ?>;

            // ---- ANALISA DATA ----
            function tambahRowAnalisa(data = null) {
                const tbody = document.getElementById('tbody-analisa');
                const index = rowAnalisaCount;
                const row = document.createElement('tr');
                row.innerHTML = `
                                <td class="text-center align-middle">${index}</td>
                                <td>
                                    <textarea class="form-control form-control-sm" name="analisa[${index}][data_subjektif_analisa]" rows="2" style="resize:none; overflow:hidden;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" ${isReadonly ? 'readonly' : ''}>${data?.data_subjektif_analisa ?? ''}</textarea>
                                </td>
                                <td>
                                    <textarea class="form-control form-control-sm" name="analisa[${index}][data_objektif_analisa]" rows="2" style="resize:none; overflow:hidden;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" ${isReadonly ? 'readonly' : ''}>${data?.data_objektif_analisa ?? ''}</textarea>
                                </td>
                                <td>
                                    <textarea class="form-control form-control-sm" name="analisa[${index}][masalah]" rows="2" style="resize:none; overflow:hidden;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" ${isReadonly ? 'readonly' : ''}>${data?.masalah ?? ''}</textarea>
                                </td>
                                <td class="text-center align-middle">
                                    <button type="button" class="btn btn-danger btn-sm" onclick="hapusRow(this)" ${isReadonly ? 'disabled' : ''}>x</button>
                                </td>
                            `;
                tbody.appendChild(row);
                rowAnalisaCount++;
            }

            function hapusRow(btn) {
                btn.closest('tr').remove();
            }
            // Load existing rows on page load
            window.addEventListener('load', function() {

                if (existingAnalisa && existingAnalisa.length > 0) {
                    existingAnalisa.forEach(row => tambahRowAnalisa(row));
                } else {
                    tambahRowAnalisa(); // default 1 row kosong
                }
                // Disable add buttons if readonly
                if (isReadonly) {
                    document.getElementById('btn-tambah-analisa').setAttribute('disabled', 'disabled');
                }
            });
            const existingData = <?= json_encode($existing_data) ?>;
        </script>

        <?php include dirname(__DIR__, 2) . '/partials/footer_form.php'; ?>

    </section>
</main>