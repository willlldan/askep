<?php
if (isset($_GET['no_dokumen'])) {
    require_once "koneksi.php";
    require_once "utils.php";

    $no_dokumen = $_GET['no_dokumen'];
    $sql = "SELECT * FROM tbl_dok_masuk WHERE no_dokumen = '$no_dokumen'";
    $result = $mysqli->query($sql);
    $row = $result->fetch_assoc();
} 
?>

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Detail Dokumen</h1>
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
                <h5 class="card-title">Dokumen Masuk</h5>

                <!-- General Form Elements -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">
                
                <!-- Bagian Update Dokumen -->
                <div class="row mb-3">
                    <label for="update_dokumen" class="col-sm-2 col-form-label">Update Dokumen *</label>
                    <div class="col-sm-10">
                        <input type="date" class="form-control" id="update_dokumen" name="update_dokumen" required readonly value="<?= $row['update_dokumen']; ?>">
                        
                    </div>
                </div>
                
                <!-- Bagian No Dokumen -->    
                    <div class="row mb-3">
                        <label for="no_dokumen" class="col-sm-2 col-form-label">No Dokumen *</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="no_dokumen" name="no_dokumen" required readonly value="<?= $row['no_dokumen']; ?>">
                        </div>
                    </div>

                    <!-- Bagian Status Dokumen -->
                    <div class="row mb-3">
                        <label for="status_dokumen" class="col-sm-2 col-form-label">Status Dokumen *</label>
                        <div class="col-sm-10">
                            <select class="form-select" name="status_dokumen" required disabled>
                                <option value="Biasa" <?= ($row['status_dokumen'] == 'Biasa') ? 'selected' : ''; ?>>Biasa</option>
                                <option value="Penting" <?= ($row['status_dokumen'] == 'Penting') ? 'selected' : ''; ?>>Penting</option>
                                <option value="Rahasia" <?= ($row['status_dokumen'] == 'Rahasia') ? 'selected' : ''; ?>>Rahasia</option>
                            </select>
                        </div>
                    </div>

                     <!-- Bagian Status Tindakan -->
                     <div class="row mb-3">
                        <label for="status_tindakan" class="col-sm-2 col-form-label">Status Tindakan *</label>
                        <div class="col-sm-10">
                            <select class="form-select" name="status_tindakan" required disabled>
                                <option value="Proses" <?= ($row['status_tindakan'] == 'Proses') ? 'selected' : ''; ?>>Proses</option>
                                <option value="Selesai" <?= ($row['status_tindakan'] == 'Selesai') ? 'selected' : ''; ?>>Selesai</option>
                                <option value="Arsip" <?= ($row['status_tindakan'] == 'Arsip') ? 'selected' : ''; ?>>Arsip</option>
                            </select>
                        </div>
                    </div>

                    <!-- Bagian Tanggal Masuk -->
                    <div class="row mb-3">
                        <label for="tgl_masuk_dok" class="col-sm-2 col-form-label">Tanggal Dokumen *</label>
                        <div class="col-sm-10">
                            <input type="date" class="form-control" id="tgl_masuk_dok" name="tgl_masuk_dok" required readonly value="<?= $row['tgl_masuk_dok']; ?>">
                        </div>
                    </div>

                    <!-- Bagian Tanggal Terima -->
                    <div class="row mb-3">
                        <label for="tgl_terima_dok" class="col-sm-2 col-form-label">Tanggal Terima *</label>
                        <div class="col-sm-10">
                            <input type="date" class="form-control" id="tgl_terima_dok" name="tgl_terima_dok" required readonly value="<?= $row['tgl_terima_dok']; ?>">
                        </div>
                    </div>

                    <!-- Bagian Asal Dokumen -->
                    <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">
                    <div class="row mb-3">
                        <label for="asal_dokumen" class="col-sm-2 col-form-label">Asal Dokumen *</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="asal_dokumen" name="asal_dokumen" required readonly value="<?= $row['asal_dokumen']; ?>">
                        </div>
                    </div>

                    <!-- Bagian Perihal -->
                    <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">
                    <div class="row mb-3">
                        <label for="perihal" class="col-sm-2 col-form-label">Perihal *</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="perihal" name="perihal" required readonly value="<?= $row['perihal']; ?>">
                        </div>
                    </div>

                    <!-- Bagian Label Arsip -->
                    <div class="row mb-3">
                        <label for="label_arsip" class="col-sm-2 col-form-label">Label Arsip *</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="label_arsip" value="<?= isset($row['label_arsip']) ? htmlspecialchars($row['label_arsip']) : ''; ?>" readonly>
                            <div class="invalid-feedback">
                                Harap isi Label Arsip.
                            </div>
                        </div>
                    </div>

                    <!-- Bagian Rak Arsip -->
                    <div class="row mb-3">
                        <label for="rak_arsip" class="col-sm-2 col-form-label">Rak Arsip *</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="rak_arsip" name="rak_arsip" value="<?= isset($row['rak_arsip']) ? htmlspecialchars($row['rak_arsip']) : ''; ?>" readonly>
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
                            <input type="datetime-local" class="form-control" id="tgl_pinjam" name="tgl_pinjam" required readonly value="<?= $row['tgl_pinjam']; ?>">
                        </div>
                    </div>                   

                    <!-- Bagian Peminjaman -->
                    <div class="row mb-3">
                        <label for="peminjaman" class="col-sm-2 col-form-label">Peminjaman</label>
                        <div class="col-sm-10">
                            <select class="form-select" name="peminjaman" disabled>
                                <option value="Tidak Dipinjam" <?php if ($row['peminjaman'] == 'Tidak Dipinjam') echo 'selected'; ?>>Tidak Dipinjam</option>
                                <option value="Dipinjam-Kembali" <?php if ($row['peminjaman'] == 'Dipinjam-Kembali') echo 'selected'; ?>>Dipinjam-Kembali</option>
                                <option value="Dipinjam-Tidak Kembali" <?php if ($row['peminjaman'] == 'Dipinjam-Tidak Kembali') echo 'selected'; ?>>Dipinjam-Tidak Kembali</option>
                            </select>
                        </div>
                    </div>

                    <!-- Bagian Tanggal Kembali -->
                    <div class="row mb-3">
                        <label for="tgl_kembali" class="col-sm-2 col-form-label">Tanggal Kembali</label>
                        <div class="col-sm-10">
                        <input type="datetime-local" class="form-control" id="tgl_kembali" name="tgl_kembali" required readonly value="<?= $row['tgl_kembali']; ?>">
                        </div>
                    </div>
                    
                    <!-- Bagian Keterangan -->
                    <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">
                        <div class="row mb-3">
                            <label for="keterangan" class="col-sm-2 col-form-label">Keterangan</label>
                            <div class="col-sm-10">
                                <textarea id="keterangan" name="keterangan" class="form-control" rows="5" cols="30" readonly><?= $row['keterangan']; ?></textarea>
                            </div>
                        </div>
                    </form>
 
                    <!-- Bagian File -->
                    <div class="row mb-3">
                        <label for="file" class="col-sm-2 col-form-label">File</label>
                        <div class="col-sm-10">
                            <?php if (!empty($row['file'])) : ?>
                                <p><a href="dokumen_masuk/uploads/<?php echo $row['file']; ?>" target="_blank"><?php echo $row['file']; ?></a></p>
                                <input type="hidden" name="file_name" value="<?php echo $row['file']; ?>">
                            <?php else : ?>
                                <?php if (isset($isEditable) && $isEditable) : ?>
                                    <input type="file" class="form-control" name="file" id="file">
                                <?php else : ?>
                                    <input type="text" class="form-control" value="Tidak ada file" readonly>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>

                </form><!-- End General Form Elements -->
            </div>
        </div>
    </section>
</main><!-- End #main -->
