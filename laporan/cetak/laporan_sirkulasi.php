<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Sirkulasi - Disposisi</title>
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

        <h2 class="text-center my-3" style="border-top: 2px solid black;">Laporan Sirkulasi -  Disposisi</h2>
        <table>
            <thead>
                <tr>
                    <th class="text-center">No</th>
                    <th class="text-center">No Agenda</th>
                    <th class="text-center">No Dokumen</th>
                    <th class="text-center">Asal Dokumen</th>
                    <th class="text-center">Perihal</th>
                    <th class="text-center">Tanggal Masuk</th>
                    <th class="text-center">Tanggal Update</th>
                    <th class="text-center">Tanggal Disposisi</th>
                    <th class="text-center">Posisi Histori</th>
                    <th class="text-center">Keterangan</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $dari = $_POST['dari'];
            $sampai = $_POST['sampai'];
            $no = 1;
            require_once "../../koneksi.php";
            
            $sql = "
                SELECT 
                    d.*,
                    h.tgl_update AS tgl_update,
                    h.tgl_disposisi AS tgl_disposisi_histori,
                    h.posisi AS posisi
                FROM tbl_disposisi d
                LEFT JOIN tbl_histori_disposisi h ON d.id_disposisi = h.id_disposisi
                WHERE 
                    d.tgl_disposisi >= '$dari' 
                    AND 
                    d.tgl_disposisi <= '$sampai' 
                ORDER BY d.id_disposisi DESC, h.tgl_update DESC
            ";
            
            $result = $mysqli->query($sql);            
            ?>

                <?php if ($result->num_rows) : ?>
                    <?php while ($row = $result->fetch_assoc()) : ?>
                        <tr>
                            <td class="text-center"><?= $no++; ?></td>
                            <td class="text-center"><?= $row['no_agenda']; ?></td>
                            <td class="text-center"><?= $row['no_dokumen']; ?></td>
                            <td class="text-center"><?= $row['asal_dokumen']; ?></td>
                            <td class="text-center"><?= $row['perihal']; ?></td>
                            <td class="text-center"><?= $row['tgl_masuk']; ?></td>
                            <td class="text-center"><?= $row['tgl_update']; ?></td>
                            <td class="text-center"><?= $row['tgl_disposisi']; ?></td>
                            <td class="text-center"><?= $row['posisi']; ?></td>
                            <td class=""><?= nl2br(htmlspecialchars($row['keterangan'])) ?></td>
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