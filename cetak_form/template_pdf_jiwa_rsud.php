<?php
// Shortcut per section
$lp   = $sections['format_lp'] ?? [];
$pengkajian     = $sections['pengkajian'] ?? [];
$lanjut     = $sections['pengkajian_lanjut'] ?? [];
$lainnya       = $sections['lainnya'] ?? [];



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
<div style="margin-top:10px;">
    <strong>Jelaskan No. 1, 2, 3 :</strong> <?= p($pengkajian['penjelasan_trauma'] ?? '') ?>
</div>
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