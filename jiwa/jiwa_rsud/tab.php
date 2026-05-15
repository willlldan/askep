<?php
$jenisjiwa = $_GET['jenisjiwa'] ?? 'jiwa_rsud';
$submission_id = $_GET['submission_id'] ?? null;

$titles = [
    'jiwa_rsud' => 'Asuhan Keperawatan Jiwa RSUD',
    'poli_jiwa' => 'Asuhan Keperawatan Poli Jiwa'
];
?>

    
<div class="pagetitle">
    <h1><strong><?= $titles[$jenisjiwa] ?? 'Pengkajian Asuhan Keperawatan' ?></strong></h1>
</div>
<br>
     <ul class="nav nav-tabs custom-tabs">

    <li class="nav-item">
    <a class="nav-link <?= ($_GET['tab'] ?? 'format_laporan_pendahuluan') == 'format_laporan_pendahuluan' ? 'active' : '' ?>"
    href="index.php?page=jiwa/jiwa_rsud&tab=format_laporan_pendahuluan<?php if($submission_id) echo '&submission_id=' . $submission_id; ?>">
    Format Laporan Pendahuluan
    </a>
</li>

<li class="nav-item">
    <a class="nav-link <?= ($_GET['tab'] ?? '') == 'pengkajian' ? 'active' : '' ?>"
    href="index.php?page=jiwa/jiwa_rsud&tab=pengkajian<?php if($submission_id) echo '&submission_id=' . $submission_id; ?>">
    Format Pengkajian
    </a>
</li>

<li class="nav-item">
    <a class="nav-link <?= ($_GET['tab'] ?? '') == 'pengkajianlanjutan' ? 'active' : '' ?>"
    href="index.php?page=jiwa/jiwa_rsud&tab=pengkajianlanjutan<?php if($submission_id) echo '&submission_id=' . $submission_id; ?>">
    Format Pengkajian Lanjutan
    </a>
</li>

<li class="nav-item">
    <a class="nav-link <?= ($_GET['tab'] ?? '') == 'lainnya' ? 'active' : '' ?>"
    href="index.php?page=jiwa/jiwa_rsud&tab=lainnya<?php if($submission_id) echo '&submission_id=' . $submission_id; ?>">
    Lainnya
    </a>
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

