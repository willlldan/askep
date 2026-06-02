<?php
$form_id       = 24;
$section_name  = 'resume';
$section_label = 'Format Resume Keperawatan Dasar';
include dirname(__DIR__, 2) . '/partials/init_section.php';

$tgl_pengkajian = $submission['tanggal_pengkajian'] ?? '';
$rs_ruangan     = $submission['rs_ruangan']         ?? '';

// Load existing dynamic rows
$existing_lab = $existing_data['lab'] ?? [];
$existing_ekg = $existing_data['ekg'] ?? '';

// =============================================
// HANDLE POST - MAHASISWA SIMPAN DATA
// =============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $level === 'Mahasiswa') {
    if (isLocked($submission)) {
        redirectWithMessage($_SERVER['REQUEST_URI'], 'error', 'Data tidak dapat diubah karena sedang dalam proses review.');
    }

    $tgl_pengkajian = $_POST['tglpengkajian'] ?? '';
    $rs_ruangan     = $_POST['rsruangan']     ?? '';

    // Upload EKG
    $path_ekg = $existing_data['ekg'] ?? '';
    if (!empty($_FILES['ekg']['name'])) {
        $upload = uploadImage($_FILES['ekg'], 'uploads/ekg/', 2);
        if ($upload['success']) {
            if (!empty($path_ekg) && file_exists($path_ekg)) {
                unlink($path_ekg);
            }
            $path_ekg = $upload['path'];
        } else {
            redirectWithMessage($_SERVER['REQUEST_URI'], 'error', $upload['error']);
            exit;
        }
    }

    // Proses dynamic rows lab
    $lab = [];
    if (!empty($_POST['lab'])) {
        foreach ($_POST['lab'] as $row) {
            if (empty($row['pemeriksaan']) && empty($row['hasil']) && empty($row['nilai_normal'])) {
                continue;
            }
            $lab[] = [
                'pemeriksaan'  => $row['pemeriksaan']  ?? '',
                'hasil'        => $row['hasil']        ?? '',
                'nilai_normal' => $row['nilai_normal'] ?? '',
            ];
        }
    }

    $data = [
        // Dynamic
        'lab'  => $lab,
        'ekg'  => $path_ekg,

        // 1. Biodata Klien
        'nama_anak'   => $_POST['nama_anak']   ?? '',
        'jenis_kelamin' => $_POST['jenis_kelamin'] ?? '',
        'umur'        => $_POST['umur']        ?? '',
        'agama'       => $_POST['agama']       ?? '',
        'status'      => $_POST['status']      ?? '',
        'pendidikan'  => $_POST['pendidikan']  ?? '',
        'pekerjaan'   => $_POST['pekerjaan']   ?? '',
        'alamat'      => $_POST['alamat']      ?? '',
        'kunjungan'   => $_POST['kunjungan']   ?? '',

        // 2. Diagnosa Medis
        'diagnosa_medis' => $_POST['diagnosa_medis'] ?? '',

        // 3-4. Keluhan & Riwayat
        'keluhan_utama'              => $_POST['keluhan_utama']              ?? '',
        'riwayat_keluhan_saat_ini'   => $_POST['riwayat_keluhan_saat_ini']   ?? '',
        'riwayat_kesehatan_yang_lalu' => $_POST['riwayat_kesehatan_yang_lalu'] ?? '',

        // 5. Tanda Vital
        'tekanan_darah' => $_POST['tekanan_darah'] ?? '',
        'nadi'          => $_POST['nadi']          ?? '',
        'suhu'          => $_POST['suhu']          ?? '',
        'pernapasan'    => $_POST['pernapasan']    ?? '',

        // 6. Antropometri
        'tb'  => $_POST['tb']  ?? '',
        'bb'  => $_POST['bb']  ?? '',
        'IMT' => $_POST['IMT'] ?? '',

        // 7. Pemeriksaan Fisik
        'pemeriksaan_fisik' => $_POST['pemeriksaan_fisik'] ?? '',

        // 8. Penunjang
        'radiologi' => $_POST['radiologi'] ?? '',
        'usg'       => $_POST['usg']       ?? '',
        'ct'        => $_POST['ct']        ?? '',

        // 9. Terapi
        'terapi' => $_POST['terapi'] ?? '',
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
?>

<main id="main" class="main">

    <?php include "askep/resume_poli_rsal/tab.php"; ?>

    <section class="section dashboard">

        <?php include dirname(__DIR__, 2) . '/partials/notifikasi.php'; ?>
        <?php include dirname(__DIR__, 2) . '/partials/status_section.php'; ?>

        <!-- Header Form -->
        <div class="card mt-3">
            <div class="card-body">
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

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

                    <h5 class="card-title mt-3"><strong>Format Resume Keperawatan</strong></h5>

                    <!-- 1. Biodata Klien -->
                    <div class="row mb-2 mt-3">
                        <label class="col-sm-12 text-primary"><strong>1. Biodata Klien</strong></label>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Nama Klien</strong></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="nama_anak"
                                value="<?= val('nama_anak', $existing_data) ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Jenis Kelamin</strong></label>
                        <div class="col-sm-10">
                            <select class="form-select" name="jenis_kelamin" <?= $ro_select ?>>
                                <option value="">Pilih</option>
                                <option value="Laki-laki" <?= val('jenis_kelamin', $existing_data) === 'Laki-laki' ? 'selected' : '' ?>>Laki-laki</option>
                                <option value="Perempuan" <?= val('jenis_kelamin', $existing_data) === 'Perempuan' ? 'selected' : '' ?>>Perempuan</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Umur</strong></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="umur"
                                value="<?= val('umur', $existing_data) ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Agama</strong></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="agama"
                                value="<?= val('agama', $existing_data) ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Status Perkawinan</strong></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="status"
                                value="<?= val('status', $existing_data) ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Pendidikan</strong></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="pendidikan"
                                value="<?= val('pendidikan', $existing_data) ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Pekerjaan</strong></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="pekerjaan"
                                value="<?= val('pekerjaan', $existing_data) ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Alamat</strong></label>
                        <div class="col-sm-10">
                            <textarea name="alamat" class="form-control" rows="3"
                                style="overflow:hidden; resize:none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro_disabled ?>><?= val('alamat', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Kunjungan Ke</strong></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="kunjungan"
                                value="<?= val('kunjungan', $existing_data) ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <!-- Diagnosa Medis -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Diagnosa Medis</strong></label>
                        <div class="col-sm-10">
                            <textarea name="diagnosa_medis" class="form-control" rows="3"
                                style="overflow:hidden; resize:none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro_disabled ?>><?= val('diagnosa_medis', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- 2. Keluhan Utama -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label text-primary"><strong>2. Keluhan Utama</strong></label>
                        <div class="col-sm-10">
                            <textarea name="keluhan_utama" class="form-control" rows="3"
                                style="overflow:hidden; resize:none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro_disabled ?>><?= val('keluhan_utama', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- 3. Riwayat Kesehatan Saat Ini -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label text-primary"><strong>3. Riwayat Kesehatan Saat Ini</strong></label>
                        <div class="col-sm-10">
                            <textarea name="riwayat_keluhan_saat_ini" class="form-control" rows="3"
                                style="overflow:hidden; resize:none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro_disabled ?>><?= val('riwayat_keluhan_saat_ini', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- 4. Riwayat Kesehatan yang Lalu -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label text-primary"><strong>4. Riwayat Kesehatan yang Lalu</strong></label>
                        <div class="col-sm-10">
                            <textarea name="riwayat_kesehatan_yang_lalu" class="form-control" rows="3"
                                style="overflow:hidden; resize:none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro_disabled ?>><?= val('riwayat_kesehatan_yang_lalu', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- 5. Tanda-tanda Vital -->
                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary"><strong>5. Tanda-tanda Vital</strong></label>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-2 col-form-label"><strong>Tekanan Darah</strong></label>
                        <div class="col-sm-4">
                            <div class="input-group">
                                <input type="text" class="form-control" name="tekanan_darah"
                                    value="<?= val('tekanan_darah', $existing_data) ?>" <?= $ro ?>>
                                <span class="input-group-text">mmHg</span>
                            </div>
                        </div>
                        <label class="col-sm-2 col-form-label"><strong>Frekuensi Nadi</strong></label>
                        <div class="col-sm-4">
                            <div class="input-group">
                                <input type="text" class="form-control" name="nadi"
                                    value="<?= val('nadi', $existing_data) ?>" <?= $ro ?>>
                                <span class="input-group-text">x/menit</span>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-2 col-form-label"><strong>Suhu Tubuh</strong></label>
                        <div class="col-sm-4">
                            <div class="input-group">
                                <input type="text" class="form-control" name="suhu"
                                    value="<?= val('suhu', $existing_data) ?>" <?= $ro ?>>
                                <span class="input-group-text">°C</span>
                            </div>
                        </div>
                        <label class="col-sm-2 col-form-label"><strong>Frekuensi Pernapasan</strong></label>
                        <div class="col-sm-4">
                            <div class="input-group">
                                <input type="text" class="form-control" name="pernapasan"
                                    value="<?= val('pernapasan', $existing_data) ?>" <?= $ro ?>>
                                <span class="input-group-text">x/menit</span>
                            </div>
                        </div>
                    </div>

                    <!-- 6. Pemeriksaan Antropometri -->
                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary"><strong>6. Pemeriksaan Antropometri</strong></label>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-2 col-form-label"><strong>Tinggi Badan</strong></label>
                        <div class="col-sm-4">
                            <div class="input-group">
                                <input type="text" class="form-control" name="tb"
                                    value="<?= val('tb', $existing_data) ?>" <?= $ro ?>>
                                <span class="input-group-text">cm</span>
                            </div>
                        </div>
                        <label class="col-sm-2 col-form-label"><strong>Berat Badan</strong></label>
                        <div class="col-sm-4">
                            <div class="input-group">
                                <input type="text" class="form-control" name="bb"
                                    value="<?= val('bb', $existing_data) ?>" <?= $ro ?>>
                                <span class="input-group-text">kg</span>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-2 col-form-label"><strong>IMT</strong></label>
                        <div class="col-sm-4">
                            <div class="input-group">
                                <input type="text" class="form-control" name="IMT"
                                    value="<?= val('IMT', $existing_data) ?>" <?= $ro ?>>
                                <span class="input-group-text">kg/m²</span>
                            </div>
                        </div>
                    </div>

                    <!-- 7. Pemeriksaan Fisik -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label text-primary"><strong>7. Pemeriksaan Fisik</strong></label>
                        <div class="col-sm-10">
                            <small class="form-text text-danger">(secara umum dan singkat)</small>
                            <textarea name="pemeriksaan_fisik" class="form-control" rows="3"
                                style="overflow:hidden; resize:none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro_disabled ?>><?= val('pemeriksaan_fisik', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- 8. Pemeriksaan Penunjang -->
                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary"><strong>8. Pemeriksaan Penunjang</strong></label>
                    </div>

                    <!-- a. Laboratorium -->
                    <p class="fw-bold mb-2">a. Laboratorium</p>
                    <table class="table table-bordered" id="tabel-lab">
                        <thead>
                            <tr>
                                <th class="text-center" style="width:40px">No</th>
                                <th class="text-center">Pemeriksaan</th>
                                <th class="text-center">Hasil</th>
                                <th class="text-center">Nilai Normal</th>
                                <?php if (!$is_readonly): ?>
                                    <th class="text-center" style="width:60px">Aksi</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody id="tbody-lab"></tbody>
                    </table>
                    <?php if (!$is_readonly): ?>
                        <div class="row mb-4">
                            <div class="col-sm-12 d-flex justify-content-end">
                                <button type="button" class="btn btn-primary btn-sm" id="btn-tambah-lab"
                                    onclick="tambahRowLab()">+ Tambah Pemeriksaan</button>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- b. Radiologi -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>b. Radiologi</strong></label>
                        <div class="col-sm-10">
                            <textarea name="radiologi" class="form-control" rows="3"
                                style="overflow:hidden; resize:none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro_disabled ?>><?= val('radiologi', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- c. EKG -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>c. EKG</strong></label>
                        <div class="col-sm-10">
                            <?php if (!empty($existing_ekg)): ?>
                                <img src="<?= htmlspecialchars($existing_ekg) ?>"
                                    class="img-fluid rounded border mb-2"
                                    style="max-height:400px;">
                            <?php endif; ?>
                            <?php if (!$is_readonly): ?>
                                <input type="file" class="form-control" name="ekg"
                                    accept="image/jpeg,image/png,image/webp">
                                <small class="text-muted">Format: JPG, PNG, WebP. Maks 2MB.</small>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- d. USG -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>d. USG</strong></label>
                        <div class="col-sm-10">
                            <textarea name="usg" class="form-control" rows="3"
                                style="overflow:hidden; resize:none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro_disabled ?>><?= val('usg', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- e. CT Scan -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>e. CT Scan</strong></label>
                        <div class="col-sm-10">
                            <textarea name="ct" class="form-control" rows="3"
                                style="overflow:hidden; resize:none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro_disabled ?>><?= val('ct', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- 9. Terapi/Obat -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label text-primary"><strong>9. Terapi/Obat</strong></label>
                        <div class="col-sm-10">
                            <textarea name="terapi" class="form-control" rows="3"
                                style="overflow:hidden; resize:none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro_disabled ?>><?= val('terapi', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- Tombol Simpan -->
                    <?php if (!$is_dosen): ?>
                        <div class="row mb-3">
                            <div class="col-sm-12 d-flex justify-content-end">
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

<script>
    let rowLabCount = 1;
    const existingLab = <?= json_encode($existing_lab) ?>;
    const isReadonly = <?= json_encode($is_readonly) ?>;

    function tambahRowLab(data = null) {
        const tbody = document.getElementById('tbody-lab');
        const index = rowLabCount;
        const ro = isReadonly ? 'readonly' : '';
        const dis = isReadonly ? 'disabled' : '';
        const row = document.createElement('tr');
        row.innerHTML = `
            <td class="text-center align-middle">${index}</td>
            <td><input type="text" class="form-control form-control-sm" name="lab[${index}][pemeriksaan]" value="${data?.pemeriksaan ?? ''}" ${ro}></td>
            <td><input type="text" class="form-control form-control-sm" name="lab[${index}][hasil]" value="${data?.hasil ?? ''}" ${ro}></td>
            <td><input type="text" class="form-control form-control-sm" name="lab[${index}][nilai_normal]" value="${data?.nilai_normal ?? ''}" ${ro}></td>
            ${!isReadonly ? `<td class="text-center align-middle"><button type="button" class="btn btn-danger btn-sm" onclick="hapusRow(this)">x</button></td>` : ''}
        `;
        tbody.appendChild(row);
        rowLabCount++;
    }

    function hapusRow(btn) {
        btn.closest('tr').remove();
    }

    window.addEventListener('load', function() {
        if (existingLab && existingLab.length > 0) {
            existingLab.forEach(row => tambahRowLab(row));
        } else {
            tambahRowLab();
        }
    });
</script>