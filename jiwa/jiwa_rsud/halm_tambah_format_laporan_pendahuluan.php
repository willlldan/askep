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

    <div class="pagetitle">
        <h1><strong>Asuhan Keperawatan Jiwa RSUD</strong></h1>
    </div><!-- End Page Title -->

    <?php include "tab.php"; ?>

    <section class="section dashboard">
    <div class="card">
        <div class="card-body mt-3">

        <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">
    
        <!-- Bagian Tanggal Pengkajian -->
                <div class="row mb-3">
                    <label for="tglpengkajian" class="col-sm-2 col-form-label"><strong>Tanggal Pengkajian</strong></label>
                    <div class="col-sm-10">
                        <input type="datetime-local" class="form-control" id="tglpengkajian" name="tglpengkajian" required>
                        <div class="invalid-feedback">
                            Harap isi Tanggal Pengkajian.
                        </div>
                    </div>
                </div>

                <!-- Bagian RS/Ruangan -->
                <div class="row mb-3">
                    <label for="rsruangan" class="col-sm-2 col-form-label"><strong>RS/Ruangan</strong></label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="rsruangan" required>
                        <div class="invalid-feedback">
                            Harap isi RS/Ruangan.
                        </div>
                    </div>
                </div>

                <h5 class="card-title"><strong>FORMAT LAPORAN PENDAHULUAN PRAKTIK KLINIK KEPERAWATAN JIWA</strong></h5>

                <div class="row mb-2">
                    <label class="col-sm-4 col-form-label text-primary">
                        <strong>A. Masalah Keperawatan Utama</strong>
                    </label>
                </div>

                 <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><strong>Masalah Keperawatan Utama</strong></label>
                    <div class="col-sm-10">
                        <textarea name="masalah_keperawatan_utama" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                    </div>
                </div>

                <div class="row mb-2">
                    <label class="col-sm-4 col-form-label text-primary">
                        <strong>B. Proses Terjadinya Masalah</strong>
                    </label>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><strong>1. Pengertian</strong></label>
                    <div class="col-sm-10">
                        <textarea name="pengertian" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><strong>2. Tanda dan Gejala</strong></label>
                    <div class="col-sm-10">
                        <textarea name="gejala_tanda" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><strong>3. Rentang Respons</strong></label>
                    <div class="col-sm-10">
                        <textarea name="rentang_respons" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><strong>4. Faktor Predisposisi</strong></label>
                    <div class="col-sm-10">
                        <textarea name="faktor_predisposisi" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><strong>5. Faktor Presipitasi</strong></label>
                    <div class="col-sm-10">
                        <textarea name="faktor_presipitasi" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><strong>6. Sumber Koping</strong></label>
                    <div class="col-sm-10">
                        <textarea name="sumber_koping" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><strong>7. Mekanisme Koping</strong></label>
                    <div class="col-sm-10">
                        <textarea name="mekanisme_koping" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><strong>8. Pohon Masalah</strong></label>
                    <div class="col-sm-10">
                        <textarea name="pohon_masalah" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><strong>9. Masalah Keperawatan yang Mungkin Muncul</strong></label>
                    <div class="col-sm-10">
                        <textarea name="masalah_keperawatan_muncul" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-4 col-form-label"><strong>10. Data yang perlu dikaji</strong></label>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><strong>Masalah Keperawatan</strong></label>
                    <div class="col-sm-10">
                        <textarea name="masalah_keperawatan" class="form-control" rows="3" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><strong>Subjektif :</strong></label>
                    <div class="col-sm-10">
                        <textarea name="subjektif" class="form-control" rows="3" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><strong>Objektif:</strong></label>
                    <div class="col-sm-10">
                        <textarea name="objektif" class="form-control" rows="3" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                    </div>
                </div>

                <style>
                    .table-pemeriksaan { table-layout: fixed; width:100% }
                    .table-pemeriksaan td, .table-pemeriksaan th { word-wrap: break-word; white-space: normal; vertical-align: top; }
                </style>

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">Masalah Keperawatan</th>
                            <th class="text-center">Data yang Perlu Dikaji</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if(!empty($data)){
                            foreach($data as $row){
                                echo "<tr>
                                    <td>".$row['masalah_keperawatan']."</td>
                                    <td>".$row['Data_yang_Perlu_Dikaji']."</td>
                                </tr>";
                            }
                        }
                        ?>
                    </tbody>
                </table>

                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><strong>11. Diagnosa Keperawatan yang Mungkin Muncul</strong></label>
                    <div class="col-sm-10">
                        <textarea name="diagnosa_muncul" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><strong>12. Rencana Tindakan Keperawatan</strong></label>
                    <div class="col-sm-10">
                        <textarea name="rencana_tindakan" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><strong>13. Daftar Pustaka</strong></label>
                    <div class="col-sm-10">
                        <textarea name="daftar_pustaka" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-12 justify-content-end d-flex">
                        <button type="submit" name="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>

                 <?php include "tab_navigasi.php"; ?>

            </form>
        </div>
    </div>
   
</section>
</main>