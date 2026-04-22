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

  <div class="pagetitle">
        <h1><strong>Asuhan Keperawatan Jiwa RSUD</strong></h1>
    </div><!-- End Page Title -->
    
    <?php include "tab.php"; ?>

    <section class="section dashboard">
       <div class="card">
<div class="card-body">

<form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

<h5 class="card-title"><strong>FORMAT PENGKAJIAN ANALISA KEPERAWATAN JIWA</strong></h5>


            <!-- ASPEK MEDIS -->
              <div class="row mb-2">
                        <label class="col-sm-4 col-form-label text-primary">
                            <strong>XI. Aspek Medis</strong>
                    </div>

<!-- Diagnosa Medis -->
<div class="row mb-3">

<label class="col-sm-2 col-form-label"><strong>Diagnosa Medis</strong></label>

<div class="col-sm-10">
<input type="text" class="form-control" name="diagnosa_medis">    
</div>

</div>


<!-- Terapi Medik -->
<div class="row mb-3">

<label class="col-sm-2 col-form-label"><strong>Terapi Medik</strong></label>

<div class="col-sm-10">
<input type="text" class="form-control" name="terapi_medik">
</div>

</div>
 <div class="row mb-2">
                        <label class="col-sm-4 col-form-label text-primary">
                            <strong>XII. Data Fokus</strong>
                    </div>



<!-- Data Subjektif -->
<div class="row mb-3">

<label class="col-sm-2 col-form-label"><strong>Data Subjektif</strong></label>

<div class="col-sm-10">
<textarea name="data_subjektif" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
</div>

</div>


<!-- Data Objektif -->
<div class="row mb-3">

<label class="col-sm-2 col-form-label"><strong>Data Objektif</strong></label>

<div class="col-sm-10"><textarea name="data_objektif" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
</div>

</div>
            <!-- XIII. ANALISA DATA -->
           <div class="row mb-2">
                        <label class="col-sm-4 col-form-label text-primary">
                            <strong>XV. Analisa Data</strong>
                    </div>


                <!-- General Form Elements -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

                <!-- Bagian Hari/Tanggal -->

                    <div class="row mb-3">
                        <label for="hari_tgl" class="col-sm-2 col-form-label"><strong>Data Subjektif</strong></label>

                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="DATA" name="DATA">
                           
                         </div>
                    </div>
                <!-- Bagian Jam -->

                    <div class="row mb-3">
                        <label for="jam" class="col-sm-2 col-form-label"><strong>Data Objektif</strong></label>

                        <div class="col-sm-10">
                             <input type="text" class="form-control" id="ETILOGI" name="ETILOGI">
                            
                         </div>
                    </div> 

                <!-- Bagian Implementasi -->

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Masalah</strong></label>

                        <div class="col-sm-10">
                            <textarea name="MASALAH" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                   
                         </div>
                    </div> 
                    
                <!-- Bagian Button -->    
                    <div class="row mb-3">
                        <div class="col-sm-12 justify-content-end d-flex">
                            <button type="submit" name="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </div> 

                    <h5 class="card-title mt-2"><strong>Analisa Data</strong></h5>

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
                                <th class="text-center">No</th>
                                <th class="text-center">Data Subjektif</th>
                                <th class="text-center">Data objektif</th>
                                <th class="text-center">Masalah</th>
                        </tr>
                        </thead>

                    <tbody>

                    <?php
                    if(!empty($data)){
                        foreach($data as $row){
                            echo "<tr>
                            <td>".$row['NO']."</td>
                            <td>".$row['Data_Subjektif']."</td>
                            <td>".$row['Data_objektif']."</td>
                            <td>".$row['Masalah']."</td>
                            </tr>";
                        }
                    }
                    ?>

                    </tbody>
                    </table>    

            </tbody>
        </table>


 <div class="row mb-2">
                        <label class="col-sm-5 col-form-label text-primary">
                            <strong>XIV. DAFTAR MASALAH KEPERAWATAN</strong>
                    </div>
             <div class="row mb-3">
                <label class="col-sm-2 col-form-label"><strong>Daftar Masalah Keperawatan</strong></label>
                    <div class="col-sm-10">
                   <textarea name="daftar_masalah_keperawatan" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
                   
                         </div>
                    </div>
            
            <!-- XV. POHON MASALAH -->
              <div class="row mb-2">
                        <label class="col-sm-5 col-form-label text-primary">
                            <strong>XV. POHON MASALAH</strong>
                    </div>
             <div class="row mb-3">
                <label class="col-sm-2 col-form-label"><strong>Efek</strong></label>
                    <div class="col-sm-10">
                    <textarea name="efek" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
                    
                  
                         </div>
                    </div>

             <div class="row mb-3">
                <label class="col-sm-2 col-form-label"><strong>Cara Problem</strong></label>
                    <div class="col-sm-10">
                   <textarea name="cara_problem" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
                 
                         </div>
                    </div>

             <div class="row mb-3">
                <label class="col-sm-2 col-form-label"><strong>Etiologi</strong></label>
                    <div class="col-sm-10">
                    <textarea name="etiologi" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
              
                         </div>
                    </div>
            
        
      
                         <?php include "tab_navigasi.php"; ?>

</section>
</main>
