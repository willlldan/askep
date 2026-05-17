<?php
// Shortcut per section
$lp   = $sections['format_lp'] ?? [];
$pengkajian     = $sections['pengkajian'] ?? [];
$lanjut     = $sections['pengkajian_lanjut'] ?? [];
$lainnya       = $sections['lainnya'] ?? [];



// Helper: decode JSON array fields
function arr($val)
{
    if (is_array($val)) return $val;
    if (is_string($val)) {
        $decoded = json_decode($val, true);
        return is_array($decoded) ? $decoded : [];
    }
    return [];
}

// Helper: checkbox mark
function chk($val, $match)
{
    return (strtolower(trim($val)) === strtolower(trim($match))) ? printCheck('☑') : printCheck('☐');
}

// Helper: checked if value contains match
function chkIn($arr_or_str, $match)
{
    $items = is_array($arr_or_str) ? $arr_or_str : arr($arr_or_str);
    foreach ($items as $item) {
        if (strtolower(trim($item)) === strtolower(trim($match))) return printCheck('☑');
    }
    return printCheck('☐');
}

function printCheck($val)
{
    return '<span style="font-family: DejaVu Sans, serif;">' . $val . '</span>';
}

// Helper: activity score row
function actRow($label, $val)
{
    $scores = ['0', '1', '2', '3', '4'];
    $cells = '';
    foreach ($scores as $s) {
        $cells .= '<td style="text-align:center;border:1px solid #000;">' . (trim((string)$val) === $s ? printCheck('☑') : printCheck('☐')) . '</td>';
    }
    return '<tr><td style="border:1px solid #000;padding:2px 4px;">' . $label . '</td>' . $cells . '</tr>';
}

include 'template_pdf.php';
?>


<body>
    <div >

        <!-- HEADER -->
        <h1>Format Laporan Pendahuluan</h1>
        <h2>Praktik Klinik Keperawatan Jiwa RSUD</h2>
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
    <div class="field-value"><?= p($lp['gejala_tanda']) ?></div>
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
<div class="field-value"><?= p($lp['pohon_masalah']) ?></div>
</div>

<h4>9. Masalah Keperawatan yang Mungkin Muncul</h4>
<div class="field-row">
    <div class="field-value"><?= p($lp['masalah_keperawatan_muncul']) ?></div>
</div>

<h4>10. Data yang Perlu Dikaji</h4>
<table class="data" style="border:1px solid #000;">
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
    <div class="field-value"><?= p($lp['rencana_tindakan']) ?></div>
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
        <h1>Format Pengkajian Jiwa</h1>
      <div class="field-row d-flex gap-3 mb-2">
        <div class="field-label" style="min-width:180px;">Ruang Rawat</div>
        <div class="field-sep">:</div>
        <div class="field-value"><?= p($pengkajian['ruang_rawat']) ?></div>
    </div>
    <div class="field-row d-flex gap-3 mb-2">
        <div class="field-label">Tanggal Rawat</div>
        <div class="field-sep">:</div>
        <div class="field-value"><?= p($pengkajian['tanggal_rawat']) ?></div>
    </div>

       <h3 class="mt-5">Identitas Klien</h3>
  <!-- IDENTITAS KLIEN -->
    <div class="field-row d-flex gap-3 mb-2">
        <div class="field-label" style="min-width:180px;">Nama Klien</div>
        <div class="field-sep">:</div>
        <div class="field-value"><?= p($pengkajian['nama_klien']) ?></div>
    </div>
    <div class="field-row d-flex gap-3 mb-2">
        <div class="field-label">Jenis Kelamin</div>
        <div class="field-sep">:</div>
        <div class="field-value"><?= p($pengkajian['jenis_kelamin']) ?></div>
    </div>
    <div class="field-row d-flex gap-3 mb-2">
        <div class="field-label">Tanggal Pengkajian</div>
        <div class="field-sep">:</div>
        <div class="field-value"><?= p($pengkajian['tanggal_pengkajian']) ?></div>
    </div>
    <div class="field-row d-flex gap-3 mb-2">
        <div class="field-label">Umur</div>
        <div class="field-sep">:</div>
        <div class="field-value"><?= p($pengkajian['umur']) ?></div>
    </div>
    <div class="field-row d-flex gap-3 mb-2">
        <div class="field-label">RM</div>
        <div class="field-sep">:</div>
        <div class="field-value"><?= p($pengkajian['rm']) ?></div>
    </div>
    <div class="field-row d-flex gap-3 mb-2">
        <div class="field-label">Informasi</div>
        <div class="field-sep">:</div>
        <div class="field-value"><?= p($pengkajian['informasi']) ?></div>
    </div>
     <h3 class="mt-5">II. ALASAN MASUK</h3>

    <!-- ALASAN MASUK -->
    <div class="field-row d-flex gap-3 mb-2">
        <div class="field-label" style="min-width:180px;">Alasan Masuk</div>
        <div class="field-sep">:</div>
        <div class="field-value"><?= p($pengkajian['alasanmasuk']) ?></div>
    </div>
    <h3 class="mt-5">III. FAKTOR PREDISPOSISI</h3>
     <div class="field-row d-flex gap-3 mb-2">
        <div class="field-label">1. Pernah mengalami gangguan jiwa di masa lalu?</div>
        <div class="field-sep">:</div>
        <div class="field-value"><?= p($pengkajian['gangguan_jiwa']) ?></div>
    </div>
    <div class="field-row d-flex gap-3 mb-2">
        <div class="field-label">2. Pengobatan sebelumnya</div>
        <div class="field-sep">:</div>
        <div class="field-value"><?= p($pengkajian['pengobatan']) ?></div>
    </div>

