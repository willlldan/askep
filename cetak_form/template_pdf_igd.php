<?php
// Shortcut per section
$laporan        = $sections['laporan_pendahuluan'] ?? [];
$pkj     = $sections['pengkajian'] ?? [];
$pkjl     = $sections['pengkajian_lanjutan'] ?? [];
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
     <h1>FORMAT LAPORAN PENDAHULUAN (LP)</h1>
     <h2>KEPERAWATAN GAWAT DARURAT</h2>
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
<div class="field-row">
        <div class="field-label">Diagnosa Keperawatan </div>
        <div class="field-sep">:</div>
        <div class="field-value"><?= p($laporan['diagnosa'] ?? '-') ?></div>
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
        <h1>FORMAT PENGKAJIAN KEPERAWATAN RUANG IGD</h1>

       
        <!-- ================================ -->
        <!-- IDENTITAS KLIEN                 -->
        <!-- ================================ -->
        <h3>1. Identitas</h3>
        <div class="subsection-title">Identitas Klien</div>
<div class="field-row">
    <div class="field-label">No Rekam Medis
</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($pkj['norekammedis'] ?? '-') ?></div>
</div>
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
    <div class="field-label">Agama</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($pkj['agama'] ?? '-') ?></div>
</div>

<div class="field-row">
    <div class="field-label">Pekerjaan</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($pkj['pekerjaan'] ?? '-') ?></div>
</div>
<div class="field-row">
    <div class="field-label">Alamat</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($pkj['alamat'] ?? '-') ?></div>
</div>
<div class="field-row">
    <div class="field-label">Diagnosa Medis
</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($pkj['diagnosamedis'] ?? '-') ?></div>
</div>
<div class="field-row">
    <div class="field-label">Jenis Kelamin</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($pkj['jeniskelamin'] ?? '-') ?></div>
</div>
<div class="field-row">
    <div class="field-label">Pendidikan
</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($pkj['pendidikan'] ?? '-') ?></div>
</div>
<div class="field-row">
    <div class="field-label">Status Perkawinan
</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($pkj['statusperkawinan'] ?? '-') ?></div>
</div>

<div class="field-row">
    <div class="field-label">Sumber Informasi
</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($pkj['sumberinformasi'] ?? '-') ?></div>
</div>
<div class="field-row">
    <div class="field-label">Triase</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($pkj['triase'] ?? '', 'p1') ?> <span style="color:red; font-weight:bold;">P1</span> &nbsp;
        <?= chk($pkj['triase'] ?? '', 'p2') ?> <span style="color:#ffc107; font-weight:bold;">P2</span> &nbsp;
        <?= chk($pkj['triase'] ?? '', 'p3') ?> <span style="color:green; font-weight:bold;">P3</span> &nbsp;
        <?= chk($pkj['triase'] ?? '', 'p4') ?> <span style="color:black; font-weight:bold;">P4</span>
    </div>
</div>
<h3>B. Primary Survey</h3>

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

<div class="subsection-title">1. Airway</div>

<div class="field-row">
    <div class="field-label">Jalan Napas</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($pkj['jalannapas'] ?? '', 'paten') ?> Paten &nbsp;
        <?= chk($pkj['jalannapas'] ?? '', 'tidakpaten') ?> Tidak Paten
    </div>
</div>

<div class="field-row">
    <div class="field-label">Obstruksi</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($pkj['obstruksi'] ?? '', 'lidah') ?> Lidah &nbsp;
        <?= chk($pkj['obstruksi'] ?? '', 'cairan') ?> Cairan &nbsp;
        <?= chk($pkj['obstruksi'] ?? '', 'bendaasing') ?> Benda Asing
    </div>
</div>

<div class="field-row">
    <div class="field-label">Suara Napas</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($pkj['suaranapas_airway'] ?? '', 'snoring') ?> Snoring &nbsp;
        <?= chk($pkj['suaranapas_airway'] ?? '', 'gurgling') ?> Gurgling
    </div>
</div>

<div class="field-row">
    <div class="field-label">Keluhan Lain</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($pkj['keluhanlainairway'] ?? '-') ?></div>
</div>

<div class="subsection-title">2. Breathing</div>

<div class="field-row">
    <div class="field-label">Gerakan Dada</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($pkj['gerakandada'] ?? '', 'simetris') ?> Simetris &nbsp;
        <?= chk($pkj['gerakandada'] ?? '', 'asimetris') ?> Asimetris
    </div>
</div>

<div class="field-row">
    <div class="field-label">Irama Napas</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($pkj['iramanapas'] ?? '', 'cepat') ?> Cepat &nbsp;
        <?= chk($pkj['iramanapas'] ?? '', 'dangkal') ?> Dangkal &nbsp;
        <?= chk($pkj['iramanapas'] ?? '', 'normal') ?> Normal
    </div>
