<?php
if(isset($_POST['submit'])){

    $mahasiswa_id = 1;

    $no_dx = $_POST['nodx'];
    $hari_tgl = $_POST['hari_tgl'];
    $jam = $_POST['jam'];

    $evaluasi_s = $_POST['evaluasi_s'];
    $evaluasi_o = $_POST['evaluasi_o'];
    $evaluasi_a = $_POST['evaluasi_a'];
    $evaluasi_p = $_POST['evaluasi_p'];

    $paraf = isset($_POST['paraf']) ? "✔" : NULL;

    $query = "INSERT INTO icu_evaluasi 
    (mahasiswa_id, no_dx, hari_tgl, jam, evaluasi_s, evaluasi_o, evaluasi_a, evaluasi_p, paraf)
    VALUES
    ('$mahasiswa_id', '$no_dx', '$hari_tgl', '$jam', '$evaluasi_s', '$evaluasi_o', '$evaluasi_a', '$evaluasi_p', '$paraf')";

    mysqli_query($conn, $query);

    echo "Evaluasi berhasil disimpan!";
}
?>

<main id="main" class="main">

    <div class="pagetitle">
        <h1><strong>Askep Keperawatan Ruang ICU</strong></h1>
        <!-- <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Home</a></li>
          <li class="breadcrumb-item active">Dashboard</li>
        </ol>
        </nav> -->
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
              <h5 class="card-title mb-1"><strong>Evaluasi Keperawatan</strong></h5>

                <!-- General Form Elements -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

                <!-- Bagian No. DX -->

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>No. DX</strong></label>

                        <div class="col-sm-9">
                             <input type="text" class="form-control" name="nodx">

                     <!-- comment -->
                            <textarea class="form-control mt-2" name="commentnodx" id="commentnodx" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 

                <!-- Bagian Hari/Tanggal -->

                    <div class="row mb-3">
                        <label for="hari_tgl" class="col-sm-2 col-form-label"><strong>Hari/Tanggal</strong></label>

                        <div class="col-sm-9">
                            <input type="datetime-local" class="form-control" id="hari_tgl" name="hari_tgl">
                             
                            <!-- comment -->
                            <textarea class="form-control mt-2" name="commenthari_tgl" id="commenthari_tgl" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>

                <!-- Bagian Jam -->

                    <div class="row mb-3">
                        <label for="jam" class="col-sm-2 col-form-label"><strong>Jam</strong></label>

                        <div class="col-sm-9">
                            <input type="time" class="form-control" id="jam" name="jam">
                            
                        <!-- comment -->
                            <textarea class="form-control mt-2" name="commentjam" id="commentjam" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>  

                <!-- Bagian Evaluasi -->

                    <div class="row mb-2">
                        <label class="col-sm-2 col-form-label">
                            <strong>Evaluasi</strong>
                        </label>
                    </div>
                    
                    <!-- S -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>S (Subjective)</strong></label>

                        <div class="col-sm-9">
                            <textarea name="evaluasi_s" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        </div> 

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>

                    </div>
                    
                    <!-- O -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>O (Objective)</strong></label>

                        <div class="col-sm-9">
                            <textarea name="evaluasi_o" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        </div>
                    </div>
                    
                    <!-- A -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>A (Assessment)</strong></label>

                        <div class="col-sm-9">
                            <textarea name="evaluasi_a" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        </div>
                    </div>

                    <!-- P -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>P (Plan)</strong></label>

                        <div class="col-sm-9">
                            <textarea name="evaluasi_p" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        </div>
                    </div>

                     <!-- comment -->
                      <div class="row mb-3">
                        <div class="offset-sm-2 col-sm-9">
                            <textarea class="form-control mt-2" name="commentevaluasi" id="commentevaluasi" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
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
                        <div class="col-sm-11 d-flex justify-content-end gap-2">
                            <button type="submit" name="submit" class="btn btn-primary">Simpan</button>
                            <button type="submit" name="cetak" class="btn btn-success">Cetak</button>
                        </div>
                    </div>

                    <!-- Bagian Paraf -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Paraf</strong></label>
                        
                        <div class="col-sm-9">
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="checkbox" name="paraf" value="✔">
                                <label class="form-check-label">Paraf jika sudah selesai</label>
                            </div>
                        </div>
                    </div>
                   
                    <h5 class="card-title mt-2"><strong>Evaluasi Keperawatan</strong></h5>

                    <style>
                    .table-evaluasi {
                        table-layout: fixed;
                        width:100%
                    }

                    .table-evaluasi td,
                    .table-evaluasi th {
                        word-wrap: break-word;
                        white-space: normal;
                        vertical-align: top;
                    }
                    </style>

                    <table class="table table-bordered table-evaluasi">
                        <thead>
                            <tr>
                                <th class="text-center">No. Dx </th>
                                <th class="text-center">Hari/Tanggal</th>
                                <th class="text-center">Jam</th>
                                <th class="text-center">Evaluasi</th>
                                <th class="text-center">Paraf</th>
                        </tr>
                        </thead>

                    <tbody>

                    <?php
                    if(!empty($data)){
                        echo "<tr>
                            <td>".$row['no_dx']."</td>
                            <td>".$row['hari_tgl']."</td>
                            <td>".$row['jam']."</td>
                            <td>
                                S: ".$row['evaluasi_s']."<br>
                                O: ".$row['evaluasi_o']."<br>
                                A: ".$row['evaluasi_a']."<br>
                                P: ".$row['evaluasi_p']."
                            </td>
                            <td>".$row['paraf']."</td>
                        </tr>";
                    }
                    ?>

                    </tbody>
                    </table>  
                    
                    <?php include "tab_navigasi.php"; ?>

                    
</section>
</main>