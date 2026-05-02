<?php
$submission_id = $_GET['submission_id'] ?? null;
$page = $_GET['page'] ?? '';
$parts = explode('/', $page);
$jenismaternitas = $parts[1] ?? 'pengkajian_inranatal_care';

$titles = [
    'pengkajian_antenatal_care' => 'Pengkajian Asuhan Keperawatan Antenatal Care',
    'pengkajian_pascapartum' => 'Pengkajian Asuhan Keperawatan Pascapartum',
    'resume_antenatal_care' => 'Resume Asuhan Keperawatan Antenatal Care',
    'pengkajian_inranatal_care' => 'Pengkajian Asuhan Keperawatan Inranatal Care',
    'pengkajian_ginekologi' => 'Pengkajian Asuhan Keperawatan Ginekologi'
];

$tabs = [
    "umum",
    "riwayat_persalinan",
    "terapi_lab",
    "laporanpersalinan",
    "analisa_data",
    "lainnya",
];
$tabLabels = [
    'umum' => 'Data Umum',
    'riwayat_persalinan' => 'Riwayat Persalinan',
    'terapi_lab' => 'Terapi Lab',
    'laporanpersalinan' => 'Laporan Persalinan',
    'analisa_data' => 'Analisa Data',
    'lainnya' => 'Lainnya',
];
$currentTab = $_GET['tab'] ?? $tabs[0];
?>

<div class="pagetitle">
    <h1><strong><?= $titles[$jenismaternitas] ?? 'Pengkajian Asuhan Keperawatan' ?></strong></h1>
</div>
<br>

<ul class="nav nav-tabs custom-tabs">
    <?php
    foreach ($tabs as $tab):
        $isActive = ($currentTab == $tab) ? 'active' : '';
        $label = $tabLabels[$tab] ?? ucfirst(str_replace('_', ' ', $tab));
        $url = "index.php?page=maternitas/{$jenismaternitas}&tab={$tab}";
        if ($submission_id) $url .= "&submission_id={$submission_id}";
    ?>
        <li class="nav-item">
            <a class="nav-link <?= $isActive ?>" href="<?= htmlspecialchars($url) ?>">
                <?= htmlspecialchars($label) ?>
            </a>
        </li>
    <?php endforeach; ?>
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