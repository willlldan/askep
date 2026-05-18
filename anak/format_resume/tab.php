<?php
$jenisanak = $_GET['jenisanak'] ?? 'anggrek';
$submission_id = $_GET['submission_id'] ?? null;
$titles = [
    'anggrek' => 'Format Pengkajian Anak',
    'aster' => 'Format Pengkajian Bayi Baru Lahir',
    'resume' => 'Format Resume Keperawatan Poli Anak'
];
$tabs = [
    'format_resume',
    'analisa_resume',
    'lainnya_resume',
    'lp_imunisasi',
    'poli_imunisasi',
    'analisa_poli',
    'lainnya_poli',
];
$tabLabels = [
    'format_resume' => 'Format Resume Keperawatan Poli Anak',
    'analisa_resume' => 'Analisa Data Format Resume Keperawatan Poli Anak',
    'lainnya_resume' => 'Lainnya Format Resume Keperawatan Poli Anak',
    'lp_imunisasi' => 'Format Laporan Pendahuluan Imunisasi',
    'poli_imunisasi' => 'Format Laporan Poli Imunisasi',
    'analisa_poli' => 'Analisa Format Laporan Poli Imunisasi',
    'lainnya_poli' => 'Lainnya Format Laporan Poli Imunisasi',
];
$currentTab = $_GET['tab'] ?? $tabs[0];
?>
<div class="pagetitle">
    <h1><strong><?= $titles[$jenisanak] ?? 'Pengkajian Asuhan Keperawatan' ?></strong></h1>
</div>
<br>
<ul class="nav nav-tabs custom-tabs">
    <?php
    foreach ($tabs as $tab):
        $isActive = ($currentTab == $tab) ? 'active' : '';
        $label = $tabLabels[$tab] ?? ucfirst(str_replace('_', ' ', $tab));
        $url = "index.php?page=anak/format_resume&jenisanak={$jenisanak}&tab={$tab}";
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