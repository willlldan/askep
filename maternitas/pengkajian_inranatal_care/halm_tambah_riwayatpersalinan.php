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
              <h5 class="card-title mb-1"><strong>RIWAYAT PERSALINAN</strong></h5>

                <!-- General Form Elements -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">
                
                <!-- Bagian Jenis Kelamin -->
                 <div class="row mb-3">
                    <label for="jeniskelamin" class="col-sm-2 col-form-label"><strong>Jenis Kelamin</strong></label> 
                    <div class="col-sm-10">
                    <select class="form-select" name="jeniskelamin">
                            <option value="">Pilih</option>
                            <option value="Perempuan">Perempuan</option>
                            <option value="Laki-laki">Laki-laki</option>
                            </select>
                         </div>
                    </div>

                <!-- Bagian Cara Lahir -->
                <div class="row mb-3">
                    <label for="caralahir" class="col-sm-2 col-form-label"><strong>Cara Lahir</strong></label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="caralahir">
                         </div>
                    </div> 
                    
                <!-- Bagian BB Lahir (gram) -->
                <div class="row mb-3">
                    <label for="bblahir" class="col-sm-2 col-form-label"><strong>BB Lahir (gram)</strong></label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="bblahir">
                         </div>
                    </div>     

                <!-- Bagian Keadaan -->
                <div class="row mb-3">
                    <label for="keadaan" class="col-sm-2 col-form-label"><strong>Keadaan</strong></label>
                    <div class="col-sm-10">
                        <textarea name="keadaan" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                         </div>
                    </div>
                    
                <!-- Bagian Umur -->
                <div class="row mb-3">
                    <label for="bbtbbayi" class="col-sm-2 col-form-label"><strong>Umur</strong></label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="umur">
                         </div>
                    </div> 

                <!-- Bagian Button -->    
                <div class="row mb-3">
                    <div class="col-sm-12 justify-content-end d-flex">
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
                        <div class="col-sm-10">
                            <select class="form-select" name="kelasprenatal">
                                <option value="">Pilih</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                            </div>  
                        </div>  

            <!-- Bagian Kunjungan ANC -->
                <div class="row mb-3">
                    <label for="anc" class="col-sm-2 col-form-label"><strong>Jumlah Kunjungan ANC pada kehamilan ini</strong></label>
                    <div class="col-sm-10">
                        <div class="input-group">
                            <input type="text" class="form-control" name="anc">
                            <span class="input-group-text">kali</span>
                    </div>
                         </div>
                    </div>   
                    
            <!-- Bagian Masalah Kehamilan Yang lalu -->
                        <div class="row mb-3">
                            <label for="masalahkehamilanlalu" class="col-sm-2 col-form-label"><strong>Masalah Kehamilan yang Lalu</strong></label>
                            <div class="col-sm-10">
                               <textarea name="masalahkehamilanlalu" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                         </div>
                    </div>   
                    
            <!-- Bagian Masalah Kehamilan Sekarang -->
                        <div class="row mb-3">
                            <label for="masalahkehamilansekarang" class="col-sm-2 col-form-label"><strong>Masalah Kehamilan Sekarang</strong></label>
                            <div class="col-sm-10">
                               <textarea name="masalahkehamilansekarang" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                         </div>
                    </div>
                    
            <!-- Bagian Rencana KB -->
                        <div class="row mb-3">
                            <label for="rencanakb" class="col-sm-2 col-form-label"><strong>Rencana KB</strong></label>
                            <div class="col-sm-10">
                               <input type="text" class="form-control" name="rencanakb">
                         </div>
                    </div>
                    
            <!-- Bagian Makanan Bayi -->
                        <div class="row mb-3">

                        <label class="col-sm-2 col-form-label"><strong>Makanan Bayi Sebelumnya</strong></label>

                        <!-- Field -->
                        <div class="col-sm-10">
                            <select class="form-select" name="makananbayi">
                                <option value="">Pilih</option>
                                <option value="ASI">ASI</option>
                                <option value="PASI">PASI</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>

                    </div>  
                    
            <!-- Bagian Bayi Lahir -->
                        <div class="row mb-3">
                            <label for="bayilahir" class="col-sm-2 col-form-label"><strong>Setelah bayi lahir, siapa yang diharapkan membantu?</strong></label>
                            <div class="col-sm-10">
                                <small class="form-text" style="color: red;">Suami/Teman/Orang Tua? Hasil:</small>
                               <textarea name="bayilahir" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                         </div>
                    </div>  
                    
            <!-- Bagian Masalah Persalinan -->
                        <div class="row mb-3">
                            <label for="masalahpersalinan" class="col-sm-2 col-form-label"><strong>Masalah Persalinan</strong></label>
                            <div class="col-sm-10">
                                <small class="form-text" style="color: red;">Ada/Tidak? Jelaskan.</small>
                               <textarea name="masalahpersalinan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                    
                </div>
            </div>
                    

                <h5 class="card-title"><strong>RIWAYAT PERSALINAN SEKARANG</strong></h5>
            
                <!-- Bagian Keluhan Utama -->
                        <div class="row mb-3">
                            <label for="keluhanutama" class="col-sm-2 col-form-label"><strong>Keluhan Utama</strong></label>
                            <div class="col-sm-10">
                               <textarea name="keluhanutama" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                         </div>
                    </div> 

                <!-- Bagian Riwayat Keluhan Utama -->
                        <div class="row mb-3">
                            <label for="riwayatkeluhanutama" class="col-sm-2 col-form-label"><strong>Riwayat Keluhan Utama</strong></label>
                            <div class="col-sm-10">
                               <textarea name="riwayatkeluhanutama" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                         </div>
                    </div>  

                <!-- Bagian Mulai Persalinan -->
                        <div class="row mb-3">
                            <label for="mulaipersalinan" class="col-sm-2 col-form-label"><strong>Mulai Persalinan</strong></label>
                            <div class="col-sm-10">
                                <small class="form-text" style="color: red;">Kontraksi (teratur/tidak), interval, lama. Hasil:</small>
                               <textarea name="mulaipersalinan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
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
                        <div class="col-sm-4">
                            <div class="input-group">
                                <input type="text" class="form-control" name="tekanandarah">
                                <span class="input-group-text">mmHg</span>
                        </div>    
                    </div>
                                
                    <!-- Nadi -->
                    <label class="col-sm-2 col-form-label"><strong>Nadi</strong></label>
                    <div class="col-sm-4">
                        <div class="input-group">
                                <input type="text" class="form-control" name="nadi">
                                <span class="input-group-text">x/menit</span>
                        </div> 
                    </div>

                    </div>
              
                    <!-- Suhu -->
                        <div class="row mb-3 align-items-center">
                            <label class="col-sm-2 col-form-label"><strong>Suhu</strong></label>
                            <div class="col-sm-4">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="suhu">
                                    <span class="input-group-text">°C</span>
                            </div>    
                        </div>

                    <!-- RR -->
                    <label class="col-sm-2 col-form-label"><strong>RR</strong></label>
                    <div class="col-sm-4">
                        <div class="input-group">
                                <input type="text" class="form-control" name="rr">
                                <span class="input-group-text">x/menit</span>
                            </div>
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

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Rontok (Ya/Tidak), Kulit Kepala (Bersih/Kotor), Nyeri Tekan (Ya/Tidak). Hasil:</small>
                            <textarea name="kepaladanrambut" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
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

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Ya/Tidak, Area ...</small>
                            <textarea name="inspeksiwajah" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                         </div>
                    </div> 

                    <!-- Masalah Khusus -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Masalah Khusus</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Ekspresi wajah, apakah pucat dan bengkak. Hasil:</small>
                            <textarea name="masalahwajah" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
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

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Anemis/An-anemis. Hasil:</small>
                           <textarea name="konjungtiva" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                         </div>
                    </div> 

                    <!-- Sklera -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Sklera</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Ikterik/An-ikterik. Hasil:</small>
                            <textarea name="sklera" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
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

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Lembab/Kering. Hasil:</small>
                            <textarea name="mukosabibir" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                         </div>
                    </div> 

                    <!-- Sariawan -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Sariawan</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">(Ada/Tidak Ada). Hasil:</small>
                            <textarea name="sariawan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                         </div>
                    </div> 

                    <!-- Gigi Berlubang -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Gigi Berlubang</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Ya/Tidak. Hasil:</small>
                            <textarea name="gigiberlubang" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                         </div>
                    </div> 

                     <!-- Masalah Khusus -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Masalah Khusus</strong></label>

                            <div class="col-sm-10">
                                <textarea name="masalahkhususmulut" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
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

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Ya/Tidak. Hasil:</small>
                            <textarea name="distensi" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                         </div>
                    </div> 

                    <!-- Kelenjar Tiroid -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Pembesaran Kelenjar Tiroid</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Ya/Tidak. Hasil:</small>
                            <textarea name="kelenjartiroid" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                         </div>
                    </div> 

                    <!-- Nyeri Menelan -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Nyeri Menelan</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Ya/Tidak. Hasil:</small>
                            <textarea name="nyerimenelan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                         </div>
                    </div> 

                     <!-- Riwayat Masalah Khusus -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Masalah Khusus</strong></label>

                            <div class="col-sm-10">
                                <textarea name="masalahkhususleher" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
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

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Normal atau apakah terdapat Mur-mur dan Gallop. Hasil:</small>
                            <textarea name="bunyijantung" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                         </div>
                    </div> 

                    <!-- Masalah Khusus -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Masalah Khusus</strong></label>

                            <div class="col-sm-10">
                                <textarea name="masalahkhususbunyijantung" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                         </div>
                    </div>

                    <!-- Sistem Pernapasan -->
                  
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Sistem Pernapasan</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Suara Napas (Vesikuler/Wheezing/Ronkhi). Hasil:</small>
                            <textarea name="sistempernapasan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                         </div>
                    </div> 

                    <!-- Masalah Khusus -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Masalah Khusus</strong></label>

                            <div class="col-sm-10">
                                <textarea name="masalahkhusussistempernapasan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
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

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Ya/Tidak. Hasil:</small>
                            <textarea name="pengeluaranasi" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                         </div>
                    </div> 

                    <!-- Inspeksi Puting -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Puting</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">(Eksverted/Inverted/Platnipple). Hasil:</small>
                            <textarea name="puting" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                         </div>
                    </div>

                     <!-- Masalah Khusus -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Masalah Khusus</strong></label>

                            <div class="col-sm-10">
                                <textarea name="masalahkhususpayudadra" class="form-control" rows="5" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
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

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Apakah terdapat: Lignea Nigra/Striae Nigra/Striae Alba, Bekas Operasi. Hasil:</small>
                            <textarea name="abdomen" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                         </div>
                    </div>

                     <!-- Pemeriksaan Palpasi Abdomen  -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Pemeriksaan Palpasi Abdomen</strong></label>

                        <div class="col-sm-10">
                            <textarea name="pemeriksaanpalpasiabdomen" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
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
                        <div class="col-sm-4">
                            <div class="input-group">
                                <input type="text" class="form-control" name="inspeksitfu">
                                <span class="input-group-text">cm</span>
                        </div>    
                    </div>
                                
                    <!-- Kontraksi -->
                    <label class="col-sm-2 col-form-label"><strong>Kontraksi</strong></label>
                    <div class="col-sm-4">
                        <select class="form-select" name="inspeksikontraksi">
                            <option value="">Pilih</option>
                            <option value="Ya">Ya</option>
                            <option value="Tidak">Tidak</option> 
                        </select>
                    </div>    
                </div>

                    <!-- Leopold I -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Leopold I</strong></label>

                        <div class="col-sm-10">
                            <select class="form-select" name="leopoldi">
                                <option value="">Pilih</option>
                                <option value="Kepala">Kepala</option>
                                <option value="Bokong">Bokong</option>
                                <option value="Kosong">Kosong</option>
                            </select>
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

                       <div class="col-sm-10">
                            <select class="form-select" name="kanan">
                                <option value="">Pilih</option>
                                <option value="Punggung">Punggung</option>
                                <option value="Bagian Kecil">Bagian Kecil</option>
                                <option value="Kepala">Kepala</option>
                            </select>
                         </div>
                    </div>

                    <!-- Kiri -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Kiri</strong></label>

                       <div class="col-sm-10">
                            <select class="form-select" name="kiri">
                                <option value="">Pilih</option>
                                <option value="Punggung">Punggung</option>
                                <option value="Bagian Kecil">Bagian Kecil</option>
                                <option value="Kepala">Kepala</option>
                            </select>
                         </div>
                    </div>        

                    <!-- Leopold III -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Leopold III</strong></label>

                       <div class="col-sm-10">
                            <select class="form-select" name="leopoldiii">
                                <option value="">Pilih</option>    
                                <option value="Kepala">Kepala</option>
                                <option value="Bokong">Bokong</option>
                                <option value="Kosong">Kosong</option>
                            </select>
                         </div>
                    </div>        

                    <!-- Leopold IV Penurunan Kepala-->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Leopold IV Penurunan Kepala</strong></label>

                       <div class="col-sm-10">
                            <select class="form-select" name="leopoldiv" required>
                                <option value="">Pilih</option>
                                <option value="Sudah">Sudah</option>
                                <option value="Belum">Belum</option>
                            </select>
                         </div>
                    </div>        

                    <!-- Pemeriksaan DJJ -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Pemeriksaan DJJ</strong></label>

                        <div class="col-sm-10">
                            <div class="input-group">
                                <input type="text" class="form-control" name="pemeriksaandjj">
                                <span class="input-group-text">Frek</span>
                            </div>
                                <small class="form-text" style="color: red;">(Normal 120-160/bradikardi, 160-180/tachikardi < 120) </small>
                            </div>
                    </div>
                    
                    <!-- Intensitas -->
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-2 col-form-label"><strong>Intensitas</strong></label>
                        <div class="col-sm-4">
                            <div class="input-group">
                                <input type="text" class="form-control" name="intensitas">
                                <span class="input-group-text">Intensitas</span>
                        </div>    
                    </div>
                                
                    <!-- Keteraturan -->
                    <label class="col-sm-2 col-form-label"><strong>Keteraturan</strong></label>
                    <div class="col-sm-4">
                            <div class="input-group">
                                <input type="text" class="form-control" name="keteraturan">
                                <span class="input-group-text">Keteraturan</span>
                        </div>    
                    </div>  
                </div> 
                    
                    <!-- Status Janin -->
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-2 col-form-label"><strong>Status Janin</strong></label>
                        <div class="col-sm-4">
                                <select class="form-select" name="statusjanin">
                                <option value="">Pilih</option>
                                <option value="Hidup">Hidup</option>
                                <option value="Tidak">Tidak</option> 
                            </select>
                        </div>    
                                
                    <!-- Jumlah -->
                    <label class="col-sm-2 col-form-label"><strong>Jumlah</strong></label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" name="jumlah">
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

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Apakah terdapat edema (ya/tidak), rasa kesemutan/baal (ya/tidak). Hasil:</small>
                            <textarea name="ekstremitasatas" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                         </div>
                    </div>

                    <!-- Ekstremitas Bawah -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Ekstremitas Bawah</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Apakah terdapat edema (ya/tidak), varises (ya/tidak), refleks pattela (+/-). Hasil:</small>
                            <textarea name="ekstremitasbawah" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
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

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Persiapan perineum. Hasil:</small>
                            <textarea name="persiapanperineum" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                         </div>
                    </div> 

                    <!-- Pengeluaran Pervaginam -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Pengeluaran Pervaginam. Hasil:</small>
                            <textarea name="pengeluaranpervaginam" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
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
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="obat">
                         </div>
                    </div>

                <!-- Bagian Dosis -->
                <div class="row mb-3">
                    <label for="dosis" class="col-sm-2 col-form-label"><strong>Dosis</strong></label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="dosis">
                         </div>
                    </div>
                    
                <!-- Bagian Kegunaan -->
                <div class="row mb-3">
                    <label for="kegunaan" class="col-sm-2 col-form-label"><strong>Kegunaan</strong></label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="kegunaan">
                         </div>
                    </div>
                
                <!-- Bagian Cara Pemberian -->
                <div class="row mb-3">
                    <label for="jenisobat" class="col-sm-2 col-form-label"><strong>Cara Pemberian</strong></label>
                    <div class="col-sm-10">
                        <textarea name="carapemberian" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                         </div>
                    </div> 
                    
                <!-- Bagian Button -->    
                <div class="row mb-3">
                    <div class="col-sm-12 justify-content-end d-flex">
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
                    <div class="col-sm-10">
                        <textarea name="pemeriksaan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                         </div>
                    </div> 

                <!-- Bagian Hasil -->
                <div class="row mb-3">
                    <label for="hasil" class="col-sm-2 col-form-label"><strong>Hasil</strong></label>
                    <div class="col-sm-10">
                        <textarea name="hasil" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                         </div>
                    </div> 
                    
                <!-- Bagian Nilai Normal -->
                <div class="row mb-3">
                    <label for="nilainormal" class="col-sm-2 col-form-label"><strong>Nilai Normal</strong></label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="nilainormal">
                         </div>
                    </div>

                <!-- Bagian Button -->    
                <div class="row mb-3">
                    <div class="col-sm-12 justify-content-end d-flex">
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

    <?php include "tab_navigasi.php"; ?>

</section>
</main>
