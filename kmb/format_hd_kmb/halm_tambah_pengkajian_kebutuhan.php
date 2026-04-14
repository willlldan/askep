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
<!-- 4 POLA PENGKAJIAN FX GORDON -->

<div class="card">
<div class="card-body">

<form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

<h5 class="card-title"><strong>5.	Pengkajian kebutuhan </strong></h5>
<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>a. Pola Aktivitas</strong></label>
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
    <label class="col-sm-12 text-primary"><strong>b. Pola Kognitif dan Perceptual</strong></label>
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
         <textarea class="form-control" rows="2" cols="30"  name="membaca"style="display:block; overflow:hidden; resize: none;"
                                    oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" ></textarea>

    </div>
</div>

<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>c. Pola Nutrisi</strong></label>
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
    <label class="col-sm-12 text-primary"><strong>d. Cairan</strong></label>
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
    <label class="col-sm-12 text-primary"><strong>e. Pola Eliminasi BAB</strong></label>
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



<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>f. Pola Eliminasi BAK</strong></label>
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
    <label class="col-sm-12 text-primary"><strong>g. Pola Tidur</strong></label>
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


<div class="row mb-2 mt-4">
    <label class="col-sm-12 text-primary"><strong>c. Data Penunjang</strong></label>
</div>

<div class="row mb-3">
    <label class="col-sm-3 col-form-label"><strong>1) Laboratorium </strong></label>
    <div class="col-sm-9">       
</div>
     <div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>Tanggal Pemeriksaan</strong>
    </div>
    <div class="col-sm-9">
        <input type="date" class="form-control" name="tanggal_pemeriksaan">
       
</div>
</div>
                         <div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>Nama Pemeriksaan</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="nama_pemeriksaan">
     
</div>
</div>
                                  <div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>Hasil</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="hasil">
       
</div>
</div>
                     <div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>Satuan</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="satuan">
       
</div>
</div>
 <div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>Nilai Rujukan</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="nilai_rujukan">
        
</div>
</div>
                     

                <!-- Bagian Button -->    
                <div class="row mb-3">
                    <div class="col-sm-11 justify-content-end d-flex">
                        <button type="submit" name="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
                </div>
                <h5 class="card-title"><strong>1) Laboratorium</strong></h5>
                
                <style>
                    .table-laboratorium {
                        table-layout: fixed;
                        width:100%
                    }

                    .table-laboratorium td,
                    .table-laboratorium th {
                        word-wrap: break-word;
                        white-space: normal;
                        vertical-align: top;
                    }
                    </style>

                    <table class="table table-bordered table-laboratorium">
                        <thead>
                            <tr>
                                <th class="text-center">Nama Pemeriksaan</th>
                                <th class="text-center">Hasil</th>
                                <th class="text-center">Satuan</th>
                                <th class="text-center">Nilai Rujukan</th>
                        </tr>
                        </thead>
                        </table>
                        </form>


<div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>2) Radiologi (Tgl Pemeriksaan & Hasil)</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="radiologi">
        
</div>
</div>

 <div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>3) Lainnya (USG, CT Scan, dll)</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="data_penunjang_lain">
        
</div>
</div>
</div>
    </form>
    </div>
</div>
    
 <div class="row mb-2">
                        <div class="card">
            <div class="card-body">
            
                <!-- General Form Elements -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

                <h5 class="card-title"><strong>6. Klasifikasi Data</strong></h5>

                    

                <!-- Bagian Data Subjektif (DS) -->
                <div class="row mb-3">
                    <label for="datasubjektif" class="col-sm-2 col-form-label"><strong>Data Subjektif (DS)</strong></label>
                    <div class="col-sm-9">
                        <textarea name="datasubjektif" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                         </div>
                    </div> 

                <!-- Bagian Data Objektif (DO) -->
                <div class="row mb-3">
                    <label for="dataobjektif" class="col-sm-2 col-form-label"><strong>Data Objektif (DO)</strong></label>
                    <div class="col-sm-9">
                        <textarea name="dataobjektif" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                         </div>
                    </div>  

                <!-- Bagian Button -->    
                <div class="row mb-3">
                    <div class="col-sm-11 justify-content-end d-flex">
                        <button type="submit" name="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
                
                <h5 class="card-title"><strong>Klasifikasi Data</strong></h5>
                
                <style>
                    .table-klasifikasidata {
                        table-layout: fixed;
                        width:100%
                    }

                    .table-klasifikasidata td,
                    .table-klasifikasidata th {
                        word-wrap: break-word;
                        white-space: normal;
                        vertical-align: top;
                    }
                    </style>

                    <table class="table table-bordered table-klasifikasidata">
                        <thead>
                            <tr>
                                <th class="text-center">Data Subjektif (DS)</th>
                                <th class="text-center">Data Objektif (DO)</th>
                        </tr>
                        </thead>
                        </table>
                        </form>
                        </div>
                        </div>
                         <!-- Bagian Analisa Data -->    
<div class="card">
             <div class="card-body">
                                
                     <!-- General Form Elements -->
                    <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

                    <h5 class="card-title"><strong>7 Analisa Data</strong></h5>
                <div class="row mb-2">
                  

                <!-- Bagian DS/DO -->
               
                    <!-- Bagian DATA -->
                <div class="row mb-3">
                    <label for="dsdo" class="col-sm-2 col-form-label"><strong>Data</strong></label>
                    <div class="col-sm-9">
                        <textarea name="dsdo" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                         </div>
                    </div> 

                <!-- Bagian Etiologi -->
                <div class="row mb-3">
                    <label for="etiologi" class="col-sm-2 col-form-label"><strong>Etiologi</strong></label>
                    <div class="col-sm-9">
                        <textarea name="etiologi" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                         </div>
                    </div> 
                    
                <!-- Bagian Masalah -->
                <div class="row mb-3">
                    <label for="masalah" class="col-sm-2 col-form-label"><strong>Masalah</strong></label>
                    <div class="col-sm-9">
                        <textarea name="masalah" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                         </div>
                    </div> 

                <!-- Bagian Button -->    
                <div class="row mb-3">
                    <div class="col-sm-11 justify-content-end d-flex">
                        <button type="submit" name="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>   
                
                <h5 class="card-title"><strong>Analisa Data</strong></h5>
                
                <style>
                    .table-analisadata {
                        table-layout: fixed;
                        width:100%
                    }

                    .table-analisadata td,
                    .table-analisadata th {
                        word-wrap: break-word;
                        white-space: normal;
                        vertical-align: top;
                    }
                    </style>

                    <table class="table table-bordered table-analisadata">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Data</th>
                                <th class="text-center">Etiologi</th>
                                <th class="text-center">Masalah</th>
                        </tr>
                        </thead>

                    <tbody>
                        </tbody>
                        </table>
                        </div>
                        
                         
                                            <?php include "tab_navigasi.php"; ?>
</section>              
</main>