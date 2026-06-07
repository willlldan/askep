<?php
/**
 * Read-only verifier for section review fields.
 *
 * Usage:
 *   php scripts/verify_section_review.php
 *   php scripts/verify_section_review.php <submission_id> <section_name>
 */

if (PHP_SAPI !== 'cli') {
    fwrite(STDERR, "This script must be run from CLI.\n");
    exit(1);
}

require_once __DIR__ . '/../koneksi.php';
require_once __DIR__ . '/../utils.php';

function out($message)
{
    fwrite(STDOUT, $message . PHP_EOL);
}

function fail($message)
{
    fwrite(STDERR, $message . PHP_EOL);
    exit(1);
}

$submissionId = isset($argv[1]) ? (int) $argv[1] : 0;
$sectionName = $argv[2] ?? '';

out('Checking submission_sections review fields...');

$stmt = $mysqli->query("\n    SELECT COLUMN_NAME\n    FROM INFORMATION_SCHEMA.COLUMNS\n    WHERE TABLE_SCHEMA = DATABASE()\n      AND TABLE_NAME = 'submission_sections'\n      AND COLUMN_NAME IN ('dosen_review_status', 'preceptor_review_status')\n");
$found = $stmt ? $stmt->fetch_all(MYSQLI_ASSOC) : [];

if (count($found) !== 2) {
    fail('Missing required columns in submission_sections. Run the migration first.');
}

out('Schema OK: dosen_review_status, preceptor_review_status exist.');

if ($submissionId <= 0 || $sectionName === '') {
    out('No submission_id/section_name provided, schema check only.');
    exit(0);
}

$state = getSectionReviewState($submissionId, $sectionName, $mysqli);
if (!$state) {
    fail('Section not found for the provided submission_id and section_name.');
}

out('Section state:');
out('  aggregate_status     = ' . ($state['status'] ?? '-'));
out('  dosen_review_status  = ' . ($state['dosen_review_status'] ?? '-'));
out('  preceptor_review_status = ' . ($state['preceptor_review_status'] ?? '-'));
out('  computed aggregate    = ' . getSectionAggregateStatus(
    $state['dosen_review_status'] ?? null,
    $state['preceptor_review_status'] ?? null,
    $state['status'] ?? 'draft'
));

out('  reviewer names (if any):');
out('    dosen     = ' . (($state['dosen_name'] ?? '-') ?: '-'));
out('    preceptor = ' . (($state['preceptor_name'] ?? '-') ?: '-'));

exit(0);
