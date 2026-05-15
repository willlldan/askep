<?php
// Shortcut per section
$lp   = $sections['format_lp'] ?? [];
$anak     = $sections['pengkajian_anak'] ?? [];
$riwayat       = $sections['pengkajian_riwayat'] ?? [];
$fisik      = $sections['pemeriksaan_fisik'] ?? [];
$analisa     = $sections['analisa_data'] ?? [];
$catatan     = $sections['catatan_keperawatan'] ?? [];



include 'template_pdf.php';
?>
<?php
function safe_p($var) {
    if (is_array($var)) {
        $var = implode(', ', $var); // gabungkan array jadi string
    }
    return htmlspecialchars($var, ENT_QUOTES, 'UTF-8');
}
?>

<body>
    

        <!-- HEADER -->
        <h1>Format Pengkajian Asuhan Keperawatan</h1>
        <h2>Anak Anggrek B</h2>

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

        <h1>Format Pengkajian Pendahuluan</h1>
    

<h4>A.	Konsep Dasar Medis</h4>

<div class="field-row">
    <div class="field-label">1. Pengertian</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($lp['pengertian']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">2. Etiologi</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($lp['etiologi']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">3.	Patofisiologi</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($lp['patofisiologi']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">4.	Manifestasi Klinik</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($lp['manifestasi_klinik']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">6.	Penatalaksanaan</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($lp['penatalaksanaan']) ?></div>
</div>


<h4>B.	Konsep Dasar Keperawatan</h4>
<h4>1.	Pengkajian Keperawatan </h4>
<div class="field-row">
    <div class="field-value"><?= p($lp['pengkajian_keperawatan']) ?></div>
</div>
<h4>2.	Penyimpangan KDM</h4>
<div class="field-row">
    <div class="field-value"><?= p($lp['penyimpangan_kdm']) ?></div>
</div>
<h4>3.	Diagnosa Keperawatan </h4>
<div class="field-row">
    <div class="field-value"><?= p($lp['diagnosa_keperawatan']) ?></div>
</div>

<h4>4.	Perencanaan</h4>
<table class="data">
    <tr>
        <th>No</th>
        <th>iagnosa Keperawatan</th>
        <th>Tujuan & Kriteria Hasil</th>
        <th>Intervensi</th>
    </tr>
    <?php foreach($lp['obat'] as $i => $row): ?>
    <tr>
        <td><?= $i+1 ?></td>
        <td><?= p($row['diagnosa']) ?></td>
        <td><?= p($row['tujuan']) ?></td>
        <td><?= p($row['intervensi']) ?></td>
    </tr>
    <?php endforeach; ?>
</table>



<h4>C. Daftar Pustaka</h4>
<div class="field-row">
    <div class="field-value"><?= p($lp['daftar_pustaka']) ?></div>
</div>
<div class="page-break"></div>


        <!-- ================================ -->
        <!-- SECTION 2: RIWAYAT KEHAMILAN -->
        <!-- ================================ -->
          <h1>FORMAT PENGKAJIAN ANAK</h1>
        <h4>A.	Identitas</h4> 
         

        <h3 class="mt-5">1.	Identitas klien </h3>
<div class="field-row">
    <div class="field-label">A. Nama Anak</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($anak['nama_anak']) ?></div>
</div>

<div class="field-row">
    <div class="field-label">B. Tempat/Tgl Lahir & Usia</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($anak['ttl_umur']) ?></div>
</div>

<div class="field-row">
    <div class="field-label">C. Jenis Kelamin</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($anak['jenis_kelamin']) ?></div>
</div>

<div class="field-row">
    <div class="field-label">D. Agama</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($anak['agama']) ?></div>
</div>

<div class="field-row">
    <div class="field-label">E. Alamat</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($anak['alamat']) ?></div>
</div>

<div class="field-row">
    <div class="field-label">F. Tgl Masuk RS</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($anak['tgl_masuk']) ?>, <strong> Jam </strong>  <?= p($anak['jam_masuk']) ?></div>
</div>


<div class="field-row">
    <div class="field-label">G. Tgl Pengkajian</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($anak['tgl_pengkajian']) ?></div>
</div>

<div class="field-row">
    <div class="field-label">H. Diagnosa Medik</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($anak['diagnosa_medik']) ?></div>
</div>

    <h3>2. Identitas Orang Tua</h3>

<h4>Ayah</h4>
<div class="field-row">
    <div class="field-label">A. Nama</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($anak['nama_ayah']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">B. Usia</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($anak['usia_ayah']) ?></div>
    
</div>
<div class="field-row">
    <div class="field-label">C. Pendidikan</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($anak['pendidikan_ayah']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">D. Pekerjaan</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($anak['pekerjaan_ayah']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">E. Agama</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($anak['agama_ayah']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">F. Alamat</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($anak['alamat_ayah']) ?></div>
</div>

<h4>Ibu</h4>
<div class="field-row">
    <div class="field-label">A. Nama</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($anak['nama_ibu']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">B. Usia</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($anak['usia_ibu']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">C. Pendidikan :</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($anak['pendidikan_ibu']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">D. Pekerjaan</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($anak['pekerjaan_ibu']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">E. Agama</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($anak['agama_ibu']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">F. Alamat</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($anak['alamat_ibu']) ?></div>
</div>

   <h3>3. Identitas Saudara Kandung</h3>
<table class="data">
    <tr>
        <th>No</th>
        <th>Nama</th>
        <th>Usia</th>
        <th>Hubungan</th>
        <th>Status Kesehatan</th>
    </tr>
    <?php foreach($anak['obat'] as $i => $row): ?>
    <tr>
        <td><?= $i+1 ?></td>
        <td><?= p($row['nama']) ?></td>
        <td><?= p($row['usia']) ?></td>
        <td><?= p($row['hubungan']) ?></td>
        <td><?= p($row['status']) ?></td>
    </tr>
    <?php endforeach; ?>
</table>
  
        
    <h3>4. Riwayat Kesehatan</h3>
    <div class="field-row">
        <div class="field-label">Alasan Masuk RS</div>
        <div class="field-sep">:</div>
        <div class="field-value"><?= p($anak['alasan_masuk']) ?></div>
    </div>
    <div class="field-row">
        <div class="field-label">Keluhan Utama</div>
        <div class="field-sep">:</div>
        <div class="field-value"><?= p($anak['keluhan_utama']) ?></div>
    </div>
    <div class="field-row">
        <div class="field-label">Riwayat Kesehatan Sekarang</div>
        <div class="field-sep">:</div>
        <div class="field-value"><?= p($anak['riwayat_sekarang']) ?></div>
    </div>
    <h3>5. Riwayat Kesehatan Lalu (khusus untuk anak usia 0–5 tahun)</h3>

<h4>a. Prenatal Care</h4>
<div class="field-row">
    <div class="field-label">a. Ibu memeriksakan kehamilannya setiap minggu di </div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($anak['prenatal_periksa']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">b. Keluhan selama hamil yang dirasakan oleh ibu </div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($anak['prenatal_keluhan']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">c. Riwayat berat badan selama hamil </div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($anak['prenatal_bb']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">d. Riwayat Imunisasi TT </div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($anak['prenatal_tt']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">Golongan darah ibu </div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($anak['goldar_ibu']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">Golongan darah ayah</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($anak['goldar_ayah']) ?></div>
</div>

<h4>b. IntraNatal</h4>
<div class="field-row">
    <div class="field-label">a. Tempat melahirkan</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($anak['tempat_lahir']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">b. Jenis persalinan</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($anak['jenis_persalinan']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">c. Penolong persalinan</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($anak['penolong']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">d. Komplikasi yang dialami oleh ibu pada saat melahirkan dan setelah melahirkan </div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($anak['komplikasi']) ?></div>
</div>

<h4>c. Postnatal</h4>
<div class="field-row">
    <div class="field-label">a. Kondisi bayi saat lahir</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($anak['kondisi_bayi']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">b. Apakah Anak pada saat lahir mengalami gangguan </div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($anak['gangguan_lahir']) ?></div>
</div>

<h4>(Untuk Semua Usia)</h4>

<div class="field-row">
    <div class="field-label">a. Klien pernah mengalami penyakit</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($anak['penyakit']) ?></div>
</div>

<div class="field-row">
    <div class="field-label">Pada Umur</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($anak['umur_penyakit']) ?></div>
</div>

<div class="field-row">
    <div class="field-label">Obat yang diberikan oleh</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($anak['obat_oleh']) ?></div>
</div>

<div class="field-row">
    <div class="field-label">b. Riwayat Kecelakaan</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($anak['kecelakaan']) ?></div>
</div>

<div class="field-row">
    <div class="field-label">c. Riwayat mengkonsumsi obat-obatan berbahaya / zat kimia berbahaya</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($anak['zat_berbahaya']) ?></div>
</div>

<div class="field-row">
    <div class="field-label">d. Perkembangan anak dibanding saudara-saudaranya</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($anak['perkembangan']) ?></div>
</div>

<h3>6. Riwayat Kesehatan Keluarga</h3>
<div class="field-row">
    <div class="field-label">Genogram </div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($anak['genogram']) ?></div>
</div>
    </table>

        <h3>7. Riwayat Imunisasi (Imunisasi Lengkap)</h3>

<table class="data">
    <thead>
        <tr>
            <th>NO</th>
            <th>Jenis Imunisasi</th>
            <th>Frekuensi</th>
            <th>Reaksi Setelah Pemberian</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>1.</td>
            <td>BCG</td>
            <td><?= p($riwayat['bcg_frekuensi']) ?></td>
            <td><?= p($riwayat['bcg_reaksi']) ?></td></td>
        </tr>
        <tr>
            <td>2.</td>
            <td>DPT (I, II, III)</td>
            <td><?= p($riwayat['dpt_frekuensi']) ?></td>
            <td><?= p($riwayat['dpt_reaksi']) ?></td>
        </tr>
        <tr>
            <td>3.</td>
            <td>Polio (I, II, III, IV)</td>
            <td><?= p($riwayat['polio_frekuensi']) ?></td>
            <td><?= p($riwayat['polio_reaksi']) ?></td>
        </tr>
        <tr>
            <td>4.</td>
            <td>Campak</td>
            <td><?= p($riwayat['campak_frekuensi']) ?></td>
            <td><?= p($riwayat['campak_reaksi']) ?></td>
        </tr>
        <tr>
            <td>5.</td>
            <td>Hepatitis</td>
            <td><?= p($riwayat['hepatitis_frekuensi']) ?></td>
            <td><?= p($riwayat['hepatitis_reaksi']) ?></td>
        </tr>
    </tbody>
</table>
<h3>8. Riwayat Tumbuh Kembang</h3>
<div class="data">
    <div class="field-row">
        <div class="field-label" style="width:30%; display:inline-block;"><strong>Berat Badan</strong></div>
        <div class="field-sep" style="width:2%; display:inline-block;">:</div>
        <div class="field-value" style="display:inline-block;"><?= p($riwayat['bb']) ?> kg</div>
    </div>
    <div class="field-row">
        <div class="field-label" style="width:30%; display:inline-block;"><strong>Tinggi Badan</strong></div>
        <div class="field-sep" style="width:2%; display:inline-block;">:</div>
        <div class="field-value" style="display:inline-block;"><?= p($riwayat['tb']) ?> cm</div>
    </div>
    <div class="field-row">
        <div class="field-label" style="width:30%; display:inline-block;"><strong>Waktu Tumbuh Gigi</strong></div>
        <div class="field-sep" style="width:2%; display:inline-block;">:</div>
        <div class="field-value" style="display:inline-block;"><?= p($riwayat['gigi']) ?></div>
    </div>
    <div class="field-row">
        <div class="field-label" style="width:30%; display:inline-block;"><strong>Tanggal Gigi Tumbuh</strong></div>
        <div class="field-sep" style="width:2%; display:inline-block;">:</div>
        <div class="field-value" style="display:inline-block;"><?= p($riwayat['gigi_tanggal']) ?>, <strong>Jumlah gigi</strong> <?= p($riwayat['gigi_jumlah']) ?> buah</div>
    </div>
</div>

<h3>9. Riwayat Nutrisi</h3>
<div class="data">
    <div class="field-row">
        <div class="field-label" style="width:30%; display:inline-block;"><strong>Pemberian ASI sampai usia</strong></div>
        <div class="field-sep" style="width:2%; display:inline-block;">:</div>
        <div class="field-value" style="display:inline-block;"><?= p($riwayat['asi']) ?></div>
    </div>
    <div class="field-row">
        <div class="field-label" style="width:30%; display:inline-block;"><strong>Alasan pemberian susu formula</strong></div>
        <div class="field-sep" style="width:2%; display:inline-block;">:</div>
        <div class="field-value" style="display:inline-block;"><?= p($riwayat['alasan_susu']) ?></div>
    </div>
    <div class="field-row">
        <div class="field-label" style="width:30%; display:inline-block;"><strong>Jumlah pemberian sehari</strong></div>
        <div class="field-sep" style="width:2%; display:inline-block;">:</div>
        <div class="field-value" style="display:inline-block;"><?= p($riwayat['jumlah_susu']) ?></div>
    </div>
</div>

<h3>10. Riwayat Psikososial</h3>
<div class="data">
    <div class="field-row">
        <div class="field-label" style="width:30%; display:inline-block;"><strong>Anak tinggal bersama</strong></div>
        <div class="field-sep" style="width:2%; display:inline-block;">:</div>
        <div class="field-value" style="display:inline-block;"><?= p($riwayat['tinggal_bersama']) ?>, di <?= p($riwayat['tinggal_di']) ?></div>
    </div>
    <div class="field-row">
        <div class="field-label" style="width:30%; display:inline-block;"><strong>Rumah dekat dengan</strong></div>
        <div class="field-sep" style="width:2%; display:inline-block;">:</div>
        <div class="field-value" style="display:inline-block;"><?= p($riwayat['rumah_dekat']) ?>, <strong>tempat anak bermain</strong>  <?= p($riwayat['tempat_bermain']) ?>, 
        <strong>Kamar Klien</strong> <?= p($riwayat['kamar_klien']) ?></div>
    </div>


<h3>11. Reaksi Hospitalisasi</h3>
<div class="data">
    <div class="field-row">
        <div class="field-label" style="width:30%; display:inline-block;"><strong>Ibu membawa anak ke RS karena</strong></div>
        <div class="field-sep" style="width:2%; display:inline-block;">:</div>
        <div class="field-value" style="display:inline-block;"><?= p($riwayat['alasan_rs']) ?></div>
    </div>
    <div class="field-row">
        <div class="field-label" style="width:30%; display:inline-block;"><strong>Dokter menceritakan kondisi anak</strong></div>
        <div class="field-sep" style="width:2%; display:inline-block;">:</div>
        <div class="field-value" style="display:inline-block;"><?= p($riwayat['penjelasan_dokter']) ?></div>
    </div>
    <div class="field-row">
        <div class="field-label" style="width:30%; display:inline-block;"><strong>Perasaan orang tua saat ini</strong></div>
        <div class="field-sep" style="width:2%; display:inline-block;">:</div>
        <div class="field-value" style="display:inline-block;"><?= p($riwayat['perasaan']) ?></div>
    </div>
    <div class="field-row">
        <div class="field-label" style="width:30%; display:inline-block;"><strong>Orang tua selalu berkunjung ke RS</strong></div>
        <div class="field-sep" style="width:2%; display:inline-block;">:</div>
        <div class="field-value" style="display:inline-block;"><?= p($riwayat['kunjungan']) ?></div>
    </div>
    <div class="field-row">
        <div class="field-label" style="width:30%; display:inline-block;"><strong>Yang akan tinggal menemani anak di rumah sakit</strong></div>
        <div class="field-sep" style="width:2%; display:inline-block;">:</div>
        <div class="field-value" style="display:inline-block;"><?= p($riwayat['pendamping']) ?></div>
    </div>
</div>

<h3>12. Reaksi Anak Selama Dirawat</h3>
<div class="data">
    <div class="field-row">
        <div class="field-label" style="width:30%; display:inline-block;"><strong>Reaksi Anak</strong></div>
        <div class="field-sep" style="width:2%; display:inline-block;">:</div>
        <div class="field-value" style="display:inline-block;"><?= p($riwayat['reaksi_anak']) ?></div>
    </div>
</div>
<div class="page-break"></div>
<h3>13. Aktivitas Sehari-hari</h3>

<!-- Nutrisi -->
<table class="data" border="1" cellspacing="0" cellpadding="5" width="100%">
    <thead>
        <tr>
            <th rowspan="2">Nutrisi</th>
            <th colspan="2">Kondisi</th>
        </tr>
        <tr>
            <th>Sebelum Sakit</th>
            <th>Saat Sakit</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Selera Makan</td>
            <td><?= p($riwayat['selera_sebelum']) ?></td>
            <td><?= p($riwayat['selera_saat']) ?></td>
        </tr>
        <tr>
            <td>Porsi Makan</td>
            <td><?= p($riwayat['porsi_sebelum']) ?></td>
            <td><?= p($riwayat['porsi_saat']) ?></td>
        </tr>
        <tr>
            <td>Menu Makanan</td>
            <td><?= p($riwayat['menu_sebelum']) ?></td>
            <td><?= p($riwayat['menu_saat']) ?></td>
        </tr>
    </tbody>
</table>

<!-- Cairan -->
<table class="data" border="1" cellspacing="0" cellpadding="5" width="100%" style="margin-top:10px;">
    <thead>
        <tr>
            <th rowspan="2">Cairan</th>
            <th colspan="2">Kondisi</th>
        </tr>
        <tr>
            <th>Sebelum Sakit</th>
            <th>Saat Sakit</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Jenis Minuman</td>
            <td><?= p($riwayat['jenis_minum_sebelum']) ?></td>
            <td><?= p($riwayat['jenis_minum_saat']) ?></td>
        </tr>
        <tr>
            <td>Frekuensi Minum</td>
            <td><?= p($riwayat['frekuensi_minum_sebelum']) ?></td>
            <td><?= p($riwayat['frekuensi_minum_saat']) ?></td>
        </tr>
        <tr>
            <td>Kebutuhan Cairan</td>
            <td><?= p($riwayat['kebutuhan_cairan_sebelum']) ?></td>
            <td><?= p($riwayat['kebutuhan_cairan_saat']) ?></td>
        </tr>
        <tr>
            <td>Cara Pemenuhan</td>
            <td><?= p($riwayat['cara_cairan_sebelum']) ?></td>
            <td><?= p($riwayat['cara_cairan_saat']) ?></td>
        </tr>
    </tbody>
</table>

<!-- Eliminasi BAK -->
<table class="data" border="1" cellspacing="0" cellpadding="5" width="100%" style="margin-top:10px;">
    <thead>
        <tr>
            <th rowspan="2">Eliminasi (BAK)</th>
            <th colspan="2">Kondisi</th>
        </tr>
        <tr>
            <th>Sebelum Sakit</th>
            <th>Saat Sakit</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Tempat Pembuangan</td>
            <td><?= p($riwayat['bak_tempat_sebelum']) ?></td>
            <td><?= p($riwayat['bak_tempat_saat']) ?></td>
        </tr>
        <tr>
            <td>Frekuensi</td>
            <td><?= p($riwayat['bak_frekuensi_sebelum']) ?></td>
            <td><?= p($riwayat['bak_frekuensi_saat']) ?></td>
        </tr>
        <tr>
            <td>Karakteristik</td>
            <td><?= p($riwayat['bak_karakteristik_sebelum']) ?></td>
            <td><?= p($riwayat['bak_karakteristik_saat']) ?></td>
        </tr>
    </tbody>
</table>

<!-- Eliminasi BAB -->
<table class="data" border="1" cellspacing="0" cellpadding="5" width="100%" style="margin-top:10px;">
    <thead>
        <tr>
            <th rowspan="2">Eliminasi (BAB)</th>
            <th colspan="2">Kondisi</th>
        </tr>
        <tr>
            <th>Sebelum Sakit</th>
            <th>Saat Sakit</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Tempat Pembuangan</td>
            <td><?= p($riwayat['bab_tempat_sebelum']) ?></td>
            <td><?= p($riwayat['bab_tempat_saat']) ?></td>
        </tr>
        <tr>
            <td>Frekuensi</td>
            <td><?= p($riwayat['bab_frekuensi_sebelum']) ?></td>
            <td><?= p($riwayat['bab_frekuensi_saat']) ?></td>
        </tr>
        <tr>
            <td>Karakteristik</td>
            <td><?= p($riwayat['bab_karakteristik_sebelum']) ?></td>
            <td><?= p($riwayat['bab_karakteristik_saat']) ?></td>
        </tr>
    </tbody>
</table>

<!-- Istirahat Tidur -->
<table class="data" border="1" cellspacing="0" cellpadding="5" width="100%" style="margin-top:10px;">
    <thead>
        <tr>
            <th rowspan="2">Istirahat Tidur</th>
            <th colspan="2">Kondisi</th>
        </tr>
        <tr>
            <th>Sebelum Sakit</th>
            <th>Saat Sakit</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Jam Tidur</td>
            <td>Siang: <?= p($riwayat['tidur_siang_sebelum']) ?>, Malam: <?= p($riwayat['tidur_malam_sebelum']) ?></td>
            <td>Siang: <?= p($riwayat['tidur_siang_sekarang']) ?>, Malam: <?= p($riwayat['tidur_malam_sekarang']) ?></td>
        </tr>
        <tr>
            <td>Pola Tidur</td>
            <td><?= p($riwayat['pola_tidur_sebelum']) ?></td>
            <td><?= p($riwayat['pola_tidur_sekarang']) ?></td>
        </tr>
        <tr>
            <td>Kebiasaan sebelum tidur</td>
            <td><?= p($riwayat['kebiasaan_tidur_sebelum']) ?></td>
            <td><?= p($riwayat['kebiasaan_tidur_sekarang']) ?></td>
        </tr>
        <tr>
            <td>Kesulitan tidur</td>
            <td><?= p($riwayat['kesulitan_tidur_sebelum']) ?></td>
            <td><?= p($riwayat['kesulitan_tidur_sekarang']) ?></td>
        </tr>
    </tbody>
</table>

<!-- Personal Hygiene -->
<table class="data" border="1" cellspacing="0" cellpadding="5" width="100%" style="margin-top:10px;">
    <thead>
        <tr>
            <th rowspan="2">Personal Hygiene</th>
            <th colspan="2">Kondisi</th>
        </tr>
        <tr>
            <th>Sebelum Sakit</th>
            <th>Saat Sakit</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Mandi</td>
            <td>Cara: <?= p($riwayat['mandi_cara_sebelum']) ?>, Frekuensi: <?= p($riwayat['mandi_frekuensi_sebelum']) ?>, Alat: <?= p($riwayat['mandi_tempat_sebelum']) ?></td>
            <td>Cara: <?= p($riwayat['mandi_cara_sekarang']) ?>, Frekuensi: <?= p($riwayat['mandi_frekuensi_sekarang']) ?>, Alat: <?= p($riwayat['mandi_tempat_sekarang']) ?></td>
        </tr>
        <tr>
            <td>Cuci Rambut</td>
            <td>Frekuensi: <?= p($riwayat['rambut_cara_sebelum']) ?>, Cara: <?= p($riwayat['rambut_cara_sebelum']) ?></td>
            <td>Frekuensi: <?= p($riwayat['rambut_frekuensi_sekarang']) ?>, Cara: <?= p($riwayat['rambut_cara_sekarang']) ?></td>
        </tr>
        <tr>
            <td>Gunting Kuku</td>
            <td>Frekuensi: <?= p($riwayat['kuku_frekuensi_sebelum']) ?>, Cara: <?= p($riwayat['kuku_cara_sebelum']) ?></td>
            <td>Frekuensi: <?= p($riwayat['kuku_frekuensi_sekarang']) ?>, Cara: <?= p($riwayat['kuku_cara_sekarang']) ?></td>
        </tr>
        <tr>
            <td>Gosok Gigi</td>
            <td>Frekuensi: <?= p($riwayat['gigi_frekuensi_sebelum']) ?>, Cara: <?= p($riwayat['gigi_cara_sebelum']) ?></td>
            <td>Frekuensi: <?= p($riwayat['gigi_frekuensi_sekarang']) ?>, Cara: <?= p($riwayat['gigi_cara_sekarang']) ?></td>
        </tr>
    </tbody>
</table>

<h3>14. Pemeriksaan Fisik</h3>

<!-- Keadaan Umum -->
<div class="field-row">
    <div class="field-label" style="width:30%; display:inline-block;">Keadaan Umum</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['keadaan_umum']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">Kesadaran</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['kesadaran']) ?></div>
</div>

<!-- Tanda-Tanda Vital -->
<div class="subsection-title"><strong>Tanda – tanda Vital</strong></div>
<div class="field-row">
    <div class="field-label">Tekanan Darah</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['tekanan_darah']) ?> mmHg</div>
</div>
<div class="field-row">
    <div class="field-label">Denyut Nadi</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['nadi']) ?> x/menit</div>
</div>
<div class="field-row">
    <div class="field-label">Suhu</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['suhu']) ?> °C</div>
</div>
<div class="field-row">
    <div class="field-label">Pernapasan</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['pernapasan']) ?> x/menit</div>
</div>
<div class="field-row">
    <div class="field-label">Berat Badan</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['bb']) ?> kg</div>
</div>
<div class="field-row">
    <div class="field-label">Tinggi Badan</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['tb']) ?> cm</div>
</div>

<!-- Kepala -->
 <h3>Kepala</h3>
<div class="subsection-title"><strong>Inspeksi</strong></div>
<div class="field-row">
    <div class="field-label">Rambut & Hygiene</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['rambut']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">Warna Rambut</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['warna_rambut']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">Penyebaran</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['penyebaran']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">Mudah Rontok</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['rontok']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">Kebersihan Rambut</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['kebersihan_rambut']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">Benjolan</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['benjolan']) ?> : <?= p($fisik['benjolan_keterangan'] ?? '') ?></div>
</div>
<div class="field-row">
    <div class="field-label">Nyeri Tekan</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['nyeri_tekan']) ?> : <?= p($fisik['nyeri_tekan_keterangan'] ?? '') ?></div>

</div>
<div class="field-row">
    <div class="field-label">Tekstur Rambut</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['tekstur_rambut']) ?> : <?= p($fisik['tekstur_rambut_keterangan'] ?? '') ?></div>
</div>

<!-- Wajah -->
 <h3>Wajah</h3>
<div class="subsection-title"><strong>Inpeksi</strong></div>
<div class="field-row">
    <div class="field-label">Simetris</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['simetris']) ?> : <?= p($fisik['simetris_keterangan'] ?? '') ?></div>
</div>
<div class="field-row">
    <div class="field-label">Bentuk Wajah</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['bentuk_wajah']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">Nyeri Tekan</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['nyeri_wajah']) ?> : <?= p($fisik['nyeri_wajah_keterangan'] ?? '') ?></div>
</div>
<div class="field-row">
    <div class="field-label">Data Lain</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['data_wajah']) ?></div>
</div>

<!-- Mata -->
 <h3>Mata</h3>
<div class="subsection-title"><strong>Inpeksi</strong></div>
<div class="field-row">
    <div class="field-label">Palpebra</div>
    <div class="field-sep">:</div>
    <div class="field-value"><strong>Edema :</strong>  <?= p($fisik['edema_palpebra']) ?>,<strong>Radang:</strong> <?= p($fisik['radang_palpebra']) ?> </div>
</div>
<div class="field-row">
    <div class="field-label">Sclera</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['sclera']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">Conjungtiva</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['radang_conjungtiva']) ?>  </div>
</div>
<div class="field-row">
    <div class="field-label"></div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['anemis']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">Pupil</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['pupil_bentuk']) ?></div>
    
</div>
<div class="field-row">
    <div class="field-label"></div>
    <div class="field-sep">:</div>

    <div class="field-value"><?= p($fisik['pupil_ukuran']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">Posisi Mata</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['posisi_mata']) ?> : <?= p($fisik['posisi_mata_keterangan'] ?? '') ?></div>
</div>
<div class="field-row">
    <div class="field-label">Gerakan Bola Mata</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['gerakan_mata']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">Penutupan Kelopak Mata</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['kelopak']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">Keadaan Bulu Mata</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['bulu_mata']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">Penglihatan</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['kabur']) ?></div>
</div>
<div class="field-row">
    <div class="field-label"></div>
    <div class="field-sep"></div>
    <div class="field-value"><?= p($fisik['diplopia']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">Data Lain</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['data_mata']) ?></div>
</div>
<!-- Hidung & Sinus -->
 <h3>Hidung & Sinus</h3>
<div class="subsection-title"><strong>Inpeksi</strong></div>
<div class="field-row">
    <div class="field-label">Bentuk Hidung</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['bentuk_hidung']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">Keadaan Septum</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['septum']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">Secret / Cairan</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['secret']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">Data Lain</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['data_hidung']) ?></div>
</div>

<!-- Telinga -->
 <h3>Telinga</h3>
<div class="subsection-title"><strong>Inpeksi</strong></div>
<div class="field-row">
    <div class="field-label">Lubang Telinga</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['telinga']) ?></div>
</div>
<div class="subsection-title"><strong>Palpasi</strong></div>
<div class="field-row">
    <div class="field-label">Nyeri Tekan</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['nyeri_telinga']) ?></div>
</div>

<!-- Mulut -->
 <h3>Mulut</h3>
<div class="subsection-title"><strong>Inpeksi</strong></div>
<div class="subsection-title"><strong>Gigi</strong></div>
<div class="field-row">
    <div class="field-label">Keadaan Gigi</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['keadaan_gigi']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">Karang Gigi / Karies</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['karies']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">Gusi</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['gusi']) ?> : <?= p($fisik['gusi_keterangan'] ?? '') ?></div>
</div>
<div class="field-row">
    <div class="field-label">Lidah</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['lidah']) ?></div>
</div><div class="field-row">
    <div class="field-label">Bibir (Warna)</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= safe_p($fisik['bibir_warna'] ?? '') ?></div>
</div>
<div class="field-row">
    <div class="field-label">Bibir (Kondisi)</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= safe_p($fisik['bibir_kondisi'] ?? '') ?></div>
</div>
<div class="field-row">
    <div class="field-label">Mulut Berbau</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['bau_mulut']) ?> : <?= p($fisik['bau_mulut_keterangan'] ?? '') ?></div>
   
</div>
<div class="field-row">
    <div class="field-label">Kemampuan Bicara</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['bicara']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">Data Lain</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['data_mulut']) ?></div>
</div>

<!-- Tenggorokan -->
 <h3>Tenggorokan</h3>
<div class="field-row">
    <div class="field-label">Warna Mukosa</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['mukosa']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">Nyeri Tekan</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['nyeri_tenggorokan']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">Nyeri Menelan</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['menelan']) ?></div>
