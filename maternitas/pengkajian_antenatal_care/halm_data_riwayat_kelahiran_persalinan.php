   <?php
    require_once "koneksi.php";
    require_once "utils.php";

    $form_id       = 1;
    $level         = $_SESSION['level'];
    $user_id       = $_SESSION['id_user'];
    $section_name  = 'riwayat_kehamilan_persalinan';
    $section_label = 'Riwayat Kehamilan dan Persalinan';

    // =============================================
    // DOSEN: ambil submission berdasarkan ?submission_id=
    // MAHASISWA: ambil submission milik sendiri
    // =============================================
    if ($level === 'Dosen') {
        $submission_id_param = $_GET['submission_id'] ?? null;
        if (!$submission_id_param) {
            echo "<div class='alert alert-danger'>Submission tidak ditemukan.</div>";
            exit;
        }
        $stmt = $mysqli->prepare("
            SELECT s.*, r.nama as dosen_name
            FROM submissions s
            LEFT JOIN tbl_user r ON s.reviewed_by = r.id_user
            WHERE s.id = ?
        ");
        $stmt->bind_param("i", $submission_id_param);
        $stmt->execute();
        $submission = $stmt->get_result()->fetch_assoc();
    } else {
        $submission = getSubmission($user_id, $form_id, $mysqli);
    }

    $existing_data  = $submission ? getSectionData($submission['id'], $section_name, $mysqli) : [];
    $section_status = $submission ? getSectionStatus($submission['id'], $section_name, $mysqli) : null;
    // Load existing riwayat persalinan (array)
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
            'pengalaman_menyusui'   => $_POST['pengalaman_menyusui'] ?? '',
            'berapa_lama'           => $_POST['berapa_lama'] ?? '',
            'riwayat_ginekologi'    => $_POST['riwayat_ginekologi'] ?? '',
            'hasil_ginekologi'      => $_POST['hasil_ginekologi'] ?? '',
            'riwayat_kb'            => $_POST['riwayat_kb'] ?? '',
            'status_obstetrik_g'    => $_POST['status_obstetrik_g'] ?? '',
            'status_obstetrik_p'    => $_POST['status_obstetrik_p'] ?? '',
            'status_obstetrik_a'    => $_POST['status_obstetrik_a'] ?? '',
            'hpht'                  => $_POST['hpht'] ?? '',
            'usia_kehamilan'        => $_POST['usia_kehamilan'] ?? '',
            'bb_sebelum_hamil'      => $_POST['bb_sebelum_hamil'] ?? '',
            'keadaan_umum'          => $_POST['keadaan_umum'] ?? '',
            'bbtb'                  => $_POST['bbtb'] ?? '',
            'lengan_atas'           => $_POST['lengan_atas'] ?? '',
            'tekanan_darah'         => $_POST['tekanan_darah'] ?? '',
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

    // =============================================
    // HANDLE POST - DOSEN APPROVE / REVISI / KOMENTAR
    // =============================================
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $level === 'Dosen') {
        $submission_id = $submission['id'];
        $dosen_id      = $user_id;
        $action        = $_POST['action'] ?? '';
        $comment       = $_POST['comment'] ?? '';

        if ($action === 'approve') {
            updateSectionStatus($submission_id, $section_name, 'approved', $mysqli);
            if (!empty($comment)) {
                saveComment($submission_id, $section_name, $comment, $dosen_id, $mysqli);
            }
        } elseif ($action === 'revision') {
            if (empty($comment)) {
                redirectWithMessage($_SERVER['REQUEST_URI'], 'error', 'Komentar wajib diisi saat meminta revisi.');
            }
            updateSectionStatus($submission_id, $section_name, 'revision', $mysqli);
            saveComment($submission_id, $section_name, $comment, $dosen_id, $mysqli);
        }

        updateReviewer($submission_id, $dosen_id, $mysqli);
        updateSubmissionStatusByDosen($submission_id, $form_id, $mysqli);
        redirectWithMessage($_SERVER['REQUEST_URI'], 'success', 'Berhasil disimpan.');
    }

    // Load komentar section (untuk dosen & mahasiswa)
    $comments = $submission ? getSectionComments($submission['id'], $section_name, $mysqli) : [];

    // Readonly jika mahasiswa + locked, atau jika dosen
    $is_dosen    = $level === 'Dosen';
    $is_readonly = $is_dosen || isLocked($submission);
    $ro          = $is_readonly ? 'readonly' : '';
    $ro_select   = $is_readonly ? 'disabled' : '';
    ?>

   <main id="main" class="main">

       <?php include "maternitas/pengkajian_antenatal_care/tab.php"; ?>

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
                               <select class="form-select" name="pengalaman_menyusui" <?= $ro_select ?>>
                                   <option value="">Pilih</option>
                                   <option value="Ya">Ya</option>
                                   <option value="Tidak">Tidak</option>
                               </select>
                           </div>
                       </div>

                       <!-- Berapa Lama -->
                       <div class="row mb-3">
                           <label for="berapa_lama" class="col-sm-2 col-form-label"><strong>Berapa Lama</strong></label>
                           <div class="col-sm-9">
                               <input type="text" class="form-control" name="berapa_lama" <?= $ro ?>>
                           </div>
                       </div>

                       <!-- Bagian Riwayat Ginekologi-->
                       <div class="row mb-3">
                           <label for="riwayat_ginekologi" class="col-sm-2 col-form-label"><strong>Riwayat Ginekologi</strong></label>
                           <div class="col-sm-9">
                               <select class="form-select" name="riwayat_ginekologi" required <?= $ro_select ?>>
                                   <option value="">Pilih</option>
                                   <option value="Ada Masalah">Ada Masalah</option>
                                   <option value="Tidak">Tidak</option>
                               </select>
                           </div>
                       </div>

                       <!-- Bagian Hasil -->
                       <div class="row mb-3">
                           <label for="hasil_ginekologi" class="col-sm-2 col-form-label"><strong>Hasil Ginekologi</strong></label>
                           <div class="col-sm-9">
                               <textarea id="hasil_ginekologi" name="hasil_ginekologi" class="form-control" rows="5" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('hasil_ginekologi', $existing_data) ?></textarea>
                           </div>
                       </div>

                       <!-- Bagian Riwayat KB -->
                       <div class="row mb-3">
                           <label for="riwayat_kb" class="col-sm-2 col-form-label"><strong>Riwayat KB</strong></label>
                           <div class="col-sm-9">
                               <textarea id="riwayat_kb" name="riwayat_kb" class="form-control" rows="5" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" <?= $ro ?>><?= val('riwayat_kb', $existing_data) ?></textarea>
                           </div>
                       </div>

                       <h5 class="card-title"><strong>RIWAYAT KEHAMILAN SAAT INI</strong></h5>

                       <!-- Bagian Status Obstetrik -->

                       <div class="row mb-3">

                           <label class="col-sm-2 col-form-label"><strong>Status Obstetrik</strong></label>
                           <div class="col-sm-9">
                               <div class="row">

                                   <!-- G -->
                                   <div class="col-md-4 d-flex align-items-center">
                                       <label for="status_obstetrik_g" class="me-2"><strong>G</strong></label>
                                       <input id="status_obstetrik_g" type="text" class="form-control" name="status_obstetrik_g" value="<?= val('status_obstetrik_g', $existing_data) ?>" <?= $ro ?>>
                                   </div>

                                   <!-- P -->
                                   <div class="col-md-4 d-flex align-items-center">
                                       <label for="status_obstetrik_p" class="me-2"><strong>P</strong></label>
                                       <input id="status_obstetrik_p" type="text" class="form-control" name="status_obstetrik_p" value="<?= val('status_obstetrik_p', $existing_data) ?>" <?= $ro ?>>
                                   </div>

                                   <!-- A -->
                                   <div class="col-md-4 d-flex align-items-center">
                                       <label for="status_obstetrik_a" class="me-2"><strong>A</strong></label>
                                       <input id="status_obstetrik_a" type="text" class="form-control" name="status_obstetrik_a" value="<?= val('status_obstetrik_a', $existing_data) ?>" <?= $ro ?>>
                                   </div>
                               </div>
                           </div>

                       </div>

                       <!-- Bagian HPHT -->
                       <div class="row mb-3">
                           <label for="hpht" class="col-sm-2 col-form-label"><strong>HPHT</strong></label>
                           <div class="col-sm-9">
                               <input type="text" class="form-control" name="hpht" value="<?= val('hpht', $existing_data) ?>" <?= $ro ?>>
                           </div>
                       </div>

                       <!-- Bagian Usia Kehamilan -->
                       <div class="row mb-3">
                           <label for="usia_kehamilan" class="col-sm-2 col-form-label"><strong>Usia Kehamilan</strong></label>
                           <div class="col-sm-9">
                               <input id="usia_kehamilan" type="text" class="form-control" name="usia_kehamilan" value="<?= val('usia_kehamilan', $existing_data) ?>" <?= $ro ?>>
                           </div>
                       </div>

                       <!-- Bagian BB Sebelum Hamil -->
                       <div class="row mb-3">
                           <label for="bb_sebelum_hamil" class="col-sm-2 col-form-label"><strong>BB Sebelum Hamil</strong></label>
                           <div class="col-sm-9">
                               <input id="bb_sebelum_hamil" type="text" class="form-control" name="bb_sebelum_hamil" value="<?= val('bb_sebelum_hamil', $existing_data) ?>" <?= $ro ?>>
                           </div>
                       </div>

                       <!-- Bagian Keadaan Umum -->
                       <div class="row mb-3">
                           <label for="keadaan_umum" class="col-sm-2 col-form-label"><strong>Keadaan Umum</strong></label>
                           <div class="col-sm-9">
                               <input id="keadaan_umum" type="text" class="form-control" name="keadaan_umum" value="<?= val('keadaan_umum', $existing_data) ?>" <?= $ro ?>>
                           </div>
                       </div>

                       <!-- Bagian BB/TB -->
                       <div class="row mb-3">
                           <label for="bbtb" class="col-sm-2 col-form-label"><strong>BB/TB</strong></label>
                           <div class="col-sm-9">
                               <div class="input-group">
                                   <input type="text" class="form-control" name="bbtb" value="<?= val('bbtb', $existing_data) ?>" <?= $ro ?>>
                                   <span class="input-group-text">kg/cm</span>
                               </div>
                           </div>
                       </div>

                       <!-- Bagian Lengan Atas -->
                       <div class="row mb-3">
                           <label for="lengan_atas" class="col-sm-2 col-form-label"><strong>Lengan Atas</strong></label>
                           <div class="col-sm-9">
                               <div class="input-group">
                                   <input id="lengan_atas" type="text" class="form-control" name="lengan_atas" value="<?= val('lengan_atas', $existing_data) ?>" <?= $ro ?>>
                                   <span class="input-group-text">cm</span>
                               </div>
                           </div>
                       </div>


                       <!-- Bagian Tanda-tanda Vital -->
                       <div class="row mb-2">
                           <label class="col-sm-2 col-form-label">
                               <strong>Tanda-tanda Vital</strong>
                       </div>

                       <!-- Tekanan Darah -->
                       <div class="row mb-3">
                           <label for="tekanan_darah" class="col-sm-2 col-form-label"><strong>Tekanan Darah</strong></label>

                           <div class="col-sm-9">
                               <input id="tekanan_darah" type="text" class="form-control" name="tekanan_darah" value="<?= val('tekanan_darah', $existing_data) ?>" <?= $ro ?>>
                           </div>
                       </div>

                       <!-- Nadi -->
                       <div class="row mb-3">
                           <label class="col-sm-2 col-form-label"><strong>Nadi</strong></label>

                           <div class="col-sm-9">
                               <input type="text" class="form-control" name="nadi" value="<?= val('nadi', $existing_data) ?>" <?= $ro ?>>
                           </div>
                       </div>

                       <!-- Suhu -->
                       <div class="row mb-3">
                           <label class="col-sm-2 col-form-label"><strong>Suhu</strong></label>

                           <div class="col-sm-9">
                               <input type="text" class="form-control" name="suhu" value="<?= val('suhu', $existing_data) ?>" <?= $ro ?>>
                           </div>
                       </div>

                       <!-- Pernapasan -->
                       <div class="row mb-3">
                           <label class="col-sm-2 col-form-label"><strong>Pernapasan</strong></label>

                           <div class="col-sm-9">
                               <input type="text" class="form-control" name="pernapasan" value="<?= val('pernapasan', $existing_data) ?>" <?= $ro ?>>
                           </div>
                       </div>
               </div>

               <?php if (!$is_dosen): ?>
                   <div class="row mb-3">
                       <div class="col-sm-11 d-flex justify-content-end">
                           <button type="submit" class="btn btn-primary">Simpan Data</button>
                       </div>
                   </div>
               <?php endif; ?>
               </form><!-- End General Form Elements -->
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