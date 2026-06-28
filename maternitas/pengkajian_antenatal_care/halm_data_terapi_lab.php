<?php
$form_id       = 1;
$section_name  = 'program_terapi_lab';
$section_label = 'Program Terapi dan Laboratorium';
include dirname(__DIR__, 2) . '/partials/init_section.php';

// Load existing dynamic rows
$existing_obat = $existing_data['obat'] ?? [];
$existing_lab  = $existing_data['lab']  ?? [];

// =============================================
// HANDLE POST - MAHASISWA SIMPAN DATA
// =============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $level === 'Mahasiswa') {
    if (isLocked($submission)) {
        redirectWithMessage($_SERVER['REQUEST_URI'], 'error', 'Data tidak dapat diubah karena sedang dalam proses review.');
    }

    // Proses dynamic rows obat
    $obat = [];
    if (!empty($_POST['obat'])) {
        foreach ($_POST['obat'] as $index => $row) {
            if (empty($row['jenis_obat']) && empty($row['dosis']) && empty($row['kegunaan'])) {
                continue;
            }
            $obat[] = [
                'jenis_obat'     => $row['jenis_obat']     ?? '',
                'dosis'          => $row['dosis']           ?? '',
                'kegunaan'       => $row['kegunaan']        ?? '',
                'cara_pemberian' => $row['cara_pemberian']  ?? '',
            ];
        }
    }

    // Proses dynamic rows lab
    $lab = [];
    if (!empty($_POST['lab'])) {
        foreach ($_POST['lab'] as $index => $row) {
            if (empty($row['pemeriksaan']) && empty($row['hasil']) && empty($row['nilai_normal'])) {
                continue;
            }
            $lab[] = [
                'pemeriksaan'  => $row['pemeriksaan']  ?? '',
                'hasil'        => $row['hasil']         ?? '',
                'nilai_normal' => $row['nilai_normal']  ?? '',
            ];
        }
    }

    $data = [
        'obat' => $obat,
        'lab'  => $lab,
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
                <h5 class="card-title"><strong>PROGRAM TERAPI & LABORATORIUM</strong></h5>
                <form class="needs-validation" novalidate action="" method="POST">
                    <!-- ===================== TABEL OBAT ===================== -->
                    <p class="text-primary fw-bold mb-2">Obat-obatan yang Dikonsumsi Saat Ini</p>
                    <table class="table table-bordered" id="tabel-obat">
                        <thead>
                            <tr>
                                <th class="text-center" style="width:40px">No</th>
                                <th class="text-center">Jenis Obat</th>
                                <th class="text-center">Dosis</th>
                                <th class="text-center">Kegunaan</th>
                                <th class="text-center">Cara Pemberian</th>
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
                    <!-- ===================== TABEL LAB ===================== -->
                    <p class="text-primary fw-bold mb-2">Hasil Pemeriksaan Penunjang dan Laboratorium</p>
                    <table class="table table-bordered" id="tabel-lab">
                        <thead>
                            <tr>
                                <th class="text-center" style="width:40px">No</th>
                                <th class="text-center">Pemeriksaan</th>
                                <th class="text-center">Hasil</th>
                                <th class="text-center">Nilai Normal</th>
                                <th class="text-center" style="width:60px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="tbody-lab">
                            <!-- Dynamic rows masuk sini -->
                        </tbody>
                    </table>
                    <div class="row mb-4">
                        <div class="col-sm-12 d-flex justify-content-end">
                            <button type="button" class="btn btn-primary btn-sm" id="btn-tambah-lab" onclick="tambahRowLab()">+ Tambah Pemeriksaan</button>
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
                        let rowObatCount = 1;
                        let rowLabCount = 1;
                        const existingObat = <?= json_encode($existing_obat) ?>;
                        const existingLab = <?= json_encode($existing_lab) ?>;
                        const isReadonly = <?= json_encode($is_readonly) ?>;
                        // ---- OBAT ----

                         function autoResizeTextarea(el) {
                        el.style.height = 'auto';
                        el.style.height = el.scrollHeight + 'px';
                        }

                        function tambahRowObat(data = null) {
                            const tbody = document.getElementById('tbody-obat');
                            const index = rowObatCount;
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td class="text-center align-middle">${index}</td>
                                <td>
                                    ${
                                    isReadonly
                                    ? `<div class="readonly-text">${data?.jenis_obat ?? ''}</div>`
                                    : `<textarea
                                    class="form-control form-control-sm auto-resize"
                                    name="obat[${index}][jenis_obat]"
                                    rows="2"
                                    style="resize:none; overflow:hidden;"
                                    oninput="autoResizeTextarea(this)"
                                    >${data?.jenis_obat ?? ''}</textarea>`
                                    }
                                </td>
                                <td>
                                    ${
                                    isReadonly
                                    ? `<div class="readonly-text">${data?.dosis ?? ''}</div>`
                                    : `<textarea
                                    class="form-control form-control-sm auto-resize"
                                    name="obat[${index}][dosis]"
                                    rows="2"
                                    style="resize:none; overflow:hidden;"
                                    oninput="autoResizeTextarea(this)"
                                    >${data?.dosis ?? ''}</textarea>`
                                    }
                                </td>
                                <td>
                                    ${
                                    isReadonly
                                    ? `<div class="readonly-text">${data?.kegunaan ?? ''}</div>`
                                    : `<textarea
                                    class="form-control form-control-sm auto-resize"
                                    name="obat[${index}][kegunaan]"
                                    rows="2"
                                    style="resize:none; overflow:hidden;"
                                    oninput="autoResizeTextarea(this)"
                                    >${data?.kegunaan ?? ''}</textarea>`
                                    }
                                </td>
                                <td>
                                    ${
                                    isReadonly
                                    ? `<div class="readonly-text">${data?.cara_pemberian ?? ''}</div>`
                                    : `<textarea
                                    class="form-control form-control-sm auto-resize"
                                    name="obat[${index}][cara_pemberian]"
                                    rows="2"
                                    style="resize:none; overflow:hidden;"
                                    oninput="autoResizeTextarea(this)"
                                    >${data?.cara_pemberian ?? ''}</textarea>`
                                    }
                                </td>
                                <td class="text-center align-middle">
                                    <button type="button" class="btn btn-danger btn-sm" onclick="hapusRow(this)" ${isReadonly ? 'disabled' : ''}>x</button>
                                </td>
                            `;
                            tbody.appendChild(row);

                            row.querySelectorAll('.auto-resize').forEach(autoResizeTextarea);

                            rowObatCount++;
                        }
                        // ---- LAB ----
                        function tambahRowLab(data = null) {
                            const tbody = document.getElementById('tbody-lab');
                            const index = rowLabCount;
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td class="text-center align-middle">${index}</td>
                                <td>
                                    ${
                                    isReadonly
                                    ? `<div class="readonly-text">${data?.pemeriksaan ?? ''}</div>`
                                    : `<textarea
                                    class="form-control form-control-sm auto-resize"
                                    name="lab[${index}][pemeriksaan]"
                                    rows="2"
                                    style="resize:none; overflow:hidden;"
                                    oninput="autoResizeTextarea(this)"
                                    >${data?.pemeriksaan ?? ''}</textarea>`
                                    }
                                </td>
                                <td>
                                    ${
                                    isReadonly
                                    ? `<div class="readonly-text">${data?.hasil ?? ''}</div>`
                                    : `<textarea
                                    class="form-control form-control-sm auto-resize"
                                    name="lab[${index}][hasil]"
                                    rows="2"
                                    style="resize:none; overflow:hidden;"
                                    oninput="autoResizeTextarea(this)"
                                    >${data?.hasil ?? ''}</textarea>`
                                    }
                                </td>
                                <td>
                                    ${
                                    isReadonly
                                    ? `<div class="readonly-text">${data?.nilai_normal ?? ''}</div>`
                                    : `<textarea
                                    class="form-control form-control-sm auto-resize"
                                    name="lab[${index}][nilai_normal]"
                                    rows="2"
                                    style="resize:none; overflow:hidden;"
                                    oninput="autoResizeTextarea(this)"
                                    >${data?.nilai_normal ?? ''}</textarea>`
                                    }
                                </td>
                                <td class="text-center align-middle">
                                    <button type="button" class="btn btn-danger btn-sm" onclick="hapusRow(this)" ${isReadonly ? 'disabled' : ''}>x</button>
                                </td>
                            `;
                            tbody.appendChild(row);

                            row.querySelectorAll('.auto-resize').forEach(autoResizeTextarea);
                            
                            rowLabCount++;
                        }

                        function hapusRow(btn) {
                            btn.closest('tr').remove();
                        }
                        // Load existing rows on page load
                        window.addEventListener('load', function() {
                            if (existingObat && existingObat.length > 0) {
                                existingObat.forEach(row => tambahRowObat(row));
                            } else {
                                tambahRowObat(); // default 1 row kosong
                            }
                            if (existingLab && existingLab.length > 0) {
                                existingLab.forEach(row => tambahRowLab(row));
                            } else {
                                tambahRowLab(); // default 1 row kosong
                            }
                            // Disable add buttons if readonly
                            if (isReadonly) {
                                document.getElementById('btn-tambah-obat').setAttribute('disabled', 'disabled');
                                document.getElementById('btn-tambah-lab').setAttribute('disabled', 'disabled');
                            }
                        });
                        const existingData = <?= json_encode($existing_data) ?>;
                    </script>
                </form>

            </div>
        </div>

        <?php include "partials/footer_form.php" ?>

    </section>
</main>