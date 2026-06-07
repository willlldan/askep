<?php
$form_id       = 22;
$section_name  = 'pengkajian';
$section_label = 'Pengkajian';
include dirname(__DIR__, 2) . '/partials/init_section.php';


// =============================================
// HANDLE POST - MAHASISWA SIMPAN DATA
// =============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $level === 'Mahasiswa') {

    if (isLocked($submission)) {
        redirectWithMessage($_SERVER['REQUEST_URI'], 'error', 'Data tidak dapat diubah karena sedang dalam proses review.');
    }


  $data = [
    // A. IDENTITAS PASIEN
    'norekammedis'             => $_POST['norekammedis']             ?? '',
    'nama'                     => $_POST['nama']                     ?? '',
    'umur'                     => $_POST['umur']                     ?? '',
    'agama'                    => $_POST['agama']                    ?? '',
    'pekerjaan'                => $_POST['pekerjaan']                ?? '',
    'alamat'                   => $_POST['alamat']                   ?? '',
    'diagnosamedis'            => $_POST['diagnosamedis']            ?? '',
    'jeniskelamin'             => $_POST['jeniskelamin']             ?? '',
    'pendidikan'               => $_POST['pendidikan']               ?? '',
    'statusperkawinan'         => $_POST['statusperkawinan']         ?? '',
    'sumberinformasi'          => $_POST['sumberinformasi']          ?? '',
    'triase'                   => $_POST['triase']                   ?? '',

    // B. PRIMARY SURVEY
    'keluhanutama'             => $_POST['keluhanutama']             ?? '',
    'riwayatkeluhanutama'      => $_POST['riwayatkeluhanutama']      ?? '',

    // Airway
    'jalannapas'               => $_POST['jalannapas']               ?? '',
    'obstruksi'                => $_POST['obstruksi']                ?? '',
    'suaranapas_airway'        => $_POST['suaranapas_airway']        ?? '',
    'keluhanlainairway'        => $_POST['keluhanlainairway']        ?? '', // Digunakan di Airway & Exposure

    // Breathing
    'gerakandada'              => $_POST['gerakandada']              ?? '',
    'iramanapas'               => $_POST['iramanapas']               ?? '',
    'polanapas'                => $_POST['polanapas']                ?? '',
    'ototdada'                 => $_POST['ototdada']                 ?? '',
    'sesaknapas'               => $_POST['sesaknapas']               ?? '',
    'rr'                       => $_POST['rr']                       ?? '',
    'suaranapas'               => $_POST['suaranapas']               ?? '',
    'keluhanlainbreathing'     => $_POST['keluhanlainbreathing']     ?? '',

    // Circulation
    'pucat'                    => $_POST['pucat']                    ?? '',
    'sianosis'                 => $_POST['sianosis']                 ?? '',
    'pendarahan'               => $_POST['pendarahan']               ?? '',
    'berapabanyak'             => $_POST['berapabanyak']             ?? '',
    'nadi'                     => $_POST['nadi']                     ?? '',
    'frekuensinadi'            => $_POST['frekuensinadi']            ?? '',
    'tekanandarah'             => $_POST['tekanandarah']             ?? '',
    'suhu'                     => $_POST['suhu']                     ?? '',
    'crt'                      => $_POST['crt']                      ?? '',
    'akral'                    => $_POST['akral']                    ?? '',
    'keluhanlain'              => $_POST['keluhanlain']              ?? '',

    // Disability
    'respon'                   => $_POST['respon']                   ?? '',
    'kesadaran'                => $_POST['kesadaran']                ?? '',
    'lainnyasebutkan'          => $_POST['lainnyasebutkan']          ?? '',
    'e'                        => $_POST['e']                        ?? '',
    'm'                        => $_POST['m']                        ?? '',
    'v'                        => $_POST['v']                        ?? '',
    'pupil'                    => $_POST['pupil']                    ?? '',
    'reflekscahaya'            => $_POST['reflekscahaya']            ?? '',
    'muntahproyektil'          => $_POST['muntahproyektil']          ?? '',
    'muntahproyekyil'          => $_POST['muntahproyekyil']          ?? '', // Mengikuti name typo radio 'tidak' di HTML
    'keluhanlaindisability'    => $_POST['keluhanlaindisability']    ?? '',

    // Exposure (Pemeriksaan Fisik)
    'diformitas'               => $_POST['diformitas']               ?? '',
    'contusio'                 => $_POST['contusio']                 ?? '',
    'abrasi'                   => $_POST['abrasi']                   ?? '',
    'penetrasi'                => $_POST['penetrasi']                ?? '',
    'laserasi'                 => $_POST['laserasi']                 ?? '',
    'edema'                    => $_POST['edema']                    ?? '',

    // Alat Terpasang
    'folleyterpasang'          => $_POST['folleyterpasang']          ?? '',
    'gastricterpasang'         => $_POST['gastricterpasang']         ?? '',
    'detail_benda'           => $_POST['detail_benda']           ?? '',
    'letak_pendarahan'           => $_POST['letak_pendarahan']           ?? '',
    'detail_suara_napas'           => $_POST['detail_suara_napas']           ?? '',
    'suara_napas_tambahan'           => $_POST['suara_napas_tambahan']           ?? '',

    ];
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

    <?php include "gadar/igd/tab.php"; ?>

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
       <div class="card">
    <div class="card-body">
        <h5 class="card-title mb-1"><strong>A. IDENTITAS</strong></h5>

        <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

            <div class="row mb-3">
                <label for="norekammedis" class="col-sm-2 col-form-label"><strong>No Rekam Medis</strong></label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="norekammedis" value="<?= htmlspecialchars($existing_data['norekammedis'] ?? '') ?>" <?= $ro ?>>
                </div>
            </div>    

            <div class="row mb-3">
                <label class="col-sm-2 col-form-label"><strong>Nama</strong></label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="nama" value="<?= htmlspecialchars($existing_data['nama'] ?? '') ?>" <?= $ro ?>>
                </div>
            </div>

            <div class="row mb-3">
                <label for="umur" class="col-sm-2 col-form-label"><strong>Umur</strong></label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="umur" value="<?= htmlspecialchars($existing_data['umur'] ?? '') ?>" <?= $ro ?>>
                </div>
            </div>

            <div class="row mb-3">
                <label for="agama" class="col-sm-2 col-form-label"><strong>Agama</strong></label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="agama" value="<?= htmlspecialchars($existing_data['agama'] ?? '') ?>" <?= $ro ?>>
                </div>
            </div>
            
            <div class="row mb-3">
                <label for="pekerjaan" class="col-sm-2 col-form-label"><strong>Pekerjaan</strong></label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="pekerjaan" value="<?= htmlspecialchars($existing_data['pekerjaan'] ?? '') ?>" <?= $ro ?>>
                </div>
            </div>
            
            <div class="row mb-3">
                <label for="alamat" class="col-sm-2 col-form-label"><strong>Alamat</strong></label>
                <div class="col-sm-10">
                   <textarea name="alamat" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars($existing_data['alamat'] ?? '') ?></textarea>
                </div>
            </div>

            <div class="row mb-3">
                <label for="diagnosamedis" class="col-sm-2 col-form-label"><strong>Diagnosa Medis</strong></label>
                <div class="col-sm-10">
                   <textarea name="diagnosamedis" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars($existing_data['diagnosamedis'] ?? '') ?></textarea>
                </div>
            </div>
            
            <div class="row mb-3">
                <label for="jeniskelamin" class="col-sm-2 col-form-label"><strong>Jenis Kelamin</strong></label> 
                <div class="col-sm-10">
                    <select class="form-select" name="jeniskelamin" <?= $ro_disabled ?>>
                        <option value="">Pilih</option>
                        <option value="Perempuan" <?= ($existing_data['jeniskelamin'] ?? '') === 'Perempuan' ? 'selected' : '' ?>>Perempuan</option>
                        <option value="Laki-laki" <?= ($existing_data['jeniskelamin'] ?? '') === 'Laki-laki' ? 'selected' : '' ?>>Laki-laki</option>
                    </select>
                </div>
            </div>    

            <div class="row mb-3">
                <label for="pendidikan" class="col-sm-2 col-form-label"><strong>Pendidikan</strong></label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="pendidikan" value="<?= htmlspecialchars($existing_data['pendidikan'] ?? '') ?>" <?= $ro ?>>
                </div>
            </div>

            <div class="row mb-3">
                <label for="statusperkawinan" class="col-sm-2 col-form-label"><strong>Status Perkawinan</strong></label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="statusperkawinan" value="<?= htmlspecialchars($existing_data['statusperkawinan'] ?? '') ?>" <?= $ro ?>>
                </div>
            </div> 
                        
            <div class="row mb-3">
                <label for="sumberinformasi" class="col-sm-2 col-form-label"><strong>Sumber Informasi</strong></label>
                <div class="col-sm-10">
                   <textarea name="sumberinformasi" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars($existing_data['sumberinformasi'] ?? '') ?></textarea>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-sm-2">
                    <strong>Triase</strong>
                </div> 

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="triase" value="p1" id="p1" <?= ($existing_data['triase'] ?? '') === 'p1' ? 'checked' : '' ?> <?= $ro_disabled ?>>
                        <label class="form-check-label" for="p1" style="color:red; font-weight:bold;">P1</label>
                    </div>
                </div>

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="triase" value="p2" id="p2" <?= ($existing_data['triase'] ?? '') === 'p2' ? 'checked' : '' ?> <?= $ro_disabled ?>>
                        <label class="form-check-label" for="p2" style="color:#ffc107; font-weight:bold;">P2</label>
                    </div>
                </div>

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="triase" value="p3" id="p3" <?= ($existing_data['triase'] ?? '') === 'p3' ? 'checked' : '' ?> <?= $ro_disabled ?>>
                        <label class="form-check-label" for="p3" style="color:green; font-weight:bold;">P3</label>
                    </div>
                </div>

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="triase" value="p4" id="p4" <?= ($existing_data['triase'] ?? '') === 'p4' ? 'checked' : '' ?> <?= $ro_disabled ?>>
                        <label class="form-check-label" for="p4" style="color:black; font-weight:bold;">P4</label>
                    </div>
                </div>
            </div> 
 
    </div>
