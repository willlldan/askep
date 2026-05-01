<?php
// Shortcut per section
$demografi   = $sections['data_demografi'] ?? [];
$riwayat     = $sections['riwayat_kehamilan_kesehatan'] ?? [];
$fisik       = $sections['pengkajian_fisik'] ?? [];
$terapi      = $sections['program_terapi_lab'] ?? [];
$catatan     = $sections['catatan_keperawatan'] ?? [];


include 'template_pdf.php';
?>


<body>
    <div >

        <!-- HEADER -->
        <h1>Format Pengkajian Ginekologi</h1>
        <h2>Keperawatan Maternitas</h2>

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
        <h3 class="mt-5">A. Data Demografi</h3>

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
                <td><?= p($riwayat['berapalama1']) ?></td>
            </tr>
            <tr>
                <td><strong>Riwayat Ginekologi</strong></td>
                <td>:</td>
                <td colspan="4"><?= p($riwayat['riwayat_ginekologi']) ?></td>
            </tr>
            <tr>
                <td><strong>Masalah Ginekologi</strong></td>
                <td>:</td>
                <td colspan="4"><?= p($riwayat['masalah_ginekologi']) ?></td>
            </tr>
            <tr>
                <td><strong>Riwayat Penyakit Keluarga</strong></td>
                <td>:</td>
                <td colspan="4"><?= p($riwayat['riwayat_penyakit']) ?></td>
            </tr>
            <tr>
                <td><strong>Riwayat KB</strong></td>
                <td>:</td>
                <td colspan="4"><?= p($riwayat['riwayat_kb']) ?></td>
            </tr>
        </table>
        
       <h3 class="mt-5">Riwayat Kesehatan Saat ini</h3>

