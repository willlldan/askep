ALTER TABLE `submissions`
  ADD COLUMN `dosen_review_status` enum('draft','submitted','revision','approved') DEFAULT NULL AFTER `dosen_reviewed_at`,
  ADD COLUMN `preceptor_review_status` enum('draft','submitted','revision','approved') DEFAULT NULL AFTER `preceptor_reviewed_at`;
