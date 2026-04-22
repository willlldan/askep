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

<h5 class="card-title"><strong>FORMAT PENGKAJIAN KEPERAWATAN JIWA</strong></h5>

<!-- RUANG RAWAT -->
<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>Ruang Rawat</strong></label>

<div class="col-sm-10">
<input type="text" class="form-control" name="ruang_rawat">
</div>
</div>


<!-- TANGGAL RAWAT -->
<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>Tanggal Rawat</strong></label>

<div class="col-sm-10">
<input type="date" class="form-control" name="tanggal_rawat">
</div>
</div>

 <div class="row mb-2">
                        <label class="col-sm-3 col-form-label text-primary">
                            <strong>I. IDENTITAS KLIEN</strong>
                    </div>


<!-- NAMA KLIEN -->
<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>Nama Klien</strong></label>

<div class="col-sm-10">
<input type="text" class="form-control" name="nama_klien">
</div>
</div>

<!-- JENIS KELAMIN -->
<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>Jenis Kelamin</strong></label>

<div class="col-sm-10">
<select class="form-control" name="jenis_kelamin">
<option value="">-- Pilih --</option>
<option value="Laki-laki">Laki-laki</option>
<option value="Perempuan">Perempuan</option>
</select>
</div>
</div>


<!-- TANGGAL PENGKAJIAN -->
<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>Tanggal Pengkajian</strong></label>

<div class="col-sm-10">
<input type="date" class="form-control" name="tanggal_pengkajian">
</div>
</div>

<!-- RM -->
<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>Umur</strong></label>

<div class="col-sm-10">
<input type="text" class="form-control" name="rm">
</div>
</div>


<!-- RM -->
<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>RM</strong></label>

<div class="col-sm-10">
<input type="text" class="form-control" name="rm">
</div>
</div>


<!-- INFORMASI -->
<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>Informasi</strong></label>

<div class="col-sm-10">
<input type="text" class="form-control" name="informasi">
</div>
</div>

<div class="row mb-2">
                        <label class="col-sm-3 col-form-label text-primary">
                            <strong>II. ALASAN MASUK</strong>
                    </div>


<!-- NAMA KLIEN -->
<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>Alasan Masuk</strong></label>

<div class="col-sm-10">
<textarea name="alasanmasuk" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
</div>
</div>


 <div class="row mb-2">
                        <label class="col-sm-4 col-form-label text-primary">
                            <strong>III. FAKTOR PREDISPOSISI</strong>
                    </div>


<!-- 1 -->
<div class="row mb-3">
<label class="col-sm-5 col-form-label"><strong>1. Pernah mengalami gangguan jiwa di masa lalu?</strong></label>

<div class="col-sm-3">

<div class="form-check form-check-inline">
<input class="form-check-input" type="radio" name="gangguan_jiwa" value="ya">
<label class="form-check-label">Ya</label>
</div>

<div class="form-check form-check-inline">
<input class="form-check-input" type="radio" name="gangguan_jiwa" value="tidak">
<label class="form-check-label">Tidak</label>
</div>
</div>
</div>


<!-- 2 -->
<div class="row mb-3">
<label class="col-sm-5 col-form-label"><strong>2. Pengobatan sebelumnya</strong></label>

<div class="col-sm-3">

<div class="form-check form-check-inline">
<input class="form-check-input" type="radio" name="pengobatan" value="berhasil">
<label class="form-check-label">Berhasil</label>
</div>

<div class="form-check form-check-inline">
<input class="form-check-input" type="radio" name="pengobatan" value="tidak_berhasil">
<label class="form-check-label">Tidak berhasil</label>
</div>
</div>
</div>


<!-- 3 TABEL -->
<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>3. Riwayat Kejadian</strong></label>

<div class="col-sm-10">

<table class="table table-bordered">
<thead>
<tr>
<th>Jenis Kejadian</th>
<th>Pelaku / Usia</th>
<th>Korban / Usia</th>
<th>Saksi / Usia</th>
</tr>
</thead>