</div>

        <div class="card">
    <div class="card-body">
        <h5 class="card-title mb-1"><strong>B. PRIMARY SURVEY</strong></h5>                

        <div class="row mb-3">
            <label for="keluhanutama" class="col-sm-2 col-form-label"><strong>Keluhan Utama</strong></label>
            <div class="col-sm-10">
               <textarea name="keluhanutama" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars($existing_data['keluhanutama'] ?? '') ?></textarea>
            </div>
        </div>
        
        <div class="row mb-3">
            <label for="riwayatkeluhanutama" class="col-sm-2 col-form-label"><strong>Riwayat Keluhan Utama</strong></label>
            <div class="col-sm-10">
               <textarea name="riwayatkeluhanutama" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars($existing_data['riwayatkeluhanutama'] ?? '') ?></textarea>
            </div>
        </div>

        <div class="row mb-3">
            <label class="col-sm-10 col-form-label text-primary">
                <strong>Airway</strong>
            </label>
        </div>   

        <div class="row mb-2">
            <div class="col-sm-2"><strong>Jalan Napas</strong></div>    
            <div class="col-sm-2">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="jalannapas" value="paten" <?= ($existing_data['jalannapas'] ?? '') === 'paten' ? 'checked' : '' ?> <?= $ro ?>>
                    <label class="form-check-label">Paten</label>
                </div>
            </div>
            <div class="col-sm-2">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="jalannapas" value="tidakpaten" <?= ($existing_data['jalannapas'] ?? '') === 'tidakpaten' ? 'checked' : '' ?> <?= $ro ?>>
                    <label class="form-check-label">Tidak Paten</label>
                </div>
            </div>
        </div>

        <style>
    /* Sembunyikan input teks secara default */
    .input-benda-container {
        display: none;
    }
    /* Jika radio dengan value 'bendaasing' dicentang, tampilkan container-nya */
    input[value="bendaasing"]:checked ~ .input-benda-container {
        display: inline-block;
    }
