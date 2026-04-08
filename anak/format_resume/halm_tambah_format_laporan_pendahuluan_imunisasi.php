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

    <div class="pagetitle">
        <h1><strong>Format Resume Keperawatan Anak di Puskesmas</strong></h1>
    </div><!-- End Page Title -->
    <br>
<ul class="nav nav-tabs custom-tabs">
<li class="nav-item">
    <a class="nav-link <?= ($_GET['tab'] ?? 'resume_keperawatan') == 'resume_keperawatan' ? 'active' : '' ?>"
    href="index.php?page=anak/format_resume&tab=resume_keperawatan">
Format Resume Keperawatan Poli Anak    </a>
</li>
<li class="nav-item">
    <a class="nav-link <?= ($_GET['tab'] ?? 'lp_imunisasi') == 'lp_imunisasi' ? 'active' : '' ?>"
    href="index.php?page=anak/format_resume&tab=lp_imunisasi">
Format Laporan Pendahuluan Imunisasi   </a>
</li>
<li class="nav-item">
    <a class="nav-link <?= ($_GET['tab'] ?? 'poli_imunisasi') == 'poli_imunisasi' ? 'active' : '' ?>"
    href="index.php?page=anak/format_resume&tab=poli_imunisasi">
Format Laporan  Poli Imunisasi   </a>
</li>
</ul>



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

                    <h5 class="card-title"><strong>FORMAT LAPORAN PENDAHULUAN IMUNISASI</strong></h5>
                     <div class="row mb-2">
                        <label class="col-sm-6 col-form-label text-primary">
                            <strong>Konsep Dasar Imunisasi (secara keseluruhan)</strong>
                    </div>

            <!-- Bagian Pengertian -->
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label"><strong>1.	Pengertian Imnuniasi</strong></label>
                <div class="col-sm-9">
                            <textarea name="pengertian_imunisasi" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                      <!-- comment -->
                            <textarea class="form-control mt-2" id="commentpengertian_imunisasi" rows="2" placeholder="Jika ada revisi atau saran dari Ibu/Bapak Dosen, silakan diketik di sini. Terima kasih." style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" onchange="document.getElementById('commentpengertian_imunisasi'). style.display= this.checked ? 'none' : 'block'">
                            </div>
                         </div>
                    </div>
<!-- 2 -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>2. Manfaat / Tujuan Imunisasi</strong></label>
    <div class="col-sm-9">
        <textarea name="manfaat_imunisasi" class="form-control" rows="3" style="display:block; overflow:hidden; resize:none;"
        oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

        <textarea class="form-control mt-2" id="commentmanfaat_imunisasi" rows="2"
        placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1 d-flex align-items-start">
        <div class="form-check">
            <input class="form-check-input" type="checkbox"
            onchange="document.getElementById('commentmanfaat_imunisasi').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</div>

<!-- 3 -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>3. Jenis-jenis Kekebalan Tubuh</strong></label>
    <div class="col-sm-9">
        <textarea name="jenis_kekebalan_tubuh" class="form-control" rows="3" style="display:block; overflow:hidden; resize:none;"
        oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

        <textarea class="form-control mt-2" id="commentjenis_kekebalan" rows="2"
        placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1 d-flex align-items-start">
        <div class="form-check">
            <input class="form-check-input" type="checkbox"
            onchange="document.getElementById('commentjenis_kekebalan').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</div>

<!-- 4 -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>4. Jenis-jenis Imunisasi Dasar</strong></label>
    <div class="col-sm-9">
        <small style="color:red;">
            Penjelasan meliputi nama imunisasi, umur, dosis, cara pemberian, frekuensi, efek samping, penyakit yang dicegah
        </small>

        <textarea name="imunisasi_dasar" class="form-control mt-2" rows="3" style="display:block; overflow:hidden; resize:none;"
        oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

        <textarea class="form-control mt-2" id="commentimunisasi_dasar" rows="2"
        placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1 d-flex align-items-start">
        <div class="form-check">
            <input class="form-check-input" type="checkbox"
            onchange="document.getElementById('commentimunisasi_dasar').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</div>

<!-- 5 -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>5. Jenis-jenis Imunisasi Lanjutan</strong></label>
    <div class="col-sm-9">
        <small style="color:red;">
            Penjelasan meliputi nama imunisasi, umur, dosis, cara pemberian, frekuensi, efek samping
        </small>

        <textarea name="imunisasi_lanjutan" class="form-control mt-2" rows="3" style="display:block; overflow:hidden; resize:none;"
        oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

        <textarea class="form-control mt-2" id="commentimunisasi_lanjutan" rows="2"
        placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" readonly></textarea>
    </div>
    <div class="col-sm-1 d-flex align-items-start">
        <div class="form-check">
            <input class="form-check-input" type="checkbox"
            onchange="document.getElementById('commentimunisasi_lanjutan').style.display = this.checked ? 'none' : 'block'">
        </div>
    </div>
</div>
</form>
</div>
</form>
</div>
</div>
<?php include "tab_navigasi.php"; ?>
</main>


    


