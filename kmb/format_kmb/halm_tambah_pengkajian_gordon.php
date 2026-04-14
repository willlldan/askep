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
        
<!-- 4 POLA PENGKAJIAN FX GORDON -->

<div class="card">
<div class="card-body">

<form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

<h5 class="card-title"><strong>4. Pola Pengkajian FX Gordon</strong></h5>
<!-- A PERSEPSI KESEHATAN -->
<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>a. Persepsi terhadap kesehatan dan manajemen kesehatan</strong></label>
</div>

<!-- 1 -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>1. Merokok / Alkohol?</strong></label>

    <div class="col-sm-9">
                <textarea class="form-control" rows="3" cols="30"  name="merokok"style="display:block; overflow:hidden; resize: none;"
                                    oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" ></textarea>


    </div>
</div>

<!-- 2 -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>2. Pemeriksaan kesehatan rutin?</strong></label>

    <div class="col-sm-9">

        <textarea class="form-control" rows="3" cols="30"  name="pemeriksaan_rutin"style="display:block; overflow:hidden; resize: none;"
                                    oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" ></textarea>
    </div>
</div>

<!-- 3 -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>3. Pendapat pasien tentang keadaan kesehatannya saat ini</strong></label>

    <div class="col-sm-9">
         <textarea class="form-control" rows="4" cols="30"  name="pendapat_kesehatan"style="display:block; overflow:hidden; resize: none;"
                                    oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" ></textarea>

      
    </div>
</div>

<!-- 4 -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>4. Persepsi pasien tentang berat ringannya penyakit</strong></label>

    <div class="col-sm-9">
     <textarea class="form-control" rows="4" cols="30"  name="persepsi_penyakit"style="display:block; overflow:hidden; resize: none;"
                                    oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" ></textarea>

     
    </div>
</div>

<!-- 5 -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>5. Persepsi tentang tingkat kesembuhan</strong></label>

    <div class="col-sm-9">
         <textarea class="form-control" rows="3" cols="30"  name="tingkat_kesembuhan"style="display:block; overflow:hidden; resize: none;"
                                    oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" ></textarea>

        
    </div>
</div>

<!-- B POLA AKTIVITAS -->
<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>b. Pola Aktivitas dan Latihan</strong></label>
</div>

<!-- 1. Rutinitas mandi -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>1. Rutinitas mandi</strong></label>

    <div class="col-sm-9">
        <small class="form-text" style="color:red;">kapan, bagaimana, dimana, sabun yang digunakan</small>
        <input class="form-control" name="rutinitas_mandi"></input>

       
    </div>
</div>

<!-- 2. Kebersihan sehari-hari -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>2. Kebersihan sehari-hari</strong></label>

    <div class="col-sm-9">
        <small class="form-text" style="color:red;">pakaian dll</small>
        <input class="form-control" name="kebersihan"></input>

       
    </div>
</div>

<!-- 3. Aktivitas sehari-hari -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>3. Aktivitas sehari-hari</strong></label>

    <div class="col-sm-9">
        <small class="form-text" style="color:red;">jenis pekerjaan, lamanya, dll</small>
        <input class="form-control" name="aktivitas"></input>

        
    </div>
</div>

<!-- TABEL KEMAMPUAN PERAWATAN DIRI -->
<div class="row mb-3">
<label class="col-sm-12"><strong>4. Kemampuan Perawatan Diri</strong></label>
</div>

<p><strong>Skor 0 = mandiri</strong></p>
<p><strong>Skor 1 = dibantu sebagian</strong></p>
<p><strong>Skor 2 = perlu bantuan orang lain</strong></p>
<p><strong>Skor 3 = bantuan orang lain dan alat</strong></p>
<p><strong>Skor 4 = tergantung</strong></p>

<div class="table-responsive">
<table class="table table-bordered">

<thead>
<tr>
<th><strong>Aktivitas</strong></th>
<th><strong>0</strong></th>
<th><strong>1</strong></th>
<th><strong>2</strong></th>
<th><strong>3</strong></th>
<th><strong>4</strong></th>
</tr>
</thead>

<tbody>

<tr>
<td><strong>Mandi</strong></td>
<td><input type="radio" name="mandi" value="0"></td>
<td><input type="radio" name="mandi" value="1"></td>
<td><input type="radio" name="mandi" value="2"></td>
<td><input type="radio" name="mandi" value="3"></td>
<td><input type="radio" name="mandi" value="4"></td>
</tr>