<tbody>
<tr>
<td>Aniaya Fisik</td>
<td><input type="text" class="form-control"></td>
<td><input type="text" class="form-control"></td>
<td><input type="text" class="form-control"></td>
</tr>

<tr>
<td>Aniaya Seksual</td>
<td><input type="text" class="form-control"></td>
<td><input type="text" class="form-control"></td>
<td><input type="text" class="form-control"></td>
</tr>

<tr>
<td>Penolakan</td>
<td><input type="text" class="form-control"></td>
<td><input type="text" class="form-control"></td>
<td><input type="text" class="form-control"></td>
</tr>

<tr>
<td>Kekerasan dalam keluarga</td>
<td><input type="text" class="form-control"></td>
<td><input type="text" class="form-control"></td>
<td><input type="text" class="form-control"></td>
</tr>

<tr>
<td>Tindakan Kriminal</td>
<td><input type="text" class="form-control"></td>
<td><input type="text" class="form-control"></td>
<td><input type="text" class="form-control"></td>
</tr>
</tbody>
</table>

</div>
</div>


<!-- PENJELASAN -->
<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>Jelaskan No 1,2,3</strong></label>

<div class="col-sm-10">

<textarea name="penjelasan" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
</div>
</div> 


<!-- 1 -->
<div class="row mb-3">
<label class="col-sm-5 col-form-label"><strong>4.	Adakah anggota keluarga yang mengalami gangguan jiwa      </strong></label>

<div class="col-sm-3">

<div class="form-check form-check-inline">
<input class="form-check-input" type="radio" name="gangguan_jiwa" value="ya">
<label class="form-check-label">Ya</label>
</div>

<div class="form-check form-check-inline">
<input class="form-check-input" type="radio" name="gangguan_jiwa" value="tidak">
<label class="form-check-label">Tidak</label>
</div>

</div>
</div>

<!-- INFORMASI -->
<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>Hubungan keluarga </strong></label>

<div class="col-sm-10">
<input type="text" class="form-control" name="Hubungan_keluarga ">
</div>
</div>

<!-- INFORMASI -->
<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>Gejala </strong></label>

<div class="col-sm-10">
<input type="text" class="form-control" name="Gejala ">
</div>
</div>

<!-- INFORMASI -->
<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>Riwayat </strong></label>

<div class="col-sm-10">
<input type="text" class="form-control" name="Riwayat ">
</div>
</div>

<!-- INFORMASI -->
<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>Pengobatan/ <br>perawatan </strong></label>

<div class="col-sm-10">
<input type="text" class="form-control" name="Hubungan_keluarga ">
</div>
</div>

 <div class="row mb-2">
                        <label class="col-sm-5 col-form-label ">
                            <strong>5.	Pengalaman masa lalu yang tidak menyenangkan </strong>
                    </div>


<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>Pengalaman masa lalu yang tidak menyenangkan</strong></label>

<div class="col-sm-10">
<textarea name="pengalaman_masa_lalu" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
</div>
</div>

        <div class="row mb-2">
    <label class="col-sm-3 col-form-label text-primary">
        <strong>IV. PEMERIKSAAN FISIK</strong>
    </label>
</div>

<!-- 1. Tanda Vital -->
<div class="row mb-3">
    <label class="col-sm-3 col-form-label"><strong>1. Tanda Vital</strong></label>
</div>

<!-- TD -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>TD</strong></label>

    <div class="col-sm-10">
        <div class="input-group">
            <input type="text" class="form-control" name="td">
            <span class="input-group-text">mmHg</span>
        </div>
    </div>
</div>

<!-- Nadi -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>N</strong></label>

    <div class="col-sm-10">
        <div class="input-group">
            <input type="text" class="form-control" name="nadi">
            <span class="input-group-text">x/mnt</span>
        </div>
    </div>
</div>

<!-- Suhu -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>S</strong></label>

    <div class="col-sm-10">
        <div class="input-group">
            <input type="text" class="form-control" name="suhu">
            <span class="input-group-text">°C</span>
        </div>
    </div>
</div>

<!-- Pernafasan -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>P</strong></label>

    <div class="col-sm-10">
        <div class="input-group">
            <input type="text" class="form-control" name="pernafasan">
            <span class="input-group-text">x/mnt</span>
        </div>
    </div>
