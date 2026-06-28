<?php
$form_id       = 21;
$section_name  = 'pemeriksaan_fisik';
$section_label = 'Pemeriksaan Fisik';
include dirname(__DIR__, 2) . '/partials/init_section.php';

// Decode checkbox fields
$checkbox_fields = ['mulutdantenggorokan'];
foreach ($checkbox_fields as $cf) {
    if (isset($existing_data[$cf])) {
        // Jika datanya masih berbentuk string JSON, lakukan json_decode
        // Jika datanya sudah berbentuk array, langsung pakai saja
        $existing_data[$cf] = is_string($existing_data[$cf]) 
            ? (json_decode($existing_data[$cf], true) ?? []) 
            : $existing_data[$cf];
    } else {
        $existing_data[$cf] = [];
    }
}

// =============================================
// HANDLE POST - MAHASISWA SIMPAN DATA
// =============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $level === 'Mahasiswa') {

    if (isLocked($submission)) {
        redirectWithMessage($_SERVER['REQUEST_URI'], 'error', 'Data tidak dapat diubah karena sedang dalam proses review.');
    }


$data = [
    // =========================================================================
    // 2. PEMERIKSAAN FISIK SPESIFIK WITH BODY SISTEM
    // =========================================================================

    // a. Pernafasan (B1: Breathing)
    'hidung'                     => $_POST['hidung']                     ?? '',
    'trakea'                     => $_POST['trakea']                     ?? '',
    'nyeri'                      => $_POST['nyeri']                      ?? '',
    'dypsnea'                    => $_POST['dypsnea']                    ?? '',
    'cyanosis'                   => $_POST['cyanosis']                   ?? '',
    'retraksidada'               => $_POST['retraksidada']               ?? '',
    'batukdarah'                 => $_POST['batukdarah']                 ?? '',
    'orthopnea'                  => $_POST['orthopnea']                  ?? '',
    'napasdangkal'               => $_POST['napasdangkal']               ?? '',
    'sputum'                     => $_POST['sputum']                     ?? '',
    'trakeostomi'                => $_POST['trakeostomi']                ?? '',
    'suaratambahannapas'         => $_POST['suaratambahannapas']         ?? '',
    'bentukdada'                 => $_POST['bentukdada']                 ?? '',
    'lainnyabentukdada'          => $_POST['lainnyabentukdada']          ?? '',

    // b. Cardiovaskuler (B2: Bleeding)
    'nyeridada'                  => $_POST['nyeridada']                  ?? '',
    'pusing'                     => $_POST['pusing']                     ?? '',
    'sakitkepala'                => $_POST['sakitkepala']                ?? '',
    'palpitasi'                  => $_POST['palpitasi']                  ?? '',
    'clubbingfinger'             => $_POST['clubbingfinger']             ?? '',
    'suarajantung'               => $_POST['suarajantung']               ?? '',
    'edema'                      => $_POST['edema']                      ?? '',
    'lainnyaedema'               => $_POST['lainnyaedema']               ?? '',
    'sebutkanedema'              => $_POST['sebutkanedema']              ?? '',

    // c. Persyarafan (B3: Brain)
    'kesadaran'                  => $_POST['kesadaran']                  ?? '',
    'e'                      => $_POST['e']                      ?? '',
    'm'                      => $_POST['m']                      ?? '',
    'v'                      => $_POST['v']                      ?? '',
    'kejang'                     => $_POST['kejang']                     ?? '',
    'kepala'                     => $_POST['kepala']                     ?? '',
    'wajah'                      => $_POST['wajah']                      ?? '',
    'sklera'                     => $_POST['sklera']                     ?? '',
    'konjungtiva'                => $_POST['konjungtiva']                ?? '',
    'pupil'                      => $_POST['pupil']                      ?? '',
    'ukuran_pupil'               => $_POST['ukuran_pupil']               ?? '',
    'leher'                      => $_POST['leher']                      ?? '',
    'reflekstendonnormal'        => $_POST['reflekstendonnormal']        ?? '',
    'reflekstidaknormal'         => $_POST['reflekstidaknormal']         ?? '',
    'pendengaran_kiri'           => $_POST['pendengaran_kiri']           ?? '',
    'pendengaran_kanan'          => $_POST['pendengaran_kanan']          ?? '',
    'penciuman'                 => $_POST['penciuman']                 ?? '',
'pengecapan'                 => $_POST['pengecapan']                 ?? '',
    'penglihatan_kiri'           => $_POST['penglihatan_kiri']       ?? '', // Menyesuaikan typo input name di HTML kamu: name="pengpenglihatan_kiri"
    'penglihatan_kanan'          => $_POST['penglihatan_kanan']          ?? '',
    'alatbantu'                  => $_POST['alatbantu']                  ?? '',
    'perabaan_panas'             => $_POST['perabaan_panas']             ?? '',
    'perabaan_dingin'            => $_POST['perabaan_dingin']            ?? '',
    'perabaan_tekan'             => $_POST['perabaan_tekan']             ?? '',

    // =========================================================================
    // d. Perkemihan-Eliminasi Urin (B4: Bladder)
    // =========================================================================
    'produksiurine'              => $_POST['produksiurine']              ?? '',
    'frekuensi_urin'             => $_POST['frekuensi']                  ?? '', // Catatan: Name di HTML berupa 'frekuensi', disesuaikan agar tidak bentrok dengan frekuensi BAB/Makan
    'warna'                 => $_POST['warna']                      ?? '', // Catatan: Name di HTML berupa 'warna'
    'bau'                   => $_POST['bau']                        ?? '', // Catatan: Name di HTML berupa 'bau'
    'douwercateter'              => $_POST['douwercateter']              ?? '',
    'harike_cateter'             => $_POST['harike_cateter']             ?? '',
    'spollingblass'              => $_POST['spollingblass']              ?? '',
    'kelainandalamurine'         => $_POST['kelainandalamurine']         ?? '',

    // =========================================================================
    // e. Pencernaan-Eliminasi Alvi (B5: Bowel)
    // =========================================================================
    'mulutdantenggorokan'        => $_POST['mulutdantenggorokan']        ?? [], // Berupa array karena input type="checkbox"
    'abdomen'                    => $_POST['abdomen']                    ?? '',
    'rektum'                     => $_POST['rektum']                     ?? '',
    'anjuranpuasa'               => $_POST['anjuranpuasa']               ?? '',
    'selama'                     => $_POST['selama']                     ?? '',
    'dietyangdiberikan'          => $_POST['dietyangdiberikan']          ?? '',
    'terpasangngt'               => $_POST['terpasangngt']               ?? '',
    'harike_ngt'                 => $_POST['harike_ngt']                 ?? '',
    'kelainansalurancerna'       => $_POST['kelainansalurancerna']       ?? '',

    // =========================================================================
    // f. Tulang-Otot-Integumen (B6: Bone)
    // =========================================================================
    'kelainanpadatulang'         => $_POST['kelainanpadatulang']         ?? '',
    'kekuatanotot'               => $_POST['kekuatanotot']               ?? '',
    'hemiparese'                 => $_POST['hemiparese']                 ?? '',
    'tetraparese'                => $_POST['tetraparese']                ?? '',
    'rom'                        => $_POST['rom']                        ?? '',
    'lainnya'                    => $_POST['lainnya']                    ?? '',
    'ekstremitasatas'            => $_POST['ekstremitasatas']            ?? '',
    'ekstremitasbawah'           => $_POST['ekstremitasbawah']           ?? '',
    'tulangbelakang'             => $_POST['tulangbelakang']             ?? '',
    'warnakulit'                 => $_POST['warnakulit']                 ?? '',
    'akral'                      => $_POST['akral']                      ?? '',
    'turgor'                     => $_POST['turgor']                     ?? '',
    // g. Sistem Endokrin
    'terapihormon'               => $_POST['terapihormon']               ?? '',

    // h. Sistem Reproduksi
    'sistemreproduksi'           => $_POST['sistemreproduksi']           ?? '',
    'kelainanbentuk'             => $_POST['kelainanbentuk']             ?? '',
    'kebersihan'                 => $_POST['kebersihan']                 ?? '',

    // i. Pola Aktivitas (Makan & Minum)
    'frekuensi'                  => $_POST['frekuensi']                  ?? '',
    'jenismenu'                  => $_POST['jenismenu']                  ?? '',
    'pantangan'                  => $_POST['pantangan']                  ?? '',
    'alergi'                     => $_POST['alergi']                     ?? '',
    'minumfrekuensi'             => $_POST['minumfrekuensi']             ?? '',
    'minumjenismenu'             => $_POST['minumjenismenu']             ?? '',
    'minumpantangan'             => $_POST['minumpantangan']             ?? '',
    'minumalergi'                => $_POST['minumalergi']                ?? '',

    // i. Pola Aktivitas (Kebersihan Diri)
    'mandi'                      => $_POST['mandi']                      ?? '',
    'keramas'                    => $_POST['keramas']                    ?? '',
    'sikatgigi'                  => $_POST['sikatgigi']                  ?? '',
    'memotongkuku'               => $_POST['memotongkuku']               ?? '',
    'gantipakaian'               => $_POST['gantipakaian']               ?? '',
    'masalahlainkebersihandiri'  => $_POST['masalahlainkebersihandiri']  ?? '',

    // j. Social Interaction (Interaksi Sosial)
    'dukungankeluarga'           => $_POST['dukungankeluarga']           ?? '',
    'dukungankel'                => $_POST['dukungankel']                ?? '',
    'hubunganklien'              => $_POST['hubunganklien']              ?? '',
    'menungguklien'              => $_POST['menungguklien']              ?? '',
    'hidung_lainlain'              => $_POST['hidung_lainlain']              ?? '',
];
 $checkbox_fields = ['mulutdantenggorokan'];
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

    <?php include "gadar/icu/tab.php"; ?>

    <section class="section dashboard">

        <!-- NOTIFIKASI -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?= $_SESSION['success'];
                                                unset($_SESSION['success']); ?></div>
        <?php endif; ?>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= $_SESSION['error'];
                                            unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <!-- Status badge -->
        <?php if ($section_status): ?>
            <?php $badge = ['draft' => 'secondary', 'submitted' => 'primary', 'revision' => 'warning', 'approved' => 'success']; ?>
            <div class="alert alert-<?= $badge[$section_status] ?>">
                Status: <strong><?= ucfirst($section_status) ?></strong>
                | Reviewed by: <strong><?= $submission['dosen_name'] ? htmlspecialchars($submission['dosen_name']) : '-' ?></strong>
            </div>
        <?php endif; ?>
   
    <section class="section dashboard">
        <div class="card">
            <div class="card-body">
            
                <!-- General Form Elements -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

             <!-- Bagian Secondary Survey -->

                   <h5 class="card-title mb-1"><strong>2. Pemeriksaan Fisik Spesifik With Body Sistem</strong></h5>

