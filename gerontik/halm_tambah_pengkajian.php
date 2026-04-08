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
        <div class="card">
            <div class="card-body">
            
                <!-- General Form Elements -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

                <h5 class="card-title"><strong>A. PENGKAJIAN</strong></h5>
                <div class="row mb-2">
                        <label class="col-sm-2 col-form-label text-primary">
                            <strong>I. Identitas</strong>
                    </div>

                <!-- General Form Elements -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">
            
            <!-- Bagian Nama -->
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label"><strong>Nama</strong></label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" name="nama">
                   
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

            <!-- Bagian Tempat Lahir -->
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label"><strong>Tempat Lahir</strong></label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" name="tempat_lahir">
                    
                    <!-- comment -->
                            <textarea class="form-control mt-2" id="commenttempat_lahir" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>

            <!-- Bagian Tanggal Lahir -->
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label"><strong>Tanggal Lahir</strong></label>
                <div class="col-sm-9">
                    <input type="date" class="form-control" name="tgl_lahir" required>
                   
                    <!-- comment -->
                            <textarea class="form-control mt-2" id="commenttgl_lahir" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
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
                <label class="col-sm-2 col-form-label"><strong>Jenis Kelamin</strong></label>
                <div class="col-sm-9">
                    <select class="form-control" name="jenis_kelamin" required>
                        <option value="">Pilih</option>
                        <option value="Laki-laki">Laki-laki</option>
                        <option value="Perempuan">Perempuan</option>
                    </select>
                   
                    <!-- comment -->
                            <textarea class="form-control mt-2" id="commentjenis_kelamin" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
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
                <label class="col-sm-2 col-form-label"><strong>Status Perkawinan</strong></label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" name="status_perkawinan" required>
                    
                    <!-- comment -->
                            <textarea class="form-control mt-2" id="commentstatus_perkawinan" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
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
                <label class="col-sm-2 col-form-label"><strong>Agama</strong></label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" name="agama" required>
                    
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

            <!-- Bagian Pendidikan -->
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label"><strong>Pendidikan</strong></label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" name="pendidikan" required>
                    
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

            <!-- Bagian Pekerjaan Saat Ini -->
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label"><strong>Pekerjaan Saat Ini</strong></label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" name="pekerjaan_sekarang">
                    
                    <!-- comment -->
                            <textarea class="form-control mt-2" id="commentpekerjaan_sekarang" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>

            <!-- Bagian Pekerjaan Sebelumnya -->
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label"><strong>Pekerjaan Sebelumnya</strong></label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" name="pekerjaan_sebelumnya">
                    
                    <!-- comment -->
                            <textarea class="form-control mt-2" id="commentpekerjaan_sebelumnya" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>

            <!-- Bagian Tanggal Pengkajian -->
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label"><strong>Tanggal Pengkajian</strong></label>
                <div class="col-sm-9">
                    <input type="date" class="form-control" name="tgl_pengkajian" required>
                    
                    <!-- comment -->
                            <textarea class="form-control mt-2" id="commenttgl_pengkajian" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
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
                <label class="col-sm-2 col-form-label"><strong>Alamat</strong></label>
               <div class="col-sm-9">
                    <input type="text" class="form-control" name="tgl_pengkajian" required>
                    
                    <!-- comment -->
                            <textarea class="form-control mt-2" id="commenttgl_pengkajian" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
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
            
                <!-- General Form Elements -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

                <h5 class="card-title"><strong>II. Riwayat Kesehatan</strong></h5>

                <!-- General Form Elements -->
                <class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">
            
                <!-- Bagian Keluhan Utama -->
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><strong>Keluhan Utama</strong></label>
                    <div class="col-sm-9">
                        <textarea name="keluhan_utama" class="form-control" rows="4" placeholder=""></textarea>
                        
                        <!-- comment -->
                            <textarea class="form-control mt-2" id="commentkeluhan_utama" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>

                <!-- Bagian Riwayat Kesehatan Saat Ini -->
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><strong>Riwayat Kesehatan Saat Ini</strong></label>
                    <div class="col-sm-9">
                        <textarea name="riwayat_kesehatan_sekarang" class="form-control" rows="4" placeholder=""></textarea>
                        
                        <!-- comment -->
                            <textarea class="form-control mt-2" id="commentriwayat_kesehatan_sekarang" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>
                </class>
                </form>
                </div>
        </div>
        

<div class="card">
            <div class="card-body">
            
                <!-- General Form Elements -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

                <h5 class="card-title"><strong>III. Status Lanjut Usia</strong></h5>

            <!-- Berkualitas -->
            <div class="row mb-3 align-items-start">
                <label class="col-sm-2 col-form-label"><strong>Berkualitas</strong></label>
                <div class="col-sm-9">
                    <select class="form-control" name="berkualitas">
                        <option value="">-- Pilih --</option>
                        <option value="Ya">Ya</option>
                        <option value="Tidak">Tidak</option>
                    </select>
                    <textarea class="form-control mt-2" rows="2"
                        placeholder="Ketikkan revisi jika tidak di ACC"
                        style="resize:none;" readonly></textarea>
                </div>
                <div class="col-sm-1 d-flex align-items-start">
                    <input class="form-check-input mt-2" type="checkbox">
                </div>
            </div>

            <!-- Sehat -->
            <div class="row mb-3 align-items-start">
                <label class="col-sm-2 col-form-label"><strong>Sehat</strong></label>
                <div class="col-sm-9">
                    <select class="form-control" name="sehat">
                        <option value="">-- Pilih --</option>
                        <option>Ya</option>
                        <option>Tidak</option>
                    </select>
                    <textarea class="form-control mt-2" rows="2"
                        placeholder="Ketikkan revisi jika tidak di ACC"
                        style="resize:none;" readonly></textarea>
                </div>
                <div class="col-sm-1 d-flex align-items-start">
                    <input class="form-check-input mt-2" type="checkbox">
                </div>
            </div>

            <!-- Aktif -->
            <div class="row mb-3 align-items-start">
                <label class="col-sm-2 col-form-label"><strong>Aktif</strong></label>
                <div class="col-sm-9">
                    <select class="form-control" name="aktif">
                        <option value="">-- Pilih --</option>
                        <option>Ya</option>
                        <option>Tidak</option>
                    </select>
                    <textarea class="form-control mt-2" rows="2"
                        placeholder="Ketikkan revisi jika tidak di ACC"
                        style="resize:none;" readonly></textarea>
                </div>
                <div class="col-sm-1 d-flex align-items-start">
                    <input class="form-check-input mt-2" type="checkbox">
                </div>
            </div>

            <!-- Produktif -->
            <div class="row mb-3 align-items-start">
                <label class="col-sm-2 col-form-label"><strong>Produktif</strong></label>
                <div class="col-sm-9">
                    <select class="form-control" name="produktif">
                        <option value="">-- Pilih --</option>
                        <option>Ya</option>
                        <option>Tidak</option>
                    </select>
                    <textarea class="form-control mt-2" rows="2"
                        placeholder="Ketikkan revisi jika tidak di ACC"
                        style="resize:none;" readonly></textarea>
                </div>
                <div class="col-sm-1 d-flex align-items-start">
                    <input class="form-check-input mt-2" type="checkbox">
                </div>
            </div>

            <!-- Sakit dengan perawatan -->
            <div class="row mb-3 align-items-start">
                <label class="col-sm-2 col-form-label">
                    <strong>Sakit dengan perawatan</strong>
                </label>
                <div class="col-sm-9">
                    <select class="form-control" name="sakit_perawatan">
                        <option value="">-- Pilih --</option>
                        <option>Ya</option>
                        <option>Tidak</option>
                    </select>
                    <textarea class="form-control mt-2" rows="2"
                        placeholder="Ketikkan revisi jika tidak di ACC"
                        style="resize:none;" readonly></textarea>
                </div>
                <div class="col-sm-1 d-flex align-items-start">
                    <input class="form-check-input mt-2" type="checkbox">
                </div>
            </div>

            <!-- Sakit tanpa perawatan -->
            <div class="row mb-3 align-items-start">
                <label class="col-sm-2 col-form-label">
                    <strong>Sakit tanpa perawatan</strong>
                </label>
                <div class="col-sm-9">
                    <select class="form-control" name="sakit_tanpa_perawatan">
                        <option value="">-- Pilih --</option>
                        <option>Ya</option>
                        <option>Tidak</option>
                    </select>
                    <textarea class="form-control mt-2" rows="2"
                        placeholder="Ketikkan revisi jika tidak di ACC"
                        style="resize:none;" readonly></textarea>
                </div>
                <div class="col-sm-1 d-flex align-items-start">
                    <input class="form-check-input mt-2" type="checkbox">
                </div>
            </div>

        </div>
    </div>
</div>
<div class="card">
            <div class="card-body">
            
                <!-- General Form Elements -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

                <h5 class="card-title"><strong>IV. Riwayat Kesehatan Masa Lalu</strong></h5>



            <!-- Imunisasi -->
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">
                    <strong>Imunisasi</strong>
                </label>

                <div class="col-sm-9">
                    <input type="text" class="form-control" name="imunisasi">

                    <textarea class="form-control mt-2"
                        rows="2"
                        placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi tetap semangat!"
                        style="overflow:hidden; resize:none;"
                        readonly></textarea>
                </div>

                <div class="col-sm-1 d-flex align-items-start">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" disabled>
                    </div>
                </div>
            </div>


            <!-- Alergi Obat -->
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">
                    <strong>Alergi Obat</strong>
                </label>

                <div class="col-sm-9">
                    <input type="text" class="form-control" name="alergi_obat">

                    <textarea class="form-control mt-2"
                        rows="2"
                        placeholder="Kolom revisi dari dosen"
                        style="overflow:hidden; resize:none;"
                        readonly></textarea>
                </div>

                <div class="col-sm-1 d-flex align-items-start">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" disabled>
                    </div>
                </div>
            </div>


            <!-- Kecelakaan -->
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">
                    <strong>Kecelakaan</strong>
                </label>

                <div class="col-sm-9">
                    <input type="text" class="form-control" name="kecelakaan">

                    <textarea class="form-control mt-2"
                        rows="2"
                        placeholder="Kolom revisi dari dosen"
                        style="overflow:hidden; resize:none;"
                        readonly></textarea>
                </div>

                <div class="col-sm-1 d-flex align-items-start">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" disabled>
                    </div>
                </div>
            </div>


            <!-- Kebiasaan Merokok -->
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">
                    <strong>Kebiasaan Merokok</strong>
                </label>

                <div class="col-sm-9">
                    <input type="text" class="form-control" name="merokok">

                    <textarea class="form-control mt-2"
                        rows="2"
                        placeholder="Kolom revisi dari dosen"
                        style="overflow:hidden; resize:none;"
                        readonly></textarea>
                </div>

                <div class="col-sm-1 d-flex align-items-start">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" disabled>
                    </div>
                </div>
            </div>


            <!-- Dirawat di Rumah Sakit -->
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">
                    <strong>Dirawat di Rumah Sakit</strong>
                </label>

                <div class="col-sm-9">
                    <input type="text" class="form-control" name="dirawat_rs">

                    <textarea class="form-control mt-2"
                        rows="2"
                        placeholder="Kolom revisi dari dosen"
                        style="overflow:hidden; resize:none;"
                        readonly></textarea>
                </div>

                <div class="col-sm-1 d-flex align-items-start">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" disabled>
                    </div>
                </div>
            </div>


            <!-- Penyakit 1 Tahun Terakhir -->
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">
                    <strong>Penyakit 1 Tahun Terakhir</strong>
                </label>

                <div class="col-sm-9">
                    <input type="text" class="form-control" name="penyakit_1_tahun">

                    <textarea class="form-control mt-2"
                        rows="2"
                        placeholder="Kolom revisi dari dosen"
                        style="overflow:hidden; resize:none;"
                        readonly></textarea>
                </div>

                <div class="col-sm-1 d-flex align-items-start">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" disabled>
                    </div>
                </div>
            </div>


            <!-- Nama Obat 2 Minggu -->
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">
                    <strong>Nama Obat (2 Minggu Terakhir)</strong>
                </label>

                <div class="col-sm-9">
                    <input type="text" class="form-control" name="obat_2_minggu">

                    <textarea class="form-control mt-2"
                        rows="2"
                        placeholder="Kolom revisi dari dosen"
                        style="overflow:hidden; resize:none;"
                        readonly></textarea>
                </div>

                <div class="col-sm-1 d-flex align-items-start">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" disabled>
                    </div>
                </div>
            </div>


            <!-- Teratur Dikonsumsi -->
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">
                    <strong>Teratur Dikonsumsi</strong>
                </label>

                <div class="col-sm-9">
                    <select class="form-control" name="teratur_konsumsi">
                        <option value="">-- Pilih --</option>
                        <option>Ya</option>
                        <option>Tidak</option>
                    </select>

                    <textarea class="form-control mt-2"
                        rows="2"
                        placeholder="Kolom revisi dari dosen"
                        style="overflow:hidden; resize:none;"
                        readonly></textarea>
                </div>

                <div class="col-sm-1 d-flex align-items-start">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" disabled>
                    </div>
                </div>
            </div>


            <!-- Obat Diresepkan Dokter -->
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">
                    <strong>Obat Diresepkan Dokter</strong>
                </label>

                <div class="col-sm-9">
                    <select class="form-control" name="resep_dokter">
                        <option value="">-- Pilih --</option>
                        <option>Ya</option>
                        <option>Tidak</option>
                    </select>

                    <textarea class="form-control mt-2"
                        rows="2"
                        placeholder="Kolom revisi dari dosen"
                        style="overflow:hidden; resize:none;"
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
</div>

