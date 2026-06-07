<?php
require_once 'utils.php';
require_once 'koneksi.php';

// Ambil id mahasiswa dari URL
$user_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Ambil data mahasiswa
$sql = "SELECT id_user, nama, npm FROM tbl_user WHERE id_user = $user_id AND level = 'Mahasiswa' LIMIT 1";
$result = $mysqli->query($sql);
$mahasiswa = $result->fetch_assoc();

if (!$mahasiswa) {
    echo "<div class='alert alert-danger'>Mahasiswa tidak ditemukan.</div>";
    exit;
}

// Ambil semua forms beserta status submission mahasiswa ini
// LEFT JOIN supaya form yang belum disubmit tetap muncul
$sql = "
    SELECT 
        f.id AS form_id,
        f.form_name,
        f.department,
        f.slug,
        s.id AS submission_id,
        s.status,
        s.dosen_review_status,
        s.preceptor_review_status,
        r.level AS reviewer_level
    FROM forms f
    LEFT JOIN submissions s ON s.form_id = f.id AND s.user_id = $user_id
    LEFT JOIN tbl_user r ON s.reviewed_by = r.id_user
    ORDER BY f.department ASC, f.form_name ASC
";
$result = $mysqli->query($sql);
$submissions = [];
while ($row = $result->fetch_assoc()) {
    $submissions[] = $row;
}
?>

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Detail Mahasiswa</h1>
    </div>
    <!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-12">

                <!-- Info Mahasiswa -->
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Informasi Mahasiswa</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless mb-0">
                                    <tr>
                                        <td width="100"><strong>Nama</strong></td>
                                        <td>: <?= htmlspecialchars($mahasiswa['nama']) ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>NIM</strong></td>
                                        <td>: <?= htmlspecialchars($mahasiswa['npm']) ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Info Mahasiswa -->

                <!-- Tabel Submissions -->
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Daftar Form</h5>
                            <a href="halaman_list_mahasiswa.php" class="btn btn-sm btn-secondary">
                                <i class="ri-arrow-left-line me-1"></i> Kembali
                            </a>
                        </div>

                        <table id="tabelSubmissions" class="table table-bordered table-striped datatable mt-3">
                            <thead>
                                <tr>
                                    <th width="50">No</th>
                                    <th>Department</th>
                                    <th>Form</th>
                                    <th class="text-center" width="100">App. Dosen</th>
                                    <th class="text-center" width="100">App. Preceptor</th>
                                    <th class="text-center" width="200">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($submissions as $index => $sub) : ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td><?= htmlspecialchars($sub['department']) ?></td>
                                        <td><?= htmlspecialchars($sub['form_name']) ?></td>
                                        <td class="text-center">
                                            <?php
                                            $status = $sub['status'] ?? null;
                                            $dosenStatus = $sub['dosen_review_status'] ?? null;
                                            $preceptorStatus = $sub['preceptor_review_status'] ?? null;
                                            $reviewerLevel = $sub['reviewer_level'] ?? null;

                                            if (!$dosenStatus && $reviewerLevel === 'Dosen' && !empty($status) && $status !== 'draft') {
                                                $dosenStatus = $status;
                                            }

                                            if (!$preceptorStatus && $reviewerLevel === 'Preceptor' && !empty($status) && $status !== 'draft') {
                                                $preceptorStatus = $status;
                                            }

                                            $departmentSlug = urlencode(strtolower($sub['department'])); // slug dari departemen
                                            ?>
                                            <?= renderStatusBadge($dosenStatus, 'Belum Diisi') ?>
                                        </td>
                                        <td class="text-center">
                                            <?= renderStatusBadge($preceptorStatus, 'Belum Diisi') ?>
                                        </td>
                                        <td class="text-center">
                                            <?php if (in_array($dosenStatus, ['submitted', 'revision', 'approved']) || in_array($preceptorStatus, ['submitted', 'revision', 'approved'])): ?>
                                                <a href="index.php?page=<?= $departmentSlug ?>/<?= $sub['slug'] ?>&submission_id=<?= $sub['submission_id'] ?>"
                                                    class="btn btn-sm btn-primary">
                                                    <i class="ri-edit-line me-1"></i> Review
                                                </a>
                                                <a href="cetak_form/cetak.php?submission_id=<?= $sub['submission_id'] ?>"
                                                    target="_blank"
                                                    class="btn btn-sm btn-success ms-1">
                                                    <i class="ri-printer-line me-1"></i> Cetak
                                                </a>
                                            <?php else: ?>
                                                <span class="text-muted">Belum submit</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>

                    </div>
                </div>
                <!-- End Tabel Submissions -->

            </div>
        </div>
    </section>
</main>
<!-- End #main -->

<!-- DataTables CSS (skip kalau udah di-include di layout utama) -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

<!-- DataTables JS (skip kalau udah di-include di layout utama) -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function() {
        $('#tabelSubmissions').DataTable({
            pageLength: 20,
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
            },
            columnDefs: [{
                    orderable: false,
                    targets: [3, 4, 5]
                } // Kolom Status & Aksi tidak bisa di-sort
            ]
        });
    });
</script>