<tr>
<td><strong>Berpakaian / Berdandan</strong></td>
<td><input type="radio" name="berpakaian" value="0"></td>
<td><input type="radio" name="berpakaian" value="1"></td>
<td><input type="radio" name="berpakaian" value="2"></td>
<td><input type="radio" name="berpakaian" value="3"></td>
<td><input type="radio" name="berpakaian" value="4"></td>
</tr>

<tr>
<td><strong>Mobilisasi di TT</strong></td>
<td><input type="radio" name="mobilisasi" value="0"></td>
<td><input type="radio" name="mobilisasi" value="1"></td>
<td><input type="radio" name="mobilisasi" value="2"></td>
<td><input type="radio" name="mobilisasi" value="3"></td>
<td><input type="radio" name="mobilisasi" value="4"></td>
</tr>

<tr>
<td><strong>Pindah</strong></td>
<td><input type="radio" name="pindah" value="0"></td>
<td><input type="radio" name="pindah" value="1"></td>
<td><input type="radio" name="pindah" value="2"></td>
<td><input type="radio" name="pindah" value="3"></td>
<td><input type="radio" name="pindah" value="4"></td>
</tr>

<tr>
<td><strong>Ambulasi</strong></td>
<td><input type="radio" name="ambulasi" value="0"></td>
<td><input type="radio" name="ambulasi" value="1"></td>
<td><input type="radio" name="ambulasi" value="2"></td>
<td><input type="radio" name="ambulasi" value="3"></td>
<td><input type="radio" name="ambulasi" value="4"></td>
</tr>

<tr>
<td><strong>Makan / Minum</strong></td>
<td><input type="radio" name="makan" value="0"></td>
<td><input type="radio" name="makan" value="1"></td>
<td><input type="radio" name="makan" value="2"></td>
<td><input type="radio" name="makan" value="3"></td>
<td><input type="radio" name="makan" value="4"></td>
</tr>

</tbody>
</table>


<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>c. Pola Kognitif dan Perceptual</strong></label>
</div>
<div class="row mb-3">
    <label class="col-sm-2 col-form-label">
        <strong>1. Nyeri (kualitas, intensitas, durasi, skala nyeri, cara mengurangi nyeri)</strong>
    </label>
    <div class="col-sm-9">
         <textarea class="form-control" rows="4" cols="30"  name="nyeri"style="display:block; overflow:hidden; resize: none;"
                                    oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" ></textarea>
   
    </div>
</div>


<div class="row mb-3">
    <label class="col-sm-2 col-form-label">
        <strong>2. Fungsi panca indra (penglihatan, pendengaran, pengecapan, penghidu, perasa) menggunakan alat bantu?</strong>
    </label>
    <div class="col-sm-9">
         <textarea class="form-control" rows="7" cols="30"  name="panca_indra"style="display:block; overflow:hidden; resize: none;"
                                    oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" ></textarea>
     
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label">
        <strong>3. Kemampuan berbicara</strong>
    </label>
    <div class="col-sm-9">
         <textarea class="form-control" rows="2" cols="30"  name="berbicara"style="display:block; overflow:hidden; resize: none;"
                                    oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" ></textarea>
        
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label">
        <strong>4. Kemampuan membaca</strong>
    </label>
    <div class="col-sm-9">
         <textarea class="form-control" rows="2" cols="30"  name="membaca" style="display:block; overflow:hidden; resize: none;"
                                    oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" ></textarea>
     
    </div>
</div>


<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>d. Pola Konsep Diri</strong></label>
</div>
<div class="row mb-3">
    <label class="col-sm-2 col-form-label">
        <strong>1. Bagaimana klien memandang dirinya</strong>
    </label>
    <div class="col-sm-9">
         <textarea class="form-control" rows="3" cols="30"  name="konsep_diri"style="display:block; overflow:hidden; resize: none;"
                                    oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" ></textarea>
      
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label">
        <strong>2. Hal-hal yang disukai klien mengenai dirinya</strong>
    </label>
    <div class="col-sm-9">
         <textarea class="form-control" rows="3" cols="30"  name="hal_disukai"style="display:block; overflow:hidden; resize: none;"
                                    oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" ></textarea>
      
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label">
        <strong>3. Apakah klien dapat mengidentifikasi kekuatan dan kelemahan dirinya</strong>
    </label>
    <div class="col-sm-9">
         <textarea class="form-control" rows="6" cols="30"  name="kekuatan_kelemahan"style="display:block; overflow:hidden; resize: none;"
                                    oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" ></textarea>
       
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label">
        <strong>4. Hal-hal yang dapat dilakukan klien secara baik</strong>
    </label>
    <div class="col-sm-9">
         <textarea class="form-control" rows="3" cols="30"  name="kemampuan_baik"style="display:block; overflow:hidden; resize: none;"
                                    oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" ></textarea>
        
    </div>
