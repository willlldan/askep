<?php
$form_id       = 19;
$section_name  = 'pengkajian';
$section_label = 'Pengkajian';
include dirname(__DIR__, 2) . '/partials/init_section.php';


$tgl_pengkajian = $submission['tanggal_pengkajian'] ?? '';
$rs_ruangan     = $submission['rs_ruangan'] ?? '';

// =============================================
// HANDLE POST - MAHASISWA SIMPAN DATA
// =============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $level === 'Mahasiswa') {

    if (isLocked($submission)) {
        redirectWithMessage($_SERVER['REQUEST_URI'], 'error', 'Data tidak dapat diubah karena sedang dalam proses review.');
    }

    $tgl_pengkajian = $_POST['tglpengkajian'] ?? '';
    $rs_ruangan     = $_POST['rsruangan'] ?? '';

    $data = [

        // =========================
        // A. IDENTITAS KLIEN
        // =========================
        'nama_klien'        => $_POST['nama_klien'] ?? '',
        'ttl_umur'          => $_POST['ttl_umur'] ?? '',
        'jenis_kelamin'     => $_POST['jenis_kelamin'] ?? '',
        'status_perkawinan' => $_POST['status_perkawinan'] ?? '',
        'agama'             => $_POST['agama'] ?? '',
        'pendidikan'        => $_POST['pendidikan'] ?? '',
        'alamat'            => $_POST['alamat'] ?? '',
        'tgl_masuk_rs'      => $_POST['tgl_masuk_rs'] ?? '',
        'diagnosa_medis'    => $_POST['diagnosa_medis'] ?? '',
        'tgl_pengkajian1'   => $_POST['tgl_pengkajian1'] ?? '',
        'golongan_darah'    => $_POST['golongan_darah'] ?? '',
        'no_registrasi'     => $_POST['no_registrasi'] ?? '',
        'ruangan'           => $_POST['ruangan'] ?? '',


        // =========================
        // B. IDENTITAS PENANGGUNG JAWAB
        // =========================
        'nama_klienpj'      => $_POST['nama_klienpj'] ?? '',
        'ttl_umurpj'        => $_POST['ttl_umurpj'] ?? '',
        'jenis_kelaminpj'   => $_POST['jenis_kelaminpj'] ?? '',
        'hubungan_klien'    => $_POST['hubungan_klien'] ?? '',
        'agamapj'           => $_POST['agamapj'] ?? '',
        'pendidikanpj'      => $_POST['pendidikanpj'] ?? '',
        'pekerjaanpj'       => $_POST['pekerjaanpj'] ?? '',
        'alamatpj'          => $_POST['alamatpj'] ?? '',


        // =========================
        // C. KEADAAN UMUM
        // =========================

        // Tanda Vital
        'nadi'              => $_POST['nadi'] ?? '',
        'pernafasan'        => $_POST['pernafasan'] ?? '',
        'td'                => $_POST['td'] ?? '',
        'suhu'              => $_POST['suhu'] ?? '',

        // Glasgow Coma Scale (GCS)
        'm'                 => $_POST['m'] ?? '',
        'v'                 => $_POST['v'] ?? '',
        'e'                 => $_POST['e'] ?? '',



        // Antropometri
        'bb_sebelum'        => $_POST['bb_sebelum'] ?? '',
        'bb_saat_sakit'     => $_POST['bb_saat_sakit'] ?? '',
        'lingkar_lengan'    => $_POST['lingkar_lengan'] ?? '',
        'tinggi_badan'      => $_POST['tinggi_badan'] ?? '',
        'imt'               => $_POST['imt'] ?? '',


        // =========================
        // D. RIWAYAT KESEHATAN
        // =========================
        'alasan_masuk_rs'           => $_POST['alasan_masuk_rs'] ?? '',
        'keluhan_utama'             => $_POST['keluhan_utama'] ?? '',
        'riwayat_penyakit_sekarang' => $_POST['riwayat_penyakit_sekarang'] ?? '',
        'riwayat_pernah_dialami'    => $_POST['riwayat_pernah_dialami'] ?? '',
        'riwayat_kesehatan_keluarga' => $_POST['riwayat_kesehatan_keluarga'] ?? '',
        'genogram'                  => $_POST['genogram'] ?? '',
        'kesadaran'                  => $_POST['kesadaran'] ?? '',

    ];



    if (!$submission) {
        $submission_id = createSubmission($user_id, $form_id, $tgl_pengkajian, $rs_ruangan, $mysqli);
    } else {
        $submission_id = $submission['id'];
        updateSubmissionHeader($submission_id, $tgl_pengkajian, $rs_ruangan, $mysqli);
    }


    saveSection($submission_id, $section_name, $section_label, $data, $mysqli);
    updateSubmissionStatus($submission_id, $form_id, $mysqli);
    redirectWithMessage($_SERVER['REQUEST_URI'], 'success', 'Data berhasil disimpan.');
}

