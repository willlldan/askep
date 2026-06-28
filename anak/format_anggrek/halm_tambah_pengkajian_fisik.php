<?php
$form_id       = 4;
$section_name  = 'pemeriksaan_fisik';
$section_label = 'Pemeriksaan Fisik';
include dirname(__DIR__, 2) . '/partials/init_section.php';

$checkbox_fields = ['bibir_warna', 'bibir_kondisi'];
foreach ($checkbox_fields as $cf) {
    $existing_data[$cf] = isset($existing_data[$cf])
        ? (json_decode($existing_data[$cf], true) ?? [])
        : [];
}

$existing_terapi = json_decode($existing_data['terapi'] ?? '[]', true);
if (!is_array($existing_terapi)) {
    $existing_terapi = [];
}
if (empty($existing_terapi) && !empty(trim((string)($existing_data['terapi'] ?? '')))) {
    $existing_terapi = [[
        'jenis_obat'     => $existing_data['terapi'],
        'dosis'          => '',
        'kegunaan'       => '',
        'cara_pemberian' => '',
    ]];
}

// =============================================
// HANDLE POST - MAHASISWA SIMPAN DATA
// =============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $level === 'Mahasiswa') {

    if (isLocked($submission)) {
        redirectWithMessage($_SERVER['REQUEST_URI'], 'error', 'Data tidak dapat diubah karena sedang dalam proses review.');
    }

    $text_fields = [
        // Keadaan Umum & Vital Signs
        'keadaan_umum',
        'kesadaran',
        'tekanan_darah',
        'nadi',
        'suhu',
        'pernapasan',
        'bb',
        'tb',

        // Kepala 
        'rambut',
        'warna_rambut',
        'penyebaran',
        'rontok',
        'kebersihan_rambut',
        'benjolan',
        'benjolan_keterangan',
        'nyeri_tekan',
        'nyeri_tekan_keterangan',
        'tekstur_rambut',
        'tekstur_rambut_keterangan',
        // wajah
        'simetris',
        'simetris_keterangan',
        'bentuk_wajah',
        'nyeri_wajah',
        'nyeri_wajah_keterangan',
        'data_wajah',

        // Mata
        'edema_palpebra',
        'radang_palpebra',
        'sclera',
        'radang_conjungtiva',
        'anemis',
        'pupil_bentuk',
        'pupil_ukuran',
        'posisi_mata',
        'posisi_mata_keterangan',
        'gerakan_mata',
        'kelopak',
        'bulu_mata',
        'kabur',
        'diplopia',
        'data_mata',

        // Hidung & Sinus
        'bentuk_hidung',
        'septum',
        'secret',
        'data_hidung',

        // Telinga
        'telinga',
        'nyeri_telinga',

        // Mulut
        'keadaan_gigi',
        'karies',
        'gusi',
        'bibir_warna',
        'gusi_keterangan',
        'lidah',
        'bibir_warna_keterangan',
        'bibir_kondisi',
        'bibir_kondisi_keterangan',
        'bau_mulut',
        'bau_mulut_keterangan',
        'bicara',
        'data_mulut',

        // Tenggorokan
        'mukosa',
        'nyeri_tenggorokan',
        'menelan',

        // Leher
        'limfe',
        'data_leher',

        // Thorax & Pernapasan
        'bentuk_dada',
        'irama_nafas',
        'pengembangan',
        'tipe_nafas',
        'suara_auskultas',
        'suara_tambahan',
        'perkusi',
        'perkusi_redup',
        'perkusi_peka',
        'perkusi_hypersonor',
        'perkusi_tympani',

        // Jantung
        'ictus_cordis',
        'pembesaran_jantung',
        'bj1',
        'bj2',
        'bj3',
        'bunyi_tambahan',
        'data_lain_jantung',

        // Abdomen
        'membuncit',
        'luka_abdomen',
        'luka_abdomen_lain',
        'peristaltik',
        'hepar',
        'lien',
        'nyeri',
        'tympani',
        'redup',
        'data_abdomen',

        // Genitalia Laki-laki
        'fistula_pria',
        'uretra',
        'skrotum',
        'genital_ganda',
        'hidrokel_pria',

        // Genitalia Perempuan
        'labia',
        'fistula_wanita',
        'hidrokel_wanita',

        // Anus
        'anus_paten',
        'mekonium',

        // Ekstremitas Atas
        'gerak_atas',
        'abnormal_atas',
        'kekuatan_atas',
        'koordinasi_atas',
        'nyeri_atas',
        'suhu_atas',
        'raba_atas',

        // Ekstremitas Bawah
        'gaya_jalan',
        'kekuatan_bawah',
        'tonus_bawah',
        'nyeri_bawah',
        'suhu_bawah',
        'raba_bawah',

        // Refleks
        'kaku_kuduk',
        'kernig',
        'brudzinski',
        'refleks_bayi',
        'iddol',
        'startel',
        'sucking',
        'rooting',
        'gawn',
        'grabella',
        'ekruction',
        'moro',
        'grasping',
        'peres',
        'kremaster',

        // Integumen
        'turgor',
        'finger_print',
        'lesi',
        'kebersihan',
        'kelembaban',
        'warna_kulit',

        // Perkembangan
        'motorik_kasar_input',
        'motorik_halus_input',
        'bahasa_input',
        'personal_social_input',

        // Test Diagnostik & Laboratorium
        'diagnostik',
        'laboratorium',
        'penunjang_link',
        'penunjang',
        'terapi',
    ];

    $data = parse_dynamic_fields($_POST, $text_fields);

    $rows_terapi = [];
    foreach ($_POST['terapi'] ?? [] as $row) {
        if (!empty($row['jenis_obat']) || !empty($row['dosis']) || !empty($row['kegunaan']) || !empty($row['cara_pemberian'])) {
            $rows_terapi[] = [
                'jenis_obat'     => $row['jenis_obat']     ?? '',
                'dosis'          => $row['dosis']          ?? '',
                'kegunaan'       => $row['kegunaan']       ?? '',
                'cara_pemberian' => $row['cara_pemberian'] ?? '',
            ];
        }
    }
    $data['terapi'] = json_encode($rows_terapi);


    foreach ($checkbox_fields as $cf) {
        $data[$cf] = json_encode(isset($_POST[$cf]) ? (array)$_POST[$cf] : []);
    }

    if (!$submission) {
        $submission_id = createSubmission($user_id, $form_id, null, null, $mysqli);
    } else {
        $submission_id = $submission['id'];
        
    }


    saveSection($submission_id, $section_name, $section_label, $data, $mysqli);
    updateSubmissionStatus($submission_id, $form_id, $mysqli);
    redirectWithMessage($_SERVER['REQUEST_URI'], 'success', 'Data berhasil disimpan.');
}
?>

