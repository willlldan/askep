<?php
$submission_id = $_GET['submission_id'] ?? null;
$page = $_GET['page'] ?? '';
$parts = explode('/', $page);
$jenismaternitas = $parts[1] ?? 'pengkajian_pascapartum';

$titles = [
    'pengkajian_antenatal_care' => 'Pengkajian Asuhan Keperawatan Antenatal Care',
    'pengkajian_pascapartum' => 'Pengkajian Asuhan Keperawatan Pascapartum',
    'resume_antenatal_care' => 'Resume Asuhan Keperawatan Antenatal Care',
    'pengkajian_inranatal_care' => 'Pengkajian Asuhan Keperawatan Inranatal Care',
    'pengkajian_ginekologi' => 'Pengkajian Asuhan Keperawatan Ginekologi'
];

$tabs = [
    "identitas",
    "pengkajian_anamnesa",
    "pengkajian_tanda_vital",
    "pemeriksaan_fisik",
    "terapi_lab",
    "lainnya",
];

$tab_labels = [
    'identitas' => 'Identitas',
    'pengkajian_anamnesa' => 'Anamnesa & Antropometri',
    'pengkajian_tanda_vital' => 'TTV & Pemeriksaan Umum',
    'pemeriksaan_fisik' => 'Pemeriksaan Fisik',
    'terapi_lab' => 'Terapi  Lab',
    'lainnya' => 'Lainnya',
];

$currentTab = $_GET['tab'] ?? $tabs[0];
?>

<div class="pagetitle">
    <h1><strong><?= $titles[$jenismaternitas] ?? 'Resume Asuhan Keperawatan Antenatal Care' ?></strong></h1>
</div>
<br>

<ul class="nav nav-tabs custom-tabs">
    <?php
    // Label mapping for tabs

    $active_tab = $_GET['tab'] ?? $tabs[0];
    foreach ($tabs as $tab) {
        $label = $tab_labels[$tab] ?? ucfirst(str_replace('_', ' ', $tab));
        $active = $active_tab === $tab ? 'active' : '';
        $url = "index.php?page=maternitas/resume_antenatal_care&tab=$tab";
        echo "<li class='nav-item'>";
        echo "<a class='nav-link $active' href='$url'>$label</a>";
        echo "</li>";
    }
    ?>
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