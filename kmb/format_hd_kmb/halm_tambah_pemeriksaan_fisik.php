<?php

$form_id       = 9;
$section_name  = 'pemeriksaan_fisik';
$section_label = 'Pemeriksaan Fisik';
include dirname(__DIR__, 2) . '/partials/init_section.php';

// Decode checkbox fields
$checkbox_fields = ['bau_mulut', 'bentuk_abdomen', 'keadaan_abdomen'];
foreach ($checkbox_fields as $cf) {
    $existing_data[$cf] = isset($existing_data[$cf])
        ? (is_array($existing_data[$cf])
            ? $existing_data[$cf]
            : (json_decode($existing_data[$cf], true) ?? []))
        : [];
}
// =============================================
// HANDLE POST - MAHASISWA SIMPAN DATA
// =============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $level === 'Mahasiswa') {

    if (isLocked($submission)) {
        redirectWithMessage($_SERVER['REQUEST_URI'], 'error', 'Data tidak dapat diubah karena sedang dalam proses review.');
    }

    $data = [
        'kepala'            => $_POST['kepala'] ?? '',
        'rambut'             => $_POST['rambut'] ?? '',
        'rambut_dicabut'           => $_POST['rambut_dicabut'] ?? '',
        'kelainan_rambut'          => $_POST['kelainan_rambut'] ?? '',
        'ekspresi_wajah'           => $_POST['ekspresi_wajah'] ?? '',
        'simetris_wajah'           => $_POST['simetris_wajah'] ?? '',
        'udema_wajah'              => $_POST['udema_wajah'] ?? '',
        'kelainan_wajah'           => $_POST['kelainan_wajah'] ?? '',
        'mata'            => $_POST['mata'] ?? '',
        'telinga'         => $_POST['telinga'] ?? '',
        'hidung'          => $_POST['hidung'] ?? '',
        'mulut'           => $_POST['mulut'] ?? '',
        'leher'           => $_POST['leher'] ?? '',
        'bentuk_dada'              => $_POST['bentuk_dada'] ?? '',
        'pengembangan_dada'        => $_POST['pengembangan_dada'] ?? '',
        'perbandingan_dada'        => $_POST['perbandingan_dada'] ?? '',
        'otot_pernafasan'          => $_POST['otot_pernafasan'] ?? '',
        'frekuensi'          => $_POST['frekuensi'] ?? '',
        'frekuensi_nafas'          => $_POST['frekuensi_nafas'] ?? '',
        'teratur_nafas1'            => $_POST['teratur_nafas1'] ?? '',
        'teratur_nafas'            => $_POST['teratur_nafas'] ?? '',
        'irama_nafas'              => $_POST['irama_nafas'] ?? '',
        'sesak_nafas'              => $_POST['sesak_nafas'] ?? '',
        'taktil_fremitus'          => $_POST['taktil_fremitus'] ?? '',
        'perkusi_paru'             => $_POST['perkusi_paru'] ?? '',
        'bunyi_abnormal'           => $_POST['bunyi_abnormal'] ?? '',
        'abnormal'                 => $_POST['abnormal'] ?? '',
        's1_jantung'               => $_POST['s1_jantung'] ?? '',
        's2_jantung'               => $_POST['s2_jantung'] ?? '',
        'bunyi_jantung'            => $_POST['bunyi_jantung'] ?? '',
        'bunyi'           => $_POST['bunyi'] ?? '',
        'pulsasi_jantung'          => $_POST['pulsasi_jantung'] ?? '',
        'irama_jantung'            => $_POST['irama_jantung'] ?? '',
        'bentuk_abdomen'           => $_POST['bentuk_abdomen'] ?? [],
        'keadaan_abdomen'          => $_POST['keadaan_abdomen'] ?? [],
        'bising_usus'              => $_POST['bising_usus'] ?? '',
        'frekuensi1'                => $_POST['frekuensi1'] ?? '',
        'benjolan_abdomen'         => $_POST['benjolan_abdomen'] ?? '',
        'letak1' => $_POST['letak1'] ?? '',
        'nyeri_abdomen'            => $_POST['nyeri_abdomen'] ?? '',
        'frekuensi_tekan'            => $_POST['frekuensi_tekan'] ?? '',
        'perkusi_abdomen'          => $_POST['perkusi_abdomen'] ?? '',
        'kelainan_abdomen'         => $_POST['kelainan_abdomen'] ?? '',
        'genetalia'         => $_POST['genetalia'] ?? '',
        'atas_simetris'            => $_POST['atas_simetris'] ?? '',
        'sensasi_halus'            => $_POST['sensasi_halus'] ?? '',
        'sensasi_tajam'            => $_POST['sensasi_tajam'] ?? '',
        'sensasi_panas'            => $_POST['sensasi_panas'] ?? '',
        'sensasi_dingin'           => $_POST['sensasi_dingin'] ?? '',
        'rom_atas'                 => $_POST['rom_atas'] ?? '',
        'refleks_bisep'                 => $_POST['refleks_bisep'] ?? '',
        'refleks_trisep'                 => $_POST['refleks_trisep'] ?? '',
        'refleks_babinski'         => $_POST['refleks_babinski'] ?? '',
        'pembengkakan5'             => $_POST['pembengkakan5'] ?? '',
        'pembengkakan2'             => $_POST['pembengkakan2'] ?? '',
        'varises'                  => $_POST['varises'] ?? '',
        'kelembaban'               => $_POST['kelembaban'] ?? '',
        'kelembaban1'               => $_POST['kelembaban1'] ?? '',
        'temperatur'               => $_POST['temperatur'] ?? '',
        'kanan1'                    => $_POST['kanan1'] ?? '',
        'kiri1'                     => $_POST['kiri1'] ?? '',
        'kelainan_genetalia1'       => $_POST['kelainan_genetalia1'] ?? '',

        'clubbing_finger'          => $_POST['clubbing_finger'] ?? '',
        'capillary_refill_time'    => $_POST['capillary_refill_time'] ?? '',
        'keadaan_kuku'             => $_POST['keadaan_kuku'] ?? '',
        'nervus1_penciuman'        => $_POST['nervus1_penciuman'] ?? '',
        'nervus2_penglihatan'      => $_POST['nervus2_penglihatan'] ?? '',
        'konstriksi_pupil'         => $_POST['konstriksi_pupil'] ?? '',
        'gerakan_kelopak'          => $_POST['gerakan_kelopak'] ?? '',
        'gerakan_bola_mata'        => $_POST['gerakan_bola_mata'] ?? '',
        'gerakan_mata_bawah'       => $_POST['gerakan_mata_bawah'] ?? '',
        'refleks_dagu'             => $_POST['refleks_dagu'] ?? '',
        'refleks_cornea'           => $_POST['refleks_cornea'] ?? '',
        'pengecapan_depan'         => $_POST['pengecapan_depan'] ?? '',
        'fungsi_pendengaran'       => $_POST['fungsi_pendengaran'] ?? '',
        'refleks_menelan1'          => $_POST['refleks_menelan1'] ?? '',
        'refleks_muntah'           => $_POST['refleks_muntah'] ?? '',
        'pengecapan_belakang'      => $_POST['pengecapan_belakang'] ?? '',
        'suara_pasien'             => $_POST['suara_pasien'] ?? '',
        'gerakan_kepala'           => $_POST['gerakan_kepala'] ?? '',
        'angkat_bahu'              => $_POST['angkat_bahu'] ?? '',
        'deviasi_lidah'            => $_POST['deviasi_lidah'] ?? '',
        'kaku_kuduk'               => $_POST['kaku_kuduk'] ?? '',
        'kernig_sign'              => $_POST['kernig_sign'] ?? '',
        'refleks_brudzinski'       => $_POST['refleks_brudzinski'] ?? '',
        'warna_kulit'            => $_POST['warna_kulit'] ?? '',
        'turgor_kulit'           => $_POST['turgor_kulit'] ?? '',
        'kelembaban2'             => $_POST['kelembaban2'] ?? '',
        'edema_kulit'            => $_POST['edema_kulit'] ?? '',
        'pada_daerah'            => $_POST['pada_daerah'] ?? '',
        'luka_kulit'             => $_POST['luka_kulit'] ?? '',
        'karakteristik_luka'     => $_POST['karakteristik_luka'] ?? '',
        'tekstur_kulit'          => $_POST['tekstur_kulit'] ?? '',
        'kelainan_kulit'         => $_POST['kelainan_kulit'] ?? '',
        'luka_kulit1'             => $_POST['luka_kulit1'] ?? '',
        'pada_daerah1'             => $_POST['pada_daerah1'] ?? '',
        'bawah_simetris'           => $_POST['bawah_simetris'] ?? '',
        'sensasi_bawah'            => $_POST['sensasi_bawah'] ?? '',
        'bawah_tajam'            => $_POST['sensasi_tajam'] ?? '',
        'sensasi_panasb'           => $_POST['sensasi_panasb'] ?? '',
        'sensasi_dinginb'          => $_POST['sensasi_dinginb'] ?? '',
        'rom_bawah'                => $_POST['rom_bawah'] ?? '',
        'refleks_babinski1'         => $_POST['refleks_babinski1'] ?? '',
        'pembengkakan3'            => $_POST['pembengkakan3'] ?? '',
        'varises1'                  => $_POST['varises1'] ?? '',
        'kelembaban3'              => $_POST['kelembaban3'] ?? '',
        'temperaturb'              => $_POST['temperaturb'] ?? '',
        'kanankaki'                    => $_POST['kanankaki'] ?? '',
        'kirikaki'                     => $_POST['kirikaki'] ?? '',
        'kelainan_genetalia2'      => $_POST['kelainan_genetalia2'] ?? '',
        'neurologi'      => $_POST['neurologi'] ?? '',

    ];
    $checkbox_fields = ['bau_mulut', 'bentuk_abdomen', 'keadaan_abdomen'];
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
    <?php include "kmb/format_hd_kmb/tab.php"; ?>
    <?php include dirname(__DIR__, 2) . '/partials/notifikasi.php'; ?>
    <?php include dirname(__DIR__, 2) . '/partials/status_section.php'; ?>

    <div class="card">
        <div class="card-body">
            <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

                <h5 class="card-title"><strong>4. Pemeriksaan fisik</strong></h5>
                <div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>a. Kepala</strong></label>
