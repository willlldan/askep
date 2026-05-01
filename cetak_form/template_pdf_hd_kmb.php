<?php
// Shortcut per section
$lp   = $sections['lp_ruanghd'] ?? [];
$format     = $sections['format_hermodalisa'] ?? [];
$pengkajian     = $sections['pengkajian'] ?? [];
$fisik     = $sections['pemeriksaan_fisik'] ?? [];
$kebutuhan     = $sections['pengkajian_kebutuhan'] ?? [];
$analisa     = $sections['analisa_data'] ?? [];
$catatan= $sections['catatan_keperawatan'] ?? [];



include 'template_pdf.php';
?>


<body>
    <div >

        <!-- HEADER -->
        <h1>Format Laporan Pendahuluan</h1>
        <h2>Ruang HD</h2>
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
 <h3 class="card-title"><strong>A. Konsep Dasar Penyakit (CKD)</strong></h3>

        <div class="field-row mb-2">
            <div class="field-label">Definisi</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($lp['definisi'] ?? '') ?></div>
        </div>

        <div class="field-row mb-2">
            <div class="field-label">Klasifikasi</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($lp['klasifikasi'] ?? '') ?></div>
        </div>

        <div class="field-row mb-2">
            <div class="field-label">Etiologi</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($lp['etiologi'] ?? '') ?></div>
        </div>

        <div class="field-row mb-2">
            <div class="field-label">Manifestasi Klinik</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($lp['manifestasi_klinik'] ?? '') ?></div>
        </div>

        <div class="field-row mb-2">
            <div class="field-label">Patofisiologi</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($lp['patofisiologi'] ?? '') ?></div>
        </div>

        <div class="field-row mb-2">
            <div class="field-label">Pemeriksaan Penunjang</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($lp['penunjang'] ?? '') ?></div>
        </div>

        <div class="field-row mb-2">
            <div class="field-label">Penatalaksanaan</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($lp['penatalaksanaan'] ?? '') ?></div>
        </div>

        <div class="field-row mb-2">
            <div class="field-label">Komplikasi</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($lp['komplikasi'] ?? '') ?></div>
        </div>
        <h3 class="card-title"><strong>B. Konsep Dasar Hemodialisa</strong></h3>

        <div class="field-row mb-2">
            <div class="field-label">Pengertian</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($lp['pengertian'] ?? '') ?></div>
        </div>

        <div class="field-row mb-2">
            <div class="field-label">Tujuan</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($lp['tujuan'] ?? '') ?></div>
        </div>

        <div class="field-row mb-2">
            <div class="field-label">Proses Hemodialisa</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($lp['proses_hemodialisa'] ?? '') ?></div>
        </div>

        <div class="field-row mb-2">
            <div class="field-label">Alasan Hemodialisa</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($lp['alasan_hemodialisa'] ?? '') ?></div>
        </div>

        <div class="field-row mb-2">
            <div class="field-label">Indikasi Hemodialisa</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($lp['indikasi_hemodialisa'] ?? '') ?></div>
        </div>

        <div class="field-row mb-2">
            <div class="field-label">Kontraindikasi Hemodialisa</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($lp['kontraindikasi_hemodialisa'] ?? '') ?></div>
        </div>

        <div class="field-row mb-2">
            <div class="field-label">Frekuensi Hemodialisa</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($lp['frekuensi_hemodialisa'] ?? '') ?></div>
        </div>

        <div class="field-row mb-2">
            <div class="field-label">Komplikasi</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($lp['komplikasi1'] ?? '') ?></div>
        </div>
      
<div class="page-break"></div>

        <!-- ================================ -->
        <!-- SECTION 2: RIWAYAT KEHAMILAN -->
        <!-- ================================ -->
          <!-- HEADER -->
        <h1>Laporan Kasus Hemodialisa</h1>
       
<div class="field-row mb-2">
    <div class="field-label">Nama Mahasiswa</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($format['nama_mahasiswa'] ?? '') ?></div>
</div>

<div class="field-row mb-2">
    <div class="field-label">NIM</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($format['nim'] ?? '') ?></div>
</div>

<div class="field-row mb-2">
    <div class="field-label">Kelompok</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($format['kelompok'] ?? '') ?></div>
</div>

<div class="field-row mb-2">
    <div class="field-label">Tempat Dinas</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($format['tempat_dinas'] ?? '') ?></div>
</div>
<hr>
<h3>A. Identitas Klien</h3>

<div class="field-row mb-2">
    <div class="field-label">Nama (inisial)</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($format['nama_klien'] ?? '') ?></div>
</div>

<div class="field-row mb-2">
    <div class="field-label">Umur</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($format['umur'] ?? '') ?></div>
</div>

<div class="field-row mb-2">
    <div class="field-label">Pekerjaan</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($format['pekerjaan'] ?? '') ?></div>
</div>

<div class="field-row mb-2">
    <div class="field-label">Agama</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($format['agama'] ?? '') ?></div>
</div>

<div class="field-row mb-2">
    <div class="field-label">Diagnosa Medis</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($format['diagnosa_medis'] ?? '') ?></div>
</div>

<div class="field-row mb-2">
    <div class="field-label">Tanggal Pertama HD</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($format['tgl_pertama_hd'] ?? '') ?></div>
</div>

<div class="field-row mb-2">
    <div class="field-label">HD ke-berapa</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($format['hd'] ?? '') ?></div>
</div>

<div class="field-row mb-2">
    <div class="field-label">Waktu HD</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        Tgl <?= p($format['tgl_operasi'] ?? '') ?> Pukul <?= p($format['pukul_mulai'] ?? '') ?> s/d Pukul <?= p($format['pukul_selesai'] ?? '') ?>
    </div>
</div>
<h3>B. Status Emosional Klien dan Keluarga</h3>
<div class="field-row mb-2">
    <div class="field-value"><?= p($format['status_emosional'] ?? '') ?></div>
</div>

<h3>C. Riwayat Komplikasi HD Sebelumnya</h3>
<div class="field-row mb-2">
    <div class="field-value"><?= p($format['riwayat_komplikasi'] ?? '') ?></div>