<main id="main" class="main">

    <?php include "anak/format_anggrek/tab.php"; ?>

    <section class="section dashboard">
        <?php include dirname(__DIR__, 2) . '/partials/notifikasi.php'; ?>
        <?php include dirname(__DIR__, 2) . '/partials/status_section.php'; ?>

        <section class="section dashboard">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-1"><strong>14. Pemeriksaan Fisik</strong></h5>

                    <!-- General Form Elements -->
                    <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">
                        <!-- KEADAAN UMUM -->
                        <div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Keadaan Umum</strong></label>
    <div class="col-sm-9">
        <textarea class="form-control" name="keadaan_umum" rows="1" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars($existing_data['keadaan_umum'] ?? '') ?></textarea>
    </div>
</div>

<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Kesadaran</strong></label>
    <div class="col-sm-9">
        <textarea class="form-control" name="kesadaran" rows="1" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars($existing_data['kesadaran'] ?? '') ?></textarea>
    </div>
</div>

<div class="row mb-2">
    <label class="col-sm-12"><strong>Tanda – Tanda Vital</strong></label>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Tekanan Darah</strong></label>
    <div class="col-sm-9">
        <div class="input-group">
            <textarea class="form-control" name="tekanan_darah" rows="1" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars($existing_data['tekanan_darah'] ?? '') ?></textarea>
            <span class="input-group-text">mmHg</span>
        </div>
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Denyut Nadi</strong></label>
    <div class="col-sm-9">
        <div class="input-group">
            <textarea class="form-control" name="nadi" rows="1" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars($existing_data['nadi'] ?? '') ?></textarea>
            <span class="input-group-text">x/menit</span>
        </div>
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Suhu</strong></label>
    <div class="col-sm-9">
        <div class="input-group">
            <textarea class="form-control" name="suhu" rows="1" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars($existing_data['suhu'] ?? '') ?></textarea>
            <span class="input-group-text">°C</span>
        </div>
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Pernapasan</strong></label>
    <div class="col-sm-9">
        <div class="input-group">
            <textarea class="form-control" name="pernapasan" rows="1" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars($existing_data['pernapasan'] ?? '') ?></textarea>
            <span class="input-group-text">x/menit</span>
        </div>
    </div>
</div>

<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Berat Badan</strong></label>
    <div class="col-sm-9">
        <textarea class="form-control" name="bb" rows="1" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars($existing_data['bb'] ?? '') ?></textarea>
    </div>
</div>

<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Tinggi Badan</strong></label>
    <div class="col-sm-9">
        <textarea class="form-control" name="tb" rows="1" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars($existing_data['tb'] ?? '') ?></textarea>
    </div>
</div>

<div class="row mb-2"><label class="col-sm-12 text-primary"><strong>Kepala</strong></label></div>
<div class="row mb-2"><label class="col-sm-12"><strong>Inspeksi</strong></label></div>

<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Keadaan Rambut & Hygiene</strong></label>
    <div class="col-sm-9">
        <textarea class="form-control" name="rambut" rows="1" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars($existing_data['rambut'] ?? '') ?></textarea>
    </div>
</div>

<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Warna Rambut</strong></label>
    <div class="col-sm-9">
        <textarea class="form-control" name="warna_rambut" rows="1" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars($existing_data['warna_rambut'] ?? '') ?></textarea>
    </div>
</div>

<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Penyebaran</strong></label>
    <div class="col-sm-9">
        <textarea class="form-control" name="penyebaran" rows="1" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars($existing_data['penyebaran'] ?? '') ?></textarea>
    </div>
</div>

<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Mudah Rontok</strong></label>
    <div class="col-sm-9">
        <textarea class="form-control" name="rontok" rows="1" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars($existing_data['rontok'] ?? '') ?></textarea>
    </div>
</div>

<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Kebersihan Rambut</strong></label>
    <div class="col-sm-9">
        <textarea class="form-control" name="kebersihan_rambut" rows="1" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars($existing_data['kebersihan_rambut'] ?? '') ?></textarea>
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Benjolan</strong></label>
    <div class="col-sm-9 d-flex align-items-center gap-3 flex-wrap">
        <select class="form-select" name="benjolan" style="max-width:200px" <?= $ro_disabled ?>>
            <option value="">Pilih</option>
            <option value="ada" <?= ($existing_data['benjolan'] ?? '') === 'ada' ? 'selected' : '' ?>>Ada</option>
            <option value="tidak" <?= ($existing_data['benjolan'] ?? '') === 'tidak' ? 'selected' : '' ?>>Tidak ada</option>
        </select>
        <textarea class="form-control" style="max-width:425px; overflow:hidden; resize:none;" name="benjolan_keterangan" rows="1" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars($existing_data['benjolan_keterangan'] ?? '') ?></textarea>
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Nyeri Tekan</strong></label>
    <div class="col-sm-9 d-flex align-items-center gap-3 flex-wrap">
        <select class="form-select" name="nyeri_tekan" style="max-width:200px" <?= $ro_disabled ?>>
            <option value="">Pilih</option>
            <option value="ada" <?= ($existing_data['nyeri_tekan'] ?? '') === 'ada' ? 'selected' : '' ?>>Ada</option>
            <option value="tidak" <?= ($existing_data['nyeri_tekan'] ?? '') === 'tidak' ? 'selected' : '' ?>>Tidak ada</option>
        </select>
        <textarea class="form-control" style="max-width:425px; overflow:hidden; resize:none;" name="nyeri_tekan_keterangan" rows="1" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars($existing_data['nyeri_tekan_keterangan'] ?? '') ?></textarea>
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Tekstur Rambut</strong></label>
    <div class="col-sm-9 d-flex align-items-center gap-3 flex-wrap">
        <select class="form-select" name="tekstur_rambut" style="max-width:200px" <?= $ro_disabled ?>>
            <option value="">Pilih</option>
            <option value="kasar" <?= ($existing_data['tekstur_rambut'] ?? '') === 'kasar' ? 'selected' : '' ?>>Kasar</option>
            <option value="halus" <?= ($existing_data['tekstur_rambut'] ?? '') === 'halus' ? 'selected' : '' ?>>Halus</option>
        </select>
        <textarea class="form-control" style="max-width:425px; overflow:hidden; resize:none;" name="tekstur_rambut_keterangan" rows="1" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars($existing_data['tekstur_rambut_keterangan'] ?? '') ?></textarea>
    </div>
</div>
                    <!-- Wajah -->
