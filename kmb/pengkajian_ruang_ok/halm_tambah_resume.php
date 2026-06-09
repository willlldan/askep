<?php
$form_id       = 11;
$section_name  = 'resume';
$section_label = 'Format Resume Ruang OK';
include dirname(__DIR__, 2) . '/partials/init_section.php';

$existing_penunjang = json_decode($existing_data['pemeriksaan_penunjang'] ?? '[]', true);
if (!is_array($existing_penunjang)) {
    $existing_penunjang = [];
}
if (empty($existing_penunjang) && !empty(trim((string)($existing_data['pemeriksaan_penunjang'] ?? '')))) {
    $existing_penunjang = [[
        'tipe'          => '',
        'tanggal'       => '',
        'hasil'         => $existing_data['pemeriksaan_penunjang'],
        'satuan'        => '',
        'nilai_rujukan' => '',
    ]];
}

$existing_terapi = json_decode($existing_data['terapi_saat_ini'] ?? '[]', true);
if (!is_array($existing_terapi)) {
    $existing_terapi = [];
}
if (empty($existing_terapi) && !empty(trim((string)($existing_data['terapi_saat_ini'] ?? '')))) {
    $existing_terapi = [[
        'jenis_obat'     => $existing_data['terapi_saat_ini'],
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
    $data = [
        'nama_klien'           => $_POST['nama_klien'] ?? '',
        'jenis_kelamin'        => $_POST['jenis_kelamin'] ?? '',
        'umur'                 => $_POST['umur'] ?? '',
        'agama'                => $_POST['agama'] ?? '',
        'status_perkawinan'    => $_POST['status_perkawinan'] ?? '',
        'pendidikan'           => $_POST['pendidikan'] ?? '',
        'pekerjaan'            => $_POST['pekerjaan'] ?? '',
        'alamat'               => $_POST['alamat'] ?? '',
        'diagnosa_medis'       => $_POST['diagnosa_medis'] ?? '',
        'keluhan_utama'        => $_POST['keluhan_utama'] ?? '',
        'tanda_vital'          => $_POST['tanda_vital'] ?? '',
        'pre_operasi'          => $_POST['pre_operasi'] ?? '',
        'pos_operasi'          => $_POST['pos_operasi'] ?? '',
    ];

    $rows_penunjang = [];
    foreach ($_POST['pemeriksaan_penunjang'] ?? [] as $row) {
        if (!empty($row['tipe']) || !empty($row['tanggal']) || !empty($row['hasil']) || !empty($row['satuan']) || !empty($row['nilai_rujukan'])) {
            $rows_penunjang[] = [
                'tipe'          => $row['tipe']          ?? '',
                'tanggal'       => $row['tanggal']       ?? '',
                'hasil'         => $row['hasil']         ?? '',
                'satuan'        => $row['satuan']        ?? '',
                'nilai_rujukan' => $row['nilai_rujukan'] ?? '',
            ];
        }
    }
    $data['pemeriksaan_penunjang'] = json_encode($rows_penunjang);

    $rows_terapi = [];
    foreach ($_POST['terapi_saat_ini'] ?? [] as $row) {
        if (!empty($row['jenis_obat']) || !empty($row['dosis']) || !empty($row['kegunaan']) || !empty($row['cara_pemberian'])) {
            $rows_terapi[] = [
                'jenis_obat'     => $row['jenis_obat']     ?? '',
                'dosis'          => $row['dosis']          ?? '',
                'kegunaan'       => $row['kegunaan']       ?? '',
                'cara_pemberian' => $row['cara_pemberian'] ?? '',
            ];
        }
    }
    $data['terapi_saat_ini'] = json_encode($rows_terapi);

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

    <?php include "kmb/pengkajian_ruang_ok/tab.php"; ?>

    <section class="section dashboard">

        <?php include dirname(__DIR__, 2) . '/partials/notifikasi.php'; ?>
        <?php include dirname(__DIR__, 2) . '/partials/status_section.php'; ?>

        <div class="card">
            <div class="card-body">

                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

                    <h5 class="card-title mb-1"><strong>FORMAT RESUME KEPERAWATAN <br>
                            PRAKTIK KLINIK KEPERAWATAN MEDIKAL BEDAH II DI RUANG OK
                        </strong></h5>

                    <!-- 1. Biodata Klien -->
                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary"><strong>1. Biodata Klien</strong></label>
                    </div>


                    <!-- NAMA KLIEN -->
                    <div class="row mb-3">
                        <label for="nama_klien" class="col-sm-2 col-form-label"><strong>Nama Klien</strong></label>

                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="nama_klien"
                                value="<?= val('nama_klien', $existing_data) ?>" <?= $ro ?>>

                        </div>
                    </div>

                    <!-- JENIS KELAMIN -->
                    <div class="row mb-3">
                        <label for="jenis_kelamin" class="col-sm-2 col-form-label"><strong>Jenis Kelamin</strong></label>

                        <div class="col-sm-10">
                            <select class="form-select" name="jenis_kelamin" <?= $ro_select ?>>
                                <option value="">Pilih</option>
                                <option value="Laki-laki" <?= val('jenis_kelamin', $existing_data) === 'Laki-laki' ? 'selected' : '' ?>>Laki-laki</option>
                                <option value="Perempuan" <?= val('jenis_kelamin', $existing_data) === 'Perempuan' ? 'selected' : '' ?>>Perempuan</option>

                            </select>


                        </div>
                    </div>

                    <!-- UMUR -->
                    <div class="row mb-3">
                        <label for="umur" class="col-sm-2 col-form-label"><strong>Umur</strong></label>

                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="umur"
                                value="<?= val('umur', $existing_data) ?>" <?= $ro ?>>


                        </div>
                    </div>

                    <!-- AGAMA -->
                    <div class="row mb-3">
                        <label for="agama" class="col-sm-2 col-form-label"><strong>Agama</strong></label>

                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="agama"
                                value="<?= val('agama', $existing_data) ?>" <?= $ro ?>>

                        </div>
                    </div>

                    <!-- STATUS PERKAWINAN -->
                    <div class="row mb-3">
                        <label for="status_perkawinan" class="col-sm-2 col-form-label"><strong>Status Perkawinan</strong></label>

                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="status_perkawinan"
                                value="<?= val('status_perkawinan', $existing_data) ?>" <?= $ro ?>>

                        </div>
                    </div>

                    <!-- PENDIDIKAN -->
                    <div class="row mb-3">
                        <label for="pendidikan" class="col-sm-2 col-form-label"><strong>Pendidikan</strong></label>

                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="pendidikan"
                                value="<?= val('pendidikan', $existing_data) ?>" <?= $ro ?>>
                        </div>

                    </div>

                    <!-- PEKERJAAN -->
                    <div class="row mb-3">
                        <label for="pekerjaan" class="col-sm-2 col-form-label"><strong>Pekerjaan</strong></label>

                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="pekerjaan"
                                value="<?= val('pekerjaan', $existing_data) ?>" <?= $ro ?>>

                        </div>
                    </div>

                    <!-- ALAMAT -->
                    <div class="row mb-3">
                        <label for="alamat" class="col-sm-2 col-form-label"><strong>Alamat</strong></label>

                        <div class="col-sm-10">
                            <textarea name="alamat" class="form-control" rows="3"
                                style="display:block; overflow:hidden; resize: none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                value="<?= val('alamat', $existing_data) ?>" <?= $ro ?>></textarea>

                        </div>
                    </div>

                    <!-- DIAGNOSA MEDIS -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Diagnosa Medis</strong></label>

                        <div class="col-sm-10">
                            <textarea name="diagnosa_medis" class="form-control"
                                rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('diagnosa_medis', $existing_data) ?></textarea>

                        </div>
                    </div>

                    <!-- 2. Keluhan Utama -->
                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary"><strong>2. Keluhan Utama</strong></label>
                    </div>


                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Keluhan Utama</strong></label>

                        <div class="col-sm-10">
                            <textarea name="keluhan_utama" class="form-control"
                                rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('keluhan_utama', $existing_data) ?></textarea>

                        </div>
                    </div>

                    <!-- 3. Tanda-tanda Vital -->
                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary"><strong>3. Tanda-tanda Vital</strong></label>
                    </div>


                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Tanda-tanda Vital </strong></label>

                        <div class="col-sm-10">
                            <textarea name="tanda_vital" class="form-control"
                                rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('tanda_vital', $existing_data) ?></textarea>

                        </div>
                    </div>

                    <!-- 4. Pengkajian  Data Fokus (Data yang Bermasalah) -->
                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary"><strong>4. Pengkajian Data Fokus (Data yang Bermasalah)</strong></label>
                    </div>


                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Pre Operasi</strong></label>

                        <div class="col-sm-10">
                            <textarea name="pre_operasi" class="form-control"
                                rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('pre_operasi', $existing_data) ?></textarea>

                        </div>
                    </div>


                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Pos Operasi</strong></label>

                        <div class="col-sm-10">
                            <textarea name="pos_operasi" class="form-control"
                                rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('pos_operasi', $existing_data) ?></textarea>

                        </div>
                    </div>

                    <!-- 5. Pemeriksaan Penunjang -->
                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary"><strong>5. Pemeriksaan Penunjang</strong></label>
                    </div>

                    <div class="table-responsive mb-3">
                        <table class="table table-bordered" id="tabel-penunjang">
                            <thead>
                                <tr>
                                    <th style="width:40px">No</th>
                                    <th style="width:160px">Tipe Pemeriksaan</th>
                                    <th style="width:150px">Tanggal Pemeriksaan</th>
                                    <th>Hasil</th>
                                    <th style="width:100px">Satuan</th>
                                    <th style="width:130px">Nilai Rujukan</th>
                                    <?php if (!$is_dosen): ?>
                                        <th style="width:50px"></th>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody id="tbody-penunjang">
                                <?php
                                $rows_penunjang = $existing_penunjang ?: [['tipe' => '', 'tanggal' => '', 'hasil' => '', 'satuan' => '', 'nilai_rujukan' => '']];
                                foreach ($rows_penunjang as $i => $row):
                                ?>
                                    <tr>
                                        <td class="text-center align-middle row-no-penunjang"><?= $i + 1 ?></td>
                                        <td>
                                            <select class="form-select form-select-sm" name="pemeriksaan_penunjang[<?= $i ?>][tipe]" <?= $ro_disabled ?>>
                                                <option value="">-- Pilih --</option>
                                                <?php foreach (['Laboratorium', 'Radiologi', 'Lainnya'] as $opt): ?>
                                                    <option value="<?= $opt ?>" <?= ($row['tipe'] ?? '') === $opt ? 'selected' : '' ?>><?= $opt ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="date" class="form-control form-control-sm" name="pemeriksaan_penunjang[<?= $i ?>][tanggal]"
                                                value="<?= htmlspecialchars($row['tanggal'] ?? '') ?>" <?= $ro ?>>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control form-control-sm" name="pemeriksaan_penunjang[<?= $i ?>][hasil]"
                                                value="<?= htmlspecialchars($row['hasil'] ?? '') ?>" <?= $ro ?>>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control form-control-sm" name="pemeriksaan_penunjang[<?= $i ?>][satuan]"
                                                value="<?= htmlspecialchars($row['satuan'] ?? '') ?>" <?= $ro ?>>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control form-control-sm" name="pemeriksaan_penunjang[<?= $i ?>][nilai_rujukan]"
                                                value="<?= htmlspecialchars($row['nilai_rujukan'] ?? '') ?>" <?= $ro ?>>
                                        </td>
                                        <?php if (!$is_dosen): ?>
                                            <td class="text-center align-middle">
                                                <button type="button" class="btn btn-sm btn-danger btn-hapus-row-penunjang" <?= $ro_disabled ?>>
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
                                Tipe pemeriksaan: <strong>Laboratorium</strong> = hasil lab darah/urin/dll &nbsp;|&nbsp;
                                <strong>Radiologi</strong> = Rontgen, MRI, dll &nbsp;|&nbsp;
                                <strong>Lainnya</strong> = USG, CT Scan, dll
                            </small>
                            <button type="button" class="btn btn-sm btn-outline-primary" id="btn-tambah-penunjang" <?= $ro_disabled ?>>
                                <i class="bi bi-plus-circle"></i> Tambah Baris
                            </button>
                        </div>
                    <?php else: ?>
                        <small class="text-muted d-block mb-3">
                            Tipe pemeriksaan: <strong>Laboratorium</strong> = hasil lab darah/urin/dll &nbsp;|&nbsp;
                            <strong>Radiologi</strong> = Rontgen, MRI, dll &nbsp;|&nbsp;
                            <strong>Lainnya</strong> = USG, CT Scan, dll
                        </small>
                    <?php endif; ?>

                    <!-- 6. Terapi Saat Ini -->
                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary"><strong>6. Terapi Saat Ini</strong></label>
                    </div>

                    <div class="table-responsive mb-3">
                        <table class="table table-bordered" id="tabel-terapi-saat-ini">
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
                            <tbody id="tbody-terapi-saat-ini">
                                <?php
                                $rows_terapi = $existing_terapi ?: [['jenis_obat' => '', 'dosis' => '', 'kegunaan' => '', 'cara_pemberian' => '']];
                                foreach ($rows_terapi as $i => $row):
                                ?>
                                    <tr>
                                        <td class="text-center align-middle row-no-terapi"><?= $i + 1 ?></td>
                                        <td>
                                            <input type="text" class="form-control form-control-sm" name="terapi_saat_ini[<?= $i ?>][jenis_obat]"
                                                value="<?= htmlspecialchars($row['jenis_obat'] ?? '') ?>" <?= $ro ?>>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control form-control-sm" name="terapi_saat_ini[<?= $i ?>][dosis]"
                                                value="<?= htmlspecialchars($row['dosis'] ?? '') ?>" <?= $ro ?>>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control form-control-sm" name="terapi_saat_ini[<?= $i ?>][kegunaan]"
                                                value="<?= htmlspecialchars($row['kegunaan'] ?? '') ?>" <?= $ro ?>>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control form-control-sm" name="terapi_saat_ini[<?= $i ?>][cara_pemberian]"
                                                value="<?= htmlspecialchars($row['cara_pemberian'] ?? '') ?>" <?= $ro ?>>
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

                    <!-- TOMBOL MAHASISWA -->
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

<?php if (!$is_dosen): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const isReadonly = <?= json_encode($is_readonly) ?>;
            const tbodyPenunjang = document.getElementById('tbody-penunjang');
            const tbodyTerapi = document.getElementById('tbody-terapi-saat-ini');

            function reindexRowsPenunjang() {
                tbodyPenunjang.querySelectorAll('tr').forEach((tr, i) => {
                    tr.querySelector('.row-no-penunjang').textContent = i + 1;
                    tr.querySelectorAll('[name]').forEach(el => {
                        el.name = el.name.replace(/pemeriksaan_penunjang\[\d+\]/, `pemeriksaan_penunjang[${i}]`);
                    });
                });
            }

            function makeRowPenunjang(index) {
                const opts = ['Laboratorium', 'Radiologi', 'Lainnya']
                    .map(o => `<option value="${o}">${o}</option>`).join('');
                return `<tr>
            <td class="text-center align-middle row-no-penunjang">${index + 1}</td>
            <td>
                <select class="form-select form-select-sm" name="pemeriksaan_penunjang[${index}][tipe]">
                    <option value="">-- Pilih --</option>${opts}
                </select>
            </td>
            <td><input type="date" class="form-control form-control-sm" name="pemeriksaan_penunjang[${index}][tanggal]"></td>
            <td><input type="text" class="form-control form-control-sm" name="pemeriksaan_penunjang[${index}][hasil]"></td>
            <td><input type="text" class="form-control form-control-sm" name="pemeriksaan_penunjang[${index}][satuan]"></td>
            <td><input type="text" class="form-control form-control-sm" name="pemeriksaan_penunjang[${index}][nilai_rujukan]"></td>
            <td class="text-center align-middle">
                <button type="button" class="btn btn-sm btn-danger btn-hapus-row-penunjang">
                    <i class="bi bi-trash"></i>
                </button>
            </td>
        </tr>`;
            }

            function reindexRowsTerapi() {
                tbodyTerapi.querySelectorAll('tr').forEach((tr, i) => {
                    tr.querySelector('.row-no-terapi').textContent = i + 1;
                    tr.querySelectorAll('[name]').forEach(el => {
                        el.name = el.name.replace(/terapi_saat_ini\[\d+\]/, `terapi_saat_ini[${i}]`);
                    });
                });
            }

            function makeRowTerapi(index) {
                return `<tr>
            <td class="text-center align-middle row-no-terapi">${index + 1}</td>
            <td><input type="text" class="form-control form-control-sm" name="terapi_saat_ini[${index}][jenis_obat]"></td>
            <td><input type="text" class="form-control form-control-sm" name="terapi_saat_ini[${index}][dosis]"></td>
            <td><input type="text" class="form-control form-control-sm" name="terapi_saat_ini[${index}][kegunaan]"></td>
            <td><input type="text" class="form-control form-control-sm" name="terapi_saat_ini[${index}][cara_pemberian]"></td>
            <td class="text-center align-middle">
                <button type="button" class="btn btn-sm btn-danger btn-hapus-row-terapi">
                    <i class="bi bi-trash"></i>
                </button>
            </td>
        </tr>`;
            }

            const btnTambahPenunjang = document.getElementById('btn-tambah-penunjang');
            if (btnTambahPenunjang) {
                btnTambahPenunjang.addEventListener('click', function() {
                    if (isReadonly) return;
                    const count = tbodyPenunjang.querySelectorAll('tr').length;
                    tbodyPenunjang.insertAdjacentHTML('beforeend', makeRowPenunjang(count));
                });
            }

            tbodyPenunjang.addEventListener('click', function(e) {
                const btn = e.target.closest('.btn-hapus-row-penunjang');
                if (!btn || isReadonly) return;
                if (tbodyPenunjang.querySelectorAll('tr').length <= 1) return;
                btn.closest('tr').remove();
                reindexRowsPenunjang();
            });

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

            if (isReadonly) {
                if (btnTambahPenunjang) btnTambahPenunjang.setAttribute('disabled', 'disabled');
                if (btnTambahTerapi) btnTambahTerapi.setAttribute('disabled', 'disabled');
            }
        });
    </script>
<?php endif; ?>