<div class="row mb-3">
    <label class="col-sm-10 col-form-label text-primary">
        <strong>a. Pernafasan (B1: Breathing)</strong>
    </label>
</div>   

<div class="row mb-2">
    <div class="col-sm-2">
        <strong>Hidung</strong>
    </div> 

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="hidung" value="asimetris" <?= ($existing_data['hidung'] ?? '') === 'asimetris' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Asimetris</label>
        </div>
    </div>

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="hidung" value="deviasiseptum" <?= ($existing_data['hidung'] ?? '') === 'deviasiseptum' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Deviasi Septum</label>
        </div>
    </div>

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="hidung" value="epistaksis" <?= ($existing_data['hidung'] ?? '') === 'epistaksis' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Epistaksis</label>
        </div>
    </div>

   <div class="col-sm-4"> <div class="form-check">
        <input class="form-check-input" type="radio" name="hidung" id="radio_lainlain" value="lainlain" 
            <?= ($existing_data['hidung'] ?? '') === 'lainlain' ? 'checked' : '' ?> <?= $ro_disabled ?>>
        <label class="form-check-label" for="radio_lainlain">Lain-lain</label>

        <input type="text" 
               class="form-control form-control-sm mt-2 input-lain-lain" 
               name="hidung_lainlain" 
               placeholder="Sebutkan lainnya..." 
               value="<?= htmlspecialchars($existing_data['hidung_lainlain'] ?? '') ?>" 
               <?= $ro_disabled ?>>
    </div>
</div>
<style>
    /* Sembunyikan input teks secara default */
    .input-lain-lain {
        display: none;
    }
    /* Tampilkan input teks jika radio dengan value 'lainlain' dicentang */
    input[value="lainlain"]:checked ~ .input-lain-lain {
        display: inline-block;
    }
</style>

<div class="row mb-2">
    <div class="col-sm-2">
        <strong>Trakea</strong>
    </div> 

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="trakea" value="deviasitrakea" <?= ($existing_data['trakea'] ?? '') === 'deviasitrakea' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Deviasi Trakea</label>
        </div>
    </div>

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="trakea" value="disfagia" <?= ($existing_data['trakea'] ?? '') === 'disfagia' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Disfagia</label>
        </div>
    </div>
</div>  

<div class="row mb-2">
    <div class="col-sm-2">
        <strong>Nyeri</strong>
    </div> 

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="nyeri" value="ya" <?= ($existing_data['nyeri'] ?? '') === 'ya' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Ya</label>
        </div>
    </div>

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="nyeri" value="tidak" <?= ($existing_data['nyeri'] ?? '') === 'tidak' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Tidak</label>
        </div>
    </div>
</div>    

<div class="row mb-2">
    <div class="col-sm-2">
        <strong>Dypsnea</strong>
    </div> 

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="dypsnea" value="ya" <?= ($existing_data['dypsnea'] ?? '') === 'ya' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Ya</label>
        </div>
    </div>

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="dypsnea" value="tidak" <?= ($existing_data['dypsnea'] ?? '') === 'tidak' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Tidak</label>
        </div>
    </div>
</div>    

<div class="row mb-2">
    <div class="col-sm-2">
        <strong>Cyanosis</strong>
    </div> 

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="cyanosis" value="ya" <?= ($existing_data['cyanosis'] ?? '') === 'ya' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Ya</label>
        </div>
    </div>

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="cyanosis" value="tidak" <?= ($existing_data['cyanosis'] ?? '') === 'tidak' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Tidak</label>
        </div>
    </div>
</div> 

<div class="row mb-2">
    <div class="col-sm-2">
        <strong>Retraksi Dada</strong>
    </div> 

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="retraksidada" value="ya" <?= ($existing_data['retraksidada'] ?? '') === 'ya' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Ya</label>
        </div>
    </div>

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="retraksidada" value="tidak" <?= ($existing_data['retraksidada'] ?? '') === 'tidak' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Tidak</label>
        </div>
    </div>