<div class="card">
            <div class="card-body">
            
                <!-- General Form Elements -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

                <h5 class="card-title"><strong>V. Riwayat Gerontik</strong></h5>

               <!-- Genogram -->
            <div class="row mb-3 align-items-start">
                <label class="col-sm-2 col-form-label"><strong>Genogram</strong></label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" name="genogram">
                    <textarea class="form-control mt-2" rows="2"
                        placeholder="Ketikkan revisi jika tidak di ACC"
                        style="resize:none;" readonly></textarea>
                </div>
                <div class="col-sm-1 d-flex align-items-start">
                    <input class="form-check-input mt-2" type="checkbox">
                </div>
            </div>

            <!-- Judul Keterangan -->
            <div class="mb-3">
                <h5><strong>Keterangan Penjelasan</strong></h5>
            </div>

            <!-- G1 -->
            <div class="row mb-3 align-items-start">
                <label class="col-sm-2 col-form-label"><strong>G1</strong></label>
                <div class="col-sm-9">
                    <textarea class="form-control" name="G1" rows="3"></textarea>
                    <textarea class="form-control mt-2" rows="2"
                        placeholder="Ketikkan revisi jika tidak di ACC"
                        style="resize:none;" readonly></textarea>
                </div>
                <div class="col-sm-1 d-flex align-items-start">
                    <input class="form-check-input mt-2" type="checkbox">
                </div>
            </div>

            <!-- G2 -->
            <div class="row mb-3 align-items-start">
                <label class="col-sm-2 col-form-label"><strong>G2</strong></label>
                <div class="col-sm-9">
                    <textarea class="form-control" name="G2" rows="3"></textarea>
                    <textarea class="form-control mt-2" rows="2"
                        placeholder="Ketikkan revisi jika tidak di ACC"
                        style="resize:none;" readonly></textarea>
                </div>
                <div class="col-sm-1 d-flex align-items-start">
                    <input class="form-check-input mt-2" type="checkbox">
                </div>
            </div>

            <!-- G3 -->
            <div class="row mb-3 align-items-start">
                <label class="col-sm-2 col-form-label"><strong>G3</strong></label>
                <div class="col-sm-9">
                    <textarea class="form-control" name="G3" rows="3"></textarea>
                    <textarea class="form-control mt-2" rows="2"
                        placeholder="Ketikkan revisi jika tidak di ACC"
                        style="resize:none;" readonly></textarea>
                </div>
                <div class="col-sm-1 d-flex align-items-start">
                    <input class="form-check-input mt-2" type="checkbox">
                </div>
            </div>

        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">

        <form class="needs-validation" novalidate method="POST" enctype="multipart/form-data">

            <h5 class="card-title"><strong>VI. Pemeriksaan Fisik</strong></h5>

            <div class="row mb-2">
                <label class="col-sm-12 col-form-label text-primary">
                    <strong>Tanda-tanda Vital</strong>
                </label>
            </div>

            <!-- TD -->
            <div class="row mb-3 align-items-start">
                <label class="col-sm-2 col-form-label"><strong>TD (Tekanan Darah)</strong></label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" name="td" placeholder="Masukkan TD">
                    <textarea class="form-control mt-2" rows="2"
                        placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                </div>
                <div class="col-sm-1">
                    <input class="form-check-input mt-2" type="checkbox">
                </div>
            </div>

            <!-- Nadi -->
            <div class="row mb-3 align-items-start">
                <label class="col-sm-2 col-form-label"><strong>N (Nadi)</strong></label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" name="nadi" placeholder="Masukkan Nadi">
                    <textarea class="form-control mt-2" rows="2"
                        placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                </div>
                <div class="col-sm-1">
                    <input class="form-check-input mt-2" type="checkbox">
                </div>
            </div>

            <!-- RR -->
            <div class="row mb-3 align-items-start">
                <label class="col-sm-2 col-form-label"><strong>RR (Frekuensi Pernafasan)</strong></label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" name="rr" placeholder="Masukkan RR">
                    <textarea class="form-control mt-2" rows="2"
                        placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                </div>
                <div class="col-sm-1">
                    <input class="form-check-input mt-2" type="checkbox">
                </div>
            </div>

            <!-- Suhu -->
            <div class="row mb-3 align-items-start">
                <label class="col-sm-2 col-form-label"><strong>Suhu (Celsius)</strong></label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" name="suhu" placeholder="Masukkan Suhu">
                    <textarea class="form-control mt-2" rows="2"
                        placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                </div>
                <div class="col-sm-1">
                    <input class="form-check-input mt-2" type="checkbox">
                </div>
            </div>

            <!-- Kesadaran -->
            <div class="row mb-3 align-items-start">
                <label class="col-sm-2 col-form-label"><strong>Tingkat Kesadaran</strong></label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" name="tingkat_kesadaran"
                        placeholder="Masukkan tingkat kesadaran">
                    <textarea class="form-control mt-2" rows="2"
                        placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                </div>
                <div class="col-sm-1">
                    <input class="form-check-input mt-2" type="checkbox">
                </div>
            </div>


            <h5 class="card-title mt-4"><strong>Pengkajian Head to Toe</strong></h5>

            <div class="row mb-2">
                <label class="col-sm-12 col-form-label text-primary">
                    <strong>1. Kepala</strong>
                </label>
            </div>

            <!-- Sakit Kepala -->
            <div class="row mb-3 align-items-start">
                <label class="col-sm-2 col-form-label"><strong>Sakit Kepala</strong></label>
                <div class="col-sm-9">
                    <select class="form-select" name="sakit_kepala">
                        <option value="">-- Pilih --</option>
                        <option value="Y">Ya</option>
                        <option value="T">Tidak</option>
                    </select>
                    <textarea class="form-control mt-2" rows="2"
                        placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                </div>
                <div class="col-sm-1">
                    <input class="form-check-input mt-2" type="checkbox">
                </div>
            </div>

            <!-- Trauma Kepala -->
            <div class="row mb-3 align-items-start">
                <label class="col-sm-2 col-form-label"><strong>Trauma Kepala di Masa Lalu</strong></label>
                <div class="col-sm-9">
                    <select class="form-select" name="trauma_kepala_masa_lalu">
                        <option value="">-- Pilih --</option>
                        <option value="Y">Ya</option>
                        <option value="T">Tidak</option>
                    </select>
                    <textarea class="form-control mt-2" rows="2"
                        placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                </div>
                <div class="col-sm-1">
                    <input class="form-check-input mt-2" type="checkbox">
                </div>
            </div>
            <!-- Pusing -->
            <div class="row mb-3 align-items-start">
                <label class="col-sm-2 col-form-label"><strong>Pusing</strong></label>
                <div class="col-sm-9">
                    <select class="form-select" name="pusing">
                        <option value="">-- Pilih --</option>
                        <option value="Y">Ya</option>
                        <option value="T">Tidak</option>
                    </select>
                    <textarea class="form-control mt-2" rows="2"
                        placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                </div>
                <div class="col-sm-1">
                    <input class="form-check-input mt-2" type="checkbox">
                </div>
            </div> <!-- INI YANG KURANG -->

            <!-- 2. Mata -->
            <div class="row mb-2">
                <label class="col-sm-12 col-form-label text-primary">
                    <strong>2. Mata</strong>
                </label>
            </div>

            <div class="row mb-3 align-items-start">
                <label class="col-sm-2 col-form-label"><strong>Penurunan Penglihatan</strong></label>
                <div class="col-sm-9">
                    <select class="form-select" name="penurunan_penglihatan" required>
                        <option value="">-- Pilih --</option>
                        <option value="Y">Ya</option>
                        <option value="T">Tidak</option>
                    </select>
                    <textarea class="form-control mt-2" rows="2"
                        placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                </div>
                <div class="col-sm-1">
                    <input class="form-check-input mt-2" type="checkbox">
                </div>
            </div>

            <div class="row mb-3 align-items-start">
                <label class="col-sm-2 col-form-label"><strong>Penglihatan Kabur</strong></label>
                <div class="col-sm-9">
                    <select class="form-select" name="penglihatan_kabur" required>
                        <option value="">-- Pilih --</option>
                        <option value="Y">Ya</option>
                        <option value="T">Tidak</option>
                    </select>
                    <textarea class="form-control mt-2" rows="2" placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                </div>
                <div class="col-sm-1">
                    <input class="form-check-input mt-2" type="checkbox">
                </div>
            </div>

            <div class="row mb-3 align-items-start">
                <label class="col-sm-2 col-form-label"><strong>Kekeruhan Lensa</strong></label>
                <div class="col-sm-9">
                    <select class="form-select" name="kekeruhan_lensa" required>
                        <option value="">-- Pilih --</option>
                        <option value="Y">Ya</option>
                        <option value="T">Tidak</option>
                    </select>
                    <textarea class="form-control mt-2" rows="2" placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                </div>
                <div class="col-sm-1">
                    <input class="form-check-input mt-2" type="checkbox">
                </div>
            </div>

            <div class="row mb-3 align-items-start">
                <label class="col-sm-2 col-form-label"><strong>Menggunakan Kacamata / Lensa Kontak</strong></label>
                <div class="col-sm-9">
                    <select class="form-select" name="kacamata_lensa_kontak" required>
                        <option value="">-- Pilih --</option>
                        <option value="Y">Ya</option>
                        <option value="T">Tidak</option>
                    </select>
                    <textarea class="form-control mt-2" rows="2" placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                </div>
                <div class="col-sm-1">
                    <input class="form-check-input mt-2" type="checkbox">
                </div>
            </div>

            <div class="row mb-3 align-items-start">
                <label class="col-sm-2 col-form-label"><strong>Nyeri</strong></label>
                <div class="col-sm-9">
                    <select class="form-select" name="nyeri_mata" required>
                        <option value="">-- Pilih --</option>
                        <option value="Y">Ya</option>
                        <option value="T">Tidak</option>
                    </select>
                    <textarea class="form-control mt-2" rows="2" placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                </div>
                <div class="col-sm-1">
                    <input class="form-check-input mt-2" type="checkbox">
                </div>
            </div>

            <div class="row mb-3 align-items-start">
                <label class="col-sm-2 col-form-label"><strong>Pruritus (Gatal)</strong></label>
                <div class="col-sm-9">
                    <select class="form-select" name="pruritus" required>
                        <option value="">-- Pilih --</option>
                        <option value="Y">Ya</option>
                        <option value="T">Tidak</option>
                    </select>
                    <textarea class="form-control mt-2" rows="2" placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                </div>
                <div class="col-sm-1">
                    <input class="form-check-input mt-2" type="checkbox">
                </div>
            </div>

            <div class="row mb-3 align-items-start">
                <label class="col-sm-2 col-form-label"><strong>Bengkak Sekitar Mata</strong></label>
                <div class="col-sm-9">
                    <select class="form-select" name="bengkak_mata" required>
                        <option value="">-- Pilih --</option>
                        <option value="Y">Ya</option>
                        <option value="T">Tidak</option>
                    </select>
                    <textarea class="form-control mt-2" rows="2" placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                </div>
                <div class="col-sm-1">
                    <input class="form-check-input mt-2" type="checkbox">
                </div>
            </div>

            <div class="row mb-3 align-items-start">
                <label class="col-sm-2 col-form-label"><strong>Floater</strong></label>
                <div class="col-sm-9">
                    <select class="form-select" name="floater" required>
                        <option value="">-- Pilih --</option>
                        <option value="Y">Ya</option>
                        <option value="T">Tidak</option>
                    </select>
                    <textarea class="form-control mt-2" rows="2" placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                </div>
                <div class="col-sm-1">
                    <input class="form-check-input mt-2" type="checkbox">
                </div>
            </div>

            <div class="row mb-3 align-items-start">
                <label class="col-sm-2 col-form-label"><strong>Diplopia</strong></label>
                <div class="col-sm-9">
                    <select class="form-select" name="diplopia" required>
                        <option value="">-- Pilih --</option>
                        <option value="Y">Ya</option>
                        <option value="T">Tidak</option>
                    </select>
                    <textarea class="form-control mt-2" rows="2" placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                </div>
                <div class="col-sm-1">
                    <input class="form-check-input mt-2" type="checkbox">
                </div>
            </div>

            <!-- 3. Telinga -->
            <div class="row mb-2 mt-4">
                <label class="col-sm-12 col-form-label text-primary">
                    <strong>3. Telinga</strong>
                </label>
            </div>

            <div class="row mb-3 align-items-start">
                <label class="col-sm-2 col-form-label"><strong>Penurunan Pendengaran</strong></label>
                <div class="col-sm-9">
                    <select class="form-select" name="penurunan_pendengaran" required>
                        <option value="">-- Pilih --</option>
                        <option value="Y">Ya</option>
                        <option value="T">Tidak</option>
                    </select>
                    <textarea class="form-control mt-2" rows="2" placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                </div>
                <div class="col-sm-1">
                    <input class="form-check-input mt-2" type="checkbox">
                </div>
            </div>

            <div class="row mb-3 align-items-start">
                <label class="col-sm-2 col-form-label"><strong>Menggunakan Alat Bantu Pendengaran</strong></label>
                <div class="col-sm-9">
                    <select class="form-select" name="alat_bantu_pendengaran" required>
                        <option value="">-- Pilih --</option>
                        <option value="Y">Ya</option>
                        <option value="T">Tidak</option>
                    </select>
                    <textarea class="form-control mt-2" rows="2" placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                </div>
                <div class="col-sm-1">
                    <input class="form-check-input mt-2" type="checkbox">
                </div>
            </div><div class="row mb-2">
                <label class="col-sm-12 col-form-label text-primary">
                    <strong>4. Hidung dan Sinus</strong>
                </label>
            </div>

            <!-- Rinorea -->
            <div class="row mb-3 align-items-start">
                <label class="col-sm-2 col-form-label"><strong>Rinorea</strong></label>
                <div class="col-sm-9">
                    <select class="form-select" name="rinorea">
                        <option value="">-- Pilih --</option>
                        <option value="Y">Ya</option>
                        <option value="T">Tidak</option>
                    </select>
                    <textarea class="form-control mt-2" rows="2"
                        placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                </div>
                <div class="col-sm-1">
                    <input class="form-check-input mt-2" type="checkbox">
                </div>
            </div>

            <!-- Rabas -->
            <div class="row mb-3 align-items-start">
                <label class="col-sm-2 col-form-label"><strong>Rabas</strong></label>
                <div class="col-sm-9">
                    <select class="form-select" name="rabas_hidung">
                        <option value="">-- Pilih --</option>
                        <option value="Y">Ya</option>
                        <option value="T">Tidak</option>
                    </select>
                    <textarea class="form-control mt-2" rows="2"
                        placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                </div>
                <div class="col-sm-1">
                    <input class="form-check-input mt-2" type="checkbox">
                </div>
            </div>

            <!-- Riwayat Epitaksis -->
            <div class="row mb-3 align-items-start">
                <label class="col-sm-2 col-form-label"><strong>Riwayat Epitaksis</strong></label>
                <div class="col-sm-9">
                    <select class="form-select" name="riwayat_epitaksis">
                        <option value="">-- Pilih --</option>
                        <option value="Y">Ya</option>
                        <option value="T">Tidak</option>
                    </select>
                    <textarea class="form-control mt-2" rows="2"
                        placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                </div>
                <div class="col-sm-1">
                    <input class="form-check-input mt-2" type="checkbox">
                </div>
            </div>

            <!-- Mendengkur -->
            <div class="row mb-3 align-items-start">
                <label class="col-sm-2 col-form-label"><strong>Mendengkur Bila Tidur</strong></label>
                <div class="col-sm-9">
                    <select class="form-select" name="mendengkur_tidur">
                        <option value="">-- Pilih --</option>
                        <option value="Y">Ya</option>
                        <option value="T">Tidak</option>
                    </select>
                    <textarea class="form-control mt-2" rows="2"
                        placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                </div>
                <div class="col-sm-1">
                    <input class="form-check-input mt-2" type="checkbox">
                </div>
            </div>

            <!-- Nyeri Sinus -->
            <div class="row mb-3 align-items-start">
                <label class="col-sm-2 col-form-label"><strong>Nyeri Pada Sinus</strong></label>
                <div class="col-sm-9">
                    <select class="form-select" name="nyeri_sinus">
                        <option value="">-- Pilih --</option>
                        <option value="Y">Ya</option>
                        <option value="T">Tidak</option>
                    </select>
                    <textarea class="form-control mt-2" rows="2"
                        placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                </div>
                <div class="col-sm-1">
                    <input class="form-check-input mt-2" type="checkbox">
                </div>
            </div>

            <!-- Pernapasan Cuping Hidung -->
            <div class="row mb-3 align-items-start">
                <label class="col-sm-2 col-form-label"><strong>Pernapasan Cuping Hidung</strong></label>
                <div class="col-sm-9">
                    <select class="form-select" name="pernapasan_cuping_hidung">
                        <option value="">-- Pilih --</option>
                        <option value="Y">Ya</option>
                        <option value="T">Tidak</option>
                    </select>
                    <textarea class="form-control mt-2" rows="2"
                        placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                </div>
                <div class="col-sm-1">
                    <input class="form-check-input mt-2" type="checkbox">
                </div>
            </div>
                         <div class="row mb-2">
                    <label class="col-sm-12 col-form-label text-primary">
                        <strong>5. Mulut dan Tenggorokan</strong>
                    </label>
                </div>

                <!-- Sakit Tenggorokan -->
                <div class="row mb-3 align-items-start">
                    <label class="col-sm-2 col-form-label"><strong>Sakit Tenggorokan</strong></label>
                    <div class="col-sm-9">
                        <select class="form-select" name="sakit_tenggorokan">
                            <option value="">-- Pilih --</option>
                            <option value="Y">Ya</option>
                            <option value="T">Tidak</option>
                        </select>
                        <textarea class="form-control mt-2" rows="2" placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                    </div>
                    <div class="col-sm-1">
                        <input class="form-check-input mt-2" type="checkbox">
                    </div>
                </div>

                <!-- Lesi / Ulkus -->
                <div class="row mb-3 align-items-start">
                    <label class="col-sm-2 col-form-label"><strong>Lesi / Ulkus</strong></label>
                    <div class="col-sm-9">
                        <select class="form-select" name="lesi_ulkus">
                            <option value="">-- Pilih --</option>
                            <option value="Y">Ya</option>
                            <option value="T">Tidak</option>
                        </select>
                        <textarea class="form-control mt-2" rows="2" placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                    </div>
                    <div class="col-sm-1">
                        <input class="form-check-input mt-2" type="checkbox">
                    </div>
                </div>

                <!-- Suara Serak -->
                <div class="row mb-3 align-items-start">
                    <label class="col-sm-2 col-form-label"><strong>Suara Serak</strong></label>
                    <div class="col-sm-9">
                        <select class="form-select" name="suara_serak">
                            <option value="">-- Pilih --</option>
                            <option value="Y">Ya</option>
                            <option value="T">Tidak</option>
                        </select>
                        <textarea class="form-control mt-2" rows="2" placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                    </div>
                    <div class="col-sm-1">
                        <input class="form-check-input mt-2" type="checkbox">
                    </div>
                </div>

                <!-- Perubahan Suara -->
                <div class="row mb-3 align-items-start">
                    <label class="col-sm-2 col-form-label"><strong>Perubahan Suara</strong></label>
                    <div class="col-sm-9">
                        <select class="form-select" name="perubahan_suara">
                            <option value="">-- Pilih --</option>
                            <option value="Y">Ya</option>
                            <option value="T">Tidak</option>
                        </select>
                        <textarea class="form-control mt-2" rows="2" placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                    </div>
                    <div class="col-sm-1">
                        <input class="form-check-input mt-2" type="checkbox">
                    </div>
                </div>

                <!-- Kesulitan Menelan -->
                <div class="row mb-3 align-items-start">
                    <label class="col-sm-2 col-form-label"><strong>Kesulitan Menelan</strong></label>
                    <div class="col-sm-9">
                        <select class="form-select" name="kesulitan_menelan">
                            <option value="">-- Pilih --</option>
                            <option value="Y">Ya</option>
                            <option value="T">Tidak</option>
                        </select>
                        <textarea class="form-control mt-2" rows="2" placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                    </div>
                    <div class="col-sm-1">
                        <input class="form-check-input mt-2" type="checkbox">
                    </div>
                </div>

                <!-- Perdarahan Gusi -->
                <div class="row mb-3 align-items-start">
                    <label class="col-sm-2 col-form-label"><strong>Perdarahan Gusi</strong></label>
                    <div class="col-sm-9">
                        <select class="form-select" name="perdarahan_gusi">
                            <option value="">-- Pilih --</option>
                            <option value="Y">Ya</option>
                            <option value="T">Tidak</option>
                        </select>
                        <textarea class="form-control mt-2" rows="2" placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                    </div>
                    <div class="col-sm-1">
                        <input class="form-check-input mt-2" type="checkbox">
                    </div>
                </div>

                <!-- Karies -->
                <div class="row mb-3 align-items-start">
                    <label class="col-sm-2 col-form-label"><strong>Karies</strong></label>
                    <div class="col-sm-9">
                        <select class="form-select" name="karies">
                            <option value="">-- Pilih --</option>
                            <option value="Y">Ya</option>
                            <option value="T">Tidak</option>
                        </select>
                        <textarea class="form-control mt-2" rows="2" placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                    </div>
                    <div class="col-sm-1">
                        <input class="form-check-input mt-2" type="checkbox">
                    </div>
                </div>

                <!-- Gigi Bersih -->
                <div class="row mb-3 align-items-start">
                    <label class="col-sm-2 col-form-label"><strong>Gigi Bersih</strong></label>
                    <div class="col-sm-9">
                        <select class="form-select" name="gigi_bersih">
                            <option value="">-- Pilih --</option>
                            <option value="Y">Ya</option>
                            <option value="T">Tidak</option>
                        </select>
                        <textarea class="form-control mt-2" rows="2" placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                    </div>
                    <div class="col-sm-1">
                        <input class="form-check-input mt-2" type="checkbox">
                    </div>
                </div>

                <!-- Menggunakan Gigi Palsu -->
                <div class="row mb-3 align-items-start">
                    <label class="col-sm-2 col-form-label"><strong>Menggunakan Gigi Palsu</strong></label>
                    <div class="col-sm-9">
                        <select class="form-select" name="gigi_palsu">
                            <option value="">-- Pilih --</option>
                            <option value="Y">Ya</option>
                            <option value="T">Tidak</option>
                        </select>
                        <textarea class="form-control mt-2" rows="2" placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                    </div>
                    <div class="col-sm-1">
                        <input class="form-check-input mt-2" type="checkbox">
                    </div>
                </div>

                <!-- Rutin Menggosok Gigi -->
                <div class="row mb-3 align-items-start">
                    <label class="col-sm-2 col-form-label"><strong>Rutin Menggosok Gigi</strong></label>
                    <div class="col-sm-9">
                        <select class="form-select" name="rutin_menggosok_gigi">
                            <option value="">-- Pilih --</option>
                            <option value="Y">Ya</option>
                            <option value="T">Tidak</option>
                        </select>
                        <textarea class="form-control mt-2" rows="2" placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                    </div>
                    <div class="col-sm-1">
                        <input class="form-check-input mt-2" type="checkbox">
                    </div>
                </div>
                                                <div class="row mb-2">
                    <label class="col-sm-12 col-form-label text-primary">
                        <strong>6. Leher</strong>
                    </label>
                </div>

                <!-- Kekakuan -->
                <div class="row mb-3 align-items-start">
                    <label class="col-sm-2 col-form-label"><strong>Kekakuan</strong></label>
                    <div class="col-sm-9">
                        <select class="form-select" name="kekakuan_leher">
                            <option value="">-- Pilih --</option>
                            <option value="Y">Ya</option>
                            <option value="T">Tidak</option>
                        </select>
                        <textarea class="form-control mt-2" rows="2"
                            placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                    </div>
                    <div class="col-sm-1">
                        <input class="form-check-input mt-2" type="checkbox">
                    </div>
                </div>

                <!-- Nyeri -->
                <div class="row mb-3 align-items-start">
                    <label class="col-sm-2 col-form-label"><strong>Nyeri</strong></label>
                    <div class="col-sm-9">
                        <select class="form-select" name="nyeri_leher">
                            <option value="">-- Pilih --</option>
                            <option value="Y">Ya</option>
                            <option value="T">Tidak</option>
                        </select>
                        <textarea class="form-control mt-2" rows="2"
                            placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                    </div>
                    <div class="col-sm-1">
                        <input class="form-check-input mt-2" type="checkbox">
                    </div>
                </div>

                <!-- Benjolan -->
                <div class="row mb-3 align-items-start">
                    <label class="col-sm-2 col-form-label"><strong>Benjolan</strong></label>
                    <div class="col-sm-9">
                        <select class="form-select" name="benjolan_leher">
                            <option value="">-- Pilih --</option>
                            <option value="Y">Ya</option>
                            <option value="T">Tidak</option>
                        </select>
                        <textarea class="form-control mt-2" rows="2"
                            placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                    </div>
                    <div class="col-sm-1">
                        <input class="form-check-input mt-2" type="checkbox">
                    </div>
                </div>

                <!-- Keterbatasan Gerak -->
                <div class="row mb-3 align-items-start">
                    <label class="col-sm-2 col-form-label"><strong>Keterbatasan Gerak</strong></label>
                    <div class="col-sm-9">
                        <select class="form-select" name="keterbatasan_gerak_leher">
                            <option value="">-- Pilih --</option>
                            <option value="Y">Ya</option>
                            <option value="T">Tidak</option>
                        </select>
                        <textarea class="form-control mt-2" rows="2"
                            placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                    </div>
                    <div class="col-sm-1">
                        <input class="form-check-input mt-2" type="checkbox">
                    </div>
                </div>

                                            <div class="row mb-2">
                    <label class="col-sm-12 col-form-label text-primary">
                        <strong>7. Pernapasan</strong>
                    </label>
                </div>

                <!-- Batuk -->
                <div class="row mb-3 align-items-start">
                    <label class="col-sm-2 col-form-label"><strong>Batuk</strong></label>
                    <div class="col-sm-9">
                        <select class="form-select" name="batuk">
                            <option value="">-- Pilih --</option>
                            <option value="Y">Ya</option>
                            <option value="T">Tidak</option>
                        </select>
                        <textarea class="form-control mt-2" rows="2"
                            placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                    </div>
                    <div class="col-sm-1">
                        <input class="form-check-input mt-2" type="checkbox">
                    </div>
                </div>

                <!-- Sesak Napas -->
                <div class="row mb-3 align-items-start">
                    <label class="col-sm-2 col-form-label"><strong>Sesak Napas</strong></label>
                    <div class="col-sm-9">
                        <select class="form-select" name="sesak_napas">
                            <option value="">-- Pilih --</option>
                            <option value="Y">Ya</option>
                            <option value="T">Tidak</option>
                        </select>
                        <textarea class="form-control mt-2" rows="2"
                            placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                    </div>
                    <div class="col-sm-1">
                        <input class="form-check-input mt-2" type="checkbox">
                    </div>
                </div>

                <!-- Hemoptomisis -->
                <div class="row mb-3 align-items-start">
                    <label class="col-sm-2 col-form-label"><strong>Hemoptomisis</strong></label>
                    <div class="col-sm-9">
                        <select class="form-select" name="hemoptomisis">
                            <option value="">-- Pilih --</option>
                            <option value="Y">Ya</option>
                            <option value="T">Tidak</option>
                        </select>
                        <textarea class="form-control mt-2" rows="2"
                            placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                    </div>
                    <div class="col-sm-1">
                        <input class="form-check-input mt-2" type="checkbox">
                    </div>
                </div>

                <!-- Sputum -->
                <div class="row mb-3 align-items-start">
                    <label class="col-sm-2 col-form-label"><strong>Sputum</strong></label>
                    <div class="col-sm-9">
                        <select class="form-select" name="sputum">
                            <option value="">-- Pilih --</option>
                            <option value="Y">Ya</option>
                            <option value="T">Tidak</option>
                        </select>
                        <textarea class="form-control mt-2" rows="2"
                            placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                    </div>
                    <div class="col-sm-1">
                        <input class="form-check-input mt-2" type="checkbox">
                    </div>
                </div>

                <!-- Riwayat Asma -->
                <div class="row mb-3 align-items-start">
                    <label class="col-sm-2 col-form-label"><strong>Riwayat Asma</strong></label>
                    <div class="col-sm-9">
                        <select class="form-select" name="riwayat_asma">
                            <option value="">-- Pilih --</option>
                            <option value="Y">Ya</option>
                            <option value="T">Tidak</option>
                        </select>
                        <textarea class="form-control mt-2" rows="2"
                            placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                    </div>
                    <div class="col-sm-1">
                        <input class="form-check-input mt-2" type="checkbox">
                    </div>
                </div>

                <!-- Kesulitan Menarik Napas -->
                <div class="row mb-3 align-items-start">
                    <label class="col-sm-2 col-form-label"><strong>Kesulitan Dalam Menarik Napas</strong></label>
                    <div class="col-sm-9">
                        <select class="form-select" name="kesulitan_menarik_napas">
                            <option value="">-- Pilih --</option>
                            <option value="Y">Ya</option>
                            <option value="T">Tidak</option>
                        </select>
                        <textarea class="form-control mt-2" rows="2"
                            placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                    </div>
                    <div class="col-sm-1">
                        <input class="form-check-input mt-2" type="checkbox">
                    </div>
                </div>

                <div class="row mb-2">
    <label class="col-sm-12 col-form-label text-primary">
        <strong>8. Kardiovaskuler</strong>
    </label>
