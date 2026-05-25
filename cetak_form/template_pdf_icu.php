<?php
// Shortcut per section
$laporan        = $sections['laporan_pendahuluan'] ?? [];
$pkj     = $sections['pengkajian'] ?? [];
$fisik       = $sections['pemeriksaan_fisik'] ?? [];
$pemeriksaan       = $sections['pemeriksaan_penunjang'] ?? [];
$analisa     = $sections['analisa'] ?? [];
$lainnya    = $sections['lainnya'] ?? [];

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
     <h1>Format Askep Keperawatan  R. (Icu)</h1>
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
<h3>A. Konsep Dasar Medis</h3>
<div class="field-row">
    <div class="field-label">Pengertian</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($laporan['pengertian'] ?? '-') ?></div>
</div>

<div class="field-row">
    <div class="field-label">Etiologi</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($laporan['etiologi'] ?? '-') ?></div>
</div>

<div class="field-row">
    <div class="field-label">Klasifikasi</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($laporan['klasifikasi'] ?? '-') ?></div>
</div>

<div class="field-row">
    <div class="field-label">Patofisiologi</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($laporan['patofisiologi'] ?? '-') ?></div>
</div>

<div class="field-row">
    <div class="field-label">Manifestasi Klinik</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($laporan['manifestasiklinik'] ?? '-') ?></div>
</div>

<div class="field-row">
    <div class="field-label">Pemeriksaan Diagnostik</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($laporan['pemeriksaandiagnostik'] ?? '-') ?></div>
</div>

<div class="field-row">
    <div class="field-label">Penatalaksanaan</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($laporan['penatalaksanaan'] ?? '-') ?></div>
</div>

<div class="field-row">
    <div class="field-label">Komplikasi</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($laporan['komplikasi'] ?? '-') ?></div>
</div>

    <h3>B. Konsep Dasar Keperawatan</h3>
    <div class="field-row">
        <div class="field-label">Pengkajian Keperawatan</div>
        <div class="field-sep">:</div>
        <div class="field-value"><?= p($laporan['pengkajiankeperawatan'] ?? '-') ?></div>
    </div>

     <div class="field-row">
        <div class="field-label">Penyimpangan KDM</div>
        <div class="field-sep">:</div>
        <div class="field-value"><?= p($laporan['link_penyimpangan'] ?? '-') ?></div>
    </div>


    <div class="subsection-title">Perencanaan</div>
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
            <?php if (!empty($laporan['perencanaan'])): ?>
                <?php foreach (arr($laporan['perencanaan']) as $i => $row): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><?= p($row['diagnosa'] ?? '-') ?></td>
                        <td><?= p($row['tujuan_kriteria'] ?? '-') ?></td>
                        <td><?= p($row['intervensi'] ?? '-') ?></td>
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
        <div class="field-value"><?= p($laporan['daftarpustaka'] ?? '-') ?></div>
    </div>
    
        <div class="page-break"></div>

        <!-- ================================ -->
        <!-- BAGIAN 2: FORMAT PENGKAJIAN     -->
        <!-- ================================ -->
        <h1>Format Askep Keperawatan  R. (Icu)</h1>

       
        <!-- ================================ -->
        <!-- IDENTITAS KLIEN                 -->
        <!-- ================================ -->
        <h3>1. Identitas</h3>
        <div class="subsection-title">Identitas Klien</div>

<div class="field-row">
    <div class="field-label">Nama</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($pkj['nama'] ?? '-') ?></div>
</div>

<div class="field-row">
    <div class="field-label">Umur</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($pkj['umur'] ?? '-') ?> tahun</div>
</div>

<div class="field-row">
    <div class="field-label">Jenis Kelamin</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($pkj['jeniskelamin'] ?? '-') ?></div>
</div>

<div class="field-row">
    <div class="field-label">Pekerjaan</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($pkj['pekerjaan'] ?? '-') ?></div>
</div>

<div class="field-row">
    <div class="field-label">Agama</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($pkj['agama'] ?? '-') ?></div>
</div>

<div class="field-row">
    <div class="field-label">Tgl MRS</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($pkj['tgl_mrs'] ?? '-') ?></div>
</div>

<div class="field-row">
    <div class="field-label">Tgl Pengkajian</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($pkj['tgl_pengkajian'] ?? '-') ?></div>
</div>

<div class="field-row">
    <div class="field-label">No. REG</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($pkj['noreg'] ?? '-') ?></div>
</div>

<div class="field-row">
    <div class="field-label">Alamat</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($pkj['alamat'] ?? '-') ?></div>
</div>

<div class="field-row">
    <div class="field-label">DX Medis</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($pkj['dxmedis'] ?? '-') ?></div>
</div>

<h3>Keluhan Utama & Tanda Vital</h3>

<div class="field-row">
    <div class="field-label" style="width: 30%;">Keluhan Utama</div>
    <div class="field-sep" style="width: 2%;">:</div>
    <div class="field-value" style="width: 68%;"><?= p($pkj['keluhanutama'] ?? '-') ?></div>
</div>

<div class="field-row">
    <div class="field-label" style="width: 30%;">Riwayat Keluhan Utama</div>
    <div class="field-sep" style="width: 2%;">:</div>
    <div class="field-value" style="width: 68%;"><?= p($pkj['riwayatkeluhanutama'] ?? '-') ?></div>
