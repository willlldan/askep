<?php
$submission_id = $_GET['submission_id'] ?? null;
$page = $_GET['page'] ?? '';
$parts = explode('/', $page);
$jeniskeperawatan = $parts[1] ?? 'askep_ruang_flamboyan';
$titles = [
    'askep_ruang_damar' => 'Askep Keperawatan Dasar Ruang Damar',
    'askep_ruang_angsana' => 'Askep Keperawatan Dasar Ruang Angsana',
    'askep_ruang_dahlia' => 'Askep Keperawatan Dasar Ruang dahlia',
    'askep_ruang_flamboyan' => 'Askep Keperawatan Dasar Ruang flamboyan',
    'askep_ruang_perawatan' => 'Askep Keperawatan Dasar Ruang perawatan',
    
    
];
$tabs = [
    
    'pengkajian',
    'data_biologis_1',
    'data_biologis_2',
    'klasifikasi_analisa_data',
    'lainnya',
   
];
$tabLabels = [
    
    'pengkajian' => 'Pengkajian',
    'data_biologis_1' => 'Data Biologis 1',
    'data_biologis_2' => 'Data Biologis 2',
    'klasifikasi_analisa_data' => 'Klasifikasi Analisa Data',
    'lainnya' => 'Lainnya',
];
$currentTab = $_GET['tab'] ?? $tabs[0];
?>

<div class="pagetitle">
    <h1><strong><?= $titles[$jeniskeperawatan] ?? 'Askep Keperawatan Dasar Ruang flamboyan' ?></strong></h1>
</div>
<br>
<ul class="nav nav-tabs custom-tabs">
    <?php
    foreach ($tabs as $tab):
        $isActive = ($currentTab == $tab) ? 'active' : '';
        $label = $tabLabels[$tab] ?? ucfirst(str_replace('_', ' ', $tab));
        $url = "index.php?page=keperawatan-dasar/askep_ruang_flamboyan&tab={$tab}";
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