<h4>3. Riwayat Kekerasan / Trauma</h4>


<table class="data" border="1" cellpadding="5" cellspacing="0" style="border-collapse: collapse; width:100%;">
    <thead>
        <tr>
            <th>Jenis Kekerasan / Kejadian</th>
            <th>Pelaku / Usia</th>
            <th>Korban / Usia</th>
            <th>Saksi / Usia</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Aniaya Fisik</td>
            <td><?= p($pengkajian['aniaya_fisik_pelaku'] ?? '') ?></td>
            <td><?= p($pengkajian['aniaya_fisik_korban'] ?? '') ?></td>
            <td><?= p($pengkajian['aniaya_fisik_saksi'] ?? '') ?></td>
        </tr>
        <tr>
            <td>Aniaya Seksual</td>
            <td><?= p($pengkajian['aniaya_seksual_pelaku'] ?? '') ?></td>
            <td><?= p($pengkajian['aniaya_seksual_korban'] ?? '') ?></td>
            <td><?= p($pengkajian['aniaya_seksual_saksi'] ?? '') ?></td>
        </tr>
        <tr>
            <td>Penolakan</td>
            <td><?= p($pengkajian['penolakan_pelaku'] ?? '') ?></td>
            <td><?= p($pengkajian['penolakan_korban'] ?? '') ?></td>
            <td><?= p($pengkajian['penolakan_saksi'] ?? '') ?></td>
        </tr>
        <tr>
            <td>Kekerasan dalam keluarga</td>
            <td><?= p($pengkajian['kekerasan_keluarga_pelaku'] ?? '') ?></td>
            <td><?= p($pengkajian['kekerasan_keluarga_korban'] ?? '') ?></td>
            <td><?= p($pengkajian['kekerasan_keluarga_saksi'] ?? '') ?></td>
        </tr>
        <tr>
            <td>Tindakan Kriminal</td>
            <td><?= p($pengkajian['tindakan_kriminal_pelaku'] ?? '') ?></td>
            <td><?= p($pengkajian['tindakan_kriminal_korban'] ?? '') ?></td>
            <td><?= p($pengkajian['tindakan_kriminal_saksi'] ?? '') ?></td>
        </tr>
    </tbody>
</table>
<div class="field-row d-flex gap-3 mb-2">
        <div class="field-label">Jelaskan No 1,2,3</div>
        <div class="field-sep">:</div>
        <div class="field-value"><?= p($pengkajian['penjelasan_kejadian']) ?></div>
    </div>

<div class="field-row d-flex gap-3 mb-2">
    <div class="field-label" style="min-width:200px;">4. Adakah anggota keluarga yang mengalami gangguan jiwa</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= ($pengkajian['gangguan_jiwa']) ?></div>
</div>

<div class="field-row d-flex gap-3 mb-2">
    <div class="field-label" style="min-width:200px;">Hubungan Keluarga</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($pengkajian['Hubungan_keluarga1']) ?></div>
</div>

<div class="field-row d-flex gap-3 mb-2">
    <div class="field-label">Gejala</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($pengkajian['Gejala']) ?></div>
</div>

<div class="field-row d-flex gap-3 mb-2">
    <div class="field-label">Riwayat</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($pengkajian['Riwayat']) ?></div>
</div>

<div class="field-row d-flex gap-3 mb-2">
    <div class="field-label">Pengobatan / Perawatan</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($pengkajian['Pengobatan_perawatan']) ?></div>
</div>

<!-- 5. Pengalaman Masa Lalu Tidak Menyenangkan -->

<div class="field-row d-flex gap-3 mb-2">
    <div class="field-label" style="min-width:200px;">5. Pengalaman Masa Lalu yang Tidak Menyenangkan</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($pengkajian['pengalaman_masa_lalu']) ?></div>
</div>
<!-- Penjelasan tambahan -->

<h3>IV. Pemeriksaan Fisik</h3>

<!-- 1. Tanda Vital -->
<h4>1. Tanda Vital</h4>
<div class="field-row d-flex gap-3 mb-2">
    <div class="field-label" style="min-width:120px;">TD</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($pengkajian['td']) ?> mmHg</div>

    <div class="field-label" style="min-width:120px;">Nadi</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($pengkajian['nadi']) ?> x/menit</div>