</div>

<div class="field-row">
    <div class="field-label">Pola Napas</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($pkj['polanapas'] ?? '', 'teratur') ?> Teratur &nbsp;
        <?= chk($pkj['polanapas'] ?? '', 'tidakteratur') ?> Tidak Teratur
    </div>
</div>

<div class="field-row">
    <div class="field-label">Retraksi Otot Dada</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($pkj['ototdada'] ?? '', 'ada') ?> Ada &nbsp;
        <?= chk($pkj['ototdada'] ?? '', 'tidakada') ?> Tidak Ada
    </div>
</div>

<div class="field-row">
    <div class="field-label">Sesak Napas</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($pkj['sesaknapas'] ?? '', 'ada') ?> Ada &nbsp;
        <?= chk($pkj['sesaknapas'] ?? '', 'tidakada') ?> Tidak Ada
    </div>
</div>

<div class="field-row">
    <div class="field-label">RR (Frekuensi Napas)</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($pkj['rr'] ?? '-') ?> x/menit</div>
</div>

<div class="field-row">
    <div class="field-label">Suara Napas</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($pkj['suaranapas'] ?? '-') ?></div>
</div>

<div class="field-row">
    <div class="field-label">Keluhan Lain</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($pkj['keluhanlainbreathing'] ?? '-') ?></div>
</div>

<div class="subsection-title">3. Circulation</div>

<div class="field-row">
    <div class="field-label">Pucat</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($pkj['pucat'] ?? '', 'ya') ?> Ya &nbsp;
        <?= chk($pkj['pucat'] ?? '', 'tidak') ?> Tidak
    </div>
</div>

<div class="field-row">
    <div class="field-label">Sianosis</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($pkj['sianosis'] ?? '', 'ya') ?> Ya &nbsp;
        <?= chk($pkj['sianosis'] ?? '', 'tidak') ?> Tidak
    </div>
</div>

<div class="field-row">
    <div class="field-label">Pendarahan</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($pkj['pendarahan'] ?? '', 'ya') ?> Ya &nbsp;
        <?= chk($pkj['pendarahan'] ?? '', 'tidak') ?> Tidak &nbsp;
        (Jumlah: <?= p($pkj['berapabanyak'] ?? '-') ?>)
    </div>
</div>

<div class="field-row">
    <div class="field-label">Nadi</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($pkj['nadi'] ?? '', 'teraba') ?> Teraba &nbsp;
        <?= chk($pkj['nadi'] ?? '', 'tidakteraba') ?> Tidak Teraba &nbsp;
        (Frekuensi: <?= p($pkj['frekuensinadi'] ?? '-') ?> x/menit)
    </div>
</div>

<div class="field-row">
    <div class="field-label">Tekanan Darah</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($pkj['tekanandarah'] ?? '-') ?> mmHg</div>
</div>

<div class="field-row">
    <div class="field-label">Suhu</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($pkj['suhu'] ?? '-') ?> °C</div>
</div>

<div class="field-row">
    <div class="field-label">CRT</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($pkj['crt'] ?? '', 'kurang2') ?> < 2 Detik &nbsp;
        <?= chk($pkj['crt'] ?? '', 'lebih2') ?> > 2 Detik
    </div>
</div>

<div class="field-row">
    <div class="field-label">Akral</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($pkj['akral'] ?? '', 'hangat') ?> Hangat &nbsp;
        <?= chk($pkj['akral'] ?? '', 'dingin') ?> Dingin
    </div>
</div>

<div class="field-row">
    <div class="field-label">Keluhan Lain</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($pkj['keluhanlain'] ?? '-') ?></div>
</div>

<div class="subsection-title">4. Disability</div>

<div class="field-row">
    <div class="field-label">Respon (AVPU)</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($pkj['respon'] ?? '', 'alert') ?> Alert &nbsp;
        <?= chk($pkj['respon'] ?? '', 'verbal') ?> Verbal &nbsp;
        <?= chk($pkj['respon'] ?? '', 'pain') ?> Pain &nbsp;
        <?= chk($pkj['respon'] ?? '', 'unresponden') ?> Unresponden
    </div>
</div>

<div class="field-row">
    <div class="field-label">Kesadaran</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($pkj['kesadaran'] ?? '', 'cm') ?> CM &nbsp;
        <?= chk($pkj['kesadaran'] ?? '', 'apatis') ?> Apatis &nbsp;
        <?= chk($pkj['kesadaran'] ?? '', 'samnolene') ?> Samnolen &nbsp;
    </div>