</div>
<h3 class="mt-5">D. Nilai Laboratorium Terakhir</h3>

<table class="data">
    <thead>
        <tr>
            <th>No</th>
            <th width="40%">Tanggal Pemeriksaan</th>
            <th width="30%">Nama Pemeriksaan</th>
            <th width="30%">Hasil</th>
            <th width="30%">Satuan</th>
            <th width="30%">Nilai Rujukan</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($format['lab'])): ?>
            <?php foreach ($format['lab'] as $index => $lab): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= p($lab['tanggal_pemeriksaan']) ?></td>
                    <td><?= p($lab['nama_pemeriksaan']) ?></td>
                    <td><?= p($lab['hasil']) ?></td>
                    <td><?= p($lab['satuan']) ?></td>
                    <td><?= p($lab['nilai_rujukkan']) ?></td>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="5" style="text-align:center">-</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
  
<h3>E. Persiapan</h3>

<div class="field-row mb-2">
    <div class="field-label">Lingkungan</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($format['lingkungan'] ?? '') ?></div>
</div>

<div class="field-row mb-2">
    <div class="field-label">Mesin HD</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($format['mesin_hd'] ?? '') ?></div>
</div>

<div class="field-row mb-2">
    <div class="field-label">Klien</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <div><strong>Pengukuran Berat Badan:</strong> <?= p($format['pengukuran'] ?? '') ?> kg</div>
        <div><strong>Pengukuran TTV</strong></div>
        <div>TD: <?= p($format['tekanandarah'] ?? '') ?> mmHg</div>
        <div>N: <?= p($format['nadi'] ?? '') ?> x/m</div>
        <div>S: <?= p($format['suhu'] ?? '') ?> °C</div>
        <div>RR: <?= p($format['rr'] ?? '') ?> x/m</div>
    </div>
</div>

<div class="field-row mb-2">
    <div class="field-label">Alat</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($format['alat'] ?? '') ?></div>
</div>
<h3>F. Prosedur Kerja</h3>
<p>(Tuliskan suatu tindakan yang diberikan mulai dari persiapan sampai selesai melakukan HD)</p>
<div class="field-row mb-2">
    <div class="field-label">1. Pre HD</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($format['pre'] ?? '') ?>
    </div>
</div>

<div class="field-row mb-2">
    <div class="field-label">2. Pos HD</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($format['pos'] ?? '') ?></div>
</div>

<div class="field-row mb-2">
    <div class="field-label">3. Observasi</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($format['observasi'] ?? '') ?></textarea>
    </div>
</div>

<div class="field-row mb-2">
    <div class="field-label">4. Respon terhadap tindakan HD</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($format['respon'] ?? '') ?>
    </div>
</div>

<h4 class="mt-5">5. Hasil yang diperoleh</h4>

<table class="data">
    <thead>
        <tr>
            <th>No</th>
            <th width="40%">Jam</th>
            <th width="30%">TD</th>
            <th width="30%">Nadi</th>
            <th width="30%">Qb</th>
            <th width="30%">TMP</th>
            <th width="30%">Tek. A</th>
            <th width="30%">Tek. V</th>
            <th width="30%">Hp</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($format['pemeriksaan'])): ?>
            <?php foreach ($format['pemeriksaan'] as $index => $pemeriksaan): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= p($pemeriksaan['jam']) ?></td>
                    <td><?= p($pemeriksaan['td']) ?></td>
                    <td><?= p($pemeriksaan['nadi']) ?></td>
                    <td><?= p($pemeriksaan['qb']) ?></td>
                    <td><?= p($pemeriksaan['tmp']) ?></td>
                    <td><?= p($pemeriksaan['teka']) ?></td>
                    <td><?= p($pemeriksaan['tekv']) ?></td>
                    <td><?= p($pemeriksaan['hp']) ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="5" style="text-align:center">-</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>


<h3>G. Health Education (HE) yang diberikan sebelum meninggalkan HD:</h3>

<div class="field-row mb-2">
    <div class="field-label">Health Education (HE) yang diberikan sebelum meninggalkan HD</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($format['health_education'] ?? '') ?></div>
</div>

 <div class="page-break"></div>
<!-- ================================ -->
<!-- SECTION E: ANALISA DATA -->
<!-- ================================ -->

<h1>Format Resume Keperawatan</h1>
<h2>Ruang Perawatan HD</h2>

            <h2>A. PENGKAJIAN</h2>

<h3>1. Identitas</h3>

<h4>a. Klien</h4>
<table class="header-table" style="border:1px solid #000; width:100%; border-collapse:collapse;">
    <tr>
        <td width="25%"><strong>Nama</strong></td>
        <td width="2%">:</td>
        <td><?= p($pengkajian['nama_klien'] ?? '') ?></td>
        <td width="25%"><strong>Tempat/Tgl Lahir/Umur</strong></td>
        <td width="2%">:</td>
        <td><?= p($pengkajian['ttl_umur'] ?? '') ?></td>
    </tr>
    <tr>
        <td><strong>Jenis Kelamin</strong></td>
        <td>:</td>
        <td><?= p($pengkajian['jenis_kelamin'] ?? '') ?></td>
        <td><strong>Status Perkawinan</strong></td>
        <td>:</td>
        <td><?= p($pengkajian['status_perkawinan'] ?? '') ?></td>
    </tr>
    <tr>
        <td><strong>Agama</strong></td>
        <td>:</td>
        <td><?= p($pengkajian['agama'] ?? '') ?></td>
        <td><strong>Pendidikan</strong></td>
        <td>:</td>
        <td><?= p($pengkajian['pendidikan'] ?? '') ?></td>
    </tr>
    <tr>
        <td><strong>Pekerjaan</strong></td>
        <td>:</td>
        <td><?= p($pengkajian['pekerjaan'] ?? '') ?></td>
        <td><strong>Alamat</strong></td>
        <td>:</td>
        <td><?= p($pengkajian['alamat'] ?? '') ?></td>
    </tr>
    <tr>
        <td><strong>Tanggal Masuk RS</strong></td>
        <td>:</td>
        <td><?= p($pengkajian['tgl_masuk_rs'] ?? '') ?></td>
        <td><strong>Tanggal Pengkajian</strong></td>
        <td>:</td>
        <td><?= p($pengkajian['tgl_pengkajian1'] ?? '') ?></td>
    </tr>
    <tr>
        <td><strong>Diagnosa Medik</strong></td>
        <td>:</td>
        <td><?= p($pengkajian['diagnosa_medik'] ?? '') ?></td>
        <td><strong>Golongan Darah</strong></td>
        <td>:</td>
        <td><?= p($pengkajian['golongan_darah'] ?? '') ?></td>
    </tr>
    <tr>
        <td><strong>No. Registrasi</strong></td>
        <td>:</td>
        <td><?= p($pengkajian['no_registrasi'] ?? '') ?></td>
        <td><strong>Ruangan</strong></td>
        <td>:</td>
        <td><?= p($pengkajian['ruangan'] ?? '') ?></td>
    </tr>
