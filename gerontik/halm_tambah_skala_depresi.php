<?php
$form_id       = 18;
$section_name  = 'skala_depresi';
$section_label = 'Skala Depresi';
include dirname(__DIR__) . '/partials/init_section.php';

$answer_key = [
    'q1'  => 'Tidak',
    'q2'  => 'Ya',
    'q3'  => 'Ya',
    'q4'  => 'Ya',
    'q5'  => 'Tidak',
    'q6'  => 'Ya',
    'q7'  => 'Ya',
    'q8'  => 'Ya',
    'q9'  => 'Tidak',
    'q10' => 'Ya',
    'q11' => 'Ya',
    'q12' => 'Ya',
    'q13' => 'Ya',
    'q14' => 'Ya',
    'q15' => 'Tidak',
    'q16' => 'Ya',
    'q17' => 'Ya',
    'q18' => 'Ya',
    'q19' => 'Tidak',
    'q20' => 'Ya',
    'q21' => 'Tidak',
    'q22' => 'Ya',
    'q23' => 'Ya',
    'q24' => 'Ya',
    'q25' => 'Ya',
    'q26' => 'Ya',
    'q27' => 'Tidak',
    'q28' => 'Ya',
    'q29' => 'Tidak',
    'q30' => 'Tidak',
];

function depressionCategory(int $score): string
{
    if ($score <= 4) return 'Normal';
    if ($score <= 14) return 'Depresi Ringan';
    if ($score <= 22) return 'Depresi Sedang';
    return 'Depresi Berat';
}

function calculateDepressionConclusion(array $answers, array $answer_key): array
{
    $score = 0;
    $answered = 0;
    foreach ($answer_key as $field => $expected) {
        $actual = $answers[$field] ?? '';
        if ($actual !== '') {
            $answered++;
        }
        if ($actual !== '' && strcasecmp($actual, $expected) === 0) {
            $score++;
        }
    }

    return [$score, $answered > 0 ? ($score . ' - ' . depressionCategory($score)) : ''];
}

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
    27 => 'APakah Anda menikmati bangun pagi setiap hari?',
    28 => 'Apakah Anda lebih menghindar dari perkumpulan sosial ?',
    29 => 'Apakah mudah bagi Anda membuat keputusan ?',
    30 => 'Apakah pemikiran / benak Anda sejernih di masa-masa lalu?',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $level === 'Mahasiswa') {
    if (isLocked($submission)) redirectWithMessage($_SERVER['REQUEST_URI'], 'error', 'Data tidak dapat diubah karena sedang dalam proses review.');
    $data = [];
    foreach (range(1, 30) as $i) $data['q' . $i] = $_POST['q' . $i] ?? '';
    [$score, $conclusion] = calculateDepressionConclusion($data, $answer_key);
    $data['kesimpulan'] = $conclusion;
    $submission_id = $submission ? $submission['id'] : createSubmission($user_id, $form_id, null, null, $mysqli);
    saveSection($submission_id, $section_name, $section_label, $data, $mysqli);
    updateSubmissionStatus($submission_id, $form_id, $mysqli);
    redirectWithMessage($_SERVER['REQUEST_URI'], 'success', 'Data berhasil disimpan.');
}
?>
<main id="main" class="main">
<?php include "tab.php"; ?><section class="section dashboard">
<?php include dirname(__DIR__) . '/partials/notifikasi.php'; ?><?php include dirname(__DIR__) . '/partials/status_section.php'; ?>
<form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">
<div class="card"><div class="card-body">
<h5 class="card-title"><strong>X. Skala Depresi Geriatric</strong></h5>
<h6>Pengkajian ini menggunakan skala depresi geriatric bentuk singkat dari Yesavage (1983) yang instrumennya disusun secara khusus digunakan pada lanjut usia untuk memeriksa depresi. Jawaban pertanyaan sesuai dengan indikasi yang dinilai. Nilai 5 atau lebih dapat menandakan depresi.</h6>
<?php foreach ($questions as $num => $text): ?>
    <?php $key_label = $answer_key['q' . $num] ?? ''; ?>
    <div class="row mb-3">
        <label class="col-sm-10 col-form-label"><strong><?= $num . '. ' . htmlspecialchars($text) ?> <span class="text-muted"><?= $key_label !== '' ? '(' . htmlspecialchars($key_label) . ')' : '' ?></span></strong></label>
        <div class="col-sm-2">
            <select class="form-select" name="q<?= $num ?>" <?= $ro_select ?>>
                <option value="">-- Pilih --</option>
                <option value="Ya" <?= val('q'.$num, $existing_data) === 'Ya' ? 'selected' : '' ?>>Ya</option>
                <option value="Tidak" <?= val('q'.$num, $existing_data) === 'Tidak' ? 'selected' : '' ?>>Tidak</option>
            </select>
        </div>
    </div>
<?php endforeach; ?>
<div class="row mb-3"><div class="col-sm-12"><p>Nilai 1 poin untuk setiap respon yang cocok dengan jawaban yang menjadi kunci. Skor otomatis dihitung dari 30 pertanyaan.</p><p>Normal (0-4), Depresi Ringan (5-14), Depresi Sedang (15-22), Depresi Berat (23-30)</p></div></div>
<div class="row mb-3"><label class="col-sm-2 col-form-label"><strong>Kesimpulan</strong></label><div class="col-sm-9"><input type="text" class="form-control" name="kesimpulan" id="kesimpulan-depresi" value="<?= htmlspecialchars(val('kesimpulan', $existing_data)) ?>" readonly <?= $ro ?>></div></div>
<?php if (!$is_dosen): ?><div class="row mb-3"><div class="col-sm-12 d-flex justify-content-end"><button type="submit" class="btn btn-primary">Simpan Data</button></div></div><?php endif; ?>
</div></div></form>
<script>
(function () {
    const answerKey = <?= json_encode($answer_key, JSON_UNESCAPED_UNICODE) ?>;
    const conclusionField = document.getElementById('kesimpulan-depresi');

    function category(score) {
        if (score <= 4) return 'Normal';
        if (score <= 14) return 'Depresi Ringan';
        if (score <= 22) return 'Depresi Sedang';
        return 'Depresi Berat';
    }

    function updateConclusion() {
        let score = 0;
        let answered = 0;

        Object.keys(answerKey).forEach(function (field) {
            const el = document.querySelector(`[name="${field}"]`);
            if (!el) return;
            const value = el.value || '';
            if (value !== '') answered++;
            if (value && value.toLowerCase() === String(answerKey[field]).toLowerCase()) {
                score++;
            }
        });

        if (!conclusionField) return;
        if (answered === 0) {
            conclusionField.value = '';
            return;
        }

        conclusionField.value = `${score} - ${category(score)}`;
    }

    document.querySelectorAll('select[name^="q"]').forEach(function (el) {
        el.addEventListener('change', updateConclusion);
    });

    updateConclusion();
})();
</script>
<?php include dirname(__DIR__) . '/partials/footer_form.php'; ?></div></div></section></main>
