<?php
require_once "koneksi.php";
require_once "utils.php";

$form_id       = 30;
$section_name  = 'pengkajianlanjutan2';
$section_label = 'Pengkajian Lanjutan 2';
include dirname(__DIR__, 2) . '/partials/init_section.php';


// =============================================
// HANDLE POST - MAHASISWA SIMPAN DATA
// =============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $level === 'Mahasiswa') {

    if (isLocked($submission)) {
        redirectWithMessage($_SERVER['REQUEST_URI'], 'error', 'Data tidak dapat diubah karena sedang dalam proses review.');
    }

    $data = [
        'gambardenahrumah'                          => $_POST['gambardenahrumah'] ?? '',
        'jenisbangunan'                             => $_POST['jenisbangunan'] ?? '',
        'jumlahkamar'                               => $_POST['jumlahkamar'] ?? '',
        'pencahayaan'                               => $_POST['pencahayaan'] ?? '',
        'ventilasi'                                 => $_POST['ventilasi'] ?? '',
        'sumberairbersih'                           => $_POST['sumberairbersih'] ?? '',
        'sumberairminum'                            => $_POST['sumberairminum'] ?? '',
        'pembuanganairlimbah'                       => $_POST['pembuanganairlimbah'] ?? '',
        'peneranganmalamhari'                       => $_POST['peneranganmalamhari'] ?? '',
        'kebersihantoilet'                          => $_POST['kebersihantoilet'] ?? '',
        'jumlahjentik'                              => $_POST['jumlahjentik'] ?? '',
        'jarakrumahdisekitarnya'                    => $_POST['jarakrumahdisekitarnya'] ?? '',
        'kondisitetanggasekitar'                    => $_POST['kondisitetanggasekitar'] ?? '',
        'keluargaterhadaptetanggasekitar'           => $_POST['keluargaterhadaptetanggasekitar'] ?? '',
        'kegiatankeluargasaatpagi'                  => $_POST['kegiatankeluargasaatpagi'] ?? '',
        'kegiatankeluargasaatsiangsore'             => $_POST['kegiatankeluargasaatsiangsore'] ?? '',
        'kegiatankeluargasaatmalam'                 => $_POST['kegiatankeluargasaatmalam'] ?? '',
        'kegiatankeluargasaatsenggangluang'         => $_POST['kegiatankeluargasaatsenggangluang'] ?? '',
        'waktuberkunjung'                           => $_POST['waktuberkunjung'] ?? '',
        'keluargabesarkumpulpadasaat'               => $_POST['keluargabesarkumpulpadasaat'] ?? '',
        'kegiatanditempattinggal'                   => $_POST['kegiatanditempattinggal'] ?? '',
        'masalahekonomi'                            => $_POST['masalahekonomi'] ?? '',
        'dukunganmenghadapimasalah'                 => $_POST['dukunganmenghadapimasalah'] ?? '',
        'jaminansosialkesehatan'                    => $_POST['jaminansosialkesehatan'] ?? '',
        'komunikasiantarkeluarga'                   => $_POST['komunikasiantarkeluarga'] ?? '',
        'bahasayangseringdigunakan'                 => $_POST['bahasayangseringdigunakan'] ?? '',
        'memilikiponsel'                            => $_POST['memilikiponsel'] ?? '',
        'waktuanak'                                 => $_POST['waktuanak'] ?? '',
        'perankepalakeluarga'                       => $_POST['perankepalakeluarga'] ?? '',
        'peranistridalamkeluarga'                   => $_POST['peranistridalamkeluarga'] ?? '',
        'perananakdalamkeluarga'                    => $_POST['perananakdalamkeluarga'] ?? '',
        'kebiasaandirumah'                          => $_POST['kebiasaandirumah'] ?? '',
        'nilainilaiagama'                           => $_POST['nilainilaiagama'] ?? '',
        'pergaulananak'                             => $_POST['pergaulananak'] ?? '',
        'mengambilkeputusan'                        => $_POST['mengambilkeputusan'] ?? '',
        'pengambilankeputusankeluarga'              => $_POST['pengambilankeputusankeluarga'] ?? '',
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

    <?php include "keluarga/format_keluarga/tab.php"; ?>

    <section class="section dashboard">

        <?php include dirname(__DIR__, 2) . '/partials/notifikasi.php'; ?>
        <?php include dirname(__DIR__, 2) . '/partials/status_section.php'; ?>

        <div class="card">
            <div class="card-body">

                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

                <h5 class="card-title"><strong>V. Data Lingkungan</strong></h5>  
            
             <!-- Bagian Karakteristik Rumah -->
                    <div class="row mb-3">
                        <label class="col-sm-10 col-form-label text-primary">
                            <strong>1. Karakteristik Rumah</strong>
                    </div> 
                    
            <!-- Bagian Gambar Denah Rumah -->
            <div class="row mb-3">
                    <label for="gambardenahrumah" class="col-sm-2 col-form-label"><strong>Gambar Denah Rumah</strong></label>

                    <div class="col-sm-10">
                        <input type="text" name="gambardenahrumah" class="form-control" placeholder="Lampirkan link Google Drive upload Gambar Denah Rumah"
                            value="<?= val('gambardenahrumah', $existing_data) ?>"
                            <?= $ro ?>>
                    </div>
                </div>

                <!-- Bagian Penjelasan Karakteristik Rumah -->

                    <div class="row mb-3">
                        <label class="col-sm-9 col-form-label">
                            <strong>Penjelasan Karakteristik Rumah</strong>
                        </label>    
                    </div>

                    <!-- Jenis Bangunan -->
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-2 col-form-label"><strong>Jenis Bangunan</strong></label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" name="jenisbangunan"
                                value="<?= val('jenisbangunan', $existing_data) ?>" <?= $ro ?>>  
                    </div>
                                
                    <!-- Jumlah Kamar -->
                    <label class="col-sm-2 col-form-label"><strong>Jumlah Kamar</strong></label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" name="jumlahkamar"
                                value="<?= val('jumlahkamar', $existing_data) ?>" <?= $ro ?>>
                    </div>
                </div>
              
                <!-- Pencahayaan -->
                <div class="row mb-3 align-items-center">
                    <label class="col-sm-2 col-form-label"><strong>Pencahayaan</strong></label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" name="pencahayaan"
                                value="<?= val('pencahayaan', $existing_data) ?>" <?= $ro ?>>
                    </div>

                <!-- Ventilasi -->
                <label class="col-sm-2 col-form-label"><strong>Ventilasi</strong></label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" name="ventilasi"
                                value="<?= val('ventilasi', $existing_data) ?>" <?= $ro ?>>
                    </div>
                </div>

                <!-- Sumber Air Bersih -->
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-2 col-form-label"><strong>Sumber Air Bersih</strong></label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" name="sumberairbersih"
                                value="<?= val('sumberairbersih', $existing_data) ?>" <?= $ro ?>>
                    </div>
                                
                    <!-- Sumber Air Minum -->
                    <label class="col-sm-2 col-form-label"><strong>Sumber Air Minum</strong></label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" name="sumberairminum"
                                value="<?= val('sumberairminum', $existing_data) ?>" <?= $ro ?>>
                    </div>
                </div>  
                
                <!-- Pembuangan Air Limbah -->
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-2 col-form-label"><strong>Pembuangan Air Limbah</strong></label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" name="pembuanganairlimbah"
                                value="<?= val('pembuanganairlimbah', $existing_data) ?>" <?= $ro ?>>
                    </div>
                                
                    <!-- Penerangan (Malam Hari) -->
                    <label class="col-sm-2 col-form-label"><strong>Penerangan (Malam Hari)</strong></label>
                        <div class="col-sm-4">
                           <input type="text" class="form-control" name="peneranganmalamhari"
                                value="<?= val('peneranganmalamhari', $existing_data) ?>" <?= $ro ?>>
                    </div>
                </div>

                <!-- Kebersihan Toilet, Kamar Mandi, dan Tempat Penampungan Air -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Kebersihan Toilet, Kamar Mandi, dan Tempat Penampungan Air</strong></label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" name="kebersihantoilet"
                                value="<?= val('kebersihantoilet', $existing_data) ?>" <?= $ro ?>>   
                    </div>
                                
                    <!-- Jumlah Jentik Nyamuk di Tempat Penampungan Air -->
                    <label class="col-sm-2 col-form-label"><strong>Jumlah Jentik Nyamuk di Tempat Penampungan Air</strong></label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" name="jumlahjentik"
                                value="<?= val('jumlahjentik', $existing_data) ?>" <?= $ro ?>>
                    </div>
            
                </div>  
                        
             <!-- Bagian Karakteristik Tetangga Komunitasnya -->
                    <div class="row mb-3">
                        <label class="col-sm-10 col-form-label text-primary">
                            <strong>2. Karakteristik Tetangga Komunitasnya</strong>
                    </div>             
        
             <!-- Bagian Jarak Rumah disekitarnya -->

             <div class="row mb-3">
                <label for="jarakrumahdisekitarnya" class="col-sm-2 col-form-label"><strong>Jarak Rumah disekitarnya</strong></label>
                    <div class="col-sm-10">
                        <div class="input-group">
                            <input type="text" class="form-control" name="jarakrumahdisekitarnya"
                                value="<?= val('jarakrumahdisekitarnya', $existing_data) ?>" <?= $ro ?>>
                            <span class="input-group-text">meter</span>
                    </div>  
                         </div>
                    </div>          
                    
             <!-- Bagian Kondisi Tetangga Sekitar -->

             <div class="row mb-3">
                <label for="kondisitetanggasekitar" class="col-sm-2 col-form-label"><strong>Kondisi Tetangga Sekitar</strong></label>
                    <div class="col-sm-10">
                       <textarea name="kondisitetanggasekitar" class="form-control auto-resize" style="overflow:hidden; resize:none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('kondisitetanggasekitar',$existing_data) ?></textarea></div>
                    </div>  
                    
             <!-- Bagian Tanggapan Keluarga Terhadap Tetangga Sekitar -->

             <div class="row mb-3">
                <label for="keluargaterhadaptetanggasekitar" class="col-sm-2 col-form-label"><strong>Tanggapan Keluarga Terhadap Tetangga Sekitar</strong></label>
                    <div class="col-sm-10">
                       <textarea name="keluargaterhadaptetanggasekitar" class="form-control auto-resize" style="overflow:hidden; resize:none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('keluargaterhadaptetanggasekitar',$existing_data) ?></textarea></div>
                    </div>    
                    
            <!-- Bagian Mobilitas Geografis Keluarga -->
                    <div class="row mb-3">
                        <label class="col-sm-10 col-form-label text-primary">
                            <strong>3. Mobilitas Geografis Keluarga</strong>
                    </div>         
            
            <!-- Bagian Kegiatan Keluarga Saat Pagi -->

             <div class="row mb-3">
                <label for="kegiatankeluargasaatpagi" class="col-sm-2 col-form-label"><strong>Kegiatan Keluarga Saat Pagi</strong></label>
                    <div class="col-sm-10">
                       <textarea name="kegiatankeluargasaatpagi" class="form-control auto-resize" style="overflow:hidden; resize:none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('kegiatankeluargasaatpagi',$existing_data) ?></textarea></div>
                    </div>   

             <!-- Bagian Kegiatan Keluarga Saat Siang/Sore -->

             <div class="row mb-3">
                <label for="kegiatankeluargasaatsiangsore" class="col-sm-2 col-form-label"><strong>Kegiatan Keluarga Saat Siang/Sore</strong></label>
                    <div class="col-sm-10">
                       <textarea name="kegiatankeluargasaatsiangsore" class="form-control auto-resize" style="overflow:hidden; resize:none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('kegiatankeluargasaatsiangsore',$existing_data) ?></textarea></div>
                    </div>  
                    
             <!-- Bagian Kegiatan Keluarga Saat Malam -->

             <div class="row mb-3">
                <label for="kegiatankeluargasaatmalam" class="col-sm-2 col-form-label"><strong>Kegiatan Keluarga Saat Malam</strong></label>
                    <div class="col-sm-10">
                       <textarea name="kegiatankeluargasaatmalam" class="form-control auto-resize" style="overflow:hidden; resize:none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('kegiatankeluargasaatmalam',$existing_data) ?></textarea></div>
                    </div> 
                    
             <!-- Bagian Kegiatan Keluarga Saat Senggang/Luang -->

             <div class="row mb-3">
                <label for="kegiatankeluargasaatsenggangluang" class="col-sm-2 col-form-label"><strong>Kegiatan Keluarga Saat Senggang/Luang</strong></label>
                    <div class="col-sm-10">
                       <textarea name="kegiatankeluargasaatsenggangluang" class="form-control auto-resize" style="overflow:hidden; resize:none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('kegiatankeluargasaatsenggangluang',$existing_data) ?></textarea></div>
                    </div> 
                    
             <!-- Bagian Waktu Keluarga Berkunjung untuk Saudara yang lain -->

             <div class="row mb-3">
                <label for="waktuberkunjung" class="col-sm-2 col-form-label"><strong>Waktu Keluarga Berkunjung untuk Saudara yang lain</strong></label>
                    <div class="col-sm-10">
                       <textarea name="waktuberkunjung" class="form-control auto-resize" style="overflow:hidden; resize:none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('waktuberkunjung',$existing_data) ?></textarea></div>
                    </div>  
                    
             <!-- Bagian Perkumpulan Keluarga dan Interaksi dengan Masyarakat -->
                    <div class="row mb-3">
                        <label class="col-sm-10 col-form-label text-primary">
                            <strong>4. Perkumpulan Keluarga dan Interaksi dengan Masyarakat</strong>
                    </div>         
                    
             <!-- Bagian Keluarga Besar Kumpul Pada Saat -->

             <div class="row mb-3">
                <label for="keluargabesarkumpulpadasaat" class="col-sm-2 col-form-label"><strong>Keluarga Besar Berkumpul Pada Saat</strong></label>
                    <div class="col-sm-10">
                       <textarea name="keluargabesarkumpulpadasaat" class="form-control auto-resize" style="overflow:hidden; resize:none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('keluargabesarkumpulpadasaat',$existing_data) ?></textarea></div>
                    </div>  
                    
             <!-- Bagian Kegiatan yang Ada dan Diikuti di Lingkungan Tempat Tinggal -->

             <div class="row mb-3">
                <label for="kegiatanditempattinggal" class="col-sm-2 col-form-label"><strong>Kegiatan yang Ada dan Diikuti di Lingkungan Tempat Tinggal</strong></label>
                    <div class="col-sm-10">
                       <textarea name="kegiatanditempattinggal" class="form-control auto-resize" style="overflow:hidden; resize:none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('kegiatanditempattinggal',$existing_data) ?></textarea></div>
                    </div>   
                    
             <!-- Bagian Sistem Pendukung Keluarga -->
                    <div class="row mb-3">
                        <label class="col-sm-10 col-form-label text-primary">
                            <strong>5. Sistem Pendukung Keluarga</strong>
                    </div>   
                    
             <!-- Bagian Yang dimintai Pertolongan Bila Keluarga Menghadapi Masalah Keuangan/Ekonomi -->

             <div class="row mb-3">
                <label for="masalahekonomi" class="col-sm-2 col-form-label"><strong>Yang dimintai Pertolongan Bila Keluarga Menghadapi Masalah Keuangan/Ekonomi</strong></label>
                    <div class="col-sm-10">
                       <textarea name="masalahekonomi" class="form-control auto-resize" style="overflow:hidden; resize:none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('masalahekonomi',$existing_data) ?></textarea></div>
                    </div>  
                    
             <!-- Bagian Anggota Keluarga yang Sering Memberikan Support/Dukungan Bila Keluarga Menghadapi Masalah -->

             <div class="row mb-3">
                <label for="dukunganmenghadapimasalah" class="col-sm-2 col-form-label"><strong>Anggota Keluarga yang Sering Memberikan Support/Dukungan Bila Keluarga Menghadapi Masalah</strong></label>
                    <div class="col-sm-10">
                       <textarea name="dukunganmenghadapimasalah" class="form-control auto-resize" style="overflow:hidden; resize:none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('dukunganmenghadapimasalah',$existing_data) ?></textarea></div>
                    </div> 
                    
             <!-- Bagian Jenis Jaminan Sosial Kesehatan yang Dimiliki Keluarga -->

             <div class="row mb-3">
                <label for="jaminansosialkesehatan" class="col-sm-2 col-form-label"><strong>Jenis Jaminan Sosial Kesehatan yang Dimiliki Keluarga</strong></label>
                    <div class="col-sm-10">
                       <textarea name="jaminansosialkesehatan" class="form-control auto-resize" style="overflow:hidden; resize:none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('jaminansosialkesehatan',$existing_data) ?></textarea></div>
                    </div>  

            <h5 class="card-title"><strong>VI. Struktur Keluarga</strong></h5>
            
             <!-- Bagian Pola Komunikasi Keluarga -->
                    <div class="row mb-3">
                        <label class="col-sm-10 col-form-label text-primary">
                            <strong>1. Pola Komunikasi Keluarga</strong>
                    </div> 

             <!-- Bagian Komunikasi Antar Keluarga -->

             <div class="row mb-3">
                <label for="komunikasiantarkeluarga" class="col-sm-2 col-form-label"><strong>Komunikasi Antar Keluarga</strong></label>
                    <div class="col-sm-10">
                       <textarea name="komunikasiantarkeluarga" class="form-control auto-resize" style="overflow:hidden; resize:none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('komunikasiantarkeluarga',$existing_data) ?></textarea></div>
                    </div> 
                    
             <!-- Bagian Bahasa yang Sering Digunakan -->

             <div class="row mb-3">
                <label for="bahasayangseringdigunakan" class="col-sm-2 col-form-label"><strong>Bahasa yang Sering Digunakan</strong></label>
                    <div class="col-sm-10">
                      <textarea name="bahasayangseringdigunakan" class="form-control auto-resize" style="overflow:hidden; resize:none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('bahasayangseringdigunakan',$existing_data) ?></textarea></div>
                    </div> 
                    
            <!-- Bagian Anggota Keluarga yang Memiliki Telepon/Ponsel -->

             <div class="row mb-3">
                <label for="memilikiponsel" class="col-sm-2 col-form-label"><strong>Anggota Keluarga yang Memiliki Telepon/Ponsel</strong></label>
                    <div class="col-sm-10">
                       <textarea name="memilikiponsel" class="form-control auto-resize" style="overflow:hidden; resize:none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('memilikiponsel',$existing_data) ?></textarea></div>
                    </div> 
                    
             <!-- Bagian Lama/Waktu Pembatasan Anak Menggunakan Ponsel -->

             <div class="row mb-3">
                <label for="waktuanak" class="col-sm-2 col-form-label"><strong>Lama/Waktu Pembatasan Anak Menggunakan Ponsel</strong></label>
                    <div class="col-sm-10">
                       <textarea name="waktuanak" class="form-control auto-resize" style="overflow:hidden; resize:none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('waktuanak',$existing_data) ?></textarea></div>
                    </div> 
                    
             <!-- Bagian Struktur Peran Keluarga -->
                    <div class="row mb-3">
                        <label class="col-sm-10 col-form-label text-primary">
                            <strong>2. Struktur Peran Keluarga</strong>
                    </div> 
                    
             <!-- Bagian Peran Kepala Keluarga -->

             <div class="row mb-3">
                <label for="perankepalakeluarga" class="col-sm-2 col-form-label"><strong>Peran Kepala Keluarga</strong></label>
                    <div class="col-sm-10">
                       <textarea name="perankepalakeluarga" class="form-control auto-resize" style="overflow:hidden; resize:none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('perankepalakeluarga',$existing_data) ?></textarea></div>
                    </div> 
                    
             <!-- Bagian Peran Istri dalam Keluarga -->

             <div class="row mb-3">
                <label for="peranistridalamkeluarga" class="col-sm-2 col-form-label"><strong>Peran Istri dalam Keluarga</strong></label>
                    <div class="col-sm-10">
                       <textarea name="peranistridalamkeluarga" class="form-control auto-resize" style="overflow:hidden; resize:none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('peranistridalamkeluarga',$existing_data) ?></textarea></div>
                    </div>   
                    
             <!-- Bagian Peran Anak dalam Keluarga -->

             <div class="row mb-3">
                <label for="perananakdalamkeluarga" class="col-sm-2 col-form-label"><strong>Peran Anak dalam Keluarga</strong></label>
                    <div class="col-sm-10">
                       <textarea name="perananakdalamkeluarga" class="form-control auto-resize" style="overflow:hidden; resize:none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('perananakdalamkeluarga',$existing_data) ?></textarea></div>
                    </div> 
                    
             <!-- Bagian Nilai atau Norma Keluarga -->
                    <div class="row mb-3">
                        <label class="col-sm-10 col-form-label text-primary">
                            <strong>3. Nilai atau Norma Keluarga</strong>
                    </div>    
                    
             <!-- Bagian Kebiasaan di Rumah yang Diterapkan Mengikuti Adat/Budaya/Suku -->

             <div class="row mb-3">
                <label for="kebiasaandirumah" class="col-sm-2 col-form-label"><strong>Kebiasaan di Rumah yang Diterapkan mengikuti Adat/Budaya/Suku</strong></label>
                    <div class="col-sm-10">
                       <textarea name="kebiasaandirumah" class="form-control auto-resize" style="overflow:hidden; resize:none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('kebiasaandirumah',$existing_data) ?></textarea></div>
                    </div> 
                    
             <!-- Bagian Penerapan Nila-nilai Agama di Keluarga -->

             <div class="row mb-3">
                <label for="nilainilaiagama" class="col-sm-2 col-form-label"><strong>Penerapan Nilai-nilai Agama di Keluarga</strong></label>
                    <div class="col-sm-10">
                       <textarea name="nilainilaiagama" class="form-control auto-resize" style="overflow:hidden; resize:none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('nilainilaiagama',$existing_data) ?></textarea></div>
                    </div> 
                    
             <!-- Bagian Tanggapan Keluarga Terhadap Pergaulan Anak Baik di Lingkungan Sekitar/di Sekolah -->

             <div class="row mb-3">
                <label for="pergaulananak" class="col-sm-2 col-form-label"><strong>Tanggapan Keluarga Terhadap Pergaulan Anak Baik di Lingkungan Sekitar/di Sekolah</strong></label>
                    <div class="col-sm-10">
                       <textarea name="pergaulananak" class="form-control auto-resize" style="overflow:hidden; resize:none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('pergaulananak',$existing_data) ?></textarea></div>
                    </div>    
                    
             <!-- Bagian Struktur Kekuatan Keluarga -->
                    <div class="row mb-3">
                        <label class="col-sm-10 col-form-label text-primary">
                            <strong>4. Struktur Kekuatan Keluarga</strong>
                    </div>   
                    
             <!-- Bagian Yang Mengambil Keputusan dalam Keluarga -->

             <div class="row mb-3">
                <label for="mengambilkeputusan" class="col-sm-2 col-form-label"><strong>Yang Mengambil Keputusan dalam Keluarga</strong></label>
                    <div class="col-sm-10">
                       <textarea name="mengambilkeputusan" class="form-control auto-resize" style="overflow:hidden; resize:none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('mengambilkeputusan',$existing_data) ?></textarea></div>
                    </div> 
                    
             <!-- Bagian Peran Anggota Keluarga dalam Pengampilan Keputusan dalam Keluarga -->

             <div class="row mb-3">
                <label for="pengambilankeputusankeluarga" class="col-sm-2 col-form-label"><strong>Peran Anggota Keluarga dalam Pengambilan Keputusan dalam Keluarga</strong></label>
                    <div class="col-sm-10">
                      <textarea name="pengambilankeputusankeluarga" class="form-control auto-resize" style="overflow:hidden; resize:none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('pengambilankeputusankeluarga',$existing_data) ?></textarea></div>
                    </div>   

                    <!-- TOMBOL MAHASISWA -->
                    <?php if (!$is_dosen): ?>
                        <div class="row mb-3">
                            <div class="col-sm-12 d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </div>
                    <?php endif; ?>

                </form>
            </div>
        </div>

        <?php include dirname(__DIR__, 2) . '/partials/footer_form.php'; ?>

    </section>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.auto-resize').forEach(function(el) {
        el.style.height = 'auto';
        el.style.height = el.scrollHeight + 'px';
    });
});
</script>