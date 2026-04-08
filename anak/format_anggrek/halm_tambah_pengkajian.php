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
        <h1><strong>Asuhan Keperawatan Anak Anggrek B</strong></h1>
    </div><!-- End Page Title -->
    <br>
<ul class="nav nav-tabs custom-tabs">

<li class="nav-item">
    <a class="nav-link <?= ($_GET['tab'] ?? 'format_laporan_pendahuluan') == 'format_laporan_pendahuluan' ? 'active' : '' ?>"
    href="index.php?page=anak/format_anggrek&tab=format_laporan_pendahuluan">
    Format Laporan Pendahuluan
    </a>
</li>

<li class="nav-item">
   <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'pengkajian' ? 'active' : '' ?>"
        href="index.php?page=anak/format_anggrek&tab=pengkajian"> Format Pengkajian Anak</a>
    </li>
    </a>
</li>
<li class="nav-item">
    <a class="nav-link <?= ($_GET['tab'] ?? '') == 'diagnosa' ? 'active' : '' ?>"
    href="index.php?page=anak/format_anggrek&tab=diagnosa">
    Diagnosa Keperawatan    </a>
</li>
<li class="nav-item">
    <a class="nav-link <?= ($_GET['tab'] ?? '') == 'rencana' ? 'active' : '' ?>"
    href="index.php?page=anak/format_anggrek&tab=rencana">
    Rencana Keperawatan
    </a>
</li>
<li class="nav-item">
    <a class="nav-link <?= ($_GET['tab'] ?? '') == 'implementasi' ? 'active' : '' ?>"
    href="index.php?page=anak/format_anggrek&tab=implementasi">
    Implementasi Keperawatan
    </a>
</li>
<li class="nav-item">
    <a class="nav-link <?= ($_GET['tab'] ?? '') == 'evaluasi' ? 'active' : '' ?>"
    href="index.php?page=anak/format_anggrek&tab=evaluasi">
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
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

                <!-- Bagian Diagnosa -->

                    <!-- A PENGKAJIAN -->
<div class="row mb-2">
<label class="col-sm-12 text-primary">
<strong>A. Identitas</strong>
</label>
</div>

<div class="row mb-2">
<label class="col-sm-12">
<strong>1. Identitas klien</strong>
</label>
</div>
<!-- a. Nama Anak -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>a. Nama Anak :</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="nama_anak">
        <textarea class="form-control mt-2" id="commentnamaanak" rows="2"
            placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1">
        <div class="form-check">
            <input class="form-check-input" type="checkbox"
                onchange="document.getElementById('commentnamaanak').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</div>

<!-- b. TTL / Umur -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>b. Tempat, Tgl Lahir / Usia :</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="ttl_umur">
        <textarea class="form-control mt-2" id="commentttl" rows="2"
            placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1">
        <div class="form-check">
            <input class="form-check-input" type="checkbox"
                onchange="document.getElementById('commentttl').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</div>

<!-- c. Jenis Kelamin -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>c. Jenis Kelamin :</strong></label>
    <div class="col-sm-9">
        <select class="form-control" name="jenis_kelamin">
            <option value="">-- Pilih --</option>
            <option value="Laki-laki">Laki-laki</option>
            <option value="Perempuan">Perempuan</option>
        </select>
        <textarea class="form-control mt-2" id="commentjk" rows="2"
            placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1">
        <div class="form-check">
            <input class="form-check-input" type="checkbox"
                onchange="document.getElementById('commentjk').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</div>

<!-- d. Agama -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>d. Agama :</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="agama">
        <textarea class="form-control mt-2" id="commentagama" rows="2"
            placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1">
        <div class="form-check">
            <input class="form-check-input" type="checkbox"
                onchange="document.getElementById('commentagama').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</div>

<!-- e. Alamat -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>e. Alamat :</strong></label>
    <div class="col-sm-9">
        <textarea class="form-control" rows="3" name="alamat"></textarea>
        <textarea class="form-control mt-2" id="commentalamat" rows="2"
            placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1">
        <div class="form-check">
            <input class="form-check-input" type="checkbox"
                onchange="document.getElementById('commentalamat').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</div>

<!-- f. Tanggal Masuk -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>f. Tgl Masuk :</strong></label>
    <div class="col-sm-9">
        <div class="d-flex align-items-center">
            <input type="date" class="form-control me-2" name="tgl_masuk">
            <span class="me-2">jam</span>
            <input type="time" class="form-control" name="jam_masuk">
        </div>
        <textarea class="form-control mt-2" id="commenttglmasuk" rows="2"
            placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1">
        <div class="form-check">
            <input class="form-check-input" type="checkbox"
                onchange="document.getElementById('commenttglmasuk').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</div>

<!-- g. Tanggal Pengkajian -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>g. Tanggal Pengkajian :</strong></label>
    <div class="col-sm-9">
        <input type="date" class="form-control" name="tgl_pengkajian">
        <textarea class="form-control mt-2" id="commenttglkaji" rows="2"
            placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1">
        <div class="form-check">
            <input class="form-check-input" type="checkbox"
                onchange="document.getElementById('commenttglkaji').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</div>

<!-- h. Diagnosa Medik -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>h. Diagnosa Medik :</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="diagnosa_medik">
        <textarea class="form-control mt-2" id="commentdiagnosamedik" rows="2"
            placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1">
        <div class="form-check">
            <input class="form-check-input" type="checkbox"
                onchange="document.getElementById('commentdiagnosamedik').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</div>


<!-- IDENTITAS PENANGGUNG -->
<div class="row mb-2">
<label class="col-sm-12 text-primary">
<strong>2. Identitas Orang tua</strong>
</label>
</div>
<!-- ===== AYAH ===== -->
<div class="row mb-2">
    <label class="col-sm-12"><strong>Ayah</strong></label>
</div>

<!-- a. Nama Ayah -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>a. Nama :</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="nama_ayah">
        <textarea class="form-control mt-2" id="commentnamaayah" rows="2"
            placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1">
        <div class="form-check">
            <input class="form-check-input" type="checkbox"
                onchange="document.getElementById('commentnamaayah').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</div>

<!-- b. Usia Ayah -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>b. Usia :</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="usia_ayah">
        <textarea class="form-control mt-2" id="commentusiaayah" rows="2"
            placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1">
        <div class="form-check">
            <input class="form-check-input" type="checkbox"
                onchange="document.getElementById('commentusiaayah').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</div>

<!-- c. Pendidikan Ayah -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>c. Pendidikan :</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="pendidikan_ayah">
        <textarea class="form-control mt-2" id="commentpendidikanayah" rows="2"
            placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1">
        <div class="form-check">
            <input class="form-check-input" type="checkbox"
                onchange="document.getElementById('commentpendidikanayah').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</div>

<!-- d. Pekerjaan Ayah -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>d. Pekerjaan/Sumber Penghasilan :</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="pekerjaan_ayah">
        <textarea class="form-control mt-2" id="commentpekerjaanayah" rows="2"
            placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1">
        <div class="form-check">
            <input class="form-check-input" type="checkbox"
                onchange="document.getElementById('commentpekerjaanayah').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</div>

<!-- e. Agama Ayah -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>e. Agama :</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="agama_ayah">
        <textarea class="form-control mt-2" id="commentagamaayah" rows="2"
            placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1">
        <div class="form-check">
            <input class="form-check-input" type="checkbox"
                onchange="document.getElementById('commentagamaayah').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</div>

<!-- f. Alamat Ayah -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>f. Alamat :</strong></label>
    <div class="col-sm-9">
        <textarea class="form-control" rows="3" name="alamat_ayah"></textarea>
        <textarea class="form-control mt-2" id="commentalamatayah" rows="2"
            placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1">
        <div class="form-check">
            <input class="form-check-input" type="checkbox"
                onchange="document.getElementById('commentalamatayah').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</div>
<!-- ===== IBU ===== -->
<div class="row mb-2">
    <label class="col-sm-12"><strong>Ibu</strong></label>
</div>

<!-- a. Nama Ibu -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>a. Nama :</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="nama_ibu">
        <textarea class="form-control mt-2" id="commentnamaibu" rows="2"
            placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1">
        <div class="form-check">
            <input class="form-check-input" type="checkbox"
                onchange="document.getElementById('commentnamaibu').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</div>

<!-- b. Usia Ibu -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>b. Usia :</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="usia_ibu">
        <textarea class="form-control mt-2" id="commentusiaibu" rows="2"
            placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1">
        <div class="form-check">
            <input class="form-check-input" type="checkbox"
                onchange="document.getElementById('commentusiaibu').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</div>

<!-- c. Pendidikan Ibu -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>c. Pendidikan :</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="pendidikan_ibu">
        <textarea class="form-control mt-2" id="commentpendidikanibu" rows="2"
            placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1">
        <div class="form-check">
            <input class="form-check-input" type="checkbox"
                onchange="document.getElementById('commentpendidikanibu').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</div>

<!-- d. Pekerjaan Ibu -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>d. Pekerjaan/Sumber Penghasilan :</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="pekerjaan_ibu">
        <textarea class="form-control mt-2" id="commentpekerjaanibu" rows="2"
            placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1">
        <div class="form-check">
            <input class="form-check-input" type="checkbox"
                onchange="document.getElementById('commentpekerjaanibu').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</div>

