<?php
require_once "../koneksi.php";

// Cek apakah parameter id_dokumen_personel telah diberikan melalui POST
if (isset($_POST['id_dokumen_personel'])) {
    $id_dokumen_personel = $_POST['id_dokumen_personel'];

    $stmt = $mysqli->prepare("SELECT * FROM tbl_dok_personel WHERE id_dokumen_personel = ?");
    $stmt->bind_param("i", $id_dokumen_personel);
    $stmt->execute();
    $result = $stmt->get_result();
    $foto;
} else {
    // Jika parameter tidak diberikan, alihkan kembali ke halaman sebelumnya atau berikan pesan error
    // Misalnya: header("Location: halm_detail_dokumen_personel.php"); exit;
    echo "Error: ID Dokumen Personel tidak diberikan.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Dokumen Personel</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse; /* Add this to collapse the borders */
            border-spacing: 0; /* Add this to remove spacing between cells */
        }

        table,
        td,
        th {
            border: 1px solid;
            padding: 8px;
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
</head>

<body onload="window.print()">
    <div class="container">
        <?php include_once "header.php"; ?>

        <h2 class="text-center my-3" style="border-top: 2px solid black;">Laporan Dokumen Personel</h2>

        <?php if ($result->num_rows) : ?>
            <?php $no = 1; ?>
            <?php while ($row = $result->fetch_assoc()) : ?>
                <div class="row" style="margin-bottom:20px">
                    <div class="col-sm-12 foto">
                        <img id="thumbnail" src="photos/<?=$row['foto']?>" alt="your image" />
                    </div>
                </div>
                <table>    
                    <tr>
                        <th>NRP / NIP</th>
                        <td><?= $row['nrp_nip']; ?></td>
                    </tr>
                    <tr>
                        <th>Nama</th>
                        <td><?= $row['nama']; ?></td>
                    </tr>
                    <tr>
                        <th>No KTP</th>
                        <td><?= $row['no_ktp']; ?></td>
                    </tr>
                    <tr>
                        <th>No BPJS</th>
                        <td><?= $row['no_bpjs']; ?></td>
                    </tr>
                    <tr>
                        <th>No NPWP</th>
                        <td><?= $row['no_npwp']; ?></td>
                    </tr>
                    <tr>
                        <th>Pangkat / Golongan</th>
                        <td><?= $row['pangkat_golongan']; ?></td>
                    </tr>
                    <tr>
                        <th>Kesatuan</th>
                        <td><?= $row['kesatuan']; ?></td>
                    </tr>
                    <tr>
                        <th>Tempat Lahir</th>
                        <td><?= $row['tempat_lahir']; ?></td>
                    </tr>
                    <tr>
                        <th>Tanggal Lahir</th>
                        <td><?= $row['tgl_lahir']; ?></td>
                    </tr>
                    <tr>
                        <th>Jenis Kelamin</th>
                        <td><?= $row['jenis_kelamin']; ?></td>
                    </tr>
                    <tr>
                        <th>Agama</th>
                        <td><?= $row['agama']; ?></td>
                    </tr>
                    <tr>
                        <th>Alamat Rumah</th>
                        <td><?= nl2br(htmlspecialchars($row['alamat_rumah'])) ?></td>
                    </tr>
                    <tr>
                        <th>Data Keluarga</th>
                        <td><?= nl2br(htmlspecialchars($row['data_keluarga'])) ?></td>
                    </tr>
                    <tr>
                        <th>Hasil Urikes Terakhir</th>
                        <td><?= nl2br(htmlspecialchars($row['hasil_urikes'])) ?></td>
                    </tr>
                    <tr>
                        <th>Hasil Samapta Terakhir</th>
                        <td><?= nl2br(htmlspecialchars($row['hasil_samapta'])) ?></td>
                    </tr>
                    <tr>
                        <th>TMT Pangkat Pertama</th>
                        <td><?= nl2br(htmlspecialchars($row['tmt_pangkat_pertama'])) ?></td>
                    </tr>
                    <tr>
                        <th>TMT Pangkat Kedua</th>
                        <td><?= nl2br(htmlspecialchars($row['tmt_pangkat_kedua'])) ?></td>
                    </tr>
                    <tr>
                        <th>TMT Masuk Satuan</th>
                        <td><?= nl2br(htmlspecialchars($row['tmt_masuk_satuan'])) ?></td>
                    </tr>
                    <tr>
                        <th>Pendidikan Terakhir</th>
                        <td><?= nl2br(htmlspecialchars($row['pendidikan_terakhir'])) ?></td>
                    </tr>
                    <tr>
                        <th>Riwayat Jabatan</th>
                        <td><?= nl2br(htmlspecialchars($row['riwayat_jabatan'])) ?></td>
                    </tr>
                    <tr>
                        <th>Tanda Kehormatan</th>
                        <td><?= nl2br(htmlspecialchars($row['tanda_kehormatan'])) ?></td>
                    </tr>
                    <tr>
                        <th>Pendidikan Umum</th>
                        <td><?= nl2br(htmlspecialchars($row['pendidikan_umum'])) ?></td>
                    </tr>
                    <tr>
                        <th>Pendidikan Militer</th>
                        <td><?= nl2br(htmlspecialchars($row['pendidikan_militer'])) ?></td>
                    </tr>
                    <tr>
                        <th>Pelatihan Khusus</th>
                        <td><?= nl2br(htmlspecialchars($row['pelatihan_khusus'])) ?></td>
                    </tr>
                    <tr>
                        <th>Label Arsip</th>
                        <td><?= $row['label_arsip']; ?></td>
                    </tr>
                    <tr>
                        <th>Rak Arsip</th>
                        <td><?= $row['rak_arsip']; ?></td>
                    </tr>
                    <tr>
                        <th>Keterangan</th>
                        <td><?= nl2br(htmlspecialchars($row['keterangan'])) ?></td>
                    </tr>
                </table>
                <br><br>
            <?php endwhile; ?>
        <?php else : ?>
            <p class="text-center">Data tidak ditemukan.</p>
        <?php endif; ?>

        <?php $result->free_result();
        ?>

        <?php include_once "footer.php"; ?>
    </div>
</body>

</html>