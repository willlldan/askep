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
    'jumlah_cairan' => 'Jumlah Cairan yang Dikonsumsi Perhari',
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
    'lama_tidur' => 'Lama Tidur (Jumlah Jam/Hari)',
    'jenis_frekuensi_olahraga' => 'Jenis dan Frekuensi',
    'kegiatan_waktu_luang' => 'Kegiatan Waktu Luang',
];

$radio_fields = [
    'kesulitan_makan_minum' => 'Kesulitan Makan dan Minum',
    'makan_minum_bantu' => 'Bantuan Untuk Makan dan Minum',
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

                    <?php
// Daftar field yang ingin dijadikan textarea
$fields = [
    'frekuensi_makan' => 'Frekuensi Makan',
    'nafsu_makan' => 'Nafsu Makan',
    'jenis_makanan' => 'Jenis Makanan',
    'makanan_tidak_disukai' => 'Makanan Tidak Disukai',
    'kebiasaan_sebelum_makan' => 'Kebiasaan Sebelum Makan',
    'berat_tinggi_badan' => 'Berat/Tinggi Badan',
    'jenis_minuman' => 'Jenis Minuman',
    'jumlah_cairan' => 'Jumlah Cairan'
];

foreach ($fields as $name => $label): ?>
    <div class="row mb-3">
        <label class="col-sm-2 col-form-label"><strong><?= $label ?></strong></label>
        <div class="col-sm-9">
            <textarea name="<?= $name ?>" 
                      class="form-control" 
                      rows="1" 
                      style="overflow:hidden; resize:none;" 
                      oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" 
                      <?= $ro ?>><?= val($name, $existing_data) ?></textarea>
        </div>
    </div>
<?php endforeach;

// Untuk radio tetap gunakan fungsi aslinya
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
$bak_fields = [
    ['name' => 'warna_bak', 'label' => 'Warna BAK'],
    ['name' => 'keluhan_bak', 'label' => 'Keluhan BAK']
];
foreach ($bak_fields as $field): ?>
    <div class="row mb-3">
        <label class="col-sm-2 col-form-label"><strong><?= $field['label'] ?></strong></label>
        <div class="col-sm-9">
            <textarea name="<?= $field['name'] ?>" class="form-control" rows="1" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val($field['name'], $existing_data) ?></textarea>
        </div>
    </div>
<?php endforeach;

render_radio_row('dibantu_bak', $radio_fields['dibantu_bak'], $existing_data, $ro_disabled);
render_radio_row('mandiri_bak', $radio_fields['mandiri_bak'], $existing_data, $ro_disabled);
?>

<div class="row mb-2">
    <label class="col-sm-12 col-form-label text-info"><strong>b. Defekasi (BAB)</strong></label>
</div>

<?php
$bab_fields = [
    ['name' => 'frekuensi_bab', 'label' => 'Frekuensi BAB'],
    ['name' => 'bau_bab', 'label' => 'Bau BAB'],
    ['name' => 'warna_bab', 'label' => 'Warna BAB'],
    ['name' => 'konsistensi_bab', 'label' => 'Konsistensi BAB'],
    ['name' => 'keluhan_bab', 'label' => 'Keluhan BAB'],
    ['name' => 'pengalaman_laksatif', 'label' => 'Pengalaman Laksatif']
];
foreach ($bab_fields as $field): ?>
    <div class="row mb-3">
        <label class="col-sm-2 col-form-label"><strong><?= $field['label'] ?></strong></label>
        <div class="col-sm-9">
            <textarea name="<?= $field['name'] ?>" class="form-control" rows="1" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val($field['name'], $existing_data) ?></textarea>
        </div>
    </div>
<?php endforeach;

render_radio_row('dibantu_bab', $radio_fields['dibantu_bab'], $existing_data, $ro_disabled);
render_radio_row('mandiri_bab', $radio_fields['mandiri_bab'], $existing_data, $ro_disabled);
?>

                    <div class="row mb-2">
    <label class="col-sm-12 col-form-label text-primary"><strong>3. Personal Hygiene</strong></label>
</div>

<?php
// Definisi data untuk perulangan agar kode lebih rapi
$hygiene_data = [
    ['section' => 'Mandi', 'name' => 'frekuensi_mandi', 'label' => 'Frekuensi Mandi', 'radio_dibantu' => 'dibantu_mandi', 'radio_mandiri' => 'mandiri_mandi'],
    ['section' => 'Oral Hygiene', 'name' => 'frekuensi_hygiene_oral', 'label' => 'Frekuensi Oral Hygiene', 'radio_dibantu' => 'dibantu_hygiene_oral', 'radio_mandiri' => 'mandiri_hygiene_oral'],
    ['section' => 'Cuci Rambut', 'name' => 'frekuensi_cuci_rambut', 'label' => 'Frekuensi Cuci Rambut', 'radio_dibantu' => 'dibantu_cuci_rambut', 'radio_mandiri' => 'mandiri_cuci_rambut'],
    ['section' => 'Gunting Kuku', 'name' => 'frekuensi_gunting_kuku', 'label' => 'Frekuensi Gunting Kuku', 'radio_dibantu' => 'dibantu_gunting_kuku', 'radio_mandiri' => 'mandiri_gunting_kuku'],
];

foreach ($hygiene_data as $index => $item): 
    $char = chr(97 + $index); // Generate 'a', 'b', 'c', ...
?>
    <div class="row mb-2">
        <label class="col-sm-12 col-form-label text-info"><strong><?= $char . '. ' . $item['section'] ?></strong></label>
    </div>

    <div class="row mb-3">
        <label class="col-sm-2 col-form-label"><strong><?= $item['label'] ?></strong></label>
        <div class="col-sm-9">
            <textarea name="<?= $item['name'] ?>" 
                      class="form-control" 
                      rows="1" 
                      style="overflow:hidden; resize:none;" 
                      oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" 
                      <?= $ro ?>><?= val($item['name'], $existing_data) ?></textarea>
        </div>
    </div>

    <?php
    render_radio_row($item['radio_dibantu'], $radio_fields[$item['radio_dibantu']], $existing_data, $ro_disabled);
    render_radio_row($item['radio_mandiri'], $radio_fields[$item['radio_mandiri']], $existing_data, $ro_disabled);
    ?>
<?php endforeach; ?><div class="row mb-2">
    <label class="col-sm-12 col-form-label text-primary"><strong>4. Istirahat dan Tidur</strong></label>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Lama Tidur</strong></label>
    <div class="col-sm-9">
        <textarea name="lama_tidur" class="form-control" rows="1" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('lama_tidur', $existing_data) ?></textarea>
    </div>
</div>
<?php
render_radio_row('kesulitan_tidur', $radio_fields['kesulitan_tidur'], $existing_data, $ro_disabled);
render_radio_row('tidur_siang', $radio_fields['tidur_siang'], $existing_data, $ro_disabled);
?>

<div class="row mb-2">
    <label class="col-sm-12 col-form-label text-primary"><strong>5. Aktivitas dan Latihan</strong></label>
</div>

<?php render_radio_row('olahraga_ringan', $radio_fields['olahraga_ringan'], $existing_data, $ro_disabled); ?>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Jenis/Frekuensi Olahraga</strong></label>
    <div class="col-sm-9">
        <textarea name="jenis_frekuensi_olahraga" class="form-control" rows="1" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('jenis_frekuensi_olahraga', $existing_data) ?></textarea>
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Kegiatan Waktu Luang</strong></label>
    <div class="col-sm-10">
        <textarea name="kegiatan_waktu_luang" class="form-control" rows="1" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('kegiatan_waktu_luang', $existing_data) ?></textarea>
    </div>
</div>

<?php
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