</div>
      
                 
    <!-- 2. Pengukuran -->
<div class="row mb-3">
    <label class="col-sm-3 col-form-label"><strong>2. Pengukuran</strong></label>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>TB</strong></label>

    <div class="col-sm-10">
        <div class="input-group">
            <input type="text" class="form-control" name="pernafasan">
            <span class="input-group-text">cm</span>
        </div>
    </div>
    </div>

    <div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>BB</strong></label>

    <div class="col-sm-10">
        <div class="input-group">
            <input type="text" class="form-control" name="pernafasan">
            <span class="input-group-text">kg</span>
        </div>

    </div>
    </div>



<!-- 3. Keluhan Fisik -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>3. Keluhan Fisik</strong></label>

    <div class="col-sm-10">
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="keluhan_fisik" value="ya">
            <label class="form-check-label">Ya</label>
        </div>

        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="keluhan_fisik" value="tidak">
            <label class="form-check-label">Tidak</label>
        </div>
    </div>
</div>


<!-- Penjelasan -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Penjelasan</strong></label>

    <div class="col-sm-10">
       <textarea name="penjelasan" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
    </div>
</div>
               <div class="row mb-2">
                        <label class="col-sm-3 col-form-label text-primary">
                            <strong>IV. PSIKOSOSIAL</strong>
                    </div> 

<!-- 1. Genogram -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>1. Genogram</strong></label>

    <div class="col-sm-10">
        <textarea name="obatdikonsumsi" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
    </div>
</div>


<!-- 2. Konsep Diri -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>2. Konsep Diri</strong></label>
</div>

<!-- a. Gambaran Diri -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>a. Gambaran Diri</strong></label>

    <div class="col-sm-10">
        <textarea name="gambaran_diri" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
    </div>
</div>


<!-- b. Identitas Diri -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>b. Identitas Diri</strong></label>

    <div class="col-sm-10">
        <textarea name="identitas_diri" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
    </div>
</div>


<!-- c. Peran -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>c. Peran</strong></label>

    <div class="col-sm-10">
        <textarea name="peran" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
    </div>
</div>


<!-- d. Ideal Diri -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>d. Ideal Diri</strong></label>

    <div class="col-sm-10">
        <textarea name="ideal_diri" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
    </div>
</div>


<!-- e. Harga Diri -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>e. Harga Diri</strong></label>

    <div class="col-sm-10">
        <textarea name="harga_diri" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
    </div>
</div>


<!-- 3. Hubungan Sosial -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>3. Hubungan Sosial</strong></label>
</div>

<!-- a. Orang yang Berarti -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>a. Orang yang Berarti</strong></label>

    <div class="col-sm-10">
        <textarea name="orang_berarti" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
    </div>
</div>


<!-- b. Kegiatan Kelompok -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>b. Peran serta dalam kegiatan kelompok/ <br>masyarakat</strong></label>

    <div class="col-sm-10">
        <textarea name="kegiatan_kelompok" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
    </div>
</div>


<!-- c. Hambatan -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>c. Hambatan dalam hubungan dengan orang lain</strong></label>

    <div class="col-sm-10">
        <textarea name="hambatan_hubungan" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
    </div>
</div>


<!-- 4. Spiritual -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>4. Spiritual</strong></label>
</div>

<!-- Nilai dan Keyakinan -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>a. Nilai dan Keyakinan</strong></label>

    <div class="col-sm-10">
        <textarea name="nilai_keyakinan" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
    
    </div>
</div>


<!-- Kegiatan Ibadah -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>b. Kegiatan Ibadah</strong></label>

    <div class="col-sm-10">
        <textarea name="kegiatan_ibadah" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
    </div>
</div>
                       <div class="row mb-2">
                        <label class="col-sm-3 col-form-label text-primary">
                            <strong>VI. STATUS MENTAL</strong>
                    </div> 
<!-- 1 Penampilan -->
<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>1. Penampilan</strong></label>

<div class="col-sm-10">

