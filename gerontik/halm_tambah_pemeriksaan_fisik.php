<?php
$form_id       = 18;
$section_name  = 'pemeriksaan_fisik';
$section_label = 'Pemeriksaan Fisik';
include dirname(__DIR__) . '/partials/init_section.php';

$vital_fields = [
    'td',
    'nadi',
    'rr',
    'suhu',
    'tingkat_kesadaran',
];

$physical_sections = [
    [
        'title' => '1. Kepala',
        'rows' => [
            ['sakit_kepala' => 'Sakit Kepala', 'trauma_kepala_masa_lalu' => 'Trauma Kepala di Masa Lalu'],
            ['gatal_kulit_kepala' => 'Gatal pada Kulit Kepala', 'kulit_rambut_bersih' => 'Kulit / Rambut Bersih'],
            ['rambut_rontok' => 'Rambut Rontok'],
        ],
    ],
    [
        'title' => '2. Mata',
        'rows' => [
            ['penurunan_penglihatan' => 'Penurunan Penglihatan', 'penglihatan_kabur' => 'Penglihatan Kabur'],
            ['kekeruhan_lensa' => 'Kekeruhan Lensa', 'kacamata_lensa_kontak' => 'Kacamata / Lensa Kontak'],
            ['nyeri_mata' => 'Nyeri Mata', 'pruritus' => 'Pruritus (Gatal)'],
            ['bengkak_mata' => 'Bengkak Sekitar Mata', 'floater' => 'Floater'],
            ['diplopia' => 'Diplopia'],
        ],
    ],
    [
        'title' => '3. Telinga',
        'rows' => [
            ['penurunan_pendengaran' => 'Penurunan Pendengaran', 'alat_bantu_pendengaran' => 'Alat Bantu Pendengaran'],
        ],
    ],
    [
        'title' => '4. Hidung dan Sinus',
        'rows' => [
            ['rinorea' => 'Rinorea', 'rabas_hidung' => 'Rabas Hidung'],
            ['riwayat_epitaksis' => 'Riwayat Epitaksis', 'mendengkur_tidur' => 'Mendengkur Saat Tidur'],
            ['nyeri_sinus' => 'Nyeri Sinus', 'pernapasan_cuping_hidung' => 'Pernapasan Cuping Hidung'],
        ],
    ],
    [
        'title' => '5. Mulut dan Tenggorokan',
        'rows' => [
            ['sakit_tenggorokan' => 'Sakit Tenggorokan', 'lesi_ulkus' => 'Lesi / Ulkus'],
            ['suara_serak' => 'Suara Serak', 'perubahan_suara' => 'Perubahan Suara'],
            ['kesulitan_menelan' => 'Kesulitan Menelan', 'perdarahan_gusi' => 'Perdarahan Gusi'],
            ['karies' => 'Karies', 'gigi_bersih' => 'Gigi Bersih'],
            ['gigi_palsu' => 'Gigi Palsu', 'rutin_menggosok_gigi' => 'Rutin Menggosok Gigi'],
        ],
    ],
    [
        'title' => '6. Leher',
        'rows' => [
            ['kekakuan_leher' => 'Kekakuan Leher', 'nyeri_leher' => 'Nyeri Leher'],
            ['benjolan_leher' => 'Benjolan Leher', 'keterbatasan_gerak_leher' => 'Keterbatasan Gerak'],
        ],
    ],
    [
        'title' => '7. Pernapasan',
        'rows' => [
            ['batuk' => 'Batuk', 'sesak_napas' => 'Sesak Napas'],
            ['hemoptomisis' => 'Hemoptomisis', 'sputum' => 'Sputum'],
            ['riwayat_asma' => 'Riwayat Asma', 'kesulitan_menarik_napas' => 'Kesulitan Menarik Napas'],
        ],
    ],
    [
        'title' => '8. Kardiovaskuler',
        'rows' => [
            ['nyeri_dada' => 'Nyeri Dada', 'palpitasi' => 'Palpitasi'],
            ['dispneu_noctural' => 'Dispneu Noctural', 'ortopneu' => 'Ortopneu'],
            ['murmur' => 'Murmur', 'edema' => 'Edema'],
            ['parestesia' => 'Parestesia', 'riwayat_infark' => 'Riwayat Infark'],
        ],
    ],
    [
        'title' => '9. Gastrointestinal',
        'rows' => [
            ['disfagia' => 'Disfagia', 'tidak_dapat_mengunyah' => 'Tidak Dapat Mengunyah'],
            ['nyeri_uluhati' => 'Nyeri Uluhati', 'mual' => 'Mual'],
            ['muntah' => 'Muntah', 'hematemesis' => 'Hematemesis'],
            ['penurunan_selera_makan' => 'Penurunan Selera Makan', 'ikterik' => 'Ikterik'],
        ],
    ],
    [
        'title' => '10. Perkemihan',
        'rows' => [
            ['disuria' => 'Disuria', 'frekuensi' => 'Frekuensi'],
            ['menetes' => 'Menetes', 'hematuria' => 'Hematuria'],
            ['poliuria' => 'Poliuria', 'oliguria' => 'Oliguria'],
            ['riwayat_batu_perkemihan' => 'Riwayat Batu Perkemihan', 'nokturia' => 'Nokturia'],
            ['inkontinensia_uri' => 'Inkontinensia Uri', 'riwayat_pembesaran_prostat' => 'Riwayat Pembesaran Prostat'],
        ],
    ],
    [
        'title' => '11. Muskuloskeletal',
        'rows' => [
            ['nyeri_sendi' => 'Nyeri Sendi', 'kekakuan_sendi' => 'Kekakuan Sendi'],
            ['pembengkakan_sendi' => 'Pembengkakan Sendi', 'spasme' => 'Spasme'],
            ['kram_otot' => 'Kram Otot', 'deformitas' => 'Deformitas'],
            ['penurunan_kekuatan_otot' => 'Penurunan Kekuatan Otot', 'kelemahan' => 'Kelemahan'],
            ['nyeri_punggung_belakang' => 'Nyeri Punggung Belakang', 'nyeri_pinggang' => 'Nyeri Pinggang'],
            ['alat_bantuan_berjalan' => 'Alat Bantuan Berjalan', 'perubahan_cara_berjalan' => 'Perubahan Cara Berjalan'],
            ['tremor' => 'Tremor', 'atropi_otot' => 'Atropi Otot'],
        ],
    ],
    [
        'title' => '12. Endokrin',
        'rows' => [
            ['intoleransi_panas' => 'Intoleransi Panas', 'intoleransi_dingin' => 'Intoleransi Dingin'],
            ['goiter' => 'Goiter', 'poli_fagi' => 'Poli Fagi'],
            ['poli_uri' => 'Poli Uri', 'poli_dipsi' => 'Poli Dipsi'],
            ['perubahan_rambut' => 'Perubahan Rambut', 'pigmentasi_kulit' => 'Pigmentasi Kulit'],
        ],
    ],
    [
        'title' => '14. Integumen',
        'rows' => [
            ['kulit_kering' => 'Kulit Kering', 'kulit_keriput' => 'Kulit Keriput / Mengerut'],
            ['menjaga_kebersihan_kulit' => 'Menjaga Kebersihan Kulit', 'penurunan_lemak_bawah_kulit' => 'Penurunan Lemak Bawah Kulit'],
        ],
    ],
];

