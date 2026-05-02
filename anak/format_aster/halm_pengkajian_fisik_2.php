<?php
require_once "koneksi.php";
require_once "utils.php";

$form_id       = 6;
$level         = $_SESSION['level'];
$user_id       = $_SESSION['id_user'];
$section_name  = 'pemfis_2';
$section_label = 'Pemeriksaan Fisik 2';

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
        // Thorax
        'dada_simetris',
        'puting',
        'thorax_simetris',
        'fraktur',
        'bentuk_dada',
        'irama',
        'pengembangan',
        'tipe',
        'taktil_fremitus',
        'perkusi_thorax',
        // Jantung
        'ictus_cordis',
        'pembesaran_jantung',
        'bj1',
        'bj2',
        'bj3',
        'bunyi_tambahan',
        'jantung_lain',
        // Abdomen
        'tali_pusat_bersih',
        'tali_pusat_kondisi',
        'tali_tidak_berbau',
        'pendarahan_tp',
        'umbilicus',
        'infeksi_tp',
        'abdomen_bentuk',
        'abdomen_cekung',
        'abdomen_gerak',
        'pembengkakan_abd',
        'kulit_abdomen',
        'peristaltik',
        'tympani',
        'abd_lain',
        'nyeri_abd',
        'hati',
        'ginjal',
        'kolon',
        // Genetalia
        'fistula_pria',
        'uretra',
        'skrotum',
        'genital_ganda',
        'gen_pria_lain',
        'labia',
        'fistula_wanita',
        'gen_wanita_lain',
        // Anus
        'lubang_anal',
        'mekonium_36jam',
        // Ekstremitas Atas
        'gerak_atas',
        'gerak_abnormal_atas',
        'kekuatan_atas',
        'koordinasi_atas',
        'jari_atas',
        'polidaktili_atas',
        'telapak_atas',
        'nyeri_atas',
        'suhu_atas',
        'raba_atas',
        // Ekstremitas Bawah
        'gerak_bawah',
        'kekuatan_bawah',
        'tonus_bawah',
        'jari_bawah',
        'polidaktili_bawah',
        'nyeri_bawah',
        'suhu_bawah',
        'raba_bawah',
        // Integumen
        'turgor',
        'finger_print',
        'lesi',
        'kebersihan',
        'kelembaban_kulit',
        'warna_kulit_integ',
        // Refleks Primitif
        'refleks_iddol',
        'refleks_startel',
        'refleks_sucking',
        'refleks_rooting',
        'refleks_gawn',
        'refleks_grabella',
        'refleks_ekruction',
        'refleks_moro',
        'refleks_grasping',
        // Tes Diagnostik
        'laboratorium',
        'pemeriksaan_penunjang',
        'terapi',
    ];

    // Checkbox fields
    $checkbox_fields = ['suara_nafas', 'suara_tambahan', 'perkusi_paru'];

    $data = [];
    foreach ($text_fields as $f) {
        $data[$f] = $_POST[$f] ?? '';
    }
    foreach ($checkbox_fields as $cf) {
        $data[$cf] = json_encode(isset($_POST[$cf]) ? (array)$_POST[$cf] : []);
    }

    // Handle upload lampiran lab
    $lampiran_lab_path = $existing_data['lampiran_lab'] ?? '';
    if (!empty($_FILES['lampiran_lab']['name'])) {
        $upload = uploadImage($_FILES['lampiran_lab'], 'uploads/anak/', 50);
        if ($upload['success']) {
            if (!empty($lampiran_lab_path) && file_exists($lampiran_lab_path)) {
                unlink($lampiran_lab_path);
            }
            $lampiran_lab_path = $upload['path'];
        } else {
            redirectWithMessage($_SERVER['REQUEST_URI'], 'error', $upload['error']);
            exit;
        }
    }
    $data['lampiran_lab'] = $lampiran_lab_path;

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
$cb_fields = ['suara_nafas', 'suara_tambahan', 'perkusi_paru'];
foreach ($cb_fields as $cf) {
    $existing_data[$cf] = isset($existing_data[$cf])
        ? (json_decode($existing_data[$cf], true) ?? [])
        : [];
}