</div>

<div class="field-row">
    <div class="field-label" style="width: 30%;">Riwayat Alergi</div>
    <div class="field-sep" style="width: 2%;">:</div>
    <div class="field-value" style="width: 68%;"><?= p($pkj['riwayatalergi'] ?? '-') ?></div>
</div>

<div class="field-row">
    <div class="field-label" style="width: 30%;">Keadaan Umum</div>
    <div class="field-sep" style="width: 2%;">:</div>
    <div class="field-value" style="width: 68%;"><?= p($pkj['keadaanumum'] ?? '-') ?></div>
</div>

        <div class="subsection-title">Tanda Vital</div>
        <table class="header-table">
            <tr>
                <td width="30%"><strong>Nadi</strong></td>
                <td width="2%">:</td>
                <td width="18%"><?= p($pkj['nadi']) ?> /menit</td>
                <td width="20%"><strong>Pernafasan</strong></td>
                <td width="2%">:</td>
                <td><?= p($pkj['rr']) ?> x/menit</td>
            </tr>
            <tr>
                <td><strong>Tekanan Darah</strong></td>
                <td>:</td>
                <td><?= p($pkj['tekanandarah']) ?> mmHg</td>
                <td><strong>Suhu</strong></td>
                <td>:</td>
                <td><?= p($pkj['suhu']) ?>°C</td>
            </tr>
        </table>


<table class="data">
    <tr>
        <th colspan="3" class="bg-header">1. Primary Survey</th>
    </tr>
    <tr>
        <td width="35%"><strong>Tanggal Pengumpulan</strong></td>
        <td width="2%">:</td>
        <td><?= p($pkj['tanggal'] ?? '-') ?></td>
    </tr>

    <tr><td colspan="3" class="bg-header">Airway (Jalan Napas)</td></tr>
    <tr>
        <td>Sumbatan Jalan Nafas/Sekret</td>
        <td>:</td>
        <td><?= p($pkj['jalannafas'] ?? '-') ?></td>
    </tr>
    <tr>
        <td>ETT/Trakeostomi</td>
        <td>:</td>
        <td><?= p($pkj['ett'] ?? '-') ?></td>
    </tr>

    <tr><td colspan="3" class="bg-header">Breathing (Pernafasan)</td></tr>
    <tr>
        <td>Pola Nafas</td>
        <td>:</td>
        <td><?= p($pkj['polanafas'] ?? '-') ?> x/menit</td>
    </tr>
    <tr>
        <td>SpO2</td>
        <td>:</td>
        <td><?= p($pkj['spo2'] ?? '-') ?> %</td>
    </tr>
    <tr>
        <td>Ventilator (mode/PEEP/FiO2)</td>
        <td>:</td>
        <td><?= p($pkj['ventilator'] ?? '-') ?></td>
    </tr>
    <tr>
        <td>Pernafasan Cuping Hidung</td>
        <td>:</td>
        <td><?= p($pkj['pernafasancupinghidung'] ?? '-') ?></td>
    </tr>
    <tr>
        <td>Retraksi Dinding Dada</td>
        <td>:</td>
        <td><?= p($pkj['retraksidindingdada'] ?? '-') ?></td>
    </tr>
    <tr>
        <td>Otot bantu napas</td>
        <td>:</td>
        <td><?= p($pkj['ototbantu'] ?? '-') ?></td>
    </tr>

    <tr><td colspan="3" class="bg-header">Circulation (Sirkulasi)</td></tr>
    <tr>
        <td>Nadi</td>
        <td>:</td>
        <td><?= p($pkj['nadi'] ?? '-') ?> x/menit</td>
    </tr>
    <tr>
        <td>CVP / CRT</td>
        <td>:</td>
        <td><?= p($pkj['cvp'] ?? '-') ?> / <?= p($pkj['crt'] ?? '-') ?> detik</td>
    </tr>
    <tr>
        <td>Suara Jantung / Perfusi</td>
        <td>:</td>
        <td><?= p($pkj['suarajantung'] ?? '-') ?> / <?= p($pkj['perfusiperifer'] ?? '-') ?></td>
    </tr>

    <tr><td colspan="3" class="bg-header">Disability (Neurologi)</td></tr>
    <tr>
        <td>Tingkat Kesadaran</td>
        <td>:</td>
        <td><?= p($pkj['tingkatkesadaran'] ?? '-') ?></td>
    </tr>
    <tr>
        <td>GCS (E, M, V)</td>
        <td>:</td>
        <td>E: <?= p($pkj['e'] ?? '-') ?>, M: <?= p($pkj['m'] ?? '-') ?>, V: <?= p($pkj['v'] ?? '-') ?></td>
    </tr>
    <tr>
        <td>Pupil / Respon Motorik</td>
        <td>:</td>
        <td><?= p($pkj['pupil'] ?? '-') ?> / <?= p($pkj['responmotorik'] ?? '-') ?></td>
    </tr>

    <tr><td colspan="3" class="bg-header">Exposure & Fluid</td></tr>
    <tr>
        <td>Suhu / Lainnya</td>
        <td>:</td>
        <td><?= p($pkj['suhu'] ?? '-') ?> °C / <?= p($pkj['lainnya'] ?? '-') ?></td>
    </tr>
    <tr>
        <td>Infuse / Cairan / Tetesan</td>
        <td>:</td>
        <td><?= p($pkj['infuse'] ?? '-') ?> / <?= p($pkj['cairan'] ?? '-') ?> / <?= p($pkj['jumlahtetesan'] ?? '-') ?> x/m</td>
    </tr>
