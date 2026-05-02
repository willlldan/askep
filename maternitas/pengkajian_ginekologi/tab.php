<?php
$jenismaternitas = $_GET['jenismaternitas'] ?? 'ginekologi';
$submission_id = $_GET['submission_id'] ?? null;

$titles = [
    'antenatal' => 'Pengkajian Asuhan Keperawatan Antenatal Care',
    'pascapartum' => 'Pengkajian Asuhan Keperawatan Pascapartum',
    'resume' => 'Resume Asuhan Keperawatan Antenatal Care',
    'inranatal' => 'Pengkajian Asuhan Keperawatan Inranatal Care',
    'ginekologi' => 'Pengkajian Asuhan Keperawatan Ginekologi'
];
?>

<div class="pagetitle">
    <h1><strong><?= $titles[$jenismaternitas] ?? 'Pengkajian Asuhan Keperawatan Inranatal Care' ?></strong></h1>
</div>
<br>

   <ul class="nav nav-tabs custom-tabs">

    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? 'demografi') == 'demografi' ? 'active' : '' ?>"
        href="?page=maternitas/pengkajian_ginekologi&tab=demografi <?php if($submission_id) echo '&submission_id=' . $submission_id; ?>" >
        Data Demografi
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? 'riwayat') == 'riwayat' ? 'active' : '' ?>"
        href="?page=maternitas/pengkajian_ginekologi&tab=riwayat<?php if($submission_id) echo '&submission_id=' . $submission_id; ?>">
        Riwayat Kehamilan dan Kesehatan
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'pengkajianfisik' ? 'active' : '' ?>"
        href="?page=maternitas/pengkajian_ginekologi&tab=pengkajianfisik<?php if($submission_id) echo '&submission_id=' . $submission_id; ?>">
        Pengkajian Fisik
        </a>
    </li>

    <!-- <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? 'riwayat') == 'pengkajianfungsional' ? 'active' : '' ?>"
        href="?page=maternitas/pengkajian_ginekologi&tab=pengkajianfungsional">
        Pengkajian Fungsional
        </a>
    </li> -->
    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? 'riwayat') == 'terapi_lab' ? 'active' : '' ?>"
        href="?page=maternitas/pengkajian_ginekologi&tab=terapi_lab<?php if($submission_id) echo '&submission_id=' . $submission_id; ?>">
        Terapi Lab
        </a>
</li>
    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? 'riwayat') == 'lainnya' ? 'active' : '' ?>"
        href="?page=maternitas/pengkajian_ginekologi&tab=lainnya<?php if($submission_id) echo '&submission_id=' . $submission_id; ?>">
        Lainnya
        </a>
    </li>

    <!-- <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'diagnosa_keperawatan' ? 'active' : '' ?>"
        href="?page=maternitas/pengkajian_ginekologi&tab=diagnosa_keperawatan">
        Diagnosa keperawatan
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'intervensi_keperawatan' ? 'active' : '' ?>"
        href="?page=maternitas/pengkajian_ginekologi&tab=intervensi_keperawatan">
        Intervensi keperawatan
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'implementasi_keperawatan' ? 'active' : '' ?>"
        href="?page=maternitas/pengkajian_ginekologi&tab=implementasi_keperawatan">
        Implementasi keperawatan
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'evaluasi_keperawatan' ? 'active' : '' ?>"
        href="?page=maternitas/pengkajian_ginekologi&tab=evaluasi_keperawatan">
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