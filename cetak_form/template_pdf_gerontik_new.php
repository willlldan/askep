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

function geroNewBool($val)
{
    $val = (string)$val;
    if (in_array($val, ['Y', 'Ya', 'ya', '1'], true)) return 'Ya';
    if (in_array($val, ['T', 'Tidak', 'tidak', '0'], true)) return 'Tidak';
    return p($val);
}

function geroNewMandiri($val)
{
    $val = (string)$val;
    if ($val === 'mandiri') return 'Mandiri';
    if ($val === 'tergantung') return 'Tergantung';
    return p($val);
}

function geroNewApgar($val)
{
    $map = ['2' => 'Selalu', '1' => 'Kadang', '0' => 'Tidak pernah'];
    $val = (string)$val;
    return $map[$val] ?? p($val);
}

function geroNewSpmsq($val)
{
    $map = ['B' => 'Benar', 'S' => 'Salah'];
    $val = (string)$val;
    return $map[$val] ?? p($val);
}

function geroNewFallAnswer($field, $val)
{
    $val = (string)$val;
    if (in_array($field, ['transfer', 'mobilitas'], true)) {
        $map = [
            '0' => $field === 'transfer' ? 'Mandiri (boleh memakai alat bantu)' : 'Mandiri (boleh menggunakan alat)',
            '1' => $field === 'transfer' ? 'Memerlukan sedikit bantuan orang dewasa (1 orang)' : 'Berjalan dengan bantuan 1 orang (fisik / verbal)',
            '2' => $field === 'transfer' ? 'Bantuan yang nyata 2 orang' : 'Menggunakan kursi roda',
            '3' => $field === 'transfer' ? 'Tidak dapat duduk seimbang, perlu bantuan total' : 'Imobilisasi',
        ];
        return $map[$val] ?? p($val);
    }
    return geroNewBool($val);
}

function geroNewRow($label, $value)
{
    return '<div class="field-row"><div class="field-label">' . htmlspecialchars($label) . '</div><div class="field-sep">:</div><div class="field-value">' . p($value) . '</div></div>';
}

function geroNewRows(array $items, array $data, callable $formatter = null)
{
    $html = '';
    foreach ($items as $item) {
        $value = $data[$item['field']] ?? '';
        if ($formatter) {
            $value = $formatter($item['field'], $value);
        }
        $html .= geroNewRow($item['label'], $value);
    }
    return $html;
}

function geroNewTableRows($rows, $fields, $emptyLabel = '-')
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

function geroNewApgarCategory($score)
{
    if ($score <= 2) return 'Disfungsi Gerontik Sangat Tinggi';
    if ($score <= 6) return 'Disfungsi Gerontik Sedang';
    if ($score <= 8) return 'Disfungsi Gerontik Ringan';
    return 'Normal';
}

function geroNewSpmsqCategory($errors)
{
    if ($errors <= 2) return 'fungsi intelektual utuh';
    if ($errors <= 4) return 'gangguan intelektual ringan';
    if ($errors <= 7) return 'gangguan intelektual sedang';
    return 'gangguan intelektual berat';
}

function geroNewFallCategory($score)
{
    if ($score <= 5) return 'Risiko Rendah';
    if ($score <= 16) return 'Risiko Sedang';
    return 'Risiko Tinggi';
}

function geroNewFallScore($data)
{
    $riwayat = (in_array((string)($data['riwayat_jatuh_1'] ?? ''), ['Y', 'Ya'], true) || in_array((string)($data['riwayat_jatuh_2'] ?? ''), ['Y', 'Ya'], true)) ? 6 : 0;
    $status = (in_array((string)($data['status_mental_1'] ?? ''), ['Y', 'Ya'], true) || in_array((string)($data['status_mental_2'] ?? ''), ['Y', 'Ya'], true) || in_array((string)($data['status_mental_3'] ?? ''), ['Y', 'Ya'], true)) ? 14 : 0;
    $penglihatan = (in_array((string)($data['penglihatan_1'] ?? ''), ['Y', 'Ya'], true) || in_array((string)($data['penglihatan_2'] ?? ''), ['Y', 'Ya'], true) || in_array((string)($data['penglihatan_3'] ?? ''), ['Y', 'Ya'], true)) ? 1 : 0;
    $berkemih = in_array((string)($data['berkemih'] ?? ''), ['Y', 'Ya'], true) ? 2 : 0;
    $transfer = (int)($data['transfer'] ?? 0);
    $mobilitas = (int)($data['mobilitas'] ?? 0);
    $tm = (($transfer + $mobilitas) <= 3) ? 0 : 7;
    $total = $riwayat + $status + $penglihatan + $berkemih + $tm;
    return [$riwayat, $status, $penglihatan, $berkemih, $tm, $total];
}