</div>

<!-- Nyeri Dada -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Nyeri / Ketidaknyamanan Dada</strong></label>
    <div class="col-sm-9">
        <select class="form-select" name="nyeri_dada">
            <option value="">-- Pilih --</option>
            <option value="Y">Ya</option>
            <option value="T">Tidak</option>
        </select>
        <textarea class="form-control mt-2" rows="2"
        placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
    </div>
    <div class="col-sm-1">
        <input class="form-check-input mt-2" type="checkbox">
    </div>
</div>

<!-- Palpitasi -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Palpitasi</strong></label>
    <div class="col-sm-9">
        <select class="form-select" name="palpitasi">
            <option value="">-- Pilih --</option>
            <option value="Y">Ya</option>
            <option value="T">Tidak</option>
        </select>
        <textarea class="form-control mt-2" rows="2"
        placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
    </div>
    <div class="col-sm-1">
        <input class="form-check-input mt-2" type="checkbox">
    </div>
</div>

<!-- Dispneu Nocturnal Proximal -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Dispneu Nocturnal Proximal</strong></label>
    <div class="col-sm-9">
        <select class="form-select" name="dispneu_nocturnal">
            <option value="">-- Pilih --</option>
            <option value="Y">Ya</option>
            <option value="T">Tidak</option>
        </select>
        <textarea class="form-control mt-2" rows="2"
        placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
    </div>
    <div class="col-sm-1">
        <input class="form-check-input mt-2" type="checkbox">
    </div>
</div>

<!-- Ortopneu -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Ortopneu</strong></label>
    <div class="col-sm-9">
        <select class="form-select" name="ortopneu">
            <option value="">-- Pilih --</option>
            <option value="Y">Ya</option>
            <option value="T">Tidak</option>
        </select>
        <textarea class="form-control mt-2" rows="2"
        placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
    </div>
    <div class="col-sm-1">
        <input class="form-check-input mt-2" type="checkbox">
    </div>
</div>

<!-- Murmur -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Murmur</strong></label>
    <div class="col-sm-9">
        <select class="form-select" name="murmur">
            <option value="">-- Pilih --</option>
            <option value="Y">Ya</option>
            <option value="T">Tidak</option>
        </select>
        <textarea class="form-control mt-2" rows="2"
        placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
    </div>
    <div class="col-sm-1">
        <input class="form-check-input mt-2" type="checkbox">
    </div>
</div>

<!-- Edema -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Edema</strong></label>
    <div class="col-sm-9">
        <select class="form-select" name="edema">
            <option value="">-- Pilih --</option>
            <option value="Y">Ya</option>
            <option value="T">Tidak</option>
        </select>
        <textarea class="form-control mt-2" rows="2"
        placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
    </div>
    <div class="col-sm-1">
        <input class="form-check-input mt-2" type="checkbox">
    </div>
</div>

<!-- Parestesia -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Parestesia</strong></label>
    <div class="col-sm-9">
        <select class="form-select" name="parestesia">
            <option value="">-- Pilih --</option>
            <option value="Y">Ya</option>
            <option value="T">Tidak</option>
        </select>
        <textarea class="form-control mt-2" rows="2"
        placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
    </div>
    <div class="col-sm-1">
        <input class="form-check-input mt-2" type="checkbox">
    </div>
</div>

<!-- Riwayat Infark -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Riwayat Infark</strong></label>
    <div class="col-sm-9">
        <select class="form-select" name="riwayat_infark">
            <option value="">-- Pilih --</option>
            <option value="Y">Ya</option>
            <option value="T">Tidak</option>
        </select>
        <textarea class="form-control mt-2" rows="2"
        placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
    </div>
    <div class="col-sm-1">
        <input class="form-check-input mt-2" type="checkbox">
    </div>
