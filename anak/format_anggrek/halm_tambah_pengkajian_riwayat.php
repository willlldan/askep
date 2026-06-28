<?php


$form_id       = 4;
$section_name  = 'pengkajian_riwayat';
$section_label = 'Pengkajian Riwayat';
include dirname(__DIR__, 2) . '/partials/init_section.php';

// =============================================
// HANDLE POST - MAHASISWA SIMPAN DATA
// =============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $level === 'Mahasiswa') {

    if (isLocked($submission)) {
        redirectWithMessage($_SERVER['REQUEST_URI'], 'error', 'Data tidak dapat diubah karena sedang dalam proses review.');
    }

    $data = [
        // 7. Riwayat Imunisasi
        'bcg_frekuensi'          => $_POST['bcg_frekuensi'] ?? '',
        'bcg_reaksi'             => $_POST['bcg_reaksi'] ?? '',
        'dpt_frekuensi'          => $_POST['dpt_frekuensi'] ?? '',
        'dpt_reaksi'             => $_POST['dpt_reaksi'] ?? '',
        'polio_frekuensi'        => $_POST['polio_frekuensi'] ?? '',
        'polio_reaksi'           => $_POST['polio_reaksi'] ?? '',
        'campak_frekuensi'       => $_POST['campak_frekuensi'] ?? '',
        'campak_reaksi'          => $_POST['campak_reaksi'] ?? '',
        'hepatitis_frekuensi'    => $_POST['hepatitis_frekuensi'] ?? '',
        'hepatitis_reaksi'       => $_POST['hepatitis_reaksi'] ?? '',

        // 8. Riwayat Tumbuh Kembang
        'bb'                      => $_POST['bb'] ?? '',
        'tb'                      => $_POST['tb'] ?? '',
        'gigi'                    => $_POST['gigi'] ?? '',
        'gigi_tanggal'            => $_POST['gigi_tanggal'] ?? '',
        'gigi_jumlah'             => $_POST['gigi_jumlah'] ?? '',

        // 9. Riwayat Nutrisi
        'asi'                     => $_POST['asi'] ?? '',
        'alasan_susu'             => $_POST['alasan_susu'] ?? '',
        'jumlah_susu'             => $_POST['jumlah_susu'] ?? '',

        // 10. Riwayat Psikososial
        'tinggal_bersama'         => $_POST['tinggal_bersama'] ?? '',
        'tinggal_di'              => $_POST['tinggal_di'] ?? '',
        'rumah_dekat'             => $_POST['rumah_dekat'] ?? '',
        'tempat_bermain'          => $_POST['tempat_bermain'] ?? '',
        'kamar_klien'             => $_POST['kamar_klien'] ?? '',

        // 11. Reaksi Hospitalisasi
        'alasan_rs'               => $_POST['alasan_rs'] ?? '',
        'penjelasan_dokter'       => $_POST['penjelasan_dokter'] ?? '',
        'perasaan'                => $_POST['perasaan'] ?? '',
        'kunjungan'               => $_POST['kunjungan'] ?? '',
        'pendamping'              => $_POST['pendamping'] ?? '',

        // 12. Reaksi Anak Selama Dirawat
        'reaksi_anak'             => $_POST['reaksi_anak'] ?? '',
        // Nutrisi
        'selera_sebelum'      => $_POST['selera_sebelum'] ?? '',
        'selera_saat'         => $_POST['selera_saat'] ?? '',
        'porsi_sebelum'       => $_POST['porsi_sebelum'] ?? '',
        'porsi_saat'          => $_POST['porsi_saat'] ?? '',
        'menu_sebelum'        => $_POST['menu_sebelum'] ?? '',
        'menu_saat'           => $_POST['menu_saat'] ?? '',

        // Cairan
        'jenis_minum_sebelum' => $_POST['jenis_minum_sebelum'] ?? '',
        'jenis_minum_saat'    => $_POST['jenis_minum_saat'] ?? '',
        'frekuensi_minum_sebelum' => $_POST['frekuensi_minum_sebelum'] ?? '',
        'frekuensi_minum_saat'    => $_POST['frekuensi_minum_saat'] ?? '',
        'kebutuhan_cairan_sebelum' => $_POST['kebutuhan_cairan_sebelum'] ?? '',
        'kebutuhan_cairan_saat'    => $_POST['kebutuhan_cairan_saat'] ?? '',
        'cara_cairan_sebelum'      => $_POST['cara_cairan_sebelum'] ?? '',
        'cara_cairan_saat'         => $_POST['cara_cairan_saat'] ?? '',

        // Eliminasi (BAK)
        'bak_tempat_sebelum'     => $_POST['bak_tempat_sebelum'] ?? '',
        'bak_tempat_saat'        => $_POST['bak_tempat_saat'] ?? '',
        'bak_frekuensi_sebelum'  => $_POST['bak_frekuensi_sebelum'] ?? '',
        'bak_frekuensi_saat'     => $_POST['bak_frekuensi_saat'] ?? '',
        'bak_karakteristik_sebelum' => $_POST['bak_karakteristik_sebelum'] ?? '',
        'bak_karakteristik_saat'    => $_POST['bak_karakteristik_saat'] ?? '',

        // Eliminasi (BAB)
        'bab_tempat_sebelum'     => $_POST['bab_tempat_sebelum'] ?? '',
        'bab_tempat_saat'        => $_POST['bab_tempat_saat'] ?? '',
        'bab_frekuensi_sebelum'  => $_POST['bab_frekuensi_sebelum'] ?? '',
        'bab_frekuensi_saat'     => $_POST['bab_frekuensi_saat'] ?? '',
        'bab_karakteristik_sebelum' => $_POST['bab_karakteristik_sebelum'] ?? '',
        'bab_karakteristik_saat'    => $_POST['bab_karakteristik_saat'] ?? '',

        // Istirahat Tidur
        'tidur_siang_sebelum'     => $_POST['tidur_siang_sebelum'] ?? '',
        'tidur_siang_sekarang'    => $_POST['tidur_siang_sekarang'] ?? '',
        'tidur_malam_sebelum'     => $_POST['tidur_malam_sebelum'] ?? '',
        'tidur_malam_sekarang'    => $_POST['tidur_malam_sekarang'] ?? '',
        'kesulitan_tidur_sebelum' => $_POST['kesulitan_tidur_sebelum'] ?? '',
        'kesulitan_tidur_sekarang' => $_POST['kesulitan_tidur_sekarang'] ?? '',
        'kebiasaan_tidur_sebelum' => $_POST['kebiasaan_tidur_sebelum'] ?? '',
        'kebiasaan_tidur_sekarang' => $_POST['kebiasaan_tidur_sekarang'] ?? '',
        'pola_tidur_sebelum'      => $_POST['pola_tidur_sebelum'] ?? '',
        'pola_tidur_sekarang'     => $_POST['pola_tidur_sekarang'] ?? '',

        // Pola Personal Hygiene
        'mandi_frekuensi_sebelum' => $_POST['mandi_frekuensi_sebelum'] ?? '',
        'mandi_frekuensi_sekarang' => $_POST['mandi_frekuensi_sekarang'] ?? '',
        'mandi_cara_sebelum'      => $_POST['mandi_cara_sebelum'] ?? '',
        'mandi_cara_sekarang'     => $_POST['mandi_cara_sekarang'] ?? '',
        'mandi_tempat_sebelum'    => $_POST['mandi_tempat_sebelum'] ?? '',
        'mandi_tempat_sekarang'   => $_POST['mandi_tempat_sekarang'] ?? '',
        'rambut_frekuensi_sebelum' => $_POST['rambut_frekuensi_sebelum'] ?? '',
        'rambut_frekuensi_sekarang' => $_POST['rambut_frekuensi_sekarang'] ?? '',
        'rambut_cara_sebelum'     => $_POST['rambut_cara_sebelum'] ?? '',
        'rambut_cara_sekarang'    => $_POST['rambut_cara_sekarang'] ?? '',
        'kuku_frekuensi_sebelum'  => $_POST['kuku_frekuensi_sebelum'] ?? '',
        'kuku_frekuensi_sekarang' => $_POST['kuku_frekuensi_sekarang'] ?? '',
        'kuku_cara_sebelum'       => $_POST['kuku_cara_sebelum'] ?? '',
        'kuku_cara_sekarang'      => $_POST['kuku_cara_sekarang'] ?? '',
        'gigi_frekuensi_sebelum'  => $_POST['gigi_frekuensi_sebelum'] ?? '',
        'gigi_frekuensi_sekarang' => $_POST['gigi_frekuensi_sekarang'] ?? '',
        'gigi_cara_sebelum'       => $_POST['gigi_cara_sebelum'] ?? '',
        'gigi_cara_sekarang'      => $_POST['gigi_cara_sekarang'] ?? ''
    ];

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

    <?php include "anak/format_anggrek/tab.php"; ?>

    <section class="section dashboard">
        <?php include dirname(__DIR__, 2) . '/partials/notifikasi.php'; ?>
        <?php include dirname(__DIR__, 2) . '/partials/status_section.php'; ?>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-1"><strong>7. Riwayat Imunisasi (Imunisasi Lengkap)</strong></h5>

                <!-- General Form Elements -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="text-center">
                                <tr>
                                    <th style="width:5%">No</th>
                                    <th style="width:35%">Jenis Imunisasi</th>
                                    <th style="width:25%">Frekuensi</th>
                                    <th style="width:35%">Reaksi Setelah Pemberian</th>
                                </tr>
                            </thead>
                            <tbody>
                               <tr>
    <td class="text-center align-middle">1</td>
    <td>BCG</td>
    <td><textarea class="form-control form-control-sm" name="bcg_frekuensi" rows="1" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('bcg_frekuensi', $existing_data) ?></textarea></td>
    <td><textarea class="form-control form-control-sm" name="bcg_reaksi" rows="1" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('bcg_reaksi', $existing_data) ?></textarea></td>
