-- =========================================================
-- Combined migration for 2-approval workflow
-- Target:
-- 1) Add Preceptor role to tbl_user
-- 2) Add reviewer separation for submissions & submission_sections
-- 3) Backfill existing production data
--
-- Run once on production in this order.
-- =========================================================

-- ---------------------------------------------------------
-- Step 1: Extend user levels
-- ---------------------------------------------------------
ALTER TABLE `tbl_user`
  MODIFY `level` ENUM('Admin','Dosen','Mahasiswa','Preceptor') NOT NULL;

UPDATE tbl_user
SET level = 'Preceptor'
WHERE password = 'preceptor'
  AND level <> 'Preceptor';

-- ---------------------------------------------------------
-- Step 2: Add submission reviewer fields
-- ---------------------------------------------------------
ALTER TABLE `submissions`
  ADD COLUMN `dosen_reviewed_by` int DEFAULT NULL AFTER `reviewed_at`,
  ADD COLUMN `dosen_reviewed_at` timestamp NULL DEFAULT NULL AFTER `dosen_reviewed_by`,
  ADD COLUMN `dosen_review_status` enum('draft','submitted','revision','approved') DEFAULT NULL AFTER `dosen_reviewed_at`,
  ADD COLUMN `preceptor_reviewed_by` int DEFAULT NULL AFTER `dosen_review_status`,
  ADD COLUMN `preceptor_reviewed_at` timestamp NULL DEFAULT NULL AFTER `preceptor_reviewed_by`,
  ADD COLUMN `preceptor_review_status` enum('draft','submitted','revision','approved') DEFAULT NULL AFTER `preceptor_reviewed_at`;

-- Backfill submission-level review fields from legacy reviewed_by.
UPDATE submissions s
JOIN tbl_user u ON u.id_user = s.reviewed_by
SET
  s.dosen_reviewed_by = s.reviewed_by,
  s.dosen_reviewed_at = COALESCE(s.reviewed_at, s.submitted_at, s.updated_at),
  s.dosen_review_status = s.status
WHERE u.level = 'Dosen'
  AND s.reviewed_by IS NOT NULL
  AND s.dosen_reviewed_by IS NULL;

-- ---------------------------------------------------------
-- Step 3: Add section reviewer fields
-- ---------------------------------------------------------
ALTER TABLE `submission_sections`
  ADD COLUMN `dosen_review_status` enum('draft','submitted','revision','approved') NOT NULL DEFAULT 'draft' AFTER `status`,
  ADD COLUMN `preceptor_review_status` enum('draft','submitted','revision','approved') NOT NULL DEFAULT 'draft' AFTER `dosen_review_status`;

-- Backfill section-level review fields from legacy status.
UPDATE submission_sections
SET
  dosen_review_status = status,
  preceptor_review_status = status
WHERE dosen_review_status = 'draft'
  AND preceptor_review_status = 'draft';
