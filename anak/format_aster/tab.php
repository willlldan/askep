<?php
$submission_id = $_GET['submission_id'] ?? null;
$page = $_GET['page'] ?? '';
$parts = explode('/', $page);
$jenisAnak = $parts[1] ?? 'format_aster';

$titles = [
    'format_aster' => 'Anak Bayi Baru Lahir',
    'format_anggrek' => 'Keperawatan Medikal Bedah',
    'format_resume' => 'Format Resume Keperawatan Poli Anak'

];

$tabs = [
    "identitas_riwayat",
    "keadaan_bayi",
    "pengkajian_umum",
    "pengkajian_fisik_1",
    "pengkajian_fisik_2",
    "analisa_data",
    "lainnya"
];

$tabLabels = [
    'identitas_riwayat' => 'Identitas dan Riwayat',
    'keadaan_bayi' => 'Keadaan Bayi',
    'pengkajian_umum' => 'Pengkajian Umum',
    'pengkajian_fisik_1' => 'Pengkajian Fisik 1',
    'pengkajian_fisik_2' => 'Pengkajian Fisik 2',
    'analisa_data' => 'Analisa Data',
    'lainnya' => 'Lainnya',
];

$currentTab = $_GET['tab'] ?? $tabs[0];
?>
<!-- Card Identitas -->

<div class="pagetitle">
    <h1><strong><?= $titles[$jenisAnak] ?? 'Anak' ?></strong></h1>
</div>
<br>

<ul class="nav nav-tabs custom-tabs">
    <?php
    foreach ($tabs as $tab):
        $isActive = ($currentTab == $tab) ? 'active' : '';
        $label = $tabLabels[$tab] ?? ucfirst(str_replace('_', ' ', $tab));
        $url = "index.php?page=anak/format_aster&tab={$tab}";
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