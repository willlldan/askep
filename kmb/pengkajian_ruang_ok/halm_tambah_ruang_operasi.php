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
        <h1><strong>Pengkajian Ruang OK</strong></h1>
        <!-- <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Home</a></li>
          <li class="breadcrumb-item active">Dashboard</li>
        </ol>
        </nav> -->
    </div><!-- End Page Title -->
    <br>

     <ul class="nav nav-tabs custom-tabs">

    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'lp_ruangok' ? 'active' : '' ?>"
        href="index.php?page=kmb/pengkajian_ruang_ok&tab=lp_ruangok">
        Format Laporan Pendahuluan Ruang OK</a>
    </li>

    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'ruang_operasi' ? 'active' : '' ?>"
        href="index.php?page=kmb/pengkajian_ruang_ok&tab=ruang_operasi">
        Laporan Ruang Operasi
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'resume' ? 'active' : '' ?>"
       href="index.php?page=kmb/pengkajian_ruang_ok&tab=resume">
        Format Resume Ruang OK
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'analisa' ? 'active' : '' ?>"
       href="index.php?page=kmb/pengkajian_ruang_ok&tab=analisa">
        Analisa Data        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'diagnosa' ? 'active' : '' ?>"
       href="index.php?page=kmb/pengkajian_ruang_ok&tab=diagnosa">
        Diagnosa Keperawatan
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'rencana' ? 'active' : '' ?>"
       href="index.php?page=kmb/pengkajian_ruang_ok&tab=rencana">
        Rencana Keperawatan
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'implementasi' ? 'active' : '' ?>"
       href="index.php?page=kmb/pengkajian_ruang_ok&tab=implementasi">
        Implementasi Keperawatan
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'evaluasi' ? 'active' : '' ?>"
       href="index.php?page=kmb/pengkajian_ruang_ok&tab=evaluasi">
        Evaluasi Keperawatan
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
              <h5 class="card-title mb-1"><strong>LAPORAN RUANG OPERASI</strong></h5>

<!-- NAMA MAHASISWA -->
<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>Nama Mahasiswa</strong></label>

<div class="col-sm-9">
<input type="text" class="form-control" name="nama_mahasiswa">

<textarea class="form-control mt-2" rows="2"
placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
</div>

<div class="col-sm-1">
<div class="form-check">
<input class="form-check-input" type="checkbox">
</div>
</div>
</div>

<!-- NIM -->
<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>NIM</strong></label>

<div class="col-sm-9">
<input type="text" class="form-control" name="nim">

<textarea class="form-control mt-2" rows="2"
placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
</div>

<div class="col-sm-1">
<div class="form-check">
<input class="form-check-input" type="checkbox">
</div>
</div>
</div>

<!-- KELOMPOK -->
<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>Kelompok</strong></label>

<div class="col-sm-9">
<input type="text" class="form-control" name="kelompok">

<textarea class="form-control mt-2" rows="2"
placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
</div>

<div class="col-sm-1">
<div class="form-check">
<input class="form-check-input" type="checkbox">
</div>
</div>
</div>

<!-- TEMPAT DINAS -->
<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>Tempat Dinas</strong></label>

<div class="col-sm-9">
<input type="text" class="form-control" name="tempat_dinas">

<textarea class="form-control mt-2" rows="2"
placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
</div>

<div class="col-sm-1">
<div class="form-check">
<input class="form-check-input" type="checkbox">
</div>
</div>
</div>

<hr>

<!-- A IDENTITAS KLIEN -->
<div class="row mb-2">
<label class="col-sm-12 text-primary"><strong>A. IDENTITAS KLIEN</strong></label>
</div>

<!-- NAMA -->
<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>Nama</strong></label>

<div class="col-sm-9">
<input type="text" class="form-control" name="nama_klien">

<textarea class="form-control mt-2" rows="2"
placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
</div>

<div class="col-sm-1">
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
placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
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
placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
</div>

<div class="col-sm-1">
<div class="form-check">
<input class="form-check-input" type="checkbox">
</div>
</div>
</div>

<!-- TGL MASUK RS -->
<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>Tgl Masuk RS</strong></label>

<div class="col-sm-9">
<input type="date" class="form-control" name="tgl_masuk_rs">

<textarea class="form-control mt-2" rows="2"
placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
</div>

<div class="col-sm-1">
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

<div class="col-sm-1">
<div class="form-check">
<input class="form-check-input" type="checkbox">
</div>
</div>
</div>

<!-- JENIS OPERASI -->
<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>Jenis Operasi</strong></label>

