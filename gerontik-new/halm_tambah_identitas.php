<?php
$form_id       = 99;
$section_name  = 'identitas';
$section_label = 'Identitas';
include dirname(__DIR__) . '/partials/init_section.php';

$tgl_pengkajian = $submission['tanggal_pengkajian'] ?? '';
$ruangan        = $submission['rs_ruangan'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $level === 'Mahasiswa') {
    if (isLocked($submission)) {
        redirectWithMessage($_SERVER['REQUEST_URI'], 'error', 'Data tidak dapat diubah karena sedang dalam proses review.');
    }

    $tgl_pengkajian = $_POST['tgl_pengkajian'] ?? '';
    $ruangan        = $_POST['ruangan'] ?? '';

    $data = [
        'nama'               => $_POST['nama']               ?? '',
        'tempat_lahir'       => $_POST['tempat_lahir']       ?? '',
        'tgl_lahir'          => $_POST['tgl_lahir']          ?? '',
        'jenis_kelamin'      => $_POST['jenis_kelamin']      ?? '',
        'status_perkawinan'  => $_POST['status_perkawinan']  ?? '',
        'agama'              => $_POST['agama']              ?? '',
        'pendidikan'         => $_POST['pendidikan']         ?? '',
        'pekerjaan'          => $_POST['pekerjaan']          ?? '',
        'alamat'             => $_POST['alamat']             ?? '',
    ];

    if (!$submission) {
        $submission_id = createSubmission($user_id, $form_id, $tgl_pengkajian, $ruangan, $mysqli);
    } else {
        $submission_id = $submission['id'];
        updateSubmissionHeader($submission_id, $tgl_pengkajian, $ruangan, $mysqli);
    }

    saveSection($submission_id, $section_name, $section_label, $data, $mysqli);
    updateSubmissionStatus($submission_id, $form_id, $mysqli);
    redirectWithMessage($_SERVER['REQUEST_URI'], 'success', 'Data berhasil disimpan.');
}
?>

<main id="main" class="main">
    <?php include "tab.php"; ?>

    <section class="section dashboard">
        <?php include dirname(__DIR__) . '/partials/notifikasi.php'; ?>
        <?php include dirname(__DIR__) . '/partials/status_section.php'; ?>

        <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-3 mt-3">
                        <label class="col-sm-2 col-form-label"><strong>Tanggal Pengkajian</strong></label>
                        <div class="col-sm-9">
                            <input type="date" class="form-control" name="tgl_pengkajian" value="<?= htmlspecialchars($tgl_pengkajian) ?>" <?= $ro ?> required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Ruangan</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="ruangan" value="<?= htmlspecialchars($ruangan) ?>" <?= $ro ?> required>
                        </div>
                    </div>

                    <h5 class="card-title"><strong>1. Identitas</strong></h5>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Nama</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="nama" value="<?= htmlspecialchars(val('nama', $existing_data)) ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Tempat Lahir</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="tempat_lahir" value="<?= htmlspecialchars(val('tempat_lahir', $existing_data)) ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Tanggal Lahir</strong></label>
                        <div class="col-sm-9">
                            <input type="date" class="form-control" name="tgl_lahir" value="<?= htmlspecialchars(val('tgl_lahir', $existing_data)) ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Jenis Kelamin</strong></label>
                        <div class="col-sm-9">
                            <select class="form-select" name="jenis_kelamin" <?= $ro_select ?>>
                                <option value="">-- Pilih --</option>
                                <option value="Laki-laki" <?= val('jenis_kelamin', $existing_data) === 'Laki-laki' ? 'selected' : '' ?>>Laki-laki</option>
                                <option value="Perempuan" <?= val('jenis_kelamin', $existing_data) === 'Perempuan' ? 'selected' : '' ?>>Perempuan</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Status Perkawinan</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="status_perkawinan" value="<?= htmlspecialchars(val('status_perkawinan', $existing_data)) ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Agama</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="agama" value="<?= htmlspecialchars(val('agama', $existing_data)) ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Pendidikan</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="pendidikan" value="<?= htmlspecialchars(val('pendidikan', $existing_data)) ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Pekerjaan</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="pekerjaan" value="<?= htmlspecialchars(val('pekerjaan', $existing_data)) ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Alamat</strong></label>
                        <div class="col-sm-9">
                            <textarea class="form-control" name="alamat" rows="3" <?= $ro ?>><?= val('alamat', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <?php if (!$is_dosen): ?>
                        <div class="row mb-3">
                            <div class="col-sm-12 d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">Simpan Data</button>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </form>

        <?php include dirname(__DIR__) . '/partials/footer_form.php'; ?>
        </div>
        </div>
    </section>
</main>