</div>



<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>e. Pola Koping</strong></label>
</div>
<div class="row mb-3">
    <label class="col-sm-2 col-form-label">
        <strong>1. Masalah utama selama masuk RS (keuangan, dll)</strong>
    </label>
    <div class="col-sm-9">
         <textarea class="form-control" rows="3" cols="30"  name="masalah_rs"style="display:block; overflow:hidden; resize: none;"
                                    oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" ></textarea>
    
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label">
        <strong>2. Kehilangan atau perubahan yang terjadi sebelumnya</strong>
    </label>
    <div class="col-sm-9">
         <textarea class="form-control" rows="4" cols="30"  name="kehilangan"style="display:block; overflow:hidden; resize: none;"
                                    oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" ></textarea>       
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label">
        <strong>3. Takut terhadap kekerasan</strong>
    </label>
    <div class="col-sm-9">
         <textarea class="form-control" rows="2" cols="30"  name="takut_kekerasan"style="display:block; overflow:hidden; resize: none;"
                                    oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" ></textarea>
       
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label">
        <strong>4. Pandangan terhadap masa depan</strong>
    </label>
    <div class="col-sm-9">
         <textarea class="form-control" rows="3" cols="30"  name="masa_depan"style="display:block; overflow:hidden; resize: none;"
                                    oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" ></textarea>
    
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label">
        <strong>5. Mekanisme koping saat menghadapi masalah</strong>
    </label>
    <div class="col-sm-9">
         <textarea class="form-control" rows="4" cols="30"  name="mekanisme_koping"style="display:block; overflow:hidden; resize: none;"
                                    oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" ></textarea>
        
    </div>
</div>
<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>f. Pola Seksual - Reproduksi</strong></label>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label">
        <strong>1. Masalah menstruasi</strong>
    </label>
    <div class="col-sm-9">
         <textarea class="form-control" rows="2" cols="30"  name="masalah_menstruasi"style="display:block; overflow:hidden; resize: none;"
                                    oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" ></textarea>
      
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label">
        <strong>2. Papsmear terakhir</strong>
    </label>
    <div class="col-sm-9">
         <textarea class="form-control" rows="2" cols="30"  name="papsmear"style="display:block; overflow:hidden; resize: none;"
                                    oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" ></textarea>
       
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label">
        <strong>3. Perawatan payudara setiap bulan</strong>
    </label>
    <div class="col-sm-9">
         <textarea class="form-control" rows="3" cols="30"  name="perawatan_payudara"style="display:block; overflow:hidden; resize: none;"
                                    oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" ></textarea>
        
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label">
        <strong>4. Apakah ada kesukaran dalam berhubungan seksual</strong>
    </label>
    <div class="col-sm-9">
         <textarea class="form-control" rows="4" cols="30"  name="kesulitan_seksual"style="display:block; overflow:hidden; resize: none;"
                                    oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" ></textarea>
        
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label">
        <strong>5. Apakah penyakit sekarang mengganggu fungsi seksual</strong>
    </label>
    <div class="col-sm-9">
         <textarea class="form-control" rows="5" cols="30"  name="gangguan_seksual"style="display:block; overflow:hidden; resize: none;"
                                    oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" ></textarea>
        
    </div>
</div>

