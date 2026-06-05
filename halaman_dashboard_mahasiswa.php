<?php
require_once 'utils.php';
require_once 'koneksi.php';

$user_id = $_SESSION['id_user'];

// Ambil semua form beserta status submission milik mahasiswa ini
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
$forms  = [];
while ($row = $result->fetch_assoc()) {
    $forms[] = $row;
}
?>

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Dashboard</h1>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-0">Daftar Form</h5>

                        <table id="tabelForm" class="table table-bordered table-striped datatable mt-3">
                            <thead>
                                <tr>
                                    <th width="50">No</th>
                                    <th>Department</th>
                                    <th>Form</th>
                                    <th width="100">App. Dosen</th>
                                    <th width="100">App. Preceptor</th>
                                    <th class="text-center" width="250">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($forms as $index => $form): ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td><?= htmlspecialchars($form['department']) ?></td>
                                        <td><?= htmlspecialchars($form['form_name']) ?></td>
                                        <td class="text-center">
                                            <?php
                                            $status = $form['status'] ?? null;
                                            $dosenStatus = $form['dosen_review_status'] ?? null;
                                            $preceptorStatus = $form['preceptor_review_status'] ?? null;
                                            $reviewerLevel = $form['reviewer_level'] ?? null;

                                            if (!$dosenStatus && $reviewerLevel === 'Dosen' && !empty($status) && $status !== 'draft') {
                                                $dosenStatus = $status;
                                            }

                                            if (!$preceptorStatus && $reviewerLevel === 'Preceptor' && !empty($status) && $status !== 'draft') {
                                                $preceptorStatus = $status;
                                            }
                                            ?>
                                            <?= renderStatusBadge($dosenStatus, 'Belum Diisi') ?>
                                        </td>
                                        <td class="text-center">
                                            <?= renderStatusBadge($preceptorStatus, 'Belum Diisi') ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $url = "index.php?page=" . strtolower($form['department']) . "/{$form['slug']}";
                                            if ($form['submission_id']) {
                                                $url .= "&submission_id={$form['submission_id']}";
                                            }
                                            $isApproved = $form['status'] === 'approved';
                                            ?>
                                            <a href="<?= $url ?>" class="btn btn-sm <?= $isApproved ? 'btn-success' : 'btn-primary' ?>">
                                                <i class="<?= $isApproved ? 'ri-eye-line' : 'ri-edit-line' ?> me-1"></i>
                                                <?= $isApproved ? 'Lihat' : 'Isi Form' ?>
                                            </a>
                                            
                                            <a href="cetak_form/cetak.php?submission_id=<?= $form['submission_id'] ?>"
                                                class="btn btn-sm btn-success ms-2"
                                                target="_blank"                                                >
                                                <i class="ri-printer-line me-1"></i> Cetak
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<!-- DataTables CSS (skip kalau udah di-include di layout utama) -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

<!-- DataTables JS (skip kalau udah di-include di layout utama) -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function() {
        $('#tabelForm').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
            },
            columnDefs: [{
                orderable: false,
                targets: [3, 4, 5]
            }]
        });
    });
</script>
