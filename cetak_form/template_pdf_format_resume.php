<?php
// Shortcut per section
$resume_keperawatan    = $sections['resume_keperawatan'] ?? [];
$analisa_resume        = $sections['analisa_resume'] ?? [];
$lainnya_poli          = $sections['lainnya_poli'] ?? [];
$lp_imunisasi          = $sections['lp_imunisasi'] ?? [];
$poli_imunisasi        = $sections['lainnya'] ?? [];
$analisa_poli          = $sections['analisa_poli'] ?? [];
$lainnya_poli          = $sections['lainnya_poli'] ?? [];

include 'template_pdf.php';
?>

<body>
    <div >

        <!-- HEADER -->
        <h1>FORMAT RESUME KEPERAWATAN POLI ANAK</h1>
        <br>
        
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
        
        <br>

        <!-- ================================ -->
        <!-- SECTION 1: Format Laporan Pendahuluan Ruang OK -->
        <!-- ================================ -->
        <h3 class="mt-5">Format Laporan Pendahuluan Ruang OK</h3>

        <h4>A. KONSEP DASAR KAMAR BEDAH</h4>
        <div class="field-row">
            <div class="field-label">1. Pengertian Kamar Operasi</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($lp_ruangok['pengertian_kamar_operasi']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">2. Pembagian Ruangan Kamar Operasi</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($lp_ruangok['ruang_kamar_operasi']) ?></div>
        </div>
         <div class="field-row">
            <div class="field-label">3. Bagian-Bagian Kamar Operasi</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($lp_ruangok['kamar_operasi']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">4. Persyaratan Kamar Operasi</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($lp_ruangok['persyaratan']) ?></div>
        </div>

        <h4>B. TATA CARA KERJA DAN PENGELOLAAN KAMAR OPERASI</h4>
        <div class="field-row">
            <div class="field-label">Tata Cara Kerja dan Pengelolaan Kamar Operasi</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($lp_ruangok['tata_cara']) ?></div>
        </div>

        <h4>C. DENAH RUANGAN KAMAR OPERASI</h4>
        <div class="field-row">
            <div class="field-label">Denah Ruangan Kamar Operasi</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($lp_ruangok['denah']) ?></div>
        </div>

        <h4>D. DAFTAR PUSTAKA</h4>
        <div class="field-row">
            <div class="field-label">Daftar Pustaka</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($lp_ruangok['daftar_pustaka']) ?></div>
        </div>

        <br>

        <!-- ================================ -->
        <!-- SECTION 1: Laporan Ruang Operasi -->
        <!-- ================================ -->
        <h3 class="mt-5">Laporan Ruang Operasi</h3>
        
        <div class="field-row">
            <div class="field-label">Nama Mahasiswa</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($ruang_operasi['nama_mahasiswa']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">NIM</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($ruang_operasi['nim']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Kelompok</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($ruang_operasi['kelompok']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Tempat Dinas</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($ruang_operasi['tempat_dinas']) ?></div>
        </div>

        <h4>A. IDENTITAS KLIEN</h4>
        <div class="field-row">
            <div class="field-label">Nama Klien</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($ruang_operasi['nama_klien']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Umur</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($ruang_operasi['umur']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Pekerjaan</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($ruang_operasi['pekerjaan']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Tgl Masuk RS</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($ruang_operasi['tgl_masuk_rs']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Diagnosa Medis</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($ruang_operasi['diagnosa_medis']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Jenis Operasi</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($ruang_operasi['jenis_operasi']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Waktu Operasi</div>
            <div class="field-sep">:</div>
            <div class="field-value">
                Tgl <?= p($ruang_operasi['tgl_operasi']) ?> 
                Pukul <?= p($ruang_operasi['pukul_mulai']) ?> 
                s/d Pukul <?= p($ruang_operasi['pukul_selesai']) ?>
            </div>
        </div>

        <h4>B. PERSIAPAN</h4>
        <div class="field-row">
            <div class="field-label">1. Alat</div>
            <div class="field-sep"></div>
            <div class="field-value"></div>
        </div>

        <div class="field-row">
            <div class="field-label" style="padding-left:20px;">a. Steril</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($ruang_operasi['steril']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label" style="padding-left:20px;">b. Non Steril</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($ruang_operasi['non_steril']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label" style="padding-left:20px;">c. Anestesi</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($ruang_operasi['anestesi']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">2. Jenis Anestesi</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($ruang_operasi['jenis_anestesi']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">3. Lingkungan</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($ruang_operasi['lingkungan']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">4. Hasil Pemeriksaan Laboratorium</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($ruang_operasi['pemeriksaan_lab']) ?></div>
        </div>

        <h4>C. TINDAKAN</h4>
        <div class="field-row">
            <div class="field-label">Tindakan</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($ruang_operasi['tindakan']) ?></div>
        </div>

        <h4>D. KESIMPULAN</h4>
        <div class="field-row">
            <div class="field-label">Kesimpulan</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($ruang_operasi['kesimpulan']) ?></div>
        </div>

        <div class="page-break"></div>

        <!-- ================================ -->
        <!-- SECTION 2: Format Resume Ruang OK -->
        <!-- ================================ -->
        <h1>FORMAT RESUME KEPERAWATAN</h1>
        <h2>PRAKTIK KLINIK KEPERAWATAN MEDIKAL BEDAH II DI RUANG OK</h2>
        <br>
        
        <h3 class="mt-5">Format Resume Ruang OK</h3>

        <h4>1. Biodata Klien</h4>
        <div class="field-row">
            <div class="field-label">Nama Klien</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($resume['nama_klien']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Jenis Kelamin</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($resume['jenis_kelamin']) ?></div>
        </div>
         <div class="field-row">
            <div class="field-label">Umur</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($resume['umur']) ?></div>
        </div>
         <div class="field-row">
            <div class="field-label">Agama</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($resume['agama']) ?></div>
        </div>
         <div class="field-row">
            <div class="field-label">Status Perkawinan</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($resume['status_perkawinan']) ?></div>
        </div>
         <div class="field-row">
            <div class="field-label">Pendidikan</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($resume['pendidikan']) ?></div>
        </div>
         <div class="field-row">
            <div class="field-label">Pekerjaan</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($resume['pekerjaan']) ?></div>
        </div>
         <div class="field-row">
            <div class="field-label">Alamat</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($resume['alamat']) ?></div>
        </div>
         <div class="field-row">
            <div class="field-label">Diagnosa Medis</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($resume['diagnosa_medis']) ?></div>
        </div>

        <h4>2. Keluhan Utama</h4>
         <div class="field-row">
            <div class="field-label">Keluhan Utama</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($resume['keluhan_utama']) ?></div>
        </div>

        <h4>3. Tanda-tanda Vital</h4>
         <div class="field-row">
            <div class="field-label">Tanda-tanda Vital</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($resume['tanda_vital']) ?></div>
        </div>

        <h4>4. Pengkajian Data Fokus (Data yang Bermasalah)</h4>
         <div class="field-row">
            <div class="field-label">Pre Operasi</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($resume['pre_operasi']) ?></div>
        </div>
         <div class="field-row">
            <div class="field-label">Pos Operasi</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($resume['pos_operasi']) ?></div>
        </div>

        <h4>5. Pemeriksaan Penunjang</h4>
         <div class="field-row">
            <div class="field-label">Pemeriksaan Penunjang</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($resume['pemeriksaan_penunjang']) ?></div>
        </div>

        <h4>6. Terapi Saat Ini</h4>
         <div class="field-row">
            <div class="field-label">Terapi Saat Ini</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($resume['terapi_saat_ini']) ?></div>
        </div>

        <br>

        <!-- ================================ -->
        <!-- SECTION 3: Analisa Data -->
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
        <!-- SECTION 4: CATATAN KEPERAWATAN -->
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

        <div class="page-break"></div>

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

        <div class="page-break"></div>

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
                    <tr>
                        <td colspan="4" style="text-align:center">-</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="page-break"></div>

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
                    <tr>
                        <td colspan="7" style="text-align:center">-</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

    </div>
</body>

</html>