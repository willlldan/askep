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
        <h1><strong>Asuhan Keperawatan Poli Jiwa</strong></h1>
    </div><!-- End Page Title -->
    <br>

    <ul class="nav nav-tabs custom-tabs">

<li class="nav-item">
    <a class="nav-link <?= ($_GET['tab'] ?? '') == 'praktik_klinik_keperawatan_jiwa' ? 'active' : '' ?>"
    href="index.php?page=jiwa/poli_jiwa&tab=praktik_klinik_keperawatan_jiwa">
    Format LP Praktik Klinik Keperawatan Jiwa
    </a>
</li>

<li class="nav-item">
    <a class="nav-link <?= ($_GET['tab'] ?? '') == 'resume' ? 'active' : '' ?>"
    href="index.php?page=jiwa/poli_jiwa&tab=resume">
    Format Resume Keperawatan Jiwa
    </a>
</li>
<li class="nav-item">
    <a class="nav-link <?= ($_GET['tab'] ?? '') == 'diagnosa' ? 'active' : '' ?>"
    href="index.php?page=jiwa/poli_jiwa&tab=diagnosa">
    Diagnosa Keperawatan
    </a>
</li>
<li class="nav-item">
    <a class="nav-link <?= ($_GET['tab'] ?? '') == 'rencana' ? 'active' : '' ?>"
    href="index.php?page=jiwa/poli_jiwa&tab=rencana">
    Rencana Keperawatan
    </a>
</li>
<li class="nav-item">
    <a class="nav-link <?= ($_GET['tab'] ?? '') == 'implementasi' ? 'active' : '' ?>"
    href="index.php?page=jiwa/poli_jiwa&tab=implementasi">
    Implementasi Keperawatan
    </a>
</li>

</ul>

    <style>
    .custom-tabs {
        border-bottom: 1px solid #dee2e6;
    }

    .custom-tabs .nav-link {
        border: none;
        background: transparent;
        color: #f6f9ff;
        font-weight: 500;
        padding: 10px 20px;
    }

    .custom-tabs .nav-link:hover {
        color: #4154f1;
    }

    .custom-tabs .nav-link.active {
        border: none;
        border-bottom: 3px solid #4154f1;
        color: #4154f1;
        font-weight: 600;
        background: transparent;
    }
    </style>

    <section class="section dashboard">
    <div class="card">
        <div class="card-body">
            <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">
                
                <h5 class="card-title"><strong>FORMAT LAPORAN PENDAHULUAN PRAKTIK KLINIK KEPERAWATAN JIWA</strong></h5>

                <!-- A. PENGKAJIAN -->
<div class="row mb-3">
<div class="col-sm-12">
<label class="text-primary"><strong>A. PENGKAJIAN</strong></label>
</div>
</div>

<!-- 1. IDENTITAS KLIEN -->
<div class="row mb-3">
<div class="col-sm-12">
<label><strong>1. IDENTITAS KLIEN</strong></label>
</div>
</div>

<!-- NAMA -->
<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>Nama</strong></label>

<div class="col-sm-9">
<input type="text" class="form-control" name="nama">

<textarea class="form-control mt-2" rows="2"
placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
</div>

<div class="col-sm-1 d-flex align-items-start">
<div class="form-check">
<input class="form-check-input" type="checkbox">
</div>
</div>
</div>

<!-- UMUR -->
<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>Umur</strong></label>

<div class="col-sm-9">
<input type="number" class="form-control" name="umur">

<textarea class="form-control mt-2" rows="2"
placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
</div>

<div class="col-sm-1 d-flex align-items-start">
<div class="form-check">
<input class="form-check-input" type="checkbox">
</div>
</div>
</div>

<!-- AGAMA -->
<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>Agama</strong></label>

<div class="col-sm-9">
<input type="text" class="form-control" name="agama">

<textarea class="form-control mt-2" rows="2"
placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
</div>

<div class="col-sm-1 d-flex align-items-start">
<div class="form-check">
<input class="form-check-input" type="checkbox">
</div>
</div>
</div>