</table>

        <!-- ================================ -->
        <!-- BAGIAN 2: FORMAT PENGKAJIAN     -->
        <!-- ================================ -->
<div class="page-break"></div>
<h3>2. Pemeriksaan Fisik Spesifik With Body Sistem</h3>
<div class="field-row">
    <div class="field-label">a. Pernafasan (B1: Breathing)</div>
</div>
<?php


?>
<div class="field-row">
    <div class="field-label">Hidung</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($fisik['hidung'] ?? '', 'asimetris') ?> Asimetris &nbsp;
        <?= chk($fisik['hidung'] ?? '', 'deviasiseptum') ?> Deviasi Septum &nbsp;
        <?= chk($fisik['hidung'] ?? '', 'epistaksis') ?> Epistaksis &nbsp;
        <?= chk($fisik['hidung'] ?? '', 'lainlain') ?> Lain-lain &nbsp;
    </div>
</div>
<div class="field-row">
    <div class="field-label">Trakea</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($fisik['trakea'] ?? '', 'deviasitrakea') ?> Deviasi Trakea &nbsp;
        <?= chk($fisik['trakea'] ?? '', 'disfagia') ?> Disfagia &nbsp;
    </div>
</div>

<div class="field-row"><div class="field-label">Nyeri</div><div class="field-sep">:</div><div class="field-value"><?= chk($fisik['nyeri'], 'ya') ?> Ya &nbsp; <?= chk($fisik['nyeri'], 'tidak') ?> Tidak</div></div>
<div class="field-row"><div class="field-label">Dyspnea</div><div class="field-sep">:</div><div class="field-value"><?= chk($fisik['dypsnea'], 'ya') ?> Ya &nbsp; <?= chk($fisik['dypsnea'], 'tidak') ?> Tidak</div></div>
<div class="field-row"><div class="field-label">Cyanosis</div><div class="field-sep">:</div><div class="field-value"><?= chk($fisik['cyanosis'], 'ya') ?> Ya &nbsp; <?= chk($fisik['cyanosis'], 'tidak') ?> Tidak</div></div>
<div class="field-row"><div class="field-label">Retraksi Dada</div><div class="field-sep">:</div><div class="field-value"><?= chk($fisik['retraksidada'], 'ya') ?> Ya &nbsp; <?= chk($fisik['retraksidada'], 'tidak') ?> Tidak</div></div>
<div class="field-row"><div class="field-label">Batuk Darah</div><div class="field-sep">:</div><div class="field-value"><?= chk($fisik['batukdarah'], 'ya') ?> Ya &nbsp; <?= chk($fisik['batukdarah'], 'tidak') ?> Tidak</div></div>
<div class="field-row"><div class="field-label">Orthopnea</div><div class="field-sep">:</div><div class="field-value"><?= chk($fisik['orthopnea'], 'ya') ?> Ya &nbsp; <?= chk($fisik['orthopnea'], 'tidak') ?> Tidak</div></div>
<div class="field-row"><div class="field-label">Napas Dangkal</div><div class="field-sep">:</div><div class="field-value"><?= chk($fisik['napasdangkal'], 'ya') ?> Ya &nbsp; <?= chk($fisik['napasdangkal'], 'tidak') ?> Tidak</div></div>
<div class="field-row"><div class="field-label">Sputum</div><div class="field-sep">:</div><div class="field-value"><?= chk($fisik['sputum'], 'ya') ?> Ya &nbsp; <?= chk($fisik['sputum'], 'tidak') ?> Tidak</div></div>
<div class="field-row"><div class="field-label">Trakeostomi</div><div class="field-sep">:</div><div class="field-value"><?= chk($fisik['trakeostomi'], 'ya') ?> Ya &nbsp; <?= chk($fisik['trakeostomi'], 'tidak') ?> Tidak</div></div>

<div class="field-row">
    <div class="field-label">Suara Napas</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($fisik['suaratambahannapas'] ?? '', 'weezhing') ?> Weezhing &nbsp;
        <?= chk($fisik['suaratambahannapas'] ?? '', 'ronchi') ?> Ronchi &nbsp;
        <?= chk($fisik['suaratambahannapas'] ?? '', 'crackles') ?> Crackles &nbsp;
        <?= chk($fisik['suaratambahannapas'] ?? '', 'stridor') ?> Stridor
    </div>
</div>

<div class="field-row">
    <div class="field-label">Bentuk Dada</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($fisik['bentukdada'], 'simetris') ?> Simetris &nbsp;
        <?= chk($fisik['bentukdada'], 'tidaksimetris') ?> Tidak Simetris<br>
        <strong>Lainnya:</strong> <?= p($fisik['lainnyabentukdada'] ?? '-') ?>
    </div>
