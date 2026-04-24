<?php
$jenismaternitas = $_GET['jenismaternitas'] ?? 'inranatal';
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
    <h1><strong><?= $titles[$jenismaternitas] ?? 'Pengkajian Asuhan Keperawatan Inranatal Care' ?></strong></h1>
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
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'umum' ? 'active' : '' ?>"
            href="index.php?page=maternitas/pengkajian_inranatal_care&jenismaternitas=<?= $jenismaternitas ?>&tab=umum<?php if($submission_id) echo '&submission_id=' . $submission_id; ?>">
        Data Umum
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'riwayat_persalinan' ? 'active' : '' ?>"
            href="index.php?page=maternitas/pengkajian_inranatal_care&jenismaternitas=<?= $jenismaternitas ?>&tab=riwayat_persalinan<?php if($submission_id) echo '&submission_id=' . $submission_id; ?>">
        Riwayat Persalinan
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'terapi_lab' ? 'active' : '' ?>"
            href="index.php?page=maternitas/pengkajian_inranatal_care&jenismaternitas=<?= $jenismaternitas ?>&tab=terapi_lab<?php if($submission_id) echo '&submission_id=' . $submission_id; ?>">
        Terapi Lab
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'laporanpersalinan' ? 'active' : '' ?>"
            href="index.php?page=maternitas/pengkajian_inranatal_care&jenismaternitas=<?= $jenismaternitas ?>&tab=laporanpersalinan<?php if($submission_id) echo '&submission_id=' . $submission_id; ?>">
        Laporan Persalinan
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'analisa_data' ? 'active' : '' ?>"
            href="index.php?page=maternitas/pengkajian_inranatal_care&jenismaternitas=<?= $jenismaternitas ?>&tab=analisa_data<?php if($submission_id) echo '&submission_id=' . $submission_id; ?>">
        Analisa Data
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'lainnya' ? 'active' : '' ?>"
            href="index.php?page=maternitas/pengkajian_inranatal_care&jenismaternitas=<?= $jenismaternitas ?>&tab=lainnya<?php if($submission_id) echo '&submission_id=' . $submission_id; ?>">
        Lainnya
        </a>
    </li>

    <!-- <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'diagnosa_keperawatan' ? 'active' : '' ?>"
        href="?page=maternitas/pengkajian_inranatal_care&tab=diagnosa_keperawatan">
        Diagnosa keperawatan
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'intervensi_keperawatan' ? 'active' : '' ?>"
        href="?page=maternitas/pengkajian_inranatal_care&tab=intervensi_keperawatan">
        Intervensi keperawatan
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'implementasi_keperawatan' ? 'active' : '' ?>"
        href="?page=maternitas/pengkajian_inranatal_care&tab=implementasi_keperawatan">
        Implementasi keperawatan
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'evaluasi_keperawatan' ? 'active' : '' ?>"
        href="?page=maternitas/pengkajian_inranatal_care&tab=evaluasi_keperawatan">
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