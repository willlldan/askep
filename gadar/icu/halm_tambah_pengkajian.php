<?php
$form_id       = 21;
$section_name  = 'pengkajian';
$section_label = 'Pengkajian';
include dirname(__DIR__, 2) . '/partials/init_section.php';



// Load existing dynamic rows
$existing_perencanaan    = $existing_data['perencanaan']      ?? [];
$existing_daftar_pustaka = $existing_data['daftar_pustaka']   ?? [];
$existing_kdm            = $existing_data['penyimpangan_kdm'] ?? '';

// =============================================
// HANDLE POST - MAHASISWA SIMPAN DATA
// =============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $level === 'Mahasiswa') {

    if (isLocked($submission)) {
        redirectWithMessage($_SERVER['REQUEST_URI'], 'error', 'Data tidak dapat diubah karena sedang dalam proses review.');
    }

    // Proses dynamic rows perencanaan
    $perencanaan = [];
    if (!empty($_POST['perencanaan'])) {
        foreach ($_POST['perencanaan'] as $index => $row) {
            if (empty($row['diagnosa']) && empty($row['tujuan_kriteria']) && empty($row['intervensi'])) {
                continue;
            }
            $perencanaan[] = [
                'diagnosa'        => $row['diagnosa']        ?? '',
                'tujuan_kriteria' => $row['tujuan_kriteria'] ?? '',
                'intervensi'      => $row['intervensi']      ?? '',
            ];
        }
    }

    // Proses dynamic list daftar pustaka
    $daftar_pustaka = [];
    if (!empty($_POST['daftar_pustaka'])) {
        foreach ($_POST['daftar_pustaka'] as $item) {
            if (empty(trim($item))) continue;
            $daftar_pustaka[] = trim($item);
        }
    }