</div>
<div class="field-row">
    <div class="field-label">b. Cardiovaskuler (B2: Bleeding)</div>
</div>

<div class="field-row">
    <div class="field-label">Nyeri Dada</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= chk($fisik['nyeridada'], 'ya') ?> Ya &nbsp; <?= chk($fisik['nyeridada'], 'tidak') ?> Tidak</div>
</div>

<div class="field-row">
    <div class="field-label">Pusing</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= chk($fisik['pusing'], 'ya') ?> Ya &nbsp; <?= chk($fisik['pusing'], 'tidak') ?> Tidak</div>
</div>

<div class="field-row">
    <div class="field-label">Sakit Kepala</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= chk($fisik['sakitkepala'], 'ya') ?> Ya &nbsp; <?= chk($fisik['sakitkepala'], 'tidak') ?> Tidak</div>
</div>

<div class="field-row">
    <div class="field-label">Palpitasi</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= chk($fisik['palpitasi'], 'ya') ?> Ya &nbsp; <?= chk($fisik['palpitasi'], 'tidak') ?> Tidak</div>
</div>

<div class="field-row">
    <div class="field-label">Clubbing Finger</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= chk($fisik['clubbingfinger'], 'ya') ?> Ya &nbsp; <?= chk($fisik['clubbingfinger'], 'tidak') ?> Tidak</div>
</div>
<div class="field-row">
    <div class="field-label">Suara Jantung</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($fisik['suarajantung'] ?? '', 'normal') ?> Normal (S1/S2 Tunggal) &nbsp;
         kelainan <?= chk($fisik['suarajantung'] ?? '', 'kelainans3') ?> S3 &nbsp;
        <?= chk($fisik['suarajantung'] ?? '', 'kelainans4') ?> S4 &nbsp;
        <?= chk($fisik['suarajantung'] ?? '', 'kelainanmurmur') ?> Mur-mur &nbsp;
        <?= chk($fisik['suarajantung'] ?? '', 'gallop') ?> Gallop
    </div>
</div>

<div class="field-row">
    <div class="field-label">Edema</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($fisik['edema'] ?? '', 'palpebra') ?> Palpebra &nbsp;
        <?= chk($fisik['edema'] ?? '', 'anasarka') ?> Anasarka &nbsp;
        <?= chk($fisik['edema'] ?? '', 'ekstremitasatas') ?> Ekstremitas Atas &nbsp;
        <?= chk($fisik['edema'] ?? '', 'ekstremitasbawah') ?> Ekstremitas Bawah &nbsp;
        <?= chk($fisik['edema'] ?? '', 'ascites') ?> Ascites<br>
        <strong>Lainnya:</strong> <?= p($fisik['lainnyaedema'] ?? '-') ?> &nbsp;
        <strong>Sebutkan:</strong> <?= p($fisik['sebutkanedema'] ?? '-') ?>
    </div>
</div>


<div class="field-row">
    <div class="field-label">c. Persyarafan (B3: Brain) </div>
</div>


<div class="field-row"><div class="field-label">Kesadaran</div><div class="field-sep">:</div><div class="field-value"><?= p($fisik['kesadaran'] ?? '-') ?></div></div>
<div class="field-row"><div class="field-label">GCS (E-M-V)</div><div class="field-sep">:</div><div class="field-value">E: <?= p($fisik['gcs_e'] ?? '-') ?> | M: <?= p($fisik['gcs_m'] ?? '-') ?> | V: <?= p($fisik['gcs_v'] ?? '-') ?></div></div>
<div class="field-row"><div class="field-label">Kejang</div><div class="field-sep">:</div><div class="field-value"><?= p($fisik['kejang'] ?? '-') ?></div></div>

<div class="field-row">
    <div class="field-label">Kepala</div><div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($fisik['kepala'], 'mesosepal') ?> Mesosepal &nbsp;
        <?= chk($fisik['kepala'], 'asimetris') ?> Asimetris &nbsp;
        <?= chk($fisik['kepala'], 'hematoma') ?> Hematoma
    </div>
</div>

<div class="field-row">
    <div class="field-label">Wajah</div><div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($fisik['wajah'], 'simetris') ?> Simetris &nbsp;
        <?= chk($fisik['wajah'], 'asimetris') ?> Asimetris &nbsp;
        <?= chk($fisik['wajah'], 'bellpalsy') ?> Bell Palsy &nbsp;
        <?= chk($fisik['wajah'], 'Kelainan Kongenital') ?> Kelainan Kongenital
    </div>
</div>

<div class="field-row">
    <div class="field-label">Mata</div><div class="field-sep">:</div>
    <div class="field-value">
        <strong>Sklera:</strong> <?= chk($fisik['sklera'], 'putih') ?> Putih, <?= chk($fisik['sklera'], 'ikterus') ?> Ikterus, <?= chk($fisik['sklera'], 'merah') ?> Merah, <?= chk($fisik['sklera'], 'perdarahan') ?> Perdarahan<br>
        <strong>Konjungtiva:</strong> <?= chk($fisik['konjungtiva'], 'anemis') ?> Putih, <?= chk($fisik['konjungtiva'], 'merahmuda') ?> Merah Muda
    </div>
