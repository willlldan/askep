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

                        <option value="index.php?page=maternitas/pengkajian_antenatal_care&tab=demografi&jenismaternitas=antenatal"
                        <?= $jenismaternitas == 'antenatal' ? 'selected' : '' ?>>
                        Pengkajian Antenatal Care
                        </option>

                        <option value="index.php?page=maternitas/pengkajian_pascapartum&tab=umum&jenismaternitas=pascapartum"
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

                <h5 class="card-title"><strong>DATA UMUM</strong></h5>

                <!-- General Form Elements -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">
                
                    <!-- Bagian Inisial Pasien -->

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Inisial Pasien</strong></label>

                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="inisialpasien">
                        </div>
                    </div>

                <!-- Bagian Usia -->
                <div class="row mb-3">
                    <label for="usiaistri" class="col-sm-2 col-form-label"><strong>Usia</strong></label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="usiaistri">
                         </div>
                    </div>

                <!-- Bagian Pekerjaan -->
                <div class="row mb-3">
                    <label for="pekerjaanistri" class="col-sm-2 col-form-label"><strong>Pekerjaan</strong></label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="pekerjaanistri">
                         </div>
                    </div>

                <!-- Bagian Pendidikan Terakhir -->
                <div class="row mb-3">
                    <label for="pendidikanterakhiristri" class="col-sm-2 col-form-label"><strong>Pendidikan Terakhir</strong></label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="pendidikanterakhiristri">
                         </div>
                    </div>

                <!-- Bagian Agama -->
                <div class="row mb-3">
                    <label for="agamaistri" class="col-sm-2 col-form-label"><strong>Agama</strong></label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="agamaistri">
                         </div>
                    </div>

                <!-- Bagian Suku Bangsa -->
                <div class="row mb-3">
                    <label for="sukubangsa" class="col-sm-2 col-form-label"><strong>Suku Bangsa</strong></label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="sukubangsa">
                         </div>
                    </div>

                <!-- Bagian Status Perkawinan -->
                <div class="row mb-3">
                    <label for="statusperkawinan" class="col-sm-2 col-form-label"><strong>Status Perkawinan</strong></label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="statusperkawinan">
                         </div>
                    </div>

                <!-- Bagian Alamat -->
                <div class="row mb-3">
                    <label for="alamat" class="col-sm-2 col-form-label"><strong>Alamat</strong></label>
                    <div class="col-sm-10">
                        <textarea name="alamat" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                         </div>
                    </div>

                <!-- Bagian Diagnosa Medik -->
                <div class="row mb-3">
                    <label for="diagnosamedik" class="col-sm-2 col-form-label"><strong>Diagnosa Medik</strong></label>
                    <div class="col-sm-10">
                        <textarea name="diagnosamedik" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                         </div>
                    </div>

                <!-- Bagian Nama Suami -->
                <div class="row mb-3">
                    <label for="namasuami" class="col-sm-2 col-form-label"><strong>Nama Suami</strong></label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="namasuami">
                         </div>
                    </div>

                <!-- Bagian Usia -->
                <div class="row mb-3">
                    <label for="usiasuami" class="col-sm-2 col-form-label"><strong>Usia</strong></label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="usiasuami">
                         </div>
                    </div>

                <!-- Bagian Pekerjaan -->
                <div class="row mb-3">
                    <label for="pekerjaansuami" class="col-sm-2 col-form-label"><strong>Pekerjaan</strong></label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="pekerjaansuami">
                         </div>
                    </div>

                <!-- Bagian Pendidikan Terakhir -->
                <div class="row mb-3">
                    <label for="pendidikanterakhirsuami" class="col-sm-2 col-form-label"><strong>Pendidikan Terakhir</strong></label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="pendidikanterakhirsuami">
                         </div>
                    </div>

                <!-- Bagian Agama -->
                <div class="row mb-3">
                    <label for="agamasuami" class="col-sm-2 col-form-label"><strong>Agama</strong></label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="agamasuami">
                        </div>
                    </div>

                <h5 class="card-title"><strong>DATA UMUM KESEHATAN</strong></h5>

                <!-- General Form Elements -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">
                
                <!-- Bagian BB/TB -->
                        <div class="row mb-3">
                            <label for="bbtb" class="col-sm-2 col-form-label"><strong>BB/TB</strong></label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="bbtb">
                                    <span class="input-group-text">kg/cm</span>
                        </div>
                         </div>
                        </div>

                <!-- Bagian BB Sebelum Hamil -->
                        <div class="row mb-3">
                            <label for="bbtb" class="col-sm-2 col-form-label"><strong>BB Sebelum Hamil</strong></label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="bbsebelumhamil">
                                    <span class="input-group-text">kg</span>
                        </div>
                         </div>
                        </div> 
                        
                <!-- Bagian LILA -->
                        <div class="row mb-3">
                            <label for="bbtb" class="col-sm-2 col-form-label"><strong>LILA</strong></label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="lila">
                                    <span class="input-group-text">cm</span>
                        </div>
                         </div>
                        </div> 
                        
                <!-- Bagian Rencana Kehamilan -->
                 <div class="row mb-3">
                    <label for="jeniskelamin" class="col-sm-2 col-form-label"><strong>Kehamilan Sekarang direncanakan</strong></label> 
                    <div class="col-sm-10">
                    <select class="form-select" name="rencanakehamilan">
                            <option value="">Pilih</option>
                            <option value="Ya">Ya</option>
                            <option value="Tidak">Tidak</option>
                            </select>
                         </div>
                    </div>
                    
                <!-- Bagian Status Obstetrik -->

                        <div class="row mb-3">

                        <label class="col-sm-2 col-form-label"><strong>Status Obstetrik</strong></label>
                            <div class="col-sm-10">
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
                         </div>
                    </div>
                    
                <!-- HPHT -->
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-2 col-form-label"><strong>HPHT</strong></label>
                        <div class="col-sm-4">
                            <div class="input-group">
                                <input type="text" class="form-control" name="hpht">
                        </div>    
                    </div>
                                
                    <!-- TP -->
                    <label class="col-sm-2 col-form-label"><strong>TP</strong></label>
                    <div class="col-sm-4">
                        <div class="input-group">
                                <input type="text" class="form-control" name="tp">
                        </div>  
                    </div>    
                </div>  
                
                <!-- Bagian Obat Dikonsumsi -->
                <div class="row mb-3">
                    <label for="obatdikonsumsi" class="col-sm-2 col-form-label"><strong>Obat-obatan yang dikonsumsi</strong></label>
                    <div class="col-sm-10">
                        <textarea name="obatdikonsumsi" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                         </div>
                    </div>

                <!-- Bagian Alergi -->
                <div class="row mb-3">
                    <label for="alergi" class="col-sm-2 col-form-label"><strong>Apakah ada alergi terhadap sesuatu</strong></label>
                    <div class="col-sm-10">
                        <textarea name="alergi" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                         </div>
                    </div>  
                    
                <!-- Bagian Alat Bantu -->
                <div class="row mb-3">
                    <label for="alatbantu" class="col-sm-2 col-form-label"><strong>Alat Bantu yang Digunakan</strong></label>
                    <div class="col-sm-10">
                        <small class="form-text" style="color: red;">Gigi tiruan, kacamata/lensa kontak, alat dengar, dan lain-lain. Sebutkan</small>
                        <textarea name="alatbantu" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                         </div>
                    </div>     
                    
                <!-- Bagian BAK -->
                <div class="row mb-3">
                    <label for="bakterakhir" class="col-sm-2 col-form-label"><strong>BAK Terakhir</strong></label>
                    <div class="col-sm-10">
                        <small class="form-text" style="color: red;">Masalah:</small>
                        <textarea name="bakterakhir" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                         </div>
                    </div>

                <!-- Bagian BAB Terakhir -->
                <div class="row mb-3">
                    <label for="babterakhir" class="col-sm-2 col-form-label"><strong>BAB Terakhir</strong></label>
                    <div class="col-sm-10">
                        <small class="form-text" style="color: red;">Masalah:</small>
                        <textarea name="babterakhir" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                         </div>
                    </div>

                <!-- Bagian Kebiasaan Waktu Tidur -->

                <!-- Bagian Kebiasaan Waktu Tidur -->
                <div class="row mb-3">

                    <label class="col-sm-2 col-form-label"><strong>Kebiasaan Waktu Tidur</strong></label>

                    <!-- Siang -->
                    <label class="col-sm-1 col-form-label"><strong>Siang</strong></label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" name="siang">
                    </div>

                    <!-- Malam -->
                    <label class="col-sm-1 col-form-label"><strong>Malam</strong></label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" name="malam">
                    </div>
                </div>

                <!-- Bagian Riwayat Kesehatan yang lalu -->
                <div class="row mb-3">
                    <label for="riwayatkesehatan" class="col-sm-2 col-form-label"><strong>Riwayat Kesehatan yang Lalu</strong></label>
                    <div class="col-sm-10">
                        <textarea name="riwayatkesehatan" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                         </div>
                    </div>
                        
    <?php include "tab_navigasi.php"; ?>

                         
    </section>             
</main>

                        


