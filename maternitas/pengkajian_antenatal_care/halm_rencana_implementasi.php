<?php
require_once "koneksi.php";
require_once "utils.php";

$form_id       = 1;
$user_id       = $_SESSION['id_user'];
$section_name  = 'diagnosa_keperawatan';
$section_label = 'Diagnosa Keperawatan';

$submission    = getSubmission($user_id, $form_id, $mysqli);
$existing_data = $submission ? getSectionData($submission['id'], $section_name, $mysqli) : [];

// Load existing dynamic rows
$existing_diagnosa = $existing_data['diagnosa'] ?? [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isLocked($submission)) {
        redirectWithMessage($_SERVER['REQUEST_URI'], 'error', 'Data tidak dapat diubah karena sedang dalam proses review.');
    }

    // Proses dynamic rows diagnosa
    $diagnosa = [];
    if (!empty($_POST['diagnosa'])) {
        foreach ($_POST['diagnosa'] as $index => $row) {
            if (empty($row['diagnosa']) && empty($row['tgl_ditemukan']) && empty($row['tgl_teratasi'])) {
                continue;
            }
            $diagnosa[] = [
                'diagnosa'      => $row['diagnosa']      ?? '',
                'tgl_ditemukan' => $row['tgl_ditemukan'] ?? '',
                'tgl_teratasi'  => $row['tgl_teratasi']  ?? '',
            ];
        }
    }

    $data = [
        'diagnosa' => $diagnosa,
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

    <?php include "maternitas/pengkajian_antenatal_care/tab.php"; ?>

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

        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-1"><strong>Intervensi Keperawatan</strong></h5>

                <!-- General Form Elements -->
                <form class="needs-validation" novalidate action="" method="POST" enctype="multipart/form-data">

                    <!-- Bagian Diagnosa -->

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Diagnosa</strong></label>

                        <div class="col-sm-9">
                            <textarea name="diagnosa" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        </div>
                    </div>

                    <!-- Bagian Tujuan dan Kriteria Hasil -->

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Tujuan dan Kriteria Hasil</strong></label>

                        <div class="col-sm-9">
                            <textarea name="tujuandankriteria" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        </div>
                    </div>

                    <!-- Bagian Intervensi -->

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label"><strong>Intervensi</strong></label>

                        <div class="col-sm-9">
                            <textarea name="intervensi" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                        </div>
                    </div>

                    <!-- Bagian Button -->
                    <div class="row mb-3">
                        <div class="col-sm-11 justify-content-end d-flex">
                            <button type="submit" name="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </div>

                    <h5 class="card-title mt-2"><strong>Intervensi Keperawatan</strong></h5>

                    <style>
                        .table-pemeriksaan {
                            table-layout: fixed;
                            width: 100%
                        }

                        .table-pemeriksaan td,
                        .table-pemeriksaan th {
                            word-wrap: break-word;
                            white-space: normal;
                            vertical-align: top;
                        }
                    </style>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center">Diagnosa</th>
                                <th class="text-center">Tujuan dan Kriteria Hasil</th>
                                <th class="text-center">Intervensi</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php
                            if (!empty($data)) {
                                foreach ($data as $row) {
                                    echo "<tr>
                            <td>" . $row['diagnosa'] . "</td>
                            <td>" . $row['tujuan'] . "</td>
                            <td>" . $row['intervensi'] . "</td>
                            </tr>";
                                }
                            }
                            ?>

                        </tbody>
                    </table>

                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title mb-1"><strong>Implementasi Keperawatan</strong></h5>
                            <!-- Bagian No. DX -->

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label"><strong>No. DX</strong></label>

                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="nodx">
                                </div>
                            </div>

                            <!-- Bagian Hari/Tanggal -->

                            <div class="row mb-3">
                                <label for="hari_tgl" class="col-sm-2 col-form-label"><strong>Hari/Tanggal</strong></label>

                                <div class="col-sm-9">
                                    <input type="datetime-local" class="form-control" id="hari_tgl" name="hari_tgl">
                                </div>
                            </div>

                            <!-- Bagian Jam -->

                            <div class="row mb-3">
                                <label for="jam" class="col-sm-2 col-form-label"><strong>Jam</strong></label>

                                <div class="col-sm-9">
                                    <input type="time" class="form-control" id="jam" name="jam">
                                </div>
                            </div>

                            <!-- Bagian Implementasi dan Hasil -->

                            <!-- Implementasi -->
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label"><strong>Implementasi</strong></label>
                                <div class="col-sm-3">
                                    <div class="input-group">
                                        <textarea name="implementasi" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                                    </div>
                                </div>

                                <!-- Hasil -->
                                <label class="col-sm-2 col-form-label"><strong>Hasil</strong></label>
                                <div class="col-sm-3">
                                    <div class="input-group">
                                        <textarea name="hasil" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-sm-9 offset-sm-2">
                                    <textarea class="form-control" rows="2" placeholder="Kolom Ini menampilkan revisi dari dosen. Jika ada revisi, tetap semangat mengerjakan!" style="display:block; overflow:hidden; resize: none;"
                                        oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';" readonly></textarea>
                                </div>
                            </div>


                            <!-- Bagian Button -->
                            <div class="row mb-3">
                                <div class="col-sm-11 justify-content-end d-flex">
                                    <button type="submit" name="submit" class="btn btn-primary">Simpan</button>
                                </div>
                            </div>

                            <h5 class="card-title mt-2"><strong>Implementasi Keperawatan</strong></h5>

                            <style>
                                .table-pemeriksaan {
                                    table-layout: fixed;
                                    width: 100%
                                }

                                .table-pemeriksaan td,
                                .table-pemeriksaan th {
                                    word-wrap: break-word;
                                    white-space: normal;
                                    vertical-align: top;
                                }
                            </style>

                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center">No. Dx </th>
                                        <th class="text-center">Hari/Tanggal</th>
                                        <th class="text-center">Jam</th>
                                        <th class="text-center">Implementasi</th>
                                        <th class="text-center">Hasil</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    <?php
                                    if (!empty($data)) {
                                        foreach ($data as $row) {
                                            echo "<tr>
                            <td>" . $row['no_dx'] . "</td>
                            <td>" . $row['hari_tgl'] . "</td>
                            <td>" . $row['jam'] . "</td>
                            <td>" . $row['implementasi'] . "</td>
                            <td>" . $row['hasil'] . "</td>
                            </tr>";
                                        }
                                    }
                                    ?>

                                </tbody>
                            </table>

                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title mb-1"><strong>Evaluasi Keperawatan</strong></h5>

                                    <!-- Bagian No. DX -->

                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label"><strong>No. DX</strong></label>

                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="nodx">
                                        </div>
                                    </div>

                                    <!-- Bagian Hari/Tanggal -->

                                    <div class="row mb-3">
                                        <label for="hari_tgl" class="col-sm-2 col-form-label"><strong>Hari/Tanggal</strong></label>

                                        <div class="col-sm-9">
                                            <input type="datetime-local" class="form-control" id="hari_tgl" name="hari_tgl">
                                        </div>
                                    </div>

                                    <!-- Bagian Jam -->

                                    <div class="row mb-3">
                                        <label for="jam" class="col-sm-2 col-form-label"><strong>Jam</strong></label>

                                        <div class="col-sm-9">
                                            <input type="time" class="form-control" id="jam" name="jam">
                                        </div>
                                    </div>

                                    <!-- Bagian Evaluasi -->

                                    <div class="row mb-2">
                                        <label class="col-sm-2 col-form-label">
                                            <strong>Evaluasi</strong>
                                        </label>
                                    </div>

                                    <!-- S -->
                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label"><strong>S (Subjective)</strong></label>

                                        <div class="col-sm-9">
                                            <textarea name="evaluasi_s" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                                        </div>

                                    </div>

                                    <!-- O -->
                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label"><strong>O (Objective)</strong></label>

                                        <div class="col-sm-9">
                                            <textarea name="evaluasi_o" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                                        </div>
                                    </div>

                                    <!-- A -->
                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label"><strong>A (Assessment)</strong></label>

                                        <div class="col-sm-9">
                                            <textarea name="evaluasi_a" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                                        </div>
                                    </div>

                                    <!-- P -->
                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label"><strong>P (Plan)</strong></label>

                                        <div class="col-sm-9">
                                            <textarea name="evaluasi_p" class="form-control" rows="3" cols="30" style="display:block; overflow:hidden; resize: none;" oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px';"></textarea>
                                        </div>
                                    </div>

                                    <!-- comment -->
                                        </div>
                                    </div>

                                    <!-- Bagian Button -->
                                    <div class="row mb-3">
                                        <div class="col-sm-11 d-flex justify-content-end gap-2">
                                            <button type="submit" name="submit" class="btn btn-primary">Simpan</button>
                                            <button type="submit" name="cetak" class="btn btn-success">Cetak</button>
                                        </div>
                                    </div>


                                    <h5 class="card-title mt-2"><strong>Evaluasi Keperawatan</strong></h5>

                                    <style>
                                        .table-evaluasi {
                                            table-layout: fixed;
                                            width: 100%
                                        }

                                        .table-evaluasi td,
                                        .table-evaluasi th {
                                            word-wrap: break-word;
                                            white-space: normal;
                                            vertical-align: top;
                                        }
                                    </style>

                                    <table class="table table-bordered table-evaluasi">
                                        <thead>
                                            <tr>
                                                <th class="text-center">No. Dx </th>
                                                <th class="text-center">Hari/Tanggal</th>
                                                <th class="text-center">Jam</th>
                                                <th class="text-center">Evaluasi</th>
                                            </tr>
                                        </thead>

                                        <tbody>

                                            <?php
                                            if (!empty($data)) {
                                                foreach ($data as $row) {
                                                    echo "<tr>
                            <td>" . $row['no_dx'] . "</td>
                            <td>" . $row['hari_tgl'] . "</td>
                            <td>" . $row['jam'] . "</td>
                            <td>
                            <b>S :</b> " . $row['evaluasi_s'] . "<br>
                            <b>O :</b> " . $row['evaluasi_o'] . "<br>
                            <b>A :</b> " . $row['evaluasi_a'] . "<br>
                            <b>P :</b> " . $row['evaluasi_p'] . "<br>
                            </td>
                            </tr>";
                                                }
                                            }
                                            ?>

                                        </tbody>
                                    </table>

    </section>
</main>