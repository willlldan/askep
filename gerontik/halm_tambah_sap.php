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
        <h1><strong>Asuhan Keperawatan Gerontik</strong></h1>
    </div><!-- End Page Title -->
    <br>


    <ul class="nav nav-tabs custom-tabs">

   <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? 'pengkajian') == 'pengkajian' ? 'active' : '' ?>"
        href="index.php?page=gerontik&tab=pengkajian">
        Pengkajian
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'diagnosa_keperawatan' ? 'active' : '' ?>"
        href="index.php?page=gerontik&tab=diagnosa_keperawatan">
        Diagnosa Keperawatan
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'rencana' ? 'active' : '' ?>"
        href="index.php?page=gerontik&tab=rencana">
        Rencana Keperawatan
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'implementasi_keperawatan' ? 'active' : '' ?>"
       href="index.php?page=gerontik&tab=implementasi_keperawatan">
        Implementasi Keperawatan
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'evaluasi_keperawatan' ? 'active' : '' ?>"
        href="index.php?page=gerontik&tab=evaluasi_keperawatan">
        Evaluasi keperawatan
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'sap' ? 'active' : '' ?>"
        href="index.php?page=gerontik&tab=sap">
        Format SAP
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
        
    
<div class="container mt-4">
    <div class="card shadow-sm border-0">
        <div class="card-body"></div>
        <h4 class="card-title"><strong>FORMAT SATUAN ACARA PENYULUHAN (SAP)</strong></h4>
          
        <!-- Input untuk informasi SAP -->
        <table border="1">
          <tr>
    <td>Topik Penyuluhan</td>
    <td>
        <div class="d-flex justify-content-start align-items-center">
            <input type="text" name="topik_penyuluhan" style="width: 500px; margin-right: 10px;">
            <textarea class="form-control mt-2" rows="2" placeholder="Ketikkan revisi jika tidak di ACC" readonly style="width: 250px; margin-right: 10px;"></textarea>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" onchange="document.getElementById('commentusiaistri').style.display = this.checked ? 'none' : 'block'">
            </div>
        </div>
    </td>
