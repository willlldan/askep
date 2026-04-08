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

    <div class="pagetitle">
        <h1><strong>Format Resume Keperawatan Anak di Puskesmas</strong></h1>
    </div><!-- End Page Title -->
    <br>
<ul class="nav nav-tabs custom-tabs">

<li class="nav-item">
    <a class="nav-link <?= ($_GET['tab'] ?? 'resume_keperawatan') == 'resume_keperawatan' ? 'active' : '' ?>"
    href="index.php?page=anak/format_resume&tab=resume_keperawatan">
Format Resume Keperawatan Poli Anak    </a>
</li>
<li class="nav-item">
    <a class="nav-link <?= ($_GET['tab'] ?? 'lp_imunisasi') == 'lp_imunisasi' ? 'active' : '' ?>"
    href="index.php?page=anak/format_resume&tab=lp_imunisasi">
Format Laporan Pendahuluan Imunisasi   </a>
</li>
<li class="nav-item">
    <a class="nav-link <?= ($_GET['tab'] ?? 'poli_imunisasi') == 'poli_imunisasi' ? 'active' : '' ?>"
    href="index.php?page=anak/format_resume&tab=poli_imunisasi">
Format Laporan  Poli Imunisasi   </a>
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

            <h5 class="card-title"><strong>FORMAT RESUME KEPERAWATAN POLI ANAK</strong></h5>

            <!-- 1. Biodata Klien -->
         
        <form class="needs-validation" novalidate action="" method="POST">

           <!-- 1. Biodata Klien -->
<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>1. Biodata Klien</strong></label>
</div>

<!-- Nama Anak -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Nama Anak :</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="nama_anak">
        <textarea class="form-control mt-2" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1 d-flex align-items-start">
        <div class="form-check"><input class="form-check-input" type="checkbox"></div>
    </div>
</div>

<!-- Jenis Kelamin -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Jenis Kelamin :</strong></label>
    <div class="col-sm-9">
        <select class="form-select" name="jenis_kelamin">
            <option value="">Pilih</option>
            <option value="Laki-laki">Laki-laki</option>
            <option value="Perempuan">Perempuan</option>
        </select>
        <textarea class="form-control mt-2" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1 d-flex align-items-start">
        <div class="form-check"><input class="form-check-input" type="checkbox"></div>
    </div>
</div>

<!-- Umur -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Umur :</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="umur">
        <textarea class="form-control mt-2" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1 d-flex align-items-start">
        <div class="form-check"><input class="form-check-input" type="checkbox"></div>
    </div>
</div>

<!-- Agama -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Agama :</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="agama">
        <textarea class="form-control mt-2" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1 d-flex align-items-start">
        <div class="form-check"><input class="form-check-input" type="checkbox"></div>
    </div>
</div>

<!-- Alamat -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Alamat :</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="alamat">
        <textarea class="form-control mt-2" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1 d-flex align-items-start">
        <div class="form-check"><input class="form-check-input" type="checkbox"></div>
    </div>
</div>



<!-- 2. Biodata Orangtua -->
<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>2. Biodata Orangtua</strong></label>
</div>

<!-- Nama Ayah -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Nama Ayah :</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="nama_ayah">
        <textarea class="form-control mt-2" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1 d-flex align-items-start"><div class="form-check"><input class="form-check-input" type="checkbox"></div></div>
</div>

<!-- Umur Ayah -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Umur Ayah :</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="umur_ayah">
        <textarea class="form-control mt-2" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1 d-flex align-items-start"><div class="form-check"><input class="form-check-input" type="checkbox"></div></div>
</div>

<!-- Pendidikan Ayah -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Pendidikan Ayah :</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="pendidikan_ayah">
        <textarea class="form-control mt-2" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1 d-flex align-items-start"><div class="form-check"><input class="form-check-input" type="checkbox"></div></div>
</div>

<!-- Pekerjaan Ayah -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Pekerjaan Ayah :</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="pekerjaan_ayah">
        <textarea class="form-control mt-2" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1 d-flex align-items-start"><div class="form-check"><input class="form-check-input" type="checkbox"></div></div>
</div>

<!-- Nama Ibu -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Nama Ibu :</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="nama_ibu">
        <textarea class="form-control mt-2" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1 d-flex align-items-start"><div class="form-check"><input class="form-check-input" type="checkbox"></div></div>
</div>

<!-- Umur Ibu -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Umur Ibu :</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="umur_ibu">
        <textarea class="form-control mt-2" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1 d-flex align-items-start"><div class="form-check"><input class="form-check-input" type="checkbox"></div></div>
</div>

<!-- Pendidikan Ibu -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Pendidikan Ibu :</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="pendidikan_ibu">
        <textarea class="form-control mt-2" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1 d-flex align-items-start"><div class="form-check"><input class="form-check-input" type="checkbox"></div></div>