</tr>
<tr>
    <td class="text-center align-middle">2</td>
    <td>DPT Hb Hib (I, II, III)</td>
    <td><textarea class="form-control form-control-sm" name="dpt_frekuensi" rows="1" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('dpt_frekuensi', $existing_data) ?></textarea></td>
    <td><textarea class="form-control form-control-sm" name="dpt_reaksi" rows="1" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('dpt_reaksi', $existing_data) ?></textarea></td>
</tr>
<tr>
    <td class="text-center align-middle">3</td>
    <td>Polio (I, II, III, IV)</td>
    <td><textarea class="form-control form-control-sm" name="polio_frekuensi" rows="1" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('polio_frekuensi', $existing_data) ?></textarea></td>
    <td><textarea class="form-control form-control-sm" name="polio_reaksi" rows="1" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('polio_reaksi', $existing_data) ?></textarea></td>
</tr>
<tr>
    <td class="text-center align-middle">4</td>
    <td>Campak</td>
    <td><textarea class="form-control form-control-sm" name="campak_frekuensi" rows="1" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('campak_frekuensi', $existing_data) ?></textarea></td>
    <td><textarea class="form-control form-control-sm" name="campak_reaksi" rows="1" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('campak_reaksi', $existing_data) ?></textarea></td>
</tr>
<tr>
    <td class="text-center align-middle">5</td>
    <td>Hepatitis</td>
    <td><textarea class="form-control form-control-sm" name="hepatitis_frekuensi" rows="1" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('hepatitis_frekuensi', $existing_data) ?></textarea></td>
    <td><textarea class="form-control form-control-sm" name="hepatitis_reaksi" rows="1" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('hepatitis_reaksi', $existing_data) ?></textarea></td>
