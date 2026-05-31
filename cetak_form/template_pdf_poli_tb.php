<?php
// Shortcut per section
$konsep       = $sections['laporan_pedahuluan'] ?? [];
$resume        = $sections['resume'] ?? [];
$analisa       = $sections['analisa'] ?? [];
$lainnya       = $sections['lainnya'] ?? [];

include 'template_pdf.php';
?>

<body>
    <div >

        <!-- HEADER -->
        <h1>FORMAT POLI TB/UMUM</h1>
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
        <!-- SECTION 2: Format Resume Ruang OK -->
        <!-- ================================ -->
        <h1>FORMAT RESUME KEPERAWATAN <br> PRAKTIK KLINIK KEPERAWATAN POLI TB/UMUM </h1>
        <br>
         <h3>A. Konsep Dasar Medis</h3>

        <div class="field-row">
            <div class="field-label">Pengertian</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($konsep['pengertian']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Etiologi</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($konsep['etiologi']) ?></div>
        </div>

         <div class="field-row">
            <div class="field-label">Patofisiologi</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($konsep['patofisiologi']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Pathway</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($konsep['pathway']) ?></div>
        </div>
       
        <div class="field-row">
            <div class="field-label">Manifestasi Klinik</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($konsep['manifestasi_klinik']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Penatalaksanaan</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($konsep['penatalaksanaan']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Pemeriksaan Penunjang</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($konsep['pemeriksaan_penunjang']) ?></div>
        </div>
        

        <h3>B. Konsep Dasar Keperawatan</h3>
            <div class="subsection-title">1. Pengkajian</div>

        <div class="field-row">
            <div class="field-label">a. Keluhan Utama</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($konsep['keluhan']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">b. Riwayat kesehatan sekarang dan dahulu</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($konsep['kesehatan']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">c. Pemeriksaan Fisik</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($konsep['pemeriksaan']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">2. Diagnosa Keperawatan</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($konsep['diagnosa_keperawatan']) ?></div>
        </div>
       

        <div class="subsection-title">3. Intervensi</div>
        <table class="data">
            <thead>
                <tr>
                    <th width="5%">No.</th>
                    <th width="35%">Diagnosa Keperawatan</th>
                    <th width="30%">Tujuan &amp; Kriteria Hasil</th>
                    <th width="30%">Intervensi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($konsep['perencanaan'])): ?>
                    <?php foreach ($konsep['perencanaan'] as $i => $row): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td><?= p($row['diagnosa']) ?></td>
                            <td><?= p($row['tujuan_kriteria']) ?></td>
                            <td><?= p($row['intervensi']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" style="text-align:center">-</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <h3>C. Daftar Pustaka</h3>
       <div class="field-row">
            <div class="field-label">Daftar Pustaka</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($konsep['daftar']) ?></div>
        </div>

        <div class="page-break"></div>

        <h3 class="mt-5">Format Resume </h3>

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
            <div class="field-label">Kunjungan Ke</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($resume['kunjungan']) ?></div>
        </div>
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
        <h4>3.	Riwayat Kesehatan Saat ini</h4>
         <div class="field-row">
            <div class="field-label">Riwayat Kesehatan Saat in</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($resume['riwayatsaatini']) ?></div>
        </div>

        <h4>4. Tanda-tanda Vital</h4>
         <div class="field-row">
            <div class="field-label">Tanda-tanda Vital</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($resume['tanda_vital']) ?></div>
        </div>

        <h4>5.	Pemeriksaan Fisik (secara umum dan singkat yang bermasalah</h4>
        <div class="field-row">
            <div class="field-label">Pemeriksaan Fisik</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($resume['pemeriksaan']) ?></div>
        </div>
        
        <h4>6.	Riwayat Kesehatan yang Lalu</h4>
         <div class="field-row">
            <div class="field-label">Riwayat Kesehatan yang Lalu</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($resume['kesehatanlalu']) ?></div>
        </div>

        <h4>7. Pemeriksaan Penunjang</h4>
            <h4 class="mt-5">Hasil Pemeriksaan Penunjang dan Laboratorium</h4>

        <table class="data">
            <thead>
                <tr>
                    <th>Pemeriksaan</th>
                    <th>Hasil</th>
                    <th>Nilai Normal</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($resume['lab'])): ?>
                    <?php foreach ($resume['lab'] as $lab): ?>
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
        <div class="field-row">
            <div class="field-label">b.	Radiologi</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($resume['radiologi']) ?></div>
        </div>
        <div class="field-row">
    <div class="subsection-title">EKG</div>
        <?php if (!empty($resume['ekg'])): ?>
            <div style="margin: 6px 0; text-align:center;">
                <img src="<?= cetakGambar($resume['ekg']) ?>" style="max-height:250px; width:auto;" />
            </div>
        <?php else: ?>
            <div style="border:1px solid #ccc; min-height:60px; padding:4px;">-</div>
        <?php endif; ?>

        <div class="field-row">
            <div class="field-label">d.	USG</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($resume['usg']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">e.	CT Scan</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($resume['ct']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">f.	Pemeriksaan Lain (Sebutkan)</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($resume['pemeriksaan']) ?></div>
        </div>
        <h3 class="mt-5">8. Obat-obatan yang Dikonsumsi Saat Ini</h3>

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
                <?php if (!empty($resume['obat'])): ?>
                    <?php foreach ($resume['obat'] as $obat): ?>
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

        <br>

        <div class="page-break"></div>

        <!-- ================================ -->
        <!-- SECTION 3: Analisa Data -->
        <!-- ================================ -->
        <h3 class="mt-5">9. Klasifikasi Data</h3>

        <table class="data">
            <thead>
                <tr>
                    <th width="50%">Data Subjektif</th>
                    <th width="50%">Data Objektif</th>
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

        <h3 class="mt-5">10. Analisa Data</h3>

        <table class="data">
            <thead>
                <tr>
                    <th width="40%">Data</th>
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
        <h3 class="mt-5">11. Diagnosa Keperawatan</h3>

        <table class="data">
            <thead>
                <tr>
                    <th>Diagnosa Keperawatan</th>
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

        <h3 class="mt-5">12. Intervensi Keperawatan</h3>

        <table class="data">
            <thead>
                <tr>
                    <th width="40%">Diagnosa Keperawatan</th>
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

        <h3 class="mt-5">13. Implementasi Keperawatan</h3>

        <table class="data">
            <thead>
                <tr>
                    <th>No. Dx</th>
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

        <h3 class="mt-5">14. Evaluasi Keperawatan</h3>

        <table class="data">
            <thead>
                <tr>
                    <th>No. Dx</th>
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