</div>

<!-- Pekerjaan Ibu -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Pekerjaan Ibu :</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="pekerjaan_ibu">
        <textarea class="form-control mt-2" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1 d-flex align-items-start"><div class="form-check"><input class="form-check-input" type="checkbox"></div></div>
</div>
<!-- B. Pemberian Imunisasi -->
<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>B. Pemberian Imunisasi</strong></label>
</div>

<!-- Imunisasi saat ini -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Imunisasi saat ini :</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="imunisasi_saat_ini">
        <textarea class="form-control mt-2" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1 d-flex align-items-start">
        <div class="form-check"><input class="form-check-input" type="checkbox"></div>
    </div>
</div>

<!-- Dosis -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Dosis pemberian :</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="dosis_pemberian">
        <textarea class="form-control mt-2" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1 d-flex align-items-start">
        <div class="form-check"><input class="form-check-input" type="checkbox"></div>
    </div>
</div>

<!-- Cara -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Cara pemberian :</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="cara_pemberian">
        <textarea class="form-control mt-2" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1 d-flex align-items-start">
        <div class="form-check"><input class="form-check-input" type="checkbox"></div>
    </div>
</div>

<!-- Reaksi anak -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Reaksi anak :</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="reaksi_anak">
        <textarea class="form-control mt-2" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1 d-flex align-items-start">
        <div class="form-check"><input class="form-check-input" type="checkbox"></div>
    </div>
</div>

<!-- Rencana berikut -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Rencana imunisasi pada kunjungan berikutnya	: </strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="rencana_imunisasi">
        <textarea class="form-control mt-2" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1 d-flex align-items-start">
        <div class="form-check"><input class="form-check-input" type="checkbox"></div>
    </div>
</div>

<!-- Riwayat imunisasi -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Imunisasi yang sudah didapatkan :</strong></label>
    <div class="col-sm-9">
        <textarea class="form-control" name="riwayat_imunisasi" rows="2"></textarea>
        <textarea class="form-control mt-2" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1 d-flex align-items-start">
        <div class="form-check"><input class="form-check-input" type="checkbox"></div>
    </div>
</div>

<!-- Efek di rumah -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Efek yang dirasakan anak di rumah setelah pemberian imunisasi  :</strong></label>
    <div class="col-sm-9">
        <textarea class="form-control" name="efek_imunisasi" rows="3"></textarea>
        <textarea class="form-control mt-2" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1 d-flex align-items-start">
        <div class="form-check"><input class="form-check-input" type="checkbox"></div>
    </div>
</div>

<!-- Keluhan orang tua -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Hal yang dikeluhkan orang tua setelah pemberian Imunisasi :</strong></label>
    <div class="col-sm-9">
        <textarea class="form-control" name="keluhan_orangtua" rows="2"></textarea>
        <textarea class="form-control mt-2" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1 d-flex align-items-start">
        <div class="form-check"><input class="form-check-input" type="checkbox"></div>
    </div>
</div>

<!-- Riwayat penyakit -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Riwayat Penyakit / pengobatan  yang pernah didapatkan :</strong></label>
    <div class="col-sm-9">
        <textarea class="form-control" name="riwayat_penyakit" rows="3"></textarea>
        <textarea class="form-control mt-2" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1 d-flex align-items-start">
        <div class="form-check"><input class="form-check-input" type="checkbox"></div>
    </div>
</div>
</div>
</div>

 <div class="row mb-2">
                        <div class="card">
            <div class="card-body">
            
                <!-- General Form Elements -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

                <h5 class="card-title"><strong>C. Klasifikasi Data</strong></h5>

                    

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

                    <h5 class="card-title"><strong>D Analisa Data</strong></h5>
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
                            <div class="card">
             <div class="card-body">
                                
                     <!-- General Form Elements -->
                    <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

                    <h5 class="card-title"><strong>E.Diagnosa Keperawatan Prioritas
                         <h5 class="card-title mb-1"><strong></strong></h5>

                <!-- General Form Elements -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

                <!-- Bagian Diagnosa -->

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Do/Ds</strong></label>

                        <div class="col-sm-9">
                           <textarea name="diagnona" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentdiagnosa" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 

                    <!-- Bagian Tanggal Ditemukan -->
                    <div class="row mb-3">
                        <label for="tgl_ditemukan" class="col-sm-2 col-form-label"><strong>Tanggal Ditemukan</strong></label>

                        <div class="col-sm-9">
                            <input type="datetime-local" class="form-control" id="tgl_ditemukan" name="tgl_ditemukan">
                             
                            <!-- comment -->
                            <textarea class="form-control mt-2" id="commenttgl_ditemukan" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>

                    <!-- Bagian Tanggal Teratasi -->
                    <div class="row mb-3">
                        <label for="tgl_teratasi" class="col-sm-2 col-form-label"><strong>Tanggal Teratasi</strong></label>

                        <div class="col-sm-9">
                            <input type="datetime-local" class="form-control" id="tgl_teratasi" name="tgl_teratasi">
                            
                             <!-- comment -->
                            <textarea class="form-control mt-2" id="commenttfl_teratasi" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
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

                    <h5 class="card-title mt-2"><strong>Diagnosa Keperawatan</strong></h5>

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
                                <th class="text-center">Do/Ds</th>
                                <th class="text-center">Tanggal Ditemukan</th>
                                <th class="text-center">Tanggal Teratasi</th>
                        </tr>
                        </thead>
                        </table>
                        </form>
                        </div>
                        </div>

                            <div class="card">
            <div class="card-body">
              <h5 class="card-title mb-1"><strong>F. Rencana Keperawatan</strong></h5>
              <div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Nama Klien</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="nama_klien">
        <textarea class="form-control mt-2" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1">
        <div class="form-check">
            <input class="form-check-input" type="checkbox">
        </div>
    </div>
