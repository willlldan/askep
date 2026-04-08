<?php
include "koneksi.php";

if(isset($_POST['submit'])){

    $mahasiswa_id = 1;

    $pengertian = $_POST['pengertian'];
    $klasifikasi = $_POST['klasifikasi'];
    $etiologi = $_POST['etiologi'];
    $patofisiologi = $_POST['patofisiologi'];
    $manifestasiklinik = $_POST['manifestasiklinik'];
    $pemeriksaandiagnostik = $_POST['pemeriksaandiagnostik'];
    $penatalaksanaan = $_POST['penatalaksanaan'];
    $komplikasi = $_POST['komplikasi'];
    $link_penyimpangan = $_POST['link_penyimpangan'];

    $query = "INSERT INTO icu_laporan_pendahuluan 
    (mahasiswa_id, pengertian, klasifikasi, etiologi, patofisiologi, manifestasiklinik, pemeriksaandiagnostik, penatalaksanaan, komplikasi, link_penyimpangan)
    VALUES
    ('$mahasiswa_id', '$pengertian', '$klasifikasi', '$etiologi', '$patofisiologi', '$manifestasiklinik', '$pemeriksaandiagnostik', '$penatalaksanaan', '$komplikasi', '$link_penyimpangan')";

    mysqli_query($conn, $query);
}

    echo "Laporan Pendahuluan berhasil disimpan!";

?>