</tr>
            
            <tr>
                <td>Waktu</td>
                
                <td><div class="d-flex justify-content-start align-items-center">
            <input type="text" name="topik_penyuluhan" style="width: 500px; margin-right: 10px;">
            <textarea class="form-control mt-2" rows="2" placeholder="Ketikkan revisi jika tidak di ACC" readonly style="width: 250px; margin-right: 10px;"></textarea>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" onchange="document.getElementById('commentusiaistri').style.display = this.checked ? 'none' : 'block'">
            </div></td>
            </tr>
            <tr>
                <td>Sasaran</td>
                <td><div class="d-flex justify-content-start align-items-center">
            <input type="text" name="topik_penyuluhan" style="width: 500px; margin-right: 10px;">
            <textarea class="form-control mt-2" rows="2" placeholder="Ketikkan revisi jika tidak di ACC" readonly style="width: 250px; margin-right: 10px;"></textarea>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" onchange="document.getElementById('commentusiaistri').style.display = this.checked ? 'none' : 'block'">
            </div></td>
            </tr>
            <tr>
                <td>Lokasi</td>
                <td><div class="d-flex justify-content-start align-items-center">
            <input type="text" name="topik_penyuluhan" style="width: 500px; margin-right: 10px;">
            <textarea class="form-control mt-2" rows="2" placeholder="Ketikkan revisi jika tidak di ACC" readonly style="width: 250px; margin-right: 10px;"></textarea>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" onchange="document.getElementById('commentusiaistri').style.display = this.checked ? 'none' : 'block'">
            </div></td>
            </tr>
            <tr>
                <td>Tanggal Pelaksanaan</td>
                <td><div class="d-flex justify-content-start align-items-center">
            <input type="text" name="topik_penyuluhan" style="width: 500px; margin-right: 10px;">
            <textarea class="form-control mt-2" rows="2" placeholder="Ketikkan revisi jika tidak di ACC" readonly style="width: 250px; margin-right: 10px;"></textarea>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" onchange="document.getElementById('commentusiaistri').style.display = this.checked ? 'none' : 'block'">
            </div></td>
            </tr>
            <tr>
                <td>Tujuan Umum</td>
                <td><div class="d-flex justify-content-start align-items-center">
            <input type="text" name="topik_penyuluhan" style="width: 500px; margin-right: 10px;">
            <textarea class="form-control mt-2" rows="2" placeholder="Ketikkan revisi jika tidak di ACC" readonly style="width: 250px; margin-right: 10px;"></textarea>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" onchange="document.getElementById('commentusiaistri').style.display = this.checked ? 'none' : 'block'">
            </div></td>
            </tr>
            <tr>
                <td>Tujuan Khusus</td>
                <td><div class="d-flex justify-content-start align-items-center">
            <input type="text" name="topik_penyuluhan" style="width: 500px; margin-right: 10px;">
            <textarea class="form-control mt-2" rows="2" placeholder="Ketikkan revisi jika tidak di ACC" readonly style="width: 250px; margin-right: 10px;"></textarea>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" onchange="document.getElementById('commentusiaistri').style.display = this.checked ? 'none' : 'block'">
            </div></td>
            </tr>
            <tr>
                <td>Metode</td>
                <td><div class="d-flex justify-content-start align-items-center">
            <input type="text" name="topik_penyuluhan" style="width: 500px; margin-right: 10px;">
            <textarea class="form-control mt-2" rows="2" placeholder="Ketikkan revisi jika tidak di ACC" readonly style="width: 250px; margin-right: 10px;"></textarea>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" onchange="document.getElementById('commentusiaistri').style.display = this.checked ? 'none' : 'block'">
            </div></td>
            </tr>
            <tr>
                <td>Media</td>
                <td><div class="d-flex justify-content-start align-items-center">
            <input type="text" name="topik_penyuluhan" style="width: 500px; margin-right: 10px;">
            <textarea class="form-control mt-2" rows="2" placeholder="Ketikkan revisi jika tidak di ACC" readonly style="width: 250px; margin-right: 10px;"></textarea>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" onchange="document.getElementById('commentusiaistri').style.display = this.checked ? 'none' : 'block'">
            </div></td>
            </tr>
            <tr>
                <td>Kegiatan Belajar</td>
               <td><div class="d-flex justify-content-start align-items-center">
            <input type="text" name="topik_penyuluhan" style="width: 500px; margin-right: 10px;">
            <textarea class="form-control mt-2" rows="2" placeholder="Ketikkan revisi jika tidak di ACC" readonly style="width: 250px; margin-right: 10px;"></textarea>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" onchange="document.getElementById('commentusiaistri').style.display = this.checked ? 'none' : 'block'">
            </div></td>
            </tr>
        </table>

        <!-- Tabel Kegiatan Penyuluhan -->
        <h3>Kegiatan Penyuluhan</h3>
        <table border="1" cellpadding="10">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kegiatan Penyuluhan</th>
                    <th>Kegiatan Penyuluh</th>
                    <th>Kegiatan Peserta</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>Membuka Penyuluhan (5 – 10%)</td>
                    <td><input type="text" name="kegiatan_penyuluh_1" style="width: 200px;"></td>
                    <td><input type="text" name="kegiatan_peserta_1" style="width: 200px;"></td>
                    <td><div class="d-flex justify-content-start align-items-center">
            <textarea class="form-control mt-2" rows="2" placeholder="Ketikkan revisi jika tidak di ACC" readonly style="width: 250px; margin-right: 10px;"></textarea>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" onchange="document.getElementById('commentusiaistri').style.display = this.checked ? 'none' : 'block'">
            </div></td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Penyajian Materi (80 – 90%)</td>
                    <td><input type="text" name="kegiatan_penyuluh_2" style="width: 200px;"></td>
                    <td><input type="text" name="kegiatan_peserta_2" style="width: 200px;"></td>
                    <td><div class="d-flex justify-content-start align-items-center">
            <textarea class="form-control mt-2" rows="2" placeholder="Ketikkan revisi jika tidak di ACC" readonly style="width: 250px; margin-right: 10px;"></textarea>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" onchange="document.getElementById('commentusiaistri').style.display = this.checked ? 'none' : 'block'">
            </div></td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>Menutup Penyuluhan (5 – 10%)</td>
                    <td><input type="text" name="kegiatan_penyuluh_3" style="width: 200px;"></td>
                    <td><input type="text" name="kegiatan_peserta_3" style="width: 200px;"></td>
                    <td><div class="d-flex justify-content-start align-items-center">
            <textarea class="form-control mt-2" rows="2" placeholder="Ketikkan revisi jika tidak di ACC" readonly style="width: 250px; margin-right: 10px;"></textarea>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" onchange="document.getElementById('commentusiaistri').style.display = this.checked ? 'none' : 'block'">
            </div></td>
                </tr>
            </tbody>
        </table>

        <!-- Evaluasi -->
        <h3>Kriteria Evaluasi</h3>
        <ul>
            <li><input type="checkbox" name="evaluasi_struktur"> Evaluasi Struktur</li>
            <li><input type="checkbox" name="evaluasi_proses"> Evaluasi Proses</li>
            <li><input type="checkbox" name="evaluasi_hasil"> Evaluasi Hasil</li>
        </ul>

        <!-- Uraian Tugas -->
        <h3>Uraian Tugas</h3>
        <textarea name="uraian_tugas" rows="4" style="width: 100%;"></textarea>
         <tr>
        <td colspan="2">
    <div class="d-flex justify-content-between align-items-center">
        <textarea class="form-control mt-2" rows="2" placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
        <div class="form-check ms-2">
            <input class="form-check-input" type="checkbox" onchange="document.getElementById('commentusiaistri').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</td>
    </tr>

        <!-- Setting -->
        <h3>Setting</h3>
        <textarea name="setting" rows="4" style="width: 100%;"></textarea>
        <tr>
        <td colspan="2">
    <div class="d-flex justify-content-between align-items-center">
        <textarea class="form-control mt-2" rows="2" placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
        <div class="form-check ms-2">
            <input class="form-check-input" type="checkbox" onchange="document.getElementById('commentusiaistri').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</td>
    </tr>
    </div>
