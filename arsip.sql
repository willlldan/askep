-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 25, 2023 at 04:42 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `arsip`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_disposisi`
--

CREATE TABLE `tbl_disposisi` (
  `id_disposisi` int(11) NOT NULL,
  `no_agenda` varchar(20) NOT NULL,
  `no_dokumen` varchar(50) NOT NULL,
  `asal_dokumen` varchar(50) NOT NULL,
  `perihal` varchar(50) NOT NULL,
  `tgl_masuk` date NOT NULL,
  `tgl_disposisi` date NOT NULL,
  `posisi` varchar(50) NOT NULL,
  `keterangan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_dok_keluar`
--

CREATE TABLE `tbl_dok_keluar` (
  `no_dokumen` varchar(20) NOT NULL,
  `update_dokumen` date NOT NULL,
  `status_dokumen` enum('Biasa','Penting','Rahasia') NOT NULL,
  `tgl_keluar_dok` date NOT NULL,
  `perihal` varchar(50) NOT NULL,
  `tujuan` varchar(50) NOT NULL,
  `label_arsip` varchar(20) NOT NULL,
  `rak_arsip` varchar(20) NOT NULL,
  `tgl_pinjam` datetime NOT NULL,
  `peminjaman` enum('Tidak Dipinjam','Dipinjam-Kembali','Dipinjam-Tidak Kembali') NOT NULL,
  `tgl_kembali` datetime NOT NULL,
  `keterangan` text NOT NULL,
  `file` blob DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_dok_keluar`
--

INSERT INTO `tbl_dok_keluar` (`no_dokumen`, `update_dokumen`, `status_dokumen`, `tgl_keluar_dok`, `perihal`, `tujuan`, `label_arsip`, `rak_arsip`, `tgl_pinjam`, `peminjaman`, `tgl_kembali`, `keterangan`, `file`) VALUES
('1111', '0000-00-00', 'Biasa', '2023-10-19', '123', '123', '12333', '123331', '2023-10-20 16:42:00', 'Tidak Dipinjam', '2023-10-19 16:42:00', '123123', '');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_dok_masuk`
--

CREATE TABLE `tbl_dok_masuk` (
  `no_dokumen` varchar(20) NOT NULL,
  `status_tindakan` enum('Proses','Selesai','Arsip') NOT NULL,
  `update_dokumen` date NOT NULL,
  `status_dokumen` enum('Biasa','Penting','Rahasia') NOT NULL,
  `tgl_masuk_dok` date NOT NULL,
  `tgl_terima_dok` date NOT NULL,
  `asal_dokumen` varchar(50) NOT NULL,
  `perihal` varchar(50) NOT NULL,
  `label_arsip` varchar(20) NOT NULL,
  `rak_arsip` varchar(20) NOT NULL,
  `tgl_pinjam` datetime NOT NULL,
  `peminjaman` enum('Tidak Dipinjam','Dipinjam-Kembali','Dipinjam-Tidak Kembali') NOT NULL,
  `tgl_kembali` datetime NOT NULL,
  `keterangan` text NOT NULL,
  `file` blob DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_dok_masuk`
--

