<?php
$form_id       = 18;
$section_name  = 'apgar_spmsq_risiko_jatuh';
$section_label = 'APGAR / SPMSQ / Risiko Jatuh';
include dirname(__DIR__) . '/partials/init_section.php';

$apgar_questions = [
    ['field' => 'A',  'label' => 'Saya puas bisa kembali pada teman saya untuk membantu saya bila suatu waktu ada kondisi yang menyusahkan saya.'],
    ['field' => 'P',  'label' => 'Saya puas dengan cara teman saya membicarakan sesuatu dan mengungkapkan masalah dengan saya.'],
    ['field' => 'G',  'label' => 'Saya puas bahwa teman saya menerima dan mendukung keinginan untuk melakukan aktivitas.'],
    ['field' => 'A2', 'label' => 'Saya puas dengan cara teman saya mengekspresikan afek dan berespon terhadap emosi saya seperti marah, sedih, atau mencintai.'],
    ['field' => 'R',  'label' => 'Saya puas dengan cara teman saya menyediakan waktu secara bersama-sama.'],
];

$spmsq_questions = [
    'Tanggal berapa hari ini? (Hari / Tanggal / Tahun)',
    'Hari apa sekarang?',
    'Apa nama tempat / kelurahan ini?',
    'Di mana alamat lengkap Anda?',
    'Berapa umur Anda?',
    'Kapan Anda lahir?',
    'Presiden Indonesia sekarang?',
    'Siapa nama presiden sebelumnya?',
    'Siapa nama kecil ibu Anda?',
    'Perhitungan 20 - 3, kemudian hasilnya dikurangi 3 terus sampai mendapat angka 0.',
];

$fall_groups = [
    [
        'no' => '1',
        'parameter' => 'Riwayat Jatuh',
        'rows' => [
            ['field' => 'riwayat_jatuh_1', 'skrining' => 'Apakah pasien datang ke rumah sakit karena jatuh?'],
            ['field' => 'riwayat_jatuh_2', 'skrining' => 'Jika tidak, apakah klien pernah jatuh dalam dua bulan terakhir?'],
        ],
        'keterangan' => 'Jika salah satu jawaban Ya = 6',
        'score_field' => 'skor_riwayat_jatuh',
    ],
    [
        'no' => '2',
        'parameter' => 'Status Mental',
        'rows' => [
            ['field' => 'status_mental_1', 'skrining' => 'Apakah pasien delirium (tidak dapat membuat keputusan, gangguan daya ingat)?'],
            ['field' => 'status_mental_2', 'skrining' => 'Apakah pasien disorientasi (salah menyebutkan waktu dan tempat)?'],
            ['field' => 'status_mental_3', 'skrining' => 'Apakah pasien mengalami agitasi (ketakutan, gelisah, dan cemas)?'],
        ],
        'keterangan' => 'Salah satu jawaban Ya = 14',
        'score_field' => 'skor_status_mental',
    ],
    [
        'no' => '3',
        'parameter' => 'Penglihatan',
        'rows' => [
            ['field' => 'penglihatan_1', 'skrining' => 'Apakah pasien menggunakan kacamata?'],
            ['field' => 'penglihatan_2', 'skrining' => 'Apakah pasien mengeluh penglihatan buram?'],
            ['field' => 'penglihatan_3', 'skrining' => 'Apakah pasien mempunyai glaukoma / katarak / degenerasi makula?'],
        ],
        'keterangan' => 'Salah satu jawaban Ya = 1',
        'score_field' => 'skor_penglihatan',
    ],
    [
        'no' => '4',
        'parameter' => 'Kebiasaan Berkemih',
        'rows' => [
            ['field' => 'berkemih', 'skrining' => 'Apakah terdapat perubahan perilaku berkemih (urgensi, frekuensi, inkontinensia, nokturia)?'],
        ],
        'keterangan' => 'Ya = 2',
        'score_field' => 'skor_berkemih',
    ],
    [
        'no' => '5 - 6',
        'parameter' => 'Transfer dan Mobilitas',
        'rows' => [
            [
                'field' => 'transfer',
                'skrining' => '5. Transfer (Berpindah Tempat): Mandiri (boleh memakai alat bantu); memerlukan sedikit bantuan orang dewasa (1 orang); bantuan yang nyata 2 orang; tidak dapat duduk seimbang, perlu bantuan total.',
                'options' => [
                    '0' => 'Mandiri (boleh memakai alat bantu)',
                    '1' => 'Memerlukan sedikit bantuan orang dewasa (1 orang)',
                    '2' => 'Bantuan yang nyata 2 orang',
                    '3' => 'Tidak dapat duduk seimbang, perlu bantuan total',
                ],
            ],
            [
                'field' => 'mobilitas',
                'skrining' => '6. Mobilitas: Mandiri (boleh menggunakan alat); berjalan dengan bantuan 1 orang (fisik / verbal); menggunakan kursi roda; imobilisasi.',
                'options' => [
                    '0' => 'Mandiri (boleh menggunakan alat)',
                    '1' => 'Berjalan dengan bantuan 1 orang (fisik / verbal)',
                    '2' => 'Menggunakan kursi roda',
                    '3' => 'Imobilisasi',
                ],
            ],
        ],
        'keterangan' => 'Jika nilai gabungan 0-3 maka skornya 0. Jika nilai 4-6 maka skornya 7.',
        'score_field' => 'skor_transfer_mobilitas',
    ],
];

