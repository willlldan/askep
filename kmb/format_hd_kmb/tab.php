<?php
$jeniskmb = $_GET['jeniskmb'] ?? 'hd';
$submission_id = $_GET['submission_id'] ?? null;
$titles = [
    'hd' => 'Format HD KMB',
    'kmb' => 'Keperawatan Medikal Bedah',
    'ok' => 'Pengkajian Ruang OK',
];
$tabs = [
    'lp_ruanghd',
    'format_hd',
    'pengkajian',
    'pemeriksaan_fisik',
    'pengkajian_kebutuhan',
    'analisa_data',
    'lainnya',
];
$tabLabels = [
    'lp_ruanghd' => 'Format Laporan Pendahuluan Ruang HD',
    'format_hd' => 'Format Hemodalisa (HD)',
    'pengkajian' => 'Pengkajian',
    'pemeriksaan_fisik' => 'Pemeriksaan Fisik',
    'pengkajian_kebutuhan' => 'Pengkajian Kebutuhan',
    'analisa_data' => 'Analisa Data',
    'lainnya' => 'Lainnya',
];
$currentTab = $_GET['tab'] ?? $tabs[0];
?>
<div class="pagetitle">
    <h1><strong><?= $titles[$jeniskmb] ?? 'Format HD KMB' ?></strong></h1>
</div>
<br>
<ul class="nav nav-tabs custom-tabs">
    <?php
    foreach ($tabs as $tab):
        $isActive = ($currentTab == $tab) ? 'active' : '';
        $label = $tabLabels[$tab] ?? ucfirst(str_replace('_', ' ', $tab));
        $url = "index.php?page=kmb/format_hd_kmb&tab={$tab}";
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