<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Arsip Dokumen</title>
    <style>
        table,
        th,
        td {
            border: 1px solid black;
            border-collapse: collapse;
            padding: 8px;
        }

        table {
            width: 100%;
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

<body onload="window.print()">
    <div class="container">
        <?php include_once "header.php"; ?>

        <h2 class="text-center my-3" style="border-top: 2px solid black;">Laporan Label Arsip Dokumen</h2>
        <table>
    <thead>
        <tr>
            <th class="text-center">No</th>
            <th>Jenis Dokumen</th>
            <th>Label Arsip Yang Telah Digunakan</th>
        </tr>
    </thead>
    <tbody>
    <?php
    $dari = $_POST['dari'];
    $sampai = $_POST['sampai'];
    $no = 1;
    require_once "../../koneksi.php";

    // Definisi fungsi formatNoUrut
    function formatNoUrut($noUrut) {
        return ' ' . str_pad($noUrut, 3, '0', STR_PAD_LEFT);
    }

    $result = $mysqli->query("
        SELECT * 
        FROM tbl_label_arsip 
        WHERE 
        tanggal_dokumen >= '$dari' 
        AND 
        tanggal_dokumen <= '$sampai' 
        ORDER BY jenis_dokumen");

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            ?>
            <tr>
                <td class="text-center"><?= $no++; ?></td>
                <td><?= $row['jenis_dokumen']; ?></td>
                <td class="align-middle text-center">
                    <?php
                    // Ambil data form input label arsip dan tanggal label arsip dari database
                    $labelArsipForm = $row['label_arsip'];
                    $tanggalDokumenForm = $row['tanggal_dokumen'];
                    $noUrutDokumenForm = $row['no_urut_dokumen'];

                    // Tampilkan gabungan label arsip, tanggal label arsip, dan nomor urut
                    echo $labelArsipForm . ' ' . date('d-m-Y', strtotime($tanggalDokumenForm)) . formatNoUrut($noUrutDokumenForm);
                    ?>
                </td>
            </tr>
            <?php
        }
    }
    $result->free_result();
    ?>
    </tbody>
</table>


        <?php include_once "footer.php"; ?>
    </div>
</body>

</html>
