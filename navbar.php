<?php
  require_once "koneksi.php";
  require_once "utils.php";

  $currentUserId = (int) ($_SESSION['id_user'] ?? 0);
  $currentLevel = $_SESSION['level'] ?? '';
  $notifications = [];
  $notificationCount = 0;

  if (in_array($currentLevel, ['Dosen', 'Mahasiswa'], true) && $currentUserId > 0) {
    $notifications = getUserNotifications($currentUserId, $mysqli, 10);
    $notificationCount = countUnreadNotifications($currentUserId, $mysqli);
  }
?>

<header id="header" class="header fixed-top d-flex align-items-center">
  <div class="d-flex align-items-center justify-content-between">
    <a href="" class="logo d-flex align-items-center">
      <!-- <img src="assets/img/diskes.png" alt=""> -->
      <h5 class="d-none d-lg-block mt-3 mb-3">Sistem Informasi ASKEP | Politeknik Kaltara</h5>
    </a>
    <i class="bi bi-list toggle-sidebar-btn"></i> <!-- End Logo -->
  </div><!-- End Logo -->

  <nav class="header-nav ms-auto">
    <ul class="d-flex align-items-center">
    <?php if (in_array($currentLevel, ['Dosen', 'Mahasiswa'], true)): ?>
      <li class="nav-item dropdown">
        <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
          <i class="bi bi-bell"></i>
          <?php if ($notificationCount > 0): ?>
            <span class="badge bg-primary badge-number"><?= $notificationCount > 9 ? '9+' : $notificationCount ?></span>
          <?php endif; ?>
        </a>

        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications">
          <li class="dropdown-header d-flex justify-content-between align-items-center">
            <span><?= $notificationCount > 0 ? $notificationCount . ' notifikasi baru' : 'Tidak ada notifikasi baru' ?></span>
            <?php if ($notificationCount > 0): ?>
              <a href="<?= htmlspecialchars('index.php?page=notification_read_all&redirect=' . urlencode($_SERVER['REQUEST_URI'])) ?>">
                <span class="badge rounded-pill bg-primary p-2 ms-2">Tandai semua dibaca</span>
              </a>
            <?php endif; ?>
          </li>
          <li><hr class="dropdown-divider"></li>

          <?php if (!empty($notifications)): ?>
            <?php foreach ($notifications as $notif): ?>
              <li>
                <a class="dropdown-item d-flex align-items-start gap-2" href="<?= htmlspecialchars('index.php?page=notification_read&id=' . (int) $notif['id'] . '&redirect=' . urlencode($notif['target_url'])) ?>">
                  <i class="bi <?= ((int)($notif['is_read'] ?? 0) === 1) ? 'bi-bell' : 'bi-bell-fill text-primary' ?> mt-1"></i>
                  <div>
                    <div class="small fw-semibold<?= ((int)($notif['is_read'] ?? 0) === 1) ? ' text-muted' : '' ?>"><?= htmlspecialchars($notif['message']) ?></div>
                    <div class="text-muted small"><?= date('d/m/Y H:i', strtotime($notif['created_at'])) ?></div>
                  </div>
                </a>
              </li>
              <li><hr class="dropdown-divider"></li>
            <?php endforeach; ?>
          <?php else: ?>
            <li class="px-3 py-2 text-muted small">Belum ada notifikasi.</li>
            <li><hr class="dropdown-divider"></li>
          <?php endif; ?>

          <li class="dropdown-footer">
            <a href="<?= htmlspecialchars('index.php?page=dashboard') ?>">Lihat dashboard</a>
          </li>
        </ul>
      </li>
    <?php endif; ?>


      <li class="nav-item dropdown pe-3">
        <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
          <!-- <img src="assets/img/profile-img.jpg" alt="Profile" class="rounded-circle"> -->
          <span class="d-none d-md-block dropdown-toggle ps-2"><?= $_SESSION['nama']; ?></span>
        </a><!-- End Image Icon -->
        
        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
          <!-- Profile Dropdown Items -->
          <li class="dropdown-header">
            <h5><?= $_SESSION['nama']; ?></h5>
            <span><?= $_SESSION['level']; ?></span>
          </li>
          <li>
            <hr class="dropdown-divider">
          </li>
          <li>
            <a class="dropdown-item d-flex align-items-center" href="index.php?page=ganti_password">
              <i class="bi bi-gear"></i>
              <span>Ganti Password</span>
            </a>
          </li>
          <li>
            <hr class="dropdown-divider">
          </li>
          <li>
            <hr class="dropdown-divider">
          </li>
          <li>
            <a class="dropdown-item d-flex align-items-center" href="index.php?page=keluar">
              <i class="bi bi-box-arrow-right"></i>
              <span>Keluar</span>
            </a>
          </li>
        </ul><!-- End Profile Dropdown Items -->
      </li><!-- End Profile Nav -->
    </ul>
  </nav><!-- End Icons Navigation -->
</header><!-- End Header -->