INSERT INTO `tbl_dok_masuk` (`no_dokumen`, `status_tindakan`, `update_dokumen`, `status_dokumen`, `tgl_masuk_dok`, `tgl_terima_dok`, `asal_dokumen`, `perihal`, `label_arsip`, `rak_arsip`, `tgl_pinjam`, `peminjaman`, `tgl_kembali`, `keterangan`, `file`) VALUES
('123123', 'Proses', '0000-00-00', 'Biasa', '2023-10-20', '2023-10-20', 'asdasd', 'asdasd', 'asasd', 'a-jan-001', '2023-10-20 16:29:00', 'Dipinjam-Kembali', '2023-10-21 16:29:00', 'asdad', '');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_dok_pendukung`
--

CREATE TABLE `tbl_dok_pendukung` (
  `no_dokumen` varchar(20) NOT NULL,
  `update_dokumen` date NOT NULL,
  `status_dokumen` enum('Biasa','Penting','Rahasia') NOT NULL,
  `tgl_masuk_dok` date NOT NULL,
  `tgl_keluar_dok` date NOT NULL,
  `perihal` varchar(50) NOT NULL,
  `tujuan` varchar(50) NOT NULL,
  `asal_dokumen` varchar(50) NOT NULL,
  `label_arsip` varchar(20) NOT NULL,
  `rak_arsip` varchar(20) NOT NULL,
  `tgl_pinjam` datetime NOT NULL,
  `peminjaman` enum('Tidak Dipinjam','Dipinjam-Kembali','Dipinjam-Tidak Kembali') NOT NULL,
  `tgl_kembali` datetime NOT NULL,
  `keterangan` text NOT NULL,
  `file` blob DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_dok_pendukung`
--

INSERT INTO `tbl_dok_pendukung` (`no_dokumen`, `update_dokumen`, `status_dokumen`, `tgl_masuk_dok`, `tgl_keluar_dok`, `perihal`, `tujuan`, `asal_dokumen`, `label_arsip`, `rak_arsip`, `tgl_pinjam`, `peminjaman`, `tgl_kembali`, `keterangan`, `file`) VALUES
('123213', '0000-00-00', 'Biasa', '2023-10-20', '2023-10-20', 'sabakjd', 'aksjdbakjd', 'aksjdbasjd', 'aksjdbadj', 'askjdbad', '2023-10-20 16:42:00', 'Tidak Dipinjam', '2023-10-20 16:43:00', 'asdkasdk', '');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_dok_personel`
--

CREATE TABLE `tbl_dok_personel` (
  `id_dokumen_personel` int(11) NOT NULL,
  `update_dokumen` date NOT NULL,
  `nrp_nip` varchar(20) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `pangkat_golongan` varchar(50) NOT NULL,
  `kesatuan` varchar(50) NOT NULL,
  `tempat_lahir` varchar(50) NOT NULL,
  `tgl_lahir` date NOT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') NOT NULL,
  `agama` varchar(30) NOT NULL,
  `alamat_rumah` varchar(50) NOT NULL,
  `data_keluarga` text NOT NULL,
  `hasil_urikes` text NOT NULL,
  `hasil_samapta` text NOT NULL,
  `tmt_pangkat_pertama` date NOT NULL,
  `tmt_pangkat_kedua` date NOT NULL,
  `tmt_masuk_satuan` date NOT NULL,
  `no_ktp` varchar(16) NOT NULL,
  `no_bpjs` varchar(13) NOT NULL,
  `no_npwp` varchar(15) NOT NULL,
  `pendidikan_terakhir` text NOT NULL,
  `riwayat_jabatan` text NOT NULL,
  `tanda_kehormatan` text NOT NULL,
  `pendidikan_umum` text NOT NULL,
  `pendidikan_militer` text NOT NULL,
  `pelatihan_khusus` text NOT NULL,
  `label_arsip` varchar(20) NOT NULL,
  `rak_arsip` varchar(20) NOT NULL,
  `keterangan` text NOT NULL,
  `file` blob DEFAULT NULL,
  `foto` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_dok_personel`
--