<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>g. Pola Peran Berhubungan</strong></label>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label">
        <strong>1. Peran pasien dalam keluarga dan masyarakat</strong>
    </label>
    <div class="col-sm-9">
         <textarea class="form-control" rows="3" cols="30"  name="peran_pasien"style="display:block; overflow:hidden; resize: none;"
                                    oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" ></textarea>
        
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label">
        <strong>2. Apakah klien punya teman dekat</strong>
    </label>
    <div class="col-sm-9">
         <textarea class="form-control" rows="3" cols="30"  name="teman_dekat"style="display:block; overflow:hidden; resize: none;"
                                    oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" ></textarea>        
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label">
        <strong>3. Siapa yang dipercaya membantu klien saat kesulitan</strong>
    </label>
    <div class="col-sm-9">
         <textarea class="form-control" rows="4" cols="30"  name="orang_terpercaya"style="display:block; overflow:hidden; resize: none;"
                                    oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" ></textarea>
        
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label">
        <strong>4. Apakah klien ikut kegiatan masyarakat? Bagaimana keterlibatannya</strong>
    </label>
    <div class="col-sm-9">
         <textarea class="form-control" rows="5" cols="30"  name="kegiatan_masyarakat"style="display:block; overflow:hidden; resize: none;"
                                    oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" ></textarea>
        
    </div>
</div>

<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>h. Pola Nilai dan Kepercayaan</strong></label>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label">
        <strong>1. Apakah klien menganut suatu agama?</strong>
    </label>
    <div class="col-sm-9">
         <textarea class="form-control" rows="3" cols="30"  name="agama_klien"style="display:block; overflow:hidden; resize: none;"
                                    oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" ></textarea>
        
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label">
        <strong>2. Menurut agama klien bagaimana hubungan manusia dengan pencipta-Nya?</strong>
    </label>
    <div class="col-sm-9">
         <textarea class="form-control" rows="5" cols="30"  name="hubungan_tuhan"style="display:block; overflow:hidden; resize: none;"
                                    oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" ></textarea>
        
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label">
        <strong>3. Dalam keadaan sakit apakah klien mengalami hambatan dalam ibadah?</strong>
    </label>
    <div class="col-sm-9">
         <textarea class="form-control" rows="5" cols="30"  name="hambatan_ibadah"style="display:block; overflow:hidden; resize: none;"
                                    oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" ></textarea>
        
    </div>
</div>

<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>i. Pola Nutrisi</strong></label>
</div>

<div class="row mb-4">
    <div class="col-sm-11"> <div class="table-responsive">
            <table class="table table-bordered table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th><strong>No</strong></th>
                        <th><strong>Kondisi</strong></th>
                        <th><strong>Sebelum</strong></th>
                        <th><strong>Saat Ini</strong></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td><strong>Frekuensi Makan</strong></td>
                        <td><input type="text" class="form-control" name="frekuensi_makan_sebelum"></td>
                        <td><input type="text" class="form-control" name="frekuensi_makan_sekarang"></td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td><strong>Selera Makan</strong></td>
                        <td><input type="text" class="form-control" name="selera_makan_sebelum"></td>
                        <td><input type="text" class="form-control" name="selera_makan_sekarang"></td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td><strong>Menu Makanan</strong></td>
                        <td><input type="text" class="form-control" name="menu_makan_sebelum"></td>
                        <td><input type="text" class="form-control" name="menu_makan_sekarang"></td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <td><strong>Ritual Saat Makan</strong></td>
                        <td><input type="text" class="form-control" name="ritual_makan_sebelum"></td>
                        <td><input type="text" class="form-control" name="ritual_makan_sekarang"></td>
                    </tr>
                    <tr>
                        <td>5</td>
                        <td><strong>Bantuan Makan Parenteral</strong></td>
                        <td><input type="text" class="form-control" name="bantuan_makan_sebelum"></td>
                        <td><input type="text" class="form-control" name="bantuan_makan_sekarang"></td>
                    </tr>
                </tbody>
            </table>
        </div>
        
    </div>
</div>


<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>j. Cairan</strong></label>
</div>

<div class="row mb-4">
    <div class="col-sm-11">
        <div class="table-responsive">
            <table class="table table-bordered table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th><strong>No</strong></th>
                        <th><strong>Kondisi</strong></th>
                        <th><strong>Sebelum Sakit</strong></th>
                        <th><strong>Saat Ini</strong></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td><strong>Jenis Minuman</strong></td>
                        <td><input type="text" class="form-control" name="jenis_minum_sebelum"></td>
                        <td><input type="text" class="form-control" name="jenis_minum_sekarang"></td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td><strong>Jumlah Cairan</strong></td>
                        <td><input type="text" class="form-control" name="jumlah_cairan_sebelum"></td>
                        <td><input type="text" class="form-control" name="jumlah_cairan_sekarang"></td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td><strong>Bantuan Cairan Parenteral</strong></td>
                        <td><input type="text" class="form-control" name="bantuan_cairan_sebelum"></td>
                        <td><input type="text" class="form-control" name="bantuan_cairan_sekarang"></td>
                    </tr>
                </tbody>
            </table>
        </div>
       
    </div>
