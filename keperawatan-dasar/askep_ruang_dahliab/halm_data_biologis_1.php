<?php
$form_id       = 31;
$section_name  = 'data_biologis_1';
$section_label = 'Data Biologis 1';
include dirname(__DIR__, 2) . '/partials/init_section.php';

// Decode checkbox fields
$checkbox_fields = [''];
foreach ($checkbox_fields as $cf) {
    $existing_data[$cf] = isset($existing_data[$cf])
        ? (json_decode($existing_data[$cf], true) ?? [])
        : [];
}

// =============================================
// HANDLE POST - MAHASISWA
// =============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $level === 'Mahasiswa') {
    if (isLocked($submission)) {
        redirectWithMessage($_SERVER['REQUEST_URI'], 'error', 'Data tidak dapat diubah karena sedang dalam proses review.');
    }
    $data = [   // i. Nutrisi
        'frekuensi_makan_sebelum'   => $_POST['frekuensi_makan_sebelum']   ?? '',
        'frekuensi_makan_sekarang'  => $_POST['frekuensi_makan_sekarang']  ?? '',
        'selera_makan_sebelum'      => $_POST['selera_makan_sebelum']      ?? '',
        'selera_makan_sekarang'     => $_POST['selera_makan_sekarang']     ?? '',
        'porsi_makan_sekarang'     => $_POST['porsi_makan_sekarang']     ?? '',
        'porsi_makan_sebelum'     => $_POST['porsi_makan_sebelum']     ?? '',
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
        'berpakaian'        => $_POST['berpakaian']        ?? '',
    ];
    $text_fields = ['kesimentrisan_wajah', 'kelainan_kepala', 'warna_bibir', 'bau_mulut', 'jvp_keterangan', 'konjungtiva', 'nyeri_tekan', 'kelainan_rambut', 'warna_penyebaran', 'bentuk_kepala', 'nyeridada', 'benjolan_kepala', 'penyebaran_merata', 'warna_rambut', 'rambut_dicabut', 'kelainan_rambut', 'ekspresi_wajah', 'simetris_wajah', 'udema_wajah', 'kelainan_wajah', 'penglihatan', 'visus_kanan', 'visus_kiri', 'lapang_pandang', 'keadaan_mata', 'lesi_mata', 'sclera', 'pupil', 'bola_mata', 'kelainan_mata', 'pendengaran_kiri', 'pendengaran_kanan', 'nyeri_Kiri', 'nyeri_kanan', 'serumen', 'kelainan_telinga', 'bau', 'sekresi', 'warna_hidung', 'mukosa_hidung', 'pembengkakan', 'cuping_hidung', 'kelainan_hidung', 'bibir', 'simetris', 'kelembaban', 'caries', 'jumlah_gigi', 'warna_gigi', 'gigi_palsu_jumlah', 'letak', 'lidah', 'lesi_lidah', 'panas/dingin', 'asampahit', 'manis', 'refleks', 'tonsil', 'sekret_mulut', 'sekret_mulut_warna', 'leher_simetris', 'kelenjar', 'jvp', 'refleks_menelan', 'kelainan_leher'];
    foreach ($text_fields as $f) {
        $data[$f] = $_POST[$f] ?? '';
    }
    $checkbox_fields = [''];
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
    <?php include "keperawatan-dasar/askep_ruang_dahliab/tab.php"; ?>

    <section class="section dashboard">

        <?php include dirname(__DIR__, 2) . '/partials/notifikasi.php'; ?>
        <?php include dirname(__DIR__, 2) . '/partials/status_section.php'; ?>

         <div class="card">
            <div class="card-body">
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

                    <h5 class="card-title"><strong>e. Pola Aktivitas sehari-hari</strong></h5>

                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary"><strong>1. Pola Nutrisi</strong></label>
                    </div>

                    <div class="row mb-4">
                        <div class="col-sm-11">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th><strong>No</strong></th>
                                            <th><strong>Kondisi</strong></th>
                                            <th><strong>Sebelum sakit</strong></th>
                                            <th><strong>Saat Ini</strong></th>
                                        </tr>
                                    </thead>
                                   <tbody>
    <?php
    $nutrisi_items = [
        ['no' => 1, 'label' => 'Frekuensi Makan', 'key' => 'frekuensi_makan'],
        ['no' => 2, 'label' => 'Selera Makan', 'key' => 'selera_makan'],
        ['no' => 3, 'label' => 'Porsi Makan', 'key' => 'porsi_makan'],
        ['no' => 4, 'label' => 'Menu Makanan', 'key' => 'menu_makan'],
        ['no' => 5, 'label' => 'Ritual Saat Makan', 'key' => 'ritual_makan'],
        ['no' => 6, 'label' => 'Bantuan Makan Parenteral', 'key' => 'bantuan_makan'],
    ];

    foreach ($nutrisi_items as $item):
        $key_sebelum = $item['key'] . '_sebelum';
        $key_sekarang = $item['key'] . '_sekarang';
    ?>
        <tr>
            <td class="text-center align-middle"><?= $item['no'] ?></td>
            <td class="align-middle"><strong><?= $item['label'] ?></strong></td>
            <td>
                <textarea class="form-control form-control-sm" 
                          name="<?= $key_sebelum ?>" 
                          rows="1" 
                          style="overflow:hidden; resize:none;" 
                          oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" 
                          <?= $ro ?>><?= htmlspecialchars($existing_data[$key_sebelum] ?? "") ?></textarea>
            </td>
            <td>
                <textarea class="form-control form-control-sm" 
                          name="<?= $key_sekarang ?>" 
                          rows="1" 
                          style="overflow:hidden; resize:none;" 
                          oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" 
                          <?= $ro ?>><?= htmlspecialchars($existing_data[$key_sekarang] ?? "") ?></textarea>
            </td>
        </tr>
    <?php endforeach; ?>
