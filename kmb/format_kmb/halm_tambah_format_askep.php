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
        <h1><strong>KEPERAWATAN MEDIKAL BEDAH </strong></h1>
    </div><!-- End Page Title -->
    <br>


   <ul class="nav nav-tabs custom-tabs">

    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'demografi' ? 'active' : '' ?>"
        href="index.php?page=kmb/format_kmb&tab=demografi"> Format Laporan Pendahuluan (LP) Keperawatan Medikal Bedah  </a>
    </li>

    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'format_askep' ? 'active' : '' ?>"
        href="index.php?page=kmb/format_kmb&tab=format_askep"> Format Askep KMB </a>
    </li>
        <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'klasifikasi_data' ? 'active' : '' ?>"
        href="index.php?page=kmb/format_kmb&tab=klasifikasi_data"> Klasifikasi Data </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'analisa' ? 'active' : '' ?>"
        href="index.php?page=kmb/format_kmb&tab=analisa"> Analisa Data </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'diagnosa_keperawatan' ? 'active' : '' ?>"
        href="index.php?page=kmb/format_kmb&tab=diagnosa_keperawatan"> Diagnosa Keperawatan </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'rencana' ? 'active' : '' ?>"
        href="index.php?page=kmb/format_kmb&tab=rencana"> Rencana Keperawatan </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'implementasi_keperawatan' ? 'active' : '' ?>"
        href="index.php?page=kmb/format_kmb&tab=implementasi_keperawatan"> Implementasi Keperawatan </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'evaluasi_keperawatan' ? 'active' : '' ?>"
        href="index.php?page=kmb/format_kmb&tab=evaluasi_keperawatan"> Evaluasi Keperawatan </a>
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
              <h5 class="card-title mb-1"><strong>Format Pengkajian Askep Ruang Perawatan Dahlia</strong></h5>

                <!-- General Form Elements -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

                <!-- Bagian Diagnosa -->

                    <!-- A PENGKAJIAN -->
<div class="row mb-2">
<label class="col-sm-12 text-primary">
<strong>A. PENGKAJIAN</strong>
</label>
</div>

<div class="row mb-2">
<label class="col-sm-12">
<strong>1. Identitas</strong>
</label>
</div>

<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>a. Klien </strong></label>
</div>
<!-- NAMA KLIEN -->
<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong> Nama (Inisial) </strong></label>

<div class="col-sm-9">
<input type="text" class="form-control" name="nama_klien">

<textarea class="form-control mt-2" rows="2"
placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!"
readonly></textarea>
</div>

<div class="col-sm-1">
<div class="form-check">
<input class="form-check-input" type="checkbox">
</div>
</div>
</div>

<!-- TTL / UMUR -->
<div class="row mb-3">
<label class="col-sm-2 col-form-label">
<strong>Tempat/Tgl Lahir/Umur</strong>
</label>

<div class="col-sm-9">
<input type="text" class="form-control" name="ttl_umur">

<textarea class="form-control mt-2" rows="2"
placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!"
readonly></textarea>
</div>

<div class="col-sm-1">
<div class="form-check">
<input class="form-check-input" type="checkbox">
</div>
</div>
</div>

<!-- JENIS KELAMIN -->
<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>Jenis Kelamin</strong></label>

<div class="col-sm-9">
<select class="form-control" name="jenis_kelamin">
<option value="">-- Pilih --</option>
<option value="Laki-laki">Laki-laki</option>
<option value="Perempuan">Perempuan</option>
</select>

<textarea class="form-control mt-2" rows="2"
placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!"
readonly></textarea>
</div>

<div class="col-sm-1">
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
placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!"
readonly></textarea>
</div>

<div class="col-sm-1">
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
placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!"
readonly></textarea>
</div>

<div class="col-sm-1">
<div class="form-check">
<input class="form-check-input" type="checkbox">
</div>
</div>
</div>

<!-- PENDIDIKAN -->
<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>Pendidikan</strong></label>

<div class="col-sm-9">
<input type="text" class="form-control" name="pendidikan">

<textarea class="form-control mt-2" rows="2"
placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!"
readonly></textarea>
</div>

<div class="col-sm-1">
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
placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!"
readonly></textarea>
</div>

<div class="col-sm-1">
<div class="form-check">
<input class="form-check-input" type="checkbox">
</div>
</div>
</div>

<!-- ALAMAT -->
<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>Alamat</strong></label>

<div class="col-sm-9">
<textarea class="form-control" rows="3" name="alamat"></textarea>

<textarea class="form-control mt-2" rows="2"
placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!"
readonly></textarea>
</div>

<div class="col-sm-1">
<div class="form-check">
<input class="form-check-input" type="checkbox">
</div>
</div>
</div>

<!-- TANGGAL MASUK RS -->
<div class="row mb-3">
<label class="col-sm-2 col-form-label">
<strong>Tanggal Masuk RS</strong>
</label>

<div class="col-sm-9">
<input type="date" class="form-control" name="tgl_masuk_rs">

<textarea class="form-control mt-2" rows="2"
placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!"
readonly></textarea>
</div>

<div class="col-sm-1">
<div class="form-check">
<input class="form-check-input" type="checkbox">
</div>
</div>
</div>

<!-- TANGGAL PENGKAJIAN -->
<div class="row mb-3">
<label class="col-sm-2 col-form-label">
<strong>Tanggal Pengkajian</strong>
</label>

<div class="col-sm-9">
<input type="date" class="form-control" name="tgl_pengkajian">

<textarea class="form-control mt-2" rows="2"
placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!"
readonly></textarea>
</div>

<div class="col-sm-1">
<div class="form-check">
<input class="form-check-input" type="checkbox">
</div>
</div>
</div>

<!-- DIAGNOSA MEDIK -->
<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>Diagnosa Medik</strong></label>

<div class="col-sm-9">
<input type="text" class="form-control" name="diagnosa_medik">

<textarea class="form-control mt-2" rows="2"
placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!"
readonly></textarea>
</div>

<div class="col-sm-1">
<div class="form-check">
<input class="form-check-input" type="checkbox">
</div>
</div>
</div>

<!-- GOLONGAN DARAH -->
<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>Golongan Darah</strong></label>

<div class="col-sm-9">
<input type="text" class="form-control" name="golongan_darah">

<textarea class="form-control mt-2" rows="2"
placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!"
readonly></textarea>
</div>

<div class="col-sm-1">
<div class="form-check">
<input class="form-check-input" type="checkbox">
</div>
</div>
</div>

<!-- NO REGISTRASI -->
<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>No Registrasi</strong></label>

<div class="col-sm-9">
<input type="text" class="form-control" name="no_registrasi">

<textarea class="form-control mt-2" rows="2"
placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!"
readonly></textarea>
</div>

<div class="col-sm-1">
<div class="form-check">
<input class="form-check-input" type="checkbox">
</div>
</div>
</div>

<!-- RUANGAN -->
<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>Ruangan</strong></label>

<div class="col-sm-9">
<input type="text" class="form-control" name="ruangan">

<textarea class="form-control mt-2" rows="2"
placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!"
readonly></textarea>
</div>

<div class="col-sm-1">
<div class="form-check">
<input class="form-check-input" type="checkbox">
</div>
</div>
</div>


<!-- IDENTITAS PENANGGUNG -->
<div class="row mb-2">
<label class="col-sm-12 text-primary">
<strong>b. Identitas Penanggung Jawab</strong>
</label>
</div>

<!-- NAMA PENANGGUNG -->
<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>Nama (Inisial)</strong></label>

<div class="col-sm-9">
<input type="text" class="form-control" name="nama_penanggung">

<textarea class="form-control mt-2" rows="2"
placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!"
readonly></textarea>
</div>

<div class="col-sm-1">
<div class="form-check">
<input class="form-check-input" type="checkbox">
</div>
</div>
</div>

<!-- TTL PENANGGUNG -->
<div class="row mb-3">
<label class="col-sm-2 col-form-label">
<strong>Tempat/Tgl Lahir/Umur</strong>
</label>

<div class="col-sm-9">
<input type="text" class="form-control" name="ttl_penanggung">

<textarea class="form-control mt-2" rows="2"
placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!"
readonly></textarea>
</div>

<div class="col-sm-1">
<div class="form-check">
<input class="form-check-input" type="checkbox">
</div>
</div>
</div>

<!-- JENIS KELAMIN -->
<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>Jenis Kelamin</strong></label>

<div class="col-sm-9">
<select class="form-control" name="jenis_kelamin">
<option value="">-- Pilih --</option>
<option value="Laki-laki">Laki-laki</option>
<option value="Perempuan">Perempuan</option>
</select>
<textarea class="form-control mt-2" rows="2"
placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!"
readonly></textarea>
</div>

<div class="col-sm-1">
<div class="form-check">
<input class="form-check-input" type="checkbox">
</div>
</div>
</div>
<!-- HUBUNGAN -->
<div class="row mb-3">
<label class="col-sm-2 col-form-label">
<strong>Hubungan dengan Klien</strong>
</label>

<div class="col-sm-9">
<input type="text" class="form-control" name="hubungan_klien">

<textarea class="form-control mt-2" rows="2"
placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!"
readonly></textarea>
</div>

<div class="col-sm-1">
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
placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!"
readonly></textarea>
</div>

<div class="col-sm-1">
<div class="form-check">
<input class="form-check-input" type="checkbox">
</div>
</div>
</div>

<!-- PENDIDIKAN -->
<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>Pendidikan</strong></label>

<div class="col-sm-9">
<input type="text" class="form-control" name="pendidikan">

<textarea class="form-control mt-2" rows="2"
placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!"
readonly></textarea>
</div>

<div class="col-sm-1">
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
placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!"
readonly></textarea>
</div>

<div class="col-sm-1">
<div class="form-check">
<input class="form-check-input" type="checkbox">
</div>
</div>
</div>

<!-- ALAMAT -->
<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>Alamat</strong></label>

<div class="col-sm-9">
<textarea class="form-control" rows="3" name="alamat"></textarea>

<textarea class="form-control mt-2" rows="2"
placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!"
readonly></textarea>
</div>

<div class="col-sm-1">
<div class="form-check">
<input class="form-check-input" type="checkbox">
</div>
</div>
</div>
</form>
</div>
        </div>
        <div class="card">
             <div class="card-body">
                                