</div>    

<div class="row mb-2">
    <div class="col-sm-2">
        <strong>Batuk Darah</strong>
    </div> 

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="batukdarah" value="ya" <?= ($existing_data['batukdarah'] ?? '') === 'ya' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Ya</label>
        </div>
    </div>

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="batukdarah" value="tidak" <?= ($existing_data['batukdarah'] ?? '') === 'tidak' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Tidak</label>
        </div>
    </div>
</div>
                        
            <div class="row mb-2">
    <div class="col-sm-2">
        <strong>Orthopnea</strong>
    </div> 

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="orthopnea" value="ya" <?= ($existing_data['orthopnea'] ?? '') === 'ya' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Ya</label>
        </div>
    </div>

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="orthopnea" value="tidak" <?= ($existing_data['orthopnea'] ?? '') === 'tidak' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Tidak</label>
        </div>
    </div>
</div>    

<div class="row mb-2">
    <div class="col-sm-2">
        <strong>Napas Dangkal</strong>
    </div> 

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="napasdangkal" value="ya" <?= ($existing_data['napasdangkal'] ?? '') === 'ya' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Ya</label>
        </div>
    </div>

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="napasdangkal" value="tidak" <?= ($existing_data['napasdangkal'] ?? '') === 'tidak' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Tidak</label>
        </div>
    </div>
</div>    

<div class="row mb-2">
    <div class="col-sm-2">
        <strong>Sputum</strong>
    </div> 

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="sputum" value="ya" <?= ($existing_data['sputum'] ?? '') === 'ya' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Ya</label>
        </div>
    </div>

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="sputum" value="tidak" <?= ($existing_data['sputum'] ?? '') === 'tidak' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Tidak</label>
        </div>
    </div>
</div>

<div class="row mb-2">
    <div class="col-sm-2">
        <strong>Trakeostomi</strong>
    </div> 

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="trakeostomi" value="ya" <?= ($existing_data['trakeostomi'] ?? '') === 'ya' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Ya</label>
        </div>
    </div>

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="trakeostomi" value="tidak" <?= ($existing_data['trakeostomi'] ?? '') === 'tidak' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Tidak</label>
        </div>
    </div>
</div>    

<div class="row mb-2">
    <div class="col-sm-2">
        <strong>Suara Napas Tambahan </strong>
    </div> 

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="suaratambahannapas" value="weezhing" <?= ($existing_data['suaratambahannapas'] ?? '') === 'weezhing' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Weezhing</label>
        </div>
    </div>

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="suaratambahannapas" value="ronchi" <?= ($existing_data['suaratambahannapas'] ?? '') === 'ronchi' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Ronchi</label>
        </div>
    </div>

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="suaratambahannapas" value="crackles" <?= ($existing_data['suaratambahannapas'] ?? '') === 'crackles' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Crakles</label>
        </div>
    </div>

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="suaratambahannapas" value="stridor" <?= ($existing_data['suaratambahannapas'] ?? '') === 'stridor' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Stridor</label>
        </div>
    </div>
</div>   

<div class="row mb-2">
    <div class="col-sm-2">
        <strong>Bentuk Dada</strong>
    </div> 

    <div class="col-sm-10">
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="bentukdada" value="simetris" <?= ($existing_data['bentukdada'] ?? '') === 'simetris' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Simetris</label>
        </div>
        
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="bentukdada" value="tidaksimetris" <?= ($existing_data['bentukdada'] ?? '') === 'tidaksimetris' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Tidak Simetris</label>
        </div>

        <div class="row mt-2">
    <div class="col-sm-11">
        <label><strong>Lainnya</strong></label>
        <textarea name="lainnyabentukdada" 
                  class="form-control" 
                  rows="1" 
                  style="overflow:hidden; resize:none;" 
                  oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" 
                  <?= $ro ?>><?= htmlspecialchars($existing_data['lainnyabentukdada'] ?? '') ?></textarea>
    </div>
</div>
    </div>

</div><div class="row mb-3">
    <label class="col-sm-10 col-form-label text-primary">
        <strong>b. Cardiovaskuler (B2: Bleeding)</strong>
    </label>
</div>   

<div class="row mb-2">
    <div class="col-sm-2">
        <strong>Nyeri Dada</strong>
    </div>    

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="nyeridada" value="ya" <?= ($existing_data['nyeridada'] ?? '') === 'ya' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Ya</label>
        </div>
    </div>

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="nyeridada" value="tidak" <?= ($existing_data['nyeridada'] ?? '') === 'tidak' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Tidak</label>
        </div>
    </div>
</div>

<div class="row mb-2">
    <div class="col-sm-2">
        <strong>Pusing</strong>
    </div> 

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="pusing" value="ya" <?= ($existing_data['pusing'] ?? '') === 'ya' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Ya</label>
        </div>
    </div>

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="pusing" value="tidak" <?= ($existing_data['pusing'] ?? '') === 'tidak' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Tidak</label>
        </div>
    </div>
</div> 

<div class="row mb-2">
    <div class="col-sm-2">
        <strong>Sakit Kepala</strong>
    </div> 

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="sakitkepala" value="ya" <?= ($existing_data['sakitkepala'] ?? '') === 'ya' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Ya</label>
        </div>
    </div>

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="sakitkepala" value="tidak" <?= ($existing_data['sakitkepala'] ?? '') === 'tidak' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Tidak</label>
        </div>
    </div>
</div>

<div class="row mb-2">
    <div class="col-sm-2">
        <strong>Palpitasi</strong>
    </div> 

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="palpitasi" value="ya" <?= ($existing_data['palpitasi'] ?? '') === 'ya' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Ya</label>
        </div>
    </div>

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="palpitasi" value="tidak" <?= ($existing_data['palpitasi'] ?? '') === 'tidak' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Tidak</label>
        </div>
    </div>
</div>  

<div class="row mb-2">
    <div class="col-sm-2">
        <strong>Clubbing Finger</strong>
    </div> 

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="clubbingfinger" value="ya" <?= ($existing_data['clubbingfinger'] ?? '') === 'ya' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Ya</label>
        </div>
    </div>

    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="clubbingfinger" value="tidak" <?= ($existing_data['clubbingfinger'] ?? '') === 'tidak' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Tidak</label>
        </div>
    </div>
</div>

<div class="row mb-2">
    <div class="col-sm-2">
        <strong>Suara Jantung</strong>
    </div>
    <div class="col-sm-2">
        <strong>Normal</strong>
    </div>
    <div class="col-sm-4">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="suarajantung" value="normal" <?= ($existing_data['suarajantung'] ?? '') === 'normal' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Normal (S1/S2 Tunggal)</label>
        </div>
    </div>
</div> 

<div class="row mb-2">
    <div class="col-sm-2"></div>
    <div class="col-sm-2">
        <strong>Kelainan</strong>
    </div>
    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="suarajantung" value="kelainans3" <?= ($existing_data['suarajantung'] ?? '') === 'kelainans3' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">S3</label>
        </div>
    </div>
    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="suarajantung" value="kelainans4" <?= ($existing_data['suarajantung'] ?? '') === 'kelainans4' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">S4</label>
        </div>
    </div>
    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="suarajantung" value="kelainanmurmur" <?= ($existing_data['suarajantung'] ?? '') === 'kelainanmurmur' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Mur-mur</label>
        </div>
    </div>  
    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="suarajantung" value="gallop" <?= ($existing_data['suarajantung'] ?? '') === 'gallop' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Gallop</label>
        </div>
    </div>    
