<?php
require_once "koneksi.php";
require_once "utils.php";

if (isset($_POST['submit'])) {
    $no_dokumen = $_POST['no_dokumen'];
    $nama_peminjam = $_POST['nama_peminjam'];
    $status_peminjam = $_POST['status_peminjam'];
    $tgl_pinjam = $_POST['tgl_pinjam'];
    $tgl_kembali = $_POST['tgl_kembali'];
    $keterangan = $_POST['keterangan'];

    $sql = "INSERT INTO tbl_peminjaman_dokumen (
        no_dokumen, 
        nama_peminjam,
        status_peminjam, 
        tgl_pinjam, 
        tgl_kembali,
        keterangan
    ) VALUES (
        '$no_dokumen',
        '$nama_peminjam',
        '$status_peminjam',
        '$tgl_pinjam',
        '$tgl_kembali',
        '$keterangan' 
    )";

    if ($mysqli->query($sql) === TRUE) {
        echo "<script>alert('Tambah Peminjaman Dokumen berhasil.')</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $mysqli->error;
    }
}

$sql = "(SELECT no_dokumen, perihal, status_dokumen, label_arsip FROM tbl_dok_masuk)
        UNION
        (SELECT no_dokumen, perihal, status_dokumen, label_arsip FROM tbl_dok_keluar)
        UNION
        (SELECT no_dokumen,  perihal, status_dokumen, label_arsip FROM tbl_dok_pendukung)
        ORDER BY no_dokumen DESC";
$result = $mysqli->query($sql);
?>

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Tambah Peminjaman Dokumen</h1>
    </div>
    <br>
    <section class="section dashboard">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Peminjaman Dokumen</h5>

                <!-- General Form Elements -->
                <form class="needs-validation" novalidate action="" method="POST">
                    <!-- Bagian No Dokumen -->
                    <div class="row mb-3">
                        <label for="no_dokumen" class="col-sm-2 col-form-label">No Dokumen</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="no_dokumen" name="no_dokumen" readonly>
                        </div>
                        <div class="col-sm-2">
                            
                        <!-- Button trigger modal -->
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#noDokumenModal">
                                Pilih No Dokumen
                            </button>
                        </div>
                    </div>

                    <!-- Modal -->
                    <div class="modal fade" id="noDokumenModal" tabindex="-1" aria-labelledby="noDokumenModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row mb-3">
                                        <div class="col-md-6 offset-md-6">
                                            <!-- Search Bar -->
                                            <input type="text" class="form-control" id="searchInput" placeholder="Cari No Dokumen / Perihal / Status Dokumen">
                                        </div>
                                    </div>
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th class="text-center">No Dokumen</th>
                                                <th class="text-center">Perihal</th>
                                                <th class="text-center">Status Dokumen</th>
                                                <th class="text-center">Label Arsip</th>
                                                <th class="text-center">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php while ($row = $result->fetch_assoc()) : ?>
                                                <tr>
                                                    <td class="align-middle text-center"><?= $row['no_dokumen']; ?></td>
                                                    <td class="align-middle text-center"><?= $row['perihal']; ?></td>
                                                    <td class="align-middle text-center"><?= $row['status_dokumen']; ?></td>
                                                    <td class="align-middle text-center"><?= $row['label_arsip']; ?></td>
                                                    <td>
                                                        <button type="button" class="btn btn-primary select-no-dokumen" data-no-dokumen="<?= $row['no_dokumen']; ?>" data-perihal="<?= $row['perihal']; ?>" data-status="<?= $row['status_dokumen']; ?>" data-bs-dismiss="modal">Pilih</button>
                                                    </td>
                                                </tr>
                                            <?php endwhile; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <style>
                        @media (max-width: 991.98px) {
                            .modal-lg {
                                max-width: 100%;
                            }
                        }
                    </style>

                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const selectNoDokumenBtns = document.querySelectorAll('.select-no-dokumen');
                            const noDokumenInput = document.getElementById('no_dokumen');
                            const searchInput = document.getElementById('searchInput');
                            const tableRows = document.querySelectorAll('.table tbody tr');

                            selectNoDokumenBtns.forEach(function(btn) {
                                btn.addEventListener('click', function() {
                                    const selectedNoDokumen = this.dataset.noDokumen;

                                    noDokumenInput.value = selectedNoDokumen;

                                    const modal = document.getElementById('noDokumenModal');
                                    const bootstrapModal = bootstrap.Modal.getInstance(modal);
                                    bootstrapModal.hide();
                                });
                            });

                            searchInput.addEventListener('input', function() {
                                const searchText = this.value.toLowerCase();
                                tableRows.forEach(function(row) {
                                    const noDokumen = row.cells[0].innerText.toLowerCase();
                                    const perihal = row.cells[1].innerText.toLowerCase();
                                    const status = row.cells[2].innerText.toLowerCase();
                                    const label = row.cells[3].innerText.toLowerCase();
                                    const isVisible = noDokumen.includes(searchText) || perihal.includes(searchText) || status.includes(searchText) || label.includes(searchText);
                                    row.style.display = isVisible ? 'table-row' : 'none';
                                });
                            });
                        });
                    </script>

                    <!-- Bagian Status Peminjam -->
                    <div class="row mb-3">
                        <label for="status_peminjam" class="col-sm-2 col-form-label">Status Peminjam</label>
                        <div class="col-sm-10">
                            <select class="form-select" name="status_peminjam" required>
                                <option value="Peminjam Internal">Peminjam Internal</option>
                                <option value="Peminjam Eksternal">Peminjam Eksternal</option>
                            </select>
                            <div class="invalid-feedback">
                                Harap isi Status Peminjam.
                            </div>
                        </div>
                    </div>

                    <!-- Bagian Nama Peminjam -->
                    <div class="row mb-3">
                        <label for="nama_peminjam" class="col-sm-2 col-form-label">Nama Peminjam</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="nama_peminjam" name="nama_peminjam" required>
                            <div class="invalid-feedback">
                                Harap isi Nama Peminjam.
                            </div>
                        </div>
                    </div>

                    <!-- Bagian Tanggal Pinjam -->
                    <div class="row mb-3">
                        <label for="tgl_pinjam" class="col-sm-2 col-form-label">Tanggal Pinjam</label>
                        <div class="col-sm-10">
                            <input type="datetime-local" class="form-control" id="tgl_pinjam" name="tgl_pinjam" required>
                            <div class="invalid-feedback">
                                Harap isi Tanggal Pinjam.
                            </div>
                        </div>
                    </div>

                    <!-- Bagian Tanggal Kembali -->
                    <div class="row mb-3">
                        <label for="tgl_kembali" class="col-sm-2 col-form-label">Tanggal Kembali</label>
                        <div class="col-sm-10">
                            <input type="datetime-local" class="form-control" id="tgl_kembali" name="tgl_kembali">
                            <div class="invalid-feedback">
                                Harap isi Tanggal Kembali.
                            </div>
                        </div>
                    </div>

                    <!-- Bagian keterangan -->
                    <div class="row mb-3">
                        <label for="keterangan" class="col-sm-2 col-form-label">Keterangan</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" name="keterangan" rows="5" cols="30"></textarea>
                            <div class="invalid-feedback">
                                Harap isi Keterangan.
                            </div>
                        </div>
                    </div>

                    <!-- Bagian Button -->
                    <div class="row mb-3">
                        <div class="col-sm-12 justify-content-end d-flex">
                            <button type="submit" name="submit" class="btn btn-primary">Tambah Peminjaman</button>
                        </div>
                    </div>
                </form><!-- End General Form Elements -->
            </div>
        </div>
    </section>
</main><!-- End main -->