</div>

<div class="field-row d-flex gap-3 mb-2">
    <div class="field-label" style="min-width:120px;">Suhu</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($pengkajian['suhu']) ?> °C</div>

    <div class="field-label" style="min-width:120px;">Pernapasan</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($pengkajian['pernafasan']) ?> x/menit</div>
</div>

<!-- 2. Pengukuran -->
<h4>2. Pengukuran</h4>
<div class="field-row d-flex gap-3 mb-2">
    <div class="field-label" style="min-width:120px;">TB</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($pengkajian['tb']) ?> cm</div>

    <div class="field-label" style="min-width:120px;">BB</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($pengkajian['bb']) ?> kg</div>
</div>

<!-- 3. Keluhan Fisik -->
<h4>3. Keluhan Fisik</h4>
<div class="field-row d-flex gap-3 mb-2">
    <div class="field-label" style="min-width:180px;">Ada Keluhan Fisik</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= ($pengkajian['keluhan_fisik'] ) ?></div>
</div>
<div class="field-row d-flex gap-3 mb-2">
    <div class="field-label" style="min-width:180px;">Penjelasan</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= ($pengkajian['penjelasan'] ) ?></div>
</div>

<h3>V. Psikososial</h3>

<!-- 1. Genogram -->
<div class="field-row d-flex gap-3 mb-2">
    <div class="field-label" style="min-width:180px;">1. Genogram</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($pengkajian['genogram']) ?></div>
</div>

<!-- 2. Konsep Diri -->
<h4>2.Konsep Diri</h4>
<div class="field-row d-flex gap-3 mb-2">
    <div class="field-label">Gambaran Diri</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($pengkajian['gambaran_diri']) ?></div>
</div>

<div class="field-row d-flex gap-3 mb-2">
    <div class="field-label">Identitas Diri</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($pengkajian['identitas_diri']) ?></div>
</div>

<div class="field-row d-flex gap-3 mb-2">
    <div class="field-label">Peran</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($pengkajian['peran']) ?></div>
</div>

<div class="field-row d-flex gap-3 mb-2">
    <div class="field-label">Ideal Diri</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($pengkajian['ideal_diri']) ?></div>
</div>

<div class="field-row d-flex gap-3 mb-2">
    <div class="field-label">Harga Diri</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($pengkajian['harga_diri']) ?></div>
</div>

<!-- 3. Hubungan Sosial -->
<h4>3. Hubungan Sosial</h4>
<div class="field-row d-flex gap-3 mb-2">
    <div class="field-label">Orang yang berarti</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($pengkajian['orang_berarti']) ?></div>
</div>

<div class="field-row d-flex gap-3 mb-2">
    <div class="field-label">b. Peran serta dalam kegiatan kelompok/ masyarakat</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($pengkajian['kegiatan_kelompok']) ?></div>
</div>

<div class="field-row d-flex gap-3 mb-2">
    <div class="field-label">c. Hambatan dalam hubungan dengan orang lain</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($pengkajian['hambatan_hubungan']) ?></div>
</div>

<!-- 4. Spiritual -->
<h4>4. Spiritual</h4>
<div class="field-row d-flex gap-3 mb-2">
    <div class="field-label">Nilai dan Keyakinan</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($pengkajian['nilai_keyakinan']) ?></div>
</div>

<div class="field-row d-flex gap-3 mb-2">
    <div class="field-label">Kegiatan Ibadah</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($pengkajian['kegiatan_ibadah']) ?></div>
</div>
<h4>VI. Status Mental</h4>
<<<<<<< HEAD
<?php $penampilan_arr = arr($pengkajian['penampilan']); ?>
<?php $pembicaraan_arr = arr($pengkajian['pembicaraan']); ?>
<?php $aktivitas_motorik_arr = arr($pengkajian['motorik']); ?>
<?php $alam_perasaan_arr = arr($pengkajian['alam_perasaan']); ?>
<?php $interaksi_arr = arr($pengkajian['interaksi_wawancara']); ?>
<?php $persepsi_sensorik_arr = arr($pengkajian['persepsi_sensorik']); ?>
<?php $proses_pikir_arr = arr($pengkajian['proses_pikir']); ?>
<?php $isi_pikir_arr = arr($pengkajian['isi_pikir']); ?>
<?php $tingkat_kesadaran_arr = arr($pengkajian['tingkat_kesadaran']); ?>
<?php $memori_arr = arr($pengkajian['memori']); ?>
<?php $tingkat_konsentrasi_arr = arr($pengkajian['konsentrasi_berhitung']); ?>
<?php $kemampuan_penilaian_arr = arr($pengkajian['kemampuan_penilaian']); ?>
<?php $daya_tilik_diri_arr = arr($pengkajian['daya_tilik_diri']); ?>

