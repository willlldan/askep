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
<?php include "kmb/format_hd_kmb/tab.php"; ?>
    

    <section class="section dashboard">
        <div class="card">
            <div class="card-body">
              <h5 class="card-title mb-1"><strong>Format Hermodalisa (HD)</strong></h5>

<!-- NAMA MAHASISWA -->
 <div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>Nama Mahasiswa</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="nama_mahasiswa">
     
        </div>
        </div>

<div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>NIM</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="nim">
    
        </div>
        </div>


<div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>Kelompok</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="kelompok">
     
        </div>
        </div>


<div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>Tempat Dinas</strong>
    </div>
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
 <div class="row mb-3">
                <label class="col-sm-2 col-form-label"><strong>Nama (inisial)</strong></label>

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

     

            <!-- DIAGNOSA MEDIS -->
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label"><strong>Diagnosa Medis</strong></label>

                <div class="col-sm-9">
                    <input type="text" class="form-control" name="diagnosa_medis">

                   
                         </div>
                    </div>



       <!-- TGL MASUK RS -->
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label"><strong>Tanggal Pertama HD</strong></label>

                <div class="col-sm-9">
                    <input type="date" class="form-control" name="tgl_pertama_hd">

              
                         </div>
                    </div>

<div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>HD ke berapa</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="hd">

        </div>
        </div>

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
<!-- C TINDAKAN -->
<div class="row mb-2">
<label class="col-sm-12 text-primary"><strong>B.Status Emosional Klien dan Keluarga</strong></label>
</div>
<div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>Status Emosional Klien dan Keluarga</strong>
    </div>
    <div class="col-sm-9">
                               <textarea name="status_emosional" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

        </div>
        </div>

<!-- C TINDAKAN -->
<div class="row mb-2">
<label class="col-sm-12 text-primary"><strong>C.Riwayat komplikasi HD Sebelumnya (Narasikan komplikasi yang di alami pasien pada HD sebelumnya)</strong></label>
</div>
<div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>C.Riwayat komplikasi HD Sebelumnya (Narasikan komplikasi yang di alami pasien pada HD sebelumnya)</strong>
    </div>
    <div class="col-sm-9">
                               <textarea name="riwayat_komplikasi" class="form-control" rows="8" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

        </div>
        </div>


<div class="row mb-3">
    <label class="col-sm-4 col-form-label text-primary"><strong>D.	Nilai Laboratorium Terakhir </strong></label>
    <div class="col-sm-9">       
</div>
<div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>Tanggal pemeriksaan</strong>
    </div>
    <div class="col-sm-9">
        <input type="date" class="form-control" name="tgl_pemeriksaan">
   
        </div>
        </div>
     
                    <div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>Nama Pemeriksaan</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="nama_pemeriksaan">
        
        </div>
        </div>

                    <div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>Hasil</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="hasil">
   
        </div>
        </div>
  
                    <div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>Satuan</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="satuan">
       
        </div>
        </div> 
                    <div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>Nilai Rujukan</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="nilai_rujukan">
     
        </div>
        </div>
                  

                <!-- Bagian Button -->    
                <div class="row mb-3">
                    <div class="col-sm-11 justify-content-end d-flex">
                        <button type="submit" name="submit" class="btn btn-primary">Simpan</button>
                    </div>
                    </div>
                    </div>
                
                <h5 class="card-title"><strong>D.	Nilai Laboratorium Terakhir</strong></h5>
                
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
                                <th class="text-center">Nama Pemeriksaan</th>
                                <th class="text-center">Hasil</th>
                                <th class="text-center">Satuan</th>
                                <th class="text-center">Nilai Rujukan</th>
                        </tr>
                        </thead>
                        </table>
                        </form>

<div class="row mb-2">
<label class="col-sm-12 text-primary"><strong>E.	Persiapan </strong></label>
</div>
<div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>1. Lingkungan</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="lingkungan">
      
        </div>
        </div>

<div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>2. Mesin HD</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="mesin_hd">
        
        </div>
        </div>

<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>3. Klien  </strong></label>
</div>

<div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>a. Pengukuran Berat Badan</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="pengukuran">
        
        </div>
        </div>

