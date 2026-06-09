<?php
// Shortcut per section
$lp   = $sections['lp_ruanghd'] ?? [];
$lp['intervensi'] = arr($lp['intervensi'] ?? []);
$format     = $sections['format_hermodalisa'] ?? [];
$pengkajian     = $sections['pengkajian'] ?? [];
$fisik     = $sections['pemeriksaan_fisik'] ?? [];
$kebutuhan     = $sections['pengkajian_kebutuhan'] ?? [];
$analisa     = $sections['analisa_data'] ?? [];
$catatan= $sections['catatan_keperawatan'] ?? [];
$kebutuhan_obat = arr($kebutuhan['obat'] ?? []);



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
                <td><strong>NIM</strong></td>
                <td>:</td>
                <td><?= p($submission['npm']) ?></td>
                <td><strong>RS/Ruangan</strong></td>
                <td>:</td>
                <td><?= p($submission['rs_ruangan']) ?></td>
            </tr> 
        </table>
    <h3 class="card-title"><strong>A. Konsep Dasar Penyakit (CKD)</strong></h3>

        <div class="field-row mb-2">
            <div class="field-label"> 1. Definisi CKD</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($lp['definisi'] ?? '') ?></div>
        </div>

        <div class="field-row mb-2">
            <div class="field-label">2. Etiologi CKD</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($lp['etiologi'] ?? '') ?></div>
        </div>

        <div class="field-row mb-2">
            <div class="field-label">3. Klasifikasi CKD</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($lp['klasifikasi'] ?? '') ?></div>
        </div>


        <div class="field-row mb-2">
            <div class="field-label">4. Manifestasi Klinik CKD</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($lp['manifestasi_klinik'] ?? '') ?></div>
        </div>

        <div class="field-row mb-2">
            <div class="field-label">5. Patofisiologi dan Pathway</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($lp['patofisiologi'] ?? '') ?></div>
        </div>

        <div class="field-row mb-2">
            <div class="field-label">6. Pemeriksaan Penunjang</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($lp['penunjang'] ?? '') ?></div>
        </div>

        <div class="field-row mb-2">
            <div class="field-label">7. Penatalaksanaan</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($lp['penatalaksanaan'] ?? '') ?></div>
        </div>
         <h3 class="card-title"><strong>B. Konsep Dasar Keperawatan </strong></h3>

        <div class="field-row mb-2">
            <div class="field-label"> 1. Pengkajian</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($lp['pengkajian'] ?? '') ?></div>
        </div>

        <div class="field-row mb-2">
            <div class="field-label">2. Diagnosa</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($lp['diagnosa'] ?? '') ?></div>
        </div>

 <div class="field">3. Intervensi Keperawatan</div>

        <table class="data">
            <thead>
                <tr>
                    <th width="40%">Diagnosa</th>
                    <th width="30%">Tujuan dan Kriteria Hasil</th>
                    <th width="30%">Intervensi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($lp['intervensi'])): ?>
                    <?php foreach ($lp['intervensi'] as $int): ?>
                        <tr>
                            <td><?= p($int['diagnosa']) ?></td>
                            <td><?= p($int['tujuan_kriteria']) ?></td>
                            <td><?= p($int['intervensi']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" style="text-align:center">-</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

       


        <div class="field-row mb-2">
            <div class="field-label">8. Komplikasi</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($lp['komplikasi'] ?? '') ?></div>
        </div>
        <div class="field-row mb-2">
            <div class="field-label">9. Daftar Pustaka</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($lp['daftarpustakackd'] ?? '') ?></div>
        </div> 

        <h3 class="card-title"><strong>C . Konsep Dasar Hemodialisa</strong></h3>

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
<p>(Narasikan komplikasi yang di alami pasien pada HD sebelumnya)</p>
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

<h3>A. PENGKAJIAN</h3>

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
    <div class="field-label">BB Kering</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($pengkajian['bbkering'] ?? '') ?> kg</div>
</div>
<div class="field-row">
    <div class="field-label">Kenaikan BB</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($pengkajian['kenaikanbb'] ?? '') ?> kg</div>
</div>
<h3>3. Riwayat Kesehatan</h3>

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
    <div class="field-label"><strong>c. Riwayat Kesehatan Sekarang</strong></div>
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
    <div class="field-value"><?= p($pengkajian['genogram'] ?? '') ?></div>
</div>
<!-- belum disimpan masih ada yang eror -->
<h3>4. Pemeriksaan Fisik</h3>
<h3>a. Kepala</h3>

<div class="field-row">
    <div class="field-label"><strong>Kepala</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['kepala'] ?? '') ?>
    </div>