<!-- e. Agama Ibu -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>e. Agama :</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="agama_ibu">
        <textarea class="form-control mt-2" id="commentagamaibu" rows="2"
            placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1">
        <div class="form-check">
            <input class="form-check-input" type="checkbox"
                onchange="document.getElementById('commentagamaibu').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</div>

<!-- f. Alamat Ibu -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>f. Alamat :</strong></label>
    <div class="col-sm-9">
        <textarea class="form-control" rows="3" name="alamat_ibu"></textarea>
        <textarea class="form-control mt-2" id="commentalamatibu" rows="2"
            placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1">
        <div class="form-check">
            <input class="form-check-input" type="checkbox"
                onchange="document.getElementById('commentalamatibu').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</div>

         <div class="row mb-2">
<label class="col-sm-12 text-primary">
<strong>3.	Identitas Saudara Kandung	</strong>
</label>
</div>

                <!-- General Form Elements -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

                <!-- Bagian No. DX -->

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>No</strong></label>

                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="no">

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


                    <div class="row mb-3">
                        <label for="hari_tgl" class="col-sm-2 col-form-label"><strong>Nama</strong></label>

                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="Nama" name="Nama">
                            
                             <!-- comment -->
                            <textarea class="form-control mt-2" id="commenthari_Nama" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
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
                        <label for="jam" class="col-sm-2 col-form-label"><strong>Usia</strong></label>

                        <div class="col-sm-9">
                             <input type="text" class="form-control" id="usia" name="usia">
                            
                             <!-- comment -->
                            <textarea class="form-control mt-2" id="commentusia" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>
                     <div class="row mb-3">
                        <label for="jam" class="col-sm-2 col-form-label"><strong>Hubungan</strong></label>

                        <div class="col-sm-9">
                             <input type="text" class="form-control" id="hubungan" name="hubungan">
                            
                             <!-- comment -->
                            <textarea class="form-control mt-2" id="commenthubungan" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>
                    <div class="row mb-3">
                        <label for="jam" class="col-sm-2 col-form-label"><strong>Status Kesehatan</strong></label>

                        <div class="col-sm-9">
                             <input type="text" class="form-control" id="status_kesehatan" name="status_kesehatan">
                            
                             <!-- comment -->
                            <textarea class="form-control mt-2" id="commentstatus_kesehatan" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
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

                    <h5 class="card-title mt-2"><strong>3.	Identitas Saudara Kandung</strong></h5>

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
                                <th class="text-center">Nama</th>
                                <th class="text-center">Usia</th>
                                <th class="text-center">Hubungan</th>
                                <th class="text-center">Status Kesehatan</th>
                        </tr>
                        </thead>

                    <tbody>

                    <?php
                    if(!empty($data)){
                        foreach($data as $row){
                            echo "<tr>
                            <td>".$row['no']."</td>
                            <td>".$row['nama']."</td>
                            <td>".$row['usia']."</td>
                            <td>".$row['hubungan']."</td>
                            <td>".$row['status_kesehatan']."</td>
                            </tr>";
                        }
                    }
                    ?>

                    </tbody>
                    </table>
                    </form>
                    <!-- JUDUL -->
<div class="row mb-3">
    <label class="col-sm-12 col-form-label text-primary">
        <strong>4. Riwayat Kesehatan</strong>
    </label>
</div>

<!-- Alasan Masuk RS -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label">
        <strong>Alasan Masuk RS :</strong>
    </label>
    <div class="col-sm-9">
        <textarea class="form-control" rows="3" name="alasan_masuk"></textarea>

        <textarea class="form-control mt-2" id="commentalasan" rows="2"
        placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!"
        readonly></textarea>
    </div>
    <div class="col-sm-1">
        <div class="form-check">
            <input class="form-check-input" type="checkbox"
                onchange="document.getElementById('commentalasan').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</div>

<!-- Keluhan Utama -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label">
        <strong>Keluhan Utama :</strong>
    </label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="keluhan_utama">

        <textarea class="form-control mt-2" id="commentkeluhan" rows="2"
        placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!"
        readonly></textarea>
    </div>
    <div class="col-sm-1">
        <div class="form-check">
            <input class="form-check-input" type="checkbox"
                onchange="document.getElementById('commentkeluhan').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</div>

<!-- Riwayat Kesehatan Sekarang -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label">
        <strong>Riwayat Kesehatan Sekarang :</strong>
    </label>
    <div class="col-sm-9">
        <textarea class="form-control" rows="4" name="riwayat_sekarang"></textarea>

        <textarea class="form-control mt-2" id="commentriwayat" rows="2"
        placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!"
        readonly></textarea>
    </div>
    <div class="col-sm-1">
        <div class="form-check">
            <input class="form-check-input" type="checkbox"
                onchange="document.getElementById('commentriwayat').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</div>
<!-- JUDUL -->
<div class="row mb-3">
    <label class="col-sm-12 col-form-label text-primary">
        <strong>5. Riwayat Kesehatan Lalu (0 – 5 Tahun)</strong>
    </label>
</div>

<!-- ===== PRENATAL ===== -->
<div class="row mb-2">
    <label class="col-sm-12"><strong>Prenatal Care</strong></label>
</div>
<!-- a -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>a. Ibu memeriksakan kehamilannya setiap minggu di</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="prenatal_periksa">
        <textarea class="form-control mt-2" id="commentperiksa" rows="2"
            placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1">
        <input class="form-check-input" type="checkbox"
            onchange="document.getElementById('commentperiksa').style.display = this.checked ? 'none' : 'block'">
    </div>
</div>

<!-- b -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>b. Keluhan selama hamil yang dirasakan oleh ibu, tapi oleh dokter dianjurkan untuk</strong></label>
    <div class="col-sm-9">
        <textarea class="form-control" rows="3" name="prenatal_keluhan"></textarea>
        <textarea class="form-control mt-2" id="commentkeluhanhamil" rows="2"
            placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1">
        <input class="form-check-input" type="checkbox"
            onchange="document.getElementById('commentkeluhanhamil').style.display = this.checked ? 'none' : 'block'">
    </div>
</div>

<!-- c -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>c. Riwayat berat badan selama hamil</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="prenatal_bb">
        <textarea class="form-control mt-2" id="commentbb" rows="2"
            placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1">
        <input class="form-check-input" type="checkbox"
            onchange="document.getElementById('commentbb').style.display = this.checked ? 'none' : 'block'">
    </div>
</div>

<!-- d -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>d. Riwayat Imunisasi TT </strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="prenatal_tt">
        <textarea class="form-control mt-2" id="commenttt" rows="2"
            placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1">
        <input class="form-check-input" type="checkbox"
            onchange="document.getElementById('commenttt').style.display = this.checked ? 'none' : 'block'">
    </div>
</div>

<!-- e -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>e. Golongan darah ibu </strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="goldar_ibu">
        <textarea class="form-control mt-2" id="commentgoldaribu" rows="2"
            placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1">
        <input class="form-check-input" type="checkbox"
            onchange="document.getElementById('commentgoldaribu').style.display = this.checked ? 'none' : 'block'">
    </div>
</div>

<!-- f -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>f. Golongan Darah Ayah :</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="goldar_ayah">
        <textarea class="form-control mt-2" id="commentgoldarayah" rows="2"
            placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1">
        <input class="form-check-input" type="checkbox"
            onchange="document.getElementById('commentgoldarayah').style.display = this.checked ? 'none' : 'block'">
    </div>
</div>
<!-- ===== INTRANATAL ===== -->
<div class="row mb-2">
    <label class="col-sm-12"><strong>Intra Natal</strong></label>
</div>
<!-- a -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>a. Tempat Melahirkan :</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="tempat_lahir">
        <textarea class="form-control mt-2" id="commenttempatlahir" rows="2"
            placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1">
        <input class="form-check-input" type="checkbox"
            onchange="document.getElementById('commenttempatlahir').style.display = this.checked ? 'none' : 'block'">
    </div>
</div>

<!-- b -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>b. Jenis Persalinan :</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="jenis_persalinan">
        <textarea class="form-control mt-2" id="commentjenispersalinan" rows="2"
            placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1">
        <input class="form-check-input" type="checkbox"
            onchange="document.getElementById('commentjenispersalinan').style.display = this.checked ? 'none' : 'block'">
    </div>
</div>

<!-- c -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>c. Penolong Persalinan :</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="penolong">
        <textarea class="form-control mt-2" id="commentpenolong" rows="2"
            placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1">
        <input class="form-check-input" type="checkbox"
            onchange="document.getElementById('commentpenolong').style.display = this.checked ? 'none' : 'block'">
    </div>
</div>

<!-- d -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>d. Komplikasi yang dialami oleh ibu pada saat melahirkan dan setelah melahirkan</strong></label>
    <div class="col-sm-9">
        <textarea class="form-control" rows="3" name="komplikasi"></textarea>
        <textarea class="form-control mt-2" id="commentkomplikasi_persalinan" rows="2"
            placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1">
        <input class="form-check-input" type="checkbox"
            onchange="document.getElementById('commentkomplikasi_persalinan').style.display = this.checked ? 'none' : 'block'">
    </div>
