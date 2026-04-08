<?php
require_once "koneksi.php";
require_once "utils.php";

if (isset($_POST['submit'])) {
    $nrp_nip = $_POST['nrp_nip'];
    $status_peminjam = $_POST['status_peminjam'];
    $nama_peminjam = $_POST['nama_peminjam'];
    $tgl_pinjam = $_POST['tgl_pinjam'];
    $tgl_kembali = $_POST['tgl_kembali'];
    $status_peminjaman = $_POST['status_peminjaman']; 
    $keterangan = $_POST['keterangan'];
    
    $sql = "INSERT INTO tbl_peminjaman_dok_personel (
                nrp_nip,
                status_peminjam, 
                nama_peminjam, 
                tgl_pinjam,
                tgl_kembali, 
                status_peminjaman,
                keterangan      
            ) VALUES (
                '$nrp_nip',
                '$status_peminjam',  
                '$nama_peminjam', 
                '$tgl_pinjam', 
                '$tgl_kembali', 
                '$status_peminjaman',
                '$keterangan'
            )";  

    if ($mysqli->query($sql) === TRUE) {
        echo "<script>alert('Peminjaman Dokumen Personel berhasil ditambah.')</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $mysqli->error;
    }
}

$sql = "SELECT nrp_nip, nama, pangkat_golongan, kesatuan, label_arsip FROM tbl_dok_personel ORDER BY nrp_nip DESC"; // Perbaikan query SQL di sini
$result = $mysqli->query($sql);
?>

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Tambah Peminjaman Dokumen Personel</h1>
    </div>
    <br>
    <section class="section dashboard">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Peminjaman Dokumen Personel</h5>

                <!-- General Form Elements -->
                <form class="needs-validation" novalidate action="" method="POST">
                    <!-- Bagian NRP / NIP -->
                    <div class="row mb-3">
                        <label for="nrp_nip" class="col-sm-2 col-form-label">NRP / NIP</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="nrp_nip" name="nrp_nip" readonly>
                        </div>
                        <div class="col-sm-2">
                            <!-- Button trigger modal -->
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#nrpNipModal">
                                Pilih NRP / NIP
                            </button>
                        </div>
                    </div>  

                    <!-- Modal -->
                    <div class="modal fade" id="nrpNipModal" tabindex="-1" aria-labelledby="nrpNipModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-scrollable modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                    <div class="mb-3">
                    <div class="col-md-6 offset-md-6">
                     <!-- Search Bar -->
                    <input type="text" class="form-control" id="searchInput" placeholder="Cari NRP / NIP, Nama, atau Pangkat / Golongan">
                    </div>
                    </div>
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="text-center">NRP / NIP</th>
                                <th class="text-center">Nama</th>
                                <th class="text-center">Pangkat / Golongan</th>
                                <th class="text-center">Label Arsip</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()) : ?>
                                <tr class="select-nrp-nip" data-nrp-nip="<?= $row['nrp_nip']; ?>" data-jenis-dokumen="Dokumen Personel">
                                    <td class="align-middle text-center"><?= $row['nrp_nip']; ?></td>
                                    <td class="align-middle text-center"><?= $row['nama']; ?></td>
                                    <td class="align-middle text-center"><?= $row['pangkat_golongan']; ?></td>
                                    <td class="align-middle text-center"><?= $row['label_arsip']; ?></td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-primary select-personel" data-nrp-nip="<?= $row['nrp_nip']; ?>" data-nama="<?= $row['nama']; ?>" data-pangkat="<?= $row['pangkat_golongan']; ?>">
                                            Pilih
                                        </button>
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
        const selectNrpNipBtns = document.querySelectorAll('.select-nrp-nip');
        const nrpNipInput = document.getElementById('nrp_nip');
        const searchInput = document.getElementById('searchInput');
        const tableRows = document.querySelectorAll('.table tbody tr');

        selectNrpNipBtns.forEach(function(btn) {
            btn.addEventListener('click', function() {
                const selectedNrpNip = this.dataset.nrpNip;

                nrpNipInput.value = selectedNrpNip;

                const modal = document.getElementById('nrpNipModal');
                const bootstrapModal = bootstrap.Modal.getInstance(modal);
                bootstrapModal.hide();
            });
        });

        searchInput.addEventListener('input', function() {
            const searchText = this.value.toLowerCase();
            tableRows.forEach(function(row) {
                const nrpNip = row.cells[0].innerText.toLowerCase();
                const nama = row.cells[1].innerText.toLowerCase();
                const pangkat = row.cells[2].innerText.toLowerCase();
                const label = row.cells[3].innerText.toLowerCase(); // Ganti ini dengan indeks sel yang benar
                const isVisible = nrpNip.includes(searchText) || nama.includes(searchText) || pangkat.includes(searchText) || label.includes(searchText);
                row.style.display = isVisible ? 'table-row' : 'none';
            });
        });

        const selectPersonelBtns = document.querySelectorAll('.select-personel');
        selectPersonelBtns.forEach(function(btn) {
            btn.addEventListener('click', function() {
                const selectedNrpNip = this.dataset.nrpNip;
                const selectedNama = this.dataset.nama;
                const selectedPangkat = this.dataset.pangkat;
                const selectedLabel = this.dataset.label;

                const modal = document.getElementById('nrpNipModal');
                const bootstrapModal = bootstrap.Modal.getInstance(modal);
                bootstrapModal.hide();
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

                    <!-- Bagian Status Peminjaman -->
                    <div class="row mb-3">
                        <label for="status_peminjaman" class="col-sm-2 col-form-label">Status Peminjaman</label>
                        <div class="col-sm-10">
                            <select class="form-select" name="status_peminjaman" required>
                                <option value="Dipinjam-Kembali">Dipinjam-Kembali</option>
                                <option value="Dipinjam-Tidak Kembali">Dipinjam-Tidak Kembali</option>
                            </select>
                            <div class="invalid-feedback">
                                Harap isi Status Peminjaman.
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
