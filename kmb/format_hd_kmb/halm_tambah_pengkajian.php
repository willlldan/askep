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
              <h5 class="card-title mb-1"><strong>A. PENGKAJIAN</strong></h5>
                   <!-- General Form Elements -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data ">
              <h5 class="card-title mb-1"><strong>1. Identitas</strong></h5>

    <div class="row mb-3">
        <label class="col-sm-2 col-form-label text-primary"><strong>a. Klien </strong></label>
    </div>

    <div class="row mb-3">
        <label for= "nama_klien" class="col-sm-2 col-form-label"><strong> Nama (Inisial) </strong></label>
        <div class="col-sm-9">
            <input type="text" class="form-control" name="nama_klien">
 
        </div>
    </div>

    <div class="row mb-3">
        <label for="ttl_umur" class="col-sm-2 col-form-label"><strong>Tempat/Tgl Lahir/Umur</strong></label>
        <div class="col-sm-9">
            <input type="text" class="form-control" name="ttl_umur">
  
        </div>
    </div>

    <div class="row mb-3">
        <label for="jenis_kelamin" class="col-sm-2 col-form-label"><strong>Jenis Kelamin</strong></label>
        <div class="col-sm-9">
            <select class="form-control" name="jenis_kelamin">
                <option value="">Pilih </option>
                <option value="Laki-laki">Laki-laki</option>
                <option value="Perempuan">Perempuan</option>
            </select>
            
        </div>
    </div>
    <div class="row mb-3">
        <label for="status_perkawinan" class="col-sm-2 col-form-label"><strong>Status Perkawinan</strong></label>
        <div class="col-sm-9">
            <input type="text" class="form-control" name="status_perkawinan">
      
        </div>
    </div>

    <div class="row mb-3">
        <label for="agama" class="col-sm-2 col-form-label"><strong>Agama</strong></label>
        <div class="col-sm-9">
            <input type="text" class="form-control" name="agama">
       
        </div>
    </div>

    <div class="row mb-3">
        <label for="pendidikan" class="col-sm-2 col-form-label"><strong>Pendidikan</strong></label>
        <div class="col-sm-9">
            <input type="text" class="form-control" name="pendidikan">
        
        </div>
    </div>

    <div class="row mb-3">
        <label for="pekerjaan" class="col-sm-2 col-form-label"><strong>Pekerjaan</strong></label>
        <div class="col-sm-9">
            <input type="text" class="form-control" name="pekerjaan">
            
      
        </div>
    </div>
    <div class="row mb-3">
        <label for="pekerjaan" class="col-sm-2 col-form-label"><strong>Alamat</strong></label>
        <div class="col-sm-9">
            <input type="text" class="form-control" name="alamat">
     
        </div>
    </div>

 
  

    <div class="row mb-3">
        <label for="tgl_masuk_rs" class="col-sm-2 col-form-label"><strong>Tanggal Masuk RS</strong></label>
        <div class="col-sm-9">
            <input type="date" class="form-control" name="tgl_masuk_rs">
      
        </div>
    </div>

    <div class="row mb-3">
        <label for="tgl_pengkajian" class="col-sm-2 col-form-label"><strong>Tanggal Pengkajian</strong></label>
        <div class="col-sm-9">
            <input type="date" class="form-control" name="tgl_pengkajian">
       
        </div>
    </div>

    <div class="row mb-3">
        <label for="diagnosa_medik" class="col-sm-2 col-form-label"><strong>Diagnosa Medik</strong></label>
        <div class="col-sm-9">
            <input type="text" class="form-control" name="diagnosa_medik">
      
        </div>
    </div>

    <div class="row mb-3">
        <label for="golongan_darah" class="col-sm-2 col-form-label"><strong>Golongan Darah</strong></label>
        <div class="col-sm-9">
            <input type="text" class="form-control" name="golongan_darah">
        
        </div>
    </div>

    <div class="row mb-3">
        <label for="no_registrasi" class="col-sm-2 col-form-label"><strong>No Registrasi</strong></label>
        <div class="col-sm-9">
            <input type="text" class="form-control" name="no_registrasi">
       
        </div>
    </div>

    <div class="row mb-3">
        <label for="ruangan" class="col-sm-2 col-form-label"><strong>Ruangan</strong></label>
        <div class="col-sm-9">
            <input type="text" class="form-control" name="ruangan">
        
        </div>
    </div>


    <div class="row mb-2">
        <label class="col-sm-12 text-primary"><strong>b. Identitas Penanggung Jawab</strong></label>
    </div>

    <div class="row mb-3">
        <label for= "nama_klien" class="col-sm-2 col-form-label"><strong> Nama (Inisial) </strong></label>
        <div class="col-sm-9">
            <input type="text" class="form-control" name="nama_klien">
   
        </div>
    </div>

    <div class="row mb-3">
        <label for="ttl_umur" class="col-sm-2 col-form-label"><strong>Tempat/Tgl Lahir/Umur</strong></label>
        <div class="col-sm-9">
            <input type="text" class="form-control" name="ttl_umur">
   
        </div>
    </div>

    <div class="row mb-3">
        <label for="jenis_kelamin" class="col-sm-2 col-form-label"><strong>Jenis Kelamin</strong></label>
        <div class="col-sm-9">
            <select class="form-control" name="jenis_kelamin">
                <option value="">Pilih </option>
                <option value="Laki-laki">Laki-laki</option>
                <option value="Perempuan">Perempuan</option>
            </select>
            
        </div>
    </div>

    <div class="row mb-3">
        <label for="hubungan_klien" class="col-sm-2 col-form-label"><strong>Hubungan dengan Klien</strong></label>
        <div class="col-sm-9">
            <input type="text" class="form-control" name="hubungan_klien">
      
        </div>
    </div>
