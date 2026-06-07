<?php
if (!isset($section_status, $submission)) return;

$badge = [
    'draft'     => 'secondary',
    'submitted' => 'primary',
    'revision'  => 'warning',
    'approved'  => 'success',
];

$dosenStatus = $section_dosen_status ?? ($submission['dosen_review_status'] ?? null);
$preceptorStatus = $section_preceptor_status ?? ($submission['preceptor_review_status'] ?? null);
$dosenName = $submission['dosen_name'] ?? null;
$preceptorName = $submission['preceptor_name'] ?? null;

if (!$dosenStatus && !empty($submission['dosen_reviewed_by']) && in_array($section_status, ['submitted', 'revision', 'approved'], true)) {
    $dosenStatus = $section_status;
}

if (!$preceptorStatus && !empty($submission['preceptor_reviewed_by']) && in_array($section_status, ['submitted', 'revision', 'approved'], true)) {
    $preceptorStatus = $section_status;
}

$renderBadge = function ($status, $emptyLabel) use ($badge) {
    if ($status && isset($badge[$status])) {
        return '<span class="badge bg-' . $badge[$status] . '">' . ucfirst($status) . '</span>';
    }
    return '<span class="badge bg-light text-dark border">' . htmlspecialchars($emptyLabel) . '</span>';
};
?>
<div class="alert alert-info d-flex flex-column gap-2">
    <div>
        <strong>Status Approval Dosen:</strong>
        <?= $renderBadge($dosenStatus, 'Belum Review') ?>
        | Reviewed by: <strong><?= $dosenName ? htmlspecialchars($dosenName) : '-' ?></strong>
    </div>
    <div>
        <strong>Status Approval Preceptor:</strong>
        <?= $renderBadge($preceptorStatus, 'Belum Review') ?>
        | Reviewed by: <strong><?= $preceptorName ? htmlspecialchars($preceptorName) : '-' ?></strong>
    </div>
</div>
