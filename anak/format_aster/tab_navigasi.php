<?php

$tabs = [
    "identitas_riwayat",
    "keadaan_bayi",
    "pengkajian_umum",
    "pengkajian_fisik_1",
    "pengkajian_fisik_2",
    "analisa_data",
    "format_laporan_pendahuluan",
    "diagnosa",
    "rencana",
    "implementasi",
    "evaluasi",
];

$currentTab = $_GET['tab'] ?? 'pengkajian';

$index = array_search($currentTab, $tabs);

$prevTab = $tabs[$index - 1] ?? null;
$nextTab = $tabs[$index + 1] ?? null;

?>

<div class="d-flex justify-content-between mt-4">

<?php if($prevTab): ?>
<a href="index.php?page=anak/format_aster&tab=<?= $prevTab ?>" class="btn btn-secondary">
Sebelumnya
</a>
<?php else: ?>
<div></div>
<?php endif; ?>

<?php if($nextTab): ?>
<a href="index.php?page=anak/format_aster&tab=<?= $nextTab ?>" class="btn btn-primary">
Selanjutnya
</a>
<?php endif; ?>

</div>