<!-- STATUS PERKAWINAN -->
<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>Status Perkawinan</strong></label>

<div class="col-sm-9">
<input type="text" class="form-control" name="status_perkawinan">

<textarea class="form-control mt-2" rows="2"
placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
</div>

<div class="col-sm-1 d-flex align-items-start">
<div class="form-check">
<input class="form-check-input" type="checkbox">
</div>
</div>
</div>

<!-- PEKERJAAN -->
<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>Pekerjaan</strong></label>

<div class="col-sm-9">
<input type="text" class="form-control" name="pekerjaan">

<textarea class="form-control mt-2" rows="2"
placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
</div>

<div class="col-sm-1 d-flex align-items-start">
<div class="form-check">
<input class="form-check-input" type="checkbox">
</div>
</div>
</div>

<!-- ALAMAT -->
<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>Alamat</strong></label>

<div class="col-sm-9">
<textarea class="form-control" rows="2" name="alamat"></textarea>

<textarea class="form-control mt-2" rows="2"
placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
</div>

<div class="col-sm-1 d-flex align-items-start">
<div class="form-check">
<input class="form-check-input" type="checkbox">
</div>
</div>
</div>

<!-- KUNJUNGAN KE -->
<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>Kunjungan ke</strong></label>

<div class="col-sm-9">
<input type="text" class="form-control" name="kunjungan_ke">

<textarea class="form-control mt-2" rows="2"
placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
</div>

<div class="col-sm-1 d-flex align-items-start">
<div class="form-check">
<input class="form-check-input" type="checkbox">
</div>
</div>
</div>

<!-- DIAGNOSA MEDIS -->
<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>Diagnosa Medis</strong></label>

<div class="col-sm-9">
<input type="text" class="form-control" name="diagnosa_medis">

<textarea class="form-control mt-2" rows="2"
placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
</div>

<div class="col-sm-1 d-flex align-items-start">
<div class="form-check">
<input class="form-check-input" type="checkbox">
</div>
</div>
</div>

<!-- TANGGAL PENGKAJIAN -->
<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>Tanggal Pengkajian</strong></label>

<div class="col-sm-9">
<input type="date" class="form-control" name="tanggal_pengkajian">

<textarea class="form-control mt-2" rows="2"
placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
</div>

<div class="col-sm-1 d-flex align-items-start">
<div class="form-check">
<input class="form-check-input" type="checkbox">
</div>
</div>
</div>

 <div class="row mb-2">
                        <label class="col-sm-3 col-form-label ">
                            <strong>2. ALASAN MASUK</strong>
                    </div>

<div class="row mb-3">
<label class="col-sm-2 col-form-label"></label>

<div class="col-sm-9">

<textarea name="alasan_masuk" class="form-control" rows="4"
style="overflow:hidden; resize:none;"
oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

<textarea class="form-control mt-2" rows="2"
placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!"
readonly></textarea>

</div>

<div class="col-sm-1 d-flex align-items-start">
<div class="form-check">
<input class="form-check-input" type="checkbox">
</div>
</div>
</div>

 <div class="row mb-2">
                        <label class="col-sm-5 col-form-label ">
                            <strong>3.  RIWAYAT KESEHATAN MASA LALU </strong>
                    </div>

<div class="row mb-3">
<label class="col-sm-2 col-form-label"></label>

<div class="col-sm-9">

<textarea name="alasan_masuk" class="form-control" rows="4"
style="overflow:hidden; resize:none;"
oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

<textarea class="form-control mt-2" rows="2"
placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!"
readonly></textarea>

</div>

<div class="col-sm-1 d-flex align-items-start">
<div class="form-check">
<input class="form-check-input" type="checkbox">
</div>
</div>
</div>

<div class="row mb-2">
                        <label class="col-sm-4 col-form-label ">
                            <strong>4.  DATA FOKUS</strong>
                    </div>


<!-- Data Subjektif -->
<div class="row mb-3">

