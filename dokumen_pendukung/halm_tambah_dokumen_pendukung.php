<?php
require_once "koneksi.php";
require_once "utils.php";
if (isset($_POST['submit'])) {
    $no_dokumen = $_POST['no_dokumen']; 
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
            echo "Terjadi kesalahan saat melakukan edit dokumen masuk.";
        }
    }

    $sql = "INSERT INTO tbl_dok_pendukung (
            no_dokumen,                        
            status_dokumen,       
            tgl_masuk_dok,
            tgl_keluar_dok,             
            perihal,
            tujuan,
            asal_dokumen,
            label_arsip,      
            rak_arsip,          
            tgl_pinjam,
            peminjaman,
            tgl_kembali,
            keterangan,
            file 
                    
            ) VALUES (
            '$no_dokumen',             
            '$status_dokumen',   
            '$tgl_masuk_dok',
            '$tgl_keluar_dok',           
            '$perihal',
            '$tujuan',
            '$asal_dokumen',
            '$label_arsip',
            '$rak_arsip',            
            '$tgl_pinjam',
            '$peminjaman',
            '$tgl_kembali',
            '$keterangan',
            '$file_name'
            )";  
                
    if ($mysqli->query($sql) === TRUE) {
        echo "<script>alert('Dokumen Pendukung berhasil ditambah.')</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $mysqli->error;
    }
}
?>

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Tambah Dokumen</h1>
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
                
                <!-- Bagian No Dokumen -->
                <div class="row mb-3">
                    <label for="no_dokumen" class="col-sm-2 col-form-label">No Dokumen *</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="no_dokumen" required>
                        <div class="invalid-feedback">
                            Harap isi No Dokumen Masuk.
                        </div>
                    </div>
                </div>

                <!-- Bagian Status Dokumen -->
                <div class="row mb-3">
                    <label for="status_dokumen" class="col-sm-2 col-form-label">Status Dokumen *</label>
                    <div class="col-sm-10">
                        <select class="form-select" name="status_dokumen" required>
                            <option value="Biasa">Biasa</option>
                            <option value="Penting">Penting</option>
                            <option value="Rahasia">Rahasia</option>
                        </select>
                        <div class="invalid-feedback">
                            Harap isi Status Dokumen.
                        </div>
                    </div>
                </div>

                <!-- Bagian Tanggal Masuk -->
                <div class="row mb-3">
                    <label for="tgl_masuk_dok" class="col-sm-2 col-form-label">Tanggal Dokumen *</label>
                    <div class="col-sm-10">
                        <input type="date" class="form-control" id="tgl_masuk_dok" name="tgl_masuk_dok">
                        <div class="invalid-feedback">
                            Harap isi Tanggal Masuk Dokumen.
                        </div>
                    </div>
                </div>

                <!-- Bagian Tanggal Keluar -->
                <div class="row mb-3">
                    <label for="tgl_keluar_dok" class="col-sm-2 col-form-label">Tanggal keluar *</label>
                    <div class="col-sm-10">
                        <input type="date" class="form-control" id="tgl_keluar_dok" name="tgl_keluar_dok">
                        <div class="invalid-feedback">
                            Harap isi Tanggal Keluar Dokumen.
                        </div>
                    </div>
                </div>

                <!-- Bagian Perihal -->
                <div class="row mb-3">
                    <label for="perihal" class="col-sm-2 col-form-label">Perihal *</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="perihal" required>
                        <div class="invalid-feedback">
                            Harap isi Perihal.
                        </div>
                    </div>
                </div>

                <!-- Bagian Tujuan -->
                <div class="row mb-3">
                    <label for="tujuan" class="col-sm-2 col-form-label">Tujuan *</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="tujuan">
                        <div class="invalid-feedback">
                            Harap isi Tujuan.
                        </div>
                    </div>
                </div>

                <!-- Bagian Asal Dokumen -->
                <div class="row mb-3">
                    <label for="asal_dokumen" class="col-sm-2 col-form-label">Asal Dokumen *</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="asal_dokumen">
                        <div class="invalid-feedback">
                            Harap isi Asal Dokumen.
                        </div>
                    </div>
                </div>

                <!-- Bagian Label Arsip -->
                <div class="row mb-3">
                    <label for="label_arsip" class="col-sm-2 col-form-label">Label Arsip *</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="label_arsip" required>
                        <div class="invalid-feedback">
                            Harap isi Label Arsip.
                        </div>
                    </div>
                </div>
                
                <!-- Bagian Rak Arsip -->
                <div class="row mb-3">
                    <label for="rak_arsip" class="col-sm-2 col-form-label">Rak Arsip *</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="rak_arsip" name="rak_arsip" required>
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
                        <input type="datetime-local" class="form-control" id="tgl_pinjam" name="tgl_pinjam">
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
                                <option value="Tidak Dipinjam">Tidak Dipinjam</option>
                                <option value="Dipinjam-Kembali">Dipinjam-Kembali</option>
                                <option value="Dipinjam-Tidak Kembali">Dipinjam-Tidak Kembali</option>
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
                        <input type="datetime-local" class="form-control" id="tgl_kembali" name="tgl_kembali">
                        <div class="invalid-feedback">
                            Harap isi Tanggal Kembali.
                        </div>
                    </div>
                </div>

                <!-- Bagian Keterangan -->
                <div class="row mb-3">
                    <label for="keterangan" class="col-sm-2 col-form-label">Keterangan</label>
                    <div class="col-sm-10">
                        <textarea name="keterangan" class="form-control" rows="5" cols="30"></textarea>
                        <div class="invalid-feedback">
                            Harap isi Keterangan.
                        </div>
                    </div>
                </div> 

                <!-- Bagian File -->
                <div class="row mb-3">
                    <label for="file" class="col-sm-2 col-form-label">File</label>
                    <div class="col-sm-10">
                        <input type="file" class="form-control" name="file" id="file">
                        <br>
                        <small>* Biarkan kosong jika tidak ingin mengunggah file</small>
                        <br>
                        <small>* Yang memiliki simbol bintang wajib diisi</small>
                    </div>
                </div>

                <!-- Bagian Button -->
                <div class="row mb-3">
                    <div class="col-sm-12 justify-content-end d-flex">
                        <button type="submit" name="submit" class="btn btn-primary">Tambah Dokumen</button>
                    </div>
                </div>
                </form><!-- End General Form Elements -->
            </div>
        </div>
    </section>
</main>
</html>