</table>

<h4>b. Identitas Penanggung</h4>
<table class="header-table" style="border:1px solid #000; width:100%; border-collapse:collapse;">
    <tr>
        <td width="25%"><strong>Nama (Inisial)</strong></td>
        <td width="2%">:</td>
        <td><?= p($pengkajian['nama_klienpj'] ?? '') ?></td>
        <td width="25%"><strong>Tempat/Tgl Lahir/Umur</strong></td>
        <td width="2%">:</td>
        <td><?= p($pengkajian['ttl_umurpj'] ?? '') ?></td>
    </tr>
    <tr>
        <td><strong>Jenis Kelamin</strong></td>
        <td>:</td>
        <td><?= p($pengkajian['jenis_kelaminpj'] ?? '') ?></td>
        <td><strong>Hubungan dengan Klien</strong></td>
        <td>:</td>
        <td><?= p($pengkajian['hubungan_klien'] ?? '') ?></td>
    </tr>
    <tr>
        <td><strong>Agama</strong></td>
        <td>:</td>
        <td><?= p($pengkajian['agamapj'] ?? '') ?></td>
        <td><strong>Pendidikan</strong></td>
        <td>:</td>
        <td><?= p($pengkajian['pendidikanpj'] ?? '') ?></td>
    </tr>
    <tr>
        <td><strong>Pekerjaan</strong></td>
        <td>:</td>
        <td><?= p($pengkajian['pekerjaanpj'] ?? '') ?></td>
        <td><strong>Alamat</strong></td>
        <td>:</td>
        <td><?= p($pengkajian['alamatpj'] ?? '') ?></td>
    </tr>
</table>
<h3>2. Keadaan Umum</h3>

<h4>a. Tanda Vital</h4>
<div class="field-row">
    <div class="field-label"><strong>Pre HD</strong></div>
</div>
<div class="field-row">
    <div class="field-label">Nadi</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($pengkajian['nadi_prehd'] ?? '') ?> /menit</div>
</div>
<div class="field-row">
    <div class="field-label">Pernafasan</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($pengkajian['pernafasan_prehd'] ?? '') ?> x/menit</div>
</div>
<div class="field-row">
    <div class="field-label">TD (Tekanan Darah)</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($pengkajian['td_prehd'] ?? '') ?> mmHg</div>
</div>
<div class="field-row">
    <div class="field-label">Suhu</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($pengkajian['suhu_prehd'] ?? '') ?> °C</div>
</div>

<div class="field-row mt-2">
    <div class="field-label"><strong>Post HD</strong></div>
</div>
<div class="field-row">
    <div class="field-label">Nadi</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($pengkajian['nadi'] ?? '') ?> /menit</div>
</div>
<div class="field-row">
    <div class="field-label">Pernafasan</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($pengkajian['pernafasan'] ?? '') ?> x/menit</div>
</div>
<div class="field-row">
    <div class="field-label">TD (Tekanan Darah)</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($pengkajian['td'] ?? '') ?> mmHg</div>
</div>
<div class="field-row">
    <div class="field-label">Suhu</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($pengkajian['suhu'] ?? '') ?> °C</div>
</div>

<h4>b. Kesadaran</h4>
<div class="field-row">
    <div class="field-label">Glasgow Coma Scale (GCS)</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        M: <?= p($pengkajian['m'] ?? '') ?>, 
        V: <?= p($pengkajian['v'] ?? '') ?>, 
        E: <?= p($pengkajian['e'] ?? '') ?>
    </div>
</div>
<div class="field-row">
    <div class="field-label">Tingkat Kesadaran</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($pengkajian['kesadaran'] ?? '') ?></div>
</div>

<h4>c. Berat Badan (BB)</h4>
<div class="field-row">
    <div class="field-label">BB HD sebelumnya</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($pengkajian['bb_hd'] ?? '') ?> kg</div>
</div>
<div class="field-row">
    <div class="field-label">BB Pre HD (sebelum HD)</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($pengkajian['bb_prehd'] ?? '') ?> kg</div>
</div>
<div class="field-row">
    <div class="field-label">BB Post HD (setelah HD)</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($pengkajian['bb_posthd'] ?? '') ?> kg</div>
</div>
<div class="field-row">
    <div class="field-label">BB Kering</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($pengkajian['bbkering'] ?? '') ?> kg</div>
</div>
<div class="field-row">
    <div class="field-label">Kenaikan BB</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($pengkajian['kenaikanbb'] ?? '') ?> kg</div>
</div>
<h3>Riwayat Kesehatan</h3>

<div class="field-row">
    <div class="field-label"><strong>a. Alasan Masuk Rumah Sakit</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($pengkajian['alasan_masuk_rs'] ?? '') ?></div>
</div>

<div class="field-row">
    <div class="field-label"><strong>b. Keluhan Utama</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($pengkajian['keluhan_utama'] ?? '') ?></div>
</div>