</div>

<div class="field-row">
    <div class="field-label">Lainnya</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($pkj['lainnyasebutkan'] ?? '-') ?></div>
</div>


<div class="field-row">
    <div class="field-label">GCS</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        E: <?= p($pkj['e'] ?? '-') ?> &nbsp;
        M: <?= p($pkj['m'] ?? '-') ?> &nbsp;
        V: <?= p($pkj['v'] ?? '-') ?>
    </div>
</div>

<div class="field-row">
    <div class="field-label">Pupil</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($pkj['pupil'] ?? '', 'isokor') ?> Isokor &nbsp;
        <?= chk($pkj['pupil'] ?? '', 'anisokor') ?> Anisokor &nbsp;
        <?= chk($pkj['pupil'] ?? '', 'miosis') ?> Miosis &nbsp;
        <?= chk($pkj['pupil'] ?? '', 'midriasis') ?> Midriasis
    </div>
</div>

<div class="field-row">
    <div class="field-label">Refleks Cahaya</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($pkj['reflekscahaya'] ?? '', 'ada') ?> Ada &nbsp;
        <?= chk($pkj['reflekscahaya'] ?? '', 'tidakada') ?> Tidak Ada
    </div>
</div>

<div class="field-row">
    <div class="field-label">Muntah Proyektil</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($pkj['muntahproyektil'] ?? '', 'ya') ?> Ya &nbsp;
        <?= chk($pkj['muntahproyektil'] ?? '', 'tidak') ?> Tidak
    </div>
</div>

<div class="field-row">
    <div class="field-label">Keluhan Lain</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($pkj['keluhanlaindisability'] ?? '-') ?></div>
</div>

<div class="subsection-title">5. Exposure</div>

<div class="field-row">
    <div class="field-label">Deformitas</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($pkj['diformitas'] ?? '', 'ya') ?> Ya &nbsp;
        <?= chk($pkj['diformitas'] ?? '', 'tidak') ?> Tidak
    </div>
</div>

<div class="field-row">
    <div class="field-label">Contusio</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($pkj['contusio'] ?? '', 'ya') ?> Ya &nbsp;
        <?= chk($pkj['contusio'] ?? '', 'tidak') ?> Tidak
    </div>
</div>

<div class="field-row">
    <div class="field-label">Abrasi</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($pkj['abrasi'] ?? '', 'ya') ?> Ya &nbsp;
        <?= chk($pkj['abrasi'] ?? '', 'tidak') ?> Tidak
    </div>
</div>

<div class="field-row">
    <div class="field-label">Penetrasi</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($pkj['penetrasi'] ?? '', 'ya') ?> Ya &nbsp;
        <?= chk($pkj['penetrasi'] ?? '', 'tidak') ?> Tidak
    </div>
</div>

<div class="field-row">
    <div class="field-label">Laserasi</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($pkj['laserasi'] ?? '', 'ya') ?> Ya &nbsp;
        <?= chk($pkj['laserasi'] ?? '', 'tidak') ?> Tidak
    </div>
</div>

<div class="field-row">
    <div class="field-label">Edema</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($pkj['edema'] ?? '', 'ya') ?> Ya &nbsp;
        <?= chk($pkj['edema'] ?? '', 'tidak') ?> Tidak
    </div>
</div>

<div class="field-row">
    <div class="field-label">Keluhan Lain</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($pkj['keluhanlainairway'] ?? '-') ?></div>
</div>

<div class="subsection-title">Folley Catheter</div>

<div class="field-row">
    <div class="field-label">Terpasang</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($pkj['folleyterpasang'] ?? '', 'ya') ?> Ya &nbsp;
        <?= chk($pkj['folleyterpasang'] ?? '', 'tidak') ?> Tidak
    </div>
</div>
<div class="subsection-title">Gastric Tube</div>

<div class="field-row">
    <div class="field-label">Terpasang</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($pkj['gastricterpasang'] ?? '', 'ya') ?> Ya &nbsp;
        <?= chk($pkj['gastricterpasang'] ?? '', 'tidak') ?> Tidak
    </div>
</div>
<div class="subsection-title">Heart Monitor
</div>

<div class="field-row">
    <div class="field-label">Terpasang</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($pkj['heartterpasang'] ?? '', 'ya') ?> Terpasang &nbsp;
        <?= chk($pkj['heartterpasang'] ?? '', 'tidak') ?> Tidak Terpasang
    </div>
</div>
<h3>C. SECONDARY SURVEY (SAMPLE History)</h3>

<div class="field-row">
    <div class="field-label">Riwayat Penyakit Saat Ini</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($pkjl['riwayatpenyakitsaatini'] ?? '-') ?></div>
