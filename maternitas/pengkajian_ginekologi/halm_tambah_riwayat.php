   <?php

    $form_id       = 12;
    $section_name  = 'riwayat_kehamilan_kesehatan';
    $section_label = 'Riwayat Kehamilan dan kesehatan';
    include dirname(__DIR__, 2) . '/partials/init_section.php';
    
    $existing_persalinan = $existing_data['riwayat_persalinan'] ?? [];

    // =============================================
    // HANDLE POST - MAHASISWA SIMPAN DATA
    // =============================================
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $level === 'Mahasiswa') {
        if (isLocked($submission)) {
            redirectWithMessage($_SERVER['REQUEST_URI'], 'error', 'Data tidak dapat diubah karena sedang dalam proses review.');
        }
        // Proses dynamic rows persalinan
        $persalinan = [];
        if (!empty($_POST['persalinan'])) {
            foreach ($_POST['persalinan'] as $index => $row) {
                // Skip row kalau semua field kosong
                if (empty($row['tahun']) && empty($row['jenis']) && empty($row['penolong'])) {
                    continue;
                }
                $persalinan[] = [
                    'no'              => $index,
                    'tahun'           => $row['tahun'] ?? '',
                    'jenis'           => $row['jenis'] ?? '',
                    'penolong'        => $row['penolong'] ?? '',
                    'jenis_kelamin'   => $row['jenis_kelamin'] ?? '',
                    'masalah'         => $row['masalah'] ?? '',
                ];
            }
        }
        $data = [
            'riwayat_persalinan'    => $persalinan,
            'pengalaman_menyusui'   => $_POST['pengalamanmenyusui'] ?? '',
            'berapalama1'           => $_POST['berapalama'] ?? '',
            'riwayat_ginekologi'    => $_POST['riwayatginekologi'] ?? '',
            'masalah_ginekologi'    => $_POST['masalahginekologi'] ?? '',
            'riwayat_kb'            => $_POST['riwayatkb'] ?? '',
            'riwayat_penyakit'      => $_POST['riwayatpenyakit'] ?? '',
            'keluhan_utama'         => $_POST['keluhanutama'] ?? '',
            'riwayat_keluhan_utama' => $_POST['riwayatkeluhanutama'] ?? '',
            'kesadaran'             => $_POST['kesadaran'] ?? '',
            'umum'                  => $_POST['umum'] ?? '',
            'bb_tb'                 => $_POST['bbtb'] ?? '',
            'lengan_atas'           => $_POST['lenganatas'] ?? '',
            'tekanan_darah'         => $_POST['tekanandarah'] ?? '',
            'nadi'                  => $_POST['nadi'] ?? '',
            'suhu'                  => $_POST['suhu'] ?? '',
            'pernapasan'            => $_POST['pernapasan'] ?? '',
        ];
        if (!$submission) {
            $submission_id = createSubmission($user_id, $form_id, null, null, $mysqli);
        } else {
            $submission_id = $submission['id'];
        }
        saveSection($submission_id, $section_name, $section_label, $data, $mysqli);
        updateSubmissionStatus($submission_id, $form_id, $mysqli);
        redirectWithMessage($_SERVER['REQUEST_URI'], 'success', 'Data berhasil disimpan.');
    }
    ?>

   <main id="main" class="main">

       <?php include "maternitas/pengkajian_ginekologi/tab.php"; ?>

       <section class="section dashboard">
           <?php include "partials/notifikasi.php"; ?>
           <?php include "partials/status_section.php"; ?>

           <div class="card">
               <div class="card-body">
                   <h5 class="card-title"><strong>RIWAYAT KEHAMILAN DAN PERSALINAN</strong></h5>
                   <!-- General Form Elements -->
                   <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">
                       <table class="table table-bordered" id="tabel-persalinan">
                           <thead>
                               <tr>
                                   <th class="text-center">No</th>
                                   <th class="text-center">Tahun</th>
                                   <th class="text-center">Jenis Persalinan</th>
                                   <th class="text-center">Penolong</th>
                                   <th class="text-center">Jenis Kelamin</th>
                                   <th class="text-center">Masalah Kehamilan</th>
                                   <th class="text-center">Aksi</th>
                               </tr>
                           </thead>
                           <tbody id="tbody-persalinan">
                               <!-- Row dinamis masuk sini -->
                           </tbody>
                       </table>

                       <?php if (!$is_dosen): ?>
                           <div class="row mb-3">
                               <div class="col-sm-11 d-flex justify-content-end">
                                   <button type="button" class="btn btn-primary" onclick="tambahRow()">Tambah Data</button>
                               </div>
                           </div>
                       <?php endif; ?>

                       <!-- Pengalaman Menyusui -->
                       <div class="row mb-3">
                           <label for="pengalaman_menyusui" class="col-sm-2 col-form-label"><strong>Pengalaman Menyusui</strong></label>
                           <div class="col-sm-9">
                               <select class="form-select" name="pengalamanmenyusui" <?= $ro_select ?>>
                                   <option value="">Pilih</option>
                                   <option value="Ya" <?= val('pengalaman_menyusui', $existing_data) === 'Ya' ? 'selected' : '' ?>>Ya</option>
                                   <option value="Tidak" <?= val('pengalaman_menyusui', $existing_data) === 'Tidak' ? 'selected' : '' ?>>Tidak</option>
                               </select>
                           </div>
                       </div>

                       <!-- Berapa Lama -->
                       <div class="row mb-3">
                           <label for="berapa_lama" class="col-sm-2 col-form-label"><strong>Berapa Lama</strong></label>
                           <div class="col-sm-9">
                               <input type="text" class="form-control" name="berapalama" value="<?= val('berapalama1', $existing_data) ?>" <?= $ro ?>>
                           </div>
                       </div>

                       <!-- Bagian Riwayat Ginekologi-->
                       <div class="row mb-3">
                           <label for="riwayat_ginekologi" class="col-sm-2 col-form-label"><strong>Riwayat Ginekologi</strong></label>
                           <div class="col-sm-9">
                               <input type="text" class="form-control" name="riwayatginekologi" value="<?= val('riwayat_ginekologi', $existing_data) ?>" <?= $ro ?>>

                           </div>
                       </div>

                       <!-- Bagian Hasil -->
                       <div class="row mb-3">
                           <label for="hasil_ginekologi" class="col-sm-2 col-form-label"><strong>Masalah Ginekologi</strong></label>
                           <div class="col-sm-9">
                               <textarea name="masalahginekologi" class="form-control" rows="5" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('masalah_ginekologi', $existing_data) ?></textarea>
                           </div>
                       </div>

                       <!-- Bagian Riwayat KB -->
                       <div class="row mb-3">
                           <label for="riwayat_kb" class="col-sm-2 col-form-label"><strong>Riwayat KB</strong></label>
                           <div class="col-sm-9">
                               <textarea name="riwayatkb" class="form-control" rows="5" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('riwayat_kb', $existing_data) ?></textarea>
                           </div>
                       </div>
                       <!-- Bagian Riwayat KB -->
                       <div class="row mb-3">
                           <label for="riwayat_kb" class="col-sm-2 col-form-label"><strong>Riwayat Penyakit Keluarga</strong></label>
                           <div class="col-sm-9">
                               <textarea name="riwayatpenyakit" class="form-control" rows="5" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('riwayat_penyakit', $existing_data) ?></textarea>
                           </div>
                       </div>

               </div>
           </div>


           <div class="card">
               <div class="card-body">
                   <h5 class="card-title mb-1"><strong>RIWAYAT KESEHATAN SAAT INI</strong></h5>

                   <!-- General Form Elements -->


                   <!-- Bagian Keluhan Utama -->
                   <div class="row mb-3">
                       <label class="col-sm-2 col-form-label"><strong>Keluhan Utama</strong></label>

                       <div class="col-sm-10">
                           <textarea name="keluhanutama" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                               <?= $ro ?>><?= val('keluhan_utama', $existing_data) ?></textarea>
                       </div>
                   </div>

                   <!-- Riwayat Keluhan Utama -->
                   <div class="row mb-3">
                       <label class="col-sm-2 col-form-label"><strong>Riwayat Keluhan Utama</strong></label>

                       <div class="col-sm-10">
                           <textarea name="riwayatkeluhanutama" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                               <?= $ro ?>><?= val('riwayat_keluhan_utama', $existing_data) ?></textarea></textarea>
                       </div>
                   </div>

                   <!-- Bagian Keadaan Umum dan Kesadaran -->

                   <div class="row mb-2">
                       <label class="col-sm-8 col-form-label text-primary">
                           <strong>Keadaan Umum dan Kesadaran</strong>
                   </div>

                   <div class="row mb-3">
                       <label for="kesadaran" class="col-sm-2 col-form-label"><strong>Kesadaran</strong></label>
                       <div class="col-sm-10">
                           <textarea name="kesadaran" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                               <?= $ro ?>><?= val('kesadaran', $existing_data) ?></textarea></textarea>
                       </div>
                   </div>

                   <!-- Bagian Keadaan Umum -->
                   <div class="row mb-3">
                       <label class="col-sm-2 col-form-label"><strong>Keadaan Umum</strong></label>
                       <div class="col-sm-10">
                           <textarea name="umum" class="form-control" rows="3" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"
                               <?= $ro ?>><?= val('umum', $existing_data) ?></textarea></textarea>
                       </div>
                   </div>

                   <!-- Bagian BB/TB -->
                   <div class="row mb-3">
                       <label for="bbtb" class="col-sm-2 col-form-label"><strong>BB/TB</strong></label>
                       <div class="col-sm-10">
                           <div class="input-group">
                               <input type="text" class="form-control" name="bbtb" value="<?= val('bb_tb', $existing_data) ?>" <?= $ro ?>>
                               <span class="input-group-text">kg/cm</span>
                           </div>
                       </div>
                   </div>

                   <!-- Bagian Lengan Atas -->
                   <div class="row mb-3">
                       <label for="lenganatas" class="col-sm-2 col-form-label"><strong>Lengan Atas</strong></label>
                       <div class="col-sm-10">
                           <div class="input-group">
                               <input type="text" class="form-control" name="lenganatas" value="<?= val('lengan_atas', $existing_data) ?>" <?= $ro ?>>
                               <span class="input-group-text">cm</span>
                           </div>
                       </div>
                   </div>

                   <!-- Bagian Tanda-tanda Vital -->

                   <div class="row mb-3">
                       <label class="col-sm-9 col-form-label">
                           <strong>Tanda-tanda Vital</strong>
                       </label>
                   </div>

                   <!-- Tekanan Darah -->
                   <div class="row mb-3 align-items-center">
                       <label class="col-sm-2 col-form-label"><strong>Tekanan Darah</strong></label>
                       <div class="col-sm-4">
                           <div class="input-group">
                               <input type="text" class="form-control" name="tekanandarah" value="<?= val('tekanan_darah', $existing_data) ?>" <?= $ro ?>>
                           </div>
                       </div>

                       <!-- Nadi -->
                       <label class="col-sm-2 col-form-label"><strong>Nadi</strong></label>
                       <div class="col-sm-4">
                           <div class="input-group">
                               <input type="text" class="form-control" name="nadi" value="<?= val('nadi', $existing_data) ?>" <?= $ro ?>>
                           </div>
                       </div>

                   </div>

                   <!-- Suhu -->
                   <div class="row mb-3 align-items-center">
                       <label class="col-sm-2 col-form-label"><strong>Suhu</strong></label>
                       <div class="col-sm-4">
                           <div class="input-group">
                               <input type="text" class="form-control" name="suhu" value="<?= val('suhu', $existing_data) ?>" <?= $ro ?>>
                           </div>
                       </div>

                       <!-- RR -->
                       <label class="col-sm-2 col-form-label"><strong>Pernapasan</strong></label>
                       <div class="col-sm-4">
                           <div class="input-group">
                               <input type="text" class="form-control" name="pernapasan" value="<?= val('pernapasan', $existing_data) ?>" <?= $ro ?>>
                           </div>
                       </div>
                   </div>


                   <!-- TOMBOL SUBMIT -->
                   <?php if (!$is_dosen): ?>
                       <div class="row mb-3">
                           <div class="col-sm-11 d-flex justify-content-end">
                               <button type="submit" class="btn btn-primary">Simpan</button>
                           </div>
                       </div>
                   <?php endif; ?>
                   </form>
               </div>
           </div>

           <script>
               let rowCount = 1;
               // Load existing data persalinan dari PHP
               const existingPersalinan = <?= json_encode($existing_persalinan) ?>;

               function tambahRow(data = null) {
                   const tbody = document.getElementById('tbody-persalinan');
                   const row = document.createElement('tr');
                   const index = rowCount;
                   row.innerHTML = `
                    <td>${index}</td>
                    <td><input type="text" class="form-control form-control-sm" name="persalinan[${index}][tahun]" value="${data?.tahun ?? ''}" <?= $ro ?>></td>
                    <td><input type="text" class="form-control form-control-sm" name="persalinan[${index}][jenis]" value="${data?.jenis ?? ''}" <?= $ro ?>></td>
                    <td><input type="text" class="form-control form-control-sm" name="persalinan[${index}][penolong]" value="${data?.penolong ?? ''}" <?= $ro ?>></td>
                    <td>
                        <select class="form-select form-select-sm" name="persalinan[${index}][jenis_kelamin]" <?= $ro_select ?> >
                            <option value="">Pilih</option>
                            <option value="Perempuan" ${data?.jenis_kelamin === 'Perempuan' ? 'selected' : ''}>Perempuan</option>
                            <option value="Laki-laki" ${data?.jenis_kelamin === 'Laki-laki' ? 'selected' : ''}>Laki-laki</option>
                        </select>
                    </td>
                    <td><input type="text" class="form-control form-control-sm" name="persalinan[${index}][masalah]" value="${data?.masalah ?? ''}" <?= $ro ?>></td>
                    <td>${!<?= json_encode($is_dosen) ?> && !<?= json_encode($is_readonly) ?> ? `<button type="button" class="btn btn-danger btn-sm" onclick="hapusRow(this)">x</button>` : ''}</td>
                `;
                   tbody.appendChild(row);
                   rowCount++;
               }

               function hapusRow(btn) {
                   btn.closest('tr').remove();
               }
               // Load existing rows kalau ada
               window.addEventListener('load', function() {
                   if (existingPersalinan && existingPersalinan.length > 0) {
                       existingPersalinan.forEach(row => tambahRow(row));
                   } else {
                       tambahRow(); // default 1 row kosong
                   }
               });
           </script>

           <?php include "partials/footer_form.php"; ?>

       </section>
   </main>