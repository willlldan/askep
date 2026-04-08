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
                    $jenisJiwa = $_GET['jenisJiwa'] ?? 'Jiwa_RSUD';
                   
                ?>

                <div class="row mb-3">
                    <label for="jenisJiwa" class="col-sm-2 col-form-label"><strong>Jiwa</strong></label>
                        <div class="col-sm-9">

                        <select class="form-select" name="jenisJiwa"
                        onchange="window.location=this.value" required>

                       <option value="">Pilih</option>
                        <option value="index.php?page=jiwa/jiwa_rsud&tab=format_laporan_pendahuluan&jenisJiwa=Jiwa_RSUD"
                        <?= $jenisJiwa == 'Jiwa_RSUD' ? 'selected' : '' ?>>
                        Jiwa RSUD
                        </option>

                        <option value="index.php?page=jiwa/poli_jiwa&tab=format_laporan_pendahuluan&jenisJiwa=Poli_Jiwa"
                        <?= $jenisJiwa == 'Poli_Jiwa' ? 'selected' : '' ?>>
                        Poli Jiwa
                        </option>
                       

                        </select>
                        <div class="invalid-feedback">
                            Harap isi Jenis Maternitas.
                        </div>
                    </div>
                </div>

             </div>
    </div>
    <!-- Card Identitas -->

    <div class="pagetitle">
        <h1><strong>Asuhan Keperawatan Jiwa RSUD</strong></h1>
    </div><!-- End Page Title -->
    <br>
<ul class="nav nav-tabs custom-tabs">

<li class="nav-item">
    <a class="nav-link <?= ($_GET['tab'] ?? 'format_laporan_pendahuluan') == 'format_laporan_pendahuluan' ? 'active' : '' ?>"
    href="index.php?page=jiwa/jiwa_rsud&tab=format_laporan_pendahuluan">
    Format Laporan Pendahuluan
    </a>
</li>



<li class="nav-item">
    <a class="nav-link <?= ($_GET['tab'] ?? '') == 'pengkajian' ? 'active' : '' ?>"
    href="index.php?page=jiwa/jiwa_rsud&tab=pengkajian">
    Format Pengkajian Keperawatan Jiwa
    </a>
</li>
<li class="nav-item">
    <a class="nav-link <?= ($_GET['tab'] ?? '') == 'diagnosa' ? 'active' : '' ?>"
    href="index.php?page=jiwa/jiwa_rsud&tab=diagnosa">
    Diagnosa Keperawatan
    </a>
</li>
<li class="nav-item">
    <a class="nav-link <?= ($_GET['tab'] ?? '') == 'rencana' ? 'active' : '' ?>"
    href="index.php?page=jiwa/jiwa_rsud&tab=rencana">
    Rencana Keperawatan
    </a>
