ALTER TABLE `submission_sections`
  ADD COLUMN `dosen_review_status` enum('draft','submitted','revision','approved') NOT NULL DEFAULT 'draft' AFTER `status`,
  ADD COLUMN `preceptor_review_status` enum('draft','submitted','revision','approved') NOT NULL DEFAULT 'draft' AFTER `dosen_review_status`;