</div>

<div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>Kepala</strong>
    </div>
    <div class="col-sm-10">
        <!-- Textarea Auto-Resize untuk Pemeriksaan Kepala -->
                    <textarea name="kepala" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('kepala',$existing_data) ?></textarea>

        </div>
</div>

<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>b. Rambut</strong></label>
</div>

<div class="row mb-3">
    <div class="col-sm-2 col-form-label"><strong>Rambut</strong></div>
    <div class="col-sm-10">
        <!-- Textarea Auto-Resize untuk Pemeriksaan Rambut -->
                <textarea name="rambut" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('rambut',$existing_data) ?></textarea>

        </div>
</div>
                <div class="row mb-2">
                    <label class="col-sm-12 text-primary"><strong>c. Wajah</strong></label>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-2 col-form-label"><strong>Ekspresi Wajah</strong></div>
                    <div class="col-sm-10">
                        <textarea name="ekspresi_wajah" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('ekspresi_wajah',$existing_data) ?></textarea>
                        </div>
                </div>

                <!-- Kesimetrisan Wajah -->
                <div class="row mb-2">
                    <div class="col-sm-2"><strong>Kesimetrisan Wajah</strong></div>
                    <div class="col-sm-10">
                        <select class="form-select" name="simetris_wajah" <?= $ro_select ?>>
                            <option value="">Pilih</option>
                            <option value="ya" <?= ($existing_data['simetris_wajah'] ?? '') === 'ya' ? 'selected' : '' ?>>Ya</option>
                            <option value="tidak" <?= ($existing_data['simetris_wajah'] ?? '') === 'tidak' ? 'selected' : '' ?>>Tidak</option>
                        </select>
                    </div>
                </div>

                <!-- Udema Wajah -->
                <div class="row mb-2">
                    <div class="col-sm-2"><strong>Terdapat Udema</strong></div>
                    <div class="col-sm-10">
                        <select class="form-select" name="udema_wajah" <?= $ro_select ?>>
                            <option value="">Pilih</option>
                            <option value="ya" <?= ($existing_data['udema_wajah'] ?? '') === 'ya' ? 'selected' : '' ?>>Ya</option>
                            <option value="tidak" <?= ($existing_data['udema_wajah'] ?? '') === 'tidak' ? 'selected' : '' ?>>Tidak</option>
                        </select>
                    </div>
                </div>

                <!-- Kelainan Wajah -->
                <div class="row mb-3">
                    <div class="col-sm-2 col-form-label"><strong>Kelainan</strong></div>
                    <div class="col-sm-10">
                        <textarea name="kelainan_wajah" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('kelainan_wajah',$existing_data) ?></textarea>
                        </div>
                </div>

               <div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>d. Mata</strong></label>
</div>

