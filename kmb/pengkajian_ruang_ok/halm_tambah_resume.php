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
              <h5 class="card-title mb-1"><strong>1. Biodata Klien</strong></h5>

                <!-- 1 BIODATA KLIEN -->


<!-- NAMA KLIEN -->
<div class="row mb-3">
    <label for="nama_klien" class="col-sm-2 col-form-label"><strong>Nama Klien</strong></label>

            <div class="col-sm-9">
                            <input type="text" class="form-control" name="nama_klien">
                       
                         </div>
                    </div>

<!-- JENIS KELAMIN -->
<div class="row mb-3">
    <label for="jenis_kelamin" class="col-sm-2 col-form-label"><strong>Jenis Kelamin</strong></label>

    <div class="col-sm-9">
        <select class="form-select" name="jenis_kelamin">
            <option value="">Pilih</option>
            <option value="Laki-laki">Laki-laki</option>
            <option value="Perempuan">Perempuan</option>
        </select>

  
                         </div>
                    </div>

<!-- UMUR -->
<div class="row mb-3">
    <label for="umur" class="col-sm-2 col-form-label"><strong>Umur</strong></label>

    <div class="col-sm-9">
        <input type="number" class="form-control" name="umur">

       
                         </div>
                    </div>

<!-- AGAMA -->
<div class="row mb-3">
    <label for="agama" class="col-sm-2 col-form-label"><strong>Agama</strong></label>

    <div class="col-sm-9">
        <input type="text" class="form-control" name="agama">

                         </div>
                    </div>

<!-- STATUS PERKAWINAN -->
<div class="row mb-3">
    <label for="status_perkawinan" class="col-sm-2 col-form-label"><strong>Status Perkawinan</strong></label>

    <div class="col-sm-9">
        <input type="text" class="form-control" name="status_perkawinan">

    </div>
</div>

<!-- PENDIDIKAN -->
<div class="row mb-3">
    <label for="pendidikan" class="col-sm-2 col-form-label"><strong>Pendidikan</strong></label>

    <div class="col-sm-9">
        <input type="text" class="form-control" name="pendidikan">
        </div>

</div>

<!-- PEKERJAAN -->
<div class="row mb-3">
    <label for="pekerjaan" class="col-sm-2 col-form-label"><strong>Pekerjaan</strong></label>

    <div class="col-sm-9">
        <input type="text" class="form-control" name="pekerjaan">

    </div>
</div>

<!-- ALAMAT -->
<div class="row mb-3">
    <label for="alamat" class="col-sm-2 col-form-label"><strong>Alamat</strong></label>

    <div class="col-sm-9">
        <textarea name="alamat" class="form-control" rows="3"
        style="display:block; overflow:hidden; resize: none;"
        oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

    </div>
</div>

<!-- DIAGNOSA MEDIS -->
<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>Diagnosa Medis</strong></label>

<div class="col-sm-9">
<input type="text" class="form-control" name="diagnosa_medis">

</div>
</div>
</div>
</div>
 <div class="card">
            <div class="card-body">
             
              <h5 class="card-title mb-1">
<!-- 2 KELUHAN UTAMA -->
<strong>2. Keluhan Utama</strong></h5>


<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>Keluhan Utama</strong></label>

<div class="col-sm-9">
<textarea name="keluhan_utama" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        

</div>
</div>
</div>
</div>
 <div class="card">
            <div class="card-body">
             
              <h5 class="card-title mb-1">

<strong>3.  Tanda-tanda vital </strong></h5>


<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>Tanda-tanda vital </strong></label>

<div class="col-sm-9">
<textarea class="form-control" rows="3" name="tanda_vital"  style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
      

</div>
</div>
</div>
</div>
 <div class="card">
            <div class="card-body">
             
              <h5 class="card-title mb-1">
<strong>4. Pengkajian  Data Fokus (Data yang Bermasalah) </strong></h5>


<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>Pre Operasi</strong></label>

<div class="col-sm-9">
<textarea class="form-control" rows="3" cols="30"
 style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"             
name="pre_operasi"></textarea>

</div>
</div>


<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>Pos Operasi</strong></label>

<div class="col-sm-9">
<textarea class="form-control" rows="3" cols="30"
style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"             
name="pos_operasi"></textarea>

</div>
</div>
</div>
</div>
<!-- 4 PEMERIKSAAN PENUNJANG -->
 <div class="card">
            <div class="card-body">
             
              <h5 class="card-title mb-1">
<strong>5. Pemeriksaan Penunjang</strong></h5>
</label>

<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>Pemeriksaan Penunjang</strong>
</label>

<div class="col-sm-9">
<textarea class="form-control" rows="3"  cols="30" style="display:block; overflow:hidden; resize: none;"
        oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
name="pemeriksaan_penunjang"></textarea>

</div>
</div>
</div>
</div>

<!-- 5 TERAPI SAAT INI -->
 <div class="card">
            <div class="card-body">
             
              <h5 class="card-title mb-1">
<strong>6. Terapi Saat Ini</strong></h5>


<div class="row mb-3">
<label class="col-sm-2 col-form-label" ><strong>Terapi Saat Ini</strong>
</label>

<div class="col-sm-9">
<textarea class="form-control" rows="3" cols="30"
name="terapi_saat_ini"  style="display:block; overflow:hidden; resize: none;"
        oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

</div>
</div>
</div>
</div>
  
 <div class="card">
            <div class="card-body">
             
              <h5 class="card-title mb-1">
                            <strong>7.  Klasifikasi data Pre dan Post Operasi</strong></h5>
       
            <section class="section dashboard">
     
                <!-- General Form Elements -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

                <!-- Bagian No. DX -->

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>DATA SUBJEKTIF</strong></label>

                        <div class="col-sm-9">
                            <textarea name="data_subjektif" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                         </div>
                    </div> 

                 

                <!-- Bagian Implementasi -->

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>DATA OBJEKTIF</strong></label>

                        <div class="col-sm-9">
                            <textarea name="data_objektif" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                         </div>
                    </div> 
                    
                <!-- Bagian Button -->    
                    <div class="row mb-3">
                        <div class="col-sm-11 justify-content-end d-flex">
                            <button type="submit" name="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </div> 

                    <h5 class="card-title mt-2"><strong>Klasifikasi data Pre dan Post Operasi</strong></h5>

                    <style>
                    .table-pemeriksaan {
                        table-layout: fixed;
                        width:100%
                    }

                    .table-pemeriksaan td,
                    .table-pemeriksaan th {
                        word-wrap: break-word;
                        white-space: normal;
                        vertical-align: top;
                    }
                    </style>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center">DATA SUBJEKTIF </th>
                                <th class="text-center">DATA OBJEKTIF</th>
                        </tr>
                        </thead>

                    <tbody>

                    <?php
                    if(!empty($data)){
                        foreach($data as $row){
                            echo "<tr>
                            <td>".$row['DATA_SUBJEKTIF ']."</td>
                            <td>".$row['DATA_OBJEKTIF']."</td>
                            </tr>";
                        }
                    }
                    ?>

                    </tbody>
                    </table>    

     
                   
<?php include "tab_navigasi.php"; ?>
</section> 
</main>

