<?php
require_once "koneksi.php";
require_once "utils.php";

if (isset($_POST['submit'])) {
    $no_dokumen = $_POST['no_dokumen'];
    $status_dokumen = $_POST['status_dokumen'];
    $tgl_keluar_dok = $_POST['tgl_keluar_dok'];
    $perihal = $_POST['perihal'];
    $tujuan = $_POST['tujuan'];
    $label_arsip = $_POST['label_arsip'];
    $rak_arsip = $_POST['rak_arsip'];
    $tgl_pinjam = $_POST['tgl_pinjam'];
    $peminjaman = $_POST['peminjaman'];
    $tgl_kembali = $_POST['tgl_kembali'];
    $keterangan = $_POST['keterangan'];
    $file_name = "";

    if (isset($_FILES['file']['name']) && !empty($_FILES['file']['name'])) {
        $target_dir = "maternitas/uploads/";
        $file_name = date("YmdHis_") . basename($_FILES["file"]["name"]);
        $target_file = $target_dir . $file_name;
        $uploadOk = 1;
        $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Lakukan validasi ukuran dan tipe file jika perlu
        // ...

        if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
            echo "Data maternitas berhasil ditambah.";
        } else {
            echo "Terjadi kesalahan saat melakukan tambah data maternitas.";
        }
    }

    $sql = "INSERT INTO tbl_dok_keluar (
            no_dokumen,                        
            status_dokumen,       
            tgl_keluar_dok,             
            perihal,
            tujuan,
            label_arsip,      
            rak_arsip,          
            tgl_pinjam,
            peminjaman,
            tgl_kembali,
            keterangan,
            file 
                    
            ) VALUES (
            '$no_dokumen',             
            '$status_dokumen',   
            '$tgl_keluar_dok',           
            '$perihal',
            '$tujuan',
            '$label_arsip',
            '$rak_arsip',            
            '$tgl_pinjam',
            '$peminjaman',
            '$tgl_kembali',
            '$keterangan',
            '$file_name'
            )";

    if ($mysqli->query($sql) === TRUE) {
        echo "<script>alert('Dokumen Keluar berhasil ditambah.')</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $mysqli->error;
    }
}

?>

<main id="main" class="main">
    <?php include "navbar_gerontik.php"; ?>
    </style>

    <section class="section dashboard">
        <div class="card">
            <div class="card-body">

                <!-- General Form Elements -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

                    <h5 class="card-title"><strong>C. RENCANAN KEPERAWATAN</strong></h5>



                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Nama Lansia</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="nama">

                            <!-- comment -->
                            <textarea class="form-control mt-2" id="commentinisialpasien" rows="2" placeholder="Jika ada revisi atau saran dari Ibu/Bapak Dosen, silakan diketik di sini. Terima kasih." style="display:block; overflow:hidden; resize: none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" onchange="document.getElementById('commentinisialpasien'). style.display= this.checked ? 'none' : 'block'">
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Umur</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="nama">

                            <!-- comment -->
                            <textarea class="form-control mt-2" id="commentinisialpasien" rows="2" placeholder="Jika ada revisi atau saran dari Ibu/Bapak Dosen, silakan diketik di sini. Terima kasih." style="display:block; overflow:hidden; resize: none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" onchange="document.getElementById('commentinisialpasien'). style.display= this.checked ? 'none' : 'block'">
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Alamat</strong></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="nama">

                            <!-- comment -->
                            <textarea class="form-control mt-2" id="commentinisialpasien" rows="2" placeholder="Jika ada revisi atau saran dari Ibu/Bapak Dosen, silakan diketik di sini. Terima kasih." style="display:block; overflow:hidden; resize: none;"
                                oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        </div>

                        <div class="col-sm-1 d-flex align-items-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" onchange="document.getElementById('commentinisialpasien'). style.display= this.checked ? 'none' : 'block'">
                            </div>
                        </div>
                    </div>



                    <!-- General Form Elements -->
                    <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

                        <!-- Bagian No. DX -->

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>No</strong></label>

                            <div class="col-sm-9">
                                <textarea name="no" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                                <!-- comment -->
                                <textarea class="form-control mt-2" id="commentnodx" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
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
                            <label for="Diagnosa_Keperawatan" class="col-sm-2 col-form-label"><strong>Diagnosa Keperawatan</strong></label>

                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="hari_tgl" name="hari_tgl">

                                <!-- comment -->
                                <textarea class="form-control mt-2" id="commenthari_tgl" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
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
                            <label for="jam" class="col-sm-2 col-form-label"><strong>Tujuan & Kriteria Hasil</strong></label>

                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="Tujuan_Kriteria Hasil" name="Tujuan_Kriteria Hasil">

                                <!-- comment -->
                                <textarea class="form-control mt-2" id="commentjam" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
                                    oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                            </div>

                            <div class="col-sm-1 d-flex align-items-start">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" disabled>
                                </div>
                            </div>
                        </div>

                        <!-- Bagian Implementasi -->

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Intervensi</strong></label>

                            <div class="col-sm-9">
                                <textarea name="Intervensi" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                                <!-- comment -->
                                <textarea class="form-control mt-2" id="commentimplementasi" rows="2" placeholder="Kolom ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakannya!" style="display:block; overflow:hidden; resize: none;"
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

                        <h5 class="card-title mt-2"><strong>Implementasi Keperawatan</strong></h5>

                        <style>
                            .table-pemeriksaan {
                                table-layout: fixed;
                                width: 100%
                            }

                            .table-pemeriksaan td,
                            .table-pemeriksaan th {
                                word-wrap: break-word;
                                white-space: normal;
                                vertical-align: top;
                            }
                        </style>

                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center">No.</th>
                                    <th class="text-center">Diagnosa Keperawatan</th>
                                    <th class="text-center">Tujuan & Kriteria Hasil</th>
                                    <th class="text-center">Intervensi</th>
                                </tr>
                            </thead>

                            <tbody>

                                <?php
                                if (!empty($data)) {
                                    foreach ($data as $row) {
                                        echo "<tr>
                            <td>" . $row['no'] . "</td>
                            <td>" . $row['Diagnosa_Keperawatan'] . "</td>
                            <td>" . $row['Tujuan_Kriteria Hasil'] . "</td>
                            <td>" . $row['Intervensi'] . "</td>
                            </tr>";
                                    }
                                }
                                ?>

                            </tbody>
                        </table>

                        <?php include "tab_navigasi.php"; ?>
            </div>
        </div>



    </section>
</main>