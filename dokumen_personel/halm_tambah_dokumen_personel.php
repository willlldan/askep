<?php
require_once "koneksi.php";
require_once "utils.php";
if (isset($_POST['submit'])) {
    $nrp_nip = $_POST['nrp_nip'];
    $nama = $_POST['nama'];
    $pangkat_golongan = $_POST['pangkat_golongan'];
    $kesatuan = $_POST['kesatuan'];
    $tempat_lahir = $_POST['tempat_lahir'];
    $tgl_lahir = $_POST['tgl_lahir'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $agama = $_POST['agama'];
    $alamat_rumah = $_POST['alamat_rumah'];
    $data_keluarga = $_POST['data_keluarga'];
    $hasil_urikes = $_POST['hasil_urikes'];
    $hasil_samapta = $_POST['hasil_samapta'];
    $tmt_pangkat_pertama = $_POST['tmt_pangkat_pertama'];
    $tmt_pangkat_kedua = $_POST['tmt_pangkat_kedua'];
    $tmt_masuk_satuan = $_POST['tmt_masuk_satuan'];
    $no_ktp = $_POST['no_ktp'];
    $no_bpjs = $_POST['no_bpjs'];
    $no_npwp = $_POST['no_npwp'];
    $pendidikan_terakhir = $_POST['pendidikan_terakhir'];
    $riwayat_jabatan = $_POST['riwayat_jabatan'];
    $tanda_kehormatan = $_POST['tanda_kehormatan'];
    $pendidikan_umum = $_POST['pendidikan_umum'];
    $pendidikan_militer = $_POST['pendidikan_militer'];
    $pelatihan_khusus = $_POST['pelatihan_khusus'];
    $label_arsip = $_POST['label_arsip'];
    $rak_arsip = $_POST['rak_arsip'];
    $keterangan = $_POST['keterangan'];
    $file_name = "";
    $foto = "";

    if (isset($_FILES['foto']['name']) && !empty($_FILES['foto']['name'])) {
        echo "Masuk sini bro";
        $target_dir = "dokumen_personel/photos/";
        $foto = date("YmdHis_") . basename($_FILES["foto"]["name"]);
        $target_file = $target_dir . $foto;
        $uploadOk = 1;
        $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    
        $allowed_extensions = array('jpeg', 'jpg', 'png'); // Format yang diizinkan
    
        if (!in_array($file_type, $allowed_extensions)) {
            echo "Format file tidak diizinkan.";
            $uploadOk = 0;
        }
    
        if ($uploadOk) {
            if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
                echo "Foto berhasil diedit.";
            } else {
                echo "Terjadi kesalahan saat melakukan edit foto personel.";
            }
        }
    }


    
    if (isset($_FILES['file']['name']) && !empty($_FILES['file']['name'])) {
        $target_dir = "dokumen_personel/uploads/";
        $file_name = date("YmdHis_") . basename($_FILES["file"]["name"]);
        $target_file = $target_dir . $file_name;
        $uploadOk = 1;
        $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    
        $allowed_extensions = array('pdf', 'rar', 'zip', 'jpeg', 'jpg', 'png'); // Format yang diizinkan
    
        if (!in_array($file_type, $allowed_extensions)) {
            echo "Format file tidak diizinkan.";
            $uploadOk = 0;
        }
    
        if ($uploadOk) {
            if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
                echo "Dokumen Personel berhasil diedit.";
            } else {
                echo "Terjadi kesalahan saat melakukan edit dokumen personel.";
            }
        }
    }
    
$sql = "INSERT INTO tbl_dok_personel (
    nrp_nip,
    nama, 
    pangkat_golongan, 
    kesatuan,
    tempat_lahir, 
    tgl_lahir,
    jenis_kelamin,
    agama,
    alamat_rumah,
    data_keluarga,
    hasil_urikes,
    hasil_samapta,
    tmt_pangkat_pertama,
    tmt_pangkat_kedua,
    tmt_masuk_satuan,
    no_ktp,
    no_bpjs,
    no_npwp,
    pendidikan_terakhir,
    riwayat_jabatan,
    tanda_kehormatan,
    pendidikan_umum,
    pendidikan_militer,
    pelatihan_khusus,
    label_arsip,      
    rak_arsip,
    keterangan, 
    file,
    foto
) VALUES (
    '$nrp_nip',
    '$nama', 
    '$pangkat_golongan',
    '$kesatuan',
    '$tempat_lahir',
    '$tgl_lahir',
    '$jenis_kelamin',
    '$agama',
    '$alamat_rumah',
    '$data_keluarga',
    '$hasil_urikes',
    '$hasil_samapta',
    '$tmt_pangkat_pertama',
    '$tmt_pangkat_kedua',
    '$tmt_masuk_satuan',
    '$no_ktp',
    '$no_bpjs',
    '$no_npwp',
    '$pendidikan_terakhir',
    '$riwayat_jabatan',
    '$tanda_kehormatan',
    '$pendidikan_umum',
    '$pendidikan_militer',
    '$pelatihan_khusus',
    '$label_arsip',
    '$rak_arsip',
    '$keterangan',
    '$file_name',
    '$foto'
)";
                

    if ($mysqli->query($sql) === TRUE) {
        echo "<script>alert('Dokumen Personel berhasil ditambah.')</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $mysqli->error;
    }
}
?>