<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>Wajah</strong></label>
</div>
<div class="row mb-2">
    <label class="col-sm-12"><strong>Inspeksi</strong></label>
</div>

<!-- Simetris -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Simetris</strong></label>
    <div class="col-sm-9 d-flex align-items-center gap-3 flex-wrap">
        <select class="form-select" name="simetris" style="max-width:200px" <?= $ro_disabled ?>>
            <option value="">Pilih</option>
            <option value="ya" <?= ($existing_data['simetris'] ?? '') === 'ya' ? 'selected' : '' ?>>Simetris</option>
            <option value="tidak" <?= ($existing_data['simetris'] ?? '') === 'tidak' ? 'selected' : '' ?>>Tidak</option>
        </select>
        <textarea class="form-control" style="max-width:425px; overflow:hidden; resize:none;" name="simetris_keterangan" rows="1" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars($existing_data['simetris_keterangan'] ?? '') ?></textarea>
    </div>
</div>

<!-- Bentuk Wajah -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Bentuk Wajah</strong></label>
    <div class="col-sm-9">
        <textarea class="form-control" name="bentuk_wajah" rows="1" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars($existing_data['bentuk_wajah'] ?? '') ?></textarea>
    </div>
</div>

<!-- Palpasi -->
<div class="row mb-2"><label class="col-sm-12"><strong>Palpasi</strong></label></div>

<!-- Nyeri Tekan Wajah -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Nyeri Tekan</strong></label>
    <div class="col-sm-9 d-flex align-items-center gap-3 flex-wrap">
        <select class="form-select" name="nyeri_wajah" style="max-width:200px" <?= $ro_disabled ?>>
            <option value="">Pilih</option>
            <option value="ya" <?= ($existing_data['nyeri_wajah'] ?? '') === 'ya' ? 'selected' : '' ?>>Ada</option>
            <option value="tidak" <?= ($existing_data['nyeri_wajah'] ?? '') === 'tidak' ? 'selected' : '' ?>>Tidak</option>
        </select>
        <textarea class="form-control" style="max-width:425px; overflow:hidden; resize:none;" name="nyeri_wajah_keterangan" rows="1" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars($existing_data['nyeri_wajah_keterangan'] ?? '') ?></textarea>
    </div>
</div>

<!-- Data Lain Wajah -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Data Lain</strong></label>
    <div class="col-sm-9">
        <textarea class="form-control" name="data_wajah" rows="1" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars($existing_data['data_wajah'] ?? '') ?></textarea>
    </div>
</div>

<!-- MATA -->
<div class="row mb-2"><label class="col-sm-12 text-primary"><strong>Mata</strong></label></div>
<div class="row mb-2"><label class="col-sm-12"><strong>Inspeksi</strong></label></div>

<!-- Palpebra -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Palpebra</strong></label>
    <div class="col-sm-9 d-flex flex-column gap-3">
        <div class="d-flex gap-3 flex-wrap align-items-center">
            <span><strong>Edema:</strong></span>
            <select class="form-select" name="edema_palpebra" style="max-width:200px" <?= $ro_disabled ?>>
                <option value="">Pilih</option>
                <option value="ya" <?= ($existing_data['edema_palpebra'] ?? '') === 'ya' ? 'selected' : '' ?>>Ya</option>
                <option value="tidak" <?= ($existing_data['edema_palpebra'] ?? '') === 'tidak' ? 'selected' : '' ?>>Tidak</option>
            </select>
        </div>
        <div class="d-flex gap-3 flex-wrap align-items-center">
            <span><strong>Radang:</strong></span>
            <select class="form-select" name="radang_palpebra" style="max-width:200px" <?= $ro_disabled ?>>
                <option value="">Pilih</option>
                <option value="ya" <?= ($existing_data['radang_palpebra'] ?? '') === 'ya' ? 'selected' : '' ?>>Ya</option>
                <option value="tidak" <?= ($existing_data['radang_palpebra'] ?? '') === 'tidak' ? 'selected' : '' ?>>Tidak</option>
            </select>
        </div>
    </div>
</div>

<!-- Sclera, Conjungtiva, Pupil, dll menggunakan pola serupa: -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Posisi Mata</strong></label>
    <div class="col-sm-9 d-flex align-items-center gap-3 flex-wrap">
        <select class="form-select" name="posisi_mata" style="max-width:200px" <?= $ro_disabled ?>>
            <option value="">Pilih</option>
            <option value="ya" <?= ($existing_data['posisi_mata'] ?? '') === 'ya' ? 'selected' : '' ?>>Simetris</option>
            <option value="tidak" <?= ($existing_data['posisi_mata'] ?? '') === 'tidak' ? 'selected' : '' ?>>Tidak</option>
        </select>
        <textarea class="form-control" style="max-width:425px; overflow:hidden; resize:none;" name="posisi_mata_keterangan" rows="1" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars($existing_data['posisi_mata_keterangan'] ?? '') ?></textarea>
    </div>
</div>

<!-- Field text lainnya di Mata -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Gerakan Bola Mata</strong></label>
    <div class="col-sm-9"><textarea class="form-control" name="gerakan_mata" rows="1" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars($existing_data['gerakan_mata'] ?? '') ?></textarea></div>
</div>

<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Penutupan Kelopak Mata</strong></label>
    <div class="col-sm-9"><textarea class="form-control" name="kelopak" rows="1" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars($existing_data['kelopak'] ?? '') ?></textarea></div>
</div>

<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Keadaan Bulu Mata</strong></label>
    <div class="col-sm-9"><textarea class="form-control" name="bulu_mata" rows="1" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars($existing_data['bulu_mata'] ?? '') ?></textarea></div>
</div>

<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Data Lain</strong></label>
    <div class="col-sm-9"><textarea class="form-control" name="data_mata" rows="1" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars($existing_data['data_mata'] ?? '') ?></textarea></div>
</div>

<!-- HIDUNG & SINUS -->
<div class="row mb-2"><label class="col-sm-12 text-primary"><strong>Hidung & Sinus</strong></label></div>

<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Bentuk Hidung</strong></label>
    <div class="col-sm-9"><textarea class="form-control" name="bentuk_hidung" rows="1" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars($existing_data['bentuk_hidung'] ?? '') ?></textarea></div>
</div>

<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Septum</strong></label>
    <div class="col-sm-9"><textarea class="form-control" name="septum" rows="1" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars($existing_data['septum'] ?? '') ?></textarea></div>
</div>

<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Secret / Cairan</strong></label>
    <div class="col-sm-9"><textarea class="form-control" name="secret" rows="1" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars($existing_data['secret'] ?? '') ?></textarea></div>