</tr>
                            </tbody>
                        </table>
                    </div>
                   <!-- 8. RIWAYAT TUMBUH KEMBANG -->
<div class="row mb-3">
    <label class="col-sm-12 col-form-label text-primary"><strong>8. Riwayat Tumbuh Kembang</strong></label>
</div>
<div class="row mb-2"><label class="col-sm-12"><strong>Pertumbuhan Fisik</strong></label></div>

<!-- Berat Badan -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Berat Badan (kg)</strong></label>
    <div class="col-sm-9">
        <textarea class="form-control" name="bb" rows="1" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('bb', $existing_data) ?></textarea>
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Tinggi Badan (cm)</strong></label>
    <div class="col-sm-9">
        <textarea class="form-control" name="tb" rows="1" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('tb', $existing_data) ?></textarea>
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Waktu Tumbuh Gigi</strong></label>
    <div class="col-sm-9">
        <textarea class="form-control" name="gigi" rows="1" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('gigi', $existing_data) ?></textarea>
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Gigi Tanggal</strong></label>
    <div class="col-sm-9">
        <textarea class="form-control" name="gigi_tanggal" rows="1" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('gigi_tanggal', $existing_data) ?></textarea>
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Gigi Jumlah</strong></label>
    <div class="col-sm-9">
        <textarea class="form-control" name="gigi_jumlah" rows="1" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('gigi_jumlah', $existing_data) ?></textarea>
    </div>
</div>

<!-- 9. RIWAYAT NUTRISI -->
<div class="row mb-3">
    <label class="col-sm-12 col-form-label text-primary"><strong>9. Riwayat Nutrisi</strong></label>
