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
                
                <!-- Bagian Nama Mahasiswa -->
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

                <!-- Jenis KMB -->

                <?php
                    $jenismaternitas = $_GET['jeniskmb'] ?? 'hd';
                   
                ?>

                <div class="row mb-3">
                    <label for="jeniskmb" class="col-sm-2 col-form-label"><strong>Keperawatan Medikal Bedah</strong></label>
                        <div class="col-sm-9">

                                <select class="form-select" name="jeniskmb"
                        onchange="window.location=this.value" required>

                        <option value="">Pilih</option>

                        <option value="index.php?page=kmb/pengkajian_ruang_ok&tab=pengkajian&jeniskmb=ok"
                        <?= $jenismaternitas == 'ok' ? 'selected' : '' ?>>
                        Pengkajian Askep Ruang OK
                        </option>

                        <option value="index.php?page=kmb/format_kmb&tab=demografi&jeniskmb=kmb"
                        <?= $jenismaternitas == 'kmb' ? 'selected' : '' ?>>
                        Format KMB
                        </option>

                        <option value="index.php?page=kmb/format_hd_kmb&tab=demografi&jeniskmb=hd"
                        <?= $jenismaternitas == 'hd' ? 'selected' : '' ?>>
                        Format HD KMB
                        </option>

                        </select>
                        <div class="invalid-feedback">
                            Harap isi Jenis KMB.
                        </div>
                    </div>
                </div>

             </div>
    </div>

    <div class="pagetitle">
        <h1><strong>Format HD</strong></h1>
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
                                
                     <!-- General Form Elements -->
                    <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

                    <h5 class="card-title"><strong> FORMAT LAPORAN PENDAHULUAN RUANG HD</strong></h5>
                                 <!-- A KONSEP DASAR MEDIS -->
<div class="row mb-2">
<label class="col-sm-12 text-primary">
<strong>A.  Konsep Dasar Penyakit (Chronic Kidney Disease (CKD))</strong>
</label>
</div>

<!-- 1 PENGERTIAN -->
<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>1. Definisi </strong></label>

<div class="col-sm-9">
<textarea class="form-control" rows="4" name="pengertian"></textarea>

<textarea class="form-control mt-2" rows="2"
placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!"
readonly></textarea>
</div>

<div class="col-sm-1">
<div class="form-check">
<input class="form-check-input" type="checkbox">
</div>
</div>
</div>

<!-- 2 ETIOLOGI -->
<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>2. Klasifikasi</strong></label>

<div class="col-sm-9">
<textarea class="form-control" rows="4" name="etiologi"></textarea>

<textarea class="form-control mt-2" rows="2"
placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!"
readonly></textarea>
</div>

<div class="col-sm-1">
<div class="form-check">
<input class="form-check-input" type="checkbox">
</div>
</div>
</div>

<!-- 2 ETIOLOGI -->
<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>3. Etiologi</strong></label>

<div class="col-sm-9">
<textarea class="form-control" rows="4" name="etiologi"></textarea>

<textarea class="form-control mt-2" rows="2"
placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!"
readonly></textarea>
</div>

<div class="col-sm-1">
<div class="form-check">
<input class="form-check-input" type="checkbox">
</div>
</div>
</div>

<!-- 3 PATOFISIOLOGI -->
<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>4. Manifestasi Klinik </strong></label>

<div class="col-sm-9">
<textarea class="form-control" rows="4" name="manifestasi_klinik"></textarea>

<textarea class="form-control mt-2" rows="2"
placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!"
readonly></textarea>
</div>

<div class="col-sm-1">
<div class="form-check">
<input class="form-check-input" type="checkbox">
</div>
</div>
</div>

<!-- 4 MANIFESTASI KLINIK -->
<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>5. Patofisiologi </strong></label>

<div class="col-sm-9">
<textarea class="form-control" rows="4" name="patofisiologi"></textarea>

<textarea class="form-control mt-2" rows="2"
placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!"
readonly></textarea>
</div>

<div class="col-sm-1">
<div class="form-check">
<input class="form-check-input" type="checkbox">
</div>
</div>
</div>

<!-- 5 PEMERIKSAAN DIAGNOSTIC -->
<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>6. Pemeriksaan penunjang</strong></label>

<div class="col-sm-9">
<textarea class="form-control" rows="4" name="pemeriksaan_penunjang"></textarea>

<textarea class="form-control mt-2" rows="2"
placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!"
readonly></textarea>
</div>

<div class="col-sm-1">
<div class="form-check">
<input class="form-check-input" type="checkbox">
</div>
</div>
</div>

