<?php
require_once "koneksi.php";
require_once "utils.php";

$form_id       = 1;
$user_id       = $_SESSION['id_user'];
$section_name  = 'analisa_data';
$section_label = 'Analisa Data';

$submission    = getSubmission($user_id, $form_id, $mysqli);
$existing_data = $submission ? getSectionData($submission['id'], $section_name, $mysqli) : [];

// Load existing dynamic rows
$existing_klasifikasi = $existing_data['klasifikasi'] ?? [];
$existing_analisa     = $existing_data['analisa']     ?? [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isLocked($submission)) {
        redirectWithMessage($_SERVER['REQUEST_URI'], 'error', 'Data tidak dapat diubah karena sedang dalam proses review.');
    }

    // Proses dynamic rows klasifikasi data
    $klasifikasi = [];
    if (!empty($_POST['klasifikasi'])) {
        foreach ($_POST['klasifikasi'] as $index => $row) {
            if (empty($row['ds']) && empty($row['do'])) {
                continue;
            }
            $klasifikasi[] = [
                'ds' => $row['ds'] ?? '',
                'do' => $row['do'] ?? '',
            ];
        }
    }

    // Proses dynamic rows analisa data
    $analisa = [];
    if (!empty($_POST['analisa'])) {
        foreach ($_POST['analisa'] as $index => $row) {
            if (empty($row['ds_do']) && empty($row['etiologi']) && empty($row['masalah'])) {
                continue;
            }
            $analisa[] = [
                'ds_do'   => $row['ds_do']   ?? '',
                'etiologi' => $row['etiologi'] ?? '',
                'masalah'  => $row['masalah']  ?? '',
            ];
        }
    }

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

        <!-- NOTIFIKASI -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>

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
                            <button type="button" class="btn btn-primary btn-sm" onclick="tambahRowKlasifikasi()">+ Tambah Baris</button>
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
                            <button type="button" class="btn btn-primary btn-sm" onclick="tambahRowAnalisa()">+ Tambah Baris</button>
                        </div>
                    </div>

                    <!-- TOMBOL SIMPAN -->
                    <div class="row mb-3">
                        <div class="col-sm-12 d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">Simpan Data</button>
                        </div>
                    </div>

                    <script>
                        let rowKlasifikasiCount = 1;
                        let rowAnalisaCount     = 1;

                        const existingKlasifikasi = <?= json_encode($existing_klasifikasi) ?>;
                        const existingAnalisa     = <?= json_encode($existing_analisa) ?>;

                        // ---- KLASIFIKASI DATA ----
                        function tambahRowKlasifikasi(data = null) {
                            const tbody = document.getElementById('tbody-klasifikasi');
                            const index = rowKlasifikasiCount;
                            const row   = document.createElement('tr');

                            row.innerHTML = `
                                <td class="text-center align-middle">${index}</td>
                                <td>
                                    <textarea
                                        class="form-control form-control-sm"
                                        name="klasifikasi[${index}][ds]"
                                        rows="2"
                                        style="resize:none; overflow:hidden;"
                                        oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                    >${data?.ds ?? ''}</textarea>
                                </td>
                                <td>
                                    <textarea
                                        class="form-control form-control-sm"
                                        name="klasifikasi[${index}][do]"
                                        rows="2"
                                        style="resize:none; overflow:hidden;"
                                        oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                    >${data?.do ?? ''}</textarea>
                                </td>
                                <td class="text-center align-middle">
                                    <button type="button" class="btn btn-danger btn-sm" onclick="hapusRow(this)">x</button>
                                </td>
                            `;

                            tbody.appendChild(row);
                            rowKlasifikasiCount++;
                        }

                        // ---- ANALISA DATA ----
                        function tambahRowAnalisa(data = null) {
                            const tbody = document.getElementById('tbody-analisa');
                            const index = rowAnalisaCount;
                            const row   = document.createElement('tr');

                            row.innerHTML = `
                                <td class="text-center align-middle">${index}</td>
                                <td>
                                    <textarea
                                        class="form-control form-control-sm"
                                        name="analisa[${index}][ds_do]"
                                        rows="2"
                                        style="resize:none; overflow:hidden;"
                                        oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                    >${data?.ds_do ?? ''}</textarea>
                                </td>
                                <td>
                                    <textarea
                                        class="form-control form-control-sm"
                                        name="analisa[${index}][etiologi]"
                                        rows="2"
                                        style="resize:none; overflow:hidden;"
                                        oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                    >${data?.etiologi ?? ''}</textarea>
                                </td>
                                <td>
                                    <textarea
                                        class="form-control form-control-sm"
                                        name="analisa[${index}][masalah]"
                                        rows="2"
                                        style="resize:none; overflow:hidden;"
                                        oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                    >${data?.masalah ?? ''}</textarea>
                                </td>
                                <td class="text-center align-middle">
                                    <button type="button" class="btn btn-danger btn-sm" onclick="hapusRow(this)">x</button>
                                </td>
                            `;

                            tbody.appendChild(row);
                            rowAnalisaCount++;
                        }

                        function hapusRow(btn) {
                            btn.closest('tr').remove();
                        }

                        // Load existing rows on page load
                        window.addEventListener('load', function () {
                            if (existingKlasifikasi && existingKlasifikasi.length > 0) {
                                existingKlasifikasi.forEach(row => tambahRowKlasifikasi(row));
                            } else {
                                tambahRowKlasifikasi(); // default 1 row kosong
                            }

                            if (existingAnalisa && existingAnalisa.length > 0) {
                                existingAnalisa.forEach(row => tambahRowAnalisa(row));
                            } else {
                                tambahRowAnalisa(); // default 1 row kosong
                            }
                        });

                        const existingData = <?= json_encode($existing_data) ?>;
                    </script>

                </form>

                <?php include "tab_navigasi.php"; ?>

            </div>
        </div>

    </section>
</main>