<!-- 2 KEADAAN UMUM -->
                     <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

                    <h5 class="card-title"><strong>2. Keadaan Umum</strong></h5>



<!-- A TANDA VITAL -->
<div class="row mb-2">
<label class="col-sm-12 text-primary"><strong>a. Tanda Vital</strong></label>
</div>
<!-- TD -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Nadi</strong></label>

    <div class="col-sm-9">
        <div class="input-group">
            <input type="text" class="form-control" name="td">
            <span class="input-group-text">/menit</span>
        </div>

        <textarea class="form-control mt-2" id="commenttd" rows="2"
        placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!"
        style="display:block; overflow:hidden; resize:none;"
        oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
        readonly></textarea>
    </div>

<div class="col-sm-1">
<div class="form-check">
<input class="form-check-input" type="checkbox">
        </div>
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Pernafasan</strong></label>

    <div class="col-sm-9">
        <div class="input-group">
            <input type="text" class="form-control" name="td">
            <span class="input-group-text">x/menit</span>
        </div>

        <textarea class="form-control mt-2" id="commenttd" rows="2"
        placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!"
        style="display:block; overflow:hidden; resize:none;"
        oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
        readonly></textarea>
    </div>



<div class="col-sm-1">
<div class="form-check">
<input class="form-check-input" type="checkbox">
</div>
</div>
</div>


<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>TD (Tekanan Darah)</strong></label>

    <div class="col-sm-9">
        <div class="input-group">
            <input type="text" class="form-control" name="td">
            <span class="input-group-text">mmHg</span>
        </div>

        <textarea class="form-control mt-2" id="commenttd" rows="2"
        placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!"
        style="display:block; overflow:hidden; resize:none;"
        oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
        readonly></textarea>
    </div>



<div class="col-sm-1">
<div class="form-check">
<input class="form-check-input" type="checkbox">
</div>
</div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Suhu</strong></label>

    <div class="col-sm-9">
        <div class="input-group">
            <input type="text" class="form-control" name="td">
            <span class="input-group-text">°C</span>
        </div>

        <textarea class="form-control mt-2" id="commenttd" rows="2"
        placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!"
        style="display:block; overflow:hidden; resize:none;"
        oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
        readonly></textarea>
    </div>



<div class="col-sm-1">
<div class="form-check">
<input class="form-check-input" type="checkbox">
</div>
</div>
</div>



<!-- B KESADARAN -->
<div class="row mb-2">
<label class="col-sm-12 text-primary"><strong>b. Kesadaran</strong></label>
</div>

<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>Glasgow Coma Scale (GCS)</strong></label>

<div class="col-sm-9">
<input type="text" class="form-control" name="gcs" placeholder="M : V : E">

<textarea class="form-control mt-2" rows="2"
placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!"
readonly></textarea>
</div>

<div class="col-sm-1">
<div class="form-check">
<input class="form-check-input" type="checkbox">
</div>
</div>
</div>


<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>Tingkat Kesadaran</strong></label>

<div class="col-sm-9">

<div class="form-check-inline">
<input class="form-check-input" type="checkbox" name="kesadaran[]" value="Kompos Mentis">
<label class="form-check-label">Kompos Mentis</label>
</div>

<div class="form-check-inline">
<input class="form-check-input" type="checkbox" name="kesadaran[]" value="Apatis">
<label class="form-check-label">Apatis</label>
</div>

<div class="form-check-inline">
<input class="form-check-input" type="checkbox" name="kesadaran[]" value="Somnolent">
<label class="form-check-label">Somnolent</label>
</div>

<div class="form-check-inline">
<input class="form-check-input" type="checkbox" name="kesadaran[]" value="Stupor">
<label class="form-check-label">Stupor / Suppor</label>
</div>

<div class="form-check-inline">
<input class="form-check-input" type="checkbox" name="kesadaran[]" value="Semikoma">
<label class="form-check-label">Semikoma</label>
</div>

<div class="form-check-inline">
<input class="form-check-input" type="checkbox" name="kesadaran[]" value="Koma">
<label class="form-check-label">Koma</label>
</div>

<textarea class="form-control mt-2" rows="2"
placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!"
readonly></textarea>

</div>

<div class="col-sm-1">
<div class="form-check">
<input class="form-check-input" type="checkbox">
</div>
</div>
</div>


<!-- C ANTROPOMETRI -->
<div class="row mb-2">
<label class="col-sm-12 text-primary"><strong>c. Antropometri</strong></label>
</div>


<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>BB Sebelum Sakit</strong></label>

<div class="col-sm-9">
<input type="text" class="form-control" name="bb_sebelum">

<textarea class="form-control mt-2" rows="2"
placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!"
readonly></textarea>
</div>

<div class="col-sm-1">
<div class="form-check">
<input class="form-check-input" type="checkbox">
</div>
</div>
</div>


<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>BB Saat Sakit</strong></label>

<div class="col-sm-9">
<input type="text" class="form-control" name="bb_saat_sakit">

<textarea class="form-control mt-2" rows="2"
placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!"
readonly></textarea>
</div>

<div class="col-sm-1">
<div class="form-check">
<input class="form-check-input" type="checkbox">
</div>
</div>
</div>


<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>Lingkar Lengan Atas</strong></label>

<div class="col-sm-9">
<input type="text" class="form-control" name="lingkar_lengan">

<textarea class="form-control mt-2" rows="2"
placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!"
readonly></textarea>
</div>

<div class="col-sm-1">
<div class="form-check">
<input class="form-check-input" type="checkbox">
</div>
</div>
</div>


<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>Tinggi Badan</strong></label>

<div class="col-sm-9">
<input type="text" class="form-control" name="tinggi_badan">

<textarea class="form-control mt-2" rows="2"
placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!"
readonly></textarea>
</div>

<div class="col-sm-1">
<div class="form-check">
<input class="form-check-input" type="checkbox">
</div>
</div>
</div>


<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>Indeks Massa Tubuh (IMT)</strong></label>

<div class="col-sm-9">
<input type="text" class="form-control" name="imt">

<textarea class="form-control mt-2" rows="2"
placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!"
readonly></textarea>
</div>

<div class="col-sm-1">
<div class="form-check">
<input class="form-check-input" type="checkbox">
</div>
</div>
</div>
                     </form>
                     </div>
                     </div>

<!-- 3 RIWAYAT KESEHATAN -->
 <div class="card">
             <div class="card-body">
                                
                     <!-- General Form Elements -->
                    <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

                    <h5 class="card-title"><strong>3. Riwayat Kesehatan</strong></h5>


<!-- A ALASAN MASUK RS -->
<div class="row mb-3">
<label class="col-sm-2 col-form-label">
<strong>a. Alasan Masuk Rumah Sakit</strong>
</label>

<div class="col-sm-9">
<textarea class="form-control" rows="3" name="alasan_masuk_rs"></textarea>

<textarea class="form-control mt-2" rows="2"
placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!"
readonly></textarea>
</div>

<div class="col-sm-1">
<div class="form-check">
<input class="form-check-input" type="checkbox">
</div>
</div>
</div>

<!-- B KELUHAN UTAMA -->
<div class="row mb-3">
<label class="col-sm-2 col-form-label">
<strong>b. Keluhan Utama</strong>
</label>

<div class="col-sm-9">
<textarea class="form-control" rows="3" name="keluhan_utama"></textarea>

<textarea class="form-control mt-2" rows="2"
placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!"
readonly></textarea>
</div>

<div class="col-sm-1">
<div class="form-check">
<input class="form-check-input" type="checkbox">
</div>
</div>
</div>

<!-- C RIWAYAT KELUHAN UTAMA -->
<div class="row mb-3">
<label class="col-sm-2 col-form-label">
<strong>c. Riwayat Keluhan Utama</strong>
</label>

<div class="col-sm-9">
<textarea class="form-control" rows="4" name="riwayat_keluhan_utama"></textarea>

<textarea class="form-control mt-2" rows="2"
placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!"
readonly></textarea>
</div>

<div class="col-sm-1">
<div class="form-check">
<input class="form-check-input" type="checkbox">
</div>
</div>
</div>

<!-- D RIWAYAT KESEHATAN YANG LALU -->
<div class="row mb-3">
<label class="col-sm-2 col-form-label">
<strong>d. Riwayat Kesehatan yang Lalu</strong>
</label>

<div class="col-sm-9">

<small class="form-text" style="color: red;">Bentuk kepala, Penyebaran, Kebersihan, Warna Rambut. Hasil:</small>

<textarea class="form-control" rows="4" name="riwayat_kesehatan_lalu"></textarea>

<textarea class="form-control mt-2" rows="2"
placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!"
readonly></textarea>

</div>

<div class="col-sm-1">
<div class="form-check">
<input class="form-check-input" type="checkbox">
</div>
</div>
</div>

<!-- E RIWAYAT KESEHATAN KELUARGA -->
<div class="row mb-3">
<label class="col-sm-2 col-form-label">
<strong>e. Riwayat Kesehatan Keluarga</strong>
</label>

<div class="col-sm-9">
<textarea class="form-control" rows="4" name="riwayat_kesehatan_keluarga"></textarea>

<textarea class="form-control mt-2" rows="2"
placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!"
readonly></textarea>
</div>

<div class="col-sm-1">
<div class="form-check">
<input class="form-check-input" type="checkbox">
</div>
</div>
</div>

<!-- F GENOGRAM -->
<div class="row mb-3">
<label class="col-sm-2 col-form-label">
<strong>f. Genogram</strong>
</label>

<div class="col-sm-9">

<textarea class="form-control" rows="4" name="Genogram"></textarea>

<textarea class="form-control mt-2" rows="2"
placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!"
readonly></textarea>

</div>

<div class="col-sm-1">
<div class="form-check">
<input class="form-check-input" type="checkbox">
</div>
</div>
</div>
</form>
</div>
</div>
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
<textarea class="form-control" rows="2" name="merokok"></textarea>

<textarea class="form-control mt-2" rows="2"
placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
</div>

<div class="col-sm-1">
<div class="form-check">
<input class="form-check-input" type="checkbox">
</div>
</div>
</div>

<!-- 2 -->
<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>2. Pemeriksaan kesehatan rutin?</strong></label>

<div class="col-sm-9">
<textarea class="form-control" rows="2" name="pemeriksaan_rutin"></textarea>

<textarea class="form-control mt-2" rows="2"
placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
</div>