</div>

<div class="field-row"><div class="field-label">Pupil</div><div class="field-sep">:</div><div class="field-value"><?= p($fisik['pupil'] ?? '-') ?> | Ukuran: <?= p($fisik['ukuran_pupil'] ?? '-') ?></div></div>

<div class="field-row">
    <div class="field-label">Leher</div><div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($fisik['leher'], 'kesulitanmenelan') ?> Kesulitan Menelan, <?= chk($fisik['leher'], 'suaraparau') ?> Suara Parau, <?= chk($fisik['leher'], 'pembesarantiroid') ?> Pembesaran Tiroid, <?= chk($fisik['leher'], 'jvp') ?> JVP
    </div>
</div>

<div class="field-row">
    <div class="field-label">Refleks Tendon</div><div class="field-sep">:</div>
    <div class="field-value">
        <strong>Normal:</strong> <?= chk($fisik['reflekstendonnormal'], 'bisep') ?> Bisep, <?= chk($fisik['reflekstendonnormal'], 'trisep') ?> Trisep, <?= chk($fisik['reflekstendonnormal'], 'brakhialis') ?> Brakhialis, <?= chk($fisik['reflekstendonnormal'], 'patella') ?> Patella, <?= chk($fisik['reflekstendonnormal'], 'achilles') ?> Achilles<br>
        <strong>Tidak Normal:</strong> <?= chk($fisik['reflekstidaknormal'], 'kakukuduk') ?> Kaku Kuduk, <?= chk($fisik['reflekstidaknormal'], 'babinski') ?> Babinski, <?= chk($fisik['reflekstidaknormal'], 'brudzinski') ?> Brudzinski, <?= chk($fisik['reflekstidaknormal'], 'kernigsign') ?> Kernig Sign
    </div>
</div>

<div class="field-row">
    <div class="field-label"><strong>Persepsi Sensori</strong></div>
</div>

<div class="field-row"><div class="field-label">Pendengaran</div><div class="field-sep">:</div><div class="field-value">Kiri: <?= chk($fisik['pendengaran_kiri'], 'baik') ?> Baik / <?= chk($fisik['pendengaran_kiri'], 'tidakbaik') ?> Tidak | Kanan: <?= chk($fisik['pendengaran_kanan'], 'baik') ?> Baik / <?= chk($fisik['pendengaran_kanan'], 'tidakbaik') ?> Tidak</div></div>
<div class="field-row"><div class="field-label">Penciuman</div><div class="field-sep">:</div><div class="field-value"><?= chk($fisik['penciuman'], 'baik') ?> Baik / <?= chk($fisik['penciuman'], 'tidakbaik') ?> Tidak</div></div>
<div class="field-row"><div class="field-label">Pengecapan</div><div class="field-sep">:</div><div class="field-value"><?= chk($fisik['pengecapan'], 'baik') ?> Baik / <?= chk($fisik['pengecapan'], 'tidakbaik') ?> Tidak</div></div>
<div class="field-row"><div class="field-label">Penglihatan</div><div class="field-sep">:</div><div class="field-value">Kiri: <?= chk($fisik['penglihatan_kiri'], 'baik') ?> Baik / <?= chk($fisik['penglihatan_kiri'], 'tidakbaik') ?> Tidak | Kanan: <?= chk($fisik['penglihatan_kanan'], 'baik') ?> Baik / <?= chk($fisik['penglihatan_kanan'], 'tidakbaik') ?> Tidak</div></div>
<div class="field-row"><div class="field-label">Alat Bantu</div><div class="field-sep">:</div><div class="field-value"><?= p($fisik['alatbantu'] ?? '-') ?></div></div>
<div class="field-row"><div class="field-label">Perabaan</div><div class="field-sep">:</div><div class="field-value">Panas: <?= chk($fisik['perabaan_panas'], 'baik') ?> Baik /<?= chk($fisik['perabaan_panas'], 'tidak') ?> Tidak | Dingin: <?= chk($fisik['perabaan_dingin'], 'baik') ?> Baik / <?= chk($fisik['perabaan_dingin'], 'tidak') ?>Tidak | Tekan: <?= chk($fisik['perabaan_tekan'], 'baik') ?> Baik / <?= chk($fisik['perabaan_tekan'], 'tidak') ?> Tidak</div></div>


<div class="field-row">
    <div class="field-label">d. Perkemihan-Eliminasi Urin (B4: Bladder)
</div>

</div>

<div class="field-row">
    <div class="field-label">Produksi Urine</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['produksiurine'] ?? '-') ?> ml</div>
</div>

<div class="field-row">
    <div class="field-label">Frekuensi</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['frekuensi'] ?? '-') ?> /hari</div>
</div>

<div class="field-row">
    <div class="field-label">Warna</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['warna'] ?? '-') ?></div>
</div>

<div class="field-row">
    <div class="field-label">Bau</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['bau'] ?? '-') ?></div>
</div>

<div class="field-row">
    <div class="field-label">Douwer Cateter</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($fisik['douwercateter'], 'ya') ?> Ya &nbsp; 
        <?= chk($fisik['douwercateter'], 'tidak') ?> Tidak<br>
        <strong>Hari Ke:</strong> <?= p($fisik['harike_cateter'] ?? '-') ?>
    </div>
