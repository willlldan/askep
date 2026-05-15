<?php
// Shortcut per section
$pkj        = $sections['pengkajian'] ?? [];
$gordon     = $sections['gordon'] ?? [];
$bio1       = $sections['data_biologis_1'] ?? [];
$bio2       = $sections['data_biologis_2'] ?? [];
$bio3       = $sections['data_biologis_3'] ?? [];
$klasif     = $sections['klasifikasi_analisa_data'] ?? [];
$konsep     = $sections['konsep_keperawatan'] ?? [];
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
    <div>

        <!-- ================================ -->
        <!-- BAGIAN 1: LAPORAN PENDAHULUAN   -->
        <!-- ================================ -->
        <h1>Format Laporan Pendahuluan (LP)</h1>
        <h2>Keperawatan Medikal Bedah II</h2>

        <h3>A. Konsep Dasar Medis</h3>

        <div class="field-row">
            <div class="field-label">Pengertian</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($konsep['pengertian']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Etiologi</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($konsep['etiologi']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Klasifikasi</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($konsep['klasifikasi']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Patofisiologi</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($konsep['patofisiologi']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Manifestasi Klinik</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($konsep['manifestasi_klinik']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Pemeriksaan Diagnostik</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($konsep['pemeriksaan_diagnostik']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Penatalaksanaan</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($konsep['penatalaksanaan']) ?></div>
        </div>

        <h3>B. Konsep Dasar Keperawatan</h3>

        <div class="field-row">
            <div class="field-label">Pengkajian Keperawatan</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($konsep['pengkajian_keperawatan']) ?></div>
        </div>

        <div class="subsection-title">Penyimpangan KDM</div>
        <?php if (!empty($konsep['penyimpangan_kdm'])): ?>
            <div style="margin: 6px 0; text-align:center;">
                <img src="<?= cetakGambar(p($konsep['penyimpangan_kdm'])) ?>" style="max-height:250px;" />
            </div>
        <?php else: ?>
            <div class="field-value" style="border-bottom:1px solid #ccc; min-height:20px;">-</div>
        <?php endif; ?>

        <div class="field-row" style="margin-top:6px;">
            <div class="field-label">Diagnosa Keperawatan</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($konsep['diagnosa_keperawatan']) ?></div>
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
                <?php if (!empty($konsep['perencanaan'])): ?>
                    <?php foreach ($konsep['perencanaan'] as $i => $row): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td><?= p($row['diagnosa']) ?></td>
                            <td><?= p($row['tujuan_kriteria']) ?></td>
                            <td><?= p($row['intervensi']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" style="text-align:center">-</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <h3>Daftar Pustaka</h3>
        <?php $pustaka = arr($konsep['daftar_pustaka']); ?>
        <?php if (!empty($pustaka)): ?>
            <?php foreach ($pustaka as $i => $item): ?>
                <div style="margin-bottom:2px;"><?= ($i + 1) ?>. <?= p($item) ?></div>
            <?php endforeach; ?>
        <?php else: ?>
            <div>-</div>
        <?php endif; ?>

        <div class="page-break"></div>

        <!-- ================================ -->
        <!-- BAGIAN 2: FORMAT PENGKAJIAN     -->
        <!-- ================================ -->
        <h1>Format Pengkajian Askep</h1>
        <h2>Ruang Perawatan Dahlia</h2>

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
                <td><strong>Ruangan</strong></td>
                <td>:</td>
                <td><?= p($pkj['ruangan']) ?></td>
            </tr>
        </table>

        <!-- ================================ -->
        <!-- IDENTITAS KLIEN                 -->
        <!-- ================================ -->
        <h3>1. Identitas</h3>
        <div class="subsection-title">Identitas Klien</div>

        <table class="header-table">
            <tr>
                <td width="30%"><strong>Nama Klien</strong></td>
                <td width="2%">:</td>
                <td width="18%"><?= p($pkj['nama_klien']) ?></td>
                <td width="20%"><strong>No. Registrasi</strong></td>
                <td width="2%">:</td>
                <td><?= p($pkj['no_registrasi']) ?></td>
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
                <td><?= p($pkj['diagnosa_medik']) ?></td>
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
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td><strong>Pekerjaan</strong></td>
                <td>:</td>
                <td><?= p($pkj['pekerjaan']) ?></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td><strong>Alamat</strong></td>
                <td>:</td>
                <td colspan="4"><?= p($pkj['alamat']) ?></td>
            </tr>
        </table>

        <div class="subsection-title">Identitas Penanggung Jawab</div>
        <table class="header-table">
            <tr>
                <td width="30%"><strong>Nama (Inisial)</strong></td>
                <td width="2%">:</td>
                <td width="18%"><?= p($pkj['pj_nama']) ?></td>
                <td width="20%"><strong>Hubungan dengan Klien</strong></td>
                <td width="2%">:</td>
                <td><?= p($pkj['pj_hubungan_klien']) ?></td>
            </tr>
            <tr>
                <td><strong>Tempat/Tgl Lahir/Umur</strong></td>
                <td>:</td>
                <td><?= p($pkj['pj_ttl_umur']) ?></td>
                <td><strong>Agama</strong></td>
                <td>:</td>
                <td><?= p($pkj['pj_agama']) ?></td>
            </tr>
            <tr>
                <td><strong>Jenis Kelamin</strong></td>
                <td>:</td>
                <td><?= p($pkj['pj_jenis_kelamin']) ?></td>
                <td><strong>Pendidikan</strong></td>
                <td>:</td>
                <td><?= p($pkj['pj_pendidikan']) ?></td>
            </tr>
            <tr>
                <td><strong>Pekerjaan</strong></td>
                <td>:</td>
                <td><?= p($pkj['pj_pekerjaan']) ?></td>
                <td><strong>Alamat</strong></td>
                <td>:</td>
                <td><?= p($pkj['pj_alamat']) ?></td>
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
                    M: <?= p($pkj['gcs_m']) ?> &nbsp; V: <?= p($pkj['gcs_v']) ?> &nbsp; E: <?= p($pkj['gcs_e']) ?>
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
                <td><strong>Tinggi Badan</strong></td>
                <td>:</td>
                <td><?= p($pkj['tinggi_badan']) ?> cm</td>
                <td><strong>Lingkar Lengan Atas</strong></td>
                <td>:</td>
                <td><?= p($pkj['lingkar_lengan']) ?> cm</td>
            </tr>
            <tr>
                <td><strong>IMT</strong></td>
                <td>:</td>
                <td colspan="4"><?= p($pkj['imt']) ?></td>
            </tr>
        </table>

        <!-- ================================ -->
        <!-- RIWAYAT KESEHATAN               -->
        <!-- ================================ -->
        <h3>3. Riwayat Kesehatan</h3>

        <div class="field-row">
            <div class="field-label">Alasan Masuk RS</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($pkj['alasan_masuk_rs']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Keluhan Utama</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($pkj['keluhan_utama']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Riwayat Keluhan Utama</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($pkj['riwayat_keluhan_utama']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Riwayat Kesehatan Lalu</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($pkj['riwayat_kesehatan_lalu']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Riwayat Kesehatan Keluarga</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($pkj['riwayat_kesehatan_keluarga']) ?></div>
        </div>

        <div class="subsection-title">Genogram (3 Generasi)</div>
        <?php if (!empty($pkj['genogram'])): ?>
            <div style="margin: 6px 0; text-align:center;">
                <img src="<?= cetakGambar(p($pkj['genogram'])) ?>" style="max-height:250px; width:auto;" />
            </div>
        <?php else: ?>
            <div style="border:1px solid #ccc; min-height:60px; padding:4px;">-</div>
        <?php endif; ?>

        <div class="page-break"></div>

        <!-- ================================ -->
        <!-- POLA GORDON                     -->
        <!-- ================================ -->
        <h3>4. Pola Pengkajian Fungsional Gordon</h3>

        <div class="subsection-title">Persepsi Terhadap Kesehatan dan Manajemen Kesehatan</div>
        <div class="field-row">
            <div class="field-label">Merokok/Alkohol</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($gordon['merokok']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Pemeriksaan Kesehatan Rutin</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($gordon['pemeriksaan_rutin']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Pendapat tentang Keadaan Kesehatan Saat Ini</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($gordon['pendapat_kesehatan']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Persepsi Berat Ringannya Penyakit</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($gordon['persepsi_penyakit']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Persepsi Tingkat Kesembuhan</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($gordon['tingkat_kesembuhan']) ?></div>
        </div>

        <div class="subsection-title">Pola Aktivitas dan Latihan</div>
        <div class="field-row">
            <div class="field-label">Rutinitas Mandi</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($gordon['rutinitas_mandi']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Kebersihan Sehari-hari</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($gordon['kebersihan']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Aktivitas Sehari-hari</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($gordon['aktivitas']) ?></div>
        </div>

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
                <?= actRow('Mandi', $gordon['mandi']) ?>
                <?= actRow('Berpakaian/Berdandan', $gordon['berpakaian']) ?>
                <?= actRow('Mobilisasi di TT', $gordon['mobilisasi']) ?>
                <?= actRow('Pindah', $gordon['pindah']) ?>
                <?= actRow('Ambulasi', $gordon['ambulasi']) ?>
                <?= actRow('Makan/Minum', $gordon['makan']) ?>
            </tbody>
        </table>

        <div class="subsection-title">Pola Kognitif dan Perseptual</div>
        <div class="field-row">
            <div class="field-label">Nyeri</div>
            <div class="field-sep">:</div>
            <div class="field-value">
                <div class="field-hint">Kualitas, intensitas, durasi, skala nyeri, cara mengurangi nyeri</div>
                <?= p($gordon['nyeri']) ?>
            </div>
        </div>
        <div class="field-row">
            <div class="field-label">Fungsi Panca Indra</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($gordon['panca_indra']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Kemampuan Berbicara</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($gordon['berbicara']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Kemampuan Membaca</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($gordon['membaca']) ?></div>
        </div>

        <div class="subsection-title">Pola Konsep Diri</div>
        <div class="field-row">
            <div class="field-label">Pandangan terhadap Diri Sendiri</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($gordon['konsep_diri']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Hal yang Disukai dari Diri Sendiri</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($gordon['hal_disukai']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Kekuatan dan Kelemahan</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($gordon['kekuatan_kelemahan']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Kemampuan yang Dapat Dilakukan dengan Baik</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($gordon['kemampuan_baik']) ?></div>
        </div>

        <div class="subsection-title">Pola Koping</div>
        <div class="field-row">
            <div class="field-label">Masalah Utama Selama Masuk RS</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($gordon['masalah_rs']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Kehilangan/Perubahan Sebelumnya</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($gordon['kehilangan']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Takut terhadap Kekerasan</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($gordon['takut_kekerasan']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Pandangan terhadap Masa Depan</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($gordon['masa_depan']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Mekanisme Koping</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($gordon['mekanisme_koping']) ?></div>
        </div>

        <div class="subsection-title">Pola Seksual-Reproduksi</div>
        <div class="field-row">
            <div class="field-label">Masalah Menstruasi</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($gordon['masalah_menstruasi']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Papsmear Terakhir</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($gordon['papsmear']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Perawatan Payudara</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($gordon['perawatan_payudara']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Kesulitan dalam Hubungan Seksual</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($gordon['kesulitan_seksual']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Gangguan Fungsi Seksual</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($gordon['gangguan_seksual']) ?></div>
        </div>

        <div class="subsection-title">Pola Peran dan Berhubungan</div>
        <div class="field-row">
            <div class="field-label">Peran Pasien dalam Keluarga dan Masyarakat</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($gordon['peran_pasien']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Teman Dekat</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($gordon['teman_dekat']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Orang yang Dipercaya</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($gordon['orang_terpercaya']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Kegiatan Masyarakat</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($gordon['kegiatan_masyarakat']) ?></div>
        </div>

        <div class="subsection-title">Pola Nilai dan Kepercayaan</div>
        <div class="field-row">
            <div class="field-label">Agama Klien</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($gordon['agama_klien']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Hubungan Manusia dengan Tuhan</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($gordon['hubungan_tuhan']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Hambatan dalam Ibadah</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($gordon['hambatan_ibadah']) ?></div>
        </div>

        <div class="page-break"></div>

        <div class="subsection-title">Pola Nutrisi</div>
        <table class="data">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Kondisi</th>
                    <th>Sebelum Sakit</th>
                    <th>Saat Ini</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>Frekuensi Makan</td>
                    <td><?= p($gordon['frekuensi_makan_sebelum']) ?></td>
                    <td><?= p($gordon['frekuensi_makan_sekarang']) ?></td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Selera Makan</td>
                    <td><?= p($gordon['selera_makan_sebelum']) ?></td>
                    <td><?= p($gordon['selera_makan_sekarang']) ?></td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>Menu Makanan</td>
                    <td><?= p($gordon['menu_makan_sebelum']) ?></td>
                    <td><?= p($gordon['menu_makan_sekarang']) ?></td>
                </tr>
                <tr>
                    <td>4</td>
                    <td>Ritual Saat Makan</td>
                    <td><?= p($gordon['ritual_makan_sebelum']) ?></td>
                    <td><?= p($gordon['ritual_makan_sekarang']) ?></td>
                </tr>
                <tr>
                    <td>5</td>
                    <td>Bantuan Makan Parental</td>
                    <td><?= p($gordon['bantuan_makan_sebelum']) ?></td>
                    <td><?= p($gordon['bantuan_makan_sekarang']) ?></td>
                </tr>
            </tbody>
        </table>

        <div class="subsection-title">Cairan</div>
        <table class="data">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Kondisi</th>
                    <th>Sebelum Sakit</th>
                    <th>Saat Ini</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>Jenis Minuman</td>
                    <td><?= p($gordon['jenis_minum_sebelum']) ?></td>
                    <td><?= p($gordon['jenis_minum_sekarang']) ?></td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Jumlah Cairan</td>
                    <td><?= p($gordon['jumlah_cairan_sebelum']) ?></td>
                    <td><?= p($gordon['jumlah_cairan_sekarang']) ?></td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>Bantuan Cairan Parental</td>
                    <td><?= p($gordon['bantuan_cairan_sebelum']) ?></td>
                    <td><?= p($gordon['bantuan_cairan_sekarang']) ?></td>
                </tr>
            </tbody>
        </table>

        <div class="subsection-title">Pola Eliminasi BAB</div>
        <table class="data">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Kondisi</th>
                    <th>Sebelum Sakit</th>
                    <th>Saat Ini</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>Frekuensi (Waktu)</td>
                    <td><?= p($gordon['bab_frekuensi_sebelum']) ?></td>
                    <td><?= p($gordon['bab_frekuensi_sekarang']) ?></td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Konsistensi</td>
                    <td><?= p($gordon['bab_konsistensi_sebelum']) ?></td>
                    <td><?= p($gordon['bab_konsistensi_sekarang']) ?></td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>Warna</td>
                    <td><?= p($gordon['bab_warna_sebelum']) ?></td>
                    <td><?= p($gordon['bab_warna_sekarang']) ?></td>
                </tr>
                <tr>
                    <td>4</td>
                    <td>Bau</td>
                    <td><?= p($gordon['bab_bau_sebelum']) ?></td>
                    <td><?= p($gordon['bab_bau_sekarang']) ?></td>
                </tr>
                <tr>
                    <td>5</td>
                    <td>Kesulitan saat BAB</td>
                    <td><?= p($gordon['bab_kesulitan_sebelum']) ?></td>
                    <td><?= p($gordon['bab_kesulitan_sekarang']) ?></td>
                </tr>
                <tr>
                    <td>6</td>
                    <td>Penggunaan Obat Pencahar</td>
                    <td><?= p($gordon['bab_obat_sebelum']) ?></td>
                    <td><?= p($gordon['bab_obat_sekarang']) ?></td>
                </tr>
            </tbody>
        </table>

        <div class="subsection-title">Pola Eliminasi BAK</div>
        <table class="data">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Kondisi</th>
                    <th>Sebelum Sakit</th>
                    <th>Saat Ini</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>Frekuensi (Waktu)</td>
                    <td><?= p($gordon['bak_frekuensi_sebelum']) ?></td>
                    <td><?= p($gordon['bak_frekuensi_sekarang']) ?></td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Warna</td>
                    <td><?= p($gordon['bak_warna_sebelum']) ?></td>
                    <td><?= p($gordon['bak_warna_sekarang']) ?></td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>Bau</td>
                    <td><?= p($gordon['bak_bau_sebelum']) ?></td>
                    <td><?= p($gordon['bak_bau_sekarang']) ?></td>
                </tr>
                <tr>
                    <td>4</td>
                    <td>Kesulitan saat BAK</td>
                    <td><?= p($gordon['bak_kesulitan_sebelum']) ?></td>
                    <td><?= p($gordon['bak_kesulitan_sekarang']) ?></td>
                </tr>
                <tr>
                    <td>5</td>
                    <td>Penggunaan Obat Diuretik</td>
                    <td><?= p($gordon['bak_obat_sebelum']) ?></td>
                    <td><?= p($gordon['bak_obat_sekarang']) ?></td>
                </tr>
            </tbody>
        </table>

        <div class="subsection-title">Pola Tidur</div>
        <table class="data">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Kondisi</th>
                    <th>Sebelum Sakit</th>
                    <th>Saat Ini</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>Tidur Siang</td>
                    <td><?= p($gordon['tidur_siang_sebelum']) ?></td>
                    <td><?= p($gordon['tidur_siang_sekarang']) ?></td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Tidur Malam</td>
                    <td><?= p($gordon['tidur_malam_sebelum']) ?></td>
                    <td><?= p($gordon['tidur_malam_sekarang']) ?></td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>Kesulitan Tidur</td>
                    <td><?= p($gordon['kesulitan_tidur_sebelum']) ?></td>
                    <td><?= p($gordon['kesulitan_tidur_sekarang']) ?></td>
                </tr>
                <tr>
                    <td>4</td>
                    <td>Kebiasaan Sebelum Tidur</td>
                    <td><?= p($gordon['kebiasaan_tidur_sebelum']) ?></td>
                    <td><?= p($gordon['kebiasaan_tidur_sekarang']) ?></td>
                </tr>
            </tbody>
        </table>

        <div class="subsection-title">Pola Personal Hygiene</div>
        <table class="data">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Kondisi</th>
                    <th>Sebelum Sakit</th>
                    <th>Saat Ini</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td rowspan="3">1</td>
                    <td>Mandi — Frekuensi</td>
                    <td><?= p($gordon['mandi_frekuensi_sebelum']) ?></td>
                    <td><?= p($gordon['mandi_frekuensi_sekarang']) ?></td>
                </tr>
                <tr>
                    <td>Mandi — Cara</td>
                    <td><?= p($gordon['mandi_cara_sebelum']) ?></td>
                    <td><?= p($gordon['mandi_cara_sekarang']) ?></td>
                </tr>
                <tr>
                    <td>Mandi — Tempat</td>
                    <td><?= p($gordon['mandi_tempat_sebelum']) ?></td>
                    <td><?= p($gordon['mandi_tempat_sekarang']) ?></td>
                </tr>
                <tr>
                    <td rowspan="2">2</td>
                    <td>Cuci Rambut — Frekuensi</td>
                    <td><?= p($gordon['rambut_frekuensi_sebelum']) ?></td>
                    <td><?= p($gordon['rambut_frekuensi_sekarang']) ?></td>
                </tr>
                <tr>
                    <td>Cuci Rambut — Cara</td>
                    <td><?= p($gordon['rambut_cara_sebelum']) ?></td>
                    <td><?= p($gordon['rambut_cara_sekarang']) ?></td>
                </tr>
                <tr>
                    <td rowspan="2">3</td>
                    <td>Gunting Kuku — Frekuensi</td>
                    <td><?= p($gordon['kuku_frekuensi_sebelum']) ?></td>
                    <td><?= p($gordon['kuku_frekuensi_sekarang']) ?></td>
                </tr>
                <tr>
                    <td>Gunting Kuku — Cara</td>
                    <td><?= p($gordon['kuku_cara_sebelum']) ?></td>
                    <td><?= p($gordon['kuku_cara_sekarang']) ?></td>
                </tr>
                <tr>
                    <td rowspan="2">4</td>
                    <td>Gosok Gigi — Frekuensi</td>
                    <td><?= p($gordon['gigi_frekuensi_sebelum']) ?></td>
                    <td><?= p($gordon['gigi_frekuensi_sekarang']) ?></td>
                </tr>
                <tr>
                    <td>Gosok Gigi — Cara</td>
                    <td><?= p($gordon['gigi_cara_sebelum']) ?></td>
                    <td><?= p($gordon['gigi_cara_sekarang']) ?></td>
                </tr>
            </tbody>
        </table>

        <div class="page-break"></div>

        <!-- ================================ -->
        <!-- DATA BIOLOGIS 1: KEPALA - LEHER -->
        <!-- ================================ -->
        <h3>5. Data Biologis</h3>

        <div class="subsection-title">Kepala</div>
        <div class="field-row">
            <div class="field-label">Bentuk Kepala</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($bio1['bentuk_kepala']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Benjolan</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= chk($bio1['benjolan_kepala'], 'ya') ?> Ya &nbsp; <?= chk($bio1['benjolan_kepala'], 'tidak') ?> Tidak</div>
        </div>

        <div class="subsection-title">Rambut</div>
        <div class="field-row">
            <div class="field-label">Penyebaran Merata</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= chk($bio1['penyebaran_merata'], 'ya') ?> Ya &nbsp; <?= chk($bio1['penyebaran_merata'], 'tidak') ?> Tidak</div>
        </div>
        <div class="field-row">
            <div class="field-label">Warna</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($bio1['warna_rambut']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Mudah Dicabut</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= chk($bio1['rambut_dicabut'], 'ya') ?> Ya &nbsp; <?= chk($bio1['rambut_dicabut'], 'tidak') ?> Tidak</div>
        </div>
        <div class="field-row">
            <div class="field-label">Kelainan</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($bio1['kelainan_rambut']) ?></div>
        </div>

        <div class="subsection-title">Wajah</div>
        <div class="field-row">
            <div class="field-label">Ekspresi Wajah</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($bio1['ekspresi_wajah']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Kesimetrisan Wajah</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= chk($bio1['simetris_wajah'], 'ya') ?> Ya &nbsp; <?= chk($bio1['simetris_wajah'], 'tidak') ?> Tidak</div>
        </div>
        <div class="field-row">
            <div class="field-label">Terdapat Udema</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= chk($bio1['udema_wajah'], 'ya') ?> Ya &nbsp; <?= chk($bio1['udema_wajah'], 'tidak') ?> Tidak</div>
        </div>
        <div class="field-row">
            <div class="field-label">Kelainan Lain</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($bio1['kelainan_wajah']) ?></div>
        </div>

        <div class="subsection-title">Mata</div>
        <div class="field-row">
            <div class="field-label">Penglihatan</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($bio1['penglihatan']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Visus Kanan / Kiri</div>
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
            <div class="field-label">Konjungtiva</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($bio1['konjungtiva']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Lesi Mata</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= chk($bio1['lesi_mata'], 'ada') ?> Ada &nbsp; <?= chk($bio1['lesi_mata'], 'tidak') ?> Tidak</div>
        </div>
        <div class="field-row">
            <div class="field-label">Sclera</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($bio1['sclera']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Reaksi Pupil</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($bio1['pupil']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Bola Mata</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($bio1['bola_mata']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Kelainan</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($bio1['kelainan_mata']) ?></div>
        </div>

        <div class="subsection-title">Telinga</div>
        <div class="field-row">
            <div class="field-label">Pendengaran Kanan / Kiri</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($bio1['pendengaran_kanan']) ?> / <?= p($bio1['pendengaran_kiri']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Serumen</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= chk($bio1['serumen'], 'ada') ?> Ada &nbsp; <?= chk($bio1['serumen'], 'tidak') ?> Tidak</div>
        </div>
        <div class="field-row">
            <div class="field-label">Kelainan</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($bio1['kelainan_telinga']) ?></div>
        </div>

        <div class="subsection-title">Hidung</div>
        <div class="field-row">
            <div class="field-label">Membedakan Bau</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= chk($bio1['bau'], 'dapat') ?> Dapat &nbsp; <?= chk($bio1['bau'], 'tidak') ?> Tidak</div>
        </div>
        <div class="field-row">
            <div class="field-label">Sekresi</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($bio1['sekresi']) ?></div>
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
            <div class="field-value"><?= chk($bio1['cuping_hidung'], 'ya') ?> Ada &nbsp; <?= chk($bio1['cuping_hidung'], 'tidak') ?> Tidak</div>
        </div>
        <div class="field-row">
            <div class="field-label">Kelainan</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($bio1['kelainan_hidung']) ?></div>
        </div>

        <div class="subsection-title">Mulut</div>
        <?php $bau_mulut_arr = arr($bio1['bau_mulut']); ?>
        <div class="field-row">
            <div class="field-label">Warna Bibir</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($bio1['bibir']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Simetris</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= chk($bio1['simetris'], 'ya') ?> Ya &nbsp; <?= chk($bio1['simetris'], 'tidak') ?> Tidak</div>
        </div>
        <div class="field-row">
            <div class="field-label">Kelembaban</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($bio1['kelembaban']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Caries Gigi</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= chk($bio1['caries'], 'ada') ?> Ada &nbsp; <?= chk($bio1['caries'], 'tidak') ?> Tidak &nbsp; | Jumlah: <?= p($bio1['jumlah_gigi']) ?> | Warna: <?= p($bio1['warna_gigi']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Gigi Palsu</div>
            <div class="field-sep">:</div>
            <div class="field-value">Jumlah: <?= p($bio1['gigi_palsu_jumlah']) ?> | Letak: <?= p($bio1['letak']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Lidah</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($bio1['lidah']) ?> | Lesi: <?= chk($bio1['lesi_lidah'], 'ada') ?> Ada</div>
        </div>
        <div class="field-row">
            <div class="field-label">Sensasi Rasa</div>
            <div class="field-sep">:</div>
            <div class="field-value">
                Panas/Dingin: <?= chk($bio1['panas/dingin'], 'ada') ?> Ada &nbsp;
                Asam/Pahit: <?= chk($bio1['asampahit'], 'ada') ?> Ada &nbsp;
                Manis: <?= chk($bio1['manis'], 'ada') ?> Ada
            </div>
        </div>
        <div class="field-row">
            <div class="field-label">Refleks Mengunyah</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= chk($bio1['refleks'], 'dapat') ?> Dapat &nbsp; <?= chk($bio1['refleks'], 'tidak') ?> Tidak</div>
        </div>
        <div class="field-row">
            <div class="field-label">Pembesaran Tonsil</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($bio1['tonsil']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Bau Mulut</div>
            <div class="field-sep">:</div>
            <div class="field-value">
                <?= chkIn($bau_mulut_arr, 'uranium') ?> Uranium &nbsp;
                <?= chkIn($bau_mulut_arr, 'amoniak') ?> Amoniak &nbsp;
                <?= chkIn($bau_mulut_arr, 'aceton') ?> Aceton &nbsp;
                <?= chkIn($bau_mulut_arr, 'busuk') ?> Busuk &nbsp;
                <?= chkIn($bau_mulut_arr, 'alkohol') ?> Alkohol
            </div>
        </div>
        <div class="field-row">
            <div class="field-label">Sekret Mulut</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= chk($bio1['sekret_mulut'], 'ada') ?> Ada | Warna: <?= p($bio1['sekret_mulut_warna']) ?></div>
        </div>

        <div class="subsection-title">Leher</div>
        <div class="field-row">
            <div class="field-label">Bentuk Simetris</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= chk($bio1['leher_simetris'], 'ya') ?> Ya &nbsp; <?= chk($bio1['leher_simetris'], 'tidak') ?> Tidak</div>
        </div>
        <div class="field-row">
            <div class="field-label">Pembesaran Kelenjar</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= chk($bio1['kelenjar'], 'ada') ?> Ada &nbsp; <?= chk($bio1['kelenjar'], 'tidak') ?> Tidak</div>
        </div>
        <div class="field-row">
            <div class="field-label">Peninggian JVP</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= chk($bio1['jvp'], 'ada') ?> Ada &nbsp; <?= chk($bio1['jvp'], 'tidak') ?> Tidak</div>
        </div>
        <div class="field-row">
            <div class="field-label">Refleks Menelan</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= chk($bio1['refleks_menelan'], 'dapat') ?> Dapat &nbsp; <?= chk($bio1['refleks_menelan'], 'tidak') ?> Tidak</div>
        </div>
        <div class="field-row">
            <div class="field-label">Kelainan</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($bio1['kelainan_leher']) ?></div>
        </div>

        <!-- DATA BIOLOGIS 2 -->
        <div class="subsection-title">Dada</div>
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
            <div class="field-label">Perbandingan Ukuran AP dengan Transversal</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($bio2['perbandingan_dada']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Penggunaan Otot Pernafasan Tambahan</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= chk($bio2['otot_pernafasan'], 'ya') ?> Ya &nbsp; <?= chk($bio2['otot_pernafasan'], 'tidak') ?> Tidak</div>
        </div>

        <div class="subsection-title">Paru</div>
        <div class="field-row">
            <div class="field-label">Frekuensi Nafas</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($bio2['frekuensi_nafas']) ?> x/menit | <?= chk($bio2['teratur_nafas'], 'teratur') ?> Teratur <?= chk($bio2['teratur_nafas'], 'tidak') ?> Tidak Teratur</div>
        </div>
        <div class="field-row">
            <div class="field-label">Irama Pernafasan</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= chk($bio2['irama_nafas'], 'dangkal') ?> Dangkal &nbsp; <?= chk($bio2['irama_nafas'], 'dalam') ?> Dalam</div>
        </div>
        <div class="field-row">
            <div class="field-label">Kesukaran Bernafas</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= chk($bio2['sesak_nafas'], 'ya') ?> Ya &nbsp; <?= chk($bio2['sesak_nafas'], 'tidak') ?> Tidak</div>
        </div>
        <div class="field-row">
            <div class="field-label">Taktil Fremitus</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($bio2['taktil_fremitus']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Bunyi Perkusi Paru</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($bio2['perkusi_paru']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Suara Nafas</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($bio2['suara_nafas']) ?> — <?= p($bio2['suara_nafas_uraian']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Bunyi Nafas Abnormal</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($bio2['bunyi_abnormal']) ?></div>
        </div>

        <div class="subsection-title">Jantung</div>
        <?php $bunyi_tambahan_arr = arr($bio2['bunyi_tambahan']); ?>
        <div class="field-row">
            <div class="field-label">S1 / S2</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($bio2['s1_jantung']) ?> / <?= p($bio2['s2_jantung']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Bunyi Teratur</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= chk($bio2['bunyi_jantung'], 'ya') ?> Ya &nbsp; <?= chk($bio2['bunyi_jantung'], 'tidak') ?> Tidak</div>
        </div>
        <div class="field-row">
            <div class="field-label">Bunyi Tambahan</div>
            <div class="field-sep">:</div>
            <div class="field-value">
                <?= chkIn($bunyi_tambahan_arr, 'murmur') ?> Murmur &nbsp;
                <?= chkIn($bunyi_tambahan_arr, 'tidak') ?> Tidak
            </div>
        </div>
        <div class="field-row">
            <div class="field-label">Pulsasi Jantung</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($bio2['pulsasi_jantung']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Irama Jantung</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= chk($bio2['irama_jantung'], 'teratur') ?> Teratur &nbsp; <?= chk($bio2['irama_jantung'], 'tidak teratur') ?> Tidak Teratur</div>
        </div>

        <div class="subsection-title">Abdomen</div>
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
            <div class="field-value"><?= chk($bio2['bising_usus'], 'ada') ?> Ada | <?= p($bio2['bising_usus_kali']) ?> x/menit</div>
        </div>
        <div class="field-row">
            <div class="field-label">Benjolan</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= chk($bio2['benjolan_abdomen'], 'ada') ?> Ada &nbsp; | Letak: <?= p($bio2['benjolan_letak']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Nyeri Tekan</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= chk($bio2['nyeri_abdomen'], 'ada') ?> Ada &nbsp; | Letak: <?= p($bio2['nyeri_letak']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Perkusi Abdomen</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($bio2['perkusi_abdomen']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Kelainan</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($bio2['kelainan_abdomen']) ?></div>
        </div>

        <div class="subsection-title">Genetalia</div>
        <div class="field-row">
            <div class="field-label">Bentuk</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($bio2['bentuk_genetalia']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Radang</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= chk($bio2['radang_genetalia'], 'ada') ?> Ada &nbsp; <?= chk($bio2['radang_genetalia'], 'tidak') ?> Tidak</div>
        </div>
        <div class="field-row">
            <div class="field-label">Sekret</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= chk($bio2['sekret_genetalia'], 'ada') ?> Ada &nbsp; <?= chk($bio2['sekret_genetalia'], 'tidak') ?> Tidak</div>
        </div>
        <div class="field-row">
            <div class="field-label">Pembengkakan pada Skrotum</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= chk($bio2['skrotum_bengkak'], 'ada') ?> Ada &nbsp; <?= chk($bio2['skrotum_bengkak'], 'tidak') ?> Tidak</div>
        </div>
        <div class="field-row">
            <div class="field-label">Rektum</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= chk($bio2['rektum_benjolan'], 'benjolan') ?> Benjolan &nbsp; <?= chk($bio2['rektum_benjolan'], 'tidak') ?> Tidak</div>
        </div>
        <div class="field-row">
            <div class="field-label">Lesi</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= chk($bio2['lesi_genetalia'], 'ya') ?> Ya &nbsp; <?= chk($bio2['lesi_genetalia'], 'tidak') ?> Tidak</div>
        </div>
        <div class="field-row">
            <div class="field-label">Kelainan</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($bio2['kelainan_genetalia']) ?></div>
        </div>

        <div class="subsection-title">Ekstremitas Atas</div>
        <div class="field-row">
            <div class="field-label">Bentuk Simetris</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= chk($bio2['atas_simetris'], 'ya') ?> Ya &nbsp; <?= chk($bio2['atas_simetris'], 'tidak') ?> Tidak</div>
        </div>
        <div class="field-row">
            <div class="field-label">Sensasi</div>
            <div class="field-sep">:</div>
            <div class="field-value">
                Halus: <?= chk($bio2['sensasi_halus'], 'ada') ?> Ada &nbsp;
                Tajam: <?= chk($bio2['sensasi_tajam'], 'ada') ?> Ada &nbsp;
                Panas: <?= chk($bio2['sensasi_panas'], 'ada') ?> Ada &nbsp;
                Dingin: <?= chk($bio2['sensasi_dingin'], 'ada') ?> Ada
            </div>
        </div>
        <div class="field-row">
            <div class="field-label">Gerakan ROM</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= chk($bio2['rom_atas'], 'dapat') ?> Dapat &nbsp; <?= chk($bio2['rom_atas'], 'tidak') ?> Tidak</div>
        </div>
        <div class="field-row">
            <div class="field-label">Refleks Bisep / Trisep</div>
            <div class="field-sep">:</div>
            <div class="field-value">
                Bisep: <?= chk($bio2['refleks_bisep'], 'ada') ?> Ada &nbsp;
                Trisep: <?= chk($bio2['refleks_trisep'], 'ada') ?> Ada
            </div>
        </div>
        <div class="field-row">
            <div class="field-label">Pembengkakan</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= chk($bio2['pembengkakan_atas'], 'ya') ?> Ya &nbsp; <?= chk($bio2['pembengkakan_atas'], 'tidak') ?> Tidak</div>
        </div>
        <div class="field-row">
            <div class="field-label">Kelembaban / Temperatur</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($bio2['kelembaban_atas']) ?> / <?= p($bio2['temperatur_atas']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Kekuatan Otot Tangan Kanan / Kiri</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($bio2['otot_tangan_kanan']) ?> / <?= p($bio2['otot_tangan_kiri']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Kelainan</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($bio2['kelainan_ekstremitas_atas']) ?></div>
        </div>

        <div class="subsection-title">Ekstremitas Bawah</div>
        <div class="field-row">
            <div class="field-label">Bentuk Simetris</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= chk($bio2['bawah_simetris'], 'ya') ?> Ya &nbsp; <?= chk($bio2['bawah_simetris'], 'tidak') ?> Tidak</div>
        </div>
        <div class="field-row">
            <div class="field-label">Sensasi</div>
            <div class="field-sep">:</div>
            <div class="field-value">
                Halus: <?= chk($bio2['bawah_sensasi_halus'], 'ada') ?> Ada &nbsp;
                Tajam: <?= chk($bio2['bawah_sensasi_tajam'], 'ada') ?> Ada &nbsp;
                Panas: <?= chk($bio2['bawah_sensasi_panas'], 'ada') ?> Ada &nbsp;
                Dingin: <?= chk($bio2['bawah_sensasi_dingin'], 'ada') ?> Ada
            </div>
        </div>
        <div class="field-row">
            <div class="field-label">Gerakan ROM</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= chk($bio2['rom_bawah'], 'dapat') ?> Dapat &nbsp; <?= chk($bio2['rom_bawah'], 'tidak') ?> Tidak</div>
        </div>
        <div class="field-row">
            <div class="field-label">Refleks Babinski</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= chk($bio2['refleks_babinski'], 'ada') ?> Ada &nbsp; <?= chk($bio2['refleks_babinski'], 'tidak') ?> Tidak</div>
        </div>
        <div class="field-row">
            <div class="field-label">Pembengkakan / Varises</div>
            <div class="field-sep">:</div>
            <div class="field-value">
                Pembengkakan: <?= chk($bio2['pembengkakan_bawah'], 'ya') ?> Ya &nbsp;
                Varises: <?= chk($bio2['varises_bawah'], 'ada') ?> Ada
            </div>
        </div>
        <div class="field-row">
            <div class="field-label">Kelembaban / Temperatur</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($bio2['kelembaban_bawah']) ?> / <?= p($bio2['temperatur_bawah']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Kekuatan Otot Kaki Kanan / Kiri</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($bio2['otot_kaki_kanan']) ?> / <?= p($bio2['otot_kaki_kiri']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Kelainan</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($bio2['kelainan_ekstremitas_bawah']) ?></div>
        </div>

        <div class="subsection-title">Kulit</div>
        <div class="field-row">
            <div class="field-label">Warna</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($bio2['warna_kulit']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Turgor</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= chk($bio2['turgor_kulit'], 'elastis') ?> Elastis &nbsp; <?= chk($bio2['turgor_kulit'], 'menurun') ?> Menurun</div>
        </div>
        <div class="field-row">
            <div class="field-label">Keadaan / Kelembaban</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($bio2['kelembaban_kulit']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Edema</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= chk($bio2['edema_kulit'], 'ada') ?> Ada | Pada Daerah: <?= p($bio2['pada_daerah']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Luka</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= chk($bio2['luka_kulit'], 'ada') ?> Ada | Karakteristik: <?= p($bio2['karakteristik_luka']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Tekstur</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($bio2['tekstur_kulit']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Kelainan</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($bio2['kelainan_kulit']) ?></div>
        </div>

        <div class="subsection-title">Kuku</div>
        <div class="field-row">
            <div class="field-label">Clubbing Finger</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= chk($bio2['clubbing_finger'], 'ya') ?> Ya &nbsp; <?= chk($bio2['clubbing_finger'], 'tidak') ?> Tidak</div>
        </div>
        <div class="field-row">
            <div class="field-label">Capillary Refill Time</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($bio2['capillary_refill_time']) ?></div>
        </div>

        <div class="page-break"></div>

        <!-- STATUS NEUROLOGI -->
        <h3>6. Status Neurologi</h3>
        <div class="subsection-title">Saraf-saraf Kranial</div>

        <?php
        $neuro = [
            ['Nervus I (Olfactorius) — Penciuman', $bio2['nervus1_penciuman']],
            ['Nervus II (Opticus) — Penglihatan', $bio2['nervus2_penglihatan']],
            ['Nervus III, IV, VI — Konstriksi Pupil', $bio2['konstriksi_pupil']],
            ['Nervus III, IV, VI — Gerakan Kelopak Mata', $bio2['gerakan_kelopak']],
            ['Nervus III, IV, VI — Pergerakan Bola Mata', $bio2['gerakan_bola_mata']],
            ['Nervus III, IV, VI — Pergerakan Mata ke Bawah & Dalam', $bio2['gerakan_mata_bawah']],
            ['Nervus V — Refleks Dagu', $bio2['refleks_dagu']],
            ['Nervus V — Refleks Cornea', $bio2['refleks_cornea']],
            ['Nervus VII — Pengecapan 2/3 Lidah Bagian Depan', $bio2['pengecapan_depan']],
            ['Nervus VIII — Fungsi Pendengaran', $bio2['fungsi_pendengaran']],
            ['Nervus IX & X — Refleks Menelan', $bio2['refleks_menelan']],
            ['Nervus IX & X — Refleks Muntah', $bio2['refleks_muntah']],
            ['Nervus IX & X — Pengecapan 1/3 Lidah Bagian Belakang', $bio2['pengecapan_belakang']],
            ['Nervus IX & X — Suara', $bio2['suara_pasien']],
            ['Nervus XI — Gerakan Kepala', $bio2['gerakan_kepala']],
            ['Nervus XI — Mengangkat Bahu', $bio2['angkat_bahu']],
            ['Nervus XII — Deviasi Lidah', $bio2['deviasi_lidah']],
        ];
        foreach ($neuro as $n): ?>
            <div class="field-row">
                <div class="field-label"><?= $n[0] ?></div>
                <div class="field-sep">:</div>
                <div class="field-value"><?= p($n[1]) ?></div>
            </div>
        <?php endforeach; ?>

        <div class="subsection-title">Tanda-tanda Peradangan Selaput Otak</div>
        <div class="field-row">
            <div class="field-label">Kaku Kuduk</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($bio2['kaku_kuduk']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Kernig Sign</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($bio2['kernig_sign']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Refleks Brudzinski</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($bio2['refleks_brudzinski']) ?></div>
        </div>

        <!-- DATA PSIKOLOGIS -->
        <h3>7. Data Psikologis</h3>
        <div class="field-row">
            <div class="field-label">Harapan Klien Saat Ini</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($bio3['harapan_klien']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Perasaan Rendah Diri</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($bio3['rendah_diri']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Pendapat tentang Keadaannya</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($bio3['pendapat_keadaan']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Status Rumah</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($bio3['status_rumah']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Hubungan Keluarga</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($bio3['hubungan_keluarga']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Pengambil Keputusan dalam Keluarga</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($bio3['pengambil_keputusan']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Keadaan Ekonomi Keluarga</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($bio3['ekonomi_cukup']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Hubungan Antar Keluarga Baik</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($bio3['hubungan_keluarga_baik']) ?></div>
        </div>

        <!-- DATA PENUNJANG -->
        <h3>8. Data Penunjang</h3>
        <?php $penunjang = arr($bio3['data_penunjang']); ?>
        <table class="data">
            <thead>
                <tr>
                    <th>Tipe</th>
                    <th>Tanggal</th>
                    <th>Pemeriksaan/Hasil</th>
                    <th>Satuan</th>
                    <th>Nilai Rujukan</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($penunjang)): ?>
                    <?php foreach ($penunjang as $dp): ?>
                        <tr>
                            <td><?= p($dp['tipe']) ?></td>
                            <td><?= p($dp['tanggal']) ?></td>
                            <td><?= p($dp['hasil']) ?></td>
                            <td><?= p($dp['satuan']) ?></td>
                            <td><?= p($dp['nilai_rujukan']) ?></td>
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
                <?php if (!empty($klasif['klasifikasi'])): ?>
                    <?php foreach ($klasif['klasifikasi'] as $klas): ?>
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
                <?php if (!empty($klasif['analisa'])): ?>
                    <?php foreach ($klasif['analisa'] as $i => $ana): ?>
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