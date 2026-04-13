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

            <!-- General Form Elements -->
            <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

            <h5 class="card-title"><strong>DATA UMUM</strong></h5>

                    <!-- Bagian Inisial Pasien -->

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Inisial Pasien</strong></label>

                        <div class="col-sm-9">
                             <input type="text" class="form-control" name="inisial_pasien" value="   "> <! ?htmlspecialchars($inisial_pasien = "";  beri nilai default) ?>
                         </div>
                    </div>

                <!-- Bagian Usia -->
                <div class="row mb-3">
                    <label for="usiaistri" class="col-sm-2 col-form-label"><strong>Usia</strong></label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="usiaistri">

                       
                         </div>
                    </div>

                <!-- Bagian Pekerjaan -->
                <div class="row mb-3">
                    <label for="pekerjaanistri" class="col-sm-2 col-form-label"><strong>Pekerjaan</strong></label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="pekerjaanistri">

                       
                    </div>
                </div>

                <!-- Bagian Pendidikan Terakhir -->
                <div class="row mb-3">
                    <label for="pendidikanterakhiristri" class="col-sm-2 col-form-label"><strong>Pendidikan Terakhir</strong></label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="pendidikanterakhiristri">

                      
                         </div>
                    </div>

                <!-- Bagian Agama -->
                <div class="row mb-3">
                    <label for="agamaistri" class="col-sm-2 col-form-label"><strong>Agama</strong></label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="agamaistri">

                       
                         </div>
                    </div>

                <!-- Bagian Suku Bangsa -->
                <div class="row mb-3">
                    <label for="sukubangsa" class="col-sm-2 col-form-label"><strong>Suku Bangsa</strong></label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="sukubangsa">
                        
                        
                         </div>
                    </div>

                <!-- Bagian Status Perkawinan -->
                <div class="row mb-3">
                    <label for="statusperkawinan" class="col-sm-2 col-form-label"><strong>Status Perkawinan</strong></label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="statusperkawinan">
                        
                        
                         </div>
                    </div>

                <!-- Bagian Alamat -->
                <div class="row mb-3">
                    <label for="keterangan" class="col-sm-2 col-form-label"><strong>Alamat</strong></label>
                    <div class="col-sm-9">
                        <textarea name="keterangan" class="form-control" rows="5" cols="30"></textarea>
                        
                        
                         </div>
                    </div>

                <!-- Bagian Diagnosa Medik -->
                <div class="row mb-3">
                    <label for="diagnosamedik" class="col-sm-2 col-form-label"><strong>Diagnosa Medik</strong></label>
                    <div class="col-sm-9">
                        <textarea name="keterangan" class="form-control" rows="5" cols="30"></textarea>
                        
                        
                         </div>
                    </div>

                <!-- Bagian Nama Suami -->
                <div class="row mb-3">
                    <label for="namasuami" class="col-sm-2 col-form-label"><strong>Nama Suami</strong></label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="namasuami">
                       
                        
                         </div>
                    </div>

                <!-- Bagian Usia -->
                <div class="row mb-3">
                    <label for="usiasuami" class="col-sm-2 col-form-label"><strong>Usia</strong></label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="usiasuami">
                        
                        
                         </div>
                    </div>

                <!-- Bagian Pekerjaan -->
                <div class="row mb-3">
                    <label for="pekerjaansuami" class="col-sm-2 col-form-label"><strong>Pekerjaan</strong></label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="pekerjaansuami">
                        
                        
                         </div>
                    </div>

                <!-- Bagian Pendidikan Terakhir -->
                <div class="row mb-3">
                    <label for="pendidikanterakhirsuami" class="col-sm-2 col-form-label"><strong>Pendidikan Terakhir</strong></label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="pendidikanterakhirsuami">
                        
                        
                    </div>

                <!-- Bagian Agama -->
                <div class="row mb-3">
                    <label for="agamasuami" class="col-sm-2 col-form-label"><strong>Agama</strong></label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="agamasuami">
                         </div>
                    </div>
                </div>
            
</section><?php include "tab_navigasi.php"; ?>
</main>
