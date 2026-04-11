    <?php
    require_once "koneksi.php";
    require_once "utils.php";

    $form_id       = 1;
    $user_id       = $_SESSION['id_user'];
    $section_name  = 'data_demografi';
    $section_label = 'Data Demografi';

    $submission    = getSubmission($user_id, $form_id, $mysqli);
    $existing_data = $submission ? getSectionData($submission['id'], $section_name, $mysqli) : [];

    $tgl_pengkajian = $submission['tanggal_pengkajian'] ?? '';
    $rs_ruangan     = $submission['rs_ruangan'] ?? '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        if (isLocked($submission)) {
            redirectWithMessage($_SERVER['PHP_SELF'], 'error', 'Data tidak dapat diubah karena sedang dalam proses review.');
        }

        $tgl_pengkajian = $_POST['tglpengkajian'] ?? '';
        $rs_ruangan = $_POST['rsruangan'] ?? '';

        $data = [
            'inisial_pasien'            => $_POST['inisialpasien'] ?? '',
            'usia_istri'                => $_POST['usiaistri'] ?? '',
            'pekerjaan_istri'           => $_POST['pekerjaanistri'] ?? '',
            'pendidikan_terakhir_istri' => $_POST['pendidikanterakhiristri'] ?? '',
            'agama_istri'               => $_POST['agamaistri'] ?? '',
            'suku_bangsa'               => $_POST['sukubangsa'] ?? '',
            'status_perkawinan'         => $_POST['statusperkawinan'] ?? '',
            'alamat'                    => $_POST['keterangan'] ?? '',
            'diagnosa_medik'            => $_POST['diagnosamedik'] ?? '',
            'nama_suami'                => $_POST['namasuami'] ?? '',
            'usia_suami'                => $_POST['usiasuami'] ?? '',
            'pekerjaan_suami'           => $_POST['pekerjaansuami'] ?? '',
            'pendidikan_terakhir_suami' => $_POST['pendidikanterakhirsuami'] ?? '',
            'agama_suami'               => $_POST['agamasuami'] ?? '',
            'keluhan_utama'             => $_POST['keluhanutama'] ?? '',
            'riwayat_keluhan_utama'     => $_POST['riwayatkeluhanutama'] ?? '',
        ];

        if (!$submission) {
            $submission_id = createSubmission($user_id, $form_id, $tgl_pengkajian, $rs_ruangan, $mysqli);
        } else {
            $submission_id = $submission['id'];
        }

        saveSection($submission_id, $section_name, $section_label, $data, $mysqli);
        updateSubmissionStatus($submission_id, $form_id, $mysqli);

        redirectWithMessage($_SERVER['REQUEST_URI'], 'success', 'Data berhasil disimpan.');
    }

    ?>

    <main id="main" class="main">

        <?php include "maternitas/pengkajian_antenatal_care/tab.php"; ?>

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

            <div class="card mt-3">
                <div class="card-body">
                    <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

                        <div class="row mb-3 mt-3">
                            <label class="col-sm-2 col-form-label"><strong>Tanggal Pengkajian</strong></label>
                            <div class="col-sm-9">
                                <input type="date" class="form-control" name="tglpengkajian"
                                    value="<?= htmlspecialchars($tgl_pengkajian) ?>" required>
                                    <div class="invalid-feedback">Harap isi Tanggal Pengkajian.</div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>RS/Ruangan</strong></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="rsruangan"
                                    value="<?= htmlspecialchars($rs_ruangan) ?>" required>
                                <div class="invalid-feedback">Harap isi RS/Ruangan.</div>
                            </div>
                        </div>

                        <h5 class="card-title"><strong>DATA DEMOGRAFI</strong></h5>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Inisial Pasien</strong></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="inisialpasien"
                                    value="<?= val('inisial_pasien', $existing_data) ?>">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Usia</strong></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="usiaistri"
                                    value="<?= val('usia_istri', $existing_data) ?>">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Pekerjaan</strong></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="pekerjaanistri"
                                    value="<?= val('pekerjaan_istri', $existing_data) ?>">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Pendidikan Terakhir</strong></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="pendidikanterakhiristri"
                                    value="<?= val('pendidikan_terakhir_istri', $existing_data) ?>">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Agama</strong></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="agamaistri"
                                    value="<?= val('agama_istri', $existing_data) ?>">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Suku Bangsa</strong></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="sukubangsa"
                                    value="<?= val('suku_bangsa', $existing_data) ?>">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Status Perkawinan</strong></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="statusperkawinan"
                                    value="<?= val('status_perkawinan', $existing_data) ?>">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Alamat</strong></label>
                            <div class="col-sm-9">
                                <textarea name="keterangan" class="form-control" rows="5"><?= val('alamat', $existing_data) ?></textarea>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Diagnosa Medik</strong></label>
                            <div class="col-sm-9">
                                <textarea name="diagnosamedik" class="form-control" rows="5"><?= val('diagnosa_medik', $existing_data) ?></textarea>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Nama Suami</strong></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="namasuami"
                                    value="<?= val('nama_suami', $existing_data) ?>">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Usia Suami</strong></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="usiasuami"
                                    value="<?= val('usia_suami', $existing_data) ?>">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Pekerjaan Suami</strong></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="pekerjaansuami"
                                    value="<?= val('pekerjaan_suami', $existing_data) ?>">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Pendidikan Terakhir Suami</strong></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="pendidikanterakhirsuami"
                                    value="<?= val('pendidikan_terakhir_suami', $existing_data) ?>">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Agama Suami</strong></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="agamasuami"
                                    value="<?= val('agama_suami', $existing_data) ?>">
                            </div>
                        </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><strong>DATA BIOLOGIS / PSIKOLOGIS</strong></h5>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Keluhan Utama</strong></label>
                        <div class="col-sm-9">
                            <textarea name="keluhanutama" class="form-control" rows="5"
                                style="overflow:hidden; resize:none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= val('keluhan_utama', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Riwayat Keluhan Utama</strong></label>
                        <div class="col-sm-9">
                            <textarea name="riwayatkeluhanutama" class="form-control" rows="5"
                                style="overflow:hidden; resize:none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= val('riwayat_keluhan_utama', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- TOMBOL SUBMIT -->
                    <div class="row mb-3">
                        <div class="col-sm-11 d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </div>
                    <script>
                        const existingData = <?= json_encode($existing_data) ?>;
                    </script>
                    </form>
                </div>
            </div>

            <?php include "tab_navigasi.php"; ?>

            </div>
        </section>
    </main>