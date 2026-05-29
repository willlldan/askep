<?php
$submission_id = $_GET['submission_id'] ?? null;
$page = $_GET['page'] ?? '';
$parts = explode('/', $page);
$section_page = $parts[1] ?? 'gerontik-new';

$titles = [
    'gerontik-new' => 'Asuhan Keperawatan Gerontik',
    'askep_gerontik' => 'Asuhan Keperawatan Gerontik',
];

$tabs = [
    'identitas',
    'riwayat_kesehatan',
    'pemeriksaan_fisik',
    'kebiasaan_harian',
    'psikososial_spiritual',
    'status_fungsional',
    'skala_depresi',
    'apgar_spmsq_risiko_jatuh',
    'catatan_keperawatan',
];

$tabLabels = [
    'identitas' => 'Identitas',
    'riwayat_kesehatan' => 'Riwayat Kesehatan',
    'pemeriksaan_fisik' => 'Pemeriksaan Fisik',
    'kebiasaan_harian' => 'Pola Kebiasaan',
    'psikososial_spiritual' => 'Psikososial & Spiritual',
    'status_fungsional' => 'Status Fungsional',
    'skala_depresi' => 'Skala Depresi',
    'apgar_spmsq_risiko_jatuh' => 'APGAR / SPMSQ / Jatuh',
    'catatan_keperawatan' => 'Catatan Keperawatan',
];

$currentTab = $_GET['tab'] ?? $tabs[0];
$can_submit = true;
?>

<div class="pagetitle">
    <h1><strong><?= $titles[$section_page] ?? 'Asuhan Keperawatan Gerontik' ?></strong></h1>
</div>
<br>

<ul class="nav nav-tabs custom-tabs">
    <?php foreach ($tabs as $tab): ?>
        <?php
        $isActive = ($currentTab === $tab) ? 'active' : '';
        $label = $tabLabels[$tab] ?? ucfirst(str_replace('_', ' ', $tab));
        $url = "index.php?page=" . urlencode($page ?: 'gerontik-new') . "&tab={$tab}";
        if ($submission_id) $url .= "&submission_id={$submission_id}";
        ?>
        <li class="nav-item">
            <a class="nav-link js-nav-tab <?= $isActive ?>" href="<?= htmlspecialchars($url) ?>">
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