<div class="form-check form-check-inline">
<input class="form-check-input" type="checkbox" name="penampilan[]" value="tidak_rapi">
<label class="form-check-label">Tidak rapi</label>
</div>

<div class="form-check form-check-inline">
<input class="form-check-input" type="checkbox" name="penampilan[]" value="pakaian_tidak_sesuai">
<label class="form-check-label">Penggunaan pakaian tidak sesuai</label>
</div>

<div class="form-check form-check-inline">
<input class="form-check-input" type="checkbox" name="penampilan[]" value="berpakaian_tidak_biasa">
<label class="form-check-label">Cara berpakaian tidak seperti biasanya</label>
</div>
</div>
</div>


<!-- 2 Pembicaraan -->
<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>2. Pembicaraan</strong></label>

<div class="col-sm-10">

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="pembicaraan[]" value="cepat"> Cepat
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="pembicaraan[]" value="keras"> Keras
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="pembicaraan[]" value="gagap"> Gagap
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="pembicaraan[]" value="inkoheren"> Inkoheren
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="pembicaraan[]" value="apatis"> Apatis
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="pembicaraan[]" value="lambat"> Lambat
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="pembicaraan[]" value="membisu"> Membisu
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="pembicaraan[]" value="tidak_memulai"> Tidak mampu memulai pembicaraan
</label>
</div>
</div>


<!-- 3 Aktivitas Motorik -->
<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>3. Aktivitas Motorik</strong></label>

<div class="col-sm-10">

<label class="form-check-label me-3"><input class="form-check-input" type="checkbox" name="motorik[]" value="lesu"> Lesu</label>
<label class="form-check-label me-3"><input class="form-check-input" type="checkbox" name="motorik[]" value="tegang"> Tegang</label>
<label class="form-check-label me-3"><input class="form-check-input" type="checkbox" name="motorik[]" value="gelisah"> Gelisah</label>
<label class="form-check-label me-3"><input class="form-check-input" type="checkbox" name="motorik[]" value="agitasi"> Agitasi</label>
<label class="form-check-label me-3"><input class="form-check-input" type="checkbox" name="motorik[]" value="tik"> TIK</label>
<label class="form-check-label me-3"><input class="form-check-input" type="checkbox" name="motorik[]" value="grimasen"> Grimasen</label>
<label class="form-check-label me-3"><input class="form-check-input" type="checkbox" name="motorik[]" value="tremor"> Tremor</label>
<label class="form-check-label me-3"><input class="form-check-input" type="checkbox" name="motorik[]" value="kompulsif"> Kompulsif</label>
</div>
</div>

           <div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>4. Alam Perasaan</strong></label>

<div class="col-sm-10">

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="alam_perasaan[]" value="sedih"> Sedih
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="alam_perasaan[]" value="ketakutan"> Ketakutan
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="alam_perasaan[]" value="putus_asa"> Putus asa
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="alam_perasaan[]" value="khawatir"> Khawatir
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="alam_perasaan[]" value="gembira_berlebihan"> Gembira berlebihan
</label>
</div>

</div>

          
        <div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>5. Afek</strong></label>

<div class="col-sm-10">

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="afek[]" value="datar"> Datar
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="afek[]" value="tumpul"> Tumpul
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="afek[]" value="tidak_sesuai"> Tidak sesuai
</label>

</div>

</div>

<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>6. Interaksi selama wawancara</strong></label>

<div class="col-sm-10">

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="interaksi_wawancara[]" value="bermusuhan"> Bermusuhan
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="interaksi_wawancara[]" value="tidak_kooperatif"> Tidak kooperatif
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="interaksi_wawancara[]" value="mudah_tersinggung"> Mudah tersinggung
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="interaksi_wawancara[]" value="kontak_mata"> Kontak mata kurang
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="interaksi_wawancara[]" value="defensif"> Defensif
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="interaksi_wawancara[]" value="curiga"> Curiga
</label>
</div>

</div>
<div class="row mb-3">

<label class="col-sm-2 col-form-label"><strong>7. Persepsi - Sensorik</strong></label>

