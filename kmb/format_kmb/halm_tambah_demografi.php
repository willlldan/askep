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

                <!-- Jenis KMB -->

                <?php
                    $jenismaternitas = $_GET['jeniskmb'] ?? 'kmb';
                   
                ?>

                <div class="row mb-3">
                    <label for="jeniskmb" class="col-sm-2 col-form-label"><strong>Keperawatan Medikal Bedah</strong></label>
                        <div class="col-sm-9">

                                <select class="form-select" name="jeniskmb"
                        onchange="window.location=this.value" required>

                        <option value="">Pilih</option>

                        <option value="index.php?page=kmb/pengkajian_ruang_ok&tab=pengkajian&jeniskmb=ok"
                        <?= $jenismaternitas == 'ok' ? 'selected' : '' ?>>
                        Pengkajian Askep Ruang ok
                        </option>

                        <option value="index.php?page=kmb/format_kmb&tab=lp_ruanghd&jeniskmb=kmb"
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
        <h1><strong>KEPERAWATAN MEDIKAL BEDAH </strong></h1>
    </div><!-- End Page Title -->
    <br>

    <ul class="nav nav-tabs custom-tabs">

    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'demografi' ? 'active' : '' ?>"
        href="index.php?page=kmb/format_kmb&tab=demografi"> Format Laporan Pendahuluan (LP) Keperawatan Medikal Bedah  </a>
    </li>

    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'format_askep' ? 'active' : '' ?>"
        href="index.php?page=kmb/format_kmb&tab=format_askep"> Format Askep KMB </a>
    </li>
        <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'klasifikasi_data' ? 'active' : '' ?>"
        href="index.php?page=kmb/format_kmb&tab=klasifikasi_data"> Klasifikasi Data </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'analisa' ? 'active' : '' ?>"
        href="index.php?page=kmb/format_kmb&tab=analisa"> Analisa Data </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'diagnosa_keperawatan' ? 'active' : '' ?>"
        href="index.php?page=kmb/format_kmb&tab=diagnosa_keperawatan"> Diagnosa Keperawatan </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'rencana' ? 'active' : '' ?>"
        href="index.php?page=kmb/format_kmb&tab=rencana"> Rencana Keperawatan </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'implementasi_keperawatan' ? 'active' : '' ?>"
        href="index.php?page=kmb/format_kmb&tab=implementasi_keperawatan"> Implementasi Keperawatan </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'evaluasi_keperawatan' ? 'active' : '' ?>"
        href="index.php?page=kmb/format_kmb&tab=evaluasi_keperawatan"> Evaluasi Keperawatan </a>
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

                <h5 class="card-title"><strong>FORMAT LAPORAN PENDAHULUAN (LP) KEPERAWATAN MEDIKAL BEDAH </strong></h5>

                <!-- General Form Elements -->
                <!-- A KONSEP DASAR MEDIS -->
<div class="row mb-2">
<label class="col-sm-12 text-primary">
<strong>A. KONSEP DASAR MEDIS</strong>
</label>
</div>

<!-- 1 PENGERTIAN -->
<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>1. Pengertian</strong></label>

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
<label class="col-sm-2 col-form-label"><strong>6. Pemeriksaan Diagnostik</strong></label>

<div class="col-sm-9">
<textarea class="form-control" rows="4" name="pemeriksaan_diagnostik"></textarea>

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


<!-- B KONSEP DASAR KEPERAWATAN -->
<div class="row mb-2">
<label class="col-sm-12 text-primary">
<strong>B. KONSEP DASAR KEPERAWATAN</strong>
</label>
</div>

<!-- 1 PENGKAJIAN KEPERAWATAN -->
<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>1. Pengkajian Keperawatan</strong></label>

<div class="col-sm-9">
<textarea class="form-control" rows="4" name="pengkajian_keperawatan"></textarea>

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

<!-- 2 PENYIMPANGAN KDM -->
<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>2. Penyimpangan KDM</strong></label>

<div class="col-sm-9">
<textarea class="form-control" rows="4" name="penyimpangan_kdm"></textarea>

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

<!-- 3 DIAGNOSA KEPERAWATAN -->
<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>3. Diagnosa Keperawatan</strong></label>

<div class="col-sm-9">
<textarea class="form-control" rows="4" name="diagnosa_keperawatan"></textarea>

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
                        <label class="col-sm-2 col-form-label"><strong>4. Perencanaan</strong></label>
</div>
                          
<!-- 4 PERENCANAAN -->
<form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">
                        <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>No</strong></label>

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

                <!-- Bagian Diagnosa -->

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Diagnosa</strong></label>

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

                    <h5 class="card-title mt-2"><strong>Perencanaan</strong></h5>

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
                                <th class="text-center">No</th>
                                <th class="text-center">Diagnosa</th>
                                <th class="text-center">Tujuan dan Kriteria Hasil</th>
                                <th class="text-center">Intervensi</th>
                        </tr>
                        </thead>

                    <tbody>

                    <?php
                    if(!empty($data)){
                        foreach($data as $row){
                            echo "<tr>
                            <td>".$row['No']."</td>
                            <td>".$row['diagnosa']."</td>
                            <td>".$row['tujuan']."</td>
                            <td>".$row['intervensi']."</td>
                            </tr>";
                        }
                    }
                    ?>

                    </tbody>
                    </table>

<!-- C DAFTAR PUSTAKA -->
<div class="row mb-2">
<label class="col-sm-12 text-primary">
<strong>C. DAFTAR PUSTAKA</strong>
</label>
</div>

<div class="row mb-3">
<label class="col-sm-2 col-form-label"></label>

<div class="col-sm-9">
<textarea class="form-control" rows="5" name="daftar_pustaka"></textarea>

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

                    <?php include "tab_navigasi.php"; ?>

</main>

                        