<div class="field-row">
    <div class="field-label"><strong>c. Riwayat Keluhan Utama</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($pengkajian['riwayat_keluhan_utama'] ?? '') ?></div>
</div>

<div class="field-row">
    <div class="field-label"><strong>d. Riwayat Kesehatan yang Lalu</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($pengkajian['riwayat_kesehatan_lalu'] ?? '') ?></div>
</div>

<div class="field-row">
    <div class="field-label"><strong>e. Riwayat Kesehatan Keluarga</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($pengkajian['riwayat_kesehatan_keluarga'] ?? '') ?></div>
</div>

<div class="field-row">
    <div class="field-label"><strong>f. Genogram</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?php if(!empty($pengkajian['link_genogram'])): ?>
            <a href="<?= p($pengkajian['link_genogram']) ?>" target="_blank">Lihat Genogram</a>
        <?php else: ?>
            Belum ada genogram
        <?php endif; ?>
    </div>
</div>

<!-- belum disimpan masih ada yang eror -->
<h4>IV. Pemeriksaan Fisik</h4>

<div class="field-row">
    <div class="field-label"><strong>a. Kepala</strong></div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Bentuk Kepala</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['bentuk_kepala'] ?? '') ?>
    </div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Nyeri Tekan</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['nyeri_tekan_dada'] ?? '') ?>
    </div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Benjolan</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['benjolan_dada'] ?? '') ?>
    </div>
</div>
<!-- Rambut -->
<div class="field-row">
    <div class="field-label"><strong>b. Rambut</strong></div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Penyebaran Merata</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['penyebaran_merata'] ?? '') ?>
    </div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Warna</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['warna_rambut'] ?? '') ?>
    </div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Mudah Dicabut</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['rambut_dicabut'] ?? '') ?>
    </div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Kelainan</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['kelainan_rambut'] ?? '') ?>
    </div>
</div>

<!-- Wajah -->
<div class="field-row">
    <div class="field-label"><strong>c. Wajah</strong></div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Ekspresi Wajah</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['ekspresi_wajah'] ?? '') ?>
    </div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Kesimetrisan Wajah</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['simetris_wajah'] ?? '') ?>
    </div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Terdapat Udema</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['udema_wajah'] ?? '') ?>
    </div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Kelainan</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['kelainan_wajah'] ?? '') ?>
    </div>
</div>
<div class="field-row">
    <div class="field-label"><strong>d. Mata</strong></div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Penglihatan</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['penglihatan'] ?? '') ?>
    </div>
</div>

<div class="field-row">
    <div class="field-label "><strong>Visus Kanan</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['kanan'] ?? '') ?>
    </div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Visus Kiri</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['kiri'] ?? '') ?>
    </div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Lapang Pandang</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['lapang_pandang'] ?? '') ?>
    </div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Keadaan Mata</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['keadaan_mata'] ?? '') ?>
    </div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Konjungtiva</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['konjungtiva'] ?? '') ?>
    </div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Lesi</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['lesi_mata'] ?? '') ?>
    </div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Sclera</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['sclera'] ?? '') ?>
    </div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Reaksi Pupil</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['pupil'] ?? '') ?>
    </div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Bola Mata</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['bola_mata'] ?? '') ?>
    </div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Kelainan</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['kelainan_mata'] ?? '') ?>
    </div>
</div>
<div class="field-row">
    <div class="field-label"><strong>f. Hidung</strong></div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Membedakan Bau</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['bau'] ?? '') ?>
    </div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Sekresi</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['sekresi'] ?? '') ?>
    </div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Warna</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['warna_hidung'] ?? '') ?>
    </div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Mukosa</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['mukosa_hidung'] ?? '') ?>
    </div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Pembengkakan</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['pembengkakan'] ?? '') ?>
    </div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Pernafasan Cuping</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['cuping_hidung'] ?? '') ?>
    </div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Kelainan</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['kelainan_hidung'] ?? '') ?>
    </div>
</div>
<div class="field-row">
    <div class="field-label"><strong>g. Mulut</strong></div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Bibir</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['bibir'] ?? '') ?> Warna
    </div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Simetris</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['simetris'] ?? '') ?>
    </div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Kelembaban</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['kelembaban'] ?? '') ?>
    </div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Gigi</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        Caries: <?= p($fisik['caries'] ?? '') ?>, Jumlah: <?= p($fisik['jumlah_gigi'] ?? '') ?>
    </div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Warna</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['warna_gigi'] ?? '') ?>
    </div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Gigi Palsu</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['frekuensi'] ?? '') ?> buah, Letak:  <?= p($fisik['letak'] ?? '') ?>
    </div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Lidah</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['lidah'] ?? '') ?>
    </div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Lesi</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['lesi_lidah'] ?? '') ?>
    </div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Sensasi Rasa</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        Panas/Dingin: <?= p($fisik['panas_dingin'] ?? '') ?>, Asam/Pahit: <?= p($fisik['asampahit'] ?? '') ?>, Manis: <?= p($fisik['manis'] ?? '') ?>
    </div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Refleks Mengunyah</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['refleks'] ?? '') ?>
    </div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Pembesaran Tonsil</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['tonsil'] ?? '') ?>
    </div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Bau Mulut</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['bau_mulut1'] ?? '') ?>
    </div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Sekret</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['caries1'] ?? '') ?>, Warna: <?= p($fisik['warna'] ?? '') ?>
    </div>
</div>
<div class="field-row">
    <div class="field-label"><strong>h. Leher</strong></div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Bentuk Simetris</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['leher_simetris'] ?? '') ?>
    </div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Kelenjar Membesar</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['kelenjar'] ?? '') ?>
    </div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Peninggian JVP</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['jvp'] ?? '') ?>
    </div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Refleks Menelan</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['refleks_menelan'] ?? '') ?>
    </div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Kelainan</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['kelainan_leher'] ?? '') ?>
    </div>
