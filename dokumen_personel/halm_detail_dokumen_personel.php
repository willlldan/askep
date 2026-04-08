<?php
if (isset($_GET['id_dokumen_personel'])) {
    require_once "koneksi.php";
    require_once "utils.php";

    $id_dokumen_personel = $_GET['id_dokumen_personel'];
    $sql = "SELECT * FROM tbl_dok_personel WHERE id_dokumen_personel = '$id_dokumen_personel'";
    $result = $mysqli->query($sql);
    $row = $result->fetch_assoc();
}
?>

<style>
    .foto {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100%; /* Opsi ini dapat membantu jika ingin mengisi seluruh tinggi kolom */
    }

    img#thumbnail{
    max-width:180px;
    }
</style>
<main id="main" class="main">
    <div class="pagetitle">
        <h1>Detail Dokumen</h1>
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
                <div class="row mb-3">
                    <div class="col-sm-12 foto">
                        <img id="thumbnail" src="dokumen_personel/photos/<?=$row['foto']?>" alt="your image" />
                    </div>
                </div>

                <!-- Bagian Update Dokumen -->
                <div class="row mb-3">
                    <label for="update_dokumen" class="col-sm-2 col-form-label">Update Dokumen *</label>
                    <div class="col-sm-10">
                        <input type="date" class="form-control" id="update_dokumen" name="update_dokumen" required readonly value="<?= $row['update_dokumen']; ?>">
                    </div>
                </div>

                <!-- Bagian NRP / NIP -->    
                <div class="row mb-3">
                    <label for="nrp_nip" class="col-sm-2 col-form-label">NRP / NIP *</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="nrp_nip" name="nrp_nip" required readonly value="<?= $row['nrp_nip']; ?>">
                    </div>
                </div>

                <!-- Bagian Nama -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">
                <div class="row mb-3">
                    <label for="nama" class="col-sm-2 col-form-label">Nama *</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="nama" name="nama" required readonly value="<?= $row['nama']; ?>"> 
                    </div>
                </div>

                <!-- Bagian No KTP -->
                <div class="row mb-3">
                    <label for="no_ktp" class="col-sm-2 col-form-label">No KTP *</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="no_ktp" name="no_ktp" required readonly value="<?= $row['no_ktp']; ?>">
                    </div>
                </div>

                 <!-- Bagian No BPJS -->
                 <div class="row mb-3">
                    <label for="no_bpjs" class="col-sm-2 col-form-label">No BPJS *</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="no_bpjs" name="no_bpjs" required readonly value="<?= $row['no_bpjs']; ?>">
                    </div>
                </div>

                 <!-- Bagian No NPWP -->
                 <div class="row mb-3">
                    <label for="no_npwp" class="col-sm-2 col-form-label">No NPWP *</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="no_npwp" name="no_npwp" required readonly value="<?= $row['no_npwp']; ?>">
                    </div>
                </div>

                <!-- Bagian Pangkat / Golongan -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">
                <div class="row mb-3">
                    <label for="pangkat_golongan" class="col-sm-2 col-form-label">Pangkat / Golongan *</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="pangkat_golongan" name="pangkat_golongan" required readonly value="<?= $row['pangkat_golongan']; ?>">
                    </div>
                </div>

                <!-- Bagian Kesatuan -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">
                <div class="row mb-3">
                    <label for="kesatuan" class="col-sm-2 col-form-label">Kesatuan *</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="kesatuan" name="kesatuan" required readonly value="<?= $row['kesatuan']; ?>">
                    </div>
                </div>

                <!-- Bagian Tempat Lahir -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">
                <div class="row mb-3">
                    <label for="tempat_lahir" class="col-sm-2 col-form-label">Tempat Lahir *</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir" required readonly value="<?= $row['tempat_lahir']; ?>">
                    </div>
                </div>

                <!-- Bagian Tanggal Lahir -->
                <div class="row mb-3">
                    <label for="tgl_lahir" class="col-sm-2 col-form-label">Tanggal Lahir *</label>
                        <div class="col-sm-10">
                            <input type="date" class="form-control" id="tgl_lahir" name="tgl_lahir" required readonly value="<?= $row['tgl_lahir']; ?>">
                    </div>
                </div>

                <!-- Bagian Jenis Kelamin -->
                <div class="row mb-3">
                    <label for="jenis_kelamin" class="col-sm-2 col-form-label">Jenis Kelamin *</label>
                    <div class="col-sm-10">
                        <select class="form-select" name="jenis_kelamin" required disabled>
                            <option value="Laki-laki" <?= ($row['jenis_kelamin'] == 'Laki-laki') ? 'selected' : ''; ?>>Laki-laki</option>
                            <option value="Perempuan" <?= ($row['jenis_kelamin'] == 'Perempuan') ? 'selected' : ''; ?>>Perempuan</option>
                        </select>
                    </div>
                </div>

                <!-- Bagian Agama -->
                <div class="row mb-3">
                    <label for="agama" class="col-sm-2 col-form-label">Agama *</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="agama" name="agama" required readonly value="<?= $row['agama']; ?>">
                    </div>
                </div>

                <!-- Bagian Alamat Rumah -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">
                <div class="row mb-3">
                    <label for="alamat_rumah" class="col-sm-2 col-form-label">Alamat Rumah *</label>
                    <div class="col-sm-10">
                        <textarea id="alamat_rumah" name="alamat_rumah" class="form-control" rows="5" cols="30" readonly><?= $row['alamat_rumah']; ?></textarea>
                    </div>
                </div>

                <!-- Bagian Data Keluarga -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">
                <div class="row mb-3">
                    <label for="data_keluarga" class="col-sm-2 col-form-label">Data Keluarga *</label>
                    <div class="col-sm-10">
                        <textarea id="data_keluarga" name="data_keluarga" class="form-control" rows="5" cols="30" readonly><?= $row['data_keluarga']; ?></textarea>
                    </div>
                </div>

                <!-- Bagian Hasil Urikes -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">
                <div class="row mb-3">
                    <label for="hasil_urikes" class="col-sm-2 col-form-label">Hasil Urikes Terakhir *</label>
                    <div class="col-sm-10">
                        <textarea id="hasil_urikes" name="hasil_urikes" class="form-control" rows="5" cols="30" readonly><?= $row['hasil_urikes']; ?></textarea>
                    </div>
                </div>

                <!-- Bagian Hasil Samapta -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">
                <div class="row mb-3">
                    <label for="hasil_samapta" class="col-sm-2 col-form-label">Hasil Samapta Terakhir *</label>
                    <div class="col-sm-10">
                        <textarea id="hasil_samapta" name="hasil_samapta" class="form-control" rows="5" cols="30" readonly><?= $row['hasil_samapta']; ?></textarea>
                    </div>
                </div>

                <!-- Bagian TMT Pangkat Pertama -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">
                <div class="row mb-3">
                    <label for="tmt_pangkat_pertama" class="col-sm-2 col-form-label">TMT Tingkat Pertama *</label>
                    <div class="col-sm-10">
                        <input type="date" class="form-control" id="tmt_pangkat_pertama" name="tmt_pangkat_pertama" required readonly value="<?= $row['tmt_pangkat_pertama']; ?>">
                        <!-- textarea id="tmt_pangkat_pertama" name="tmt_pangkat_pertama" class="form-control" rows="5" cols="30" readonly><?= $row['tmt_pangkat_pertama']; ?></textarea -->
                    </div>
                </div>

                <!-- Bagian TMT Pangkat Kedua -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">
                <div class="row mb-3">
                    <label for="tmt_pangkat_kedua" class="col-sm-2 col-form-label">TMT Tingkat Kedua *</label>
                    <div class="col-sm-10">
                        <input type="date" class="form-control" id="tmt_pangkat_kedua" name="tmt_pangkat_kedua" required readonly value="<?= $row['tmt_pangkat_kedua']; ?>">
                        <!-- textarea id="tmt_pangkat_kedua" name="tmt_pangkat_kedua" class="form-control" rows="5" cols="30" readonly><?= $row['tmt_pangkat_kedua']; ?></textarea -->
                    </div>
                </div>

                <!-- Bagian TMT Masuk Satuan -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">
                <div class="row mb-3">
                    <label for="tmt_masuk_satuan" class="col-sm-2 col-form-label">TMT Masuk Satuan *</label>
                    <div class="col-sm-10">
                        <input type="date" class="form-control" id="tmt_masuk_satuan" name="tmt_masuk_satuan" required readonly value="<?= $row['tmt_masuk_satuan']; ?>">
                        <!-- textarea id="tmt_masuk_satuan" name="tmt_masuk_satuan" class="form-control" rows="5" cols="30" readonly><?= $row['tmt_masuk_satuan']; ?></textarea -->
                    </div>
                </div>

                <!-- Bagian Pendidikan Terakhir -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">
                <div class="row mb-3">
                    <label for="pendidikan_terakhir" class="col-sm-2 col-form-label">Pendidikan Terakhir *</label>
                    <div class="col-sm-10">
                        <textarea id="pendidikan_terakhir" name="pendidikan_terakhir" class="form-control" rows="5" cols="30" readonly><?= $row['pendidikan_terakhir']; ?></textarea>
                    </div>
                </div>

                <!-- Bagian Riwayat Jabatan -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">
                <div class="row mb-3">
                    <label for="riwayat_jabatan" class="col-sm-2 col-form-label">Riwayat Pekerjaan *</label>
                    <div class="col-sm-10">
                        <textarea id="riwayat_jabatan" name="riwayat_jabatan" class="form-control" rows="5" cols="30" readonly><?= $row['riwayat_jabatan']; ?></textarea>
                    </div>
                </div>

                <!-- Bagian Tanda Kehormatan -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">
                <div class="row mb-3">
                    <label for="tanda_kehormatan" class="col-sm-2 col-form-label">Tanda Kehormatan *</label>
                    <div class="col-sm-10">
                        <textarea id="tanda_kehormatan" name="tanda_kehormatan" class="form-control" rows="5" cols="30" readonly><?= $row['tanda_kehormatan']; ?></textarea>
                    </div>
                </div>    
                    
                <!-- Bagian Pendidikan Umum -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">
                <div class="row mb-3">
                    <label for="pendidikan_umum" class="col-sm-2 col-form-label">Pendidikan Umum *</label>
                    <div class="col-sm-10">
                        <textarea id="pendidikan_umum" name="pendidikan_umum" class="form-control" rows="5" cols="30" readonly><?= $row['pendidikan_umum']; ?></textarea>
                    </div>
                </div>

                <!-- Bagian Pendidikan Militer -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">
                <div class="row mb-3">
                    <label for="pendidikan_militer" class="col-sm-2 col-form-label">Pendidikan Militer *</label>
                    <div class="col-sm-10">
                        <textarea id="pendidikan_militer" name="pendidikan_militer" class="form-control" rows="5" cols="30" readonly><?= $row['pendidikan_militer']; ?></textarea>
                    </div>
                </div>

                <!-- Bagian Pelatihan Khusus -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">
                <div class="row mb-3">
                    <label for="pelatihan_khusus" class="col-sm-2 col-form-label">Pelatihan Khusus *</label>
                    <div class="col-sm-10">
                        <textarea id="pelatihan_khusus" name="pelatihan_khusus" class="form-control" rows="5" cols="30" readonly><?= $row['pelatihan_khusus']; ?></textarea>
                    </div>
                </div>

                 <!-- Bagian Label Arsip -->
                 <div class="row mb-3">
                        <label for="label_arsip" class="col-sm-2 col-form-label">Label Arsip *</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="label_arsip" value="<?= isset($row['label_arsip']) ? htmlspecialchars($row['label_arsip']) : ''; ?>" readonly>
                            <div class="invalid-feedback">
                                Harap isi Label Arsip.
                            </div>
                        </div>
                    </div>

                    <!-- Bagian Rak Arsip -->
                    <div class="row mb-3">
                        <label for="rak_arsip" class="col-sm-2 col-form-label">Rak Arsip *</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="rak_arsip" name="rak_arsip" value="<?= isset($row['rak_arsip']) ? htmlspecialchars($row['rak_arsip']) : ''; ?>" readonly>
                            <small class="form-text" style="color: red;"> * Contoh Pengetikan Rak Arsip: A-JAN-001</small>
                            <small class="form-text" style="color: red;"> <br> A : Kode Laci <br> JAN : Kode Bulan Dokumen <br> 001 : Urutan Dokumen</small>
                            <div class="invalid-feedback">
                                Harap isi Rak Arsip dengan format yang benar (contoh: AJAN001).
                            </div>
                        </div>
                    </div>

                    <!-- Bagian Tahun 
                    <div class="row mb-3">
                        <label for="tahun" class="col-sm-2 col-form-label">Tahun *</label>
                        <div class="col-sm-10">
                        <input type="text" class="form-control" id="tahun" name="tahun" required readonly value="<?= $row['tahun']; ?>">
                        </div>
                    </div> -->
                    
                <!-- Bagian keterangan -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">
                <div class="row mb-3">
                    <label for="keterangan" class="col-sm-2 col-form-label">Keterangan</label>
                    <div class="col-sm-10">
                        <textarea id="keterangan" name="keterangan" class="form-control" rows="5" cols="30" readonly><?= $row['keterangan']; ?></textarea>
                        </div>
                    </div>
                </form>

               <!-- Bagian File -->
               <div class="row mb-3">
                    <label for="file" class="col-sm-2 col-form-label">File</label>
                    <div class="col-sm-10">
                        <?php if (!empty($row['file'])) : ?>
                            <p><a href="dokumen_personel/uploads/<?php echo $row['file']; ?>" target="_blank"><?php echo $row['file']; ?></a></p>
                            <input type="hidden" name="file_name" value="<?php echo $row['file']; ?>">
                        <?php else : ?>
                            <?php if (isset($isEditable) && $isEditable) : ?>
                                <input type="file" class="form-control" name="file" id="file">
                            <?php else : ?>
                                <input type="text" class="form-control" value="Tidak ada file" readonly>
                            <?php endif; ?>
                            <?php endif; ?>
                    </div>
                </div>

                     <!-- Bagian Button Dokumen Personel -->
                     <div class="row mb-3">
                        <div class="col-sm-12 justify-content-end d-flex">
                            <form action="dokumen_personel/halm_print_dokumen_personel.php" method="POST" target="_blank">
                                <!-- Tambahkan atribut action, method, dan tambahkan input hidden untuk mengirim id_dokumen_personel -->
                                <input type="hidden" name="id_dokumen_personel" value="<?= $row['id_dokumen_personel'] ?>">
                                <button type="submit" class="btn btn-primary">Cetak Dokumen Personel</button>
                            </form>
                        </div>
                    </div><!-- End General Form Elements -->
            </div>
        </div>
</section>
</main><!-- End #main -->        
                    