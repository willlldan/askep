<?php
require_once "koneksi.php";
require_once "utils.php";

$form_id       = 30;
$section_name  = 'pengkajianlanjutan3';
$section_label = 'Pengkajian Lanjutan 3';
include dirname(__DIR__, 2) . '/partials/init_section.php';

// Load existing dynamic rows
$existing_kemandirian     = $existing_data['kemandirian']     ?? [];


// =============================================
// HANDLE POST - MAHASISWA SIMPAN DATA
// =============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $level === 'Mahasiswa') {

    if (isLocked($submission)) {
        redirectWithMessage($_SERVER['REQUEST_URI'], 'error', 'Data tidak dapat diubah karena sedang dalam proses review.');
    }

    // Tabel Kemandirian
    $kemandirian = parse_dynamic_rows($_POST['kemandirian'] ?? [], ['kunjunganke', 'perawat', 'hasil', 'menerimaperawat','pelayanankesehatan',
    'mengungkapkanmasalah', 'faskes', 'tindakankeperawatan', 'tindakanpencegahan', 'tindakanpromotif']);
    
    $data = [
        'emosikeluarga'                   => $_POST['emosikeluarga'] ?? '',
        'responmasalah'                   => $_POST['responmasalah'] ?? '',
        'harmoniskeluarga'                => $_POST['harmoniskeluarga'] ?? '',
        'interaksilingkungan'             => $_POST['interaksilingkungan'] ?? '',
        'sosialisasianak'                 => $_POST['sosialisasianak'] ?? '',
        'jumlahanak'                      => $_POST['jumlahanak'] ?? '',
        'menambahanak'                    => $_POST['menambahanak'] ?? '',
        'tanggapanjumlahanak'             => $_POST['tanggapanjumlahanak'] ?? '',
        'jeniskontrasepsi'                => $_POST['jeniskontrasepsi'] ?? '',
        'penghasilankeluargaperbulan'     => $_POST['penghasilankeluargaperbulan'] ?? '',
        'tanggapanpenghasilan'            => $_POST['tanggapanpenghasilan'] ?? '',
        'perhatiankeluarga'               => $_POST['perhatiankeluarga'] ?? '',
        'jawabantidak'                    => $_POST['jawabantidak'] ?? '',
        'mengetahuimasalahkesehatan'      => $_POST['mengetahuimasalahkesehatan'] ?? '',
        'penyebabmasalahkesehatan'        => $_POST['penyebabmasalahkesehatan'] ?? '',
        'tandadangejala'                  => $_POST['tandadangejala'] ?? '',
        'akibatmasalahkesehatan'          => $_POST['akibatmasalahkesehatan'] ?? '',
        'informasikesehatan'              => $_POST['informasikesehatan'] ?? '',
        'sembuhsendiri'                   => $_POST['sembuhsendiri'] ?? '',
        'perluberobat'                    => $_POST['perluberobat'] ?? '',
        'tidakpeduli'                     => $_POST['tidakpeduli'] ?? '',
        'upayakesehatan'                  => $_POST['upayakesehatan'] ?? '',
        'bilatidakupayakesehatan'         => $_POST['bilatidakupayakesehatan'] ?? '',
        'kebutuhanpengobatan'             => $_POST['kebutuhanpengobatan'] ?? '',
        'bilatidakupayapengobatan'        => $_POST['bilatidakupayapengobatan'] ?? '',
        'caramerawat'                     => $_POST['caramerawat'] ?? '',
        'bilatidakcaramerawat'            => $_POST['bilatidakcaramerawat'] ?? '',
        'pencegahanmasalah'               => $_POST['pencegahanmasalah'] ?? '',
        'bilatidakpencegahanmasalah'      => $_POST['bilatidakpencegahanmasalah'] ?? '',
        'memeliharalingkungan'            => $_POST['memeliharalingkungan'] ?? '',
        'bilatidakmemeliharalingkungan'   => $_POST['bilatidakmemeliharalingkungan'] ?? '',
        'tenagakesehatan'                 => $_POST['tenagakesehatan'] ?? '',
        'bilatidaktenagakesehatan'        => $_POST['bilatidaktenagakesehatan'] ?? '',
        'jenisibadah'                     => $_POST['jenisibadah'] ?? '',
        'ajaranagama'                     => $_POST['ajaranagama'] ?? '',
        'masalahbebankeluarga'            => $_POST['masalahbebankeluarga'] ?? '',
        'masalahbebankeluargalama'        => $_POST['masalahbebankeluargalama'] ?? '',
        'kemampuanterhadapstressor'       => $_POST['kemampuanterhadapstressor'] ?? '',
        'berceritadengankeluarga'         => $_POST['berceritadengankeluarga'] ?? '',
        'menyelesaikansendiri'            => $_POST['menyelesaikansendiri'] ?? '',
        'memintatanggapan'                => $_POST['memintatanggapan'] ?? '',
        'mendekatkandiri'                 => $_POST['mendekatkandiri'] ?? '',
        'seringmarah'                     => $_POST['seringmarah'] ?? '',
        'halnegatif'                      => $_POST['halnegatif'] ?? '',
        'mengalihkanbebanpikiran'         => $_POST['mengalihkanbebanpikiran'] ?? '',
        'pemeriksaanfisik'                => $_POST['pemeriksaanfisik'] ?? '',
        'harapankesehatan'                => $_POST['harapankesehatan'] ?? '',
        'harapanterhadappetugaskesehatan' => $_POST['harapanterhadappetugaskesehatan'] ?? '',

        'kemandirian'                     => $kemandirian,

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

                <h5 class="card-title"><strong>VII. Fungsi Keluarga</strong></h5>
            
            <!-- Bagian Fungsi Afeksi -->
                    <div class="row mb-3">
                        <label class="col-sm-10 col-form-label text-primary">
                            <strong>1. Fungsi Afeksi</strong>
                    </div> 

             <!-- Bagian Kedekatan Emosi Antar Anggota Keluarga -->

             <div class="row mb-3">
                <label for="emosikeluarga" class="col-sm-2 col-form-label"><strong>Kedekatan Emosi Antar Anggota Keluarga</strong></label>
                    <div class="col-sm-10">
                       <textarea name="emosikeluarga" class="form-control auto-resize" style="overflow:hidden; resize:none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('emosikeluarga',$existing_data) ?></textarea>
                         </div>
                    </div>  
                    
             <!-- Bagian Respon Anggota Keluarga Bila Ada yang Mengalami Masalah -->

             <div class="row mb-3">
                <label for="responmasalah" class="col-sm-2 col-form-label"><strong>Respon Anggota Keluarga Bila Ada yang Mengalami Masalah</strong></label>
                    <div class="col-sm-10">
                       <textarea name="responmasalah" class="form-control auto-resize" style="overflow:hidden; resize:none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('responmasalah',$existing_data) ?></textarea>
                         </div>
                    </div>    
                    
             <!-- Bagian Cara Keluarga Agar Tetap Harmonis untuk Anggota Keluarga -->

             <div class="row mb-3">
                <label for="harmoniskeluarga" class="col-sm-2 col-form-label"><strong>Cara Keluarga Agar Tetap Harmonis untuk Anggota Keluarga</strong></label>
                    <div class="col-sm-10">
                      <textarea name="harmoniskeluarga" class="form-control auto-resize" style="overflow:hidden; resize:none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('harmoniskeluarga',$existing_data) ?></textarea>
                         </div>
                    </div>
                    
             <!-- Bagian Fungsi Sosial -->
                    <div class="row mb-3">
                        <label class="col-sm-10 col-form-label text-primary">
                            <strong>2. Fungsi Sosial</strong>
                    </div>         
                    
             <!-- Bagian Interaksi Keluarga dengan Lingkungan Sekitar -->

             <div class="row mb-3">
                <label for="interaksilingkungan" class="col-sm-2 col-form-label"><strong>Interaksi Keluarga dengan Lingkungan Sekitar</strong></label>
                    <div class="col-sm-10">
                       <textarea name="interaksilingkungan" class="form-control auto-resize" style="overflow:hidden; resize:none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('interaksilingkungan',$existing_data) ?></textarea>
                         </div>
                    </div>    
                    
             <!-- Bagian Cara Keluarga Agar Anak Bersosialisasi dengan Lingkungan Sekitar -->

             <div class="row mb-3">
                <label for="sosialisasianak" class="col-sm-2 col-form-label"><strong>Cara Keluarga Agar Anak Bersosialisasi dengan Lingkungan Sekitar</strong></label>
                    <div class="col-sm-10">
                       <textarea name="sosialisasianak" class="form-control auto-resize" style="overflow:hidden; resize:none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('sosialisasianak',$existing_data) ?></textarea>
                         </div>
                    </div>
                    
             <!-- Bagian Fungsi Reproduksi -->
                    <div class="row mb-3">
                        <label class="col-sm-10 col-form-label text-primary">
                            <strong>3. Fungsi Reproduksi</strong>
                    </div> 
                    
             <!-- Bagian Jumlah Anak -->

             <div class="row mb-3">
                <label for="jumlahanak" class="col-sm-2 col-form-label"><strong>Jumlah Anak</strong></label>
                    <div class="col-sm-10">
                       <input type="text" class="form-control" name="jumlahanak"
                                value="<?= val('jumlahanak', $existing_data) ?>" <?= $ro ?>>
                         </div>
                    </div> 
                    
             <!-- Bagian Keinginan untuk Menambah Anak -->

             <div class="row mb-3">
                <label for="menambahanak" class="col-sm-2 col-form-label"><strong>Keinginan untuk Menambah Anak</strong></label>
                    <div class="col-sm-10">
                       <input type="text" class="form-control" name="menambahanak"
                                value="<?= val('menambahanak', $existing_data) ?>" <?= $ro ?>>
                         </div>
                    </div> 
                    
             <!-- Bagian Tanggapan Keluarga dengan Jumlah Anaknya Saat ini -->

             <div class="row mb-3">
                <label for="tanggapanjumlahanak" class="col-sm-2 col-form-label"><strong>Tanggapan Keluarga dengan Jumlah Anaknya Saat Ini</strong></label>
                    <div class="col-sm-10">
                       <textarea name="tanggapanjumlahanak" class="form-control auto-resize" style="overflow:hidden; resize:none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('tanggapanjumlahanak',$existing_data) ?></textarea>
                         </div>
                    </div>   
                    
             <!-- Bagian Jenis Kontrasepsi yang Digunakan -->

             <div class="row mb-3">
                <label for="jeniskontrasepsi" class="col-sm-2 col-form-label"><strong>Jenis Kontrasepsi yang Digunakan Sebelum dan Saat Ini</strong></label>
                    <div class="col-sm-10">
                       <input type="text" class="form-control" name="jeniskontrasepsi"
                                value="<?= val('jeniskontrasepsi', $existing_data) ?>" <?= $ro ?>>
                         </div>
                    </div>  
                    
             <!-- Bagian Ekonomi -->
                    <div class="row mb-3">
                        <label class="col-sm-10 col-form-label text-primary">
                            <strong>4. Fungsi Ekonomi</strong>
                    </div>    
                    
             <!-- Bagian Penghasilan Keluarga Perbulan -->

             <div class="row mb-3">
                <label for="penghasilankeluargaperbulan" class="col-sm-2 col-form-label"><strong>Penghasilan Keluarga Perbulan</strong></label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" name="penghasilankeluargaperbulan"
                                value="<?= val('penghasilankeluargaperbulan', $existing_data) ?>" <?= $ro ?>>
                         </div>
                    </div>    
                    
            <!-- Bagian Tanggapan Keluarga Tentang Penghasilan Tersebut dalam Memenuhi Kebutuhan Sehari-hari -->

             <div class="row mb-3">
                <label for="tanggapanpenghasilan" class="col-sm-2 col-form-label"><strong>Tanggapan Keluarga Tentang Penghasilan Tersebut dalam Memenuhi Kebutuhan Sehari-hari</strong></label>
                    <div class="col-sm-10">
                       <textarea name="tanggapanpenghasilan" class="form-control auto-resize" style="overflow:hidden; resize:none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('belumterpenuhi',$existing_data) ?></textarea>
                         </div>
                    </div>
                    
             <!-- Bagian Perawatan Kesehatan Keluarga -->
                    <div class="row mb-3">
                        <label class="col-sm-10 col-form-label text-primary">
                            <strong>5. Fungsi Perawatan Kesehatan Keluarga <br> a. Mengenal Masalah Keluarga</strong>
                    </div> 
                    
            <!-- Bagian Adakah Perhatian Keluarga Kepada Anggotanya yang Menderita Sakit -->
                 <div class="row mb-3">
                    <label for="perhatiankeluarga" class="col-sm-4 col-form-label"><strong>1. Adakah Perhatian Keluarga Kepada Anggotanya yang Menderita Sakit</strong></label> 
                    <div class="col-sm-8">
                    <select class="form-select" name="perhatiankeluarga" <?= $ro_select ?> >
                                <option value="">Pilih</option>
                                <option value="Ya" <?= val('perhatiankeluarga', $existing_data) === 'Ya' ? 'selected' : '' ?>>Ya</option>
                                <option value="Tidak" <?= val('perhatiankeluarga', $existing_data) === 'Tidak' ? 'selected' : '' ?>>Tidak</option>
                            </select>
                         </div>
                    </div>
                    
             <!-- Bagian Bila Jawaban Tidak, Alasanya -->

             <div class="row mb-3">
                <label for="jawabantidak" class="col-sm-4 col-form-label"><strong>Bila Jawaban Tidak, Alasannya</strong></label>
                    <div class="col-sm-8">
                       <textarea name="jawabantidak" class="form-control auto-resize" style="overflow:hidden; resize:none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('jawabantidak',$existing_data) ?></textarea>
                         </div>
                    </div>
                    
            <!-- Bagian Apakah Keluarga Mengetahui Masalah Kesehatan yang Dialami Anggota dalam Keluarganya -->
                 <div class="row mb-3">
                    <label for="mengetahuimasalahkesehatan" class="col-sm-4 col-form-label"><strong>2. Apakah Keluarga Mengetahui Masalah Kesehatan yang Dialami Anggota dalam Keluarganya</strong></label> 
                    <div class="col-sm-8">
                    <select class="form-select" name="mengetahuimasalahkesehatan" <?= $ro_select ?> >
                                <option value="">Pilih</option>
                                <option value="Ya" <?= val('mengetahuimasalahkesehatan', $existing_data) === 'Ya' ? 'selected' : '' ?>>Ya</option>
                                <option value="Tidak" <?= val('mengetahuimasalahkesehatan', $existing_data) === 'Tidak' ? 'selected' : '' ?>>Tidak</option>
                            </select>
                         </div>
                    </div>
                    
            <!-- Bagian Apakah Keluarga Mengetahui Penyebab Masalah Kesehatan yang dialami Anggota dalam Keluarganya -->
                 <div class="row mb-3">
                    <label for="penyebabmasalahkesehatan" class="col-sm-4 col-form-label"><strong>3. Apakah Keluarga Mengetahui Penyebab Masalah Kesehatan yang Dialami Anggota dalam Keluarganya</strong></label> 
                    <div class="col-sm-8">
                   <select class="form-select" name="penyebabmasalahkesehatan" <?= $ro_select ?> >
                                <option value="">Pilih</option>
                                <option value="Ya" <?= val('penyebabmasalahkesehatan', $existing_data) === 'Ya' ? 'selected' : '' ?>>Ya</option>
                                <option value="Tidak" <?= val('penyebabmasalahkesehatan', $existing_data) === 'Tidak' ? 'selected' : '' ?>>Tidak</option>
                            </select>
                         </div>
                    </div>
                    
            <!-- Bagian Apakah Keluarga Mengetahui Tanda dan Gejala Masalah Kesehatan yang Dialami Anggota dalam Keluarganya -->
                 <div class="row mb-3">
                    <label for="tandadangejala" class="col-sm-4 col-form-label"><strong>4. Apakah Keluarga Mengetahui Tanda dan Gejala Masalah Kesehatan yang Dialami Anggota dalam Keluarganya</strong></label> 
                    <div class="col-sm-8">
                    <select class="form-select" name="tandadangejala" <?= $ro_select ?> >
                                <option value="">Pilih</option>
                                <option value="Ya" <?= val('tandadangejala', $existing_data) === 'Ya' ? 'selected' : '' ?>>Ya</option>
                                <option value="Tidak" <?= val('tandadangejala', $existing_data) === 'Tidak' ? 'selected' : '' ?>>Tidak</option>
                            </select>
                         </div>
                    </div>        

                 <!-- Bagian Mengambil Keputusan -->
                    <div class="row mb-3">
                        <label class="col-sm-10 col-form-label text-primary">
                            <strong>b. Mengambil Keputusan</strong>
                    </div> 
                    
                <!-- Bagian Apakah Keluarga Mengetahui Akibat Masalah Kesehatan yang Dialami Anggota Dalam Keluarganya Bila Tidak Diobati/Dirawat -->
                 <div class="row mb-3">
                    <label for="akibatmasalahkesehatan" class="col-sm-4 col-form-label"><strong>1. Apakah Keluarga Mengetahui Akibat Masalah Kesehatan yang Dialami Anggota dalam Keluarganya Bila Tidak Diobati/Dirawat</strong></label> 
                    <div class="col-sm-8">
                    <select class="form-select" name="akibatmasalahkesehatan" <?= $ro_select ?> >
                                <option value="">Pilih</option>
                                <option value="Ya" <?= val('akibatmasalahkesehatan', $existing_data) === 'Ya' ? 'selected' : '' ?>>Ya</option>
                                <option value="Tidak" <?= val('akibatmasalahkesehatan', $existing_data) === 'Tidak' ? 'selected' : '' ?>>Tidak</option>
                            </select>
                         </div>
                    </div>  
                    
             <!-- Bagian Pada Siapa Keluarga Biasa Menggali Informasi Tentang Masalah Kesehatan yang Dialami Anggota Keluarganya -->

             <div class="row mb-3">
                <label for="informasikesehatan" class="col-sm-4 col-form-label"><strong>2. Pada Siapa Keluarga Biasa Menggali Informasi Tentang Masalah Kesehatan yang Dialami Anggota Keluarganya</strong></label>
                    <div class="col-sm-8">
                       <textarea name="informasikesehatan" class="form-control auto-resize" style="overflow:hidden; resize:none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('informasikesehatan',$existing_data) ?></textarea>
                         </div>
                    </div>  
                    
             <!-- Bagian Keyakinan Keluarga Tentang Masalah Kesehatan yang Dialami Anggota Keluarganya -->
                    <div class="row mb-3">
                        <label class="col-sm-10 col-form-label">
                            <strong>3. Keyakinan Keluarga Tentang Masalah Kesehatan yang Dialami Anggota Keluarganya</strong>
                    </div> 
                    
            <!-- Bagian Tidak Perlu ditangani karena Akan Sembuh Sendiri Biasanya -->
                 <div class="row mb-3">
                    <label for="sembuhsendiri" class="col-sm-4 col-form-label"><strong>- Tidak Perlu ditangani karena Akan Sembuh Sendiri Biasanya</strong></label> 
                    <div class="col-sm-8">
                    <select class="form-select" name="sembuhsendiri" <?= $ro_select ?> >
                                <option value="">Pilih</option>
                                <option value="Ya" <?= val('sembuhsendiri', $existing_data) === 'Ya' ? 'selected' : '' ?>>Ya</option>
                                <option value="Tidak" <?= val('sembuhsendiri', $existing_data) === 'Tidak' ? 'selected' : '' ?>>Tidak</option>
                            </select>
                         </div>
                    </div>  
                    
            <!-- Bagian Perlu Berobat ee Fasilitas Yankes -->
                 <div class="row mb-3">
                    <label for="perluberobat" class="col-sm-4 col-form-label"><strong>- Perlu Berobat ke Fasilitas Yankes</strong></label> 
                    <div class="col-sm-8">
                    <select class="form-select" name="perluberobat" <?= $ro_select ?> >
                                <option value="">Pilih</option>
                                <option value="Ya" <?= val('perluberobat', $existing_data) === 'Ya' ? 'selected' : '' ?>>Ya</option>
                                <option value="Tidak" <?= val('perluberobat', $existing_data) === 'Tidak' ? 'selected' : '' ?>>Tidak</option>
                            </select>
                         </div>
                    </div>   
                    
            <!-- Bagian Tidak Terpikir/Tidak Peduli/Cuek -->
                 <div class="row mb-3">
                    <label for="tidakpeduli" class="col-sm-4 col-form-label"><strong>- Tidak Terpikir/Tidak Peduli/Cuek</strong></label> 
                    <div class="col-sm-8">
                    <select class="form-select" name="tidakpeduli" <?= $ro_select ?> >
                                <option value="">Pilih</option>
                                <option value="Ya" <?= val('tidakpeduli', $existing_data) === 'Ya' ? 'selected' : '' ?>>Ya</option>
                                <option value="Tidak" <?= val('tidakpeduli', $existing_data) === 'Tidak' ? 'selected' : '' ?>>Tidak</option>
                            </select>
                         </div>
                    </div>  
                    
            <!-- Bagian Apakah Keluarga Melakukan Upaya Peningkatan Kesehatan yang dialami Anggota Keluarganya Secara Aktif -->
                 <div class="row mb-3">
                    <label for="upayakesehatan" class="col-sm-4 col-form-label"><strong>4. Apakah Keluarga Melakukan Upaya-upaya Peningkatan Kesehatan yang dialami Anggota Keluarganya Secara Aktif</strong></label> 
                    <div class="col-sm-8">
                    <select class="form-select" name="upayakesehatan" <?= $ro_select ?> >
                                <option value="">Pilih</option>
                                <option value="Ya" <?= val('upayakesehatan', $existing_data) === 'Ya' ? 'selected' : '' ?>>Ya</option>
                                <option value="Tidak" <?= val('upayakesehatan', $existing_data) === 'Tidak' ? 'selected' : '' ?>>Tidak</option>
                            </select>
                         </div>
                    </div> 
                    
             <!-- Bagian Bila Tidak, Jelaskan -->

             <div class="row mb-3">
                <label for="bilatidakupayakesehatan" class="col-sm-4 col-form-label"><strong>Bila Tidak, Jelaskan</strong></label>
                    <div class="col-sm-8">
                      <textarea name="bilatidakupayakesehatan" class="form-control auto-resize" style="overflow:hidden; resize:none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('bilatidakupayakesehatan',$existing_data) ?></textarea>
                         </div>
                    </div>   
                    
            <!-- Bagian Apakah Keluarga Mengetahui Kebutuhan Pengobatan Masalah Kesehatan yang dialami Anggota Keluarganya -->
                 <div class="row mb-3">
                    <label for="kebutuhanpengobatan" class="col-sm-4 col-form-label"><strong>5. Apakah Keluarga Mengetahui Kebutuhan Pengobatan Masalah Kesehatan yang Dialami Anggota dalam Keluarganya</strong></label> 
                    <div class="col-sm-8">
                    <select class="form-select" name="kebutuhanpengobatan" <?= $ro_select ?> >
                                <option value="">Pilih</option>
                                <option value="Ya" <?= val('kebutuhanpengobatan', $existing_data) === 'Ya' ? 'selected' : '' ?>>Ya</option>
                                <option value="Tidak" <?= val('kebutuhanpengobatan', $existing_data) === 'Tidak' ? 'selected' : '' ?>>Tidak</option>
                            </select>
                         </div>
                    </div>  
                    
             <!-- Bagian Bila Tidak, Jelaskan -->

             <div class="row mb-3">
                <label for="bilatidakupayapengobatan" class="col-sm-4 col-form-label"><strong>Bila Tidak, Jelaskan</strong></label>
                    <div class="col-sm-8">
                       <textarea name="bilatidakupayapengobatan" class="form-control auto-resize" style="overflow:hidden; resize:none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('bilatidakupayapengobatan',$existing_data) ?></textarea>
                         </div>
                    </div>  
                    
             <!-- Bagian Merawat Anggota Keluarga yang Sakit -->
                    <div class="row mb-3">
                        <label class="col-sm-10 col-form-label text-primary">
                            <strong>c. Merawat Anggota Keluarga yang Sakit</strong>
                    </div> 
                    
            <!-- Bagian Apakah Keluarga Dapat Melakukan Cara Merawat Anggota Keluarga Dengan Masalah Kesehatan yang dialaminya -->
                 <div class="row mb-3">
                    <label for="caramerawat" class="col-sm-4 col-form-label"><strong>Apakah Keluarga Dapat Melakukan Cara Merawat Anggota Keluarga dengan Masalah Kesehatan yang dialaminya</strong></label> 
                    <div class="col-sm-8">
                   <select class="form-select" name="caramerawat" <?= $ro_select ?> >
                                <option value="">Pilih</option>
                                <option value="Ya" <?= val('caramerawat', $existing_data) === 'Ya' ? 'selected' : '' ?>>Ya</option>
                                <option value="Tidak" <?= val('caramerawat', $existing_data) === 'Tidak' ? 'selected' : '' ?>>Tidak</option>
                            </select>
                         </div>
                    </div>  
                    
             <!-- Bagian Bila Tidak, Jelaskan -->

             <div class="row mb-3">
                <label for="bilatidakcaramerawat" class="col-sm-4 col-form-label"><strong>Bila Tidak, Jelaskan</strong></label>
                    <div class="col-sm-8">
                       <textarea name="bilatidakcaramerawat" class="form-control auto-resize" style="overflow:hidden; resize:none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('bilatidakcaramerawat',$existing_data) ?></textarea>
                         </div>
                    </div>  

             <!-- Bagian Memodifikasi Lingkungan -->
                    <div class="row mb-3">
                        <label class="col-sm-10 col-form-label text-primary">
                            <strong>d. Memodifikasi Lingkungan</strong>
                    </div>         
                    
            <!-- Bagian Apakah Keluarga Dapat Melakukan Pencegahan Masalah Kesehatan yang dialami Anggota Keluarganya -->
                 <div class="row mb-3">
                    <label for="pencegahanmasalah" class="col-sm-4 col-form-label"><strong>1. Apakah Keluarga Dapat Melakukan Pencegahan Masalah Kesehatan yang Dialami Anggota dalam Keluarganya</strong></label> 
                    <div class="col-sm-8">
                    <select class="form-select" name="pencegahanmasalah" <?= $ro_select ?> >
                                <option value="">Pilih</option>
                                <option value="Ya" <?= val('pencegahanmasalah', $existing_data) === 'Ya' ? 'selected' : '' ?>>Ya</option>
                                <option value="Tidak" <?= val('pencegahanmasalah', $existing_data) === 'Tidak' ? 'selected' : '' ?>>Tidak</option>
                            </select>
                         </div>
                    </div>  
                    
             <!-- Bagian Bila Tidak, Jelaskan -->

             <div class="row mb-3">
                <label for="bilatidakpencegahanmasalah" class="col-sm-4 col-form-label"><strong>Bila Tidak, Jelaskan</strong></label>
                    <div class="col-sm-8">
                       <textarea name="bilatidakpencegahanmasalah" class="form-control auto-resize" style="overflow:hidden; resize:none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('bilatidakpencegahanmasalah',$existing_data) ?></textarea>
                         </div>
                    </div>  

            <!-- Bagian Apakah Keluarga Mampu Memelihara atau Memodifikasi Lingkungan yang Mendukung Kesehatan Anggota Keluarga yang Mengalami Masalah Kesehatan -->
                 <div class="row mb-3">
                    <label for="memeliharalingkungan" class="col-sm-4 col-form-label"><strong>2. Apakah Keluarga Mampu Memelihara atau Memodifikasi Lingkungan yang Mendukung Kesehatan Anggota Keluarga yang Mengalami Masalah Kesehatan</strong></label> 
                    <div class="col-sm-8">
                    <select class="form-select" name="memeliharanlingkungan" <?= $ro_select ?> >
                                <option value="">Pilih</option>
                                <option value="Ya" <?= val('memeliharalingkungan', $existing_data) === 'Ya' ? 'selected' : '' ?>>Ya</option>
                                <option value="Tidak" <?= val('memeliharalingkungan', $existing_data) === 'Tidak' ? 'selected' : '' ?>>Tidak</option>
                            </select>
                         </div>
                    </div>  
                    
             <!-- Bagian Bila Tidak, Jelaskan -->

             <div class="row mb-3">
                <label for="bilatidakmemeliharalingkungan" class="col-sm-4 col-form-label"><strong>Bila Tidak, Jelaskan</strong></label>
                    <div class="col-sm-8">
                       <textarea name="bilatidakmemeliharalingkungan" class="form-control auto-resize" style="overflow:hidden; resize:none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('bilatidakmemeliharalingkungan',$existing_data) ?></textarea>
                         </div>
                    </div>          

             <!-- Bagain Memanfaatkan Fasilitas Kesehatan -->
                    <div class="row mb-3">
                        <label class="col-sm-10 col-form-label text-primary">
                            <strong>e. Memanfaatkan Fasilitas Kesehatan</strong>
                    </div> 
                     
            <!-- Bagian Apakah Keluarga Mampu Mengganggi dan Memanfaatkan Tenaga Kesehatan di Masyarakat untuk Mengatasi Masalah Kesehatan Anggota Keluarganya -->
                 <div class="row mb-3">
                    <label for="tenagakesehatan" class="col-sm-4 col-form-label"><strong>Apakah Keluarga Mampu Menggali dan Memanfaatkan Tenaga Kesehatan di Masyarakat untuk Mengatasi Masalah Kesehatan Anggota Keluarganya</strong></label> 
                    <div class="col-sm-8">
                    <select class="form-select" name="tenagakesehatan" <?= $ro_select ?> >
                                <option value="">Pilih</option>
                                <option value="Ya" <?= val('tenagakesehatan', $existing_data) === 'Ya' ? 'selected' : '' ?>>Ya</option>
                                <option value="Tidak" <?= val('tenagakesehatan', $existing_data) === 'Tidak' ? 'selected' : '' ?>>Tidak</option>
                            </select>
                         </div>
                    </div>  
                    
             <!-- Bagian Bila Tidak, Jelaskan -->

             <div class="row mb-3">
                <label for="bilatidaktenagakesehatan" class="col-sm-4 col-form-label"><strong>Bila Tidak, Jelaskan</strong></label>
                    <div class="col-sm-8">
                       <textarea name="bilatidaktenagakesehatan" class="form-control auto-resize" style="overflow:hidden; resize:none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('bilatidaktenagakesehatan',$existing_data) ?></textarea>
                         </div>
                    </div>  

             <!-- Bagian Fungsi Religius -->
                    <div class="row mb-3">
                        <label class="col-sm-10 col-form-label text-primary">
                            <strong>6. Fungsi Religius</strong>
                    </div>    
                    
             <!-- Bagian Jenis Ibadah yang Dijalakan Keluarga -->

             <div class="row mb-3">
                <label for="jenisibadah" class="col-sm-2 col-form-label"><strong>Jenis Ibadah yang Dijalankan Keluarga</strong></label>
                    <div class="col-sm-10">
                       <input type="text" class="form-control" name="jenisibadah"
                                value="<?= val('jenisibadah', $existing_data) ?>" <?= $ro ?>>
                         </div>
                    </div>   
                    
             <!-- Bagian Usia Anak diperkenalkan Tentang Ajaran Agama -->

             <div class="row mb-3">
                <label for="ajaranagama" class="col-sm-2 col-form-label"><strong>Usia Anak diperkenalkan Tentang Ajaran Agama</strong></label>
                    <div class="col-sm-10">
                       <input type="text" class="form-control" name="ajaranagama"
                                value="<?= val('ajaranagama', $existing_data) ?>" <?= $ro ?>>
                         </div>
                    </div>  
                    
            <h5 class="card-title"><strong>VIII. Stress dan Koping Keluarga</strong></h5>
            
             <!-- Bagian Stressor Jangka Pendek dan Panjang -->
                    <div class="row mb-3">
                        <label class="col-sm-10 col-form-label">
                            <strong>1. Stressor Jangka Pendek dan Panjang</strong>
                    </div> 

             <!-- Bagian Masalah/Beban Pikiran Keluarga Saat Ini -->

             <div class="row mb-3">
                <label for="masalahbebankeluarga" class="col-sm-4 col-form-label"><strong>a. Masalah/Beban Pikiran Saat Ini</strong></label>
                    <div class="col-sm-8">
                       <textarea name="masalahbebankeluarga" class="form-control auto-resize" style="overflow:hidden; resize:none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('masalahbebankeluarga',$existing_data) ?></textarea>
                         </div>
                    </div>  
                    
             <!-- Bagian Masalah/Beban Pikiran yang Sudah Lama dirasakan (Lebih Dari 6 Bulan) -->

             <div class="row mb-3">
                <label for="masalahbebankeluargalama" class="col-sm-4 col-form-label"><strong>b. Masalah/Beban Pikiran yang Sudah Lama dirasakan (Lebih Dari 6 Bulan)</strong></label>
                    <div class="col-sm-8">
                       <textarea name="masalahbebankeluargalama" class="form-control auto-resize" style="overflow:hidden; resize:none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('masalahbebankeluargalama',$existing_data) ?></textarea>
                         </div>
                    </div> 
                    
             <!-- Bagian Kemampuan/Tanggapan Keluarga Terhadap Stressor -->

             <div class="row mb-3">
                <label for="kemampuanterhadapstressor" class="col-sm-4 col-form-label"><strong>2. Kemampuan/Tanggapan Keluarga Terhadap Stressor</strong></label>
                    <div class="col-sm-8">
                       <textarea name="kemampuanterhadapstressor" class="form-control auto-resize" style="overflow:hidden; resize:none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('kemampuanterhadapstressor',$existing_data) ?></textarea>
                         </div>
                    </div> 
                    
             <!-- Bagian Strategi Koping yang digunakan -->
                <div class="row mb-3">
                        <label class="col-sm-10 col-form-label">
                            <strong>3. Strategi Koping yang digunakan</strong>
                    </div> 
                    
            <!-- Bagian Bercerita dengan Keluarga -->
                 <div class="row mb-3">
                    <label for="berceritadengankeluarga" class="col-sm-4 col-form-label"><strong>a. Bercerita dengen Keluarga</strong></label> 
                    <div class="col-sm-8">
                    <select class="form-select" name="berceritadengankeluarga" <?= $ro_select ?> >
                                <option value="">Pilih</option>
                                <option value="Ya" <?= val('berceritadengankeluarga', $existing_data) === 'Ya' ? 'selected' : '' ?>>Ya</option>
                                <option value="Tidak" <?= val('berceritadengankeluarga', $existing_data) === 'Tidak' ? 'selected' : '' ?>>Tidak</option>
                            </select>
                         </div>
                    </div>  
                    
            <!-- Bagian Menyelesaikan Sendiri -->
                 <div class="row mb-3">
                    <label for="menyelesaikansendiri" class="col-sm-4 col-form-label"><strong>b. Menyelesaikan Sendiri</strong></label> 
                    <div class="col-sm-8">
                   <select class="form-select" name="menyelesaikansendiri" <?= $ro_select ?> >
                                <option value="">Pilih</option>
                                <option value="Ya" <?= val('menyelesaisendiri', $existing_data) === 'Ya' ? 'selected' : '' ?>>Ya</option>
                                <option value="Tidak" <?= val('menyelesaikansendiri', $existing_data) === 'Tidak' ? 'selected' : '' ?>>Tidak</option>
                            </select>
                         </div>
                    </div> 
                    
            <!-- Bagian Meminta Tanggapan dari Teman yang dipercaya -->
                 <div class="row mb-3">
                    <label for="memintatanggapan" class="col-sm-4 col-form-label"><strong>c. Meminta Tanggapan dari Teman yang dipercaya</strong></label> 
                    <div class="col-sm-8">
                    <select class="form-select" name="memintatanggapan" <?= $ro_select ?> >
                                <option value="">Pilih</option>
                                <option value="Ya" <?= val('memintatanggapan', $existing_data) === 'Ya' ? 'selected' : '' ?>>Ya</option>
                                <option value="Tidak" <?= val('memintatanggapan', $existing_data) === 'Tidak' ? 'selected' : '' ?>>Tidak</option>
                            </select>
                         </div>
                    </div>    
                    
            <!-- Bagian Lebih Mendekatkan Diri Pada Tuhan Yang Maha Esa -->
                 <div class="row mb-3">
                    <label for="mendekatkandiri" class="col-sm-4 col-form-label"><strong>d. Lebih Mendekatkan Diri Pada Tuhan Yang Maha Esa</strong></label> 
                    <div class="col-sm-8">
                    <select class="form-select" name="mendekatkandiri" <?= $ro_select ?> >
                                <option value="">Pilih</option>
                                <option value="Ya" <?= val('mendekatkandiri', $existing_data) === 'Ya' ? 'selected' : '' ?>>Ya</option>
                                <option value="Tidak" <?= val('mendekatkandiri', $existing_data) === 'Tidak' ? 'selected' : '' ?>>Tidak</option>
                            </select>
                         </div>
                    </div>   
                    
             <!-- Bagian Strategi Adapatasi Disfungsional -->
                    <div class="row mb-3">
                        <label class="col-sm-10 col-form-label">
                            <strong>4. Strategi Adaptasi Fungsional</strong>
                    </div>  
                    
            <!-- Bagian Sering Marah -->
                 <div class="row mb-3">
                    <label for="seringmarah" class="col-sm-4 col-form-label"><strong>a. Sering Marah</strong></label> 
                    <div class="col-sm-8">
                    <select class="form-select" name="seringmarah" <?= $ro_select ?> >
                                <option value="">Pilih</option>
                                <option value="Ya" <?= val('seringmarah', $existing_data) === 'Ya' ? 'selected' : '' ?>>Ya</option>
                                <option value="Tidak" <?= val('seringmarah', $existing_data) === 'Tidak' ? 'selected' : '' ?>>Tidak</option>
                            </select>
                         </div>
                    </div>   
                    
            <!-- Bagian Mengalihkan ke Hal yang Negatif seperti Mengkonsumsi Minuman Alkohol -->
                 <div class="row mb-3">
                    <label for="halnegatif" class="col-sm-4 col-form-label"><strong>b. Mengalihkan ke Hal Negatif seperti Mengkonsumsi Minuman Alkohol</strong></label> 
                    <div class="col-sm-8">
                    <select class="form-select" name="halnegatif" <?= $ro_select ?> >
                                <option value="">Pilih</option>
                                <option value="Ya" <?= val('halnegatif', $existing_data) === 'Ya' ? 'selected' : '' ?>>Ya</option>
                                <option value="Tidak" <?= val('halnegatif', $existing_data) === 'Tidak' ? 'selected' : '' ?>>Tidak</option>
                            </select>
                         </div>
                    </div>   
                    
            <!-- Bagian Mengalihkan Beban Pikiran dengan Merokok -->
                 <div class="row mb-3">
                    <label for="mengalihkanbebanpikiran" class="col-sm-4 col-form-label"><strong>c. Mengalihkan Beban Pikiran dengan Merokok</strong></label> 
                    <div class="col-sm-8">
                    <select class="form-select" name="mengalihkanbebanpikiran" <?= $ro_select ?> >
                                <option value="">Pilih</option>
                                <option value="Ya" <?= val('mengalihkanbebanpikiran', $existing_data) === 'Ya' ? 'selected' : '' ?>>Ya</option>
                                <option value="Tidak" <?= val('mengalihkanbebanpikiran', $existing_data) === 'Tidak' ? 'selected' : '' ?>>Tidak</option>
                            </select>
                         </div>
                    </div>               
                    
            <h5 class="card-title"><strong>IX. Pemeriksaan Fisik</strong></h5> 

            <!-- Bagian Pemeriksaan Fisik -->
            <div class="row mb-3">
                    <label for="pemeriksaanfisik" class="col-sm-2 col-form-label"><strong>Pemeriksaan Fisik</strong></label>

                    <div class="col-sm-10">
                        <input type="text" name="pemeriksaanfisik" class="form-control" placeholder="Lampirkan link Google Drive upload gambar Pemeriksaan Fisik"
                            value="<?= val('pemeriksaanfisik', $existing_data) ?>"
                            <?= $ro ?>>
                    </div>
                </div> 

            <h5 class="card-title"><strong>X. Harapan Keluarga</strong></h5> 

             <!-- Bagian Harapan Keluarga Terhadap Kesehatannya -->

             <div class="row mb-3">
                <label for="harapankesehatan" class="col-sm-2 col-form-label"><strong>1. Harapan Terhadap Kesehatannya</strong></label>
                    <div class="col-sm-10">
                       <textarea name="harapankesehatan" class="form-control auto-resize" style="overflow:hidden; resize:none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('harapankesehatan',$existing_data) ?></textarea>
                         </div>
                    </div>   

             <!-- Bagian Harapan Keluarga Terhadap Petugas Kesehatan -->

             <div class="row mb-3">
                <label for="harapanterhadappetugaskesehatan" class="col-sm-2 col-form-label"><strong>2. Harapan Keluarga Terhadap Petugas Kesehatan</strong></label>
                    <div class="col-sm-10">
                       <textarea name="harapanterhadappetugaskesehatan" class="form-control auto-resize" style="overflow:hidden; resize:none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                            <?= $ro ?>><?= val('harapanterhadappetugaskesehatan',$existing_data) ?></textarea>
                         </div>
                    </div>       
                    
            <h5 class="card-title"><strong>XI. Tingkat Kemandirian Keluarga</strong></h5> 

            <!-- Tabel Kemandirian -->

            <table class="table table-bordered" id="tabel-kemandirian">
                        <thead>
                            <tr>
                                <th class="text-center" style="width:40px">No</th>
                                <th class="text-center align-middle">Kunjungan Ke</th>
                                <th class="text-center align-middle">Perawat</th>
                                <th class="text-center align-middle">Hasil</th>
                                <th class="text-center align-middle">Kriteria Tingkat Kemandirian Keluarga</th>
                                <?php if (!$is_readonly): ?>
                                    <th class="text-center" style="width:60px">Aksi</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody id="tbody-kemandirian"></tbody>
                    </table>

                    <?php if (!$is_readonly): ?>
                        <div class="row mb-3">
                            <div class="col-sm-12 d-flex justify-content-end">
                                <button type="button" class="btn btn-primary btn-sm"
                                    onclick="tambahRowKemandirian()">+ Tambah Baris</button>
                            </div>
                        </div>
                    <?php endif; ?>
          
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

<script>
const isReadonly = <?= json_encode($is_readonly) ?>;
const existingKemandirian = <?= json_encode($existing_kemandirian ?? []) ?>;

let rowKemandirianCount = 1;

function hapusRow(btn) {
    btn.closest('tr').remove();
}

function autoResizeTextarea(el) {
    el.style.height = 'auto';
    el.style.height = el.scrollHeight + 'px';
}


function mkTextarea(name, value = '', rows = 2) {
    return `
        <textarea
            class="form-control form-control-sm"
            name="${name}"
            rows="${rows}"
            style="resize:none;overflow:hidden;"
            oninput="this.style.height='auto';this.style.height=this.scrollHeight+'px';"
            ${isReadonly ? 'readonly' : ''}
        >${value ?? ''}</textarea>
    `;
}

const aksiCol = isReadonly ? '' : `
<td class="text-center align-middle">
    <button type="button"
            class="btn btn-danger btn-sm"
            onclick="hapusRow(this)">
        x
    </button>
</td>
`;

function tambahRowKemandirian(data = null) {

    const tbody = document.getElementById('tbody-kemandirian');

    if (!tbody) {
        console.error('tbody-kemandirian tidak ditemukan');
        return;
    }

    const index = rowKemandirianCount;

    const row = document.createElement('tr');

    row.innerHTML = `
        <td class="text-center align-middle">${index}</td>

        <td>
            ${mkTextarea(
                `kemandirian[${index}][kunjunganke]`,
                data?.kunjunganke
            )}
        </td>

        <td>
            ${mkTextarea(
                `kemandirian[${index}][perawat]`,
                data?.perawat
            )}
        </td>

        <td>
        ${
            isReadonly
                ? `<div class="readonly-text">${data?.hasil ?? ''}</div>`
                : `<textarea
                        class="form-control form-control-sm auto-resize"
                        name="kemandirian[${index}][hasil]"
                        rows="2"
                        style="resize:none; overflow:hidden;"
                        oninput="autoResizeTextarea(this)"
                        >${data?.hasil ?? ''}</textarea>`
        }
        </td>

        <td>

            <!-- 1 -->
            <div id="row${index}_1" class="mb-2">
                <strong>1. Keluarga Menerima Perawat</strong><br>

                <label class="me-2">
                    <input type="radio"
                        name="kemandirian[${index}][menerimaperawat]"
                        value="ya"
                        ${data?.menerimaperawat === 'ya' ? 'checked' : ''}
                        onclick="nextQuestion(${index},1,true)"
                        ${isReadonly ? 'disabled' : ''}>
                    Ya
                </label>

                <label>
                    <input type="radio"
                        name="kemandirian[${index}][menerimaperawat]"
                        value="tidak"
                        ${data?.menerimaperawat === 'tidak' ? 'checked' : ''}
                        onclick="nextQuestion(${index},1,false)"
                        ${isReadonly ? 'disabled' : ''}>
                    Tidak
                </label>
            </div>

            <!-- 2 -->
            <div id="row${index}_2" class="mb-2" style="display:none;">
                <strong>2. Keluarga Menerima Pelayanan Kesehatan</strong><br>

                <label class="me-2">
                    <input type="radio"
                        name="kemandirian[${index}][pelayanankesehatan]"
                        value="ya"
                        ${data?.pelayanankesehatan === 'ya' ? 'checked' : ''}
                        onclick="nextQuestion(${index},2,true)"
                        ${isReadonly ? 'disabled' : ''}>
                    Ya
                </label>

                <label>
                    <input type="radio"
                        name="kemandirian[${index}][pelayanankesehatan]"
                        value="tidak"
                        ${data?.pelayanankesehatan === 'tidak' ? 'checked' : ''}
                        onclick="nextQuestion(${index},2,false)"
                        ${isReadonly ? 'disabled' : ''}>
                    Tidak
                </label>
            </div>

            <!-- 3 -->
            <div id="row${index}_3" class="mb-2" style="display:none;">
                <strong>3. Keluarga Tahu dan Mengungkapkan Masalah</strong><br>

                <label class="me-2">
                    <input type="radio"
                        name="kemandirian[${index}][mengungkapkanmasalah]"
                        value="ya"
                        ${data?.mengungkapkanmasalah === 'ya' ? 'checked' : ''}
                        onclick="nextQuestion(${index},3,true)"
                        ${isReadonly ? 'disabled' : ''}>
                    Ya
                </label>

                <label>
                    <input type="radio"
                        name="kemandirian[${index}][mengungkapkanmasalah]"
                        value="tidak"
                        ${data?.mengungkapkanmasalah === 'tidak' ? 'checked' : ''}
                        onclick="nextQuestion(${index},3,false)"
                        ${isReadonly ? 'disabled' : ''}>
                    Tidak
                </label>
            </div>

            <!-- 4 -->
            <div id="row${index}_4" class="mb-2" style="display:none;">
                <strong>4. Memanfaatkan Faskes</strong><br>

                <label class="me-2">
                    <input type="radio"
                        name="kemandirian[${index}][faskes]"
                        value="ya"
                        ${data?.faskes === 'ya' ? 'checked' : ''}
                        onclick="nextQuestion(${index},4,true)"
                        ${isReadonly ? 'disabled' : ''}>
                    Ya
                </label>

                <label>
                    <input type="radio"
                        name="kemandirian[${index}][faskes]"
                        value="tidak"
                        ${data?.faskes === 'tidak' ? 'checked' : ''}
                        onclick="nextQuestion(${index},4,false)"
                        ${isReadonly ? 'disabled' : ''}>
                    Tidak
                </label>
            </div>

            <!-- 5 -->
            <div id="row${index}_5" class="mb-2" style="display:none;">
                <strong>5. Tindakan Keperawatan</strong><br>

                <label class="me-2">
                    <input type="radio"
                        name="kemandirian[${index}][tindakankeperawatan]"
                        value="ya"
                        ${data?.tindakankeperawatan === 'ya' ? 'checked' : ''}
                        onclick="nextQuestion(${index},5,true)"
                        ${isReadonly ? 'disabled' : ''}>
                    Ya
                </label>

                <label>
                    <input type="radio"
                        name="kemandirian[${index}][tindakankeperawatan]"
                        value="tidak"
                        ${data?.tindakankeperawatan === 'tidak' ? 'checked' : ''}
                        onclick="nextQuestion(${index},5,false)"
                        ${isReadonly ? 'disabled' : ''}>
                    Tidak
                </label>
            </div>

            <!-- 6 -->
            <div id="row${index}_6" class="mb-2" style="display:none;">
                <strong>6. Tindakan Pencegahan</strong><br>

                <label class="me-2">
                    <input type="radio"
                        name="kemandirian[${index}][tindakanpencegahan]"
                        value="ya"
                        ${data?.tindakanpencegahan === 'ya' ? 'checked' : ''}
                        onclick="nextQuestion(${index},6,true)"
                        ${isReadonly ? 'disabled' : ''}>
                    Ya
                </label>

                <label>
                    <input type="radio"
                        name="kemandirian[${index}][tindakanpencegahan]"
                        value="tidak"
                        ${data?.tindakanpencegahan === 'tidak' ? 'checked' : ''}
                        onclick="nextQuestion(${index},6,false)"
                        ${isReadonly ? 'disabled' : ''}>
                    Tidak
                </label>
            </div>

            <!-- 7 -->
            <div id="row${index}_7" class="mb-2" style="display:none;">
                <strong>7. Tindakan Promotif</strong><br>

                <label class="me-2">
                    <input type="radio"
                        name="kemandirian[${index}][tindakanpromotif]"
                        value="ya"
                        ${data?.tindakanpromotif === 'ya' ? 'checked' : ''}
                        onclick="nextQuestion(${index},7,true)"
                        ${isReadonly ? 'disabled' : ''}>
                    Ya
                </label>

                <label>
                    <input type="radio"
                        name="kemandirian[${index}][tindakanpromotif]"
                        value="tidak"
                        ${data?.tindakanpromotif === 'tidak' ? 'checked' : ''}
                        onclick="nextQuestion(${index},7,false)"
                        ${isReadonly ? 'disabled' : ''}>
                    Tidak
                </label>
            </div>

        </td>

        ${aksiCol}
    `;

    tbody.appendChild(row);

    // Auto resize textarea yang baru ditambahkan
    row.querySelectorAll('.auto-resize').forEach(autoResizeTextarea);


    if (data) {
        restoreKemandirian(index, data);
    }

    rowKemandirianCount++;
}

window.addEventListener('load', function() {

    console.log('existingKemandirian=', existingKemandirian);

    if (
        existingKemandirian &&
        Array.isArray(existingKemandirian) &&
        existingKemandirian.length > 0
    ) {
        existingKemandirian.forEach(item => {
            tambahRowKemandirian(item);
        });
    } else {
        tambahRowKemandirian();
    }
});
</script>
                    
<script>

function nextQuestion(rowIndex, no, isYa) {

// sembunyikan semua setelah current
for (let i = no + 1; i <= 7; i++) {

let row = document.getElementById(`row${rowIndex}_${i}`);

if (row) {

row.style.display = 'none';

// reset radio button
let radios = row.querySelectorAll('input[type=radio]');

radios.forEach(r => {
r.checked = false;
        });
    }
}

// kalau pilih YA
if (isYa) {

    let nextRow = document.getElementById(`row${rowIndex}_${no + 1}`);

        if (nextRow) {
            nextRow.style.display = 'block';
                }
        }
    }

    function restoreKemandirian(index, data) {

    // kalau tidak ada data
    if (!data) return;

    // urutan field radio
    const urutan = [
        'menerimaperawat',
        'pelayanankesehatan',
        'mengungkapkanmasalah',
        'faskes',
        'tindakankeperawatan',
        'tindakanpencegahan',
        'tindakanpromotif'
    ];

    // cek satu per satu
    for (let i = 0; i < urutan.length; i++) {

    // kalau jawabannya YA
    if (data[urutan[i]] === 'ya') {

    // tampilkan row berikutnya
    let nextRow = document.getElementById(`row${index}_${i + 2}`);

    if (nextRow) {
        nextRow.style.display = 'block';
    }

    } else {

    // berhenti kalau tidak
    break;
        }
    }
}

</script>   