</div>

<!-- ===== POST NATAL ===== -->
<div class="row mb-2">
    <label class="col-sm-12"><strong>Post Natal</strong></label>
</div>
<!-- a -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>a. Kondisi Bayi :</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="kondisi_bayi">
        <textarea class="form-control mt-2" id="commentkondisibayi" rows="2"
            placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1">
        <input class="form-check-input" type="checkbox"
            onchange="document.getElementById('commentkondisibayi').style.display = this.checked ? 'none' : 'block'">
    </div>
</div>

<!-- b -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>b. Apakah Anak pada saat lahir mengalami gangguan </strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="gangguan_lahir">
        <textarea class="form-control mt-2" id="commentgangguanlahir" rows="2"
            placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1">
        <input class="form-check-input" type="checkbox"
            onchange="document.getElementById('commentgangguanlahir').style.display = this.checked ? 'none' : 'block'">
    </div>
</div>

<!-- ===== UMUM ===== -->
<div class="row mb-2">
    <label class="col-sm-12"><strong>(Untuk Semua Usia)</strong></label>
</div>
<!-- a -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label">
        <strong>a. Klien pernah mengalami </strong>
    </label>

    <div class="col-sm-9">
        <div class="d-flex align-items-center flex-wrap">
            <span class="me-2">penyakit</span>

            <input type="text" class="form-control me-2 mb-2" name="penyakit" style="max-width: 200px;">

            <span class="me-2">pada umur :</span>

            <input type="text" class="form-control me-2 mb-2" name="umur_penyakit" style="max-width: 120px;">

            <span class="me-2">diberikan obat oleh :</span>

            <input type="text" class="form-control mb-2" name="obat_oleh" style="max-width: 250px;">
        </div>

        <textarea class="form-control mt-2" id="commentpenyakit" rows="2"
        placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!"
        readonly></textarea>
    </div>

    <div class="col-sm-1">
        <input class="form-check-input" type="checkbox"
            onchange="document.getElementById('commentpenyakit').style.display = this.checked ? 'none' : 'block'">
    </div>
</div>

<!-- b -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>b. Riwayat Kecelakaan </strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="kecelakaan">
        <textarea class="form-control mt-2" id="commentkecelakaan" rows="2"
        placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1">
        <input class="form-check-input" type="checkbox"
            onchange="document.getElementById('commentkecelakaan').style.display = this.checked ? 'none' : 'block'">
    </div>
</div>

<!-- c -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>c. Riwayat mengkonsumsi obat-obatan berbahaya tanpa anjuran dokter dan menggunakan zat/subtansi kimia yang berbahaya </strong></label>
    <div class="col-sm-9">
        <textarea class="form-control" rows="3" name="zat_berbahaya"></textarea>
        <textarea class="form-control mt-2" id="commentzat" rows="2"
        placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1">
        <input class="form-check-input" type="checkbox"
            onchange="document.getElementById('commentzat').style.display = this.checked ? 'none' : 'block'">
    </div>
</div>

<!-- d -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>d. Perkembangan anak dibanding saudara-saudaranya </strong></label>
    <div class="col-sm-9">
        <textarea class="form-control" rows="3" name="perkembangan"></textarea>
        <textarea class="form-control mt-2" id="commentperkembangan" rows="2"
        placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1">
        <input class="form-check-input" type="checkbox"
            onchange="document.getElementById('commentperkembangan').style.display = this.checked ? 'none' : 'block'">
    </div>
</div>

<!-- JUDUL -->
<div class="row mb-3">
    <label class="col-sm-12 col-form-label text-primary">
        <strong>6. Riwayat Kesehatan Keluarga</strong>
    </label>
</div>

<!-- Genogram -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label">
        <strong>Genogram</strong>
    </label>

    <div class="col-sm-9">
        <textarea class="form-control" rows="1" name="genogram"></textarea>

        <textarea class="form-control mt-2" id="commentgenogram" rows="2"
        placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!"
        readonly></textarea>
    </div>

    <div class="col-sm-1">
        <input class="form-check-input" type="checkbox"
            onchange="document.getElementById('commentgenogram').style.display = this.checked ? 'none' : 'block'">
    </div>
</div>
<!-- JUDUL -->
<div class="row mb-3">
    <label class="col-sm-12 col-form-label text-primary">
        <strong>7. Riwayat Imunisasi (Imunisasi Lengkap)</strong>
    </label>
</div>

<div class="table-responsive">
<table class="table table-bordered">
    <thead class="text-center">
        <tr>
            <th style="width:5%">No</th>
            <th style="width:35%">Jenis Imunisasi</th>
            <th style="width:25%">Frekuensi</th>
            <th style="width:35%">Reaksi Setelah Pemberian</th>
            <th style="width:5%"></th>
        </tr>
    </thead>
    <tbody>

        <tr>
            <td class="text-center">1</td>
            <td>BCG</td>
            <td><input type="text" class="form-control" name="bcg_frekuensi"></td>
            <td><input type="text" class="form-control" name="bcg_reaksi"></td>
            <td class="text-center align-middle" rowspan="5">
                <input type="checkbox" class="form-check-input"
                    onchange="document.getElementById('commentimunisasi').style.display = this.checked ? 'none' : 'block'">
            </td>
        </tr>

        <tr>
            <td class="text-center">2</td>
            <td>DPT Hb Hib (I, II, III)</td>
            <td><input type="text" class="form-control" name="dpt_frekuensi"></td>
            <td><input type="text" class="form-control" name="dpt_reaksi"></td>
        </tr>

        <tr>
            <td class="text-center">3</td>
            <td>Polio (I, II, III, IV)</td>
            <td><input type="text" class="form-control" name="polio_frekuensi"></td>
            <td><input type="text" class="form-control" name="polio_reaksi"></td>
        </tr>

        <tr>
            <td class="text-center">4</td>
            <td>Campak</td>
            <td><input type="text" class="form-control" name="campak_frekuensi"></td>
            <td><input type="text" class="form-control" name="campak_reaksi"></td>
        </tr>

        <tr>
            <td class="text-center">5</td>
            <td>Hepatitis</td>
            <td><input type="text" class="form-control" name="hepatitis_frekuensi"></td>
            <td><input type="text" class="form-control" name="hepatitis_reaksi"></td>
        </tr>

        <!-- REVISI DI BAWAH -->
        <tr>
            <td colspan="5">
                <label><strong></strong></label>
                <textarea class="form-control mt-2" id="commentimunisasi" rows="2"
                placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!"
                readonly></textarea>
            </td>
        </tr>

    </tbody>
</table>
</div>
<!-- 8. RIWAYAT TUMBUH KEMBANG -->
<div class="row mb-3">
    <label class="col-sm-12 col-form-label text-primary">
        <strong>8. Riwayat Tumbuh Kembang</strong>
    </label>
</div>
<div class="row mb-2">
    <label class="col-sm-12"><strong>Pertumbuhan Fisik</strong></label>
</div>

<!-- Berat Badan -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Berat  Badan</strong></label>
    <div class="col-sm-9">
        <div class="input-group">
            <input type="text" class="form-control" name="bb">
            <span class="input-group-text">kg</span>
        </div>

        <textarea class="form-control mt-2" id="commentbb" rows="2"
        placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!"
        readonly></textarea>
    </div>

    <div class="col-sm-1">
        <input class="form-check-input" type="checkbox"
            onchange="document.getElementById('commentbb').style.display = this.checked ? 'none' : 'block'">
    </div>
</div>

<!-- Tinggi Badan -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Tinggi Badan</strong></label>
    <div class="col-sm-9">
        <div class="input-group">
            <input type="text" class="form-control" name="tb">
            <span class="input-group-text">cm</span>
        </div>

        <textarea class="form-control mt-2" id="commenttb" rows="2"
        placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!"
        readonly></textarea>
    </div>

    <div class="col-sm-1">
        <input class="form-check-input" type="checkbox"
            onchange="document.getElementById('commenttb').style.display = this.checked ? 'none' : 'block'">
    </div>
</div>

<!-- Tumbuh Gigi -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Waktu Tumbuh Gigi</strong></label>
    <div class="col-sm-9">
        <div class="input-group">
            <input type="text" class="form-control" name="gigi">
            <span class="input-group-text">kg/cm</span>
        </div>

        <textarea class="form-control mt-2" id="commentgigi" rows="2"
        placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!"
        readonly></textarea>
    </div>

    <div class="col-sm-1">
        <input class="form-check-input" type="checkbox"
            onchange="document.getElementById('commentgigi').style.display = this.checked ? 'none' : 'block'">
    </div>
</div>

<!-- Gigi Tanggal & Jumlah -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Gigi Tanggal</strong></label>
    
    <div class="col-sm-9">
        <div class="d-flex flex-nowrap align-items-center gap-2 mb-2">
            <span>:</span>

            <input type="text" class="form-control" style="min-width:120px;">

            <span class="text-nowrap">Jumlah gigi</span>

            <input type="text" class="form-control" style="min-width:80px;">

            <span class="text-nowrap">buah</span>
        </div>

        <textarea class="form-control mt-2" id="commentgigijumlah" rows="2"
        placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!"
        readonly></textarea>
    </div>

    <div class="col-sm-1">
        <input type="checkbox" class="form-check-input mt-2"
            onchange="document.getElementById('commentgigijumlah').style.display = this.checked ? 'none' : 'block'">
    </div>