<label class="col-sm-2 col-form-label"><strong>Data Subjektif</strong></label>

<div class="col-sm-9">

<textarea
class="form-control"
name="data_subjektif"
rows="3"
placeholder="Masukkan data subjektif"></textarea>

<textarea id="comment_subjektif"
class="form-control mt-2"
rows="2"
placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!"
readonly></textarea>

</div>

<div class="col-sm-1 d-flex align-items-start">
<div class="form-check">
<input class="form-check-input"
type="checkbox"
onclick="toggleComment('comment_subjektif',this)">
</div>
</div>

</div>

<!-- Data Objektif -->
<div class="row mb-3">

<label class="col-sm-2 col-form-label"><strong>Data Objektif</strong></label>

<div class="col-sm-9">

<textarea
class="form-control"
name="data_objektif"
rows="3"
placeholder="Masukkan data objektif"></textarea>

<textarea id="comment_objektif"
class="form-control mt-2"
rows="2"
placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!"
readonly></textarea>

</div>

<div class="col-sm-1 d-flex align-items-start">
<div class="form-check">
<input class="form-check-input"
type="checkbox"
onclick="toggleComment('comment_objektif',this)">
</div>
</div>

</div>
            <!-- XIII. ANALISA DATA -->
           <div class="row mb-2">
                        <label class="col-sm-4 col-form-label">
                            <strong>5.  ANALISA DATA</strong>
                    </div>

                <!-- General Form Elements -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

                <!-- Bagian No. DX -->

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>No</strong></label>

                        <div class="col-sm-9">
                            <textarea name="no" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentnodx" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 

                <!-- Bagian Hari/Tanggal -->

                    <div class="row mb-3">
                        <label for="hari_tgl" class="col-sm-2 col-form-label"><strong>Data Subjektif</strong></label>

                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="DATA" name="DATA">
                            
                             <!-- comment -->
                            <textarea class="form-control mt-2" id="commenthari_tgl" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>
                <!-- Bagian Jam -->

                    <div class="row mb-3">
                        <label for="jam" class="col-sm-2 col-form-label"><strong>Data Objektif</strong></label>

                        <div class="col-sm-9">
                             <input type="text" class="form-control" id="ETILOGI" name="ETILOGI">
                            
                             <!-- comment -->
                            <textarea class="form-control mt-2" id="commentjam" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 

                <!-- Bagian Implementasi -->

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Masalah</strong></label>

                        <div class="col-sm-9">
                            <textarea name="MASALAH" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentimplementasi" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
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
</form>
 <div class="row mb-2">
                        <label class="col-sm-5 col-form-label ">
                            <strong>6.  DAFTAR MASALAH KEPERAWATAN</strong>
                    </div>
             <div class="row mb-3">
                <label class="col-sm-2 col-form-label"><strong></strong></label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control" name="tempat_lahir">
                    
                    <!-- comment -->
                            <textarea class="form-control mt-2" id="commenttempat_lahir" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>
            
            <!-- XV. POHON MASALAH -->
              <div class="row mb-2">
                        <label class="col-sm-5 col-form-label ">
                            <strong>7.  POHON MASALAH </strong>
                    </div>
             <div class="row mb-3">
                <label class="col-sm-2 col-form-label"><strong>Efek</strong></label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control" name="tempat_lahir">
                    
                    <!-- comment -->
                            <textarea class="form-control mt-2" id="commenttempat_lahir" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>
             <div class="row mb-3">
                <label class="col-sm-2 col-form-label"><strong>Cara Problem</strong></label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control" name="tempat_lahir">
                    
                    <!-- comment -->
                            <textarea class="form-control mt-2" id="commenttempat_lahir" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>
             <div class="row mb-3">
                <label class="col-sm-2 col-form-label"><strong>Etiologi</strong></label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control" name="tempat_lahir">
                    
                    <!-- comment -->
                            <textarea class="form-control mt-2" id="commenttempat_lahir" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>
            
           

          
    <?php include "tab_navigasi.php"; ?>
</section>
</main>