</div>

<div class="field-row">
    <div class="field-label">Alergi</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($pkjl['alergi'] ?? '-') ?></div>
</div>

<div class="field-row">
    <div class="field-label">Medikasi</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($pkjl['medikasi'] ?? '-') ?></div>
</div>

<div class="field-row">
    <div class="field-label">Riwayat Penyakit Sebelumnya</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($pkjl['riwayatpenyakitsebelumnya'] ?? '-') ?></div>
</div>

<div class="field-row">
    <div class="field-label">Makan Minum Terakhir</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($pkjl['makanminumterakhir'] ?? '-') ?></div>
</div>

<div class="field-row">
    <div class="field-label">Even / Peristiwa</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($pkjl['even'] ?? '-') ?></div>
</div>
<div class="subsection-title">D. Tanda-tanda Vital</div>

<div class="field-row">
    <div class="field-label">Tekanan Darah</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($pkjl['tekanandarah'] ?? '-') ?> mmHg</div>
</div>

<div class="field-row">
    <div class="field-label">Nadi</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($pkjl['nadi'] ?? '-') ?> x/menit</div>
</div>

<div class="field-row">
    <div class="field-label">Suhu</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($pkjl['suhu'] ?? '-') ?> °C</div>
</div>

<div class="field-row">
    <div class="field-label">RR</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($pkjl['rr'] ?? '-') ?> x/menit</div>
</div>
<h4>Pemeriksaan Fisik </h4>
<div class="subsection-title"> Kepala</div>

<div class="field-row">
    <div class="field-label">Pendarahan</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($pkjl['pendarahankepala'] ?? '', 'ya') ?> Ya &nbsp;
        <?= chk($pkjl['pendarahankepala'] ?? '', 'tidak') ?> Tidak
    </div>
</div>

<div class="field-row">
    <div class="field-label">Depresi Tulang Kepala</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($pkjl['depresitulangkepala'] ?? '', 'ya') ?> Ya &nbsp;
        <?= chk($pkjl['depresitulangkepala'] ?? '', 'tidak') ?> Tidak
    </div>
</div>

<div class="field-row">
    <div class="field-label">Laserasi</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($pkjl['laserasikepala'] ?? '', 'ya') ?> Ya &nbsp;
        <?= chk($pkjl['laserasikepala'] ?? '', 'tidak') ?> Tidak
    </div>
</div>

<div class="field-row">
    <div class="field-label">Echymosis/Memar</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($pkjl['echymosismemar'] ?? '', 'ya') ?> Ya &nbsp;
        <?= chk($pkjl['echymosismemar'] ?? '', 'tidak') ?> Tidak
    </div>
</div>

<div class="field-row">
    <div class="field-label">Nyeri Tekan</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($pkjl['nyeritekankepala'] ?? '', 'ya') ?> Ya &nbsp;
        <?= chk($pkjl['nyeritekankepala'] ?? '', 'tidak') ?> Tidak
    </div>
</div>



<div class="field-row">
    <div class="field-label">Keluhan Lain</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($pkjl['keluhanlainkepala'] ?? '-') ?></div>
</div>

<div class="subsection-title">Mata</div>

<div class="field-row">
    <div class="field-label">Racoon Eyes</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($pkjl['racooneyes'] ?? '', 'ya') ?> Ya &nbsp;
        <?= chk($pkjl['racooneyes'] ?? '', 'tidak') ?> Tidak
    </div>
</div>

<div class="field-row">
    <div class="field-label">Pendarahan Mata</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($pkjl['pendarahanmata'] ?? '', 'ya') ?> Ya &nbsp;
        <?= chk($pkjl['pendarahanmata'] ?? '', 'tidak') ?> Tidak
    </div>
</div>

<div class="field-row">
    <div class="field-label">Ruptur / Robek</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($pkjl['rupturrobek'] ?? '', 'ya') ?> Ya &nbsp;
        <?= chk($pkjl['rupturrobek'] ?? '', 'tidak') ?> Tidak
    </div>
</div>

<div class="field-row">
    <div class="field-label">Konjungtiva</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($pkjl['konjungtiva'] ?? '', 'anemis') ?> Anemis &nbsp;
        <?= chk($pkjl['konjungtiva'] ?? '', 'ananemis') ?> Ananemis
    </div>
</div>

<div class="field-row">
    <div class="field-label">Sklera</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($pkjl['sklera'] ?? '', 'ikterik') ?> Ikterik &nbsp;
        <?= chk($pkjl['sklera'] ?? '', 'anikterik') ?> Anikterik
    </div>
</div>