<div class="col-sm-1">
<div class="form-check">
<input class="form-check-input" type="checkbox">
</div>
</div>
</div>

<!-- 3 -->
<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>3. Pendapat pasien tentang keadaan kesehatannya saat ini</strong></label>

<div class="col-sm-9">
<textarea class="form-control" rows="3" name="pendapat_kesehatan"></textarea>

<textarea class="form-control mt-2" rows="2"
placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
</div>

<div class="col-sm-1">
<div class="form-check">
<input class="form-check-input" type="checkbox">
</div>
</div>
</div>

<!-- 4 -->
<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>4. Persepsi pasien tentang berat ringannya penyakit</strong></label>

<div class="col-sm-9">
<textarea class="form-control" rows="3" name="persepsi_penyakit"></textarea>

<textarea class="form-control mt-2" rows="2"
placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
</div>

<div class="col-sm-1">
<div class="form-check">
<input class="form-check-input" type="checkbox">
</div>
</div>
</div>

<!-- 5 -->
<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>5. Persepsi tentang tingkat kesembuhan</strong></label>

<div class="col-sm-9">
<textarea class="form-control" rows="3" name="tingkat_kesembuhan"></textarea>

<textarea class="form-control mt-2" rows="2"
placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
</div>

<div class="col-sm-1">
<div class="form-check">
<input class="form-check-input" type="checkbox">
</div>
</div>
</div>

<!-- B POLA AKTIVITAS -->
<div class="row mb-2">
<label class="col-sm-12 text-primary"><strong>b. Pola Aktivitas dan Latihan</strong></label>
</div>

<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>1. Rutinitas mandi</strong></label>

<div class="col-sm-9">
<small class="form-text" style="color:red;">kapan, bagaimana, dimana, sabun yang digunakan</small>
<textarea class="form-control" rows="3" name="rutinitas_mandi"></textarea>

<textarea class="form-control mt-2" rows="2"
placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
</div>

<div class="col-sm-1">
<div class="form-check">
<input class="form-check-input" type="checkbox">
</div>
</div>
</div>

<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>2. Kebersihan sehari-hari</strong></label>

<div class="col-sm-9">
<small class="form-text" style="color:red;">pakaian dll</small>
<textarea class="form-control" rows="3" name="kebersihan"></textarea>

<textarea class="form-control mt-2" rows="2"
placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
</div>

<div class="col-sm-1">
<div class="form-check">
<input class="form-check-input" type="checkbox">
</div>
</div>
</div>

<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>3. Aktivitas sehari-hari</strong></label>

<div class="col-sm-9">
<small class="form-text" style="color:red;">jenis pekerjaan, lamanya, dll</small>
<textarea class="form-control" rows="3" name="aktivitas"></textarea>

<textarea class="form-control mt-2" rows="2"
placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
</div>

<div class="col-sm-1">
<div class="form-check">
<input class="form-check-input" type="checkbox">
</div>
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
    <label class="col-sm-3 col-form-label">
        <strong>1. Nyeri (kualitas, intensitas, durasi, skala nyeri, cara mengurangi nyeri)</strong>
    </label>
    <div class="col-sm-8">
        <textarea class="form-control" rows="3" name="nyeri"></textarea>
        <textarea class="form-control mt-2" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1">
        <div class="form-check">
            <input class="form-check-input" type="checkbox">
        </div>
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-3 col-form-label">
        <strong>2. Fungsi panca indra (penglihatan, pendengaran, pengecapan, penghidu, perasa) menggunakan alat bantu?</strong>
    </label>
    <div class="col-sm-8">
        <textarea class="form-control" rows="3" name="panca_indra"></textarea>
        <textarea class="form-control mt-2" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1">
        <div class="form-check">
            <input class="form-check-input" type="checkbox">
        </div>
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-3 col-form-label">
        <strong>3. Kemampuan berbicara</strong>
    </label>
    <div class="col-sm-8">
        <textarea class="form-control" rows="3" name="berbicara"></textarea>
        <textarea class="form-control mt-2" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1">
        <div class="form-check">
            <input class="form-check-input" type="checkbox">
        </div>
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-3 col-form-label">
        <strong>4. Kemampuan membaca</strong>
    </label>
    <div class="col-sm-8">
        <textarea class="form-control" rows="3" name="membaca"></textarea>
        <textarea class="form-control mt-2" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1">
        <div class="form-check">
            <input class="form-check-input" type="checkbox">
        </div>
    </div>
</div>

<hr>

<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>d. Pola Konsep Diri</strong></label>
</div>

<div class="row mb-3">
    <label class="col-sm-3 col-form-label">
        <strong>1. Bagaimana klien memandang dirinya</strong>
    </label>
    <div class="col-sm-8">
        <textarea class="form-control" rows="3" name="konsep_diri"></textarea>
        <textarea class="form-control mt-2" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1">
        <div class="form-check">
            <input class="form-check-input" type="checkbox">
        </div>
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-3 col-form-label">
        <strong>2. Hal-hal yang disukai klien mengenai dirinya</strong>
    </label>
    <div class="col-sm-8">
        <textarea class="form-control" rows="3" name="hal_disukai"></textarea>
        <textarea class="form-control mt-2" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1">
        <div class="form-check">
            <input class="form-check-input" type="checkbox">
        </div>
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-3 col-form-label">
        <strong>3. Apakah klien dapat mengidentifikasi kekuatan dan kelemahan dirinya</strong>
    </label>
    <div class="col-sm-8">
        <textarea class="form-control" rows="3" name="kekuatan_kelemahan"></textarea>
        <textarea class="form-control mt-2" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1">
        <div class="form-check">
            <input class="form-check-input" type="checkbox">
        </div>
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-3 col-form-label">
        <strong>4. Hal-hal yang dapat dilakukan klien secara baik</strong>
    </label>
    <div class="col-sm-8">
        <textarea class="form-control" rows="3" name="kemampuan_baik"></textarea>
        <textarea class="form-control mt-2" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1">
        <div class="form-check">
            <input class="form-check-input" type="checkbox">
        </div>
    </div>
</div>

<hr>

<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>e. Pola Koping</strong></label>
</div>

<div class="row mb-3">
    <label class="col-sm-3 col-form-label">
        <strong>1. Masalah utama selama masuk RS (keuangan, dll)</strong>
    </label>
    <div class="col-sm-8">
        <textarea class="form-control" rows="3" name="masalah_rs"></textarea>
        <textarea class="form-control mt-2" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1">
        <div class="form-check">
            <input class="form-check-input" type="checkbox">
        </div>
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-3 col-form-label">
        <strong>2. Kehilangan atau perubahan yang terjadi sebelumnya</strong>
    </label>
    <div class="col-sm-8">
        <textarea class="form-control" rows="3" name="kehilangan"></textarea>
        <textarea class="form-control mt-2" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1">
        <div class="form-check">
            <input class="form-check-input" type="checkbox">
        </div>
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-3 col-form-label">
        <strong>3. Takut terhadap kekerasan</strong>
    </label>
    <div class="col-sm-8">
        <textarea class="form-control" rows="3" name="takut_kekerasan"></textarea>
        <textarea class="form-control mt-2" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1">
        <div class="form-check">
            <input class="form-check-input" type="checkbox">
        </div>
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-3 col-form-label">
        <strong>4. Pandangan terhadap masa depan</strong>
    </label>
    <div class="col-sm-8">
        <textarea class="form-control" rows="3" name="masa_depan"></textarea>
        <textarea class="form-control mt-2" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1">
        <div class="form-check">
            <input class="form-check-input" type="checkbox">
        </div>
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-3 col-form-label">
        <strong>5. Mekanisme koping saat menghadapi masalah</strong>
    </label>
    <div class="col-sm-8">
        <textarea class="form-control" rows="3" name="mekanisme_koping"></textarea>
        <textarea class="form-control mt-2" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1">
        <div class="form-check">
            <input class="form-check-input" type="checkbox">
        </div>
    </div>
</div>
<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>f. Pola Seksual - Reproduksi</strong></label>
</div>

<div class="row mb-3">
    <label class="col-sm-3 col-form-label">
        <strong>1. Masalah menstruasi</strong>
    </label>
    <div class="col-sm-8">
        <textarea class="form-control" rows="3" name="masalah_menstruasi"></textarea>
        <textarea class="form-control mt-2" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1">
        <div class="form-check">
            <input class="form-check-input" type="checkbox">
        </div>
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-3 col-form-label">
        <strong>2. Papsmear terakhir</strong>
    </label>
    <div class="col-sm-8">
        <textarea class="form-control" rows="3" name="papsmear"></textarea>
        <textarea class="form-control mt-2" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1">
        <div class="form-check">
            <input class="form-check-input" type="checkbox">
        </div>
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-3 col-form-label">
        <strong>3. Perawatan payudara setiap bulan</strong>
    </label>
    <div class="col-sm-8">
        <textarea class="form-control" rows="3" name="perawatan_payudara"></textarea>
        <textarea class="form-control mt-2" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1">
        <div class="form-check">
            <input class="form-check-input" type="checkbox">
        </div>
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-3 col-form-label">
        <strong>4. Apakah ada kesukaran dalam berhubungan seksual</strong>
    </label>
    <div class="col-sm-8">
        <textarea class="form-control" rows="3" name="kesulitan_seksual"></textarea>
        <textarea class="form-control mt-2" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1">
        <div class="form-check">
            <input class="form-check-input" type="checkbox">
        </div>
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-3 col-form-label">
        <strong>5. Apakah penyakit sekarang mengganggu fungsi seksual</strong>
    </label>
    <div class="col-sm-8">
        <textarea class="form-control" rows="3" name="gangguan_seksual"></textarea>
        <textarea class="form-control mt-2" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1">
        <div class="form-check">
            <input class="form-check-input" type="checkbox">
        </div>
    </div>
</div>

<hr>

<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>g. Pola Peran Berhubungan</strong></label>
</div>