</div>


                <div class="row mb-2">
                    <label class="col-sm-12 col-form-label text-primary">
                        <strong>9. Gastrointestinal</strong>
                    </label>
                </div>

                <!-- Disfagia -->
                <div class="row mb-3 align-items-start">
                    <label class="col-sm-2 col-form-label"><strong>Disfagia</strong></label>
                    <div class="col-sm-9">
                        <select class="form-select" name="disfagia">
                            <option value="">-- Pilih --</option>
                            <option value="Y">Ya</option>
                            <option value="T">Tidak</option>
                        </select>
                        <textarea class="form-control mt-2" rows="2"
                            placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                    </div>
                    <div class="col-sm-1">
                        <input class="form-check-input mt-2" type="checkbox">
                    </div>
                </div>

                <!-- Tidak Dapat Mengunyah -->
                <div class="row mb-3 align-items-start">
                    <label class="col-sm-2 col-form-label"><strong>Tidak Dapat Mengunyah</strong></label>
                    <div class="col-sm-9">
                        <select class="form-select" name="tidak_dapat_mengunyah">
                            <option value="">-- Pilih --</option>
                            <option value="Y">Ya</option>
                            <option value="T">Tidak</option>
                        </select>
                        <textarea class="form-control mt-2" rows="2"
                            placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                    </div>
                    <div class="col-sm-1">
                        <input class="form-check-input mt-2" type="checkbox">
                    </div>
                </div>

                <!-- Nyeri Uluhati -->
                <div class="row mb-3 align-items-start">
                    <label class="col-sm-2 col-form-label"><strong>Nyeri Uluhati</strong></label>
                    <div class="col-sm-9">
                        <select class="form-select" name="nyeri_uluhati">
                            <option value="">-- Pilih --</option>
                            <option value="Y">Ya</option>
                            <option value="T">Tidak</option>
                        </select>
                        <textarea class="form-control mt-2" rows="2"
                            placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                    </div>
                    <div class="col-sm-1">
                        <input class="form-check-input mt-2" type="checkbox">
                    </div>
                </div>

                <!-- Mual -->
                <div class="row mb-3 align-items-start">
                    <label class="col-sm-2 col-form-label"><strong>Mual</strong></label>
                    <div class="col-sm-9">
                        <select class="form-select" name="mual">
                            <option value="">-- Pilih --</option>
                            <option value="Y">Ya</option>
                            <option value="T">Tidak</option>
                        </select>
                        <textarea class="form-control mt-2" rows="2"
                            placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                    </div>
                    <div class="col-sm-1">
                        <input class="form-check-input mt-2" type="checkbox">
                    </div>
                </div>

                <!-- Muntah -->
                <div class="row mb-3 align-items-start">
                    <label class="col-sm-2 col-form-label"><strong>Muntah</strong></label>
                    <div class="col-sm-9">
                        <select class="form-select" name="muntah">
                            <option value="">-- Pilih --</option>
                            <option value="Y">Ya</option>
                            <option value="T">Tidak</option>
                        </select>
                        <textarea class="form-control mt-2" rows="2"
                            placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                    </div>
                    <div class="col-sm-1">
                        <input class="form-check-input mt-2" type="checkbox">
                    </div>
                </div>

                <!-- Hematemesis -->
                <div class="row mb-3 align-items-start">
                    <label class="col-sm-2 col-form-label"><strong>Hematemesis</strong></label>
                    <div class="col-sm-9">
                        <select class="form-select" name="hematemesis">
                            <option value="">-- Pilih --</option>
                            <option value="Y">Ya</option>
                            <option value="T">Tidak</option>
                        </select>
                        <textarea class="form-control mt-2" rows="2"
                            placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                    </div>
                    <div class="col-sm-1">
                        <input class="form-check-input mt-2" type="checkbox">
                    </div>
                </div>

                <!-- Penurunan Selera Makan -->
                <div class="row mb-3 align-items-start">
                    <label class="col-sm-2 col-form-label"><strong>Penurunan Selera Makan</strong></label>
                    <div class="col-sm-9">
                        <select class="form-select" name="penurunan_selera_makan">
                            <option value="">-- Pilih --</option>
                            <option value="Y">Ya</option>
                            <option value="T">Tidak</option>
                        </select>
                        <textarea class="form-control mt-2" rows="2"
                            placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                    </div>
                    <div class="col-sm-1">
                        <input class="form-check-input mt-2" type="checkbox">
                    </div>
                </div>

                <!-- Ikterik -->
                <div class="row mb-3 align-items-start">
                    <label class="col-sm-2 col-form-label"><strong>Ikterik</strong></label>
                    <div class="col-sm-9">
                        <select class="form-select" name="ikterik">
                            <option value="">-- Pilih --</option>
                            <option value="Y">Ya</option>
                            <option value="T">Tidak</option>
                        </select>
                        <textarea class="form-control mt-2" rows="2"
                            placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                    </div>
                    <div class="col-sm-1">
                        <input class="form-check-input mt-2" type="checkbox">
                    </div>
                </div>
                                                                            <div class="row mb-2">
                    <label class="col-sm-12 col-form-label text-primary">
                        <strong>10. Perkemihan</strong>
                    </label>
                </div>

                <!-- Disuria -->
                <div class="row mb-3 align-items-start">
                    <label class="col-sm-2 col-form-label"><strong>Disuria</strong></label>
                    <div class="col-sm-9">
                        <select class="form-select" name="disuria">
                            <option value="">-- Pilih --</option>
                            <option value="Y">Ya</option>
                            <option value="T">Tidak</option>
                        </select>
                        <textarea class="form-control mt-2" rows="2"
                            placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                    </div>
                    <div class="col-sm-1">
                        <input class="form-check-input mt-2" type="checkbox">
                    </div>
                </div>

                <!-- Frekuensi -->
                <div class="row mb-3 align-items-start">
                    <label class="col-sm-2 col-form-label"><strong>Frekuensi</strong></label>
                    <div class="col-sm-9">
                        <select class="form-select" name="frekuensi">
                            <option value="">-- Pilih --</option>
                            <option value="Y">Ya</option>
                            <option value="T">Tidak</option>
                        </select>
                        <textarea class="form-control mt-2" rows="2"
                            placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                    </div>
                    <div class="col-sm-1">
                        <input class="form-check-input mt-2" type="checkbox">
                    </div>
                </div>

                <!-- Menetes -->
                <div class="row mb-3 align-items-start">
                    <label class="col-sm-2 col-form-label"><strong>Menetes</strong></label>
                    <div class="col-sm-9">
                        <select class="form-select" name="menetes">
                            <option value="">-- Pilih --</option>
                            <option value="Y">Ya</option>
                            <option value="T">Tidak</option>
                        </select>
                        <textarea class="form-control mt-2" rows="2"
                            placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                    </div>
                    <div class="col-sm-1">
                        <input class="form-check-input mt-2" type="checkbox">
                    </div>
                </div>

                <!-- Hematuria -->
                <div class="row mb-3 align-items-start">
                    <label class="col-sm-2 col-form-label"><strong>Hematuria</strong></label>
                    <div class="col-sm-9">
                        <select class="form-select" name="hematuria">
                            <option value="">-- Pilih --</option>
                            <option value="Y">Ya</option>
                            <option value="T">Tidak</option>
                        </select>
                        <textarea class="form-control mt-2" rows="2"
                            placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                    </div>
                    <div class="col-sm-1">
                        <input class="form-check-input mt-2" type="checkbox">
                    </div>
                </div>

                <!-- Poliuria -->
                <div class="row mb-3 align-items-start">
                    <label class="col-sm-2 col-form-label"><strong>Poliuria</strong></label>
                    <div class="col-sm-9">
                        <select class="form-select" name="poliuria">
                            <option value="">-- Pilih --</option>
                            <option value="Y">Ya</option>
                            <option value="T">Tidak</option>
                        </select>
                        <textarea class="form-control mt-2" rows="2"
                            placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                    </div>
                    <div class="col-sm-1">
                        <input class="form-check-input mt-2" type="checkbox">
                    </div>
                </div>

                <!-- Oliguria -->
                <div class="row mb-3 align-items-start">
                    <label class="col-sm-2 col-form-label"><strong>Oliguria</strong></label>
                    <div class="col-sm-9">
                        <select class="form-select" name="oliguria">
                            <option value="">-- Pilih --</option>
                            <option value="Y">Ya</option>
                            <option value="T">Tidak</option>
                        </select>
                        <textarea class="form-control mt-2" rows="2"
                            placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                    </div>
                    <div class="col-sm-1">
                        <input class="form-check-input mt-2" type="checkbox">
                    </div>
                </div>

                <!-- Riwayat Batu Perkemihan -->
                <div class="row mb-3 align-items-start">
                    <label class="col-sm-2 col-form-label"><strong>Riwayat Batu Pada Perkemihan</strong></label>
                    <div class="col-sm-9">
                        <select class="form-select" name="riwayat_batu_perkemihan">
                            <option value="">-- Pilih --</option>
                            <option value="Y">Ya</option>
                            <option value="T">Tidak</option>
                        </select>
                        <textarea class="form-control mt-2" rows="2"
                            placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                    </div>
                    <div class="col-sm-1">
                        <input class="form-check-input mt-2" type="checkbox">
                    </div>
                </div>

                <!-- Nokturia -->
                <div class="row mb-3 align-items-start">
                    <label class="col-sm-2 col-form-label"><strong>Nokturia</strong></label>
                    <div class="col-sm-9">
                        <select class="form-select" name="nokturia">
                            <option value="">-- Pilih --</option>
                            <option value="Y">Ya</option>
                            <option value="T">Tidak</option>
                        </select>
                        <textarea class="form-control mt-2" rows="2"
                            placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                    </div>
                    <div class="col-sm-1">
                        <input class="form-check-input mt-2" type="checkbox">
                    </div>
                </div>

                <!-- Inkontinensia Uri -->
                <div class="row mb-3 align-items-start">
                    <label class="col-sm-2 col-form-label"><strong>Inkontinensia Uri</strong></label>
                    <div class="col-sm-9">
                        <select class="form-select" name="inkontinensia_uri">
                            <option value="">-- Pilih --</option>
                            <option value="Y">Ya</option>
                            <option value="T">Tidak</option>
                        </select>
                        <textarea class="form-control mt-2" rows="2"
                            placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                    </div>
                    <div class="col-sm-1">
                        <input class="form-check-input mt-2" type="checkbox">
                    </div>
                </div>

                <!-- Riwayat Pembesaran Prostat -->
                <div class="row mb-3 align-items-start">
                    <label class="col-sm-2 col-form-label"><strong>Riwayat Pembesaran Prostat</strong></label>
                    <div class="col-sm-9">
                        <select class="form-select" name="riwayat_pembesaran_prostat">
                            <option value="">-- Pilih --</option>
                            <option value="Y">Ya</option>
                            <option value="T">Tidak</option>
                        </select>
                        <textarea class="form-control mt-2" rows="2"
                            placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                    </div>
                    <div class="col-sm-1">
                        <input class="form-check-input mt-2" type="checkbox">
                    </div>
                </div>
                                                                                <div class="row mb-2">
                    <label class="col-sm-12 col-form-label text-primary">
                        <strong>11. Muskuloskeletal</strong>
                    </label>
                </div>

                <!-- Nyeri Sendi -->
                <div class="row mb-3 align-items-start">
                    <label class="col-sm-2 col-form-label"><strong>Nyeri Sendi</strong></label>
                    <div class="col-sm-9">
                        <select class="form-select" name="nyeri_sendi">
                            <option value="">-- Pilih --</option>
                            <option value="Y">Ya</option>
                            <option value="T">Tidak</option>
                        </select>
                        <textarea class="form-control mt-2" rows="2"
                            placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                    </div>
                    <div class="col-sm-1">
                        <input class="form-check-input mt-2" type="checkbox">
                    </div>
                </div>

                <!-- Kekakuan Sendi -->
                <div class="row mb-3 align-items-start">
                    <label class="col-sm-2 col-form-label"><strong>Kekakuan Sendi</strong></label>
                    <div class="col-sm-9">
                        <select class="form-select" name="kekakuan_sendi">
                            <option value="">-- Pilih --</option>
                            <option value="Y">Ya</option>
                            <option value="T">Tidak</option>
                        </select>
                        <textarea class="form-control mt-2" rows="2"
                            placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                    </div>
                    <div class="col-sm-1">
                        <input class="form-check-input mt-2" type="checkbox">
                    </div>
                </div>

                <!-- Pembengkakan Sendi -->
                <div class="row mb-3 align-items-start">
                    <label class="col-sm-2 col-form-label"><strong>Pembengkakan Sendi</strong></label>
                    <div class="col-sm-9">
                        <select class="form-select" name="pembengkakan_sendi">
                            <option value="">-- Pilih --</option>
                            <option value="Y">Ya</option>
                            <option value="T">Tidak</option>
                        </select>
                        <textarea class="form-control mt-2" rows="2"
                            placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                    </div>
                    <div class="col-sm-1">
                        <input class="form-check-input mt-2" type="checkbox">
                    </div>
                </div>

                <!-- Spasme -->
                <div class="row mb-3 align-items-start">
                    <label class="col-sm-2 col-form-label"><strong>Spasme</strong></label>
                    <div class="col-sm-9">
                        <select class="form-select" name="spasme">
                            <option value="">-- Pilih --</option>
                            <option value="Y">Ya</option>
                            <option value="T">Tidak</option>
                        </select>
                        <textarea class="form-control mt-2" rows="2"
                            placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                    </div>
                    <div class="col-sm-1">
                        <input class="form-check-input mt-2" type="checkbox">
                    </div>
                </div>

                <!-- Kram Otot -->
                <div class="row mb-3 align-items-start">
                    <label class="col-sm-2 col-form-label"><strong>Kram Otot</strong></label>
                    <div class="col-sm-9">
                        <select class="form-select" name="kram_otot">
                            <option value="">-- Pilih --</option>
                            <option value="Y">Ya</option>
                            <option value="T">Tidak</option>
                        </select>
                        <textarea class="form-control mt-2" rows="2"
                            placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                    </div>
                    <div class="col-sm-1">
                        <input class="form-check-input mt-2" type="checkbox">
                    </div>
                </div>

                <!-- Deformitas -->
                <div class="row mb-3 align-items-start">
                    <label class="col-sm-2 col-form-label"><strong>Deformitas</strong></label>
                    <div class="col-sm-9">
                        <select class="form-select" name="deformitas">
                            <option value="">-- Pilih --</option>
                            <option value="Y">Ya</option>
                            <option value="T">Tidak</option>
                        </select>
                        <textarea class="form-control mt-2" rows="2"
                            placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                    </div>
                    <div class="col-sm-1">
                        <input class="form-check-input mt-2" type="checkbox">
                    </div>
                </div>

                <!-- Penurunan Kekuatan Otot -->
                <div class="row mb-3 align-items-start">
                    <label class="col-sm-2 col-form-label"><strong>Penurunan Kekuatan Otot</strong></label>
                    <div class="col-sm-9">
                        <select class="form-select" name="penurunan_kekuatan_otot">
                            <option value="">-- Pilih --</option>
                            <option value="Y">Ya</option>
                            <option value="T">Tidak</option>
                        </select>
                        <textarea class="form-control mt-2" rows="2"
                            placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                    </div>
                    <div class="col-sm-1">
                        <input class="form-check-input mt-2" type="checkbox">
                    </div>
                </div>

                <!-- Kelemahan -->
                <div class="row mb-3 align-items-start">
                    <label class="col-sm-2 col-form-label"><strong>Kelemahan</strong></label>
                    <div class="col-sm-9">
                        <select class="form-select" name="kelemahan">
                            <option value="">-- Pilih --</option>
                            <option value="Y">Ya</option>
                            <option value="T">Tidak</option>
                        </select>
                        <textarea class="form-control mt-2" rows="2"
                            placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                    </div>
                    <div class="col-sm-1">
                        <input class="form-check-input mt-2" type="checkbox">
                    </div>
                </div>

                <!-- Nyeri Punggung -->
                <div class="row mb-3 align-items-start">
                    <label class="col-sm-2 col-form-label"><strong>Nyeri Punggung Belakang</strong></label>
                    <div class="col-sm-9">
                        <select class="form-select" name="nyeri_punggung_belakang">
                            <option value="">-- Pilih --</option>
                            <option value="Y">Ya</option>
                            <option value="T">Tidak</option>
                        </select>
                        <textarea class="form-control mt-2" rows="2"
                            placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                    </div>
                    <div class="col-sm-1">
                        <input class="form-check-input mt-2" type="checkbox">
                    </div>
                </div>

                <!-- Nyeri Pinggang -->
                <div class="row mb-3 align-items-start">
                    <label class="col-sm-2 col-form-label"><strong>Nyeri Pinggang</strong></label>
                    <div class="col-sm-9">
                        <select class="form-select" name="nyeri_pinggang">
                            <option value="">-- Pilih --</option>
                            <option value="Y">Ya</option>
                            <option value="T">Tidak</option>
                        </select>
                        <textarea class="form-control mt-2" rows="2"
                            placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                    </div>
                    <div class="col-sm-1">
                        <input class="form-check-input mt-2" type="checkbox">
                    </div>
                </div>

                <!-- Alat Bantuan Berjalan -->
                <div class="row mb-3 align-items-start">
                    <label class="col-sm-2 col-form-label"><strong>Menggunakan Alat Bantuan Berjalan</strong></label>
                    <div class="col-sm-9">
                        <select class="form-select" name="alat_bantuan_berjalan">
                            <option value="">-- Pilih --</option>
                            <option value="Y">Ya</option>
                            <option value="T">Tidak</option>
                        </select>
                        <textarea class="form-control mt-2" rows="2"
                            placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                    </div>
                    <div class="col-sm-1">
                        <input class="form-check-input mt-2" type="checkbox">
                    </div>
                </div>

                <!-- Perubahan Cara Berjalan -->
                <div class="row mb-3 align-items-start">
                    <label class="col-sm-2 col-form-label"><strong>Perubahan Cara Berjalan</strong></label>
                    <div class="col-sm-9">
                        <select class="form-select" name="perubahan_cara_berjalan">
                            <option value="">-- Pilih --</option>
                            <option value="Y">Ya</option>
                            <option value="T">Tidak</option>
                        </select>
                        <textarea class="form-control mt-2" rows="2"
                            placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                    </div>
                    <div class="col-sm-1">
                        <input class="form-check-input mt-2" type="checkbox">
                    </div>
                </div>

                <!-- Tremor -->
                <div class="row mb-3 align-items-start">
                    <label class="col-sm-2 col-form-label"><strong>Tremor</strong></label>
                    <div class="col-sm-9">
                        <select class="form-select" name="tremor">
                            <option value="">-- Pilih --</option>
                            <option value="Y">Ya</option>
                            <option value="T">Tidak</option>
                        </select>
                        <textarea class="form-control mt-2" rows="2"
                            placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                    </div>
                    <div class="col-sm-1">
                        <input class="form-check-input mt-2" type="checkbox">
                    </div>
                </div>

                <!-- Atropi Otot -->
                <div class="row mb-3 align-items-start">
                    <label class="col-sm-2 col-form-label"><strong>Atropi Otot</strong></label>
                    <div class="col-sm-9">
                        <select class="form-select" name="atropi_otot">
                            <option value="">-- Pilih --</option>
                            <option value="Y">Ya</option>
                            <option value="T">Tidak</option>
                        </select>
                        <textarea class="form-control mt-2" rows="2"
                            placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                    </div>
                    <div class="col-sm-1">
                        <input class="form-check-input mt-2" type="checkbox">
                    </div>
                </div>
                                                                                <div class="row mb-2">
                    <label class="col-sm-12 col-form-label text-primary">
                        <strong>12. Endokrin</strong>
                    </label>
                </div>

                <!-- Intoleransi Panas -->
                <div class="row mb-3 align-items-start">
                    <label class="col-sm-2 col-form-label"><strong>Intoleransi Panas</strong></label>
                    <div class="col-sm-9">
                        <select class="form-select" name="intoleransi_panas">
                            <option value="">-- Pilih --</option>
                            <option value="Y">Ya</option>
                            <option value="T">Tidak</option>
                        </select>
                        <textarea class="form-control mt-2" rows="2"
                            placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                    </div>
                    <div class="col-sm-1">
                        <input class="form-check-input mt-2" type="checkbox">
                    </div>
                </div>

                <!-- Intoleransi Dingin -->
                <div class="row mb-3 align-items-start">
                    <label class="col-sm-2 col-form-label"><strong>Intoleransi Dingin</strong></label>
                    <div class="col-sm-9">
                        <select class="form-select" name="intoleransi_dingin">
                            <option value="">-- Pilih --</option>
                            <option value="Y">Ya</option>
                            <option value="T">Tidak</option>
                        </select>
                        <textarea class="form-control mt-2" rows="2"
                            placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                    </div>
                    <div class="col-sm-1">
                        <input class="form-check-input mt-2" type="checkbox">
                    </div>
                </div>

                <!-- Goiter -->
                <div class="row mb-3 align-items-start">
                    <label class="col-sm-2 col-form-label"><strong>Goiter</strong></label>
                    <div class="col-sm-9">
                        <select class="form-select" name="goiter">
                            <option value="">-- Pilih --</option>
                            <option value="Y">Ya</option>
                            <option value="T">Tidak</option>
                        </select>
                        <textarea class="form-control mt-2" rows="2"
                            placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                    </div>
                    <div class="col-sm-1">
                        <input class="form-check-input mt-2" type="checkbox">
                    </div>
                </div>

                <!-- Poli Fagi -->
                <div class="row mb-3 align-items-start">
                    <label class="col-sm-2 col-form-label"><strong>Poli Fagi</strong></label>
                    <div class="col-sm-9">
                        <select class="form-select" name="poli_fagi">
                            <option value="">-- Pilih --</option>
                            <option value="Y">Ya</option>
                            <option value="T">Tidak</option>
                        </select>
                        <textarea class="form-control mt-2" rows="2"
                            placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                    </div>
                    <div class="col-sm-1">
                        <input class="form-check-input mt-2" type="checkbox">
                    </div>
                </div>

                <!-- Poli Uri -->
                <div class="row mb-3 align-items-start">
                    <label class="col-sm-2 col-form-label"><strong>Poli Uri</strong></label>
                    <div class="col-sm-9">
                        <select class="form-select" name="poli_uri">
                            <option value="">-- Pilih --</option>
                            <option value="Y">Ya</option>
                            <option value="T">Tidak</option>
                        </select>
                        <textarea class="form-control mt-2" rows="2"
                            placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                    </div>
                    <div class="col-sm-1">
                        <input class="form-check-input mt-2" type="checkbox">
                    </div>
                </div>

                <!-- Poli Dipsi -->
                <div class="row mb-3 align-items-start">
                    <label class="col-sm-2 col-form-label"><strong>Poli Dipsi</strong></label>
                    <div class="col-sm-9">
                        <select class="form-select" name="poli_dipsi">
                            <option value="">-- Pilih --</option>
                            <option value="Y">Ya</option>
                            <option value="T">Tidak</option>
                        </select>
                        <textarea class="form-control mt-2" rows="2"
                            placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                    </div>
                    <div class="col-sm-1">
                        <input class="form-check-input mt-2" type="checkbox">
                    </div>
                </div>

                <!-- Perubahan Rambut -->
                <div class="row mb-3 align-items-start">
                    <label class="col-sm-2 col-form-label"><strong>Perubahan Rambut</strong></label>
                    <div class="col-sm-9">
                        <select class="form-select" name="perubahan_rambut">
                            <option value="">-- Pilih --</option>
                            <option value="Y">Ya</option>
                            <option value="T">Tidak</option>
                        </select>
                        <textarea class="form-control mt-2" rows="2"
                            placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                    </div>
                    <div class="col-sm-1">
                        <input class="form-check-input mt-2" type="checkbox">
                    </div>
                </div>

                <!-- Pigmentasi Kulit -->
                <div class="row mb-3 align-items-start">
                    <label class="col-sm-2 col-form-label"><strong>Pigmentasi Kulit</strong></label>
                    <div class="col-sm-9">
                        <select class="form-select" name="pigmentasi_kulit">
                            <option value="">-- Pilih --</option>
                            <option value="Y">Ya</option>
                            <option value="T">Tidak</option>
                        </select>
                        <textarea class="form-control mt-2" rows="2"
                            placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                    </div>
                    <div class="col-sm-1">
                        <input class="form-check-input mt-2" type="checkbox">
                    </div>
                </div>
                                                <div class="row mb-2">
                    <label class="col-sm-12 col-form-label text-primary">
                        <strong>14. Integumen</strong>
                    </label>
                </div>

                <!-- Kulit Kering -->
                <div class="row mb-3 align-items-start">
                    <label class="col-sm-2 col-form-label"><strong>Kulit Kering</strong></label>
                    <div class="col-sm-9">
                        <select class="form-select" name="kulit_kering">
                            <option value="">-- Pilih --</option>
                            <option value="Y">Ya</option>
                            <option value="T">Tidak</option>
                        </select>
                        <textarea class="form-control mt-2" rows="2"
                            placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                    </div>
                    <div class="col-sm-1">
                        <input class="form-check-input mt-2" type="checkbox">
                    </div>
                </div>

                <!-- Kulit Keriput -->
                <div class="row mb-3 align-items-start">
                    <label class="col-sm-2 col-form-label"><strong>Kulit Keriput / Mengerut</strong></label>
                    <div class="col-sm-9">
                        <select class="form-select" name="kulit_keriput">
                            <option value="">-- Pilih --</option>
                            <option value="Y">Ya</option>
                            <option value="T">Tidak</option>
                        </select>
                        <textarea class="form-control mt-2" rows="2"
                            placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                    </div>
                    <div class="col-sm-1">
                        <input class="form-check-input mt-2" type="checkbox">
                    </div>
                </div>

                <!-- Menjaga Kebersihan Kulit -->
                <div class="row mb-3 align-items-start">
                    <label class="col-sm-2 col-form-label"><strong>Menjaga Kebersihan Kulit</strong></label>
                    <div class="col-sm-9">
                        <select class="form-select" name="menjaga_kebersihan_kulit">
                            <option value="">-- Pilih --</option>
                            <option value="Y">Ya</option>
                            <option value="T">Tidak</option>
                        </select>
                        <textarea class="form-control mt-2" rows="2"
                            placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                    </div>
                    <div class="col-sm-1">
                        <input class="form-check-input mt-2" type="checkbox">
                    </div>
                </div>

                <!-- Penurunan Lemak di Bawah Kulit -->
                <div class="row mb-3 align-items-start">
                    <label class="col-sm-2 col-form-label"><strong>Penurunan Lemak di Bawah Kulit</strong></label>
                    <div class="col-sm-9">
                        <select class="form-select" name="penurunan_lemak_bawah_kulit">
                            <option value="">-- Pilih --</option>
                            <option value="Y">Ya</option>
                            <option value="T">Tidak</option>
                        </select>
                        <textarea class="form-control mt-2" rows="2"
                            placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                    </div>
                    <div class="col-sm-1">
                        <input class="form-check-input mt-2" type="checkbox">
                    </div>
                </div>
                            </div>
