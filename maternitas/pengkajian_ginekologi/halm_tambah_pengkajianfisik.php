<?php
require_once "koneksi.php";
require_once "utils.php";

$form_id       = 6;
$level         = $_SESSION['level'];
$user_id       = $_SESSION['id_user'];
$section_name  = 'pengkajian_fisik';
$section_label = 'Pengkajian Fisik';

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

// =============================================
// HANDLE POST - MAHASISWA SIMPAN DATA
// =============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $level === 'Mahasiswa') {

    if (isLocked($submission)) {
        redirectWithMessage($_SERVER['REQUEST_URI'], 'error', 'Data tidak dapat diubah karena sedang dalam proses review.');
    }


    $data = [
        // Kepala & Rambut
        'inspeksi_kepala'           => $_POST['inspeksikepala'] ?? '',
        'palpasi_kepala'            => $_POST['palpasiikepala'] ?? '',
        'masalah_kepala'            => $_POST['masalahkhususkepala'] ?? '',
        // Wajah
        'inspeksi_wajah'            => $_POST['inspeksiwajah'] ?? '',
        'palpasi_wajah'             => $_POST['palpasiwajah'] ?? '',
        'masalah_wajah'             => $_POST['masalahkhususwajah'] ?? '',
        // Mata
        'inspeksik_konjung_tiva'     => $_POST['inspeksikkonjungtiva'] ?? '',

        'inspeksi_sklrea'           => $_POST['inspeksisklrea'] ?? '',
        'palpasi_kelopak_mata'      => $_POST['palpasikelopakmata'] ?? '',
        'masalah_mata'              => $_POST['masalahkhususmata'] ?? '',
        // Hidung
        'inspeksi_hidung'           => $_POST['inspeksihidung'] ?? '',
        'palpasi_hidung'            => $_POST['palpasihidung'] ?? '',
        'masalah_hidung'            => $_POST['masalahkhusushidung'] ?? '',
        // Mulut
        'inspeksi_bibir'            => $_POST['inspeksibibir'] ?? '',
        'inspeksi_gigi'             => $_POST['inspeksigigi'] ?? '',
        'inspeksi_gusi'             => $_POST['inspeksigusi'] ?? '',
        'inspeksi_lidah'            => $_POST['inspeksilidah'] ?? '',
        'inspeksi_bau_mulut'        => $_POST['inspeksibaumulut'] ?? '',
        'palpasi_mulut'             => $_POST['palpasimulut'] ?? '',
        'masalah_mulut'             => $_POST['masalahkhususmulut'] ?? '',
        // Telinga
        'inspeksi_telinga'          => $_POST['inspeksitelinga'] ?? '',
        'palpasi_nyeri_tekan'       => $_POST['palpasinyeritekan'] ?? '',
        'palpasi_gangguan'          => $_POST['palpasigangguan'] ?? '',
        'masalah_telinga'           => $_POST['masalahkhusustelinga'] ?? '',
        // Leher
        'inspeksi_leher'            => $_POST['inspeksileher'] ?? '',
        'palpasi_kelenjar'          => $_POST['palpasikelenjar'] ?? '',
        'palpasi_trakea'            => $_POST['palpasitrakea'] ?? '',
        'palpasi_nyeri_menelan'     => $_POST['palpasinyerimenelan'] ?? '',
        'masalah_leher'             => $_POST['masalahkhususleher'] ?? '',
        // Axila
        'inspeksi_axila'            => $_POST['inspeksiaxilia'] ?? '',
        'palpasi_axila'             => $_POST['palpasiaxilia'] ?? '',
        'masalah_axila'             => $_POST['masalahkhususaxilia'] ?? '',
        // Dada
        'inspeksi_dada'             => $_POST['inspeksidada'] ?? '',
        'palpasi_dada'              => $_POST['palpasidada'] ?? '',

        // Auskultasi
        'auskultasi_dada'           => $_POST['auskultasidada'] ?? '',
        'masalah_khusus_dada'       => $_POST['masalahkhususdada'] ?? '',

        // Inspeksi dan Palpasi 
        'inspeksi_dan_palpasi_sistem' => $_POST['inspeksidanpalpasisistem'] ?? '',
        'dada1'              => $_POST['palpasidada1'] ?? '',

        'khusus'       => $_POST['masalahkhususdada1'] ?? '',

        'auskultasi_sistem'          => $_POST['auskultasisistem'] ?? '',
        'auskultasi'         => $_POST['auskultasisistem1'] ?? '',
        'masalah_khusus_sistem'      => $_POST['masalahkhusussistem'] ?? '',
        // payudara
        'inspeksi_bentuk'            => $_POST['inspeksibentuk'] ?? '',
        'inspeksi_pengeluaran_cairan' => $_POST['inspeksipengeluarancairan'] ?? '',
        'inspeksi_pembengkakan'      => $_POST['inspeksipembengkakan'] ?? '',
        'palpasi_raba'               => $_POST['palpasiraba'] ?? '',
        'palpasi_benjolan'           => $_POST['palpasibenjolan'] ?? '',
        'masalah_khusus_payudadra'   => $_POST['masalahkhususpayudadra'] ?? '',
        'inspeksi_abdomen'           => $_POST['inspeksiabdomen'] ?? '',
        'auskultasi_bising_usus'     => $_POST['auskultasibisingusus'] ?? '',
        'perkusi'                    => $_POST['perkusi'] ?? '',
        'palpasi_involusi'           => $_POST['palpasiinvolusi'] ?? '',
        'palpasi_kandung_kemih'      => $_POST['palpasikandungkemih'] ?? '',
        'masalah_khusus__abdomen'    => $_POST['masalahkhususabdomen'] ?? '',
        // Perineum & Genitalia
        'pendarahan'                 => $_POST['pendarahan'] ?? '',
        'hemoroid'                   => $_POST['hemoroid'] ?? '',
        'keputihan'                  => $_POST['keputihan'] ?? '',
        'masalah_khusus_genetalia'   => $_POST['masalahkhususgenetalia'] ?? '',
        // Ekstremitas
        'inspeksi_ekstremitas_atas'  => $_POST['inspeksiekstremitasatas'] ?? '',
        'inspeksi_ekstremitas_bawah' => $_POST['inspeksiekstremitasbawah'] ?? '',
        'masalah_khusus_ekstremitas' => $_POST['masalahkhususekstremitas'] ?? '',
        // Integumen
        'inspeksi_integumen'        => $_POST['inspeksiintegumen'] ?? '',
        'palpasi_integumen'         => $_POST['palpasiintegumen'] ?? '',
        // Eliminasi
        'bak'                       => $_POST['inspeksibak'] ?? '',
        'masalah_khusus_bak'        => $_POST['masalahkhususbak'] ?? '',
        'inspeksi_bab'              => $_POST['inspeksibab'] ?? '',
        'masalah_khusus_bab'        => $_POST['masalahkhususbab'] ?? '',
        // Istirahat & Kenyamanan
        'pola_tidur'                => $_POST['inspeksiistirahat'] ?? '',
        'kenyamanan'                => $_POST['inspeksikenyamanan'] ?? '',
        'masalah_istirahat'         => $_POST['masalahkhususistirahat'] ?? '',
        // Mobilisasi & Latihan
        'tingkat_mobilisasi'        => $_POST['inspeksimobilisasi'] ?? '',
        'masalah_mobilisasi'        => $_POST['masalahkhususmobilisasi'] ?? '',
        // Nutrisi & Cairan
        'jenis_makanan'            => $_POST['jenismakanan'] ?? '',
        'frekuensi'                => $_POST['frekuensi'] ?? '',
        'konsumsi_snack'           => $_POST['konsumsisnack'] ?? '',
        'nafsu_makan'              => $_POST['nafsumakan'] ?? '',
        // Pengetahuan
        'pola_minum'               => $_POST['polaminum'] ?? '',
        'frekuensi2'               => $_POST['frekuensi2'] ?? '',
        'pantangan_makanan'        => $_POST['pantanganmakanan'] ?? '',
    ];

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
    <?php include "maternitas/pengkajian_ginekologi/tab.php"; ?>

    <section class="section dashboard">
        <?php include "partials/notifikasi.php"; ?>
        <?php include "partials/status_section.php"; ?>
        <div class="card">
            <div class="card-body">

                <!-- General Form Elements -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

                    <h5 class="card-title mb-1"><strong>Pengkajian Fisik</strong></h5>

                    <!-- Bagian Kepala dan Rambut -->

                    <div class="row mb-2">
                        <label class="col-sm-2 col-form-label text-primary">
                            <strong>Kepala dan Rambut</strong>
                    </div>

                    <!-- Inspeksi -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Inspeksi</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Bentuk kepala, Penyebaran, Kebersihan, Warna Rambut. Hasil:</small>
                            <textarea name="inspeksikepala" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('inspeksi_kepala', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- Palpasi -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Palpasi</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Apakah terdapat benjolan, pembengkakan, nyeri tekan. Hasil:</small>
                            <textarea name="palpasiikepala" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('palpasi_kepala', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- Masalah Khusus -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Masalah Khusus</strong></label>

                        <div class="col-sm-10">
                            <textarea name="masalahkhususkepala" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('masalah_kepala', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- Bagian Wajah -->

                    <div class="row mb-2">
                        <label class="col-sm-2 col-form-label text-primary">
                            <strong>Wajah</strong>
                    </div>

                    <!-- Inspeksi -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Inspeksi</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Bentuk, ekspresi wajah (meringis), pembengkakan. Hasil:</small>
                            <textarea name="inspeksiwajah" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('inspeksi_wajah', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- Palpasi -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Palpasi</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Adakah nyeri tekan/tidak ada. Hasil:</small>
                            <textarea name="palpasiwajah" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('palpasi_wajah', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- Masalah Khusus -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Masalah Khusus</strong></label>

                        <div class="col-sm-10">
                            <textarea name="masalahkhususwajah" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('masalah_wajah', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- Bagian Mata -->

                    <div class="row mb-2">
                        <label class="col-sm-2 col-form-label text-primary">
                            <strong>Mata</strong>
                    </div>

                    <!-- Inspeksi Konjungtive -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Inspeksi Konjungtiva</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Konjungtiva: Apakah anemis/an-anemis. Hasil:</small>
                            <textarea name="inspeksikkonjungtiva" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('inspeksik_konjung_tiva', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- Inspeksi Sklera -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Inspeksi Sklera</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Sklera: Ikterik/An-ikterik. Hasil:</small>
                            <textarea name="inspeksisklrea" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('inspeksi_sklrea', $existing_data) ?></textarea></textarea>
                        </div>
                    </div>

                    <!-- Palpasi -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Palpasi</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Kelopak mata: Nyeri tekan/tidak. Hasil:</small>
                            <textarea name="palpasikelopakmata" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('palpasi_kelopak_mata', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- Masalah Khusus -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Masalah Khusus</strong></label>

                        <div class="col-sm-10">
                            <textarea name="masalahkhususmata" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('masalah_mata', $existing_data) ?></textarea></textarea>
                        </div>
                    </div>

                    <!-- Bagian Hidung -->

                    <div class="row mb-2">
                        <label class="col-sm-2 col-form-label text-primary">
                            <strong>Hidung</strong>
                    </div>

                    <!-- Inspeksi -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Inspeksi</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Apakah ada pembengkakan/tidak ada pembengkakan, kesimetrisan lubang hidung, kebersihan, septum utuh/tidak utuh. Hasil:</small>
                            <textarea name="inspeksihidung" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('inspeksi_hidung', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- Palpasi -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Palpasi</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Nyeri tekan/tidak ada. Hasil:</small>
                            <textarea name="palpasihidung" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('palpasi_hidung', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- Riwayat Masalah Khusus -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Masalah Khusus</strong></label>

                        <div class="col-sm-10">
                            <textarea name="masalahkhusushidung" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('masalah_hidung', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- Bagian Mulut -->

                    <div class="row mb-2">
                        <label class="col-sm-2 col-form-label text-primary">
                            <strong>Mulut</strong>
                    </div>

                    <!-- Inspeksi Bibir -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Inspeksi Bibir</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Warna, kesimertrisan, kelembapan, bibir sumbing, ulkus. Hasil:</small>
                            <textarea name="inspeksibibir" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('inspeksi_bibir', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- Inspeksi Gigi -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Inspeksi Gigi</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Amati jumlah, warna, kebersihan, karies. Hasil:</small>
                            <textarea name="inspeksigigi" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('inspeksi_gigi', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- Inspeksi Gusi -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Inspeksi Gusi</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Adakah atau tidak lesi/pembengkakan? Hasil:</small>
                            <textarea name="inspeksigusi" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('inspeksi_gusi', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- Inspeksi Lidah -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Inspeksi Lidah</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Amati warna dan kebersihan. Hasil:</small>
                            <textarea name="inspeksilidah" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('inspeksi_lidah', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- Inspeksi Bau Mulut -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Inspeksi Bau Mulut</strong></label>

                        <div class="col-sm-10">
                            <textarea name="inspeksibaumulut" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('inspeksi_bau_mulut', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- Palpasi -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Palpasi</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Apakah ada nyeri tekan atau tidak ada? Hasil:</small>
                            <textarea name="palpasimulut" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('palpasi_mulut', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- Masalah Khusus -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Masalah Khusus</strong></label>

                        <div class="col-sm-10">
                            <textarea name="masalahkhususmulut" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('masalah_mulut', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- Bagian Telinga -->

                    <div class="row mb-2">
                        <label class="col-sm-2 col-form-label text-primary">
                            <strong>Telinga</strong>
                    </div>

                    <!-- Inspeksi -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Inspeksi</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Bentuk: simetris/tidak. <br> Kebersihan: apakah ada perdarahan, peradangan, kotoran/serumen atau tidak ada? Hasil:</small>
                            <textarea name="inspeksitelinga" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('inspeksi_telinga', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- Palpasi -->
                    <!-- Palpasi Nyeri Tekan -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Palpasi</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Nyeri Tekanan: Apakah ada pembengkakan, nyeri tekan atau tidak ada? Hasil:</small>
                            <textarea name="palpasinyeritekan" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('palpasi_nyeri_tekan', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- Palpasi Gangguan -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Gangguan pendengaran: apakah ada ganguan atau tidak? Hasil:</small>
                            <textarea name="palpasigangguan" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('palpasi_gangguan', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- Riwayat Masalah Khusus -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Masalah Khusus</strong></label>

                        <div class="col-sm-10">
                            <textarea name="masalahkhusustelinga" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('masalah_telinga', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- Bagian Leher -->

                    <div class="row mb-2">
                        <label class="col-sm-2 col-form-label text-primary">
                            <strong>Leher</strong>
                    </div>

                    <!-- Inspeksi -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Inspeksi</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Bentuk leher, ada massa dan benjolan atau tidak. Adakah Distensi vena jugularis/tidak ada. Hasil:</small>
                            <textarea name="inspeksileher" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('inspeksi_leher', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- Palpasi -->
                    <!-- Palpasi Kelenjar Tiroid -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Palpasi</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Kelenjar Tiroid: Apakah ada pembesaran kelenjar tiroid atau tidak. Hasil:</small>
                            <textarea name="palpasikelenjar" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('palpasi_kelenjar', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- Palpasi Trakea -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Trakea: Apakah ada pergeseran/tidak. Hasil:</small>
                            <textarea name="palpasitrakea" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('palpasi_trakea', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- Palpasi Nyeri Menelan -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Nyeri menelan: ya/tidak. Hasil:</small>
                            <textarea name="palpasinyerimenelan" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('palpasi_nyeri_menelan', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- Riwayat Masalah Khusus -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Masalah Khusus</strong></label>

                        <div class="col-sm-10">
                            <textarea name="masalahkhususleher" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('masalah_leher', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- Bagian Axila -->

                    <div class="row mb-2">
                        <label class="col-sm-2 col-form-label text-primary">
                            <strong>Axila</strong>
                    </div>

                    <!-- Inspeksi -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Inspeksi</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Warna, Pembengkakan. Hasil:</small>
                            <textarea name="inspeksiaxilia" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('inspeksi_axila', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- Palpasi -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Palpasi</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Pembesaran kelenjar limfe: Ya/Tidak? Hasil:</small>
                            <textarea name="palpasiaxilia" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('palpasi_axila', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- Masalah Khusus -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Masalah Khusus</strong></label>

                        <div class="col-sm-10">
                            <textarea name="masalahkhususaxilia" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('masalah_axila', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- Bagian Dada -->

                    <div class="row mb-2">
                        <label class="col-sm-8 col-form-label text-primary">
                            <strong>Dada (Sistem Pernapasan)</strong>
                    </div>

                    <!-- Inspeksi -->

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Inspeksi</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Bentuk dada, apakah ada retraksi interkostalis atau tidak, ekspansi dada,
                                gerakan dinding dada dan taktil premitus. Hasil:</small>
                            <textarea name="inspeksidada" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('inspeksi_dada', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- Palplasi -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Palpasi</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Apakah pekak, redup, sonor, hipersonor, timpani? Hasil:</small>
                            <textarea name="palpasidada" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('palpasi_dada', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- Auskultasi -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Auskultasi</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Bunyi napas. Hasil:</small>
                            <textarea name="auskultasidada" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('auskultasi_dada', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- Masalah Khusus -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Masalah Khusus</strong></label>

                        <div class="col-sm-10">
                            <textarea name="masalahkhususdada" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('masalah_khusus_dada', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- Bagian Sistem Kardiovaskuler -->

                    <div class="row mb-2">
                        <label class="col-sm-8 col-form-label text-primary">
                            <strong>Sistem Kardiovaskuler </strong>
                    </div>

                    <!-- Inspeksi dan Palpasi -->

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Inspeksi dan Palpasi</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Area aorta dan pulmonal. Hasil:</small>
                            <textarea name="inspeksidanpalpasisistem" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('inspeksi_dan_palpasi_sistem', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- Perkusi -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Perkusi</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Perkusi batas jantung. Hasil:</small>
                            <textarea name="palpasidada1" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('dada1', $existing_data) ?></textarea></textarea>
                        </div>
                    </div>

                    <!-- Suara Perkusi -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Suara perkusi: (pekak, redup, sonor, hipersonor, timpani) Hasil:</small>
                            <textarea name="masalahkhususdada1" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('khusus', $existing_data) ?></textarea></textarea>
                        </div>
                    </div>

                    <!-- Auskultasi Suara Jantung -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Auskultasi</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Suara jantung. Hasil:</small>
                            <textarea name="auskultasisistem" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('auskultasi_sistem', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- Auskultasi Suara Jantung Tambahan -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Suara jantung tambahan: apakah ada Mur-mur dan gallop. Hasil:</small>
                            <textarea name="auskultasisistem1" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('auskultasi', $existing_data) ?></textarea></textarea>
                        </div>
                    </div>

                    <!-- Masalah Khusus -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Masalah Khusus</strong></label>

                        <div class="col-sm-10">
                            <textarea name="masalahkhusussistem" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('masalah_khusus_sistem', $existing_data) ?></textarea></textarea>
                        </div>
                    </div>

                    <!-- Bagian Payudara -->

                    <div class="row mb-2">
                        <label class="col-sm-2 col-form-label text-primary">
                            <strong>Payudara</strong>
                    </div>

                    <!-- Inspeksi Bentuk-->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Inspeksi Bentuk</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Bentuk, Warna Kulit, Lesi, Kebersihan. Hasil:</small>
                            <textarea name="inspeksibentuk" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('inspeksi_bentuk', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- Inspeksi Pengeluaran Cairan -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Inspeksi Pengeluaran Cairan</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Colostim dan ASI (Ada atau tidak). Hasil:</small>
                            <select class="form-select" name="inspeksipengeluarancairan" <?= $ro_select ?>>
                                <option value="Ya" <?= val('inspeksi_pengeluaran_cairan', $existing_data) === 'Ya' ? 'selected' : '' ?>>Ya</option>
                                <option value="Tidak" <?= val('inspeksi_pengeluaran_cairan', $existing_data) === 'Tidak' ? 'selected' : '' ?>>Tidak</option>
                            </select>
                        </div>
                    </div>

                    <!-- Inspeksi Tanda Pembengkakan -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Inspeksi Tanda Pembengkakan</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Tanda Pembengkakan: Ya/Tidak. Hasil:</small>
                            <textarea name="inspeksipembengkakan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('inspeksi_pembengkakan', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- Palpasi Raba -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Palpasi Raba</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Teraba hangat: Ya/Tidak. Hasil:</small>
                            <select class="form-select" name="palpasiraba" <?= $ro_select ?>>
                                <option value="Ya" <?= val('palpasi_raba', $existing_data) === 'Ya' ? 'selected' : '' ?>>Ya</option>
                                <option value="Tidak" <?= val('palpasi_raba', $existing_data) === 'Tidak' ? 'selected' : '' ?>>Tidak</option>
                            </select>
                        </div>
                    </div>

                    <!-- Palpasi Benjolan -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Palpasi Benjolan</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Ada/Tidak Ada. Hasil:</small>
                            <select class="form-select" name="palpasibenjolan" <?= $ro_select ?>>
                                <option value="Ada" <?= val('palpasi_benjolan', $existing_data) === 'Ya' ? 'selected' : '' ?>>Ada</option>
                                <option value="Tidak Ada" <?= val('palpasi_benjolan', $existing_data) === 'Tidak Ada' ? 'selected' : '' ?>>Tidak Ada</option>
                            </select>
                        </div>
                    </div>

                    <!-- Masalah Khusus -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Masalah Khusus</strong></label>

                        <div class="col-sm-10">
                            <textarea name="masalahkhususpayudadra" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('masalah_khusus_payudadra', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- Bagian Abdomen -->

                    <div class="row mb-3">
                        <label class="col-sm-9 col-form-label text-primary">
                            <strong>Abdomen</strong>
                        </label>
                    </div>

                    <!-- Inspeksi -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Inspeksi</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Bentuk, Warna Kulit, Jaringan Perut (ada/tidak), Strie (ada/tidak), Luka (ada/tidak). Hasil:</small>
                            <textarea name="inspeksiabdomen" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('inspeksi_abdomen', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- Auskultasi -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Auskultasi</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Bising Usus. Hasil:</small>
                            <textarea name="auskultasibisingusus" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('auskultasi_bising_usus', $existing_data) ?></textarea></textarea>
                        </div>
                    </div>

                    <!-- Perkusi -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Perkusi</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Bunyi (Pekak, redup, sonor, hipersonor, timpani). Hasil:</small>
                            <textarea name="perkusi" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('perkusi', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- Palpasi -->

                    <!-- Palpasi Involusi Uterus -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Palpasi</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Nyeri tekan. Hasil:</small>
                            <textarea name="palpasiinvolusi" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('palpasi_involusi', $existing_data) ?></textarea></textarea>
                        </div>
                    </div>

                    <!-- Palpasi Kandung Kemih -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Kandung Kemih: teraba/tidak, penuh/tidak. Hasil:</small>
                            <textarea name="palpasikandungkemih" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('palpasi_kandung_kemih', $existing_data) ?></textarea></textarea>
                        </div>
                    </div>

                    <!-- Masalah Khusus -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Masalah Khusus</strong></label>

                        <div class="col-sm-10">
                            <textarea name="masalahkhususabdomen" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('masalah_khusus__abdomen', $existing_data) ?></textarea></textarea>
                        </div>
                    </div>

                    <!-- Bagian Genetalia dan Anus -->

                    <div class="row mb-2">
                        <label class="col-sm-8 col-form-label text-primary">
                            <strong>Genital dan Anus</strong>
                    </div>

                    <!-- Genetalia dan Anus -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Genetalia dan Anus</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Pendarahan: (ya/tidak), jika ya: warna, sudah berapa lama, konsistensi. Hasil:</small>
                            <textarea name="pendarahan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('pendarahan', $existing_data) ?></textarea></textarea>
                        </div>
                    </div>

                    <!-- Hemoroid -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Hemoroid</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Ya/Tidak</small>
                            <select class="form-select" name="hemoroid" <?= $ro_select ?>>
                                <option value="Ya" <?= val('hemoroid', $existing_data) === 'Ya' ? 'selected' : '' ?>>Ya</option>
                                <option value="Tidak" <?= val('hemoroid', $existing_data) === 'Tidak' ? 'selected' : '' ?>>Tidak</option>
                            </select>
                        </div>
                    </div>

                    <!-- Keputihan -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Keputihan</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Keputihan (ya/tidak), warna, konsistensi, bau, dan gatal. Hasil:</small>
                            <textarea name="keputihan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('keputihan', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- Masalah Khusus -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Masalah Khusus</strong></label>

                        <div class="col-sm-10">
                            <textarea name="masalahkhususgenetalia" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('masalah_khusus_genetalia', $existing_data) ?></textarea></textarea>
                        </div>
                    </div>


                    <!-- Bagian Ekstremitas -->

                    <div class="row mb-2">
                        <label class="col-sm-8 col-form-label text-primary">
                            <strong>Ekstremitas</strong>
                    </div>

                    <!-- Inspeksi Ekstremitas Atas-->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Ekstremitas Atas</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Apakah terdapat edema (Ya/Tidak), rasa kesemutan/baal (Ya/Tidak), Kekuatan otot. Hasil:</small>
                            <textarea name="inspeksiekstremitasatas" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('inspeksi_ekstremitas_atas', $existing_data) ?></textarea></textarea>
                        </div>
                    </div>

                    <!-- Inspeksi Ekstremitas Bawah -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Ekstremitas Bawah</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Apakah terdapat edema (Ya/Tidak), Varises (Ya/Tidak),
                                Refleks Patella (+/-), apakah terdapat kekakuan sendi, dan kekuatan otot. Hasil:</small>
                            <textarea name="inspeksiekstremitasbawah" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('inspeksi_ekstremitas_bawah', $existing_data) ?></textarea></textarea>
                        </div>
                    </div>

                    <!-- Masalah Khusus -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Masalah Khusus</strong></label>

                        <div class="col-sm-10">
                            <textarea name="masalahkhususekstremitas" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('masalah_khusus_ekstremitas', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- Bagian Integumen -->

                    <div class="row mb-2">
                        <label class="col-sm-2 col-form-label text-primary">
                            <strong>Integumen</strong>
                    </div>

                    <!-- Inspeksi -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Inspeksi</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Warna, turgor, elastisitas, ulkus. Hasil:</small>
                            <textarea name="inspeksiintegumen" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('inspeksi_integumen', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- Palpasi -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Palpasi</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Akral, CRT, dan Nyeri. Hasil:</small>
                            <textarea name="palpasiintegumen" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('palpasi_integumen', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- Bagian Eliminasi -->

                    <div class="row mb-2">
                        <label class="col-sm-2 col-form-label text-primary">
                            <strong>Eliminasi</strong>
                    </div>

                    <!-- Inspeksi BAK -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Urin</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">BAK saat ini: nyeri (ya/tidak), frekuensi, jumlah. Hasil:</small>
                            <textarea name="inspeksibak" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('bak', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- Masalah Khusus -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Masalah Khusus</strong></label>

                        <div class="col-sm-10">
                            <textarea name="masalahkhususbak" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('masalah_khusus_bak', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- Inspeksi BAB -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>BAB</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">BAB saat ini: Konstipasi (Ya/Tidak), Frekuensi. Hasil:</small>
                            <textarea name="inspeksibab" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('inspeksi_bab', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- Masalah Khusus -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Masalah Khusus</strong></label>

                        <div class="col-sm-10">
                            <textarea name="masalahkhususbab" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('masalah_khusus_bab', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- Bagian Istirahat dan Kenyamanan -->

                    <div class="row mb-2">
                        <label class="col-sm-6 col-form-label text-primary">
                            <strong>Istirahat dan Kenyamanan</strong>
                    </div>

                    <!-- Inspeksi Istirahat -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Pola Tidur Saat Ini</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Kebiasaan tidur, lama dalam hitungan jam, frekuensi. Hasil:</small>
                            <textarea name="inspeksiistirahat" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('pola_tidur', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- Inspeksi Kenyamanan -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Keluhan ketidaknyamanan (Ya/Tidak), lokasi. Hasil:</small>
                            <textarea name="inspeksikenyamanan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('kenyamanan', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- Masalah Khusus -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Masalah Khusus</strong></label>

                        <div class="col-sm-10">
                            <textarea name="masalahkhususistirahat" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('masalah_istirahat', $existing_data) ?></textarea></textarea>
                        </div>
                    </div>

                    <!-- Bagian Mobilisasi dan Latihan -->

                    <div class="row mb-2">
                        <label class="col-sm-6 col-form-label text-primary">
                            <strong>Mobilisasi dan Latihan</strong>
                    </div>

                    <!-- Inspeksi -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Tingkat Mobilisasi</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Apakah mandiri, parsial, total. Hasil:</small>
                            <textarea name="inspeksimobilisasi" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('tingkat_mobilisasi', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- Masalah Khusus -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Masalah Khusus</strong></label>

                        <div class="col-sm-10">
                            <textarea name="masalahkhususmobilisasi" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('masalah_mobilisasi', $existing_data) ?></textarea></textarea>
                        </div>
                    </div>

                    <!-- Bagian Pola Nutrisi dan Cairan -->

                    <div class="row mb-2">
                        <label class="col-sm-6 col-form-label text-primary">
                            <strong>Pola Nutrisi dan Cairan</strong>
                    </div>

                    <!-- Jenis Makanan -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Jenis Makanan</strong></label>

                        <div class="col-sm-10">
                            <textarea name="jenismakanan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('jenis_makanan', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- Frekuensi -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Frekuensi</strong></label>

                        <div class="col-sm-10">
                            <textarea name="frekuensi" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('frekuensi', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- Konsumsi Snack -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Konsumsi Snack</strong></label>

                        <div class="col-sm-10">
                            <textarea name="konsumsisnack" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('konsumsi_snack', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- Nafsu Makan -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Nafsu Makan</strong></label>

                        <div class="col-sm-10">
                            <textarea name="nafsumakan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('nafsu_makan', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- Pola Minum -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Pola Minum</strong></label>

                        <div class="col-sm-10">
                            <textarea name="polaminum" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('pola_minum', $existing_data) ?></textarea></textarea>
                        </div>
                    </div>

                    <!-- Masalah Khusus -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Masalah Khusus</strong></label>

                        <div class="col-sm-10">
                            <textarea name="masalahkhususbab" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('masalah_khusus_bab', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- Bagian Istirahat dan Kenyamanan -->

                    <div class="row mb-2">
                        <label class="col-sm-6 col-form-label text-primary">
                            <strong>Istirahat dan Kenyamanan</strong>
                    </div>

                    <!-- Inspeksi Istirahat -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Pola Tidur Saat Ini</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Kebiasaan tidur, lama dalam hitungan jam, frekuensi. Hasil:</small>
                            <textarea name="inspeksiistirahat" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('pola_tidur', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- Inspeksi Kenyamanan -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Keluhan ketidaknyamanan (Ya/Tidak), lokasi. Hasil:</small>
                            <textarea name="inspeksikenyamanan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('kenyamanan', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- Masalah Khusus -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Masalah Khusus</strong></label>

                        <div class="col-sm-10">
                            <textarea name="masalahkhususistirahat" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('masalah_istirahat', $existing_data) ?></textarea></textarea>
                        </div>
                    </div>

                    <!-- Bagian Mobilisasi dan Latihan -->

                    <div class="row mb-2">
                        <label class="col-sm-6 col-form-label text-primary">
                            <strong>Mobilisasi dan Latihan</strong>
                    </div>

                    <!-- Inspeksi -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Tingkat Mobilisasi</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Apakah mandiri, parsial, total. Hasil:</small>
                            <textarea name="inspeksimobilisasi" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('tingkat_mobilisasi', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- Masalah Khusus -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Masalah Khusus</strong></label>

                        <div class="col-sm-10">
                            <textarea name="masalahkhususmobilisasi" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('masalah_mobilisasi', $existing_data) ?></textarea></textarea>
                        </div>
                    </div>

                    <!-- Bagian Pola Nutrisi dan Cairan -->

                    <div class="row mb-2">
                        <label class="col-sm-6 col-form-label text-primary">
                            <strong>Pola Nutrisi dan Cairan</strong>
                    </div>

                    <!-- Jenis Makanan -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Jenis Makanan</strong></label>

                        <div class="col-sm-10">
                            <textarea name="jenismakanan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('jenis_makanan', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- Frekuensi -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Frekuensi</strong></label>

                        <div class="col-sm-10">
                            <textarea name="frekuensi" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('frekuensi', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- Konsumsi Snack -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Konsumsi Snack</strong></label>

                        <div class="col-sm-10">
                            <textarea name="konsumsisnack" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('konsumsi_snack', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- Nafsu Makan -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Nafsu Makan</strong></label>

                        <div class="col-sm-10">
                            <textarea name="nafsumakan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('nafsu_makan', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- Pola Minum -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Pola Minum</strong></label>

                        <div class="col-sm-10">
                            <textarea name="polaminum" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('pola_minum', $existing_data) ?></textarea></textarea>
                        </div>
                    </div>

                    <!-- Frekuensi -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Frekuensi</strong></label>

                        <div class="col-sm-10">
                            <textarea name="frekuensi2" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('frekuensi2', $existing_data) ?></textarea></textarea>
                        </div>
                    </div>

                    <!-- Pantangan Makanan -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Pantangan Makanan</strong></label>

                        <div class="col-sm-10">
                            <textarea name="pantanganmakanan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                                <?= $ro ?>><?= val('pantangan_makanan', $existing_data) ?></textarea></textarea>
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



        <!-- ================================ -->
        <!-- SECTION KOMENTAR & ACTION DOSEN -->
        <!-- ================================ -->

        </div>

        <?php include "partials/footer_form.php"; ?>
    </section>
</main>