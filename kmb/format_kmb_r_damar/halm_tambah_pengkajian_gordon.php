<?php
$form_id       = 16;
$section_name  = 'gordon';
$section_label = 'Pola Pengkajian FX Gordon';
include dirname(__DIR__, 2) . '/partials/init_section.php';

// =============================================
// HANDLE POST - MAHASISWA SIMPAN DATA
// =============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $level === 'Mahasiswa') {

    if (isLocked($submission)) {
        redirectWithMessage($_SERVER['REQUEST_URI'], 'error', 'Data tidak dapat diubah karena sedang dalam proses review.');
    }

    $data = [
        // a. Persepsi Kesehatan
        'merokok'               => $_POST['merokok']               ?? '',
        'pemeriksaan_rutin'     => $_POST['pemeriksaan_rutin']     ?? '',
        'pendapat_kesehatan'    => $_POST['pendapat_kesehatan']    ?? '',
        'persepsi_penyakit'     => $_POST['persepsi_penyakit']     ?? '',
        'tingkat_kesembuhan'    => $_POST['tingkat_kesembuhan']    ?? '',
        // b. Pola Aktivitas
        'rutinitas_mandi'       => $_POST['rutinitas_mandi']       ?? '',
        'kebersihan'            => $_POST['kebersihan']            ?? '',
        'aktivitas'             => $_POST['aktivitas']             ?? '',
        // Kemampuan Perawatan Diri
        'mandi'                 => $_POST['mandi']                 ?? '',
        'berpakaian'            => $_POST['berpakaian']            ?? '',
        'mobilisasi'            => $_POST['mobilisasi']            ?? '',
        'pindah'                => $_POST['pindah']                ?? '',
        'ambulasi'              => $_POST['ambulasi']              ?? '',
        'makan'                 => $_POST['makan']                 ?? '',
        // c. Pola Kognitif
        'nyeri'                 => $_POST['nyeri']                 ?? '',
        'panca_indra'           => $_POST['panca_indra']           ?? '',
        'berbicara'             => $_POST['berbicara']             ?? '',
        'membaca'               => $_POST['membaca']               ?? '',
        // d. Pola Konsep Diri
        'konsep_diri'           => $_POST['konsep_diri']           ?? '',
        'hal_disukai'           => $_POST['hal_disukai']           ?? '',
        'kekuatan_kelemahan'    => $_POST['kekuatan_kelemahan']    ?? '',
        'kemampuan_baik'        => $_POST['kemampuan_baik']        ?? '',
        // e. Pola Koping
        'masalah_rs'            => $_POST['masalah_rs']            ?? '',
        'kehilangan'            => $_POST['kehilangan']            ?? '',
        'takut_kekerasan'       => $_POST['takut_kekerasan']       ?? '',
        'masa_depan'            => $_POST['masa_depan']            ?? '',
        'mekanisme_koping'      => $_POST['mekanisme_koping']      ?? '',
        // f. Pola Seksual
        'masalah_menstruasi'    => $_POST['masalah_menstruasi']    ?? '',
        'papsmear'              => $_POST['papsmear']              ?? '',
        'perawatan_payudara'    => $_POST['perawatan_payudara']    ?? '',
        'kesulitan_seksual'     => $_POST['kesulitan_seksual']     ?? '',
        'gangguan_seksual'      => $_POST['gangguan_seksual']      ?? '',
        // g. Pola Peran
        'peran_pasien'          => $_POST['peran_pasien']          ?? '',
        'teman_dekat'           => $_POST['teman_dekat']           ?? '',
        'orang_terpercaya'      => $_POST['orang_terpercaya']      ?? '',
        'kegiatan_masyarakat'   => $_POST['kegiatan_masyarakat']   ?? '',
        // h. Pola Nilai & Kepercayaan
        'agama_klien'           => $_POST['agama_klien']           ?? '',
        'hubungan_tuhan'        => $_POST['hubungan_tuhan']        ?? '',
        'hambatan_ibadah'       => $_POST['hambatan_ibadah']       ?? '',
        // i. Nutrisi
        'frekuensi_makan_sebelum'   => $_POST['frekuensi_makan_sebelum']   ?? '',
        'frekuensi_makan_sekarang'  => $_POST['frekuensi_makan_sekarang']  ?? '',
        'selera_makan_sebelum'      => $_POST['selera_makan_sebelum']      ?? '',
        'selera_makan_sekarang'     => $_POST['selera_makan_sekarang']     ?? '',
        'menu_makan_sebelum'        => $_POST['menu_makan_sebelum']        ?? '',
        'menu_makan_sekarang'       => $_POST['menu_makan_sekarang']       ?? '',
        'ritual_makan_sebelum'      => $_POST['ritual_makan_sebelum']      ?? '',
        'ritual_makan_sekarang'     => $_POST['ritual_makan_sekarang']     ?? '',
        'bantuan_makan_sebelum'     => $_POST['bantuan_makan_sebelum']     ?? '',
        'bantuan_makan_sekarang'    => $_POST['bantuan_makan_sekarang']    ?? '',
        // j. Cairan
        'jenis_minum_sebelum'       => $_POST['jenis_minum_sebelum']       ?? '',
        'jenis_minum_sekarang'      => $_POST['jenis_minum_sekarang']      ?? '',
        'jumlah_cairan_sebelum'     => $_POST['jumlah_cairan_sebelum']     ?? '',
        'jumlah_cairan_sekarang'    => $_POST['jumlah_cairan_sekarang']    ?? '',
        'bantuan_cairan_sebelum'    => $_POST['bantuan_cairan_sebelum']    ?? '',
        'bantuan_cairan_sekarang'   => $_POST['bantuan_cairan_sekarang']   ?? '',
        // k. BAB
        'bab_frekuensi_sebelum'     => $_POST['bab_frekuensi_sebelum']     ?? '',
        'bab_frekuensi_sekarang'    => $_POST['bab_frekuensi_sekarang']    ?? '',
        'bab_konsistensi_sebelum'   => $_POST['bab_konsistensi_sebelum']   ?? '',
        'bab_konsistensi_sekarang'  => $_POST['bab_konsistensi_sekarang']  ?? '',
        'bab_warna_sebelum'         => $_POST['bab_warna_sebelum']         ?? '',
        'bab_warna_sekarang'        => $_POST['bab_warna_sekarang']        ?? '',
        'bab_bau_sebelum'           => $_POST['bab_bau_sebelum']           ?? '',
        'bab_bau_sekarang'          => $_POST['bab_bau_sekarang']          ?? '',
        'bab_kesulitan_sebelum'     => $_POST['bab_kesulitan_sebelum']     ?? '',
        'bab_kesulitan_sekarang'    => $_POST['bab_kesulitan_sekarang']    ?? '',
        'bab_obat_sebelum'          => $_POST['bab_obat_sebelum']          ?? '',
        'bab_obat_sekarang'         => $_POST['bab_obat_sekarang']         ?? '',
        // l. BAK
        'bak_frekuensi_sebelum'     => $_POST['bak_frekuensi_sebelum']     ?? '',
        'bak_frekuensi_sekarang'    => $_POST['bak_frekuensi_sekarang']    ?? '',
        'bak_warna_sebelum'         => $_POST['bak_warna_sebelum']         ?? '',
        'bak_warna_sekarang'        => $_POST['bak_warna_sekarang']        ?? '',
        'bak_bau_sebelum'           => $_POST['bak_bau_sebelum']           ?? '',
        'bak_bau_sekarang'          => $_POST['bak_bau_sekarang']          ?? '',
        'bak_kesulitan_sebelum'     => $_POST['bak_kesulitan_sebelum']     ?? '',
        'bak_kesulitan_sekarang'    => $_POST['bak_kesulitan_sekarang']    ?? '',
        'bak_obat_sebelum'          => $_POST['bak_obat_sebelum']          ?? '',
        'bak_obat_sekarang'         => $_POST['bak_obat_sekarang']         ?? '',
        // m. Tidur
        'tidur_siang_sebelum'       => $_POST['tidur_siang_sebelum']       ?? '',
        'tidur_siang_sekarang'      => $_POST['tidur_siang_sekarang']      ?? '',
        'tidur_malam_sebelum'       => $_POST['tidur_malam_sebelum']       ?? '',
        'tidur_malam_sekarang'      => $_POST['tidur_malam_sekarang']      ?? '',
        'kesulitan_tidur_sebelum'   => $_POST['kesulitan_tidur_sebelum']   ?? '',
        'kesulitan_tidur_sekarang'  => $_POST['kesulitan_tidur_sekarang']  ?? '',
        'kebiasaan_tidur_sebelum'   => $_POST['kebiasaan_tidur_sebelum']   ?? '',
        'kebiasaan_tidur_sekarang'  => $_POST['kebiasaan_tidur_sekarang']  ?? '',
        // n. Personal Hygiene
        'mandi_frekuensi_sebelum'   => $_POST['mandi_frekuensi_sebelum']   ?? '',
        'mandi_frekuensi_sekarang'  => $_POST['mandi_frekuensi_sekarang']  ?? '',
        'mandi_cara_sebelum'        => $_POST['mandi_cara_sebelum']        ?? '',
        'mandi_cara_sekarang'       => $_POST['mandi_cara_sekarang']       ?? '',
        'mandi_tempat_sebelum'      => $_POST['mandi_tempat_sebelum']      ?? '',
        'mandi_tempat_sekarang'     => $_POST['mandi_tempat_sekarang']     ?? '',
        'rambut_frekuensi_sebelum'  => $_POST['rambut_frekuensi_sebelum']  ?? '',
        'rambut_frekuensi_sekarang' => $_POST['rambut_frekuensi_sekarang'] ?? '',
        'rambut_cara_sebelum'       => $_POST['rambut_cara_sebelum']       ?? '',
        'rambut_cara_sekarang'      => $_POST['rambut_cara_sekarang']      ?? '',
        'kuku_frekuensi_sebelum'    => $_POST['kuku_frekuensi_sebelum']    ?? '',
        'kuku_frekuensi_sekarang'   => $_POST['kuku_frekuensi_sekarang']   ?? '',
        'kuku_cara_sebelum'         => $_POST['kuku_cara_sebelum']         ?? '',
        'kuku_cara_sekarang'        => $_POST['kuku_cara_sekarang']        ?? '',
        'gigi_frekuensi_sebelum'    => $_POST['gigi_frekuensi_sebelum']    ?? '',
        'gigi_frekuensi_sekarang'   => $_POST['gigi_frekuensi_sekarang']   ?? '',
        'gigi_cara_sebelum'         => $_POST['gigi_cara_sebelum']         ?? '',
        'gigi_cara_sekarang'        => $_POST['gigi_cara_sekarang']        ?? '',
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
    <?php include "kmb/format_kmb_r_damar/tab.php"; ?>

    <section class="section dashboard">

        <?php include dirname(__DIR__, 2) . '/partials/notifikasi.php'; ?>
        <?php include dirname(__DIR__, 2) . '/partials/status_section.php'; ?>

        <!-- 4 POLA PENGKAJIAN FX GORDON -->

        <div class="card">
            <div class="card-body">

                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

                    <h5 class="card-title"><strong>4. Pola Pengkajian FX Gordon</strong></h5>
                    <!-- A PERSEPSI KESEHATAN -->
                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary"><strong>a. Persepsi terhadap kesehatan dan manajemen kesehatan</strong></label>
                    </div>

                    <!-- 1 -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>1. Merokok / Alkohol?</strong></label>
                        <div class="col-sm-10">
                        <textarea name="merokok" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('merokok',$existing_data) ?></textarea>    
                            </div>
                    </div>

                    <!-- 2 -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>2. Pemeriksaan kesehatan rutin?</strong></label>
                        <div class="col-sm-10">
                        <textarea name="pemeriksaan_rutin" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('pemeriksaan_rutin',$existing_data) ?></textarea>    
                            </div>
                    </div>

                    <!-- 3 -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>3. Pendapat pasien tentang keadaan kesehatannya saat ini</strong></label>
                        <div class="col-sm-10">
                        <textarea name="pendapat_kesehatan" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('pendapat_kesehatan',$existing_data) ?></textarea>    
                            </div>
                    </div>

                    <!-- 4 -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>4. Persepsi pasien tentang berat ringannya penyakit</strong></label>
                        <div class="col-sm-10">
                        <textarea name="persepsi_penyakit" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('persepsi_penyakit',$existing_data) ?></textarea>    
                            </div>
                    </div>

                    <!-- 5 -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>5. Persepsi tentang tingkat kesembuhan</strong></label>
                        <div class="col-sm-10">
                        <textarea name="tingkat_kesembuhan" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('tingkat_kesembuhan',$existing_data) ?></textarea>    
                            </div>
                    </div>

                    <!-- B POLA AKTIVITAS -->
                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary"><strong>b. Pola Aktivitas dan Latihan</strong></label>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>1. Rutinitas mandi</strong></label>
                        <div class="col-sm-10">
                            <small class="form-text" style="color:red;">kapan, bagaimana, dimana, sabun yang digunakan</small>
                            <textarea name="rutinitas_mandi" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('rutinitas_mandi',$existing_data) ?></textarea>
                            </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>2. Kebersihan sehari-hari</strong></label>
                        <div class="col-sm-10">
                            <small class="form-text" style="color:red;">pakaian dll</small>
                            <textarea name="kebersihan" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('kebersihan',$existing_data) ?></textarea>
                            </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>3. Aktivitas sehari-hari</strong></label>
                        <div class="col-sm-10">
                            <small class="form-text" style="color:red;">jenis pekerjaan, lamanya, dll</small>
                            <textarea name="aktivitas" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('aktivitas',$existing_data) ?></textarea>
                            </div>
                    </div>

                    <!-- TABEL KEMAMPUAN PERAWATAN DIRI -->
                    <div class="row mb-3">
                        <label class="col-sm-12"><strong>4. Kemampuan Perawatan Diri</strong></label>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th><strong>Aktivitas</strong></th>
                                    <th class="text-center"><strong>0</strong></th>
                                    <th class="text-center"><strong>1</strong></th>
                                    <th class="text-center"><strong>2</strong></th>
                                    <th class="text-center"><strong>3</strong></th>
                                    <th class="text-center"><strong>4</strong></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $perawatan_fields = [
                                    'mandi'      => 'Mandi',
                                    'berpakaian' => 'Berpakaian / Berdandan',
                                    'mobilisasi' => 'Mobilisasi di TT',
                                    'pindah'     => 'Pindah',
                                    'ambulasi'   => 'Ambulasi',
                                    'makan'      => 'Makan / Minum',
                                ];
                                foreach ($perawatan_fields as $name => $label): ?>
                                    <tr>
                                        <td><strong><?= $label ?></strong></td>
                                        <?php for ($i = 0; $i <= 4; $i++): ?>
                                            <td class="text-center"><input type="radio" name="<?= $name ?>" value="<?= $i ?>" <?= $ro_disabled ?>
                                                    <?= ($existing_data[$name] ?? '') == $i ? 'checked' : '' ?>></td>
                                        <?php endfor; ?>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>

                        <small class="text-muted d-block text-end">
                            Skor 0 = Mandiri &nbsp;|&nbsp; Skor 1 = Dibantu sebagian &nbsp;|&nbsp; Skor 2 = Perlu bantuan orang lain <br>
                            Skor 3 = Bantuan orang lain dan alat &nbsp;|&nbsp; Skor 4 = Tergantung
                        </small>


                        <div class="row mb-2">
                            <label class="col-sm-12 text-primary"><strong>c. Pola Kognitif dan Perceptual</strong></label>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">
                                <strong>1. Nyeri (kualitas, intensitas, durasi, skala nyeri, cara mengurangi nyeri)</strong>
                            </label>
                            <div class="col-sm-10">
                            <textarea name="nyeri" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('nyeri',$existing_data) ?></textarea>    
                                </div>
                        </div>


                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">
                                <strong>2. Fungsi panca indra (penglihatan, pendengaran, pengecapan, penghidu, perasa) menggunakan alat bantu?</strong>
                            </label>
                            <div class="col-sm-10">
                                <textarea name="panca_indra" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('panca_indra',$existing_data) ?></textarea>
                                </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">
                                <strong>3. Kemampuan berbicara</strong>
                            </label>
                            <div class="col-sm-10">
                            <textarea name="berbicara" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('berbicara',$existing_data) ?></textarea>    
                                </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">
                                <strong>4. Kemampuan membaca</strong>
                            </label>
                            <div class="col-sm-10">
                                <textarea name="membaca" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('membaca',$existing_data) ?></textarea>
                                </div>
                        </div>


                        <div class="row mb-2">
                            <label class="col-sm-12 text-primary"><strong>d. Pola Konsep Diri</strong></label>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">
                                <strong>1. Bagaimana klien memandang dirinya</strong>
                            </label>
                            <div class="col-sm-10">
                                <textarea name="konsep_diri" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('konsep_diri',$existing_data) ?></textarea>
                                </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">
                                <strong>2. Hal-hal yang disukai klien mengenai dirinya</strong>
                            </label>
                            <div class="col-sm-10">
                                <textarea name="hal_disukai" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('hal_disukai',$existing_data) ?></textarea>
                                </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">
                                <strong>3. Apakah klien dapat mengidentifikasi kekuatan dan kelemahan dirinya</strong>
                            </label>
                            <div class="col-sm-10">
                                <textarea name="kekuatan_kelemahan" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('kekuatan_kelemahan',$existing_data) ?></textarea>
                                </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">
                                <strong>4. Hal-hal yang dapat dilakukan klien secara baik</strong>
                            </label>
                            <div class="col-sm-10">
                                <textarea name="kemampuan_baik" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('kemampuan_baik',$existing_data) ?></textarea>
                                </div>
                        </div>

                        <div class="row mb-2">
                            <label class="col-sm-12 text-primary"><strong>e. Pola Koping</strong></label>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">
                                <strong>1. Masalah utama selama masuk RS (keuangan, dll)</strong>
                            </label>
                            <div class="col-sm-10">
                                <textarea name="masalah_rs" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('masalah_rs',$existing_data) ?></textarea>
                                </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">
                                <strong>2. Kehilangan atau perubahan yang terjadi sebelumnya</strong>
                            </label>
                            <div class="col-sm-10">
                                <textarea name="kehilangan" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('kehilangan',$existing_data) ?></textarea>
                                </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">
                                <strong>3. Takut terhadap kekerasan</strong>
                            </label>
                            <div class="col-sm-10">
                                <textarea name="takut_kekerasan" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('takut_kekerasan',$existing_data) ?></textarea>
                                </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">
                                <strong>4. Pandangan terhadap masa depan</strong>
                            </label>
                            <div class="col-sm-10">
                                <textarea name="masa_depan" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('masa_depan',$existing_data) ?></textarea>
                               </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">
                                <strong>5. Mekanisme koping saat menghadapi masalah</strong>
                            </label>
                            <div class="col-sm-10">
                                <textarea name="mekanisme_koping" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('mekanisme_koping',$existing_data) ?></textarea>
                                </div>
                        </div>
                        <div class="row mb-2">
                            <label class="col-sm-12 text-primary"><strong>f. Pola Seksual - Reproduksi</strong></label>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">
                                <strong>1. Masalah menstruasi</strong>
                            </label>
                            <div class="col-sm-10">
                                <textarea name="masalah_menstruasi" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('masalah_menstruasi',$existing_data) ?></textarea>
                                </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">
                                <strong>2. Papsmear terakhir</strong>
                            </label>
                            <div class="col-sm-10">
                                <textarea name="papsmear" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('papsmear',$existing_data) ?></textarea>
                                </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">
                                <strong>3. Perawatan payudara setiap bulan</strong>
                            </label>
                            <div class="col-sm-10">
                                <textarea name="perawatan_payudara" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('perawatan_payudara',$existing_data) ?></textarea>
                                </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">
                                <strong>4. Apakah ada kesukaran dalam berhubungan seksual</strong>
                            </label>
                            <div class="col-sm-10">
                                <textarea name="kesulitan_seksual" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('kesulitan_seksual',$existing_data) ?></textarea>
                                </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">
                                <strong>5. Apakah penyakit sekarang mengganggu fungsi seksual</strong>
                            </label>
                            <div class="col-sm-10">
                                <textarea name="gangguan_seksual" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('gangguan_seksual',$existing_data) ?></textarea>
                                </div>
                        </div>

                        <div class="row mb-2">
                            <label class="col-sm-12 text-primary"><strong>g. Pola Peran Berhubungan</strong></label>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">
                                <strong>1. Peran pasien dalam keluarga dan masyarakat</strong>
                            </label>
                            <div class="col-sm-10">
                                <textarea name="peran_pasien" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('peran_pasien',$existing_data) ?></textarea>
                                </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">
                                <strong>2. Apakah klien punya teman dekat</strong>
                            </label>
                            <div class="col-sm-10">
                                <textarea name="teman_dekat" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('teman_dekat',$existing_data) ?></textarea>
                                </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">
                                <strong>3. Siapa yang dipercaya membantu klien saat kesulitan</strong>
                            </label>
                            <div class="col-sm-10">
                                <textarea name="orang_terpercaya" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('orang_terpercaya',$existing_data) ?></textarea>
                                </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">
                                <strong>4. Apakah klien ikut kegiatan masyarakat? Bagaimana keterlibatannya</strong>
                            </label>
                            <div class="col-sm-10">
                                <textarea name="kegiatan_masyarakat" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('kegiatan_masyarakat',$existing_data) ?></textarea>
                                </div>
                        </div>

                        <div class="row mb-2">
                            <label class="col-sm-12 text-primary"><strong>h. Pola Nilai dan Kepercayaan</strong></label>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">
                                <strong>1. Apakah klien menganut suatu agama?</strong>
                            </label>
                            <div class="col-sm-10">
                                <textarea name="agama_klien" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('agama_klien',$existing_data) ?></textarea>
                                </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">
                                <strong>2. Menurut agama klien bagaimana hubungan manusia dengan pencipta-Nya?</strong>
                            </label>
                            <div class="col-sm-10">
                                <textarea name="hubungan_tuhan" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('hubungan_tuhan',$existing_data) ?></textarea>
                                </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">
                                <strong>3. Dalam keadaan sakit apakah klien mengalami hambatan dalam ibadah?</strong>
                            </label>
                            <div class="col-sm-10">
                                <textarea name="hambatan_ibadah" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('hambatan_ibadah',$existing_data) ?></textarea>
                                </div>
                        </div>

                        <div class="row mb-2">
                            <label class="col-sm-12 text-primary"><strong>i. Pola Nutrisi</strong></label>
                        </div>

                        <div class="row mb-4">
                            <div class="col-sm-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th><strong>No</strong></th>
                                                <th><strong>Kondisi</strong></th>
                                                <th><strong>Sebelum</strong></th>
                                                <th><strong>Saat Ini</strong></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td><strong>Frekuensi Makan</strong></td>

                                                <td>
                                                    <?php if ($ro): ?>
                                                        <div class="readonly-text">
                                                            <?= nl2br(htmlspecialchars($existing_data["frekuensi_makan_sebelum"] ?? "")) ?>
                                                        </div>
                                                    <?php else: ?>
                                                        <textarea
                                                            class="form-control auto-resize"
                                                            name="frekuensi_makan_sebelum"
                                                            rows="2"
                                                            style="resize:none; overflow:hidden;"
                                                            oninput="autoResizeTextarea(this)"
                                                        ><?= htmlspecialchars($existing_data["frekuensi_makan_sebelum"] ?? "") ?></textarea>
                                                    <?php endif; ?>
                                                </td>

                                                <td>
                                                    <?php if ($ro): ?>
                                                        <div class="readonly-text">
                                                            <?= nl2br(htmlspecialchars($existing_data["frekuensi_makan_sekarang"] ?? "")) ?>
                                                        </div>
                                                    <?php else: ?>
                                                        <textarea
                                                            class="form-control auto-resize"
                                                            name="frekuensi_makan_sekarang"
                                                            rows="2"
                                                            style="resize:none; overflow:hidden;"
                                                            oninput="autoResizeTextarea(this)"
                                                        ><?= htmlspecialchars($existing_data["frekuensi_makan_sekarang"] ?? "") ?></textarea>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                            <td>2</td>
                                            <td><strong>Selera Makan</strong></td>
                                            <td>
                                                <textarea
                                                    class="form-control auto-resize"
                                                    name="selera_makan_sebelum"
                                                    rows="2"
                                                    style="resize:none; overflow:hidden;"
                                                    oninput="autoResizeTextarea(this)"
                                                    <?= $ro ?>
                                                ><?= htmlspecialchars($existing_data["selera_makan_sebelum"] ?? "") ?></textarea>
                                            </td>
                                            <td>
                                                <textarea
                                                    class="form-control auto-resize"
                                                    name="selera_makan_sekarang"
                                                    rows="2"
                                                    style="resize:none; overflow:hidden;"
                                                    oninput="autoResizeTextarea(this)"
                                                    <?= $ro ?>
                                                ><?= htmlspecialchars($existing_data["selera_makan_sekarang"] ?? "") ?></textarea>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>3</td>
                                            <td><strong>Menu Makanan</strong></td>
                                            <td>
                                                <textarea
                                                    class="form-control auto-resize"
                                                    name="menu_makan_sebelum"
                                                    rows="2"
                                                    style="resize:none; overflow:hidden;"
                                                    oninput="autoResizeTextarea(this)"
                                                    <?= $ro ?>
                                                ><?= htmlspecialchars($existing_data["menu_makan_sebelum"] ?? "") ?></textarea>
                                            </td>
                                            <td>
                                                <textarea
                                                    class="form-control auto-resize"
                                                    name="menu_makan_sekarang"
                                                    rows="2"
                                                    style="resize:none; overflow:hidden;"
                                                    oninput="autoResizeTextarea(this)"
                                                    <?= $ro ?>
                                                ><?= htmlspecialchars($existing_data["menu_makan_sekarang"] ?? "") ?></textarea>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>4</td>
                                            <td><strong>Ritual Saat Makan</strong></td>
                                            <td>
                                                <textarea
                                                    class="form-control auto-resize"
                                                    name="ritual_makan_sebelum"
                                                    rows="2"
                                                    style="resize:none; overflow:hidden;"
                                                    oninput="autoResizeTextarea(this)"
                                                    <?= $ro ?>
                                                ><?= htmlspecialchars($existing_data["ritual_makan_sebelum"] ?? "") ?></textarea>
                                            </td>
                                            <td>
                                                <textarea
                                                    class="form-control auto-resize"
                                                    name="ritual_makan_sekarang"
                                                    rows="2"
                                                    style="resize:none; overflow:hidden;"
                                                    oninput="autoResizeTextarea(this)"
                                                    <?= $ro ?>
                                                ><?= htmlspecialchars($existing_data["ritual_makan_sekarang"] ?? "") ?></textarea>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>5</td>
                                            <td><strong>Bantuan Makan Parenteral</strong></td>
                                            <td>
                                                <textarea
                                                    class="form-control auto-resize"
                                                    name="bantuan_makan_sebelum"
                                                    rows="2"
                                                    style="resize:none; overflow:hidden;"
                                                    oninput="autoResizeTextarea(this)"
                                                    <?= $ro ?>
                                                ><?= htmlspecialchars($existing_data["bantuan_makan_sebelum"] ?? "") ?></textarea>
                                            </td>
                                            <td>
                                                <textarea
                                                    class="form-control auto-resize"
                                                    name="bantuan_makan_sekarang"
                                                    rows="2"
                                                    style="resize:none; overflow:hidden;"
                                                    oninput="autoResizeTextarea(this)"
                                                    <?= $ro ?>
                                                ><?= htmlspecialchars($existing_data["bantuan_makan_sekarang"] ?? "") ?></textarea>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>


                        <div class="row mb-2">
                            <label class="col-sm-12 text-primary"><strong>j. Cairan</strong></label>
                        </div>

                        <div class="row mb-4">
                            <div class="col-sm-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th><strong>No</strong></th>
                                                <th><strong>Kondisi</strong></th>
                                                <th><strong>Sebelum Sakit</strong></th>
                                                <th><strong>Saat Ini</strong></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                            <td>1</td>
                                            <td><strong>Jenis Minuman</strong></td>
                                            <td>
                                                <textarea
                                                    class="form-control auto-resize"
                                                    name="jenis_minum_sebelum"
                                                    rows="2"
                                                    style="resize:none; overflow:hidden;"
                                                    oninput="autoResizeTextarea(this)"
                                                    <?= $ro ?>
                                                ><?= htmlspecialchars($existing_data["jenis_minum_sebelum"] ?? "") ?></textarea>
                                            </td>
                                            <td>
                                                <textarea
                                                    class="form-control auto-resize"
                                                    name="jenis_minum_sekarang"
                                                    rows="2"
                                                    style="resize:none; overflow:hidden;"
                                                    oninput="autoResizeTextarea(this)"
                                                    <?= $ro ?>
                                                ><?= htmlspecialchars($existing_data["jenis_minum_sekarang"] ?? "") ?></textarea>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>2</td>
                                            <td><strong>Jumlah Cairan</strong></td>
                                            <td>
                                                <textarea
                                                    class="form-control auto-resize"
                                                    name="jumlah_cairan_sebelum"
                                                    rows="2"
                                                    style="resize:none; overflow:hidden;"
                                                    oninput="autoResizeTextarea(this)"
                                                    <?= $ro ?>
                                                ><?= htmlspecialchars($existing_data["jumlah_cairan_sebelum"] ?? "") ?></textarea>
                                            </td>
                                            <td>
                                                <textarea
                                                    class="form-control auto-resize"
                                                    name="jumlah_cairan_sekarang"
                                                    rows="2"
                                                    style="resize:none; overflow:hidden;"
                                                    oninput="autoResizeTextarea(this)"
                                                    <?= $ro ?>
                                                ><?= htmlspecialchars($existing_data["jumlah_cairan_sekarang"] ?? "") ?></textarea>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>3</td>
                                            <td><strong>Bantuan Cairan Parenteral</strong></td>
                                            <td>
                                                <textarea
                                                    class="form-control auto-resize"
                                                    name="bantuan_cairan_sebelum"
                                                    rows="2"
                                                    style="resize:none; overflow:hidden;"
                                                    oninput="autoResizeTextarea(this)"
                                                    <?= $ro ?>
                                                ><?= htmlspecialchars($existing_data["bantuan_cairan_sebelum"] ?? "") ?></textarea>
                                            </td>
                                            <td>
                                                <textarea
                                                    class="form-control auto-resize"
                                                    name="bantuan_cairan_sekarang"
                                                    rows="2"
                                                    style="resize:none; overflow:hidden;"
                                                    oninput="autoResizeTextarea(this)"
                                                    <?= $ro ?>
                                                ><?= htmlspecialchars($existing_data["bantuan_cairan_sekarang"] ?? "") ?></textarea>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                        <div class="row mb-2">
                            <label class="col-sm-12 text-primary"><strong>k. Pola Eliminasi BAB</strong></label>
                        </div>
                        <div class="row mb-4">
                            <div class="col-sm-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th><strong>No</strong></th>
                                                <th><strong>Kondisi</strong></th>
                                                <th><strong>Sebelum Sakit</strong></th>
                                                <th><strong>Saat Ini</strong></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                            <td>1</td>
                                            <td><strong>Frekuensi (Waktu)</strong></td>
                                            <td><textarea class="form-control auto-resize" name="bab_frekuensi_sebelum" rows="2" style="resize:none; overflow:hidden;" oninput="autoResizeTextarea(this)" <?= $ro ?>><?= htmlspecialchars($existing_data["bab_frekuensi_sebelum"] ?? "") ?></textarea></td>
                                            <td><textarea class="form-control auto-resize" name="bab_frekuensi_sekarang" rows="2" style="resize:none; overflow:hidden;" oninput="autoResizeTextarea(this)" <?= $ro ?>><?= htmlspecialchars($existing_data["bab_frekuensi_sekarang"] ?? "") ?></textarea></td>
                                        </tr>

                                        <tr>
                                            <td>2</td>
                                            <td><strong>Konsistensi</strong></td>
                                            <td><textarea class="form-control auto-resize" name="bab_konsistensi_sebelum" rows="2" style="resize:none; overflow:hidden;" oninput="autoResizeTextarea(this)" <?= $ro ?>><?= htmlspecialchars($existing_data["bab_konsistensi_sebelum"] ?? "") ?></textarea></td>
                                            <td><textarea class="form-control auto-resize" name="bab_konsistensi_sekarang" rows="2" style="resize:none; overflow:hidden;" oninput="autoResizeTextarea(this)" <?= $ro ?>><?= htmlspecialchars($existing_data["bab_konsistensi_sekarang"] ?? "") ?></textarea></td>
                                        </tr>

                                        <tr>
                                            <td>3</td>
                                            <td><strong>Warna</strong></td>
                                            <td><textarea class="form-control auto-resize" name="bab_warna_sebelum" rows="2" style="resize:none; overflow:hidden;" oninput="autoResizeTextarea(this)" <?= $ro ?>><?= htmlspecialchars($existing_data["bab_warna_sebelum"] ?? "") ?></textarea></td>
                                            <td><textarea class="form-control auto-resize" name="bab_warna_sekarang" rows="2" style="resize:none; overflow:hidden;" oninput="autoResizeTextarea(this)" <?= $ro ?>><?= htmlspecialchars($existing_data["bab_warna_sekarang"] ?? "") ?></textarea></td>
                                        </tr>

                                        <tr>
                                            <td>4</td>
                                            <td><strong>Bau</strong></td>
                                            <td><textarea class="form-control auto-resize" name="bab_bau_sebelum" rows="2" style="resize:none; overflow:hidden;" oninput="autoResizeTextarea(this)" <?= $ro ?>><?= htmlspecialchars($existing_data["bab_bau_sebelum"] ?? "") ?></textarea></td>
                                            <td><textarea class="form-control auto-resize" name="bab_bau_sekarang" rows="2" style="resize:none; overflow:hidden;" oninput="autoResizeTextarea(this)" <?= $ro ?>><?= htmlspecialchars($existing_data["bab_bau_sekarang"] ?? "") ?></textarea></td>
                                        </tr>

                                        <tr>
                                            <td>5</td>
                                            <td><strong>Kesulitan saat BAB</strong></td>
                                            <td><textarea class="form-control auto-resize" name="bab_kesulitan_sebelum" rows="2" style="resize:none; overflow:hidden;" oninput="autoResizeTextarea(this)" <?= $ro ?>><?= htmlspecialchars($existing_data["bab_kesulitan_sebelum"] ?? "") ?></textarea></td>
                                            <td><textarea class="form-control auto-resize" name="bab_kesulitan_sekarang" rows="2" style="resize:none; overflow:hidden;" oninput="autoResizeTextarea(this)" <?= $ro ?>><?= htmlspecialchars($existing_data["bab_kesulitan_sekarang"] ?? "") ?></textarea></td>
                                        </tr>

                                        <tr>
                                            <td>6</td>
                                            <td><strong>Penggunaan Obat Pencahar</strong></td>
                                            <td><textarea class="form-control auto-resize" name="bab_obat_sebelum" rows="2" style="resize:none; overflow:hidden;" oninput="autoResizeTextarea(this)" <?= $ro ?>><?= htmlspecialchars($existing_data["bab_obat_sebelum"] ?? "") ?></textarea></td>
                                            <td><textarea class="form-control auto-resize" name="bab_obat_sekarang" rows="2" style="resize:none; overflow:hidden;" oninput="autoResizeTextarea(this)" <?= $ro ?>><?= htmlspecialchars($existing_data["bab_obat_sekarang"] ?? "") ?></textarea></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>

                        <hr>

                        <div class="row mb-2">
                            <label class="col-sm-12 text-primary"><strong>l. Pola Eliminasi BAK</strong></label>
                        </div>
                        <div class="row mb-4">
                            <div class="col-sm-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th><strong>No</strong></th>
                                                <th><strong>Kondisi</strong></th>
                                                <th><strong>Sebelum Sakit</strong></th>
                                                <th><strong>Saat Ini</strong></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                            <td>1</td>
                                            <td><strong>Frekuensi (Waktu)</strong></td>
                                            <td><textarea class="form-control auto-resize" name="bak_frekuensi_sebelum" rows="2" style="resize:none; overflow:hidden;" oninput="autoResizeTextarea(this)" <?= $ro ?>><?= htmlspecialchars($existing_data["bak_frekuensi_sebelum"] ?? "") ?></textarea></td>
                                            <td><textarea class="form-control auto-resize" name="bak_frekuensi_sekarang" rows="2" style="resize:none; overflow:hidden;" oninput="autoResizeTextarea(this)" <?= $ro ?>><?= htmlspecialchars($existing_data["bak_frekuensi_sekarang"] ?? "") ?></textarea></td>
                                        </tr>

                                        <tr>
                                            <td>2</td>
                                            <td><strong>Warna</strong></td>
                                            <td><textarea class="form-control auto-resize" name="bak_warna_sebelum" rows="2" style="resize:none; overflow:hidden;" oninput="autoResizeTextarea(this)" <?= $ro ?>><?= htmlspecialchars($existing_data["bak_warna_sebelum"] ?? "") ?></textarea></td>
                                            <td><textarea class="form-control auto-resize" name="bak_warna_sekarang" rows="2" style="resize:none; overflow:hidden;" oninput="autoResizeTextarea(this)" <?= $ro ?>><?= htmlspecialchars($existing_data["bak_warna_sekarang"] ?? "") ?></textarea></td>
                                        </tr>

                                        <tr>
                                            <td>3</td>
                                            <td><strong>Bau</strong></td>
                                            <td><textarea class="form-control auto-resize" name="bak_bau_sebelum" rows="2" style="resize:none; overflow:hidden;" oninput="autoResizeTextarea(this)" <?= $ro ?>><?= htmlspecialchars($existing_data["bak_bau_sebelum"] ?? "") ?></textarea></td>
                                            <td><textarea class="form-control auto-resize" name="bak_bau_sekarang" rows="2" style="resize:none; overflow:hidden;" oninput="autoResizeTextarea(this)" <?= $ro ?>><?= htmlspecialchars($existing_data["bak_bau_sekarang"] ?? "") ?></textarea></td>
                                        </tr>

                                        <tr>
                                            <td>4</td>
                                            <td><strong>Kesulitan saat BAK</strong></td>
                                            <td><textarea class="form-control auto-resize" name="bak_kesulitan_sebelum" rows="2" style="resize:none; overflow:hidden;" oninput="autoResizeTextarea(this)" <?= $ro ?>><?= htmlspecialchars($existing_data["bak_kesulitan_sebelum"] ?? "") ?></textarea></td>
                                            <td><textarea class="form-control auto-resize" name="bak_kesulitan_sekarang" rows="2" style="resize:none; overflow:hidden;" oninput="autoResizeTextarea(this)" <?= $ro ?>><?= htmlspecialchars($existing_data["bak_kesulitan_sekarang"] ?? "") ?></textarea></td>
                                        </tr>

                                        <tr>
                                            <td>5</td>
                                            <td><strong>Penggunaan Obat Diuretik</strong></td>
                                            <td><textarea class="form-control auto-resize" name="bak_obat_sebelum" rows="2" style="resize:none; overflow:hidden;" oninput="autoResizeTextarea(this)" <?= $ro ?>><?= htmlspecialchars($existing_data["bak_obat_sebelum"] ?? "") ?></textarea></td>
                                            <td><textarea class="form-control auto-resize" name="bak_obat_sekarang" rows="2" style="resize:none; overflow:hidden;" oninput="autoResizeTextarea(this)" <?= $ro ?>><?= htmlspecialchars($existing_data["bak_obat_sekarang"] ?? "") ?></textarea></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>

                        <div class="row mb-2">
                            <label class="col-sm-12 text-primary"><strong>m. Pola Tidur</strong></label>
                        </div>
                        <div class="row mb-4">
                            <div class="col-sm-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th><strong>No</strong></th>
                                                <th><strong>Kondisi</strong></th>
                                                <th><strong>Sebelum Sakit</strong></th>
                                                <th><strong>Saat Ini</strong></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td rowspan="2">1</td>
                                                <td><strong>Jam Tidur - Siang</strong></td>
                                                <td><textarea class="form-control auto-resize" name="tidur_siang_sebelum" rows="2" style="resize:none; overflow:hidden;" oninput="autoResizeTextarea(this)" <?= $ro ?>><?= htmlspecialchars($existing_data["tidur_siang_sebelum"] ?? "") ?></textarea></td>
                                                <td><textarea class="form-control auto-resize" name="tidur_siang_sekarang" rows="2" style="resize:none; overflow:hidden;" oninput="autoResizeTextarea(this)" <?= $ro ?>><?= htmlspecialchars($existing_data["tidur_siang_sekarang"] ?? "") ?></textarea></td>
                                            </tr>

                                            <tr>
                                                <td><strong>Jam Tidur - Malam</strong></td>
                                                <td><textarea class="form-control auto-resize" name="tidur_malam_sebelum" rows="2" style="resize:none; overflow:hidden;" oninput="autoResizeTextarea(this)" <?= $ro ?>><?= htmlspecialchars($existing_data["tidur_malam_sebelum"] ?? "") ?></textarea></td>
                                                <td><textarea class="form-control auto-resize" name="tidur_malam_sekarang" rows="2" style="resize:none; overflow:hidden;" oninput="autoResizeTextarea(this)" <?= $ro ?>><?= htmlspecialchars($existing_data["tidur_malam_sekarang"] ?? "") ?></textarea></td>
                                            </tr>

                                            <tr>
                                                <td>2</td>
                                                <td><strong>Kesulitan Tidur</strong></td>
                                                <td><textarea class="form-control auto-resize" name="kesulitan_tidur_sebelum" rows="2" style="resize:none; overflow:hidden;" oninput="autoResizeTextarea(this)" <?= $ro ?>><?= htmlspecialchars($existing_data["kesulitan_tidur_sebelum"] ?? "") ?></textarea></td>
                                                <td><textarea class="form-control auto-resize" name="kesulitan_tidur_sekarang" rows="2" style="resize:none; overflow:hidden;" oninput="autoResizeTextarea(this)" <?= $ro ?>><?= htmlspecialchars($existing_data["kesulitan_tidur_sekarang"] ?? "") ?></textarea></td>
                                            </tr>

                                            <tr>
                                                <td>3</td>
                                                <td><strong>Kebiasaan Sebelum Tidur</strong></td>
                                                <td><textarea class="form-control auto-resize" name="kebiasaan_tidur_sebelum" rows="2" style="resize:none; overflow:hidden;" oninput="autoResizeTextarea(this)" <?= $ro ?>><?= htmlspecialchars($existing_data["kebiasaan_tidur_sebelum"] ?? "") ?></textarea></td>
                                                <td><textarea class="form-control auto-resize" name="kebiasaan_tidur_sekarang" rows="2" style="resize:none; overflow:hidden;" oninput="autoResizeTextarea(this)" <?= $ro ?>><?= htmlspecialchars($existing_data["kebiasaan_tidur_sekarang"] ?? "") ?></textarea></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>



                        <div class="row mb-2">
                            <label class="col-sm-12 text-primary"><strong>n. Pola Personal Hygiene</strong></label>
                        </div>
                        <div class="row mb-4">
                            <div class="col-sm-12">
                                <div class="table">
                                    <table class="table table-bordered table-hover mb-1">
                                        <thead class="table-light">
                                            <tr>
                                                <th><strong>No</strong></th>
                                                <th><strong>Kondisi</strong></th>
                                                <th><strong>Sebelum Sakit</strong></th>
                                                <th><strong>Saat Ini</strong></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td rowspan="3">1</td>
                                                <td><strong>Mandi - Frekuensi</strong></td>
                                                <td><textarea class="form-control auto-resize" name="mandi_frekuensi_sebelum" rows="2" style="resize:none; overflow:hidden;" oninput="autoResizeTextarea(this)" <?= $ro ?>><?= htmlspecialchars($existing_data["mandi_frekuensi_sebelum"] ?? "") ?></textarea></td>
                                                <td><textarea class="form-control auto-resize" name="mandi_frekuensi_sekarang" rows="2" style="resize:none; overflow:hidden;" oninput="autoResizeTextarea(this)" <?= $ro ?>><?= htmlspecialchars($existing_data["mandi_frekuensi_sekarang"] ?? "") ?></textarea></td>
                                            </tr>

                                            <tr>
                                                <td><strong>Mandi - Cara</strong></td>
                                                <td><textarea class="form-control auto-resize" name="mandi_cara_sebelum" rows="2" style="resize:none; overflow:hidden;" oninput="autoResizeTextarea(this)" <?= $ro ?>><?= htmlspecialchars($existing_data["mandi_cara_sebelum"] ?? "") ?></textarea></td>
                                                <td><textarea class="form-control auto-resize" name="mandi_cara_sekarang" rows="2" style="resize:none; overflow:hidden;" oninput="autoResizeTextarea(this)" <?= $ro ?>><?= htmlspecialchars($existing_data["mandi_cara_sekarang"] ?? "") ?></textarea></td>
                                            </tr>

                                            <tr>
                                                <td><strong>Mandi - Tempat</strong></td>
                                                <td><textarea class="form-control auto-resize" name="mandi_tempat_sebelum" rows="2" style="resize:none; overflow:hidden;" oninput="autoResizeTextarea(this)" <?= $ro ?>><?= htmlspecialchars($existing_data["mandi_tempat_sebelum"] ?? "") ?></textarea></td>
                                                <td><textarea class="form-control auto-resize" name="mandi_tempat_sekarang" rows="2" style="resize:none; overflow:hidden;" oninput="autoResizeTextarea(this)" <?= $ro ?>><?= htmlspecialchars($existing_data["mandi_tempat_sekarang"] ?? "") ?></textarea></td>
                                            </tr>

                                            <tr>
                                                <td rowspan="2">2</td>
                                                <td><strong>Cuci Rambut - Frekuensi</strong></td>
                                                <td><textarea class="form-control auto-resize" name="rambut_frekuensi_sebelum" rows="2" style="resize:none; overflow:hidden;" oninput="autoResizeTextarea(this)" <?= $ro ?>><?= htmlspecialchars($existing_data["rambut_frekuensi_sebelum"] ?? "") ?></textarea></td>
                                                <td><textarea class="form-control auto-resize" name="rambut_frekuensi_sekarang" rows="2" style="resize:none; overflow:hidden;" oninput="autoResizeTextarea(this)" <?= $ro ?>><?= htmlspecialchars($existing_data["rambut_frekuensi_sekarang"] ?? "") ?></textarea></td>
                                            </tr>

                                            <tr>
                                                <td><strong>Cuci Rambut - Cara</strong></td>
                                                <td><textarea class="form-control auto-resize" name="rambut_cara_sebelum" rows="2" style="resize:none; overflow:hidden;" oninput="autoResizeTextarea(this)" <?= $ro ?>><?= htmlspecialchars($existing_data["rambut_cara_sebelum"] ?? "") ?></textarea></td>
                                                <td><textarea class="form-control auto-resize" name="rambut_cara_sekarang" rows="2" style="resize:none; overflow:hidden;" oninput="autoResizeTextarea(this)" <?= $ro ?>><?= htmlspecialchars($existing_data["rambut_cara_sekarang"] ?? "") ?></textarea></td>
                                            </tr>

                                            <tr>
                                                <td rowspan="2">3</td>
                                                <td><strong>Gunting Kuku - Frekuensi</strong></td>
                                                <td><textarea class="form-control auto-resize" name="kuku_frekuensi_sebelum" rows="2" style="resize:none; overflow:hidden;" oninput="autoResizeTextarea(this)" <?= $ro ?>><?= htmlspecialchars($existing_data["kuku_frekuensi_sebelum"] ?? "") ?></textarea></td>
                                                <td><textarea class="form-control auto-resize" name="kuku_frekuensi_sekarang" rows="2" style="resize:none; overflow:hidden;" oninput="autoResizeTextarea(this)" <?= $ro ?>><?= htmlspecialchars($existing_data["kuku_frekuensi_sekarang"] ?? "") ?></textarea></td>
                                            </tr>

                                            <tr>
                                                <td><strong>Gunting Kuku - Cara</strong></td>
                                                <td><textarea class="form-control auto-resize" name="kuku_cara_sebelum" rows="2" style="resize:none; overflow:hidden;" oninput="autoResizeTextarea(this)" <?= $ro ?>><?= htmlspecialchars($existing_data["kuku_cara_sebelum"] ?? "") ?></textarea></td>
                                                <td><textarea class="form-control auto-resize" name="kuku_cara_sekarang" rows="2" style="resize:none; overflow:hidden;" oninput="autoResizeTextarea(this)" <?= $ro ?>><?= htmlspecialchars($existing_data["kuku_cara_sekarang"] ?? "") ?></textarea></td>
                                            </tr>

                                            <tr>
                                                <td rowspan="2">4</td>
                                                <td><strong>Gosok Gigi - Frekuensi</strong></td>
                                                <td><textarea class="form-control auto-resize" name="gigi_frekuensi_sebelum" rows="2" style="resize:none; overflow:hidden;" oninput="autoResizeTextarea(this)" <?= $ro ?>><?= htmlspecialchars($existing_data["gigi_frekuensi_sebelum"] ?? "") ?></textarea></td>
                                                <td><textarea class="form-control auto-resize" name="gigi_frekuensi_sekarang" rows="2" style="resize:none; overflow:hidden;" oninput="autoResizeTextarea(this)" <?= $ro ?>><?= htmlspecialchars($existing_data["gigi_frekuensi_sekarang"] ?? "") ?></textarea></td>
                                            </tr>

                                            <tr>
                                                <td><strong>Gosok Gigi - Cara</strong></td>
                                                <td><textarea class="form-control auto-resize" name="gigi_cara_sebelum" rows="2" style="resize:none; overflow:hidden;" oninput="autoResizeTextarea(this)" <?= $ro ?>><?= htmlspecialchars($existing_data["gigi_cara_sebelum"] ?? "") ?></textarea></td>
                                                <td><textarea class="form-control auto-resize" name="gigi_cara_sekarang" rows="2" style="resize:none; overflow:hidden;" oninput="autoResizeTextarea(this)" <?= $ro ?>><?= htmlspecialchars($existing_data["gigi_cara_sekarang"] ?? "") ?></textarea></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- TOMBOL SIMPAN (hanya mahasiswa) -->
                        <?php if (!$is_dosen): ?>
                            <div class="row mb-3">
                                <div class="col-sm-12 d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary" <?= $ro_disabled ?>>Simpan Data</button>
                                </div>
                            </div>
                        <?php endif; ?>

                <script>
                function autoResizeTextarea(el) {
                    el.style.height = "auto";
                    el.style.height = el.scrollHeight + "px";
                }

                document.addEventListener("DOMContentLoaded", function () {
                    document.querySelectorAll(".auto-resize").forEach(function(el) {
                        autoResizeTextarea(el);
                    });
                });
                </script>

                </form>



            </div>
        </div>
        </div>

        <?php include dirname(__DIR__, 2) . '/partials/footer_form.php'; ?>
        </div>
        </div>

    </section>
</main>