</div>

<!-- 9. RIWAYAT NUTRISI -->
<div class="row mb-3">
    <label class="col-sm-12 col-form-label text-primary">
        <strong>9. Riwayat Nutrisi</strong>
    </label>
</div>
<!-- ASI -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Pemberian ASI sampai usia</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="asi">

        <textarea class="form-control mt-2" id="commentasi" rows="2"
        placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!"
        readonly></textarea>
    </div>
    <div class="col-sm-1">
        <input type="checkbox" class="form-check-input"
            onchange="document.getElementById('commentasi').style.display = this.checked ? 'none' : 'block'">
    </div>
</div>

<!-- Susu Formula -->
<div class="row mb-2">
    <label class="col-sm-12"><strong>Pemberian Susu Formula</strong></label>
</div>

<!-- Alasan -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Alasan pemberian</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="alasan_susu">

        <textarea class="form-control mt-2" id="commentalasansusu" rows="2"
        placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!"
        readonly></textarea>
    </div>
    <div class="col-sm-1">
        <input type="checkbox" class="form-check-input"
            onchange="document.getElementById('commentalasansusu').style.display = this.checked ? 'none' : 'block'">
    </div>
</div>

<!-- Jumlah -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Jumlah pemberian dalam sehari </strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="jumlah_susu">

        <textarea class="form-control mt-2" id="commentjumlahsusu" rows="2"
        placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!"
        readonly></textarea>
    </div>
    <div class="col-sm-1">
        <input type="checkbox" class="form-check-input"
            onchange="document.getElementById('commentjumlahsusu').style.display = this.checked ? 'none' : 'block'">
    </div>
</div>
<!-- 10. RIWAYAT PSIKOSOSIAL -->
<div class="row mb-3">
    <label class="col-sm-12 col-form-label text-primary">
        <strong>10. Riwayat Psikososial</strong>
    </label>
</div>

<!-- Anak tinggal -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Anak tinggal bersama</strong></label>
    
    <div class="col-sm-9">
        <div class="d-flex flex-nowrap align-items-center gap-2 mb-2">
            <span>:</span>

            <input type="text" class="form-control" name="tinggal_bersama" style="min-width:120px;" placeholder="">

            <span class="text-nowrap">di :</span>

            <input type="text" class="form-control" name="tinggal_di" style="min-width:150px;" placeholder="">
        </div>

        <textarea class="form-control mt-2" id="comment_tinggal" rows="2"
        placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!"
        style="display:block; overflow:hidden; resize: none;"
        oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
        readonly></textarea>
    </div>

    <div class="col-sm-1">
        <input type="checkbox" class="form-check-input mt-2"
        onchange="document.getElementById('comment_tinggal').style.display = this.checked ? 'none' : 'block'">
    </div>
</div>

<!-- Lingkungan -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Lingkungan</strong></label>

    <div class="col-sm-9">
        <div class="d-flex flex-wrap align-items-center gap-1 mb-1">
            
            <span class="text-nowrap">Rumah dekat dengan :</span>
            <input type="text" class="form-control" name="rumah_dekat" style="min-width:150px;">

            <span class="text-nowrap">, tempat anak bermain</span>
            <input type="text" class="form-control" name="tempat_bermain" style="min-width:150px;">

            <span class="text-nowrap">kamar klien :</span>
            <input type="text" class="form-control" name="kamar_klien" style="min-width:120px;">
        </div>

        <textarea class="form-control mt-2" id="comment_lingkungan" rows="2"
        placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!"
        style="display:block; overflow:hidden; resize: none;"
        oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
        readonly></textarea>
    </div>

    <div class="col-sm-1">
        <input type="checkbox" class="form-check-input mt-2"
        onchange="document.getElementById('comment_lingkungan').style.display = this.checked ? 'none' : 'block'">
    </div>
</div>
<!-- 11. REAKSI HOSPITALISASI -->
<div class="row mb-3">
    <label class="col-sm-12 col-form-label text-primary">
        <strong>11. Reaksi Hospitalisasi</strong>
    </label>
</div>
<div class="row mb-2">
    <label class="col-sm-12"><strong>a. Pengalaman keluarga tentang sakit dan rawat inap</strong></label>
</div>

<!-- Alasan ke RS -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>- Ibu membawa anaknya ke RS karena</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="alasan_rs">
        <textarea class="form-control mt-2" id="comment_alasan_rs" rows="2"
        placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!"
        style="display:block; overflow:hidden; resize: none;"
        oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
        readonly></textarea>
    </div>
    <div class="col-sm-1">
        <input type="checkbox" class="form-check-input"
        onchange="document.getElementById('comment_alasan_rs').style.display = this.checked ? 'none' : 'block'">
    </div>
</div>

<!-- Penjelasan dokter -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>- Apakah dokter menceritakan tentang kondisi anak</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="penjelasan_dokter">
        <textarea class="form-control mt-2" id="comment_penjelasan" rows="2"
        placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!"
        style="display:block; overflow:hidden; resize: none;"
        oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
        readonly></textarea>
    </div>
    <div class="col-sm-1">
        <input type="checkbox" class="form-check-input"
        onchange="document.getElementById('comment_penjelasan').style.display = this.checked ? 'none' : 'block'">
    </div>
</div>

<!-- Perasaan -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>- Perasaan orang tua saat ini</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="perasaan">
        <textarea class="form-control mt-2" id="comment_perasaan" rows="2"
        placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!"
        style="display:block; overflow:hidden; resize: none;"
        oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
        readonly></textarea>
    </div>
    <div class="col-sm-1">
        <input type="checkbox" class="form-check-input"
        onchange="document.getElementById('comment_perasaan').style.display = this.checked ? 'none' : 'block'">
    </div>
</div>

<!-- Kunjungan -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>- Orang tua selalu berkunjung ke RS</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="kunjungan">
        <textarea class="form-control mt-2" id="comment_kunjungan" rows="2"
        placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!"
        style="display:block; overflow:hidden; resize: none;"
        oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
        readonly></textarea>
    </div>
    <div class="col-sm-1">
        <input type="checkbox" class="form-check-input"
        onchange="document.getElementById('comment_kunjungan').style.display = this.checked ? 'none' : 'block'">
    </div>
</div>

<!-- Pendamping -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>- Yang akan tinggal menemani anak di rumah sakit</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="pendamping">
        <textarea class="form-control mt-2" id="comment_pendamping" rows="2"
        placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!"
        style="display:block; overflow:hidden; resize: none;"
        oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
        readonly></textarea>
    </div>
    <div class="col-sm-1">
        <input type="checkbox" class="form-check-input"
        onchange="document.getElementById('comment_pendamping').style.display = this.checked ? 'none' : 'block'">
    </div>
</div>
<!-- 12. REAKSI ANAK -->
<div class="row mb-3">
    <label class="col-sm-12 col-form-label text-primary">
        <strong>12. Reaksi Anak Selama Dirawat</strong>
    </label>
</div>
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Reaksi Anak</strong></label>
    
    <div class="col-sm-9">
        <textarea class="form-control" rows="3" name="reaksi_anak"></textarea>

        <textarea class="form-control mt-2" id="comment_reaksi_anak" rows="2"
        placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!"
        style="display:block; overflow:hidden; resize: none;"
        oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
        readonly></textarea>
    </div>

    <div class="col-sm-1">
        <input type="checkbox" class="form-check-input"
        onchange="document.getElementById('comment_reaksi_anak').style.display = this.checked ? 'none' : 'block'" >
    </div>
</div>
    <div class="row mb-3">
    <label class="col-sm-12 col-form-label text-primary">
        <strong>13.	Aktivitas sehari-hari</strong>
    </label>
</div>
<div class="row mb-2">
    <label class="col-sm-12 text-primary">
        <strong>Nutrisi</strong>
    </label>
</div>

<div class="row mb-4">
    <div class="col-sm-11">

        <div class="table-responsive">
            <table class="table table-bordered mb-0">
                <thead class="table-light text-center">
                    <tr>
                        <th style="width:40%">Kondisi</th>
                        <th style="width:30%">Sebelum Sakit</th>
                        <th style="width:30%">Saat Sakit</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>1. Selera Makan</strong></td>
                        <td><input type="text" class="form-control" name="selera_sebelum"></td>
                        <td><input type="text" class="form-control" name="selera_saat"></td>
                    </tr>

                    <tr>
                        <td><strong>2. Porsi Makan</strong></td>
                        <td><input type="text" class="form-control" name="porsi_sebelum"></td>
                        <td><input type="text" class="form-control" name="porsi_saat"></td>
                    </tr>

                    <tr>
                        <td><strong>3. Menu Makanan</strong></td>
                        <td><input type="text" class="form-control" name="menu_sebelum"></td>
                        <td><input type="text" class="form-control" name="menu_saat"></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- REVISI -->
        <textarea class="form-control mt-2" id="comment_nutrisi" rows="2"
        placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!"
        readonly></textarea>

    </div>

    <!-- CHECKBOX -->
    <div class="col-sm-1 d-flex align-items-center justify-content-center">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" style="transform: scale(1.5);"
            onchange="document.getElementById('comment_nutrisi').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</div>