</div>
<div class="container mt-4">
    <div class="card shadow-sm border-0">
        <div class="card-body"></div>
        <h4 class="card-title"><strong>FORMAT MATERI PENYULUHAN</strong></h4>

        <table border="1" cellpadding="10" cellspacing="0" style="width:100%; margin-top: 20px;">
            <tr>
                <td>Topik</td>
                <td><textarea name="topik" rows="4" style="width: 100%;"></textarea></td>
                <tr>
        <td colspan="2">
    <div class="d-flex justify-content-between align-items-center">
        <textarea class="form-control mt-2" rows="2" placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
        <div class="form-check ms-2">
            <input class="form-check-input" type="checkbox" onchange="document.getElementById('commentusiaistri').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</td>
    </tr>
            </tr>
            <tr>
                <td>Definisi</td>
                <td><textarea name="definisi" rows="4" style="width: 100%;"></textarea></td>
                <tr>
        <td colspan="2">
    <div class="d-flex justify-content-between align-items-center">
        <textarea class="form-control mt-2" rows="2" placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
        <div class="form-check ms-2">
            <input class="form-check-input" type="checkbox" onchange="document.getElementById('commentusiaistri').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</td>
    </tr>
            </tr>
            <tr>
                <td>Etiologi</td>
                <td><textarea name="etiologi" rows="4" style="width: 100%;"></textarea></td>
                <tr>
        <td colspan="2">
    <div class="d-flex justify-content-between align-items-center">
        <textarea class="form-control mt-2" rows="2" placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
        <div class="form-check ms-2">
            <input class="form-check-input" type="checkbox" onchange="document.getElementById('commentusiaistri').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</td>
    </tr>
            </tr>
            <tr>
                <td>Manifestasi Klinis</td>
                <td><textarea name="manifestasi_klinis" rows="4" style="width: 100%;"></textarea></td>
                <tr>
        <td colspan="2">
    <div class="d-flex justify-content-between align-items-center">
        <textarea class="form-control mt-2" rows="2" placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
        <div class="form-check ms-2">
            <input class="form-check-input" type="checkbox" onchange="document.getElementById('commentusiaistri').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</td>
    </tr>
            </tr>
            <tr>
                <td>Penatalaksanaan</td>
                <td><textarea name="penatalaksanaan" rows="4" style="width: 100%;"></textarea></td>
                <tr>
        <td colspan="2">
    <div class="d-flex justify-content-between align-items-center">
        <textarea class="form-control mt-2" rows="2" placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
        <div class="form-check ms-2">
            <input class="form-check-input" type="checkbox" onchange="document.getElementById('commentusiaistri').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</td>
    </tr>
            </tr>
            <tr>
                <td>Pencegahan</td>
                <td><textarea name="pencegahan" rows="4" style="width: 100%;"></textarea></td>
                <tr>
        <td colspan="2">
    <div class="d-flex justify-content-between align-items-center">
        <textarea class="form-control mt-2" rows="2" placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
        <div class="form-check ms-2">
            <input class="form-check-input" type="checkbox" onchange="document.getElementById('commentusiaistri').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</td>
    </tr>
            </tr>
            <tr>
                <td>Sumber Rujukan</td>
                <td><textarea name="sumber_rujukan" rows="4" style="width: 100%;"></textarea></td>
                <tr>
        <td colspan="2">
    <div class="d-flex justify-content-between align-items-center">
        <textarea class="form-control mt-2" rows="2" placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
        <div class="form-check ms-2">
            <input class="form-check-input" type="checkbox" onchange="document.getElementById('commentusiaistri').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</td>
    </tr>
            </tr>
        </table>
    </div>