</div> 

<div class="row mb-2">
    <div class="col-sm-2">
        <strong>Edema</strong>
    </div>
    <div class="col-sm-10">
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="edema" value="palpebra" <?= ($existing_data['edema'] ?? '') === 'palpebra' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Palpebra</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="edema" value="anasarka" <?= ($existing_data['edema'] ?? '') === 'anasarka' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Anasarka</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="edema" value="ekstremitasatas" <?= ($existing_data['edema'] ?? '') === 'ekstremitasatas' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Ekstremitas Atas</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="edema" value="ekstremitasbawah" <?= ($existing_data['edema'] ?? '') === 'ekstremitasbawah' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Ekstremitas Bawah</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="edema" value="ascites" <?= ($existing_data['edema'] ?? '') === 'ascites' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Ascites</label>
        </div>

       <div class="row mt-2">
    <div class="col-sm-12">
        <label><strong>Lainnya</strong></label>
        <textarea name="lainnyaedema" 
                  class="form-control" 
                  rows="1" 
                  style="overflow:hidden; resize:none;" 
                  oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" 
                  <?= $ro ?>><?= htmlspecialchars($existing_data['lainnyaedema'] ?? '') ?></textarea>
    </div>
</div>

        
    </div> 
</div>
                                                      
                    <div class="row mb-3">
    <div class="col-sm-12">
        <h5 class="text-primary"><strong>c. Persyarafan (B3: Brain)</strong></h5>
    </div>
</div>   

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Glasgow Coma Scale (GCS)</strong></label>
    <div class="col-sm-10">
        <div class="row">
            <div class="col-md-4 d-flex align-items-center">
                <label class="me-2"><strong>E</strong></label>
                <input type="text" class="form-control" name="e" value="<?= htmlspecialchars($existing_data['e'] ?? '') ?>" <?= $ro ?>>
            </div>
            <div class="col-md-4 d-flex align-items-center">
                <label class="me-2"><strong>M</strong></label>
                <input type="text" class="form-control" name="m" value="<?= htmlspecialchars($existing_data['m'] ?? '') ?>" <?= $ro ?>>
            </div>
            <div class="col-md-4 d-flex align-items-center">
                <label class="me-2"><strong>V</strong></label>
                <input type="text" class="form-control" name="v" value="<?= htmlspecialchars($existing_data['v'] ?? '') ?>" <?= $ro ?>>
            </div>
        </div>
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Tingkat Kesadaran</strong></label>
    <div class="col-sm-9">
        <?php
        $kesadaran_options = ['Kompos Mentis', 'Apatis', 'Somnolent', 'Stupor / Suppor', 'Semikoma', 'Koma'];
        $kesadaran_values  = ['Kompos Mentis', 'Apatis', 'Somnolent', 'Stupor', 'Semikoma', 'Koma'];
        
        // Asumsi $existing_data berisi nilai tunggal yang tersimpan
        $selected_val = val('kesadaran', $existing_data); 

        foreach ($kesadaran_options as $i => $label):
            $val = $kesadaran_values[$i];
        ?>
            <div class="form-check-inline">
                <input class="form-check-input" type="radio" 
                    name="kesadaran" 
                    id="kesadaran_<?= $i ?>" 
                    value="<?= $val ?>" 
                    <?= ($val === $selected_val) ? 'checked' : '' ?> 
                    <?= $ro_check ?>>
                <label class="form-check-label" for="kesadaran_<?= $i ?>"><?= $label ?></label>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Kejang</strong></label>
    <div class="col-sm-10">
        <select class="form-select" name="kejang" <?= $ro_disabled ?>>
            <option value="">Pilih</option>
            <option value="Ya" <?= ($existing_data['kejang'] ?? '') === 'Ya' ? 'selected' : '' ?>>Ya</option>
            <option value="Tidak Ada" <?= ($existing_data['kejang'] ?? '') === 'Tidak Ada' ? 'selected' : '' ?>>Tidak Ada</option>
        </select> 
    </div>
</div>        

<div class="row mb-2">
    <div class="col-sm-2"><strong>Kepala</strong></div> 
    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="kepala" value="mesosepal" <?= ($existing_data['kepala'] ?? '') === 'mesosepal' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Mesosepal</label>
        </div>
    </div>
    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="kepala" value="asimetris" <?= ($existing_data['kepala'] ?? '') === 'asimetris' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Asimetris</label>
        </div>
    </div>
    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="kepala" value="hematoma" <?= ($existing_data['kepala'] ?? '') === 'hematoma' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Hematoma</label>
        </div>
    </div>
</div>

<div class="row mb-3">
    <div class="col-sm-2"><strong>Wajah</strong></div> 
    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="wajah" value="simetris" <?= ($existing_data['wajah'] ?? '') === 'simetris' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Simetris</label>
        </div>
    </div>
    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="wajah" value="asimetris" <?= ($existing_data['wajah'] ?? '') === 'asimetris' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Asimetris</label>
        </div>
    </div>
    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="wajah" value="bellpalsy" <?= ($existing_data['wajah'] ?? '') === 'bellpalsy' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Bell Palsy</label>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="wajah" value="Kelainan Kongenital" <?= ($existing_data['wajah'] ?? '') === 'Kelainan Kongenital' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Kelainan Kongenital</label>
        </div>
    </div>
</div>        

<div class="row mb-2">
    <div class="col-sm-2"><strong>Mata</strong></div>
    <div class="col-sm-2"><strong>Sklera</strong></div>
    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="sklera" value="putih" <?= ($existing_data['sklera'] ?? '') === 'putih' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Putih</label>
        </div>
    </div>
    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="sklera" value="ikterus" <?= ($existing_data['sklera'] ?? '') === 'ikterus' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Ikterus</label>
        </div>
    </div>
    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="sklera" value="merah" <?= ($existing_data['sklera'] ?? '') === 'merah' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Merah</label>
        </div>
    </div> 
    <div class="col-sm-2 offset-sm-2 offset-md-0">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="sklera" value="perdarahan" <?= ($existing_data['sklera'] ?? '') === 'perdarahan' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Perdarahan</label>
        </div>
    </div>    
</div>

<div class="row mb-2">
    <div class="col-sm-2"></div>
    <div class="col-sm-2"><strong>Konjungtiva</strong></div>
    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="konjungtiva" value="anemis" <?= ($existing_data['konjungtiva'] ?? '') === 'anemis' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Putih</label>
        </div>
    </div>
    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="konjungtiva" value="merahmuda" <?= ($existing_data['konjungtiva'] ?? '') === 'merahmuda' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Merah Muda</label>
        </div>
    </div>