<div class="field-row">
    <div class="field-label">Respon Pupil</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($pkjl['responpupilmata'] ?? '', 'isokor') ?> Isokor &nbsp;
        <?= chk($pkjl['responpupilmata'] ?? '', 'anisokor') ?> Anisokor &nbsp;
        <?= chk($pkjl['responpupilmata'] ?? '', 'midriasis') ?> Midriasis
    </div>
</div>

<div class="field-row">
    <div class="field-label">Keluhan Lain</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($pkjl['keluhanlainmata'] ?? '-') ?></div>
</div>

<div class="subsection-title">Telinga</div>

<div class="field-row">
    <div class="field-label">Cairan</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($pkjl['cairan'] ?? '', 'ada') ?> Ada 
        (Warna: <?= p($pkjl['jikaadawarna'] ?? '-') ?>) &nbsp;
        <?= chk($pkjl['cairan'] ?? '', 'tidak') ?> Tidak
    </div>
</div>

<div class="field-row">
    <div class="field-label">Lecet</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($pkjl['lecet'] ?? '', 'ya') ?> Ya &nbsp;
        <?= chk($pkjl['lecet'] ?? '', 'tidak') ?> Tidak
    </div>
</div>

<div class="field-row">
    <div class="field-label">Leserasi</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($pkjl['leserasi'] ?? '', 'ya') ?> Ya &nbsp;
        <?= chk($pkjl['leserasi'] ?? '', 'tidak') ?> Tidak
    </div>
</div>

<div class="field-row">
    <div class="field-label">Benda Asing</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($pkjl['bendaasing'] ?? '', 'ada') ?> Ada 
        (Berupa: <?= p($pkjl['berupa'] ?? '-') ?>) &nbsp;
        <?= chk($pkjl['bendaasing'] ?? '', 'tidak') ?> Tidak
    </div>
</div>

<div class="field-row">
    <div class="field-label">Keluhan Lain</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($pkjl['keluhanlaintelinga'] ?? '-') ?></div>
</div>
<div class="subsection-title">Hidung</div>

<div class="field-row">
    <div class="field-label">Ada Cairan</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($pkjl['adacairan'] ?? '', 'ya') ?> Ya 
        (Warna: <?= p($pkjl['warna'] ?? '-') ?>) &nbsp;
        <?= chk($pkjl['adacairan'] ?? '', 'tidak') ?> Tidak
    </div>
</div>

<div class="field-row">
    <div class="field-label">Lecet</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($pkjl['lecethidung'] ?? '', 'ya') ?> Ya &nbsp;
        <?= chk($pkjl['lecethidung'] ?? '', 'tidak') ?> Tidak
    </div>
</div>

<div class="field-row">
    <div class="field-label">Kemerahan</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($pkjl['kemerahan'] ?? '', 'ya') ?> Ya &nbsp;
        <?= chk($pkjl['kemerahan'] ?? '', 'tidak') ?> Tidak
    </div>
</div>

<div class="field-row">
    <div class="field-label">Leserasi</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($pkjl['leserasihidung'] ?? '', 'ya') ?> Ya &nbsp;
        <?= chk($pkjl['leserasihidung'] ?? '', 'tidak') ?> Tidak
    </div>
</div>

<div class="field-row">
    <div class="field-label">Benda Asing</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($pkjl['bendaasinghidung'] ?? '', 'ada') ?> Ada 
        (Berupa: <?= p($pkjl['berupahidung'] ?? '-') ?>) &nbsp;
        <?= chk($pkjl['bendaasinghidung'] ?? '', 'tidak') ?> Tidak
    </div>
</div>

<div class="field-row">
    <div class="field-label">Keluhan Lain</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($pkjl['keluhanlainhidung'] ?? '-') ?></div>
</div>
<div class="subsection-title">Leher</div>

<div class="field-row">
    <div class="field-label">Deviasi Trakea</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($pkjl['deviasitrakea'] ?? '', 'ya') ?> Ya &nbsp;
        <?= chk($pkjl['deviasitrakea'] ?? '', 'tidak') ?> Tidak
    </div>
</div>

<div class="field-row">
    <div class="field-label">Distensi Vena Jugularis</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($pkjl['distensivenajugularis'] ?? '', 'ya') ?> Ya &nbsp;
        <?= chk($pkjl['distensivenajugularis'] ?? '', 'tidak') ?> Tidak
    </div>
</div>

<div class="field-row">
    <div class="field-label">Bengkak</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($pkjl['bengkak'] ?? '', 'ya') ?> Ya &nbsp;
        <?= chk($pkjl['bengkak'] ?? '', 'tidak') ?> Tidak
    </div>
</div>