function ed($key, $data)
{
    return htmlspecialchars($data[$key] ?? '');
}

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

        <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

            <!-- ===================== THORAX & PERNAPASAN ===================== -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><strong>Pemeriksaan Fisik (Lanjutan)</strong></h5>

                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary mt-3"><strong>i. Thorax dan Pernapasan</strong></label>
                    </div>

                    <?php
                    $thorax_radio = [
                        ['name' => 'dada_simetris', 'label' => 'Periksa kesimetrisan gerakan dada saat bernapas'],
                        ['name' => 'puting',        'label' => 'Puting susu tampak membesar'],
                        ['name' => 'thorax_simetris', 'label' => 'Simetris'],
                        ['name' => 'fraktur',       'label' => 'Fraktur Klavikula'],
                    ];
                    foreach ($thorax_radio as $tr):
                    ?>
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label"><strong><?= $tr['label'] ?></strong></label>
                            <div class="col-sm-9">
                                <?= radioYaTidak($tr['name'], $existing_data, $ro_disabled) ?>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <?php
                    $thorax_text = [
                        ['name' => 'bentuk_dada',    'label' => 'Bentuk Dada'],
                        ['name' => 'irama',          'label' => 'Irama Pernapasan'],
                        ['name' => 'pengembangan',   'label' => 'Pengembangan di waktu bernapas'],
                        ['name' => 'tipe',           'label' => 'Tipe Pernapasan'],
                        ['name' => 'taktil_fremitus', 'label' => 'Palpasi Taktil Fremitus'],
                    ];
                    foreach ($thorax_text as $tt):
                    ?>
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label"><strong><?= $tt['label'] ?></strong></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="<?= $tt['name'] ?>"
                                    value="<?= ed($tt['name'], $existing_data) ?>" <?= $ro ?>>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <!-- Auskultasi -->
                    <div class="row mb-2 mt-2">
                        <label class="col-sm-12"><em>Auskultasi</em></label>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Suara Nafas</strong></label>
                        <div class="col-sm-9">
                            <?php foreach (['Vesikuler', 'Bronchial', 'Bronchovesikuler'] as $opt): ?>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="suara_nafas[]"
                                        value="<?= $opt ?>" id="sn_<?= $opt ?>"
                                        <?= $ro_disabled ?>
                                        <?= in_array($opt, $existing_data['suara_nafas']) ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="sn_<?= $opt ?>"><?= $opt ?></label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Suara Tambahan</strong></label>
                        <div class="col-sm-9">
                            <?php foreach (['Ronchi', 'Wheezing', 'Rales'] as $opt): ?>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="suara_tambahan[]"
                                        value="<?= $opt ?>" id="st_<?= $opt ?>"
                                        <?= $ro_disabled ?>
                                        <?= in_array($opt, $existing_data['suara_tambahan']) ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="st_<?= $opt ?>"><?= $opt ?></label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Perkusi -->
                    <div class="row mb-2 mt-2">
                        <label class="col-sm-12"><em>Perkusi</em></label>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Hasil Perkusi</strong></label>
                        <div class="col-sm-9">
                            <?php foreach (['Redup', 'Pekak', 'Hypersonor', 'Tympani'] as $opt): ?>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="perkusi_paru[]"
                                        value="<?= $opt ?>" id="pp_<?= $opt ?>"
                                        <?= $ro_disabled ?>
                                        <?= in_array($opt, $existing_data['perkusi_paru']) ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="pp_<?= $opt ?>"><?= $opt ?></label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ===================== JANTUNG ===================== -->
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary mt-3"><strong>j. Jantung</strong></label>
                    </div>

                    <div class="row mb-2">
                        <label class="col-sm-12"><em>Palpasi</em></label>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Ictus Cordis</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="ictus_cordis"
                                value="<?= ed('ictus_cordis', $existing_data) ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <label class="col-sm-12"><em>Perkusi</em></label>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Pembesaran Jantung</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="pembesaran_jantung"
                                value="<?= ed('pembesaran_jantung', $existing_data) ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <label class="col-sm-12"><em>Auskultasi</em></label>
                    </div>

                    <?php
                    $bj = [
                        ['name' => 'bj1', 'label' => 'BJ I'],
                        ['name' => 'bj2', 'label' => 'BJ II'],
                        ['name' => 'bj3', 'label' => 'BJ III'],
                        ['name' => 'bunyi_tambahan', 'label' => 'Bunyi Jantung Tambahan'],
                        ['name' => 'jantung_lain',   'label' => 'Data Lain'],
                    ];
                    foreach ($bj as $b):
                    ?>
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label"><strong><?= $b['label'] ?></strong></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="<?= $b['name'] ?>"
                                    value="<?= ed($b['name'], $existing_data) ?>" <?= $ro ?>>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- ===================== ABDOMEN ===================== -->
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary mt-3"><strong>k. Abdomen</strong></label>
                    </div>

                    <div class="row mb-2">
                        <label class="col-sm-12"><em>Inspeksi — Keadaan Tali Pusat</em></label>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Bersih / Terawat</strong></label>
                        <div class="col-sm-9">
                            <?= radioYaTidak('tali_pusat_bersih', $existing_data, $ro_disabled) ?>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Tali Pusat</strong></label>
                        <div class="col-sm-9">
                            <?php foreach (['Layu', 'Segar'] as $opt): ?>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="tali_pusat_kondisi"
                                        value="<?= $opt ?>" id="tp_<?= $opt ?>"
                                        <?= $ro_disabled ?>
                                        <?= (ed('tali_pusat_kondisi', $existing_data) === $opt) ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="tp_<?= $opt ?>"><?= $opt ?></label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Tidak Berbau</strong></label>
                        <div class="col-sm-9">
                            <?= radioYaTidak('tali_tidak_berbau', $existing_data, $ro_disabled) ?>
                        </div>
                    </div>

                    <?php
                    $abd_text1 = [
                        ['name' => 'pendarahan_tp', 'label' => 'Pendarahan Tali Pusat'],
                        ['name' => 'umbilicus',     'label' => 'Penonjolan Umbilicus'],
                        ['name' => 'infeksi_tp',    'label' => 'Tanda-tanda Infeksi'],
                    ];
                    foreach ($abd_text1 as $a):
                    ?>
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label"><strong><?= $a['label'] ?></strong></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="<?= $a['name'] ?>"
                                    value="<?= ed($a['name'], $existing_data) ?>" <?= $ro ?>>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <?php
                    $abd_radio = [
                        ['name' => 'abdomen_bentuk',  'label' => 'Abdomen Tampak Bulat'],
                        ['name' => 'abdomen_cekung',  'label' => 'Atau Cekung'],
                        ['name' => 'abdomen_gerak',   'label' => 'Abdomen bergerak bersamaan dengan gerakan dada saat bernafas'],
                        ['name' => 'pembengkakan_abd', 'label' => 'Kaji adanya pembengkakan'],
                    ];
                    foreach ($abd_radio as $ar):
                    ?>
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label"><strong><?= $ar['label'] ?></strong></label>
                            <div class="col-sm-9">
                                <?= radioYaTidak($ar['name'], $existing_data, $ro_disabled) ?>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Warna & Keadaan Kulit Abdomen</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="kulit_abdomen"
                                placeholder="Jaringan parut, ekimosis, distensi vena"
                                value="<?= ed('kulit_abdomen', $existing_data) ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-2 mt-2">
                        <label class="col-sm-12"><em>Auskultasi</em></label>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Peristaltik</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="peristaltik"
                                value="<?= ed('peristaltik', $existing_data) ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-2 mt-2">
                        <label class="col-sm-12"><em>Perkusi</em></label>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Tympani</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="tympani"
                                value="<?= ed('tympani', $existing_data) ?>" <?= $ro ?>>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Data Lain</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="abd_lain"
                                value="<?= ed('abd_lain', $existing_data) ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-2 mt-2">
                        <label class="col-sm-12"><em>Palpasi</em></label>
                    </div>
                    <?php
                    $palpasi = [
                        ['name' => 'nyeri_abd', 'label' => 'Adanya Nyeri'],
                        ['name' => 'hati',      'label' => 'Hati'],
                        ['name' => 'ginjal',    'label' => 'Ginjal'],
                        ['name' => 'kolon',     'label' => 'Kolon Sigmoid'],
                    ];
                    foreach ($palpasi as $p):
                    ?>
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label"><strong><?= $p['label'] ?></strong></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="<?= $p['name'] ?>"
                                    value="<?= ed($p['name'], $existing_data) ?>" <?= $ro ?>>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- ===================== GENETALIA ===================== -->
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary mt-3"><strong>l. Genetalia</strong></label>
                    </div>

                    <!-- Laki-laki -->
                    <div class="row mb-2">
                        <label class="col-sm-12"><strong>Anak Laki-laki</strong></label>
                    </div>
                    <?php
                    $gen_pria = [
                        ['name' => 'fistula_pria',   'label' => 'Fistula Urinari (Laki-laki)'],
                        ['name' => 'uretra',         'label' => 'Lubang Uretra'],
                        ['name' => 'skrotum',        'label' => 'Skrotum'],
                        ['name' => 'genital_ganda',  'label' => 'Genitalia Ganda'],
                        ['name' => 'gen_pria_lain',  'label' => 'Data Lain'],
                    ];
                    foreach ($gen_pria as $g):
                    ?>
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label"><strong><?= $g['label'] ?></strong></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="<?= $g['name'] ?>"
                                    value="<?= ed($g['name'], $existing_data) ?>" <?= $ro ?>>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <!-- Perempuan -->
                    <div class="row mb-2 mt-3">
                        <label class="col-sm-12"><strong>Anak Perempuan</strong></label>
                    </div>
                    <?php
                    $gen_wanita = [
                        ['name' => 'labia',           'label' => 'Labia & Klitoris'],
                        ['name' => 'fistula_wanita',  'label' => 'Fistula Urogenital (Perempuan)'],
                        ['name' => 'gen_wanita_lain', 'label' => 'Data Lain'],
                    ];
                    foreach ($gen_wanita as $g):
                    ?>
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label"><strong><?= $g['label'] ?></strong></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="<?= $g['name'] ?>"
                                    value="<?= ed($g['name'], $existing_data) ?>" <?= $ro ?>>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- ===================== ANUS ===================== -->
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary mt-3"><strong>m. Anus</strong></label>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Lubang Anal Paten</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="lubang_anal"
                                value="<?= ed('lubang_anal', $existing_data) ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Lintasan Mekonium dalam 36 Jam</strong></label>
                        <div class="col-sm-9">
                            <?= radioYaTidak('mekonium_36jam', $existing_data, $ro_disabled) ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ===================== EKSTREMITAS ATAS ===================== -->
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary mt-3"><strong>n. Ekstremitas Atas</strong></label>
                    </div>

                    <div class="row mb-2">
                        <label class="col-sm-12"><em>Motorik</em></label>
                    </div>

                    <?php
                    $ekst_atas_text = [
                        ['name' => 'gerak_atas',       'label' => 'Pergerakan Kanan / Kiri'],
                        ['name' => 'gerak_abnormal_atas', 'label' => 'Pergerakan Abnormal'],
                        ['name' => 'kekuatan_atas',    'label' => 'Kekuatan Otot Kanan / Kiri'],
                        ['name' => 'koordinasi_atas',  'label' => 'Koordinasi Gerak'],
                        ['name' => 'jari_atas',        'label' => 'Jumlah Jari'],
                        ['name' => 'telapak_atas',     'label' => 'Telapak Tangan Dapat Terbuka'],
                    ];
                    foreach ($ekst_atas_text as $e):
                    ?>
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label"><strong><?= $e['label'] ?></strong></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="<?= $e['name'] ?>"
                                    value="<?= ed($e['name'], $existing_data) ?>" <?= $ro ?>>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Polidaktili atau Sidaktili</strong></label>
                        <div class="col-sm-9">
                            <?= radioYaTidak('polidaktili_atas', $existing_data, $ro_disabled) ?>
                        </div>
                    </div>

                    <div class="row mb-2 mt-2">
                        <label class="col-sm-12"><em>Sensori</em></label>
                    </div>
                    <?php
                    $sensori_atas = [
                        ['name' => 'nyeri_atas', 'label' => 'Nyeri'],
                        ['name' => 'suhu_atas',  'label' => 'Rangsang Suhu'],
                        ['name' => 'raba_atas',  'label' => 'Rasa Raba'],
                    ];
                    foreach ($sensori_atas as $s):
                    ?>
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label"><strong><?= $s['label'] ?></strong></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="<?= $s['name'] ?>"
                                    value="<?= ed($s['name'], $existing_data) ?>" <?= $ro ?>>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- ===================== EKSTREMITAS BAWAH ===================== -->
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary mt-3"><strong>o. Ekstremitas Bawah</strong></label>
                    </div>

                    <div class="row mb-2">
                        <label class="col-sm-12"><em>Motorik</em></label>
                    </div>

                    <?php
                    $ekst_bawah_text = [
                        ['name' => 'gerak_bawah',    'label' => 'Pergerakan Kanan / Kiri'],
                        ['name' => 'kekuatan_bawah', 'label' => 'Kekuatan Kanan / Kiri'],
                        ['name' => 'tonus_bawah',    'label' => 'Tonus Otot Kanan / Kiri'],
                        ['name' => 'jari_bawah',     'label' => 'Jumlah Jari'],
                    ];
                    foreach ($ekst_bawah_text as $e):
                    ?>
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label"><strong><?= $e['label'] ?></strong></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="<?= $e['name'] ?>"
                                    value="<?= ed($e['name'], $existing_data) ?>" <?= $ro ?>>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Polidaktili atau Sidaktili</strong></label>
                        <div class="col-sm-9">
                            <?= radioYaTidak('polidaktili_bawah', $existing_data, $ro_disabled) ?>
                        </div>
                    </div>

                    <div class="row mb-2 mt-2">
                        <label class="col-sm-12"><em>Sensori</em></label>
                    </div>
                    <?php
                    $sensori_bawah = [
                        ['name' => 'nyeri_bawah', 'label' => 'Nyeri'],
                        ['name' => 'suhu_bawah',  'label' => 'Rangsang Suhu'],
                        ['name' => 'raba_bawah',  'label' => 'Rasa Raba'],
                    ];
                    foreach ($sensori_bawah as $s):
                    ?>
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label"><strong><?= $s['label'] ?></strong></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="<?= $s['name'] ?>"
                                    value="<?= ed($s['name'], $existing_data) ?>" <?= $ro ?>>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- ===================== INTEGUMEN ===================== -->
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary mt-3"><strong>p. Integumen</strong></label>
                    </div>

                    <?php
                    $integumen = [
                        ['name' => 'turgor',          'label' => 'Turgor Kulit'],
                        ['name' => 'finger_print',    'label' => 'Finger Print di Dahi'],
                        ['name' => 'lesi',            'label' => 'Adanya Lesi'],
                        ['name' => 'kebersihan',      'label' => 'Kebersihan Kulit'],
                        ['name' => 'kelembaban_kulit', 'label' => 'Kelembaban Kulit'],
                        ['name' => 'warna_kulit_integ', 'label' => 'Warna Kulit'],
                    ];
                    foreach ($integumen as $ig):
                    ?>
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label"><strong><?= $ig['label'] ?></strong></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="<?= $ig['name'] ?>"
                                    value="<?= ed($ig['name'], $existing_data) ?>" <?= $ro ?>>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- ===================== REFLEKS PRIMITIF ===================== -->
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary mt-3"><strong>q. Pengkajian Refleks Primitif</strong></label>
                    </div>

                    <?php
                    $refleks = [
                        ['name' => 'refleks_iddol',    'label' => 'Refleks Iddol'],
                        ['name' => 'refleks_startel',  'label' => 'Refleks Startel'],
                        ['name' => 'refleks_sucking',  'label' => 'Refleks Sucking (Isap)'],
                        ['name' => 'refleks_rooting',  'label' => 'Refleks Rooting (Menoleh)'],
                        ['name' => 'refleks_gawn',     'label' => 'Refleks Gawn'],
                        ['name' => 'refleks_grabella', 'label' => 'Refleks Grabella'],
                        ['name' => 'refleks_ekruction', 'label' => 'Refleks Ekruction'],
                        ['name' => 'refleks_moro',     'label' => 'Refleks Moro'],
                        ['name' => 'refleks_grasping', 'label' => 'Refleks Grasping'],
                    ];
                    foreach ($refleks as $r):
                    ?>
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label"><strong><?= $r['label'] ?></strong></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="<?= $r['name'] ?>"
                                    value="<?= ed($r['name'], $existing_data) ?>" <?= $ro ?>>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- ===================== TES DIAGNOSTIK ===================== -->
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary mt-3"><strong>r. Tes Diagnostik</strong></label>
                    </div>

                    <div class="row mb-2">
                        <label class="col-sm-12"><strong>Laboratorium</strong></label>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Hasil Laboratorium</strong></label>
                        <div class="col-sm-9">
                            <textarea class="form-control" name="laboratorium" rows="3"
                                style="overflow:hidden; resize:none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= ed('laboratorium', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Lampiran Lab</strong></label>
                        <div class="col-sm-9">
                            <?php if (!empty($existing_data['lampiran_lab'])): ?>
                                <img src="<?= htmlspecialchars($existing_data['lampiran_lab']) ?>" class="img-fluid rounded border mb-2" style="max-height:400px;">
                            <?php endif; ?>
                            <?php if (!$is_readonly): ?>
                                <input type="file" class="form-control" name="lampiran_lab" accept="image/jpeg,image/png,image/webp">
                                <small class="text-muted">ex : Foto rotgen, CT scan, MRI, USG, EEG, ECG dll</small>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Pemeriksaan Penunjang</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="pemeriksaan_penunjang"
                                placeholder="Foto Rontgen, CT Scan, MRI, USG, EEG, ECG"
                                value="<?= ed('pemeriksaan_penunjang', $existing_data) ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Terapi Saat Ini</strong></label>
                        <div class="col-sm-9">
                            <textarea class="form-control" name="terapi" rows="3"
                                style="overflow:hidden; resize:none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= ed('terapi', $existing_data) ?></textarea>
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