</div>

<!-- Rambut -->
 <h3>b. Rambut</h3>


<div class="field-row">
    <div class="field-label"><strong>Rambut</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['rambut'] ?? '') ?>
    </div>
</div>

<!-- Wajah -->
 <h3>c. Wajah</h3>
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
<h3>d. Mata</h3>


<div class="field-row">
    <div class="field-label"><strong>Mata</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['mata'] ?? '') ?>
    </div>
</div>
<h3>e. Telinga</h3>


<!-- Kelainan -->
<div class="field-row d-flex gap-3 mb-2">
    <div class="field-label">Telinga</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['telinga'] ?? '') ?></div>
</div>

<h3>f. Hidung</h3>


<div class="field-row">
    <div class="field-label"><strong>Hidung</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['hidung'] ?? '') ?>
    </div>
</div>
<h3>g. Mulut</h3>

<div class="field-row">
    <div class="field-label"><strong>Mulut</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['mulut'] ?? '') ?>
    </div>
</div>

<h3>h. Leher</h3>

<div class="field-row">
    <div class="field-label"><strong>Leher</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['leher'] ?? '') ?>
    </div>
</div>
<h3>i. Dada</h3>
        <!-- DATA BIOLOGIS 2 -->
        
        <div class="field-row">
            <div class="field-label">Bentuk Dada</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($fisik['bentuk_dada']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Pengembangan Dada</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($fisik['pengembangan_dada']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Perbandingan Ukuran AP dengan Transversal</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($fisik['perbandingan_dada']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Penggunaan Otot Pernafasan Tambahan</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= chk($fisik['otot_pernafasan'], 'ya') ?> Ya &nbsp; <?= chk($fisik['otot_pernafasan'], 'tidak') ?> Tidak</div>
        </div>

<h3>j. Paru-Paru</h3>

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
<h3>k. Jantung</h3>

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
        <?= p($fisik['bunyi'] ?? '') ?>
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
<h3>l. Abdomen</h3>
<?php $bentuk_abd = arr($fisik['bentuk_abdomen']);
        $keadaan_abd = arr($fisik['keadaan_abdomen']); ?>
<div class="field-row">
            <div class="field-label">Bentuk</div>
            <div class="field-sep">:</div>
            <div class="field-value">
                <?= chkIn($bentuk_abd, 'datar') ?> Datar &nbsp;
                <?= chkIn($bentuk_abd, 'membuncit') ?> Membuncit &nbsp;
                <?= chkIn($bentuk_abd, 'cekung') ?> Cekung &nbsp;
                <?= chkIn($bentuk_abd, 'tegang') ?> Tegang
            </div>
        </div>
        <div class="field-row">
            <div class="field-label">Keadaan</div>
            <div class="field-sep">:</div>
            <div class="field-value">
                <?= chkIn($keadaan_abd, 'parut') ?> Parut &nbsp;
                <?= chkIn($keadaan_abd, 'lesi') ?> Lesi &nbsp;
                <?= chkIn($keadaan_abd, ' bercak_merah') ?> Bercak Merah
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
<h3>m. Genetalia</h3>


<div class="field-row">
    <div class="field-label"><strong>Genetalia</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['genetalia'] ?? '') ?>
    </div>
</div>

<h3>n. Ekstremitas Bawah</h3>

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
        <?= p($fisik['pembengkakan5'] ?? '') ?>
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
<h3>o. Kulit</h3>


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
<h3>p. Kuku</h3>


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
<h3>q. Status Neurologi</h3>
<div class="field-row">
    <div class="field-label"><strong>Status Neurologi</strong></div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($fisik['neurologi'] ?? '') ?>
    </div>
</div>


<h4>a. Pola Aktivitas</h4>

        <div style="margin: 4px 0 2px 0; font-size:9px;"><strong>Kemampuan Perawatan Diri</strong> (0=Mandiri, 1=Dibantu Sebagian, 2=Perlu Dibantu Orang Lain, 3=Perlu Bantuan Orang Lain &amp; Alat, 4=Tergantung/Tidak Mampu)</div>
        <table class="data">
            <thead>
                <tr>
                    <th>Aktivitas</th>
                    <th>0</th>
                    <th>1</th>
                    <th>2</th>
                    <th>3</th>
                    <th>4</th>
                </tr>
            </thead>
            <tbody>
                <?= actRow('Mandi', $kebutuhan['mandi']) ?>
                <?= actRow('Berpakaian/Berdandan', $kebutuhan['berpakaian']) ?>
                <?= actRow('Mobilisasi di TT', $kebutuhan['mobilisasi']) ?>
                <?= actRow('Pindah', $kebutuhan['pindah']) ?>
                <?= actRow('Ambulasi', $kebutuhan['ambulasi']) ?>
                <?= actRow('Makan/Minum', $kebutuhan['makan']) ?>
            </tbody>
        </table>
      
<h4>b. Pola Kognitif dan Perceptual</h4>
  <div class="field-row mb-2">
    <div class="field-value"><?= p($kebutuhan['kognitif'] ?? '') ?></div>
</div>

<h4> c. Pola Nutrisi</h4>
 <div class="field-row mb-2">
    <div class="field-value"><?= p($kebutuhan['pola_nutrisi'] ?? '') ?></div>
</div>

<h4>d. Cairan</h4>
 <div class="field-row mb-2">
    <div class="field-value"><?= p($kebutuhan['cairan'] ?? '') ?></div>
</div>

<h4>e. Pola Eliminasi BAB</h4>
 <div class="field-row mb-2">
    <div class="field-value"><?= p($kebutuhan['bab'] ?? '') ?></div>
</div>

<h4>f. Pola Eliminasi BAK</h4>
 <div class="field-row mb-2">
    <div class="field-value"><?= p($kebutuhan['bak'] ?? '') ?></div>
</div>

<h4>g. Pola Tidur</h4>
 <div class="field-row mb-2">
    <div class="field-value"><?= p($kebutuhan['tidur'] ?? '') ?></div>
</div>

<h4>h. Pola Personal Hygiene</h4>
 <div class="field-row mb-2">
    <div class="field-value"><?= p($kebutuhan['hygiene'] ?? '') ?></div>
</div>

<h3>6. Data Penunjang</h3>
<div class="field-row mb-2">
    <div class="field-label">Tanggal Pemeriksaan
</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($kebutuhan['tanggal_pemeriksaan'] ?? '') ?></div>
</div>
<h4>a) Laboratorium</h4>
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
    <div class="field-label">b) Radiologi (Tgl Pemeriksaan & Hasil)</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($kebutuhan['radiologi'] ?? '') ?></div>
</div>          
<div class="field-row mb-2">
    <div class="field-label">c) Lainnya (USG, CT Scan, dll)</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($kebutuhan['data_penunjang_lain'] ?? '') ?></div>
</div>

<p class="text-primary fw-bold mb-2">4) Terapi/Obat</p>
<table class="data">
    <thead>
        <tr>
            <th>No</th>
            <th width="30%">Jenis Obat</th>
            <th width="20%">Dosis</th>
            <th width="25%">Kegunaan</th>
            <th width="25%">Cara Pemberian</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($kebutuhan_obat)): ?>
            <?php foreach ($kebutuhan_obat as $index => $obat): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= p($obat['jenis_obat']) ?></td>
                    <td><?= p($obat['dosis']) ?></td>
                    <td><?= p($obat['kegunaan']) ?></td>
                    <td><?= p($obat['cara_pemberian']) ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="5" style="text-align:center">-</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>


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
