<?php

$tabs = [
    "format_resume_keperawatan",
    "format_laporan_poli",
    "format_lapiran_pendahuluan_imunisasi",
   
];

$currentTab = $_GET['tab'] ?? 'resume_keperawatan';

$index = array_search($currentTab, $tabs);

$prevTab = $tabs[$index - 1] ?? null;
$nextTab = $tabs[$index + 1] ?? null;

?>

<div class="d-flex justify-content-between mt-4">

<?php if($prevTab): ?>
<a href="index.php?page=anak/format_resume&tab=<?= $prevTab ?>" class="btn btn-secondary">
Sebelumnya
</a>
<?php else: ?>
<div></div>
<?php endif; ?>

<?php if($nextTab): ?>
<a href="index.php?page=anak/format_resume&tab=<?= $nextTab ?>" class="btn btn-primary">
Selanjutnya
</a>
<?php endif; ?>

</div>