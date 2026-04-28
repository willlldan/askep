<?php
require_once "koneksi.php";
require_once "utils.php";

$form_id       = 18;
$level         = $_SESSION['level'];
$user_id       = $_SESSION['id_user'];
$section_name  = 'konsep_keperawatan';
$section_label = 'Konsep Keperawatan';

// =============================================
// DOSEN: ambil submission berdasarkan ?submission_id=
// MAHASISWA: ambil submission milik sendiri
// =============================================
if ($level === 'Dosen') {
    $submission_id_param = $_GET['submission_id'] ?? null;
    if (!$submission_id_param) {
        echo "<div class='alert alert-danger'>Submission tidak ditemukan.</div>";
        exit;
    }
    $stmt = $mysqli->prepare("
        SELECT s.*, r.nama as dosen_name
        FROM submissions s
        LEFT JOIN tbl_user r ON s.reviewed_by = r.id_user
        WHERE s.id = ?
    ");
    $stmt->bind_param("i", $submission_id_param);
    $stmt->execute();
    $submission = $stmt->get_result()->fetch_assoc();
} else {
    $submission = getSubmission($user_id, $form_id, $mysqli);
}

$existing_data   = $submission ? getSectionData($submission['id'], $section_name, $mysqli) : [];
$section_status  = $submission ? getSectionStatus($submission['id'], $section_name, $mysqli) : null;
$tgl_pengkajian  = $submission['tanggal_pengkajian'] ?? '';
$rs_ruangan      = $submission['rs_ruangan']         ?? '';

// Load existing dynamic rows
$existing_perencanaan    = $existing_data['perencanaan']      ?? [];
$existing_daftar_pustaka = $existing_data['daftar_pustaka']   ?? [];
$existing_kdm            = $existing_data['penyimpangan_kdm'] ?? '';

// Readonly flag
$is_dosen    = $level === 'Dosen';
$is_readonly = $is_dosen || isLocked($submission);
$ro          = $is_readonly ? 'readonly' : '';

// =============================================
// HANDLE POST - MAHASISWA SIMPAN DATA
// =============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $level === 'Mahasiswa') {

    if (isLocked($submission)) {
        redirectWithMessage($_SERVER['REQUEST_URI'], 'error', 'Data tidak dapat diubah karena sedang dalam proses review.');
    }

    $tgl_pengkajian = $_POST['tgl_pengkajian'] ?? '';
    $rs_ruangan     = $_POST['rs_ruangan']     ?? '';

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

    // Proses upload penyimpangan KDM
    $path_kdm = $existing_data['penyimpangan_kdm'] ?? '';
    if (!empty($_FILES['penyimpangan_kdm']['name'])) {
        $upload = uploadImage($_FILES['penyimpangan_kdm'], 'uploads/kdm/', 50);
        if ($upload['success']) {
            if (!empty($path_kdm) && file_exists($path_kdm)) {
                unlink($path_kdm);
            }
            $path_kdm = $upload['path'];
        } else {
            redirectWithMessage($_SERVER['REQUEST_URI'], 'error', $upload['error']);
            exit;
        }
    }

    $data = [
        // A. Konsep Dasar Medis
        'pengertian'             => $_POST['pengertian']             ?? '',
        'klasifikasi'            => $_POST['klasifikasi']            ?? '',
        'etiologi'               => $_POST['etiologi']               ?? '',
        'manifestasi_klinik'     => $_POST['manifestasi_klinik']     ?? '',
        'patofisiologi'          => $_POST['patofisiologi']          ?? '',
        'pemeriksaan_diagnostik' => $_POST['pemeriksaan_diagnostik'] ?? '',
        'penatalaksanaan'        => $_POST['penatalaksanaan']        ?? '',
        // B. Konsep Dasar Keperawatan
        'pengkajian_keperawatan' => $_POST['pengkajian_keperawatan'] ?? '',
        'penyimpangan_kdm'       => $path_kdm,
        'diagnosa_keperawatan'   => $_POST['diagnosa_keperawatan']   ?? '',
        'perencanaan'            => $perencanaan,
        // C. Daftar Pustaka
        'daftar_pustaka'         => $daftar_pustaka,
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

// =============================================
// HANDLE POST - DOSEN APPROVE / REVISI
// =============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $level === 'Dosen') {
    $submission_id = $submission['id'];
    $dosen_id      = $user_id;
    $action        = $_POST['action'] ?? '';
    $comment       = $_POST['comment'] ?? '';

    if ($action === 'approve') {
        updateSectionStatus($submission_id, $section_name, 'approved', $mysqli);
        if (!empty($comment)) {
            saveComment($submission_id, $section_name, $comment, $dosen_id, $mysqli);
        }
    } elseif ($action === 'revision') {
        if (empty($comment)) {
            redirectWithMessage($_SERVER['REQUEST_URI'], 'error', 'Komentar wajib diisi saat meminta revisi.');
        }
        updateSectionStatus($submission_id, $section_name, 'revision', $mysqli);
        saveComment($submission_id, $section_name, $comment, $dosen_id, $mysqli);
    }

    updateReviewer($submission_id, $dosen_id, $mysqli);
    updateSubmissionStatusByDosen($submission_id, $form_id, $mysqli);
    redirectWithMessage($_SERVER['REQUEST_URI'], 'success', 'Berhasil disimpan.');
}

// Load komentar section
$comments = $submission ? getSectionComments($submission['id'], $section_name, $mysqli) : [];
?>

<main id="main" class="main">

    <?php include "kmb/format_kmb/tab.php"; ?>

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

        <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

            <!-- ===================== HEADER ===================== -->
            <div class="card">
                <div class="card-body">

                    <div class="row mb-3 mt-3">
                        <label class="col-sm-2 col-form-label"><strong>Tanggal Pengkajian</strong></label>
                        <div class="col-sm-9">
                            <input type="date" class="form-control" name="tgl_pengkajian"
                                value="<?= htmlspecialchars($tgl_pengkajian) ?>" <?= $ro ?> required>
                            <div class="invalid-feedback">Harap isi Tanggal Pengkajian.</div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>RS/Ruangan</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="rs_ruangan"
                                value="<?= htmlspecialchars($rs_ruangan) ?>" <?= $ro ?> required>
                            <div class="invalid-feedback">Harap isi RS/Ruangan.</div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- ===================== A. KONSEP DASAR MEDIS ===================== -->
            <div class="card">
                <div class="card-body">

                    <h5 class="card-title"><strong>A. Konsep Dasar Medis</strong></h5>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Pengertian</strong></label>
                        <div class="col-sm-9">
                            <textarea name="pengertian" class="form-control" rows="3"
                                style="overflow:hidden; resize:none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('pengertian', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Klasifikasi</strong></label>
                        <div class="col-sm-9">
                            <textarea name="klasifikasi" class="form-control" rows="3"
                                style="overflow:hidden; resize:none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('klasifikasi', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Etiologi</strong></label>
                        <div class="col-sm-9">
                            <textarea name="etiologi" class="form-control" rows="3"
                                style="overflow:hidden; resize:none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('etiologi', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Manifestasi Klinik</strong></label>
                        <div class="col-sm-9">
                            <textarea name="manifestasi_klinik" class="form-control" rows="3"
                                style="overflow:hidden; resize:none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('manifestasi_klinik', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Patofisiologi</strong></label>
                        <div class="col-sm-9">
                            <textarea name="patofisiologi" class="form-control" rows="3"
                                style="overflow:hidden; resize:none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('patofisiologi', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Pemeriksaan Diagnostik</strong></label>
                        <div class="col-sm-9">
                            <textarea name="pemeriksaan_diagnostik" class="form-control" rows="3"
                                style="overflow:hidden; resize:none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('pemeriksaan_diagnostik', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Penatalaksanaan</strong></label>
                        <div class="col-sm-9">
                            <textarea name="penatalaksanaan" class="form-control" rows="3"
                                style="overflow:hidden; resize:none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('penatalaksanaan', $existing_data) ?></textarea>
                        </div>
                    </div>

                </div>
            </div>

            <!-- ===================== B. KONSEP DASAR KEPERAWATAN ===================== -->
            <div class="card">
                <div class="card-body">

                    <h5 class="card-title"><strong>B. Konsep Dasar Keperawatan</strong></h5>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Pengkajian Keperawatan</strong></label>
                        <div class="col-sm-9">
                            <textarea name="pengkajian_keperawatan" class="form-control" rows="3"
                                style="overflow:hidden; resize:none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('pengkajian_keperawatan', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- Penyimpangan KDM -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Penyimpangan KDM</strong></label>
                        <div class="col-sm-9">
                            <?php if (!empty($existing_kdm)): ?>
                                <img src="<?= htmlspecialchars($existing_kdm) ?>"
                                    class="img-fluid rounded border mb-2"
                                    style="max-height:400px;">
                            <?php endif; ?>
                            <?php if (!$is_readonly): ?>
                                <input type="file" class="form-control" name="penyimpangan_kdm"
                                    accept="image/jpeg,image/png,image/webp">
                                <small class="text-muted">Format: JPG, PNG, WebP. Maks 2MB.</small>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Diagnosa Keperawatan</strong></label>
                        <div class="col-sm-9">
                            <textarea name="diagnosa_keperawatan" class="form-control" rows="3"
                                style="overflow:hidden; resize:none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('diagnosa_keperawatan', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- TABEL PERENCANAAN -->
                    <p class="text-primary fw-bold mb-2">Perencanaan</p>

                    <table class="table table-bordered" id="tabel-perencanaan">
                        <thead>
                            <tr>
                                <th class="text-center" style="width:40px">No</th>
                                <th class="text-center">Diagnosa</th>
                                <th class="text-center">Tujuan dan Kriteria Hasil</th>
                                <th class="text-center">Intervensi</th>
                                <?php if (!$is_readonly): ?>
                                    <th class="text-center" style="width:60px">Aksi</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody id="tbody-perencanaan"></tbody>
                    </table>

                    <?php if (!$is_readonly): ?>
                        <div class="row mb-4">
                            <div class="col-sm-12 d-flex justify-content-end">
                                <button type="button" class="btn btn-primary btn-sm" onclick="tambahRowPerencanaan()">+ Tambah Baris</button>
                            </div>
                        </div>
                    <?php endif; ?>

                </div>
            </div>

            <!-- ===================== C. DAFTAR PUSTAKA ===================== -->
            <div class="card">
                <div class="card-body">

                    <h5 class="card-title"><strong>C. Daftar Pustaka</strong></h5>

                    <div id="list-pustaka"></div>

                    <?php if (!$is_readonly): ?>
                        <div class="row mb-3">
                            <div class="col-sm-12 d-flex justify-content-end">
                                <button type="button" class="btn btn-primary btn-sm " onclick="tambahPustaka()">+ Tambah Pustaka</button>
                            </div>
                        </div>
                    <?php endif; ?>

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



            <script>
                let rowPerencanaanCount = 1;
                let pustakCount = 0;
                const isReadonly = <?= $is_readonly ? 'true' : 'false' ?>;

                const existingPerencanaan = <?= json_encode($existing_perencanaan) ?>;
                const existingDaftarPustaka = <?= json_encode($existing_daftar_pustaka) ?>;

                // ---- PERENCANAAN ----
                function tambahRowPerencanaan(data = null) {
                    const tbody = document.getElementById('tbody-perencanaan');
                    const index = rowPerencanaanCount;
                    const row = document.createElement('tr');
                    const aksiCol = isReadonly ? '' : `
                        <td class="text-center align-middle">
                            <button type="button" class="btn btn-danger btn-sm" onclick="hapusRow(this)">x</button>
                        </td>`;

                    row.innerHTML = `
                        <td class="text-center align-middle">${index}</td>
                        <td>
                            <textarea class="form-control form-control-sm"
                                name="perencanaan[${index}][diagnosa]"
                                rows="2" style="resize:none; overflow:hidden;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                ${isReadonly ? 'readonly' : ''}
                            >${data?.diagnosa ?? ''}</textarea>
                        </td>
                        <td>
                            <textarea class="form-control form-control-sm"
                                name="perencanaan[${index}][tujuan_kriteria]"
                                rows="2" style="resize:none; overflow:hidden;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                ${isReadonly ? 'readonly' : ''}
                            >${data?.tujuan_kriteria ?? ''}</textarea>
                        </td>
                        <td>
                            <textarea class="form-control form-control-sm"
                                name="perencanaan[${index}][intervensi]"
                                rows="2" style="resize:none; overflow:hidden;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                ${isReadonly ? 'readonly' : ''}
                            >${data?.intervensi ?? ''}</textarea>
                        </td>
                        ${aksiCol}
                    `;

                    tbody.appendChild(row);
                    rowPerencanaanCount++;
                }

                // ---- DAFTAR PUSTAKA ----
                function tambahPustaka(value = '') {
                    const container = document.getElementById('list-pustaka');
                    const index = pustakCount;
                    const div = document.createElement('div');

                    div.className = 'row mb-2 pustaka-item';
                    div.innerHTML = isReadonly ?
                        `<div class="col-sm-11 d-flex align-items-center gap-2">
                                <span class="text-muted fw-bold" style="min-width:24px;">${index + 1}.</span>
                                <input type="text" class="form-control" value="${value}" readonly>
                           </div>` :
                        `<div class="col-sm-11 d-flex align-items-center gap-2">
                                <span class="text-muted fw-bold" style="min-width:24px;">${index + 1}.</span>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="daftar_pustaka[]"
                                        value="${value}" placeholder="Masukkan referensi pustaka...">
                                    <button type="button" class="btn btn-danger" onclick="hapusPustaka(this)">x</button>
                                </div>
                           </div>`;

                    container.appendChild(div);
                    pustakCount++;
                }

                function hapusRow(btn) {
                    btn.closest('tr').remove();
                }

                function hapusPustaka(btn) {
                    btn.closest('.pustaka-item').remove();
                    document.querySelectorAll('.pustaka-item').forEach((item, i) => {
                        item.querySelector('span.text-muted').textContent = (i + 1) + '.';
                    });
                }

                // Load existing data on page load
                window.addEventListener('load', function() {
                    if (existingPerencanaan && existingPerencanaan.length > 0) {
                        existingPerencanaan.forEach(row => tambahRowPerencanaan(row));
                    } else if (!isReadonly) {
                        tambahRowPerencanaan();
                    }

                    if (existingDaftarPustaka && existingDaftarPustaka.length > 0) {
                        existingDaftarPustaka.forEach(v => tambahPustaka(v));
                    } else if (!isReadonly) {
                        tambahPustaka();
                    }
                });

                const existingData = <?= json_encode($existing_data) ?>;
            </script>

        </form>

        <!-- ================================ -->
        <!-- SECTION KOMENTAR & ACTION DOSEN -->
        <!-- ================================ -->
        <div class="card mt-3">
            <div class="card-body">
                <h5 class="card-title"><strong>Komentar</strong></h5>

                <?php if (!empty($comments)): ?>
                    <?php foreach ($comments as $cmt): ?>
                        <div class="alert alert-warning">
                            <strong><?= htmlspecialchars($cmt['dosen_name']) ?></strong>
                            <small class="text-muted ms-2"><?= date('d/m/Y H:i', strtotime($cmt['created_at'])) ?></small>
                            <p class="mb-0 mt-1"><?= htmlspecialchars($cmt['comment']) ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-muted">Belum ada komentar.</p>
                <?php endif; ?>

                <?php if ($is_dosen && $section_status !== 'approved'): ?>
                    <form action="" method="POST">
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Komentar</strong></label>
                            <div class="col-sm-9">
                                <textarea name="comment" class="form-control" rows="3"
                                    placeholder="Tulis komentar (wajib jika meminta revisi)..."></textarea>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-11 d-flex justify-content-end gap-2">
                                <button type="submit" name="action" value="revision" class="btn btn-warning">Minta Revisi</button>
                                <button type="submit" name="action" value="approve" class="btn btn-success">Approve</button>
                            </div>
                        </div>
                    </form>
                <?php elseif ($is_dosen && $section_status === 'approved'): ?>
                    <div class="alert alert-success">Section ini sudah di-approve.</div>
                <?php endif; ?>

            </div>
        </div>

        <?php include "tab_navigasi.php"; ?>

    </section>
</main>