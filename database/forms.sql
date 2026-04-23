-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 22, 2026 at 04:21 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `askep_new`
--

-- --------------------------------------------------------

--
-- Table structure for table `forms`
--

CREATE TABLE `forms` (
  `id` int(11) NOT NULL,
  `form_name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `department` varchar(255) NOT NULL,
  `count_section` int(11) NOT NULL DEFAULT 0,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `first_section` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `forms`
--

INSERT INTO `forms` (`id`, `form_name`, `slug`, `department`, `count_section`, `description`, `created_at`, `first_section`) VALUES
(1, 'Antenatal Care', 'pengkajian_antenatal_care', 'Maternitas', 6, 'Form pengkajian asuhan keperawatan antenatal care', '2026-04-11 08:09:32', 'data_demografi'),
(3, 'Pasapartum', 'pascapartum', 'Maternitas', 7, 'Form pengkajian asuhan keperawatan antenatal care', '2026-04-11 08:09:32', NULL),
(4, 'Resume Antenatal Care', 'resume_antenatal_care', 'Maternitas', 0, 'Form Resume Antenatal Care', '2026-04-11 08:09:32', NULL),
(6, 'Pengkajian Ginekologi', 'pengkajian_ginekologi', 'Maternitas', 5, 'Form pengkajian asuhan keperawatan ginekologi', '2026-04-11 08:09:32', NULL),
(7, 'Pengkajian Inranatal Care', 'pengkajian_inranatal_care', 'Maternitas', 6, 'Form pengkajian asuhan keperawatan inranatal care', '2026-04-11 08:09:32', NULL),
(8, 'Format Anggrek B', 'format_anggrek_b', 'Anak', 6, 'Form pengkajian asuhan keperawatan format anggrek B', '2026-04-11 08:09:32', NULL),
(9, 'Format Aster', 'format_aster', 'Anak', 0, 'Form pengkajian asuhan keperawatan format aster', '2026-04-11 08:09:32', NULL),
(10, 'Format Resume Keperawatan Poli Anak', 'format_resume_keperawatan_poli_anak', 'Anak', 0, 'Form resume keperawatan poli anak', '2026-04-11 08:09:32', NULL),
(11, 'Format Askep ICU', 'format_askep_icu', 'Gadar', 0, 'Form pengkajian asuhan keperawatan ICU', '2026-04-11 08:09:32', NULL),
(12, 'Format Askep IGD', 'format_askep_igd', 'Gadar', 0, 'Form pengkajian asuhan keperawatan IGD', '2026-04-11 08:09:32', NULL),
(13, 'Askep Gerontik', 'askep_gerontik', 'Gerontik', 0, 'Form pengkajian asuhan keperawatan gerontik', '2026-04-11 08:09:32', NULL),
(14, 'Format Jiwa RSUD', 'format_jiwa_rsud', 'Jiwa', 0, 'Form pengkajian asuhan keperawatan jiwa RSUD', '2026-04-11 08:09:32', NULL),
(15, 'Format Poli Jiwa', 'format_poli_jiwa', 'Jiwa', 0, 'Form pengkajian asuhan keperawatan poli jiwa', '2026-04-11 08:09:32', NULL),
(16, 'Askep Keluarga', 'askep_keluarga', 'Keluarga', 0, 'Form pengkajian asuhan keperawatan keluarga', '2026-04-11 08:09:32', NULL),
(17, 'Format HD KMB II', 'format_hd_kmb', 'kmb', 6, 'Form pengkajian asuhan keperawatan hemodialisa KMB II', '2026-04-11 08:09:32', 'lp_ruanghd'),
(18, 'Format KMB', 'format_kmb', 'KMB', 0, 'Form pengkajian asuhan keperawatan KMB', '2026-04-11 08:09:32', ''),
(19, 'Format OK KMB II', 'format_ok_kmb_ii', 'KMB', 0, 'Form pengkajian asuhan keperawatan OK KMB II', '2026-04-11 08:09:32', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `forms`
--
ALTER TABLE `forms`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `forms`
--
ALTER TABLE `forms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