</div>

<div class="field-row">
    <div class="field-label">Spolling Blass</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['spollingblass'] ?? '-') ?></div>
</div>

<div class="field-row">
    <div class="field-label">Kelainan Urine</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['kelainandalamurine'] ?? '-') ?></div>
</div>

<div class="field-row">
    <div class="field-label">e. Pencernaan-Eliminasi Alvi (B5: Bowel)</div>
</div>
 <?php $mulutdantenggorokan_arr = arr($fisik['mulutdantenggorokan']); ?>

<div class="field-row">
            <div class="field-label">Mulut & Tenggorokan</div>
            <div class="field-sep">:</div>
            <div class="field-value">
                <?= chkIn($mulutdantenggorokan_arr, 'mukosakering') ?> Mukosa Kering &nbsp;
                <?= chkIn($mulutdantenggorokan_arr, 'merahmudah') ?> Merah Muda &nbsp;
                <?= chkIn($mulutdantenggorokan_arr, 'kesulitanmenelan') ?> Kesulitan Menelan &nbsp;
             
        </div>
        </div>
        <div class="field-row">
    <div class="field-label">Abdomen</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($fisik['abdomen'] ?? '', 'distensi') ?> Distensi &nbsp;
        <?= chk($fisik['abdomen'] ?? '', 'nyeritekan') ?> Nyeri Tekan
    </div>
</div>

<div class="field-row">
    <div class="field-label">Rektum</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($fisik['rektum'] ?? '', 'adakelainan') ?> Ada Kelainan &nbsp;
        <?= chk($fisik['rektum'] ?? '', 'tidakadakelainan') ?> Tidak Ada Kelainan
    </div>
</div>

<div class="field-row">
    <div class="field-label">Anjuran Puasa</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['anjuranpuasa'] ?? '-') ?> | Selama: <?= p($fisik['selama'] ?? '-') ?></div>
</div>

<div class="field-row">
    <div class="field-label">Diet</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['dietyangdiberikan'] ?? '-') ?></div>
</div>

<div class="field-row">
    <div class="field-label">Terpasang NGT</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['terpasangngt'] ?? '-') ?> | Hari Ke: <?= p($fisik['harike_ngt'] ?? '-') ?></div>
</div>

<div class="field-row">
    <div class="field-label">Kelainan Cerna</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['kelainansalurancerna'] ?? '-') ?></div>
</div>

<div class="field-row">
    <div class="field-label">f. Tulang-Otot-Integumen (B6: Bone)
</div>
</div>


<div class="field-row"><div class="field-label">Kelainan Tulang</div><div class="field-sep">:</div><div class="field-value"><?= p($fisik['kelainanpadatulang'] ?? '-') ?></div></div>
<div class="field-row"><div class="field-label">Kekuatan Otot</div><div class="field-sep">:</div><div class="field-value"><?= p($fisik['kekuatanotot'] ?? '-') ?></div></div>
<div class="field-row"><div class="field-label">Hemiparese</div><div class="field-sep">:</div><div class="field-value"><?= p($fisik['hemiparese'] ?? '-') ?></div></div>
<div class="field-row"><div class="field-label">Tetraparese</div><div class="field-sep">:</div><div class="field-value"><?= p($fisik['tetraparese'] ?? '-') ?></div></div>
<div class="field-row"><div class="field-label">ROM</div><div class="field-sep">:</div><div class="field-value"><?= p($fisik['rom'] ?? '-') ?></div></div>
<div class="field-row"><div class="field-label">Lainnya</div><div class="field-sep">:</div><div class="field-value"><?= p($fisik['lainnya'] ?? '-') ?></div></div>

<div class="field-row">
    <div class="field-label">Ekstremitas</div><div class="field-sep">:</div>
    <div class="field-value">
        <strong>Atas:</strong> <?= chk($fisik['ekstremitasatas'], 'tidakadakelainan') ?> Tidak ada kelainan, <?= chk($fisik['ekstremitasatas'], 'peradangan') ?> Peradangan, <?= chk($fisik['ekstremitasatas'], 'patahtulang') ?> Patah tulang<br>
        <strong>Bawah:</strong> <?= chk($fisik['ekstremitasbawah'], 'tidakadakelainan') ?> Tidak ada kelainan, <?= chk($fisik['ekstremitasbawah'], 'peradangan') ?> Peradangan, <?= chk($fisik['ekstremitasbawah'], 'patahtulang') ?> Patah tulang
    </div>
</div>

<div class="field-row">
    <div class="field-label">Tulang Belakang</div><div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($fisik['tulangbelakang'], 'kifosis') ?> Kifosis, <?= chk($fisik['tulangbelakang'], 'lordosis') ?> Lordosis, <?= chk($fisik['tulangbelakang'], 'skoliosis') ?> Skoliosis, <?= chk($fisik['tulangbelakang'], 'nyeri') ?> Nyeri, <?= chk($fisik['tulangbelakang'], 'tidakadakelainan') ?> Tidak ada kelainan
    </div>
</div>