</div>
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>No. Registrasi</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="registrasi">
        <textarea class="form-control mt-2" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1">
        <div class="form-check">
            <input class="form-check-input" type="checkbox">
        </div>
    </div>
</div>
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Ruangan</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="ruangan">
        <textarea class="form-control mt-2" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1">
        <div class="form-check">
            <input class="form-check-input" type="checkbox">
        </div>
    </div>
</div>

                <!-- General Form Elements -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

                <!-- Bagian Diagnosa -->

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Diagnosa Keperawatan</strong></label>

                        <div class="col-sm-9">
                            <textarea name="diagnosa" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentdiagnosa" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 

                    <!-- Bagian Tujuan dan Kriteria Hasil -->

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Tujuan dan Kriteria Hasil</strong></label>

                        <div class="col-sm-9">
                            <textarea name="tujuandankriteria" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commenttujuandankriteria" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 

                    <!-- Bagian Intervensi -->

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Intervensi</strong></label>

                        <div class="col-sm-9">
                            <textarea name="intervensi" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentintervensi" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
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

                    <h5 class="card-title mt-2"><strong>Rencana Keperawatan</strong></h5>

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
                                <th class="text-center">Diagnosa</th>
                                <th class="text-center">Tujuan dan Kriteria Hasil</th>
                                <th class="text-center">Intervensi</th>
                        </tr>
                        </thead>
                        </table>
                        </form>
                        </div>
                            </div>

                             <div class="card">
            <div class="card-body">
              <h5 class="card-title mb-1"><strong>G. Implementasi Keperawatan</strong></h5>
                    <label class="col-sm-2 col-form-label"><strong>Nama Klien</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="nama_klien">
        <textarea class="form-control mt-2" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1">
        <div class="form-check">
            <input class="form-check-input" type="checkbox">
        </div>
    </div>
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>No. Registrasi</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="registrasi">
        <textarea class="form-control mt-2" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1">
        <div class="form-check">
            <input class="form-check-input" type="checkbox">
        </div>
    </div>
</div>
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Ruangan</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="ruangan">
        <textarea class="form-control mt-2" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1">
        <div class="form-check">
            <input class="form-check-input" type="checkbox">
        </div>
    </div>
</div>
                <!-- General Form Elements -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

                <!-- Bagian No. DX -->

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>No. DX</strong></label>

                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="nodx">

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
                        <label for="hari_tgl" class="col-sm-2 col-form-label"><strong>Hari/Tanggal</strong></label>

                        <div class="col-sm-9">
                            <input type="datetime-local" class="form-control" id="hari_tgl" name="hari_tgl">
                            
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
                        <label for="jam" class="col-sm-2 col-form-label"><strong>Jam</strong></label>

                        <div class="col-sm-9">
                             <input type="time" class="form-control" id="jam" name="jam">
                            
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

                <!-- Bagian Implementasi dan Hasil -->

                    <!-- Implementasi -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Implementasi</strong></label>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <textarea name="implementasi" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        </div>    
                    </div>
                                
                    <!-- Hasil -->
                    <label class="col-sm-2 col-form-label"><strong>Hasil</strong></label>
                    <div class="col-sm-3">
                        <div class="input-group">
                            <textarea name="hasil" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                         </div> 
                    </div>   

                    <div class="col-sm-1">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" disabled>
                        </div>
                    </div>   
                </div>

                <div class="row mb-3">
                    <div class="col-sm-9 offset-sm-2">
                        <textarea class="form-control" rows="2" placeholder="Kolom Ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakan!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                     </div>
                </div>
                                 
                    
                <!-- Bagian Button -->    
                    <div class="row mb-3">
                        <div class="col-sm-11 justify-content-end d-flex">
                            <button type="submit" name="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </div> 

                    <h5 class="card-title mt-2"><strong>Implementasi Keperawatan</strong></h5>

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
                                <th class="text-center">No. Dx </th>
                                <th class="text-center">Hari/Tanggal</th>
                                <th class="text-center">Jam</th>
                                <th class="text-center">Implementasi</th>
                                <th class="text-center">Hasil</th>
                        </tr>
                        </thead>

                    <tbody>

                    <?php
                    if(!empty($data)){
                        foreach($data as $row){
                            echo "<tr>
                            <td>".$row['no_dx']."</td>
                            <td>".$row['hari_tgl']."</td>
                            <td>".$row['jam']."</td>
                            <td>".$row['implementasi']."</td>
                            <td>".$row['hasil']."</td>
                            </tr>";
                        }
                    }
                    ?>

                    </tbody>
                    </table>
                    </form>
                    </div>