<div class="col-sm-10">

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="persepsi_sensorik[]" value="pendengaran"> Pendengaran
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="persepsi_sensorik[]" value="pengecapan"> Pengecapan
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="persepsi_sensorik[]" value="penglihatan"> Penglihatan
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="persepsi_sensorik[]" value="perabaan"> Perabaan
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="persepsi_sensorik[]" value="penghidu"> Penghidu
</label>

<br>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="ilusi[]" value="ada"> Ilusi Ada
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="ilusi[]" value="tidak_ada"> Ilusi Tidak Ada
</label>

</div>

</div>
            <!-- 8. Proses pikir -->
            <div class="row mb-3">

<label class="col-sm-2 col-form-label"><strong>8. Proses Pikir</strong></label>

<div class="col-sm-10">

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="proses_pikir[]" value="sirkumtansial"> Sirkumtansial
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="proses_pikir[]" value="tangensial"> Tangensial
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="proses_pikir[]" value="kehilangan_asosiasi"> Kehilangan asosiasi
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="proses_pikir[]" value="inkoheren"> Inkoheren
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="proses_pikir[]" value="flight_of_idea"> Flight of idea
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="proses_pikir[]" value="blocking"> Blocking
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="proses_pikir[]" value="pengulangan_pembicaraan"> Pengulangan pembicaraan
</label>

</div>

</div>

            <!-- 9. Isi pikir -->
            <div class="row mb-3">

<label class="col-sm-2 col-form-label"><strong>9. Isi Pikir</strong></label>

<div class="col-sm-10">

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="isi_pikir[]" value="obsesi"> Obsesi
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="isi_pikir[]" value="fobia"> Fobia
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="isi_pikir[]" value="hipokondria"> Hipokondria
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="isi_pikir[]" value="depersonalisasi"> Depersonalisasi
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="isi_pikir[]" value="ide_terkait"> Ide yang terkait
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="isi_pikir[]" value="pikiran_magis"> Pikiran magis
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="isi_pikir[]" value="waham"> Waham
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="isi_pikir[]" value="agama"> Agama
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="isi_pikir[]" value="somatik"> Somatik
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="isi_pikir[]" value="kebesaran"> Kebesaran
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="isi_pikir[]" value="curiga"> Curiga
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="isi_pikir[]" value="nihilistik"> Nihilistik
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="isi_pikir[]" value="sisip_pikir"> Sisip Pikir
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="isi_pikir[]" value="siar_pikir"> Siar Pikir
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="isi_pikir[]" value="kontrol_pikir"> Kontrol Pikir
</label>

</div>

</div>

            <!-- 10. Tingkat Kesadaran -->
           <div class="row mb-3">

<label class="col-sm-2 col-form-label"><strong>10. Tingkat Kesadaran</strong></label>

<div class="col-sm-10">

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="tingkat_kesadaran[]" value="bingung"> Bingung
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="tingkat_kesadaran[]" value="sedasi"> Sedasi
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="tingkat_kesadaran[]" value="disorientasi_waktu"> Disorientasi waktu
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="tingkat_kesadaran[]" value="disorientasi_orang"> Disorientasi orang
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="tingkat_kesadaran[]" value="disorientasi_tempat"> Disorientasi tempat
</label>
</div>

</div>
            <!-- 11. Memori -->
       <div class="row mb-3">

<label class="col-sm-2 col-form-label"><strong>11. Memori</strong></label>

<div class="col-sm-10">

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="memori[]" value="gangguan_daya_ingat_jangka_panjang"> Gangguan daya ingat jangka panjang
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="memori[]" value="gangguan_daya_ingat_jangka_pendek"> Gangguan daya ingat jangka pendek
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="memori[]" value="gangguan_daya_ingat_saat_ini"> Gangguan daya ingat saat ini
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="memori[]" value="konfabulasi"> Konfabulasi
</label>
</div>

</div>

            <!-- 12. Tingkat konsentrasi dan berhitung -->
           <div class="row mb-3">

<label class="col-sm-2 col-form-label"><strong>12. Tingkat Konsentrasi dan Berhitung</strong></label>

