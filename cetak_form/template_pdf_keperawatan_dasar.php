<?php
// Shortcut per section
$resume_keperawatan    = $sections['resume_keperawatan'] ?? [];
$analisa     = $sections['analisa_resume'] ?? [];
$lainnya_resume          = $sections['lainnya_resume'] ?? [];
$pkj          = $sections['pengkajian'] ?? [];
$bio1      = $sections['data_biologis_1'] ?? [];
$bio2        = $sections['data_biologis_2'] ?? [];
$kla          = $sections['klasifikasi_analisa_data'] ?? [];
$lainnya        = $sections['lainnya'] ?? [];

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
        <h1>FORMAT RESUME KEPERAWATAN Dasar</h1>
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
<div class="field-row">
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

        <div class="page-break"></div>

        <!-- ================================ -->
        <!-- SECTION 4: Format Laporan Pendahuluan Imunisasi -->
        <!-- ================================ -->
          <!-- ================================ -->
        <!-- IDENTITAS KLIEN                 -->
        <!-- ================================ -->
         <h1>FORMAT LAPORAN ASUHAN KEPERAWATAN</h1>
         <h2>A.	PENGKAJIAN KEPERAWATAN</h2>
        <h3>1. Identitas</h3>
        <div class="subsection-title">Identitas Klien</div>

          <table class="header-table" style="border:1px solid #000;">
    <tr>
        <td width="20%"><strong>Nama Klien</strong></td>
        <td width="2%">:</td>
        <td width="28%"><?= p($pkj['nama_klien']) ?></td>
        <td width="20%"><strong>No. Registrasi</strong></td>
        <td width="2%">:</td>
        <td width="28%"><?= p($pkj['no_registrasi']) ?></td>
    </tr>
    <tr>
        <td><strong>Tempat/Tgl Lahir/Umur</strong></td>
        <td>:</td>
        <td><?= p($pkj['ttl_umur']) ?></td>
        <td><strong>Tgl Masuk RS</strong></td>
        <td>:</td>
        <td><?= p($pkj['tgl_masuk_rs']) ?></td>
    </tr>
    <tr>
        <td><strong>Jenis Kelamin</strong></td>
        <td>:</td>
        <td><?= p($pkj['jenis_kelamin']) ?></td>
        <td><strong>Diagnosa Medik</strong></td>
        <td>:</td>
        <td><?= p($pkj['diagnosa_medis']) ?></td>
    </tr>
    <tr>
        <td><strong>Status Perkawinan</strong></td>
        <td>:</td>
        <td><?= p($pkj['status_perkawinan']) ?></td>
        <td><strong>Golongan Darah</strong></td>
        <td>:</td>
        <td><?= p($pkj['golongan_darah']) ?></td>
    </tr>
    <tr>
        <td><strong>Agama</strong></td>
        <td>:</td>
        <td><?= p($pkj['agama']) ?></td>
        <td><strong>Ruangan</strong></td>
        <td>:</td>
        <td><?= p($pkj['ruangan']) ?></td>
    </tr>
    <tr>
        <td><strong>Pendidikan</strong></td>
        <td>:</td>
        <td><?= p($pkj['pendidikan']) ?></td>
        <td><strong>Alamat</strong></td>
        <td>:</td>
        <td colspan="4"><?= p($pkj['alamat']) ?></td>
    </tr>
