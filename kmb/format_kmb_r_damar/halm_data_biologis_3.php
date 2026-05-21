<?php
$form_id       = 16;
$section_name  = 'data_biologis_3';
$section_label = 'Data Biologis 3';
include dirname(__DIR__, 2) . '/partials/init_section.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $level === 'Mahasiswa') {
    if (isLocked($submission)) {
        redirectWithMessage($_SERVER['REQUEST_URI'], 'error', 'Data tidak dapat diubah karena sedang dalam proses review.');
    }
    $text_fields = [
        'harapan_klien',
        'rendah_diri',
        'pendapat_keadaan',
        'status_rumah',
        'hubungan_keluarga',
        'pengambil_keputusan',
        'ekonomi_cukup',
        'hubungan_keluarga_baik',
        'kelainan_mata'
    ];
    $data = [];
    foreach ($text_fields as $f) {
        $data[$f] = $_POST[$f] ?? '';
    }

    // Dynamic rows penunjang
    $rows = [];
    foreach ($_POST['penunjang'] ?? [] as $row) {
        if (!empty($row['tipe']) || !empty($row['hasil'])) {
            $rows[] = [
                'tipe'          => $row['tipe']          ?? '',
                'tanggal'       => $row['tanggal']       ?? '',
                'hasil'         => $row['hasil']         ?? '',
                'satuan'        => $row['satuan']        ?? '',
                'nilai_rujukan' => $row['nilai_rujukan'] ?? '',
            ];
        }
    }
    $data['data_penunjang'] = json_encode($rows);
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
    <?php include "kmb/format_kmb_r_damar/tab.php"; ?>

    <section class="section dashboard">

        <?php include dirname(__DIR__, 2) . '/partials/notifikasi.php'; ?>
        <?php include dirname(__DIR__, 2) . '/partials/status_section.php'; ?>

        <div class="card">
            <div class="card-body">
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

                    <h5 class="card-title"><strong>5. Data Biologis 3</strong></h5>

                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary"><strong>b. Data Psikologis</strong></label>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-2 col-form-label">
                            <strong>1) Apakah yang diharapkan klien saat ini</strong>
                        </div>
                        <div class="col-sm-9">
                            <textarea class="form-control" rows="3" cols="30" name="harapan_klien" style="display:block; overflow:hidden; resize: none;" <?= $ro ?>
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= htmlspecialchars($existing_data["harapan_klien"] ?? "") ?></textarea>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-2 col-form-label">
                            <strong>2) Apakah klien merasa rendah diri dengan keadaannya saat ini</strong>
                        </div>
                        <div class="col-sm-9">
                            <textarea class="form-control" rows="4" cols="30" name="rendah_diri" style="display:block; overflow:hidden; resize: none;" <?= $ro ?>
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= htmlspecialchars($existing_data["rendah_diri"] ?? "") ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-2 col-form-label">
                            <strong>3) Bagaimana menurut klien dengan keadaannya saat ini</strong>
                        </div>
                        <div class="col-sm-9">
                            <textarea class="form-control" rows="5" cols="30" name="pendapat_keadaan" style="display:block; overflow:hidden; resize: none;" <?= $ro ?>
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= htmlspecialchars($existing_data["pendapat_keadaan"] ?? "") ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-2 col-form-label">
                            <strong>4) Apakah klien tinggal di rumah sendiri atau rumah kontrakan</strong>
                        </div>
                        <div class="col-sm-9">
                            <textarea class="form-control" rows="5" cols="30" name="status_rumah" style="display:block; overflow:hidden; resize: none;" <?= $ro ?>
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= htmlspecialchars($existing_data["status_rumah"] ?? "") ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-2 col-form-label">
                            <strong>5) Apakah hubungan antar keluarga harmonis atau berjauhan</strong>
                        </div>
                        <div class="col-sm-9">
                            <textarea class="form-control" rows="5" cols="30" name="hubungan_keluarga" style="display:block; overflow:hidden; resize: none;" <?= $ro ?>
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= htmlspecialchars($existing_data["hubungan_keluarga"] ?? "") ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-2 col-form-label">
                            <strong>6) Siapakah yang mengambil keputusan dalam keluarga</strong>
                        </div>
                        <div class="col-sm-9">
                            <textarea class="form-control" rows="4" cols="30" name="pengambil_keputusan" style="display:block; overflow:hidden; resize: none;" <?= $ro ?>
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= htmlspecialchars($existing_data["pengambil_keputusan"] ?? "") ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-2 col-form-label">
                            <strong>7) Apakah klien merasa cukup dengan keadaan ekonomi keluarganya saat ini</strong>
                        </div>
                        <div class="col-sm-9">
                            <textarea class="form-control" rows="5" cols="30" name="ekonomi_cukup" style="display:block; overflow:hidden; resize: none;" <?= $ro ?>
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= htmlspecialchars($existing_data["ekonomi_cukup"] ?? "") ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-2 col-form-label">
                            <strong>8) Apakah hubungan antar keluarga baik</strong>
                        </div>
                        <div class="col-sm-9">
                            <textarea class="form-control" rows="3" cols="30" name="hubungan_keluarga_baik" style="display:block; overflow:hidden; resize: none;" <?= $ro ?>
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= htmlspecialchars($existing_data["hubungan_keluarga_baik"] ?? "") ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-2 col-form-label">

                            <strong>9) Apakah klien aktif mengikuti kegiatan kemasyarakatan di sekitar tempat tinggalnya</strong>
                        </div>
                        <div class="col-sm-9">
                            <textarea class="form-control" rows="7" cols="30" name="kelainan_mata" style="display:block; overflow:hidden; resize: none;" <?= $ro ?>
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= htmlspecialchars($existing_data["kelainan_mata"] ?? "") ?></textarea>
                        </div>
                    </div>



                    <div class="row mb-2 mt-4">
                        <label class="col-sm-12 text-primary"><strong>c. Data Penunjang</strong></label>
                    </div>

                    <div class="table-responsive">
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
                                $rows_penunjang = json_decode($existing_data['data_penunjang'] ?? '[]', true) ?: [['tipe' => '', 'tanggal' => '', 'hasil' => '', 'satuan' => '', 'nilai_rujukan' => '']];
                                foreach ($rows_penunjang as $i => $row):
                                ?>
                                    <tr>
                                        <td class="text-center align-middle row-no"><?= $i + 1 ?></td>
                                        <td>
                                            <select class="form-select form-select-sm" name="penunjang[<?= $i ?>][tipe]" <?= $ro_disabled ?>>
                                                <option value="">-- Pilih --</option>
                                                <?php foreach (['Laboratorium', 'Radiologi', 'Lainnya'] as $opt): ?>
                                                    <option value="<?= $opt ?>" <?= ($row['tipe'] ?? '') === $opt ? 'selected' : '' ?>><?= $opt ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="date" class="form-control form-control-sm" name="penunjang[<?= $i ?>][tanggal]"
                                                value="<?= htmlspecialchars($row['tanggal'] ?? '') ?>" <?= $ro ?>>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control form-control-sm" name="penunjang[<?= $i ?>][hasil]"
                                                value="<?= htmlspecialchars($row['hasil'] ?? '') ?>" <?= $ro ?>>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control form-control-sm" name="penunjang[<?= $i ?>][satuan]"
                                                value="<?= htmlspecialchars($row['satuan'] ?? '') ?>" <?= $ro ?>>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control form-control-sm" name="penunjang[<?= $i ?>][nilai_rujukan]"
                                                value="<?= htmlspecialchars($row['nilai_rujukan'] ?? '') ?>" <?= $ro ?>>
                                        </td>
                                        <?php if (!$is_dosen): ?>
                                            <td class="text-center align-middle">
                                                <button type="button" class="btn btn-sm btn-danger btn-hapus-row">
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
                            <button type="button" class="btn btn-sm btn-outline-primary" id="btn-tambah-penunjang">
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


                    <?php if (!$is_dosen): ?>
                        <div class="row mb-3">
                            <div class="col-sm-12 d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary" <?= $ro_disabled ?>>Simpan Data</button>
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
            const tbody = document.getElementById('tbody-penunjang');

            function reindexRows() {
                tbody.querySelectorAll('tr').forEach((tr, i) => {
                    tr.querySelector('.row-no').textContent = i + 1;
                    tr.querySelectorAll('[name]').forEach(el => {
                        el.name = el.name.replace(/penunjang\[\d+\]/, `penunjang[${i}]`);
                    });
                });
            }

            function makeRow(index) {
                const opts = ['Laboratorium', 'Radiologi', 'Lainnya']
                    .map(o => `<option value="${o}">${o}</option>`).join('');
                return `<tr>
            <td class="text-center align-middle row-no">${index + 1}</td>
            <td>
                <select class="form-select form-select-sm" name="penunjang[${index}][tipe]">
                    <option value="">-- Pilih --</option>${opts}
                </select>
            </td>
            <td><input type="date" class="form-control form-control-sm" name="penunjang[${index}][tanggal]"></td>
            <td><input type="text" class="form-control form-control-sm" name="penunjang[${index}][hasil]"></td>
            <td><input type="text" class="form-control form-control-sm" name="penunjang[${index}][satuan]"></td>
            <td><input type="text" class="form-control form-control-sm" name="penunjang[${index}][nilai_rujukan]"></td>
            <td class="text-center align-middle">
                <button type="button" class="btn btn-sm btn-danger btn-hapus-row">
                    <i class="bi bi-trash"></i>
                </button>
            </td>
        </tr>`;
            }

            document.getElementById('btn-tambah-penunjang').addEventListener('click', function() {
                const count = tbody.querySelectorAll('tr').length;
                tbody.insertAdjacentHTML('beforeend', makeRow(count));
            });

            tbody.addEventListener('click', function(e) {
                const btn = e.target.closest('.btn-hapus-row');
                if (!btn) return;
                if (tbody.querySelectorAll('tr').length <= 1) return; // minimal 1 row
                btn.closest('tr').remove();
                reindexRows();
            });
        });
    </script>
<?php endif; ?>