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

    <!-- Card Identitas -->
<div class="card">
            <div class="card-body">
    <h5 class="card-title"><strong>DATA MAHASISWA</strong></h5>

                <!-- General Form Elements -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">
                
                <!-- Bagian Nama Mahasiswa -->
                <div class="row mb-3">
                    <label for="namamahasiswa" class="col-sm-2 col-form-label"><strong>Nama Mahasiswa</strong></label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="namamahasiswa" required>
                        <div class="invalid-feedback">
                            Harap isi Nama Mahasiswa.
                        </div>
                    </div>
                </div>

                <!-- Bagian NPM -->
                <div class="row mb-3">
                    <label for="npm" class="col-sm-2 col-form-label"><strong>NPM</strong></label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="npm" required>
                        <div class="invalid-feedback">
                            Harap isi NPM.
                        </div>
                    </div>
                </div>

                <!-- Bagian Tanggal Pengkajian -->
                <div class="row mb-3">
                    <label for="tglpengkajian" class="col-sm-2 col-form-label"><strong>Tanggal Pengkajian</strong></label>
                    <div class="col-sm-9">
                        <input type="datetime-local" class="form-control" id="tglpengkajian" name="tglpengkajian" required>
                        <div class="invalid-feedback">
                            Harap isi Tanggal Pengkajian.
                        </div>
                    </div>
                </div>

                <!-- Bagian RS/Ruangan -->
                <div class="row mb-3">
                    <label for="rsruangan" class="col-sm-2 col-form-label"><strong>RS/Ruangan</strong></label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="rsruangan" required>
                        <div class="invalid-feedback">
                            Harap isi RS/Ruangan.
                        </div>
                    </div>
                </div>

                <!-- Jenis Maternitas -->

                 <?php
                    $jenisAnak = $_GET['jenisAnak'] ?? 'aster';
                   
                ?>

               <div class="row mb-3">
    <label for="jenisAnak" class="col-sm-2 col-form-label"><strong>Anak</strong></label>
    <div class="col-sm-9">

        <select class="form-select" name="jenisAnak"
        onchange="window.location=this.value" required>

        <option value="">Pilih</option>

        <option value="index.php?page=anak/format_anggrek&tab=format_laporan_pendahuluan&jenisAnak=anggrek"
        <?= $jenisAnak == 'anggrek' ? 'selected' : '' ?>>
        Format Anggrek B
        </option>

        <option value="index.php?page=anak/format_aster&tab=format_laporan_pendahuluan&jenisAnak=aster"
        <?= $jenisAnak == 'aster' ? 'selected' : '' ?>>
        Format Aster
        </option>

        <option value="index.php?page=anak/format_resume&tab=format_laporan_pendahuluan&jenisAnak=poli_anak"
        <?= $jenisAnak == 'poli_anak' ? 'selected' : '' ?>>
        FORMAT RESUME KEPERAWATAN POLI ANAK
        </option>

        </select>

        <div class="invalid-feedback">
            Harap isi Jenis Anak.
        </div>

    </div>
</div>


             </div>
    </div>
    <!-- Card Identitas -->

    <div class="pagetitle">
        <h1><strong>Anak Bayi Baru Lahir</strong></h1>
    </div><!-- End Page Title -->
    <br>
<ul class="nav nav-tabs custom-tabs">

<li class="nav-item">
    <a class="nav-link <?= ($_GET['tab'] ?? 'format_laporan_pendahuluan') == 'format_laporan_pendahuluan' ? 'active' : '' ?>"
    href="index.php?page=anak/format_aster&tab=format_laporan_pendahuluan">
    Format Pengkajian Bayi Baru Lahir
    </a>
</li>

<li class="nav-item">
    <a class="nav-link <?= ($_GET['tab'] ?? '') == 'diagnosa' ? 'active' : '' ?>"
    href="index.php?page=anak/format_aster&tab=diagnosa">
    Diagnosa Keperawatan    </a>
</li>
<li class="nav-item">
    <a class="nav-link <?= ($_GET['tab'] ?? '') == 'rencana' ? 'active' : '' ?>"
    href="index.php?page=anak/format_aster&tab=rencana">
    Rencana Keperawatan
    </a>
</li>
<li class="nav-item">
    <a class="nav-link <?= ($_GET['tab'] ?? '') == 'implementasi' ? 'active' : '' ?>"
    href="index.php?page=anak/format_aster&tab=implementasi">
    Implementasi Keperawatan
    </a>
</li>
<li class="nav-item">
    <a class="nav-link <?= ($_GET['tab'] ?? '') == 'evaluasi' ? 'active' : '' ?>"
    href="index.php?page=anak/format_aster&tab=evaluasi">
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
              <h5 class="card-title mb-1"><strong>Format Pengkajian Anak</strong></h5>

                <!-- General Form Elements -->
                <div class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

                 <!-- No Registrasi -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>No Registrasi</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="no_registrasi">
         <textarea class="form-control mt-2" id="commentpustaka" rows="2"
            placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1 d-flex align-items-start">
        <div class="form-check">
            <input class="form-check-input" type="checkbox"
                onchange="document.getElementById('commentpustaka').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</div>

<!-- Nama Pengkaji -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Nama Pengkaji</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="nama_pengkaji">
        <textarea class="form-control mt-2" id="commentpustaka" rows="2"
            placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1 d-flex align-items-start">
        <div class="form-check">
            <input class="form-check-input" type="checkbox"
                onchange="document.getElementById('commentpustaka').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</div>
<!-- Hari Tanggal -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Hari Tanggal</strong></label>
    <div class="col-sm-9">
        <input type="date" class="form-control" name="hari_tanggal">
        <textarea class="form-control mt-2" id="commentpustaka" rows="2"
            placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1 d-flex align-items-start">
        <div class="form-check">
            <input class="form-check-input" type="checkbox"
                onchange="document.getElementById('commentpustaka').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</div>

<!-- Waktu Pengkajian -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Waktu Pengkajian</strong></label>
    <div class="col-sm-9">
        <input type="time" class="form-control" name="waktu_pengkajian">
         <textarea class="form-control mt-2" id="commentpustaka" rows="2"
            placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1 d-flex align-items-start">
        <div class="form-check">
            <input class="form-check-input" type="checkbox"
                onchange="document.getElementById('commentpustaka').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</div>
                </div>
                </div>
                </div>

   <div class="card">
            <div class="card-body">
              <h5 class="card-title mb-1"><strong>I. Identitas</strong></h5>
<!-- I. IDENTITAS -->
<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>Identitas Klien</strong></label>
</div>

<!-- Identitas Klien -->


<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Nama</strong></label>
    <div class="col-sm-9">
        <input type="text" name="nama_klien" class="form-control">
      <textarea class="form-control mt-2" id="commentpustaka" rows="2"
            placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1 d-flex align-items-start">
        <div class="form-check">
            <input class="form-check-input" type="checkbox"
                onchange="document.getElementById('commentpustaka').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Umur</strong></label>
    <div class="col-sm-9">
        <input type="text" name="umur" class="form-control">
          <textarea class="form-control mt-2" id="commentpustaka" rows="2"
            placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1 d-flex align-items-start">
        <div class="form-check">
            <input class="form-check-input" type="checkbox"
                onchange="document.getElementById('commentpustaka').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</div>
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Jenis Kelamin</strong></label>
    <div class="col-sm-9">
        <input type="text" name="jk" class="form-control">
          <textarea class="form-control mt-2" id="commentpustaka" rows="2"
            placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1 d-flex align-items-start">
        <div class="form-check">
            <input class="form-check-input" type="checkbox"
                onchange="document.getElementById('commentpustaka').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</div>
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Tanggal Lahir</strong></label>
    <div class="col-sm-9">
        <input type="date" name="tgl_lahir" class="form-control">
        <textarea class="form-control mt-2" id="commentpustaka" rows="2"
            placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1 d-flex align-items-start">
        <div class="form-check">
            <input class="form-check-input" type="checkbox"
                onchange="document.getElementById('commentpustaka').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Apgar Score</strong></label>
    <div class="col-sm-9">
        <input type="text" name="apgar" class="form-control">
        <textarea class="form-control mt-2" id="commentpustaka" rows="2"
            placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1 d-flex align-items-start">
        <div class="form-check">
            <input class="form-check-input" type="checkbox"
                onchange="document.getElementById('commentpustaka').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Berat Badan Lahir</strong></label>
    <div class="col-sm-9">
        <input type="text" name="bb_lahir" class="form-control">
        <textarea class="form-control mt-2" id="commentpustaka" rows="2"
            placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1 d-flex align-items-start">
        <div class="form-check">
            <input class="form-check-input" type="checkbox"
                onchange="document.getElementById('commentpustaka').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Berat Badan Saat Pengkajian</strong></label>
    <div class="col-sm-9">
        <input type="text" name="bb_sekarang" class="form-control">
         <textarea class="form-control mt-2" id="commentpustaka" rows="2"
            placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1 d-flex align-items-start">
        <div class="form-check">
            <input class="form-check-input" type="checkbox"
                onchange="document.getElementById('commentpustaka').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Alamat</strong></label>
    <div class="col-sm-9">
        <textarea name="alamat" class="form-control"></textarea>
       <textarea class="form-control mt-2" id="commentpustaka" rows="2"
            placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1 d-flex align-items-start">
        <div class="form-check">
            <input class="form-check-input" type="checkbox"
                onchange="document.getElementById('commentpustaka').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Usia Gestasi</strong></label>
    <div class="col-sm-9">
        <input type="text" name="usia_gestasi" class="form-control">
         <textarea class="form-control mt-2" id="commentpustaka" rows="2"
            placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1 d-flex align-items-start">
        <div class="form-check">
            <input class="form-check-input" type="checkbox"
                onchange="document.getElementById('commentpustaka').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</div>

<!-- Orang Tua -->
<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>Identitas Orang Tua</strong></label>
</div>

<div class="row mb-2">
    <label class="col-sm-12"><strong>Ayah</strong></label>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Nama </strong></label>
    <div class="col-sm-9">
        <input type="text" name="nama_ayah" class="form-control">
       <textarea class="form-control mt-2" id="commentpustaka" rows="2"
            placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1 d-flex align-items-start">
        <div class="form-check">
            <input class="form-check-input" type="checkbox"
                onchange="document.getElementById('commentpustaka').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Usia </strong></label>
    <div class="col-sm-9">
        <input type="text" name="usia_ayah" class="form-control">
         <textarea class="form-control mt-2" id="commentpustaka" rows="2"
            placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1 d-flex align-items-start">
        <div class="form-check">
            <input class="form-check-input" type="checkbox"
                onchange="document.getElementById('commentpustaka').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Pekerjaan </strong></label>
    <div class="col-sm-9">
        <input type="text" name="pekerjaan_ayah" class="form-control">
        <textarea class="form-control mt-2" id="commentpustaka" rows="2"
            placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1 d-flex align-items-start">
        <div class="form-check">
            <input class="form-check-input" type="checkbox"
                onchange="document.getElementById('commentpustaka').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Alamat </strong></label>
    <div class="col-sm-9">
        <textarea name="alamat_ayah" class="form-control"></textarea>
         <textarea class="form-control mt-2" id="commentpustaka" rows="2"
            placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1 d-flex align-items-start">
        <div class="form-check">
            <input class="form-check-input" type="checkbox"
                onchange="document.getElementById('commentpustaka').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</div>

<div class="row mb-2">
    <label class="col-sm-12"><strong>Ibu</strong></label>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Nama </strong></label>
    <div class="col-sm-9">
        <input type="text" name="nama_ibu" class="form-control">
         <textarea class="form-control mt-2" id="commentpustaka" rows="2"
            placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1 d-flex align-items-start">
        <div class="form-check">
            <input class="form-check-input" type="checkbox"
                onchange="document.getElementById('commentpustaka').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Usia </strong></label>
    <div class="col-sm-9">
        <input type="text" name="usia_ibu" class="form-control">
          <textarea class="form-control mt-2" id="commentpustaka" rows="2"
            placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1 d-flex align-items-start">
        <div class="form-check">
            <input class="form-check-input" type="checkbox"
                onchange="document.getElementById('commentpustaka').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Pekerjaan </strong></label>
    <div class="col-sm-9">
        <input type="text" name="pekerjaan_ibu" class="form-control">
         <textarea class="form-control mt-2" id="commentpustaka" rows="2"
            placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1 d-flex align-items-start">
        <div class="form-check">
            <input class="form-check-input" type="checkbox"
                onchange="document.getElementById('commentpustaka').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</div>
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Status Gravida</strong></label>
    <div class="col-sm-9">
        <input type="text" name="gravida" class="form-control">
          <textarea class="form-control mt-2" id="commentpustaka" rows="2"
            placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1 d-flex align-items-start">
        <div class="form-check">
            <input class="form-check-input" type="checkbox"
                onchange="document.getElementById('commentpustaka').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</div>
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Pemeriksaan Kehamilan</strong></label>
    <div class="col-sm-9">
        <input type="text" name="gravida" class="form-control">
         <textarea class="form-control mt-2" id="commentpustaka" rows="2"
            placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1 d-flex align-items-start">
        <div class="form-check">
            <input class="form-check-input" type="checkbox"
                onchange="document.getElementById('commentpustaka').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</div>