<hr>
<div class="row mb-2">
    <label class="col-sm-12 text-primary">
        <strong>Cairan</strong>
    </label>
</div>

<div class="row mb-4">
    <div class="col-sm-11">

        <div class="table-responsive">
            <table class="table table-bordered mb-0">
                <thead class="table-light text-center">
                    <tr>
                        <th style="width:40%">Kondisi</th>
                        <th style="width:30%">Sebelum Sakit</th>
                        <th style="width:30%">Saat Sakit</th>
                    </tr>
                </thead>
                <tbody>

                    <tr>
                        <td><strong>1. Jenis Minuman</strong></td>
                        <td><input type="text" class="form-control" name="jenis_minum_sebelum"></td>
                        <td><input type="text" class="form-control" name="jenis_minum_saat"></td>
                    </tr>

                    <tr>
                        <td><strong>2. Frekuensi Minum</strong></td>
                        <td><input type="text" class="form-control" name="frekuensi_minum_sebelum"></td>
                        <td><input type="text" class="form-control" name="frekuensi_minum_saat"></td>
                    </tr>

                    <tr>
                        <td><strong>3. Kebutuhan Cairan</strong></td>
                        <td><input type="text" class="form-control" name="kebutuhan_cairan_sebelum"></td>
                        <td><input type="text" class="form-control" name="kebutuhan_cairan_saat"></td>
                    </tr>

                    <tr>
                        <td><strong>4. Cara Pemenuhan</strong></td>
                        <td><input type="text" class="form-control" name="cara_cairan_sebelum"></td>
                        <td><input type="text" class="form-control" name="cara_cairan_saat"></td>
                    </tr>

                </tbody>
            </table>
        </div>

        <!-- REVISI -->
        <textarea class="form-control mt-2" id="comment_cairan" rows="2"
        placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!"
        readonly></textarea>

    </div>

    <!-- CHECKBOX -->
    <div class="col-sm-1 d-flex align-items-center justify-content-center">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" style="transform: scale(1.5);"
            onchange="document.getElementById('comment_cairan').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</div><div class="row mb-2">
    <label class="col-sm-12 text-primary">
        <strong>Cairan</strong>
    </label>
</div>

<div class="row mb-4">
    <div class="col-sm-11">

        <div class="table-responsive">
            <table class="table table-bordered mb-0">
                <thead class="table-light text-center">
                    <tr>
                        <th style="width:40%">Kondisi</th>
                        <th style="width:30%">Sebelum Sakit</th>
                        <th style="width:30%">Saat Sakit</th>
                    </tr>
                </thead>
                <tbody>

                    <tr>
                        <td><strong>1. Jenis Minuman</strong></td>
                        <td><input type="text" class="form-control" name="jenis_minum_sebelum"></td>
                        <td><input type="text" class="form-control" name="jenis_minum_saat"></td>
                    </tr>

                    <tr>
                        <td><strong>2. Frekuensi Minum</strong></td>
                        <td><input type="text" class="form-control" name="frekuensi_minum_sebelum"></td>
                        <td><input type="text" class="form-control" name="frekuensi_minum_saat"></td>
                    </tr>

                    <tr>
                        <td><strong>3. Kebutuhan Cairan</strong></td>
                        <td><input type="text" class="form-control" name="kebutuhan_cairan_sebelum"></td>
                        <td><input type="text" class="form-control" name="kebutuhan_cairan_saat"></td>
                    </tr>

                    <tr>
                        <td><strong>4. Cara Pemenuhan</strong></td>
                        <td><input type="text" class="form-control" name="cara_cairan_sebelum"></td>
                        <td><input type="text" class="form-control" name="cara_cairan_saat"></td>
                    </tr>

                </tbody>
            </table>
        </div>

        <!-- REVISI -->
        <textarea class="form-control mt-2" id="comment_cairan" rows="2"
        placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!"
        readonly></textarea>

    </div>

    <!-- CHECKBOX -->
    <div class="col-sm-1 d-flex align-items-center justify-content-center">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" style="transform: scale(1.5);"
            onchange="document.getElementById('comment_cairan').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</div>
<hr>
<div class="row mb-2">
    <label class="col-sm-12 text-primary">
        <strong>Eliminasi (BAK)</strong>
    </label>
</div>

<div class="row mb-4">
    <div class="col-sm-11">

        <div class="table-responsive">
            <table class="table table-bordered mb-0">
                <thead class="table-light text-center">
                    <tr>
                        <th style="width:40%">Kondisi</th>
                        <th style="width:30%">Sebelum Sakit</th>
                        <th style="width:30%">Saat Sakit</th>
                    </tr>
                </thead>
                <tbody>

                    <tr>
                        <td><strong>1. Tempat Pembuangan</strong></td>
                        <td><input type="text" class="form-control" name="bak_tempat_sebelum"></td>
                        <td><input type="text" class="form-control" name="bak_tempat_saat"></td>
                    </tr>

                    <tr>
                        <td><strong>2. Frekuensi (Waktu)</strong></td>
                        <td><input type="text" class="form-control" name="bak_frekuensi_sebelum"></td>
                        <td><input type="text" class="form-control" name="bak_frekuensi_saat"></td>
                    </tr>

                    <tr>
                        <td><strong>3. Karakteristik</strong></td>
                        <td><input type="text" class="form-control" name="bak_karakteristik_sebelum"></td>
                        <td><input type="text" class="form-control" name="bak_karakteristik_saat"></td>
                    </tr>

                </tbody>
            </table>
        </div>

        <!-- REVISI -->
        <textarea class="form-control mt-2" id="comment_bak" rows="2"
        placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!"
        readonly></textarea>

    </div>

    <!-- CHECKBOX -->
    <div class="col-sm-1 d-flex align-items-center justify-content-center">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" style="transform: scale(1.5);"
            onchange="document.getElementById('comment_bak').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</div>

<hr>
<div class="row mb-2">
    <label class="col-sm-12 text-primary">
        <strong>Eliminasi (BAB)</strong>
    </label>
</div>

<div class="row mb-4">
    <div class="col-sm-11">

        <div class="table-responsive">
            <table class="table table-bordered mb-0">
                <thead class="table-light text-center">
                    <tr>
                        <th style="width:40%">Kondisi</th>
                        <th style="width:30%">Sebelum Sakit</th>
                        <th style="width:30%">Saat Sakit</th>
                    </tr>
                </thead>
                <tbody>

                    <tr>
                        <td><strong>1. Tempat Pembuangan</strong></td>
                        <td><input type="text" class="form-control" name="bab_tempat_sebelum"></td>
                        <td><input type="text" class="form-control" name="bab_tempat_saat"></td>
                    </tr>

                    <tr>
                        <td><strong>2. Frekuensi (Waktu)</strong></td>
                        <td><input type="text" class="form-control" name="bab_frekuensi_sebelum"></td>
                        <td><input type="text" class="form-control" name="bab_frekuensi_saat"></td>
                    </tr>

                    <tr>
                        <td><strong>3. Karakteristik</strong></td>
                        <td><input type="text" class="form-control" name="bab_karakteristik_sebelum"></td>
                        <td><input type="text" class="form-control" name="bab_karakteristik_saat"></td>
                    </tr>

                </tbody>
            </table>
        </div>

        <!-- REVISI -->
        <textarea id="comment_bab" class="form-control mt-2" rows="2"
        placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!"
        readonly></textarea>

    </div>

    <!-- CHECKBOX -->
    <div class="col-sm-1 d-flex align-items-center justify-content-center">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" style="transform: scale(1.5);"
            onchange="document.getElementById('comment_bab').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</div>


<hr>
<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>Istirahat Tidur</strong></label>
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

                    <tr>
                        <td>4</td>
                        <td><strong>Pola Tidur</strong></td>
                        <td><input type="text" class="form-control" name="pola_tidur_sebelum"></td>
                        <td><input type="text" class="form-control" name="pola_tidur_sekarang"></td>
                    </tr>

                </tbody>
            </table>
        </div>

        <!-- REVISI -->
        <textarea id="revisi_tidur" class="form-control mt-2" rows="2"
        placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!"
        readonly></textarea>

    </div>

    <!-- CHECKBOX -->
   <div class="col-sm-1 d-flex align-items-center justify-content-center">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" style="transform: scale(1.5);"
            onchange="document.getElementById('revisi_tidur').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</div>



<hr>
<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>Pola Personal Hygiene</strong></label>
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

        <!-- REVISI -->
        <textarea id="comment_hygiene" class="form-control mt-2" rows="2"
        placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!"
        readonly></textarea>
    </div>

    <!-- CHECKBOX -->
    <div class="col-sm-1 d-flex align-items-center justify-content-center">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" style="transform: scale(1.5);"
            onchange="document.getElementById('comment_hygiene').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</div>
<!-- 14. PEMERIKSAAN FISIK -->
<div class="row mb-3">
    <label class="col-sm-12 col-form-label text-primary">
        <strong>14. Pemeriksaan Fisik</strong>
    </label>