</div>

<!-- Leher -->
 <h3>Leher</h3>
<div class="subsection-title"><strong>Palpasi</strong></div>
<div class="field-row">
    <div class="field-label">Kelenjar Limfe</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['limfe']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">Data Lain</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['data_leher']) ?></div>
</div>

<!-- Thorax dan Pernapasan -->
 <h3>Thorax dan Pernapasan</h3>
<div class="field-row">
    <div class="field-label">Bentuk Dada</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['bentuk_dada']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">Irama Pernapasan</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['irama_nafas']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">Pengembangan di Waktu Bernapas</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['pengembangan']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">Tipe Pernapasan</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['tipe_nafas']) ?></div>
</div>
<div class="subsection-title"><strong>Auskultasi</strong></div>
<div class="field-row">
    <div class="field-label">Suara Nafas</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['suara_auskultas']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">Suara Nafas</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['suara_auskultas']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">Suara Tambahan</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['suara_tambahan']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">Perkusi</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['perkusi']) ?></div>
</div>

<!-- Jantung -->
 <h3>Jantung</h3>
<div class="subsection-title"><strong>Palpasi</strong></div>
<div class="field-row">
    <div class="field-label">Ictus Cordis</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['ictus_cordis']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">Pembesaran Jantung</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['pembesaran_jantung']) ?></div>
