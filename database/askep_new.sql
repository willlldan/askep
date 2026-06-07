-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.39 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for askep_new
CREATE DATABASE IF NOT EXISTS `askep_new` /*!40100 DEFAULT CHARACTER SET latin1 */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `askep_new`;


-- Dumping structure for table askep_new.tbl_user
CREATE TABLE IF NOT EXISTS `tbl_user` (
  `id_user` int NOT NULL AUTO_INCREMENT,
  `nama` varchar(50) NOT NULL,
  `npm` varchar(50) DEFAULT NULL,
  `username` varchar(15) NOT NULL,
  `password` varchar(10) NOT NULL,
  `level` enum('Admin','Dosen','Mahasiswa','Preceptor') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table askep_new.tbl_user: ~5 rows (approximately)
INSERT INTO `tbl_user` (`id_user`, `nama`, `npm`, `username`, `password`, `level`) VALUES
	(3, 'Tatang suherman', '-', 'admin', 'admin', 'Admin'),
	(4, 'mahasiswa', '123123111', 'mahasiswa', 'mahasiswa', 'Mahasiswa'),
	(7, 'Bambang', NULL, 'dosen', 'dosen', 'Dosen'),
	(8, 'tasha', '173040071', 'tasha', 'tasha', 'Mahasiswa'),
	(9, 'Wildan', '173040071', 'wil', 'wil', 'Mahasiswa');

-- Dumping structure for table askep_new.forms
CREATE TABLE IF NOT EXISTS `forms` (
  `id` int NOT NULL AUTO_INCREMENT,
  `form_name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `department` varchar(255) NOT NULL,
  `count_section` int NOT NULL DEFAULT '0',
  `description` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=latin1;

-- Dumping data for table askep_new.forms: ~13 rows (approximately)
INSERT INTO `forms` (`id`, `form_name`, `slug`, `department`, `count_section`, `description`, `created_at`) VALUES (1, 'Antenatal Care', 'pengkajian_antenatal_care', 'Maternitas', 6, 'Form pengkajian asuhan keperawatan antenatal care', '2026-04-11 16:09:32');
INSERT INTO `forms` (`id`, `form_name`, `slug`, `department`, `count_section`, `description`, `created_at`) VALUES (2, 'Pascapartum', 'pengkajian_pascapartum', 'Maternitas', 8, 'Form pengkajian asuhan keperawatan antenatal care', '2026-04-11 16:09:32');
INSERT INTO `forms` (`id`, `form_name`, `slug`, `department`, `count_section`, `description`, `created_at`) VALUES (3, 'Resume Antenatal Care', 'resume_antenatal_care', 'Maternitas', 6, 'Form Resume Antenatal Care', '2026-04-11 16:09:32');
INSERT INTO `forms` (`id`, `form_name`, `slug`, `department`, `count_section`, `description`, `created_at`) VALUES (4, 'Format Anggrek B', 'format_anggrek', 'Anak', 6, 'Form pengkajian asuhan keperawatan format anggrek B', '2026-04-11 16:09:32');
INSERT INTO `forms` (`id`, `form_name`, `slug`, `department`, `count_section`, `description`, `created_at`) VALUES (5, 'Format Aster', 'format_aster', 'Anak', 7, 'Form pengkajian asuhan keperawatan format aster', '2026-04-11 16:09:32');
INSERT INTO `forms` (`id`, `form_name`, `slug`, `department`, `count_section`, `description`, `created_at`) VALUES (6, 'Format Resume Keperawatan Poli Anak', 'format_resume', 'Anak', 7, 'Form resume keperawatan poli anak', '2026-04-11 16:09:32');
INSERT INTO `forms` (`id`, `form_name`, `slug`, `department`, `count_section`, `description`, `created_at`) VALUES (7, 'Format Jiwa RSUD', 'jiwa_rsud', 'Jiwa', 4, 'Form pengkajian asuhan keperawatan jiwa RSUD', '2026-04-11 16:09:32');
INSERT INTO `forms` (`id`, `form_name`, `slug`, `department`, `count_section`, `description`, `created_at`) VALUES (8, 'Format Poli Jiwa', 'poli_jiwa', 'Jiwa', 3, 'Form pengkajian asuhan keperawatan poli jiwa', '2026-04-11 16:09:32');
INSERT INTO `forms` (`id`, `form_name`, `slug`, `department`, `count_section`, `description`, `created_at`) VALUES (9, 'Format HD KMB II', 'format_hd_kmb', 'KMB', 7, 'Form pengkajian asuhan keperawatan hemodialisa KMB II', '2026-04-11 16:09:32');
INSERT INTO `forms` (`id`, `form_name`, `slug`, `department`, `count_section`, `description`, `created_at`) VALUES (10, 'Format KMB', 'format_kmb', 'KMB', 8, 'Form pengkajian asuhan keperawatan KMB', '2026-04-11 16:09:32');
INSERT INTO `forms` (`id`, `form_name`, `slug`, `department`, `count_section`, `description`, `created_at`) VALUES (11, 'Format OK KMB II', 'pengkajian_ruang_ok', 'KMB', 5, 'Form pengkajian asuhan keperawatan OK KMB II', '2026-04-11 16:09:32');
INSERT INTO `forms` (`id`, `form_name`, `slug`, `department`, `count_section`, `description`, `created_at`) VALUES (12, 'Pengkajian Ginekologi', 'pengkajian_ginekologi', 'Maternitas', 5, 'Form pengkajian asuhan keperawatan ginekologi', '2026-04-11 16:09:32');
INSERT INTO `forms` (`id`, `form_name`, `slug`, `department`, `count_section`, `description`, `created_at`) VALUES (13, 'Pengkajian Inranatal Care', 'pengkajian_inranatal_care', 'Maternitas', 6, 'Form pengkajian asuhan keperawatan inranatal care', '2026-04-11 16:09:32');


-- Dumping structure for table askep_new.submissions
CREATE TABLE IF NOT EXISTS `submissions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `form_id` int NOT NULL,
  `status` enum('draft','submitted','revision','approved') NOT NULL DEFAULT 'draft',
  `submitted_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `tanggal_pengkajian` date DEFAULT NULL,
  `rs_ruangan` varchar(255) DEFAULT NULL,
  `reviewed_by` int DEFAULT NULL,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `dosen_reviewed_by` int DEFAULT NULL,
  `dosen_reviewed_at` timestamp NULL DEFAULT NULL,
  `dosen_review_status` enum('draft','submitted','revision','approved') DEFAULT NULL,
  `preceptor_reviewed_by` int DEFAULT NULL,
  `preceptor_reviewed_at` timestamp NULL DEFAULT NULL,
  `preceptor_review_status` enum('draft','submitted','revision','approved') DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_submission` (`user_id`,`form_id`),
  KEY `form_id` (`form_id`),
  KEY `FK_submissions_tbl_user_2` (`reviewed_by`),
  KEY `FK_submissions_tbl_user_3` (`dosen_reviewed_by`),
  KEY `FK_submissions_tbl_user_4` (`preceptor_reviewed_by`),
  CONSTRAINT `FK_submissions_tbl_user` FOREIGN KEY (`user_id`) REFERENCES `tbl_user` (`id_user`) ON DELETE CASCADE,
  CONSTRAINT `FK_submissions_tbl_user_2` FOREIGN KEY (`reviewed_by`) REFERENCES `tbl_user` (`id_user`) ON DELETE SET NULL,
  CONSTRAINT `FK_submissions_tbl_user_3` FOREIGN KEY (`dosen_reviewed_by`) REFERENCES `tbl_user` (`id_user`) ON DELETE SET NULL,
  CONSTRAINT `FK_submissions_tbl_user_4` FOREIGN KEY (`preceptor_reviewed_by`) REFERENCES `tbl_user` (`id_user`) ON DELETE SET NULL,
  CONSTRAINT `submissions_ibfk_2` FOREIGN KEY (`form_id`) REFERENCES `forms` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;

-- Dumping structure for table askep_new.submission_sections
CREATE TABLE IF NOT EXISTS `submission_sections` (
  `id` int NOT NULL AUTO_INCREMENT,
  `submission_id` int NOT NULL,
  `section_name` varchar(255) NOT NULL,
  `section_label` varchar(255) NOT NULL,
  `data` json NOT NULL,
  `status` enum('draft','submitted','revision','approved') NOT NULL DEFAULT 'draft',
  `dosen_review_status` enum('draft','submitted','revision','approved') NOT NULL DEFAULT 'draft',
  `preceptor_review_status` enum('draft','submitted','revision','approved') NOT NULL DEFAULT 'draft',
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_section` (`submission_id`,`section_name`),
  CONSTRAINT `submission_sections_ibfk_1` FOREIGN KEY (`submission_id`) REFERENCES `submissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=119 DEFAULT CHARSET=latin1;


-- Dumping structure for table askep_new.section_comments
CREATE TABLE IF NOT EXISTS `section_comments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `submission_id` int NOT NULL,
  `section_name` varchar(255) NOT NULL,
  `comment` text NOT NULL,
  `commented_by` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `submission_id` (`submission_id`),
  KEY `FK_section_comments_tbl_user` (`commented_by`),
  CONSTRAINT `FK_section_comments_tbl_user` FOREIGN KEY (`commented_by`) REFERENCES `tbl_user` (`id_user`) ON DELETE CASCADE,
  CONSTRAINT `section_comments_ibfk_1` FOREIGN KEY (`submission_id`) REFERENCES `submissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

-- Dumping structure for table askep_new.user_notifications
CREATE TABLE IF NOT EXISTS `user_notifications` (
  `id` int NOT NULL AUTO_INCREMENT,
  `recipient_id` int NOT NULL,
  `actor_id` int DEFAULT NULL,
  `submission_id` int NOT NULL,
  `type` enum('resubmitted','revision','approved') NOT NULL,
  `message` varchar(255) NOT NULL,
  `target_url` varchar(255) NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `read_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `recipient_id` (`recipient_id`),
  KEY `actor_id` (`actor_id`),
  KEY `submission_id` (`submission_id`),
  CONSTRAINT `user_notifications_ibfk_1` FOREIGN KEY (`recipient_id`) REFERENCES `tbl_user` (`id_user`) ON DELETE CASCADE,
  CONSTRAINT `user_notifications_ibfk_2` FOREIGN KEY (`actor_id`) REFERENCES `tbl_user` (`id_user`) ON DELETE SET NULL,
  CONSTRAINT `user_notifications_ibfk_3` FOREIGN KEY (`submission_id`) REFERENCES `submissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table askep_new.section_comments: ~0 rows (approximately)

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
