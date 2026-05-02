<?php
require_once "koneksi.php";
require_once "utils.php";

$form_id       = 6;
$level         = $_SESSION['level'];
$user_id       = $_SESSION['id_user'];
$section_name  = 'pemfis_1';
$section_label = 'Pemeriksaan Fisik 1';

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
        // Kepala
        'keadaan_kepala',
        'fontenel',
        'trauma_kepala',
        // Wajah
        'wajah_simetris',
        'laserasi',
        'paresis',
        // Mata
        'mata_terbuka',
        'mata_jumlah',
        'mata_posisi',
        'mata_letak',
        'strabismus',
        'katarak',
        'trauma_palpebral',
        'palpebra',
        'sclera',
        'radang_conjungtiva',
        'anemis',
        'pupil_bentuk',
        'pupil_ukuran',
        'refleks_pupil',
        'refleks_pupil_ket',
        'gerakan_mata',
        'kelopak_mata',
        'bulu_mata',
        'mata_lain',
        // Hidung
        'hidung_bentuk',
        'cuping',
        'septum',
        'secret_hidung',
        'hidung_lain',
        // Telinga
        'telinga_bentuk',
        'letak_telinga',
        'lubang_telinga',
        'nyeri_telinga',
        // Mulut
        'gusi_ket',
        'lidah',
        'bibir_warna_ket',
        'bibir_kondisi_ket',
        'bau_mulut',
        'bau_mulut_ket',
        'bibir_simetris',
        'labio_skizis',
        'palato_skizis',
        'bercak_putih',
        // Tenggorokan
        'warna_mukosa',
        'sumbatan',
        // Leher
        'limfe',
        'leher_simetris',
        'pembengkakan_leher',
        'lipatan_leher',
        'leher_lain',
    ];

    $checkbox_fields = ['gusi', 'bibir_warna', 'bibir_kondisi'];

    $data = [];
    foreach ($text_fields as $f) {
        $data[$f] = $_POST[$f] ?? '';
    }
    foreach ($checkbox_fields as $cf) {
        $data[$cf] = json_encode(isset($_POST[$cf]) ? (array)$_POST[$cf] : []);
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

// Decode checkbox fields
$cb_fields = ['gusi', 'bibir_warna', 'bibir_kondisi'];
foreach ($cb_fields as $cf) {
    $existing_data[$cf] = isset($existing_data[$cf])
        ? (json_decode($existing_data[$cf], true) ?? [])
        : [];
}

function ed($key, $data)
{
    return htmlspecialchars($data[$key] ?? '');
}

// Helper: radio row Ya/Tidak
function radioYaTidak($name, $existing, $disabled)
{
    $val = $existing[$name] ?? '';
    $out = '';
    foreach (['Ya', 'Tidak'] as $opt) {
        $checked = ($val === $opt) ? 'checked' : '';
        $id = $name . '_' . strtolower($opt);
        $out .= "<div class='form-check form-check-inline'>
            <input class='form-check-input' type='radio' name='{$name}' value='{$opt}' id='{$id}' {$checked} {$disabled}>
            <label class='form-check-label' for='{$id}'>{$opt}</label>
        </div>";
    }
    return $out;
}
?>

<main id="main" class="main">
    <?php include "anak/format_aster/tab.php"; ?>

    <section class="section dashboard">

        <?php include dirname(__DIR__, 2) . '/partials/notifikasi.php'; ?>
        <?php include dirname(__DIR__, 2) . '/partials/status_section.php'; ?>
        <form class="needs-validation" novalidate action="" method="POST">

            <!-- ===================== KEPALA ===================== -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><strong>Pemeriksaan Fisik</strong></h5>

                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary mt-3"><strong>a. Kepala</strong></label>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Keadaan Kepala</strong></label>
                        <div class="col-sm-9">
                            <?= radioYaTidak('keadaan_kepala', $existing_data, $ro_disabled) ?>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Keadaan Fontanel</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="fontenel"
                                value="<?= ed('fontenel', $existing_data) ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Adanya trauma kelahiran pada kepala</strong></label>
                        <div class="col-sm-9">
                            <?= radioYaTidak('trauma_kepala', $existing_data, $ro_disabled) ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ===================== WAJAH ===================== -->
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary mt-3"><strong>b. Wajah</strong></label>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Wajah Simetris</strong></label>
                        <div class="col-sm-9">
                            <?= radioYaTidak('wajah_simetris', $existing_data, $ro_disabled) ?>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Kelainan akibat trauma lahir — Laserasi</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="laserasi"
                                value="<?= ed('laserasi', $existing_data) ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Paresis N. Fasialis</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="paresis"
                                value="<?= ed('paresis', $existing_data) ?>" <?= $ro ?>>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ===================== MATA ===================== -->
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary mt-3"><strong>c. Mata</strong></label>
                    </div>
                    <div class="row mb-2">
                        <label class="col-sm-12"><em>Inspeksi</em></label>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Mata terbuka ketika kepala bayi digoyang perlahan</strong></label>
                        <div class="col-sm-9">
                            <?= radioYaTidak('mata_terbuka', $existing_data, $ro_disabled) ?>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Jumlah Mata / Posisi / Letak</strong></label>
                        <div class="col-sm-9">
                            <div class="row g-2">
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" name="mata_jumlah"
                                        placeholder="Jumlah"
                                        value="<?= ed('mata_jumlah', $existing_data) ?>" <?= $ro ?>>
                                </div>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" name="mata_posisi"
                                        placeholder="Posisi"
                                        value="<?= ed('mata_posisi', $existing_data) ?>" <?= $ro ?>>
                                </div>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" name="mata_letak"
                                        placeholder="Letak"
                                        value="<?= ed('mata_letak', $existing_data) ?>" <?= $ro ?>>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Adanya Strabismus</strong></label>
                        <div class="col-sm-9">
                            <?= radioYaTidak('strabismus', $existing_data, $ro_disabled) ?>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Adanya Katarak Kongenital</strong></label>
                        <div class="col-sm-9">
                            <?= radioYaTidak('katarak', $existing_data, $ro_disabled) ?>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Adanya trauma pada palpebral</strong></label>
                        <div class="col-sm-9">
                            <?= radioYaTidak('trauma_palpebral', $existing_data, $ro_disabled) ?>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Palpebra</strong></label>
                        <div class="col-sm-9">
                            <?php foreach (['Edema', 'Tidak Edema', 'Radang', 'Tidak Radang'] as $opt): ?>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="palpebra"
                                        value="<?= $opt ?>"
                                        id="palpebra_<?= str_replace(' ', '_', $opt) ?>"
                                        <?= $ro_disabled ?>
                                        <?= (ed('palpebra', $existing_data) === $opt) ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="palpebra_<?= str_replace(' ', '_', $opt) ?>">
                                        <?= $opt ?>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Sclera</strong></label>
                        <div class="col-sm-9">
                            <?php foreach (['Icterus', 'Tidak'] as $opt): ?>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="sclera"
                                        value="<?= $opt ?>" id="sclera_<?= $opt ?>"
                                        <?= $ro_disabled ?>
                                        <?= (ed('sclera', $existing_data) === $opt) ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="sclera_<?= $opt ?>"><?= $opt ?></label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Conjungtiva</strong></label>
                        <div class="col-sm-9">
                            <div class="mb-2">
                                <small class="text-muted">Radang:</small>
                                <?php foreach (['Radang', 'Tidak'] as $opt): ?>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="radang_conjungtiva"
                                            value="<?= $opt ?>" id="radang_conj_<?= $opt ?>"
                                            <?= $ro_disabled ?>
                                            <?= (ed('radang_conjungtiva', $existing_data) === $opt) ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="radang_conj_<?= $opt ?>"><?= $opt ?></label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <div>
                                <small class="text-muted">Anemis:</small>
                                <?php foreach (['Anemis', 'Tidak'] as $opt): ?>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="anemis"
                                            value="<?= $opt ?>" id="anemis_<?= $opt ?>"
                                            <?= $ro_disabled ?>
                                            <?= (ed('anemis', $existing_data) === $opt) ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="anemis_<?= $opt ?>"><?= $opt ?></label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Pupil</strong></label>
                        <div class="col-sm-9">
                            <div class="mb-2">
                                <small class="text-muted">Bentuk:</small>
                                <?php foreach (['Isokor', 'Anisokor'] as $opt): ?>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="pupil_bentuk"
                                            value="<?= $opt ?>" id="pupil_b_<?= $opt ?>"
                                            <?= $ro_disabled ?>
                                            <?= (ed('pupil_bentuk', $existing_data) === $opt) ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="pupil_b_<?= $opt ?>"><?= $opt ?></label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <div>
                                <small class="text-muted">Ukuran:</small>
                                <?php foreach (['Myosis', 'Midriasis'] as $opt): ?>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="pupil_ukuran"
                                            value="<?= $opt ?>" id="pupil_u_<?= $opt ?>"
                                            <?= $ro_disabled ?>
                                            <?= (ed('pupil_ukuran', $existing_data) === $opt) ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="pupil_u_<?= $opt ?>"><?= $opt ?></label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Refleks pupil terhadap cahaya</strong></label>
                        <div class="col-sm-9">
                            <div class="d-flex gap-3 align-items-center flex-wrap">
                                <?php foreach (['Simetris', 'Tidak'] as $opt): ?>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="refleks_pupil"
                                            value="<?= $opt ?>" id="refleks_pupil_<?= $opt ?>"
                                            <?= $ro_disabled ?>
                                            <?= (ed('refleks_pupil', $existing_data) === $opt) ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="refleks_pupil_<?= $opt ?>"><?= $opt ?></label>
                                    </div>
                                <?php endforeach; ?>
                                <input type="text" class="form-control" style="max-width:220px;"
                                    name="refleks_pupil_ket" placeholder="Keterangan"
                                    value="<?= ed('refleks_pupil_ket', $existing_data) ?>" <?= $ro ?>>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Gerakan Bola Mata</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="gerakan_mata"
                                value="<?= ed('gerakan_mata', $existing_data) ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Penutupan Kelopak Mata</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="kelopak_mata"
                                value="<?= ed('kelopak_mata', $existing_data) ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Keadaan Bulu Mata</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="bulu_mata"
                                value="<?= ed('bulu_mata', $existing_data) ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Data Lain</strong></label>
                        <div class="col-sm-9">
                            <textarea class="form-control" name="mata_lain" rows="2"
                                style="overflow:hidden; resize:none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= ed('mata_lain', $existing_data) ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ===================== HIDUNG & SINUS ===================== -->
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary mt-3"><strong>d. Hidung & Sinus</strong></label>
                    </div>
                    <div class="row mb-2">
                        <label class="col-sm-12"><em>Inspeksi</em></label>
                    </div>

                    <?php
                    $hidung_text = [
                        ['name' => 'hidung_bentuk',  'label' => 'Bentuk Hidung'],
                        ['name' => 'cuping',         'label' => 'Pernapasan Cuping Hidung'],
                        ['name' => 'septum',         'label' => 'Keadaan Septum'],
                        ['name' => 'secret_hidung',  'label' => 'Secret / Cairan'],
                        ['name' => 'hidung_lain',    'label' => 'Data Lain'],
                    ];
                    foreach ($hidung_text as $f):
                    ?>
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label"><strong><?= $f['label'] ?></strong></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="<?= $f['name'] ?>"
                                    value="<?= ed($f['name'], $existing_data) ?>" <?= $ro ?>>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- ===================== TELINGA ===================== -->
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary mt-3"><strong>e. Telinga</strong></label>
                    </div>
                    <div class="row mb-2">
                        <label class="col-sm-12"><em>Inspeksi</em></label>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Bentuk Telinga</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="telinga_bentuk"
                                value="<?= ed('telinga_bentuk', $existing_data) ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Letak Telinga terhadap Mata</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="letak_telinga"
                                value="<?= ed('letak_telinga', $existing_data) ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Lubang Telinga</strong></label>
                        <div class="col-sm-9">
                            <?php foreach (['Bersih', 'Serumen', 'Nanah', 'Cairan'] as $opt): ?>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="lubang_telinga"
                                        value="<?= $opt ?>" id="telinga_<?= $opt ?>"
                                        <?= $ro_disabled ?>
                                        <?= (ed('lubang_telinga', $existing_data) === $opt) ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="telinga_<?= $opt ?>"><?= $opt ?></label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <label class="col-sm-12"><em>Palpasi</em></label>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Nyeri Tekan</strong></label>
                        <div class="col-sm-9">
                            <?= radioYaTidak('nyeri_telinga', $existing_data, $ro_disabled) ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ===================== MULUT ===================== -->
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary mt-3"><strong>f. Mulut</strong></label>
                    </div>
                    <div class="row mb-2">
                        <label class="col-sm-12"><em>Inspeksi</em></label>
                    </div>

                    <!-- Gusi -->
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Gusi</strong></label>
                        <div class="col-sm-9">
                            <div class="d-flex align-items-center gap-3 flex-wrap">
                                <?php foreach (['Merah', 'Radang', 'Tidak'] as $opt): ?>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="gusi[]"
                                            value="<?= $opt ?>" id="gusi_<?= $opt ?>"
                                            <?= $ro_disabled ?>
                                            <?= in_array($opt, $existing_data['gusi']) ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="gusi_<?= $opt ?>"><?= $opt ?></label>
                                    </div>
                                <?php endforeach; ?>
                                <input type="text" class="form-control" style="max-width:200px;"
                                    name="gusi_ket" placeholder="Keterangan"
                                    value="<?= ed('gusi_ket', $existing_data) ?>" <?= $ro ?>>
                            </div>
                        </div>
                    </div>

                    <!-- Lidah -->
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Lidah</strong></label>
                        <div class="col-sm-9">
                            <?php foreach (['Kotor', 'Tidak'] as $opt): ?>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="lidah"
                                        value="<?= $opt ?>" id="lidah_<?= $opt ?>"
                                        <?= $ro_disabled ?>
                                        <?= (ed('lidah', $existing_data) === $opt) ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="lidah_<?= $opt ?>"><?= $opt ?></label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Bibir Warna -->
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Bibir (Warna)</strong></label>
                        <div class="col-sm-9">
                            <div class="d-flex align-items-center gap-3 flex-wrap">
                                <?php foreach (['Sianosis', 'Pucat', 'Tidak'] as $opt): ?>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="bibir_warna[]"
                                            value="<?= $opt ?>" id="bibir_w_<?= $opt ?>"
                                            <?= $ro_disabled ?>
                                            <?= in_array($opt, $existing_data['bibir_warna']) ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="bibir_w_<?= $opt ?>"><?= $opt ?></label>
                                    </div>
                                <?php endforeach; ?>
                                <input type="text" class="form-control" style="max-width:200px;"
                                    name="bibir_warna_ket" placeholder="Keterangan"
                                    value="<?= ed('bibir_warna_ket', $existing_data) ?>" <?= $ro ?>>
                            </div>
                        </div>
                    </div>

                    <!-- Bibir Kondisi -->
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Bibir (Kondisi)</strong></label>
                        <div class="col-sm-9">
                            <div class="d-flex align-items-center gap-3 flex-wrap">
                                <?php foreach (['Basah', 'Kering', 'Pecah'] as $opt): ?>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="bibir_kondisi[]"
                                            value="<?= $opt ?>" id="bibir_k_<?= $opt ?>"
                                            <?= $ro_disabled ?>
                                            <?= in_array($opt, $existing_data['bibir_kondisi']) ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="bibir_k_<?= $opt ?>"><?= $opt ?></label>
                                    </div>
                                <?php endforeach; ?>
                                <input type="text" class="form-control" style="max-width:200px;"
                                    name="bibir_kondisi_ket" placeholder="Keterangan"
                                    value="<?= ed('bibir_kondisi_ket', $existing_data) ?>" <?= $ro ?>>
                            </div>
                        </div>
                    </div>

                    <!-- Mulut Berbau -->
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Mulut Berbau</strong></label>
                        <div class="col-sm-9">
                            <div class="d-flex align-items-center gap-3 flex-wrap">
                                <?php foreach (['Berbau', 'Tidak'] as $opt): ?>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="bau_mulut"
                                            value="<?= $opt ?>" id="bau_<?= $opt ?>"
                                            <?= $ro_disabled ?>
                                            <?= (ed('bau_mulut', $existing_data) === $opt) ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="bau_<?= $opt ?>"><?= $opt ?></label>
                                    </div>
                                <?php endforeach; ?>
                                <input type="text" class="form-control" style="max-width:220px;"
                                    name="bau_mulut_ket" placeholder="Keterangan"
                                    value="<?= ed('bau_mulut_ket', $existing_data) ?>" <?= $ro ?>>
                            </div>
                        </div>
                    </div>

                    <!-- Radio Ya/Tidak group -->
                    <?php
                    $mulut_radio = [
                        ['name' => 'bibir_simetris',  'label' => 'Keadaan bibir simetris'],
                        ['name' => 'labio_skizis',    'label' => 'Adanya Labio Skizis'],
                        ['name' => 'palato_skizis',   'label' => 'Abiopalato Skizis'],
                        ['name' => 'bercak_putih',    'label' => 'Bercak putih pada lidah dan palatum'],
                    ];
                    foreach ($mulut_radio as $mr):
                    ?>
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label"><strong><?= $mr['label'] ?></strong></label>
                            <div class="col-sm-9">
                                <?= radioYaTidak($mr['name'], $existing_data, $ro_disabled) ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- ===================== TENGGOROKAN ===================== -->
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary mt-3"><strong>g. Tenggorokan</strong></label>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Warna Mukosa</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="warna_mukosa"
                                value="<?= ed('warna_mukosa', $existing_data) ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Ada Sumbatan</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="sumbatan"
                                value="<?= ed('sumbatan', $existing_data) ?>" <?= $ro ?>>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ===================== LEHER ===================== -->
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary mt-3"><strong>h. Leher</strong></label>
                    </div>
                    <div class="row mb-2">
                        <label class="col-sm-12"><em>Palpasi</em></label>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Kelenjar Limfe</strong></label>
                        <div class="col-sm-9">
                            <?php foreach (['Membesar', 'Tidak'] as $opt): ?>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="limfe"
                                        value="<?= $opt ?>" id="limfe_<?= $opt ?>"
                                        <?= $ro_disabled ?>
                                        <?= (ed('limfe', $existing_data) === $opt) ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="limfe_<?= $opt ?>"><?= $opt ?></label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <?php
                    $leher_radio = [
                        ['name' => 'leher_simetris',    'label' => 'Simetris'],
                        ['name' => 'pembengkakan_leher', 'label' => 'Ada Pembengkakan'],
                        ['name' => 'lipatan_leher',      'label' => 'Adanya lipatan kulit berlebihan di belakang leher'],
                    ];
                    foreach ($leher_radio as $lr):
                    ?>
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label"><strong><?= $lr['label'] ?></strong></label>
                            <div class="col-sm-9">
                                <?= radioYaTidak($lr['name'], $existing_data, $ro_disabled) ?>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Data Lain</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="leher_lain"
                                value="<?= ed('leher_lain', $existing_data) ?>" <?= $ro ?>>
                        </div>
                    </div>

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

        <?php include dirname(__DIR__, 2) . '/partials/footer_form.php'; ?>


    </section>
</main>