</tbody>
                                </table>
                            </div>

                        </div>
                    </div>


                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary"><strong>2. Cairan</strong></label>
                    </div>

                    <div class="row mb-4">
                        <div class="col-sm-11">
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
    <?php
    $cairan_items = [
        ['no' => 1, 'label' => 'Jenis Minuman', 'key' => 'jenis_minum'],
        ['no' => 2, 'label' => 'Jumlah Cairan', 'key' => 'jumlah_cairan'],
        ['no' => 3, 'label' => 'Bantuan Cairan Parenteral', 'key' => 'bantuan_cairan'],
    ];

    foreach ($cairan_items as $item):
        $key_sebelum = $item['key'] . '_sebelum';
        $key_sekarang = $item['key'] . '_sekarang';
    ?>
        <tr>
            <td class="text-center align-middle"><?= $item['no'] ?></td>
            <td class="align-middle"><strong><?= $item['label'] ?></strong></td>
            <td>
                <textarea class="form-control form-control-sm" 
                          name="<?= $key_sebelum ?>" 
                          rows="1" 
                          style="overflow:hidden; resize:none;" 
                          oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" 
                          <?= $ro ?>><?= htmlspecialchars($existing_data[$key_sebelum] ?? "") ?></textarea>
            </td>
            <td>
                <textarea class="form-control form-control-sm" 
                          name="<?= $key_sekarang ?>" 
                          rows="1" 
                          style="overflow:hidden; resize:none;" 
                          oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" 
                          <?= $ro ?>><?= htmlspecialchars($existing_data[$key_sekarang] ?? "") ?></textarea>
            </td>
        </tr>
    <?php endforeach; ?>
</tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary"><strong>3. Pola Eliminasi BAB</strong></label>
                    </div>
                    <div class="row mb-4">
                        <div class="col-sm-11">
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
    <?php
    $bab_items = [
        ['no' => 1, 'label' => 'Frekuensi (Waktu)', 'key' => 'bab_frekuensi'],
        ['no' => 2, 'label' => 'Konsistensi', 'key' => 'bab_konsistensi'],
        ['no' => 3, 'label' => 'Warna', 'key' => 'bab_warna'],
        ['no' => 4, 'label' => 'Bau', 'key' => 'bab_bau'],
        ['no' => 5, 'label' => 'Kesulitan saat BAB', 'key' => 'bab_kesulitan'],
        ['no' => 6, 'label' => 'Penggunaan Obat Pencahar', 'key' => 'bab_obat'],
    ];

    foreach ($bab_items as $item):
        $key_sebelum = $item['key'] . '_sebelum';
        $key_sekarang = $item['key'] . '_sekarang';
    ?>
        <tr>
            <td class="text-center align-middle"><?= $item['no'] ?></td>
            <td class="align-middle"><strong><?= $item['label'] ?></strong></td>
            <td>
                <textarea class="form-control form-control-sm" 
                          name="<?= $key_sebelum ?>" 
                          rows="1" 
                          style="overflow:hidden; resize:none;" 
                          oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" 
                          <?= $ro ?>><?= htmlspecialchars($existing_data[$key_sebelum] ?? "") ?></textarea>
            </td>
            <td>
                <textarea class="form-control form-control-sm" 
                          name="<?= $key_sekarang ?>" 
                          rows="1" 
                          style="overflow:hidden; resize:none;" 
                          oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" 
                          <?= $ro ?>><?= htmlspecialchars($existing_data[$key_sekarang] ?? "") ?></textarea>
            </td>
        </tr>
    <?php endforeach; ?>
</tbody>
                                </table>
                            </div>

                        </div>
                    </div>



                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary"><strong>4. Pola Eliminasi BAK</strong></label>
                    </div>
                    <div class="row mb-4">
                        <div class="col-sm-11">
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
    <?php
    $bak_items = [
        ['no' => 1, 'label' => 'Frekuensi (Waktu)', 'key' => 'bak_frekuensi'],
        ['no' => 2, 'label' => 'Warna', 'key' => 'bak_warna'],
        ['no' => 3, 'label' => 'Bau', 'key' => 'bak_bau'],
        ['no' => 4, 'label' => 'Kesulitan saat BAK', 'key' => 'bak_kesulitan'],
        ['no' => 5, 'label' => 'Penggunaan Obat Diuretik', 'key' => 'bak_obat'],
    ];

    foreach ($bak_items as $item):
        $key_sebelum = $item['key'] . '_sebelum';
        $key_sekarang = $item['key'] . '_sekarang';
    ?>
        <tr>
            <td class="text-center align-middle"><?= $item['no'] ?></td>
            <td class="align-middle"><strong><?= $item['label'] ?></strong></td>
            <td>
                <textarea class="form-control form-control-sm" 
                          name="<?= $key_sebelum ?>" 
                          rows="1" 
                          style="overflow:hidden; resize:none;" 
                          oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" 
                          <?= $ro ?>><?= htmlspecialchars($existing_data[$key_sebelum] ?? "") ?></textarea>
            </td>
            <td>
                <textarea class="form-control form-control-sm" 
                          name="<?= $key_sekarang ?>" 
                          rows="1" 
                          style="overflow:hidden; resize:none;" 
                          oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" 
                          <?= $ro ?>><?= htmlspecialchars($existing_data[$key_sekarang] ?? "") ?></textarea>
            </td>
        </tr>
    <?php endforeach; ?>