</style>

<div class="row mb-2">
    <div class="col-sm-2"><strong>Obstruksi</strong></div>    
    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="obstruksi" value="lidah" <?= ($existing_data['obstruksi'] ?? '') === 'lidah' ? 'checked' : '' ?> <?= $ro ?>>
            <label class="form-check-label">Lidah</label>
        </div>
    </div>
    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="obstruksi" value="cairan" <?= ($existing_data['obstruksi'] ?? '') === 'cairan' ? 'checked' : '' ?> <?= $ro ?>>
            <label class="form-check-label">Cairan</label>
        </div>
    </div>
    <div class="col-sm-6"> <div class="form-check">
            <input class="form-check-input" type="radio" name="obstruksi" value="bendaasing" <?= ($existing_data['obstruksi'] ?? '') === 'bendaasing' ? 'checked' : '' ?> <?= $ro ?>>
            <label class="form-check-label">Benda Asing</label>
            
            <div class="input-benda-container ms-9">
                <input type="text" class="form-control form-control-sm" name="detail_benda" 
                       placeholder="" 
                       value="<?= htmlspecialchars($existing_data['detail_benda'] ?? '') ?>" <?= $ro ?>>
            </div>
        </div>
    </div>
</div>

        <div class="row mb-2">
            <div class="col-sm-2"><strong>Suara Napas </strong></div>    
            <div class="col-sm-2">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="suaranapas_airway" value="snoring" <?= ($existing_data['suaranapas_airway'] ?? '') === 'snoring' ? 'checked' : '' ?> <?= $ro ?>>
                    <label class="form-check-label">Snoring</label>
                </div>
            </div>
            <div class="col-sm-2">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="suaranapas_airway" value="gurgling" <?= ($existing_data['suaranapas_airway'] ?? '') === 'gurgling' ? 'checked' : '' ?> <?= $ro ?>>
                    <label class="form-check-label">Gurgling</label>
                </div>
            </div>
       
        <div class="col-sm-2">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="suaranapas_airway" value="stridot" <?= ($existing_data['suaranapas_airway'] ?? '') === 'stridot' ? 'checked' : '' ?> <?= $ro ?>>
                    <label class="form-check-label">Stridot</label>
                </div>
            </div>
        </div>

        <div class="row mb-3">
            <label for="keluhanlainairway" class="col-sm-2 col-form-label"><strong>Keluhan Lain </strong></label>
            <div class="col-sm-10">
               <textarea name="keluhanlainairway" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars($existing_data['keluhanlainairway'] ?? '') ?></textarea>
            </div>
        </div>

        <div class="row mb-3">
            <label class="col-sm-10 col-form-label text-primary">
                <strong>Breathing</strong>
            </label>
        </div>   

        <div class="row mb-2">
            <div class="col-sm-2"><strong>Gerakan Dada</strong></div>    
            <div class="col-sm-2">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="gerakandada" value="simetris" <?= ($existing_data['gerakandada'] ?? '') === 'simetris' ? 'checked' : '' ?> <?= $ro ?>>
                    <label class="form-check-label">Simetris</label>
                </div>
            </div>
            <div class="col-sm-2">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="gerakandada" value="asimetris" <?= ($existing_data['gerakandada'] ?? '') === 'asimetris' ? 'checked' : '' ?> <?= $ro ?>>
                    <label class="form-check-label">Ansimetris</label>
                </div>
            </div>
        </div>

        <div class="row mb-2">
            <div class="col-sm-2"><strong>Irama Napas</strong></div>    
            <div class="col-sm-2">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="iramanapas" value="cepat" <?= ($existing_data['iramanapas'] ?? '') === 'cepat' ? 'checked' : '' ?> <?= $ro ?>>
                    <label class="form-check-label">Cepat</label>
                </div>
            </div>
            <div class="col-sm-2">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="iramanapas" value="dangkal" <?= ($existing_data['iramanapas'] ?? '') === 'dangkal' ? 'checked' : '' ?> <?= $ro ?>>
                    <label class="form-check-label">Dangkal</label>
                </div>
            </div>
            <div class="col-sm-2">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="iramanapas" value="normal" <?= ($existing_data['iramanapas'] ?? '') === 'normal' ? 'checked' : '' ?> <?= $ro ?>>
                    <label class="form-check-label">Normal</label>
                </div>
            </div>
        </div>

        <div class="row mb-2">
            <div class="col-sm-2"><strong>Pola Napas</strong></div>    
            <div class="col-sm-2">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="polanapas" value="teratur" <?= ($existing_data['polanapas'] ?? '') === 'teratur' ? 'checked' : '' ?> <?= $ro ?>>
                    <label class="form-check-label">Teratur</label>
                </div>
            </div>
            <div class="col-sm-2">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="polanapas" value="tidakteratur" <?= ($existing_data['polanapas'] ?? '') === 'tidakteratur' ? 'checked' : '' ?> <?= $ro ?>>
                    <label class="form-check-label">Tidak Teratur</label>
                </div>
            </div>
        </div>

        <div class="row mb-2">
            <div class="col-sm-2"><strong>Retraksi Otot Dada</strong></div>    
            <div class="col-sm-2">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="ototdada" value="ada" <?= ($existing_data['ototdada'] ?? '') === 'ada' ? 'checked' : '' ?> <?= $ro ?>>
                    <label class="form-check-label">Ada</label>
                </div>
            </div>
            <div class="col-sm-2">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="ototdada" value="tidakada" <?= ($existing_data['ototdada'] ?? '') === 'tidakada' ? 'checked' : '' ?> <?= $ro ?>>
                    <label class="form-check-label">Tidak Ada</label>
                </div>
            </div>
        </div>

        <div class="row mb-2">
            <div class="col-sm-2"><strong>Sesak Napas</strong></div>    
            <div class="col-sm-2">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="sesaknapas" value="ya" <?= ($existing_data['sesaknapas'] ?? '') === 'ya' ? 'checked' : '' ?> <?= $ro ?>>
                    <label class="form-check-label">Ya</label>
                </div>
            </div>
            <div class="col-sm-2">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="sesaknapas" value="tidakada" <?= ($existing_data['sesaknapas'] ?? '') === 'tidakada' ? 'checked' : '' ?> <?= $ro ?>>
                    <label class="form-check-label">Tidak Ada</label>
                </div>
            </div>
        </div>

        <div class="row mb-3">
            <label for="rr" class="col-sm-2 col-form-label"><strong>RR</strong></label>
            <div class="col-sm-10">
                <div class="input-group">
                    <input type="text" class="form-control" name="rr" value="<?= htmlspecialchars($existing_data['rr'] ?? '') ?>" <?= $ro ?>>
                    <span class="input-group-text">x/menit</span>
                </div>
            </div>
        </div>

        <div class="row mb-3">
            <label for="suaranapas" class="col-sm-2 col-form-label"><strong>Suara Napas</strong></label>
            <div class="col-sm-10">
               <textarea name="suaranapas" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars($existing_data['suaranapas'] ?? '') ?></textarea>
            </div>
        </div>   
       <style>
    /* Sembunyikan container input secara default */
    .input-napas-container {
        display: none;
    }
    /* Jika radio dengan value 'ada' dicentang, tampilkan input */
    input[value="ada"]:checked ~ .input-napas-container {
        display: inline-block;
    }