</div>

<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Data Lain</strong></label>
    <div class="col-sm-9"><textarea class="form-control" name="data_hidung" rows="1" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars($existing_data['data_hidung'] ?? '') ?></textarea></div>
</div>

                        <!-- TELINGA -->
                        <div class="row mb-2">
                            <label class="col-sm-12 text-primary"><strong>Telinga</strong></label>
                        </div>

                        <div class="row mb-2">
                            <label class="col-sm-12"><strong>Inspeksi</strong></label>
                        </div>
                        <!-- Lubang Telinga -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Lubang Telinga</strong></label>
                            <div class="col-sm-9 d-flex gap-3 flex-wrap">
                                <select class="form-select" name="telinga" style="max-width:200px" <?= $ro_disabled ?>>
                                    <option value="">Pilih</option>
                                    <option value="bersih" <?= ($existing_data['telinga'] ?? '') === 'bersih' ? 'selected' : '' ?>>Bersih</option>
                                    <option value="serumen" <?= ($existing_data['telinga'] ?? '') === 'serumen' ? 'selected' : '' ?>>Serumen</option>
                                    <option value="nanah" <?= ($existing_data['telinga'] ?? '') === 'nanah' ? 'selected' : '' ?>>Nanah</option>
                                </select>
                            </div>
                        </div>

                        <!-- Palpasi -->
                        <div class="row mb-2">
                            <label class="col-sm-12"><strong>Palpasi</strong></label>
                        </div>

                        <!-- Nyeri Tekan -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Nyeri Tekan</strong></label>
                            <div class="col-sm-9">
                                <select class="form-select" name="nyeri_telinga" style="max-width:200px" <?= $ro_disabled ?>>
                                    <option value="">Pilih</option>
                                    <option value="ya" <?= ($existing_data['nyeri_telinga'] ?? '') === 'ya' ? 'selected' : '' ?>>Ya</option>
                                    <option value="tidak" <?= ($existing_data['nyeri_telinga'] ?? '') === 'tidak' ? 'selected' : '' ?>>Tidak</option>
                                </select>
                            </div>
                        </div>

                        <!-- MULUT -->
                        <div class="row mb-2">
                            <label class="col-sm-12 text-primary"><strong>Mulut</strong></label>
                        </div>

                        <div class="row mb-2">
                            <label class="col-sm-12"><strong>Inspeksi</strong></label>
                        </div>

                        <!-- Gigi - Keadaan -->
                        <div class="row mb-2">
                            <label class="col-sm-12"><strong>Gigi</strong></label>
                        </div>

                        <div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Keadaan Gigi</strong></label>
    <div class="col-sm-9">
        <textarea class="form-control" name="keadaan_gigi" rows="1" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars($existing_data['keadaan_gigi'] ?? '') ?></textarea>
    </div>
</div>

                        <!-- Karang / Karies -->
                        <div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Karang Gigi / Karies</strong></label>
    <div class="col-sm-9">
        <textarea class="form-control" name="karies" rows="1" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars($existing_data['karies'] ?? '') ?></textarea>
    </div>
</div>
                     <!-- Gusi -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Gusi</strong></label>
    <div class="col-sm-9 d-flex align-items-center gap-3 flex-wrap">
        <select class="form-select" name="gusi" style="max-width:200px" <?= $ro_disabled ?>>
            <option value="">Pilih</option>
            <option value="merah" <?= ($existing_data['gusi'] ?? '') === 'merah' ? 'selected' : '' ?>>Merah</option>
            <option value="radang" <?= ($existing_data['gusi'] ?? '') === 'radang' ? 'selected' : '' ?>>Radang</option>
            <option value="tidak" <?= ($existing_data['gusi'] ?? '') === 'tidak' ? 'selected' : '' ?>>Tidak</option>
        </select>
        <textarea class="form-control" name="gusi_keterangan" rows="1" style="max-width:425px; overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars($existing_data['gusi_keterangan'] ?? '') ?></textarea>
    </div>
</div>

<!-- Lidah (tetap seperti semula, namun jika ingin dirubah ke textarea, gunakan pola di bawah) -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Lidah</strong></label>
    <div class="col-sm-9 d-flex gap-3 flex-wrap">
        <select class="form-select" name="lidah" style="max-width:200px" <?= $ro_disabled ?>>
            <option value="">Pilih</option>
            <option value="ya" <?= ($existing_data['lidah'] ?? '') === 'ya' ? 'selected' : '' ?>>Kotor</option>
            <option value="tidak" <?= ($existing_data['lidah'] ?? '') === 'tidak' ? 'selected' : '' ?>>Tidak</option>
        </select>
    </div>
</div>

<!-- Bibir - Warna -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Bibir (Warna)</strong></label>
    <div class="col-sm-9 d-flex gap-3 flex-wrap align-items-center">
        <!-- Checkbox tetap sama -->
        <div class="form-check form-check-inline"><input class="form-check-input" type="checkbox" name="bibir_warna[]" value="cianosis" <?= $ro_disabled ?> <?= in_array('cianosis', $existing_data['bibir_warna'] ?? []) ? 'checked' : '' ?>><label class="form-check-label">Cianosis</label></div>
        <div class="form-check form-check-inline"><input class="form-check-input" type="checkbox" name="bibir_warna[]" value="pucat" <?= $ro_disabled ?> <?= in_array('pucat', $existing_data['bibir_warna'] ?? []) ? 'checked' : '' ?>><label class="form-check-label">Pucat</label></div>
        <div class="form-check form-check-inline"><input class="form-check-input" type="checkbox" name="bibir_warna[]" value="tidak" <?= $ro_disabled ?> <?= in_array('tidak', $existing_data['bibir_warna'] ?? []) ? 'checked' : '' ?>><label class="form-check-label">Tidak</label></div>
        <textarea class="form-control" name="bibir_warna_keterangan" rows="1" style="max-width:200px; overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars($existing_data['bibir_warna_keterangan'] ?? '') ?></textarea>
    </div>
</div>

