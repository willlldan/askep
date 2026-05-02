<?php
require_once "koneksi.php";
require_once "utils.php";

$form_id       = 6;
$level         = $_SESSION['level'];
$user_id       = $_SESSION['id_user'];
$section_name  = 'keadaan_bayi';
$section_label = 'Keadaan Bayi Saat Lahir';

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
        // APGAR
        'apgar_1mnt',
        'apgar_5mnt',
        // Ballard Header
        'ballard_name',
        'ballard_hospital_no',
        'ballard_race',
        'ballard_datetime_birth',
        'ballard_datetime_exam',
        'ballard_age_exam',
        'ballard_sex',
        'ballard_birth_weight',
        'ballard_length',
        'ballard_head_circ',
        'ballard_examiner',
        'ballard_apgar_1',
        'ballard_apgar_5',
        // Neuromuscular - pilihan score
        'posture',
        'square_window',
        'arm_recoil',
        'popliteal_angle',
        'scarf_sign',
        'heel_to_ear',
        // Neuromuscular - record score per row (auto JS)
        'score_posture',
        'score_square_window',
        'score_arm_recoil',
        'score_popliteal_angle',
        'score_scarf_sign',
        'score_heel_to_ear',
        // Physical Maturity - pilihan score
        'skin',
        'lanugo',
        'plantar',
        'breast',
        'eye_ear',
        'gen_male',
        'gen_female',
        // Physical Maturity - record score per row (auto JS)
        'score_skin',
        'score_lanugo',
        'score_plantar',
        'score_breast',
        'score_eye_ear',
        'score_gen_male',
        'score_gen_female',
        // Summary score (auto JS)
        'score_neuromuscular',
        'score_physical',
        'score_total',
        // Gestational Age
        'gest_by_dates',
        'gest_by_ultrasound',
        'gest_by_exam',
    ];

    $data = [];
    foreach ($text_fields as $f) {
        $data[$f] = $_POST[$f] ?? '';
    }

    // APGAR per tanda - masing-masing punya nilai menit-1 (checkbox) dan menit-5 (radio)
    $apgar_tanda = ['frekuensi_jantung', 'usaha_nafas', 'tonus_otot', 'refleks_apgar', 'warna_kulit'];
    foreach ($apgar_tanda as $f) {
        $data[$f . '_mnt1'] = $_POST[$f . '_mnt1'] ?? ''; // nilai score menit ke-1
        $data[$f . '_mnt5'] = $_POST[$f . '_mnt5'] ?? ''; // nilai score menit ke-5
    }
    // Total jumlah (dihitung manual oleh mahasiswa atau auto via JS)
    $data['apgar_total_1mnt'] = $_POST['apgar_total_1mnt'] ?? '';
    $data['apgar_total_5mnt'] = $_POST['apgar_total_5mnt'] ?? '';

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

function ed($key, $data)
{
    return htmlspecialchars($data[$key] ?? '');
}

// Helper: render radio row untuk tabel APGAR/Ballard
function radioVal($field, $val, $existing, $disabled)
{
    $checked = (isset($existing[$field]) && (string)$existing[$field] === (string)$val) ? 'checked' : '';
    return "<input type='radio' name='{$field}' value='{$val}' {$checked} {$disabled}>";
}
?>