</div>
<div class="row mb-3 align-items-center">
    <label class="col-sm-2 col-form-label"><strong>Pupil</strong></label>
    <div class="col-sm-3">
        <textarea name="pupil" 
                  class="form-control" 
                  rows="1" 
                  style="overflow:hidden; resize:none;" 
                  oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" 
                  <?= $ro ?>><?= htmlspecialchars($existing_data['pupil'] ?? '') ?></textarea>
    </div>    
    <label class="col-sm-2 col-form-label text-sm-end"><strong>Ukuran</strong></label>
    <div class="col-sm-3">
        <textarea name="ukuran_pupil" 
                  class="form-control" 
                  rows="1" 
                  style="overflow:hidden; resize:none;" 
                  oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" 
                  <?= $ro ?>><?= htmlspecialchars($existing_data['ukuran_pupil'] ?? '') ?></textarea>
    </div>   
</div>

<div class="row mb-3">
    <div class="col-sm-2"><strong>Leher</strong></div> 
    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="leher" value="kesulitanmenelan" <?= ($existing_data['leher'] ?? '') === 'kesulitanmenelan' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Kesulitan Menelan</label>
        </div>
    </div>
    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="leher" value="suaraparau" <?= ($existing_data['leher'] ?? '') === 'suaraparau' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Suara Parau</label>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="leher" value="pembesarantiroid" <?= ($existing_data['leher'] ?? '') === 'pembesarantiroid' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Pembesaran Tiroid</label>
        </div>
    </div>
    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="leher" value="jvp" <?= ($existing_data['leher'] ?? '') === 'jvp' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">JVP</label>
        </div>
    </div>
</div>

<div class="row mb-2">
    <div class="col-sm-2"><strong>Refleks Tendon Normal</strong></div> 
    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="reflekstendonnormal" value="bisep" <?= ($existing_data['reflekstendonnormal'] ?? '') === 'bisep' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Bisep</label>
        </div>
    </div>
    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="reflekstendonnormal" value="trisep" <?= ($existing_data['reflekstendonnormal'] ?? '') === 'trisep' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Trisep</label>
        </div>
    </div>
    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="reflekstendonnormal" value="brakhialis" <?= ($existing_data['reflekstendonnormal'] ?? '') === 'brakhialis' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Brakhialis</label>
        </div>
    </div>
    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="reflekstendonnormal" value="patella" <?= ($existing_data['reflekstendonnormal'] ?? '') === 'patella' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Patella</label>
        </div>
    </div>
    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="reflekstendonnormal" value="achilles" <?= ($existing_data['reflekstendonnormal'] ?? '') === 'achilles' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Achilles</label>
        </div>
    </div>
</div>

<div class="row mb-3">
    <div class="col-sm-2"><strong>Refleks Tidak Normal
</strong></div> 
    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="reflekstidaknormal" value="kakukuduk" <?= ($existing_data['reflekstidaknormal'] ?? '') === 'kakukuduk' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Kaku Kuduk</label>
        </div>
    </div>
    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="reflekstidaknormal" value="babinski" <?= ($existing_data['reflekstidaknormal'] ?? '') === 'babinski' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Babinski</label>
        </div>
    </div>
    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="reflekstidaknormal" value="brudzinski" <?= ($existing_data['reflekstidaknormal'] ?? '') === 'brudzinski' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Brudzinski</label>
        </div>
    </div>
    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="reflekstidaknormal" value="kernigsign" <?= ($existing_data['reflekstidaknormal'] ?? '') === 'kernigsign' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Kernig Sign</label>
        </div>
    </div>
</div>

<div class="row mb-1">
    <div class="col-sm-12">
        <h6><strong>Persepsi Sensori</strong></h6>
    </div>
</div>

<div class="row mb-2">
    <div class="col-sm-2"><strong>Pendengaran</strong></div>
    <div class="col-sm-2"><strong>Kiri</strong></div>
    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="pendengaran_kiri" value="baik" <?= ($existing_data['pendengaran_kiri'] ?? '') === 'baik' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Baik</label>
        </div>
    </div>
    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="pendengaran_kiri" value="tidakbaik" <?= ($existing_data['pendengaran_kiri'] ?? '') === 'tidakbaik' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Tidak Baik</label>
        </div>
    </div> 
</div>
<div class="row mb-3">
    <div class="col-sm-2"></div>
    <div class="col-sm-2"><strong>Kanan</strong></div>
    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="pendengaran_kanan" value="baik" <?= ($existing_data['pendengaran_kanan'] ?? '') === 'baik' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Baik</label>
        </div>
    </div>
    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="pendengaran_kanan" value="tidakbaik" <?= ($existing_data['pendengaran_kanan'] ?? '') === 'tidakbaik' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Tidak Baik</label>
        </div>
    </div> 
</div>

<div class="row mb-2">
    <div class="col-sm-2"><strong>Penciuman</strong></div> 
    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="penciuman" value="baik" <?= ($existing_data['penciuman'] ?? '') === 'baik' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Baik</label>
        </div>
    </div>
    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="penciuman" value="tidakbaik" <?= ($existing_data['penciuman'] ?? '') === 'tidakbaik' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Tidak Baik</label>
        </div>
    </div>
</div>

<div class="row mb-3">
    <div class="col-sm-2"><strong>Pengecapan</strong></div> 
    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="pengecapan" value="baik" <?= ($existing_data['pengecapan'] ?? '') === 'baik' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Baik</label>
        </div>
    </div>
    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="pengecapan" value="tidakbaik" <?= ($existing_data['pengecapan'] ?? '') === 'tidakbaik' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Tidak Baik</label>
        </div>
    </div>
</div>    

<div class="row mb-2">
    <label class="col-sm-2"><strong>Penglihatan</strong></label>
    <div class="col-sm-2"><strong>Kiri</strong></div>
    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="penglihatan_kiri" value="baik" <?= ($existing_data['penglihatan_kiri'] ?? '') === 'baik' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Baik</label>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="penglihatan_kiri" value="tidakbaik" <?= ($existing_data['penglihatan_kiri'] ?? '') === 'tidakbaik' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Tidak Baik</label>
        </div>
    </div>
</div>
<div class="row mb-3">
    <div class="col-sm-2"></div>
    <div class="col-sm-2"><strong>Kanan</strong></div>
    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="penglihatan_kanan" value="baik" <?= ($existing_data['penglihatan_kanan'] ?? '') === 'baik' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Baik</label>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="penglihatan_kanan" value="tidakbaik" <?= ($existing_data['penglihatan_kanan'] ?? '') === 'tidakbaik' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Tidak Baik</label>
        </div>
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Alat Bantu</strong></label>
    <div class="col-sm-10">
        <textarea name="alatbantu" class="form-control" rows="3" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars($existing_data['alatbantu'] ?? '') ?></textarea>
    </div>
</div> 

<div class="row mb-2">
    <div class="col-sm-2"><strong>Perabaan</strong></div>
    <div class="col-sm-2"><strong>Panas</strong></div>
    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="perabaan_panas" value="baik" <?= ($existing_data['perabaan_panas'] ?? '') === 'baik' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Baik</label>
        </div>
    </div>
    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="perabaan_panas" value="tidak" <?= ($existing_data['perabaan_panas'] ?? '') === 'tidak' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Tidak</label>
        </div>
    </div> 
</div>

<div class="row mb-2">
    <div class="col-sm-2"></div>
    <div class="col-sm-2"><strong>Dingin</strong></div>
    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="perabaan_dingin" value="baik" <?= ($existing_data['perabaan_dingin'] ?? '') === 'baik' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Baik</label>
        </div>
    </div>
    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="perabaan_dingin" value="tidak" <?= ($existing_data['perabaan_dingin'] ?? '') === 'tidak' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Tidak</label>
        </div>
    </div>
