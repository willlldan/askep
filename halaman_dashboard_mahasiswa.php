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
        s.status
    FROM forms f
    LEFT JOIN submissions s ON s.form_id = f.id AND s.user_id = $user_id
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
                                    <th width="130">Status</th>
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
                                            $statusMap = [
                                                'draft'     => ['label' => 'Draft',     'class' => 'secondary'],
                                                'submitted' => ['label' => 'Submitted', 'class' => 'primary'],
                                                'revision'  => ['label' => 'Revision',  'class' => 'warning'],
                                                'approved'  => ['label' => 'Approved',  'class' => 'success'],
                                            ];
                                            $status = $form['status'] ?? null;
                                            if ($status && isset($statusMap[$status])) {
                                                $s = $statusMap[$status];
                                                echo "<span class='badge bg-{$s['class']}'>{$s['label']}</span>";
                                            } else {
                                                echo "<span class='badge bg-light text-dark border'>Belum Diisi</span>";
                                            }
                                            ?>
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
                                                class="btn btn-sm btn-success<?= ($form['status'] !== 'approved' ? ' disabled' : '') ?>"
                                                target="_blank"
                                                <?= ($form['status'] !== 'approved' ? 'tabindex="-1" aria-disabled="true" onclick="return false;"' : '') ?>>
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
                targets: [3, 4]
            }]
        });
    });
</script>