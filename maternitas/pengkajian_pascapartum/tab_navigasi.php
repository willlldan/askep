<?php

$tabs = [
    "identitas",
    "data_biologis",
    "pemeriksaan_fisik1",
    "pemeriksaan_fisik2",
    "pemeriksaan_fisik3",
    "terapi_lab",
     "riwayat_kehamilan",
    "lainnya",
    // "intervensi_keperawatan",
    // "implementasi_keperawatan",
    // "evaluasi_keperawatan"
];

$currentTab = $_GET['tab'] ?? 'pemeriksaanfisik';

$index = array_search($currentTab, $tabs);

$prevTab = $tabs[$index - 1] ?? null;
$nextTab = $tabs[$index + 1] ?? null;

?>

<div class="d-flex justify-content-between mt-4">

<?php if($prevTab): ?>
<a href="index.php?page=maternitas/pengkajian_pascapartum&tab=<?= $prevTab ?>" class="btn btn-secondary">
Sebelumnya
</a>
<?php else: ?>
<div></div>
<?php endif; ?>

<?php if($nextTab): ?>
<a href="index.php?page=maternitas/pengkajian_pascapartum&tab=<?= $nextTab ?>" class="btn btn-primary">
Selanjutnya
</a>
<?php endif; ?>

</div>