<div class="field-row">
    <div class="field-label">Kulit</div><div class="field-sep">:</div>
    <div class="field-value">
        <strong>Warna:</strong> <?= chk($fisik['warnakulit'], 'ikterik') ?> Ikterik, <?= chk($fisik['warnakulit'], 'pigmentasi') ?> Pigmentasi, <?= chk($fisik['warnakulit'], 'sianotik') ?> Sianotik, <?= chk($fisik['warnakulit'], 'pucat') ?> Pucat, <?= chk($fisik['warnakulit'], 'kemerahan') ?> Kemerahan<br>
        <strong>Akral:</strong> <?= chk($fisik['akral'], 'hangat') ?> Hangat, <?= chk($fisik['akral'], 'panas') ?> Panas, <?= chk($fisik['akral'], 'dinginkering') ?> Dingin kering, <?= chk($fisik['akral'], 'dinginbasah') ?> Dingin basah
    </div>
</div>

<div class="field-row"><div class="field-label">Turgor</div><div class="field-sep">:</div><div class="field-value"><?= p($fisik['turgor'] ?? '-') ?> detik</div></div>

<div class="field-row">
    <div class="field-label">g. Sistem Endokrin</div>
</div>

<div class="field-row">
    <div class="field-label">Terapi Hormon</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['terapihormon'] ?? '-') ?></div>
</div>
<div class="field-row">
    <div class="field-label">h. Sistem Reproduksi</div>
</div>

<div class="field-row">
    <div class="field-label">Sistem Reproduksi</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($fisik['sistemreproduksi'], 'lakilaki') ?> Laki-laki &nbsp;
        <?= chk($fisik['sistemreproduksi'], 'perempuan') ?> Perempuan
    </div>
</div>

<div class="field-row">
    <div class="field-label">Kelainan Bentuk</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($fisik['kelainanbentuk'], 'normal') ?> Normal &nbsp;
        <?= chk($fisik['kelainanbentuk'], 'tidaknormal') ?> Tidak Normal
    </div>
</div>

<div class="field-row">
    <div class="field-label">Kebersihan</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($fisik['kebersihan'], 'bersih') ?> Bersih &nbsp;
        <?= chk($fisik['kebersihan'], 'kotor') ?> Kotor
    </div>
</div>
<div class="field-row">
    <div class="field-label">i. Pola Aktivitas</div>
</div>

<div class="field-row"><div class="field-label"><strong>Makan</strong></div></div>
<div class="field-row"><div class="field-label">Frekuensi</div><div class="field-sep">:</div><div class="field-value"><?= p($fisik['frekuensi'] ?? '-') ?></div></div>
<div class="field-row"><div class="field-label">Jenis Menu</div><div class="field-sep">:</div><div class="field-value"><?= p($fisik['jenismenu'] ?? '-') ?></div></div>
<div class="field-row"><div class="field-label">Pantangan</div><div class="field-sep">:</div><div class="field-value"><?= p($fisik['pantangan'] ?? '-') ?></div></div>
<div class="field-row"><div class="field-label">Alergi</div><div class="field-sep">:</div><div class="field-value"><?= p($fisik['alergi'] ?? '-') ?></div></div>

<div class="field-row"><div class="field-label"><strong>Minum</strong></div></div>
<div class="field-row"><div class="field-label">Frekuensi</div><div class="field-sep">:</div><div class="field-value"><?= p($fisik['minumfrekuensi'] ?? '-') ?></div></div>
<div class="field-row"><div class="field-label">Jenis Menu</div><div class="field-sep">:</div><div class="field-value"><?= p($fisik['minumjenismenu'] ?? '-') ?></div></div>
<div class="field-row"><div class="field-label">Pantangan</div><div class="field-sep">:</div><div class="field-value"><?= p($fisik['minumpantangan'] ?? '-') ?></div></div>
<div class="field-row"><div class="field-label">Alergi</div><div class="field-sep">:</div><div class="field-value"><?= p($fisik['minumalergi'] ?? '-') ?></div></div>

<div class="field-row"><div class="field-label"><strong>Kebersihan Diri</strong></div></div>
<div class="field-row"><div class="field-label">Mandi</div><div class="field-sep">:</div><div class="field-value"><?= p($fisik['mandi'] ?? '-') ?></div></div>
<div class="field-row"><div class="field-label">Keramas</div><div class="field-sep">:</div><div class="field-value"><?= p($fisik['keramas'] ?? '-') ?></div></div>
<div class="field-row"><div class="field-label">Sikat Gigi</div><div class="field-sep">:</div><div class="field-value"><?= p($fisik['sikatgigi'] ?? '-') ?></div></div>
<div class="field-row"><div class="field-label">Potong Kuku</div><div class="field-sep">:</div><div class="field-value"><?= p($fisik['memotongkuku'] ?? '-') ?></div></div>
<div class="field-row"><div class="field-label">Ganti Pakaian</div><div class="field-sep">:</div><div class="field-value"><?= p($fisik['gantipakaian'] ?? '-') ?></div></div>
<div class="field-row"><div class="field-label">Masalah Lain</div><div class="field-sep">:</div><div class="field-value"><?= p($fisik['masalahlainkebersihandiri'] ?? '-') ?></div></div>
<div class="field-row">
    <div class="field-label">j. Social Interaction (Interaksi Sosial)</div>
