<?php
// Shortcut per section
$resume_keperawatan    = $sections['resume'] ?? [];
$analisa     = $sections['analisa_resume'] ?? [];
$lainnya_resume          = $sections['lainnya_resume'] ?? [];


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
        <h1>FORMAT RESUME KEPERAWATAN DASAR RSAL</h1>
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
                <td><strong>NIM</strong></td>
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
<h3>1. Biodata Klien</h3>
<div class="field-row">
    <div class="field-label">Nama Klien</div>
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
    <div class="field-label">Status Perkawinan</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($resume_keperawatan['status']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">Pendidikan</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($resume_keperawatan['pendidikan']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">Pekerjaan</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($resume_keperawatan['pekerjaan']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">Alamat</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($resume_keperawatan['alamat']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">Kunjungan Ke</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($resume_keperawatan['kunjungan']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">Diagnosa Medis</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($resume_keperawatan['diagnosa_medis']) ?></div>
</div>

<h3>2. Keluhan Utama</h3>
<div class="field-row">
    <div class="field-value"><?= p($resume_keperawatan['keluhan_utama']) ?></div>
</div>

<h3>3. Riwayat Kesehatan Saat ini</h3>
<div class="field-row">
    <div class="field-value"><?= p($resume_keperawatan['riwayat_keluhan_saat_ini']) ?></div>
</div>

<h3>4. Tanda-tanda Vital</h3>
<div class="field-row">
    <div class="field-label">Tekanan Darah</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($resume_keperawatan['tekanan_darah']) ?> mmHg</div>
</div>
<div class="field-row">
    <div class="field-label">Frekuensi Nadi</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($resume_keperawatan['nadi']) ?> x/mnt</div>
</div>
<div class="field-row">
    <div class="field-label">Suhu Tubuh</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($resume_keperawatan['suhu']) ?> °C</div>
</div>
<div class="field-row">
    <div class="field-label">Frekuensi Pernapasan</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($resume_keperawatan['pernapasan']) ?> x/mnt</div>
</div>

<h3>5. Pemeriksaan Antropometri</h3>
<div class="field-row">
    <div class="field-label">Tinggi Badan</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($resume_keperawatan['tb']) ?> cm</div>
</div>
<div class="field-row">
    <div class="field-label">Berat Badan</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($resume_keperawatan['bb']) ?> kg</div>
</div>
<div class="field-row">
    <div class="field-label">IMT</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($resume_keperawatan['IMT']) ?></div>
</div>

<h3>6. Pemeriksaan Fisik</h3>
<div class="field-row">
    <div class="field-value"><?= p($resume_keperawatan['pemeriksaan_fisik']) ?></div>
</div>

<h3>7. Riwayat Kesehatan yang Lalu</h3>
<div class="field-row">
    <div class="field-value"><?= p($resume_keperawatan['riwayat_kesehatan_yang_lalu']) ?></div>
</div>


        <h3>8. Pemeriksaan Penunjang</h3>
            <h4 class="mt-5">Laboratorium</h4>

        <table class="data">
            <thead>
                <tr>
                    <th>Pemeriksaan</th>
                    <th>Hasil</th>
                    <th>Nilai Normal</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($resume_keperawatan['lab'])): ?>
                    <?php foreach ($resume_keperawatan['lab'] as $lab): ?>
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
            <div class="field-value"><?= p($resume_keperawatan['radiologi']) ?></div>
        </div>
        <div class="field-row">
   <div class="subsection-title">c. EKG</div>
        <?php if (!empty($resume_keperawatan['ekg'])): ?>
            <div style="margin: 6px 0; text-align:center;">
                <img src="<?= cetakGambar($resume_keperawatan['ekg']) ?>" style="max-height:250px; width:auto;" />
            </div>
        <?php else: ?>
            <div style="border:1px solid #ccc; min-height:60px; padding:4px;">-</div>
        <?php endif; ?>

        <div class="field-row">
            <div class="field-label">d.	USG</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($resume_keperawatan['usg']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">e.	CT Scan</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($resume_keperawatan['ct']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">f.	Pemeriksaan Lain (Sebutkan)</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($resume_keperawatan['pemeriksaan']) ?></div>
        </div>

<h3>9. Terapi/Obat</h3>
<div class="field-row">
    <div class="field-value"><?= p($resume_keperawatan['terapi']) ?></div>
</div>

        <!-- ================================ -->
        <!-- SECTION 2: Analisa Data -->
        <!-- ================================ -->

        <h3 class="mt-5">10. Klasifikasi Data</h3>

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

        <h3 class="mt-5">11. Analisa Data</h3>

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
        <!-- SECTION 3: CATATAN KEPERAWATAN -->
        <!-- ================================ -->
        <h3 class="mt-5">10. Diagnosa Keperawatan</h3>

        <table class="data">
            <thead>
                <tr>
                    <th>Diagnosa Keperawatan</th>
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


        <h3 class="mt-5">11. Perencanaan Keperawatan</h3>

        <table class="data">
            <thead>
                <tr>
                    <th width="40%">Diagnosa Keperawatan</th>
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

  

        <h3 class="mt-5">12. Implementasi Keperawatan</h3>

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

        <h3 class="mt-5">13. Evaluasi Keperawatan</h3>

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

    

    </div>
</body>

</html>