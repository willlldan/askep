<?php
require_once "koneksi.php";
require_once "utils.php";
// Inisialisasi variabel form agar tidak undefined/null
$nama = $nama ?? '';
$tempat_lahir = $tempat_lahir ?? '';
$tgl_lahir = $tgl_lahir ?? '';
$jenis_kelamin = $jenis_kelamin ?? '';
$status_perkawinan = $status_perkawinan ?? '';
$agama = $agama ?? '';
$pendidikan = $pendidikan ?? '';
$pekerjaan_sekarang = $pekerjaan_sekarang ?? '';
$pekerjaan_sebelumnya = $pekerjaan_sebelumnya ?? '';
$tgl_pengkajian = $tgl_pengkajian ?? '';
$alamat = $alamat ?? '';

// Handle form submission
$alert = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect form data
    $nama = $_POST['nama'] ?? '';
    $tempat_lahir = $_POST['tempat_lahir'] ?? '';
    $tgl_lahir = $_POST['tgl_lahir'] ?? '';
    $jenis_kelamin = $_POST['jenis_kelamin'] ?? '';
    $status_perkawinan = $_POST['status_perkawinan'] ?? '';
    $agama = $_POST['agama'] ?? '';
    $pendidikan = $_POST['pendidikan'] ?? '';
    $pekerjaan_sekarang = $_POST['pekerjaan_sekarang'] ?? '';
    $pekerjaan_sebelumnya = $_POST['pekerjaan_sebelumnya'] ?? '';
    $tgl_pengkajian = $_POST['tgl_pengkajian'] ?? '';
    $alamat = $_POST['alamat'] ?? '';

    // Audit fields
    $created_at = date('Y-m-d H:i:s');
    $updated_at = $created_at;
    // You may want to get this from session or auth system
    $created_by = isset($_SESSION['username']) ? $_SESSION['username'] : 'system';
    $updated_by = $created_by;

    // Insert into database
    $sql = "INSERT INTO tbl_gerontik_identitas (nama, tempat_lahir, tgl_lahir, jenis_kelamin, status_perkawinan, agama, pendidikan, pekerjaan_sekarang, pekerjaan_sebelumnya, tgl_pengkajian, alamat, created_at, created_by, updated_at, updated_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $mysqli->prepare($sql);
    if ($stmt) {
        $stmt->bind_param('sssssssssssssss', $nama, $tempat_lahir, $tgl_lahir, $jenis_kelamin, $status_perkawinan, $agama, $pendidikan, $pekerjaan_sekarang, $pekerjaan_sebelumnya, $tgl_pengkajian, $alamat, $created_at, $created_by, $updated_at, $updated_by);
        if ($stmt->execute()) {
            $new_id = $stmt->insert_id ? $stmt->insert_id : $mysqli->insert_id;
            echo "<script>window.location.href = 'index.php?page=gerontik&tab=pengkajian-riwayat&idpasien=" . urlencode($new_id) . "';</script>";
            exit;
        } else {
            $alert = '<div class="alert alert-danger">Gagal menyimpan data: ' . htmlspecialchars($stmt->error) . '</div>';
        }
        $stmt->close();
    } else {
        $alert = '<div class="alert alert-danger">Gagal menyiapkan statement: ' . htmlspecialchars($mysqli->error) . '</div>';
    }
} ?>

