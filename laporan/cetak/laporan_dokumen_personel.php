<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Dokumen Pendukung</title>
    <style>
    table,
    td,
    th {
        border: 1px solid;
        padding: 3px; /* Mengurangi padding untuk membuat tabel lebih kompak */
        font-size: 3px; /* Mengurangi ukuran font */
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th {
        text-align: left;
    }

    .text-center {
        text-align: center;
    }

    #kop {
        display: flex;
        justify-content: center;
    }
</style>
</head>

<body>
    <div class="container">
        <?php include_once "header.php"; ?>

        <h2 class="text-center my-3" style="border-top: 2px solid black;">Laporan Dokumen Personel</h2>
        <table>
            <thead>
                <tr>
                <th class="text-center">No</th>
                    <th class="text-center">NRP / NIP</th>
                    <th class="text-center">Nama</th>
                    <th class="text-center">No KTP</th>
                    <th class="text-center">No BPJS</th>
                    <th class="text-center">No NPWP</th>
                    <th class="text-center">Pangkat / Golongan</th>
                    <th class="text-center">Kesatuan</th>
                    <th class="text-center">Tempat Lahir</th>
                    <th class="text-center">Tanggal Lahir</th>
                    <th class="text-center">Jenis Kelamin</th>
                    <th class="text-center">Agama</th>
                    <th class="text-center">Alamat Rumah</th>
                    <th class="text-center">Data Keluarga</th>
                    <th class="text-center">Hasil Urikes Terakhir</th>
                    <th class="text-center">Hasil Samapta Terakhir</th>
                    <th class="text-center">TMT Pangkat Pertama</th>
                    <th class="text-center">TMT Pangkat Kedua</th>
                    <th class="text-center">TMT Masuk Satuan</th>
                    <th class="text-center">Riwayat Jabatan</th>
                    <th class="text-center">Tanda Kehormatan</th>
                    <th class="text-center">Pendidikan Umum</th>
                    <th class="text-center">Pendidikan Militer</th>
                    <th class="text-center">Pelatihan Khusus</th>
                    <th class="text-center">Label Arsip</th>
                    <th class="text-center">Rak Arsip</th>
                    <th class="text-center">Keterangan</th>
                </tr>
            </thead>
            <tbody>
            <?php
                $dari = $_POST['dari'];
                $sampai = $_POST['sampai'];
                $no = 1;
                require_once "../../koneksi.php";
                $result = $mysqli->query("
                    SELECT 
                        * 
                    FROM 
                        tbl_dok_personel
                    ORDER BY nrp_nip DESC");
                ?>
                <?php if ($result->num_rows) : ?>
                    <?php while ($row = $result->fetch_assoc()) : ?>
                        <tr>
                            <td class="text-center"><?= $no++; ?></td>
                            <td class="text-center"><?= $row['nrp_nip']; ?></td>
                            <td class="text-center"><?= $row['nama']; ?></td>
                            <td class="text-center"><?= $row['no_ktp']; ?></td>
                            <td class="text-center"><?= $row['no_bpjs']; ?></td>
                            <td class="text-center"><?= $row['no_npwp']; ?></td>
                            <td class="text-center"><?= $row['pangkat_golongan']; ?></td>
                            <td class="text-center"><?= $row['kesatuan']; ?></td>
                            <td class="text-center"><?= $row['tempat_lahir']; ?></td>
                            <td class="text-center"><?= $row['tgl_lahir']; ?></td>
                            <td class="text-center"><?= $row['jenis_kelamin']; ?></td>
                            <td class="text-center"><?= $row['agama']; ?></td>
                            <td class="text-center"><?= $row['alamat_rumah']; ?></td>
                            <td class="text-center"><?= $row['data_keluarga']; ?></td>
                            <td class="text-center"><?= $row['hasil_urikes']; ?></td>
                            <td class="text-center"><?= $row['hasil_samapta']; ?></td>
                            <td class="text-center"><?= $row['tmt_pangkat_pertama']; ?></td>
                            <td class="text-center"><?= $row['tmt_pangkat_kedua']; ?></td>
                            <td class="text-center"><?= $row['tmt_masuk_satuan']; ?></td>
                            <td class="text-center"><?= $row['pendidikan_terakhir']; ?></td>
                            <td class="text-center"><?= $row['riwayat_jabatan']; ?></td>
                            <td class="text-center"><?= $row['tanda_kehormatan']; ?></td>
                            <td class="text-center"><?= $row['pendidikan_umum']; ?></td>
                            <td class="text-center"><?= $row['pendidikan_militer']; ?></td>
                            <td class="text-center"><?= $row['pelatihan_khusus']; ?></td>
                            <td class="text-center"><?= $row['rak_arsip']; ?></td>
                            <td class="text-center"><?= $row['label_arsip']; ?></td>
                            <td class="text-center"><?= nl2br(htmlspecialchars($row['keterangan'])) ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php endif; ?>
                <?php $result->free_result(); ?>
            </tbody>
        </table>
        <?php include_once "footer.php"; ?>
    </div>
    <script>
        window.print();
    </script>
</body>

</html>