<div class="field-row">
    <div class="field-label">Kebiruan/Memar</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($pkjl['kebiruanmemar'] ?? '', 'ya') ?> Ya &nbsp;
        <?= chk($pkjl['kebiruanmemar'] ?? '', 'tidak') ?> Tidak
    </div>
</div>

<div class="field-row">
    <div class="field-label">Nyeri Tekan</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($pkjl['nyeritekanleher'] ?? '', 'ya') ?> Ya &nbsp;
        <?= chk($pkjl['nyeritekanleher'] ?? '', 'tidak') ?> Tidak
    </div>
</div>

<div class="field-row">
    <div class="field-label">Krepitasi</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($pkjl['krepitasi'] ?? '', 'ya') ?> Ya &nbsp;
        <?= chk($pkjl['krepitasi'] ?? '', 'tidak') ?> Tidak
    </div>
</div>

<div class="field-row">
    <div class="field-label">Keluhan Lain</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($pkjl['keluhanlainleher'] ?? '-') ?></div>
</div>
<div class="subsection-title">Dada/Paru</div>

<div class="field-row">
    <div class="field-label">Bentuk Dada</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($pkjl['bentukdada'] ?? '', 'simetris') ?> Simetris &nbsp;
        <?= chk($pkjl['bentukdada'] ?? '', 'asimetris') ?> Asimetris
    </div>
</div>

<div class="field-row">
    <div class="field-label">Laserasi/Jejas</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($pkjl['laserasijejas'] ?? '', 'ada') ?> Ada 
        (Ukuran: <?= p($pkjl['ukuranluka'] ?? '-') ?>, Lokasi: <?= p($pkjl['lokasi'] ?? '-') ?>) &nbsp;
        <?= chk($pkjl['laserasijejas'] ?? '', 'tidak') ?> Tidak
    </div>
</div>

<div class="field-row">
    <div class="field-label">Pendarahan</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($pkjl['pendarahandada'] ?? '', 'ada') ?> Ada 
        (Banyak: <?= p($pkjl['jikaadaberapabanyak'] ?? '-') ?>) &nbsp;
        <?= chk($pkjl['pendarahandada'] ?? '', 'tidakada') ?> Tidak Ada
    </div>
</div>

<div class="field-row">
    <div class="field-label">RR</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($pkjl['rr2'] ?? '-') ?> x/menit</div>
</div>

<div class="field-row">
    <div class="field-label">Irama Napas</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($pkjl['iramanapas'] ?? '', 'teratur') ?> Teratur &nbsp;
        <?= chk($pkjl['iramanapas'] ?? '', 'tidakteratur') ?> Tidak Teratur
    </div>
</div>

<div class="field-row">
    <div class="field-label">Otot Bantu Napas</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($pkjl['ototdada'] ?? '', 'ya') ?> Ya &nbsp;
        <?= chk($pkjl['ototdada'] ?? '', 'tidak') ?> Tidak
    </div>
</div>

<div class="field-row">
    <div class="field-label">Bunyi Jantung</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($pkjl['bunyijantung'] ?? '', 'normal') ?> Normal &nbsp;
        <?= chk($pkjl['bunyijantung'] ?? '', 'murmur') ?> Murmur &nbsp;
        <?= chk($pkjl['bunyijantung'] ?? '', 'gallop') ?> Gallop &nbsp;
        <?= chk($pkjl['bunyijantung'] ?? '', 'friction') ?> Friction Rub
    </div>
</div>

<div class="field-row">
    <div class="field-label">Nyeri Dada</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($pkjl['nyeridada'] ?? '', 'ya') ?> Ya (<?= p($pkjl['jikaadanyerijelaskan'] ?? '-') ?>) &nbsp;
        <?= chk($pkjl['nyeridada'] ?? '', 'tidak') ?> Tidak
    </div>
</div>

<div class="field-row">
    <div class="field-label">Keluhan Lain</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($pkjl['keluhanlaindada'] ?? '-') ?></div>
</div>
<div class="subsection-title">Abdomen</div>

<div class="field-row">
    <div class="field-label">Dinding Abdomen</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($pkjl['dindingabdomen'] ?? '', 'simetris') ?> Simetris &nbsp;
        <?= chk($pkjl['dindingabdomen'] ?? '', 'asimetris') ?> Asimetris
    </div>
</div>

<div class="field-row">
    <div class="field-label">Pendarahan</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($pkjl['pendarahanabdomen'] ?? '', 'ya') ?> Ya 
        (Banyak: <?= p($pkjl['jikaadaberapabanyakabdomen'] ?? '-') ?>) &nbsp;
        <?= chk($pkjl['pendarahanabdomen'] ?? '', 'tidak') ?> Tidak
    </div>
</div>