$kesadaran_checked = isset($existing_data['kesadaran'])
    ? (array)$existing_data['kesadaran']
    : [];
?>

<main id="main" class="main">
    <?php include "keperawatan-dasar/askep_ruang_damar/tab.php"; ?>
    <?php include dirname(__DIR__, 2) . '/partials/notifikasi.php'; ?>
    <?php include dirname(__DIR__, 2) . '/partials/status_section.php'; ?>
    <section class="section dashboard">
        <div class="card">
            <div class="card-body">
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data ">

                    <h5 class="card-title mb-1"><strong>1. Pengumpulan Data</strong></h5>
                    <!-- General Form Elements -->
                    <div class="row mb-3 mt-3">
                        <label class="col-sm-2 col-form-label"><strong>Tanggal Pengkajian</strong></label>
                        <div class="col-sm-10">
                            <input type="date" class="form-control" name="tglpengkajian"
                                value="<?= htmlspecialchars($tgl_pengkajian) ?>" <?= $ro ?> required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>RS/Ruangan</strong></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="rsruangan"
                                value="<?= htmlspecialchars($rs_ruangan) ?>" <?= $ro ?> required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label text-primary"><strong>a. Identitas Klien </strong></label>
                    </div>

                    <div class="row mb-3">
                        <label for="nama_klien" class="col-sm-2 col-form-label"><strong> Nama (Inisial) </strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="nama_klien" value="<?= val('nama_klien', $existing_data) ?>" <?= $ro ?>>

                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="ttl_umur" class="col-sm-2 col-form-label"><strong>Tempat/Tgl Lahir/Umur</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="ttl_umur" value="<?= val('ttl_umur', $existing_data) ?>" <?= $ro ?>>

                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="jenis_kelamin" class="col-sm-2 col-form-label"><strong>Jenis Kelamin</strong></label>
                        <div class="col-sm-9">
                            <select class="form-control" name="jenis_kelamin" <?= $ro_select ?>>
                                <option value="">Pilih </option>
                                <option value="Laki-laki" <?= val('jenis_kelamin', $existing_data) === 'Laki-laki' ? 'selected' : '' ?>>Laki-laki</option>
                                <option value="Perempuan" <?= val('jenis_kelamin', $existing_data) === 'Perempuan' ? 'selected' : '' ?>>Perempuan</option>
                            </select>

                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="status_perkawinan" class="col-sm-2 col-form-label"><strong>Status Perkawinan</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="status_perkawinan" value="<?= val('status_perkawinan', $existing_data) ?>" <?= $ro ?>>

                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="agama" class="col-sm-2 col-form-label"><strong>Agama</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="agama" value="<?= val('agama', $existing_data) ?>" <?= $ro ?>>

                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="pendidikan" class="col-sm-2 col-form-label"><strong>Pendidikan</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="pendidikan" value="<?= val('pendidikan', $existing_data) ?>" <?= $ro ?>>

                        </div>
                    </div>



                    <div class="row mb-3">
                        <label for="pekerjaan" class="col-sm-2 col-form-label"><strong>Alamat</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="alamat" value="<?= val('alamat', $existing_data) ?>" <?= $ro ?>>

                        </div>
                    </div>




                    <div class="row mb-3">
                        <label for="tgl_masuk_rs" class="col-sm-2 col-form-label"><strong>Tanggal Masuk RS</strong></label>
                        <div class="col-sm-9">
                            <input type="date" class="form-control" name="tgl_masuk_rs" value="<?= val('tgl_masuk_rs', $existing_data) ?>" <?= $ro ?>>

                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="diagnosa_medis" class="col-sm-2 col-form-label"><strong>Diagnosa Medis</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="diagnosa_medis" value="<?= val('diagnosa_medis', $existing_data) ?>" <?= $ro ?>>

                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="tgl_pengkajian" class="col-sm-2 col-form-label"><strong>Tanggal Pengkajian</strong></label>
                        <div class="col-sm-9">
                            <input type="date" class="form-control" name="tgl_pengkajian1" value="<?= val('tgl_pengkajian1', $existing_data) ?>" <?= $ro ?>>

                        </div>
                    </div>


                    <div class="row mb-3">
                        <label for="golongan_darah" class="col-sm-2 col-form-label"><strong>Golongan Darah</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="golongan_darah" value="<?= val('golongan_darah', $existing_data) ?>" <?= $ro ?>>

                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="no_registrasi" class="col-sm-2 col-form-label"><strong>No Registrasi</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="no_registrasi" value="<?= val('no_registrasi', $existing_data) ?>" <?= $ro ?>>

                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="ruangan" class="col-sm-2 col-form-label"><strong>Ruang Perawatan</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="ruangan" value="<?= val('ruangan', $existing_data) ?>" <?= $ro ?>>

                        </div>
                    </div>


                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary"><strong>b. Identitas Penanggung Jawab</strong></label>
                    </div>

                    <div class="row mb-3">
                        <label for="nama_klien" class="col-sm-2 col-form-label"><strong> Nama (Inisial) </strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="nama_klienpj" value="<?= val('nama_klienpj', $existing_data) ?>" <?= $ro ?>>

                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="ttl_umur" class="col-sm-2 col-form-label"><strong>Tempat/Tgl Lahir/Umur</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="ttl_umurpj" value="<?= val('ttl_umurpj', $existing_data) ?>" <?= $ro ?>>

                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="jenis_kelamin" class="col-sm-2 col-form-label"><strong>Jenis Kelamin</strong></label>
                        <div class="col-sm-9">
                            <select class="form-control" name="jenis_kelaminpj" <?= $ro_select ?>>
                                <option value="">Pilih </option>
                                <option value="Laki-laki" <?= val('jenis_kelaminpj', $existing_data) === 'Laki-laki' ? 'selected' : '' ?>>Laki-laki</option>
                                <option value="Perempuan" <?= val('jenis_kelaminpj', $existing_data) === 'Perempuan' ? 'selected' : '' ?>>Perempuan</option>
                            </select>

                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="hubungan_klien" class="col-sm-2 col-form-label"><strong>Hubungan dengan Klien</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="hubungan_klien" value="<?= val('hubungan_klien', $existing_data) ?>" <?= $ro ?>">

                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="agama" class="col-sm-2 col-form-label"><strong>Agama</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="agamapj" value="<?= val('agamapj', $existing_data) ?>" <?= $ro ?>>

                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="pendidikan" class="col-sm-2 col-form-label"><strong>Pendidikan</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="pendidikanpj" value="<?= val('pendidikanpj', $existing_data) ?>" <?= $ro ?>>

                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="pekerjaan" class="col-sm-2 col-form-label"><strong>Pekerjaan</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="pekerjaanpj" value="<?= val('pekerjaanpj', $existing_data) ?>" <?= $ro ?>>

                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="alamat" class="col-sm-2 col-form-label"><strong>Alamat</strong></label>
                        <div class="col-sm-9">
                            <textarea class="form-control" rows="3" name="alamatpj" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('alamatpj', $existing_data) ?></textarea>
                        </div>

                    </div>





                    <!-- A TANDA VITAL -->
                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary"><strong>c. Keadaan Umum</strong></label>
                    </div>
                    <div class="row mb-2">
                        <label class="col-sm-12"><strong>Tanda-tanda Vital</strong></label>
                    </div>

                    <!-- TD -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Nadi</strong></label>

                        <div class="col-sm-9">
                            <div class="input-group">
                                <input type="text" class="form-control" name="nadi" value="<?= val('nadi', $existing_data) ?>" <?= $ro ?>>
                                <span class="input-group-text">/menit</span>
                            </div>


                        </div>
                    </div>



                    <!-- Pernafasan -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Pernafasan</strong></label>

                        <div class="col-sm-9">
                            <div class="input-group">
                                <input type="text" class="form-control" name="pernafasan" value="<?= val('pernafasan', $existing_data) ?>" <?= $ro ?>>
                                <span class="input-group-text">x/menit</span>
                            </div>


                        </div>
                    </div>
                    <!-- TD (Tekanan Darah) -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Tekanan Darah</strong></label>

                        <div class="col-sm-9">
                            <div class="input-group">
                                <input type="text" class="form-control" name="td" value="<?= val('td', $existing_data) ?>" <?= $ro ?>>
                                <span class="input-group-text">mmHg</span>
                            </div>


                        </div>
                    </div>

                    <!-- Suhu -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Suhu</strong></label>

                        <div class="col-sm-9">
                            <div class="input-group">
                                <input type="text" class="form-control" name="suhu" value="<?= val('suhu', $existing_data) ?>" <?= $ro ?>>
                                <span class="input-group-text">°C</span>
                            </div>

                        </div>
                    </div>
                    <!-- B KESADARAN -->
                    <div class="row mb-2">
                        <label class="col-sm-12"><strong>Kesadaran</strong></label>
                    </div>
                    <!-- GCS -->

                    <div class="row mb-3">

                        <label class="col-sm-2 col-form-label"><strong>Glasgow Coma Scale (GCS)</strong></label>
                               <div class="col-sm-9">
    <div class="row">
        <!-- M -->
        <div class="col-md-4 d-flex align-items-center">
            <label class="me-2"><strong>M</strong></label>
            <textarea class="form-control" 
                      name="m" 
                      rows="1" 
                      style="overflow:hidden; resize:none;" 
                      oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" 
                      <?= $ro ?>><?= val('m', $existing_data) ?></textarea>
        </div>

        <!-- V -->
        <div class="col-md-4 d-flex align-items-center">
            <label class="me-2"><strong>V</strong></label>
            <textarea class="form-control" 
                      name="v" 
                      rows="1" 
                      style="overflow:hidden; resize:none;" 
                      oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" 
                      <?= $ro ?>><?= val('v', $existing_data) ?></textarea>
        </div>

        <!-- E -->
        <div class="col-md-4 d-flex align-items-center">
            <label class="me-2"><strong>E</strong></label>
            <textarea class="form-control" 
                      name="e" 
                      rows="1" 
                      style="overflow:hidden; resize:none;" 
                      oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" 
                      <?= $ro ?>><?= val('e', $existing_data) ?></textarea>
        </div>
    </div>