<!-- Kelainan Mata -->
<div class="row mb-3">
    <div class="col-sm-2 col-form-label"><strong>Mata</strong></div>
    <div class="col-sm-10">
        <textarea name="mata" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('mata',$existing_data) ?></textarea>
        </div>
</div>

<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>e. Telinga</strong></label>
</div>

<!-- Kelainan Telinga -->
<div class="row mb-3">
    <div class="col-sm-2 col-form-label"><strong>Telinga</strong></div>
    <div class="col-sm-10">
        <textarea name="telinga" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('telinga',$existing_data) ?></textarea>
        </div>
</div>

<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>f. Hidung</strong></label>
</div>

<!-- Kelainan Hidung -->
<div class="row mb-3">
    <div class="col-sm-2 col-form-label"><strong>Hidung</strong></div>
    <div class="col-sm-10">
        <textarea name="hidung" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('hidung',$existing_data) ?></textarea>
        </div>
</div>

<div class="row mb-2"> <!-- Diubah ke mb-2 agar jaraknya konsisten dengan judul bagian lain -->
    <label class="col-sm-12 text-primary"><strong>g. Mulut</strong></label>
</div>

<!-- Warna Gigi / Pemeriksaan Mulut -->
<div class="row mb-3">
    <div class="col-sm-2 col-form-label"><strong>Mulut</strong></div>
    <div class="col-sm-10">
        <textarea name="mulut" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('mulut',$existing_data) ?></textarea>
        </div>
</div>

<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>h. Leher</strong></label>
</div>

<!-- Kelainan Leher -->
<div class="row mb-3">
    <div class="col-sm-2 col-form-label"><strong>Leher</strong></div>
    <div class="col-sm-10">
        <textarea name="leher" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('leher',$existing_data) ?></textarea>
        </div>