<div class="col-sm-10">

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="konsentrasi_berhitung[]" value="mudah_beralih"> Mudah beralih
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="konsentrasi_berhitung[]" value="tidak_berkonsentrasi"> Tidak mampu berkonsentrasi
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="konsentrasi_berhitung[]" value="tidak_berhitung"> Tidak mampu berhitung sederhana
</label>
</div>

</div>

            <!-- 13. Kemampuan penilaian -->
           <div class="row mb-3">

<label class="col-sm-2 col-form-label"><strong>13. Kemampuan Penilaian</strong></label>

<div class="col-sm-10">

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="kemampuan_penilaian[]" value="gangguan_ringan"> Gangguan ringan
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="kemampuan_penilaian[]" value="gangguan_bermakna"> Gangguan bermakna
</label>
</div>

</div>

            <!-- 14. Daya tilik diri -->
           <div class="row mb-3">

<label class="col-sm-2 col-form-label"><strong>14. Daya Tilik Diri</strong></label>

<div class="col-sm-10">

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="daya_tilik_diri[]" value="mengingkari_penyakit"> Mengingkari penyakit yang diderita
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="daya_tilik_diri[]" value="menyalahkan_diluar_diri"> Menyalahkan hal-hal di luar dirinya
</label>
</div>

</div>
 <div class="row mb-2">
                        <label class="col-sm-3 col-form-label text-primary">
                            <strong>VII.	STATUS MENTAL</strong>
                    </div>
              

            <!-- Makan -->
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label"><strong>1. Makan</strong></label>
                <div class="col-sm-9">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="makan" value="bantuan_minimal">
                        <label class="form-check-label">Bantuan minimal</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="makan" value="bantuan_partial">
                        <label class="form-check-label">Bantuan partial</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="makan" value="bantuan_total">
                        <label class="form-check-label">Bantuan total</label>
                    </div>
                </div>
            </div>

            <!-- BAB/BAK -->
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label"><strong>2. BAB/BAK</strong></label>
                <div class="col-sm-9">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="bab_bak" value="bantuan_minimal">
                        <label class="form-check-label">Bantuan minimal</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="bab_bak" value="bantuan_partial">
                        <label class="form-check-label">Bantuan partial</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="bab_bak" value="bantuan_total">
                        <label class="form-check-label">Bantuan total</label>
                    </div>
                </div>
            </div>

            <!-- Mandi -->
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label"><strong>3. Mandi</strong></label>
                <div class="col-sm-9">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="mandi" value="bantuan_minimal">
                        <label class="form-check-label">Bantuan minimal</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="mandi" value="bantuan_partial">
                        <label class="form-check-label">Bantuan partial</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="mandi" value="bantuan_total">
                        <label class="form-check-label">Bantuan total</label>
                    </div>
                </div>
            </div>

            <!-- Berpakian/berhias -->
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label"><strong>4. Berpakian/berhias</strong></label>
                <div class="col-sm-9">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="berpakian" value="bantuan_minimal">
                        <label class="form-check-label">Bantuan minimal</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="berpakian" value="bantuan_partial">
                        <label class="form-check-label">Bantuan partial</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="berpakian" value="bantuan_total">
                        <label class="form-check-label">Bantuan total</label>
                    </div>
                </div>
            </div>

            <!-- Istirahat/tidur -->
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label"><strong>5. Istirahat/tidur</strong></label>
                <div class="col-sm-9">
                    <label class="form-check-label me-4">Tidur siang: </label>
                    <input type="text" class="form-control d-inline" name="tidur_siang" style="width: 100px;">
                    <label class="form-check-label ms-4">s/d</label>
                    <input type="text" class="form-control d-inline" name="tidur_siang_sampai" style="width: 100px;">

                    
                    <label class="form-check-label me-4">Tidur malam: </label>
                    <input type="text" class="form-control d-inline" name="tidur_malam" style="width: 100px;">
                    <label class="form-check-label ms-4">s/d</label>
                    <input type="text" class="form-control d-inline" name="tidur_malam_sampai" style="width: 100px;">
                   
                    <label class="form-check-label me-4">Kegiatan sebelum/sesudah tidur: </label>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="kegiatan_tidur" value="tidak">
                        <label class="form-check-label">Ya</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="kegiatan_tidur" value="tidak">
                        <label class="form-check-label">Tidak</label>
                    </div>
                </div>
            </div>

            <!-- Penggunaan obat -->
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label"><strong>6. Penggunaan obat</strong></label>
                <div class="col-sm-9">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="penggunaan_obat" value="bantuan_minimal">
                        <label class="form-check-label">Bantuan minimal</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="penggunaan_obat" value="bantuan_partial">
                        <label class="form-check-label">Bantuan partial</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="penggunaan_obat" value="bantuan_total">
                        <label class="form-check-label">Bantuan total</label>
                    </div>
                </div>
            </div>

            <!-- Pemeliharaan kesehatan -->
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label"><strong>7. Pemeliharaan kesehatan</strong></label>
                <div class="col-sm-9">
                    <label class="form-check-label me-3">Perawatan lanjutan: </label>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="perawatan_lanjutan" value="ya">
                        <label class="form-check-label">Ya</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="perawatan_lanjutan" value="tidak">
                        <label class="form-check-label">Tidak</label>
                    </div>

                    <br>

                    <label class="form-check-label me-3">Perawatan pendukung: </label>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="perawatan_pendukung" value="ya">
                        <label class="form-check-label">Ya</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="perawatan_pendukung" value="tidak">
                        <label class="form-check-label">Tidak</label>
                    </div>
                </div>
            </div>

            <!-- Kegiatan di dalam rumah -->
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label"><strong>8. Kegiatan di dalam rumah</strong></label>
                <div class="col-sm-9">
                    <label class="form-check-label me-3">Mempersiapkan makanan: </label>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="memasak" value="ya">
                        <label class="form-check-label">Ya</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="memasak" value="tidak">
                        <label class="form-check-label">Tidak</label>
                    </div>

                    <br>

                    <label class="form-check-label me-3">Menjaga kerapian di rumah: </label>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="menjaga_kerapian" value="ya">
                        <label class="form-check-label">Ya</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="menjaga_kerapian" value="tidak">
                        <label class="form-check-label">Tidak</label>
                    </div>

                    <br>

                    <label class="form-check-label me-3">Mencuci pakaian: </label>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="mencuci_pakaian" value="ya">
                        <label class="form-check-label">Ya</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="mencuci_pakaian" value="tidak">
                        <label class="form-check-label">Tidak</label>
                    </div>

                    <br>

                    <label class="form-check-label me-3">Pengaturan keuangan: </label>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="pengaturan_keuangan" value="ya">
                        <label class="form-check-label">Ya</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="pengaturan_keuangan" value="tidak">
                        <label class="form-check-label">Tidak</label>
                    </div>
                </div>
            </div>

            <!-- Kegiatan di luar rumah -->
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label"><strong>9. Kegiatan di luar rumah</strong></label>
                <div class="col-sm-9">
                    <label class="form-check-label me-3">Belanja: </label>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="belanja" value="ya">
                        <label class="form-check-label">Ya</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="belanja" value="tidak">
                        <label class="form-check-label">Tidak</label>
                    </div>

                    <br>

                    <label class="form-check-label me-3">Transportasi: </label>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="transportasi" value="ya">
                        <label class="form-check-label">Ya</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="transportasi" value="tidak">
                        <label class="form-check-label">Tidak</label>
                    </div>

                    <br>

                    <label class="form-check-label me-3">Lain-lain: </label>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="lain_lain" value="ya">
                        <label class="form-check-label">Ya</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="lain_lain" value="tidak">
                        <label class="form-check-label">Tidak</label>
                    </div>
                </div>
            </div>

            <!-- Penjelasan -->
             
            <div class="row mb-3">
                    <label for="penjelasan" class="col-sm-2 col-form-label"><strong>Penjelasan</strong></label>
                    <div class="col-sm-10">
                        <textarea name="penjelasan" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                         </div>
                    </div>