<div class="row mb-3">
        <label for="agama" class="col-sm-2 col-form-label"><strong>Agama</strong></label>
        <div class="col-sm-9">
            <input type="text" class="form-control" name="agama">
       
        </div>
    </div>

    <div class="row mb-3">
        <label for="pendidikan" class="col-sm-2 col-form-label"><strong>Pendidikan</strong></label>
        <div class="col-sm-9">
            <input type="text" class="form-control" name="pendidikan">
       
        </div>
    </div>

    <div class="row mb-3">
        <label for="pekerjaan" class="col-sm-2 col-form-label"><strong>Pekerjaan</strong></label>
        <div class="col-sm-9">
            <input type="text" class="form-control" name="pekerjaan">
      
    </div>
    </div>

    <div class="row mb-3">
        <label for="alamat" class="col-sm-2 col-form-label"><strong>Alamat</strong></label>
        <div class="col-sm-9">
            <textarea class="form-control" rows="3" name="alamat"style="display:block; overflow:hidden; resize: none;"
                                    oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" ></textarea>
        </div>
  
    </div>
</form>
        </div>
        </div>
        </section>
         <section class="section dashboard">
        <div class="card">
             <div class="card-body">
                                
<!-- 2 KEADAAN UMUM -->
                     <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

                    <h5 class="card-title"><strong>2. Keadaan Umum</strong></h5>



<!-- A TANDA VITAL -->
<div class="row mb-2">
<label class="col-sm-12 text-primary"><strong>a. Tanda Vital</strong></label>
</div>
<div class="row mb-2">
<label class="col-sm-12 "><strong>Pre HD</strong></label>
</div>
<!-- TD -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Nadi</strong></label>

    <div class="col-sm-9">
        <div class="input-group">
            <input type="text" class="form-control" name="td">
            <span class="input-group-text">/menit</span>
        </div>

     
        </div>
        </div>

<!-- Nadi -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Nadi</strong></label>

    <div class="col-sm-9">
        <div class="input-group">
            <input type="text" class="form-control" name="nadi">
            <span class="input-group-text">/menit</span>
        </div>

        </div>
        </div>

<!-- Pernafasan -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Pernafasan</strong></label>

    <div class="col-sm-9">
        <div class="input-group">
            <input type="text" class="form-control" name="pernafasan">
            <span class="input-group-text">x/menit</span>
        </div>

      
        </div>
        </div>
<!-- TD (Tekanan Darah) -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>TD (Tekanan Darah)</strong></label>

    <div class="col-sm-9">
        <div class="input-group">
            <input type="text" class="form-control" name="td">
            <span class="input-group-text">mmHg</span>
        </div>

     
        </div>
        </div>

<!-- Suhu -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Suhu</strong></label>

    <div class="col-sm-9">
        <div class="input-group">
            <input type="text" class="form-control" name="suhu">
            <span class="input-group-text">°C</span>
        </div>

       
        </div>
        </div>
<div class="row mb-2">
<label class="col-sm-12 "><strong>Post HD</strong></label>
</div>

<!-- Nadi -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Nadi</strong></label>

    <div class="col-sm-9">
        <div class="input-group">
            <input type="text" class="form-control" name="nadi">
            <span class="input-group-text">/menit</span>
        </div>

        
        </div>
        </div>

<!-- Pernafasan -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Pernafasan</strong></label>

    <div class="col-sm-9">
        <div class="input-group">
            <input type="text" class="form-control" name="pernafasan">
            <span class="input-group-text">x/menit</span>
        </div>

        
        </div>
        </div>

<!-- TD (Tekanan Darah) -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>TD (Tekanan Darah)</strong></label>

    <div class="col-sm-9">
        <div class="input-group">
            <input type="text" class="form-control" name="td">
            <span class="input-group-text">mmHg</span>
        </div>

        
        </div>
        </div>