<!-- Riwayat -->
<div class="row mb-2">
    <label class="col-sm-12"><strong>Riwayat Kehamilan</strong></label>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Status GPA</strong></label>
    <div class="col-sm-9">
        <input type="text" name="gpa" class="form-control">
         <textarea class="form-control mt-2" id="commentpustaka" rows="2"
            placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1 d-flex align-items-start">
        <div class="form-check">
            <input class="form-check-input" type="checkbox"
                onchange="document.getElementById('commentpustaka').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</div>
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Penggunaan Obat-obatan selama kehamilan</strong></label>
    <div class="col-sm-9">
        <textarea name="obat" class="form-control"></textarea>
        <textarea class="form-control mt-2" id="commentpustaka" rows="2"
            placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1 d-flex align-items-start">
        <div class="form-check">
            <input class="form-check-input" type="checkbox"
                onchange="document.getElementById('commentpustaka').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</div>
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Imunisasi TT	</strong></label>
    <div class="col-sm-9">
        <textarea name="obat" class="form-control"></textarea>
         <textarea class="form-control mt-2" id="commentpustaka" rows="2"
            placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1 d-flex align-items-start">
        <div class="form-check">
            <input class="form-check-input" type="checkbox"
                onchange="document.getElementById('commentpustaka').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</div>


<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Komplikasi penyakit selama kehamilan</strong></label>
    <div class="col-sm-9">
        <textarea name="komplikasi" class="form-control"></textarea>
       <textarea class="form-control mt-2" id="commentpustaka" rows="2"
            placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1 d-flex align-items-start">
        <div class="form-check">
            <input class="form-check-input" type="checkbox"
                onchange="document.getElementById('commentpustaka').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</div>
</div>
</div>
   <div class="card">
            <div class="card-body">
              <h5 class="card-title mb-1"><strong>Riwayat Persalinan Sekarang</strong></h5>
<!-- Riwayat Persalinan Sekarang -->


<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Riwayat Persalinan</strong></label>
    <div class="col-sm-9">
        <textarea name="riwayat_persalinan_sekarang" class="form-control"></textarea>
       <textarea class="form-control mt-2" id="commentpustaka" rows="2"
            placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1 d-flex align-items-start">
        <div class="form-check">
            <input class="form-check-input" type="checkbox"
                onchange="document.getElementById('commentpustaka').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Tempat Persalinan</strong></label>
    <div class="col-sm-9">
        <input type="text" name="tempat_persalinan" class="form-control">
       <textarea class="form-control mt-2" id="commentpustaka" rows="2"
            placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1 d-flex align-items-start">
        <div class="form-check">
            <input class="form-check-input" type="checkbox"
                onchange="document.getElementById('commentpustaka').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Jenis Persalinan</strong></label>
    <div class="col-sm-9">
        <input type="text" name="jenis_persalinan" class="form-control">
         <textarea class="form-control mt-2" id="commentpustaka" rows="2"
            placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1 d-flex align-items-start">
        <div class="form-check">
            <input class="form-check-input" type="checkbox"
                onchange="document.getElementById('commentpustaka').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Persentasi</strong></label>
    <div class="col-sm-9">
        <input type="text" name="persentasi" class="form-control">
     <textarea class="form-control mt-2" id="commentpustaka" rows="2"
            placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1 d-flex align-items-start">
        <div class="form-check">
            <input class="form-check-input" type="checkbox"
                onchange="document.getElementById('commentpustaka').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Air Ketuban</strong></label>
    <div class="col-sm-9">
        <input type="text" name="air_ketuban" class="form-control">
       <textarea class="form-control mt-2" id="commentpustaka" rows="2"
            placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1 d-flex align-items-start">
        <div class="form-check">
            <input class="form-check-input" type="checkbox"
                onchange="document.getElementById('commentpustaka').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Lama Persalinan Kala II</strong></label>
    <div class="col-sm-9">
        <input type="text" name="lama_kala2" class="form-control">
      <textarea class="form-control mt-2" id="commentpustaka" rows="2"
            placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1 d-flex align-items-start">
        <div class="form-check">
            <input class="form-check-input" type="checkbox"
                onchange="document.getElementById('commentpustaka').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</div>

<!-- Keadaan Tali Pusat -->
<div class="row mb-2">
    <label class="col-sm-12"><strong>Keadaan Tali Pusat</strong></label>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Panjang</strong></label>
    <div class="col-sm-9">
        <input type="text" name="panjang_tali_pusat" class="form-control">
       <textarea class="form-control mt-2" id="commentpustaka" rows="2"
            placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1 d-flex align-items-start">
        <div class="form-check">
            <input class="form-check-input" type="checkbox"
                onchange="document.getElementById('commentpustaka').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Jumlah Vena</strong></label>
    <div class="col-sm-9">
        <input type="text" name="vena" class="form-control">
   <textarea class="form-control mt-2" id="commentpustaka" rows="2"
            placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1 d-flex align-items-start">
        <div class="form-check">
            <input class="form-check-input" type="checkbox"
                onchange="document.getElementById('commentpustaka').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Jumlah Arteri</strong></label>
    <div class="col-sm-9">
        <input type="text" name="arteri" class="form-control">
        <textarea class="form-control mt-2" id="commentpustaka" rows="2"
            placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1 d-flex align-items-start">
        <div class="form-check">
            <input class="form-check-input" type="checkbox"
                onchange="document.getElementById('commentpustaka').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Warna</strong></label>
    <div class="col-sm-9">
        <input type="text" name="warna_tali_pusat" class="form-control">
       <textarea class="form-control mt-2" id="commentpustaka" rows="2"
            placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1 d-flex align-items-start">
        <div class="form-check">
            <input class="form-check-input" type="checkbox"
                onchange="document.getElementById('commentpustaka').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Kelainan</strong></label>
    <div class="col-sm-9">
        <textarea name="kelainan_tali_pusat" class="form-control"></textarea>
     <textarea class="form-control mt-2" id="commentpustaka" rows="2"
            placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1 d-flex align-items-start">
        <div class="form-check">
            <input class="form-check-input" type="checkbox"
                onchange="document.getElementById('commentpustaka').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</div>
</div>
</div>
   <div class="card">
            <div class="card-body">
              <h5 class="card-title mb-1"><strong>A. Pengkajian</strong>


<!-- Keadaan Bayi Saat Lahir -->
<div class="row mb-2">
    <label class="col-sm-12 "><strong>Keadaan Bayi Saat Lahir</strong></label>
</div></h5>

<!-- APGAR Score -->
<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>Apgar Score</strong></label>
</div>

<div class="row mb-3">
    <div class="col-sm-12 text-center">
        <img src="assets/img/apgar.png" class="img-fluid" style="max-height:1000px;">
    </div>
</div>
<style>
    table {
        width: 100%;
        border-collapse: collapse;
        font-family: "Times New Roman", serif;
    }

    th, td {
        border: 1px solid black;
        padding: 8px;
        text-align: center;
        vertical-align: middle;
    }

    th {
        background-color: #eaeaea;
    }

    .left {
        text-align: left;
    }

    .checkbox {
        display: flex;
        justify-content: center;
        gap: 5px;
    }
</style>

<table>
    <tr>
        <th rowspan="2">TANDA</th>
        <th colspan="6">SCORE</th>
        <th colspan="2">Jumlah</th>
    </tr>
    <tr>
        <th colspan="2">0</th>
        <th colspan="2">1</th>
        <th colspan="2">2</th>
        <th>1 mnt</th>
        <th>5 mnt</th>
    </tr>

    <!-- 1 -->
  <tr>
    <td class="left">1. Frekuensi Jantung</td>

    <td>
        <input type="checkbox">
        <input type="radio" name="frekuensi_jantung" value="0">
    </td>
    <td>Tidak ada</td>

    <td>
        <input type="checkbox">
        <input type="radio" name="frekuensi_jantung" value="1">
    </td>
    <td>&lt;100</td>

    <td>
        <input type="checkbox">
        <input type="radio" name="frekuensi_jantung" value="2">
    </td>
    <td>&gt;100</td>

    <td></td>
    <td></td>
</tr>

   <!-- 2 -->
<tr>
    <td class="left">2. Usaha Nafas</td>

    <td>        <input type="checkbox">
        <input type="radio" name="usaha_nafas" value="0"></td>
    <td>Tidak ada</td>

    <td>        <input type="checkbox">

        <input type="radio" name="usaha_nafas" value="1"></td>
    <td>Lambat</td>

    <td>
            <input type="checkbox">    
    <input type="radio" name="usaha_nafas" value="2"></td>
    <td>Menangis kuat</td>

    <td></td>
    <td></td>
</tr>

<!-- 3 -->
<tr>
    <td class="left">3. Tonus otot</td>

    <td><input type="checkbox">
        <input type="radio" name="tonus_otot" value="0"></td>
    <td>Lumpuh</td>

    <td><input type="checkbox">
        <input type="radio" name="tonus_otot" value="1"></td>
    <td>Ekstremitas fleksi sedikit</td>

    <td><input type="checkbox">
        <input type="radio" name="tonus_otot" value="2"></td>
    <td>Gerakan aktif</td>

    <td></td>
    <td></td>
</tr>

<!-- 4 -->
<tr>
    <td class="left">4. Refleks</td>

    <td><input type="checkbox">
    <input type="radio" name="refleks" value="0"></td>
    <td>Tidak bereaksi</td>

    <td><input type="checkbox">
    <input type="radio" name="refleks" value="1"></td>
    <td>Gerakan sedikit</td>

    <td><input type="checkbox">
    <input type="radio" name="refleks" value="2"></td>
    <td>Reaksi melawan</td>

    <td></td>
    <td></td>
</tr>

<!-- 5 -->
<tr>
    <td class="left">5. Warna Kulit</td>

    <td><input type="checkbox">
    <input type="radio" name="warna_kulit" value="0"></td>
    <td>Biru / pucat</td>

    <td><input type="checkbox">
    <input type="radio" name="warna_kulit" value="1"></td>
    <td>Tubuh kemerahan, tangan & kaki biru</td>

    <td><input type="checkbox">
    <input type="radio" name="warna_kulit" value="2"></td>
    <td>Kemerahan</td>

    <td></td>
    <td></td>
</tr>

<!-- Jumlah -->
<tr>
    <td colspan="7"><strong>Jumlah</strong></td>
    <td></td>
    <td></td>
</tr>
</table>
<div style="margin-top:10px; text-align:center; font-family: 'Times New Roman', serif; font-size:20px;">
    <strong>Ket:</strong>
    <span style="margin-left:10px;">
        <input type="checkbox" disabled> Penilaian menit ke-1
    </span>
    <span style="margin-left:20px;">
        <input type="radio" disabled> Penilaian menit ke-5
    </span>
</div>
<hr>
<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>Maturational Assessment Of Gestational Age (New Ballard Score)</strong></label>
</div>



     <!-- NAME -->
<div class="row mb-3">
    <label for="name" class="col-sm-2 col-form-label"><strong>Name</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="name" id="name">
        <textarea class="form-control mt-2" id="commentname" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                       </div>
    <div class="col-sm-1 d-flex align-items-start">
        <input type="checkbox" disabled>
    </div>
</div>

<!-- HOSPITAL NO -->
<div class="row mb-3">
    <label for="hospital_no" class="col-sm-2 col-form-label"><strong>Hospital No.</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="hospital_no" id="hospital_no">
        <textarea class="form-control mt-2" id="commentname" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>    </div>
    <div class="col-sm-1"><input type="checkbox" disabled></div>
</div>

<!-- RACE -->
<div class="row mb-3">
    <label for="race" class="col-sm-2 col-form-label"><strong>Race</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="race" id="race">
        <textarea class="form-control mt-2" id="commentname" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
    </div>
    <div class="col-sm-1"><input type="checkbox" disabled></div>
</div>

<!-- DOB -->
<div class="row mb-3">
    <label for="datetime_birth" class="col-sm-2 col-form-label"><strong>Date/Time Of Birth</strong></label>
    <div class="col-sm-9">
        <input type="datetime-local" class="form-control" name="datetime_birth" id="datetime_birth">
        <textarea class="form-control mt-2" id="commentname" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
    </div>
    <div class="col-sm-1"><input type="checkbox" disabled></div>
</div>

<!-- EXAM DATE -->
<div class="row mb-3">
    <label for="datetime_exam" class="col-sm-2 col-form-label"><strong>Date/Time Of Exam</strong></label>
    <div class="col-sm-9">
        <input type="datetime-local" class="form-control" name="datetime_exam" id="datetime_exam">
        <textarea class="form-control mt-2" id="commentname" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
    </div>
    <div class="col-sm-1"><input type="checkbox" disabled></div>
</div>

<!-- AGE -->
<div class="row mb-3">
    <label for="age_exam" class="col-sm-2 col-form-label"><strong>Age When Examined</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="age_exam" id="age_exam">
        <textarea class="form-control mt-2" id="commentname" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
    </div>
    <div class="col-sm-1"><input type="checkbox" disabled></div>
</div>

<!-- SEX -->
<div class="row mb-3">
    <label for="sex" class="col-sm-2 col-form-label"><strong>Sex</strong></label>
    <div class="col-sm-9">
        <select class="form-control" name="sex" id="sex">
            <option value="">-- Pilih --</option>
            <option value="L">Laki-laki</option>
            <option value="P">Perempuan</option>
        </select>
        <textarea class="form-control mt-2" id="commentname" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
    </div>
    <div class="col-sm-1"><input type="checkbox" disabled></div>
</div>

