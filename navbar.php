<?php
  require_once "koneksi.php";
  require_once "utils.php";
?>

<header id="header" class="header fixed-top d-flex align-items-center">
  <div class="d-flex align-items-center justify-content-between">
    <a href="" class="logo d-flex align-items-center">
      <!-- <img src="assets/img/diskes.png" alt=""> -->
      <h5 class="d-none d-lg-block">Diskes Lantamal XIII Tarakan</h5>
    </a>
    <i class="bi bi-list toggle-sidebar-btn"></i> <!-- End Logo -->
  </div><!-- End Logo -->

  <nav class="header-nav ms-auto">
    <ul class="d-flex align-items-center">
    <li class="nav-item dropdown">

          <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
            <i class="bi bi-bell"></i>
            <span class="badge bg-primary badge-number"> 1 </span>
          </a><!-- End Notification Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications">
            <li class="dropdown-header">
              You have 5 new notifications
              <a href="#"><span class="badge rounded-pill bg-primary p-2 ms-2">View all</span></a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>
              <hr class="dropdown-divider">
            </li>
            <?php //endfor ?>

            
            <li class="dropdown-footer">
              <a href="#">Show all notifications</a>
            </li>

          </ul><!-- End Notification Dropdown Items -->

        </li><!-- End Notification Nav -->


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