<div class="row mb-3">
                        <label class="col-sm-9 col-form-label">
                            <strong>TTV</strong>
                        </label>    
                    </div>

                    <!-- Tekanan Darah -->
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-2 col-form-label"><strong>TD</strong></label>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="text" class="form-control" name="tekanandarah">
                                <span class="input-group-text">mmHg</span>
                        </div>    
                    </div>
                                
                    <!-- Nadi -->
                    <label class="col-sm-2 col-form-label"><strong>N</strong></label>
                    <div class="col-sm-3">
                        <div class="input-group">
                                <input type="text" class="form-control" name="nadi">
                                <span class="input-group-text">x/menit</span>
                        </div> 
                    </div>
                    </div>

                   
              
                <!-- Suhu -->
                <div class="row mb-3 align-items-center">
                    <label class="col-sm-2 col-form-label"><strong>S</strong></label>
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

                
                    <div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>4.	Alat</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="alat">
    
        </div>
        </div>

<div class="row mb-2">
<label class="col-sm-12 text-primary"><strong>F. Prosedur Kerja</strong></label>
</div>
<p>(Tuliskan suatu tindakan yang diberikan mulai dari persiapan sampai selesai melakukan HD)</p>
<div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>Kelainan</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="kelainan_mata">
       
        </div>
        </div>
<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>1.	Pre HD</strong></label>

<div class="col-sm-9">
<input type="text" class="form-control" name="pre">


</div>
</div>
<div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>Kelainan</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="kelainan_mata">
    
        </div>
        </div>
<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>2.	Post HD</strong></label>

<div class="col-sm-9">
<input type="text" class="form-control" name="pos">


</div>
</div>
<div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>Kelainan</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="kelainan_mata">
       
        </div>
        </div>
<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>3.	Observasi</strong></label>

<div class="col-sm-9">
<input type="text" class="form-control" name="observasi">


</div>
</div>
<div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>Kelainan</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="kelainan_mata">
      
        </div>
        </div>
<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>4.	Respon terhadap tindakan HD</strong></label>

<div class="col-sm-9">
<input type="text" class="form-control" name="respon">

</div>
</div>
<div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>Kelainan</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="kelainan_mata">
    
        </div>
        </div>
<div class="row mb-3">
<label class="col-sm-2 col-form-label"><strong>5.	Hasil yang diperoleh</strong></label>

                <!-- General Form Elements -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

                <!-- Bagian Diagnosa -->
                 <div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>Jam</strong>
    </div>
    <div class="col-sm-9">
        <input type="time" class="form-control" name="Jam">
     
        </div>
        </div>

                  
<div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>TD</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="td">
        
        </div>
        </div>

<div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>Nadi</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="nadi">
      
        </div>
        </div>

                    <div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>Qb</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="qb">
        
        </div>
        </div>

                    <div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>TMP</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="tmp">
        
        </div>
        </div>
 
                    <div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>Tek. A</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="tek.a">
        
        </div>
        </div>

                    <div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>Tek. V</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="tek.v">
        
        </div>
        </div>
 
                    <div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>Hp</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="hp">
       
        </div>
        </div>


                    <!-- Bagian Button -->    
                    <div class="row mb-3">
                        <div class="col-sm-11 justify-content-end d-flex">
                            <button type="submit" name="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </div> 

                    <h5 class="card-title mt-2"><strong>5. Hasil yang diperoleh
</strong></h5>

                    <style>
                    .table-pemeriksaan {
                        table-layout: fixed;
                        width:100%
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
                                <th class="text-center">Jam</th>
                                <th class="text-center">TD</th>
                                <th class="text-center">Nadi</th>
                                <th class="text-center">Qb</th>
                                <th class="text-center">TMP</th>
                                <th class="text-center">Tek. A</th>
                                <th class="text-center">Tek. V</th>
                                <th class="text-center">Hp</th>

                        </tr>
                        </thead>
                        </table>
                        </form>
                        </div>
                    
<div class="row mb-2">
<label class="col-sm-12 text-primary"><strong>G.	Health Education (HE) yang diberikan sebelum meninggalkan HD:</strong></label>
</div>
<div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>Health Education (HE) yang diberikan sebelum meninggalkan HD</strong>
    </div>
    <div class="col-sm-9">
        <textarea name="health_education" class="form-control" rows="7" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

      
        </div>
        </div>


</div>

</section> <?php include "tab_navigasi.php"; ?>             
</main>
                
                 