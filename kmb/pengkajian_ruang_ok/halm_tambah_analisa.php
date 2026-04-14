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
if (isset($_POST['submit'])) {
    $data = $_POST['DATA'];
    $etiologi = $_POST['ETILOGI'];
    $masalah = $_POST['MASALAH'];

    // Koneksi database dan simpan data ke tabel (pastikan Anda sudah melakukan koneksi database)
    $sql = "INSERT INTO tbl_analisa_data (data, etiologi, masalah) VALUES ('$data', '$etiologi', '$masalah')";
    if ($mysqli->query($sql) === TRUE) {
        echo "<script>alert('Data berhasil disimpan.')</script>";
    } else {
        echo "Terjadi kesalahan: " . $mysqli->error;
    }
}

?>

?>

<main id="main" class="main">

                 <?php include "kmb/pengkajian_ruang_ok/tab.php"; ?>


    <section class="section dashboard">
        <div class="card">
            <div class="card-body">
 <div class="row mb-2">
                        <label class="col-sm-6 col-form-label text-primary">
                            <strong>7.  Analisa Data Pre dan Post Operasi</strong>
                    </div>

                <!-- General Form Elements -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

              

                <!-- Bagian Hari/Tanggal -->

                    <div class="row mb-3">
                        <label for="hari_tgl" class="col-sm-2 col-form-label"><strong>DATA</strong></label>

                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="DATA" name="DATA">
                            
                           
                         </div>
                    </div>
                <!-- Bagian Jam -->

                    <div class="row mb-3">
                        <label for="jam" class="col-sm-2 col-form-label"><strong>ETIOLOGI</strong></label>

                        <div class="col-sm-9">
                             <input type="text" class="form-control" id="ETILOGI" name="ETILOGI">
                          
                         </div>
                    </div> 

                <!-- Bagian Implementasi -->

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>MASALAH</strong></label>

                        <div class="col-sm-9">
                            <textarea name="MASALAH" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                         </div>
                    </div> 
                    
                <!-- Bagian Button -->    
                    <div class="row mb-3">
                        <div class="col-sm-11 justify-content-end d-flex">
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
                                <th class="text-center">DATA</th>
                                <th class="text-center">ETIOLOGI</th>
                                <th class="text-center">MASALAH</th>
                        </tr>
                        </thead>

                    <tbody>

                    <?php
                    if(!empty($data)){
                        foreach($data as $row){
                            echo "<tr>
                            <td>".$row['NO']."</td>
                            <td>".$row['DATA']."</td>
                            <td>".$row['ETILOGI']."</td>
                            <td>".$row['MASALAH']."</td>
                            </tr>";
                        }
                    }
                    ?>

                    </tbody>
                    </table>  
                    </form>
                    <?php include "tab_navigasi.php"; ?></div>
                    </section>  

    
   