<div class="row mb-3">
    <label class="col-sm-3 col-form-label">
        <strong>1. Peran pasien dalam keluarga dan masyarakat</strong>
    </label>
    <div class="col-sm-8">
        <textarea class="form-control" rows="3" name="peran_pasien"></textarea>
        <textarea class="form-control mt-2" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1">
        <div class="form-check">
            <input class="form-check-input" type="checkbox">
        </div>
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-3 col-form-label">
        <strong>2. Apakah klien punya teman dekat</strong>
    </label>
    <div class="col-sm-8">
        <textarea class="form-control" rows="3" name="teman_dekat"></textarea>
        <textarea class="form-control mt-2" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1">
        <div class="form-check">
            <input class="form-check-input" type="checkbox">
        </div>
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-3 col-form-label">
        <strong>3. Siapa yang dipercaya membantu klien saat kesulitan</strong>
    </label>
    <div class="col-sm-8">
        <textarea class="form-control" rows="3" name="orang_terpercaya"></textarea>
        <textarea class="form-control mt-2" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1">
        <div class="form-check">
            <input class="form-check-input" type="checkbox">
        </div>
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-3 col-form-label">
        <strong>4. Apakah klien ikut kegiatan masyarakat? Bagaimana keterlibatannya</strong>
    </label>
    <div class="col-sm-8">
        <textarea class="form-control" rows="3" name="kegiatan_masyarakat"></textarea>
        <textarea class="form-control mt-2" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1">
        <div class="form-check">
            <input class="form-check-input" type="checkbox">
        </div>
    </div>
</div>

<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>h. Pola Nilai dan Kepercayaan</strong></label>
</div>

<div class="row mb-3">
    <label class="col-sm-3 col-form-label">
        <strong>1. Apakah klien menganut suatu agama?</strong>
    </label>
    <div class="col-sm-8">
        <textarea class="form-control" rows="3" name="agama"></textarea>
        <textarea class="form-control mt-2" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1">
        <div class="form-check">
            <input class="form-check-input" type="checkbox">
        </div>
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-3 col-form-label">
        <strong>2. Menurut agama klien bagaimana hubungan manusia dengan pencipta-Nya?</strong>
    </label>
    <div class="col-sm-8">
        <textarea class="form-control" rows="3" name="hubungan_tuhan"></textarea>
        <textarea class="form-control mt-2" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1">
        <div class="form-check">
            <input class="form-check-input" type="checkbox">
        </div>
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-3 col-form-label">
        <strong>3. Dalam keadaan sakit apakah klien mengalami hambatan dalam ibadah?</strong>
    </label>
    <div class="col-sm-8">
        <textarea class="form-control" rows="3" name="hambatan_ibadah"></textarea>
        <textarea class="form-control mt-2" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1">
        <div class="form-check">
            <input class="form-check-input" type="checkbox">
        </div>
    </div>
</div>

<hr>
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
        <textarea class="form-control mt-2" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>

    <div class="col-sm-1 d-flex align-items-center justify-content-center">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" style="transform: scale(1.5);">
        </div>
    </div>
</div>

<hr>

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
        <textarea class="form-control mt-2" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1 d-flex align-items-center justify-content-center">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" style="transform: scale(1.5);">
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
        <textarea class="form-control mt-2" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1 d-flex align-items-center justify-content-center">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" style="transform: scale(1.5);">
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
        <textarea class="form-control mt-2" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1 d-flex align-items-center justify-content-center">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" style="transform: scale(1.5);">
        </div>
    </div>
</div>

<hr>

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
        <textarea class="form-control mt-2" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1 d-flex align-items-center justify-content-center">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" style="transform: scale(1.5);">
        </div>
    </div>
</div>

<hr>

<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>n. Pola Personal Hygiene</strong></label>
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
        <textarea class="form-control mt-2" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1 d-flex align-items-center justify-content-center">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" style="transform: scale(1.5);">
        </div>
    </div>
</div>
</div>
</form>
</div>
</div>

<div class="card">
    <div class="card-body">
        <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

            <h5 class="card-title"><strong>5. Data Biologis</strong></h5>

            <div class="row mb-2">
                <label class="col-sm-12 text-primary"><strong>a. Kepala</strong></label>
            </div>
            <div class="row mb-3">
                <div class="col-sm-11">
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Bentuk Kepala</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="bentuk_kepala">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Apa ada nyeri tekan	:</strong></label>
                        <div class="col-sm-9">
                            <label class="me-3"><input type="radio" name="nyeri_kepala" value="ya"> Ya</label>
                            <label><input type="radio" name="nyeri_kepala" value="tidak"> Tidak</label>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Apa ada benjolan</strong></label>
                        <div class="col-sm-9">
                            <label class="me-3"><input type="radio" name="benjolan_kepala" value="ya"> Ya</label>
                            <label><input type="radio" name="benjolan_kepala" value="tidak"> Tidak</label>
                        </div>
                    </div>
                    <textarea class="form-control mt-2" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
                </div>
                <div class="col-sm-1 d-flex align-items-center justify-content-center">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" style="transform: scale(1.5);">
                    </div>
                </div>
            </div>

            <hr>

            <div class="row mb-2">
                <label class="col-sm-12 text-primary"><strong>b. Rambut</strong></label>
            </div>
            <div class="row mb-3">
                <div class="col-sm-11">
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Penyebaran Merata</strong></label>
                        <div class="col-sm-9">
                            <label class="me-3"><input type="radio" name="rambut_merata" value="ya"> Ya</label>
                            <label><input type="radio" name="rambut_merata" value="tidak"> Tidak</label>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Warna</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="warna_rambut">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Mudah Dicabut</strong></label>
                        <div class="col-sm-9">
                            <label class="me-3"><input type="radio" name="rambut_dicabut" value="ya"> Ya</label>
                            <label><input type="radio" name="rambut_dicabut" value="tidak"> Tidak</label>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Kelainan</strong></label>
                        <div class="col-sm-9">
                            <textarea class="form-control" rows="2" name="kelainan_rambut"></textarea>
                        </div>
                    </div>
                    <textarea class="form-control mt-2" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
                </div>
                <div class="col-sm-1 d-flex align-items-center justify-content-center">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" style="transform: scale(1.5);">
                    </div>
                </div>
            </div>

            <hr>

            <div class="row mb-2">
                <label class="col-sm-12 text-primary"><strong>c. Wajah</strong></label>
            </div>
            <div class="row mb-3">
                <div class="col-sm-11">
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Ekspresi Wajah</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="ekspresi_wajah">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Kesimetrisan Wajah</strong></label>
                        <div class="col-sm-9">
                            <label class="me-3"><input type="radio" name="simetris_wajah" value="ya"> Ya</label>
                            <label><input type="radio" name="simetris_wajah" value="tidak"> Tidak</label>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Terdapat Udema</strong></label>
                        <div class="col-sm-9">
                            <label class="me-3"><input type="radio" name="udema_wajah" value="ya"> Ya</label>
                            <label><input type="radio" name="udema_wajah" value="tidak"> Tidak</label>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Kelainan</strong></label>
                        <div class="col-sm-9">
                            <textarea class="form-control" rows="2" name="kelainan_wajah"></textarea>
                        </div>
                    </div>
                    <textarea class="form-control mt-2" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
                </div>
                <div class="col-sm-1 d-flex align-items-center justify-content-center">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" style="transform: scale(1.5);">
                    </div>
                </div>
            </div>

            <hr>
<div class="row mb-2">
                <label class="col-sm-12 text-primary"><strong>d. Mata</strong></label>
            </div>
            <div class="row mb-3">
                <div class="col-sm-11">
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Penglihatan</strong></label>
                        <div class="col-sm-9">
                            <label class="me-2"><input type="radio" name="penglihatan[]" value="jelas"> Jelas</label>
                            <label class="me-2"><input type="radio" name="penglihatan[]" value="kabur"> Kabur</label>
                            <label class="me-2"><input type="radio" name="penglihatan[]" value="rabun"> Rabun</label>
                            <label><input type="checkbox" name="penglihatan[]" value="berkunang"> Berkunang-kunang</label>
                        </div>
                    </div>
                   <div class="row mb-3">
    <div class="col-sm-11">
        <div class="row align-items-center">
            <label class="col-sm-3 col-form-label"><strong>Visus</strong></label>
            
            <div class="col-sm-4">
                <div class="row align-items-center">
                    <label class="col-auto mb-0">Kanan :</label>
                    <div class="col">
                        <input type="text" class="form-control" name="visus_kanan">
                    </div>
                </div>
            </div>
            
            <div class="col-sm-5">
                <div class="row align-items-center">
                    <label class="col-auto mb-0">Kiri :</label>
                    <div class="col">
                        <input type="text" class="form-control" name="visus_kiri">
                    </div>
                </div>
            </div>
        </div>
        </div>
        </div>

       

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Lapang Pandang</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="lapang_pandang">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Keadaan Mata</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="lapang_pandang">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Konjungtiva</strong></label>
                        <div class="col-sm-9">
                            <label class="me-3"><input type="radio" name="lesi_mata" value="ada"> Anemis</label>
                            <label><input type="radio" name="lesi_mata" value="tidak"> Anenmis</label>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Lesi</strong></label>
                        <div class="col-sm-9">
                            <label class="me-3"><input type="radio" name="lesi_mata" value="ada"> Ada</label>
                            <label><input type="radio" name="lesi_mata" value="tidak"> Tidak</label>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Sclera</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="sclera">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Reaksi Pupil</strong></label>
                        <div class="col-sm-9">
                            <label class="me-3"><input type="radio" name="pupil" value="isokor"> Isokor</label>
                            <label><input type="radio" name="pupil" value="anisokor"> Anisokor</label>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Bola Mata</strong></label>
                        <div class="col-sm-9">
                            <label class="me-3"><input type="radio" name="bola_mata" value="simetris"> Simetris</label>
                            <label><input type="radio" name="bola_mata" value="tidak"> Tidak</label>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Kelainan</strong></label>
                        <div class="col-sm-9">
                            <textarea class="form-control" rows="2" name="kelainan_mata"></textarea>
                        </div>
                    </div>
                    <textarea class="form-control mt-2" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
                </div>
                <div class="col-sm-1 d-flex align-items-center justify-content-center">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" style="transform: scale(1.5);">
                    </div>
                </div>
            </div>

            <hr>

            <div class="row mb-2">
                <label class="col-sm-12 text-primary"><strong>e. Telinga</strong></label>
            </div>
            <div class="row mb-3">
                <div class="col-sm-11">
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Pendengaran Kiri</strong></label>
                        <div class="col-sm-9">
                            <label class="me-3"><input type="radio" name="pendengaran_Kiri" value="jelas"> Jelas</label>
                            <label><input type="radio" name="pendengaran" value="berkurang"> Berkurang</label>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Pendengaran Kanan </strong></label>
                        <div class="col-sm-9">
                            <label class="me-3"><input type="radio" name="pendengaran_Kanan" value="jelas"> Jelas</label>
                            <label><input type="radio" name="pendengaran" value="berkurang"> Berkurang</label>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Nyeri Kiri</strong></label>
                        <div class="col-sm-9">
                            <label class="me-3"><input type="radio" name="serumen_Kiri" value="ada"> Ada</label>
                            <label><input type="radio" name="serumen" value="tidak"> Tidak</label>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Nyeri Kanan</strong></label>
                        <div class="col-sm-9">
                            <label class="me-3"><input type="radio" name="serumen_Kanan" value="ada"> Ada</label>
                            <label><input type="radio" name="serumen" value="tidak"> Tidak</label>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Serumen</strong></label>
                        <div class="col-sm-9">
                            <label class="me-3"><input type="radio" name="serumen" value="ada"> Ada</label>
                            <label><input type="radio" name="serumen" value="tidak"> Tidak</label>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Kelainan</strong></label>
                        <div class="col-sm-9">
                            <textarea class="form-control" rows="2" name="kelainan_telinga"></textarea>
                        </div>
                    </div>
                    <textarea class="form-control mt-2" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
                </div>
                <div class="col-sm-1 d-flex align-items-center justify-content-center">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" style="transform: scale(1.5);">
                    </div>
                </div>
            </div>

            <hr>