<!-- Bibir - Kondisi -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Bibir (Kondisi)</strong></label>
    <div class="col-sm-9 d-flex gap-3 flex-wrap align-items-center">
        <div class="form-check form-check-inline"><input class="form-check-input" type="checkbox" name="bibir_kondisi[]" value="basah" <?= $ro_disabled ?><?= in_array('basah', $existing_data['bibir_kondisi'] ?? []) ? 'checked' : '' ?>><label class="form-check-label">Basah</label></div>
        <div class="form-check form-check-inline"><input class="form-check-input" type="checkbox" name="bibir_kondisi[]" value="kering" <?= $ro_disabled ?> <?= in_array('kering', $existing_data['bibir_kondisi'] ?? []) ? 'checked' : '' ?>><label class="form-check-label">Kering</label></div>
        <div class="form-check form-check-inline"><input class="form-check-input" type="checkbox" name="bibir_kondisi[]" value="pecah" <?= $ro_disabled ?> <?= in_array('pecah', $existing_data['bibir_kondisi'] ?? []) ? 'checked' : '' ?>><label class="form-check-label">Pecah</label></div>
        <textarea class="form-control" name="bibir_kondisi_keterangan" rows="1" style="max-width:200px; overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars($existing_data['bibir_kondisi_keterangan'] ?? '') ?></textarea>
    </div>
</div>

<!-- Bau Mulut -->
<div class="row mb-3 align-items-center">
    <label class="col-sm-2 col-form-label"><strong>Mulut Berbau</strong></label>
    <div class="col-sm-9 d-flex gap-3 align-items-center">
        <select class="form-select" name="bau_mulut" style="max-width:200px" <?= $ro_disabled ?>>
            <option value="">Pilih</option>
            <option value="ya" <?= ($existing_data['bau_mulut'] ?? '') === 'ya' ? 'selected' : '' ?>>Ya</option>
            <option value="tidak" <?= ($existing_data['bau_mulut'] ?? '') === 'tidak' ? 'selected' : '' ?>>Tidak</option>
        </select>
        <textarea class="form-control" name="bau_mulut_keterangan" rows="1" style="max-width:425px; overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars($existing_data['bau_mulut_keterangan'] ?? '') ?></textarea>
    </div>
</div>

<!-- Kemampuan Bicara -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Kemampuan Bicara</strong></label>
    <div class="col-sm-9">
        <textarea class="form-control" name="bicara" rows="1" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars($existing_data['bicara'] ?? '') ?></textarea>
    </div>
</div>

<!-- Data Lain -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Data Lain</strong></label>
    <div class="col-sm-9">
        <textarea class="form-control" name="data_mulut" rows="1" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars($existing_data['data_mulut'] ?? '') ?></textarea>
    </div>
</div>
                       <!-- TENGGOROKAN -->
<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>Tenggorokan</strong></label>
</div>

<!-- Warna Mukosa -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Warna Mukosa</strong></label>
    <div class="col-sm-9">
        <textarea class="form-control" name="mukosa" rows="1" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars($existing_data['mukosa'] ?? '') ?></textarea>
    </div>
</div>

<!-- Nyeri Tekan -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Nyeri Tekan</strong></label>
    <div class="col-sm-9">
        <textarea class="form-control" name="nyeri_tenggorokan" rows="1" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars($existing_data['nyeri_tenggorokan'] ?? '') ?></textarea>
    </div>
</div>

<!-- Nyeri Menelan -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Nyeri Menelan</strong></label>
    <div class="col-sm-9">
        <textarea class="form-control" name="menelan" rows="1" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars($existing_data['menelan'] ?? '') ?></textarea>
    </div>
</div>

<!-- LEHER -->
<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>Leher</strong></label>
</div>

<div class="row mb-2">
    <label class="col-sm-12"><strong>Palpasi</strong></label>
</div>

<!-- Kelenjar Limfe -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Kelenjar Limfe</strong></label>
    <div class="col-sm-9 d-flex gap-3 flex-wrap">
        <select class="form-select" name="limfe" style="max-width:200px" <?= $ro_disabled ?>>
            <option value="">Pilih</option>
            <option value="membesar" <?= ($existing_data['limfe'] ?? '') === 'membesar' ? 'selected' : '' ?>>Membesar</option>
            <option value="tidak" <?= ($existing_data['limfe'] ?? '') === 'tidak' ? 'selected' : '' ?>>Tidak</option>
        </select>
    </div>
</div>

<!-- Data Lain Leher -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Data Lain</strong></label>
    <div class="col-sm-9">
        <textarea class="form-control" name="data_leher" rows="1" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars($existing_data['data_leher'] ?? '') ?></textarea>
    </div>
</div>
                        <!-- THORAX -->
<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>Thorax dan Pernapasan</strong></label>
</div>

<!-- Bentuk Dada -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Bentuk Dada</strong></label>
    <div class="col-sm-9">
        <textarea class="form-control" name="bentuk_dada" rows="1" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars($existing_data['bentuk_dada'] ?? '') ?></textarea>
    </div>
</div>

<!-- Irama Pernapasan -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Irama Pernapasan</strong></label>
    <div class="col-sm-9">
        <textarea class="form-control" name="irama_nafas" rows="1" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars($existing_data['irama_nafas'] ?? '') ?></textarea>
    </div>
</div>

<!-- Pengembangan -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Pengembangan di Waktu Bernapas</strong></label>
    <div class="col-sm-9">
        <textarea class="form-control" name="pengembangan" rows="1" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars($existing_data['pengembangan'] ?? '') ?></textarea>
    </div>
</div>

<!-- Tipe Pernapasan -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Tipe Pernapasan</strong></label>
    <div class="col-sm-9">
        <textarea class="form-control" name="tipe_nafas" rows="1" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars($existing_data['tipe_nafas'] ?? '') ?></textarea>
    </div>
</div>

<!-- AUSKULTASI -->
<div class="row mb-2">
    <label class="col-sm-12"><strong>Auskultasi</strong></label>
</div>

<!-- Suara Nafas -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Suara Nafas</strong></label>
    <div class="col-sm-9 d-flex gap-3 flex-wrap">
        <select class="form-select" name="suara_auskultas" style="max-width:200px" <?= $ro_disabled ?>>
            <option value="">Pilih</option>
            <option value="vesikuler" <?= ($existing_data['suara_auskultas'] ?? '') === 'vesikuler' ? 'selected' : '' ?>>Vesikuler</option>
            <option value="bronchial" <?= ($existing_data['suara_auskultas'] ?? '') === 'bronchial' ? 'selected' : '' ?>>Bronchial</option>
            <option value="bronchovesikuler" <?= ($existing_data['suara_auskultas'] ?? '') === 'bronchovesikuler' ? 'selected' : '' ?>>Bronchovesikuler</option>
        </select>
    </div>
</div>

