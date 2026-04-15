<?php
require_once "koneksi.php";
require_once "utils.php";

$form_id       = 3;
$level         = $_SESSION['level'];
$user_id       = $_SESSION['id_user'];
$section_name  = 'riwayat_kehamilan';
$section_label = 'Riwayat Kehamilan';

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

$existing_data  = $submission ? getSectionData($submission['id'], $section_name, $mysqli) : [];
$section_status = $submission ? getSectionStatus($submission['id'], $section_name, $mysqli) : null;
$tgl_pengkajian = $submission['tanggal_pengkajian'] ?? '';
$rs_ruangan     = $submission['rs_ruangan'] ?? '';

// Load existing dynamic rows
$existing_riwayat = $existing_data['riwayat'] ?? [];
// =============================================
// HANDLE POST - MAHASISWA SIMPAN DATA
// =============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $level === 'Mahasiswa') {

    if (isLocked($submission)) {
        redirectWithMessage($_SERVER['REQUEST_URI'], 'error', 'Data tidak dapat diubah karena sedang dalam proses review.');
    }

 $riwayat = [];
    if (!empty($_POST['riwayat'])) {
        foreach ($_POST['riwayat'] as $index => $row) {
            if (empty($row['jenis_persalinan']) && empty($row['penolong']) && empty($row['jenis_kelamin'])&& empty($row['bbtb_bayi'])&& empty($row['menyesui_berapa_lama'])
                && empty($row['masalah_kehamilan'])) {
                continue;
            }
            $riwayat[] = [
                'jenis_persalinan'      => $row['jenis_persalinan']     ?? '',
                'penolong'              => $row['penolong']           ?? '',
                'jenis_kelamin'         => $row['jenis_kelamin']        ?? '',
                'bbtb_bayi'             => $row['bbtb_bayi']  ?? '',
                'menyesui_berapa_lama'  => $row['menyesui_berapa_lama']  ?? '',
                'masalah_kehamilan'     => $row['masalah_kehamilan']  ?? '',
            ];
        }
    }
    $tgl_pengkajian = $_POST['tglpengkajian'] ?? '';
    $rs_ruangan     = $_POST['rsruangan'] ?? '';
    $data = [
        'riwayat' => $riwayat,
        'pemeriksaan'            => $_POST['pemeriksaan'] ?? '',
        'masalah_kehamilan'      => $_POST['masalahkehamilan'] ?? '',
        'riwayat_persalinan'     => $_POST['riwayatpersalinan'] ?? '',
        'riwayat_kb'             => $_POST['riwayatkb'] ?? '',
        'jumlah_pendarahan'      => $_POST['jumlahpendarahan'] ?? '',
        // 'tahun'                  => $_POST['tahun'] ?? '',
        // 'jenis_persalinan'       => $_POST['jenispersalinan'] ?? '',
        // 'penolong'               => $_POST['penolong'] ?? '',
        // 'jenis_kelamin'          => $_POST['jeniskelamin'] ?? '',
        // 'bb_tb_bayi'             => $_POST['bbtbbayi'] ?? '',
        // 'menyusui'               => $_POST['menyusui'] ?? '',
        // 'keluhan_utama'          => $_POST['keluhanutama'] ?? '',,
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
// HANDLE POST - DOSEN APPROVE / REVISI / KOMENTAR
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

// Load komentar section (untuk dosen & mahasiswa)
$comments = $submission ? getSectionComments($submission['id'], $section_name, $mysqli) : [];

// Readonly jika mahasiswa + locked, atau jika dosen
$is_dosen    = $level === 'Dosen';
$is_readonly = $is_dosen || isLocked($submission);
$ro          = $is_readonly ? 'readonly' : '';
$ro_select   = $is_readonly ? 'disabled' : '';
?>

<main id="main" class="main">
    <?php include "navbar_maternitas.php"; ?>
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?= $_SESSION['success'];
                                                unset($_SESSION['success']); ?></div>
        <?php endif; ?>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= $_SESSION['error'];
                                            unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <!-- Info status section (untuk dosen) -->
        <?php if  ($section_status): ?>
            <?php
            $badge = [
                'draft'     => 'secondary',
                'submitted' => 'primary',
                'revision'  => 'warning',
                'approved'  => 'success',
            ];
            ?>

             <div class="alert alert-<?= $badge[$section_status] ?>">
                Status: <strong><?= ucfirst($section_status) ?></strong>
                    | Reviewed by: <strong><?php echo $submission['dosen_name'] ? htmlspecialchars($submission['dosen_name']) : '-'; ?></strong>       
            </div>
        <?php endif; ?>
    


     <section class="section dashboard">
        <div class="card">
            <div class="card-body">

                <h5 class="card-title"><strong>RIWAYAT KEHAMILAN DAN PERSALINAN YANG LALU</strong></h5>

                <!-- General Form Elements -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">
                
                <!-- Bagian Pemeriksaan -->
                <div class="row mb-3">
                    <label for="pemeriksaan" class="col-sm-2 col-form-label"><strong>Berapa kali pemeriksaan ANC (kehamilan)?</strong></label>
                    <div class="col-sm-9">
                        <textarea name="pemeriksaan" class="form-control" rows="5" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('pemeriksaan', $existing_data) ?></textarea>
                        
                      
                         </div>
                    </div>
                    
                <!-- Bagian Masalah Kehamilan -->
                <div class="row mb-3">
                    <label for="masalahkehamilan" class="col-sm-2 col-form-label"><strong>Masalah yang dialami selama hamil dan tindakan pengotaban yang dilakukan</strong></label>
                    <div class="col-sm-9">
                        <textarea name="masalahkehamilan" class="form-control" rows="5" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                        <?= $ro ?>><?= val('masalah_kehamilan', $existing_data) ?></textarea>
                        
                     
                         </div>
                    </div>

                <!-- Bagian Riwayat Persalinan -->
                <div class="row mb-3">
                    <label for="riwayatpersalinan" class="col-sm-2 col-form-label"><strong>Riwayat Persalinan apakah Spontan/Letkep/Letsu/Sectio Caesarea (jika SC atas indikasi apa?)</strong></label>
                    <div class="col-sm-9">
                        <textarea name="riwayatpersalinan" class="form-control" rows="5" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                    <?= $ro ?>><?= val('riwayat_persalinan', $existing_data) ?></textarea>
                        
                        
                         </div>
                    </div>

                <!-- Bagian Riwayat KB -->
                <div class="row mb-3">
                    <label for="riwayatkb" class="col-sm-2 col-form-label"><strong>Riwayat KB (Jenis, Berapa lama penggunaan)</strong></label>
                    <div class="col-sm-9">
                        <textarea name="riwayatkb" class="form-control" rows="5" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                    <?= $ro ?>><?= val('riwayat_kb', $existing_data) ?></textarea>
                        
                       
                         </div>
                    </div>

                <!-- Bagian Jumlah Pendarahan -->
                <div class="row mb-3">
                    <label for="jumlahpendarahan" class="col-sm-2 col-form-label"><strong>Jumlah pendarahan saat melahirkan</strong></label>
                    <div class="col-sm-9">
                        <textarea name="jumlahpendarahan" class="form-control" rows="5" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                    <?= $ro ?>><?= val('jumlah_pendarahan', $existing_data) ?></textarea>
                        
                      
                         </div>
                    </div>
<!-- ===================== TABEL RIWAYAT ===================== -->
                    <p class="text-primary fw-bold mb-2">Riwayat Persalinan yang Lalu</p>
                    <table class="table table-bordered" id="tabel-riwayat">
                        <thead>
                            <tr>
                                <th class="text-center" style="width:40px">No</th>
                                <th class="text-center">Jenis Persalinan</th>
                                <th class="text-center">Penolong</th>
                                <th class="text-center">Jenis Kelamin</th>
                                <th class="text-center">BB/TB Bayi</th>
                                <th class="text-center">Menyusui Berapa Lama</th>
                                <th class="text-center">Masalah Kehamilan</th>
                                <th class="text-center" style="width:60px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="tbody-riwayat">
                            <!-- Dynamic rows masuk sini -->
                        </tbody>
                    </table>
                    <div class="row mb-4">
                        <div class="col-sm-12 d-flex justify-content-end">
                            <button type="button" class="btn btn-primary btn-sm" id="btn-tambah-riwayat" onclick="tambahRowRiwayat()">+ Tambah Riwayat</button>
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
                        let rowRiwayatCount = 1;
                        // let rowLabCount  = 1;
                        const existingRiwayat = <?= json_encode($existing_riwayat) ?>;
                        // const existingLab  = ?= json_encode($existing_lab) ?>;
                        const isReadonly = <?= json_encode($is_readonly) ?>;
                        // ---- Riwayat ----
                        function tambahRowRiwayat(data = null) {
                            const tbody = document.getElementById('tbody-riwayat');
                            const index = rowRiwayatCount;
                            const row   = document.createElement('tr');
                            row.innerHTML = `
                                <td class="text-center align-middle">${index}</td>
                                <td><input type="text" class="form-control form-control-sm" name="riwayat[${index}][jenis_persalinan]" value="${data?.jenis_persalinan ?? ''}" ${isReadonly ? 'readonly' : ''}></td>
                                <td><input type="text" class="form-control form-control-sm" name="riwayat[${index}][penolong]" value="${data?.penolong ?? ''}" ${isReadonly ? 'readonly' : ''}></td>
                                <td><input type="text" class="form-control form-control-sm" name="riwayat[${index}][jenis_kelamin]" value="${data?.jenis_kelamin ?? ''}" ${isReadonly ? 'readonly' : ''}></td>
                                <td><input type="text" class="form-control form-control-sm" name="riwayat[${index}][bbtb_bayi]" value="${data?.bbtb_bayi ?? ''}" ${isReadonly ? 'readonly' : ''}></td>
                                <td><input type="text" class="form-control form-control-sm" name="riwayat[${index}][menyesui_berapa_lama]" value="${data?.menyesui_berapa_lama ?? ''}" ${isReadonly ? 'readonly' : ''}></td>
                                <td><input type="text" class="form-control form-control-sm" name="riwayat[${index}][masalah_kehamilan]" value="${data?.masalah_kehamilan ?? ''}" ${isReadonly ? 'readonly' : ''}></td>

                                <td class="text-center align-middle">
                                    <button type="button" class="btn btn-danger btn-sm" onclick="hapusRow(this)" ${isReadonly ? 'disabled' : ''}>x</button>
                                </td>
                            `;
                            tbody.appendChild(row);
                            rowRiwayatCount++;
                        }
                        // // ---- LAB ----
                        // function tambahRowLab(data = null) {
                        //     const tbody = document.getElementById('tbody-lab');
                        //     const index = rowLabCount;
                        //     const row   = document.createElement('tr');
                        //     row.innerHTML = `
                        //         <td class="text-center align-middle">${index}</td>
                        //         <td><input type="text" class="form-control form-control-sm" name="lab[${index}][pemeriksaan]" value="${data?.pemeriksaan ?? ''}" ${isReadonly ? 'readonly' : ''}></td>
                        //         <td><input type="text" class="form-control form-control-sm" name="lab[${index}][hasil]" value="${data?.hasil ?? ''}" ${isReadonly ? 'readonly' : ''}></td>
                        //         <td><input type="text" class="form-control form-control-sm" name="lab[${index}][nilai_normal]" value="${data?.nilai_normal ?? ''}" ${isReadonly ? 'readonly' : ''}></td>
                        //         <td class="text-center align-middle">
                        //             <button type="button" class="btn btn-danger btn-sm" onclick="hapusRow(this)" ${isReadonly ? 'disabled' : ''}>x</button>
                        //         </td>
                        //     `;
                        //     tbody.appendChild(row);
                        //     rowLabCount++;
                        // }
                        function hapusRow(btn) {
                            btn.closest('tr').remove();
                        }
                        // Load existing rows on page load
                        window.addEventListener('load', function () {
                            if (existingRiwayat && existingRiwayat.length > 0) {
                                existingRiwayat.forEach(row => tambahRowRiwayat(row));
                            } else {
                                tambahRowRiwayat(); // default 1 row kosong
                            }
                            // if (existingLab && existingLab.length > 0) {
                            //     existingLab.forEach(row => tambahRowLab(row));
                            // } else {
                            //     tambahRowLab(); // default 1 row kosong
                            // }
                           // Disable add buttons if readonly
                            
 
                            if (isReadonly) {
                                document.getElementById('btn-tambah-riwayat').setAttribute('disabled', 'disabled');
                                // document.getElementById('btn-tambah-lab').setAttribute('disabled', 'disabled');
                            }
                        });
                        const existingData = <?= json_encode($existing_data) ?>;
                         </script>
                </form>
              
            </div>
        </div>
        <!-- ================================ -->
        <!-- SECTION KOMENTAR & ACTION DOSEN -->
        <!-- ================================ -->
        <div class="card mt-3">
            <div class="card-body">
                <h5 class="card-title"><strong>Komentar</strong></h5>

                <!-- List komentar -->
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

                <!-- Form komentar + action (khusus dosen) -->
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
                                <button type="submit" name="action" value="revision" class="btn btn-warning">
                                    Minta Revisi
                                </button>
                                <button type="submit" name="action" value="approve" class="btn btn-success">
                                    Approve
                                </button>
                            </div>
                        </div>
                    </form>
                <?php elseif ($is_dosen && $section_status === 'approved'): ?>
                    <div class="alert alert-success">
                        Section ini sudah di-approve.
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <?php include "tab_navigasi.php"; ?>

    </section>
</main>