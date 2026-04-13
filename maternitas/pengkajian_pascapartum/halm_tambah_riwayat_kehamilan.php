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
    
     <section class="section dashboard">
        <div class="card">
            <div class="card-body">

                <h5 class="card-title"><strong>RIWAYAT KEHAMILAN DAN PERSALINAN YANG LALU</strong></h5>

                <!-- General Form Elements -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">
                
                <!-- Bagian Pemeriksaan -->
                <div class="row mb-3">
                    <label for="pemeriksaan" class="col-sm-2 col-form-label"><strong>Berapa kali pemeriksaan ANC (kehamilan)?</strong></label>
                    <div class="col-sm-9">
                        <textarea name="pemeriksaan" class="form-control" rows="5" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
                      
                         </div>
                    </div>
                    
                <!-- Bagian Masalah Kehamilan -->
                <div class="row mb-3">
                    <label for="masalahkehamilan" class="col-sm-2 col-form-label"><strong>Masalah yang dialami selama hamil dan tindakan pengotaban yang dilakukan</strong></label>
                    <div class="col-sm-9">
                        <textarea name="masalahkehamilan" class="form-control" rows="5" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
                     
                         </div>
                    </div>

                <!-- Bagian Riwayat Persalinan -->
                <div class="row mb-3">
                    <label for="riwayatpersalinan" class="col-sm-2 col-form-label"><strong>Riwayat Persalinan apakah Spontan/Letkep/Letsu/Sectio Caesarea (jika SC atas indikasi apa?)</strong></label>
                    <div class="col-sm-9">
                        <textarea name="riwayatpersalinan" class="form-control" rows="5" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
                        
                         </div>
                    </div>

                <!-- Bagian Riwayat KB -->
                <div class="row mb-3">
                    <label for="riwayatkb" class="col-sm-2 col-form-label"><strong>Riwayat KB (Jenis, Berapa lama penggunaan)</strong></label>
                    <div class="col-sm-9">
                        <textarea name="riwayatkb" class="form-control" rows="5" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
                       
                         </div>
                    </div>

                <!-- Bagian Jumlah Pendarahan -->
                <div class="row mb-3">
                    <label for="jumlahpendarahan" class="col-sm-2 col-form-label"><strong>Jumlah pendarahan saat melahirkan</strong></label>
                    <div class="col-sm-9">
                        <textarea name="jumlahpendarahan" class="form-control" rows="5" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
                      
                         </div>
                    </div>

                <h5 class="card-title"><strong>Riwayat Persalinan yang Lalu</strong></h5>    

                <!-- Bagian Tahun -->
                <div class="row mb-3">
                    <label for="tahun" class="col-sm-2 col-form-label"><strong>Tahun</strong></label>
                    <div class="col-sm-9">
                        <input type="number" class="form-control" name="tahun" value="">
                        
                        
                         </div>
                    </div>

                <!-- Bagian Jenis Persalinan -->
                <div class="row mb-3">
                    <label for="jenispersalinan" class="col-sm-2 col-form-label"><strong>Jenis Persalinan</strong></label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="jenispersalinan">
                        
                         </div>
                    </div>

                <!-- Bagian Penolong -->
                <div class="row mb-3">
                    <label for="penolong" class="col-sm-2 col-form-label"><strong>Penolong</strong></label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="penolong">
                        
                        
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
                            
                   
                         </div>
                    </div>
                    
                <!-- Bagian BB/TB Bayi -->
                <div class="row mb-3">
                    <label for="bbtbbayi" class="col-sm-2 col-form-label"><strong>BB/TB Bayi</strong></label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="bbtbbayi">

                       
                         </div>
                    </div> 
                    
                <!-- Bagian Menyusui Berapa Lama -->
                <div class="row mb-3">
                    <label for="menyusui" class="col-sm-2 col-form-label"><strong>Menyusui Berapa Lama</strong></label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="menyusui">

                       
                         </div>
                    </div>    
                
                <!-- Bagian Masalah Kehamilan -->
                <div class="row mb-3">
                    <label for="masalahkehamilan" class="col-sm-2 col-form-label"><strong>Masalah Kehamilan</strong></label>
                    <div class="col-sm-9">
                        <textarea name="keluhanutama" class="form-control" rows="5" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
                       
                         </div>
                    </div>

                <!-- Bagian Button -->    
                <div class="row mb-3">
                    <div class="col-sm-11 justify-content-end d-flex">
                        <button type="submit" name="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div> 

                <h5 class="card-title mt-2"><strong>Tabel Riwayat Kehamilan dan Persalinan</strong></h5>

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Tahun</th>
                            <th class="text-center">Jenis Persalinan</th>
                            <th class="text-center">Penolong</th>
                            <th class="text-center">Jenis Kelamin</th>
                            <th class="text-center">BB/TB Bayi</th>
                            <th class="text-center">Menyusui Berapa Lama</th>
                            <th class="text-center">Masalah Kehamilan</th>
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
                        <td>".$row['tahun']."</td>
                        <td>".$row['persalinan']."</td>
                        <td>".$row['penolong']."</td>
                        <td>".$row['jeniskelamin']."</td>
                        <td>".$row['bbtbbayi']."</td>
                        <td>".$row['menyusui']."</td>
                        <td>".$row['masalah']."</td>
                        </tr>";
                    }
                }
                ?>

                </tbody>
                </table>
                </div>
            </div>
            

</section><?php include "tab_navigasi.php"; ?>
</main>