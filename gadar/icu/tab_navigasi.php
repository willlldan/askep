<?php

$tabs = [
    "laporanpendahuluan",
    "pengkajian",
    "diagnosa_keperawatan",
    "rencana_keperawatan",
    "implementasi_keperawatan",
    "evaluasi_keperawatan"
];

$currentTab = $_GET['tab'] ?? 'laporanpendahuluan';

$index = array_search($currentTab, $tabs);

$prevTab = $tabs[$index - 1] ?? null;
$nextTab = $tabs[$index + 1] ?? null;

?>

<div class="d-flex justify-content-between mt-4">

<?php if($prevTab): ?>
<a href="index.php?page=gadar/icu&tab=<?= $prevTab ?>" class="btn btn-secondary">
Sebelumnya
</a>
<?php else: ?>
<div></div>
<?php endif; ?>

<?php if($nextTab): ?>
<a href="index.php?page=gadar/icu&tab=<?= $nextTab ?>" class="btn btn-primary">
Selanjutnya
</a>
<?php endif; ?>

</div>