</table>

         <table class="header-table" style="border:1px solid #000;">
            <tr>
                <td width="30%"><strong>Nama (Inisial)</strong></td>
                <td width="2%">:</td>
                <td width="18%"><?= p($pkj['nama_klienpj']) ?></td>
                <td width="20%"><strong>Hubungan dengan Klien</strong></td>
                <td width="2%">:</td>
                <td><?= p($pkj['hubungan_klien']) ?></td>
            </tr>
            <tr>
                <td><strong>Tempat/Tgl Lahir/Umur</strong></td>
                <td>:</td>
                <td><?= p($pkj['ttl_umurpj']) ?></td>
                <td><strong>Agama</strong></td>
                <td>:</td>
                <td><?= p($pkj['agamapj']) ?></td>
            </tr>
            <tr>
                <td><strong>Jenis Kelamin</strong></td>
                <td>:</td>
                <td><?= p($pkj['jenis_kelaminpj']) ?></td>
                <td><strong>Pendidikan</strong></td>
                <td>:</td>
                <td><?= p($pkj['pendidikanpj']) ?></td>
            </tr>
            <tr>
                <td><strong>Pekerjaan</strong></td>
                <td>:</td>
                <td><?= p($pkj['pekerjaanpj']) ?></td>
                <td><strong>Alamat</strong></td>
                <td>:</td>
                <td><?= p($pkj['alamatpj']) ?></td>
            </tr>
        </table>

        <!-- ================================ -->
        <!-- KEADAAN UMUM                    -->
        <!-- ================================ -->
        <h3>2. Keadaan Umum</h3>

        <div class="subsection-title">Tanda Vital</div>
        <table class="header-table">
            <tr>
                <td width="30%"><strong>Nadi</strong></td>
                <td width="2%">:</td>
                <td width="18%"><?= p($pkj['nadi']) ?> /menit</td>
                <td width="20%"><strong>Pernafasan</strong></td>
                <td width="2%">:</td>
                <td><?= p($pkj['pernafasan']) ?> x/menit</td>
            </tr>
            <tr>
                <td><strong>TD</strong></td>
                <td>:</td>
                <td><?= p($pkj['td']) ?> mmHg</td>
                <td><strong>Suhu</strong></td>
                <td>:</td>
                <td><?= p($pkj['suhu']) ?></td>
            </tr>
        </table>

        <div class="subsection-title">Kesadaran</div>
        <?php $kesadaran_arr = arr($pkj['kesadaran']); ?>
        <table class="header-table">
            <tr>
                <td width="30%"><strong>GCS</strong></td>
                <td width="2%">:</td>
                <td colspan="4">
                    M: <?= p($pkj['m']) ?> &nbsp; V: <?= p($pkj['v']) ?> &nbsp; E: <?= p($pkj['e']) ?>
                </td>
            </tr>
            <tr>
                <td><strong>Tingkat Kesadaran</strong></td>
                <td>:</td>
                <td colspan="4">
                    <?= chkIn($kesadaran_arr, 'Kompos Mentis') ?> Kompos Mentis &nbsp;
                    <?= chkIn($kesadaran_arr, 'Apatis') ?> Apatis &nbsp;
                    <?= chkIn($kesadaran_arr, 'Somnolent') ?> Somnolent &nbsp;
                    <?= chkIn($kesadaran_arr, 'Stupor') ?> Stupor &nbsp;
                    <?= chkIn($kesadaran_arr, 'Semikoma') ?> Semikoma &nbsp;
                    <?= chkIn($kesadaran_arr, 'Koma') ?> Koma
                </td>
            </tr>
        </table>

        <div class="subsection-title">Antropometri</div>
        <table class="header-table">
            <tr>
                <td width="30%"><strong>BB Sebelum Sakit</strong></td>
                <td width="2%">:</td>
                <td width="18%"><?= p($pkj['bb_sebelum']) ?> kg</td>
                <td width="20%"><strong>BB Saat Sakit</strong></td>
                <td width="2%">:</td>
                <td><?= p($pkj['bb_saat_sakit']) ?> kg</td>
            </tr>
            
            <tr>
               
                <td><strong>Lingkar Lengan Atas</strong></td>
                <td>:</td>
                <td><?= p($pkj['lingkar_lengan']) ?> cm</td>
                <td><strong>IMT</strong></td>
                <td>:</td>
                <td colspan="4"><?= p($pkj['imt']) ?></td>
            </tr>
            
        </table>

        <!-- ================================ -->
        <!-- RIWAYAT KESEHATAN               -->
        <!-- ================================ -->

        <div class="field-row">
            <div class="field-label">Alasan Masuk Rumah Sakit</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($pkj['alasan_masuk_rs']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Keluhan Utama</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($pkj['keluhan_utama']) ?></div>
        </div>
      
        <div class="field-row">
            <div class="field-label">Riwayat Penyakit Sekarang</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($pkj['riwayat_penyakit_sekarang']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Riwayat dan Kecelakaan Yang Pernah Dialami</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($pkj['riwayat_pernah_dialami']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Riwayat Kesehatan Keluarga</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($pkj['riwayat_kesehatan_keluarga']) ?></div>
        </div>

        <div class="field-row">
            <div class="field-label">Genogram</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($pkj['genogram']) ?></div>
        </div>



        <div class="subsection-title">Pola Nutrisi</div>
<table class="data">
    <thead>
        <tr>
            <th>No.</th><th>Kondisi</th><th>Sebelum Sakit</th><th>Saat Ini</th>
        </tr>
    </thead>
    <tbody>
        <tr><td>1</td><td>Frekuensi Makan</td><td><?= p($bio1['frekuensi_makan_sebelum'] ?? '-') ?></td><td><?= p($bio1['frekuensi_makan_sekarang'] ?? '-') ?></td></tr>
        <tr><td>2</td><td>Selera Makan</td><td><?= p($bio1['selera_makan_sebelum'] ?? '-') ?></td><td><?= p($bio1['selera_makan_sekarang'] ?? '-') ?></td></tr>
        <tr><td>3</td><td>Menu Makanan</td><td><?= p($bio1['menu_makan_sebelum'] ?? '-') ?></td><td><?= p($bio1['menu_makan_sekarang'] ?? '-') ?></td></tr>
        <tr><td>4</td><td>Ritual Saat Makan</td><td><?= p($bio1['ritual_makan_sebelum'] ?? '-') ?></td><td><?= p($bio1['ritual_makan_sekarang'] ?? '-') ?></td></tr>
        <tr><td>5</td><td>Bantuan Makan</td><td><?= p($bio1['bantuan_makan_sebelum'] ?? '-') ?></td><td><?= p($bio1['bantuan_makan_sekarang'] ?? '-') ?></td></tr>
    </tbody>
</table>

<div class="subsection-title">Cairan</div>
<table class="data">
    <thead><tr><th>No.</th><th>Kondisi</th><th>Sebelum Sakit</th><th>Saat Ini</th></tr></thead>
    <tbody>
        <tr><td>1</td><td>Jenis Minuman</td><td><?= p($bio1['jenis_minum_sebelum'] ?? '-') ?></td><td><?= p($bio1['jenis_minum_sekarang'] ?? '-') ?></td></tr>
        <tr><td>2</td><td>Jumlah Cairan</td><td><?= p($bio1['jumlah_cairan_sebelum'] ?? '-') ?></td><td><?= p($bio1['jumlah_cairan_sekarang'] ?? '-') ?></td></tr>
        <tr><td>3</td><td>Bantuan Cairan</td><td><?= p($bio1['bantuan_cairan_sebelum'] ?? '-') ?></td><td><?= p($bio1['bantuan_cairan_sekarang'] ?? '-') ?></td></tr>
    </tbody>
</table>

<div class="subsection-title">Pola Eliminasi BAB</div>
<table class="data">
    <thead><tr><th>No.</th><th>Kondisi</th><th>Sebelum Sakit</th><th>Saat Ini</th></tr></thead>
    <tbody>
        <tr><td>1</td><td>Frekuensi (Waktu)</td><td><?= p($bio1['bab_frekuensi_sebelum'] ?? '-') ?></td><td><?= p($bio1['bab_frekuensi_sekarang'] ?? '-') ?></td></tr>
        <tr><td>2</td><td>Konsistensi</td><td><?= p($bio1['bab_konsistensi_sebelum'] ?? '-') ?></td><td><?= p($bio1['bab_konsistensi_sekarang'] ?? '-') ?></td></tr>
        <tr><td>3</td><td>Warna</td><td><?= p($bio1['bab_warna_sebelum'] ?? '-') ?></td><td><?= p($bio1['bab_warna_sekarang'] ?? '-') ?></td></tr>
        <tr><td>4</td><td>Bau</td><td><?= p($bio1['bab_bau_sebelum'] ?? '-') ?></td><td><?= p($bio1['bab_bau_sekarang'] ?? '-') ?></td></tr>
        <tr><td>5</td><td>Kesulitan BAB</td><td><?= p($bio1['bab_kesulitan_sebelum'] ?? '-') ?></td><td><?= p($bio1['bab_kesulitan_sekarang'] ?? '-') ?></td></tr>
        <tr><td>6</td><td>Obat Pencahar</td><td><?= p($bio1['bab_obat_sebelum'] ?? '-') ?></td><td><?= p($bio1['bab_obat_sekarang'] ?? '-') ?></td></tr>
    </tbody>
</table>

<div class="subsection-title">Pola Eliminasi BAK</div>
<table class="data">
    <thead><tr><th>No.</th><th>Kondisi</th><th>Sebelum Sakit</th><th>Saat Ini</th></tr></thead>
    <tbody>
        <tr><td>1</td><td>Frekuensi (Waktu)</td><td><?= p($bio1['bak_frekuensi_sebelum'] ?? '-') ?></td><td><?= p($bio1['bak_frekuensi_sekarang'] ?? '-') ?></td></tr>
        <tr><td>2</td><td>Warna</td><td><?= p($bio1['bak_warna_sebelum'] ?? '-') ?></td><td><?= p($bio1['bak_warna_sekarang'] ?? '-') ?></td></tr>
        <tr><td>3</td><td>Bau</td><td><?= p($bio1['bak_bau_sebelum'] ?? '-') ?></td><td><?= p($bio1['bak_bau_sekarang'] ?? '-') ?></td></tr>
        <tr><td>4</td><td>Kesulitan BAK</td><td><?= p($bio1['bak_kesulitan_sebelum'] ?? '-') ?></td><td><?= p($bio1['bak_kesulitan_sekarang'] ?? '-') ?></td></tr>
        <tr><td>5</td><td>Obat Diuretik</td><td><?= p($bio1['bak_obat_sebelum'] ?? '-') ?></td><td><?= p($bio1['bak_obat_sekarang'] ?? '-') ?></td></tr>
    </tbody>
</table>

<div class="subsection-title">Pola Tidur</div>
<table class="data">
    <thead><tr><th>No.</th><th>Kondisi</th><th>Sebelum Sakit</th><th>Saat Ini</th></tr></thead>
    <tbody>
        <tr><td>1</td><td>Tidur Siang</td><td><?= p($bio1['tidur_siang_sebelum'] ?? '-') ?></td><td><?= p($bio1['tidur_siang_sekarang'] ?? '-') ?></td></tr>
        <tr><td>2</td><td>Tidur Malam</td><td><?= p($bio1['tidur_malam_sebelum'] ?? '-') ?></td><td><?= p($bio1['tidur_malam_sekarang'] ?? '-') ?></td></tr>
        <tr><td>3</td><td>Kesulitan Tidur</td><td><?= p($bio1['kesulitan_tidur_sebelum'] ?? '-') ?></td><td><?= p($bio1['kesulitan_tidur_sekarang'] ?? '-') ?></td></tr>
        <tr><td>4</td><td>Kebiasaan Tidur</td><td><?= p($bio1['kebiasaan_tidur_sebelum'] ?? '-') ?></td><td><?= p($bio1['kebiasaan_tidur_sekarang'] ?? '-') ?></td></tr>
    </tbody>
</table>

<div class="subsection-title">Pola Personal Hygiene</div>
<table class="data">
    <thead><tr><th>No.</th><th>Kondisi</th><th>Sebelum Sakit</th><th>Saat Ini</th></tr></thead>
    <tbody>
        <tr><td>1</td><td>Mandi</td><td><?= p($bio1['mandi_frekuensi_sebelum'] ?? '-') ?> / <?= p($bio1['mandi_cara_sebelum'] ?? '-') ?> / <?= p($bio1['mandi_tempat_sebelum'] ?? '-') ?></td><td><?= p($bio1['mandi_frekuensi_sekarang'] ?? '-') ?> / <?= p($bio1['mandi_cara_sekarang'] ?? '-') ?> / <?= p($bio1['mandi_tempat_sekarang'] ?? '-') ?></td></tr>
        <tr><td>2</td><td>Cuci Rambut</td><td><?= p($bio1['rambut_frekuensi_sebelum'] ?? '-') ?> / <?= p($bio1['rambut_cara_sebelum'] ?? '-') ?></td><td><?= p($bio1['rambut_frekuensi_sekarang'] ?? '-') ?> / <?= p($bio1['rambut_cara_sekarang'] ?? '-') ?></td></tr>
        <tr><td>3</td><td>Gunting Kuku</td><td><?= p($bio1['kuku_frekuensi_sebelum'] ?? '-') ?> / <?= p($bio1['kuku_cara_sebelum'] ?? '-') ?></td><td><?= p($bio1['kuku_frekuensi_sekarang'] ?? '-') ?> / <?= p($bio1['kuku_cara_sekarang'] ?? '-') ?></td></tr>
        <tr><td>4</td><td>Gosok Gigi</td><td><?= p($bio1['gigi_frekuensi_sebelum'] ?? '-') ?> / <?= p($bio1['gigi_cara_sebelum'] ?? '-') ?></td><td><?= p($bio1['gigi_frekuensi_sekarang'] ?? '-') ?> / <?= p($bio1['gigi_cara_sekarang'] ?? '-') ?></td></tr>
    </tbody>
</table>

        <div class="page-break"></div>

        <!-- ================================ -->
        <!-- DATA BIOLOGIS 1: KEPALA - LEHER -->
        <!-- ================================ -->
         <h3>5. Data Biologis</h3>
         <div class="subsection-title">1. Rambut</div>
<div class="field-row">
    <div class="field-label">Penyebaran Merata</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($bio1['penyebaran_merata'], 'ya') ?> Ya &nbsp; 
        <?= chk($bio1['penyebaran_merata'], 'tidak') ?> Tidak &nbsp; 
        (Warna: <?= p($bio1['warna_penyebaran']) ?>)
    </div>
</div>
<div class="field-row">
    <div class="field-label">Mudah Dicabut</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= chk($bio1['rambut_dicabut'], 'ya') ?> Ya &nbsp; <?= chk($bio1['rambut_dicabut'], 'tidak') ?> Tidak</div>
</div>
<div class="field-row">
    <div class="field-label">Lain-lain</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($bio1['kelainan_rambut']) ?></div>
</div>

<div class="subsection-title">2. Wajah</div>
<div class="field-row">
    <div class="field-label">Ekspresi Wajah</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($bio1['ekspresi_wajah']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">Terdapat Udema</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= chk($bio1['udema_wajah'], 'ya') ?> Ya &nbsp; <?= chk($bio1['udema_wajah'], 'tidak') ?> Tidak</div>
</div>
<div class="field-row">
    <div class="field-label">Lain-lain</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($bio1['kelainan_wajah']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">Penglihatan</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($bio1['penglihatan'], 'jelas') ?> Jelas &nbsp; 
        <?= chk($bio1['penglihatan'], 'kabur') ?> Kabur &nbsp; 
        <?= chk($bio1['penglihatan'], 'rabun') ?> Rabun &nbsp; 
        <?= chk($bio1['penglihatan'], 'berkunang') ?> Berkunang
    </div>
</div>
<div class="field-row">
    <div class="field-label">Visus (Kanan / Kiri)</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($bio1['visus_kanan']) ?> / <?= p($bio1['visus_kiri']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">Lapang Pandang</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($bio1['lapang_pandang']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">Keadaan Mata</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($bio1['keadaan_mata']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">Lesi</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= chk($bio1['lesi_mata'], 'ada') ?> Ada &nbsp; <?= chk($bio1['lesi_mata'], 'tidak') ?> Tidak</div>
</div>


<div class="field-row">
    <div class="field-label">Sclera</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($bio1['sclera'], 'sclera_sclera_anikterikikterik') ?> An-Ikterik &nbsp; 
        <?= chk($bio1['sclera'], 'sclera_ikterik') ?> Ikterik
    </div>
</div>
<div class="field-row">
    <div class="field-label">Bola Mata</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= chk($bio1['bola_mata'], 'simetris') ?> Simetris &nbsp; <?= chk($bio1['bola_mata'], 'tidak') ?> Tidak</div>
</div>
<div class="field-row">
    <div class="field-label">Lain-lain</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($bio1['kelainan_mata']) ?></div>
</div>

<div class="subsection-title">4. Telinga</div>
<div class="field-row">
    <div class="field-label">Pendengaran (Kiri / Kanan)</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        Kiri: <?= p($bio1['pendengaran_kiri']) ?> &nbsp; | &nbsp; 
        Kanan: <?= p($bio1['pendengaran_kanan']) ?>
    </div>
</div>
<div class="field-row">
    <div class="field-label">Nyeri Kiri</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($bio1['nyeri_Kiri'], 'ada') ?> Ada &nbsp; 
        <?= chk($bio1['nyeri_Kiri'], 'tidak') ?> Tidak
    </div>
</div>

<div class="field-row">
    <div class="field-label">Nyeri Kanan</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($bio1['nyeri_kanan'], 'ada') ?> Ada &nbsp; 
        <?= chk($bio1['nyeri_kanan'], 'tidak') ?> Tidak
    </div>
</div>
<div class="field-row">
    <div class="field-label">Serumen</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= chk($bio1['serumen'], 'ada') ?> Ada &nbsp; <?= chk($bio1['serumen'], 'tidak') ?> Tidak</div>
</div>
<div class="field-row">
    <div class="field-label">Lain-lain</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($bio1['kelainan_telinga']) ?></div>
</div>

<div class="subsection-title">5. Hidung</div>
<div class="field-row">
    <div class="field-label">Membedakan Bau</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= chk($bio1['bau'], 'dapat') ?> Dapat &nbsp; <?= chk($bio1['bau'], 'tidak') ?> Tidak</div>
</div>
<div class="field-row">
    <div class="field-label">Sekresi / Warna</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($bio1['sekresi']) ?> / <?= p($bio1['warna_hidung']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">Mukosa</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($bio1['mukosa_hidung']) ?></div>
</div>
<div class="field-row">
    <div class="field-label">Pembengkakan</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= chk($bio1['pembengkakan'], 'ya') ?> Ya &nbsp; <?= chk($bio1['pembengkakan'], 'tidak') ?> Tidak</div>
</div>
<div class="field-row">
    <div class="field-label">Pernafasan Cuping Hidung</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= chk($bio1['cuping_hidung'], 'ya') ?> Ya &nbsp; <?= chk($bio1['cuping_hidung'], 'tidak') ?> Tidak</div>
</div>
<div class="field-row">
    <div class="field-label">Lain-lain</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($bio1['kelainan_hidung']) ?></div>
</div>
<div class="subsection-title">6. Mulut</div>

<div class="field-row">
    <div class="field-label">Bibir</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($bio1['bibir']) ?></div>
</div>

<div class="field-row">
    <div class="field-label">Simetris</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($bio1['simetris'], 'ya') ?> Ya &nbsp; 
        <?= chk($bio1['simetris'], 'tidak') ?> Tidak
    </div>
</div>

<div class="field-row">
    <div class="field-label">Kelembaban</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($bio1['kelembaban'], 'basah') ?> Basah &nbsp; 
        <?= chk($bio1['kelembaban'], 'kering') ?> Kering &nbsp; 
        <?= chk($bio1['kelembaban'], 'lesi') ?> Lesi
    </div>
</div>

<div class="field-row">
    <div class="field-label">Caries</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($bio1['caries'], 'ada') ?> Ada &nbsp; 
        <?= chk($bio1['caries'], 'tidak') ?> Tidak
    </div>
</div>

<div class="field-row">
    <div class="field-label">Jumlah Gigi</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($bio1['jumlah_gigi']) ?></div>
</div>

<div class="field-row">
    <div class="field-label">Warna Gigi</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($bio1['warna_gigi']) ?></div>
</div>

<div class="field-row">
    <div class="field-label">Gigi Palsu</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($bio1['gigi_palsu_jumlah']) ?> buah, Letak: <?= p($bio1['letak']) ?></div>
</div>

<div class="field-row">
    <div class="field-label">Lidah</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($bio1['lidah']) ?></div>
</div>

<div class="field-row">
    <div class="field-label">Lesi Lidah</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($bio1['lesi_lidah'], 'ada') ?> Ada &nbsp; 
        <?= chk($bio1['lesi_lidah'], 'tidak') ?> Tidak
    </div>
</div>

<div class="field-row">
    <div class="field-label">Sensasi Rasa</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        Panas/Dingin: <?= chk($bio1['panas/dingin'], 'ada') ?> Ada / <?= chk($bio1['panas/dingin'], 'tidak') ?> Tidak <br>
        Asam/Pahit: <?= chk($bio1['asampahit'], 'ada') ?> Ada / <?= chk($bio1['asampahit'], 'tidak') ?> Tidak <br>
        Manis: <?= chk($bio1['manis'], 'ada') ?> Ada / <?= chk($bio1['manis'], 'tidak') ?> Tidak
    </div>
</div>

<div class="field-row">
    <div class="field-label">Refleks Mengunyah</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($bio1['refleks'], 'dapat') ?> Dapat &nbsp; 
        <?= chk($bio1['refleks'], 'tidak') ?> Tidak
    </div>
</div>

<div class="field-row">
    <div class="field-label">Pembesaran Tonsil</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($bio1['tonsil'], 'ya') ?> Ya &nbsp; 
        <?= chk($bio1['tonsil'], 'tidak') ?> Tidak
    </div>
</div>

<div class="field-row">
    <div class="field-label">Bau Mulut</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chkIn($bio1['bau_mulut'], 'uranium') ?> Uranium &nbsp;
        <?= chkIn($bio1['bau_mulut'], 'amoniak') ?> Amoniak &nbsp;
        <?= chkIn($bio1['bau_mulut'], 'aceton') ?> Aceton &nbsp;
        <?= chkIn($bio1['bau_mulut'], 'busuk') ?> Busuk &nbsp;
        <?= chkIn($bio1['bau_mulut'], 'alkohol') ?> Alkohol
    </div>
</div>

<div class="field-row">
    <div class="field-label">Sekret Mulut</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($bio1['sekret_mulut'], 'ada') ?> Ada / <?= chk($bio1['sekret_mulut'], 'tidak') ?> Tidak <br>
        Warna: <?= p($bio1['sekret_mulut_warna']) ?>
    </div>
</div><div class="subsection-title">7. Leher</div>

<div class="field-row">
    <div class="field-label">Bentuk Simetris</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($bio1['leher_simetris'], 'ya') ?> Ya &nbsp; 
        <?= chk($bio1['leher_simetris'], 'tidak') ?> Tidak
    </div>
</div>

<div class="field-row">
    <div class="field-label">Pembesaran Kelenjar</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($bio1['kelenjar'], 'ada') ?> Ada &nbsp; 
        <?= chk($bio1['kelenjar'], 'tidak') ?> Tidak
    </div>
</div>

<div class="field-row">
    <div class="field-label">Peninggian JVP</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($bio1['jvp'], 'ada') ?> Ada &nbsp; 
        <?= chk($bio1['jvp'], 'tidak') ?> Tidak
    </div>
</div>

<div class="field-row">
    <div class="field-label">Refleks Menelan</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($bio1['refleks_menelan'], 'dapat') ?> Dapat &nbsp; 
        <?= chk($bio1['refleks_menelan'], 'tidak') ?> Tidak
    </div>
</div>

<div class="field-row">
    <div class="field-label">Kelainan</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($bio1['kelainan_leher']) ?></div>
</div>
        <!-- DATA BIOLOGIS 2 -->
       <div class="subsection-title">8. Thorax</div>
<div class="subsection-subtitle">a. Dada</div>

<div class="field-row">
    <div class="field-label">Bentuk Dada</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($bio2['bentuk_dada']) ?></div>
</div>

<div class="field-row">
    <div class="field-label">Pengembangan Dada</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($bio2['pengembangan_dada']) ?></div>
</div>

<div class="field-row">
    <div class="field-label">Perbandingan ukuran anterior-posterior dengan transversal</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($bio2['perbandingan_dada']) ?></div>
</div>

<div class="field-row">
    <div class="field-label">Penggunaan Otot Pernafasan Tambahan</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($bio2['otot_pernafasan'], 'ya') ?> Ya &nbsp; 
        <?= chk($bio2['otot_pernafasan'], 'tidak') ?> Tidak
    </div>
</div>
<div class="subsection-subtitle">b. Paru</div>

<div class="field-row">
    <div class="field-label">Suara Nafas</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($bio2['suara_nafas_uraian']) ?></div>
</div>

<div class="field-row">
    <div class="field-label">Bunyi Nafas Abnormal</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($bio2['bunyi_abnormal'], 'wheezing') ?> Wheezing &nbsp; 
        <?= chk($bio2['bunyi_abnormal'], 'ronchi') ?> Ronchi &nbsp; 
        Lainnya: <?= p($bio2['abnormal']) ?>
    </div>
</div>

<div class="field-row">
    <div class="field-label">Frekuensi Nafas</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= p($bio2['frekuensi_nafas']) ?> x/menit, 
        <?= chk($bio2['teratur_nafas'], 'teratur') ?> Teratur &nbsp; 
        <?= chk($bio2['teratur_nafas'], 'tidak') ?> Tidak
    </div>
</div>

<div class="field-row">
    <div class="field-label">Irama Pernafasan</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($bio2['irama_nafas'], 'dangkal') ?> Dangkal &nbsp; 
        <?= chk($bio2['irama_nafas'], 'dalam') ?> Dalam
    </div>
</div>

<div class="field-row">
    <div class="field-label">Kesukaran Bernafas</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($bio2['sesak_nafas'], 'ya') ?> Ya &nbsp; 
        <?= chk($bio2['sesak_nafas'], 'tidak') ?> Tidak
    </div>
</div>

<div class="field-row">
    <div class="field-label">Bunyi Perkusi Paru</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($bio2['perkusi_paru']) ?></div>
</div>

<div class="field-row">
    <div class="field-label">Taktil Fremitus</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($bio2['taktil_fremitus']) ?></div>
</div>

<div class="field-row">
    <div class="field-label">Kelainan Paru</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($bio2['kelainan_paru']) ?></div>
</div>

<div class="subsection-subtitle">c. Jantung</div>

<div class="field-row">
    <div class="field-label">S1 / Terdapat pada</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($bio2['s1_jantung']) ?> / <?= p($bio2['terdapat_pada_s1']) ?></div>
</div>

<div class="field-row">
    <div class="field-label">S2 / Terdapat pada</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($bio2['s2_jantung']) ?> / <?= p($bio2['terdapat_pada_s2']) ?></div>
</div>

<div class="field-row">
    <div class="field-label">Bunyi Teratur</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($bio2['bunyi_jantung'], 'ya') ?> Ya &nbsp; 
        <?= chk($bio2['bunyi_jantung'], 'tidak') ?> Tidak
    </div>
</div>

<div class="field-row">
    <div class="field-label">Bunyi Tambahan</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($bio2['bunyi_tambahan_jantung'], 'murmur') ?> Murmur &nbsp; 
        <?= chk($bio2['bunyi_tambahan_jantung'], 'tidak') ?> Tidak
    </div>
</div>

<div class="field-row">
    <div class="field-label">Pulsasi Jantung</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($bio2['pulsasi_jantung']) ?></div>
</div>

<div class="field-row">
    <div class="field-label">Irama</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($bio2['irama_jantung'], 'teratur') ?> Teratur &nbsp; 
        <?= chk($bio2['irama_jantung'], 'tidak_teratur') ?> Tidak Teratur
    </div>
</div>

<div class="field-row">
    <div class="field-label">Kelainan Jantung</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($bio2['kelainan_jantung']) ?></div>
</div>
<div class="subsection-title">9. Abdomen</div>
<?php $bentuk_abd = arr($bio2['bentuk_abdomen']);
        $keadaan_abd = arr($bio2['keadaan_abdomen']); ?>
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
    <div class="field-label">Bising Usus</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($bio2['bising_usus'], 'ada') ?> Ada / 
        <?= chk($bio2['bising_usus'], 'tidak') ?> Tidak 
        (<?= p($bio2['bising_usus_kali']) ?> kali/menit)
    </div>
</div>

<div class="field-row">
    <div class="field-label">Benjolan</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($bio2['benjolan_abdomen'], 'ada') ?> Ada / 
        <?= chk($bio2['benjolan_abdomen'], 'tidak') ?> Tidak 
        (Letak: <?= p($bio2['benjolan_letak']) ?>)
    </div>
</div>

<div class="field-row">
    <div class="field-label">Nyeri Tekan</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($bio2['nyeri_abdomen'], 'ada') ?> Ada / 
        <?= chk($bio2['nyeri_abdomen'], 'tidak') ?> Tidak 
        (Letak: <?= p($bio2['nyeri_letak']) ?>)
    </div>
</div>

<div class="field-row">
    <div class="field-label">Kelainan Abdomen</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($bio2['kelainan_abdomen']) ?></div>
</div>
<div class="subsection-title">10. Genetalia</div>

<div class="field-row">
    <div class="field-label">Bentuk</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($bio2['bentuk_genetalia'], 'utuh') ?> Utuh &nbsp; 
        <?= chk($bio2['bentuk_genetalia'], 'tidak') ?> Tidak
    </div>
</div>

<div class="field-row">
    <div class="field-label">Radang</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($bio2['radang_genetalia'], 'ada') ?> Ada &nbsp; 
        <?= chk($bio2['radang_genetalia'], 'tidak') ?> Tidak
    </div>
</div>

<div class="field-row">
    <div class="field-label">Sekret</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($bio2['sekret_genetalia'], 'ada') ?> Ada &nbsp; 
        <?= chk($bio2['sekret_genetalia'], 'tidak') ?> Tidak
    </div>
</div>

<div class="field-row">
    <div class="field-label">Pembengkakan Skrotum</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($bio2['skrotum_bengkak'], 'ada') ?> Ada &nbsp; 
        <?= chk($bio2['skrotum_bengkak'], 'tidak') ?> Tidak
    </div>
</div>

<div class="field-row">
    <div class="field-label">Rektum</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($bio2['rektum_benjolan'], 'benjolan') ?> Benjolan &nbsp; 
        <?= chk($bio2['rektum_benjolan'], 'tidak') ?> Tidak
    </div>
</div>

<div class="field-row">
    <div class="field-label">Lain-lain</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($bio2['kelainan_ekstremitas_atas']) ?></div>
</div>
<div class="subsection-title">11. Ekstremitas Atas</div>

<div class="field-row">
    <div class="field-label">Simetris</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= chk($bio2['atas_simetris'], 'ya') ?> Ya &nbsp; <?= chk($bio2['atas_simetris'], 'tidak') ?> Tidak</div>
</div>

<div class="field-row">
    <div class="field-label">Sensasi</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        Halus: <?= chk($bio2['sensasi_halus'], 'ada') ?> Ada &nbsp; <?= chk($bio2['sensasi_halus'], 'tidak') ?> Tidak <br>
        Tajam: <?= chk($bio2['sensasi_tajam'], 'ada') ?> Ada &nbsp; <?= chk($bio2['sensasi_tajam'], 'tidak') ?> Tidak <br>
        Panas: <?= chk($bio2['sensasi_panas'], 'ada') ?> Ada &nbsp; <?= chk($bio2['sensasi_panas'], 'tidak') ?> Tidak <br>
        Dingin: <?= chk($bio2['sensasi_dingin'], 'ada') ?> Ada &nbsp; <?= chk($bio2['sensasi_dingin'], 'tidak') ?> Tidak
    </div>
</div>

<div class="field-row">
    <div class="field-label">Gerakan ROM</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= chk($bio2['rom_atas'], 'dapat') ?> Dapat &nbsp; <?= chk($bio2['rom_atas'], 'tidak') ?> Tidak</div>
</div>
<div class="field-row">
    <div class="field-label">Refleks Bisep</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($bio2['refleks_bisep'], 'ada') ?> Ada &nbsp; 
        <?= chk($bio2['refleks_bisep'], 'tidak') ?> Tidak
    </div>
</div>

<div class="field-row">
    <div class="field-label">Refleks Trisep</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($bio2['refleks_trisep'], 'ada') ?> Ada &nbsp; 
        <?= chk($bio2['refleks_trisep'], 'tidak') ?> Tidak
    </div>
</div>

<div class="field-row">
    <div class="field-label">Pembengkakan</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($bio2['pembengkakan_atas'], 'ya') ?> Ya &nbsp; 
        <?= chk($bio2['pembengkakan_atas'], 'tidak') ?> Tidak
    </div>
</div>

<div class="field-row">
    <div class="field-label">Kelembaban</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($bio2['kelembaban_atas'], 'lembab') ?> Lembab &nbsp; 
        <?= chk($bio2['kelembaban_atas'], 'kering') ?> Kering
    </div>
</div>

<div class="field-row">
    <div class="field-label">Temperatur</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($bio2['temperatur_atas'], 'panas') ?> Panas &nbsp; 
        <?= chk($bio2['temperatur_atas'], 'dingin') ?> Dingin
    </div>
</div>
<div class="field-row">
    <div class="field-label">Kekuatan Otot</div>
    <div class="field-sep">:</div>
    <div class="field-value">Kanan: <?= p($bio2['otot_tangan_kanan']) ?> | Kiri: <?= p($bio2['otot_tangan_kiri']) ?></div>
</div>

<div class="field-row">
    <div class="field-label">Lain-lain</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($bio2['kelainan_ekstremitas_bawah']) ?></div>
</div>
       
<div class="subsection-title">12. Ekstremitas Bawah</div>

<div class="field-row">
    <div class="field-label">Bentuk Simetris</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= chk($bio2['bawah_simetris'], 'ya') ?> Ya &nbsp; <?= chk($bio2['bawah_simetris'], 'tidak') ?> Tidak</div>
</div>

<div class="field-row">
    <div class="field-label">Sensasi</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        Halus: <?= chk($bio2['bawah_sensasi_halus'], 'ada') ?> Ada &nbsp; <?= chk($bio2['bawah_sensasi_halus'], 'tidak') ?> Tidak <br>
        Tajam: <?= chk($bio2['bawah_sensasi_tajam'], 'ada') ?> Ada &nbsp; <?= chk($bio2['bawah_sensasi_tajam'], 'tidak') ?> Tidak <br>
        Panas: <?= chk($bio2['bawah_sensasi_panas'], 'ada') ?> Ada &nbsp; <?= chk($bio2['bawah_sensasi_panas'], 'tidak') ?> Tidak <br>
        Dingin: <?= chk($bio2['bawah_sensasi_dingin'], 'ada') ?> Ada &nbsp; <?= chk($bio2['bawah_sensasi_dingin'], 'tidak') ?> Tidak
    </div>
</div>

<div class="field-row">
    <div class="field-label">Pembengkakan</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= chk($bio2['pembengkakan_bawah'], 'ya') ?> Ya &nbsp; <?= chk($bio2['pembengkakan_bawah'], 'tidak') ?> Tidak</div>
</div>

<div class="field-row">
    <div class="field-label">Gerakan ROM</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= chk($bio2['rom_bawah'], 'dapat') ?> Dapat &nbsp; <?= chk($bio2['rom_bawah'], 'tidak') ?> Tidak</div>
</div>

<div class="field-row">
    <div class="field-label">Refleks Lipat Paha</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= chk($bio2['refleks_babinski'], 'ada') ?> Ada &nbsp; <?= chk($bio2['refleks_babinski'], 'tidak') ?> Tidak</div>
</div>

<div class="field-row">
    <div class="field-label">Varises</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= chk($bio2['varises_bawah'], 'ada') ?> Ada &nbsp; <?= chk($bio2['varises_bawah'], 'tidak') ?> Tidak</div>
</div>

<div class="field-row">
    <div class="field-label">Kelembaban</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= chk($bio2['kelembaban_bawah'], 'lembab') ?> Lembab &nbsp; <?= chk($bio2['kelembaban_bawah'], 'kering') ?> Kering</div>
</div>

<div class="field-row">
    <div class="field-label">Temperatur</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= chk($bio2['temperatur_bawah'], 'panas') ?> Panas &nbsp; <?= chk($bio2['temperatur_bawah'], 'dingin') ?> Dingin</div>
</div>

<div class="field-row">
    <div class="field-label">Kekuatan Otot</div>
    <div class="field-sep">:</div>
    <div class="field-value">Kanan: <?= p($bio2['otot_kaki_kanan']) ?> | Kiri: <?= p($bio2['otot_kaki_kiri']) ?></div>
</div>

<div class="field-row">
    <div class="field-label">Lain-lain</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= p($bio2['kelainan_genetalia']) ?></div>
</div>
<div class="field-row">
    <div class="field-label"><strong>13. Kulit</strong></div>
</div>

<div class="field-row">
    <div class="field-label">Warna</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= htmlspecialchars($bio2['warna_kulit'] ?? '-') ?></div>
</div>

<div class="field-row">
    <div class="field-label">Turgor</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= chk($bio2['turgor_kulit'], 'elastis') ?> Elastis &nbsp; <?= chk($bio2['turgor_kulit'], 'menurun') ?> Menurun</div>
</div>

<div class="field-row">
    <div class="field-label">Kelembaban</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= chk($bio2['kelembaban_kulit'], 'lembab') ?> Lembab &nbsp; <?= chk($bio2['kelembaban_kulit'], 'kering') ?> Kering</div>
</div>

<div class="field-row">
    <div class="field-label">Edema</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($bio2['edema_kulit'], 'ada') ?> Ada &nbsp; <?= chk($bio2['edema_kulit'], 'tidak') ?> Tidak 
        (Pada Daerah: <?= htmlspecialchars($bio2['pada_daerah'] ?? '-') ?>)
    </div>
</div>

<div class="field-row">
    <div class="field-label">Luka</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($bio2['luka_kulit'], 'ada') ?> Ada &nbsp; <?= chk($bio2['luka_kulit'], 'tidak') ?> Tidak 
          (Pada Daerah: <?= htmlspecialchars($bio2['karakteristik_luka'] ?? '-') ?>)
    </div>
</div>

<div class="field-row">
    <div class="field-label">Tekstur</div>
    <div class="field-sep">:</div>
    <div class="field-value">
        <?= chk($bio2['tekstur_kulit'], 'licin') ?> Licin &nbsp; 
        <?= chk($bio2['tekstur_kulit'], 'keriput') ?> Keriput &nbsp; 
        <?= chk($bio2['tekstur_kulit'], 'kasar') ?> Kasar
    </div>
</div>

<div class="field-row">
    <div class="field-label">Kelainan Lain</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= htmlspecialchars($bio2['kelainan_kulit'] ?? '-') ?></div>
</div>

<div class="field-row">
    <div class="field-label"><strong>14. Kuku</strong></div>
</div>

<div class="field-row">
    <div class="field-label">Clubbing Finger</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= chk($bio2['clubbing_finger'], 'ya') ?> Ya &nbsp; <?= chk($bio2['clubbing_finger'], 'tidak') ?> Tidak</div>
</div>

<div class="field-row">
    <div class="field-label">CRT (Capillary Refill)</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= htmlspecialchars($bio2['capillary_refill_time'] ?? '-') ?></div>
</div>

<div class="field-row">
    <div class="field-label"><strong>f. Data Penunjang</strong></div>
</div>

<div class="field-row">
    <div class="field-label">Lab/Radiologi</div>
    <div class="field-sep">:</div>
    <div class="field-value"><?= htmlspecialchars($bio2['lab'] ?? '-') ?></div>
</div>
   

        <div class="page-break"></div>

        <!-- ================================ -->
        <!-- KLASIFIKASI & ANALISA DATA      -->
        <!-- ================================ -->
         <h3>Terapi / Obat</h3>
        <table class="data">
            <thead>
                <tr>
                    <th width="5%">No.</th>
                    <th width="40%">Jenis Obat</th>
                    <th width="30%">Dosis</th>
                    <th width="25%">Manfaat</th>
                    <th width="25%">Cara Pemberian</th>

                </tr>
            </thead>
            <tbody>
                <?php if (!empty($kla['obat'])): ?>
                    <?php foreach ($kla['obat'] as $i => $obat): ?>
                          <tr>
                            <td><?= $i + 1 ?></td>
                            <td><?= p($obat['jenis_obat']) ?></td>
                            <td><?= p($obat['dosis']) ?></td>
                            <td><?= p($obat['kegunaan']) ?></td>
                            <td><?= p($obat['cara_pemberian']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="2" style="text-align:center">-</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <h3>Klasifikasi Data</h3>
        <table class="data">
            <thead>
                <tr>
                    <th width="50%">Data Subyektif (DS)</th>
                    <th width="50%">Data Obyektif (DO)</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($kla['klasifikasi'])): ?>
                    <?php foreach ($kla['klasifikasi'] as $klas): ?>
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
                <?php if (!empty($kla['analisa'])): ?>
                    <?php foreach ($kla['analisa'] as $i => $ana): ?>
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