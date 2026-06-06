-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 06, 2026 at 12:24 AM
-- Server version: 8.0.30
-- PHP Version: 8.3.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bsi_tasikmalaya`
--

-- --------------------------------------------------------

--
-- Table structure for table `bju_data`
--

CREATE TABLE `bju_data` (
  `id` int NOT NULL,
  `formulir` varchar(50) NOT NULL,
  `nama` varchar(150) NOT NULL,
  `rangking` varchar(50) DEFAULT NULL,
  `asal_sekolah` varchar(150) NOT NULL,
  `beasiswa` varchar(50) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `bju_data`
--

INSERT INTO `bju_data` (`id`, `formulir`, `nama`, `rangking`, `asal_sekolah`, `beasiswa`, `status`) VALUES
(1, '19260275', 'Asri Nurfalah', 'Peringkat 1', 'SMK YPC Tasikmalaya', '100%', 'Closing'),
(2, '19260326', 'Aini Dwi Guna', 'Peringkat 3', 'SMK YPC Tasikmalaya', '50%', 'Closing'),
(3, '19260416', 'Nendi Sanjaya', 'Peringkat 3', 'SMKN 3 Tasikmalaya', '50%', 'Closing'),
(4, '19260280', 'Kirani Nurramadhani', 'Peringkat 1', 'SMAN 6 Tasikmalaya', '100%', 'Closing'),
(5, '19260282', 'Kirana Nurramadhina', 'Peringkat 2', 'SMAN 6 Tasikmalaya', '75%', 'Closing'),
(6, '19260354', 'Vira Haerunisa', 'Peringkat 1', 'SMAN 6 Tasikmalaya', '100%', 'Closing'),
(7, '19260309', 'Keysa Maharani Saepul', 'Peringkat 5', 'SMKN 1 Kawali', '50%', 'Closing'),
(8, '19260308', 'Liva', 'Peringkat 1', 'SMKN 1 Kawali', '100%', 'Closing'),
(9, '19260311', 'Nadia Nurdianti', 'Peringkat 10', 'SMKN 1 Kawali', '50%', 'Closing'),
(10, '19260492', 'Pajar Azmi Anugraha', 'Peringkat 1', 'SMKN 1 Kawali', '100%', 'Closing'),
(11, '19260370', 'Sri Dwi Wahyuni', 'Peringkat 2', 'SMKN 1 Kawali', '75%', 'Closing'),
(12, '19260515', 'Shalfa Aznha Aditya', 'Peringkat 1', 'MA Ibadul Ghofur Rajadesa', '100%', 'Closing'),
(13, '19260268', 'Ulya Nuryaomi', 'Peringkat 1', 'SMAN 10 Tasikmalaya', '100%', 'Closing'),
(14, '19260264', 'Moh Tazky Yanjali', 'Peringkat 9', 'SMK YPC Tasikmalaya', '50%', 'Closing'),
(15, '19260251', 'Noval Albi Ba\'adilah', 'Peringkat 3', 'SMAN 10 Tasikmalaya', '50%', 'Closing'),
(16, '19260248', 'Budi Wahyu', 'Peringkat 2', 'SMAN 10 Tasikmalaya', '75%', 'Closing'),
(17, '19260247', 'Risad Diya Ulhaq', 'Peringkat 1', 'SMAN 10 Tasikmalaya', '100%', 'Closing'),
(18, '19260179', 'Randi Ardiansyah', 'Peringkat 1', 'SMAN 8 Tasikmalaya', '100%', 'Closing'),
(19, '1202601003015', 'Aang Anwar', 'Peringkat 1', 'MA AS-SA\'ADAH', '100%', 'Belum Closing'),
(20, '1202601002997', 'Defril Mulya Saputra', 'Peringkat 1', 'SMK PLUS AN-NUUR', '100%', 'Belum Closing'),
(21, '19260392', 'Nirma Nur Fatimah', 'Peringkat 1', 'SMA Negeri 1 Baregbeg', '100%', 'Closing'),
(22, '1202604007476', 'Reya Pamungkas', 'Peringkat 2', 'SMK PGRI Cikoneng', '100%', 'Closing'),
(23, '19260437', 'Antung Tasria Tambusai', 'Peringkat 1', 'SMKN 3 Tasikmalaya', '100%', 'Closing'),
(24, '19260534', 'Abdi Fajar Maulana', 'Peringkat 2', 'SMKN 3 Tasikmalaya', '75%', 'Closing'),
(25, '19260292', 'Rahmadian Auliani Putri', 'Peringkat 1', 'SMKN 3 Tasikmalaya', '100%', 'Closing'),
(26, '19260596', 'Mila Amalia', 'Peringkat 1', 'SMKN 3 Tasikmalaya', '100%', 'Closing'),
(27, '19260350', 'Deseu Pohaseu', 'Peringkat 1', 'SMK PGRI Cikoneng', '100%', 'Closing'),
(28, '1202604007518', 'Radhitya Adira', 'Peringkat 2', 'SMK PGRI Cikoneng', '75%', 'Belum Closing'),
(29, '19260656', 'Ade Sofyan Irfani', 'Peringkat 1', 'SMK YPC Tasikmalaya', '100%', 'Closing'),
(30, '1202604007797', 'Mita Putri Cahyadi', 'Peringkat 3', 'SMKN 3 Tasikmalaya', '50%', 'Closing'),
(31, '19260384', 'Rafi Izazul Hakim', 'Peringkat 1', 'SMKN 1 Rajadesa', '100%', 'Closing'),
(32, '19260403', 'Gina Qolbiatus Saadah S', 'Peringkat 2', 'SMK PGRI Cikoneng', '75%', 'Closing'),
(33, '19260691', 'Dendra Alfiansyah', 'Peringkat 3', 'SMKN 3 Tasikmalaya', '50%', 'Closing'),
(34, '19260440', 'Anggiea Putri Luswandari', 'Peringkat 1', 'SMKN 1 Ciamis', '100%', 'Closing'),
(35, '19260560', 'Safitri indriani', 'Peringkat 1', 'SMK Arrohmah Dadaha', '100%', 'Closing'),
(36, '19260497', 'Muhamad Yasin Fadilah', 'Peringkat 1', 'SMK Igasar Pindad', '100%', 'Closing'),
(37, '1202604008564', 'Fawwaz Zaidan Baariqni', 'Peringkat 9', 'SMK YPC Tasikmalaya', '50%', 'Belum Closing'),
(38, '19260577', 'Lisna', 'Peringkat 1', 'SMK Sukapura', '75%', 'Closing'),
(39, '19260486', 'Muhamad Syarip Hidayatuloh', 'Peringkat 2', 'SMK Arrohmah Dadaha', '50%', 'Closing'),
(40, '1202604009227', 'Nurul Hasna', 'Peringkat 4', 'SMK Nurul Wafa', '50%', 'Belum Closing'),
(42, '19260626', 'Akesa Zul Rifan', 'Peringkat 2', 'SMA Nurul Wafa', '50%', 'Closing'),
(43, '19260684', 'Ghaitsa Mutiara Ardianti', 'Peringkat 1', 'SMKN 1 Tasikmalaya', '75%', 'Closing'),
(47, '19260634', 'Siti Fauziah', 'Peringkat 1', 'MAN 1 Kota Tasikmalaya', '75%', 'Closing'),
(48, '1202605010831', 'Muhammad Rizki', 'Peringkat 1', 'SMKN 1 Tasikmalaya', '75%', 'Closing'),
(49, '1202605012102', 'Riki Syamsul Fikri', 'Peringkat 1', 'SMK Islam Kawalu Kota Tasikmalaya', '75%', 'Belum Closing');

-- --------------------------------------------------------

--
-- Table structure for table `tabel_event`
--

CREATE TABLE `tabel_event` (
  `id` int NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `no_whatsapp` varchar(20) NOT NULL,
  `asal_sekolah` varchar(100) NOT NULL,
  `kelas` enum('X','XI','XII','Sudah Lulus') NOT NULL,
  `tertarik_jurusan` varchar(255) NOT NULL,
  `rencana_kuliah` enum('Tahun Ini','Tahun Depan','Mencari Info') NOT NULL,
  `tertarik_beasiswa` enum('Ya','Tidak') NOT NULL,
  `mau_dihubungi` enum('Ya','Tidak') NOT NULL,
  `sumber_event` varchar(255) NOT NULL,
  `status_crm` enum('Belum Diproses','Nolak','Ragu','Minat','Closing') DEFAULT 'Belum Diproses',
  `alasan` text,
  `di_follow_up_oleh` varchar(50) DEFAULT NULL,
  `tanggal_follow` date DEFAULT NULL,
  `upload_gambar` varchar(255) DEFAULT NULL,
  `alasan_crm` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tabel_event`