<!-- BIRTH WEIGHT -->
<div class="row mb-3">
    <label for="birth_weight" class="col-sm-2 col-form-label"><strong>Birth Weight</strong></label>
    <div class="col-sm-9">
        <input type="number" class="form-control" name="birth_weight" id="birth_weight">
        <textarea class="form-control mt-2" id="commentname" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
    </div>
    <div class="col-sm-1"><input type="checkbox" disabled></div>
</div>

<!-- LENGTH -->
<div class="row mb-3">
    <label for="length" class="col-sm-2 col-form-label"><strong>Length</strong></label>
    <div class="col-sm-9">
        <input type="number" class="form-control" name="length" id="length">
        <textarea class="form-control mt-2" id="commentname" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
    </div>
    <div class="col-sm-1"><input type="checkbox" disabled></div>
</div>

<!-- HEAD CIRC -->
<div class="row mb-3">
    <label for="head_circ" class="col-sm-2 col-form-label"><strong>Head Circ</strong></label>
    <div class="col-sm-9">
        <input type="number" class="form-control" name="head_circ" id="head_circ">
        <textarea class="form-control mt-2" id="commentname" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
    </div>
    <div class="col-sm-1"><input type="checkbox" disabled></div>
</div>

<!-- EXAMINER -->
<div class="row mb-3">
    <label for="examiner" class="col-sm-2 col-form-label"><strong>Examiner</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="examiner" id="examiner">
        <textarea class="form-control mt-2" id="commentname" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
    </div>
    <div class="col-sm-1"><input type="checkbox" disabled></div>
</div>
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>APGAR Score</strong></label>

    <div class="col-sm-3">
        <label for="apgar_1"><small>1 Minute</small></label>
        <input type="number" class="form-control" name="apgar_1" id="apgar_1" min="0" max="10">
    </div>

    <div class="col-sm-3">
        <label for="apgar_5"><small>5 Minutes</small></label>
        <input type="number" class="form-control" name="apgar_5" id="apgar_5" min="0" max="10">
    </div>

    <div class="col-sm-3">
        <label for="apgar_10"><small>10 Minutes</small></label>
        <input type="number" class="form-control" name="apgar_10" id="apgar_10" min="0" max="10">
    </div>
</div>
                
<style>
.table-ballard {
    width: 100%;
    border-collapse: collapse;
    font-size: 12px;
    font-family: Arial, sans-serif;
}

.table-ballard th, 
.table-ballard td {
    border: 1px solid black;
    padding: 5px;
    text-align: center;
    vertical-align: middle;
}

.table-ballard th {
    background-color: #cfe2f3;
}

.left {
    text-align: left;
    font-weight: bold;
}

.score-box {
    background-color: #cfe2f3;
    width: 80px;
}

.radio-center {
    display: flex;
    justify-content: center;
}
</style>
<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>NEUROMUSCULAR MATURITY</strong></label>
</div>

<div class="row mb-3">
    <div class="col-sm-12 text-center">
        <img src="assets/img/NEUROMUSCULAR.png" class="img-fluid" style="max-height: 2000px;">
    </div>
</div>
<table class="table-ballard">

    <!-- HEADER -->
    <tr>
        <th rowspan="2">NEUROMUSCULAR<br>MATURITY SIGN</th>
        <th colspan="7">SCORE</th>
        <th rowspan="2">RECORD SCORE HERE</th>
    </tr>
    <tr>
        <th>-1</th>
        <th>0</th>
        <th>1</th>
        <th>2</th>
        <th>3</th>
        <th>4</th>
        <th>5</th>
    </tr>

    <!-- POSTURE -->
    <tr>
        <td class="left">POSTURE</td>
        <td><input type="radio" name="posture" value="-1"></td>
        <td><input type="radio" name="posture" value="0"></td>
        <td><input type="radio" name="posture" value="1"></td>
        <td><input type="radio" name="posture" value="2"></td>
        <td><input type="radio" name="posture" value="3"></td>
        <td><input type="radio" name="posture" value="4"></td>
        <td><input type="radio" name="posture" value="5"></td>
        <td class="score-box"></td>
    </tr>

    <!-- SQUARE WINDOW -->
    <tr>
        <td class="left">SQUARE WINDOW (Wrist)</td>
        <td><input type="radio" name="square_window" value="-1"></td>
        <td><input type="radio" name="square_window" value="0"></td>
        <td><input type="radio" name="square_window" value="1"></td>
        <td><input type="radio" name="square_window" value="2"></td>
        <td><input type="radio" name="square_window" value="3"></td>
        <td><input type="radio" name="square_window" value="4"></td>
        <td><input type="radio" name="square_window" value="5"></td>
        <td class="score-box"></td>
    </tr>

    <!-- ARM RECOIL -->
    <tr>
        <td class="left">ARM RECOIL</td>
        <td><input type="radio" name="arm_recoil" value="-1"></td>
        <td><input type="radio" name="arm_recoil" value="0"></td>
        <td><input type="radio" name="arm_recoil" value="1"></td>
        <td><input type="radio" name="arm_recoil" value="2"></td>
        <td><input type="radio" name="arm_recoil" value="3"></td>
        <td><input type="radio" name="arm_recoil" value="4"></td>
        <td><input type="radio" name="arm_recoil" value="5"></td>
        <td class="score-box"></td>
    </tr>

    <!-- POPLITEAL ANGLE -->
    <tr>
        <td class="left">POPLITEAL ANGLE</td>
        <td><input type="radio" name="popliteal_angle" value="-1"></td>
        <td><input type="radio" name="popliteal_angle" value="0"></td>
        <td><input type="radio" name="popliteal_angle" value="1"></td>
        <td><input type="radio" name="popliteal_angle" value="2"></td>
        <td><input type="radio" name="popliteal_angle" value="3"></td>
        <td><input type="radio" name="popliteal_angle" value="4"></td>
        <td><input type="radio" name="popliteal_angle" value="5"></td>
        <td class="score-box"></td>
    </tr>

    <!-- SCARF SIGN -->
    <tr>
        <td class="left">SCARF SIGN</td>
        <td><input type="radio" name="scarf_sign" value="-1"></td>
        <td><input type="radio" name="scarf_sign" value="0"></td>
        <td><input type="radio" name="scarf_sign" value="1"></td>
        <td><input type="radio" name="scarf_sign" value="2"></td>
        <td><input type="radio" name="scarf_sign" value="3"></td>
        <td><input type="radio" name="scarf_sign" value="4"></td>
        <td><input type="radio" name="scarf_sign" value="5"></td>
        <td class="score-box"></td>
    </tr>

    <!-- HEEL TO EAR -->
    <tr>
        <td class="left">HEEL TO EAR</td>
        <td><input type="radio" name="heel_to_ear" value="-1"></td>
        <td><input type="radio" name="heel_to_ear" value="0"></td>
        <td><input type="radio" name="heel_to_ear" value="1"></td>
        <td><input type="radio" name="heel_to_ear" value="2"></td>
        <td><input type="radio" name="heel_to_ear" value="3"></td>
        <td><input type="radio" name="heel_to_ear" value="4"></td>
        <td><input type="radio" name="heel_to_ear" value="5"></td>
        <td class="score-box"></td>
    </tr>

    <!-- TOTAL -->
    <tr>
        <td colspan="8" style="text-align:right;"><strong>TOTAL NEUROMUSCULAR MATURITY SCORE</strong></td>
        <td class="score-box"></td>
    </tr>
</table>
<hr>
<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>Physical Maturity</strong></label>
</div>

<div class="row mb-3">
    <div class="col-sm-12 text-center">
        <img src="assets/img/PHYSICAL.png" class="img-fluid" style="max-height: 2000px;">
    </div>
</div>
<style>
.table-ballard {
    width: 100%;
    border-collapse: collapse;
    font-size: 12px;
    font-family: Arial, sans-serif;
}

.table-ballard th, 
.table-ballard td {
    border: 1px solid black;
    padding: 5px;
    text-align: center;
    vertical-align: middle;
}

.table-ballard th {
    background-color: #cfe2f3;
}

.left {
    text-align: left;
    font-weight: bold;
}

.score-box {
    background-color: #cfe2f3;
    width: 80px;
}
</style>

<table class="table-ballard">

    <!-- HEADER -->
    <tr>
        <th rowspan="2">PHYSICAL MATURITY SIGN</th>
        <th colspan="7">SCORE</th>
        <th rowspan="2">RECORD SCORE HERE</th>
    </tr>
    <tr>
        <th>-1</th>
        <th>0</th>
        <th>1</th>
        <th>2</th>
        <th>3</th>
        <th>4</th>
        <th>5</th>
    </tr>

    <!-- SKIN -->
    <tr>
        <td class="left">SKIN</td>
        <td>sticky friable transparent<br><input type="radio" name="skin" value="-1"></td>
        <td>gelatinous red translucent<br><input type="radio" name="skin" value="0"></td>
        <td>smooth pink visible veins<br><input type="radio" name="skin" value="1"></td>
        <td>superficial peeling & / or rash, few veins<br><input type="radio" name="skin" value="2"></td>
        <td>cracking pale areas rare veins<br><input type="radio" name="skin" value="3"></td>
        <td>parchment deep cracking no vessls<br><input type="radio" name="skin" value="4"></td>
        <td>leathery cracked wrinkled<br><input type="radio" name="skin" value="5"></td>
        <td class="score-box"></td>
    </tr>

    <!-- LANUGO -->
    <tr>
        <td class="left">LANUGO</td>
        <td>none<br><input type="radio" name="lanugo" value="-1"></td>
        <td>sparse<br><input type="radio" name="lanugo" value="0"></td>
        <td>abundant<br><input type="radio" name="lanugo" value="1"></td>
        <td>thinning<br><input type="radio" name="lanugo" value="2"></td>
        <td>bald areas<br><input type="radio" name="lanugo" value="3"></td>
        <td>mostly bald<br><input type="radio" name="lanugo" value="4"></td>
        <td><input type="radio" name="lanugo" value="5"></td>
        <td class="score-box"></td>
    </tr>

    <!-- PLANTAR -->
    <tr>
        <td class="left">PLANTAR SURFACE</td>
        <td>heel-toe 40mm- 50mm:-1 <40mm:-2 <br><input type="radio" name="plantar" value="-1"></td>
        <td>>50 mm no crease<br><input type="radio" name="plantar" value="0"></td>
        <td>faint red marks<br><input type="radio" name="plantar" value="1"></td>
        <td>anterior transverse crease only<br><input type="radio" name="plantar" value="2"></td>
        <td>creases ant 2/3<br><input type="radio" name="plantar" value="3"></td>
        <td>creases entire sole<br><input type="radio" name="plantar" value="4"></td>
        <td><input type="radio" name="plantar" value="5"></td>
        <td class="score-box"></td>
    </tr>

    <!-- BREAST -->
    <tr>
        <td class="left">BREAST</td>
        <td>imperceptible<br><input type="radio" name="breast" value="-1"></td>
        <td>barely perceptible<br><input type="radio" name="breast" value="0"></td>
        <td>flat areola no bud<br><input type="radio" name="breast" value="1"></td>
        <td>stippled areola 1-2 mm bud<br><input type="radio" name="breast" value="2"></td>
        <td>raised areola 3-4 mm bud<br><input type="radio" name="breast" value="3"></td>
        <td>full areola 5-10 mm bud<br><input type="radio" name="breast" value="4"></td>
        <td><input type="radio" name="breast" value="5"></td>
        <td class="score-box"></td>
    </tr>

    <!-- EYE/EAR -->
    <tr>
        <td class="left">EYE / EAR</td>
        <td>lids fused loosely: -1 tightly :-2<br><input type="radio" name="eye_ear" value="-1"></td>
        <td>lids open pinna flat stays floded<br><input type="radio" name="eye_ear" value="0"></td>
        <td>sl. curved pinna; soft; slow recoil<br><input type="radio" name="eye_ear" value="1"></td>
        <td>well-curved pinna; soft but ready recoil<br><input type="radio" name="eye_ear" value="2"></td>
        <td>formed & firm instant recoil<br><input type="radio" name="eye_ear" value="3"></td>
        <td>thick cartilage ear stiff<br><input type="radio" name="eye_ear" value="4"></td>
        <td><input type="radio" name="eye_ear" value="5"></td>
        <td class="score-box"></td>
    </tr>

    <!-- GENITAL MALE -->
    <tr>
        <td class="left">GENITALS (Male)</td>
        <td>scrotum flat, smooth<br><input type="radio" name="gen_male" value="-1"></td>
        <td>scrotum empty faint rugae<br><input type="radio" name="gen_male" value="0"></td>
        <td>testes in canal rare rugae<br><input type="radio" name="gen_male" value="1"></td>
        <td>testes descending few rugae<br><input type="radio" name="gen_male" value="2"></td>
        <td>testes down good rugae<br><input type="radio" name="gen_male" value="3"></td>
        <td>testes pendulous deep rugae<br><input type="radio" name="gen_male" value="4"></td>
        <td><input type="radio" name="gen_male" value="5"></td>
        <td class="score-box"></td>
    </tr>

    <!-- GENITAL FEMALE -->
    <tr>
        <td class="left">GENITALS (Female)</td>
        <td>clitoris prominent & labia flat<br><input type="radio" name="gen_female" value="-1"></td>
        <td>prominent clitoris & small labia minora<br><input type="radio" name="gen_female" value="0"></td>
        <td>prominent clitoris & enlarging minora<br><input type="radio" name="gen_female" value="1"></td>
        <td>majora & minora equally prominent<br><input type="radio" name="gen_female" value="2"></td>
        <td>majora large minora small<br><input type="radio" name="gen_female" value="3"></td>
        <td>majora cover clitoris & minora<br><input type="radio" name="gen_female" value="4"></td>
        <td><input type="radio" name="gen_female" value="5"></td>
        <td class="score-box"></td>
    </tr>

    <!-- TOTAL -->
    <tr>
        <td colspan="8" style="text-align:right;">
            <strong>TOTAL PHYSICAL MATURITY SCORE</strong>
        </td>
        <td class="score-box"></td>
    </tr>