</div>
<div class="card">
            <div class="card-body">
              <h5 class="card-title mb-1"><strong>E. Evaluasi Keperawatan</strong></h5>
              <label class="col-sm-2 col-form-label"><strong>Nama Klien</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="nama_klien">
        <textarea class="form-control mt-2" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1">
        <div class="form-check">
            <input class="form-check-input" type="checkbox">
        </div>
    </div>
</div>
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>No. Registrasi</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="registrasi">
        <textarea class="form-control mt-2" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1">
        <div class="form-check">
            <input class="form-check-input" type="checkbox">
        </div>
    </div>
</div>
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Ruangan</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="ruangan">
        <textarea class="form-control mt-2" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1">
        <div class="form-check">
            <input class="form-check-input" type="checkbox">
        </div>
    </div>
</div>

                <!-- General Form Elements -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

                <!-- Bagian No. DX -->

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>No. DX</strong></label>

                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="nodx">
                            
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

       

                <!-- Bagian Jam -->

                    <div class="row mb-3">
                        <label for="jam" class="col-sm-2 col-form-label"><strong>Jam</strong></label>

                        <div class="col-sm-9">
                            <input type="time" class="form-control" id="jam" name="jam">
                            
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

                <!-- Bagian Evaluasi -->

                    <div class="row mb-2">
                        <label class="col-sm-2 col-form-label">
                            <strong>Evaluasi</strong>
                        </label>
                    </div>
                    
                    <!-- S -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>S (Subjective)</strong></label>

                        <div class="col-sm-9">
                            <textarea name="evaluasi_s" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        </div> 

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>

                    </div>
                    
                    <!-- O -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>O (Objective)</strong></label>

                        <div class="col-sm-9">
                            <textarea name="evaluasi_o" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        </div>
                    </div>
                    
                    <!-- A -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>A (Assessment)</strong></label>

                        <div class="col-sm-9">
                            <textarea name="evaluasi_a" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        </div>
                    </div>

                    <!-- P -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>P (Plan)</strong></label>

                        <div class="col-sm-9">
                            <textarea name="evaluasi_p" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        </div>
                    </div>

                     <!-- comment -->
                      <div class="row mb-3">
                        <div class="offset-sm-2 col-sm-9">
                            <textarea class="form-control mt-2" name="commentevaluasi" id="commentevaluasi" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
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
                        <div class="col-sm-11 d-flex justify-content-end gap-2">
                            <button type="submit" name="submit" class="btn btn-primary">Simpan</button>
                            <button type="submit" name="cetak" class="btn btn-success">Cetak</button>
                        </div>
                    </div>
                    
                   
                    <h5 class="card-title mt-2"><strong>Evaluasi Keperawatan</strong></h5>

                    <style>
                    .table-evaluasi {
                        table-layout: fixed;
                        width:100%
                    }

                    .table-evaluasi td,
                    .table-evaluasi th {
                        word-wrap: break-word;
                        white-space: normal;
                        vertical-align: top;
                    }
                    </style>

                    <table class="table table-bordered table-evaluasi">
                        <thead>
                            <tr>
                                <th class="text-center">No. Dx </th>
                                <th class="text-center">Jam</th>
                                <th class="text-center">Evaluasi</th>
                        </tr>
                        </thead>

                    <tbody>

                    <?php
                    if(!empty($data)){
                        foreach($data as $row){
                            echo "<tr>
                            <td>".$row['no_dx']."</td>
                            <td>".$row['jam']."</td>
                            <td>
                            <b>S :</b> ".$row['evaluasi_s']."<br>
                            <b>O :</b> ".$row['evaluasi_o']."<br>
                            <b>A :</b> ".$row['evaluasi_a']."<br>
                            <b>P :</b> ".$row['evaluasi_p']."<br>
                            </td>
                            </tr>";
                        }
                    }
                    ?>

                    </tbody>
                    </table>
            

    
<?php include "tab_navigasi.php"; ?>
</section>
</main>