</style>

<div class="row mb-2">
    <div class="col-sm-2"><strong>Suara Napas Tambahan</strong></div>    
    <div class="col-sm-6"> <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="suara_napas_tambahan" value="ada" 
                <?= ($existing_data['suara_napas_tambahan'] ?? '') === 'ada' ? 'checked' : '' ?> <?= $ro ?>>
            <label class="form-check-label">Ada</label>
            
            <div class="input-napas-container ms-2">
                <input type="text" class="form-control form-control-sm" name="detail_suara_napas" 
                    placeholder="Sebutkan suara napas..." 
                    value="<?= htmlspecialchars($existing_data['detail_suara_napas'] ?? '') ?>" <?= $ro ?>>
            </div>
        </div>

        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="suara_napas_tambahan" value="tidakada" 
                <?= ($existing_data['suara_napas_tambahan'] ?? '') === 'tidakada' ? 'checked' : '' ?> <?= $ro ?>>
            <label class="form-check-label">Tidak Ada</label>
        </div>
    </div>
</div>  

        <div class="row mb-3">
            <label for="keluhanlainbreathing" class="col-sm-2 col-form-label"><strong>Keluhan Lain </strong></label>
            <div class="col-sm-10">
               <textarea name="keluhanlainbreathing" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars($existing_data['keluhanlainbreathing'] ?? '') ?></textarea>
            </div>
        </div>

        <div class="row mb-3">
            <label class="col-sm-10 col-form-label text-primary">
                <strong>Circulation</strong>
            </label>
        </div>   

        <div class="row mb-2">
            <div class="col-sm-2"><strong>Pucat</strong></div>    
            <div class="col-sm-2">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="pucat" value="ya" <?= ($existing_data['pucat'] ?? '') === 'ya' ? 'checked' : '' ?> <?= $ro ?>>
                    <label class="form-check-label">Ya</label>
                </div>
            </div>
            <div class="col-sm-2">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="pucat" value="tidak" <?= ($existing_data['pucat'] ?? '') === 'tidak' ? 'checked' : '' ?> <?= $ro ?>>
                    <label class="form-check-label">Tidak</label>
                </div>
            </div>
        </div>

        <div class="row mb-2">
            <div class="col-sm-2"><strong>Sianosis</strong></div>    
            <div class="col-sm-2">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="sianosis" value="ya" <?= ($existing_data['sianosis'] ?? '') === 'ya' ? 'checked' : '' ?> <?= $ro ?>>
                    <label class="form-check-label">Ya</label>
                </div>
            </div>
            <div class="col-sm-2">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="sianosis" value="tidak" <?= ($existing_data['sianosis'] ?? '') === 'tidak' ? 'checked' : '' ?> <?= $ro ?>>
                    <label class="form-check-label">Tidak</label>
                </div>
            </div>
        </div>

    

