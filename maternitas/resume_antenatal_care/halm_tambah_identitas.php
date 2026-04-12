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
            
                <!-- General Form Elements -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

                <h5 class="card-title"><strong>DATA DEMOGRAFI</strong></h5>

                <!-- General Form Elements -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">
                
                    <!-- Bagian Inisial Pasien -->

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Inisial Pasien</strong></label>

                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="inisialpasien">
                         </div>
                    </div>

                <!-- Bagian Usia -->
                <div class="row mb-3">
                    <label for="usiai" class="col-sm-2 col-form-label"><strong>Usia</strong></label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="usia">
                         </div>
                    </div>

                <!-- Bagian Pekerjaan -->
                <div class="row mb-3">
                    <label for="pekerjaan" class="col-sm-2 col-form-label"><strong>Pekerjaan</strong></label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="pekerjaan">
                         </div>
                    </div>

                <!-- Bagian Pendidikan -->
                <div class="row mb-3">
                    <label for="pendidikan" class="col-sm-2 col-form-label"><strong>Pendidikan</strong></label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="pendidikan">
                         </div>
                    </div>

                <!-- Bagian Agama -->
                <div class="row mb-3">
                    <label for="agama" class="col-sm-2 col-form-label"><strong>Agama</strong></label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="agama">
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
            </div>
            </section><?php include "tab_navigasi.php"; ?>
</main>