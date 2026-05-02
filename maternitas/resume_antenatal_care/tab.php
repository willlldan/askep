<?php
$jenismaternitas = $_GET['jenismaternitas'] ?? 'resume_antenatal_care';
$submission_id = $_GET['submission_id'] ?? null;

$titles = [
    'antenatal' => 'Pengkajian Asuhan Keperawatan Antenatal Care',
    'pascapartum' => 'Pengkajian Asuhan Keperawatan Pascapartum',
    'resume_antenatal_care' => 'Resume Asuhan Keperawatan Antenatal Care',
    'inranatal' => 'Pengkajian Asuhan Keperawatan Inranatal Care',
    'ginekologi' => 'Pengkajian Asuhan Keperawatan Ginekologi'
];
?>

<div class="pagetitle">
    <h1><strong><?= $titles[$jenismaternitas] ?? 'Resume Asuhan Keperawatan Antenatal Care' ?></strong></h1>
</div>
<br>
    <ul class="nav nav-tabs custom-tabs">
        <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'identitas' ? 'active' : '' ?>"
        href="index.php?page=maternitas/resume_antenatal_care&tab=identitas">
        Identitas
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'pengkajian_anamnesa' ? 'active' : '' ?>"
        href="index.php?page=maternitas/resume_antenatal_care&tab=pengkajian_anamnesa">
        Anamnesa & Antropometri
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'pengkajian_tanda_vital' ? 'active' : '' ?>"
        href="index.php?page=maternitas/resume_antenatal_care&tab=pengkajian_tanda_vital">
        TTV & Pemeriksaan Umum
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'pemeriksaan_fisik' ? 'active' : '' ?>"
        href="index.php?page=maternitas/resume_antenatal_care&tab=pemeriksaan_fisik">
        Pemeriksaan Fisik
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'terapi_lab' ? 'active' : '' ?>"
        href="index.php?page=maternitas/resume_antenatal_care&tab=terapi_lab">
        Terapi  Lab
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'lainnya' ? 'active' : '' ?>"
        href="index.php?page=maternitas/resume_antenatal_care&tab=lainnya">
        Lainnya
        </a>
    </li>
    <!-- <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'program_terapi' ? 'active' : '' ?>"
        href="index.php?page=maternitas/resume_antenatal_care&tab=program_terapi">
        Program Terapi
        </a>
    </li> -->

    <!-- <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'pengkajian' ? 'active' : '' ?>"
        href="index.php?page=maternitas/resume_antenatal_care&tab=pengkajian">
        Pengkajian
        </a>
    </li> -->

    <!-- <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'diagnosa_keperawatan' ? 'active' : '' ?>"
        href="index.php?page=maternitas/resume_antenatal_care&tab=diagnosa_keperawatan">
        Diagnosa Keperawatan
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'intervensi_keperawatan' ? 'active' : '' ?>"
        href="index.php?page=maternitas/resume_antenatal_care&tab=intervensi_keperawatan">
        Intervensi Keperawatan
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'implementasi_keperawatan' ? 'active' : '' ?>"
       href="index.php?page=maternitas/resume_antenatal_care&tab=implementasi_keperawatan">
        Implementasi Keperawatan
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'evaluasi_keperawatan' ? 'active' : '' ?>"
        href="index.php?page=maternitas/resume_antenatal_care&tab=evaluasi_keperawatan">
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