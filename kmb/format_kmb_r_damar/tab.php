<?php
$submission_id = $_GET['submission_id'] ?? null;
$page = $_GET['page'] ?? '';
$parts = explode('/', $page);
$jeniskmb = $parts[1] ?? 'format_kmb_r_damar';
$titles = [
    'format_hd_kmb' => 'Format HD KMB',
    'format_kmb' => 'Keperawatan Medikal Bedah',
    'format_kmb_r_angsana' => 'Keperawatan Medikal Bedah Ruang Angsana',
    'format_kmb_r_dahlia' => 'Keperawatan Medikal Bedah Ruang Dahlia',
    'format_kmb_r_damar' => 'Keperawatan Medikal Bedah Ruang Damar',
    'pengkajian_ruang_ok' => 'Pengkajian Ruang OK',
    'format_poli_tb' => 'Format Poli Tb/Umum ',
];
$tabs = [
    'konsep_keperawatan',
    'pengkajian',
    'pengkajian_gordon',
    'data_biologis_1',
    'data_biologis_2',
    'data_biologis_3',
    'klasifikasi_analisa_data',
    'lainnya',
];
$tabLabels = [
    'konsep_keperawatan' => 'Laporan Pendahuluan',
    'pengkajian' => 'Pengkajian',
    'pengkajian_gordon' => 'Pengkajian FX Gordon',
    'data_biologis_1' => 'Data Biologis 1',
    'data_biologis_2' => 'Data Biologis 2',
    'data_biologis_3' => 'Data Biologis 3',
    'klasifikasi_analisa_data' => 'Klasifikasi Analisa Data',
    'lainnya' => 'Lainnya',
];
$currentTab = $_GET['tab'] ?? $tabs[0];
?>

<div class="pagetitle">
    <h1><strong><?= $titles[$jeniskmb] ?? 'Keperawatan Medikal Bedah' ?></strong></h1>
</div>
<br>
<ul class="nav nav-tabs custom-tabs">
    <?php
    foreach ($tabs as $tab):
        $isActive = ($currentTab == $tab) ? 'active' : '';
        $label = $tabLabels[$tab] ?? ucfirst(str_replace('_', ' ', $tab));
        $url = "index.php?page=kmb/format_kmb_r_damar&tab={$tab}";
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