function geroNewApgarScore($data)
{
    $fields = ['A', 'P', 'G', 'A2', 'R'];
    $score = 0;
    $answered = 0;
    foreach ($fields as $field) {
        $value = (string)($data[$field] ?? '');
        if ($value !== '') {
            $answered++;
            $score += (int)$value;
        }
    }
    return [$answered ? $score : '', $answered ? ($score . ' - ' . geroNewApgarCategory($score)) : ''];
}

function geroNewSpmsqScore($data)
{
    $benar = 0;
    $salah = 0;
    $answered = 0;
    foreach (range(1, 10) as $i) {
        $value = (string)($data['q' . $i] ?? '');
        if ($value !== '') {
            $answered++;
            if ($value === 'B') $benar++;
            if ($value === 'S') $salah++;
        }
    }
    return [$answered ? $benar : '', $answered ? $salah : '', $answered ? ($salah . ' - ' . geroNewSpmsqCategory($salah)) : ''];
}

function geroNewDepressionQuestions()
{
    return [
        1  => 'Apakah pada dasarnya Anda puas dengan kehidupan Anda?',
        2  => 'Sudahkah Anda banyak menghentikan aktivitas dan minat Anda?',
        3  => 'Apakah Anda merasa bahwa hidup Anda kosong?',
        4  => 'Apakah Anda sering bosan?',
        5  => 'Apakah Anda banyak berharap pada masa depan?',
        6  => 'Apakah Anda takut sesuatu akan terjadi pada Anda?',
        7  => 'Apakah Anda merasa terganggu dengan pemikiran bahwa Anda tidak bisa lepas dari pikiran yang sama?',
        8  => 'Apakah Anda takut bahwa suatu hal yang buruk akan menimpa Anda?',
        9  => 'Apakah Anda merasa gembira dalam sebagian besar waktu Anda?',
        10 => 'Apakah Anda merasa tidak mungkin tertolong?',
        11 => 'Apakah Anda sering menjadi gelisah atau sering / mudah terkejut?',
        12 => 'Apakah Anda lebih suka tinggal di rumah pada malam hari daripada pergi dan melakukan sesuatu yang baru?',
        13 => 'Apakah Anda sering mengkhawatirkan masa depan?',
        14 => 'Apakah Anda merasa bahwa Anda mempunyai lebih banyak masalah dengan ingatan Anda daripada yang lainnya?',
        15 => 'Apakah Anda berpikir sangat menyenangkan hidup sekarang ini?',
        16 => 'Apakah Anda sering merasa tidak enak hati atau sedih?',
        17 => 'Apakah Anda sering merasa benar-benar tidak berharga saat ini?',
        18 => 'Apakah Anda cukup sering khawatir mengenai masa lampau?',
        19 => 'Apakah Anda merasa kehidupan itu menyenangkan?',
        20 => 'Apakah sulit bagi Anda memulai hal yang baru?',
        21 => 'Apakah Anda merasa penuh berenergi / semangat?',
        22 => 'Apakah Anda berpikir bahwa situasi Anda menggambarkan keputusasaan / tidak ada harapan?',
        23 => 'Apakah Anda berpikir bahwa banyak orang yang lebih baik dari pada Anda?',
        24 => 'Apakah Anda sering menjadi kesal dikarenakan hal kecil?',
        25 => 'Apakah Anda sering merasakan menangis?',
        26 => 'Apakah Anda merasa kesulitan untuk berkonsentrasi?',
        27 => 'Apakah Anda menikmati bangun pagi setiap hari?',
        28 => 'Apakah Anda lebih menghindar dari perkumpulan sosial?',
        29 => 'Apakah mudah bagi Anda membuat keputusan?',
        30 => 'Apakah pemikiran / benak Anda sejernih di masa-masa lalu?',
    ];
}