<div class="col-sm-9">
<input type="text" class="form-control" name="jenis_operasi">

<textarea class="form-control mt-2" rows="2"
placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
</div>

<div class="col-sm-1">
<div class="form-check">
<input class="form-check-input" type="checkbox">
</div>
</div>
</div>


<!-- WAKTU OPERASI -->
<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>Waktu Operasi</strong></label>

<div class="col-sm-9">
<div class="row">
<div class="col-md-4">
<input type="date" class="form-control" name="tgl_operasi">
</div>

<div class="col-md-4">
<input type="time" class="form-control" name="pukul_mulai">
</div>

<div class="col-md-4">
<input type="time" class="form-control" name="pukul_selesai">
</div>
</div>

<textarea class="form-control mt-2" rows="2"
placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>

</div>

<div class="col-sm-1">
<div class="form-check">
<input class="form-check-input" type="checkbox">
</div>
</div>
</div>

<!-- B PERSIAPAN -->
<div class="row mb-2">
<label class="col-sm-12 text-primary"><strong>B. PERSIAPAN</strong></label>
</div>

<!-- ALAT -->
<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>1. Alat</strong></label>
<div class="col-sm-9">

</div>
</div>
<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>Steril</strong></label>

<div class="col-sm-9">
<textarea class="form-control" rows="3" name="persiapan_klien"></textarea>

<textarea class="form-control mt-2" rows="2"
placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>

</div>
</div>

<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>Non Steril</strong></label>

<div class="col-sm-9">
<textarea class="form-control" rows="3" name="persiapan_klien"></textarea>

<textarea class="form-control mt-2" rows="2"
placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>

</div>
</div>
<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>Anestesi</strong></label>

<div class="col-sm-9">
<textarea class="form-control" rows="3" name="persiapan_klien"></textarea>

<textarea class="form-control mt-2" rows="2"
placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>

</div>
</div>

<!-- KLIEN -->
<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>2. Klien</strong></label>
</div>

<!-- JENIS ANESTESI -->
<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>Jenis Anestesi</strong></label>

<div class="col-sm-9">
<input type="text" class="form-control" name="jenis_anestesi">

<textarea class="form-control mt-2" rows="2"
placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
</div>

<div class="col-sm-1">
<div class="form-check">
<input class="form-check-input" type="checkbox">
</div>
</div>
</div>

<!-- LINGKUNGAN -->
<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>3. Lingkungan</strong></label>

<div class="col-sm-9">
<textarea class="form-control" rows="3" name="persiapan_lingkungan"></textarea>

<textarea class="form-control mt-2" rows="2"
placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>

</div>

<div class="col-sm-1">
<div class="form-check">
<input class="form-check-input" type="checkbox">
</div>
</div>
</div>

<!-- LAB -->
<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>4.   Hasil pemeriksaan Laboratorium</strong></label>

<div class="col-sm-9">
<textarea class="form-control" rows="3" name="hasil_lab"></textarea>

<textarea class="form-control mt-2" rows="2"
placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>

</div>

<div class="col-sm-1">
<div class="form-check">
<input class="form-check-input" type="checkbox">
</div>
</div>
</div>

<!-- C TINDAKAN -->
<div class="row mb-2">
<label class="col-sm-12 text-primary"><strong>C. TINDAKAN</strong></label>
</div>

<div class="row mb-3">
<label class="col-sm-2 col-form-label"></label>

<div class="col-sm-9">
<textarea class="form-control" rows="5" name="tindakan_operasi"
placeholder="Tuliskan tindakan mulai dari anestesi sampai observasi 2 jam setelah operasi"></textarea>

<textarea class="form-control mt-2" rows="2"
placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>

</div>

<div class="col-sm-1">
<div class="form-check">
<input class="form-check-input" type="checkbox">
</div>
</div>
</div>

<!-- D KESIMPULAN -->
<div class="row mb-2">
<label class="col-sm-12 text-primary"><strong>D. KESIMPULAN</strong></label>
</div>

<div class="row mb-3">
<label class="col-sm-2 col-form-label"></label>

<div class="col-sm-9">
<textarea class="form-control" rows="5" name="kesimpulan"></textarea>

<textarea class="form-control mt-2" rows="2"
placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>

</div>

<div class="col-sm-1">
<div class="form-check">
<input class="form-check-input" type="checkbox">
</div>
</div>
</div>
                    
<?php include "tab_navigasi.php"; ?>
</section>              
</main>
                
                 