<div class="row mb-2">
                <label class="col-sm-12 text-primary"><strong>f. Hidung</strong></label>
            </div>
            <div class="row mb-3">
                <div class="col-sm-11">
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Membedakan Bau</strong></label>
                        <div class="col-sm-9">
                            <label class="me-3"><input type="radio" name="bau" value="dapat"> Dapat</label>
                            <label><input type="radio" name="bau" value="tidak"> Tidak</label>
                        </div>
                    </div>
                    <div class="row mb-3">
    <div class="col-sm-11">
        <div class="row align-items-center">
            <label class="col-sm-3 col-form-label"><strong>Sekresi</strong></label>
            
            <div class="col-sm-4">
                <div class="row align-items-center">
                    <label class="col-auto mb-0"></label>
                    <div class="col">
                        <input type="text" class="form-control" name="visus_kanan">
                    </div>
                </div>
            </div>
            
            <div class="col-sm-5">
                <div class="row align-items-center">
                    <label class="col-auto mb-0">Warna</label>
                    <div class="col">
                        <input type="text" class="form-control" name="visus_kiri">
                    </div>
                </div>
            </div>
        </div>
        </div>
        </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Mukosa</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="mukosa_hidung">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Pembengkakan</strong></label>
                        <div class="col-sm-9">
                            <label class="me-3"><input type="radio" name="Pembengkakan" value="ada"> Ya</label>
                            <label><input type="radio" name="Pembengkakan" value="tidak"> Tidak</label>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Pernafasan Cuping Hidung</strong></label>
                        <div class="col-sm-9">
                            <label class="me-3"><input type="radio" name="cuping_hidung" value="ada"> Ada</label>
                            <label><input type="radio" name="cuping_hidung" value="tidak"> Tidak</label>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Kelainan</strong></label>
                        <div class="col-sm-9">
                            <textarea class="form-control" rows="2" name="kelainan_hidung"></textarea>
                        </div>
                    </div>
                    <textarea class="form-control mt-2" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
                </div>
                <div class="col-sm-1 d-flex align-items-center justify-content-center">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" style="transform: scale(1.5);">
                    </div>
                </div>
            </div>

            <hr>

            <div class="row mb-3">
<label class="col-sm-12"><strong>g. Mulut</strong></label>
</div>

<!-- Bibir -->
<div class="row mb-3">
<label class="col-sm-3 col-form-label"><strong>Bibir</strong></label>
<div class="col-sm-9">
Warna :
<input type="text" class="form-control d-inline-block" style="width:200px;">
</div>
</div>

<div class="row mb-3">
<label class="col-sm-3 col-form-label"><strong>Simetris</strong></label>
<div class="col-sm-9">
<label><input type="radio" name="simetris"> Ya</label>
<label class="ms-3"><input type="radio" name="simetris"> Tidak</label>
</div>
</div>

<div class="row mb-3">
<label class="col-sm-3 col-form-label"><strong>Kelembaban</strong></label>
<div class="col-sm-9">
<label><input type="radio" name="kelembaban"> Basah</label>
<label class="ms-3"><input type="radio" name="kelembaban"> Kering</label>
</div>
</div>

<!-- Gigi -->
<div class="row mb-3">
<label class="col-sm-3 col-form-label"><strong>Gigi</strong></label>
<div class="col-sm-9">
Caries :
<label><input type="radio" name="caries"> Ada</label>
<label class="ms-3"><input type="radio" name="caries"> Tidak</label>

<span class="ms-4">Jumlah :</span>
<input type="text" class="form-control d-inline-block" style="width:120px;">
</div>
</div>

<div class="row mb-3">
<label class="col-sm-3 col-form-label"><strong>Warna</strong></label>
<div class="col-sm-9">
<input type="text" class="form-control" style="width:300px;">
</div>
</div>

<div class="row mb-3">
<label class="col-sm-3 col-form-label"><strong>Gigi Palsu</strong></label>
<div class="col-sm-9">
<input type="text" class="form-control d-inline-block" style="width:100px;"> buah
<span class="ms-3">Letak :</span>
<input type="text" class="form-control d-inline-block" style="width:200px;">
</div>
</div>

<!-- Lidah -->
<div class="row mb-3">
<label class="col-sm-3 col-form-label"><strong>Lidah</strong></label>
<div class="col-sm-9">
Warna :
<input type="text" class="form-control d-inline-block" style="width:200px;">
</div>
</div>

<div class="row mb-3">
<label class="col-sm-3 col-form-label"><strong>Lesi</strong></label>
<div class="col-sm-9">
<label><input type="radio" name="lesi_lidah"> Ada</label>
<label class="ms-3"><input type="radio" name="lesi_lidah"> Kaku</label>
</div>
</div>

<!-- Sensasi Rasa -->
<div class="row mb-3">
<label class="col-sm-3 col-form-label"><strong>Sensasi Rasa</strong></label>
<div class="col-sm-9">

Panas/Dingin :
<label class="ms-2"><input type="radio" name="panas"> Ada</label>
<label class="ms-2"><input type="radio" name="panas"> Tidak</label>

<br><br>

Asam / Pahit :
<label class="ms-2"><input type="radio" name="asam"> Ada</label>
<label class="ms-2"><input type="radio" name="asam"> Tidak</label>

<br><br>

Manis :
<label class="ms-2"><input type="radio" name="manis"> Ada</label>
<label class="ms-2"><input type="radio" name="manis"> Tidak</label>

</div>
</div>

<!-- Refleks Mengunyah -->
<div class="row mb-3">
<label class="col-sm-3 col-form-label"><strong>Refleks Mengunyah</strong></label>
<div class="col-sm-9">
<label><input type="radio" name="refleks"> Dapat</label>
<label class="ms-3"><input type="radio" name="refleks"> Tidak</label>
</div>
</div>

<!-- Tonsil -->
<div class="row mb-3">
<label class="col-sm-3 col-form-label"><strong>Pembesaran Tonsil</strong></label>
<div class="col-sm-9">
<label><input type="radio" name="simetris"> Ya</label>
<label class="ms-3"><input type="radio" name="simetris"> Tidak</label>
</div>
</div>

<!-- Bau Mulut -->
<div class="row mb-3">
<label class="col-sm-3 col-form-label"><strong>Bau Mulut</strong></label>
<div class="col-sm-9">

<label><input type="checkbox"> Uranium + / -</label>
<label class="ms-3"><input type="checkbox"> Amoniak + / -</label>
<label class="ms-3"><input type="checkbox"> Aceton + / -</label>

<br><br>

<label><input type="checkbox"> Busuk + / -</label>
<label class="ms-3"><input type="checkbox"> Alkohol + / -</label>

</div>
</div>

<!-- Sekret -->
<div class="row mb-3">
<label class="col-sm-3 col-form-label"><strong>Sekret</strong></label>
<div class="col-sm-9">

<label><input type="radio" name="sekret"> Ada</label>
<label class="ms-3"><input type="radio" name="sekret"> Tidak</label>

<span class="ms-3">Warna :</span>
<input type="text" class="form-control d-inline-block" style="width:200px;">

</div>
</div>
<hr>
<div>
                <label class="col-sm-12 text-primary"><strong>h. Leher</strong></label>
            </div>
            <div class="row mb-3">
                <div class="col-sm-11">
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Bentuk Simetris</strong></label>
                        <div class="col-sm-9">
                            <label class="me-3"><input type="radio" name="leher_simetris" value="ya"> Ya</label>
                            <label><input type="radio" name="leher_simetris" value="tidak"> Tidak</label>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Pembesaran Kelenjar</strong></label>
                        <div class="col-sm-9">
                            <label class="me-3"><input type="radio" name="kelenjar" value="ada"> Ada</label>
                            <label><input type="radio" name="kelenjar" value="tidak"> Tidak</label>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Peninggian JVP</strong></label>
                        <div class="col-sm-9">
                            <label class="me-3"><input type="radio" name="jvp" value="ada"> Ada</label>
                            <label><input type="radio" name="jvp" value="tidak"> Tidak</label>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Refleks Menelan</strong></label>
                        <div class="col-sm-9">
                            <label class="me-3"><input type="radio" name="refleks_menelan" value="dapat"> Dapat</label>
                            <label><input type="radio" name="refleks_menelan" value="tidak"> Tidak</label>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Kelainan</strong></label>
                        <div class="col-sm-9">
                            <textarea class="form-control" rows="2" name="kelainan_leher"></textarea>
                        </div>
                    </div>
                    <textarea class="form-control mt-2" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
                </div>
                <div class="col-sm-1 d-flex align-items-center justify-content-center">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" style="transform: scale(1.5);">
                    </div>
                </div>
            </div>

            <hr>

            <div class="row mb-2">
                <label class="col-sm-12 text-primary"><strong>i. Dada</strong></label>
            </div>
            <div class="row mb-3">
                <div class="col-sm-11">
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Bentuk Dada</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="bentuk_dada">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Pengembangan Dada</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="pengembangan_dada">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Perbandingan ukuran anterior-posterior dengan transversal</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="perbandingan_dada">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Penggunaan Otot Pernafasan Tambahan</strong></label>
                        <div class="col-sm-9">
                            <label class="me-3"><input type="radio" name="otot_pernafasan" value="ya"> Ya</label>
                            <label><input type="radio" name="otot_pernafasan" value="tidak"> Tidak</label>
                        </div>
                    </div>
                    <textarea class="form-control mt-2" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
                </div>
                <div class="col-sm-1 d-flex align-items-center justify-content-center">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" style="transform: scale(1.5);">
                    </div>
                </div>
            </div>

            <hr>
