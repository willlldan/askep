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
    <?php include "navbar_gerontik.php"; ?>
    </style>

    <section class="section dashboard">
        <div class="card">

            <div class="card-body">
                <h5 class="card-title">Tambah Identitas Lansia</h5>
                <?php if (!empty($alert)) echo $alert; ?>

                <!-- General Form Elements -->
                <form class="needs-validation" novalidate action="" method="POST" autocomplete="off">

                    <!-- Bagian Nama -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Nama</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="nama" value="<?= htmlspecialchars($nama) ?>">
                        </div>
                    </div>

                    <!-- Bagian Tempat Lahir -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Tempat Lahir</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="tempat_lahir" value="<?= htmlspecialchars($tempat_lahir) ?>">
                        </div>
                    </div>

                    <!-- Bagian Tanggal Lahir -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Tanggal Lahir</strong></label>
                        <div class="col-sm-9">
                            <input type="date" class="form-control" name="tgl_lahir" required value="<?= htmlspecialchars($tgl_lahir) ?>">
                        </div>
                    </div>

                    <!-- Bagian Jenis Kelamin -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Jenis Kelamin</strong></label>
                        <div class="col-sm-9">
                            <select class="form-control" name="jenis_kelamin" required>
                                <option value="">Pilih</option>
                                <option value="Laki-laki" <?= ($jenis_kelamin == 'Laki-laki') ? 'selected' : '' ?>>Laki-laki</option>
                                <option value="Perempuan" <?= ($jenis_kelamin == 'Perempuan') ? 'selected' : '' ?>>Perempuan</option>
                            </select>
                        </div>
                    </div>


                    <!-- Bagian Status Perkawinan -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Status Perkawinan</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="status_perkawinan" required value="<?= htmlspecialchars($status_perkawinan) ?>">
                        </div>
                    </div>

                    <!-- Bagian Agama -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Agama</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="agama" required value="<?= htmlspecialchars($agama) ?>">
                        </div>
                    </div>

                    <!-- Bagian Pendidikan -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Pendidikan</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="pendidikan" required value="<?= htmlspecialchars($pendidikan) ?>">
                        </div>
                    </div>

                    <!-- Bagian Pekerjaan Saat Ini -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Pekerjaan Saat Ini</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="pekerjaan_sekarang" value="<?= htmlspecialchars($pekerjaan_sekarang) ?>">
                        </div>
                    </div>

                    <!-- Bagian Pekerjaan Sebelumnya -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Pekerjaan Sebelumnya</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="pekerjaan_sebelumnya" value="<?= htmlspecialchars($pekerjaan_sebelumnya) ?>">
                        </div>
                    </div>

                    <!-- Bagian Tanggal Pengkajian -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Tanggal Pengkajian</strong></label>
                        <div class="col-sm-9">
                            <input type="date" class="form-control" name="tgl_pengkajian" required value="<?= htmlspecialchars($tgl_pengkajian) ?>">
                        </div>
                    </div>

                    <!-- Bagian Alamat -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Alamat</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="alamat" required value="<?= htmlspecialchars($alamat) ?>">
                        </div>
                    </div>

                    <!-- Tombol Submit -->
                    <div class="row mb-3">
                        <div class="col-sm-9 offset-sm-2 text-end">
                            <button type="submit" class="btn btn-primary">Lanjutkan</button>
                        </div>
                    </div>
                </form><!-- End General Form Elements -->
            </div>
        </div>
    </section>
</main>