</div>
<div class="subsection-title"><strong>Auskultasi</strong></div>
<div class="field-row">
    <div class="field-label">Auskultasi BJ I</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['bj1']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">Auskultasi BJ II</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['bj2']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">Auskultasi BJ III</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['bj3']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">Bunyi tambahan</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['bunyi_tambahan']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">Data Lain</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['data_lain_jantung']) ?></div>
</div>
<!-- Abdomen -->
 <h3>Abdomen</h3>
<div class="subsection-title"><strong>Inpeksi</strong></div>
<div class="field-row">
    <div class="field-label">Membuncit</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['membuncit']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">Ada luka</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['luka_abdomen']) ?> : <?= p($fisik['luka_abdomen_lain'] ?? '') ?></div>
</div>
<div class="subsection-title"><strong>Auskultasi</strong></div>
<div class="field-row">
    <div class="field-label">Peristaltik</div><div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['peristaltik']) ?></div>
</div>

<div class="subsection-title"><strong>Palpasi</strong></div>
<div class="field-row">
    <div class="field-label">Hepar</div><div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['hepar']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">Lien</div><div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['lien']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">Nyeri Tekan</div><div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['nyeri']) ?></div>
</div>
<div class="subsection-title"><strong>Perkusi</strong></div>
<div class="field-row">
    <div class="field-label">Tympani</div><div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['tympani']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">Redup</div><div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['redup']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">Data Lain</div><div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['data_abdomen']) ?></div>
