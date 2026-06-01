<?php
$form_id       = 30;
$section_name  = 'klasifikasi';
$section_label = 'Klasifikasi';
include dirname(__DIR__, 2) . '/partials/init_section.php';

// Load existing dynamic rows
$existing_klasifikasi = $existing_data['klasifikasi'] ?? [];

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
            if (empty($row['penunjang']) && empty($row['kesehatan'])) {
                continue;
            }
            $klasifikasi[] = [
                'penunjang' => $row['penunjang'] ?? '',
                'kesehatan' => $row['kesehatan'] ?? '',
            ];
        }
    }

    $data = [
        'klasifikasi' => $klasifikasi,
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

    <?php include "keluarga/format_keluarga/tab.php"; ?>

    <section class="section dashboard">

        <?php include dirname(__DIR__, 2) . '/partials/notifikasi.php'; ?>
        <?php include dirname(__DIR__, 2) . '/partials/status_section.php'; ?>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><strong>KLASIFIKASI DATA</strong></h5>
                <form class="needs-validation" novalidate action="" method="POST">
                    <!-- ===================== TABEL KLASIFIKASI DATA ===================== -->
                    <p class="text-primary fw-bold mb-2">XII. Klasifikasi Data</p>
                    <table class="table table-bordered" id="tabel-klasifikasi">
                        <thead>
                            <tr>
                                <th class="text-center" style="width:40px">No</th>
                                <th class="text-center">Data Kesehatan Keluarga (DS)</th>
                                <th class="text-center">Data Penunjang (DO)</th>
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
                        const existingKlasifikasi = <?= json_encode($existing_klasifikasi) ?>;
                        const isReadonly = <?= json_encode($is_readonly) ?>;
                        // ---- KLASIFIKASI DATA ----

                        function autoResizeTextarea(el) {
                            el.style.height = 'auto';
                            el.style.height = el.scrollHeight + 'px';
                        }

                        function tambahRowKlasifikasi(data = null) {
                            const tbody = document.getElementById('tbody-klasifikasi');
                            const index = rowKlasifikasiCount;
                            const row = document.createElement('tr');

                            row.innerHTML = `
                                <td class="text-center align-middle">${index}</td>

                                <td>
                                    ${
                                        isReadonly
                                            ? `<div class="readonly-text">${data?.penunjang ?? ''}</div>`
                                            : `<textarea
                                                    class="form-control form-control-sm auto-resize"
                                                    name="klasifikasi[${index}][penunjang]"
                                                    rows="2"
                                                    style="resize:none; overflow:hidden;"
                                                    oninput="autoResizeTextarea(this)"
                                                >${data?.penunjang ?? ''}</textarea>`
                                    }
                                </td>

                                <td>
                                    ${
                                        isReadonly
                                            ? `<div class="readonly-text">${data?.kesehatan ?? ''}</div>`
                                            : `<textarea
                                                    class="form-control form-control-sm auto-resize"
                                                    name="klasifikasi[${index}][kesehatan]"
                                                    rows="2"
                                                    style="resize:none; overflow:hidden;"
                                                    oninput="autoResizeTextarea(this)"
                                                >${data?.kesehatan ?? ''}</textarea>`
                                    }
                                </td>

                                <td class="text-center align-middle">
                                    <button
                                        type="button"
                                        class="btn btn-danger btn-sm"
                                        onclick="hapusRow(this)"
                                        ${isReadonly ? 'disabled' : ''}
                                    >
                                        x
                                    </button>
                                </td>
                            `;

                            tbody.appendChild(row);

                            // Auto resize textarea yang baru ditambahkan
                            row.querySelectorAll('.auto-resize').forEach(autoResizeTextarea);

                            rowKlasifikasiCount++;
                        }

                        function hapusRow(btn) {
                            btn.closest('tr').remove();
                        }

                        window.addEventListener('load', function () {
                            if (existingKlasifikasi && existingKlasifikasi.length > 0) {
                                existingKlasifikasi.forEach(row => tambahRowKlasifikasi(row));
                            } else {
                                tambahRowKlasifikasi();
                            }

                            if (isReadonly) {
                                document.getElementById('btn-tambah-klasifikasi')
                                    .setAttribute('disabled', 'disabled');
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