</div>
                        <div class="row mb-2">
                        <label class="col-sm-12 text-primary"><strong>i. Dada</strong></label>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-2 col-form-label">
                            <strong>Bentuk Dada</strong>
                        </div>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="bentuk_dada" value="<?= htmlspecialchars($existing_data['bentuk_dada'] ?? '') ?>" <?= $ro ?>>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-2 col-form-label">
                            <strong>Pengembangan Dada</strong>
                        </div>
                        <div class="col-sm-10">
                            <textarea name="pengembangan_dada" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('pengemabangan_dada',$existing_data) ?></textarea>

                            </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-2 col-form-label">
                            <strong>Perbandingan ukuran anterior-posterior dengan transversal</strong>
                        </div>
                        <div class="col-sm-10">
                        <textarea name="perbandingan_dada" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('perbandingan_dada',$existing_data) ?></textarea>
                            </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Penggunaan Otot Pernafasan Tambahan</strong>
                        </div>

                        <div class="col-sm-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="otot_pernafasan" value="ya" id="otot_pernafasan_ya" <?= $ro_disabled ?> <?= ($existing_data['otot_pernafasan'] ?? '') === 'ya' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="otot_pernafasan_ya">Ya</label>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="otot_pernafasan" value="tidak" id="otot_pernafasan_tidak" <?= $ro_disabled ?> <?= ($existing_data['otot_pernafasan'] ?? '') === 'tidak' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="otot_pernafasan_tidak">Tidak</label>
                            </div>
                        </div>
                    </div>

                        <div class="row mb-2">
                            <label class="col-sm-12 text-primary"><strong>j. Paru-Paru</strong></label>
                        </div>

                        <!-- Frekuensi Nafas -->
                        <div class="row mb-3 align-items-center">
                            <label class="col-sm-2 col-form-label"><strong>Frekuensi Nafas</strong></label>
                            <div class="col-sm-4">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="frekuensi" value="<?= val('frekuensi', $existing_data) ?>" <?= $ro_select ?>>
                                    <span class="input-group-text">x/menit</span>
                                </div>
                            </div>
                            <div class="col-sm-2"><strong></strong></div>
                            <div class="col-sm-4">
                                <select class="form-select" name="teratur_nafas1" <?= $ro_select ?>>
                                    <option value="">Pilih</option>
                                    <option value="teratur" <?= val('teratur_nafas1', $existing_data) === 'teratur' ? 'selected' : '' ?>>Teratur</option>
                                    <option value="tidak" <?= val('teratur_nafas1', $existing_data) === 'tidak' ? 'selected' : '' ?>>Tidak</option>
                                </select>
                            </div>
                        </div>

                        <!-- Irama Pernafasan -->
                        <div class="row mb-2">
                            <div class="col-sm-2"><strong>Irama Pernafasan</strong></div>
                            <div class="col-sm-10">
                                <select class="form-select" name="irama_nafas"<?= $ro_select ?>>
                                    <option value="">Pilih</option>
                                    <option value="dangkal" <?= val('irama_nafas', $existing_data) === 'dangkal' ? 'selected' : '' ?>>Dangkal</option>
                                    <option value="dalam" <?= val('irama_nafas', $existing_data) === 'dalam' ? 'selected' : '' ?>>Dalam</option>
                                </select>
                            </div>
                        </div>

                        <!-- Kesukaran Bernafas -->
                        <div class="row mb-2">
                            <div class="col-sm-2"><strong>Kesukaran Bernafas</strong></div>
                            <div class="col-sm-10">
                                <select class="form-select" name="sesak_nafas" <?= $ro_select ?>>
                                    <option value="">Pilih</option>
                                    <option value="ya" <?= val('sesak_nafas', $existing_data) === 'ya' ? 'selected' : '' ?>>Ya</option>
                                    <option value="tidak" <?= val('sesak_nafas', $existing_data) === 'tidak' ? 'selected' : '' ?>>Tidak</option>
                                </select>
                            </div>
                        </div>

                        <!-- Taktil Fremitus -->
                        <div class="row mb-3">
                            <div class="col-sm-2 col-form-label"><strong>Taktil Fremitus</strong></div>
                            <div class="col-sm-10">
                                <textarea name="taktil_fremitus" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('taktil_fremitus',$existing_data) ?></textarea>
                                </div>
                        </div>

                        <!-- Bunyi Perkusi Paru -->
                        <div class="row mb-3">
                            <div class="col-sm-2 col-form-label"><strong>Bunyi Perkusi Paru</strong></div>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="perkusi_paru" value="<?= val('perkusi_paru', $existing_data) ?>" <?= $ro_select ?>>
                            </div>
                        </div>


                        <!-- Frekuensi Nafas (again for "Suara Nafas") -->
                        <div class="row mb-3">
                            <label class="col-sm-2"><strong>Suara Nafas</strong></label>
                            <div class="col-sm-4">
                                <select class="form-select" name="teratur_nafas" style="max-width:200px" <?= $ro_select ?>>
                                    <option value="teratur" <?= val('teratur_nafas', $existing_data) === 'teratur' ? 'selected' : '' ?>>Normal</option>
                                </select>
                            </div>

                            <div class="col-sm-6">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="frekuensi_nafas" value="<?= val('frekuensi_nafas', $existing_data) ?>" <?= $ro_select ?>>
                                    <span class="input-group-text">uraikan</span>
                                </div>
                            </div>
                        </div>

                        <!-- Bunyi Nafas Abnormal -->
                        <div class="row mb-2">
                            <div class="col-sm-2"><strong>Bunyi Nafas Abnormal</strong></div>
                            <div class="col-sm-10">
                                <select class="form-select" name="bunyi_abnormal" <?= $ro_select ?>>
                                    <option value="">Pilih</option>
                                    <option value="wheezing" <?= val('bunyi_abnormal', $existing_data) === 'wheezing' ? 'selected' : '' ?>>Wheezing</option>
                                    <option value="ronchi" <?= val('bunyi_abnormal', $existing_data) === 'ronchi' ? 'selected' : '' ?>>Ronchi</option>
                                </select>
                            </div>
                            </div>
                             <div class="row mb-3">
                            <div class="col-sm-2 col-form-label"><strong>Lainnya</strong></div>
                            <div class="col-sm-10">
                                <textarea name="abnormal" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('abnormal',$existing_data) ?></textarea>
                                </div>
                        </div>

                        <div class="row mb-2">
                            <label class="col-sm-12 text-primary"><strong>k. Jantung</strong></label>
                        </div>

                        <!-- S1 -->
                        <div class="row mb-3">
                            <div class="col-sm-2 col-form-label"><strong>S1</strong></div>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="s1_jantung" value="<?= val('s1_jantung', $existing_data) ?>" <?= $ro_select ?>>
                            </div>
                        </div>

                        <!-- S2 -->
                        <div class="row mb-3">
                            <div class="col-sm-2 col-form-label"><strong>S2</strong></div>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="s2_jantung" value="<?= val('s2_jantung', $existing_data) ?>" <?= $ro_select ?>>
                            </div>
                        </div>

                        <!-- Bunyi Teratur -->
                        <div class="row mb-2">
                            <div class="col-sm-2"><strong>Bunyi Teratur</strong></div>
                            <div class="col-sm-10">
                                <select class="form-select" name="bunyi_jantung"<?= $ro_select ?>>
                                    <option value="">Pilih</option>
                                    <option value="ya" <?= val('bunyi_jantung', $existing_data) === 'ya' ? 'selected' : '' ?>>Ya</option>
                                    <option value="tidak" <?= val('bunyi_jantung', $existing_data) === 'tidak' ? 'selected' : '' ?>>Tidak</option>
                                </select>
                            </div>
                        </div>

                        <!-- Bunyi Tambahan -->
                        <div class="row mb-2">
                            <div class="col-sm-2"><strong>Bunyi Tambahan</strong></div>
                            <div class="col-sm-10">
                                <select class="form-select" name="bunyi" <?= $ro_select ?>>
                                    <option value="">Pilih</option>
                                    <option value="murmur" <?= val('bunyi', $existing_data) === 'murmur' ? 'selected' : '' ?>>Murmur</option>
                                    <option value="tidak" <?= val('bunyi', $existing_data) === 'tidak' ? 'selected' : '' ?>>Tidak</option>
                                </select>
                            </div>
                        </div>


                        <!-- Pulsasi Jantung -->
                        <div class="row mb-3">
                            <div class="col-sm-2 col-form-label"><strong>Pulsasi Jantung</strong></div>
                            <div class="col-sm-10">
                                <textarea name="pulsasi_jantung" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('pulsasi_jantung',$existing_data) ?></textarea>
                                </div>
                        </div>

                        <!-- Irama -->
                        <div class="row mb-2">
                            <div class="col-sm-2"><strong>Irama</strong></div>
                            <div class="col-sm-10">
                                <select class="form-select" name="irama_jantung"<?= $ro_select ?>>
                                    <option value="">Pilih</option>
                                    <option value="teratur" <?= val('irama_jantung', $existing_data) === 'teratur' ? 'selected' : '' ?>>Teratur</option>
                                    <option value="tidak_teratur" <?= val('irama_jantung', $existing_data) === 'tidak_teratur' ? 'selected' : '' ?>>Tidak Teratur</option>
                                </select>
                            </div>
                        </div><!-- Abdomen -->
                        <div class="row mb-2">
                            <label class="col-sm-12 text-primary"><strong>l. Abdomen</strong></label>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-2"><strong>Bentuk</strong>
                            </div>

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="bentuk_abdomen[]" value="datar" id="cb_bentuk_abdomen_datar" <?= $ro_disabled ?> <?= in_array('datar', (array)($existing_data['bentuk_abdomen'] ?? [])) ? 'checked' : '' ?>>
                                    <label class="form-check-label">Datar</label>
                                </div>
                            </div>

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="bentuk_abdomen[]" value="membuncit" id="cb_bentuk_abdomen_membuncit" <?= $ro_disabled ?> <?= in_array('membuncit', (array)($existing_data['bentuk_abdomen'] ?? [])) ? 'checked' : '' ?>>
                                    <label class="form-check-label">Membuncit</label>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="bentuk_abdomen[]" value="cekung" id="cb_bentuk_abdomen_cekung" <?= $ro_disabled ?> <?= in_array('cekung', (array)($existing_data['bentuk_abdomen'] ?? [])) ? 'checked' : '' ?>>
                                    <label class="form-check-label">Cekung</label>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="bentuk_abdomen[]" value="tegang" id="cb_bentuk_abdomen_tegang" <?= $ro_disabled ?> <?= in_array('tegang', (array)($existing_data['bentuk_abdomen'] ?? [])) ? 'checked' : '' ?>>
                                    <label class="form-check-label">Tegang</label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-2"><strong>Keadaan</strong>
                            </div>



                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="keadaan_abdomen[]" value="parut" id="cb_keadaan_abdomen_parut" <?= $ro_disabled ?> <?= in_array('parut', (array)($existing_data['keadaan_abdomen'] ?? [])) ? 'checked' : '' ?>>
                                    <label class="form-check-label">Parut</label>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="keadaan_abdomen[]" value="lesi" id="cb_keadaan_abdomen_lesi" <?= $ro_disabled ?> <?= in_array('lesi', (array)($existing_data['keadaan_abdomen'] ?? [])) ? 'checked' : '' ?>>
                                    <label class="form-check-label">Lesi</label>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="keadaan_abdomen[]" value=" bercak_merah" id="cb_keadaan_abdomen_bercak_merah" <?= $ro_disabled ?> <?= in_array(' bercak_merah', (array)($existing_data['keadaan_abdomen'] ?? [])) ? 'checked' : '' ?>>
                                    <label class="form-check-label"> Bercak Merah</label>
                                </div>
                            </div>
                        </div>

                        <!-- Bising Usus -->
                        <div class="row mb-3 align-items-center">
                            <label class="col-sm-2 col-form-label"><strong>Bising Usus</strong></label>
                            <div class="col-sm-5">
                                <select class="form-select" name="bising_usus"<?= $ro_select ?>>
                                    <option value="">Pilih</option>
                                    <option value="ada" <?= (isset($existing_data['bising_usus']) && $existing_data['bising_usus'] === 'ada') ? 'selected' : '' ?>>Ada</option>
                                    <option value="tidak" <?= (isset($existing_data['bising_usus']) && $existing_data['bising_usus'] === 'tidak') ? 'selected' : '' ?>>Tidak</option>
                                </select>
                            </div>
                            <div class="col-sm-5">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="frekuensi1" value="<?= val('frekuensi1', $existing_data) ?>" <?= $ro_select ?>>
                                    <span class="input-group-text">kali</span>
                                </div>
                            </div>
                        </div>

                        <!-- Benjolan -->
                        <div class="row mb-3 align-items-center">
                            <label class="col-sm-2 col-form-label"><strong>Benjolan</strong></label>
                            <div class="col-sm-5">
                                <select class="form-select" name="benjolan_abdomen" <?= $ro_select ?>>
                                    <option value="">Pilih</option>
                                    <option value="ada" <?= (isset($existing_data['benjolan_abdomen']) && $existing_data['benjolan_abdomen'] === 'ada') ? 'selected' : '' ?>>Ada</option>
                                    <option value="tidak" <?= (isset($existing_data['benjolan_abdomen']) && $existing_data['benjolan_abdomen'] === 'tidak') ? 'selected' : '' ?>>Tidak</option>
                                </select>
                            </div>
                            <div class="col-sm-5">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="letak1" value="<?= val('letak1', $existing_data) ?>" <?= $ro_select ?>>
                                    <span class="input-group-text">letak</span>
                                </div>
                            </div>
                        </div>

                        <!-- Nyeri Tekan -->
                        <div class="row mb-3 align-items-center">
                            <label class="col-sm-2 col-form-label"><strong>Nyeri Tekan</strong></label>
                            <div class="col-sm-5">
                                <select class="form-select" name="nyeri_abdomen" <?= $ro_select ?>>
                                    <option value="">Pilih</option>
                                    <option value="ada" <?= (isset($existing_data['nyeri_abdomen']) && $existing_data['nyeri_abdomen'] === 'ada') ? 'selected' : '' ?>>Ada</option>
                                    <option value="tidak" <?= (isset($existing_data['nyeri_abdomen']) && $existing_data['nyeri_abdomen'] === 'tidak') ? 'selected' : '' ?>>Tidak</option>
                                </select>
                            </div>
                            <div class="col-sm-5">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="frekuensi_tekan" value="<?= val('frekuensi_tekan', $existing_data) ?>" <?= $ro_select ?>>
                                    <span class="input-group-text">letak</span>
                                </div>
                            </div>
                        </div>

                        <!-- Perkusi Abdomen -->
                        <div class="row mb-3">
                            <div class="col-sm-2 col-form-label"><strong>Perkusi Abdomen</strong></div>
                            <div class="col-sm-10">
                                <textarea name="perkusi_abdomen" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('perkusi_abdomen',$existing_data) ?></textarea>
                               </div>
                        </div>

                        <!-- Kelainan -->
                        <div class="row mb-3">
                            <div class="col-sm-2 col-form-label"><strong>Kelainan</strong></div>
                            <div class="col-sm-10">
                                <textarea name="kelainan_abdomen" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('kelainan_abdomen',$existing_data) ?></textarea>
                                </div>
                        </div>
                        <div class="row mb-2">
                            <label class="col-sm-12 text-primary"><strong>m. Genetalia</strong></label>
                        </div>
                        


                        <!-- Kelainan -->
                        <div class="row mb-3">
                            <div class="col-sm-2 col-form-label"><strong>Genetalia</strong></div>
                            <div class="col-sm-10">
                                <textarea name="genetalia" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('genetalia',$existing_data) ?></textarea>
       </div>
                            
                        </div>
                        <div class="row mb-2">
                            <label class="col-sm-12 text-primary"><strong>n. Ekstremitas</strong></label>
                        </div>

                        <!-- Atas -->
                        <div class="row mb-2">
                            <label class="col-sm-12"><strong>1) Atas</strong></label>
                        </div>

                        <!-- Bentuk Simetris -->
                        <div class="row mb-2">
                            <div class="col-sm-2"><strong>Bentuk Simetris</strong></div>
                            <div class="col-sm-10">
                                <select class="form-select" name="atas_simetris" <?= $ro_select ?>>
                                    <option value="">Pilih</option>
                                    <option value="ya" <?= val('atas_simetris', $existing_data) === 'ya' ? 'selected' : '' ?>>Ya</option>
                                    <option value="tidak" <?= val('atas_simetris', $existing_data) === 'tidak' ? 'selected' : '' ?>>Tidak</option>
                                </select>
                            </div>
                        </div>

                        <!-- Sensasi Halus -->
                        <div class="row mb-2">
                            <div class="col-sm-2"><strong>Sensasi Halus</strong></div>
                            <div class="col-sm-10">
                                <select class="form-select" name="sensasi_halus"  <?= $ro_select ?>>
                                    <option value="">Pilih</option>
                                    <option value="ada" <?= val('sensasi_halus', $existing_data) === 'ada' ? 'selected' : '' ?>>Ada</option>
                                    <option value="tidak" <?= val('sensasi_halus', $existing_data) === 'tidak' ? 'selected' : '' ?>>Tidak</option>
                                </select>
                            </div>
                        </div>

                        <!-- Sensasi Tajam -->
                        <div class="row mb-2">
                            <div class="col-sm-2"><strong>Sensasi Tajam</strong></div>
                            <div class="col-sm-10">
                                <select class="form-select" name="sensasi_tajam" <?= $ro_select ?>>
                                    <option value="">Pilih</option>
                                    <option value="ada" <?= val('sensasi_tajam', $existing_data) === 'ada' ? 'selected' : '' ?>>Ada</option>
                                    <option value="tidak" <?= val('sensasi_tajam', $existing_data) === 'tidak' ? 'selected' : '' ?>>Tidak</option>
                                </select>
                            </div>
                        </div>

                        <!-- Sensasi Panas -->
                        <div class="row mb-2">
                            <div class="col-sm-2"><strong>Sensasi Panas</strong></div>
                            <div class="col-sm-10">
                                <select class="form-select" name="sensasi_panas"  <?= $ro_select ?>>
                                    <option value="">Pilih</option>
                                    <option value="ada" <?= val('sensasi_panas', $existing_data) === 'ada' ? 'selected' : '' ?>>Ada</option>
                                    <option value="tidak" <?= val('sensasi_panas', $existing_data) === 'tidak' ? 'selected' : '' ?>>Tidak</option>
                                </select>
                            </div>
                        </div>

                        <!-- Sensasi Dingin -->
                        <div class="row mb-2">
                            <div class="col-sm-2"><strong>Sensasi Dingin</strong></div>
                            <div class="col-sm-10">
                                <select class="form-select" name="sensasi_dingin" <?= $ro_select ?>>
                                    <option value="">Pilih</option>
                                    <option value="ada" <?= val('sensasi_dingin', $existing_data) === 'ada' ? 'selected' : '' ?>>Ada</option>
                                    <option value="tidak" <?= val('sensasi_dingin', $existing_data) === 'tidak' ? 'selected' : '' ?>>Tidak</option>
                                </select>
                            </div>
                        </div>

                        <!-- Gerakan ROM -->
                        <div class="row mb-2">
                            <div class="col-sm-2"><strong>Gerakan ROM</strong></div>
                            <div class="col-sm-10">
                                <select class="form-select" name="rom_atas" <?= $ro_select ?>>
                                    <option value="">Pilih</option>
                                    <option value="dapat" <?= val('rom_atas', $existing_data) === 'dapat' ? 'selected' : '' ?>>Dapat</option>
                                    <option value="tidak" <?= val('rom_atas', $existing_data) === 'tidak' ? 'selected' : '' ?>>Tidak</option>
                                </select>
                            </div>
                        </div>

                        <!-- Refleks Bisep -->
                        <div class="row mb-2">
                            <div class="col-sm-2"><strong>Refleks Bisep</strong></div>
                            <div class="col-sm-10">
                                <select class="form-select" name="refleks_bisep"  <?= $ro_select ?>>
                                    <option value="">Pilih</option>
                                    <option value="ada" <?= val('refleks_bisep', $existing_data) === 'ada' ? 'selected' : '' ?>>Ada</option>
                                    <option value="tidak" <?= val('refleks_bisep', $existing_data) === 'tidak' ? 'selected' : '' ?>>Tidak</option>
                                </select>
                            </div>
                        </div>

                        <!-- Refleks Trisep -->
                        <div class="row mb-2">
                            <div class="col-sm-2"><strong>Refleks Trisep</strong></div>
                            <div class="col-sm-10">
                                <select class="form-select" name="refleks_trisep" <?= $ro_select ?>>
                                    <option value="">Pilih</option>
                                    <option value="ada" <?= val('refleks_trisep', $existing_data) === 'ada' ? 'selected' : '' ?>>Ada</option>
                                    <option value="tidak" <?= val('refleks_trisep', $existing_data) === 'tidak' ? 'selected' : '' ?>>Tidak</option>
                                </select>
                            </div>
                        </div>

                        <!-- Pembengkakan -->
                        <div class="row mb-2">
                            <div class="col-sm-2"><strong>Pembengkakan</strong></div>
                            <div class="col-sm-10">
                                <select class="form-select" name="pembengkakan5" <?= $ro_select ?>>
                                    <option value="">Pilih</option>
                                    <option value="ya" <?= val('pembengkakan5', $existing_data) === 'ya' ? 'selected' : '' ?>>Ya</option>
                                    <option value="tidak" <?= val('pembengkakan5', $existing_data) === 'tidak' ? 'selected' : '' ?>>Tidak</option>
                                </select>
                            </div>
                        </div>

                        <!-- Kelembaban -->
                        <div class="row mb-2">
                            <div class="col-sm-2"><strong>Kelembaban</strong></div>
                            <div class="col-sm-10">
                                <select class="form-select" name="kelembaban" <?= $ro_select ?>>
                                    <option value="">Pilih</option>
                                    <option value="lembab" <?= val('kelembaban', $existing_data) === 'lembab' ? 'selected' : '' ?>>Lembab</option>
                                    <option value="kering" <?= val('kelembaban', $existing_data) === 'kering' ? 'selected' : '' ?>>Kering</option>
                                </select>
                            </div>
                        </div>

                        <!-- Temperatur -->
                        <div class="row mb-2">
                            <div class="col-sm-2"><strong>Temperatur</strong></div>
                            <div class="col-sm-10">
                                <select class="form-select" name="temperatur"  <?= $ro_select ?>>
                                    <option value="">Pilih</option>
                                    <option value="panas" <?= val('temperatur', $existing_data) === 'panas' ? 'selected' : '' ?>>Panas</option>
                                    <option value="dingin" <?= val('temperatur', $existing_data) === 'dingin' ? 'selected' : '' ?>>Dingin</option>
                                </select>
                            </div>
                        </div>

                        <!-- Kekuatan Otot Tangan -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Kekuatan Otot Tangan</strong></label>
                            <div class="col-sm-10">
                                <div class="row">
                                    <!-- Kanan -->
                                    <div class="col-md-6 d-flex align-items-center">
                                        <label class="me-2"><strong>Kanan</strong></label>
                                        <input type="text" class="form-control" name="kanan1" value="<?= val('kanan1', $existing_data) ?>" <?= $ro_select ?>>
                                    </div>

                                    <!-- Kiri -->
                                    <div class="col-md-6 d-flex align-items-center">
                                        <label class="me-2"><strong>Kiri</strong></label>
                                        <input type="text" class="form-control" name="kiri1" value="<?= val('kiri1', $existing_data) ?>" <?= $ro_select ?>>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Kelainan -->
                        <div class="row mb-3">
                            <div class="col-sm-2 col-form-label"><strong>Kelainan</strong></div>
                            <div class="col-sm-10">
                                <textarea name="kelainan_genetalia1" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('kelainan_genetalia1',$existing_data) ?></textarea>
                                </div>
                        </div>
                        <div class="row mb-3">
                            <div class="row mb-2">
                                <label class="col-sm-12"><strong>2) Bawah</strong></label>
                            </div>

                            <div class="row mb-2">
                                <div class="col-sm-2"><strong>Bentuk Simetris</strong></div>
                                <div class="col-sm-10">
                                    <select class="form-select" name="bawah_simetris" <?= $ro_select ?>>
                                        <option value="">Pilih</option>
                                        <option value="ya" <?= val('bawah_simetris', $existing_data) === 'ya' ? 'selected' : '' ?>>Ya</option>
                                        <option value="tidak" <?= val('bawah_simetris', $existing_data) === 'tidak' ? 'selected' : '' ?>>Tidak</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-sm-2"><strong>Sensasi Halus</strong></div>
                                <div class="col-sm-10">
                                    <select class="form-select" name="sensasi_bawah" <?= $ro_select ?>>
                                        <option value="">Pilih</option>
                                        <option value="ada" <?= val('sensasi_bawah', $existing_data) === 'ada' ? 'selected' : '' ?>>Ada</option>
                                        <option value="tidak" <?= val('sensasi_bawah', $existing_data) === 'tidak' ? 'selected' : '' ?>>Tidak</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-sm-2"><strong>Sensasi Tajam</strong></div>
                                <div class="col-sm-10">
                                    <select class="form-select" name="bawah_tajam" <?= $ro_select ?>>
                                        <option value="">Pilih</option>
                                        <option value="ada" <?= val('sensasi_tajam', $existing_data) === 'ada' ? 'selected' : '' ?>>Ada</option>
                                        <option value="tidak" <?= val('sensasi_tajam', $existing_data) === 'tidak' ? 'selected' : '' ?>>Tidak</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-sm-2"><strong>Sensasi Panas</strong></div>
                                <div class="col-sm-10">
                                    <select class="form-select" name="sensasi_panasb"<?= $ro_select ?>>
                                        <option value="">Pilih</option>
                                        <option value="ada" <?= val('sensasi_panasb', $existing_data) === 'ada' ? 'selected' : '' ?>>Ada</option>
                                        <option value="tidak" <?= val('sensasi_panasb', $existing_data) === 'tidak' ? 'selected' : '' ?>>Tidak</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-sm-2"><strong>Sensasi Dingin</strong></div>
                                <div class="col-sm-10">
                                    <select class="form-select" name="sensasi_dinginb" <?= $ro_select ?>>
                                        <option value="">Pilih</option>
                                        <option value="ada" <?= val('sensasi_dinginb', $existing_data) === 'ada' ? 'selected' : '' ?>>Ada</option>
                                        <option value="tidak" <?= val('sensasi_dinginb', $existing_data) === 'tidak' ? 'selected' : '' ?>>Tidak</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-sm-2"><strong>Gerakan ROM</strong></div>
                                <div class="col-sm-10">
                                    <select class="form-select" name="rom_bawah" <?= $ro_select ?>>
                                        <option value="">Pilih</option>
                                        <option value="dapat" <?= val('rom_bawah', $existing_data) === 'dapat' ? 'selected' : '' ?>>Dapat</option>
                                        <option value="tidak" <?= val('rom_bawah', $existing_data) === 'tidak' ? 'selected' : '' ?>>Tidak</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-sm-2"><strong>Refleks Babinski</strong></div>
                                <div class="col-sm-10">
                                    <select class="form-select" name="refleks_babinski1" <?= $ro_select ?>>
                                        <option value="">Pilih</option>
                                        <option value="ada" <?= val('refleks_babinski1', $existing_data) === 'ada' ? 'selected' : '' ?>>Ada</option>
                                        <option value="tidak" <?= val('refleks_babinski1', $existing_data) === 'tidak' ? 'selected' : '' ?>>Tidak</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-sm-2"><strong>Pembengkakan</strong></div>
                                <div class="col-sm-10">
                                    <select class="form-select" name="pembengkakan3"  <?= $ro_select ?>>
                                        <option value="">Pilih</option>
                                        <option value="ya" <?= val('pembengkakan3', $existing_data) === 'ya' ? 'selected' : '' ?>>Ya</option>
                                        <option value="tidak" <?= val('pembengkakan3', $existing_data) === 'tidak' ? 'selected' : '' ?>>Tidak</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-sm-2"><strong>Varises</strong></div>
                                <div class="col-sm-10">
                                    <select class="form-select" name="varises1"<?= $ro_select ?>>
                                        <option value="">Pilih</option>
                                        <option value="ada" <?= val('varises1', $existing_data) === 'ada' ? 'selected' : '' ?>>Ada</option>
                                        <option value="tidak" <?= val('varises1', $existing_data) === 'tidak' ? 'selected' : '' ?>>Tidak</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-sm-2"><strong>Kelembaban</strong></div>
                                <div class="col-sm-10">
                                    <select class="form-select" name="kelembaban3"  <?= $ro_select ?>>
                                        <option value="">Pilih</option>
                                        <option value="lembab" <?= val('kelembaban3', $existing_data) === 'lembab' ? 'selected' : '' ?>>Lembab</option>
                                        <option value="kering" <?= val('kelembaban3', $existing_data) === 'kering' ? 'selected' : '' ?>>Kering</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-sm-2"><strong>Temperatur</strong></div>
                                <div class="col-sm-10">
                                    <select class="form-select" name="temperaturb" <?= $ro_select ?>>
                                        <option value="">Pilih</option>
                                        <option value="panas" <?= val('temperaturb', $existing_data) === 'panas' ? 'selected' : '' ?>>Panas</option>
                                        <option value="dingin" <?= val('temperaturb', $existing_data) === 'dingin' ? 'selected' : '' ?>>Dingin</option>
                                    </select>
                                </div>
                            </div>
                            <!-- Kekuatan Otot Kaki -->
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label"><strong>Kekuatan Otot Kaki</strong></label>
                                <div class="col-sm-10">
                                    <div class="row">
                                        <!-- Kanan -->
                                        <div class="col-md-6 d-flex align-items-center">
                                            <label class="me-2"><strong>Kanan</strong></label>
                                            <input type="text" class="form-control" name="kanankaki" value="<?= val('kanankaki', $existing_data) ?>" <?= $ro_select ?>>
                                        </div>

                                        <!-- Kiri -->
                                        <div class="col-md-6 d-flex align-items-center">
                                            <label class="me-2"><strong>Kiri</strong></label>
                                            <input type="text" class="form-control" name="kirikaki" value="<?= val('kirikaki', $existing_data) ?>" <?= $ro_select ?>>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Kelainan -->
                            <div class="row mb-3">
                                <div class="col-sm-2 col-form-label"><strong>Kelainan</strong></div>
                                <div class="col-sm-10">
                                    <textarea name="kelainan_genetalia2" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('kelainan_genetalia2',$existing_data) ?></textarea>
                                    </div>
                            </div>
                            <!-- Kulit -->
                            <div class="row mb-2">
                                <label class="col-sm-12 text-primary"><strong>o. Kulit</strong></label>
                            </div>

                            <!-- Warna -->
                            <div class="row mb-3">
                                <div class="col-sm-2 col-form-label"><strong>Warna</strong></div>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="warna_kulit" value="<?= val('warna_kulit', $existing_data) ?>" <?= $ro_select ?>>
                                </div>
                            </div>

                            <!-- Turgor -->
                            <div class="row mb-2">
                                <div class="col-sm-2"><strong>Turgor</strong></div>
                                <div class="col-sm-10">
                                    <select class="form-select" name="turgor_kulit" <?= $ro_select ?>>
                                        <option value="">Pilih</option>
                                        <option value="elastis" <?= val('turgor_kulit', $existing_data) === 'elastis' ? 'selected' : '' ?>>Elastis</option>
                                        <option value="menurun" <?= val('turgor_kulit', $existing_data) === 'menurun' ? 'selected' : '' ?>>Tidak</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Kelembaban -->
                            <div class="row mb-2">
                                <div class="col-sm-2"><strong>Kelembaban</strong></div>
                                <div class="col-sm-10">
                                    <select class="form-select" name="kelembaban2" <?= $ro_select ?>>
                                        <option value="">Pilih</option>
                                        <option value="lembab" <?= val('kelembaban2', $existing_data) === 'lembab' ? 'selected' : '' ?>>Lembab</option>
                                        <option value="kering" <?= val('kelembaban2', $existing_data) === 'kering' ? 'selected' : '' ?>>Kering</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Edema -->
                            <div class="row mb-3 align-items-center">
                                <label class="col-sm-2 col-form-label"><strong>Edema</strong></label>
                                <div class="col-sm-5">
                                    <select class="form-select" name="edema_kulit"  <?= $ro_select ?>>
                                        <option value="">Pilih</option>
                                        <option value="ada" <?= val('edema_kulit', $existing_data) === 'ada' ? 'selected' : '' ?>>Ada</option>
                                        <option value="tidak" <?= val('edema_kulit', $existing_data) === 'tidak' ? 'selected' : '' ?>>Tidak</option>
                                    </select>
                                </div>
                                <div class="col-sm-5">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="pada_daerah" value="<?= val('pada_daerah', $existing_data) ?>" <?= $ro_select ?>>
                                        <span class="input-group-text">Pada Daerah</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Luka -->
                            <div class="row mb-3 align-items-center">
                                <label class="col-sm-2 col-form-label"><strong>Luka</strong></label>
                                <div class="col-sm-5">
                                    <select class="form-select" name="luka_kulit1" <?= $ro_select ?>>
                                        <option value="">Pilih</option>
                                        <option value="ada" <?= val('luka_kulit1', $existing_data) === 'ada' ? 'selected' : '' ?>>Ada</option>
                                        <option value="tidak" <?= val('luka_kulit1', $existing_data) === 'tidak' ? 'selected' : '' ?>>Tidak</option>
                                    </select>
                                </div>
                                <div class="col-sm-5">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="pada_daerah1" value="<?= val('pada_daerah1', $existing_data) ?>" <?= $ro_select ?>>
                                        <span class="input-group-text">Pada Daerah</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Karakteristik Luka -->
                            <div class="row mb-3">
                                <div class="col-sm-2 col-form-label"><strong>Karakteristik Luka</strong></div>
                                <div class="col-sm-10">
                                    <textarea name="karakteristik_luka" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('karakteristik_luka',$existing_data) ?></textarea>
                                   </div>
                            </div>

                            <!-- Tekstur -->
                            <div class="row mb-2">
                                <div class="col-sm-2"><strong>Tekstur</strong></div>
                                <div class="col-sm-10">
                                    <select class="form-select" name="tekstur_kulit" <?= $ro_select ?>>
                                        <option value="">Pilih</option>
                                        <option value="licin" <?= val('tekstur_kulit', $existing_data) === 'licin' ? 'selected' : '' ?>>Licin</option>
                                        <option value="keriput" <?= val('tekstur_kulit', $existing_data) === 'keriput' ? 'selected' : '' ?>>Keriput</option>
                                        <option value="kasar" <?= val('tekstur_kulit', $existing_data) === 'kasar' ? 'selected' : '' ?>>Kasar</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Kelainan -->
                            <div class="row mb-3">
                                <div class="col-sm-2 col-form-label"><strong>Kelainan</strong></div>
                                <div class="col-sm-10">
                                    <textarea name="kelainan_kulit" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('kelainan_kulit',$existing_data) ?></textarea>
                                    </div>
                            </div>
                            <div class="row mb-2">
                                <label class="col-sm-12 text-primary"><strong>p. Kuku</strong></label>
                            </div>

                            <!-- Clubbing Finger -->
                            <div class="row mb-2">
                                <div class="col-sm-2"><strong>Clubbing Finger</strong></div>
                                <div class="col-sm-10">
                                    <select class="form-select" name="clubbing_finger" <?= $ro_select ?>>
                                        <option value="">Pilih</option>
                                        <option value="ya" <?= val('clubbing_finger', $existing_data) === 'ya' ? 'selected' : '' ?>>Ya</option>
                                        <option value="tidak" <?= val('clubbing_finger', $existing_data) === 'tidak' ? 'selected' : '' ?>>Tidak</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Capillary Refill Time -->
                            <div class="row mb-3">
                                <div class="col-sm-2 col-form-label"><strong>Capillary Refill Time</strong></div>
                                <div class="col-sm-10">
                                    <textarea name="capillary_refill_time" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('capillary_refill_time',$existing_data) ?></textarea>
                                    </div>
                            </div>

                            <!-- Keadaan Kuku -->
                            <div class="row mb-3">
                                <div class="col-sm-2 col-form-label"><strong>Keadaan</strong></div>
                                <div class="col-sm-10">
                                    <textarea name="keadaan_kuku" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('keadaan_kuku',$existing_data) ?></textarea>
                                    </div>
                            </div>
                            <div class="row mb-2">
                                <label class="col-sm-12 text-primary"><strong>q. Status Neurologi</strong></label>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-2 col-form-label">
                                    <strong>Status Neurologi</strong>
                                </div>
                                <div class="col-sm-10">
                                    <textarea name="neurologi" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('neurologi',$existing_data) ?></textarea>
                                    </div>
                            </div>
                            <!-- TOMBOL SUBMIT -->
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
    </div>
    </div>
    </div>
    <?php include dirname(__DIR__, 2) . '/partials/footer_form.php'; ?>
    </div>
    </div>
    </section>
</main>