<?php
if (isset($_GET['no_dokumen'])) {
    require_once "koneksi.php";
    require_once "utils.php";

    $no_dokumen = $_GET['no_dokumen'];
    $sql = "SELECT * FROM tbl_dok_pendukung WHERE no_dokumen = '$no_dokumen'";
    $result = $mysqli->query($sql);
    $row = $result->fetch_assoc();
} else {
    echo "<script>window.location.href='index.php?page=dokumen_pendukung&item=tampil_dokumen_pendukung';</script>";
}

if (isset($_POST['submit'])) {
    $no_dokumen = $_POST['no_dokumen'];
    $update_dokumen = $_POST['update_dokumen'];
    $status_dokumen = $_POST['status_dokumen'];
    $tgl_masuk_dok = $_POST['tgl_masuk_dok'];
    $tgl_keluar_dok = $_POST['tgl_keluar_dok'];
    $perihal = $_POST['perihal'];
    $tujuan = $_POST['tujuan'];
    $asal_dokumen = $_POST['asal_dokumen'];
    $label_arsip = $_POST['label_arsip'];
    $rak_arsip = $_POST['rak_arsip'];
    $tgl_pinjam = $_POST['tgl_pinjam'];
    $peminjaman = $_POST['peminjaman'];
    $tgl_kembali = $_POST['tgl_kembali'];
    $keterangan = $_POST['keterangan'];
    $file_name = "";

    if (isset($_FILES['file']['name']) && !empty($_FILES['file']['name'])) {
        $target_dir = "dokumen_pendukung/uploads/";
        $file_name = date("YmdHis_") . basename($_FILES["file"]["name"]);
        $target_file = $target_dir . $file_name;
        $uploadOk = 1;
        $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Lakukan validasi ukuran dan tipe file jika perlu
        // ...

        if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
            echo "Dokumen Pendukung berhasil diedit.";
        } else {
            echo "Terjadi kesalahan saat melakukan edit dokumen pendukung.";
        }
    } else {
        $file_name = $row['file']; // Gunakan nilai file yang sudah ada dari database
    }

    // Hapus nama file dari database jika file dihapus
    if (isset($_POST['remove_file']) && $_POST['remove_file'] === '1') {
        $file_name = "";
        // Jika file dihapus, hapus juga file fisik dari server jika ada
        if (!empty($row['file'])) {
            $file_path = "dokumen_pendukung/uploads/" . $row['file'];
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }
    }

    $sql = "UPDATE tbl_dok_pendukung 
            SET 
                no_dokumen='$no_dokumen',
                update_dokumen='$update_dokumen',
                status_dokumen='$status_dokumen',
                tgl_masuk_dok='$tgl_masuk_dok',
                tgl_keluar_dok='$tgl_keluar_dok',
                perihal='$perihal', 
                tujuan='$tujuan', 
                asal_dokumen='$asal_dokumen',
                label_arsip='$label_arsip',
                rak_arsip='$rak_arsip',
                tgl_pinjam='$tgl_pinjam',
                peminjaman='$peminjaman',
                tgl_kembali='$tgl_kembali', 
                keterangan='$keterangan',
                file='$file_name' 
            WHERE 
                no_dokumen='$no_dokumen'";

    if ($mysqli->query($sql) === TRUE) {
        echo "<script>alert('Dokumen Pendukung berhasil diedit.')</script>";
        echo "<script>window.location.href='index.php?page=dokumen_pendukung&item=tampil_dokumen_pendukung';</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $mysqli->error;
    }
}
?>

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Edit Dokumen</h1>
        <!-- <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Home</a></li>
          <li class="breadcrumb-item active">Dashboard</li>
        </ol>
        </nav> -->
    </div><!-- End Page Title -->
    <br>
    <section class="section dashboard">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Dokumen Pendukung</h5>

                <!-- General Form Elements -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

                <!-- Bagian Update Dokumen -->
                <div class="row mb-3">
                    <label for="update_dokumen" class="col-sm-2 col-form-label">Update Dokumen *</label>
                    <div class="col-sm-10">
                        <input type="date" class="form-control" id="update_dokumen" name="update_dokumen" required value="<?= $row['update_dokumen']; ?>">
                    </div>
                </div>

                <!-- Bagian No Dokumen -->    
                <div class="row mb-3">
                    <label for="no_dokumen" class="col-sm-2 col-form-label">No Dokumen *</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="no_dokumen" name="no_dokumen" readonly required value="<?= $row['no_dokumen']; ?>">
                    <div class="invalid-feedback">
                        Harap isi No Dokumen.
                        </div>
                    </div>
                </div>

                <!-- Bagian Status Dokumen -->
                <div class="row mb-3">
                    <label for="status_dokumen" class="col-sm-2 col-form-label">Status Dokumen *</label>
                    <div class="col-sm-10">
                        <select class="form-select" name="status_dokumen" required>
                            <option value="Biasa" <?php if($row['status_dokumen'] == 'Biasa') echo 'selected'; ?>>Biasa</option>
                            <option value="Penting" <?php if($row['status_dokumen'] == 'Penting') echo 'selected'; ?>>Penting</option>
                            <option value="Rahasia" <?php if($row['status_dokumen'] == 'Rahasia') echo 'selected'; ?>>Rahasia</option>
                        </select>
                        <div class="invalid-feedback">
                             Harap isi Status Dokumen.
                        </div>
                    </div>
                </div>

                <!-- Bagian Tanggal Masuk -->
                <div class="row mb-3">
                    <label for="tgl_masuk_dok" class="col-sm-2 col-form-label">Tanggal Dok Masuk *</label>
                    <div class="col-sm-10">
                        <input type="date" class="form-control" id="tgl_lahir" name="tgl_masuk_dok" required value="<?= $row['tgl_masuk_dok']; ?>">
                    <div class="invalid-feedback">
                        Harap isi Tanggal Masuk Dokumen.
                        </div>
                    </div>
                </div>

                <!-- Bagian Tanggal Keluar -->
                <div class="row mb-3">
                    <label for="tgl_keluar_dok" class="col-sm-2 col-form-label">Tanggal Dok Keluar *</label>
                    <div class="col-sm-10">
                        <input type="date" class="form-control" id="tgl_keluar_dok" name="tgl_keluar_dok" value="<?= $row['tgl_keluar_dok']; ?>">
                    <div class="invalid-feedback">
                        Harap isi Tanggal Keluar Dokumen.
                        </div>
                    </div>
                </div>

                <!-- Bagian Perihal -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">
                <div class="row mb-3">
                    <label for="perihal" class="col-sm-2 col-form-label">Perihal *</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="perihal" name="perihal" value="<?= $row['perihal']; ?>">
                    <div class="invalid-feedback">
                            Harap isi Perihal.
                        </div>
                    </div>
                </div>

                <!-- Bagian Tujuan -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">
                <div class="row mb-3">
                    <label for="tujuan" class="col-sm-2 col-form-label">Tujuan *</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="tujuan" name="tujuan" value="<?= $row['tujuan']; ?>">
                    <div class="invalid-feedback">
                            Harap isi Tujuan.
                        </div>
                    </div>
                </div>

                <!-- Bagian Asal Dokumen -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">
                <div class="row mb-3">
                    <label for="asal_dokumen" class="col-sm-2 col-form-label">Asal Dokumen *</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="asal_dokumen" name="asal_dokumen" value="<?= $row['asal_dokumen']; ?>">
                    <div class="invalid-feedback">
                            Harap isi Asal Dokumen.
                        </div>
                    </div>
                </div>

                <!-- Bagian Label Arsip -->
                <div class="row mb-3">
                        <label for="label_arsip" class="col-sm-2 col-form-label">Label Arsip *</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="label_arsip" value="<?= isset($row['label_arsip']) ? htmlspecialchars($row['label_arsip']) : ''; ?>" required>
                            <div class="invalid-feedback">
                                Harap isi Label Arsip.
                            </div>
                        </div>
                    </div>

                    <!-- Bagian Rak Arsip -->
                    <div class="row mb-3">
                        <label for="rak_arsip" class="col-sm-2 col-form-label">Rak Arsip *</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="rak_arsip" name="rak_arsip" value="<?= isset($row['rak_arsip']) ? htmlspecialchars($row['rak_arsip']) : ''; ?>" required>
                            <small class="form-text" style="color: red;"> * Contoh Pengetikan Rak Arsip: A-JAN-001</small>
                            <small class="form-text" style="color: red;"> <br> A : Kode Laci <br> JAN : Kode Bulan Dokumen <br> 001 : Urutan Dokumen</small>
                            <div class="invalid-feedback">
                                Harap isi Rak Arsip dengan format yang benar (contoh: AJAN001).
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

                    <!-- Bagian Peminjaman -->
                     <div class="row mb-3">
                        <label for="peminjaman" class="col-sm-2 col-form-label">Peminjaman</label>
                        <div class="col-sm-10">
                            <select class="form-select" name="peminjaman" required>
                                <option value="Tidak Dipinjam" <?= ($row['peminjaman'] === 'Tidak Dipinjam') ? 'selected' : ''; ?>>Tidak Dipinjam</option>
                                <option value="Dipinjam-Kembali" <?= ($row['peminjaman'] === 'Dipinjam-Kembali') ? 'selected' : ''; ?>>Dipinjam-Kembali</option>
                                <option value="Dipinjam-Tidak Kembali" <?= ($row['peminjaman'] === 'Dipinjam-Tidak Kembali') ? 'selected' : ''; ?>>Dipinjam-Tidak Kembali</option>
                            </select>
                            <div class="invalid-feedback">
                                Harap isi Peminjaman.
                            </div>
                        </div>
                    </div> 

                    <!-- Bagian Tanggal Kembali -->
                    <div class="row mb-3">
                        <label for="tgl_kembali" class="col-sm-2 col-form-label">Tanggal Kembali</label>
                        <div class="col-sm-10">
                            <?php if (!empty($row['tgl_kembali'])) : ?>
                                <input type="datetime-local" class="form-control" id="tgl_kembali" name="tgl_kembali" value="<?= $row['tgl_kembali']; ?>">
                            <?php else : ?>
                                <input type="datetime-local" class="form-control" id="tgl_kembali" name="tgl_kembali">
                            <?php endif; ?>
                        </div>
                    </div>

                <!-- Bagian Keterangan -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">
                <div class="row mb-3">
                        <label for="keterangan" class="col-sm-2 col-form-label">Keterangan</label>
                        <div class="col-sm-10">
                            <textarea id="keterangan" name="keterangan" class="form-control" rows="5" cols="30"><?= $row['keterangan']; ?></textarea>
                            <div class="invalid-feedback">
                                Harap isi Keterangan.
                            </div>
                        </div>
                    </div>
               
                <!-- Bagian File -->
                <!-- Contoh menggunakan Font Awesome -->
                <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">

                <div class="row mb-3">
                    <label for="file" class="col-sm-2 col-form-label">File</label>
                    <div class="col-sm-10">
                        <?php if (!empty($row['file'])) : ?>
                            <p><a href="dokumen_pendukung/uploads/<?php echo $row['file']; ?>" target="_blank"><?php echo $row['file']; ?></a>
                                <span class="file-remove-icon" data-file-name="<?php echo $row['file']; ?>"><i class="fas fa-times-circle"></i></span></p>
                            <input type="hidden" name="file_name" value="<?php echo $row['file']; ?>">
                            <input type="file" class="form-control" name="new_file" id="new_file">
                        <?php else : ?>
                            <input type="file" class="form-control" name="file" id="file">
                            <br>
                            <small>* Biarkan kosong jika tidak ingin mengunggah file</small>
                        <?php endif; ?>
                        <!-- Tambahkan input hidden untuk menandai file yang ingin dihapus -->
                        <?php if (!empty($row['file'])) : ?>
                            <input type="hidden" name="remove_file" id="remove_file" value="0">
                        <?php endif; ?>
                    </div>
                </div>

                <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        // Tambahkan event listener untuk setiap ikon "Remove"
                        var removeIcons = document.querySelectorAll(".file-remove-icon");
                        removeIcons.forEach(function(icon) {
                            icon.addEventListener("click", function() {
                                var fileName = this.getAttribute("data-file-name");
                                removeFile(fileName);
                            });
                        });

                        function removeFile(fileName) {
                            if (confirm('Apakah Anda yakin ingin menghapus file ini?')) {
                                // Tandai bahwa file ingin dihapus dengan mengubah nilai pada input hidden
                                document.getElementById("remove_file").value = "1";

                                // Sembunyikan tautan dan ikon "Remove"
                                var fileLink = document.querySelector('a[href="dokumen_pendukung/uploads/' + fileName + '"]');
                                var removeIcon = document.querySelector('.file-remove-icon[data-file-name="' + fileName + '"]');

                                fileLink.parentElement.style.display = "none";
                                removeIcon.style.display = "none";
                            }
                        }
                    });
                </script>

                <!-- Bagian Button -->
                <div class="row mb-3">
                    <div class="col-sm-12 justify-content-end d-flex">
                        <button type="submit" name="submit" class="btn btn-primary">Edit Dokumen</button>
                    </div>
                </div>
                </form><!-- End General Form Elements -->
            </div>
        </div>
</section>
</main><!-- End #main -->