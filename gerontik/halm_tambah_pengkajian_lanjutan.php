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

   
    var_dump($_POST); // Debug: Tampilkan data POST yang diterima
    die;

    $created_at = date('Y-m-d H:i:s');
    $created_by = $username;

    $fields = [
        // XI. APGAR Gerontik
        'A',
        'P',
        'G',
        'A2',
        'R',
        'kesimpulan_apgar',

        // XII. SPMSQ
        'q1',
        'q2',
        'q3',
        'q4',
        'q5',
        'q6',
        'q7',
        'q8',
        'q9',
        'q10',
        'jawaban1',
        'jawaban2',
        'jawaban3',
        'jawaban4',
        'jawaban5',
        'jawaban6',
        'jawaban7',
        'jawaban8',
        'jawaban9',
        'jawaban10',
        'kesimpulan_spmsq',

        // XIII. Risiko Jatuh
        'riwayat_jatuh_1',
        'riwayat_jatuh_2',
        'status_mental_1',
        'status_mental_2',
        'status_mental_3',
        'penglihatan_1',
        'penglihatan_2',
        'penglihatan_3',
        'berkemih',
        'transfer_1',
        'transfer_2',
        'transfer_3',
        'transfer_4',
        'mobilitas_1',
        'mobilitas_2',
        'mobilitas_3',
        'mobilitas_4',
        'skor_riwayat_jatuh_1',
        'skor_status_mental_1',
        'skor_penglihatan_1',
        'skor_berkemih',
        'skor_transfer_1',
        'kesimpulan_penilaian',

        // XIV. Klasifikasi Data
        'nodx',
        'implementasi',

        // XV. Analisa Data
        'no',
        'DATA',
        'ETILOGI',
        'MASALAH'
    ];

    $data = [];
    foreach ($fields as $field) {
        $data[$field] = $_POST[$field] ?? '';
    }

    $columns = implode(', ', array_keys($data));
    $placeholders = implode(', ', array_fill(0, count($data), '?'));

    $sql = "
        INSERT INTO tbl_gerontik_pengkajian_lanjutan
        (id_identitas, $columns, created_at, created_by)
        VALUES (?, $placeholders, ?, ?)
    ";

    $stmt = $mysqli->prepare($sql);
    $types = 'i' . str_repeat('s', count($data)) . 'ss';
    $values = array_merge([$id_identitas], array_values($data), [$created_at, $created_by]);

    $stmt->bind_param($types, ...$values);
    $stmt->execute();
    $stmt->close();
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
                    <h5 class="card-title"><strong>XI. APGAR Gerontik</strong></h5>

                    <p>APGAR Gerontik ditujukan untuk mengkaji fungsi sosial lansia. A (Adaptasi), P (Partnership), G (Growth), A (Affection), R (Resolve)</p>
                    <p><strong>Penilaian:</strong></p>
                    <ul>
                        <li>&lt; 3: disfungsi Gerontik sangat tinggi</li>
                        <li>4-6: disfungsi Gerontik sedang</li>
                        <li>7-8: ringan</li>
                        <li>9-10: normal</li>
                    </ul>
                    <p><strong>Petunjuk:</strong> Silakan memberikan tanda centang pada pilihan jawaban yang sesuai dengan pernyataan Lansia.</p>
                    <!-- Pertanyaan A -->
                    <div class="question">
                        <label for="A">A. Saya puas bisa kembali pada Gerontik (teman) saya untuk membantu saya bila suatu waktu ada kondisi yang menyusahkan saya:</label><br>
                        <input type="radio" id="A_selalu" name="A" value="2">
                        <label for="A_selalu">Selalu (2)</label>
                        <input type="radio" id="A_kadang" name="A" value="1">
                        <label for="A_kadang">Kadang (1)</label>
                        <input type="radio" id="A_tidak_pernah" name="A" value="0">
                        <label for="A_tidak_pernah">Tidak pernah (0)</label>
                    </div>

                    <!-- Pertanyaan P -->
                    <div class="question">
                        <label for="P">P. Saya puas dengan cara Gerontik (teman) saya membicarakan sesuatu dan mengungkapkan masalah dengan saya:</label><br>
                        <input type="radio" id="P_selalu" name="P" value="2">
                        <label for="P_selalu">Selalu (2)</label>
                        <input type="radio" id="P_kadang" name="P" value="1">
                        <label for="P_kadang">Kadang (1)</label>
                        <input type="radio" id="P_tidak_pernah" name="P" value="0">
                        <label for="P_tidak_pernah">Tidak pernah (0)</label>
                    </div>

                    <!-- Pertanyaan G -->
                    <div class="question">
                        <label for="G">G. Saya puas bahwa Gerontik (teman) saya menerima dan mendukung keinginan untuk melakukan aktivitas:</label><br>
                        <input type="radio" id="G_selalu" name="G" value="2">
                        <label for="G_selalu">Selalu (2)</label>
                        <input type="radio" id="G_kadang" name="G" value="1">
                        <label for="G_kadang">Kadang (1)</label>
                        <input type="radio" id="G_tidak_pernah" name="G" value="0">
                        <label for="G_tidak_pernah">Tidak pernah (0)</label>
                    </div>

                    <!-- Pertanyaan A2 -->
                    <div class="question">
                        <label for="A2">A. Saya puas dengan cara Gerontik (teman) saya mengekspresikan afek dan berespon terhadap emosi saya seperti marah, sedih, atau mencintai:</label><br>
                        <input type="radio" id="A2_selalu" name="A2" value="2">
                        <label for="A2_selalu">Selalu (2)</label>
                        <input type="radio" id="A2_kadang" name="A2" value="1">
                        <label for="A2_kadang">Kadang (1)</label>
                        <input type="radio" id="A2_tidak_pernah" name="A2" value="0">
                        <label for="A2_tidak_pernah">Tidak pernah (0)</label>
                    </div>

                    <!-- Pertanyaan R -->
                    <div class="question">
                        <label for="R">R. Saya puas dengan cara teman saya menyediakan waktu secara bersama-sama:</label><br>
                        <input type="radio" id="R_selalu" name="R" value="2">
                        <label for="R_selalu">Selalu (2)</label>
                        <input type="radio" id="R_kadang" name="R" value="1">
                        <label for="R_kadang">Kadang (1)</label>
                        <input type="radio" id="R_tidak_pernah" name="R" value="0">
                        <label for="R_tidak_pernah">Tidak pernah (0)</label>
                    </div>

                    <!-- Kesimpulan APGAR Gerontik -->
                    <div style="text-align: left; margin-top: 20px;">
                        <p><em>Kesimpulan APGAR Gerontik:</em></p>
                        <form>
                            <div class="d-flex align-items-center">
                                <textarea name="kesimpulan_apgar" rows="4" cols="50" placeholder="" class="form-control"></textarea>
                                <div class="col-sm-2 d-flex justify-content-start align-items-center ms-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" onchange="document.getElementById('commentusiaistri').style.display = this.checked ? 'none' : 'block'">
                                    </div>
                                </div>
                            </div>
                            <textarea class="form-control mt-2" rows="2" placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                        </form>
                    </div>
            </div>
        </div>
        </div>
        <div class="card">
            <div class="card-body">

                <!-- General Form Elements -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

                    <h5 class="card-title"><strong>XII. Pemeriksaan Short Portable Status Questionnaire (SPMSQ)</strong></h5>

                    <p>Digunakan untuk mendeteksi adanya tingkat gangguan intelektual/memori. Catatlah jumlah kesalahan dari semua pertanyaan, berikan tanda centang pada kolom B (Jawaban lansia benar) atau S (Jawaban lansia salah).</p>

                    <form action="#">
                        <table style="width:100%; margin-top: 20px; text-align: left;">
                            <thead>
                                <tr>
                                    <th>B</th>
                                    <th>S</th>
                                    <th>No</th>
                                    <th>Pertanyaan</th>
                                    <th>Jawaban Lansia</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Pertanyaan 1 -->
                                <tr>
                                    <td><input type="radio" name="q1" value="B"></td>
                                    <td><input type="radio" name="q1" value="S"></td>
                                    <td>1</td>
                                    <td>Tanggal berapa hari ini? (Hari/Tanggal/Tahun)</td>
                                    <td><input type="text" name="jawaban1"></td>
                                </tr>

                                <!-- Pertanyaan 2 -->
                                <tr>
                                    <td><input type="radio" name="q2" value="B"></td>
                                    <td><input type="radio" name="q2" value="S"></td>
                                    <td>2</td>
                                    <td>Hari apa sekarang?</td>
                                    <td><input type="text" name="jawaban2"></td>
                                </tr>

                                <!-- Pertanyaan 3 -->
                                <tr>
                                    <td><input type="radio" name="q3" value="B"></td>
                                    <td><input type="radio" name="q3" value="S"></td>
                                    <td>3</td>
                                    <td>Apa nama tempat/kelurahan ini?</td>
                                    <td><input type="text" name="jawaban3"></td>
                                </tr>

                                <!-- Pertanyaan 4 -->
                                <tr>
                                    <td><input type="radio" name="q4" value="B"></td>
                                    <td><input type="radio" name="q4" value="S"></td>
                                    <td>4</td>
                                    <td>Dimana alamat lengkap Anda?</td>
                                    <td><input type="text" name="jawaban4"></td>
                                </tr>

                                <!-- Pertanyaan 5 -->
                                <tr>
                                    <td><input type="radio" name="q5" value="B"></td>
                                    <td><input type="radio" name="q5" value="S"></td>
                                    <td>5</td>
                                    <td>Berapa umur Anda?</td>
                                    <td><input type="text" name="jawaban5"></td>
                                </tr>

                                <!-- Pertanyaan 6 -->
                                <tr>
                                    <td><input type="radio" name="q6" value="B"></td>
                                    <td><input type="radio" name="q6" value="S"></td>
                                    <td>6</td>
                                    <td>Kapan Anda lahir?</td>
                                    <td><input type="text" name="jawaban6"></td>
                                </tr>

                                <!-- Pertanyaan 7 -->
                                <tr>
                                    <td><input type="radio" name="q7" value="B"></td>
                                    <td><input type="radio" name="q7" value="S"></td>
                                    <td>7</td>
                                    <td>Presiden Indonesia sekarang?</td>
                                    <td><input type="text" name="jawaban7"></td>
                                </tr>

                                <!-- Pertanyaan 8 -->
                                <tr>
                                    <td><input type="radio" name="q8" value="B"></td>
                                    <td><input type="radio" name="q8" value="S"></td>
                                    <td>8</td>
                                    <td>Siapa nama presidennya sebelumnya?</td>
                                    <td><input type="text" name="jawaban8"></td>
                                </tr>

                                <!-- Pertanyaan 9 -->
                                <tr>
                                    <td><input type="radio" name="q9" value="B"></td>
                                    <td><input type="radio" name="q9" value="S"></td>
                                    <td>9</td>
                                    <td>Siapa nama kecil ibu Anda?</td>
                                    <td><input type="text" name="jawaban9"></td>
                                </tr>

                                <!-- Pertanyaan 10 -->
                                <tr>
                                    <td><input type="radio" name="q10" value="B"></td>
                                    <td><input type="radio" name="q10" value="S"></td>
                                    <td>10</td>
                                    <td>Perhitungan: 20-3 = … kemudian hasilnya dikurangi 3 terus hasilnya lagi dikurang 3 sampai mendapat angka 0.</td>
                                    <td><input type="text" name="jawaban10"></td>
                                </tr>
                            </tbody>
                        </table>


                        <!-- Kesimpulan Pemeriksaan SPMSQ -->
                        <div style="text-align: left; margin-top: 20px;">
                            <p><em>Kesimpulan Pemeriksaan SPMSQ:</em></p>
                            <form>
                                <div class="d-flex align-items-center">
                                    <textarea name="kesimpulan_spmsq" rows="4" cols="50" placeholder="Masukkan kesimpulan di sini..." class="form-control"></textarea>
                                    <div class="col-sm-2 d-flex justify-content-start align-items-center ms-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" onchange="document.getElementById('commentusiaistri').style.display = this.checked ? 'none' : 'block'">
                                        </div>
                                    </div>
                                </div>
                                <textarea class="form-control mt-2" rows="2" placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                            </form>
                        </div>

                        <!-- General Form Elements -->
                        <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

                            <h5 class="card-title"><strong>Kriteria Penilaian</strong></h5>
                            <!-- Kriteria Penilaian -->

                            <p>Kesalahan 0-2 = Fungsi intelektual utuh</p>
                            <p>Kesalahan 3-4 = Gangguan intelektual ringan</p>
                            <p>Kesalahan 5-7 = Gangguan intelektual sedang</p>
                            <p>Kesalahan 8-10 = Gangguan intelektual berat</p>
            </div>
        </div>
        </div>
        <div class="card">
            <div class="card-body">

                <!-- General Form Elements -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

                    <h5 class="card-title"><strong>XIII. Skala Jatuh Pada Lansia - Ontario Modified Stratify-Sydney Scoring</strong></h5>

                    <div class="container">
                        <form action="#">
                            <table border="1" cellpadding="10" cellspacing="0" style="width:100%; margin-top: 20px; text-align: left;">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Parameter</th>
                                        <th>Skrining</th>
                                        <th>Jawaban</th>
                                        <th>Keterangan Nilai</th>
                                        <th>Skor</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Riwayat Jatuh -->
                                    <tr>
                                        <td>1</td>
                                        <td>Riwayat jatuh</td>
                                        <td>1. Apakah pasien datang ke rumah sakit karena jatuh?</td>
                                        <td>
                                            <input type="radio" name="riwayat_jatuh_1" value="Y"> Ya
                                            <input type="radio" name="riwayat_jatuh_1" value="T"> Tidak
                                        </td>
                                        <td>Jika jawaban "Ya" = 6</td>
                                        <td><input type="number" name="skor_riwayat_jatuh_1" placeholder="Masukkan skor" min="0" max="6"></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td>2. Jika tidak, apakah klien pernah jatuh dalam dua bulan terakhir?</td>
                                        <td>
                                            <input type="radio" name="riwayat_jatuh_2" value="Y"> Ya
                                            <input type="radio" name="riwayat_jatuh_2" value="T"> Tidak
                                        </td>
                                        <td></td>
                                        <td></td>
                                    </tr>

                                    <!-- Status Mental -->
                                    <tr>
                                        <td>2</td>
                                        <td>Status mental</td>
                                        <td>1. Apakah pasien derilium (tidak dapat membuat keputusan, gangguan daya ingat)?</td>
                                        <td>
                                            <input type="radio" name="status_mental_1" value="Y"> Ya
                                            <input type="radio" name="status_mental_1" value="T"> Tidak
                                        </td>
                                        <td>Jika jawaban "Ya" = 14</td>
                                        <td><input type="number" name="skor_status_mental_1" placeholder="Masukkan skor" min="0" max="14"></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td>2. Apakah pasien disorientasi (salah menyebutkan waktu dan tempat)?</td>
                                        <td>
                                            <input type="radio" name="status_mental_2" value="Y"> Ya
                                            <input type="radio" name="status_mental_2" value="T"> Tidak
                                        </td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td>3. Apakah pasien mengalami agitasi (ketakutan, gelisah, dan cemas)?</td>
                                        <td>
                                            <input type="radio" name="status_mental_3" value="Y"> Ya
                                            <input type="radio" name="status_mental_3" value="T"> Tidak
                                        </td>
                                        <td></td>
                                        <td></td>
                                    </tr>

                                    <!-- Penglihatan -->
                                    <tr>
                                        <td>3</td>
                                        <td>Penglihatan</td>
                                        <td>1. Apakah pasien menggunakan kacamata?</td>
                                        <td>
                                            <input type="radio" name="penglihatan_1" value="Y"> Ya
                                            <input type="radio" name="penglihatan_1" value="T"> Tidak
                                        </td>
                                        <td>Jika jawaban "Ya" = 1</td>
                                        <td><input type="number" name="skor_penglihatan_1" placeholder="Masukkan skor" min="0" max="1"></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td>2. Apakah pasien mengeluh penglihatan buram?</td>
                                        <td>
                                            <input type="radio" name="penglihatan_2" value="Y"> Ya
                                            <input type="radio" name="penglihatan_2" value="T"> Tidak
                                        </td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td>3. Apakah pasien mempunyai glaukoma/katarak/degenerasi makula?</td>
                                        <td>
                                            <input type="radio" name="penglihatan_3" value="Y"> Ya
                                            <input type="radio" name="penglihatan_3" value="T"> Tidak
                                        </td>
                                        <td></td>
                                        <td></td>
                                    </tr>

                                    <!-- Kebiasaan Berkemih -->
                                    <tr>
                                        <td>4</td>
                                        <td>Kebiasaan Berkemih</td>
                                        <td>Apakah terdapat perubahan perilaku berkemih (urgensi, frekuensi, inkontinensia, nokturia)?</td>
                                        <td>
                                            <input type="radio" name="berkemih" value="Y"> Ya
                                            <input type="radio" name="berkemih" value="T"> Tidak
                                        </td>
                                        <td>Jika jawaban "Ya" = 2</td>
                                        <td><input type="number" name="skor_berkemih" placeholder="Masukkan skor" min="0" max="2"></td>
                                    </tr>

                                    <!-- Transfer (Berpindah Tempat) -->
                                    <tr>
                                        <td>5</td>
                                        <td>Transfer (Berpindah Tempat)</td>
                                        <td>1. Mandiri (boleh memakai alat bantu):</td>
                                        <td><input type="radio" name="transfer_1" value="0"> 0</td>
                                        <td>Jumlah (gabungan nilai) </td>
                                        <td><input type="number" name="skor_transfer_1" placeholder="Masukkan skor" min="0" max="0"></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td>2. Memerlukan sedikit bantuan orang dewasa (1 orang):</td>
                                        <td><input type="radio" name="transfer_2" value="1"> 1</td>
                                        <td>transfer dan mobilitas</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td>3. Bantuan yang nyata 2 orang:</td>
                                        <td><input type="radio" name="transfer_3" value="2"> 2</td>
                                        <td>Jika nilai 0-3 maka skornya 0</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td>4. Tidak dapat duduk seimbang, perlu bantuan total:</td>
                                        <td><input type="radio" name="transfer_4" value="3"> 3</td>
                                        <td>Jika nilai 4-6 maka skornya 7</td>
                                        <td></td>
                                    </tr>

                                    <!-- Mobilitas -->
                                    <tr>
                                        <td>6</td>
                                        <td>Mobilitas</td>
                                        <td>1. Mandiri (boleh menggunakan alat):</td>
                                        <td><input type="radio" name="mobilitas_1" value="0"> 0</td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td>2. Berjalan dengan bantuan 1 orang (fisik/verbal):</td>
                                        <td><input type="radio" name="mobilitas_2" value="1"> 1</td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td>3. Menggunakan kursi roda:</td>
                                        <td><input type="radio" name="mobilitas_3" value="2"> 2</td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td>4. Imobilisasi:</td>
                                        <td><input type="radio" name="mobilitas_4" value="3"> 3</td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>

                            <p>0-5 : risiko rendah, 6-16 : risiko sedang, 17-30 : risiko tinggi</p>

                            <!-- Kesimpulan Penilaian -->
                            <div style="text-align: left; margin-top: 20px;">
                                <p><em>Kesimpulan Penilaian:</em></p>
                                <form>
                                    <div class="d-flex align-items-center">
                                        <textarea name="kesimpulan_penilaian" rows="4" cols="50" placeholder="Masukkan kesimpulan di sini..." class="form-control"></textarea>
                                        <div class="col-sm-2 d-flex justify-content-start align-items-center ms-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" onchange="document.getElementById('commentusiaistri').style.display = this.checked ? 'none' : 'block'">
                                            </div>
                                        </div>
                                    </div>
                                    <textarea class="form-control mt-2" rows="2" placeholder="Ketikkan revisi jika tidak di ACC" readonly></textarea>
                                </form>
                            </div>
                        </form>
                    </div>
            </div>
        </div>
        </div>

        <div class="card">
            <div class="card-body">

                <!-- General Form Elements -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

                    <h5 class="card-title"><strong>XIV. Format Klasifikasi Data</strong></h5>



                    <section class="section dashboard">

                        <!-- General Form Elements -->
                        <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

                            <!-- Bagian No. DX -->

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label"><strong>DATA SUBJEKTIF</strong></label>

                                <div class="col-sm-9">
                                    <textarea name="nodx" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                                    <!-- comment -->
                                    <textarea class="form-control mt-2" id="commentnodx" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                                        oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                                </div>

                                <div class="col-sm-1 d-flex align-items-start">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" disabled>
                                    </div>
                                </div>
                            </div>



                            <!-- Bagian Implementasi -->

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label"><strong>DATA OBJEKTIF</strong></label>

                                <div class="col-sm-9">
                                    <textarea name="implementasi" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                                    <!-- comment -->
                                    <textarea class="form-control mt-2" id="commentimplementasi" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                                        oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                                </div>

                                <div class="col-sm-1 d-flex align-items-start">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" disabled>
                                    </div>
                                </div>
                            </div>

                            <!-- Bagian Button -->
                            <div class="row mb-3">
                                <div class="col-sm-11 justify-content-end d-flex">
                                    <button type="submit" name="submit" class="btn btn-primary">Simpan</button>
                                </div>
                            </div>

                            <h5 class="card-title mt-2"><strong>Format Klasifikasi Data</strong></h5>

                            <style>
                                .table-pemeriksaan {
                                    table-layout: fixed;
                                    width: 100%
                                }

                                .table-pemeriksaan td,
                                .table-pemeriksaan th {
                                    word-wrap: break-word;
                                    white-space: normal;
                                    vertical-align: top;
                                }
                            </style>

                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center">DATA SUBJEKTIF </th>
                                        <th class="text-center">DATA OBJEKTIF</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    <?php
                                    if (!empty($data)) {
                                        foreach ($data as $row) {
                                            echo "<tr>
                            <td>" . $row['DATA_SUBJEKTIF '] . "</td>
                            <td>" . $row['DATA_OBJEKTIF'] . "</td>
                            </tr>";
                                        }
                                    }
                                    ?>

                                </tbody>
                            </table>
            </div>
        </div>
        </div>

        <section class="section dashboard">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-1"><strong>XV. Analisa Data</strong></h5>

                    <!-- General Form Elements -->
                    <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

                        <!-- Bagian No. DX -->

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>No</strong></label>

                            <div class="col-sm-9">
                                <textarea name="no" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                                <!-- comment -->
                                <textarea class="form-control mt-2" id="commentnodx" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                                    oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                            </div>

                            <div class="col-sm-1 d-flex align-items-start">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" disabled>
                                </div>
                            </div>
                        </div>

                        <!-- Bagian Hari/Tanggal -->

                        <div class="row mb-3">
                            <label for="hari_tgl" class="col-sm-2 col-form-label"><strong>DATA</strong></label>

                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="DATA" name="DATA">

                                <!-- comment -->
                                <textarea class="form-control mt-2" id="commenthari_tgl" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                                    oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                            </div>

                            <div class="col-sm-1 d-flex align-items-start">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" disabled>
                                </div>
                            </div>
                        </div>
                        <!-- Bagian Jam -->

                        <div class="row mb-3">
                            <label for="jam" class="col-sm-2 col-form-label"><strong>ETILOGI</strong></label>

                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="ETILOGI" name="ETILOGI">

                                <!-- comment -->
                                <textarea class="form-control mt-2" id="commentjam" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                                    oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                            </div>

                            <div class="col-sm-1 d-flex align-items-start">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" disabled>
                                </div>
                            </div>
                        </div>

                        <!-- Bagian Implementasi -->

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>MASALAH</strong></label>

                            <div class="col-sm-9">
                                <textarea name="MASALAH" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                                <!-- comment -->
                                <textarea class="form-control mt-2" id="commentimplementasi" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                                    oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                            </div>

                            <div class="col-sm-1 d-flex align-items-start">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" disabled>
                                </div>
                            </div>
                        </div>

                        <!-- Bagian Button -->
                        <div class="row mb-3">
                            <div class="col-sm-11 justify-content-end d-flex">
                                <button type="submit" name="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </div>

                        <h5 class="card-title mt-2"><strong>Analisa Data</strong></h5>

                        <style>
                            .table-pemeriksaan {
                                table-layout: fixed;
                                width: 100%
                            }

                            .table-pemeriksaan td,
                            .table-pemeriksaan th {
                                word-wrap: break-word;
                                white-space: normal;
                                vertical-align: top;
                            }
                        </style>

                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">DATA</th>
                                    <th class="text-center">ETILOGI</th>
                                    <th class="text-center">MASALAH</th>
                                </tr>
                            </thead>

                            <tbody>

                                <?php
                                if (!empty($data)) {
                                    foreach ($data as $row) {
                                        echo "<tr>
                            <td>" . $row['NO'] . "</td>
                            <td>" . $row['DATA'] . "</td>
                            <td>" . $row['ETILOGI'] . "</td>
                            <td>" . $row['MASALAH'] . "</td>
                            </tr>";
                                    }
                                }
                                ?>

                            </tbody>
                        </table>


                    </form>
                </div>
            </div>

        </section>
    </section>
</main>

<?php include "footer_gerontik.php"; ?>