<?php
$identitas = $sections['identitas'] ?? [];
$riwayat_kesehatan = $sections['riwayat_kesehatan'] ?? [];
$pemeriksaan_fisik = $sections['pemeriksaan_fisik'] ?? [];
$kebiasaan_harian = $sections['kebiasaan_harian'] ?? [];
$psikososial_spiritual = $sections['psikososial_spiritual'] ?? [];
$status_fungsional = $sections['status_fungsional'] ?? [];
$skala_depresi = $sections['skala_depresi'] ?? [];
$apgar = $sections['apgar_spmsq_risiko_jatuh'] ?? [];
$catatan = $sections['catatan_keperawatan'] ?? [];

function geroBool($val)
{
    if ($val === 'Y' || $val === 'Ya' || $val === 'mandiri') return 'Ya';
    if ($val === 'T' || $val === 'Tidak' || $val === 'tergantung') return 'Tidak';
    return p($val);
}

function geroRow($label, $value)
{
    return '<div class="field-row"><div class="field-label">' . $label . '</div><div class="field-sep">:</div><div class="field-value">' . p($value) . '</div></div>';
}

function geroSpmsqMark($val, $expected)
{
    return (string)$val === (string)$expected ? $expected : '';
}

function geroTableRows($rows, $fields, $emptyLabel = '-')
{
    if (empty($rows)) {
        return '<tr><td colspan="' . (count($fields) + 1) . '" style="text-align:center">' . $emptyLabel . '</td></tr>';
    }
    $html = '';
    foreach ($rows as $idx => $row) {
        $html .= '<tr><td style="text-align:center;">' . ($idx + 1) . '</td>';
        foreach ($fields as $field) {
            $html .= '<td>' . p($row[$field] ?? '') . '</td>';
        }
        $html .= '</tr>';
    }
    return $html;
}

include 'template_pdf.php';
?>

