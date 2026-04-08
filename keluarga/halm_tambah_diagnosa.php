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
        <h1><strong>Asuhan Keperawatan Keluarga</strong></h1>
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
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'pengkajian' ? 'active' : '' ?>"
        href="index.php?page=keluarga&tab=pengkajian">
        Pengkajian
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'diagnosa_keperawatan' ? 'active' : '' ?>"
        href="index.php?page=keluarga&tab=diagnosa_keperawatan">
        Diagnosa Keperawatan
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'rencana_keperawatan' ? 'active' : '' ?>"
        href="index.php?page=keluarga&tab=rencana_keperawatan">
        Rencana Keperawatan
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'implementasi_keperawatan' ? 'active' : '' ?>"
       href="index.php?page=keluarga&tab=implementasi_keperawatan">
        Implementasi Keperawatan
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'evaluasi_keperawatan' ? 'active' : '' ?>"
        href="index.php?page=keluarga&tab=evaluasi_keperawatan">
        Evaluasi keperawatan
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
              <h5 class="card-title mb-1"><strong>Diagnosa Keperawatan</strong></h5>

                <!-- General Form Elements -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

                <!-- Bagian Masalah Keperawatan -->

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Masalah Keperawatan</strong></label>

                        <div class="col-sm-9">
                           <textarea name="masalahkeperawatan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" name="commentmasalahkeperawatan" id="commentmasalhkeperawatan" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 
    
                <!-- Bagian Nama Anggota Keluarga yang Sakit -->

                <div class="row mb-3">
                    <label for="keluargayangsakit" class="col-sm-2 col-form-label"><strong>Nama Anggota Keluarga yang Sakit</strong></label>
                        <div class="col-sm-9">
                        <textarea name="keluargayangsakit" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                            
                            <!-- comment -->
                                <textarea class="form-control mt-2" name="commentkeluargayangsakit" id="commentkeluargayangsakit" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
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
                    .table-diagnosa {
                        table-layout: fixed;
                        width:100%
                    }

                    .table-diagnosa td,
                    .table-diagnosa th {
                        word-wrap: break-word;
                        white-space: normal;
                        vertical-align: top;
                    }
                    </style>

                    <table class="table table-bordered" style="table-layout: fixed; width: 100%;">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Masalah Keperawatan</th>
                                <th class="text-center">Nama Anggota Keluarga yang Sakit</th>
                        </tr>
                        </thead>

                    <tbody>

                    <?php
                    if(!empty($data)){
                        foreach($data as $row){
                            echo "<tr>
                            <td>".$no++."</td>
                            <td>".$row['masalahkeperawatan']."</td>
                            <td>".$row['keluargayangsakit']."</td>
                            </tr>";
                        }
                    }
                    ?>

                    </tbody>
                    </table>

                    <!-- Kriteria -->
                    <div class="row mb-3">
                        <label class="col-sm-10 col-form-label text-primary">
                            <strong>Kriteria</strong>
                        </label>
                    </div> 

                     <!-- Bagian Sifat Masalah -->

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Sifat Masalah <br> (Bobot 1)</strong></label>

                        <div class="col-sm-9">
                            <select class="form-select" name="sifatmasalah">
                                <option value="">Pilih</option>
                                <option value="Actual">Actual (3)</option>
                                <option value="Resiko">Resiko (2)</option>
                                <option value="Sejahtera">Sejahtera (1)</option>
                            </select>
                        
                     <!-- comment -->
                            <textarea class="form-control mt-2" name="commentsifatmasalah" id="commentsifatmasalah" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 

                     <!-- Bagian Pembenaran -->

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Pembenaran</strong></label>

                        <div class="col-sm-9">
                           <textarea name="pembenaransifatmasalah" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" name="commentpembenaransifatmasalah" id="commentpembenaransifatmasalah" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 

                    <!-- Bagian Kemungkinan Masalah Dapat diubah -->

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Kemungkinan Masalah Dapat diubah (Bobot 2)</strong></label>

                        <div class="col-sm-9">
                           <select class="form-select" name="masalahdiubah">
                                <option value="">Pilih</option>
                                <option value="Mudah">Mudah (2)</option>
                                <option value="Sebagian">Sebagian (1)</option>
                                <option value="Tidak_dapat">Tidak Dapat (0)</option>
                            </select>

                     <!-- comment -->
                            <textarea class="form-control mt-2" name="commentmasalahdiubah" id="commentmasalahdiubah" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 

                     <!-- Bagian Pembenaran -->

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Pembenaran</strong></label>

                        <div class="col-sm-9">
                           <textarea name="pembenaranmasalahdiubah" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" name="commentmasalahdiubah" id="commentpembenaranmasalahdiubah" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>

                    <!-- Bagian Potensial Masalah Dapat dicegah -->

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Potensial Masalah Dapat dicegah <br> (Bobot 1)</strong></label>

                        <div class="col-sm-9">
                           <select class="form-select" name="masalahdicegah">
                                <option value="">Pilih</option>
                                <option value="Tinggi">Tinggi (3)</option>
                                <option value="Cukup">Cukup (2)</option>
                                <option value="Rendah">Rendah (1)</option>
                            </select>

                     <!-- comment -->
                            <textarea class="form-control mt-2" name="commentmasalahdicegah" id="commentmasalahdicegah" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 

                     <!-- Bagian Pembenaran -->

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Pembenaran</strong></label>

                        <div class="col-sm-9">
                           <textarea name="pembenaranmasalahdicegah" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" name="commentpembenaranmasalahdicegah" id="commentpembenaranmasalahdicegah" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>

                    <!-- Bagian Menonjolnya Masalah -->

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Menonjolnya Masalah (Bobot 1)</strong></label>

                        <div class="col-sm-9">
                           <select class="form-select" name="menonjolnyamasalah">
                                <option value="">Pilih</option>
                                <option value="Masalahdirasakan">Masalah dirasakan dan harus segara ditangani (2)</option>
                                <option value="Adamasalah">Ada masalah tidak segera ditangani (1)</option>
                                <option value="Tidakdirasakan">Tidak dirasakan (0)</option>
                            </select>

                     <!-- comment -->
                            <textarea class="form-control mt-2" name="commentmenonjolnyamasalah" id="commentmenonjolnyamasalah" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 

                     <!-- Bagian Pembenaran -->

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Pembenaran</strong></label>

                        <div class="col-sm-9">
                           <textarea name="pembenaranmenonjolnyamasalah" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" name="commentpembenaranmenonjolnyamasalah" id="commentpembenaranmenonjolnyamasalah" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>

                            <!-- Bagian Skoring -->

                            <div class="row mb-3">
                                <label for="skoring" class="col-sm-2 col-form-label"><strong>Skoring</strong></label>
                                    <div class="col-sm-9">
                                    <input type="text" class="form-control" name="skoring">

                                        <!-- comment -->
                                            <textarea class="form-control mt-2" name="commentskoring" id="commentskoring" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
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

                                <style>
                                .table-skoring {
                                    table-layout: fixed;
                                    width:100%
                                }

                                .table-skoring td,
                                .table-skoring th {
                                    word-wrap: break-word;
                                    white-space: normal;
                                    vertical-align: top;
                                }
                                </style>

                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="text-center">No</th>
                                            <th class="text-center">Kriteria</th>
                                            <th class="text-center">Skoring</th>
                                    </tr>
                                    </thead>

                                <tbody>

                                <?php
                                if(!empty($data)){
                                    foreach($data as $row){
                                        echo"
                                        <tr>
                                        <td>1</td>
                                        <td>
                                        Sifat Masalah: ".$row['sifatmasalah']."<br>
                                        Pembenaran: ".$row['pembenaransifatmasalah']."
                                        </td>
                                        <td></td>
                                        </tr>

                                        <tr>
                                        <td>2</td>
                                        <td>
                                        Kemungkinan Masalah Dapat diubah: ".$row['masalahdiubah']."<br>
                                        Pembenaran: ".$row['pembenaranmasalahdiubah']."
                                        </td>
                                        <td></td>
                                        </tr>

                                        <tr>
                                        <td>3</td>
                                        <td>
                                        Potensial Masalah Dapat dicegah: ".$row['masalahdicegah']."<br>
                                        Pembenaran: ".$row['pembenaranmasalahdicegah']."
                                        </td>
                                        <td></td>
                                        </tr>

                                        <tr>
                                        <td>4</td>
                                        <td>
                                        Menonjolnya Masalah: ".$row['menonjolnyamasalah']."<br>
                                        Pembenaran: ".$row['pembenaranmenonjolnyamasalah']."
                                        </td>
                                        <td></td>
                                        </tr>

                                        <tr>
                                        <td colspan='2'><b>Jumlah</b></td>
                                        <td>".$row['skoring']."</td>                                            
                                        </tr>";

                                    }
                                }
                                ?>

                                </tbody>
                                </table>  
                                
                    <?php include "tab_navigasi.php"; ?>

</section>              
</main>
                
                 