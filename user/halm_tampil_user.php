<main id="main" class="main">

  <div class="pagetitle">
    <h1>Data User</h1>
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
            <h5 class="card-title" style="flex: 1;">User</h5>
            <!-- <p>Add lightweight datatables to your project with using the <a href="https://github.com/fiduswriter/Simple-DataTables" target="_blank">Simple DataTables</a> library. Just add <code>.datatable</code> class name to any table you wish to conver to a datatable</p> -->

            <!-- Table with stripped rows -->
            <table class="table datatable">
              <thead>
                <tr>
                  <th scope="col" class="text-center">No</th>
                  <th scope="col" class="text-center">Nama</th>
                  <th scope="col" class="text-center">Username</th>
                  <th scope="col" class="text-center">Level</th>
                  <th scope="col" class="text-center">Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php
                require_once "koneksi.php";
                require_once "utils.php";

                $no = 1;
                $sql = "SELECT * FROM tbl_user ORDER BY id_user DESC";
                $result = $mysqli->query($sql);
                ?>
                <?php while ($row = $result->fetch_assoc()) : ?>
                  <tr>
                    <th class="align-middle text-center" scope="row"><?= $no++; ?></th>
                    <td class="align-middle text-center"><?= $row['nama']; ?></td>
                    <td class="align-middle text-center"><?= $row['username']; ?></td>
                    <td class="align-middle text-center"><?= $row['level']; ?></td>
                    <td class="d-flex justify-content-center gap-1">
                      <a href="index.php?page=user&item=detail_user&id_user=<?= $row['id_user']; ?>" class="btn btn-info btn-xs"><i class="bi bi-eye"></i></a>
                      <a href="index.php?page=user&item=edit_user&id_user=<?= $row['id_user']; ?>" class="btn btn-warning"><i class="bi bi-pencil"></i></a>
                      <a href="index.php?page=user&item=delete_user&id_user=<?= $row['id_user']; ?>" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus data ini?')"><i class="bi bi-trash"></i></a>
                    </td>
                  </tr>
                <?php endwhile; ?>
              </tbody>
            </table>
            <!-- End Table with stripped rows -->

          </div>
        </div>

      </div>
    </div>
  </section>

</main><!-- End #main -->