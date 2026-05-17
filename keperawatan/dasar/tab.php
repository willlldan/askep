<?php
$jeniskeperawatan = $_GET['jeniskeperawatan'] ?? 'keperawatan';
$submission_id = $_GET['submission_id'] ?? null;

$titles = [
    'keperawatan' => 'Keperawatan Dasar',


];
?>


<div class="pagetitle">
    <h1><strong><?= $titles[$jeniskeperawatan] ?? 'keperawatan' ?></strong></h1>
</div>
<br>


<ul class="nav nav-tabs custom-tabs">

    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'resume_keperawatan' ? 'active' : '' ?>"
            href="index.php?page=keperawatan/dasar&jeniskeperawatan=<?= $jeniskeperawatan ?>&tab=resume_keperawatan"<?php if($submission_id) echo '&submission_id=' . $submission_id; ?>> Format Resume </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'analisa_resume' ? 'active' : '' ?>"
            href="index.php?page=keperawatan/dasar&jeniskeperawatan=<?= $jeniskeperawatan ?>&tab=analisa_resume"<?php if($submission_id) echo '&submission_id=' . $submission_id; ?>> Analisa Resume </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'lainnya_resume' ? 'active' : '' ?>"
            href="index.php?page=keperawatan/dasar&jeniskeperawatan=<?= $jeniskeperawatan ?>&tab=lainnya_resume"<?php if($submission_id) echo '&submission_id=' . $submission_id; ?>> Lainnya Resume </a>
    </li>

     <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'pengkajian' ? 'active' : '' ?>"
            href="index.php?page=keperawatan/dasar&jeniskeperawatan=<?= $jeniskeperawatan ?>&tab=pengkajian"<?php if($submission_id) echo '&submission_id=' . $submission_id; ?>> Pengkajian </a>
</li>   

    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'data_biologis_1' ? 'active' : '' ?>"
            href="index.php?page=keperawatan/dasar&jeniskeperawatan=<?= $jeniskeperawatan ?>&tab=data_biologis_1"<?php if($submission_id) echo '&submission_id=' . $submission_id; ?>> Data Biologis 1 </a>
    </li>

   <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'data_biologis_2' ? 'active' : '' ?>"
            href="index.php?page=keperawatan/dasar&jeniskeperawatan=<?= $jeniskeperawatan ?>&tab=data_biologis_2"<?php if($submission_id) echo '&submission_id=' . $submission_id; ?>> Data Biologis 2 </a>
    </li>


    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'klasifikasi_analisa_data' ? 'active' : '' ?>"
            href="index.php?page=keperawatan/dasar&jeniskeperawatan=<?= $jeniskeperawatan ?>&tab=klasifikasi_analisa_data"<?php if($submission_id) echo '&submission_id=' . $submission_id; ?>> Klasifikasi Analisa Data </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'lainnya' ? 'active' : '' ?>"
            href="index.php?page=keperawatan/dasar&jeniskeperawatan=<?= $jeniskeperawatan ?>&tab=lainnya"<?php if($submission_id) echo '&submission_id=' . $submission_id; ?>> Lainnya </a>
    </li>
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