</div>
<div class="field-row">
    <div class="field-label"><strong>i. Paru-Paru</strong></div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Frekuensi Nafas</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['frekuensi'] ?? '') ?>, <?= p($fisik['teratur_nafas1'] ?? '') ?>
    </div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Irama Pernafasan</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['irama_nafas'] ?? '') ?>
    </div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Kesukaran Bernafas</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['sesak_nafas'] ?? '') ?>
    </div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Taktil Fremitus</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['taktil_fremitus'] ?? '') ?>
    </div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Bunyi Perkusi Paru</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['perkusi_paru'] ?? '') ?>
    </div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Suara Nafas</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['teratur_nafas'] ?? '') ?>, Uraikan: <?= p($fisik['frekuensi_nafas'] ?? '') ?>
    </div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Bunyi Nafas Abnormal</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['bunyi_abnormal'] ?? '') ?>, <?= p($fisik['abnormal'] ?? '') ?>
    </div>
</div>
<div class="field-row">
    <div class="field-label"><strong>j. Jantung</strong></div>
</div>

<div class="field-row">
    <div class="field-label"><strong>S1</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['s1_jantung'] ?? '') ?>
    </div>
</div>

<div class="field-row">
    <div class="field-label"><strong>S2</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['s2_jantung'] ?? '') ?>
    </div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Bunyi Teratur</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['bunyi_jantung'] ?? '') ?>
    </div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Bunyi Tambahan</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['bunyi_tambahan'] ?? '') ?>
    </div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Pulsasi Jantung</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['pulsasi_jantung'] ?? '') ?>
    </div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Irama</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['irama_jantung'] ?? '') ?>
    </div>
</div>
<div class="field-row">
    <div class="field-label"><strong>k. Abdomen</strong></div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Bentuk</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['bentuk_abdomen1'] ?? '') ?>
    </div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Keadaan</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['keadaan_abdomen1'] ?? '') ?>
    </div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Bising Usus</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['bising_usus'] ?? '') ?>, <?= p($fisik['bising_usus'] ?? '') ?> Kali
    </div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Benjolan</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['benjolan_abdomen'] ?? '') ?>, <?= p($fisik['letak1'] ?? '') ?> Letak
    </div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Nyeri Tekan</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['nyeri_abdomen'] ?? '') ?>,  <?= p($fisik['frekuensi_tekan'] ?? '') ?> Letak
    </div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Perkusi</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['perkusi_abdomen'] ?? '') ?>
    </div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Kelainan</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['kelainan_abdomen'] ?? '') ?>
    </div>
</div>
<div class="field-row">
    <div class="field-label"><strong>m. Genetalia</strong></div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Bentuk</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['bentuk_genetalia'] ?? '') ?>
    </div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Radang</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['radang_genetalia'] ?? '') ?>
    </div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Sekret</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['sekret_genetalia'] ?? '') ?>
    </div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Pembengkakan Skrotum</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['skrotum_bengkak'] ?? '') ?>
    </div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Rektum</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['rektum_benjolan'] ?? '') ?>
    </div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Lesi</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['lesi_genetalia'] ?? '') ?>
    </div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Kelainan</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['kelainan_genetalia'] ?? '') ?>
    </div>
</div>
<div class="field-row">
    <div class="field-label"><strong>n. Ekstremitas Atas</strong></div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Bentuk Simetris</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['atas_simetris'] ?? '') ?>
    </div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Sensasi Halus</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['sensasi_halus'] ?? '') ?>
    </div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Sensasi Tajam</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['sensasi_tajam'] ?? '') ?>
    </div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Sensasi Panas</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['sensasi_panas'] ?? '') ?>
    </div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Sensasi Dingin</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['sensasi_dingin'] ?? '') ?>
    </div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Gerakan ROM</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['rom_atas'] ?? '') ?>
    </div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Refleks Bisep</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['refleks_bisep'] ?? '') ?>
    </div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Refleks Trisep</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['refleks_trisep'] ?? '') ?>
    </div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Pembengkakan</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['pembengkakan'] ?? '') ?>
    </div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Kelembaban</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['kelembaban'] ?? '') ?>
    </div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Temperatur</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['temperatur'] ?? '') ?>
    </div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Kekuatan Otot Tangan</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        Kanan: <?= p($fisik['kanan1'] ?? '') ?>, Kiri: <?= p($fisik['kiri1'] ?? '') ?>
    </div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Bawah</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['kelainan_genetalia1'] ?? '') ?>
    </div>
</div>
<div class="field-row">
    <div class="field-label"><strong>n. Ekstremitas Bawah</strong></div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Bentuk Simetris</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['bawah_simetris'] ?? '') ?>
    </div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Sensasi Halus</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['sensasi_bawah'] ?? '') ?>
    </div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Sensasi Tajam</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['bawah_tajam'] ?? '') ?>
    </div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Sensasi Panas</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['sensasi_panasb'] ?? '') ?>
    </div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Sensasi Dingin</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['sensasi_dinginb'] ?? '') ?>
    </div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Gerakan ROM</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['rom_bawah'] ?? '') ?>
    </div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Refleks Babinski</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['refleks_babinski1'] ?? '') ?>
    </div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Pembengkakan</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['pembengkakan2'] ?? '') ?>
    </div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Varises</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['varises1'] ?? '') ?>
    </div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Kelembaban</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['kelembaban3'] ?? '') ?>
    </div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Temperatur</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['temperaturb'] ?? '') ?>
    </div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Kekuatan Otot Kaki</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        Kanan: <?= p($fisik['kanan'] ?? '') ?>, Kiri: <?= p($fisik['kiri'] ?? '') ?>
    </div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Bawah</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['kelainan_genetalia2'] ?? '') ?>
    </div>
</div>
<div class="field-row">
    <div class="field-label"><strong>o. Kulit</strong></div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Warna</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['warna_kulit'] ?? '') ?>
    </div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Turgor</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['turgor_kulit'] ?? '') ?>
    </div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Kelembaban</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['kelembaban2'] ?? '') ?>
    </div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Edema</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['edema_kulit'] ?? '') ?>, Pada Daerah <?= p($fisik['pada_daerah'] ?? '') ?>
    </div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Luka</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['luka_kulit'] ?? '') ?>, Pada Daerah <?= p($fisik['pada_daerah1'] ?? '') ?>
    </div>