</div><div class="container mt-4">
    <div class="card shadow-sm border-0">
        <div class="card-body"></div>
        <h4 class="card-title"><strong>FORMAT LAPORAN EVALUASI HASIL PENYULUHAN</strong></h4>
    <p><strong>Topik:</strong> <input type="text" name="topik" style="width: 100%; margin-bottom: 10px;"></p>
    <tr>
        <td colspan="2">
    <div class="d-flex justify-content-between align-items-center">
        <textarea class="form-control mt-2" rows="2" placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
        <div class="form-check ms-2">
            <input class="form-check-input" type="checkbox" onchange="document.getElementById('commentusiaistri').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</td>
    </tr>

<p><strong>Tanggal Pelaksanaan:</strong> <input type="text" name="tgl_pelaksanaan" style="width: 100%; margin-bottom: 10px;"></p>
<tr>
        <td colspan="2">
    <div class="d-flex justify-content-between align-items-center">
        <textarea class="form-control mt-2" rows="2" placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
        <div class="form-check ms-2">
            <input class="form-check-input" type="checkbox" onchange="document.getElementById('commentusiaistri').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</td>
    </tr>

<p><strong>Lokasi:</strong> <input type="text" name="lokasi" style="width: 100%; margin-bottom: 10px;"></p>
<tr>
        <td colspan="2">
    <div class="d-flex justify-content-between align-items-center">
        <textarea class="form-control mt-2" rows="2" placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
        <div class="form-check ms-2">
            <input class="form-check-input" type="checkbox" onchange="document.getElementById('commentusiaistri').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</td>
    </tr>
        <p><strong>Evaluasi:</strong></p>
        <textarea name="evaluasi" rows="4" style="width: 100%;"></textarea>
        <tr>
        <td colspan="2">
    <div class="d-flex justify-content-between align-items-center">
        <textarea class="form-control mt-2" rows="2" placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
        <div class="form-check ms-2">
            <input class="form-check-input" type="checkbox" onchange="document.getElementById('commentusiaistri').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</td>
    </tr>

        <p><strong>Evaluasi Struktur:</strong></p>
        <textarea name="evaluasi_struktur" rows="4" style="width: 100%;"></textarea>
        <tr>
        <td colspan="2">
    <div class="d-flex justify-content-between align-items-center">
        <textarea class="form-control mt-2" rows="2" placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
        <div class="form-check ms-2">
            <input class="form-check-input" type="checkbox" onchange="document.getElementById('commentusiaistri').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</td>
    </tr>

        <p><strong>Evaluasi Proses:</strong></p>
        <textarea name="evaluasi_proses" rows="4" style="width: 100%;"></textarea>
        <tr>
        <td colspan="2">
    <div class="d-flex justify-content-between align-items-center">
        <textarea class="form-control mt-2" rows="2" placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
        <div class="form-check ms-2">
            <input class="form-check-input" type="checkbox" onchange="document.getElementById('commentusiaistri').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</td>
    </tr>

        <p><strong>Evaluasi Hasil:</strong></p>
        <textarea name="evaluasi_hasil" rows="4" style="width: 100%;"></textarea>
        <tr>
        <td colspan="2">
    <div class="d-flex justify-content-between align-items-center">
        <textarea class="form-control mt-2" rows="2" placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
        <div class="form-check ms-2">
            <input class="form-check-input" type="checkbox" onchange="document.getElementById('commentusiaistri').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</td>
    </tr>
    </div>
