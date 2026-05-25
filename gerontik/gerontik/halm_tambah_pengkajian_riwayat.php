<?php
require_once "koneksi.php";
require_once "utils.php";

$username = $_SESSION['username'];
$identitas_result = $mysqli->query("SELECT id, nama, tempat_lahir, tgl_lahir FROM tbl_gerontik_identitas WHERE created_by = '$username' ORDER BY created_at DESC");

$alert = '';

// Ambil id_identitas dari POST (dropdown navbar) jika ada, jika tidak dari URL
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_identitas = isset($_POST['id_identitas']) ? intval($_POST['id_identitas']) : (isset($_GET['idpasien']) ? intval($_GET['idpasien']) : 0);
} else {
    $id_identitas = isset($_GET['idpasien']) ? intval($_GET['idpasien']) : 0;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!$id_identitas) {
        $alert = '<div class="alert alert-danger">Identitas pasien belum dipilih atau tidak valid.</div>';
        echo "ID identitas: " . htmlspecialchars($id_identitas);
        die;
    }


    $id_identitas = $_POST['id_identitas'] ?? $id_identitas;
    $keluhan_utama = $_POST['keluhan_utama'] ?? '';
    $riwayat_kesehatan_sekarang = $_POST['riwayat_kesehatan_sekarang'] ?? '';
    $berkualitas = $_POST['berkualitas'] ?? '';
    $sehat = $_POST['sehat'] ?? '';
    $aktif = $_POST['aktif'] ?? '';
    $produktif = $_POST['produktif'] ?? '';
    $sakit_perawatan = $_POST['sakit_perawatan'] ?? '';
    $sakit_tanpa_perawatan = $_POST['sakit_tanpa_perawatan'] ?? '';
    $imunisasi = $_POST['imunisasi'] ?? '';
    $alergi_obat = $_POST['alergi_obat'] ?? '';
    $kecelakaan = $_POST['kecelakaan'] ?? '';
    $merokok = $_POST['merokok'] ?? '';
    $dirawat_rs = $_POST['dirawat_rs'] ?? '';
    $penyakit_1_tahun = $_POST['penyakit_1_tahun'] ?? '';
    $obat_2_minggu = $_POST['obat_2_minggu'] ?? '';
    $teratur_konsumsi = $_POST['teratur_konsumsi'] ?? '';
    $resep_dokter = $_POST['resep_dokter'] ?? '';
    $genogram = $_POST['genogram'] ?? '';
    $G1 = $_POST['G1'] ?? '';
    $G2 = $_POST['G2'] ?? '';
    $G3 = $_POST['G3'] ?? '';

    $created_at = date('Y-m-d H:i:s');
    $created_by = $username;

    $sql = "INSERT INTO tbl_gerontik_pengkajian_riwayat (id_identitas, keluhan_utama, riwayat_kesehatan_sekarang, berkualitas, sehat, aktif, produktif, sakit_perawatan, sakit_tanpa_perawatan, imunisasi, alergi_obat, kecelakaan, merokok, dirawat_rs, penyakit_1_tahun, obat_2_minggu, teratur_konsumsi, resep_dokter, genogram, G1, G2, G3, created_at, created_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $mysqli->prepare($sql);
    if ($stmt) {
        $stmt->bind_param('isssssssssssssssssssssss', $id_identitas, $keluhan_utama, $riwayat_kesehatan_sekarang, $berkualitas, $sehat, $aktif, $produktif, $sakit_perawatan, $sakit_tanpa_perawatan, $imunisasi, $alergi_obat, $kecelakaan, $merokok, $dirawat_rs, $penyakit_1_tahun, $obat_2_minggu, $teratur_konsumsi, $resep_dokter, $genogram, $G1, $G2, $G3, $created_at, $created_by);
        if ($stmt->execute()) {
            echo "<script>window.location.href = 'index.php?page=gerontik&tab=pengkajian-fisik&idpasien=" . urlencode($id_identitas) . "';</script>";
        } else {
            $alert = '<div class="alert alert-danger">Gagal menyimpan data: ' . htmlspecialchars($stmt->error) . '</div>';
        }
        $stmt->close();
    } else {
        $alert = '<div class="alert alert-danger">Gagal menyiapkan statement: ' . htmlspecialchars($mysqli->error) . '</div>';
    }
}
?>