</div>
<div class="field-row">
    <div class="field-label"><strong>Karakteristik Luka</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['karakteristik_luka'] ?? '') ?>
    </div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Tekstur</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['tekstur_kulit'] ?? '') ?>
    </div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Kelainan</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['kelainan_kulit'] ?? '') ?>
    </div>
</div>
<div class="field-row">
    <div class="field-label"><strong>p. Kuku</strong></div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Clubbing Finger</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['clubbing_finger'] ?? '') ?>
    </div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Capillary Refill Time</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['capillary_refill_time'] ?? '') ?>
    </div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Keadaan</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['keadaan_kuku'] ?? '') ?>
    </div>
</div>
<div class="field-row">
    <div class="field-label"><strong>q. Status Neurologi</strong></div>
</div>

<div class="field-row">
    <div class="field-label"><strong>1) Saraf-saraf Kranial</strong></div>
</div>

<div class="field-row">
    <div class="field-label"><strong>a) Nervus I (Olfactorius) - Penciuman</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['nervus1_penciuman']) ?>
    </div>
</div>

<div class="field-row">
    <div class="field-label"><strong>b) Nervus II (Opticus) - Penglihatan</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['nervus2_penglihatan']) ?>
    </div>
</div>

<div class="field-row">
    <div class="field-label"><strong>c) Nervus III, IV, VI (Oculomotorius, Trochlearis, Abducens)</strong></div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Konstriksi Pupil</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['konstriksi_pupil']) ?>
    </div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Gerakan Kelopak Mata</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['gerakan_kelopak']) ?>
    </div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Pergerakan Bola Mata</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['gerakan_bola_mata']) ?>
    </div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Pergerakan Mata ke Bawah & Dalam</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['gerakan_mata_bawah']) ?>
    </div>
</div>

<!-- Nervus V (Trigeminus) -->
<div class="field-row">
    <div class="field-label"><strong>d) Nervus V (Trigeminus)</strong></div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Refleks Dagu</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['refleks_dagu']) ?>
    </div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Refleks Cornea</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['refleks_cornea']) ?>
    </div>
</div>

<!-- Nervus VII (Facialis) -->
<div class="field-row">
    <div class="field-label"><strong>e) Nervus VII (Facialis)</strong></div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Pengecapan 2/3 Lidah Bagian Depan</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['pengecapan_depan']) ?>
    </div>
</div>

<!-- Nervus VIII (Acusticus) -->
<div class="field-row">
    <div class="field-label"><strong>f) Nervus VIII (Acusticus)</strong></div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Fungsi Pendengaran</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['fungsi_pendengaran']) ?>
    </div>
</div>

<!-- Nervus IX & X (Glossopharyngeus dan Vagus) -->
<div class="field-row">
    <div class="field-label"><strong>g) Nervus IX & X (Glossopharyngeus dan Vagus)</strong></div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Refleks Menelan</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['refleks_menelan']) ?>
    </div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Refleks Muntah</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['refleks_muntah']) ?>
    </div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Pengecapan 1/3 Lidah Belakang</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['pengecapan_belakang']) ?>
    </div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Suara</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['suara_pasien']) ?>
    </div>
</div>

<!-- Nervus XI (Assesorius) -->
<div class="field-row">
    <div class="field-label"><strong>h) Nervus XI (Assesorius)</strong></div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Memalingkan Kepala</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['gerakan_kepala']) ?>
    </div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Mengangkat Bahu</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['angkat_bahu']) ?>
    </div>
</div>

<!-- Nervus XII (Hypoglossus) -->
<div class="field-row">
    <div class="field-label"><strong>i) Nervus XII (Hypoglossus)</strong></div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Deviasi Lidah</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['deviasi_lidah']) ?>
    </div>
</div>

<!-- Tanda-tanda Peradangan Selaput Otak -->
<div class="field-row">
    <div class="field-label"><strong>2) Tanda-tanda Peradangan Selaput Otak</strong></div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Kaku Kuduk</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['kaku_kuduk']) ?>
    </div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Kernig Sign</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['kernig_sign']) ?>
    </div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Refleks Brudzinski</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['refleks_brudzinski']) ?>
    </div>
</div>

<h4>a. Pola Aktivitas</h4>
<div class="table-responsive">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Aktivitas</th>
                <th class="text-center">0</th>
                <th class="text-center">1</th>
                <th class="text-center">2</th>
                <th class="text-center">3</th>
                <th class="text-center">4</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $perawatan_fields = [
                'mandi'      => 'Mandi',
                'berpakaian' => 'Berpakaian / Berdandan',
                'mobilisasi' => 'Mobilisasi di TT',
                'pindah'     => 'Pindah',
                'ambulasi'   => 'Ambulasi',
                'makan'      => 'Makan / Minum',
            ];

            foreach ($perawatan_fields as $field => $label):
                $skor = $pengkajian[$field] ?? ''; // ambil skor dari pengkajian
            ?>
            <tr>
                <td><?= $label ?></td>
                <?php for ($i = 0; $i <= 4; $i++): ?>
                    <td class="text-center">
                        <?= ($skor == (string)$i) ? '✔' : '' ?>
                    </td>
                <?php endfor; ?>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <small>
        Skor 0 = Mandiri | Skor 1 = Dibantu sebagian | Skor 2 = Perlu bantuan orang lain |
        Skor 3 = Bantuan orang lain dan alat | Skor 4 = Tergantung
    </small>
</div>
<h4>b. Pola Kognitif dan Perceptual</h4>

<div class="field-row mb-2">
    <div class="field-label">1. Nyeri (kualitas, intensitas, durasi, skala nyeri, cara mengurangi nyeri)</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($kebutuhan['nyeri'] ?? '') ?></div>
</div>

<div class="field-row mb-2">
    <div class="field-label">2. Fungsi Panca Indra (penglihatan, pendengaran, pengecapan, penghidu, perasa) menggunakan alat bantu?</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($kebutuhan['panca_indra'] ?? '') ?></div>
