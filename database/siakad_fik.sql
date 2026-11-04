-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 06, 2026 at 06:32 AM
-- Server version: 5.7.44
-- PHP Version: 7.4.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `siakad_fik`
--

-- --------------------------------------------------------

--
-- Table structure for table `tabel_menu`
--

CREATE TABLE `tabel_menu` (
  `id` int(11) NOT NULL,
  `nama_menu` varchar(50) NOT NULL,
  `link` varchar(50) NOT NULL,
  `icon` varchar(25) NOT NULL,
  `is_main_menu` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tabel_menu`
--

INSERT INTO `tabel_menu` (`id`, `nama_menu`, `link`, `icon`, `is_main_menu`) VALUES
(1, 'Data Siswa', 'siswa', 'fa fa-users', 0),
(2, 'Data Guru', 'guru', 'fa fa-user-circle', 0),
(3, 'Data Master', '#', 'fa fa-bars', 0),
(4, 'Mata Pelajaran', 'mapel', 'fa fa-book', 3),
(5, 'Ruangan Kelas', 'ruangan', 'fa fa-building', 3),
(6, 'Tingkatan Kelas', 'tingkatan', 'fa fa-sitemap', 3),
(7, 'Jurusan', 'jurusan', 'fa fa-th-large', 3),
(8, 'Tahun Akademik', 'tahunakademik', 'fa fa-calendar-check-o', 3),
(9, 'Kelas', 'kelas', 'fa fa-cubes', 3),
(10, 'Kurikulum', 'kurikulum', 'fa fa-list', 3),
(11, 'Jadwal Pelajaran', 'jadwal', 'fa fa-calendar-plus-o', 0),
(12, 'Peserta Didik', 'siswa/siswa_aktif', 'fa fa-users', 0),
(13, 'Walikelas', 'walikelas', 'fa fa-user-plus', 0),
(14, 'Pengguna Sistem', 'user', 'fa fa-id-badge', 0),
(15, 'Menu', 'menu', 'fa fa-list', 0),
(16, 'Form Pembayaran', 'pembayaran', 'fa fa-dollar', 0),
(17, 'Nilai', 'nilai', 'fa fa-archive', 0),
(18, 'Laporan Nilai', 'laporan_nilai', 'fa fa-file-pdf-o', 0),
(19, 'Absensi', 'absensi', 'fa fa-calendar-check-o', 0),
(20, 'Profil Saya', 'siswa/profil', 'fa fa-user', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_absensi`
--

CREATE TABLE `tbl_absensi` (
  `id_absensi` int(11) NOT NULL,
  `id_jadwal` int(11) NOT NULL,
  `nim` varchar(11) NOT NULL,
  `tahun_akademik` varchar(10) NOT NULL,
  `semester` varchar(10) NOT NULL,
  `tanggal` date NOT NULL,
  `status` enum('Hadir','Izin','Sakit','Alpha') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_absensi`
--

INSERT INTO `tbl_absensi` (`id_absensi`, `id_jadwal`, `nim`, `tahun_akademik`, `semester`, `tanggal`, `status`) VALUES
(1, 6, '1914', '2025/2026', 'ganjil', '2026-06-16', 'Hadir'),
(2, 6, '1915', '2025/2026', 'ganjil', '2026-06-16', 'Hadir'),
(3, 6, '1916', '2025/2026', 'ganjil', '2026-06-16', 'Hadir'),
(4, 6, '1919', '2025/2026', 'ganjil', '2026-06-16', 'Hadir'),
(5, 6, '1921', '2025/2026', 'ganjil', '2026-06-16', 'Hadir'),
(6, 6, '1922', '2025/2026', 'ganjil', '2026-06-16', 'Hadir'),
(7, 6, '1925', '2025/2026', 'ganjil', '2026-06-16', 'Hadir'),
(8, 6, '1924', '2025/2026', 'ganjil', '2026-06-16', 'Hadir'),
(9, 6, '1930', '2025/2026', 'ganjil', '2026-06-16', 'Hadir'),
(10, 6, '1929', '2025/2026', 'ganjil', '2026-06-16', 'Hadir'),
(11, 6, '1955', '2025/2026', 'ganjil', '2026-06-16', 'Hadir');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_agama`
--

CREATE TABLE `tbl_agama` (
  `kd_agama` int(2) NOT NULL,
  `nama_agama` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_agama`
--

INSERT INTO `tbl_agama` (`kd_agama`, `nama_agama`) VALUES
(1, 'ISLAM'),
(2, 'KRISTEN/ PROTESTAN'),
(3, 'KATHOLIK'),
(4, 'HINDU'),
(5, 'BUDHA'),
(6, 'KHONG HU CHU'),
(99, 'LAIN LAIN');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_guru`
--

CREATE TABLE `tbl_guru` (
  `id_guru` int(11) NOT NULL,
  `nuptk` varchar(11) NOT NULL,
  `nama_guru` varchar(40) NOT NULL,
  `gender` enum('P','W') NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_guru`
--

INSERT INTO `tbl_guru` (`id_guru`, `nuptk`, `nama_guru`, `gender`, `username`, `password`) VALUES
(0, '00000000000', 'Default', 'W', '', ''),
(2, '00000000002', 'Teuku Tommy Yanuar Satria, S.Pd.I', 'P', '', ''),
(3, '00000000004', 'Mariyadi, A.Md', 'P', 'mariyadi', '14e1b600b1fd579f47433b88e8d85291'),
(4, '00000000003', 'Dita Ayu Lestari', 'W', 'dita', 'e10adc3949ba59abbe56e057f20f883e'),
(5, '00000000009', 'Rendra Agus Prawira', 'P', 'rendra', 'e10adc3949ba59abbe56e057f20f883e'),
(6, '00000000001', 'Fajri', 'P', 'fajri', 'e10adc3949ba59abbe56e057f20f883e');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_jadwal`
--

CREATE TABLE `tbl_jadwal` (
  `id_jadwal` int(11) NOT NULL,
  `id_tahun_akademik` int(11) NOT NULL,
  `semester` varchar(10) NOT NULL,
  `kd_jurusan` varchar(5) NOT NULL,
  `kd_tingkatan` varchar(5) NOT NULL,
  `kd_kelas` varchar(5) NOT NULL,
  `kd_mapel` varchar(5) NOT NULL,
  `id_guru` int(11) NOT NULL,
  `jam` varchar(30) NOT NULL,
  `kd_ruangan` varchar(10) NOT NULL,
  `hari` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_jadwal`
--

INSERT INTO `tbl_jadwal` (`id_jadwal`, `id_tahun_akademik`, `semester`, `kd_jurusan`, `kd_tingkatan`, `kd_kelas`, `kd_mapel`, `id_guru`, `jam`, `kd_ruangan`, `hari`) VALUES
(4, 6, 'genap', 'J01', '10', '10-E1', 'BID1', 4, '07.15 - 08.00', 'XE1', 'Senin'),
(6, 1, 'ganjil', 'J01', '10', '10-E1', 'MTK1', 4, '07.15 - 08.00', 'XE1', 'Rabu'),
(7, 1, 'ganjil', 'J01', '10', '10-E1', 'PAI1', 4, '07.15 - 08.00', 'XE1', 'Senin'),
(8, 1, 'ganjil', 'J01', '10', '10-E1', 'BIO1', 0, '07.15 - 08.00', 'XE1', 'Selasa'),
(9, 1, 'ganjil', 'J01', '10', '10-E1', 'BID1', 2, '07.15 - 08.00', 'XE1', 'Kamis'),
(10, 7, 'Ganjil', 'J02', '11', '11-F1', 'BID1', 6, '12.15 - 13.00', 'XIF1', 'Rabu'),
(11, 7, 'Ganjil', 'J02', '11', '11-F1', 'MTK2', 6, '13.00 - 13.30', 'XIF1', 'Rabu'),
(14, 1, 'ganjil', 'J01', '10', '10-E2', 'BID1', 2, '07.15 - 08.00', 'XE2', 'Senin');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_jurusan`
--

CREATE TABLE `tbl_jurusan` (
  `kd_jurusan` varchar(5) NOT NULL,
  `nama_jurusan` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_jurusan`
--

INSERT INTO `tbl_jurusan` (`kd_jurusan`, `nama_jurusan`) VALUES
('J01', 'Umum'),
('J02', 'Sains'),
('J03', 'Sosiologi');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_kelas`
--

CREATE TABLE `tbl_kelas` (
  `kd_kelas` varchar(5) NOT NULL,
  `nama_kelas` varchar(30) NOT NULL,
  `kd_tingkatan` varchar(5) NOT NULL,
  `kd_jurusan` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_kelas`
--

INSERT INTO `tbl_kelas` (`kd_kelas`, `nama_kelas`, `kd_tingkatan`, `kd_jurusan`) VALUES
('10-E1', 'Kelas 10-E1 Umum', '10', 'J01'),
('10-E2', 'Kelas 10-E2 Umum', '10', 'J01'),
('10-E3', 'Kelas 10-E3 Umum', '10', 'J01'),
('10-E4', 'Kelas 10-E4 Umum', '10', 'J01'),
('10-E5', 'Kelas 10-E5 Umum', '10', 'J01'),
('11-F1', 'Kelas 11-F1 Sains', '11', 'J02'),
('11-F2', 'Kelas 11-F2 Sains', '11', 'J02'),
('11-F3', 'Kelas 11-F3 Sosiologi', '11', 'J03'),
('11-F4', 'Kelas 11-F4 Sosiologi', '11', 'J03'),
('11-F5', 'Kelas 11-F5 Sosiologi', '11', 'J03'),
('12-F1', 'Kelas 12-F1 Sains', '12', 'J02'),
('12-F2', 'Kelas 12-F2 Sains', '12', 'J02'),
('12-F3', 'Kelas 12-F3 Sosiologi', '12', 'J03'),
('12-F4', 'Kelas 12-F4 Sosiologi', '12', 'J03'),
('12-F5', 'Kelas 12-F5 Sosiologi', '12', 'J03');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_kurikulum`
--

CREATE TABLE `tbl_kurikulum` (
  `id_kurikulum` int(11) NOT NULL,
  `nama_kurikulum` varchar(30) NOT NULL,
  `is_aktif` enum('Y','N') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_kurikulum`
--

INSERT INTO `tbl_kurikulum` (`id_kurikulum`, `nama_kurikulum`, `is_aktif`) VALUES
(1, 'Kurikulum Satuan Pendidikan', 'Y'),
(2, 'Kurikulum 2006 (KTSP)', 'N'),
(3, 'Kurikulum 2004 (KBK)', 'N');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_kurikulum_detail`
--

CREATE TABLE `tbl_kurikulum_detail` (
  `id_kurikulum_detail` int(11) NOT NULL,
  `id_kurikulum` int(11) NOT NULL,
  `kd_mapel` varchar(5) NOT NULL,
  `kd_jurusan` varchar(5) NOT NULL,
  `kd_tingkatan` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_level_user`
--

CREATE TABLE `tbl_level_user` (
  `id_level_user` int(11) NOT NULL,
  `nama_level` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_level_user`
--

INSERT INTO `tbl_level_user` (`id_level_user`, `nama_level`) VALUES
(1, 'Admin'),
(2, 'Walikelas'),
(3, 'Guru'),
(4, 'Kepala Sekolah'),
(5, 'Siswa');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_mapel`
--

CREATE TABLE `tbl_mapel` (
  `kd_mapel` varchar(5) NOT NULL,
  `nama_mapel` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_mapel`
--

INSERT INTO `tbl_mapel` (`kd_mapel`, `nama_mapel`) VALUES
('BID1', 'Bahasa Indonesia 1'),
('BID2', 'Bahasa Indonesia 2'),
('BID3', 'Bahasa Indonesia 3'),
('BIO1', 'Biologi 1'),
('BIO2', 'Biologi 2'),
('BIO3', 'Biologi 3'),
('MTK1', 'Matematika 1'),
('MTK2', 'Matematika 2'),
('MTK3', 'Matematika 3'),
('PAI1', 'Pendidikan Agama Islam 1'),
('PAI2', 'Pendidikan Agama Islam 2'),
('PAI3', 'Pendidikan Agama Islam 3');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_nilai`
--

CREATE TABLE `tbl_nilai` (
  `id_nilai` int(11) NOT NULL,
  `id_jadwal` int(11) NOT NULL,
  `nim` varchar(11) NOT NULL,
  `nilai` int(11) NOT NULL,
  `keterangan` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_nilai`
--

INSERT INTO `tbl_nilai` (`id_nilai`, `id_jadwal`, `nim`, `nilai`, `keterangan`) VALUES
(1, 6, '1914', 100, ''),
(2, 6, '1915', 75, NULL),
(3, 10, '1915', 74, 'saya kurang belajar'),
(4, 11, '1915', 100, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_nilai_catatan`
--

CREATE TABLE `tbl_nilai_catatan` (
  `id_catatan` int(11) NOT NULL,
  `nim` varchar(11) NOT NULL,
  `catatan` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_nilai_catatan`
--

INSERT INTO `tbl_nilai_catatan` (`id_catatan`, `nim`, `catatan`) VALUES
(1, '1914', 'asda');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_riwayat_kelas`
--

CREATE TABLE `tbl_riwayat_kelas` (
  `id_riwayat` int(11) NOT NULL,
  `kd_kelas` varchar(5) NOT NULL,
  `nim` varchar(11) NOT NULL,
  `id_tahun_akademik` int(11) NOT NULL,
  `semester` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_riwayat_kelas`
--

INSERT INTO `tbl_riwayat_kelas` (`id_riwayat`, `kd_kelas`, `nim`, `id_tahun_akademik`, `semester`) VALUES
(3, '10-E1', '1914', 1, NULL),
(4, '10-E1', '1915', 1, NULL),
(5, '10-E1', '1916', 1, NULL),
(6, '10-E1', '1919', 1, NULL),
(7, '10-E1', '1921', 1, NULL),
(8, '10-E1', '1922', 1, NULL),
(9, '10-E1', '1925', 1, NULL),
(10, '10-E1', '1924', 1, NULL),
(11, '10-E1', '1930', 1, NULL),
(12, '10-E1', '1929', 1, NULL),
(13, '10-E2', '1953', 1, NULL),
(14, '10-E2', '1954', 1, NULL),
(15, '10-E1', '1955', 1, NULL),
(16, '10-E2', '1956', 1, NULL),
(17, '10-E2', '1957', 1, NULL),
(18, '10-E2', '1959', 1, NULL),
(19, '11-F1', '1953', 1, NULL),
(20, '11-F1', '1954', 1, NULL),
(21, '11-F1', '1956', 1, NULL),
(22, '11-F1', '1957', 1, NULL),
(23, '11-F1', '1959', 1, NULL),
(24, '10-E2', '1953', 1, NULL),
(25, '10-E2', '1954', 1, NULL),
(26, '10-E2', '1956', 1, NULL),
(27, '10-E2', '1957', 1, NULL),
(28, '10-E2', '1959', 1, NULL),
(29, '11-F1', '1953', 1, NULL),
(30, '10-E2', '1953', 1, NULL),
(31, '11-F1', '1915', 1, NULL),
(32, '11-F1', '1915', 7, 'Ganjil');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_ruangan`
--

CREATE TABLE `tbl_ruangan` (
  `kd_ruangan` varchar(10) NOT NULL,
  `nama_ruangan` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_ruangan`
--

INSERT INTO `tbl_ruangan` (`kd_ruangan`, `nama_ruangan`) VALUES
('000', 'Default'),
('XE1', 'Ruangan Kelas X-E1 UMUM'),
('XE2', 'Ruangan Kelas X-E2 UMUM'),
('XE3', 'Ruangan Kelas X-E3 UMUM'),
('XE4', 'Ruangan Kelas X-E4 UMUM'),
('XE5', 'Ruangan Kelas X-E5 UMUM'),
('XIF1', 'Ruangan Kelas X1-F1 SAINS'),
('XIF2', 'Ruangan Kelas X1-F2 SAINS'),
('XIF3', 'Ruangan Kelas X1-F3 SOSIOLOGI'),
('XIF4', 'Ruangan Kelas X1-F4 SOSIOLOGI'),
('XIF5', 'Ruangan Kelas XI-F5 SOSIOLOGI'),
('XII1', 'Ruangan Kelas XII-F1 SAINS'),
('XII2', 'Ruangan Kelas XII-F2 SAINS'),
('XII3', 'Ruangan Kelas XII-F3 SOSIOLOGI'),
('XII4', 'Ruangan Kelas XII-F4 SOSIOLOGI'),
('XII5', 'Ruangan Kelas XII-F5 SOSIOLOGI');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_siswa`
--

CREATE TABLE `tbl_siswa` (
  `nim` varchar(11) NOT NULL,
  `nama` varchar(40) NOT NULL,
  `gender` enum('L','P') NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `alamat` text,
  `tempat_lahir` varchar(30) NOT NULL,
  `kd_agama` int(2) NOT NULL,
  `foto` text,
  `kd_kelas` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_siswa`
--

INSERT INTO `tbl_siswa` (`nim`, `nama`, `gender`, `tanggal_lahir`, `alamat`, `tempat_lahir`, `kd_agama`, `foto`, `kd_kelas`) VALUES
('1914', 'Abdan Hanifan', 'L', '2011-01-14', NULL, 'Jambi', 1, NULL, '10-E1'),
('1915', 'Abdul Rohim', 'L', '2011-01-11', NULL, 'Jambi', 1, NULL, '11-F1'),
('1916', 'ACHMAD ARIEF ZULADHA', 'L', '2011-02-16', NULL, 'Jambi', 1, NULL, '10-E1'),
('1919', 'DALIA ARIFAH', 'P', '2011-01-18', NULL, 'Jambi', 1, NULL, '10-E1'),
('1921', 'DEA AGUSTIANI', 'P', '2011-08-26', NULL, 'Jambi', 1, NULL, '10-E1'),
('1922', 'DEA SAPUTRI', 'P', '2011-12-19', NULL, 'Jambi', 1, NULL, '10-E1'),
('1924', 'Fattaahu Rizki', 'L', '2011-05-18', NULL, 'Jambi', 1, NULL, '10-E1'),
('1925', 'FEBRYANTO CHRYSTYAN SIHOMBING', 'L', '2011-10-09', NULL, 'Jambi', 2, NULL, '10-E1'),
('1929', 'KEYSHA NAMIRA ABELIA', 'P', '2011-06-07', NULL, 'Bungo', 1, NULL, '10-E1'),
('1930', 'Khayla Andini Putri', 'P', '2011-02-09', NULL, 'Tebo', 1, NULL, '10-E1'),
('1953', 'AL-FATHIR RIZQI AKBAR', 'L', '0000-00-00', NULL, '', 1, NULL, '10-E2'),
('1954', 'Aldi Puji Lastyo', 'L', '0000-00-00', NULL, '', 1, NULL, '10-E2'),
('1955', 'DESTY ANUGRAH STEFANY', 'P', '0000-00-00', NULL, '', 1, NULL, '10-E1'),
('1956', 'Dewi Oktaviani', 'P', '0000-00-00', NULL, '', 1, NULL, '10-E2'),
('1957', 'Dhisya Najwaa Fadhilah', 'P', '0000-00-00', NULL, '', 1, NULL, '10-E2'),
('1959', 'DINDA AULIA SAFITRI', 'P', '0000-00-00', NULL, '', 1, NULL, '10-E2');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_tahun_akademik`
--

CREATE TABLE `tbl_tahun_akademik` (
  `id_tahun_akademik` int(11) NOT NULL,
  `tahun_akademik` varchar(10) NOT NULL,
  `is_aktif` enum('Y','N') NOT NULL,
  `semester` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_tahun_akademik`
--

INSERT INTO `tbl_tahun_akademik` (`id_tahun_akademik`, `tahun_akademik`, `is_aktif`, `semester`) VALUES
(1, '2025/2026', 'Y', 'ganjil'),
(5, '2019/2020', 'N', 'genap'),
(6, '2025/2026', 'N', 'genap'),
(7, '2026/2027', 'N', 'Ganjil');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_tingkatan_kelas`
--

CREATE TABLE `tbl_tingkatan_kelas` (
  `kd_tingkatan` varchar(5) NOT NULL,
  `nama_tingkatan` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_tingkatan_kelas`
--

INSERT INTO `tbl_tingkatan_kelas` (`kd_tingkatan`, `nama_tingkatan`) VALUES
('10', 'Kelas 10'),
('11', 'Kelas 11'),
('12', 'Kelas 12');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

CREATE TABLE `tbl_user` (
  `id_user` int(11) NOT NULL,
  `nama_lengkap` varchar(40) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(40) NOT NULL,
  `id_level_user` int(11) NOT NULL,
  `foto` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_user`
--

INSERT INTO `tbl_user` (`id_user`, `nama_lengkap`, `username`, `password`, `id_level_user`, `foto`) VALUES
(1, 'admin', 'admin', 'e10adc3949ba59abbe56e057f20f883e', 1, 'user-siluet2.jpg'),
(2, 'Muhammad Mulvi', 'mulvi', 'e10adc3949ba59abbe56e057f20f883e', 1, 'user-siluet1.jpg'),
(5, 'Dita Ayu Lestari', 'dita', '10ed1697617fe7758b4236d5b791286c', 3, 'user_icon1.png'),
(6, 'Ika Kartikasari, S.Si, M.Pd', 'ika', 'e10adc3949ba59abbe56e057f20f883e', 4, 'user_icon2.png'),
(10, 'Abdul Rohim', '1915', 'b432f34c5a997c8e7c806a895ecc5e25', 5, ''),
(11, 'Rendra Agus Prawira', 'rendra', '14e1b600b1fd579f47433b88e8d85291', 2, '');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user_rule`
--

CREATE TABLE `tbl_user_rule` (
  `id_rule` int(11) NOT NULL,
  `id_menu` int(11) NOT NULL,
  `id_level_user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_user_rule`
--

INSERT INTO `tbl_user_rule` (`id_rule`, `id_menu`, `id_level_user`) VALUES
(2, 1, 1),
(3, 2, 1),
(4, 3, 1),
(5, 4, 1),
(6, 5, 1),
(7, 7, 1),
(8, 8, 1),
(9, 11, 1),
(10, 6, 1),
(11, 14, 1),
(13, 13, 1),
(14, 12, 1),
(15, 10, 1),
(16, 9, 1),
(17, 11, 3),
(19, 17, 3),
(21, 12, 3),
(22, 18, 2),
(23, 17, 2),
(24, 13, 2),
(25, 6, 2),
(26, 5, 2),
(27, 7, 2),
(28, 1, 2),
(29, 1, 4),
(30, 2, 4),
(31, 11, 4),
(36, 18, 4),
(37, 17, 5),
(39, 4, 5),
(40, 11, 5),
(41, 20, 5),
(44, 18, 3);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_walikelas`
--

CREATE TABLE `tbl_walikelas` (
  `id_walikelas` int(11) NOT NULL,
  `id_guru` int(11) DEFAULT NULL,
  `id_tahun_akademik` int(11) NOT NULL,
  `kd_kelas` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_walikelas`
--

INSERT INTO `tbl_walikelas` (`id_walikelas`, `id_guru`, `id_tahun_akademik`, `kd_kelas`) VALUES
(1, 4, 1, '10-E1'),
(2, 0, 1, '10-E2'),
(3, 0, 1, '10-E3'),
(4, 0, 1, '10-E4'),
(5, 0, 1, '10-E5'),
(6, 0, 1, '11-F1'),
(7, 0, 1, '11-F2'),
(8, 0, 1, '11-F3'),
(9, 0, 1, '11-F4'),
(10, 0, 1, '11-F5'),
(11, 0, 1, '12-F1'),
(12, 0, 1, '12-F2'),
(13, 0, 1, '12-F3'),
(14, 0, 1, '12-F4'),
(15, 0, 1, '12-F5'),
(26, 6, 6, '12-F1'),
(27, 0, 6, '12-F2'),
(28, 0, 6, '12-F3'),
(29, 0, 6, '12-F4'),
(30, 0, 6, '12-F5'),
(31, 0, 7, '10-E1'),
(32, 0, 7, '10-E2'),
(33, 0, 7, '10-E3'),
(34, 0, 7, '10-E4'),
(35, 0, 7, '10-E5'),
(36, 6, 7, '11-F1'),
(37, 0, 7, '11-F2'),
(38, 0, 7, '11-F3'),
(39, 0, 7, '11-F4'),
(40, 0, 7, '11-F5'),
(41, 0, 7, '12-F1'),
(42, 0, 7, '12-F2'),
(43, 0, 7, '12-F3'),
(44, 0, 7, '12-F4'),
(45, 0, 7, '12-F5');

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_kelas`
-- (See below for the actual view)
--
CREATE TABLE `view_kelas` (
`kd_kelas` varchar(5)
,`nama_kelas` varchar(30)
,`kd_tingkatan` varchar(5)
,`kd_jurusan` varchar(5)
,`nama_tingkatan` varchar(30)
,`nama_jurusan` varchar(30)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_user`
-- (See below for the actual view)
--
CREATE TABLE `view_user` (
`id_user` int(11)
,`nama_lengkap` varchar(40)
,`username` varchar(30)
,`password` varchar(40)
,`id_level_user` int(11)
,`foto` text
,`nama_level` varchar(30)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_walikelas`
-- (See below for the actual view)
--
CREATE TABLE `view_walikelas` (
`id_walikelas` int(11)
,`nama_kelas` varchar(30)
,`nama_jurusan` varchar(30)
,`nama_tingkatan` varchar(30)
,`tahun_akademik` varchar(10)
);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tabel_menu`
--
ALTER TABLE `tabel_menu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_absensi`
--
ALTER TABLE `tbl_absensi`
  ADD PRIMARY KEY (`id_absensi`),
  ADD KEY `id_jadwal` (`id_jadwal`),
  ADD KEY `id_jadwal_2` (`id_jadwal`),
  ADD KEY `nim` (`nim`);

--
-- Indexes for table `tbl_agama`
--
ALTER TABLE `tbl_agama`
  ADD PRIMARY KEY (`kd_agama`);

--
-- Indexes for table `tbl_guru`
--
ALTER TABLE `tbl_guru`
  ADD PRIMARY KEY (`id_guru`);

--
-- Indexes for table `tbl_jadwal`
--
ALTER TABLE `tbl_jadwal`
  ADD PRIMARY KEY (`id_jadwal`),
  ADD KEY `fk_jadwal_tahun` (`id_tahun_akademik`),
  ADD KEY `fk_jadwal_jurusan` (`kd_jurusan`),
  ADD KEY `fk_jadwal_tingkatan` (`kd_tingkatan`),
  ADD KEY `fk_jadwal_kelas` (`kd_kelas`),
  ADD KEY `fk_jadwal_mapel` (`kd_mapel`),
  ADD KEY `fk_jadwal_guru` (`id_guru`),
  ADD KEY `fk_jadwal_ruangan` (`kd_ruangan`);

--
-- Indexes for table `tbl_jurusan`
--
ALTER TABLE `tbl_jurusan`
  ADD PRIMARY KEY (`kd_jurusan`);

--
-- Indexes for table `tbl_kelas`
--
ALTER TABLE `tbl_kelas`
  ADD PRIMARY KEY (`kd_kelas`),
  ADD KEY `fk_kelas_jurusan` (`kd_jurusan`),
  ADD KEY `fk_kelas_tingkatan` (`kd_tingkatan`);

--
-- Indexes for table `tbl_kurikulum`
--
ALTER TABLE `tbl_kurikulum`
  ADD PRIMARY KEY (`id_kurikulum`);

--
-- Indexes for table `tbl_kurikulum_detail`
--
ALTER TABLE `tbl_kurikulum_detail`
  ADD PRIMARY KEY (`id_kurikulum_detail`),
  ADD KEY `fk_kurikulum_detail_kurikulum` (`id_kurikulum`),
  ADD KEY `fk_kurikulum_detail_mapel` (`kd_mapel`),
  ADD KEY `fk_kurikulum_detail_jurusan` (`kd_jurusan`),
  ADD KEY `fk_kurikulum_detail_tingkatan` (`kd_tingkatan`);

--
-- Indexes for table `tbl_level_user`
--
ALTER TABLE `tbl_level_user`
  ADD PRIMARY KEY (`id_level_user`);

--
-- Indexes for table `tbl_mapel`
--
ALTER TABLE `tbl_mapel`
  ADD PRIMARY KEY (`kd_mapel`);

--
-- Indexes for table `tbl_nilai`
--
ALTER TABLE `tbl_nilai`
  ADD PRIMARY KEY (`id_nilai`),
  ADD KEY `fk_nilai_siswa_fix` (`nim`),
  ADD KEY `fk_nilai_jadwal_fix` (`id_jadwal`);

--
-- Indexes for table `tbl_nilai_catatan`
--
ALTER TABLE `tbl_nilai_catatan`
  ADD PRIMARY KEY (`id_catatan`),
  ADD KEY `nim` (`nim`);

--
-- Indexes for table `tbl_riwayat_kelas`
--
ALTER TABLE `tbl_riwayat_kelas`
  ADD PRIMARY KEY (`id_riwayat`),
  ADD KEY `fk_riwayat_kelas_fix` (`kd_kelas`);

--
-- Indexes for table `tbl_ruangan`
--
ALTER TABLE `tbl_ruangan`
  ADD PRIMARY KEY (`kd_ruangan`);

--
-- Indexes for table `tbl_siswa`
--
ALTER TABLE `tbl_siswa`
  ADD PRIMARY KEY (`nim`),
  ADD KEY `fk_siswa_kelas` (`kd_kelas`),
  ADD KEY `fk_siswa_agama` (`kd_agama`);

--
-- Indexes for table `tbl_tahun_akademik`
--
ALTER TABLE `tbl_tahun_akademik`
  ADD PRIMARY KEY (`id_tahun_akademik`);

--
-- Indexes for table `tbl_tingkatan_kelas`
--
ALTER TABLE `tbl_tingkatan_kelas`
  ADD PRIMARY KEY (`kd_tingkatan`);

--
-- Indexes for table `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`id_user`),
  ADD KEY `fk_user_level` (`id_level_user`);

--
-- Indexes for table `tbl_user_rule`
--
ALTER TABLE `tbl_user_rule`
  ADD PRIMARY KEY (`id_rule`),
  ADD KEY `fk_rule_menu` (`id_menu`),
  ADD KEY `fk_rule_level` (`id_level_user`);

--
-- Indexes for table `tbl_walikelas`
--
ALTER TABLE `tbl_walikelas`
  ADD PRIMARY KEY (`id_walikelas`),
  ADD KEY `fk_walikelas_guru` (`id_guru`),
  ADD KEY `fk_walikelas_tahun` (`id_tahun_akademik`),
  ADD KEY `fk_walikelas_kelas` (`kd_kelas`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tabel_menu`
--
ALTER TABLE `tabel_menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `tbl_absensi`
--
ALTER TABLE `tbl_absensi`
  MODIFY `id_absensi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tbl_guru`
--
ALTER TABLE `tbl_guru`
  MODIFY `id_guru` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tbl_jadwal`
--
ALTER TABLE `tbl_jadwal`
  MODIFY `id_jadwal` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `tbl_kurikulum`
--
ALTER TABLE `tbl_kurikulum`
  MODIFY `id_kurikulum` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_kurikulum_detail`
--
ALTER TABLE `tbl_kurikulum_detail`
  MODIFY `id_kurikulum_detail` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_level_user`
--
ALTER TABLE `tbl_level_user`
  MODIFY `id_level_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_nilai`
--
ALTER TABLE `tbl_nilai`
  MODIFY `id_nilai` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_nilai_catatan`
--
ALTER TABLE `tbl_nilai_catatan`
  MODIFY `id_catatan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_riwayat_kelas`
--
ALTER TABLE `tbl_riwayat_kelas`
  MODIFY `id_riwayat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `tbl_tahun_akademik`
--
ALTER TABLE `tbl_tahun_akademik`
  MODIFY `id_tahun_akademik` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tbl_user_rule`
--
ALTER TABLE `tbl_user_rule`
  MODIFY `id_rule` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `tbl_walikelas`
--
ALTER TABLE `tbl_walikelas`
  MODIFY `id_walikelas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

-- --------------------------------------------------------

--
-- Structure for view `view_kelas`
--
DROP TABLE IF EXISTS `view_kelas`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_kelas`  AS SELECT `tk`.`kd_kelas` AS `kd_kelas`, `tk`.`nama_kelas` AS `nama_kelas`, `tk`.`kd_tingkatan` AS `kd_tingkatan`, `tk`.`kd_jurusan` AS `kd_jurusan`, `ttk`.`nama_tingkatan` AS `nama_tingkatan`, `tj`.`nama_jurusan` AS `nama_jurusan` FROM ((`tbl_kelas` `tk` join `tbl_tingkatan_kelas` `ttk`) join `tbl_jurusan` `tj`) WHERE ((`tk`.`kd_tingkatan` = `ttk`.`kd_tingkatan`) AND (`tk`.`kd_jurusan` = `tj`.`kd_jurusan`)) ;

-- --------------------------------------------------------

--
-- Structure for view `view_user`
--
DROP TABLE IF EXISTS `view_user`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_user`  AS SELECT `tu`.`id_user` AS `id_user`, `tu`.`nama_lengkap` AS `nama_lengkap`, `tu`.`username` AS `username`, `tu`.`password` AS `password`, `tu`.`id_level_user` AS `id_level_user`, `tu`.`foto` AS `foto`, `tlu`.`nama_level` AS `nama_level` FROM (`tbl_user` `tu` join `tbl_level_user` `tlu`) WHERE (`tu`.`id_level_user` = `tlu`.`id_level_user`) ;

-- --------------------------------------------------------

--
-- Structure for view `view_walikelas`
--
DROP TABLE IF EXISTS `view_walikelas`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_walikelas`  AS SELECT max(`w`.`id_walikelas`) AS `id_walikelas`, `k`.`nama_kelas` AS `nama_kelas`, `j`.`nama_jurusan` AS `nama_jurusan`, `t`.`nama_tingkatan` AS `nama_tingkatan`, `ta`.`tahun_akademik` AS `tahun_akademik` FROM ((((`tbl_walikelas` `w` join `tbl_kelas` `k` on((`w`.`kd_kelas` = `k`.`kd_kelas`))) join `tbl_jurusan` `j` on((`k`.`kd_jurusan` = `j`.`kd_jurusan`))) join `tbl_tingkatan_kelas` `t` on((`k`.`kd_tingkatan` = `t`.`kd_tingkatan`))) join `tbl_tahun_akademik` `ta` on((`w`.`id_tahun_akademik` = `ta`.`id_tahun_akademik`))) GROUP BY `k`.`nama_kelas`, `j`.`nama_jurusan`, `t`.`nama_tingkatan`, `ta`.`tahun_akademik` ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_absensi`
--
ALTER TABLE `tbl_absensi`
  ADD CONSTRAINT `tbl_absensi_ibfk_1` FOREIGN KEY (`id_jadwal`) REFERENCES `tbl_jadwal` (`id_jadwal`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_jadwal`
--
ALTER TABLE `tbl_jadwal`
  ADD CONSTRAINT `fk_jadwal_guru` FOREIGN KEY (`id_guru`) REFERENCES `tbl_guru` (`id_guru`),
  ADD CONSTRAINT `fk_jadwal_jurusan` FOREIGN KEY (`kd_jurusan`) REFERENCES `tbl_jurusan` (`kd_jurusan`),
  ADD CONSTRAINT `fk_jadwal_kelas` FOREIGN KEY (`kd_kelas`) REFERENCES `tbl_kelas` (`kd_kelas`),
  ADD CONSTRAINT `fk_jadwal_mapel` FOREIGN KEY (`kd_mapel`) REFERENCES `tbl_mapel` (`kd_mapel`),
  ADD CONSTRAINT `fk_jadwal_ruangan` FOREIGN KEY (`kd_ruangan`) REFERENCES `tbl_ruangan` (`kd_ruangan`),
  ADD CONSTRAINT `fk_jadwal_tahun` FOREIGN KEY (`id_tahun_akademik`) REFERENCES `tbl_tahun_akademik` (`id_tahun_akademik`),
  ADD CONSTRAINT `fk_jadwal_tingkatan` FOREIGN KEY (`kd_tingkatan`) REFERENCES `tbl_tingkatan_kelas` (`kd_tingkatan`);

--
-- Constraints for table `tbl_kelas`
--
ALTER TABLE `tbl_kelas`
  ADD CONSTRAINT `fk_kelas_jurusan` FOREIGN KEY (`kd_jurusan`) REFERENCES `tbl_jurusan` (`kd_jurusan`),
  ADD CONSTRAINT `fk_kelas_tingkatan` FOREIGN KEY (`kd_tingkatan`) REFERENCES `tbl_tingkatan_kelas` (`kd_tingkatan`);

--
-- Constraints for table `tbl_kurikulum_detail`
--
ALTER TABLE `tbl_kurikulum_detail`
  ADD CONSTRAINT `fk_kurikulum_detail_jurusan` FOREIGN KEY (`kd_jurusan`) REFERENCES `tbl_jurusan` (`kd_jurusan`),
  ADD CONSTRAINT `fk_kurikulum_detail_kurikulum` FOREIGN KEY (`id_kurikulum`) REFERENCES `tbl_kurikulum` (`id_kurikulum`),
  ADD CONSTRAINT `fk_kurikulum_detail_mapel` FOREIGN KEY (`kd_mapel`) REFERENCES `tbl_mapel` (`kd_mapel`),
  ADD CONSTRAINT `fk_kurikulum_detail_tingkatan` FOREIGN KEY (`kd_tingkatan`) REFERENCES `tbl_tingkatan_kelas` (`kd_tingkatan`);

--
-- Constraints for table `tbl_nilai`
--
ALTER TABLE `tbl_nilai`
  ADD CONSTRAINT `fk_nilai_jadwal` FOREIGN KEY (`id_jadwal`) REFERENCES `tbl_jadwal` (`id_jadwal`),
  ADD CONSTRAINT `fk_nilai_jadwal_fix` FOREIGN KEY (`id_jadwal`) REFERENCES `tbl_jadwal` (`id_jadwal`),
  ADD CONSTRAINT `fk_nilai_siswa` FOREIGN KEY (`nim`) REFERENCES `tbl_siswa` (`nim`),
  ADD CONSTRAINT `fk_nilai_siswa_fix` FOREIGN KEY (`nim`) REFERENCES `tbl_siswa` (`nim`);

--
-- Constraints for table `tbl_nilai_catatan`
--
ALTER TABLE `tbl_nilai_catatan`
  ADD CONSTRAINT `tbl_nilai_catatan_ibfk_1` FOREIGN KEY (`nim`) REFERENCES `tbl_siswa` (`nim`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_riwayat_kelas`
--
ALTER TABLE `tbl_riwayat_kelas`
  ADD CONSTRAINT `fk_riwayat_kelas_fix` FOREIGN KEY (`kd_kelas`) REFERENCES `tbl_kelas` (`kd_kelas`);

--
-- Constraints for table `tbl_siswa`
--
ALTER TABLE `tbl_siswa`
  ADD CONSTRAINT `fk_siswa_agama` FOREIGN KEY (`kd_agama`) REFERENCES `tbl_agama` (`kd_agama`),
  ADD CONSTRAINT `fk_siswa_kelas` FOREIGN KEY (`kd_kelas`) REFERENCES `tbl_kelas` (`kd_kelas`);

--
-- Constraints for table `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD CONSTRAINT `fk_user_level` FOREIGN KEY (`id_level_user`) REFERENCES `tbl_level_user` (`id_level_user`);

--
-- Constraints for table `tbl_user_rule`
--
ALTER TABLE `tbl_user_rule`
  ADD CONSTRAINT `fk_rule_level` FOREIGN KEY (`id_level_user`) REFERENCES `tbl_level_user` (`id_level_user`),
  ADD CONSTRAINT `fk_rule_menu` FOREIGN KEY (`id_menu`) REFERENCES `tabel_menu` (`id`);

--
-- Constraints for table `tbl_walikelas`
--
ALTER TABLE `tbl_walikelas`
  ADD CONSTRAINT `fk_walikelas_guru` FOREIGN KEY (`id_guru`) REFERENCES `tbl_guru` (`id_guru`),
  ADD CONSTRAINT `fk_walikelas_kelas` FOREIGN KEY (`kd_kelas`) REFERENCES `tbl_kelas` (`kd_kelas`),
  ADD CONSTRAINT `fk_walikelas_tahun` FOREIGN KEY (`id_tahun_akademik`) REFERENCES `tbl_tahun_akademik` (`id_tahun_akademik`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