</tbody>
                                </table>
                            </div>

                        </div>
                    </div>



                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary"><strong>5. Pola Tidur</strong></label>
                    </div>
                    <div class="row mb-4">
                        <div class="col-sm-11">
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
    <!-- Row 1: Jam Tidur (Menggunakan rowspan) -->
    <?php
    $jam_tidur_items = [
        ['label' => 'Jam Tidur - Siang', 'key' => 'tidur_siang'],
        ['label' => 'Jam Tidur - Malam', 'key' => 'tidur_malam']
    ];
    
    foreach ($jam_tidur_items as $index => $item):
        $key_sebelum = $item['key'] . '_sebelum';
        $key_sekarang = $item['key'] . '_sekarang';
    ?>
        <tr>
            <?php if ($index === 0): ?>
                <td rowspan="2" class="text-center align-middle">1</td>
            <?php endif; ?>
            <td class="align-middle"><strong><?= $item['label'] ?></strong></td>
            <td>
                <textarea class="form-control form-control-sm" name="<?= $key_sebelum ?>" rows="1" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars($existing_data[$key_sebelum] ?? "") ?></textarea>
            </td>
            <td>
                <textarea class="form-control form-control-sm" name="<?= $key_sekarang ?>" rows="1" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars($existing_data[$key_sekarang] ?? "") ?></textarea>
            </td>
        </tr>
    <?php endforeach; ?>

    <!-- Row 2 & 3: Kesulitan & Kebiasaan -->
    <?php
    $tidur_lainnya = [
        ['no' => 2, 'label' => 'Kesulitan Tidur', 'key' => 'kesulitan_tidur'],
        ['no' => 3, 'label' => 'Kebiasaan Sebelum Tidur', 'key' => 'kebiasaan_tidur']
    ];

    foreach ($tidur_lainnya as $item):
        $key_sebelum = $item['key'] . '_sebelum';
        $key_sekarang = $item['key'] . '_sekarang';
    ?>
        <tr>
            <td class="text-center align-middle"><?= $item['no'] ?></td>
            <td class="align-middle"><strong><?= $item['label'] ?></strong></td>
            <td>
                <textarea class="form-control form-control-sm" name="<?= $key_sebelum ?>" rows="1" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars($existing_data[$key_sebelum] ?? "") ?></textarea>
            </td>
            <td>
                <textarea class="form-control form-control-sm" name="<?= $key_sekarang ?>" rows="1" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars($existing_data[$key_sekarang] ?? "") ?></textarea>
            </td>
        </tr>
    <?php endforeach; ?>
</tbody>
                                </table>
                            </div>

                        </div>
                    </div>



                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary"><strong>6. Pola Personal Hygiene</strong></label>
                    </div>
                    <div class="row mb-4">
                        <div class="col-sm-11">
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
    <?php
    // Definisi data untuk mempermudah perulangan
    $hygiene_data = [
        ['no' => 1, 'rowspan' => 3, 'items' => [
            ['label' => 'Mandi - Frekuensi', 'key' => 'mandi_frekuensi'],
            ['label' => 'Mandi - Cara', 'key' => 'mandi_cara'],
            ['label' => 'Mandi - Tempat', 'key' => 'mandi_tempat']
        ]],
        ['no' => 2, 'rowspan' => 2, 'items' => [
            ['label' => 'Cuci Rambut - Frekuensi', 'key' => 'rambut_frekuensi'],
            ['label' => 'Cuci Rambut - Cara', 'key' => 'rambut_cara']
        ]],
        ['no' => 3, 'rowspan' => 2, 'items' => [
            ['label' => 'Gunting Kuku - Frekuensi', 'key' => 'kuku_frekuensi'],
            ['label' => 'Gunting Kuku - Cara', 'key' => 'kuku_cara']
        ]],
        ['no' => 4, 'rowspan' => 2, 'items' => [
            ['label' => 'Gosok Gigi - Frekuensi', 'key' => 'gigi_frekuensi'],
            ['label' => 'Gosok Gigi - Cara', 'key' => 'gigi_cara']
        ]],
        ['no' => 5, 'rowspan' => 1, 'items' => [
            ['label' => 'Berpakaian', 'key' => 'berpakaian']
        ]]
    ];

    foreach ($hygiene_data as $group):
        foreach ($group['items'] as $index => $item):
            $key_sebelum = $item['key'] . '_sebelum';
            $key_sekarang = $item['key'] . '_sekarang';
            // Penanganan khusus untuk "Berpakaian" yang mungkin tidak punya suffix _sebelum/_sekarang di database Anda
            if ($group['no'] == 5) {
                $key_sebelum = $key_sekarang = $item['key'];
            }
    ?>
        <tr>
            <?php if ($index === 0): ?>
                <td rowspan="<?= $group['rowspan'] ?>" class="text-center align-middle"><?= $group['no'] ?></td>
            <?php endif; ?>
            <td class="align-middle"><strong><?= $item['label'] ?></strong></td>
            <td>
                <textarea class="form-control form-control-sm" name="<?= $key_sebelum ?>" rows="1" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars($existing_data[$key_sebelum] ?? "") ?></textarea>
            </td>
            <td>
                <textarea class="form-control form-control-sm" name="<?= $key_sekarang ?>" rows="1" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars($existing_data[$key_sekarang] ?? "") ?></textarea>
            </td>
        </tr>
    <?php 
        endforeach;
    endforeach; 
    ?>
</tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <h5 class="card-title"><strong>5. Data Biologis</strong></h5>
                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary"><strong>1. Kepala</strong></label>
                    </div>

                    <div class="row mb-2 align-items-center">
                        <div class="col-sm-2">
                            <strong>Bentuk Kepala</strong>
                        </div>
                        <!-- Input Warna Ditambahkan di sini -->
                        <div class="col-sm-9">
<textarea class="form-control" 
          name="bentuk_kepala" 
          id="bentuk_kepala" 
          rows="1" 
          style="overflow:hidden; resize:none;" 
          oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" 
          <?= $ro ?>><?= htmlspecialchars($existing_data['bentuk_kepala'] ?? '') ?></textarea>                        </div>
                    </div>



                    <!-- Nyeri Dada -->
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Nyeri Tekan</strong>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="nyeri_tekan" value="ya" id="nyeri_tekan_ya" <?= $ro_disabled ?> <?= ($existing_data['nyeri_tekan'] ?? '') === 'ya' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="nyeri_tekan_ya">Ya</label>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="nyeri_tekan" value="tidak" id="nyeri_tekan_tidak" <?= $ro_disabled ?> <?= ($existing_data['nyeri_tekan'] ?? '') === 'tidak' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="nyeri_tekan_tidak">Tidak</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Benjolan</strong>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="benjolan_kepala" value="ya" id="benjolan_ya" <?= $ro_disabled ?> <?= ($existing_data['benjolan_kepala'] ?? '') === 'ya' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="benjolan_ya">Ya</label>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="benjolan_kepala" value="tidak" id="benjolan_tidak" <?= $ro_disabled ?> <?= ($existing_data['benjolan_kepala'] ?? '') === 'tidak' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="benjolan_tidak">Tidak</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-2 col-form-label">
                            <strong>Lain-lain</strong>
                        </div>
                        <div class="col-sm-9">