</table>

<HR>
<!-- EXAMINER -->
<div class="row mb-3">
    <label for="neuromuscular" class="col-sm-2 col-form-label"><strong>Neuromuscular</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="neuromuscular" id="neuromuscular">
        <textarea class="form-control mt-2" id="commentneuromuscular" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
    </div>
    <div class="col-sm-1"><input type="checkbox" disabled></div>
</div>
<div class="row mb-3">
    <label for="physical" class="col-sm-2 col-form-label"><strong>Physical</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="physical" id="physical">
        <textarea class="form-control mt-2" id="commentphysical" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
    </div>
    <div class="col-sm-1"><input type="checkbox" disabled></div>
</div>
<div class="row mb-3">
    <label for="total" class="col-sm-2 col-form-label"><strong>Total</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="total" id="total">
        <textarea class="form-control mt-2" id="commenttotal" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
    </div>
    <div class="col-sm-1"><input type="checkbox" disabled></div>
</div>
<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>MATURITY RATING</strong></label>
</div>

  <table border="1" cellspacing="0" cellpadding="8" style="border-collapse: collapse;">
    <tr style="background-color: #bcd2ee; text-align: center;">
      <th>SCORE</th>
      <th>WEEKS</th>
    </tr>
    <tr><td>-10</td><td>20</td></tr>
    <tr><td>-5</td><td>22</td></tr>
    <tr><td>0</td><td>24</td></tr>
    <tr><td>5</td><td>26</td></tr>
    <tr><td>10</td><td>28</td></tr>
    <tr><td>15</td><td>30</td></tr>
    <tr><td>20</td><td>32</td></tr>
    <tr><td>25</td><td>34</td></tr>
    <tr><td>30</td><td>36</td></tr>
    <tr><td>35</td><td>38</td></tr>
    <tr><td>40</td><td>40</td></tr>
    <tr><td>45</td><td>42</td></tr>
    <tr><td>50</td><td>44</td></tr>
  </table> <hr>
<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>GESTATIONAL AGE (weeks)</strong></label>
</div>
  <div class="row mb-3">
    <label for="dates" class="col-sm-2 col-form-label"><strong>By dates</strong></label>
    <div class="col-sm-9">
        <input type="date" class="form-control" name="dates" id="dates">
        <textarea class="form-control mt-2" id="commentdates" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
    </div>
    <div class="col-sm-1"><input type="checkbox" disabled></div>
</div>
<div class="row mb-3">
    <label for="ultrasound" class="col-sm-2 col-form-label"><strong>By ultrasound</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="ultrasound" id="ultrasound">
        <textarea class="form-control mt-2" id="commentultrasound" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
    </div>
    <div class="col-sm-1"><input type="checkbox" disabled></div>
</div>
<div class="row mb-3">
    <label for="exam" class="col-sm-2 col-form-label"><strong>By exam</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="exam" id="exam">
        <textarea class="form-control mt-2" id="commentexam" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
    </div>
    <div class="col-sm-1"><input type="checkbox" disabled></div>

<!-- Obat -->
<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>Obat-obatan</strong></label>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Vitamin K</strong></label>
    <div class="col-sm-9">
        <input type="text" name="vitamin_k" class="form-control">
        <textarea class="form-control mt-2" id="commentpustaka" rows="2"
            placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1 d-flex align-items-start">
        <div class="form-check">
            <input class="form-check-input" type="checkbox"
                onchange="document.getElementById('commentpustaka').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Salep Mata</strong></label>
    <div class="col-sm-9">
        <input type="text" name="salep_mata" class="form-control">
        <textarea class="form-control mt-2" id="commentpustaka" rows="2"
            placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1 d-flex align-items-start">
        <div class="form-check">
            <input class="form-check-input" type="checkbox"
                onchange="document.getElementById('commentpustaka').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</div>

<!-- O2 -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Pemberian O2</strong></label>
    <div class="col-sm-9">
        <input type="text" name="o2" class="form-control">
        <textarea class="form-control mt-2" id="commentpustaka" rows="2"
            placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1 d-flex align-items-start">
        <div class="form-check">
            <input class="form-check-input" type="checkbox"
                onchange="document.getElementById('commentpustaka').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</div>

<!-- Pernapasan -->
<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>Pernapasan</strong></label>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Spontan Tidak</strong></label>
    <div class="col-sm-9">
        <input type="text" name="nafas_spontan" class="form-control">
       <textarea class="form-control mt-2" id="commentpustaka" rows="2"
            placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1 d-flex align-items-start">
        <div class="form-check">
            <input class="form-check-input" type="checkbox"
                onchange="document.getElementById('commentpustaka').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Frekuensi</strong></label>
    <div class="col-sm-9">
        <input type="text" name="frekuensi_nafas" class="form-control">
      <textarea class="form-control mt-2" id="commentpustaka" rows="2"
            placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1 d-flex align-items-start">
        <div class="form-check">
            <input class="form-check-input" type="checkbox"
                onchange="document.getElementById('commentpustaka').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</div>
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Teratur Tidak</strong></label>
    <div class="col-sm-9">
        <input type="text" name="nafas_teratur" class="form-control">
        <textarea class="form-control mt-2" id="commentpustaka" rows="2"
            placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1 d-flex align-items-start">
        <div class="form-check">
            <input class="form-check-input" type="checkbox"
                onchange="document.getElementById('commentpustaka').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</div>
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Suara Nafas</strong></label>
    <div class="col-sm-9">
        <input type="text" name="suara_nafas" class="form-control">
      <textarea class="form-control mt-2" id="commentpustaka" rows="2"
            placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1 d-flex align-items-start">
        <div class="form-check">
            <input class="form-check-input" type="checkbox"
                onchange="document.getElementById('commentpustaka').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</div>

<!-- Asupan Cairan -->
<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>Asupan Cairan</strong></label>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>ASI</strong></label>

    <div class="col-sm-9">
        <div class="row mb-2">
            <div class="col">
                <label>Frekuensi</label>
                <input type="text" name="asi_frekuensi" class="form-control">
            </div>
            <div class="col">
                <label>Jumlah</label>
                <input type="text" name="asi_jumlah" class="form-control">
            </div>
        </div>

         <textarea class="form-control mt-2" id="commentpustaka" rows="2"
            placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1 d-flex align-items-start">
        <div class="form-check">
            <input class="form-check-input" type="checkbox"
                onchange="document.getElementById('commentpustaka').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Susu Formula</strong></label>
    <div class="col-sm-9">
        <div class="row mb-2">
            <div class="col">
                <label>Frekuensi</label>
                <input type="text" name="asi_frekuensi" class="form-control">
            </div>
            <div class="col">
                <label>Jumlah</label>
                <input type="text" name="asi_jumlah" class="form-control">
            </div>
        </div>

         <textarea class="form-control mt-2" id="commentpustaka" rows="2"
            placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1 d-flex align-items-start">
        <div class="form-check">
            <input class="form-check-input" type="checkbox"
                onchange="document.getElementById('commentpustaka').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Infus</strong></label>
    <div class="col-sm-9">
        <div class="row mb-2">
            <div class="col">
                <label>Jenis Cairan</label>
                <input type="text" name="asi_frekuensi" class="form-control">
            </div>
            <div class="col">
                <label>Jumlah</label>
                <input type="text" name="asi_jumlah" class="form-control">
            </div>
        </div>

         <textarea class="form-control mt-2" id="commentpustaka" rows="2"
            placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1 d-flex align-items-start">
        <div class="form-check">
            <input class="form-check-input" type="checkbox"
                onchange="document.getElementById('commentpustaka').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</div>
 

<!-- Eliminasi -->
<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>Eliminasi</strong></label>
</div>

<!-- BAB -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>BAB</strong></label>

    <div class="col-sm-9">
        <div class=" mb-2">
            <div class="col">
                <label>Kapan Mekonium</label>
                <input type="text" name="mekonium" class="form-control">
            </div>
            <div class="col">
                <label>Frekuensi</label>
                <input type="text" name="bab_frekuensi" class="form-control">
            </div>
            <div class="col">
                <label>Warna</label>
                <input type="text" name="bab_warna" class="form-control">
            </div>
        </div>

        <textarea class="form-control mt-2" id="commentpustaka" rows="2"
            placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>

    <div class="col-sm-1 d-flex align-items-start">
        <div class="form-check">
            <input class="form-check-input" type="checkbox"
                onchange="document.getElementById('commentpustaka').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</div>
<!-- BAK -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>BAK</strong></label>
   <div class="col-sm-9">
        <div class=" mb-2">
           
            <div class="col">
                <label>Frekuensi</label>
                <input type="text" name="bab_frekuensi" class="form-control">
            </div>
            <div class="col">
                <label>Warna</label>
                <input type="text" name="bab_warna" class="form-control">
            </div>
        </div>

        <textarea class="form-control mt-2" id="commentpustaka" rows="2"
            placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>

    <div class="col-sm-1 d-flex align-items-start">
        <div class="form-check">
            <input class="form-check-input" type="checkbox"
                onchange="document.getElementById('commentpustaka').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</div>

<!-- Istirahat -->
<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>Istirahat dan Tidur</strong></label>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Lamanya</strong></label>
    <div class="col-sm-9">
        <input type="text" name="lama_tidur" class="form-control">
       <textarea class="form-control mt-2" id="commentpustaka" rows="2"
            placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>

    <div class="col-sm-1 d-flex align-items-start">
        <div class="form-check">
            <input class="form-check-input" type="checkbox"
                onchange="document.getElementById('commentpustaka').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</div>
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Keadaan Waktu Tidur</strong></label>
    <div class="col-sm-9">
        <textarea name="keadaan_tidur" class="form-control"></textarea>
          <textarea class="form-control mt-2" id="commentpustaka" rows="2"
            placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>

    <div class="col-sm-1 d-flex align-items-start">
        <div class="form-check">
            <input class="form-check-input" type="checkbox"
                onchange="document.getElementById('commentpustaka').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</div>
<!-- Antropometri -->
<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>Pengukuran Antropometri</strong></label>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Penimbangan berat badan</strong></label>
    <div class="col-sm-9">
        <input type="text" name="bb" class="form-control">
        <textarea class="form-control mt-2" id="commentpustaka" rows="2"
            placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>

    <div class="col-sm-1 d-flex align-items-start">
        <div class="form-check">
            <input class="form-check-input" type="checkbox"
                onchange="document.getElementById('commentpustaka').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Pengukuran panjang badan</strong></label>
    <div class="col-sm-9">
        <input type="text" name="pb" class="form-control">
          <textarea class="form-control mt-2" id="commentpustaka" rows="2"
            placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>

    <div class="col-sm-1 d-flex align-items-start">
        <div class="form-check">
            <input class="form-check-input" type="checkbox"
                onchange="document.getElementById('commentpustaka').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Ukur  Lingkar Kepala</strong></label>
    <div class="col-sm-9">
        <input type="text" name="lk" class="form-control">
            <textarea class="form-control mt-2" id="commentpustaka" rows="2"
            placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>

    <div class="col-sm-1 d-flex align-items-start">
        <div class="form-check">
            <input class="form-check-input" type="checkbox"
                onchange="document.getElementById('commentpustaka').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Ukur Lingkar Dada</strong></label>
    <div class="col-sm-9">
        <input type="text" name="ld" class="form-control">
             <textarea class="form-control mt-2" id="commentpustaka" rows="2"
            placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>

    <div class="col-sm-1 d-flex align-items-start">
        <div class="form-check">
            <input class="form-check-input" type="checkbox"
                onchange="document.getElementById('commentpustaka').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Ukuran Lingkar Perut</strong></label>
    <div class="col-sm-9">
        <input type="text" name="lp" class="form-control">
             <textarea class="form-control mt-2" id="commentpustaka" rows="2"
            placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>

    <div class="col-sm-1 d-flex align-items-start">
        <div class="form-check">
            <input class="form-check-input" type="checkbox"
                onchange="document.getElementById('commentpustaka').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Ukuran lingkar lengan atas</strong></label>
    <div class="col-sm-9">
        <input type="text" name="lila" class="form-control">
          <textarea class="form-control mt-2" id="commentpustaka" rows="2"
            placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>

    <div class="col-sm-1 d-flex align-items-start">
        <div class="form-check">
            <input class="form-check-input" type="checkbox"
                onchange="document.getElementById('commentpustaka').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</div>
</div>
</div>
</div>
   <div class="card">
            <div class="card-body">
              <h5 class="card-title mb-1"><strong>Pemeriksaan Fisik</strong></h5>
<!-- Keadaan Umum -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label text-primary"><strong>Keadaan Umum</strong></label>
    <div class="col-sm-9">
        <textarea name="keadaan_umum" class="form-control"></textarea>
        <textarea class="form-control mt-2" rows="2" readonly></textarea>
    </div>
    <div class="col-sm-1">
        <input type="checkbox" disabled>
    </div>
