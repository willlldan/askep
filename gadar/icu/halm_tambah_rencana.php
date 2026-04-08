<?php
if(isset($_POST['submit'])){

    $mahasiswa_id = 1;
    $diagnosa_id = $_POST['diagnosa_id'];

    $tujuan = $_POST['tujuan'];
    $kriteria_hasil = $_POST['kriteria_hasil'];
    $rencana_tindakan = $_POST['rencana_tindakan'];

    $query = "INSERT INTO icu_intervensi
    (mahasiswa_id, diagnosa_id, tujuan, kriteria_hasil, rencana_tindakan)
    VALUES
    ('$mahasiswa_id', '$diagnosa_id', '$tujuan', '$kriteria_hasil', '$rencana_tindakan')";

    mysqli_query($conn, $query);

    echo "Intervensi berhasil disimpan!";
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
              <h5 class="card-title mb-1"><strong>Rencana Keperawatan</strong></h5>

                <!-- General Form Elements -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

                <!-- Bagian Diagnosa -->

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Diagnosa Keperawatan</strong></label>

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
                        <label class="col-sm-2 col-form-label"><strong>Tujuan dan Kriteria Hasil</strong></label>

                        <div class="col-sm-9">
                            <textarea name="tujuandankriteria" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                     <!-- comment -->
                            <textarea class="form-control mt-2" name="commenttujuandankriteria" id="commenttujuandankriteria" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
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
                        <label class="col-sm-2 col-form-label"><strong>Intervensi</strong></label>

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

                    <h5 class="card-title mt-2"><strong>Rencana Keperawatan</strong></h5>

                    <style>
                    .table-rencana {
                        table-layout: fixed;
                        width:100%
                    }

                    .table-rencana td,
                    .table-rencana th {
                        word-wrap: break-word;
                        white-space: normal;
                        vertical-align: top;
                    }
                    </style>

                   <table class="table table-bordered">
<thead>
<tr>
    <th>No</th>
    <th>Diagnosa</th>
    <th>Tujuan</th>
    <th>Kriteria Hasil</th>
    <th>Rencana Tindakan</th>
</tr>
</thead>

<tbody>

<?php
$data = mysqli_query($conn, "
SELECT i.*, d.diagnosa 
FROM icu_intervensi i
JOIN icu_diagnosa d ON i.diagnosa_id = d.id
");

$no = 1;
while($row = mysqli_fetch_assoc($data)){
    echo "<tr>
        <td>".$no++."</td>
        <td>".$row['diagnosa']."</td>
        <td>".$row['tujuan']."</td>
        <td>".$row['kriteria_hasil']."</td>
        <td>".$row['rencana_tindakan']."</td>
    </tr>";
}
?>

</tbody>
</table>

                    <?php include "tab_navigasi.php"; ?>

</section>
</main>