<main id="main" class="main">
    <?php include "anak/format_aster/tab.php"; ?>

    <section class="section dashboard">

        <?php include dirname(__DIR__, 2) . '/partials/notifikasi.php'; ?>
        <?php include dirname(__DIR__, 2) . '/partials/status_section.php'; ?>

        <form class="needs-validation" novalidate action="" method="POST">

            <!-- ===================== APGAR SCORE ===================== -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><strong>A. Pengkajian — Keadaan Bayi Saat Lahir</strong></h5>

                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary"><strong>APGAR Score</strong></label>
                    </div>

                    <style>
                        .table-apgar {
                            width: 100%;
                            border-collapse: collapse;
                            font-size: 13px;
                        }

                        .table-apgar th,
                        .table-apgar td {
                            border: 1px solid #aaa;
                            padding: 6px 8px;
                            text-align: center;
                            vertical-align: middle;
                        }

                        .table-apgar th {
                            background-color: #eaeaea;
                        }

                        .table-apgar td.label-left {
                            text-align: left;
                            font-weight: 600;
                            min-width: 120px;
                        }

                        .table-apgar input[type=checkbox],
                        .table-apgar input[type=radio] {
                            width: 16px;
                            height: 16px;
                            cursor: pointer;
                        }

                        .table-apgar .score-cell {
                            min-width: 32px;
                        }

                        .apgar-total-input {
                            width: 55px;
                            text-align: center;
                            border: 1px solid #ccc;
                            border-radius: 4px;
                            padding: 3px 4px;
                            font-weight: bold;
                        }
                    </style>

                    <?php
                    // Data APGAR tiap tanda: label, score 0, score 1, score 2
                    $apgar_rows = [
                        ['field' => 'frekuensi_jantung', 'label' => '1. Frekuensi Jantung', 's0' => 'Tidak ada',       's1' => '&lt;100',                                's2' => '&gt;100'],
                        ['field' => 'usaha_nafas',        'label' => '2. Usaha Nafas',        's0' => 'Tidak ada',       's1' => 'Lambat',                                 's2' => 'Menangis Kuat'],
                        ['field' => 'tonus_otot',         'label' => '3. Tonus Otot',          's0' => 'Lumpuh',          's1' => 'Ekstremitas fleksi sedikit',             's2' => 'Gerakan Aktif'],
                        ['field' => 'refleks_apgar',      'label' => '4. Refleks',             's0' => 'Tidak bereaksi',  's1' => 'Gerakan sedikit',                        's2' => 'Reaksi Melawan'],
                        ['field' => 'warna_kulit',        'label' => '5. Warna Kulit',         's0' => 'Biru / Pucat',    's1' => 'Tubuh kemerahan, tangan & kaki biru',    's2' => 'Kemerahan'],
                    ];
                    ?>

                    <div class="table-responsive mb-2">
                        <table class="table-apgar">
                            <thead>
                                <tr>
                                    <th rowspan="3">TANDA</th>
                                    <th colspan="6">SCORE</th>
                                    <th colspan="2">Jumlah</th>
                                </tr>
                                <tr>
                                    <th colspan="2">0</th>
                                    <th colspan="2">1</th>
                                    <th colspan="2">2</th>
                                    <th rowspan="2">1 mnt</th>
                                    <th rowspan="2">5 mnt</th>
                                </tr>
                                <tr>
                                    <th class="score-cell">□</th>
                                    <th></th>
                                    <th class="score-cell">□○</th>
                                    <th></th>
                                    <th class="score-cell">□○</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($apgar_rows as $i => $row):
                                    $f     = $row['field'];
                                    $val1  = $existing_data[$f . '_mnt1'] ?? '';
                                    $val5  = $existing_data[$f . '_mnt5'] ?? '';
                                ?>
                                    <tr>
                                        <td class="label-left"><?= $row['label'] ?></td>

                                        <!-- Score 0 -->
                                        <td class="score-cell">
                                            <!-- □ menit-1 (checkbox) -->
                                            <input type="checkbox" name="<?= $f ?>_mnt1" value="0"
                                                class="apgar-cb-mnt1" data-field="<?= $f ?>"
                                                <?= ($val1 === '0') ? 'checked' : '' ?>
                                                <?= $ro_disabled ?>>
                                            <!-- ○ menit-5 (radio) -->
                                            <input type="radio" name="<?= $f ?>_mnt5" value="0"
                                                class="apgar-rd-mnt5" data-field="<?= $f ?>"
                                                <?= ($val5 === '0') ? 'checked' : '' ?>
                                                <?= $ro_disabled ?>>
                                        </td>
                                        <td><?= $row['s0'] ?></td>

                                        <!-- Score 1 -->
                                        <td class="score-cell">
                                            <input type="checkbox" name="<?= $f ?>_mnt1" value="1"
                                                class="apgar-cb-mnt1" data-field="<?= $f ?>"
                                                <?= ($val1 === '1') ? 'checked' : '' ?>
                                                <?= $ro_disabled ?>>
                                            <input type="radio" name="<?= $f ?>_mnt5" value="1"
                                                class="apgar-rd-mnt5" data-field="<?= $f ?>"
                                                <?= ($val5 === '1') ? 'checked' : '' ?>
                                                <?= $ro_disabled ?>>
                                        </td>
                                        <td><?= $row['s1'] ?></td>

                                        <!-- Score 2 -->
                                        <td class="score-cell">
                                            <input type="checkbox" name="<?= $f ?>_mnt1" value="2"
                                                class="apgar-cb-mnt1" data-field="<?= $f ?>"
                                                <?= ($val1 === '2') ? 'checked' : '' ?>
                                                <?= $ro_disabled ?>>
                                            <input type="radio" name="<?= $f ?>_mnt5" value="2"
                                                class="apgar-rd-mnt5" data-field="<?= $f ?>"
                                                <?= ($val5 === '2') ? 'checked' : '' ?>
                                                <?= $ro_disabled ?>>
                                        </td>
                                        <td><?= $row['s2'] ?></td>

                                        <?php if ($i === 0): // Jumlah rowspan 5 
                                        ?>
                                            <td rowspan="5" style="vertical-align:middle; text-align:center;">
                                                <input type="text" class="apgar-total-input" name="apgar_total_1mnt"
                                                    id="apgar_total_1mnt"
                                                    value="<?= ed('apgar_total_1mnt', $existing_data) ?>" <?= $ro ?> readonly>
                                            </td>
                                            <td rowspan="5" style="vertical-align:middle; text-align:center;">
                                                <input type="text" class="apgar-total-input" name="apgar_total_5mnt"
                                                    id="apgar_total_5mnt"
                                                    value="<?= ed('apgar_total_5mnt', $existing_data) ?>" <?= $ro ?> readonly>
                                            </td>
                                        <?php endif; ?>
                                    </tr>
                                <?php endforeach; ?>

                                <!-- Baris jumlah -->
                                <tr>
                                    <td colspan="7" style="text-align:center; font-weight:bold;">Jumlah</td>
                                    <td style="text-align:center; font-weight:bold;" id="show_total_1mnt">
                                        <?= ed('apgar_total_1mnt', $existing_data) ?>
                                    </td>
                                    <td style="text-align:center; font-weight:bold;" id="show_total_5mnt">
                                        <?= ed('apgar_total_5mnt', $existing_data) ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-12">
                            <small class="text-muted">
                                Ket: &nbsp;
                                <strong>□ (Checkbox)</strong> = Penilaian menit ke-1 &nbsp;|&nbsp;
                                <strong>○ (Radio)</strong> = Penilaian menit ke-5
                            </small>
                        </div>
                    </div>

                    <script>
                        // Checkbox menit-1: hanya boleh 1 yang tercentang per tanda (simulate radio)
                        document.querySelectorAll('.apgar-cb-mnt1').forEach(function(cb) {
                            cb.addEventListener('change', function() {
                                if (this.checked) {
                                    // uncheck checkbox lain di field yang sama
                                    document.querySelectorAll('.apgar-cb-mnt1[data-field="' + this.dataset.field + '"]').forEach(function(other) {
                                        if (other !== cb) other.checked = false;
                                    });
                                }
                                hitungTotal();
                            });
                        });

                        // Radio menit-5: auto hitung saat berubah
                        document.querySelectorAll('.apgar-rd-mnt5').forEach(function(rd) {
                            rd.addEventListener('change', function() {
                                hitungTotal();
                            });
                        });

                        function hitungTotal() {
                            var fields = ['frekuensi_jantung', 'usaha_nafas', 'tonus_otot', 'refleks_apgar', 'warna_kulit'];
                            var total1 = 0,
                                total5 = 0;

                            fields.forEach(function(f) {
                                // Menit-1: cari checkbox yang dicentang
                                var cb = document.querySelector('.apgar-cb-mnt1[data-field="' + f + '"]:checked');
                                if (cb) total1 += parseInt(cb.value);

                                // Menit-5: cari radio yang dipilih
                                var rd = document.querySelector('.apgar-rd-mnt5[data-field="' + f + '"]:checked');
                                if (rd) total5 += parseInt(rd.value);
                            });

                            var inp1 = document.getElementById('apgar_total_1mnt');
                            var inp5 = document.getElementById('apgar_total_5mnt');
                            var inp1_apgar = document.getElementById('ballard_apgar_1');
                            var inp5_apgar = document.getElementById('ballard_apgar_5');
                            if (inp1) {
                                inp1.value = total1;
                                document.getElementById('show_total_1mnt').textContent = total1;
                            }
                            if (inp5) {
                                inp5.value = total5;
                                document.getElementById('show_total_5mnt').textContent = total5;
                            }
                            if (inp1_apgar) {
                                inp1_apgar.value = total1;
                            }
                            if (inp5_apgar) {
                                inp5_apgar.value = total5;
                            }
                        }

                        // Hitung saat load (untuk tampilkan existing data)
                        document.addEventListener('DOMContentLoaded', function() {
                            hitungTotal();
                        });
                    </script>
                </div>
            </div>

            <!-- ===================== NEW BALLARD SCORE - HEADER ===================== -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><strong>Maturational Assessment Of Gestational Age (New Ballard Score)</strong></h5>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Name</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="ballard_name"
                                value="<?= ed('ballard_name', $existing_data) ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Hospital No.</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="ballard_hospital_no"
                                value="<?= ed('ballard_hospital_no', $existing_data) ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Race</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="ballard_race"
                                value="<?= ed('ballard_race', $existing_data) ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Date / Time Of Birth</strong></label>
                        <div class="col-sm-9">
                            <input type="datetime-local" class="form-control" name="ballard_datetime_birth"
                                value="<?= ed('ballard_datetime_birth', $existing_data) ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Date / Time Of Exam</strong></label>
                        <div class="col-sm-9">
                            <input type="datetime-local" class="form-control" name="ballard_datetime_exam"
                                value="<?= ed('ballard_datetime_exam', $existing_data) ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Age When Examined</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="ballard_age_exam"
                                value="<?= ed('ballard_age_exam', $existing_data) ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Sex</strong></label>
                        <div class="col-sm-9">
                            <div class="d-flex gap-4 mt-2">
                                <?php foreach (['Laki-laki', 'Perempuan'] as $opt): ?>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="ballard_sex"
                                            value="<?= $opt ?>" id="bsex_<?= $opt ?>" <?= $ro_disabled ?>
                                            <?= (ed('ballard_sex', $existing_data) === $opt) ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="bsex_<?= $opt ?>"><?= $opt ?></label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Birth Weight</strong></label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <input type="text" class="form-control" name="ballard_birth_weight"
                                    value="<?= ed('ballard_birth_weight', $existing_data) ?>" <?= $ro ?>>
                                <span class="input-group-text">gram</span>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Length</strong></label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <input type="text" class="form-control" name="ballard_length"
                                    value="<?= ed('ballard_length', $existing_data) ?>" <?= $ro ?>>
                                <span class="input-group-text">cm</span>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Head Circumference</strong></label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <input type="text" class="form-control" name="ballard_head_circ"
                                    value="<?= ed('ballard_head_circ', $existing_data) ?>" <?= $ro ?>>
                                <span class="input-group-text">cm</span>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Examiner</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="ballard_examiner"
                                value="<?= ed('ballard_examiner', $existing_data) ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>APGAR Score</strong></label>
                        <div class="col-sm-9">
                            <div class="row g-2">
                                <div class="col-sm-4">
                                    <label class="form-label"><small>1 Minute</small></label>
                                    <input type="number" class="form-control" name="ballard_apgar_1" id="ballard_apgar_1"
                                        value="<?= ed('ballard_apgar_1', $existing_data) ?>" <?= $ro ?> readonly>
                                </div>
                                <div class="col-sm-4">
                                    <label class="form-label"><small>5 Minutes</small></label>
                                    <input type="number" class="form-control" name="ballard_apgar_5" id="ballard_apgar_5"
                                        value="<?= ed('ballard_apgar_5', $existing_data) ?>" <?= $ro ?> readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ===================== NEUROMUSCULAR MATURITY ===================== -->
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary"><strong>Neuromuscular Maturity</strong></label>
                    </div>

                    <?php if (file_exists('assets/img/neuro.png')): ?>
                        <div class="row mb-3">
                            <div class="col-sm-12 text-center">
                                <img src="assets/img/neuro.png" class="img-fluid border rounded" style="max-height:400px;">
                            </div>
                        </div>
                    <?php endif; ?>

                    <style>
                        .table-ballard {
                            width: 100%;
                            border-collapse: collapse;
                            font-size: 12px;
                        }

                        .table-ballard th,
                        .table-ballard td {
                            border: 1px solid #999;
                            padding: 6px;
                            text-align: center;
                            vertical-align: middle;
                        }

                        .table-ballard th {
                            background-color: #cfe2f3;
                        }

                        .table-ballard td.label-left {
                            text-align: left;
                            font-weight: bold;
                        }

                        .score-box {
                            background-color: #e8f4ff;
                            width: 80px;
                        }

                        .table-ballard input[type=radio] {
                            width: 16px;
                            height: 16px;
                            cursor: pointer;
                        }
                    </style>

                    <div class="table-responsive mb-3">
                        <table class="table-ballard">
                            <thead>
                                <tr>
                                    <th rowspan="2" style="width:20%">NEUROMUSCULAR<br>MATURITY SIGN</th>
                                    <th colspan="7">SCORE</th>
                                    <th rowspan="2">RECORD<br>SCORE</th>
                                </tr>
                                <tr>
                                    <th>-1</th>
                                    <th>0</th>
                                    <th>1</th>
                                    <th>2</th>
                                    <th>3</th>
                                    <th>4</th>
                                    <th>5</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $neuro_fields = [
                                    'posture'         => 'POSTURE',
                                    'square_window'   => 'SQUARE WINDOW (Wrist)',
                                    'arm_recoil'      => 'ARM RECOIL',
                                    'popliteal_angle' => 'POPLITEAL ANGLE',
                                    'scarf_sign'      => 'SCARF SIGN',
                                    'heel_to_ear'     => 'HEEL TO EAR',
                                ];
                                foreach ($neuro_fields as $fname => $flabel):
                                    $score_key = 'score_' . $fname;
                                ?>
                                    <tr>
                                        <td class="label-left"><?= $flabel ?></td>
                                        <?php foreach (['-1', '0', '1', '2', '3', '4', '5'] as $v): ?>
                                            <td><?= radioVal($fname, $v, $existing_data, $ro_disabled) ?></td>
                                        <?php endforeach; ?>
                                        <td class="score-box">
                                            <!-- hidden field untuk disimpan ke DB -->
                                            <input type="hidden" name="<?= $score_key ?>"
                                                id="<?= $score_key ?>"
                                                value="<?= ed($score_key, $existing_data) ?>">
                                            <!-- tampilan disabled -->
                                            <input type="text" class="form-control form-control-sm text-center ballard-record-neuro"
                                                id="display_<?= $score_key ?>"
                                                data-hidden="<?= $score_key ?>"
                                                value="<?= ed($score_key, $existing_data) ?>"
                                                disabled style="background:#e8f4ff;">
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <tr>
                                    <td colspan="8" style="text-align:right; font-weight:bold;">
                                        TOTAL NEUROMUSCULAR MATURITY SCORE
                                    </td>
                                    <td class="score-box">
                                        <input type="hidden" name="score_neuromuscular" id="score_neuromuscular"
                                            value="<?= ed('score_neuromuscular', $existing_data) ?>">
                                        <input type="text" class="form-control form-control-sm text-center fw-bold"
                                            id="display_score_neuromuscular"
                                            value="<?= ed('score_neuromuscular', $existing_data) ?>"
                                            disabled style="background:#cfe2f3; font-weight:bold;">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- ===================== PHYSICAL MATURITY ===================== -->
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary"><strong>Physical Maturity</strong></label>
                    </div>

                    <?php if (file_exists('assets/img/PHYSICAL.png')): ?>
                        <div class="row mb-3">
                            <div class="col-sm-12 text-center">
                                <img src="assets/img/PHYSICAL.png" class="img-fluid border rounded" style="max-height:400px;">
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="table-responsive mb-3">
                        <table class="table-ballard">
                            <thead>
                                <tr>
                                    <th rowspan="2" style="width:18%">PHYSICAL<br>MATURITY SIGN</th>
                                    <th colspan="7">SCORE</th>
                                    <th rowspan="2">RECORD<br>SCORE</th>
                                </tr>
                                <tr>
                                    <th>-1</th>
                                    <th>0</th>
                                    <th>1</th>
                                    <th>2</th>
                                    <th>3</th>
                                    <th>4</th>
                                    <th>5</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $physical_fields = [
                                    'skin' => [
                                        'label' => 'SKIN',
                                        'desc'  => [
                                            '-1' => 'sticky friable transparent',
                                            '0'  => 'gelatinous red translucent',
                                            '1'  => 'smooth pink visible veins',
                                            '2'  => 'superficial peeling &/or rash, few veins',
                                            '3'  => 'cracking pale areas rare veins',
                                            '4'  => 'parchment deep cracking no vessels',
                                            '5'  => 'leathery cracked wrinkled',
                                        ]
                                    ],
                                    'lanugo' => [
                                        'label' => 'LANUGO',
                                        'desc'  => [
                                            '-1' => 'none',
                                            '0'  => 'sparse',
                                            '1'  => 'abundant',
                                            '2'  => 'thinning',
                                            '3'  => 'bald areas',
                                            '4'  => 'mostly bald',
                                            '5'  => '',
                                        ]
                                    ],
                                    'plantar' => [
                                        'label' => 'PLANTAR SURFACE',
                                        'desc'  => [
                                            '-1' => 'heel-toe 40-50mm:-1 / <40mm:-2',
                                            '0'  => '>50mm no crease',
                                            '1'  => 'faint red marks',
                                            '2'  => 'anterior transverse crease only',
                                            '3'  => 'creases ant 2/3',
                                            '4'  => 'creases entire sole',
                                            '5'  => '',
                                        ]
                                    ],
                                    'breast' => [
                                        'label' => 'BREAST',
                                        'desc'  => [
                                            '-1' => 'imperceptible',
                                            '0'  => 'barely perceptible',
                                            '1'  => 'flat areola no bud',
                                            '2'  => 'stippled areola 1-2mm bud',
                                            '3'  => 'raised areola 3-4mm bud',
                                            '4'  => 'full areola 5-10mm bud',
                                            '5'  => '',
                                        ]
                                    ],
                                    'eye_ear' => [
                                        'label' => 'EYE / EAR',
                                        'desc'  => [
                                            '-1' => 'lids fused loosely:-1 / tightly:-2',
                                            '0'  => 'lids open pinna flat stays folded',
                                            '1'  => 'sl. curved pinna soft slow recoil',
                                            '2'  => 'well-curved pinna soft but ready recoil',
                                            '3'  => 'formed & firm instant recoil',
                                            '4'  => 'thick cartilage ear stiff',
                                            '5'  => '',
                                        ]
                                    ],
                                    'gen_male' => [
                                        'label' => 'GENITALS (Male)',
                                        'desc'  => [
                                            '-1' => 'scrotum flat smooth',
                                            '0'  => 'scrotum empty faint rugae',
                                            '1'  => 'testes in canal rare rugae',
                                            '2'  => 'testes descending few rugae',
                                            '3'  => 'testes down good rugae',
                                            '4'  => 'testes pendulous deep rugae',
                                            '5'  => '',
                                        ]
                                    ],
                                    'gen_female' => [
                                        'label' => 'GENITALS (Female)',
                                        'desc'  => [
                                            '-1' => 'clitoris prominent & labia flat',
                                            '0'  => 'prominent clitoris & small labia minora',
                                            '1'  => 'prominent clitoris & enlarging minora',
                                            '2'  => 'majora & minora equally prominent',
                                            '3'  => 'majora large minora small',
                                            '4'  => 'majora cover clitoris & minora',
                                            '5'  => '',
                                        ]
                                    ],
                                ];
                                foreach ($physical_fields as $fname => $fdata):
                                ?>
                                    <tr>
                                        <td class="label-left"><?= $fdata['label'] ?></td>
                                        <?php foreach (['-1', '0', '1', '2', '3', '4', '5'] as $v): ?>
                                            <td style="font-size:10px;">
                                                <?php if (!empty($fdata['desc'][$v])): ?>
                                                    <small><?= htmlspecialchars($fdata['desc'][$v]) ?></small><br>
                                                <?php endif; ?>
                                                <?= radioVal($fname, $v, $existing_data, $ro_disabled) ?>
                                            </td>
                                        <?php endforeach; ?>
                                        <td class="score-box">
                                            <input type="hidden" name="score_<?= $fname ?>"
                                                id="score_<?= $fname ?>"
                                                value="<?= ed('score_' . $fname, $existing_data) ?>">
                                            <input type="text" class="form-control form-control-sm text-center ballard-record-phys"
                                                id="display_score_<?= $fname ?>"
                                                data-hidden="score_<?= $fname ?>"
                                                value="<?= ed('score_' . $fname, $existing_data) ?>"
                                                disabled style="background:#e8f4ff;">
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <tr>
                                    <td colspan="8" style="text-align:right; font-weight:bold;">
                                        TOTAL PHYSICAL MATURITY SCORE
                                    </td>
                                    <td class="score-box">
                                        <input type="hidden" name="score_physical" id="score_physical"
                                            value="<?= ed('score_physical', $existing_data) ?>">
                                        <input type="text" class="form-control form-control-sm text-center fw-bold"
                                            id="display_score_physical"
                                            value="<?= ed('score_physical', $existing_data) ?>"
                                            disabled style="background:#cfe2f3; font-weight:bold;">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- ===================== MATURITY RATING + TOTAL + GESTATIONAL AGE ===================== -->
            <div class="card">
                <div class="card-body">

                    <!-- Rekapitulasi Score -->
                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary"><strong>Rekapitulasi Score</strong></label>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Neuromuscular Score</strong></label>
                        <div class="col-sm-3">
                            <input type="hidden" name="score_neuromuscular" id="recap_score_neuromuscular"
                                value="<?= ed('score_neuromuscular', $existing_data) ?>">
                            <input type="text" class="form-control" id="display_recap_neuro"
                                value="<?= ed('score_neuromuscular', $existing_data) ?>"
                                disabled style="background:#e9ecef;">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Physical Score</strong></label>
                        <div class="col-sm-3">
                            <input type="hidden" name="score_physical" id="recap_score_physical"
                                value="<?= ed('score_physical', $existing_data) ?>">
                            <input type="text" class="form-control" id="display_recap_physical"
                                value="<?= ed('score_physical', $existing_data) ?>"
                                disabled style="background:#e9ecef;">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>Total Score</strong></label>
                        <div class="col-sm-3">
                            <input type="hidden" name="score_total" id="recap_score_total"
                                value="<?= ed('score_total', $existing_data) ?>">
                            <input type="text" class="form-control fw-bold" id="display_recap_total"
                                value="<?= ed('score_total', $existing_data) ?>"
                                disabled style="background:#d1ecf1; font-weight:bold;">
                        </div>
                    </div>

                    <!-- ===================== JS AUTO HITUNG BALLARD ===================== -->
                    <script>
                        (function() {
                            // Map nilai string score ke integer (untuk radio value "-1" dst)
                            function parseScore(val) {
                                var n = parseInt(val);
                                return isNaN(n) ? 0 : n;
                            }

                            // Daftar field neuromuscular & physical
                            var neuroFields = ['posture', 'square_window', 'arm_recoil', 'popliteal_angle', 'scarf_sign', 'heel_to_ear'];
                            var physFields = ['skin', 'lanugo', 'plantar', 'breast', 'eye_ear', 'gen_male', 'gen_female'];

                            function hitungBallard() {
                                var totalNeuro = 0,
                                    totalPhys = 0;

                                // Hitung neuromuscular
                                neuroFields.forEach(function(f) {
                                    var checked = document.querySelector('input[name="' + f + '"]:checked');
                                    var val = checked ? parseScore(checked.value) : 0;

                                    // Update record score per row
                                    var hidden = document.getElementById('score_' + f);
                                    var display = document.getElementById('display_score_' + 'score_' + f);
                                    // id display pakai prefix display_score_
                                    var display2 = document.getElementById('display_score_score_' + f);
                                    if (!display2) display2 = document.querySelector('[data-hidden="score_' + f + '"]');

                                    if (hidden) hidden.value = val;
                                    if (display2) display2.value = val;

                                    totalNeuro += val;
                                });

                                // Hitung physical
                                physFields.forEach(function(f) {
                                    var checked = document.querySelector('input[name="' + f + '"]:checked');
                                    var val = checked ? parseScore(checked.value) : 0;

                                    var hidden = document.getElementById('score_' + f);
                                    var display = document.querySelector('[data-hidden="score_' + f + '"]');

                                    if (hidden) hidden.value = val;
                                    if (display) display.value = val;

                                    totalPhys += val;
                                });

                                var totalAll = totalNeuro + totalPhys;

                                // Update tabel neuromuscular total
                                var nHidden = document.getElementById('score_neuromuscular');
                                var nDisplay = document.getElementById('display_score_neuromuscular');
                                if (nHidden) nHidden.value = totalNeuro;
                                if (nDisplay) nDisplay.value = totalNeuro;

                                // Update tabel physical total
                                var pHidden = document.getElementById('score_physical');
                                var pDisplay = document.getElementById('display_score_physical');
                                if (pHidden) pHidden.value = totalPhys;
                                if (pDisplay) pDisplay.value = totalPhys;

                                // Update rekapitulasi
                                var rNeuroH = document.getElementById('recap_score_neuromuscular');
                                var rNeuroD = document.getElementById('display_recap_neuro');
                                if (rNeuroH) rNeuroH.value = totalNeuro;
                                if (rNeuroD) rNeuroD.value = totalNeuro;

                                var rPhysH = document.getElementById('recap_score_physical');
                                var rPhysD = document.getElementById('display_recap_physical');
                                if (rPhysH) rPhysH.value = totalPhys;
                                if (rPhysD) rPhysD.value = totalPhys;

                                var rTotH = document.getElementById('recap_score_total');
                                var rTotD = document.getElementById('display_recap_total');
                                if (rTotH) rTotH.value = totalAll;
                                if (rTotD) rTotD.value = totalAll;
                            }

                            // Bind semua radio ballard
                            document.addEventListener('change', function(e) {
                                var allFields = neuroFields.concat(physFields);
                                if (allFields.indexOf(e.target.name) !== -1) {
                                    hitungBallard();
                                }
                            });

                            // Hitung saat load (existing data)
                            document.addEventListener('DOMContentLoaded', function() {
                                hitungBallard();
                            });
                        })();
                    </script>

                    <hr>

                    <!-- Maturity Rating Reference Table -->
                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary"><strong>Maturity Rating (Referensi)</strong></label>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-4">
                            <table class="table table-bordered table-sm text-center" style="width:auto;">
                                <thead class="table-light">
                                    <tr>
                                        <th>Score</th>
                                        <th>Weeks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $maturity = [
                                        -10 => 20,
                                        -5 => 22,
                                        0 => 24,
                                        5 => 26,
                                        10 => 28,
                                        15 => 30,
                                        20 => 32,
                                        25 => 34,
                                        30 => 36,
                                        35 => 38,
                                        40 => 40,
                                        45 => 42,
                                        50 => 44
                                    ];
                                    foreach ($maturity as $score => $weeks): ?>
                                        <tr>
                                            <td><?= $score ?></td>
                                            <td><?= $weeks ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <hr>

                    <!-- Gestational Age -->
                    <div class="row mb-2">
                        <label class="col-sm-12 text-primary"><strong>Gestational Age (weeks)</strong></label>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>By Dates</strong></label>
                        <div class="col-sm-9">
                            <input type="date" class="form-control" name="gest_by_dates"
                                value="<?= ed('gest_by_dates', $existing_data) ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>By Ultrasound</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="gest_by_ultrasound"
                                value="<?= ed('gest_by_ultrasound', $existing_data) ?>" <?= $ro ?>>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"><strong>By Exam</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="gest_by_exam"
                                value="<?= ed('gest_by_exam', $existing_data) ?>" <?= $ro ?>>
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