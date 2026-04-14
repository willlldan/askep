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

                 <?php include "kmb/format_kmb/tab.php"; ?>
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
                <h5 class="card-title">

                <!-- General Form Elements -->
                <!-- A KONSEP DASAR MEDIS -->

<strong>A. KONSEP DASAR MEDIS</strong></h5>


                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Pengertian</strong></label>

                        <div class="col-sm-9">
                            <textarea name="pengertian" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                         </div>
                    </div> 

                <!-- Bagian Klasifikasi -->
                <div class="row mb-3">
                    <label for="klasifikasi" class="col-sm-2 col-form-label"><strong>Klasifikasi</strong></label>
                    <div class="col-sm-9">
                        <textarea name="klasifikasi" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                         </div>
                    </div>

<!-- Bagian Etiologi -->
                <div class="row mb-3">
                    <label for="etiologi" class="col-sm-2 col-form-label"><strong>Etiologi</strong></label>
                    <div class="col-sm-9">
                        <textarea name="etiologi" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                         </div>
                    </div>

   <!-- Bagian Manifestasi Klinik -->
                <div class="row mb-3">
                    <label for="manifestasiklinik" class="col-sm-2 col-form-label"><strong>Manifestasi Klinik</strong></label>
                    <div class="col-sm-9">
                        <textarea name="manifestasiklinik" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                         </div>
                    </div> 

    <!-- Bagian Patofisiologi -->
                <div class="row mb-3">
                    <label for="patofisiologi" class="col-sm-2 col-form-label"><strong>Patofisiologi</strong></label>
                    <div class="col-sm-9">
                        <textarea name="patofisiologi" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                         </div>
                    </div>

 <!-- Bagian Pemeriksaan Diagnostik -->
                <div class="row mb-3">
                    <label for="pemeriksaandiagnostik" class="col-sm-2 col-form-label"><strong>Pemeriksaan Diagnostik</strong></label>
                    <div class="col-sm-9">
                        <textarea name="pemeriksaandiagnostik" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                         </div>
                    </div>

          <!-- Bagian Penatalaksanaan -->
                <div class="row mb-3">
                    <label for="penatalaksanaan" class="col-sm-2 col-form-label"><strong>Penatalaksanaan</strong></label>
                    <div class="col-sm-9">
                        <textarea name="penatalaksaan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                         </div>
                    </div> 
                    </form>
                </div>
            </div>


 <div class="card">
            <div class="card-body">
            
                <!-- General Form Elements -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

                <h5 class="card-title"><strong>B. KONSEP DASAR KEPERAWATAN</strong></h5>

   <!-- Bagian Pengkajian Keperawatan -->

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Pengkajian Keperawatan</strong></label>

                        <div class="col-sm-9">
                            <textarea name="pengkajiankeperawatan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                         </div>
                    </div>
  <!-- Bagian Penyimpangan KDM -->
                <div class="row mb-3">
                    <label for="penyimpangankdm" class="col-sm-2 col-form-label"><strong>Penyimpangan KDM</strong></label>
                    <div class="col-sm-9">

                        <!-- Link Google Drive -->
                         <div class="form-control d-flex justify-content-between align-items-center">
                            <span>Upload Gambar Penyimpangan KDM pada link Google Drive yang tersedia</span>
                            <a href="<?= $penyimpangankdm ?>" target="_blank" class="btn btn-sm btn-primary">Upload</a>
                        </div>

                         </div>
                    </div>

                <!-- Bagian Diagnosa Keperawatan -->
                <div class="row mb-3">
                    <label for="diagnosakeperawatan" class="col-sm-2 col-form-label"><strong>Diagnosa Keperawatan</strong></label>
                    <div class="col-sm-9">
                        <textarea name="diagnosakeperawatan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                         </div>
                    </div>


<div class="row mb-2">
                    <label class="col-sm-6 col-form-label">
                        <strong>Perencanaan:</strong>
                </div>
                          
<!-- 4 PERENCANAAN -->
<form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">
                       
                <!-- Bagian Diagnosa -->

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Diagnosa</strong></label>

                        <div class="col-sm-9">
                            <textarea name="diagnosa" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                         </div>
                    </div> 

                    <!-- Bagian Tujuan dan Kriteria Hasil -->

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Tujuan dan Kriteria Hasil</strong></label>

                        <div class="col-sm-9">
                            <textarea name="tujuandankriteria" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                         </div>
                    </div> 

                    <!-- Bagian Intervensi -->

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Intervensi</strong></label>

                        <div class="col-sm-9">
                            <textarea name="intervensi" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                         </div>
                    </div> 

                    <!-- Bagian Button -->    
                    <div class="row mb-3">
                        <div class="col-sm-11 justify-content-end d-flex">
                            <button type="submit" name="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </div> 

                    <h5 class="card-title mt-2"><strong>Perencanaan</strong></h5>

                    <style>
                    .table-perencanaan {
                        table-layout: fixed;
                        width:100%
                    }

                    .table-perencanaan td,
                    .table-perencanaan th {
                        word-wrap: break-word;
                        white-space: normal;
                        vertical-align: top;
                    }
                    </style>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Diagnosa</th>
                                <th class="text-center">Tujuan dan Kriteria Hasil</th>
                                <th class="text-center">Intervensi</th>
                        </tr>
                        </thead>

                    <tbody>

                    <?php
                    if(!empty($data)){
                        foreach($data as $row){
                            echo "<tr>
                            <td>".$row['No']."</td>
                            <td>".$row['diagnosa']."</td>
                            <td>".$row['tujuan']."</td>
                            <td>".$row['intervensi']."</td>
                            </tr>";
                        }
                    }
                    ?>

                    </tbody>
                    </table>
 </div>
 </div>
 </form>
 </div>
 </div>

        <div class="card">
            <div class="card-body">
            
                <!-- General Form Elements -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

                <h5 class="card-title"><strong>C. DAFTAR PUSTAKA</strong></h5>

                <!-- General Form Elements -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">
                
                    <!-- Bagian Daftar Pustaka -->

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Daftar Pustaka</strong></label>

                        <div class="col-sm-9">
                            <textarea name="daftarpustaka" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                         </div>
                    </div>
                </div> 
            </div>

                    <?php include "tab_navigasi.php"; ?>

</main>

                        



