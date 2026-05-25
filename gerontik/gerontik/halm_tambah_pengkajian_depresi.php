<?php

require_once "koneksi.php";
require_once "utils.php";

$username = $_SESSION['username'];
$identitas_result = $mysqli->query("SELECT id, nama, tempat_lahir, tgl_lahir FROM tbl_gerontik_identitas WHERE created_by = '$username' ORDER BY created_at DESC");

$questions = [
    1  => 'Apakah pada dasarnya Anda puas dengan kehidupan Anda?',
    2  => 'Sudahkah Anda banyak menghentikan aktivitas dan minat Anda?',
    3  => 'Apakah Anda merasa bahwa hidup Anda kosong?',
    4  => 'Apakah Anda sering bosan?',
    5  => 'Apakah Anda banyak berharap pada masa depan?',
    6  => 'Apakah Anda takut sesuatu akan terjadi pada Anda?',
    7  => 'Apakah Anda merasa terganggu dengan pemikiran bahwa Anda tidak bisa lepas dari pikiran yang sama?',
    8  => 'Apakah Anda takut bahwa suatu hal yang buruk akan menimpa Anda?',
    9  => 'Apakah Anda merasa gembira dalam sebagian besar waktu Anda?',
    10 => 'Apakah Anda merasa tidak mungkin tertolong?',
    11 => 'Apakah Anda sering menjadi gelisah atau sering / mudah terkejut?',
    12 => 'Apakah anda lebih suka tinggal di rumah pada malam hari dari pada pergi dan melakukan sesuatu yang baru?',
    13 => 'Apakah Anda sering mengkhawatirkan masa depan?',
    14 => 'Apakah anda merasa bahwa anda mempunyai lebih banyak masalah dengan ingatan anda dari pada yang lainnya?',
    15 => 'Apakah anda berfikir sangat menyenangkan hidup sekarang ini ?',
    16 => 'Apakah Anda sering merasa tidak enak hati atau sedih ?',
    17 => 'Apakah anda sering merasa benar-benar tidak berharga saat ini?',
    18 => 'Apakah Anda cukup sering khawatir mengenai masa lampau?',
    19 => 'Apakah Anda merasa kehidupan itu menyenangkan?',
    20 => 'Apakah sulit bagi Anda memulai hal yang baru?',
    21 => 'Apakah anda merasa penuh berenergi / semangat?',
    22 => 'Apakah anda berfikir bahwa situasi anda menggambarkan keputusasaan/ tidak ada harapan ?',
    23 => 'Apakah anda berfikir bahwa banyak orang yang lebih baik dari pada anda ?',
    24 => 'Apakah Anda sering menjadi kesal dikarenakan hal kecil?',
    25 => 'Apakah anda sering merasakan menangis?',
    26 => 'Apakah Anda merasa kesulitan untuk berkonsentrasi?',
    27 => 'APakah Anda menikimati bangun pagi setiap hari?',
    28 => 'Apakah Anda lebih menghindar dari perkumpulan sosial ?',
    29 => 'Apakah mudah bagi Anda membuat keputusan ?',
    30 => 'Apakah pemikiran / benak Anda sejernih di masa-masa lalu?',
];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_identitas = isset($_POST['id_identitas']) ? intval($_POST['id_identitas']) : (isset($_GET['idpasien']) ? intval($_GET['idpasien']) : 0);
} else {
    $id_identitas = isset($_GET['idpasien']) ? intval($_GET['idpasien']) : 0;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $created_at = date('Y-m-d H:i:s');
    $created_by = $username;

    $fields = [
        // Pertanyaan 1–30
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
        'q11',
        'q12',
        'q13',
        'q14',
        'q15',
        'q16',
        'q17',
        'q18',
        'q19',
        'q20',
        'q21',
        'q22',
        'q23',
        'q24',
        'q25',
        'q26',
        'q27',
        'q28',
        'q29',
        'q30',

        // Kesimpulan
        'kesimpulan'
    ];

    $data = [];
    foreach ($fields as $field) {
        $data[$field] = $_POST[$field] ?? '';
    }

    $columns = implode(', ', array_keys($data));
    $placeholders = implode(', ', array_fill(0, count($data), '?'));

    $sql = "
        INSERT INTO tbl_gerontik_skala_depresi
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
                    <h5 class="card-title"><strong>X. Skala Depresi Geriatric</strong></h5>

                    <h6>Pengkajian ini menggunakan skala depresi geriatric bentuk singkat dari Yesavage (1983)
                        yang instrumennya disusun secara khusus digunakan pada lanjut usia untuk memeriksa depresi.
                        Jawaban pertanyaan sesuai dengan indikasi yang dinilai. Nilai 5 atau lebih dapat menandakan depresi.</h6>
                    <!-- Pertanyaan 1 - 10 -->
                    <style>
                        .question {
                            display: flex;
                            align-items: center;
                            margin-bottom: 10px;
                        }

                        .question-label {
                            flex: 1;
                            margin-right: 16px;
                        }

                        .question-options label {
                            margin-right: 12px;
                            margin-left: 4px;
                        }
                    </style>

                    <?php foreach ($questions as $num => $text): ?>
                        <div class="question">
                            <span class="question-label">
                                <?= $num . '. ' . htmlspecialchars($text) ?>
                            </span>
                            <span class="question-options">
                                <select name="q<?= $num ?>" class="form-select">
                                    <option value="">-- Pilih --</option>
                                    <option value="Ya">Ya</option>
                                    <option value="Tidak">Tidak</option>
                                </select>
                            </span>
                        </div>
                    <?php endforeach; ?>


                    <!-- Kesimpulan Skala Depresi -->
                    <div style="text-align: left; margin: 20px;">
                        <p>Nilai 1 poin untuk setiap respon yang cocok dengan jawaban "Ya".</p>
                        <p>Normal (5±4), Depresi Ringan (15±6), Depresi Berat (23±5)</p>
                    </div>

                    <div style="text-align: left; margin-top: 20px;">
                        <p><em>Kesimpulan Skala Depresi Lansia:</em></p>
                        <div class="d-flex">
                            <textarea name="kesimpulan" rows="4" cols="50" placeholder="" class="form-control"></textarea>
                        </div>
                    </div>

                    <!-- Tombol Submit -->
                    <div class="row mb-3">
                        <div class="col-sm-9 offset-sm-2 text-end">
                            <button type="submit" class="btn btn-primary">Lanjutkan</button>
                        </div>
                    </div>
            </div>

            </form>
        </div>
        </div>
        </div>


    </section>
    </section>
</main>
<?php include "footer_gerontik.php"; ?>