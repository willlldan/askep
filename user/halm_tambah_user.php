<?php

if (isset($_POST['submit'])) {
    require_once "koneksi.php";
    require_once "utils.php";
    
    $nama= $_POST['nama'];
    $username= $_POST['username'];
    $password= $_POST['password'];
    $level= $_POST['level'];

    $sql = "INSERT INTO tbl_user (
            nama, 
            username, 
            password, 
            level 
        ) VALUES (
            '$nama', 
            '$username',
            '$password', 
            '$level' 
        )";

    if ($mysqli->query($sql) === TRUE) echo "<script>alert('User berhasil ditambahkan.')</script>";
    else echo "Error: " . $sql . "<br>" . $mysqli->error;
}

?>
<main id="main" class="main">
    <div class="pagetitle">
        <h1>Tambah User</h1>
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
                <form class="needs-validation" novalidate action="" method="POST">
                     <!-- Bagian Nama -->
                    <div class="row mb-3">
                        <label for="nama" class="col-sm-2 col-form-label">Nama</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="nama" name="nama" required>
                            <div class="invalid-feedback">
                                Harap isi Nama.
                            </div>
                        </div>
                    </div>

                     <!-- Bagian Username -->
                    <div class="row mb-3">
                        <label for="username" class="col-sm-2 col-form-label">Username</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="username" name="username" required>
                            <div class="invalid-feedback">
                                Harap isi Username.
                            </div>
                        </div>
                    </div>

                     <!-- Bagian Password -->
                    <div class="row mb-3">
                        <label for="password" class="col-sm-2 col-form-label">Password</label>
                        <div class="col-sm-10">
                            <input type="password" class="form-control" id="password" name="password" required>
                            <div class="invalid-feedback">
                                Harap isi Password.
                            </div>
                        </div>
                    </div>

                     <!-- Bagian Level -->
                    <div class="row mb-3">
                        <label for="level" class="col-sm-2 col-form-label">Level</label>
                        <div class="col-sm-10">
                            <select class="form-select" name="level" required>
                                <option value="Admin">Admin</option>
                                <option value="Staff-Dokter">Staff-Dokter</option>
                            </select>
                            <div class="invalid-feedback">
                                Harap isi Level.
                            </div>
                        </div>
                    </div>

                     <!-- Bagian Button -->
                    <div class="row mb-3">
                        <div class="col-sm-12 justify-content-end d-flex">
                            <button type="submit" name="submit" class="btn btn-primary">Tambah User</button>
                        </div>
                    </div>

                </form><!-- End General Form Elements -->

            </div>
        </div>
    </section>

</main><!-- End #main -->