<main id="main" class="main">

   <!-- Card Identitas -->

     <div class="card">
            <div class="card-body">
    <h5 class="card-title"><strong>DATA MAHASISWA</strong></h5>

                <!-- General Form Elements -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">
                
                <!-- Bagian Nama Mahasiswa -->
                <div class="row mb-3">
                    <label for="namamahasiswa" class="col-sm-2 col-form-label"><strong>Nama Mahasiswa</strong></label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="namamahasiswa" required>
                        <div class="invalid-feedback">
                            Harap isi Nama Mahasiswa.
                        </div>
                    </div>
                </div>

                <!-- Bagian NIM -->
                <div class="row mb-3">
                    <label for="npm" class="col-sm-2 col-form-label"><strong>NIM</strong></label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="nim" required>
                        <div class="invalid-feedback">
                            Harap isi NIM.
                        </div>
                    </div>
                </div>

                <!-- Bagian Kelompok -->
                <div class="row mb-3">
                    <label for="npm" class="col-sm-2 col-form-label"><strong>Kelompok</strong></label>
                    <div class="col-sm-9">
                        <textarea name="kelompok" class="form-control" rows="2" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>    
                        <div class="invalid-feedback">
                            Harap isi Kelompok.
                        </div>
                    </div>
                </div>

                <!-- Bagian Tempat Dinas -->
                <div class="row mb-3">
                    <label for="tempatdinas" class="col-sm-2 col-form-label"><strong>Tempat Dinas</strong></label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="tempatdinas" required>
                        <div class="invalid-feedback">
                            Harap isi Tempat Dinas.
                        </div>
                    </div>
                </div>

                <!-- Jenis Gadar -->

                <?php
                    $jenismaternitas = $_GET['jenisgadar'] ?? 'icu';
                   
                ?>

                <div class="row mb-3">
                    <label for="jenisgadar" class="col-sm-2 col-form-label"><strong>Gawat Darurat</strong></label>
                        <div class="col-sm-9">

                                <select class="form-select" name="jenisgadar"
                        onchange="window.location=this.value" required>

                        <option value="">Pilih</option>

                        <option value="index.php?page=gadar/icu&tab=umum&jenisgadar=icu"
                        <?= $jenismaternitas == 'icu' ? 'selected' : '' ?>>
                        Askep Keperawatan Ruang ICU
                        </option>

                        <option value="index.php?page=gadar/igd&jenisgadar=igd"
                        <?= $jenismaternitas == 'igd' ? 'selected' : '' ?>>
                        Pengkajian Keperawatan Ruang IGD
                        </option>
        
                        </select>
                        <div class="invalid-feedback">
                            Harap isi Jenis Gawat Darurat.
                        </div>
                    </div>
                </div>

             </div>
    </div>

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

                <h5 class="card-title"><strong>A. KONSEP DASAR MEDIS</strong></h5>

                <!-- General Form Elements -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">
                
                    <!-- Bagian Pengertian -->

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Pengertian</strong></label>

                        <div class="col-sm-9">
                            <textarea name="pengertian" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                            <!-- comment -->
                            <textarea class="form-control mt-2" name="commentpengertian" id="commentpengertian" rows="2" placeholder="Jika ada revisi atau saran dari Ibu/Bapak Dosen, silakan diketik di sini. Terima kasih." style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" onchange="document.getElementById('commentpengertian'). style.display= this.checked ? 'none' : 'block'">
                            </div>
                         </div>
                    </div> 

                <!-- Bagian Klasifikasi -->
                <div class="row mb-3">
                    <label for="klasifikasi" class="col-sm-2 col-form-label"><strong>Klasifikasi</strong></label>
                    <div class="col-sm-9">
                        <textarea name="klasifikasi" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentklasifikasi" id="commentklasifikasi" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
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
                            <textarea class="form-control mt-2" name="commentetiologi" id="commentketiologi" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>
               
                <!-- Bagian Patofisiologi -->
                <div class="row mb-3">
                    <label for="patofisiologi" class="col-sm-2 col-form-label"><strong>Patofisiologi</strong></label>
                    <div class="col-sm-9">
                        <textarea name="patofisiologi" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentpatofisiologi" id="commentpatofisiologi" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 
                    
                <!-- Bagian Manifestasi Klinik -->
                <div class="row mb-3">
                    <label for="manifestasiklinik" class="col-sm-2 col-form-label"><strong>Manifestasi Klinik</strong></label>
                    <div class="col-sm-9">
                        <textarea name="manifestasiklinik" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentmanifestasiklinik" id="commentmanifestasiklinik" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 
                    
                <!-- Bagian Pemeriksaan Diagnostik -->
                <div class="row mb-3">
                    <label for="pemeriksaandiagnostik" class="col-sm-2 col-form-label"><strong>Pemeriksaan Diagnostik</strong></label>
                    <div class="col-sm-9">
                        <textarea name="pemeriksaandiagnostik" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentpemeriksaandiagnostik" id="commentpemeriksaandiagnostik" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>
                    
                <!-- Bagian Penatalaksanaan -->
                <div class="row mb-3">
                    <label for="penatalaksanaan" class="col-sm-2 col-form-label"><strong>Penatalaksanaan</strong></label>
                    <div class="col-sm-9">
                        <textarea name="penatalaksanaan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentpenatalaksanaan" id="commentpenatalaksanaan" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>  
                    
                <!-- Bagian Komplikasi -->
                <div class="row mb-3">
                    <label for="komplikasi" class="col-sm-2 col-form-label"><strong>Komplikasi</strong></label>
                    <div class="col-sm-9">
                        <textarea name="komplikasi" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentkomplikasi" id="commentkomplikasi" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>
            </div>
        </div> 

        <div class="card">
            <div class="card-body">
            
                <!-- General Form Elements -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

                <h5 class="card-title"><strong>B. KONSEP DASAR KEPERAWATAN</strong></h5>

                <!-- General Form Elements -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">
                
                    <!-- Bagian Pengkajian Keperawatan -->

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Pengkajian Keperawatan</strong></label>

                        <div class="col-sm-9">
                            <textarea name="pengkajiankeperawatan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                            <!-- comment -->
                            <textarea class="form-control mt-2" name="commentpengkajiankeperawatan" id="commentpengkajiankeperawatan" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>

                <!-- Bagian Penyimpangan KDM -->
                <div class="row mb-3">
                    <label for="penyimpangankdm" class="col-sm-2 col-form-label"><strong>Penyimpangan KDM</strong></label>
                    <div class="col-sm-9">
                        

                        <!-- Link Google Drive -->
                         <div class="d-flex align-items-center gap-2">
    <input type="text" name="link_penyimpangan" class="form-control" placeholder="Tempel link file Google Drive di sini">

    <a href="https://drive.google.com/drive/folders/ID_FOLDER_KAMU" 
       target="_blank" 
       class="btn btn-primary">
       Upload ke Drive
    </a>