--

INSERT INTO `tabel_event` (`id`, `nama_lengkap`, `no_whatsapp`, `asal_sekolah`, `kelas`, `tertarik_jurusan`, `rencana_kuliah`, `tertarik_beasiswa`, `mau_dihubungi`, `sumber_event`, `status_crm`, `alasan`, `di_follow_up_oleh`, `tanggal_follow`, `upload_gambar`, `alasan_crm`, `created_at`) VALUES
(1, 'Rosid', '083896250027', 'Ciamis', 'XII', 'Informatika', 'Tahun Ini', 'Ya', 'Ya', 'https://admin.pmbubsi.id/ubsi2026/', 'Ragu', 'Belum ada balasan', 'Sri Mulyan F.N', '2026-05-29', 'IMG_1780057082_8891.png', NULL, '2026-05-29 01:47:05'),
(2, 'AA MUMU MUHAEMIN', '085335479327', 'Tasikmalaya', 'XII', 'Informatika', 'Tahun Ini', 'Ya', 'Ya', 'https://admin.pmbubsi.id/ubsi2026/', 'Ragu', 'Belum ada respon', 'Sri Mulyan F.N', '2026-05-29', 'IMG_1780056998_3383.png', NULL, '2026-05-29 01:50:02'),
(3, 'Anggi Lestari', '083824808230', 'Tasikmalaya', 'XII', 'Informatika', 'Tahun Ini', 'Ya', 'Ya', 'https://admin.pmbubsi.id/ubsi2026/', 'Ragu', 'Belum ada respon', 'Sri Mulyan F.N', '2026-05-29', 'IMG_1780056328_6567.png', NULL, '2026-05-29 01:51:04'),
(4, 'Hallifah Fitriani Salsa Billah', '083871373487', 'Tasikmalaya', 'XII', 'Informatika', 'Tahun Ini', 'Ya', 'Ya', 'https://admin.pmbubsi.id/ubsi2026/', 'Ragu', 'Belum ada respon ', 'Sri Mulyan F.N', '2026-05-29', 'IMG_1780056378_3865.png', NULL, '2026-05-29 01:52:42'),
(5, 'Annisa Jamilah', '085520735944', 'Tasikmalaya', 'XII', 'Informatika', 'Tahun Ini', 'Ya', 'Ya', 'https://admin.pmbubsi.id/ubsi2026/', 'Minat', 'Mau daftar kalau dapat beasiswa, tetapi harus di ajukan terlebih dahulu ', 'Sri Mulyan F.N', '2026-05-29', 'IMG_1780059280_6424.png', NULL, '2026-05-29 01:54:03'),
(6, 'Widdy Nurfadillah Indriati', '089524700216', 'Garut', 'XII', 'Informatika', 'Tahun Ini', 'Ya', 'Ya', 'https://admin.pmbubsi.id/ubsi2026/', 'Ragu', 'Belum ada respon ', 'Sri Mulyan F.N', '2026-05-29', 'IMG_1780056512_7808.png', NULL, '2026-05-29 01:54:54'),
(7, 'MUHAMAD RIZKI ADITIA', '081285009788', 'Ciamis', 'XII', 'Informatika', 'Tahun Ini', 'Ya', 'Ya', 'https://admin.pmbubsi.id/ubsi2026/', 'Ragu', 'Belum ada respon', 'Sri Mulyan F.N', '2026-05-29', 'IMG_1780057042_3901.png', NULL, '2026-05-29 01:55:46'),
(8, 'Rizqy Alistia Putra', '083837930256', 'Majalengka', 'XII', 'Informatika', 'Tahun Ini', 'Ya', 'Ya', 'https://admin.pmbubsi.id/ubsi2026/', 'Ragu', 'Belum ada respon ', 'Sri Mulyan F.N', '2026-05-29', 'IMG_1780056576_7874.png', NULL, '2026-05-29 01:56:53'),
(9, 'Dadan Ilham Maulana', '081298570764', 'Tasikmalaya', 'XII', 'Informatika', 'Tahun Ini', 'Ya', 'Ya', 'https://admin.pmbubsi.id/ubsi2026/', 'Ragu', 'Belum ada respon ', 'Sri  Mulyan F.N', '2026-05-29', 'IMG_1780057356_3309.png', NULL, '2026-05-29 01:58:30'),
(10, 'Aji Triana', '087722266759', 'Tasikmalaya', 'XII', 'Informatika', 'Tahun Ini', 'Ya', 'Ya', 'https://admin.pmbubsi.id/ubsi2026/', 'Ragu', 'Belum ada respon', 'Sri Mulyan F.N', '2026-05-29', 'IMG_1780055447_4976.png', NULL, '2026-05-29 01:59:11'),
(11, 'Adi Setiadi', '085871571210', 'Tasikmalaya', 'XII', 'Informatika', 'Tahun Ini', 'Ya', 'Ya', 'https://admin.pmbubsi.id/ubsi2026/', 'Ragu', 'Masih tanya tanya dan belum daftar', 'Sri Mulyan F.N', '2026-05-29', 'IMG_1780054605_2712.png', NULL, '2026-05-29 01:59:56'),
(12, 'Wati Nurmala', '089529057397', 'Tasikmalaya', 'XII', 'Informatika', 'Tahun Ini', 'Ya', 'Ya', 'https://admin.pmbubsi.id/ubsi2026/', 'Ragu', 'Belum ada respon', 'Sri Mulyan F.N', '2026-05-29', 'IMG_1780055504_7378.png', NULL, '2026-05-29 02:02:39'),
(13, 'Aliifah', '085187985251', 'Tasikmalaya', 'XII', 'Informatika', 'Tahun Ini', 'Ya', 'Ya', 'https://admin.pmbubsi.id/ubsi2026/', 'Ragu', 'Masih Bertanya tanya belum daftar', 'Sri Mulyan F.N', '2026-05-29', 'IMG_1780054825_2557.png', NULL, '2026-05-29 02:03:44'),
(14, 'Muhammad Alfaruq', '087780138082', 'Tasikmalaya', 'XII', 'Informatika', 'Tahun Ini', 'Ya', 'Ya', 'https://admin.pmbubsi.id/ubsi2026/', 'Nolak', 'Tidak tertarik', 'Sri Mulyan F.N', '2026-05-29', 'IMG_1780050617_7346.png', NULL, '2026-05-29 02:04:43'),
(15, 'Naufal Ahmad', '081221932424', 'Tasikmalaya', 'XII', 'Informatika', 'Tahun Ini', 'Ya', 'Ya', 'https://admin.pmbubsi.id/ubsi2026/', 'Nolak', 'Sudah keterima SNBT di Universitas lain', 'Sri Mulyan F.N', '2026-05-29', 'IMG_1780050129_5537.png', NULL, '2026-05-29 02:05:29'),
(16, 'Deswita Regita Putri', '089682020503', 'Tasikmalaya', 'XII', 'Informatika', 'Tahun Ini', 'Ya', 'Ya', 'https://admin.pmbubsi.id/ubsi2026/', 'Ragu', 'Belum ada balasan', 'Sri Mulyan F.N', '2026-05-29', 'IMG_1780048496_3814.png', NULL, '2026-05-29 02:06:15'),
(17, 'Dea sri nugraha', '081221536868', 'Tasikmalaya', 'XII', 'Informatika', 'Tahun Ini', 'Ya', 'Ya', 'https://admin.pmbubsi.id/ubsi2026/', 'Ragu', 'Belum ada balasan', 'Sri Mulyan F.N', '2026-05-29', 'IMG_1780048356_5000.png', NULL, '2026-05-29 02:07:07'),
(18, 'Muhammad Fikriyatul Islam', '087875964778', 'Ciamis', 'XII', 'Informatika', 'Tahun Ini', 'Ya', 'Ya', 'https://admin.pmbubsi.id/ubsi2026/', 'Ragu', 'Masiih bingung dan mau difikirkanlagi', 'Sri Mulyan F.N', '2026-05-29', 'IMG_1780047373_5607.png', NULL, '2026-05-29 02:08:09'),
(19, 'M RASYA RAYGANIS', '082119368237', 'Tasikmalaya', 'XII', 'Informatika', 'Tahun Ini', 'Ya', 'Ya', 'https://admin.pmbubsi.id/ubsi2026/', 'Minat', 'Tinggal bayar pendaftaran, nanti minggu depan', 'Sri Mulyan F.N', '2026-05-29', 'IMG_1780048779_2048.png', NULL, '2026-05-29 02:08:49'),
(20, 'Raisya Citra Fitria', '085624777950', 'Tasikmalaya', 'XII', 'Informatika', 'Tahun Ini', 'Ya', 'Ya', 'https://admin.pmbubsi.id/ubsi2026/', 'Ragu', 'Belum  Pengajuan Surat LoA, dan belum ada lagi balasan', 'Sri Mulyan F.N', '2026-05-29', 'IMG_1780047125_9688.png', NULL, '2026-05-29 02:09:40'),
(21, 'Nazwa alika musarofah', '083116819210', 'Tasikmalaya', 'XII', 'Manajemen', 'Tahun Ini', 'Ya', 'Ya', 'https://admin.pmbubsi.id/ubsi2026/', 'Ragu', 'Belum ada lagi balasan', 'Sri Mulyan F.N', '2026-05-29', 'IMG_1780046947_2584.png', NULL, '2026-05-29 02:10:23'),
(22, 'Hanan', '081210863808', 'Tasikmalaya', 'XII', 'Informatika', 'Tahun Ini', 'Ya', 'Ya', 'https://admin.pmbubsi.id/ubsi2026/', 'Ragu', 'Baru bertanya tanya dan mau di pikirkan terlebih dahulu ', 'Sri Mulyan F.N', '2026-05-29', 'IMG_1780055112_8860.png', NULL, '2026-05-29 02:10:59'),
(23, 'Razaan', '081211846570', 'Tasikmalaya', 'XII', 'Informatika', 'Tahun Ini', 'Ya', 'Ya', 'https://admin.pmbubsi.id/ubsi2026/', 'Closing', 'Sudah menjadi mahasiswa UBSI Margonda', 'Sri Mulyan F.N', '2026-05-29', 'IMG_1780047235_4049.png', NULL, '2026-05-29 02:12:00'),
(24, 'Riqi Ubaedillah', '085188710624', 'Tasikmalaya', 'XII', 'Informatika', 'Tahun Ini', 'Ya', 'Ya', 'https://admin.pmbubsi.id/ubsi2026/', 'Ragu', 'Belum ada balasan', 'Sri Mulyan F.N', '2026-05-29', 'IMG_1780046620_3994.png', NULL, '2026-05-29 02:12:48'),
(25, 'NUROFI ANDRIAWAN', '0895803460186', 'Tasikmalaya', 'XII', 'Informatika', 'Tahun Ini', 'Ya', 'Ya', 'https://admin.pmbubsi.id/ubsi2026/', 'Minat', 'Ada rencana masuk UBSI Tasik tapi masih bingung dengan masalah biaya, dan belum mengajukan surat LoA beasiswa jalur undangan', 'Sri Mulyan F.N', '2026-06-01', 'IMG_1780288543_5378.png', NULL, '2026-05-29 02:13:28'),
(26, 'putri avni aprilia nuraini', '087720417007', 'Tasikmalaya', 'XII', 'Informatika', 'Tahun Ini', 'Ya', 'Ya', 'https://admin.pmbubsi.id/ubsi2026/', 'Ragu', 'Belum ada balasan', 'Sri Mulyan F.N', '2026-05-29', 'IMG_1780043231_4347.png', NULL, '2026-05-29 02:14:12'),
(27, 'Riki Syamsul Fikri', '08871727289', 'SMK Islam Kawalu Kota Tasikmalaya', 'XII', 'Informatika', 'Tahun Ini', 'Ya', 'Ya', 'https://admin.pmbubsi.id/ubsi2026/', 'Minat', 'Masih mengusahakan buat daftar ulang', 'Sri Mulyan F.N', '2026-06-02', 'IMG_1780401922_4830.png', NULL, '2026-05-28 20:03:48'),
(28, 'Muhammad Rizki', '085771802881', 'SMKN 1 Tasikmalaya', 'XII', 'Informatika', 'Tahun Ini', 'Ya', 'Ya', 'https://admin.pmbubsi.id/ubsi2026/', 'Closing', 'Sudah melakukan daftar ulang', 'Sri Mulyan F.N', '2026-06-05', 'IMG_1780658512_6735.png', NULL, '2026-05-28 20:06:44'),
(29, 'Fawwaz Zaidan Baariqni', '083126026692', 'SMK YPC Tasikmalaya', 'XII', 'Informatika', 'Tahun Ini', 'Ya', 'Ya', 'https://admin.pmbubsi.id/ubsi2026/', 'Nolak', 'Nomor WA tidak dapat dihubungi', 'Sri Mulyan F.N', '2026-05-29', 'IMG_1780050440_8553.jpeg', NULL, '2026-05-28 20:07:44'),
(30, 'Radhitya Adira', '083189352601', 'SMK PGRI Cikoneng', 'XII', 'Informatika', 'Tahun Ini', 'Ya', 'Ya', 'https://admin.pmbubsi.id/ubsi2026/', 'Minat', 'Sudah terpotong tapi masih mengusahkan buat membayar daftar ulang', 'Sri Mulyan F.N', '2026-06-02', 'IMG_1780378276_7887.jpeg', NULL, '2026-05-28 20:08:31'),
(31, 'Reya Pamungkas', '0881022315909', 'SMK PGRI Cikoneng', 'XII', 'Informatika', 'Tahun Ini', 'Ya', 'Ya', 'https://admin.pmbubsi.id/ubsi2026/', 'Closing', 'Sudah melakukan daftar ulang ', 'Sri Mulyan F.N', '2026-06-02', 'IMG_1780378055_2874.jpeg', NULL, '2026-05-28 20:09:13'),
(32, 'Defril Mulya Saputra', '085721763539', 'SMK PLUS AN-NUUR', 'XII', 'Manajemen', 'Tahun Ini', 'Ya', 'Ya', 'https://admin.pmbubsi.id/ubsi2026/', 'Ragu', 'Belum ada balasan', 'Sri Mulyan F.N', '2026-05-29', 'IMG_1780042966_9434.png', NULL, '2026-05-28 20:10:09'),
(33, 'Aang Anwar', '085794453096', 'MA AS-SA\'ADAH', 'XII', 'Informatika', 'Tahun Ini', 'Ya', 'Ya', 'https://admin.pmbubsi.id/ubsi2026/', 'Nolak', 'Sudah dibicarakan sama keluarga beasiswanya tidak bakal di ambil', 'Sri Mulyan F.N', '2026-05-29', 'IMG_1780026252_6554.jpeg', NULL, '2026-05-28 20:11:03'),
(34, 'Syifa siti solihah', '087895046940', 'Pesantren Al Muslihun Rajadesa Ciamis', 'XII', 'Sistem Informasi', 'Tahun Ini', 'Tidak', 'Ya', 'Live Tiktok', 'Minat', 'Udah Beli Formulir Tinggal Mau ngumpulin dulu buat daftar ulangnya, tinggal di follow up kembali', 'Sri Mulyan F.N', '2026-04-25', 'IMG_1780024822_9840.jpeg', NULL, '2026-05-28 20:13:29'),
(35, 'silpia latifatul husna', '+62 896-5511-8269', 'SMAN 1 Cihaurbeuti', 'Sudah Lulus', 'Informatika', 'Tahun Ini', 'Ya', 'Ya', 'Whatsapp Agung', 'Minat', 'KIP, saya tawarkan kelas karyawan karena tidak freshgraduate', 'Agung Baitul Hikmah', '2026-05-29', 'IMG_1780041469_2642.jpg', NULL, '2026-05-29 00:55:02'),
(36, 'Agustian Bayu', '+62 817-7998-4092', 'SMK Karnas', 'XII', 'Informatika', 'Tahun Depan', 'Ya', 'Ya', 'Whatsapp Agung', 'Ragu', 'Mau Lanjut Tahun Depan, Lagi Pikir Pikir Dulu ', 'Agung Baitul Hikmah', '2026-05-29', 'IMG_1780041854_1007.jpg', NULL, '2026-05-29 01:03:02'),
(37, 'Antung Tasria Tambusai', '083144802554', 'SMKN 3 Tasikmalaya', 'Sudah Lulus', 'Sistem Informasi', 'Tahun Ini', 'Ya', 'Ya', 'Live Tiktok', 'Closing', 'Sudah daftar daftar ulang dan mendapatkan kelas', 'Sri Mulyan F.N', '2026-05-28', 'IMG_1780049818_6490.png', NULL, '2026-05-29 03:14:51'),
(38, 'Rifqi Chairul Umam', '6285294167630', 'SMKN 3 Ciamis', 'XII', 'Informatika', 'Tahun Ini', 'Ya', 'Ya', 'Whatsapp Agung', 'Minat', 'Menanyakan Fakultas yang ada di Kampus UBSI Tasikmalaya dan belum ada respon kelanjutannya', 'Agung Baitul Hikmah', '2026-05-30', 'IMG_1780105550_3223.jpg', NULL, '2026-05-29 18:43:49'),
(39, 'Shifa Saadah', '081313728610', 'Smk plus Ysb Suryalaa', 'Sudah Lulus', 'Informatika', 'Tahun Ini', 'Ya', 'Ya', 'Event Lainnya', 'Closing', 'Closing', 'Devi Novita', '2026-05-30', 'IMG_1780271396_6181.jpg', NULL, '2026-05-31 16:47:45'),
(40, 'Ardi Warist Firdaus', '085846577820', 'SMKN 2 Tasikmalaya', 'X', 'Manajemen', 'Tahun Ini', 'Ya', 'Ya', 'Iklan Facebook ', 'Minat', 'Tinggal Melakukan Daftar Ulang', 'Devi Novita', '2026-05-04', 'IMG_1780356244_2287.jpg', NULL, '2026-06-01 16:23:30'),
(41, 'Fajri Nuril Hidayat', '083116726113', 'SMKN 2 TASIKMALAYA', 'XII', 'Informatika', 'Tahun Ini', 'Tidak', 'Ya', 'Dari Teman', 'Closing', 'Sudah melakukan daftar ulang, tinggal melengkapi data', 'Siti Fatimah', '2026-06-02', 'IMG_1780362850_5003.jpeg', NULL, '2026-06-01 18:06:59'),
(42, 'Raffy Pramana', '082121201990', 'Ponpes riyadhlul ulum waddawah', 'XII', 'Informatika', 'Tahun Ini', 'Tidak', 'Ya', 'Workshop Digital Kreatif', 'Minat', 'Tinggal Melakukan Daftar Ulang', 'Siti Fatimah', '2026-06-02', 'IMG_1780364209_7475.jpeg', NULL, '2026-06-01 18:35:48'),
(43, 'Raisya Ananda Rizaldy', '081912023097', '-', 'XII', 'Informatika', 'Tahun Ini', 'Tidak', 'Ya', 'Workshop Digital Kreatif', 'Ragu', 'Belum ada respon', 'Siti Fatimah', '2026-05-18', 'IMG_1780365647_8417.jpeg', NULL, '2026-06-01 18:51:09'),
(44, 'M Rasya Rayganis', '087714616991', 'SMAN 5 Tasikmalaya', 'XII', 'Sistem Informasi', 'Tahun Ini', 'Ya', 'Ya', 'Event Lainnya', 'Minat', 'Akan bayar Pendaftaran pertengahan Juni', 'Herlan', '2026-06-02', NULL, NULL, '2026-06-01 18:54:46'),
(45, 'Rim', '082315205715', '-', 'Sudah Lulus', 'Sistem Informasi', 'Tahun Ini', 'Ya', 'Ya', 'Iklan di Instagram ', 'Ragu', '', 'Devi Novita', '2026-05-02', 'IMG_1780391045_2985.jpg', NULL, '2026-06-02 02:03:41'),
(46, 'Hani', '08122370517', '-', 'Sudah Lulus', 'Sistem Informasi', 'Tahun Ini', 'Ya', 'Ya', 'Iklan di Instagram ', 'Ragu', '', 'Devi Novita', '2026-05-05', 'IMG_1780392437_4202.jpg', NULL, '2026-06-02 02:26:18'),
(47, 'R MUH AZKA EKA PUTRA', '089526039954', 'SMA NEGERI 1 SINGAPARNA', 'XII', 'Informatika', 'Tahun Ini', 'Ya', 'Ya', 'Dari Teman', 'Minat', 'Diusahain bulan ini', 'Siti Fatimah', '2026-06-03', 'IMG_1780460891_4569.jpeg', NULL, '2026-06-02 21:25:24'),
(48, 'Daniella Juliani Hermansyah', '085220447033', '-', 'XII', 'Informatika', 'Tahun Ini', 'Ya', 'Ya', 'dari staff UBSI', 'Minat', 'Tinggal Melakukan Daftar Ulang', 'Siti Fatimah', '2026-06-03', 'IMG_1780475658_2307.jpeg', NULL, '2026-06-03 01:20:04'),
(49, 'Rani Nurani', '085603227671', '-', 'Sudah Lulus', 'Sistem Informasi', 'Tahun Ini', 'Ya', 'Ya', 'Iklan Instagram', 'Minat', 'Tinggal Melakukan Daftar Ulang', 'Siti Fatimah', '2026-06-03', 'IMG_1780475609_9882.jpeg', NULL, '2026-06-03 01:30:37'),
(50, 'Yola', '0859106979467', '-', 'Sudah Lulus', 'Informatika', 'Tahun Ini', 'Ya', 'Ya', 'Datang Ke Kampus', 'Ragu', '', 'Devi Novita', '2026-06-02', NULL, NULL, '2026-06-03 01:35:38'),
(51, 'Lutfi Muzaky Arif', '082219364322', 'SMA  Terpadu Cikanyere', 'Sudah Lulus', 'Sistem Informasi', 'Tahun Ini', 'Ya', 'Ya', 'Event Lainnya', 'Minat', 'Sudah daftar tapi belum melakukan daftar ulang masih di usahakan', 'Sri Mulyan F.N', '2026-06-03', 'IMG_1780478820_7345.png', NULL, '2026-06-03 02:26:06'),
(52, 'Muhamad rifan surya abdul kholid', '087781583914', 'Tasikamalaya', 'Sudah Lulus', 'Sistem Informasi', 'Tahun Ini', 'Ya', 'Ya', 'Event Lainnya', 'Ragu', 'Masih bingung, mau mencoba daftar ke negeri dulu', 'Sri Mulyan F.N', '2026-06-03', 'IMG_1780479045_7159.png', NULL, '2026-06-03 02:29:36'),
(53, 'Muthia Nurul Haniah Mudrikatus Syahidah', '081802528985', 'Tasikmalaya', 'Sudah Lulus', 'Sistem Informasi', 'Tahun Ini', 'Ya', 'Ya', 'Event Lainnya', 'Ragu', 'Belum ada respon', 'Sri Mulyan F.N', '2026-06-03', 'IMG_1780479332_1969.png', NULL, '2026-06-03 02:34:20'),
(54, 'Yunita Dewi', '081224579613', 'Tasikmalaya', 'Sudah Lulus', 'Jurusan Lainnya', 'Tahun Ini', 'Ya', 'Ya', 'Event Lainnya', 'Closing', 'Sudah Daftar Ulang', 'Agung Baitul Hikmah', '2026-06-03', 'IMG_1780479631_2714.jpg', NULL, '2026-06-03 02:37:04'),
(55, 'Agustian Bayu Nugraha', '081779984092', 'Tasikamalaya', 'Sudah Lulus', 'Sistem Informasi', 'Tahun Ini', 'Ya', 'Ya', 'Event Lainnya', 'Ragu', 'Belum ada respon', 'Sri Mulyan F.N', '2026-06-03', 'IMG_1780479839_2518.png', NULL, '2026-06-03 02:43:07'),
(56, 'Ardi Waris Firdaus', '082319948667', 'Tasikamalaya', 'Sudah Lulus', 'Sistem Informasi', 'Tahun Ini', 'Ya', 'Ya', 'Event Lainnya', 'Ragu', 'Belum ada respon', 'Sri Mulyan F.N', '2026-06-03', 'IMG_1780479994_1255.png', NULL, '2026-06-03 02:45:34'),
(57, 'Zahra Nurul Azizah', '081212299813', 'Tasikamalaya', 'Sudah Lulus', 'Sistem Informasi', 'Tahun Ini', 'Ya', 'Ya', 'Event Lainnya', 'Ragu', 'Belum ada respon', 'Sri Mulyan F.N', '2026-06-03', 'IMG_1780480227_1848.png', NULL, '2026-06-03 02:49:18'),
(58, 'Bella', '085121117185', 'SMAN 5 Tasikmalaya ', 'Sudah Lulus', 'Sistem informasi ', 'Tahun Ini', 'Ya', 'Ya', 'Event Lainnya', 'Minat', 'Masih bertanya tanyaa, belum ada balasan lagi ', 'Sri Mulyan F.N', '2026-06-04', 'IMG_1780572942_7448.png', NULL, '2026-06-04 04:34:41'),
(59, 'Vika (Kordinasi Fawwaz)', '085220516286', 'SMK YPC (Alumni)', 'Sudah Lulus', 'Informatika', 'Tahun Ini', 'Ya', 'Ya', 'Relasi', 'Minat', 'Masih menunggu uang', 'Haerul Fatah', '2026-05-19', 'IMG_1780586705_5259.jpg', NULL, '2026-06-04 08:23:34'),
(60, 'Noname', '085603266475', 'Belum ada info', 'XII', 'Informatika', 'Tahun Ini', 'Ya', 'Ya', 'Medsos', 'Ragu', 'Baru tanya2', 'Haerul Fatah', '2026-06-03', 'IMG_1780587076_2889.jpg', NULL, '2026-06-04 08:30:04'),
(61, 'Shei', '082116794723', 'Belum ada info', 'XII', 'Informatika', 'Tahun Ini', 'Ya', 'Ya', 'Event Lainnya', 'Ragu', 'Ingin kuliah yg full online', 'Haerul Fatah', '2026-06-02', 'IMG_1780587211_1500.jpg', NULL, '2026-06-04 08:32:36'),
(62, 'Pak Fahmi smk nurul wafa', '085210896787', 'Smk nurul wafa', 'Sudah Lulus', 'Informatika', 'Tahun Ini', 'Ya', 'Ya', 'Seminar Digital Kreatif', 'Closing', 'Sudah closing 3 siswa, sedang penjajakan siswa lainnya', 'Haerul Fatah', '2026-06-02', 'IMG_1780587408_5779.jpg', NULL, '2026-06-04 08:36:03'),
(63, 'Rizki', '085864350072', 'Sma Al Amin', 'Sudah Lulus', 'Informatika', 'Tahun Ini', 'Ya', 'Ya', 'Event Lainnya', 'Minat', 'Tanya2 tentang beasiswa hafidz qur\'an', 'Haerul Fatah', '2026-06-02', 'IMG_1780587588_6290.jpg', NULL, '2026-06-04 08:38:54'),
(64, 'Bu Astri', '081292772340', 'Sman 1 cihaurbeuti', 'Sudah Lulus', 'Informatika', 'Tahun Ini', 'Ya', 'Ya', 'Seminar Digital Kreatif', 'Minat', 'Sedang proses pendataan siswa untuk dibuatkan surat rekomendasi', 'Haerul Fatah', '2026-05-01', 'IMG_1780587771_4888.jpg', NULL, '2026-06-04 08:41:47'),
(65, 'Lyzz', '088971653529', 'Belum ada info', 'Sudah Lulus', 'Informatika', 'Tahun Ini', 'Ya', 'Ya', 'Event Lainnya', 'Ragu', 'Baru tanya2', 'Haerul Fatah', '2026-06-01', 'IMG_1780587882_5171.jpg', NULL, '2026-06-04 08:44:18'),
(66, 'Noname', '0895701081287', 'Belum ada info', 'XII', 'Informatika', 'Tahun Ini', 'Ya', 'Ya', 'Event Lainnya', 'Ragu', 'Baru tanya2 & info KIP', 'Haerul Fatah', '2026-05-30', 'IMG_1780587981_3646.jpg', NULL, '2026-06-04 08:45:20'),
(67, 'Muhammad Fikriyatul Islam', '085315742076', 'Belum ada info', 'XII', 'Informatika', 'Tahun Ini', 'Ya', 'Ya', 'Event Lainnya', 'Minat', 'Sudah daftar KIP, bayar form & ujian online, sedang menunggu hasil', 'Haerul Fatah', '2026-05-29', 'IMG_1780588207_4205.jpg', NULL, '2026-06-04 08:49:10'),
(68, 'Hidayatul Anwar (Ortu camaba)', '085223056190', 'Belum ada info', 'XII', 'Informatika', 'Tahun Ini', 'Ya', 'Ya', 'Event Lainnya', 'Minat', 'Sudah survey ke kampus disaat libur, Masih diskusi dengan anaknya', 'Haerul Fatah', '2026-05-28', 'IMG_1780588359_5831.jpg', NULL, '2026-06-04 08:51:15'),
(69, 'Noname', '085797943024', 'Smkn manonjaya', 'XII', 'Informatika', 'Tahun Ini', 'Ya', 'Ya', 'Event Lainnya', 'Minat', 'Juara taekwondo kabupaten kota, tanya beasiswa, sudah diarahkan minta surat rekomendasi beasiswa bju ke guru BK', 'Haerul Fatah', '2026-05-26', NULL, NULL, '2026-06-04 08:54:56'),
(70, 'Noname', '085724111350', 'Belum ada info', 'XII', 'Informatika', 'Tahun Ini', 'Ya', 'Ya', 'Event Lainnya', 'Ragu', 'Baru tanya2', 'Haerul Fatah', '2026-05-26', 'IMG_1780588669_7037.jpg', NULL, '2026-06-04 08:57:24'),
(71, 'Hajni', '087765640265', 'Belum ada info', 'XII', 'Informatika', 'Tahun Ini', 'Ya', 'Ya', 'Event Lainnya', 'Ragu', 'Baru tanya2 jurusan', 'Haerul Fatah', '2026-05-25', 'IMG_1780588804_9715.jpg', NULL, '2026-06-04 08:59:15'),
(72, 'Adittya', '083869225824', 'Belum ada info', 'XII', 'Informatika', 'Tahun Ini', 'Ya', 'Ya', 'Event Lainnya', 'Ragu', 'Baru tanya2', 'Haerul Fatah', '2026-05-25', 'IMG_1780588872_9243.jpg', NULL, '2026-06-04 09:00:54'),
(73, 'Ortu camaba Syifa', '082218637780', 'Smk al-ma\'arif rajadesa', 'XII', 'Informatika', 'Tahun Ini', 'Ya', 'Ya', 'Event Lainnya', 'Minat', 'Tanya tentang beasiswa BJU dan cara pengajuannya', 'Haerul Fatah', '2026-05-21', 'IMG_1780589080_8762.jpg', NULL, '2026-06-04 09:03:49'),
(74, 'Noname', '083835517184', 'Belum ada info', 'XII', 'Informatika', 'Tahun Ini', 'Ya', 'Ya', 'Event Lainnya', 'Ragu', 'Baru tanya2 kelas karyawan', 'Haerul Fatah', '2026-05-16', 'IMG_1780589201_1799.jpg', NULL, '2026-06-04 09:06:12'),
(75, 'Noname', '085700281664', 'Alumni smk bpn', 'Sudah Lulus', 'Informatika', 'Tahun Ini', 'Ya', 'Ya', 'Event Lainnya', 'Ragu', 'Baru tanya2 tentang KIP dan kuliah gratis', 'Haerul Fatah', '2026-05-07', 'IMG_1780589375_2402.jpg', NULL, '2026-06-04 09:08:56'),
(76, 'Ortu camaba', '081945075980', 'MAN 3 Ciawi', 'Sudah Lulus', 'Informatika', 'Tahun Ini', 'Ya', 'Ya', 'Event Lainnya', 'Minat', 'Masih diskusi dengan anaknya', 'Haerul Fatah', '2026-05-07', 'IMG_1780589630_5325.jpg', NULL, '2026-06-04 09:13:16'),
(77, 'Athala Khansa Miraj', '081288472073', 'SMAS Terpadu Riyadlul Ulum', 'Sudah Lulus', 'Sistem Informasi', 'Tahun Ini', 'Ya', 'Ya', 'Relasi karyawan ', 'Closing', '', 'Devi Novita', '2026-06-04', 'IMG_1780667655_5010.jpg', NULL, '2026-06-05 06:48:47'),
(78, 'No Name', '0895386834972', '-', 'Sudah Lulus', 'Manajemen', 'Mencari Info', 'Ya', 'Ya', 'Iklan di Instagram ', 'Ragu', '', 'admin', '2026-05-15', 'IMG_1780668286_5892.jpg', NULL, '2026-06-05 07:02:37');