<div class="row mb-2">
    <div class="col-sm-2"><strong>Pendarahan</strong></div>  
    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="pendarahan" id="pendarahan_ya" value="ya" 
                <?= ($existing_data['pendarahan'] ?? '') === 'ya' ? 'checked' : '' ?> <?= $ro ?>>
            <label class="form-check-label" for="pendarahan_ya">Ya</label>
        </div>
    </div>
    <div class="col-sm-2">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="pendarahan" id="pendarahan_tidak" value="tidak" 
                <?= ($existing_data['pendarahan'] ?? '') === 'tidak' ? 'checked' : '' ?> <?= $ro ?>>
            <label class="form-check-label" for="pendarahan_tidak">Tidak</label>
        </div>
    </div>

    <div class="detail-pendarahan col-sm-10 offset-sm-2 mt-2">
        <div class="row">
            <div class="col-sm-6">
                <label><strong>Berapa Banyak</strong></label>
                <input type="text" class="form-control" name="berapabanyak" 
                    value="<?= htmlspecialchars($existing_data['berapabanyak'] ?? '') ?>" <?= $ro ?>>
            </div>
            <div class="col-sm-6">
                <label><strong>Letak</strong></label>
                <input type="text" class="form-control" name="letak_pendarahan" 
                    placeholder="Masukkan lokasi..."
                    value="<?= htmlspecialchars($existing_data['letak_pendarahan'] ?? '') ?>" <?= $ro ?>>
            </div>
        </div>
    </div>
