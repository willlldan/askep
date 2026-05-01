<?php
require_once "koneksi.php";
require_once "utils.php";

$form_id       = 6;
$level         = $_SESSION['level'];
$user_id       = $_SESSION['id_user'];
$section_name  = 'pengkajian_umum';
$section_label = 'Pengkajian Umum';

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

$is_dosen    = $level === 'Dosen';
$is_readonly = $is_dosen || isLocked($submission);
$ro          = $is_readonly ? 'readonly' : '';
$ro_disabled = $is_readonly ? 'disabled' : '';

// =============================================
// HANDLE POST - MAHASISWA
// =============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $level === 'Mahasiswa') {
    if (isLocked($submission)) {
        redirectWithMessage($_SERVER['REQUEST_URI'], 'error', 'Data tidak dapat diubah karena sedang dalam proses review.');
    }

    $text_fields = [
        // Resusitasi & Obat
        'resusitasi',
        'vitamin_k', 'salep_mata', 'o2',
        // Pernapasan
        'nafas_spontan', 'frekuensi_nafas', 'nafas_teratur', 'suara_nafas',
        // Asupan Cairan
        'asi_frekuensi', 'asi_jumlah',
        'formula_frekuensi', 'formula_jumlah',
        'infus_jenis', 'infus_jumlah',
        // Eliminasi BAB
        'bab_mekonium', 'bab_frekuensi', 'bab_warna',
        // Eliminasi BAK
        'bak_frekuensi', 'bak_warna',
        // Istirahat & Tidur
        'lama_tidur', 'keadaan_tidur',
        // Antropometri
        'bb', 'pb', 'lk', 'ld', 'lp', 'lila',
        // TTV
        'keadaan_umum', 'tekanan_darah', 'nadi', 'suhu', 'pernapasan_ttv',
    ];

    $data = [];
    foreach ($text_fields as $f) {
        $data[$f] = $_POST[$f] ?? '';
    }

    if (!$submission) {
        $submission_id = createSubmission($user_id, $form_id, null, null, $mysqli);
    } else {
        $submission_id = $submission['id'];
    }

    saveSection($submission_id, $section_name, $section_label, $data, $mysqli);
    updateSubmissionStatus($submission_id, $form_id, $mysqli);
    redirectWithMessage($_SERVER['REQUEST_URI'], 'success', 'Data berhasil disimpan.');
}

// =============================================
// HANDLE POST - DOSEN
// =============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $level === 'Dosen') {
    $submission_id = $submission['id'];
    $dosen_id      = $user_id;
    $action        = $_POST['action'] ?? '';
    $comment       = $_POST['comment'] ?? '';

    if ($action === 'approve') {
        updateSectionStatus($submission_id, $section_name, 'approved', $mysqli);
        if (!empty($comment)) saveComment($submission_id, $section_name, $comment, $dosen_id, $mysqli);
    } elseif ($action === 'revision') {
        if (empty($comment)) redirectWithMessage($_SERVER['REQUEST_URI'], 'error', 'Komentar wajib diisi saat meminta revisi.');
        updateSectionStatus($submission_id, $section_name, 'revision', $mysqli);
        saveComment($submission_id, $section_name, $comment, $dosen_id, $mysqli);
    }

    updateReviewer($submission_id, $dosen_id, $mysqli);
    updateSubmissionStatusByDosen($submission_id, $form_id, $mysqli);
    redirectWithMessage($_SERVER['REQUEST_URI'], 'success', 'Berhasil disimpan.');
}

$comments = $submission ? getSectionComments($submission['id'], $section_name, $mysqli) : [];

function ed($key, $data) {
    return htmlspecialchars($data[$key] ?? '');
}
?>