function geroNewDepressionScore($data)
{
    $questions = geroNewDepressionQuestions();
    $answerKey = [
        'q1'  => 'Tidak', 'q2'  => 'Ya', 'q3'  => 'Ya', 'q4'  => 'Ya', 'q5'  => 'Tidak',
        'q6'  => 'Ya', 'q7'  => 'Ya', 'q8'  => 'Ya', 'q9'  => 'Tidak', 'q10' => 'Ya',
        'q11' => 'Ya', 'q12' => 'Ya', 'q13' => 'Ya', 'q14' => 'Ya', 'q15' => 'Tidak',
        'q16' => 'Ya', 'q17' => 'Ya', 'q18' => 'Ya', 'q19' => 'Tidak', 'q20' => 'Ya',
        'q21' => 'Tidak', 'q22' => 'Ya', 'q23' => 'Ya', 'q24' => 'Ya', 'q25' => 'Ya',
        'q26' => 'Ya', 'q27' => 'Tidak', 'q28' => 'Ya', 'q29' => 'Tidak', 'q30' => 'Tidak',
    ];

    $score = 0;
    $answered = 0;
    foreach ($questions as $i => $_) {
        $value = (string)($data['q' . $i] ?? '');
        if ($value !== '') {
            $answered++;
            if (strcasecmp($value, $answerKey['q' . $i]) === 0) $score++;
        }
    }

    if (!$answered) {
        return ['', ''];
    }

    $label = $score <= 4 ? 'Normal' : ($score <= 14 ? 'Depresi Ringan' : ($score <= 22 ? 'Depresi Sedang' : 'Depresi Berat'));
    return [$score, $score . ' - ' . $label];
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
                <td><strong>Ruangan</strong></td>
                <td>:</td>
                <td><?= p($submission['rs_ruangan']) ?></td>
            </tr>
        </table>

        <h3>1. Identitas</h3>
        <?= geroNewRow('Nama', $identitas['nama'] ?? '') ?>
        <?= geroNewRow('Tempat Lahir', $identitas['tempat_lahir'] ?? '') ?>
        <?= geroNewRow('Tanggal Lahir', $identitas['tgl_lahir'] ?? '') ?>
        <?= geroNewRow('Jenis Kelamin', $identitas['jenis_kelamin'] ?? '') ?>
        <?= geroNewRow('Status Perkawinan', $identitas['status_perkawinan'] ?? '') ?>
        <?= geroNewRow('Agama', $identitas['agama'] ?? '') ?>
        <?= geroNewRow('Pendidikan', $identitas['pendidikan'] ?? '') ?>
        <?= geroNewRow('Pekerjaan', $identitas['pekerjaan'] ?? '') ?>
        <?= geroNewRow('Alamat', $identitas['alamat'] ?? '') ?>

        <div class="page-break"></div>

        <h3>2. Riwayat Kesehatan</h3>
        <?= geroNewRow('Keluhan Utama', $riwayat_kesehatan['keluhan_utama'] ?? '') ?>
        <?= geroNewRow('Riwayat Kesehatan Saat Ini', $riwayat_kesehatan['riwayat_kesehatan_saat_ini'] ?? '') ?>
        <div class="subsection-title">Status Lanjut Usia</div>
        <?php foreach ([
            ['field' => 'berkualitas', 'label' => 'Berkualitas'],
            ['field' => 'sehat', 'label' => 'Sehat'],
            ['field' => 'aktif', 'label' => 'Aktif'],
            ['field' => 'produktif', 'label' => 'Produktif'],
            ['field' => 'sakit_perawatan', 'label' => 'Sakit dengan Perawatan'],
            ['field' => 'sakit_tanpa_perawatan', 'label' => 'Sakit tanpa Perawatan'],
        ] as $item): ?>
            <?= geroNewRow($item['label'], geroNewBool($riwayat_kesehatan[$item['field']] ?? '')) ?>
        <?php endforeach; ?>
        <?= geroNewRow('Riwayat Kesehatan Masa Lalu', $riwayat_kesehatan['riwayat_kesehatan_masa_lalu'] ?? '') ?>
        <?= geroNewRow('Riwayat Gerontik', $riwayat_kesehatan['riwayat_gerontik'] ?? '') ?>
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
        <?= geroNewRow('G1', $riwayat_kesehatan['G1'] ?? '') ?>
        <?= geroNewRow('G2', $riwayat_kesehatan['G2'] ?? '') ?>
        <?= geroNewRow('G3', $riwayat_kesehatan['G3'] ?? '') ?>

        <h3>3. Pemeriksaan Fisik</h3>
        <?= geroNewRow('TD (Tekanan Darah)', $pemeriksaan_fisik['td'] ?? '') ?>
        <?= geroNewRow('N (Nadi)', $pemeriksaan_fisik['nadi'] ?? '') ?>
        <?= geroNewRow('RR (Frekuensi Pernafasan)', $pemeriksaan_fisik['rr'] ?? '') ?>
        <?= geroNewRow('Suhu (Celsius)', $pemeriksaan_fisik['suhu'] ?? '') ?>
        <?= geroNewRow('Tingkat Kesadaran', $pemeriksaan_fisik['tingkat_kesadaran'] ?? '') ?>

        <?php
        $physicalGroups = [
            '1. Kepala' => [
                ['field' => 'kepala', 'label' => 'Kepala'],
            ],
            '2. Mata' => [
                ['field' => 'mata', 'label' => 'Mata'],
            ],
            '3. Telinga' => [
                ['field' => 'telinga', 'label' => 'Telinga'],
            ],
            '4. Hidung dan Sinus' => [
                ['field' => 'hidung_sinus', 'label' => 'Hidung dan Sinus'],
            ],
            '5. Mulut dan Tenggorokan' => [
                ['field' => 'mulut_tenggorokan', 'label' => 'Mulut dan Tenggorokan'],
            ],
            '6. Leher' => [
                ['field' => 'leher', 'label' => 'Leher'],
            ],
            '7. Pernapasan' => [
                ['field' => 'pernapasan', 'label' => 'Pernapasan'],
            ],
            '8. Kardiovaskuler' => [
                ['field' => 'kardiovaskuler', 'label' => 'Kardiovaskuler'],
            ],
            '9. Gastrointestinal' => [
                ['field' => 'gastrointestinal', 'label' => 'Gastrointestinal'],
            ],
            '10. Perkemihan' => [
                ['field' => 'perkemihan', 'label' => 'Perkemihan'],
            ],
            '11. Muskuloskeletal' => [
                ['field' => 'muskuloskeletal', 'label' => 'Muskuloskeletal'],
            ],
            '12. Endokrin' => [
                ['field' => 'endokrin', 'label' => 'Endokrin'],
            ],
            '14. Integumen' => [
                ['field' => 'integumen', 'label' => 'Integumen'],
            ],
        ];
        ?>
        <?php foreach ($physicalGroups as $title => $items): ?>
            <div class="subsection-title"><?= $title ?></div>
            <?= geroNewRows($items, $pemeriksaan_fisik, function ($field, $value) {
                return geroNewBool($value);
            }) ?>
        <?php endforeach; ?>

        <div class="page-break"></div>

        <h3>4. Pola Kebiasaan Sehari-Hari</h3>
        <div class="subsection-title">1. Nutrisi dan Cairan</div>
        <?= geroNewRows([
            ['field' => 'frekuensi_makan', 'label' => 'Frekuensi Makan'],
            ['field' => 'nafsu_makan', 'label' => 'Nafsu Makan'],
            ['field' => 'jenis_makanan', 'label' => 'Jenis Makanan'],
            ['field' => 'makanan_tidak_disukai', 'label' => 'Makanan yang Tidak Disukai'],
            ['field' => 'kebiasaan_sebelum_makan', 'label' => 'Kebiasaan / Ritual Sebelum Makan'],
            ['field' => 'berat_tinggi_badan', 'label' => 'Berat Badan / Tinggi Badan'],
            ['field' => 'jenis_minuman', 'label' => 'Jenis Minuman'],
            ['field' => 'jumlah_cairan', 'label' => 'Jumlah Cairan yang Dikonsumsi'],
            ['field' => 'kesulitan_makan_minum', 'label' => 'Kesulitan Makan dan Minum'],
            ['field' => 'makan_minum_bantu', 'label' => 'Untuk Makan dan Minum'],
        ], $kebiasaan_harian, function ($field, $value) {
            if ($field === 'makan_minum_bantu') return (string)$value === 'Y' ? 'Dibantu' : ((string)$value === 'T' ? 'Mandiri' : p($value));
            return geroNewBool($value);
        }) ?>

        <div class="subsection-title">2. Eliminasi</div>
        <div class="subsection-title">a. Berkemih (BAK)</div>
        <?= geroNewRows([
            ['field' => 'warna_bak', 'label' => 'Warna'],
            ['field' => 'keluhan_bak', 'label' => 'Keluhan yang Berhubungan dengan BAK'],
            ['field' => 'dibantu_bak', 'label' => 'Dibantu'],
            ['field' => 'mandiri_bak', 'label' => 'Mandiri'],
        ], $kebiasaan_harian, function ($field, $value) {
            return in_array($field, ['dibantu_bak', 'mandiri_bak'], true) ? geroNewBool($value) : p($value);
        }) ?>

        <div class="subsection-title">b. Defekasi (BAB)</div>
        <?= geroNewRows([
            ['field' => 'frekuensi_bab', 'label' => 'Frekuensi'],
            ['field' => 'bau_bab', 'label' => 'Bau'],
            ['field' => 'warna_bab', 'label' => 'Warna'],
            ['field' => 'konsistensi_bab', 'label' => 'Konsistensi'],
            ['field' => 'keluhan_bab', 'label' => 'Keluhan yang Berhubungan dengan Defekasi'],
            ['field' => 'pengalaman_laksatif', 'label' => 'Pengalaman Memakai Laksatif'],
            ['field' => 'dibantu_bab', 'label' => 'Dibantu'],
            ['field' => 'mandiri_bab', 'label' => 'Mandiri'],
        ], $kebiasaan_harian, function ($field, $value) {
            return in_array($field, ['dibantu_bab', 'mandiri_bab'], true) ? geroNewBool($value) : p($value);
        }) ?>

        <div class="subsection-title">3. Hygiene Personal</div>
        <div class="subsection-title">a. Mandi</div>
        <?= geroNewRows([
            ['field' => 'frekuensi_mandi', 'label' => 'Frekuensi'],
            ['field' => 'dibantu_mandi', 'label' => 'Dibantu'],
            ['field' => 'mandiri_mandi', 'label' => 'Mandiri'],
        ], $kebiasaan_harian, fn ($field, $value) => in_array($field, ['dibantu_mandi', 'mandiri_mandi'], true) ? geroNewBool($value) : p($value)) ?>

        <div class="subsection-title">b. Hygiene Oral</div>
        <?= geroNewRows([
            ['field' => 'frekuensi_hygiene_oral', 'label' => 'Frekuensi'],
            ['field' => 'dibantu_hygiene_oral', 'label' => 'Dibantu'],
            ['field' => 'mandiri_hygiene_oral', 'label' => 'Mandiri'],
        ], $kebiasaan_harian, fn ($field, $value) => in_array($field, ['dibantu_hygiene_oral', 'mandiri_hygiene_oral'], true) ? geroNewBool($value) : p($value)) ?>

        <div class="subsection-title">c. Cuci Rambut</div>
        <?= geroNewRows([
            ['field' => 'frekuensi_cuci_rambut', 'label' => 'Frekuensi'],
            ['field' => 'dibantu_cuci_rambut', 'label' => 'Dibantu'],
            ['field' => 'mandiri_cuci_rambut', 'label' => 'Mandiri'],
        ], $kebiasaan_harian, fn ($field, $value) => in_array($field, ['dibantu_cuci_rambut', 'mandiri_cuci_rambut'], true) ? geroNewBool($value) : p($value)) ?>

        <div class="subsection-title">d. Gunting Kuku</div>
        <?= geroNewRows([
            ['field' => 'frekuensi_gunting_kuku', 'label' => 'Frekuensi'],
            ['field' => 'dibantu_gunting_kuku', 'label' => 'Dibantu'],
            ['field' => 'mandiri_gunting_kuku', 'label' => 'Mandiri'],
        ], $kebiasaan_harian, fn ($field, $value) => in_array($field, ['dibantu_gunting_kuku', 'mandiri_gunting_kuku'], true) ? geroNewBool($value) : p($value)) ?>

        <div class="subsection-title">4. Istirahat dan Tidur</div>
        <?= geroNewRows([
            ['field' => 'lama_tidur', 'label' => 'Lama Tidur (Jam/Hari)'],
            ['field' => 'kesulitan_tidur', 'label' => 'Kesulitan / Gangguan Tidur'],
            ['field' => 'tidur_siang', 'label' => 'Tidur Siang'],
        ], $kebiasaan_harian, fn ($field, $value) => in_array($field, ['kesulitan_tidur', 'tidur_siang'], true) ? geroNewBool($value) : p($value)) ?>

        <div class="subsection-title">5. Aktivitas dan Latihan</div>
        <?= geroNewRows([
            ['field' => 'olahraga_ringan', 'label' => 'Olahraga Ringan'],
            ['field' => 'jenis_frekuensi_olahraga', 'label' => 'Jenis dan Frekuensi'],
            ['field' => 'kegiatan_waktu_luang', 'label' => 'Kegiatan Waktu Luang'],
            ['field' => 'keluhan_aktivitas', 'label' => 'Keluhan Beraktivitas'],
            ['field' => 'kesulitan_pergerakan', 'label' => 'Kesulitan Pergerakan'],
            ['field' => 'sesak_nafas', 'label' => 'Sesak Nafas Setelah Aktivitas'],
        ], $kebiasaan_harian, fn ($field, $value) => in_array($field, ['olahraga_ringan', 'keluhan_aktivitas', 'kesulitan_pergerakan', 'sesak_nafas'], true) ? geroNewBool($value) : p($value)) ?>

        <div class="page-break"></div>

        <h3>5. Psikososial &amp; Spiritual</h3>
        <?= geroNewRows([
            ['field' => 'kondisi_lansia', 'label' => 'Lansia menyadari dan menerima kondisinya / kesehatannya tidak seperti saat muda'],
            ['field' => 'penyesuaian_lansia', 'label' => 'Lansia menyesuaikan / tidak memaksakan pekerjaan / aktivitas yang dilakukan'],
            ['field' => 'prolanis_lansia', 'label' => 'Lansia rutin mengikuti kegiatan Prolanis'],
            ['field' => 'periksa_kesehatan_lansia', 'label' => 'Lansia rutin memeriksakan kesehatannya di praktik dokter / puskesmas'],
            ['field' => 'posyandu_lansia', 'label' => 'Lansia masih mengikuti kegiatan pemeriksaan kesehatan di Posyandu lansia'],
            ['field' => 'kegiatan_rt_lansia', 'label' => 'Lansia masih sempat mengikuti kegiatan-kegiatan yang dilaksanakan oleh RT'],
            ['field' => 'dukungan_gerontik', 'label' => 'Gerontik memberikan dukungan dan peduli terhadap kesehatan lansia'],
            ['field' => 'ingatkan_pantangan', 'label' => 'Gerontik mengingatkan pantangan makanan bagi kesehatan lansia'],
            ['field' => 'senang_berkumpul', 'label' => 'Lansia merasa senang bila sedang berkumpul dengan anak dan cucu'],
            ['field' => 'rutin_ibadah', 'label' => 'Lansia masih rutin menjalankan ibadah'],
            ['field' => 'bersyukur', 'label' => 'Lansia merasa bersyukur kepada Tuhan YME dengan kondisinya saat ini'],
            ['field' => 'berkembang_usia', 'label' => 'Lansia menganggap bahwa semakin bertambahnya usia semakin mendekatkan diri kepada Tuhan YME'],
        ], $psikososial_spiritual, fn ($field, $value) => geroNewBool($value)) ?>

        <h3>6. Status Fungsional</h3>
        <p>Pengkajian status fungsional adalah suatu bentuk pengukuran kemampuan seseorang untuk melakukan aktivitas sehari-hari secara mandiri. Pengkajian ini menggunakan indeks kemandirian Katz.</p>
        <p>Kemandirian berarti tanpa pengawasan, pengarahan, atau bantuan pribadi aktif. Pengkajian ini didasarkan pada kondisi aktual klien dan bukan pada kemampuan.</p>
        <table class="data">
            <thead>
                <tr><th style="width:20%">Kegiatan</th><th style="width:40%">Mandiri</th><th>Tergantung</th></tr>
            </thead>
            <tbody>
                <tr><td>Makan</td><td>Memilih makanan dari piring dan menyuapi sendiri</td><td>Bantuan dalam hal mengambil makanan dan menyuapinya, tidak makan sama sekali, makan parenteral/enteral.</td></tr>
                <tr><td>Kontinen (Defekasi/Berkemih)</td><td>Berkemih dan defekasi sepenuhnya dikendalikan sendiri</td><td>Inkontinensia parsial atau total, penggunaan kateter, pispot, enema, pembalut (diapers)</td></tr>
                <tr><td>Berpindah</td><td>Berpindah ke dan dari tempat tidur, bangkit dari kursi sendiri</td><td>Bantuan dalam naik dan turun dari tempat tidur atau kursi, tidak melakukan satu atau lebih perpindahan</td></tr>
                <tr><td>Ke Kamar Kecil</td><td>Masuk dan keluar kamar kecil, membersihkan genitalia sendiri</td><td>Menerima bantuan untuk masuk ke kamar kecil dan menggunakan pispot</td></tr>
                <tr><td>Berpakaian</td><td>Memilih baju dari lemari, memakai dan melepaskan pakaian, mengancing atau mengikat pakaian</td><td>Tidak dapat memakai baju sendiri atau sebagian</td></tr>
                <tr><td>Mandi</td><td>Bantuan hanya pada satu bagian tubuh (seperti punggung atau ekstremitas yang tidak mampu) atau mandi sepenuhnya sendiri</td><td>Bantuan mandi lebih dari satu bagian tubuh, bantuan masuk dan keluar dari bak mandi, tidak mandi sendiri</td></tr>
            </tbody>
        </table>
        <div class="subsection-title">Kriteria Penilaian</div>
        <table class="data">
            <thead><tr><th style="width:10%">Nilai</th><th>Keterangan</th></tr></thead>
            <tbody>
                <tr><td>A</td><td>Kemandirian dalam hal makan, kontinen (defekasi/berkemih), berpindah, ke kamar kecil, berpakaian, dan mandi.</td></tr>
                <tr><td>B</td><td>Kemandirian dalam semua hal kecuali satu dari fungsi tersebut.</td></tr>
                <tr><td>C</td><td>Kemandirian dalam semua hal kecuali mandi dan satu fungsi tambahan.</td></tr>
                <tr><td>D</td><td>Kemandirian dalam semua hal kecuali mandi, berpakaian, dan satu fungsi tambahan.</td></tr>
                <tr><td>E</td><td>Kemandirian dalam semua hal kecuali mandi, berpakaian, ke kamar kecil, dan satu fungsi tambahan.</td></tr>
                <tr><td>F</td><td>Kemandirian dalam semua hal kecuali mandi, berpakaian, ke kamar kecil, berpindah, dan satu fungsi tambahan.</td></tr>
                <tr><td>G</td><td>Ketergantungan pada keenam fungsi tersebut.</td></tr>
            </tbody>
        </table>

        <h3>7. Skala Depresi</h3>
        <p>Pengkajian ini menggunakan skala depresi geriatric bentuk singkat dari Yesavage (1983) yang disusun khusus untuk lanjut usia. Nilai 5 atau lebih dapat menandakan depresi.</p>
        <?php [$depresi_score, $depresi_conclusion] = geroNewDepressionScore($skala_depresi); ?>
        <table class="data">
            <thead><tr><th style="width:8%">No</th><th>Pertanyaan</th><th style="width:20%">Jawaban</th></tr></thead>
            <tbody>
                <?php foreach (geroNewDepressionQuestions() as $i => $question): ?>
                    <tr>
                        <td style="text-align:center"><?= $i ?></td>
                        <td><?= p($question) ?></td>
                        <td><?= p($skala_depresi['q' . $i] ?? '') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?= geroNewRow('Kesimpulan', $depresi_conclusion ?: ($skala_depresi['kesimpulan'] ?? '')) ?>

        <div class="page-break"></div>

        <h3>8. APGAR / SPMSQ / Risiko Jatuh</h3>
        <h4>XI. APGAR Gerontik</h4>
        <p>APGAR Gerontik ditujukan untuk mengkaji fungsi sosial lansia. A (adaptation / adaptasi), P (partnership / hubungan), G (growth / pertumbuhan), A (affection / kedekatan), R (resolve / pemecahan).</p>
        <?php [$apgarScore, $apgarConclusion] = geroNewApgarScore($apgar); ?>
        <table class="data">
            <thead><tr><th style="width:8%">No</th><th>Pertanyaan</th><th style="width:20%">Jawaban</th></tr></thead>
            <tbody>
                <?php foreach ([
                    ['field' => 'A', 'label' => 'Saya puas bisa kembali pada teman saya untuk membantu saya bila suatu waktu ada kondisi yang menyusahkan saya.'],
                    ['field' => 'P', 'label' => 'Saya puas dengan cara teman saya membicarakan sesuatu dan mengungkapkan masalah dengan saya.'],
                    ['field' => 'G', 'label' => 'Saya puas bahwa teman saya menerima dan mendukung keinginan untuk melakukan aktivitas.'],
                    ['field' => 'A2', 'label' => 'Saya puas dengan cara teman saya mengekspresikan afek dan berespon terhadap emosi saya seperti marah, sedih, atau mencintai.'],
                    ['field' => 'R', 'label' => 'Saya puas dengan cara teman saya menyediakan waktu secara bersama-sama.'],
                ] as $idx => $item): ?>
                    <tr>
                        <td style="text-align:center"><?= $idx + 1 ?></td>
                        <td><?= p($item['label']) ?></td>
                        <td><?= geroNewApgar($apgar[$item['field']] ?? '') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?= geroNewRow('Skor APGAR', $apgarScore) ?>
        <?= geroNewRow('Kesimpulan APGAR', $apgarConclusion) ?>

        <h4>XII. SPMSQ</h4>
        <p>Pemeriksaan Short Portable Status Questionnaire (SPMSQ) digunakan untuk mendeteksi adanya tingkat gangguan intelektual / memori. Catatlah jumlah kesalahan dari semua pertanyaan.</p>
        <?php [$spmsqBenar, $spmsqSalah, $spmsqConclusion] = geroNewSpmsqScore($apgar); ?>
        <table class="data">
            <thead><tr><th style="width:8%">No</th><th>Pertanyaan</th><th style="width:18%">Jawaban Lansia</th></tr></thead>
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
                        <td><?= geroNewSpmsq($apgar['q' . ($i + 1)] ?? '') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?= geroNewRow('Jumlah Benar', $spmsqBenar) ?>
        <?= geroNewRow('Jumlah Salah', $spmsqSalah) ?>
        <?= geroNewRow('Kesimpulan SPMSQ', $spmsqConclusion) ?>

        <h4>XIII. Skala Jatuh Pada Lansia - Ontario Modified Stratify-Sydney Scoring</h4>
        <p>Skala jatuh menilai faktor risiko berdasarkan riwayat jatuh, status mental, penglihatan, kebiasaan berkemih, transfer, dan mobilitas. Skor total 0-5 = risiko rendah, 6-16 = risiko sedang, 17-30 = risiko tinggi.</p>
        <?php [$rJatuh, $sMental, $penglihatanScore, $berkemihScore, $transferMobilitasScore, $fallTotal] = geroNewFallScore($apgar); ?>
        <table class="data">
            <thead>
                <tr>
                    <th style="width:6%">No</th>
                    <th style="width:18%">Parameter</th>
                    <th>Skrining</th>
                    <th style="width:24%">Jawaban</th>
                    <th style="width:18%">Keterangan Nilai</th>
                    <th style="width:10%">Skor</th>
                </tr>
            </thead>
            <tbody>
                <tr><td>1</td><td>Riwayat Jatuh</td><td>Apakah pasien datang ke rumah sakit karena jatuh?</td><td><?= geroNewFallAnswer('riwayat_jatuh_1', $apgar['riwayat_jatuh_1'] ?? '') ?></td><td rowspan="2">Jika salah satu jawaban Ya = 6</td><td><?= p($rJatuh) ?></td></tr>
                <tr><td></td><td></td><td>Jika tidak, apakah klien pernah jatuh dalam dua bulan terakhir?</td><td><?= geroNewFallAnswer('riwayat_jatuh_2', $apgar['riwayat_jatuh_2'] ?? '') ?></td><td></td></tr>
                <tr><td>2</td><td>Status Mental</td><td>Apakah pasien delirium (tidak dapat membuat keputusan, gangguan daya ingat)?</td><td><?= geroNewFallAnswer('status_mental_1', $apgar['status_mental_1'] ?? '') ?></td><td rowspan="3">Salah satu jawaban Ya = 14</td><td><?= p($sMental) ?></td></tr>
                <tr><td></td><td></td><td>Apakah pasien disorientasi (salah menyebutkan waktu dan tempat)?</td><td><?= geroNewFallAnswer('status_mental_2', $apgar['status_mental_2'] ?? '') ?></td><td></td></tr>
                <tr><td></td><td></td><td>Apakah pasien mengalami agitasi (ketakutan, gelisah, dan cemas)?</td><td><?= geroNewFallAnswer('status_mental_3', $apgar['status_mental_3'] ?? '') ?></td><td></td></tr>
                <tr><td>3</td><td>Penglihatan</td><td>Apakah pasien menggunakan kacamata?</td><td><?= geroNewFallAnswer('penglihatan_1', $apgar['penglihatan_1'] ?? '') ?></td><td rowspan="3">Salah satu jawaban Ya = 1</td><td><?= p($penglihatanScore) ?></td></tr>
                <tr><td></td><td></td><td>Apakah pasien mengeluh penglihatan buram?</td><td><?= geroNewFallAnswer('penglihatan_2', $apgar['penglihatan_2'] ?? '') ?></td><td></td></tr>
                <tr><td></td><td></td><td>Apakah pasien mempunyai glaukoma / katarak / degenerasi makula?</td><td><?= geroNewFallAnswer('penglihatan_3', $apgar['penglihatan_3'] ?? '') ?></td><td></td></tr>
                <tr><td>4</td><td>Kebiasaan Berkemih</td><td>Apakah terdapat perubahan perilaku berkemih (urgensi, frekuensi, inkontinensia, nokturia)?</td><td><?= geroNewFallAnswer('berkemih', $apgar['berkemih'] ?? '') ?></td><td>Ya = 2</td><td><?= p($berkemihScore) ?></td></tr>
                <tr><td>5</td><td>Transfer (Berpindah Tempat)</td><td>Mandiri (boleh memakai alat bantu) / memerlukan sedikit bantuan / bantuan yang nyata / tidak dapat duduk seimbang.</td><td><?= geroNewFallAnswer('transfer', $apgar['transfer'] ?? '') ?></td><td>Jika nilai 0-3 maka skornya 0. Jika nilai 4-6 maka skornya 7.</td><td rowspan="2"><?= p($transferMobilitasScore) ?></td></tr>
                <tr><td>6</td><td>Mobilitas</td><td>Mandiri (boleh menggunakan alat) / berjalan dengan bantuan 1 orang / menggunakan kursi roda / imobilisasi.</td><td><?= geroNewFallAnswer('mobilitas', $apgar['mobilitas'] ?? '') ?></td><td>Gabungan nilai transfer dan mobilitas</td></tr>
            </tbody>
        </table>
        <?= geroNewRow('Skor Risiko Jatuh', $fallTotal) ?>
        <?= geroNewRow('Kesimpulan Penilaian', $apgar['kesimpulan_penilaian'] ?? ($fallTotal !== '' ? ($fallTotal . ' - ' . geroNewFallCategory($fallTotal)) : '')) ?>

        <h3>9. Catatan Keperawatan</h3>
        <table class="data">
            <thead><tr><th>No</th><th>Diagnosa</th><th>Tanggal Ditemukan</th><th>Tanggal Teratasi</th></tr></thead>
            <tbody><?= geroNewTableRows($catatan['diagnosa'] ?? [], ['diagnosa', 'tgl_ditemukan', 'tgl_teratasi']) ?></tbody>
        </table>
        <table class="data">
            <thead><tr><th>No</th><th>Diagnosa</th><th>Tujuan dan Kriteria Hasil</th><th>Rencana</th></tr></thead>
            <tbody><?= geroNewTableRows($catatan['rencana'] ?? [], ['diagnosa', 'tujuan_kriteria', 'rencana']) ?></tbody>
        </table>
        <table class="data">
            <thead><tr><th>No. Dx</th><th>Hari/Tanggal</th><th>Jam</th><th>Implementasi</th></tr></thead>
            <tbody><?= geroNewTableRows($catatan['implementasi'] ?? [], ['no_dx', 'hari_tgl', 'jam', 'implementasi']) ?></tbody>
        </table>
        <table class="data">
            <thead><tr><th>No. Dx</th><th>Hari/Tanggal</th><th>Jam</th><th>S</th><th>O</th><th>A</th><th>P</th></tr></thead>
            <tbody><?= geroNewTableRows($catatan['evaluasi'] ?? [], ['no_dx', 'hari_tgl', 'jam', 'evaluasi_s', 'evaluasi_o', 'evaluasi_a', 'evaluasi_p']) ?></tbody>
        </table>
    </div>
</body>
