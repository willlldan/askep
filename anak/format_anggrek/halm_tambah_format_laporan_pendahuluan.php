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

                <?php
                    $jenisAnak = $_GET['jenisAnak'] ?? 'anggrek';
                   
                ?>

                <div class="row mb-3">
    <label for="jenisAnak" class="col-sm-2 col-form-label"><strong>Anak</strong></label>
    <div class="col-sm-9">

        <select class="form-select" name="jenisAnak"
        onchange="window.location=this.value" required>

        <option value="">Pilih</option>

        <option value="index.php?page=anak/format_anggrek&tab=format_laporan_pendahuluan&jenisAnak=anggrek"
        <?= $jenisAnak == 'anggrek' ? 'selected' : '' ?>>
        Format Anggrek B
        </option>

        <option value="index.php?page=anak/format_aster&tab=format_laporan_pendahuluan&jenisAnak=aster"
        <?= $jenisAnak == 'aster' ? 'selected' : '' ?>>
        Format Aster
        </option>

        <option value="index.php?page=anak/format_resume&tab=halm_tambah_format_resume_keperawatan&jenisAnak=poli_anak"
        <?= $jenisAnak == 'poli_anak' ? 'selected' : '' ?>>
        FORMAT RESUME KEPERAWATAN POLI ANAK
        </option>

        </select>

        <div class="invalid-feedback">
            Harap isi Jenis Anak.
        </div>

    </div>
</div>

             </div>
    </div>
    <!-- Card Identitas -->

    <div class="pagetitle">
        <h1><strong>Asuhan Keperawatan Anak Anggrek B</strong></h1>
    </div><!-- End Page Title -->
    <br>
<ul class="nav nav-tabs custom-tabs">

<li class="nav-item">
    <a class="nav-link <?= ($_GET['tab'] ?? 'format_laporan_pendahuluan') == 'format_laporan_pendahuluan' ? 'active' : '' ?>"
    href="index.php?page=anak/format_anggrek&tab=format_laporan_pendahuluan">
    Format Laporan Pendahuluan
    </a>
</li>

<li class="nav-item">
   <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'pengkajian' ? 'active' : '' ?>"
        href="index.php?page=anak/format_anggrek&tab=pengkajian"> Format Pengkajian Anak</a>
    </li>
    </a>
</li>
<li class="nav-item">
    <a class="nav-link <?= ($_GET['tab'] ?? '') == 'diagnosa' ? 'active' : '' ?>"
    href="index.php?page=anak/format_anggrek&tab=diagnosa">
    Diagnosa Keperawatan    </a>
</li>
<li class="nav-item">
    <a class="nav-link <?= ($_GET['tab'] ?? '') == 'rencana' ? 'active' : '' ?>"
    href="index.php?page=anak/format_anggrek&tab=rencana">
    Rencana Keperawatan
    </a>
</li>
<li class="nav-item">
    <a class="nav-link <?= ($_GET['tab'] ?? '') == 'implementasi' ? 'active' : '' ?>"
    href="index.php?page=anak/format_anggrek&tab=implementasi">
    Implementasi Keperawatan
    </a>
</li>
<li class="nav-item">
    <a class="nav-link <?= ($_GET['tab'] ?? '') == 'evaluasi' ? 'active' : '' ?>"
    href="index.php?page=anak/format_anggrek&tab=evaluasi">
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

                    <h5 class="card-title"><strong>FORMAT LAPORAN PENDAHULUAN</strong></h5>
                     <div class="row mb-2">
                        <label class="col-sm-3 col-form-label text-primary">
                            <strong>A.  Konsep Dasar Medis</strong>
                    </div>

           <!-- 1. Pengertian -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>1. Pengertian</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="pengertian" required>
        <textarea class="form-control mt-2" id="commentpengertian" rows="2"
            placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1 d-flex align-items-start">
        <div class="form-check">
            <input class="form-check-input" type="checkbox"
                onchange="document.getElementById('commentpengertian').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</div>

<!-- 2. Etiologi -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>2. Etiologi</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="etiologi" required>
        <textarea class="form-control mt-2" id="commentetiologi" rows="2"
            placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1 d-flex align-items-start">
        <div class="form-check">
            <input class="form-check-input" type="checkbox"
                onchange="document.getElementById('commentetiologi').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</div>

<!-- 3. Patofisiologi -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>3. Patofisiologi</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="patofisiologi" required>
        <textarea class="form-control mt-2" id="commentpatofisiologi" rows="2"
            placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1 d-flex align-items-start">
        <div class="form-check">
            <input class="form-check-input" type="checkbox"
                onchange="document.getElementById('commentpatofisiologi').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</div>

<!-- 4. Manifestasi Klinik -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>4. Manifestasi Klinik</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="manifestasi_klinik" required>
        <textarea class="form-control mt-2" id="commentmanifestasi" rows="2"
            placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1 d-flex align-items-start">
        <div class="form-check">
            <input class="form-check-input" type="checkbox"
                onchange="document.getElementById('commentmanifestasi').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</div>

