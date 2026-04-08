<main id="main" class="main">
    <div class="pagetitle">
        <h1>Data Peminjaman Dokumen Personel</h1>
    </div>
    <br>
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Peminjaman Dokumen Personel</h5>
                        <div class="table-wrapper">
                            <style>.table-wrapper {overflow-x: auto;} </style>
                            <table class="table datatable">
                                <thead>
                                    <tr>
                                        <th scope="col" class="text-center">No</th>
                                        <th scope="col" class="text-center">NRP / NIP</th>
                                        <th scope="col" class="text-center">Nama Peminjam</th>
                                        <th scope="col" class="text-center">Status Peminjam</th>
                                        <th scope="col" class="text-center">Tanggal Pinjam</th>
                                        <th scope="col" class="text-center">Tanggal Kembali</th>
                                        <th scope="col" class="text-center">Status Peminjaman</th>
                                        <th scope="col" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    require_once "koneksi.php";
                                    require_once "utils.php";
                                    $no = 1;
                                    $sql = "SELECT * FROM tbl_peminjaman_dok_personel ORDER BY id_peminjaman_dok_personel DESC";
                                    $result = $mysqli->query($sql);
                                    ?>
                                    <?php while ($row = $result->fetch_assoc()) : ?>
                                        <tr>
                                            <th class="align-middle text-center" scope="row"><?= $no++; ?></th>
                                            <td class="align-middle text-center"><?= $row['nrp_nip']; ?></td>
                                            <td class="align-middle text-center"><?= $row['nama_peminjam']; ?></td>
                                            <td class="align-middle text-center"><?= $row['status_peminjam']; ?></td>
                                            <td class="align-middle text-center"><?= $row['tgl_pinjam']; ?></td>
                                            <td class="align-middle text-center"><?= $row['tgl_kembali']; ?></td>
                                            <td class="align-middle text-center"><?= $row['status_peminjaman']; ?></td>
                                            <td class="d-flex justify-content-center gap-1">
                                                <a href="index.php?page=peminjam_dokumen_personel&item=detail_dok_personel&id_peminjaman_dok_personel=<?= $row['id_peminjaman_dok_personel']; ?>" class="btn btn-info btn-xs"><i class="bi bi-eye"></i></a>
                                                <a href="index.php?page=peminjam_dokumen_personel&item=edit_dok_personel&id_peminjaman_dok_personel=<?= $row['id_peminjaman_dok_personel']; ?>" class="btn btn-warning"><i class="bi bi-pencil"></i></a>
                                                <a href="index.php?page=peminjam_dokumen_personel&item=delete_dok_personel&id_peminjaman_dok_personel=<?= $row['id_peminjaman_dok_personel']; ?>" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus data ini?')"><i class="bi bi-trash"></i></a>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