<style>
    img#thumbnail{
    max-width:180px;
    }

    .row-foto {
    display: flex;
    align-items: center;
}
</style>

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Tambah Dokumen</h1>
        <!-- <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Home</a></li>
          <li class="breadcrumb-item active">Dashboard</li>
        </ol>
        </nav> -->
    </div><!-- End Page Title -->
    <br>
    <section class="section dashboard">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Dokumen Personel</h5>

                <!-- General Form Elements -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">
                

                <!-- Bagian Foto -->
                <div class="row mb-3 row-foto">
                    <div class="col-sm-2 label-foto">
                        <label for="foto">Foto</label>
                    </div>
                    <div class="col-sm-3 thumbnail-foto">
                        <img id="thumbnail" src="http://placehold.it/180" alt="your image" />
                    </div>
                    <div class="col-4 input-foto center-vertically">
                        <input type='file' id="foto" onchange="readURL(this); name='foto' " />
                    </div>
                </div>

                <!-- Bagian NRP / NIP -->
                <div class="row mb-3">
                    <label for="nrp_nip" class="col-sm-2 col-form-label">NRP / NIP *</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="nrp_nip" required>
                        <div class="invalid-feedback">
                            Harap isi NRP / NIP.
                        </div>
                    </div>
                </div>

                <!-- Bagian Nama -->
                <div class="row mb-3">
                    <label for="nama" class="col-sm-2 col-form-label">Nama *</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="nama" required>
                        <div class="invalid-feedback">
                            Harap isi Nama.
                        </div>
                    </div>
                </div>

                 <!-- Bagian No KTP -->
                 <div class="row mb-3">
                    <label for="no_ktp" class="col-sm-2 col-form-label">No KTP *</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="no_ktp" required>
                        <div class="invalid-feedback">
                            Harap isi No KTP.
                        </div>
                    </div>
                </div>

                <!-- Bagian No BPJS -->
                <div class="row mb-3">
                    <label for="no_bpjs" class="col-sm-2 col-form-label">No BPJS *</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="no_bpjs" required>
                        <div class="invalid-feedback">
                            Harap isi No BPJS.
                        </div>
                    </div>
                </div>

                <!-- Bagian No NPWP -->
                <div class="row mb-3">
                    <label for="no_npwp" class="col-sm-2 col-form-label">No NPWP *</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="no_npwp" required>
                        <div class="invalid-feedback">
                            Harap isi No NPWP.
                        </div>
                    </div>
                </div>

                <!-- Bagian Pangkat / Golongan -->
                <div class="row mb-3">
                    <label for="pangkat_golongan" class="col-sm-2 col-form-label">Pangkat / Golongan *</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="pangkat_golongan" required>
                        <div class="invalid-feedback">
                            Harap isi Pangkat / Golongan.
                        </div>
                    </div>
                </div>

                <!-- Bagian Kesatuan -->
                <div class="row mb-3">
                    <label for="kesatuan" class="col-sm-2 col-form-label">Kesatuan *</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="kesatuan" required>
                        <div class="invalid-feedback">
                            Harap isi Kesatuan.
                        </div>
                    </div>
                </div>

                <!-- Bagian Tempat Lahir -->
                <div class="row mb-3">
                    <label for="tempat_lahir" class="col-sm-2 col-form-label">Tempat Lahir *</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="tempat_lahir" required>
                        <div class="invalid-feedback">
                            Harap isi Tempat Lahir.
                        </div>
                    </div>
                </div>

                <!-- Bagian Tanggal Lahir -->
                <div class="row mb-3">
                    <label for="tgl_lahir" class="col-sm-2 col-form-label">Tanggal Lahir *</label>
                        <div class="col-sm-10">
                            <input type="date" class="form-control" id="tgl_lahir" name="tgl_lahir" required value="<?= $row['tgl_lahir']; ?>">
                        <div class="invalid-feedback">
                            Harap isi Tanggal Lahir.
                        </div>
                    </div>
                </div>

                <!-- Bagian Jenis Kelamin -->
                <div class="row mb-3">
                    <label for="jenis_kelamin" class="col-sm-2 col-form-label">Jenis Kelamin *</label>
                        <div class="col-sm-10">
                            <select class="form-select" name="jenis_kelamin" required>
                                <option value="Laki-laki">Laki-laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                        <div class="invalid-feedback">
                            Harap isi Jenis Kelamin.
                        </div>
                    </div>
                </div>              

                <!-- Bagian Agama -->
                <div class="row mb-3">
                    <label for="agama" class="col-sm-2 col-form-label">Agama *</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="agama" required>
                        <div class="invalid-feedback">
                             Harap isi Agama.
                        </div>
                    </div>
                </div>

                 <!-- Bagian Alamat Rumah -->
                 <div class="row mb-3">
                    <label for="alamat_rumah" class="col-sm-2 col-form-label">Alamat Rumah *</label>
                        <div class="col-sm-10">
                            <textarea input type="text" name="alamat_rumah" class="form-control" rows="5" cols="30"></textarea>
                        <div class="invalid-feedback">
                            Harap isi Alamat Rumah.
                        </div>
                    </div>
                </div>  

              <!-- Bagian Data Keluarga -->
              <div class="row mb-3">
                    <label for="data_keluarga" class="col-sm-2 col-form-label">Data Keluarga *</label>
                        <div class="col-sm-10">
                            <textarea input type="text" name="data_keluarga" class="form-control" rows="5" cols="30"></textarea>
                        <div class="invalid-feedback">
                            Harap isi Data Keluarga.
                        </div>
                    </div>
                </div>   

                <!-- Bagian Hasil Urikes -->
                <div class="row mb-3">
                    <label for="hasil_urikes" class="col-sm-2 col-form-label">Hasil Urikes Terakhir *</label>
                        <div class="col-sm-10">
                            <textarea input type="text" name="hasil_urikes" class="form-control" rows="5" cols="30"></textarea>
                        <div class="invalid-feedback">
                            Harap isi Hasil Urikes.
                        </div>
                    </div>
                </div>   

                <!-- Bagian Hasil Samapta -->
                <div class="row mb-3">
                    <label for="hasil_samapta" class="col-sm-2 col-form-label">Hasil Samapta Terakhir*</label>
                        <div class="col-sm-10">
                            <textarea input type="text" name="hasil_samapta" class="form-control" rows="5" cols="30"></textarea>
                        <div class="invalid-feedback">
                            Harap isi Pendidikan Terakhir.
                        </div>
                    </div>
                </div>   

                <!-- Bagian TMT Pangkat Pertama -->
                <div class="row mb-3">
                    <label for="tmt_pangkat_pertama" class="col-sm-2 col-form-label">TMT Pangkat Pertama *</label>
                        <div class="col-sm-10">
                            <input type="date" class="form-control" name="tmt_pangkat_pertama" required>
                            <!-- textarea input type="text" name="tmt_pangkat_pertama" class="form-control" rows="5" cols="30"></textarea -->
                        <div class="invalid-feedback">
                            Harap isi TMT Pangkat Pertama.
                        </div>
                    </div>
                </div>   

                <!-- Bagian TMT Pangkat Kedua -->
                <div class="row mb-3">
                    <label for="tmt_pangkat_kedua" class="col-sm-2 col-form-label">TMT Pangkat Kedua *</label>
                        <div class="col-sm-10">
                            <input type="date" class="form-control" name="tmt_pangkat_kedua" required>
                            <!-- textarea input type="text" name="tmt_pangkat_kedua" class="form-control" rows="5" cols="30"></textarea -->
                        <div class="invalid-feedback">
                            Harap isi TMT Pangkat Kedua.
                        </div>
                    </div>
                </div> 
                
                <!-- Bagian TMT Masuk Satuan -->
                <div class="row mb-3">
                    <label for="tmt_masuk_satuan" class="col-sm-2 col-form-label">TMT Masuk Satuan *</label>
                        <div class="col-sm-10">
                            <input type="date" class="form-control" name="tmt_masuk_satuan" required>
                            <!-- textarea input type="text" name="tmt_masuk_satuan" class="form-control" rows="5" cols="30"></textarea -->
                        <div class="invalid-feedback">
                            Harap isi TMT Masuk Satuan.
                        </div>
                    </div>
                </div>   

                <!-- Bagian Pendidikan Terakhir -->
                <div class="row mb-3">
                    <label for="pendidikan_terakhir" class="col-sm-2 col-form-label">Pendidikan Terakhir *</label>
                        <div class="col-sm-10">
                            <textarea input type="text" name="pendidikan_terakhir" class="form-control" rows="5" cols="30"></textarea>
                        <div class="invalid-feedback">
                            Harap isi Pendidikan Terakhir.
                        </div>
                    </div>
                </div>   

                <!-- Bagian Riwayat Jabatan -->
                <div class="row mb-3">
                    <label for="riwayat_jabatan" class="col-sm-2 col-form-label">Riwayat Jabatan *</label>
                        <div class="col-sm-10">
                            <textarea input type="text" name="riwayat_jabatan" class="form-control" rows="5" cols="30"></textarea>
                        <div class="invalid-feedback">
                            Harap isi Riwayet Jabatan.
                        </div>
                    </div>
                </div>  

                <!-- Bagian Tanda Kehormatan -->
                <div class="row mb-3">
                    <label for="tanda_kehormatan" class="col-sm-2 col-form-label">Tanda Kehormatan *</label>
                        <div class="col-sm-10">
                            <textarea input type="text" name="tanda_kehormatan" class="form-control" rows="5" cols="30"></textarea>
                        <div class="invalid-feedback">
                            Harap isi Tanda Kehormatan.
                        </div>
                    </div>
                </div>  
                    
                <!-- Bagian Pendidikan Umum -->
                <div class="row mb-3">
                    <label for="pendidikan_umum" class="col-sm-2 col-form-label">Pendidikan Umum *</label>
                        <div class="col-sm-10">
                            <textarea input type="text" name="pendidikan_umum" class="form-control" rows="5" cols="30"></textarea>
                        <div class="invalid-feedback">
                            Harap isi Pendidikan Umum.
                        </div>
                    </div>
                </div>  

                <!-- Bagian Pendidikan Militer -->
                <div class="row mb-3">
                    <label for="pendidikan_militer" class="col-sm-2 col-form-label">Pendidikan Militer *</label>
                        <div class="col-sm-10">
                            <textarea input type="text" name="pendidikan_militer" class="form-control" rows="5" cols="30"></textarea>
                        <div class="invalid-feedback">
                            Harap isi Pendidikan Militer.
                        </div>
                    </div>
                </div>  

                <!-- Bagian Pelatihan Khusus -->
                <div class="row mb-3">
                    <label for="pelatihan_khusus" class="col-sm-2 col-form-label">Pelatihan Khusus *</label>
                        <div class="col-sm-10">
                            <textarea input type="text" name="pelatihan_khusus" class="form-control" rows="5" cols="30"></textarea>
                        <div class="invalid-feedback">
                            Harap isi Pelatihan Khusus.
                        </div>
                    </div>
                </div>  
                
                <!-- Bagian Label Arsip -->
                <div class="row mb-3">
                    <label for="label_arsip" class="col-sm-2 col-form-label">Label Arsip *</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="label_arsip" required>
                        <div class="invalid-feedback">
                            Harap isi Label Arsip.
                        </div>
                    </div>
                </div>
                
                <!-- Bagian Rak Arsip -->
                <div class="row mb-3">
                    <label for="rak_arsip" class="col-sm-2 col-form-label">Rak Arsip *</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="rak_arsip" name="rak_arsip" required>
                        <small class="form-text" style="color: red;"> * Contoh Pengetikan Rak Arsip: A-JAN-001</small>
                        <small class="form-text" style="color: red;"> <br> A : Kode Laci <br> JAN : Kode Bulan Dokumen <br> 001 : Urutan Dokumen</small>
                        <div class="invalid-feedback">
                            Harap isi Rak Arsip dengan format yang benar (contoh: AJAN001).
                        </div>
                    </div>
                </div>

                <!-- Bagian Keterangan -->
                <div class="row mb-3">
                    <label for="keterangan" class="col-sm-2 col-form-label">Keterangan</label>
                        <div class="col-sm-10">
                            <textarea input type="text" name="keterangan" class="form-control" rows="5" cols="30"></textarea>
                        <div class="invalid-feedback">
                            Harap isi Keterangan.
                        </div>
                    </div>
                </div>

                 <!-- Bagian File -->
                 <div class="row mb-3">
                    <label for="file" class="col-sm-2 col-form-label">File</label>
                    <div class="col-sm-10">
                        <input type="file" class="form-control" name="file" id="file">
                        <br>
                        <small>* Unggah foto dan dokumen dalam satu file .zip / .rar (dokumen data personel, SK, dll) </small> 
                        <br>
                        <small>* Biarkan kosong jika tidak ingin mengunggah file</small>
                        <br>
                        <small>* Yang memiliki simbol bintang wajib diisi</small>
                    </div>
                </div>

                <!-- Bagian Button -->
                <div class="row mb-3">
                    <div class="col-sm-12 justify-content-end d-flex">
                        <button type="submit" name="submit" class="btn btn-primary">Tambah Dokumen</button>
                    </div>
                </div>
                </form><!-- End General Form Elements -->
            </div>
        </div>
    </section>
</main>

    <script src="assets/js/jquery-3.6.0.min.js"></script>

    <script>
        console.log(jQuery.fn.jquery); // Cetak versi jQuery ke konsol
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#thumbnail')
                        .attr('src', e.target.result);
                };

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</html>

