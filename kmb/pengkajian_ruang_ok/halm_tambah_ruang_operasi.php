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
             <?php include "kmb/pengkajian_ruang_ok/tab.php"; ?>


    <section class="section dashboard">
        <div class="card">
            <div class="card-body">
              <h5 class="card-title mb-1"><strong>LAPORAN RUANG OPERASI</strong></h5>

           <div class="row mb-3">
                <label class="col-sm-2 col-form-label"><strong>Nama Mahasiswa </strong></label>

                <div class="col-sm-9">
                    <input type="text" class="form-control" name="nama_mahasiswa">

                    
                </div>
            </div>
               <div class="row mb-3">
                <label class="col-sm-2 col-form-label"><strong>NIM</strong></label>

                <div class="col-sm-9">
                    <input type="text" class="form-control" name="nim">

                    
                </div>
            </div>
               <div class="row mb-3">
                <label class="col-sm-2 col-form-label"><strong>Kelompok 	</strong></label>

                <div class="col-sm-9">
                    <input type="text" class="form-control" name="kelompok">

                    
                </div>
            </div>
               <div class="row mb-3">
                <label class="col-sm-2 col-form-label"><strong>Tempat dinas </strong></label>

                <div class="col-sm-9">
                    <input type="text" class="form-control" name="tempat_dinas">

                   
                </div>
            </div>

            <!-- A IDENTITAS KLIEN -->
            <div class="row mb-2">
                <label class="col-sm-12 text-primary">
                    <strong>A. IDENTITAS KLIEN</strong>
                </label>
            </div>

            <!-- NAMA -->
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label"><strong>Nama Klien</strong></label>

                <div class="col-sm-9">
                    <input type="text" class="form-control" name="nama_klien">

                   
                </div>
            </div>

            <!-- UMUR -->
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label"><strong>Umur</strong></label>

                <div class="col-sm-9">
                    <input type="number" class="form-control" name="umur">

                   
                </div>
            </div>

            <!-- PEKERJAAN -->
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label"><strong>Pekerjaan</strong></label>

                <div class="col-sm-9">
                    <input type="text" class="form-control" name="pekerjaan">

                    
                </div>
            </div>

            <!-- AGAMA -->
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label"><strong>Agama</strong></label>

                <div class="col-sm-9">
                    <input type="text" class="form-control" name="agama">

                    
                </div>
            </div>

            <!-- TGL MASUK RS -->
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label"><strong>Tgl Masuk RS</strong></label>

                <div class="col-sm-9">
                    <input type="date" class="form-control" name="tgl_masuk_rs">

                    
                         </div>
                    </div>

            <!-- DIAGNOSA MEDIS -->
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label"><strong>Diagnosa Medis</strong></label>

                <div class="col-sm-9">
                    <input type="text" class="form-control" name="diagnosa_medis">

                    
                         </div>
                    </div>

            <!-- JENIS OPERASI -->
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label"><strong>Jenis Operasi</strong></label>

                <div class="col-sm-9">
                    <input type="text" class="form-control" name="jenis_operasi">

                    
                         </div>
                    </div>
            <!-- WAKTU OPERASI -->
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label"><strong>Waktu Operasi</strong></label>

                <div class="col-sm-9">
                    <div class="row">
                        <div class="col-md-4">
                            <input type="date" class="form-control" name="tgl_operasi">
                        </div>
                        <div class="col-md-4">
                            <input type="time" class="form-control" name="pukul_mulai">
                        </div>
                        <div class="col-md-4">
                            <input type="time" class="form-control" name="pukul_selesai">
                        </div>
                    </div>

                    
                         </div>
                    </div>

            <!-- B PERSIAPAN -->
            <div class="row mb-2">
                <label class="col-sm-12 text-primary">
                    <strong>B. PERSIAPAN</strong>
                </label>
            </div>

            <!-- ALAT -->
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label"><strong>1. Alat</strong></label>
            </div>

            <!-- STERIL -->
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label"><strong>Steril</strong></label>

                <div class="col-sm-9">
                    <textarea class="form-control"
                        rows="3"
                        name="persiapan_klien"
                        style="display:block; overflow:hidden; resize: none;"
                        oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                  
                         </div>
                    </div> 
            <!-- NON STERIL -->
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label"><strong>Non Steril</strong></label>

                <div class="col-sm-9">
                    <textarea class="form-control"
                        rows="3"
                        name="persiapan_klien"
                        style="display:block; overflow:hidden; resize: none;"
                        oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                  
                         </div>
                    </div> 
               
            <!-- ANESTESI -->
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label"><strong>Anestesi</strong></label>

                <div class="col-sm-9">
                    <textarea class="form-control"
                        rows="3"
                        name="persiapan_klien"
                        style="display:block; overflow:hidden; resize: none;"
                        oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        </div>

                </div>

            <!-- JENIS ANESTESI -->
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label"><strong>2. Jenis Anestesi</strong></label>

                <div class="col-sm-9">
                    <input type="text"
                        class="form-control"
                        name="jenis_anestesi"
                        style="display:block; overflow:hidden; resize: none;"
                        oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';">


                
                         </div>
                    </div> 

            <!-- LINGKUNGAN -->
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label"><strong>3. Lingkungan</strong></label>

                <div class="col-sm-9">
                    <textarea class="form-control"
                        rows="3"
                        name="persiapan_lingkungan"
                        style="display:block; overflow:hidden; resize: none;"
                        oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                  
                         </div>
            </div>

            <!-- HASIL LAB -->
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">
                    <strong>4. Hasil pemeriksaan Laboratorium</strong>
                </label>

                <div class="col-sm-9">
                    <textarea class="form-control"
                        rows="3"
                        name="hasil_lab"
                        style="display:block; overflow:hidden; resize: none;"
                        oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                   
                         </div>
            </div>

            <!-- C TINDAKAN -->
            <div class="row mb-2">
                <label class="col-sm-12 text-primary">
                    <strong>C. TINDAKAN</strong>
                </label>
            </div>

            <div class="row mb-3">
                <label class="col-sm-2 col-form-label"><strong>Tindakan</strong></label>

                <div class="col-sm-9">
                    <textarea class="form-control"
                        rows="5"
                        name="tindakan_operasi"
                        style="display:block; overflow:hidden; resize: none;"
                        oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                   
                         </div>
                    </div> 

            <!-- D KESIMPULAN -->
            <div class="row mb-2">
                <label class="col-sm-12 text-primary">
                    <strong>D. KESIMPULAN</strong>
                </label>
            </div>

            <div class="row mb-3">
                <label class="col-sm-2 col-form-label"><strong>Kesimpulan</strong></label>

                <div class="col-sm-9">
                    <textarea class="form-control"
                        rows="5"
                        name="kesimpulan"
                        style="display:block; overflow:hidden; resize: none;"
                        oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

                 
                         </div>
                    </div> 
            </div>

            <?php include "tab_navigasi.php"; ?>

        </div>
    </div>
</section>
</main>
                
                 

