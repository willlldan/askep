<?php
if(isset($_POST['submit'])){

    $mahasiswa_id = 1;

    $diagnosa = $_POST['diagnosa'];
    $tgl_ditemukan = $_POST['tgl_ditemukan'];
    $tgl_teratasi = $_POST['tgl_teratasi'];

    $paraf = isset($_POST['paraf']) ? "✔" : NULL;

    $query = "INSERT INTO icu_diagnosa 
    (mahasiswa_id, diagnosa, tgl_ditemukan, tgl_teratasi, paraf)
    VALUES
    ('$mahasiswa_id', '$diagnosa', '$tgl_ditemukan', '$tgl_teratasi', '$paraf')";

    mysqli_query($conn, $query);

    echo "Diagnosa berhasil disimpan!";
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
              <h5 class="card-title mb-1"><strong>Diagnosa Keperawatan</strong></h5>

                <!-- General Form Elements -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

                <!-- Bagian Diagnosa -->

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Diagnosa</strong></label>

                        <div class="col-sm-9">
                           <textarea name="diagnosa" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" name="commentdiagnosa" id="commentdiagnosa" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div> 

                    <!-- Bagian Tanggal Ditemukan -->
                    <div class="row mb-3">
                        <label for="tgl_ditemukan" class="col-sm-2 col-form-label"><strong>Tanggal Ditemukan</strong></label>

                        <div class="col-sm-9">
                            <input type="datetime-local" class="form-control" id="tgl_ditemukan" name="tgl_ditemukan">
                             
                            <!-- comment -->
                            <textarea class="form-control mt-2" name="commenttgl_ditemukan" id="commenttgl_ditemukan" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
                         </div>
                    </div>

                    <!-- Bagian Tanggal Teratasi -->
                    <div class="row mb-3">
                        <label for="tgl_teratasi" class="col-sm-2 col-form-label"><strong>Tanggal Teratasi</strong></label>

                        <div class="col-sm-9">
                            <input type="datetime-local" class="form-control" id="tgl_teratasi" name="tgl_teratasi">
                            
                             <!-- comment -->
                            <textarea class="form-control mt-2" name="commenttgl_teratasi" id="commenttgl_teratasi" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                            oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled>
                            </div>
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

                    <!-- Bagian Button -->    
                    <div class="row mb-3">
                        <div class="col-sm-11 justify-content-end d-flex">
                            <button type="submit" name="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </div> 

                    <h5 class="card-title mt-2"><strong>Diagnosa Keperawatan</strong></h5>

                    <style>
                    .table-diagnosa {
                        table-layout: fixed;
                        width:100%
                    }

                    .table-diagnosa td,
                    .table-diagnosa th {
                        word-wrap: break-word;
                        white-space: normal;
                        vertical-align: top;
                    }
                    </style>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Diagnosa</th>
                                <th class="text-center">Tanggal Ditemukan</th>
                                <th class="text-center">Tanggal Teratasi</th>
                                <th class="text-center">Paraf</th>
                        </tr>
                        </thead>

                    <tbody>

                    <?php
                    $no = 1;
                    if(!empty($data)){
                        foreach($data as $row){
                            echo "<tr>
                            <td>".$no++."</td>
                            <td>".$row['diagnosa']."</td>
                            <td>".$row['tgl_ditemukan']."</td>
                            <td>".$row['tgl_teratasi']."</td>
                            <td>".$row['paraf']."</td>
                            </tr>";
                        }
                    }
                    ?>

                    

                    </tbody>
                    </table>

                    <?php include "tab_navigasi.php"; ?>

</section>              
</main>
                
                 