</div>
                   
                   
         <div class="card">
             <div class="card-body">
                                
                     <!-- General Form Elements -->
                    <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

                    <h5 class="card-title"><strong>VII. Pola Kebiasaan Sehari-Hari</strong></h5>

                    <div class="row mb-2">
                        <label class="col-sm-12 col-form-label text-primary">
                            <strong>1. Nutrisi dan Cairan</strong>
                        </label>
                    </div>

                    <!-- Frekuensi Makan -->
                    <div class="row mb-3 align-items-start">
                        <label class="col-sm-2 col-form-label"><strong>Frekuensi Makan</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="frekuensi_makan">
                            <textarea class="form-control mt-2" rows="2"
                                placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                        </div>
                        <div class="col-sm-1">
                            <input class="form-check-input mt-2" type="checkbox">
                        </div>
                    </div>

                    <!-- Nafsu Makan -->
                    <div class="row mb-3 align-items-start">
                        <label class="col-sm-2 col-form-label"><strong>Nafsu Makan</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="nafsu_makan">
                            <textarea class="form-control mt-2" rows="2"
                                placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                        </div>
                        <div class="col-sm-1">
                            <input class="form-check-input mt-2" type="checkbox">
                        </div>
                    </div>

                    <!-- Jenis Makanan -->
                    <div class="row mb-3 align-items-start">
                        <label class="col-sm-2 col-form-label"><strong>Jenis Makanan</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="jenis_makanan">
                            <textarea class="form-control mt-2" rows="2"
                                placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                        </div>
                        <div class="col-sm-1">
                            <input class="form-check-input mt-2" type="checkbox">
                        </div>
                    </div>

                    <!-- Makanan yang Tidak Disukai -->
                    <div class="row mb-3 align-items-start">
                        <label class="col-sm-2 col-form-label"><strong>Makanan yang Tidak Disukai</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="makanan_tidak_disukai">
                            <textarea class="form-control mt-2" rows="2"
                                placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                        </div>
                        <div class="col-sm-1">
                            <input class="form-check-input mt-2" type="checkbox">
                        </div>
                    </div>

                    <!-- Kebiasaan Sebelum Makan -->
                    <div class="row mb-3 align-items-start">
                        <label class="col-sm-2 col-form-label"><strong>Kebiasaan / Ritual Sebelum Makan</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="kebiasaan_sebelum_makan">
                            <textarea class="form-control mt-2" rows="2"
                                placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                        </div>
                        <div class="col-sm-1">
                            <input class="form-check-input mt-2" type="checkbox">
                        </div>
                    </div>

                    <!-- Berat Badan / Tinggi Badan -->
                    <div class="row mb-3 align-items-start">
                        <label class="col-sm-2 col-form-label"><strong>Berat Badan / Tinggi Badan</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="berat_tinggi_badan">
                            <textarea class="form-control mt-2" rows="2"
                                placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                        </div>
                        <div class="col-sm-1">
                            <input class="form-check-input mt-2" type="checkbox">
                        </div>
                    </div>

                    <!-- Jenis Minuman -->
                    <div class="row mb-3 align-items-start">
                        <label class="col-sm-2 col-form-label"><strong>Jenis Minuman</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="jenis_minuman">
                            <textarea class="form-control mt-2" rows="2"
                                placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                        </div>
                        <div class="col-sm-1">
                            <input class="form-check-input mt-2" type="checkbox">
                        </div>
                    </div>

                    <!-- Jumlah Cairan -->
                    <div class="row mb-3 align-items-start">
                        <label class="col-sm-2 col-form-label"><strong>Jumlah Cairan yang Dikonsumsi</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="jumlah_cairan">
                            <textarea class="form-control mt-2" rows="2"
                                placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                        </div>
                        <div class="col-sm-1">
                            <input class="form-check-input mt-2" type="checkbox">
                        </div>
                    </div>

                    <!-- Kesulitan Makan Minum -->
                    <div class="row mb-3 align-items-start">
                        <label class="col-sm-2 col-form-label"><strong>Kesulitan Makan dan Minum</strong></label>
                        <div class="col-sm-9">
                            <select class="form-select" name="kesulitan_makan_minum">
                                <option value="">-- Pilih --</option>
                                <option value="Y">Ya</option>
                                <option value="T">Tidak</option>
                            </select>
                            <textarea class="form-control mt-2" rows="2"
                                placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                        </div>
                        <div class="col-sm-1">
                            <input class="form-check-input mt-2" type="checkbox">
                        </div>
                    </div>

                    <!-- Makan Minum -->
                    <div class="row mb-3 align-items-start">
                        <label class="col-sm-2 col-form-label"><strong>Untuk Makan dan Minum</strong></label>
                        <div class="col-sm-9">
                            <select class="form-select" name="makan_minum_bantu">
                                <option value="">-- Pilih --</option>
                                <option value="Y">Dibantu</option>
                                <option value="T">Mandiri</option>
                            </select>
                            <textarea class="form-control mt-2" rows="2"
                                placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                        </div>
                        <div class="col-sm-1">
                            <input class="form-check-input mt-2" type="checkbox">
                        </div>
                    </div>
                        <div class="row mb-2">
                        <label class="col-sm-12 col-form-label text-primary">
                            <strong>2. Eliminasi</strong>
                        </label>
                    </div>

                    <div class="row mb-2">
                        <label class="col-sm-12 col-form-label text-info">
                            <strong>a. Berkemih (BAK)</strong>
                        </label>
                    </div>

                    <!-- Warna BAK -->
                    <div class="row mb-3 align-items-start">
                        <label class="col-sm-2 col-form-label"><strong>Warna</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="warna_bak">
                            <textarea class="form-control mt-2" rows="2"
                            placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                        </div>
                        <div class="col-sm-1">
                            <input class="form-check-input mt-2" type="checkbox">
                        </div>
                    </div>

                    <!-- Keluhan BAK -->
                    <div class="row mb-3 align-items-start">
                        <label class="col-sm-2 col-form-label"><strong>Keluhan yang Berhubungan dengan BAK</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="keluhan_bak">
                            <textarea class="form-control mt-2" rows="2"
                            placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                        </div>
                        <div class="col-sm-1">
                            <input class="form-check-input mt-2" type="checkbox">
                        </div>
                    </div>

                    <!-- Dibantu BAK -->
                    <div class="row mb-3 align-items-start">
                        <label class="col-sm-2 col-form-label"><strong>Dibantu</strong></label>
                        <div class="col-sm-9">
                            <select class="form-select" name="dibantu_bak">
                                <option value="">-- Pilih --</option>
                                <option value="Y">Ya</option>
                                <option value="T">Tidak</option>
                            </select>
                            <textarea class="form-control mt-2" rows="2"
                            placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                        </div>
                        <div class="col-sm-1">
                            <input class="form-check-input mt-2" type="checkbox">
                        </div>
                    </div>

                    <!-- Mandiri BAK -->
                    <div class="row mb-3 align-items-start">
                        <label class="col-sm-2 col-form-label"><strong>Mandiri</strong></label>
                        <div class="col-sm-9">
                            <select class="form-select" name="mandiri_bak">
                                <option value="">-- Pilih --</option>
                                <option value="Y">Ya</option>
                                <option value="T">Tidak</option>
                            </select>
                            <textarea class="form-control mt-2" rows="2"
                            placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                        </div>
                        <div class="col-sm-1">
                            <input class="form-check-input mt-2" type="checkbox">
                        </div>
                    </div>

                    <div class="row mb-2">
                        <label class="col-sm-12 col-form-label text-info">
                            <strong>b. Defekasi (BAB)</strong>
                        </label>
                    </div>

                    <!-- Frekuensi BAB -->
                    <div class="row mb-3 align-items-start">
                        <label class="col-sm-2 col-form-label"><strong>Frekuensi</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="frekuensi_bab">
                            <textarea class="form-control mt-2" rows="2"
                            placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                        </div>
                        <div class="col-sm-1">
                            <input class="form-check-input mt-2" type="checkbox">
                        </div>
                    </div>

                    <!-- Bau BAB -->
                    <div class="row mb-3 align-items-start">
                        <label class="col-sm-2 col-form-label"><strong>Bau</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="bau_bab">
                            <textarea class="form-control mt-2" rows="2"
                            placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                        </div>
                        <div class="col-sm-1">
                            <input class="form-check-input mt-2" type="checkbox">
                        </div>
                    </div>

                    <!-- Warna BAB -->
                    <div class="row mb-3 align-items-start">
                        <label class="col-sm-2 col-form-label"><strong>Warna</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="warna_bab">
                            <textarea class="form-control mt-2" rows="2"
                            placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                        </div>
                        <div class="col-sm-1">
                            <input class="form-check-input mt-2" type="checkbox">
                        </div>
                    </div>

                    <!-- Konsistensi BAB -->
                    <div class="row mb-3 align-items-start">
                        <label class="col-sm-2 col-form-label"><strong>Konsistensi</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="konsistensi_bab">
                            <textarea class="form-control mt-2" rows="2"
                            placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                        </div>
                        <div class="col-sm-1">
                            <input class="form-check-input mt-2" type="checkbox">
                        </div>
                    </div>

                    <!-- Keluhan BAB -->
                    <div class="row mb-3 align-items-start">
                        <label class="col-sm-2 col-form-label"><strong>Keluhan yang Berhubungan dengan Defekasi</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="keluhan_bab">
                            <textarea class="form-control mt-2" rows="2"
                            placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                        </div>
                        <div class="col-sm-1">
                            <input class="form-check-input mt-2" type="checkbox">
                        </div>
                    </div>

                    <!-- Laksatif -->
                    <div class="row mb-3 align-items-start">
                        <label class="col-sm-2 col-form-label"><strong>Pengalaman Memakai Laksatif</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="pengalaman_laksatif">
                            <textarea class="form-control mt-2" rows="2"
                            placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                        </div>
                        <div class="col-sm-1">
                            <input class="form-check-input mt-2" type="checkbox">
                        </div>
                    </div>

                    <!-- Dibantu BAB -->
                    <div class="row mb-3 align-items-start">
                        <label class="col-sm-2 col-form-label"><strong>Dibantu</strong></label>
                        <div class="col-sm-9">
                            <select class="form-select" name="dibantu_bab">
                                <option value="">-- Pilih --</option>
                                <option value="Y">Ya</option>
                                <option value="T">Tidak</option>
                            </select>
                            <textarea class="form-control mt-2" rows="2"
                            placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                        </div>
                        <div class="col-sm-1">
                            <input class="form-check-input mt-2" type="checkbox">
                        </div>
                    </div>

                    <!-- Mandiri BAB -->
                    <div class="row mb-3 align-items-start">
                        <label class="col-sm-2 col-form-label"><strong>Mandiri</strong></label>
                        <div class="col-sm-9">
                            <select class="form-select" name="mandiri_bab">
                                <option value="">-- Pilih --</option>
                                <option value="Y">Ya</option>
                                <option value="T">Tidak</option>
                            </select>
                            <textarea class="form-control mt-2" rows="2"
                            placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                        </div>
                        <div class="col-sm-1">
                            <input class="form-check-input mt-2" type="checkbox">
                        </div>
                    </div><div class="row mb-2">
                        <label class="col-sm-12 col-form-label text-primary">
                            <strong>3. Hygiene Personal</strong>
                        </label>
                    </div>

                    <div class="row mb-2">
                        <label class="col-sm-12 col-form-label text-info">
                            <strong>a. Mandi</strong>
                        </label>
                    </div>

                    <!-- Frekuensi Mandi -->
                    <div class="row mb-3 align-items-start">
                        <label class="col-sm-2 col-form-label"><strong>Frekuensi</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="frekuensi_mandi">
                            <textarea class="form-control mt-2" rows="2"
                            placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                        </div>
                        <div class="col-sm-1">
                            <input class="form-check-input mt-2" type="checkbox">
                        </div>
                    </div>

                    <!-- Dibantu Mandi -->
                    <div class="row mb-3 align-items-start">
                        <label class="col-sm-2 col-form-label"><strong>Dibantu</strong></label>
                        <div class="col-sm-9">
                            <select class="form-select" name="dibantu_mandi">
                                <option value="">-- Pilih --</option>
                                <option value="Y">Ya</option>
                                <option value="T">Tidak</option>
                            </select>
                            <textarea class="form-control mt-2" rows="2"
                            placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                        </div>
                        <div class="col-sm-1">
                            <input class="form-check-input mt-2" type="checkbox">
                        </div>
                    </div>

                    <!-- Mandiri Mandi -->
                    <div class="row mb-3 align-items-start">
                        <label class="col-sm-2 col-form-label"><strong>Mandiri</strong></label>
                        <div class="col-sm-9">
                            <select class="form-select" name="mandiri_mandi">
                                <option value="">-- Pilih --</option>
                                <option value="Y">Ya</option>
                                <option value="T">Tidak</option>
                            </select>
                            <textarea class="form-control mt-2" rows="2"
                            placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                        </div>
                        <div class="col-sm-1">
                            <input class="form-check-input mt-2" type="checkbox">
                        </div>
                    </div>


                    <div class="row mb-2">
                        <label class="col-sm-12 col-form-label text-info">
                            <strong>b. Hygiene Oral</strong>
                        </label>
                    </div>

                    <!-- Frekuensi Hygiene Oral -->
                    <div class="row mb-3 align-items-start">
                        <label class="col-sm-2 col-form-label"><strong>Frekuensi</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="frekuensi_hygiene_oral">
                            <textarea class="form-control mt-2" rows="2"
                            placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                        </div>
                        <div class="col-sm-1">
                            <input class="form-check-input mt-2" type="checkbox">
                        </div>
                    </div>

                    <!-- Dibantu Hygiene Oral -->
                    <div class="row mb-3 align-items-start">
                        <label class="col-sm-2 col-form-label"><strong>Dibantu</strong></label>
                        <div class="col-sm-9">
                            <select class="form-select" name="dibantu_hygiene_oral">
                                <option value="">-- Pilih --</option>
                                <option value="Y">Ya</option>
                                <option value="T">Tidak</option>
                            </select>
                            <textarea class="form-control mt-2" rows="2"
                            placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                        </div>
                        <div class="col-sm-1">
                            <input class="form-check-input mt-2" type="checkbox">
                        </div>
                    </div>

                    <!-- Mandiri Hygiene Oral -->
                    <div class="row mb-3 align-items-start">
                        <label class="col-sm-2 col-form-label"><strong>Mandiri</strong></label>
                        <div class="col-sm-9">
                            <select class="form-select" name="mandiri_hygiene_oral">
                                <option value="">-- Pilih --</option>
                                <option value="Y">Ya</option>
                                <option value="T">Tidak</option>
                            </select>
                            <textarea class="form-control mt-2" rows="2"
                            placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                        </div>
                        <div class="col-sm-1">
                            <input class="form-check-input mt-2" type="checkbox">
                        </div>
                    </div>


                    <div class="row mb-2">
                        <label class="col-sm-12 col-form-label text-info">
                            <strong>c. Cuci Rambut</strong>
                        </label>
                    </div>

                    <!-- Frekuensi Cuci Rambut -->
                    <div class="row mb-3 align-items-start">
                        <label class="col-sm-2 col-form-label"><strong>Frekuensi</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="frekuensi_cuci_rambut">
                            <textarea class="form-control mt-2" rows="2"
                            placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                        </div>
                        <div class="col-sm-1">
                            <input class="form-check-input mt-2" type="checkbox">
                        </div>
                    </div>

                    <!-- Dibantu Cuci Rambut -->
                    <div class="row mb-3 align-items-start">
                        <label class="col-sm-2 col-form-label"><strong>Dibantu</strong></label>
                        <div class="col-sm-9">
                            <select class="form-select" name="dibantu_cuci_rambut">
                                <option value="">-- Pilih --</option>
                                <option value="Y">Ya</option>
                                <option value="T">Tidak</option>
                            </select>
                            <textarea class="form-control mt-2" rows="2"
                            placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                        </div>
                        <div class="col-sm-1">
                            <input class="form-check-input mt-2" type="checkbox">
                        </div>
                    </div>

                    <!-- Mandiri Cuci Rambut -->
                    <div class="row mb-3 align-items-start">
                        <label class="col-sm-2 col-form-label"><strong>Mandiri</strong></label>
                        <div class="col-sm-9">
                            <select class="form-select" name="mandiri_cuci_rambut">
                                <option value="">-- Pilih --</option>
                                <option value="Y">Ya</option>
                                <option value="T">Tidak</option>
                            </select>
                            <textarea class="form-control mt-2" rows="2"
                            placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                        </div>
                        <div class="col-sm-1">
                            <input class="form-check-input mt-2" type="checkbox">
                        </div>
                    </div>


                    <div class="row mb-2">
                        <label class="col-sm-12 col-form-label text-info">
                            <strong>d. Gunting Kuku</strong>
                        </label>
                    </div>

                    <!-- Frekuensi Gunting Kuku -->
                    <div class="row mb-3 align-items-start">
                        <label class="col-sm-2 col-form-label"><strong>Frekuensi</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="frekuensi_gunting_kuku">
                            <textarea class="form-control mt-2" rows="2"
                            placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                        </div>
                        <div class="col-sm-1">
                            <input class="form-check-input mt-2" type="checkbox">
                        </div>
                    </div>

                    <!-- Dibantu Gunting Kuku -->
                    <div class="row mb-3 align-items-start">
                        <label class="col-sm-2 col-form-label"><strong>Dibantu</strong></label>
                        <div class="col-sm-9">
                            <select class="form-select" name="dibantu_gunting_kuku">
                                <option value="">-- Pilih --</option>
                                <option value="Y">Ya</option>
                                <option value="T">Tidak</option>
                            </select>
                            <textarea class="form-control mt-2" rows="2"
                            placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                        </div>
                        <div class="col-sm-1">
                            <input class="form-check-input mt-2" type="checkbox">
                        </div>
                    </div>

                    <!-- Mandiri Gunting Kuku -->
                    <div class="row mb-3 align-items-start">
                        <label class="col-sm-2 col-form-label"><strong>Mandiri</strong></label>
                        <div class="col-sm-9">
                            <select class="form-select" name="mandiri_gunting_kuku">
                                <option value="">-- Pilih --</option>
                                <option value="Y">Ya</option>
                                <option value="T">Tidak</option>
                            </select>
                            <textarea class="form-control mt-2" rows="2"
                            placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                        </div>
                        <div class="col-sm-1">
                            <input class="form-check-input mt-2" type="checkbox">
                        </div>
                    </div>

                            <div class="row mb-2">
                        <label class="col-sm-12 col-form-label text-primary">
                            <strong>4. Istirahat dan Tidur</strong>
                        </label>
                    </div>

                    <!-- Lama Tidur -->
                    <div class="row mb-3 align-items-start">
                        <label class="col-sm-2 col-form-label"><strong>Lama Tidur (Jam/Hari)</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="lama_tidur">
                            <textarea class="form-control mt-2" rows="2"
                            placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                        </div>
                        <div class="col-sm-1">
                            <input class="form-check-input mt-2" type="checkbox">
                        </div>
                    </div>

                    <!-- Kesulitan Tidur -->
                    <div class="row mb-3 align-items-start">
                        <label class="col-sm-2 col-form-label"><strong>Kesulitan / Gangguan Tidur</strong></label>
                        <div class="col-sm-9">
                            <select class="form-select" name="kesulitan_tidur">
                                <option value="">-- Pilih --</option>
                                <option value="Y">Ya</option>
                                <option value="T">Tidak</option>
                            </select>
                            <textarea class="form-control mt-2" rows="2"
                            placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                        </div>
                        <div class="col-sm-1">
                            <input class="form-check-input mt-2" type="checkbox">
                        </div>
                    </div>

                    <!-- Tidur Siang -->
                    <div class="row mb-3 align-items-start">
                        <label class="col-sm-2 col-form-label"><strong>Tidur Siang</strong></label>
                        <div class="col-sm-9">
                            <select class="form-select" name="tidur_siang">
                                <option value="">-- Pilih --</option>
                                <option value="Y">Ya</option>
                                <option value="T">Tidak</option>
                            </select>
                            <textarea class="form-control mt-2" rows="2"
                            placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                        </div>
                        <div class="col-sm-1">
                            <input class="form-check-input mt-2" type="checkbox">
                        </div>
                    </div>


                    <div class="row mb-2">
                        <label class="col-sm-12 col-form-label text-primary">
                            <strong>5. Aktivitas dan Latihan</strong>
                        </label>
                    </div>

                    <!-- Olahraga -->
                    <div class="row mb-3 align-items-start">
                        <label class="col-sm-2 col-form-label"><strong>Olahraga Ringan</strong></label>
                        <div class="col-sm-9">
                            <select class="form-select" name="olahraga_ringan">
                                <option value="">-- Pilih --</option>
                                <option value="Y">Ya</option>
                                <option value="T">Tidak</option>
                            </select>
                            <textarea class="form-control mt-2" rows="2"
                            placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                        </div>
                        <div class="col-sm-1">
                            <input class="form-check-input mt-2" type="checkbox">
                        </div>
                    </div>

                    <!-- Jenis dan Frekuensi -->
                    <div class="row mb-3 align-items-start">
                        <label class="col-sm-2 col-form-label"><strong>Jenis dan Frekuensi</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="jenis_frekuensi_olahraga">
                            <textarea class="form-control mt-2" rows="2"
                            placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                        </div>
                        <div class="col-sm-1">
                            <input class="form-check-input mt-2" type="checkbox">
                        </div>
                    </div>

                    <!-- Kegiatan Waktu Luang -->
                    <div class="row mb-3 align-items-start">
                        <label class="col-sm-2 col-form-label"><strong>Kegiatan Waktu Luang</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="kegiatan_waktu_luang">
                            <textarea class="form-control mt-2" rows="2"
                            placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                        </div>
                        <div class="col-sm-1">
                            <input class="form-check-input mt-2" type="checkbox">
                        </div>
                    </div>

                    <!-- Keluhan Aktivitas -->
                    <div class="row mb-3 align-items-start">
                        <label class="col-sm-2 col-form-label"><strong>Keluhan Beraktivitas</strong></label>
                        <div class="col-sm-9">
                            <select class="form-select" name="keluhan_aktivitas">
                                <option value="">-- Pilih --</option>
                                <option value="Y">Ya</option>
                                <option value="T">Tidak</option>
                            </select>
                            <textarea class="form-control mt-2" rows="2"
                            placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                        </div>
                        <div class="col-sm-1">
                            <input class="form-check-input mt-2" type="checkbox">
                        </div>
                    </div>

                    <!-- Kesulitan Pergerakan -->
                    <div class="row mb-3 align-items-start">
                        <label class="col-sm-2 col-form-label"><strong>Kesulitan Pergerakan</strong></label>
                        <div class="col-sm-9">
                            <select class="form-select" name="kesulitan_pergerakan">
                                <option value="">-- Pilih --</option>
                                <option value="Y">Ya</option>
                                <option value="T">Tidak</option>
                            </select>
                            <textarea class="form-control mt-2" rows="2"
                            placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                        </div>
                        <div class="col-sm-1">
                            <input class="form-check-input mt-2" type="checkbox">
                        </div>
                    </div>

                    <!-- Sesak Nafas -->
                    <div class="row mb-3 align-items-start">
                        <label class="col-sm-2 col-form-label"><strong>Sesak Nafas Setelah Aktivitas</strong></label>
                        <div class="col-sm-9">
                            <select class="form-select" name="sesak_nafas">
                                <option value="">-- Pilih --</option>
                                <option value="Y">Ya</option>
                                <option value="T">Tidak</option>
                            </select>
                            <textarea class="form-control mt-2" rows="2"
                            placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                        </div>
                        <div class="col-sm-1">
                        <input class="form-check-input mt-2" type="checkbox">
                </div>
            </div>
        </form>
    </div>
