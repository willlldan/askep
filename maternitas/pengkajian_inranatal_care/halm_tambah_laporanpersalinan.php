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
        <h1><strong>Pengkajian Inranatal Care Keperawatan Maternitas</strong></h1>
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
        <a class="nav-link <?= ($_GET['tab'] ?? 'umum') == 'umum' ? 'active' : '' ?>"
        href="?page=maternitas/pengkajian_inranatal_care&tab=umum">
        Data Umum
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? 'riwayatpersalinan') == 'riwayatpersalinan' ? 'active' : '' ?>"
        href="?page=maternitas/pengkajian_inranatal_care&tab=riwayatpersalinan">
        Riwayat Persalinan
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'laporanpersalinan' ? 'active' : '' ?>"
        href="?page=maternitas/pengkajian_inranatal_care&tab=laporanpersalinan">
        Laporan Persalinan
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'diagnosa_keperawatan' ? 'active' : '' ?>"
        href="?page=maternitas/pengkajian_inranatal_care&tab=diagnosa_keperawatan">
        Diagnosa keperawatan
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'intervensi_keperawatan' ? 'active' : '' ?>"
        href="?page=maternitas/pengkajian_inranatal_care&tab=intervensi_keperawatan">
        Intervensi keperawatan
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'implementasi_keperawatan' ? 'active' : '' ?>"
        href="?page=maternitas/pengkajian_inranatal_care&tab=implementasi_keperawatan">
        Implementasi keperawatan
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'evaluasi_keperawatan' ? 'active' : '' ?>"
        href="?page=maternitas/pengkajian_inranatal_care&tab=evaluasi_keperawatan">
        Evaluasi keperawatan
        </a>
    </li>

    </ul>

        <style>
        .custom-tabs {
            border-bottom: 1px solid #dee2e6;
            display: flex;
            width: 100%;
        }

        .custom-tabs .nav-item {
            flex: 1;
            display: flex;
        }

        .custom-tabs .nav-link {
            border: none;
            background: transparent;
            color: #f6f9ff;
            font-weight: 500;
            padding: 10px 15px;
            
            display: flex;
            align-items: center;
            justify-content: flex-start;

            width: 100%;
            height: 100%;
            text-align: left;
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
            
                <!-- General Form Elements -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

              <h5 class="card-title mb-1"><strong>Persalinan Kala I</strong></h5>

                <!-- Bagian Persalinan Kala I -->
                    
                    <!-- Mulai Persalinan dan Akhir -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Persalinan Kala I</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Mulai Persalinan dan Akhir (Tuliskan tanggal dan Jam). Hasil:</small>
                            <textarea name="mulaipersalinandanakhir" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                         </div>
                    </div>        

                    <!-- Tanda dan Gejala -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Tanda dan Gejala</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Tanda dan Gejala (Keluhan mules-mules, ada darah keluar dan lendir tapi baru sedikit melalui kemaluan). Hasil:</small>
                            <textarea name="tandadangejala" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                         </div>
                    </div>
                    
                    <!-- Bagian Tanda-tanda Vital -->

                    <div class="row mb-3">
                        <label class="col-sm-9 col-form-label">
                            <strong>Tanda-tanda Vital</strong>
                        </label>    
                    </div>

                    <!-- Tekanan Darah -->
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-2 col-form-label"><strong>Tekanan Darah</strong></label>
                        <div class="col-sm-4">
                            <div class="input-group">
                                <input type="text" class="form-control" name="tekanandarah">
                                <span class="input-group-text">mmHg</span>
                        </div>    
                    </div>
                                
                    <!-- Nadi -->
                    <label class="col-sm-2 col-form-label"><strong>Nadi</strong></label>
                    <div class="col-sm-4">
                        <div class="input-group">
                                <input type="text" class="form-control" name="nadi">
                                <span class="input-group-text">x/menit</span>
                        </div> 
                    </div>

                    </div>
                     
                    <!-- Suhu -->
                        <div class="row mb-3 align-items-center">
                            <label class="col-sm-2 col-form-label"><strong>Suhu</strong></label>
                            <div class="col-sm-4">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="suhu">
                                    <span class="input-group-text">°C</span>
                            </div>    
                        </div>

                    <!-- RR -->
                    <label class="col-sm-2 col-form-label"><strong>RR</strong></label>
                    <div class="col-sm-4">
                        <div class="input-group">
                                <input type="text" class="form-control" name="rr">
                                <span class="input-group-text">x/menit</span>
                            </div>
                        </div>
                    </div>
                
                <!-- Keluhan Lain -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Keluhan Lain</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Keluhan lainnya yang dirasakan (nyeri, cemas). Hasil:</small>
                            <textarea name="keluhanlain" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                         </div>
                    </div>

                <!-- Lama Kala I -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Lama Kala I</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">(Tuliskan berapa lama dalam hitungan jam dan menitnya). Hasil:</small>
                            <textarea name="lamakalai" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                         </div>
                    </div>

                    <!-- Tindakan Khusus -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Tindakan Khusus</strong></label>

                        <div class="col-sm-10">
                            <textarea name="tindakankhusus" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                         </div>
                    </div>

                    <!-- Bagian Pemeriksaan Dalam (VT) -->

                    <div class="row mb-3">
                        <label class="col-sm-9 col-form-label text-primary">
                            <strong>Pemeriksaan Dalam (VT)</strong>
                        </label>    
                    </div>

                    <!-- Pemeriksaan Ke -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Pemeriksaan Ke</strong></label>

                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="pemeriksaanke">    
                         </div>
                    </div>

                    <!-- Jam -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Jam</strong></label>

                        <div class="col-sm-10">
                            <input type="time" class="form-control" name="jam">
                         </div>
                    </div>

                    <!-- Hasil -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Hasil</strong></label>

                        <div class="col-sm-10">
                            <textarea name="hasil" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                         </div>
                    </div>

                    <!-- Bagian Button -->    
                <div class="row mb-3">
                    <div class="col-sm-12 justify-content-end d-flex">
                        <button type="submit" name="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div> 

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

                <h5 class="card-title mt-2"><strong>Tabel Pemeriksaan Dalam (VT)</strong></h5>

                <table class="table table-bordered table-pemeriksaan">
                    <thead>
                        <tr>
                            <th class="text-center">Pemeriksaan Ke</th>
                            <th class="text-center">Jam</th>
                            <th class="text-center">Hasil</th>
                       </tr>
                    </thead>

                <tbody>

                <?php
                if(!empty($data)){
                    foreach($data as $row){
                        echo "<tr>
                        <td>".$row['pemeriksaanke']."</td>
                        <td>".$row['jam']."</td>
                        <td>".$row['hasil']."</td>
                        </tr>";
                    }
                }
                ?>

                </tbody>
                </table>
                                   
                    <!-- Bagian Observasi  -->
                
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Observasi</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Observasi kemajuan persalinan menggunakan patograf</small>
                            <textarea name="observasi" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                         </div>
                    </div> 

                <!-- Bagian Pemantauan HIS -->

                    <div class="row mb-3">
                        <label class="col-sm-9 col-form-label text-primary">
                            <strong>Pementauan HIS</strong>
                        </label>    
                    </div>

                    <!-- Tanggal/Jam -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Tanggal/Jam</strong></label>

                        <div class="col-sm-10">
                            <input type="datetime-local" class="form-control" name="tanggaljam">    
                         </div>
                    </div>

                    <!-- Kontraksi Uterus -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Kontraksi Uterus</strong></label>

                        <div class="col-sm-10">
                            <textarea name="kontraksiuterus" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                         </div>
                    </div>

                    <!-- DJJ -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>DJJ</strong></label>

                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="djj">
                         </div>
                    </div>

                <!-- Keterangan -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Keterangan</strong></label>

                        <div class="col-sm-10">
                            <textarea name="keterangan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                         </div>
                    </div>
    
                    <!-- Bagian Button -->    
                <div class="row mb-3">
                    <div class="col-sm-12 justify-content-end d-flex">
                        <button type="submit" name="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div> 

                 <style>
                    .table-pemantauan {
                        table-layout: fixed;
                        width:100%
                    }

                    .table-pemantauan td,
                    .table-pemantauan th {
                        word-wrap: break-word;
                        white-space: normal;
                        vertical-align: top;
                    }
                    </style>

                <h5 class="card-title mt-2"><strong>Tabel Pemantauan HIS</strong></h5>

                <table class="table table-bordered table-pemantauan">
                    <thead>
                        <tr>
                            <th class="text-center">Tanggal/Jam</th>
                            <th class="text-center">Kontraksi Uterus</th>
                            <th class="text-center">DJJ</th>
                            <th class="text-center">Keterangan</th>
                       </tr>
                    </thead>

                <tbody>

                <?php
                if(!empty($data)){
                    foreach($data as $row){
                        echo "<tr>
                        <td>".$row['tanggaljam']."</td>
                        <td>".$row['kontraksiuterus']."</td>
                        <td>".$row['djj']."</td>
                        <td>".$row['keterangan']."</td>
                        </tr>";
                    }
                }
                ?>

                </tbody>
                </table>
             </div>
        </div>    


                <div class="card">
            <div class="card-body">
              <h5 class="card-title mb-1"><strong>Persalinan Kala II</strong></h5>

                <!-- General Form Elements -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

                <!-- Bagian Persalinan Kala II -->
                    
                    <!-- Mulai Persalinan dan Akhir -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Persalinan Kala II</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Mulai dan berakhir kala II (Tuliskan jam berapa mulai masuk ke kala II). Hasil:</small>
                            <textarea name="mulaipersalinandanakhir" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                         </div>
                    </div>        

                    <!-- Bagian Tanda-tanda Vital -->

                    <div class="row mb-3">
                        <label class="col-sm-9 col-form-label">
                            <strong>Tanda-tanda Vital</strong>
                        </label>    
                    </div>

                    <!-- Tekanan Darah -->
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-2 col-form-label"><strong>Tekanan Darah</strong></label>
                        <div class="col-sm-4">
                            <div class="input-group">
                                <input type="text" class="form-control" name="tekanandarah">
                                <span class="input-group-text">mmHg</span>
                        </div>    
                    </div>
                                
                    <!-- Nadi -->
                    <label class="col-sm-2 col-form-label"><strong>Nadi</strong></label>
                    <div class="col-sm-4">
                        <div class="input-group">
                                <input type="text" class="form-control" name="nadi">
                                <span class="input-group-text">x/menit</span>
                        </div> 
                    </div>

                    </div>
              
                    <!-- Suhu -->
                        <div class="row mb-3 align-items-center">
                            <label class="col-sm-2 col-form-label"><strong>Suhu</strong></label>
                            <div class="col-sm-4">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="suhu">
                                    <span class="input-group-text">°C</span>
                            </div>    
                        </div>

                    <!-- RR -->
                    <label class="col-sm-2 col-form-label"><strong>RR</strong></label>
                    <div class="col-sm-4">
                        <div class="input-group">
                                <input type="text" class="form-control" name="rr">
                                <span class="input-group-text">x/menit</span>
                            </div>
                        </div>
                    </div>
            
                <!-- Tanda dan Gejala Kala II -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Tanda dan Gejala Kala II</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">(Ibu merasa semakin sakit, keringat lebih banyak, merasa mules dan ingin BAB, HIS semakin sering dan meningkat,
                                terjadi pengeluaran pervagina semakin banyak, vulva membuka, perineum meregang, anus mengembang dan membentu huruf D). Hasil:</small>
                            <textarea name="tandadangejalaII" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                         </div>
                    </div>

                    <!-- Keluhan Tambahan -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Keluhan Tambahan</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">(pqrst). Hasil:</small>
                            <textarea name="keluhantambahan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                         </div>
                    </div>

                    <!-- Jelaskan Tanda/Tata Cara Mengejan -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Jelaskan Tanda/Tata Cara Mengejan</strong></label>

                        <div class="col-sm-10">
                            <textarea name="tandamengejan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                         </div>
                    </div>

                    <!-- Kebutuhan atau Tindakan -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Kebutuhan atau tindakan khusus yang dilakukan</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Hasil:</small>
                            <textarea name="kebutuhanataukeluhan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                         </div>
                    </div>

                    <!-- Lama Kala II -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Lama Kala II</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">(Catat berapa lama kala II berlangsung). Hasil:</small>
                            <textarea name="tandadangejala" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                         </div>
                    </div>
                   
                    <!-- Bagian Catatan Kelahiran Bayi -->

                    <div class="row mb-2">
                        <label class="col-sm-3 col-form-label text-primary">
                            <strong>Catatan Kelahiran Bayi</strong>
                    </div>
                    
                    <!-- Jam  -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Bayi lahir jam berapa</strong></label>

                        <div class="col-sm-10">
                            <input type="datetime-local" class="form-control" name="lahirjamberapa">
                         </div>
                    </div> 

                    <!-- Bagian Nilai APGAR -->
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-2 col-form-label"><strong>Nilai APGAR Menit I</strong></label>
                        <div class="col-sm-4">
                            <div class="input-group">
                                <input type="text" class="form-control" name="nilaiapgar">
                                <span class="input-group-text">menit</span>
                        </div>    
                    </div>
                                
                    <!-- V -->
                    <label class="col-sm-2 col-form-label"><strong>V</strong></label>
                    <div class="col-sm-4">
                        <div class="input-group">
                                <input type="text" class="form-control" name="nilaiapgarv">
                        </div>  
                    </div>    
                </div>

                    <!-- Bonding Ibu dan Bayi -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Bonding Ibu dan Bayi</strong></label>

                        <div class="col-sm-10">
                            <textarea name="bondingibudanbayi" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                         </div>
                    </div> 

                     <!-- Pengobatan -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Pengobatan</strong></label>

                            <div class="col-sm-10">
                                <textarea name="pengobatan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                    </div> 
                </div>
            </div>  
        </div>

        <div class="card">
            <div class="card-body">
              <h5 class="card-title mb-1"><strong>Persalinan Kala III</strong></h5>

                <!-- General Form Elements -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

                <!-- Bagian Persalinan Kala III -->
                    
                    <!--Placenta -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Plancenta lahir jam berapa</strong></label>

                        <div class="col-sm-10">
                            <input type="time" class="form-control" name="placenta">
                         </div>
                    </div>        

                    <!-- Tanda dan Gejala III -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Tanda dan Gejala III</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">(Rahim membulat, lebih mengeras, keluar darah tiba-tiba, tali pusat menjulur keluar.
                                TFU setinggi pusat, kontraksi rahim baik, kandung kemih kosong, uterus nampak bulat dan keras) dan (Perhatikan
                                keluhan pusing, mual, pendarahan, robekan perineum dan kondisi psikologis). Hasil:</small>
                            <textarea name="tandadangejalaiii" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                         </div>
                    </div>

                    <!-- Keluhan Lain yang dirasakan -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Keluhan Lain yang dirasakan</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Hasil:</small>
                            <textarea name="keluhanlain" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                         </div>
                    </div>
                    
                    
                    <!-- Bagian Karakteristik Placenta -->

                    <div class="row mb-3">
                        <label class="col-sm-9 col-form-label">
                            <strong>Karakteristik Placenta</strong>
                        </label>    
                    </div>

                    <!-- Ukuran -->
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-2 col-form-label"><strong>Ukuran</strong></label>
                        <div class="col-sm-4">
                            <div class="input-group">
                                <input type="text" class="form-control" name="ukuran">
                        </div>    
                    </div>
                                
                    <!-- Panjang Tali Pusat -->
                    <label class="col-sm-2 col-form-label"><strong>Panjang Tali Pusat</strong></label>
                    <div class="col-sm-4">
                        <div class="input-group">
                                <input type="text" class="form-control" name="panjangtalipusat">
                        </div>  
                    </div>
                </div>

                    <!-- Bagian Jumlah Pengeluaran Darah -->
                 
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Jumlah Pengeluaran Darah</strong></label>

                        <div class="col-sm-10">
                            <div class="input-group">
                                <input type="text" class="form-control" name="jumlahpengeluarandarah">
                                <span class="input-group-text">ml</span>
                        </div>  
                         </div>
                    </div> 

                    <!-- Karakteristik Darah -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Karakteristik Darah</strong></label>

                        <div class="col-sm-10">
                            <textarea name="karakteristikdarah" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                         </div>
                    </div> 

                     <!-- Tindakan/Kebutuhan Khusus -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Tindakan/Kebutuhan Khusus</strong></label>

                            <div class="col-sm-10">
                                <small class="form-text" style="color: red;">Hasil:</small>
                                <textarea name="tindakankebutuhankhusus" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                         </div>
                    </div> 

                    <!-- Pengobatan -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Pengobatan</strong></label>

                        <div class="col-sm-10">
                            <textarea name="pengobatan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                         </div>
                    </div>
                </div>
        </div>
                    
            <div class="card">
            <div class="card-body">
              <h5 class="card-title mb-1"><strong>Persalinan Kala IV</strong></h5>

                <!-- General Form Elements -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

                <!-- Bagian Persalinan Kala IV -->
                    
                    <!-- Mulai Persalinan dan Akhir -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Mulai Jam Berapa Masuk Kala IV</strong></label>

                        <div class="col-sm-10">
                            <input type="time" class="form-control" name="kalaiv">
                         </div>
                    </div>        
                    
                    <!-- Bagian Tanda-tanda Vital -->

                    <div class="row mb-3">
                        <label class="col-sm-9 col-form-label">
                            <strong>Tanda-tanda Vital</strong>
                        </label>    
                    </div>

                    <!-- Tekanan Darah -->
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-2 col-form-label"><strong>Tekanan Darah</strong></label>
                        <div class="col-sm-4">
                            <div class="input-group">
                                <input type="text" class="form-control" name="tekanandarah">
                                <span class="input-group-text">mmHg</span>
                        </div>    
                    </div>
                                
                    <!-- Nadi -->
                    <label class="col-sm-2 col-form-label"><strong>Nadi</strong></label>
                    <div class="col-sm-4">
                        <div class="input-group">
                                <input type="text" class="form-control" name="nadi">
                                <span class="input-group-text">x/menit</span>
                        </div> 
                    </div>

                    </div>
              
                    <!-- Suhu -->
                        <div class="row mb-3 align-items-center">
                            <label class="col-sm-2 col-form-label"><strong>Suhu</strong></label>
                            <div class="col-sm-4">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="suhu">
                                    <span class="input-group-text">°C</span>
                            </div>    
                        </div>

                    <!-- RR -->
                    <label class="col-sm-2 col-form-label"><strong>RR</strong></label>
                    <div class="col-sm-4">
                        <div class="input-group">
                                <input type="text" class="form-control" name="rr">
                                <span class="input-group-text">x/menit</span>
                            </div>
                        </div>
                    </div>

                <!-- Tanda dan Gejala IV -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Tanda dan Gejala IV</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">(Observasi keadaan umum, keluhan pusing, mual, mata kunang-kunang, TTV,
                                kontraksi uterus, perdarahan (jumlah, warna, karakteristik, dan bau), pengosongan kandung kemih (setiap 15 menit pada 1 jam pertama dst),
                                periksa perineum, bersihkan ibu). Hasil:</small>
                            <textarea name="tandadangejalaiv" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                         </div>
                    </div>
                    
                    <!-- Keluhan Lain yang dirasakan -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Keluhan Lain yang dirasakan</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Hasil:</small>
                            <textarea name="keluhan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                         </div>
                    </div> 

                    <!-- Bagian Jumlah Pengeluaran Darah -->
                 
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Jumlah Pengeluaran Darah</strong></label>

                        <div class="col-sm-10">
                            <div class="input-group">
                                <input type="text" class="form-control" name="jumlahpengeluarandarah">
                                <span class="input-group-text">ml</span>
                        </div>  
                         </div>
                    </div> 

                    <!-- Karakteristik Darah -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Karakteristik Darah</strong></label>

                        <div class="col-sm-10">
                            <textarea name="karakteristikdarah" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                         </div>
                    </div> 

                    <!-- Bonding Bayi dan Ibu -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Bonding Bayi dan Ibu</strong></label>

                        <div class="col-sm-10">
                            <textarea name="bondingibudanbayi" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                         </div>
                    </div> 

                    <!-- Tindakan/Kebutuhan Khusus -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Tindakan/Kebutuhan Khusus</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Hasil:</small>
                            <textarea name="tindakankebutuhankhusus" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                         </div>
                    </div> 

                <!-- Bagian Klasifikasi Data -->    

                <div class="row mb-2">
                    <label class="col-sm-6 col-form-label text-primary">
                        <strong>Klasifikasi Data</strong>
                </div>

                <!-- Bagian Data Subjektif (DS) -->
                <div class="row mb-3">
                    <label for="datasubjektif" class="col-sm-2 col-form-label"><strong>Data Subjektif (DS)</strong></label>
                    <div class="col-sm-10">
                        <textarea name="datasubjektif" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                         </div>
                    </div> 

                <!-- Bagian Data Objektif (DO) -->
                <div class="row mb-3">
                    <label for="dataobjektif" class="col-sm-2 col-form-label"><strong>Data Objektif (DO)</strong></label>
                    <div class="col-sm-10">
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
                    <div class="col-sm-12 justify-content-end d-flex">
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

                    <tbody>

                    <?php
                    if(!empty($data)){
                        foreach($data as $row){
                            echo "<tr>
                            <td>".nlrbr($row['datasubjektif'])."</td>
                            <td>".nlrbr($row['dataobjektif'])."</td>
                            </tr>";
                        }
                    }
                    ?>

                    </tbody>
                    </table>

            <!-- Bagian Analisa Data -->    

                <div class="row mb-2">
                    <label class="col-sm-6 col-form-label text-primary">
                        <strong>Analisa Data</strong>
                </div>

                <!-- Bagian DS/DO -->
                <div class="row mb-3">
                    <label for="dsdo" class="col-sm-2 col-form-label"><strong>DS/DO</strong></label>
                    <div class="col-sm-10">
                        <textarea name="dsdo" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                         </div>
                    </div> 

                <!-- Bagian Etiologi -->
                <div class="row mb-3">
                    <label for="etiologi" class="col-sm-2 col-form-label"><strong>Etiologi</strong></label>
                    <div class="col-sm-10">
                        <textarea name="etiologi" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                         </div>
                    </div> 
                    
                <!-- Bagian Masalah -->
                <div class="row mb-3">
                    <label for="masalah" class="col-sm-2 col-form-label"><strong>Masalah</strong></label>
                    <div class="col-sm-10">
                        <textarea name="masalah" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                         </div>
                    </div> 

                <!-- Bagian Button -->    
                <div class="row mb-3">
                    <div class="col-sm-12 justify-content-end d-flex">
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
                                <th class="text-center">Etiologi</th>
                                <th class="text-center">Masalah</th>
                        </tr>
                        </thead>

                    <tbody>

                    <?php
                    if(!empty($data)){
                        foreach($data as $row){
                            echo "<tr>
                            <td>".$row['dsdo']."</td>
                            <td>".$row['etiologi']."</td>
                            <td>".$row['masalah']."</td>
                            </tr>";
                        }
                    }
                    ?>

                    </tbody>
                    </table>
    
                <?php include "tab_navigasi.php"; ?>

</section>
</main>