<main id="main" class="main">
    <?php include "anak/format_aster/tab.php"; ?>

    <section class="section dashboard">

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <?php if ($section_status): ?>
            <?php $badge = ['draft'=>'secondary','submitted'=>'primary','revision'=>'warning','approved'=>'success']; ?>
            <div class="alert alert-<?= $badge[$section_status] ?>">
                Status: <strong><?= ucfirst($section_status) ?></strong>
                | Reviewed by: <strong><?= $submission['dosen_name'] ? htmlspecialchars($submission['dosen_name']) : '-' ?></strong>
            </div>
        <?php endif; ?>

        <form class="needs-validation" novalidate action="" method="POST">

            <!-- ===================== RESUSITASI & OBAT-OBATAN ===================== -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><strong>B. Pengkajian Umum</strong></h5>

                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary"><strong>Resusitasi</strong></label>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Resusitasi</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="resusitasi"
                                value="<?= ed('resusitasi', $existing_data) ?>"
                                placeholder="Jenis resusitasi yang dilakukan" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-2 mt-3">
                        <label class="col-sm-12 text-primary"><strong>Obat-obatan</strong></label>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Vitamin K</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="vitamin_k"
                                value="<?= ed('vitamin_k', $existing_data) ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Salep Mata</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="salep_mata"
                                value="<?= ed('salep_mata', $existing_data) ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Pemberian O2</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="o2"
                                value="<?= ed('o2', $existing_data) ?>" <?= $ro ?>>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ===================== PERNAPASAN ===================== -->
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary"><strong>Pernapasan</strong></label>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Spontan / Tidak</strong></label>
                        <div class="col-sm-9">
                            <div class="d-flex gap-4 mt-2">
                                <?php foreach (['Spontan', 'Tidak Spontan'] as $opt): ?>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="nafas_spontan"
                                        value="<?= $opt ?>"
                                        id="nafas_<?= str_replace(' ', '_', $opt) ?>"
                                        <?= $ro_disabled ?>
                                        <?= (ed('nafas_spontan', $existing_data) === $opt) ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="nafas_<?= str_replace(' ', '_', $opt) ?>">
                                        <?= $opt ?>
                                    </label>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Frekuensi</strong></label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <input type="text" class="form-control" name="frekuensi_nafas"
                                    value="<?= ed('frekuensi_nafas', $existing_data) ?>" <?= $ro ?>>
                                <span class="input-group-text">x/menit</span>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Teratur / Tidak</strong></label>
                        <div class="col-sm-9">
                            <div class="d-flex gap-4 mt-2">
                                <?php foreach (['Teratur', 'Tidak Teratur'] as $opt): ?>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="nafas_teratur"
                                        value="<?= $opt ?>"
                                        id="teratur_<?= str_replace(' ', '_', $opt) ?>"
                                        <?= $ro_disabled ?>
                                        <?= (ed('nafas_teratur', $existing_data) === $opt) ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="teratur_<?= str_replace(' ', '_', $opt) ?>">
                                        <?= $opt ?>
                                    </label>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Suara Nafas</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="suara_nafas"
                                value="<?= ed('suara_nafas', $existing_data) ?>" <?= $ro ?>>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ===================== ASUPAN CAIRAN ===================== -->
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary"><strong>Asupan Cairan</strong></label>
                    </div>

                    <!-- ASI -->
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>ASI</strong></label>
                        <div class="col-sm-9">
                            <div class="row g-2">
                                <div class="col-sm-6">
                                    <label class="form-label"><small>Frekuensi</small></label>
                                    <input type="text" class="form-control" name="asi_frekuensi"
                                        value="<?= ed('asi_frekuensi', $existing_data) ?>"
                                        placeholder="x/hari" <?= $ro ?>>
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label"><small>Jumlah</small></label>
                                    <input type="text" class="form-control" name="asi_jumlah"
                                        value="<?= ed('asi_jumlah', $existing_data) ?>"
                                        placeholder="ml" <?= $ro ?>>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Susu Formula -->
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Susu Formula</strong></label>
                        <div class="col-sm-9">
                            <div class="row g-2">
                                <div class="col-sm-6">
                                    <label class="form-label"><small>Frekuensi</small></label>
                                    <input type="text" class="form-control" name="formula_frekuensi"
                                        value="<?= ed('formula_frekuensi', $existing_data) ?>"
                                        placeholder="x/hari" <?= $ro ?>>
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label"><small>Jumlah</small></label>
                                    <input type="text" class="form-control" name="formula_jumlah"
                                        value="<?= ed('formula_jumlah', $existing_data) ?>"
                                        placeholder="ml" <?= $ro ?>>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Infus -->
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Infus</strong></label>
                        <div class="col-sm-9">
                            <div class="row g-2">
                                <div class="col-sm-6">
                                    <label class="form-label"><small>Jenis Cairan</small></label>
                                    <input type="text" class="form-control" name="infus_jenis"
                                        value="<?= ed('infus_jenis', $existing_data) ?>" <?= $ro ?>>
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label"><small>Jumlah</small></label>
                                    <input type="text" class="form-control" name="infus_jumlah"
                                        value="<?= ed('infus_jumlah', $existing_data) ?>"
                                        placeholder="ml/jam" <?= $ro ?>>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ===================== ELIMINASI ===================== -->
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary"><strong>Eliminasi</strong></label>
                    </div>

                    <!-- BAB -->
                    <div class="row mb-2">
                        <label class="col-sm-12"><strong>BAB</strong></label>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Kapan Pengeluaran Mekonium</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="bab_mekonium"
                                value="<?= ed('bab_mekonium', $existing_data) ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Frekuensi</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="bab_frekuensi"
                                value="<?= ed('bab_frekuensi', $existing_data) ?>"
                                placeholder="x/hari" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Warna</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="bab_warna"
                                value="<?= ed('bab_warna', $existing_data) ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <!-- BAK -->
                    <div class="row mb-2 mt-3">
                        <label class="col-sm-12"><strong>BAK</strong></label>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Frekuensi</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="bak_frekuensi"
                                value="<?= ed('bak_frekuensi', $existing_data) ?>"
                                placeholder="x/hari" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Warna</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="bak_warna"
                                value="<?= ed('bak_warna', $existing_data) ?>" <?= $ro ?>>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ===================== ISTIRAHAT & TIDUR ===================== -->
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary"><strong>Istirahat dan Tidur</strong></label>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Lamanya</strong></label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <input type="text" class="form-control" name="lama_tidur"
                                    value="<?= ed('lama_tidur', $existing_data) ?>" <?= $ro ?>>
                                <span class="input-group-text">jam/hari</span>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Keadaan Waktu Tidur</strong></label>
                        <div class="col-sm-9">
                            <textarea class="form-control" name="keadaan_tidur" rows="2"
                                style="overflow:hidden; resize:none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= ed('keadaan_tidur', $existing_data) ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ===================== ANTROPOMETRI ===================== -->
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary"><strong>Pengukuran Antropometri</strong></label>
                    </div>

                    <?php
                    $antro = [
                        ['name'=>'bb',   'label'=>'Penimbangan Berat Badan',    'unit'=>'gram'],
                        ['name'=>'pb',   'label'=>'Pengukuran Panjang Badan',   'unit'=>'cm'],
                        ['name'=>'lk',   'label'=>'Lingkar Kepala',             'unit'=>'cm'],
                        ['name'=>'ld',   'label'=>'Lingkar Dada',               'unit'=>'cm'],
                        ['name'=>'lp',   'label'=>'Lingkar Perut',              'unit'=>'cm'],
                        ['name'=>'lila', 'label'=>'Lingkar Lengan Atas (LILA)', 'unit'=>'cm'],
                    ];
                    foreach ($antro as $a):
                    ?>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong><?= $a['label'] ?></strong></label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <input type="text" class="form-control" name="<?= $a['name'] ?>"
                                    value="<?= ed($a['name'], $existing_data) ?>" <?= $ro ?>>
                                <span class="input-group-text"><?= $a['unit'] ?></span>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- ===================== PEMERIKSAAN FISIK UMUM + TTV ===================== -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><strong>C. Pemeriksaan Fisik</strong></h5>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label text-primary"><strong>Keadaan Umum</strong></label>
                        <div class="col-sm-9">
                            <textarea class="form-control" name="keadaan_umum" rows="2"
                                style="overflow:hidden; resize:none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= ed('keadaan_umum', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary"><strong>Tanda-Tanda Vital</strong></label>
                    </div>

                    <?php
                    $ttv = [
                        ['name'=>'tekanan_darah',  'label'=>'Tekanan Darah', 'unit'=>'mmHg'],
                        ['name'=>'nadi',            'label'=>'Denyut Nadi',   'unit'=>'x/menit'],
                        ['name'=>'suhu',            'label'=>'Suhu',          'unit'=>'°C'],
                        ['name'=>'pernapasan_ttv',  'label'=>'Pernapasan',    'unit'=>'x/menit'],
                    ];
                    foreach ($ttv as $t):
                    ?>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong><?= $t['label'] ?></strong></label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <input type="text" class="form-control" name="<?= $t['name'] ?>"
                                    value="<?= ed($t['name'], $existing_data) ?>" <?= $ro ?>>
                                <span class="input-group-text"><?= $t['unit'] ?></span>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>

                    <!-- TOMBOL SIMPAN -->
                    <?php if (!$is_dosen): ?>
                    <div class="row mb-3">
                        <div class="col-sm-12 d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary" <?= $ro_disabled ?>>Simpan Data</button>
                        </div>
                    </div>
                    <?php endif; ?>

                </div>
            </div>

        </form>

        <!-- ===================== KOMENTAR & ACTION DOSEN ===================== -->
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