</div>

<div class="row mb-3">
    <div class="col-sm-2"></div>
    <div class="col-sm-2"><strong>Tekan</strong></div>
    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="perabaan_tekan" value="baik" <?= ($existing_data['perabaan_tekan'] ?? '') === 'baik' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Baik</label>
        </div>
    </div>
    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="perabaan_tekan" value="tidak" <?= ($existing_data['perabaan_tekan'] ?? '') === 'tidak' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Tidak</label>
        </div>
    </div>
</div>
               <div class="row mb-3">
    <div class="col-sm-12">
        <h5 class="text-primary"><strong>d. Perkemihan-Eliminasi Urin (B4: Bladder)</strong></h5>
    </div>
</div>   

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Produksi Urine</strong></label>
    <div class="col-sm-10">
        <div class="input-group">
            <input type="text" class="form-control" name="produksiurine" value="<?= htmlspecialchars($existing_data['produksiurine'] ?? '') ?>" <?= $ro ?>>
            <span class="input-group-text">ml</span>
        </div>
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Frekuensi</strong></label>
    <div class="col-sm-10">
        <div class="input-group">
            <input type="text" class="form-control" name="frekuensi" value="<?= htmlspecialchars($existing_data['frekuensi'] ?? '') ?>" <?= $ro ?>>
            <span class="input-group-text">/hari</span>
        </div>
    </div>
</div>  

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Warna</strong></label>
    <div class="col-sm-10">
        <input type="text" class="form-control" name="warna" value="<?= htmlspecialchars($existing_data['warna'] ?? '') ?>" <?= $ro ?>>
    </div>
</div>  

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Bau</strong></label>
    <div class="col-sm-10">
        <input type="text" class="form-control" name="bau" value="<?= htmlspecialchars($existing_data['bau'] ?? '') ?>" <?= $ro ?>>
    </div>
</div>            

<div class="row mb-3">
    <div class="col-sm-2">
        <strong>Douwer Cateter</strong>
    </div> 
    <div class="col-sm-10">
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="douwercateter" value="ya" <?= ($existing_data['douwercateter'] ?? '') === 'ya' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Ya</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="douwercateter" value="tidak" <?= ($existing_data['douwercateter'] ?? '') === 'tidak' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Tidak</label>
        </div>

        <div class="mt-2"> 
            <label class="me-2"><strong>Hari Ke</strong></label>
            <input type="text" class="form-control" name="harike_cateter" value="<?= htmlspecialchars($existing_data['harike_cateter'] ?? '') ?>" <?= $ro ?>>
        </div>
    </div>
</div>
       
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Spolling Blass dengan cairan NaCL 0,9%</strong></label>
    <div class="col-sm-10">
        <select class="form-select" name="spollingblass" <?= $ro_disabled ?>>
            <option value="">Pilih</option>
            <option value="Ya" <?= ($existing_data['spollingblass'] ?? '') === 'Ya' ? 'selected' : '' ?>>Ya</option>
            <option value="Tidak" <?= ($existing_data['spollingblass'] ?? '') === 'Tidak' ? 'selected' : '' ?>>Tidak</option>
        </select>
    </div>
</div>   

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Kelainan dalam Urine (Sebutkan)</strong></label>
    <div class="col-sm-10">
        <textarea name="kelainandalamurine" class="form-control" rows="3" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars($existing_data['kelainandalamurine'] ?? '') ?></textarea>
    </div>
</div>

<div class="row mb-3">
                        <label class="col-sm-10 col-form-label text-primary">
                            <strong>e. Pencernaan-Eliminasi Alvi (B5: Bowel)</strong>
                    </div>  
     
<div class="row mb-2">
    <div class="col-sm-2">
        <strong>Mulut dan Tenggorokan</strong>
    </div>
    <div class="col-sm-10">
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="mulutdantenggorokan[]" value="mukosakering" <?= in_array('mukosakering', $existing_data['mulutdantenggorokan'] ?? []) ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Mukosa Kering</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="mulutdantenggorokan[]" value="merahmudah" <?= in_array('merahmudah', $existing_data['mulutdantenggorokan'] ?? []) ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Merah Muda</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="mulutdantenggorokan[]" value="kesulitanmenelan" <?= in_array('kesulitanmenelan', $existing_data['mulutdantenggorokan'] ?? []) ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Kesulitan Menelan</label>
        </div>
    </div>
</div>

<div class="row mb-2">
    <div class="col-sm-2">
        <strong>Abdomen</strong>
    </div>
    <div class="col-sm-10">
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="abdomen" value="distensi" <?= ($existing_data['abdomen'] ?? '') === 'distensi' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Distensi</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="abdomen" value="nyeritekan" <?= ($existing_data['abdomen'] ?? '') === 'nyeritekan' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Nyeri Tekan</label>
        </div>
    </div>
</div>

<div class="row mb-2">
    <div class="col-sm-2">
        <strong>Rektum</strong>
    </div>
    <div class="col-sm-10">
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="rektum" value="adakelainan" <?= ($existing_data['rektum'] ?? '') === 'adakelainan' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Ada Kelainan</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="rektum" value="tidakadakelainan" <?= ($existing_data['rektum'] ?? '') === 'tidakadakelainan' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Tidak Ada Kelainan</label>
        </div>
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Anjuran Puasa</strong></label>
    <div class="col-sm-4">
        <select class="form-select" name="anjuranpuasa" <?= $ro_disabled ?>>
            <option value="">Pilih</option>
            <option value="ya" <?= ($existing_data['anjuranpuasa'] ?? '') === 'ya' ? 'selected' : '' ?>>Ya</option>
            <option value="Tidak" <?= ($existing_data['anjuranpuasa'] ?? '') === 'Tidak ' ? 'selected' : '' ?>>Tidak </option>
        </select>  
    </div>
                                    
    <label class="col-sm-2 col-form-label text-end"><strong>Selama</strong></label>
    <div class="col-sm-4">
        <input type="text" class="form-control" name="selama" value="<?= htmlspecialchars($existing_data['selama'] ?? '') ?>" <?= $ro ?>>
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Diet yang diberikan</strong></label>
    <div class="col-sm-10">
        <input type="text" class="form-control" name="dietyangdiberikan" value="<?= htmlspecialchars($existing_data['dietyangdiberikan'] ?? '') ?>" <?= $ro ?>>
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Terpasang NGT</strong></label>
    <div class="col-sm-4">
        <select class="form-select" name="terpasangngt" <?= $ro_disabled ?>>
            <option value="">Pilih</option>
            <option value="ya" <?= ($existing_data['terpasangngt'] ?? '') === 'ya' ? 'selected' : '' ?>>Ya</option>
            <option value="Tidak" <?= ($existing_data['terpasangngt'] ?? '') === 'Tidak' ? 'selected' : '' ?>>Tidak</option>
        </select>  
    </div>
                                    
    <label class="col-sm-2 col-form-label text-end"><strong>Hari Ke</strong></label>
    <div class="col-sm-4">
        <input type="number" class="form-control" name="harike_ngt" value="<?= htmlspecialchars($existing_data['harike_ngt'] ?? '') ?>" <?= $ro ?>>
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Kelainan Saluran Cerna</strong></label>
    <div class="col-sm-10">
        <input type="text" class="form-control" name="kelainansalurancerna" value="<?= htmlspecialchars($existing_data['kelainansalurancerna'] ?? '') ?>" <?= $ro ?>>
    </div>