</div>

<!-- Keadaan Umum -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Keadaan Umum</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="keadaan_umum">
        <textarea id="comment_keadaan_umum" class="form-control" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1 text-center">
        <input type="checkbox" class="form-check-input mt-2"
        onchange="document.getElementById('comment_keadaan_umum').style.display = this.checked ? 'none' : 'block'">
    </div>
</div>

<!-- Kesadaran -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Kesadaran</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="kesadaran">
        <textarea id="comment_kesadaran" class="form-control" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1 text-center">
        <input type="checkbox" class="form-check-input mt-2"
        onchange="document.getElementById('comment_kesadaran').style.display = this.checked ? 'none' : 'block'">
    </div>
</div>

<!-- Tanda Vital -->
<div class="row mb-2">
    <label class="col-sm-12"><strong>Tanda – Tanda Vital</strong></label>
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

<!-- Berat Badan -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Berat Badan</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="bb">
        <textarea id="comment_bb" class="form-control mt-2" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1 d-flex align-items-start">
        <input type="checkbox" class="form-check-input"
        onchange="document.getElementById('comment_bb').style.display = this.checked ? 'none' : 'block'">
    </div>
</div>

<!-- Tinggi Badan -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Tinggi Badan</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="tb">
        <textarea id="comment_tb" class="form-control mt-2" rows="2"  placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1 d-flex align-items-start">
        <input type="checkbox" class="form-check-input"
        onchange="document.getElementById('comment_tb').style.display = this.checked ? 'none' : 'block'">
    </div>
</div>
<!-- KEPALA -->
<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>Kepala</strong></label>
</div>

<div class="row mb-2">
    <label class="col-sm-12"><strong>Inspeksi</strong></label>
</div>

<!-- Rambut & Hygiene -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Keadaan Rambut & Hygiene</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="rambut">
       <!-- comment -->
                            <textarea class="form-control mt-2" id="commentsuhu" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                                </div>
    </div>
</div>

<!-- Warna -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Warna Rambut</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="warna_rambut">
        <!-- comment -->
                            <textarea class="form-control mt-2" id="commentsuhu" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                                </div>
    </div>
</div>

<!-- Penyebaran -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Penyebaran</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="penyebaran">
      <!-- comment -->
                            <textarea class="form-control mt-2" id="commentsuhu" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                                </div>
    </div>
</div>

<!-- Mudah Rontok -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Mudah Rontok</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="rontok">
      <!-- comment -->
                            <textarea class="form-control mt-2" id="commentsuhu" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                                </div>
    </div>
</div>
<!-- Kebersihan Rambut -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Kebersihan Rambut</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="rontok">
      <!-- comment -->
                            <textarea class="form-control mt-2" id="commentsuhu" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                                </div>
    </div>
</div>
<!-- Benjolan -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label">
        <strong>- Benjolan</strong>
    </label>

    <div class="col-sm-9 d-flex align-items-center gap-3 flex-wrap">
        <strong>:</strong>

        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="benjolan" value="ada">
            <label class="form-check-label"><strong>Ada</strong></label>
        </div>

        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="benjolan" value="tidak">
            <label class="form-check-label"><strong>Tidak ada</strong></label>
        </div>

        <strong>:</strong>

        <input type="text" class="form-control" style="max-width:200px;">

       <!-- comment -->
                            <textarea class="form-control mt-2" id="commentsuhu" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                                </div>
    </div>
</div>

<!-- Nyeri Tekan -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label">
        <strong>- Nyeri tekan</strong>
    </label>

    <div class="col-sm-9 d-flex align-items-center gap-3 flex-wrap">
        <strong>:</strong>

        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="nyeri_tekan" value="ada">
            <label class="form-check-label"><strong>Ada</strong></label>
        </div>

        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="nyeri_tekan" value="tidak">
            <label class="form-check-label"><strong>Tidak ada</strong></label>
        </div>

        <strong>:</strong>

        <input type="text" class="form-control" style="max-width:200px;">
 <!-- comment -->
                            <textarea class="form-control mt-2" id="commentsuhu" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                                </div>
    </div>
</div>

<!-- Tekstur Rambut -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label">
        <strong>- Tekstur rambut</strong>
    </label>

    <div class="col-sm-9 d-flex align-items-center gap-3 flex-wrap">
        <strong>:</strong>

        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="tekstur_rambut" value="kasar">
            <label class="form-check-label"><strong>Kasar</strong></label>
        </div>

        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="tekstur_rambut" value="halus">
            <label class="form-check-label"><strong>Halus</strong></label>
        </div>

        <strong>:</strong>

        <input type="text" class="form-control" style="max-width:200px;">

        <!-- comment -->
                            <textarea class="form-control mt-2" id="commentsuhu" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                                </div>
    </div>
</div>
<!-- Wajah -->
<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>Wajah</strong></label>
</div>

<div class="row mb-2">
    <label class="col-sm-12"><strong>Inspeksi</strong></label>
</div>

<!-- Simetris -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label">
        <strong>- Simetris</strong>
    </label>

    <div class="col-sm-9 d-flex align-items-center gap-3 flex-wrap">
        <strong>:</strong>

        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="simetris" value="ya">
            <label class="form-check-label"><strong>Simetris</strong></label>
        </div>

        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="simetris" value="tidak">
            <label class="form-check-label"><strong>Tidak</strong></label>
        </div>

        <strong>:</strong>
        <input type="text" class="form-control" style="max-width:200px;">

         <!-- comment -->
                            <textarea class="form-control mt-2" id="commentsuhu" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                                </div>
    </div>
</div>

<!-- Bentuk Wajah -->
 <div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>- Bentuk wajah</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="ruangan">
       <!-- comment -->
                            <textarea class="form-control mt-2" id="commentsuhu" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                                </div>
    </div>
</div>



<!-- Palpasi -->
<div class="row mb-2">
    <label class="col-sm-12"><strong>Palpasi</strong></label>
</div>

<!-- Nyeri Tekan -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label">
        <strong>- Nyeri tekan</strong>
    </label>

    <div class="col-sm-9 d-flex align-items-center gap-3 flex-wrap">
        <strong>:</strong>

        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="nyeri_wajah" value="ya">
            <label class="form-check-label"><strong>Ada</strong></label>
        </div>

        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="nyeri_wajah" value="tidak">
            <label class="form-check-label"><strong>Tidak</strong></label>
        </div>

        <strong>:</strong>
        <input type="text" class="form-control" style="max-width:200px;">

        <!-- comment -->
                            <textarea class="form-control mt-2" id="commentsuhu" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                                </div>
    </div>
</div>

<!-- Data Lain -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label">
        <strong>Data lain</strong>
    </label>

    <div class="col-sm-9">
        <textarea class="form-control mb-2" rows="2"></textarea>
        <!-- comment -->
                            <textarea class="form-control mt-2" id="commentsuhu" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                                </div>
    </div>
<!-- MATA -->
<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>Mata</strong></label>
</div>

<div class="row mb-2">
    <label class="col-sm-12"><strong>Inspeksi</strong></label>
</div>

<!-- Palpebra -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Palpebra</strong></label>
    <div class="col-sm-9">
        <div class="mb-2">
            Edema:
            <input type="radio" name="edema" value="ya"> Ya
            <input type="radio" name="edema" value="tidak"> Tidak
        </div>
        <div class="mb-2">
            Radang:
            <input type="radio" name="radang_palpebra" value="ya"> Ya
            <input type="radio" name="radang_palpebra" value="tidak"> Tidak
        </div>
        <textarea class="form-control" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
    </div>
    <div class="col-sm-1 text-center"><input type="checkbox" class="form-check-input mt-2"></div>
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

<!-- Refleks Cahaya -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-5 col-form-label"><strong>Refleks pupil terhadap cahaya</strong></label>
</div>

<!-- Posisi Mata -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Posisi Mata</strong></label>
    <div class="col-sm-9">
        <input type="radio" name="posisi_mata" value="simetris"> Simetris
        <input type="radio" name="posisi_mata" value="tidak"> Tidak
        <input type="text" class="form-control mt-2" placeholder="Keterangan tambahan">
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

<!-- Penglihatan -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Penglihatan</strong></label>
    <div class="col-sm-9">
        <div class="mb-2">
           
            <input type="radio" name="kabur" value="kabur">Kabur
            <input type="radio" name="kabur" value="tidak"> Tidak
        </div>
        <div class="mb-2">
            
            <input type="radio" name="diplopia" value="diplopia"> Diplopia
            <input type="radio" name="diplopia" value="tidak"> Tidak
        </div>
        <textarea class="form-control" rows="2"   placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea></textarea>
    </div>
    <div class="col-sm-1 text-center"><input type="checkbox" class="form-check-input mt-2"></div>
</div>

<!-- Data Lain -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Data Lain</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="data_mata">
        <textarea class="form-control" rows="2"   placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea></textarea>
    </div>
    <div class="col-sm-1 text-center"><input type="checkbox" class="form-check-input mt-2"></div>
</div>

<!-- HIDUNG & SINUS -->
<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>Hidung & Sinus</strong></label>
</div>

<div class="row mb-2">
    <label class="col-sm-12"><strong>Inspeksi</strong></label>
</div>