<!-- 5. Pemeriksaan Diagnostic -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>5. Pemeriksaan Diagnostic</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="pemeriksaan_diagnostic" required>
        <textarea class="form-control mt-2" id="commentdiagnostic" rows="2"
            placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1 d-flex align-items-start">
        <div class="form-check">
            <input class="form-check-input" type="checkbox"
                onchange="document.getElementById('commentdiagnostic').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</div>

<!-- 6. Penatalaksanaan -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>6. Penatalaksanaan</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="penatalaksanaan" required>
        <textarea class="form-control mt-2" id="commentpenatalaksanaan" rows="2"
            placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1 d-flex align-items-start">
        <div class="form-check">
            <input class="form-check-input" type="checkbox"
                onchange="document.getElementById('commentpenatalaksanaan').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</div>

<!-- 7. Komplikasi -->
<div class="row mb-3">
    <label for="komplikasi" class="col-sm-2 col-form-label"><strong>7. Komplikasi</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="komplikasi" required>
        <textarea class="form-control mt-2" id="commentkomplikasi" rows="2"
            placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1 d-flex align-items-start">
        <div class="form-check">
            <input class="form-check-input" type="checkbox"
                onchange="document.getElementById('commentkomplikasi').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</div>

              <div class="row mb-2">
    <label class="col-sm-4 col-form-label text-primary">
        <strong>B. Konsep Dasar Keperawatan</strong>
    </label>
</div>
<form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

<!-- 1. Pengkajian Keperawatan -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>1. Pengkajian Keperawatan</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="pengkajian_keperawatan" required>
        <textarea class="form-control mt-2" id="commentpengkajian" rows="2"
            placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1 d-flex align-items-start">
        <div class="form-check">
            <input class="form-check-input" type="checkbox"
                onchange="document.getElementById('commentpengkajian').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</div>

<!-- 2. Penyimpangan KDM -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>2. Penyimpangan KDM</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="penyimpangan_kdm" required>
        <textarea class="form-control mt-2" id="commentkdm" rows="2"
            placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1 d-flex align-items-start">
        <div class="form-check">
            <input class="form-check-input" type="checkbox"
                onchange="document.getElementById('commentkdm').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</div>

<!-- 3. Diagnosa Keperawatan -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>3. Diagnosa Keperawatan</strong></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="diagnosa_keperawatan" required>
        <textarea class="form-control mt-2" id="commentdiagnosautama" rows="2"
            placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1 d-flex align-items-start">
        <div class="form-check">
            <input class="form-check-input" type="checkbox"
                onchange="document.getElementById('commentdiagnosautama').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</div>

<hr>

<!-- 4. Perencanaan -->
<div class="row mb-3">
    <label class="col-sm-3 col-form-label"><strong>4. Perencanaan</strong></label>
</div>

<!-- No -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>No</strong></label>
    <div class="col-sm-9">
        <textarea name="no" class="form-control" rows="1"
            style="overflow:hidden; resize: none;"
            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

        <textarea class="form-control mt-2" id="commentno" rows="2"
            placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1 d-flex align-items-start">
        <div class="form-check">
            <input class="form-check-input" type="checkbox"
                onchange="document.getElementById('commentno').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</div>

<!-- Diagnosa -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Diagnosa Keperawatan</strong></label>
    <div class="col-sm-9">
        <textarea name="diagnosa" class="form-control" rows="3"
            style="overflow:hidden; resize: none;"
            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

        <textarea class="form-control mt-2" id="commentdiagnosa" rows="2"
            placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1 d-flex align-items-start">
        <div class="form-check">
            <input class="form-check-input" type="checkbox"
                onchange="document.getElementById('commentdiagnosa').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</div>

<!-- Tujuan -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Tujuan & Kriteria Hasil</strong></label>
    <div class="col-sm-9">
        <textarea name="tujuan" class="form-control" rows="3"
            style="overflow:hidden; resize: none;"
            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

        <textarea class="form-control mt-2" id="commenttujuan" rows="2"
            placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1 d-flex align-items-start">
        <div class="form-check">
            <input class="form-check-input" type="checkbox"
                onchange="document.getElementById('commenttujuan').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</div>

<!-- Intervensi -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Intervensi</strong></label>
    <div class="col-sm-9">
        <textarea name="intervensi" class="form-control" rows="3"
            style="overflow:hidden; resize: none;"
            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

        <textarea class="form-control mt-2" id="commentintervensi" rows="2"
            placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1 d-flex align-items-start">
        <div class="form-check">
            <input class="form-check-input" type="checkbox"
                onchange="document.getElementById('commentintervensi').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</div>

<!-- Submit -->
<div class="row mb-3">
    <div class="col-sm-11 d-flex justify-content-end">
        <button type="submit" name="submit" class="btn btn-primary">Simpan</button>
    </div>
</div>

<!-- Daftar Pustaka -->
<div class="row mt-4 mb-2">
    <label class="col-sm-4 col-form-label text-primary">
        <strong>C. Daftar Pustaka</strong>
    </label>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="daftar_pustaka" required>
        <textarea class="form-control mt-2" id="commentpustaka" rows="2"
            placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1 d-flex align-items-start">
        <div class="form-check">
            <input class="form-check-input" type="checkbox"
                onchange="document.getElementById('commentpustaka').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</div>

</form>
</div><?php include "tab_navigasi.php"; ?>

</main>



    


