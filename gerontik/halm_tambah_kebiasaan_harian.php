<?php
$form_id       = 18;
$section_name  = 'kebiasaan_harian';
$section_label = 'Pola Kebiasaan Harian';
include dirname(__DIR__) . '/partials/init_section.php';

$text_fields = [
    'frekuensi_makan' => 'Frekuensi Makan',
    'nafsu_makan' => 'Nafsu Makan',
    'jenis_makanan' => 'Jenis Makanan',
    'makanan_tidak_disukai' => 'Makanan yang Tidak Disukai',
    'kebiasaan_sebelum_makan' => 'Kebiasaan / Ritual Sebelum Makan',
    'berat_tinggi_badan' => 'Berat Badan / Tinggi Badan',
    'jenis_minuman' => 'Jenis Minuman',
    'jumlah_cairan' => 'Jumlah Cairan yang Dikonsumsi',
    'warna_bak' => 'Warna',
    'keluhan_bak' => 'Keluhan yang Berhubungan dengan BAK',
    'frekuensi_bab' => 'Frekuensi',
    'bau_bab' => 'Bau',
    'warna_bab' => 'Warna',
    'konsistensi_bab' => 'Konsistensi',
    'keluhan_bab' => 'Keluhan yang Berhubungan dengan Defekasi',
    'pengalaman_laksatif' => 'Pengalaman Memakai Laksatif',
    'frekuensi_mandi' => 'Frekuensi',
    'frekuensi_hygiene_oral' => 'Frekuensi',
    'frekuensi_cuci_rambut' => 'Frekuensi',
    'frekuensi_gunting_kuku' => 'Frekuensi',
    'lama_tidur' => 'Lama Tidur (Jam/Hari)',
    'jenis_frekuensi_olahraga' => 'Jenis dan Frekuensi',
    'kegiatan_waktu_luang' => 'Kegiatan Waktu Luang',
];

$radio_fields = [
    'kesulitan_makan_minum' => 'Kesulitan Makan dan Minum',
    'makan_minum_bantu' => 'Untuk Makan dan Minum',
    'dibantu_bak' => 'Dibantu',
    'mandiri_bak' => 'Mandiri',
    'dibantu_bab' => 'Dibantu',
    'mandiri_bab' => 'Mandiri',
    'dibantu_mandi' => 'Dibantu',
    'mandiri_mandi' => 'Mandiri',
    'dibantu_hygiene_oral' => 'Dibantu',
    'mandiri_hygiene_oral' => 'Mandiri',
    'dibantu_cuci_rambut' => 'Dibantu',
    'mandiri_cuci_rambut' => 'Mandiri',
    'dibantu_gunting_kuku' => 'Dibantu',
    'mandiri_gunting_kuku' => 'Mandiri',
    'kesulitan_tidur' => 'Kesulitan / Gangguan Tidur',
    'tidur_siang' => 'Tidur Siang',
    'olahraga_ringan' => 'Olahraga Ringan',
    'keluhan_aktivitas' => 'Keluhan Beraktivitas',
    'kesulitan_pergerakan' => 'Kesulitan Pergerakan',
    'sesak_nafas' => 'Sesak Nafas Setelah Aktivitas',
];

$field_order = array_merge(array_keys($text_fields), array_keys($radio_fields));

function render_text_row($field, $label, $existing_data, $ro)
{
?>
    <div class="row mb-3 align-items-start">
        <label class="col-sm-2 col-form-label"><strong><?= htmlspecialchars($label) ?></strong></label>
        <div class="col-sm-9">
            <input type="text" class="form-control" name="<?= htmlspecialchars($field) ?>" value="<?= htmlspecialchars(val($field, $existing_data)) ?>" <?= $ro ?>>
        </div>
    </div>
<?php
}

