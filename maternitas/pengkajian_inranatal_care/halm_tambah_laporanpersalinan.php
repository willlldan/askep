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

                <h5 class="card-title"><strong>A. DATA UMUM</strong></h5>

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
                    <label for="usiaistri" class="col-sm-2 col-form-label"><strong>Usia</strong></label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="usiaistri">

                        <!-- comment -->
                            <textarea class="form-control mt-2" id="commentusiaistri" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
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
                    <label for="pekerjaanistri" class="col-sm-2 col-form-label"><strong>Pekerjaan</strong></label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="pekerjaanistri">

                        <!-- comment -->
                            <textarea class="form-control mt-2" id="commentpekerjaanistri" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>

                <!-- Bagian Pendidikan Terakhir -->
                <div class="row mb-3">
                    <label for="pendidikanterakhiristri" class="col-sm-2 col-form-label"><strong>Pendidikan Terakhir</strong></label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="pendidikanterakhiristri">

                        <!-- comment -->
                            <textarea class="form-control mt-2" id="commentpendidikanterakhiristri" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
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
                    <label for="agamaistri" class="col-sm-2 col-form-label"><strong>Agama</strong></label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="agamaistri">

                       <!-- comment -->
                            <textarea class="form-control mt-2" id="commentagamaistri" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
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

                <!-- Bagian Diagnosa Medik -->
                <div class="row mb-3">
                    <label for="diagnosamedik" class="col-sm-2 col-form-label"><strong>Diagnosa Medik</strong></label>
                    <div class="col-sm-9">
                        <textarea name="keterangan" class="form-control" rows="5" cols="30"></textarea>
                        
                        <!-- comment -->
                            <textarea class="form-control mt-2" id="commentdiagnosamedik" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>

                <!-- Bagian Nama Suami -->
                <div class="row mb-3">
                    <label for="namasuami" class="col-sm-2 col-form-label"><strong>Nama Suami</strong></label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="namasuami">
                       
                        <!-- comment -->
                            <textarea class="form-control mt-2" id="commentnamasuami" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
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
                    <label for="usiasuami" class="col-sm-2 col-form-label"><strong>Usia</strong></label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="usiasuami">
                        
                        <!-- comment -->
                            <textarea class="form-control mt-2" id="commentusiasuami" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
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
                    <label for="pekerjaansuami" class="col-sm-2 col-form-label"><strong>Pekerjaan</strong></label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="pekerjaansuami">
                        
                        <!-- comment -->
                            <textarea class="form-control mt-2" id="commentpekerjaansuami" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>

                <!-- Bagian Pendidikan Terakhir -->
                <div class="row mb-3">
                    <label for="pendidikanterakhirsuami" class="col-sm-2 col-form-label"><strong>Pendidikan Terakhir</strong></label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="pendidikanterakhirsuami">
                        
                        <!-- comment -->
                            <textarea class="form-control mt-2" id="commentpendidikanterakhirsuami" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
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
                    <label for="agamasuami" class="col-sm-2 col-form-label"><strong>Agama</strong></label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="agamasuami">
                        
                        <!-- comment -->
                            <textarea class="form-control mt-2" id="commentagamasuami" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
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

                <h5 class="card-title"><strong>B. DATA UMUM KESEHATAN</strong></h5>

                <!-- General Form Elements -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">
                
                <!-- Bagian BB/TB -->
                        <div class="row mb-3">
                            <label for="bbtb" class="col-sm-2 col-form-label"><strong>BB/TB</strong></label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="bbtb">
                                    <span class="input-group-text">kg/cm</span>
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

                <!-- Bagian BB Sebelum Hamil -->
                        <div class="row mb-3">
                            <label for="bbtb" class="col-sm-2 col-form-label"><strong>BB Sebelum Hamil</strong></label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="bbsebelumhamil">
                                    <span class="input-group-text">kg</span>
                        </div>
                                    
                        <!-- comment -->
                            <textarea class="form-control mt-2" id="commentbbsebelumhamil" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                        </div> 
                        
                <!-- Bagian LILA -->
                        <div class="row mb-3">
                            <label for="bbtb" class="col-sm-2 col-form-label"><strong>LILA</strong></label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="lila">
                                    <span class="input-group-text">cm</span>
                        </div>
                                    
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
                        
                <!-- Bagian Rencana Kehamilan -->
                 <div class="row mb-3">
                    <label for="jeniskelamin" class="col-sm-2 col-form-label"><strong>Kehamilan Sekarang direncanakan</strong></label> 
                    <div class="col-sm-9">
                    <select class="form-select" name="rencanakehamilan">
                            <option value="">Pilih</option>
                            <option value="Ya">Ya</option>
                            <option value="Tidak">Tidak</option>
                            </select>
                            
                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentrencanakehamilan" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>
                    
                <!-- Bagian Status Obstetrik -->

                        <div class="row mb-3">

                        <label class="col-sm-2 col-form-label"><strong>Status Obstetrik</strong></label>
                            <div class="col-sm-9">
                                <div class="row">

                        <!-- G -->
                        <div class="col-md-4 d-flex align-items-center">
                            <label class="me-2"><strong>G</strong></label>
                            <input type="text" class="form-control" name="g">
                        </div>

                        <!-- p -->
                        <div class="col-md-4 d-flex align-items-center">
                            <label class="me-2"><strong>P</strong></label>
                            <input type="text" class="form-control" name="p">
                        </div>

                        <!-- A -->
                        <div class="col-md-4 d-flex align-items-center">
                            <label class="me-2"><strong>A</strong></label>
                            <input type="text" class="form-control" name="a">
                        </div>
                    </div>

                         <!-- comment -->
                        <textarea class="form-control mt-2" id="commentgpa" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>

                    </div>
                    
                <!-- HPHT -->
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-2 col-form-label"><strong>HPHT</strong></label>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="text" class="form-control" name="hpht">
                        </div>    
                    </div>
                                
                    <!-- TP -->
                    <label class="col-sm-2 col-form-label"><strong>TP</strong></label>
                    <div class="col-sm-3">
                        <div class="input-group">
                                <input type="text" class="form-control" name="tp">
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
                
                <!-- Bagian Obat Dikonsumsi -->
                <div class="row mb-3">
                    <label for="obatdikonsumsi" class="col-sm-2 col-form-label"><strong>Obat-obatan yang dikonsumsi</strong></label>
                    <div class="col-sm-9">
                        <textarea name="obatdikonsumsi" class="form-control" rows="5" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
                        <!-- comment -->
                            <textarea class="form-control mt-2" id="commentobatdikonsumsi" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
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
                    <label for="alergi" class="col-sm-2 col-form-label"><strong>Apakah ada alergi terhadap sesuatu</strong></label>
                    <div class="col-sm-9">
                        <textarea name="alergi" class="form-control" rows="5" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
                        <!-- comment -->
                            <textarea class="form-control mt-2" id="commentalergi" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>  
                    
                <!-- Bagian Alat Bantu -->
                <div class="row mb-3">
                    <label for="alatbantu" class="col-sm-2 col-form-label"><strong>Alat Bantu yang Digunakan</strong></label>
                    <div class="col-sm-9">
                        <small class="form-text" style="color: red;">Gigi tiruan, kacamata/lensa kontak, alat dengar, dan lain-lain. Sebutkan</small>
                        <textarea name="alatbantu" class="form-control" rows="5" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
                        <!-- comment -->
                            <textarea class="form-control mt-2" id="commentobatdikonsumsi" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>     
                    
                <!-- Bagian BAK -->
                <div class="row mb-3">
                    <label for="bakterakhir" class="col-sm-2 col-form-label"><strong>BAK Terakhir</strong></label>
                    <div class="col-sm-9">
                        <small class="form-text" style="color: red;">Masalah:</small>
                        <textarea name="bakterakhir" class="form-control" rows="5" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
                        <!-- comment -->
                            <textarea class="form-control mt-2" id="commentbakterakhir" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>

                <!-- Bagian BAB Terakhir -->
                <div class="row mb-3">
                    <label for="babterakhir" class="col-sm-2 col-form-label"><strong>BAB Terakhir</strong></label>
                    <div class="col-sm-9">
                        <small class="form-text" style="color: red;">Masalah:</small>
                        <textarea name="babterakhir" class="form-control" rows="5" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
                        <!-- comment -->
                            <textarea class="form-control mt-2" id="commentbabterakhir" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>

                <!-- Bagian Kebiasaan Waktu Tidur -->

                <!-- Bagian Kebiasaan Waktu Tidur -->
                <div class="row mb-3">

                    <label class="col-sm-2 col-form-label"><strong>Kebiasaan Waktu Tidur</strong></label>

                    <!-- Siang -->
                    <label class="col-sm-1 col-form-label"><strong>Siang</strong></label>
                    <div class="col-sm-3">
                        <input type="text" class="form-control" name="siang">
                    </div>

                    <!-- Malam -->
                    <label class="col-sm-1 col-form-label"><strong>Malam</strong></label>
                    <div class="col-sm-3">
                        <input type="text" class="form-control" name="malam">
                    </div>

                    <!-- Checkbox -->
                    <div class="col-sm-1 d-flex align-items-start">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" disabled>
                        </div>
                    </div>

                </div>

                <div class="row mb-3">
                    <div class="col-sm-9 offset-sm-2">
                        <textarea class="form-control" rows="2" placeholder="Kolom Ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakan!" style="display:block; overflow:hidden; resize: none;"
                        oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"readonly></textarea>
                    </div>
                </div>

                <!-- Bagian Riwayat Kesehatan yang lalu -->
                <div class="row mb-3">
                    <label for="riwayatkesehatan" class="col-sm-2 col-form-label"><strong>Riwayat Kesehatan yang Lalu</strong></label>
                    <div class="col-sm-9">
                        <textarea name="riwayatkesehatan" class="form-control" rows="5" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
                        <!-- comment -->
                            <textarea class="form-control mt-2" id="commentriwayatkesehatan" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>

                <h5 class="card-title"><strong>Riwayat Persalinan</strong></h5>
                
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
                            <textarea class="form-control mt-2" id="commentjeniskelamin" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>

                <!-- Bagian Cara Lahir -->
                <div class="row mb-3">
                    <label for="caralahir" class="col-sm-2 col-form-label"><strong>Cara Lahir</strong></label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="caralahir">
                        
                        <!-- comment -->
                            <textarea class="form-control mt-2" id="commentcaralahir" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 
                    
                <!-- Bagian BB Lahir (gram) -->
                <div class="row mb-3">
                    <label for="bblahir" class="col-sm-2 col-form-label"><strong>BB Lahir (gram)</strong></label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="bblahir">

                        <!-- comment -->
                            <textarea class="form-control mt-2" id="commentbblahir" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>     

                <!-- Bagian Keadaan -->
                <div class="row mb-3">
                    <label for="keadaan" class="col-sm-2 col-form-label"><strong>Keadaan</strong></label>
                    <div class="col-sm-9">
                        <textarea name="keadaan" class="form-control" rows="5" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
                        <!-- comment -->
                            <textarea class="form-control mt-2" id="commentusiaistri" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
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
                    <label for="bbtbbayi" class="col-sm-2 col-form-label"><strong>Umur</strong></label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="umur">

                        <!-- comment -->
                            <textarea class="form-control mt-2" id="commentumur" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
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

                <h5 class="card-title mt-2"><strong>Tabel Riwayat Persalinan</strong></h5>

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Jenis Kelamin</th>
                            <th class="text-center">Cara Lahir</th>
                            <th class="text-center">BB Lahir (gram)</th>
                            <th class="text-center">Keadaan</th>
                            <th class="text-center">Umur</th>
                       </tr>
                    </thead>

                <tbody>

                <?php
                if(!empty($data)){
                    $no = 1;
                    foreach($data as $row){
                        echo "<tr>
                        <td class='text-center'>".$no++."</td>
                        <td>".$row['no']."</td>
                        <td>".$row['jeniskelamin']."</td>
                        <td>".$row['caralahir']."</td>
                        <td>".$row['bblahir']."</td>
                        <td>".$row['keadaan']."</td>
                        <td>".$row['umur']."</td>
                        </tr>";
                    }
                }
                ?>

                </tbody>
                </table>

                <!-- Bagian Kelas Prenatal -->
                        <div class="row mb-3">

                        <label class="col-sm-2 col-form-label"><strong>Kelas Prenatal</strong></label>

                        <!-- Field -->
                        <div class="col-sm-9">
                            <select class="form-select" name="kelasprenatal">
                                <option value="">Pilih</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>

                            <!-- Comment -->
                            <textarea class="form-control mt-2" id="commentkelasprenatal" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize:none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                        </div>

                    </div>    

            <!-- Bagian Kunjungan ANC -->
                <div class="row mb-3">
                    <label for="anc" class="col-sm-2 col-form-label"><strong>Jumlah Kunjungan ANC pada kehamilan ini</strong></label>
                    <div class="col-sm-9">
                        <div class="input-group">
                            <input type="text" class="form-control" name="anc">
                            <span class="input-group-text">kali</span>
                    </div>


                        <!-- comment -->
                            <textarea class="form-control mt-2" id="commentsnc" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>   
                    
            <!-- Bagian Masalah Kehamilan Yang lalu -->
                        <div class="row mb-3">
                            <label for="masalahkehamilanlalu" class="col-sm-2 col-form-label"><strong>Masalah Kehamilan yang Lalu</strong></label>
                            <div class="col-sm-9">
                               <textarea name="masalahkehamilanlalu" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentmasalahkehamilanlalu" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>   
                    
            <!-- Bagian Masalah Kehamilan Sekarang -->
                        <div class="row mb-3">
                            <label for="masalahkehamilansekarang" class="col-sm-2 col-form-label"><strong>Masalah Kehamilan Sekarang</strong></label>
                            <div class="col-sm-9">
                               <textarea name="masalahkehamilansekarang" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentmasalahkehamilansekarang" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>
                    
            <!-- Bagian Rencana KB -->
                        <div class="row mb-3">
                            <label for="rencanakb" class="col-sm-2 col-form-label"><strong>Rencana KB</strong></label>
                            <div class="col-sm-9">
                               <input type="text" class="form-control" name="rencanakb">

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentrencanakb" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>
                    
            <!-- Bagian Makanan Bayi -->
                        <div class="row mb-3">

                        <label class="col-sm-2 col-form-label"><strong>Makanan Bayi Sebelumnya</strong></label>

                        <!-- Field -->
                        <div class="col-sm-9">
                            <select class="form-select" name="makananbayi">
                                <option value="">Pilih</option>
                                <option value="ASI">ASI</option>
                                <option value="PASI">PASI</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>

                            <!-- Comment -->
                            <textarea class="form-control mt-2" id="commentmakananbayi" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize:none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                        </div>

                    </div>  
                    
            <!-- Bagian Bayi Lahir -->
                        <div class="row mb-3">
                            <label for="bayilahir" class="col-sm-2 col-form-label"><strong>Setelah bayi lahir, siapa yang diharapkan membantu?</strong></label>
                            <div class="col-sm-9">
                                <small class="form-text" style="color: red;">Suami/Teman/Orang Tua? Hasil:</small>
                               <textarea name="bayilahir" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentbayilahir" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>  
                    
            <!-- Bagian Masalah Persalinan -->
                        <div class="row mb-3">
                            <label for="masalahpersalinan" class="col-sm-2 col-form-label"><strong>Masalah Persalinan</strong></label>
                            <div class="col-sm-9">
                                <small class="form-text" style="color: red;">Ada/Tidak? Jelaskan.</small>
                               <textarea name="masalahpersalinan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentriwayatkeluhanutama" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
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

                <h5 class="card-title"><strong>C. RIWAYAT PERSALINAN SEKARANG</strong></h5>
                
                <!-- General Form Elements -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">
                
                <!-- Bagian Keluhan Utama -->
                        <div class="row mb-3">
                            <label for="keluhanutama" class="col-sm-2 col-form-label"><strong>Keluhan Utama</strong></label>
                            <div class="col-sm-9">
                               <textarea name="keluhanutama" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentkeluhanutama" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
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
                            <label for="riwayatkeluhanutama" class="col-sm-2 col-form-label"><strong>Riwayat Keluhan Utama</strong></label>
                            <div class="col-sm-9">
                               <textarea name="riwayatkeluhanutama" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentriwayatkeluhanutama" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>  

                <!-- Bagian Mulai Persalinan -->
                        <div class="row mb-3">
                            <label for="mulaipersalinan" class="col-sm-2 col-form-label"><strong>Mulai Persalinan</strong></label>
                            <div class="col-sm-9">
                                <small class="form-text" style="color: red;">Kontraksi (teratur/tidak), interval, lama. Hasil:</small>
                               <textarea name="mulaipersalinan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentriwayatkeluhanutama" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 mt-4 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>  
                    
                <!-- Bagian Tanda-tanda Vital -->

                    <div class="row mb-3">
                        <label class="col-sm-9 col-form-label text-primary">
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
                        <textarea class="form-control mt-2" id="commenttandavital" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize:none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                    </div>
                </div>    

                <!-- Bagian Kepala dan Rambut -->

                    <div class="row mb-2">
                        <label class="col-sm-2 col-form-label text-primary">
                            <strong>Kepala dan Rambut</strong>
                    </div>
                    
                    <!-- Kepala dan Rambut -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Kepala dan Rambut</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Rontok (Ya/Tidak), Kulit Kepala (Bersih/Kotor), Nyeri Tekan (Ya/Tidak). Hasil:</small>
                            <textarea name="kepaladanrambut" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentkepaladanrambut" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 mt-4 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>        
  
                    <!-- Bagian Wajah -->

                    <div class="row mb-2">
                        <label class="col-sm-2 col-form-label text-primary">
                            <strong>Wajah</strong>
                    </div>
                    
                    <!-- Hiperpigmentasi -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Hiperpigmentasi (Cloasma Gravidarum)</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Ya/Tidak, Area ...</small>
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

                    <!-- Masalah Khusus -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Masalah Khusus</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Ekspresi wajah, apakah pucat dan bengkak. Hasil:</small>
                            <textarea name="masalahwajah" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentmasalahwajah" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
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
                    
                    <!-- Konjungtiva  -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Konjungtiva</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Anemis/An-anemis. Hasil:</small>
                           <textarea name="konjungtiva" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentkonjungtiva" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 mt-4 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 

                    <!-- Sklera -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Sklera</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Ikterik/An-ikterik. Hasil:</small>
                            <textarea name="sklera" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentsklera" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 mt-4 d-flex align-items-start">
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
                    
                    <!-- Mukosa Bibir -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Mukosa Bibir</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Lembab/Kering. Hasil:</small>
                            <textarea name="mukosabibir" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentmukosabibir" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 mt-4 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 

                    <!-- Sariawan -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Sariawan</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">(Ada/Tidak Ada). Hasil:</small>
                            <textarea name="sariawan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentsariawan" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 mt-4 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 

                    <!-- Gigi Berlubang -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Gigi Berlubang</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Ya/Tidak. Hasil:</small>
                            <textarea name="gigiberlubang" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentgigiberlubang" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
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
                    
                    <!-- Distensi -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Distensi Vena Jugularis</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Ya/Tidak. Hasil:</small>
                            <textarea name="distensi" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentdistensi" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 mt-4 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 

                    <!-- Kelenjar Tiroid -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Pembesaran Kelenjar Tiroid</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Ya/Tidak. Hasil:</small>
                            <textarea name="kelenjartiroid" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentkelenjartiroid" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 mt-4 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 

                    <!-- Nyeri Menelan -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Nyeri Menelan</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Ya/Tidak. Hasil:</small>
                            <textarea name="nyerimenelan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentnyeirmenelan" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 mt-4 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 

                     <!-- Riwayat Masalah Khusus -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Masalah Khusus</strong></label>

                            <div class="col-sm-9">
                                <textarea name="masalahkhususleher" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentmasalahkhususleher" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>  
                            
                     <!-- Bagian Dada -->

                    <div class="row mb-2">
                        <label class="col-sm-8 col-form-label text-primary">
                            <strong>Dada/Thorax</strong>
                    </div>
                    
                    <!-- Bunyi Jantung -->
                  
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Bunyi Jantung</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Normal atau apakah terdapat Mur-mur dan Gallop. Hasil:</small>
                            <textarea name="bunyijantung" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentbunyijantung" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
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
                                <textarea name="masalahkhususbunyijantung" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentmasalahkhususbunyijantung" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>

                    <!-- Sistem Pernapasan -->
                  
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Sistem Pernapasan</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Suara Napas (Vesikuler/Wheezing/Ronkhi). Hasil:</small>
                            <textarea name="sistempernapasan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentsistempernapasan" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
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
                                <textarea name="masalahkhusussistempernapasan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentmasalahkhusussistempernapasan" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
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
                    
                    <!-- Asi -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Pengeluaran ASI/Kolostum</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Ya/Tidak. Hasil:</small>
                            <textarea name="pengeluaranasi" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentpengeluaranasi" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 mt-4 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 

                    <!-- Inspeksi Puting -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Puting</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">(Eksverted/Inverted/Platnipple). Hasil:</small>
                            <textarea name="puting" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentputing" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
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
                                <textarea name="masalahkhususpayudadra" class="form-control" rows="5" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
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

                    <!-- Abdomen  -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Abdomen</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Apakah terdapat: Lignea Nigra/Striae Nigra/Striae Alba, Bekas Operasi. Hasil:</small>
                            <textarea name="abdomen" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentabdomen" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 mt-4 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>

                     <!-- Pemeriksaan Palpasi Abdomen  -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Pemeriksaan Palpasi Abdomen</strong></label>

                        <div class="col-sm-9">
                            <textarea name="pemeriksaanpalpasiabdomen" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentpemeriksaanpalpasiabdomen" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 mt-4 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>

                     <div class="row mb-3">
                        <label class="col-sm-9 col-form-label text-primary">
                            <strong>Uterus</strong>
                        </label>    
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
                    
                    <!-- Status Janin -->
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-2 col-form-label"><strong>Status Janin</strong></label>
                        <div class="col-sm-3">
                                <select class="form-select" name="statusjanin">
                                <option value="">Pilih</option>
                                <option value="Hidup">Hidup</option>
                                <option value="Tidak">Tidak</option> 
                            </select>
                        </div>    
                                
                    <!-- Jumlah -->
                    <label class="col-sm-2 col-form-label"><strong>Jumlah</strong></label>
                    <div class="col-sm-3">
                        <input type="text" class="form-control" name="jumlah">
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
                            <td><strong>Status Janin</strong></td>
                            <td><?= $row['statusjanin'] ?? ''; ?></td>
                        </tr>

                        <tr>
                            <td></td>
                            <td>Jumlah: <?= $row['jumlah'] ?? ''; ?></td>
                        </tr>

                    </tbody>
                    </table>

                    <!-- Bagian Ekstremitas -->

                    <div class="row mb-2">
                        <label class="col-sm-8 col-form-label text-primary">
                            <strong>Ekstremitas</strong>
                    </div>
                    
                    <!-- Ekstremitas Atas -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Ekstremitas Atas</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Apakah terdapat edema (ya/tidak), rasa kesemutan/baal (ya/tidak). Hasil:</small>
                            <textarea name="ekstremitasatas" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentekstremitasatas" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 mt-4 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>

                    <!-- Ekstremitas Bawah -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Ekstremitas Bawah</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Apakah terdapat edema (ya/tidak), varises (ya/tidak), refleks pattela (+/-). Hasil:</small>
                            <textarea name="ekstremitasbawah" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

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

                    <!-- Bagian Vagina -->

                    <div class="row mb-2">
                        <label class="col-sm-8 col-form-label text-primary">
                            <strong>Vagina</strong>
                    </div>
                    
                    <!-- Persiapan Perineum -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Vagina</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Persiapan perineum. Hasil:</small>
                            <textarea name="persiapanperineum" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentpersiapanperineum" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 mt-4 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 

                    <!-- Pengeluaran Pervaginam -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Pengeluaran Pervaginam. Hasil:</small>
                            <textarea name="pengeluaranpervaginam" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentpengeluaranpervaginam" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 mt-4 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 

                <h5 class="card-title"><strong>Program Terapi</strong></h5>

                <!-- General Form Elements -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

                    <div class="row mb-2">
                        <label class="col-sm-6 col-form-label text-primary">
                            <strong>Obat-obatan yang dikonsumsi Saat Ini</strong>
                    </div>

                <!-- Bagian Jenis Obat -->
                <div class="row mb-3">
                    <label for="obat" class="col-sm-2 col-form-label"><strong>Obat</strong></label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="obat">
                        
                         <!-- comment -->
                            <textarea class="form-control mt-2" id="commentobat" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
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
                                <th class="text-center">Obat</th>
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
                            <td>".nlrbr($row['obat'])."</td>
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

                </div>
            </div>

        <div class="card">
            <div class="card-body">
              <h5 class="card-title mb-1"><strong>A. Persalinan Kala I</strong></h5>

                <!-- Bagian Persalinan Kala I -->
                    
                    <!-- Mulai Persalinan dan Akhir -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Persalinan Kala I</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Mulai Persalinan dan Akhir (Tuliskan tanggal dan Jam). Hasil:</small>
                            <textarea name="mulaipersalinandanakhir" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentmulaipersalinandanakhir" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 mt-4 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>        

                    <!-- Tanda dan Gejala -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Tanda dan Gejala</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Tanda dan Gejala (Keluhan mules-mules, ada darah keluar dan lendir tapi baru sedikit melalui kemaluan). Hasil:</small>
                            <textarea name="tandadangejala" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commenttandadangejala" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 mt-4 d-flex align-items-start">
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
                        <textarea class="form-control mt-2" id="commenttandavital" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize:none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                    </div>
                </div>
                
                <!-- Keluhan Lain -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Keluhan Lain</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Keluhan lainnya yang dirasakan (nyeri, cemas). Hasil:</small>
                            <textarea name="keluhanlain" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentkeluhanlain" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 mt-4 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>

                <!-- Lama Kala I -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Lama Kala I</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">(Tuliskan berapa lama dalam hitungan jam dan menitnya). Hasil:</small>
                            <textarea name="lamakalai" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentlamakalai" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 mt-4 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>

                    <!-- Tindakan Khusus -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Tindakan Khusus</strong></label>

                        <div class="col-sm-9">
                            <textarea name="tindakankhusus" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commenttindakankhusus" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 mt-4 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
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

                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="pemeriksaanke">    
                    
                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentpemeriksaanke" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 mt-4 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>

                    <!-- Jam -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Jam</strong></label>

                        <div class="col-sm-9">
                            <input type="time" class="form-control" name="jam">

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentjam" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 mt-4 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>

                    <!-- Hasil -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Hasil</strong></label>

                        <div class="col-sm-9">
                            <textarea name="hasil" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commenthasil" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 mt-4 d-flex align-items-start">
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

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Observasi kemajuan persalinan menggunakan patograf</small>
                            <textarea name="observasi" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentobservasi" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 mt-4 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
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

                        <div class="col-sm-9">
                            <input type="datetime-local" class="form-control" name="tanggaljam">    
                    
                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commenttanggaljam" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 mt-4 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>

                    <!-- Kontraksi Uterus -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Kontraksi Uterus</strong></label>

                        <div class="col-sm-9">
                            <textarea name="kontraksiuterus" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentkontraksiuterus" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 mt-4 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>

                    <!-- DJJ -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>DJJ</strong></label>

                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="djj">

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentdjj" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 mt-4 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>

                <!-- Keterangan -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Keterangan</strong></label>

                        <div class="col-sm-9">
                            <textarea name="keterangan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentketerangan" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 mt-4 d-flex align-items-start">
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
              <h5 class="card-title mb-1"><strong>B. Persalinan Kala II</strong></h5>

                <!-- General Form Elements -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

                <!-- Bagian Persalinan Kala II -->
                    
                    <!-- Mulai Persalinan dan Akhir -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Persalinan Kala II</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Mulai dan berakhir kala II (Tuliskan jam berapa mulai masuk ke kala II). Hasil:</small>
                            <textarea name="mulaipersalinandanakhir" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentmulaipersalinandanakhir" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 mt-4 d-flex align-items-start">
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
                        <textarea class="form-control mt-2" id="commenttandavital" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize:none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                    </div>
                </div>
            
                <!-- Tanda dan Gejala Kala II -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Tanda dan Gejala Kala II</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">(Ibu merasa semakin sakit, keringat lebih banyak, merasa mules dan ingin BAB, HIS semakin sering dan meningkat,
                                terjadi pengeluaran pervagina semakin banyak, vulva membuka, perineum meregang, anus mengembang dan membentu huruf D). Hasil:</small>
                            <textarea name="tandadangejalaII" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commenttandadangejalaII" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 mt-4 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>

                    <!-- Keluhan Tambahan -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Keluhan Tambahan</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">(pqrst). Hasil:</small>
                            <textarea name="keluhantambahan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentkeluhantambahan" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 mt-4 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>

                    <!-- Upaya -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Jelaskan tanda/tata cara mengejan</strong></label>

                        <div class="col-sm-9">
                            <textarea name="upaya" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentupaya" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>

                    <!-- Kebutuhan atau Tindakan -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Kebutuhan atau tindakan khusus yang dilakukan</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Hasil:</small>
                            <textarea name="kebutuhanataukeluhan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentkebutuhanataukeluhan" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 mt-4 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>

                    <!-- Lama Kala II -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Lama Kala II</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">(Catat berapa lama kala II berlangsung). Hasil:</small>
                            <textarea name="tandadangejala" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commenttandadangejala" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 mt-4 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>
                   
                    <!-- Bagian Catatan Kelahiran Bayi -->

                    <div class="row mb-2">
                        <label class="col-sm-2 col-form-label text-primary">
                            <strong>Catatan Kelahiran Bayi</strong>
                    </div>
                    
                    <!-- Jam  -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Bayi lahir jam berapa</strong></label>

                        <div class="col-sm-9">
                            <input type="datetime-local" class="form-control" name="lahirjamberapa">

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentlahirjamberapa" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 

                    <!-- Bagian Nilai APGAR -->
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-2 col-form-label"><strong>Nilai APGAR Menit I</strong></label>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="text" class="form-control" name="nilaiapgar">
                                <span class="input-group-text">menit</span>
                        </div>    
                    </div>
                                
                    <!-- V -->
                    <label class="col-sm-2 col-form-label"><strong>V</strong></label>
                    <div class="col-sm-3">
                        <div class="input-group">
                                <input type="text" class="form-control" name="nilaiapgarv">
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

                    <!-- Bonding Ibu dan Bayi -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Bonding Ibu dan Bayi</strong></label>

                        <div class="col-sm-9">
                            <textarea name="bondingibudanbayi" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentbondingibudanbayi" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 

                     <!-- Pengobatan -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Pengobatan</strong></label>

                            <div class="col-sm-9">
                                <textarea name="pengobatan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentpengobatan" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
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
              <h5 class="card-title mb-1"><strong>C. Persalinan Kala III</strong></h5>

                <!-- General Form Elements -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

                <!-- Bagian Persalinan Kala III -->
                    
                    <!--Placenta -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Plancenta lahir jam berapa</strong></label>

                        <div class="col-sm-9">
                            <input type="time" class="form-control" name="placenta">

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentplacenta" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>        

                    <!-- Tanda dan Gejala III -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Tanda dan Gejala III</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">(Rahim membulat, lebih mengeras, keluar darah tiba-tiba, tali pusat menjulur keluar.
                                TFU setinggi pusat, kontraksi rahim baik, kandung kemih kosong, uterus nampak bulat dan keras) dan (Perhatikan
                                keluhan pusing, mual, pendarahan, robekan perineum dan kondisi psikologis). Hasil:</small>
                            <textarea name="tandadangejalaiii" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commenttandadangejalaiii" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 mt-5 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>

                    <!-- Keluhan Lain yang dirasakan -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Keluhan Lain yang dirasakan</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Hasil:</small>
                            <textarea name="keluhanlain" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentkeluhanlain" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 mt-4 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
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
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="text" class="form-control" name="ukuran">
                        </div>    
                    </div>
                                
                    <!-- Panjang Tali Pusat -->
                    <label class="col-sm-2 col-form-label"><strong>Panjang Tali Pusat</strong></label>
                    <div class="col-sm-3">
                        <div class="input-group">
                                <input type="text" class="form-control" name="panjangtalipusat">
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

                    <!-- Bagian Jumlah Pengeluaran Darah -->
                 
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Jumlah Pengeluaran Darah</strong></label>

                        <div class="col-sm-9">
                            <div class="input-group">
                                <input type="text" class="form-control" name="jumlahpengeluarandarah">
                                <span class="input-group-text">ml</span>
                        </div>  

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentpengeluarandarah" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 

                    <!-- Karakteristik Darah -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Karakteristik Darah</strong></label>

                        <div class="col-sm-9">
                            <textarea name="karakteristikdarah" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentkarakteristikdarah" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 mt-4 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 

                     <!-- Tindakan/Kebutuhan Khusus -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Tindakan/Kebutuhan Khusus</strong></label>

                            <div class="col-sm-9">
                                <small class="form-text" style="color: red;">Hasil:</small>
                                <textarea name="tindakankebutuhankhusus" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commenttindakankebutuhankhusus" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 

                    <!-- Pengobatan -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Pengobatan</strong></label>

                        <div class="col-sm-9">
                            <textarea name="pengobatan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentpengobatan" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 mt-4 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>
        </div>