function radioId($field, $value)
{
    return preg_replace('/[^a-zA-Z0-9_]+/', '_', $field . '_' . $value);
}

function renderCenteredRadio($field, $value, array $existing_data, $ro_disabled)
{
    $checked = (string)val($field, $existing_data) === (string)$value ? 'checked' : '';
    $id = radioId($field, $value);
    return '<div class="d-flex justify-content-center"><input class="form-check-input m-0" type="radio" name="' . htmlspecialchars($field) . '" id="' . htmlspecialchars($id) . '" value="' . htmlspecialchars($value) . '" ' . $ro_disabled . ' ' . $checked . '></div>';
}

function renderOptionGroup($field, array $options, array $existing_data, $ro_disabled, $inline = true)
{
    $current = (string)val($field, $existing_data);
    $html = '';

    foreach ($options as $value => $label) {
        $id = radioId($field, $value);
        $wrapper = $inline ? 'form-check form-check-inline me-3' : 'form-check mb-1';
        $checked = $current === (string)$value ? 'checked' : '';
        $html .= '<div class="' . $wrapper . '">';
        $html .= '<input class="form-check-input" type="radio" name="' . htmlspecialchars($field) . '" id="' . htmlspecialchars($id) . '" value="' . htmlspecialchars($value) . '" ' . $ro_disabled . ' ' . $checked . '>';
        $html .= '<label class="form-check-label" for="' . htmlspecialchars($id) . '">' . htmlspecialchars($label) . '</label>';
        $html .= '</div>';
    }

    return $html;
}

function renderFallGroup(array $group, array $existing_data, array $fall_scores, $ro_disabled, $ro)
{
    $rows = $group['rows'];
    $rowspan = count($rows);
    $scoreValue = $fall_scores[$group['score_field']] ?? '';
    $html = '';

    foreach ($rows as $index => $row) {
        $html .= '<tr>';

        if ($index === 0) {
            $html .= '<td rowspan="' . $rowspan . '" class="text-center">' . htmlspecialchars($group['no']) . '</td>';
            $html .= '<td rowspan="' . $rowspan . '"><strong>' . htmlspecialchars($group['parameter']) . '</strong></td>';
        }

        $html .= '<td>' . htmlspecialchars($row['skrining']) . '</td>';
        $html .= '<td>';
        if (isset($row['options'])) {
            $html .= renderOptionGroup($row['field'], $row['options'], $existing_data, $ro_disabled, false);
        } else {
            $html .= renderOptionGroup($row['field'], ['Y' => 'Ya', 'T' => 'Tidak'], $existing_data, $ro_disabled, true);
        }
        $html .= '</td>';

        if ($index === 0) {
            $html .= '<td rowspan="' . $rowspan . '">' . htmlspecialchars($group['keterangan']) . '</td>';
            $html .= '<td rowspan="' . $rowspan . '" class="text-center"><input type="text" class="form-control form-control-sm" id="' . htmlspecialchars($group['score_field']) . '" name="' . htmlspecialchars($group['score_field']) . '" value="' . htmlspecialchars($scoreValue) . '" readonly ' . $ro . '></td>';
        }

        $html .= '</tr>';
    }

    return $html;
}