<body>
    <div>
        <h1>Format Asuhan Keperawatan Gerontik</h1>
        <h2>Gerontik</h2>

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
                <td><strong>Tempat Praktek</strong></td>
                <td>:</td>
                <td><?= p($submission['rs_ruangan']) ?></td>
            </tr>
        </table>

        <h3>1. Identitas</h3>
        <?= geroRow('Nama', $identitas['nama'] ?? '') ?>
        <?= geroRow('Tempat Lahir', $identitas['tempat_lahir'] ?? '') ?>
        <?= geroRow('Tanggal Lahir', $identitas['tgl_lahir'] ?? '') ?>
        <?= geroRow('Jenis Kelamin', $identitas['jenis_kelamin'] ?? '') ?>
        <?= geroRow('Status Perkawinan', $identitas['status_perkawinan'] ?? '') ?>
        <?= geroRow('Agama', $identitas['agama'] ?? '') ?>
        <?= geroRow('Pendidikan', $identitas['pendidikan'] ?? '') ?>
        <?= geroRow('Pekerjaan', $identitas['pekerjaan'] ?? '') ?>
        <?= geroRow('Alamat', $identitas['alamat'] ?? '') ?>

        <div class="page-break"></div>

        <h3>2. Riwayat Kesehatan</h3>
        <?= geroRow('Keluhan Utama', $riwayat_kesehatan['keluhan_utama'] ?? '') ?>
        <?= geroRow('Riwayat Kesehatan Saat Ini', $riwayat_kesehatan['riwayat_kesehatan_saat_ini'] ?? '') ?>
        <?= geroRow('Berkualitas', geroBool($riwayat_kesehatan['berkualitas'] ?? '')) ?>
        <?= geroRow('Sehat', geroBool($riwayat_kesehatan['sehat'] ?? '')) ?>
        <?= geroRow('Aktif', geroBool($riwayat_kesehatan['aktif'] ?? '')) ?>
        <?= geroRow('Produktif', geroBool($riwayat_kesehatan['produktif'] ?? '')) ?>
        <?= geroRow('Sakit dengan Perawatan', geroBool($riwayat_kesehatan['sakit_perawatan'] ?? '')) ?>
        <?= geroRow('Sakit tanpa Perawatan', geroBool($riwayat_kesehatan['sakit_tanpa_perawatan'] ?? '')) ?>
        <?= geroRow('Riwayat Kesehatan Masa Lalu', $riwayat_kesehatan['riwayat_kesehatan_masa_lalu'] ?? '') ?>
        <?= geroRow('Imunisasi', $riwayat_kesehatan['imunisasi'] ?? '') ?>
        <?= geroRow('Alergi Obat', $riwayat_kesehatan['alergi_obat'] ?? '') ?>
        <?= geroRow('Kecelakaan', $riwayat_kesehatan['kecelakaan'] ?? '') ?>
        <?= geroRow('Kebiasaan Merokok', $riwayat_kesehatan['merokok'] ?? '') ?>
        <?= geroRow('Dirawat di Rumah Sakit', $riwayat_kesehatan['dirawat_rs'] ?? '') ?>
        <?= geroRow('Penyakit 1 Tahun Terakhir', $riwayat_kesehatan['penyakit_1_tahun'] ?? '') ?>
        <?= geroRow('Nama Obat (2 Minggu Terakhir)', $riwayat_kesehatan['obat_2_minggu'] ?? '') ?>
        <?= geroRow('Teratur Dikonsumsi', geroBool($riwayat_kesehatan['teratur_konsumsi'] ?? '')) ?>
        <?= geroRow('Obat Diresepkan Dokter', geroBool($riwayat_kesehatan['resep_dokter'] ?? '')) ?>
        <?= geroRow('Riwayat Gerontik', $riwayat_kesehatan['riwayat_gerontik'] ?? '') ?>
        <div class="field-row">
            <div class="field-label">Genogram</div>
            <div class="field-sep">:</div>
            <div class="field-value">
                <?php if (!empty($riwayat_kesehatan['genogram'])): ?>
                    <img src="<?= cetakGambar($riwayat_kesehatan['genogram']) ?>" style="max-height:240px;">
                <?php else: ?>
                    -
                <?php endif; ?>
            </div>
        </div>
        <?= geroRow('G1', $riwayat_kesehatan['G1'] ?? '') ?>
        <?= geroRow('G2', $riwayat_kesehatan['G2'] ?? '') ?>
        <?= geroRow('G3', $riwayat_kesehatan['G3'] ?? '') ?>

        <h3>3. Pemeriksaan Fisik</h3>
        <?= geroRow('TD', $pemeriksaan_fisik['td'] ?? '') ?>
        <?= geroRow('Nadi', $pemeriksaan_fisik['nadi'] ?? '') ?>
        <?= geroRow('RR', $pemeriksaan_fisik['rr'] ?? '') ?>
        <?= geroRow('Suhu', $pemeriksaan_fisik['suhu'] ?? '') ?>
        <?= geroRow('Tingkat Kesadaran', $pemeriksaan_fisik['tingkat_kesadaran'] ?? '') ?>
        <?php foreach (['kepala'=>'Kepala','mata'=>'Mata','telinga'=>'Telinga','hidung_sinus'=>'Hidung dan Sinus','mulut_tenggorokan'=>'Mulut dan Tenggorokan','leher'=>'Leher','pernapasan'=>'Pernapasan','kardiovaskuler'=>'Kardiovaskuler','gastrointestinal'=>'Gastrointestinal','perkemihan'=>'Perkemihan','muskuloskeletal'=>'Muskuloskeletal','endokrin'=>'Endokrin','neuro_motorik_sensoris'=>'Neuro Motorik Sensoris','integumen'=>'Integumen'] as $field => $label): ?>
            <?= geroRow($label, $pemeriksaan_fisik[$field] ?? '') ?>
        <?php endforeach; ?>

        <div class="page-break"></div>

        <h3>4. Pola Kebiasaan Harian</h3>
        <?php foreach ([
            'frekuensi_makan' => 'Frekuensi Makan',
            'nafsu_makan' => 'Nafsu Makan',
            'jenis_makanan' => 'Jenis Makanan',
            'makanan_tidak_disukai' => 'Makanan Tidak Disukai',
            'kebiasaan_sebelum_makan' => 'Kebiasaan / Ritual Sebelum Makan',
            'berat_tinggi_badan' => 'Berat / Tinggi Badan',
            'jenis_minuman' => 'Jenis Minuman',
            'jumlah_cairan' => 'Jumlah Cairan',
            'kesulitan_makan_minum' => 'Kesulitan Makan dan Minum',
            'makan_minum_bantu' => 'Untuk Makan dan Minum',
            'warna_bak' => 'Warna BAK',
            'keluhan_bak' => 'Keluhan BAK',
            'dibantu_bak' => 'Dibantu BAK',
            'mandiri_bak' => 'Mandiri BAK',
            'frekuensi_bab' => 'Frekuensi BAB',
            'bau_bab' => 'Bau BAB',
            'warna_bab' => 'Warna BAB',
            'konsistensi_bab' => 'Konsistensi BAB',
            'keluhan_bab' => 'Keluhan BAB',
            'pengalaman_laksatif' => 'Pengalaman Memakai Laksatif',
            'dibantu_bab' => 'Dibantu BAB',
            'mandiri_bab' => 'Mandiri BAB',
            'frekuensi_mandi' => 'Frekuensi Mandi',
            'dibantu_mandi' => 'Dibantu Mandi',
            'mandiri_mandi' => 'Mandiri Mandi',
            'frekuensi_hygiene_oral' => 'Frekuensi Hygiene Oral',
            'dibantu_hygiene_oral' => 'Dibantu Hygiene Oral',
            'mandiri_hygiene_oral' => 'Mandiri Hygiene Oral',
            'frekuensi_cuci_rambut' => 'Frekuensi Cuci Rambut',
            'dibantu_cuci_rambut' => 'Dibantu Cuci Rambut',
            'mandiri_cuci_rambut' => 'Mandiri Cuci Rambut',
            'frekuensi_gunting_kuku' => 'Frekuensi Gunting Kuku',
            'dibantu_gunting_kuku' => 'Dibantu Gunting Kuku',
            'mandiri_gunting_kuku' => 'Mandiri Gunting Kuku',
            'lama_tidur' => 'Lama Tidur',
            'kesulitan_tidur' => 'Kesulitan Tidur',
            'tidur_siang' => 'Tidur Siang',
            'olahraga_ringan' => 'Melakukan Olahraga Ringan',
            'jenis_frekuensi_olahraga' => 'Jenis dan Frekuensi Olahraga',
            'kegiatan_waktu_luang' => 'Kegiatan Waktu Luang',
            'keluhan_aktivitas' => 'Keluhan Beraktifitas',
            'kesulitan_pergerakan' => 'Kesulitan Pergerakan Tubuh',
            'sesak_nafas' => 'Sesak Nafas Setelah Aktivitas',
        ] as $key => $label): ?>
            <?= geroRow($label, geroBool($kebiasaan_harian[$key] ?? '')) ?>
        <?php endforeach; ?>

        <h3>5. Psikososial &amp; Spiritual</h3>
        <?php foreach ($psikososial_spiritual as $key => $value): ?>
            <?= geroRow(str_replace('_', ' ', ucfirst($key)), geroBool($value)) ?>
        <?php endforeach; ?>

        <h3>6. Status Fungsional</h3>
        <?php foreach (['makan'=>'Makan','kontinen'=>'Kontinen','berpindah'=>'Berpindah','kamar_kecil'=>'Kamar Kecil','berpakaian'=>'Berpakaian','mandi'=>'Mandi'] as $field => $label): ?>
            <?= geroRow($label, $status_fungsional[$field] ?? '') ?>
        <?php endforeach; ?>
        <?= geroRow('Kesimpulan', $status_fungsional['kesimpulan_status_fungsional'] ?? '') ?>

        <h3>7. Skala Depresi</h3>
        <?php for ($i = 1; $i <= 30; $i++): ?>
            <?= geroRow('Pertanyaan ' . $i, $skala_depresi['q' . $i] ?? '') ?>
        <?php endfor; ?>
        <?= geroRow('Kesimpulan', $skala_depresi['kesimpulan'] ?? '') ?>

        <div class="page-break"></div>

        <h3>8. APGAR / SPMSQ / Risiko Jatuh</h3>
        <?php foreach (['A','P','G','A2','R'] as $field): ?>
            <?= geroRow($field === 'A2' ? 'A (afek)' : $field, $apgar[$field] ?? '') ?>
        <?php endforeach; ?>
        <?= geroRow('Kesimpulan APGAR', $apgar['kesimpulan_apgar'] ?? '') ?>
        <table class="data">
            <thead>
                <tr>
                    <th style="width:8%">No</th>
                    <th>Pertanyaan</th>
                    <th style="width:26%">Jawaban</th>
                    <th style="width:8%">B</th>
                    <th style="width:8%">S</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ([
                    'Tanggal berapa hari ini? (Hari / Tanggal / Tahun)',
                    'Hari apa sekarang?',
                    'Apa nama tempat / kelurahan ini?',
                    'Di mana alamat lengkap Anda?',
                    'Berapa umur Anda?',
                    'Kapan Anda lahir?',
                    'Presiden Indonesia sekarang?',
                    'Siapa nama presiden sebelumnya?',
                    'Siapa nama kecil ibu Anda?',
                    'Perhitungan 20 - 3, kemudian hasilnya dikurangi 3 terus sampai mendapat angka 0.',
                ] as $i => $question): ?>
                    <tr>
                        <td style="text-align:center"><?= $i + 1 ?></td>
                        <td><?= p($question) ?></td>
                        <td><?= p($apgar['jawaban_spmsq_' . ($i + 1)] ?? '') ?></td>
                        <td style="text-align:center"><?= geroSpmsqMark($apgar['q' . ($i + 1)] ?? '', 'B') ?></td>
                        <td style="text-align:center"><?= geroSpmsqMark($apgar['q' . ($i + 1)] ?? '', 'S') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?= geroRow('Kesimpulan SPMSQ', $apgar['kesimpulan_spmsq'] ?? '') ?>
        <?php foreach (['riwayat_jatuh'=>'Riwayat Jatuh','status_mental'=>'Status Mental','penglihatan'=>'Penglihatan','berkemih'=>'Berkemih','transfer'=>'Transfer','mobilitas'=>'Mobilitas'] as $field => $label): ?>
            <?= geroRow($label, geroBool($apgar[$field] ?? '')) ?>
        <?php endforeach; ?>
        <?= geroRow('Kesimpulan Penilaian', $apgar['kesimpulan_penilaian'] ?? '') ?>

        <h3>9. Catatan Keperawatan</h3>
        <table class="data">
            <thead><tr><th>No</th><th>Diagnosa</th><th>Tanggal Ditemukan</th><th>Tanggal Teratasi</th></tr></thead>
            <tbody><?= geroTableRows($catatan['diagnosa'] ?? [], ['diagnosa', 'tgl_ditemukan', 'tgl_teratasi']) ?></tbody>
        </table>
        <table class="data">
            <thead><tr><th>No</th><th>Diagnosa</th><th>Tujuan dan Kriteria Hasil</th><th>Rencana</th></tr></thead>
            <tbody><?= geroTableRows($catatan['rencana'] ?? [], ['diagnosa', 'tujuan_kriteria', 'rencana']) ?></tbody>
        </table>
        <table class="data">
            <thead><tr><th>No. Dx</th><th>Hari/Tanggal</th><th>Jam</th><th>Implementasi</th></tr></thead>
            <tbody><?= geroTableRows($catatan['implementasi'] ?? [], ['no_dx', 'hari_tgl', 'jam', 'implementasi']) ?></tbody>
        </table>
        <table class="data">
            <thead><tr><th>No. Dx</th><th>Hari/Tanggal</th><th>Jam</th><th>S</th><th>O</th><th>A</th><th>P</th></tr></thead>
            <tbody><?= geroTableRows($catatan['evaluasi'] ?? [], ['no_dx', 'hari_tgl', 'jam', 'evaluasi_s', 'evaluasi_o', 'evaluasi_a', 'evaluasi_p']) ?></tbody>
        </table>
    </div>
</body>
