<?php
$form_id       = 20;
$section_name  = 'data_biologis_2';
$section_label = 'Data Biologis 2';
include dirname(__DIR__, 2) . '/partials/init_section.php';

// Load existing dynamic rows
$existing_lab = $existing_data['lab'] ?? [];
$existing_obat     = $existing_data['obat']     ?? [];
$existing_ekg = $existing_data['ekg'] ?? '';

// Decode checkbox fields
$checkbox_fields = ['bunyi_tambahan', 'keadaan_abdomen'];
foreach ($checkbox_fields as $cf) {
    $existing_data[$cf] = isset($existing_data[$cf])
        ? (is_array($existing_data[$cf])
            ? $existing_data[$cf]
            : (json_decode($existing_data[$cf], true) ?? []))
        : [];
}

// =============================================
// HANDLE POST - MAHASISWA
// =============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $level === 'Mahasiswa') {
    if (isLocked($submission)) {
        redirectWithMessage($_SERVER['REQUEST_URI'], 'error', 'Data tidak dapat diubah karena sedang dalam proses review.');
    }

    // Upload EKG
    $path_ekg = $existing_data['ekg'] ?? '';
    if (!empty($_FILES['ekg']['name'])) {
        $upload = uploadImage($_FILES['ekg'], 'uploads/ekg/', 2);
        if ($upload['success']) {
            if (!empty($path_ekg) && file_exists($path_ekg)) {
                unlink($path_ekg);
            }
            $path_ekg = $upload['path'];
        } else {
            redirectWithMessage($_SERVER['REQUEST_URI'], 'error', $upload['error']);
            exit;
        }
    }
    $obat = [];
    if (!empty($_POST['obat'])) {
        foreach ($_POST['obat'] as $index => $row) {
            if (empty($row['jenis_obat']) && empty($row['dosis']) && empty($row['kegunaan'])) {
                continue;
            }
            $obat[] = [
                'jenis_obat'     => $row['jenis_obat']     ?? '',
                'dosis'          => $row['dosis']           ?? '',
                'kegunaan'       => $row['kegunaan']        ?? '',
                'cara_pemberian' => $row['cara_pemberian']  ?? '',
            ];
        }
    }
    // Proses dynamic rows lab
    $lab = [];
    if (!empty($_POST['lab'])) {
        foreach ($_POST['lab'] as $row) {
            if (empty($row['pemeriksaan']) && empty($row['hasil']) && empty($row['nilai_normal'])) continue;
            $lab[] = [
                'pemeriksaan'  => $row['pemeriksaan']  ?? '',
                'hasil'        => $row['hasil']        ?? '',
                'nilai_normal' => $row['nilai_normal'] ?? '',
            ];
        }
    }

    $text_fields = [
        'bentuk_dada',
        'pengembangan_dada',
        'perbandingan_dada',
        'suara_nafas_uraian',
        'abnormal',
        'frekuensi_nafas',
        'perkusi_paru',
        'taktil_fremitus',
        'kelainan_paru',
        's1_jantung',
        'terdapat_pada_s1',
        's2_jantung',
        'terdapat_pada_s2',
        'pulsasi_jantung',
        'kelainan_jantung',
        'bising_usus_kali',
        'benjolan_letak',
        'nyeri_letak',
        'kelainan_abdomen',
        'kelainan_genetalia',
        'otot_tangan_kanan',
        'otot_tangan_kiri',
        'kelainan_ekstremitas_atas',
        'kelainan_ekstremitas_bawah',
        'otot_kaki_kanan',
        'otot_kaki_kiri',
        'warna_kulit',
        'pada_daerah',
        'pada_daerah_luka',
        'karakteristik_luka',
        'kelainan_kulit',
        'kelainan_kuku',
        'nervus1_penciuman',
        'nervus2_penglihatan',
        'konstriksi_pupil',
        'gerakan_kelopak',
        'gerakan_bola_mata',
        'gerakan_mata_bawah',
        'refleks_dagu',
        'refleks_cornea',
        'pengecapan_depan',
        'fungsi_pendengaran',
        'refleks_menelan',
        'refleks_muntah',
        'pengecapan_belakang',
        'suara_pasien',
        'gerakan_kepala',
        'angkat_bahu',
        'deviasi_lidah',
        'kaku_kuduk',
        'kernig_sign',
        'refleks_brudzinski',
        'radiologi',
        'usg',
        'ct',
        'terapi',
    ];

    $radio_fields = [
        'otot_pernafasan',
        'bunyi_abnormal',
        'teratur_nafas',
        'irama_nafas',
        'sesak_nafas',
        'bunyi_jantung',
        'bunyi_tambahan_jantung',
        'irama_jantung',
        'bentuk_abdomen',
        'bising_usus',
        'benjolan_abdomen',
        'nyeri_abdomen',
        'perkusi_abdomen',
        'bentuk_genetalia',
        'radang_genetalia',
        'sekret_genetalia',
        'skrotum_bengkak',
        'rektum_benjolan',
        'terdapat_lesi',
        'atas_simetris',
        'sensasi_halus',
        'sensasi_tajam',
        'sensasi_panas',
        'sensasi_dingin',
        'rom_atas',
        'refleks_bisep',
        'refleks_trisep',
        'pembengkakan_atas',
        'kelembaban_atas',
        'temperatur_atas',
        'bawah_simetris',
        'bawah_sensasi_halus',
        'bawah_sensasi_tajam',
        'bawah_sensasi_panas',
        'bawah_sensasi_dingin',
        'rom_bawah',
        'refleks_babinski',
        'varises_bawah',
        'pembengkakan_bawah',
        'kelembaban_bawah',
        'temperatur_bawah',
        'turgor_kulit',
        'kelembaban_kulit',
        'edema_kulit',
        'luka_kulit',
        'tekstur_kulit',
        'clubbing_finger',
        'crt',
    ];

    $data = [
        'obat' => $obat,
        'lab' => $lab,
        'ekg' => $path_ekg
    ];

    foreach ($text_fields as $f) {
        $data[$f] = $_POST[$f] ?? '';
    }
    foreach ($radio_fields as $f) {
        $data[$f] = $_POST[$f] ?? '';
    }
    foreach ($checkbox_fields as $cf) {
        $data[$cf] = json_encode(isset($_POST[$cf]) ? (array)$_POST[$cf] : []);
    }

    if (!$submission) {
        $submission_id = createSubmission($user_id, $form_id, null, null, $mysqli);
    } else {
        $submission_id = $submission['id'];
    }

    saveSection($submission_id, $section_name, $section_label, $data, $mysqli);
    updateSubmissionStatus($submission_id, $form_id, $mysqli);
    redirectWithMessage($_SERVER['REQUEST_URI'], 'success', 'Data berhasil disimpan.');
}
?>

