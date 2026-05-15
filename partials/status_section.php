<?php
if (!isset($section_status)) return;
$badge = [
    'draft'     => 'secondary',
    'submitted' => 'primary',
    'revision'  => 'warning',
    'approved'  => 'success',
];
$dosen_name = $submission['dosen_name'] ?? '-';
?>
<div class="alert alert-<?= $badge[$section_status] ?>">
    Status: <strong><?= ucfirst($section_status) ?></strong>
    | Reviewed by: <strong><?= $dosen_name ? htmlspecialchars($dosen_name) : '-' ?></strong>
</div>