function apgarCategory($score)
{
    if ($score <= 2) return 'Disfungsi Gerontik Sangat Tinggi';
    if ($score <= 6) return 'Disfungsi Gerontik Sedang';
    if ($score <= 8) return 'Disfungsi Gerontik Ringan';
    return 'Normal';
}

function evaluateApgar(array $data)
{
    $fields = ['A', 'P', 'G', 'A2', 'R'];
    $score = 0;
    $answered = 0;

    foreach ($fields as $field) {
        $value = (string)($data[$field] ?? '');
        if ($value !== '') {
            $answered++;
            $score += (int)$value;
        }
    }

    if ($answered === 0) {
        return ['', ''];
    }

    return [$score, $score . ' - ' . apgarCategory($score)];
}

function spmsqCategory($errors)
{
    if ($errors <= 2) return 'fungsi intelektual utuh';
    if ($errors <= 4) return 'gangguan intelektual ringan';
    if ($errors <= 7) return 'gangguan intelektual sedang';
    return 'gangguan intelektual berat';
}

function evaluateSpmsq(array $data)
{
    $errors = 0;
    $correct = 0;
    $answered = 0;

    foreach (range(1, 10) as $i) {
        $value = (string)($data['q' . $i] ?? '');
        if ($value !== '') {
            $answered++;
            if ($value === 'B') {
                $correct++;
            } elseif ($value === 'S') {
                $errors++;
            }
        }
    }

    if ($answered === 0) {
        return ['', '', ''];
    }

    return [$correct, $errors, $errors . ' - ' . spmsqCategory($errors)];
}

function fallCategory($score)
{
    if ($score <= 5) return 'Risiko Rendah';
    if ($score <= 16) return 'Risiko Sedang';
    return 'Risiko Tinggi';
}

