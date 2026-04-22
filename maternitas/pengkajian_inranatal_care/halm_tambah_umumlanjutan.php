<?php
    require_once "koneksi.php";
    require_once "utils.php";

    $form_id       = 4;
    $level         = $_SESSION['level'];
    $user_id       = $_SESSION['id_user'];
    $section_name  = 'umum_lanjutan';
    $section_label = 'Umum Lanjutan';

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
                if (empty($row['jeniskelamin']) && empty($row['caralahir']) && empty($row['bblahir']) && empty($row['keadaan']) && empty($row['umum'])) {
                    continue;
                }
                $persalinan[] = [
                    'no'              => $index,
                    'jeniskelamin'    => $row['jeniskelamin'] ?? '',
                    'caralahir'       => $row['caralahir'] ?? '',
                    'bblahir'         => $row['bblahir'] ?? '',
                    'keadaan'         => $row['keadaan'] ?? '',
                    'umur'            => $row['umur'] ?? '',
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

       <?php include "maternitas/pengkajian_inranatal_care/tab.php"; ?>

       <section class="section dashboard">

           <!-- NOTIFIKASI -->
           <?php if (isset($_SESSION['success'])): ?>
               <div class="alert alert-success"><?= $_SESSION['success'];
                                                unset($_SESSION['success']); ?></div>
           <?php endif; ?>
           <?php if (isset($_SESSION['error'])): ?>
               <div class="alert alert-danger"><?= $_SESSION['error'];
                                                unset($_SESSION['error']); ?></div>
           <?php endif; ?>

        <!-- Info status section (untuk dosen) -->
        <?php if  ($section_status): ?>
            <?php
            $badge = [
                'draft'     => 'secondary',
                'submitted' => 'primary',
                'revision'  => 'warning',
                'approved'  => 'success',
            ];
            ?>
            <div class="alert alert-<?= $badge[$section_status] ?>">
                Status: <strong><?= ucfirst($section_status) ?></strong>
                | Reviewed by: <strong><?php echo $submission['dosen_name'] ? htmlspecialchars($submission['dosen_name']) : '-'; ?></strong>
            </div>
        <?php endif; ?>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><strong>RIWAYAT PERSALINAN</strong></h5>
                <!-- General Form Elements -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">
                       <table class="table table-bordered" id="tabel-persalinan">
                           <thead>
                               <tr>
                                   <th class="text-center">No</th>
                                   <th class="text-center">Jenis Kelamin</th>
                                   <th class="text-center">Cara Lahir</th>
                                   <th class="text-center">BB Lahir (gram)</th>
                                   <th class="text-center">Keadaan</th>
                                   <th class="text-center">Umur</th>
                                   <th class="text-center">Aksi</th>
                               </tr>
                           </thead>
                           <tbody id="tbody-persalinan">
                               <!-- Row dinamis masuk sini -->
                           </tbody>
                       </table>

                        <?php if (!$is_dosen): ?>
                        <div class="row mb-3">
                            <div class="col-sm-12 d-flex justify-content-end">
                                <button type="button" class="btn btn-primary" onclick="tambahRow()">Tambah Data</button>
                            </div>
                        </div>
                        <?php endif; ?>
            
                <!-- Bagian Kelas Prenatal -->
                        <div class="row mb-3">

                        <label class="col-sm-2 col-form-label"><strong>Kelas Prenatal</strong></label>

                        <!-- Field -->
                        <div class="col-sm-10">
                            <select class="form-select" name="kelasprenatal">
                                <option value="">Pilih</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                            </div>  
                        </div>  

            <!-- Bagian Kunjungan ANC -->
                <div class="row mb-3">
                    <label for="anc" class="col-sm-2 col-form-label"><strong>Jumlah Kunjungan ANC pada kehamilan ini</strong></label>
                    <div class="col-sm-10">
                        <div class="input-group">
                            <input type="text" class="form-control" name="anc">
                            <span class="input-group-text">kali</span>
                    </div>
                         </div>
                    </div>   
                    
            <!-- Bagian Masalah Kehamilan Yang lalu -->
                        <div class="row mb-3">
                            <label for="masalahkehamilanlalu" class="col-sm-2 col-form-label"><strong>Masalah Kehamilan yang Lalu</strong></label>
                            <div class="col-sm-10">
                               <textarea name="masalahkehamilanlalu" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                         </div>
                    </div>   
                    
            <!-- Bagian Masalah Kehamilan Sekarang -->
                        <div class="row mb-3">
                            <label for="masalahkehamilansekarang" class="col-sm-2 col-form-label"><strong>Masalah Kehamilan Sekarang</strong></label>
                            <div class="col-sm-10">
                               <textarea name="masalahkehamilansekarang" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                         </div>
                    </div>
                    
            <!-- Bagian Rencana KB -->
                        <div class="row mb-3">
                            <label for="rencanakb" class="col-sm-2 col-form-label"><strong>Rencana KB</strong></label>
                            <div class="col-sm-10">
                               <input type="text" class="form-control" name="rencanakb">
                         </div>
                    </div>
                    
            <!-- Bagian Makanan Bayi -->
                        <div class="row mb-3">

                        <label class="col-sm-2 col-form-label"><strong>Makanan Bayi Sebelumnya</strong></label>

                        <!-- Field -->
                        <div class="col-sm-10">
                            <select class="form-select" name="makananbayi">
                                <option value="">Pilih</option>
                                <option value="ASI">ASI</option>
                                <option value="PASI">PASI</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>

                    </div>  
                    
            <!-- Bagian Bayi Lahir -->
                        <div class="row mb-3">
                            <label for="bayilahir" class="col-sm-2 col-form-label"><strong>Setelah bayi lahir, siapa yang diharapkan membantu?</strong></label>
                            <div class="col-sm-10">
                                <small class="form-text" style="color: red;">Suami/Teman/Orang Tua? Hasil:</small>
                               <textarea name="bayilahir" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                         </div>
                    </div>  
                    
            <!-- Bagian Masalah Persalinan -->
                        <div class="row mb-3">
                            <label for="masalahpersalinan" class="col-sm-2 col-form-label"><strong>Masalah Persalinan</strong></label>
                            <div class="col-sm-10">
                                <small class="form-text" style="color: red;">Ada/Tidak? Jelaskan.</small>
                               <textarea name="masalahpersalinan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                    
                </div>
            </div>
                    

                <h5 class="card-title"><strong>RIWAYAT PERSALINAN SEKARANG</strong></h5>
            
                <!-- Bagian Keluhan Utama -->
                        <div class="row mb-3">
                            <label for="keluhanutama" class="col-sm-2 col-form-label"><strong>Keluhan Utama</strong></label>
                            <div class="col-sm-10">
                               <textarea name="keluhanutama" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                         </div>
                    </div> 

                <!-- Bagian Riwayat Keluhan Utama -->
                        <div class="row mb-3">
                            <label for="riwayatkeluhanutama" class="col-sm-2 col-form-label"><strong>Riwayat Keluhan Utama</strong></label>
                            <div class="col-sm-10">
                               <textarea name="riwayatkeluhanutama" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                         </div>
                    </div>  

                <!-- Bagian Mulai Persalinan -->
                        <div class="row mb-3">
                            <label for="mulaipersalinan" class="col-sm-2 col-form-label"><strong>Mulai Persalinan</strong></label>
                            <div class="col-sm-10">
                                <small class="form-text" style="color: red;">Kontraksi (teratur/tidak), interval, lama. Hasil:</small>
                               <textarea name="mulaipersalinan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                         </div>
                    </div>  
                    
                <!-- Bagian Tanda-tanda Vital -->

                    <div class="row mb-3">
                        <label class="col-sm-9 col-form-label text-primary">
                            <strong>Tanda-tanda Vital</strong>
                        </label>    
                    </div>

                    <!-- Tekanan Darah -->
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-2 col-form-label"><strong>Tekanan Darah</strong></label>
                        <div class="col-sm-4">
                            <div class="input-group">
                                <input type="text" class="form-control" name="tekanandarah">
                                <span class="input-group-text">mmHg</span>
                        </div>    
                    </div>
                                
                    <!-- Nadi -->
                    <label class="col-sm-2 col-form-label"><strong>Nadi</strong></label>
                    <div class="col-sm-4">
                        <div class="input-group">
                                <input type="text" class="form-control" name="nadi">
                                <span class="input-group-text">x/menit</span>
                        </div> 
                    </div>

                    </div>
              
                    <!-- Suhu -->
                        <div class="row mb-3 align-items-center">
                            <label class="col-sm-2 col-form-label"><strong>Suhu</strong></label>
                            <div class="col-sm-4">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="suhu">
                                    <span class="input-group-text">°C</span>
                            </div>    
                        </div>

                    <!-- RR -->
                    <label class="col-sm-2 col-form-label"><strong>RR</strong></label>
                    <div class="col-sm-4">
                        <div class="input-group">
                                <input type="text" class="form-control" name="rr">
                                <span class="input-group-text">x/menit</span>
                            </div>
                        </div>
                    </div>

                <!-- Bagian Kepala dan Rambut -->

                    <div class="row mb-2">
                        <label class="col-sm-2 col-form-label text-primary">
                            <strong>Kepala dan Rambut</strong>
                    </div>
                    
                    <!-- Kepala dan Rambut -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Kepala dan Rambut</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Rontok (Ya/Tidak), Kulit Kepala (Bersih/Kotor), Nyeri Tekan (Ya/Tidak). Hasil:</small>
                            <textarea name="kepaladanrambut" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                         </div>
                    </div>        
  
                    <!-- Bagian Wajah -->

                    <div class="row mb-2">
                        <label class="col-sm-2 col-form-label text-primary">
                            <strong>Wajah</strong>
                    </div>
                    
                    <!-- Hiperpigmentasi -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Hiperpigmentasi (Cloasma Gravidarum)</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Ya/Tidak, Area ...</small>
                            <textarea name="inspeksiwajah" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                         </div>
                    </div> 

                    <!-- Masalah Khusus -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Masalah Khusus</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Ekspresi wajah, apakah pucat dan bengkak. Hasil:</small>
                            <textarea name="masalahwajah" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                         </div>
                    </div> 

                    <!-- Bagian Mata -->

                    <div class="row mb-2">
                        <label class="col-sm-2 col-form-label text-primary">
                            <strong>Mata</strong>
                    </div>
                    
                    <!-- Konjungtiva  -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Konjungtiva</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Anemis/An-anemis. Hasil:</small>
                           <textarea name="konjungtiva" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                         </div>
                    </div> 

                    <!-- Sklera -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Sklera</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Ikterik/An-ikterik. Hasil:</small>
                            <textarea name="sklera" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                         </div>
                    </div> 

                <!-- Bagian Mulut -->

                    <div class="row mb-2">
                        <label class="col-sm-2 col-form-label text-primary">
                            <strong>Mulut</strong>
                    </div>
                    
                    <!-- Mukosa Bibir -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Mukosa Bibir</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Lembab/Kering. Hasil:</small>
                            <textarea name="mukosabibir" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                         </div>
                    </div> 

                    <!-- Sariawan -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Sariawan</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">(Ada/Tidak Ada). Hasil:</small>
                            <textarea name="sariawan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                         </div>
                    </div> 

                    <!-- Gigi Berlubang -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Gigi Berlubang</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Ya/Tidak. Hasil:</small>
                            <textarea name="gigiberlubang" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                         </div>
                    </div> 

                     <!-- Masalah Khusus -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Masalah Khusus</strong></label>

                            <div class="col-sm-10">
                                <textarea name="masalahkhususmulut" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                         </div>
                    </div>  
                                    
                     <!-- Bagian Leher -->

                    <div class="row mb-2">
                        <label class="col-sm-2 col-form-label text-primary">
                            <strong>Leher</strong>
                    </div>
                    
                    <!-- Distensi -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Distensi Vena Jugularis</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Ya/Tidak. Hasil:</small>
                            <textarea name="distensi" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                         </div>
                    </div> 

                    <!-- Kelenjar Tiroid -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Pembesaran Kelenjar Tiroid</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Ya/Tidak. Hasil:</small>
                            <textarea name="kelenjartiroid" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                         </div>
                    </div> 

                    <!-- Nyeri Menelan -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Nyeri Menelan</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Ya/Tidak. Hasil:</small>
                            <textarea name="nyerimenelan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                         </div>
                    </div> 

                     <!-- Riwayat Masalah Khusus -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Masalah Khusus</strong></label>

                            <div class="col-sm-10">
                                <textarea name="masalahkhususleher" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                         </div>
                    </div>  
                            
                     <!-- Bagian Dada -->

                    <div class="row mb-2">
                        <label class="col-sm-8 col-form-label text-primary">
                            <strong>Dada/Thorax</strong>
                    </div>
                    
                    <!-- Bunyi Jantung -->
                  
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Bunyi Jantung</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Normal atau apakah terdapat Mur-mur dan Gallop. Hasil:</small>
                            <textarea name="bunyijantung" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                         </div>
                    </div> 

                    <!-- Masalah Khusus -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Masalah Khusus</strong></label>

                            <div class="col-sm-10">
                                <textarea name="masalahkhususbunyijantung" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                         </div>
                    </div>

                    <!-- Sistem Pernapasan -->
                  
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Sistem Pernapasan</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Suara Napas (Vesikuler/Wheezing/Ronkhi). Hasil:</small>
                            <textarea name="sistempernapasan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                         </div>
                    </div> 

                    <!-- Masalah Khusus -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Masalah Khusus</strong></label>

                            <div class="col-sm-10">
                                <textarea name="masalahkhusussistempernapasan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                         </div>
                    </div>
                            
                    <!-- Bagian Payudara -->

                    <div class="row mb-2">
                        <label class="col-sm-2 col-form-label text-primary">
                            <strong>Payudara</strong>
                    </div>
                    
                    <!-- Asi -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Pengeluaran ASI/Kolostum</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Ya/Tidak. Hasil:</small>
                            <textarea name="pengeluaranasi" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                         </div>
                    </div> 

                    <!-- Inspeksi Puting -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Puting</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">(Eksverted/Inverted/Platnipple). Hasil:</small>
                            <textarea name="puting" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                         </div>
                    </div>

                     <!-- Masalah Khusus -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"><strong>Masalah Khusus</strong></label>

                            <div class="col-sm-10">
                                <textarea name="masalahkhususpayudadra" class="form-control" rows="5" style="overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                         </div>
                    </div>
                            
                <!-- Bagian Abdomen -->

                    <div class="row mb-3">
                        <label class="col-sm-9 col-form-label text-primary">
                            <strong>Abdomen</strong>
                        </label>    
                    </div>

                    <!-- Abdomen  -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Abdomen</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Apakah terdapat: Lignea Nigra/Striae Nigra/Striae Alba, Bekas Operasi. Hasil:</small>
                            <textarea name="abdomen" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                         </div>
                    </div>

                     <!-- Pemeriksaan Palpasi Abdomen  -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Pemeriksaan Palpasi Abdomen</strong></label>

                        <div class="col-sm-10">
                            <textarea name="pemeriksaanpalpasiabdomen" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                         </div>
                    </div>

                     <div class="row mb-3">
                        <label class="col-sm-9 col-form-label text-primary">
                            <strong>Uterus</strong>
                        </label>    
                    </div>

                    <!-- TFU -->
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-2 col-form-label"><strong>TFU</strong></label>
                        <div class="col-sm-4">
                            <div class="input-group">
                                <input type="text" class="form-control" name="inspeksitfu">
                                <span class="input-group-text">cm</span>
                        </div>    
                    </div>
                                
                    <!-- Kontraksi -->
                    <label class="col-sm-2 col-form-label"><strong>Kontraksi</strong></label>
                    <div class="col-sm-4">
                        <select class="form-select" name="inspeksikontraksi">
                            <option value="">Pilih</option>
                            <option value="Ya">Ya</option>
                            <option value="Tidak">Tidak</option> 
                        </select>
                    </div>    
                </div>

                    <!-- Leopold I -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Leopold I</strong></label>

                        <div class="col-sm-10">
                            <select class="form-select" name="leopoldi">
                                <option value="">Pilih</option>
                                <option value="Kepala">Kepala</option>
                                <option value="Bokong">Bokong</option>
                                <option value="Kosong">Kosong</option>
                            </select>
                         </div>
                    </div>

                    <!-- Leopold II -->

                    <div class="row mb-2">
                        <label class="col-sm-2 col-form-label">
                            <strong>Leopold II</strong>
                    </div>
                    
                    <!-- Kanan -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Kanan</strong></label>

                       <div class="col-sm-10">
                            <select class="form-select" name="kanan">
                                <option value="">Pilih</option>
                                <option value="Punggung">Punggung</option>
                                <option value="Bagian Kecil">Bagian Kecil</option>
                                <option value="Kepala">Kepala</option>
                            </select>
                         </div>
                    </div>

                    <!-- Kiri -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Kiri</strong></label>

                       <div class="col-sm-10">
                            <select class="form-select" name="kiri">
                                <option value="">Pilih</option>
                                <option value="Punggung">Punggung</option>
                                <option value="Bagian Kecil">Bagian Kecil</option>
                                <option value="Kepala">Kepala</option>
                            </select>
                         </div>
                    </div>        

                    <!-- Leopold III -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Leopold III</strong></label>

                       <div class="col-sm-10">
                            <select class="form-select" name="leopoldiii">
                                <option value="">Pilih</option>    
                                <option value="Kepala">Kepala</option>
                                <option value="Bokong">Bokong</option>
                                <option value="Kosong">Kosong</option>
                            </select>
                         </div>
                    </div>        

                    <!-- Leopold IV Penurunan Kepala-->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Leopold IV Penurunan Kepala</strong></label>

                       <div class="col-sm-10">
                            <select class="form-select" name="leopoldiv" required>
                                <option value="">Pilih</option>
                                <option value="Sudah">Sudah</option>
                                <option value="Belum">Belum</option>
                            </select>
                         </div>
                    </div>        

                    <!-- Pemeriksaan DJJ -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Pemeriksaan DJJ</strong></label>

                        <div class="col-sm-10">
                            <div class="input-group">
                                <input type="text" class="form-control" name="pemeriksaandjj">
                                <span class="input-group-text">Frek</span>
                            </div>
                                <small class="form-text" style="color: red;">(Normal 120-160/bradikardi, 160-180/tachikardi < 120) </small>
                            </div>
                    </div>
                    
                    <!-- Intensitas -->
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-2 col-form-label"><strong>Intensitas</strong></label>
                        <div class="col-sm-4">
                            <div class="input-group">
                                <input type="text" class="form-control" name="intensitas">
                                <span class="input-group-text">Intensitas</span>
                        </div>    
                    </div>
                                
                    <!-- Keteraturan -->
                    <label class="col-sm-2 col-form-label"><strong>Keteraturan</strong></label>
                    <div class="col-sm-4">
                            <div class="input-group">
                                <input type="text" class="form-control" name="keteraturan">
                                <span class="input-group-text">Keteraturan</span>
                        </div>    
                    </div>  
                </div> 
                    
                    <!-- Status Janin -->
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-2 col-form-label"><strong>Status Janin</strong></label>
                        <div class="col-sm-4">
                                <select class="form-select" name="statusjanin">
                                <option value="">Pilih</option>
                                <option value="Hidup">Hidup</option>
                                <option value="Tidak">Tidak</option> 
                            </select>
                        </div>    
                                
                    <!-- Jumlah -->
                    <label class="col-sm-2 col-form-label"><strong>Jumlah</strong></label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" name="jumlah">
                    </div>    
                </div>

                    <style>
                    .table-abdomen {
                        table-layout: fixed;
                        width:100%
                    }

                    .table-abdomen td,
                    .table-abdomen th {
                        word-wrap: break-word;
                        white-space: normal;
                        vertical-align: top;
                    }

                
                    </style>

                    <table class="table table-bordered table-abdomen">

                    <tbody>
                        <tr>
                            <td colspan="2"><strong>Uterus</strong></td>
                        </tr>

                        <tr>
                            <td><strong>TFU</strong></td>
                            <td><?= $row['tfu'] ?? ''; ?> cm</td>
                        </tr>

                        <tr>
                            <td></td>
                            <td>Kontraksi: <?= $row['kontraksi'] ?? ''; ?></td>
                        </tr>

                        <tr>
                            <td><strong>Leopold I<strong></td>
                            <td><?= $row['leopoldi'] ?? ''; ?></td>
                        </tr>

                        <tr>
                            <td><strong>Leopold II<strong></td>
                            <td>Kanan: <?= $row['kanan'] ?? ''; ?></td>
                        </tr>

                        <tr>
                            <td></td>
                            <td>Kiri: <?= $row['kiri'] ?? ''; ?></td>
                        </tr>

                        <tr>
                            <td><strong>Leopold III<strong></td>
                            <td><?= $row['leopoldiii'] ?? ''; ?></td>
                        </tr>

                        <tr>
                            <td><strong>Leopold IV Penurunan Kepala<strong></td>
                            <td><?= $row['leopoldiv'] ?? ''; ?></td>
                        </tr>

                        <tr>
                            <td><strong>Leopold I<strong></td>
                            <td><?= $row['leopoldi'] ?? ''; ?></td>
                        </tr>

                        <tr>
                            <td><strong>Pemeriksaan DJJ</strong></td>
                        <td>
                            <?= $row['pemeriksaandjj'] ?? ''; ?> Frek (normal 120-160),
                            <?= $row['intensitas'] ?? ''; ?> Intensitas,
                            <?= $row['keteraturan'] ?? ''; ?> Keteraturan
                        </td>
                        </tr>

                        <tr>
                            <td><strong>Status Janin</strong></td>
                            <td><?= $row['statusjanin'] ?? ''; ?></td>
                        </tr>

                        <tr>
                            <td></td>
                            <td>Jumlah: <?= $row['jumlah'] ?? ''; ?></td>
                        </tr>

                    </tbody>
                    </table>

                    <!-- Bagian Ekstremitas -->

                    <div class="row mb-2">
                        <label class="col-sm-8 col-form-label text-primary">
                            <strong>Ekstremitas</strong>
                    </div>
                    
                    <!-- Ekstremitas Atas -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Ekstremitas Atas</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Apakah terdapat edema (ya/tidak), rasa kesemutan/baal (ya/tidak). Hasil:</small>
                            <textarea name="ekstremitasatas" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                         </div>
                    </div>

                    <!-- Ekstremitas Bawah -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Ekstremitas Bawah</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Apakah terdapat edema (ya/tidak), varises (ya/tidak), refleks pattela (+/-). Hasil:</small>
                            <textarea name="ekstremitasbawah" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                         </div>
                    </div>

                    <!-- Bagian Vagina -->

                    <div class="row mb-2">
                        <label class="col-sm-8 col-form-label text-primary">
                            <strong>Vagina</strong>
                    </div>
                    
                    <!-- Persiapan Perineum -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Vagina</strong></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Persiapan perineum. Hasil:</small>
                            <textarea name="persiapanperineum" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                         </div>
                    </div> 

                    <!-- Pengeluaran Pervaginam -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"></label>

                        <div class="col-sm-10">
                            <small class="form-text" style="color: red;">Pengeluaran Pervaginam. Hasil:</small>
                            <textarea name="pengeluaranpervaginam" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                         </div>
                    </div> 

                <h5 class="card-title"><strong>Program Terapi</strong></h5>

                <!-- General Form Elements -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

                    <div class="row mb-2">
                        <label class="col-sm-6 col-form-label text-primary">
                            <strong>Obat-obatan yang dikonsumsi Saat Ini</strong>
                    </div>

                <!-- Bagian Jenis Obat -->
                <div class="row mb-3">
                    <label for="obat" class="col-sm-2 col-form-label"><strong>Obat</strong></label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="obat">
                         </div>
                    </div>

                <!-- Bagian Dosis -->
                <div class="row mb-3">
                    <label for="dosis" class="col-sm-2 col-form-label"><strong>Dosis</strong></label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="dosis">
                         </div>
                    </div>
                    
                <!-- Bagian Kegunaan -->
                <div class="row mb-3">
                    <label for="kegunaan" class="col-sm-2 col-form-label"><strong>Kegunaan</strong></label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="kegunaan">
                         </div>
                    </div>
                
                <!-- Bagian Cara Pemberian -->
                <div class="row mb-3">
                    <label for="jenisobat" class="col-sm-2 col-form-label"><strong>Cara Pemberian</strong></label>
                    <div class="col-sm-10">
                        <textarea name="carapemberian" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                         </div>
                    </div> 
                    
                <!-- Bagian Button -->    
                <div class="row mb-3">
                    <div class="col-sm-12 justify-content-end d-flex">
                        <button type="submit" name="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>     
                    
                    <h5 class="card-title mt-2"><strong>Program Terapi</strong></h5>

                    <style>
                    .table-terapi {
                        table-layout: fixed;
                        width:100%
                    }

                    .table-terapi td,
                    .table-terapi th {
                        word-wrap: break-word;
                        white-space: normal;
                        vertical-align: top;
                    }
                    </style>

                    <table class="table table-bordered table-terapi">
                        <thead>
                            <tr>
                                <th class="text-center">Obat</th>
                                <th class="text-center">Dosis</th>
                                <th class="text-center">kegunaan</th>
                                <th class="text-center">Cara Pemberian</th>
                        </tr>
                        </thead>

                    <tbody>

                    <?php
                    if(!empty($data)){
                        foreach($data as $row){
                            echo "<tr>
                            <td>".nlrbr($row['obat'])."</td>
                            <td>".nlrbr($row['dosis'])."</td>
                            <td>".nlrbr($row['kegunaan'])."</td>
                            <td>".nlrbr($row['carapemberian'])."</td>
                            </tr>";
                        }
                    }
                    ?>

                    </tbody>
                    </table>

                <!-- Bagian Hasil Pemeriksaan -->    

                <div class="row mb-2">
                    <label class="col-sm-6 col-form-label text-primary">
                        <strong>Hasil Pemeriksaan Penunjang dan Laboratorium:</strong>
                </div>

                <!-- Bagian Pemeriksaan -->
                <div class="row mb-3">
                    <label for="pemeriksaan" class="col-sm-2 col-form-label"><strong>Pemeriksaan</strong></label>
                    <div class="col-sm-10">
                        <textarea name="pemeriksaan" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                         </div>
                    </div> 

                <!-- Bagian Hasil -->
                <div class="row mb-3">
                    <label for="hasil" class="col-sm-2 col-form-label"><strong>Hasil</strong></label>
                    <div class="col-sm-10">
                        <textarea name="hasil" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                         </div>
                    </div> 
                    
                <!-- Bagian Nilai Normal -->
                <div class="row mb-3">
                    <label for="nilainormal" class="col-sm-2 col-form-label"><strong>Nilai Normal</strong></label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="nilainormal">
                         </div>
                    </div>

                <!-- Bagian Button -->    
                <div class="row mb-3">
                    <div class="col-sm-12 justify-content-end d-flex">
                        <button type="submit" name="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>  

                <h5 class="card-title"><strong>Hasil Pemeriksaan Penunjang dan Laboratorium:</strong></h5>
                
                <style>
                    .table-pemeriksaan {
                        table-layout: fixed;
                        width:100%
                    }

                    .table-pemeriksaan td,
                    .table-pemeriksaan th {
                        word-wrap: break-word;
                        white-space: normal;
                        vertical-align: top;
                    }
                    </style>

                    <table class="table table-bordered table-pemeriksaan">
                        <thead>
                            <tr>
                                <th class="text-center">Pemeriksaan</th>
                                <th class="text-center">Hasil</th>
                                <th class="text-center">Nilai Normal</th>
                        </tr>
                        </thead>

                    <tbody>

                    <?php
                    if(!empty($data)){
                        foreach($data as $row){
                            echo "<tr>
                            <td>".nlrbr($row['pemeriksaan'])."</td>
                            <td>".nlrbr($row['hasil'])."</td>
                            <td>".nlrbr($row['nilainormal'])."</td>
                            </tr>";
                        }
                    }
                    ?>

                    </tbody>
                    </table>

    <?php include "tab_navigasi.php"; ?>

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
                    <td>
                        <select class="form-select form-select-sm" name="persalinan[${index}][jeniskelamin]" <?= $ro_select ?> >
                            <option value="">Pilih</option>
                            <option value="Perempuan" ${data?.jeniskelamin === 'Perempuan' ? 'selected' : ''}>Perempuan</option>
                            <option value="Laki-laki" ${data?.jeniskelamin === 'Laki-laki' ? 'selected' : ''}>Laki-laki</option>
                        </select>
                    </td>
                    <td><input type="text" class="form-control form-control-sm" name="persalinan[${index}][caralahir]" value="${data?.tahun ?? ''}" <?= $ro ?>></td>
                    <td><input type="text" class="form-control form-control-sm" name="persalinan[${index}][bblahir]" value="${data?.jenis ?? ''}" <?= $ro ?>></td>
                    <td><input type="text" class="form-control form-control-sm" name="persalinan[${index}][keadaan]" value="${data?.penolong ?? ''}" <?= $ro ?>></td>
                    <td><input type="text" class="form-control form-control-sm" name="persalinan[${index}][umur]" value="${data?.masalah ?? ''}" <?= $ro ?>></td>
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
            const existingData = <?= json_encode($existing_data) ?>;
        </script>

</section>
</main>