</div>

<!-- Genitalia dan Anus -->
 <h3>Genitalia</h3>
<div class="subsection-title"><strong>Anak Laki-laki</strong></div>
<div class="field-row">
    <div class="field-label">Fistula Urinari (Laki-laki)</div><div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['fistula_pria']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">Lubang Uretra</div><div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['uretra']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">Skrotum</div><div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['skrotum']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">Genitalia Ganda</div><div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['genital_ganda']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">Hidrokel</div><div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['hidrokel_pria']) ?></div>
</div>
<div class="subsection-title"><strong>Anak Perempuan</strong></div>
<div class="field-row">
    <div class="field-label">Labia & Klitoris</div><div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['labia']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">Fistula Urogenital (Perempuan)</div><div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['fistula_wanita']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">Hidrokel</div><div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['hidrokel_wanita']) ?></div>
</div>


<!-- Anus -->
 <h3>Anus</h3>
<div class="field-row">
    <div class="field-label">Lubang Anal Paten</div><div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['anus_paten']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">Lintasan Mekonium (36 jam)</div><div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['mekonium']) ?></div>
</div>

<!-- Ekstremitas -->
 <h3>Ekstremitas Atas</h3>
<div class="subsection-title"><strong>Motorik</strong></div>
<div class="field-row">
    <div class="field-label">Pergerakan Kanan/Kiri</div><div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['gerak_atas']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">Pergerakan Abnormal</div><div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['abnormal_atas']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">Kekuatan Otot Kanan/Kiri</div><div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['kekuatan_atas']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">Koordinasi Gerak</div><div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['koordinasi_atas']) ?></div>
