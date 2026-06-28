<?php

$form_id       = 5;
$section_name  = 'identitas_riwayat';
$section_label = 'Identitas & Riwayat';
include dirname(__DIR__, 2) . '/partials/init_section.php';

// =============================================
// HANDLE POST - MAHASISWA
// =============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $level === 'Mahasiswa') {
    if (isLocked($submission)) {
        redirectWithMessage($_SERVER['REQUEST_URI'], 'error', 'Data tidak dapat diubah karena sedang dalam proses review.');
    }

    $text_fields = [
        // Header
        'no_registrasi',
        'hari_tanggal',
        'waktu_pengkajian',
        'tempat_pengkajian',
        // Identitas Klien
        'nama_klien',
        'umur',
        'jk',
        'tgl_lahir',
        'apgar',
        'bb_lahir',
        'bb_sekarang',
        'alamat',
        'usia_gestasi',
        // Identitas Ayah
        'nama_ayah',
        'usia_ayah',
        'pekerjaan_ayah',
        'alamat_ayah',
        // Identitas Ibu
        'nama_ibu',
        'usia_ibu',
        'pekerjaan_ibu',
        'status_gravida',
        'pemeriksaan_kehamilan',
        // Riwayat Kehamilan
        'status_gpa',
        'gpa_g',
        'gpa_p',
        'gpa_a',
        'obat_kehamilan',
        'imunisasi_tt',
        'komplikasi_kehamilan',
        // Riwayat Persalinan
        'riwayat_persalinan',
        'tempat_persalinan',
        'jenis_persalinan',
        'persentasi',
        'air_ketuban',
        'lama_persalinan',
        // Tali Pusat
        'tali_pusat_panjang',
        'tali_pusat_vena',
        'tali_pusat_arteri',
        'tali_pusat_warna',
        'tali_pusat_kelainan',
    ];

    $data = [];
    foreach ($text_fields as $f) {
        $data[$f] = $_POST[$f] ?? '';
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
    <?php include "anak/format_aster/tab.php"; ?>

    <section class="section dashboard">

        <?php include dirname(__DIR__, 2) . '/partials/notifikasi.php'; ?>
        <?php include dirname(__DIR__, 2) . '/partials/status_section.php'; ?>

        <form class="needs-validation" novalidate action="" method="POST">

            <!-- ===================== HEADER PENGKAJIAN ===================== -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><strong>Format Pengkajian Bayi Baru Lahir</strong></h5>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>No. Registrasi</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="no_registrasi"
                                value="<?= ed('no_registrasi', $existing_data) ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Hari / Tanggal</strong></label>
                        <div class="col-sm-9">
                            <input type="date" class="form-control" name="hari_tanggal"
                                value="<?= ed('hari_tanggal', $existing_data) ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Waktu Pengkajian</strong></label>
                        <div class="col-sm-9">
                            <input type="time" class="form-control" name="waktu_pengkajian"
                                value="<?= ed('waktu_pengkajian', $existing_data) ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Tempat Pengkajian</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="tempat_pengkajian"
                                value="<?= ed('tempat_pengkajian', $existing_data) ?>" <?= $ro ?>>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ===================== I. IDENTITAS KLIEN ===================== -->
            <div class="card">
    <div class="card-body">
        <h5 class="card-title"><strong>I. Identitas</strong></h5>
        <div class="row mb-2"><label class="col-sm-12 text-primary"><strong>Identitas Klien</strong></label></div>

        <?php 
        $fields_klien = [
            'nama_klien' => 'Nama', 'umur' => 'Umur', 'tgl_lahir' => 'Tanggal Lahir (Date)', 
            'apgar' => 'Apgar Score', 'bb_lahir' => 'Berat Badan Lahir (gram)', 
            'bb_sekarang' => 'Berat Badan Saat Pengkajian (gram)', 'usia_gestasi' => 'Usia Gestasi (minggu)'
        ]; 
        ?>

        <?php foreach ($fields_klien as $name => $label): 
            $is_date = (strpos($label, 'Date') !== false);
            $unit = (strpos($label, 'gram') !== false) ? 'gram' : ((strpos($label, 'minggu') !== false) ? 'minggu' : null);
        ?>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label"><strong><?= str_replace([' (Date)', ' (gram)', ' (minggu)'], '', $label) ?></strong></label>
                <div class="col-sm-9">
                    <?php if ($unit): ?>
                        <div class="input-group">
                            <textarea class="form-control" name="<?= $name ?>" rows="1" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= ed($name, $existing_data) ?></textarea>
                            <span class="input-group-text"><?= $unit ?></span>
                        </div>
                    <?php elseif ($is_date): ?>
                        <input type="date" class="form-control" name="<?= $name ?>" value="<?= ed($name, $existing_data) ?>" <?= $ro ?>>
                    <?php else: ?>
                        <textarea class="form-control" name="<?= $name ?>" rows="1" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= ed($name, $existing_data) ?></textarea>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>

        <div class="row mb-3">
            <label class="col-sm-3 col-form-label"><strong>Jenis Kelamin</strong></label>
            <div class="col-sm-9">
                <div class="d-flex gap-4 mt-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="jk" value="Laki-laki" id="jk_laki" <?= $ro_disabled ?> <?= (ed('jk', $existing_data) === 'Laki-laki') ? 'checked' : '' ?>>
                        <label class="form-check-label" for="jk_laki">Laki-laki</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="jk" value="Perempuan" id="jk_perempuan" <?= $ro_disabled ?> <?= (ed('jk', $existing_data) === 'Perempuan') ? 'checked' : '' ?>>
                        <label class="form-check-label" for="jk_perempuan">Perempuan</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-3">
            <label class="col-sm-3 col-form-label"><strong>Alamat</strong></label>
            <div class="col-sm-9">
                <textarea class="form-control" name="alamat" rows="1" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= ed('alamat', $existing_data) ?></textarea>
            </div>
        </div>
    </div>
</div>

<div class="card mt-3">
    <div class="card-body">
        <div class="row mb-2"><label class="col-sm-12 text-primary"><strong>Identitas Orang Tua</strong></label></div>

        <?php 
        $parent_fields = [
            'Ayah' => ['nama_ayah' => 'Nama', 'usia_ayah' => 'Usia (tahun)', 'pekerjaan_ayah' => 'Pekerjaan', 'alamat_ayah' => 'Alamat'],
            'Ibu'  => ['nama_ibu' => 'Nama', 'usia_ibu' => 'Usia (tahun)', 'pekerjaan_ibu' => 'Pekerjaan', 'status_gravida' => 'Status Gravida', 'pemeriksaan_kehamilan' => 'Pemeriksaan Kehamilan']
        ];
        ?>

        <?php foreach ($parent_fields as $parent => $items): ?>
            <div class="row mb-2 <?= $parent === 'Ibu' ? 'mt-3' : '' ?>"><label class="col-sm-12"><strong><?= $parent ?></strong></label></div>
            <?php foreach ($items as $name => $label): ?>
                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label"><strong><?= str_replace(' (tahun)', '', $label) ?></strong></label>
                    <div class="col-sm-9">
                        <?php if (strpos($label, 'tahun') !== false): ?>
                            <div class="input-group">
                                <textarea class="form-control" name="<?= $name ?>" rows="1" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= ed($name, $existing_data) ?></textarea>
                                <span class="input-group-text">tahun</span>
                            </div>
                        <?php else: ?>
                            <textarea class="form-control" name="<?= $name ?>" rows="1" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= ed($name, $existing_data) ?></textarea>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endforeach; ?>
    </div>
</div>

<script>
    // Tambahan: Pastikan semua textarea menyesuaikan tinggi saat data dimuat
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('textarea').forEach(el => {
            el.style.height = 'auto';
            el.style.height = el.scrollHeight + 'px';
        });
    });