<textarea class="form-control" 
          name="kelainan_kepala" 
          rows="1" 
          style="overflow:hidden; resize:none;" 
          oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" 
          <?= $ro ?>><?= htmlspecialchars($existing_data['kelainan_kepala'] ?? '') ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary"><strong>2. Rambut</strong></label>
                    </div>

                    <div class="row mb-2 align-items-center">
                        <div class="col-sm-2">
                            <strong>Penyebaran Merata</strong>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="penyebaran_merata" value="ya" id="penyebaran_merata_ya" <?= $ro_disabled ?> <?= ($existing_data['penyebaran_merata'] ?? '') === 'ya' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="penyebaran_merata_ya">Ya</label>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="penyebaran_merata" value="tidak" id="penyebaran_merata_tidak" <?= $ro_disabled ?> <?= ($existing_data['penyebaran_merata'] ?? '') === 'tidak' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="penyebaran_merata_tidak">Tidak</label>
                            </div>
                        </div>
                    </div>



                    <!-- Nyeri Dada -->
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Mudah Rontok</strong>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="rambut_dicabut" value="ya" id="rambut_dicabut_ya" <?= $ro_disabled ?> <?= ($existing_data['rambut_dicabut'] ?? '') === 'ya' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="rambut_dicabut_ya">Ya</label>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="rambut_dicabut" value="tidak" id="rambut_dicabut_tidak" <?= $ro_disabled ?> <?= ($existing_data['rambut_dicabut'] ?? '') === 'tidak' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="rambut_dicabut_tidak">Tidak</label>
                            </div>
                        </div>
                    </div>
                  <div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>Warna Rambut</strong>
    </div>
    <div class="col-sm-9">
        <textarea class="form-control" 
                  name="warna_penyebaran" 
                  rows="1" 
                  style="overflow:hidden; resize:none;" 
                  oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" 
                  <?= $ro ?>><?= htmlspecialchars($existing_data['warna_penyebaran'] ?? '') ?></textarea>
    </div>
</div>
<div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>Lain-lain</strong>
    </div>
    <div class="col-sm-9">
        <textarea class="form-control" 
                  name="kelainan_rambut" 
                  rows="1" 
                  style="overflow:hidden; resize:none;" 
                  oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" 
                  <?= $ro ?>><?= htmlspecialchars($existing_data['kelainan_rambut'] ?? '') ?></textarea>
    </div>
</div>



                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary"><strong>3. Wajah</strong></label>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-2 col-form-label">
                            <strong>Ekspresi Wajah</strong>
                        </div>
                        <div class="col-sm-9">
<textarea class="form-control" 
          name="ekspresi_wajah" 
          rows="1" 
          style="overflow:hidden; resize:none;" 
          oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" 
          <?= $ro ?>><?= htmlspecialchars($existing_data['ekspresi_wajah'] ?? '') ?></textarea>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Kesimetrisan Wajah</strong>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="kesimentrisan_wajah" value="ya" id="kesimentrisan_wajah_ya" <?= $ro_disabled ?> <?= ($existing_data['kesimentrisan_wajah'] ?? '') === 'ya' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="kesimentrisan_wajah_ya">Ya</label>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="kesimentrisan_wajah" value="tidak" id="kesimentrisan_wajah_tidak" <?= $ro_disabled ?> <?= ($existing_data['kesimentrisan_wajah'] ?? '') === 'tidak' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="kesimentrisan_wajah_tidak">Tidak</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Terdapat Udema</strong>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="udema_wajah" value="ya" id="udema_wajah_ya" <?= $ro_disabled ?> <?= ($existing_data['udema_wajah'] ?? '') === 'ya' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="udema_wajah_ya">Ya</label>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="udema_wajah" value="tidak" id="udema_wajah_tidak" <?= $ro_disabled ?> <?= ($existing_data['udema_wajah'] ?? '') === 'tidak' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="udema_wajah_tidak">Tidak</label>
                            </div>
                        </div>
                    </div>



                    <div class="row mb-3">
                        <div class="col-sm-2 col-form-label">
                            <strong>Lain-lain</strong>
                        </div>
                        <div class="col-sm-9">
<textarea class="form-control" 
          name="kelainan_wajah" 
          rows="1" 
          style="overflow:hidden; resize:none;" 
          oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" 
          <?= $ro ?>><?= htmlspecialchars($existing_data['kelainan_wajah'] ?? '') ?></textarea>
                        </div>
                    </div>




                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary"><strong>4. Mata</strong></label>
                    </div>


                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Penglihatan</strong>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="penglihatan" value="jelas" id="penglihatan_jelas" <?= $ro_disabled ?> <?= ($existing_data['penglihatan'] ?? '') === 'jelas' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="penglihatan_jelas">Jelas</label>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="penglihatan" value="kabur" id="penglihatan_kabur" <?= $ro_disabled ?> <?= ($existing_data['penglihatan'] ?? '') === 'kabur' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="penglihatan_kabur">Kabur</label>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="penglihatan" value="rabun" id="penglihatan_rabun" <?= $ro_disabled ?> <?= ($existing_data['penglihatan'] ?? '') === 'rabun' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="penglihatan_rabun">Rabun</label>
                            </div>
                        </div>


                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="penglihatan" value="berkunang" id="penglihatan_berkunang" <?= $ro_disabled ?> <?= ($existing_data['penglihatan'] ?? '') === 'berkunang' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="penglihatan_berkunang">Berkunang</label>
                            </div>
                        </div>
                    </div>


                  <div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Visus</strong></label>
    <div class="col-sm-9">
        <div class="row">
            <!-- Kanan -->
            <div class="col-md-4 d-flex align-items-center">
                <label class="me-2"><strong>Kanan</strong></label>
                <textarea class="form-control" 
                          name="visus_kanan" 
                          rows="1" 
                          style="overflow:hidden; resize:none;" 
                          oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" 
                          <?= $ro ?>><?= htmlspecialchars($existing_data['visus_kanan'] ?? '') ?></textarea>
            </div>

            <!-- Kiri -->
            <div class="col-md-4 d-flex align-items-center">
                <label class="me-2"><strong>Kiri</strong></label>
                <textarea class="form-control" 
                          name="visus_kiri" 
                          rows="1" 
                          style="overflow:hidden; resize:none;" 
                          oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" 
                          <?= $ro ?>><?= htmlspecialchars($existing_data['visus_kiri'] ?? '') ?></textarea>
            </div>
        </div>
    </div>