</div>
<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>k. Pola Eliminasi BAB</strong></label>
</div>
<div class="row mb-4">
    <div class="col-sm-11">
        <div class="table-responsive">
            <table class="table table-bordered table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th><strong>No</strong></th>
                        <th><strong>Kondisi</strong></th>
                        <th><strong>Sebelum Sakit</strong></th>
                        <th><strong>Saat Ini</strong></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td><strong>Frekuensi (Waktu)</strong></td>
                        <td><input type="text" class="form-control" name="bab_frekuensi_sebelum"></td>
                        <td><input type="text" class="form-control" name="bab_frekuensi_sekarang"></td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td><strong>Konsistensi</strong></td>
                        <td><input type="text" class="form-control" name="bab_konsistensi_sebelum"></td>
                        <td><input type="text" class="form-control" name="bab_konsistensi_sekarang"></td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td><strong>Warna</strong></td>
                        <td><input type="text" class="form-control" name="bab_warna_sebelum"></td>
                        <td><input type="text" class="form-control" name="bab_warna_sekarang"></td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <td><strong>Bau</strong></td>
                        <td><input type="text" class="form-control" name="bab_bau_sebelum"></td>
                        <td><input type="text" class="form-control" name="bab_bau_sekarang"></td>
                    </tr>
                    <tr>
                        <td>5</td>
                        <td><strong>Kesulitan saat BAB</strong></td>
                        <td><input type="text" class="form-control" name="bab_kesulitan_sebelum"></td>
                        <td><input type="text" class="form-control" name="bab_kesulitan_sekarang"></td>
                    </tr>
                    <tr>
                        <td>6</td>
                        <td><strong>Penggunaan Obat Pencahar</strong></td>
                        <td><input type="text" class="form-control" name="bab_obat_sebelum"></td>
                        <td><input type="text" class="form-control" name="bab_obat_sekarang"></td>
                    </tr>
                </tbody>
            </table>
        </div>
        
    </div>
</div>

<hr>

<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>l. Pola Eliminasi BAK</strong></label>
</div>
<div class="row mb-4">
    <div class="col-sm-11">
        <div class="table-responsive">
            <table class="table table-bordered table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th><strong>No</strong></th>
                        <th><strong>Kondisi</strong></th>
                        <th><strong>Sebelum Sakit</strong></th>
                        <th><strong>Saat Ini</strong></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td><strong>Frekuensi (Waktu)</strong></td>
                        <td><input type="text" class="form-control" name="bak_frekuensi_sebelum"></td>
                        <td><input type="text" class="form-control" name="bak_frekuensi_sekarang"></td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td><strong>Warna</strong></td>
                        <td><input type="text" class="form-control" name="bak_warna_sebelum"></td>
                        <td><input type="text" class="form-control" name="bak_warna_sekarang"></td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td><strong>Bau</strong></td>
                        <td><input type="text" class="form-control" name="bak_bau_sebelum"></td>
                        <td><input type="text" class="form-control" name="bak_bau_sekarang"></td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <td><strong>Kesulitan saat BAK</strong></td>
                        <td><input type="text" class="form-control" name="bak_kesulitan_sebelum"></td>
                        <td><input type="text" class="form-control" name="bak_kesulitan_sekarang"></td>
                    </tr>
                    <tr>
                        <td>5</td>
                        <td><strong>Penggunaan Obat Diuretik</strong></td>
                        <td><input type="text" class="form-control" name="bak_obat_sebelum"></td>
                        <td><input type="text" class="form-control" name="bak_obat_sekarang"></td>
                    </tr>
                </tbody>
            </table>
        </div>
        
    </div>
</div>