-- --------------------------------------------------------

--
-- Table structure for table `tabel_quisoner`
--

CREATE TABLE `tabel_quisoner` (
  `id` int NOT NULL,
  `nim` varchar(15) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `whatsapp` varchar(15) NOT NULL,
  `tempat_tinggal` varchar(255) NOT NULL,
  `sekolah` varchar(100) NOT NULL,
  `beasiswa` enum('Ya','Tidak') NOT NULL,
  `jenis_beasiswa` varchar(50) DEFAULT NULL,
  `memilih_ubsi` varchar(50) NOT NULL,
  `mengetahui_ubsi` varchar(50) NOT NULL,
  `minat_kompetensi` varchar(50) NOT NULL,
  `aktivitas_organisasi` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tabel_quisoner`
--

INSERT INTO `tabel_quisoner` (`id`, `nim`, `nama`, `whatsapp`, `tempat_tinggal`, `sekolah`, `beasiswa`, `jenis_beasiswa`, `memilih_ubsi`, `mengetahui_ubsi`, `minat_kompetensi`, `aktivitas_organisasi`, `created_at`) VALUES
(1, '19260691', 'DENDRA ALFIANSYAH', '085600509124', 'Kabupaten Ciamis', 'SMKN 3 TASIKMALAYA', 'Ya', 'Beasiswa Jalur Undangan', 'Beasiswa', 'Guru BK', 'Olah Raga', 'Pencinta Alam', '2026-05-25 19:53:09'),
(2, '19260534', 'Abdi Fajar Maulana', '085157664613', 'Kota Tasikmalaya', 'SMKN 3 TASIKMALAYA', 'Ya', 'Beasiswa Jalur Undangan', 'Rekomendasi Guru BK', 'Guru BK', 'Live Streaming', 'Keagamaan', '2026-05-25 19:58:07'),
(3, '19260292', 'RAHMADIAN AULIANI PUTRI ', '0895352310660', 'Kota Tasikmalaya', 'SMKN 3 TASIKMALAYA ', 'Ya', 'Beasiswa Jalur Undangan', 'Beasiswa', 'Guru BK', 'Selebgram', 'Bahasa', '2026-05-25 19:58:30'),
(4, '19260440', 'ANGGIEA PUTRI LUSWANDARI', '087794155269', 'Kabupaten Ciamis', 'SMKN 1 CIAMIS ', 'Ya', 'Beasiswa Jalur Undangan', 'Rekomendasi Guru BK', 'Guru BK', 'Live Streaming', 'Komunitas Jepang', '2026-05-25 20:00:42'),
(5, '19260245', 'Dian', '087773667718', 'Kabupaten Tasikmalaya', 'MAN 3 KABUPATEN TASIKMALAYA', 'Tidak', NULL, 'Akreditasi Unggul', 'Guru BK', 'Editing Photo', 'Tari', '2026-05-25 20:14:50'),
(6, '19260384', 'RAFI IZAZUL HAKIM', '085318383793', 'Kabupaten Ciamis', 'SMKN 1 RAJADESA', 'Ya', 'Beasiswa Jalur Undangan', 'Rekomendasi Guru BK', 'Guru BK', 'Olah Raga', 'Bahasa', '2026-05-25 20:21:02'),
(7, '19260247', 'RISAD DIYA ULHAQ ', '083159960729', 'Kota Tasikmalaya', 'SMAN 10 TASIKMALAYA ', 'Ya', 'Beasiswa Jalur Undangan', 'Beasiswa', 'Guru BK', 'Olah Raga', 'Pencinta Alam', '2026-05-25 20:25:07'),
(8, '19260354', 'VIRA HAERUNISA', '085703215723', 'Kabupaten Tasikmalaya', 'SMAN 6 TASIKMALAYA', 'Ya', 'Beasiswa Jalur Undangan', 'Biaya Terjangkau', 'Guru BK', 'Editing Video', 'Karang Taruna', '2026-05-25 20:29:37'),
(9, '19260309', 'Keysa Maharani Saepul', '0822-9768-7121', 'Kabupaten Ciamis', 'SMKN 1 KAWALI', 'Ya', 'Beasiswa Jalur Undangan', 'Beasiswa', 'Guru BK', 'Influencer', 'Tari', '2026-05-25 20:32:41'),
(10, '19260536', 'Mutia Ardina Putri', '087865753590', 'Kota Tasikmalaya', 'SMAN 1 PARIGI', 'Tidak', NULL, 'Pilihan Orang Tua', 'Orang Tua', 'Live Streaming', 'Karang Taruna', '2026-05-25 20:37:44'),
(11, '19260311', 'NADIA NURDIANTI', '085860263224', 'Kabupaten Ciamis', 'SMKN 1 KAWALI', 'Ya', 'Beasiswa Jalur Undangan', 'Beasiswa', 'Guru BK', 'Olah Raga', 'Pencinta Alam', '2026-05-25 20:41:24'),
(12, '19260350', 'Deseu Pohaseu ', '082130459279', 'Kabupaten Ciamis', 'SMKS PGRI CIKONENG ', 'Ya', 'Beasiswa Jalur Undangan', 'Rekomendasi Guru BK', 'Mahasiswa UBSI', 'Olah Raga', 'Pencinta Alam', '2026-05-25 20:47:49'),
(13, '19260248', 'Budi Wahyu', '085801224137', 'Kota Tasikmalaya', 'SMAN 10 TASIKMALAYA ', 'Ya', 'Beasiswa Jalur Undangan', 'Beasiswa', 'Guru BK', 'Olah Raga', 'Musik', '2026-05-25 20:50:35'),
(14, '19260308', 'LIVA', '085722320460', 'Kabupaten Ciamis', 'SMKN 1 KAWALI', 'Ya', 'Beasiswa Jalur Undangan', 'Beasiswa', 'Guru BK', 'Editing Video', 'OSIS', '2026-05-25 20:51:06'),
(15, '19260728', 'Mita putri cahyadi', '082120029596', 'Kabupaten Tasikmalaya', 'SMKN 3 TASIKMALAYA', 'Ya', 'Beasiswa Jalur Undangan', 'Akreditasi Unggul', 'Guru BK', 'Selebgram', 'Pencinta Alam', '2026-05-25 20:54:16'),
(16, '19260684', 'Ghaitsa Mutiara Ardianti ', '081958048378', 'Kota Tasikmalaya', 'SMKN 1 TASIKMALAYA ', 'Ya', 'Beasiswa Jalur Undangan', 'Beasiswa', 'Orang Tua', 'Live Streaming', 'Tari', '2026-05-25 20:54:43'),
(17, '19260505', 'Liony Lee Vista', '082162212424', 'Kota Tasikmalaya', 'SMKN 4 TASIKMALAYA', 'Tidak', NULL, 'Biaya Terjangkau', 'Brosur', 'Olah Raga', 'Bahasa', '2026-05-25 20:58:47'),
(18, '19260656', 'Ade Sofyan Irfani ', '085185691357', 'Kabupaten Tasikmalaya', 'SMKS YPC TASIKMALAYA', 'Ya', 'Beasiswa Jalur Undangan', 'Beasiswa', 'Tiktok UBSI Tasikmalaya', 'Olah Raga', 'Bahasa', '2026-05-25 21:04:25'),
(19, '19260021', 'Wawan Wahyudin', '081395513181', 'Kabupaten Tasikmalaya', 'SMAS MUHAMMADIYAH SINGAPARNA', 'Tidak', NULL, 'Biaya Terjangkau', 'Instagram UBSI Tasikmalaya', 'Olah Raga', 'Keagamaan', '2026-05-25 21:09:29'),
(20, '19260437', 'ANTUNG TASRIA TAMBUSAI ', '083144802554', 'Kota Tasikmalaya', 'SMKN 3 TASIKMALAYA ', 'Ya', 'Beasiswa Jalur Undangan', 'Beasiswa', 'Guru BK', 'Selebgram', 'Komunitas Jepang', '2026-05-25 21:09:32'),
(21, '19260622', 'Shifa Saadah', '081313728610', 'Kabupaten Tasikmalaya', 'SMKS PLUS YSB SURYALAYA', 'Tidak', NULL, 'Akreditasi Unggul', 'Orang Tua', 'MC', 'Tari', '2026-05-25 21:11:21'),
(22, '19260634', 'Siti Fauziah', '081312442844', 'Kota Tasikmalaya', 'MAN 1 KOTA TASIKMALAYA', 'Ya', 'Beasiswa Jalur Undangan', 'Akreditasi Unggul', 'Guru BK', 'Live Streaming', 'Keagamaan', '2026-05-25 21:17:04'),
(23, '19260392', 'Nirma Nur Fatimah', '085314921664', 'Kabupaten Ciamis', 'SMAN 1 BAREGBEG', 'Ya', 'Beasiswa Jalur Undangan', 'Beasiswa', 'Guru BK', 'Menulis Berita', 'Bahasa', '2026-05-25 21:17:44'),
(24, '19260370', 'Sri Dwi Wahyuni ', '085860265747', 'Kabupaten Ciamis', 'SMKN 1 KAWALI', 'Ya', 'Beasiswa Jalur Undangan', 'Beasiswa', 'Guru BK', 'Olah Raga', 'OSIS', '2026-05-25 21:20:49'),
(25, '19260662', 'Tyara Nuralifah ', '085722483713', 'Kabupaten Pangandaran', 'SMAN 1 MANGUNJAYA ', 'Tidak', NULL, 'Biaya Terjangkau', 'Saudara', 'Selebgram', 'Tari', '2026-05-25 21:30:49'),
(26, '19260251', 'NOVAL ALBI BA\'ADILAH ', '085603642313', 'Kota Tasikmalaya', 'SMAN 10 TASIKMALAYA ', 'Ya', 'Beasiswa Jalur Undangan', 'Rekomendasi Guru BK', 'Saudara', 'Olah Raga', 'Pencinta Alam', '2026-05-25 21:41:10'),
(28, '19260112', 'Farhan Muhamad Nur', '082319216695', 'Kabupaten Ciamis', 'SMKS GALUH RAHAYU', 'Tidak', NULL, 'Biaya Terjangkau', 'Alumni UBSI', 'Olah Raga', 'Karang Taruna', '2026-05-25 22:16:38'),
(29, '19260311', 'NADIA NURDIANTI', '085860263224', 'Kabupaten Ciamis', 'SMKN 1 KAWALI', 'Ya', 'Beasiswa Jalur Undangan', 'Beasiswa', 'Guru BK', 'Olah Raga', 'Pencinta Alam', '2026-05-25 22:34:20'),
(30, '19260403', 'GINA QOLBIATUS SAADAH.S', '085187921419', 'Kabupaten Tasikmalaya', 'SMKS PGRI CIKONENG ', 'Ya', 'Beasiswa Jalur Undangan', 'Beasiswa', 'Guru BK', 'Live Streaming', 'Musik', '2026-05-25 22:45:25'),
(31, '19260497', 'M Yasin fadilah', '089670233040', 'Kabupaten Tasikmalaya', 'SMKS IGASAR PINDAD TASIKMALAYA', 'Ya', 'Beasiswa Jalur Undangan', 'Akreditasi Unggul', 'Karyawan UBSI', 'Olah Raga', 'Bahasa', '2026-05-25 23:22:35'),
(32, '19260486', 'Muhammad Syarip Hidayatulloh', '087848407206', 'Kota Tasikmalaya', 'SMKS ARROHMAH DADAHA', 'Ya', 'Beasiswa Jalur Undangan', 'Rekomendasi Guru BK', 'Guru BK', 'Olah Raga', 'Bahasa', '2026-05-26 07:05:06'),
(34, '19260737', 'Siti Zakiah', '082123984527', 'Kabupaten Tasikmalaya', 'MAS ATHORIYYAH CIKATOMAS', 'Tidak', NULL, 'Pilihan Orang Tua', 'Alumni UBSI', 'Selebgram', 'OSIS', '2026-05-26 07:26:20'),
(35, '19260264', 'Moh Tazky Yanjali', '081904953361', 'Kabupaten Tasikmalaya', 'SMKS YPC TASIKMALAYA', 'Ya', 'Beasiswa Jalur Undangan', 'Akreditasi Unggul', 'Guru BK', 'Editing Video', 'Bahasa', '2026-05-26 07:58:53'),
(36, '19260326', 'AINI DWI GUNA', '085321471240', 'Kabupaten Tasikmalaya', 'SMKS YPC TASIKMALAYA', 'Ya', 'Beasiswa Jalur Undangan', 'Beasiswa', 'Guru BK', 'Selebgram', 'Bahasa', '2026-05-26 01:18:07'),
(37, '19260492', 'PAJAR AZMI ANUGRAHA', '088973469020', 'Kabupaten Ciamis', 'SMKN 1 KAWALI', 'Ya', 'Beasiswa Jalur Undangan', 'Beasiswa', 'Guru BK', 'Olah Raga', 'OSIS', '2026-05-26 01:23:30'),
(38, '19260596', 'Mila Amaliah', '083869352104', 'Kota Tasikmalaya', 'SMKN 3 TASIKMALAYA', 'Ya', 'Beasiswa Jalur Undangan', 'Beasiswa', 'Guru BK', 'MC', 'Keagamaan', '2026-05-26 01:36:20'),
(39, '19260444', 'Trisna Tubagus Saleh', '081315601431', 'Kabupaten Tasikmalaya', 'MAN 7 TASIKMALAYA', 'Tidak', NULL, 'Biaya Terjangkau', 'Instagram UBSI Tasikmalaya', 'Menulis Berita', 'OSIS', '2026-05-26 02:10:39'),
(40, '19260577', 'Lisna', '083879729725', 'Kabupaten Tasikmalaya', 'SMKS SUKAPURA', 'Ya', 'Beasiswa Jalur Undangan', 'Beasiswa', 'Guru BK', 'Live Streaming', 'Pencinta Alam', '2026-05-26 02:29:07'),
(41, '19260515', 'SHALFA AZNHA ADITYA', '+62 831-5178-34', 'Kabupaten Ciamis', 'MAS IBADUL GHOFUR', 'Ya', 'Beasiswa Jalur Undangan', 'Rekomendasi Guru BK', 'Guru BK', 'Influencer', 'Pencinta Alam', '2026-05-26 02:38:16'),
(42, '19260209', 'salsabila putrinda nugraha', '087861906580', 'Kota Tasikmalaya', 'SMAN 10 TASIKMALAYA', 'Tidak', NULL, 'Biaya Terjangkau', 'Saudara', 'Olah Raga', 'OSIS', '2026-05-26 02:48:43'),
(43, '19260416', 'Nendi Sanjaya', '083121099042', 'Kota Tasikmalaya', 'SMKN 3 TASIKMALAYA', 'Ya', 'Beasiswa Jalur Undangan', 'Biaya Terjangkau', 'Guru BK', 'Olah Raga', 'OSIS', '2026-05-26 06:11:10'),
(44, '19260560', 'Safitri indriani', '087730376783', 'Kota Tasikmalaya', 'SMKS ARROHMAH DADAHA', 'Ya', 'Beasiswa Jalur Undangan', 'Beasiswa', 'Alumni UBSI', 'MC', 'OSIS', '2026-05-26 06:17:15'),
(45, '19260268', 'Ulya Nuryaomi', '087749603252', 'Kota Tasikmalaya', 'SMAN 10 TASIKMALAYA', 'Ya', 'Beasiswa Jalur Undangan', 'Beasiswa', 'Guru BK', 'MC', 'Keagamaan', '2026-05-26 06:50:21'),
(46, '19260179', 'RANDI ARDIANSYAH', '089602059149', 'Kota Tasikmalaya', 'SMAN 8 TASIKMALAYA', 'Ya', 'Beasiswa Jalur Undangan', 'Rekomendasi Guru BK', 'Guru BK', 'Editing Video', 'Musik', '2026-05-26 08:05:42'),
(47, '19260280', 'KIRANI NURRAMADHANI', '+62 812-8831-35', 'Kabupaten Tasikmalaya', 'SMAN 6 TASIKMALAYA', 'Ya', 'Beasiswa Jalur Undangan', 'Akreditasi Unggul', 'Guru BK', 'Olah Raga', 'OSIS', '2026-05-26 08:34:25'),
(48, '19260282', 'kirana nurramadhina', '087741970799', 'Kabupaten Tasikmalaya', 'SMAN 6 TASIKMALAYA', 'Ya', 'Beasiswa Jalur Undangan', 'Beasiswa', 'Guru BK', 'Olah Raga', 'OSIS', '2026-05-26 08:36:10'),
(49, '19260275', 'Asri Nurfalah', '082126357191', 'Kabupaten Tasikmalaya', 'SMKS YPC Tasikmalaya', 'Ya', 'Beasiswa Jalur Undangan', 'Beasiswa', 'Guru BK', 'MC', 'Bahasa', '2026-06-03 02:50:00'),
(50, '19260874', 'Reya pamungkas', '0881022315909', 'Kabupaten Ciamis', 'SMKS PGRI CIKONENG', 'Ya', 'Beasiswa Jalur Undangan', 'Beasiswa', 'Guru BK', 'MC', 'OSIS', '2026-06-03 21:46:02'),
(51, '19260871', 'ardi warist firdaus', '085846577820', 'Kota Tasikmalaya', 'SMKN 2 TASIKMALAYA', 'Tidak', NULL, 'Biaya Terjangkau', 'Instagram UBSI Tasikmalaya', 'Editing Video', 'Pencinta Alam', '2026-06-04 02:20:57'),
(52, '19260903', 'Yunita dewi', '081224579613', 'Kota Tasikmalaya', 'SMAN 4 Tasikmalaya', 'Tidak', NULL, 'Biaya Terjangkau', 'Instagram UBSI Tasikmalaya', 'Selebgram', 'Tari', '2026-06-04 02:28:44'),
(53, '19260798', 'Fajri Nuril Hidayat', '083116726113', 'Kota Tasikmalaya', 'SMKN 2 TASIKMALAYA', 'Tidak', NULL, 'Biaya Terjangkau', 'Teman', 'Olah Raga', 'Karang Taruna', '2026-06-04 02:29:01'),
(54, '19260896', 'Raffy Pramana', '081285219424', 'Kota Tasikmalaya', 'SMAT RIYADLUL ULUM WADDA\'WAH CONDONG', 'Tidak', NULL, 'Akreditasi Unggul', 'Alumni UBSI', 'Editing Photo', 'Musik', '2026-06-04 03:56:19'),
(55, '19260972', 'Muhammad Rizki', '085771802881', 'Kota Tasikmalaya', 'SMKN 1 TASIKMALAYA', 'Ya', 'Beasiswa Jalur Undangan', 'Beasiswa', 'Guru BK', 'Editing Video', 'Musik', '2026-06-05 07:23:00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `role` enum('admin','operator') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `nama`, `role`) VALUES
(1, 'admin', '$2y$10$VwpMOUbpsjBmVFqegx9QKupGFQsx7kHdxtPIQSoPPCD2B02Yp4fGm', 'Agung Baitul Hikmah, M.Kom', 'admin'),
(2, 'operator', '$2y$10$bsNZ.wb1Lp3wPKktKmVxTOQO6NaJtSYEKK6IsGjw8D9dd.3vXFTxC', 'Operator BSI Tasikmalaya', 'operator');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bju_data`
--
ALTER TABLE `bju_data`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tabel_event`
--
ALTER TABLE `tabel_event`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tabel_quisoner`
--
ALTER TABLE `tabel_quisoner`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bju_data`
--
ALTER TABLE `bju_data`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `tabel_event`
--
ALTER TABLE `tabel_event`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT for table `tabel_quisoner`
--
ALTER TABLE `tabel_quisoner`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