<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>j. Paru</strong></label>
</div>

<div class="row mb-3">
    <div class="col-sm-11">

        <!-- Frekuensi Nafas -->
        <div class="row mb-3">
            <label class="col-sm-3 col-form-label"><strong>Frekuensi Nafas</strong></label>
            <div class="col-sm-3">
                <input type="number" class="form-control" name="frekuensi_nafas" placeholder="x/menit">
            </div>
            <div class="col-sm-6">
                <label class="me-3">
                    <input type="radio" name="teratur_nafas" value="teratur"> Teratur
                </label>
                <label>
                    <input type="radio" name="teratur_nafas" value="tidak"> Tidak
                </label>
            </div>
        </div>

        <!-- Irama Pernafasan -->
        <div class="row mb-3">
            <label class="col-sm-3 col-form-label"><strong>Irama Pernafasan</strong></label>
            <div class="col-sm-9">
                <label class="me-3">
                    <input type="radio" name="irama_nafas" value="dangkal"> Dangkal
                </label>
                <label>
                    <input type="radio" name="irama_nafas" value="dalam"> Dalam
                </label>
            </div>
        </div>

        <!-- Kesukaran Bernafas -->
        <div class="row mb-3">
            <label class="col-sm-3 col-form-label"><strong>Kesukaran Bernafas</strong></label>
            <div class="col-sm-9">
                <label class="me-3">
                    <input type="radio" name="sesak_nafas" value="ya"> Ya
                </label>
                <label>
                    <input type="radio" name="sesak_nafas" value="tidak"> Tidak
                </label>
            </div>
        </div>

        <!-- Taktil Fremitus -->
        <div class="row mb-3">
            <label class="col-sm-3 col-form-label"><strong>Taktil Fremitus</strong></label>
            <div class="col-sm-9">
                <input type="text" class="form-control" name="taktil_fremitus">
            </div>
        </div>

        <!-- Bunyi Perkusi -->
        <div class="row mb-3">
            <label class="col-sm-3 col-form-label"><strong>Bunyi Perkusi Paru</strong></label>
            <div class="col-sm-9">
                <input type="text" class="form-control" name="perkusi_paru">
            </div>
        </div>

        <!-- Suara Nafas -->
        <div class="row mb-3">
            <label class="col-sm-3 col-form-label"><strong>Suara Nafas</strong></label>
            <div class="col-sm-3">
                <label>
                    <input type="radio" name="suara_nafas" value="normal"> Normal
                </label>
            </div>
            <div class="col-sm-6">
                <input type="text" class="form-control" name="uraian_suara_nafas" placeholder="Uraikan...">
            </div>
        </div>

        <!-- Bunyi Nafas Abnormal -->
        <div class="row mb-3">
            <label class="col-sm-3 col-form-label"><strong>Bunyi Nafas Abnormal</strong></label>
            <div class="col-sm-9">

                <label class="me-3">
                    <input type="Radio" name="bunyi_abnormal[]" value="wheezing"> Wheezing
                </label>

                <label class="me-3">
                    <input type="Radio" name="bunyi_abnormal[]" value="ronchi"> Ronchi
                </label>

                <label class="me-3">
                    <input type="Radio" id="lainnyaCheck" onclick="toggleLainnya()"> Lainnya
                </label>

                <input type="text"
                       class="form-control mt-2"
                       id="lainnyaInput"
                       name="bunyi_abnormal_lainnya"
                       placeholder="Sebutkan bunyi nafas lainnya"
                       style="display:none;">

            </div>
        </div>

        <!-- Textarea Revisi -->
        <textarea class="form-control mt-2" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>

    </div>

    <!-- Checkbox ACC -->
    <div class="col-sm-1 d-flex align-items-center justify-content-center">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" style="transform: scale(1.5);">
        </div>
    </div>
</div>

<script>
function toggleLainnya() {
    var check = document.getElementById("lainnyaCheck");
    var input = document.getElementById("lainnyaInput");

    if (check.checked) {
        input.style.display = "block";
    } else {
        input.style.display = "none";
        input.value = "";
    }
}
</script>
<hr>
<div class="row mb-2">
                <label class="col-sm-12 text-primary"><strong>k. Jantung</strong></label>
            </div>
            <div class="row mb-3">
                <div class="col-sm-11">
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>S1</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="s1_jantung">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>S2</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="s2_jantung">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Bunyi Teratur</strong></label>
                        <div class="col-sm-9">
                            <label class="me-3"><input type="radio" name="bunyi_jantung" value="ya"> Ya</label>
                            <label><input type="radio" name="bunyi_jantung" value="tidak"> Tidak</label>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Bunyi Tambahan</strong></label>
                        <div class="col-sm-9">
                            <label class="me-3"><input type="checkbox" name="bunyi_tambahan" value="murmur"> Murmur</label>
                            <label><input type="checkbox" name="bunyi_tambahan" value="tidak"> Tidak</label>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Pulsasi Jantung</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="pulsasi_jantung">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Irama</strong></label>
                        <div class="col-sm-9">
                            <label class="me-3"><input type="radio" name="irama_jantung" value="teratur"> Teratur</label>
                            <label><input type="radio" name="irama_jantung" value="tidak_teratur"> Tidak Teratur</label>
                        </div>
                    </div>
                    <textarea class="form-control mt-2" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
                </div>
                <div class="col-sm-1 d-flex align-items-center justify-content-center">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" style="transform: scale(1.5);">
                    </div>
                </div>
            </div>

            <hr>

            <div class="row mb-2">
                <label class="col-sm-12 text-primary"><strong>l. Abdomen</strong></label>
            </div>
            <div class="row mb-3">
                <div class="col-sm-11">
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Bentuk</strong></label>
                        <div class="col-sm-9">
                            <label class="me-2"><input type="checkbox" name="bentuk_abdomen[]" value="datar"> Datar</label>
                            <label class="me-2"><input type="checkbox" name="bentuk_abdomen[]" value="membuncit"> Membuncit</label>
                            <label class="me-2"><input type="checkbox" name="bentuk_abdomen[]" value="cekung"> Cekung</label>
                            <label><input type="checkbox" name="bentuk_abdomen[]" value="tegang"> Tegang</label>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Keadaan</strong></label>
                        <div class="col-sm-9">
                            <label class="me-2"><input type="checkbox" name="keadaan_abdomen[]" value="parut"> Parut</label>
                            <label class="me-2"><input type="checkbox" name="keadaan_abdomen[]" value="lesi"> Lesi</label>
                            <label><input type="checkbox" name="keadaan_abdomen[]" value="bercak_merah"> Bercak Merah</label>
                        </div>
                    </div>
<div class="row mb-3">
    <label class="col-sm-3 col-form-label"><strong>Bising Usus</strong></label>

    <div class="col-sm-3">
        <label class="me-3">
            <input type="radio" name="bising_usus" value="ada"> Ada
        </label>
        <label>
            <input type="radio" name="bising_usus" value="tidak"> Tidak
        </label>
    </div>

    <label class="col-sm-2 col-form-label">...kali</label>

    <div class="col-sm-4">
        <input type="text" class="form-control" name="frekuensi_bising_usus" placeholder="">
    </div>
</div>
                    
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Benjolan</strong></label>
                        <div class="col-sm-3">
                            <label class="me-2"><input type="radio" name="benjolan_abdomen" value="ada"> Ada</label>
                            <label><input type="radio" name="benjolan_abdomen" value="tidak"> Tidak</label>
                        </div>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" placeholder="Letak" name="letak_benjolan">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Nyeri Tekan</strong></label>
                        <div class="col-sm-3">
                            <label class="me-2"><input type="radio" name="nyeri_abdomen" value="ada"> Ada</label>
                            <label><input type="radio" name="nyeri_abdomen" value="tidak"> Tidak</label>
                        </div>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" placeholder="Letak" name="letak_nyeri">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Perkusi Abdomen</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="perkusi_abdomen">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Kelainan</strong></label>
                        <div class="col-sm-9">
                            <textarea class="form-control" rows="2" name="kelainan_abdomen"></textarea>
                        </div>
                    </div>
                    <textarea class="form-control mt-2" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
                </div>
                <div class="col-sm-1 d-flex align-items-center justify-content-center">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" style="transform: scale(1.5);">
                    </div>
                </div>
            </div>

            <hr>

            <div class="row mb-2">
                <label class="col-sm-12 text-primary"><strong>m. Genetalia</strong></label>
            </div>
            <div class="row mb-3">
                <div class="col-sm-11">
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Bentuk</strong></label>
                        <div class="col-sm-9">
                            <label class="me-3"><input type="radio" name="bentuk_genetalia" value="utuh"> Utuh</label>
                            <label><input type="radio" name="bentuk_genetalia" value="tidak"> Tidak</label>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Radang</strong></label>
                        <div class="col-sm-9">
                            <label class="me-3"><input type="radio" name="radang_genetalia" value="ada"> Ada</label>
                            <label><input type="radio" name="radang_genetalia" value="tidak"> Tidak</label>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Sekret</strong></label>
                        <div class="col-sm-9">
                            <label class="me-3"><input type="radio" name="sekret_genetalia" value="ada"> Ada</label>
                            <label><input type="radio" name="sekret_genetalia" value="tidak"> Tidak</label>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Pembengkakan Skrotum</strong></label>
                        <div class="col-sm-9">
                            <label class="me-3"><input type="radio" name="skrotum_bengkak" value="ada"> Ada</label>
                            <label><input type="radio" name="skrotum_bengkak" value="tidak"> Tidak</label>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Rektum</strong></label>
                        <div class="col-sm-9">
                            <label class="me-3"><input type="radio" name="rektum_benjolan" value="ada"> Benjolan</label>
                            <label><input type="radio" name="rektum_benjolan" value="tidak"> Tidak</label>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Lesi</strong></label>
                        <div class="col-sm-9">
                            <label class="me-3"><input type="radio" name="lesi_genetalia" value="ya"> Ya</label>
                            <label><input type="radio" name="lesi_genetalia" value="tidak"> Tidak</label>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Kelainan</strong></label>
                        <div class="col-sm-9">
                            <textarea class="form-control" rows="2" name="kelainan_genetalia"></textarea>
                        </div>
                    </div>
                    <textarea class="form-control mt-2" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
                </div>
                <div class="col-sm-1 d-flex align-items-center justify-content-center">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" style="transform: scale(1.5);">
                    </div>
                </div>
            </div>
            <hr>