<div class="field-row">
            <div class="field-label">1. Penampilan</div>
            <div class="field-sep">:</div>
            <div class="field-value">
                <?= chkIn($penampilan_arr, 'tidak_rapi') ?> Tidak Rapi &nbsp;
                <?= chkIn($penampilan_arr, 'pakaian_tidak_sesuai') ?> Penggunaan Pakaian Tidak Sesuai &nbsp;
                <?= chkIn($penampilan_arr, 'berpakaian_tidak_biasa') ?> Cara Berpakaian Tidak Seperti Biasanya
            </div>
        </div>
  <div class="field-row">
    <div class="field-label">2. Pembicaraan</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chkIn($pembicaraan_arr, 'cepat') ?> Cepat &nbsp;
        <?= chkIn($pembicaraan_arr, 'keras') ?> Keras &nbsp;
        <?= chkIn($pembicaraan_arr, 'gagap') ?> Gagap &nbsp;
        <?= chkIn($pembicaraan_arr, 'inkoheren') ?> Inkoheren &nbsp;
        <?= chkIn($pembicaraan_arr, 'apatis') ?> Apatis &nbsp;
        <?= chkIn($pembicaraan_arr, 'lambat') ?> Lambat &nbsp;
        <?= chkIn($pembicaraan_arr, 'membisu') ?> Membisu &nbsp;
        <?= chkIn($pembicaraan_arr, 'tidak_memulai') ?> Tidak Mampu Memulai Pembicaraan
    </div>
</div>

<div class="field-row">
    <div class="field-label">3. Aktivitas Motorik</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chkIn($aktivitas_motorik_arr, 'lesu') ?> Lesu &nbsp;
        <?= chkIn($aktivitas_motorik_arr, 'tegang') ?> Tegang &nbsp;
        <?= chkIn($aktivitas_motorik_arr, 'gelisah') ?> Gelisah &nbsp;
        <?= chkIn($aktivitas_motorik_arr, 'agitasi') ?> Agitasi &nbsp;
        <?= chkIn($aktivitas_motorik_arr, 'tik') ?> TIK &nbsp;
        <?= chkIn($aktivitas_motorik_arr, 'grimasen') ?> Grimasen &nbsp;
        <?= chkIn($aktivitas_motorik_arr, 'tremor') ?> Tremor &nbsp;
        <?= chkIn($aktivitas_motorik_arr, 'kompulsif') ?> Kompulsif
    </div>
</div>

<div class="field-row">
    <div class="field-label">4. Alam Perasaan</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chkIn($alam_perasaan_arr, 'sedih') ?> Sedih &nbsp;
        <?= chkIn($alam_perasaan_arr, 'ketakutan') ?> Ketakutan &nbsp;
        <?= chkIn($alam_perasaan_arr, 'putus_asa') ?> Putus Asa &nbsp;
        <?= chkIn($alam_perasaan_arr, 'khawatir') ?> Khawatir &nbsp;
        <?= chkIn($alam_perasaan_arr, 'gembira_berlebihan') ?> Gembira Berlebihan
    </div>
</div>

<div class="field-row">
    <div class="field-label">6. Interaksi Selama Wawancara</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chkIn($interaksi_arr, 'bermusuhan') ?> Bermusuhan &nbsp;
        <?= chkIn($interaksi_arr, 'tidak_kooperatif') ?> Tidak Kooperatif &nbsp;
        <?= chkIn($interaksi_arr, 'mudah_tersinggung') ?> Mudah Tersinggung &nbsp;
        <?= chkIn($interaksi_arr, 'kontak_mata') ?> Kontak Mata Kurang &nbsp;
        <?= chkIn($interaksi_arr, 'defensif') ?> Defensif &nbsp;
        <?= chkIn($interaksi_arr, 'curiga') ?> Curiga
    </div>
</div>

<div class="field-row">
    <div class="field-label">7. Persepsi - Sensorik</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chkIn($persepsi_sensorik_arr, 'pendengaran') ?> Pendengaran &nbsp;
        <?= chkIn($persepsi_sensorik_arr, 'pengecapan') ?> Pengecapan &nbsp;
        <?= chkIn($persepsi_sensorik_arr, 'penglihatan') ?> Penglihatan &nbsp;
        <?= chkIn($persepsi_sensorik_arr, 'perabaan') ?> Perabaan &nbsp;
        <?= chkIn($persepsi_sensorik_arr, 'penghidu') ?> Penghidu &nbsp;
        <?= chkIn($persepsi_sensorik_arr, 'ilusi_ada') ?> Ilusi Ada &nbsp;
        <?= chkIn($persepsi_sensorik_arr, 'ilusi_tidak_ada') ?> Ilusi Tidak Ada
    </div>
</div>

<div class="field-row">
    <div class="field-label">8. Proses Pikir</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chkIn($proses_pikir_arr, 'sirkumtansial') ?> Sirkumtansial &nbsp;
        <?= chkIn($proses_pikir_arr, 'tangensial') ?> Tangensial &nbsp;
        <?= chkIn($proses_pikir_arr, 'kehilangan_asosiasi') ?> Kehilangan Asosiasi &nbsp;
        <?= chkIn($proses_pikir_arr, 'inkoheren') ?> Inkoheren &nbsp;
        <?= chkIn($proses_pikir_arr, 'flight_of_idea') ?> Flight of Idea &nbsp;
        <?= chkIn($proses_pikir_arr, 'blocking') ?> Blocking &nbsp;
        <?= chkIn($proses_pikir_arr, 'pengulangan_pembicaraan') ?> Pengulangan Pembicaraan
    </div>
