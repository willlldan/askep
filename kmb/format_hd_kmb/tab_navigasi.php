<?php

$tabs = [
    "lp_ruanghd",
    "format_hd",
    "resume",
    "analisa",
    "diagnosa",
    "rencana",
    "implementasi",
    "evaluasi",
];

$currentTab = $_GET['tab'] ?? 'lp_ruanghd';

$index = array_search($currentTab, $tabs);

$prevTab = $tabs[$index - 1] ?? null;
$nextTab = $tabs[$index + 1] ?? null;

?>

<div class="d-flex justify-content-between mt-4">

<?php if($prevTab): ?>
<a href="index.php?page=kmb/format_hd_kmb&tab=<?= $prevTab ?>" class="btn btn-secondary">
Sebelumnya
</a>
<?php else: ?>
<div></div>
<?php endif; ?>

<?php if($nextTab): ?>
<a href="index.php?page=kmb/format_hd_kmb&tab=<?= $nextTab ?>" class="btn btn-primary">
Selanjutnya
</a>
<?php endif; ?>

</div>

