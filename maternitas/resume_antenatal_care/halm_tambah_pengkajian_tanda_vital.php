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
    <?php include "navbar_resume_antenatal_care.php"; ?> 
    <section class="section dashboard">
         <div class="card">
            <div class="card-body">

                <h5 class="card-title"> <strong>c. Tanda-tanda Vital</strong></h5>

                <!-- General Form Elements -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">
                        
                <!-- Tekanan Darah -->
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-2 col-form-label"><strong>Tekanan Darah</strong></label>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="text" class="form-control" name="tekanandarah">
                        </div>    
                    </div>
                                
                    <!-- Nadi -->
                    <label class="col-sm-2 col-form-label"><strong>Nadi</strong></label>
                    <div class="col-sm-3">
                        <div class="input-group">
                                <input type="text" class="form-control" name="nadi">
                        </div> 
                    </div>
                </div>

                    <!-- Suhu -->
                        <div class="row mb-3 align-items-center">
                            <label class="col-sm-2 col-form-label"><strong>Suhu</strong></label>
                            <div class="col-sm-3">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="suhu">
                            </div>    
                        </div>

                    <!-- Pernapasan -->
                    <label class="col-sm-2 col-form-label"><strong>Pernapasan</strong></label>
                    <div class="col-sm-3">
                        <div class="input-group">
                                <input type="text" class="form-control" name="pernapasan">
                            </div>
                        </div>
                    </div>
                    </div>
                      <div class="card">
            <div class="card-body">

                <h5 class="card-title"> <strong>d. Pemeriksaan Umum</strong></h5>
                <label class="col-sm-2 col-form-label"><strong>Keluhan Utama</strong></label>


                <!-- General Form Elements -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">
                
                <!-- Bagian Pemeriksaan -->
                <div class="row mb-3">
                    <label for="pemeriksaan" class="col-sm-2 col-form-label"><strong>Riwayat Keluhan Utama</strong></label>
                    <div class="col-sm-9">
                        <textarea name="pemeriksaan" class="form-control" rows="5" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
                      
                         </div>
                    </div>
</div>
</div>
</div>
            </section><?php include "tab_navigasi.php"; ?>
</main>