</div>

        <div class="row mb-2">
            <div class="col-sm-2"><strong>Nadi</strong></div>  
            <div class="col-sm-2">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="nadi" value="teraba" <?= ($existing_data['nadi'] ?? '') === 'teraba' ? 'checked' : '' ?> <?= $ro ?>>
                    <label class="form-check-label">Teraba</label>
                </div>
            </div>
            <div class="col-sm-2">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="nadi" value="tidakteraba" <?= ($existing_data['nadi'] ?? '') === 'tidakteraba' ? 'checked' : '' ?> <?= $ro ?>>
                    <label class="form-check-label">Tidak Teraba</label>
                </div>
            </div>

            <div class="col-sm-10 offset-sm-2 mt-2">
                <label><strong>Frekuensi Nadi</strong></label>
                <div class="input-group">
                    <input type="text" class="form-control" name="frekuensinadi" value="<?= htmlspecialchars($existing_data['frekuensinadi'] ?? '') ?>" <?= $ro ?>>
                    <span class="input-group-text">x/menit</span>
                </div>
            </div>
        </div>

        <div class="row mb-3">
            <label class="col-sm-2 col-form-label"><strong>Tekanan Darah</strong></label>
            <div class="col-sm-10">
                <div class="input-group">
                <textarea name="tekanandarah" class="form-control" rows="1" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars($existing_data['tekanandarah'] ?? '') ?></textarea>
                                <span class="input-group-text">mmHg</span>

            </div>
        </div>
        </div>

        <div class="row mb-3">
            <label class="col-sm-2 col-form-label"><strong>Suhu</strong></label>
            <div class="col-sm-10">
                <div class="input-group">
                    <input type="text" class="form-control" name="suhu" value="<?= htmlspecialchars($existing_data['suhu'] ?? '') ?>" <?= $ro ?>>
                    <span class="input-group-text">°C</span>
                </div>
            </div> 
        </div>   
        
        <div class="row mb-2">
            <div class="col-sm-2"><strong>CRT</strong></div>    
            <div class="col-sm-2">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="crt" value="kurang2" <?= ($existing_data['crt'] ?? '') === 'kurang2' ? 'checked' : '' ?> <?= $ro ?>>
                    <label class="form-check-label">&lt; 2 Detik</label>
                </div>
            </div>
            <div class="col-sm-2">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="crt" value="lebih2" <?= ($existing_data['crt'] ?? '') === 'lebih2' ? 'checked' : '' ?> <?= $ro ?>>
                    <label class="form-check-label">&gt; 2 Detik</label>
                </div>
            </div>
        </div>

        <div class="row mb-2">
            <div class="col-sm-2"><strong>Akral</strong></div>    
            <div class="col-sm-2">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="akral" value="hangat" <?= ($existing_data['akral'] ?? '') === 'hangat' ? 'checked' : '' ?> <?= $ro ?>>
                    <label class="form-check-label">Hangat</label>
                </div>
            </div>
            <div class="col-sm-2">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="akral" value="dingin" <?= ($existing_data['akral'] ?? '') === 'dingin' ? 'checked' : '' ?> <?= $ro ?>>
                    <label class="form-check-label">Dingin</label>
                </div>
            </div>
        </div>

        <div class="row mb-3">
            <label class="col-sm-2 col-form-label"><strong>Keluhan Lain </strong></label>
            <div class="col-sm-10">
               <textarea name="keluhanlain" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars($existing_data['keluhanlain'] ?? '') ?></textarea>
            </div>
        </div>

        <div class="row mb-3">
            <label class="col-sm-10 col-form-label text-primary">
                <strong>Disability</strong>
            </label>
        </div>   

        <div class="row mb-2">
            <div class="col-sm-2"><strong>Respon</strong></div>    
            <div class="col-sm-2">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="respon" value="alert" <?= ($existing_data['respon'] ?? '') === 'alert' ? 'checked' : '' ?> <?= $ro ?>>
                    <label class="form-check-label">Alert</label>
                </div>
            </div>
            <div class="col-sm-2">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="respon" value="verbal" <?= ($existing_data['respon'] ?? '') === 'verbal' ? 'checked' : '' ?> <?= $ro ?>>
                    <label class="form-check-label">Verbal</label>
                </div>
            </div>
            <div class="col-sm-2">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="respon" value="pain" <?= ($existing_data['respon'] ?? '') === 'pain' ? 'checked' : '' ?> <?= $ro ?>>
                    <label class="form-check-label">Pain</label>
                </div>
            </div>
            <div class="col-sm-2">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="respon" value="unresponden" <?= ($existing_data['respon'] ?? '') === 'unresponden' ? 'checked' : '' ?> <?= $ro ?>>
                    <label class="form-check-label">Unresponden</label>
                </div>
            </div>
        </div>

