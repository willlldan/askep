<?php

require_once "koneksi.php";
require_once "utils.php";

$username = $_SESSION['username'];
$identitas_result = $mysqli->query("SELECT id, nama, tempat_lahir, tgl_lahir FROM tbl_gerontik_identitas WHERE created_by = '$username' ORDER BY created_at DESC");


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_identitas = isset($_POST['id_identitas']) ? intval($_POST['id_identitas']) : (isset($_GET['idpasien']) ? intval($_GET['idpasien']) : 0);
} else {
    $id_identitas = isset($_GET['idpasien']) ? intval($_GET['idpasien']) : 0;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $created_at = date('Y-m-d H:i:s');
    $created_by = $username;

    // ===== FIELD PENGKAJIAN KEBIASAAN =====
    $fields = [
        // Nutrisi dan Cairan
        'frekuensi_makan',
        'nafsu_makan',
        'jenis_makanan',
        'makanan_tidak_disukai',
        'kebiasaan_sebelum_makan',
        'berat_tinggi_badan',
        'jenis_minuman',
        'jumlah_cairan',
        'kesulitan_makan_minum',
        'makan_minum_bantu',

        // Eliminasi - BAK
        'warna_bak',
        'keluhan_bak',
        'dibantu_bak',
        'mandiri_bak',

        // Eliminasi - BAB
        'frekuensi_bab',
        'bau_bab',
        'warna_bab',
        'konsistensi_bab',
        'keluhan_bab',
        'pengalaman_laksatif',
        'dibantu_bab',
        'mandiri_bab',

        // Hygiene Personal - Mandi
        'frekuensi_mandi',
        'dibantu_mandi',
        'mandiri_mandi',

        // Hygiene Personal - Oral
        'frekuensi_hygiene_oral',
        'dibantu_hygiene_oral',
        'mandiri_hygiene_oral',

        // Hygiene Personal - Cuci Rambut
        'frekuensi_cuci_rambut',
        'dibantu_cuci_rambut',
        'mandiri_cuci_rambut',

        // Hygiene Personal - Gunting Kuku
        'frekuensi_gunting_kuku',
        'dibantu_gunting_kuku',
        'mandiri_gunting_kuku',

        // Istirahat dan Tidur
        'lama_tidur',
        'kesulitan_tidur',
        'tidur_siang',

        // Aktivitas dan Latihan
        'olahraga_ringan',
        'jenis_frekuensi_olahraga',
        'kegiatan_waktu_luang',
        'keluhan_aktivitas',
        'kesulitan_pergerakan',
        'sesak_nafas'
    ];

    // ===== AMBIL DATA POST =====
    $data = [];
    foreach ($fields as $field) {
        $data[$field] = $_POST[$field] ?? '';
    }

    // ===== BUILD QUERY =====
    $columns = implode(', ', array_keys($data));
    $placeholders = implode(', ', array_fill(0, count($data), '?'));

    $sql = "
        INSERT INTO tbl_gerontik_pengkajian_kebiasaan
        (id_identitas, $columns, created_at, created_by)
        VALUES (?, $placeholders, ?, ?)
    ";

    $stmt = $mysqli->prepare($sql);

    if ($stmt) {
        $types = 'i' . str_repeat('s', count($data)) . 'ss';
        $values = array_merge([$id_identitas], array_values($data), [$created_at, $created_by]);

        $stmt->bind_param($types, ...$values);

        if ($stmt->execute()) {
            echo "<script>window.location.href = 'index.php?page=gerontik&tab=pengkajian-psikis&idpasien=" . urlencode($id_identitas) . "';</script>";
        } else {
            $alert = '<div class="alert alert-danger">Gagal menyimpan data: ' . htmlspecialchars($stmt->error) . '</div>';
        }

        $stmt->close();
    } else {
        $alert = '<div class="alert alert-danger">Prepare statement gagal: ' . htmlspecialchars($mysqli->error) . '</div>';
    }
}
?>

