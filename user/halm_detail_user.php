<?php

if (isset($_GET['id_user'])) {
    require_once "koneksi.php";
    require_once "utils.php";

    $sql = "SELECT * FROM tbl_user WHERE id_user=" . $_GET['id_user'];
    $result = $mysqli->query($sql);
    $row = $result->fetch_assoc();
}
?>

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Detail User</h1>
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
                <h5 class="card-title">User</h5>

                <!-- General Form Elements -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">
                    <div class="row mb-3">
                        <label for="nama" class="col-sm-2 col-form-label">Nama</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="nama" name="nama" required readonly value="<?= $row['nama']; ?>">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="username" class="col-sm-2 col-form-label">Username</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="username" name="username" required readonly value="<?= $row['username']; ?>">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="password" class="col-sm-2 col-form-label">Password</label>
                        <div class="col-sm-10">
                            <input type="password" class="form-control" id="password" name="password" required readonly value="<?= $row['password']; ?>">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="level" class="col-sm-2 col-form-label">Level</label>
                        <div class="col-sm-10">
                            <select class="form-select" name="level" required disabled>
                                <option <?= $row['level'] == 'Admin' ? 'selected' : ''; ?> value="Admin">Admin</option>
                                <option <?= $row['level'] == 'Staff-Dokter' ? 'selected' : ''; ?> value="Staff-Dokter">Staff-Dokter</option>
                            </select>
                            <div class="invalid-feedback">
                                Harap isi Level.
                            </div>
                        </div>
                    </div>
                </form><!-- End General Form Elements -->
            </div>
        </div>
    </section>

</main><!-- End #main -->