function evaluateFall(array $data)
{
    $riwayatJatuh = (in_array((string)($data['riwayat_jatuh_1'] ?? ''), ['Y', 'y'], true) || in_array((string)($data['riwayat_jatuh_2'] ?? ''), ['Y', 'y'], true)) ? 6 : 0;
    $statusMental = (in_array((string)($data['status_mental_1'] ?? ''), ['Y', 'y'], true) || in_array((string)($data['status_mental_2'] ?? ''), ['Y', 'y'], true) || in_array((string)($data['status_mental_3'] ?? ''), ['Y', 'y'], true)) ? 14 : 0;
    $penglihatan = (in_array((string)($data['penglihatan_1'] ?? ''), ['Y', 'y'], true) || in_array((string)($data['penglihatan_2'] ?? ''), ['Y', 'y'], true) || in_array((string)($data['penglihatan_3'] ?? ''), ['Y', 'y'], true)) ? 1 : 0;
    $berkemih = in_array((string)($data['berkemih'] ?? ''), ['Y', 'y'], true) ? 2 : 0;

    $transferRaw  = (int)($data['transfer'] ?? 0);
    $mobilitasRaw = (int)($data['mobilitas'] ?? 0);
    $transferMobilitasScore = (($transferRaw + $mobilitasRaw) <= 3) ? 0 : 7;

    $anyAnswered = false;
    foreach (['riwayat_jatuh_1', 'riwayat_jatuh_2', 'status_mental_1', 'status_mental_2', 'status_mental_3', 'penglihatan_1', 'penglihatan_2', 'penglihatan_3', 'berkemih', 'transfer', 'mobilitas'] as $field) {
        if (($data[$field] ?? '') !== '') {
            $anyAnswered = true;
            break;
        }
    }

    $total = $riwayatJatuh + $statusMental + $penglihatan + $berkemih + $transferMobilitasScore;
    if (!$anyAnswered) {
        return ['', '', [
            'riwayat_jatuh' => '',
            'status_mental' => '',
            'penglihatan' => '',
            'berkemih' => '',
            'transfer' => '',
            'mobilitas' => '',
            'riwayat_jatuh_summary' => '',
            'status_mental_summary' => '',
            'penglihatan_summary' => '',
            'berkemih_summary' => '',
            'skor_riwayat_jatuh' => '',
            'skor_status_mental' => '',
            'skor_penglihatan' => '',
            'skor_berkemih' => '',
            'skor_transfer_mobilitas' => '',
        ]];
    }

    return [$total, $total . ' - ' . fallCategory($total), [
        'riwayat_jatuh' => $riwayatJatuh > 0 ? 'Y' : 'T',
        'status_mental' => $statusMental > 0 ? 'Y' : 'T',
        'penglihatan' => $penglihatan > 0 ? 'Y' : 'T',
        'berkemih' => $berkemih > 0 ? 'Y' : 'T',
        'transfer' => (string)$transferRaw,
        'mobilitas' => (string)$mobilitasRaw,
        'riwayat_jatuh_summary' => $riwayatJatuh > 0 ? 'Y' : 'T',
        'status_mental_summary' => $statusMental > 0 ? 'Y' : 'T',
        'penglihatan_summary' => $penglihatan > 0 ? 'Y' : 'T',
        'berkemih_summary' => $berkemih > 0 ? 'Y' : 'T',
        'skor_riwayat_jatuh' => $riwayatJatuh,
        'skor_status_mental' => $statusMental,
        'skor_penglihatan' => $penglihatan,
        'skor_berkemih' => $berkemih,
        'skor_transfer_mobilitas' => $transferMobilitasScore,
    ]];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $level === 'Mahasiswa') {
    if (isLocked($submission)) redirectWithMessage($_SERVER['REQUEST_URI'], 'error', 'Data tidak dapat diubah karena sedang dalam proses review.');

    $data = [];

    foreach (['A', 'P', 'G', 'A2', 'R'] as $field) {
        $data[$field] = $_POST[$field] ?? '';
    }
    foreach (range(1, 10) as $i) {
        $data['q' . $i] = $_POST['q' . $i] ?? '';
        $data['jawaban_spmsq_' . $i] = $_POST['jawaban_spmsq_' . $i] ?? '';
    }
    foreach (['riwayat_jatuh_1', 'riwayat_jatuh_2', 'status_mental_1', 'status_mental_2', 'status_mental_3', 'penglihatan_1', 'penglihatan_2', 'penglihatan_3', 'berkemih', 'transfer', 'mobilitas'] as $field) {
        $data[$field] = $_POST[$field] ?? '';
    }

    [$apgar_score, $apgar_conclusion] = evaluateApgar($data);
    [$spmsq_correct, $spmsq_errors, $spmsq_conclusion] = evaluateSpmsq($data);
    [$fall_total, $fall_conclusion, $fall_scores] = evaluateFall($data);

    $data['skor_apgar'] = $apgar_score;
    $data['kesimpulan_apgar'] = $apgar_conclusion;
    $data['jumlah_benar_spmsq'] = $spmsq_correct;
    $data['jumlah_salah_spmsq'] = $spmsq_errors;
    $data['kesimpulan_spmsq'] = $spmsq_conclusion;
    $data['skor_risiko_jatuh'] = $fall_total;
    $data['kesimpulan_penilaian'] = $fall_conclusion;
    $data = array_merge($data, $fall_scores);

    $submission_id = $submission ? $submission['id'] : createSubmission($user_id, $form_id, null, null, $mysqli);
    saveSection($submission_id, $section_name, $section_label, $data, $mysqli);
    updateSubmissionStatus($submission_id, $form_id, $mysqli);
    redirectWithMessage($_SERVER['REQUEST_URI'], 'success', 'Data berhasil disimpan.');
}

[$apgar_score, $apgar_conclusion] = evaluateApgar($existing_data);
[$spmsq_correct, $spmsq_errors, $spmsq_conclusion] = evaluateSpmsq($existing_data);
[$fall_total, $fall_conclusion, $fall_scores] = evaluateFall($existing_data);
?>