</div>
<div class="container mt-4">
    <!-- Card wrapper untuk menampung semua konten form -->
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <!-- Judul bagian Pre Planning -->
            <h4 class="card-title"><strong>Pre Planning</strong></h4>

            <!-- Nama Kegiatan -->
            <p><strong>Nama Kegiatan:</strong>
                <input type="text" name="nama_kegiatan" style="width: 100%; margin-bottom: 10px;">
                <tr>
        <td colspan="2">
    <div class="d-flex justify-content-between align-items-center">
        <textarea class="form-control mt-2" rows="2" placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
        <div class="form-check ms-2">
            <input class="form-check-input" type="checkbox" onchange="document.getElementById('commentusiaistri').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</td>
    </tr>
            </p>

            <!-- Tempat dan Lokasi Kegiatan -->
            <p><strong>Tempat dan Lokasi Kegiatan:</strong>
                <input type="text" name="tempat_lokasi_kegiatan" style="width: 100%; margin-bottom: 10px;">
            </p>

            <!-- Latar Belakang -->
            <h4>I. Latar Belakang</h4>
            <textarea name="latar_belakang" rows="4" style="width: 100%; margin-bottom: 10px;"></textarea>
            <tr>
        <td colspan="2">
    <div class="d-flex justify-content-between align-items-center">
        <textarea class="form-control mt-2" rows="2" placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
        <div class="form-check ms-2">
            <input class="form-check-input" type="checkbox" onchange="document.getElementById('commentusiaistri').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</td>
    </tr>

            <!-- Tujuan Umum -->
            <h4>II. Tujuan Umum</h4>
            <textarea name="tujuan_umum" rows="4" style="width: 100%; margin-bottom: 10px;"></textarea>
            <tr>
        <td colspan="2">
    <div class="d-flex justify-content-between align-items-center">
        <textarea class="form-control mt-2" rows="2" placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
        <div class="form-check ms-2">
            <input class="form-check-input" type="checkbox" onchange="document.getElementById('commentusiaistri').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</td>
    </tr>

            <!-- Tujuan Khusus -->
            <h4>III. Tujuan Khusus</h4>
            <textarea name="tujuan_khusus" rows="4" style="width: 100%; margin-bottom: 10px;"></textarea>
            <tr>
        <td colspan="2">
    <div class="d-flex justify-content-between align-items-center">
        <textarea class="form-control mt-2" rows="2" placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
        <div class="form-check ms-2">
            <input class="form-check-input" type="checkbox" onchange="document.getElementById('commentusiaistri').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</td>
    </tr>

            <!-- Sasaran dan Target -->
            <h4>IV. Sasaran dan Target</h4>
            <p><strong>Sasaran:</strong>
                <textarea name="sasaran" rows="3" style="width: 100%; margin-bottom: 10px;"></textarea>
                <tr>
        <td colspan="2">
    <div class="d-flex justify-content-between align-items-center">
        <textarea class="form-control mt-2" rows="2" placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
        <div class="form-check ms-2">
            <input class="form-check-input" type="checkbox" onchange="document.getElementById('commentusiaistri').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</td>
    </tr>
            </p>
            <p><strong>Target:</strong>
                <textarea name="target" rows="3" style="width: 100%; margin-bottom: 10px;"></textarea>
                <tr>
        <td colspan="2">
    <div class="d-flex justify-content-between align-items-center">
        <textarea class="form-control mt-2" rows="2" placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
        <div class="form-check ms-2">
            <input class="form-check-input" type="checkbox" onchange="document.getElementById('commentusiaistri').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</td>
    </tr>
            </p>

            <!-- Strategi Pelaksanaan -->
            <h4>V. Strategi Pelaksanaan</h4>
            <p><strong>a. Metode:</strong>
                <textarea name="metode" rows="4" style="width: 100%; margin-bottom: 10px;"></textarea>
                <tr>
        <td colspan="2">
    <div class="d-flex justify-content-between align-items-center">
        <textarea class="form-control mt-2" rows="2" placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
        <div class="form-check ms-2">
            <input class="form-check-input" type="checkbox" onchange="document.getElementById('commentusiaistri').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</td>
    </tr>
            </p>

            <!-- Kriteria Evaluasi -->
            <h4>b. Kriteria Evaluasi</h4>
            <p><strong>Evaluasi Struktur:</strong>
                <textarea name="evaluasi_struktur" rows="3" style="width: 100%; margin-bottom: 10px;"></textarea>
                <tr>
        <td colspan="2">
    <div class="d-flex justify-content-between align-items-center">
        <textarea class="form-control mt-2" rows="2" placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
        <div class="form-check ms-2">
            <input class="form-check-input" type="checkbox" onchange="document.getElementById('commentusiaistri').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</td>
    </tr>
            </p>
            <p><strong>Evaluasi Proses:</strong>
                <textarea name="evaluasi_proses" rows="3" style="width: 100%; margin-bottom: 10px;"></textarea>
                <tr>
        <td colspan="2">
    <div class="d-flex justify-content-between align-items-center">
        <textarea class="form-control mt-2" rows="2" placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
        <div class="form-check ms-2">
            <input class="form-check-input" type="checkbox" onchange="document.getElementById('commentusiaistri').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</td>
    </tr>
            </p>
            <p><strong>Evaluasi Hasil:</strong>
                <textarea name="evaluasi_hasil" rows="3" style="width: 100%; margin-bottom: 10px;"></textarea>
                <tr>
        <td colspan="2">
    <div class="d-flex justify-content-between align-items-center">
        <textarea class="form-control mt-2" rows="2" placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
        <div class="form-check ms-2">
            <input class="form-check-input" type="checkbox" onchange="document.getElementById('commentusiaistri').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</td>
    </tr>
            </p>

            <!-- Waktu dan Tempat -->
            <p><strong>c. Waktu dan Tempat:</strong>
                <textarea name="waktu_tempat" rows="3" style="width: 100%; margin-bottom: 10px;"></textarea>
                <tr>
        <td colspan="2">
    <div class="d-flex justify-content-between align-items-center">
        <textarea class="form-control mt-2" rows="2" placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
        <div class="form-check ms-2">
            <input class="form-check-input" type="checkbox" onchange="document.getElementById('commentusiaistri').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</td>
    </tr>
            </p>

            <!-- Susunan Acara -->
            <p><strong>d. Susunan Acara:</strong>
                <textarea name="susunan_acara" rows="3" style="width: 100%; margin-bottom: 10px;"></textarea>
                <tr>
        <td colspan="2">
    <div class="d-flex justify-content-between align-items-center">
        <textarea class="form-control mt-2" rows="2" placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
        <div class="form-check ms-2">
            <input class="form-check-input" type="checkbox" onchange="document.getElementById('commentusiaistri').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</td>
    </tr>
            </p>

            <!-- Uraian Tugas -->
            <p><strong>e. Uraian Tugas:</strong>
                <textarea name="uraian_tugas" rows="4" style="width: 100%; margin-bottom: 10px;"></textarea>
                <tr>
        <td colspan="2">
    <div class="d-flex justify-content-between align-items-center">
        <textarea class="form-control mt-2" rows="2" placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
        <div class="form-check ms-2">
            <input class="form-check-input" type="checkbox" onchange="document.getElementById('commentusiaistri').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</td>
    </tr>
            </p>

            <!-- Setting -->
            <p><strong>f. Setting:</strong>
                <textarea name="setting" rows="4" style="width: 100%; margin-bottom: 10px;"></textarea>
                <tr>
        <td colspan="2">
    <div class="d-flex justify-content-between align-items-center">
        <textarea class="form-control mt-2" rows="2" placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
        <div class="form-check ms-2">
            <input class="form-check-input" type="checkbox" onchange="document.getElementById('commentusiaistri').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</td>
    </tr>
            </p>
        </div>
    </div>