<!-- Bentuk -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Bentuk Hidung</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="bentuk_hidung">
        <textarea class="form-control" rows="2"   placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea></textarea>
    </div>
    <div class="col-sm-1 text-center"><input type="checkbox" class="form-check-input mt-2"></div>
</div>

<!-- Septum -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Septum</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="septum">
        <textarea class="form-control" rows="2"  placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea></textarea>
    </div>
    <div class="col-sm-1 text-center"><input type="checkbox" class="form-check-input mt-2"></div>
</div>

<!-- Secret -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Secret / Cairan</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="secret">
        <textarea class="form-control" rows="2"   placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea></textarea>
    </div>
    <div class="col-sm-1 text-center"><input type="checkbox" class="form-check-input mt-2"></div>
</div>

<!-- Data Lain -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Data Lain</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="data_hidung">
        <textarea class="form-control" rows="2"  placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea></textarea>
    </div>
    <div class="col-sm-1 text-center"><input type="checkbox" class="form-check-input mt-2"></div>
</div>
<!-- TELINGA -->
<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>Telinga</strong></label>
</div>

<div class="row mb-2">
    <label class="col-sm-12"><strong>Inspeksi</strong></label>
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

<!-- MULUT -->
<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>Mulut</strong></label>
</div>

<div class="row mb-2">
    <label class="col-sm-12"><strong>Inspeksi</strong></label>
</div>

<!-- Gigi - Keadaan -->
 <div class="row mb-2">
    <label class="col-sm-12"><strong>Gigi</strong></label>
</div>
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Keadaan Gigi</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="keadaan_gigi">
        <textarea class="form-control" rows="2"   placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea></textarea>
    </div>
    <div class="col-sm-1 text-center">
        <input type="checkbox" class="form-check-input mt-2">
    </div>
</div>

<!-- Karang / Karies -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Karang Gigi / Karies</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="karies">
        <textarea class="form-control" rows="2"   placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea></textarea>
    </div>
    <div class="col-sm-1 text-center">
        <input type="checkbox" class="form-check-input mt-2">
    </div>
</div>

<!-- Gusi -->
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
        <strong>Bibir (Warna)</strong>
    </label>

    <div class="col-sm-9 d-flex align-items-center gap-3 flex-wrap">

        <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="bibir_warna" value="cianosis">
            <label class="form-check-label"><strong>Cianosis</strong></label>
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
        <strong> Bibir (Kondisi)</strong>
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
            <input class="form-check-input" type="radio" name="bau_mulut" value="ya">
            <label class="form-check-label"><strong>Ya</strong></label>
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

<!-- Bicara -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Kemampuan Bicara</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="bicara">
        <textarea class="form-control" rows="2"   placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea></textarea>
    </div>
    <div class="col-sm-1 text-center">
        <input type="checkbox" class="form-check-input mt-2">
    </div>
</div>

<!-- Data Lain -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Data Lain</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="data_mulut">
        <textarea class="form-control" rows="2"   placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea></textarea>
    </div>
    <div class="col-sm-1 text-center">
        <input type="checkbox" class="form-check-input mt-2">
    </div>
</div>

<!-- TENGGOROKAN -->
<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>Tenggorokan</strong></label>
</div>

<!-- Warna Mukosa -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Warna Mukosa</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="mukosa">
        <textarea class="form-control" rows="2"   placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea></textarea>
    </div>
    <div class="col-sm-1 text-center">
        <input type="checkbox" class="form-check-input mt-2">
    </div>
</div>

<!-- Nyeri Tekan -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Nyeri Tekan</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="nyeri_tenggorokan">
        <textarea class="form-control" rows="2"   placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea></textarea>
    </div>
    <div class="col-sm-1 text-center">
        <input type="checkbox" class="form-check-input mt-2">
    </div>
</div>

<!-- Nyeri Menelan -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Nyeri Menelan</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="menelan">
        <textarea class="form-control" rows="2"   placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea></textarea>
    </div>
    <div class="col-sm-1 text-center">
        <input type="checkbox" class="form-check-input mt-2">
    </div>
</div>
<!-- LEHER -->
<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>Leher</strong></label>
</div>

<div class="row mb-2">
    <label class="col-sm-12"><strong>Palpasi</strong></label>
</div>

<!-- Kelenjar Limfe -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Kelenjar Limfe</strong></label>
    <div class="col-sm-9">
        <input type="radio" name="limfe" value="membesar"> Membesar
        <input type="radio" name="limfe" value="tidak"> Tidak
        <textarea class="form-control mt-2" rows="2"   placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea></textarea>
    </div>
    <div class="col-sm-1 text-center">
        <input type="checkbox" class="form-check-input mt-2">
    </div>
</div>

<!-- Data Lain -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Data Lain</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="data_leher">
        <textarea class="form-control" rows="2"   placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea></textarea>
    </div>
    <div class="col-sm-1 text-center">
        <input type="checkbox" class="form-check-input mt-2">
    </div>
</div>

<!-- THORAX -->
<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>Thorax dan Pernapasan</strong></label>
</div>

<!-- Bentuk Dada -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Bentuk Dada</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="bentuk_dada">
        <textarea class="form-control" rows="2"   placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea></textarea>
    </div>
    <div class="col-sm-1 text-center">
        <input type="checkbox" class="form-check-input mt-2">
    </div>
</div>

<!-- Irama Pernapasan -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Irama Pernapasan</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="irama_nafas">
        <textarea class="form-control" rows="2"   placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea></textarea>
    </div>
    <div class="col-sm-1 text-center">
        <input type="checkbox" class="form-check-input mt-2">
    </div>
</div>

<!-- Pengembangan -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Pengembangan di waktu bernapas</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="pengembangan">
        <textarea class="form-control" rows="2"   placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea></textarea>
    </div>
    <div class="col-sm-1 text-center">
        <input type="checkbox" class="form-check-input mt-2">
    </div>
</div>

<!-- Tipe Pernapasan -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Tipe Pernapasan</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="tipe_nafas">
        <textarea class="form-control" rows="2"   placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea></textarea>
    </div>
    <div class="col-sm-1 text-center">
        <input type="checkbox" class="form-check-input mt-2">
    </div>
</div>

<!-- AUSKULTASI -->
<div class="row mb-2">
    <label class="col-sm-12"><strong>Auskultasi</strong></label>
</div>

<!-- Suara Nafas -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Suara Nafas</strong></label>
    <div class="col-sm-9">
        <input type="radio" name="suara_nafas" value="vesikuler"> Vesikuler
        <input type="radio" name="suara_nafas" value="bronchial"> Bronchial
        <input type="radio" name="suara_nafas" value="bronchovesikuler"> Bronchovesikuler
        <textarea class="form-control mt-2" rows="2"   placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea></textarea>
    </div>
    <div class="col-sm-1 text-center">
        <input type="checkbox" class="form-check-input mt-2">
    </div>
</div>

<!-- Suara Tambahan -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Suara Tambahan</strong></label>
    <div class="col-sm-9">
        <input type="radio" name="suara_tambahan" value="ronchi"> Ronchi
        <input type="radio" name="suara_tambahan" value="wheezing"> Wheezing
        <input type="radio" name="suara_tambahan" value="rales"> Rales
        <textarea class="form-control mt-2" rows="2"   placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea></textarea>
    </div>
    <div class="col-sm-1 text-center">
        <input type="checkbox" class="form-check-input mt-2">
    </div>
</div>

<!-- PERKUSI -->
<div class="row mb-2">
    <label class="col-sm-12"><strong>Perkusi</strong></label>
</div>

<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong></strong></label>
    <div class="col-sm-9">
        <input type="radio" name="perkusi" value="redup"> Redup
        <input type="radio" name="perkusi" value="pekak"> Pekak
        <input type="radio" name="perkusi" value="hypersonor"> Hypersonor
        <input type="radio" name="perkusi" value="tympani"> Tympani
        <textarea class="form-control mt-2" rows="2"   placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea></textarea>
    </div>
    <div class="col-sm-1 text-center">
        <input type="checkbox" class="form-check-input mt-2">
    </div>
</div>

<!-- JANTUNG -->
<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>Jantung</strong></label>
</div>
<div class="row mb-2">
    <label class="col-sm-12"><strong>Palpasi</strong></label>
</div>

<!-- Ictus Cordis -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Ictus Cordis</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="ictus">
        <textarea class="form-control" rows="2"   placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea></textarea>
    </div>
    <div class="col-sm-1 text-center"><input type="checkbox" class="form-check-input mt-2"></div>
</div>
<div class="row mb-2">
    <label class="col-sm-12"><strong>Perkusi</strong></label>
</div>
<!-- Pembesaran -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Pembesaran jantung</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="pembesaran">
        <textarea class="form-control" rows="2"   placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea></textarea>
    </div>
    <div class="col-sm-1 text-center"><input type="checkbox" class="form-check-input mt-2"></div>
</div>

<!-- BJ -->
 <div class="row mb-2">
    <label class="col-sm-12"><strong>Auskultasi</strong></label>