</div>



                    <div class="row mb-3">
                        <div class="col-sm-2 col-form-label">
                            <strong>Lapang Pandang</strong>
                        </div>
                        <div class="col-sm-9">
<textarea class="form-control" 
          name="lapang_pandang" 
          rows="1" 
          style="overflow:hidden; resize:none;" 
          oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" 
          <?= $ro ?>><?= htmlspecialchars($existing_data['lapang_pandang'] ?? '') ?></textarea>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-2 col-form-label">
                            <strong>Konjungtiva</strong>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="konjungtiva" value="anemis" id="anemis" <?= $ro_disabled ?> <?= ($existing_data['konjungtiva'] ?? '') === 'anemis' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="anemis">Anemis</label>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="konjungtiva" value="an-anemis" id="an-anemis" <?= $ro_disabled ?> <?= ($existing_data['konjungtiva'] ?? '') === 'an-anemis' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="an-anemis">An-anemis</label>
                            </div>
                        </div>
                    </div>


                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Lesi</strong>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="lesi_mata" value="ada" id="lesi_mata_ada" <?= $ro_disabled ?> <?= ($existing_data['lesi_mata'] ?? '') === 'ada' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="lesi_mata_ada">Ada</label>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="lesi_mata" value="tidak" id="lesi_mata_tidak" <?= $ro_disabled ?> <?= ($existing_data['lesi_mata'] ?? '') === 'tidak' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="lesi_mata_tidak">Tidak</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Sclera</strong></div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="sclera" value="sclera_ikterik" id="sclera_ikterik" <?= $ro_disabled ?> <?= ($existing_data['sclera'] ?? '') === 'sclera_ikterik' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="sclera_ikterik">Ikterik</label>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="sclera" value="sclera_anikterik" id="sclera_anikterik" <?= $ro_disabled ?> <?= ($existing_data['sclera'] ?? '') === 'sclera_anikterik' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="sclera_anikterik">An-Ikterik</label>
                            </div>
                        </div>
                    </div>


                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Reaksi Pupil</strong>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="pupil" value="isokor" id="pupil_isokor" <?= $ro_disabled ?> <?= ($existing_data['pupil'] ?? '') === 'isokor' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="pupil_isokor">Isokor</label>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="pupil" value="anisokor" id="pupil_anisokor" <?= $ro_disabled ?> <?= ($existing_data['pupil'] ?? '') === 'anisokor' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="pupil_anisokor">An-isokor</label>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>pergerakan Bola Mata</strong>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="bola_mata" value="simetris" id="bola_mata_simetris" <?= $ro_disabled ?> <?= ($existing_data['bola_mata'] ?? '') === 'simetris' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="bola_mata_simetris">Simetris</label>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="bola_mata" value="tidak" id="bola_mata_tidak" <?= $ro_disabled ?> <?= ($existing_data['bola_mata'] ?? '') === 'tidak' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="bola_mata_tidak">Tidak</label>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-2 col-form-label">
                            <strong>Lain-lain</strong>
                        </div>
                        <div class="col-sm-9">
<textarea class="form-control" 
          name="kelainan_mata" 
          rows="1" 
          style="overflow:hidden; resize:none;" 
          oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" 
          <?= $ro ?>><?= htmlspecialchars($existing_data['kelainan_mata'] ?? '') ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary"><strong>5. Telinga</strong></label>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Pendengaran Kiri</strong>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="pendengaran_kiri" value="jelas" id="pendengaran_kiri_jelas" <?= $ro_disabled ?> <?= ($existing_data['pendengaran_kiri'] ?? '') === 'jelas' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="pendengaran_kiri_jelas">Jelas</label>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="pendengaran_kiri" value="berkurang" id="pendengaran_kiri_berkurang" <?= $ro_disabled ?> <?= ($existing_data['pendengaran_kiri'] ?? '') === 'berkurang' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="pendengaran_kiri_berkurang">Berkurang</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Pendengaran Kanan</strong>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="pendengaran_kanan" value="jelas" id="pendengaran_kanan_jelas" <?= $ro_disabled ?> <?= ($existing_data['pendengaran_kanan'] ?? '') === 'jelas' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="pendengaran_kanan_jelas">Jelas</label>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="pendengaran_kanan" value="berkurang" id="pendengaran_kanan_berkurang" <?= $ro_disabled ?> <?= ($existing_data['pendengaran_kanan'] ?? '') === 'berkurang' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="pendengaran_kanan_berkurang">Berkurang</label>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Nyeri Kiri</strong>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="nyeri_Kiri" value="ada" id="nyeri_Kiri_ada" <?= $ro_disabled ?> <?= ($existing_data['nyeri_Kiri'] ?? '') === 'ada' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="nyeri_Kiri_ada">Ada</label>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="nyeri_Kiri" value="tidak" id="nyeri_Kiri_tidak" <?= $ro_disabled ?> <?= ($existing_data['nyeri_Kiri'] ?? '') === 'tidak' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="nyeri_Kiri_tidak">Tidak</label>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Nyeri Kanan</strong>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="nyeri_kanan" value="ada" id="nyeri_kanan_ada" <?= $ro_disabled ?> <?= ($existing_data['nyeri_kanan'] ?? '') === 'ada' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="nyeri_kanan_ada">Ada</label>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="nyeri_kanan" value="tidak" id="nyeri_kanan_tidak" <?= $ro_disabled ?> <?= ($existing_data['nyeri_kanan'] ?? '') === 'tidak' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="nyeri_kanan_tidak">Tidak</label>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Serumen</strong>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="serumen" value="ada" id="serumen_ada" <?= $ro_disabled ?> <?= ($existing_data['serumen'] ?? '') === 'ada' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="serumen_ada">Ada</label>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="serumen" value="tidak" id="serumen_tidak" <?= $ro_disabled ?> <?= ($existing_data['serumen'] ?? '') === 'tidak' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="serumen_tidak">Tidak</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-2 col-form-label">
                            <strong>Lain-lain</strong>
                        </div>
                        <div class="col-sm-9">