<div class="row mb-2">
                <label class="col-sm-12 text-primary"><strong>n. Ekstremitas</strong></label>
            </div>

            <div class="row mb-3">
                <div class="col-sm-11">
                    <div class="row mb-2">
                        <label class="col-sm-12"><strong>1) Atas</strong></label>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Bentuk Simetris</strong></label>
                        <div class="col-sm-9">
                            <label class="me-3"><input type="radio" name="atas_simetris" value="ya"> Ya</label>
                            <label><input type="radio" name="atas_simetris" value="tidak"> Tidak</label>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Sensasi Halus</strong></label>
                        <div class="col-sm-9">
                            <label class="me-3"><input type="radio" name="atas_simetris" value="ya"> Ada</label>
                            <label><input type="radio" name="atas_simetris" value="tidak"> Tidak</label>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Sensasi Tajam</strong></label>
                        <div class="col-sm-9">
                            <label class="me-3"><input type="radio" name="atas_simetris" value="ya"> Ada</label>
                            <label><input type="radio" name="atas_simetris" value="tidak"> Tidak</label>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Sensasi Panas</strong></label>
                        <div class="col-sm-9">
                            <label class="me-3"><input type="radio" name="atas_simetris" value="ya"> Ada</label>
                            <label><input type="radio" name="atas_simetris" value="tidak"> Tidak</label>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Sensasi Dingin </strong></label>
                        <div class="col-sm-9">
                            <label class="me-3"><input type="radio" name="atas_simetris" value="ya"> Ada</label>
                            <label><input type="radio" name="atas_simetris" value="tidak"> Tidak</label>
                        </div>
                    </div>

                    

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Gerakan ROM</strong></label>
                        <div class="col-sm-9">
                            <label class="me-3"><input type="radio" name="rom_atas" value="dapat"> Dapat</label>
                            <label><input type="radio" name="rom_atas" value="tidak"> Tidak</label>
                        </div>
                    </div>
                        <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Refleks Bisep</strong></label>
                        <div class="col-sm-9">
                            <label class="me-3"><input type="radio" name="atas_simetris" value="ya"> Ada</label>
                            <label><input type="radio" name="atas_simetris" value="tidak"> Tidak</label>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Refleks Trisep</strong></label>
                        <div class="col-sm-9">
                            <label class="me-3"><input type="radio" name="atas_simetris" value="ya"> Ada</label>
                            <label><input type="radio" name="atas_simetris" value="tidak"> Tidak</label>
                        </div>
                    </div>
                  
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Pembengkakan</strong></label>
                        <div class="col-sm-9">
                            <label class="me-3"><input type="radio" name="atas_simetris" value="ya"> Ya</label>
                            <label><input type="radio" name="atas_simetris" value="tidak"> Tidak</label>
                        </div>
                    </div>
                     <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Kelembaban</strong></label>
                        <div class="col-sm-9">
                            <label class="me-3"><input type="radio" name="atas_simetris" value="Lembab"> Lembab </label>
                            <label><input type="radio" name="atas_simetris" value="Kering"> Kering </label>
                        </div>
                    </div>
                     <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Temperatur</strong></label>
                        <div class="col-sm-9">
                            <label class="me-3"><input type="radio" name="atas_simetris" value="Panas"> Panas </label>
                            <label><input type="radio" name="atas_simetris" value="Dingin"> Dingin </label>
                        </div>
                    </div>
                    
                    

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Kekuatan Otot Tangan</strong></label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control" placeholder="Kanan" name="otot_tangan_kanan">
                        </div>
                        <div class="col-sm-3">
                            <input type="text" class="form-control" placeholder="Kiri" name="otot_tangan_kiri">
                        </div>
                    </div>  <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Kelainan</strong></label>
                        <div class="col-sm-9">
                            <textarea class="form-control" rows="2" name="kelainan_genetalia"></textarea>
                        </div>
                    </div>
                    <textarea class="form-control mt-2" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
                </div>
                <div class="col-sm-1 d-flex align-items-center justify-content-center">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" style="transform: scale(1.5);">
                    </div>
                </div>
            </div>
                <div class="row mb-3">
                <div class="col-sm-11">
                    <div class="row mb-2">
                        <label class="col-sm-12"><strong>2)	Bawah</strong></label>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Bentuk Simetris</strong></label>
                        <div class="col-sm-9">
                            <label class="me-3"><input type="radio" name="atas_simetris" value="ya"> Ya</label>
                            <label><input type="radio" name="atas_simetris" value="tidak"> Tidak</label>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Sensasi Halus</strong></label>
                        <div class="col-sm-9">
                            <label class="me-3"><input type="radio" name="atas_simetris" value="ya"> Ada</label>
                            <label><input type="radio" name="atas_simetris" value="tidak"> Tidak</label>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Sensasi Tajam</strong></label>
                        <div class="col-sm-9">
                            <label class="me-3"><input type="radio" name="atas_simetris" value="ya"> Ada</label>
                            <label><input type="radio" name="atas_simetris" value="tidak"> Tidak</label>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Sensasi Panas</strong></label>
                        <div class="col-sm-9">
                            <label class="me-3"><input type="radio" name="atas_simetris" value="ya"> Ada</label>
                            <label><input type="radio" name="atas_simetris" value="tidak"> Tidak</label>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Sensasi Dingin </strong></label>
                        <div class="col-sm-9">
                            <label class="me-3"><input type="radio" name="atas_simetris" value="ya"> Ada</label>
                            <label><input type="radio" name="atas_simetris" value="tidak"> Tidak</label>
                        </div>
                    </div>

                    

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Gerakan ROM</strong></label>
                        <div class="col-sm-9">
                            <label class="me-3"><input type="radio" name="rom_atas" value="dapat"> Dapat</label>
                            <label><input type="radio" name="rom_atas" value="tidak"> Tidak</label>
                        </div>
                    </div>
                        <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Refleks Babinski </strong></label>
                        <div class="col-sm-9">
                            <label class="me-3"><input type="radio" name="atas_simetris" value="ya"> Ada</label>
                            <label><input type="radio" name="atas_simetris" value="tidak"> Tidak</label>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Pembengkakan</strong></label>
                        <div class="col-sm-9">
                            <label class="me-3"><input type="radio" name="atas_simetris" value="ya"> Ada</label>
                            <label><input type="radio" name="atas_simetris" value="tidak"> Tidak</label>
                        </div>
                    </div>
                  
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Varises </strong></label>
                        <div class="col-sm-9">
                            <label class="me-3"><input type="radio" name="atas_simetris" value="ya"> Ada</label>
                            <label><input type="radio" name="atas_simetris" value="tidak"> Tidak</label>
                        </div>
                    </div>
                     <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Kelembaban</strong></label>
                        <div class="col-sm-9">
                            <label class="me-3"><input type="radio" name="atas_simetris" value="Lembab"> Lembab </label>
                            <label><input type="radio" name="atas_simetris" value="Kering"> Kering </label>
                        </div>
                    </div>
                     <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Temperatur</strong></label>
                        <div class="col-sm-9">
                            <label class="me-3"><input type="radio" name="atas_simetris" value="Panas"> Panas </label>
                            <label><input type="radio" name="atas_simetris" value="Dingin"> Dingin </label>
                        </div>
                    </div>
                    
                    

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Kekuatan Otot Kaki</strong></label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control" placeholder="Kanan" name="otot_tangan_kanan">
                        </div>
                        <div class="col-sm-3">
                            <input type="text" class="form-control" placeholder="Kiri" name="otot_tangan_kiri">
                        </div>
                    </div>  <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Kelainan</strong></label>
                        <div class="col-sm-9">
                            <textarea class="form-control" rows="2" name="kelainan_genetalia"></textarea>
                        </div>
                    </div>
                    <textarea class="form-control mt-2" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
                </div>
                <div class="col-sm-1 d-flex align-items-center justify-content-center">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" style="transform: scale(1.5);">
                    </div>
                </div>
            </div>
            <hr>
            <div class="row mb-2">
                <label class="col-sm-12 text-primary"><strong>o. Kulit</strong></label>
            </div>
            <div class="row mb-3">
                <div class="col-sm-11">
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Warna </strong></label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" placeholder="Warna" name="warna_kulit">
                        </div>
                        </div>
                        <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Turgor </strong></label>
                        <div class="col-sm-4">
                            <label class="me-3"><input type="radio" name="turgor_kulit" value="elastis"> Elastis</label>
                            <label><input type="radio" name="turgor_kulit" value="menurun"> Menurun</label>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Kelembaban</strong></label>
                        <div class="col-sm-9">
                            <label class="me-3"><input type="radio" name="atas_simetris" value="Lembab"> Lembab </label>
                            <label><input type="radio" name="atas_simetris" value="Kering"> Kering </label>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Edema</strong></label>
                        <div class="col-sm-3">
                            <label class="me-2"><input type="radio" name="edema_kulit" value="ada"> Ada</label>
                            <label><input type="radio" name="edema_kulit" value="tidak"> Tidak</label>
                        </div>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" placeholder="Pada Daerah" name="daerah_edema">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Luka</strong></label>
                        <div class="col-sm-3">
                            <label class="me-2"><input type="radio" name="luka_kulit" value="ada"> Ada</label>
                            <label><input type="radio" name="luka_kulit" value="tidak"> Tidak</label>
                        </div>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" placeholder="Pada Daerah" name="daerah_luka">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Karakteristik Luka</strong></label>
                        <div class="col-sm-9">
                            <textarea class="form-control mb-2" rows="2" placeholder="Karakteristik luka..." name="karakteristik_luka"></textarea> 
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Tekstur</strong></label>
                        <div class="col-sm-4">
                            <label class="me-2"><input type="radio" name="luka_kulit" value="ada"> Licin </label>
                            <label><input type="radio" name="luka_kulit" value="tidak"> Keriput </label>
                            <label><input type="radio" name="luka_kulit" value="tidak"> Kasar  </label>
                        </div>
                        
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Kelainan</strong></label>
                        <div class="col-sm-9">
                            <textarea class="form-control" rows="2" name="kelainan_genetalia"></textarea>
                        </div>
                    </div>
                    <textarea class="form-control mt-2" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
                </div>
                <div class="col-sm-1 d-flex align-items-center justify-content-center">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" style="transform: scale(1.5);">
                    </div>
                </div>
            </div>