</div>

<div class="field-row">
    <div class="field-label">9. Isi Pikir</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chkIn($isi_pikir_arr, 'obsesi') ?> Obsesi &nbsp;
        <?= chkIn($isi_pikir_arr, 'fobia') ?> Fobia &nbsp;
        <?= chkIn($isi_pikir_arr, 'hipokondria') ?> Hipokondria &nbsp;
        <?= chkIn($isi_pikir_arr, 'depersonalisasi') ?> Depersonalisasi &nbsp;
        <?= chkIn($isi_pikir_arr, 'ide_terkait') ?> Ide yang Terkait &nbsp;
        <?= chkIn($isi_pikir_arr, 'pikiran_magis') ?> Pikiran Magis &nbsp;
        <?= chkIn($isi_pikir_arr, 'waham') ?> Waham &nbsp;
        <?= chkIn($isi_pikir_arr, 'agama') ?> Agama &nbsp;
        <?= chkIn($isi_pikir_arr, 'somatik') ?> Somatik &nbsp;
        <?= chkIn($isi_pikir_arr, 'kebesaran') ?> Kebesaran &nbsp;
        <?= chkIn($isi_pikir_arr, 'curiga') ?> Curiga &nbsp;
        <?= chkIn($isi_pikir_arr, 'nihilistik') ?> Nihilistik &nbsp;
        <?= chkIn($isi_pikir_arr, 'sisip_pikir') ?> Sisip Pikir &nbsp;
        <?= chkIn($isi_pikir_arr, 'siar_pikir') ?> Siar Pikir &nbsp;
        <?= chkIn($isi_pikir_arr, 'kontrol_pikir') ?> Kontrol Pikir
    </div>
</div>

<div class="field-row">
    <div class="field-label">10. Tingkat Kesadaran</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chkIn($tingkat_kesadaran_arr, 'bingung') ?> Bingung &nbsp;
        <?= chkIn($tingkat_kesadaran_arr, 'sedasi') ?> Sedasi &nbsp;
        <?= chkIn($tingkat_kesadaran_arr, 'disorientasi_waktu') ?> Disorientasi Waktu &nbsp;
        <?= chkIn($tingkat_kesadaran_arr, 'disorientasi_orang') ?> Disorientasi Orang &nbsp;
        <?= chkIn($tingkat_kesadaran_arr, 'disorientasi_tempat') ?> Disorientasi Tempat
    </div>
</div>

<div class="field-row">
    <div class="field-label">11. Memori</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chkIn($memori_arr, 'gangguan_daya_ingat_jangka_panjang') ?> Gangguan Daya Ingat Jangka Panjang &nbsp;
        <?= chkIn($memori_arr, 'gangguan_daya_ingat_jangka_pendek') ?> Gangguan Daya Ingat Jangka Pendek &nbsp;
        <?= chkIn($memori_arr, 'gangguan_daya_ingat_saat_ini') ?> Gangguan Daya Ingat Saat Ini &nbsp;
        <?= chkIn($memori_arr, 'konfabulasi') ?> Konfabulasi
    </div>
</div>

<div class="field-row">
    <div class="field-label">12. Tingkat Konsentrasi dan Berhitung</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chkIn($tingkat_konsentrasi_arr, 'mudah_beralih') ?> Mudah Beralih &nbsp;
        <?= chkIn($tingkat_konsentrasi_arr, 'tidak_berkonsentrasi') ?> Tidak Mampu Berkonsentrasi &nbsp;
        <?= chkIn($tingkat_konsentrasi_arr, 'tidak_berhitung') ?> Tidak Mampu Berhitung Sederhana
    </div>
</div>

<div class="field-row">
    <div class="field-label">13. Kemampuan Penilaian</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chkIn($kemampuan_penilaian_arr, 'gangguan_ringan') ?> Gangguan Ringan &nbsp;
        <?= chkIn($kemampuan_penilaian_arr, 'gangguan_bermakna') ?> Gangguan Bermakna
    </div>
</div>

<div class="field-row">
    <div class="field-label">14. Daya Tilik Diri</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chkIn($daya_tilik_diri_arr, 'mengingkari_penyakit') ?> Mengingkari Penyakit yang Diderita &nbsp;
        <?= chkIn($daya_tilik_diri_arr, 'menyalahkan_diluar_diri') ?> Menyalahkan Hal-hal di Luar Dirinya
    </div>
</div>

=======