<div class="field-row">
    <div class="field-label">Bengkak</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($pkjl['bengkakabdomen'] ?? '', 'ya') ?> Ya &nbsp;
        <?= chk($pkjl['bengkakabdomen'] ?? '', 'tidak') ?> Tidak
    </div>
</div>

<div class="field-row">
    <div class="field-label">Laserasi</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($pkjl['leserasiabdomen'] ?? '', 'ya') ?> Ya &nbsp;
        <?= chk($pkjl['leserasiabdomen'] ?? '', 'tidak') ?> Tidak
    </div>
</div>

<div class="field-row">
    <div class="field-label">Distensi Abdomen</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($pkjl['distensiabdomen'] ?? '', 'ya') ?> Ya &nbsp;
        <?= chk($pkjl['distensiabdomen'] ?? '', 'tidak') ?> Tidak
    </div>
</div>

<div class="field-row">
    <div class="field-label">Bising Usus</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($pkjl['bisingusus'] ?? '', 'ada') ?> Ada 
        (Frekuensi: <?= p($pkjl['jikadaaberapakali'] ?? '-') ?> kali) &nbsp;
        <?= chk($pkjl['bisingusus'] ?? '', 'tidak') ?> Tidak
    </div>
</div>

<div class="field-row">
    <div class="field-label">Nyeri Tekan</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($pkjl['nyeritekanabdomen'] ?? '', 'ya') ?> Ya &nbsp;
        <?= chk($pkjl['nyeritekanabdomen'] ?? '', 'tidak') ?> Tidak
    </div>
</div>

<div class="field-row">
    <div class="field-label">Keluhan Lain</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($pkjl['keluhanlainabdomen'] ?? '-') ?></div>
</div>
<div class="subsection-title">Ekstremitas Atas dan Bawah</div>

<div class="field-row">
    <div class="field-label">Benjolan/Keras</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($pkjl['terababenjolankeras'] ?? '', 'ya') ?> Ya 
        (Ukuran: <?= p($pkjl['jikaadaukuran'] ?? '-') ?> cm, Lokasi: <?= p($pkjl['lokasibenjolan'] ?? '-') ?>) &nbsp;
        <?= chk($pkjl['terababenjolankeras'] ?? '', 'tidak') ?> Tidak
    </div>
</div>

<div class="field-row">
    <div class="field-label">Pendarahan</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($pkjl['pendarahanekstremitas'] ?? '', 'ya') ?> Ya 
        (Lokasi: <?= p($pkjl['lokasiekstremitas'] ?? '-') ?>, Jumlah: <?= p($pkjl['jumlah'] ?? '-') ?>) &nbsp;
        <?= chk($pkjl['pendarahanekstremitas'] ?? '', 'tidak') ?> Tidak
    </div>
</div>

<div class="field-row">
    <div class="field-label">Edema</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($pkjl['edemaekstremitas'] ?? '', 'ya') ?> Ya &nbsp;
        <?= chk($pkjl['edemaekstremitas'] ?? '', 'tidak') ?> Tidak
    </div>
</div>

<div class="field-row">
    <div class="field-label">Nyeri Tekan</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($pkjl['nyeritekanekstremitas'] ?? '', 'ya') ?> Ya &nbsp;
        <?= chk($pkjl['nyeritekanekstremitas'] ?? '', 'tidak') ?> Tidak
    </div>
</div>

<div class="field-row">
    <div class="field-label">Fraktur</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($pkjl['fraktur'] ?? '', 'ya') ?> Ya (Lokasi: <?= p($pkjl['lokasifraktur'] ?? '-') ?>) &nbsp;
        <?= chk($pkjl['fraktur'] ?? '', 'tidak') ?> Tidak
    </div>
</div>

<div class="field-row">
    <div class="field-label">Kekakuan Sendi</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($pkjl['kekakuan'] ?? '', 'ya') ?> Ya &nbsp;
        <?= chk($pkjl['kekakuan'] ?? '', 'tidak') ?> Tidak
    </div>
</div>

<div class="field-row">
    <div class="field-label">Keterbatasan Gerak</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($pkjl['keterbatasangerak'] ?? '', 'ya') ?> Ya &nbsp;
        <?= chk($pkjl['keterbatasangerak'] ?? '', 'tidak') ?> Tidak
    </div>
</div>

<div class="subsection-title">Kekuatan Otot</div>

<div class="field-row">
    <div class="field-label">Ekstremitas Atas</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($pkjl['ekstremitasatas'] ?? '-') ?></div>
</div>

<div class="field-row">
    <div class="field-label">Ekstremitas Bawah</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($pkjl['ekstremitasbawah'] ?? '-') ?></div>
</div>

