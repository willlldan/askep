<?php
if (isset($_GET['id_dokumen_personel'])) {
    require_once "koneksi.php";
    require_once "utils.php";

    $id_dokumen_personel = $_GET['id_dokumen_personel'];
    $sql = "SELECT * FROM tbl_dok_personel WHERE id_dokumen_personel = '$id_dokumen_personel'";
    $result = $mysqli->query($sql);
    $row = $result->fetch_assoc();

} else {
    echo "<script>window.location.href='index.php?page=dokumen_personel&item=tampil_dokumen_personel';</script>";
}

if (isset($_POST['submit'])) {
    $nrp_nip = $_POST['nrp_nip'];
    $update_dokumen = $_POST['update_dokumen'];
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
    

    // Hapus nama file dari database jika file dihapus
    if (isset($_POST['remove_file']) && $_POST['remove_file'] === '1') {
        $file_name = "";
        // Jika file dihapus, hapus juga file fisik dari server jika ada
        if (!empty($row['file'])) {
            $file_path = "dokumen_personel/uploads/" . $row['file'];
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }
    }

    $sql = "UPDATE tbl_dok_personel 
            SET 
                nrp_nip='$nrp_nip',
                update_dokumen='$update_dokumen',
                nama='$nama',
                pangkat_golongan='$pangkat_golongan',
                kesatuan='$kesatuan',
                tempat_lahir='$tempat_lahir',
                tgl_lahir='$tgl_lahir',
                jenis_kelamin='$jenis_kelamin',
                agama='$agama',
                alamat_rumah='$alamat_rumah',
                data_keluarga='$data_keluarga',
                hasil_urikes='$hasil_urikes',
                hasil_samapta='$hasil_samapta',
                tmt_pangkat_pertama='$tmt_pangkat_pertama',
                tmt_pangkat_kedua='$tmt_pangkat_kedua',
                tmt_masuk_satuan='$tmt_masuk_satuan',
                no_ktp='$no_ktp',
                no_bpjs='$no_bpjs',
                no_npwp='$no_npwp',
                pendidikan_terakhir='$pendidikan_terakhir',
                riwayat_jabatan='$riwayat_jabatan',
                tanda_kehormatan='$tanda_kehormatan',
                pendidikan_umum='$pendidikan_umum',
                pendidikan_militer='$pendidikan_militer',
                pelatihan_khusus='$pelatihan_khusus',
                label_arsip='$label_arsip',
                rak_arsip='$rak_arsip',
                keterangan='$keterangan',
                file='$file_name' ,
                foto='$foto'
            WHERE 
                id_dokumen_personel='$id_dokumen_personel'";

    if ($mysqli->query($sql) === TRUE) {
        echo "<script>alert('Dokumen Personel berhasil diedit.')</script>";
        echo "<script>window.location.href='index.php?page=dokumen_personel&item=tampil_dokumen_personel';</script>";
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
        <h1>Edit Dokumen</h1>
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
                        <img id="thumbnail" src="dokumen_personel/photos/<?= $row['foto']; ?>" alt="your image" />
                    </div>
                    <div class="col-4 input-foto center-vertically">
                        <input type='file' id="foto" onchange="readURL(this); name='foto' " />
                    </div>
                </div>

                 <!-- Bagian Update Dokumen -->
                 <div class="row mb-3">
                    <label for="update_dokumen" class="col-sm-2 col-form-label">Update Dokumen *</label>
                    <div class="col-sm-10">
                        <input type="date" class="form-control" id="update_dokumen" name="update_dokumen" required value="<?= $row['update_dokumen']; ?>">
                    </div>
                </div>

                <!-- Bagian NRP / NIP -->
                <div class="row mb-3">
                    <label for="nrp_nip" class="col-sm-2 col-form-label">NRP / NIP *</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="nrp_nip" name="nrp_nip" readonly required value="<?= $row['nrp_nip']; ?>">
                    <div class="invalid-feedback">
                            Harap isi NRP / NIP.
                        </div>
                    </div>
                </div>

                <!-- Bagian Nama -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">
                <div class="row mb-3">
                    <label for="nama" class="col-sm-2 col-form-label">Nama *</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="nama" name="nama" required value="<?= $row['nama']; ?>">
                    <div class="invalid-feedback">
                        Harap isi Nama.
                        </div>
                    </div>
                </div>

                <!-- Bagian No KTP-->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">
                <div class="row mb-3">
                    <label for="no_ktp" class="col-sm-2 col-form-label">No KTP *</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="no_ktp" name="no_ktp" required value="<?= $row['no_ktp']; ?>">
                    <div class="invalid-feedback">
                        Harap isi No KTP.
                        </div>
                    </div>
                </div>

                <!-- Bagian No BPJS -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">
                <div class="row mb-3">
                    <label for="no_bpjs" class="col-sm-2 col-form-label">No BPJS *</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="no_bpjs" name="no_bpjs" required value="<?= $row['no_bpjs']; ?>">
                    <div class="invalid-feedback">
                        Harap isi No BPJS.
                        </div>
                    </div>
                </div>

                <!-- Bagian No NPWP -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">
                <div class="row mb-3">
                    <label for="no_npwp" class="col-sm-2 col-form-label">No NPWP *</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="no_npwp" name="no_npwp" required value="<?= $row['no_npwp']; ?>">
                    <div class="invalid-feedback">
                        Harap isi No NPWP.
                        </div>
                    </div>
                </div>

                <!-- Bagian Pangkat / Golongan -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">
                <div class="row mb-3">
                    <label for="pangkat_golongan" class="col-sm-2 col-form-label">Pangkat / Golongan *</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="pangkat_golongan" name="pangkat_golongan" required value="<?= $row['pangkat_golongan']; ?>">
                    <div class="invalid-feedback">
                        Harap isi Pangkat / Golongan.
                        </div>
                    </div>
                </div>

                <!-- Bagian kesatuan -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">
                <div class="row mb-3">
                    <label for="kesatuan" class="col-sm-2 col-form-label">Kesatuan *</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="kesatuan" name="kesatuan" required value="<?= $row['kesatuan']; ?>">
                    <div class="invalid-feedback">
                        Harap isi Kesatuan.
                        </div>
                    </div>
                </div>

                <!-- Bagian Tempat Lahir-->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">
                <div class="row mb-3">
                    <label for="tempat_lahir" class="col-sm-2 col-form-label">Tempat Lahir *</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir" required value="<?= $row['tempat_lahir']; ?>">
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
                            <option value="Laki-laki" <?php if($row['jenis_kelamin'] == 'Laki-laki') echo 'selected'; ?>>Laki-laki</option>
                            <option value="Perempuan" <?php if($row['jenis_kelamin'] == 'Perempuan') echo 'selected'; ?>>Perempuan</option>
                        </select>
                    <div class="invalid-feedback">
                        Harap isi Jenis Kelamin.
                        </div>
                    </div>
                </div>

                <!-- Bagian Agama -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">
                <div class="row mb-3">
                    <label for="agama" class="col-sm-2 col-form-label">Agama *</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="agama" name="agama" required value="<?= $row['agama']; ?>">
                    <div class="invalid-feedback">
                        Harap isi Agama.
                        </div>
                    </div>
                </div>

                <!-- Bagian Alamat Rumah -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">
                <div class="row mb-3">
                    <label for="alamat_rumah" class="col-sm-2 col-form-label">Alamat Rumah *</label>
                    <div class="col-sm-10">
                        <textarea id="alamat_rumah" name="alamat_rumah" class="form-control" rows="5" cols="30" required><?= $row['alamat_rumah']; ?></textarea>
                    <div class="invalid-feedback">
                        Harap isi Alamat Rumah.
                        </div>
                    </div>
                </div>  

                <!-- Bagian Data Keluarga -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">
                <div class="row mb-3">
                    <label for="data_keluarga" class="col-sm-2 col-form-label">Data Keluarga *</label>
                    <div class="col-sm-10">
                        <textarea id="data_keluarga" name="data_keluarga" class="form-control" rows="5" cols="30" required><?= $row['data_keluarga']; ?></textarea>
                    <div class="invalid-feedback">
                        Harap isi Data Keluarga.
                        </div>
                    </div>
                </div>  

                <!-- Bagian Hasil Urikes -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">
                <div class="row mb-3">
                    <label for="hasil_urikes" class="col-sm-2 col-form-label">Hasil Urikes Terakhir *</label>
                    <div class="col-sm-10">
                        <textarea id="hasil_urikes" name="hasil_urikes" class="form-control" rows="5" cols="30" required><?= $row['hasil_urikes']; ?></textarea>
                    <div class="invalid-feedback">
                        Harap isi Hasil Urikes.
                        </div>
                    </div>
                </div>  

                <!-- Bagian Hasil Samapta -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">
                <div class="row mb-3">
                    <label for="hasil_samapta" class="col-sm-2 col-form-label">Hasil Samapta Terakhir *</label>
                    <div class="col-sm-10">
                        <textarea id="hasil_samapta" name="hasil_samapta" class="form-control" rows="5" cols="30" required><?= $row['hasil_samapta']; ?></textarea>
                    <div class="invalid-feedback">
                        Harap isi Hasil Samapta.
                        </div>
                    </div>
                </div>  

                <!-- Bagian TMT Pangkat Pertama -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">
                <div class="row mb-3">
                    <label for="tmt_pangkat_pertama" class="col-sm-2 col-form-label">TMT Tingkat Pertama *</label>
                    <div class="col-sm-10">
                        <input type="date" class="form-control" id="tmt_pangkat_pertama" name="tmt_pangkat_pertama" required value="<?= $row['tmt_pangkat_pertama']; ?>">
                        <!-- textarea id="tmt_pangkat_pertama" name="tmt_pangkat_pertama" class="form-control" rows="5" cols="30" required><?= $row['tmt_pangkat_pertama']; ?></textarea -->
                    <div class="invalid-feedback">
                        Harap isi TMT Pangkat Pertama.
                        </div>
                    </div>
                </div>  

                <!-- Bagian TMT Pangkat Kedua -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">
                <div class="row mb-3">
                    <label for="tmt_pangkat_kedua" class="col-sm-2 col-form-label">TMT Tingkat Kedua *</label>
                    <div class="col-sm-10">
                        <input type="date" class="form-control" id="tmt_pangkat_kedua" name="tmt_pangkat_kedua" required value="<?= $row['tmt_pangkat_kedua']; ?>">
                        <!-- textarea id="tmt_pangkat_kedua" name="tmt_pangkat_kedua" class="form-control" rows="5" cols="30" required><?= $row['tmt_pangkat_kedua']; ?></textarea -->
                    <div class="invalid-feedback">
                        Harap isi TMT Pangkat Kedua.
                        </div>
                    </div>
                </div>  

                <!-- Bagian TMT Masuk Satuan -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">
                <div class="row mb-3">
                    <label for="tmt_masuk_satuan" class="col-sm-2 col-form-label">TMT Masuk Satuan *</label>
                    <div class="col-sm-10">
                        <input type="date" class="form-control" id="tmt_masuk_satuan" name="tmt_masuk_satuan" required value="<?= $row['tmt_masuk_satuan']; ?>">
                        <!-- textarea id="tmt_masuk_satuan" name="tmt_masuk_satuan" class="form-control" rows="5" cols="30" required><?= $row['tmt_masuk_satuan']; ?></textarea -->
                    <div class="invalid-feedback">
                        Harap isi TMT Masuk Satuan.
                        </div>
                    </div>
                </div>  

                <!-- Bagian Pendidikan Terakhir -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">
                <div class="row mb-3">
                    <label for="pendidikan_terakhir" class="col-sm-2 col-form-label">Pendidikan Terakhir *</label>
                    <div class="col-sm-10">
                        <textarea id="pendidikan_terakhir" name="pendidikan_terakhir" class="form-control" rows="5" cols="30" required><?= $row['pendidikan_terakhir']; ?></textarea>
                    <div class="invalid-feedback">
                        Harap isi Pendidikan Terakhir.
                        </div>
                    </div>
                </div>   

                <!-- Bagian Riwayat Jabatan -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">
                <div class="row mb-3">
                    <label for="riwayat_jabatan" class="col-sm-2 col-form-label">Riwayat Jabatan *</label>
                    <div class="col-sm-10">
                        <textarea id="riwayat_jabatan" name="riwayat_jabatan" class="form-control" rows="5" cols="30" required><?= $row['riwayat_jabatan']; ?></textarea>
                    <div class="invalid-feedback">
                        Harap isi Riwayat Pekerjaan.
                        </div>
                    </div>
                </div>   
                    
                <!-- Bagian Tanda Kehormatan -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">
                <div class="row mb-3">
                    <label for="tanda_kehormatan" class="col-sm-2 col-form-label">Tanda Kehormatan *</label>
                    <div class="col-sm-10">
                        <textarea id="tanda_kehormatan" name="tanda_kehormatan" class="form-control" rows="5" cols="30" required><?= $row['tanda_kehormatan']; ?></textarea>
                    <div class="invalid-feedback">
                        Harap isi Tanda Kehormatan.
                        </div>
                    </div>
                </div>   

                <!-- Bagian Pendidikan Umum -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">
                <div class="row mb-3">
                    <label for="pendidikan_umum" class="col-sm-2 col-form-label">Pendidikan Umum *</label>
                    <div class="col-sm-10">
                        <textarea id="pendidikan_umum" name="pendidikan_umum" class="form-control" rows="5" cols="30" required><?= $row['pendidikan_umum']; ?></textarea>
                    <div class="invalid-feedback">
                        Harap isi Pendidikan Umum.
                        </div>
                    </div>
                </div>   

                <!-- Bagian Pendidikan Militer -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">
                <div class="row mb-3">
                    <label for="pendidikan_militer" class="col-sm-2 col-form-label">Pendidikan Militer *</label>
                    <div class="col-sm-10">
                        <textarea id="pendidikan_militer" name="pendidikan_militer" class="form-control" rows="5" cols="30" required><?= $row['pendidikan_militer']; ?></textarea>
                    <div class="invalid-feedback">
                        Harap isi Pendidikan Militer.
                        </div>
                    </div>
                </div>   

                <!-- Bagian Pelatihan Khusus -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">
                <div class="row mb-3">
                    <label for="pelatihan_khusus" class="col-sm-2 col-form-label">Pelatihan Khusus *</label>
                    <div class="col-sm-10">
                        <textarea id="pelatihan_khusus" name="pelatihan_khusus" class="form-control" rows="5" cols="30" required><?= $row['pelatihan_khusus']; ?></textarea>
                    <div class="invalid-feedback">
                        Harap isi Pelatihan Khusus.
                        </div>
                    </div>
                </div>

                <!-- Bagian Label Arsip -->
                <div class="row mb-3">
                        <label for="label_arsip" class="col-sm-2 col-form-label">Label Arsip *</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="label_arsip" value="<?= isset($row['label_arsip']) ? htmlspecialchars($row['label_arsip']) : ''; ?>" required>
                            <div class="invalid-feedback">
                                Harap isi Label Arsip.
                            </div>
                        </div>
                    </div>

                    <!-- Bagian Rak Arsip -->
                    <div class="row mb-3">
                        <label for="rak_arsip" class="col-sm-2 col-form-label">Rak Arsip *</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="rak_arsip" name="rak_arsip" value="<?= isset($row['rak_arsip']) ? htmlspecialchars($row['rak_arsip']) : ''; ?>" required>
                            <small class="form-text" style="color: red;"> * Contoh Pengetikan Rak Arsip: A-JAN-001</small>
                            <small class="form-text" style="color: red;"> <br> A : Kode Laci <br> JAN : Kode Bulan Dokumen <br> 001 : Urutan Dokumen</small>
                            <div class="invalid-feedback">
                                Harap isi Rak Arsip dengan format yang benar (contoh: AJAN001).
                            </div>
                        </div>
                    </div>

                <!-- Bagian Keterangan -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">
                <div class="row mb-3">
                    <label for="keterangan" class="col-sm-2 col-form-label">Keterangan</label>
                    <div class="col-sm-10">
                        <textarea id="keterangan" name="keterangan" class="form-control" rows="5" cols="30" ><?= $row['keterangan']; ?></textarea>
                    <div class="invalid-feedback">
                        Harap isi Keterangan.
                        </div>
                    </div>
                </div>   

                    <!-- Bagian File -->
                <!-- Contoh menggunakan Font Awesome -->
                <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">

                <div class="row mb-3">
                    <label for="file" class="col-sm-2 col-form-label">File</label>
                    <div class="col-sm-10">
                        <?php if (!empty($row['file'])) : ?>
                            <p><a href="dokumen_personel/uploads/<?php echo $row['file']; ?>" target="_blank"><?php echo $row['file']; ?></a>
                                <span class="file-remove-icon" data-file-name="<?php echo $row['file']; ?>"><i class="fas fa-times-circle"></i></span></p>
                            <input type="hidden" name="file_name" value="<?php echo $row['file']; ?>">
                            <input type="file" class="form-control" name="new_file" id="new_file">
                        <?php else : ?>
                            <input type="file" class="form-control" name="file" id="file">
                            <br>
                            <small>* Unggah foto dan dokumen dalam satu file .zip / .rar (dokumen data personel, SK, dll) </small>
                            <br>
                            <small>* Biarkan kosong jika tidak ingin mengunggah file</small>
                        <?php endif; ?>
                        <!-- Tambahkan input hidden untuk menandai file yang ingin dihapus -->
                        <?php if (!empty($row['file'])) : ?>
                            <input type="hidden" name="remove_file" id="remove_file" value="0">
                        <?php endif; ?>
                    </div>
                </div>

                <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        // Tambahkan event listener untuk setiap ikon "Remove"
                        var removeIcons = document.querySelectorAll(".file-remove-icon");
                        removeIcons.forEach(function(icon) {
                            icon.addEventListener("click", function() {
                                var fileName = this.getAttribute("data-file-name");
                                removeFile(fileName);
                            });
                        });

                        function removeFile(fileName) {
                            if (confirm('Apakah Anda yakin ingin menghapus file ini?')) {
                                // Tandai bahwa file ingin dihapus dengan mengubah nilai pada input hidden
                                document.getElementById("remove_file").value = "1";

                                // Sembunyikan tautan dan ikon "Remove"
                                var fileLink = document.querySelector('a[href="dokumen_personel/uploads/' + fileName + '"]');
                                var removeIcon = document.querySelector('.file-remove-icon[data-file-name="' + fileName + '"]');

                                fileLink.parentElement.style.display = "none";
                                removeIcon.style.display = "none";
                            }
                        }
                    });
                </script>

                <!-- Bagian Button -->
                <div class="row mb-3">
                    <div class="col-sm-12 justify-content-end d-flex">
                        <button type="submit" name="submit" class="btn btn-primary">Edit Dokumen</button>
                    </div>
                </div>
                </form><!-- End General Form Elements -->
            </div>
        </div>
</section>

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
</main><!-- End #main -->