<?php
$fields = [
    'penampilan' => ['Tidak rapi','Penggunaan pakaian tidak sesuai','Cara berpakaian tidak seperti biasanya'],
    'pembicaraan' => ['Cepat','Keras','Gagap','Inkoheren','Apatis','Lambat','Membisu','Tidak mampu memulai pembicaraan'],
    'aktivitas_motorik' => ['Lesu','Tegang','Gelisah','Agitasi','TIK','Grimasen','Tremor','Kompulsif'],
    'alam_perasaan' => ['Sedih','Ketakutan','Putus asa','Khawatir','Gembira berlebihan'],
    'afek' => ['Datar','Tumpul','Tidak sesuai'],
    'interaksi' => ['Bermusuhan','Tidak kooperatif','Mudah tersinggung','Kontak mata','Defensif','Curiga'],
    'persepsi_sensorik' => ['Pendengaran','Pengecapan','Penglihatan','Perabaan','Penghidu','Ilusi'],
    'proses_pikir' => ['Sirkumtansial','Tangensial','Kehilangan asosiasi','Inkoheren','Flight of idea','Blocking','Pengulangan pembicaraan/perseverasi'],
    'isi_pikir' => ['Obsesi','Fobia','Hipokondria','Depersonalisasi','Ide yang terkait','Pikiran magis','Waham','Agama','Somatik','Kebesaran','Curiga','Nihilistik','Sisip Pikir','Siar Pikir','Kontrol Pikir'],
    'tingkat_kesadaran' => ['Bingung','Sedasi','Disorientasi waktu','Disorientasi orang','Disorientasi tempat'],
    'memori' => ['Gangguan daya ingat jangka panjang','Gangguan daya ingat jangka pendek','Gangguan daya ingat saat ini','Konfabulasi'],
    'tingkat_konsentrasi' => ['Mudah beralih','Tidak mampu berkonsentrasi','Tidak mampu berhitung sederhana'],
    'kemampuan_penilaian' => ['Gangguan ringan','Gangguan bermakna'],
    'daya_tilik_diri' => ['Mengingkari penyakit yang diderita','Menyalahkan hal-hal di luar dirinya']
];
?>

<?php foreach($fields as $key=>$options): ?>
    <div class="field-row mb-2">
        <div class="field-label" style="min-width:220px;"><strong><?= ucwords(str_replace('_',' ',$key)) ?></strong></div>
        <div class="field-sep">:</div>
        <div class="field-value">
            <?php
            foreach($options as $opt){
                echo (in_array($opt, explode(',', $jiwa[$key] ?? '')) ? '✔ '.$opt : '(   )'.$opt) . ' &nbsp; ';
            }
            ?>
        </div>
    </div>
    <div class="field-row mb-2">
        <div class="field-label">Penjelasan</div>
        <div class="field-sep">:</div>
        <div class="field-value"><?= p($pengkajian[$key.'_penjelasan'] ?? '') ?></div>
    </div>
<?php endforeach; ?>
>>>>>>> master
<h4>VII. Kebutuhan Persiapan Pulang</h4>
<h4>4. Spiritual</h4>

<!-- Makan -->
<div class="field-row d-flex gap-3 mb-2">
    <div class="field-label">1. Makan</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($pengkajian['persiapan_makan1'] ?? '') ?></div>
</div>

<!-- BAB/BAK -->
<div class="field-row d-flex gap-3 mb-2">
    <div class="field-label">2. BAB/BAK</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($pengkajian['bab1'] ?? '') ?></div>
</div>

<!-- Mandi -->
<div class="field-row d-flex gap-3 mb-2">
    <div class="field-label">3. Mandi</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($pengkajian['mandi1'] ?? '') ?></div>
</div>

<!-- Berpakian/berhias -->
<div class="field-row d-flex gap-3 mb-2">
    <div class="field-label">4. Berpakian/berhias</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($pengkajian['berpakian1'] ?? '') ?></div>
</div>

<!-- Istirahat/tidur -->
<div class="field-row d-flex gap-3 mb-2">
    <div class="field-label">5. Istirahat/tidur</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <strong>Tidur Siang</strong> <?= p($pengkajian['tidur_siang'] ?? '') ?> s/d <?= p($pengkajian['tidur_siang_sampai'] ?? '') ?>
    <strong>Tidur Malam</strong> <?= p($pengkajian['tidur_malam'] ?? '') ?> s/d <?= p($pengkajian['tidur_malam_sampai'] ?? '') ?>, <strong> Kegiatan sebelum/sesudah tidur:</strong> <?= p($pengkajian['tidur'] ?? '') ?>  </div>
</div>

<!-- Penggunaan obat -->
<div class="field-row d-flex gap-3 mb-2">
    <div class="field-label">6. Penggunaan obat</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($pengkajian['obat'] ?? '') ?></div>
</div>

<!-- Pemeliharaan kesehatan -->
<div class="field-row d-flex gap-3 mb-2">
    <div class="field-label">7. Pemeliharaan kesehatan</div>
    <div class="field-sep">:</div>
    <div class="field-value"><Strong>Perawatan lanjutan:</Strong>  <?= p($pengkajian['perawatanlanjutan'] ?? '') ?>, <strong>Perawatan lanjutan:</strong> <?= p($pengkajian['perawatanpendukung1'] ?? '') ?></div>