<main id="main" class="main">
    <?php include "maternitas/resume_antenatal_care/tab.php"; ?>
    <section class="section dashboard">
 <div class="card">
            <div class="card-body">

                <h5 class="card-title"><strong>Program Terapi</strong></h5>

                <!-- General Form Elements -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

                    <div class="row mb-2">
                        <label class="col-sm-6 col-form-label text-primary">
                            <strong>Obat-obatan yang dikonsumsi Saat Ini</strong>
                    </div>

                <!-- Bagian Jenis Obat -->
                <div class="row mb-3">
                    <label for="jenisobat" class="col-sm-2 col-form-label"><strong>Jenis Obat</strong></label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="jenisobat">
                         </div>
                    </div>

                <!-- Bagian Dosis -->
                <div class="row mb-3">
                    <label for="dosis" class="col-sm-2 col-form-label"><strong>Dosis</strong></label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="dosis">
                         </div>
                    </div>
                    
                <!-- Bagian Kegunaan -->
                <div class="row mb-3">
                    <label for="kegunaan" class="col-sm-2 col-form-label"><strong>Kegunaan</strong></label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="kegunaan">
                         </div>
                    </div>
                
                <!-- Bagian Cara Pemberian -->
                <div class="row mb-3">
                    <label for="jenisobat" class="col-sm-2 col-form-label"><strong>Cara Pemberian</strong></label>
                    <div class="col-sm-9">
                        <textarea name="carapemberian" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                         </div>
                    </div> 
                    
                <!-- Bagian Button -->    
                <div class="row mb-3">
                    <div class="col-sm-11 justify-content-end d-flex">
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
                                <th class="text-center">Jenis Obat</th>
                                <th class="text-center">Dosis</th>
                                <th class="text-center">kegunaan</th>
                                <th class="text-center">Cara Pemberian</th>
                        </tr>
                        </thead>

                    <tbody>

                    <?php
                    // if(!empty($data)){
                    //     foreach($data as $row){
                    //         echo "<tr>
                    //         <td>".nlrbr($row['jenisobat'])."</td>
                    //         <td>".nlrbr($row['dosis'])."</td>
                    //         <td>".nlrbr($row['kegunaan'])."</td>
                    //         <td>".nlrbr($row['carapemberian'])."</td>
                    //         </tr>";
                    //     }
                    // }
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
                    <div class="col-sm-9">
                        <textarea name="pemeriksaan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                         </div>
                    </div> 

                <!-- Bagian Hasil -->
                <div class="row mb-3">
                    <label for="hasil" class="col-sm-2 col-form-label"><strong>Hasil</strong></label>
                    <div class="col-sm-9">
                        <textarea name="hasil" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                         </div>
                    </div> 
                    
                <!-- Bagian Nilai Normal -->
                <div class="row mb-3">
                    <label for="nilainormal" class="col-sm-2 col-form-label"><strong>Nilai Normal</strong></label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="nilainormal">                        
                         </div>
                    </div>

                <!-- Bagian Button -->    
                <div class="row mb-3">
                    <div class="col-sm-11 justify-content-end d-flex">
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
                    // if(!empty($data)){
                    //     foreach($data as $row){
                    //         echo "<tr>
                    //         <td>".nlrbr($row['pemeriksaan'])."</td>
                    //         <td>".nlrbr($row['hasil'])."</td>
                    //         <td>".nlrbr($row['nilainormal'])."</td>
                    //         </tr>";
                    //     }
                    // }
                    ?>

                    </tbody>
                    </table>

                <!-- Bagian Klasifikasi Data -->    

                <div class="row mb-2">
                    <label class="col-sm-6 col-form-label text-primary">
                        <strong>Klasifikasi Data</strong>
                </div>

                <!-- Bagian Data Subjektif (DS) -->
                <div class="row mb-3">
                    <label for="datasubjektif" class="col-sm-2 col-form-label"><strong>Data Subjektif (DS)</strong></label>
                    <div class="col-sm-9">
                        <textarea name="datasubjektif" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                         </div>
                    </div> 

                <!-- Bagian Data Objektif (DO) -->
                <div class="row mb-3">
                    <label for="dataobjektif" class="col-sm-2 col-form-label"><strong>Data Objektif (DO)</strong></label>
                    <div class="col-sm-9">
                        <textarea name="dataobjektif" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                         </div>
                    </div>  

                <!-- Bagian Button -->    
                <div class="row mb-3">
                    <div class="col-sm-11 justify-content-end d-flex">
                        <button type="submit" name="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div> 

                <h5 class="card-title"><strong>Klasifikasi Data</strong></h5>
                
                <style>
                    .table-klasifikasidata {
                        table-layout: fixed;
                        width:100%
                    }

                    .table-klasifikasidata td,
                    .table-klasifikasidata th {
                        word-wrap: break-word;
                        white-space: normal;
                        vertical-align: top;
                    }
                    </style>

                    <table class="table table-bordered table-klasifikasidata">
                        <thead>
                            <tr>
                                <th class="text-center">Data Subjektif (DS)</th>
                                <th class="text-center">Data Objektif (DO)</th>
                        </tr>
                        </thead>

                    <tbody>

                    <?php
                    // if(!empty($data)){
                    //     foreach($data as $row){
                    //         echo "<tr>
                    //         <td>".nlrbr($row['datasubjektif'])."</td>
                    //         <td>".nlrbr($row['dataobjektif'])."</td>
                    //         </tr>";
                    //     }
                    // }
                    ?>

                    </tbody>
                    </table>

            <!-- Bagian Analisa Data -->    

                <div class="row mb-2">
                    <label class="col-sm-6 col-form-label text-primary">
                        <strong>Analisa Data</strong>
                </div>

                <!-- Bagian DS/DO -->
                <div class="row mb-3">
                    <label for="dsdo" class="col-sm-2 col-form-label"><strong>DS/DO</strong></label>
                    <div class="col-sm-9">
                        <textarea name="dsdo" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                         </div>
                    </div> 

                <!-- Bagian Etiologi -->
                <div class="row mb-3">
                    <label for="etiologi" class="col-sm-2 col-form-label"><strong>Etiologi</strong></label>
                    <div class="col-sm-9">
                        <textarea name="etiologi" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                         </div>
                    </div> 
                    
                <!-- Bagian Masalah -->
                <div class="row mb-3">
                    <label for="masalah" class="col-sm-2 col-form-label"><strong>Masalah</strong></label>
                    <div class="col-sm-9">
                        <textarea name="masalah" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                         </div>
                    </div> 

                <!-- Bagian Button -->    
                <div class="row mb-3">
                    <div class="col-sm-11 justify-content-end d-flex">
                        <button type="submit" name="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>   
                
                <h5 class="card-title"><strong>Analisa Data</strong></h5>
                
                <style>
                    .table-analisadata {
                        table-layout: fixed;
                        width:100%
                    }

                    .table-analisadata td,
                    .table-analisadata th {
                        word-wrap: break-word;
                        white-space: normal;
                        vertical-align: top;
                    }
                    </style>

                    <table class="table table-bordered table-analisadata">
                        <thead>
                            <tr>
                                <th class="text-center">DS/DO</th>
                                <th class="text-center">Etiologi</th>
                                <th class="text-center">Masalah</th>
                        </tr>
                        </thead>

                    <tbody>

                    <?php
                    if(!empty($data)){
                        foreach($data as $row){
                            echo "<tr>
                            <td>".$row['dsdo']."</td>
                            <td>".$row['etiologi']."</td>
                            <td>".$row['masalah']."</td>
                            </tr>";
                        }
                    }
                    ?>

                    </tbody>
                    </table>
            </section><?php include "tab_navigasi.php"; ?>
</main>