INSERT INTO `tbl_dok_personel` (`id_dokumen_personel`, `update_dokumen`, `nrp_nip`, `nama`, `pangkat_golongan`, `kesatuan`, `tempat_lahir`, `tgl_lahir`, `jenis_kelamin`, `agama`, `alamat_rumah`, `data_keluarga`, `hasil_urikes`, `hasil_samapta`, `tmt_pangkat_pertama`, `tmt_pangkat_kedua`, `tmt_masuk_satuan`, `no_ktp`, `no_bpjs`, `no_npwp`, `pendidikan_terakhir`, `riwayat_jabatan`, `tanda_kehormatan`, `pendidikan_umum`, `pendidikan_militer`, `pelatihan_khusus`, `label_arsip`, `rak_arsip`, `keterangan`, `file`, `foto`) VALUES
(6, '2023-01-01', 'asdsad', 'askdsakd', 'adsad', 'asdsad', 'asdas', '2023-12-31', 'Perempuan', 'asdkjsabd', 'askjdbask', 'bjabskjbdk', 'b', 'kjb', '2023-12-31', '2023-12-31', '2023-12-31', 'asdjksadk', 'sadad', 'adsad', 'akjsbdkj', 'kjsakjdb', 'asd', 'asjkdbak', 'kjb', 'kjb', 'kjb', 'k', '', '', '20231024163613_wil.png');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_histori_disposisi`
--

CREATE TABLE `tbl_histori_disposisi` (
  `id_histori` int(11) NOT NULL,
  `id_disposisi` int(11) DEFAULT NULL,
  `tgl_update` datetime DEFAULT NULL,
  `tgl_disposisi` date DEFAULT NULL,
  `posisi` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_label_arsip`
--