</div>

<!-- Kegiatan di dalam rumah -->
<div class="field-row d-flex gap-3 mb-2">
    <div class="field-label">8. Kegiatan di dalam rumah</div>
    <div class="field-sep">:</div>
    <div class="field-value"><strong>Mempersiapkan makanan:</strong>  <?= p($pengkajian['memasak1'] ?? '') ?>, <strong>Menjaga kerapian di rumah:</strong>  <?= p($pengkajian['menjaga_kerapian1'] ?? '') ?>, <strong>Mencuci pakaian:</strong>  <?= p($pengkajian['mencuci_pakaian1'] ?? '') ?>, <strong>Pengaturan keuangan:</strong>  <?= p($pengkajian['pengaturan_keuangan1'] ?? '') ?></div>
</div>

<!-- Kegiatan di luar rumah -->
<div class="field-row d-flex gap-3 mb-2">
    <div class="field-label">9. Kegiatan di luar rumah</div>
    <div class="field-sep">:</div>
    <div class="field-value"><strong>Belanja</strong> : <?= p($pengkajian['belanja1'] ?? '') ?>, <strong>Transportasi:</strong>  <?= p($pengkajian['transportasi1'] ?? '') ?>, <strong> Lain-lain:</strong> <?= p($pengkajian['lain_lain1'] ?? '') ?></div>
</div>
<div class="field-row mb-2">
    <div class="field-label">Penjelasan</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($pengkajian['penjelasan'] ?? '') ?></div>
</div>

<h4>VIII. Mekanisme Koping</h4>
<<<<<<< HEAD
<?php $psikososial_arr = arr($pengkajian['psikososial']); ?>

<div class="field-row">
    <div class="field-label">Mekanisme Koping</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chkIn($psikososial_arr, 'adaptif') ?> Adaptif &nbsp;
        <?= chkIn($psikososial_arr, 'maladaptif') ?> Maladaptif &nbsp;
        <?= chkIn($psikososial_arr, 'bicara_dengan_orang_lain') ?> Bicara dengan Orang Lain &nbsp;
        <?= chkIn($psikososial_arr, 'minum_alcohol') ?> Minum Alkohol &nbsp;
        <?= chkIn($psikososial_arr, 'mampu_menyelesaikan_masalah') ?> Mampu Menyelesaikan Masalah &nbsp;
        <?= chkIn($psikososial_arr, 'reaksi_lambat_berlebih') ?> Reaksi Lambat/Berlebih &nbsp;
        <?= chkIn($psikososial_arr, 'teknik_relaksasi') ?> Teknik Relaksasi &nbsp;
        <?= chkIn($psikososial_arr, 'bekerja_berlebihan') ?> Bekerja Berlebihan &nbsp;
        <?= chkIn($psikososial_arr, 'aktivitas_konstruktif') ?> Aktivitas Konstruktif &nbsp;
        <?= chkIn($psikososial_arr, 'menghindar') ?> Menghindar &nbsp;
        <?= chkIn($psikososial_arr, 'olahraga') ?> Olahraga &nbsp;
        <?= chkIn($psikososial_arr, 'mencederai_diri') ?> Mencederai Diri
    </div>
</div>



=======
<div class="field-row mb-2">
    <?php
    $koping_options = [
        'Adaptif','Maladaptif','Bicara dengan orang lain','Minum alcohol',
        'Mampu menyelesaikan masalah','Reaksi lambat/berlebih','Teknik relaksasi',
        'Bekerja berlebihan','Aktivitas Konstruktif','Menghindar','Olahraga','Mencederai diri'
    ];
    foreach($koping_options as $opt){
        $field = 'koping_'.strtolower(str_replace([' ','/'],['_','_'],$opt));
        echo (isset($pengkajian[$field]) && $pengkajian[$field]=='ya' ? '✔ ' : '(   )') . $opt . ' &nbsp; ';
    }
    ?>
</div>


>>>>>>> master
<h4>IX. Masalah Psikososial dan Lingkungan</h4>

<div class="field-row mb-2">
    <div class="field-label"><strong>Masalah dengan dukungan kelompok</strong></div>
    <div class="field-value"><?= p($pengkajian['dukungan_kelompok'] ?? '') ?></div>
</div>

<div class="field-row mb-2">
    <div class="field-label"><strong>Masalah dengan lingkungan</strong></div>
    <div class="field-value"><?= p($pengkajian['masalah_lingkungan'] ?? '') ?></div>
</div>

<div class="field-row mb-2">
    <div class="field-label"><strong>Masalah dengan pendidikan</strong></div>
    <div class="field-value"><?= p($pengkajian['masalah_pendidikan'] ?? '') ?></div>
</div>

