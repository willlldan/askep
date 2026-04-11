<?php
require_once "koneksi.php";
require_once "utils.php";

$form_id       = 1;
$user_id       = $_SESSION['id_user'];
$section_name  = 'pengkajian_fisik';
$section_label = 'Pengkajian Fisik';

$submission    = getSubmission($user_id, $form_id, $mysqli);
$existing_data = $submission ? getSectionData($submission['id'], $section_name, $mysqli) : [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

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
        'inspeksi_kelopak_mata'     => $_POST['inspeksikelopakmata'] ?? '',
        'inspeksi_bentuk_mata'      => $_POST['inspeksibentukmata'] ?? '',
        'inspeksi_sklera'           => $_POST['inspeksisklera'] ?? '',
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
        'bunyi_napas'               => $_POST['bunyinapas'] ?? '',
        'suara_jantung'             => $_POST['suarajantung'] ?? '',
        'masalah_dada'              => $_POST['masalahkhususdada'] ?? '',
        // Payudara
        'inspeksi_bentuk_payudara'  => $_POST['inspeksibentuk'] ?? '',
        'inspeksi_asi'              => $_POST['inspeksiasi'] ?? '',
        'inspeksi_puting'           => $_POST['inspeksiputing'] ?? '',
        'palpasi_raba'              => $_POST['palpasiraba'] ?? '',
        'palpasi_benjolan'          => $_POST['palpasibenjolan'] ?? '',
        'masalah_payudara'          => $_POST['masalahkhususpayudadra'] ?? '',
        // Abdomen
        'tfu'                       => $_POST['inspeksitfu'] ?? '',
        'kontraksi'                 => $_POST['inspeksikontraksi'] ?? '',
        'leopold_i'                 => $_POST['leopoldi'] ?? '',
        'leopold_ii_kanan'          => $_POST['kanan'] ?? '',
        'leopold_ii_kiri'           => $_POST['kiri'] ?? '',
        'leopold_iii'               => $_POST['leopoldiii'] ?? '',
        'leopold_iv'                => $_POST['leopoldiv'] ?? '',
        'djj'                       => $_POST['pemeriksaandjj'] ?? '',
        'intensitas'                => $_POST['intensitas'] ?? '',
        'keteraturan'               => $_POST['keteraturan'] ?? '',
        'linea_nigra'               => $_POST['linea_nigra'] ?? '',
        'striae'                    => $_POST['striae'] ?? '',
        'fungsi_pencernaan'         => $_POST['fungsipencernaan'] ?? '',
        'bising_usus'               => $_POST['bisingusus'] ?? '',
        'masalah_abdomen'           => $_POST['masalahkhususabdomen'] ?? '',
        // Perineum & Genitalia
        'vagina'                    => $_POST['inspeksivagina'] ?? '',
        'keputihan'                 => $_POST['inspeksikeputihan'] ?? '',
        'hemoroid'                  => $_POST['inspeksihemoroid'] ?? '',
        'masalah_perineum'          => $_POST['masalahkhususperineum'] ?? '',
        // Ekstremitas
        'ekstremitas_atas'          => $_POST['inspeksiekstremitasatas'] ?? '',
        'ekstremitas_bawah'         => $_POST['inspeksiekstremitasbawah'] ?? '',
        'masalah_ekstremitas'       => $_POST['masalahkhususekstremitas'] ?? '',
        // Integumen
        'inspeksi_integumen'        => $_POST['inspeksiintegumen'] ?? '',
        'palpasi_integumen'         => $_POST['palpasiintegumen'] ?? '',
        // Eliminasi
        'bak'                       => $_POST['inspeksibak'] ?? '',
        'bab'                       => $_POST['inspeksibab'] ?? '',
        'masalah_eliminasi'         => $_POST['masalahkhususeliminasi'] ?? '',
        // Istirahat & Kenyamanan
        'pola_tidur'                => $_POST['inspeksiistirahat'] ?? '',
        'kenyamanan'                => $_POST['inspeksikenyamanan'] ?? '',
        'masalah_istirahat'         => $_POST['masalahkhususistirahat'] ?? '',
        // Mobilisasi & Latihan
        'tingkat_mobilisasi'        => $_POST['inspeksimobilisasi'] ?? '',
        'masalah_mobilisasi'        => $_POST['masalahkhususmobilisasi'] ?? '',
        // Nutrisi & Cairan
        'asupan_nutrisi'            => $_POST['inspeksinutrisi'] ?? '',
        'asupan_cairan'             => $_POST['inspeksicairan'] ?? '',
        'pantangan_makan'           => $_POST['inspeksipantangan'] ?? '',
        'masalah_nutrisi'           => $_POST['masalahkhususpolanutrisi'] ?? '',
        // Pengetahuan
        'tanda_melahirkan'          => $_POST['tandamelahirkan'] ?? '',
        'nyeri_melahirkan'          => $_POST['nyerimelahirkan'] ?? '',
        'cara_mengejan'             => $_POST['caramengejan'] ?? '',
        'asi_payudara'              => $_POST['asidanpayudara'] ?? '',
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
?>

<main id="main" class="main">

    <?php include "maternitas/pengkajian_antenatal_care/tab.php"; ?>

    <section class="section dashboard">

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-1"><strong>Pengkajian</strong></h5>

                <form class="needs-validation" novalidate action="" method="POST">

                    <!-- ================================ -->
                    <!-- KEPALA DAN RAMBUT -->
                    <!-- ================================ -->
                    <div class="row mb-2">
                        <label class="col-sm-2 col-form-label text-primary"><strong>Kepala dan Rambut</strong></label>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Inspeksi</strong></label>
                        <div class="col-sm-9">
                            <small class="form-text text-danger">Bentuk kepala, Penyebaran, Kebersihan, Warna Rambut. Hasil:</small>
                            <textarea name="inspeksikepala" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= val('inspeksi_kepala', $existing_data) ?></textarea>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Palpasi</strong></label>
                        <div class="col-sm-9">
                            <small class="form-text text-danger">Apakah terdapat benjolan, pembengkakan, nyeri tekan. Hasil:</small>
                            <textarea name="palpasiikepala" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= val('palpasi_kepala', $existing_data) ?></textarea>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Masalah Khusus</strong></label>
                        <div class="col-sm-9">
                            <textarea name="masalahkhususkepala" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= val('masalah_kepala', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- ================================ -->
                    <!-- WAJAH -->
                    <!-- ================================ -->
                    <div class="row mb-2">
                        <label class="col-sm-2 col-form-label text-primary"><strong>Wajah</strong></label>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Inspeksi</strong></label>
                        <div class="col-sm-9">
                            <small class="form-text text-danger">Bentuk, adakah hiperpigmentasi/cloasma gravidarum, area jika ada cloasma. Hasil:</small>
                            <textarea name="inspeksiwajah" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= val('inspeksi_wajah', $existing_data) ?></textarea>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Palpasi</strong></label>
                        <div class="col-sm-9">
                            <small class="form-text text-danger">Adakah nyeri tekan/tidak ada. Hasil:</small>
                            <textarea name="palpasiwajah" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= val('palpasi_wajah', $existing_data) ?></textarea>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Masalah Khusus</strong></label>
                        <div class="col-sm-9">
                            <textarea name="masalahkhususwajah" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= val('masalah_wajah', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- ================================ -->
                    <!-- MATA -->
                    <!-- ================================ -->
                    <div class="row mb-2">
                        <label class="col-sm-2 col-form-label text-primary"><strong>Mata</strong></label>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Inspeksi Kelopak Mata</strong></label>
                        <div class="col-sm-9">
                            <small class="form-text text-danger">Kelopak mata apakah terdapat pembengkakan. Hasil:</small>
                            <textarea name="inspeksikelopakmata" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= val('inspeksi_kelopak_mata', $existing_data) ?></textarea>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Inspeksi Bentuk Mata</strong></label>
                        <div class="col-sm-9">
                            <small class="form-text text-danger">Apakah simetris/tidak simetris. Hasil:</small>
                            <textarea name="inspeksibentukmata" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= val('inspeksi_bentuk_mata', $existing_data) ?></textarea>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Inspeksi Sklera</strong></label>
                        <div class="col-sm-9">
                            <small class="form-text text-danger">Apakah anemis/an-anemis. Hasil:</small>
                            <textarea name="inspeksisklera" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= val('inspeksi_sklera', $existing_data) ?></textarea>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Palpasi Kelopak Mata</strong></label>
                        <div class="col-sm-9">
                            <small class="form-text text-danger">Nyeri tekan/tidak. Hasil:</small>
                            <textarea name="palpasikelopakmata" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= val('palpasi_kelopak_mata', $existing_data) ?></textarea>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Masalah Khusus</strong></label>
                        <div class="col-sm-9">
                            <textarea name="masalahkhususmata" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= val('masalah_mata', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- ================================ -->
                    <!-- HIDUNG -->
                    <!-- ================================ -->
                    <div class="row mb-2">
                        <label class="col-sm-2 col-form-label text-primary"><strong>Hidung</strong></label>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Inspeksi</strong></label>
                        <div class="col-sm-9">
                            <small class="form-text text-danger">Apakah ada pembengkakan/tidak, kesimetrisan lubang hidung, kebersihan, septum utuh/tidak. Hasil:</small>
                            <textarea name="inspeksihidung" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= val('inspeksi_hidung', $existing_data) ?></textarea>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Palpasi</strong></label>
                        <div class="col-sm-9">
                            <small class="form-text text-danger">Nyeri tekan/tidak ada. Hasil:</small>
                            <textarea name="palpasihidung" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= val('palpasi_hidung', $existing_data) ?></textarea>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Masalah Khusus</strong></label>
                        <div class="col-sm-9">
                            <textarea name="masalahkhusushidung" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= val('masalah_hidung', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- ================================ -->
                    <!-- MULUT -->
                    <!-- ================================ -->
                    <div class="row mb-2">
                        <label class="col-sm-2 col-form-label text-primary"><strong>Mulut</strong></label>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Inspeksi Bibir</strong></label>
                        <div class="col-sm-9">
                            <small class="form-text text-danger">Warna, kesimetrisan, kelembapan, bibir sumbing, ulkus. Hasil:</small>
                            <textarea name="inspeksibibir" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= val('inspeksi_bibir', $existing_data) ?></textarea>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Inspeksi Gigi</strong></label>
                        <div class="col-sm-9">
                            <small class="form-text text-danger">Amati jumlah, warna, kebersihan, karies. Hasil:</small>
                            <textarea name="inspeksigigi" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= val('inspeksi_gigi', $existing_data) ?></textarea>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Inspeksi Gusi</strong></label>
                        <div class="col-sm-9">
                            <small class="form-text text-danger">Adakah atau tidak lesi/pembengkakan? Hasil:</small>
                            <textarea name="inspeksigusi" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= val('inspeksi_gusi', $existing_data) ?></textarea>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Inspeksi Lidah</strong></label>
                        <div class="col-sm-9">
                            <small class="form-text text-danger">Amati warna dan kebersihan. Hasil:</small>
                            <textarea name="inspeksilidah" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= val('inspeksi_lidah', $existing_data) ?></textarea>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Inspeksi Bau Mulut</strong></label>
                        <div class="col-sm-9">
                            <textarea name="inspeksibaumulut" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= val('inspeksi_bau_mulut', $existing_data) ?></textarea>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Palpasi</strong></label>
                        <div class="col-sm-9">
                            <small class="form-text text-danger">Apakah ada nyeri tekan atau tidak ada? Hasil:</small>
                            <textarea name="palpasimulut" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= val('palpasi_mulut', $existing_data) ?></textarea>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Masalah Khusus</strong></label>
                        <div class="col-sm-9">
                            <textarea name="masalahkhususmulut" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= val('masalah_mulut', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- ================================ -->
                    <!-- TELINGA -->
                    <!-- ================================ -->
                    <div class="row mb-2">
                        <label class="col-sm-2 col-form-label text-primary"><strong>Telinga</strong></label>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Inspeksi</strong></label>
                        <div class="col-sm-9">
                            <small class="form-text text-danger">Bentuk: simetris/tidak. Kebersihan: apakah ada perdarahan, peradangan, kotoran/serumen atau tidak ada? Hasil:</small>
                            <textarea name="inspeksitelinga" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= val('inspeksi_telinga', $existing_data) ?></textarea>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Palpasi</strong></label>
                        <div class="col-sm-9">
                            <small class="form-text text-danger">Nyeri Tekan: Apakah ada pembengkakan, nyeri tekan atau tidak ada? Hasil:</small>
                            <textarea name="palpasinyeritekan" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= val('palpasi_nyeri_tekan', $existing_data) ?></textarea>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"></label>
                        <div class="col-sm-9">
                            <small class="form-text text-danger">Gangguan pendengaran: apakah ada gangguan atau tidak? Hasil:</small>
                            <textarea name="palpasigangguan" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= val('palpasi_gangguan', $existing_data) ?></textarea>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Masalah Khusus</strong></label>
                        <div class="col-sm-9">
                            <textarea name="masalahkhusustelinga" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= val('masalah_telinga', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- ================================ -->
                    <!-- LEHER -->
                    <!-- ================================ -->
                    <div class="row mb-2">
                        <label class="col-sm-2 col-form-label text-primary"><strong>Leher</strong></label>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Inspeksi</strong></label>
                        <div class="col-sm-9">
                            <small class="form-text text-danger">Bentuk leher, ada massa dan benjolan atau tidak. Adakah Distensi vena jugularis/tidak ada. Hasil:</small>
                            <textarea name="inspeksileher" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= val('inspeksi_leher', $existing_data) ?></textarea>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Palpasi</strong></label>
                        <div class="col-sm-9">
                            <small class="form-text text-danger">Kelenjar Tiroid: Apakah ada pembesaran kelenjar tiroid atau tidak. Hasil:</small>
                            <textarea name="palpasikelenjar" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= val('palpasi_kelenjar', $existing_data) ?></textarea>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"></label>
                        <div class="col-sm-9">
                            <small class="form-text text-danger">Trakea: Apakah ada pergeseran/tidak. Hasil:</small>
                            <textarea name="palpasitrakea" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= val('palpasi_trakea', $existing_data) ?></textarea>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"></label>
                        <div class="col-sm-9">
                            <small class="form-text text-danger">Nyeri menelan: ya/tidak. Hasil:</small>
                            <textarea name="palpasinyerimenelan" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= val('palpasi_nyeri_menelan', $existing_data) ?></textarea>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Masalah Khusus</strong></label>
                        <div class="col-sm-9">
                            <textarea name="masalahkhususleher" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= val('masalah_leher', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- ================================ -->
                    <!-- AXILA -->
                    <!-- ================================ -->
                    <div class="row mb-2">
                        <label class="col-sm-2 col-form-label text-primary"><strong>Axila</strong></label>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Inspeksi</strong></label>
                        <div class="col-sm-9">
                            <small class="form-text text-danger">Warna, Pembengkakan. Hasil:</small>
                            <textarea name="inspeksiaxilia" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= val('inspeksi_axila', $existing_data) ?></textarea>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Palpasi</strong></label>
                        <div class="col-sm-9">
                            <small class="form-text text-danger">Pembesaran kelenjar limfe: Ya/Tidak? Hasil:</small>
                            <textarea name="palpasiaxilia" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= val('palpasi_axila', $existing_data) ?></textarea>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Masalah Khusus</strong></label>
                        <div class="col-sm-9">
                            <textarea name="masalahkhususaxilia" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= val('masalah_axila', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- ================================ -->
                    <!-- DADA -->
                    <!-- ================================ -->
                    <div class="row mb-2">
                        <label class="col-sm-8 col-form-label text-primary"><strong>Dada; Sistem Pernapasan dan Kardiovaskuler</strong></label>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Auskultasi</strong></label>
                        <div class="col-sm-9">
                            <small class="form-text text-danger">Bunyi Napas. Hasil:</small>
                            <textarea name="bunyinapas" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= val('bunyi_napas', $existing_data) ?></textarea>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"></label>
                        <div class="col-sm-9">
                            <small class="form-text text-danger">Suara Jantung (Apakah ada mur-mur dan gallop). Hasil:</small>
                            <textarea name="suarajantung" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= val('suara_jantung', $existing_data) ?></textarea>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Masalah Khusus</strong></label>
                        <div class="col-sm-9">
                            <textarea name="masalahkhususdada" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= val('masalah_dada', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- ================================ -->
                    <!-- PAYUDARA -->
                    <!-- ================================ -->
                    <div class="row mb-2">
                        <label class="col-sm-2 col-form-label text-primary"><strong>Payudara</strong></label>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Inspeksi Bentuk</strong></label>
                        <div class="col-sm-9">
                            <small class="form-text text-danger">Bentuk, Lesi, Kebersihan. Hasil:</small>
                            <textarea name="inspeksibentuk" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= val('inspeksi_bentuk_payudara', $existing_data) ?></textarea>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Inspeksi Pengeluaran ASI</strong></label>
                        <div class="col-sm-9">
                            <small class="form-text text-danger">Pengeluaran ASI (Ada atau tidak). Hasil:</small>
                            <textarea name="inspeksiasi" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= val('inspeksi_asi', $existing_data) ?></textarea>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Inspeksi Puting</strong></label>
                        <div class="col-sm-9">
                            <small class="form-text text-danger">Eksverted/Inverted/Plat nipple. Hasil:</small>
                            <textarea name="inspeksiputing" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= val('inspeksi_puting', $existing_data) ?></textarea>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Palpasi Raba</strong></label>
                        <div class="col-sm-9">
                            <small class="form-text text-danger">Teraba hangat: Ya/Tidak. Hasil:</small>
                            <select class="form-select" name="palpasiraba">
                                <option value="">Pilih</option>
                                <option value="Ya" <?= val('palpasi_raba', $existing_data) === 'Ya' ? 'selected' : '' ?>>Ya</option>
                                <option value="Tidak" <?= val('palpasi_raba', $existing_data) === 'Tidak' ? 'selected' : '' ?>>Tidak</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Palpasi Benjolan</strong></label>
                        <div class="col-sm-9">
                            <small class="form-text text-danger">Ada/Tidak Ada. Hasil:</small>
                            <select class="form-select" name="palpasibenjolan">
                                <option value="">Pilih</option>
                                <option value="Ada" <?= val('palpasi_benjolan', $existing_data) === 'Ada' ? 'selected' : '' ?>>Ada</option>
                                <option value="Tidak Ada" <?= val('palpasi_benjolan', $existing_data) === 'Tidak Ada' ? 'selected' : '' ?>>Tidak Ada</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Masalah Khusus</strong></label>
                        <div class="col-sm-9">
                            <textarea name="masalahkhususpayudadra" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= val('masalah_payudara', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- ================================ -->
                    <!-- ABDOMEN -->
                    <!-- ================================ -->
                    <div class="row mb-2">
                        <label class="col-sm-8 col-form-label text-primary"><strong>Abdomen - Inspeksi Uterus</strong></label>
                    </div>
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-2 col-form-label"><strong>TFU</strong></label>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="text" class="form-control" name="inspeksitfu" value="<?= val('tfu', $existing_data) ?>">
                                <span class="input-group-text">cm</span>
                            </div>
                        </div>
                        <label class="col-sm-2 col-form-label"><strong>Kontraksi</strong></label>
                        <div class="col-sm-3">
                            <select class="form-select" name="inspeksikontraksi">
                                <option value="">Pilih</option>
                                <option value="Ya" <?= val('kontraksi', $existing_data) === 'Ya' ? 'selected' : '' ?>>Ya</option>
                                <option value="Tidak" <?= val('kontraksi', $existing_data) === 'Tidak' ? 'selected' : '' ?>>Tidak</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Leopold I</strong></label>
                        <div class="col-sm-9">
                            <select class="form-select" name="leopoldi">
                                <option value="">Pilih</option>
                                <option value="Kepala" <?= val('leopold_i', $existing_data) === 'Kepala' ? 'selected' : '' ?>>Kepala</option>
                                <option value="Bokong" <?= val('leopold_i', $existing_data) === 'Bokong' ? 'selected' : '' ?>>Bokong</option>
                                <option value="Kosong" <?= val('leopold_i', $existing_data) === 'Kosong' ? 'selected' : '' ?>>Kosong</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label class="col-sm-2 col-form-label"><strong>Leopold II</strong></label>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Kanan</strong></label>
                        <div class="col-sm-9">
                            <select class="form-select" name="kanan">
                                <option value="">Pilih</option>
                                <option value="Punggung" <?= val('leopold_ii_kanan', $existing_data) === 'Punggung' ? 'selected' : '' ?>>Punggung</option>
                                <option value="Bagian Kecil" <?= val('leopold_ii_kanan', $existing_data) === 'Bagian Kecil' ? 'selected' : '' ?>>Bagian Kecil</option>
                                <option value="Kepala" <?= val('leopold_ii_kanan', $existing_data) === 'Kepala' ? 'selected' : '' ?>>Kepala</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Kiri</strong></label>
                        <div class="col-sm-9">
                            <select class="form-select" name="kiri">
                                <option value="">Pilih</option>
                                <option value="Punggung" <?= val('leopold_ii_kiri', $existing_data) === 'Punggung' ? 'selected' : '' ?>>Punggung</option>
                                <option value="Bagian Kecil" <?= val('leopold_ii_kiri', $existing_data) === 'Bagian Kecil' ? 'selected' : '' ?>>Bagian Kecil</option>
                                <option value="Kepala" <?= val('leopold_ii_kiri', $existing_data) === 'Kepala' ? 'selected' : '' ?>>Kepala</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Leopold III</strong></label>
                        <div class="col-sm-9">
                            <select class="form-select" name="leopoldiii">
                                <option value="">Pilih</option>
                                <option value="Kepala" <?= val('leopold_iii', $existing_data) === 'Kepala' ? 'selected' : '' ?>>Kepala</option>
                                <option value="Bokong" <?= val('leopold_iii', $existing_data) === 'Bokong' ? 'selected' : '' ?>>Bokong</option>
                                <option value="Kosong" <?= val('leopold_iii', $existing_data) === 'Kosong' ? 'selected' : '' ?>>Kosong</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Leopold IV Penurunan Kepala</strong></label>
                        <div class="col-sm-9">
                            <select class="form-select" name="leopoldiv">
                                <option value="">Pilih</option>
                                <option value="Sudah" <?= val('leopold_iv', $existing_data) === 'Sudah' ? 'selected' : '' ?>>Sudah</option>
                                <option value="Belum" <?= val('leopold_iv', $existing_data) === 'Belum' ? 'selected' : '' ?>>Belum</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Pemeriksaan DJJ</strong></label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <input type="text" class="form-control" name="pemeriksaandjj" value="<?= val('djj', $existing_data) ?>">
                                <span class="input-group-text">Frek</span>
                            </div>
                            <small class="form-text text-danger">(Normal 120-160/bradikardi, 160-180/tachikardi &lt; 120)</small>
                        </div>
                    </div>
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-2 col-form-label"><strong>Intensitas</strong></label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control" name="intensitas" value="<?= val('intensitas', $existing_data) ?>">
                        </div>
                        <label class="col-sm-2 col-form-label"><strong>Keteraturan</strong></label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control" name="keteraturan" value="<?= val('keteraturan', $existing_data) ?>">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label class="col-sm-2 col-form-label"><strong>Pigmentasi</strong></label>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Linea Nigra</strong></label>
                        <div class="col-sm-9">
                            <select class="form-select" name="linea_nigra">
                                <option value="">Pilih</option>
                                <option value="Ada" <?= val('linea_nigra', $existing_data) === 'Ada' ? 'selected' : '' ?>>Ada</option>
                                <option value="Tidak" <?= val('linea_nigra', $existing_data) === 'Tidak' ? 'selected' : '' ?>>Tidak</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Striae</strong></label>
                        <div class="col-sm-9">
                            <select class="form-select" name="striae">
                                <option value="">Pilih</option>
                                <option value="Ada" <?= val('striae', $existing_data) === 'Ada' ? 'selected' : '' ?>>Ada</option>
                                <option value="Tidak" <?= val('striae', $existing_data) === 'Tidak' ? 'selected' : '' ?>>Tidak</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Fungsi Pencernaan</strong></label>
                        <div class="col-sm-9">
                            <textarea name="fungsipencernaan" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= val('fungsi_pencernaan', $existing_data) ?></textarea>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Bising Usus</strong></label>
                        <div class="col-sm-9">
                            <textarea name="bisingusus" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= val('bising_usus', $existing_data) ?></textarea>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Masalah Khusus</strong></label>
                        <div class="col-sm-9">
                            <textarea name="masalahkhususabdomen" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= val('masalah_abdomen', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- ================================ -->
                    <!-- PERINEUM DAN GENITALIA -->
                    <!-- ================================ -->
                    <div class="row mb-2">
                        <label class="col-sm-8 col-form-label text-primary"><strong>Perineum dan Genitalia</strong></label>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Vagina</strong></label>
                        <div class="col-sm-9">
                            <small class="form-text text-danger">Ada varises atau tidak. Kebersihan. Hasil:</small>
                            <textarea name="inspeksivagina" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= val('vagina', $existing_data) ?></textarea>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Keputihan</strong></label>
                        <div class="col-sm-9">
                            <small class="form-text text-danger">Keputihan (Ya/Tidak): warna, konsistensi, bau, dan gatal. Hasil:</small>
                            <textarea name="inspeksikeputihan" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= val('keputihan', $existing_data) ?></textarea>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Hemoroid</strong></label>
                        <div class="col-sm-9">
                            <small class="form-text text-danger">Hemoroid (Ya/Tidak). Jika Ya sebutkan (derajat, sudah berapa lama, nyeri?). Hasil:</small>
                            <textarea name="inspeksihemoroid" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= val('hemoroid', $existing_data) ?></textarea>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Masalah Khusus</strong></label>
                        <div class="col-sm-9">
                            <textarea name="masalahkhususperineum" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= val('masalah_perineum', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- ================================ -->
                    <!-- EKSTREMITAS -->
                    <!-- ================================ -->
                    <div class="row mb-2">
                        <label class="col-sm-8 col-form-label text-primary"><strong>Ekstremitas</strong></label>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Ekstremitas Atas</strong></label>
                        <div class="col-sm-9">
                            <small class="form-text text-danger">Apakah terdapat edema (Ya/Tidak), rasa kesemutan/baal (Ya/Tidak), Kekuatan otot. Hasil:</small>
                            <textarea name="inspeksiekstremitasatas" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= val('ekstremitas_atas', $existing_data) ?></textarea>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Ekstremitas Bawah</strong></label>
                        <div class="col-sm-9">
                            <small class="form-text text-danger">Apakah terdapat edema (Ya/Tidak), Varises (Ya/Tidak), Tanda Homan (+/-), Refleks Patella (+/-), kekakuan sendi, kekuatan otot. Hasil:</small>
                            <textarea name="inspeksiekstremitasbawah" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= val('ekstremitas_bawah', $existing_data) ?></textarea>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Masalah Khusus</strong></label>
                        <div class="col-sm-9">
                            <textarea name="masalahkhususekstremitas" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= val('masalah_ekstremitas', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- ================================ -->
                    <!-- INTEGUMEN -->
                    <!-- ================================ -->
                    <div class="row mb-2">
                        <label class="col-sm-2 col-form-label text-primary"><strong>Integumen</strong></label>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Inspeksi</strong></label>
                        <div class="col-sm-9">
                            <small class="form-text text-danger">Warna, turgor, elastisitas, ulkus. Hasil:</small>
                            <textarea name="inspeksiintegumen" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= val('inspeksi_integumen', $existing_data) ?></textarea>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Palpasi</strong></label>
                        <div class="col-sm-9">
                            <small class="form-text text-danger">Akral, CRT, dan Nyeri. Hasil:</small>
                            <textarea name="palpasiintegumen" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= val('palpasi_integumen', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- ================================ -->
                    <!-- ELIMINASI -->
                    <!-- ================================ -->
                    <div class="row mb-2">
                        <label class="col-sm-2 col-form-label text-primary"><strong>Eliminasi</strong></label>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Urin</strong></label>
                        <div class="col-sm-9">
                            <small class="form-text text-danger">BAK saat ini. Hasil:</small>
                            <textarea name="inspeksibak" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= val('bak', $existing_data) ?></textarea>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"></label>
                        <div class="col-sm-9">
                            <small class="form-text text-danger">BAB saat ini: Konstipasi (Ya/Tidak), Frekuensi. Hasil:</small>
                            <textarea name="inspeksibab" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= val('bab', $existing_data) ?></textarea>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Masalah Khusus</strong></label>
                        <div class="col-sm-9">
                            <textarea name="masalahkhususeliminasi" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= val('masalah_eliminasi', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- ================================ -->
                    <!-- ISTIRAHAT DAN KENYAMANAN -->
                    <!-- ================================ -->
                    <div class="row mb-2">
                        <label class="col-sm-6 col-form-label text-primary"><strong>Istirahat dan Kenyamanan</strong></label>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Pola Tidur Saat Ini</strong></label>
                        <div class="col-sm-9">
                            <small class="form-text text-danger">Kebiasaan tidur, lama dalam hitungan jam, frekuensi. Hasil:</small>
                            <textarea name="inspeksiistirahat" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= val('pola_tidur', $existing_data) ?></textarea>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"></label>
                        <div class="col-sm-9">
                            <small class="form-text text-danger">Keluhan ketidaknyamanan (Ya/Tidak), lokasi. Hasil:</small>
                            <textarea name="inspeksikenyamanan" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= val('kenyamanan', $existing_data) ?></textarea>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Masalah Khusus</strong></label>
                        <div class="col-sm-9">
                            <textarea name="masalahkhususistirahat" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= val('masalah_istirahat', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- ================================ -->
                    <!-- MOBILISASI DAN LATIHAN -->
                    <!-- ================================ -->
                    <div class="row mb-2">
                        <label class="col-sm-6 col-form-label text-primary"><strong>Mobilisasi dan Latihan</strong></label>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Tingkat Mobilisasi</strong></label>
                        <div class="col-sm-9">
                            <small class="form-text text-danger">Apakah mandiri, parsial, total. Hasil:</small>
                            <textarea name="inspeksimobilisasi" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= val('tingkat_mobilisasi', $existing_data) ?></textarea>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Masalah Khusus</strong></label>
                        <div class="col-sm-9">
                            <textarea name="masalahkhususmobilisasi" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= val('masalah_mobilisasi', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- ================================ -->
                    <!-- POLA NUTRISI DAN CAIRAN -->
                    <!-- ================================ -->
                    <div class="row mb-2">
                        <label class="col-sm-6 col-form-label text-primary"><strong>Pola Nutrisi dan Cairan</strong></label>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Asupan Nutrisi</strong></label>
                        <div class="col-sm-9">
                            <small class="form-text text-danger">Nafsu makan: baik, kurang atau tidak nafsu makan. Hasil:</small>
                            <textarea name="inspeksinutrisi" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= val('asupan_nutrisi', $existing_data) ?></textarea>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Asupan Cairan</strong></label>
                        <div class="col-sm-9">
                            <small class="form-text text-danger">Asupan cairan (cukup/kurang). Hasil:</small>
                            <textarea name="inspeksicairan" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= val('asupan_cairan', $existing_data) ?></textarea>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Pantangan Makan</strong></label>
                        <div class="col-sm-9">
                            <textarea name="inspeksipantangan" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= val('pantangan_makan', $existing_data) ?></textarea>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Masalah Khusus</strong></label>
                        <div class="col-sm-9">
                            <textarea name="masalahkhususpolanutrisi" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= val('masalah_nutrisi', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- ================================ -->
                    <!-- PENGETAHUAN -->
                    <!-- ================================ -->
                    <div class="row mb-2">
                        <label class="col-sm-2 col-form-label text-primary"><strong>Pengetahuan</strong></label>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Tanda-tanda Melahirkan</strong></label>
                        <div class="col-sm-9">
                            <textarea name="tandamelahirkan" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= val('tanda_melahirkan', $existing_data) ?></textarea>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Cara Menangani Nyeri Melahirkan</strong></label>
                        <div class="col-sm-9">
                            <textarea name="nyerimelahirkan" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= val('nyeri_melahirkan', $existing_data) ?></textarea>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <!-- fix: name ganti dari 'persalinan' ke 'caramengejan' -->
                        <label class="col-sm-2 col-form-label"><strong>Cara Mengejan Saat Persalinan</strong></label>
                        <div class="col-sm-9">
                            <textarea name="caramengejan" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= val('cara_mengejan', $existing_data) ?></textarea>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Manfaat ASI dan Perawatan Payudara</strong></label>
                        <div class="col-sm-9">
                            <textarea name="asidanpayudara" class="form-control" rows="3" style="overflow:hidden; resize:none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"><?= val('asi_payudara', $existing_data) ?></textarea>
                        </div>
                    </div>

                    <!-- TOMBOL SUBMIT -->
                    <div class="row mb-3">
                        <div class="col-sm-11 d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </div>

                    <script>
                        const existingData = <?= json_encode($existing_data) ?>;
                    </script>

                </form>
            </div>
        </div>

        <?php include "tab_navigasi.php"; ?>

    </section>
</main>