</div>
  
<div class="container mt-4">
    <!-- Card wrapper untuk menampung seluruh konten form -->
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <!-- Judul untuk Form Evaluasi Kegiatan -->
            <h4 class="card-title"><strong>FORMAT EVALUASI KEGIATAN</strong></h4>

            <!-- Nama Kegiatan -->
            <p><strong>Nama Kegiatan:</strong>
                <input type="text" name="nama_kegiatan" style="width: 100%; margin-bottom: 10px;">
                <tr>
        <td colspan="2">
    <div class="d-flex justify-content-between align-items-center">
        <textarea class="form-control mt-2" rows="2" placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
        <div class="form-check ms-2">
            <input class="form-check-input" type="checkbox" onchange="document.getElementById('commentusiaistri').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</td>
    </tr>
            </p>

            <!-- Hari / Tanggal -->
            <p><strong>Hari / Tanggal:</strong>
                <input type="text" name="hari_tanggal" style="width: 100%; margin-bottom: 10px;">
                <tr>
        <td colspan="2">
    <div class="d-flex justify-content-between align-items-center">
        <textarea class="form-control mt-2" rows="2" placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
        <div class="form-check ms-2">
            <input class="form-check-input" type="checkbox" onchange="document.getElementById('commentusiaistri').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</td>
    </tr>
            </p>

            <!-- Pukul -->
            <p><strong>Pukul:</strong>
                <input type="text" name="pukul" style="width: 100%; margin-bottom: 10px;">
                <tr>
        <td colspan="2">
    <div class="d-flex justify-content-between align-items-center">
        <textarea class="form-control mt-2" rows="2" placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
        <div class="form-check ms-2">
            <input class="form-check-input" type="checkbox" onchange="document.getElementById('commentusiaistri').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</td>
    </tr>
            </p>

            <!-- Tempat -->
            <p><strong>Tempat:</strong>
                <input type="text" name="tempat" style="width: 100%; margin-bottom: 10px;">
                <tr>
        <td colspan="2">
    <div class="d-flex justify-content-between align-items-center">
        <textarea class="form-control mt-2" rows="2" placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
        <div class="form-check ms-2">
            <input class="form-check-input" type="checkbox" onchange="document.getElementById('commentusiaistri').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</td>
    </tr>
            </p>

            <!-- Bagian Persiapan -->
            <h4>A. Persiapan</h4>
            <textarea name="persiapan" rows="4" style="width: 100%; margin-bottom: 10px;"></textarea>
            <tr>
        <td colspan="2">
    <div class="d-flex justify-content-between align-items-center">
        <textarea class="form-control mt-2" rows="2" placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
        <div class="form-check ms-2">
            <input class="form-check-input" type="checkbox" onchange="document.getElementById('commentusiaistri').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</td>
    </tr>

            <!-- Bagian Pelaksanaan -->
            <h4>B. Pelaksanaan</h4>
            <textarea name="pelaksanaan" rows="4" style="width: 100%; margin-bottom: 10px;"></textarea>
            <tr>
        <td colspan="2">
    <div class="d-flex justify-content-between align-items-center">
        <textarea class="form-control mt-2" rows="2" placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
        <div class="form-check ms-2">
            <input class="form-check-input" type="checkbox" onchange="document.getElementById('commentusiaistri').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</td>
    </tr>

            <!-- Bagian Evaluasi -->
            <h4>C. Evaluasi</h4>

            <!-- Evaluasi Struktur -->
            <p><strong>a. Evaluasi Struktur:</strong></p>
            <textarea name="evaluasi_struktur" rows="4" style="width: 100%; margin-bottom: 10px;"></textarea>
            <tr>
        <td colspan="2">
    <div class="d-flex justify-content-between align-items-center">
        <textarea class="form-control mt-2" rows="2" placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
        <div class="form-check ms-2">
            <input class="form-check-input" type="checkbox" onchange="document.getElementById('commentusiaistri').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</td>
    </tr>

            <!-- Evaluasi Proses -->
            <p><strong>b. Evaluasi Proses:</strong></p>
            <textarea name="evaluasi_proses" rows="4" style="width: 100%; margin-bottom: 10px;"></textarea>
            <tr>
        <td colspan="2">
    <div class="d-flex justify-content-between align-items-center">
        <textarea class="form-control mt-2" rows="2" placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
        <div class="form-check ms-2">
            <input class="form-check-input" type="checkbox" onchange="document.getElementById('commentusiaistri').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</td>
    </tr>

            <!-- Evaluasi Hasil -->
            <p><strong>c. Evaluasi Hasil:</strong></p>
            <textarea name="evaluasi_hasil" rows="4" style="width: 100%; margin-bottom: 10px;"></textarea>
            <tr>
        <td colspan="2">
    <div class="d-flex justify-content-between align-items-center">
        <textarea class="form-control mt-2" rows="2" placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
        <div class="form-check ms-2">
            <input class="form-check-input" type="checkbox" onchange="document.getElementById('commentusiaistri').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</td>
    </tr>
        </div>
    </div>
