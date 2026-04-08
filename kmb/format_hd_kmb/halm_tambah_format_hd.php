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
        <h1><strong>Format HD</strong></h1>
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
        <a class="nav-link <?= ($_GET['tab'] ?? 'lp_ruanghd') == 'lp_ruanghd' ? 'active' : '' ?>"
        href="index.php?page=kmb/format_hd_kmb&tab=lp_ruanghd">
            Format Laporan Pendahuluan Ruang HD</a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'format_hd' ? 'active' : '' ?>"
        href="index.php?page=kmb/format_hd_kmb&tab=format_hd">
Format Hermodalisa (HD) </a>
    </li>

    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'resume' ? 'active' : '' ?>"
        href="index.php?page=kmb/format_hd_kmb&tab=resume">
        Format Resume Ruang HD
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'analisa' ? 'active' : '' ?>"
        href="index.php?page=kmb/format_hd_kmb&tab=analisa">
        Analisa Keperawatan
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'diagnosa' ? 'active' : '' ?>"
        href="index.php?page=kmb/format_hd_kmb&tab=diagnosa">
        Diagnosa Keperawatan
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'rencana' ? 'active' : '' ?>"
        href="index.php?page=kmb/format_hd_kmb&tab=rencana">
Rencana Keperawatan        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'implementasi' ? 'active' : '' ?>"
        href="index.php?page=kmb/format_hd_kmb&tab=implementasi">
        Implementasi Keperawatan
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'evaluasi' ? 'active' : '' ?>"
        href="index.php?page=kmb/format_hd_kmb&tab=evaluasi">
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
              <h5 class="card-title mb-1"><strong>Format Hermodalisa (HD)</strong></h5>

<!-- NAMA MAHASISWA -->
<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>Nama Mahasiswa</strong></label>

<div class="col-sm-9">
<input type="text" class="form-control" name="nama_mahasiswa">

<textarea class="form-control mt-2" rows="2"
placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
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
placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
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
placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
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
placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
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
<label class="col-sm-2 col-form-label"><strong>Nama (inisial)</strong></label>

<div class="col-sm-9">
<input type="text" class="form-control" name="nama_klien">

<textarea class="form-control mt-2" rows="2"
placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
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
placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
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
placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
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
placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
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
placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
</div>

<div class="col-sm-1">
<div class="form-check">
<input class="form-check-input" type="checkbox">
</div>
</div>
</div>





<!-- WAKTU OPERASI -->
<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>Tanggal Pertama HD</strong></label>

<div class="col-sm-9">
<div class="row">
<div class="col-md-4">
<input type="date" class="form-control" name="tgl_operasi">
</div>
</div>

<textarea class="form-control mt-2" rows="2"
placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>

</div>

<div class="col-sm-1">
<div class="form-check">
<input class="form-check-input" type="checkbox">
</div>
</div>
</div>
<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>HD ke berapa</strong></label>

<div class="col-sm-9">
<input type="text" class="form-control" name="hd">

<textarea class="form-control mt-2" rows="2"
placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
</div>

<div class="col-sm-1">
<div class="form-check">
<input class="form-check-input" type="checkbox">
</div>
</div>
</div>

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
placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>

</div>

<div class="col-sm-1">
<div class="form-check">
<input class="form-check-input" type="checkbox">
</div>
</div>
</div>

<!-- C TINDAKAN -->
<div class="row mb-2">
<label class="col-sm-12 text-primary"><strong>B.Status Emosional Klien dan Keluarga</strong></label>
</div>

<div class="row mb-3">
<label class="col-sm-2 col-form-label"></label>

<div class="col-sm-9">
<textarea class="form-control" rows="5" name="status"
placeholder=""></textarea>

<textarea class="form-control mt-2" rows="2"
placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>

</div>

<div class="col-sm-1">
<div class="form-check">
<input class="form-check-input" type="checkbox">
</div>
</div>
</div>
<!-- C TINDAKAN -->
<div class="row mb-2">
<label class="col-sm-12 text-primary"><strong>C.Riwayat komplikasi HD Sebelumnya (Narasikan komplikasi yang di alami pasien pada HD sebelumnya)</strong></label>
</div>

<div class="row mb-3">
<label class="col-sm-2 col-form-label"></label>

<div class="col-sm-9">
<textarea class="form-control" rows="5" name="riwayat"
placeholder=""></textarea>

<textarea class="form-control mt-2" rows="2"
placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>

</div>

<div class="col-sm-1">
<div class="form-check">
<input class="form-check-input" type="checkbox">
</div>
</div>
</div>
<!-- C TINDAKAN -->

<div class="row mb-3">
    <label class="col-sm-4 col-form-label text-primary"><strong>D.	Nilai Laboratorium Terakhir </strong></label>
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
                    </div>
                
                <h5 class="card-title"><strong>D.	Nilai Laboratorium Terakhir</strong></h5>
                
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

<div class="row mb-2">
<label class="col-sm-12 text-primary"><strong>E.	Persiapan </strong></label>
</div>

<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>1. Lingkungan </strong></label>

<div class="col-sm-9">
<input type="text" class="form-control" name="diagnosa_medis">

<textarea class="form-control mt-2" rows="2"
placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
</div>

<div class="col-sm-1">
<div class="form-check">
<input class="form-check-input" type="checkbox">
</div>
</div>
</div>
<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>2. Mesin HD </strong></label>

<div class="col-sm-9">
<input type="text" class="form-control" name="diagnosa_medis">

<textarea class="form-control mt-2" rows="2"
placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
</div>

<div class="col-sm-1">
<div class="form-check">
<input class="form-check-input" type="checkbox">
</div>
</div>
</div>
<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>3. Klien  </strong></label>
</div>
<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>a. Pengukuran Berat Badan</strong></label>

