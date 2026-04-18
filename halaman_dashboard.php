<?php
require_once 'utils.php';
require_once 'koneksi.php';

// Ambil semua mahasiswa dari tbl_user
$sql = "SELECT id_user, nama, npm FROM tbl_user WHERE level = 'Mahasiswa' ORDER BY nama ASC";
$result = $mysqli->query($sql);
$mahasiswaList = [];
while ($row = $result->fetch_assoc()) {
    $mahasiswaList[] = $row;
}
?>

<main id="main" class="main">
    <div class="pagetitle">
        <h1>List Mahasiswa</h1>
    </div>
    <!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Data Mahasiswa</h5>

                        <table id="tabelMahasiswa" class="table table-bordered table-striped datatable">
                            <thead>
                                <tr>
                                    <th width="50">No</th>
                                    <th>Nama</th>
                                    <th>NPM</th>
                                    <th width="200">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($mahasiswaList as $index => $mhs) : ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td><?= htmlspecialchars($mhs['nama']) ?></td>
                                        <td><?= htmlspecialchars($mhs['npm']) ?></td>
                                        <td class="text-center">
                                            <a href="index.php?page=dashboard/detail_mahasiswa&id=<?= $mhs['id_user'] ?>" class="btn btn-primary btn-sm">
                                                <i class="ri-eye-line me-1"></i> Lihat Detail
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
<!-- End #main -->

<!-- DataTables CSS (skip kalau udah di-include di layout utama) -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

<!-- DataTables JS (skip kalau udah di-include di layout utama) -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function () {
        $('#tabelMahasiswa').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/id.json' // Bahasa Indonesia
            },
            columnDefs: [
                { orderable: false, targets: [3] } // Kolom Aksi tidak bisa di-sort
            ]
        });
    });
</script>