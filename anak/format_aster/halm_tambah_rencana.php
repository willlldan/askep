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
        <h1><strong>Anak Bayi Baru Lahir</strong></h1>
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
              <h5 class="card-title mb-1"><strong> Rencana Keperawatan</strong></h5>
              <div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Nama Klien</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="nama_klien">
        <textarea class="form-control mt-2" rows="2" placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
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
        <textarea class="form-control mt-2" rows="2" placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
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
        <textarea class="form-control mt-2" rows="2" placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
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


  <?php include "tab_navigasi.php"; ?>
    </section>
  