</div>
<!-- ASI -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Pemberian ASI sampai usia </strong></label>
    <div class="col-sm-9">
        <textarea class="form-control" name="asi" rows="1" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('asi', $existing_data) ?></textarea>
    </div>
</div>

<!-- Susu Formula -->
<div class="row mb-2"><label class="col-sm-12"><strong>Pemberian Susu Formula</strong></label></div>

<!-- Alasan -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Alasan pemberian</strong></label>
    <div class="col-sm-9">
        <textarea class="form-control" name="alasan_susu" rows="1" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('alasan_susu', $existing_data) ?></textarea>
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Jumlah pemberian sehari</strong></label>
    <div class="col-sm-9">
        <textarea class="form-control" name="jumlah_susu" rows="1" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('jumlah_susu', $existing_data) ?></textarea>
    </div>
</div>

<!-- 10. RIWAYAT PSIKOSOSIAL -->
<div class="row mb-3">
    <label class="col-sm-12 col-form-label text-primary"><strong>10. Riwayat Psikososial</strong></label>
</div>

<!-- Anak tinggal -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Anak tinggal bersama</strong></label>
    <div class="col-sm-9 d-flex gap-2">
        <textarea class="form-control" name="tinggal_bersama" rows="1" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('tinggal_bersama', $existing_data) ?></textarea>
        <span class="align-self-center">di</span>
        <textarea class="form-control" name="tinggal_di" rows="1" style="overflow:hidden; resize:none; min-width:150px;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('tinggal_di', $existing_data) ?></textarea>
    </div>
</div>
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Rumah Dekat Dengan</strong></label>
    <div class="col-sm-9">
        <textarea class="form-control" name="rumah_dekat" rows="1" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('rumah_dekat', $existing_data) ?></textarea>
    </div>
</div>
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Tempat Anak Bermain</strong></label>
    <div class="col-sm-9">
        <textarea class="form-control" name="tempat_bermain" rows="1" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('tempat_bermain', $existing_data) ?></textarea>
    </div>
</div>
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Kamar klien</strong></label>
    <div class="col-sm-9">
        <textarea class="form-control" name="kamar_klien" rows="1" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('kamar_klien', $existing_data) ?></textarea>
    </div>