<!-- Suara Tambahan -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Suara Tambahan</strong></label>
    <div class="col-sm-9 d-flex gap-3 flex-wrap">
        <select class="form-select" name="suara_tambahan" style="max-width:200px" <?= $ro_disabled ?>>
            <option value="">Pilih</option>
            <option value="ronchi" <?= ($existing_data['suara_tambahan'] ?? '') === 'ronchi' ? 'selected' : '' ?>>Ronchi</option>
            <option value="wheezing" <?= ($existing_data['suara_tambahan'] ?? '') === 'wheezing' ? 'selected' : '' ?>>Wheezing</option>
            <option value="rales" <?= ($existing_data['suara_tambahan'] ?? '') === 'rales' ? 'selected' : '' ?>>Rales</option>
        </select>
    </div>
</div>

<!-- Perkusi -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Perkusi</strong></label>
    <div class="col-sm-9 d-flex gap-3 flex-wrap">
        <select class="form-select" name="perkusi" style="max-width:200px" <?= $ro_disabled ?>>
            <option value="">Pilih</option>
            <option value="redup" <?= ($existing_data['perkusi'] ?? '') === 'redup' ? 'selected' : '' ?>>Redup</option>
            <option value="pekak" <?= ($existing_data['perkusi'] ?? '') === 'pekak' ? 'selected' : '' ?>>Pekak</option>
            <option value="hypersonor" <?= ($existing_data['perkusi'] ?? '') === 'hypersonor' ? 'selected' : '' ?>>Hypersonor</option>
            <option value="tympani" <?= ($existing_data['perkusi'] ?? '') === 'tympani' ? 'selected' : '' ?>>Tympani</option>
        </select>
    </div>
</div>
<!-- JANTUNG -->
<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>Jantung</strong></label>
</div>

<div class="row mb-2">
    <label class="col-sm-12"><strong>Palpasi</strong></label>
</div>

<!-- Ictus Cordis -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Ictus Cordis</strong></label>
    <div class="col-sm-9">
        <textarea class="form-control" name="ictus_cordis" rows="1" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars($existing_data['ictus_cordis'] ?? '') ?></textarea>
    </div>
</div>

<!-- Pembesaran -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Pembesaran Jantung</strong></label>
    <div class="col-sm-9">
        <textarea class="form-control" name="pembesaran_jantung" rows="1" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars($existing_data['pembesaran_jantung'] ?? '') ?></textarea>
    </div>
</div>

<!-- Auskultasi -->
<div class="row mb-2">
    <label class="col-sm-12"><strong>Auskultasi</strong></label>
</div>

<!-- BJ I, II, III -->
<?php foreach (['bj1' => 'BJ I', 'bj2' => 'BJ II', 'bj3' => 'BJ III'] as $key => $label): ?>
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong><?= $label ?></strong></label>
    <div class="col-sm-9">
        <textarea class="form-control" name="<?= $key ?>" rows="1" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars($existing_data[$key] ?? '') ?></textarea>
    </div>
</div>
<?php endforeach; ?>

<!-- Bunyi Tambahan -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Bunyi Tambahan</strong></label>
    <div class="col-sm-9">
        <textarea class="form-control" name="bunyi_tambahan" rows="1" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars($existing_data['bunyi_tambahan'] ?? '') ?></textarea>
    </div>
</div>

<!-- Data Lain Jantung -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Data Lain</strong></label>
    <div class="col-sm-9">
        <textarea class="form-control" name="data_lain_jantung" rows="1" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars($existing_data['data_lain_jantung'] ?? '') ?></textarea>
    </div>
</div>

<!-- ABDOMEN -->
<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>Abdomen</strong></label>
</div>

<div class="row mb-2">
    <label class="col-sm-12"><strong>Inspeksi</strong></label>
</div>

<!-- Membuncit -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Membuncit</strong></label>
    <div class="col-sm-9">
        <textarea class="form-control" name="membuncit" rows="1" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars($existing_data['membuncit'] ?? '') ?></textarea>
    </div>
</div>

<!-- Luka Abdomen -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Ada Luka</strong></label>
    <div class="col-sm-9 d-flex gap-3 flex-wrap align-items-center">
        <select class="form-select" name="luka_abdomen" style="max-width:200px" <?= $ro_disabled ?>>
            <option value="">Pilih</option>
            <option value="ada" <?= ($existing_data['luka_abdomen'] ?? '') === 'ada' ? 'selected' : '' ?>>Ada</option>
            <option value="tidak" <?= ($existing_data['luka_abdomen'] ?? '') === 'tidak' ? 'selected' : '' ?>>Tidak</option>
        </select>
        <textarea class="form-control" name="luka_abdomen_lain" rows="1" style="max-width:425px; overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars($existing_data['luka_abdomen_lain'] ?? '') ?></textarea>
    </div>
</div>

<!-- Auskultasi, Palpasi, Perkusi -->
<?php 
$fields = [
    'peristaltik' => 'Peristaltik',
    'hepar' => 'Hepar',
    'lien' => 'Lien',
    'nyeri' => 'Nyeri Tekan',
    'tympani' => 'Tympani',
    'redup' => 'Redup',
    'data_abdomen' => 'Data Lain'
];

foreach ($fields as $key => $label): ?>
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong><?= $label ?></strong></label>
    <div class="col-sm-9">
        <textarea class="form-control" name="<?= $key ?>" rows="1" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars($existing_data[$key] ?? '') ?></textarea>
    </div>
</div>
<?php endforeach; ?>
                       <!-- GENITALIA -->
<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>Genitalia</strong></label>
</div>

<!-- LAKI-LAKI -->
<div class="row mb-2">
    <label class="col-sm-12"><strong>Anak Laki-laki</strong></label>
</div>

<?php 
$genitalia_pria = [
    'fistula_pria' => 'Fistula Urinari (Laki-laki)',
    'uretra' => 'Lubang Uretra',
    'skrotum' => 'Skrotum',
    'genital_ganda' => 'Genitalia Ganda',
    'hidrokel_pria' => 'Hidrokel'
];

foreach ($genitalia_pria as $key => $label): ?>
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong><?= $label ?></strong></label>
    <div class="col-sm-9">
        <textarea class="form-control" name="<?= $key ?>" rows="1" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars($existing_data[$key] ?? '') ?></textarea>
    </div>
</div>
<?php endforeach; ?>

<!-- PEREMPUAN -->
<div class="row mb-2">
    <label class="col-sm-12"><strong>Anak Perempuan</strong></label>
</div>

<?php 
$genitalia_wanita = [
    'labia' => 'Labia & Klitoris',
    'fistula_wanita' => 'Fistula Urogenital (Perempuan)',
    'hidrokel_wanita' => 'Hidrokel'
];

foreach ($genitalia_wanita as $key => $label): ?>
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong><?= $label ?></strong></label>
    <div class="col-sm-9">
        <textarea class="form-control" name="<?= $key ?>" rows="1" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars($existing_data[$key] ?? '') ?></textarea>
    </div>
