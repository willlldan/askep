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
  `level` enum('Admin','Dosen','Mahasiswa') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table askep_new.tbl_user: ~4 rows (approximately)
INSERT INTO `tbl_user` (`id_user`, `nama`, `npm`, `username`, `password`, `level`) VALUES
	(3, 'admin', NULL, 'admin', 'admin', 'Admin'),
	(4, 'mahasiswa', '123123111', 'mahasiswa', 'mahasiswa', 'Mahasiswa'),
	(7, 'Bambang', NULL, 'dosen', 'dosen', 'Dosen'),
	(8, 'tasha', '173040071', 'tasha', 'tasha', 'Mahasiswa');


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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- Dumping data for table askep_new.forms: ~2 rows (approximately)
INSERT INTO `forms` (`id`, `form_name`, `slug`, `department`, `count_section`, `description`, `created_at`) VALUES
	(1, 'Antenatal Care', 'antenatal-care', 'Maternitas', 6, 'Form pengkajian asuhan keperawatan antenatal care', '2026-04-11 09:09:32'),
	(3, 'Pasapartum', 'pascapartum', 'Maternitas', 7, 'Form pengkajian asuhan keperawatan antenatal care', '2026-04-11 09:09:32');

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

-- Dumping data for table askep_new.section_comments: ~4 rows (approximately)
INSERT INTO `section_comments` (`id`, `submission_id`, `section_name`, `comment`, `commented_by`, `created_at`) VALUES
	(2, 7, 'data_demografi', 'nama orang nya salah, revisi !!!', 7, '2026-04-12 08:09:06'),
	(3, 7, 'data_demografi', 'kok masih salah, revisi lagi !!', 7, '2026-04-12 08:09:21'),
	(4, 7, 'data_demografi', 'SALAH, REVISI LAGI', 7, '2026-04-12 08:14:16'),
	(5, 7, 'riwayat_kehamilan_persalinan', 'REVISI!', 7, '2026-04-12 08:38:30'),
	(9, 9, 'data_demografi', 'Revisi namanya salah', 7, '2026-04-12 13:09:06'),
	(10, 9, 'data_demografi', 'pendidkannya salah', 7, '2026-04-12 13:09:27');

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
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_submission` (`user_id`,`form_id`),
  KEY `form_id` (`form_id`),
  KEY `FK_submissions_tbl_user_2` (`reviewed_by`),
  CONSTRAINT `FK_submissions_tbl_user` FOREIGN KEY (`user_id`) REFERENCES `tbl_user` (`id_user`) ON DELETE CASCADE,
  CONSTRAINT `FK_submissions_tbl_user_2` FOREIGN KEY (`reviewed_by`) REFERENCES `tbl_user` (`id_user`) ON DELETE SET NULL,
  CONSTRAINT `submissions_ibfk_2` FOREIGN KEY (`form_id`) REFERENCES `forms` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

-- Dumping data for table askep_new.submissions: ~2 rows (approximately)
INSERT INTO `submissions` (`id`, `user_id`, `form_id`, `status`, `submitted_at`, `updated_at`, `tanggal_pengkajian`, `rs_ruangan`, `reviewed_by`, `reviewed_at`) VALUES
	(7, 4, 1, 'revision', '2026-04-12 10:29:52', '2026-04-12 08:38:30', '2026-04-11', 'Oke deh bro ini di revisi, sorry ye', 7, '2026-04-12 08:38:30'),
	(9, 8, 1, 'approved', '2026-04-12 13:11:34', '2026-04-12 13:12:43', '2026-04-12', 'RSIA Assyifa', 7, '2026-04-12 13:12:43');

-- Dumping structure for table askep_new.submission_sections
CREATE TABLE IF NOT EXISTS `submission_sections` (
  `id` int NOT NULL AUTO_INCREMENT,
  `submission_id` int NOT NULL,
  `section_name` varchar(255) NOT NULL,
  `section_label` varchar(255) NOT NULL,
  `data` json NOT NULL,
  `status` enum('draft','submitted','revision','approved') NOT NULL DEFAULT 'draft',
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_section` (`submission_id`,`section_name`),
  CONSTRAINT `submission_sections_ibfk_1` FOREIGN KEY (`submission_id`) REFERENCES `submissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=latin1;

-- Dumping data for table askep_new.submission_sections: ~12 rows (approximately)
INSERT INTO `submission_sections` (`id`, `submission_id`, `section_name`, `section_label`, `data`, `status`, `updated_at`) VALUES
	(6, 7, 'data_demografi', 'Data Demografi', '{"alamat": "lkj", "nama_suami": "lkj", "usia_istri": "123", "usia_suami": "lkj", "agama_istri": "islam", "agama_suami": "klj", "suku_bangsa": "jklj", "keluhan_utama": "lkj", "diagnosa_medik": "lkj", "inisial_pasien": "ABD EFGH", "pekerjaan_istri": "asd1111", "pekerjaan_suami": "lkj", "status_perkawinan": "lkjl", "riwayat_keluhan_utama": "kl", "pendidikan_terakhir_istri": "SD", "pendidikan_terakhir_suami": "lkj"}', 'approved', '2026-04-12 08:38:18'),
	(7, 7, 'riwayat_kehamilan_persalinan', 'Riwayat Kehamilan dan Persalinan', '{"bbtb": "kjaskjdnakj", "hpht": "sda", "nadi": "asndkajsn", "suhu": "askdjnasjk", "pernapasan": "asjdknajk", "riwayat_kb": "asd", "berapa_lama": "2 Tahun", "lengan_atas": "askjdnajkd", "keadaan_umum": "kasjkdn", "tekanan_darah": "askjdnasjkd", "usia_kehamilan": "asdkjnaskj", "bb_sebelum_hamil": "jknjkasnd", "hasil_ginekologi": "asd", "riwayat_ginekologi": "Tidak", "riwayat_persalinan": [{"no": 1, "jenis": "Normal", "tahun": "2023", "masalah": "Gaada", "penolong": "Ada lah", "jenis_kelamin": ""}], "status_obstetrik_a": "1", "status_obstetrik_g": "12", "status_obstetrik_p": "12", "pengalaman_menyusui": "Ya"}', 'revision', '2026-04-12 08:38:30'),
	(8, 7, 'pengkajian_fisik', 'Pengkajian Fisik', '{"bab": "kjn", "bak": "kjn", "djj": "231", "tfu": "hkj", "striae": "Ada", "vagina": "n", "hemoroid": "kjnk", "keputihan": "kjn", "kontraksi": "Ya", "leopold_i": "Kepala", "intensitas": "n", "kenyamanan": "nkj", "leopold_iv": "Sudah", "pola_tidur": "nkj", "bising_usus": "jn", "bunyi_napas": "kjh", "keteraturan": "knknkjn", "leopold_iii": "Kepala", "linea_nigra": "Ada", "asi_payudara": "kn", "inspeksi_asi": "kjh", "masalah_dada": "hkj", "masalah_mata": "kj", "palpasi_raba": "", "asupan_cairan": "kjn", "cara_mengejan": "jnj", "inspeksi_gigi": "kjh", "inspeksi_gusi": "kj", "masalah_axila": "kjh", "masalah_leher": "kh", "masalah_mulut": "kh", "masalah_wajah": "hk", "palpasi_axila": "kh", "palpasi_mulut": "jh", "palpasi_wajah": "hkj", "suara_jantung": "kj", "asupan_nutrisi": "kjn", "inspeksi_axila": "jh", "inspeksi_bibir": "kjh", "inspeksi_leher": "hkj", "inspeksi_lidah": "hkj", "inspeksi_wajah": "hkj", "masalah_hidung": "h", "masalah_kepala": "kjhkj", "palpasi_hidung": "hj", "palpasi_kepala": "kjh", "palpasi_trakea": "jh", "inspeksi_hidung": "hkj", "inspeksi_kepala": "askdhaskjdh", "inspeksi_puting": "kjh", "inspeksi_sklera": "kjh", "leopold_ii_kiri": "Kepala", "masalah_abdomen": "kjnkj", "masalah_nutrisi": "kj", "masalah_telinga": "kj", "pantangan_makan": "kjn", "ekstremitas_atas": "jkn", "inspeksi_telinga": "kjh", "leopold_ii_kanan": "Kepala", "masalah_payudara": "kj", "masalah_perineum": "n", "nyeri_melahirkan": "nk", "palpasi_benjolan": "", "palpasi_gangguan": "kjh", "palpasi_kelenjar": "hk", "tanda_melahirkan": "nkj", "ekstremitas_bawah": "jkn", "fungsi_pencernaan": "nkjn", "masalah_eliminasi": "kj", "masalah_istirahat": "nk", "palpasi_integumen": "kjn", "inspeksi_bau_mulut": "hk", "inspeksi_integumen": "jkn", "masalah_mobilisasi": "kjn", "tingkat_mobilisasi": "jn", "masalah_ekstremitas": "kjn", "palpasi_nyeri_tekan": "jh", "inspeksi_bentuk_mata": "kjh", "palpasi_kelopak_mata": "kjh", "inspeksi_kelopak_mata": "jh", "palpasi_nyeri_menelan": "kjh", "inspeksi_bentuk_payudara": "h"}', 'draft', '2026-04-11 15:29:16'),
	(9, 7, 'program_terapi_lab', 'Program Terapi dan Laboratorium', '{"lab": [{"hasil": "Bau", "pemeriksaan": "Pipis", "nilai_normal": "aman"}], "obat": [{"dosis": "Banyak", "kegunaan": "Gatau", "jenis_obat": "Amoxilin", "cara_pemberian": "Kasih aja"}]}', 'draft', '2026-04-11 15:50:35'),
	(10, 7, 'analisa_data', 'Analisa Data', '{"analisa": [{"ds_do": "DS/DO", "masalah": "Masalah lu", "etiologi": "Etiologi"}], "klasifikasi": [{"do": "Objektif", "ds": "Gatau"}]}', 'draft', '2026-04-11 15:53:36'),
	(12, 7, 'catatan_keperawatan', 'Catatan Keperawatan', '{"diagnosa": [{"diagnosa": "Ambein", "tgl_teratasi": "2026-04-24", "tgl_ditemukan": "2026-04-08"}], "evaluasi": [{"jam": "02:35", "no_dx": "1", "hari_tgl": "2026-04-24", "evaluasi_a": "asd", "evaluasi_o": "asd", "evaluasi_p": "asd", "evaluasi_s": "asdad"}], "intervensi": [{"diagnosa": "Ambein", "intervensi": "asd", "tujuan_kriteria": "asda"}], "implementasi": [{"jam": "02:37", "no_dx": "1", "hari_tgl": "2026-04-09", "implementasi": "aman bro"}]}', 'draft', '2026-04-11 16:35:14'),
	(30, 9, 'data_demografi', 'Data Demografi', '{"alamat": "Tangeran", "nama_suami": "Wildan F", "usia_istri": "24", "usia_suami": "28", "agama_istri": "Islam", "agama_suami": "ISlam", "suku_bangsa": "Sunda", "keluhan_utama": "Tidak ada", "diagnosa_medik": "Tidak ada", "inisial_pasien": "TRA", "pekerjaan_istri": "IRT", "pekerjaan_suami": "Swasta", "status_perkawinan": "Kawin", "riwayat_keluhan_utama": "Tidak ada", "pendidikan_terakhir_istri": "Mahasiswa", "pendidikan_terakhir_suami": "S1"}', 'approved', '2026-04-12 13:11:49'),
	(32, 9, 'riwayat_kehamilan_persalinan', 'Riwayat Kehamilan dan Persalinan', '{"bbtb": "111/11", "hpht": "2", "nadi": "111", "suhu": "123", "pernapasan": "1", "riwayat_kb": "Aman", "berapa_lama": "2 Tahun", "lengan_atas": "12", "keadaan_umum": "Aman", "tekanan_darah": "11", "usia_kehamilan": "8 Bulan", "bb_sebelum_hamil": "78 Kg", "hasil_ginekologi": "Aman", "riwayat_ginekologi": "Ada Masalah", "riwayat_persalinan": [{"no": 1, "jenis": "Normal", "tahun": "2023", "masalah": "Pecah Ketuban", "penolong": "Mertua", "jenis_kelamin": "Laki-laki"}, {"no": 2, "jenis": "Normal", "tahun": "2024", "masalah": "Aman", "penolong": "Suami", "jenis_kelamin": "Perempuan"}], "status_obstetrik_a": "1", "status_obstetrik_g": "12", "status_obstetrik_p": "12", "pengalaman_menyusui": "Ya"}', 'approved', '2026-04-12 13:09:44'),
	(35, 9, 'pengkajian_fisik', 'Pengkajian Fisik', '{"bab": "", "bak": "", "djj": "", "tfu": "asd", "striae": "", "vagina": "", "hemoroid": "", "keputihan": "", "kontraksi": "Ya", "leopold_i": "Kepala", "intensitas": "", "kenyamanan": "", "leopold_iv": "Sudah", "pola_tidur": "", "bising_usus": "", "bunyi_napas": "", "keteraturan": "", "leopold_iii": "Kepala", "linea_nigra": "", "asi_payudara": "", "inspeksi_asi": "", "masalah_dada": "", "masalah_mata": "asdasd", "palpasi_raba": "", "asupan_cairan": "", "cara_mengejan": "", "inspeksi_gigi": "asdasd", "inspeksi_gusi": "asdasd", "masalah_axila": "", "masalah_leher": "", "masalah_mulut": "asdas", "masalah_wajah": "asda", "palpasi_axila": "", "palpasi_mulut": "asdasd", "palpasi_wajah": "asdas", "suara_jantung": "", "asupan_nutrisi": "", "inspeksi_axila": "", "inspeksi_bibir": "asdasd", "inspeksi_leher": "", "inspeksi_lidah": "asdad", "inspeksi_wajah": "adssa", "masalah_hidung": "asdasd", "masalah_kepala": "asdasd", "palpasi_hidung": "asdasd", "palpasi_kepala": "asdas", "palpasi_trakea": "", "inspeksi_hidung": "asdasd", "inspeksi_kepala": "asdas", "inspeksi_puting": "", "inspeksi_sklera": "asdsad", "leopold_ii_kiri": "Punggung", "masalah_abdomen": "", "masalah_nutrisi": "", "masalah_telinga": "asdas", "pantangan_makan": "", "ekstremitas_atas": "", "inspeksi_telinga": "dasdad", "leopold_ii_kanan": "Punggung", "masalah_payudara": "", "masalah_perineum": "", "nyeri_melahirkan": "", "palpasi_benjolan": "", "palpasi_gangguan": "asdas", "palpasi_kelenjar": "", "tanda_melahirkan": "", "ekstremitas_bawah": "", "fungsi_pencernaan": "", "masalah_eliminasi": "", "masalah_istirahat": "", "palpasi_integumen": "", "inspeksi_bau_mulut": "asdas", "inspeksi_integumen": "", "masalah_mobilisasi": "", "tingkat_mobilisasi": "", "masalah_ekstremitas": "", "palpasi_nyeri_tekan": "asda", "inspeksi_bentuk_mata": "asd", "palpasi_kelopak_mata": "asdasd", "inspeksi_kelopak_mata": "asdas", "palpasi_nyeri_menelan": "", "inspeksi_bentuk_payudara": ""}', 'approved', '2026-04-12 13:10:11'),
	(36, 9, 'program_terapi_lab', 'Program Terapi dan Laboratorium', '{"lab": [{"hasil": "Normal", "pemeriksaan": "Test Darah", "nilai_normal": "Normal"}], "obat": [{"dosis": "20mg", "kegunaan": "Pereda Nyeri", "jenis_obat": "Amoxiling", "cara_pemberian": "Diminum"}]}', 'approved', '2026-04-12 13:12:03'),
	(37, 9, 'analisa_data', 'Analisa Data', '{"analisa": [{"ds_do": "DS/DO", "masalah": "Masalh", "etiologi": "ETiologi"}], "klasifikasi": [{"do": "DO", "ds": "DS"}]}', 'approved', '2026-04-12 13:12:09'),
	(38, 9, 'catatan_keperawatan', 'Catatan Keperawatan', '{"diagnosa": [{"diagnosa": "asdas", "tgl_teratasi": "2026-04-07", "tgl_ditemukan": "2026-04-22"}], "evaluasi": [{"jam": "00:11", "no_dx": "1", "hari_tgl": "2026-04-12", "evaluasi_a": "asd", "evaluasi_o": "asd", "evaluasi_p": "asd", "evaluasi_s": "asdsad"}], "intervensi": [{"diagnosa": "asdasd", "intervensi": "asdas", "tujuan_kriteria": "asdas"}], "implementasi": [{"jam": "20:08", "no_dx": "1", "hari_tgl": "2026-04-12", "implementasi": "asdas"}]}', 'approved', '2026-04-12 13:12:43');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