</div>
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>BJ I</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="bj1">
        <textarea class="form-control" rows="2"   placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea></textarea>
    </div>
    <div class="col-sm-1 text-center"><input type="checkbox" class="form-check-input mt-2"></div>
</div>

<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>BJ II</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="bj2">
        <textarea class="form-control" rows="2"   placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea></textarea>
    </div>
    <div class="col-sm-1 text-center"><input type="checkbox" class="form-check-input mt-2"></div>
</div>

<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>BJ III</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="bj3">
        <textarea class="form-control" rows="2"   placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea></textarea>
    </div>
    <div class="col-sm-1 text-center"><input type="checkbox" class="form-check-input mt-2"></div>
</div>

<!-- Bunyi Tambahan -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Bunyi Tambahan</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="bunyi_tambahan">
        <textarea class="form-control" rows="2"   placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea></textarea>
    </div>
    <div class="col-sm-1 text-center"><input type="checkbox" class="form-check-input mt-2"></div>
</div>

<!-- Data Lain -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Data Lain</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="data_jantung">
        <textarea class="form-control" rows="2"   placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea></textarea>
    </div>
    <div class="col-sm-1 text-center"><input type="checkbox" class="form-check-input mt-2"></div>
</div>
<!-- ABDOMEN -->
<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>Abdomen</strong></label>
</div>

<div class="row mb-2">
    <label class="col-sm-12"><strong>Inspeksi</strong></label>
</div>

<!-- Membuncit -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Membuncit</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="membuncit">
        <textarea class="form-control" rows="2"   placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea></textarea>
    </div>
    <div class="col-sm-1 text-center">
        <input type="checkbox" class="form-check-input mt-2">
    </div>
</div>

<!-- Luka -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label">
        <strong>- Ada luka</strong>
    </label>

    <div class="col-sm-9 d-flex align-items-center gap-3 flex-wrap">

        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="luka_abdomen" value="ada">
            <label class="form-check-label">Ada</label>
        </div>


        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="luka_abdomen" value="tidak">
            <label class="form-check-label">Tidak</label>
        </div>

        <input type="text" class="form-control" style="max-width:200px;">

        <textarea class="form-control mt-2" rows="2"   placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea></textarea>
    </div>

    <div class="col-sm-1 text-center">
        <input type="checkbox" class="form-check-input mt-2">
    </div>
</div>

<!-- AUSKULTASI -->
<div class="row mb-2">
    <label class="col-sm-12"><strong>Auskultasi</strong></label>
</div>

<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Peristaltik</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="peristaltik">
        <textarea class="form-control" rows="2"   placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea></textarea>
    </div>
    <div class="col-sm-1 text-center">
        <input type="checkbox" class="form-check-input mt-2">
    </div>
</div>

<!-- PALPASI -->
<div class="row mb-2">
    <label class="col-sm-12"><strong>Palpasi</strong></label>
</div>

<!-- Hepar -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Hepar</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="hepar">
        <textarea class="form-control" rows="2"   placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea></textarea>
    </div>
    <div class="col-sm-1 text-center">
        <input type="checkbox" class="form-check-input mt-2">
    </div>
</div>

<!-- Lien -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Lien</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="lien">
        <textarea class="form-control" rows="2"   placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea></textarea>
    </div>
    <div class="col-sm-1 text-center">
        <input type="checkbox" class="form-check-input mt-2">
    </div>
</div>

<!-- Nyeri Tekan -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Nyeri Tekan</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="nyeri">
        <textarea class="form-control" rows="2"   placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea></textarea>
    </div>
    <div class="col-sm-1 text-center">
        <input type="checkbox" class="form-check-input mt-2">
    </div>
</div>

<!-- PERKUSI -->
<div class="row mb-2">
    <label class="col-sm-12"><strong>Perkusi</strong></label>
</div>

<!-- Tympani -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Tympani</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="tympani">
        <textarea class="form-control" rows="2"   placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea></textarea>
    </div>
    <div class="col-sm-1 text-center">
        <input type="checkbox" class="form-check-input mt-2">
    </div>
</div>

<!-- Redup -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Redup</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="redup">
        <textarea class="form-control" rows="2"   placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea></textarea>
    </div>
    <div class="col-sm-1 text-center">
        <input type="checkbox" class="form-check-input mt-2">
    </div>
</div>

<!-- Data Lain -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Data Lain</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="data_abdomen">
        <textarea class="form-control" rows="2"   placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea></textarea>
    </div>
    <div class="col-sm-1 text-center">
        <input type="checkbox" class="form-check-input mt-2">
    </div>
</div>

<!-- GENITALIA -->
<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>Genitalia</strong></label>
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
    <label class="col-sm-2 col-form-label"><strong>Hidrokel</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="hidrokel_pria">
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
    <label class="col-sm-2 col-form-label"><strong>Hidrokel</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="hidrokel_wanita">
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
        <input type="radio" name="anus_paten" value="ya"> Ya
        <input type="radio" name="anus_paten" value="tidak"> Tidak
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

<!-- REFLEKS -->
<div class="row mb-2">
    <label class="col-sm-12 "><strong>Tanda Perangsangan Selaput Otak</strong></label>
</div>

<!-- List Refleks -->
<!-- Gunakan pola yang sama -->
<!-- Kaku Kuduk -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Kaku kuduk</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="kaku_kuduk">
        <textarea class="form-control mt-2" rows="2"   placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
    </div>
    <div class="col-sm-1 text-center">
        <input type="checkbox" class="form-check-input mt-2">
    </div>
</div>

<!-- Kernig -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Kernig Sign</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="kernig">
        <textarea class="form-control mt-2" rows="2"   placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
    </div>
    <div class="col-sm-1 text-center">
        <input type="checkbox" class="form-check-input mt-2">
    </div>
</div>

<!-- Brudzinski -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Refleks Brudzinski</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="brudzinski">
        <textarea class="form-control mt-2" rows="2"   placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
    </div>
    <div class="col-sm-1 text-center">
        <input type="checkbox" class="form-check-input mt-2">
    </div>
</div>

<!-- Refleks Bayi -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Refleks pada bayi</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="refleks_bayi">
        <textarea class="form-control mt-2" rows="2"   placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
    </div>
    <div class="col-sm-1 text-center">
        <input type="checkbox" class="form-check-input mt-2">
    </div>
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

<!-- Peres -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Refleks peres</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="peres">
        <textarea class="form-control mt-2" rows="2"   placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
    </div>
    <div class="col-sm-1 text-center">
        <input type="checkbox" class="form-check-input mt-2">
    </div>
</div>

<!-- Kremaster -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Refleks kremaster</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="kremaster">
        <textarea class="form-control mt-2" rows="2"   placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
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
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>a>
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
<!-- 15. PERKEMBANGAN -->
<div class="row mb-2">
    <label class="col-sm-12 text-primary">
        <strong>15. Pemeriksaan Tingkat Perkembangan (0 – 6 Tahun)</strong>
    </label>
    <label class="col-sm-12"><em>Dengan menggunakan DDST</em></label>
</div>

<!-- Motorik Kasar -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Motorik Kasar</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="motorik_kasar">
        <textarea class="form-control" rows="2"   placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
    </div>
    <div class="col-sm-1 text-center">
        <input type="checkbox" class="form-check-input mt-2">
    </div>
</div>

<!-- Motorik Halus -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Motorik Halus</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="motorik_halus">
        <textarea class="form-control" rows="2"   placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
    </div>
    <div class="col-sm-1 text-center">
        <input type="checkbox" class="form-check-input mt-2">
    </div>
</div>

<!-- Bahasa -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Bahasa</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="bahasa">
        <textarea class="form-control" rows="2"   placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
    </div>
    <div class="col-sm-1 text-center">
        <input type="checkbox" class="form-check-input mt-2">
    </div>
</div>

<!-- Personal Social -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Personal Social</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="personal_social">
        <textarea class="form-control" rows="2"   placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
    </div>
    <div class="col-sm-1 text-center">
        <input type="checkbox" class="form-check-input mt-2">
    </div>
</div>

<!-- 16. TEST DIAGNOSTIK -->
<div class="row mb-2">
    <label class="col-sm-12 text-primary">
        <strong>16. Test Diagnostik</strong>
    </label>
</div>

<div class="row mb-3 align-items-start">
    <div class="col-sm-11">
        <textarea class="form-control" rows="3" name="diagnostik"></textarea>
        <textarea class="form-control mt-2" rows="2"   placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
    </div>
    <div class="col-sm-1 text-center">
        <input type="checkbox" class="form-check-input mt-2">
    </div>
</div>

<!-- 17. LABORATORIUM -->
<div class="row mb-2">
    <label class="col-sm-12 text-primary">
        <strong>17. Laboratorium</strong>
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
</class>
</form>
                    </div>
                    </div>
       
 
    
 <div class="row mb-2">
                        <div class="card">
            <div class="card-body">
            
                <!-- General Form Elements -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

                <h5 class="card-title"><strong>18. Klasifikasi Data</strong></h5>

                    

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

                    <h5 class="card-title"><strong>19. Analisa Data</strong></h5>
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
                         
                        
                    <?php include "tab_navigasi.php"; ?>




</section>              
</main>
                
                 
