<?php
require_once "koneksi.php";
require_once "utils.php";

$form_id       = 8;
$section_name  = 'format_lp';
$section_label = 'Format Laporan Pendahuluan';
include dirname(__DIR__, 2) . '/partials/init_section.php';

$tgl_pengkajian = $submission['tanggal_pengkajian'] ?? '';
$rs_ruangan     = $submission['rs_ruangan'] ?? '';
$existing_evaluasi     = $existing_data['evaluasi']     ?? [];

// =============================================
// HANDLE POST - MAHASISWA SIMPAN DATA
// =============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $level === 'Mahasiswa') {

    if (isLocked($submission)) {
        redirectWithMessage($_SERVER['REQUEST_URI'], 'error', 'Data tidak dapat diubah karena sedang dalam proses review.');
    }

    $tgl_pengkajian = $_POST['tglpengkajian'] ?? '';
    $rs_ruangan     = $_POST['rsruangan'] ?? '';
    // Proses dynamic rows evaluasi
    $evaluasi = [];
    if (!empty($_POST['evaluasi'])) {
        foreach ($_POST['evaluasi'] as $index => $row) {
            if (empty($row['masalah']) && empty($row['data_dikaji'])) {
                continue;
            }
            $evaluasi[] = [
                'masalah'      => $row['masalah']      ?? '',
                'data_dikaji_subjektif'         => $row['data_dikaji_subjektif']   ?? '',
                'data_dikaji_objektif'          => $row['data_dikaji_objektif']         ?? '',

            ];
        }
    }
    $data = [
        'evaluasi'     => $evaluasi,
        'masalah_keperawatan_utama'     => $_POST['masalah_keperawatan_utama'] ?? '',
        'pengertian'                    => $_POST['pengertian'] ?? '',
        'tanda_gejala'                  => $_POST['tanda_gejala'] ?? '',
        'rentang_respons'               => $_POST['rentang_respons'] ?? '',
        'faktor_predisposisi'           => $_POST['faktor_predisposisi'] ?? '',
        'faktor_presipitasi'            => $_POST['faktor_presipitasi'] ?? '',
        'sumber_koping'                 => $_POST['sumber_koping'] ?? '',
        'mekanisme_koping'              => $_POST['mekanisme_koping'] ?? '',
        'pohon_masalah'                 => $_POST['pohon_masalah'] ?? '',
        'masalah_keperawatan_muncul'    => $_POST['masalah_keperawatan_muncul'] ?? '',
        'masalah_keperawatan'           => $_POST['masalah_keperawatan'] ?? '',
        'subjektif'                     => $_POST['subjektif'] ?? '',
        'objektif'                      => $_POST['objektif'] ?? '',
        'diagnosa_muncul'               => $_POST['diagnosa_muncul'] ?? '',
        'rencana_keperawatan'           => $_POST['rencana_keperawatan'] ?? '',
        'daftar_pustaka'                => $_POST['daftar_pustaka'] ?? ''
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

    <?php include "jiwa/poli_jiwa/tab.php"; ?>

    <section class="section dashboard">

        <?php include dirname(__DIR__, 2) . '/partials/notifikasi.php'; ?>
        <?php include dirname(__DIR__, 2) . '/partials/status_section.php'; ?>

        <div class="card">
            <div class="card-body mt-3">

                <form class="needs-validation" novalidate action="" method="POST">

                    <div class="row mb-3 mt-3">
                        <label class="col-sm-2 col-form-label"><strong>Tanggal Pengkajian</strong></label>
                        <div class="col-sm-9">
                            <input type="date" class="form-control" name="tglpengkajian"
                                value="<?= htmlspecialchars($tgl_pengkajian) ?>" <?= $ro ?> required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>RS/Ruangan</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="rsruangan"
                                value="<?= htmlspecialchars($rs_ruangan) ?>" <?= $ro ?> required>
                        </div>
                    </div>


                    <h5 class="card-title"><strong>FORMAT LAPORAN PENDAHULUAN PRAKTIK KLINIK KEPERAWATAN JIWA</strong></h5>

                    <div class="row mb-2">
                        <label class="col-sm-4 col-form-label text-primary">
                            <strong>A. Masalah Keperawatan Utama</strong>
                        </label>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Masalah Keperawatan Utama</strong></label>
                        <div class="col-sm-10">
                            <textarea name="masalah_keperawatan_utama" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('masalah_keperawatan_utama', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <label class="col-sm-4 col-form-label text-primary">
                            <strong>B. Proses Terjadinya Masalah</strong>
                        </label>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>1. Pengertian</strong></label>
                        <div class="col-sm-10">
                            <textarea name="pengertian" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('pengertian', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>2. Tanda dan Gejala</strong></label>
                        <div class="col-sm-10">
                            <textarea name="tanda_gejala" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('tanda_gejala', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>3. Rentang Respons</strong></label>
                        <div class="col-sm-10">
                            <textarea name="rentang_respons" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('rentang_respons', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>4. Faktor Predisposisi</strong></label>
                        <div class="col-sm-10">
                            <textarea name="faktor_predisposisi" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('faktor_predisposisi', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>5. Faktor Presipitasi</strong></label>
                        <div class="col-sm-10">
                            <textarea name="faktor_presipitasi" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('faktor_presipitasi', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>6. Sumber Koping</strong></label>
                        <div class="col-sm-10">
                            <textarea name="sumber_koping" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('sumber_koping', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>7. Mekanisme Koping</strong></label>
                        <div class="col-sm-10">
                            <textarea name="mekanisme_koping" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('mekanisme_koping', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>8. Pohon Masalah</strong></label>
                        <div class="col-sm-10">
                            <textarea name="pohon_masalah" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('pohon_masalah', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>9. Masalah Keperawatan yang Mungkin Muncul</strong></label>
                        <div class="col-sm-10">
                            <textarea name="masalah_keperawatan_muncul" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('masalah_keperawatan_muncul', $existing_data) ?></textarea>
                        </div>
                        <!-- !-- ===================== TABEL EVALUASI ===================== -->
                        <p class="text-primary fw-bold mb-2">10. Data yang perlu dikaji</p>

                        <table class="table table-bordered" id="tabel-evaluasi">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width:70px">Masalah Keperawatan</th>
                                    <th class="text-center" style="width:150px">Data yang Perlu Dikaji</th>

                                </tr>
                            </thead>
                            <tbody id="tbody-evaluasi">
                                <!-- Dynamic rows masuk sini -->
                            </tbody>
                        </table>

                        <?php if (!$is_readonly): ?>
                            <div class="row mb-4">
                                <div class="col-sm-12 d-flex justify-content-end">
                                    <button type="button" class="btn btn-primary btn-sm" onclick="tambahRowEvaluasi()">+ Tambah Data</button>
                                </div>
                            </div>
                        <?php endif; ?>


                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>11. Diagnosa Keperawatan yang Mungkin Muncul</strong></label>
                            <div class="col-sm-10">
                                <textarea name="diagnosa_muncul" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('diagnosa_muncul', $existing_data) ?></textarea>

                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>12. Rencana Tindakan Keperawatan</strong></label>
                            <div class="col-sm-10">
                                <textarea name="rencana_keperawatan" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('rencana_keperawatan', $existing_data) ?></textarea>

                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>13. Daftar Pustaka</strong></label>
                            <div class="col-sm-10">
                                <textarea name="daftar_pustaka" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('daftar_pustaka', $existing_data) ?></textarea>
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
       <script>
    let rowEvaluasiCount = 1;
    const existingEvaluasi = <?= json_encode($existing_evaluasi) ?>;
    const isReadonly = <?= json_encode($is_readonly) ?>;

    // Helper untuk auto-resize textarea
    function autoResize(textarea) {
        textarea.style.height = 'auto';
        textarea.style.height = textarea.scrollHeight + 'px';
    }

    // ---- EVALUASI ----
    function tambahRowEvaluasi(data = null) {
        const tbody = document.getElementById('tbody-evaluasi');
        const index = rowEvaluasiCount;
        const row = document.createElement('tr');

        row.innerHTML = `
            <td class="col-6">
                <textarea class="form-control form-control-sm" name="evaluasi[${index}][masalah]" rows="1" style="overflow:hidden; resize:none;" oninput="autoResize(this)" ${isReadonly ? 'readonly' : ''}>${data?.masalah ?? ''}</textarea>
            </td>
            <td class="col-6">
                <div class="d-flex flex-column">
                    <div class="mb-1 d-flex align-items-start gap-2">
                        <label class="form-label form-label-sm fw-bold mb-0" style="width:20px;">S</label>
                        <textarea class="form-control form-control-sm" name="evaluasi[${index}][data_dikaji_subjektif]" rows="1" style="overflow:hidden; resize:none;" oninput="autoResize(this)" ${isReadonly ? 'readonly' : ''}>${data?.data_dikaji_subjektif ?? ''}</textarea>
                    </div>
                    <div class="mb-1 d-flex align-items-start gap-2">
                        <label class="form-label form-label-sm fw-bold mb-0" style="width:20px;">O</label>
                        <textarea class="form-control form-control-sm" name="evaluasi[${index}][data_dikaji_objektif]" rows="1" style="overflow:hidden; resize:none;" oninput="autoResize(this)" ${isReadonly ? 'readonly' : ''}>${data?.data_dikaji_objektif ?? ''}</textarea>
                    </div>
                </div>
            </td>
            <td class="text-center align-middle">
                ${!isReadonly ? `<button type="button" class="btn btn-danger btn-sm" onclick="hapusRow(this)">x</button>` : ''}
            </td>
        `;
        tbody.appendChild(row);
        
        // Panggil autoResize untuk textarea yang baru dibuat jika ada nilai awal
        const textareas = row.querySelectorAll('textarea');
        textareas.forEach(ta => autoResize(ta));
        
        rowEvaluasiCount++;
    }

    function hapusRow(btn) {
        btn.closest('tr').remove();
    }

    // Load existing rows on page load
    window.addEventListener('load', function() {
        if (existingEvaluasi && existingEvaluasi.length > 0) {
            existingEvaluasi.forEach(row => tambahRowEvaluasi(row));
        } else {
            tambahRowEvaluasi();
        }
    });

    const existingData = <?= json_encode($existing_data) ?>;
</script>
        </div>
        </div>
        <?php include dirname(__DIR__, 2) . '/partials/footer_form.php'; ?>

    </section>
</main>