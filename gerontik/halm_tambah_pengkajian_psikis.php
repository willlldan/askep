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

        // VIII. Pengkajian Psiko Sosial dan Spiritual
        'kondisi_lansia',
        'penyesuaian_lansia',
        'prolanis_lansia',
        'periksa_kesehatan_lansia',
        'posyandu_lansia',
        'kegiatan_rt_lansia',
        'dukungan_gerontik',
        'ingatkan_pantangan',
        'senang_berkumpul',
        'rutin_ibadah',
        'bersyukur',
        'berkembang_usia',

        // IX. Pengkajian Status Fungsional (Indeks Katz)
        'makan',
        'kontinen',
        'berpindah',
        'kamar_kecil',
        'berpakaian',
        'mandi',
        'kesimpulan_status_fungsional'
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
        INSERT INTO tbl_gerontik_pengkajian_psikis
        (id_identitas, $columns, created_at, created_by)
        VALUES (?, $placeholders, ?, ?)
    ";

    $stmt = $mysqli->prepare($sql);

    if ($stmt) {
        $types = 'i' . str_repeat('s', count($data)) . 'ss';
        $values = array_merge([$id_identitas], array_values($data), [$created_at, $created_by]);

        $stmt->bind_param($types, ...$values);

        if ($stmt->execute()) {
            echo "<script>window.location.href = 'index.php?page=gerontik&tab=pengkajian-depresi&idpasien=" . urlencode($id_identitas) . "';</script>";
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

                <multipart /form-data">
                    <input type="hidden" name="id_identitas" value="<?= htmlspecialchars($id_identitas) ?>">

                    <h5 class="card-title">
                        <strong>VIII. Pengkajian Psiko Sosial dan Spiritual</strong>
                    </h5>

                    <!-- Pertanyaan 1 -->
                    <div class="row mb-3">
                        <label class="col-sm-10 col-form-label">
                            <strong>1. Lansia menyadari dan menerima kondisinya / kesehatannya tidak seperti saat muda</strong>
                        </label>
                        <div class="col-sm-2">
                            <select class="form-select" name="kondisi_lansia" required>
                                <option value="">-- Pilih --</option>
                                <option value="Y">Ya</option>
                                <option value="T">Tidak</option>
                            </select>
                        </div>
                    </div>

                    <!-- Pertanyaan 2 -->
                    <div class="row mb-3">
                        <label class="col-sm-10 col-form-label">
                            <strong>2. Lansia menyesuaikan / tidak memaksakan pekerjaan / aktivitas yang dilakukan</strong>
                        </label>
                        <div class="col-sm-2">
                            <select class="form-select" name="penyesuaian_lansia" required>
                                <option value="">-- Pilih --</option>
                                <option value="Y">Ya</option>
                                <option value="T">Tidak</option>
                            </select>
                        </div>
                    </div>

                    <!-- Pertanyaan 3 -->
                    <div class="row mb-3">
                        <label class="col-sm-10 col-form-label">
                            <strong>3. Lansia rutin mengikuti kegiatan Prolanis</strong>
                        </label>
                        <div class="col-sm-2">
                            <select class="form-select" name="prolanis_lansia" required>
                                <option value="">-- Pilih --</option>
                                <option value="Y">Ya</option>
                                <option value="T">Tidak</option>
                            </select>
                        </div>
                    </div>

                    <!-- Pertanyaan 4 -->
                    <div class="row mb-3">
                        <label class="col-sm-10 col-form-label">
                            <strong>4. Lansia rutin memeriksakan kesehatannya di praktik dokter / puskesmas</strong>
                        </label>
                        <div class="col-sm-2">
                            <select class="form-select" name="periksa_kesehatan_lansia" required>
                                <option value="">-- Pilih --</option>
                                <option value="Y">Ya</option>
                                <option value="T">Tidak</option>
                            </select>
                        </div>
                    </div>

                    <!-- Pertanyaan 5 -->
                    <div class="row mb-3">
                        <label class="col-sm-10 col-form-label">
                            <strong>5. Lansia masih mengikuti kegiatan pemeriksaan kesehatan di Posyandu lansia</strong>
                        </label>
                        <div class="col-sm-2">
                            <select class="form-select" name="posyandu_lansia" required>
                                <option value="">-- Pilih --</option>
                                <option value="Y">Ya</option>
                                <option value="T">Tidak</option>
                            </select>
                        </div>
                    </div>

                    <!-- Pertanyaan 6 -->
                    <div class="row mb-3">
                        <label class="col-sm-10 col-form-label">
                            <strong>6. Lansia masih sempat mengikuti kegiatan-kegiatan yang dilaksanakan oleh RT</strong>
                        </label>
                        <div class="col-sm-2">
                            <select class="form-select" name="kegiatan_rt_lansia" required>
                                <option value="">-- Pilih --</option>
                                <option value="Y">Ya</option>
                                <option value="T">Tidak</option>
                            </select>
                        </div>
                    </div>

                    <!-- Pertanyaan 7 -->
                    <div class="row mb-3">
                        <label class="col-sm-10 col-form-label">
                            <strong>7. Gerontik lansia memberikan dukungan dan peduli terhadap kesehatan lansia</strong>
                        </label>
                        <div class="col-sm-2">
                            <select class="form-select" name="dukungan_gerontik" required>
                                <option value="">-- Pilih --</option>
                                <option value="Y">Ya</option>
                                <option value="T">Tidak</option>
                            </select>
                        </div>
                    </div>

                    <!-- Pertanyaan 8 -->
                    <div class="row mb-3">
                        <label class="col-sm-10 col-form-label">
                            <strong>8. Gerontik mengingatkan pantangan makanan bagi kesehatan lansia</strong>
                        </label>
                        <div class="col-sm-2">
                            <select class="form-select" name="ingatkan_pantangan" required>
                                <option value="">-- Pilih --</option>
                                <option value="Y">Ya</option>
                                <option value="T">Tidak</option>
                            </select>
                        </div>
                    </div>

                    <!-- Pertanyaan 9 -->
                    <div class="row mb-3">
                        <label class="col-sm-10 col-form-label">
                            <strong>9. Lansia merasa senang bila sedang berkumpul dengan anak dan cucu</strong>
                        </label>
                        <div class="col-sm-2">
                            <select class="form-select" name="senang_berkumpul" required>
                                <option value="">-- Pilih --</option>
                                <option value="Y">Ya</option>
                                <option value="T">Tidak</option>
                            </select>
                        </div>
                    </div>

                    <!-- Pertanyaan 10 -->
                    <div class="row mb-3">
                        <label class="col-sm-10 col-form-label">
                            <strong>10. Lansia masih rutin menjalankan ibadah</strong>
                        </label>
                        <div class="col-sm-2">
                            <select class="form-select" name="rutin_ibadah" required>
                                <option value="">-- Pilih --</option>
                                <option value="Y">Ya</option>
                                <option value="T">Tidak</option>
                            </select>
                        </div>
                    </div>

                    <!-- Pertanyaan 11 -->
                    <div class="row mb-3">
                        <label class="col-sm-10 col-form-label">
                            <strong>11. Lansia merasa bersyukur kepada Tuhan YME dengan kondisinya saat ini</strong>
                        </label>
                        <div class="col-sm-2">
                            <select class="form-select" name="bersyukur" required>
                                <option value="">-- Pilih --</option>
                                <option value="Y">Ya</option>
                                <option value="T">Tidak</option>
                            </select>
                        </div>
                    </div>

                    <!-- Pertanyaan 12 -->
                    <div class="row mb-4">
                        <label class="col-sm-10 col-form-label">
                            <strong>12. Lansia menganggap bahwa semakin bertambahnya usia semakin mendekatkan diri kepada Tuhan YME</strong>
                        </label>
                        <div class="col-sm-2">
                            <select class="form-select" name="berkembang_usia" required>
                                <option value="">-- Pilih --</option>
                                <option value="Y">Ya</option>
                                <option value="T">Tidak</option>
                            </select>
                        </div>
                    </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><strong>IX. Pengkajian Status Fungsional</strong></h5>

                <p>
                    Pengkajian status fungsional adalah suatu bentuk pengukuran kemampuan seseorang untuk melakukan aktivitas sehari-hari secara mandiri. Penentuan kemandirian fungsional dapat mengidentifikasi kemampuan dan keterbatasan klien sehingga memudahkan pemilihan intervensi yang tepat.
                </p>
                <p>
                    Pengkajian ini menggunakan indeks kemandirian Katz untuk aktivitas kehidupan sehari-hari yang berdasarkan pada evaluasi fungsi kemandirian atau tergantung dari klien dalam hal makan, kontinen (defekasi/berkemih), berpindah, ke kamar kecil, pakaian, dan mandi. Silakan dicentang sesuai dengan kondisi lansia.
                </p>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Kegiatan</th>
                            <th>Status Fungsional</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Makan</td>
                            <td>
                                <select class="form-select" name="makan" required>
                                    <option value="">-- Pilih --</option>
                                    <option value="mandiri">Mandiri</option>
                                    <option value="tergantung">Tergantung</option>
                                </select>
                            </td>
                        </tr>

                        <tr>
                            <td>Kontinen (Defekasi/Berkemih)</td>
                            <td>
                                <select class="form-select" name="kontinen" required>
                                    <option value="">-- Pilih --</option>
                                    <option value="mandiri">Mandiri</option>
                                    <option value="tergantung">Tergantung</option>
                                </select>
                            </td>
                        </tr>

                        <tr>
                            <td>Berpindah</td>
                            <td>
                                <select class="form-select" name="berpindah" required>
                                    <option value="">-- Pilih --</option>
                                    <option value="mandiri">Mandiri</option>
                                    <option value="tergantung">Tergantung</option>
                                </select>
                            </td>
                        </tr>

                        <tr>
                            <td>Ke kamar kecil</td>
                            <td>
                                <select class="form-select" name="kamar_kecil" required>
                                    <option value="">-- Pilih --</option>
                                    <option value="mandiri">Mandiri</option>
                                    <option value="tergantung">Tergantung</option>
                                </select>
                            </td>
                        </tr>

                        <tr>
                            <td>Berpakaian</td>
                            <td>
                                <select class="form-select" name="berpakaian" required>
                                    <option value="">-- Pilih --</option>
                                    <option value="mandiri">Mandiri</option>
                                    <option value="tergantung">Tergantung</option>
                                </select>
                            </td>
                        </tr>

                        <tr>
                            <td>Mandi</td>
                            <td>
                                <select class="form-select" name="mandi" required>
                                    <option value="">-- Pilih --</option>
                                    <option value="mandiri">Mandiri</option>
                                    <option value="tergantung">Tergantung</option>
                                </select>
                            </td>
                        </tr>

                        <tr>
                            <td><strong>Kesimpulan Status Fungsional</strong></td>
                            <td>
                                <input type="text"
                                    class="form-control"
                                    name="kesimpulan_status_fungsional"
                                    placeholder="Kesimpulan"
                                    required>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <table class="table">
                    <thead>
                        <tr>
                            <th>Ketentuan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>A</td>
                            <td>Kemandirian dalam hal makan, kontinen (defekasi/berkemih), berpindah, ke kamar kecil, berpakaian, dan mandi.</td>
                        </tr>
                        <tr>
                            <td>B</td>
                            <td>Kemandirian dalam semua hal kecuali satu dari fungsi tersebut.</td>
                        </tr>
                        <tr>
                            <td>C</td>
                            <td>Kemandirian dalam semua hal kecuali mandi dan satu fungsi tambahan.</td>
                        </tr>
                        <tr>
                            <td>D</td>
                            <td>Kemandirian dalam semua hal kecuali mandi, berpakaian, dan satu fungsi tambahan.</td>
                        </tr>
                        <tr>
                            <td>E</td>
                            <td>Kemandirian dalam semua hal kecuali mandi, berpakaian, ke kamar kecil, dan satu fungsi tambahan.</td>
                        </tr>
                        <tr>
                            <td>F</td>
                            <td>Kemandirian dalam semua hal kecuali mandi, berpakaian, ke kamar kecil, berpindah, dan satu fungsi tambahan.</td>
                        </tr>
                        <tr>
                            <td>G</td>
                            <td>Ketergantungan pada keenam fungsi tersebut.</td>
                        </tr>
                    </tbody>
                </table>


                <h5 class="card-title"><strong>Penjelasan</strong></h5>

                <p>Kemandirian berarti tanpa pengawasan, pengarahan, atau bantuan pribadi aktif, kecuali secara spesifik akan digambarkan di bawah ini.</p>
                <p>Pengkajian ini didasarkan pada kondisi aktual klien dan bukan pada kemampuan. Artinya, jika klien menolak untuk melakukan suatu fungsi, dianggap sebagai tidak melakukan fungsi meskipun ia sebenarnya mampu.</p>


                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Kegiatan</th>
                            <th>Mandiri</th>
                            <th>Tergantung</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Makan</td>
                            <td>Memilih makanan dari piring dan menyuapi sendiri</td>
                            <td>Bantuan dalam hal mengambil makanan dan menyuapinya, tidak makan sama sekali, makan parenteral/enteral.</td>
                        </tr>
                        <tr>
                            <td>Kontinen (Defekasi/Berkemih)</td>
                            <td>Berkemih dan defekasi sepenuhnya dikendalikan sendiri</td>
                            <td>Inkontinensia parsial atau total, penggunaan kateter, pispot, enema, pembalut (diapers)</td>
                        </tr>
                        <tr>
                            <td>Berpindah</td>
                            <td>Berpindah ke dan dari tempat tidur, bangkit dari kursi sendiri</td>
                            <td>Bantuan dalam naik dan turun dari tempat tidur atau kursi, tidak melakukan satu atau lebih perpindahan</td>
                        </tr>
                        <tr>
                            <td>Ke Kamar Kecil</td>
                            <td>Masuk dan keluar kamar kecil, membersihkan genitalia sendiri</td>
                            <td>Menerima bantuan untuk masuk ke kamar kecil dan menggunakan pispot</td>
                        </tr>
                        <tr>
                            <td>Berpakaian</td>
                            <td>Memilih baju dari lemari, memakai dan melepaskan pakaian, mengancing atau mengikat pakaian</td>
                            <td>Tidak dapat memakai baju sendiri atau sebagian</td>
                        </tr>
                        <tr>
                            <td>Mandi</td>
                            <td>Bantuan hanya pada satu bagian tubuh (seperti punggung atau ekstremitas yang tidak mampu) atau mandi sepenuhnya sendiri</td>
                            <td>Bantuan mandi lebih dari satu bagian tubuh, bantuan masuk dan keluar dari bak mandi, tidak mandi sendiri</td>
                        </tr>
                    </tbody>
                </table>
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