</div>
<div class="card">
             <div class="card-body">
                                
                     <!-- General Form Elements -->
                    <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

                    <h5 class="card-title"><strong>VIII. Pengkajian Psiko Sosial dan Spiritual</strong></h5>


        <!-- Pertanyaan 1 -->
        <div class="row mb-3">
            <label class="col-sm-6 col-form-label"><strong>1. Lansia menyadari dan menerima kondisinya / kesehatannya tidak seperti saat muda</strong></label>
            <div class="col-sm-6 d-flex align-items-center">
                <div class="flex-grow-1">
                    <input type="radio" id="kondisi_lansia_ya" name="kondisi_lansia" value="Y" required>
                    <label for="kondisi_lansia_ya" class="me-3">Ya</label>
                    <input type="radio" id="kondisi_lansia_tidak" name="kondisi_lansia" value="T" required>
                    <label for="kondisi_lansia_tidak">Tidak</label>
                    <textarea class="form-control mt-2" rows="2" placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                </div>
                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" onchange="document.getElementById('commentusiaistri').style.display = this.checked ? 'none' : 'block'">
                    </div>
                </div>
            </div>
        </div>

        <!-- Pertanyaan 2 -->
        <div class="row mb-3">
            <label class="col-sm-6 col-form-label"><strong>2. Lansia menyesuaikan / tidak memaksakan pekerjaan / aktivitas yang dilakukan</strong></label>
            <div class="col-sm-6 d-flex align-items-center">
                <div class="flex-grow-1">
                    <input type="radio" id="penyesuaian_lansia_ya" name="penyesuaian_lansia" value="Y" required>
                    <label for="penyesuaian_lansia_ya" class="me-3">Ya</label>
                    <input type="radio" id="penyesuaian_lansia_tidak" name="penyesuaian_lansia" value="T" required>
                    <label for="penyesuaian_lansia_tidak">Tidak</label>
                    <textarea class="form-control mt-2" rows="2" placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                </div>
                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" onchange="document.getElementById('commentusiaistri').style.display = this.checked ? 'none' : 'block'">
                    </div>
                </div>
                </div>
            
        </div>

        <!-- Pertanyaan 3 -->
        <div class="row mb-3">
            <label class="col-sm-6 col-form-label"><strong>3. Lansia rutin mengikuti kegiatan Prolanis</strong></label>
            <div class="col-sm-6 d-flex align-items-center">
                <div class="flex-grow-1">
                    <input type="radio" id="prolanis_lansia_ya" name="prolanis_lansia" value="Y" required>
                    <label for="prolanis_lansia_ya" class="me-3">Ya</label>
                    <input type="radio" id="prolanis_lansia_tidak" name="prolanis_lansia" value="T" required>
                    <label for="prolanis_lansia_tidak">Tidak</label>
                <textarea class="form-control mt-2" rows="2" placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                </div>
                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" onchange="document.getElementById('commentusiaistri').style.display = this.checked ? 'none' : 'block'">
                    </div>
                </div>
            </div>
        </div>

        <!-- Pertanyaan 4 -->
        <div class="row mb-3">
            <label class="col-sm-6 col-form-label"><strong>4. Lansia rutin memeriksakan kesehatannya di praktik dokter / puskesmas</strong></label>
            <div class="col-sm-6 d-flex align-items-center">
                <div class="flex-grow-1">
                    <input type="radio" id="periksa_kesehatan_lansia_ya" name="periksa_kesehatan_lansia" value="Y" required>
                    <label for="periksa_kesehatan_lansia_ya" class="me-3">Ya</label>
                    <input type="radio" id="periksa_kesehatan_lansia_tidak" name="periksa_kesehatan_lansia" value="T" required>
                    <label for="periksa_kesehatan_lansia_tidak">Tidak</label>
                    <textarea class="form-control mt-2" rows="2" placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                </div>
                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" onchange="document.getElementById('commentusiaistri').style.display = this.checked ? 'none' : 'block'">
                    </div>
                </div>
            </div>
        </div>

        <!-- Pertanyaan 5 -->
        <div class="row mb-3">
            <label class="col-sm-6 col-form-label"><strong>5. Lansia masih mengikuti kegiatan pemeriksaan kesehatan di Posyandu lansia</strong></label>
            <div class="col-sm-6 d-flex align-items-center">
                <div class="flex-grow-1">
                    <input type="radio" id="posyandu_lansia_ya" name="posyandu_lansia" value="Y" required>
                    <label for="posyandu_lansia_ya" class="me-3">Ya</label>
                    <input type="radio" id="posyandu_lansia_tidak" name="posyandu_lansia" value="T" required>
                    <label for="posyandu_lansia_tidak">Tidak</label>
                    <textarea class="form-control mt-2" rows="2" placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                </div>
                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" onchange="document.getElementById('commentusiaistri').style.display = this.checked ? 'none' : 'block'">
                    </div>
                </div>
            </div>
        </div>

        <!-- Pertanyaan 6 -->
        <div class="row mb-3">
            <label class="col-sm-6 col-form-label"><strong>6. Lansia masih sempat mengikuti kegiatan-kegiatan yang dilaksanakan oleh RT</strong></label>
            <div class="col-sm-6 d-flex align-items-center">
                <div class="flex-grow-1">
                    <input type="radio" id="kegiatan_rt_lansia_ya" name="kegiatan_rt_lansia" value="Y" required>
                    <label for="kegiatan_rt_lansia_ya" class="me-3">Ya</label>
                    <input type="radio" id="kegiatan_rt_lansia_tidak" name="kegiatan_rt_lansia" value="T" required>
                    <label for="kegiatan_rt_lansia_tidak">Tidak</label>
                    <textarea class="form-control mt-2" rows="2" placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                </div>
                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" onchange="document.getElementById('commentusiaistri').style.display = this.checked ? 'none' : 'block'">
                    </div>
                </div>
            </div>
        </div>

        <!-- Pertanyaan 7 -->
        <div class="row mb-3">
            <label class="col-sm-6 col-form-label"><strong>7. Gerontik lansia memberikan dukungan dan peduli terhadap kesehatan lansia</strong></label>
            <div class="col-sm-6 d-flex align-items-center">
                <div class="flex-grow-1">
                    <input type="radio" id="dukungan_gerontik_ya" name="dukungan_gerontik" value="Y" required>
                    <label for="dukungan_gerontik_ya" class="me-3">Ya</label>
                    <input type="radio" id="dukungan_gerontik_tidak" name="dukungan_gerontik" value="T" required>
                    <label for="dukungan_gerontik_tidak">Tidak</label>
                    <textarea class="form-control mt-2" rows="2" placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                </div>
                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" onchange="document.getElementById('commentusiaistri').style.display = this.checked ? 'none' : 'block'">
                    </div>
                </div>
            </div>
        </div>

        <!-- Pertanyaan 8 -->
        <div class="row mb-3">
            <label class="col-sm-6 col-form-label"><strong>8. Gerontik mengingatkan pantangan makanan bagi kesehatan lansia</strong></label>
            <div class="col-sm-6 d-flex align-items-center">
                <div class="flex-grow-1">
                    <input type="radio" id="ingatkan_pantangan_ya" name="ingatkan_pantangan" value="Y" required>
                    <label for="ingatkan_pantangan_ya" class="me-3">Ya</label>
                    <input type="radio" id="ingatkan_pantangan_tidak" name="ingatkan_pantangan" value="T" required>
                    <label for="ingatkan_pantangan_tidak">Tidak</label>
                    <textarea class="form-control mt-2" rows="2" placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                </div>
                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" onchange="document.getElementById('commentusiaistri').style.display = this.checked ? 'none' : 'block'">
                    </div>
                </div>
            </div>
        </div>

        <!-- Pertanyaan 9 -->
        <div class="row mb-3">
            <label class="col-sm-6 col-form-label"><strong>9. Lansia merasa senang bila sedang berkumpul dengan anak dan cucu</strong></label>
            <div class="col-sm-6 d-flex align-items-center">
                <div class="flex-grow-1">
                    <input type="radio" id="senang_berkumpul_ya" name="senang_berkumpul" value="Y" required>
                    <label for="senang_berkumpul_ya" class="me-3">Ya</label>
                    <input type="radio" id="senang_berkumpul_tidak" name="senang_berkumpul" value="T" required>
                    <label for="senang_berkumpul_tidak">Tidak</label>
                    <textarea class="form-control mt-2" rows="2" placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                </div>
                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" onchange="document.getElementById('commentusiaistri').style.display = this.checked ? 'none' : 'block'">
                    </div>
                </div>
            </div>
        </div>

        <!-- Pertanyaan 10 -->
        <div class="row mb-3">
            <label class="col-sm-6 col-form-label"><strong>10. Lansia masih rutin menjalankan ibadah</strong></label>
            <div class="col-sm-6 d-flex align-items-center">
                <div class="flex-grow-1">
                    <input type="radio" id="rutin_ibadah_ya" name="rutin_ibadah" value="Y" required>
                    <label for="rutin_ibadah_ya" class="me-3">Ya</label>
                    <input type="radio" id="rutin_ibadah_tidak" name="rutin_ibadah" value="T" required>
                    <label for="rutin_ibadah_tidak">Tidak</label>
                    <textarea class="form-control mt-2" rows="2" placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                </div>
                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" onchange="document.getElementById('commentusiaistri').style.display = this.checked ? 'none' : 'block'">
                    </div>
                </div>
            </div>
        </div>

        <!-- Pertanyaan 11 -->
        <div class="row mb-3">
            <label class="col-sm-6 col-form-label"><strong>11. Lansia merasa bersyukur kepada Tuhan YME dengan kondisinya saat ini</strong></label>
            <div class="col-sm-6 d-flex align-items-center">
                <div class="flex-grow-1">
                    <input type="radio" id="bersyukur_ya" name="bersyukur" value="Y" required>
                    <label for="bersyukur_ya" class="me-3">Ya</label>
                    <input type="radio" id="bersyukur_tidak" name="bersyukur" value="T" required>
                    <label for="bersyukur_tidak">Tidak</label>
                    <textarea class="form-control mt-2" rows="2" placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                </div>
                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" onchange="document.getElementById('commentusiaistri').style.display = this.checked ? 'none' : 'block'">
                    </div>
                </div>
            </div>
        </div>

        <!-- Pertanyaan 12 -->
        <div class="row mb-3">
            <label class="col-sm-6 col-form-label"><strong>12. Lansia menganggap bahwa semakin bertambahnya usia semakin mendekatkan diri kepada Tuhan YME</strong></label>
            <div class="col-sm-6 d-flex align-items-center">
                <div class="flex-grow-1">
                    <input type="radio" id="berkembang_usia_ya" name="berkembang_usia" value="Y" required>
                    <label for="berkembang_usia_ya" class="me-3">Ya</label>
                    <input type="radio" id="berkembang_usia_tidak" name="berkembang_usia" value="T" required>
                    <label for="berkembang_usia_tidak">Tidak</label>
                    <textarea class="form-control mt-2" rows="2" placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                </div>
                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" onchange="document.getElementById('commentusiaistri').style.display = this.checked ? 'none' : 'block'">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="card">
             <div class="card-body">
                                
                     <!-- General Form Elements -->
                    <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

                    <h5 class="card-title"><strong>IX. Pengkajian Status Fungsional</strong></h5>

        <p>
            Pengkajian status fungsional adalah suatu bentuk pengukuran kemampuan seseorang untuk melakukan aktivitas sehari-hari secara mandiri. Penentuan kemandirian fungsional dapat mengidentifikasi kemampuan dan keterbatasan klien sehingga memudahkan pemilihan intervensi yang tepat.
        </p>
        <p>
            Pengkajian ini menggunakan indeks kemandirian Katz untuk aktivitas kehidupan sehari-hari yang berdasarkan pada evaluasi fungsi kemandirian atau tergantung dari klien dalam hal makan, kontinen (defekasi/berkemih), berpindah, ke kamar kecil, pakaian, dan mandi. Silakan dicentang sesuai dengan kondisi lansia.
        </p>