<main id="main" class="main">
    <?php include "keperawatan/dasar/tab.php"; ?>

    <section class="section dashboard">

        <?php include dirname(__DIR__, 2) . '/partials/notifikasi.php'; ?>
        <?php include dirname(__DIR__, 2) . '/partials/status_section.php'; ?>

        <div class="card">
            <div class="card-body">
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

                    <h5 class="card-title"><strong>5. Data Biologis 2</strong></h5>

                    <!-- 8. Thorax -->
                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary"><strong>8. Thorax</strong></label>
                    </div>
                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary"><strong>a. Dada</strong></label>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-2 col-form-label"><strong>Bentuk Dada</strong></div>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="bentuk_dada"
                                value="<?= htmlspecialchars($existing_data['bentuk_dada'] ?? '') ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-2 col-form-label"><strong>Pengembangan Dada</strong></div>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="pengembangan_dada"
                                value="<?= htmlspecialchars($existing_data['pengembangan_dada'] ?? '') ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-2 col-form-label">
                            <strong>Perbandingan ukuran anterior-posterior dengan transversal</strong>
                        </div>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="perbandingan_dada"
                                value="<?= htmlspecialchars($existing_data['perbandingan_dada'] ?? '') ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Penggunaan Otot Pernafasan Tambahan</strong></div>
                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="otot_pernafasan" value="ya"
                                    id="otot_pernafasan_ya" <?= $ro_disabled ?>
                                    <?= ($existing_data['otot_pernafasan'] ?? '') === 'ya' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="otot_pernafasan_ya">Ya</label>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="otot_pernafasan" value="tidak"
                                    id="otot_pernafasan_tidak" <?= $ro_disabled ?>
                                    <?= ($existing_data['otot_pernafasan'] ?? '') === 'tidak' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="otot_pernafasan_tidak">Tidak</label>
                            </div>
                        </div>
                    </div>

                    <!-- b. Paru -->
                    <div class="row mb-2 mt-2">
                        <label class="col-sm-12 text-primary"><strong>b. Paru</strong></label>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-2 col-form-label"><strong>Suara Nafas</strong></div>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="suara_nafas_uraian"
                                value="<?= htmlspecialchars($existing_data['suara_nafas_uraian'] ?? '') ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Bunyi Nafas Tambahan</strong></div>
                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="bunyi_abnormal" value="wheezing"
                                    id="bunyi_abnormal_wheezing" <?= $ro_disabled ?>
                                    <?= ($existing_data['bunyi_abnormal'] ?? '') === 'wheezing' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="bunyi_abnormal_wheezing">Wheezing</label>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="bunyi_abnormal" value="ronchi"
                                    id="bunyi_abnormal_ronchi" <?= $ro_disabled ?>
                                    <?= ($existing_data['bunyi_abnormal'] ?? '') === 'ronchi' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="bunyi_abnormal_ronchi">Ronchi</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-2 col-form-label"><strong>Lainnya</strong></div>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="abnormal"
                                value="<?= htmlspecialchars($existing_data['abnormal'] ?? '') ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-2 col-form-label"><strong>Frekuensi Nafas</strong></label>
                        <div class="col-sm-4">
                            <div class="input-group">
                                <input type="text" class="form-control" name="frekuensi_nafas"
                                    value="<?= htmlspecialchars($existing_data['frekuensi_nafas'] ?? '') ?>" <?= $ro ?>>
                                <span class="input-group-text">x/menit</span>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="teratur_nafas" value="teratur"
                                    id="teratur_nafas_teratur" <?= $ro_disabled ?>
                                    <?= ($existing_data['teratur_nafas'] ?? '') === 'teratur' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="teratur_nafas_teratur">Teratur</label>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="teratur_nafas" value="tidak"
                                    id="teratur_nafas_tidak" <?= $ro_disabled ?>
                                    <?= ($existing_data['teratur_nafas'] ?? '') === 'tidak' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="teratur_nafas_tidak">Tidak</label>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Irama Pernafasan</strong></div>
                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="irama_nafas" value="dangkal"
                                    id="irama_nafas_dangkal" <?= $ro_disabled ?>
                                    <?= ($existing_data['irama_nafas'] ?? '') === 'dangkal' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="irama_nafas_dangkal">Dangkal</label>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="irama_nafas" value="dalam"
                                    id="irama_nafas_dalam" <?= $ro_disabled ?>
                                    <?= ($existing_data['irama_nafas'] ?? '') === 'dalam' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="irama_nafas_dalam">Dalam</label>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Kesukaran Bernafas</strong></div>
                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="sesak_nafas" value="ya"
                                    id="sesak_nafas_ya" <?= $ro_disabled ?>
                                    <?= ($existing_data['sesak_nafas'] ?? '') === 'ya' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="sesak_nafas_ya">Ya</label>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="sesak_nafas" value="tidak"
                                    id="sesak_nafas_tidak" <?= $ro_disabled ?>
                                    <?= ($existing_data['sesak_nafas'] ?? '') === 'tidak' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="sesak_nafas_tidak">Tidak</label>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-2 col-form-label"><strong>Bunyi Perkusi Paru</strong></div>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="perkusi_paru"
                                value="<?= htmlspecialchars($existing_data['perkusi_paru'] ?? '') ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-2 col-form-label"><strong>Taktil Fremitus</strong></div>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="taktil_fremitus"
                                value="<?= htmlspecialchars($existing_data['taktil_fremitus'] ?? '') ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-2 col-form-label"><strong>Lain-lain</strong></div>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="kelainan_paru"
                                value="<?= htmlspecialchars($existing_data['kelainan_paru'] ?? '') ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <!-- c. Jantung -->
                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary"><strong>c. Jantung</strong></label>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-2 col-form-label"><strong>S1</strong></div>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" name="s1_jantung"
                                value="<?= htmlspecialchars($existing_data['s1_jantung'] ?? '') ?>" <?= $ro ?>>
                        </div>
                        <div class="col-sm-2 col-form-label"><strong>Terdapat pada</strong></div>
                        <div class="col-sm-3">
                            <input type="text" class="form-control" name="terdapat_pada_s1"
                                value="<?= htmlspecialchars($existing_data['terdapat_pada_s1'] ?? '') ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-2 col-form-label"><strong>S2</strong></div>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" name="s2_jantung"
                                value="<?= htmlspecialchars($existing_data['s2_jantung'] ?? '') ?>" <?= $ro ?>>
                        </div>
                        <div class="col-sm-2 col-form-label"><strong>Terdapat pada</strong></div>
                        <div class="col-sm-3">
                            <input type="text" class="form-control" name="terdapat_pada_s2"
                                value="<?= htmlspecialchars($existing_data['terdapat_pada_s2'] ?? '') ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Bunyi Teratur</strong></div>
                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="bunyi_jantung" value="ya"
                                    id="bunyi_jantung_ya" <?= $ro_disabled ?>
                                    <?= ($existing_data['bunyi_jantung'] ?? '') === 'ya' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="bunyi_jantung_ya">Ya</label>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="bunyi_jantung" value="tidak"
                                    id="bunyi_jantung_tidak" <?= $ro_disabled ?>
                                    <?= ($existing_data['bunyi_jantung'] ?? '') === 'tidak' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="bunyi_jantung_tidak">Tidak</label>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Bunyi Tambahan</strong></div>
                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="bunyi_tambahan_jantung" value="murmur"
                                    id="cb_bunyi_tambahan_murmur" <?= $ro_disabled ?>
                                    <?= ($existing_data['bunyi_tambahan_jantung'] ?? '') === 'murmur' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="cb_bunyi_tambahan_murmur">Murmur</label>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="bunyi_tambahan_jantung" value="tidak"
                                    id="cb_bunyi_tambahan_tidak" <?= $ro_disabled ?>
                                    <?= ($existing_data['bunyi_tambahan_jantung'] ?? '') === 'tidak' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="cb_bunyi_tambahan_tidak">Tidak</label>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-2 col-form-label"><strong>Pulsasi Jantung</strong></div>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="pulsasi_jantung"
                                value="<?= htmlspecialchars($existing_data['pulsasi_jantung'] ?? '') ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Irama</strong></div>
                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="irama_jantung" value="teratur"
                                    id="irama_jantung_teratur" <?= $ro_disabled ?>
                                    <?= ($existing_data['irama_jantung'] ?? '') === 'teratur' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="irama_jantung_teratur">Teratur</label>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="irama_jantung" value="tidak_teratur"
                                    id="irama_jantung_tidak_teratur" <?= $ro_disabled ?>
                                    <?= ($existing_data['irama_jantung'] ?? '') === 'tidak_teratur' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="irama_jantung_tidak_teratur">Tidak Teratur</label>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-2 col-form-label"><strong>Lain-lain</strong></div>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="kelainan_jantung"
                                value="<?= htmlspecialchars($existing_data['kelainan_jantung'] ?? '') ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <!-- 9. Abdomen -->
                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary"><strong>9. Abdomen</strong></label>
                    </div>

                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Bentuk</strong></div>
                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="bentuk_abdomen" value="datar"
                                    id="cb_bentuk_abdomen_datar" <?= $ro_disabled ?>
                                    <?= ($existing_data['bentuk_abdomen'] ?? '') === 'datar' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="cb_bentuk_abdomen_datar">Datar</label>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="bentuk_abdomen" value="membuncit"
                                    id="cb_bentuk_abdomen_membuncit" <?= $ro_disabled ?>
                                    <?= ($existing_data['bentuk_abdomen'] ?? '') === 'membuncit' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="cb_bentuk_abdomen_membuncit">Membuncit</label>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="bentuk_abdomen" value="cekung"
                                    id="cb_bentuk_abdomen_cekung" <?= $ro_disabled ?>
                                    <?= ($existing_data['bentuk_abdomen'] ?? '') === 'cekung' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="cb_bentuk_abdomen_cekung">Cekung</label>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="bentuk_abdomen" value="tegang"
                                    id="cb_bentuk_abdomen_tegang" <?= $ro_disabled ?>
                                    <?= ($existing_data['bentuk_abdomen'] ?? '') === 'tegang' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="cb_bentuk_abdomen_tegang">Tegang</label>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Keadaan</strong></div>
                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="keadaan_abdomen[]" value="parut"
                                    id="cb_keadaan_abdomen_parut" <?= $ro_disabled ?>
                                    <?= in_array('parut', (array)($existing_data['keadaan_abdomen'] ?? [])) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="cb_keadaan_abdomen_parut">Parut</label>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="keadaan_abdomen[]" value="lesi"
                                    id="cb_keadaan_abdomen_lesi" <?= $ro_disabled ?>
                                    <?= in_array('lesi', (array)($existing_data['keadaan_abdomen'] ?? [])) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="cb_keadaan_abdomen_lesi">Lesi</label>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="keadaan_abdomen[]" value="bercak_merah"
                                    id="cb_keadaan_abdomen_bercak_merah" <?= $ro_disabled ?>
                                    <?= in_array('bercak_merah', (array)($existing_data['keadaan_abdomen'] ?? [])) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="cb_keadaan_abdomen_bercak_merah">Bercak Merah</label>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-2 col-form-label"><strong>Bising Usus</strong></label>
                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="bising_usus" value="ada"
                                    id="bising_usus_ada" <?= $ro_disabled ?>
                                    <?= ($existing_data['bising_usus'] ?? '') === 'ada' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="bising_usus_ada">Ada</label>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="bising_usus" value="tidak"
                                    id="bising_usus_tidak" <?= $ro_disabled ?>
                                    <?= ($existing_data['bising_usus'] ?? '') === 'tidak' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="bising_usus_tidak">Tidak</label>
                            </div>
                        </div>
                        <div class="col-sm-5">
                            <div class="input-group">
                                <input type="text" class="form-control" name="bising_usus_kali"
                                    value="<?= htmlspecialchars($existing_data['bising_usus_kali'] ?? '') ?>" <?= $ro ?>>
                                <span class="input-group-text">kali</span>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-2 col-form-label"><strong>Benjolan</strong></label>
                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="benjolan_abdomen" value="ada"
                                    id="benjolan_abdomen_ada" <?= $ro_disabled ?>
                                    <?= ($existing_data['benjolan_abdomen'] ?? '') === 'ada' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="benjolan_abdomen_ada">Ada</label>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="benjolan_abdomen" value="tidak"
                                    id="benjolan_abdomen_tidak" <?= $ro_disabled ?>
                                    <?= ($existing_data['benjolan_abdomen'] ?? '') === 'tidak' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="benjolan_abdomen_tidak">Tidak</label>
                            </div>
                        </div>
                        <div class="col-sm-5">
                            <div class="input-group">
                                <span class="input-group-text">Letak</span>
                                <input type="text" class="form-control" name="benjolan_letak"
                                    value="<?= htmlspecialchars($existing_data['benjolan_letak'] ?? '') ?>" <?= $ro ?>>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-2 col-form-label"><strong>Nyeri Tekan</strong></label>
                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="nyeri_abdomen" value="ada"
                                    id="nyeri_abdomen_ada" <?= $ro_disabled ?>
                                    <?= ($existing_data['nyeri_abdomen'] ?? '') === 'ada' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="nyeri_abdomen_ada">Ada</label>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="nyeri_abdomen" value="tidak"
                                    id="nyeri_abdomen_tidak" <?= $ro_disabled ?>
                                    <?= ($existing_data['nyeri_abdomen'] ?? '') === 'tidak' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="nyeri_abdomen_tidak">Tidak</label>
                            </div>
                        </div>
                        <div class="col-sm-5">
                            <div class="input-group">
                                <span class="input-group-text">Letak</span>
                                <input type="text" class="form-control" name="nyeri_letak"
                                    value="<?= htmlspecialchars($existing_data['nyeri_letak'] ?? '') ?>" <?= $ro ?>>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-2 col-form-label"><strong>Lain-lain</strong></div>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="kelainan_abdomen"
                                value="<?= htmlspecialchars($existing_data['kelainan_abdomen'] ?? '') ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <!-- 10. Genetalia -->
                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary"><strong>10. Genetalia</strong></label>
                    </div>

                    <?php
                    $genetalia_radio = [
                        'bentuk_genetalia'  => ['label' => 'Bentuk',                  'opts' => ['utuh' => 'Utuh', 'tidak' => 'Tidak']],
                        'radang_genetalia'  => ['label' => 'Radang',                  'opts' => ['ada' => 'Ada', 'tidak' => 'Tidak']],
                        'sekret_genetalia'  => ['label' => 'Sekret/Cairan',            'opts' => ['ada' => 'Ada', 'tidak' => 'Tidak']],
                        'skrotum_bengkak'   => ['label' => 'Pembengkakan Skrotum',    'opts' => ['ada' => 'Ada', 'tidak' => 'Tidak']],
                        'rektum_benjolan'   => ['label' => 'Benjolan pada Rektum',    'opts' => ['ada' => 'Ada', 'tidak' => 'Tidak']],
                        'terdapat_lesi'     => ['label' => 'Terdapat Lesi',           'opts' => ['ada' => 'Ada', 'tidak' => 'Tidak']],
                    ];
                    foreach ($genetalia_radio as $name => $cfg): ?>
                        <div class="row mb-2">
                            <div class="col-sm-2"><strong><?= $cfg['label'] ?></strong></div>
                            <?php foreach ($cfg['opts'] as $val => $lbl): ?>
                                <div class="col-sm-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="<?= $name ?>" value="<?= $val ?>"
                                            id="<?= $name ?>_<?= $val ?>" <?= $ro_disabled ?>
                                            <?= ($existing_data[$name] ?? '') === $val ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="<?= $name ?>_<?= $val ?>"><?= $lbl ?></label>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endforeach; ?>

                    <div class="row mb-3">
                        <div class="col-sm-2 col-form-label"><strong>Lain-lain</strong></div>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="kelainan_genetalia"
                                value="<?= htmlspecialchars($existing_data['kelainan_genetalia'] ?? '') ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <!-- 11. Ekstremitas Atas -->
                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary"><strong>11. Ekstremitas Atas</strong></label>
                    </div>

                    <?php
                    $ekst_atas_radio = [
                        'atas_simetris'     => ['label' => 'Bentuk Simetris',  'opts' => ['ya' => 'Ya', 'tidak' => 'Tidak']],
                        'sensasi_halus'     => ['label' => 'Sensasi Halus',    'opts' => ['ada' => 'Ada', 'tidak' => 'Tidak']],
                        'sensasi_tajam'     => ['label' => 'Sensasi Tajam',    'opts' => ['ada' => 'Ada', 'tidak' => 'Tidak']],
                        'sensasi_panas'     => ['label' => 'Sensasi Panas',    'opts' => ['ada' => 'Ada', 'tidak' => 'Tidak']],
                        'sensasi_dingin'    => ['label' => 'Sensasi Dingin',   'opts' => ['ada' => 'Ada', 'tidak' => 'Tidak']],
                        'rom_atas'          => ['label' => 'Gerakan ROM',      'opts' => ['dapat' => 'Dapat', 'tidak' => 'Tidak']],
                        'refleks_bisep'     => ['label' => 'Refleks Bisep',    'opts' => ['ada' => 'Ada', 'tidak' => 'Tidak']],
                        'refleks_trisep'    => ['label' => 'Refleks Trisep',   'opts' => ['ada' => 'Ada', 'tidak' => 'Tidak']],
                        'pembengkakan_atas' => ['label' => 'Pembengkakan',     'opts' => ['ya' => 'Ya', 'tidak' => 'Tidak']],
                        'kelembaban_atas'   => ['label' => 'Kelembaban',       'opts' => ['lembab' => 'Lembab', 'kering' => 'Kering']],
                        'temperatur_atas'   => ['label' => 'Temperatur',       'opts' => ['panas' => 'Panas', 'dingin' => 'Dingin']],
                    ];
                    foreach ($ekst_atas_radio as $name => $cfg): ?>
                        <div class="row mb-2">
                            <div class="col-sm-2"><strong><?= $cfg['label'] ?></strong></div>
                            <?php foreach ($cfg['opts'] as $val => $lbl): ?>
                                <div class="col-sm-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="<?= $name ?>" value="<?= $val ?>"
                                            id="<?= $name ?>_<?= $val ?>" <?= $ro_disabled ?>
                                            <?= ($existing_data[$name] ?? '') === $val ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="<?= $name ?>_<?= $val ?>"><?= $lbl ?></label>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endforeach; ?>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Kekuatan Otot Tangan</strong></label>
                        <div class="col-sm-9">
                            <div class="row">
                                <div class="col-md-4 d-flex align-items-center">
                                    <label class="me-2"><strong>Kanan</strong></label>
                                    <input type="text" class="form-control" name="otot_tangan_kanan"
                                        value="<?= htmlspecialchars($existing_data['otot_tangan_kanan'] ?? '') ?>" <?= $ro ?>>
                                </div>
                                <div class="col-md-4 d-flex align-items-center">
                                    <label class="me-2"><strong>Kiri</strong></label>
                                    <input type="text" class="form-control" name="otot_tangan_kiri"
                                        value="<?= htmlspecialchars($existing_data['otot_tangan_kiri'] ?? '') ?>" <?= $ro ?>>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-2 col-form-label"><strong>Lain-lain</strong></div>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="kelainan_ekstremitas_atas"
                                value="<?= htmlspecialchars($existing_data['kelainan_ekstremitas_atas'] ?? '') ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <!-- 12. Ekstremitas Bawah -->
                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary"><strong>12. Ekstremitas Bawah</strong></label>
                    </div>

                    <?php
                    $ekst_bawah_radio = [
                        'bawah_simetris'        => ['label' => 'Bentuk Simetris',  'opts' => ['ya' => 'Ya', 'tidak' => 'Tidak']],
                        'bawah_sensasi_halus'   => ['label' => 'Sensasi Halus',    'opts' => ['ada' => 'Ada', 'tidak' => 'Tidak']],
                        'bawah_sensasi_tajam'   => ['label' => 'Sensasi Tajam',    'opts' => ['ada' => 'Ada', 'tidak' => 'Tidak']],
                        'bawah_sensasi_panas'   => ['label' => 'Sensasi Panas',    'opts' => ['ada' => 'Ada', 'tidak' => 'Tidak']],
                        'bawah_sensasi_dingin'  => ['label' => 'Sensasi Dingin',   'opts' => ['ada' => 'Ada', 'tidak' => 'Tidak']],
                        'rom_bawah'             => ['label' => 'Gerakan ROM',      'opts' => ['dapat' => 'Dapat', 'tidak' => 'Tidak']],
                        'refleks_babinski'      => ['label' => 'Refleks Lipat Paha', 'opts' => ['ada' => 'Ada', 'tidak' => 'Tidak']],
                        'varises_bawah'         => ['label' => 'Varises',          'opts' => ['ada' => 'Ada', 'tidak' => 'Tidak']],
                        'pembengkakan_bawah'    => ['label' => 'Pembengkakan',     'opts' => ['ya' => 'Ya', 'tidak' => 'Tidak']],
                        'kelembaban_bawah'      => ['label' => 'Kelembaban',       'opts' => ['lembab' => 'Lembab', 'kering' => 'Kering']],
                        'temperatur_bawah'      => ['label' => 'Temperatur',       'opts' => ['panas' => 'Panas', 'dingin' => 'Dingin']],
                    ];
                    foreach ($ekst_bawah_radio as $name => $cfg): ?>
                        <div class="row mb-2">
                            <div class="col-sm-2"><strong><?= $cfg['label'] ?></strong></div>
                            <?php foreach ($cfg['opts'] as $val => $lbl): ?>
                                <div class="col-sm-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="<?= $name ?>" value="<?= $val ?>"
                                            id="<?= $name ?>_<?= $val ?>" <?= $ro_disabled ?>
                                            <?= ($existing_data[$name] ?? '') === $val ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="<?= $name ?>_<?= $val ?>"><?= $lbl ?></label>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endforeach; ?>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Kekuatan Otot Kaki</strong></label>
                        <div class="col-sm-9">
                            <div class="row">
                                <div class="col-md-4 d-flex align-items-center">
                                    <label class="me-2"><strong>Kanan</strong></label>
                                    <input type="text" class="form-control" name="otot_kaki_kanan"
                                        value="<?= htmlspecialchars($existing_data['otot_kaki_kanan'] ?? '') ?>" <?= $ro ?>>
                                </div>
                                <div class="col-md-4 d-flex align-items-center">
                                    <label class="me-2"><strong>Kiri</strong></label>
                                    <input type="text" class="form-control" name="otot_kaki_kiri"
                                        value="<?= htmlspecialchars($existing_data['otot_kaki_kiri'] ?? '') ?>" <?= $ro ?>>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-2 col-form-label"><strong>Lain-lain</strong></div>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="kelainan_ekstremitas_bawah"
                                value="<?= htmlspecialchars($existing_data['kelainan_ekstremitas_bawah'] ?? '') ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <!-- 13. Kulit -->
                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary"><strong>13. Kulit</strong></label>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-2 col-form-label"><strong>Warna</strong></div>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="warna_kulit"
                                value="<?= htmlspecialchars($existing_data['warna_kulit'] ?? '') ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Turgor</strong></div>
                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="turgor_kulit" value="elastis"
                                    id="turgor_kulit_elastis" <?= $ro_disabled ?>
                                    <?= ($existing_data['turgor_kulit'] ?? '') === 'elastis' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="turgor_kulit_elastis">Elastis</label>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="turgor_kulit" value="menurun"
                                    id="turgor_kulit_menurun" <?= $ro_disabled ?>
                                    <?= ($existing_data['turgor_kulit'] ?? '') === 'menurun' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="turgor_kulit_menurun">Menurun</label>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Keadaan</strong></div>
                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="kelembaban_kulit" value="lembab"
                                    id="kelembaban_kulit_lembab" <?= $ro_disabled ?>
                                    <?= ($existing_data['kelembaban_kulit'] ?? '') === 'lembab' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="kelembaban_kulit_lembab">Lembab</label>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="kelembaban_kulit" value="kering"
                                    id="kelembaban_kulit_kering" <?= $ro_disabled ?>
                                    <?= ($existing_data['kelembaban_kulit'] ?? '') === 'kering' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="kelembaban_kulit_kering">Kering</label>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-2 col-form-label"><strong>Edema</strong></label>
                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="edema_kulit" value="ada"
                                    id="edema_kulit_ada" <?= $ro_disabled ?>
                                    <?= ($existing_data['edema_kulit'] ?? '') === 'ada' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="edema_kulit_ada">Ada</label>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="edema_kulit" value="tidak"
                                    id="edema_kulit_tidak" <?= $ro_disabled ?>
                                    <?= ($existing_data['edema_kulit'] ?? '') === 'tidak' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="edema_kulit_tidak">Tidak</label>
                            </div>
                        </div>
                        <div class="col-sm-5">
                            <div class="input-group">
                                <span class="input-group-text">Pada Daerah</span>
                                <input type="text" class="form-control" name="pada_daerah"
                                    value="<?= htmlspecialchars($existing_data['pada_daerah'] ?? '') ?>" <?= $ro ?>>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-2 col-form-label"><strong>Luka</strong></label>
                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="luka_kulit" value="ada"
                                    id="luka_kulit_ada" <?= $ro_disabled ?>
                                    <?= ($existing_data['luka_kulit'] ?? '') === 'ada' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="luka_kulit_ada">Ada</label>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="luka_kulit" value="tidak"
                                    id="luka_kulit_tidak" <?= $ro_disabled ?>
                                    <?= ($existing_data['luka_kulit'] ?? '') === 'tidak' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="luka_kulit_tidak">Tidak</label>
                            </div>
                        </div>
                        <div class="col-sm-5">
                            <div class="input-group">
                                <span class="input-group-text">Pada Daerah</span>
                                <input type="text" class="form-control" name="pada_daerah_luka"
                                    value="<?= htmlspecialchars($existing_data['pada_daerah_luka'] ?? '') ?>" <?= $ro ?>>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-2 col-form-label"><strong>Karakteristik Luka</strong></div>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="karakteristik_luka"
                                value="<?= htmlspecialchars($existing_data['karakteristik_luka'] ?? '') ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Tekstur</strong></div>
                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="tekstur_kulit" value="keriput"
                                    id="tekstur_kulit_keriput" <?= $ro_disabled ?>
                                    <?= ($existing_data['tekstur_kulit'] ?? '') === 'keriput' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="tekstur_kulit_keriput">Keriput</label>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="tekstur_kulit" value="kasar"
                                    id="tekstur_kulit_kasar" <?= $ro_disabled ?>
                                    <?= ($existing_data['tekstur_kulit'] ?? '') === 'kasar' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="tekstur_kulit_kasar">Kasar</label>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-2 col-form-label"><strong>Lain-lain</strong></div>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="kelainan_kulit"
                                value="<?= htmlspecialchars($existing_data['kelainan_kulit'] ?? '') ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <!-- 14. Kuku -->
                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary"><strong>14. Kuku</strong></label>
                    </div>

                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Clubbing Finger</strong></div>
                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="clubbing_finger" value="ya"
                                    id="clubbing_finger_ya" <?= $ro_disabled ?>
                                    <?= ($existing_data['clubbing_finger'] ?? '') === 'ya' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="clubbing_finger_ya">Ya</label>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="clubbing_finger" value="tidak"
                                    id="clubbing_finger_tidak" <?= $ro_disabled ?>
                                    <?= ($existing_data['clubbing_finger'] ?? '') === 'tidak' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="clubbing_finger_tidak">Tidak</label>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Capillary Refill Time</strong></div>
                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="crt" value="≤2"
                                    id="crt_le2" <?= $ro_disabled ?>
                                    <?= ($existing_data['crt'] ?? '') === '≤2' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="crt_le2">≤2 detik</label>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="crt" value=">2"
                                    id="crt_gt2" <?= $ro_disabled ?>
                                    <?= ($existing_data['crt'] ?? '') === '>2' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="crt_gt2">&gt;2 detik</label>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-2 col-form-label"><strong>Lain-lain</strong></div>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="kelainan_kuku"
                                value="<?= htmlspecialchars($existing_data['kelainan_kuku'] ?? '') ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <!-- F. Data Penunjang -->
                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary"><strong>F. Data Penunjang</strong></label>
                    </div>

                    <!-- a. Laboratorium -->
                    <p class="fw-bold mb-2">a. Laboratorium</p>
                    <table class="table table-bordered" id="tabel-lab">
                        <thead>
                            <tr>
                                <th class="text-center" style="width:40px">No</th>
                                <th class="text-center">Pemeriksaan</th>
                                <th class="text-center">Hasil</th>
                                <th class="text-center">Nilai Normal</th>
                                <?php if (!$is_readonly): ?>
                                    <th class="text-center" style="width:60px">Aksi</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody id="tbody-lab"></tbody>
                    </table>
                    <?php if (!$is_readonly): ?>
                        <div class="row mb-4">
                            <div class="col-sm-12 d-flex justify-content-end">
                                <button type="button" class="btn btn-primary btn-sm" id="btn-tambah-lab"
                                    onclick="tambahRowLab()">+ Tambah Pemeriksaan</button>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- b. Radiologi -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>b. Radiologi</strong></label>
                        <div class="col-sm-10">
                            <textarea name="radiologi" class="form-control" rows="3"
                                style="overflow:hidden; resize:none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro_disabled ?>><?= htmlspecialchars($existing_data['radiologi'] ?? '') ?></textarea>
                        </div>
                    </div>

                    <!-- c. EKG -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>c. EKG</strong></label>
                        <div class="col-sm-10">
                            <?php if (!empty($existing_ekg)): ?>
                                <img src="<?= htmlspecialchars($existing_ekg) ?>"
                                    class="img-fluid rounded border mb-2" style="max-height:400px;">
                            <?php endif; ?>
                            <?php if (!$is_readonly): ?>
                                <input type="file" class="form-control" name="ekg"
                                    accept="image/jpeg,image/png,image/webp">
                                <small class="text-muted">Format: JPG, PNG, WebP. Maks 2MB.</small>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- d. USG -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>d. USG</strong></label>
                        <div class="col-sm-10">
                            <textarea name="usg" class="form-control" rows="3"
                                style="overflow:hidden; resize:none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro_disabled ?>><?= htmlspecialchars($existing_data['usg'] ?? '') ?></textarea>
                        </div>
                    </div>

                    <!-- e. CT Scan -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>e. CT Scan</strong></label>
                        <div class="col-sm-10">
                            <textarea name="ct" class="form-control" rows="3"
                                style="overflow:hidden; resize:none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro_disabled ?>><?= htmlspecialchars($existing_data['ct'] ?? '') ?></textarea>
                        </div>
                    </div>

                    <!-- ========================= -->
                    <!-- TERAPI / OBAT -->
                    <!-- ========================= -->
                    <p class="text-primary fw-bold mb-2">
                        Terapi / Obat
                    </p>

                    <table class="table table-bordered align-middle" id="tabel-obat">

                        <thead class="table-light">
                            <tr>
                                <th class="text-center" width="40">No</th>
                                <th class="text-center">Jenis Obat</th>
                                <th class="text-center">Dosis</th>
                                <th class="text-center">Manfaat</th>
                                <th class="text-center">Cara Pemberian</th>
                                <th class="text-center" width="60">Aksi</th>
                            </tr>
                        </thead>

                        <tbody id="tbody-obat"></tbody>

                    </table>

                    <?php if (!$is_readonly): ?>
                        <div class="text-end mb-4">
                            <button type="button"
                                class="btn btn-primary btn-sm"
                                onclick="tambahRowObat()">

                                + Tambah Obat

                            </button>
                        </div>
                    <?php endif; ?>

                    <!-- Tombol Simpan -->
                    <?php if (!$is_dosen): ?>
                        <div class="row mb-3">
                            <div class="col-sm-12 d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </div>
                    <?php endif; ?>

                </form>
            </div>
        </div>

        <?php include dirname(__DIR__, 2) . '/partials/footer_form.php'; ?>

    </section>
