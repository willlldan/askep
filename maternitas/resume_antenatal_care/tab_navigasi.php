<?php

$tabs = [
    "identitas",
    "pengkajian_anamnesa",
    "pengkajian_tanda_vital",
    "pemeriksaan_fisik",
    "terapi_lab",
    "lainnya",
    //     "intervensi_keperawatan",
    //     "implementasi_keperawatan",
    //     "evaluasi_keperawatan"
];

$currentTab = $_GET['tab'] ?? 'pengkajian';

$index = array_search($currentTab, $tabs);

$prevTab = $tabs[$index - 1] ?? null;
$nextTab = $tabs[$index + 1] ?? null;

?>

<div class="d-flex justify-content-between mt-4">

    <?php if ($prevTab): ?>
        <a href="index.php?page=maternitas/resume_antenatal_care&tab=<?= $prevTab ?>" class="btn btn-secondary">
            Sebelumnya
        </a>
    <?php else: ?>
        <div></div>
    <?php endif; ?>

    <?php if ($nextTab): ?>
        <a href="index.php?page=maternitas/resume_antenatal_care&tab=<?= $nextTab ?><?php if ($submission_id) echo '&submission_id=' . $submission_id; ?>" class="btn btn-primary">
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