<!-- 6 PENATALAKSANAAN -->
<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>7. Penatalaksanaan</strong></label>

<div class="col-sm-9">
<textarea class="form-control" rows="4" name="penatalaksanaan"></textarea>

<textarea class="form-control mt-2" rows="2"
placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!"
readonly></textarea>
</div>

<div class="col-sm-1">
<div class="form-check">
<input class="form-check-input" type="checkbox">
</div>
</div>
</div>
<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>8. Komplikasi</strong></label>

<div class="col-sm-9">
<textarea class="form-control" rows="4" name="komplikasi"></textarea>

<textarea class="form-control mt-2" rows="2"
placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!"
readonly></textarea>
</div>

<div class="col-sm-1">
<div class="form-check">
<input class="form-check-input" type="checkbox">
</div>
</div>
</div>
                    <div class="row mb-2">
                        <label class="col-sm-4 col-form-label text-primary">
                            <strong>B.  Konsep Dasar Hemodialisa</strong>
                    </div>

            <!-- Bagian Pengertian -->
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label"><strong>1. Pengertian</strong></label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" name="Pengertian" required>
                      <!-- comment -->
                            <textarea class="form-control mt-2" id="commentinisialpasien" rows="2" placeholder="Jika ada revisi atau saran dari Ibu/Bapak Dosen, silakan diketik di sini. Terima kasih." style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" onchange="document.getElementById('commentinisialpasien'). style.display= this.checked ? 'none' : 'block'">
                            </div>
                         </div>
                    </div>

            <!-- Bagian Etiologi -->
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label"><strong>2. Tujuan</strong></label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" name="Etiologi" required>
                    <textarea class="form-control mt-2" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
                </div>
                <div class="col-sm-1 d-flex align-items-start">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" onchange="document.getElementById('commentusiaistri').style.display = this.checked ? 'none' : 'block'">
                    </div>
                </div>
            </div>

            <!-- Bagian Patofisiologi -->
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label"><strong>3. Proses Hemodialisa</strong></label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" name="Patofisiolog" required>
                    <textarea class="form-control mt-2" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
                </div>
                <div class="col-sm-1 d-flex align-items-start">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" onchange="document.getElementById('commentusiaistri').style.display = this.checked ? 'none' : 'block'">
                    </div>
                </div>
            </div>

            <!-- Bagian Manifestasi Klinik -->
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label"><strong>4. Alasan dilakukan Hemodialisa</strong></label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" name="Manifestasi_Klinik" required>
                    <textarea class="form-control mt-2" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
                </div>
                <div class="col-sm-1 d-flex align-items-start">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" onchange="document.getElementById('commentusiaistri').style.display = this.checked ? 'none' : 'block'">
                    </div>
                </div>
            </div>

            <!-- Bagian Pemeriksaan Diagnostic -->
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label"><strong>5. Indikasi Hemodialisa</strong></label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" name="Pemeriksaan_diagnostic" required>
                    <textarea class="form-control mt-2" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
                </div>
                <div class="col-sm-1 d-flex align-items-start">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" onchange="document.getElementById('commentusiaistri').style.display = this.checked ? 'none' : 'block'">
                    </div>
                </div>
            </div>

            <!-- Bagian Penatalaksanaan -->
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label"><strong>6. Kontraindikasi Hemodialisa</strong></label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" name="Penatalaksanaan" required>
                    <textarea class="form-control mt-2" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
                </div>
                <div class="col-sm-1 d-flex align-items-start">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" onchange="document.getElementById('commentusiaistri').style.display = this.checked ? 'none' : 'block'">
                    </div>
                </div>
                </div>
                  <!-- Bagian Penatalaksanaan -->
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label"><strong>7.   Frekuensi Hemodialisa</strong></label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" name="Penatalaksanaan" required>
                    <textarea class="form-control mt-2" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
                </div>
                <div class="col-sm-1 d-flex align-items-start">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" onchange="document.getElementById('commentusiaistri').style.display = this.checked ? 'none' : 'block'">
                    </div>
                </div>
                </div>
                  <!-- Bagian Penatalaksanaan -->
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label"><strong>8.   Komplikasi Hemodialisa</strong></label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" name="Penatalaksanaan" required>
                    <textarea class="form-control mt-2" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
                </div>
                <div class="col-sm-1 d-flex align-items-start">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" onchange="document.getElementById('commentusiaistri').style.display = this.checked ? 'none' : 'block'">
                    </div>
                </div>
                </div>

                    <?php include "tab_navigasi.php"; ?>

</main>

                        