</main>

<script>
    let rowObatCount = 1;
    let rowLabCount = 1;
    const existingLab = <?= json_encode($existing_lab) ?>;
    const existingObat = <?= json_encode($existing_obat) ?>;
    const isReadonly = <?= json_encode($is_readonly) ?>;

    function tambahRowLab(data = null) {
        const tbody = document.getElementById('tbody-lab');
        const index = rowLabCount;
        const ro = isReadonly ? 'readonly' : '';
        const row = document.createElement('tr');
        row.innerHTML = `
            <td class="text-center align-middle">${index}</td>
            <td><input type="text" class="form-control form-control-sm" name="lab[${index}][pemeriksaan]" value="${data?.pemeriksaan ?? ''}" ${ro}></td>
            <td><input type="text" class="form-control form-control-sm" name="lab[${index}][hasil]" value="${data?.hasil ?? ''}" ${ro}></td>
            <td><input type="text" class="form-control form-control-sm" name="lab[${index}][nilai_normal]" value="${data?.nilai_normal ?? ''}" ${ro}></td>
            ${!isReadonly ? `<td class="text-center align-middle"><button type="button" class="btn btn-danger btn-sm" onclick="hapusRow(this)">x</button></td>` : ''}
        `;
        tbody.appendChild(row);
        rowLabCount++;
    }

    function tambahRowObat(data = null) {
        const tbody = document.getElementById('tbody-obat');
        const index = rowObatCount;
        const ro = isReadonly ? 'readonly' : '';
        const row = document.createElement('tr');

        row.innerHTML = `
            <td class="text-center align-middle">${index}</td>
            <td><input type="text" class="form-control form-control-sm" name="obat[${index}][jenis_obat]" value="${data?.jenis_obat ?? ''}" ${ro}></td>
            <td><input type="text" class="form-control form-control-sm" name="obat[${index}][dosis]" value="${data?.dosis ?? ''}" ${ro}></td>
            <td><input type="text" class="form-control form-control-sm" name="obat[${index}][kegunaan]" value="${data?.kegunaan ?? ''}" ${ro}></td>
            <td><input type="text" class="form-control form-control-sm" name="obat[${index}][cara_pemberian]" value="${data?.cara_pemberian ?? ''}" ${ro}></td>
            ${!isReadonly ? `<td class="text-center align-middle"><button type="button" class="btn btn-danger btn-sm" onclick="hapusRow(this)">x</button></td>` : ''}
        `;
        tbody.appendChild(row);
        rowObatCount++;
    }


    function hapusRow(btn) {
        btn.closest('tr').remove();
    }

    window.addEventListener('load', function() {
        if (existingLab && existingLab.length > 0) {
            existingLab.forEach(row => tambahRowLab(row));
        } else {
            tambahRowLab();
        }
        // Tambahkan ini untuk memuat data obat
        if (existingObat && existingObat.length > 0) {
            existingObat.forEach(row => tambahRowObat(row));
        } else {
            tambahRowObat();
        }
    });
</script>