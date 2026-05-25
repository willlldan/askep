<?php

require_once "koneksi.php";
require_once "utils.php";


$username = $_SESSION['username'];
$identitas_result = $mysqli->query("SELECT id, nama, tempat_lahir, tgl_lahir FROM tbl_gerontik_identitas WHERE created_by = '$username' ORDER BY created_at DESC");

$alert = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_identitas = isset($_POST['id_identitas']) ? intval($_POST['id_identitas']) : (isset($_GET['idpasien']) ? intval($_GET['idpasien']) : 0);
} else {
    $id_identitas = isset($_GET['idpasien']) ? intval($_GET['idpasien']) : 0;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $created_at = date('Y-m-d H:i:s');
    $created_by = $username;

    // ===== DAFTAR FIELD (SAMA DENGAN MASS ASSIGNMENT) =====
    $fields = [
        // Tanda Vital & Kesadaran
        'td',
        'nadi',
        'rr',
        'suhu',
        'tingkat_kesadaran',

        // Kepala & Kulit Kepala
        'sakit_kepala',
        'trauma_kepala_masa_lalu',
        'gatal_kulit_kepala',
        'kulit_rambut_bersih',
        'rambut_rontok',

        // Mata
        'penurunan_penglihatan',
        'penglihatan_kabur',
        'kekeruhan_lensa',
        'kacamata_lensa_kontak',
        'nyeri_mata',
        'pruritus',
        'bengkak_mata',
        'floater',
        'diplopia',

        // Telinga
        'penurunan_pendengaran',
        'alat_bantu_pendengaran',

        // Hidung & Sinus
        'rinorea',
        'rabas_hidung',
        'riwayat_epitaksis',
        'mendengkur_tidur',
        'nyeri_sinus',
        'pernapasan_cuping_hidung',

        // Tenggorok
        'sakit_tenggorokan',
        'lesi_ulkus',
        'suara_serak',
        'perubahan_suara',
        'kesulitan_menelan',
        'perdarahan_gusi',

        // Mulut & Gigi
        'karies',
        'gigi_bersih',
        'gigi_palsu',
        'rutin_menggosok_gigi',

        // Leher
        'kekakuan_leher',
        'nyeri_leher',
        'benjolan_leher',
        'keterbatasan_gerak_leher',

        // Pernapasan
        'batuk',
        'sesak_napas',
        'hemoptomisis',
        'sputum',
        'riwayat_asma',
        'kesulitan_menarik_napas',

        // Kardiovaskuler
        'nyeri_dada',
        'palpitasi',
        'dispneu_nocturnal',
        'ortopneu',
        'murmur',
        'edema',
        'parestesia',
        'riwayat_infark',

        // Gastrointestinal
        'disfagia',
        'tidak_dapat_mengunyah',
        'nyeri_uluhati',
        'mual',
        'muntah',
        'hematemesis',
        'penurunan_selera_makan',
        'ikterik',

        // Perkemihan
        'disuria',
        'frekuensi',
        'menetes',
        'hematuria',
        'poliuria',
        'oliguria',
        'riwayat_batu_perkemihan',
        'nokturia',
        'inkontinensia_uri',
        'riwayat_pembesaran_prostat',

        // Muskuloskeletal
        'nyeri_sendi',
        'kekakuan_sendi',
        'pembengkakan_sendi',
        'spasme',
        'kram_otot',
        'deformitas',
        'penurunan_kekuatan_otot',
        'kelemahan',
        'nyeri_punggung_belakang',
        'nyeri_pinggang',
        'alat_bantuan_berjalan',
        'perubahan_cara_berjalan',
        'tremor',
        'atropi_otot',

        // Endokrin
        'intoleransi_panas',
        'intoleransi_dingin',
        'goiter',
        'poli_fagi',
        'poli_uri',
        'poli_dipsi',
        'perubahan_rambut',
        'pigmentasi_kulit',

        // Integumen
        'kulit_kering',
        'kulit_keriput',
        'menjaga_kebersihan_kulit',
        'penurunan_lemak_bawah_kulit'
    ];

    // ===== AMBIL DATA POST =====
    $data = [];
    foreach ($fields as $field) {
        $data[$field] = $_POST[$field] ?? '';
    }

    // ===== BUILD QUERY =====
    $columns = implode(', ', array_keys($data));
    $placeholders = implode(', ', array_fill(0, count($data), '?'));

    $sql = "
        INSERT INTO tbl_gerontik_pengkajian_fisik
        (id_identitas, $columns, created_at, created_by)
        VALUES (?, $placeholders, ?, ?)
    ";

    $stmt = $mysqli->prepare($sql);

    if ($stmt) {

        // ===== TYPE STRING =====
        $types = 'i' . str_repeat('s', count($data)) . 'ss';

        $values = array_merge([$id_identitas], array_values($data), [$created_at, $created_by]);

        $stmt->bind_param($types, ...$values);

        if ($stmt->execute()) {
            echo "<script>window.location.href = 'index.php?page=gerontik&tab=pengkajian-kebiasaan&idpasien=" . urlencode($id_identitas) . "';</script>";
        } else {
            $alert = '<div class="alert alert-danger">Gagal menyimpan data: ' . htmlspecialchars($stmt->error) . '</div>';
        }

        $stmt->close();
    } else {
        $alert = '<div class="alert alert-danger">Gagal menyiapkan statement: ' . htmlspecialchars($mysqli->error) . '</div>';
    }
} else {
    $id_identitas = isset($_GET['idpasien']) ? intval($_GET['idpasien']) : 0;
}

