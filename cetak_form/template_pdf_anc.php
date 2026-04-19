<?php
// Shortcut per section
$demografi   = $sections['data_demografi'] ?? [];
$riwayat     = $sections['riwayat_kehamilan_persalinan'] ?? [];
$fisik       = $sections['pengkajian_fisik'] ?? [];
$terapi      = $sections['program_terapi_lab'] ?? [];
$analisa     = $sections['analisa_data'] ?? [];
$catatan     = $sections['catatan_keperawatan'] ?? [];


include 'template_pdf.php';
?>


<body>
    <div >

        <!-- HEADER -->
        <h1>Format Pengkajian Asuhan Keperawatan</h1>
        <h2>Antenatal Care</h2>

        <table class="header-table">
            <tr>
                <td width="25%"><strong>Nama Mahasiswa</strong></td>
                <td width="2%">:</td>
                <td width="23%"><?= p($submission['mahasiswa_name']) ?></td>
                <td width="25%"><strong>Tanggal Pengkajian</strong></td>
                <td width="2%">:</td>
                <td><?= p($submission['tanggal_pengkajian']) ?></td>
            </tr>
            <tr>
                <td><strong>NPM</strong></td>
                <td>:</td>
                <td><?= p($submission['npm']) ?></td>
                <td><strong>RS/Ruangan</strong></td>
                <td>:</td>
                <td><?= p($submission['rs_ruangan']) ?></td>
            </tr>
        </table>

        <!-- ================================ -->
        <!-- SECTION 1: DATA DEMOGRAFI -->
        <!-- ================================ -->
        <h3 class="mt-5">Data Demografi</h3>

        <table class="header-table" style="border:1px solid #000;">
            <tr style="border:1px solid #000;">
                <td width="25%"><strong>Inisial Pasien</strong></td>
                <td width="2%">:</td>
                <td width="23%"><?= p($demografi['inisial_pasien']) ?></td>
                <td width="25%"><strong>Nama Suami</strong></td>
                <td width="2%">:</td>
                <td><?= p($demografi['nama_suami']) ?></td>
            </tr>
            <tr>
                <td><strong>Usia</strong></td>
                <td>:</td>
                <td><?= p($demografi['usia_istri']) ?></td>
                <td><strong>Usia</strong></td>
                <td>:</td>
                <td><?= p($demografi['usia_suami']) ?></td>
            </tr>
            <tr>
                <td><strong>Pekerjaan</strong></td>
                <td>:</td>
                <td><?= p($demografi['pekerjaan_istri']) ?></td>
                <td><strong>Pekerjaan</strong></td>
                <td>:</td>
                <td><?= p($demografi['pekerjaan_suami']) ?></td>
            </tr>
            <tr>
                <td><strong>Pendidikan Terakhir</strong></td>
                <td>:</td>
                <td><?= p($demografi['pendidikan_terakhir_istri']) ?></td>
                <td><strong>Pendidikan Terakhir</strong></td>
                <td>:</td>
                <td><?= p($demografi['pendidikan_terakhir_suami']) ?></td>
            </tr>
            <tr>
                <td><strong>Agama</strong></td>
                <td>:</td>
                <td><?= p($demografi['agama_istri']) ?></td>
                <td><strong>Agama</strong></td>
                <td>:</td>
                <td><?= p($demografi['agama_suami']) ?></td>
            </tr>
            <tr>
                <td><strong>Suku Bangsa</strong></td>
                <td>:</td>
                <td><?= p($demografi['suku_bangsa']) ?></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td><strong>Status Perkawinan</strong></td>
                <td>:</td>
                <td><?= p($demografi['status_perkawinan']) ?></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td><strong>Alamat</strong></td>
                <td>:</td>
                <td colspan="4"><?= p($demografi['alamat']) ?></td>
            </tr>
            <tr>
                <td><strong>Diagnosa Medik</strong></td>
                <td>:</td>
                <td colspan="4"><?= p($demografi['diagnosa_medik']) ?></td>
            </tr>
        </table>

        <h4>Data Biologis / Psikologis</h4>
        <div class="field-row">
            <div class="field-label">Keluhan Utama</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($demografi['keluhan_utama']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Riwayat Keluhan Utama</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($demografi['riwayat_keluhan_utama']) ?></div>
        </div>

        <!-- ================================ -->
        <!-- SECTION 2: RIWAYAT KEHAMILAN -->
        <!-- ================================ -->
        <h3 class="mt-5">Riwayat Kehamilan dan Persalinan</h3>

        <table class="data">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tahun</th>
                    <th>Jenis Persalinan</th>
                    <th>Penolong</th>
                    <th>Jenis Kelamin</th>
                    <th>Masalah Kehamilan</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($riwayat['riwayat_persalinan'])): ?>
                    <?php foreach ($riwayat['riwayat_persalinan'] as $i => $row): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td><?= p($row['tahun']) ?></td>
                            <td><?= p($row['jenis']) ?></td>
                            <td><?= p($row['penolong']) ?></td>
                            <td><?= p($row['jenis_kelamin']) ?></td>
                            <td><?= p($row['masalah']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="text-align:center">-</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <table class="header-table">
            <tr>
                <td width="30%"><strong>Pengalaman Menyusui</strong></td>
                <td width="2%">:</td>
                <td width="18%"><?= p($riwayat['pengalaman_menyusui']) ?></td>
                <td width="20%"><strong>Berapa Lama</strong></td>
                <td width="2%">:</td>
                <td><?= p($riwayat['berapa_lama']) ?></td>
            </tr>
            <tr>
                <td><strong>Riwayat Ginekologi</strong></td>
                <td>:</td>
                <td colspan="4"><?= p($riwayat['riwayat_ginekologi']) ?></td>
            </tr>
            <tr>
                <td><strong>Hasil Ginekologi</strong></td>
                <td>:</td>
                <td colspan="4"><?= p($riwayat['hasil_ginekologi']) ?></td>
            </tr>
            <tr>
                <td><strong>Riwayat KB</strong></td>
                <td>:</td>
                <td colspan="4"><?= p($riwayat['riwayat_kb']) ?></td>
            </tr>
        </table>

        <h4>Riwayat Kehamilan Saat Ini</h4>
        <table class="header-table">
            <tr>
                <td width="30%"><strong>Status Obstetrik</strong></td>
                <td width="2%">:</td>
                <td colspan="4">
                    G<?= p($riwayat['status_obstetrik_g']) ?>
                    P<?= p($riwayat['status_obstetrik_p']) ?>
                    A<?= p($riwayat['status_obstetrik_a']) ?>
                </td>
            </tr>
            <tr>
                <td><strong>HPHT</strong></td>
                <td>:</td>
                <td width="18%"><?= p($riwayat['hpht']) ?></td>
                <td width="20%"><strong>Usia Kehamilan</strong></td>
                <td>:</td>
                <td><?= p($riwayat['usia_kehamilan']) ?></td>
            </tr>
            <tr>
                <td><strong>BB Sebelum Hamil</strong></td>
                <td>:</td>
                <td><?= p($riwayat['bb_sebelum_hamil']) ?> kg</td>
                <td><strong>Keadaan Umum</strong></td>
                <td>:</td>
                <td><?= p($riwayat['keadaan_umum']) ?></td>
            </tr>
            <tr>
                <td><strong>BB/TB</strong></td>
                <td>:</td>
                <td><?= p($riwayat['bbtb']) ?> kg/cm</td>
                <td><strong>Lengan Atas</strong></td>
                <td>:</td>
                <td><?= p($riwayat['lengan_atas']) ?> cm</td>
            </tr>
        </table>

        <h4>Tanda-tanda Vital</h4>
        <table class="header-table">
            <tr>
                <td width="30%"><strong>Tekanan Darah</strong></td>
                <td width="2%">:</td>
                <td width="18%"><?= p($riwayat['tekanan_darah']) ?></td>
                <td width="20%"><strong>Nadi</strong></td>
                <td width="2%">:</td>
                <td><?= p($riwayat['nadi']) ?></td>
            </tr>
            <tr>
                <td><strong>Suhu</strong></td>
                <td>:</td>
                <td><?= p($riwayat['suhu']) ?></td>
                <td><strong>Pernapasan</strong></td>
                <td>:</td>
                <td><?= p($riwayat['pernapasan']) ?></td>
            </tr>
        </table>

        <div class="page-break"></div>

        <!-- ================================ -->
        <!-- SECTION 3: PENGKAJIAN FISIK -->
        <!-- ================================ -->
        <h3 class="mt-5">Pengkajian Fisik</h3>

        <?php
        $pengkajian_items = [
            'Kepala dan Rambut' => [
                ['Inspeksi', 'Bentuk kepala, Penyebaran, Kebersihan, Warna Rambut', $fisik['inspeksi_kepala']],
                ['Palpasi', 'Apakah terdapat benjolan, pembengkakan, nyeri tekan', $fisik['palpasi_kepala']],
                ['Masalah Khusus', '', $fisik['masalah_kepala']],
            ],
            'Wajah' => [
                ['Inspeksi', 'Bentuk, adakah hiperpigmentasi/cloasma gravidarum, area jika ada cloasma', $fisik['inspeksi_wajah']],
                ['Palpasi', 'Adakah nyeri tekan/tidak ada', $fisik['palpasi_wajah']],
                ['Masalah Khusus', '', $fisik['masalah_wajah']],
            ],
            'Mata' => [
                ['Inspeksi Kelopak Mata', 'Kelopak mata apakah terdapat pembengkakan', $fisik['inspeksi_kelopak_mata']],
                ['Inspeksi Bentuk Mata', 'Apakah simetris/tidak simetris', $fisik['inspeksi_bentuk_mata']],
                ['Inspeksi Sklera', 'Apakah anemis/an-anemis', $fisik['inspeksi_sklera']],
                ['Palpasi Kelopak Mata', 'Nyeri tekan/tidak', $fisik['palpasi_kelopak_mata']],
                ['Masalah Khusus', '', $fisik['masalah_mata']],
            ],
            'Hidung' => [
                ['Inspeksi', 'Apakah ada pembengkakan/tidak, kesimetrisan lubang hidung, kebersihan, septum utuh/tidak', $fisik['inspeksi_hidung']],
                ['Palpasi', 'Nyeri tekan/tidak ada', $fisik['palpasi_hidung']],
                ['Masalah Khusus', '', $fisik['masalah_hidung']],
            ],
            'Mulut' => [
                ['Inspeksi Bibir', 'Warna, kesimetrisan, kelembapan, bibir sumbing, ulkus', $fisik['inspeksi_bibir']],
                ['Inspeksi Gigi', 'Amati jumlah, warna, kebersihan, karies', $fisik['inspeksi_gigi']],
                ['Inspeksi Gusi', 'Adakah atau tidak lesi/pembengkakan', $fisik['inspeksi_gusi']],
                ['Inspeksi Lidah', 'Amati warna dan kebersihan', $fisik['inspeksi_lidah']],
                ['Inspeksi Bau Mulut', '', $fisik['inspeksi_bau_mulut']],
                ['Palpasi', 'Apakah ada nyeri tekan atau tidak ada', $fisik['palpasi_mulut']],
                ['Masalah Khusus', '', $fisik['masalah_mulut']],
            ],
            'Telinga' => [
                ['Inspeksi', 'Bentuk: simetris/tidak. Kebersihan: apakah ada perdarahan, peradangan, kotoran/serumen atau tidak ada', $fisik['inspeksi_telinga']],
                ['Palpasi Nyeri Tekan', 'Apakah ada pembengkakan, nyeri tekan atau tidak ada', $fisik['palpasi_nyeri_tekan']],
                ['Palpasi Gangguan Pendengaran', 'Apakah ada gangguan atau tidak', $fisik['palpasi_gangguan']],
                ['Masalah Khusus', '', $fisik['masalah_telinga']],
            ],
            'Leher' => [
                ['Inspeksi', 'Bentuk leher, ada massa dan benjolan atau tidak. Adakah distensi vena jugularis/tidak ada', $fisik['inspeksi_leher']],
                ['Palpasi Kelenjar Tiroid', 'Apakah ada pembesaran kelenjar tiroid atau tidak', $fisik['palpasi_kelenjar']],
                ['Palpasi Trakea', 'Apakah ada pergeseran/tidak', $fisik['palpasi_trakea']],
                ['Palpasi Nyeri Menelan', 'Ya/tidak', $fisik['palpasi_nyeri_menelan']],
                ['Masalah Khusus', '', $fisik['masalah_leher']],
            ],
            'Axila' => [
                ['Inspeksi', 'Warna, pembengkakan', $fisik['inspeksi_axila']],
                ['Palpasi', 'Pembesaran kelenjar limfe: Ya/Tidak', $fisik['palpasi_axila']],
                ['Masalah Khusus', '', $fisik['masalah_axila']],
            ],
            'Dada; Sistem Pernapasan dan Kardiovaskuler' => [
                ['Auskultasi Bunyi Napas', 'Bunyi napas', $fisik['bunyi_napas']],
                ['Auskultasi Suara Jantung', 'Apakah ada mur-mur dan gallop', $fisik['suara_jantung']],
                ['Masalah Khusus', '', $fisik['masalah_dada']],
            ],
            'Payudara' => [
                ['Inspeksi Bentuk', 'Bentuk, Lesi, Kebersihan', $fisik['inspeksi_bentuk_payudara']],
                ['Inspeksi Pengeluaran ASI', 'Ada atau tidak', $fisik['inspeksi_asi']],
                ['Inspeksi Puting', 'Eksverted/Inverted/Plat nipple', $fisik['inspeksi_puting']],
                ['Palpasi Raba', 'Teraba hangat: Ya/Tidak', $fisik['palpasi_raba']],
                ['Palpasi Benjolan', 'Ada/Tidak Ada', $fisik['palpasi_benjolan']],
                ['Masalah Khusus', '', $fisik['masalah_payudara']],
            ]
        ];

        foreach ($pengkajian_items as $title => $fields): ?>
            <div class="subsection-title"><?= $title ?></div>
            <?php foreach ($fields as $field): ?>
                <div class="field-row">
                    <div class="field-label"><?= $field[0] ?></div>
                    <div class="field-sep">:</div>
                    <div class="field-value">
                        <?php if (!empty($field[1])): ?>
                            <div class="field-hint"><?= p($field[1]) ?></div>
                        <?php endif; ?>
                        <?= p($field[2]) ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endforeach; ?>

        <div class="subsection-title">Abdomen - Inspeksi Uterus</div>
        <table class="header-table">
            <tr>
                <td width="30%" style="border:1px solid #000;"><strong>TFU</strong></td>
                <td width="2%" style="border:1px solid #000;">:</td>
                <td width="18%" style="border:1px solid #000;"><?= p($fisik['tfu']) ?> cm</td>
                <td width="20%" style="border:1px solid #000;"><strong>Kontraksi</strong></td>
                <td width="2%" style="border:1px solid #000;">:</td>
                <td style="border:1px solid #000;"><?= p($fisik['kontraksi']) ?></td>
            </tr>
            <tr style="border:1px solid #000;">
                <td style="border:1px solid #000;"><strong>Leopold I</strong></td>
                <td style="border:1px solid #000;">:</td>
                <td colspan="4" style="border:1px solid #000;"><?= p($fisik['leopold_i']) ?></td>
            </tr>
            <tr style="border:1px solid #000;">
                <td style="border:1px solid #000;"><strong>Leopold II Kanan</strong></td>
                <td style="border:1px solid #000;">:</td>
                <td style="border:1px solid #000;"><?= p($fisik['leopold_ii_kanan']) ?></td>
                <td style="border:1px solid #000;"><strong>Leopold II Kiri</strong></td>
                <td style="border:1px solid #000;">:</td>
                <td style="border:1px solid #000;"><?= p($fisik['leopold_ii_kiri']) ?></td>
            </tr>
            <tr style="border:1px solid #000;">
                <td style="border:1px solid #000;"><strong>Leopold III</strong></td>
                <td style="border:1px solid #000;">:</td>
                <td colspan="4" style="border:1px solid #000;"><?= p($fisik['leopold_iii']) ?></td>
            </tr>
            <tr style="border:1px solid #000;">
                <td style="border:1px solid #000;"><strong>Leopold IV</strong></td>
                <td style="border:1px solid #000;">:</td>
                <td colspan="4" style="border:1px solid #000;"><?= p($fisik['leopold_iv']) ?></td>
            </tr>
            <tr style="border:1px solid #000;">
                <td style="border:1px solid #000;"><strong>Pemeriksaan DJJ</strong></td>
                <td style="border:1px solid #000;">:</td>
                <td style="border:1px solid #000;"><?= p($fisik['djj']) ?> Frek</td>
                <td style="border:1px solid #000;"><strong>Intensitas</strong></td>
                <td style="border:1px solid #000;">:</td>
                <td style="border:1px solid #000;"><?= p($fisik['intensitas']) ?></td>
            </tr>
            <tr style="border:1px solid #000;">
                <td style="border:1px solid #000;"><strong>Keteraturan</strong></td>
                <td style="border:1px solid #000;">:</td>
                <td colspan="4" style="border:1px solid #000;"><?= p($fisik['keteraturan']) ?></td>
            </tr>
            <tr style="border:1px solid #000;">
                <td style="border:1px solid #000;"><strong>Linea Nigra</strong></td>
                <td style="border:1px solid #000;">:</td>
                <td style="border:1px solid #000;"><?= p($fisik['linea_nigra']) ?></td>
                <td style="border:1px solid #000;"><strong>Striae</strong></td>
                <td style="border:1px solid #000;">:</td>
                <td style="border:1px solid #000;"><?= p($fisik['striae']) ?></td>
            </tr>
            <tr style="border:1px solid #000;">
                <td style="border:1px solid #000;"><strong>Fungsi Pencernaan</strong></td>
                <td style="border:1px solid #000;">:</td>
                <td colspan="4" style="border:1px solid #000;"><?= p($fisik['fungsi_pencernaan']) ?></td>
            </tr>
            <tr style="border:1px solid #000;">
                <td style="border:1px solid #000;"><strong>Bising Usus</strong></td>
                <td style="border:1px solid #000;">:</td>
                <td colspan="4" style="border:1px solid #000;"><?= p($fisik['bising_usus']) ?></td>
            </tr>
            <tr style="border:1px solid #000;">
                <td style="border:1px solid #000;"><strong>Masalah Khusus</strong></td>
                <td style="border:1px solid #000;">:</td>
                <td colspan="4" style="border:1px solid #000;"><?= p($fisik['masalah_abdomen']) ?></td>
            </tr>
        </table>

        <?php
        $pengkajian_items2 = [
            'Perineum dan Genitalia' => [
                ['Vagina', 'Ada varises atau tidak. Kebersihan', $fisik['vagina']],
                ['Keputihan', 'Ya/Tidak: warna, konsistensi, bau, dan gatal', $fisik['keputihan']],
                ['Hemoroid', 'Ya/Tidak. Jika Ya: derajat, sudah berapa lama, nyeri', $fisik['hemoroid']],
                ['Masalah Khusus', '', $fisik['masalah_perineum']],
            ],
            'Ekstremitas' => [
                ['Ekstremitas Atas', 'Apakah terdapat edema (Ya/Tidak), rasa kesemutan/baal (Ya/Tidak), Kekuatan otot', $fisik['ekstremitas_atas']],
                ['Ekstremitas Bawah', 'Apakah terdapat edema (Ya/Tidak), Varises (Ya/Tidak), Tanda Homan (+/-), Refleks Patella (+/-), kekakuan sendi, kekuatan otot', $fisik['ekstremitas_bawah']],
                ['Masalah Khusus', '', $fisik['masalah_ekstremitas']],
            ],
            'Integumen' => [
                ['Inspeksi', 'Warna, turgor, elastisitas, ulkus', $fisik['inspeksi_integumen']],
                ['Palpasi', 'Akral, CRT, dan Nyeri', $fisik['palpasi_integumen']],
            ],
            'Eliminasi' => [
                ['Urin (BAK)', 'BAK saat ini', $fisik['bak']],
                ['BAB', 'Konstipasi (Ya/Tidak), Frekuensi', $fisik['bab']],
                ['Masalah Khusus', '', $fisik['masalah_eliminasi']],
            ],
            'Istirahat dan Kenyamanan' => [
                ['Pola Tidur', 'Kebiasaan tidur, lama dalam hitungan jam, frekuensi', $fisik['pola_tidur']],
                ['Kenyamanan', 'Keluhan ketidaknyamanan (Ya/Tidak), lokasi', $fisik['kenyamanan']],
                ['Masalah Khusus', '', $fisik['masalah_istirahat']],
            ],
            'Mobilisasi dan Latihan' => [
                ['Tingkat Mobilisasi', 'Apakah mandiri, parsial, total', $fisik['tingkat_mobilisasi']],
                ['Masalah Khusus', '', $fisik['masalah_mobilisasi']],
            ],
            'Pola Nutrisi dan Cairan' => [
                ['Asupan Nutrisi', 'Nafsu makan: baik, kurang atau tidak nafsu makan', $fisik['asupan_nutrisi']],
                ['Asupan Cairan', 'Cukup/kurang', $fisik['asupan_cairan']],
                ['Pantangan Makan', '', $fisik['pantangan_makan']],
                ['Masalah Khusus', '', $fisik['masalah_nutrisi']],
            ],
            'Pengetahuan' => [
                ['Tanda-tanda Melahirkan', '', $fisik['tanda_melahirkan']],
                ['Cara Menangani Nyeri Melahirkan', '', $fisik['nyeri_melahirkan']],
                ['Cara Mengejan Saat Persalinan', '', $fisik['cara_mengejan']],
                ['Manfaat ASI dan Perawatan Payudara', '', $fisik['asi_payudara']],
            ],
        ];

        foreach ($pengkajian_items2 as $title => $fields): ?>
            <div class="subsection-title"><?= $title ?></div>
            <?php foreach ($fields as $field): ?>
                <div class="field-row">
                    <div class="field-label"><?= $field[0] ?></div>
                    <div class="field-sep">:</div>
                    <div class="field-value">
                        <?php if (!empty($field[1])): ?>
                            <div class="field-hint"><?= p($field[1]) ?></div>
                        <?php endif; ?>
                        <?= p($field[2]) ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endforeach; ?>

        <div class="page-break"></div>

        <!-- ================================ -->
        <!-- SECTION 4: PROGRAM TERAPI & LAB -->
        <!-- ================================ -->
        <h3 class="mt-5">Program Terapi</h3>

        <table class="data">
            <thead>
                <tr>
                    <th>Jenis Obat</th>
                    <th>Dosis</th>
                    <th>Kegunaan</th>
                    <th>Cara Pemberian</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($terapi['obat'])): ?>
                    <?php foreach ($terapi['obat'] as $obat): ?>
                        <tr>
                            <td><?= p($obat['jenis_obat']) ?></td>
                            <td><?= p($obat['dosis']) ?></td>
                            <td><?= p($obat['kegunaan']) ?></td>
                            <td><?= p($obat['cara_pemberian']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" style="text-align:center">-</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <h3 class="mt-5">Hasil Pemeriksaan Penunjang dan Laboratorium</h3>

        <table class="data">
            <thead>
                <tr>
                    <th>Pemeriksaan</th>
                    <th>Hasil</th>
                    <th>Nilai Normal</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($terapi['lab'])): ?>
                    <?php foreach ($terapi['lab'] as $lab): ?>
                        <tr>
                            <td><?= p($lab['pemeriksaan']) ?></td>
                            <td><?= p($lab['hasil']) ?></td>
                            <td><?= p($lab['nilai_normal']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" style="text-align:center">-</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- ================================ -->
        <!-- SECTION 5: ANALISA DATA -->
        <!-- ================================ -->
        <h3 class="mt-5">Klasifikasi Data</h3>

        <table class="data">
            <thead>
                <tr>
                    <th width="50%">Data Subjektif (DS)</th>
                    <th width="50%">Data Objektif (DO)</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($analisa['klasifikasi'])): ?>
                    <?php foreach ($analisa['klasifikasi'] as $klas): ?>
                        <tr>
                            <td><?= p($klas['ds']) ?></td>
                            <td><?= p($klas['do']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="2" style="text-align:center">-</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <h3 class="mt-5">Analisa Data</h3>

        <table class="data">
            <thead>
                <tr>
                    <th width="40%">DS/DO</th>
                    <th width="30%">Etiologi</th>
                    <th width="30%">Masalah</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($analisa['analisa'])): ?>
                    <?php foreach ($analisa['analisa'] as $ana): ?>
                        <tr>
                            <td><?= p($ana['ds_do']) ?></td>
                            <td><?= p($ana['etiologi']) ?></td>
                            <td><?= p($ana['masalah']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" style="text-align:center">-</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="page-break"></div>

        <!-- ================================ -->
        <!-- SECTION 6: CATATAN KEPERAWATAN -->
        <!-- ================================ -->
        <h3 class="mt-5">Diagnosa Keperawatan</h3>

        <table class="data">
            <thead>
                <tr>
                    <th>Diagnosa</th>
                    <th>Tanggal Ditemukan</th>
                    <th>Tanggal Teratasi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($catatan['diagnosa'])): ?>
                    <?php foreach ($catatan['diagnosa'] as $dx): ?>
                        <tr>
                            <td><?= p($dx['diagnosa']) ?></td>
                            <td><?= p($dx['tgl_ditemukan']) ?></td>
                            <td><?= p($dx['tgl_teratasi']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" style="text-align:center">-</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <h3 class="mt-5">Intervensi Keperawatan</h3>

        <table class="data">
            <thead>
                <tr>
                    <th width="40%">Diagnosa</th>
                    <th width="30%">Tujuan dan Kriteria Hasil</th>
                    <th width="30%">Intervensi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($catatan['intervensi'])): ?>
                    <?php foreach ($catatan['intervensi'] as $inv): ?>
                        <tr>
                            <td><?= p($inv['diagnosa']) ?></td>
                            <td><?= p($inv['tujuan_kriteria']) ?></td>
                            <td><?= p($inv['intervensi']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" style="text-align:center">-</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <h3 class="mt-5">Implementasi Keperawatan</h3>

        <table class="data">
            <thead>
                <tr>
                    <th>No. DX</th>
                    <th>Hari/Tanggal</th>
                    <th>Jam</th>
                    <th>Implementasi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($catatan['implementasi'])): ?>
                    <?php foreach ($catatan['implementasi'] as $impl): ?>
                        <tr>
                            <td><?= p($impl['no_dx']) ?></td>
                            <td><?= p($impl['hari_tgl']) ?></td>
                            <td><?= p($impl['jam']) ?></td>
                            <td><?= p($impl['implementasi']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" style="text-align:center">-</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <h3 class="mt-5">Evaluasi Keperawatan</h3>

        <table class="data">
            <thead>
                <tr>
                    <th>No. DX</th>
                    <th>Hari/Tanggal</th>
                    <th>Jam</th>
                    <th>S</th>
                    <th>O</th>
                    <th>A</th>
                    <th>P</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($catatan['evaluasi'])): ?>
                    <?php foreach ($catatan['evaluasi'] as $eval): ?>
                        <tr>
                            <td><?= p($eval['no_dx']) ?></td>
                            <td><?= p($eval['hari_tgl']) ?></td>
                            <td><?= p($eval['jam']) ?></td>
                            <td><?= p($eval['evaluasi_s']) ?></td>
                            <td><?= p($eval['evaluasi_o']) ?></td>
                            <td><?= p($eval['evaluasi_a']) ?></td>
                            <td><?= p($eval['evaluasi_p']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" style="text-align:center">-</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

    </div>
</body>

</html>