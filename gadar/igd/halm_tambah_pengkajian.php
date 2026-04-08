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
        <h1><strong>Pengkajian Keperawatan Ruang IGD</strong></h1>
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
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'laporanpendahuluan' ? 'active' : '' ?>"
        href="index.php?page=gadar/igd&tab=laporanpendahuluan">
        Laporan Pendahuluan
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'pengkajian' ? 'active' : '' ?>"
        href="index.php?page=gadar/igd&tab=pengkajian">
        Pengkajian
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'diagnosa_keperawatan' ? 'active' : '' ?>"
        href="index.php?page=gadar/igd&tab=diagnosa_keperawatan">
        Diagnosa Keperawatan
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'rencana_keperawatan' ? 'active' : '' ?>"
        href="index.php?page=gadar/igd&tab=rencana_keperawatan">
        Rencana Keperawatan
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'implementasi_keperawatan' ? 'active' : '' ?>"
       href="index.php?page=gadar/igd&tab=implementasi_keperawatan">
        Implementasi Keperawatan
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'evaluasi_keperawatan' ? 'active' : '' ?>"
        href="index.php?page=gadar/igd&tab=evaluasi_keperawatan">
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
              <h5 class="card-title mb-1"><strong>A. IDENTITAS</strong></h5>

                <!-- General Form Elements -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

                <!-- Bagian No Rekam Medis -->
                <div class="row mb-3">
                    <label for="norekammedis" class="col-sm-2 col-form-label"><strong>No Rekam Medis</strong></label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="norekammedis">
                       
                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentnorekammedis" id="commentnorekammedis" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>    

                <!-- Bagian Nama -->

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Nama</strong></label>

                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="nama">

                            <!-- comment -->
                            <textarea class="form-control mt-2" name="commentnama" id="commentnama" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>

                <!-- Bagian Umur -->
                <div class="row mb-3">
                    <label for="umur" class="col-sm-2 col-form-label"><strong>Umur</strong></label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="umur">

                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentumur" id="commentumur" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>

                <!-- Bagian Agama -->
                <div class="row mb-3">
                    <label for="agama" class="col-sm-2 col-form-label"><strong>Agama</strong></label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="agama">

                       <!-- comment -->
                            <textarea class="form-control mt-2" name="commentagama" id="commentagama" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>
                
                <!-- Bagian Pekerjaan -->
                <div class="row mb-3">
                    <label for="pekerjaan" class="col-sm-2 col-form-label"><strong>Pekerjaan</strong></label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="pekerjaan">

                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentpekerjaan" id="commentpekerjaan" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>
                
                <!-- Bagian Alamat -->
                <div class="row mb-3">
                    <label for="alamat" class="col-sm-2 col-form-label"><strong>Alamat</strong></label>
                    <div class="col-sm-9">
                       <textarea name="alamat" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentalamat" id="commentalamat" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>

                <!-- Bagian Diagnosa Medis -->
                <div class="row mb-3">
                    <label for="diagnosamedis" class="col-sm-2 col-form-label"><strong>Diagnosa Medis</strong></label>
                    <div class="col-sm-9">
                       <textarea name="diagnosamedis" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentdiagnosamedis" id="commentdiagnosamedis" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>
    
                <!-- Bagian Jenis Kelamin -->
                 <div class="row mb-3">
                    <label for="jeniskelamin" class="col-sm-2 col-form-label"><strong>Jenis Kelamin</strong></label> 
                    <div class="col-sm-9">
                    <select class="form-select" name="jeniskelamin">
                            <option value="">Pilih</option>
                            <option value="Perempuan">Perempuan</option>
                            <option value="Laki-laki">Laki-laki</option>
                            </select>
                            
                     <!-- comment -->
                            <textarea class="form-control mt-2" name="commentjeniskelamin" id="commentjeniskelamin" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>    

                <!-- Bagian Pendidikan -->
                <div class="row mb-3">
                    <label for="pendidikan" class="col-sm-2 col-form-label"><strong>Pendidikan</strong></label>
                    <div class="col-sm-9">
                       <input type="text" class="form-control" name="pendidikan">
 
                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentpendidikan" id="commentpendidikan" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>

                <!-- Bagian Status Perkawinan -->
                <div class="row mb-3">
                    <label for="statusperkawinan" class="col-sm-2 col-form-label"><strong>Status Perkawinan</strong></label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="statusperkawinan">

                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentstatusperkawinan" id="commentstatusperkawinan" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 
                    
                <!-- Bagian Sumber Informasi -->
                <div class="row mb-3">
                    <label for="sumberinformasi" class="col-sm-2 col-form-label"><strong>Sumber Informasi</strong></label>
                    <div class="col-sm-9">
                       <textarea name="sumberinformasi" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentsumberinformasi" id="commentsumberinformasi" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>

                <!-- Bagian Triase -->
                        <div class="row mb-2">
                            <div class="col-sm-2">
                                <strong>Triase</strong>
                            </div> 

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="triase" value="p1" id="p1">
                                        <label class="form-check-label" for="p1" style="color:red; font-weight:bold;">P1</label>
                                    </div>
                                </div>

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="triase" value="p2" id="p2">
                                        <label class="form-check-label" for="p2" style="color:#ffc107; font-weight:bold;">P2</label>
                                </div>
                            </div>

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="triase" value="p3">
                                        <label class="form-check-label" for="p3" style="color:green; font-weight:bold;">P3</label>
                                </div>
                            </div>

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="triase" value="p4">
                                        <label class="form-check-label" for="p4" style="color:black; font-weight:bold;">P4</label>
                                </div>
                            </div>
                        </div> 
                    </div>
                </div>  
                        
        <div class="card">
            <div class="card-body">
              <h5 class="card-title mb-1"><strong>B. PRIMARY SURVEY</strong></h5>                
    
                <!-- Bagian Keluhan Utama -->
                <div class="row mb-3">
                    <label for="keluhanutama" class="col-sm-2 col-form-label"><strong>Keluhan Utama</strong></label>
                    <div class="col-sm-9">
                       <textarea name="keluhanutama" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentkeluhanutama" id="commentkeluhanutama" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>
                
                <!-- Bagian Riwayat Keluhan Utama -->
                <div class="row mb-3">
                    <label for="riwyatkeluhanutama" class="col-sm-2 col-form-label"><strong>Riwayat Keluhan Utama</strong></label>
                    <div class="col-sm-9">
                       <textarea name="riwayatkeluhanutama" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentriwayatkeluhanutama" id="commentriwayatkeluhanutama" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>

                <!-- Bagian Airway -->
                <div class="row mb-3">
                    <label class="col-sm-10 col-form-label text-primary">
                        <strong>Airway</strong>
                </div>   

                    <!-- Bagian Jalan Napas -->
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Jalan Napas</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="jalannapas" value="paten">
                            <label class="form-check-label">Paten</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="jalannapas" value="tidakpaten">
                            <label class="form-check-label">Tidak Paten</label>
                        </div>
                    </div>
                </div>

                 <!-- Bagian Obstruksi -->
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Obstruksi</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="obstruksi" value="lidah">
                            <label class="form-check-label">Lidah</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="obstruksi" value="cairan">
                            <label class="form-check-label">Cairan</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="obstruksi" value="bendaasing">
                            <label class="form-check-label">Benda Asing</label>
                        </div>
                    </div>
                </div>

                 <!-- Bagian Suara Napas -->
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Suara Napas</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="suaranapas" value="snoring">
                            <label class="form-check-label">Snoring</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="suaranapas" value="gurgling">
                            <label class="form-check-label">Gurgling</label>
                        </div>
                    </div>
                </div>

                <!-- Bagian Keluhan Lain -->
                <div class="row mb-3">
                    <label for="keluhanlainairway" class="col-sm-2 col-form-label"><strong>Keluhan Lain</strong></label>
                    <div class="col-sm-9">
                       <textarea name="keluhanlainairway" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentkeluhanlainairway" id="commentkeluhanlainairway" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>

            <!-- Bagian Breathing -->
                <div class="row mb-3">
                    <label class="col-sm-10 col-form-label text-primary">
                        <strong>Breathing</strong>
                </div>   

                    <!-- Bagian Gerakan Dada -->
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Gerakan Dada</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="gerakandada" value="simetris">
                            <label class="form-check-label">Simetris</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="gerakandada" value="asimetris">
                            <label class="form-check-label">Asimetris</label>
                        </div>
                    </div>
                </div>

                 <!-- Bagian Irama Napas -->
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Irama Napas</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="iramanapas" value="cepat">
                            <label class="form-check-label">Cepat</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="iramanapas" value="dangkal">
                            <label class="form-check-label">Dangkal</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="iramanapas" value="normal">
                            <label class="form-check-label">Normal</label>
                        </div>
                    </div>
                </div>

                 <!-- Bagian Pola Napas -->
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Pola Napas</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="polanapas" value="teratur">
                            <label class="form-check-label">Teratur</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="polanapas" value="tidakteratur">
                            <label class="form-check-label">Tidak Teratur</label>
                        </div>
                    </div>
                </div>

                <!-- Bagian Retraksio Otot Dada -->
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Retraksio Otot Dada</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="ototdada" value="ada">
                            <label class="form-check-label">Ada</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="ototdada" value="tidakada">
                            <label class="form-check-label">Tidak Ada</label>
                        </div>
                    </div>
                </div>

                 <!-- Bagian Sesak Napas -->
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Sesak Napas</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="sesaknapas" value="ada">
                            <label class="form-check-label">Ada</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="sesaknapas" value="tidakada">
                            <label class="form-check-label">Tidak Ada</label>
                        </div>
                    </div>
                </div>

                <!-- Bagian RR -->
                <div class="row mb-3">
                    <label for="rr" class="col-sm-2 col-form-label"><strong>RR</strong></label>
                    <div class="col-sm-9">
                        <div class="input-group">
                                <input type="text" class="form-control" name="rr">
                                <span class="input-group-text">x/menit</span>
                            </div>
                  
                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentrr" id="commentrr" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>

                <!-- Bagian Suara Napas -->
                <div class="row mb-3">
                    <label for="suaranapas" class="col-sm-2 col-form-label"><strong>Suara Napas</strong></label>
                    <div class="col-sm-9">
                       <textarea name="suaranapas" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentsuaranapas" id="commentsuaranapas" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>    

                <!-- Bagian Keluhan Lain -->
                <div class="row mb-3">
                    <label for="keluhanlainairway" class="col-sm-2 col-form-label"><strong>Keluhan Lain</strong></label>
                    <div class="col-sm-9">
                       <textarea name="keluhanlainairway" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentkeluhanlainairway" id="commentkeluhanlainairway" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>

            <!-- Bagian Circulation -->
                <div class="row mb-3">
                    <label class="col-sm-10 col-form-label text-primary">
                        <strong>Circulation</strong>
                </div>   

                    <!-- Bagian Pucat -->
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Pucat</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="pucat" value="ya">
                            <label class="form-check-label">Ya</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="pucat" value="tidak">
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>

                 <!-- Bagian Sianosis -->
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Sianosis</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="sianosis" value="ya">
                            <label class="form-check-label">Ya</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="sianosis" value="tidak">
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>

                <!-- Bagian Pendarahan -->
                <div class="row mb-2">
                    <div class="col-sm-2"><strong>Pendarahan</strong>
                </div>  

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="pendarahan" value="ya">
                        <label class="form-check-label">Ya</label>
                    </div>
                </div>

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="pendarahan" value="tidak">
                        <label class="form-check-label">Tidak</label>
                    </div>
                </div>

                <!-- Berapa Banyak -->
                <div class="col-sm-10 offset-sm-2">
                    <div class="row mt-2">
                        
                    <div class="col-sm-11">
                        <label><strong>Berapa Banyak</strong></label>
                        <input type="text" class="form-control" name="berapabanyak">

                        <textarea class="form-control mt-2" name="commentberapabanyak" id="commentberapabanyak" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1  mt-4 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bagian Nadi -->
                <div class="row mb-2">
                    <div class="col-sm-2"><strong>Nadi</strong>
                </div>  

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="nadi" value="teraba">
                        <label class="form-check-label">Teraba</label>
                    </div>
                </div>

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="nadi" value="tidakteraba">
                        <label class="form-check-label">Tidak Teraba</label>
                    </div>
                </div>

                <!-- Frekuensi Nadi -->
                <div class="col-sm-10 offset-sm-2">
                    <div class="row mt-2">
                        
                    <div class="col-sm-11">
                        <label><strong>Frekuensi Nadi</strong></label>
                        <div class="input-group">
                                <input type="text" class="form-control" name="frekuensinadi">
                                <span class="input-group-text">x/menit</span>
                            </div>

                        <textarea class="form-control mt-2" name="commentfrekuensinadi" id="commentfrekuensinadi" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1  mt-4 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

                <!-- Bagian Tekanan Darah -->
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><strong>Tekanan Darah</strong></label>
                    
                <div class="col-sm-10">
                    <div class="row">  
                        
                <div class="col-sm-11">
                    <textarea name="tekanandarah" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commenttekanandarah" id="commenttekanandarah" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 
                </div>
            </div>

            <!-- Bagian Suhu -->
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><strong>Suhu</strong></label>
                    
                <div class="col-sm-10">
                    <div class="row">  
                        
                <div class="col-sm-11">
                    <div class="input-group">
                        <input type="text" class="form-control" name="suhu">
                        <span class="input-group-text">°C</span>
                    </div>

                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentsuhu" id="commentsuhu" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 
                </div> 
            </div>   
            
                 <!-- Bagian CRT -->
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>CRT</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="crt" value="kurang2">
                            <label class="form-check-label">&lt; 2 Detik</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="crt" value="lebih2">
                            <label class="form-check-label">&gt; 2 Detik</label>
                        </div>
                    </div>
                </div>

                 <!-- Bagian Akral -->
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Akral</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="akral" value="hangat">
                            <label class="form-check-label">Hangat</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="akral" value="dingin">
                            <label class="form-check-label">Dingin</label>
                        </div>
                    </div>
                </div>

                <!-- Bagian Keluhan Lain -->
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><strong>Keluhan Lain</strong></label>
                    
                <div class="col-sm-10">
                    <div class="row">  
                        
                <div class="col-sm-11">
                    <textarea name="keluhanlain" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentkeluhanlain" id="commentkeluhanlain" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 
                </div>
            </div>

        <!-- Bagian Disability -->
                <div class="row mb-3">
                    <label class="col-sm-10 col-form-label text-primary">
                        <strong>Disability</strong>
                </div>   

                    <!-- Bagian Respon -->
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Respon</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="respon" value="alert">
                            <label class="form-check-label">Alert</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="respon" value="verbal">
                            <label class="form-check-label">Verbal</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="respon" value="pain">
                            <label class="form-check-label">Pain</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="respon" value="unresponden">
                            <label class="form-check-label">Unresponden</label>
                        </div>
                    </div>
                </div>

                <!-- Bagian Kesadaran -->
                <div class="row mb-2">
                    <div class="col-sm-2"><strong>Kesadaran</strong>
                </div>  

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="kesadaran" value="cm">
                        <label class="form-check-label">CM</label>
                    </div>
                </div>

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="kesadaran" value="apatis">
                        <label class="form-check-label">Apatis</label>
                    </div>
                </div>

                 <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="kesadaran" value="samnolene">
                        <label class="form-check-label">Samnolene</label>
                    </div>
                </div>

                 <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="kesadaran" value="lainnya">
                        <label class="form-check-label">....</label>
                    </div>
                </div>

                <!-- Lainnya -->
                <div class="col-sm-10 offset-sm-2">
                    <div class="row mt-2">
                        
                    <div class="col-sm-11">
                        <label><strong>Lainnya (Sebutkan)</strong></label>
                        <input type="text" class="form-control" name="lainnyasebutkan">

                        <textarea class="form-control mt-2" name="commentlainnyasebutkan" id="commentlainnyasebutkan" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1  mt-4 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- GCS -->
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label"><strong>GCS</strong></label>
                
                <div class="col-sm-10">
                    <div class="row">

                    <div class="col-sm-11">

                    <div class="row">
                        <div class="col-sm-4 d-flex align-items-center">
                            <label class="me-2 mb-0"><strong>E</strong></label>
                            <input type="text" class="form-control" name="e">
                        </div>

                        <div class="col-sm-4 d-flex align-items-center">
                            <label class="me-2 mb-0"><strong>M</strong></label>
                            <input type="text" class="form-control" name="m">
                        </div>

                        <div class="col-sm-4 d-flex align-items-center">
                            <label class="me-2 mb-0"><strong>V</strong></label>
                            <input type="text" class="form-control" name="v">
                        </div>
                    </div>

                     <!-- comment -->
                        <textarea class="form-control mt-2" name="commentemv" id="commentemv" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>

                    </div>
                </div>
            </div>
  
            <!-- Bagian Pupil -->
            <div class="row mb-2">
                <div class="col-sm-2"><strong>Pupil</strong>
            </div>    

            <div class="col-sm-2">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="pupil" value="isokor">
                    <label class="form-check-label">Isokor</label>
                </div>
            </div>

            <div class="col-sm-2">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="pupil" value="anisokor">
                    <label class="form-check-label">Anisokor</label>
                </div>
            </div>

            <div class="col-sm-2">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="pupil" value="miosis">
                    <label class="form-check-label">Miosis</label>
                </div>
            </div>

            <div class="col-sm-2">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="respon" value="midriasis">
                    <label class="form-check-label">Midriasis</label>
                </div>
            </div>
     </div>

            <!-- Bagian Refleks Cahaya -->
            <div class="row mb-2">
                <div class="col-sm-2"><strong>Refleks Cahaya</strong>
            </div>    

            <div class="col-sm-2">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="reflekscahaya" value="ada">
                    <label class="form-check-label">Ada</label>
                </div>
            </div>

            <div class="col-sm-2">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="reflekscahaya" value="tidakada">
                    <label class="form-check-label">Tidak Ada</label>
                </div>
            </div>
     </div> 

            <!-- Bagian Muntah Proyektil -->
                  <div class="row mb-2">
                <div class="col-sm-2"><strong>Muntah Proyektil</strong>
            </div>    

            <div class="col-sm-2">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="muntahproyektil" value="ya">
                    <label class="form-check-label">Ya</label>
                </div>
            </div>

            <div class="col-sm-2">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="muntahproyekyil" value="tidak">
                    <label class="form-check-label">Tidak</label>
                </div>
            </div>
     </div>  

            <!-- Bagian Keluhan Lain -->
            <div class="row mb-3">
                <label for="keluhanlaindisability" class="col-sm-2 col-form-label"><strong>Keluhan Lain</strong></label>
                <div class="col-sm-9">
                    <textarea name="keluhanlaindisability" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentkeluhanlaindisability" id="commentkeluhanlaindisability" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>
     
            <!-- Bagian Exposure -->
                <div class="row mb-3">
                    <label class="col-sm-10 col-form-label text-primary">
                        <strong>Exposure</strong>
                </div>   

                    <!-- Bagian Deformitas -->
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Deformitas</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="diformitas" value="ya">
                            <label class="form-check-label">Ya</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="diformitas" value="tidak">
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>

                 <!-- Bagian Contusio -->
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Contusio</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="contusio" value="ya">
                            <label class="form-check-label">Ya</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="contusio" value="tidak">
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>

                 <!-- Bagian Abrasi -->
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Abrasi</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="abrasi" value="ya">
                            <label class="form-check-label">Ya</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="abrasi" value="tidak">
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>

                <!-- Bagian Penetrasi -->
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Penetrasi</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="penetrasi" value="ya">
                            <label class="form-check-label">Ya</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="penetrasi" value="tidak">
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>

                <!-- Bagian Laserasi -->
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Laserasi</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="laserasi" value="ya">
                            <label class="form-check-label">Ya</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="laserasi" value="tidak">
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>

                <!-- Bagian Edema -->
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Edema</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="edema" value="ya">
                            <label class="form-check-label">Ya</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="edema" value="tidak">
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>

                <!-- Bagian Keluhan Lain -->
                <div class="row mb-3">
                    <label for="keluhanlainairway" class="col-sm-2 col-form-label"><strong>Keluhan Lain</strong></label>
                    <div class="col-sm-9">
                       <textarea name="keluhanlainairway" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentkeluhanlainairway" id="commentkeluhanlainairway" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>

            <!-- Bagian Folley Catheter -->
                <div class="row mb-3">
                    <label class="col-sm-10 col-form-label text-primary">
                        <strong>Folley Catheter</strong>
                </div>   

                    <!-- Bagian Terpasang -->
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Terpasang</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="folleyterpasang" value="ya">
                            <label class="form-check-label">Ya</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="folleyterpasang" value="tidak">
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>

            <!-- Bagian Gastric Tube -->
                <div class="row mb-3">
                    <label class="col-sm-10 col-form-label text-primary">
                        <strong>Gastric Tube</strong>
                </div>   

                    <!-- Bagian Terpasang -->
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Terpasang</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="gastricterpasang" value="ya">
                            <label class="form-check-label">Ya</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="gastricterpasang" value="tidak">
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>    

                <!-- Bagian Heart Monitor -->
                <div class="row mb-3">
                    <label class="col-sm-10 col-form-label text-primary">
                        <strong>Heart Monitor</strong>
                </div>   

                    <!-- Bagian Terpasang -->
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Terpasang</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="heartterpasang" value="ya">
                            <label class="form-check-label">Ya</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="heartterpasang" value="tidak">
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>

            <h5 class="card-title mb-1"><strong>C. SECONDARY SURVEY</strong></h5>                
    
                <!-- Bagian Riwayat Penyakit Saat Ini -->
                <div class="row mb-3">
                    <label for="riwayatpenyakitsaatini" class="col-sm-2 col-form-label"><strong>Riwayat Penyakit Saat Ini</strong></label>
                    <div class="col-sm-9">
                       <textarea name="riwayatpenyakitsaatini" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentriwayatpenyakitsaatini" id="commentriwayatpenyakitsaatini" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>

                <!-- Bagian Alergi -->
                <div class="row mb-3">
                    <label for="alergi" class="col-sm-2 col-form-label"><strong>Alergi</strong></label>
                    <div class="col-sm-9">
                       <textarea name="alergi" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentalergi" id="commentkalergi" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>    
                      
                <!-- Bagian Medikasi -->
                <div class="row mb-3">
                    <label for="medikasi" class="col-sm-2 col-form-label"><strong>Medikasi</strong></label>
                    <div class="col-sm-9">
                       <textarea name="medikasi" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentmedikasi" id="commentmedikasi" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>   
                    
                <!-- Bagian Riwayat Penyakit Sebelumnya -->
                <div class="row mb-3">
                    <label for="riwayatpenyakitsebelumnya" class="col-sm-2 col-form-label"><strong>Riwayat Penyakit Sebelumnya</strong></label>
                    <div class="col-sm-9">
                       <textarea name="riwayatpenyakitsebelumnya" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentriwayatpenyakitsebelumnya" id="commentriwayatpenyakitsebelumnya" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 
                    
                <!-- Bagian Makan Minum Terakir -->
                <div class="row mb-3">
                    <label for="makanminumterakhir" class="col-sm-2 col-form-label"><strong>Makan Minum Terakhir</strong></label>
                    <div class="col-sm-9">
                       <textarea name="makanminumterakhir" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentmakanminumterakhir" id="commentmakanminumterakhir" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>  
                    
                <!-- Bagian Even -->
                <div class="row mb-3">
                    <label for="even" class="col-sm-2 col-form-label"><strong>Even/Peristiwa Penyebab</strong></label>
                    <div class="col-sm-9">
                       <textarea name="even" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentvene" id="commenteven" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
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
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="text" class="form-control" name="tekanandarah">
                                <span class="input-group-text">mmHg</span>
                        </div>    
                    </div>
                                
                    <!-- Nadi -->
                    <label class="col-sm-2 col-form-label"><strong>Nadi</strong></label>
                    <div class="col-sm-3">
                        <div class="input-group">
                                <input type="text" class="form-control" name="nadi">
                                <span class="input-group-text">x/menit</span>
                        </div> 
                    </div>

                    <div class="col-sm-1 d-flex align-items-start">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" disabled>
                        </div>
                    </div>
                </div>
              
                <!-- Suhu -->
                <div class="row mb-3 align-items-center">
                    <label class="col-sm-2 col-form-label"><strong>Suhu</strong></label>
                    <div class="col-sm-3">
                        <div class="input-group">
                            <input type="text" class="form-control" name="suhu">
                            <span class="input-group-text">°C</span>
                    </div>    
                </div>

                    <!-- RR -->
                    <label class="col-sm-2 col-form-label"><strong>RR</strong></label>
                    <div class="col-sm-3">
                        <div class="input-group">
                                <input type="text" class="form-control" name="rr">
                                <span class="input-group-text">x/menit</span>
                            </div>
                        </div>
                    </div>

                <!-- comment -->
                <div class="row mb-3">

                    <div class="offset-sm-2 col-sm-9">
                        <textarea class="form-control mt-2" name="commenttandavital" id="commenttandavital" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize:none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                    </div>
                </div>

            <!-- Bagian Pemeriksaan Fisik -->
                <div class="row mb-3">
                    <div class="col-sm-12 text-primary">
                        <strong>Pemeriksaan Fisik</strong>
                    </div>   

                      <div class="col-sm-12 text-primary">
                        <strong>Kepala</strong>
                    </div>   
                </div>
 
                    <!-- Bagian Pendarahan -->
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Pendarahan</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="pendarahankepala" value="ya">
                            <label class="form-check-label">Ya</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="pendarahankepala" value="tidak">
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>

                 <!-- Bagian Depresi Tulang Kepala -->
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Depresi Tulang Kepala</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="depresitulangkepala" value="ya">
                            <label class="form-check-label">Ya</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="depresitulangkepala" value="tidak">
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>

                 <!-- Bagian Laserasi -->
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Laserasi</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="laserasikepala" value="ya">
                            <label class="form-check-label">Ya</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="laserasikepala" value="tidak">
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>

                 <!-- Bagian Echymosis/Memar -->
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Echymosis/Memar</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="echymosismemar" value="ya">
                            <label class="form-check-label">Ya</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="echymosismemar" value="tidak">
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>

                 <!-- Bagian Nyeri Tekan -->
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Nyeri Tekan</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="nyeritekankepala" value="ya">
                            <label class="form-check-label">Ya</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="nyeritekankepala" value="tidak">
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>

                <!-- Bagian Keluhan Lain -->
                <div class="row mb-3">
                    <label for="keluhanlainkepala" class="col-sm-2 col-form-label"><strong>Keluhan Lain</strong></label>
                    <div class="col-sm-9">
                       <textarea name="keluhanlainkepala" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentkeluhanlainkepala" id="commentkeluhanlainkepala" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>

            <!-- Bagian Mata -->
                <div class="row mb-3">
                    <label class="col-sm-10 col-form-label text-primary">
                        <strong>Mata</strong>
                </div>   

                    <!-- Bagian Racoon Eyes -->
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Racoon Eyes</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="racooneyes" value="ya">
                            <label class="form-check-label">Ya</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="racooneyes" value="tidak">
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>

                 <!-- Bagian Pendarahan -->
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Pendarahan</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="pendarahanmata" value="ya">
                            <label class="form-check-label">Ya</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="penarahanmata" value="tidak">
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>

                 <!-- Bagian Ruptur/Robek -->
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Ruptur/Robek</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="rupturrobek" value="ya">
                            <label class="form-check-label">Ya</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="rupturrobek" value="tidak">
                            <label class="form-check-label">tidak</label>
                        </div>
                    </div>
                </div>

                 <!-- Bagian Kongjungtiva -->
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Konjungtiva</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="konjungtiva" value="anemis">
                            <label class="form-check-label">Anemis</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="konjungtiva" value="ananemis">
                            <label class="form-check-label">Ananemis</label>
                        </div>
                    </div>
                </div>

                 <!-- Bagian Sklera -->
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Sklera</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="sklera" value="ikterik">
                            <label class="form-check-label">Ikterik</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="sklera" value="anikterik">
                            <label class="form-check-label">Anikterik</label>
                        </div>
                    </div>
                </div>

                 <!-- Bagian Respon Pupil -->
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Respon Pupil</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="responpupilmata" value="isokor">
                            <label class="form-check-label">Isokor</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="responpupilmata" value="anisokor">
                            <label class="form-check-label">Anisokor</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="responpupilmata" value="midriasis">
                            <label class="form-check-label">Midriasis</label>
                        </div>
                    </div>
                </div>

                <!-- Bagian Keluhan Lain -->
                <div class="row mb-3">
                    <label for="keluhanlainmata" class="col-sm-2 col-form-label"><strong>Keluhan Lain</strong></label>
                    <div class="col-sm-9">
                       <textarea name="keluhanlainmata" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentkeluhanlainmata" id="commentkeluhanlainmata" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>

            <!-- Bagian Telinga -->
                <div class="row mb-3">
                    <label class="col-sm-10 col-form-label text-primary">
                        <strong>Telinga</strong>
                </div>   

                <!-- Bagian Cairan -->
                <div class="row mb-2">
                    <div class="col-sm-2"><strong>Cairan</strong>
                </div>  

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="cairan" value="ada">
                        <label class="form-check-label">Ada</label>
                    </div>
                </div>

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="cairan" value="tidak">
                        <label class="form-check-label">Tidak</label>
                    </div>
                </div>

                <!-- Bagian Jika Ada, Warna -->
                <div class="col-sm-10 offset-sm-2">
                    <div class="row mt-2">
                        
                    <div class="col-sm-11">
                        <label><strong>Jika ada, warna</strong></label>
                        <input type="text" class="form-control" name="jikaadawarna">

                        <textarea class="form-control mt-2" name="commentjikaadawarna" id="commentjikaadawarna" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1  mt-4 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

                    <!-- Bagian Lecet -->
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Lecet</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="lecet" value="ya">
                            <label class="form-check-label">Ya</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="lecet" value="tidak">
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>

                 <!-- Bagian Leserasi -->
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Leserasi</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="leserasi" value="ya">
                            <label class="form-check-label">Ya</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="leserasi" value="tidak">
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>

                <!-- Bagian Benda Asing -->
                <div class="row mb-2">
                    <div class="col-sm-2"><strong>Benda Asing</strong>
                </div>  

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="bendaasing" value="ada">
                        <label class="form-check-label">Ada</label>
                    </div>
                </div>

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="bendaasing" value="Tidak">
                        <label class="form-check-label">Tidak</label>
                    </div>
                </div>

                <!-- Berupa -->
                <div class="col-sm-10 offset-sm-2">
                    <div class="row mt-2">
                        
                    <div class="col-sm-11">
                        <label><strong>Berupa</strong></label>
                        <input type="text" class="form-control" name="berupa">

                        <textarea class="form-control mt-2" name="commentberupa" id="commentberupa" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1  mt-4 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bagian Keluhan Lain -->
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><strong>Keluhan Lain</strong></label>
                    
                <div class="col-sm-10">
                    <div class="row">  
                        
                <div class="col-sm-11">
                    <textarea name="keluhanlaintelinga" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentkeluhanlaintelinga" id="commentkeluhanlaintelinga" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 
                </div>
            </div>

            <!-- Bagian Hidung -->
                <div class="row mb-3">
                    <label class="col-sm-10 col-form-label text-primary">
                        <strong>Hidung</strong>
                </div>   

                <!-- Bagian Ada Cairan -->
                <div class="row mb-2">
                    <div class="col-sm-2"><strong>Ada Cairan</strong>
                </div>  

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="adacairan" value="ya">
                        <label class="form-check-label">Ya</label>
                    </div>
                </div>

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="adacairan" value="tidak">
                        <label class="form-check-label">Tidak</label>
                    </div>
                </div>

                <!-- Bagian Jika Ada, Warna -->
                <div class="col-sm-10 offset-sm-2">
                    <div class="row mt-2">
                        
                    <div class="col-sm-11">
                        <label><strong>Warna</strong></label>
                        <input type="text" class="form-control" name="warna">

                        <textarea class="form-control mt-2" name="commentwarna" id="commentwarna" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1  mt-4 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

                    <!-- Bagian Lecet -->
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Lecet</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="lecethidung" value="ya">
                            <label class="form-check-label">Ya</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="lecethidung" value="tidak">
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>

                 <!-- Bagian Kemerahan -->
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Kemerahan</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="kemerahan" value="ya">
                            <label class="form-check-label">Ya</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="kemerahan" value="tidak">
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>

                 <!-- Bagian Leserasi -->
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Leserasi</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="leserasihidung" value="ya">
                            <label class="form-check-label">Ya</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="leserasihidung" value="tidak">
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>

                <!-- Bagian Benda Asing -->
                <div class="row mb-2">
                    <div class="col-sm-2"><strong>Benda Asing</strong>
                </div>  

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="bendaasinghidung" value="ada">
                        <label class="form-check-label">Ada</label>
                    </div>
                </div>

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="bendaasinghidung" value="Tidak">
                        <label class="form-check-label">Tidak</label>
                    </div>
                </div>

                <!-- Berupa -->
                <div class="col-sm-10 offset-sm-2">
                    <div class="row mt-2">
                        
                    <div class="col-sm-11">
                        <label><strong>Berupa</strong></label>
                        <input type="text" class="form-control" name="berupahidung">

                        <textarea class="form-control mt-2" name="commentberupahidung" id="commentberupahidung" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1  mt-4 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bagian Keluhan Lain -->
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><strong>Keluhan Lain</strong></label>
                    
                <div class="col-sm-10">
                    <div class="row">  
                        
                <div class="col-sm-11">
                    <textarea name="keluhanlainhidung" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentkeluhanlainhidung" id="commentkeluhanlainhidung" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 
                </div>
            </div>
                    
            <!-- Bagian Leher -->
                <div class="row mb-3">
                    <label class="col-sm-10 col-form-label text-primary">
                        <strong>Leher</strong>
                </div>   

                <!-- Bagian Deviasi Trakea -->
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Deviasi Trakea</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="deviasitrakea" value="ya">
                            <label class="form-check-label">Ya</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="deviasitrakea" value="tidak">
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>

                 <!-- Bagian Distensi Vena Jugularis -->
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Distensi Vena Jugularis</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="distensivenajugularis" value="ya">
                            <label class="form-check-label">Ya</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="distensivenajugularis" value="tidak">
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>

                 <!-- Bagian Bengkak -->
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Bengkak</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="bengkak" value="ya">
                            <label class="form-check-label">Ya</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="bengkak" value="tidak">
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>

                 <!-- Bagian Kebiruan/Memar -->
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Kebiruan/Memar</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="kebiruanmemar" value="ya">
                            <label class="form-check-label">Ya</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="kebiruanmemar" value="tidak">
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>

                 <!-- Bagian Nyeri Tekan -->
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Nyeri Tekan</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="nyeritekanleher" value="ya">
                            <label class="form-check-label">Ya</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="nyeritekanleher" value="tidak">
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>

                 <!-- Bagian Krepitasi -->
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Krepitas</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="kretipasi" value="ya">
                            <label class="form-check-label">Ya</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="kretipasi" value="tidak">
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>

                <!-- Bagian Keluhan Lain -->
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><strong>Keluhan Lain</strong></label>
                    
                <div class="col-sm-10">
                    <div class="row">  
                        
                <div class="col-sm-11">
                    <textarea name="keluhanlainleher" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentkeluhanlainleher" id="commentkeluhanlainleher" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 
                </div>
            </div>
                    
            <!-- Bagian Dada/Paru -->
                <div class="row mb-3">
                    <label class="col-sm-10 col-form-label text-primary">
                        <strong>Dada/Paru</strong>
                </div>
                
                <!-- Bagian Bentuk Dada -->
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Bentuk Dada</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="bentukdada" value="simetris">
                            <label class="form-check-label">Simetris</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="bentukdada" value="asimetris">
                            <label class="form-check-label">Asimetris</label>
                        </div>
                    </div>
                </div>

                <!-- Bagian Laserasi/Jejas -->
                <div class="row mb-2">
                    <div class="col-sm-2"><strong>Laserasi/Jejas</strong>
                </div>  

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="laserasijejas" value="ada">
                        <label class="form-check-label">Ada</label>
                    </div>
                </div>

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="larerasijejas" value="tidak">
                        <label class="form-check-label">Tidak</label>
                    </div>
                </div>

                <!-- Bagian Ukuran Luka -->
                <div class="col-sm-10 offset-sm-2">
                    <div class="row mt-2">
                        
                    <div class="col-sm-11">
                        <label><strong>Ukuran Luka</strong></label>
                        <input type="text" class="form-control" name="ukuranluka">

                        <textarea class="form-control mt-2" name="commentukuranluka" id="commentukuranluka" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1  mt-4 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                        </div>

                    <div class="col-sm-11">
                        <label><strong>Lokasi</strong></label>
                        <input type="text" class="form-control" name="lokasi">

                        <textarea class="form-control mt-2" name="commentlokasi" id="commentlokasi" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1  mt-4 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

                <!-- Bagian Pendarahan -->
                <div class="row mb-2">
                    <div class="col-sm-2"><strong>Pendarahan</strong>
                </div>  

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="pendarahandada" value="ada">
                        <label class="form-check-label">Ada</label>
                    </div>
                </div>

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="pendarahandada" value="tidakada">
                        <label class="form-check-label">Tidak Ada</label>
                    </div>
                </div>

                <!-- Jika ada berapa banyak -->
                <div class="col-sm-10 offset-sm-2">
                    <div class="row mt-2">
                        
                    <div class="col-sm-11">
                        <label><strong>Jika Ada, Berapa Banyak</strong></label>
                        <input type="text" class="form-control" name="jikaadaberapabanyak">

                        <textarea class="form-control mt-2" name="commentjikaadaberapabanyak" id="commentjikaadaberapabanyak" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1  mt-4 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

                <!-- Bagian RR -->
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><strong>RR</strong></label>
                    
                <div class="col-sm-10">
                    <div class="row">  
                        
                <div class="col-sm-11">
                    <div class="input-group">
                        <input type="text" class="form-control" name="rr">
                        <span class="input-group-text">x/menit</span>
                    </div>

                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentrrdada" id="commentrrdada" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 
                </div>
            </div>

                <!-- Bagian Irama Napas -->
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Irama Napas</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="iramanapas" value="teratur">
                            <label class="form-check-label">Teratur</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="iramanapas" value="tidakteratur">
                            <label class="form-check-label">Tidak Teratur</label>
                        </div>
                    </div>
                </div>

                 <!-- Bagian Penggunaan Otot-otot Dinding Dada -->
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Penggunaan Otot-otot Dinding Dada</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="ototdada" value="ya">
                            <label class="form-check-label">Ya</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="ototdada" value="tidak">
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>

                 <!-- Bagian Bunyi Jantung -->
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>bunyi Jantung</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="bunyijantung" value="normal">
                            <label class="form-check-label">Normal</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="bunyijantung" value="murmur">
                            <label class="form-check-label">Murmur (Mendesis)</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="bunyijantung" value="gallop">
                            <label class="form-check-label">Gallop</label>
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="bunyijantung" value="friction">
                            <label class="form-check-label">Friction Rub/Gesekan</label>
                        </div>
                    </div>
                </div>

                <!-- Bagian Nyeri Dada -->
                <div class="row mb-2">
                    <div class="col-sm-2"><strong>Nyeri Dada</strong>
                </div>  

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="nyeridada" value="ya">
                        <label class="form-check-label">Ya</label>
                    </div>
                </div>

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="nyeridada" value="tidak">
                        <label class="form-check-label">Tidak</label>
                    </div>
                </div>

                <!-- Jika Ada Nyeri, Jelaskan -->
                <div class="col-sm-10 offset-sm-2">
                    <div class="row mt-2">
                        
                    <div class="col-sm-11">
                        <label><strong>Jika Ada Nyeri, Jelaskan</strong></label>
                        <input type="text" class="form-control" name="jikaadanyerijelaskan">

                        <textarea class="form-control mt-2" name="commentjikaadanyerijelaskan" id="commentjikaadanyerijelaskan" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1  mt-4 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bagian keluhan Lain -->
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><strong>Keluhan Lain</strong></label>
                    
                <div class="col-sm-10">
                    <div class="row">  
                        
                <div class="col-sm-11">
                    <textarea name="keluhanlaindada" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentkeluhanlaindada" id="commentkeluhanlaindada" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 
                </div>
            </div>

            <!-- Bagian Abdomen -->
                <div class="row mb-3">
                    <label class="col-sm-10 col-form-label text-primary">
                        <strong>Abdomen</strong>
                </div> 
                
                <!-- Bagian Dinding Abdomen -->
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Dinding Abdomen</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="dindingabdomen" value="simetris">
                            <label class="form-check-label">Simetris</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="dindingabdomen" value="asimetris">
                            <label class="form-check-label">Asimetris</label>
                        </div>
                    </div>
                </div>

                <!-- Bagian Pendarahan -->
                <div class="row mb-2">
                    <div class="col-sm-2"><strong>Pendarahan</strong>
                </div>  

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="pendarahanabdomen" value="ya">
                        <label class="form-check-label">Ya</label>
                    </div>
                </div>

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="cairan" value="tidak">
                        <label class="form-check-label">Tidak</label>
                    </div>
                </div>

                <!-- Bagian Jika Ada, Berapa banyak -->
                <div class="col-sm-10 offset-sm-2">
                    <div class="row mt-2">
                        
                    <div class="col-sm-11">
                        <label><strong>Jika Ada, Berapa Banyak</strong></label>
                        <input type="text" class="form-control" name="jikaadaberapabanyakabdomen">

                        <textarea class="form-control mt-2" name="commentjikaadaberapabanyakabdomen" id="commentjikaadaberapabanyakabdomen" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1  mt-4 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

                    <!-- Bagian Bengkak -->
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Bengkak</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="bengkakabdomen" value="ya">
                            <label class="form-check-label">Ya</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="begnkakabdomen" value="tidak">
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>

                 <!-- Bagian Leserasi -->
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Leserasi</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="leserasiabdomen" value="ya">
                            <label class="form-check-label">Ya</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="leserasiabdomen" value="tidak">
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>

                <!-- Bagian Distensi Abdomen -->
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Distensi Abdomen</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="distensiabdomen" value="ya">
                            <label class="form-check-label">Ya</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="distensiabdomen" value="tidak">
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>

                <!-- Bagian Bising Usus -->
                <div class="row mb-2">
                    <div class="col-sm-2"><strong>Bising Usus</strong>
                </div>  

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="bisingusus" value="ada">
                        <label class="form-check-label">Ada</label>
                    </div>
                </div>

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="bisingusus" value="Tidak">
                        <label class="form-check-label">Tidak</label>
                    </div>
                </div>

                <!-- Jika Ada, Berapa Kali -->
                <div class="col-sm-10 offset-sm-2">
                    <div class="row mt-2">
                        
                    <div class="col-sm-11">
                        <label><strong>Jika Ada, Berapa Kali</strong></label>
                        <input type="text" class="form-control" name="jikadaaberapakali">

                        <textarea class="form-control mt-2" name="commentjikaadaberapakali" id="commentjikaadaberapakali" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1  mt-4 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bagian Nyeri Tekan -->
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Nyeri Tekan</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="nyeritekanabdomen" value="ya">
                            <label class="form-check-label">Ya</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="nyeritekanabdomen" value="tidak">
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>

                <!-- Bagian Keluhan Lain -->
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><strong>Keluhan Lain</strong></label>
                    
                <div class="col-sm-10">
                    <div class="row">  
                        
                <div class="col-sm-11">
                    <textarea name="keluhanlainabdomen" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentkeluhanlainabdomen" id="commentkeluhanlainabdomen" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 
                </div>
            </div>

            <!-- Bagian Ekstremitas Atas dan Bawah -->
                <div class="row mb-3">
                    <label class="col-sm-10 col-form-label text-primary">
                        <strong>Eksremitas Atas dan Bawah</strong>
                </div>   

                <!-- Bagian Teraba Benjolan/Keras -->
                <div class="row mb-2">
                    <div class="col-sm-2"><strong>Teraba Benjolan/Keras</strong>
                </div>  

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="terababenjolankeras" value="ya">
                        <label class="form-check-label">Ya</label>
                    </div>
                </div>

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="terababenjolankeras" value="tidak">
                        <label class="form-check-label">Tidak</label>
                    </div>
                </div>

                <!-- Bagian Jika Ada, Ukuran -->
                <div class="col-sm-10 offset-sm-2">
                    <div class="row mt-2">
                        
                    <div class="col-sm-11">
                        <label><strong>Jika Ada, Ukuran</strong></label>
                            <div class="input-group">
                            <input type="text" class="form-control" name="jikaadaukuran">
                            <span class="input-group-text">cm</span>
                        </div>

                        <textarea class="form-control mt-2" name="commentjikaadaukuran" id="commentjikaadaukuran" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1  mt-4 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                        </div>

                    <div class="col-sm-11">
                        <label><strong>Lokasi</strong></label>
                        <input type="text" class="form-control" name="lokasibenjolan">

                        <textarea class="form-control mt-2" name="commentlokasibenjolan" id="commentlokasibenjolan" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1  mt-4 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

                <!-- Bagian Pendarahan -->
                <div class="row mb-2">
                    <div class="col-sm-2"><strong>Pendarahan</strong>
                </div>  

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="pendarahanekstremitas" value="ya">
                        <label class="form-check-label">Ya</label>
                    </div>
                </div>

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="pendarahanekstremitas" value="tidak">
                        <label class="form-check-label">Tidak</label>
                    </div>
                </div>

                <!-- Bagian Lokasi -->
                <div class="col-sm-10 offset-sm-2">
                    <div class="row mt-2">
                        
                    <div class="col-sm-11">
                        <label><strong>Lokasi</strong></label>
                        <input type="text" class="form-control" name="lokasiekstremitas">

                        <textarea class="form-control mt-2" name="commentlokasiekstremitas" id="commentlokasiekstremitas" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1  mt-4 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                        </div>

                    <div class="col-sm-11">
                        <label><strong>Jumlah</strong></label>
                        <input type="text" class="form-control" name="jumlah">

                        <textarea class="form-control mt-2" name="commentjumlah" id="commentjumlah" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1  mt-4 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

                    <!-- Bagian Edema -->
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Edema</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="edemaekstremitas" value="ya">
                            <label class="form-check-label">Ya</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="edemaekstremitas" value="tidak">
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>

                 <!-- Bagian Nyeri Tekan -->
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Nyeri Tekan</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="nyeritekanekstremitas" value="ya">
                            <label class="form-check-label">Ya</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="nyeritekanekstremitas" value="tidak">
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>

                <!-- Bagian Fraktur -->
                <div class="row mb-2">
                    <div class="col-sm-2"><strong>Fraktur</strong>
                </div>  

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="fraktur" value="ya">
                        <label class="form-check-label">Ya</label>
                    </div>
                </div>

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="fraktur" value="Tidak">
                        <label class="form-check-label">Tidak</label>
                    </div>
                </div>

                <!-- Lokasi Fraktur -->
                <div class="col-sm-10 offset-sm-2">
                    <div class="row mt-2">
                        
                    <div class="col-sm-11">
                        <label><strong>Lokasi Fraktur</strong></label>
                        <input type="text" class="form-control" name="lokasifraktur">

                        <textarea class="form-control mt-2" name="commentlokasifraktur" id="commentlokasifraktur" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1  mt-4 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

                 <!-- Bagian Kekakuan Pada Persendian Ekstremitas -->
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Kekakuan Pada Persendian Ekstremitas</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="kekakuan" value="ya">
                            <label class="form-check-label">Ya</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="kekakuan" value="tidak">
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>

                 <!-- Bagian Keterbatas Gerak -->
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Keterbatasan Gerak</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="keterbatasangerak" value="ya">
                            <label class="form-check-label">Ya</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="keterbatasangerak" value="tidak">
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>

                <!-- Bagian Kekuatan Otot -->
                 <div class="row mb-3">
                    <label class="col-sm-10 col-form-label">
                        <strong>Kekuatan Otot</strong>
                    </label> 
                    <div class="col-sm-10"></div>
                </div>
                
                <!-- Ekstremitas Atas -->
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><strong>Ekstremitas Atas</strong></label>
                    
                <div class="col-sm-10">
                    <div class="row">  
                        
                <div class="col-sm-11">
                    <textarea name="ekstremitasatas" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentekstremitasatas" id="commentekstremitasatas" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 
                </div>
            </div>

            <!-- Ekstremitas Bawah -->

            <div class="row mb-3">
                <label class="col-sm-2 col-form-label"><strong>Ekstremitas Bawah</strong></label>
                    
                <div class="col-sm-10">
                    <div class="row">  
                        
                <div class="col-sm-11">
                    <textarea name="ekstremitasbawah" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentekstremitasbawah" id="commentekstremitasbawah" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 
                </div>
            </div>

                <!-- Bagian Keluhan Lain -->
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><strong>Keluhan Lain</strong></label>
                    
                <div class="col-sm-10">
                    <div class="row">  
                        
                <div class="col-sm-11">
                    <textarea name="keluhanlainekstremitas" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentkeluhanlainekstremitas" id="commentkeluhalainekstremitas" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 
                </div>
            </div>

            <!-- Bagian Punggung -->
                <div class="row mb-3">
                    <label class="col-sm-10 col-form-label text-primary">
                        <strong>Punggung</strong>
                </div>   

                <!-- Bagian Terdapat Luka -->
                <div class="row mb-2">
                    <div class="col-sm-2"><strong>Terdapat Luka</strong>
                </div>  

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="terdapatluka" value="ya">
                        <label class="form-check-label">Ya</label>
                    </div>
                </div>

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="terdapatluka" value="tidak">
                        <label class="form-check-label">Tidak</label>
                    </div>
                </div>

                <!-- Bagian Ukuran -->
                <div class="col-sm-10 offset-sm-2">
                    <div class="row mt-2">
                        
                    <div class="col-sm-11">
                        <label><strong>Ukuran</strong></label>
                            <div class="input-group">
                            <input type="text" class="form-control" name="ukuranluka">
                            <span class="input-group-text">cm</span>
                    </div>

                        <textarea class="form-control mt-2" name="commentukuranluka" id="commentukuranluka" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1  mt-4 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bagian Decubitus -->
                <div class="row mb-2">
                    <div class="col-sm-2"><strong>Decubitus</strong>
                </div>  

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="decubituspunggung" value="ada">
                        <label class="form-check-label">Ada</label>
                    </div>
                </div>

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="decubituspunggung" value="tidakada">
                        <label class="form-check-label">Tidak Ada</label>
                    </div>
                </div>

                <!-- Bagian Ukuran -->
                <div class="col-sm-10 offset-sm-2">
                    <div class="row mt-2">
                        
                    <div class="col-sm-11">
                        <label><strong>Ukuran</strong></label>
                            <input type="text" class="form-control" name="ukurandecubituspunggung">

                        <textarea class="form-control mt-2" name="commentukurandecubitusspunggung" id="commentukurandecubituspunggung" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1  mt-4 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                        </div>
                </div>
            </div>

                    <!-- Bagian Echymosis/Lebam -->
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Echymosis/Lebam</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="echymosislebampunggung" value="ada">
                            <label class="form-check-label">Ada</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="echymosislebampunggung" value="tidakada">
                            <label class="form-check-label">Tidak Ada</label>
                        </div>
                    </div>
                </div>

                 <!-- Bagian Gatal-gatal -->
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Gatal-gatal</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="gatalgatal" value="ya">
                            <label class="form-check-label">Ya</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="gatalgatal" value="tidak">
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>
                
                <!-- Bagian Keluhan Lain -->
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><strong>Keluhan Lain</strong></label>
                    
                <div class="col-sm-10">
                    <div class="row">  
                        
                <div class="col-sm-11">
                    <textarea name="keluhanlainpunggung" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentkeluhanlainpunggung" id="commentkeluhanlainpunggung" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 
                </div>
            </div>
                    
            <!-- Bagian Kulit -->
                <div class="row mb-3">
                    <label class="col-sm-10 col-form-label text-primary">
                        <strong>Kulit</strong>
                </div>   

                <!-- Bagian Warna -->
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><strong>Warna</strong></label>
                    
                <div class="col-sm-10">
                    <div class="row">  
                        
                <div class="col-sm-11">
                    <textarea name="kulit" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentkulit" id="commentkulit" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 
                </div>
            </div>

                 <!-- Bagian Turgor -->
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Turgor</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="turgor" value="elastis">
                            <label class="form-check-label">Elastis</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="turgor" value="menurun">
                            <label class="form-check-label">Menurun</label>
                        </div>
                    </div>
                </div>

                 <!-- Bagian Keadaan -->
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Keadaan</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="keadaan" value="lembab">
                            <label class="form-check-label">Lembab</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="keadaan" value="kering">
                            <label class="form-check-label">Kering</label>
                        </div>
                    </div>
                </div>

                <!-- Bagian Edema -->
                <div class="row mb-2">
                    <div class="col-sm-2"><strong>Edema</strong>
                </div>  

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="edemakulit" value="ya">
                        <label class="form-check-label">Ya</label>
                    </div>
                </div>

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="edemakulit" value="tidak">
                        <label class="form-check-label">Tidak</label>
                    </div>
                </div>

                <!-- Bagian Lokasi -->
                <div class="col-sm-10 offset-sm-2">
                    <div class="row mt-2">
                        
                    <div class="col-sm-11">
                        <label><strong>lokasi</strong></label>
                        <input type="text" class="form-control" name="lokasiedemakulit">

                        <textarea class="form-control mt-2" name="commentlokasiedemakulit" id="commentlokasiedemakulit" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1  mt-4 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

                <!-- Bagian Luka -->
                <div class="row mb-2">
                    <div class="col-sm-2"><strong>Luka</strong>
                </div>  

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="lukakulit" value="ya">
                        <label class="form-check-label">Ya</label>
                    </div>
                </div>

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="lukakulit" value="tidak">
                        <label class="form-check-label">Tidak</label>
                    </div>
                </div>

                <!-- Bagian Lokasi -->
                <div class="col-sm-10 offset-sm-2">
                    <div class="row mt-2">

                    <div class="col-sm-11">
                        <label><strong>Lokasi</strong></label>
                        <input type="text" class="form-control" name="lokasilukakulit">

                        <textarea class="form-control mt-2" name="commentlokasilukakulit" id="commentlokasilukakulit" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1  mt-4 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

                <!-- Bagian Karakteristik Luka -->
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><strong>Karakteristik Luka</strong></label>
                    
                <div class="col-sm-10">
                    <div class="row">  
                        
                <div class="col-sm-11">
                    <textarea name="karakteristikluka" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentkarakteristikluka" id="commentkarakteristikluka" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 
                </div>
            </div>

                <!-- Bagian Keluhan Lain -->
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><strong>Keluhan Lain</strong></label>
                    
                <div class="col-sm-10">
                    <div class="row">  
                        
                <div class="col-sm-11">
                    <textarea name="keluhanlainkulit" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentkeluhanlainkulit" id="commentkeluhanlainkulit" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 
                </div>
            </div>
                    
            <!-- Bagian Genitalia -->
                <div class="row mb-3">
                    <label class="col-sm-10 col-form-label text-primary">
                        <strong>Genitalia</strong>
                </div>   

                 <!-- Bagian Radang -->
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Radang</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="radanggenitalia" value="ya">
                            <label class="form-check-label">Ya</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="radanggenitalia" value="tidak">
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>

                 <!-- Bagian Pembengkakan Skrotum -->
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Pembengkakan Skrotum</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="pembengkakanskrotum" value="ya">
                            <label class="form-check-label">Ya</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="pembengkakanskrotum" value="tidak">
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>

                 <!-- Bagian Lesi -->
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Lesi</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="lesi" value="ya">
                            <label class="form-check-label">Ya</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="lesi" value="tidak">
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>

                <!-- Bagian Keluhan Lain -->
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><strong>Keluhan Lain</strong></label>
                    
                <div class="col-sm-10">
                    <div class="row">  
                        
                <div class="col-sm-11">
                    <textarea name="keluhanlaingenitalia" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentkeluhanlaingenitalia" id="commentkeluhanlaingenitalia" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 
                </div>
            </div>
        </div>

        <h5 class="card-title mb-1"><strong>D. PEMERIKSAAN PENUNJANG</strong></h5> 
        
            <!-- Laboratorium -->
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label"><strong>Laboratorium</strong></label>

                <div class="col-sm-9">
                    <input type="date" class="form-control" name="tgllaboratorium">
                            
                    <!-- comment -->
                    <textarea class="form-control mt-2" name="commenthasillaboratorium" id="commenthasillaboratorium" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                        oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                    </div>

                    <div class="col-sm-1 mt-5 d-flex align-items-start">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" disabled>
                        </div>
                    </div>
                </div> 

            <!-- Bagian Pemeriksaan -->
            <div class="row mb-3">
                <label for="pemeriksaan" class="col-sm-2 col-form-label"><strong>Pemeriksaan</strong></label>
                    <div class="col-sm-9">
                        <textarea name="pemeriksaan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" name="commentpemeriksaan" id="commentpemeriksaan" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 

            <!-- Bagian Hasil -->
            <div class="row mb-3">
                <label for="hasil" class="col-sm-2 col-form-label"><strong>Hasil</strong></label>
                    <div class="col-sm-9">
                        <textarea name="hasil" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" name="commenthasil" id="commenthasil" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 
                    
            <!-- Bagian Satuan -->
            <div class="row mb-3">
                <label for="satuan" class="col-sm-2 col-form-label"><strong>Satuan</strong></label>
                    <div class="col-sm-9">
                        <textarea name="satuan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" name="commentsatuan" id="commentsatuan" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>  
                    
            <!-- Bagian Nilai Rujukan -->
            <div class="row mb-3">
                <label for="nilairujukan" class="col-sm-2 col-form-label"><strong>Nilai Rujukan</strong></label>
                    <div class="col-sm-9">
                        <textarea name="nilairujukan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" name="commentnilairujukan" id="commentnilairujukan" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
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
                                <th class="text-center">Pemeriksaan</th>
                                <th class="text-center">Hasil</th>
                                <th class="text-center">Satuan</th>
                                <th class="text-center">Nilai Rujukan</th>
                        </tr>
                        </thead>

                    <tbody>

                    <?php
                    if(!empty($data)){
                        foreach($data as $row){
                            echo "<tr>
                            <td>".nlrbr($row['pemeriksaan'])."</td>
                            <td>".nlrbr($row['hasil'])."</td>
                            <td>".nlrbr($row['satuan'])."</td>
                            <td>".nlrbr($row['nilairujukan'])."</td>
                            </tr>";
                        }
                    }
                    ?>

                    </tbody>
                    </table>

                <!-- Radiologi -->
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><strong>Radiologi</strong></label>

                        <div class="col-sm-9">
                            <input type="date" class="form-control" name="tglradiologi">
                            <small class="form-text" style="color: red;"> Hasil:</small>
                            <textarea name="radiologi" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" name="commentradiologi" id="commentradiologi" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 mt-5 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>
                    
        <h5 class="card-title mb-1"><strong>E. TERAPI DAN OBAT</strong></h5>
                    
            <!-- Bagian Nama Obat -->
            <div class="row mb-3">
                <label for="jenisobat" class="col-sm-2 col-form-label"><strong>Nama Obat</strong></label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="namaobat">
                            
                            <!-- comment -->
                                <textarea class="form-control mt-2" name="commentnamaobat" id="commentnamaobat" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                            </div>

                            <div class="col-sm-1 d-flex align-items-start">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" disabled>
                                </div>
                            </div>
                        </div>

            <!-- Bagian Dosis -->
            <div class="row mb-3">
                <label for="dosis" class="col-sm-2 col-form-label"><strong>Dosis</strong></label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="dosis">

                            <!-- comment -->
                                <textarea class="form-control mt-2" id="commentdosis" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                            </div>

                            <div class="col-sm-1 d-flex align-items-start">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" disabled>
                                </div>
                            </div>
                        </div>
                        
            <!-- Bagian Rute Pemberian -->
            <div class="row mb-3">
                <label for="rutepemberian" class="col-sm-2 col-form-label"><strong>Rute Pemberian</strong></label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="rutepemberian">
                            
                            <!-- comment -->
                                <textarea class="form-control mt-2" name="commentrutepemberian" id="commentrutepemberian" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                            </div>

                            <div class="col-sm-1 d-flex align-items-start">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" disabled>
                                </div>
                            </div>
                        </div>
                    
            <!-- Bagian Berapa Kali Pemberian/hri -->
            <div class="row mb-3">
                <label for="pemberianobat" class="col-sm-2 col-form-label"><strong>Berapa Kali Pemberian/hari</strong></label>
                    <div class="col-sm-9">
                        <textarea name="pemberianobat" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                        <!-- comment -->
                                <textarea class="form-control mt-2" name="commentpemberianobat" id="commentpemberianobat" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
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
                        
                        <h5 class="card-title mt-2"><strong>Pemberian Obat</strong></h5>

                        <style>
                        .table-pemberianobat {
                            table-layout: fixed;
                            width:100%
                        }

                        .table-pemberianobat td,
                        .table-pemberianobat th {
                            word-wrap: break-word;
                            white-space: normal;
                            vertical-align: top;
                        }
                        </style>

                        <table class="table table-bordered table-pemberianobat">
                            <thead>
                                <tr>
                                    <th class="text-center">Nama Obat</th>
                                    <th class="text-center">Dosis</th>
                                    <th class="text-center">Rute Pemberian</th>
                                    <th class="text-center">Berapa Kali Pemberian/hari</th>
                            </tr>
                            </thead>

                        <tbody>

                        <?php
                        if(!empty($data)){
                            foreach($data as $row){
                                echo "<tr>
                                <td>".nlrbr($row['namaobat'])."</td>
                                <td>".nlrbr($row['dosis'])."</td>
                                <td>".nlrbr($row['rutepemberian'])."</td>
                                <td>".nlrbr($row['pemberianobat'])."</td>
                                </tr>";
                            }
                        }
                        ?>

                        </tbody>
                        </table>
                 
                <!-- Bagian Klasifikasi Data -->    

                <div class="row mb-2">
                    <label class="col-sm-6 col-form-label text-primary">
                        <strong>Klasifikasi Data</strong>
                </div>

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