<div class="col-sm-9">
<input type="text" class="form-control" name="pengukuran">

<textarea class="form-control mt-2" rows="2"
placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
</div>

<div class="col-sm-1">
<div class="form-check">
<input class="form-check-input" type="checkbox">
</div>
</div>
</div>
<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>b. Pengukuran TTV </strong></label>
</div>
<div class="row mb-3">
                            <label for="bbtb" class="col-sm-2 col-form-label"><strong>TD</strong></label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="bbtb">
                                    <span class="input-group-text">mmHg</span>
                        </div>
                                    
                        <!-- comment -->
                            <textarea class="form-control mt-2" id="commentbbtb" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                        </div>
                        <div class="row mb-3">
                            <label for="bbtb" class="col-sm-2 col-form-label"><strong>N</strong></label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="bbtb">
                                    <span class="input-group-text">X/m</span>
                        </div>
                                    
                        <!-- comment -->
                            <textarea class="form-control mt-2" id="commentbbtb" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                        </div>
                        <div class="row mb-3">
                            <label for="bbtb" class="col-sm-2 col-form-label"><strong>S</strong></label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="bbtb">
                                    <span class="input-group-text">°C</span>
                        </div>
                                    
                        <!-- comment -->
                            <textarea class="form-control mt-2" id="commentbbtb" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                        </div>
                        <div class="row mb-3">
                            <label for="bbtb" class="col-sm-2 col-form-label"><strong>RR</strong></label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="bbtb">
                                    <span class="input-group-text">x/m</span>
                        </div>
                                    
                        <!-- comment -->
                            <textarea class="form-control mt-2" id="commentbbtb" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                        </div>
                        <div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>4.	Alat </strong></label>

<div class="col-sm-9">
<input type="text" class="form-control" name="diagnosa_medis">

<textarea class="form-control mt-2" rows="2"
placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
</div>

<div class="col-sm-1">
<div class="form-check">
<input class="form-check-input" type="checkbox">
</div>
</div>
</div>
<div class="row mb-2">
<label class="col-sm-12 text-primary"><strong>F. Prosedur Kerja</strong></label>
</div>
<p>(Tuliskan suatu tindakan yang diberikan mulai dari persiapan sampai selesai melakukan HD)</p>
<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>1.	Pre HD</strong></label>

<div class="col-sm-9">
<input type="text" class="form-control" name="pre">

<textarea class="form-control mt-2" rows="2"
placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
</div>

<div class="col-sm-1">
<div class="form-check">
<input class="form-check-input" type="checkbox">
</div>
</div>
</div>

<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>2.	Post HD</strong></label>

<div class="col-sm-9">
<input type="text" class="form-control" name="pos">

<textarea class="form-control mt-2" rows="2"
placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
</div>

<div class="col-sm-1">
<div class="form-check">
<input class="form-check-input" type="checkbox">
</div>
</div>
</div>
<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>3.	Observasi</strong></label>

<div class="col-sm-9">
<input type="text" class="form-control" name="observasi">

<textarea class="form-control mt-2" rows="2"
placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
</div>

<div class="col-sm-1">
<div class="form-check">
<input class="form-check-input" type="checkbox">
</div>
</div>
</div>
<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>4.	Respon terhadap tindakan HD</strong></label>

<div class="col-sm-9">
<input type="text" class="form-control" name="respon">

<textarea class="form-control mt-2" rows="2"
placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
</div>

<div class="col-sm-1">
<div class="form-check">
<input class="form-check-input" type="checkbox">
</div>
</div>
</div>
<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>5.	Hasil yang diperoleh</strong></label>

                <!-- General Form Elements -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

                <!-- Bagian Diagnosa -->

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Jam</strong></label>

                        <div class="col-sm-9">
                            <textarea name="Jam" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

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
                        <label class="col-sm-2 col-form-label"><strong>TD</strong></label>

                        <div class="col-sm-9">
                            <textarea name="td" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

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
                        <label class="col-sm-2 col-form-label"><strong>Nadi</strong></label>

                        <div class="col-sm-9">
                            <textarea name="Nadi" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

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
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Qb</strong></label>

                        <div class="col-sm-9">
                            <textarea name="Qb" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

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
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>TMP</strong></label>

                        <div class="col-sm-9">
                            <textarea name="tmp" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

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
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Tek. A</strong></label>

                        <div class="col-sm-9">
                            <textarea name="tek.a" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

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
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Tek. V</strong></label>

                        <div class="col-sm-9">
                            <textarea name="tek.v" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

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
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Hp</strong></label>

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

                    <h5 class="card-title mt-2"><strong>5. Hasil yang diperoleh
</strong></h5>

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
                                <th class="text-center">Jam</th>
                                <th class="text-center">TD</th>
                                <th class="text-center">Nadi</th>
                                <th class="text-center">Qb</th>
                                <th class="text-center">TMP</th>
                                <th class="text-center">Tek. A</th>
                                <th class="text-center">Tek. V</th>
                                <th class="text-center">Hp</th>

                        </tr>
                        </thead>
                        </table>
                        </form>
                        </div>
                    
<div class="row mb-2">
<label class="col-sm-12 text-primary"><strong>G.	Health Education (HE) yang diberikan sebelum meninggalkan HD:</strong></label>
</div>

<div class="row mb-3">
<label class="col-sm-2 col-form-label"></label>

<div class="col-sm-9">
<textarea class="form-control" rows="5" name="laboratorium"
placeholder=""></textarea>

<textarea class="form-control mt-2" rows="2"
placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>

</div><?php include "tab_navigasi.php"; ?>

</section>              
</main>
                
                 