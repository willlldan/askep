<?php
$form_id       = 22;
$section_name  = 'pengkajian_lanjutan';
$section_label = 'Pengkajian Lanjutan';
include dirname(__DIR__, 2) . '/partials/init_section.php';

// =============================================
// HANDLE POST - MAHASISWA SIMPAN DATA
// =============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $level === 'Mahasiswa') {

    if (isLocked($submission)) {
        redirectWithMessage($_SERVER['REQUEST_URI'], 'error', 'Data tidak dapat diubah karena sedang dalam proses review.');
    }


   $data = [
       

        // C. SECONDARY SURVEY
        'riwayatpenyakitsaatini'        => $_POST['riwayatpenyakitsaatini']        ?? '',
        'alergi'                        => $_POST['alergi']                        ?? '',
        'medikasi'                      => $_POST['medikasi']                      ?? '',
        'riwayatpenyakitsebelumnya'     => $_POST['riwayatpenyakitsebelumnya']     ?? '',
        'makanminumterakhir'            => $_POST['makanminumterakhir']            ?? '',
        'even'                          => $_POST['even']                          ?? '',

        // Tanda-tanda Vital
        'tekanandarah'                  => $_POST['tekanandarah']                  ?? '',
        'nadi'                          => $_POST['nadi']                          ?? '',
        'suhu'                          => $_POST['suhu']                          ?? '',
        'rr'                            => $_POST['rr']                            ?? '',

        // Pemeriksaan Fisik: Kepala
        'pendarahankepala'              => $_POST['pendarahankepala']              ?? '',
        'depresitulangkepala'           => $_POST['depresitulangkepala']           ?? '',
        'laserasikepala'                => $_POST['laserasikepala']                ?? '',
        'echymosismemar'                => $_POST['echymosismemar']                ?? '',
        'nyeritekankepala'              => $_POST['nyeritekankepala']              ?? '',
        'keluhanlainkepala'             => $_POST['keluhanlainkepala']             ?? '',

        // Pemeriksaan Fisik: Mata
        'racooneyes'                    => $_POST['racooneyes']                    ?? '',
        'pendarahanmata'                => $_POST['pendarahanmata']                ?? '',
        'rupturrobek'                   => $_POST['rupturrobek']                   ?? '',
        'konjungtiva'                   => $_POST['konjungtiva']                   ?? '',
        'sklera'                        => $_POST['sklera']                        ?? '',
        'responpupilmata'               => $_POST['responpupilmata']               ?? '',
        'keluhanlainmata'               => $_POST['keluhanlainmata']               ?? '',

        // Pemeriksaan Fisik: Telinga
        'cairan'                        => $_POST['cairan']                        ?? '',
        'jikaadawarna'                  => $_POST['jikaadawarna']                  ?? '',
        'lecet'                         => $_POST['lecet']                         ?? '',
        'leserasi'                      => $_POST['leserasi']                      ?? '',
        'bendaasing'                    => $_POST['bendaasing']                    ?? '',
        'berupa'                        => $_POST['berupa']                        ?? '',
        'keluhanlaintelinga'            => $_POST['keluhanlaintelinga']            ?? '',

        // Pemeriksaan Fisik: Hidung
        'adacairan'                     => $_POST['adacairan']                     ?? '',
        'warna'                         => $_POST['warna']                         ?? '',
        'lecethidung'                   => $_POST['lecethidung']                   ?? '',
        'kemerahan'                     => $_POST['kemerahan']                     ?? '',
        'leserasihidung'                => $_POST['leserasihidung']                ?? '',
        'bendaasinghidung'              => $_POST['bendaasinghidung']              ?? '',
        'berupahidung'                  => $_POST['berupahidung']                  ?? '',
        'keluhanlainhidung'             => $_POST['keluhanlainhidung']             ?? '',

        // Pemeriksaan Fisik: Leher
        'deviasitrakea'                 => $_POST['deviasitrakea']                 ?? '',
        'distensivenajugularis'         => $_POST['distensivenajugularis']         ?? '',
        'bengkak'                       => $_POST['bengkak']                       ?? '',
        'kebiruanmemar'                 => $_POST['kebiruanmemar']                 ?? '',
        'nyeritekanleher'               => $_POST['nyeritekanleher']               ?? '',
        'krepitasi'                     => $_POST['krepitasi']                     ?? '',
        'keluhanlainleher'             => $_POST['keluhanlainleher']             ?? '',

        // Pemeriksaan Fisik: Dada/Paru
        'bentukdada'                    => $_POST['bentukdada']                    ?? '',
        'laserasijejas'                 => $_POST['laserasijejas']                 ?? '',
        'ukuranluka'                    => $_POST['ukuranluka']                    ?? '',
        'lokasi'                        => $_POST['lokasi']                        ?? '',
        'pendarahandada'                => $_POST['pendarahandada']                ?? '',
        'jikaadaberapabanyak'           => $_POST['jikaadaberapabanyak']           ?? '',
        'rr2'                            => $_POST['rr2']                            ?? '',
        'iramanapas'                    => $_POST['iramanapas']                    ?? '',
        'ototdada'                      => $_POST['ototdada']                      ?? '',
        'bunyijantung'                  => $_POST['bunyijantung']                  ?? '',
        'nyeridada'                     => $_POST['nyeridada']                     ?? '',
        'jikaadanyerijelaskan'          => $_POST['jikaadanyerijelaskan']          ?? '',
        'keluhanlaindada'               => $_POST['keluhanlaindada']               ?? '',

        // Pemeriksaan Fisik: Abdomen
        'dindingabdomen'                => $_POST['dindingabdomen']                ?? '',
        'pendarahanabdomen'             => $_POST['pendarahanabdomen']             ?? '',
        'jikaadaberapabanyakabdomen'    => $_POST['jikaadaberapabanyakabdomen']    ?? '',
        'bengkakabdomen'                => $_POST['bengkakabdomen']                ?? '',
        'leserasiabdomen'               => $_POST['leserasiabdomen']               ?? '',
        'distensiabdomen'               => $_POST['distensiabdomen']               ?? '',
        'bisingusus'                    => $_POST['bisingusus']                    ?? '',
        'jikadaaberapakali'             => $_POST['jikadaaberapakali']             ?? '',
        'nyeritekanabdomen'             => $_POST['nyeritekanabdomen']             ?? '',
        'keluhanlainabdomen'            => $_POST['keluhanlainabdomen']            ?? '',

        // Pemeriksaan Fisik: Ekstremitas Atas dan Bawah
        'terababenjolankeras'           => $_POST['terababenjolankeras']           ?? '',
        'jikaadaukuran'                 => $_POST['jikaadaukuran']                 ?? '',
        'lokasibenjolan'                => $_POST['lokasibenjolan']                ?? '',
        'pendarahanekstremitas'         => $_POST['pendarahanekstremitas']         ?? '',
        'lokasiekstremitas'             => $_POST['lokasiekstremitas']             ?? '',
        'jumlah'                        => $_POST['jumlah']                        ?? '',
        'edemaekstremitas'              => $_POST['edemaekstremitas']              ?? '',
        'nyeritekanekstremitas'         => $_POST['nyeritekanekstremitas']         ?? '',
        'fraktur'                       => $_POST['fraktur']                       ?? '',
        'lokasifraktur'                 => $_POST['lokasifraktur']                 ?? '',
        'kekakuan'                      => $_POST['kekakuan']                      ?? '',
        'keterbatasangerak'             => $_POST['keterbatasangerak']             ?? '',
        'ekstremitasatas'               => $_POST['ekstremitasatas']               ?? '',
        'ekstremitasbawah'              => $_POST['ekstremitasbawah']              ?? '',
        'keluhanlainekstremitas'        => $_POST['keluhanlainekstremitas']        ?? '',


        // Pemeriksaan Fisik: Punggung
        'terdapatluka'                  => $_POST['terdapatluka']                  ?? '',
        'ukuranluka2'                    => $_POST['ukuranluka2']                    ?? '',
        'decubituspunggung'             => $_POST['decubituspunggung']             ?? '',
        'ukurandecubituspunggung'       => $_POST['ukurandecubituspunggung']       ?? '',
        'echymosislebampunggung'        => $_POST['echymosislebampunggung']        ?? '',
        'gatalgatal'                    => $_POST['gatalgatal']                    ?? '',
        'keluhanlainpunggung'           => $_POST['keluhanlainpunggung']           ?? '',

        // Pemeriksaan Fisik: Kulit
        'kulit'                         => $_POST['kulit']                         ?? '',
        'turgor'                        => $_POST['turgor']                        ?? '',
        'keadaan'                       => $_POST['keadaan']                       ?? '',
        'edemakulit'                    => $_POST['edemakulit']                    ?? '',
        'lokasiedemakulit'              => $_POST['lokasiedemakulit']              ?? '',
        'lukakulit'                     => $_POST['lukakulit']                     ?? '',
        'lokasilukakulit'               => $_POST['lokasilukakulit']               ?? '',
        'karakteristikluka'             => $_POST['karakteristikluka']             ?? '',
        'keluhanlainkulit'              => $_POST['keluhanlainkulit']              ?? '',

        // Pemeriksaan Fisik: Genitalia
        'radanggenitalia'               => $_POST['radanggenitalia']               ?? '',
        'pembengkakanskrotum'           => $_POST['pembengkakanskrotum']           ?? '',
        'lesi'                          => $_POST['lesi']                          ?? '',
        'keluhanlaingenitalia'          => $_POST['keluhanlaingenitalia']          ?? '',
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

    <?php include "gadar/igd/tab.php"; ?>

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
        <div class="card">
            <div class="card-body">

          <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">
                <h5 class="card-title mb-1"><strong>C. SECONDARY SURVEY</strong></h5>                
    
                <div class="row mb-3">
                    <label for="riwayatpenyakitsaatini" class="col-sm-2 col-form-label"><strong>Riwayat Penyakit Saat Ini</strong></label>
                    <div class="col-sm-10">
                        <textarea name="riwayatpenyakitsaatini" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars(val('riwayatpenyakitsaatini', $existing_data)) ?></textarea>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="alergi" class="col-sm-2 col-form-label"><strong>Alergi</strong></label>
                    <div class="col-sm-10">
                        <textarea name="alergi" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars(val('alergi', $existing_data)) ?></textarea>
                    </div>
                </div>    
                      
                <div class="row mb-3">
                    <label for="medikasi" class="col-sm-2 col-form-label"><strong>Medikasi</strong></label>
                    <div class="col-sm-10">
                        <textarea name="medikasi" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars(val('medikasi', $existing_data)) ?></textarea>
                    </div>
                </div>   
                    
                <div class="row mb-3">
                    <label for="riwayatpenyakitsebelumnya" class="col-sm-2 col-form-label"><strong>Riwayat Penyakit Sebelumnya</strong></label>
                    <div class="col-sm-10">
                        <textarea name="riwayatpenyakitsebelumnya" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars(val('riwayatpenyakitsebelumnya', $existing_data)) ?></textarea>
                    </div>
                </div> 
                    
                <div class="row mb-3">
                    <label for="makanminumterakhir" class="col-sm-2 col-form-label"><strong>Makan Minum Terakhir</strong></label>
                    <div class="col-sm-10">
                        <textarea name="makanminumterakhir" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars(val('makanminumterakhir', $existing_data)) ?></textarea>
                    </div>
                </div>  
                    
                <div class="row mb-3">
                    <label for="even" class="col-sm-2 col-form-label"><strong>Even/Peristiwa Penyebab</strong></label>
                    <div class="col-sm-10">
                        <textarea name="even" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars(val('even', $existing_data)) ?></textarea>
                    </div>
                </div>  
                    
                <div class="row mb-3">
                    <label class="col-sm-10 col-form-label"><strong>Tanda-tanda Vital</strong></label>    
                </div>

                <div class="row mb-3 align-items-center">
                    <label class="col-sm-2 col-form-label"><strong>Tekanan Darah</strong></label>
                    <div class="col-sm-3">
                        <div class="input-group">
                            <input type="text" class="form-control" name="tekanandarah" value="<?= htmlspecialchars(val('tekanandarah', $existing_data)) ?>" <?= $ro ?>>
                            <span class="input-group-text">mmHg</span>
                        </div>    
                    </div>
                                
                    <label class="col-sm-2 col-form-label offset-sm-1"><strong>Nadi</strong></label>
                    <div class="col-sm-3">
                        <div class="input-group">
                            <input type="text" class="form-control" name="nadi" value="<?= htmlspecialchars(val('nadi', $existing_data)) ?>" <?= $ro ?>>
                            <span class="input-group-text">x/menit</span>
                        </div> 
                    </div>
                </div>
              
                <div class="row mb-3 align-items-center">
                    <label class="col-sm-2 col-form-label"><strong>Suhu</strong></label>
                    <div class="col-sm-3">
                        <div class="input-group">
                            <input type="text" class="form-control" name="suhu" value="<?= htmlspecialchars(val('suhu', $existing_data)) ?>" <?= $ro ?>>
                            <span class="input-group-text">°C</span>
                        </div>    
                    </div>

                    <label class="col-sm-2 col-form-label offset-sm-1"><strong>RR</strong></label>
                    <div class="col-sm-3">
                        <div class="input-group">
                            <input type="text" class="form-control" name="rr" value="<?= htmlspecialchars(val('rr', $existing_data)) ?>" <?= $ro ?>>
                            <span class="input-group-text">x/menit</span>
                        </div>
                    </div>
                </div>

                <div class="row mb-3 mt-4">
                    <div class="col-sm-12 text-primary"><strong>Pemeriksaan Fisik</strong></div>   
                    <div class="col-sm-12 text-primary mt-1"><strong>Kepala</strong></div>   
                </div>
 
                <div class="row mb-2">
                    <div class="col-sm-2"><strong>Pendarahan</strong></div>    
                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="pendarahankepala" value="ya" <?= val('pendarahankepala', $existing_data) == 'ya' ? 'checked' : '' ?> <?= $ro ?>>
                            <label class="form-check-label">Ya</label>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="pendarahankepala" value="tidak" <?= val('pendarahankepala', $existing_data) == 'tidak' ? 'checked' : '' ?> <?= $ro ?>>
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-sm-2"><strong>Depresi Tulang Kepala</strong></div>    
                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="depresitulangkepala" value="ya" <?= val('depresitulangkepala', $existing_data) == 'ya' ? 'checked' : '' ?> <?= $ro ?>>
                            <label class="form-check-label">Ya</label>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="depresitulangkepala" value="tidak" <?= val('depresitulangkepala', $existing_data) == 'tidak' ? 'checked' : '' ?> <?= $ro ?>>
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-sm-2"><strong>Laserasi</strong></div>    
                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="laserasikepala" value="ya" <?= val('laserasikepala', $existing_data) == 'ya' ? 'checked' : '' ?> <?= $ro ?>>
                            <label class="form-check-label">Ya</label>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="laserasikepala" value="tidak" <?= val('laserasikepala', $existing_data) == 'tidak' ? 'checked' : '' ?> <?= $ro ?>>
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-sm-2"><strong>Echymosis/Memar</strong></div>    
                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="echymosismemar" value="ya" <?= val('echymosismemar', $existing_data) == 'ya' ? 'checked' : '' ?> <?= $ro ?>>
                            <label class="form-check-label">Ya</label>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="echymosismemar" value="tidak" <?= val('echymosismemar', $existing_data) == 'tidak' ? 'checked' : '' ?> <?= $ro ?>>
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-sm-2"><strong>Nyeri Tekan</strong></div>    
                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="nyeritekankepala" value="ya" <?= val('nyeritekankepala', $existing_data) == 'ya' ? 'checked' : '' ?> <?= $ro ?>>
                            <label class="form-check-label">Ya</label>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="nyeritekankepala" value="tidak" <?= val('nyeritekankepala', $existing_data) == 'tidak' ? 'checked' : '' ?> <?= $ro ?>>
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="keluhanlainkepala" class="col-sm-2 col-form-label"><strong>Keluhan Lain</strong></label>
                    <div class="col-sm-10">
                        <textarea name="keluhanlainkepala" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars(val('keluhanlainkepala', $existing_data)) ?></textarea>
                    </div>
                </div>

                <div class="row mb-3 mt-4">
                    <label class="col-sm-12 col-form-label text-primary"><strong>Mata</strong></label>
                </div>   

                <div class="row mb-2">
                    <div class="col-sm-2"><strong>Racoon Eyes</strong></div>    
                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="racooneyes" value="ya" <?= val('racooneyes', $existing_data) == 'ya' ? 'checked' : '' ?> <?= $ro ?>>
                            <label class="form-check-label">Ya</label>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="racooneyes" value="tidak" <?= val('racooneyes', $existing_data) == 'tidak' ? 'checked' : '' ?> <?= $ro ?>>
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-sm-2"><strong>Pendarahan</strong></div>    
                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="pendarahanmata" value="ya" <?= val('pendarahanmata', $existing_data) == 'ya' ? 'checked' : '' ?> <?= $ro ?>>
                            <label class="form-check-label">Ya</label>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="penarahanmata" value="tidak" <?= val('penarahanmata', $existing_data) == 'tidak' ? 'checked' : '' ?> <?= $ro ?>>
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-sm-2"><strong>Ruptur/Robek</strong></div>    
                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="rupturrobek" value="ya" <?= val('rupturrobek', $existing_data) == 'ya' ? 'checked' : '' ?> <?= $ro ?>>
                            <label class="form-check-label">Ya</label>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="rupturrobek" value="tidak" <?= val('rupturrobek', $existing_data) == 'tidak' ? 'checked' : '' ?> <?= $ro ?>>
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-sm-2"><strong>Konjungtiva</strong></div>    
                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="konjungtiva" value="anemis" <?= val('konjungtiva', $existing_data) == 'anemis' ? 'checked' : '' ?> <?= $ro ?>>
                            <label class="form-check-label">Anemis</label>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="konjungtiva" value="ananemis" <?= val('konjungtiva', $existing_data) == 'ananemis' ? 'checked' : '' ?> <?= $ro ?>>
                            <label class="form-check-label">Ananemis</label>
                        </div>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-sm-2"><strong>Sklera</strong></div>    
                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="sklera" value="ikterik" <?= val('sklera', $existing_data) == 'ikterik' ? 'checked' : '' ?> <?= $ro ?>>
                            <label class="form-check-label">Ikterik</label>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="sklera" value="anikterik" <?= val('sklera', $existing_data) == 'anikterik' ? 'checked' : '' ?> <?= $ro ?>>
                            <label class="form-check-label">Anikterik</label>
                        </div>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-sm-2"><strong>Respon Pupil</strong></div>    
                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="responpupilmata" value="isokor" <?= val('responpupilmata', $existing_data) == 'isokor' ? 'checked' : '' ?> <?= $ro ?>>
                            <label class="form-check-label">Isokor</label>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="responpupilmata" value="anisokor" <?= val('responpupilmata', $existing_data) == 'anisokor' ? 'checked' : '' ?> <?= $ro ?>>
                            <label class="form-check-label">Anisokor</label>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="responpupilmata" value="midriasis" <?= val('responpupilmata', $existing_data) == 'midriasis' ? 'checked' : '' ?> <?= $ro ?>>
                            <label class="form-check-label">Midriasis</label>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="keluhanlainmata" class="col-sm-2 col-form-label"><strong>Keluhan Lain</strong></label>
                    <div class="col-sm-10">
                        <textarea name="keluhanlainmata" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars(val('keluhanlainmata', $existing_data)) ?></textarea>
                    </div>
                </div>

                <div class="row mb-3 mt-4">
                    <label class="col-sm-12 col-form-label text-primary"><strong>Telinga</strong></label>
                </div>   

                <div class="row mb-2">
                    <div class="col-sm-2"><strong>Cairan</strong></div>  
                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="cairan" value="ada" <?= val('cairan', $existing_data) == 'ada' ? 'checked' : '' ?> <?= $ro ?>>
                            <label class="form-check-label">Ada</label>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="cairan" value="tidak" <?= val('cairan', $existing_data) == 'tidak' ? 'checked' : '' ?> <?= $ro ?>>
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>

                    <div class="col-sm-10 offset-sm-2">
                        <div class="row mt-2">
                            <div class="col-sm-12">
                                <label class="mb-1"><strong>Jika ada, warna</strong></label>
                                <input type="text" class="form-control" name="jikaadawarna" value="<?= htmlspecialchars(val('jikaadawarna', $existing_data)) ?>" <?= $ro ?>>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-sm-2"><strong>Lecet</strong></div>    
                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="lecet" value="ya" <?= val('lecet', $existing_data) == 'ya' ? 'checked' : '' ?> <?= $ro ?>>
                            <label class="form-check-label">Ya</label>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="lecet" value="tidak" <?= val('lecet', $existing_data) == 'tidak' ? 'checked' : '' ?> <?= $ro ?>>
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-sm-2"><strong>Leserasi</strong></div>    
                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="leserasi" value="ya" <?= val('leserasi', $existing_data) == 'ya' ? 'checked' : '' ?> <?= $ro ?>>
                            <label class="form-check-label">Ya</label>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="leserasi" value="tidak" <?= val('leserasi', $existing_data) == 'tidak' ? 'checked' : '' ?> <?= $ro ?>>
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-sm-2"><strong>Benda Asing</strong></div>  
                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="bendaasing" value="ada" <?= val('bendaasing', $existing_data) == 'ada' ? 'checked' : '' ?> <?= $ro ?>>
                            <label class="form-check-label">Ada</label>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="bendaasing" value="Tidak" <?= val('bendaasing', $existing_data) == 'Tidak' ? 'checked' : '' ?> <?= $ro ?>>
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>

                    <div class="col-sm-10 offset-sm-2">
                        <div class="row mt-2">
                            <div class="col-sm-12">
                                <label class="mb-1"><strong>Berupa</strong></label>
                                <input type="text" class="form-control" name="berupa" value="<?= htmlspecialchars(val('berupa', $existing_data)) ?>" <?= $ro ?>>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><strong>Keluhan Lain</strong></label>
                    <div class="col-sm-10">
                        <textarea name="keluhanlaintelinga" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars(val('keluhanlaintelinga', $existing_data)) ?></textarea>
                    </div>
                </div>

                <div class="row mb-3 mt-4">
                    <label class="col-sm-12 col-form-label text-primary"><strong>Hidung</strong></label>
                </div>   

                <div class="row mb-2">
                    <div class="col-sm-2"><strong>Ada Cairan</strong></div>  
                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="adacairan" value="ya" <?= val('adacairan', $existing_data) == 'ya' ? 'checked' : '' ?> <?= $ro ?>>
                            <label class="form-check-label">Ya</label>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="adacairan" value="tidak" <?= val('adacairan', $existing_data) == 'tidak' ? 'checked' : '' ?> <?= $ro ?>>
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>

                    <div class="col-sm-10 offset-sm-2">
                        <div class="row mt-2">
                            <div class="col-sm-12">
                                <label class="mb-1"><strong>Warna</strong></label>
                                <input type="text" class="form-control" name="warna" value="<?= htmlspecialchars(val('warna', $existing_data)) ?>" <?= $ro ?>>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-sm-2"><strong>Lecet</strong></div>    
                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="lecethidung" value="ya" <?= val('lecethidung', $existing_data) == 'ya' ? 'checked' : '' ?> <?= $ro ?>>
                            <label class="form-check-label">Ya</label>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="lecethidung" value="tidak" <?= val('lecethidung', $existing_data) == 'tidak' ? 'checked' : '' ?> <?= $ro ?>>
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-sm-2"><strong>Kemerahan</strong></div>    
                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="kemerahan" value="ya" <?= val('kemerahan', $existing_data) == 'ya' ? 'checked' : '' ?> <?= $ro ?>>
                            <label class="form-check-label">Ya</label>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="kemerahan" value="tidak" <?= val('kemerahan', $existing_data) == 'tidak' ? 'checked' : '' ?> <?= $ro ?>>
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-sm-2"><strong>Leserasi</strong></div>    
                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="leserasihidung" value="ya" <?= val('leserasihidung', $existing_data) == 'ya' ? 'checked' : '' ?> <?= $ro ?>>
                            <label class="form-check-label">Ya</label>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="leserasihidung" value="tidak" <?= val('leserasihidung', $existing_data) == 'tidak' ? 'checked' : '' ?> <?= $ro ?>>
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-sm-2"><strong>Benda Asing</strong></div>  
                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="bendaasinghidung" value="ada" <?= val('bendaasinghidung', $existing_data) == 'ada' ? 'checked' : '' ?> <?= $ro ?>>
                            <label class="form-check-label">Ada</label>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="bendaasinghidung" value="禮拜" <?= val('bendaasinghidung', $existing_data) == 'Tidak' ? 'checked' : '' ?> <?= $ro ?>>
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>

                    <div class="col-sm-10 offset-sm-2">
                        <div class="row mt-2">
                            <div class="col-sm-12">
                                <label class="mb-1"><strong>Berupa</strong></label>
                                <input type="text" class="form-control" name="berupahidung" value="<?= htmlspecialchars(val('berupahidung', $existing_data)) ?>" <?= $ro ?>>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><strong>Keluhan Lain</strong></label>
                    <div class="col-sm-10">
                        <textarea name="keluhanlainhidung" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars(val('keluhanlainhidung', $existing_data)) ?></textarea>
                    </div>
                </div>

                <div class="row mb-3 mt-4">
                    <label class="col-sm-12 col-form-label text-primary"><strong>Leher</strong></label>
                </div>   

                <div class="row mb-2">
                    <div class="col-sm-2"><strong>Deviasi Trakea</strong></div>    
                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="deviasitrakea" value="ya" <?= val('deviasitrakea', $existing_data) == 'ya' ? 'checked' : '' ?> <?= $ro ?>>
                            <label class="form-check-label">Ya</label>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="deviasitrakea" value="tidak" <?= val('deviasitrakea', $existing_data) == 'tidak' ? 'checked' : '' ?> <?= $ro ?>>
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-sm-2"><strong>Distensi Vena Jugularis</strong></div>    
                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="distensivenajugularis" value="ya" <?= val('distensivenajugularis', $existing_data) == 'ya' ? 'checked' : '' ?> <?= $ro ?>>
                            <label class="form-check-label">Ya</label>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="distensivenajugularis" value="tidak" <?= val('distensivenajugularis', $existing_data) == 'tidak' ? 'checked' : '' ?> <?= $ro ?>>
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-sm-2"><strong>Bengkak</strong></div>    
                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="bengkak" value="ya" <?= val('bengkak', $existing_data) == 'ya' ? 'checked' : '' ?> <?= $ro ?>>
                            <label class="form-check-label">Ya</label>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="bengkak" value="tidak" <?= val('bengkak', $existing_data) == 'tidak' ? 'checked' : '' ?> <?= $ro ?>>
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-sm-2"><strong>Kebiruan/Memar</strong></div>    
                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="kebiruanmemar" value="ya" <?= val('kebiruanmemar', $existing_data) == 'ya' ? 'checked' : '' ?> <?= $ro ?>>
                            <label class="form-check-label">Ya</label>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="kebiruanmemar" value="tidak" <?= val('kebiruanmemar', $existing_data) == 'tidak' ? 'checked' : '' ?> <?= $ro ?>>
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-sm-2"><strong>Nyeri Tekan</strong></div>    
                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="nyeritekanleher" value="ya" <?= val('nyeritekanleher', $existing_data) == 'ya' ? 'checked' : '' ?> <?= $ro ?>>
                            <label class="form-check-label">Ya</label>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="nyeritekanleher" value="tidak" <?= val('nyeritekanleher', $existing_data) == 'tidak' ? 'checked' : '' ?> <?= $ro ?>>
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-sm-2"><strong>Krepitasi</strong></div>    
                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="krepitasi" value="ya" <?= val('krepitasi', $existing_data) == 'ya' ? 'checked' : '' ?> <?= $ro ?>>
                            <label class="form-check-label">Ya</label>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="krepitasi" value="tidak" <?= val('krepitasi', $existing_data) == 'tidak' ? 'checked' : '' ?> <?= $ro ?>>
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>

           
                <!-- Bagian Keluhan Lain -->
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><strong>Keluhan Lain</strong></label>
                    
                <div class="col-sm-10">
                    <div class="row">  
                        
                <div class="col-sm-11">
                    <textarea name="keluhanlainleher" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" 
                    value="<?= val('keluhanlainleher', $existing_data) ?>" <?= $ro ?>></textarea>
                        
                        </div>

                        
                    </div> 
                </div>
            </div>
                    
           <div class="row mb-3">
                    <label class="col-sm-12 col-form-label text-primary">
                        <strong>Dada/Paru</strong>
                    </label>
                </div>
                
                <div class="row mb-2">
                    <div class="col-sm-2"><strong>Bentuk Dada</strong></div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="bentukdada" value="simetris" <?= val('bentukdada', $existing_data) == 'simetris' ? 'checked' : '' ?> <?= $ro ?>>
                            <label class="form-check-label">Simetris</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="bentukdada" value="asimetris" <?= val('bentukdada', $existing_data) == 'asimetris' ? 'checked' : '' ?> <?= $ro ?>>
                            <label class="form-check-label">Asimetris</label>
                        </div>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-sm-2"><strong>Laserasi/Jejas</strong></div>  

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="laserasijejas" value="ada" <?= val('laserasijejas', $existing_data) == 'ada' ? 'checked' : '' ?> <?= $ro ?>>
                            <label class="form-check-label">Ada</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="laserasijejas" value="tidak" <?= val('laserasijejas', $existing_data) == 'tidak' ? 'checked' : '' ?> <?= $ro ?>>
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>

                    <div class="col-sm-10 offset-sm-2">
                        <div class="row mt-2">
                            <div class="col-sm-12 mb-2">
                                <label class="mb-1"><strong>Ukuran Luka</strong></label>
                                <input type="text" class="form-control" name="ukuranluka" value="<?= htmlspecialchars(val('ukuranluka', $existing_data)) ?>" <?= $ro ?>>
                            </div>

                            <div class="col-sm-12">
                                <label class="mb-1"><strong>Lokasi</strong></label>
                                <input type="text" class="form-control" name="lokasi" value="<?= htmlspecialchars(val('lokasi', $existing_data)) ?>" <?= $ro ?>>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-sm-2"><strong>Pendarahan</strong></div>  

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="pendarahandada" value="ada" <?= val('pendarahandada', $existing_data) == 'ada' ? 'checked' : '' ?> <?= $ro ?>>
                            <label class="form-check-label">Ada</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="pendarahandada" value="tidakada" <?= val('pendarahandada', $existing_data) == 'tidakada' ? 'checked' : '' ?> <?= $ro ?>>
                            <label class="form-check-label">Tidak Ada</label>
                        </div>
                    </div>

                    <div class="col-sm-10 offset-sm-2">
                        <div class="row mt-2">
                            <div class="col-sm-12">
                                <label class="mb-1"><strong>Jika Ada, Berapa Banyak</strong></label>
                                <input type="text" class="form-control" name="jikaadaberapabanyak" value="<?= htmlspecialchars(val('jikaadaberapabanyak', $existing_data)) ?>" <?= $ro ?>>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><strong>RR</strong></label>
                    <div class="col-sm-10">
                        <div class="input-group">
                            <input type="text" class="form-control" name="rr2" value="<?= htmlspecialchars(val('rr2', $existing_data)) ?>" <?= $ro ?>>
                            <span class="input-group-text">x/menit</span>
                        </div>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-sm-2"><strong>Irama Napas</strong></div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="iramanapas" value="teratur" <?= val('iramanapas', $existing_data) == 'teratur' ? 'checked' : '' ?> <?= $ro ?>>
                            <label class="form-check-label">Teratur</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="iramanapas" value="tidakteratur" <?= val('iramanapas', $existing_data) == 'tidakteratur' ? 'checked' : '' ?> <?= $ro ?>>
                            <label class="form-check-label">Tidak Teratur</label>
                        </div>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-sm-2"><strong>Penggunaan Otot-otot Dinding Dada</strong></div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="ototdada" value="ya" <?= val('ototdada', $existing_data) == 'ya' ? 'checked' : '' ?> <?= $ro ?>>
                            <label class="form-check-label">Ya</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="ototdada" value="tidak" <?= val('ototdada', $existing_data) == 'tidak' ? 'checked' : '' ?> <?= $ro ?>>
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-sm-2"><strong>Bunyi Jantung</strong></div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="bunyijantung" value="normal" <?= val('bunyijantung', $existing_data) == 'normal' ? 'checked' : '' ?> <?= $ro ?>>
                            <label class="form-check-label">Normal</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="bunyijantung" value="murmur" <?= val('bunyijantung', $existing_data) == 'murmur' ? 'checked' : '' ?> <?= $ro ?>>
                            <label class="form-check-label">Murmur (Mendesis)</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="bunyijantung" value="gallop" <?= val('bunyijantung', $existing_data) == 'gallop' ? 'checked' : '' ?> <?= $ro ?>>
                            <label class="form-check-label">Gallop</label>
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="bunyijantung" value="friction" <?= val('bunyijantung', $existing_data) == 'friction' ? 'checked' : '' ?> <?= $ro ?>>
                            <label class="form-check-label">Friction Rub/Gesekan</label>
                        </div>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-sm-2"><strong>Nyeri Dada</strong></div>  

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="nyeridada" value="ya" <?= val('nyeridada', $existing_data) == 'ya' ? 'checked' : '' ?> <?= $ro ?>>
                            <label class="form-check-label">Ya</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="nyeridada" value="tidak" <?= val('nyeridada', $existing_data) == 'tidak' ? 'checked' : '' ?> <?= $ro ?>>
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>

                    <div class="col-sm-10 offset-sm-2">
                        <div class="row mt-2">
                            <div class="col-sm-12">
                                <label class="mb-1"><strong>Jika Ada Nyeri, Jelaskan</strong></label>
                                <input type="text" class="form-control" name="jikaadanyerijelaskan" value="<?= htmlspecialchars(val('jikaadanyerijelaskan', $existing_data)) ?>" <?= $ro ?>>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><strong>Keluhan Lain</strong></label>
                    <div class="col-sm-10">
                        <textarea name="keluhanlaindada" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars(val('keluhanlaindada', $existing_data)) ?></textarea>
                    </div>
                </div>

<div class="row mb-3">
                <label class="col-sm-12 col-form-label text-primary">
                    <strong>Abdomen</strong>
                </label>
            </div> 
            
            <div class="row mb-2">
                <div class="col-sm-2"><strong>Dinding Abdomen</strong></div>    

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="dindingabdomen" value="simetris" <?= val('dindingabdomen', $existing_data) == 'simetris' ? 'checked' : '' ?> <?= $ro ?>>
                        <label class="form-check-label">Simetris</label>
                    </div>
                </div>

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="dindingabdomen" value="asimetris" <?= val('dindingabdomen', $existing_data) == 'asimetris' ? 'checked' : '' ?> <?= $ro ?>>
                        <label class="form-check-label">An-simetris</label>
                    </div>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-sm-2"><strong>Pendarahan</strong></div>  

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="pendarahanabdomen" value="ya" <?= val('pendarahanabdomen', $existing_data) == 'ya' ? 'checked' : '' ?> <?= $ro ?>>
                        <label class="form-check-label">Ya</label>
                    </div>
                </div>

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="pendarahanabdomen" value="tidak" <?= val('pendarahanabdomen', $existing_data) == 'tidak' ? 'checked' : '' ?> <?= $ro ?>>
                        <label class="form-check-label">Tidak</label>
                    </div>
                </div>

                <div class="col-sm-10 offset-sm-2">
                    <div class="row mt-2">
                        <div class="col-sm-12">
                            <label class="mb-1"><strong>Jika Ada, Berapa Banyak</strong></label>
                            <input type="text" class="form-control" name="jikaadaberapabanyakabdomen" value="<?= htmlspecialchars(val('jikaadaberapabanyakabdomen', $existing_data)) ?>" <?= $ro ?>>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-sm-2"><strong>Bengkak</strong></div>    

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="bengkakabdomen" value="ya" <?= val('bengkakabdomen', $existing_data) == 'ya' ? 'checked' : '' ?> <?= $ro ?>>
                        <label class="form-check-label">Ya</label>
                    </div>
                </div>

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="bengkakabdomen" value="tidak" <?= val('bengkakabdomen', $existing_data) == 'tidak' ? 'checked' : '' ?> <?= $ro ?>>
                        <label class="form-check-label">Tidak</label>
                    </div>
                </div>
            </div>

             <div class="row mb-2">
                <div class="col-sm-2"><strong>Laserasi</strong></div>    

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="leserasiabdomen" value="ya" <?= val('leserasiabdomen', $existing_data) == 'ya' ? 'checked' : '' ?> <?= $ro ?>>
                        <label class="form-check-label">Ya</label>
                    </div>
                </div>

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="leserasiabdomen" value="tidak" <?= val('leserasiabdomen', $existing_data) == 'tidak' ? 'checked' : '' ?> <?= $ro ?>>
                        <label class="form-check-label">Tidak</label>
                    </div>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-sm-2"><strong>Distensi Abdomen</strong></div>    

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="distensiabdomen" value="ya" <?= val('distensiabdomen', $existing_data) == 'ya' ? 'checked' : '' ?> <?= $ro ?>>
                        <label class="form-check-label">Ya</label>
                    </div>
                </div>

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="distensiabdomen" value="tidak" <?= val('distensiabdomen', $existing_data) == 'tidak' ? 'checked' : '' ?> <?= $ro ?>>
                        <label class="form-check-label">Tidak</label>
                    </div>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-sm-2"><strong>Bising Usus</strong></div>  

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="bisingusus" value="ada" <?= val('bisingusus', $existing_data) == 'ada' ? 'checked' : '' ?> <?= $ro ?>>
                        <label class="form-check-label">Ada</label>
                    </div>
                </div>

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="bisingusus" value="tidak" <?= val('bisingusus', $existing_data) == 'tidak' ? 'checked' : '' ?> <?= $ro ?>>
                        <label class="form-check-label">Tidak</label>
                    </div>
                </div>

                <div class="col-sm-10 offset-sm-2">
                    <div class="row mt-2">
                        <div class="col-sm-12">
                            <label class="mb-1"><strong>Jika Ada, Berapa Kali</strong></label>
                            <input type="text" class="form-control" name="jikadaaberapakali" value="<?= htmlspecialchars(val('jikadaaberapakali', $existing_data)) ?>" <?= $ro ?>>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-sm-2"><strong>Nyeri Tekan</strong></div>    

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="nyeritekanabdomen" value="ya" <?= val('nyeritekanabdomen', $existing_data) == 'ya' ? 'checked' : '' ?> <?= $ro ?>>
                        <label class="form-check-label">Ya</label>
                    </div>
                </div>

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="nyeritekanabdomen" value="tidak" <?= val('nyeritekanabdomen', $existing_data) == 'tidak' ? 'checked' : '' ?> <?= $ro ?>>
                        <label class="form-check-label">Tidak</label>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-2 col-form-label"><strong>Keluhan Lain</strong></label>
                <div class="col-sm-10">
                    <textarea name="keluhanlainabdomen" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars(val('keluhanlainabdomen', $existing_data)) ?></textarea>
                </div>
            </div>

<div class="row mb-3">
                <label class="col-sm-12 col-form-label text-primary">
                    <strong>Ekstremitas Atas dan Bawah</strong>
                </label>
            </div>   

            <div class="row mb-2">
                <div class="col-sm-2"><strong>Teraba Benjolan/Keras</strong></div>  

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="terababenjolankeras" value="ya" <?= val('terababenjolankeras', $existing_data) == 'ya' ? 'checked' : '' ?> <?= $ro ?>>
                        <label class="form-check-label">Ya</label>
                    </div>
                </div>

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="terababenjolankeras" value="tidak" <?= val('terababenjolankeras', $existing_data) == 'tidak' ? 'checked' : '' ?> <?= $ro ?>>
                        <label class="form-check-label">Tidak</label>
                    </div>
                </div>

                <div class="col-sm-10 offset-sm-2">
                    <div class="row mt-2">
                        <div class="col-sm-12 mb-2">
                            <label class="mb-1"><strong>Jika Ada, Ukuran</strong></label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="jikaadaukuran" value="<?= htmlspecialchars(val('jikaadaukuran', $existing_data)) ?>" <?= $ro ?>>
                                <span class="input-group-text">cm</span>
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <label class="mb-1"><strong>Lokasi</strong></label>
                            <input type="text" class="form-control" name="lokasibenjolan" value="<?= htmlspecialchars(val('lokasibenjolan', $existing_data)) ?>" <?= $ro ?>>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-sm-2"><strong>Pendarahan</strong></div>  

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="pendarahanekstremitas" value="ya" <?= val('pendarahanekstremitas', $existing_data) == 'ya' ? 'checked' : '' ?> <?= $ro ?>>
                        <label class="form-check-label">Ya</label>
                    </div>
                </div>

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="pendarahanekstremitas" value="tidak" <?= val('pendarahanekstremitas', $existing_data) == 'tidak' ? 'checked' : '' ?> <?= $ro ?>>
                        <label class="form-check-label">Tidak</label>
                    </div>
                </div>

                <div class="col-sm-10 offset-sm-2">
                    <div class="row mt-2">
                        <div class="col-sm-12 mb-2">
                            <label class="mb-1"><strong>Lokasi</strong></label>
                            <input type="text" class="form-control" name="lokasiekstremitas" value="<?= htmlspecialchars(val('lokasiekstremitas', $existing_data)) ?>" <?= $ro ?>>
                        </div>

                        <div class="col-sm-12">
                            <label class="mb-1"><strong>Jumlah</strong></label>
                            <input type="text" class="form-control" name="jumlah" value="<?= htmlspecialchars(val('jumlah', $existing_data)) ?>" <?= $ro ?>>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-sm-2"><strong>Edema</strong></div>    

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="edemaekstremitas" value="ya" <?= val('edemaekstremitas', $existing_data) == 'ya' ? 'checked' : '' ?> <?= $ro ?>>
                        <label class="form-check-label">Ya</label>
                    </div>
                </div>

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="edemaekstremitas" value="tidak" <?= val('edemaekstremitas', $existing_data) == 'tidak' ? 'checked' : '' ?> <?= $ro ?>>
                        <label class="form-check-label">Tidak</label>
                    </div>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-sm-2"><strong>Nyeri Tekan</strong></div>    

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="nyeritekanekstremitas" value="ya" <?= val('nyeritekanekstremitas', $existing_data) == 'ya' ? 'checked' : '' ?> <?= $ro ?>>
                        <label class="form-check-label">Ya</label>
                    </div>
                </div>

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="nyeritekanekstremitas" value="tidak" <?= val('nyeritekanekstremitas', $existing_data) == 'tidak' ? 'checked' : '' ?> <?= $ro ?>>
                        <label class="form-check-label">Tidak</label>
                    </div>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-sm-2"><strong>Fraktur</strong></div>  

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="fraktur" value="ya" <?= val('fraktur', $existing_data) == 'ya' ? 'checked' : '' ?> <?= $ro ?>>
                        <label class="form-check-label">Ya</label>
                    </div>
                </div>

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="fraktur" value="tidak" <?= val('fraktur', $existing_data) == 'tidak' ? 'checked' : '' ?> <?= $ro ?>>
                        <label class="form-check-label">Tidak</label>
                    </div>
                </div>

                <div class="col-sm-10 offset-sm-2">
                    <div class="row mt-2">
                        <div class="col-sm-12">
                            <label class="mb-1"><strong>Lokasi Fraktur</strong></label>
                            <input type="text" class="form-control" name="lokasifraktur" value="<?= htmlspecialchars(val('lokasifraktur', $existing_data)) ?>" <?= $ro ?>>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-sm-2"><strong>Kekakuan Pada Persendian Ekstremitas</strong></div>    

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="kekakuan" value="ya" <?= val('kekakuan', $existing_data) == 'ya' ? 'checked' : '' ?> <?= $ro ?>>
                        <label class="form-check-label">Ya</label>
                    </div>
                </div>

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="kekakuan" value="tidak" <?= val('kekakuan', $existing_data) == 'tidak' ? 'checked' : '' ?> <?= $ro ?>>
                        <label class="form-check-label">Tidak</label>
                    </div>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-sm-2"><strong>Keterbatasan Gerak</strong></div>    

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="keterbatasangerak" value="ya" <?= val('keterbatasangerak', $existing_data) == 'ya' ? 'checked' : '' ?> <?= $ro ?>>
                        <label class="form-check-label">Ya</label>
                    </div>
                </div>

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="keterbatasangerak" value="tidak" <?= val('keterbatasangerak', $existing_data) == 'tidak' ? 'checked' : '' ?> <?= $ro ?>>
                        <label class="form-check-label">Tidak</label>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-12 col-form-label text-secondary">
                    <strong>Kekuatan Otot</strong>
                </label> 
            </div>
            
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label"><strong>Ekstremitas Atas</strong></label>
                <div class="col-sm-10">
                    <textarea name="ekstremitasatas" class="form-control" rows="3" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars(val('ekstremitasatas', $existing_data)) ?></textarea>
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-2 col-form-label"><strong>Ekstremitas Bawah</strong></label>
                <div class="col-sm-10">
                    <textarea name="ekstremitasbawah" class="form-control" rows="3" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars(val('ekstremitasbawah', $existing_data)) ?></textarea>
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-2 col-form-label"><strong>Keluhan Lain</strong></label>
                <div class="col-sm-10">
                    <textarea name="keluhanlainekstremitas" class="form-control" rows="3" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= htmlspecialchars(val('keluhanlainekstremitas', $existing_data)) ?></textarea>
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-10 col-form-label text-primary"><strong>Punggung</strong></label>
            </div>   

            <div class="row mb-2">
                <div class="col-sm-2"><strong>Terdapat Luka</strong></div>  

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="terdapatluka" value="ya" <?= val('terdapatluka', $existing_data) == 'ya' ? 'checked' : '' ?> <?= $ro ?>>
                        <label class="form-check-label">Ya</label>
                    </div>
                </div>

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="terdapatluka" value="tidak" <?= val('terdapatluka', $existing_data) == 'tidak' ? 'checked' : '' ?> <?= $ro ?>>
                        <label class="form-check-label">Tidak</label>
                    </div>
                </div>

                <div class="col-sm-10 offset-sm-2">
                    <div class="row mt-2">
                        <div class="col-sm-11">
                            <label><strong>Ukuran</strong></label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="ukuranluka2" value="<?= val('ukuranluka2', $existing_data) ?>" <?= $ro ?>>
                                <span class="input-group-text">cm</span>
                            </div>

                        </div>

                    </div>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-sm-2"><strong>Decubitus</strong></div>  

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="decubituspunggung" value="ada" <?= val('decubituspunggung', $existing_data) == 'ada' ? 'checked' : '' ?> <?= $ro ?>>
                        <label class="form-check-label">Ada</label>
                    </div>
                </div>

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="decubituspunggung" value="tidakada" <?= val('decubituspunggung', $existing_data) == 'tidakada' ? 'checked' : '' ?> <?= $ro ?>>
                        <label class="form-check-label">Tidak Ada</label>
                    </div>
                </div>

                <div class="col-sm-10 offset-sm-2">
                    <div class="row mt-2">
                        <div class="col-sm-11">
                            <label><strong>Ukuran</strong></label>
                            <input type="text" class="form-control" name="ukurandecubituspunggung" value="<?= val('ukurandecubituspunggung', $existing_data) ?>" <?= $ro ?>>

                        </div>

                    </div>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-sm-2"><strong>Echymosis/Lebam</strong></div>    

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="echymosislebampunggung" value="ada" <?= val('echymosislebampunggung', $existing_data) == 'ada' ? 'checked' : '' ?> <?= $ro ?>>
                        <label class="form-check-label">Ada</label>
                    </div>
                </div>

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="echymosislebampunggung" value="tidakada" <?= val('echymosislebampunggung', $existing_data) == 'tidakada' ? 'checked' : '' ?> <?= $ro ?>>
                        <label class="form-check-label">Tidak Ada</label>
                    </div>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-sm-2"><strong>Gatal-gatal</strong></div>    

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="gatalgatal" value="ya" <?= val('gatalgatal', $existing_data) == 'ya' ? 'checked' : '' ?> <?= $ro ?>>
                        <label class="form-check-label">Ya</label>
                    </div>
                </div>

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="gatalgatal" value="tidak" <?= val('gatalgatal', $existing_data) == 'tidak' ? 'checked' : '' ?> <?= $ro ?>>
                        <label class="form-check-label">Tidak</label>
                    </div>
                </div>
            </div>
                
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label"><strong>Keluhan Lain</strong></label>
                <div class="col-sm-10">
                    <div class="row">  
                        <div class="col-sm-11">
                            <textarea name="keluhanlainpunggung" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('keluhanlainpunggung', $existing_data) ?></textarea>
                                
                        </div>

                    </div> 
                </div>
            </div>
                    
            <div class="row mb-3">
                <label class="col-sm-10 col-form-label text-primary"><strong>Kulit</strong></label>
            </div>   

            <div class="row mb-3">
                <label class="col-sm-2 col-form-label"><strong>Warna</strong></label>
                <div class="col-sm-10">
                    <div class="row">  
                        <div class="col-sm-11">
                            <textarea name="kulit" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('kulit', $existing_data) ?></textarea>
                                
                        </div>

                    </div> 
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-sm-2"><strong>Turgor</strong></div>    

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="turgor" value="elastis" <?= val('turgor', $existing_data) == 'elastis' ? 'checked' : '' ?> <?= $ro ?>>
                        <label class="form-check-label">Elastis</label>
                    </div>
                </div>

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="turgor" value="menurun" <?= val('turgor', $existing_data) == 'menurun' ? 'checked' : '' ?> <?= $ro ?>>
                        <label class="form-check-label">Menurun</label>
                    </div>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-sm-2"><strong>Keadaan</strong></div>    

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="keadaan" value="lembab" <?= val('keadaan', $existing_data) == 'lembab' ? 'checked' : '' ?> <?= $ro ?>>
                        <label class="form-check-label">Lembab</label>
                    </div>
                </div>

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="keadaan" value="kering" <?= val('keadaan', $existing_data) == 'kering' ? 'checked' : '' ?> <?= $ro ?>>
                        <label class="form-check-label">Kering</label>
                    </div>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-sm-2"><strong>Edema</strong></div>  

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="edemakulit" value="ya" <?= val('edemakulit', $existing_data) == 'ya' ? 'checked' : '' ?> <?= $ro ?>>
                        <label class="form-check-label">Ya</label>
                    </div>
                </div>

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="edemakulit" value="tidak" <?= val('edemakulit', $existing_data) == 'tidak' ? 'checked' : '' ?> <?= $ro ?>>
                        <label class="form-check-label">Tidak</label>
                    </div>
                </div>

                <div class="col-sm-10 offset-sm-2">
                    <div class="row mt-2">
                        <div class="col-sm-11">
                            <label><strong>Lokasi</strong></label>
                            <input type="text" class="form-control" name="lokasiedemakulit" value="<?= val('lokasiedemakulit', $existing_data) ?>" <?= $ro ?>>

                        </div>

                    </div>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-sm-2"><strong>Luka</strong></div>  

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="lukakulit" value="ya" <?= val('lukakulit', $existing_data) == 'ya' ? 'checked' : '' ?> <?= $ro ?>>
                        <label class="form-check-label">Ya</label>
                    </div>
                </div>

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="lukakulit" value="tidak" <?= val('lukakulit', $existing_data) == 'tidak' ? 'checked' : '' ?> <?= $ro ?>>
                        <label class="form-check-label">Tidak</label>
                    </div>
                </div>

                <div class="col-sm-10 offset-sm-2">
                    <div class="row mt-2">
                        <div class="col-sm-11">
                            <label><strong>Lokasi</strong></label>
                            <input type="text" class="form-control" name="lokasilukakulit" value="<?= val('lokasilukakulit', $existing_data) ?>" <?= $ro ?>>

                        </div>

                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-2 col-form-label"><strong>Karakteristik Luka</strong></label>
                <div class="col-sm-10">
                    <div class="row">  
                        <div class="col-sm-11">
                            <textarea name="karakteristikluka" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('karakteristikluka', $existing_data) ?></textarea>
                                
                        </div>

                    </div> 
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-2 col-form-label"><strong>Keluhan Lain</strong></label>
                <div class="col-sm-10">
                    <div class="row">  
                        <div class="col-sm-11">
                            <textarea name="keluhanlainkulit" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('keluhanlainkulit', $existing_data) ?></textarea>
                                
                        </div>

                    </div> 
                </div>
            </div>
                    
            <div class="row mb-3">
                <label class="col-sm-10 col-form-label text-primary"><strong>Genitalia</strong></label>
            </div>   

            <div class="row mb-2">
                <div class="col-sm-2"><strong>Radang</strong></div>    

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="radanggenitalia" value="ya" <?= val('radanggenitalia', $existing_data) == 'ya' ? 'checked' : '' ?> <?= $ro ?>>
                        <label class="form-check-label">Ya</label>
                    </div>
                </div>

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="radanggenitalia" value="tidak" <?= val('radanggenitalia', $existing_data) == 'tidak' ? 'checked' : '' ?> <?= $ro ?>>
                        <label class="form-check-label">Tidak</label>
                    </div>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-sm-2"><strong>Pembengkakan Skrotum</strong></div>    

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="pembengkakanskrotum" value="ya" <?= val('pembengkakanskrotum', $existing_data) == 'ya' ? 'checked' : '' ?> <?= $ro ?>>
                        <label class="form-check-label">Ya</label>
                    </div>
                </div>

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="pembengkakanskrotum" value="tidak" <?= val('pembengkakanskrotum', $existing_data) == 'tidak' ? 'checked' : '' ?> <?= $ro ?>>
                        <label class="form-check-label">Tidak</label>
                    </div>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-sm-2"><strong>Lesi</strong></div>    

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="lesi" value="ya" <?= val('lesi', $existing_data) == 'ya' ? 'checked' : '' ?> <?= $ro ?>>
                        <label class="form-check-label">Ya</label>
                    </div>
                </div>

                <div class="col-sm-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="lesi" value="tidak" <?= val('lesi', $existing_data) == 'tidak' ? 'checked' : '' ?> <?= $ro ?>>
                        <label class="form-check-label">Tidak</label>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-2 col-form-label"><strong>Keluhan Lain</strong></label>
                <div class="col-sm-10">
                    <div class="row">  
                        <div class="col-sm-11">
                            <textarea name="keluhanlaingenitalia" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('keluhanlaingenitalia', $existing_data) ?></textarea>
                                
                        </div>

                    </div> 
                    </div>
                    </div>
                   
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
    </div>
</div>
       </form>

               <?php include dirname(__DIR__, 2) . '/partials/footer_form.php'; ?>


</section>
</main>
