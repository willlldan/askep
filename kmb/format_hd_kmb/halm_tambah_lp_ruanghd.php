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


    <section class="section dashboard">
        <?php include "kmb/format_hd_kmb/tab.php"; ?>
       
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

                    <h5 class="card-title"><strong>A.  Konsep Dasar Penyakit (Chronic Kidney Disease (CKD))</strong></h5>
                                 <!-- A KONSEP DASAR MEDIS -->

                            <div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>1. Definisi</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="definisi">
        </div>
       
</div>

                    <div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>2. Klasifikasi</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="klasifikasi">
</div>
</div>

<div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>3. Etiologi</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="etiologi">
  
        </div>
        </div>

<div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>4. Manifestasi Klinik</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="manifestasi_klinik">
    
         </div>
        </div>

<div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>5. Patofisiologi</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="patofisiologi">
   
         </div>
        </div>

<div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>6. Pemeriksaan penunjang</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="pemeriksaan_penunjang">
   
         </div>
        </div>

<div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>7. Penatalaksanaan</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="penatalaksanaan">
     
         </div>
        </div>

<div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>8. Komplikasi</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="komplikasi">
     
         </div>
        </div>
  
        </div>
        </div>
        </div>
</div>
</section>
        
        
                <div class="card">
             <div class="card-body">
                    <div class="row mb-2">
                        <label class="col-sm-4 col-form-label text-primary">
                            <strong>B.  Konsep Dasar Hemodialisa</strong>
                    </div>
<div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>1. Pengertian</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="pengertian">
        
         </div>
        </div>
            
<div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>2. Tujuan</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="tujuan">
      
         </div>
        </div>
          
<div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>3. Proses Hemodialisa</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="proses_hemodialisa">
       
         </div>
        </div>
          
<div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>4. Alasan dilakukan Hemodialisa</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="alasan_hemodialisa">
       
         </div>
        </div>
<div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>5. Indikasi Hemodialisa</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="indikasi_hemodialisa">
      
         </div>
        </div>
<div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>6. Kontraindikasi Hemodialisa</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="kontraindikasi_hemodialisa">
       
          </div>
        </div>
                <div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>7.   Frekuensi Hemodialisa</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="frekuensi_hemodialisa">
        
          </div>
        </div>
                <div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>8.   Komplikasi Hemodialisa</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="komplikasi">
       
         </div>
        </div>
        </div>
        </div>
</section>            <?php include "tab_navigasi.php"; ?>

</main>

                        