?>

<main id="main" class="main">
    <?php include "navbar_gerontik.php"; ?>

    <section class="section dashboard">
        <div class="card">
            <div class="card-body">

                <form class="needs-validation" novalidate method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id_identitas" id="hidden-id-identitas" value="<?= htmlspecialchars($id_identitas) ?>">
                    <h5 class="card-title"><strong>VI. Pemeriksaan Fisik</strong></h5>

                    <div class="row mb-2">
                        <label class="col-sm-12 col-form-label text-primary">
                            <strong>Tanda-tanda Vital</strong>
                        </label>
                    </div>

                    <!-- TD -->
                    <div class="row mb-3 align-items-start">
                        <label class="col-sm-2 col-form-label"><strong>TD (Tekanan Darah)</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="td" placeholder="Masukkan TD">
                        </div>

                    </div>

                    <!-- Nadi -->
                    <div class="row mb-3 align-items-start">
                        <label class="col-sm-2 col-form-label"><strong>N (Nadi)</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="nadi" placeholder="Masukkan Nadi">
                        </div>

                    </div>

                    <!-- RR -->
                    <div class="row mb-3 align-items-start">
                        <label class="col-sm-2 col-form-label"><strong>RR (Frekuensi Pernafasan)</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="rr" placeholder="Masukkan RR">
                        </div>

                    </div>

                    <!-- Suhu -->
                    <div class="row mb-3 align-items-start">
                        <label class="col-sm-2 col-form-label"><strong>Suhu (Celsius)</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="suhu" placeholder="Masukkan Suhu">
                        </div>

                    </div>

                    <!-- Kesadaran -->
                    <div class="row mb-3 align-items-start">
                        <label class="col-sm-2 col-form-label"><strong>Tingkat Kesadaran</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="tingkat_kesadaran"
                                placeholder="Masukkan tingkat kesadaran">
                        </div>

                    </div>

                    <h5 class="card-title mt-4"><strong>Pengkajian Head to Toe</strong></h5>

                    <div class="row mb-2">
                        <label class="col-sm-12 col-form-label text-primary">
                            <strong>1. Kepala</strong>
                        </label>
                    </div>

                    <!-- Sakit Kepala & Trauma Kepala -->
                    <div class="row mb-3 align-items-center">
                        <div class="col-sm-2 col-form-label"><strong>Sakit Kepala</strong></div>
                        <div class="col-sm-4">
                            <select class="form-select" name="sakit_kepala">
                                <option value="">-- Pilih --</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </div>

                        <div class="col-sm-2 col-form-label"><strong>Trauma Kepala di Masa Lalu</strong></div>
                        <div class="col-sm-4">
                            <select class="form-select" name="trauma_kepala_masa_lalu">
                                <option value="">-- Pilih --</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </div>
                    </div>

                    <!-- Gatal Kulit Kepala & Kulit/Rambut Bersih -->
                    <div class="row mb-3 align-items-center">
                        <div class="col-sm-2 col-form-label"><strong>Gatal pada Kulit Kepala</strong></div>
                        <div class="col-sm-4">
                            <select class="form-select" name="gatal_kulit_kepala">
                                <option value="">-- Pilih --</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </div>

                        <div class="col-sm-2 col-form-label"><strong>Kulit / Rambut Bersih</strong></div>
                        <div class="col-sm-4">
                            <select class="form-select" name="kulit_rambut_bersih">
                                <option value="">-- Pilih --</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </div>
                    </div>

                    <!-- Rambut Rontok -->
                    <div class="row mb-3 align-items-center">
                        <div class="col-sm-2 col-form-label"><strong>Rambut Rontok</strong></div>
                        <div class="col-sm-4">
                            <select class="form-select" name="rambut_rontok">
                                <option value="">-- Pilih --</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </div>
                    </div>

                    <!-- 2. Mata -->
                    <div class="row mb-2">
                        <label class="col-sm-12 col-form-label text-primary">
                            <strong>2. Mata</strong>
                        </label>
                    </div>

                    <!-- Penurunan Penglihatan & Kabur -->
                    <div class="row mb-3 align-items-center">
                        <div class="col-sm-2 col-form-label"><strong>Penurunan Penglihatan</strong></div>
                        <div class="col-sm-4">
                            <select class="form-select" name="penurunan_penglihatan">
                                <option value="">-- Pilih --</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </div>

                        <div class="col-sm-2 col-form-label"><strong>Penglihatan Kabur</strong></div>
                        <div class="col-sm-4">
                            <select class="form-select" name="penglihatan_kabur">
                                <option value="">-- Pilih --</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </div>
                    </div>

                    <!-- Kekeruhan Lensa & Kacamata -->
                    <div class="row mb-3 align-items-center">
                        <div class="col-sm-2 col-form-label"><strong>Kekeruhan Lensa</strong></div>
                        <div class="col-sm-4">
                            <select class="form-select" name="kekeruhan_lensa">
                                <option value="">-- Pilih --</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </div>

                        <div class="col-sm-2 col-form-label"><strong>Kacamata / Lensa Kontak</strong></div>
                        <div class="col-sm-4">
                            <select class="form-select" name="kacamata_lensa_kontak">
                                <option value="">-- Pilih --</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </div>
                    </div>

                    <!-- Nyeri & Pruritus -->
                    <div class="row mb-3 align-items-center">
                        <div class="col-sm-2 col-form-label"><strong>Nyeri Mata</strong></div>
                        <div class="col-sm-4">
                            <select class="form-select" name="nyeri_mata">
                                <option value="">-- Pilih --</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </div>

                        <div class="col-sm-2 col-form-label"><strong>Pruritus (Gatal)</strong></div>
                        <div class="col-sm-4">
                            <select class="form-select" name="pruritus">
                                <option value="">-- Pilih --</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </div>
                    </div>

                    <!-- Bengkak & Floater -->
                    <div class="row mb-3 align-items-center">
                        <div class="col-sm-2 col-form-label"><strong>Bengkak Sekitar Mata</strong></div>
                        <div class="col-sm-4">
                            <select class="form-select" name="bengkak_mata">
                                <option value="">-- Pilih --</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </div>

                        <div class="col-sm-2 col-form-label"><strong>Floater</strong></div>
                        <div class="col-sm-4">
                            <select class="form-select" name="floater">
                                <option value="">-- Pilih --</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </div>
                    </div>

                    <!-- Diplopia -->
                    <div class="row mb-3 align-items-center">
                        <div class="col-sm-2 col-form-label"><strong>Diplopia</strong></div>
                        <div class="col-sm-4">
                            <select class="form-select" name="diplopia">
                                <option value="">-- Pilih --</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </div>
                    </div>

                    <!-- 3. Telinga -->
                    <div class="row mb-2 mt-4">
                        <label class="col-sm-12 col-form-label text-primary">
                            <strong>3. Telinga</strong>
                        </label>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <div class="col-sm-2 col-form-label"><strong>Penurunan Pendengaran</strong></div>
                        <div class="col-sm-4">
                            <select class="form-select" name="penurunan_pendengaran">
                                <option value="">-- Pilih --</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </div>

                        <div class="col-sm-2 col-form-label"><strong>Alat Bantu Pendengaran</strong></div>
                        <div class="col-sm-4">
                            <select class="form-select" name="alat_bantu_pendengaran">
                                <option value="">-- Pilih --</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </div>
                    </div>

                    <!-- 4. Hidung dan Sinus -->
                    <label class="col-sm-12 col-form-label text-primary">
                        <strong>4. Hidung dan Sinus</strong>
                    </label>

                    <div class="row mb-3 align-items-center">
                        <div class="col-sm-2 col-form-label"><strong>Rinorea</strong></div>
                        <div class="col-sm-4">
                            <select class="form-select" name="rinorea">
                                <option value="">-- Pilih --</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </div>

                        <div class="col-sm-2 col-form-label"><strong>Rabas</strong></div>
                        <div class="col-sm-4">
                            <select class="form-select" name="rabas_hidung">
                                <option value="">-- Pilih --</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <div class="col-sm-2 col-form-label"><strong>Riwayat Epitaksis</strong></div>
                        <div class="col-sm-4">
                            <select class="form-select" name="riwayat_epitaksis">
                                <option value="">-- Pilih --</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </div>

                        <div class="col-sm-2 col-form-label"><strong>Mendengkur Saat Tidur</strong></div>
                        <div class="col-sm-4">
                            <select class="form-select" name="mendengkur_tidur">
                                <option value="">-- Pilih --</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <div class="col-sm-2 col-form-label"><strong>Nyeri Sinus</strong></div>
                        <div class="col-sm-4">
                            <select class="form-select" name="nyeri_sinus">
                                <option value="">-- Pilih --</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </div>

                        <div class="col-sm-2 col-form-label"><strong>Pernapasan Cuping Hidung</strong></div>
                        <div class="col-sm-4">
                            <select class="form-select" name="pernapasan_cuping_hidung">
                                <option value="">-- Pilih --</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </div>
                    </div>

                    <!-- 5. Mulut dan Tenggorokan -->
                    <div class="row mb-2">
                        <label class="col-sm-12 col-form-label text-primary">
                            <strong>5. Mulut dan Tenggorokan</strong>
                        </label>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <div class="col-sm-2 col-form-label"><strong>Sakit Tenggorokan</strong></div>
                        <div class="col-sm-4">
                            <select class="form-select" name="sakit_tenggorokan">
                                <option value="">-- Pilih --</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </div>

                        <div class="col-sm-2 col-form-label"><strong>Lesi / Ulkus</strong></div>
                        <div class="col-sm-4">
                            <select class="form-select" name="lesi_ulkus">
                                <option value="">-- Pilih --</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <div class="col-sm-2 col-form-label"><strong>Suara Serak</strong></div>
                        <div class="col-sm-4">
                            <select class="form-select" name="suara_serak">
                                <option value="">-- Pilih --</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </div>

                        <div class="col-sm-2 col-form-label"><strong>Perubahan Suara</strong></div>
                        <div class="col-sm-4">
                            <select class="form-select" name="perubahan_suara">
                                <option value="">-- Pilih --</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <div class="col-sm-2 col-form-label"><strong>Kesulitan Menelan</strong></div>
                        <div class="col-sm-4">
                            <select class="form-select" name="kesulitan_menelan">
                                <option value="">-- Pilih --</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </div>

                        <div class="col-sm-2 col-form-label"><strong>Perdarahan Gusi</strong></div>
                        <div class="col-sm-4">
                            <select class="form-select" name="perdarahan_gusi">
                                <option value="">-- Pilih --</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <div class="col-sm-2 col-form-label"><strong>Karies</strong></div>
                        <div class="col-sm-4">
                            <select class="form-select" name="karies">
                                <option value="">-- Pilih --</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </div>

                        <div class="col-sm-2 col-form-label"><strong>Gigi Bersih</strong></div>
                        <div class="col-sm-4">
                            <select class="form-select" name="gigi_bersih">
                                <option value="">-- Pilih --</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <div class="col-sm-2 col-form-label"><strong>Gigi Palsu</strong></div>
                        <div class="col-sm-4">
                            <select class="form-select" name="gigi_palsu">
                                <option value="">-- Pilih --</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </div>

                        <div class="col-sm-2 col-form-label"><strong>Rutin Menggosok Gigi</strong></div>
                        <div class="col-sm-4">
                            <select class="form-select" name="rutin_menggosok_gigi">
                                <option value="">-- Pilih --</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </div>
                    </div>

                    <!-- 6. Leher -->
                    <div class="row mb-2">
                        <label class="col-sm-12 col-form-label text-primary">
                            <strong>6. Leher</strong>
                        </label>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <div class="col-sm-2 col-form-label"><strong>Kekakuan Leher</strong></div>
                        <div class="col-sm-4">
                            <select class="form-select" name="kekakuan_leher">
                                <option value="">-- Pilih --</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </div>

                        <div class="col-sm-2 col-form-label"><strong>Nyeri Leher</strong></div>
                        <div class="col-sm-4">
                            <select class="form-select" name="nyeri_leher">
                                <option value="">-- Pilih --</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <div class="col-sm-2 col-form-label"><strong>Benjolan Leher</strong></div>
                        <div class="col-sm-4">
                            <select class="form-select" name="benjolan_leher">
                                <option value="">-- Pilih --</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </div>

                        <div class="col-sm-2 col-form-label"><strong>Keterbatasan Gerak</strong></div>
                        <div class="col-sm-4">
                            <select class="form-select" name="keterbatasan_gerak_leher">
                                <option value="">-- Pilih --</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </div>
                    </div>

                    <!-- 7. Pernapasan -->
                    <div class="row mb-2">
                        <label class="col-sm-12 col-form-label text-primary">
                            <strong>7. Pernapasan</strong>
                        </label>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <div class="col-sm-2 col-form-label"><strong>Batuk</strong></div>
                        <div class="col-sm-4">
                            <select class="form-select" name="batuk">
                                <option value="">-- Pilih --</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </div>

                        <div class="col-sm-2 col-form-label"><strong>Sesak Napas</strong></div>
                        <div class="col-sm-4">
                            <select class="form-select" name="sesak_napas">
                                <option value="">-- Pilih --</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <div class="col-sm-2 col-form-label"><strong>Hemoptomisis</strong></div>
                        <div class="col-sm-4">
                            <select class="form-select" name="hemoptomisis">
                                <option value="">-- Pilih --</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </div>

                        <div class="col-sm-2 col-form-label"><strong>Sputum</strong></div>
                        <div class="col-sm-4">
                            <select class="form-select" name="sputum">
                                <option value="">-- Pilih --</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <div class="col-sm-2 col-form-label"><strong>Riwayat Asma</strong></div>
                        <div class="col-sm-4">
                            <select class="form-select" name="riwayat_asma">
                                <option value="">-- Pilih --</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </div>

                        <div class="col-sm-2 col-form-label"><strong>Kesulitan Menarik Napas</strong></div>
                        <div class="col-sm-4">
                            <select class="form-select" name="kesulitan_menarik_napas">
                                <option value="">-- Pilih --</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </div>
                    </div>

                    <!-- 8. Kardiovaskuler -->
                    <div class="row mb-2">
                        <label class="col-sm-12 col-form-label text-primary">
                            <strong>8. Kardiovaskuler</strong>
                        </label>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <div class="col-sm-2 col-form-label"><strong>Nyeri Dada</strong></div>
                        <div class="col-sm-4">
                            <select class="form-select" name="nyeri_dada">
                                <option value="">-- Pilih --</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </div>

                        <div class="col-sm-2 col-form-label"><strong>Palpitasi</strong></div>
                        <div class="col-sm-4">
                            <select class="form-select" name="palpitasi">
                                <option value="">-- Pilih --</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <div class="col-sm-2 col-form-label"><strong>Dispneu Nocturnal</strong></div>
                        <div class="col-sm-4">
                            <select class="form-select" name="dispneu_nocturnal">
                                <option value="">-- Pilih --</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </div>

                        <div class="col-sm-2 col-form-label"><strong>Ortopneu</strong></div>
                        <div class="col-sm-4">
                            <select class="form-select" name="ortopneu">
                                <option value="">-- Pilih --</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <div class="col-sm-2 col-form-label"><strong>Murmur</strong></div>
                        <div class="col-sm-4">
                            <select class="form-select" name="murmur">
                                <option value="">-- Pilih --</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </div>

                        <div class="col-sm-2 col-form-label"><strong>Edema</strong></div>
                        <div class="col-sm-4">
                            <select class="form-select" name="edema">
                                <option value="">-- Pilih --</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <div class="col-sm-2 col-form-label"><strong>Parestesia</strong></div>
                        <div class="col-sm-4">
                            <select class="form-select" name="parestesia">
                                <option value="">-- Pilih --</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </div>

                        <div class="col-sm-2 col-form-label"><strong>Riwayat Infark</strong></div>
                        <div class="col-sm-4">
                            <select class="form-select" name="riwayat_infark">
                                <option value="">-- Pilih --</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </div>
                    </div>

                    <!-- 9. Gastrointestinal -->
                    <div class="row mb-2">
                        <label class="col-sm-12 col-form-label text-primary">
                            <strong>9. Gastrointestinal</strong>
                        </label>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <div class="col-sm-2 col-form-label"><strong>Disfagia</strong></div>
                        <div class="col-sm-4">
                            <select class="form-select" name="disfagia">
                                <option value="">-- Pilih --</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </div>

                        <div class="col-sm-2 col-form-label"><strong>Tidak Dapat Mengunyah</strong></div>
                        <div class="col-sm-4">
                            <select class="form-select" name="tidak_dapat_mengunyah">
                                <option value="">-- Pilih --</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <div class="col-sm-2 col-form-label"><strong>Nyeri Uluhati</strong></div>
                        <div class="col-sm-4">
                            <select class="form-select" name="nyeri_uluhati">
                                <option value="">-- Pilih --</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </div>

                        <div class="col-sm-2 col-form-label"><strong>Mual</strong></div>
                        <div class="col-sm-4">
                            <select class="form-select" name="mual">
                                <option value="">-- Pilih --</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <div class="col-sm-2 col-form-label"><strong>Muntah</strong></div>
                        <div class="col-sm-4">
                            <select class="form-select" name="muntah">
                                <option value="">-- Pilih --</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </div>

                        <div class="col-sm-2 col-form-label"><strong>Hematemesis</strong></div>
                        <div class="col-sm-4">
                            <select class="form-select" name="hematemesis">
                                <option value="">-- Pilih --</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <div class="col-sm-2 col-form-label"><strong>Penurunan Selera Makan</strong></div>
                        <div class="col-sm-4">
                            <select class="form-select" name="penurunan_selera_makan">
                                <option value="">-- Pilih --</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </div>

                        <div class="col-sm-2 col-form-label"><strong>Ikterik</strong></div>
                        <div class="col-sm-4">
                            <select class="form-select" name="ikterik">
                                <option value="">-- Pilih --</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </div>
                    </div>

                    <!-- 10. Perkemihan -->
                    <div class="row mb-2">
                        <label class="col-sm-12 col-form-label text-primary">
                            <strong>10. Perkemihan</strong>
                        </label>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <div class="col-sm-2 col-form-label"><strong>Disuria</strong></div>
                        <div class="col-sm-4">
                            <select class="form-select" name="disuria">
                                <option value="">-- Pilih --</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </div>

                        <div class="col-sm-2 col-form-label"><strong>Frekuensi</strong></div>
                        <div class="col-sm-4">
                            <select class="form-select" name="frekuensi">
                                <option value="">-- Pilih --</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <div class="col-sm-2 col-form-label"><strong>Menetes</strong></div>
                        <div class="col-sm-4">
                            <select class="form-select" name="menetes">
                                <option value="">-- Pilih --</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </div>

                        <div class="col-sm-2 col-form-label"><strong>Hematuria</strong></div>
                        <div class="col-sm-4">
                            <select class="form-select" name="hematuria">
                                <option value="">-- Pilih --</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <div class="col-sm-2 col-form-label"><strong>Poliuria</strong></div>
                        <div class="col-sm-4">
                            <select class="form-select" name="poliuria">
                                <option value="">-- Pilih --</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </div>

                        <div class="col-sm-2 col-form-label"><strong>Oliguria</strong></div>
                        <div class="col-sm-4">
                            <select class="form-select" name="oliguria">
                                <option value="">-- Pilih --</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <div class="col-sm-2 col-form-label"><strong>Riwayat Batu Perkemihan</strong></div>
                        <div class="col-sm-4">
                            <select class="form-select" name="riwayat_batu_perkemihan">
                                <option value="">-- Pilih --</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </div>

                        <div class="col-sm-2 col-form-label"><strong>Nokturia</strong></div>
                        <div class="col-sm-4">
                            <select class="form-select" name="nokturia">
                                <option value="">-- Pilih --</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <div class="col-sm-2 col-form-label"><strong>Inkontinensia Uri</strong></div>
                        <div class="col-sm-4">
                            <select class="form-select" name="inkontinensia_uri">
                                <option value="">-- Pilih --</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </div>

                        <div class="col-sm-2 col-form-label"><strong>Riwayat Pembesaran Prostat</strong></div>
                        <div class="col-sm-4">
                            <select class="form-select" name="riwayat_pembesaran_prostat">
                                <option value="">-- Pilih --</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </div>
                    </div>

                    <!-- 11. Muskuloskeletal -->
                    <div class="row mb-2">
                        <label class="col-sm-12 col-form-label text-primary">
                            <strong>11. Muskuloskeletal</strong>
                        </label>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <div class="col-sm-2 col-form-label"><strong>Nyeri Sendi</strong></div>
                        <div class="col-sm-4">
                            <select class="form-select" name="nyeri_sendi">
                                <option value="">-- Pilih --</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </div>

                        <div class="col-sm-2 col-form-label"><strong>Kekakuan Sendi</strong></div>
                        <div class="col-sm-4">
                            <select class="form-select" name="kekakuan_sendi">
                                <option value="">-- Pilih --</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <div class="col-sm-2 col-form-label"><strong>Pembengkakan Sendi</strong></div>
                        <div class="col-sm-4">
                            <select class="form-select" name="pembengkakan_sendi">
                                <option value="">-- Pilih --</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </div>

                        <div class="col-sm-2 col-form-label"><strong>Spasme</strong></div>
                        <div class="col-sm-4">
                            <select class="form-select" name="spasme">
                                <option value="">-- Pilih --</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <div class="col-sm-2 col-form-label"><strong>Kram Otot</strong></div>
                        <div class="col-sm-4">
                            <select class="form-select" name="kram_otot">
                                <option value="">-- Pilih --</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </div>

                        <div class="col-sm-2 col-form-label"><strong>Deformitas</strong></div>
                        <div class="col-sm-4">
                            <select class="form-select" name="deformitas">
                                <option value="">-- Pilih --</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <div class="col-sm-2 col-form-label"><strong>Penurunan Kekuatan Otot</strong></div>
                        <div class="col-sm-4">
                            <select class="form-select" name="penurunan_kekuatan_otot">
                                <option value="">-- Pilih --</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </div>

                        <div class="col-sm-2 col-form-label"><strong>Kelemahan</strong></div>
                        <div class="col-sm-4">
                            <select class="form-select" name="kelemahan">
                                <option value="">-- Pilih --</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <div class="col-sm-2 col-form-label"><strong>Nyeri Punggung Belakang</strong></div>
                        <div class="col-sm-4">
                            <select class="form-select" name="nyeri_punggung_belakang">
                                <option value="">-- Pilih --</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </div>

                        <div class="col-sm-2 col-form-label"><strong>Nyeri Pinggang</strong></div>
                        <div class="col-sm-4">
                            <select class="form-select" name="nyeri_pinggang">
                                <option value="">-- Pilih --</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <div class="col-sm-2 col-form-label"><strong>Alat Bantuan Berjalan</strong></div>
                        <div class="col-sm-4">
                            <select class="form-select" name="alat_bantuan_berjalan">
                                <option value="">-- Pilih --</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </div>

                        <div class="col-sm-2 col-form-label"><strong>Perubahan Cara Berjalan</strong></div>
                        <div class="col-sm-4">
                            <select class="form-select" name="perubahan_cara_berjalan">
                                <option value="">-- Pilih --</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <div class="col-sm-2 col-form-label"><strong>Tremor</strong></div>
                        <div class="col-sm-4">
                            <select class="form-select" name="tremor">
                                <option value="">-- Pilih --</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </div>

                        <div class="col-sm-2 col-form-label"><strong>Atropi Otot</strong></div>
                        <div class="col-sm-4">
                            <select class="form-select" name="atropi_otot">
                                <option value="">-- Pilih --</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </div>
                    </div>

                    <!-- 12. Endokrin -->
                    <div class="row mb-2">
                        <label class="col-sm-12 col-form-label text-primary">
                            <strong>12. Endokrin</strong>
                        </label>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <div class="col-sm-2 col-form-label"><strong>Intoleransi Panas</strong></div>
                        <div class="col-sm-4">
                            <select class="form-select" name="intoleransi_panas">
                                <option value="">-- Pilih --</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </div>

                        <div class="col-sm-2 col-form-label"><strong>Intoleransi Dingin</strong></div>
                        <div class="col-sm-4">
                            <select class="form-select" name="intoleransi_dingin">
                                <option value="">-- Pilih --</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <div class="col-sm-2 col-form-label"><strong>Goiter</strong></div>
                        <div class="col-sm-4">
                            <select class="form-select" name="goiter">
                                <option value="">-- Pilih --</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </div>

                        <div class="col-sm-2 col-form-label"><strong>Poli Fagi</strong></div>
                        <div class="col-sm-4">
                            <select class="form-select" name="poli_fagi">
                                <option value="">-- Pilih --</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <div class="col-sm-2 col-form-label"><strong>Poli Uri</strong></div>
                        <div class="col-sm-4">
                            <select class="form-select" name="poli_uri">
                                <option value="">-- Pilih --</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </div>

                        <div class="col-sm-2 col-form-label"><strong>Poli Dipsi</strong></div>
                        <div class="col-sm-4">
                            <select class="form-select" name="poli_dipsi">
                                <option value="">-- Pilih --</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <div class="col-sm-2 col-form-label"><strong>Perubahan Rambut</strong></div>
                        <div class="col-sm-4">
                            <select class="form-select" name="perubahan_rambut">
                                <option value="">-- Pilih --</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </div>

                        <div class="col-sm-2 col-form-label"><strong>Pigmentasi Kulit</strong></div>
                        <div class="col-sm-4">
                            <select class="form-select" name="pigmentasi_kulit">
                                <option value="">-- Pilih --</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </div>
                    </div>

                    <!-- 14. Integumen -->
                    <div class="row mb-2">
                        <label class="col-sm-12 col-form-label text-primary">
                            <strong>14. Integumen</strong>
                        </label>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <div class="col-sm-2 col-form-label"><strong>Kulit Kering</strong></div>
                        <div class="col-sm-4">
                            <select class="form-select" name="kulit_kering">
                                <option value="">-- Pilih --</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </div>

                        <div class="col-sm-2 col-form-label"><strong>Kulit Keriput / Mengerut</strong></div>
                        <div class="col-sm-4">
                            <select class="form-select" name="kulit_keriput">
                                <option value="">-- Pilih --</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <div class="col-sm-2 col-form-label"><strong>Menjaga Kebersihan Kulit</strong></div>
                        <div class="col-sm-4">
                            <select class="form-select" name="menjaga_kebersihan_kulit">
                                <option value="">-- Pilih --</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </div>

                        <div class="col-sm-2 col-form-label"><strong>Penurunan Lemak Bawah Kulit</strong></div>
                        <div class="col-sm-4">
                            <select class="form-select" name="penurunan_lemak_bawah_kulit">
                                <option value="">-- Pilih --</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </div>
                    </div>

                    <!-- Tombol Submit -->
                    <div class="row mb-3">
                        <div class="col-sm-9 offset-sm-2 text-end">
                            <button type="submit" class="btn btn-primary">Lanjutkan</button>
                        </div>
                    </div>
                </form><!-- End General Form Elements -->

            </div>
        </div>
        </div>
    </section>
</main>

<?php include "footer_gerontik.php"; ?>