<?php
require_once 'utils.php';
require_once 'koneksi.php';

// Handle delete
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    if ($id > 0) {
        $mysqli->query("DELETE FROM tbl_user WHERE id_user = $id");
    }
    echo "<script>window.location.href='index.php?page=manage_user&success=delete';</script>";
    exit;
}

$success = $_GET['success'] ?? null;
?>

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Manage User</h1>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Daftar User</h5>
                            <a href="index.php?page=form_user" class="btn btn-primary btn-sm">
                                <i class="ri-user-add-line me-1"></i> Tambah User
                            </a>
                        </div>

                        <?php if ($success === 'tambah'): ?>
                            <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                                User berhasil ditambahkan.
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php elseif ($success === 'edit'): ?>
                            <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                                User berhasil diupdate.
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php elseif ($success === 'delete'): ?>
                            <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                                User berhasil dihapus.
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <table id="tabelUser" class="table table-bordered table-striped datatable mt-3">
                            <thead>
                                <tr>
                                    <th width="50">No</th>
                                    <th>Nama</th>
                                    <th>NPM</th>
                                    <th>Username</th>
                                    <th>Level</th>
                                    <th class="text-center" width="200">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql = "SELECT id_user, nama, npm, username, level FROM tbl_user ORDER BY nama ASC";
                                $result = $mysqli->query($sql);
                                $no = 1;
                                while ($row = $result->fetch_assoc()):
                                ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= htmlspecialchars($row['nama']) ?></td>
                                        <td><?= htmlspecialchars($row['npm'] ?? '-') ?></td>
                                        <td><?= htmlspecialchars($row['username']) ?></td>
                                        <td>
                                            <?php
                                            $levelClass = [
                                                'Admin'     => 'danger',
                                                'Dosen'     => 'primary',
                                                'Mahasiswa' => 'success',
                                            ];
                                            $lvl = $row['level'];
                                            $cls = $levelClass[$lvl] ?? 'secondary';
                                            echo "<span class='badge bg-{$cls}'>{$lvl}</span>";
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <a href="index.php?page=form_user&id=<?= $row['id_user'] ?>" class="btn btn-sm btn-warning">
                                                <i class="ri-edit-line me-1"></i> Edit
                                            </a>
                                            <button class="btn btn-sm btn-danger ms-1" onclick="confirmDelete(<?= $row['id_user'] ?>, '<?= htmlspecialchars($row['nama']) ?>')">
                                                <i class="ri-delete-bin-line me-1"></i> Hapus
                                            </button>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<!-- Modal Konfirmasi Delete -->
<div class="modal fade" id="modalDelete" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Yakin ingin menghapus user <strong id="namaUserDelete"></strong>?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <a id="btnConfirmDelete" href="#" class="btn btn-danger">Hapus</a>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function () {
        $('#tabelUser').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
            },
            columnDefs: [
                { orderable: false, targets: [5] }
            ]
        });
    });

    function confirmDelete(id, nama) {
        document.getElementById('namaUserDelete').textContent = nama;
        document.getElementById('btnConfirmDelete').href = 'index.php?page=manage_user&action=delete&id=' + id;
        new bootstrap.Modal(document.getElementById('modalDelete')).show();
    }
</script>