</div>

                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentpenyimpangankdm" id="commentpenyimpangankdm" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>

                <!-- Bagian Diagnosa Keperawatan -->
                <div class="row mb-3">
                    <label for="diagnosakeperawatan" class="col-sm-2 col-form-label"><strong>Diagnosa Keperawatan</strong></label>
                    <div class="col-sm-9">
                        <textarea name="diagnosakeperawatan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentdiagnosakeperawatan" id="commentdiagnosakeperawatan" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>

                <!-- Bagian Perencanaan -->    

                <div class="row mb-2">
                    <label class="col-sm-6 col-form-label">
                        <strong>Perencanaan:</strong>
                </div>

                <!-- Bagian Diagnosa Keperawatan -->
                <div class="row mb-3">
                    <label for="diagnosakeperawatan" class="col-sm-2 col-form-label"><strong>Diagnosa Keperawatan</strong></label>
                    <div class="col-sm-9">
                        <textarea name="diagnosakeperawatan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" name="commentdiagnosakeperawatan" id="commentdiagnosakeperawatan" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 

                <!-- Bagian Tujuan dan Kriteria Hasil -->
                <div class="row mb-3">
                    <label for="tujuandankriteriahasil" class="col-sm-2 col-form-label"><strong>Tujuan dan Kriteria Hasil</strong></label>
                    <div class="col-sm-9">
                        <textarea name="tujuandankriteriahasil" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" name="commenttujuandankriteriahasil" id="commenttujuandankriteriahasil" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 
                    
                <!-- Bagian Intervensi -->
                <div class="row mb-3">
                    <label for="intervensi" class="col-sm-2 col-form-label"><strong>Intervensi</strong></label>
                    <div class="col-sm-9">
                        <textarea name="intervensi" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" name="commentintervensi" id="commentintervensi" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
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
                
                <h5 class="card-title"><strong>Perencanaan:</strong></h5>
                
                <style>
                    .table-perencanaan {
                        table-layout: fixed;
                        width:100%
                    }

                    .table-perencanaan td,
                    .table-perencanaan th {
                        word-wrap: break-word;
                        white-space: normal;
                        vertical-align: top;
                    }
                    </style>

                    <table class="table table-bordered table-perencanaan">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Diagnosa Keperawatan</th>
                                <th class="text-center">Tujuan dan Kriteria Hasil</th>
                                <th class="text-center">Intervensi</th>
                        </tr>
                        </thead>

                    <tbody>

                    <?php
                    if(!empty($data)){
                        $no = 1;
                        foreach($data as $row){
                            echo "<tr>
                            <td class='text-center'>".$no++."</td>
                            <td>".$row['no']."</td>
                            <td>".$row['diagnosakeperawatan']."</td>
                            <td>".$row['tujuandankriteriahasil']."</td>
                            <td>".$row['intervensi']."</td>
                            </tr>";
                        }
                    }
                    ?>

                    </tbody>
                    </table>
        </div>
    </div>

        <div class="card">
            <div class="card-body">
            
                <!-- General Form Elements -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

                <h5 class="card-title"><strong>C. DAFTAR PUSTAKA</strong></h5>

                <!-- General Form Elements -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">
                
                    <!-- Bagian Daftar Pustaka -->

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Daftar Pustaka</strong></label>

                        <div class="col-sm-9">
                            <textarea name="daftarpustaka" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" name="commentdaftarpustaka" id="commentdaftarpustaka" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>
                </div> 
            </div>

                
                <?php include "tab_navigasi.php"; ?>
</form>
</section>
</main>

                        