</li>
<li class="nav-item">
    <a class="nav-link <?= ($_GET['tab'] ?? '') == 'implementasi' ? 'active' : '' ?>"
    href="index.php?page=jiwa/jiwa_rsud&tab=implementasi">
        Implementasi Keperawatan
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
            <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">
                
                <h5 class="card-title"><strong>FORMAT LAPORAN PENDAHULUAN PRAKTIK KLINIK KEPERAWATAN JIWA</strong></h5>

                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label text-primary">
                        <strong>A. Masalah Keperawatan Utama</strong>
                    </label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="masalah_keperawatan_utama">
                        <textarea class="form-control mt-2" id="comment_masalah" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!. Jika ada revisi, tetap semangat mengerjakannya!. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                    </div>
                    <div class="col-sm-1 d-flex align-items-start">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" onchange="document.getElementById('comment_masalah').style.display=this.checked?'none':'block'">
                        </div>
                    </div>
                </div>

                <div class="row mb-2">
                    <label class="col-sm-4 col-form-label text-primary">
                        <strong>B. Proses Terjadinya Masalah</strong>
                    </label>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><strong>1. Pengertian</strong></label>
                    <div class="col-sm-9">
                        <textarea name="pengertian" class="form-control" rows="3"></textarea>
                        <textarea class="form-control mt-2" id="comment_pengertian" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!. Jika ada revisi, tetap semangat mengerjakannya!. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                    </div>
                    <div class="col-sm-1 d-flex align-items-start">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" onchange="document.getElementById('comment_pengertian').style.display=this.checked?'none':'block'">
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><strong>2. Tanda dan Gejala</strong></label>
                    <div class="col-sm-9">
                        <textarea name="tanda_gejala" class="form-control" rows="3"></textarea>
                        <textarea class="form-control mt-2" id="comment_gejala" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!. Jika ada revisi, tetap semangat mengerjakannya!. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                    </div>
                    <div class="col-sm-1 d-flex align-items-start">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" onchange="document.getElementById('comment_gejala').style.display=this.checked?'none':'block'">
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><strong>3. Rentang Respons</strong></label>
                    <div class="col-sm-9">
                        <textarea name="rentang_respons" class="form-control" rows="3"></textarea>
                        <textarea class="form-control mt-2" id="comment_respons" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!. Jika ada revisi, tetap semangat mengerjakannya!. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                    </div>
                    <div class="col-sm-1 d-flex align-items-start">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" onchange="document.getElementById('comment_respons').style.display=this.checked?'none':'block'">
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><strong>4. Faktor Predisposisi</strong></label>
                    <div class="col-sm-9">
                        <textarea name="faktor_predisposisi" class="form-control" rows="3"></textarea>
                        <textarea class="form-control mt-2" id="comment_predisposisi" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!. Jika ada revisi, tetap semangat mengerjakannya!. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                    </div>
                    <div class="col-sm-1 d-flex align-items-start">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" onchange="document.getElementById('comment_predisposisi').style.display=this.checked?'none':'block'">
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><strong>5. Faktor Presipitasi</strong></label>
                    <div class="col-sm-9">
                        <textarea name="faktor_presipitasi" class="form-control" rows="3"></textarea>
                        <textarea class="form-control mt-2" id="comment_presipitasi" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!. Jika ada revisi, tetap semangat mengerjakannya!. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                    </div>
                    <div class="col-sm-1 d-flex align-items-start">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" onchange="document.getElementById('comment_presipitasi').style.display=this.checked?'none':'block'">
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><strong>6. Sumber Koping</strong></label>
                    <div class="col-sm-9">
                        <textarea name="sumber_koping" class="form-control" rows="3"></textarea>
                        <textarea class="form-control mt-2" id="comment_koping" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!. Jika ada revisi, tetap semangat mengerjakannya!. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                    </div>
                    <div class="col-sm-1 d-flex align-items-start">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" onchange="document.getElementById('comment_koping').style.display=this.checked?'none':'block'">
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><strong>7. Mekanisme Koping</strong></label>
                    <div class="col-sm-9">
                        <textarea name="mekanisme_koping" class="form-control" rows="3"></textarea>
                        <textarea class="form-control mt-2" id="comment_mekanisme" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!. Jika ada revisi, tetap semangat mengerjakannya!. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                    </div>
                    <div class="col-sm-1 d-flex align-items-start">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" onchange="document.getElementById('comment_mekanisme').style.display=this.checked?'none':'block'">
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><strong>8. Pohon Masalah</strong></label>
                    <div class="col-sm-9">
                        <textarea name="pohon_masalah" class="form-control" rows="3"></textarea>
                        <textarea class="form-control mt-2" id="comment_pohon" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!. Jika ada revisi, tetap semangat mengerjakannya!. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                    </div>
                    <div class="col-sm-1 d-flex align-items-start">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" onchange="document.getElementById('comment_pohon').style.display=this.checked?'none':'block'">
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><strong>9. Masalah Keperawatan yang Mungkin Muncul</strong></label>
                    <div class="col-sm-9">
                        <textarea name="masalah_keperawatan_muncul" class="form-control" rows="3"></textarea>
                        <textarea class="form-control mt-2" id="comment_masalah_muncul" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!. Jika ada revisi, tetap semangat mengerjakannya!. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-4 col-form-label"><strong>10. Data yang perlu dikaji</strong></label>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><strong>Masalah Keperawatan</strong></label>
                    <div class="col-sm-9">
                        <textarea name="diagnona" class="form-control" rows="3" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        <textarea class="form-control mt-2" id="commentdiagnosa" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!. Jika ada revisi, tetap semangat mengerjakannya!." style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                    </div>
                    <div class="col-sm-1 d-flex align-items-start">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" disabled>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><strong>Subjektif :</strong></label>
                    <div class="col-sm-9">
                        <textarea name="diagnona" class="form-control" rows="3" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        <textarea class="form-control mt-2" id="commentdiagnosa_sub" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!. Jika ada revisi, tetap semangat mengerjakannya!." style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                    </div>
                    <div class="col-sm-1 d-flex align-items-start">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" disabled>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><strong>Objektif:</strong></label>
                    <div class="col-sm-9">
                        <textarea name="diagnona" class="form-control" rows="3" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        <textarea class="form-control mt-2" id="commentdiagnosa_obj" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!. Jika ada revisi, tetap semangat mengerjakannya!." style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                    </div>
                    <div class="col-sm-1 d-flex align-items-start">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" disabled>
                        </div>
                    </div>
                </div>

                <style>
                    .table-pemeriksaan { table-layout: fixed; width:100% }
                    .table-pemeriksaan td, .table-pemeriksaan th { word-wrap: break-word; white-space: normal; vertical-align: top; }
                </style>

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">Masalah Keperawatan</th>
                            <th class="text-center">Data yang Perlu Dikaji</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if(!empty($data)){
                            foreach($data as $row){
                                echo "<tr>
                                    <td>".$row['Masalah_Keperawatan']."</td>
                                    <td>".$row['Data_yang_Perlu_Dikaji']."</td>
                                </tr>";
                            }
                        }
                        ?>
                    </tbody>
                </table>

                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><strong>11. Diagnosa Keperawatan yang Mungkin Muncul</strong></label>
                    <div class="col-sm-9">
                        <textarea name="diagnosa_muncul" class="form-control" rows="3"></textarea>
                    <textarea class="form-control mt-2" id="comment_rencana_tindakan" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                    </div>
                    <div class="col-sm-1 d-flex align-items-start">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" onchange="document.getElementById('comment_rencana_tindakan').style.display=this.checked?'none':'block'">
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><strong>12. Rencana Tindakan Keperawatan</strong></label>
                    <div class="col-sm-9">
                        <textarea name="rencana_tindakan" class="form-control" rows="3"></textarea>
                        <textarea class="form-control mt-2" id="comment_rencana_tindakan" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                    </div>
                    <div class="col-sm-1 d-flex align-items-start">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" onchange="document.getElementById('comment_rencana_tindakan').style.display=this.checked?'none':'block'">
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><strong>13. Daftar Pustaka</strong></label>
                    <div class="col-sm-9">
                        <textarea name="daftar_pustaka" class="form-control" rows="3"></textarea>
                        <textarea class="form-control mt-2" id="comment_pustaka" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                    </div>
                    <div class="col-sm-1 d-flex align-items-start">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" onchange="document.getElementById('comment_pustaka').style.display=this.checked?'none':'block'">
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-11 justify-content-end d-flex">
                        <button type="submit" name="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>

            </form>
        </div>
    </div>
    <?php include "tab_navigasi.php"; ?>
</section>
</main>

    


