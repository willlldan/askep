<?php

if (isset($_GET['id_peminjaman_dokumen'])) {
    require_once "koneksi.php";
    require_once "utils.php";

    $sql = "SELECT * FROM tbl_peminjaman_dokumen WHERE id_peminjaman_dokumen=" . $_GET['id_peminjaman_dokumen'];
    $result = $mysqli->query($sql);
    $row = $result->fetch_assoc();
} else {
    echo "<script>" .
        "window.location.href='index.php?page=peminjam_dokumen&item=tampil_dok';" .
        "</script>";
}

if (isset($_POST['submit'])) {
    $no_dokumen = $_POST['no_dokumen'];
    $nama_peminjam = $_POST['nama_peminjam'];
    $status_peminjam = $_POST['status_peminjam'];
    $tgl_pinjam = $_POST['tgl_pinjam'];
    $tgl_kembali = $_POST['tgl_kembali'];
    $keterangan = $_POST['keterangan'];

    $sql = "UPDATE tbl_peminjaman_dokumen
            SET 
                no_dokumen='$no_dokumen',
                nama_peminjam='$nama_peminjam',
                status_peminjam='$status_peminjam',
                tgl_pinjam='$tgl_pinjam', 
                tgl_kembali='$tgl_kembali',  
                keterangan='$keterangan' 
            WHERE 
                id_peminjaman_dokumen=" . $_GET['id_peminjaman_dokumen'];
    if ($mysqli->query($sql) === TRUE) {
        echo "<script>alert('Edit Peminjaman Dokumen berhasil.')</script>";
        echo "<script>" .
            "window.location.href='index.php?page=peminjam_dokumen&item=tampil_dok';" .
            "</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $mysqli->error;
    }
}

?>

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Edit Peminjaman Dokumen</h1>
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
                            <input type="text" class="form-control" id="no_dokumen" name="no_dokumen" readonly value="<?= isset($_POST['no_dokumen']) ? htmlspecialchars($_POST['no_dokumen']) : (isset($row['no_dokumen']) ? htmlspecialchars($row['no_dokumen']) : ''); ?>">
                        </div>
                    </div>

                    <!-- Bagian Status Peminjam -->
                    <div class="row mb-3">
                        <label for="status_peminjam" class="col-sm-2 col-form-label">Status Peminjam</label>
                        <div class="col-sm-10">
                            <select class="form-select" name="status_peminjam" required>
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
                            <input type="text" class="form-control" id="nama_peminjam" name="nama_peminjam" required value="<?= $row['nama_peminjam']; ?>">
                            <div class="invalid-feedback">
                                Harap isi Nama Peminjam.
                            </div>
                        </div>
                    </div>

                    <!-- Bagian Tanggal Pinjam -->
                    <div class="row mb-3">
                        <label for="tgl_pinjam" class="col-sm-2 col-form-label">Tanggal Pinjam</label>
                        <div class="col-sm-10">
                            <input type="datetime-local" class="form-control" id="tgl_pinjam" name="tgl_pinjam" value="<?= $row['tgl_pinjam']; ?>">
                            <div class="invalid-feedback">
                                Harap isi Tanggal Pinjam.
                            </div>
                        </div>
                    </div>

                    <!-- Bagian Tanggal Kembali -->
                    <div class="row mb-3">
                        <label for="tgl_kembali" class="col-sm-2 col-form-label">Tanggal Kembali</label>
                        <div class="col-sm-10">
                            <input type="datetime-local" class="form-control" id="tgl_kembali" name="tgl_kembali" value="<?= $row['tgl_kembali']; ?>">
                            <div class="invalid-feedback">
                                Harap isi Tanggal Kembali.
                            </div>
                        </div>
                    </div>
                
                    <!-- Bagian Keterangan -->
                    <div class="row mb-3">
                        <label for="keterangan" class="col-sm-2 col-form-label">Keterangan</label>
                        <div class="col-sm-10">
                            <textarea id="keterangan" name="keterangan" class="form-control" rows="5" cols="30"><?= isset($row['keterangan']) ? $row['keterangan'] : ''; ?></textarea>
                        </div>
                    </div>

                    <!-- Bagian Button -->
                    <div class="row mb-3">
                        <div class="col-sm-12 justify-content-end d-flex">
                            <button type="submit" name="submit" class="btn btn-primary">Edit Peminjaman</button>
                        </div>
                    </div>
                </form><!-- End General Form Elements -->
            </div>
        </div>
    </section>
</main><!-- End main -->
