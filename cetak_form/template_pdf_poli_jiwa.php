<?php
// Shortcut per section
$lp   = $sections['format_lp'] ?? [];
$resume     = $sections['format_resume'] ?? [];
$lainnya       = $sections['lainnya'] ?? [];



include 'template_pdf.php';
?>


<body>
    <div >

        <!-- HEADER -->
        <h1>Format Pengkajian Pendahuluan</h1>
        <h2>PRAKTIK KLINIK KEPERAWATAN JIWA</h2>

<h4>A. Masalah Keperawatan Utama</h4>
<div class="field-row">
    <div class="field-value"><?= p($lp['masalah_keperawatan_utama']) ?></div>
</div>

<h4>B. Proses Terjadinya Masalah</h4>

<div class="field-row">
    <div class="field-label">1. Pengertian</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($lp['pengertian']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">2. Tanda dan Gejala</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($lp['tanda_gejala']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">3. Rentang Respons</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($lp['rentang_respons']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">4. Faktor Predisposisi</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($lp['faktor_predisposisi']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">5. Faktor Presipitasi</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($lp['faktor_presipitasi']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">6. Sumber Koping</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($lp['sumber_koping']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">7. Mekanisme Koping</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($lp['mekanisme_koping']) ?></div>
</div>

<h4>8. Pohon Masalah</h4>
<div class="field-row">
<div class="field-sep">:</div>
<div class="field-value"><?= p($lp['pohon_masalah']) ?></div>
</div>

<h4>9. Masalah Keperawatan yang Mungkin Muncul</h4>
<div class="field-row">
    <div class="field-value"><?= p($lp['masalah_keperawatan_muncul']) ?></div>
</div>

<h4>10. Data yang Perlu Dikaji</h4>
<table class="header-table" style="border:1px solid #000;">
    <tr>
       
        <th>Masalah Keperawatan</th>
        <th>Data yang Perlu Dikaji</th>
    </tr>
    <?php foreach($lp['evaluasi'] as $row): ?>
    <tr>
       
        <td><?= p($row['masalah']) ?></td>
        <td>
            
            Subjektif: <?= p($row['data_dikaji_subjektif']) ?><br>
            Objektif: <?= p($row['data_dikaji_objektif']) ?>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<h4>11. Diagnosa Keperawatan yang Mungkin Muncul</h4>
<div class="field-row">
    <div class="field-value"><?= p($lp['diagnosa_muncul']) ?></div>
</div>

<h4>12. Rencana Tindakan Keperawatan</h4>
<div class="field-row">
    <div class="field-value"><?= p($lp['rencana_keperawatan']) ?></div>
</div>

<h4>13. Daftar Pustaka</h4>
<div class="field-row">
    <div class="field-value"><?= p($lp['daftar_pustaka']) ?></div>
</div>
<div class="page-break"></div>

        <!-- ================================ -->
        <!-- SECTION 2: RIWAYAT KEHAMILAN -->
        <!-- ================================ -->
          <!-- HEADER -->
        <h1>Format Resume</h1>
        <h2>Keperawatan Jiwa</h2>
        <h3 class="mt-5">Riwayat Kehamilan dan Persalinan</h3>

       <h3 class="mt-5">Identitas Klien</h3>
<table class="header-table" style="border:1px solid #000;">
    <tr>
        <td width="25%"><strong>Nama</strong></td>
        <td width="2%">:</td>
        <td width="23%"><?= p($resume['nama']) ?></td>
        <td width="25%"><strong>Umur</strong></td>
        <td width="2%">:</td>
        <td><?= p($resume['umur']) ?></td>
    </tr>
    <tr>
        <td><strong>Agama</strong></td>
        <td>:</td>
        <td><?= p($resume['agama']) ?></td>
        <td><strong>Status Perkawinan</strong></td>
        <td>:</td>
        <td><?= p($resume['status_perkawinan']) ?></td>
    </tr>
    <tr>
        <td><strong>Pekerjaan</strong></td>
        <td>:</td>
        <td><?= p($resume['pekerjaan']) ?></td>
        <td><strong>Alamat</strong></td>
        <td>:</td>
        <td><?= p($resume['alamat']) ?></td>
    </tr>
    <tr>
        <td><strong>Kunjungan ke</strong></td>
        <td>:</td>
        <td><?= p($resume['kunjungan_ke']) ?></td>
        <td><strong>Diagnosa Medis</strong></td>
        <td>:</td>
        <td><?= p($resume['diagnosa_medis']) ?></td>
    </tr>
    <tr>
        <td><strong>Tanggal Pengkajian</strong></td>
        <td>:</td>
        <td colspan="4"><?= p($resume['tanggal_pengkajian']) ?></td>
    </tr>
</table>
        <h4>Alasan Masuk</h4>
<div class="field-row">
    <div class="field-value"><?= p($resume['alasan_masuk']) ?></div>
</div>
<h4>Riwayat Kesehatan Masa Lalu</h4>
<div class="field-row">
    <div class="field-value"><?= p($resume['riwayat_kesehatan_masa_lalu']) ?></div>
</div>
<h4>Data Fokus</h4>
<div class="field-row">
    <div class="field-label">Data Subjektif</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($resume['data_subjektif']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">Data Objektif</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($resume['data_objektif']) ?></div>
</div>

<!-- ================================ -->
<!-- SECTION E: ANALISA DATA -->
<!-- ================================ -->
<h4>Analisa Data</h4>
<table class="header-table" style="border:1px solid #000;">
    <tr>
        <th>No</th>
        <th>Data Subjektif</th>
        <th>Data Objektif</th>
        <th>Masalah</th>
    </tr>
    <?php foreach($resume['analisa'] as $i => $row): ?>
    <tr>
        <td><?= $i+1 ?></td>
        <td><?= p($row['data_subjektif_analisa']) ?></td>
        <td><?= p($row['data_objektif_analisa']) ?></td>
        <td><?= p($row['masalah']) ?></td>
    </tr>
    <?php endforeach; ?>
</table>

<!-- ================================ -->
<!-- SECTION F: DAFTAR MASALAH KEPERAWATAN -->
<!-- ================================ -->
<h4>Daftar Masalah Keperawatan</h4>
<div class="field-row">
    <div class="field-value"><?= p($resume['daftar_masalah']) ?></div>
</div>

<!-- ================================ -->
<!-- SECTION G: POHON MASALAH -->
<!-- ================================ -->
<h4>Pohon Masalah</h4>
<div class="field-row">
    <div class="field-label">Efek</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($resume['efek']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">Cara Problem</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($resume['cara_problem']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">Etiologi</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($resume['etilogoi']) ?></div>
</div>

<!-- ================================ -->
<!-- SECTION H: DIAGNOSA KEPERAWATAN -->
<!-- ================================ -->

       <div class="page-break"></div>

<!-- ================================ -->
<!-- SECTION 6: CATATAN KEPERAWATAN -->
<!-- ================================ -->
<h3 class="mt-5">Diagnosa Keperawatan</h3>

<table class="data">
    <thead>
        <tr>
            <th>No</th>
            <th>Diagnosa Keperawatan</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($lainnya['diagnosa'])): ?>
            <?php foreach ($lainnya['diagnosa'] as $index => $dx): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= p($dx['diagnosa']) ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="2" style="text-align:center">-</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<h3 class="mt-5">Intervensi Keperawatan</h3>

<table class="data">
    <thead>
        <tr>
            <th>No</th>
            <th width="40%">Diagnosa Keperawatan</th>
            <th width="30%">Tujuan</th>
            <th width="30%">Kriteria Hasil</th>
            <th width="30%">Intervensi</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($lainnya['intervensi'])): ?>
            <?php foreach ($lainnya['intervensi'] as $index => $inv): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= p($inv['diagnosa']) ?></td>
                    <td><?= p($inv['tujuan']) ?></td>
                    <td><?= p($inv['kriteria']) ?></td>
                    <td><?= p($inv['intervensi']) ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="5" style="text-align:center">-</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<h3 class="mt-5">Implementasi dan Evaluasi</h3>

<table class="data">
    <thead>
        <tr>
            <th>No</th>
            <th>Hari/Tanggal/Jam</th>
            <th>Diagnosa Keperawatan</th>
            <th>Implementasi</th>
            <th>S</th>
            <th>O</th>
            <th>A</th>
            <th>P</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($lainnya['implementasi'])): ?>
            <?php foreach ($lainnya['implementasi'] as $index => $impl): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= p($impl['hari_tgl']) ?></td>
                    <td><?= p($impl['diagnosa']) ?></td>
                    <td><?= p($impl['implementasi']) ?></td>
                    <td><?= p($impl['evaluasi_s']) ?></td>
                    <td><?= p($impl['evaluasi_o']) ?></td>
                    <td><?= p($impl['evaluasi_a']) ?></td>
                    <td><?= p($impl['evaluasi_p']) ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="8" style="text-align:center">-</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

    </div>
</body>

</html>