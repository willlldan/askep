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
    <?php include "navbar_gerontik.php"; ?>

    <section class="section dashboard">
        <div class="card">
            <div class="card-body">

                <!-- General Form Elements -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id_identitas" id="hidden-id-identitas" value="<?= htmlspecialchars($id_identitas) ?>">
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
                        </div>
                    </div>

                    <!-- Nafsu Makan -->
                    <div class="row mb-3 align-items-start">
                        <label class="col-sm-2 col-form-label"><strong>Nafsu Makan</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="nafsu_makan">
                        </div>
                    </div>

                    <!-- Jenis Makanan -->
                    <div class="row mb-3 align-items-start">
                        <label class="col-sm-2 col-form-label"><strong>Jenis Makanan</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="jenis_makanan">
                        </div>
                    </div>

                    <!-- Makanan yang Tidak Disukai -->
                    <div class="row mb-3 align-items-start">
                        <label class="col-sm-2 col-form-label"><strong>Makanan yang Tidak Disukai</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="makanan_tidak_disukai">
                        </div>
                    </div>

                    <!-- Kebiasaan Sebelum Makan -->
                    <div class="row mb-3 align-items-start">
                        <label class="col-sm-2 col-form-label"><strong>Kebiasaan / Ritual Sebelum Makan</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="kebiasaan_sebelum_makan">
                        </div>
                    </div>

                    <!-- Berat Badan / Tinggi Badan -->
                    <div class="row mb-3 align-items-start">
                        <label class="col-sm-2 col-form-label"><strong>Berat Badan / Tinggi Badan</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="berat_tinggi_badan">
                        </div>
                    </div>

                    <!-- Jenis Minuman -->
                    <div class="row mb-3 align-items-start">
                        <label class="col-sm-2 col-form-label"><strong>Jenis Minuman</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="jenis_minuman">
                        </div>
                    </div>

                    <!-- Jumlah Cairan -->
                    <div class="row mb-3 align-items-start">
                        <label class="col-sm-2 col-form-label"><strong>Jumlah Cairan yang Dikonsumsi</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="jumlah_cairan">
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
                        </div>
                    </div>

                    <!-- Keluhan BAK -->
                    <div class="row mb-3 align-items-start">
                        <label class="col-sm-2 col-form-label"><strong>Keluhan yang Berhubungan dengan BAK</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="keluhan_bak">
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
                        </div>
                    </div>

                    <!-- Bau BAB -->
                    <div class="row mb-3 align-items-start">
                        <label class="col-sm-2 col-form-label"><strong>Bau</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="bau_bab">
                        </div>
                    </div>

                    <!-- Warna BAB -->
                    <div class="row mb-3 align-items-start">
                        <label class="col-sm-2 col-form-label"><strong>Warna</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="warna_bab">
                        </div>
                    </div>

                    <!-- Konsistensi BAB -->
                    <div class="row mb-3 align-items-start">
                        <label class="col-sm-2 col-form-label"><strong>Konsistensi</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="konsistensi_bab">
                        </div>
                    </div>

                    <!-- Keluhan BAB -->
                    <div class="row mb-3 align-items-start">
                        <label class="col-sm-2 col-form-label"><strong>Keluhan yang Berhubungan dengan Defekasi</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="keluhan_bab">
                        </div>
                    </div>

                    <!-- Laksatif -->
                    <div class="row mb-3 align-items-start">
                        <label class="col-sm-2 col-form-label"><strong>Pengalaman Memakai Laksatif</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="pengalaman_laksatif">
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
                        </div>
                    </div>
                    <div class="row mb-2">
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
                        </div>
                    </div>

                    <!-- Jenis dan Frekuensi -->
                    <div class="row mb-3 align-items-start">
                        <label class="col-sm-2 col-form-label"><strong>Jenis dan Frekuensi</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="jenis_frekuensi_olahraga">
                        </div>
                    </div>

                    <!-- Kegiatan Waktu Luang -->
                    <div class="row mb-3 align-items-start">
                        <label class="col-sm-2 col-form-label"><strong>Kegiatan Waktu Luang</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="kegiatan_waktu_luang">
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
                        </div>
                    </div>

                    <!-- Tombol Submit -->
                    <div class="row mb-3">
                        <div class="col-sm-9 offset-sm-2 text-end">
                            <button type="submit" class="btn btn-primary">Lanjutkan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
    </section>
</main>

<?php include "footer_gerontik.php"; ?>