<main id="main" class="main">
    <?php include "navbar_maternitas.php"; ?>
    </style>
    <section class="section dashboard">
        <div class="card">
         


               <div class="card">
                    <div class="card-body"> 

              <h5 class="card-title mb-1"><strong>Pengkajian</strong></h5>

                
                <!-- Bagian Kepala dan Rambut -->

                    <div class="row mb-2">
                        <label class="col-sm-2 col-form-label text-primary">
                            <strong>Kepala dan Rambut</strong>
                    </div>
                    
                    <!-- Inspeksi -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Inspeksi</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Bentuk kepala, Penyebaran, Kebersihan, Warna Rambut. Hasil:</small>
                            <textarea name="inspeksikepala" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                         </div>
                    </div>        

                    <!-- Palpasi -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Palpasi</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Apakah terdapat benjolan, pembengkakan, nyeri tekan. Hasil:</small>
                            <textarea name="palpasiikepala" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                 
                         </div>
                    </div> 

                     <!-- Masalah Khusus -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Masalah Khusus</strong></label>

                            <div class="col-sm-9">
                                <textarea name="masalahkhususkepala" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                  
                         </div>
                    </div> 
                            
                    <!-- Bagian Wajah -->

                    <div class="row mb-2">
                        <label class="col-sm-2 col-form-label text-primary">
                            <strong>Wajah</strong>
                    </div>
                    
                    <!-- Inspeksi -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Inspeksi</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Bentuk, adakah hiperpigmentasi/cloasma gravidarum, area jika ada cloasma. Hasil:</small>
                            <textarea name="inspeksiwajah" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                    
                         </div>
                    </div> 

                    <!-- Palpasi -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Palpasi</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Adakah nyeri tekan/tidak ada. Hasil:</small>
                            <textarea name="palpasiwajah" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     
                         </div>
                    </div> 

                     <!-- Masalah Khusus -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Masalah Khusus</strong></label>

                            <div class="col-sm-9">
                                <textarea name="masalahkhususwajah" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                    
                         </div>
                    </div> 
                    
                    <!-- Bagian Mata -->

                    <div class="row mb-2">
                        <label class="col-sm-2 col-form-label text-primary">
                            <strong>Mata</strong>
                    </div>
                    
                    <!-- Inspeksi  -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Inspeksi</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Konjungtiva: Apakah anemis/an-anemis. Hasil:</small>
                           <textarea name="inspeksikmata" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                  
                         </div>
                    </div> 

                    <!-- Palpasi -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Palpasi</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Kelopak mata: Nyeri tekan/tidak. Hasil:</small>
                            <textarea name="palpasikelopakmata" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                   
                         </div>
                    </div> 

                     <!-- Masalah Khusus -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Masalah Khusus</strong></label>

                            <div class="col-sm-9">
                                <textarea name="masalahkhususmata" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                         </div>
                    </div>   

                    <!-- Bagian Hidung -->

                    <div class="row mb-2">
                        <label class="col-sm-2 col-form-label text-primary">
                            <strong>Hidung</strong>
                    </div>
                    
                    <!-- Inspeksi -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Inspeksi</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Apakah ada pembengkakan/tidak ada pembengkakan, kesimetrisan lubang hidung, kebersihan, septum utuh/tidak utuh. Hasil:</small>
                            <textarea name="inspeksihidung" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                   
                         </div>
                    </div> 

                    <!-- Palpasi -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Palpasi</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Nyeri tekan/tidak ada. Hasil:</small>
                           <textarea name="palpasihidung" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                    
                         </div>
                    </div> 

                     <!-- Riwayat Masalah Khusus -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Masalah Khusus</strong></label>

                            <div class="col-sm-9">
                                <textarea name="masalahkhusushidung" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                    
                         </div>
                    </div> 

                    <!-- Bagian Mulut -->

                    <div class="row mb-2">
                        <label class="col-sm-2 col-form-label text-primary">
                            <strong>Mulut</strong>
                    </div>
                    
                    <!-- Inspeksi Bibir -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Inspeksi Bibir</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Warna, kesimertrisan, kelembapan, bibir sumbing, ulkus. Hasil:</small>
                            <textarea name="inspeksibibir" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     
                         </div>
                    </div> 

                    <!-- Inspeksi Gigi -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Inspeksi Gigi</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Amati jumlah, warna, kebersihan, karies. Hasil:</small>
                            <textarea name="inspeksigigi" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                   
                         </div>
                    </div> 

                    <!-- Inspeksi Gusi -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Inspeksi Gusi</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Adakah atau tidak lesi/pembengkakan? Hasil:</small>
                            <textarea name="inspeksigusi" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                   
                         </div>
                    </div> 

                    <!-- Inspeksi Lidah -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Inspeksi Lidah</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Amati warna dan kebersihan. Hasil:</small>
                            <textarea name="inspeksilidah" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                   
                         </div>
                    </div> 

                    <!-- Inspeksi Bau Mulut -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Inspeksi Bau Mulut</strong></label>

                        <div class="col-sm-9">
                            <textarea name="inspeksibaumulut" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                 
                         </div>
                    </div> 

                    <!-- Palpasi -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Palpasi</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Apakah ada nyeri tekan atau tidak ada? Hasil:</small>
                            <textarea name="palpasimulut" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                    
                         </div>
                    </div> 

                     <!-- Masalah Khusus -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Masalah Khusus</strong></label>

                            <div class="col-sm-9">
                                <textarea name="masalahkhususmulut" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                   
                         </div>
                    </div>  
                            
                     <!-- Bagian Telinga -->

                    <div class="row mb-2">
                        <label class="col-sm-2 col-form-label text-primary">
                            <strong>Telinga</strong>
                    </div>
                    
                    <!-- Inspeksi -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Inspeksi</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Bentuk: simetris/tidak. <br> Kebersihan: apakah ada perdarahan, peradangan, kotoran/serumen atau tidak ada? Hasil:</small>
                            <textarea name="inspeksitelinga" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                         </div>
                    </div> 

                    <!-- Palpasi -->
                    <!-- Palpasi Nyeri Tekan -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Palpasi</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Nyeri Tekanan: Apakah ada pembengkakan, nyeri tekan atau tidak ada? Hasil:</small>
                            <textarea name="palpasinyeritekan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                    
                         </div>
                    </div> 
                    
                    <!-- Palpasi Gangguan -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Gangguan pendengaran: apakah ada ganguan atau tidak? Hasil:</small>
                            <textarea name="palpasigangguan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                    
                         </div>
                    </div>

                     <!-- Riwayat Masalah Khusus -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Masalah Khusus</strong></label>

                            <div class="col-sm-9">
                                <textarea name="masalahkhusustelinga" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                    
                         </div>
                    </div> 
                            
                     <!-- Bagian Leher -->

                    <div class="row mb-2">
                        <label class="col-sm-2 col-form-label text-primary">
                            <strong>Leher</strong>
                    </div>
                    
                    <!-- Inspeksi -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Inspeksi</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Bentuk leher, ada massa dan benjolan atau tidak. Adakah Distensi vena jugularis/tidak ada. Hasil:</small>
                            <textarea name="inspeksileher" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                    
                         </div>
                    </div> 

                    <!-- Palpasi -->
                    <!-- Palpasi Kelenjar Tiroid -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Palpasi</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Kelenjar Tiroid: Apakah ada pembesaran kelenjar tiroid atau tidak. Hasil:</small>
                            <textarea name="palpasikelenjar" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     
                         </div>
                    </div> 
                    
                    <!-- Palpasi Trakea -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Trakea: Apakah ada pergeseran/tidak. Hasil:</small>
                            <textarea name="palpasitrakea" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                    
                         </div>
                    </div> 

                     <!-- Palpasi Nyeri Menelan -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Nyeri menelan: ya/tidak. Hasil:</small>
                            <textarea name="palpasinyerimenelan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                    
                         </div>
                    </div> 

                     <!-- Riwayat Masalah Khusus -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Masalah Khusus</strong></label>

                            <div class="col-sm-9">
                                <textarea name="masalahkhususleher" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     
                         </div>
                    </div> 
                            
                     <!-- Bagian Axila -->

                    <div class="row mb-2">
                        <label class="col-sm-2 col-form-label text-primary">
                            <strong>Axila</strong>
                    </div>
                    
                    <!-- Inspeksi -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Inspeksi</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Warna, Pembengkakan. Hasil:</small>
                            <textarea name="inspeksiaxilia" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                   
                         </div>
                    </div> 

                    <!-- Palpasi -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Palpasi</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Pembesaran kelenjar limfe: Ya/Tidak? Hasil:</small>
                            <textarea name="palpasiaxilia" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     
                         </div>
                    </div> 

                     <!-- Masalah Khusus -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Masalah Khusus</strong></label>

                            <div class="col-sm-9">
                                <textarea name="masalahkhususaxilia" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                    
                         </div>
                    </div> 
                    </section><?php include "tab_navigasi.php"; ?>
</main>
            