<!-- Suhu -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>Suhu</strong></label>

    <div class="col-sm-9">
        <div class="input-group">
            <input type="text" class="form-control" name="suhu">
            <span class="input-group-text">°C</span>
        </div>

        
        </div>
        </div>

<!-- B KESADARAN -->
<div class="row mb-2">
<label class="col-sm-12 text-primary"><strong>b. Kesadaran</strong></label>
</div>
<!-- GCS -->

                        <div class="row mb-3">

                        <label class="col-sm-2 col-form-label"><strong>Glasgow Coma Scale (GCS)</strong></label>
                            <div class="col-sm-9">
                                <div class="row">

                        <!-- E -->
                        <div class="col-md-4 d-flex align-items-center">
                            <label class="me-2"><strong>M</strong></label>
                            <input type="text" class="form-control" name="e">
                        </div>

                        <!-- M -->
                        <div class="col-md-4 d-flex align-items-center">
                            <label class="me-2"><strong>V</strong></label>
                            <input type="text" class="form-control" name="m">
                        </div>

                        <!-- V -->
                        <div class="col-md-4 d-flex align-items-center">
                            <label class="me-2"><strong>E</strong></label>
                            <input type="text" class="form-control" name="v">
                        </div>
                    </div>

                       
                  
        </div>
        </div>
<div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>Tingkat Kesadaran</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="tingkat_kesadaran">
        
        </div>
    </div>




<!-- C ANTROPOMETRI -->
<div class="row mb-2">
<label class="col-sm-12 text-primary"><strong>Berat badan (BB)</strong></label>
</div>
<!-- BB Sebelum Sakit -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label"><strong>BB HD sebelumnya</strong></label>

    <div class="col-sm-9">
        <input type="text" class="form-control" name="bb_hd">

       
        </div>
        </div>
<div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>BB Pre HD (sebelum HD)</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="bb_prehd">
     
        </div>
        </div>


<div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>BB Post HD (setelah HD)</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="bb_posthd">
       
        </div>
        </div>


<div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>BB Kering</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="bbkering">
        
        </div>
        </div>


<div class="row mb-3">
    <div class="col-sm-2 col-form-label">
        <strong>Kenaikan BB</strong>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="kenaikanbb">
        
        </div>
        </div>

        
        </form>
        </div>
        </div>

<!-- 3 RIWAYAT KESEHATAN -->
 <div class="card">
             <div class="card-body">
                                
                     <!-- General Form Elements -->
                    <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

                    <h5 class="card-title"><strong>3. Riwayat Kesehatan</strong></h5>


<!-- A ALASAN MASUK RS -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label">
        <strong>a. Alasan Masuk Rumah Sakit</strong>
    </label>

    <div class="col-sm-9">
        <textarea class="form-control" rows="3" 
        name="alasan_masuk_rs"
        style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

       
    </div>
</div>

<!-- B KELUHAN UTAMA -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label">
        <strong>b. Keluhan Utama</strong>
    </label>

    <div class="col-sm-9">
        <textarea class="form-control" rows="3" 
         name="keluhan_utama"
         style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

        
    </div>
</div>

<!-- C RIWAYAT KELUHAN UTAMA -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label">
        <strong>c. Riwayat Keluhan Utama</strong>
    </label>

    <div class="col-sm-9">
        <textarea class="form-control" rows="4" 
        name="riwayat_keluhan_utama"
        style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

        
    </div>
</div>

<!-- D RIWAYAT KESEHATAN YANG LALU -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label">
        <strong>d. Riwayat Kesehatan yang Lalu</strong>
    </label>

    <div class="col-sm-9">

        <small class="form-text" style="color: red;">Bentuk kepala, Penyebaran, Kebersihan, Warna Rambut. Hasil:</small>

        <textarea class="form-control" rows="4" name="riwayat_kesehatan_lalu"
        style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

        
    </div>
</div>

<!-- E RIWAYAT KESEHATAN KELUARGA -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label">
        <strong>e. Riwayat Kesehatan Keluarga</strong>
    </label>

    <div class="col-sm-9">
        <textarea class="form-control" rows="4" name="riwayat_kesehatan_keluarga"
        style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>

       
    </div>
</div>
  <!-- Bagian Genogram -->
                <div class="row mb-3">
                    <label for="genogram" class="col-sm-2 col-form-label"><strong>Genogram</strong></label>
                    <div class="col-sm-9">

                        <!-- Link Google Drive -->
                         <div class="form-control d-flex justify-content-between align-items-center">
                            <span>Upload Gambar Genogram pada link Google Drive yang tersedia</span>
                            <a href="<?= $genogram ?>" target="_blank" class="btn btn-sm btn-primary">Upload</a>
                        </div>

                       
                         </div>
                    </div>

</div>
</div>
</form>
</div>
</div>



         <?php include "tab_navigasi.php"; ?>
</section>              
</main>
                
                 