</div>
<div class="subsection-title"><strong>Sensori</strong></div>
<div class="field-row">
    <div class="field-label">Nyeri</div><div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['nyeri_atas']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">Rangsang Suhu</div><div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['suhu_atas']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">Rasa Raba</div><div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['raba_atas']) ?></div>
</div>
<!-- Ekstremitas -->
 <h3>Ekstremitas Bawah</h3>
<div class="subsection-title"><strong>Motorik</strong></div>
<div class="field-row">
    <div class="field-label">Gaya Berjalan</div><div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['gaya_jalan']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">Kekuatan Otot Kanan/Kiri</div><div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['kekuatan_bawah']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">Tonus Otot Kanan/Kiri</div><div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['tonus_bawah']) ?></div>
</div>
<div class="subsection-title"><strong>Sensori</strong></div>
<div class="field-row">
    <div class="field-label">Nyeri</div><div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['nyeri_bawah']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">Rangsang Suhu</div><div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['suhu_bawah']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">Rasa Raba</div><div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['raba_bawah']) ?></div>
</div>

<div class="subsection-title"><strong>Tanda Perangsangan Selaput Otak & Refleks Bayi</strong></div>
<div class="field-row">
        <div class="field-label" >Kaku Kuduk</div>
        <div class="field-sep">:</div>
        <div class="field-value"><?= p($fisik['kaku_kuduk']) ?></div>
    </div>

    <!-- Kernig -->
    <div class="field-row">
        <div class="field-label">Kernig Sign</div>
        <div class="field-sep">:</div>
        <div class="field-value"><?= p($fisik['kernig']) ?></div>
    </div>

    <!-- Brudzinski -->
    <div class="field-row ">
        <div class="field-label">Refleks Brudzinski</div>
        <div class="field-sep">:</div>
        <div class="field-value"><?= p($fisik['brudzinski']) ?></div>
    </div>

    <!-- Refleks pada Bayi -->
    <div class="field-row ">
        <div class="field-label">Refleks pada Bayi</div>
        <div class="field-sep">:</div>
        <div class="field-value"><?= p($fisik['refleks_bayi']) ?></div>
    </div>

    <!-- Iddol -->
    <div class="field-row ">
        <div class="field-label">Refleks Iddol</div>
        <div class="field-sep">:</div>
        <div class="field-value"><?= p($fisik['iddol']) ?></div>
    </div>

    <!-- Startel -->
    <div class="field-row ">
        <div class="field-label">Refleks Startel</div>
        <div class="field-sep">:</div>
        <div class="field-value"><?= p($fisik['startel']) ?></div>
    </div>

    <!-- Sucking -->
    <div class="field-row">
        <div class="field-label">Refleks Sucking (Isap)</div>
        <div class="field-sep">:</div>
        <div class="field-value"><?= p($fisik['sucking']) ?></div>
    </div>

    <!-- Rooting -->
    <div class="field-row">
        <div class="field-label">Refleks Rooting (Menoleh)</div>
        <div class="field-sep">:</div>
        <div class="field-value"><?= p($fisik['rooting']) ?></div>
    </div>

    <!-- Gawn -->
    <div class="field-row">
        <div class="field-label" >Refleks Gawn</div>
        <div class="field-sep">:</div>
        <div class="field-value"><?= p($fisik['gawn']) ?></div>
   </div>

    <!-- Grabella -->
    <div class="field-row">
        <div class="field-label">Refleks Grabella</div>
        <div class="field-sep">:</div>
        <div class="field-value"><?= p($fisik['grabella']) ?></div>
    </div>

    <!-- Ekruction -->
    <div class="field-row">
        <div class="field-label">Refleks Ekruction</div>
        <div class="field-sep">:</div>
        <div class="field-value"><?= p($fisik['ekruction']) ?></div>
    </div>

    <!-- Moro -->
    <div class="field-row">
        <div class="field-label">Refleks Moro</div>
        <div class="field-sep">:</div>
        <div class="field-value"><?= p($fisik['moro']) ?></div>
    </div>

    <!-- Grasping -->
    <div class="field-row">
        <div class="field-label" >Refleks Grasping</div>
        <div class="field-sep">:</div>
        <div class="field-value"><?= p($fisik['grasping']) ?></div>
    </div>

    <!-- Peres -->
    <div class="field-row">
        <div class="field-label">Refleks Peres</div>
        <div class="field-sep">:</div>
        <div class="field-value"><?= p($fisik['peres']) ?></div>
    </div>

    <!-- Kremaster -->
    <div class="field-row ">
        <div class="field-label" >Refleks Kremaster</div>
        <div class="field-sep">:</div>
        <div class="field-value"><?= p($fisik['kremaster']) ?></div>
    </div>