</div>
<?php endforeach; ?>

<!-- ANUS -->
<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>Anus</strong></label>
</div>

<!-- Select Anus -->
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Lubang Anal Paten</strong></label>
    <div class="col-sm-9">
        <select class="form-select" name="anus_paten" style="max-width:200px" <?= $ro_disabled ?>>
            <option value="">Pilih</option>
            <option value="ya" <?= ($existing_data['anus_paten'] ?? '') === 'ya' ? 'selected' : '' ?>>Ya</option>
            <option value="tidak" <?= ($existing_data['anus_paten'] ?? '') === 'tidak' ? 'selected' : '' ?>>Tidak</option>
        </select>
    </div>
</div>

<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong>Lintasan Mekonium (36 jam)</strong></label>
    <div class="col-sm-9">
        <select class="form-select" name="mekonium" style="max-width:200px" <?= $ro_disabled ?>>
            <option value="">Pilih</option>
            <option value="ada" <?= ($existing_data['mekonium'] ?? '') === 'ada' ? 'selected' : '' ?>>Ada</option>
            <option value="tidak" <?= ($existing_data['mekonium'] ?? '') === 'tidak' ? 'selected' : '' ?>>Tidak</option>
        </select>
    </div>
</div>

<!-- EKSTREMITAS ATAS -->
<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>Ekstremitas Atas</strong></label>
</div>

<?php 
$ekstremitas_atas = [
    'gerak_atas' => 'Pergerakan Kanan/Kiri',
    'abnormal_atas' => 'Pergerakan Abnormal',
    'kekuatan_atas' => 'Kekuatan Otot Kanan/Kiri',
    'koordinasi_atas' => 'Koordinasi Gerak',
    'nyeri_atas' => 'Nyeri',
    'suhu_atas' => 'Rangsang Suhu',
    'raba_atas' => 'Rasa Raba'
];

foreach ($ekstremitas_atas as $key => $label): ?>
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong><?= $label ?></strong></label>
    <div class="col-sm-9">
        <textarea class="form-control" name="<?= $key ?>" rows="1" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars($existing_data[$key] ?? '') ?></textarea>
    </div>
</div>
<?php endforeach; ?>

<!-- EKSTREMITAS BAWAH -->
<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>Ekstremitas Bawah</strong></label>
</div>

<?php 
$ekstremitas_bawah = [
    'gaya_jalan' => 'Gaya Berjalan',
    'kekuatan_bawah' => 'Kekuatan Kanan/Kiri',
    'tonus_bawah' => 'Tonus Otot Kanan/Kiri',
    'nyeri_bawah' => 'Nyeri',
    'suhu_bawah' => 'Rangsang Suhu',
    'raba_bawah' => 'Rasa Raba'
];

foreach ($ekstremitas_bawah as $key => $label): ?>
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong><?= $label ?></strong></label>
    <div class="col-sm-9">
        <textarea class="form-control" name="<?= $key ?>" rows="1" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars($existing_data[$key] ?? '') ?></textarea>
    </div>
</div>
<?php endforeach; ?>
                    <!-- REFLEKS -->
<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>Tanda Perangsangan Selaput Otak & Refleks Bayi</strong></label>
</div>

<?php 
$refleks_fields = [
    'kaku_kuduk' => 'Kaku Kuduk',
    'kernig' => 'Kernig Sign',
    'brudzinski' => 'Refleks Brudzinski',
    'refleks_bayi' => 'Refleks pada Bayi',
    'iddol' => 'Refleks Iddol',
    'startel' => 'Refleks Startel',
    'sucking' => 'Refleks Sucking (Isap)',
    'rooting' => 'Refleks Rooting (Menoleh)',
    'gawn' => 'Refleks Gawn',
    'grabella' => 'Refleks Grabella',
    'ekruction' => 'Refleks Ekruction',
    'moro' => 'Refleks Moro',
    'grasping' => 'Refleks Grasping',
    'peres' => 'Refleks Peres',
    'kremaster' => 'Refleks Kremaster'
];

foreach ($refleks_fields as $key => $label): ?>
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong><?= $label ?></strong></label>
    <div class="col-sm-9">
        <textarea class="form-control" name="<?= $key ?>" rows="1" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars($existing_data[$key] ?? '') ?></textarea>
    </div>
</div>
<?php endforeach; ?>

<!-- INTEGUMEN -->
<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>Integumen</strong></label>
</div>

<?php 
$integumen_fields = [
    'turgor' => 'Turgor Kulit',
    'finger_print' => 'Finger Print di Dahi',
    'lesi' => 'Adanya Lesi',
    'kebersihan' => 'Kebersihan Kulit',
    'kelembaban' => 'Kelembaban Kulit',
    'warna_kulit' => 'Warna Kulit'
];

foreach ($integumen_fields as $key => $label): ?>
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong><?= $label ?></strong></label>
    <div class="col-sm-9">
        <textarea class="form-control" name="<?= $key ?>" rows="1" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars($existing_data[$key] ?? '') ?></textarea>
    </div>
</div>
<?php endforeach; ?>
                      <!-- 15. PERKEMBANGAN -->
<div class="row mb-2">
    <label class="col-sm-12 text-primary">
        <strong>15. Pemeriksaan Tingkat Perkembangan (0 – 6 Tahun)</strong>
    </label>
    <label class="col-sm-12"><em>Dengan menggunakan DDST</em></label>
</div>

<?php 
$perkembangan_fields = [
    'motorik_kasar_input' => 'Motorik Kasar',
    'motorik_halus_input' => 'Motorik Halus',
    'bahasa_input' => 'Bahasa',
    'personal_social_input' => 'Personal Social'
];

foreach ($perkembangan_fields as $key => $label): ?>
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong><?= $label ?></strong></label>
    <div class="col-sm-9">
        <textarea class="form-control" name="<?= $key ?>" rows="1" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars($existing_data[$key] ?? '') ?></textarea>
    </div>
</div>
<?php endforeach; ?>

<!-- 16. TEST DIAGNOSTIK -->
<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>16. Test Diagnostik</strong></label>
</div>
<div class="row mb-3 align-items-start">
    <div class="col-sm-11">
        <textarea class="form-control" name="diagnostik" rows="2" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars($existing_data['diagnostik'] ?? '') ?></textarea>
    </div>
</div>

<!-- 17. LABORATORIUM -->
<div class="row mb-2">
    <label class="col-sm-12 text-primary"><strong>17. Laboratorium</strong></label>
