<?php
$jenisAnak = $_GET['jenisAnak'] ?? 'anggrek';
$submission_id = $_GET['submission_id'] ?? null;
$titles = [
    'anggrek' => 'Asuhan Keperawatan Anak Anggrek B',
    'aster' => 'Anak Bayi Baru Lahir',
    'resume' => 'Format Resume Keperawatan Anak di Puskesmas',
];

$tabs = [
    'format_laporan_pendahuluan',
    'pengkajian',
    'pengkajian_riwayat',
    'pengkajian_fisik',
    'analisa_data',
    'lainnya',
];
$tabLabels = [
    'format_laporan_pendahuluan' => 'Format Laporan Pendahuluan',
    'pengkajian' => 'Pengkajian Anak',
    'pengkajian_riwayat' => 'Format Pengkajian Riwayat',
    'pengkajian_fisik' => 'Pemeriksaan Fisik',
    'analisa_data' => 'Analisa Data',
    'lainnya' => 'Lainnya',
];
$currentTab = $_GET['tab'] ?? $tabs[0];
?>

<div class="pagetitle">
    <h1><strong><?= $titles[$jenisAnak] ?? 'Asuhan Keperawatan Anak Anggrek B' ?></strong></h1>
</div>
<br>
<ul class="nav nav-tabs custom-tabs">
    <?php
    foreach ($tabs as $tab):
        $isActive = ($currentTab == $tab) ? 'active' : '';
        $label = $tabLabels[$tab] ?? ucfirst(str_replace('_', ' ', $tab));
        $url = "index.php?page=anak/format_anggrek&jenisAnak={$jenisAnak}&tab={$tab}";
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