<style>
    .risiko-jatuh-table {
        border-width: 2px !important;
        border-color: #000 !important;
    }

    .risiko-jatuh-table th,
    .risiko-jatuh-table td {
        border-width: 2px !important;
        border-color: #000 !important;
    }
</style>

<main id="main" class="main">
    <?php include "tab.php"; ?>
    <section class="section dashboard">
        <?php include dirname(__DIR__) . '/partials/notifikasi.php'; ?>
        <?php include dirname(__DIR__) . '/partials/status_section.php'; ?>

        <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><strong>8. APGAR / SPMSQ / Risiko Jatuh</strong></h5>

                    <h5 class="card-title mt-3"><strong>XI. APGAR Gerontik</strong></h5>
                    <p>APGAR Gerontik ditujukan untuk mengkaji fungsi sosial lansia. A (adaptation / adaptasi), P (partnership / hubungan), G (growth / pertumbuhan), A (affection / kedekatan), dan R (resolve / pemecahan).</p>

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 60px;">No</th>
                                    <th>Pertanyaan</th>
                                    <th class="text-center" style="width: 130px;">Selalu (2)</th>
                                    <th class="text-center" style="width: 130px;">Kadang (1)</th>
                                    <th class="text-center" style="width: 150px;">Tidak pernah (0)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($apgar_questions as $i => $item): ?>
                                    <tr>
                                        <td class="text-center"><?= $i + 1 ?></td>
                                        <td><?= htmlspecialchars($item['label']) ?></td>
                                        <td class="text-center"><?= renderCenteredRadio($item['field'], '2', $existing_data, $ro_disabled) ?></td>
                                        <td class="text-center"><?= renderCenteredRadio($item['field'], '1', $existing_data, $ro_disabled) ?></td>
                                        <td class="text-center"><?= renderCenteredRadio($item['field'], '0', $existing_data, $ro_disabled) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-2 col-form-label"><strong>Skor APGAR</strong></label>
                        <div class="col-sm-2">
                            <input type="text" class="form-control" name="skor_apgar" id="skor-apgar" value="<?= htmlspecialchars($apgar_score) ?>" readonly <?= $ro ?>>
                        </div>
                        <label class="col-sm-2 col-form-label"><strong>Kesimpulan</strong></label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" name="kesimpulan_apgar" id="kesimpulan-apgar" value="<?= htmlspecialchars($apgar_conclusion) ?>" readonly <?= $ro ?>>
                        </div>
                    </div>
                    <p class="mb-4">Nilai 2 untuk jawaban yang sesuai dengan indikasi, nilai 1 untuk kadang, dan nilai 0 untuk tidak pernah. Skor 0-2 = disfungsi gerontik sangat tinggi, 3-6 = disfungsi gerontik sedang, 7-8 = disfungsi gerontik ringan, 9-10 = normal.</p>

                    <h5 class="card-title mt-4"><strong>XII. SPMSQ</strong></h5>
                    <p>Pemeriksaan Short Portable Status Questionnaire (SPMSQ) digunakan untuk mendeteksi adanya tingkat gangguan intelektual / memori. Catatlah jumlah kesalahan dari semua pertanyaan, berikan tanda pada kolom B (jawaban lansia benar) atau S (jawaban lansia salah).</p>

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 60px;">No</th>
                                    <th>Pertanyaan</th>
                                    <th class="text-center" style="width: 220px;">Jawaban</th>
                                    <th class="text-center" style="width: 100px;">B</th>
                                    <th class="text-center" style="width: 100px;">S</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($spmsq_questions as $i => $question): ?>
                                    <tr>
                                        <td class="text-center"><?= $i + 1 ?></td>
                                        <td><?= htmlspecialchars($question) ?></td>
                                        <td>
                                            <input type="text" class="form-control" name="jawaban_spmsq_<?= $i + 1 ?>" value="<?= val('jawaban_spmsq_' . ($i + 1), $existing_data) ?>" <?= $ro ?> >
                                        </td>
                                        <td class="text-center"><?= renderCenteredRadio('q' . ($i + 1), 'B', $existing_data, $ro_disabled) ?></td>
                                        <td class="text-center"><?= renderCenteredRadio('q' . ($i + 1), 'S', $existing_data, $ro_disabled) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-2 col-form-label"><strong>Jumlah Benar</strong></label>
                        <div class="col-sm-2">
                            <input type="text" class="form-control" name="jumlah_benar_spmsq" id="jumlah-benar-spmsq" value="<?= htmlspecialchars($spmsq_correct) ?>" readonly <?= $ro ?>>
                        </div>
                        <label class="col-sm-2 col-form-label"><strong>Jumlah Salah</strong></label>
                        <div class="col-sm-2">
                            <input type="text" class="form-control" name="jumlah_salah_spmsq" id="jumlah-salah-spmsq" value="<?= htmlspecialchars($spmsq_errors) ?>" readonly <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-2 col-form-label"><strong>Kesimpulan SPMSQ</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="kesimpulan_spmsq" id="kesimpulan-spmsq" value="<?= htmlspecialchars($spmsq_conclusion) ?>" readonly <?= $ro ?>>
                        </div>
                    </div>

                    <p class="mb-4">Kriteria penilaian: kesalahan 0-2 = fungsi intelektual utuh, 3-4 = gangguan intelektual ringan, 5-7 = gangguan intelektual sedang, 8-10 = gangguan intelektual berat.</p>

                    <h5 class="card-title mt-4"><strong>XIII. Skala Jatuh Pada Lansia - Ontario Modified Stratify-Sydney Scoring</strong></h5>
                    <p>Skala jatuh menilai faktor risiko berdasarkan riwayat jatuh, status mental, penglihatan, kebiasaan berkemih, serta gabungan transfer dan mobilitas. Skor total 0-5 = risiko rendah, 6-16 = risiko sedang, 17-30 = risiko tinggi.</p>

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle risiko-jatuh-table">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 60px;">No</th>
                                    <th class="text-center" style="width: 180px;">Parameter</th>
                                    <th>Skrining</th>
                                    <th class="text-center" style="width: 260px;">Jawaban</th>
                                    <th class="text-center" style="width: 180px;">Keterangan Nilai</th>
                                    <th class="text-center" style="width: 120px;">Skor</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?= renderFallGroup($fall_groups[0], $existing_data, $fall_scores, $ro_disabled, $ro) ?>
                                <?= renderFallGroup($fall_groups[1], $existing_data, $fall_scores, $ro_disabled, $ro) ?>
                                <?= renderFallGroup($fall_groups[2], $existing_data, $fall_scores, $ro_disabled, $ro) ?>
                                <?= renderFallGroup($fall_groups[3], $existing_data, $fall_scores, $ro_disabled, $ro) ?>
                                <?= renderFallGroup($fall_groups[4], $existing_data, $fall_scores, $ro_disabled, $ro) ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-2 col-form-label"><strong>Skor Risiko Jatuh</strong></label>
                        <div class="col-sm-2">
                            <input type="text" class="form-control" name="skor_risiko_jatuh" id="skor-risiko-jatuh" value="<?= htmlspecialchars($fall_total) ?>" readonly <?= $ro ?>>
                        </div>
                        <label class="col-sm-2 col-form-label"><strong>Kesimpulan Penilaian</strong></label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" name="kesimpulan_penilaian" id="kesimpulan-penilaian" value="<?= htmlspecialchars($fall_conclusion) ?>" readonly <?= $ro ?>>
                        </div>
                    </div>

                    <?php if (!$is_dosen): ?>
                        <div class="row mb-3">
                            <div class="col-sm-12 d-flex justify-content-end"><button type="submit" class="btn btn-primary">Simpan Data</button></div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </form>

        <?php include dirname(__DIR__) . '/partials/footer_form.php'; ?>
    </section>