<main id="main" class="main">
    <?php include "navbar_gerontik.php"; ?>

    <section class="section dashboard">
        <?php if (!empty($alert)) echo $alert; ?>
        <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data" id="form-pengkajian-riwayat">
            <input type="hidden" name="id_identitas" id="hidden-id-identitas" value="<?= htmlspecialchars($id_identitas) ?>">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><strong>II. Riwayat Kesehatan</strong></h5>
                    <!-- Bagian Keluhan Utama -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Keluhan Utama</strong></label>
                        <div class="col-sm-9">
                            <textarea name="keluhan_utama" class="form-control" rows="4" placeholder=""></textarea>
                        </div>
                    </div>
                    <!-- Bagian Riwayat Kesehatan Saat Ini -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Riwayat Kesehatan Saat Ini</strong></label>
                        <div class="col-sm-9">
                            <textarea name="riwayat_kesehatan_sekarang" class="form-control" rows="4" placeholder=""></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><strong>III. Status Lanjut Usia</strong></h5>

                    <!-- Berkualitas & Sehat -->
                    <div class="row mb-3 align-items-center">
                        <div class="col-sm-2 col-form-label"><strong>Berkualitas</strong></div>
                        <div class="col-sm-4">
                            <select class="form-select" name="berkualitas">
                                <option value="">-- Pilih --</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </div>

                        <div class="col-sm-2 col-form-label"><strong>Sehat</strong></div>
                        <div class="col-sm-4">
                            <select class="form-select" name="sehat">
                                <option value="">-- Pilih --</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </div>
                    </div>

                    <!-- Aktif & Produktif -->
                    <div class="row mb-3 align-items-center">
                        <div class="col-sm-2 col-form-label"><strong>Aktif</strong></div>
                        <div class="col-sm-4">
                            <select class="form-select" name="aktif">
                                <option value="">-- Pilih --</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </div>

                        <div class="col-sm-2 col-form-label"><strong>Produktif</strong></div>
                        <div class="col-sm-4">
                            <select class="form-select" name="produktif">
                                <option value="">-- Pilih --</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </div>
                    </div>

                    <!-- Sakit dengan/Tanpa Perawatan -->
                    <div class="row mb-3 align-items-center">
                        <div class="col-sm-2 col-form-label"><strong>Sakit dengan perawatan</strong></div>
                        <div class="col-sm-4">
                            <select class="form-select" name="sakit_perawatan">
                                <option value="">-- Pilih --</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </div>

                        <div class="col-sm-2 col-form-label"><strong>Sakit tanpa perawatan</strong></div>
                        <div class="col-sm-4">
                            <select class="form-select" name="sakit_tanpa_perawatan">
                                <option value="">-- Pilih --</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </div>
                    </div>

                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><strong>IV. Riwayat Kesehatan Masa Lalu</strong></h5>
                    <!-- Imunisasi -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">
                            <strong>Imunisasi</strong>
                        </label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="imunisasi">
                        </div>
                    </div>
                    <!-- Alergi Obat -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">
                            <strong>Alergi Obat</strong>
                        </label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="alergi_obat">
                        </div>
                    </div>
                    <!-- Kecelakaan -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">
                            <strong>Kecelakaan</strong>
                        </label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="kecelakaan">
                        </div>
                    </div>
                    <!-- Kebiasaan Merokok -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">
                            <strong>Kebiasaan Merokok</strong>
                        </label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="merokok">
                        </div>
                    </div>
                    <!-- Dirawat di Rumah Sakit -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">
                            <strong>Dirawat di Rumah Sakit</strong>
                        </label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="dirawat_rs">
                        </div>
                    </div>
                    <!-- Penyakit 1 Tahun Terakhir -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">
                            <strong>Penyakit 1 Tahun Terakhir</strong>
                        </label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="penyakit_1_tahun">
                        </div>
                    </div>
                    <!-- Nama Obat 2 Minggu -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">
                            <strong>Nama Obat (2 Minggu Terakhir)</strong>
                        </label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="obat_2_minggu">
                        </div>
                    </div>
                    <!-- Teratur Dikonsumsi -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">
                            <strong>Teratur Dikonsumsi</strong>
                        </label>
                        <div class="col-sm-9">
                            <select class="form-control" name="teratur_konsumsi">
                                <option value="">-- Pilih --</option>
                                <option>Ya</option>
                                <option>Tidak</option>
                            </select>
                        </div>
                    </div>
                    <!-- Obat Diresepkan Dokter -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">
                            <strong>Obat Diresepkan Dokter</strong>
                        </label>
                        <div class="col-sm-9">
                            <select class="form-control" name="resep_dokter">
                                <option value="">-- Pilih --</option>
                                <option>Ya</option>
                                <option>Tidak</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><strong>V. Riwayat Gerontik</strong></h5>
                    <!-- Genogram -->
                    <div class="row mb-3 align-items-start">
                        <label class="col-sm-2 col-form-label"><strong>Genogram</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="genogram">
                        </div>
                    </div>
                    <!-- Judul Keterangan -->
                    <div class="mb-3">
                        <h5><strong>Keterangan Penjelasan</strong></h5>
                    </div>
                    <!-- G1 -->
                    <div class="row mb-3 align-items-start">
                        <label class="col-sm-2 col-form-label"><strong>G1</strong></label>
                        <div class="col-sm-9">
                            <textarea class="form-control" name="G1" rows="3"></textarea>
                        </div>
                    </div>
                    <!-- G2 -->
                    <div class="row mb-3 align-items-start">
                        <label class="col-sm-2 col-form-label"><strong>G2</strong></label>
                        <div class="col-sm-9">
                            <textarea class="form-control" name="G2" rows="3"></textarea>
                        </div>
                    </div>
                    <!-- G3 -->
                    <div class="row mb-3 align-items-start">
                        <label class="col-sm-2 col-form-label"><strong>G3</strong></label>
                        <div class="col-sm-9">
                            <textarea class="form-control" name="G3" rows="3"></textarea>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-9 offset-sm-2 text-end">
                        <button type="submit" class="btn btn-primary">Lanjutkan</button>
                    </div>
                </div>
        </form>
        </div>
    </section>

    </section>
</main>

<?php include "footer_gerontik.php"; ?>