<div class="row mb-3">
 <div class="row mb-2">
                        <label class="col-sm-5 col-form-label text-primary">
                            <strong>VIII. Mekanisme Koping</strong>
                    </div>
 <div class="row mb-3">
                    <label for="agamaistri" class="col-sm-2 col-form-label"><strong>Mekanisme Koping</strong></label>
                    <div class="col-sm-10">
                       

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="psikososial[]" value="adaptif"> Adaptif
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="psikososial[]" value="maladaptif"> Maladaptif
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="psikososial[]" value="bicara_dengan_orang_lain"> Bicara dengan orang lain
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="psikososial[]" value="minum_alcohol"> Minum alcohol
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="psikososial[]" value="mampu_menyelesaikan_masalah"> Mampu menyelesaikan masalah
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="psikososial[]" value="reaksi_lambat_berlebih"> Reaksi lambat / berlebih
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="psikososial[]" value="teknik_relaksasi"> Teknik relaksasi
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="psikososial[]" value="bekerja_berlebihan"> Bekerja berlebihan
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="psikososial[]" value="aktivitas_konstruktif"> Aktivitas konstruktif
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="psikososial[]" value="menghindar"> Menghindar
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="psikososial[]" value="olahraga"> Olahraga
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="psikososial[]" value="mencederai_diri"> Mencederai diri
</label>
</div>