<div class="row mb-3">
                <label class="col-sm-2 col-form-label"><strong>GCS</strong></label>
                
                <div class="col-sm-10">
                    <div class="row">
                        <div class="col-sm-4 d-flex align-items-center">
                            <label class="me-2 mb-0"><strong>E</strong></label>
                            <input type="text" class="form-control" name="e" value="<?= val('e', $existing_data) ?>" <?= $ro ?>>
                        </div>

                        <div class="col-sm-4 d-flex align-items-center">
                            <label class="me-2 mb-0"><strong>M</strong></label>
                            <input type="text" class="form-control" name="m" value="<?= val('m', $existing_data) ?>" <?= $ro ?>>
                        </div>

                        <div class="col-sm-4 d-flex align-items-center">
                            <label class="me-2 mb-0"><strong>V</strong></label>
                            <input type="text" class="form-control" name="v" value="<?= val('v', $existing_data) ?>" <?= $ro ?>>
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

  
            <div class="row mb-2">
                <div class="col-sm-2"><strong>Pupil</strong></div>    

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="pupil" value="isokor" <?= val('pupil', $existing_data) == 'isokor' ? 'checked' : '' ?> <?= $ro ?>>
                        <label class="form-check-label">Isokor</label>
                    </div>
                </div>

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="pupil" value="anisokor" <?= val('pupil', $existing_data) == 'anisokor' ? 'checked' : '' ?> <?= $ro ?>>
                        <label class="form-check-label">Anisokor</label>
                    </div>
                </div>

              
            </div>

            <div class="row mb-2">
                <div class="col-sm-2"><strong>Refleks Cahaya</strong></div>    

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="reflekscahaya" value="ada" <?= val('reflekscahaya', $existing_data) == 'ada' ? 'checked' : '' ?> <?= $ro ?>>
                        <label class="form-check-label">Ada</label>
                    </div>
                </div>

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="reflekscahaya" value="tidakada" <?= val('reflekscahaya', $existing_data) == 'tidakada' ? 'checked' : '' ?> <?= $ro ?>>
                        <label class="form-check-label">Tidak Ada</label>
                    </div>
                </div>
            </div> 

            <div class="row mb-2">
                <div class="col-sm-2"><strong>Muntah Proyektil</strong></div>    

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="muntahproyektil" value="ya" <?= val('muntahproyektil', $existing_data) == 'ya' ? 'checked' : '' ?> <?= $ro ?>>
                        <label class="form-check-label">Ya</label>
                    </div>
                </div>

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="muntahproyekyil" value="tidak" <?= val('muntahproyekyil', $existing_data) == 'tidak' ? 'checked' : '' ?> <?= $ro ?>>
                        <label class="form-check-label">Tidak</label>
                    </div>
                </div>
            </div>  

            <div class="row mb-3">
                <label for="keluhanlaindisability" class="col-sm-2 col-form-label"><strong>Keluhan Lain</strong></label>
                <div class="col-sm-10">
                    <textarea name="keluhanlaindisability" class="form-control" rows="3" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('keadaan_umum', $existing_data) ?></textarea>
                </div>
            </div>
     
            <div class="row mb-3">
                <label class="col-sm-10 col-form-label text-primary"><strong>Exposure</strong></label>
            </div>   

            <div class="row mb-2">
                <div class="col-sm-2"><strong>Deformitas</strong></div>    

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="diformitas" value="ya" <?= val('diformitas', $existing_data) == 'ya' ? 'checked' : '' ?> <?= $ro ?>>
                        <label class="form-check-label">Ya</label>
                    </div>
                </div>

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="diformitas" value="tidak" <?= val('diformitas', $existing_data) == 'tidak' ? 'checked' : '' ?> <?= $ro ?>>
                        <label class="form-check-label">Tidak</label>
                    </div>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-sm-2"><strong>Contusio</strong></div>    

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="contusio" value="ya" <?= val('contusio', $existing_data) == 'ya' ? 'checked' : '' ?> <?= $ro ?>>
                        <label class="form-check-label">Ya</label>
                    </div>
                </div>

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="contusio" value="tidak" <?= val('contusio', $existing_data) == 'tidak' ? 'checked' : '' ?> <?= $ro ?>>
                        <label class="form-check-label">Tidak</label>
                    </div>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-sm-2"><strong>Abrasi</strong></div>    

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="abrasi" value="ya" <?= val('abrasi', $existing_data) == 'ya' ? 'checked' : '' ?> <?= $ro ?>>
                        <label class="form-check-label">Ya</label>
                    </div>
                </div>

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="abrasi" value="tidak" <?= val('abrasi', $existing_data) == 'tidak' ? 'checked' : '' ?> <?= $ro ?>>
                        <label class="form-check-label">Tidak</label>
                    </div>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-sm-2"><strong>Penetrasi</strong></div>    

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="penetrasi" value="ya" <?= val('penetrasi', $existing_data) == 'ya' ? 'checked' : '' ?> <?= $ro ?>>
                        <label class="form-check-label">Ya</label>
                    </div>
                </div>

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="penetrasi" value="tidak" <?= val('penetrasi', $existing_data) == 'tidak' ? 'checked' : '' ?> <?= $ro ?>>
                        <label class="form-check-label">Tidak</label>
                    </div>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-sm-2"><strong>Laserasi</strong></div>    

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="laserasi" value="ya" <?= val('laserasi', $existing_data) == 'ya' ? 'checked' : '' ?> <?= $ro ?>>
                        <label class="form-check-label">Ya</label>
                    </div>
                </div>

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="laserasi" value="tidak" <?= val('laserasi', $existing_data) == 'tidak' ? 'checked' : '' ?> <?= $ro ?>>
                        <label class="form-check-label">Tidak</label>
                    </div>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-sm-2"><strong>Edema</strong></div>    

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="edema" value="ya" <?= val('edema', $existing_data) == 'ya' ? 'checked' : '' ?> <?= $ro ?>>
                        <label class="form-check-label">Ya</label>
                    </div>
                </div>

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="edema" value="tidak" <?= val('edema', $existing_data) == 'tidak' ? 'checked' : '' ?> <?= $ro ?>>
                        <label class="form-check-label">Tidak</label>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <label for="keluhanlainairway" class="col-sm-2 col-form-label"><strong>Keluhan Lain</strong></label>
                <div class="col-sm-10">
                   <textarea name="keluhanlainairway" class="form-control" rows="3" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('keluhanlainairway', $existing_data) ?></textarea>
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-10 col-form-label text-primary"><strong>Folley Catheter</strong></label>
            </div>   

            <div class="row mb-2">
                <div class="col-sm-2"><strong>Terpasang</strong></div>    

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="folleyterpasang" value="ya" <?= val('folleyterpasang', $existing_data) == 'ya' ? 'checked' : '' ?> <?= $ro ?>>
                        <label class="form-check-label">Ya</label>
                    </div>
                </div>

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="folleyterpasang" value="tidak" <?= val('folleyterpasang', $existing_data) == 'tidak' ? 'checked' : '' ?> <?= $ro ?>>
                        <label class="form-check-label">Tidak</label>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-10 col-form-label text-primary"><strong>Gastric Tube</strong></label>
            </div>   

            <div class="row mb-2">
                <div class="col-sm-2"><strong>Terpasang</strong></div>    

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="gastricterpasang" value="ya" <?= val('gastricterpasang', $existing_data) == 'ya' ? 'checked' : '' ?> <?= $ro ?>>
                        <label class="form-check-label">Ya</label>
                    </div>
                </div>

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="gastricterpasang" value="tidak" <?= val('gastricterpasang', $existing_data) == 'tidak' ? 'checked' : '' ?> <?= $ro ?>>
                        <label class="form-check-label">Tidak</label>
                    </div>
                </div>
            </div>    

            <div class="row mb-3">
                <label class="col-sm-10 col-form-label text-primary"><strong>Heart Monitor</strong></label>
            </div>   

            <div class="row mb-2">
                <div class="col-sm-2"><strong>Terpasang</strong></div>    

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="heartterpasang" value="ya" <?= val('heartterpasang', $existing_data) == 'ya' ? 'checked' : '' ?> <?= $ro ?>>
                        <label class="form-check-label">Ya</label>
                    </div>
                </div>

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="heartterpasang" value="tidak" <?= val('heartterpasang', $existing_data) == 'tidak' ? 'checked' : '' ?> <?= $ro ?>>
                        <label class="form-check-label">Tidak</label>
                    </div>
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
       </form>

               <?php include dirname(__DIR__, 2) . '/partials/footer_form.php'; ?>


</section>
</main>

