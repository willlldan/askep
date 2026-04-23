<?php
// Shortcut per section
$identitas   = $sections['identitas'] ?? [];
$biologis    = $sections['data_biologis'] ?? [];
$riwayat     = $sections['riwayat_kehamilan'] ?? [];
$fisik1      = $sections['pemeriksaan_fisik'] ?? [];
$fisik2      = $sections['pemeriksaan_fisik2'] ?? [];
$fisik3      = $sections['pemeriksaan_fisik3'] ?? [];
$terapi      = $sections['program_terapi_lab'] ?? [];
$lainnya     = $sections['lainnya'] ?? [];

include 'template_pdf.php';
?>

<body>
    <div>

        <!-- HEADER -->
        <h1>Format Pengkajian Asuhan Keperawatan</h1>
        <h2>Post Partum</h2>

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
        <!-- SECTION 1: DATA UMUM -->
        <!-- ================================ -->
        <h3>Data Umum</h3>

        <table class="header-table" style="border:1px solid #000;">
            <tr>
                <td width="25%"><strong>Inisial Pasien</strong></td>
                <td width="2%">:</td>
                <td width="23%"><?= p($identitas['inisial_pasien']) ?></td>
                <td width="25%"><strong>Nama Suami</strong></td>
                <td width="2%">:</td>
                <td><?= p($identitas['nama_suami']) ?></td>
            </tr>
            <tr>
                <td><strong>Usia</strong></td>
                <td>:</td>
                <td><?= p($identitas['usia_istri']) ?></td>
                <td><strong>Usia</strong></td>
                <td>:</td>
                <td><?= p($identitas['usia_suami']) ?></td>
            </tr>
            <tr>
                <td><strong>Pekerjaan</strong></td>
                <td>:</td>
                <td><?= p($identitas['pekerjaan_istri']) ?></td>
                <td><strong>Pekerjaan</strong></td>
                <td>:</td>
                <td><?= p($identitas['pekerjaan_suami']) ?></td>
            </tr>
            <tr>
                <td><strong>Pendidikan Terakhir</strong></td>
                <td>:</td>
                <td><?= p($identitas['pendidikan_terakhir_istri']) ?></td>
                <td><strong>Pendidikan Terakhir</strong></td>
                <td>:</td>
                <td><?= p($identitas['pendidikan_terakhir_suami']) ?></td>
            </tr>
            <tr>
                <td><strong>Agama</strong></td>
                <td>:</td>
                <td><?= p($identitas['agama_istri']) ?></td>
                <td><strong>Agama</strong></td>
                <td>:</td>
                <td><?= p($identitas['agama_suami']) ?></td>
            </tr>
            <tr>
                <td><strong>Suku Bangsa</strong></td>
                <td>:</td>
                <td><?= p($identitas['suku_bangsa']) ?></td>
                <td></td><td></td><td></td>
            </tr>
            <tr>
                <td><strong>Status Perkawinan</strong></td>
                <td>:</td>
                <td><?= p($identitas['status_perkawinan']) ?></td>
                <td></td><td></td><td></td>
            </tr>
            <tr>
                <td><strong>Alamat</strong></td>
                <td>:</td>
                <td colspan="4"><?= p($identitas['alamat']) ?></td>
            </tr>
            <tr>
                <td><strong>Diagnosa Medik</strong></td>
                <td>:</td>
                <td colspan="4"><?= p($identitas['diagnosa_medik']) ?></td>
            </tr>
        </table>

        <!-- ================================ -->
        <!-- SECTION 2: RIWAYAT KEHAMILAN -->
        <!-- ================================ -->
        <h3>Riwayat Kehamilan dan Persalinan yang Lalu</h3>

        <h4>Riwayat Kehamilan yang Lalu</h4>
        <div class="field-row">
            <div class="field-label">Pemeriksaan ANC</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($riwayat['pemeriksaan']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Masalah Kehamilan</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($riwayat['masalah_kehamilan']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Riwayat Persalinan</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($riwayat['riwayat_persalinan']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Riwayat KB</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($riwayat['riwayat_kb']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Jumlah Perdarahan</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($riwayat['jumlah_pendarahan']) ?></div>
        </div>

        <h4>Riwayat Persalinan yang Lalu</h4>
        <table class="data">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tahun</th>
                    <th>Jenis Persalinan</th>
                    <th>Penolong</th>
                    <th>Jenis Kelamin</th>
                    <th>BB/TB Bayi</th>
                    <th>Menyusui Berapa Lama</th>
                    <th>Masalah Kehamilan</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($riwayat['riwayat'])): ?>
                    <?php foreach ($riwayat['riwayat'] as $i => $row): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td></td>
                            <td><?= p($row['jenis_persalinan']) ?></td>
                            <td><?= p($row['penolong']) ?></td>
                            <td><?= p($row['jenis_kelamin']) ?></td>
                            <td><?= p($row['bbtb_bayi']) ?></td>
                            <td><?= p($row['menyesui_berapa_lama']) ?></td>
                            <td><?= p($row['masalah_kehamilan']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" style="text-align:center">-</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- ================================ -->
        <!-- SECTION 3: DATA UMUM KESEHATAN -->
        <!-- ================================ -->
        <h3>Data Umum Kesehatan Saat Ini</h3>

        <h4>Data Biologis / Fisiologis</h4>
        <div class="field-row">
            <div class="field-label">Keluhan Utama</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($identitas['keluhan_utama']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Riwayat Keluhan Utama</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($identitas['riwayat_keluhan_utama']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Biologis / Fisiologis</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($biologis['biologis_fisiologis']) ?></div>
        </div>

        <table class="header-table">
            <tr>
                <td width="30%"><strong>Status Obstetri</strong></td>
                <td width="2%">:</td>
                <td colspan="4">
                    NH<?= p($biologis['nh']) ?>
                    P<?= p($biologis['p']) ?>
                    A<?= p($biologis['a']) ?>
                </td>
            </tr>
            <tr>
                <td><strong>Bayi Rawat Gabung</strong></td>
                <td>:</td>
                <td><?= p($biologis['bayi_rawat_gabung']) ?></td>
                <td width="20%"><strong>Jika Tidak, Alasan</strong></td>
                <td width="2%">:</td>
                <td><?= p($biologis['tidak_ada_alasan']) ?></td>
            </tr>
            <tr>
                <td><strong>Keadaan Umum</strong></td>
                <td>:</td>
                <td><?= p($biologis['keadaan_umum']) ?></td>
                <td><strong>Kesadaran</strong></td>
                <td>:</td>
                <td><?= p($biologis['kesadaran']) ?></td>
            </tr>
            <tr>
                <td><strong>BB/TB</strong></td>
                <td>:</td>
                <td colspan="4"><?= p($biologis['bb_tb']) ?> kg/cm</td>
            </tr>
        </table>

        <h4>Tanda-tanda Vital</h4>
        <table class="header-table">
            <tr>
                <td width="30%"><strong>Tekanan Darah</strong></td>
                <td width="2%">:</td>
                <td width="18%"><?= p($biologis['tekanan_darah']) ?></td>
                <td width="20%"><strong>Nadi</strong></td>
                <td width="2%">:</td>
                <td><?= p($biologis['nadi']) ?></td>
            </tr>
            <tr>
                <td><strong>Suhu</strong></td>
                <td>:</td>
                <td><?= p($biologis['suhu']) ?></td>
                <td><strong>Pernapasan</strong></td>
                <td>:</td>
                <td><?= p($biologis['pernapasan']) ?></td>
            </tr>
        </table>

        <div class="page-break"></div>

        <!-- ================================ -->
        <!-- SECTION 4: PEMERIKSAAN FISIK -->
        <!-- ================================ -->
        <h3>Pemeriksaan Fisik</h3>

        <?php
        $pengkajian_items = [
            'Kepala dan Rambut' => [
                ['Inspeksi', 'Bentuk kepala, penyebaran, kebersihan, warna rambut', $fisik1['inspeksi_kepala']],
                ['Palpasi', 'Apakah terdapat atau tidak benjolan, pembengkakan, nyeri tekan', $fisik1['palpasi_kepala']],
                ['Masalah Khusus', '', $fisik1['masalah_khusus_kepala']],
            ],
            'Wajah' => [
                ['Inspeksi', 'Bentuk, adakah hiperpigmentasi/cloasma gravidarum, area mana jika ada cloasma', $fisik1['inspeksi_wajah']],
                ['Palpasi', 'Adakah nyeri tekan/tidak ada', $fisik1['palpasi_wajah']],
                ['Masalah Khusus', '', $fisik1['masalah_khusus_wajah']],
            ],
            'Mata' => [
                ['Inspeksi', 'Konjungtiva: anemis/an-anemis. Sklera: ikterik/an-ikterik', $fisik1['inspeksik_mata']],
                ['Palpasi Kelopak Mata', 'Nyeri tekan/tidak', $fisik1['palpasi_kelopak_mata']],
                ['Masalah Khusus', '', $fisik1['masalah_khusus_mata']],
            ],
            'Hidung' => [
                ['Inspeksi', 'Apakah ada pembengkakan/tidak, kesimetrisan lubang hidung, kebersihan, septum utuh/tidak', $fisik1['inspeksi_hidung']],
                ['Palpasi', 'Nyeri tekan/tidak', $fisik1['palpasi_hidung']],
                ['Masalah Khusus', '', $fisik1['masalah_khusus_hidung']],
            ],
            'Mulut' => [
                ['Inspeksi Bibir', 'Warna, kesimetrisan, kelembapan, bibir sumbing, ulkus', $fisik1['inspeksi_bibir']],
                ['Inspeksi Gigi', 'Amati jumlah, warna, kebersihan, karies', $fisik1['inspeksi_gigi']],
                ['Inspeksi Gusi', 'Adakah atau tidak lesi/pembengkakan', $fisik1['inspeksi_gusi']],
                ['Inspeksi Lidah', 'Amati warna dan kebersihan', $fisik1['inspeksi_lidah']],
                ['Inspeksi Bau Mulut', '', $fisik1['inspeksi_bau_mulut']],
                ['Palpasi', 'Apakah ada nyeri tekan atau tidak ada', $fisik1['palpasi_mulut']],
                ['Masalah Khusus', '', $fisik1['masalah_khusus_mulut']],
            ],
            'Telinga' => [
                ['Inspeksi', 'Bentuk: simetris/tidak. Kebersihan: apakah ada perdarahan, peradangan, kotoran/serumen atau tidak ada', $fisik1['inspeksi_telinga']],
                ['Palpasi Nyeri Tekan', 'Apakah ada pembengkakan, nyeri tekan atau tidak ada', $fisik1['palpasi_nyeri_tekan']],
                ['Palpasi Gangguan Pendengaran', 'Apakah ada gangguan atau tidak', $fisik1['palpasi_gangguan']],
                ['Masalah Khusus', '', $fisik1['masalah_khusus_telinga']],
            ],
            'Leher' => [
                ['Inspeksi', 'Ada massa dan benjolan atau tidak. Adakah distensi vena jugularis/tidak ada', $fisik1['inspeksi_leher']],
                ['Palpasi Kelenjar Tiroid', 'Apakah ada pembesaran kelenjar tiroid atau tidak', $fisik1['palpasi_kelenjar']],
                ['Palpasi Trakea', 'Apakah ada pergeseran/tidak', $fisik1['palpasi_trakea']],
                ['Palpasi Nyeri Menelan', 'Ya/tidak', $fisik1['palpasi_nyeri_menelan']],
                ['Masalah Khusus', '', $fisik1['masalah_khusus_leher']],
            ],
            'Axila' => [
                ['Inspeksi', 'Warna, pembengkakan', $fisik1['inspeksi_axilia']],
                ['Palpasi', 'Pembesaran kelenjar limfe: Ya/Tidak', $fisik1['palpasi_axilia']],
                ['Masalah Khusus', '', $fisik1['masalah_khusus_axilia']],
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

        <!-- Dada -->
        <div class="subsection-title">Dada; Sistem Pernapasan dan Kardiovaskuler</div>
        <div class="field-row">
            <div class="field-label">Auskultasi Bunyi Napas</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($fisik2['bunyinapas']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Auskultasi Suara Jantung</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($fisik2['suarajantung']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Masalah Khusus</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($fisik2['masalah_khusus_dada']) ?></div>
        </div>

        <!-- Payudara -->
        <div class="subsection-title">Payudara</div>
        <div class="field-row">
            <div class="field-label">Inspeksi Bentuk</div>
            <div class="field-sep">:</div>
            <div class="field-value">
                <div class="field-hint">Bentuk, lesi, kebersihan</div>
                <?= p($fisik2['inspeksi_bentuk']) ?>
            </div>
        </div>
        <div class="field-row">
            <div class="field-label">Colostrum &amp; ASI</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($fisik2['inspeksi_colostum']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Puting</div>
            <div class="field-sep">:</div>
            <div class="field-value">
                <div class="field-hint">Eksverted/Inverted/Plat nipple</div>
                <?= p($fisik2['inspeksi_puting']) ?>
            </div>
        </div>
        <div class="field-row">
            <div class="field-label">Tanda Pembengkakan</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($fisik2['inspeksi_pembengkakan']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Palpasi (Teraba Hangat)</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($fisik2['palpasi_raba']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Palpasi Benjolan</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($fisik2['palpasi_benjolan']) ?></div>
        </div>

        <!-- Abdomen -->
        <div class="subsection-title">Abdomen</div>
        <div class="field-row">
            <div class="field-label">Inspeksi</div>
            <div class="field-sep">:</div>
            <div class="field-value">
                <div class="field-hint">Bentuk, warna kulit, jaringan parut (ada/tidak), strie (ada/tidak), luka (ada/tidak)</div>
                <?= p($fisik2['inspeksiabdomen']) ?>
            </div>
        </div>
        <div class="field-row">
            <div class="field-label">Auskultasi Bising Usus</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($fisik2['auskultasi_bising_usus']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Perkusi</div>
            <div class="field-sep">:</div>
            <div class="field-value">
                <div class="field-hint">Bunyi: pekak, redup, sonor, hipersonor, timpani</div>
                <?= p($fisik2['perkusi']) ?>
            </div>
        </div>
        <div class="field-row">
            <div class="field-label">Palpasi Involusi Uterus</div>
            <div class="field-sep">:</div>
            <div class="field-value">
                <div class="field-hint">Tinggi fundus uterus dan kontraksi</div>
                <?= p($fisik2['palpasi_involusi']) ?>
            </div>
        </div>
        <div class="field-row">
            <div class="field-label">Palpasi Kandung Kemih</div>
            <div class="field-sep">:</div>
            <div class="field-value">
                <div class="field-hint">Teraba/tidak, penuh/tidak</div>
                <?= p($fisik2['palpasi_kandung_kemih']) ?>
            </div>
        </div>
        <div class="field-row">
            <div class="field-label">Masalah Khusus</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($fisik2['masalah_khusus_abdomen']) ?></div>
        </div>

        <!-- Perineum dan Genitalia -->
        <div class="subsection-title">Perineum dan Genitalia</div>
        <div class="field-row">
            <div class="field-label">Vagina</div>
            <div class="field-sep">:</div>
            <div class="field-value">
                <div class="field-hint">Integritas kulit, edema (ya/tidak), memar (ya/tidak), hematom (ya/tidak)</div>
                <?= p($fisik2['vagina']) ?>
            </div>
        </div>
        <div class="field-row">
            <div class="field-label">Perineum</div>
            <div class="field-sep">:</div>
            <div class="field-value">
                <div class="field-hint">Utuh/Episiotomi/Ruptur</div>
                <?= p($fisik2['perineum']) ?>
            </div>
        </div>

        <div class="subsection-title" style="font-size:9px; font-weight:bold; margin: 4px 0 2px 0;">Tanda REEDA</div>
        <table class="header-table">
            <tr>
                <td width="30%"><strong>R - Redness (Kemerahan)</strong></td>
                <td width="2%">:</td>
                <td width="18%"><?= p($fisik2['redness']) ?></td>
                <td width="20%"><strong>E - Edema</strong></td>
                <td width="2%">:</td>
                <td><?= p($fisik2['edema']) ?></td>
            </tr>
            <tr>
                <td><strong>E - Echimosis</strong></td>
                <td>:</td>
                <td><?= p($fisik2['echimosis']) ?></td>
                <td><strong>D - Discharge</strong></td>
                <td>:</td>
                <td><?= p($fisik2['discharge']) ?></td>
            </tr>
            <tr>
                <td><strong>A - Approximate</strong></td>
                <td>:</td>
                <td colspan="4"><?= p($fisik2['aprroximate']) ?></td>
            </tr>
        </table>

        <div class="field-row">
            <div class="field-label">Lochea</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($fisik2['lochea']) ?></div>
        </div>
        <table class="header-table">
            <tr>
                <td width="30%"><strong>Jumlah</strong></td>
                <td width="2%">:</td>
                <td width="18%"><?= p($fisik2['jumlah']) ?></td>
                <td width="20%"><strong>Jenis Warna</strong></td>
                <td width="2%">:</td>
                <td><?= p($fisik2['jenis_warna']) ?></td>
            </tr>
            <tr>
                <td><strong>Konsistensi</strong></td>
                <td>:</td>
                <td><?= p($fisik2['konsistensi']) ?></td>
                <td><strong>Bau</strong></td>
                <td>:</td>
                <td><?= p($fisik2['bau']) ?></td>
            </tr>
        </table>

        <div class="field-row">
            <div class="field-label">Hemorrhoid</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($fisik2['hemorrhoid']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Data Tambahan</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($fisik2['data_tambahan']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Masalah Khusus</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($fisik2['masalah']) ?></div>
        </div>

        <!-- Ekstremitas -->
        <div class="subsection-title">Ekstremitas</div>
        <div class="field-row">
            <div class="field-label">Ekstremitas Atas</div>
            <div class="field-sep">:</div>
            <div class="field-value">
                <div class="field-hint">Edema (ya/tidak), kesemutan/baal (ya/tidak), kekuatan otot</div>
                <?= p($fisik2['inspeksi_ekstremitas_atas']) ?>
            </div>
        </div>
        <div class="field-row">
            <div class="field-label">Ekstremitas Bawah</div>
            <div class="field-sep">:</div>
            <div class="field-value">
                <div class="field-hint">Edema (ya/tidak), varises (ya/tidak), tanda Homan (+/-), refleks patella (+/-), kekakuan sendi, kekuatan otot</div>
                <?= p($fisik2['inspeksi_ekstremitas_bawah']) ?>
            </div>
        </div>
        <div class="field-row">
            <div class="field-label">Masalah Khusus</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($fisik2['masalah_khusus_eksremitas']) ?></div>
        </div>

        <!-- Integumen -->
        <div class="subsection-title">Integumen</div>
        <div class="field-row">
            <div class="field-label">Inspeksi</div>
            <div class="field-sep">:</div>
            <div class="field-value">
                <div class="field-hint">Warna, turgor, elastisitas, ulkus</div>
                <?= p($fisik2['inspeksi_integumen']) ?>
            </div>
        </div>
        <div class="field-row">
            <div class="field-label">Palpasi</div>
            <div class="field-sep">:</div>
            <div class="field-value">
                <div class="field-hint">Akral, CRT, dan nyeri</div>
                <?= p($fisik2['palpasi_integumen']) ?>
            </div>
        </div>

        <!-- Eliminasi -->
        <div class="subsection-title">Eliminasi</div>
        <div class="field-row">
            <div class="field-label">Urin (BAK)</div>
            <div class="field-sep">:</div>
            <div class="field-value">
                <div class="field-hint">Nyeri (ya/tidak), frekuensi, jumlah</div>
                <?= p($fisik2['inspeksi_bak']) ?>
            </div>
        </div>
        <div class="field-row">
            <div class="field-label">BAB</div>
            <div class="field-sep">:</div>
            <div class="field-value">
                <div class="field-hint">Konstipasi (ya/tidak), frekuensi</div>
                <?= p($fisik2['inspeksi_bab']) ?>
            </div>
        </div>
        <div class="field-row">
            <div class="field-label">Masalah Khusus</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($fisik2['masalah_khusus_eliminasi']) ?></div>
        </div>

        <!-- Istirahat dan Kenyamanan -->
        <div class="subsection-title">Istirahat dan Kenyamanan</div>
        <div class="field-row">
            <div class="field-label">Pola Tidur</div>
            <div class="field-sep">:</div>
            <div class="field-value">
                <div class="field-hint">Kebiasaan tidur, lama dalam hitungan jam, frekuensi</div>
                <?= p($fisik3['inspeksi_istirahat']) ?>
            </div>
        </div>
        <div class="field-row">
            <div class="field-label">Kenyamanan</div>
            <div class="field-sep">:</div>
            <div class="field-value">
                <div class="field-hint">Keluhan ketidaknyamanan (ya/tidak), lokasi</div>
                <?= p($fisik3['inspeksi_kenyamanan']) ?>
            </div>
        </div>
        <div class="field-row">
            <div class="field-label">Masalah Khusus</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($fisik3['masalah_khusus_istirahat']) ?></div>
        </div>

        <!-- Mobilisasi -->
        <div class="subsection-title">Mobilisasi dan Latihan</div>
        <div class="field-row">
            <div class="field-label">Tingkat Mobilisasi</div>
            <div class="field-sep">:</div>
            <div class="field-value">
                <div class="field-hint">Apakah mandiri, parsial, total</div>
                <?= p($fisik3['inspeksi_mobilisasi']) ?>
            </div>
        </div>
        <div class="field-row">
            <div class="field-label">Masalah Khusus</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($fisik3['masalah_khusus_mobilisasi']) ?></div>
        </div>

        <!-- Nutrisi dan Cairan -->
        <div class="subsection-title">Pola Nutrisi dan Cairan</div>
        <div class="field-row">
            <div class="field-label">Jenis Makanan</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($fisik3['jenis_makanan']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Frekuensi Makan</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($fisik3['frekuensi']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Konsumsi Snack</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($fisik3['konsumsi_snack']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Nafsu Makan</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($fisik3['nafsu_makan']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Pola Minum</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($fisik3['polaminum']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Frekuensi Minum</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($fisik3['frekuensi2']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Pantangan Makanan</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($fisik3['pantangan_makanan']) ?></div>
        </div>

        <!-- Pengetahuan Menyusui -->
        <div class="subsection-title">Pengetahuan Menyusui</div>
        <div class="field-row">
            <div class="field-label">Kemampuan Menyusui</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($fisik3['kemampuan_menyusui']) ?> — <?= p($fisik3['kemampuan_menyusui1']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Posisi Menyusui</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($fisik3['posisi_menyusui']) ?> — <?= p($fisik3['posisi_menyusui1']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Penyimpanan ASI</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($fisik3['penyimpanan_asi']) ?> — <?= p($fisik3['penyimpanan_asi1']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Perawatan Payudara</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($fisik3['perawatan_payudara']) ?> — <?= p($fisik3['perawatan_payudara1']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Produksi ASI</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($fisik3['produksi_asi']) ?> — <?= p($fisik3['produksi_asi1']) ?></div>
        </div>

        <!-- Kontrasepsi (KB) -->
        <div class="subsection-title">Kontrasepsi (KB)</div>
        <div class="field-row">
            <div class="field-label">Jenis KB</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($fisik3['jenis_kb']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Pengetahuan KB</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($fisik3['pengetahuan_kb']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Rencana Penggunaan KB</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($fisik3['rencana_penggunaan_kb']) ?></div>
        </div>

        <div class="page-break"></div>

        <!-- ================================ -->
        <!-- SECTION 5: PROGRAM TERAPI & LAB -->
        <!-- ================================ -->
        <h3>Program Terapi</h3>

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
                    <tr><td colspan="4" style="text-align:center">-</td></tr>
                <?php endif; ?>
            </tbody>
        </table>

        <h3>Hasil Pemeriksaan Penunjang dan Laboratorium</h3>

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
                    <tr><td colspan="3" style="text-align:center">-</td></tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- ================================ -->
        <!-- SECTION 6: ANALISA DATA -->
        <!-- ================================ -->
        <h3>Klasifikasi Data</h3>

        <table class="data">
            <thead>
                <tr>
                    <th width="50%">Data Subjektif (DS)</th>
                    <th width="50%">Data Objektif (DO)</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($terapi['klasifikasi'])): ?>
                    <?php foreach ($terapi['klasifikasi'] as $klas): ?>
                        <tr>
                            <td><?= p($klas['data_subjektif']) ?></td>
                            <td><?= p($klas['data_objektif']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="2" style="text-align:center">-</td></tr>
                <?php endif; ?>
            </tbody>
        </table>

        <h3>Analisa Data</h3>

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
                    <?php foreach ($terapi['analisa'] as $ana): ?>
                        <tr>
                            <td><?= p($ana['ds_do']) ?></td>
                            <td><?= p($ana['etiologi']) ?></td>
                            <td><?= p($ana['masalah']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="3" style="text-align:center">-</td></tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="page-break"></div>

        <!-- ================================ -->
        <!-- SECTION 7: CATATAN KEPERAWATAN -->
        <!-- ================================ -->
        <h3>Diagnosa Keperawatan</h3>

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
                    <tr><td colspan="3" style="text-align:center">-</td></tr>
                <?php endif; ?>
            </tbody>
        </table>

        <h3>Intervensi Keperawatan</h3>

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
                    <tr><td colspan="3" style="text-align:center">-</td></tr>
                <?php endif; ?>
            </tbody>
        </table>

        <h3>Implementasi Keperawatan</h3>

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
                <?php if (!empty($lainnya['implementasi'])): ?>
                    <?php foreach ($lainnya['implementasi'] as $impl): ?>
                        <tr>
                            <td><?= p($impl['no_dx']) ?></td>
                            <td><?= p($impl['hari_tgl']) ?></td>
                            <td><?= p($impl['jam']) ?></td>
                            <td><?= p($impl['implementasi']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="4" style="text-align:center">-</td></tr>
                <?php endif; ?>
            </tbody>
        </table>

        <h3>Evaluasi Keperawatan</h3>

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
                <?php if (!empty($lainnya['evaluasi'])): ?>
                    <?php foreach ($lainnya['evaluasi'] as $eval): ?>
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
                    <tr><td colspan="7" style="text-align:center">-</td></tr>
                <?php endif; ?>
            </tbody>
        </table>

    </div>
</body>

</html>