</div>

<!-- Tanda Vital -->
<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>Tanda Tanda Vital</strong></label>
</div>


<!-- Tekanan Darah -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Tekanan Darah</strong></label>
    <div class="col-sm-9">
        <div class="input-group">
            <input type="text" class="form-control" name="tekanan_darah">
            <span class="input-group-text">mmHg</span>
        </div>
        <textarea id="comment_tekanan_darah" class="form-control mt-2" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1 d-flex align-items-start">
        <input type="checkbox" class="form-check-input"
        onchange="document.getElementById('comment_tekanan_darah').style.display = this.checked ? 'none' : 'block'">
    </div>
</div>

<!-- Denyut Nadi -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Denyut Nadi</strong></label>
    <div class="col-sm-9">
        <div class="input-group">
            <input type="text" class="form-control" name="nadi">
            <span class="input-group-text">x/menit</span>
        </div>
        <textarea id="comment_nadi" class="form-control mt-2" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1 d-flex align-items-start">
        <input type="checkbox" class="form-check-input"
        onchange="document.getElementById('comment_nadi').style.display = this.checked ? 'none' : 'block'">
    </div>
</div>

<!-- Suhu -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Suhu</strong></label>
    <div class="col-sm-9">
        <div class="input-group">
            <input type="text" class="form-control" name="suhu">
            <span class="input-group-text">°C</span>
        </div>
        <textarea id="comment_suhu" class="form-control mt-2" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1 d-flex align-items-start">
        <input type="checkbox" class="form-check-input"
        onchange="document.getElementById('comment_suhu').style.display = this.checked ? 'none' : 'block'">
    </div>
</div>

<!-- Pernapasan -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Pernapasan</strong></label>
    <div class="col-sm-9">
        <div class="input-group">
            <input type="text" class="form-control" name="pernapasan">
            <span class="input-group-text">x/menit</span>
        </div>
        <textarea id="comment_pernapasan" class="form-control mt-2" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1 d-flex align-items-start">
        <input type="checkbox" class="form-check-input"
        onchange="document.getElementById('comment_pernapasan').style.display = this.checked ? 'none' : 'block'">
    </div>
</div>

<!-- Kepala -->
<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>Kepala</strong></label>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Keadaan kepala</strong></label>

    <div class="col-sm-9">
        <div class="d-flex align-items-center gap-3 flex-wrap">

            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="keadaan_kepala" value="normal">
                <label class="form-check-label"><strong>Normal</strong></label>
            </div>

            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="keadaan_kepala" value="tidak_normal">
                <label class="form-check-label"><strong>Tidak </strong></label>
            </div>

        </div>

        <textarea id="comment_kepala" class="form-control mt-2" rows="2"
        placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!"
        readonly></textarea>
    </div>

    <div class="col-sm-1 d-flex align-items-start">
        <input type="checkbox" class="form-check-input"
        onchange="document.getElementById('comment_kepala').style.display = this.checked ? 'none' : 'block'">
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Fontenel</strong></label>
    <div class="col-sm-9">
        <input type="text" name="fontenel" class="form-control">
        <textarea class="form-control mt-2" rows="2" readonly></textarea>
    </div>
    <div class="col-sm-1"><input type="checkbox" disabled></div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Adanya trauma kelahiran pada kepala</strong></label>
    
    <div class="col-sm-9">
        <div class="d-flex align-items-center gap-3 flex-wrap">

            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="keadaan_kepala" value="normal">
                <label class="form-check-label"><strong>Ya</strong></label>
            </div>

            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="keadaan_kepala" value="tidak_normal">
                <label class="form-check-label"><strong>Tidak </strong></label>
            </div>

        </div>

        <textarea id="comment_kepala" class="form-control mt-2" rows="2"
        placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!"
        readonly></textarea>
    </div>

    <div class="col-sm-1 d-flex align-items-start">
        <input type="checkbox" class="form-check-input"
        onchange="document.getElementById('comment_kepala').style.display = this.checked ? 'none' : 'block'">
    </div>
</div>

<!-- Wajah -->
<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>Wajah</strong></label>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Wajah Simetris</strong></label>
     <div class="col-sm-9">
        <div class="d-flex align-items-center gap-3 flex-wrap">

            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="keadaan_kepala" value="normal">
                <label class="form-check-label"><strong>Ya</strong></label>
            </div>

            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="keadaan_kepala" value="tidak_normal">
                <label class="form-check-label"><strong>Tidak </strong></label>
            </div>

        </div>

        <textarea id="comment_kepala" class="form-control mt-2" rows="2"
        placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!"
        readonly></textarea>
    </div>

    <div class="col-sm-1 d-flex align-items-start">
        <input type="checkbox" class="form-check-input"
        onchange="document.getElementById('comment_kepala').style.display = this.checked ? 'none' : 'block'">
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Kelainan wajah akibat trauma lahir seperti Laserasi </strong></label>
    <div class="col-sm-9">
        <input type="text" name="laserasi" class="form-control">
        <textarea class="form-control mt-2" rows="2" readonly></textarea>
    </div>
    <div class="col-sm-1"><input type="checkbox" disabled></div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Paresis N Fasialis</strong></label>
    <div class="col-sm-9">
        <input type="text" name="paresis" class="form-control">
        <textarea class="form-control mt-2" rows="2" readonly></textarea>
    </div>
    <div class="col-sm-1"><input type="checkbox" disabled></div>
</div>
<!-- Mata -->
<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>Mata</strong></label>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Kelainan wajah akibat trauma lahir seperti Laserasi </strong></label>
   <div class="col-sm-9">
        <div class="d-flex align-items-center gap-3 flex-wrap">

            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="keadaan_kepala" value="normal">
                <label class="form-check-label"><strong>Ya</strong></label>
            </div>

            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="keadaan_kepala" value="tidak_normal">
                <label class="form-check-label"><strong>Tidak </strong></label>
            </div>

        </div>

        <textarea id="comment_kepala" class="form-control mt-2" rows="2"
        placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!"
        readonly></textarea>
    </div>

    <div class="col-sm-1 d-flex align-items-start">
        <input type="checkbox" class="form-check-input"
        onchange="document.getElementById('comment_kepala').style.display = this.checked ? 'none' : 'block'">
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Jumlah Mata</strong></label>

    <div class="col-sm-9">
        <div class="row g-2">

            <div class="col-md-4 d-flex align-items-center">
                <small class="me-2"></small>
                <input type="text" name="mata_jumlah" class="form-control">
            </div>

            <div class="col-md-4 d-flex align-items-center">
                <small class="me-2">Posisi</small>
                <input type="text" name="mata_posisi" class="form-control">
            </div>

            <div class="col-md-4 d-flex align-items-center">
                <small class="me-2">Letak</small>
                <input type="text" name="mata_letak" class="form-control">
            </div>

        </div>

        <textarea id="comment_mata" class="form-control mt-2" rows="2"
        placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>

    <div class="col-sm-1 d-flex align-items-start">
        <input type="checkbox" class="form-check-input"
        onchange="document.getElementById('comment_mata').style.display = this.checked ? 'none' : 'block'">
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Strabismus</strong></label>
    <div class="col-sm-9">
        <div class="d-flex align-items-center gap-3 flex-wrap">

            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="keadaan_kepala" value="Ya">
                <label class="form-check-label"><strong>Ya</strong></label>
            </div>

            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="keadaan_kepala" value="tidak_normal">
                <label class="form-check-label"><strong>Tidak </strong></label>
            </div>

        </div>

        <textarea id="comment_kepala" class="form-control mt-2" rows="2"
        placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!"
        readonly></textarea>
    </div>

    <div class="col-sm-1 d-flex align-items-start">
        <input type="checkbox" class="form-check-input"
        onchange="document.getElementById('comment_kepala').style.display = this.checked ? 'none' : 'block'">
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Adanya Katarak kongenital</strong></label>
  <div class="col-sm-9">
        <div class="d-flex align-items-center gap-3 flex-wrap">

            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="keadaan_kepala" value="Ya">
                <label class="form-check-label"><strong>Ya</strong></label>
            </div>

            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="keadaan_kepala" value="tidak_normal">
                <label class="form-check-label"><strong>Tidak </strong></label>
            </div>

        </div>

        <textarea id="comment_kepala" class="form-control mt-2" rows="2"
        placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!"
        readonly></textarea>
    </div>

    <div class="col-sm-1 d-flex align-items-start">
        <input type="checkbox" class="form-check-input"
        onchange="document.getElementById('comment_kepala').style.display = this.checked ? 'none' : 'block'">
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Adanya trauma pada palpebral</strong></label>
    <div class="col-sm-9">
        <div class="d-flex align-items-center gap-3 flex-wrap">

            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="keadaan_kepala" value="Ya">
                <label class="form-check-label"><strong>Ya</strong></label>
            </div>

            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="keadaan_kepala" value="tidak_normal">
                <label class="form-check-label"><strong>Tidak </strong></label>
            </div>

        </div>

        <textarea id="comment_kepala" class="form-control mt-2" rows="2"
        placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!"
        readonly></textarea>
    </div>

    <div class="col-sm-1 d-flex align-items-start">
        <input type="checkbox" class="form-check-input"
        onchange="document.getElementById('comment_kepala').style.display = this.checked ? 'none' : 'block'">
    </div>
</div>

<!-- Sclera -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Sclera</strong></label>
    <div class="col-sm-9">
        <input type="radio" name="sclera" value="icterus"> Icterus
        <input type="radio" name="sclera" value="tidak"> Tidak
        <textarea class="form-control mt-2" rows="2"  placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
    </div>
    <div class="col-sm-1 text-center"><input type="checkbox" class="form-check-input mt-2"></div>
</div>

<!-- Conjungtiva -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Conjungtiva</strong></label>
    <div class="col-sm-9">
        <div class="mb-2">
           
            <input type="radio" name="radang_conjungtiva" value=" radang">  Radang
            <input type="radio" name="radang_conjungtiva" value="tidak"> Tidak
        </div>
        <div class="mb-2">
          
            <input type="radio" name="anemis" value="anemis"> Anemis
            <input type="radio" name="anemis" value="tidak"> Tidak
        </div>
        <textarea class="form-control" rows="2"  placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
    </div>
    <div class="col-sm-1 text-center"><input type="checkbox" class="form-check-input mt-2"></div>
</div>

<!-- Pupil -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Pupil</strong></label>
    <div class="col-sm-9">
        <div class="mb-2">
            <input type="radio" name="pupil_bentuk" value="isokor"> Isokor
            <input type="radio" name="pupil_bentuk" value="anisokor"> Anisokor
        </div>
        <div class="mb-2">
            <input type="radio" name="pupil_ukuran" value="myosis"> Myosis
            <input type="radio" name="pupil_ukuran" value="midriasis"> Midriasis
        </div>
        <textarea class="form-control" rows="2"  placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
    </div>
    <div class="col-sm-1 text-center"><input type="checkbox" class="form-check-input mt-2"></div>
</div>



<!-- Posisi Mata -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Refleks pupil terhadap cahaya</strong></label>
    <div class="col-sm-9">
        <input type="radio" name="posisi_mata" value="simetris"> Simetris
        <input type="radio" name="posisi_mata" value="tidak"> Tidak
        <input type="text" class="form-control mt-2" placeholder="">
        <textarea class="form-control mt-2" rows="2"  placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea></textarea>
    </div>
    <div class="col-sm-1 text-center"><input type="checkbox" class="form-check-input mt-2"></div>
</div>

<!-- Gerakan Bola Mata -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Gerakan Bola Mata</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="gerakan_mata">
        <textarea class="form-control" rows="2"   placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea></textarea>
    </div>
    <div class="col-sm-1 text-center"><input type="checkbox" class="form-check-input mt-2"></div>
</div>

<!-- Penutupan Kelopak -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Penutupan Kelopak mata</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="kelopak">
        <textarea class="form-control" rows="2"   placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea></textarea>
    </div>
    <div class="col-sm-1 text-center"><input type="checkbox" class="form-check-input mt-2"></div>
</div>

<!-- Bulu Mata -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Keadaan Bulu Mata</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="bulu_mata">
        <textarea class="form-control" rows="2"   placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea></textarea>
    </div>
    <div class="col-sm-1 text-center"><input type="checkbox" class="form-check-input mt-2"></div>
</div>


<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Data Lain</strong></label>
    <div class="col-sm-9">
        <textarea name="mata_lain" class="form-control"></textarea>
        <textarea class="form-control mt-2" rows="2" readonly></textarea>
    </div>
    <div class="col-sm-1"><input type="checkbox" disabled></div>
</div>

<!-- Hidung -->
<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>Hidung dan Sinus</strong></label>
</div>
<div class="row mb-2">
    <label class="col-sm-12 "><strong>Inspeksi</strong></label>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Bentuk hidung</strong></label>
    <div class="col-sm-9">
        <input type="text" name="hidung_bentuk" class="form-control">
        <textarea class="form-control mt-2" readonly></textarea>
    </div>
    <div class="col-sm-1"><input type="checkbox" disabled></div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Pernapasan cuping hidung</strong></label>
    <div class="col-sm-9">
        <input type="text" name="cuping" class="form-control">
        <textarea class="form-control mt-2" readonly></textarea>
    </div>
    <div class="col-sm-1"><input type="checkbox" disabled></div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Keadaan septum</strong></label>
    <div class="col-sm-9">
        <input type="text" name="septum" class="form-control">
        <textarea class="form-control mt-2" readonly></textarea>
    </div>
    <div class="col-sm-1"><input type="checkbox" disabled></div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Secret / cairan</strong></label>
    <div class="col-sm-9">
        <input type="text" name="secret" class="form-control">
        <textarea class="form-control mt-2" readonly></textarea>
    </div>
    <div class="col-sm-1"><input type="checkbox" disabled></div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Data Lain</strong></label>
    <div class="col-sm-9">
        <textarea name="hidung_lain" class="form-control"></textarea>
        <textarea class="form-control mt-2" readonly></textarea>
    </div>
    <div class="col-sm-1"><input type="checkbox" disabled></div>