<!-- Integumen (Kulit, Rambut, Kuku) -->
 <h3>Integumen</h3>
<div class="field-row">
    <div class="field-label">Turgor Kulit</div><div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['turgor']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">Finger Print di Dahi</div><div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['finger_print']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">Adanya Lesi</div><div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['lesi']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">Kebersihan Kulit</div><div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['kebersihan']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">Kelembaban Kulit</div><div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['kelembaban']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">Warna Kulit</div><div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['warna_kulit']) ?></div>
</div>
<h3>15. Pemeriksaan Tingkat Perkembangan (0–6 Tahun) – DDST</h3>

<div class="field-row">
    <div class="field-label" >Motorik Kasar</div>
    <div class="field-sep">:</div>
    <div class="field-value" style="display:inline-block;"><?= p($fisik['motorik_kasar_input']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">Motorik Halus</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['motorik_halus_input']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">Bahasa</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['bahasa_input']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">Personal / Sosial</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['personal_social_input']) ?></div>
</div>

<h4>16. Test Diagnostik</h4>
<div class="field-row">
    <div class="field-label">Test Diagnostik</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['diagnostik']) ?></div>
</div>

<h4>17. Laboratorium</h4>
<div class="field-row">
    <div class="field-label">Laboratorium</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['laboratorium']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">Link drive Laboratorium</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['penunjang_link']) ?></div>
</div>

<div class="field-row">
    <div class="field-label">Pemeriksaan Penunjang</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['penunjang']) ?></div>
</div>

<h4>Terapi Saat Ini</h4>
<div class="field-row">
    <div class="field-label">Terapi Saat Ini</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['terapi']) ?></div>
</div>

        <div class="page-break"></div>

        <!-- ================================ -->
        <!-- SECTION 4: PROGRAM TERAPI & LAB -->
        <!-- ================================ -->
      

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