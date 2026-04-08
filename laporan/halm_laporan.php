<?php
require_once "koneksi.php";
require_once "utils.php";
?>

<main id="main" class="main">
  <div class="pagetitle">
    <h1>Laporan</h1>
  </div>
    <section class="section">
      <div class="row">
        <div class="col-lg-4">

        <!-- Default Card -->
        <!-- Laporan Dokumen Masuk -->
        <div class="card">
          <div class="card-body">
            <form action="laporan/cetak/laporan_dokumen_masuk.php" method="POST" target="_blank">
              <h5 class="card-title">Laporan Dokumen Masuk</h5>
              <div class="row mb-3">
                <div class="col-md-6">
                  <label for="dari">Dari</label>
                  <input type="date" value="2023-01-01" id="dari" name="dari" class="form-control">
                </div>
                <div class="col-md-6">
                  <label for="sampai">Sampai</label>
                  <input type="date" value="<?= Date("Y-m-d"); ?>" name="sampai" id="sampai" class="form-control">
                </div>
              </div>
              <div class="row mb-3">
                <div class="col-md-12">
                </div>
              </div>
              <div class="row mb-3">
                <div class="col-md-12">
                  <button class="btn btn-primary w-100">Cetak</button>
                </div>
              </div>
            </form>
          </div>
        </div><!-- End Default Card -->

        <!-- Default Card -->
        <!-- Laporan Dokumen Pendukung -->
        <div class="card">
          <div class="card-body">
            <form action="laporan/cetak/laporan_dokumen_pendukung.php" method="POST" target="_blank">
              <h5 class="card-title">Laporan Dokumen Pendukung</h5>
              <div class="row mb-3">
                <div class="col-md-6">
                  <label for="dari">Dari</label>
                  <input type="date" value="2023-01-01" id="dari" name="dari" class="form-control">
                </div>
                <div class="col-md-6">
                  <label for="sampai">Sampai</label>
                  <input type="date" value="<?= Date("Y-m-d"); ?>" name="sampai" id="sampai" class="form-control">
                </div>
              </div>
              <div class="row mb-3">
                <div class="col-md-12">
                </div>
              </div>
              <div class="row mb-3">
                <div class="col-md-12">
                  <button class="btn btn-primary w-100">Cetak</button>
                </div>
              </div>
            </form>
          </div>
        </div><!-- End Default Card -->

        <!-- Default Card -->
        <!-- Laporan Peminjaman Dokumen -->
        <div class="card">
          <div class="card-body">
            <form action="laporan/cetak/laporan_peminjaman_dokumen.php" method="POST" target="_blank">
              <h5 class="card-title">Laporan Peminjaman Dokumen</h5>
              <div class="row mb-3">
                <div class="col-md-6">
                  <label for="dari">Dari</label>
                  <input type="date" value="2023-01-01" id="dari" name="dari" class="form-control">
                </div>
                <div class="col-md-6">
                  <label for="sampai">Sampai</label>
                  <input type="date" value="<?= Date("Y-m-d"); ?>" name="sampai" id="sampai" class="form-control">
                </div>
              </div>
              <div class="row mb-3">
                <div class="col-md-12">
                </div>
              </div>
              <div class="row mb-3">
                <div class="col-md-12">
                  <button class="btn btn-primary w-100">Cetak</button>
                </div>
              </div>
            </form>
          </div>
        </div><!-- End Default Card -->
      </div>

      <div class="col-lg-4">

      <!-- Default Card -->
      <!-- Laporan Dokumen Keluar -->
      <div class="card">
        <div class="card-body">
          <form action="laporan/cetak/laporan_dokumen_keluar.php" method="POST" target="_blank">
            <h5 class="card-title">Laporan Dokumen Keluar</h5>
            <div class="row mb-3">
              <div class="col-md-6">
                <label for="dari">Dari</label>
                <input type="date" value="2023-01-01" id="dari" name="dari" class="form-control">
              </div>
              <div class="col-md-6">
                <label for="sampai">Sampai</label>
                <input type="date" value="<?= Date("Y-m-d"); ?>" name="sampai" id="sampai" class="form-control">
                </div>
              </div>
              <div class="row mb-3">
                <div class="col-md-12">
                </div>
              </div>
              <div class="row mb-3">
                <div class="col-md-12">
                  <button class="btn btn-primary w-100">Cetak</button>
                </div>
              </div>
            </form>
          </div>
        </div><!-- End Default Card -->

        <!-- Default Card -->
        <!-- Laporan Dokumen Personel -->
        <div class="card">
          <div class="card-body">
            <form action="laporan/cetak/laporan_dokumen_personel.php" method="POST" target="_blank">
              <h5 class="card-title">Laporan Dokumen Personel</h5>
              <div class="row mb-3">
                <div class="col-md-6">
                  <label for="dari">Dari</label>
                  <input type="date" value="2023-01-01" id="dari" name="dari" class="form-control">
                </div>
                <div class="col-md-6">
                  <label for="sampai">Sampai</label>
                  <input type="date" value="<?= Date("Y-m-d"); ?>" name="sampai" id="sampai" class="form-control">
                </div>
              </div>
              <div class="row mb-3">
                <div class="col-md-12">
                </div>
              </div>
              <div class="row mb-3">
                <div class="col-md-12">
                  <button class="btn btn-primary w-100">Cetak</button>
                </div>
              </div>
            </form>
          </div>
        </div><!-- End Default Card -->

        <!-- Default Card -->
        <!-- Laporan Peminjaman Dokumen Personel -->
        <div class="card">
          <div class="card-body">
            <form action="laporan/cetak/laporan_peminjaman_dok_personel.php" method="POST" target="_blank">
              <h5 class="card-title">Laporan Peminjaman Dokumen Personel</h5>
              <div class="row mb-3">
                <div class="col-md-6">
                  <label for="dari">Dari</label>
                  <input type="date" value="2023-01-01" id="dari" name="dari" class="form-control">
                </div>
                <div class="col-md-6">
                  <label for="sampai">Sampai</label>
                  <input type="date" value="<?= Date("Y-m-d"); ?>" name="sampai" id="sampai" class="form-control">
                </div>
              </div>
              <div class="row mb-3">
                <div class="col-md-12">
                </div>
              </div>
              <div class="row mb-3">
                <div class="col-md-12">
                  <button class="btn btn-primary w-100">Cetak</button>
                </div>
              </div>
            </form>
          </div>
        </div><!-- End Default Card -->
      </div>

      <div class="col-lg-4">

        <!-- Default Card -->
       <!-- Laporan Arsip -->
        <div class="card">
            <div class="card-body">
                <form action="laporan/cetak/laporan_label_arsip.php" method="POST" target="_blank">
                    <h5 class="card-title">Laporan Label Arsip</h5>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="dari">Dari</label>
                            <input type="date" value="2023-01-01" id="dari" name="dari" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label for="sampai">Sampai</label>
                            <input type="date" value="<?= Date("Y-m-d"); ?>" name="sampai" id="sampai" class="form-control">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-12">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <button class="btn btn-primary w-100">Cetak</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
      
        <!-- Default Card -->
        <!-- Laporan Sirkulasi - Disposisi -->
        <div class="card">
          <div class="card-body">
            <form action="laporan/cetak/laporan_sirkulasi.php" method="POST" target="_blank">
              <h5 class="card-title">Laporan Sirkulasi - Disposisi</h5>
              <div class="row mb-3">
                <div class="col-md-6">
                  <label for="dari">Dari</label>
                  <input type="date" value="2023-01-01" id="dari" name="dari" class="form-control">
                </div>
                <div class="col-md-6">
                  <label for="sampai">Sampai</label>
                  <input type="date" value="<?= Date("Y-m-d"); ?>" name="sampai" id="sampai" class="form-control">
                </div>
              </div>
              <div class="row mb-3">
                <div class="col-md-12">
                </div>
              </div>
              <div class="row mb-3">
                <div class="col-md-12">
                  <button class="btn btn-primary w-100">Cetak</button>
                </div>
              </div>
            </form>
          </div>
        </div><!-- End Default Card -->

        <div class="col-lg-4">
      </div>
    </div>
  </section>
</main>