<textarea class="form-control" 
          name="kelainan_telinga" 
          rows="1" 
          style="overflow:hidden; resize:none;" 
          oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" 
          <?= $ro ?>><?= htmlspecialchars($existing_data['kelainan_telinga'] ?? '') ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary"><strong>6. Hidung</strong></label>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Membedakan Bau</strong>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="bau" value="dapat" id="bau_dapat" <?= $ro_disabled ?> <?= ($existing_data['bau'] ?? '') === 'dapat' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="bau_dapat">Dapat</label>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="bau" value="tidak" id="bau_tidak" <?= $ro_disabled ?> <?= ($existing_data['bau'] ?? '') === 'tidak' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="bau_tidak">Tidak</label>
                            </div>
                        </div>
                    </div>
                    <!-- Pupil -->
                   <div class="row mb-3 align-items-center">
    <label class="col-sm-2 col-form-label"><strong>Sekret</strong></label>
    <div class="col-sm-3">
        <textarea class="form-control" 
                  name="sekresi" 
                  rows="1" 
                  style="overflow:hidden; resize:none;" 
                  oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" 
                  <?= $ro ?>><?= htmlspecialchars($existing_data['sekresi'] ?? '') ?></textarea>
    </div>

    <!-- Warna -->
    <label class="col-sm-2 col-form-label"><strong>Warna</strong></label>
    <div class="col-sm-3">
        <textarea class="form-control" 
                  name="warna_hidung" 
                  rows="1" 
                  style="overflow:hidden; resize:none;" 
                  oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" 
                  <?= $ro ?>><?= htmlspecialchars($existing_data['warna_hidung'] ?? '') ?></textarea>
    </div>
</div>
                    <div class="row mb-3">
                        <div class="col-sm-2 col-form-label">
                            <strong>Mukosa</strong>
                        </div>
                        <div class="col-sm-9">
<textarea class="form-control" 
          name="mukosa_hidung" 
          rows="1" 
          style="overflow:hidden; resize:none;" 
          oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" 
          <?= $ro ?>><?= htmlspecialchars($existing_data['mukosa_hidung'] ?? '') ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Pembengkakan</strong>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="pembengkakan" value="ya" id="pembengkakan_ya" <?= $ro_disabled ?> <?= ($existing_data['pembengkakan'] ?? '') === 'ya' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="pembengkakan_ya">Ya</label>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="pembengkakan" value="tidak" id="pembengkakan_tidak" <?= $ro_disabled ?> <?= ($existing_data['pembengkakan'] ?? '') === 'tidak' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="pembengkakan_tidak">Tidak</label>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Pernafasan Cuping Hidung</strong>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="cuping_hidung" value="ya" id="cuping_hidung_ya" <?= $ro_disabled ?> <?= ($existing_data['cuping_hidung'] ?? '') === 'ya' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="cuping_hidung_ya">Ya</label>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="cuping_hidung" value="tidak" id="cuping_hidung_tidak" <?= $ro_disabled ?> <?= ($existing_data['cuping_hidung'] ?? '') === 'tidak' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="cuping_hidung_tidak">Tidak</label>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-2 col-form-label">
                            <strong>Lain-lain</strong>
                        </div>
                        <div class="col-sm-9">
<textarea class="form-control" 
          name="kelainan_hidung" 
          rows="1" 
          style="overflow:hidden; resize:none;" 
          oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" 
          <?= $ro ?>><?= htmlspecialchars($existing_data['kelainan_hidung'] ?? '') ?></textarea>
                        </div>
                    </div>


                    <div class="row mb-3">
                        <label class="col-sm-12 text-primary"><strong>7. Mulut</strong></label>
                    </div>
                    <!-- Frekuensi -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Bibir</strong></label>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="bibir" value="ya" id="simetris_ya" <?= $ro_disabled ?> <?= ($existing_data['bibir'] ?? '') === 'ya' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="simetris_ya">Simetris</label>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="simetris" value="tidak" id="simetris_tidak" <?= $ro_disabled ?> <?= ($existing_data['bibir'] ?? '') === 'tidak' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="simetris_tidak">Tidak Simetris</label>
                            </div>
                        </div>
                       <div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>Warna Bibir</strong>
    </div>
    <div class="col-sm-9">
        <textarea class="form-control" 
                  name="warna_bibir" 
                  rows="1" 
                  style="overflow:hidden; resize:none;" 
                  oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" 
                  <?= $ro ?>><?= htmlspecialchars($existing_data['warna_bibir'] ?? '') ?></textarea>
    </div>
</div>
                        <div class="row mb-2">
                            <div class="col-sm-2"><strong>Simetris</strong>
                            </div>

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="simetris" value="ya" id="simetris_ya" <?= $ro_disabled ?> <?= ($existing_data['simetris'] ?? '') === 'ya' ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="simetris_ya">Ya</label>
                                </div>
                            </div>

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="simetris" value="tidak" id="simetris_tidak" <?= $ro_disabled ?> <?= ($existing_data['simetris'] ?? '') === 'tidak' ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="simetris_tidak">Tidak</label>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-sm-2"><strong>Kelembaban</strong>
                            </div>

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="kelembaban" value="basah" id="kelembaban_basah" <?= $ro_disabled ?> <?= ($existing_data['kelembaban'] ?? '') === 'basah' ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="kelembaban_basah">Basah</label>
                                </div>
                            </div>

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="kelembaban" value="kering" id="kelembaban_kering" <?= $ro_disabled ?> <?= ($existing_data['kelembaban'] ?? '') === 'kering' ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="kelembaban_kering">Kering</label>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="kelembaban" value="lesi" id="kelembaban_lesi" <?= $ro_disabled ?> <?= ($existing_data['kelembaban'] ?? '') === 'lesi' ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="kelembaban_lesi">Lesi</label>
                                </div>
                            </div>
                        </div>

                        <!-- Suara Jantung -->
                        <div class="row mb-2">
                            <div class="col-sm-2">
                                <strong>Gigi</strong>
                            </div>



                            <div class="row mb-2">
                                <div class="col-sm-2">
                                    <strong>Caries :</strong>
                                </div>

                                <div class="col-sm-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="caries" value="ada" id="caries_ada" <?= $ro_disabled ?> <?= ($existing_data['caries'] ?? '') === 'ada' ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="caries_ada">Ada</label>
                                    </div>
                                </div>

                                <div class="col-sm-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="caries" value="tidak" id="caries_tidak" <?= $ro_disabled ?> <?= ($existing_data['caries'] ?? '') === 'tidak' ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="caries_tidak">Tidak</label>
                                    </div>
                                </div>

                            </div>
                            <div class="col-sm-2">
                                <strong></strong>
                            </div>
                            <!-- Menyesuaikan layout agar input jumlah dan warna sejajar -->
                            <div class="col-sm-10">
    <div class="row">
        <div class="col-sm-5">
            <label><strong>Jumlah</strong></label>
            <textarea class="form-control" 
                      name="jumlah_gigi" 
                      rows="1" 
                      style="overflow:hidden; resize:none;" 
                      oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" 
                      <?= $ro ?>><?= htmlspecialchars($existing_data['jumlah_gigi'] ?? '') ?></textarea>
        </div>
        <div class="col-sm-5">
            <label><strong>Warna</strong></label>
            <textarea class="form-control" 
                      name="warna_gigi" 
                      rows="1" 
                      style="overflow:hidden; resize:none;" 
                      oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" 
                      <?= $ro ?>><?= htmlspecialchars($existing_data['warna_gigi'] ?? '') ?></textarea>
        </div>
    </div>