</div>

<div class="field-row">
    <div class="field-label">Dukungan Keluarga</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($fisik['dukungankeluarga'], 'aktif') ?> Aktif &nbsp;
        <?= chk($fisik['dukungankeluarga'], 'kurang') ?> Kurang &nbsp;
        <?= chk($fisik['dukungankeluarga'], 'tidakada') ?> Tidak Ada
    </div>
</div>

<div class="field-row">
    <div class="field-label">Dukungan Teman/Masyarakat</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($fisik['dukungankel'], 'aktif') ?> Aktif &nbsp;
        <?= chk($fisik['dukungankel'], 'kurang') ?> Kurang &nbsp;
        <?= chk($fisik['dukungankel'], 'tidakada') ?> Tidak Ada
    </div>
</div>

<div class="field-row">
    <div class="field-label">Hubungan dengan Keluarga/Teman</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['hubunganklien'] ?? '-') ?></div>
</div>

<div class="field-row">
    <div class="field-label">Pendamping Selama Perawatan</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($fisik['menungguklien'] ?? '-') ?></div>
</div>
<div class="page-break"></div>

   <h3 class="mt-5">k. Pemeriksaan Laboratorium</h3>
<div class="field-row">
    <div class="field-label">Laboratorium</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($pemeriksaan['tgllaboratorium'] ?? '-') ?></div>
</div>
        <table class="data">
            <thead>
                <tr>
                    <th>Pemeriksaan</th>
                    <th>Hasil</th>
                    <th>Satuan</th>
                    <th>Nilai Rujukan</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($pemeriksaan['lab'])): ?>
                    <?php foreach ($pemeriksaan['lab'] as $lab): ?>
                        <tr>
                            <td><?= p($lab['pemeriksaan']) ?></td>
                            <td><?= p($lab['hasil']) ?></td>
                            <td><?= p($lab['satuan']) ?></td>
                            <td><?= p($lab['rujukan']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" style="text-align:center">-</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <div class="field-row">
    <div class="field-label">Radiologi</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($pemeriksaan['tglradiologi'] ?? '-') ?>| hasil <?= p($pemeriksaan['radiologi'] ?? '-') ?></div>
</div>

          <h3 class="mt-5">9. Pengobatan</h3>

        <table class="data">
            <thead>
                <tr>
                    <th>Nama Obat</th>
                    <th>Dosis</th>
                    <th>Rute Pemberian</th>
                    <th>Berapa Kali Pemberian/hari</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($pemeriksaan['obat'])): ?>
                    <?php foreach ($pemeriksaan['obat'] as $obat): ?>
                        <tr>
                            <td><?= p($obat['nama_obat']) ?></td>
                            <td><?= p($obat['dosis']) ?></td>
                            <td><?= p($obat['rute']) ?></td>
                            <td><?= p($obat['pemberian']) ?></td>
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

        <!-- ================================ -->
        <!-- KLASIFIKASI & ANALISA DATA      -->
        <!-- ================================ -->
        <h3>Klasifikasi Data</h3>
        <table class="data">
            <thead>
                <tr>
                    <th width="50%">Data Subyektif (DS)</th>
                    <th width="50%">Data Obyektif (DO)</th>
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

        <h3>Analisa Data</h3>
        <table class="data">
            <thead>
                <tr>
                    <th width="5%">No.</th>
                    <th width="40%">DS/DO</th>
                    <th width="30%">Etiologi</th>
                    <th width="25%">Masalah</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($analisa['analisa'])): ?>
                    <?php foreach ($analisa['analisa'] as $i => $ana): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td><?= p($ana['ds_do']) ?></td>
                            <td><?= p($ana['etiologi']) ?></td>
                            <td><?= p($ana['masalah']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" style="text-align:center">-</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- ================================ -->
        <!-- CATATAN KEPERAWATAN             -->
        <!-- ================================ -->
        <h3>Diagnosa Keperawatan Prioritas</h3>
        <table class="data">
            <thead>
                <tr>
                    <th width="5%">No.</th>
                    <th width="40%">DO/DS</th>
                    <th>Tanggal Ditemukan</th>
                    <th>Tanggal Teratasi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($lainnya['diagnosa'])): ?>
                    <?php foreach ($lainnya['diagnosa'] as $i => $dx): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td><?= p($dx['diagnosa']) ?></td>
                            <td><?= p($dx['tgl_ditemukan']) ?></td>
                            <td><?= p($dx['tgl_teratasi']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" style="text-align:center">-</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <h3>Rencana Keperawatan</h3>
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
                <?php if (!empty($lainnya['rencana'])): ?>
                    <?php foreach ($lainnya['rencana'] as $i => $r): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td><?= p($r['diagnosa']) ?></td>
                            <td><?= p($r['tujuan_kriteria']) ?></td>
                            <td><?= p($r['rencana']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" style="text-align:center">-</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <h3>Implementasi</h3>
        <table class="data">
            <thead>
                <tr>
                    <th>No. DX</th>
                    <th>Hari/Tanggal</th>
                    <th>Jam</th>
                    <th>Implementasi dan Hasil</th>
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

        <h3>Evaluasi</h3>
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