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

                <!-- Bagian Tempat Dinas -->
                <div class="row mb-3">
                    <label for="tempatdinas" class="col-sm-2 col-form-label"><strong>Tempat Dinas</strong></label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="tempatdinas" required>
                        <div class="invalid-feedback">
                            Harap isi Tempat Dinas.
                        </div>
                    </div>
                </div>
            </div>
     </div>

    <div class="pagetitle">
        <h1><strong>Asuhan Keperawatan Keluarga</strong></h1>
    </div><!-- End Page Title -->
    <br>

    <ul class="nav nav-tabs custom-tabs">

    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'pengkajian' ? 'active' : '' ?>"
        href="index.php?page=keluarga&tab=pengkajian">
        Pengkajian
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'diagnosa_keperawatan' ? 'active' : '' ?>"
        href="index.php?page=keluarga&tab=diagnosa_keperawatan">
        Diagnosa Keperawatan
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'rencana_keperawatan' ? 'active' : '' ?>"
        href="index.php?page=keluarga&tab=rencana_keperawatan">
        Rencana Keperawatan
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'implementasi_keperawatan' ? 'active' : '' ?>"
       href="index.php?page=keluarga&tab=implementasi_keperawatan">
        Implementasi Keperawatan
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'evaluasi_keperawatan' ? 'active' : '' ?>"
        href="index.php?page=keluarga&tab=evaluasi_keperawatan">
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

                <h5 class="card-title"><strong>A. PENGKAJIAN</strong></h5>

                <!-- General Form Elements -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

                    <h5 class="card-title mb-1"><strong>I. Data Umum</strong></h5>    

                    <!-- Bagian Nama -->

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Nama</strong></label>

                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="nama">

                            <!-- comment -->
                            <textarea class="form-control mt-2" name="commentnama" id="commentnama" rows="2" placeholder="Jika ada revisi atau saran dari Ibu/Bapak Dosen, silakan diketik di sini. Terima kasih." style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" onchange="document.getElementById('commentinisialpasien'). style.display= this.checked ? 'none' : 'block'">
                            </div>
                         </div>
                    </div>

                    <!-- Bagian Tempat/ Tgl Lahir -->

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Tempat/Tanggal Hari</strong></label>

                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="tempattgllahir">

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

                <!-- Bagian Pendidikan KK -->
                <div class="row mb-3">
                    <label for="pendidikankk" class="col-sm-2 col-form-label"><strong>Pendidikan KK</strong></label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="pendidikankk">

                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentpendidikankk" id="commentpendidikankk" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>

                <!-- Bagian Tipe Keluarga -->
                <div class="row mb-3">
                    <label for="tipekeluarga" class="col-sm-2 col-form-label"><strong>Tipe Keluarga</strong></label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="tipekeluarga">

                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commenttipekeluarga" id="commenttipekeluarga" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
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
                            <textarea class="form-control mt-2" name="commentsukubangsa" id="commentsukubangsa" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
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

                <!-- Bagian Status Sosial Ekomoni -->
                <div class="row mb-3">
                    <label for="statussosialekonomi" class="col-sm-2 col-form-label"><strong>Status Sosial Ekonomi</strong></label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="statussosialekonomi">

                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentstatussosialekonomi" id="commentstatussosialekonomi" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>

                <!-- Bagian Aktivitas Rekreasi -->
                <div class="row mb-3">
                    <label for="aktivitasrekreasi" class="col-sm-2 col-form-label"><strong>Aktivitas Rekreasi</strong></label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="aktivitasrekreasi">

                       <!-- comment -->
                            <textarea class="form-control mt-2" name="commentaktivitasrekreasi" id="commentaktivitasrekreasi" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>

                <!-- Bagian Komposisi Keluarga -->
                <div class="row mb-3">
                    <label for="komposisikeluarga" class="col-sm-2 col-form-label"><strong>Komposisi Keluarga</strong></label>
                    <div class="col-sm-9">

                        <!-- Link Google Drive -->
                         <div class="form-control d-flex justify-content-between align-items-center">
                            <span>Upload Foto Kartu Keluarga pada link Google Drive yang tersedia</span>
                            <a href="<?= $kartukeluarga ?>" target="_blank" class="btn btn-sm btn-primary">Upload</a>
                        </div>

                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentkomposisikeluarga" id="commentkomposisikeluarga" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>

                    <hr class="my-4">
    
            <!-- Tabel -->

                <!-- Bagian Nama -->
                <div class="row mb-3">
                    <label for="namainisial" class="col-sm-2 col-form-label"><strong>Nama (Inisial)</strong></label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="namainisial">

                       <!-- comment -->
                            <textarea class="form-control mt-2" name="commentnamainisial" id="commentnamainisial" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
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
             
                 <!-- Bagian Hubungan Dengan KK -->

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Hub. dengan KK</strong></label>

                        <div class="col-sm-9">
                           <textarea name="hubungandengankk" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" name="commenthubungandengankk" id="commenthubungandengankk" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
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

                    <!-- Status Gizi -->

                        <div class="row mb-3">

                        <label class="col-sm-2 col-form-label"><strong>Status Gizi</strong></label>
                            <div class="col-sm-9">
                                <div class="row">

                        <!-- E -->
                        <div class="col-md-4 d-flex align-items-center">
                            <label class="me-2"><strong>BB</strong></label>
                            <input type="text" class="form-control" name="bb">
                        </div>

                        <!-- M -->
                        <div class="col-md-4 d-flex align-items-center">
                            <label class="me-2"><strong>TB</strong></label>
                            <input type="text" class="form-control" name="tb">
                        </div>

                        <!-- V -->
                        <div class="col-md-4 d-flex align-items-center">
                            <label class="me-2"><strong>IMT</strong></label>
                            <input type="text" class="form-control" name="imt">
                        </div>
                    </div>

                         <!-- comment -->
                        <textarea class="form-control mt-2" name="commentbbtbtimt" id="commentbbtbimt" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>

                    </div>
                
                    <!-- Bagian Status Imunisasi -->
                    <div class="row mb-3">
                        <label for="statusimunisasi" class="col-sm-2 col-form-label"><strong>Status Imunisasi</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="statusimunisasi">

                            <!-- comment -->
                                <textarea class="form-control mt-2" name="commentstatusimunisasi" id="commentstatusimunisasi" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                            </div>

                            <div class="col-sm-1 d-flex align-items-start">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" disabled>
                                </div>
                            </div>
                        </div> 
                        
                <!-- Bagian Kondisi Kesehatan -->
                <div class="row mb-3">
                    <label for="kondisikesehatan" class="col-sm-2 col-form-label"><strong>Kondisi Kesehatan</strong></label>
                    <div class="col-sm-9">
                       <textarea name="kondisikesehatan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentkondisikesehatan" id="commentkondisikesehatan" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
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
                            <strong>TTV</strong>
                        </label>    
                    </div>

                    <!-- Tekanan Darah -->
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-2 col-form-label"><strong>TD</strong></label>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="text" class="form-control" name="tekanandarah">
                                <span class="input-group-text">mmHg</span>
                        </div>    
                    </div>
                                
                    <!-- Nadi -->
                    <label class="col-sm-2 col-form-label"><strong>N</strong></label>
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
                    <label class="col-sm-2 col-form-label"><strong>S</strong></label>
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

                <!-- comment -->
                <div class="row mb-3">

                    <div class="offset-sm-2 col-sm-9">
                        <textarea class="form-control mt-2" name="commenttandavital" id="commenttandavital" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize:none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
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
                    .table-keluarga {
                        table-layout: fixed;
                        width:100%
                    }

                    .table-keluarga td,
                    .table-keluarga th {
                        word-wrap: break-word;
                        white-space: normal;
                        vertical-align: top;
                    }
                    </style>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center align-middle">No</th>
                                <th class="text-center">Nama (Inisial)</th>
                                <th class="text-center">Jenis Kelamin</th>
                                <th class="text-center">Hub. dengan KK</th>
                                <th class="text-center align-middle">Umur</th>
                                <th class="text-center align-middle">Pendidikan</th>
                                <th class="text-center align-middle">Pekerjaan</th>
                                <th class="text-center">Status Gizi</th>
                                <th class="text-center">Status Imunisasi</th>
                                <th class="text-center">Kondisi Kesehatan</th>
                                <th class="text-center">Tanda-tanda Vital</th>
                        </tr>
                        </thead>

                    <tbody>

                    <?php
                    $no = 1;
                    if(!empty($data)){
                        foreach($data as $row){
                            echo "<tr>
                            <td>".$no++."</td>
                            <td>".$row['namainisial']."</td>
                            <td>".$row['jeniskelamin']."</td>
                            <td>".$row['hubungandengankk']."</td>
                            <td>".$row['umur']."</td>
                            <td>".$row['pendidikan']."</td>
                            <td>".$row['pekerjaan']."</td>
                            <td>
                            <b>BB :</b> ".$row['bb']."<br>
                            <b>TB :</b> ".$row['tb']."<br>
                            <b>IMT :</b> ".$row['imt']."<br>
                            </td>
                            <td>".$row['statusimunisasi']."</td>
                            <td>".$row['kondisikesehatan']."</td>
                            <td>
                            <b>TD :</b> ".$row['tekanandarah']."<br>
                            <b>N : </b> ".$row['nadi']."<br>
                            <b>S : </b> ".$row['suhu']."<br>
                            <b>RR: </b> ".$row['rr']."<br>
                            </td>
                            </tr>";
                        }
                    }
                    ?>

                    </tbody>
                    </table>
   
                <!-- Bagian Genogram -->
                <div class="row mb-3">
                    <label for="genogram" class="col-sm-2 col-form-label"><strong>Genogram</strong></label>
                    <div class="col-sm-9">

                        <!-- Link Google Drive -->
                         <div class="form-control d-flex justify-content-between align-items-center">
                            <span>Upload Gambar Genogram pada link Google Drive yang tersedia</span>
                            <a href="<?= $genogram ?>" target="_blank" class="btn btn-sm btn-primary">Upload</a>
                        </div>

                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentgenogram" id="commentgenogram" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>

                <!-- Bagian Keterangan Gambar -->
                <div class="row mb-3">
                    <label for="keterangangambar" class="col-sm-2 col-form-label"><strong>Keterangan Gambar</strong></label>
                    <div class="col-sm-9">
                       <textarea name="keterangangambar" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentketerangangambar" id="commentketerangangambar" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>

                    <!-- Bagian Penjelasan -->
                    <div class="row mb-3">
                        <label for="penjelasan" class="col-sm-2 col-form-label"><strong>Penjelasan</strong></label>
                            <div class="col-sm-9">
                            <textarea name="penjelasan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                                
                                <!-- comment -->
                                    <textarea class="form-control mt-2" name="commenpenjelasan" id="commentpenjelasan" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                                    oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                                </div>

                                <div class="col-sm-1 d-flex align-items-start">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" disabled>
                                    </div>
                                </div>
                            </div> 
                            
                     <!-- Bagian G1 G2 G3 -->
                     <div class="row mb-2 align-items-center">
                        <label class="col-sm-2 col-form-label"><strong>G1</strong></label>
                        
                        <div class="col-md-3">
                            <input type="text" class="form-control" name="g1">
                        </div>

                        <div class="col-md-3 d-flex align-items-center">
                            <label class="me-2"><strong>G2</strong></label>
                            <input type="text" class="form-control" name="g2">
                        </div>

                        <div class="col-md-3 d-flex align-items-center">
                            <label class="me-2"><strong>G3</strong></label>
                            <input type="text" class="form-control" name="g3">
                        </div>

                             
                        <div class="col-sm-1 d-flex align-items-center">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                             </div>
                        </div>
                    </div>

                         <!-- comment -->
                         <div class="row mb-3"> 
                         <div class="col-sm-2"></div>
                         
                         <div class="col-sm-9">
                            <textarea class="form-control mt-2" name="commentg1g2g3" id="commentg1g2g3" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1"></div>
                        </div>
    
            <h5 class="card-title"><strong>II. Riwayat dan Tahap Perkembangan Keluarga</strong></h5>    
                
                <!-- Bagian Tahap Perkembangan Keluarga Saat ini -->
                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label"><strong>a. Tahap Perkembangan Keluarga</strong></label>
                    
                    <div class="col-sm-9">

                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="tahapkeluarga" value="tahap1">Tahap 1: Keluarga pasangan baru</label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="tahapkeluarga" value="tahap2">Tahap 2: Keluarga dengan kelahiran anak pertama</label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="tahapkeluarga" value="tahap3">Tahap 3: Keluarga dengan anak pra sekolah</label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="tahapkeluarga" value="tahap4">Tahap 4: Keluarga dengan anak sekolah</label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="tahapkeluarga" value="tahap5">Tahap 5: Keluarga dengan anak remaja</label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="tahapkeluarga" value="tahap6">Tahap 6: Keluarga dengan usia dewasa/pertengahan</label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="tahapkeluarga" value="tahap7">Tahap 7: Keluarga dengan usia pelepasan</label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="tahapkeluarga" value="tahap8">Tahap 8: Keluarga dengan lanjut usia</label>
                    </div>
                </div>
            </div>

            <!-- Bagian Tahap Perkembangan Keluarga yang Belum Terpenuhi -->

             <div class="row mb-3">
                <label for="belumterpenuhi" class="col-sm-2 col-form-label"><strong>b. Tahap Perkembangan Keluarga yang Belum Terpenuhi</strong></label>
                    <div class="col-sm-9">
                       <textarea name="belumterpenuhi" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentbelumterpenuhi" id="commentbelumterpenuhi" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>  
                    
            <h5 class="card-title"><strong>III. Riwayat Kesehatan Keluarga Inti</strong></h5> 
            
            <!-- Bagian Gambaran Umum Kondisi Kesehatan Seluruh Anggota Keluarga -->

             <div class="row mb-3">
                <label for="kondisikesehatankeluarga" class="col-sm-2 col-form-label"><strong>Gambaran Umum Kondisi Kesehatan Seluruh Anggota Keluarga</strong></label>
                    <div class="col-sm-9">
                       <textarea name="kondisikesehatankeluarga" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentkondisikesehatankeluarga" id="commentkondisikesehatankeluarga" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 
                    
                 <!-- Bagian Saat Ini Anggota Keluarga yang Sakit dan Keluhan -->

                <div class="row mb-3">
                    <label for="anggotasakitdankeluhan" class="col-sm-2 col-form-label"><strong>Saat Ini Anggota Keluarga yang Sakit dan Keluhan</strong></label>
                        <div class="col-sm-9">
                        <textarea name="anggotasakitdankeluhan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                            
                            <!-- comment -->
                                <textarea class="form-control mt-2" name="commentanggotasakitdankeluhan" id="commentanggotasakitdankeluhan" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                            </div>

                            <div class="col-sm-1 d-flex align-items-start">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" disabled>
                                </div>
                            </div>
                        </div>    
                        
            <!-- Bagian Keluhan/Sakit yang Sering Dialami Berulang Dalam Keluarga -->

             <div class="row mb-3">
                <label for="keluhansakit" class="col-sm-2 col-form-label"><strong>Keluhan/Sakit yang Sering Dialami Berulang Dalam Keluarga</strong></label>
                    <div class="col-sm-9">
                       <textarea name="keluhansakit" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentkeluhansakit" id="commentkeluhansakit" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 
                    
             <!-- Bagian Anggota Keluarga yang Menderita Penyakit Kronik Membutuhkan Penanganan/Perawatan -->

             <div class="row mb-3">
                <label for="penyakitkronik" class="col-sm-2 col-form-label"><strong>Anggota Keluarga yang Menderita Penyakit Kronik Membutuhkan Penanganan/ Perawatan</strong></label>
                    <div class="col-sm-9">
                       <textarea name="penyakitkronik" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentpenyakitkronik" id="commentpenyakitkronik" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>    

            <h5 class="card-title"><strong>IV. Riwayat Kesehatan Keluarga Sebelumnya</strong></h5>   
             
             <!-- Bagian Anggota Keluarga yang Pernah Dirawat dan Penyakit yang Diderita -->

             <div class="row mb-3">
                <label for="penyakitdiderita" class="col-sm-2 col-form-label"><strong>Anggota Keluarga yang Pernah Dirawat dan Penyakit yang Diderita</strong></label>
                    <div class="col-sm-9">
                       <textarea name="penyakitdiderita" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentpenyakitdiderita" id="commentpenyakitdiderita" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>   

            <h5 class="card-title"><strong>V. Data Lingkungan</strong></h5>  
            
             <!-- Bagian Karakteristik Rumah -->
                    <div class="row mb-3">
                        <label class="col-sm-10 col-form-label text-primary">
                            <strong>1. Karakteristik Rumah</strong>
                    </div> 
                    
            <!-- Bagian Gambar Denah Rumah -->
                <div class="row mb-3">
                    <label for="gambardenahrumah" class="col-sm-2 col-form-label"><strong>Gambar Denah Rumah</strong></label>
                    <div class="col-sm-9">

                        <!-- Link Google Drive -->
                         <div class="form-control d-flex justify-content-between align-items-center">
                            <span>Upload Gambar Denah Rumah pada link Google Drive yang tersedia</span>
                            <a href="<?= $gambardenahrumah ?>" target="_blank" class="btn btn-sm btn-primary">Upload</a>
                        </div>

                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentgambardenahrumah" id="commentgambardenahrumah" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>

            <!-- Bagian Penjelasan Karakteristik Rumah -->

                    <div class="row mb-3">
                        <label class="col-sm-9 col-form-label">
                            <strong>Penjelasan Karakteristik Rumah</strong>
                        </label>    
                    </div>

                    <!-- Jenis Bangunan -->
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-2 col-form-label"><strong>Jenis Bangunan</strong></label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control" name="jenisbangunan">   
                    </div>
                                
                    <!-- Jumlah Kamar -->
                    <label class="col-sm-2 col-form-label"><strong>Jumlah Kamar</strong></label>
                        <div class="col-sm-3">
                                <input type="text" class="form-control" name="jumlahkamar">
                    </div>

                    <div class="col-sm-1 d-flex align-items-start">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" disabled>
                        </div>
                    </div>
                </div>
              
                <!-- Pencahayaan -->
                <div class="row mb-3 align-items-center">
                    <label class="col-sm-2 col-form-label"><strong>Pencahayaan</strong></label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control" name="pencahayaan">
                    </div>

                <!-- Ventilasi -->
                <label class="col-sm-2 col-form-label"><strong>Ventilasi</strong></label>
                    <div class="col-sm-3">
                        <input type="text" class="form-control" name="ventilasi">
                    </div>
                </div>

                <!-- Sumber Air Bersih -->
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-2 col-form-label"><strong>Sumber Air Bersih</strong></label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control" name="sumberairbersih">   
                    </div>
                                
                    <!-- Sumber Air Minum -->
                    <label class="col-sm-2 col-form-label"><strong>Sumber Air Minum</strong></label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control" name="sumberairminum">
                    </div>

                    <div class="col-sm-1 d-flex align-items-start">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" disabled>
                        </div>
                    </div>
                </div>  
                
                <!-- Pembuangan Air Limbah -->
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-2 col-form-label"><strong>Pembuangan Air Limbah</strong></label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control" name="pembuanganairlimbah">   
                    </div>
                                
                    <!-- Penerangan (Malam Hari) -->
                    <label class="col-sm-2 col-form-label"><strong>Penerangan (Malam Hari)</strong></label>
                        <div class="col-sm-3">
                                <input type="text" class="form-control" name="peneranganmalamhari">
                    </div>

                    <div class="col-sm-1 d-flex align-items-start">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" disabled>
                        </div>
                    </div>
                </div>

                <!-- Kebersihan Toilet, Kamar Mandi, dan Tempat Penampungan Air -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Kebersihan Toilet, Kamar Mandi, dan Tempat Penampungan Air</strong></label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control" name="kebersihantoilet">   
                    </div>
                                
                    <!-- Jumlah Jentik Nyamuk di Tempat Penampungan Air -->
                    <label class="col-sm-2 col-form-label"><strong>Jumlah Jentik Nyamuk di Tempat Penampungan Air</strong></label>
                        <div class="col-sm-3">
                                <input type="text" class="form-control" name="jumlahjentiknyamuk">
                    </div>

                    <div class="col-sm-1 d-flex align-items-start">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" disabled>
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
                        
             <!-- Bagian Karakteristik Tetangga Komunitasnya -->
                    <div class="row mb-3">
                        <label class="col-sm-10 col-form-label text-primary">
                            <strong>2. Karakteristik Tetangga Komunitasnya</strong>
                    </div>             
        
             <!-- Bagian Jarak Rumah disekitarnya -->

             <div class="row mb-3">
                <label for="jarakrumahdisekitarnya" class="col-sm-2 col-form-label"><strong>Jarak Rumah disekitarnya</strong></label>
                    <div class="col-sm-9">
                        <div class="input-group">
                            <input type="text" class="form-control" name="jarakrumahdisekitarnya">
                            <span class="input-group-text">meter</span>
                    </div>  
                        
                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentjarakrumahdisekitarnya" id="commentjarakrumahdisekitarnya" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>          
                    
             <!-- Bagian Kondisi Tetangga Sekitar -->

             <div class="row mb-3">
                <label for="kondisitetanggasekitar" class="col-sm-2 col-form-label"><strong>Kondisi Tetangga Sekitar</strong></label>
                    <div class="col-sm-9">
                       <textarea name="kondisitetanggasekitar" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentkondisitetanggasekitar" id="commentkondisitetanggasekitar" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>  
                    
             <!-- Bagian Tanggapan Keluarga Terhadap Tetangga Sekitar -->

             <div class="row mb-3">
                <label for="keluargaterhadaptetanggasekitar" class="col-sm-2 col-form-label"><strong>Tanggapan Keluarga Terhadap Tetangga Sekitar</strong></label>
                    <div class="col-sm-9">
                       <textarea name="keluargaterhadaptetanggasekitar" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentkeluargaterhadaptetanggasekitar" id="commenkeluargaterhadaptetanggasekitar" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>    
                    
            <!-- Bagian Mobilitas Geografis Keluarga -->
                    <div class="row mb-3">
                        <label class="col-sm-10 col-form-label text-primary">
                            <strong>3. Mobilitas Geografis Keluarga</strong>
                    </div>         
            
            <!-- Bagian Kegiatan Keluarga Saat Pagi -->

             <div class="row mb-3">
                <label for="kegiatankeluargasaatpagi" class="col-sm-2 col-form-label"><strong>Kegiatan Keluarga Saat Pagi</strong></label>
                    <div class="col-sm-9">
                       <textarea name="kegiatankeluargasaatpagi" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentkegiatankeluargasaatpagi" id="commenkegiatankeluargasaatpagi" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>   

             <!-- Bagian Kegiatan Keluarga Saat Siang/Sore -->

             <div class="row mb-3">
                <label for="kegiatankeluargasaatsiangsore" class="col-sm-2 col-form-label"><strong>Kegiatan Keluarga Saat Siang/Sore</strong></label>
                    <div class="col-sm-9">
                       <textarea name="kegiatankeluargasaatsiangsore" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentkegiatankeluargasaatsiangsore" id="commenkegiatankeluargasaatsiangsore" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>  
                    
             <!-- Bagian Kegiatan Keluarga Saat Malam -->

             <div class="row mb-3">
                <label for="kegiatankeluargasaatmalam" class="col-sm-2 col-form-label"><strong>Kegiatan Keluarga Saat Malam</strong></label>
                    <div class="col-sm-9">
                       <textarea name="kegiatankeluargasaatmalam" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentkegiatankeluargsaatmalam" id="commenkegiatankeluargasaatmalam" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 
                    
             <!-- Bagian Kegiatan Keluarga Saat Senggang/Luang -->

             <div class="row mb-3">
                <label for="kegiatankeluargasaatsenggangluang" class="col-sm-2 col-form-label"><strong>Kegiatan Keluarga Saat Senggang/Luang</strong></label>
                    <div class="col-sm-9">
                       <textarea name="kegiatankeluargasaatsenggangluang" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentkegiatankeluargasaatsenggangluang" id="commenkegiatankeluargasaatsenggangluang" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 
                    
             <!-- Bagian Waktu Keluarga Berkunjung untuk Saudara yang lain -->

             <div class="row mb-3">
                <label for="waktuberkunjung" class="col-sm-2 col-form-label"><strong>Waktu Keluarga Berkunjung untuk Saudara yang lain</strong></label>
                    <div class="col-sm-9">
                       <textarea name="waktuberkunjung" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentwaktuberkunjung" id="commenwaktuberkunjung" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>  
                    
             <!-- Bagian Perkumpulan Keluarga dan Interaksi dengan Masyarakat -->
                    <div class="row mb-3">
                        <label class="col-sm-10 col-form-label text-primary">
                            <strong>4. Perkumpulan Keluarga dan Interaksi dengan Masyarakat</strong>
                    </div>         
                    
             <!-- Bagian Keluarga Besar Kumpul Pada Saat -->

             <div class="row mb-3">
                <label for="keluargabesarkumpulpadasaat" class="col-sm-2 col-form-label"><strong>Keluarga Besar Berkumpul Pada Saat</strong></label>
                    <div class="col-sm-9">
                       <textarea name="keluargabesarberkumpulpadasaat" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentkeluargabesarberkumpulpadasaat" id="commenkeluargabesarberkumpulpadasaat" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>  
                    
             <!-- Bagian Kegiatan yang Ada dan Diikuti di Lingkungan Tempat Tinggal -->

             <div class="row mb-3">
                <label for="kegiatanditempattinggal" class="col-sm-2 col-form-label"><strong>Kegiatan yang Ada dan Diikuti di Lingkungan Tempat Tinggal</strong></label>
                    <div class="col-sm-9">
                       <textarea name="kegiatanditempattinggal" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentkegiatanditempattinggal" id="commenkegiatanditempattinggal" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>   
                    
             <!-- Bagian Sistem Pendukung Keluarga -->
                    <div class="row mb-3">
                        <label class="col-sm-10 col-form-label text-primary">
                            <strong>5. Sistem Pendukung Keluarga</strong>
                    </div>   
                    
             <!-- Bagian Yang dimintai Pertolongan Bila Keluarga Menghadapi Masalah Keuangan/Ekonomi -->

             <div class="row mb-3">
                <label for="masalahekonomi" class="col-sm-2 col-form-label"><strong>Yang dimintai Pertolongan Bila Keluarga Menghadapi Masalah Keuangan/Ekonomi</strong></label>
                    <div class="col-sm-9">
                       <textarea name="masalahekonomi" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentmasalahekonomi" id="commentmasalahekonomi" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>  
                    
             <!-- Bagian Anggota Keluarga yang Sering Memberikan Support/Dukungan Bila Keluarga Menghadapi Masalah -->

             <div class="row mb-3">
                <label for="dukunganmenghadapimasalah" class="col-sm-2 col-form-label"><strong>Anggota Keluarga yang Sering Memberikan Support/Dukungan Bila Keluarga Menghadapi Masalah</strong></label>
                    <div class="col-sm-9">
                       <textarea name="dukunganmenghadapimasalah" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentdukunganmenghadapimasalah" id="commentdukunganmenghadapimasalah" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 
                    
             <!-- Bagian Jenis Jaminan Sosial Kesehatan yang Dimiliki Keluarga -->

             <div class="row mb-3">
                <label for="jaminansosialkesehatan" class="col-sm-2 col-form-label"><strong>Jenis Jaminan Sosial Kesehatan yang Dimiliki Keluarga</strong></label>
                    <div class="col-sm-9">
                       <textarea name="jaminansosialkesehatan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentjaminansosialkesehatan" id="commentjaminansosialkesehatan" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>  

            <h5 class="card-title"><strong>VI. Struktur Keluarga</strong></h5>
            
             <!-- Bagian Pola Komunikasi Keluarga -->
                    <div class="row mb-3">
                        <label class="col-sm-10 col-form-label text-primary">
                            <strong>1. Pola Komunikasi Keluarga</strong>
                    </div> 

             <!-- Bagian Komunikasi Antar Keluarga -->

             <div class="row mb-3">
                <label for="komunikasiantarkeluarga" class="col-sm-2 col-form-label"><strong>Komunikasi Antar Keluarga</strong></label>
                    <div class="col-sm-9">
                       <textarea name="komunikasiantarkeluarga" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentkomunikasiantarkeluarga" id="commentmonukasiantarkeluarga" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 
                    
             <!-- Bagian Bahasa yang Sering Digunakan -->

             <div class="row mb-3">
                <label for="bahasayangseringdigunakan" class="col-sm-2 col-form-label"><strong>Bahasa yang Sering Digunakan</strong></label>
                    <div class="col-sm-9">
                       <textarea name="bahasayangseringdigunakan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentbahasayangseringdigunakan" id="commentbahasayangseringdigunakan" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 
                    
            <!-- Bagian Anggota Keluarga yang Memiliki Telepon/Ponsel -->

             <div class="row mb-3">
                <label for="memilikiponsel" class="col-sm-2 col-form-label"><strong>Anggota Keluarga yang Memiliki Telepon/Ponsel</strong></label>
                    <div class="col-sm-9">
                       <textarea name="memilikiponsel" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentmemilikiponsel" id="commentmemilikiponsel" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 
                    
             <!-- Bagian Lama/Waktu Pembatasan Anak Menggunakan Ponsel -->

             <div class="row mb-3">
                <label for="waktuanak" class="col-sm-2 col-form-label"><strong>Lama/Waktu Pembatasan Anak Menggunakan Ponsel</strong></label>
                    <div class="col-sm-9">
                       <textarea name="waktuanak" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentwaktuanak" id="commentwaktuanak" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 
                    
             <!-- Bagian Struktur Peran Keluarga -->
                    <div class="row mb-3">
                        <label class="col-sm-10 col-form-label text-primary">
                            <strong>2. Struktur Peran Keluarga</strong>
                    </div> 
                    
             <!-- Bagian Peran Kepala Keluarga -->

             <div class="row mb-3">
                <label for="perankepalakeluarga" class="col-sm-2 col-form-label"><strong>Peran Kepala Keluarga</strong></label>
                    <div class="col-sm-9">
                       <textarea name="perankepalakeluarga" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentperankepalakeluarga" id="commentperankepalakeluarga" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 
                    
             <!-- Bagian Peran Istri dalam Keluarga -->

             <div class="row mb-3">
                <label for="peranistridalamkeluarga" class="col-sm-2 col-form-label"><strong>Peran Istri dalam Keluarga</strong></label>
                    <div class="col-sm-9">
                       <textarea name="peranistridalamkeluarga" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentperanistridalamkeluarga" id="commentperanistridalamkeluarga" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>   
                    
             <!-- Bagian Peran Anak dalam Keluarga -->

             <div class="row mb-3">
                <label for="perananakdalamkeluarga" class="col-sm-2 col-form-label"><strong>Peran Anak dalam Keluarga</strong></label>
                    <div class="col-sm-9">
                       <textarea name="perananakdalamkeluarga" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentperananakdalamkeluarga" id="commentperananakdalamkeluarga" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 
                    
             <!-- Bagian Nilai atau Norma Keluarga -->
                    <div class="row mb-3">
                        <label class="col-sm-10 col-form-label text-primary">
                            <strong>3. Nilai atau Norma Keluarga</strong>
                    </div>    
                    
             <!-- Bagian Kebiasaan di Rumah yang Diterapkan Mengikuti Adat/Budaya/Suku -->

             <div class="row mb-3">
                <label for="kebiasaandirumah" class="col-sm-2 col-form-label"><strong>Kebiasaan di Rumah yang Diterapkan mengikuti Adat/Budaya/Suku</strong></label>
                    <div class="col-sm-9">
                       <textarea name="kebiasaandirumah" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentkebiasaandirumah" id="commentkebiasaandirumah" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 
                    
             <!-- Bagian Penerapan Nila-nilai Agama di Keluarga -->

             <div class="row mb-3">
                <label for="nilainilaiagama" class="col-sm-2 col-form-label"><strong>Penerapan Nilai-nilai Agama di Keluarga</strong></label>
                    <div class="col-sm-9">
                       <textarea name="nilainilaiagama" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentnilainilaiagama" id="commentnilainilaiagama" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 
                    
             <!-- Bagian Tanggapan Keluarga Terhadap Pergaulan Anak Baik di Lingkungan Sekitar/di Sekolah -->

             <div class="row mb-3">
                <label for="pergaulananak" class="col-sm-2 col-form-label"><strong>Tanggapan Keluarga Terhadap Pergaulan Anak Baik di Lingkungan Sekitar/di Sekolah</strong></label>
                    <div class="col-sm-9">
                       <textarea name="pergaulananak" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentpergaulananak" id="commentpergaulananak" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>    
                    
             <!-- Bagian Struktur Kekuatan Keluarga -->
                    <div class="row mb-3">
                        <label class="col-sm-10 col-form-label text-primary">
                            <strong>4. Struktur Kekuatan Keluarga</strong>
                    </div>   
                    
             <!-- Bagian Yang Mengambil Keputusan dalam Keluarga -->

             <div class="row mb-3">
                <label for="mengambilkeputusan" class="col-sm-2 col-form-label"><strong>Yang Mengambil Keputusan dalam Keluarga</strong></label>
                    <div class="col-sm-9">
                       <textarea name="mengambilkeputusan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentmengambilkeputusan" id="commentmengambilkeputusan" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 
                    
             <!-- Bagian Peran Anggota Keluarga dalam Pengampilan Keputusan dalam Keluarga -->

             <div class="row mb-3">
                <label for="pengambilankeputusankeluarga" class="col-sm-2 col-form-label"><strong>Peran Anggota Keluarga dalam Pengambilan Keputusan dalam Keluarga</strong></label>
                    <div class="col-sm-9">
                       <textarea name="pengambilankeputusankeluarga" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentpengambilankeputusankeluarga" id="commentpengambilankeputusankeluarga" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 
                    
            <h5 class="card-title"><strong>VII. Fungsi Keluarga</strong></h5>
            
            <!-- Bagian Fungsi Afeksi -->
                    <div class="row mb-3">
                        <label class="col-sm-10 col-form-label text-primary">
                            <strong>1. Fungsi Afeksi</strong>
                    </div> 

             <!-- Bagian Kedekatan Emosi Antar Anggota Keluarga -->

             <div class="row mb-3">
                <label for="emosikeluarga" class="col-sm-2 col-form-label"><strong>Kedekatan Emosi Antar Anggota Keluarga</strong></label>
                    <div class="col-sm-9">
                       <textarea name="emosikeluarga" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentemosikeluarga" id="commentemosikeluarga" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>  
                    
             <!-- Bagian Respon Anggota Keluarga Bila Ada yang Mengalami Masalah -->

             <div class="row mb-3">
                <label for="responmasalah" class="col-sm-2 col-form-label"><strong>Respon Anggota Keluarga Bila Ada yang Mengalami Masalah</strong></label>
                    <div class="col-sm-9">
                       <textarea name="responmasalah" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentresponmasalah" id="commentresponmasalah" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>    
                    
             <!-- Bagian Cara Keluarga Agar Tetap Harmonis untuk Anggota Keluarga -->

             <div class="row mb-3">
                <label for="harmoniskeluarga" class="col-sm-2 col-form-label"><strong>Cara Keluarga Agar Tetap Harmonis untuk Anggota Keluarga</strong></label>
                    <div class="col-sm-9">
                       <textarea name="harmoniskeluarga" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentkeluargaterhadaptetanggasekitar" id="commenkeluargaterhadaptetanggasekitar" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>
                    
             <!-- Bagian Fungsi Sosial -->
                    <div class="row mb-3">
                        <label class="col-sm-10 col-form-label text-primary">
                            <strong>2. Fungsi Sosial</strong>
                    </div>         
                    
             <!-- Bagian Interaksi Keluarga dengan Lingkungan Sekitar -->

             <div class="row mb-3">
                <label for="interaksilingkungan" class="col-sm-2 col-form-label"><strong>Interkasi Keluarga dengan Lingkungan Sekitar</strong></label>
                    <div class="col-sm-9">
                       <textarea name="interaksilingkungan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentinteraksilingkungan" id="commentinteraksilingkungan" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>    
                    
             <!-- Bagian Cara Keluarga Agar Anak Bersosialisasi dengan Lingkungan Sekitar -->

             <div class="row mb-3">
                <label for="sosialisasianak" class="col-sm-2 col-form-label"><strong>Cara Keluarga Agar Anak Bersosialisasi dengan Lingkungan Sekitar</strong></label>
                    <div class="col-sm-9">
                       <textarea name="sosialisasianak" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentsosialisasianak" id="commentsosialisasianak" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>
                    
             <!-- Bagian Fungsi Reproduksi -->
                    <div class="row mb-3">
                        <label class="col-sm-10 col-form-label text-primary">
                            <strong>3. Fungsi Reproduksi</strong>
                    </div> 
                    
             <!-- Bagian Jumlah Anak -->

             <div class="row mb-3">
                <label for="jumlahanak" class="col-sm-2 col-form-label"><strong>Jumlah Anak</strong></label>
                    <div class="col-sm-9">
                       <input type="text" class="form-control" name="jumlahanak">

                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentjumlahanak" id="commentjumlahanak" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 
                    
             <!-- Bagian Keinginan untuk Menambah Anak -->

             <div class="row mb-3">
                <label for="menambahanak" class="col-sm-2 col-form-label"><strong>Keinginan untuk Menambah Anak</strong></label>
                    <div class="col-sm-9">
                       <input type="text" class="form-control" name="menambahanak">

                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentmenambahanak" id="commentmenambahanak" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 
                    
             <!-- Bagian Tanggapan Keluarga dengan Jumlah Anaknya Saat ini -->

             <div class="row mb-3">
                <label for="tanggapanjumlahanak" class="col-sm-2 col-form-label"><strong>Tanggapan Keluarga dengan Jumlah Anaknya Saat Ini</strong></label>
                    <div class="col-sm-9">
                       <textarea name="tanggapanjumlahanak" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commenttanggapanjumlahanak" id="commenttanggapanjumlahanak" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>   
                    
             <!-- Bagian Jenis Kontrasepsi yang Digunakan -->

             <div class="row mb-3">
                <label for="jeniskontrasepsi" class="col-sm-2 col-form-label"><strong>Jenis Kontrasepsi yang Digunakan Sebelum dan Saat Ini</strong></label>
                    <div class="col-sm-9">
                       <input type="text" class="form-control" name="jeniskontrasepsi">

                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentjeniskontrasepsi" id="commentjeniskontrasepsi" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>  
                    
             <!-- Bagian Ekonomi -->
                    <div class="row mb-3">
                        <label class="col-sm-10 col-form-label text-primary">
                            <strong>4. Fungsi Ekonomi</strong>
                    </div>    
                    
             <!-- Bagian Penghasilan Keluarga Perbulan -->

             <div class="row mb-3">
                <label for="penghasilankeluargaperbulan" class="col-sm-2 col-form-label"><strong>Penghasilan Keluarga Perbulan</strong></label>
                    <div class="col-sm-9">
                       <input type="text" class="form-control" name="penghasilankeluargaperbulan">

                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentpenghasilankeluargaperbulan" id="commentpenghasilankeluargaperbulan" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>    
                    
            <!-- Bagian Tanggapan Keluarga Tentang Penghasilan Tersebut dalam Memenuhi Kebutuhan Sehari-hari -->

             <div class="row mb-3">
                <label for="tanggapanpenghasilan" class="col-sm-2 col-form-label"><strong>Tanggapan Keluarga Tentang Penghasilan Tersebut dalam Memenuhi Kebutuhan Sehari-hari</strong></label>
                    <div class="col-sm-9">
                       <textarea name="tanggapanpenghasilan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commenttanggapanpenghasilan" id="commenttanggapanpenghasilan" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>
                    
             <!-- Bagian Perawatan Kesehatan Keluarga -->
                    <div class="row mb-3">
                        <label class="col-sm-10 col-form-label text-primary">
                            <strong>5. Fungsi Perawatan Kesehatan Keluarga <br> a. Mengenal Masalah Keluarga</strong>
                    </div> 
                    
            <!-- Bagian Adakah Perhatian Keluarga Kepada Anggotanya yang Menderita Sakit -->
                 <div class="row mb-3">
                    <label for="perhatiankeluarga" class="col-sm-4 col-form-label"><strong>1. Adakah Perhatian Keluarga Kepada Anggotanya yang Menderita Sakit</strong></label> 
                    <div class="col-sm-7">
                    <select class="form-select" name="perhatiankeluarga">
                            <option value="">Pilih</option>
                            <option value="Ya">Ya</option>
                            <option value="Tidak">Tidak</option>
                            </select>
                            
                     <!-- comment -->
                            <textarea class="form-control mt-2" name="commentperhatiankeluarga" id="commentperhatiankeluarga" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>
                    
             <!-- Bagian Bila Jawaban Tidak, Alasanya -->

             <div class="row mb-3">
                <label for="jawabantidak" class="col-sm-4 col-form-label"><strong>Bila Jawaban Tidak, Alasannya</strong></label>
                    <div class="col-sm-7">
                       <textarea name="jawabantidak" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentjawabantidak" id="commentjawabantidak" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>
                    
            <!-- Bagian Apakah Keluarga Mengetahui Masalah Kesehatan yang Dialami Anggota dalam Keluarganya -->
                 <div class="row mb-3">
                    <label for="mengetahuimasalahkesehatan" class="col-sm-4 col-form-label"><strong>2. Apakah Keluarga Mengetahui Masalah Kesehatan yang Dialami Anggota dalam Keluarganya</strong></label> 
                    <div class="col-sm-7">
                    <select class="form-select" name="mengetahuimasalahkesehatan">
                            <option value="">Pilih</option>
                            <option value="Ya">Ya</option>
                            <option value="Tidak">Tidak</option>
                            </select>
                            
                     <!-- comment -->
                            <textarea class="form-control mt-2" name="commentmengetahuimasalahkesehatan" id="commentmengetahuimasalahkesehatan" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>
                    
            <!-- Bagian Apakah Keluarga Mengetahui Penyebab Masalah Kesehatan yang dialami Anggota dalam Keluarganya -->
                 <div class="row mb-3">
                    <label for="penyebabmasalahkesehatan" class="col-sm-4 col-form-label"><strong>3. Apakah Keluarga Mengetahui Penyebab Masalah Kesehatan yang Dialami Anggota dalam Keluarganya</strong></label> 
                    <div class="col-sm-7">
                    <select class="form-select" name="penyebabmasalahkesehatan">
                            <option value="">Pilih</option>
                            <option value="Ya">Ya</option>
                            <option value="Tidak">Tidak</option>
                            </select>
                            
                     <!-- comment -->
                            <textarea class="form-control mt-2" name="commentperhatiankeluarga" id="commentperhatiankeluarga" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>
                    
            <!-- Bagian Apakah Keluarga Mengetahui Tanda dan Gejala Masalah Kesehatan yang Dialami Anggota dalam Keluarganya -->
                 <div class="row mb-3">
                    <label for="tandadangejala" class="col-sm-4 col-form-label"><strong>4. Apakah Keluarga Mengetahui Tanda dan Gejala Masalah Kesehatan yang Dialami Anggota dalam Keluarganya</strong></label> 
                    <div class="col-sm-7">
                    <select class="form-select" name="tandadangejala">
                            <option value="">Pilih</option>
                            <option value="Ya">Ya</option>
                            <option value="Tidak">Tidak</option>
                            </select>
                            
                     <!-- comment -->
                            <textarea class="form-control mt-2" name="commenttandadangejala" id="commenttandadangejala" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>        

                 <!-- Bagian Mengambil Keputusan -->
                    <div class="row mb-3">
                        <label class="col-sm-10 col-form-label text-primary">
                            <strong>b. Mengambil Keputusan</strong>
                    </div> 
                    
                <!-- Bagian Apakah Keluarga Mengetahui Akibat Masalah Kesehatan yang Dialami Anggota Dalam Keluarganya Bila Tidak Diobati/Dirawat -->
                 <div class="row mb-3">
                    <label for="akibatmasalahkesehatan" class="col-sm-4 col-form-label"><strong>1. Apakah Keluarga Mengetahui Akibat Masalah Kesehatan yang Dialami Anggota dalam Keluarganya Bila Tidak Diobati/Dirawat</strong></label> 
                    <div class="col-sm-7">
                    <select class="form-select" name="akibatmasalahkesehatan">
                            <option value="">Pilih</option>
                            <option value="Ya">Ya</option>
                            <option value="Tidak">Tidak</option>
                            </select>
                            
                     <!-- comment -->
                            <textarea class="form-control mt-2" name="commentakibatmasalahkesehatan" id="commentakibatmasalahkesehatan" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>  
                    
             <!-- Bagian Pada Siapa Keluarga Biasa Menggali Informasi Tentang Masalah Kesehatan yang Dialami Anggota Keluarganya -->

             <div class="row mb-3">
                <label for="informasikesehatan" class="col-sm-4 col-form-label"><strong>2. Pada Siapa Keluarga Biasa Menggali Informasi Tentang Masalah Kesehatan yang Dialami Anggota Keluarganya</strong></label>
                    <div class="col-sm-7">
                       <textarea name="informasikesehatan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentinformasikesehatan" id="commentinformasikesehatan" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>  
                    
             <!-- Bagian Keyakinan Keluarga Tentang Masalah Kesehatan yang Dialami Anggota Keluarganya -->
                    <div class="row mb-3">
                        <label class="col-sm-10 col-form-label">
                            <strong>3. Keyakinan Keluarga Tentang Masalah Kesehatan yang Dialami Anggota Keluarganya</strong>
                    </div> 
                    
            <!-- Bagian Tidak Perlu ditangani karena Akan Sembuh Sendiri Biasanya -->
                 <div class="row mb-3">
                    <label for="sembuhsendiri" class="col-sm-4 col-form-label"><strong>- Tidak Perlu ditangani karena Akan Sembuh Sendiri Biasanya</strong></label> 
                    <div class="col-sm-7">
                    <select class="form-select" name="sembuhsendiri">
                            <option value="">Pilih</option>
                            <option value="Ya">Ya</option>
                            <option value="Tidak">Tidak</option>
                            </select>
                            
                     <!-- comment -->
                            <textarea class="form-control mt-2" name="commentsembuhsendiri" id="commentsembuhsendiri" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>  
                    
            <!-- Bagian Perlu Berobat ee Fasilitas Yankes -->
                 <div class="row mb-3">
                    <label for="perluberobat" class="col-sm-4 col-form-label"><strong>- Perlu Berobat ke Fasilitas Yankes</strong></label> 
                    <div class="col-sm-7">
                    <select class="form-select" name="perluberobat">
                            <option value="">Pilih</option>
                            <option value="Ya">Ya</option>
                            <option value="Tidak">Tidak</option>
                            </select>
                            
                     <!-- comment -->
                            <textarea class="form-control mt-2" name="commentperluberobat" id="commentperluberobat" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>   
                    
            <!-- Bagian Tidak Terpikir/Tidak Peduli/Cuek -->
                 <div class="row mb-3">
                    <label for="tidakpeduli" class="col-sm-4 col-form-label"><strong>- Tidak Terpikir/Tidak Peduli/Cuek</strong></label> 
                    <div class="col-sm-7">
                    <select class="form-select" name="tidakpeduli">
                            <option value="">Pilih</option>
                            <option value="Ya">Ya</option>
                            <option value="Tidak">Tidak</option>
                            </select>
                            
                     <!-- comment -->
                            <textarea class="form-control mt-2" name="commenttidakpeduli" id="commenttidakpeduli" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>  
                    
            <!-- Bagian Apakah Keluarga Melakukan Upaya Peningkatan Kesehatan yang dialami Anggota Keluarganya Secara Aktif -->
                 <div class="row mb-3">
                    <label for="upayakesehatan" class="col-sm-4 col-form-label"><strong>4. Apakah Keluarga Melakukan Upaya Upaya Peningkatan Kesehatan yang dialami Anggota Keluarganya Secara Aktif</strong></label> 
                    <div class="col-sm-7">
                    <select class="form-select" name="upayakesehatan">
                            <option value="">Pilih</option>
                            <option value="Ya">Ya</option>
                            <option value="Tidak">Tidak</option>
                            </select>
                            
                     <!-- comment -->
                            <textarea class="form-control mt-2" name="commentupayakesehatan" id="commentupayakesehatan" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 
                    
             <!-- Bagian Bila Tidak, Jelaskan -->

             <div class="row mb-3">
                <label for="bilatidakupayakesehatan" class="col-sm-4 col-form-label"><strong>Bila Tidak, Jelaskan</strong></label>
                    <div class="col-sm-7">
                       <textarea name="bilatidakupayakesehatan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentbilatidakupayakesehatan" id="commentbilatidakupayakesehatan" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>   
                    
            <!-- Bagian Apakah Keluarga Mengetahui Kebutuhan Pengobatan Masalah Kesehatan yang dialami Anggota Keluarganya -->
                 <div class="row mb-3">
                    <label for="kebutuhanpengobatan" class="col-sm-4 col-form-label"><strong>5. Apakah Keluarga Mengetahui Kebutuhan Pengobatan Masalah Kesehatan yang Dialami Anggota dalam Keluarganya</strong></label> 
                    <div class="col-sm-7">
                    <select class="form-select" name="kebutuhanpengobatan">
                            <option value="">Pilih</option>
                            <option value="Ya">Ya</option>
                            <option value="Tidak">Tidak</option>
                            </select>
                            
                     <!-- comment -->
                            <textarea class="form-control mt-2" name="commentkebutuhanpengobatan" id="commentkebutuhanpengobatan" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>  
                    
             <!-- Bagian Bila Tidak, Jelaskan -->

             <div class="row mb-3">
                <label for="bilatidakupayapengobatan" class="col-sm-4 col-form-label"><strong>Bila Tidak, Jelaskan</strong></label>
                    <div class="col-sm-7">
                       <textarea name="bilatidakupayapengobatan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentbilatidakupayapengobatan" id="commentbilatidakupayapengobatan" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>  
                    
             <!-- Bagian Merawat Anggota Keluarga yang Sakit -->
                    <div class="row mb-3">
                        <label class="col-sm-10 col-form-label text-primary">
                            <strong>c. Merawat Anggota Keluarga yang Sakit</strong>
                    </div> 
                    
            <!-- Bagian Apakah Keluarga Dapat Melakukan Cara Merawat Anggota Keluarga Dengan Masalah Kesehatan yang dialaminya -->
                 <div class="row mb-3">
                    <label for="caramerawat" class="col-sm-4 col-form-label"><strong>Apakah Keluarga Dapat Melakukan Cara Merawat Anggota Keluarga dengan Masalah Kesehatan yang dialaminya</strong></label> 
                    <div class="col-sm-7">
                    <select class="form-select" name="caramerawat">
                            <option value="">Pilih</option>
                            <option value="Ya">Ya</option>
                            <option value="Tidak">Tidak</option>
                            </select>
                            
                     <!-- comment -->
                            <textarea class="form-control mt-2" name="commentcaramerawat" id="commentcaramerawat" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>  
                    
             <!-- Bagian Bila Tidak, Jelaskan -->

             <div class="row mb-3">
                <label for="bilatidakcaramerawat" class="col-sm-4 col-form-label"><strong>Bila Tidak, Jelaskan</strong></label>
                    <div class="col-sm-7">
                       <textarea name="bilatidakcaramerawat" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentbilatidakcaramerawat" id="commentbilatidakcaramerawat" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>  

             <!-- Bagian Memodifikasi Lingkungan -->
                    <div class="row mb-3">
                        <label class="col-sm-10 col-form-label text-primary">
                            <strong>d. Memodifikasi Lingkungan</strong>
                    </div>         
                    
            <!-- Bagian Apakah Keluarga Dapat Melakukan Pencegahan Masalah Kesehatan yang dialami Anggota Keluarganya -->
                 <div class="row mb-3">
                    <label for="pencegahanmasalah" class="col-sm-4 col-form-label"><strong>1. Apakah Keluarga Dapat Melakukan Pencegahan Masalah Kesehatan yang Dialami Anggota dalam Keluarganya</strong></label> 
                    <div class="col-sm-7">
                    <select class="form-select" name="pencegahanmasalah">
                            <option value="">Pilih</option>
                            <option value="Ya">Ya</option>
                            <option value="Tidak">Tidak</option>
                            </select>
                            
                     <!-- comment -->
                            <textarea class="form-control mt-2" name="commentpencegahanmasalah" id="commentpencegahanmasalah" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>  
                    
             <!-- Bagian Bila Tidak, Jelaskan -->

             <div class="row mb-3">
                <label for="bilatidakpencegahanmasalah" class="col-sm-4 col-form-label"><strong>Bila Tidak, Jelaskan</strong></label>
                    <div class="col-sm-7">
                       <textarea name="bilatidakpencegahanmasalah" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentbilatidakpencegahanmasalah" id="commentbilatidakpencegahanmasalah" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>  

            <!-- Bagian Apakah Keluarga Mampu Memelihara atau Memodifikasi Lingkungan yang Mendukung Kesehatan Anggota Keluarga yang Mengalami Masalah Kesehatan -->
                 <div class="row mb-3">
                    <label for="memeliharalingkungan" class="col-sm-4 col-form-label"><strong>2. Apakah Keluarga Mampu Memelihara atau Memodifikasi Lingkungan yang Mendukung Kesehatan Anggota Keluarga yang Mengalami Masalah Kesehatan</strong></label> 
                    <div class="col-sm-7">
                    <select class="form-select" name="memeliharalingkungan">
                            <option value="">Pilih</option>
                            <option value="Ya">Ya</option>
                            <option value="Tidak">Tidak</option>
                            </select>
                            
                     <!-- comment -->
                            <textarea class="form-control mt-2" name="commentmemeliharalingkungan" id="commentmemeliharalingkungan" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>  
                    
             <!-- Bagian Bila Tidak, Jelaskan -->

             <div class="row mb-3">
                <label for="bilatidakmemeliharalingkungan" class="col-sm-4 col-form-label"><strong>Bila Tidak, Jelaskan</strong></label>
                    <div class="col-sm-7">
                       <textarea name="bilatidakmemeliharalingkungan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentbilatidakmemeliharalingkungan" id="commentbilatidakmemeliharalingkungan" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>          

             <!-- Bagain Memanfaatkan Fasilitas Kesehatan -->
                    <div class="row mb-3">
                        <label class="col-sm-10 col-form-label text-primary">
                            <strong>e. Memanfaatkan Fasilitas Kesehatan</strong>
                    </div> 
                    
            <!-- Bagian Apakah Keluarga Mampu Mengganggi dan Memanfaatkan Tenaga Kesehatan di Masyarakat untuk Mengatasi Masalah Kesehatan Anggota Keluarganya -->
                 <div class="row mb-3">
                    <label for="tenagakesehatan" class="col-sm-4 col-form-label"><strong>Apakah Keluarga Mampu Menggali dan Memanfaatkan Tenaga Kesehatan di Masyarakat untuk Mengatasi Masalah Kesehatan Anggota Keluarganya</strong></label> 
                    <div class="col-sm-7">
                    <select class="form-select" name="tenagakesehatan">
                            <option value="">Pilih</option>
                            <option value="Ya">Ya</option>
                            <option value="Tidak">Tidak</option>
                            </select>
                            
                     <!-- comment -->
                            <textarea class="form-control mt-2" name="commenttenagakesehatan" id="commenttenagakesehatan" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>  
                    
             <!-- Bagian Bila Tidak, Jelaskan -->

             <div class="row mb-3">
                <label for="bilatidaktenagakesehatan" class="col-sm-4 col-form-label"><strong>Bila Tidak, Jelaskan</strong></label>
                    <div class="col-sm-7">
                       <textarea name="bilatidaktenagakesehatan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentbilatidaktenagakesehatan" id="commentbilatidaktenagakesehatan" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>  

             <!-- Bagian Fungsi Religius -->
                    <div class="row mb-3">
                        <label class="col-sm-10 col-form-label text-primary">
                            <strong>6. Fungsi Religius</strong>
                    </div>    
                    
             <!-- Bagian Jenis Ibadah yang Dijalakan Keluarga -->

             <div class="row mb-3">
                <label for="jenisibadah" class="col-sm-2 col-form-label"><strong>Jenis Ibadah yang Dijalankan Keluarga</strong></label>
                    <div class="col-sm-9">
                       <input type="text" class="form-control" name="jenisibadah">

                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentjenisibadah" id="commentjenisibadah" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>   
                    
             <!-- Bagian Usia Anak diperkenalkan Tentang Ajaran Agama -->

             <div class="row mb-3">
                <label for="ajaranagama" class="col-sm-2 col-form-label"><strong>Usia Anak diperkenalkan Tentang Ajaran Agama</strong></label>
                    <div class="col-sm-9">
                       <input type="text" class="form-control" name="ajaranagama">

                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentajaranagama" id="commentajaranagama" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>  
                    
            <h5 class="card-title"><strong>VIII. Stress dan Koping Keluarga</strong></h5>
            
             <!-- Bagian Stressor Jangka Pendek dan Panjang -->
                    <div class="row mb-3">
                        <label class="col-sm-10 col-form-label">
                            <strong>1. Stressor Jangka Pendek dan Panjang</strong>
                    </div> 

             <!-- Bagian Masalah/Beban Pikiran Keluarga Saat Ini -->

             <div class="row mb-3">
                <label for="masalahbebankeluarga" class="col-sm-4 col-form-label"><strong>a. Masalah/Beban Pikiran Saat Ini</strong></label>
                    <div class="col-sm-7">
                       <textarea name="masalahbebankeluarga" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentmasalahbebankeluarga" id="commentmasalahbebankeluarga" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>  
                    
             <!-- Bagian Masalah/Beban Pikiran yang Sudah Lama dirasakan (Lebih Dari 6 Bulan) -->

             <div class="row mb-3">
                <label for="masalahbebankeluargalama" class="col-sm-4 col-form-label"><strong>b. Masalah/Beban Pikiran yang Sudah Lama dirasakan (Lebih Dadri 6 Bulan)</strong></label>
                    <div class="col-sm-7">
                       <textarea name="masalahbebankeluargalama" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentmasalahbebankeluargalama" id="commentmasalahbebankeluargalama" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 
                    
             <!-- Bagian Kemampuan/Tanggapan Keluarga Terhadap Stressor -->

             <div class="row mb-3">
                <label for="kemampuanterhadapstressor" class="col-sm-4 col-form-label"><strong>2. Kemampuan/Tanggapan Keluarga Terhadap Stressor</strong></label>
                    <div class="col-sm-7">
                       <textarea name="kemampuanterhadapstressor" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentekmampuanterhadapstressor" id="commentkemampuanterhadapstressor" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 
                    
             <!-- Bagian Strategi Koping yang digunakan -->
                <div class="row mb-3">
                        <label class="col-sm-10 col-form-label">
                            <strong>3. Strategi Koping yang digunakan</strong>
                    </div> 
                    
            <!-- Bagian Bercerita dengan Keluarga -->
                 <div class="row mb-3">
                    <label for="berceritadengankeluarga" class="col-sm-4 col-form-label"><strong>a. Bercerita dengen Keluarga</strong></label> 
                    <div class="col-sm-7">
                    <select class="form-select" name="berceritadengankeluarga">
                            <option value="">Pilih</option>
                            <option value="Ya">Ya</option>
                            <option value="Tidak">Tidak</option>
                            </select>
                            
                     <!-- comment -->
                            <textarea class="form-control mt-2" name="commentberceritadengankeluarga" id="commentberceritadengankeluarga" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>  
                    
            <!-- Bagian Menyelesaikan Sendiri -->
                 <div class="row mb-3">
                    <label for="menyelesaikansendiri" class="col-sm-4 col-form-label"><strong>b. Menyelesaikan Sendiri</strong></label> 
                    <div class="col-sm-7">
                    <select class="form-select" name="menyelesaikansendiri">
                            <option value="">Pilih</option>
                            <option value="Ya">Ya</option>
                            <option value="Tidak">Tidak</option>
                            </select>
                            
                     <!-- comment -->
                            <textarea class="form-control mt-2" name="commentmenyelesaikansendiri" id="commentmenyelesaikansendiri" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 
                    
            <!-- Bagian Meminta Tanggapan dari Teman yang dipercaya -->
                 <div class="row mb-3">
                    <label for="memintatanggapan" class="col-sm-4 col-form-label"><strong>c. Meminta Tanggapan dari Teman yang dipercaya</strong></label> 
                    <div class="col-sm-7">
                    <select class="form-select" name="memintatanggapan">
                            <option value="">Pilih</option>
                            <option value="Ya">Ya</option>
                            <option value="Tidak">Tidak</option>
                            </select>
                            
                     <!-- comment -->
                            <textarea class="form-control mt-2" name="commentmemintatanggapan" id="commentmemintatanggapan" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>    
                    
            <!-- Bagian Lebih Mendekatkan Diri Pada Tuhan Yang Maha Esa -->
                 <div class="row mb-3">
                    <label for="mendekatkandiri" class="col-sm-4 col-form-label"><strong>d. Lebih Mendekatkan Diri Pada Tuhan Yang Maha Esa</strong></label> 
                    <div class="col-sm-7">
                    <select class="form-select" name="mendekatkandiri">
                            <option value="">Pilih</option>
                            <option value="Ya">Ya</option>
                            <option value="Tidak">Tidak</option>
                            </select>
                            
                     <!-- comment -->
                            <textarea class="form-control mt-2" name="commentmendekatkandiri" id="commentmendekatkandiri" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>   
                    
             <!-- Bagian Strategi Adapatasi Disfungsional -->
                    <div class="row mb-3">
                        <label class="col-sm-10 col-form-label">
                            <strong>4. Strategi Adaptasi Fungsional</strong>
                    </div>  
                    
            <!-- Bagian Sering Marah -->
                 <div class="row mb-3">
                    <label for="seringmarah" class="col-sm-4 col-form-label"><strong>a. Sering Marah</strong></label> 
                    <div class="col-sm-7">
                    <select class="form-select" name="seringmarah">
                            <option value="">Pilih</option>
                            <option value="Ya">Ya</option>
                            <option value="Tidak">Tidak</option>
                            </select>
                            
                     <!-- comment -->
                            <textarea class="form-control mt-2" name="commentseringmarah" id="commentseringmarah" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>   
                    
            <!-- Bagian Mengalihkan ke Hal yang Negatif seperti Mengkonsumsi Minuman Alkohol -->
                 <div class="row mb-3">
                    <label for="halnegatif" class="col-sm-4 col-form-label"><strong>b. Mengalihakan he Hal Negatif seperti Mengkonsumsi Minuman Alkohol</strong></label> 
                    <div class="col-sm-7">
                    <select class="form-select" name="halnegatif">
                            <option value="">Pilih</option>
                            <option value="Ya">Ya</option>
                            <option value="Tidak">Tidak</option>
                            </select>
                            
                     <!-- comment -->
                            <textarea class="form-control mt-2" name="commenthalnegatif" id="commenthalnegatif" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>   
                    
            <!-- Bagian Mengalihkan Beban Pikiran dengan Merokok -->
                 <div class="row mb-3">
                    <label for="mengalihkanbebanpikiran" class="col-sm-4 col-form-label"><strong>c. Mengalihakn Beban Pikiran dengan Merokok</strong></label> 
                    <div class="col-sm-7">
                    <select class="form-select" name="mengalihkanbebanpikiran">
                            <option value="">Pilih</option>
                            <option value="Ya">Ya</option>
                            <option value="Tidak">Tidak</option>
                            </select>
                            
                     <!-- comment -->
                            <textarea class="form-control mt-2" name="commentmengalihkanbebanpikiran" id="commentmengalihkanbebanpikiran" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>               
                    
            <h5 class="card-title"><strong>IX. Pemeriksaan Fisik</strong></h5> 

            <!-- Bagian Pemeriksaan Fisik -->
                <div class="row mb-3">
                    <label for="pemeriksaanfisik" class="col-sm-2 col-form-label"><strong>Pemeriksaan Fisik</strong></label>
                    <div class="col-sm-9">

                        <!-- Link Google Drive -->
                         <div class="form-control d-flex justify-content-between align-items-center">
                            <span>Melampirkan format pengkajian anggota keluarga yang sakit pada link Google Drive yang tersedia</span>
                            <a href="<?= $kartukeluarga ?>" target="_blank" class="btn btn-sm btn-primary">Upload</a>
                        </div>

                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentpemeriksaanfisik" id="commentpemeriksaanfisik" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>

            <h5 class="card-title"><strong>X. Harapan Keluarga</strong></h5> 

             <!-- Bagian Harapan Keluarga Terhadap Kesehatannya -->

             <div class="row mb-3">
                <label for="harapankesehatan" class="col-sm-2 col-form-label"><strong>1. Harapan Terhadap Kesehatannya</strong></label>
                    <div class="col-sm-9">
                       <textarea name="harapankesehatan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentharapankesehatan" id="commentharapankesehatan" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>   

             <!-- Bagian Harapan Keluarga Terhadap Petugas Kesehatan -->

             <div class="row mb-3">
                <label for="harapanterhadappetugaskesehatan" class="col-sm-2 col-form-label"><strong>2. Harapan Keluarga Terhadap Petugas Kesehatan</strong></label>
                    <div class="col-sm-9">
                       <textarea name="harapanterhadappetugaskesehatan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentharapanterhadappetugaskesehatan" id="commentharapanterhadappetugaskesehatan" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>  

            <form method="POST">        
                    
            <h5 class="card-title"><strong>XI. Tingkat Kemandirian Keluarga</strong></h5> 

            <!-- Bagian Kunjungan Ke-->

                <div class="row mb-3">
                    <label for="kunjunganke" class="col-sm-2 col-form-label"><strong>Kunjungan Ke</strong></label>
                        <div class="col-sm-9">
                        <input type="text" class="form-control" name="kunjunganke">

                            <!-- comment -->
                                <textarea class="form-control mt-2" name="commentkunjunganke" id="commentkunjunganke" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                            </div>

                            <div class="col-sm-1 d-flex align-items-start">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" disabled>
                                </div>
                            </div>
                        </div> 

                <!-- Bagian Perawat -->

                    <div class="row mb-3">
                        <label for="perawat" class="col-sm-2 col-form-label"><strong>Perawat</strong></label>

                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="perawat" name="perawat">
                            
                             <!-- comment -->
                            <textarea class="form-control mt-2" name="commentperawat" id="commentperawat" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
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
                
                <!-- Bagian Kriteria Tingka Kemandirian Keluarga -->
                    <div class="row mb-3">
                        <label class="col-sm-10 col-form-label text-primary">
                            <strong>Kriteria Tingkat Kemandirian Keluarga</strong>
                        </label>
                    </div> 

                <!-- Bagian Keluarga Menerima Perawat -->

                    <div class="row mb-2" id="row1">
                        <div class="col-sm-7"><strong>1. Keluarga Menerima Perawat</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="menerimaperawat" value="ya" onclick="next(1, true)">
                            <label class="form-check-label">Ya</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="menerimaperawat" value="tidak" onclick="next(1, false)">
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>

            <!-- Bagian Keluarga Menerima Pelayanan Kesehatan Sesuai Rencana Keperawatan -->
                    <div class="row mb-2" id="row2">
                        <div class="col-sm-7"><strong>2. Keluarga Menerima Pelayanan Kesehatan Sesuai Rencana Keperawatan</strong>
                    </div>   
                    
                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="pelayanankesehatan" value="ya" onclick="next(2, true)">
                            <label class="form-check-label">Ya</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="pelayanankesehatan" value="tidak" onclick="next(2, false)">
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>
                
            <!-- Bagian Keluarga Tahu dan Dapat Mengungkapkan Masalah Kesehatan Secara Benar -->
                    <div class="row mb-2" id="row3">
                        <div class="col-sm-7"><strong>3. Keluarga Tahu dan Dapat Mengungkapkan Masalah Kesehatan Secara Benar</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="mengungkapkanmasalah" value="ya" onclick="next(3, true)">
                            <label class="form-check-label">Ya</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="mengungkapkanmasalah" value="tidak" onclick="next(3, false)">
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>  
                
            <!-- Bagian Keluarga Memanfaatkan Faskes Sesuai Anjuran -->
                    <div class="row mb-2" id="row4">
                        <div class="col-sm-7"><strong>4. Keluarga Memanfaatkan Faskes Sesuai Anjuran</strong>
                    </div>    

                   <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="faskes" value="ya" onclick="next(4, true)">
                            <label class="form-check-label">Ya</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="faskes" value="tidak" onclick="next(4, false)">
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>
                
            <!-- Bagian Keluarga Melaksanakan Tindakan Keperawatan Sederhana Sesuai Anjuran -->
                    <div class="row mb-2" id="row5">
                        <div class="col-sm-7"><strong>5. Keluarga Melaksanakan Tindakan Keperawatan Sederhana Sesuai Anjuran</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="tindakankeperawatan" value="ya" onclick="next(5, true)">
                            <label class="form-check-label">Ya</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="tindakankeperawatan" value="tidak" onclick="next(5, false)">
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>  
                
            <!-- Bagian Keluarga Melakukan Tindakan Pencegahan Secara Aktif -->
                    <div class="row mb-2" id="row6">
                        <div class="col-sm-7"><strong>6. Keluarga Melakukan Tindakan Pencegahan Secara Aktif</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="tindakanpencegahan" value="ya" onclick="next(6, true)">
                            <label class="form-check-label">Ya</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="tindakanpencegahan" value="tidak" onclick="next(6, false)">
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>
                
            <!-- Bagian Keluarga Melakukan Tindakan Promotif Secara Aktif -->
                    <div class="row mb-2" id="row7">
                        <div class="col-sm-7"><strong>7. Keluarga Melakukan Tindakan Promotif Secara Aktif</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="tindakanpromotif" value="ya" onclick="next(7, true)">
                            <label class="form-check-label">Ya</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="tindakanpromotif" value="tidak" onclick="next(7, false)">
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div> 
                
                <script>
                    function next(no, isYa) {
                        let nextRow = document.getElementById('row' + (no + 1));
                        for (let i = no + 1; 1<=7; i++) {
                            let radios = document.getElementByName([
                                'pelayanankesehatan',
                                'mengungkapkanmasalah',
                                'faskes',
                                'tindakankeperawatan',
                                'tindakanpencegahan',
                                'tindakanpromotif'
                            ][i-2]);

                            if (radios) {
                                radios.forEach(r => r.checked = false);
                            }
                        }

                        if (isYa) {
                            if (nextRow) nextRow.style.display = '';
                        } else {
                            for (let i = no + 1; i <=7; i++) {
                                let row = document.getElementById('row' + i)
                                if (row) row.style.display = 'none';
                            }
                        }
                    }

                    </script> 

                    <script>
                        window.onload = function () {
                            for (let i = 2; i <=7; i++) {
                                let row = document.getElementById('row' + i);
                                if (row) row.style.display = 'none';
                            }
                        }

                    </script>

                <!-- Bagian Button -->    
                    <div class="row mb-3">
                        <div class="col-sm-11 justify-content-end d-flex">
                            <button type="submit" name="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </div> 
                </form> 

                <?php
                    $hasilkriteria = "";
                    
                    if(isset($_POST['submit'])){
                        // Inputan
                        $kunjunganke = $_POST['kunjunganke'] ?? '';
                        $perawat_nama = $_POST['perawat'] ?? '';
                        $hasil = $_POST['hasil'] ?? '';

                        // Keterangan
                        $kriteriaList = [
                            1 => "Keluarga Menerima Perawat",
                            2 => "Keluarga Menerima Pelayanan Kesehatan Sesuai Rencana Keperawatan",
                            3 => "Keluarga Tahu dan Dapat Mengungkapkan Masalah Kesehatan Secara Benar",
                            4 => "Keluarga Memanfaatkan Faskes Sesuai Anjuran",
                            5 => "Keluarga Melaksanakan Tindakan Keperawatan Sederhana Sesuai Anjuran",
                            6 => "Keluarga Melakukan Tindakan Pencegahan Secara Aktif",
                            7 => "Keluarga Melakukan Tindakan Promotif Secara Aktif",
                        ];

                        //Jawaban Radio
                        $jawaban = [
                            1 => $_POST['menerimaperawat'];
                            2 => $_POST['pelayanankesehatan'];
                            3 => $_POST['mengungkapkanmasalah'];
                            4 => $_POST['faskes'];
                            5 => $_POST['tindakankeperawatan'];
                            6 => $_POST['tindakanpencegahan'];
                            7 => $_POST['tindakanpromotif'];
                        ];

                        //Jawaban 'Ya'
                        foreach ($jawaban as $no => $val) {
                            if($val == 'ya'){
                                $hasilKriteria .= $no . ". " . $kriteriaList[$no] . "<br>";
                            }
                        }

                    }
                ?>

                    <h5 class="card-title mt-2"><strong>Kemandirian Keluarga</strong></h5>

                    <style>
                    .table-kemandirian {
                        table-layout: fixed;
                        width:100%
                    }

                    .table-kemandirian td,
                    .table-kemandirian th {
                        word-wrap: break-word;
                        white-space: normal;
                        vertical-align: top;
                    }
                    </style>

                    <table class="table table-bordered" style="table-layout: fixed; width: 100%;">
                        <thead>
                            <tr>
                                <th class="text-center align-middle">Kunjungan Ke</th>
                                <th class="text-center align-middle">Perawat</th>
                                <th class="text-center align-middle">Hasil</th>
                                <th class="text-center align-middle">Kriteria Tingkat Kemandirian Keluarga</th>
                        </tr>
                        </thead>

                    <tbody>

                    <?php
                        if(isset($_POST['submit'])){
                            echo "<tr>
                            <td>$kunjunganke</td>
                            <td>$perawat_nama</td>
                            <td>$hasil</td>
                            <td>$hasilKriteria</td>
                            </tr>";
                        }

                        //Daftar Data Lama (Jika Ada)
                        if(!empty($data)){
                            foreach($data as $row){
                                echo "<tr>
                                <td>".$row['kunjunganke']."</td>
                                <td>".$row['perawat']."</td>
                                <td>".$row['hasil']."</td>
                                <td>".$row['kriteria']."</td>
                                </tr>";
                            }
                        }
                    ?>

                    </tbody>
                    </table>

            <h5 class="card-title"><strong>XII. Klasifikasi Data</strong></h5> 
                
            <!-- Bagian Data Kesehatan Keluarga-->

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Data Kesehatan Keluarga</strong></label>

                        <div class="col-sm-9">
                           <textarea name="datakesehatankeluarga" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" name="commentdatakesehatankeluarga" id="commentdatakesehatankeluarga" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 

                <!-- Bagian Data Penunjang -->

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Data Penunjang</strong></label>

                        <div class="col-sm-9">
                           <textarea name="datapenunjang" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" name="commentdatapenunjang" id="commentdatapenunjang" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
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

                    <h5 class="card-title mt-2"><strong>Klasifikasi Data</strong></h5>

                    <style>
                    .table-klasifikasi {
                        table-layout: fixed;
                        width:100%
                    }

                    .table-klasifikasi td,
                    .table-klasifikasi th {
                        word-wrap: break-word;
                        white-space: normal;
                        vertical-align: top;
                    }
                    </style>

                    <table class="table table-bordered" style="table-layout: fixed; width: 100%;">
                        <thead>
                            <tr>
                                <th class="text-center">Data Kesehatan Keluarga</th>
                                <th class="text-center">Data Penunjang</th>
                        </tr>
                        </thead>

                    <tbody>

                    <?php
                    if(!empty($data)){
                        foreach($data as $row){
                            echo "<tr>
                            <td>".$row['datakesehatankeluarga']."</td>
                            <td>".$row['datapenunjang']."</td>
                            </tr>";
                        }
                    }
                    ?>

                    </tbody>
                    </table>

                
                <?php include "tab_navigasi.php"; ?>

</section>
</main>