</div>
                        </div>



                      <!-- Gigi Palsu & Letak -->
<div class="row mb-3 align-items-center">
    <label class="col-sm-2 col-form-label"><strong>Gigi Palsu</strong></label>
    <div class="col-sm-4">
        <div class="input-group">
            <textarea class="form-control" 
                      name="gigi_palsu_jumlah" 
                      rows="1" 
                      style="overflow:hidden; resize:none;" 
                      oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" 
                      <?= $ro ?>><?= htmlspecialchars($existing_data['gigi_palsu_jumlah'] ?? '') ?></textarea>
            <span class="input-group-text">buah</span>
        </div>
    </div>

    <!-- Letak Gigi Palsu -->
    <label class="col-sm-2 col-form-label"><strong>Letak</strong></label>
    <div class="col-sm-2">
        <textarea class="form-control" 
                  name="letak" 
                  rows="1" 
                  style="overflow:hidden; resize:none;" 
                  oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" 
                  <?= $ro ?>><?= htmlspecialchars($existing_data['letak'] ?? '') ?></textarea>
    </div>
</div>

<!-- Warna Lidah -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Warna Lidah</strong></label>
    <div class="col-sm-9">
        <textarea class="form-control" 
                  name="lidah" 
                  rows="1" 
                  style="overflow:hidden; resize:none;" 
                  oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" 
                  <?= $ro ?>><?= htmlspecialchars($existing_data['lidah'] ?? '') ?></textarea>
    </div>
</div>


                        <div class="row mb-2">
                            <div class="col-sm-2"><strong>Lesi</strong>
                            </div>

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="lesi_lidah" value="ada" id="lesi_lidah_ada" <?= $ro_disabled ?> <?= ($existing_data['lesi_lidah'] ?? '') === 'ada' ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="lesi_lidah_ada">Ada</label>
                                </div>
                            </div>

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="lesi_lidah" value="tidak" id="lesi_lidah_tidak" <?= $ro_disabled ?> <?= ($existing_data['lesi_lidah'] ?? '') === 'tidak' ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="lesi_lidah_tidak">Tidak</label>
                                </div>
                            </div>
                        </div>
                        <!-- Perabaan -->

                        <div class="row mb-2">
                            <div class="col-sm-2"><strong>Sensasi Rasa</strong>
                            </div>

                            <!-- Panas -->

                            <div class="col-sm-2">
                                <strong>Panas/Dingin</strong>
                            </div>

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="panas/dingin" value="ada" id="panas_dingin_ada" <?= $ro_disabled ?> <?= ($existing_data['panas/dingin'] ?? '') === 'ada' ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="panas_dingin_ada">Ada</label>
                                </div>
                            </div>

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="panas/dingin" value="tidak" id="panas_dingin_tidak" <?= $ro_disabled ?> <?= ($existing_data['panas/dingin'] ?? '') === 'tidak' ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="panas_dingin_tidak">tidak</label>
                                </div>
                            </div>
                        </div>

                        <!-- Dingin -->

                        <div class="row mb-2">
                            <div class="col-sm-2">
                            </div>

                            <div class="col-sm-2">
                                <strong>Asam / Pahit </strong>
                            </div>

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="asampahit" value="ada" id="asampahit_ada" <?= $ro_disabled ?> <?= ($existing_data['asampahit'] ?? '') === 'ada' ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="asampahit_ada">Ada</label>
                                </div>
                            </div>

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="asampahit" value="tidak" id="asampahit_tidak" <?= $ro_disabled ?> <?= ($existing_data['asampahit'] ?? '') === 'tidak' ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="asampahit_tidak">Tidak</label>
                                </div>
                            </div>
                        </div>

                        <!-- Tekan -->

                        <div class="row mb-2">
                            <div class="col-sm-2">
                            </div>

                            <div class="col-sm-2">
                                <strong>Manis </strong>
                            </div>

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="manis" value="ada" id="manis_ada" <?= $ro_disabled ?> <?= ($existing_data['manis'] ?? '') === 'ada' ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="manis_ada">Ada</label>
                                </div>
                            </div>

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="manis" value="tidak" id="manis_tidak" <?= $ro_disabled ?> <?= ($existing_data['manis'] ?? '') === 'tidak' ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="manis_tidak">Tidak</label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-2"><strong>Refleks Mengunyah</strong>
                            </div>

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="refleks" value="dapat" id="refleks_dapat" <?= $ro_disabled ?> <?= ($existing_data['refleks'] ?? '') === 'dapat' ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="refleks_dapat">Dapat</label>
                                </div>
                            </div>

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="refleks" value="tidak" id="refleks_tidak" <?= $ro_disabled ?> <?= ($existing_data['refleks'] ?? '') === 'tidak' ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="refleks_tidak">Tidak</label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-2"><strong>Pembesaran Tonsil</strong>
                            </div>

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="tonsil" value="ya" id="tonsil_ya" <?= $ro_disabled ?> <?= ($existing_data['tonsil'] ?? '') === 'ya' ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="tonsil_ya">Ya</label>
                                </div>
                            </div>

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="tonsil" value="tidak" id="tonsil_tidak" <?= $ro_disabled ?> <?= ($existing_data['tonsil'] ?? '') === 'tidak' ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="tonsil_tidak">Tidak</label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-2"><strong>Bau Mulut</strong>
                            </div>

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="bau_mulut" value="uranium" id="uranium" <?= $ro_disabled ?> <?= ($existing_data['bau_mulut'] ?? '') === 'uranium' ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="uranium">Uranium + / -</label>
                                </div>
                            </div>

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="bau_mulut" value="amoniak" id="amoniak" <?= $ro_disabled ?> <?= ($existing_data['bau_mulut'] ?? '') === 'amoniak' ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="amoniak">Amoniak + / - </label>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="bau_mulut" value="aceton" id="aceton" <?= $ro_disabled ?> <?= ($existing_data['bau_mulut'] ?? '') === 'aceton' ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="aceton">Aceton + / -</label>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="bau_mulut" value="busuk" id="busuk" <?= $ro_disabled ?> <?= ($existing_data['bau_mulut'] ?? '') === 'busuk' ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="busuk">Busuk + / - </label>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="bau_mulut" value="alkohol" id="alkohol" <?= $ro_disabled ?> <?= ($existing_data['bau_mulut'] ?? '') === 'alkohol' ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="alkohol">Alkohol + / -</label>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-sm-2">
                                <strong>Sekret</strong>
                            </div>

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="sekret_mulut" value="ada" id="sekret_mulut_ada" <?= $ro_disabled ?> <?= ($existing_data['sekret_mulut'] ?? '') === 'ada' ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="sekret_mulut_ada">Ada</label>
                                </div>
                            </div>

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="sekret_mulut" value="tidak" id="sekret_mulut_tidak" <?= $ro_disabled ?> <?= ($existing_data['sekret_mulut'] ?? '') === 'tidak' ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="sekret_mulut_tidak">Tidak</label>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <strong></strong>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-check">

                                    <label class="form-check-label"></label>
                                </div>
                            </div>

                            <div class="col-sm-2">
                                <div class="form-check">

                                    <label class="form-check-label"></label>
                                </div>

                            </div>
                            <div class="col-sm-2"></div>
                            <!-- Lainnya -->
                            <div class="col-sm-9">
                                <label><strong>Warna</strong></label>