<div class="field-row mb-2">
    <div class="field-label"><strong>Masalah dengan pekerjaan</strong></div>
    <div class="field-value"><?= p($pengkajian['masalah_pekerjaan'] ?? '') ?></div>
</div>

<div class="field-row mb-2">
    <div class="field-label"><strong>Masalah dengan perumahan</strong></div>
    <div class="field-value"><?= p($pengkajian['masalah_perumahan'] ?? '') ?></div>
</div>

<div class="field-row mb-2">
    <div class="field-label"><strong>Masalah dengan ekonomi</strong></div>
    <div class="field-value"><?= p($pengkajian['masalah_ekonomi'] ?? '') ?></div>
</div>

<div class="field-row mb-2">
    <div class="field-label"><strong>Masalah dengan pelayanan kesehatan</strong></div>
    <div class="field-value"><?= p($pengkajian['masalah_pelayanan_kesehatan'] ?? '') ?></div>
</div>

<div class="field-row mb-2">
    <div class="field-label"><strong>Masalah lain</strong></div>
    <div class="field-value"><?= p($pengkajian['masalah_lain'] ?? '') ?></div>
</div>

<<<<<<< HEAD
<h4>X. PENGETAHUAN KURANG TENTANG :</h4>
<?php $pengetahuan_arr = arr($pengkajian['pengetahuan']); ?>

<div class="field-row">
    <div class="field-label">Pengetahuan Kurang Tentang</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chkIn($pengetahuan_arr, 'penyakit_jiwa') ?> Penyakit Jiwa &nbsp;
        <?= chkIn($pengetahuan_arr, 'sistem_pendukung') ?> Sistem Pendukung &nbsp;
        <?= chkIn($pengetahuan_arr, 'faktor_presipitasi') ?> Faktor Presipitasi &nbsp;
        <?= chkIn($pengetahuan_arr, 'penyakit_fisik') ?> Penyakit Fisik &nbsp;
        <?= chkIn($pengetahuan_arr, 'koping') ?> Koping &nbsp;
        <?= chkIn($pengetahuan_arr, 'obat_obatan') ?> Obat-obatan &nbsp;
        <?= chkIn($pengetahuan_arr, 'lainnya') ?> Lainnya
    </div>
=======
<h4>X.	PENGETAHUAN KURANG TENTANG :</h4>
<div class="field-row mb-2">
    <?php
    $koping_options = [
        'Penyakit Jiwa','Sistem Pendukung','Faktor Presipitasi','Penyakit Fisik',
        'Koping','Obat-obatan','Lainnya'
    ];
    
    foreach($koping_options as $opt){
        // buat key field
        $field = 'koping_'.strtolower(str_replace([' ','/'],['_','_'],$opt));
        echo (isset($pengkajian[$field]) && $pengkajian[$field]=='ya' ? '✔ ' : '(   )') . $opt . ' &nbsp; ';
    }
    ?>
>>>>>>> master
</div>

<?php if(isset($pengkajian['koping_lainnya']) && $pengkajian['koping_lainnya']=='ya'): ?>
    <div class="field-row mb-2 d-flex">
        <div class="field-label" style="min-width:250px;"><strong>Detail Lainnya</strong></div>
        <div class="field-value"><?= p($pengkajian['koping_lainnya_text'] ?? '') ?></div>
    </div>
<?php endif; ?>

 
<h4>XI.	ASPEK MEDIS</h4>
<div class="field-row">
    <div class="field-label">Diagnosa Medis</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($lanjut['diagnosa_medis']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">Terapi Medik</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($lanjut['terapi_medik']) ?></div>
</div>
<h4>Data Fokus</h4>
<div class="field-row">
    <div class="field-label">Data Subjektif</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($lanjut['data_subjektif']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">Data Objektif</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($lanjut['data_objektif']) ?></div>
</div>


<h4>Analisa Data</h4>
<table class="data" style="border:1px solid #000;">
    <tr>
        <th>No</th>
        <th>Data Subjektif</th>
        <th>Data Objektif</th>
        <th>Masalah</th>
    </tr>
    <?php foreach($lanjut['analisa'] as $i => $row): ?>
    <tr>
        <td><?= $i+1 ?></td>
        <td><?= p($row['data_subjektif_analisa']) ?></td>
        <td><?= p($row['data_objektif_analisa']) ?></td>
        <td><?= p($row['masalah']) ?></td>
    </tr>
    <?php endforeach; ?>
</table>


<h4>XIV. Daftar Masalah Keperawatan</h4>
<div class="field-row">
    <div class="field-value"><?= p($lanjut['daftar_masalah_keperawatan']) ?></div>
</div>


<h4>Pohon Masalah</h4>
<div class="field-row">
    <div class="field-label">Efek</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($lanjut['efek']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">Cara Problem</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($lanjut['cara_problem']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">Etiologi</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($lanjut['etiologi']) ?></div>
</div>


      

<!-- ================================ -->
<!-- SECTION 6: CATATAN KEPERAWATAN -->
<!-- ================================ -->
 <div class="page-break"></div>
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