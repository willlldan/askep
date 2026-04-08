<?php
if (isset($_POST['submit'])) {
    require_once "koneksi.php";

    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM tbl_user WHERE username ='$username' AND password='$password'";
    if ($user = $mysqli->query($sql)) {
        $user = $user->fetch_assoc();
        if (!is_null($user)) {
            $_SESSION['nama'] = $user['nama'];
            $_SESSION['id_user'] = $user['id_user'];
            $_SESSION['level'] = $user['level'];
            header('Location: index.php');
        } else echo "<script>alert('Username atau Password Salah!');</script>";
    } else echo "Error: " . $sql . "<br>" . $mysqli->error;
}
?>

<main>
    <div class="container">
        <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-xl-5 col-xxl-4 col-lg-5 col-md-7 d-flex flex-column align-items-center justify-content-center">

                        <div class="d-flex justify-content-center py-4">
                            <a href="index.html" class="logo d-flex align-items-center w-auto">
                                <!-- <img src="assets/img/diskes.png" alt=""> -->
                            </a>
                        </div><!-- End Logo -->

                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="pt-4 pb-2">
                                    <div class="d-flex">
                                        <img src="assets/img/diskes.png" width="114" alt="">
                                    <div class="d-flex flex-column">
                                        <h5 class="card-title text-center pb-0 fs-5">Sistem Informasi Pengelolaan Kearsipan</h5>
                                        <p class="text-center">Diskes Lantamal XIII Tarakan</p>
                                </div>
                            </div>
                        </div>

                        <!-- Bagian Login -->
                        <form class="row g-3 needs-validation mt-1" novalidate method="POST">
                            <!-- Bagian Username -->
                                <div class="col-12">
                                    <label for="username" class="form-label">Username</label>
                                        <input type="text" autofocus name="username" class="form-control" id="username" required>
                                    <div class="invalid-feedback">Masukkan Username!</div>
                                </div>
                            
                                <!-- Bagian Password -->
                                    <div class="col-12">
                                        <label for="password" class="form-label">Password</label>
                                            <input type="password" name="password" class="form-control" id="password" required>
                                        <div class="invalid-feedback">Masukkan Password!</div>
                                    </div>

                                <!-- Bagian Button -->
                                    <div class="col-12">
                                        <button class="btn btn-primary w-100" name="submit" type="submit">Login</button>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</main><!-- End #main -->