</script>
<div class="card">
    <div class="card-body">
        <h5 class="card-title"><strong>I. Identitas</strong></h5>
        <div class="row mb-2"><label class="col-sm-12 text-primary"><strong>Identitas Klien</strong></label></div>

        <?php 
        $fields_klien = [
            'nama_klien' => 'Nama', 'umur' => 'Umur', 'tgl_lahir' => 'Tanggal Lahir (Date)', 
            'apgar' => 'Apgar Score', 'bb_lahir' => 'Berat Badan Lahir (gram)', 
            'bb_sekarang' => 'Berat Badan Saat Pengkajian (gram)', 'usia_gestasi' => 'Usia Gestasi (minggu)'
        ]; 
        ?>

        <?php foreach ($fields_klien as $name => $label): 
            $is_date = (strpos($label, 'Date') !== false);
            $unit = (strpos($label, 'gram') !== false) ? 'gram' : ((strpos($label, 'minggu') !== false) ? 'minggu' : null);
        ?>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label"><strong><?= str_replace([' (Date)', ' (gram)', ' (minggu)'], '', $label) ?></strong></label>
                <div class="col-sm-9">
                    <?php if ($unit): ?>
                        <div class="input-group">
                            <textarea class="form-control" name="<?= $name ?>" rows="1" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= ed($name, $existing_data) ?></textarea>
                            <span class="input-group-text"><?= $unit ?></span>
                        </div>
                    <?php elseif ($is_date): ?>
                        <input type="date" class="form-control" name="<?= $name ?>" value="<?= ed($name, $existing_data) ?>" <?= $ro ?>>
                    <?php else: ?>
                        <textarea class="form-control" name="<?= $name ?>" rows="1" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= ed($name, $existing_data) ?></textarea>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>

        <div class="row mb-3">
            <label class="col-sm-3 col-form-label"><strong>Jenis Kelamin</strong></label>
            <div class="col-sm-9">
                <div class="d-flex gap-4 mt-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="jk" value="Laki-laki" id="jk_laki" <?= $ro_disabled ?> <?= (ed('jk', $existing_data) === 'Laki-laki') ? 'checked' : '' ?>>
                        <label class="form-check-label" for="jk_laki">Laki-laki</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="jk" value="Perempuan" id="jk_perempuan" <?= $ro_disabled ?> <?= (ed('jk', $existing_data) === 'Perempuan') ? 'checked' : '' ?>>
                        <label class="form-check-label" for="jk_perempuan">Perempuan</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-3">
            <label class="col-sm-3 col-form-label"><strong>Alamat</strong></label>
            <div class="col-sm-9">
                <textarea class="form-control" name="alamat" rows="1" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= ed('alamat', $existing_data) ?></textarea>
            </div>
        </div>
    </div>
