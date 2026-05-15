<?php
// Shortcut per section
$demografi   = $sections['data_umum'] ?? [];
$riwayat     = $sections['riwayat_persalinan'] ?? [];
$terapi      = $sections['program_terapi_lab'] ?? [];
$analisa     = $sections['analisa_data'] ?? [];
$lainnya    = $sections['lainnya'] ?? [];


include 'template_pdf.php';
?>


<body>
    <div >

        <!-- HEADER -->
        <h1>Format Pengkajian Asuhan Keperawatan</h1>
        <h2>Inranatal Care</h2>

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
        <h3 class="mt-5">Data Umum</h3>

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
        <h3 class="mt-5">Data Umum Kesehatan</h3>

    
        <table class="header-table">
            <tr>
                <td><strong>TB/BB</strong></td>
                <td>:</td>
                <td><?= p($demografi['bbtb']) ?> cm/kg</td>
            </tr>
            <tr>
                <td><strong>BB Sebelum Hamil</strong></td>
                <td>:</td>
                <td><?= p($demografi['bb_sebelum_hamil']) ?> kg</td>
            </tr>
            <tr>
                <td><strong>LILA</strong></td>
                <td>:</td>
                <td><?= p($demografi['lila']) ?> cm</td>
            </tr>
            <tr>
                <td><strong>Kehamilan Sekarang Direncanakan</strong></td>
                <td>:</td>
                <td><?= p($demografi['rencana_kehamilan']) ?> </td>
            </tr>
            <tr>
                <td width="30%"><strong>Status Obstetrik</strong></td>
                <td width="2%">:</td>
                <td colspan="4">
                    G<?= p($demografi['g']) ?>
                    P<?= p($demografi['p']) ?>
                    A<?= p($demografi['a']) ?>
                </td>
            </tr>
            
            <tr>
                <td><strong>HPHT</strong></td>
                <td>:</td>
                <td width="18%"><?= p($demografi['hpht']) ?></td>
                <td width="20%"><strong>TP</strong></td>
                <td>:</td>
                <td><?= p($demografi['tp']) ?></td>
            </tr>
            <tr>
                <td><strong>Obat-obatan yang dikonsumsi</strong></td>
                <td>:</td>
                <td><?= p($demografi['obat_dikonsumsi']) ?> </td>
            </tr>
             <tr>
                <td><strong>Apakah ada alergi terhadap seseuatu</strong></td>
                <td>:</td>
                <td><?= p($demografi['alergi']) ?> </td>
            </tr>
             <tr>
                <td><strong>Alat bantu yang digunakan (Gigi tiruan, kacamata/lensa kontak, alat dengar) Lain-lain, sebutkan</strong></td>
                <td>:</td>
                <td><?= p($demografi['alat_bantu']) ?> </td>
            </tr>
             <tr>
                <td><strong>BAK terakhir,   Masalah</strong></td>
                <td>:</td>
                <td><?= p($demografi['bak_terakhir']) ?> </td>
            </tr>
             <tr>
                <td><strong>BAB terakhir, Masalah</strong></td>
                <td>:</td>
                <td><?= p($demografi['bab_terakhir']) ?> </td>
            </tr>
             <tr>
            <tr>
                <td><strong>Kebiasaan waktu tidur </strong></td>
                <td>:</td>
                <td width="20%">
                    Siang: <?= p($demografi['siang']) ?>
                    Malam: <?= p($demografi['malam']) ?>
                </td>
            </tr>
             <tr>
                <td><strong>Riwayat kesehatan yang lalu</strong></td>
                <td>:</td>
                <td><?= p($demografi['riwayat']) ?> </td>
            </tr>
        </table>
        <!-- ================================ -->
        <!-- SECTION 2: RIWAYAT KEHAMILAN -->
        <!-- ================================ -->
        <!-- riwayat Persalinan -->
            <h4>Riwayat Persalinan</h4>

        <table class="data">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Jenis Kelamin</th>
                    <th>Cara Lahir</th>
                    <th>BB Lahir (gram)</th>
                    <th>Keadaan</th>
                    <th>Umur</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($riwayat['riwayat_persalinan'])): ?>
                    <?php foreach ($riwayat['riwayat_persalinan'] as $i => $row): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td><?= p($row['jenis']) ?></td>
                            <td><?= p($row['cara_lahir']) ?></td>
                            <td><?= p($row['bb']) ?></td>
                            <td><?= p($row['keadaan']) ?></td>
                            <td><?= p($row['umur']) ?></td>
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
                <td width="30%"><strong>TB/BB</strong></td>
                <td width="2%">:</td>
                <td><?= p($demografi['bbtb']) ?> cm/kg</td>
            </tr>
             <tr>
                <td><strong>Kelas Prenatal</strong></td>
                <td>:</td>
                <td><?= p($riwayat['kelas_prenatal']) ?></td>
        </tr>
        <tr>
                <td><strong>Jumlah Kunjungan ANC pada kehamilan ini</strong></td>
                <td>:</td>
                <td><?= p($riwayat['anc']) ?></td>
        </tr>
        <tr>
                <td><strong>Masalah Kehamilan yang Lalu</strong></td>
                <td>:</td>
                <td><?= p($riwayat['masalah_kehamilanlalu']) ?></td>
        </tr>
        <tr>
                <td><strong>Masalah Kehamilan Sekarang</strong></td>
                <td>:</td>
                <td><?= p($riwayat['masalah_kehamilan_sekarang']) ?></td>
        </tr>
        <tr>
                <td><strong>Rencana KB</strong></td>
                <td>:</td>
                <td><?= p($riwayat['rencana_kb']) ?></td>
        </tr>
        <tr>
                <td><strong>Makanan Bayi Sebelumnya</strong></td>
                <td>:</td>
                <td ><?= p($riwayat['makanan_bayi']) ?></td>
        </tr>
        <tr>
                <td><strong>Setelah bayi lahir, siapa yang diharapkan membantu?</strong></td>
                <td>:</td>
                <td><?= p($riwayat['bayi_lahir']) ?></td>
        </tr>
        <tr>
                <td><strong>Masalah Persalinan</strong></td>
                <td>:</td>
                <td ><?= p($riwayat['masalah_persalinan']) ?></td>
        </tr>
        </table>
   
     

        
        <h3 class="mt-5">Riwayat Persalinan Sekarang</h3>
        <div class="field-row">
            <div class="field-label">Keluhan Utama</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($riwayat['keluhan_utama']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Riwayat Keluhan Utama</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($riwayat['riwayat_keluhan_utama']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Mulai Persalinan</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($riwayat['mulai_persalinan']) ?></div>
        </div>

        <h4>Tanda-tanda Vital</h4>
        <table class="header-table">
            <tr class="field-row">
                <td class="field-label"><strong>Tekanan Darah</strong></td>
                <td class="field-sep">:</td>
                <td><?= p($riwayat['tekana_ndarah']) ?> mmHg</td>
            </tr>
            <tr class="field-row">
                <td class="field-label"><strong>Nadi</strong></td>
                <td class="field-sep">:</td>
                <td ><?= p($riwayat['nadi']) ?> x/menit</td>
            </tr>
            <tr class="field-row">
                <td class="field-label"><strong>Suhu</strong></td>
                <td class="field-sep">:</td>
                <td ><?= p($riwayat['suhu']) ?> °C</td>
            </tr>
            <tr class="field-row">
                <td class="field-label"><strong>RR</strong></td>
                <td class="field-sep">:</td>
                <td ><?= p($riwayat['rr']) ?> x/menit</td>
            </tr>
        </table>
                <div class="page-break"></div>

       
        <h3 class="mt-5"></h3>

        <?php
        $riwayat_items = [
            'Kepala dan Rambut' => [
                ['Kepala dan Rambut', 'Rontok (Ya/Tidak), Kulit Kepala (Bersih/Kotor), Nyeri Tekan (Ya/Tidak). Hasil:', $riwayat['kepala_dan_rambut']],
            ],
            'Wajah' => [
                ['Hiperpigmentasi (Cloasma Gravidarum)', 'Ya/Tidak, Area ', $riwayat['inspeksi_wajah']],
                ['Masalah Khusus', '', $riwayat['masalah_wajah']],
            ],
            'Mata' => [
                ['Konjungtiva', 'Anemis/An-anemis', $riwayat['konjungtiva']],
                ['Sklera', 'Ikterik/An-ikterik', $riwayat['sklerag']],
            ],
            'Mulut' => [
                ['Mukosa Bibir', 'Lembab/Kering', $riwayat['mukosa_bibir']],
                ['Sariawan', '(Ada/Tidak Ada)', $riwayat['sariawan']],
                ['Gigi Berlubang', 'Ya/Tidak', $riwayat['gigi_berlubang']],
                ['Masalah Khusus', '', $riwayat['masalah_khusus_mulut']],
            ],
            'Dada/Thorax' => [
                ['Bunyi Jantung', 'Normal atau apakah terdapat Mur-mur dan Gallop', $riwayat['bunyi_jantung']],
                ['Masalah Khusus', '', $riwayat['masalah_khusus_bunyi_jantung']],
                ['Sistem Pernapasan', 'Suara Napas (Vesikuler/Wheezing/Ronkhi)', $riwayat['sistem_pernapasan']],
                ['Masalah Khusus', '', $riwayat['masalah_khusus_sistem_pernapasan']],
            ],
            
            'Payudara' => [
                ['Pengeluaran ASI/Kolostum', 'Ya/Tidak', $riwayat['pengeluaran_asi']],
                ['Puting', '(Eksverted/Inverted/Platnipple)', $riwayat['puting']],
                ['Masalah Khusus', '', $riwayat['masalah_khusus_payudadra']],
            ],
            'abdomen' => [
                ['Abdomen', 'Apakah terdapat: Lignea Nigra/Striae Nigra/Striae Alba, Bekas Operasi', $riwayat['abdomen']],
                ['Pemeriksaan Palpasi Abdomen', '', $riwayat['pemeriksaan_palpasi_abdomen']],
            ],
        ];

        foreach ($riwayat_items as $title => $fields): ?>
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

        <div class="subsection-title">Uterus</div>
        <table class="header-table">
            <tr>
                <td width="30%" style="border:1px solid #000;"><strong>TFU</strong></td>
                <td width="2%" style="border:1px solid #000;">:</td>
                <td width="18%" style="border:1px solid #000;"><?= p($riwayat['inspeksitfu']) ?> cm</td>
                <td width="20%" style="border:1px solid #000;"><strong>Kontraksi</strong></td>
                <td width="2%" style="border:1px solid #000;">:</td>
                <td style="border:1px solid #000;"><?= p($riwayat['inspeksi_kontraksi']) ?></td>
            </tr>
            <tr style="border:1px solid #000;">
                <td style="border:1px solid #000;"><strong>Leopold I</strong></td>
                <td style="border:1px solid #000;">:</td>
                <td colspan="4" style="border:1px solid #000;"><?= p($riwayat['leopoldi']) ?></td>
            </tr>
            <tr style="border:1px solid #000;">
                <td style="border:1px solid #000;"><strong>Leopold II Kanan</strong></td>
                <td style="border:1px solid #000;">:</td>
                <td style="border:1px solid #000;"><?= p($riwayat['kanan']) ?></td>
                <td style="border:1px solid #000;"><strong>Leopold II Kiri</strong></td>
                <td style="border:1px solid #000;">:</td>
                <td style="border:1px solid #000;"><?= p($riwayat['kiri']) ?></td>
            </tr>
            <tr style="border:1px solid #000;">
                <td style="border:1px solid #000;"><strong>Leopold III</strong></td>
                <td style="border:1px solid #000;">:</td>
                <td colspan="4" style="border:1px solid #000;"><?= p($riwayat['leopoldiii']) ?></td>
            </tr>
            <tr style="border:1px solid #000;">
                <td style="border:1px solid #000;"><strong>Leopold IV</strong></td>
                <td style="border:1px solid #000;">:</td>
                <td colspan="4" style="border:1px solid #000;"><?= p($riwayat['leopoldiv']) ?></td>
            </tr>
            <tr style="border:1px solid #000;">
                <td style="border:1px solid #000;"><strong>Pemeriksaan DJJ</strong></td>
                <td style="border:1px solid #000;">:</td>
                <td style="border:1px solid #000;"><?= p($riwayat['pemeriksaandjj']) ?> Frek</td>
                <td style="border:1px solid #000;"><strong>Intensitas</strong></td>
                <td style="border:1px solid #000;">:</td>
                <td style="border:1px solid #000;"><?= p($riwayat['intensitas']) ?></td>
            </tr>
            <tr style="border:1px solid #000;">
                <td style="border:1px solid #000;"><strong>Keteraturan</strong></td>
                <td style="border:1px solid #000;">:</td>
                <td colspan="4" style="border:1px solid #000;"><?= p($riwayat['keteraturan']) ?></td>
            </tr>
            <tr style="border:1px solid #000;">
                <td style="border:1px solid #000;"><strong>Status janin:</strong></td>
                <td style="border:1px solid #000;">:</td>
                <td style="border:1px solid #000;"><?= p($riwayat['status_janin']) ?></td>
                <td style="border:1px solid #000;"><strong>Jumlah</strong></td>
                <td style="border:1px solid #000;">:</td>
                <td style="border:1px solid #000;"><?= p($riwayat['jumlah']) ?></td>
            </tr>
        </table>

        <?php
        $riwayat_items2 = [
            
            'Ekstremitas' => [
                ['Ekstremitas Atas', 'Apakah terdapat edema (Ya/Tidak), rasa kesemutan/baal (Ya/Tidak), Kekuatan otot', $riwayat['ekstremitas_atas']],
                ['Ekstremitas Bawah', 'Apakah terdapat edema (Ya/Tidak), Varises (Ya/Tidak), Tanda Homan (+/-), Refleks Patella (+/-), kekakuan sendi, kekuatan otot', $riwayat['ekstremitas_bawah']],
 
            ],
            'Vagina' => [
                ['Vagina', 'Persiapan perineum.', $riwayat['persiapan_perineum']],
                ['', 'Pengeluaran Pervaginam', $riwayat['pengeluaran_pervaginam']],
            ],
            
        ];

        foreach ($riwayat_items2 as $title => $fields): ?>
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
        <!-- SECTION 3: PROGRAM TERAPI & LAB -->
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
                <?php if (!empty($lainnya['diagnosa'])): ?>
                    <?php foreach ($lainnya['diagnosa'] as $dx): ?>
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
                <?php if (!empty($lainnya['intervensi'])): ?>
                    <?php foreach ($lainnya['intervensi'] as $inv): ?>
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