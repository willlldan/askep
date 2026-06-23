<?php
$form_id       = 9;
$section_name  = 'lp_ruanghd';
$section_label = 'Format Laporan Pendahuluan Ruang HD';
include dirname(__DIR__, 2) . '/partials/init_section.php';

$tgl_pengkajian = $submission['tanggal_pengkajian'] ?? '';
$rs_ruangan     = $submission['rs_ruangan'] ?? '';

// FIX: decode jika masih string JSON
$existing_intervensi = $existing_data['intervensi'] ?? [];
if (is_string($existing_intervensi)) {
    $existing_intervensi = json_decode($existing_intervensi, true) ?? [];
}

// =============================================
// HANDLE POST - MAHASISWA SIMPAN DATA
// =============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $level === 'Mahasiswa') {

    if (isLocked($submission)) {
        redirectWithMessage($_SERVER['REQUEST_URI'], 'error', 'Data tidak dapat diubah karena sedang dalam proses review.');
    }

    $tgl_pengkajian = $_POST['tglpengkajian'] ?? '';
    $rs_ruangan     = $_POST['rsruangan'] ?? '';

    // Proses dynamic rows intervensi
    $intervensi = [];
    if (!empty($_POST['intervensi'])) {
        foreach ($_POST['intervensi'] as $index => $row) {
            if (empty($row['diagnosa']) && empty($row['tujuan_kriteria']) && empty($row['intervensi'])) {
                continue;
            }
            $intervensi[] = [
                'diagnosa'        => $row['diagnosa']        ?? '',
                'tujuan_kriteria' => $row['tujuan_kriteria'] ?? '',
                'intervensi'      => $row['intervensi']      ?? '',
            ];
        }
    }

    $data = [
        'intervensi'                 => json_encode($intervensi), // FIX: encode ke JSON
        'definisi'                   => $_POST['definisi'] ?? '',
        'klasifikasi'                => $_POST['klasifikasi'] ?? '',
        'etiologi'                   => $_POST['etiologi'] ?? '',
        'manifestasiklinik'         => $_POST['manifestasiklinik'] ?? '',
        'patofisiologi'              => $_POST['patofisiologi'] ?? '',
        'penunjang'                  => $_POST['penunjang'] ?? '',
        'penatalaksanaan'            => $_POST['penatalaksanaan'] ?? '',
        'komplikasi'                 => $_POST['komplikasi'] ?? '',
        'pengertian'                 => $_POST['pengertian'] ?? '',
        'tujuan'                     => $_POST['tujuan'] ?? '',
        'proses_hemodialisa'         => $_POST['proses_hemodialisa'] ?? '',
        'alasanhemodialisa'         => $_POST['alasanhemodialisa'] ?? '',
        'indikasihemodialisa'       => $_POST['indikasihemodialisa'] ?? '',
        'kontraindikasihemodialisa' => $_POST['kontraindikasihemodialisa'] ?? '',
        'frekuensihemodialisa'      => $_POST['frekuensihemodialisa'] ?? '',
        'komplikasi1'                => $_POST['komplikasi1'] ?? '',
        'daftarpustakackd'           => $_POST['daftarpustakackd'] ?? '',
        'daftarpustakahd'            => $_POST['daftarpustakahd'] ?? '',
        'pengkajian'                 => $_POST['pengkajian'] ?? '',
        'diagnosa'                   => $_POST['diagnosa'] ?? '',
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
    <?php include "kmb/format_hd_kmb/tab.php"; ?>
    <section class="section dashboard">

        <?php include "partials/notifikasi.php"; ?>
        <?php include "partials/status_section.php"; ?>

        <!-- FIX: form dimulai di sini, membungkus semua card -->
        <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

        <div class="card">
            <div class="card-body">
               <!--  <div class="row mb-3 mt-3">
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
                </div> -->

                <h5 class="card-title"><strong>A. Konsep Dasar Penyakit (Chronic Kidney Disease (CKD))</strong></h5>

                <div class="row mb-3">
                    <div class="col-sm-2 col-form-label"><strong>1. Definisi CKD</strong></div>
                    <div class="col-sm-10">
                        <textarea name="definisi" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('definisi',$existing_data) ?></textarea>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-2 col-form-label"><strong>2. Etiologi CKD</strong></div>
                    <div class="col-sm-10">
                        <textarea name="etiologi" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('etiologi',$existing_data) ?></textarea>
                        </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-2 col-form-label"><strong>3. Klasifikasi CKD</strong></div>
                    <div class="col-sm-10">
                        <textarea name="klasifikasi" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('klasifikasi',$existing_data) ?></textarea>
                        </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-2 col-form-label"><strong>4. Manifestasi Klinik CKD</strong></div>
                    <div class="col-sm-10">
                        <textarea name="manifestasiklinik" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('manifestasiklinik',$existing_data) ?></textarea>
                        </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-2 col-form-label"><strong>5. Patofisiologi dan Pathway</strong></div>
                    <div class="col-sm-10">
                        <textarea name="patofisiologi" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('patofisiologi',$existing_data) ?></textarea>
                        </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-2 col-form-label"><strong>6. Pemeriksaan Penunjang</strong></div>
                    <div class="col-sm-10">
                        <textarea name="penunjang" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('penunjang',$existing_data) ?></textarea>
                        </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-2 col-form-label"><strong>7. Penatalaksanaan</strong></div>
                    <div class="col-sm-10">
                        <textarea name="penatalaksanaan" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('penatalaksanaan',$existing_data) ?></textarea>
                        </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-2 col-form-label"><strong>8. Komplikasi</strong></div>
                    <div class="col-sm-10">
                        <textarea name="komplikasi" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('komplikasi',$existing_data) ?></textarea>
                        </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-2 col-form-label"><strong>9. Daftar Pustaka</strong></div>
                    <div class="col-sm-10">
                        <textarea name="daftarpustakackd" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('daftarpustakackd',$existing_data) ?></textarea>
                         </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><strong>B. Konsep Dasar Keperawatan</strong></h5>

                <div class="row mb-3">
                    <div class="col-sm-2 col-form-label"><strong>1. Pengkajian</strong></div>
                    <div class="col-sm-10">
                        <textarea name="pengkajian" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('pengkajian',$existing_data) ?></textarea>
                        </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-2 col-form-label"><strong>2. Diagnosa</strong></div>
                    <div class="col-sm-10">
                        <textarea name="diagnosa" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('diagnosa',$existing_data) ?></textarea>
                        </div>
                </div>

                <!-- ===================== TABEL INTERVENSI ===================== -->
                <p class="fw-bold mb-2">3. Intervensi Keperawatan</p>

                <table class="table table-bordered" id="tabel-intervensi">
                    <thead>
                        <tr>
                            <th class="text-center" style="width:40px">No</th>
                            <th class="text-center">Diagnosa</th>
                            <th class="text-center">Tujuan dan Kriteria Hasil</th>
                            <th class="text-center">Intervensi</th>
                            <th class="text-center" style="width:60px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="tbody-intervensi">
                        <!-- Dynamic rows masuk sini -->
                    </tbody>
                </table>

                <?php if (!$is_readonly): ?>
                    <div class="row mb-4">
                        <div class="col-sm-12 d-flex justify-content-end">
                            <button type="button" class="btn btn-primary btn-sm" onclick="tambahRowIntervensi()">+ Tambah Intervensi</button>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><strong>C. Konsep Dasar Hemodialisa</strong></h5>

                <div class="row mb-3">
                    <div class="col-sm-2 col-form-label"><strong>1. Pengertian</strong></div>
                    <div class="col-sm-10">
                        <textarea name="pengertian" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('pengertian',$existing_data) ?></textarea>
                       </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-2 col-form-label"><strong>2. Tujuan</strong></div>
                    <div class="col-sm-10">
                        <textarea name="tujuan" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('tujuan',$existing_data) ?></textarea>
                        </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-2 col-form-label"><strong>3. Proses Hemodialisa</strong></div>
                    <div class="col-sm-10">
                        <textarea name="proses_hemodialisa" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('proses_hemodialisa',$existing_data) ?></textarea>
                        </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-2 col-form-label"><strong>4. Alasan dilakukan Hemodialisa</strong></div>
                    <div class="col-sm-10">
                        <textarea name="alasanhemodialisa" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('alasanhemodialisa',$existing_data) ?></textarea>
                        </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-2 col-form-label"><strong>5. Indikasi Hemodialisa</strong></div>
                    <div class="col-sm-10">
                        <textarea name="indikasihemodialisa" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('indikasihemodialisa',$existing_data) ?></textarea>
                        </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-2 col-form-label"><strong>6. Kontraindikasi Hemodialisa</strong></div>
                    <div class="col-sm-10">
                        <textarea name="kontraindikasihemodialisa" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('kontraindikasihemodialisa',$existing_data) ?></textarea>
                        </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-2 col-form-label"><strong>7. Frekuensi Hemodialisa</strong></div>
                    <div class="col-sm-10">
                        <textarea name="frekuensihemodialisa" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('frekuensihemodialisa',$existing_data) ?></textarea>
                        </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-2 col-form-label"><strong>8. Komplikasi Hemodialisa</strong></div>
                    <div class="col-sm-10">
                        <textarea name="komplikasi1" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('komplikasi1',$existing_data) ?></textarea>
                        </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-2 col-form-label"><strong>9. Daftar Pustaka</strong></div>
                    <div class="col-sm-10">
                        <textarea name="daftarpustakahd" class="form-control"
                        rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('daftarpustakahd',$existing_data) ?></textarea>
                        </div>
                </div>

                <!-- TOMBOL SUBMIT -->
                <?php if (!$is_dosen): ?>
                    <div class="row mb-3">
                        <div class="col-sm-12 d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </div>
                <?php endif; ?>

            </div>
        </div>

        </form>
        <!-- FIX: form ditutup di sini setelah semua card -->

        <script>

            let rowIntervensiCount = 1;
            const existingIntervensi = <?= json_encode($existing_intervensi) ?>;

            function autoResizeTextarea(el) {
                            el.style.height = 'auto';
                            el.style.height = el.scrollHeight + 'px';
                        }

            function tambahRowIntervensi(data = null) {
                const tbody = document.getElementById('tbody-intervensi');
                const index = rowIntervensiCount;
                const row = document.createElement('tr');
                const isReadonly = <?= json_encode($is_readonly) ?>;
                row.innerHTML = `
                    <td class="text-center align-middle">${index}</td>
                    <td>
                        ${
                            isReadonly
                            ? `<div class="readonly-text">${data?.diagnosa ?? ''}</div>`
                            : `<textarea
                            class="form-control form-control-sm auto-resize"
                            name="intervensi[${index}][diagnosa]"
                            rows="2"
                            style="resize:none; overflow:hidden;"
                            oninput="autoResizeTextarea(this)"
                            >${data?.diagnosa ?? ''}</textarea>`
                        }
                    </td>
                    <td>
                        ${
                            isReadonly
                            ? `<div class="readonly-text">${data?.tujuan_kriteria ?? ''}</div>`
                            : `<textarea
                            class="form-control form-control-sm auto-resize"
                            name="intervensi[${index}][tujuan_kriteria]"
                            rows="2"
                            style="resize:none; overflow:hidden;"
                            oninput="autoResizeTextarea(this)"
                            >${data?.tujuan_kriteria ?? ''}</textarea>`
                        }
                    </td>
                    <td>
                        ${
                            isReadonly
                            ? `<div class="readonly-text">${data?.intervensi ?? ''}</div>`
                            : `<textarea
                            class="form-control form-control-sm auto-resize"
                            name="intervensi[${index}][intervensi]"
                            rows="2"
                            style="resize:none; overflow:hidden;"
                            oninput="autoResizeTextarea(this)"
                            >${data?.intervensi ?? ''}</textarea>`
                        }
                    </td>
                    <td class="text-center align-middle">
                        ${!isReadonly ? `<button type="button" class="btn btn-danger btn-sm" onclick="hapusRow(this)">x</button>` : ''}
                    </td>
                `;
                tbody.appendChild(row);

                row.querySelectorAll('.auto-resize').forEach(autoResizeTextarea);

                rowIntervensiCount++;
            }

            function hapusRow(btn) {
                btn.closest('tr').remove();
            }

            window.addEventListener('load', function () {
                if (existingIntervensi && existingIntervensi.length > 0) {
                    existingIntervensi.forEach(row => tambahRowIntervensi(row));
                } else {
                    tambahRowIntervensi();
                }
            });
        </script>
<?php include dirname(__DIR__, 2) . '/partials/footer_form.php'; ?>
    </section>
</main>

