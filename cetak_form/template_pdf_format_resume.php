<?php
// Shortcut per section
$resume_keperawatan    = $sections['resume_keperawatan'] ?? [];
$analisa_resume        = $sections['analisa_resume'] ?? [];
$lainnya_resume          = $sections['lainnya_resume'] ?? [];
$lp_imunisasi          = $sections['lp_imunisasi'] ?? [];
$poli_imunisasi        = $sections['poli_imunisasi'] ?? [];
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
        <!-- SECTION 1: Format Resume Keperawatan Poli Anak -->
        <!-- ================================ -->
        <h3 class="mt-5">Format Resume Keperawatan Poli Anak</h3> 

        <h4>1. Biodata Klien</h4>
        <div class="field-row">
            <div class="field-label">Nama Anak</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($resume_keperawatan['nama_anak']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Jenis Kelamin</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($resume_keperawatan['jenis_kelamin']) ?></div>
        </div>
         <div class="field-row">
            <div class="field-label">Umur</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($resume_keperawatan['umur']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Agama</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($resume_keperawatan['agama']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Alamat</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($resume_keperawatan['alamat']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Diagnosa Medis</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($resume_keperawatan['diagnosa_medis']) ?></div>
        </div>

        <h4>2. Biodata Orangtua</h4>
         <div class="field-row">
            <div class="field-label">Nama Ayah</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($resume_keperawatan['nama_ayah']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Umur Ayah</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($resume_keperawatan['umur_ayah']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Pendidikan Ayah</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($resume_keperawatan['pendidikan_ayah']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Pekerjaan Ayah</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($resume_keperawatan['pekerjaan_ayah']) ?></div>
        </div>
         <div class="field-row">
            <div class="field-label">Nama Ibu</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($resume_keperawatan['nama_ibu']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Umur Ibu</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($resume_keperawatan['umur_ibu']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Pendidikan Ibu</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($resume_keperawatan['pendidikan_ibu']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Pekerjaan Ibu</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($resume_keperawatan['pekerjaan_ibu']) ?></div>
        </div>

        <h4>3. Keluhan Utama</h4>
         <div class="field-row">
            <div class="field-label">Keluhan Utama</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($resume_keperawatan['keluhan_utama']) ?></div>
        </div>

        <h4>4. Riwayat Keluhan Utama</h4>
        <div class="field-row">
            <div class="field-label">Riwayat Keluhan Utama</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($resume_keperawatan['riwayat_keluhan_utama']) ?></div>
        </div>

        <h4>5. Keluhan yang Menyertai</h4>
         <div class="field-row">
            <div class="field-label">Keluhan yang Menyertai</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($resume_keperawatan['keluhan']) ?></div>
        </div>

        <h4>6. Riwayat Kesehatan yang Lalu</h4>
        <div class="field-row">
            <div class="field-label">Riwayat Kesehatan yang Lalu</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($resume_keperawatan['riwayat_kesehatan_yang_lalu']) ?></div>
        </div>

        <h4>7. Pemeriksaan Fisik</h4>
        <div class="field-row">
        <div class="field-label">Pemeriksaan Fisik</div>
        <div class="field-sep">:</div>
        <div class="field-value">
            <div class="field-hint">
                (secara umum dan singkat, berat badan, tinggi badan, status gizi anak)
            </div>
            <?= p($resume_keperawatan['pemeriksaan_fisik']) ?>
        </div>
    </div>

        <!-- ================================ -->
        <!-- SECTION 2: Analisa Data -->
        <!-- ================================ -->

        <h3 class="mt-5">8. Klasifikasi Data</h3>

        <table class="data">
            <thead>
                <tr>
                    <th width="50%">Data Subjektif (DS)</th>
                    <th width="50%">Data Objektif (DO)</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($analisa_resume['klasifikasi'])): ?>
                    <?php foreach ($analisa_resume['klasifikasi'] as $klas): ?>
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

        <h3 class="mt-5">9. Analisa Data</h3>

        <table class="data">
            <thead>
                <tr>
                    <th width="40%">DS/DO</th>
                    <th width="30%">Etiologi</th>
                    <th width="30%">Masalah</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($analisa_resume['analisa'])): ?>
                    <?php foreach ($analisa_resume['analisa'] as $ana): ?>
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

        <!-- ================================ -->
        <!-- SECTION 3: CATATAN KEPERAWATAN -->
        <!-- ================================ -->
        <h3 class="mt-5">10. Diagnosa Keperawatan</h3>

        <table class="data">
            <thead>
                <tr>
                    <th>Diagnosa</th>
                    <th>Tanggal Ditemukan</th>
                    <th>Tanggal Teratasi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($lainnya_resume['diagnosa'])): ?>
                    <?php foreach ($lainnya_resume['diagnosa'] as $dx): ?>
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

        <h3 class="mt-5">11. Perencanaan Keperawatan</h3>

        <table class="data">
            <thead>
                <tr>
                    <th width="40%">Diagnosa</th>
                    <th width="30%">Tujuan dan Kriteria Hasil</th>
                    <th width="30%">Intervensi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($lainnya_resume['intervensi'])): ?>
                    <?php foreach ($lainnya_resume['intervensi'] as $inv): ?>
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

        <h3 class="mt-5">12. Implementasi Keperawatan</h3>

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
                <?php if (!empty($lainnya_resume['implementasi'])): ?>
                    <?php foreach ($lainnya_resume['implementasi'] as $impl): ?>
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

        <h3 class="mt-5">13. Evaluasi Keperawatan</h3>

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
                <?php if (!empty($lainnya_resume['evaluasi'])): ?>
                    <?php foreach ($lainnya_resume['evaluasi'] as $eval): ?>
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

        <div class="page-break"></div>

        <!-- ================================ -->
        <!-- SECTION 4: Format Laporan Pendahuluan Imunisasi -->
        <!-- ================================ -->
        <h1>FORMAT LAPORAN PENDAHULUAN IMUNISASI</h1>            

        <h3 class="mt-5">Format Laporan Pendahuluan Imunisasi</h3>

        <h4>Konsep Dasar Imunisasi (secara keseluruhan)</h4>
 
        <div class="field-row">
            <div class="field-label">1. Pengertian Imunisasi</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($lp_imunisasi['pengertian_imunisasi']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">2. Manfaat / Tujuan Imunisasi</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($lp_imunisasi['manfaat_imunisasi']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">3. Jenis-jenis Kekebalan Tubuh</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($lp_imunisasi['jenis_kekebalan_tubuh']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">4. Jenis-jenis Imunisasi Dasar</div>
            <div class="field-sep">:</div>
            <div class="field-value">
                <div class="field-hint">
                   Penjelasan meliputi nama imunisasi, sasaran umur, dosis, cara pemberian, frekuensi pemberian, ,efek samping, penyakit yang dicegah dengan pemberian imunisasi (definisi, penyebab, tanda dan gejala)
                </div>
                <?= p($lp_imunisasi['jenis_imunisasi_dasar']) ?>
            </div>
        </div>
        <div class="field-row">
            <div class="field-label">5. Jenis-jenis Imunisasi Lanjutan</div>
            <div class="field-sep">:</div>
            <div class="field-value">
                <div class="field-hint">
                   Penjelasan meliputi nama imunisasi, sasaran umur, dosis, cara pemberian, frekuensi pemberian, efek samping
                </div>
                <?= p($lp_imunisasi['imunisasi_lanjutan']) ?>
            </div>
        </div>

        <div class="page-break"></div>

        <!-- ================================ -->
        <!-- SECTION 5: Format Laporan Poli Imunisasi -->
        <!-- ================================ -->
        <h1>FORMAT LAPORAN POLI IMUNISASI</h1>
        <br>

        <h3 class="mt-5">Format Laporan Poli Imunisasi</h3>

        <h4>1. Biodata Klien</h4>
        <div class="field-row">
            <div class="field-label">Nama Anak</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($poli_imunisasi['nama_anak']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Jenis Kelamin</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($poli_imunisasi['jenis_kelamin']) ?></div>
        </div>
         <div class="field-row">
            <div class="field-label">Umur</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($poli_imunisasi['umur']) ?></div>
        </div>
         <div class="field-row">
            <div class="field-label">Agama</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($poli_imunisasi['agama']) ?></div>
        </div>
         <div class="field-row">
            <div class="field-label">Alamat</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($poli_imunisasi['alamat']) ?></div>
        </div>

        <h4>1. Biodata Orangtua</h4>
         <div class="field-row">
            <div class="field-label">Nama Ayah</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($poli_imunisasi['nama_ayah']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Umur Ayah</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($poli_imunisasi['umur_ayah']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Pendidikan Ayah</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($poli_imunisasi['pendidikan_ayah']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Pekerjaan Ayah</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($poli_imunisasi['pekerjaan_ayah']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Nama Ibu</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($poli_imunisasi['nama_ibu']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Umur Ibu</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($poli_imunisasi['umur_ibu']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Pendidikan Ibu</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($poli_imunisasi['pendidikan_ibu']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Pekerjaan Ibu</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($poli_imunisasi['pekerjaan_ibu']) ?></div>
        </div>

        <h4>B. Pemberian Imunisasi</h4>
         <div class="field-row">
            <div class="field-label">Imunisasi saat ini</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($poli_imunisasi['imunisasi_saat_ini']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Dosis pemberian</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($poli_imunisasi['dosis_pemberian']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Cara pemberian</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($poli_imunisasi['cara_pemberian']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Reaksi anak</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($poli_imunisasi['reaksi_anak']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Rencana imunisasi pada kunjungan berikutnya</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($poli_imunisasi['rencana_imunisasi']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Imunisasi yang sudah didapatkan</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($poli_imunisasi['imunisasi_didapatkan']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Efek yang dirasakan anak di rumah setelah pemberian imunisasi</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($poli_imunisasi['efek_dirumah']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Hal yang dikeluhkan orang tua setelah pemberian imunisasi</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($poli_imunisasi['pemberian_imunisasi']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Riwayat penyakit / pengobatan yang pernah didapatkan</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($poli_imunisasi['riwayat_penyakit']) ?></div>
        </div>

        <br>

        <!-- ================================ -->
        <!-- SECTION 3: Analisa Data -->
        <!-- ================================ -->
        <h3 class="mt-5">C. Klasifikasi Data</h3>

        <table class="data">
            <thead>
                <tr>
                    <th width="50%">Data Subjektif (DS)</th>
                    <th width="50%">Data Objektif (DO)</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($analisa_poli['klasifikasi'])): ?>
                    <?php foreach ($analisa_poli['klasifikasi'] as $klas): ?>
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

        <h3 class="mt-5">D. Analisa Data</h3>

        <table class="data">
            <thead>
                <tr>
                    <th width="40%">Data</th>
                    <th width="30%">Etiologi</th>
                    <th width="30%">Masalah</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($analisa_poli['analisa'])): ?>
                    <?php foreach ($analisa_poli['analisa'] as $ana): ?>
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
        <h3 class="mt-5">E. Diagnosa Keperawatan</h3>

        <table class="data">
            <thead>
                <tr>
                    <th>Diagnosa</th>
                    <th>Tanggal Ditemukan</th>
                    <th>Tanggal Teratasi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($lainnya_poli['diagnosa'])): ?>
                    <?php foreach ($lainnya_poli['diagnosa'] as $dx): ?>
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

        <h3 class="mt-5">F. Perencanaan Keperawatan</h3>

        <div class="field-row">
            <div class="field-label">Nama Klien</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($lainnya_poli['nama_klien']) ?></div>
        </div>

        <div class="field-row">
            <div class="field-label">No Registrasi</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($lainnya_poli['no_registrasi']) ?></div>
        </div>

        <div class="field-row">
            <div class="field-label">Ruangan</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($lainnya_poli['ruangan']) ?></div>
        </div>

        <table class="data">
            <thead>
                <tr>
                    <th width="40%">Diagnosa</th>
                    <th width="30%">Tujuan dan Kriteria Hasil</th>
                    <th width="30%">Intervensi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($lainnya_poli['intervensi'])): ?>
                    <?php foreach ($lainnya_poli['intervensi'] as $inv): ?>
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

        <h3 class="mt-5">G. Implementasi Keperawatan</h3>

         <div class="field-row">
            <div class="field-label">Nama Klien</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($lainnya_poli['nama_klien']) ?></div>
        </div>

        <div class="field-row">
            <div class="field-label">No Registrasi</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($lainnya_poli['no_registrasi']) ?></div>
        </div>

        <div class="field-row">
            <div class="field-label">Ruangan</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($lainnya_poli['ruangan']) ?></div>
        </div>

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
                <?php if (!empty($lainnya_poli['implementasi'])): ?>
                    <?php foreach ($lainnya_poli['implementasi'] as $impl): ?>
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

        <h3 class="mt-5">H. Evaluasi Keperawatan</h3>

         <div class="field-row">
            <div class="field-label">Nama Klien</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($lainnya_poli['nama_klien']) ?></div>
        </div>

        <div class="field-row">
            <div class="field-label">No Registrasi</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($lainnya_poli['no_registrasi']) ?></div>
        </div>

        <div class="field-row">
            <div class="field-label">Ruangan</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($lainnya_poli['ruangan']) ?></div>
        </div>

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
                <?php if (!empty($lainnya_poli['evaluasi'])): ?>
                    <?php foreach ($lainnya_poli['evaluasi'] as $eval): ?>
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