</div>



                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-2">
                            <strong>Tingkat Kesadaran</strong>
                        </div>
                        <div class="col-sm-10 d-flex flex-wrap align-items-center">
                            <div class="form-check form-check-inline me-4">
                                <input class="form-check-input" type="radio" name="kesadaran" id="kesadaran_kompos" value="Kompos Mentis" <?= $ro_disabled ?> <?= ($existing_data['kesadaran'] ?? '') === 'Kompos Mentis' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="kesadaran_kompos">Kompos Mentis</label>
                            </div>
                            <div class="form-check form-check-inline me-4">
                                <input class="form-check-input" type="radio" name="kesadaran" id="kesadaran_apatis" value="Apatis" <?= $ro_disabled ?> <?= ($existing_data['kesadaran'] ?? '') === 'Apatis' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="kesadaran_apatis">Apatis</label>
                            </div>
                            <div class="form-check form-check-inline me-4">
                                <input class="form-check-input" type="radio" name="kesadaran" id="kesadaran_somnolent" value="Somnolent" <?= $ro_disabled ?> <?= ($existing_data['kesadaran'] ?? '') === 'Somnolent' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="kesadaran_somnolent">Somnolent</label>
                            </div>
                            <div class="form-check form-check-inline me-4">
                                <input class="form-check-input" type="radio" name="kesadaran" id="kesadaran_stupor" value="Stupor" <?= $ro_disabled ?> <?= ($existing_data['kesadaran'] ?? '') === 'Stupor' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="kesadaran_stupor">Stupor</label>
                            </div>
                            <div class="form-check form-check-inline me-4">
                                <input class="form-check-input" type="radio" name="kesadaran" id="kesadaran_semikoma" value="Semikoma" <?= $ro_disabled ?> <?= ($existing_data['kesadaran'] ?? '') === 'Semikoma' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="kesadaran_semikoma">Semikoma</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="kesadaran" id="kesadaran_koma" value="Koma" <?= $ro_disabled ?> <?= ($existing_data['kesadaran'] ?? '') === 'Koma' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="kesadaran_koma">Koma</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label class="col-sm-12"><strong>Antropomentri</strong></label>
                    </div>


                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>BB Sebelum Sakit</strong></label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <input type="text" class="form-control" name="bb_sebelum"
                                    value="<?= htmlspecialchars($existing_data['bb_sebelum'] ?? '') ?>" <?= $ro ?>>
                                <span class="input-group-text">kg</span>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>BB Saat Sakit</strong></label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <input type="text" class="form-control" name="bb_saat_sakit"
                                    value="<?= htmlspecialchars($existing_data['bb_saat_sakit'] ?? '') ?>" <?= $ro ?>>
                                <span class="input-group-text">kg</span>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Lingkar Lengan Atas</strong></label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <input type="text" class="form-control" name="lingkar_lengan"
                                    value="<?= htmlspecialchars($existing_data['lingkar_lengan'] ?? '') ?>" <?= $ro ?>>
                                <span class="input-group-text">cm</span>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Indeks Massa Tubuh (IMT)</strong></label>

                        <div class="col-sm-9">
                            <div class="input-group">
                                <input type="text" class="form-control" name="imt" value="<?= val('imt', $existing_data) ?>" <?= $ro ?>>
                                <span class="input-group-text">Kg/m²</span>
                            </div>
                        </div>
                    </div>



                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary"><strong>d. Riwayat Kesehatan</strong></label>
                    </div>

                    <!-- A ALASAN MASUK RS -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">
                            <strong>Alasan Masuk Rumah Sakit</strong>
                        </label>

                        <div class="col-sm-9">
                            <textarea class="form-control" rows="3" name="alasan_masuk_rs" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('alasan_masuk_rs', $existing_data) ?></textarea>


                        </div>
                    </div>

                    <!-- B KELUHAN UTAMA -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">
                            <strong>Keluhan Utama</strong>
                        </label>

                        <div class="col-sm-9">
                            <textarea class="form-control" rows="3" name="keluhan_utama" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('keluhan_utama', $existing_data) ?></textarea>


                        </div>
                    </div>

                    <!-- C RIWAYAT KELUHAN UTAMA -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">
                            <strong>Riwayat Penyakit Sekarang</strong>
                        </label>

                        <div class="col-sm-9">
                            <textarea class="form-control" rows="4"
                                name="riwayat_penyakit_sekarang"
                                style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('riwayat_penyakit_sekarang', $existing_data) ?></textarea>


                        </div>
                    </div>

                    <!-- D RIWAYAT KESEHATAN YANG LALU -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">
                            <strong>Riwayat dan Kecelakaan Yang Pernah Dialami</strong>
                        </label>

                        <div class="col-sm-9">
                            <textarea class="form-control" rows="4" name="riwayat_pernah_dialami"
                                style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('riwayat_pernah_dialami', $existing_data) ?></textarea>


                        </div>
                    </div>

                    <!-- E RIWAYAT KESEHATAN KELUARGA -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">
                            <strong>Riwayat Kesehatan Keluarga</strong>
                        </label>

                        <div class="col-sm-9">
                            <textarea class="form-control" rows="4" name="riwayat_kesehatan_keluarga"
                                style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('riwayat_kesehatan_keluarga', $existing_data) ?></textarea>


                        </div>
                    </div>
                    <!-- Bagian Genogram -->
                    <div class="row mb-3">
                        <label for="genogram" class="col-sm-2 col-form-label"><strong>Genogram</strong></label>
                        <div class="col-sm-9">
                            <small class="form-text text-danger">3 generasi</small>

                            <textarea class="form-control" rows="2" name="genogram"
                                style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('genogram', $existing_data) ?></textarea>


                        </div>


                    </div>
            </div>


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

        <?php include dirname(__DIR__, 2) . '/partials/footer_form.php'; ?>

    </section>
</main>