<form>
    <table class="table">
        <thead>
            <tr>
                <th>Kegiatan</th>
                <th>Status Fungsional</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Makan</td>
                <td>
                    <label class="form-check-label">
                        <input class="form-check-input" type="radio" name="makan" value="mandiri" required> Mandiri
                    </label>
                    <label class="form-check-label ms-3">
                        <input class="form-check-input" type="radio" name="makan" value="tergantung"> Tergantung
                    </label>
                </td>
            </tr>
            <tr>
                <td>Kontinen (Defekasi/Berkemih)</td>
                <td>
                    <label class="form-check-label">
                        <input class="form-check-input" type="radio" name="kontinen" value="mandiri" required> Mandiri
                    </label>
                    <label class="form-check-label ms-3">
                        <input class="form-check-input" type="radio" name="kontinen" value="tergantung"> Tergantung
                    </label>
                </td>
            </tr>
            <tr>
                <td>Berpindah</td>
                <td>
                    <label class="form-check-label">
                        <input class="form-check-input" type="radio" name="berpindah" value="mandiri" required> Mandiri
                    </label>
                    <label class="form-check-label ms-3">
                        <input class="form-check-input" type="radio" name="berpindah" value="tergantung"> Tergantung
                    </label>
                </td>
            </tr>
            <tr>
                <td>Ke kamar kecil</td>
                <td>
                    <label class="form-check-label">
                        <input class="form-check-input" type="radio" name="kamar_kecil" value="mandiri" required> Mandiri
                    </label>
                    <label class="form-check-label ms-3">
                        <input class="form-check-input" type="radio" name="kamar_kecil" value="tergantung"> Tergantung
                    </label>
                </td>
            </tr>
            <tr>
                <td>Berpakaian</td>
                <td>
                    <label class="form-check-label">
                        <input class="form-check-input" type="radio" name="berpakaian" value="mandiri" required> Mandiri
                    </label>
                    <label class="form-check-label ms-3">
                        <input class="form-check-input" type="radio" name="berpakaian" value="tergantung"> Tergantung
                    </label>
                </td>
            </tr>
            <tr>
                <td>Mandi</td>
                <td>
                    <label class="form-check-label">
                        <input class="form-check-input" type="radio" name="mandi" value="mandiri" required> Mandiri
                    </label>
                    <label class="form-check-label ms-3">
                        <input class="form-check-input" type="radio" name="mandi" value="tergantung"> Tergantung
                    </label>
                </td>
            </tr>
            <tr>
                <td><strong>Kesimpulan Status Fungsional</strong></td>
                <td>
                    <div class="d-flex align-items-center">
                        <input type="text" class="form-control" name="kesimpulan_status_fungsional" placeholder="Kesimpulan" required>
                        <div class="col-sm-2 d-flex align-items-center ms-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" onchange="document.getElementById('commentusiaistri').style.display = this.checked ? 'none' : 'block'">
                            </div>
                        </div>
                    </div>
                    <textarea class="form-control mt-2" rows="2" placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                </td>
            </tr>
        </tbody>
    </table>

    <table class="table">
        <thead>
            <tr>
                <th>Ketentuan</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>A</td>
                <td>Kemandirian dalam hal makan, kontinen (defekasi/berkemih), berpindah, ke kamar kecil, berpakaian, dan mandi.</td>
            </tr>
            <tr>
                <td>B</td>
                <td>Kemandirian dalam semua hal kecuali satu dari fungsi tersebut.</td>
            </tr>
            <tr>
                <td>C</td>
                <td>Kemandirian dalam semua hal kecuali mandi dan satu fungsi tambahan.</td>
            </tr>
            <tr>
                <td>D</td>
                <td>Kemandirian dalam semua hal kecuali mandi, berpakaian, dan satu fungsi tambahan.</td>
            </tr>
            <tr>
                <td>E</td>
                <td>Kemandirian dalam semua hal kecuali mandi, berpakaian, ke kamar kecil, dan satu fungsi tambahan.</td>
            </tr>
            <tr>
                <td>F</td>
                <td>Kemandirian dalam semua hal kecuali mandi, berpakaian, ke kamar kecil, berpindah, dan satu fungsi tambahan.</td>
            </tr>
            <tr>
                <td>G</td>
                <td>Ketergantungan pada keenam fungsi tersebut.</td>
            </tr>
        </tbody>
    </table>

            
                                
                     <!-- General Form Elements -->
                    <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

                    <h5 class="card-title"><strong>Penjelasan</strong></h5>
    
        <p>Kemandirian berarti tanpa pengawasan, pengarahan, atau bantuan pribadi aktif, kecuali secara spesifik akan digambarkan di bawah ini.</p>
        <p>Pengkajian ini didasarkan pada kondisi aktual klien dan bukan pada kemampuan. Artinya, jika klien menolak untuk melakukan suatu fungsi, dianggap sebagai tidak melakukan fungsi meskipun ia sebenarnya mampu.</p>

        
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Kegiatan</th>
                    <th>Mandiri</th>
                    <th>Tergantung</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Makan</td>
                    <td>Memilih makanan dari piring dan menyuapi sendiri</td>
                    <td>Bantuan dalam hal mengambil makanan dan menyuapinya, tidak makan sama sekali, makan parenteral/enteral.</td>
                </tr>
                <tr>
                    <td>Kontinen (Defekasi/Berkemih)</td>
                    <td>Berkemih dan defekasi sepenuhnya dikendalikan sendiri</td>
                    <td>Inkontinensia parsial atau total, penggunaan kateter, pispot, enema, pembalut (diapers)</td>
                </tr>
                <tr>
                    <td>Berpindah</td>
                    <td>Berpindah ke dan dari tempat tidur, bangkit dari kursi sendiri</td>
                    <td>Bantuan dalam naik dan turun dari tempat tidur atau kursi, tidak melakukan satu atau lebih perpindahan</td>
                </tr>
                <tr>
                    <td>Ke Kamar Kecil</td>
                    <td>Masuk dan keluar kamar kecil, membersihkan genitalia sendiri</td>
                    <td>Menerima bantuan untuk masuk ke kamar kecil dan menggunakan pispot</td>
                </tr>
                <tr>
                    <td>Berpakaian</td>
                    <td>Memilih baju dari lemari, memakai dan melepaskan pakaian, mengancing atau mengikat pakaian</td>
                    <td>Tidak dapat memakai baju sendiri atau sebagian</td>
                </tr>
                <tr>
                    <td>Mandi</td>
                    <td>Bantuan hanya pada satu bagian tubuh (seperti punggung atau ekstremitas yang tidak mampu) atau mandi sepenuhnya sendiri</td>
                    <td>Bantuan mandi lebih dari satu bagian tubuh, bantuan masuk dan keluar dari bak mandi, tidak mandi sendiri</td>
                </tr>
            </tbody>
        </table>
    </div>
</form>
    </div>
</div>

<div class="card">
             <div class="card-body">
                                
                     <!-- General Form Elements -->
                    <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

                    <h5 class="card-title"><strong>X. Skala Depresi Geriatric</strong></h5>

            <h6>Pengkajian ini menggunakan skala depresi geriatric bentuk singkat dari Yesavage (1983) 
                yang instrumennya disusun secara khusus digunakan pada lanjut usia untuk memeriksa depresi. 
                Jawaban pertanyaan sesuai dengan indikasi yang dinilai. Nilai 5 atau lebih dapat menandakan depresi.</h6>

            <form action="#">
                <!-- Pertanyaan 1 - 10 -->
                <div class="question">
                    <label>1. Apakah pada dasarnya Anda puas dengan kehidupan Anda?</label><br>
                    <input type="radio" id="q1_no" name="q1" value="Tidak">
                    <label for="q1_no">Tidak</label>
                    <input type="radio" id="q1_yes" name="q1" value="Ya">
                    <label for="q1_yes">Ya</label>
                </div>
                <div class="question">
                    <label>2. Sudahkah Anda banyak menghentikan aktivitas dan minat Anda?</label><br>
                    <input type="radio" id="q2_no" name="q2" value="Tidak">
                    <label for="q2_no">Tidak</label>
                    <input type="radio" id="q2_yes" name="q2" value="Ya">
                    <label for="q2_yes">Ya</label>
                </div>
                <div class="question">
                    <label>3. Apakah Anda merasa bahwa hidup Anda kosong?</label><br>
                    <input type="radio" id="q3_no" name="q3" value="Tidak">
                    <label for="q3_no">Tidak</label>
                    <input type="radio" id="q3_yes" name="q3" value="Ya">
                    <label for="q3_yes">Ya</label>
                </div>
                <div class="question">
                    <label>4. Apakah Anda sering bosan?</label><br>
                    <input type="radio" id="q4_no" name="q4" value="Tidak">
                    <label for="q4_no">Tidak</label>
                    <input type="radio" id="q4_yes" name="q4" value="Ya">
                    <label for="q4_yes">Ya</label>
                </div>
                <div class="question">
                    <label>5. Apakah Anda banyak berharap pada masa depan?</label><br>
                    <input type="radio" id="q5_no" name="q5" value="Tidak">
                    <label for="q5_no">Tidak</label>
                    <input type="radio" id="q5_yes" name="q5" value="Ya">
                    <label for="q5_yes">Ya</label>
                </div>
                <div class="question">
                    <label>6. Apakah Anda takut sesuatu akan terjadi pada Anda?</label><br>
                    <input type="radio" id="q6_no" name="q6" value="Tidak">
                    <label for="q6_no">Tidak</label>
                    <input type="radio" id="q6_yes" name="q6" value="Ya">
                    <label for="q6_yes">Ya</label>
                </div>
                <div class="question">
                    <label>7. Apakah Anda merasa terganggu dengan pemikiran bahwa Anda tidak bisa lepas dari pikiran yang sama?</label><br>
                    <input type="radio" id="q7_no" name="q7" value="Tidak">
                    <label for="q7_no">Tidak</label>
                    <input type="radio" id="q7_yes" name="q7" value="Ya">
                    <label for="q7_yes">Ya</label>
                </div>
                <div class="question">
                    <label>8. Apakah Anda takut bahwa suatu hal yang buruk akan menimpa Anda?</label><br>
                    <input type="radio" id="q8_no" name="q8" value="Tidak">
                    <label for="q8_no">Tidak</label>
                    <input type="radio" id="q8_yes" name="q8" value="Ya">
                    <label for="q8_yes">Ya</label>
                </div>
                <div class="question">
                    <label>9. Apakah Anda merasa gembira dalam sebagian besar waktu Anda?</label><br>
                    <input type="radio" id="q9_no" name="q9" value="Tidak">
                    <label for="q9_no">Tidak</label>
                    <input type="radio" id="q9_yes" name="q9" value="Ya">
                    <label for="q9_yes">Ya</label>
                </div>
                <div class="question">
                    <label>10. Apakah Anda merasa tidak mungkin tertolong?</label><br>
                    <input type="radio" id="q10_no" name="q10" value="Tidak">
                    <label for="q10_no">Tidak</label>
                    <input type="radio" id="q10_yes" name="q10" value="Ya">
                    <label for="q10_yes">Ya</label>
                </div>

                <!-- Kesimpulan Skala Depresi -->
                <div style="text-align: left; margin: 20px;">
    <p>Nilai 1 poin untuk setiap respon yang cocok dengan jawaban "Ya".</p>
    <p>Normal (5±4), Depresi Ringan (15±6), Depresi Berat (23±5)</p>
</div>

<div style="text-align: left; margin-top: 20px;">
    <p><em>Kesimpulan Skala Depresi Lansia:</em></p>
    <form>
        <div class="d-flex">
            <textarea name="kesimpulan" rows="4" cols="50" placeholder="" class="form-control"></textarea>
            <div class="col-sm-2 d-flex align-items-center ms-2">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" onchange="document.getElementById('commentusiaistri').style.display = this.checked ? 'none' : 'block'">
                </div>
            </div>
        </div>
        <textarea class="form-control mt-2" rows="2" placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
    </form>
</div>
                </div>

            </form>
        </div>
    </div>
</div>

<div class="card">
             <div class="card-body">
                                
                     <!-- General Form Elements -->
                    <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

                    <h5 class="card-title"><strong>XI. APGAR Gerontik</strong></h5>

            <p>APGAR Gerontik ditujukan untuk mengkaji fungsi sosial lansia. A (Adaptasi), P (Partnership), G (Growth), A (Affection), R (Resolve)</p>
            <p><strong>Penilaian:</strong></p>
            <ul>
                <li>&lt; 3: disfungsi Gerontik sangat tinggi</li>
                <li>4-6: disfungsi Gerontik sedang</li>
                <li>7-8: ringan</li>
                <li>9-10: normal</li>
            </ul>
            <p><strong>Petunjuk:</strong> Silakan memberikan tanda centang pada pilihan jawaban yang sesuai dengan pernyataan Lansia.</p>

            <form action="#">
                <!-- Pertanyaan A -->
                <div class="question">
                    <label for="A">A. Saya puas bisa kembali pada Gerontik (teman) saya untuk membantu saya bila suatu waktu ada kondisi yang menyusahkan saya:</label><br>
                    <input type="radio" id="A_selalu" name="A" value="2">
                    <label for="A_selalu">Selalu (2)</label>
                    <input type="radio" id="A_kadang" name="A" value="1">
                    <label for="A_kadang">Kadang (1)</label>
                    <input type="radio" id="A_tidak_pernah" name="A" value="0">
                    <label for="A_tidak_pernah">Tidak pernah (0)</label>
                </div>

                <!-- Pertanyaan P -->
                <div class="question">
                    <label for="P">P. Saya puas dengan cara Gerontik (teman) saya membicarakan sesuatu dan mengungkapkan masalah dengan saya:</label><br>
                    <input type="radio" id="P_selalu" name="P" value="2">
                    <label for="P_selalu">Selalu (2)</label>
                    <input type="radio" id="P_kadang" name="P" value="1">
                    <label for="P_kadang">Kadang (1)</label>
                    <input type="radio" id="P_tidak_pernah" name="P" value="0">
                    <label for="P_tidak_pernah">Tidak pernah (0)</label>
                </div>

                <!-- Pertanyaan G -->
                <div class="question">
                    <label for="G">G. Saya puas bahwa Gerontik (teman) saya menerima dan mendukung keinginan untuk melakukan aktivitas:</label><br>
                    <input type="radio" id="G_selalu" name="G" value="2">
                    <label for="G_selalu">Selalu (2)</label>
                    <input type="radio" id="G_kadang" name="G" value="1">
                    <label for="G_kadang">Kadang (1)</label>
                    <input type="radio" id="G_tidak_pernah" name="G" value="0">
                    <label for="G_tidak_pernah">Tidak pernah (0)</label>
                </div>

                <!-- Pertanyaan A2 -->
                <div class="question">
                    <label for="A2">A. Saya puas dengan cara Gerontik (teman) saya mengekspresikan afek dan berespon terhadap emosi saya seperti marah, sedih, atau mencintai:</label><br>
                    <input type="radio" id="A2_selalu" name="A2" value="2">
                    <label for="A2_selalu">Selalu (2)</label>
                    <input type="radio" id="A2_kadang" name="A2" value="1">
                    <label for="A2_kadang">Kadang (1)</label>
                    <input type="radio" id="A2_tidak_pernah" name="A2" value="0">
                    <label for="A2_tidak_pernah">Tidak pernah (0)</label>
                </div>

                <!-- Pertanyaan R -->
                <div class="question">
                    <label for="R">R. Saya puas dengan cara teman saya menyediakan waktu secara bersama-sama:</label><br>
                    <input type="radio" id="R_selalu" name="R" value="2">
                    <label for="R_selalu">Selalu (2)</label>
                    <input type="radio" id="R_kadang" name="R" value="1">
                    <label for="R_kadang">Kadang (1)</label>
                    <input type="radio" id="R_tidak_pernah" name="R" value="0">
                    <label for="R_tidak_pernah">Tidak pernah (0)</label>
                </div>
            </form>

            <!-- Kesimpulan APGAR Gerontik -->
            <div style="text-align: left; margin-top: 20px;">
    <p><em>Kesimpulan APGAR Gerontik:</em></p>
    <form>
        <div class="d-flex align-items-center">
            <textarea name="kesimpulan_apgar" rows="4" cols="50" placeholder="" class="form-control"></textarea>
            <div class="col-sm-2 d-flex justify-content-start align-items-center ms-2">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" onchange="document.getElementById('commentusiaistri').style.display = this.checked ? 'none' : 'block'">
                </div>
            </div>
        </div>
        <textarea class="form-control mt-2" rows="2" placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
    </form>