$yes_no_fields = [];
foreach ($physical_sections as $section) {
    foreach ($section['rows'] as $row) {
        foreach ($row as $field => $label) {
            $yes_no_fields[] = $field;
        }
    }
}

function render_yes_no_block($field, $label, $existing_data, $ro_disabled)
{
    $current = strtolower(trim((string)(val($field, $existing_data))));
    $yes_checked = in_array($current, ['ya', 'y'], true) ? 'checked' : '';
    $no_checked  = in_array($current, ['tidak', 't'], true) ? 'checked' : '';
?>
    <div class="col-sm-6">
        <div class="row align-items-center">
            <div class="col-sm-4 col-form-label"><strong><?= htmlspecialchars($label) ?></strong></div>
            <div class="col-sm-4">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="<?= htmlspecialchars($field) ?>" id="<?= htmlspecialchars($field) ?>_ya" value="Ya" <?= $ro_disabled ?> <?= $yes_checked ?>>
                    <label class="form-check-label" for="<?= htmlspecialchars($field) ?>_ya">Ya</label>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="<?= htmlspecialchars($field) ?>" id="<?= htmlspecialchars($field) ?>_tidak" value="Tidak" <?= $ro_disabled ?> <?= $no_checked ?>>
                    <label class="form-check-label" for="<?= htmlspecialchars($field) ?>_tidak">Tidak</label>
                </div>
            </div>
        </div>
    </div>
<?php
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $level === 'Mahasiswa') {
    if (isLocked($submission)) redirectWithMessage($_SERVER['REQUEST_URI'], 'error', 'Data tidak dapat diubah karena sedang dalam proses review.');

    $data = [];
    foreach ($vital_fields as $field) {
        $data[$field] = $_POST[$field] ?? '';
    }
    foreach ($yes_no_fields as $field) {
        $data[$field] = $_POST[$field] ?? '';
    }

    $submission_id = $submission ? $submission['id'] : createSubmission($user_id, $form_id, null, null, $mysqli);
    saveSection($submission_id, $section_name, $section_label, $data, $mysqli);
    updateSubmissionStatus($submission_id, $form_id, $mysqli);
    redirectWithMessage($_SERVER['REQUEST_URI'], 'success', 'Data berhasil disimpan.');
}
?>
<main id="main" class="main">
    <?php include "tab.php"; ?>
    <section class="section dashboard">
        <?php include dirname(__DIR__) . '/partials/notifikasi.php'; ?>
        <?php include dirname(__DIR__) . '/partials/status_section.php'; ?>
        <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><strong>3. Pemeriksaan Fisik</strong></h5>
                    <div class="row mb-2"><label class="col-sm-12 col-form-label text-primary"><strong>Tanda-tanda Vital</strong></label></div>
                    <div class="row mb-3"><label class="col-sm-2 col-form-label"><strong>TD (Tekanan Darah)</strong></label>
                        <div class="col-sm-9"><input type="text" class="form-control" name="td" placeholder="Masukkan TD" value="<?= htmlspecialchars(val('td', $existing_data)) ?>" <?= $ro ?>></div>
                    </div>
                    <div class="row mb-3"><label class="col-sm-2 col-form-label"><strong>N (Nadi)</strong></label>
                        <div class="col-sm-9"><input type="text" class="form-control" name="nadi" placeholder="Masukkan Nadi" value="<?= htmlspecialchars(val('nadi', $existing_data)) ?>" <?= $ro ?>></div>
                    </div>
                    <div class="row mb-3"><label class="col-sm-2 col-form-label"><strong>RR (Frekuensi Pernafasan)</strong></label>
                        <div class="col-sm-9"><input type="text" class="form-control" name="rr" placeholder="Masukkan RR" value="<?= htmlspecialchars(val('rr', $existing_data)) ?>" <?= $ro ?>></div>
                    </div>
                    <div class="row mb-3"><label class="col-sm-2 col-form-label"><strong>Suhu (Celsius)</strong></label>
                        <div class="col-sm-9"><input type="text" class="form-control" name="suhu" placeholder="Masukkan Suhu" value="<?= htmlspecialchars(val('suhu', $existing_data)) ?>" <?= $ro ?>></div>
                    </div>
                    <div class="row mb-3"><label class="col-sm-2 col-form-label"><strong>Tingkat Kesadaran</strong></label>
                        <div class="col-sm-9"><input type="text" class="form-control" name="tingkat_kesadaran" placeholder="Masukkan tingkat kesadaran" value="<?= htmlspecialchars(val('tingkat_kesadaran', $existing_data)) ?>" <?= $ro ?>></div>
                    </div>

                    <h5 class="card-title mt-4"><strong>Pengkajian Head to Toe</strong></h5>

                    <?php foreach ($physical_sections as $index => $section): ?>
                        <div class="row mb-2 <?= $index > 0 ? 'mt-4' : '' ?>">
                            <label class="col-sm-12 col-form-label text-primary">
                                <strong><?= htmlspecialchars($section['title']) ?></strong>
                            </label>
                        </div>
                        <?php foreach ($section['rows'] as $row): ?>
                            <div class="row mb-3 align-items-center">
                                <?php
                                $fields_in_row = array_keys($row);
                                render_yes_no_block($fields_in_row[0], $row[$fields_in_row[0]], $existing_data, $ro_disabled);
                                if (isset($fields_in_row[1])) {
                                    render_yes_no_block($fields_in_row[1], $row[$fields_in_row[1]], $existing_data, $ro_disabled);
                                } else {
                                    echo '<div class="col-sm-6"></div>';
                                }
                                ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endforeach; ?>

                    <?php if (!$is_dosen): ?><div class="row mb-3">
                            <div class="col-sm-12 d-flex justify-content-end"><button type="submit" class="btn btn-primary">Simpan Data</button></div>
                        </div><?php endif; ?>
                </div>
            </div>
        </form>
        <?php include dirname(__DIR__) . '/partials/footer_form.php'; ?>
        </div>
        </div>
    </section>
</main>