</div>

<div class="field-row mb-2">
    <div class="field-label">3. Kemampuan Berbicara</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($kebutuhan['berbicara'] ?? '') ?></div>
</div>

<div class="field-row mb-2">
    <div class="field-label">4. Kemampuan Membaca</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($kebutuhan['membaca'] ?? '') ?></div>
</div>

<h3>Aktivitas Sehari-hari</h3>
<4>c. Pola Nutrisi</4>
<!-- Nutrisi -->
<table class="data" border="1" cellspacing="0" cellpadding="5" width="100%">
    <thead>
        <tr>
            <th>No</th>
            <th>Kondisi</th>
            <th>Sebelum Sakit</th>
            <th>Saat Ini</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>1</td>
            <td>Frekuensi Makan</td>
            <td><?= p($kebutuhan['frekuensi_makan_sebelum']) ?></td>
            <td><?= p($kebutuhan['frekuensi_makan_sekarang']) ?></td>
        </tr>
        <tr>
            <td>2</td>
            <td>Selera Makan</td>
            <td><?= p($kebutuhan['selera_makan_sebelum']) ?></td>
            <td><?= p($kebutuhan['selera_makan_sekarang']) ?></td>
        </tr>
        <tr>
            <td>3</td>
            <td>Menu Makanan</td>
            <td><?= p($kebutuhan['menu_makan_sebelum']) ?></td>
            <td><?= p($kebutuhan['menu_makan_sekarang']) ?></td>
        </tr>
        <tr>
            <td>4</td>
            <td>Ritual Saat Makan</td>
            <td><?= p($kebutuhan['ritual_makan_sebelum']) ?></td>
            <td><?= p($kebutuhan['ritual_makan_sekarang']) ?></td>
        </tr>
        <tr>
            <td>5</td>
            <td>Bantuan Makan Parenteral</td>
            <td><?= p($kebutuhan['bantuan_makan_sebelum']) ?></td>
            <td><?= p($kebutuhan['bantuan_makan_sekarang']) ?></td>
        </tr>
    </tbody>
</table>
<h4>d. Cairan</h4>
<!-- Cairan -->
<table class="data" border="1" cellspacing="0" cellpadding="5" width="100%" style="margin-top:10px;">
    <thead>
        <tr>
            <th>No</th>
            <th>Kondisi</th>
            <th>Sebelum Sakit</th>
            <th>Saat Ini</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>1</td>
            <td>Jenis Minuman</td>
            <td><?= p($kebutuhan['jenis_minum_sebelum']) ?></td>
            <td><?= p($kebutuhan['jenis_minum_sekarang']) ?></td>
        </tr>
        <tr>
            <td>2</td>
            <td>Jumlah Cairan</td>
            <td><?= p($kebutuhan['jumlah_cairan_sebelum']) ?></td>
            <td><?= p($kebutuhan['jumlah_cairan_sekarang']) ?></td>
        </tr>
        <tr>
            <td>3</td>
            <td>Cairan Parenteral</td>
            <td><?= p($kebutuhan['bantuan_cairan_sebelum']) ?></td>
            <td><?= p($kebutuhan['bantuan_cairan_sekarang']) ?></td>
        </tr>
    </tbody>
</table>
<h4>e. Pola Eliminasi BAB</h4>
<!-- Pola Eliminasi BAB -->
<table class="data" border="1" cellspacing="0" cellpadding="5" width="100%" style="margin-top:10px;">
    <thead>
        <tr>
            <th>No</th>
            <th>Kondisi</th>
            <th>Sebelum Sakit</th>
            <th>Saat Ini</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>1</td>
            <td>Frekuensi (Waktu)</td>
            <td><?= p($kebutuhan['bab_frekuensi_sebelum']) ?></td>
            <td><?= p($kebutuhan['bab_frekuensi_sekarang']) ?></td>
        </tr>
        <tr>
            <td>2</td>
            <td>Konsistensi</td>
            <td><?= p($kebutuhan['bab_konsistensi_sebelum']) ?></td>
            <td><?= p($kebutuhan['bab_konsistensi_sekarang']) ?></td>
        </tr>
        <tr>
            <td>3</td>
            <td>Warna</td>
            <td><?= p($kebutuhan['bab_warna_sebelum']) ?></td>
            <td><?= p($kebutuhan['bab_warna_sekarang']) ?></td>
        </tr>
        <tr>
            <td>4</td>
            <td>Bau</td>
            <td><?= p($kebutuhan['bab_bau_sebelum']) ?></td>
            <td><?= p($kebutuhan['bab_bau_sekarang']) ?></td>
        </tr>
        <tr>
            <td>5</td>
            <td>Kesulitan saat BAB</td>
            <td><?= p($kebutuhan['bab_kesulitan_sebelum']) ?></td>
            <td><?= p($kebutuhan['bab_kesulitan_sekarang']) ?></td>
        </tr>
        <tr>
            <td>6</td>
            <td>Penggunaan Obat Pencahar</td>
            <td><?= p($kebutuhan['bab_obat_sebelum']) ?></td>
            <td><?= p($kebutuhan['bab_obat_sekarang']) ?></td>
        </tr>
    </tbody>
</table>
<h4>f. Pola Eliminasi BAK</h4>
<!-- Pola Eliminasi BAK -->
<table class="data" border="1" cellspacing="0" cellpadding="5" width="100%" style="margin-top:10px;">
    <thead>
        <tr>
            <th>No</th>
            <th>Kondisi</th>
            <th>Sebelum Sakit</th>
            <th>Saat Ini</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>1</td>
            <td>Frekuensi (Waktu)</td>
            <td><?= p($kebutuhan['bak_frekuensi_sebelum']) ?></td>
            <td><?= p($kebutuhan['bak_frekuensi_sekarang']) ?></td>
        </tr>
        <tr>
            <td>2</td>
            <td>Warna</td>
            <td><?= p($kebutuhan['bak_warna_sebelum']) ?></td>
            <td><?= p($kebutuhan['bak_warna_sekarang']) ?></td>
        </tr>
        <tr>
            <td>3</td>
            <td>Bau</td>
            <td><?= p($kebutuhan['bak_bau_sebelum']) ?></td>
            <td><?= p($kebutuhan['bak_bau_sekarang']) ?></td>
        </tr>
        <tr>
            <td>4</td>
            <td>Kesulitan saat BAK</td>
            <td><?= p($kebutuhan['bak_kesulitan_sebelum']) ?></td>
            <td><?= p($kebutuhan['bak_kesulitan_sekarang']) ?></td>
        </tr>
        <tr>
            <td>5</td>
            <td>Penggunaan Obat Diuretik</td>
            <td><?= p($kebutuhan['bak_obat_sebelum']) ?></td>
            <td><?= p($kebutuhan['bak_obat_sekarang']) ?></td>
        </tr>
    </tbody>
