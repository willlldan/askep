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
              <div class="row mb-2">
                        <label class="col-sm-8 col-form-label text-primary">
                            <strong>Dada; Sistem Pernapasan dan Kardiovaskuler</strong>
                    </div>
                    
                    <!-- Auskultasi Bunyi Napas-->
                  
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Auskultasi</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Bunyi Napas. Hasil:</small>
                            <textarea name="bunyinapas" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                         </div>
                    </div> 
                    
                    <!-- Auskultasi Suara Jantung -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Suara Jantung (Apakah ada mur-mur dan gallop). Hasil:</small>
                            <textarea name="suarajantung" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                   
                         </div>
                    </div> 

                     <!-- Masalah Khusus -->
                         <!-- Masalah Khusus -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Masalah Khusus</strong></label>

                            <div class="col-sm-9">
                                <textarea name="masalahkhususdada" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                         </div>
                    </div>
                 
                    <!-- Bagian Payudara -->

                    <div class="row mb-2">
                        <label class="col-sm-2 col-form-label text-primary">
                            <strong>Payudara</strong>
                    </div>
                    
                    <!-- Inspeksi Bentuk-->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Inspeksi Bentuk</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Bentuk, Lesi, Kebersihan. Hasil:</small>
                            <textarea name="inspeksibentuk" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                         </div>
                    </div> 

                    <!-- Inspeksi Colostum-->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Inspeksi Colostum dan ASI</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Colostim dan ASI (Ada atau tidak). Hasil:</small>
                            <textarea name="inspeksicolostum" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                    
                         </div>
                    </div> 

                    <!-- Inspeksi Puting -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Inspeksi Puting</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Eksverted/Inverted/Plat nipple. Hasil:</small>
                            <textarea name="inspeksiputing" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                   
                         </div>
                    </div>
                    
                <!-- Inspeksi Tanda Pembengkakan -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Inspeksi Tanda Pembengkakan</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Tanda Pembengkakan: Ya/Tidak. Hasil:</small>
                            <textarea name="inspeksipembengkakan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                   
                         </div>
                    </div>

                    <!-- Palpasi Raba -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Palpasi Raba</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Teraba hangat: Ya/Tidak. Hasil:</small>
                            <select class="form-select" name="palpasiraba" required>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select> 

                  
                         </div>
                    </div> 

                    <!-- Palpasi Benjolan -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Palpasi Benjolan</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Ada/Tidak Ada. Hasil:</small>
                            <select class="form-select" name="palpasibenjolan" required>
                                <option value="Ada">Ada</option>
                                <option value="Tidak Ada">Tidak Ada</option>
                            </select>
                        
                         </div>
                    </div>

                     <!-- Masalah Khusus -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Masalah Khusus</strong></label>

                            <div class="col-sm-9">
                                <textarea name="masalahkhususpayudadra" class="form-control" rows="5" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
                      
                         </div>
                    </div>
                            
                <!-- Bagian Abdomen -->

                    <div class="row mb-3">
                        <label class="col-sm-9 col-form-label text-primary">
                            <strong>Abdomen</strong>
                        </label>    
                    </div>

                    <!-- Inspeksi -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Inspeksi</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Bentuk, Warna Kulit, Jaringan Perut (ada/tidak), Strie (ada/tidak), Luka (ada/tidak). Hasil:</small>
                            <textarea name="inspeksiabdomen" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                    
                         </div>
                    </div>

                    <!-- Auskultasi -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Auskultasi</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Bising Usus. Hasil:</small>
                            <textarea name="auskultasibisingusus" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     
                         </div>
                    </div>

                    <!-- Perkusi -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Perkusi</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Bunyi (Pekak, redup, sonor, hipersonor, timpani). Hasil:</small>
                            <textarea name="perkusi" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                    
                         </div>
                    </div>

                    <!-- Palpasi -->
                    <!-- Palpasi Involusi Uterus -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Palpasi</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Involusi Uterus: Tinggi Fundus dan Kontraksi. Hasil:</small>
                            <textarea name="palpasiinvolusi" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                  
                         </div>
                    </div> 
                    
                    <!-- Palpasi Kandung Kemih -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Kandung Kemih: teraba/tidak, penuh/tidak. Hasil:</small>
                            <textarea name="palpasikandungkemih" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                   
                         </div>
                    </div>

                     <!-- Masalah Khusus -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Masalah Khusus</strong></label>

                            <div class="col-sm-9">
                               <textarea name="masalahkhususabdomen" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     
                         </div>
                    </div> 
                            
                    <!-- Bagian Perineum dan Genetalia -->

                    <div class="row mb-2">
                        <label class="col-sm-8 col-form-label text-primary">
                            <strong>Perineum dan Genital</strong>
                    </div>
                    
                    <!-- Inspeksi Vagina-->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Vagina</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Vagina: Integritas kulit, edema (ya/tidak), memar (ya/tidak), dan hematom (ya/tidak). Hasil:</small>
                            <textarea name="vagina" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                         </div>
                    </div> 
                    
                    <!-- Perineum -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Perineum: Utuh/Episiotomi/Ruptur. Hasil:</small>
                            <textarea name="perineum" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                    
                         </div>
                    </div> 

                    <!-- Tanda REEDA R-->

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Tanda REEDA</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">R: Redness Kemerahan</small>
                            <select class="form-select" name="redness" required>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select> 

                     
                         </div>
                    </div> 
                    
                    <!-- E -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">E: Edema</small>
                          <select class="form-select" name="edema" required>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select> 

                    
                         </div>
                    </div>

                    <!-- E -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">E: Echimosis</small>
                          <select class="form-select" name="echimosis" required>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select> 

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentechimosisi" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 mt-4 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>

                    <!-- D -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">D: Discharge/Pelepasan</small>
                          <select class="form-select" name="discharge" required>
                                <option value="Serum">Serum</option>
                                <option value="Pus">Pus</option>
                                <option value="Darah">Darah</option>
                                <option value="Tidak Ada">Tidak Ada</option>
                            </select> 

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentdischarge" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 mt-4 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>

                    <!-- A -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">A: Approximate/Perkiraan</small>
                          <select class="form-select" name="aprroximate" required>
                                <option value="Baik">Baik</option>
                                <option value="ti">Tidak</option>
                            </select> 
                         </div>
                    </div>

                    <!-- Pengeluaran Darah -->

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Pengeluaran Darah</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Lochea</small>
                            <textarea name="lochea" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea></div>

                      
                         </div>
                    
                    

                    <!-- Jumlah -->
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-2 col-form-label"></label>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="text" class="form-control" name="jumlah">
                                <span class="input-group-text">Jumlah</span>
                        </div>    
                    </div>
                                
                    <!-- Jenis Warna -->
                    <label class="col-sm-2 col-form-label"></label>
                    <div class="col-sm-3">
                        <div class="input-group">
                                <input type="text" class="form-control" name="jeniswarna">
                                <span class="input-group-text">Jenis Warna</span>
                        </div> 
                    </div>
                </div>

                    <!-- Konsistensi -->
                        <div class="row mb-3 align-items-center">
                            <label class="col-sm-2 col-form-label"></label>
                            <div class="col-sm-3">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="konsistensi">
                                    <span class="input-group-text">Konsistensi</span>
                            </div>    
                        </div>

                    <!-- Bau -->
                    <label class="col-sm-2 col-form-label"></label>
                    <div class="col-sm-3">
                        <div class="input-group">
                                <input type="text" class="form-control" name="bau">
                                <span class="input-group-text">Bau</span>
                        </div> 
                    </div>
                </div>

                        

                    <!-- Hemorrhoid -->

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Hemorrhoid</strong></label>

                        <div class="col-sm-9">
                            <select class="form-select" name="hemorrhoid" required>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select> 

                   
                         </div>
                    </div>

                    <!-- Data Tambahan -->

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Data Tambahan</strong></label>

                        <div class="col-sm-9">
                            <textarea name="datatambahan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                    
                         </div>
                    </div> 
                    
                    <!-- Bagian Ekstremitas -->

                    <div class="row mb-2">
                        <label class="col-sm-8 col-form-label text-primary">
                            <strong>Ekstremitas</strong>
                    </div>
                    
                    <!-- Inspeksi Ekstremitas Atas-->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Ekstremitas Atas</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Apakah terdapat edema (Ya/Tidak), rasa kesemutan/baal (Ya/Tidak), Kekuatan otot. Hasil:</small>
                            <textarea name="inspeksiekstremitasatas" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                    
                         </div>
                    </div> 

                    <!-- Inspeksi Ekstremitas Bawah -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Ekstremitas Bawah</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Apakah terdapat edema (Ya/Tidak), Varises (Ya/Tidak), Tanda
                                Homan untuk melihat adanya tromboflebitis (+/-), Refleks Patella (+/-), apakah terdapat
                                kekakuan sendi, dan kekuatan otot. Hasil:</small>
                           <textarea name="inspeksiekstremitasbawah" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                   
                         </div>
                    </div> 

                    <!-- Masalah Khusus -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Masalah Khusus</strong></label>

                            <div class="col-sm-9">
                                <textarea name="masalahkhususekstremitas" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                    
                         </div>
                    </div> 

                    <!-- Bagian Integumen -->

                    <div class="row mb-2">
                        <label class="col-sm-2 col-form-label text-primary">
                            <strong>Integumen</strong>
                    </div>
                    
                    <!-- Inspeksi -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Inspeksi</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Warna, turgor, elastisitas, ulkus. Hasil:</small>
                            <textarea name="inspeksiintegumen" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     
                         </div>
                    </div> 
                    
                   <!-- Palpasi -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Palpasi</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">Akral, CRT, dan Nyeri. Hasil:</small>
                           <textarea name="palpasiintegumen" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     
                         </div>
                    </div> 

                <!-- Bagian Eliminasi -->

                    <div class="row mb-2">
                        <label class="col-sm-2 col-form-label text-primary">
                            <strong>Eliminasi</strong>
                    </div>
                    
                    <!-- Inspeksi BAK -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Urin</strong></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">BAK saat ini: nyeri (ya/tidak), frekuensi, jumlah. Hasil:</small>
                            <textarea name="inspeksibak" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     </div>
                    </div> 
                    
                    <!-- Inspeksi BAB -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"></label>

                        <div class="col-sm-9">
                            <small class="form-text" style="color: red;">BAB saat ini: Konstipasi (Ya/Tidak), Frekuensi. Hasil:</small>
                            <textarea name="inspeksibab" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     
                         </div>
                    </div> 
                    
                    <!-- Masalah Khusus -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Masalah Khusus</strong></label>

                            <div class="col-sm-9">
                                <textarea name="masalahkhususeliminasi" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     
                         </div>
                    </div> 
                    </section><?php include "tab_navigasi.php"; ?>
</main>