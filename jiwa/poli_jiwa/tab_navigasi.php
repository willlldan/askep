<?php

$tabs = [
    "halm_tambah_praktik_klinik_keperawatan_jiwa",
    "format_resume",
    "lainnya",
];

$currentTab = $_GET['tab'] ?? 'halm_tambah_praktik_klinik_keperawatan_jiwa';

$index = array_search($currentTab, $tabs);

$prevTab = $tabs[$index - 1] ?? null;
$nextTab = $tabs[$index + 1] ?? null;

?>

<div class="d-flex justify-content-between mt-4">
 <?php if ($prevTab): ?>
        <a  href="index.php?page=jiwa/poli_jiwa&tab=<?= $prevTab ?><?php if ($submission_id) echo '&submission_id=' . $submission_id; ?>" class="btn btn-secondary">
            Sebelumnya
        </a>
    <?php else: ?>
        <div></div>
    <?php endif; ?>


    <?php if ($nextTab): ?>
        <a  href="index.php?page=jiwa/poli_jiwa&tab=<?= $nextTab ?><?php if ($submission_id) echo '&submission_id=' . $submission_id; ?>" class="btn btn-primary">
            Selanjutnya
        </a>
    <?php else: ?>
        <?php if ($can_submit): ?>
            <div class="d-flex flex-column align-items-end">
                <form action="" method="POST" class="mb-1">
                    <input type="hidden" name="action" value="submit_to_dosen">
                    <button type="submit" class="btn btn-primary">
                        Submit ke Dosen
                    </button>
                </form>
                <p class="text-muted mb-0 small">Pastikan semua data sudah benar sebelum submit.</p>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>