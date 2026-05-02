<?php
$jenismaternitas = $_GET['jenismaternitas'] ?? 'antenatal';
$submission_id = $_GET['submission_id'] ?? null;

$titles = [
    'antenatal' => 'Pengkajian Asuhan Keperawatan Antenatal Care',
    'pascapartum' => 'Pengkajian Asuhan Keperawatan Pascapartum',
    'resume' => 'Resume Asuhan Keperawatan Antenatal Care',
    'inranatal' => 'Pengkajian Asuhan Keperawatan Inranatal Care',
    'ginekologi' => 'Pengkajian Asuhan Keperawatan Ginekologi'
];

$tabs = [
    "data_demografi",
    "riwayat_kelahiran_persalinan",
    "pengkajian_fisik",
    "terapi_lab",
    "analisa_data",
    "catatan_keperawatan",
];

// Label mapping for tab display names
$tabLabels = [
    'data_demografi' => 'Data Demografi',
    'riwayat_kelahiran_persalinan' => 'Riwayat Kelahiran dan Persalinan',
    'pengkajian_fisik' => 'Pengkajian Fisik',
    'terapi_lab' => 'Terapi Lab',
    'analisa_data' => 'Analisa Data',
    'catatan_keperawatan' => 'Catatan Keperawatan',
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
        $url = "index.php?page=maternitas/pengkajian_antenatal_care&jenismaternitas={$jenismaternitas}&tab={$tab}";
        if ($submission_id) $url .= "&submission_id={$submission_id}";

    ?>
        <li class="nav-item">
            <a class="nav-link <?= $isActive ?>" href="<?= htmlspecialchars($url) ?>">
                <?= htmlspecialchars($label) ?>
            </a>
        </li>
    <?php endforeach; ?>
</ul>