</div>

<!-- Telinga -->
<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>Telinga</strong></label>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Bentuk telinga</strong></label>
    <div class="col-sm-9">
        <input type="text" name="telinga_bentuk" class="form-control">
        <textarea class="form-control mt-2" readonly></textarea>
    </div>
    <div class="col-sm-1"><input type="checkbox" disabled></div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Letak telinga terhadap mata</strong></label>
    <div class="col-sm-9">
        <input type="text" name="letak_telinga" class="form-control">
        <textarea class="form-control mt-2" readonly></textarea>
    </div>
    <div class="col-sm-1"><input type="checkbox" disabled></div>
</div>

<!-- Lubang Telinga -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Lubang Telinga</strong></label>
    <div class="col-sm-9">
        <div class="mb-2">
            <input type="radio" name="telinga" value="bersih"> Bersih
            <input type="radio" name="telinga" value="serumen"> Serumen
            <input type="radio" name="telinga" value="nanah"> Nanah
        </div>
        <textarea class="form-control" rows="2"   placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea></textarea>
    </div>
    <div class="col-sm-1 text-center">
        <input type="checkbox" class="form-check-input mt-2">
    </div>
</div>

<div class="row mb-2">
    <label class="col-sm-12"><strong>Palpasi</strong></label>
</div>

<!-- Nyeri Tekan -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Nyeri Tekan</strong></label>
    <div class="col-sm-9">
        <input type="radio" name="nyeri_telinga" value="ya"> Ya
        <input type="radio" name="nyeri_telinga" value="tidak"> Tidak
        <textarea class="form-control mt-2" rows="2"  placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea></textarea>
    </div>
    <div class="col-sm-1 text-center">
        <input type="checkbox" class="form-check-input mt-2">
    </div>
</div>
<!-- Mulut -->
<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>Mulut</strong></label>
</div>
<div class="row mb-2">
    <label class="col-sm-12 "><strong>Inspeksi</strong></label>
</div>
<div class="row mb-3">
    <label class="col-sm-2 col-form-label">
        <strong>Gusi</strong>
    </label>

    <div class="col-sm-9 d-flex align-items-center gap-3 flex-wrap">

        <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="gusi" value="merah">
            <label class="form-check-label">Merah</label>
        </div>

        <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="gusi" value="radang">
            <label class="form-check-label">Radang</label>
        </div>

        <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="gusi" value="tidak">
            <label class="form-check-label">Tidak</label>
        </div>

        :
        <input type="text" class="form-control" style="max-width:200px;">

        <textarea class="form-control mt-2" rows="2"   placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea></textarea>
    </div>

    <div class="col-sm-1 text-center">
        <input type="checkbox" class="form-check-input mt-2">
    </div>
</div>

<!-- Lidah -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Lidah</strong></label>
    <div class="col-sm-9">
        <input type="radio" name="lidah" value="kotor"> Kotor
        <input type="radio" name="lidah" value="tidak"> Tidak
        <textarea class="form-control mt-2" rows="2"   placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea></textarea>
    </div>
    <div class="col-sm-1 text-center">
        <input type="checkbox" class="form-check-input mt-2">
    </div>
</div>

<!-- Bibir - Warna -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label">
        <strong>Bibir </strong>
    </label>

    <div class="col-sm-9 d-flex align-items-center gap-3 flex-wrap">

        <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="bibir_warna" value="cianosis">
            <label class="form-check-label"><strong>Gianosis</strong></label>
        </div>


        <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="bibir_warna" value="pucat">
            <label class="form-check-label"><strong>Pucat</strong></label>
        </div>


        <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="bibir_warna" value="tidak">
            <label class="form-check-label"><strong>Tidak</strong></label>
        </div>

        <strong>:</strong>
        <input type="text" class="form-control" style="max-width:200px;">

        <textarea class="form-control mt-2" rows="2"   placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea></textarea>
    </div>

    <div class="col-sm-1 text-center">
        <input type="checkbox" class="form-check-input mt-2">
    </div>
</div>

<!-- Bibir - Kondisi -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label">
        <strong> Bibir </strong>
    </label>

    <div class="col-sm-9 d-flex align-items-center gap-3 flex-wrap">

        <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="bibir_kondisi" value="basah">
            <label class="form-check-label"><strong>Basah</strong></label>
        </div>


        <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="bibir_kondisi" value="kering">
            <label class="form-check-label"><strong>Kering</strong></label>
        </div>


        <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="bibir_kondisi" value="pecah">
            <label class="form-check-label"><strong>Pecah</strong></label>
        </div>

        <strong>:</strong>
        <input type="text" class="form-control" style="max-width:200px;">

        <textarea class="form-control mt-2" rows="2"   placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea></textarea>
    </div>

    <div class="col-sm-1 text-center">
        <input type="checkbox" class="form-check-input mt-2">
    </div>
</div>

<!-- Bau Mulut -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label">
        <strong>Mulut berbau</strong>
    </label>

    <div class="col-sm-9 d-flex align-items-center gap-3 flex-wrap">

        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="bau_mulut" value="berbau">
            <label class="form-check-label"><strong>berbau</strong></label>
        </div>


        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="bau_mulut" value="tidak">
            <label class="form-check-label"><strong>Tidak</strong></label>
        </div>

        <strong>:</strong>
        <input type="text" class="form-control" style="max-width:200px;">

        <textarea class="form-control mt-2" rows="2"   placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea></textarea>
    </div>

    <div class="col-sm-1 text-center">
        <input type="checkbox" class="form-check-input mt-2">
    </div>
</div>
<!-- Simetris -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Keadaan bibir simetris</strong></label>
    <div class="col-sm-9 d-flex align-items-center gap-3 flex-wrap">

        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="bau_mulut" value="ya">
            <label class="form-check-label"><strong>Ya</strong></label>
        </div>


        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="bau_mulut" value="tidak">
            <label class="form-check-label"><strong>Tidak</strong></label>
        </div>



        <textarea class="form-control mt-2" rows="2"   placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea></textarea>
    </div>
    </div>

<!-- Labio Skizis -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Adanya  Labio Skizis</strong></label>
   <div class="col-sm-9 d-flex align-items-center gap-3 flex-wrap">

        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="bau_mulut" value="ya">
            <label class="form-check-label"><strong>Ya</strong></label>
        </div>


        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="bau_mulut" value="tidak">
            <label class="form-check-label"><strong>Tidak</strong></label>
        </div>



        <textarea class="form-control mt-2" rows="2"   placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea></textarea>
    </div>
    </div>
<!-- Palato Skizis -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Abiopalato  Skizis</strong></label>
   <div class="col-sm-9 d-flex align-items-center gap-3 flex-wrap">

        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="bau_mulut" value="ya">
            <label class="form-check-label"><strong>Ya</strong></label>
        </div>


        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="bau_mulut" value="tidak">
            <label class="form-check-label"><strong>Tidak</strong></label>
        </div>



        <textarea class="form-control mt-2" rows="2"   placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea></textarea>
    </div>
    </div>

<!-- Bercak Putih -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Bercak putih pada lidah dan palatum</strong></label>
    <div class="col-sm-9 d-flex align-items-center gap-3 flex-wrap">

        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="bau_mulut" value="ya">
            <label class="form-check-label"><strong>Ya</strong></label>
        </div>


        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="bau_mulut" value="tidak">
            <label class="form-check-label"><strong>Tidak</strong></label>
        </div>



        <textarea class="form-control mt-2" rows="2"   placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea></textarea>
    </div>
<!-- Tenggorokan -->
<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>Tenggorokan</strong></label>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Warna Mukosa</strong></label>
    <div class="col-sm-9">
        <input type="text" name="warna_mukosa" class="form-control">
        <textarea class="form-control mt-2" rows="2" readonly></textarea>
    </div>
    <div class="col-sm-1"><input type="checkbox" disabled></div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Ada Sumbatan</strong></label>
    <div class="col-sm-9">
        <input type="text" name="sumbatan" class="form-control">
        <textarea class="form-control mt-2" rows="2" readonly></textarea>
    </div>
    <div class="col-sm-1"><input type="checkbox" disabled></div>
</div>
<!-- Leher -->
<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>Leher</strong></label>
</div>
<div class="row mb-2">
    <label class="col-sm-12 "><strong>Palpasi</strong></label>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Kelenjar Limfe</strong></label>
    <div class="col-sm-9">
        <input type="radio" name="limfe" value="Membesar"> Membesar
        <input type="radio" name="limfe" value="Tidak"> Tidak

        <textarea class="form-control mt-2" rows="2" readonly></textarea>
    </div>
    <div class="col-sm-1"><input type="checkbox" disabled></div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Simetris</strong></label>
    <div class="col-sm-9">
        <input type="radio" name="leher_simetris" value="Ya"> Ya
        <input type="radio" name="leher_simetris" value="Tidak"> Tidak

        <textarea class="form-control mt-2" rows="2" readonly></textarea>
    </div>
    <div class="col-sm-1"><input type="checkbox" disabled></div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Ada Pembengkakan</strong></label>
    <div class="col-sm-9">
        <input type="radio" name="pembengkakan" value="Ya"> Ya
        <input type="radio" name="pembengkakan" value="Tidak"> Tidak

        <textarea class="form-control mt-2" rows="2" readonly></textarea>
    </div>
    <div class="col-sm-1"><input type="checkbox" disabled></div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Adanya lipatan kulit yang berlebihan di bagian belakang leher </strong></label>
    <div class="col-sm-9">
        <input type="radio" name="lipatan" value="Ya"> Ya
        <input type="radio" name="lipatan" value="Tidak"> Tidak

        <textarea class="form-control mt-2" rows="2" readonly></textarea>
    </div>
    <div class="col-sm-1"><input type="checkbox" disabled></div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Data Lain</strong></label>
    <div class="col-sm-9">
        <input type="text" name="leher_lain" class="form-control">
        <textarea class="form-control mt-2" rows="2" readonly></textarea>
    </div>
    <div class="col-sm-1"><input type="checkbox" disabled></div>
</div>
<!-- Thorax -->
<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>Thorax dan Pernapasan</strong></label>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Periksa kesimetrisan gerakan dada saat bernapas: </strong></label>
    <div class="col-sm-9">
        <input type="radio" name="dada_simetris" value="Ya"> Ya
        <input type="radio" name="dada_simetris" value="Tidak"> Tidak

        <textarea class="form-control mt-2" rows="2" readonly></textarea>
    </div>
    <div class="col-sm-1"><input type="checkbox" disabled></div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Puting susu tampak membesar	</strong></label>
    <div class="col-sm-9">
        <input type="radio" name="puting" value="Ya"> Ya
        <input type="radio" name="puting" value="Tidak"> Tidak

        <textarea class="form-control mt-2" rows="2" readonly></textarea>
    </div>
    <div class="col-sm-1"><input type="checkbox" disabled></div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Simetris</strong></label>
    <div class="col-sm-9">
        <input type="radio" name="thorax_simetris" value="Ya"> Ya
        <input type="radio" name="thorax_simetris" value="Tidak"> Tidak

        <textarea class="form-control mt-2" rows="2" readonly></textarea>
    </div>
    <div class="col-sm-1"><input type="checkbox" disabled></div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Fraktur Klavikula</strong></label>
    <div class="col-sm-9">
        <input type="radio" name="fraktur" value="Ya"> Ya
        <input type="radio" name="fraktur" value="Tidak"> Tidak

        <textarea class="form-control mt-2" rows="2" readonly></textarea>
    </div>
    <div class="col-sm-1"><input type="checkbox" disabled></div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Bentuk Dada</strong></label>
    <div class="col-sm-9">
        <input type="text" name="bentuk_dada" class="form-control">
        <textarea class="form-control mt-2" rows="2" readonly></textarea>
    </div>
    <div class="col-sm-1"><input type="checkbox" disabled></div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Irama Pernapasan</strong></label>
    <div class="col-sm-9">
        <input type="text" name="irama" class="form-control">
        <textarea class="form-control mt-2" rows="2" readonly></textarea>
    </div>
    <div class="col-sm-1"><input type="checkbox" disabled></div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Pengembangan di waktu bernapas</strong></label>
    <div class="col-sm-9">
        <input type="text" name="pengembangan" class="form-control">
        <textarea class="form-control mt-2" rows="2" readonly></textarea>
    </div>
    <div class="col-sm-1"><input type="checkbox" disabled></div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Tipe Pernapasan</strong></label>
    <div class="col-sm-9">
        <input type="text" name="tipe" class="form-control">
        <textarea class="form-control mt-2" rows="2" readonly></textarea>
    </div>
    <div class="col-sm-1"><input type="checkbox" disabled></div>
