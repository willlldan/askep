<?php

if (isset($_GET['id_peminjaman_dokumen'])) {
    require_once "koneksi.php";
    require_once "utils.php";

    $sql = "SELECT * FROM tbl_peminjaman_dokumen WHERE id_peminjaman_dokumen=" . $_GET['id_peminjaman_dokumen'];
    $result = $mysqli->query($sql);
    $row = $result->fetch_assoc();
}
?>

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Detail Peminjaman Dokumen</h1>
    </div><!-- End Page Title -->
    <br>
    <section class="section dashboard">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Peminjaman Dokumen</h5>

                <!-- General Form Elements -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">
                <!-- Bagian No Dokumen -->
                <div class="row mb-3">
                    <label for="no_dokumen" class="col-sm-2 col-form-label">No Dokumen</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="no_dokumen" name="no_dokumen" required readonly value="<?= $row['no_dokumen']; ?>">
                    </div>
                </div>

                <!-- Bagian Status Peminjam -->
                <div class="row mb-3">
                        <label for="status_peminjam" class="col-sm-2 col-form-label">Status Peminjam</label>
                        <div class="col-sm-10">
                            <select class="form-select" name="status_peminjam" required disabled>
                                <option value="Peminjam Internal" <?php if ($row['status_peminjam'] == 'Peminjam Internal') echo 'selected'; ?>>Peminjam Internal</option>
                                <option value="Peminjam Eksternal" <?php if ($row['status_peminjam'] == 'Peminjam Eksternal') echo 'selected'; ?>>Peminjam Eksternal</option>
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
                            <input type="text" class="form-control" id="nama_peminjam" name="nama_peminjam" required readonly value="<?= $row['nama_peminjam']; ?>">
                            <div class="invalid-feedback">
                                Harap isi Nama Peminjam.
                            </div>
                        </div>
                    </div>

                    <!-- Bagian Tanggal Pinjam -->
                    <div class="row mb-3">
                        <label for="tgl_pinjam" class="col-sm-2 col-form-label">Tanggal Pinjam</label>
                        <div class="col-sm-10">
                            <input type="datetime-local" class="form-control" id="tgl_pinjam" name="tgl_pinjam" required readonly value="<?= $row['tgl_pinjam']; ?>">
                        </div>
                    </div>

                    <!-- Bagian Tanggal Kembali -->
                    <div class="row mb-3">
                        <label for="tgl_kembali" class="col-sm-2 col-form-label">Tanggal Kembali</label>
                        <div class="col-sm-10">
                        <input type="datetime-local" class="form-control" id="tgl_kembali" name="tgl_kembali" required readonly value="<?= $row['tgl_kembali']; ?>">
                        </div>
                    </div>

                    <!-- Bagian Keterengan -->
                    <div class="row mb-3">
                            <label for="keterangan" class="col-sm-2 col-form-label">Keterangan</label>
                            <div class="col-sm-10">
                                <textarea id="keterangan" name="keterangan" class="form-control" rows="5" cols="30" readonly><?= $row['keterangan']; ?></textarea>
                        </div>
                    </div>
                </form><!-- End General Form Elements -->
            </div>
        </div>
    </section>

</main><!-- End #main -->
