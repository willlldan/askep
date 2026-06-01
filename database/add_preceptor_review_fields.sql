ALTER TABLE `submissions`
  ADD COLUMN `dosen_reviewed_by` int DEFAULT NULL AFTER `reviewed_at`,
  ADD COLUMN `dosen_reviewed_at` timestamp NULL DEFAULT NULL AFTER `dosen_reviewed_by`,
  ADD COLUMN `preceptor_reviewed_by` int DEFAULT NULL AFTER `dosen_reviewed_at`,
  ADD COLUMN `preceptor_reviewed_at` timestamp NULL DEFAULT NULL AFTER `preceptor_reviewed_by`,
  ADD KEY `FK_submissions_tbl_user_3` (`dosen_reviewed_by`),
  ADD KEY `FK_submissions_tbl_user_4` (`preceptor_reviewed_by`),
  ADD CONSTRAINT `FK_submissions_tbl_user_3` FOREIGN KEY (`dosen_reviewed_by`) REFERENCES `tbl_user` (`id_user`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_submissions_tbl_user_4` FOREIGN KEY (`preceptor_reviewed_by`) REFERENCES `tbl_user` (`id_user`) ON DELETE SET NULL;