</div>
<div class="row mb-3">
    <label class="col-sm-2 col-form-label "><strong>Palapasi taktil fremitus</strong></label>
    <div class="col-sm-9">
        <input type="text" name="tipe" class="form-control">
        <textarea class="form-control mt-2" rows="2" readonly></textarea>
    </div>
    <div class="col-sm-1"><input type="checkbox" disabled></div>
</div>
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Auskultasi</strong></label>

<!-- Auskultasi -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Suara Nafas</strong></label>
    <div class="col-sm-9">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="suara[]" value="Vesikuler">
            <label class="form-check-label">Vesikuler</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="suara[]" value="Bronchial">
            <label class="form-check-label">Bronchial</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="suara[]" value="Bronchovesikuler">
            <label class="form-check-label">Bronchovesikuler</label>
        </div>

        <textarea class="form-control mt-2" rows="2" readonly></textarea>
    </div>
    <div class="col-sm-1"><input type="checkbox" disabled></div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Suara Tambahan</strong></label>
    <div class="col-sm-9">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="tambahan[]" value="Ronchi">
            <label class="form-check-label">Ronchi</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="tambahan[]" value="Wheezing">
            <label class="form-check-label">Wheezing</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="tambahan[]" value="Rales">
            <label class="form-check-label">Rales</label>
        </div>

        <textarea class="form-control mt-2" rows="2" readonly></textarea>
    </div>
    <div class="col-sm-1"><input type="checkbox" disabled></div>
</div>

<!-- Perkusi -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Perkusi</strong></label>
    <div class="col-sm-9">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="perkusi[]" value="Redup">
            <label class="form-check-label">Redup</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="perkusi[]" value="Pekak">
            <label class="form-check-label">Pekak</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="perkusi[]" value="Hypersonor">
            <label class="form-check-label">Hypersonor</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="perkusi[]" value="Tympani">
            <label class="form-check-label">Tympani</label>
        </div>

        <textarea class="form-control mt-2" rows="2" readonly></textarea>
    </div>
    <div class="col-sm-1"><input type="checkbox" disabled></div>
</div>
<!-- Abdomen -->
<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>Abdomen</strong></label>
</div>

<!-- Inspeksi -->
<div class="row mb-2">
    <label class="col-sm-12"><strong>Inspeksi</strong></label>
</div>



<!-- Keadaan Tali Pusat -->
<div class="row mb-2">
    <label class="col-sm-12"><strong>Keadaan Tali Pusar</strong></label>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Bersih/Terawat</strong></label>
    <div class="col-sm-9">
        Ya <input type="checkbox" name="bersih_ya">
        Tidak <input type="checkbox" name="bersih_tidak">
        <textarea class="form-control mt-2" rows="2" readonly></textarea>
    </div>
    <div class="col-sm-1"><input type="checkbox" disabled></div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Tali Pusat</strong></label>
    <div class="col-sm-9">
        Layu <input type="checkbox" name="tali_layu">
        Segar <input type="checkbox" name="tali_segar">
        <textarea class="form-control mt-2" rows="2" readonly></textarea>
    </div>
    <div class="col-sm-1"><input type="checkbox" disabled></div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Tidak Berbau</strong></label>
    <div class="col-sm-9">
        Ya <input type="checkbox" name="tidak_berbau_ya">
        Tidak <input type="checkbox" name="tidak_berbau_tidak">
        <textarea class="form-control mt-2" rows="2" readonly></textarea>
    </div>
    <div class="col-sm-1"><input type="checkbox" disabled></div>
</div>

<!-- Input biasa -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Pendarahan tali pusat</strong></label>
    <div class="col-sm-9">
        <input type="text" name="pendarahan" class="form-control">
        <textarea class="form-control mt-2" rows="2" readonly></textarea>
    </div>
    <div class="col-sm-1"><input type="checkbox" disabled></div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Penonjolan Umbilicus</strong></label>
    <div class="col-sm-9">
        <input type="text" name="umbilicus" class="form-control">
        <textarea class="form-control mt-2" rows="2" readonly></textarea>
    </div>
    <div class="col-sm-1"><input type="checkbox" disabled></div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Tanda-tanda Infeksi</strong></label>
    <div class="col-sm-9">
        <input type="text" name="infeksi" class="form-control">
        <textarea class="form-control mt-2" rows="2" readonly></textarea>
    </div>
    <div class="col-sm-1"><input type="checkbox" disabled></div>
</div>

<!-- Bentuk Abdomen -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Abdomen Tampak Bulat</strong></label>
    <div class="col-sm-9">
        Ya <input type="checkbox" name="bulat_ya">
        Tidak <input type="checkbox" name="bulat_tidak">
        <textarea class="form-control mt-2" rows="2" readonly></textarea>
    </div>
    <div class="col-sm-1"><input type="checkbox" disabled></div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Atau Cekung</strong></label>
    <div class="col-sm-9">
        Ya <input type="checkbox" name="cekung_ya">
        Tidak <input type="checkbox" name="cekung_tidak">
        <textarea class="form-control mt-2" rows="2" readonly></textarea>
    </div>
    <div class="col-sm-1"><input type="checkbox" disabled></div>
</div>

<!-- Gerakan -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Abdomen bergerak secara bersamaan dengan gerakan dada saat bernafas</strong></label>
    <div class="col-sm-9">
        Ya <input type="checkbox" name="gerak_ya">
        Tidak <input type="checkbox" name="gerak_tidak">
        <textarea class="form-control mt-2" rows="2" readonly></textarea>
    </div>
    <div class="col-sm-1"><input type="checkbox" disabled></div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Kaji adanya pembengkakan </strong></label>
    <div class="col-sm-9">
        Ya <input type="checkbox" name="bengkak_ya">
        Tidak <input type="checkbox" name="bengkak_tidak">
        <textarea class="form-control mt-2" rows="2" readonly></textarea>
    </div>
    <div class="col-sm-1"><input type="checkbox" disabled></div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Periksa Warna & Keadaan kult abdomen (jaringan parut, ekimosis, distensi vena)</strong></label>
    <div class="col-sm-9">
        <input type="text" name="kulit_abdomen" class="form-control">
        <textarea class="form-control mt-2" rows="2" readonly></textarea>
    </div>
    <div class="col-sm-1"><input type="checkbox" disabled></div>
</div>

<!-- Auskultasi -->
<div class="row mb-2">
    <label class="col-sm-12"><strong>Auskultasi</strong></label>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Peristaltik</strong></label>
    <div class="col-sm-9">
        <input type="text" name="peristaltik" class="form-control">
        <textarea class="form-control mt-2" rows="2" readonly></textarea>
    </div>
    <div class="col-sm-1"><input type="checkbox" disabled></div>
</div>

<!-- Perkusi -->
<div class="row mb-2">
    <label class="col-sm-12"><strong>Perkusi</strong></label>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Tympani</strong></label>
    <div class="col-sm-9">
        <input type="text" name="tympani" class="form-control">
        <textarea class="form-control mt-2" rows="2" readonly></textarea>
    </div>
    <div class="col-sm-1"><input type="checkbox" disabled></div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Data Lain</strong></label>
    <div class="col-sm-9">
        <input type="text" name="perkusi_lain" class="form-control">
        <textarea class="form-control mt-2" rows="2" readonly></textarea>
    </div>
    <div class="col-sm-1"><input type="checkbox" disabled></div>
</div>

<!-- Palpasi -->
<div class="row mb-2">
    <label class="col-sm-12"><strong>Palpasi</strong></label>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Nyeri</strong></label>
    <div class="col-sm-9">
        <input type="text" name="nyeri" class="form-control">
        <textarea class="form-control mt-2" rows="2" readonly></textarea>
    </div>
    <div class="col-sm-1"><input type="checkbox" disabled></div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Hati</strong></label>
    <div class="col-sm-9">
        <input type="text" name="hati" class="form-control">
        <textarea class="form-control mt-2" rows="2" readonly></textarea>
    </div>
    <div class="col-sm-1"><input type="checkbox" disabled></div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Ginjal</strong></label>
    <div class="col-sm-9">
        <input type="text" name="ginjal" class="form-control">
        <textarea class="form-control mt-2" rows="2" readonly></textarea>
    </div>
    <div class="col-sm-1"><input type="checkbox" disabled></div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Kolon Sigmoid</strong></label>
    <div class="col-sm-9">
        <input type="text" name="kolon" class="form-control">
        <textarea class="form-control mt-2" rows="2" readonly></textarea>
    </div>
    <div class="col-sm-1"><input type="checkbox" disabled></div>
</div>
<!-- GENITALIA -->
<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>Genetalia</strong></label>
</div>

<!-- LAKI-LAKI -->
<div class="row mb-2">
    <label class="col-sm-12"><strong>Anak Laki-laki</strong></label>
</div>

<!-- Fistula -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Fistula Urinari (Laki-laki)</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="fistula_pria">
        <textarea class="form-control" rows="2"   placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea></textarea>
    </div>
    <div class="col-sm-1 text-center">
        <input type="checkbox" class="form-check-input mt-2">
    </div>
</div>

<!-- Uretra -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Lubang Uretra</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="uretra">
        <textarea class="form-control" rows="2"   placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea></textarea>
    </div>
    <div class="col-sm-1 text-center">
        <input type="checkbox" class="form-check-input mt-2">
    </div>
</div>

<!-- Skrotum -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Skrotum</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="skrotum">
        <textarea class="form-control" rows="2"   placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea></textarea>
    </div>
    <div class="col-sm-1 text-center">
        <input type="checkbox" class="form-check-input mt-2">
    </div>
</div>

<!-- Genital Ganda -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Genitalia Ganda</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="genital_ganda">
        <textarea class="form-control" rows="2"   placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea></textarea>
    </div>
    <div class="col-sm-1 text-center">
        <input type="checkbox" class="form-check-input mt-2">
    </div>
</div>

<!-- Hidrokel -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>-	Data lain</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="data_lain">
        <textarea class="form-control" rows="2"   placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea></textarea>
    </div>
    <div class="col-sm-1 text-center">
        <input type="checkbox" class="form-check-input mt-2">
    </div>
</div>

<!-- PEREMPUAN -->
<div class="row mb-2">
    <label class="col-sm-12"><strong>Anak Perempuan</strong></label>
</div>

<!-- Labia -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Labia & Klitoris</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="labia">
        <textarea class="form-control" rows="2"  placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea></textarea>
    </div>
    <div class="col-sm-1 text-center">
        <input type="checkbox" class="form-check-input mt-2">
    </div>
</div>

<!-- Fistula Wanita -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Fistula Urogenital (Perempuan)</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="fistula_wanita">
        <textarea class="form-control" rows="2"   placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea></textarea>
    </div>
    <div class="col-sm-1 text-center">
        <input type="checkbox" class="form-check-input mt-2">
    </div>
</div>

<!-- Hidrokel Wanita -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Data Lain</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="data_lain">
        <textarea class="form-control" rows="2"   placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea></textarea>
    </div>
    <div class="col-sm-1 text-center">
        <input type="checkbox" class="form-check-input mt-2">
    </div>
</div>
<!-- ANUS -->
<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>Anus</strong></label>
</div>

<!-- Lubang Anal -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Lubang Anal Paten</strong></label>
    <div class="col-sm-9">
                <input type="text" class="form-control mb-2" name="lubang_anal">

        <textarea class="form-control mt-2" rows="2"   placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea></textarea>
    </div>
    <div class="col-sm-1 text-center">
        <input type="checkbox" class="form-check-input mt-2">
    </div>
</div>

<!-- Mekonium -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label">
        <strong>Lintasan Mekonium (36 jam)</strong>
    </label>
    <div class="col-sm-9">
        <input type="radio" name="mekonium" value="ada"> Ada
        <input type="radio" name="mekonium" value="tidak"> Tidak
        <textarea class="form-control mt-2" rows="2"   placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea></textarea>
    </div>
    <div class="col-sm-1 text-center">
        <input type="checkbox" class="form-check-input mt-2">
    </div>
</div>

<!-- EKSTREMITAS ATAS -->
<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>Ekstremitas Atas</strong></label>
</div>

<div class="row mb-2">
    <label class="col-sm-12"><strong>Motorik</strong></label>
</div>

<!-- Pergerakan -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Pergerakan Kanan/Kiri</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="gerak_atas">
        <textarea class="form-control" rows="2"   placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea></textarea>
    </div>
    <div class="col-sm-1 text-center">
        <input type="checkbox" class="form-check-input mt-2">
    </div>
</div>

<!-- Abnormal -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Pergerakan Abnormal</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="abnormal_atas">
        <textarea class="form-control" rows="2"   placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea></textarea>
    </div>
    <div class="col-sm-1 text-center">
        <input type="checkbox" class="form-check-input mt-2">
    </div>
</div>

<!-- Kekuatan -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Kekuatan Otot Kanan/Kiri</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="kekuatan_atas">
        <textarea class="form-control" rows="2"   placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea></textarea>
    </div>
    <div class="col-sm-1 text-center">
        <input type="checkbox" class="form-check-input mt-2">
    </div>
</div>

<!-- Koordinasi -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Koordinasi Gerak</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="koordinasi_atas">
        <textarea class="form-control" rows="2"   placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea></textarea>
    </div>
    <div class="col-sm-1 text-center">
        <input type="checkbox" class="form-check-input mt-2">
    </div>
