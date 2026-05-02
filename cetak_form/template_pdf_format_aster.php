<?php
$identitas  = $sections['identitas_riwayat'] ?? [];
$pkjUmum    = $sections['pengkajian_umum'] ?? [];
$keadaan    = $sections['keadaan_bayi'] ?? [];
$pemfis1    = $sections['pemfis_1'] ?? [];
$pemfis2    = $sections['pemfis_2'] ?? [];
$analisa    = $sections['analisa_data'] ?? [];
$lainnya    = $sections['lainnya'] ?? [];

function decodeArr($val) {
    if (is_array($val)) return $val;
    if (is_string($val)) {
        $decoded = json_decode($val, true);
        return is_array($decoded) ? $decoded : [];
    }
    return [];
}

function checkYaTidak($val, $yes = 'Ya', $no = 'Tidak') {
    $v = strtolower(trim($val ?? ''));
    $isYes = in_array($v, ['ya', 'yes', '1', 'true', 'ada', 'bersih', 'segar']);
    return ($isYes ? '☑' : '☐') . ' ' . $yes . '  ' . ($isYes ? '☐' : '☑') . ' ' . $no;
}

include 'template_pdf.php';
?>

<body>
<div>

    <h1>Format Pengkajian Bayi Baru Lahir</h1>

    <table class="header-table">
        <tr>
            <td width="30%"><strong>No. Registrasi</strong></td>
            <td width="2%">:</td>
            <td width="18%"><?= p($identitas['no_registrasi']) ?></td>
            <td width="20%"><strong>Nama Pengkaji</strong></td>
            <td width="2%">:</td>
            <td><?= p($submission['mahasiswa_name']) ?></td>
        </tr>
        <tr>
            <td><strong>Hari/Tanggal</strong></td>
            <td>:</td>
            <td><?= p($identitas['hari_tanggal']) ?></td>
            <td><strong>Waktu Pengkajian</strong></td>
            <td>:</td>
            <td><?= p($identitas['waktu_pengkajian']) ?></td>
        </tr>
        <tr>
            <td><strong>Tempat Pengkajian</strong></td>
            <td>:</td>
            <td colspan="4"><?= p($identitas['tempat_pengkajian']) ?></td>
        </tr>
    </table>

    <!-- ================================ -->
    <!-- SECTION 1: IDENTITAS            -->
    <!-- ================================ -->
    <h3>1. Identitas</h3>

    <h4>Identitas Klien</h4>
    <table class="header-table" style="border:1px solid #000;">
        <tr>
            <td width="30%"><strong>Nama</strong></td>
            <td width="2%">:</td>
            <td width="18%"><?= p($identitas['nama_klien']) ?></td>
            <td width="20%"><strong>Jenis Kelamin</strong></td>
            <td width="2%">:</td>
            <td><?= p($identitas['jk']) ?></td>
        </tr>
        <tr>
            <td><strong>Umur</strong></td>
            <td>:</td>
            <td><?= p($identitas['umur']) ?></td>
            <td><strong>Tanggal Lahir</strong></td>
            <td>:</td>
            <td><?= p($identitas['tgl_lahir']) ?></td>
        </tr>
        <tr>
            <td><strong>Apgar Score</strong></td>
            <td>:</td>
            <td><?= p($identitas['apgar']) ?></td>
            <td><strong>Usia Gestasi</strong></td>
            <td>:</td>
            <td><?= p($identitas['usia_gestasi']) ?></td>
        </tr>
        <tr>
            <td><strong>BB Lahir</strong></td>
            <td>:</td>
            <td><?= p($identitas['bb_lahir']) ?> gram</td>
            <td><strong>BB Saat Pengkajian</strong></td>
            <td>:</td>
            <td><?= p($identitas['bb_sekarang']) ?> gram</td>
        </tr>
        <tr>
            <td><strong>Alamat</strong></td>
            <td>:</td>
            <td colspan="4"><?= p($identitas['alamat']) ?></td>
        </tr>
    </table>

    <h4>Identitas Orang Tua</h4>
    <table class="header-table" style="border:1px solid #000;">
        <tr>
            <td width="10%" style="background:#d9d9d9;"><strong>Ayah</strong></td>
            <td width="20%"><strong>Nama</strong></td>
            <td width="2%">:</td>
            <td width="18%"><?= p($identitas['nama_ayah']) ?></td>
            <td width="20%"><strong>Usia</strong></td>
            <td width="2%">:</td>
            <td><?= p($identitas['usia_ayah']) ?></td>
        </tr>
        <tr>
            <td style="background:#d9d9d9;"></td>
            <td><strong>Pekerjaan</strong></td>
            <td>:</td>
            <td><?= p($identitas['pekerjaan_ayah']) ?></td>
            <td><strong>Alamat</strong></td>
            <td>:</td>
            <td><?= p($identitas['alamat_ayah']) ?></td>
        </tr>
        <tr>
            <td style="background:#d9d9d9;"><strong>Ibu</strong></td>
            <td><strong>Nama</strong></td>
            <td>:</td>
            <td><?= p($identitas['nama_ibu']) ?></td>
            <td><strong>Usia</strong></td>
            <td>:</td>
            <td><?= p($identitas['usia_ibu']) ?></td>
        </tr>
        <tr>
            <td style="background:#d9d9d9;"></td>
            <td><strong>Pekerjaan</strong></td>
            <td>:</td>
            <td><?= p($identitas['pekerjaan_ibu']) ?></td>
            <td><strong>Status Gravida</strong></td>
            <td>:</td>
            <td><?= p($identitas['status_gravida']) ?></td>
        </tr>
        <tr>
            <td style="background:#d9d9d9;"></td>
            <td><strong>Pemeriksaan Kehamilan</strong></td>
            <td>:</td>
            <td colspan="4"><?= p($identitas['pemeriksaan_kehamilan']) ?></td>
        </tr>
    </table>

    <h4>Riwayat Kehamilan</h4>
    <div class="field-row">
        <div class="field-label">Status GPA</div>
        <div class="field-sep">:</div>
        <div class="field-value"><?= p($identitas['status_gpa']) ?></div>
    </div>
    <div class="field-row">
        <div class="field-label">Obat-obatan selama Kehamilan</div>
        <div class="field-sep">:</div>
        <div class="field-value"><?= p($identitas['obat_kehamilan']) ?></div>
    </div>
    <div class="field-row">
        <div class="field-label">Imunisasi TT</div>
        <div class="field-sep">:</div>
        <div class="field-value"><?= p($identitas['imunisasi_tt']) ?></div>
    </div>
    <div class="field-row">
        <div class="field-label">Komplikasi Penyakit selama Kehamilan</div>
        <div class="field-sep">:</div>
        <div class="field-value"><?= p($identitas['komplikasi_kehamilan']) ?></div>
    </div>

    <h4>Riwayat Persalinan Sekarang</h4>
    <table class="header-table">
        <tr>
            <td width="30%"><strong>Riwayat Persalinan</strong></td>
            <td width="2%">:</td>
            <td width="18%"><?= p($identitas['riwayat_persalinan']) ?></td>
            <td width="20%"><strong>Tempat Persalinan</strong></td>
            <td width="2%">:</td>
            <td><?= p($identitas['tempat_persalinan']) ?></td>
        </tr>
        <tr>
            <td><strong>Jenis Persalinan</strong></td>
            <td>:</td>
            <td><?= p($identitas['jenis_persalinan']) ?></td>
            <td><strong>Presentasi</strong></td>
            <td>:</td>
            <td><?= p($identitas['persentasi']) ?></td>
        </tr>
        <tr>
            <td><strong>Air Ketuban</strong></td>
            <td>:</td>
            <td><?= p($identitas['air_ketuban']) ?></td>
            <td><strong>Lama Persalinan Kala II</strong></td>
            <td>:</td>
            <td><?= p($identitas['lama_persalinan']) ?></td>
        </tr>
    </table>

    <div class="subsection-title">Keadaan Tali Pusat</div>
    <table class="header-table">
        <tr>
            <td width="30%"><strong>Panjang</strong></td>
            <td width="2%">:</td>
            <td width="18%"><?= p($identitas['tali_pusat_panjang']) ?></td>
            <td width="20%"><strong>Jumlah Vena</strong></td>
            <td width="2%">:</td>
            <td><?= p($identitas['tali_pusat_vena']) ?></td>
        </tr>
        <tr>
            <td><strong>Jumlah Arteri</strong></td>
            <td>:</td>
            <td><?= p($identitas['tali_pusat_arteri']) ?></td>
            <td><strong>Warna</strong></td>
            <td>:</td>
            <td><?= p($identitas['tali_pusat_warna']) ?></td>
        </tr>
        <tr>
            <td><strong>Kelainan</strong></td>
            <td>:</td>
            <td colspan="4"><?= p($identitas['tali_pusat_kelainan']) ?></td>
        </tr>
    </table>

    <div class="page-break"></div>

    <!-- ================================ -->
    <!-- SECTION 2: PENGKAJIAN           -->
    <!-- ================================ -->
    <h3>2. Pengkajian</h3>

    <!-- APGAR SCORE -->
    <h4>Keadaan Bayi Saat Lahir – APGAR Score</h4>
    <?php
    $apgarRows = [
        ['Frekuensi Jantung', '0 = Tidak Ada', '1 = &lt;100', '2 = &gt;100', 'frekuensi_jantung_mnt1', 'frekuensi_jantung_mnt5'],
        ['Usaha Nafas',       '0 = Tidak Ada', '1 = Lambat', '2 = Menangis Kuat', 'usaha_nafas_mnt1', 'usaha_nafas_mnt5'],
        ['Tonus Otot',        '0 = Lumpuh', '1 = Ekstremitas Fleksi Sedikit', '2 = Gerakan Aktif', 'tonus_otot_mnt1', 'tonus_otot_mnt5'],
        ['Refleks',           '0 = Tidak Bereaksi', '1 = Gerakan Sedikit', '2 = Reaksi Melawan', 'refleks_apgar_mnt1', 'refleks_apgar_mnt5'],
        ['Warna Kulit',       '0 = Biru/Pucat', '1 = Tubuh Kemerahan, Tangan & Kaki Biru', '2 = Kemerahan', 'warna_kulit_mnt1', 'warna_kulit_mnt5'],
    ];
    ?>
    <table class="data">
        <thead>
            <tr>
                <th rowspan="2" width="18%">Tanda</th>
                <th colspan="3" width="52%">Score</th>
                <th colspan="2" width="30%">Jumlah</th>
            </tr>
            <tr>
                <th width="14%">0</th>
                <th width="19%">1</th>
                <th width="19%">2</th>
                <th width="15%">1 mnt</th>
                <th width="15%">5 mnt</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($apgarRows as $row): ?>
            <tr>
                <td><?= $row[0] ?></td>
                <td style="font-size:8px;"><?= $row[1] ?></td>
                <td style="font-size:8px;"><?= $row[2] ?></td>
                <td style="font-size:8px;"><?= $row[3] ?></td>
                <td style="text-align:center;"><?= p($keadaan[$row[4]]) ?></td>
                <td style="text-align:center;"><?= p($keadaan[$row[5]]) ?></td>
            </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="4"><strong>Total</strong></td>
                <td style="text-align:center;"><strong><?= p($keadaan['apgar_total_1mnt']) ?></strong></td>
                <td style="text-align:center;"><strong><?= p($keadaan['apgar_total_5mnt']) ?></strong></td>
            </tr>
        </tbody>
    </table>

    <table class="header-table">
        <tr>
            <td width="30%"><strong>Penilaian Menit ke-1</strong></td>
            <td width="2%">:</td>
            <td width="18%"><?= p($keadaan['apgar_total_1mnt']) ?></td>
            <td width="20%"><strong>Penilaian Menit ke-5</strong></td>
            <td width="2%">:</td>
            <td><?= p($keadaan['apgar_total_5mnt']) ?></td>
        </tr>
    </table>

    <!-- BALLARD SCORE -->
    <h4>Ballard Score</h4>
    <table class="header-table">
        <tr>
            <td width="30%"><strong>Nama</strong></td>
            <td width="2%">:</td>
            <td width="18%"><?= p($keadaan['ballard_name']) ?></td>
            <td width="20%"><strong>No. RS</strong></td>
            <td width="2%">:</td>
            <td><?= p($keadaan['ballard_hospital_no']) ?></td>
        </tr>
        <tr>
            <td><strong>Jenis Kelamin</strong></td>
            <td>:</td>
            <td><?= p($keadaan['ballard_sex']) ?></td>
            <td><strong>Ras</strong></td>
            <td>:</td>
            <td><?= p($keadaan['ballard_race']) ?></td>
        </tr>
        <tr>
            <td><strong>Tanggal/Jam Lahir</strong></td>
            <td>:</td>
            <td><?= p($keadaan['ballard_datetime_birth']) ?></td>
            <td><strong>Tanggal/Jam Periksa</strong></td>
            <td>:</td>
            <td><?= p($keadaan['ballard_datetime_exam']) ?></td>
        </tr>
        <tr>
            <td><strong>BB Lahir</strong></td>
            <td>:</td>
            <td><?= p($keadaan['ballard_birth_weight']) ?></td>
            <td><strong>Usia saat Diperiksa</strong></td>
            <td>:</td>
            <td><?= p($keadaan['ballard_age_exam']) ?></td>
        </tr>
        <tr>
            <td><strong>Panjang Badan</strong></td>
            <td>:</td>
            <td><?= p($keadaan['ballard_length']) ?></td>
            <td><strong>Lingkar Kepala</strong></td>
            <td>:</td>
            <td><?= p($keadaan['ballard_head_circ']) ?></td>
        </tr>
        <tr>
            <td><strong>Pemeriksa</strong></td>
            <td>:</td>
            <td colspan="4"><?= p($keadaan['ballard_examiner']) ?></td>
        </tr>
    </table>

    <div class="subsection-title">Neuromuscular Maturity</div>
    <?php
    $neuromuscular = [
        ['Posture',         'posture',      ['-1','0','1','2','3','4']],
        ['Square Window',   'square_window',['-1','0','1','2','3','4']],
        ['Arm Recoil',      'arm_recoil',   ['-1','0','1','2','3','4']],
        ['Popliteal Angle', 'popliteal_angle',['-1','0','1','2','3','4']],
        ['Scarf Sign',      'scarf_sign',   ['-1','0','1','2','3','4']],
        ['Heel to Ear',     'heel_to_ear',  ['-1','0','1','2','3','4']],
    ];
    $scoreFields = [
        'posture' => 'score_posture',
        'square_window' => 'score_square_window',
        'arm_recoil' => 'score_arm_recoil',
        'popliteal_angle' => 'score_popliteal_angle',
        'scarf_sign' => 'score_scarf_sign',
        'heel_to_ear' => 'score_heel_to_ear',
    ];
    ?>
    <table class="data">
        <thead>
            <tr>
                <th width="25%">Tanda</th>
                <th width="10%">-1</th>
                <th width="10%">0</th>
                <th width="10%">1</th>
                <th width="10%">2</th>
                <th width="10%">3</th>
                <th width="10%">4</th>
                <th width="15%">Score</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($neuromuscular as $nm):
                $scoreKey = $scoreFields[$nm[1]];
                $scoreVal = (string)($keadaan[$scoreKey] ?? '');
            ?>
            <tr>
                <td><?= $nm[0] ?></td>
                <?php foreach ($nm[2] as $opt): ?>
                    <td style="text-align:center;"><?= ($scoreVal === $opt) ? '✓' : '' ?></td>
                <?php endforeach; ?>
                <td style="text-align:center;"><strong><?= p($keadaan[$scoreKey]) ?></strong></td>
            </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="7"><strong>Total Neuromuscular Score</strong></td>
                <td style="text-align:center;"><strong><?= p($keadaan['score_neuromuscular']) ?></strong></td>
            </tr>
        </tbody>
    </table>

    <div class="subsection-title">Physical Maturity</div>
    <?php
    $physical = [
        ['Skin',       'skin',      ['-1','0','1','2','3','4']],
        ['Lanugo',     'lanugo',    ['-1','0','1','2','3','4']],
        ['Plantar',    'plantar',   ['-1','0','1','2','3','4']],
        ['Breast',     'breast',    ['-1','0','1','2','3','4']],
        ['Eye/Ear',    'eye_ear',   ['-1','0','1','2','3','4']],
        ['Gen. Male',  'gen_male',  ['-1','0','1','2','3','4']],
        ['Gen. Female','gen_female',['-1','0','1','2','3','4']],
    ];
    $physScoreFields = [
        'skin'       => 'score_skin',
        'lanugo'     => 'score_lanugo',
        'plantar'    => 'score_plantar',
        'breast'     => 'score_breast',
        'eye_ear'    => 'score_eye_ear',
        'gen_male'   => 'score_gen_male',
        'gen_female' => 'score_gen_female',
    ];
    ?>
    <table class="data">
        <thead>
            <tr>
                <th width="25%">Tanda</th>
                <th width="10%">-1</th>
                <th width="10%">0</th>
                <th width="10%">1</th>
                <th width="10%">2</th>
                <th width="10%">3</th>
                <th width="10%">4</th>
                <th width="15%">Score</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($physical as $ph):
                $scoreKey = $physScoreFields[$ph[1]];
                $scoreVal = (string)($keadaan[$scoreKey] ?? '');
            ?>
            <tr>
                <td><?= $ph[0] ?></td>
                <?php foreach ($ph[2] as $opt): ?>
                    <td style="text-align:center;"><?= ($scoreVal === $opt) ? '✓' : '' ?></td>
                <?php endforeach; ?>
                <td style="text-align:center;"><strong><?= p($keadaan[$scoreKey]) ?></strong></td>
            </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="7"><strong>Total Physical Score</strong></td>
                <td style="text-align:center;"><strong><?= p($keadaan['score_physical']) ?></strong></td>
            </tr>
        </tbody>
    </table>

    <table class="header-table">
        <tr>
            <td width="30%"><strong>Total Score</strong></td>
            <td width="2%">:</td>
            <td width="18%"><?= p($keadaan['score_total']) ?></td>
            <td width="20%"><strong>Gest. By Exam</strong></td>
            <td width="2%">:</td>
            <td><?= p($keadaan['gest_by_exam']) ?></td>
        </tr>
        <tr>
            <td><strong>Gest. By Dates</strong></td>
            <td>:</td>
            <td><?= p($keadaan['gest_by_dates']) ?></td>
            <td><strong>Gest. By Ultrasound</strong></td>
            <td>:</td>
            <td><?= p($keadaan['gest_by_ultrasound']) ?></td>
        </tr>
    </table>

    <div class="page-break"></div>

    <!-- PENGKAJIAN UMUM -->
    <h4>Resusitasi &amp; Intervensi Awal</h4>
    <table class="header-table">
        <tr>
            <td width="30%"><strong>Resusitasi</strong></td>
            <td width="2%">:</td>
            <td width="18%"><?= p($pkjUmum['resusitasi']) ?></td>
            <td width="20%"><strong>Vitamin K</strong></td>
            <td width="2%">:</td>
            <td><?= p($pkjUmum['vitamin_k']) ?></td>
        </tr>
        <tr>
            <td><strong>Salep Mata</strong></td>
            <td>:</td>
            <td><?= p($pkjUmum['salep_mata']) ?></td>
            <td><strong>Pemberian O2</strong></td>
            <td>:</td>
            <td><?= p($pkjUmum['o2']) ?></td>
        </tr>
    </table>

    <h4>Pernapasan</h4>
    <table class="header-table">
        <tr>
            <td width="30%"><strong>Nafas Spontan</strong></td>
            <td width="2%">:</td>
            <td width="18%"><?= p($pkjUmum['nafas_spontan']) ?></td>
            <td width="20%"><strong>Frekuensi Nafas</strong></td>
            <td width="2%">:</td>
            <td><?= p($pkjUmum['frekuensi_nafas']) ?> x/mnt</td>
        </tr>
        <tr>
            <td><strong>Nafas Teratur</strong></td>
            <td>:</td>
            <td><?= p($pkjUmum['nafas_teratur']) ?></td>
            <td><strong>Suara Nafas</strong></td>
            <td>:</td>
            <td><?= p($pkjUmum['suara_nafas']) ?></td>
        </tr>
    </table>

    <h4>Asupan Cairan</h4>
    <table class="header-table">
        <tr>
            <td width="30%"><strong>ASI – Frekuensi</strong></td>
            <td width="2%">:</td>
            <td width="18%"><?= p($pkjUmum['asi_frekuensi']) ?></td>
            <td width="20%"><strong>ASI – Jumlah</strong></td>
            <td width="2%">:</td>
            <td><?= p($pkjUmum['asi_jumlah']) ?></td>
        </tr>
        <tr>
            <td><strong>Formula – Frekuensi</strong></td>
            <td>:</td>
            <td><?= p($pkjUmum['formula_frekuensi']) ?></td>
            <td><strong>Formula – Jumlah</strong></td>
            <td>:</td>
            <td><?= p($pkjUmum['formula_jumlah']) ?></td>
        </tr>
        <tr>
            <td><strong>Infus – Jenis</strong></td>
            <td>:</td>
            <td><?= p($pkjUmum['infus_jenis']) ?></td>
            <td><strong>Infus – Jumlah</strong></td>
            <td>:</td>
            <td><?= p($pkjUmum['infus_jumlah']) ?></td>
        </tr>
    </table>

    <h4>Eliminasi</h4>
    <table class="header-table">
        <tr>
            <td width="30%"><strong>BAB – Mekonium</strong></td>
            <td width="2%">:</td>
            <td width="18%"><?= p($pkjUmum['bab_mekonium']) ?></td>
            <td width="20%"><strong>BAB – Frekuensi</strong></td>
            <td width="2%">:</td>
            <td><?= p($pkjUmum['bab_frekuensi']) ?></td>
        </tr>
        <tr>
            <td><strong>BAB – Warna</strong></td>
            <td>:</td>
            <td><?= p($pkjUmum['bab_warna']) ?></td>
            <td><strong>BAK – Frekuensi</strong></td>
            <td>:</td>
            <td><?= p($pkjUmum['bak_frekuensi']) ?></td>
        </tr>
        <tr>
            <td><strong>BAK – Warna</strong></td>
            <td>:</td>
            <td colspan="4"><?= p($pkjUmum['bak_warna']) ?></td>
        </tr>
    </table>

    <h4>Istirahat dan Tidur</h4>
    <table class="header-table">
        <tr>
            <td width="30%"><strong>Lama Tidur</strong></td>
            <td width="2%">:</td>
            <td width="18%"><?= p($pkjUmum['lama_tidur']) ?></td>
            <td width="20%"><strong>Keadaan Waktu Tidur</strong></td>
            <td width="2%">:</td>
            <td><?= p($pkjUmum['keadaan_tidur']) ?></td>
        </tr>
    </table>

    <h4>Pengukuran Antropometri</h4>
    <table class="header-table">
        <tr>
            <td width="30%"><strong>Berat Badan</strong></td>
            <td width="2%">:</td>
            <td width="18%"><?= p($pkjUmum['bb']) ?> gram</td>
            <td width="20%"><strong>Panjang Badan</strong></td>
            <td width="2%">:</td>
            <td><?= p($pkjUmum['pb']) ?> cm</td>
        </tr>
        <tr>
            <td><strong>Lingkar Kepala</strong></td>
            <td>:</td>
            <td><?= p($pkjUmum['lk']) ?> cm</td>
            <td><strong>Lingkar Dada</strong></td>
            <td>:</td>
            <td><?= p($pkjUmum['ld']) ?> cm</td>
        </tr>
        <tr>
            <td><strong>Lingkar Perut</strong></td>
            <td>:</td>
            <td><?= p($pkjUmum['lp']) ?> cm</td>
            <td><strong>Lingkar Lengan Atas</strong></td>
            <td>:</td>
            <td><?= p($pkjUmum['lila']) ?> cm</td>
        </tr>
    </table>

    <h4>Pemeriksaan Fisik Umum</h4>
    <table class="header-table">
        <tr>
            <td width="30%"><strong>Keadaan Umum</strong></td>
            <td width="2%">:</td>
            <td width="18%"><?= p($pkjUmum['keadaan_umum']) ?></td>
            <td width="20%"><strong>Tekanan Darah</strong></td>
            <td width="2%">:</td>
            <td><?= p($pkjUmum['tekanan_darah']) ?> mmHg</td>
        </tr>
        <tr>
            <td><strong>Nadi</strong></td>
            <td>:</td>
            <td><?= p($pkjUmum['nadi']) ?> x/mnt</td>
            <td><strong>Suhu</strong></td>
            <td>:</td>
            <td><?= p($pkjUmum['suhu']) ?> °C</td>
        </tr>
        <tr>
            <td><strong>Pernapasan</strong></td>
            <td>:</td>
            <td colspan="4"><?= p($pkjUmum['pernapasan_ttv']) ?> x/mnt</td>
        </tr>
    </table>

    <div class="page-break"></div>

    <!-- ================================ -->
    <!-- SECTION 3: PEMERIKSAAN FISIK    -->
    <!-- ================================ -->
    <h3>3. Pemeriksaan Fisik</h3>

    <!-- Kepala -->
    <div class="subsection-title">Kepala</div>
    <div class="field-row">
        <div class="field-label">Keadaan Kepala</div>
        <div class="field-sep">:</div>
        <div class="field-value"><?= checkYaTidak($pemfis1['keadaan_kepala']) ?></div>
    </div>
    <div class="field-row">
        <div class="field-label">Keadaan Fontenel</div>
        <div class="field-sep">:</div>
        <div class="field-value"><?= p($pemfis1['fontenel']) ?></div>
    </div>
    <div class="field-row">
        <div class="field-label">Trauma Kepala</div>
        <div class="field-sep">:</div>
        <div class="field-value"><?= checkYaTidak($pemfis1['trauma_kepala']) ?></div>
    </div>

    <!-- Wajah -->
    <div class="subsection-title">Wajah</div>
    <div class="field-row">
        <div class="field-label">Wajah Simetris</div>
        <div class="field-sep">:</div>
        <div class="field-value"><?= checkYaTidak($pemfis1['wajah_simetris']) ?></div>
    </div>
    <div class="field-row">
        <div class="field-label">Laserasi</div>
        <div class="field-sep">:</div>
        <div class="field-value"><?= p($pemfis1['laserasi']) ?></div>
    </div>
    <div class="field-row">
        <div class="field-label">Paresis N. Fasialis</div>
        <div class="field-sep">:</div>
        <div class="field-value"><?= p($pemfis1['paresis']) ?></div>
    </div>

    <!-- Mata -->
    <div class="subsection-title">Mata</div>
    <?php
    $mataPemfis = [
        ['Mata Terbuka saat Kepala Digoyang', 'mata_terbuka', true],
        ['Jumlah Mata', 'mata_jumlah', false],
        ['Posisi/Letak Mata', 'mata_posisi', false],
        ['Strabismus', 'strabismus', true],
        ['Katarak Kongenital', 'katarak', true],
        ['Trauma pada Palpebral', 'trauma_palpebral', true],
        ['Palpebra', 'palpebra', false],
        ['Sclera', 'sclera', false],
        ['Anemis/Konjungtiva', 'anemis', false],
        ['Pupil – Bentuk', 'pupil_bentuk', false],
        ['Pupil – Ukuran', 'pupil_ukuran', false],
        ['Refleks Pupil', 'refleks_pupil', false],
        ['Keterangan Refleks Pupil', 'refleks_pupil_ket', false],
        ['Gerakan Bola Mata', 'gerakan_mata', false],
        ['Penutupan Kelopak Mata', 'kelopak_mata', false],
        ['Keadaan Bulu Mata', 'bulu_mata', false],
        ['Data Lain', 'mata_lain', false],
    ];
    foreach ($mataPemfis as $m): ?>
        <div class="field-row">
            <div class="field-label"><?= $m[0] ?></div>
            <div class="field-sep">:</div>
            <div class="field-value">
                <?= $m[2] ? checkYaTidak($pemfis1[$m[1]]) : p($pemfis1[$m[1]]) ?>
            </div>
        </div>
    <?php endforeach; ?>

    <!-- Hidung -->
    <div class="subsection-title">Hidung &amp; Sinus</div>
    <?php
    $hidungPemfis = [
        ['Bentuk Hidung', 'hidung_bentuk'],
        ['Pernafasan Cuping Hidung', 'cuping'],
        ['Keadaan Septum', 'septum'],
        ['Sekret/Cairan', 'secret_hidung'],
        ['Data Lain', 'hidung_lain'],
    ];
    foreach ($hidungPemfis as $h): ?>
        <div class="field-row">
            <div class="field-label"><?= $h[0] ?></div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($pemfis1[$h[1]]) ?></div>
        </div>
    <?php endforeach; ?>

    <!-- Telinga -->
    <div class="subsection-title">Telinga</div>
    <div class="field-row">
        <div class="field-label">Bentuk Telinga</div>
        <div class="field-sep">:</div>
        <div class="field-value"><?= p($pemfis1['telinga_bentuk']) ?></div>
    </div>
    <div class="field-row">
        <div class="field-label">Letak Telinga terhadap Mata</div>
        <div class="field-sep">:</div>
        <div class="field-value"><?= p($pemfis1['letak_telinga']) ?></div>
    </div>
    <div class="field-row">
        <div class="field-label">Lubang Telinga</div>
        <div class="field-sep">:</div>
        <div class="field-value"><?= p($pemfis1['lubang_telinga']) ?></div>
    </div>
    <div class="field-row">
        <div class="field-label">Nyeri Tekan</div>
        <div class="field-sep">:</div>
        <div class="field-value"><?= checkYaTidak($pemfis1['nyeri_telinga']) ?></div>
    </div>

    <!-- Mulut -->
    <div class="subsection-title">Mulut</div>
    <?php
    $gusiArr    = decodeArr($pemfis1['gusi'] ?? []);
    $bibirWarna = decodeArr($pemfis1['bibir_warna'] ?? []);
    $bibirKond  = decodeArr($pemfis1['bibir_kondisi'] ?? []);
    ?>
    <div class="field-row">
        <div class="field-label">Gusi</div>
        <div class="field-sep">:</div>
        <div class="field-value"><?= !empty($gusiArr) ? implode(', ', $gusiArr) : '-' ?> — <?= p($pemfis1['gusi_ket']) ?></div>
    </div>
    <div class="field-row">
        <div class="field-label">Lidah</div>
        <div class="field-sep">:</div>
        <div class="field-value"><?= p($pemfis1['lidah']) ?></div>
    </div>
    <div class="field-row">
        <div class="field-label">Bibir – Warna</div>
        <div class="field-sep">:</div>
        <div class="field-value"><?= !empty($bibirWarna) ? implode(', ', $bibirWarna) : '-' ?> — <?= p($pemfis1['bibir_warna_ket']) ?></div>
    </div>
    <div class="field-row">
        <div class="field-label">Bibir – Kondisi</div>
        <div class="field-sep">:</div>
        <div class="field-value"><?= !empty($bibirKond) ? implode(', ', $bibirKond) : '-' ?> — <?= p($pemfis1['bibir_kondisi_ket']) ?></div>
    </div>
    <div class="field-row">
        <div class="field-label">Bau Mulut</div>
        <div class="field-sep">:</div>
        <div class="field-value"><?= p($pemfis1['bau_mulut']) ?> — <?= p($pemfis1['bau_mulut_ket']) ?></div>
    </div>
    <div class="field-row">
        <div class="field-label">Bibir Simetris</div>
        <div class="field-sep">:</div>
        <div class="field-value"><?= checkYaTidak($pemfis1['bibir_simetris']) ?></div>
    </div>
    <div class="field-row">
        <div class="field-label">Labio Skizis</div>
        <div class="field-sep">:</div>
        <div class="field-value"><?= checkYaTidak($pemfis1['labio_skizis']) ?></div>
    </div>
    <div class="field-row">
        <div class="field-label">Palato Skizis</div>
        <div class="field-sep">:</div>
        <div class="field-value"><?= checkYaTidak($pemfis1['palato_skizis']) ?></div>
    </div>
    <div class="field-row">
        <div class="field-label">Bercak Putih pada Lidah dan Palatum</div>
        <div class="field-sep">:</div>
        <div class="field-value"><?= checkYaTidak($pemfis1['bercak_putih']) ?></div>
    </div>

    <!-- Tenggorokan -->
    <div class="subsection-title">Tenggorokan</div>
    <div class="field-row">
        <div class="field-label">Warna Mukosa</div>
        <div class="field-sep">:</div>
        <div class="field-value"><?= p($pemfis1['warna_mukosa']) ?></div>
    </div>
    <div class="field-row">
        <div class="field-label">Ada Sumbatan</div>
        <div class="field-sep">:</div>
        <div class="field-value"><?= p($pemfis1['sumbatan']) ?></div>
    </div>

    <!-- Leher -->
    <div class="subsection-title">Leher</div>
    <div class="field-row">
        <div class="field-label">Kelenjar Limfe</div>
        <div class="field-sep">:</div>
        <div class="field-value"><?= p($pemfis1['limfe']) ?></div>
    </div>
    <div class="field-row">
        <div class="field-label">Simetris</div>
        <div class="field-sep">:</div>
        <div class="field-value"><?= checkYaTidak($pemfis1['leher_simetris']) ?></div>
    </div>
    <div class="field-row">
        <div class="field-label">Pembengkakan</div>
        <div class="field-sep">:</div>
        <div class="field-value"><?= checkYaTidak($pemfis1['pembengkakan_leher']) ?></div>
    </div>
    <div class="field-row">
        <div class="field-label">Lipatan Kulit Berlebihan di Belakang Leher</div>
        <div class="field-sep">:</div>
        <div class="field-value"><?= checkYaTidak($pemfis1['lipatan_leher']) ?></div>
    </div>
    <div class="field-row">
        <div class="field-label">Data Lain</div>
        <div class="field-sep">:</div>
        <div class="field-value"><?= p($pemfis1['leher_lain']) ?></div>
    </div>

    <div class="page-break"></div>

    <!-- Thorax -->
    <div class="subsection-title">Thorax dan Pernapasan</div>
    <?php
    $suaraNafasArr  = decodeArr($pemfis2['suara_nafas'] ?? []);
    $suaraTambArr   = decodeArr($pemfis2['suara_tambahan'] ?? []);
    $perkusiParuArr = decodeArr($pemfis2['perkusi_paru'] ?? []);
    ?>
    <div class="field-row">
        <div class="field-label">Kesimetrisan Gerakan Dada</div>
        <div class="field-sep">:</div>
        <div class="field-value"><?= checkYaTidak($pemfis2['thorax_simetris']) ?></div>
    </div>
    <div class="field-row">
        <div class="field-label">Puting Susu Membesar</div>
        <div class="field-sep">:</div>
        <div class="field-value"><?= checkYaTidak($pemfis2['puting']) ?></div>
    </div>
    <div class="field-row">
        <div class="field-label">Fraktur Klavikula</div>
        <div class="field-sep">:</div>
        <div class="field-value"><?= checkYaTidak($pemfis2['fraktur']) ?></div>
    </div>
    <div class="field-row">
        <div class="field-label">Bentuk Dada</div>
        <div class="field-sep">:</div>
        <div class="field-value"><?= p($pemfis2['bentuk_dada']) ?></div>
    </div>
    <div class="field-row">
        <div class="field-label">Irama Pernafasan</div>
        <div class="field-sep">:</div>
        <div class="field-value"><?= p($pemfis2['irama']) ?></div>
    </div>
    <div class="field-row">
        <div class="field-label">Pengembangan saat Bernafas</div>
        <div class="field-sep">:</div>
        <div class="field-value"><?= p($pemfis2['pengembangan']) ?></div>
    </div>
    <div class="field-row">
        <div class="field-label">Tipe Pernafasan</div>
        <div class="field-sep">:</div>
        <div class="field-value"><?= p($pemfis2['tipe']) ?></div>
    </div>
    <div class="field-row">
        <div class="field-label">Taktil Fremitus</div>
        <div class="field-sep">:</div>
        <div class="field-value"><?= p($pemfis2['taktil_fremitus']) ?></div>
    </div>
    <div class="field-row">
        <div class="field-label">Suara Nafas</div>
        <div class="field-sep">:</div>
        <div class="field-value"><?= !empty($suaraNafasArr) ? implode(', ', $suaraNafasArr) : '-' ?></div>
    </div>
    <div class="field-row">
        <div class="field-label">Suara Tambahan</div>
        <div class="field-sep">:</div>
        <div class="field-value"><?= !empty($suaraTambArr) ? implode(', ', $suaraTambArr) : '-' ?></div>
    </div>
    <div class="field-row">
        <div class="field-label">Perkusi Paru</div>
        <div class="field-sep">:</div>
        <div class="field-value"><?= !empty($perkusiParuArr) ? implode(', ', $perkusiParuArr) : '-' ?></div>
    </div>

    <!-- Jantung -->
    <div class="subsection-title">Jantung</div>
    <?php
    $jantungPemfis = [
        ['Ictus Cordis', 'ictus_cordis'],
        ['Pembesaran Jantung', 'pembesaran_jantung'],
        ['BJ I', 'bj1'],
        ['BJ II', 'bj2'],
        ['BJ III', 'bj3'],
        ['Bunyi Tambahan', 'bunyi_tambahan'],
        ['Data Lain', 'jantung_lain'],
    ];
    foreach ($jantungPemfis as $j): ?>
        <div class="field-row">
            <div class="field-label"><?= $j[0] ?></div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($pemfis2[$j[1]]) ?></div>
        </div>
    <?php endforeach; ?>

    <!-- Abdomen -->
    <div class="subsection-title">Abdomen</div>
    <div class="field-row">
        <div class="field-label">Tali Pusat Bersih/Terawat</div>
        <div class="field-sep">:</div>
        <div class="field-value"><?= checkYaTidak($pemfis2['tali_pusat_bersih']) ?></div>
    </div>
    <div class="field-row">
        <div class="field-label">Kondisi Tali Pusat</div>
        <div class="field-sep">:</div>
        <div class="field-value"><?= p($pemfis2['tali_pusat_kondisi']) ?></div>
    </div>
    <div class="field-row">
        <div class="field-label">Tali Pusat Tidak Berbau</div>
        <div class="field-sep">:</div>
        <div class="field-value"><?= checkYaTidak($pemfis2['tali_tidak_berbau']) ?></div>
    </div>
    <div class="field-row">
        <div class="field-label">Perdarahan Tali Pusat</div>
        <div class="field-sep">:</div>
        <div class="field-value"><?= p($pemfis2['pendarahan_tp']) ?></div>
    </div>
    <div class="field-row">
        <div class="field-label">Tanda Infeksi Tali Pusat</div>
        <div class="field-sep">:</div>
        <div class="field-value"><?= p($pemfis2['infeksi_tp']) ?></div>
    </div>
    <div class="field-row">
        <div class="field-label">Abdomen Tampak Bulat</div>
        <div class="field-sep">:</div>
        <div class="field-value"><?= checkYaTidak($pemfis2['abdomen_bentuk']) ?></div>
    </div>
    <div class="field-row">
        <div class="field-label">Abdomen Cekung</div>
        <div class="field-sep">:</div>
        <div class="field-value"><?= checkYaTidak($pemfis2['abdomen_cekung']) ?></div>
    </div>
    <div class="field-row">
        <div class="field-label">Abdomen Bergerak dengan Gerakan Dada</div>
        <div class="field-sep">:</div>
        <div class="field-value"><?= checkYaTidak($pemfis2['abdomen_gerak']) ?></div>
    </div>
    <div class="field-row">
        <div class="field-label">Pembengkakan</div>
        <div class="field-sep">:</div>
        <div class="field-value"><?= checkYaTidak($pemfis2['pembengkakan_abd']) ?></div>
    </div>
    <div class="field-row">
        <div class="field-label">Warna &amp; Keadaan Kulit Abdomen</div>
        <div class="field-sep">:</div>
        <div class="field-value"><?= p($pemfis2['kulit_abdomen']) ?></div>
    </div>
    <div class="field-row">
        <div class="field-label">Umbilikus</div>
        <div class="field-sep">:</div>
        <div class="field-value"><?= p($pemfis2['umbilicus']) ?></div>
    </div>
    <div class="field-row">
        <div class="field-label">Auskultasi Peristaltik</div>
        <div class="field-sep">:</div>
        <div class="field-value"><?= p($pemfis2['peristaltik']) ?></div>
    </div>
    <div class="field-row">
        <div class="field-label">Perkusi Tympani</div>
        <div class="field-sep">:</div>
        <div class="field-value"><?= p($pemfis2['tympani']) ?></div>
    </div>
    <div class="field-row">
        <div class="field-label">Nyeri</div>
        <div class="field-sep">:</div>
        <div class="field-value"><?= p($pemfis2['nyeri_abd']) ?></div>
    </div>
    <div class="field-row">
        <div class="field-label">Hati</div>
        <div class="field-sep">:</div>
        <div class="field-value"><?= p($pemfis2['hati']) ?></div>
    </div>
    <div class="field-row">
        <div class="field-label">Ginjal</div>
        <div class="field-sep">:</div>
        <div class="field-value"><?= p($pemfis2['ginjal']) ?></div>
    </div>
    <div class="field-row">
        <div class="field-label">Kolon Sigmoid</div>
        <div class="field-sep">:</div>
        <div class="field-value"><?= p($pemfis2['kolon']) ?></div>
    </div>
    <div class="field-row">
        <div class="field-label">Data Lain</div>
        <div class="field-sep">:</div>
        <div class="field-value"><?= p($pemfis2['abd_lain']) ?></div>
    </div>

    <!-- Genetalia -->
    <div class="subsection-title">Genetalia</div>
    <div class="subsection-title" style="font-size:9px;">Anak Laki-laki</div>
    <div class="field-row">
        <div class="field-label">Fistula Urinari</div>
        <div class="field-sep">:</div>
        <div class="field-value"><?= p($pemfis2['fistula_pria']) ?></div>
    </div>
    <div class="field-row">
        <div class="field-label">Lubang Uretra</div>
        <div class="field-sep">:</div>
        <div class="field-value"><?= p($pemfis2['uretra']) ?></div>
    </div>
    <div class="field-row">
        <div class="field-label">Skrotum</div>
        <div class="field-sep">:</div>
        <div class="field-value"><?= p($pemfis2['skrotum']) ?></div>
    </div>
    <div class="field-row">
        <div class="field-label">Genitalia Ganda</div>
        <div class="field-sep">:</div>
        <div class="field-value"><?= p($pemfis2['genital_ganda']) ?></div>
    </div>
    <div class="field-row">
        <div class="field-label">Data Lain</div>
        <div class="field-sep">:</div>
        <div class="field-value"><?= p($pemfis2['gen_pria_lain']) ?></div>
    </div>
    <div class="subsection-title" style="font-size:9px;">Anak Perempuan</div>
    <div class="field-row">
        <div class="field-label">Labia dan Klitoris</div>
        <div class="field-sep">:</div>
        <div class="field-value"><?= p($pemfis2['labia']) ?></div>
    </div>
    <div class="field-row">
        <div class="field-label">Fistula Urigenital</div>
        <div class="field-sep">:</div>
        <div class="field-value"><?= p($pemfis2['fistula_wanita']) ?></div>
    </div>
    <div class="field-row">
        <div class="field-label">Data Lain</div>
        <div class="field-sep">:</div>
        <div class="field-value"><?= p($pemfis2['gen_wanita_lain']) ?></div>
    </div>

    <!-- Anus -->
    <div class="subsection-title">Anus</div>
    <div class="field-row">
        <div class="field-label">Lubang Anal Paten</div>
        <div class="field-sep">:</div>
        <div class="field-value"><?= p($pemfis2['lubang_anal']) ?></div>
    </div>
    <div class="field-row">
        <div class="field-label">Lintasan Mekonium dalam 36 Jam</div>
        <div class="field-sep">:</div>
        <div class="field-value"><?= checkYaTidak($pemfis2['mekonium_36jam']) ?></div>
    </div>

    <!-- Ekstremitas Atas -->
    <div class="subsection-title">Ekstremitas Atas</div>
    <?php
    $ekstrAtas = [
        ['Pergerakan Kanan/Kiri', 'gerak_atas'],
        ['Pergerakan Abnormal', 'gerak_abnormal_atas'],
        ['Kekuatan Otot Kanan/Kiri', 'kekuatan_atas'],
        ['Koordinasi Gerak', 'koordinasi_atas'],
        ['Jumlah Jari', 'jari_atas'],
        ['Telapak Tangan dapat Terbuka', 'telapak_atas'],
        ['Nyeri', 'nyeri_atas'],
        ['Rangsang Suhu', 'suhu_atas'],
        ['Rasa Raba', 'raba_atas'],
    ];
    foreach ($ekstrAtas as $e): ?>
        <div class="field-row">
            <div class="field-label"><?= $e[0] ?></div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($pemfis2[$e[1]]) ?></div>
        </div>
    <?php endforeach; ?>
    <div class="field-row">
        <div class="field-label">Polidaktili/Sindaktili</div>
        <div class="field-sep">:</div>
        <div class="field-value"><?= checkYaTidak($pemfis2['polidaktili_atas']) ?></div>
    </div>

    <!-- Ekstremitas Bawah -->
    <div class="subsection-title">Ekstremitas Bawah</div>
    <?php
    $ekstrBawah = [
        ['Pergerakan Kanan/Kiri', 'gerak_bawah'],
        ['Kekuatan Kanan/Kiri', 'kekuatan_bawah'],
        ['Tonus Otot Kanan/Kiri', 'tonus_bawah'],
        ['Jumlah Jari', 'jari_bawah'],
        ['Nyeri', 'nyeri_bawah'],
        ['Rangsang Suhu', 'suhu_bawah'],
        ['Rasa Raba', 'raba_bawah'],
    ];
    foreach ($ekstrBawah as $e): ?>
        <div class="field-row">
            <div class="field-label"><?= $e[0] ?></div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($pemfis2[$e[1]]) ?></div>
        </div>
    <?php endforeach; ?>
    <div class="field-row">
        <div class="field-label">Polidaktili/Sindaktili</div>
        <div class="field-sep">:</div>
        <div class="field-value"><?= checkYaTidak($pemfis2['polidaktili_bawah']) ?></div>
    </div>

    <!-- Integumen -->
    <div class="subsection-title">Integumen</div>
    <?php
    $integ = [
        ['Turgor Kulit', 'turgor'],
        ['Finger Print di Dahi', 'finger_print'],
        ['Adanya Lesi', 'lesi'],
        ['Kebersihan Kulit', 'kebersihan'],
        ['Kelembaban Kulit', 'kelembaban_kulit'],
        ['Warna Kulit', 'warna_kulit_integ'],
    ];
    foreach ($integ as $ig): ?>
        <div class="field-row">
            <div class="field-label"><?= $ig[0] ?></div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($pemfis2[$ig[1]]) ?></div>
        </div>
    <?php endforeach; ?>

    <!-- Refleks Primitif -->
    <div class="subsection-title">Pengkajian Refleks Primitif</div>
    <?php
    $refleks = [
        ['Refleks Iddol',       'refleks_iddol'],
        ['Refleks Startel',     'refleks_startel'],
        ['Refleks Sucking',     'refleks_sucking'],
        ['Refleks Rooting',     'refleks_rooting'],
        ['Refleks Gawn',        'refleks_gawn'],
        ['Refleks Grabella',    'refleks_grabella'],
        ['Refleks Ekruction',   'refleks_ekruction'],
        ['Refleks Moro',        'refleks_moro'],
        ['Refleks Grasping',    'refleks_grasping'],
    ];
    foreach ($refleks as $r): ?>
        <div class="field-row">
            <div class="field-label"><?= $r[0] ?></div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($pemfis2[$r[1]]) ?></div>
        </div>
    <?php endforeach; ?>

    <!-- Tes Diagnostik -->
    <div class="subsection-title">Tes Diagnostik</div>
    <div class="field-row">
        <div class="field-label">Laboratorium</div>
        <div class="field-sep">:</div>
        <div class="field-value"><?= p($pemfis2['laboratorium']) ?></div>
    </div>
    <div class="field-row">
        <div class="field-label">Pemeriksaan Penunjang</div>
        <div class="field-sep">:</div>
        <div class="field-value"><?= p($pemfis2['pemeriksaan_penunjang']) ?></div>
    </div>
    <?php if (!empty($pemfis2['lampiran_lab'])): ?>
        <div style="text-align:center; margin: 6px 0;">
            <img src="<?= cetakGambar(p($pemfis2['lampiran_lab'])) ?>"
                 style="height:250px;" />
        </div>
    <?php endif; ?>
    <div class="field-row">
        <div class="field-label">Terapi Saat Ini</div>
        <div class="field-sep">:</div>
        <div class="field-value"><?= p($pemfis2['terapi']) ?></div>
    </div>

    <div class="page-break"></div>

    <!-- ================================ -->
    <!-- SECTION 4: ANALISA DATA         -->
    <!-- ================================ -->
    <h3>Klasifikasi Data</h3>
    <table class="data">
        <thead>
            <tr>
                <th width="50%">Data Subjektif (DS)</th>
                <th width="50%">Data Objektif (DO)</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $klasifikasi = decodeArr($analisa['klasifikasi'] ?? []);
            if (!empty($klasifikasi)):
                foreach ($klasifikasi as $kl): ?>
                    <tr>
                        <td><?= p($kl['ds']) ?></td>
                        <td><?= p($kl['do']) ?></td>
                    </tr>
                <?php endforeach;
            else: ?>
                <tr><td colspan="2" style="text-align:center">-</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <h3>Analisa Data</h3>
    <table class="data">
        <thead>
            <tr>
                <th width="5%">No.</th>
                <th width="35%">Data</th>
                <th width="30%">Etiologi</th>
                <th width="30%">Masalah</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $analisaData = decodeArr($analisa['analisa'] ?? []);
            if (!empty($analisaData)):
                foreach ($analisaData as $i => $an): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><?= p($an['data']) ?> — <?= p($an['dsdo']) ?></td>
                        <td><?= p($an['etiologi']) ?></td>
                        <td><?= p($an['masalah']) ?></td>
                    </tr>
                <?php endforeach;
            else: ?>
                <tr><td colspan="4" style="text-align:center">-</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- DIAGNOSA -->
    <h3>Diagnosa Keperawatan Prioritas</h3>
    <table class="data">
        <thead>
            <tr>
                <th width="5%">No.</th>
                <th width="40%">DO/DS</th>
                <th width="55%">Diagnosa Keperawatan</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $diagnosa = decodeArr($lainnya['diagnosa'] ?? []);
            if (!empty($diagnosa)):
                foreach ($diagnosa as $i => $dx): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><?= p($dx['dods']) ?></td>
                        <td><?= p($dx['diagnosa_kep']) ?></td>
                    </tr>
                <?php endforeach;
            else: ?>
                <tr><td colspan="3" style="text-align:center">-</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- RENCANA -->
    <h3>Rencana Keperawatan</h3>
    <table class="header-table">
        <tr>
            <td width="30%"><strong>Nama Klien</strong></td>
            <td width="2%">:</td>
            <td width="18%"><?= p($identitas['nama_klien']) ?></td>
            <td width="20%"><strong>No. Registrasi</strong></td>
            <td width="2%">:</td>
            <td><?= p($identitas['no_registrasi']) ?></td>
        </tr>
    </table>
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
            <?php
            $rencana = decodeArr($lainnya['rencana'] ?? []);
            if (!empty($rencana)):
                foreach ($rencana as $i => $rn): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><?= p($rn['diagnosa']) ?></td>
                        <td><?= p($rn['tujuan']) ?></td>
                        <td><?= p($rn['intervensi']) ?></td>
                    </tr>
                <?php endforeach;
            else: ?>
                <tr><td colspan="4" style="text-align:center">-</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- IMPLEMENTASI -->
    <h3>Implementasi</h3>
    <table class="data">
        <thead>
            <tr>
                <th>No. DX</th>
                <th>Hari/Tanggal</th>
                <th>Jam</th>
                <th>Implementasi &amp; Hasil</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $implementasi = decodeArr($lainnya['implementasi'] ?? []);
            if (!empty($implementasi)):
                foreach ($implementasi as $impl): ?>
                    <tr>
                        <td><?= p($impl['no_dx']) ?></td>
                        <td><?= p($impl['hari_tgl']) ?></td>
                        <td><?= p($impl['jam']) ?></td>
                        <td><?= p($impl['implementasi_hasil']) ?></td>
                    </tr>
                <?php endforeach;
            else: ?>
                <tr><td colspan="4" style="text-align:center">-</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- EVALUASI -->
    <h3>Evaluasi</h3>
    <table class="data">
        <thead>
            <tr>
                <th>No. DX</th>
                <th>Hari/Tanggal</th>
                <th>Jam</th>
                <th>Evaluasi (SOAP)</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $evaluasi = decodeArr($lainnya['evaluasi'] ?? []);
            if (!empty($evaluasi)):
                foreach ($evaluasi as $ev): ?>
                    <tr>
                        <td><?= p($ev['no_dx']) ?></td>
                        <td><?= p($ev['hari_tgl']) ?></td>
                        <td><?= p($ev['jam']) ?></td>
                        <td><?= p($ev['evaluasi_soap']) ?></td>
                    </tr>
                <?php endforeach;
            else: ?>
                <tr><td colspan="4" style="text-align:center">-</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

</div>
</body>
</html>