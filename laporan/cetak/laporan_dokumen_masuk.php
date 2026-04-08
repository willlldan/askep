<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Dokumen Masuk</title>
    <style>
        table,
        td,
        th {
            border: 1px solid;
            padding: 8px;
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

        <h2 class="text-center my-3" style="border-top: 2px solid black;">Laporan Dokumen Masuk</h2>
        <table>
            <thead>
                <tr>
                    <th class="text-center">No</th>
                    <th class="text-center">No Dokumen</th>
                    <th class="text-center">Status Dokumen</th>
                    <th class="text-center">Status Tindakan</th>
                    <th class="text-center">Tanggal Masuk</th>
                    <th class="text-center">Tanggal Terima</th>
                    <th class="text-center">Asal Dokumen</th>
                    <th class="text-center">Perihal</th>
                    <th class="text-center">Label Arsip</th>
                    <th class="text-center">Rak Arsip</th>
                    <th class="text-center">Tanggal Pinjam</th>
                    <th class="text-center">Peminjaman</th>
                    <th class="text-center">Tanggal Kembali</th>
                    <th class="text-center">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                <?php
                require_once "../../koneksi.php";
                $dari = $_POST['dari'];
                $sampai = $_POST['sampai'];
                $no = 1;
                $result = $mysqli->query("
                    SELECT 
                        * 
                    FROM 
                        tbl_dok_masuk 
                    WHERE 
                        tgl_terima_dok >= '$dari' 
                        AND 
                        tgl_terima_dok <= '$sampai' 
                    ORDER BY no_dokumen DESC");

                while ($row = $result->fetch_assoc()) :
                ?>
                    <tr>
                        <td class="text-center"><?= $no++; ?></td>
                        <td class="text-center"><?= $row['no_dokumen']; ?></td>
                        <td class="text-center"><?= $row['status_dokumen']; ?></td>
                        <td class="text-center"><?= $row['status_tindakan']; ?></td>
                        <td class="text-center"><?= $row['tgl_masuk_dok']; ?></td>
                        <td class="text-center"><?= $row['tgl_terima_dok']; ?></td>
                        <td class="text-center"><?= $row['asal_dokumen']; ?></td>
                        <td class="text-center"><?= $row['perihal']; ?></td>
                        <td class="text-center"><?= $row['label_arsip']; ?></td>
                        <td class="text-center"><?= $row['rak_arsip']; ?></td>
                        <td class="text-center"><?= $row['tgl_pinjam']; ?></td>
                        <td class="text-center"><?= $row['peminjaman']; ?></td>
                        <td class="text-center"><?= $row['tgl_kembali']; ?></td>
                        <td class="text-center"><?= nl2br(htmlspecialchars($row['keterangan'])) ?></td>
                    </tr>
                <?php endwhile; ?>
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
