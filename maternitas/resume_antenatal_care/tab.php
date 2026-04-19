<?php
$jenismaternitas = $_GET['jenismaternitas'] ?? 'resume';
$submission_id = $_GET['submission_id'] ?? null;

$titles = [
    'antenatal' => 'Pengkajian Asuhan Keperawatan Antenatal Care',
    'pascapartum' => 'Pengkajian Asuhan Keperawatan Pascapartum',
    'resume' => 'Resume Asuhan Keperawatan Antenatal Care',
    'inranatal' => 'Pengkajian Asuhan Keperawatan Inranatal Care',
    'ginekologi' => 'Pengkajian Asuhan Keperawatan Ginekologi'
];
?>


<div class="card">
    <div class="card-body">
        <div class="row mb-3 mt-3   ">
            <label for="jenismaternitas" class="col-sm-2 col-form-label"><strong>Maternitas</strong></label>
            <div class="col-sm-9">

                <select class="form-select" name="jenismaternitas"
                    onchange="window.location=this.value" required>

                    <option value="">Pilih</option>

                    <option value="index.php?page=maternitas/pengkajian_antenatal_care&jenismaternitas=antenatal"
                        <?= $jenismaternitas == 'antenatal' ? 'selected' : '' ?>>
                        Pengkajian Antenatal Care
                    </option>

                    <option value="index.php?page=maternitas/pengkajian_pascapartum&jenismaternitas=pascapartum"
                        <?= $jenismaternitas == 'pascapartum' ? 'selected' : '' ?>>
                        Pengkajian Pascapartum
                    </option>

                    <option value="index.php?page=maternitas/resume_antenatal_care&jenismaternitas=resume"
                        <?= $jenismaternitas == 'resume' ? 'selected' : '' ?>>
                        Resume Antenatal Care
                    </option>

                    <option value="index.php?page=maternitas/pengkajian_inranatal_care&jenismaternitas=inranatal"
                        <?= $jenismaternitas == 'inranatal' ? 'selected' : '' ?>>
                        Pengkajian Inranatal Care
                    </option>

                    <option value="index.php?page=maternitas/pengkajian_ginekologi&jenismaternitas=ginekologi"
                        <?= $jenismaternitas == 'ginekologi' ? 'selected' : '' ?>>
                        Pengkajian Ginekologi
                    </option>

                </select>
                <div class="invalid-feedback">
                    Harap isi Jenis Maternitas.
                </div>
            </div>
        </div>

    </div>
</div>

<div class="pagetitle">
    <h1><strong><?= $titles[$jenismaternitas] ?? 'Resume Asuhan Keperawatan Antenatal Care' ?></strong></h1>
</div>
<br>

        <!-- <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Home</a></li>
          <li class="breadcrumb-item active">Dashboard</li>
        </ol>
        </nav> -->
    </div><!-- End Page Title -->
    <br>

    <ul class="nav nav-tabs custom-tabs">
        <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'identitas' ? 'active' : '' ?>"
<<<<<<< HEAD
        href="index.php?page=maternitas/resume_antenatal_care&tab=identitas">
=======
        href="index.php?page=maternitas/resume_antenatal_care&jenismaternitas=<?= $jenismaternitas ?>&tab=identitas">
>>>>>>> 430a53a4929ab49113ea8456ae4229d729249d2b
        Identitas
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'pengkajian_anamnesa' ? 'active' : '' ?>"
<<<<<<< HEAD
        href="index.php?page=maternitas/resume_antenatal_care&tab=pengkajian_anamnesa">
=======
        href="index.php?page=maternitas/resume_antenatal_care&jenismaternitas=<?= $jenismaternitas ?>&tab=pengkajian_anamnesa">
>>>>>>> 430a53a4929ab49113ea8456ae4229d729249d2b
        Anamnesa & Antropometri
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'pengkajian_tanda_vital' ? 'active' : '' ?>"
<<<<<<< HEAD
        href="index.php?page=maternitas/resume_antenatal_care&tab=pengkajian_tanda_vital">
=======
        href="index.php?page=maternitas/resume_antenatal_care&jenismaternitas=<?= $jenismaternitas ?>&tab=pengkajian_tanda_vital">
>>>>>>> 430a53a4929ab49113ea8456ae4229d729249d2b
        TTV & Pemeriksaan Umum
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'pemeriksaan_fisik' ? 'active' : '' ?>"
<<<<<<< HEAD
        href="index.php?page=maternitas/resume_antenatal_care&tab=pemeriksaan_fisik">
