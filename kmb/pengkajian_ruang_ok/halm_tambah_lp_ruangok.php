<?php
require_once "koneksi.php";
require_once "utils.php";

if (isset($_POST['submit'])) {
    $no_dokumen = $_POST['no_dokumen']; 
    $status_dokumen = $_POST['status_dokumen'];
    $tgl_keluar_dok = $_POST['tgl_keluar_dok'];
    $perihal = $_POST['perihal'];
    $tujuan = $_POST['tujuan'];
    $label_arsip = $_POST['label_arsip'];
    $rak_arsip = $_POST['rak_arsip'];    
    $tgl_pinjam = $_POST['tgl_pinjam'];
    $peminjaman = $_POST['peminjaman'];
    $tgl_kembali = $_POST['tgl_kembali'];
    $keterangan = $_POST['keterangan'];
    $file_name = "";

    if (isset($_FILES['file']['name']) && !empty($_FILES['file']['name'])) {
        $target_dir = "maternitas/uploads/";
        $file_name = date("YmdHis_") . basename($_FILES["file"]["name"]);
        $target_file = $target_dir . $file_name;
        $uploadOk = 1;
        $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Lakukan validasi ukuran dan tipe file jika perlu
        // ...

        if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
            echo "Data maternitas berhasil ditambah.";
        } else {
            echo "Terjadi kesalahan saat melakukan tambah data maternitas.";
        }
    }

    $sql = "INSERT INTO tbl_dok_keluar (
            no_dokumen,                        
            status_dokumen,       
            tgl_keluar_dok,             
            perihal,
            tujuan,
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
            '$tgl_keluar_dok',           
            '$perihal',
            '$tujuan',
            '$label_arsip',
            '$rak_arsip',            
            '$tgl_pinjam',
            '$peminjaman',
            '$tgl_kembali',
            '$keterangan',
            '$file_name'
            )";  
                
    if ($mysqli->query($sql) === TRUE) {
        echo "<script>alert('Dokumen Keluar berhasil ditambah.')</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $mysqli->error;
    }
}

?>

<main id="main" class="main">

             <?php include "kmb/pengkajian_ruang_ok/tab.php"; ?>


                      
    <section class="section dashboard">
        <div class="card">
            <div class="card-body">
            
                <!-- General Form Elements -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">
  <div class="row mb-3 mt-3">
    <label class="col-sm-2 col-form-label"><strong>Tanggal Pengkajian</strong></label>
                        <div class="col-sm-9">
                            <input type="date" class="form-control" name="tglpengkajian">
                               
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>RS/Ruangan</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="rsruangan">
                                
                        </div>
                    </div>
                <h5 class="card-title"><strong>A.	KONSEP DASAR KAMAR BEDAH</strong></h5>

               
             
                
                    <!-- Bagian Inisial Pasien -->

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>1.	Pengertian Kamar Operasi</strong></label>

                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="pengertian_kamar_operasi">

                          
                         </div>
                    </div>

                <!-- Bagian Usia -->
                <div class="row mb-3">
                    <label for="usiaistri" class="col-sm-2 col-form-label"><strong>2.	Pembagian Ruangan Kamar Operasi</strong></label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="ruang_kamar_operasi">

                     
                    </div>

                <!-- Bagian Pekerjaan -->
                <div class="row mb-3">
                    <label for="pekerjaanistri" class="col-sm-2 col-form-label "><strong>3.	Bagian-Bagian Kamar Operasi</strong></label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="kamar_operasi">

                        
                         </div>
                    </div>

                <!-- Bagian Pendidikan Terakhir -->
                <div class="row mb-3">
                    <label for="pendidikanterakhiristri" class="col-sm-2 col-form-label"><strong>4.	Persyaratan Kamar Operasi</strong></label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="persyaratan">

                        
                         </div>
                    </div>
                    </form>
                    </form>
                    </div>
                    </div>
 <div class="card">
            <div class="card-body">
             
              <h5 class="card-title mb-1"><strong>B.	TATA CARA KERJA DAN PENGELOLAAN KAMAR OPERASI</strong></h5>
               
                <div class="row mb-3">
                    <label for="agamaistri" class="col-sm-2 col-form-label"><strong>Tata Cara Kerja Dan Pengelolaan Kamar Operasi</strong></label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="tata_cara">

                         </div>
                    </div>
                    </div>
                    </div>
  <div class="card">
            <div class="card-body">
             
              <h5 class="card-title mb-1">
                            <strong> C.	DENAH RUANGAN KAMAR OPERASI</strong></h5>
                <div class="row mb-3">
                    <label for="sukubangsa" class="col-sm-2 col-form-label"><strong>Denah Ruangan Kamar Operasi</strong></label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="denah">
                        
                       
                         </div>
                    </div>
                    </div>
                    </div>
 <div class="card">
            <div class="card-body">
             
              <h5 class="card-title mb-1">
                            <strong> D.	DAFTAR PUSTAKA</strong></h5>
                <div class="row mb-3">
                    <label for="statusperkawinan" class="col-sm-2 col-form-label"><strong>Daftar Pustaka</strong></label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="daftar_pustaka">
                        
                       
                         </div>
                    </div>
                </form>
            </form>
    <?php include "tab_navigasi.php"; ?> </div>
                       
    </section>

               