CREATE TABLE `tbl_label_arsip` (
  `id_label_arsip` int(11) NOT NULL,
  `jenis_dokumen` enum('Dokumen Masuk','Dokumen Keluar','Dokumen Pendukung','Dokumen Personel') NOT NULL,
  `label_arsip` varchar(20) NOT NULL,
  `tanggal_dokumen` date NOT NULL,
  `no_urut_dokumen` varchar(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_label_arsip`
--

INSERT INTO `tbl_label_arsip` (`id_label_arsip`, `jenis_dokumen`, `label_arsip`, `tanggal_dokumen`, `no_urut_dokumen`) VALUES
(1, 'Dokumen Masuk', 'DM', '2023-10-23', '001'),
(2, 'Dokumen Keluar', 'DK', '2023-10-23', '001'),
(3, 'Dokumen Pendukung', 'DG', '2023-10-23', '001'),
(4, 'Dokumen Personel', 'DP', '2023-10-23', '001'),
(5, 'Dokumen Masuk', 'DP', '2023-10-23', '006'),
(6, 'Dokumen Masuk', 'DP', '2023-10-23', '007');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_peminjaman_dokumen`
--

CREATE TABLE `tbl_peminjaman_dokumen` (
  `id_peminjaman_dokumen` int(11) NOT NULL,
  `no_dokumen` varchar(20) NOT NULL,
  `status_peminjam` enum('Peminjam Internal','Peminjam Eksternal') NOT NULL,
  `nama_peminjam` varchar(50) NOT NULL,
  `tgl_pinjam` datetime NOT NULL,
  `tgl_kembali` datetime NOT NULL,
  `keterangan` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_peminjaman_dokumen`
--

INSERT INTO `tbl_peminjaman_dokumen` (`id_peminjaman_dokumen`, `no_dokumen`, `status_peminjam`, `nama_peminjam`, `tgl_pinjam`, `tgl_kembali`, `keterangan`) VALUES
(1, '', 'Peminjam Eksternal', 'asep', '2023-10-20 11:21:00', '2023-10-21 11:21:00', 'abcd'),
(2, '', 'Peminjam Eksternal', 'Dadang', '2023-10-20 13:19:00', '2023-10-22 13:19:00', '');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_peminjaman_dok_personel`
--

CREATE TABLE `tbl_peminjaman_dok_personel` (
  `id_peminjaman_dok_personel` int(11) NOT NULL,
  `nrp_nip` varchar(20) NOT NULL,
  `status_peminjam` enum('Peminjam Internal','Peminjam Eksternal') NOT NULL,
  `nama_peminjam` varchar(50) NOT NULL,
  `tgl_pinjam` datetime NOT NULL,
  `tgl_kembali` datetime NOT NULL,
  `status_peminjaman` enum('Dipinjam-Kembali','Dipinjam-Tidak Kembali') NOT NULL,
  `keterangan` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_peminjaman_dok_personel`
--

INSERT INTO `tbl_peminjaman_dok_personel` (`id_peminjaman_dok_personel`, `nrp_nip`, `status_peminjam`, `nama_peminjam`, `tgl_pinjam`, `tgl_kembali`, `status_peminjaman`, `keterangan`) VALUES
(1, '', 'Peminjam Internal', 'Ucup', '2023-10-20 14:38:00', '2023-10-22 14:38:00', 'Dipinjam-Kembali', 'abcd');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

CREATE TABLE `tbl_user` (
  `id_user` int(11) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `username` varchar(15) NOT NULL,
  `password` varchar(10) NOT NULL,
  `level` enum('Admin','Staff-Dokter') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_user`
--

INSERT INTO `tbl_user` (`id_user`, `nama`, `username`, `password`, `level`) VALUES
(1, 'admin', 'admin', 'admin', 'Admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_disposisi`
--
ALTER TABLE `tbl_disposisi`
  ADD PRIMARY KEY (`id_disposisi`);

--
-- Indexes for table `tbl_dok_keluar`
--
ALTER TABLE `tbl_dok_keluar`
  ADD PRIMARY KEY (`no_dokumen`);

--
-- Indexes for table `tbl_dok_masuk`
--
ALTER TABLE `tbl_dok_masuk`
  ADD PRIMARY KEY (`no_dokumen`);

--
-- Indexes for table `tbl_dok_pendukung`
--
ALTER TABLE `tbl_dok_pendukung`
  ADD PRIMARY KEY (`no_dokumen`);

--
-- Indexes for table `tbl_dok_personel`
--
ALTER TABLE `tbl_dok_personel`
  ADD PRIMARY KEY (`id_dokumen_personel`);

--
-- Indexes for table `tbl_histori_disposisi`
--
ALTER TABLE `tbl_histori_disposisi`
  ADD PRIMARY KEY (`id_histori`),
  ADD KEY `id_disposisi` (`id_disposisi`);

--
-- Indexes for table `tbl_label_arsip`
--
ALTER TABLE `tbl_label_arsip`
  ADD PRIMARY KEY (`id_label_arsip`);

--
-- Indexes for table `tbl_peminjaman_dokumen`
--
ALTER TABLE `tbl_peminjaman_dokumen`
  ADD PRIMARY KEY (`id_peminjaman_dokumen`);

--
-- Indexes for table `tbl_peminjaman_dok_personel`
--
ALTER TABLE `tbl_peminjaman_dok_personel`
  ADD PRIMARY KEY (`id_peminjaman_dok_personel`);

--
-- Indexes for table `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_disposisi`
--
ALTER TABLE `tbl_disposisi`
  MODIFY `id_disposisi` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_dok_personel`
--
ALTER TABLE `tbl_dok_personel`
  MODIFY `id_dokumen_personel` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tbl_histori_disposisi`
--
ALTER TABLE `tbl_histori_disposisi`
  MODIFY `id_histori` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_label_arsip`
--
ALTER TABLE `tbl_label_arsip`
  MODIFY `id_label_arsip` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tbl_peminjaman_dokumen`
--
ALTER TABLE `tbl_peminjaman_dokumen`
  MODIFY `id_peminjaman_dokumen` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_peminjaman_dok_personel`
--
ALTER TABLE `tbl_peminjaman_dok_personel`
  MODIFY `id_peminjaman_dok_personel` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_histori_disposisi`
--
ALTER TABLE `tbl_histori_disposisi`
  ADD CONSTRAINT `tbl_histori_disposisi_ibfk_1` FOREIGN KEY (`id_disposisi`) REFERENCES `tbl_disposisi` (`id_disposisi`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