</div>
                    
            <div class="card">
            <div class="card-body">
              <h5 class="card-title mb-1"><strong>D. Persalinan Kala IV</strong></h5>

                <!-- General Form Elements -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

                <!-- Bagian Persalinan Kala IV -->
                    
                    <!-- Mulai Persalinan dan Akhir -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Mulai Jam Berapa Masuk Kala IV</strong></label>

                        <div class="col-sm-9">
                            <input type="time" class="form-control" name="kalaiv">

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentkalaiv" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 mt-4 d-flex align-items-start">
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
                        <textarea class="form-control mt-2" id="commenttandavital" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize:none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                    </div>
                </div>

                <!-- Tanda dan Gejala IV -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Tanda dan Gejala IV</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">(Observasi keadaan umum, keluhan pusing, mual, mata kunang-kunang, TTV,
                                kontraksi uterus, perdarahan (jumlah, warna, karakteristik, dan bau), pengosongan kandung kemih (setiap 15 menit pada 1 jam pertama dst),
                                periksa perineum, bersihkan ibu). Hasil:</small>
                            <textarea name="tandadangejalaiv" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commenttandadangejalaiv" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 mt-4 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>
                    
                    <!-- Keluhan Lain yang dirasakan -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Keluhan Lain yang dirasakan</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Hasil:</small>
                            <textarea name="keluhan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentkeluhan" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 mt-4 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 

                    <!-- Bagian Jumlah Pengeluaran Darah -->
                 
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Jumlah Pengeluaran Darah</strong></label>

                        <div class="col-sm-9">
                            <div class="input-group">
                                <input type="text" class="form-control" name="jumlahpengeluarandarah">
                                <span class="input-group-text">ml</span>
                        </div>  

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentpengeluarandarah" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 

                    <!-- Karakteristik Darah -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Karakteristik Darah</strong></label>

                        <div class="col-sm-9">
                            <textarea name="karakteristikdarah" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentkarakteristikdarah" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 mt-4 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 

                    <!-- Bonding Bayi dan Ibu -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Bonding Bayi dan Ibu</strong></label>

                        <div class="col-sm-9">
                            <textarea name="bondingibudanbayi" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentbondingibudanbayi" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 mt-4 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 

                    <!-- Tindakan/Kebutuhan Khusus -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Tindakan/Kebutuhan Khusus</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Hasil:</small>
                            <textarea name="tindakankebutuhankhusus" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commenttindakankebutuhankhusus" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 mt-4 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
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
