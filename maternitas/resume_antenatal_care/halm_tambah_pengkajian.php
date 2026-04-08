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
                
                <!-- Bagian Inisial Pasien -->
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
                    $jenismaternitas = $_GET['jenismaternitas'] ?? '';
                ?>

                <div class="row mb-3">
                    <label for="jenismaternitas" class="col-sm-2 col-form-label"><strong>Maternitas</strong></label>
                        <div class="col-sm-9">

                        <select class="form-select" name="jenismaternitas"
                        onchange="window.location=this.value" required>

                        <option value="">Pilih</option>

                        <option value="index.php?page=maternitas/pengkajian_antenatal_care&jenismaternitas=antenatal"
                        <?= $jenismaternitas == 'antenatal' ? 'selected' : '' ?>>
                        Pengkajian Antenatal Care
                        </option>

                        <option value="index.php?page=maternitas/pengkajian_pascapartum&jenismaternitas=pascapartum"
                        <?= $jenismaternitas == 'pascapartum' ? 'selected' : '' ?>>
                        Pengkajian Pascapartum
                        </option>

                        <option value="index.php?page=maternitas/resume_antenatal_care&jenismaternitas=resume"
                        <?= $jenismaternitas == 'resume' ? 'selected' : '' ?>>
                        Resume Antenatal Care
                        </option>

                        <option value="index.php?page=maternitas/pengkajian_inranatal_care&jenismaternitas=inranatal"
                        <?= $jenismaternitas == 'inranatal' ? 'selected' : '' ?>>
                        Pengkajian Inranatal Care
                        </option>

                        <option value="index.php?page=maternitas/pengkajian_ginekologi&jenismaternitas=ginekologi"
                        <?= $jenismaternitas == 'ginekologi' ? 'selected' : '' ?>>
                        Pengkajian Ginekologi
                        </option>

                        </select>
                        <div class="invalid-feedback">
                            Harap isi Jenis Maternitas.
                        </div>
                    </div>
                </div>

             </div>
    </div>


    <div class="pagetitle">
        <h1><strong>Resume Antenal Care Keperawatan Maternitas</strong></h1>
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
        href="index.php?page=maternitas/resume_antenatal_care&tab=pengkajian">
        Pengkajian
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'diagnosa_keperawatan' ? 'active' : '' ?>"
        href="index.php?page=maternitas/resume_antenatal_care&tab=diagnosa_keperawatan">
        Diagnosa Keperawatan
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'intervensi_keperawatan' ? 'active' : '' ?>"
        href="index.php?page=maternitas/resume_antenatal_care&tab=intervensi_keperawatan">
        Intervensi Keperawatan
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'implementasi_keperawatan' ? 'active' : '' ?>"
       href="index.php?page=maternitas/resume_antenatal_care&tab=implementasi_keperawatan">
        Implementasi Keperawatan
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'evaluasi_keperawatan' ? 'active' : '' ?>"
        href="index.php?page=maternitas/resume_antenatal_care&tab=evaluasi_keperawatan">
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
            
                <!-- General Form Elements -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

                <h5 class="card-title"><strong>DATA DEMOGRAFI</strong></h5>

                <!-- General Form Elements -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">
                
                    <!-- Bagian Inisial Pasien -->

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Inisial Pasien</strong></label>

                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="inisialpasien">

                        <!-- comment -->
                            <textarea class="form-control mt-2" id="commentinisialpasien" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>

                <!-- Bagian Usia -->
                <div class="row mb-3">
                    <label for="usiai" class="col-sm-2 col-form-label"><strong>Usia</strong></label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="usia">

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

                <!-- Bagian Pekerjaan -->
                <div class="row mb-3">
                    <label for="pekerjaan" class="col-sm-2 col-form-label"><strong>Pekerjaan</strong></label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="pekerjaan">

                        <!-- comment -->
                            <textarea class="form-control mt-2" id="commentpekerjaan" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
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
                            <textarea class="form-control mt-2" id="commentpendidikan" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
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
                            <textarea class="form-control mt-2" id="commentagama" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>

                <!-- Bagian Suku Bangsa -->
                <div class="row mb-3">
                    <label for="sukubangsa" class="col-sm-2 col-form-label"><strong>Suku Bangsa</strong></label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="sukubangsa">
                        
                        <!-- comment -->
                            <textarea class="form-control mt-2" id="commentsukubangsa" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
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
                            <textarea class="form-control mt-2" id="commentstatusperkawinan" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
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
                    <label for="keterangan" class="col-sm-2 col-form-label"><strong>Alamat</strong></label>
                    <div class="col-sm-9">
                        <textarea name="keterangan" class="form-control" rows="5" cols="30"></textarea>
                        
                        <!-- comment -->
                            <textarea class="form-control mt-2" id="commentalamat" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
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

        <div class="card">
            <div class="card-body">
                
              <h5 class="card-title mb-1"><strong>Pengkajian</strong></h5>

                <!-- Bagian Kepala dan Rambut -->

                    <div class="row mb-2">
                        <label class="col-sm-2 col-form-label text-primary">
                            <strong>a. Anamnesa</strong>
                    </div>
                    
                    <!-- HPHT -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>NPHT</strong></label>

                        <div class="col-sm-9">
                            <textarea name="hpht" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commenthpht" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>        

                    <!-- Status Gravida -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Status Gravida</strong></label>
                    </div>

                    <!-- Bagian G -->

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>G</strong></label>

                        <div class="col-sm-9">
                            <textarea name="g" class="form-control" rows="2"></textarea>
                    
                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentg" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>
                    
                 <!-- Bagian P -->

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>P</strong></label>

                        <div class="col-sm-9">
                            <textarea name="p" class="form-control" rows="2"></textarea>
                    
                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentp" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>   
                    
                 <!-- Bagian A -->

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>A</strong></label>

                        <div class="col-sm-9">
                            <textarea name="a" class="form-control" rows="2"></textarea>
                    
                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commenta" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>  
                    
             <!-- Usia Kehamilan -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Usia Kehamilan</strong></label>

                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="usiakehamilan">
                            
                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentusiakehamilan" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 
                    
             <!-- Tapsiran Partus -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Tapsiran Partus</strong></label>

                        <div class="col-sm-9">
                            <textarea name="tapsiranpartus" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commenttapsiranpartus" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>  
                    
             <!-- Riwayat Imunisasi TT (Saat Ini) -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Riwayat Imunisasi TT (Saat Ini)</strong></label>

                        <div class="col-sm-9">
                            <textarea name="riwayatimunisasi" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentriwayatimunisasi" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 
                    
             <!-- Riwayat Kehamilan Saat Ini -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Riwayat Kehamilan Saat Ini</strong></label>

                        <div class="col-sm-9">
                            <textarea name="riwayatkehamilan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentriwayatkehamilan" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 
                    
             <!-- Riwayat Penyakit Ibu dan Keluarga -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Riwayat Penyakit Ibu dan Keluarga</strong></label>

                        <div class="col-sm-9">
                            <textarea name="riwayatpenyakit" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentriwayatpenyakit" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
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
            
          <div class="card">
            <div class="card-body">

                <div class="row mb-2">
                        <label class="col-sm-8 col-form-label text-primary">
                            <strong>b. Pemeriksaan Antropometri</strong>
                    </div>

                <!-- General Form Elements -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">
                
                <!-- TB -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>TB</strong></label>

                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="tb">
                            
                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commenttb" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 
                
                <!-- BB -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>BB</strong></label>

                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="bb">
                            
                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentbb" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 
                    
                <!-- LILA -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>LILA</strong></label>

                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="lila">
                            
                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentlila" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
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
                
            <div class="card">
            <div class="card-body">

                <div class="row mb-2">
                        <label class="col-sm-8 col-form-label text-primary">
                            <strong>c. Tanda-tanda Vital</strong>
                    </div>

                <!-- General Form Elements -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">
                        
                <!-- Tekanan Darah -->
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-2 col-form-label"><strong>Tekanan Darah</strong></label>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="text" class="form-control" name="tekanandarah">
                        </div>    
                    </div>
                                
                    <!-- Nadi -->
                    <label class="col-sm-2 col-form-label"><strong>Nadi</strong></label>
                    <div class="col-sm-3">
                        <div class="input-group">
                                <input type="text" class="form-control" name="nadi">
                        </div> 
                    </div>
                </div>

                    <!-- Suhu -->
                        <div class="row mb-3 align-items-center">
                            <label class="col-sm-2 col-form-label"><strong>Suhu</strong></label>
                            <div class="col-sm-3">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="suhu">
                            </div>    
                        </div>

                    <!-- Pernapasan -->
                    <label class="col-sm-2 col-form-label"><strong>Pernapasan</strong></label>
                    <div class="col-sm-3">
                        <div class="input-group">
                                <input type="text" class="form-control" name="pernapasan">
                            </div>
                        </div>
                    </div>
                        
                <!-- comment -->
                <div class="row mb-3">

                    <div class="offset-sm-2 col-sm-9">
                        <textarea class="form-control mt-2" id="commenttandavital" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize:none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            readonly></textarea>
                    </div>

                    <div class="col-sm-1 d-flex align-items-start">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" disabled>
                        </div>
                    </div>
