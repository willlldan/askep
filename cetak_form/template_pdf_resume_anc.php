<?php
// Shortcut per section
$identitas   = $sections['identitas'] ?? [];
$anamnesa    = $sections['anamnesa_antropometri'] ?? [];
$ttv         = $sections['ttv_pemeriksaan_umum'] ?? [];
$fisik       = $sections['pemeriksaan_fisik'] ?? [];
$terapi      = $sections['program_terapi_lab'] ?? [];
$lainnya     = $sections['lainnya'] ?? [];

include 'template_pdf.php';
?>

<body>
    <div>

        <!-- HEADER -->
        <h1>Format Resume Antenatal Care</h1>
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
                <td><strong>Ruangan</strong></td>
                <td>:</td>
                <td><?= p($submission['rs_ruangan']) ?></td>
            </tr>
        </table>

        <!-- ================================ -->
        <!-- SECTION 1: DATA DEMOGRAFI -->
        <!-- ================================ -->
        <h3>Data Demografi</h3>

        <table class="header-table">
            <tr>
                <td width="30%"><strong>Inisial Pasien</strong></td>
                <td width="2%">:</td>
                <td width="18%"><?= p($identitas['inisial_pasien']) ?></td>
            </tr>
            <tr>
                <td><strong>Usia</strong></td>
                <td>:</td>
                <td><?= p($identitas['usia_istri']) ?></td>
            </tr>
            <tr>
                <td><strong>Pekerjaan</strong></td>
                <td>:</td>
                <td><?= p($identitas['pekerjaan_istri']) ?></td>
            </tr>
            <tr>
                <td><strong>Pendidikan Terakhir</strong></td>
                <td>:</td>
                <td><?= p($identitas['pendidikan_terakhir_istri']) ?></td>
            </tr>
            <tr>
                <td><strong>Agama</strong></td>
                <td>:</td>
                <td><?= p($identitas['agama_istri']) ?></td>
            </tr>
            <tr>
                <td><strong>Suku Bangsa</strong></td>
                <td>:</td>
                <td><?= p($identitas['suku_bangsa']) ?></td>
            </tr>
            <tr>
                <td><strong>Alamat</strong></td>
                <td>:</td>
                <td colspan="4"><?= p($identitas['alamat']) ?></td>
            </tr>
            <tr>
                <td><strong>Status Pernikahan</strong></td>
                <td>:</td>
                <td colspan="4"><?= p($identitas['status_perkawinan']) ?></td>
            </tr>
        </table>

        <!-- ================================ -->
        <!-- SECTION 2: PENGKAJIAN / ANAMNESA -->
        <!-- ================================ -->
        <h3>Pengkajian</h3>
        <h4>Anamnesa</h4>

        <table class="header-table">
            <tr>
                <td width="30%"><strong>HPHT</strong></td>
                <td width="2%">:</td>
                <td width="18%"><?= p($anamnesa['hpht']) ?></td>
                <td width="20%"><strong>Tapsiran Partus</strong></td>
                <td width="2%">:</td>
                <td><?= p($anamnesa['tapsiran_partus']) ?></td>
            </tr>
            <tr>
                <td><strong>Status Gravida</strong></td>
                <td>:</td>
                <td colspan="4">
                    G<?= p($anamnesa['g']) ?>
                    P<?= p($anamnesa['p']) ?>
                    A<?= p($anamnesa['a']) ?>
                </td>
            </tr>
            <tr>
                <td><strong>Usia Kehamilan</strong></td>
                <td>:</td>
                <td colspan="4"><?= p($anamnesa['usia_kehamilan']) ?></td>
            </tr>
            <tr>
                <td><strong>Riwayat Imunisasi TT</strong></td>
                <td>:</td>
                <td colspan="4"><?= p($anamnesa['riwayati_munisasi']) ?></td>
            </tr>
            <tr>
                <td><strong>Riwayat Kehamilan Saat Ini</strong></td>
                <td>:</td>
                <td colspan="4"><?= p($anamnesa['riwayat_kehamilan']) ?></td>
            </tr>
            <tr>
                <td><strong>Riwayat Penyakit</strong></td>
                <td>:</td>
                <td colspan="4"><?= p($anamnesa['riwayat_penyakit']) ?></td>
            </tr>
        </table>

        <h4>Pemeriksaan Antropometri</h4>
        <table class="header-table">
            <tr>
                <td width="30%"><strong>TB</strong></td>
                <td width="2%">:</td>
                <td width="18%"><?= p($anamnesa['tb']) ?> cm</td>
                <td width="20%"><strong>BB</strong></td>
                <td width="2%">:</td>
                <td><?= p($anamnesa['bb']) ?> kg</td>
            </tr>
            <tr>
                <td><strong>LILA</strong></td>
                <td>:</td>
                <td colspan="4"><?= p($anamnesa['lila']) ?> cm</td>
            </tr>
        </table>

        <h4>Tanda-tanda Vital</h4>
        <table class="header-table">
            <tr>
                <td width="30%"><strong>Tekanan Darah</strong></td>
                <td width="2%">:</td>
                <td width="18%"><?= p($ttv['tekanan_darah']) ?></td>
                <td width="20%"><strong>Nadi</strong></td>
                <td width="2%">:</td>
                <td><?= p($ttv['nadi']) ?></td>
            </tr>
            <tr>
                <td><strong>Suhu</strong></td>
                <td>:</td>
                <td><?= p($ttv['suhu']) ?></td>
                <td><strong>Pernapasan</strong></td>
                <td>:</td>
                <td><?= p($ttv['pernapasan']) ?></td>
            </tr>
        </table>

        <!-- ================================ -->
        <!-- SECTION 3: PEMERIKSAAN UMUM -->
        <!-- ================================ -->
        <h3>Pemeriksaan Umum</h3>

        <div class="field-row">
            <div class="field-label">Keluhan Utama</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($ttv['keluhan_utama']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Riwayat Keluhan Utama</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($ttv['riwayat_keluhan_utama']) ?></div>
        </div>
      

        <!-- ================================ -->
        <!-- SECTION 4: PEMERIKSAAN FISIK -->
        <!-- ================================ -->
        <h3>Pemeriksaan Fisik</h3>

        <!-- Wajah -->
        <div class="subsection-title">Wajah</div>
        <div class="field-row">
            <div class="field-label">Inspeksi</div>
            <div class="field-sep">:</div>
            <div class="field-value">
                <div class="field-hint">Adakah pembengkakan, hiperpigmentasi/cloasma gravidarum, area jika ada cloasma</div>
                <?= p($fisik['inspeksi_wajah']) ?>
            </div>
        </div>

        <!-- Mata -->
        <div class="subsection-title">Mata</div>
        <div class="field-row">
            <div class="field-label">Konjungtiva</div>
            <div class="field-sep">:</div>
            <div class="field-value">
                <div class="field-hint">Anemis/an-anemis</div>
                <?= p($fisik['inspeksi_konjungtiva']) ?>
            </div>
        </div>
        <div class="field-row">
            <div class="field-label">Sklera</div>
            <div class="field-sep">:</div>
            <div class="field-value">
                <div class="field-hint">Ikterik/an-ikterik</div>
                <?= p($fisik['inspeksi_sklera']) ?>
            </div>
        </div>

        <!-- Mulut -->
        <div class="subsection-title">Mulut</div>
        <div class="field-row">
            <div class="field-label">Gigi</div>
            <div class="field-sep">:</div>
            <div class="field-value">
                <div class="field-hint">Amati jumlah, warna, kebersihan, karies</div>
                <?= p($fisik['inspeksi_gigi']) ?>
            </div>
        </div>
        <div class="field-row">
            <div class="field-label">Gusi</div>
            <div class="field-sep">:</div>
            <div class="field-value">
                <div class="field-hint">Adakah atau tidak lesi/pembengkakan</div>
                <?= p($fisik['inspeksi_gusi']) ?>
            </div>
        </div>
        <div class="field-row">
            <div class="field-label">Masalah Khusus</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($fisik['masalah_khusus_mulut']) ?></div>
        </div>

        <!-- Leher -->
        <div class="subsection-title">Leher</div>
        <div class="field-row">
            <div class="field-label">Inspeksi</div>
            <div class="field-sep">:</div>
            <div class="field-value">
                <div class="field-hint">Adakah distensi vena jugularis/tidak ada</div>
                <?= p($fisik['inspeksi_leher']) ?>
            </div>
        </div>

        <!-- Dada -->
        <div class="subsection-title">Dada; Sistem Pernapasan dan Kardiovaskuler</div>
        <div class="field-row">
            <div class="field-label">Auskultasi Bunyi Napas</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($fisik['bunyi_napas']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Auskultasi Suara Jantung</div>
            <div class="field-sep">:</div>
            <div class="field-value">
                <div class="field-hint">Apakah ada mur-mur dan gallop</div>
                <?= p($fisik['suara_jantung']) ?>
            </div>
        </div>
        <div class="field-row">
            <div class="field-label">Masalah Khusus</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($fisik['masalah_khusus_dada']) ?></div>
        </div>

        <!-- Payudara -->
        <div class="subsection-title">Payudara</div>
        <div class="field-row">
            <div class="field-label">Inspeksi</div>
            <div class="field-sep">:</div>
            <div class="field-value">
                <div class="field-hint">Hiperpigmentasi pada areola mammae, pengeluaran cairan</div>
                <?= p($fisik['payudara']) ?>
            </div>
        </div>
        <div class="field-row">
            <div class="field-label">Bentuk Puting</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($fisik['puting']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Masalah Khusus</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($fisik['masalahkhususpayudara']) ?></div>
        </div>

        <!-- Abdomen -->
        <div class="subsection-title">Abdomen</div>
        <div class="field-row">
            <div class="field-label">Linea Nigra / Alba</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($fisik['lineanigra']) ?> / <?= p($fisik['linea_nigra1']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Keadaan Dinding Perut</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($fisik['dindingperut']) ?></div>
        </div>

        <div class="subsection-title" style="font-size:9px; font-weight:bold; margin: 4px 0 2px 0;">Uterus</div>
        <table class="header-table" style="border:1px solid #000;">
            <tr>
                <td width="30%" style="border:1px solid #000;"><strong>TFU</strong></td>
                <td width="2%" style="border:1px solid #000;">:</td>
                <td width="18%" style="border:1px solid #000;"><?= p($fisik['inspeksitfu']) ?> cm</td>
                <td width="20%" style="border:1px solid #000;"><strong>Kontraksi</strong></td>
                <td width="2%" style="border:1px solid #000;">:</td>
                <td style="border:1px solid #000;"><?= p($fisik['inspeksikontraksi']) ?></td>
            </tr>
            <tr>
                <td style="border:1px solid #000;"><strong>Leopold I</strong></td>
                <td style="border:1px solid #000;">:</td>
                <td colspan="4" style="border:1px solid #000;"><?= p($fisik['leopoldi']) ?></td>
            </tr>
            <tr>
                <td style="border:1px solid #000;"><strong>Leopold II Kanan</strong></td>
                <td style="border:1px solid #000;">:</td>
                <td style="border:1px solid #000;"><?= p($fisik['kanan']) ?></td>
                <td style="border:1px solid #000;"><strong>Leopold II Kiri</strong></td>
                <td style="border:1px solid #000;">:</td>
                <td style="border:1px solid #000;"><?= p($fisik['kiri']) ?></td>
            </tr>
            <tr>
                <td style="border:1px solid #000;"><strong>Leopold III</strong></td>
                <td style="border:1px solid #000;">:</td>
                <td colspan="4" style="border:1px solid #000;"><?= p($fisik['leopoldiii']) ?></td>
            </tr>
            <tr>
                <td style="border:1px solid #000;"><strong>Leopold IV</strong></td>
                <td style="border:1px solid #000;">:</td>
                <td colspan="4" style="border:1px solid #000;"><?= p($fisik['leopoldiv']) ?></td>
            </tr>
            <tr>
                <td style="border:1px solid #000;"><strong>Pemeriksaan DJJ</strong></td>
                <td style="border:1px solid #000;">:</td>
                <td style="border:1px solid #000;"><?= p($fisik['pemeriksaandjj']) ?> Frek</td>
                <td style="border:1px solid #000;"><strong>Intensitas</strong></td>
                <td style="border:1px solid #000;">:</td>
                <td style="border:1px solid #000;"><?= p($fisik['intensitas']) ?></td>
            </tr>
            <tr>
                <td style="border:1px solid #000;"><strong>Keteraturan</strong></td>
                <td style="border:1px solid #000;">:</td>
                <td colspan="4" style="border:1px solid #000;"><?= p($fisik['keteraturan']) ?></td>
            </tr>
        </table>

        <!-- Vagina dan Perineum -->
        <div class="subsection-title">Vagina dan Perineum</div>
        <div class="field-row">
            <div class="field-label">Keputihan</div>
            <div class="field-sep">:</div>
            <div class="field-value">
                <div class="field-hint">Ya/Tidak: warna, konsistensi, bau, dan gatal</div>
                <?= p($fisik['inspeksi_keputihan']) ?>
            </div>
        </div>
        <div class="field-row">
            <div class="field-label">Hemoroid</div>
            <div class="field-sep">:</div>
            <div class="field-value">
                <div class="field-hint">Ya/Tidak. Jika ya: derajat, sudah berapa lama, nyeri</div>
                <?= p($fisik['inspeksi_hemoroid']) ?>
            </div>
        </div>
        <div class="field-row">
            <div class="field-label">Masalah Khusus</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($fisik['masalah_khusus_perineum']) ?></div>
        </div>

        <!-- Ekstremitas -->
        <div class="subsection-title">Ekstremitas Bawah</div>
        <div class="field-row">
            <div class="field-label">Inspeksi</div>
            <div class="field-sep">:</div>
            <div class="field-value">
                <div class="field-hint">Edema (ya/tidak), varises (ya/tidak)</div>
                <?= p($fisik['inspeksi_ekstremitas_bawah']) ?>
            </div>
        </div>

        <!-- Istirahat dan Kenyamanan -->
        <div class="subsection-title">Istirahat dan Kenyamanan</div>
        <div class="field-row">
            <div class="field-label">Pola Tidur</div>
            <div class="field-sep">:</div>
            <div class="field-value">
                <div class="field-hint">Kebiasaan tidur, lama dalam hitungan jam, frekuensi</div>
                <?= p($fisik['inspeksi_istirahat']) ?>
            </div>
        </div>
        <div class="field-row">
            <div class="field-label">Kenyamanan</div>
            <div class="field-sep">:</div>
            <div class="field-value">
                <div class="field-hint">Keluhan ketidaknyamanan (ya/tidak), lokasi</div>
                <?= p($fisik['inspeksi_kenyamanan']) ?>
            </div>
        </div>
        <div class="field-row">
            <div class="field-label">Masalah Khusus</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($fisik['masalah_khusus_istirahat']) ?></div>
        </div>

        <!-- Mobilisasi -->
        <div class="subsection-title">Mobilisasi dan Latihan</div>
        <div class="field-row">
            <div class="field-label">Tingkat Mobilisasi</div>
            <div class="field-sep">:</div>
            <div class="field-value">
                <div class="field-hint">Apakah mandiri, parsial, total</div>
                <?= p($fisik['inspeksi_mobilisasi']) ?>
            </div>
        </div>
        <div class="field-row">
            <div class="field-label">Masalah Khusus</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($fisik['masalah_khusus_mobilisasi']) ?></div>
        </div>

        <!-- Nutrisi -->
        <div class="subsection-title">Pola Nutrisi dan Cairan</div>
        <div class="field-row">
            <div class="field-label">Asupan Nutrisi</div>
            <div class="field-sep">:</div>
            <div class="field-value">
                <div class="field-hint">Nafsu makan: baik, kurang atau tidak nafsu makan</div>
                <?= p($fisik['inspeksi_nutrisi']) ?>
            </div>
        </div>
        <div class="field-row">
            <div class="field-label">Asupan Cairan</div>
            <div class="field-sep">:</div>
            <div class="field-value">
                <div class="field-hint">Cukup/kurang</div>
                <?= p($fisik['inspeksi_cairan']) ?>
            </div>
        </div>
        <div class="field-row">
            <div class="field-label">Pantangan Makanan</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($fisik['inspeksi_pantangan']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Masalah Khusus</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($fisik['masalah_khusus_polanutrisi']) ?></div>
        </div>

        <!-- Pengetahuan -->
        <div class="subsection-title">Pengetahuan</div>
        <div class="field-row">
            <div class="field-label">Tanda-tanda Melahirkan</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($fisik['tanda_melahirkan']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Cara Menangani Nyeri</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($fisik['nyeri_melahirkan']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Cara Mengejan</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($fisik['persalinan']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Manfaat ASI &amp; Perawatan Payudara</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($fisik['asi_dan_payudara']) ?></div>
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