=======
        href="index.php?page=maternitas/resume_antenatal_care&jenismaternitas=<?= $jenismaternitas ?>&tab=pemeriksaan_fisik">
>>>>>>> 430a53a4929ab49113ea8456ae4229d729249d2b
        Pemeriksaan Fisik
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'terapi_lab' ? 'active' : '' ?>"
<<<<<<< HEAD
        href="index.php?page=maternitas/resume_antenatal_care&tab=terapi_lab">
=======
        href="index.php?page=maternitas/resume_antenatal_care&jenismaternitas=<?= $jenismaternitas ?>&tab=terapi_lab">
>>>>>>> 430a53a4929ab49113ea8456ae4229d729249d2b
        Terapi  Lab
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'lainnya' ? 'active' : '' ?>"
<<<<<<< HEAD
        href="index.php?page=maternitas/resume_antenatal_care&tab=lainnya">
=======
        href="index.php?page=maternitas/resume_antenatal_care&jenismaternitas=<?= $jenismaternitas ?>&tab=lainnya">
>>>>>>> 430a53a4929ab49113ea8456ae4229d729249d2b
        Lainnya
        </a>
    </li>
    <!-- <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'program_terapi' ? 'active' : '' ?>"
<<<<<<< HEAD
        href="index.php?page=maternitas/resume_antenatal_care&tab=program_terapi">
=======
        href="index.php?page=maternitas/resume_antenatal_care&jenismaternitas=<?= $jenismaternitas ?>&tab=program_terapi">
>>>>>>> 430a53a4929ab49113ea8456ae4229d729249d2b
        Program Terapi
        </a>
    </li> -->

    <!-- <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'pengkajian' ? 'active' : '' ?>"
<<<<<<< HEAD
        href="index.php?page=maternitas/resume_antenatal_care&tab=pengkajian">
=======
        href="index.php?page=maternitas/resume_antenatal_care&jenismaternitas=<?= $jenismaternitas ?>&tab=pengkajian">
>>>>>>> 430a53a4929ab49113ea8456ae4229d729249d2b
        Pengkajian
        </a>
    </li> -->

    <!-- <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'diagnosa_keperawatan' ? 'active' : '' ?>"
<<<<<<< HEAD
        href="index.php?page=maternitas/resume_antenatal_care&tab=diagnosa_keperawatan">
=======
        href="index.php?page=maternitas/resume_antenatal_care&jenismaternitas=<?= $jenismaternitas ?>&tab=diagnosa_keperawatan">
>>>>>>> 430a53a4929ab49113ea8456ae4229d729249d2b
        Diagnosa Keperawatan
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'intervensi_keperawatan' ? 'active' : '' ?>"
<<<<<<< HEAD
        href="index.php?page=maternitas/resume_antenatal_care&tab=intervensi_keperawatan">
=======
        href="index.php?page=maternitas/resume_antenatal_care&jenismaternitas=<?= $jenismaternitas ?>&tab=intervensi_keperawatan">
>>>>>>> 430a53a4929ab49113ea8456ae4229d729249d2b
        Intervensi Keperawatan
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'implementasi_keperawatan' ? 'active' : '' ?>"
<<<<<<< HEAD
       href="index.php?page=maternitas/resume_antenatal_care&tab=implementasi_keperawatan">
=======
       href="index.php?page=maternitas/resume_antenatal_care&jenismaternitas=<?= $jenismaternitas ?>&tab=implementasi_keperawatan">
>>>>>>> 430a53a4929ab49113ea8456ae4229d729249d2b
        Implementasi Keperawatan
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'evaluasi_keperawatan' ? 'active' : '' ?>"
<<<<<<< HEAD
        href="index.php?page=maternitas/resume_antenatal_care&tab=evaluasi_keperawatan">
=======
        href="index.php?page=maternitas/resume_antenatal_care&jenismaternitas=<?= $jenismaternitas ?>&tab=evaluasi_keperawatan">
>>>>>>> 430a53a4929ab49113ea8456ae4229d729249d2b
        Evaluasi keperawatan
        </a>
    </li> -->

    </ul>

<style>
    .custom-tabs {
        border-bottom: 1px solid #dee2e6;
    }

    .custom-tabs .nav-link {
        border: none;
        background: transparent;
        color: #f6f9ff;
        font-weight: 500;
        padding: 10px 20px;
    }

    .custom-tabs .nav-link:hover {
        color: #4154f1;
    }

    .custom-tabs .nav-link.active {
        border: none;
        border-bottom: 3px solid #4154f1;
        color: #4154f1;
        font-weight: 600;
        background: transparent;
    }
</style>