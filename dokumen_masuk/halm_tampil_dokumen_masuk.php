<main id="main" class="main">

  <div class="pagetitle">
    <h1>Data Dokumen</h1>
    <!-- <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Home</a></li>
          <li class="breadcrumb-item">Tables</li>
          <li class="breadcrumb-item active">Data</li>
        </ol>
      </nav> -->
  </div><!-- End Page Title -->
  <br>
  <section class="section">
    <div class="row">
      <div class="col-lg-12">

        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Dokumen Masuk</h5>
            <!-- <p>Add lightweight datatables to your project with using the <a href="https://github.com/fiduswriter/Simple-DataTables" target="_blank">Simple DataTables</a> library. Just add <code>.datatable</code> class name to any table you wish to conver to a datatable</p> -->

            <!-- Table with stripped rows -->
            <div class="table-wrapper">
              <style>.table-wrapper {overflow-x: auto;} </style>
              <table class="table datatable">
              <thead>
                <tr>
                  <th scope="col" class="text-center">No</th>
                  <th scope="col" class="text-center">No Dokumen</th>
                  <th scope="col" class="text-center">Status Dokumen</th>
                  <th scope="col" class="text-center">Status Tindakan</th>
                  <th scope="col" class="text-center">Tanggal Dokumen</th>
                  <th scope="col" class="text-center">Asal Dokumen</th>
                  <th scope="col" class="text-center">Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php
                require_once "koneksi.php";
                $no = 1;
                $sql = "
                    SELECT 
                      * 
                    FROM 
                      tbl_dok_masuk ORDER BY tgl_terima_dok DESC";
                $result = $mysqli->query($sql);
                ?>
                <?php while ($row = $result->fetch_assoc()) : ?>
                  <tr>
                    <th class="align-middle text-center" scope="row"><?= $no++; ?></th>
                    <td class="align-middle text-center"><?= $row['no_dokumen']; ?></td>
                    <td class="align-middle text-center"><?= $row['status_dokumen']; ?></td>
                    <td class="align-middle text-center"><?= $row['status_tindakan']; ?></td>
                    <td class="align-middle text-center"><?= $row['tgl_masuk_dok']; ?></td>
                    <td class="align-middle text-center"><?= $row['asal_dokumen']; ?></td>
                    <td class="d-flex justify-content-center gap-1">
                      <a href="index.php?page=dokumen_masuk&item=detail_dokumen_masuk&no_dokumen=<?= $row['no_dokumen']; ?>" class="btn btn-info btn-xs"><i class="bi bi-eye"></i></a>
                      <a href="index.php?page=dokumen_masuk&item=edit_dokumen_masuk&no_dokumen=<?= $row['no_dokumen']; ?>" class="btn btn-warning text-light"><i class="bi bi-pencil"></i></a>
                      <a href="index.php?page=dokumen_masuk&item=delete_dokumen_masuk&no_dokumen=<?= $row['no_dokumen']; ?>" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus data ini?')"><i class="bi bi-trash"></i></a>
                    </td>
                  </tr>
                <?php endwhile; ?>
              </tbody>
            </table>
            </div><!-- End Table with stripped rows -->

          </div>
        </div>

      </div>
    </div>
  </section>

</main><!-- End #main -->