</div>

<div class="card mt-3">
    <div class="card-body">
        <div class="row mb-2"><label class="col-sm-12 text-primary"><strong>Identitas Orang Tua</strong></label></div>
        <?php 
        $parent_fields = [
            'Ayah' => ['nama_ayah' => 'Nama', 'usia_ayah' => 'Usia (tahun)', 'pekerjaan_ayah' => 'Pekerjaan', 'alamat_ayah' => 'Alamat'],
            'Ibu'  => ['nama_ibu' => 'Nama', 'usia_ibu' => 'Usia (tahun)', 'pekerjaan_ibu' => 'Pekerjaan', 'status_gravida' => 'Status Gravida', 'pemeriksaan_kehamilan' => 'Pemeriksaan Kehamilan']
        ];
        ?>
        <?php foreach ($parent_fields as $parent => $items): ?>
            <div class="row mb-2 <?= $parent === 'Ibu' ? 'mt-3' : '' ?>"><label class="col-sm-12"><strong><?= $parent ?></strong></label></div>
            <?php foreach ($items as $name => $label): ?>
                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label"><strong><?= str_replace(' (tahun)', '', $label) ?></strong></label>
                    <div class="col-sm-9">
                        <textarea class="form-control" name="<?= $name ?>" rows="1" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= ed($name, $existing_data) ?></textarea>
                        <?php if (strpos($label, 'tahun') !== false) echo '<span class="text-muted small">tahun</span>'; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endforeach; ?>
    </div>
</div>

<div class="card mt-3">
    <div class="card-body">
        <div class="row mb-2"><label class="col-sm-12 text-primary"><strong>Riwayat Persalinan Sekarang</strong></label></div>
        <?php 
        $persalinan_fields = [
            'riwayat_persalinan' => 'Riwayat Persalinan', 'tempat_persalinan' => 'Tempat Persalinan',
            'persentasi' => 'Persentasi', 'air_ketuban' => 'Air Ketuban', 'lama_persalinan' => 'Lama Persalinan Kala II',
            'tali_pusat_panjang' => 'Panjang Tali Pusat (cm)', 'tali_pusat_vena' => 'Jumlah Vena', 
            'tali_pusat_arteri' => 'Jumlah Arteri', 'tali_pusat_warna' => 'Warna Tali Pusat', 'tali_pusat_kelainan' => 'Kelainan Tali Pusat'
        ];
        ?>
        <?php foreach ($persalinan_fields as $name => $label): ?>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label"><strong><?= str_replace([' (cm)'], '', $label) ?></strong></label>
                <div class="col-sm-9">
                    <textarea class="form-control" name="<?= $name ?>" rows="1" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= ed($name, $existing_data) ?></textarea>
                </div>
            </div>
        <?php endforeach; ?>
        
        <div class="row mb-3">
            <label class="col-sm-3 col-form-label"><strong>Jenis Persalinan</strong></label>
            <div class="col-sm-9">
                <div class="d-flex gap-4 mt-2 flex-wrap">
                    <?php $jenis_options = ['Spontan', 'SC (Sectio Caesaria)', 'Vakum', 'Forcep'];
                    foreach ($jenis_options as $opt):
                        $id = 'jp_' . strtolower(preg_replace('/\W+/', '_', $opt)); ?>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="jenis_persalinan" value="<?= $opt ?>" id="<?= $id ?>" <?= $ro_disabled ?> <?= (ed('jenis_persalinan', $existing_data) === $opt) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="<?= $id ?>"><?= $opt ?></label>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

                    <!-- TOMBOL SIMPAN -->
                    <?php if (!$is_dosen): ?>
                        <div class="row mb-3">
                            <div class="col-sm-12 d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary" <?= $ro_disabled ?>>Simpan Data</button>
                            </div>
                        </div>
                    <?php endif; ?>

                </div>
            </div>

        </form>

        <?php include dirname(__DIR__, 2) . '/partials/footer_form.php'; ?>
    </section>
</main>