</table>
<h4>g. Pola Tidur</h4>
<!-- Pola Tidur -->
<table class="data" border="1" cellspacing="0" cellpadding="5" width="100%" style="margin-top:10px;">
    <thead>
        <tr>
            <th>No</th>
            <th>Kondisi</th>
            <th>Sebelum Sakit</th>
            <th>Saat Ini</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>1</td>
            <td>Jam Tidur </td>
            <td>Siang <?= p($kebutuhan['tidur_siang_sebelum']) ?>,Malam <?= p($kebutuhan['tidur_malam_sebelum']) ?></td>
            <td>Siang<?= p($kebutuhan['tidur_siang_sekarang']) ?>,Malam<?= p($kebutuhan['tidur_malam_sekarang']) ?></td>
        </tr>
       
        <tr>
            <td>2</td>
            <td>Kesulitan Tidur</td>
            <td><?= p($kebutuhan['kesulitan_tidur_sebelum']) ?></td>
            <td><?= p($kebutuhan['kesulitan_tidur_sekarang']) ?></td>
        </tr>
        <tr>
            <td>3</td>
            <td>Kebiasaan Sebelum Tidur</td>
            <td><?= p($kebutuhan['kebiasaan_tidur_sebelum']) ?></td>
            <td><?= p($kebutuhan['kebiasaan_tidur_sekarang']) ?></td>
        </tr>
    </tbody>
</table>
<h4>n. Pola Personal Hygiene</h4>
<!-- Personal Hygiene -->
<table class="data" border="1" cellspacing="0" cellpadding="5" width="100%" style="margin-top:10px;">
    <thead>
        <tr>
            <th>No</th>
            <th>Kondisi</th>
            <th>Sebelum Sakit</th>
            <th>Saat Ini</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>1</td>
            <td>Mandi</td>
            <td>Frekuensi <?= p($kebutuhan['mandi_frekuensi_sebelum']) ?>,cara <?= p($kebutuhan['mandi_cara_sebelum']) ?> ,Tempat <?= p($kebutuhan['mandi_tempat_sebelum']) ?></td>
            <td>Frekuensi<?= p($kebutuhan['mandi_frekuensi_sekarang']) ?> ,cara <?= p($kebutuhan['mandi_cara_sekarang']) ?> ,Tempat <?= p($kebutuhan['mandi_tempat_sekarang']) ?></td>
        </tr>
        <tr>
            <td>2</td>
            <td>Cuci Rambut</td>
            <td>Frekuensi<?= p($kebutuhan['rambut_frekuensi_sebelum']) ?> ,cara <?= p($kebutuhan['rambut_cara_sebelum']) ?></td>
            <td>Frekuensi<?= p($kebutuhan['rambut_frekuensi_sekarang']) ?> ,cara <?= p($kebutuhan['rambut_cara_sekarang']) ?></td>
        </tr>
        <tr>
            <td>3</td>
            <td>Gunting Kuku</td>
            <td>Frekuensi<?= p($kebutuhan['kuku_frekuensi_sebelum']) ?> ,cara <?= p($kebutuhan['kuku_cara_sebelum']) ?></td>
            <td>Frekuensi<?= p($kebutuhan['kuku_frekuensi_sekarang']) ?> ,cara <?= p($kebutuhan['kuku_cara_sekarang']) ?></td>
        </tr>
        <tr>
            <td>4</td>
            <td>Gosok Gigi</td>
            <td>Frekuensi<?= p($kebutuhan['gigi_frekuensi_sebelum']) ?> ,cara <?= p($kebutuhan['gigi_cara_sebelum']) ?></td>
            <td>Frekuensi<?= p($kebutuhan['gigi_frekuensi_sekarang']) ?> ,cara <?= p($kebutuhan['gigi_cara_sekarang']) ?></td>
        </tr>
    </tbody>
</table>
<h4>c. Data Penunjang</h4>
<div class="field-row mb-2">
    <div class="field-label">Tanggal Pemeriksaan
</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($kebutuhan['tanggal_pemeriksaan'] ?? '') ?></div>
</div>
<h4>1) Laboratorium</h4>
<table class="data">
    <thead>
        <tr>
         <th>No</th>
            <th width="40%">Nama Pemeriksaan</th>
            <th width="30%">Hasil</th>
            <th width="30%">Satuan</th>
            <th width="30%">Nilai Rujukan</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($kebutuhan['diagnosa'])): ?>
            <?php foreach ($kebutuhan['diagnosa'] as $index => $diagnosa): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= p($diagnosa['pemeriksaan']) ?></td>
                    <td><?= p($diagnosa['hasil']) ?></td>
                    <td><?= p($diagnosa['satuan']) ?></td>
                    <td><?= p($diagnosa['nilai']) ?></td>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="5" style="text-align:center">-</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
      <div class="field-row mb-2">
    <div class="field-label">2) Radiologi (Tgl Pemeriksaan & Hasil)</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($kebutuhan['radiologi'] ?? '') ?></div>
</div>          
<div class="field-row mb-2">
    <div class="field-label">3) Lainnya (USG, CT Scan, dll)</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($kebutuhan['data_penunjang_lain'] ?? '') ?></div>
</div>


 <div class="page-break"></div>


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