</div>
<div class="row mb-3 align-items-start">
    <div class="col-sm-11">
        <textarea class="form-control" name="laboratorium" rows="2" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars($existing_data['laboratorium'] ?? '') ?></textarea>
    </div>
</div>

<!-- PENUNJANG & LINK -->
<?php 
$penunjang_fields = [
    'penunjang_link' => 'Link drive Laboratorium',
    'penunjang' => 'Pemeriksaan Penunjang'
];
?>

<?php foreach ($penunjang_fields as $key => $label): ?>
<div class="row mb-3 align-items-start">
    <label class="col-sm-2 col-form-label"><strong><?= $label ?></strong></label>
    <div class="col-sm-9">
        <textarea class="form-control" name="<?= $key ?>" rows="1" style="overflow:hidden; resize:none;" placeholder="<?= $key === 'penunjang' ? 'Foto Rontgen, CT Scan, MRI, USG, EEG, ECG' : '' ?>" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars($existing_data[$key] ?? '') ?></textarea>
    </div>
</div>
<?php endforeach; ?>

                        <!-- TERAPI -->
                        <div class="row mb-2">
                            <label class="col-sm-12 text-primary">
                                <strong>Terapi/Obat</strong>
                            </label>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered" id="tabel-terapi">
                                <thead>
                                    <tr>
                                        <th style="width:40px">No</th>
                                        <th>Jenis Obat</th>
                                        <th style="width:120px">Dosis</th>
                                        <th>Kegunaan</th>
                                        <th style="width:160px">Cara Pemberian</th>
                                        <?php if (!$is_dosen): ?>
                                            <th style="width:50px"></th>
                                        <?php endif; ?>
                                    </tr>
                                </thead>
                                <tbody id="tbody-terapi">
                                    <?php 
$rows_terapi = $existing_terapi ?: [['jenis_obat' => '', 'dosis' => '', 'kegunaan' => '', 'cara_pemberian' => '']];
foreach ($rows_terapi as $i => $row): 
?>
    <tr>
        <td class="text-center align-middle row-no-terapi"><?= $i + 1 ?></td>
        <td>
            <textarea class="form-control form-control-sm" name="terapi[<?= $i ?>][jenis_obat]" 
                      style="overflow:hidden; resize:none; min-height:30px;" 
                      oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" 
                      <?= $ro ?>><?= htmlspecialchars($row['jenis_obat'] ?? '') ?></textarea>
        </td>
        <td>
            <textarea class="form-control form-control-sm" name="terapi[<?= $i ?>][dosis]" 
                      style="overflow:hidden; resize:none; min-height:30px;" 
                      oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" 
                      <?= $ro ?>><?= htmlspecialchars($row['dosis'] ?? '') ?></textarea>
        </td>
        <td>
            <textarea class="form-control form-control-sm" name="terapi[<?= $i ?>][kegunaan]" 
                      style="overflow:hidden; resize:none; min-height:30px;" 
                      oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" 
                      <?= $ro ?>><?= htmlspecialchars($row['kegunaan'] ?? '') ?></textarea>
        </td>
        <td>
            <textarea class="form-control form-control-sm" name="terapi[<?= $i ?>][cara_pemberian]" 
                      style="overflow:hidden; resize:none; min-height:30px;" 
                      oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" 
                      <?= $ro ?>><?= htmlspecialchars($row['cara_pemberian'] ?? '') ?></textarea>
        </td>
        <?php if (!$is_dosen): ?>
            <td class="text-center align-middle">
                <button type="button" class="btn btn-sm btn-danger btn-hapus-row-terapi" <?= $ro_disabled ?>>
                    <i class="bi bi-trash"></i>
                </button>
            </td>
        <?php endif; ?>
    </tr>
<?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <?php if (!$is_dosen): ?>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <small class="text-muted">
                                    Isi terapi atau obat yang sedang dikonsumsi saat ini.
                                </small>
                                <button type="button" class="btn btn-sm btn-outline-primary" id="btn-tambah-terapi" <?= $ro_disabled ?>>
                                    <i class="bi bi-plus-circle"></i> Tambah Baris
                                </button>
                            </div>
                        <?php endif; ?>


                        <!-- TOMBOL SUBMIT -->
                        <?php if (!$is_dosen): ?>
                            <div class="row mb-3">
                                <div class="col-sm-11 d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                </div>
                            </div>
                        <?php endif; ?>
                    </form>
                </div>
            </div>

            <?php if (!$is_dosen): ?>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const isReadonly = <?= json_encode($is_readonly) ?>;
                        const tbodyTerapi = document.getElementById('tbody-terapi');

                        function reindexRowsTerapi() {
                            tbodyTerapi.querySelectorAll('tr').forEach((tr, i) => {
                                tr.querySelector('.row-no-terapi').textContent = i + 1;
                                tr.querySelectorAll('[name]').forEach(el => {
                                    el.name = el.name.replace(/terapi\[\d+\]/, `terapi[${i}]`);
                                });
                            });
                        }

                        function makeRowTerapi(index) {
                            return `<tr>
                                <td class="text-center align-middle row-no-terapi">${index + 1}</td>
                                <td><input type="text" class="form-control form-control-sm" name="terapi[${index}][jenis_obat]"></td>
                                <td><input type="text" class="form-control form-control-sm" name="terapi[${index}][dosis]"></td>
                                <td><input type="text" class="form-control form-control-sm" name="terapi[${index}][kegunaan]"></td>
                                <td><input type="text" class="form-control form-control-sm" name="terapi[${index}][cara_pemberian]"></td>
                                <td class="text-center align-middle">
                                    <button type="button" class="btn btn-sm btn-danger btn-hapus-row-terapi">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>`;
                        }

                        const btnTambahTerapi = document.getElementById('btn-tambah-terapi');
                        if (btnTambahTerapi) {
                            btnTambahTerapi.addEventListener('click', function() {
                                if (isReadonly) return;
                                const count = tbodyTerapi.querySelectorAll('tr').length;
                                tbodyTerapi.insertAdjacentHTML('beforeend', makeRowTerapi(count));
                            });
                        }

                        tbodyTerapi.addEventListener('click', function(e) {
                            const btn = e.target.closest('.btn-hapus-row-terapi');
                            if (!btn || isReadonly) return;
                            if (tbodyTerapi.querySelectorAll('tr').length <= 1) return;
                            btn.closest('tr').remove();
                            reindexRowsTerapi();
                        });

                        if (isReadonly && btnTambahTerapi) {
                            btnTambahTerapi.setAttribute('disabled', 'disabled');
                        }
                    });
                </script>
            <?php endif; ?>

            <?php include dirname(__DIR__, 2) . '/partials/footer_form.php'; ?>


        </section>
</main>
