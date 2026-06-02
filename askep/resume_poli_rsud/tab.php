<?php
$submission_id = $_GET['submission_id'] ?? null;
$page = $_GET['page'] ?? '';
$parts = explode('/', $page);
$jeniskeperawatan = $parts[1] ?? 'resume_poli_rsud';
$titles = [
    'resume_poli_rsud' => 'Resume Poli RSUD',
    'resume_poli_rsukt' => 'Resume Poli RSUKT',
    'resume_poli_rsal' => 'Resume Poli RSAL',
    
];
$tabs = [
    'resume_keperawatan',
    'analisa_resume',
    'lainnya_resume',
   
   
];
$tabLabels = [
    'resume_keperawatan' => 'Format Resume ',
    'analisa_resume' => 'Analisa Resume',
    'lainnya_resume' => 'Lainnya Resume',
  
];
$currentTab = $_GET['tab'] ?? $tabs[0];
?>

<div class="pagetitle">
    <h1><strong><?= $titles[$jeniskeperawatan] ?? 'Askep Keperawatan Dasar' ?></strong></h1>
</div>
<br>
<ul class="nav nav-tabs custom-tabs">
    <?php
    foreach ($tabs as $tab):
        $isActive = ($currentTab == $tab) ? 'active' : '';
        $label = $tabLabels[$tab] ?? ucfirst(str_replace('_', ' ', $tab));
        $url = "index.php?page=askep/resume_poli_rsud&tab={$tab}";
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