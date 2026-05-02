<?php
$submission_id = $_GET['submission_id'] ?? null;

// $currentTab dan $tabs didefiniskan di tab.php, pastikan sudah include tab.php sebelum menggunakan variabel ini
$index = array_search($currentTab, $tabs);
$prevTab = $tabs[$index - 1] ?? null;
$nextTab = $tabs[$index + 1] ?? null;

function buildTabUrl($tab, $submission_id)
{
    $page = $_GET['page'] ?? 'maternitas/pengkajian_antenatal_care';
    $url = "index.php?page=" . $page . "&tab={$tab}";
    if ($submission_id) $url .= "&submission_id={$submission_id}";
    return $url;
}

?>


<script>
    // $existingData sudah didefinisikan di halm sebelumnya
    const existingData = <?= json_encode($existing_data) ?>;
</script>


<!-- ================================ -->
<!-- SECTION KOMENTAR & ACTION DOSEN -->
<!-- ================================ -->
<div class="card mt-3">
    <div class="card-body">
        <h5 class="card-title"><strong>Komentar</strong></h5>

        <!-- List komentar -->
        <?php if (!empty($comments)): ?>
            <?php foreach ($comments as $cmt): ?>
                <div class="alert alert-warning">
                    <strong><?= htmlspecialchars($cmt['dosen_name']) ?></strong>
                    <small class="text-muted ms-2"><?= date('d/m/Y H:i', strtotime($cmt['created_at'])) ?></small>
                    <p class="mb-0 mt-1"><?= htmlspecialchars($cmt['comment']) ?></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-muted">Belum ada komentar.</p>
        <?php endif; ?>

        <!-- Form komentar + action (khusus dosen) -->
        <!-- $is_dosen dan $section_status didefinisikan di file sebelumnya -->
        <?php if ($is_dosen && $section_status !== 'approved'): ?>
            <form action="" method="POST">
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><strong>Komentar</strong></label>
                    <div class="col-sm-9">
                        <textarea name="comment" class="form-control" rows="3"
                            placeholder="Tulis komentar (wajib jika meminta revisi)..."></textarea>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-11 d-flex justify-content-end gap-2">
                        <button type="submit" name="action" value="revision" class="btn btn-warning">
                            Minta Revisi
                        </button>
                        <button type="submit" name="action" value="approve" class="btn btn-success">
                            Approve
                        </button>
                    </div>
                </div>
            </form>
        <?php elseif ($is_dosen && $section_status === 'approved'): ?>
            <div class="alert alert-success">
                Section ini sudah di-approve.
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="d-flex justify-content-between mt-4">
    <!-- Tombol Sebelumnya -->
    <?php if ($prevTab): ?>
        <a href="<?= htmlspecialchars(buildTabUrl($prevTab, $submission_id)) ?>" class="btn btn-secondary">
            Sebelumnya
        </a>
    <?php else: ?>
        <div></div>
    <?php endif; ?>

    <!-- Tombol Selanjutnya atau Submit -->
    <?php if ($nextTab): ?>
        <a href="<?= htmlspecialchars(buildTabUrl($nextTab, $submission_id)) ?>" class="btn btn-primary">
            Selanjutnya
        </a>
    <?php elseif (!empty($can_submit)): ?>
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
</div>