</div>
        </div>
    </div>
</div>
<div class="card">
             <div class="card-body">
                                
                     <!-- General Form Elements -->
                    <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

                    <h5 class="card-title"><strong>XII. Pemeriksaan Short Portable Status Questionnaire (SPMSQ)</strong></h5>

            <p>Digunakan untuk mendeteksi adanya tingkat gangguan intelektual/memori. Catatlah jumlah kesalahan dari semua pertanyaan, berikan tanda centang pada kolom B (Jawaban lansia benar) atau S (Jawaban lansia salah).</p>

            <form action="#">
                <table style="width:100%; margin-top: 20px; text-align: left;">
                    <thead>
                        <tr>
                            <th>B</th>
                            <th>S</th>
                            <th>No</th>
                            <th>Pertanyaan</th>
                            <th>Jawaban Lansia</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Pertanyaan 1 -->
                        <tr>
                            <td><input type="radio" name="q1" value="B"></td>
                            <td><input type="radio" name="q1" value="S"></td>
                            <td>1</td>
                            <td>Tanggal berapa hari ini? (Hari/Tanggal/Tahun)</td>
                            <td><input type="text" name="jawaban1"></td>
                        </tr>

                        <!-- Pertanyaan 2 -->
                        <tr>
                            <td><input type="radio" name="q2" value="B"></td>
                            <td><input type="radio" name="q2" value="S"></td>
                            <td>2</td>
                            <td>Hari apa sekarang?</td>
                            <td><input type="text" name="jawaban2"></td>
                        </tr>

                        <!-- Pertanyaan 3 -->
                        <tr>
                            <td><input type="radio" name="q3" value="B"></td>
                            <td><input type="radio" name="q3" value="S"></td>
                            <td>3</td>
                            <td>Apa nama tempat/kelurahan ini?</td>
                            <td><input type="text" name="jawaban3"></td>
                        </tr>

                        <!-- Pertanyaan 4 -->
                        <tr>
                            <td><input type="radio" name="q4" value="B"></td>
                            <td><input type="radio" name="q4" value="S"></td>
                            <td>4</td>
                            <td>Dimana alamat lengkap Anda?</td>
                            <td><input type="text" name="jawaban4"></td>
                        </tr>

                        <!-- Pertanyaan 5 -->
                        <tr>
                            <td><input type="radio" name="q5" value="B"></td>
                            <td><input type="radio" name="q5" value="S"></td>
                            <td>5</td>
                            <td>Berapa umur Anda?</td>
                            <td><input type="text" name="jawaban5"></td>
                        </tr>

                        <!-- Pertanyaan 6 -->
                        <tr>
                            <td><input type="radio" name="q6" value="B"></td>
                            <td><input type="radio" name="q6" value="S"></td>
                            <td>6</td>
                            <td>Kapan Anda lahir?</td>
                            <td><input type="text" name="jawaban6"></td>
                        </tr>

                        <!-- Pertanyaan 7 -->
                        <tr>
                            <td><input type="radio" name="q7" value="B"></td>
                            <td><input type="radio" name="q7" value="S"></td>
                            <td>7</td>
                            <td>Presiden Indonesia sekarang?</td>
                            <td><input type="text" name="jawaban7"></td>
                        </tr>

                        <!-- Pertanyaan 8 -->
                        <tr>
                            <td><input type="radio" name="q8" value="B"></td>
                            <td><input type="radio" name="q8" value="S"></td>
                            <td>8</td>
                            <td>Siapa nama presidennya sebelumnya?</td>
                            <td><input type="text" name="jawaban8"></td>
                        </tr>

                        <!-- Pertanyaan 9 -->
                        <tr>
                            <td><input type="radio" name="q9" value="B"></td>
                            <td><input type="radio" name="q9" value="S"></td>
                            <td>9</td>
                            <td>Siapa nama kecil ibu Anda?</td>
                            <td><input type="text" name="jawaban9"></td>
                        </tr>

                        <!-- Pertanyaan 10 -->
                        <tr>
                            <td><input type="radio" name="q10" value="B"></td>
                            <td><input type="radio" name="q10" value="S"></td>
                            <td>10</td>
                            <td>Perhitungan: 20-3 = … kemudian hasilnya dikurangi 3 terus hasilnya lagi dikurang 3 sampai mendapat angka 0.</td>
                            <td><input type="text" name="jawaban10"></td>
                        </tr>
                    </tbody>
                </table>
            

            <!-- Kesimpulan Pemeriksaan SPMSQ -->
           <div style="text-align: left; margin-top: 20px;">
    <p><em>Kesimpulan Pemeriksaan SPMSQ:</em></p>
    <form>
        <div class="d-flex align-items-center">
            <textarea name="kesimpulan_spmsq" rows="4" cols="50" placeholder="Masukkan kesimpulan di sini..." class="form-control"></textarea>
            <div class="col-sm-2 d-flex justify-content-start align-items-center ms-2">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" onchange="document.getElementById('commentusiaistri').style.display = this.checked ? 'none' : 'block'">
                </div>
            </div>
        </div>
        <textarea class="form-control mt-2" rows="2" placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
    </form>
</div>
 
                     <!-- General Form Elements -->
                    <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

                    <h5 class="card-title"><strong>Kriteria Penilaian</strong></h5>
        <!-- Kriteria Penilaian -->
       
            <p>Kesalahan 0-2 = Fungsi intelektual utuh</p>
            <p>Kesalahan 3-4 = Gangguan intelektual ringan</p>
            <p>Kesalahan 5-7 = Gangguan intelektual sedang</p>
            <p>Kesalahan 8-10 = Gangguan intelektual berat</p>
        </div>
    </div>
</div>
<div class="card">
             <div class="card-body">
                                
                     <!-- General Form Elements -->
                    <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

                    <h5 class="card-title"><strong>XIII. Skala Jatuh Pada Lansia - Ontario Modified Stratify-Sydney Scoring</strong></h5>

            <div class="container">
                <form action="#">
                    <table border="1" cellpadding="10" cellspacing="0" style="width:100%; margin-top: 20px; text-align: left;">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Parameter</th>
                                <th>Skrining</th>
                                <th>Jawaban</th>
                                <th>Keterangan Nilai</th>
                                <th>Skor</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Riwayat Jatuh -->
                            <tr>
                                <td>1</td>
                                <td>Riwayat jatuh</td>
                                <td>1. Apakah pasien datang ke rumah sakit karena jatuh?</td>
                                <td>
                                    <input type="radio" name="riwayat_jatuh_1" value="Y"> Ya
                                    <input type="radio" name="riwayat_jatuh_1" value="T"> Tidak
                                </td>
                                <td>Jika jawaban "Ya" = 6</td>
                                <td><input type="number" name="skor_riwayat_jatuh_1" placeholder="Masukkan skor" min="0" max="6"></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td>2. Jika tidak, apakah klien pernah jatuh dalam dua bulan terakhir?</td>
                                <td>
                                    <input type="radio" name="riwayat_jatuh_2" value="Y"> Ya
                                    <input type="radio" name="riwayat_jatuh_2" value="T"> Tidak
                                </td>
                                <td></td>
                                <td></td>
                            </tr>

                            <!-- Status Mental -->
                            <tr>
                                <td>2</td>
                                <td>Status mental</td>
                                <td>1. Apakah pasien derilium (tidak dapat membuat keputusan, gangguan daya ingat)?</td>
                                <td>
                                    <input type="radio" name="status_mental_1" value="Y"> Ya
                                    <input type="radio" name="status_mental_1" value="T"> Tidak
                                </td>
                                <td>Jika jawaban "Ya" = 14</td>
                                <td><input type="number" name="skor_status_mental_1" placeholder="Masukkan skor" min="0" max="14"></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td>2. Apakah pasien disorientasi (salah menyebutkan waktu dan tempat)?</td>
                                <td>
                                    <input type="radio" name="status_mental_2" value="Y"> Ya
                                    <input type="radio" name="status_mental_2" value="T"> Tidak
                                </td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td>3. Apakah pasien mengalami agitasi (ketakutan, gelisah, dan cemas)?</td>
                                <td>
                                    <input type="radio" name="status_mental_3" value="Y"> Ya
                                    <input type="radio" name="status_mental_3" value="T"> Tidak
                                </td>
                                <td></td>
                                <td></td>
                            </tr>

                            <!-- Penglihatan -->
                            <tr>
                                <td>3</td>
                                <td>Penglihatan</td>
                                <td>1. Apakah pasien menggunakan kacamata?</td>
                                <td>
                                    <input type="radio" name="penglihatan_1" value="Y"> Ya
                                    <input type="radio" name="penglihatan_1" value="T"> Tidak
                                </td>
                                <td>Jika jawaban "Ya" = 1</td>
                                <td><input type="number" name="skor_penglihatan_1" placeholder="Masukkan skor" min="0" max="1"></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td>2. Apakah pasien mengeluh penglihatan buram?</td>
                                <td>
                                    <input type="radio" name="penglihatan_2" value="Y"> Ya
                                    <input type="radio" name="penglihatan_2" value="T"> Tidak
                                </td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td>3. Apakah pasien mempunyai glaukoma/katarak/degenerasi makula?</td>
                                <td>
                                    <input type="radio" name="penglihatan_3" value="Y"> Ya
                                    <input type="radio" name="penglihatan_3" value="T"> Tidak
                                </td>
                                <td></td>
                                <td></td>
                            </tr>

                            <!-- Kebiasaan Berkemih -->
                            <tr>
                                <td>4</td>
                                <td>Kebiasaan Berkemih</td>
                                <td>Apakah terdapat perubahan perilaku berkemih (urgensi, frekuensi, inkontinensia, nokturia)?</td>
                                <td>
                                    <input type="radio" name="berkemih" value="Y"> Ya
                                    <input type="radio" name="berkemih" value="T"> Tidak
                                </td>
                                <td>Jika jawaban "Ya" = 2</td>
                                <td><input type="number" name="skor_berkemih" placeholder="Masukkan skor" min="0" max="2"></td>
                            </tr>

                            <!-- Transfer (Berpindah Tempat) -->
                            <tr>
                                <td>5</td>
                                <td>Transfer (Berpindah Tempat)</td>
                                <td>1. Mandiri (boleh memakai alat bantu):</td>
                                <td><input type="radio" name="transfer_1" value="0"> 0</td>
                                <td>Jumlah (gabungan nilai) </td>
                                <td><input type="number" name="skor_transfer_1" placeholder="Masukkan skor" min="0" max="0"></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td>2. Memerlukan sedikit bantuan orang dewasa (1 orang):</td>
                                <td><input type="radio" name="transfer_2" value="1"> 1</td>
                                <td>transfer dan mobilitas</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td>3. Bantuan yang nyata 2 orang:</td>
                                <td><input type="radio" name="transfer_3" value="2"> 2</td>
                                <td>Jika nilai 0-3 maka skornya 0</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td>4. Tidak dapat duduk seimbang, perlu bantuan total:</td>
                                <td><input type="radio" name="transfer_4" value="3"> 3</td>
                                <td>Jika nilai 4-6 maka skornya 7</td>
                                <td></td>
                            </tr>

                            <!-- Mobilitas -->
                            <tr>
                                <td>6</td>
                                <td>Mobilitas</td>
                                <td>1. Mandiri (boleh menggunakan alat):</td>
                                <td><input type="radio" name="mobilitas_1" value="0"> 0</td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td>2. Berjalan dengan bantuan 1 orang (fisik/verbal):</td>
                                <td><input type="radio" name="mobilitas_2" value="1"> 1</td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td>3. Menggunakan kursi roda:</td>
                                <td><input type="radio" name="mobilitas_3" value="2"> 2</td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td>4. Imobilisasi:</td>
                                <td><input type="radio" name="mobilitas_4" value="3"> 3</td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>

                    <p>0-5  : risiko rendah,   6-16 : risiko sedang,  17-30 : risiko tinggi</p>

                    <!-- Kesimpulan Penilaian -->
                    <div style="text-align: left; margin-top: 20px;">
    <p><em>Kesimpulan Penilaian:</em></p>
    <form>
        <div class="d-flex align-items-center">
            <textarea name="kesimpulan_penilaian" rows="4" cols="50" placeholder="Masukkan kesimpulan di sini..." class="form-control"></textarea>
            <div class="col-sm-2 d-flex justify-content-start align-items-center ms-2">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" onchange="document.getElementById('commentusiaistri').style.display = this.checked ? 'none' : 'block'">
                </div>
            </div>
        </div>
        <textarea class="form-control mt-2" rows="2" placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
    </form>
</div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="card">
             <div class="card-body">
                                
                     <!-- General Form Elements -->
                    <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

                    <h5 class="card-title"><strong>XIV. Format Klasifikasi Data</strong></h5>


       
            <section class="section dashboard">
     
                <!-- General Form Elements -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

                <!-- Bagian No. DX -->

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>DATA SUBJEKTIF</strong></label>

                        <div class="col-sm-9">
                            <textarea name="nodx" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentnodx" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 

                 

                <!-- Bagian Implementasi -->

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>DATA OBJEKTIF</strong></label>

                        <div class="col-sm-9">
                            <textarea name="implementasi" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentimplementasi" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
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

                    <h5 class="card-title mt-2"><strong>Format Klasifikasi Data</strong></h5>

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
                                <th class="text-center">DATA SUBJEKTIF </th>
                                <th class="text-center">DATA OBJEKTIF</th>
                        </tr>
                        </thead>

                    <tbody>

                    <?php
                    if(!empty($data)){
                        foreach($data as $row){
                            echo "<tr>
                            <td>".$row['DATA_SUBJEKTIF ']."</td>
                            <td>".$row['DATA_OBJEKTIF']."</td>
                            </tr>";
                        }
                    }
                    ?>

                    </tbody>
                    </table>    
        </div>
    </div>
</div>

<section class="section dashboard">
        <div class="card">
            <div class="card-body">
              <h5 class="card-title mb-1"><strong>XV. Analisa Data</strong></h5>

                <!-- General Form Elements -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

                <!-- Bagian No. DX -->

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>No</strong></label>

                        <div class="col-sm-9">
                            <textarea name="no" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentnodx" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 

                <!-- Bagian Hari/Tanggal -->

                    <div class="row mb-3">
                        <label for="hari_tgl" class="col-sm-2 col-form-label"><strong>DATA</strong></label>

                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="DATA" name="DATA">
                            
                             <!-- comment -->
                            <textarea class="form-control mt-2" id="commenthari_tgl" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>
                <!-- Bagian Jam -->

                    <div class="row mb-3">
                        <label for="jam" class="col-sm-2 col-form-label"><strong>ETILOGI</strong></label>

                        <div class="col-sm-9">
                             <input type="text" class="form-control" id="ETILOGI" name="ETILOGI">
                            
                             <!-- comment -->
                            <textarea class="form-control mt-2" id="commentjam" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 

                <!-- Bagian Implementasi -->

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>MASALAH</strong></label>

                        <div class="col-sm-9">
                            <textarea name="MASALAH" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentimplementasi" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
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

                    <h5 class="card-title mt-2"><strong>Analisa Data</strong></h5>

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
                                <th class="text-center">DATA</th>
                                <th class="text-center">ETILOGI</th>
                                <th class="text-center">MASALAH</th>
                        </tr>
                        </thead>

                    <tbody>

                    <?php
                    if(!empty($data)){
                        foreach($data as $row){
                            echo "<tr>
                            <td>".$row['NO']."</td>
                            <td>".$row['DATA']."</td>
                            <td>".$row['ETILOGI']."</td>
                            <td>".$row['MASALAH']."</td>
                            </tr>";
                        }
                    }
                    ?>

                    </tbody>
                    </table>    

    

<?php include "tab_navigasi.php"; ?>