</div>

</div>
 <div class="row mb-2">
                        <label class="col-sm-5 col-form-label text-primary">
                            <strong>IX. Masalah Psikososial dan Lingkungan</strong>
                    </div>

<!-- Dukungan Kelompok -->
<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>Masalah dengan dukungan kelompok, Uraikan</strong></label>

<div class="col-sm-10">

<textarea name="dukungan_kelompok" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
</div>
</div>


<!-- Lingkungan -->
<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>Masalah dengan lingkungan, Uraikan</strong></label>

<div class="col-sm-10">

<textarea name="masalah_lingkungan" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
</div>
</div>


<!-- Pendidikan -->
<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>Masalah dengan pendidikan, Uraikan</strong></label>

<div class="col-sm-10">
<textarea name="masalah_pendidikan" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
</div>
</div>


<!-- Pekerjaan -->
<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>Masalah dengan pekerjaan, Uraikan</strong></label>

<div class="col-sm-10">

<textarea name="masalah_pekerjaan" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
</div>
</div>


<!-- Perumahan -->
<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>Masalah dengan perumahan, Uraikan</strong></label>

<div class="col-sm-10">

<textarea name="masalah_perumahan" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
</div>
</div>


<!-- Ekonomi -->
<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>Masalah dengan ekonomi, Uraikan</strong></label>

<div class="col-sm-10">
<textarea name="masalah_ekonomi" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
</div>
</div>


<!-- Pelayanan Kesehatan -->
<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>Masalah dengan pelayanan kesehatan, Uraikan</strong></label>

<div class="col-sm-10">

<textarea name="masalah_pelayanan_kesehatan" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
</div>
</div>


<!-- Masalah Lain -->
<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>Masalah lain, Uraikan</strong></label>

<div class="col-sm-10">
<textarea name="masalah_lain" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
</div>
</div><div class="row mb-3">
 <div class="row mb-2">
                        <label class="col-sm-5 col-form-label text-primary">
                            <strong>X. Pengetahuan Kurang Tentang</strong>
                    </div>
 <div class="row mb-3">
                    <label for="agamaistri" class="col-sm-2 col-form-label"><strong>Pengetahuan Kurang Tentang</strong></label>
                    <div class="col-sm-10">
                       

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="pengetahuan[]" value="penyakit_jiwa">
Penyakit Jiwa
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="pengetahuan[]" value="sistem_pendukung">
Sistem Pendukung
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="pengetahuan[]" value="faktor_presipitasi">
Faktor Presipitasi
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="pengetahuan[]" value="penyakit_fisik">
Penyakit Fisik
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="pengetahuan[]" value="koping">
Koping
</label>

<label class="form-check-label me-3">
<input class="form-check-input" type="checkbox" name="pengetahuan[]" value="obat_obatan">
Obat-obatan
</label>

</div>

</div>
      
                         <?php include "tab_navigasi.php"; ?>

</section>
</main>