function render_radio_row($field, $label, $existing_data, $ro_disabled)
{
    $current = trim((string) val($field, $existing_data));
?>
    <div class="row mb-3 align-items-start">
        <label class="col-sm-2 col-form-label"><strong><?= htmlspecialchars($label) ?></strong></label>
        <div class="col-sm-9">
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="<?= htmlspecialchars($field) ?>" id="<?= htmlspecialchars($field) ?>_ya" value="Y" <?= $ro_disabled ?> <?= $current === 'Y' ? 'checked' : '' ?>>
                        <label class="form-check-label" for="<?= htmlspecialchars($field) ?>_ya">Ya</label>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="<?= htmlspecialchars($field) ?>" id="<?= htmlspecialchars($field) ?>_tidak" value="T" <?= $ro_disabled ?> <?= $current === 'T' ? 'checked' : '' ?>>
                        <label class="form-check-label" for="<?= htmlspecialchars($field) ?>_tidak">Tidak</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $level === 'Mahasiswa') {
    if (isLocked($submission)) redirectWithMessage($_SERVER['REQUEST_URI'], 'error', 'Data tidak dapat diubah karena sedang dalam proses review.');
    $data = [];
    foreach ($field_order as $field) {
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
                    <h5 class="card-title"><strong>4. Pola Kebiasaan Sehari-Hari</strong></h5>

                    <div class="row mb-2">
                        <label class="col-sm-12 col-form-label text-primary"><strong>1. Nutrisi dan Cairan</strong></label>
                    </div>

                    <?php
                    render_text_row('frekuensi_makan', $text_fields['frekuensi_makan'], $existing_data, $ro);
                    render_text_row('nafsu_makan', $text_fields['nafsu_makan'], $existing_data, $ro);
                    render_text_row('jenis_makanan', $text_fields['jenis_makanan'], $existing_data, $ro);
                    render_text_row('makanan_tidak_disukai', $text_fields['makanan_tidak_disukai'], $existing_data, $ro);
                    render_text_row('kebiasaan_sebelum_makan', $text_fields['kebiasaan_sebelum_makan'], $existing_data, $ro);
                    render_text_row('berat_tinggi_badan', $text_fields['berat_tinggi_badan'], $existing_data, $ro);
                    render_text_row('jenis_minuman', $text_fields['jenis_minuman'], $existing_data, $ro);
                    render_text_row('jumlah_cairan', $text_fields['jumlah_cairan'], $existing_data, $ro);
                    render_radio_row('kesulitan_makan_minum', $radio_fields['kesulitan_makan_minum'], $existing_data, $ro_disabled);
                    render_radio_row('makan_minum_bantu', $radio_fields['makan_minum_bantu'], $existing_data, $ro_disabled);
                    ?>

                    <div class="row mb-2">
                        <label class="col-sm-12 col-form-label text-primary"><strong>2. Eliminasi</strong></label>
                    </div>

                    <div class="row mb-2">
                        <label class="col-sm-12 col-form-label text-info"><strong>a. Berkemih (BAK)</strong></label>
                    </div>

                    <?php
                    render_text_row('warna_bak', $text_fields['warna_bak'], $existing_data, $ro);
                    render_text_row('keluhan_bak', $text_fields['keluhan_bak'], $existing_data, $ro);
                    render_radio_row('dibantu_bak', $radio_fields['dibantu_bak'], $existing_data, $ro_disabled);
                    render_radio_row('mandiri_bak', $radio_fields['mandiri_bak'], $existing_data, $ro_disabled);
                    ?>

                    <div class="row mb-2">
                        <label class="col-sm-12 col-form-label text-info"><strong>b. Defekasi (BAB)</strong></label>
                    </div>

                    <?php
                    render_text_row('frekuensi_bab', $text_fields['frekuensi_bab'], $existing_data, $ro);
                    render_text_row('bau_bab', $text_fields['bau_bab'], $existing_data, $ro);
                    render_text_row('warna_bab', $text_fields['warna_bab'], $existing_data, $ro);
                    render_text_row('konsistensi_bab', $text_fields['konsistensi_bab'], $existing_data, $ro);
                    render_text_row('keluhan_bab', $text_fields['keluhan_bab'], $existing_data, $ro);
                    render_text_row('pengalaman_laksatif', $text_fields['pengalaman_laksatif'], $existing_data, $ro);
                    render_radio_row('dibantu_bab', $radio_fields['dibantu_bab'], $existing_data, $ro_disabled);
                    render_radio_row('mandiri_bab', $radio_fields['mandiri_bab'], $existing_data, $ro_disabled);
                    ?>

                    <div class="row mb-2">
                        <label class="col-sm-12 col-form-label text-primary"><strong>3. Hygiene Personal</strong></label>
                    </div>

                    <div class="row mb-2">
                        <label class="col-sm-12 col-form-label text-info"><strong>a. Mandi</strong></label>
                    </div>

                    <?php
                    render_text_row('frekuensi_mandi', $text_fields['frekuensi_mandi'], $existing_data, $ro);
                    render_radio_row('dibantu_mandi', $radio_fields['dibantu_mandi'], $existing_data, $ro_disabled);
                    render_radio_row('mandiri_mandi', $radio_fields['mandiri_mandi'], $existing_data, $ro_disabled);
                    ?>

                    <div class="row mb-2">
                        <label class="col-sm-12 col-form-label text-info"><strong>b. Hygiene Oral</strong></label>
                    </div>

                    <?php
                    render_text_row('frekuensi_hygiene_oral', $text_fields['frekuensi_hygiene_oral'], $existing_data, $ro);
                    render_radio_row('dibantu_hygiene_oral', $radio_fields['dibantu_hygiene_oral'], $existing_data, $ro_disabled);
                    render_radio_row('mandiri_hygiene_oral', $radio_fields['mandiri_hygiene_oral'], $existing_data, $ro_disabled);
                    ?>

                    <div class="row mb-2">
                        <label class="col-sm-12 col-form-label text-info"><strong>c. Cuci Rambut</strong></label>
                    </div>

                    <?php
                    render_text_row('frekuensi_cuci_rambut', $text_fields['frekuensi_cuci_rambut'], $existing_data, $ro);
                    render_radio_row('dibantu_cuci_rambut', $radio_fields['dibantu_cuci_rambut'], $existing_data, $ro_disabled);
                    render_radio_row('mandiri_cuci_rambut', $radio_fields['mandiri_cuci_rambut'], $existing_data, $ro_disabled);
                    ?>

                    <div class="row mb-2">
                        <label class="col-sm-12 col-form-label text-info"><strong>d. Gunting Kuku</strong></label>
                    </div>

                    <?php
                    render_text_row('frekuensi_gunting_kuku', $text_fields['frekuensi_gunting_kuku'], $existing_data, $ro);
                    render_radio_row('dibantu_gunting_kuku', $radio_fields['dibantu_gunting_kuku'], $existing_data, $ro_disabled);
                    render_radio_row('mandiri_gunting_kuku', $radio_fields['mandiri_gunting_kuku'], $existing_data, $ro_disabled);
                    ?>

                    <div class="row mb-2">
                        <label class="col-sm-12 col-form-label text-primary"><strong>4. Istirahat dan Tidur</strong></label>
                    </div>

                    <?php
                    render_text_row('lama_tidur', $text_fields['lama_tidur'], $existing_data, $ro);
                    render_radio_row('kesulitan_tidur', $radio_fields['kesulitan_tidur'], $existing_data, $ro_disabled);
                    render_radio_row('tidur_siang', $radio_fields['tidur_siang'], $existing_data, $ro_disabled);
                    ?>

                    <div class="row mb-2">
                        <label class="col-sm-12 col-form-label text-primary"><strong>5. Aktivitas dan Latihan</strong></label>
                    </div>

                    <?php
                    render_radio_row('olahraga_ringan', $radio_fields['olahraga_ringan'], $existing_data, $ro_disabled);
                    render_text_row('jenis_frekuensi_olahraga', $text_fields['jenis_frekuensi_olahraga'], $existing_data, $ro);
                    render_text_row('kegiatan_waktu_luang', $text_fields['kegiatan_waktu_luang'], $existing_data, $ro);
                    render_radio_row('keluhan_aktivitas', $radio_fields['keluhan_aktivitas'], $existing_data, $ro_disabled);
                    render_radio_row('kesulitan_pergerakan', $radio_fields['kesulitan_pergerakan'], $existing_data, $ro_disabled);
                    render_radio_row('sesak_nafas', $radio_fields['sesak_nafas'], $existing_data, $ro_disabled);
                    ?>

                    <?php if (!$is_dosen): ?>
                        <div class="row mb-3">
                            <div class="col-sm-9 offset-sm-2 text-end"><button type="submit" class="btn btn-primary">Simpan Data</button></div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </form>
        <?php include dirname(__DIR__) . '/partials/footer_form.php'; ?>
    </section>
</main>