<?php
require_once "koneksi.php";
require_once "utils.php";


$form_id       = 6;
$section_name  = 'analisa_resume';
$section_label = 'Analisa Data Format Resume Keperawatan Poli Anak';
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

    <?php include "anak/format_resume/tab.php"; ?>

    <section class="section dashboard">

        <?php include dirname(__DIR__, 2) . '/partials/notifikasi.php'; ?>
        <?php include dirname(__DIR__, 2) . '/partials/status_section.php'; ?>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><strong>ANALISA DATA</strong></h5>
                <form class="needs-validation" novalidate action="" method="POST">
                    <!-- ===================== TABEL KLASIFIKASI DATA ===================== -->
                    <p class="fw-bold mb-2">8. Klasifikasi Data</p>
                    <table class="table table-bordered" id="tabel-klasifikasi">
                        <thead>
                            <tr>
                                <th class="text-center" style="width:40px">No</th>
                                <th class="text-center">Data Subjektif</th>
                                <th class="text-center">Data Objektif</th>
                                <th class="text-center" style="width:60px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="tbody-klasifikasi">
                            <!-- Dynamic rows masuk sini -->
                        </tbody>
                    </table>
                    <div class="row mb-4">
                        <div class="col-sm-12 d-flex justify-content-end">
                            <button type="button" class="btn btn-primary btn-sm" id="btn-tambah-klasifikasi" onclick="tambahRowKlasifikasi()">+ Tambah Baris</button>
                        </div>
                    </div>
                    <!-- ===================== TABEL ANALISA DATA ===================== -->
                    <p class="fw-bold mb-2">9. Analisa Data</p>
                    <table class="table table-bordered" id="tabel-analisa">
                        <thead>
                            <tr>
                                <th class="text-center" style="width:40px">No</th>
                                <th class="text-center">Data</th>
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
                            <button type="button" class="btn btn-primary btn-sm" id="btn-tambah-analisa" onclick="tambahRowAnalisa()">+ Tambah Baris</button>
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
                        // ---- KLASIFIKASI DATA ----
                        function tambahRowKlasifikasi(data = null) {
                            const tbody = document.getElementById('tbody-klasifikasi');
                            const index = rowKlasifikasiCount;
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td class="text-center align-middle">${index}</td>
                                <td>
                                    <textarea class="form-control form-control-sm" name="klasifikasi[${index}][ds]" rows="2" style="resize:none; overflow:hidden;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" ${isReadonly ? 'readonly' : ''}>${data?.ds ?? ''}</textarea>
                                </td>
                                <td>
                                    <textarea class="form-control form-control-sm" name="klasifikasi[${index}][do]" rows="2" style="resize:none; overflow:hidden;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" ${isReadonly ? 'readonly' : ''}>${data?.do ?? ''}</textarea>
                                </td>
                                <td class="text-center align-middle">
                                    <button type="button" class="btn btn-danger btn-sm" onclick="hapusRow(this)" ${isReadonly ? 'disabled' : ''}>x</button>
                                </td>
                            `;
                            tbody.appendChild(row);
                            rowKlasifikasiCount++;
                        }
                        // ---- ANALISA DATA ----
                        function tambahRowAnalisa(data = null) {
                            const tbody = document.getElementById('tbody-analisa');
                            const index = rowAnalisaCount;
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td class="text-center align-middle">${index}</td>
                                <td>
                                    <textarea class="form-control form-control-sm" name="analisa[${index}][ds_do]" rows="2" style="resize:none; overflow:hidden;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" ${isReadonly ? 'readonly' : ''}>${data?.ds_do ?? ''}</textarea>
                                </td>
                                <td>
                                    <textarea class="form-control form-control-sm" name="analisa[${index}][etiologi]" rows="2" style="resize:none; overflow:hidden;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" ${isReadonly ? 'readonly' : ''}>${data?.etiologi ?? ''}</textarea>
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
                            // Disable add buttons if readonly
                            if (isReadonly) {
                                document.getElementById('btn-tambah-klasifikasi').setAttribute('disabled', 'disabled');
                                document.getElementById('btn-tambah-analisa').setAttribute('disabled', 'disabled');
                            }
                        });
                        const existingData = <?= json_encode($existing_data) ?>;
                    </script>
                </form>

            </div>
        </div>

        <?php include dirname(__DIR__, 2) . '/partials/footer_form.php'; ?>

    </section>
</main>