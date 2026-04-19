<?php
require_once 'utils.php';
require_once 'koneksi.php';

$id     = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$isEdit = $id > 0;
$errors = [];
$user   = ['nama' => '', 'npm' => '', 'username' => '', 'password' => '', 'level' => ''];

if ($isEdit) {
    $result = $mysqli->query("SELECT * FROM tbl_user WHERE id_user = $id LIMIT 1");
    $user   = $result->fetch_assoc();

    if (!$user) {
        echo "<div class='alert alert-danger'>User tidak ditemukan.</div>";
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama     = trim($_POST['nama'] ?? '');
    $npm      = trim($_POST['npm'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $level    = $_POST['level'] ?? '';

    if (empty($nama))     $errors[] = 'Nama wajib diisi.';
    if (empty($username)) $errors[] = 'Username wajib diisi.';
    if (empty($password)) $errors[] = 'Password wajib diisi.';
    if (empty($level))    $errors[] = 'Level wajib dipilih.';

    if (empty($errors)) {
        $usernameEsc = $mysqli->real_escape_string($username);
        $cekSql      = "SELECT id_user FROM tbl_user WHERE username = '$usernameEsc'";
        if ($isEdit) $cekSql .= " AND id_user != $id";
        $cekSql .= " LIMIT 1";
        if ($mysqli->query($cekSql)->num_rows > 0) {
            $errors[] = 'Username sudah digunakan.';
        }
    }

    if (empty($errors)) {
        $namaEsc     = $mysqli->real_escape_string($nama);
        $npmEsc      = $mysqli->real_escape_string($npm);
        $usernameEsc = $mysqli->real_escape_string($username);
        $passwordEsc = $mysqli->real_escape_string($password);
        $levelEsc    = $mysqli->real_escape_string($level);

        if ($isEdit) {
            $sql = "UPDATE tbl_user SET nama='$namaEsc', npm='$npmEsc', username='$usernameEsc', password='$passwordEsc', level='$levelEsc' WHERE id_user = $id";
            $successParam = 'edit';
        } else {
            $sql = "INSERT INTO tbl_user (nama, npm, username, password, level) VALUES ('$namaEsc', '$npmEsc', '$usernameEsc', '$passwordEsc', '$levelEsc')";
            $successParam = 'tambah';
        }

        if ($mysqli->query($sql)) {
            echo "<script>window.location.href='index.php?page=manage_user&success=$successParam';</script>";
            exit;
        } else {
            $errors[] = 'Gagal menyimpan data: ' . $mysqli->error;
        }
    }

    $user = array_merge($user, compact('nama', 'npm', 'username', 'password', 'level'));
}
?>

<main id="main" class="main">
    <div class="pagetitle">
        <h1><?= $isEdit ? 'Edit User' : 'Tambah User' ?></h1>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0"><?= $isEdit ? 'Form Edit User' : 'Form Tambah User' ?></h5>
                            <a href="index.php?page=manage_user" class="btn btn-sm btn-secondary">
                                <i class="ri-arrow-left-line me-1"></i> Kembali
                            </a>
                        </div>

                        <?php if (!empty($errors)): ?>
                            <div class="alert alert-danger mt-3">
                                <ul class="mb-0">
                                    <?php foreach ($errors as $err): ?>
                                        <li><?= htmlspecialchars($err) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <form method="POST" class="mt-3">
                            <div class="mb-3">
                                <label class="form-label">Nama <span class="text-danger">*</span></label>
                                <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($user['nama']) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Username <span class="text-danger">*</span></label>
                                <input type="text" name="username" class="form-control" maxlength="15" value="<?= htmlspecialchars($user['username']) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password <span class="text-danger">*</span></label>
                                <input type="text" name="password" class="form-control" maxlength="10" value="<?= htmlspecialchars($user['password']) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Level <span class="text-danger">*</span></label>
                                <select name="level" id="inputLevel" class="form-select" required>
                                    <option value="">-- Pilih Level --</option>
                                    <?php foreach (['Admin', 'Dosen', 'Mahasiswa'] as $lvl): ?>
                                        <option value="<?= $lvl ?>" <?= ($user['level'] === $lvl) ? 'selected' : '' ?>>
                                            <?= $lvl ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">NPM</label>
                                <input type="text" name="npm" id="inputNpm" class="form-control"
                                    value="<?= htmlspecialchars($user['npm'] ?? '') ?>"
                                    <?= ($user['level'] !== 'Mahasiswa') ? 'disabled' : '' ?>>
                                <small class="text-muted">Hanya untuk Mahasiswa.</small>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="ri-save-line me-1"></i> <?= $isEdit ? 'Update' : 'Simpan' ?>
                            </button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<script>
    const levelSelect = document.getElementById('inputLevel');
    const npmInput    = document.getElementById('inputNpm');

    levelSelect.addEventListener('change', function () {
        const isMahasiswa = this.value === 'Mahasiswa';
        npmInput.disabled = !isMahasiswa;
        if (!isMahasiswa) npmInput.value = '';
    });
</script>