</main>

<script>
    (function() {
        function getCheckedValue(name) {
            const node = document.querySelector(`input[name="${name}"]:checked`);
            return node ? node.value : '';
        }

        function setValue(id, value) {
            const el = document.getElementById(id);
            if (el) el.value = value;
        }

        function updateApgar() {
            const fields = ['A', 'P', 'G', 'A2', 'R'];
            let score = 0;
            let answered = 0;

            fields.forEach(function(field) {
                const value = getCheckedValue(field);
                if (value !== '') {
                    answered++;
                    score += parseInt(value, 10) || 0;
                }
            });

            if (!answered) {
                setValue('skor-apgar', '');
                setValue('kesimpulan-apgar', '');
                return;
            }

            const label = score <= 2 ? 'Disfungsi Gerontik Sangat Tinggi' : (score <= 6 ? 'Disfungsi Gerontik Sedang' : (score <= 8 ? 'Disfungsi Gerontik Ringan' : 'Normal'));
            setValue('skor-apgar', score);
            setValue('kesimpulan-apgar', `${score} - ${label}`);
        }

        function updateSpmsq() {
            let benar = 0;
            let salah = 0;
            let answered = 0;

            for (let i = 1; i <= 10; i++) {
                const value = getCheckedValue(`q${i}`);
                if (value !== '') {
                    answered++;
                    if (value === 'B') benar++;
                    if (value === 'S') salah++;
                }
            }

            if (!answered) {
                setValue('jumlah-benar-spmsq', '');
                setValue('jumlah-salah-spmsq', '');
                setValue('kesimpulan-spmsq', '');
                return;
            }

            const label = salah <= 2 ? 'fungsi intelektual utuh' : (salah <= 4 ? 'gangguan intelektual ringan' : (salah <= 7 ? 'gangguan intelektual sedang' : 'gangguan intelektual berat'));
            setValue('jumlah-benar-spmsq', benar);
            setValue('jumlah-salah-spmsq', salah);
            setValue('kesimpulan-spmsq', `${salah} - ${label}`);
        }

        function updateFall() {
            const anyAnswered = [
                'riwayat_jatuh_1', 'riwayat_jatuh_2', 'status_mental_1', 'status_mental_2', 'status_mental_3',
                'penglihatan_1', 'penglihatan_2', 'penglihatan_3', 'berkemih', 'transfer', 'mobilitas'
            ].some(function(field) {
                return getCheckedValue(field) !== '';
            });

            if (!anyAnswered) {
                ['skor_riwayat_jatuh', 'skor_status_mental', 'skor_penglihatan', 'skor_berkemih', 'skor_transfer_mobilitas', 'skor-risiko-jatuh', 'kesimpulan-penilaian'].forEach(function(id) {
                    setValue(id, '');
                });
                return;
            }

            const riwayat = (getCheckedValue('riwayat_jatuh_1') === 'Y' || getCheckedValue('riwayat_jatuh_2') === 'Y') ? 6 : 0;
            const status = (getCheckedValue('status_mental_1') === 'Y' || getCheckedValue('status_mental_2') === 'Y' || getCheckedValue('status_mental_3') === 'Y') ? 14 : 0;
            const penglihatan = (getCheckedValue('penglihatan_1') === 'Y' || getCheckedValue('penglihatan_2') === 'Y' || getCheckedValue('penglihatan_3') === 'Y') ? 1 : 0;
            const berkemih = getCheckedValue('berkemih') === 'Y' ? 2 : 0;
            const transferRaw = parseInt(getCheckedValue('transfer') || '0', 10) || 0;
            const mobilitasRaw = parseInt(getCheckedValue('mobilitas') || '0', 10) || 0;
            const transferMobilitas = (transferRaw + mobilitasRaw) <= 3 ? 0 : 7;
            const total = riwayat + status + penglihatan + berkemih + transferMobilitas;
            const label = total <= 5 ? 'Risiko Rendah' : (total <= 16 ? 'Risiko Sedang' : 'Risiko Tinggi');

            setValue('skor_riwayat_jatuh', riwayat);
            setValue('skor_status_mental', status);
            setValue('skor_penglihatan', penglihatan);
            setValue('skor_berkemih', berkemih);
            setValue('skor_transfer_mobilitas', transferMobilitas);
            setValue('skor-risiko-jatuh', total);
            setValue('kesimpulan-penilaian', `${total} - ${label}`);
        }

        function updateAll() {
            updateApgar();
            updateSpmsq();
            updateFall();
        }

        document.querySelectorAll('input[type="radio"]').forEach(function(input) {
            input.addEventListener('change', updateAll);
        });

        updateAll();
    })();
</script>
