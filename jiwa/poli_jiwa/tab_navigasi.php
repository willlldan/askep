<?php

$tabs = [
    "halm_tambah_praktik_klinik_keperawatan_jiwa",
    "diagnosa",
     "rencana",
     "implementasi",
    
];

$currentTab = $_GET['tab'] ?? 'halm_tambah_praktik_klinik_keperawatan_jiwa';

$index = array_search($currentTab, $tabs);

$prevTab = $tabs[$index - 1] ?? null;
$nextTab = $tabs[$index + 1] ?? null;

?>

<div class="d-flex justify-content-between mt-4">

<?php if($prevTab): ?>
<a href="index.php?page=jiwa/poli_jiwa&tab=<?= $prevTab ?>" class="btn btn-secondary">
Sebelumnya
</a>
<?php else: ?>
<div></div>
<?php endif; ?>

<?php if($nextTab): ?>
<a href="index.php?page=jiwa/poli_jiwa&tab=<?= $nextTab ?>" class="btn btn-primary">
Selanjutnya
</a>
<?php endif; ?>

</div>