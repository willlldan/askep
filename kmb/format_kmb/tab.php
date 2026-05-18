<?php
$jeniskmb = $_GET['jeniskmb'] ?? 'kmb';
$submission_id = $_GET['submission_id'] ?? null;

$titles = [
    'hd' => 'Format HD KMB',
    'kmb' => 'Keperawatan Medikal Bedah',
    'ok' => 'Pengkajian Ruang OK',

];
?>

<div class="pagetitle">
    <h1><strong><?= $titles[$jeniskmb] ?? 'Keperawatan Medikal Bedah' ?></strong></h1>
</div>
<br>


<ul class="nav nav-tabs custom-tabs">

    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'konsep_keperawatan' ? 'active' : '' ?>"
            href="index.php?page=kmb/format_kmb&jeniskmb=<?= $jeniskmb ?>&tab=konsep_keperawatan"<?php if($submission_id) echo '&submission_id=' . $submission_id; ?>> Konsep Keperawatan </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'pengkajian' ? 'active' : '' ?>"
            href="index.php?page=kmb/format_kmb&jeniskmb=<?= $jeniskmb ?>&tab=pengkajian"<?php if($submission_id) echo '&submission_id=' . $submission_id; ?>> Pengkajian </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'pengkajian_gordon' ? 'active' : '' ?>"
            href="index.php?page=kmb/format_kmb&jeniskmb=<?= $jeniskmb ?>&tab=pengkajian_gordon"<?php if($submission_id) echo '&submission_id=' . $submission_id; ?>> Pengkajian FX Gordon </a>
    </li>

    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'data_biologis_1' ? 'active' : '' ?>"
            href="index.php?page=kmb/format_kmb&jeniskmb=<?= $jeniskmb ?>&tab=data_biologis_1"<?php if($submission_id) echo '&submission_id=' . $submission_id; ?>> Data Biologis 1 </a>
    </li>

    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'data_biologis_2' ? 'active' : '' ?>"
            href="index.php?page=kmb/format_kmb&jeniskmb=<?= $jeniskmb ?>&tab=data_biologis_2"<?php if($submission_id) echo '&submission_id=' . $submission_id; ?>> Data Biologis 2 </a>
    </li>

    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'data_biologis_3' ? 'active' : '' ?>"
            href="index.php?page=kmb/format_kmb&jeniskmb=<?= $jeniskmb ?>&tab=data_biologis_3"<?php if($submission_id) echo '&submission_id=' . $submission_id; ?>> Data Biologis 3 </a>
    </li>

    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'klasifikasi_analisa_data' ? 'active' : '' ?>"
            href="index.php?page=kmb/format_kmb&jeniskmb=<?= $jeniskmb ?>&tab=klasifikasi_analisa_data"<?php if($submission_id) echo '&submission_id=' . $submission_id; ?>> Klasifikasi Analisa Data </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'lainnya' ? 'active' : '' ?>"
            href="index.php?page=kmb/format_kmb&jeniskmb=<?= $jeniskmb ?>&tab=lainnya"<?php if($submission_id) echo '&submission_id=' . $submission_id; ?>> Lainnya </a>
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