<HR>
<div class="row mb-2">
                <label class="col-sm-12 text-primary"><strong>p. Kuku</strong></label>
            </div>
            <div class="row mb-3">
                <div class="col-sm-11">
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Clubbing Finger</strong></label>
                        <div class="col-sm-9">
                            <label class="me-3"><input type="radio" name="clubbing_finger" value="ya"> Ya</label>
                            <label><input type="radio" name="clubbing_finger" value="tidak"> Tidak</label>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Capillary Refill Time</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="capillary_refill_time">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Keadaan</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="keadaan_kuku">
                        </div>
                    </div>
                    <textarea class="form-control mt-2" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
                </div>
                <div class="col-sm-1 d-flex align-items-center justify-content-center">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" style="transform: scale(1.5);">
                    </div>
                </div>
            </div>

            <hr>

            <div class="row mb-2">
                <label class="col-sm-12 text-primary"><strong>q. Status Neurologi</strong></label>
            </div>

            <div class="row mb-3">
                <div class="col-sm-11">
                    <div class="row mb-2">
                        <label class="col-sm-12"><strong>1) Saraf-saraf Kranial</strong></label>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-4 col-form-label"><strong>a) Nervus I (Olfactorius) - Penciuman</strong></label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="nervus1_penciuman">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-4 col-form-label"><strong>b) Nervus II (Opticus) - Penglihatan</strong></label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="nervus2_penglihatan">
                        </div>
                    </div>

                    <div class="row mb-2">
                        <label class="col-sm-12"><strong>c) Nervus III, IV, VI (Oculomotorius, Trochlearis, Abducens)</strong></label>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-4 col-form-label"><strong>Konstriksi Pupil</strong></label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="konstriksi_pupil">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-4 col-form-label"><strong>Gerakan Kelopak Mata</strong></label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="gerakan_kelopak">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-4 col-form-label"><strong>Pergerakan Bola Mata</strong></label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="gerakan_bola_mata">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-4 col-form-label"><strong>Pergerakan Mata ke Bawah & Dalam</strong></label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="gerakan_mata_bawah">
                        </div>
                    </div>

                    <div class="row mb-2">
                        <label class="col-sm-12"><strong>d) Nervus V (Trigeminus)</strong></label>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-4 col-form-label"><strong>Refleks Dagu</strong></label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="refleks_dagu">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-4 col-form-label"><strong>Refleks Cornea</strong></label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="refleks_cornea">
                        </div>
                    </div>

                    <div class="row mb-2">
                        <label class="col-sm-12"><strong>e) Nervus VII (Facialis)</strong></label>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-4 col-form-label"><strong>Pengecapan 2/3 Lidah Bagian Depan</strong></label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="pengecapan_depan">
                        </div>
                    </div>

                    <div class="row mb-2">
                        <label class="col-sm-12"><strong>f) Nervus VIII (Acusticus)</strong></label>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-4 col-form-label"><strong>Fungsi Pendengaran</strong></label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="fungsi_pendengaran">
                        </div>
                    </div>

                    <div class="row mb-2">
                        <label class="col-sm-12"><strong>g) Nervus IX & X (Glossopharyngeus dan Vagus)</strong></label>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-4 col-form-label"><strong>Refleks Menelan</strong></label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="refleks_menelan_neuro">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-4 col-form-label"><strong>Refleks Muntah</strong></label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="refleks_muntah">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-4 col-form-label"><strong>Pengecapan 1/3 Lidah Belakang</strong></label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="pengecapan_belakang">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-4 col-form-label"><strong>Suara</strong></label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="suara_pasien">
                        </div>
                    </div>

                    <div class="row mb-2">
                        <label class="col-sm-12"><strong>h) Nervus XI (Assesorius)</strong></label>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-4 col-form-label"><strong>Memalingkan Kepala</strong></label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="gerakan_kepala">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-4 col-form-label"><strong>Mengangkat Bahu</strong></label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="angkat_bahu">
                        </div>
                    </div>

                    <div class="row mb-2">
                        <label class="col-sm-12"><strong>i) Nervus XII (Hypoglossus)</strong></label>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-4 col-form-label"><strong>Deviasi Lidah</strong></label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="deviasi_lidah">
                        </div>
                    </div>

                    <textarea class="form-control mt-2" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
                </div>
                <div class="col-sm-1 d-flex align-items-center justify-content-center">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" style="transform: scale(1.5);">
                    </div>
                </div>
            </div>
<div class="row mb-2">
                <label class="col-sm-12"><strong>2) Tanda-tanda Peradangan Selaput Otak</strong></label>
            </div>
            <div class="row mb-3">
                <div class="col-sm-11">
                    <div class="row mb-3">
                        <label class="col-sm-4 col-form-label"><strong>Kaku Kuduk</strong></label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="kaku_kuduk">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-4 col-form-label"><strong>Kernig Sign</strong></label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="kernig_sign">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-4 col-form-label"><strong>Refleks Brudzinski</strong></label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="refleks_brudzinski">
                        </div>
                    </div>
                    <textarea class="form-control mt-2" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
                </div>
                <div class="col-sm-1 d-flex align-items-center justify-content-center">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" style="transform: scale(1.5);">
                    </div>
                </div>
            </div>

            <hr>

            <div class="row mb-2">
                <label class="col-sm-12 text-primary"><strong>b. Data Psikologis</strong></label>
            </div>
            <div class="row mb-3">
                <div class="col-sm-11">
                    <div class="row mb-3">
                        <label class="col-sm-6 col-form-label"><strong>1) Apakah yang diharapkan klien saat ini</strong></label>
                        <div class="col-sm-6">
                            <textarea class="form-control" rows="2" name="harapan_klien"></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-6 col-form-label"><strong>2) Apakah klien merasa rendah diri dengan keadaannya saat ini</strong></label>
                        <div class="col-sm-6">
                             <textarea class="form-control" rows="2" name="rendah_diri"></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-6 col-form-label"><strong>3) Bagaimana menurut klien dengan keadaannya saat ini</strong></label>
                        <div class="col-sm-6">
                            <textarea class="form-control" rows="2" name="pendapat_keadaan"></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-6 col-form-label"><strong>4) Apakah klien tinggal di rumah sendiri atau rumah kontrakan</strong></label>
                        <div class="col-sm-6">
                            <textarea class="form-control" rows="2" name="status_rumah"></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-6 col-form-label"><strong>5) Apakah hubungan antar keluarga harmonis atau berjauhan</strong></label>
                        <div class="col-sm-6">
                            <textarea class="form-control" rows="2" name="hubungan_keluarga"></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-6 col-form-label"><strong>6) Siapakah yang mengambil keputusan dalam keluarga</strong></label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" name="pengambil_keputusan">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-6 col-form-label"><strong>7) Apakah klien merasa cukup dengan keadaan ekonomi keluarganya saat ini</strong></label>
                        <div class="col-sm-6">
                          <textarea class="form-control" rows="2" name="ekonomi_cukup"></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-6 col-form-label"><strong>8) Apakah hubungan antar keluarga baik</strong></label>
                        <div class="col-sm-6">
                            <textarea class="form-control" rows="2" name="hubungan_keluarga_baik"></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-6 col-form-label"><strong>9) Apakah klien aktif mengikuti kegiatan kemasyarakatan di sekitar tempat tinggalnya</strong></label>
                        <div class="col-sm-6">
                            <textarea class="form-control" rows="2" name="kegiatan_masyarakat"></textarea>
                        </div>
                    </div>
                    <textarea class="form-control mt-2" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
                </div>
                <div class="col-sm-1 d-flex align-items-center justify-content-center">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" style="transform: scale(1.5);">
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
                    <label for="datasubjektif" class="col-sm-2 col-form-label"><strong>Tanggal pemeriksaan</strong></label>
                    <div class="col-sm-9">
                        <textarea name="datasubjektif" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                    </div>
                    </div>

                <!-- Bagian Data Subjektif (DS) -->
                <div class="row mb-3">
                    <label for="datasubjektif" class="col-sm-2 col-form-label"><strong>Nama Pemeriksaan</strong></label>
                    <div class="col-sm-9">
                        <textarea name="NamaPemeriksaan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentdatasubjektif" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 

                <!-- Bagian Data Objektif (DO) -->
                <div class="row mb-3">
                    <label for="dataobjektif" class="col-sm-2 col-form-label"><strong>Hasil</strong></label>
                    <div class="col-sm-9">
                        <textarea name="hasil" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentdataobjektif" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>  
                    <div class="row mb-3">
                    <label for="dataobjektif" class="col-sm-2 col-form-label"><strong>Satuan</strong></label>
                    <div class="col-sm-9">
                        <textarea name="Satuan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentdataobjektif" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>  
                      <div class="row mb-3">
                    <label for="dataobjektif" class="col-sm-2 col-form-label"><strong>Nilai Rujukan</strong></label>
                    <div class="col-sm-9">
                        <textarea name="nilairujukan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentdataobjektif" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
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
    <label class="col-sm-4 col-form-label"><strong>2) Radiologi (Tgl Pemeriksaan & Hasil)</strong></label>
    <div class="col-sm-6">
        <textarea class="form-control" rows="2" name="radiologi"></textarea>
        <textarea class="form-control mt-2" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-2 d-flex align-items-start justify-content-center">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" style="transform: scale(1.5);">
        </div>
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-4 col-form-label"><strong>3) Lainnya (USG, CT Scan, dll)</strong></label>
    <div class="col-sm-6">
        <textarea class="form-control" rows="2" name="data_penunjang_lain"></textarea>
        <textarea class="form-control mt-2" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-2 d-flex align-items-start justify-content-center">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" style="transform: scale(1.5);">
        </div>
    </div>
</div></div>
    </form>
    </div>                 
                    <?php include "tab_navigasi.php"; ?>

</section>              
</main>
                
                 