<textarea class="form-control" 
          name="sekret_mulut_warna" 
          rows="1" 
          style="overflow:hidden; resize:none;" 
          oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" 
          <?= $ro ?>><?= htmlspecialchars($existing_data['sekret_mulut_warna'] ?? '') ?></textarea>                            </div>
                        </div>



                        <div>
                            <label class="col-sm-12 text-primary"><strong>8. Leher</strong></label>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-2"><strong>Bentuk Simetris</strong>
                            </div>

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="leher_simetris" value="ya" id="leher_simetris_ya" <?= $ro_disabled ?> <?= ($existing_data['leher_simetris'] ?? '') === 'ya' ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="leher_simetris_ya">Ya</label>
                                </div>
                            </div>

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="leher_simetris" value="tidak" id="leher_simetris_tidak" <?= $ro_disabled ?> <?= ($existing_data['leher_simetris'] ?? '') === 'tidak' ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="leher_simetris_tidak">Tidak</label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-2"><strong>Pembesaran Kelenjar</strong>
                            </div>

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="kelenjar" value="ada" id="kelenjar_ada" <?= $ro_disabled ?> <?= ($existing_data['kelenjar'] ?? '') === 'ada' ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="kelenjar_ada">Ada</label>
                                </div>
                            </div>

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="kelenjar" value="tidak" id="kelenjar_tidak" <?= $ro_disabled ?> <?= ($existing_data['kelenjar'] ?? '') === 'tidak' ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="kelenjar_tidak">Tidak</label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-2"><strong>Peninggian JVP</strong></div>

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="jvp" value="ada" id="jvp_ada" <?= $ro_disabled ?> <?= ($existing_data['jvp'] ?? '') === 'ada' ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="jvp_ada">Ada</label>
                                </div>
                            </div>

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="jvp" value="tidak" id="jvp_tidak" <?= $ro_disabled ?> <?= ($existing_data['jvp'] ?? '') === 'tidak' ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="jvp_tidak">Tidak</label>
                                </div>
                            </div>

                            <!-- Kolom input teks tambahan -->
                           <div class="col-sm-4">
    <textarea class="form-control" 
              name="jvp_keterangan" 
              id="jvp_keterangan" 
              rows="1" 
              style="overflow:hidden; resize:none;" 
              oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" 
              <?= $ro_disabled ?>><?= htmlspecialchars($existing_data['jvp_keterangan'] ?? '') ?></textarea>
</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-2"><strong>Refleks Menelan</strong>
                            </div>

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="refleks_menelan" value="dapat" id="refleks_menelan_dapat" <?= $ro_disabled ?> <?= ($existing_data['refleks_menelan'] ?? '') === 'dapat' ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="refleks_menelan_dapat">Dapat</label>
                                </div>
                            </div>

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="refleks_menelan" value="tidak" id="refleks_menelan_tidak" <?= $ro_disabled ?> <?= ($existing_data['refleks_menelan'] ?? '') === 'tidak' ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="refleks_menelan_tidak">Tidak</label>
                                </div>
                            </div>
                        </div>


                        <div class="row mb-3">
                            <div class="col-sm-2 col-form-label">
                                <strong>Kelainan</strong>
                            </div>
                            <div class="col-sm-9">
<textarea class="form-control" 
          name="kelainan_leher" 
          rows="1" 
          style="overflow:hidden; resize:none;" 
          oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" 
          <?= $ro ?>><?= htmlspecialchars($existing_data['kelainan_leher'] ?? '') ?></textarea>                            </div>
                        </div>

                        <!-- TOMBOL SIMPAN -->
                        <?php if (!$is_dosen): ?>
                            <div class="row mb-3">
                                <div class="col-sm-12 d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary" <?= $ro_disabled ?>>Simpan Data</button>
                                </div>
                            </div>
                        <?php endif; ?>

                </form>
            </div>
        </div>
        </div>


        <?php include dirname(__DIR__, 2) . '/partials/footer_form.php'; ?>

    </section>
</main>