<div class="field-row">
    <div class="field-label">Keluhan Lain</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($pkjl['keluhanlainekstremitas'] ?? '-') ?></div>
</div>
<div class="subsection-title">Punggung</div>

<div class="field-row">
    <div class="field-label">Terdapat Luka</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($pkjl['terdapatluka'] ?? '', 'ya') ?> Ya 
        (Ukuran: <?= p($pkjl['ukuranluka2'] ?? '-') ?> cm) &nbsp;
        <?= chk($pkjl['terdapatluka'] ?? '', 'tidak') ?> Tidak
    </div>
</div>

<div class="field-row">
    <div class="field-label">Decubitus</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($pkjl['decubituspunggung'] ?? '', 'ada') ?> Ada 
        (Ukuran: <?= p($pkjl['ukurandecubituspunggung'] ?? '-') ?>) &nbsp;
        <?= chk($pkjl['decubituspunggung'] ?? '', 'tidakada') ?> Tidak Ada
    </div>
</div>

<div class="field-row">
    <div class="field-label">Echymosis/Lebam</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($pkjl['echymosislebampunggung'] ?? '', 'ada') ?> Ada &nbsp;
        <?= chk($pkjl['echymosislebampunggung'] ?? '', 'tidakada') ?> Tidak Ada
    </div>
</div>

<div class="field-row">
    <div class="field-label">Gatal-gatal</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($pkjl['gatalgatal'] ?? '', 'ya') ?> Ya &nbsp;
        <?= chk($pkjl['gatalgatal'] ?? '', 'tidak') ?> Tidak
    </div>
</div>

<div class="field-row">
    <div class="field-label">Keluhan Lain</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($pkjl['keluhanlainpunggung'] ?? '-') ?></div>
</div>

<div class="subsection-title">Kulit</div>

<div class="field-row">
    <div class="field-label">Warna</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($pkjl['kulit'] ?? '-') ?></div>
</div>

<div class="field-row">
    <div class="field-label">Turgor</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($pkjl['turgor'] ?? '', 'elastis') ?> Elastis &nbsp;
        <?= chk($pkjl['turgor'] ?? '', 'menurun') ?> Menurun
    </div>
</div>

<div class="field-row">
    <div class="field-label">Keadaan</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($pkjl['keadaan'] ?? '', 'lembab') ?> Lembab &nbsp;
        <?= chk($pkjl['keadaan'] ?? '', 'kering') ?> Kering
    </div>
</div>

<div class="field-row">
    <div class="field-label">Edema</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($pkjl['edemakulit'] ?? '', 'ya') ?> Ya 
        (Lokasi: <?= p($pkjl['lokasiedemakulit'] ?? '-') ?>) &nbsp;
        <?= chk($pkjl['edemakulit'] ?? '', 'tidak') ?> Tidak
    </div>
</div>

<div class="field-row">
    <div class="field-label">Luka</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($pkjl['lukakulit'] ?? '', 'ya') ?> Ya 
        (Lokasi: <?= p($pkjl['lokasilukakulit'] ?? '-') ?>) &nbsp;
        <?= chk($pkjl['lukakulit'] ?? '', 'tidak') ?> Tidak
    </div>
</div>

<div class="field-row">
    <div class="field-label">Karakteristik Luka</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($pkjl['karakteristikluka'] ?? '-') ?></div>
</div>

<div class="field-row">
    <div class="field-label">Keluhan Lain</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($pkjl['keluhanlainkulit'] ?? '-') ?></div>
</div>

<div class="subsection-title">Genitalia</div>

<div class="field-row">
    <div class="field-label">Radang</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($pkjl['radanggenitalia'] ?? '', 'ya') ?> Ya &nbsp;
        <?= chk($pkjl['radanggenitalia'] ?? '', 'tidak') ?> Tidak
    </div>
</div>

<div class="field-row">
    <div class="field-label">Pembengkakan Skrotum</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($pkjl['pembengkakanskrotum'] ?? '', 'ya') ?> Ya &nbsp;
        <?= chk($pkjl['pembengkakanskrotum'] ?? '', 'tidak') ?> Tidak
    </div>
</div>

<div class="field-row">
    <div class="field-label">Lesi</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($pkjl['lesi'] ?? '', 'ya') ?> Ya &nbsp;
        <?= chk($pkjl['lesi'] ?? '', 'tidak') ?> Tidak
    </div>
</div>

<div class="field-row">
    <div class="field-label">Keluhan Lain</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($pkjl['keluhanlaingenitalia'] ?? '-') ?></div>
</div>

        <!-- ================================ -->
        <!-- BAGIAN 2: FORMAT PENGKAJIAN     -->
        <!-- ================================ -->
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