<table class="header-table">
    <tr>
        <td width="30%"><strong>Keluhan Utama</strong></td>
        <td>:</td>
        <td width="18%"><?= p($riwayat['keluhan_utama']) ?></td>
    </tr>
    <tr>
        <td width="30%"><strong>Riwayat Keluhan Utama</strong></td>
        <td>:</td>
        <td width="18%"><?= p($riwayat['riwayat_keluhan_utama']) ?></td>
    </tr>

    <h3 class="mt-5">Keadaan Umum & Kesadaran</h3>
    <tr>
        <td><strong>Kesadaran</strong></td>
        <td>:</td>
        <td colspan="4"><?= p($riwayat['kesadaran']) ?></td>
    </tr>
    <tr>
        <td><strong>Keadaan Umum</strong></td>
        <td>:</td>
        <td colspan="10"><?= p($riwayat['umum']) ?></td>
    </tr> 
    <tr>
        <td><strong>Lengan Atas</strong></td>
        <td>:</td>
        <td><?= p($riwayat['lengan_atas']) ?> cm</td>
    </tr>
    <tr>
        <td><strong>BB/TB</strong></td>
        <td>:</td>
        <td><?= p($riwayat['bb_tb']) ?> kg/cm</td>
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
            'Inpeksi Mata '=> [
            
                ['Konjungtiva', 'Apakah anemis/ an-anemis', $fisik['inspeksik_konjung_tiva']],
                ['Sklera', 'Ikterik/ An-ikterik', $fisik['inspeksi_sklrea']],
            ],
            'palpasi Mata' => [
                ['Kelopak mata', 'Nyeri tekan/ tidak', $fisik['palpasi_kelopak_mata']],
                ['Masalah Khusus', '', $fisik['masalah_kepala']],
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
                ['Inspeksi bentuk', 'Bentuk: simetris/tidak. Kebersihan: apakah ada perdarahan, peradangan, kotoran/serumen atau tidak ada', $fisik['inspeksi_telinga']],
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
                ['Palpasi', 'APembesaran kelenjar limfe: Ya/Tidak?', $fisik['palpasi_axila']],
                ['Masalah Khusus', '', $fisik['masalah_axila']],
            ],
            
            'Dada; (Sistem Pernapasan)' => [
                ['Inpeksi', 'Bentuk dada, apakah ada retraksi interkostalis atau tidak, ekspansi dada, gerakan dinding dada dan taktil premitus', $fisik['inspeksi_dada']],
                ['Palpasi', 'Apakah pekak, redup, sonor, hipersonor, timpani?', $fisik['palpasi_dada']],
                ['Auskultasi', 'Bunyi napas', $fisik['auskultasi_dada']],
                ['Masalah Khusus', '', $fisik['masalah_khusus_dada']],
            ],
            'Sistem Kardiovaskuler' => [
                ['Inspeksi dan Palpasi', 'Area aorta dan pulmonal', $fisik['inspeksi_dan_palpasi_sistem']],
                ['Perkusi', 'Perkusi batas jantung', $fisik['dada1']],
                ['', 'Suara perkusi: (pekak, redup, sonor, hipersonor, timpani)', $fisik['khusus']],
                ['Auskultasi', 'Suara jantung. ', $fisik['auskultasi_sistem']],
                ['', 'Suara jantung tambahan: apakah ada Mur-mur dan gallop', $fisik['auskultasi']],
                ['Masalah Khusus', '', $fisik['khusus']],
            ],
            'Payudara' => [
                ['Inspeksi Bentuk', 'Bentuk, Lesi, Kebersihan', $fisik['inspeksi_bentuk']],
                ['Inspeksi Pengeluaran Cairan', 'Colostim dan ASI (Ada atau tidak)', $fisik['inspeksi_pengeluaran_cairan']],
                ['Inspeksi Tanda Pembengkakan', 'Tanda Pembengkakan: Ya/Tidak', $fisik['inspeksi_pembengkakan']],
                ['Palpasi Raba', 'Teraba hangat: Ya/Tidak', $fisik['palpasi_raba']],
                ['Palpasi Benjolan', 'Ada/Tidak Ada', $fisik['palpasi_benjolan']],
                ['Masalah Khusus', '', $fisik['masalah_khusus_payudadra']],
            ],
            'Abdomen' => [
                ['Inspeksi Bentuk', 'Bentuk, Warna Kulit, Jaringan Perut (ada/tidak), Strie (ada/tidak), Luka (ada/tidak)', $fisik['inspeksi_abdomen']],
                ['Auskultasi', '', $fisik['auskultasi_bising_usus']],
                ['Perkusi', 'Bunyi (Pekak, redup, sonor, hipersonor, timpani)', $fisik['perkusi']],
                ['Palpasi', 'Nyeri tekan', $fisik['palpasi_involusi']],
                ['', 'Kandung Kemih: teraba/tidak, penuh/tidak. Hasil', $fisik['palpasi_kandung_kemih']],
                ['Masalah Khusus', '', $fisik['masalah_khusus__abdomen']],
            ],
            'Genital dan Anus' => [
                ['Genetalia dan Anus', 'Pendarahan: (ya/tidak), jika ya: warna, sudah berapa lama, konsistensi', $fisik['pendarahan']],
                ['Hemoroid', 'Ya/Tidak', $fisik['hemoroid']],
                ['Keputihan', 'Keputihan (ya/tidak), warna, konsistensi, bau, dan gatal', $fisik['keputihan']],
                ['Masalah Khusus', '', $fisik['masalah_khusus_genetalia']],
            ],
            'Ekstremitas' => [
                ['Ekstremitas Atas', 'Apakah terdapat edema (Ya/Tidak), rasa kesemutan/baal (Ya/Tidak), Kekuatan otot', $fisik['inspeksi_ekstremitas_atas']],
                ['Ekstremitas Bawah', 'Apakah terdapat edema (Ya/Tidak), Varises (Ya/Tidak),  Refleks Patella (+/-), apakah terdapat kekakuan sendi, dan kekuatan otot', $fisik['inspeksi_ekstremitas_bawah']],
                ['Keputihan', 'Keputihan (ya/tidak), warna, konsistensi, bau, dan gatal', $fisik['keputihan']],
                ['Masalah Khusus', '', $fisik['masalah_khusus_genetalia']],
            ],
            'Integumen' => [
                ['Inspeksi', 'Warna, turgor, elastisitas, ulkus', $fisik['inspeksi_integumen']],
                ['Palpasi', 'Akral, CRT, dan Nyeri', $fisik['palpasi_integumen']],
                ['Keputihan', 'Keputihan (ya/tidak), warna, konsistensi, bau, dan gatal', $fisik['keputihan']],
                ['Masalah Khusus', '', $fisik['masalah_khusus_genetalia']],
            ],
            'Eliminasi' => [
                ['Urin', 'BAK saat ini: nyeri (ya/tidak), frekuensi, jumlah', $fisik['bak']],
                ['Masalah Khusus', '', $fisik['masalah_khusus_bak']],
                ['BAB', 'BAB saat ini: Konstipasi (Ya/Tidak), Frekuensi', $fisik['inspeksi_bab']],
                ['Masalah Khusus', '', $fisik['masalah_khusus_bab']],
            ],
            'Istirahat dan Kenyamanan' => [
                ['Pola Tidur Saat Ini', 'Kebiasaan tidur, lama dalam hitungan jam, frekuensi', $fisik['pola_tidur']],
                ['', 'Keluhan ketidaknyamanan  (Ya/Tidak), lokasi', $fisik['kenyamanan']],
                ['Masalah Khusus', '', $fisik['masalah_istirahat']],
            ],
            'Mobilisasi dan Latihan' => [
                ['Tingkat Mobilisasi', 'Apakah mandiri, parsial, total', $fisik['tingkat_mobilisasi']],
                ['Masalah Khusus', '', $fisik['masalah_mobilisasi']],
            ],
            'Pola Nutrisi dan Cairan' => [
                ['Jenis Makanan', '', $fisik['jenis_makanan']],
                ['Frekuensi', '', $fisik['frekuensi']],
                ['Konsumsi Snack', '', $fisik['konsumsi_snack']],
                ['Nafsu Makan', '', $fisik['nafsu_makan']],
                ['Pola Minum', '', $fisik['pola_minum']],
                ['Frekuensi', '', $fisik['frekuensi2']],
                ['Pantangan Makanan', '', $fisik['pantangan_makanan']],


            ],
            





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
                <?php if (!empty($terapi['klasifikasi'])): ?>
                    <?php foreach ($terapi['klasifikasi'] as $klasfi): ?>
                        <tr>
                            <td><?= p($klasfi['data_subjektif']) ?></td>
                            <td><?= p($klasfi['data_objektif']) ?></td>
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
                <?php if (!empty($terapi['analisa'])): ?>
                    <?php foreach ($terapi['analisa'] as $analis): ?>
                        <tr>
                            <td><?= p($analis['ds_do']) ?></td>
                            <td><?= p($analis['etiologi']) ?></td>
                            <td><?= p($analis['masalah']) ?></td>
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