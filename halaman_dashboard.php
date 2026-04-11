<?php
require_once 'utils.php';
require_once 'koneksi.php';

// $masterDokumen = getAllDataDokumen($mysqli);
?>

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Dashboard</h1>

        <!-- <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
        </nav -->
    </div>
    <!-- End Page Title -->

    <section class="section dashboard">
        <div class="row">

        <!-- Dokumen Masuk Card -->
        <div class="col-xxl-4 col-md-6">
            <div class="card info-card">
                <div class="card-body">
                    <h5 class="card-title">Dokumen Masuk</h5>
                    <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                            <i class="ri-mail-unread-line"></i>
                        </div>
                        <?php
                        // $sql = "SELECT COUNT(*) AS dokumen_masuk FROM tbl_dok_masuk";
                        // $result = $mysqli->query($sql);
                        // $row = $result->fetch_assoc();
                        // $dokumen_masuk = $row['dokumen_masuk'];

                        // $sql = "SELECT COUNT(*) AS dokumen_masuk_hari_ini FROM tbl_dok_masuk WHERE tgl_terima_dok='" . Date("Y-m-d") . "'";
                        // $result = $mysqli->query($sql);
                        // $row = $result->fetch_assoc();
                        // $dokumen_masuk_hari_ini = $row['dokumen_masuk_hari_ini'];
                        ?>
                        <div class="ps-4">
                          <!-- <h6><?= $dokumen_masuk; ?>Dokumen</h6> -->
                          <!-- <span class="text-success small pt-1 fw-bold"><?= $dokumen_masuk_hari_ini; ?></span> -->
                          <span class="text-muted small pt-2 ps-1">Dokumen Masuk Hari ini</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- End Dokumen Masuk Card -->

                <!-- Revenue Card -->
                <div class="col-xxl-4 col-md-6">
                    <div class="card info-card revenue-card">
                    <div class="card-body">
                        <h5 class="card-title">Dokumen Keluar</h5>

                        <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                            <i class="ri-mail-send-line"></i>
                        </div>
                        <?php
                        // $sql            = "SELECT COUNT(*) AS dokumen_keluar FROM tbl_dok_keluar";
                        // $result         = $mysqli->query($sql);
                        // $row            = $result->fetch_assoc();
                        // $dokumen_keluar = $row['dokumen_keluar'];

                        // $sql                    = "SELECT COUNT(*) AS dokumen_keluar_hari_ini FROM tbl_dok_keluar WHERE tgl_keluar_dok='" . Date("Y-m-d") . "'";
                        // $result                 = $mysqli->query($sql);
                        // $row                    = $result->fetch_assoc();
                        // $dokumen_keluar_hari_ini  = $row['dokumen_keluar_hari_ini'];
                        ?>
                        <div class="ps-4">
                            <!-- <h6><?= $dokumen_keluar; ?>Dokumen</h6>
                            <span class="text-success small pt-1 fw-bold"><?= $dokumen_keluar_hari_ini; ?></span> <span class="text-muted small pt-2 ps-1">Dokumen Keluar Hari ini</span> -->

                        </div>
                        </div>
                    </div>

                    </div>
                </div>
                <!-- End Revenue Card -->

                <!-- Revenue Card -->
                <div class="col-xxl-4 col-md-6">
                    <div class="card info-card revenue-card">
                    <div class="card-body">
                        <h5 class="card-title">Dokumen Pendukung</h5>

                        <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                            <i class="ri-mail-send-line"></i>
                        </div>
                        <?php
                        // $sql            = "SELECT COUNT(*) AS dokumen_pendukung FROM tbl_dok_pendukung";
                        // $result         = $mysqli->query($sql);
                        // $row            = $result->fetch_assoc();
                        // $dokumen_pendukung = $row['dokumen_pendukung'];

                        // $sql                    = "SELECT COUNT(*) AS dokumen_pendukung_hari_ini FROM tbl_dok_pendukung WHERE tgl_masuk_dok='" . Date("Y-m-d") . "'";
                        // $result                 = $mysqli->query($sql);
                        // $row                    = $result->fetch_assoc();
                        // $dokumen_pendukung_hari_ini  = $row['dokumen_pendukung_hari_ini'];
                        ?>
                        <div class="ps-4">
                            <!-- <h6><?= $dokumen_pendukung; ?>Dokumen</h6>
                            <span class="text-success small pt-1 fw-bold"><?= $dokumen_pendukung_hari_ini; ?></span> <span class="text-muted small pt-2 ps-1">Dokumen Pendukung Hari ini</span> -->

                        </div>
                        </div>
                    </div>

                    </div>
                </div>
                <!-- End Revenue Card -->

                <!-- Top Selling -->
                <div class="col-12">
                        <div class="card top-selling">

                        <!-- <div class="filter">
                            <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                            <li class="dropdown-header text-start">
                                <h6>Filter</h6>
                            </li>

                            <li><a class="dropdown-item" href="#">Today</a></li>
                            <li><a class="dropdown-item" href="#">This Month</a></li>
                            <li><a class="dropdown-item" href="#">This Year</a></li>
                            </ul>
                        </div> -->

                        <div class="card-body pb-0">
                            <h5 class="card-title">Master Dokumen</h5>
                            <table class="table datatable">
                            <thead>
                                <tr>
                                <th scope="col" class="text-center">No</th>
                                <th scope="col" class="text-center">Nomor Dokumen</th>
                                <th scope="col" class="text-center">Tanggal Dokumen</th>
                                <th scope="col" class="text-center">Perihal</th>
                                <th scope="col" class="text-center">Tipe Dokumen</th>
                                <th scope="col" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                               
                                <?php // $no     = 1; for ($i=0; $i < sizeof($masterDokumen); $i++) : ?>
                                <!-- <tr>
                                    <th class="align-middle text-center" scope="row"><?= $no++; ?></th>
                                    <td class="align-middle text-center"><?= $masterDokumen[$i]['id']; ?></td>
                                    <td class="align-middle text-center"><?= $masterDokumen[$i]['tgl_dokumen']; ?></td>
                                    <td class="align-middle text-center"><?= $masterDokumen[$i]['perihal']; ?></td>
                                    <td class="align-middle text-center"><?= $masterDokumen[$i]['type']; ?></td>
                                    <td class="d-flex justify-content-center gap-1">
                                        <a href="<?= getURLMasterData($masterDokumen[$i]['type'], $masterDokumen[$i]['id'], "Detail")?>" class="btn btn-info btn-xs"><i class="bi bi-eye"></i></a>
                                        <a href="<?= getURLMasterData($masterDokumen[$i]['type'], $masterDokumen[$i]['id'], "Edit")?>" class="btn btn-warning text-light"><i class="bi bi-pencil"></i></a>
                                        <a href="<?= getURLMasterData($masterDokumen[$i]['type'], $masterDokumen[$i]['id'], "Delete")?>" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus data ini?')"><i class="bi bi-trash"></i></a>
                                    </td>
                                </tr> -->
                                <?php // endfor; ?>
                            </tbody>
                            </table>
                        </div>
                        </div>
                    </div>
                    <!-- End Top Selling -->

                <!-- Top Selling -->
                    <div class="col-12">
                        <div class="card top-selling">

                        <!-- <div class="filter">
                            <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                            <li class="dropdown-header text-start">
                                <h6>Filter</h6>
                            </li>

                            <li><a class="dropdown-item" href="#">Today</a></li>
                            <li><a class="dropdown-item" href="#">This Month</a></li>
                            <li><a class="dropdown-item" href="#">This Year</a></li>
                            </ul>
                        </div> -->

                        <div class="card-body pb-0">
                            <h5 class="card-title">Dokumen Masuk Terakhir</h5>
                            <table class="table datatable">
                            <thead>
                                <tr>
                                <th scope="col" class="text-center">No</th>
                                <th scope="col" class="text-center">Nomor Dokumen</th>
                                <th scope="col" class="text-center">Tanggal Masuk Dokumen</th>
                                <th scope="col" class="text-center">Status Tindakan</th>
                                <th scope="col" class="text-center">Perihal</th>
                                <th scope="col" class="text-center">Asal Dokumen</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // $no     = 1;
                                // $sql    = "SELECT * FROM tbl_dok_masuk ORDER BY no_dokumen DESC";
                                // $result = $mysqli->query($sql);
                                ?>

                                <?php // while ($row = $result->fetch_assoc()) : ?>
                                <!-- <tr>
                                    <th class="align-middle text-center" scope="row"><?= $no++; ?></th>
                                    <td class="align-middle text-center"><?= $row['no_dokumen']; ?></td>
                                    <td class="align-middle text-center"><?= $row['tgl_masuk_dok']; ?></td>
                                    <td class="align-middle text-center"><?= $row['status_tindakan']; ?></td>
                                    <td class="align-middle text-center"><?= $row['perihal']; ?></td>
                                    <td class="align-middle text-center"><?= $row['asal_dokumen']; ?></td>
                                </tr> -->
                                <?php // endwhile; ?>
                            </tbody>
                            </table>
                        </div>
                        </div>
                    </div>
                    <!-- End Top Selling -->

                    <div class="col-12">
                        <div class="card top-selling">

                        <div class="card-body pb-0">
                            <h5 class="card-title">Dokumen Keluar Terakhir</h5>

                            <table class="table datatable">
                            <thead>
                                <tr>
                                <th scope="col" class="text-center">No</th>
                                <th scope="col" class="text-center">Nomor Dokumen</th>
                                <th scope="col" class="text-center">Tanggal Keluar Dokumen</th>
                                <th scope="col" class="text-center">Perihal</th>
                                <th scope="col" class="text-center">Tujuan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    // $no     = 1;
                                    // $sql = "SELECT * FROM tbl_dok_keluar ORDER BY no_dokumen DESC";
                                    // $result = $mysqli->query($sql);
                                ?>
                                <?php // while ($row = $result->fetch_assoc()) : ?>
                                <!-- <tr>
                                    <th class="align-middle text-center" scope="row"><?= $no++; ?></th>
                                    <td class="align-middle text-center"><?= $row['no_dokumen']; ?></td>
                                    <td class="align-middle text-center"><?= $row['tgl_keluar_dok']; ?></td>
                                    <td class="align-middle text-center"><?= $row['perihal']; ?></td>
                                    <td class="align-middle text-center"><?= $row['tujuan']; ?></td>
                                </tr>
                                <?php //endwhile; ?>
                            </tbody>
                            </table>

                        </div>

                        </div>
                    </div>
                    <!-- End Top Selling -->

                    <div class="col-12">
                        <div class="card top-selling">

                        <div class="card-body pb-0">
                            <h5 class="card-title">Dokumen Pendukung Terakhir</h5>

                            <table class="table datatable">
                            <thead>
                                <tr>
                                <th scope="col" class="text-center">No</th>
                                <th scope="col" class="text-center">Nomor Dokumen</th>
                                <th scope="col" class="text-center">Tanggal Masuk</th>
                                <th scope="col" class="text-center">Tanggal Keluar</th>
                                <th scope="col" class="text-center">Perihal</th>
                                <th scope="col" class="text-center">Asal Dokumen</th>
                                <th scope="col" class="text-center">Tujuan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // $no     = 1;
                                // $sql = "SELECT * FROM tbl_dok_pendukung ORDER BY no_dokumen DESC";
                                // $result = $mysqli->query($sql);
                                ?>
                                <?php // while ($row = $result->fetch_assoc()) : ?>
                                <!-- <tr>
                                    <th class="align-middle text-center" scope="row"><?= $no++; ?></th>
                                    <td class="align-middle text-center"><?= $row['no_dokumen']; ?></td>
                                    <td class="align-middle text-center"><?= $row['tgl_masuk_dok']; ?></td>
                                    <td class="align-middle text-center"><?= $row['tgl_keluar_dok']; ?></td>
                                    <td class="align-middle text-center"><?= $row['perihal']; ?></td>
                                    <td class="align-middle text-center"><?= $row['asal_dokumen']; ?></td>
                                    <td class="align-middle text-center"><?= $row['tujuan']; ?></td>
                                </tr> -->
                                <?php // endwhile; ?>
                            </tbody>
                            </table>

                        </div>

                        </div>
                    </div>

                    </div>
                </div>
                <!-- End Left side columns -->
                
            </section>

            </main>
            <!-- End #main -->