</div>
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Jumlah jari</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="koordinasi_atas">
        <textarea class="form-control" rows="2"   placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea></textarea>
    </div>
    <div class="col-sm-1 text-center">
        <input type="checkbox" class="form-check-input mt-2">
    </div>
</div>
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Polidaktili atau sidaktili</strong></label>
     <div class="col-sm-9">
        <div class="d-flex align-items-center gap-3 flex-wrap">

            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="keadaan_kepala" value="Ya">
                <label class="form-check-label"><strong>Ya</strong></label>
            </div>

            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="keadaan_kepala" value="tidak">
                <label class="form-check-label"><strong>Tidak </strong></label>
            </div>

        </div>

        <textarea id="comment_kepala" class="form-control mt-2" rows="2"
        placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!"
        readonly></textarea>
    </div>

    <div class="col-sm-1 d-flex align-items-start">
        <input type="checkbox" class="form-check-input"
        onchange="document.getElementById('comment_kepala').style.display = this.checked ? 'none' : 'block'">
    </div>
</div>
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>-	Polidaktili atau sidaktili</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="koordinasi_atas">
        <textarea class="form-control" rows="2"   placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea></textarea>
    </div>
    <div class="col-sm-1 text-center">
        <input type="checkbox" class="form-check-input mt-2">
    </div>
</div>

<!-- SENSORI -->
<div class="row mb-2">
    <label class="col-sm-12"><strong>Sensori</strong></label>
</div>

<!-- Nyeri -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Nyeri</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="nyeri_atas">
        <textarea class="form-control" rows="2"   placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea></textarea>
    </div>
    <div class="col-sm-1 text-center">
        <input type="checkbox" class="form-check-input mt-2">
    </div>
</div>

<!-- Suhu -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Rangsang Suhu</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="suhu_atas">
        <textarea class="form-control" rows="2"   placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea></textarea>
    </div>
    <div class="col-sm-1 text-center">
        <input type="checkbox" class="form-check-input mt-2">
    </div>
</div>

<!-- Raba -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Rasa Raba</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="raba_atas">
        <textarea class="form-control" rows="2"   placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea></textarea>
    </div>
    <div class="col-sm-1 text-center">
        <input type="checkbox" class="form-check-input mt-2">
    </div>
</div>
<!-- EKSTREMITAS BAWAH -->
<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>Ekstremitas Bawah</strong></label>
</div>

<div class="row mb-2">
    <label class="col-sm-12"><strong>Motorik</strong></label>
</div>

<!-- Gaya Berjalan -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Gaya Berjalan</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="gaya_jalan">
        <textarea class="form-control" rows="2"   placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea></textarea>
    </div>
    <div class="col-sm-1 text-center">
        <input type="checkbox" class="form-check-input mt-2">
    </div>
</div>

<!-- Kekuatan -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Kekuatan Kanan/Kiri</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="kekuatan_bawah">
        <textarea class="form-control" rows="2"   placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
    </div>
    <div class="col-sm-1 text-center">
        <input type="checkbox" class="form-check-input mt-2">
    </div>
</div>

<!-- Tonus -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Tonus Otot Kanan/Kiri</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="tonus_bawah">
        <textarea class="form-control" rows="2"  placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
    </div>
    <div class="col-sm-1 text-center">
        <input type="checkbox" class="form-check-input mt-2">
    </div>
</div>
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Jumlah jari</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="koordinasi_atas">
        <textarea class="form-control" rows="2"   placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea></textarea>
    </div>
    <div class="col-sm-1 text-center">
        <input type="checkbox" class="form-check-input mt-2">
    </div>
</div>
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Polidaktili atau sidaktili</strong></label>
     <div class="col-sm-9">
        <div class="d-flex align-items-center gap-3 flex-wrap">

            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="keadaan_kepala" value="Ya">
                <label class="form-check-label"><strong>Ya</strong></label>
            </div>

            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="keadaan_kepala" value="tidak">
                <label class="form-check-label"><strong>Tidak </strong></label>
            </div>

        </div>

        <textarea id="comment_kepala" class="form-control mt-2" rows="2"
        placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!"
        readonly></textarea>
    </div>

    <div class="col-sm-1 d-flex align-items-start">
        <input type="checkbox" class="form-check-input"
        onchange="document.getElementById('comment_kepala').style.display = this.checked ? 'none' : 'block'">
    </div>
</div>
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Polidaktili atau sidaktili</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="koordinasi_atas">
        <textarea class="form-control" rows="2"   placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea></textarea>
    </div>
    <div class="col-sm-1 text-center">
        <input type="checkbox" class="form-check-input mt-2">
    </div>
</div>
<div class="row mb-2">
    <label class="col-sm-12"><strong>Sensori</strong></label>
</div>

<!-- Nyeri -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Nyeri</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="nyeri_bawah">
        <textarea class="form-control" rows="2"   placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
    </div>
    <div class="col-sm-1 text-center">
        <input type="checkbox" class="form-check-input mt-2">
    </div>
</div>

<!-- Suhu -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Rangsang Suhu</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="suhu_bawah">
        <textarea class="form-control" rows="2"   placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
    </div>
    <div class="col-sm-1 text-center">
        <input type="checkbox" class="form-check-input mt-2">
    </div>
</div>

<!-- Raba -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Rasa Raba</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="raba_bawah">
        <textarea class="form-control" rows="2"   placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
    </div>
    <div class="col-sm-1 text-center">
        <input type="checkbox" class="form-check-input mt-2">
    </div>
</div>
<!-- INTEGUMEN -->
<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>Integumen</strong></label>
</div>

<!-- Turgor -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Turgor Kulit</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="turgor">
        <textarea class="form-control" rows="2"   placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
    </div>
    <div class="col-sm-1 text-center"><input type="checkbox" class="form-check-input mt-2"></div>
</div>

<!-- Finger Print -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Finger Print di Dahi</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="finger_print">
        <textarea class="form-control" rows="2"   placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
    </div>
    <div class="col-sm-1 text-center"><input type="checkbox" class="form-check-input mt-2"></div>
</div>

<!-- Lesi -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Adanya Lesi</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="lesi">
        <textarea class="form-control" rows="2"   placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
    </div>
    <div class="col-sm-1 text-center"><input type="checkbox" class="form-check-input mt-2"></div>
</div>

<!-- Kebersihan -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Kebersihan Kulit</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="kebersihan">
        <textarea class="form-control" rows="2"   placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
    </div>
    <div class="col-sm-1 text-center"><input type="checkbox" class="form-check-input mt-2"></div>
</div>

<!-- Kelembaban -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Kelembaban Kulit</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="kelembaban">
        <textarea class="form-control" rows="2"   placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
    </div>
    <div class="col-sm-1 text-center"><input type="checkbox" class="form-check-input mt-2"></div>
</div>

<!-- Warna -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Warna Kulit</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="warna_kulit">
        <textarea class="form-control" rows="2"   placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
    </div>
    <div class="col-sm-1 text-center"><input type="checkbox" class="form-check-input mt-2"></div>
</div>
<!-- Pengkajian Refleks Primitif -->
<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>Pengkajian Refleks Primitif</strong></label>
</div>


<!-- Iddol -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Refleks Iddol</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="iddol">
        <textarea class="form-control mt-2" rows="2"   placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
    </div>
    <div class="col-sm-1 text-center">
        <input type="checkbox" class="form-check-input mt-2">
    </div>
</div>

<!-- Startel -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Refleks Startel</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="startel">
        <textarea class="form-control mt-2" rows="2"   placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
    </div>
    <div class="col-sm-1 text-center">
        <input type="checkbox" class="form-check-input mt-2">
    </div>
</div>

<!-- Sucking -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Refleks sucking (isap)</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="sucking">
        <textarea class="form-control mt-2" rows="2"   placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
    </div>
    <div class="col-sm-1 text-center">
        <input type="checkbox" class="form-check-input mt-2">
    </div>
</div>

<!-- Rooting -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Refleks rooting (menoleh)</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="rooting">
        <textarea class="form-control mt-2" rows="2"   placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
    </div>
    <div class="col-sm-1 text-center">
        <input type="checkbox" class="form-check-input mt-2">
    </div>
</div>

<!-- Gawn -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Refleks Gawn</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="gawn">
        <textarea class="form-control mt-2" rows="2"   placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
    </div>
    <div class="col-sm-1 text-center">
        <input type="checkbox" class="form-check-input mt-2">
    </div>
</div>

<!-- Grabella -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Refleks grabella</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="grabella">
        <textarea class="form-control mt-2" rows="2"   placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
    </div>
    <div class="col-sm-1 text-center">
        <input type="checkbox" class="form-check-input mt-2">
    </div>
</div>

<!-- Ekruction -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Refleks ekruction</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="ekruction">
        <textarea class="form-control mt-2" rows="2"   placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
    </div>
    <div class="col-sm-1 text-center">
        <input type="checkbox" class="form-check-input mt-2">
    </div>
</div>

<!-- Moro -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Refleks moro</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="moro">
        <textarea class="form-control mt-2" rows="2"   placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
    </div>
    <div class="col-sm-1 text-center">
        <input type="checkbox" class="form-check-input mt-2">
    </div>
</div>

<!-- Grasping -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Refleks garsping</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="grasping">
        <textarea class="form-control mt-2" rows="2"   placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
    </div>
    <div class="col-sm-1 text-center">
        <input type="checkbox" class="form-check-input mt-2">
    </div>
</div>
<!-- Tes Diagnostik -->
<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>Tes Diagnostik</strong></label>
</div>

<!-- 17. LABORATORIUM -->
<div class="row mb-2">
    <label class="col-sm-12 text-primary">
        <strong> Laboratorium</strong>
    </label>
</div>

<div class="row mb-3 align-items-start">
    <div class="col-sm-11">
        <textarea class="form-control mb-2" rows="3" name="laboratorium"></textarea>
        <textarea class="form-control" rows="2"   placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
    </div>
    <div class="col-sm-1 text-center">
        <input type="checkbox" class="form-check-input mt-2">
    </div>
</div>

<!-- PENUNJANG -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Link drive Laboratorium</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="penunjang"
        placeholder="">
        <textarea class="form-control" rows="2"   placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
    </div>
    <div class="col-sm-1 text-center">
        <input type="checkbox" class="form-check-input mt-2">
    </div>
</div>

<!-- PENUNJANG -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Pemeriksaan Penunjang</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="penunjang"
        placeholder="Foto Rontgen, CT Scan, MRI, USG, EEG, ECG">
        <textarea class="form-control" rows="2"   placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
    </div>
    <div class="col-sm-1 text-center">
        <input type="checkbox" class="form-check-input mt-2">
    </div>
</div>

<!-- TERAPI -->
<div class="row mb-2">
    <label class="col-sm-12 text-primary">
        <strong>Terapi Saat Ini (ditulis dengan rinci)</strong>
    </label>
</div>

<div class="row mb-3 align-items-start">
    <div class="col-sm-11">
        <textarea class="form-control mb-2" rows="4" name="terapi"></textarea>
        <textarea class="form-control" rows="2"   placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
    </div>
    <div class="col-sm-1 text-center">
        <input type="checkbox" class="form-check-input mt-2">
    </div>
</div>
</div>
</div>
</div>
</div>







 <div class="row mb-2">
                        <div class="card">
            <div class="card-body">
            
                <!-- General Form Elements -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

                <h5 class="card-title"><strong>Klasifikasi Data</strong></h5>

                    

                <!-- Bagian Data Subjektif (DS) -->
                <div class="row mb-3">
                    <label for="datasubjektif" class="col-sm-2 col-form-label"><strong>Data Subjektif (DS)</strong></label>
                    <div class="col-sm-9">
                        <textarea name="datasubjektif" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

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
                    <label for="dataobjektif" class="col-sm-2 col-form-label"><strong>Data Objektif (DO)</strong></label>
                    <div class="col-sm-9">
                        <textarea name="dataobjektif" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

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

                    <h5 class="card-title"><strong>Analisa Data</strong></h5>
                <div class="row mb-2">
                  

                <!-- Bagian DS/DO -->
                <div class="row mb-3">
                    <label for="dsdo" class="col-sm-2 col-form-label"><strong>NO</strong></label>
                    <div class="col-sm-9">
                        <textarea name="dsdo" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentdsdo" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 
                    <!-- Bagian DATA -->
                <div class="row mb-3">
                    <label for="dsdo" class="col-sm-2 col-form-label"><strong>Data</strong></label>
                    <div class="col-sm-9">
                        <textarea name="dsdo" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentdsdo" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 

                <!-- Bagian Etiologi -->
                <div class="row mb-3">
                    <label for="etiologi" class="col-sm-2 col-form-label"><strong>Etiologi</strong></label>
                    <div class="col-sm-9">
                        <textarea name="etiologi" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentetiologi" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 
                    
                <!-- Bagian Masalah -->
                <div class="row mb-3">
                    <label for="masalah" class="col-sm-2 col-form-label"><strong>Masalah</strong></label>
                    <div class="col-sm-9">
                        <textarea name="masalah" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentmasalah" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
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
                                <th class="text-center">DS/DO</th>
                                <th class="text-center">Data</th>
                                <th class="text-center">Etiologi</th>
                                <th class="text-center">Masalah</th>
                        </tr>
                        </thead>

                    <tbody>
                        </tbody>
                        </table>
                        </div>
                        </div>
                        </div>
             


   
</section>                    <?php include "tab_navigasi.php"; ?>

</main>



    