</div>


                    <!-- 11. REAKSI HOSPITALISASI -->
                    <div class="row mb-3">
                        <label class="col-sm-12 col-form-label text-primary">
                            <strong>11. Reaksi Hospitalisasi</strong>
                        </label>
                    </div>
                    <div class="row mb-2">
                        <label class="col-sm-12"><strong>a. Pengalaman keluarga tentang sakit dan rawat inap</strong></label>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Ibu membawa anak ke RS karena</strong></label>
                        <div class="col-sm-9">
                            <textarea class="form-control" rows="3" name="alasan_rs" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('alasan_rs', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Dokter menceritakan kondisi anak</strong></label>
                        <div class="col-sm-9">
                            <textarea class="form-control" rows="3" name="penjelasan_dokter" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('penjelasan_dokter', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Perasaan orang tua saat ini</strong></label>
                        <div class="col-sm-9">
                            <textarea class="form-control" rows="3" name="perasaan" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('perasaan', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Orang tua selalu berkunjung ke RS</strong></label>
                        <div class="col-sm-9">
                            <textarea class="form-control" rows="2" name="kunjungan" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('kunjungan', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Yang akan tinggal menemani anak di rumah sakit</strong></label>
                        <div class="col-sm-9">
                            <textarea class="form-control" rows="4" name="pendamping" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('pendamping', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- 12. REAKSI ANAK -->
                    <div class="row mb-3">
                        <label class="col-sm-12 col-form-label text-primary">
                            <strong>12. Reaksi Anak Selama Dirawat</strong>
                        </label>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Reaksi Anak selama dirawat</strong></label>
                        <div class="col-sm-9">
                            <textarea class="form-control" rows="2" name="reaksi_anak" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('reaksi_anak', $existing_data) ?></textarea>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-12 col-form-label text-primary">
                            <strong>13. Aktivitas sehari-hari</strong>
                        </label>
                    </div>
                <div class="row mb-2"><label class="col-sm-12 text-primary"><strong>Nutrisi</strong></label></div>
<div class="row mb-4">
    <div class="col-sm-12">
        <div class="table-responsive">
            <table class="table table-bordered mb-0">
                <thead class="table-light text-center">
                    <tr>
                        <th style="width:40%">Kondisi</th>
                        <th style="width:30%">Sebelum Sakit</th>
                        <th style="width:30%">Saat Sakit</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $fields = [
                        'Selera Makan' => ['selera_sebelum', 'selera_saat'],
                        'Porsi Makan'  => ['porsi_sebelum', 'porsi_saat'],
                        'Menu Makanan' => ['menu_sebelum', 'menu_saat']
                    ];
                    foreach ($fields as $label => $n) : ?>
                    <tr>
                        <td><strong><?= $label ?></strong></td>
                        <td><textarea class="form-control" name="<?= $n[0] ?>" rows="1" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val($n[0], $existing_data) ?></textarea></td>
                        <td><textarea class="form-control" name="<?= $n[1] ?>" rows="1" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val($n[1], $existing_data) ?></textarea></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="row mb-2"><label class="col-sm-12 text-primary"><strong>Cairan</strong></label></div>
<div class="row mb-4">
    <div class="col-sm-12">
        <div class="table-responsive">
            <table class="table table-bordered mb-0">
                <thead class="table-light text-center">
                    <tr>
                        <th style="width:40%">Kondisi</th>
                        <th style="width:30%">Sebelum Sakit</th>
                        <th style="width:30%">Saat Sakit</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $fields = [
                        'Jenis Minuman'    => ['jenis_minum_sebelum', 'jenis_minum_saat'],
                        'Frekuensi Minum'  => ['frekuensi_minum_sebelum', 'frekuensi_minum_saat'],
                        'Kebutuhan Cairan' => ['kebutuhan_cairan_sebelum', 'kebutuhan_cairan_saat'],
                        'Cara Pemenuhan'   => ['cara_cairan_sebelum', 'cara_cairan_saat']
                    ];
                    foreach ($fields as $label => $n) : ?>
                    <tr>
                        <td><strong><?= $label ?></strong></td>
                        <td><textarea class="form-control" name="<?= $n[0] ?>" rows="1" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val($n[0], $existing_data) ?></textarea></td>
                        <td><textarea class="form-control" name="<?= $n[1] ?>" rows="1" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val($n[1], $existing_data) ?></textarea></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="row mb-2"><label class="col-sm-12 text-primary"><strong>Eliminasi (BAK)</strong></label></div>
<div class="row mb-4">
    <div class="col-sm-12">
        <div class="table-responsive">
            <table class="table table-bordered mb-0">
                <thead class="table-light text-center">
                    <tr>
                        <th style="width:40%">Kondisi</th>
                        <th style="width:30%">Sebelum Sakit</th>
                        <th style="width:30%">Saat Sakit</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $fields = [
                        'Tempat'      => ['bak_tempat_sebelum', 'bak_tempat_saat'],
                        'Frekuensi (Waktu)'   => ['bak_frekuensi_sebelum', 'bak_frekuensi_saat'],
                        'Karakter'    => ['bak_karakteristik_sebelum', 'bak_karakteristik_saat'],
                        
                    ];
                    foreach ($fields as $label => $n) : ?>
                    <tr>
                        <td><strong><?= $label ?></strong></td>
                        <td><textarea class="form-control" name="<?= $n[0] ?>" rows="1" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val($n[0], $existing_data) ?></textarea></td>
                        <td><textarea class="form-control" name="<?= $n[1] ?>" rows="1" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val($n[1], $existing_data) ?></textarea></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="row mb-2"><label class="col-sm-12 text-primary"><strong>Eliminasi (BAB)</strong></label></div>
<div class="row mb-4">
    <div class="col-sm-12">
        <div class="table-responsive">
            <table class="table table-bordered mb-0">
                <thead class="table-light text-center">
                    <tr>
                        <th style="width:40%">Kondisi</th>
                        <th style="width:30%">Sebelum Sakit</th>
                        <th style="width:30%">Saat Sakit</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $fields = [

                        'Tempat'      => ['bab_tempat_sebelum', 'bab_tempat_saat'],
                        'Frekuensi (Waktu)'   => ['bab_frekuensi_sebelum', 'bab_frekuensi_saat'],
                        'Karakter'    => ['bab_karakteristik_sebelum', 'bab_karakteristik_saat']
                    ];
                    foreach ($fields as $label => $n) : ?>
                    <tr>
                        <td><strong><?= $label ?></strong></td>
                        <td><textarea class="form-control" name="<?= $n[0] ?>" rows="1" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val($n[0], $existing_data) ?></textarea></td>
                        <td><textarea class="form-control" name="<?= $n[1] ?>" rows="1" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val($n[1], $existing_data) ?></textarea></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="row mb-2"><label class="col-sm-12 text-primary"><strong>Istirahat Tidur</strong></label></div>
<div class="row mb-4">
    <div class="col-sm-12">
        <div class="table-responsive">
            <table class="table table-bordered table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Kondisi</th>
                        <th>Sebelum Sakit</th>
                        <th>Saat Ini</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    

                    rowHygiene(['1', 2], 'Jam Tidur - Siang', 'tidur_siang_sebelum', 'tidur_siang_sekarang', $existing_data, $ro);
                    rowHygiene(null, 'Jam Tidur - Malam', 'tidur_malam_sebelum', 'tidur_malam_sekarang', $existing_data, $ro);
                    rowHygiene(2, 'Kesulitan Tidur', 'kesulitan_tidur_sebelum', 'kesulitan_tidur_sekarang', $existing_data, $ro);
                    rowHygiene(3, 'Kebiasaan Sebelum Tidur', 'kebiasaan_tidur_sebelum', 'kebiasaan_tidur_sekarang', $existing_data, $ro);
                    rowHygiene(4, 'Pola Tidur', 'pola_tidur_sebelum', 'pola_tidur_sekarang', $existing_data, $ro);
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="row mb-2"><label class="col-sm-12 text-primary"><strong>Personal Hygiene</strong></label></div>
<div class="row mb-4">
    <div class="col-sm-12">
        <div class="table-responsive">
            <table class="table table-bordered table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Kondisi</th>
                        <th>Sebelum Sakit</th>
                        <th>Saat Ini</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    function renderTextarea($name, $existing_data, $ro) {
                        return "<textarea class='form-control' name='$name' rows='1' style='overflow:hidden; resize:none;' oninput='this.style.height=\"auto\"; this.style.height=this.scrollHeight+\"px\";' $ro>".val($name, $existing_data)."</textarea>";
                    }

                    function rowHygiene($no, $label, $n1, $n2, $existing_data, $ro) {
                        $span = is_array($no) ? "rowspan='{$no[1]}'" : "";
                        $num = is_array($no) ? $no[0] : $no;
                        $displayNo = ($num !== null) ? "<td class='align-middle text-center' $span>$num</td>" : "";
                        echo "<tr>$displayNo<td class='align-middle'><strong>$label</strong></td><td>".renderTextarea($n1, $existing_data, $ro)."</td><td>".renderTextarea($n2, $existing_data, $ro)."</td></tr>";
                    }
          
                    rowHygiene(['1', 3], 'Mandi - Frekuensi', 'mandi_frekuensi_sebelum', 'mandi_frekuensi_sekarang', $existing_data, $ro);
                    rowHygiene(null, 'Mandi - Cara', 'mandi_cara_sebelum', 'mandi_cara_sekarang', $existing_data, $ro);
                    rowHygiene(null, 'Mandi - Alat Mandi', 'mandi_tempat_sebelum', 'mandi_tempat_sekarang', $existing_data, $ro);
                    rowHygiene(['2', 2], 'Cuci Rambut - Frekuensi', 'rambut_frekuensi_sebelum', 'rambut_frekuensi_sekarang', $existing_data, $ro);
                    rowHygiene(null, 'Cuci Rambut - Cara', 'rambut_cara_sebelum', 'rambut_cara_sekarang', $existing_data, $ro);
                    rowHygiene(['3', 2], 'Gunting Kuku - Frekuensi', 'kuku_frekuensi_sebelum', 'kuku_frekuensi_sekarang', $existing_data, $ro);
                    rowHygiene(null, 'Gunting Kuku - Cara', 'kuku_cara_sebelum', 'kuku_cara_sekarang', $existing_data, $ro);
                    rowHygiene(['4', 2], 'Gosok Gigi - Frekuensi', 'gigi_frekuensi_sebelum', 'gigi_frekuensi_sekarang', $existing_data, $ro);
                    rowHygiene(null, 'Gosok Gigi - Cara', 'gigi_cara_sebelum', 'gigi_cara_sekarang', $existing_data, $ro);
                    ?>
                </tbody>
            </table>
        </div>
   


                        </div>
                    </div>
                    <!-- TOMBOL SUBMIT -->
                    <?php if (!$is_dosen): ?>
                        <div class="row mb-3">
                            <div class="col-sm-11 d-flex justify-content-end">
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