<?php
require_once "koneksi.php";
require_once "utils.php";

$form_id       = 1;
$user_id       = $_SESSION['id_user'];
$section_name  = 'program_terapi_lab';
$section_label = 'Program Terapi dan Laboratorium';

$submission    = getSubmission($user_id, $form_id, $mysqli);
$existing_data = $submission ? getSectionData($submission['id'], $section_name, $mysqli) : [];

// Load existing dynamic rows
$existing_obat = $existing_data['obat'] ?? [];
$existing_lab  = $existing_data['lab']  ?? [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

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

        <!-- NOTIFIKASI -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>

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
                            <button type="button" class="btn btn-primary btn-sm" onclick="tambahRowObat()">+ Tambah Obat</button>
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
                            <button type="button" class="btn btn-primary btn-sm" onclick="tambahRowLab()">+ Tambah Pemeriksaan</button>
                        </div>
                    </div>

                    <!-- TOMBOL SIMPAN -->
                    <div class="row mb-3">
                        <div class="col-sm-12 d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">Simpan Data</button>
                        </div>
                    </div>

                    <script>
                        let rowObatCount = 1;
                        let rowLabCount  = 1;

                        const existingObat = <?= json_encode($existing_obat) ?>;
                        const existingLab  = <?= json_encode($existing_lab) ?>;

                        // ---- OBAT ----
                        function tambahRowObat(data = null) {
                            const tbody = document.getElementById('tbody-obat');
                            const index = rowObatCount;
                            const row   = document.createElement('tr');

                            row.innerHTML = `
                                <td class="text-center align-middle">${index}</td>
                                <td><input type="text" class="form-control form-control-sm" name="obat[${index}][jenis_obat]" value="${data?.jenis_obat ?? ''}"></td>
                                <td><input type="text" class="form-control form-control-sm" name="obat[${index}][dosis]" value="${data?.dosis ?? ''}"></td>
                                <td><input type="text" class="form-control form-control-sm" name="obat[${index}][kegunaan]" value="${data?.kegunaan ?? ''}"></td>
                                <td><input type="text" class="form-control form-control-sm" name="obat[${index}][cara_pemberian]" value="${data?.cara_pemberian ?? ''}"></td>
                                <td class="text-center align-middle">
                                    <button type="button" class="btn btn-danger btn-sm" onclick="hapusRow(this)">x</button>
                                </td>
                            `;

                            tbody.appendChild(row);
                            rowObatCount++;
                        }

                        // ---- LAB ----
                        function tambahRowLab(data = null) {
                            const tbody = document.getElementById('tbody-lab');
                            const index = rowLabCount;
                            const row   = document.createElement('tr');

                            row.innerHTML = `
                                <td class="text-center align-middle">${index}</td>
                                <td><input type="text" class="form-control form-control-sm" name="lab[${index}][pemeriksaan]" value="${data?.pemeriksaan ?? ''}"></td>
                                <td><input type="text" class="form-control form-control-sm" name="lab[${index}][hasil]" value="${data?.hasil ?? ''}"></td>
                                <td><input type="text" class="form-control form-control-sm" name="lab[${index}][nilai_normal]" value="${data?.nilai_normal ?? ''}"></td>
                                <td class="text-center align-middle">
                                    <button type="button" class="btn btn-danger btn-sm" onclick="hapusRow(this)">x</button>
                                </td>
                            `;

                            tbody.appendChild(row);
                            rowLabCount++;
                        }

                        function hapusRow(btn) {
                            btn.closest('tr').remove();
                        }

                        // Load existing rows on page load
                        window.addEventListener('load', function () {
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
                        });

                        const existingData = <?= json_encode($existing_data) ?>;
                    </script>

                </form>

                <?php include "tab_navigasi.php"; ?>

            </div>
        </div>

    </section>
</main>