</div>
</div>
</div>

            <div class="card">
            <div class="card-body">

                <div class="row mb-2">
                        <label class="col-sm-8 col-form-label text-primary">
                            <strong>e. Pemeriksaan Fisik</strong>
                    </div>

                <!-- General Form Elements -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">            
                
                <!-- Bagian Wajah -->

                    <div class="row mb-2">
                        <label class="col-sm-2 col-form-label text-primary">
                            <strong>Wajah</strong>
                    </div>
                    
                    <!-- Inspeksi -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Wajah</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Adakah pembengkakan, hiperpigmentasi/cloasma gravidarum, area jika ada cloasma. Hasil:</small>
                            <textarea name="inspeksiwajah" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentinspeksiwajah" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 mt-4 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 

                <!-- Bagian Mata -->

                    <div class="row mb-2">
                        <label class="col-sm-2 col-form-label text-primary">
                            <strong>Mata</strong>
                    </div>
                    
                    <!-- Inspeksi -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Konjungtiva</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Apakah anemis/an-anemis. Hasil:</small>
                           <textarea name="inspeksikonjungtiva" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentinspeksikonjungtiva" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 

                    <!-- Inspeksi Sklera -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Sklera</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Ikterik/An-ikterik. Hasil:</small>
                           <textarea name="inspeksisklera" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentinspeksisklera" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>   

                    <!-- Bagian Mulut -->

                    <div class="row mb-2">
                        <label class="col-sm-2 col-form-label text-primary">
                            <strong>Mulut</strong>
                    </div>

                    <!-- Inspeksi Gigi -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Gigi Gigi</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Amati jumlah, warna, kebersihan, karies. Hasil:</small>
                            <textarea name="inspeksigigi" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentinspeksigigi" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 

                    <!-- Inspeksi Gusi -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Gusi</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Adakah atau tidak lesi/pembengkakan? Hasil:</small>
                            <textarea name="inspeksigusi" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentinspeksigusi" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 

                     <!-- Keluhan / Masalah Khusus -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Keluhan/Masalah Khusus</strong></label>

                            <div class="col-sm-9">
                                <textarea name="masalahkhususmulut" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentmasalahkhususmulut" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>  
                                    
                     <!-- Bagian Leher -->

                    <div class="row mb-2">
                        <label class="col-sm-2 col-form-label text-primary">
                            <strong>Leher</strong>
                    </div>
                    
                    <!-- Inspeksi -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Leher</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Adakah Distensi vena jugularis/tidak ada. Hasil:</small>
                            <textarea name="inspeksileher" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentinspeksileher" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 mt-5 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 
        
                    <!-- Bagian Dada -->

                    <div class="row mb-2">
                        <label class="col-sm-8 col-form-label text-primary">
                            <strong>Dada; Sistem Pernapasan dan Kardiovaskuler</strong>
                    </div>
                    
                    <!-- Auskultasi Bunyi Napas-->
                  
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Auskultasi</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Bunyi Napas. Hasil:</small>
                            <textarea name="bunyinapas" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentbunyinapas" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 mt-4 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 
                    
                    <!-- Auskultasi Suara Jantung -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Suara Jantung (Apakah ada mur-mur dan gallop). Hasil:</small>
                            <textarea name="suarajantung" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentsuarajantung" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 mt-4 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>  

                     <!-- Masalah Khusus -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Masalah Khusus</strong></label>

                            <div class="col-sm-9">
                                <textarea name="masalahkhususdada" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentmasalahkhususdada" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>
                 
                    <!-- Bagian Payudara -->

                    <div class="row mb-2">
                        <label class="col-sm-2 col-form-label text-primary">
                            <strong>Payudara</strong>
                    </div>
                    
                    <!-- Payudara -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Payudara</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Hyperpigmentasi pada areola mammae, pengeluaran cairan:</small>
                            <textarea name="payudara" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentpayudara" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 mt-4 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 

                    <!--  Puting -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Puting</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Bentuk puting:</small>
                            <textarea name="puting" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="puting" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 mt-4 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 

                     <!-- Masalah Khusus -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Masalah Khusus</strong></label>

                            <div class="col-sm-9">
                                <textarea name="masalahkhususpayudara" class="form-control" rows="5" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
                        <!-- comment -->
                            <textarea class="form-control mt-2" id="commentmasalahkhususpayudara" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>
                            
                <!-- Bagian Abdomen -->

                    <div class="row mb-3">
                        <label class="col-sm-9 col-form-label text-primary">
                            <strong>Abdomen</strong>
                        </label>    
                    </div>

                    
                    <!-- Linea Nigra -->
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-2 col-form-label"><strong>Linea Nigra</strong></label>
                        <div class="col-sm-3">
                            <select class="form-select" name="lineanigra">
                            <option value="">Pilih</option>
                            <option value="Ya">Ya</option>
                            <option value="Tidak">Tidak</option> 
                        </select>  
                    </div>
                                
                    <!-- Linea Alba -->
                    <label class="col-sm-2 col-form-label"><strong>Linea Alba</strong></label>
                    <div class="col-sm-3">
                        <select class="form-select" name="inspeksikontraksi">
                            <option value="">Pilih</option>
                            <option value="Ya">Ya</option>
                            <option value="Tidak">Tidak</option> 
                        </select>
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

                <!-- Keadaan Dinding Perut -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Keadaan Dinding Perut</strong></label>

                            <div class="col-sm-9">
                                <textarea name="dindingperut" class="form-control" rows="5" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
                        <!-- comment -->
                            <textarea class="form-control mt-2" id="commentdindingperut" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>
                     

                    <!-- TFU -->
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-2 col-form-label"><strong>TFU</strong></label>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="text" class="form-control" name="inspeksitfu">
                                <span class="input-group-text">cm</span>
                        </div>    
                    </div>
                                
                    <!-- Kontraksi -->
                    <label class="col-sm-2 col-form-label"><strong>Kontraksi</strong></label>
                    <div class="col-sm-3">
                        <select class="form-select" name="inspeksikontraksi">
                            <option value="">Pilih</option>
                            <option value="Ya">Ya</option>
                            <option value="Tidak">Tidak</option> 
                        </select>
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

                    <!-- Leopold I -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Leopold I</strong></label>

                        <div class="col-sm-9">
                            <select class="form-select" name="leopoldi">
                                <option value="">Pilih</option>
                                <option value="Kepala">Kepala</option>
                                <option value="Bokong">Bokong</option>
                                <option value="Kosong">Kosong</option>
                            </select>
                        
                        <!-- comment -->
                            <textarea class="form-control mt-2" id="commentleopoldi" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>

                    <!-- Leopold II -->

                    <div class="row mb-2">
                        <label class="col-sm-2 col-form-label">
                            <strong>Leopold II</strong>
                    </div>
                    
                    <!-- Kanan -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Kanan</strong></label>

                       <div class="col-sm-9">
                            <select class="form-select" name="kanan">
                                <option value="">Pilih</option>
                                <option value="Punggung">Punggung</option>
                                <option value="Bagian Kecil">Bagian Kecil</option>
                                <option value="Kepala">Kepala</option>
                            </select>
                    
                    <!-- comment -->
                            <textarea class="form-control mt-2" id="commentkanan" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>

                    <!-- Kiri -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Kiri</strong></label>

                       <div class="col-sm-9">
                            <select class="form-select" name="kiri">
                                <option value="">Pilih</option>
                                <option value="Punggung">Punggung</option>
                                <option value="Bagian Kecil">Bagian Kecil</option>
                                <option value="Kepala">Kepala</option>
                            </select>
                            
                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentkanan" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>        

                    <!-- Leopold III -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Leopold III</strong></label>

                       <div class="col-sm-9">
                            <select class="form-select" name="leopoldiii">
                                <option value="">Pilih</option>    
                                <option value="Kepala">Kepala</option>
                                <option value="Bokong">Bokong</option>
                                <option value="Kosong">Kosong</option>
                            </select>
                            
                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentleopoldiii" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>        

                    <!-- Leopold IV Penurunan Kepala-->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Leopold IV Penurunan Kepala</strong></label>

                       <div class="col-sm-9">
                            <select class="form-select" name="leopoldiv" required>
                                <option value="">Pilih</option>
                                <option value="Sudah">Sudah</option>
                                <option value="Belum">Belum</option>
                            </select>
                            
                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentleopoldiv" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>        


                    <!-- Pemeriksaan DJJ -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Pemeriksaan DJJ</strong></label>

                        <div class="col-sm-9">
                            <div class="input-group">
                                <input type="text" class="form-control" name="pemeriksaandjj">
                                <span class="input-group-text">Frek</span>
                            </div>
                                <small class="form-text" style="color: red;">(Normal 120-160/bradikardi, 160-180/tachikardi < 120) </small>
                            
                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentpemeriksaandjj" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>
                    
                    <!-- Intensitas -->
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-2 col-form-label"><strong>Intensitas</strong></label>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="text" class="form-control" name="intensitas">
                                <span class="input-group-text">Intensitas</span>
                        </div>    
                    </div>
                                
                    <!-- Keteraturan -->
                    <label class="col-sm-2 col-form-label"><strong>Keteraturan</strong></label>
                    <div class="col-sm-3">
                            <div class="input-group">
                                <input type="text" class="form-control" name="keteraturan">
                                <span class="input-group-text">Keteraturan</span>
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

                    <!-- Pigmentasi -->

                    <div class="row mb-2">
                        <label class="col-sm-2 col-form-label">
                            <strong>Pigmentasi</strong>
                    </div>
                    
                    <!-- Linea Nigra -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Linea Nigra</strong></label>

                       <div class="col-sm-9">
                            <select class="form-select" name="linea_nigra">
                                <option value="">Pilih</option>
                                <option value="Ada">Ada</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                            
                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentlineanigra" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>

                    <style>
                    .table-abdomen {
                        table-layout: fixed;
                        width:100%
                    }

                    .table-abdomen td,
                    .table-abdomen th {
                        word-wrap: break-word;
                        white-space: normal;
                        vertical-align: top;
                    }

                
                    </style>

                    <table class="table table-bordered table-abdomen">

                    <tbody>
                        <tr>
                            <td colspan="2"><strong>Uterus</strong></td>
                        </tr>

                        <tr>
                            <td><strong>TFU</strong></td>
                            <td><?= $row['tfu'] ?? ''; ?> cm</td>
                        </tr>

                        <tr>
                            <td></td>
                            <td>Kontraksi: <?= $row['kontraksi'] ?? ''; ?></td>
                        </tr>

                        <tr>
                            <td><strong>Leopold I<strong></td>
                            <td><?= $row['leopoldi'] ?? ''; ?></td>
                        </tr>

                        <tr>
                            <td><strong>Leopold II<strong></td>
                            <td>Kanan: <?= $row['kanan'] ?? ''; ?></td>
                        </tr>

                        <tr>
                            <td></td>
                            <td>Kiri: <?= $row['kiri'] ?? ''; ?></td>
                        </tr>

                        <tr>
                            <td><strong>Leopold III<strong></td>
                            <td><?= $row['leopoldiii'] ?? ''; ?></td>
                        </tr>

                        <tr>
                            <td><strong>Leopold IV Penurunan Kepala<strong></td>
                            <td><?= $row['leopoldiv'] ?? ''; ?></td>
                        </tr>

                        <tr>
                            <td><strong>Leopold I<strong></td>
                            <td><?= $row['leopoldi'] ?? ''; ?></td>
                        </tr>

                        <tr>
                            <td><strong>Pemeriksaan DJJ</strong></td>
                        <td>
                            <?= $row['pemeriksaandjj'] ?? ''; ?> Frek (normal 120-160),
                            <?= $row['intensitas'] ?? ''; ?> Intensitas,
                            <?= $row['keteraturan'] ?? ''; ?> Keteraturan
                        </td>
                        </tr>

                        <tr>
                            <td colspan="2"><strong>Pigmentasi</strong></td>    
                        </tr>

                        <tr>
                            <td><strong>Linea Nigra<strong></td>
                            <td><?= $row['linea_nigra'] ?? ''; ?></td>
                        </tr>

                    </tbody>
                    </table>
                            
                    <!-- Bagian Perineum dan Genetalia -->

                    <div class="row mb-2">
                        <label class="col-sm-8 col-form-label text-primary">
                            <strong>Vagina dan Perineum</strong>
                    </div>
                    
                    <!-- Keputihan -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Keputihan</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Keputihan (Ya/Tidak): warna, konsistensi, bau, dan gatal. Hasil:</small>
                            <textarea name="inspeksikeputihan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentinspeksikeputihan" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 mt-4 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 
                    
                    <!-- Hemoroid -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Hemoroid</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">hemoroid (Ya/Tidak). Jika Ya sebutkan (derajat, sudah berapa lama nyeri?). Hasil:</small>
                            <textarea name="inspeksihemoroid" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentinspeksihemoroid" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 mt-4 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 

                    <!-- Masalah Khusus -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Masalah Khusus</strong></label>

                            <div class="col-sm-9">
                                <textarea name="masalahkhususperineum" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentmasalahkhususperineum" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 
                    
                    <!-- Bagian Ekstremitas -->

                    <div class="row mb-2">
                        <label class="col-sm-8 col-form-label text-primary">
                            <strong>Ekstremitas</strong>
                    </div> 

                    <!-- Inspeksi Ekstremitas Bawah -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Ekstremitas Bawah</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Apakah terdapat edema (Ya/Tidak), Varises (Ya/Tidak). Hasil:</small>
                           <textarea name="inspeksiekstremitasbawah" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentekstremitasbawah" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 mt-4 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 
        
                <!-- Bagian Istirahat dan Kenyamanan -->

                    <div class="row mb-2">
                        <label class="col-sm-6 col-form-label text-primary">
                            <strong>Istirahat dan Kenyamanan</strong>
                    </div>
                    
                    <!-- Inspeksi Istirahat -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Pola Tidur Saat Ini</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Kebiasaan tidur, lama dalam hitungan jam, frekuensi. Hasil:</small>
                            <textarea name="inspeksiistirahat" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentinspeksiistirahat" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 mt-4 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 
                    
                    <!-- Inspeksi Kenyamanan -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Keluhan ketidaknyamanan  (Ya/Tidak), lokasi. Hasil:</small>
                            <textarea name="inspeksikenyamanan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentinspeksikenyamanan" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 mt-4 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 
   
                    <!-- Masalah Khusus -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Masalah Khusus</strong></label>

                            <div class="col-sm-9">
                                <textarea name="masalahkhususistirahat" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentmasalahkhususistirahat" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>  
                            
                <!-- Bagian Mobilisasi dan Latihan -->

                    <div class="row mb-2">
                        <label class="col-sm-6 col-form-label text-primary">
                            <strong>Mobilisasi dan Latihan</strong>
                    </div>

                    <!-- Inspeksi -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Tingkat Mobilisasi</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Apakah mandiri, parsial, total. Hasil:</small>
                            <textarea name="inspeksimobilisasi" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentmobilisasi" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 mt-4 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 
                    
                    <!-- Masalah Khusus -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Masalah Khusus</strong></label>

                            <div class="col-sm-9">
                                <textarea name="masalahkhususmobilisasi" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentmasalahkhususmobilisasi" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 
                            
                <!-- Bagian Pola Nutrisi dan Cairan -->

                    <div class="row mb-2">
                        <label class="col-sm-6 col-form-label text-primary">
                            <strong>Pola Nutrisi dan Cairan</strong>
                    </div>
                    
                    <!-- Inspeksi Nutrisi -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Pola Nutrisi dan Cairan</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Asupan nutrisi (nafsu makan: baik, kurang atau tidak nafsu makan). Hasil:</small>
                            <textarea name="inspeksinutrisi" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentinspeksinutrisi" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 mt-4 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 
                    
                    <!-- Inspeksi Cairan -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Asupan cairan (cukup/kurang). Hasil:</small>
                            <textarea name="inspeksicairan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentinspeksicairan" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 mt-4 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 

                    <!-- Inspeksi Pantangan Makan -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Pantangan Makan. Hasil:</small>
                            <textarea name="inspeksipantangan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentinspeksipantangan" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 mt-4 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 
                    
                    <!-- Masalah Khusus -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Masalah Khusus</strong></label>

                            <div class="col-sm-9">
                               <textarea name="masalahkhususpolanutrisi" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentmasalahkhususpolanutrisi" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 
                            
                <!-- Bagian Pengetahuan -->

                    <div class="row mb-2">
                        <label class="col-sm-2 col-form-label text-primary">
                            <strong>Pengetahuan</strong>
                    </div>

                    <!-- Inspeksi Tanda Melahirkan -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Apakah mengetahui tanda-tanda melahirkan?</strong></label>

                        <div class="col-sm-9">
                            <textarea name="tandamelahirkan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commenttandamelahirkan" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 

                    <!-- Inspeksi Nyeri Melahirkan -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Apakah mengetahui cara menangani nyeri saat melahirkan?</strong></label>

                        <div class="col-sm-9">
                            <textarea name="nyerimelahirkan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentnyerimelahirkan" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 

                    <!-- Inspeksi Persalinan -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Cara mengejan saat persalinan?</strong></label>

                        <div class="col-sm-9">
                            <textarea name="persalinan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentpersalinan" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 

                    <!-- Inspeksi Asi dan Payudara -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Apakah mengetahuai manfaat ASI dan cara perawatan payudara?</strong></label>

                        <div class="col-sm-9">
                            <textarea name="asidanpayudara" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentasidanpayudara" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
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

            <div class="card">
            <div class="card-body">

                <h5 class="card-title"><strong>Program Terapi</strong></h5>

                <!-- General Form Elements -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

                    <div class="row mb-2">
                        <label class="col-sm-6 col-form-label text-primary">
                            <strong>Obat-obatan yang dikonsumsi Saat Ini</strong>
                    </div>

                <!-- Bagian Jenis Obat -->
                <div class="row mb-3">
                    <label for="jenisobat" class="col-sm-2 col-form-label"><strong>Jenis Obat</strong></label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="jenisobat">
                        
                         <!-- comment -->
                            <textarea class="form-control mt-2" id="commentjenisobat" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
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
                    
                <!-- Bagian Kegunaan -->
                <div class="row mb-3">
                    <label for="kegunaan" class="col-sm-2 col-form-label"><strong>Kegunaan</strong></label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="kegunaan">
                         
                        <!-- comment -->
                            <textarea class="form-control mt-2" id="commentkegunaan" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>
                
                <!-- Bagian Cara Pemberian -->
                <div class="row mb-3">
                    <label for="jenisobat" class="col-sm-2 col-form-label"><strong>Cara Pemberian</strong></label>
                    <div class="col-sm-9">
                        <textarea name="carapemberian" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentcarapemberian" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
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
                    
                    <h5 class="card-title mt-2"><strong>Program Terapi</strong></h5>

                    <style>
                    .table-terapi {
                        table-layout: fixed;
                        width:100%
                    }

                    .table-terapi td,
                    .table-terapi th {
                        word-wrap: break-word;
                        white-space: normal;
                        vertical-align: top;
                    }
                    </style>

                    <table class="table table-bordered table-terapi">
                        <thead>
                            <tr>
                                <th class="text-center">Jenis Obat</th>
                                <th class="text-center">Dosis</th>
                                <th class="text-center">kegunaan</th>
                                <th class="text-center">Cara Pemberian</th>
                        </tr>
                        </thead>

                    <tbody>

                    <?php
                    if(!empty($data)){
                        foreach($data as $row){
                            echo "<tr>
                            <td>".nlrbr($row['jenisobat'])."</td>
                            <td>".nlrbr($row['dosis'])."</td>
                            <td>".nlrbr($row['kegunaan'])."</td>
                            <td>".nlrbr($row['carapemberian'])."</td>
                            </tr>";
                        }
                    }
                    ?>

                    </tbody>
                    </table>

                <!-- Bagian Hasil Pemeriksaan -->    

                <div class="row mb-2">
                    <label class="col-sm-6 col-form-label text-primary">
                        <strong>Hasil Pemeriksaan Penunjang dan Laboratorium:</strong>
                </div>

                <!-- Bagian Pemeriksaan -->
                <div class="row mb-3">
                    <label for="pemeriksaan" class="col-sm-2 col-form-label"><strong>Pemeriksaan</strong></label>
                    <div class="col-sm-9">
                        <textarea name="pemeriksaan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentpemeriksaan" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
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
                            <textarea class="form-control mt-2" id="commenthasil" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 
                    
                <!-- Bagian Nilai Normal -->
                <div class="row mb-3">
                    <label for="nilainormal" class="col-sm-2 col-form-label"><strong>Nilai Normal</strong></label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="nilainormal">
                        
                         <!-- comment -->
                            <textarea class="form-control mt-2" id="commentnilainormal" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
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

                <h5 class="card-title"><strong>Hasil Pemeriksaan Penunjang dan Laboratorium:</strong></h5>
                
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

                    <table class="table table-bordered table-pemeriksaan">
                        <thead>
                            <tr>
                                <th class="text-center">Pemeriksaan</th>
                                <th class="text-center">Hasil</th>
                                <th class="text-center">Nilai Normal</th>
                        </tr>
                        </thead>

                    <tbody>

                    <?php
                    if(!empty($data)){
                        foreach($data as $row){
                            echo "<tr>
                            <td>".nlrbr($row['pemeriksaan'])."</td>
                            <td>".nlrbr($row['hasil'])."</td>
                            <td>".nlrbr($row['nilainormal'])."</td>
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
        
    



                

            
                   





                           






                            
        




















                    <!-- 
                    <div class="row mb-2">
                        <label class="col-sm-2 col-form-label">
                            <strong>Status Gravida</strong>
                    </div>
                    
                   
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>G</strong></label>

                        <div class="col-sm-8">
                            <textarea name="G" class="form-control" rows="3"></textarea>
                            <div class="invalid-feedback">
                                Harap isi Status Gravida
                            </div>
                        </div>    

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="checkstatusgravidag">
                                </div>
                            </div>
                        </div>

                        
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>P</strong></label>

                            <div class="col-sm-8">
                                <textarea name="P" class="form-control" rows="3"></textarea>
                                <div class="invalid-feedback">
                                    Harap isi Status Gravida
                                </div>
                            </div>    

                            <div class="col-sm-1 d-flex align-items-start">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="checkstatusgravidap">
                                    </div>
                                </div>
                            </div>

                      
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>A</strong></label>

                            <div class="col-sm-8">
                                <textarea name="A" class="form-control" rows="3"></textarea>
                                <div class="invalid-feedback">
                                    Harap isi Status Gravida
                                </div>
                            </div>    

                            <div class="col-sm-1 d-flex align-items-start">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="checkstatusgravidaa">
                                    </div>
                                </div>
                            </div>
                            
                       
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Usia Kehamilan</strong></label>

                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="usia" required>
                                <div class="invalid-feedback">
                                    Harap isi Usia Kehamilan.
                                </div>
                            </div>  
                            
                            <div class="col-sm-1 d-flex align-items-start">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="checkusiakehamilan">
                                    </div>
                                </div>
                            </div> 
                            
                        
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Tapsiran Partus</strong></label>

                            <div class="col-sm-8">
                                <textarea name="tapsiran_partus" class="form-control" rows="3"></textarea>
                                <div class="invalid-feedback">
                                    Harap isi Tapsiran Partus.
                                </div>
                            </div>  
                            
                            <div class="col-sm-1 d-flex align-items-start">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="checktapsiranpartus">
                                    </div>
                                </div>
                            </div>
                            
                      
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Riwayat Imunisasi TT (Saat Ini)</strong></label>

                            <div class="col-sm-8">
                                <textarea name="riwayat_imunisasi_tt" class="form-control" rows="3"></textarea>
                                <div class="invalid-feedback">
                                    Harap isi Riwayat Imunisasi TT (Saat Ini).
                                </div>
                            </div>  
                            
                            <div class="col-sm-1 d-flex align-items-start">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="checkriwayatimunisasitt">
                                    </div>
                                </div>
                            </div>  
                            
                     
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Riwayat Kehamilan Saat Ini</strong></label>

                            <div class="col-sm-8">
                                <textarea name="riyawat_kehamilan" class="form-control" rows="3"></textarea>
                                <div class="invalid-feedback">
                                    Harap isi Riwayat Kehamilan Saat Ini.
                                </div>
                            </div>  
                            
                            <div class="col-sm-1 d-flex align-items-start">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="checkriwayatkehamilan">
                                    </div>
                                </div>
                            </div>  
                            
                      
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Riwayat Penyakit Ibu dan Keluarga</strong></label>

                            <div class="col-sm-8">
                                <textarea name="riwayat_penyakit_ibu_dan_keluarga" class="form-control" rows="3"></textarea>
                                <div class="invalid-feedback">
                                    Harap isi Riwayat Penyakit Ibu dan Keluarga.
                                </div>
                            </div>  
                            
                            <div class="col-sm-1 d-flex align-items-start">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="checkriwayatpenyakitibudankeluarga">
                                    </div>
                                </div>
                            </div>      

             <h5 class="card-title mb-1"><strong>Pemeriksaan Antropometri</strong></h5>  
             
            
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label"><strong>Tinggi Badan</strong></label>

                <div class="col-sm-8">
                    <input type="text" class="form-control" name="tinggi_badan" required>
                        <div class="invalid-feedback">
                        Harap isi Tinggi Badan.
                        </div>
                </div>  
                            
                <div class="col-sm-1 d-flex align-items-start">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="checktinggibadan">
                            </div>
                        </div>
                </div> 

           
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label"><strong>Berat Badan</strong></label>

                <div class="col-sm-8">
                    <input type="text" class="form-control" name="berat_badan" required>
                        <div class="invalid-feedback">
                        Harap isi Berat Badan.
                        </div>
                </div>  
                            
                <div class="col-sm-1 d-flex align-items-start">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="checkberatbadan">
                            </div>
                        </div>
                </div>

           
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label"><strong>LILA</strong></label>

                <div class="col-sm-8">
                    <input type="text" class="form-control" name="lila" required>
                        <div class="invalid-feedback">
                        Harap isi LILA.
                        </div>
                </div>  
                            
                <div class="col-sm-1 d-flex align-items-start">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="checklila">
                            </div>
                        </div>
                </div>   
                
              
                <h5 class="card-title mb-1"><strong>Tanda-Tanda Vital</strong></h5>   
            
                   
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Tekanan Darah</strong></label>

                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="tekanan_darah" required>
                            <div class="invalid-feedback">
                                Harap isi Tekanan Darah.
                            </div>
                        </div>    

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="checktekanandarah">
                                </div>
                            </div>
                        </div>

                   
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Nadi</strong></label>

                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="nadi" required>
                                <div class="invalid-feedback">
                                    Harap isi Nadi.
                                </div>
                            </div>    

                            <div class="col-sm-1 d-flex align-items-start">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="checknadi">
                                    </div>
                                </div>
                            </div>
                            
                  
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>P</strong></label>

                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="p" required>
                                <div class="invalid-feedback">
                                    Harap isi P.
                                </div>
                            </div>    

                            <div class="col-sm-1 d-flex align-items-start">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="checkp">
                                    </div>
                                </div>
                            </div>  
                            
                       
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Suhu</strong></label>

                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="suhu" required>
                                <div class="invalid-feedback">
                                    Harap isi Suhu.
                                </div>
                            </div>    

                            <div class="col-sm-1 d-flex align-items-start">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="checksuhu">
                                    </div>
                                </div>
                            </div>    
                    
                    
                   
                     <h5 class="card-title mb-1"><strong>Pemeriksaan Umum</strong></h5>  
                    
                
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Keluhan Utama</strong></label>

                        <div class="col-sm-8">
                           <textarea name="keluhan_utama" class="form-control" rows="3"></textarea>
                            <div class="invalid-feedback">
                                Harap isi Keluhan Utama.
                            </div>
                        </div>    

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="checkkeluhanutama">
                                </div>
                            </div>
                        </div>
                        
                   
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Riwayat Keluhan Utama</strong></label>

                        <div class="col-sm-8">
                           <textarea name="riwayat_keluhan_utama" class="form-control" rows="3"></textarea>
                            <div class="invalid-feedback">
                                Harap isi Riwayat Keluhan Utama.
                            </div>
                        </div>    

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="checkriwayatkeluhanutama">
                                </div>
                            </div>
                        </div>    -->

                
                 