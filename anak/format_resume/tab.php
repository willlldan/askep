<?php
$jenisanak = $_GET['jenisanak'] ?? 'anggrek';
$submission_id = $_GET['submission_id'] ?? null;

$titles = [
    'anggrek' => 'Format Pengkajian Anak',
    'aster' => 'Format Pengkajian Bayi Baru Lahir',
    'resume' => 'Format Resume Keperawatan Poli Anak'
];
?>

<div class="pagetitle">
    <h1><strong><?= $titles[$jenisanak] ?? 'Pengkajian Asuhan Keperawatan' ?></strong></h1>
</div>
<br>

<ul class="nav nav-tabs custom-tabs">

    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'format_resume' ? 'active' : '' ?>"
            href="index.php?page=anak/format_resume&jenisanak=<?= $jenisanak ?>&tab=format_resume<?php if($submission_id) echo '&submission_id=' . $submission_id; ?>">
            Format Resume Keperawatan Poli Anak
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'analisa_resume' ? 'active' : '' ?>"
            href="index.php?page=anak/format_resume&jenisanak=<?= $jenisanak ?>&tab=analisa_resume<?php if($submission_id) echo '&submission_id=' . $submission_id; ?>">
            Analisa Data Format Resume Keperawatan Poli Anak
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'lainnya_resume' ? 'active' : '' ?>"
            href="index.php?page=anak/format_resume&jenisanak=<?= $jenisanak ?>&tab=lainnya_resume<?php if($submission_id) echo '&submission_id=' . $submission_id; ?>">
            Lainnya Format Resume Keperawatan Poli Anak
        </a>
    </li>

     <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'lp_imunisasi' ? 'active' : '' ?>"
            href="index.php?page=anak/format_resume&jenisanak=<?= $jenisanak ?>&tab=lp_imunisasi<?php if($submission_id) echo '&submission_id=' . $submission_id; ?>">
            Format Laporan Pendahuluan Imunisasi
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'poli_imunisasi' ? 'active' : '' ?>"
            href="index.php?page=anak/format_resume&jenisanak=<?= $jenisanak ?>&tab=poli_imunisasi<?php if($submission_id) echo '&submission_id=' . $submission_id; ?>">
            Format Laporan Poli Imunisasi
        </a>
    </li>

     <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'analisa_poli' ? 'active' : '' ?>"
            href="index.php?page=anak/format_resume&jenisanak=<?= $jenisanak ?>&tab=analisa_poli<?php if($submission_id) echo '&submission_id=' . $submission_id; ?>">
            Analisa Format Laporan Poli Imunisasi
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'lainnya_poli' ? 'active' : '' ?>"
            href="index.php?page=anak/format_resume&jenisanak=<?= $jenisanak ?>&tab=lainnya_poli<?php if($submission_id) echo '&submission_id=' . $submission_id; ?>">
            Lainnya Format Laporan Poli Imunisasi
        </a>
    </li>

</ul>

<style>
        .custom-tabs {
            border-bottom: 1px solid #dee2e6;
            display: flex;
            width: 100%;
        }

        .custom-tabs .nav-item {
            flex: 1;
            display: flex;
        }

        .custom-tabs .nav-link {
            border: none;
            background: transparent;
            color: #f6f9ff;
            font-weight: 500;
            padding: 10px 15px;
            
            display: flex;
            align-items: center;
            justify-content: flex-start;

            width: 100%;
            height: 100%;
            text-align: left;
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