</div>
           <div class="row mb-3">
    <div class="col-sm-12">
        <h5 class="text-primary"><strong>f. Tulang-Otot-Integumen (B6: Bone)</strong></h5>
    </div>
</div>  
                    
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Kelainan pada Tulang</strong></label>
    <div class="col-sm-10">
        <textarea name="kelainanpadatulang" class="form-control" rows="3" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars($existing_data['kelainanpadatulang'] ?? '') ?></textarea>
    </div>
</div> 

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Kekuatan Otot</strong></label>
    <div class="col-sm-10">
        <textarea name="kekuatanotot" class="form-control" rows="3" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars($existing_data['kekuatanotot'] ?? '') ?></textarea>
    </div>
</div> 

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Hemiparese</strong></label>
    <div class="col-sm-10">
        <select class="form-select" name="hemiparese" <?= $ro_disabled ?>>
            <option value="">Pilih</option>
            <option value="Ada" <?= ($existing_data['hemiparese'] ?? '') === 'Ada' ? 'selected' : '' ?>>Ada</option>
            <option value="Tidak Ada" <?= ($existing_data['hemiparese'] ?? '') === 'Tidak Ada' ? 'selected' : '' ?>>Tidak Ada</option>
        </select>
    </div>
</div> 

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Tetraparese</strong></label>
    <div class="col-sm-10">
        <select class="form-select" name="tetraparese" <?= $ro_disabled ?>>
            <option value="">Pilih</option>
            <option value="Ada" <?= ($existing_data['tetraparese'] ?? '') === 'Ada' ? 'selected' : '' ?>>Ada</option>
            <option value="Tidak Ada" <?= ($existing_data['tetraparese'] ?? '') === 'Tidak Ada' ? 'selected' : '' ?>>Tidak Ada</option>
        </select>
    </div>
</div> 

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>ROM</strong></label>
    <div class="col-sm-10">
        <textarea name="rom" class="form-control" rows="3" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars($existing_data['rom'] ?? '') ?></textarea>
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Lainnya</strong></label>
    <div class="col-sm-10">
        <textarea name="lainnya" class="form-control" rows="3" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars($existing_data['lainnya'] ?? '') ?></textarea>
    </div>
</div>

<div class="row mb-2">
    <div class="col-sm-2"><strong>Ekstremitas</strong></div>
    <div class="col-sm-2"><strong>Atas</strong></div>
    <div class="col-sm-8">
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="ekstremitasatas" value="tidakadakelainan" <?= ($existing_data['ekstremitasatas'] ?? '') === 'tidakadakelainan' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Tidak Ada Kelainan</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="ekstremitasatas" value="peradangan" <?= ($existing_data['ekstremitasatas'] ?? '') === 'peradangan' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Peradangan</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="ekstremitasatas" value="patahtulang" <?= ($existing_data['ekstremitasatas'] ?? '') === 'patahtulang' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Patah Tulang</label>
        </div>
    </div>
</div>

<div class="row mb-3">
    <div class="col-sm-2"></div>
    <div class="col-sm-2"><strong>Bawah</strong></div>
    <div class="col-sm-8">
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="ekstremitasbawah" value="tidakadakelainan" <?= ($existing_data['ekstremitasbawah'] ?? '') === 'tidakadakelainan' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Tidak Ada Kelainan</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="ekstremitasbawah" value="peradangan" <?= ($existing_data['ekstremitasbawah'] ?? '') === 'peradangan' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Peradangan</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="ekstremitasbawah" value="patahtulang" <?= ($existing_data['ekstremitasbawah'] ?? '') === 'patahtulang' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Patah Tulang</label>
        </div>
    </div>
</div>

<div class="row mb-3">
    <div class="col-sm-2"><strong>Tulang Belakang</strong></div>
    <div class="col-sm-10">
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="tulangbelakang" value="kifosis" <?= ($existing_data['tulangbelakang'] ?? '') === 'kifosis' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Kifosis</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="tulangbelakang" value="lordosis" <?= ($existing_data['tulangbelakang'] ?? '') === 'lordosis' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Lordosis</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="tulangbelakang" value="skoliosis" <?= ($existing_data['tulangbelakang'] ?? '') === 'skoliosis' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Skoliosis</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="tulangbelakang" value="nyeri" <?= ($existing_data['tulangbelakang'] ?? '') === 'nyeri' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Nyeri</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="tulangbelakang" value="tidakadakelainan" <?= ($existing_data['tulangbelakang'] ?? '') === 'tidakadakelainan' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Tidak Ada Kelainan</label>
        </div>
    </div>
</div>
                    
<div class="row mb-2">
    <div class="col-sm-2"><strong>Kulit</strong></div>
    <div class="col-sm-2"><strong>Warna Kulit</strong></div>
    <div class="col-sm-8">
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="warnakulit" value="ikterik" <?= ($existing_data['warnakulit'] ?? '') === 'ikterik' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Ikterik</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="warnakulit" value="pigmentasi" <?= ($existing_data['warnakulit'] ?? '') === 'pigmentasi' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Pigmentasi</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="warnakulit" value="sianotik" <?= ($existing_data['warnakulit'] ?? '') === 'sianotik' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Sianotik</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="warnakulit" value="pucat" <?= ($existing_data['warnakulit'] ?? '') === 'pucat' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Pucat</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="warnakulit" value="kemerahan" <?= ($existing_data['warnakulit'] ?? '') === 'kemerahan' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Kemerahan</label>
        </div>
    </div>
</div>

<div class="row mb-3">
    <div class="col-sm-2"></div>
    <div class="col-sm-2"><strong>Akral</strong></div>
    <div class="col-sm-8">
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="akral" value="hangat" <?= ($existing_data['akral'] ?? '') === 'hangat' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Hangat</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="akral" value="panas" <?= ($existing_data['akral'] ?? '') === 'panas' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Panas</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="akral" value="dinginkering" <?= ($existing_data['akral'] ?? '') === 'dinginkering' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Dingin Kering</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="akral" value="dinginbasah" <?= ($existing_data['akral'] ?? '') === 'dinginbasah' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Dingin Basah</label>
        </div>
    </div>
</div>
    
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Turgor</strong></label>
    <div class="col-sm-10">
        <div class="input-group">
            <input type="text" class="form-control" name="turgor" value="<?= htmlspecialchars($existing_data['turgor'] ?? '') ?>" <?= $ro ?>>
            <span class="input-group-text">detik</span>
        </div>
    </div>
</div>
            
<div class="row mb-3">
    <div class="col-sm-12">
        <h5 class="text-primary"><strong>g. Sistem Endokrin</strong></h5>
    </div>
</div> 
                    
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Terapi Hormon</strong></label>
    <div class="col-sm-10">
        <textarea name="terapihormon" class="form-control" rows="3" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars($existing_data['terapihormon'] ?? '') ?></textarea>
    </div>
</div>

<div class="row mb-3">
    <div class="col-sm-12">
        <h5 class="text-primary"><strong>h. Sistem Reproduksi</strong></h5>
    </div>
</div>   