<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>m. Pola Tidur</strong></label>
</div>
<div class="row mb-4">
    <div class="col-sm-11">
        <div class="table-responsive">
            <table class="table table-bordered table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th><strong>No</strong></th>
                        <th><strong>Kondisi</strong></th>
                        <th><strong>Sebelum Sakit</strong></th>
                        <th><strong>Saat Ini</strong></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td rowspan="2">1</td>
                        <td><strong>Jam Tidur - Siang</strong></td>
                        <td><input type="text" class="form-control" name="tidur_siang_sebelum"></td>
                        <td><input type="text" class="form-control" name="tidur_siang_sekarang"></td>
                    </tr>
                    <tr>
                        <td><strong>Jam Tidur - Malam</strong></td>
                        <td><input type="text" class="form-control" name="tidur_malam_sebelum"></td>
                        <td><input type="text" class="form-control" name="tidur_malam_sekarang"></td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td><strong>Kesulitan Tidur</strong></td>
                        <td><input type="text" class="form-control" name="kesulitan_tidur_sebelum"></td>
                        <td><input type="text" class="form-control" name="kesulitan_tidur_sekarang"></td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td><strong>Kebiasaan Sebelum Tidur</strong></td>
                        <td><input type="text" class="form-control" name="kebiasaan_tidur_sebelum"></td>
                        <td><input type="text" class="form-control" name="kebiasaan_tidur_sekarang"></td>
                    </tr>
                </tbody>
            </table>
        </div>
       
    </div>
</div>



<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>n. Pola Personal Hygiene</strong></label>
</div>
<div class="row mb-4">
    <div class="col-sm-11">
        <div class="table">
            <table class="table table-bordered table-hover mb-1">
                <thead class="table-light">
                    <tr>
                        <th><strong>No</strong></th>
                        <th><strong>Kondisi</strong></th>
                        <th><strong>Sebelum Sakit</strong></th>
                        <th><strong>Saat Ini</strong></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td rowspan="3">1</td>
                        <td><strong>Mandi - Frekuensi</strong></td>
                        <td><input type="text" class="form-control" name="mandi_frekuensi_sebelum"></td>
                        <td><input type="text" class="form-control" name="mandi_frekuensi_sekarang"></td>
                    </tr>
                    <tr>
                        <td><strong>Mandi - Cara</strong></td>
                        <td><input type="text" class="form-control" name="mandi_cara_sebelum"></td>
                        <td><input type="text" class="form-control" name="mandi_cara_sekarang"></td>
                    </tr>
                    <tr>
                        <td><strong>Mandi - Tempat</strong></td>
                        <td><input type="text" class="form-control" name="mandi_tempat_sebelum"></td>
                        <td><input type="text" class="form-control" name="mandi_tempat_sekarang"></td>
                    </tr>
                    <tr>
                        <td rowspan="2">2</td>
                        <td><strong>Cuci Rambut - Frekuensi</strong></td>
                        <td><input type="text" class="form-control" name="rambut_frekuensi_sebelum"></td>
                        <td><input type="text" class="form-control" name="rambut_frekuensi_sekarang"></td>
                    </tr>
                    <tr>
                        <td><strong>Cuci Rambut - Cara</strong></td>
                        <td><input type="text" class="form-control" name="rambut_cara_sebelum"></td>
                        <td><input type="text" class="form-control" name="rambut_cara_sekarang"></td>
                    </tr>
                    <tr>
                        <td rowspan="2">3</td>
                        <td><strong>Gunting Kuku - Frekuensi</strong></td>
                        <td><input type="text" class="form-control" name="kuku_frekuensi_sebelum"></td>
                        <td><input type="text" class="form-control" name="kuku_frekuensi_sekarang"></td>
                    </tr>
                    <tr>
                        <td><strong>Gunting Kuku - Cara</strong></td>
                        <td><input type="text" class="form-control" name="kuku_cara_sebelum"></td>
                        <td><input type="text" class="form-control" name="kuku_cara_sekarang"></td>
                    </tr>
                    <tr>
                        <td rowspan="2">4</td>
                        <td><strong>Gosok Gigi - Frekuensi</strong></td>
                        <td><input type="text" class="form-control" name="gigi_frekuensi_sebelum"></td>
                        <td><input type="text" class="form-control" name="gigi_frekuensi_sekarang"></td>
                    </tr>
                    <tr>
                        <td><strong>Gosok Gigi - Cara</strong></td>
                        <td><input type="text" class="form-control" name="gigi_cara_sebelum"></td>
                        <td><input type="text" class="form-control" name="gigi_cara_sekarang"></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    </div>