$data = [
    // Identitas Klien
    'nama'                 => $_POST['nama']                 ?? '',
    'umur'                 => $_POST['umur']                 ?? '',
    'jeniskelamin'         => $_POST['jeniskelamin']         ?? '',
    'pekerjaan'            => $_POST['pekerjaan']            ?? '',
    'agama'                => $_POST['agama']                ?? '',
    'tgl_mrs'              => $_POST['tgl_mrs']              ?? '',
    'tgl_pengkajian'       => $_POST['tgl_pengkajian']       ?? '',
    'noreg'                => $_POST['noreg']                ?? '',
    'alamat'               => $_POST['alamat']               ?? '',
    'dxmedis'              => $_POST['dxmedis']              ?? '',
    'keluhanutama'         => $_POST['keluhanutama']         ?? '',
    'riwayatkeluhanutama'  => $_POST['riwayatkeluhanutama']  ?? '',
    'riwayatalergi'        => $_POST['riwayatalergi']        ?? '',
    'keadaanumum'          => $_POST['keadaanumum']          ?? '',

    // Tanda-tanda Vital
    'tekanandarah'         => $_POST['tekanandarah']         ?? '',
    'nadi'                 => $_POST['nadi']                 ?? '',
    'suhu'                 => $_POST['suhu']                 ?? '',
    'rr'                   => $_POST['rr']                   ?? '',

    // 1. Primary Survey - Pengumpulan Data
    'tanggal'                 => $_POST['tanggal']                 ?? '',

    // Airways (Jalan Nafas)
    'jalannafas'              => $_POST['jalannafas']              ?? '',
    'ett'                     => $_POST['ett']                     ?? '',

    // Breathing (Pernafasan)
    'polanafas'               => $_POST['polanafas']               ?? '',
    'spo2'                    => $_POST['spo2']                    ?? '',
    'ventilator'              => $_POST['ventilator']              ?? '',
    'pernafasancupinghidung'  => $_POST['pernafasancupinghidung']  ?? '',
    'suaranafastambahan'      => $_POST['suaranafastambahan']      ?? '',
    'retraksidindingdada'     => $_POST['retraksidindingdada']     ?? '',
    'ototbantu'               => $_POST['ototbantu']               ?? '',

    // Circulation (Sirkulasi)
    'nadi1'                    => $_POST['nadi1']                    ?? '',
    'tekanandarah1'            => $_POST['tekanandarah1']            ?? '',
    'cvp'                     => $_POST['cvp']                     ?? '',
    'crt'                     => $_POST['crt']                     ?? '',
    'suarajantung'            => $_POST['suarajantung']            ?? '',
    'perfusiperifer'          => $_POST['perfusiperifer']          ?? '',

    // Disability (Neurologi)
    'tingkatkesadaran'        => $_POST['tingkatkesadaran']        ?? '',
    'e'                       => $_POST['e']                       ?? '',
    'm'                       => $_POST['m']                       ?? '',
    'v'                       => $_POST['v']                       ?? '',
    'pupil'                   => $_POST['pupil']                   ?? '',
    'responmotorik'           => $_POST['responmotorik']           ?? '',

    // Exposure
    'suhu1'                    => $_POST['suhu1']                    ?? '',
    'lainnya'                 => $_POST['lainnya']                 ?? '',

    // Fluid (Cairan dan Elektrolit)
    'infuse'                  => $_POST['infuse']                  ?? '',
    'cairan'                  => $_POST['cairan']                  ?? '',
    'jumlahtetesan'           => $_POST['jumlahtetesan']           ?? '',
];

    if (!$submission) {
        $submission_id = createSubmission($user_id, $form_id, null, null, $mysqli);
    } else {
        $submission_id = $submission['id'];
        updateSubmissionHeader($submission_id, null, null, $mysqli);
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
     
            
                <!-- General Form Elements -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

                <div class="card">
    <div class="card-body">
        <h5 class="card-title mb-4 mt-2"><strong>IDENTITAS KLIEN</strong></h5>

        <!-- Bagian Nama -->
        <div class="row mb-3">
            <label class="col-sm-2 col-form-label"><strong>Nama</strong></label>
            <div class="col-sm-9">
                <input type="text" class="form-control" name="nama" value="<?= val('nama', $existing_data) ?>" <?= $ro ?>>
            </div>
        </div>

        <!-- Bagian Umur -->
        <div class="row mb-3">
            <label class="col-sm-2 col-form-label"><strong>Umur</strong></label>
            <div class="col-sm-9">
                <input type="text" class="form-control" name="umur" value="<?= val('umur', $existing_data) ?>" <?= $ro ?>>
            </div>
        </div>

        <!-- Bagian Jenis Kelamin -->
        <div class="row mb-3">
            <label class="col-sm-2 col-form-label"><strong>Jenis Kelamin</strong></label>
            <div class="col-sm-9">
                <select class="form-select" name="jeniskelamin" <?= $ro ?>>
                    <option value="">Pilih</option>
                    <option value="Perempuan" <?= val('jeniskelamin', $existing_data) == 'Perempuan' ? 'selected' : '' ?>>Perempuan</option>
                    <option value="Laki-laki" <?= val('jeniskelamin', $existing_data) == 'Laki-laki' ? 'selected' : '' ?>>Laki-laki</option>
                </select>
            </div>
        </div>

        <!-- Bagian Pekerjaan -->
        <div class="row mb-3">
            <label class="col-sm-2 col-form-label"><strong>Pekerjaan</strong></label>
            <div class="col-sm-9">
                <input type="text" class="form-control" name="pekerjaan" value="<?= val('pekerjaan', $existing_data) ?>" <?= $ro ?>>
            </div>
        </div>

        <!-- Bagian Agama -->
        <div class="row mb-3">
            <label class="col-sm-2 col-form-label"><strong>Agama</strong></label>
            <div class="col-sm-9">
                <input type="text" class="form-control" name="agama" value="<?= val('agama', $existing_data) ?>" <?= $ro ?>>
            </div>
        </div>

        <!-- Bagian Tanggal MRS -->
        <div class="row mb-3">
            <label class="col-sm-2 col-form-label"><strong>Tanggal MRS</strong></label>
            <div class="col-sm-9">
                <input type="datetime-local" class="form-control" name="tgl_mrs" value="<?= val('tgl_mrs', $existing_data) ?>" <?= $ro ?>>
            </div>
        </div>

        <!-- Bagian Tanggal Pengkajian -->
        <div class="row mb-3">
            <label class="col-sm-2 col-form-label"><strong>Tanggal Pengkajian</strong></label>
            <div class="col-sm-9">
                <input type="datetime-local" class="form-control" name="tgl_pengkajian" value="<?= val('tgl_pengkajian', $existing_data) ?>" <?= $ro ?>>
            </div>
        </div>

        <!-- Bagian No REG -->
        <div class="row mb-3">
            <label class="col-sm-2 col-form-label"><strong>No REG</strong></label>
            <div class="col-sm-9">
                <input type="text" class="form-control" name="noreg" value="<?= val('noreg', $existing_data) ?>" <?= $ro ?>>
            </div>
        </div>

        <!-- Bagian Alamat -->
        <div class="row mb-3">
            <label class="col-sm-2 col-form-label"><strong>Alamat</strong></label>
            <div class="col-sm-9">
                <textarea name="alamat" class="form-control" rows="3" 
                    style="overflow:hidden; resize: none;" 
                    oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('alamat', $existing_data) ?></textarea>
            </div>
        </div>

        <!-- Bagian DX Medis -->
        <div class="row mb-3">
            <label class="col-sm-2 col-form-label"><strong>DX Medis</strong></label>
            <div class="col-sm-9">
                <input type="text" class="form-control" name="dxmedis" value="<?= val('dxmedis', $existing_data) ?>" <?= $ro ?>>
            </div>
        </div>

        <!-- Bagian Keluhan Utama -->
        <div class="row mb-3">
            <label class="col-sm-2 col-form-label"><strong>Keluhan Utama</strong></label>
            <div class="col-sm-9">
                <textarea name="keluhanutama" class="form-control" rows="3" 
                    style="overflow:hidden; resize: none;" 
                    oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('keluhanutama', $existing_data) ?></textarea>
            </div>
        </div>

        <!-- Bagian Riwayat Keluhan Utama -->
        <div class="row mb-3">
            <label class="col-sm-2 col-form-label"><strong>Riwayat Keluhan Utama</strong></label>
            <div class="col-sm-9">
                <textarea name="riwayatkeluhanutama" class="form-control" rows="3" 
                    style="overflow:hidden; resize: none;" 
                    oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('riwayatkeluhanutama', $existing_data) ?></textarea>
            </div>
        </div>

        <!-- Bagian Riwayat Alergi -->
        <div class="row mb-3">
            <label class="col-sm-2 col-form-label"><strong>Riwayat Alergi</strong></label>
            <div class="col-sm-9">
                <textarea name="riwayatalergi" class="form-control" rows="3" 
                    style="overflow:hidden; resize: none;" 
                    oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('riwayatalergi', $existing_data) ?></textarea>
            </div>
        </div>

        <!-- Bagian Keadaan Umum -->
        <div class="row mb-3">
            <label class="col-sm-2 col-form-label"><strong>Keadaan Umum</strong></label>
            <div class="col-sm-9">
                <textarea name="keadaanumum" class="form-control" rows="3" 
                    style="overflow:hidden; resize: none;" 
                    oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('keadaanumum', $existing_data) ?></textarea>
            </div>
        </div>

        <div class="row mb-4 mt-4">
            <div class="col-sm-12">
                <h6 class="border-bottom pb-2 text-primary"><strong>Tanda-tanda Vital:</strong></h6>
            </div>
        </div>

        <!-- Tekanan Darah & Nadi -->
        <div class="row mb-3 align-items-center">
            <label class="col-sm-2 col-form-label"><strong>Tekanan Darah</strong></label>
            <div class="col-sm-3">
                <div class="input-group">
                    <input type="text" class="form-control" name="tekanandarah" value="<?= val('tekanandarah', $existing_data) ?>" <?= $ro ?>>
                    <span class="input-group-text">mmHg</span>
                </div>    
            </div>
                                
            <label class="col-sm-2 col-form-label text-sm-end"><strong>Nadi</strong></label>
            <div class="col-sm-4">
                <div class="input-group">
                    <input type="text" class="form-control" name="nadi" value="<?= val('nadi', $existing_data) ?>" <?= $ro ?>>
                    <span class="input-group-text">x/menit</span>
                </div> 
            </div>
        </div>
              
        <!-- Suhu & RR -->
        <div class="row mb-3 align-items-center">
            <label class="col-sm-2 col-form-label"><strong>Suhu</strong></label>
            <div class="col-sm-3">
                <div class="input-group">
                    <input type="text" class="form-control" name="suhu" value="<?= val('suhu', $existing_data) ?>" <?= $ro ?>>
                    <span class="input-group-text">°C</span>
                </div>    
            </div>

            <label class="col-sm-2 col-form-label text-sm-end"><strong>RR</strong></label>
            <div class="col-sm-4">
                <div class="input-group">
                    <input type="text" class="form-control" name="rr" value="<?= val('rr', $existing_data) ?>" <?= $ro ?>>
                    <span class="input-group-text">x/menit</span>
                </div>
            </div>
        </div>

    </div>
</div>

            <div class="card">
            <div class="card-body">
              <h5 class="card-title mb-1"><strong>Pengkajian</strong></h5>

                <!-- General Form Elements -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

                <!-- Bagian Primary Survey -->

                   <h5 class="card-title mb-1"><strong>1. Primary Survey</strong></h5>

<div class="row mb-2">
    <label class="col-sm-12 col-form-label"><strong>Pengumpulan Data</strong></label>
</div> 

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Tanggal</strong></label>
    <div class="col-sm-10">
       <input type="datetime-local" class="form-control" name="tanggal" value="<?= htmlspecialchars($existing_data['tanggal'] ?? '') ?>" <?= $ro ?>>
    </div>
</div> 

<div class="row mb-2">
    <label class="col-sm-12 col-form-label"><strong>Airways (Jalan Nafas)</strong></label>
</div> 

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Sumbatan Jalan Nafas/Sekret</strong></label>
    <div class="col-sm-10">
        <select class="form-select" name="jalannafas" required <?= $ro ?>>
            <option value="">Pilih</option>
            <option value="ya" <?= val('jalannafas', $existing_data) === 'ya' ? 'selected' : '' ?>>Ya</option>
            <option value="tidak" <?= val('jalannafas', $existing_data) === 'tidak' ? 'selected' : '' ?>>Tidak Ada</option>
        </select>
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>ETT/Trakeostomi</strong></label>
    <div class="col-sm-10">
        <select class="form-select" name="ett" required <?= $ro ?>>
            <option value="">Pilih</option>
            <option value="Ya" <?= val('ett', $existing_data) === 'Ya' ? 'selected' : '' ?>>Ya</option>
            <option value="Tidak" <?= val('ett', $existing_data) === 'Tidak' ? 'selected' : '' ?>>Tidak</option>
        </select>
    </div>
</div>

<div class="row mb-2">
    <label class="col-sm-12 col-form-label"><strong>Breathing (Pernafasan)</strong></label>
</div> 

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Pola Nafas</strong></label>
    <div class="col-sm-10">
        <div class="input-group">
            <input type="text" class="form-control" name="polanafas" value="<?= htmlspecialchars($existing_data['polanafas'] ?? '') ?>" <?= $ro ?>>
            <span class="input-group-text">x/menit</span>
        </div>
    </div>
</div> 

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>SpO2</strong></label>
    <div class="col-sm-10">
        <div class="input-group">
            <input type="text" class="form-control" name="spo2" value="<?= htmlspecialchars($existing_data['spo2'] ?? '') ?>" <?= $ro ?>>
            <span class="input-group-text">%</span>
        </div>
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Ventilator (mode/PEEP/FiO2)</strong></label>
    <div class="col-sm-10">
        <input type="text" class="form-control" name="ventilator" value="<?= htmlspecialchars($existing_data['ventilator'] ?? '') ?>" <?= $ro ?>>
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Pernafasan Cuping Hidung</strong></label>
    <div class="col-sm-10">
        <select class="form-select" name="pernafasancupinghidung" required <?= $ro ?>>
            <option value="">Pilih</option>
            <option value="Ya" <?= val('pernafasancupinghidung', $existing_data) === 'Ya' ? 'selected' : '' ?>>Ya</option>
            <option value="Tidak" <?= val('pernafasancupinghidung', $existing_data) === 'Tidak' ? 'selected' : '' ?>>Tidak Ada</option>
        </select>
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Suara Nafas Tambahan</strong></label>
    <div class="col-sm-10">
        <input type="text" class="form-control" name="suaranafastambahan" value="<?= htmlspecialchars($existing_data['suaranafastambahan'] ?? '') ?>" <?= $ro ?>>
    </div>
</div>
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Retraksi Dinding Dada</strong></label>
    <div class="col-sm-10">
        <select class="form-select" name="retraksidindingdada" required <?= $ro ?>>
            <option value="">Pilih</option>
            <option value="Ya" <?= val('retraksidindingdada', $existing_data) === 'Ya' ? 'selected' : '' ?>>Ya</option>
            <option value="Tidak" <?= val('retraksidindingdada', $existing_data) === 'Tidak' ? 'selected' : '' ?>>Tidak Ada</option>
        </select>
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Menggunakan otot bantu dalam bernafas</strong></label>
    <div class="col-sm-10">
        <select class="form-select" name="ototbantu" required <?= $ro ?>>
            <option value="">Pilih</option>
            <option value="Ya" <?= val('ototbantu', $existing_data) === 'Ya' ? 'selected' : '' ?>>Ya</option>
            <option value="Never" <?= val('ototbantu', $existing_data) === 'Tidak' ? 'selected' : '' ?>>Tidak</option>
        </select>
    </div>
</div>
<div class="row mb-2">
    <label class="col-sm-12 col-form-label"><strong>Circulation (Sirkulasi)</strong></label>
</div> 

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Nadi</strong></label>
    <div class="col-sm-10">
        <div class="input-group">
            <input type="text" class="form-control" name="nadi1" value="<?= htmlspecialchars($existing_data['nadi'] ?? '') ?>" <?= $ro ?>>
            <span class="input-group-text">x/menit</span>
        </div>
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Tekanan Darah</strong></label>
    <div class="col-sm-10">
        <div class="input-group">
            <input type="text" class="form-control" name="tekanandarah1" value="<?= htmlspecialchars($existing_data['tekanandarah'] ?? '') ?>" <?= $ro ?>>
            <span class="input-group-text">mmHg</span>
        </div>
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>CVP</strong></label>
    <div class="col-sm-10">
        <input type="text" class="form-control" name="cvp" value="<?= htmlspecialchars($existing_data['cvp'] ?? '') ?>" <?= $ro ?>>
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>CRT</strong></label>
    <div class="col-sm-10">
        <div class="input-group">
            <input type="text" class="form-control" name="crt" value="<?= htmlspecialchars($existing_data['crt'] ?? '') ?>" <?= $ro ?>>
            <span class="input-group-text">detik</span>
        </div>
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Suara Jantung</strong></label>
    <div class="col-sm-10">
        <input type="text" class="form-control" name="suarajantung" value="<?= htmlspecialchars($existing_data['suarajantung'] ?? '') ?>" <?= $ro ?>>
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Perfusi Perifer</strong></label>
    <div class="col-sm-10">
        <input type="text" class="form-control" name="perfusiperifer" value="<?= htmlspecialchars($existing_data['perfusiperifer'] ?? '') ?>" <?= $ro ?>>
    </div>
</div>

<div class="row mb-2">
    <label class="col-sm-12 col-form-label"><strong>Disability (Neurologi)</strong></label>
</div> 

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Tingkat Kesadaran</strong></label>
    <div class="col-sm-10">
        <input type="text" class="form-control" name="tingkatkesadaran" value="<?= htmlspecialchars($existing_data['tingkatkesadaran'] ?? '') ?>" <?= $ro ?>>
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
    <label class="col-sm-2 col-form-label"><strong>Pupil</strong></label>
    <div class="col-sm-10">
        <input type="text" class="form-control" name="pupil" value="<?= htmlspecialchars($existing_data['pupil'] ?? '') ?>" <?= $ro ?>>
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Respon Motorik</strong></label>
    <div class="col-sm-10">
        <input type="text" class="form-control" name="responmotorik" value="<?= htmlspecialchars($existing_data['responmotorik'] ?? '') ?>" <?= $ro ?>>
    </div>
</div>

<div class="row mb-2">
    <label class="col-sm-12 col-form-label"><strong>Exposure</strong></label>
</div> 

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Suhu</strong></label>
    <div class="col-sm-10">
        <div class="input-group">
            <input type="text" class="form-control" name="suhu1" value="<?= htmlspecialchars($existing_data['suhu'] ?? '') ?>" <?= $ro ?>>
            <span class="input-group-text">°C</span>
        </div>
    </div>
</div> 

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Lainnya</strong></label>
    <div class="col-sm-10">
        <input type="text" class="form-control" name="lainnya" value="<?= htmlspecialchars($existing_data['lainnya'] ?? '') ?>" <?= $ro ?>>
    </div>
</div> 

<div class="row mb-2">
    <label class="col-sm-12 col-form-label"><strong>Fluid (Cairan dan Elektrolit)</strong></label>
</div> 

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Infuse yang Terpasang</strong></label>
    <div class="col-sm-10">
       <input type="text" class="form-control" name="infuse" value="<?= htmlspecialchars($existing_data['infuse'] ?? '') ?>" <?= $ro ?>>
    </div>
</div> 

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Cairan</strong></label>
    <div class="col-sm-10">
        <input type="text" class="form-control" name="cairan" value="<?= htmlspecialchars($existing_data['cairan'] ?? '') ?>" <?= $ro ?>>
    </div>
</div> 

<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Jumlah Tetesan</strong></label>
    <div class="col-sm-10">
        <div class="input-group">
            <input type="text" class="form-control" name="jumlahtetesan" value="<?= htmlspecialchars($existing_data['jumlahtetesan'] ?? '') ?>" <?= $ro ?>>
            <span class="input-group-text">x/m</span>
        </div>
    </div>
</div>

                <!-- Bagian Button -->    
                <div class="row mb-3">
                    <div class="col-sm-11 justify-content-end d-flex">
                        <button type="submit" name="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div> 

                <style>
                
                .table-primarysurvey {
                    table-layout: fixed;
                    width: 100;
                }

                .table-primarysurvey td,
                .table-primarysurvey th {
                    word-wrap: break-word;
                    white-space: normal;
                    vertical-align: top;
                }

                </style>

                <h5 class="card-title"><strong>Primary Survey</strong></h5>

                <table class="table table-bordered table-primarysurvey">
                   
                <tbody>

                <tr>
                <td><strong>Pengumpulan Data</strong></td>
                <td>Tanggal: <?= $row['tanggal'] ?? ''; ?></td>
                </tr>

                <tr>
                <td rowspan="2"><strong>Airways (Jalan Nafas)</strong></td>
                <td>Sumbatan Jalan Nafas: <?= $row['jalannafas'] ?? ''; ?></td>
                </tr>

                <tr>
                <td>ETT/Trakeostomi: <?= $row['ett'] ?? ''; ?></td>
                </tr>

                <tr>
                <td rowspan="7"><strong>Breathing (Pernafasan)</strong></td>
                <td>Pola Napas: <?= $row['polanafas'] ?? ''; ?></td>
                </tr>

                <tr>
                <td>SpO2: <?= $row['sp02'] ?? ''; ?></td>
                </tr>

                <tr>
                <td>Ventilator (mode/PEEP/FiO2): <?= $row['ventilator'] ?? ''; ?></td>
                </tr>

                <tr>
                <td>Penafasan Cuping Hidung: <?= $row['pernafasancupinghidung'] ?? ''; ?></td>
                </tr>

                <tr>
                <td>Suara Nafas Tambahan: <?= $row['suaranafastambahan'] ?? ''; ?></td>
                </tr>

                <tr>
                <td>Retraksi Dinding Dada: <?= $row['retraksidindingdada'] ?? ''; ?></td>
                </tr>

                <tr>
                <td>Menggunakan Otot Bantu: <?= $row['otobantu'] ?? ''; ?></td>
                </tr>

                <tr>
                <td rowspan="6"><strong>Circulation (Sirkulasi)</strong></td>
                <td>Nadi: <?= $row['nadi'] ?? ''; ?></td>
                </tr>

                <tr>
                <td>Tekanan Darah: <?= $row['tekanandarah'] ?? ''; ?></td>
                </tr>

                <tr>
                <td>CVP: <?= $row['cvp'] ?? ''; ?></td>
                </tr>

                <tr>
                <td>CRT: <?= $row['crt'] ?? ''; ?></td>
                </tr>

                <tr>
                <td>Suara Jantung: <?= $row['suarajantung'] ?? ''; ?></td>
                </tr>

                <tr>
                <td>Perfusi Perifer: <?= $row['perfusiperifer'] ?? ''; ?></td>
                </tr>

                <tr>
                <td rowspan="4"><strong>Disability (Neurologi)</strong></td>
                <td>Tingkat Kesadaran: <?= $row['tingkatkesadaran'] ?? ''; ?></td>
                </tr>

                <tr>
                <td>Glasgow Coma Scale (GCS):
                    E<?= $row['e'] ?? '0'; ?>
                    M<?= $row['m'] ?? '0'; ?>
                    V<?= $row['v'] ?? '0'; ?>
                </td> 
                </tr>

                <tr>
                <td>Pupil: <?= $row['pupil'] ?? ''; ?></td>
                </tr>

                <tr>
                <td>Respon Motorik: <?= $row['responmotorik'] ?? ''; ?></td>
                </tr>

                <tr>
                <td rowspan="2"><strong>Exposure</strong></td>
                <td>Suhu: <?= $row['suhu'] ?? ''; ?></td>
                </tr>

                <tr>
                <td>Lainnya: <?= $row['lainnya'] ?? ''; ?></td>
                </tr>

                <tr>
                <td rowspan="3"><strong>Fluid (Cairan dan Elektrolit)</strong></td>
                <td>Infuse yang Terpasang: <?= $row['infuse'] ?? ''; ?></td>
                </tr>

                <tr>
                <td>Cairan: <?= $row['cairan'] ?? ''; ?></td>
                </tr>

                <tr>
                <td>Jumlah Tetesan: <?= $row['jumlahtetesan'] ?? ''; ?></td>
                </tr>

                </tbody>
                </table>

            
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

                        