</div>
<div class="container mt-4">
    <!-- Card wrapper untuk form Lembar Konsultasi -->
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <!-- Judul Lembar Konsultasi -->
            <h4 class="card-title"><strong>Lembar Konsultasi Laporan Kasus Gerontik Binaan</strong></h4>

            <!-- Nama Mahasiswa -->
            <p><strong>Nama Mahasiswa:</strong>
                <input type="text" name="nama_mahasiswa" style="width: 300px; margin-bottom: 10px;">
            </p>

            <!-- NIM -->
            <p><strong>NIM:</strong>
                <input type="text" name="nim" style="width: 300px; margin-bottom: 10px;">
            </p>

            <!-- Kelompok -->
            <p><strong>Kelompok:</strong> 
                RT <input type="text" name="rt" style="width: 50px;">
                RW <input type="text" name="rw" style="width: 50px;">
                Kel <input type="text" name="kel" style="width: 50px;">
            </p>

            <!-- Pembimbing -->
            <p><strong>Pembimbing:</strong>
                <input type="text" name="pembimbing" style="width: 300px; margin-bottom: 10px;">
            </p>

            <!-- Daftar Konsultasi -->
            <h3>Daftar Konsultasi</h3>

            <!-- Tabel Konsultasi -->
            <table border="1" cellpadding="10" style="width: 100%;">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Hari / Tanggal</th>
                        <th>Kritik & Saran Pembimbing</th>
                        <th>Paraf Pembimbing</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Baris Konsultasi 1 -->
                    <tr>
                        <td>1</td>
                        <td><input type="date" name="hari_tanggal_1" style="width: 150px; margin-bottom: 10px;"></td>
                        <td><textarea name="kritik_saran_1" rows="3" style="width: 200px; margin-bottom: 10px;"></textarea></td>
                        <td><input type="text" name="paraf_pembimbing_1" style="width: 100px; margin-bottom: 10px;"></td>
                    </tr>
                    <!-- Baris Konsultasi 2 -->
                    <tr>
                        <td>2</td>
                        <td><input type="date" name="hari_tanggal_2" style="width: 150px; margin-bottom: 10px;"></td>
                        <td><textarea name="kritik_saran_2" rows="3" style="width: 200px; margin-bottom: 10px;"></textarea></td>
                        <td><input type="text" name="paraf_pembimbing_2" style="width: 100px; margin-bottom: 10px;"></td>
                    </tr>
                    <!-- Baris Konsultasi 3 -->
                    <tr>
                        <td>3</td>
                        <td><input type="date" name="hari_tanggal_3" style="width: 150px; margin-bottom: 10px;"></td>
                        <td><textarea name="kritik_saran_3" rows="3" style="width: 200px; margin-bottom: 10px;"></textarea></td>
                        <td><input type="text" name="paraf_pembimbing_3" style="width: 100px; margin-bottom: 10px;"></td>
                    </tr>
                    <!-- Baris-baris lainnya bisa ditambahkan jika diperlukan -->
                </tbody>
            </table>

               <?php include "tab_navigasi.php"; ?>
        </div>
    </div>
</div>
             
                 

                    
</section>
</main>