<div class="row mb-2">
    <div class="col-sm-2"><strong>Sistem Reproduksi</strong></div>    
    <div class="col-sm-10">
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="sistemreproduksi" value="lakilaki" <?= ($existing_data['sistemreproduksi'] ?? '') === 'lakilaki' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Laki-laki</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="sistemreproduksi" value="perempuan" <?= ($existing_data['sistemreproduksi'] ?? '') === 'perempuan' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Perempuan</label>
        </div>
    </div>
</div>

<div class="row mb-2">
    <div class="col-sm-2"><strong>Kelainan Bentuk</strong></div>    
    <div class="col-sm-10">
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="kelainanbentuk" value="normal" <?= ($existing_data['kelainanbentuk'] ?? '') === 'normal' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Normal</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="kelainanbentuk" value="tidaknormal" <?= ($existing_data['kelainanbentuk'] ?? '') === 'tidaknormal' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Tidak Normal</label>
        </div>
    </div>
</div>
                        
<div class="row mb-3">
    <div class="col-sm-2"><strong>Kebersihan</strong></div>    
    <div class="col-sm-10">
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="kebersihan" value="bersih" <?= ($existing_data['kebersihan'] ?? '') === 'bersih' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Bersih</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="kebersihan" value="kotor" <?= ($existing_data['kebersihan'] ?? '') === 'kotor' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Kotor</label>
        </div>
    </div>
</div>

<div class="row mb-3">
    <div class="col-sm-12">
        <h5 class="text-primary"><strong>i. Pola Aktivitas</strong></h5>
    </div>
</div> 

<div class="row mb-2">
    <div class="col-sm-12"><span class="badge bg-secondary">Makan</span></div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Frekuensi</strong></label>
    <div class="col-sm-10">
        <textarea name="frekuensi" class="form-control" rows="3" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars($existing_data['frekuensi'] ?? '') ?></textarea>
    </div>
</div> 
                    
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Jenis Menu</strong></label>
    <div class="col-sm-10">
       <textarea name="jenismenu" class="form-control" rows="3" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars($existing_data['jenismenu'] ?? '') ?></textarea>
    </div>
</div> 

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Pantangan</strong></label>
    <div class="col-sm-10">
        <textarea name="pantangan" class="form-control" rows="3" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars($existing_data['pantangan'] ?? '') ?></textarea>
    </div>
</div> 

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Alergi</strong></label>
    <div class="col-sm-10">
        <textarea name="alergi" class="form-control" rows="3" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars($existing_data['alergi'] ?? '') ?></textarea>
    </div>
</div>
                    
<div class="row mb-2 mt-4">
    <div class="col-sm-12"><span class="badge bg-secondary">Minum</span></div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Frekuensi</strong></label>
    <div class="col-sm-10">
        <textarea name="minumfrekuensi" class="form-control" rows="3" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars($existing_data['minumfrekuensi'] ?? '') ?></textarea>
    </div>
</div> 
                    
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Jenis Menu</strong></label>
    <div class="col-sm-10">
        <textarea name="minumjenismenu" class="form-control" rows="3" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars($existing_data['minumjenismenu'] ?? '') ?></textarea>
    </div>
</div> 

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Pantangan</strong></label>
    <div class="col-sm-10">
        <textarea name="minumpantangan" class="form-control" rows="3" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars($existing_data['minumpantangan'] ?? '') ?></textarea>
    </div>
</div> 

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Alergi</strong></label>
    <div class="col-sm-10">
       <textarea name="minumalergi" class="form-control" rows="3" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars($existing_data['minumalergi'] ?? '') ?></textarea>
    </div>
</div>

<div class="row mb-2 mt-4">
    <div class="col-sm-12"><span class="badge bg-secondary">Kebersihan Diri</span></div>
</div>   

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Mandi</strong></label>
    <div class="col-sm-10">
        <textarea name="mandi" class="form-control" rows="3" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars($existing_data['mandi'] ?? '') ?></textarea>
    </div>
</div> 
                    
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Keramas</strong></label>
    <div class="col-sm-10">
        <textarea name="keramas" class="form-control" rows="3" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars($existing_data['keramas'] ?? '') ?></textarea>
    </div>
</div> 

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Sikat Gigi</strong></label>
    <div class="col-sm-10">
        <textarea name="sikatgigi" class="form-control" rows="3" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars($existing_data['sikatgigi'] ?? '') ?></textarea>
    </div>
</div> 

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Memotong Kuku</strong></label>
    <div class="col-sm-10">
        <textarea name="memotongkuku" class="form-control" rows="3" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars($existing_data['memotongkuku'] ?? '') ?></textarea>
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Ganti Pakaian</strong></label>
    <div class="col-sm-10">
        <textarea name="gantipakaian" class="form-control" rows="3" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars($existing_data['gantipakaian'] ?? '') ?></textarea>
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Masalah Lain</strong></label>
    <div class="col-sm-10">
        <textarea name="masalahlainkebersihandiri" class="form-control" rows="3" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars($existing_data['masalahlainkebersihandiri'] ?? '') ?></textarea>
    </div>
</div>

<div class="row mb-3">
    <div class="col-sm-12">
        <h5 class="text-primary"><strong>j. Social Interaction (Interaksi Sosial)</strong></h5>
    </div>
</div>   
                    
<div class="row mb-2">
    <div class="col-sm-2"><strong>Dukungan Keluarga</strong></div>    
    <div class="col-sm-10">
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="dukungankeluarga" value="aktif" <?= ($existing_data['dukungankeluarga'] ?? '') === 'aktif' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Aktif</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="dukungankeluarga" value="kurang" <?= ($existing_data['dukungankeluarga'] ?? '') === 'kurang' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Kurang</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="dukungankeluarga" value="tidakada" <?= ($existing_data['dukungankeluarga'] ?? '') === 'tidakada' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Tidak Ada</label>
        </div>
    </div>
</div>

<div class="row mb-3">
    <div class="col-sm-2"><strong>Dukungan Teman/Masyarakat</strong></div>    
    <div class="col-sm-10">
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="dukungankel" value="aktif" <?= ($existing_data['dukungankel'] ?? '') === 'aktif' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Aktif</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="dukungankel" value="kurang" <?= ($existing_data['dukungankel'] ?? '') === 'kurang' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Kurang</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="dukungankel" value="tidakada" <?= ($existing_data['dukungankel'] ?? '') === 'tidakada' ? 'checked' : '' ?> <?= $ro_disabled ?>>
            <label class="form-check-label">Tidak Ada</label>
        </div>
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Hubungan dengan Keluarga/Teman Sejawat</strong></label>
    <div class="col-sm-10">
        <textarea name="hubunganklien" class="form-control" rows="3" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars($existing_data['hubunganklien'] ?? '') ?></textarea>
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Yang Menunggu Selama Perawatan</strong></label>
    <div class="col-sm-10">
        <textarea name="menungguklien" class="form-control" rows="3" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars($existing_data['menungguklien'] ?? '') ?></textarea>
    </div>
</div>
                     
 <!-- TOMBOL SIMPAN (mahasiswa only) -->
                    <?php if (!$is_dosen): ?>

                        <div class="row mb-3">
                            <div class="col-sm-12 d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">Simpan Data</button>
                            </div>
                        </div>

                    <?php endif; ?>

                </div>
            </div>
    </div>
</div>
        
               


                <?php include dirname(__DIR__, 2) . '/partials/footer_form.php'; ?>

</section>
</main>

                        


