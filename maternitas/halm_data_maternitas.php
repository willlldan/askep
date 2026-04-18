<?php
require_once "koneksi.php";
require_once "utils.php";

$form_id = 1; // ANC
$level   = $_SESSION['level'];
$user_id = $_SESSION['id_user'];
?>

<main id="main" class="main">
    <section class="section dashboard">

        <?php if ($level === 'Dosen'): ?>
            <!-- ================================ -->
            <!-- POV DOSEN: LIST MAHASISWA -->
            <!-- ================================ -->
            <div class="card mt-3">
                <div class="card-body">
                    <h5 class="card-title"><strong>Data Submission - Antenatal Care</strong></h5>

                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Mahasiswa</th>
                                <th>NPM</th>
                                <th>RS/Ruangan</th>
                                <th>Tanggal Pengkajian</th>
                                <th>Status</th>
                                <th>Tanggal Submit</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $submissions = getAllSubmissions($form_id, $mysqli);
                            if (empty($submissions)): ?>
                                <tr>
                                    <td colspan="8" class="text-center">Belum ada submission</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($submissions as $i => $sub): ?>
                                    <tr>
                                        <td><?= $i + 1 ?></td>
                                        <td><?= htmlspecialchars($sub['mahasiswa_name']) ?></td>
                                        <td><?= htmlspecialchars($sub['mahasiswa_npm']) ?></td>
                                        <td><?= htmlspecialchars($sub['rs_ruangan'] ?? '-') ?></td>
                                        <td><?= htmlspecialchars($sub['tanggal_pengkajian'] ?? '-') ?></td>
                                        <td>
                                            <?php
                                            $badge = [
                                                'draft'     => 'secondary',
                                                'submitted' => 'primary',
                                                'revision'  => 'warning',
                                                'approved'  => 'success',
                                            ];
                                            $status = $sub['status'];
                                            ?>
                                            <span class="badge bg-<?= $badge[$status] ?>">
                                                <?= ucfirst($status) ?>
                                            </span>
                                        </td>
                                        <td><?= $sub['submitted_at'] ? date('d/m/Y H:i', strtotime($sub['submitted_at'])) : '-' ?></td>
                                        <td>
                                            <?php if ($sub['status'] === 'submitted' || $sub['status'] === 'revision' || $sub['status'] === 'approved'): ?>
                                                <a href="index.php?page=maternitas/pengkajian_antenatal_care&tab=data_demografi&submission_id=<?= $sub['id'] ?>"
                                                    class="btn btn-sm btn-primary">
                                                    Review
                                                </a>
                                            <?php else: ?>
                                                <span class="text-muted">Belum submit</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        <?php elseif ($level === 'Mahasiswa'): ?>
            <!-- ================================ -->
            <!-- POV MAHASISWA: LIST FORM & STATUS -->
            <!-- ================================ -->
            <div class="card mt-3">
                <div class="card-body">
                    <h5 class="card-title"><strong>Data Maternitas</strong></h5>

                    <?php $submission = getSubmission($user_id, $form_id, $mysqli); ?>
                    <?php var_dump($submission); ?>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Form</th>
                                <th>Status</th>
                                <th>Tanggal Submit</th>
                                <th>Dosen Reviewer</th>
                                <th>Tanggal Review</th>
                                <th>Cetak</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Antenatal Care</td>
                                <td>
                                    <?php if ($submission): ?>
                                        <?php
                                        $badge = [
                                            'draft'     => 'secondary',
                                            'submitted' => 'primary',
                                            'revision'  => 'warning',
                                            'approved'  => 'success',
                                        ];
                                        $status = $submission['status'];
                                        ?>
                                        <span class="badge bg-<?= $badge[$status] ?>">
                                            <?= ucfirst($status) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Belum Diisi</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= $submission && $submission['submitted_at'] ? date('d/m/Y H:i', strtotime($submission['submitted_at'])) : '-' ?></td>
                                <td><?= $submission && $submission['reviewed_by'] ? htmlspecialchars($submission['dosen_name'] ?? '-') : '-' ?></td>
                                <td><?= $submission && $submission['reviewed_at'] ? date('d/m/Y H:i', strtotime($submission['reviewed_at'])) : '-' ?></td>
                                <td>
                                    <?php if ($submission && $submission['status'] === 'approved'): ?>
                                        <a href="maternitas/cetak.php?submission_id=<?= $submission['id'] ?>"
                                            class="btn btn-sm btn-success" target="_blank">
                                            Cetak PDF
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted">Belum tersedia</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>

    </section>
</main>