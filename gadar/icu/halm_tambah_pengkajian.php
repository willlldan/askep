<?php
if(isset($_POST['submit'])){

    $mahasiswa_id = 1;

    // =========================
    // PENGKAJIAN UMUM
    // =========================
    $nama = $_POST['nama'];
    $umur = $_POST['umur'];
    $jeniskelamin = $_POST['jeniskelamin'];
    $pekerjaan = $_POST['pekerjaan'];
    $agama = $_POST['agama'];
    $tgl_mrs = $_POST['tgl_mrs'];
    $tgl_pengkajian = $_POST['tgl_pengkajian'];
    $noreg = $_POST['noreg'];
    $alamat = $_POST['alamat'];
    $dxmedis = $_POST['dxmedis'];
    $keluhanutama = $_POST['keluhanutama'];
    $riwayatkeluhanutama = $_POST['riwayatkeluhanutama'];
    $riwayat_alergi = $_POST['riwayat_alergi'];
    $keadaan_umum = $_POST['keadaan_umum'];

    $tekanan_darah_umum = $_POST['tekanan_darah_umum'];
    $nadi_umum = $_POST['nadi_umum'];
    $suhu_umum = $_POST['suhu_umum'];
    $rr_umum = $_POST['rr_umum'];

    mysqli_query($conn, "INSERT INTO icu_pengkajian_umum
    (mahasiswa_id,nama,umur,jeniskelamin,pekerjaan,agama,tgl_mrs,tgl_pengkajian,noreg,alamat,dxmedis,keluhanutama,riwayatkeluhanutama,riwayat_alergi,keadaan_umum,tekanan_darah,nadi,suhu,rr)
    VALUES
    ('$mahasiswa_id','$nama','$umur','$jeniskelamin','$pekerjaan','$agama','$tgl_mrs','$tgl_pengkajian','$noreg','$alamat','$dxmedis','$keluhanutama','$riwayatkeluhanutama','$riwayat_alergi','$keadaan_umum','$tekanan_darah_umum','$nadi_umum','$suhu_umum','$rr_umum')");


    // =========================
    // PRIMARY SURVEY
    // =========================
    $tanggal = $_POST['tanggal'];

    $jalan_nafas = $_POST['jalan_nafas'];
    $ett = $_POST['ett'];

    $pola_nafas = $_POST['pola_nafas'];
    $spo2 = $_POST['spo2'];
    $ventilator = $_POST['ventilator'];

    $nadi_primary = $_POST['nadi_primary'];
    $tekanan_darah_primary = $_POST['tekanan_darah_primary'];

    $tingkat_kesadaran = $_POST['tingkat_kesadaran'];

    $gcs_e = $_POST['gcs_e'];
    $gcs_m = $_POST['gcs_m'];
    $gcs_v = $_POST['gcs_v'];

    $suhu_primary = $_POST['suhu_primary'];

    mysqli_query($conn, "INSERT INTO icu_pengkajian_primary
    (mahasiswa_id,tanggal,jalan_nafas,ett,pola_nafas,spo2,ventilator,nadi,tekanandarah,tingkat_kesadaran,gcs_e,gcs_m,gcs_v,suhu)
    VALUES
    ('$mahasiswa_id','$tanggal','$jalan_nafas','$ett','$pola_nafas','$spo2','$ventilator','$nadi_primary','$tekanan_darah_primary','$tingkat_kesadaran','$gcs_e','$gcs_m','$gcs_v','$suhu_primary')");


    // =========================
    // SECONDARY B1
    // =========================
    $hidung = $_POST['hidung'];
    $trakea = $_POST['trakea'];
    $nyeri = $_POST['nyeri'];
    $dypsnea = $_POST['dypsnea'];

    mysqli_query($conn, "INSERT INTO icu_pengkajian_secondary_b1
    (mahasiswa_id,hidung,trakea,nyeri,dypsnea)
    VALUES
    ('$mahasiswa_id','$hidung','$trakea','$nyeri','$dypsnea')");


    // =========================
    // KLASIFIKASI DATA
    // =========================
    $datasubjektif = $_POST['datasubjektif'];
    $dataobjektif = $_POST['dataobjektif'];

    mysqli_query($conn, "INSERT INTO icu_klasifikasi_data
    (mahasiswa_id,datasubjektif,dataobjektif)
    VALUES
    ('$mahasiswa_id','$datasubjektif','$dataobjektif')");


    // =========================
    // ANALISA DATA
    // =========================
    $dsdo = $_POST['dsdo'];
    $etiologi = $_POST['etiologi'];
    $masalah = $_POST['masalah'];

    mysqli_query($conn, "INSERT INTO icu_analisa_data
    (mahasiswa_id,dsdo,etiologi,masalah)
    VALUES
    ('$mahasiswa_id','$dsdo','$etiologi','$masalah')");


    echo "SEMUA DATA BERHASIL DISIMPAN!";
}
?>

<main id="main" class="main">

    <div class="pagetitle">
        <h1><strong>Askep Keperawatan Ruang ICU</strong></h1>
    </div><!-- End Page Title -->
    <br>


    <ul class="nav nav-tabs custom-tabs">

    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'laporanpendahuluan' ? 'active' : '' ?>"
        href="index.php?page=gadar/icu&tab=laporanpendahuluan">
        Laporan Pendahuluan
        </a>
    </li> 

    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'pengkajian' ? 'active' : '' ?>"
        href="index.php?page=gadar/icu&tab=pengkajian">
        Pengkajian
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'diagnosa_keperawatan' ? 'active' : '' ?>"
        href="index.php?page=gadar/icu&tab=diagnosa_keperawatan">
        Diagnosa Keperawatan
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'rencana_keperawatan' ? 'active' : '' ?>"
        href="index.php?page=gadar/icu&tab=rencana_keperawatan">
        Rencana Keperawatan
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'implementasi_keperawatan' ? 'active' : '' ?>"
       href="index.php?page=gadar/icu&tab=implementasi_keperawatan">
        Implementasi Keperawatan
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'evaluasi_keperawatan' ? 'active' : '' ?>"
        href="index.php?page=gadar/icu&tab=evaluasi_keperawatan">
        Evaluasi keperawatan
        </a>
    </li>

    </ul>

    <style>
    .custom-tabs {
        border-bottom: 1px solid #dee2e6;
    }

    .custom-tabs .nav-link {
        border: none;
        background: transparent;
        color: #f6f9ff;
        font-weight: 500;
        padding: 10px 20px;
    }

    .custom-tabs .nav-link:hover {
        color: #4154f1;
    }

    .custom-tabs .nav-link.active {
        border: none;
        border-bottom: 3px solid #4154f1;
        color: #4154f1;
        font-weight: 600;
        background: transparent;
    }
    </style>

    <section class="section dashboard">
        <div class="card">
            <div class="card-body">
            
                <!-- General Form Elements -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

                <h5 class="card-title"><strong>IDENTITAS KLIEN</strong></h5>

                <!-- General Form Elements -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">
                
                    <!-- Bagian Nama -->

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Nama</strong></label>

                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="nama">

                            <!-- comment -->
                            <textarea class="form-control mt-2" name="commentnama" id="commentnama" rows="2" placeholder="Jika ada revisi atau saran dari Ibu/Bapak Dosen, silakan diketik di sini. Terima kasih." style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" onchange="document.getElementById('commentinisialpasien'). style.display= this.checked ? 'none' : 'block'">
                            </div>
                         </div>
                    </div>

                <!-- Bagian Umur -->
                <div class="row mb-3">
                    <label for="umur" class="col-sm-2 col-form-label"><strong>Umur</strong></label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="umur">

                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentumur" id="commentumur" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>

                <!-- Bagian Jenis Kelamin -->
                 <div class="row mb-3">
                    <label for="jeniskelamin" class="col-sm-2 col-form-label"><strong>Jenis Kelamin</strong></label> 
                    <div class="col-sm-9">
                    <select class="form-select" name="jeniskelamin">
                            <option value="">Pilih</option>
                            <option value="Perempuan">Perempuan</option>
                            <option value="Laki-laki">Laki-laki</option>
                            </select>
                            
                     <!-- comment -->
                            <textarea class="form-control mt-2" name="commentjeniskelamin" id="commentjeniskelamin" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>    

                <!-- Bagian Pekerjaan -->
                <div class="row mb-3">
                    <label for="pekerjaan" class="col-sm-2 col-form-label"><strong>Pekerjaan</strong></label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="pekerjaan">

                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentpekerjaan" id="commentpekerjaan" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>

                <!-- Bagian Agama -->
                <div class="row mb-3">
                    <label for="agama" class="col-sm-2 col-form-label"><strong>Agama</strong></label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="agama">

                       <!-- comment -->
                            <textarea class="form-control mt-2" name="commentagama" id="commentagama" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>

                <!-- Bagian Tanggal MRS -->
                <div class="row mb-3">
                    <label for="tgl_mrs" class="col-sm-2 col-form-label"><strong>Tanggal MRS</strong></label>
                    <div class="col-sm-9">
                        <input type="datetime-local" class="form-control" name="tgl_mrs">
                        
                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commenttgl_mrs" id="commenttgl_mrs" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>

                <!-- Bagian Tanggal Pengkajian -->
                <div class="row mb-3">
                    <label for="tgl_pengkajian" class="col-sm-2 col-form-label"><strong>Tanggal Pengkajian</strong></label>
                    <div class="col-sm-9">
                        <input type="datetime-local" class="form-control" name="tgl_pengkajian">
                        
                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commenttgl_pengkajian" id="commenttgl_pengkajian" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>

                <!-- Bagian No REG -->
                <div class="row mb-3">
                    <label for="noreg" class="col-sm-2 col-form-label"><strong>No REG</strong></label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="noreg">

                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentnoreg" id="commentnoreg" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>

                <!-- Bagian Alamat -->
                <div class="row mb-3">
                    <label for="alamat" class="col-sm-2 col-form-label"><strong>Alamat</strong></label>
                    <div class="col-sm-9">
                       <textarea name="alamat" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentalamat" id="commentalamat" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>

                <!-- Bagian DX Medis -->
                <div class="row mb-3">
                    <label for="dxmedis" class="col-sm-2 col-form-label"><strong>DX Medis</strong></label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="dxmedis">
                       
                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentdxmedis" id="commentdxmedis" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>    

                <!-- Bagian Keluhan Utama -->
                <div class="row mb-3">
                    <label for="keluhanutama" class="col-sm-2 col-form-label"><strong>Keluhan Utama</strong></label>
                    <div class="col-sm-9">
                        <textarea name="riwayatkeluhanutama" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                        <!-- comment -->
                            <textarea class="form-control mt-2" name="comentkeluhanutama" id="commentkeluhanutama" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>    

                <!-- Bagian Riwayat Keluhan Utama -->
                <div class="row mb-3">
                    <label for="riwayatkeluhanutama" class="col-sm-2 col-form-label"><strong>Riwayat Keluhan Utama</strong></label>
                    <div class="col-sm-9">
                        <textarea name="riwayatkeluhanutama" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentriwayatkeluhanutama" id="commentriwayatkeluhanutama" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>

                <!-- Bagian Riwayat Alergi -->
                <div class="row mb-3">
                    <label for="riwayatalergi" class="col-sm-2 col-form-label"><strong>Riwayat Alergi</strong></label>
                    <div class="col-sm-9">
                        <textarea name="riwayatalergi" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                        <!-- comment -->
                            <textarea class="form-control mt-2"  name="commentriwayatalergi" id="commentriwayatalergi" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>

                <!-- Bagian Keadaan Umum -->
                <div class="row mb-3">
                    <label for="keadaanumum" class="col-sm-2 col-form-label"><strong>Keadaan Umum</strong></label>
                    <div class="col-sm-9">
                        <textarea name="keadaanumum" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        
                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentkeadaanumum" id="commentkeadaanumum" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>

                <!-- Bagian Tanda-tanda Vital -->

                    <div class="row mb-3">
                        <label class="col-sm-9 col-form-label">
                            <strong>Tanda-tanda Vital</strong>
                        </label>    
                    </div>

                    <!-- Tekanan Darah -->
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-2 col-form-label"><strong>Tekanan Darah</strong></label>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="text" class="form-control" name="tekanandarah">
                                <span class="input-group-text">mmHg</span>
                        </div>    
                    </div>
                                
                    <!-- Nadi -->
                    <label class="col-sm-2 col-form-label"><strong>Nadi</strong></label>
                    <div class="col-sm-3">
                        <div class="input-group">
                                <input type="text" class="form-control" name="nadi">
                                <span class="input-group-text">x/menit</span>
                        </div> 
                    </div>

                    <div class="col-sm-1 d-flex align-items-start">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" disabled>
                        </div>
                    </div>
                </div>
              
                    <!-- Suhu -->
                        <div class="row mb-3 align-items-center">
                            <label class="col-sm-2 col-form-label"><strong>Suhu</strong></label>
                            <div class="col-sm-3">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="suhu">
                                    <span class="input-group-text">°C</span>
                            </div>    
                        </div>

                    <!-- RR -->
                    <label class="col-sm-2 col-form-label"><strong>RR</strong></label>
                    <div class="col-sm-3">
                        <div class="input-group">
                                <input type="text" class="form-control" name="rr">
                                <span class="input-group-text">x/menit</span>
                            </div>
                        </div>
                    </div>

                <!-- comment -->
                <div class="row mb-3">

                    <div class="offset-sm-2 col-sm-9">
                        <textarea class="form-control mt-2" name="commenttandavital" id="commenttandavital" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize:none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                    </div>
                        </div>
                    </div>
                </div>

            <div class="card">
            <div class="card-body">
              <h5 class="card-title mb-1"><strong>Pengkajian</strong></h5>

                <!-- General Form Elements -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

                <!-- Bagian Primary Survey -->

                    <h5 class="card-title mb-1"><strong>1. Primary Survey</strong></h5>
 
                    <!-- Pengumpulan Data -->
                    <div class="row mb-2">
                        <label class="col-sm-12 col-form-label"><strong>Pengumpulan Data</strong><label>
                    </div> 

                    <!-- Tanggal -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Tanggal</strong></label>

                        <div class="col-sm-9">
                           <input type="datetime-local" class="form-control" name="tanggal">

                     <!-- comment -->
                            <textarea class="form-control mt-2" name="commenttgl" id="commenttanggal" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 

                    <!-- Airways (Jalan Napas) -->
                    <div class="row mb-2">
                        <label class="col-sm-12 col-form-label"><strong>Airways (Jalan Nafas)</strong><label>
                    </div> 

                    <!-- Sumbatan Jalan Napas -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Sumbatan Jalan Nafas/Sekret</strong></label>

                        <div class="col-sm-9">
                            <select class="form-select" name="jalannafas" required>
                                <option value="">Pilih</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak Ada">Tidak Ada</option>
                            </select>

                     <!-- comment -->
                            <textarea class="form-control mt-2" name="commentjalannafas" id="commentjalannafas" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 

                    <!-- ETT/Trakeostomi -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>ETT/Trakeostomi</strong></label>

                        <div class="col-sm-9">
                            <select class="form-select" name="ett" required>
                                <option value="">Pilih</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>

                     <!-- comment -->
                            <textarea class="form-control mt-2" name="commentett" id="commentett" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 
                    
                    <!-- Breathing (Pernafasan) -->
                    <div class="row mb-2">
                        <label class="col-sm-12 col-form-label"><strong>Breathing (Pernafasan)</strong><label>
                    </div> 

                    <!-- Pola Napas -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Pola Nafas</strong></label>

                        <div class="col-sm-9">
                            <div class="input-group">
                                <input type="text" class="form-control" name="polanafas">
                                <span class="input-group-text">x/menit</span>
                            </div>

                     <!-- comment -->
                            <textarea class="form-control mt-2" name="commentpolanafas" id="commentpolanafas" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 

                    <!-- SpO2 -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>SpO2</strong></label>

                        <div class="col-sm-9">
                            <div class="input-group">
                                <input type="text" class="form-control" name="spo2">
                                <span class="input-group-text">%</span>
                            </div>

                     <!-- comment -->
                            <textarea class="form-control mt-2" name="commentspo2" id="commentspo2" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>

                    <!-- Ventilator -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Ventilator (mode/PEEP/FiO2)</strong></label>

                        <div class="col-sm-9">
                                <input type="text" class="form-control" name="ventilator">

                     <!-- comment -->
                            <textarea class="form-control mt-2" name="commentventilator" id="commentventilator" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>

                    <!-- Pernafasan Cuping Hidung -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Pernafasan Cuping Hidung</strong></label>

                        <div class="col-sm-9">
                            <select class="form-select" name="pernafasancupinghidung" required>
                                <option value="">Pilih</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak Ada">Tidak Ada</option>
                            </select>

                     <!-- comment -->
                            <textarea class="form-control mt-2" name="commentpernafasancupinghidung" id="commentpernafasancupinghidung" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 

                    <!-- Suara Nafas Tambahan -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Suara Nafas Tambahan</strong></label>

                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="suaranafastambahan">

                     <!-- comment -->
                            <textarea class="form-control mt-2" name="commensuaranafastambahan" id="commentsuaranapastambahan" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>
                    
                    <!-- Retraksi Dinding Dada -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Retraksi Dinding Dada</strong></label>

                        <div class="col-sm-9">
                            <select class="form-select" name="retraksidindingdada" required>
                                <option value="">Pilih</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak Ada">Tidak Ada</option>
                            </select>
                     <!-- comment -->
                            <textarea class="form-control mt-2" name="commentretraksidindingdada" id="commentretraksidindingdada" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>
                    
                    <!-- Otot Bantu -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Menggunakan otot bantu dalam bernafas</strong></label>

                        <div class="col-sm-9">
                            <select class="form-select" name="ototbantu" required>
                                <option value="">Pilih</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>

                     <!-- comment -->
                            <textarea class="form-control mt-2" name="commentototbantu" id="commentotobantu" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 

                    <!-- Circulation -->

                    <div class="row mb-2">
                        <label class="col-sm-12 col-form-label"><strong>Circulation (Sirkulasi)</strong><label>
                    </div> 

                    <!-- Nadi -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Nadi</strong></label>

                        <div class="col-sm-9">
                            <div class="input-group">
                                <input type="text" class="form-control" name="polanafas">
                                <span class="input-group-text">x/menit</span>
                            </div>

                     <!-- comment -->
                            <textarea class="form-control mt-2" name="commentnadi" id="commentnadi" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>

                    <!-- Tekanan Darah -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Tekanan Darah</strong></label>

                        <div class="col-sm-9">
                            <div class="input-group">
                                <input type="text" class="form-control" name="tekanandarah">
                                <span class="input-group-text">mmHg</span>
                            </div>

                     <!-- comment -->
                            <textarea class="form-control mt-2" name="commenttekanandarah" id="commenttekanandarah" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>

                    <!-- CVP -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>CVP</strong></label>

                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="cvp">

                     <!-- comment -->
                            <textarea class="form-control mt-2" name="commentcvp" id="commentcvp" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>

                    <!-- CRT -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>CRT</strong></label>

                        <div class="col-sm-9">
                            <div class="input-group">
                                <input type="text" class="form-control" name="crt">
                                <span class="input-group-text">detik</span>
                            </div>

                     <!-- comment -->
                            <textarea class="form-control mt-2" name="commentcrt" id="commentcrt" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>

                    <!-- Suara Jantung -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Suara Jantung</strong></label>

                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="suarajantung">
                            

                     <!-- comment -->
                            <textarea class="form-control mt-2" name="commentsuarajantung" id="commentsuarajantung" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>

                    <!-- Perfusi Perifer -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Perfusi Perifer</strong></label>

                        <div class="col-sm-9">
                                <input type="text" class="form-control" name="perfusiperifer">

                     <!-- comment -->
                            <textarea class="form-control mt-2" name="commentperfusiperifer" id="commentperfusiperifer" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>

                    <!-- Disability -->

                    <div class="row mb-2">
                        <label class="col-sm-12 col-form-label"><strong>Disability (Neurologi)</strong><label>
                    </div> 

                    <!-- Tingkat Kesadaran -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Tingkat Kesadaran</strong></label>

                        <div class="col-sm-9">
                                <input type="text" class="form-control" name="tingkatkesadaran">

                     <!-- comment -->
                            <textarea class="form-control mt-2" name="commenttingkatkesadaran" id="commenttingkatkesadaran" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>

                    <!-- GCS -->

                        <div class="row mb-3">

                        <label class="col-sm-2 col-form-label"><strong>Glasgow Coma Scale (GCS)</strong></label>
                            <div class="col-sm-9">
                                <div class="row">

                        <!-- E -->
                        <div class="col-md-4 d-flex align-items-center">
                            <label class="me-2"><strong>E</strong></label>
                            <input type="text" class="form-control" name="e">
                        </div>

                        <!-- M -->
                        <div class="col-md-4 d-flex align-items-center">
                            <label class="me-2"><strong>M</strong></label>
                            <input type="text" class="form-control" name="m">
                        </div>

                        <!-- V -->
                        <div class="col-md-4 d-flex align-items-center">
                            <label class="me-2"><strong>V</strong></label>
                            <input type="text" class="form-control" name="v">
                        </div>
                    </div>

                         <!-- comment -->
                        <textarea class="form-control mt-2" name="commentemv" id="commentemv" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>

                    </div>

                    <!-- Pupil -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Pupil</strong></label>

                        <div class="col-sm-9">
                                <input type="text" class="form-control" name="pupil">

                     <!-- comment -->
                            <textarea class="form-control mt-2" name="commentpupil" id="commentpupil" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>

                     <!-- Respon Motorik -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Respon Motorik</strong></label>

                        <div class="col-sm-9">
                                <input type="text" class="form-control" name="responmotorik">

                     <!-- comment -->
                            <textarea class="form-control mt-2" name="commentresponmotorik" id="commentresponmotorik" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>

                    <!-- Exposure -->
                    <div class="row mb-2">
                        <label class="col-sm-12 col-form-label"><strong>Exposure</strong><label>
                    </div> 

                    <!-- Suhu -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Suhu</strong></label>

                        <div class="col-sm-9">
                            <div class="input-group">
                                <input type="text" class="form-control" name="suhu">
                                <span class="input-group-text">°C</span>
                            </div>

                         <!-- comment -->
                        <textarea class="form-control mt-2" name="commentsuhu" id="commentsuhu" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 
                    
                    <!-- Lainnya -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Lainnya</strong></label>

                        <div class="col-sm-9">
                                <input type="text" class="form-control" name="lainnya">

                         <!-- comment -->
                        <textarea class="form-control mt-2" name="commentlainnya" id="commentlainnya" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 

                    <!-- Fluid -->
                    <div class="row mb-2">
                        <label class="col-sm-12 col-form-label"><strong>Fluid (Cairan dan Elektrolit)</strong><label>
                    </div> 

                    <!-- Infuse -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Infuse yang Terpasang</strong></label>

                        <div class="col-sm-9">
                           <input type="text" class="form-control" name="infuse">

                     <!-- comment -->
                            <textarea class="form-control mt-2" name="commentinfuse" id="commentinfuse" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 

                    <!-- Cairan -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Cairan</strong></label>

                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="cairan">

                     <!-- comment -->
                            <textarea class="form-control mt-2" name="commentcairan" id="commentcairan" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 

                    <!-- Jumlah -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Jumlah Tetesan</strong></label>

                    <div class="col-sm-9">
                        <div class="input-group">
                            <input type="text" class="form-control" name="jumlahtetesan">
                            <span class="input-group-text">x/m</span>
                        </div>

                     <!-- comment -->
                            <textarea class="form-control mt-2" name="commentjumlahtetesan" id="commentjumlahtetesan" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 

                <!-- Bagian Button -->    
                <div class="row mb-3">
                    <div class="col-sm-11 justify-content-end d-flex">
                        <button type="submit" name="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div> 

                <style>
                
                .table-primarysurvey {
                    table-layout: fixed;
                    width: 100;
                }

                .table-primarysurvey td,
                .table-primarysurvey th {
                    word-wrap: break-word;
                    white-space: normal;
                    vertical-align: top;
                }

                </style>

                <h5 class="card-title"><strong>Primary Survey</strong></h5>

                <table class="table table-bordered table-primarysurvey">
                   
                <tbody>

                <tr>
                <td><strong>Pengumpulan Data</strong></td>
                <td>Tanggal: <?= $row['tanggal'] ?? ''; ?></td>
                </tr>

                <tr>
                <td rowspan="2"><strong>Airways (Jalan Nafas)</strong></td>
                <td>Sumbatan Jalan Nafas: <?= $row['jalannafas'] ?? ''; ?></td>
                </tr>

                <tr>
                <td>ETT/Trakeostomi: <?= $row['ett'] ?? ''; ?></td>
                </tr>

                <tr>
                <td rowspan="7"><strong>Breathing (Pernafasan)</strong></td>
                <td>Pola Napas: <?= $row['polanafas'] ?? ''; ?></td>
                </tr>

                <tr>
                <td>SpO2: <?= $row['sp02'] ?? ''; ?></td>
                </tr>

                <tr>
                <td>Ventilator (mode/PEEP/FiO2): <?= $row['ventilator'] ?? ''; ?></td>
                </tr>

                <tr>
                <td>Penafasan Cuping Hidung: <?= $row['pernafasancupinghidung'] ?? ''; ?></td>
                </tr>

                <tr>
                <td>Suara Nafas Tambahan: <?= $row['suaranafastambahan'] ?? ''; ?></td>
                </tr>

                <tr>
                <td>Retraksi Dinding Dada: <?= $row['retraksidindingdada'] ?? ''; ?></td>
                </tr>

                <tr>
                <td>Menggunakan Otot Bantu: <?= $row['otobantu'] ?? ''; ?></td>
                </tr>

                <tr>
                <td rowspan="6"><strong>Circulation (Sirkulasi)</strong></td>
                <td>Nadi: <?= $row['nadi'] ?? ''; ?></td>
                </tr>

                <tr>
                <td>Tekanan Darah: <?= $row['tekanandarah'] ?? ''; ?></td>
                </tr>

                <tr>
                <td>CVP: <?= $row['cvp'] ?? ''; ?></td>
                </tr>

                <tr>
                <td>CRT: <?= $row['crt'] ?? ''; ?></td>
                </tr>

                <tr>
                <td>Suara Jantung: <?= $row['suarajantung'] ?? ''; ?></td>
                </tr>

                <tr>
                <td>Perfusi Perifer: <?= $row['perfusiperifer'] ?? ''; ?></td>
                </tr>

                <tr>
                <td rowspan="4"><strong>Disability (Neurologi)</strong></td>
                <td>Tingkat Kesadaran: <?= $row['tingkatkesadaran'] ?? ''; ?></td>
                </tr>

                <tr>
                <td>Glasgow Coma Scale (GCS):
                    E<?= $row['e'] ?? '0'; ?>
                    M<?= $row['m'] ?? '0'; ?>
                    V<?= $row['v'] ?? '0'; ?>
                </td> 
                </tr>

                <tr>
                <td>Pupil: <?= $row['pupil'] ?? ''; ?></td>
                </tr>

                <tr>
                <td>Respon Motorik: <?= $row['responmotorik'] ?? ''; ?></td>
                </tr>

                <tr>
                <td rowspan="2"><strong>Exposure</strong></td>
                <td>Suhu: <?= $row['suhu'] ?? ''; ?></td>
                </tr>

                <tr>
                <td>Lainnya: <?= $row['lainnya'] ?? ''; ?></td>
                </tr>

                <tr>
                <td rowspan="3"><strong>Fluid (Cairan dan Elektrolit)</strong></td>
                <td>Infuse yang Terpasang: <?= $row['infuse'] ?? ''; ?></td>
                </tr>

                <tr>
                <td>Cairan: <?= $row['cairan'] ?? ''; ?></td>
                </tr>

                <tr>
                <td>Jumlah Tetesan: <?= $row['jumlahtetesan'] ?? ''; ?></td>
                </tr>

                </tbody>
                </table>

             <!-- Bagian Secondary Survey -->

                    <h5 class="card-title mb-1"><strong>2. Pemeriksaan Fisik Spesifik With Body Sistem</strong></h5>

                    <!-- Pernafasan (B1: Breathing) -->
                    <div class="row mb-3">
                        <label class="col-sm-10 col-form-label text-primary">
                            <strong>a. Pernafasan (B1: Breathing)</strong>
                    </div>   

                    <!-- Hidung -->
                        <div class="row mb-2">
                            <div class="col-sm-2">
                                <strong>Hidung</strong>
                            </div> 

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="hidung" value="asimetris">
                                        <label class="form-check-label">Asimetris</label>
                                    </div>
                                </div>

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="hidung" value="deviasiseptum">
                                        <label class="form-check-label">Deviasi Septum</label>
                                </div>
                            </div>

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="hidung" value="epistaksis">
                                        <label class="form-check-label">Epistaksis</label>
                                </div>
                            </div>

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="hidung" value="lainlain">
                                        <label class="form-check-label">Lain-lain</label>
                                </div>
                            </div>
                        </div>

                        <!-- Trakea -->
                        <div class="row mb-2">
                            <div class="col-sm-2">
                                <strong>Trakea</strong>
                            </div> 

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="trakea" value="deviasitrakea">
                                        <label class="form-check-label">Deviasi Trakea</label>
                                    </div>
                                </div>

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="trakea" value="disfagia">
                                        <label class="form-check-label">Disfagia</label>
                                </div>
                            </div>
                        </div>  
                        
                        <!-- Nyeri -->
                        <div class="row mb-2">
                            <div class="col-sm-2">
                                <strong>Nyeri</strong>
                            </div> 

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="nyeri" value="ya">
                                        <label class="form-check-label">Ya</label>
                                    </div>
                                </div>

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="nyeri" value="tidak">
                                        <label class="form-check-label">Tidak</label>
                                </div>
                            </div>
                        </div>    

                        <!-- Dypsnea -->
                        <div class="row mb-2">
                            <div class="col-sm-2">
                                <strong>Dypsnea</strong>
                            </div> 

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="dypsnea" value="ya">
                                        <label class="form-check-label">Ya</label>
                                    </div>
                                </div>

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="dypsnea" value="tidak">
                                        <label class="form-check-label">Tidak</label>
                                </div>
                            </div>
                        </div>    

                        <!-- Cyanosis -->
                        <div class="row mb-2">
                            <div class="col-sm-2">
                                <strong>Cyanosis</strong>
                            </div> 

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="cyanosis" value="ya">
                                        <label class="form-check-label">Ya</label>
                                    </div>
                                </div>

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="cyanosis" value="tidak">
                                        <label class="form-check-label">Tidak</label>
                                </div>
                            </div>
                        </div> 
                        
                        <!-- Retraksi Dada -->
                        <div class="row mb-2">
                            <div class="col-sm-2">
                                <strong>Retraksi Dada</strong>
                            </div> 

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="retraksidada" value="ya">
                                        <label class="form-check-label">Ya</label>
                                    </div>
                                </div>

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="retraksidada" value="tidak">
                                        <label class="form-check-label">Tidak</label>
                                </div>
                            </div>
                        </div>    

                        <!-- Batuk Darah -->
                        <div class="row mb-2">
                            <div class="col-sm-2">
                                <strong>Batuk Darah</strong>
                            </div> 

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="batukdarah" value="ya">
                                        <label class="form-check-label">Ya</label>
                                    </div>
                                </div>

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="batukdarah" value="tidak">
                                        <label class="form-check-label">Tidak</label>
                                </div>
                            </div>
                        </div>   
                        
                        <!-- Orthopnea -->
                        <div class="row mb-2">
                            <div class="col-sm-2">
                                <strong>Orthopnea</strong>
                            </div> 

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="orthopnea" value="ya">
                                        <label class="form-check-label">Ya</label>
                                    </div>
                                </div>

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="orthopnea" value="tidak">
                                        <label class="form-check-label">Tidak</label>
                                </div>
                            </div>
                        </div>    

                        <!-- Napas Dangkal -->
                        <div class="row mb-2">
                            <div class="col-sm-2">
                                <strong>Napas Dangkal</strong>
                            </div> 

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="napasdangkal" value="ya">
                                        <label class="form-check-label">Ya</label>
                                    </div>
                                </div>

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="napasdangkal" value="tidak">
                                        <label class="form-check-label">Tidak</label>
                                </div>
                            </div>
                        </div>    
                    
                        <!-- Sputum -->
                        <div class="row mb-2">
                            <div class="col-sm-2">
                                <strong>Sputum</strong>
                            </div> 

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="sputum" value="ya">
                                        <label class="form-check-label">Ya</label>
                                    </div>
                                </div>

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="sputum" value="tidak">
                                        <label class="form-check-label">Tidak</label>
                                </div>
                            </div>
                        </div>
                     
                        <!-- Trakeostomi -->
                        <div class="row mb-2">
                            <div class="col-sm-2">
                                <strong>Trakeostomi</strong>
                            </div> 

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="trakeostomi" value="ya">
                                        <label class="form-check-label">Ya</label>
                                    </div>
                                </div>

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="trakeostomi" value="tidak">
                                        <label class="form-check-label">Tidak</label>
                                </div>
                            </div>
                        </div>    

                        <!-- Suara Tambahan Napas -->
                        <div class="row mb-2">
                            <div class="col-sm-2">
                                <strong>Suara Tambahan Napas</strong>
                            </div> 

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="suaratambahannapas" value="weezhing">
                                        <label class="form-check-label">Weezhing</label>
                                    </div>
                                </div>

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="suaratambahannapas" value="ronchi">
                                        <label class="form-check-label">Ronchi</label>
                                </div>
                            </div>

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="suaratambahannapas" value="crackles">
                                        <label class="form-check-label">Crakles</label>
                                </div>
                            </div>

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="suaratambahannapas" value="stridor">
                                        <label class="form-check-label">Stridor</label>
                                </div>
                            </div>
                        </div>   
                        
                        <!-- Bentuk Dada -->
                        <div class="row mb-2">
                            <div class="col-sm-2">
                                <strong>Bentuk Dada</strong>
                            </div> 

                            <div class="col-sm-10">

                            <!-- Radio -->
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="bentukdada" value="simetris">
                                <label class="form-check-label">Simetris</label>
                            </div>
                            
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="bentukdada" value="tidaksimetris">
                                <label class="form-check-label">Tidak Simetris</label>
                            </div>

                            <div class="row mt-2">
                            
                            <!-- Lainnya -->
                            <div class="col-sm-11">
                                <label><strong>Lainnya</strong></label>
                                <input type="text" class="form-control" name="lainnyabentukdada">

                                <!-- comment -->
                                    <textarea class="form-control mt-2" name="commentlainnyabentukdada" id="commentlainnyabentukdada" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                                        oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                                    </div>

                                    <div class="col-sm-1 d-flex align-items-start">
                                        <div class="form-check mt-4">
                                            <input class="form-check-input" type="checkbox" disabled>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> 

                <!-- Cardiovaskuler (B2: Bleeding) -->
                    <div class="row mb-3">
                        <label class="col-sm-10 col-form-label text-primary">
                            <strong>b. Cardiovaskuler (B2: Bleeding)</strong>
                    </div>   

                    <!-- Nyeri Dada -->
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Nyeri Dada</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="nyeridada" value="ya">
                            <label class="form-check-label">Ya</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="nyeridada" value="tidak">
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>

                        <!-- Pusing -->
                        <div class="row mb-2">
                            <div class="col-sm-2">
                                <strong>Pusing</strong>
                            </div> 

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="pusing" value="ya">
                                        <label class="form-check-label">Ya</label>
                                    </div>
                                </div>

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="pusing" value="tidak">
                                        <label class="form-check-label">Tidak</label>
                                </div>
                            </div>
                        </div> 
                        
                        <!-- Sakit Kepala -->
                        <div class="row mb-2">
                            <div class="col-sm-2">
                                <strong>Sakit Kepala</strong>
                            </div> 

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="sakitkepala" value="ya">
                                        <label class="form-check-label">Ya</label>
                                    </div>
                                </div>

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="sakitkepala" value="tidak">
                                        <label class="form-check-label">Tidak</label>
                                </div>
                            </div>
                        </div>

                      <!-- Palpitasi -->
                        <div class="row mb-2">
                            <div class="col-sm-2">
                                <strong>Palpitasi</strong>
                            </div> 

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="palpitasi" value="ya">
                                        <label class="form-check-label">Ya</label>
                                    </div>
                                </div>

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="palpitasi" value="tidak">
                                        <label class="form-check-label">Tidak</label>
                                </div>
                            </div>
                        </div>  

                        <!-- Clubbing Finger -->
                        <div class="row mb-2">
                            <div class="col-sm-2">
                                <strong>Clubbing Finger</strong>
                            </div> 

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="clubbingfinger" value="ya">
                                        <label class="form-check-label">Ya</label>
                                    </div>
                                </div>

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="clubbingfinger" value="tidak">
                                        <label class="form-check-label">Tidak</label>
                                </div>
                            </div>
                        </div>

                    <!-- Suara Jantung -->
                        <div class="row mb-2">
                            <div class="col-sm-2">
                                <strong>Suara Jantung</strong>
                            </div>

                            <!-- Normal -->
                          
                            <div class="col-sm-2">
                                <strong>Normal</strong>
                            </div>

                            <div class="col-sm-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="suarajantung" value="normal">
                                        <label class="form-check-label">Normal (S1/S2 Tunggal)</label>
                                    </div>
                                </div>
                        </div> 

                        <!-- Kelainan -->

                        <div class="row mb-2">
                            <div class="col-sm-2">
                        </div>
                        
                        <div class="col-sm-2">
                                <strong>Kelainan</strong>
                            </div>

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="suarajantung" value="kelainans3">
                                        <label class="form-check-label">S3</label>
                                    </div>
                                </div>

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="suarajantung" value="kelainans4">
                                        <label class="form-check-label">S4</label>
                                    </div>
                                </div>
                             
                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="suarajantung" value="kelainanmurmur">
                                        <label class="form-check-label">Mur-mur</label>
                                    </div>
                                </div>  
                                
                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="suarjantung" value="gallop">
                                        <label class="form-check-label">Gallop</label>
                                    </div>
                                </div>    
                        </div> 

                    <!-- Edema -->
                        <div class="row mb-2">
                            <div class="col-sm-2">
                                <strong>Edema</strong>
                            </div>

                            <!-- Radio -->
                            <div class="col-sm-2"> 
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="edema" value="palpebra">
                                <label class="form-check-label">Palpebra</label>
                            </div>
                            </div>

                            <div class="col-sm-2">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="edema" value="anasarka">
                                <label class="form-check-label">Anasarka</label>
                            </div>
                            </div>

                            <div class="col-sm-2">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="edema" value="ekstremitasatas">
                                <label class="form-check-label">Ekstremitas Atas</label>
                            </div>
                            </div>

                            <div class="col-sm-2">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="edema" value="ekstremitasbawah">
                                <label class="form-check-label">Ekstremitas Bawah</label>
                            </div>
                            </div>

                            <div class="col-sm-2">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="edema" value="ascites">
                                <label class="form-check-label">Ascites</label>
                            </div>
                            </div>

                            <div class="col-sm-2"></div>
                            
                            <!-- Lainnya -->
                            <div class="col-sm-9">
                                <label><strong>Lainnya</strong></label>
                                <input type="text" class="form-control" name="lainnyaedema">

                                <!-- comment -->
                                    <textarea class="form-control mt-2" name="commentlainnyaedema" id="commentlainnyaedema" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                                        oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                                    </div>

                                    <div class="col-sm-1 d-flex align-items-start">
                                        <div class="form-check mt-4">
                                            <input class="form-check-input" type="checkbox" disabled>
                                        </div>
                                    </div>
                                </div> 
                                
                            <div class="row mt-2">
                            <div class="col-sm-2"></div>    

                            <!-- Sebutkan -->
                            <div class="col-sm-9">
                                <label><strong>Sebutkan</strong></label>
                                <input type="text" class="form-control" name="sebutkanaedema">

                                <!-- comment -->
                                    <textarea class="form-control mt-2" name="commentsebutkanedema" id="commentsebutkanedema" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                                        oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                                    </div>

                                    <div class="col-sm-1 d-flex align-items-start">
                                        <div class="form-check mt-4">
                                            <input class="form-check-input" type="checkbox" disabled>
                                        </div>
                                    </div>
                                </div>
                                                      
                    <!-- Persyarafan (B3: Brain) -->
                    <div class="row mb-3">
                        <label class="col-sm-10 col-form-label text-primary">
                            <strong>c. Persyarafan (B3: Brain)</strong>
                    </div>   

                    <!-- Kesadaran -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Kesadaran</strong></label>

                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="kesadaran">

                                <!-- comment -->
                                    <textarea class="form-control mt-2" name="commentkesadaran" id="commentkesadaran" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                                        oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                                    </div>

                                    <div class="col-sm-1 d-flex align-items-start">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" disabled>
                                        </div>
                                    </div>
                                </div>

                    <!-- GCS -->

                        <div class="row mb-3">

                        <label class="col-sm-2 col-form-label"><strong>Glasgow Coma Scale (GCS)</strong></label>
                            <div class="col-sm-9">
                                <div class="row">

                        <!-- E -->
                        <div class="col-md-4 d-flex align-items-center">
                            <label class="me-2"><strong>E</strong></label>
                            <input type="text" class="form-control" name="e">
                        </div>

                        <!-- M -->
                        <div class="col-md-4 d-flex align-items-center">
                            <label class="me-2"><strong>M</strong></label>
                            <input type="text" class="form-control" name="m">
                        </div>

                        <!-- V -->
                        <div class="col-md-4 d-flex align-items-center">
                            <label class="me-2"><strong>V</strong></label>
                            <input type="text" class="form-control" name="v">
                        </div>
                    </div>

                         <!-- comment -->
                        <textarea class="form-control mt-2" name="commentemv" id="commentemv" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>

                    </div>

                    <!-- Kejang -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Kejang</strong></label>

                        <div class="col-sm-9">
                            <select class="form-select" name="kejang" required>
                                <option value="">Pilih</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak Ada">Tidak Ada</option>
                            </select> 

                     <!-- comment -->
                            <textarea class="form-control mt-2" name="commentkejang" id="commentkejang" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>            
                                
                    <!-- Kepala -->
                        <div class="row mb-2">
                            <div class="col-sm-2">
                                <strong>Kepala</strong>
                            </div> 

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="kepala" value="mesosepal">
                                        <label class="form-check-label">Mesosepal</label>
                                    </div>
                                </div>

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="kepala" value="asimetris">
                                        <label class="form-check-label">Asimetris</label>
                                </div>
                            </div>

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="kepala" value="hematoma">
                                        <label class="form-check-label">Hematoma</label>
                                </div>
                            </div>
                        </div>

                    <!-- Wajah -->
                        <div class="row mb-2">
                            <div class="col-sm-2">
                                <strong>Wajah</strong>
                            </div> 

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="kepala" value="simetris">
                                        <label class="form-check-label">Simetris</label>
                                    </div>
                                </div>

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="kepala" value="asimetris">
                                        <label class="form-check-label">Asimetris</label>
                                    </div>
                                </div>

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="kepala" value="bellpals">
                                        <label class="form-check-label">Bell Palsy</label>
                                </div>
                            </div>

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="kepala" value="Kelainan Kongenital">
                                        <label class="form-check-label">Kelainan Kongenital</label>
                                </div>
                            </div>
                        </div>        
            
                        <!-- Mata -->

                        <div class="row mb-2">
                            <div class="col-sm-2"><strong>Mata</strong>
                        </div>
                        
                        <!-- Sklera -->

                        <div class="col-sm-2">
                                <strong>Sklera</strong>
                            </div>

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="sklera" value="putih">
                                        <label class="form-check-label">Putih</label>
                                    </div>
                                </div>

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="sklera" value="ikterus">
                                        <label class="form-check-label">Ikterus</label>
                                    </div>
                                </div>
                             
                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="sklera" value="merah">
                                        <label class="form-check-label">Merah</label>
                                    </div>
                                </div>  
                                
                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="sklera" value="pendarahan">
                                        <label class="form-check-label">Pendarahan</label>
                                    </div>
                                </div>    
                        </div>
                        
                        <!-- Konjunctiva -->
                         
                        <div class="row mb-2">
                            <div class="col-sm-2">
                        </div>

                        <div class="col-sm-2">
                                <strong>Konjunctiva</strong>
                            </div>

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="konjunctiva" value="putih">
                                        <label class="form-check-label">Putih</label>
                                    </div>
                                </div>

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="konjunctiva" value="merahmuda">
                                        <label class="form-check-label">Merah Muda</label>
                                    </div>
                                </div>
                        </div>

                    <!-- Pupil -->
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-2 col-form-label"><strong>Pupil</strong></label>
                        <div class="col-sm-3">
                                <input type="text" class="form-control" name="pupil">
                        </div>    
                                
                    <!-- Ukuran -->
                    <label class="col-sm-2 col-form-label"><strong>Ukuran</strong></label>
                    <div class="col-sm-3">
                        <input type="text" class="form-control" name="ukuran">
                    </div>    

                    <div class="col-sm-1">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" disabled>
                        </div>
                    </div>   
                </div>

                <div class="row mb-3">
                    <div class="col-sm-9 offset-sm-2">
                        <textarea class="form-control" rows="2" placeholder="Kolom Ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakan!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                     </div>
                </div> 
                
                <!-- Leher -->
                <div class="row mb-2">
                    <div class="col-sm-2">
                        <strong>Leher</strong>
                    </div> 

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="leher" value="kesulitanmenelan">
                                <label class="form-check-label">Kesulitan Menelan</label>
                            </div>
                        </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="leher" value="suaraparau">
                                <label class="form-check-label">Suara Parau</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="leher" value="pembesarantiroid">
                                <label class="form-check-label">Pembesaran Tiroid</label>
                                </div>
                        </div>

                     <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="leher" value="jvp">
                                <label class="form-check-label">JVP</label>
                                </div>
                        </div>
                    </div>

                <!-- Refleks Tendon Normal -->
                <div class="row mb-2">
                    <div class="col-sm-2">
                        <strong>Refleks Tendon Normal</strong>
                    </div> 

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="reflekstendonnormal" value="bisep">
                                <label class="form-check-label">Bisep</label>
                            </div>
                        </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="reflekstendonnormal" value="trisep">
                                <label class="form-check-label">Trisep</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="reflekstendonnormal" value="brakhialis">
                                <label class="form-check-label">Brakhialis</label>
                                </div>
                        </div>

                     <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="reflekstendonnormal" value="patella">
                                <label class="form-check-label">Pattela</label>
                                </div>
                        </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="reflekstendonnormal" value="achilles">
                                <label class="form-check-label">Achilles</label>
                                </div>
                        </div>
                    </div>

                <!-- Refleks Tidak Normal -->
                <div class="row mb-2">
                    <div class="col-sm-2">
                        <strong>Refleks Tidak Normal</strong>
                    </div> 

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="reflekstidaknormal" value="kakukuduk">
                                <label class="form-check-label">Kaku Kuduk</label>
                            </div>
                        </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="reflekstidaknormal" value="babinski">
                                <label class="form-check-label">Babinski</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="reflekstidaknormal" value="bruzinski">
                                <label class="form-check-label">Bruzinski</label>
                                </div>
                        </div>

                     <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="reflekstidaknormal" value="kernigsign">
                                <label class="form-check-label">Kernig Sign</label>
                                </div>
                        </div>
                    </div>

                    <!-- Persepsi Sensori -->
                    <div class="row mb-3">
                        <label class="col-sm-10 col-form-label">
                            <strong>Persepsi Sensori</strong>

                     <!-- Pendengaran -->

                        <div class="row mb-2">
                            <div class="col-sm-2"><strong>Pendengaran</strong>
                        </div>
                        
                        <!-- Kiri -->

                        <div class="col-sm-2">
                                <strong>Kiri</strong>
                            </div>

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="kiri" value="baik">
                                        <label class="form-check-label">Baik</label>
                                    </div>
                                </div>

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="kiri" value="tidakbaik">
                                        <label class="form-check-label">Tidak Baik</label>
                                    </div>
                                </div>  
                        </div>
                        
                        <!-- Kanan -->
                         
                        <div class="row mb-2">
                            <div class="col-sm-2">
                        </div>

                        <div class="col-sm-2">
                                <strong>Kanan</strong>
                            </div>

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="kanan" value="baik">
                                        <label class="form-check-label">Baik</label>
                                    </div>
                                </div>

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="kanan" value="tidakbaik">
                                        <label class="form-check-label">Tidak Baik</label>
                                    </div>
                                </div>
                        </div>

                    <!-- Penciuman -->
                        <div class="row mb-2">
                            <div class="col-sm-2">
                                <strong>Penciuman</strong>
                            </div> 

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="penciuman" value="baik">
                                        <label class="form-check-label">Baik</label>
                                    </div>
                                </div>

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="penciuman" value="tidakbaik">
                                        <label class="form-check-label">Tidak Baik</label>
                                </div>
                            </div>
                        </div>

                    <!-- Pengecapan -->
                        <div class="row mb-2">
                            <div class="col-sm-2">
                                <strong>Pengecapan</strong>
                            </div> 

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="pengecapan" value="baik">
                                        <label class="form-check-label">Baik</label>
                                    </div>
                                </div>

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="pengecapan" value="tidakbaik">
                                        <label class="form-check-label">Tidak Baik</label>
                                </div>
                            </div>
                        </div>    

<!-- Penglihatan -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Penglihatan</strong></label>

    <div class="col-sm-9">

        <!-- Kiri -->
        <div class="row mb-2">
            <div class="col-sm-2"><strong>Kiri</strong></div>

            <div class="col-sm-2">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="kiri" value="baik">
                    <label class="form-check-label">Baik</label>
                </div>
            </div>

            <div class="col-sm-3">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="kiri" value="tidakbaik">
                    <label class="form-check-label">Tidak Baik</label>
                </div>
            </div>
        </div>

        <!-- Kanan -->
        <div class="row">
            <div class="col-sm-2"><strong>Kanan</strong></div>

            <div class="col-sm-2">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="kanan" value="baik">
                    <label class="form-check-label">Baik</label>
                </div>
            </div>

            <div class="col-sm-3">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="kanan" value="tidakbaik">
                    <label class="form-check-label">Tidak Baik</label>
                </div>
            </div>
        </div>
</div>
</div>
</div>

                        <!-- Alat Bantu -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Alat Bantu</strong></label>

                            <div class="col-sm-9">
                                <textarea name="alatbantu" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                                
                        <!-- comment -->
                                <textarea class="form-control mt-2" name="commentalatbantu" id="commentalatbantu" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                            </div>

                            <div class="col-sm-1 d-flex align-items-start">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" disabled>
                                </div>
                            </div>
                        </div> 

                    <!-- Perabaan -->

                        <div class="row mb-2">
                            <div class="col-sm-2"><strong>Perabaan</strong>
                        </div>
                        
                        <!-- Panas -->

                        <div class="col-sm-2">
                                <strong>Panas</strong>
                            </div>

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="panas" value="baik">
                                        <label class="form-check-label">Baik</label>
                                    </div>
                                </div>

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="panas" value="tidak">
                                        <label class="form-check-label">Tidak</label>
                                    </div>
                                </div>  
                        </div>
                        
                        <!-- Dingin -->
                         
                        <div class="row mb-2">
                            <div class="col-sm-2">
                        </div>

                        <div class="col-sm-2">
                                <strong>Dingin</strong>
                            </div>

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="dingin" value="baik">
                                        <label class="form-check-label">Baik</label>
                                    </div>
                                </div>

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="dingin" value="tidak">
                                        <label class="form-check-label">Tidak</label>
                                    </div>
                                </div>
                        </div>

                        <!-- Tekan -->
                         
                        <div class="row mb-2">
                            <div class="col-sm-2">
                        </div>

                        <div class="col-sm-2">
                                <strong>Tekan</strong>
                            </div>

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="tekan" value="baik">
                                        <label class="form-check-label">Baik</label>
                                    </div>
                                </div>

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="tekan" value="tidak">
                                        <label class="form-check-label">Tidak</label>
                                    </div>
                                </div>
                        </div>

                <!-- Perkemihan-Eliminasi Urin (B4: Bladder) -->
                    <div class="row mb-3">
                        <label class="col-sm-10 col-form-label text-primary">
                            <strong>d. Perkemihan-Eliminasi Urin (B4: Bladder)</strong>
                    </div>   

                   <!-- Produksi Urine -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Produksi Urine</strong></label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="produksiurine">
                                    <span class="input-group-text">ml</span>
                            </div>

                                <!-- comment -->
                                    <textarea class="form-control mt-2" name="commentproduksiurine" id="commentproduksiurine" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                                        oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                                    </div>

                                    <div class="col-sm-1 d-flex align-items-start">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" disabled>
                                        </div>
                                    </div>
                                </div>

                    
                   <!-- Frekuensi -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Frekuensi</strong></label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="frekuensi">
                                    <span class="input-group-text">/hari</span>
                            </div>

                                <!-- comment -->
                                    <textarea class="form-control mt-2" name="commentfrekuensi" id="commentfrekuensi" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                                        oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                                    </div>

                                    <div class="col-sm-1 d-flex align-items-start">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" disabled>
                                        </div>
                                    </div>
                                </div>  
                                
                    
                   <!-- Warna -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Warna</strong></label>

                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="warna">

                                <!-- comment -->
                                    <textarea class="form-control mt-2" name="commentwarna" id="commentwarna" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                                        oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                                    </div>

                                    <div class="col-sm-1 d-flex align-items-start">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" disabled>
                                        </div>
                                    </div>
                                </div>  
                                
                   <!-- Bau -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Bau</strong></label>

                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="bau">
                        

                                <!-- comment -->
                                    <textarea class="form-control mt-2" name="commentbau" id="commentbau" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                                        oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                                    </div>

                                    <div class="col-sm-1 d-flex align-items-start">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" disabled>
                                        </div>
                                    </div>
                                </div>            
                                
                    <!-- Douwer Cateter -->
                        <div class="row mb-2">
                            <div class="col-sm-2">
                                <strong>Douwer Cateter</strong>
                            </div> 

                            <div class="col-sm-9">

                            <!-- Radio -->
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="douwercateter" value="ya">
                                <label class="form-check-label">Ya</label>
                            </div>
                            
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="douwercateter" value="tidak">
                                <label class="form-check-label">Tidak</label>
                            </div>

                            <!-- Hari Ke -->
                            <div class="mt-2"> 
                                <label><strong>Hari Ke</strong></label>
                                <input type="text" class="form-control" name="harike_cateter">
                            </div>

                                <!-- comment -->
                                    <textarea class="form-control mt-2" name="commentharike" id="commentharike" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                                        oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                                    
                            </div>

                                    <div class="col-sm-1 mt-4 d-flex align-items-start">
                                        <div class="form-check mt-4">
                                            <input class="form-check-input" type="checkbox" disabled>
                                        </div>
                                    </div>
                                </div>
                           
                    <!-- Spolling Blass -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Spolling Blass dengan cairan NaCL 0,9%</strong></label>

                            <div class="col-sm-9">
                                <select class="form-select" name="spollingblass">
                                    <option value="">Pilih</option>
                                    <option value="Ya">Ya</option>
                                    <option value="Tidak">Tidak</option>
                            </select>
                            
                     <!-- comment -->
                            <textarea class="form-control mt-2" name="commentspollingblass" id="commentspollingblass" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>   
                    
                    <!-- Kelainan dalam Urine -->

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Kelainan dalam Urine (Sebutkan)</strong></label>

                        <div class="col-sm-9">
                            <textarea name="kelainandalamurine" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentkelainandalamurine" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>
                    
            <!-- Pencernaan-Eliminasi Alvi (B5: Bowel) -->
                    <div class="row mb-3">
                        <label class="col-sm-10 col-form-label text-primary">
                            <strong>e. Pencernaan-Eliminasi Alvi (B5: Bowel)</strong>
                    </div>   

                   <!-- Mulut dan Tenggorokan -->

                        <div class="row mb-2">
                            <div class="col-sm-2"><strong>Mulut dan Tenggorokan</strong>
                        </div>

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="mulutdantenggorokan[]" value="mukosakering">
                                        <label class="form-check-label">Mukosa Kering</label>
                                    </div>
                                </div>

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="mulutdantenggorokan[]" value="merahmudah">
                                        <label class="form-check-label">Merah Muda</label>
                                    </div>
                                </div>
                             
                            <div class="col-sm-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="mulutdantenggorokan[]" value="kesulitanmenelan">
                                        <label class="form-check-label">Kesulitan Menelan</label>
                                    </div>
                                </div>   
                        </div>

                    <!-- Abdomen -->

                        <div class="row mb-2">
                            <div class="col-sm-2"><strong>Abdomen</strong>
                        </div>

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="abdomen" value="distensi">
                                        <label class="form-check-label">Distensi</label>
                                    </div>
                                </div>

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="abdomen" value="nyeritekan">
                                        <label class="form-check-label">Nyeri Tekan</label>
                                    </div>
                                </div>
                        </div>

                    <!-- Rektum -->

                        <div class="row mb-2">
                            <div class="col-sm-2"><strong>Rektum</strong>
                        </div>

                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="rektum" value="adakelainan">
                                        <label class="form-check-label">Ada Kelainan</label>
                                    </div>
                                </div>

                            <div class="col-sm-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="rektum" value="tidakadakelainan">
                                        <label class="form-check-label">Tidak Ada Kelainan</label>
                                    </div>
                                </div>
                        </div>

                    <!-- Anjuran Puasa -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Anjuran Puasa</strong></label>
                        <div class="col-sm-3">
                            <select class="form-select" name="anjuranpuasa">
                                <option value="">Pilih</option>
                                <option value="Terpasang">Terpasang</option>
                                <option value="Tidak Terpasang">Tidak Terpasang</option>
                            </select>  
                        </div>
                                
                    <!-- Selama -->
                    <label class="col-sm-2 col-form-label"><strong>Selama</strong></label>
                    <div class="col-sm-3">
                                <input type="text" class="form-control" name="selama">
                        </div>

                    <div class="col-sm-1">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" disabled>
                        </div>
                    </div>   
                </div>

                <div class="row mb-3">
                    <div class="col-sm-9 offset-sm-2">
                        <textarea class="form-control" rows="2" placeholder="Kolom Ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakan!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                     </div>
                </div>
                
                <!-- Diet yang diberikan -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Diet yang diberikan</strong></label>

                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="dietyangdiberikan">

                <!-- comment -->
                    <textarea class="form-control mt-2" name="commentdietyangdiberikan" id="commentdietyangdiberikan" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                        oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                    </div>

                    <div class="col-sm-1 d-flex align-items-start">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" disabled>
                        </div>
                    </div>
                </div>

                <!-- Terpasang NGT -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Terpasang NGT</strong></label>
                        <div class="col-sm-3">
                            <select class="form-select" name="terpasangngt">
                                <option value="">Pilih</option>
                                <option value="Terpasang">Terpasang</option>
                                <option value="Tidak Terpasang">Tidak Terpasang</option>
                            </select>  
                        </div>
                                
                    <!-- Hari Ke -->
                    <label class="col-sm-2 col-form-label"><strong>Hari Ke</strong></label>
                    <div class="col-sm-3">
                                <input type="number" class="form-control" name="harike_ngt">
                        </div>

                    <div class="col-sm-1">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" disabled>
                        </div>
                    </div>   
                </div>

                <div class="row mb-3">
                    <div class="col-sm-9 offset-sm-2">
                        <textarea class="form-control" rows="2" placeholder="Kolom Ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakan!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                     </div>
                </div>

                 <!-- Kelainan Saluran Cerna -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Kelainan Saluran Cerna</strong></label>

                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="kelainansalurancerna">

                                <!-- comment -->
                                    <textarea class="form-control mt-2" name="commentkelainansalurancerna" id="commentkelainansalurancerna" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                                        oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                                    </div>

                                    <div class="col-sm-1 d-flex align-items-start">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" disabled>
                                        </div>
                                    </div>
                                </div>

            <!-- Tulang-Otot-Integumen (B6: Bone) -->
                    <div class="row mb-3">
                        <label class="col-sm-10 col-form-label text-primary">
                            <strong>f. Tulang-Otot-Integumen (B6: Bone)</strong>
                    </div>  
                    
                    <!-- Kelainan pada Tulang -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Kelainan pada Tulang</strong></label>

                        <div class="col-sm-9">
                            <textarea name="kelainanpadatulang" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentkelainanpadatulang" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 

                    <!-- Kekuatan Otot -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Kekuatan Otot</strong></label>

                        <div class="col-sm-9">
                            <textarea name="kekuatanotot" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentkekuatanotot" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 

                    <!-- Hemiparese -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Hemiparese</strong></label>

                        <div class="col-sm-9">
                            <select class="form-select" name="hemiparese">
                                <option value="">Pilih</option>
                                <option value="Ada">Ada</option>
                                <option value="Tidak Ada">Tidak Ada</option>

                                </select>
                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commenthemiparese" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 

                    <!-- Tetraparese -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Tetraparese</strong></label>

                        <div class="col-sm-9">
                            <select class="form-select" name="tetraparese">
                                <option value="">Pilih</option>
                                <option value="Ada">Ada</option>
                                <option value="Tidak Ada">Tidak Ada</option>
                            </select>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commenttetraparese" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 

                    <!-- ROM -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>ROM</strong></label>

                        <div class="col-sm-9">
                            <textarea name="rom" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentrom" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>

                    <!-- Lainnya -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Lainnya</strong></label>

                        <div class="col-sm-9">
                            <textarea name="lainnya" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" name="lainnya" id="commentlainnya" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>

                    <!-- Ekstremitas -->
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Ekstremitas</strong></div>
                        <div class="col-sm-2"><strong>Atas</strong></div>
                        
                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="ekstremitasatas" value="tidakadakelainan">
                                <label class="form-check-label">Tidak Ada Kelainan</label>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="ekstremitasatas" value="peradangan">
                                <label class="form-check-label">Peradangan</label>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="ekstremitasatas" value="patahtulang">
                                <label class="form-check-label">Patah Tulang</label>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-sm-2"></div>
                        <div class="col-sm-2"><strong>Bawah</strong></div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="ekstremitasbawah" value="tidakadakelainan">
                                <label class="form-check-label">Tidak Ada Kelainan</label>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="ekstremitasbawah" value="peradangan">
                                <label class="form-check-label">Peradangan</label>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="ekstremitasbawah" value="patahtulang">
                                <label class="form-check-label">Patah Tulang</label>
                            </div>
                        </div>
                    </div>

                    <!-- Tulang Belakang -->
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Tulang Belakang</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="tulangbelakang" value="kifosis">
                            <label class="form-check-label">Kifosis</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="tulangbelakang" value="lordosis">
                            <label class="form-check-label">Lordosis</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="tulangbelakang" value="skoliosis">
                            <label class="form-check-label">Skoliosis</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="tulangbelakang" value="nyeri">
                            <label class="form-check-label">Nyeri</label>
                        </div>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-sm-2"></div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="tulangbelakang" value="tidakadakelainan">
                            <label class="form-check-label">Tidak Ada Kelainan</label>
                        </div>
                    </div>
                </div>
                    
                <!-- Kulit -->
                <div class="row mb-2">
                    <div class="col-sm-2"><strong>Kulit</strong></div>
                    <div class="col-sm-2"><strong>Warna Kulit</strong></div>
        
                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="warnakulit" value="ikterik">
                             <label class="form-check-label">Ikterik</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="warnakulit" value="pigmentasi">
                            <label class="form-check-label">Pigmentasi</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="warnakulit" value="sianotik">
                            <label class="form-check-label">Sianotik</label>
                        </div>
                    </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="warnakulit" value="pucat">
                                <label class="form-check-label">Pucat</label>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="warnakulit" value="kemerahan">
                                <label class="form-check-label">Kemerahan</label>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-sm-2"></div>
                        <div class="col-sm-2"><strong>Akral</strong></div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="akral" value="hangat">
                                <label class="form-check-label">Hangat</label>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="akral" value="panas">
                                <label class="form-check-label">Panas</label>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="akral" value="dinginkering">
                                <label class="form-check-label">Dingin Kering</label>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="akral" value="dinginbasah">
                                <label class="form-check-label">Dingin Basah</label>
                            </div>
                        </div>
                    </div>
    
                    <!-- Turgor -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Turgor</strong></label>

                        <div class="col-sm-9">
                            <div class="input-group">
                                    <input type="text" class="form-control" name="turgor">
                                    <span class="input-group-text">detik</span>
                        </div>

                     <!-- comment -->
                            <textarea class="form-control mt-2" name="commentturgor" id="commentturgor" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                        </div>
                    </div> 
            
                <!-- Sistem Endokrin -->
                    <div class="row mb-3">
                        <label class="col-sm-10 col-form-label text-primary">
                            <strong>g. Sistem Endokrin</strong>
                    </div> 
                    
                    <!-- Terapi Hormon -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Terapi Hormon</strong></label>

                            <div class="col-sm-9">
                               <textarea name="terapihormon" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                                <!-- comment -->
                                    <textarea class="form-control mt-2" name="commentterapihormon" id="commentterapihormon" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                                        oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                                    </div>

                                    <div class="col-sm-1 d-flex align-items-start">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" disabled>
                                        </div>
                                    </div>
                                </div>

                <!-- Sistem Reproduksi -->
                    <div class="row mb-3">
                        <label class="col-sm-10 col-form-label text-primary">
                            <strong>h. Sistem Reproduksi</strong>
                    </div>   

                    <!-- Sistem Reproduksi -->
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Sistem Reproduksi</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="sistemreproduksi" value="lakilaki">
                            <label class="form-check-label">Laki-laki</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="sistemreproduksi" value="perempuan">
                            <label class="form-check-label">Perempuan</label>
                        </div>
                    </div>
                </div>

                <!-- Kelamin (bentuk) -->
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Kelainan Bentuk</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="kelainanbentuk" value="normal">
                            <label class="form-check-label">Normal</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="kelainanbentuk" value="tidaknormal">
                            <label class="form-check-label">Tidak Normal</label>
                        </div>
                    </div>
                </div>
                        
                <!-- Kebersihan -->
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Kebersihan</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="kebersihan" value="bersih">
                            <label class="form-check-label">Bersih</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="kebersihan" value="kotor">
                            <label class="form-check-label">Kotor</label>
                        </div>
                    </div>
                </div>

                <!-- Pola Aktivitas -->
                    <div class="row mb-1">
                        <label class="col-sm-10 col-form-label text-primary"><strong>i. Pola Aktivitas</strong></label>
                    </div> 

                    <div class="row mb-3">
                        <label class="col-sm-10 col-form-label"><strong>Makan</strong></label>
                    </div>

                    <!-- Frekuensi -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Frekuensi</strong></label>

                        <div class="col-sm-9">
                            <textarea name="frekuensi" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" name="commentfrekuensi" id="commentfrekuensi" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 mt-4 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 
                    
                    <!-- Jenis Menu -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Jenis Menu</strong></label>

                        <div class="col-sm-9">
                           <textarea name="jenismenu" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" name="commentjenismenu" id="commentjenismenu" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 mt-4 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 

                    <!-- Pantangan -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Pantangan</strong></label>

                        <div class="col-sm-9">
                            <textarea name="pantangan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" name="commentpantangan" id="commentpantangan" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 mt-4 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 

                    <!-- Alergi -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Alergi</strong></label>

                        <div class="col-sm-9">
                            <textarea name="alergi" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" name="commentalergi" id="commentalergi" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 mt-4 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>
                    
                    <!-- Minum -->
                    <div class="row mb-3">
                        <label class="col-sm-10 col-form-label"><strong>Minum</strong></label>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Frekuensi</strong></label>

                        <div class="col-sm-9">
                            <textarea name="minumfrekuensi" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" name="commentminumfrekuensi" id="commentminumfrekuensi" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 mt-4 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 
                    
                    <!-- Jenis Menu -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Jenis Menu</strong></label>

                        <div class="col-sm-9">
                            <textarea name="minumjenismenu" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" name="commentminumjenismenu" id="commentminumjenismenu" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 mt-4 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 

                    <!-- Pantangan -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Pantangan</strong></label>

                        <div class="col-sm-9">
                            <textarea name="minumpantangan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" name="commentminumpantangan" id="commentminumpantangan" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 mt-4 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 

                    <!-- Alergi -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Alergi</strong></label>

                        <div class="col-sm-9">
                           <textarea name="minumalergi" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" name="commentminumalergi" id="commentminumalergi" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 mt-4 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>

                    <!-- Kebersihan Diri -->
                    <div class="row mb-3">
                        <label class="col-sm-10 col-form-label"><strong>Kebersihan Diri</strong></label>
                    </div>    

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Mandi</strong></label>

                        <div class="col-sm-9">
                            <textarea name="mandi" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" name="commentmandi" id="commentmandi" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 mt-4 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 
                    
                    <!-- Keramas -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Keramas</strong></label>

                        <div class="col-sm-9">
                            <textarea name="keramas" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" name="commentkeramas" id="commentkeramas" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 mt-4 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 

                    <!-- Sikat Gigi -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Sikat Gigi</strong></label>

                        <div class="col-sm-9">
                            <textarea name="sikatgigi" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" name="commentsikatgigi" id="commentsikatgigi" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 mt-4 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 

                    <!-- Memotong Kuku -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Memotong Kuku</strong></label>

                        <div class="col-sm-9">
                            <textarea name="memotongkuku" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" name="commentmemotongkuku" id="commentmemotongkuku" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 mt-4 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>

                    <!-- Ganti Pakaian -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Ganti Pakaian</strong></label>

                        <div class="col-sm-9">
                            <textarea name="gantipakaian" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" name="commentgantipakaian" id="commentgantipakaian" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 mt-4 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>

                    <!-- Masalah Lain -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Masalah Lain</strong></label>

                        <div class="col-sm-9">
                            <textarea name="masalahlainkebersihandiri" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" name="commentmasalahlainkebersihandiri" id="commentmasalahlainkebersihandiri" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 mt-4 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>


                <!-- Social Interaction (Interaksi Sosial) -->
                    <div class="row mb-2">
                        <label class="col-sm-10 col-form-label text-primary">
                            <strong>j. Social Interaction (Interaksi Sosial)</strong>
                    </div>  
                    
                <!-- Dukungan Keluarga -->
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Dukungan Keluarga</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="dukungankeluarga" value="aktif">
                            <label class="form-check-label">Aktif</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="dukungankeluarga" value="kurang">
                            <label class="form-check-label">Kurang</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="dukungankeluarga" value="tidakada">
                            <label class="form-check-label">Tidak Ada</label>
                        </div>
                    </div>
                </div>

                <!-- Dukungan Kelompok -->
                    <div class="row mb-2">
                        <div class="col-sm-2"><strong>Dukungan Keluarga/Teman/Masyarakat</strong>
                    </div>    

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="dukungankeluarga" value="aktif">
                            <label class="form-check-label">Aktif</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="dukungankeluarga" value="kurang">
                            <label class="form-check-label">Kurang</label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="dukungankel" value="tidakada">
                            <label class="form-check-label">Tidak Ada</label>
                        </div>
                    </div>
                </div>

                    <!-- Hubungan Klien dengan Keluarga/Teman Sejawat -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Hubungan Klien dengan Keluarga/Teman Sejawat</strong></label>

                        <div class="col-sm-9">
                            <textarea name="hubunganklien" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" name="comemnthubunganklien" id="commenthubunganklien" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 mt-4 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>

                     <!-- Yang Menunggu Klien Selama dalam Perawatan -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Yang Menunggu Klien Selama dalam Perawatan</strong></label>

                            <div class="col-sm-9">
                                <textarea name="menungguklien" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" name="commentmenungguklien" id="commentmenungguklien" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 
                            
                <!-- Pemeriksan Penunjunang -->
                    <div class="row mb-2">
                        <label class="col-sm-10 col-form-label text-primary">
                            <strong>k. Pemeriksaan Penunjang </strong>
                    </div> 
                    
                    <!-- Laboratorium -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Laboratorium</strong></label>

                        <div class="col-sm-9">
                            <input type="date" class="form-control" name="tgllaboratorium">
                            
                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commenthasillaboratorium" id="commenthasillaboratorium" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 mt-5 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 

                <!-- Bagian Pemeriksaan -->
                <div class="row mb-3">
                    <label for="pemeriksaan" class="col-sm-2 col-form-label"><strong>Pemeriksaan</strong></label>
                    <div class="col-sm-9">
                        <textarea name="pemeriksaan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" name="commentpemeriksaan" id="commentpemeriksaan" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 

                <!-- Bagian Hasil -->
                <div class="row mb-3">
                    <label for="hasil" class="col-sm-2 col-form-label"><strong>Hasil</strong></label>
                    <div class="col-sm-9">
                        <textarea name="hasil" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" name="commenthasil" id="commenthasil" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 
                    
                <!-- Bagian Satuan -->
                <div class="row mb-3">
                    <label for="satuan" class="col-sm-2 col-form-label"><strong>Satuan</strong></label>
                    <div class="col-sm-9">
                        <textarea name="satuan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" name="commentsatuan" id="commentsatuan" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>  
                    
                <!-- Bagian Nilai Rujukan -->
                <div class="row mb-3">
                    <label for="nilairujukan" class="col-sm-2 col-form-label"><strong>Nilai Rujukan</strong></label>
                    <div class="col-sm-9">
                        <textarea name="nilairujukan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" name="commentnilairujukan" id="commentnilairujukan" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>     

                <!-- Bagian Button -->    
                <div class="row mb-3">
                    <div class="col-sm-11 justify-content-end d-flex">
                        <button type="submit" name="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div> 
                
                <style>
                    .table-laboratorium {
                        table-layout: fixed;
                        width:100%
                    }

                    .table-laboratorium td,
                    .table-laboratorium th {
                        word-wrap: break-word;
                        white-space: normal;
                        vertical-align: top;
                    }
                    </style>

                    <table class="table table-bordered table-laboratorium">
                        <thead>
                            <tr>
                                <th class="text-center">Pemeriksaan</th>
                                <th class="text-center">Hasil</th>
                                <th class="text-center">Satuan</th>
                                <th class="text-center">Nilai Rujukan</th>
                        </tr>
                        </thead>

                    <tbody>

                    <?php
                    if(!empty($data)){
                        foreach($data as $row){
                            echo "<tr>
                            <td>".nlrbr($row['pemeriksaan'])."</td>
                            <td>".nlrbr($row['hasil'])."</td>
                            <td>".nlrbr($row['satuan'])."</td>
                            <td>".nlrbr($row['nilairujukan'])."</td>
                            </tr>";
                        }
                    }
                    ?>

                    </tbody>
                    </table>

                    <!-- Radiologi -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Radiologi</strong></label>

                        <div class="col-sm-9">
                            <input type="date" class="form-control" name="tglradiologi">
                            <small class="form-text" style="color: red;"> Hasil:</small>
                            <textarea name="radiologi" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" name="commentradiologi" id="commentradiologi" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 mt-5 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>  

                <!-- Pengobatan -->
                    <div class="row mb-2">
                        <label class="col-sm-10 col-form-label text-primary">
                            <strong>9. Pengobatan</strong>
                    </div>
                    
                    <!-- Bagian Nama Obat -->
                    <div class="row mb-3">
                        <label for="jenisobat" class="col-sm-2 col-form-label"><strong>Nama Obat</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="namaobat">
                            
                            <!-- comment -->
                                <textarea class="form-control mt-2" name="commentnamaobat" id="commentnamaobat" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                            </div>

                            <div class="col-sm-1 d-flex align-items-start">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" disabled>
                                </div>
                            </div>
                        </div>

                    <!-- Bagian Dosis -->
                    <div class="row mb-3">
                        <label for="dosis" class="col-sm-2 col-form-label"><strong>Dosis</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="dosis">

                            <!-- comment -->
                                <textarea class="form-control mt-2" id="commentdosis" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                            </div>

                            <div class="col-sm-1 d-flex align-items-start">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" disabled>
                                </div>
                            </div>
                        </div>
                        
                    <!-- Bagian Rute Pemberian -->
                    <div class="row mb-3">
                        <label for="rutepemberian" class="col-sm-2 col-form-label"><strong>Rute Pemberian</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="rutepemberian">
                            
                            <!-- comment -->
                                <textarea class="form-control mt-2" name="commentrutepemberian" id="commentrutepemberian" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                            </div>

                            <div class="col-sm-1 d-flex align-items-start">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" disabled>
                                </div>
                            </div>
                        </div>
                    
                    <!-- Bagian Berapa Kali Pemberian/hri -->
                    <div class="row mb-3">
                        <label for="pemberianobat" class="col-sm-2 col-form-label"><strong>Berapa Kali Pemberian/hari</strong></label>
                        <div class="col-sm-9">
                            <textarea name="pemberianobat" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                        <!-- comment -->
                                <textarea class="form-control mt-2" name="commentpemberianobat" id="commentpemberianobat" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                            </div>

                            <div class="col-sm-1 d-flex align-items-start">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" disabled>
                                </div>
                            </div>
                        </div> 
                        
                    <!-- Bagian Button -->    
                    <div class="row mb-3">
                        <div class="col-sm-11 justify-content-end d-flex">
                            <button type="submit" name="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </div>     
                        
                        <h5 class="card-title mt-2"><strong>Pemberian Obat</strong></h5>

                        <style>
                        .table-pemberianobat {
                            table-layout: fixed;
                            width:100%
                        }

                        .table-pemberianobat td,
                        .table-pemberianobat th {
                            word-wrap: break-word;
                            white-space: normal;
                            vertical-align: top;
                        }
                        </style>

                        <table class="table table-bordered table-pemberianobat">
                            <thead>
                                <tr>
                                    <th class="text-center">Nama Obat</th>
                                    <th class="text-center">Dosis</th>
                                    <th class="text-center">Rute Pemberian</th>
                                    <th class="text-center">Berapa Kali Pemberian/hari</th>
                            </tr>
                            </thead>

                        <tbody>

                        <?php
                        if(!empty($data)){
                            foreach($data as $row){
                                echo "<tr>
                                <td>".nlrbr($row['namaobat'])."</td>
                                <td>".nlrbr($row['dosis'])."</td>
                                <td>".nlrbr($row['rutepemberian'])."</td>
                                <td>".nlrbr($row['pemberianobat'])."</td>
                                </tr>";
                            }
                        }
                        ?>

                        </tbody>
                        </table>
                 
                <!-- Bagian Klasifikasi Data -->    

                <div class="row mb-2">
                    <label class="col-sm-6 col-form-label text-primary">
                        <strong>Klasifikasi Data</strong>
                </div>

                <!-- Bagian Data Subjektif (DS) -->
                <div class="row mb-3">
                    <label for="datasubjektif" class="col-sm-2 col-form-label"><strong>Data Subjektif (DS)</strong></label>
                    <div class="col-sm-9">
                        <textarea name="datasubjektif" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentdatasubjektif" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 

                <!-- Bagian Data Objektif (DO) -->
                <div class="row mb-3">
                    <label for="dataobjektif" class="col-sm-2 col-form-label"><strong>Data Objektif (DO)</strong></label>
                    <div class="col-sm-9">
                        <textarea name="dataobjektif" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentdataobjektif" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>  

                <!-- Bagian Button -->    
                <div class="row mb-3">
                    <div class="col-sm-11 justify-content-end d-flex">
                        <button type="submit" name="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div> 

                <h5 class="card-title"><strong>Klasifikasi Data</strong></h5>
                
                <style>
                    .table-klasifikasidata {
                        table-layout: fixed;
                        width:100%
                    }

                    .table-klasifikasidata td,
                    .table-klasifikasidata th {
                        word-wrap: break-word;
                        white-space: normal;
                        vertical-align: top;
                    }
                    </style>

                    <table class="table table-bordered table-klasifikasidata">
                        <thead>
                            <tr>
                                <th class="text-center">Data Subjektif (DS)</th>
                                <th class="text-center">Data Objektif (DO)</th>
                        </tr>
                        </thead>

                    <tbody>

                    <?php
                    if(!empty($data)){
                        foreach($data as $row){
                            echo "<tr>
                            <td>".nlrbr($row['datasubjektif'])."</td>
                            <td>".nlrbr($row['dataobjektif'])."</td>
                            </tr>";
                        }
                    }
                    ?>

                    </tbody>
                    </table>

            <!-- Bagian Analisa Data -->    

                <div class="row mb-2">
                    <label class="col-sm-6 col-form-label text-primary">
                        <strong>Analisa Data</strong>
                </div>

                <!-- Bagian DS/DO -->
                <div class="row mb-3">
                    <label for="dsdo" class="col-sm-2 col-form-label"><strong>DS/DO</strong></label>
                    <div class="col-sm-9">
                        <textarea name="dsdo" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentdsdo" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 

                <!-- Bagian Etiologi -->
                <div class="row mb-3">
                    <label for="etiologi" class="col-sm-2 col-form-label"><strong>Etiologi</strong></label>
                    <div class="col-sm-9">
                        <textarea name="etiologi" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentetiologi" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 
                    
                <!-- Bagian Masalah -->
                <div class="row mb-3">
                    <label for="masalah" class="col-sm-2 col-form-label"><strong>Masalah</strong></label>
                    <div class="col-sm-9">
                        <textarea name="masalah" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" id="commentmasalah" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 

                <!-- Bagian Button -->    
                <div class="row mb-3">
                    <div class="col-sm-11 justify-content-end d-flex">
                        <button type="submit" name="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>  
                
                <h5 class="card-title"><strong>Analisa Data</strong></h5>
                
                <style>
                    .table-analisadata {
                        table-layout: fixed;
                        width:100%
                    }

                    .table-analisadata td,
                    .table-analisadata th {
                        word-wrap: break-word;
                        white-space: normal;
                        vertical-align: top;
                    }
                    </style>

                    <table class="table table-bordered table-analisadata">
                        <thead>
                            <tr>
                                <th class="text-center">DS/DO</th>
                                <th class="text-center">Etiologi</th>
                                <th class="text-center">Masalah</th>
                        </tr>
                        </thead>

                    <tbody>

                    <?php
                    if(!empty($data)){
                        foreach($data as $row){
                            echo "<tr>
                            <td>".$row['dsdo']."</td>
                            <td>".$row['etiologi']."</td>
                            <td>".$row['masalah']."</td>
                            </tr>";
                        }
                    }
                    ?>

                    </tbody>
                    </table>



                <?php include "tab_navigasi.php"; ?>

</section>
</main>

                        


