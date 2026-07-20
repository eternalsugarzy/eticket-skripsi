-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 20, 2026 at 10:29 AM
-- Server version: 8.0.30
-- PHP Version: 8.2.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_eticket_skripsi`
--

-- --------------------------------------------------------

--
-- Table structure for table `banners`
--

CREATE TABLE `banners` (
  `id` bigint UNSIGNED NOT NULL,
  `judul` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gambar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `link_url` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `urutan` int NOT NULL DEFAULT '0',
  `status` enum('aktif','nonaktif') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'aktif',
  `tanggal_mulai` date DEFAULT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  `id_user` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `banners`
--

INSERT INTO `banners` (`id`, `judul`, `gambar`, `link_url`, `urutan`, `status`, `tanggal_mulai`, `tanggal_selesai`, `id_user`, `created_at`, `updated_at`) VALUES
(1, 'Suka Travel Mart', '1784027576_poster.jpeg', NULL, 1, 'aktif', NULL, NULL, 1, '2026-07-07 01:24:28', '2026-07-14 03:12:56'),
(2, 'Desa Bunglai', '1784027592_poster2.jpg', NULL, 2, 'aktif', NULL, NULL, 1, '2026-07-07 01:41:36', '2026-07-14 03:13:12'),
(3, 'Ramadhan EXPO Tanah Laut', '1784043564_tala-ramadhan1.jpg', NULL, 1, 'aktif', '2026-07-14', '2026-07-20', 22, '2026-07-14 07:39:24', '2026-07-17 09:53:45');

-- --------------------------------------------------------

--
-- Table structure for table `beritas`
--

CREATE TABLE `beritas` (
  `id` bigint UNSIGNED NOT NULL,
  `judul` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `kategori` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Berita Umum',
  `gambar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ringkasan` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `konten` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_publish` date NOT NULL,
  `status` enum('draft','published') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `id_kabupaten` bigint UNSIGNED DEFAULT NULL,
  `id_user` bigint UNSIGNED DEFAULT NULL,
  `dilihat` int UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `beritas`
--

INSERT INTO `beritas` (`id`, `judul`, `slug`, `kategori`, `gambar`, `ringkasan`, `konten`, `tanggal_publish`, `status`, `id_kabupaten`, `id_user`, `dilihat`, `created_at`, `updated_at`) VALUES
(1, 'Festival Pasar Terapung Banjarmasin 2026', 'festival-pasar-terapung-banjarmasin-2026', 'Event', '1784027534_pasarterapung.jpg', 'Festival Pasar Terapung', 'Festival Pasar Terapung Banjarmasin merupakan salah satu agenda wisata budaya yang menjadi daya tarik utama di Kalimantan Selatan. Festival ini diselenggarakan untuk melestarikan tradisi masyarakat Banjar yang telah berlangsung sejak ratusan tahun lalu, yaitu aktivitas jual beli di atas perahu atau jukung di kawasan sungai. Melalui penyelenggaraan festival, pengunjung dapat menyaksikan secara langsung keunikan budaya sungai yang menjadi identitas masyarakat Banjarmasin, sekaligus menikmati berbagai pertunjukan seni, kuliner khas, dan produk unggulan daerah.\r\n\r\nSelain menjadi sarana pelestarian budaya, Festival Pasar Terapung juga berperan penting dalam meningkatkan sektor pariwisata dan perekonomian masyarakat. Kehadiran wisatawan dari berbagai daerah memberikan peluang bagi pelaku usaha lokal, seperti pedagang, pengrajin, dan pelaku UMKM, untuk memasarkan produk mereka. Beragam kegiatan yang diselenggarakan, seperti parade jukung hias, pertunjukan seni tradisional, lomba budaya, serta promosi kuliner khas Banjar, menjadikan festival ini sebagai media promosi yang efektif bagi potensi wisata Kalimantan Selatan.\r\n\r\nFestival Pasar Terapung Banjarmasin tidak hanya menjadi hiburan bagi masyarakat, tetapi juga menjadi upaya memperkenalkan warisan budaya Banjar kepada generasi muda dan wisatawan mancanegara. Dengan dukungan pemerintah, komunitas lokal, dan masyarakat, festival ini terus berkembang sebagai salah satu ikon pariwisata unggulan daerah. Keberlangsungan festival diharapkan mampu menjaga kelestarian budaya sungai sekaligus memperkuat citra Banjarmasin sebagai \"Kota Seribu Sungai\" yang kaya akan nilai sejarah, budaya, dan potensi wisata.', '2026-07-06', 'published', 1, 1, 7, '2026-07-05 18:22:07', '2026-07-17 21:51:05'),
(2, 'Pasar Ramadhan Tanah Laut 2026', 'pasar-ramadhan-tanah-laut-2026', 'Event', '1784043412_tala-ramadhan.jpg', 'Pasar Ramadhan 2026', 'Pasar Ramadan Tanah Laut 2026, yang diselenggarakan dalam rangka Ramadan Expo 1447 H di kawasan Jalan H. Boedjasin (Pal-Palan), Pelaihari, merupakan pusat kegiatan ekonomi dan wisata kuliner selama bulan Ramadan. Acara ini menghadirkan sekitar 126 pelaku UMKM yang menawarkan beragam menu berbuka puasa, makanan khas daerah, minuman, serta produk lokal. Selain menjadi destinasi masyarakat untuk berbelanja kebutuhan berbuka, kegiatan ini juga bertujuan mendukung pertumbuhan UMKM, meningkatkan perputaran ekonomi daerah, serta menjadi sarana promosi pariwisata dan ekonomi kreatif di Kabupaten Tanah Laut.', '2026-07-14', 'published', 4, 22, 2, '2026-07-14 07:36:52', '2026-07-17 09:52:31');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `detail_transaksis`
--

CREATE TABLE `detail_transaksis` (
  `id` bigint UNSIGNED NOT NULL,
  `id_transaksi` bigint UNSIGNED NOT NULL,
  `id_jenis_tiket` bigint UNSIGNED NOT NULL,
  `jumlah` int NOT NULL,
  `harga_satuan` decimal(15,2) NOT NULL,
  `subtotal` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `detail_transaksis`
--

INSERT INTO `detail_transaksis` (`id`, `id_transaksi`, `id_jenis_tiket`, `jumlah`, `harga_satuan`, `subtotal`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, '10000.00', '10000.00', '2025-12-17 10:56:20', '2025-12-17 10:56:20'),
(2, 1, 2, 2, '5000.00', '10000.00', '2025-12-17 10:56:20', '2025-12-17 10:56:20'),
(3, 2, 1, 1, '10000.00', '10000.00', '2025-12-17 11:09:54', '2025-12-17 11:09:54'),
(4, 3, 1, 1, '10000.00', '10000.00', '2025-12-17 11:11:26', '2025-12-17 11:11:26'),
(5, 4, 2, 2, '5000.00', '10000.00', '2025-12-17 11:18:14', '2025-12-17 11:18:14'),
(6, 5, 1, 1, '10000.00', '10000.00', '2025-12-17 11:23:11', '2025-12-17 11:23:11'),
(7, 6, 1, 2, '10000.00', '20000.00', '2025-12-17 11:30:20', '2025-12-17 11:30:20'),
(8, 7, 1, 2, '10000.00', '20000.00', '2025-12-17 11:40:41', '2025-12-17 11:40:41'),
(9, 8, 1, 1, '10000.00', '10000.00', '2025-12-17 11:42:27', '2025-12-17 11:42:27'),
(10, 9, 1, 1, '5000.00', '5000.00', '2025-12-25 05:21:36', '2025-12-25 05:21:36'),
(11, 9, 2, 1, '3000.00', '3000.00', '2025-12-25 05:21:36', '2025-12-25 05:21:36'),
(12, 10, 1, 1, '10000.00', '10000.00', '2025-12-29 03:50:40', '2025-12-29 03:50:40'),
(13, 11, 1, 2, '10000.00', '20000.00', '2026-02-05 08:16:14', '2026-02-05 08:16:14'),
(14, 11, 2, 1, '8000.00', '8000.00', '2026-02-05 08:16:14', '2026-02-05 08:16:14'),
(15, 12, 1, 1, '5000.00', '5000.00', '2026-02-05 08:30:46', '2026-02-05 08:30:46'),
(16, 13, 1, 4, '5000.00', '20000.00', '2026-02-05 09:08:33', '2026-02-05 09:08:33'),
(17, 14, 1, 2, '10000.00', '20000.00', '2026-02-05 09:10:38', '2026-02-05 09:10:38'),
(18, 15, 1, 1, '10000.00', '10000.00', '2026-06-22 00:14:35', '2026-06-22 00:14:35'),
(19, 16, 1, 5, '10000.00', '50000.00', '2026-07-01 19:52:00', '2026-07-01 19:52:00'),
(20, 16, 2, 20, '8000.00', '160000.00', '2026-07-01 19:52:00', '2026-07-01 19:52:00'),
(21, 17, 1, 1, '10000.00', '10000.00', '2026-07-14 03:46:10', '2026-07-14 03:46:10'),
(22, 17, 2, 1, '8000.00', '8000.00', '2026-07-14 03:46:10', '2026-07-14 03:46:10'),
(23, 18, 1, 10, '10000.00', '100000.00', '2026-07-14 07:51:40', '2026-07-14 07:51:40'),
(24, 19, 1, 10, '10000.00', '100000.00', '2026-07-17 22:04:12', '2026-07-17 22:04:12'),
(25, 20, 1, 10, '10000.00', '100000.00', '2026-07-17 22:25:02', '2026-07-17 22:25:02');

-- --------------------------------------------------------

--
-- Table structure for table `diskon_rombongans`
--

CREATE TABLE `diskon_rombongans` (
  `id` bigint UNSIGNED NOT NULL,
  `min_orang` int NOT NULL COMMENT 'Minimal jumlah tiket untuk dapat diskon',
  `persen_diskon` decimal(5,2) NOT NULL COMMENT 'Persentase diskon (misal: 10.00)',
  `keterangan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `aktif` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `diskon_rombongans`
--

INSERT INTO `diskon_rombongans` (`id`, `min_orang`, `persen_diskon`, `keterangan`, `aktif`, `created_at`, `updated_at`) VALUES
(1, 10, '10.00', 'Diskon Rombongan Pelajar SD', 1, '2026-07-01 19:50:04', '2026-07-01 19:50:04'),
(2, 20, '15.00', 'Diskon Rombongan Pelajar SMP', 1, '2026-07-01 19:50:37', '2026-07-01 19:50:37'),
(3, 50, '20.00', 'Diskon Study Tour', 1, '2026-07-14 07:28:33', '2026-07-14 07:28:33');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` bigint UNSIGNED NOT NULL,
  `judul` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_event` date NOT NULL,
  `id_objek` bigint UNSIGNED DEFAULT NULL,
  `link_url` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('aktif','nonaktif') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'aktif',
  `id_user` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `judul`, `tanggal_event`, `id_objek`, `link_url`, `status`, `id_user`, `created_at`, `updated_at`) VALUES
(1, 'Festival Kembang Api', '2026-07-11', 1, NULL, 'aktif', 1, '2026-07-07 02:21:53', '2026-07-07 02:37:15'),
(2, 'Ramadhan EXPO Tanah Laut', '2026-07-14', NULL, NULL, 'aktif', 22, '2026-07-14 07:40:16', '2026-07-14 07:40:16');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `galeri_wisatas`
--

CREATE TABLE `galeri_wisatas` (
  `id` bigint UNSIGNED NOT NULL,
  `id_objek` bigint UNSIGNED NOT NULL,
  `foto` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `galeri_wisatas`
--

INSERT INTO `galeri_wisatas` (`id`, `id_objek`, `foto`, `created_at`, `updated_at`) VALUES
(4, 8, '1784027905_6a561b01f0354.jpg', '2026-07-14 03:18:25', '2026-07-14 03:18:25');

-- --------------------------------------------------------

--
-- Table structure for table `harga_tikets`
--

CREATE TABLE `harga_tikets` (
  `id` bigint UNSIGNED NOT NULL,
  `id_objek` bigint UNSIGNED NOT NULL,
  `id_jenis_tiket` bigint UNSIGNED NOT NULL,
  `harga` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `harga_tikets`
--

INSERT INTO `harga_tikets` (`id`, `id_objek`, `id_jenis_tiket`, `harga`, `created_at`, `updated_at`) VALUES
(4, 2, 1, '0.00', '2026-06-10 10:34:22', '2026-06-10 10:34:22'),
(6, 3, 1, '10000.00', '2026-06-10 10:34:22', '2026-06-10 10:34:22'),
(7, 2, 2, '0.00', '2026-06-10 10:34:22', '2026-06-10 10:34:22'),
(8, 1, 1, '0.00', '2026-06-10 10:34:22', '2026-06-10 10:34:22'),
(9, 1, 2, '0.00', '2026-06-10 10:34:22', '2026-06-10 10:34:22'),
(10, 2, 3, '0.00', '2026-06-10 10:34:22', '2026-06-10 10:34:22'),
(11, 3, 2, '8000.00', '2026-06-10 10:34:22', '2026-06-10 10:34:22'),
(12, 4, 1, '10000.00', '2026-06-10 10:34:22', '2026-06-10 10:34:22'),
(13, 4, 2, '8000.00', '2026-06-10 10:34:22', '2026-06-10 10:34:22'),
(14, 5, 1, '10000.00', '2026-06-10 10:34:22', '2026-06-10 10:34:22'),
(15, 5, 2, '8000.00', '2026-06-10 10:34:22', '2026-06-10 10:34:22'),
(16, 6, 1, '10000.00', '2026-06-10 10:34:22', '2026-06-10 10:34:22'),
(17, 6, 2, '8000.00', '2026-06-10 10:34:22', '2026-06-10 10:34:22'),
(18, 7, 1, '10000.00', '2026-06-10 10:34:22', '2026-06-10 10:34:22'),
(19, 7, 2, '8000.00', '2026-06-10 10:34:22', '2026-06-10 10:34:22'),
(20, 8, 1, '0.00', '2026-06-10 10:34:22', '2026-06-10 10:34:22'),
(21, 8, 2, '0.00', '2026-06-10 10:34:22', '2026-06-10 10:34:22'),
(22, 8, 3, '0.00', '2026-06-10 10:34:22', '2026-06-10 10:34:22'),
(23, 9, 1, '10000.00', '2026-06-10 10:34:22', '2026-06-10 10:34:22'),
(24, 9, 2, '8000.00', '2026-06-10 10:34:22', '2026-06-10 10:34:22'),
(25, 10, 1, '10000.00', '2026-06-10 10:34:22', '2026-06-10 10:34:22'),
(26, 10, 2, '8000.00', '2026-06-10 10:34:22', '2026-06-10 10:34:22'),
(27, 11, 1, '5000.00', '2026-06-10 10:34:22', '2026-06-10 10:34:22'),
(28, 11, 2, '3000.00', '2026-06-10 10:34:22', '2026-06-10 10:34:22'),
(29, 12, 1, '5000.00', '2026-06-10 10:34:22', '2026-06-10 10:34:22'),
(30, 12, 2, '3000.00', '2026-06-10 10:34:22', '2026-06-10 10:34:22'),
(31, 12, 3, '50000.00', '2026-06-10 10:34:22', '2026-06-10 10:34:22'),
(32, 13, 1, '5000.00', '2026-06-10 10:34:22', '2026-06-10 10:34:22'),
(33, 13, 2, '3000.00', '2026-06-10 10:34:22', '2026-06-10 10:34:22'),
(34, 14, 1, '5000.00', '2026-06-10 10:34:22', '2026-06-10 10:34:22'),
(35, 14, 2, '3000.00', '2026-06-10 10:34:22', '2026-06-10 10:34:22'),
(36, 15, 1, '5000.00', '2026-06-10 10:34:22', '2026-06-10 10:34:22'),
(37, 15, 2, '3000.00', '2026-06-10 10:34:22', '2026-06-10 10:34:22'),
(38, 15, 3, '50000.00', '2026-06-10 10:34:22', '2026-06-10 10:34:22'),
(39, 16, 1, '5000.00', '2026-06-10 10:34:22', '2026-06-10 10:34:22'),
(40, 16, 2, '3000.00', '2026-06-10 10:34:22', '2026-06-10 10:34:22'),
(41, 17, 1, '5000.00', '2026-06-10 10:34:22', '2026-06-10 10:34:22'),
(42, 17, 2, '3000.00', '2026-06-10 10:34:22', '2026-06-10 10:34:22'),
(43, 18, 1, '5000.00', '2026-06-10 10:34:22', '2026-06-10 10:34:22'),
(44, 18, 2, '3000.00', '2026-06-10 10:34:22', '2026-06-10 10:34:22'),
(47, 20, 1, '5000.00', '2026-06-10 10:34:22', '2026-06-10 10:34:22'),
(48, 20, 2, '3000.00', '2026-06-10 10:34:22', '2026-06-10 10:34:22'),
(50, 22, 1, '0.00', '2026-06-29 02:50:11', '2026-06-29 02:50:26');

-- --------------------------------------------------------

--
-- Table structure for table `jenis_tikets`
--

CREATE TABLE `jenis_tikets` (
  `id` bigint UNSIGNED NOT NULL,
  `nama_jenis` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jenis_tikets`
--

INSERT INTO `jenis_tikets` (`id`, `nama_jenis`, `created_at`, `updated_at`) VALUES
(1, 'Dewasa', '2026-06-10 10:34:22', '2026-06-10 10:34:22'),
(2, 'Anak-anak', '2026-06-10 10:34:22', '2026-06-10 10:34:22'),
(3, 'Mancanegara', '2026-06-10 10:34:22', '2026-06-10 10:34:22'),
(6, 'Lansia', '2026-07-14 07:18:54', '2026-07-14 07:18:54');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kabupatens`
--

CREATE TABLE `kabupatens` (
  `id` bigint UNSIGNED NOT NULL,
  `nama_kabupaten` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kabupatens`
--

INSERT INTO `kabupatens` (`id`, `nama_kabupaten`, `created_at`, `updated_at`) VALUES
(1, 'Kota Banjarmasin', '2026-06-10 10:34:22', '2026-06-10 10:34:22'),
(2, 'Kota Banjarbaru', '2026-06-10 10:34:22', '2026-06-10 10:34:22'),
(3, 'Kab. Banjar', '2026-06-10 10:34:22', '2026-06-10 10:34:22'),
(4, 'Kab. Tanah Laut', '2026-06-10 10:34:22', '2026-06-10 10:34:22'),
(5, 'Kab. Barito Kuala', '2026-06-10 10:34:22', '2026-06-10 10:34:22'),
(6, 'Kab. Tapin', '2026-06-10 10:34:22', '2026-06-10 10:34:22'),
(7, 'Kab. Hulu Sungai Selatan (HSS)', '2026-06-10 10:34:22', '2026-06-10 10:34:22'),
(8, 'Kab. Hulu Sungai Tengah (HST)', '2026-06-10 10:34:22', '2026-06-10 10:34:22'),
(9, 'Kab. Hulu Sungai Utara (HSU)', '2026-06-10 10:34:22', '2026-06-10 10:34:22'),
(10, 'Kab. Balangan', '2026-06-10 10:34:22', '2026-06-10 10:34:22'),
(11, 'Kab. Tabalong', '2026-06-10 10:34:22', '2026-06-10 10:34:22'),
(12, 'Kab. Tanah Bumbu', '2026-06-10 10:34:22', '2026-06-10 10:34:22'),
(13, 'Kab. Kotabaru', '2026-06-10 10:34:22', '2026-06-10 10:34:22');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_12_17_093943_create_kabupatens_table', 1),
(5, '2025_12_17_094256_create_objek_wisatas_table', 1),
(6, '2025_12_17_094322_create_jenis_tikets_table', 1),
(7, '2025_12_17_094417_create_harga_tikets_table', 1),
(10, '2025_12_17_094608_create_tikets_table', 1),
(11, '2025_12_17_094448_create_transaksis_table', 2),
(12, '2025_12_17_094552_create_transaksi_details_table', 2),
(13, '2025_12_17_191343_add_status_to_transaksis_table', 3),
(14, '2025_12_19_141809_add_deskripsi_to_objek_wisatas_table', 4),
(15, '2026_06_10_183023_add_front_end_fields_to_objek_wisatas_table', 5),
(16, '2026_06_11_104919_create_galeri_wisatas_table', 6),
(17, '2026_06_21_131638_create_pesanans_table', 7),
(19, '2026_07_18_132448_add_nip_to_users_table', 8);

-- --------------------------------------------------------

--
-- Table structure for table `objek_wisatas`
--

CREATE TABLE `objek_wisatas` (
  `id` bigint UNSIGNED NOT NULL,
  `id_kabupaten` bigint UNSIGNED NOT NULL,
  `nama_objek` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `foto` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `fasilitas` json DEFAULT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci,
  `latitude` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `longitude` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jam_operasional` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('buka','tutup') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'buka',
  `is_populer` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `objek_wisatas`
--

INSERT INTO `objek_wisatas` (`id`, `id_kabupaten`, `nama_objek`, `foto`, `deskripsi`, `fasilitas`, `alamat`, `latitude`, `longitude`, `jam_operasional`, `status`, `is_populer`, `created_at`, `updated_at`) VALUES
(1, 1, 'Menara Pandang Siring', '1784027408_menarapandang.png', 'Ikon wisata sungai di tengah kota Banjarmasin dengan view sungai Martapura.', '[\"Parkir Motor\", \"Toilet\", \"Spot Foto\", \"WiFi\", \"Pusat Informasi\", \"ATM / Money Changer\"]', 'Jl. Kapten Pierre Tendean, Gadang, Kec. Banjarmasin Tengah', '-3.3194', '114.5936', '06:00 - 22:00 WITA', 'buka', 1, '2026-06-10 10:34:22', '2026-07-14 03:10:08'),
(2, 1, 'Pasar Terapung Siring', '1784027428_pasarterapung.jpg', 'Pasar tradisional unik di atas jukung yang beroperasi setiap akhir pekan.', '[]', 'Jl. Kapten Pierre Tendean, Sungai Martapura', '-3.3188', '114.5939', '06:00 - 10:00 WITA (Sabtu-Minggu)', 'buka', 1, '2026-06-10 10:34:22', '2026-07-14 03:10:28'),
(3, 2, 'Amanah Borneo Park', '1784027455_tugu-amanah-borneo-park.jpg', 'Wahana rekreasi dan edukasi keluarga terbesar dengan fasilitas agrowisata.', '[\"Parkir Motor\", \"Parkir Mobil\", \"Toilet\", \"Mushola\", \"Pusat Informasi\", \"Pos Keamanan\"]', 'Jl. Taruna Bhakti, Palam, Kec. Cempaka', '-3.4731', '114.8115', '09:00 - 17:00 WITA', 'buka', 1, '2026-06-10 10:34:22', '2026-07-14 03:10:55'),
(4, 2, 'Kebun Raya Banjarbaru', '1784027481_kebunraya.jpg', 'Kawasan konservasi tumbuhan, area jogging, dan taman labirin.', '[\"Parkir Motor\", \"Parkir Mobil\", \"Toilet\", \"Gazebo\", \"Spot Foto\", \"Penyewaan Alat (Sewa Perahu/Sepeda)\"]', 'Kawasan Perkantoran Pemprov Kalsel, Cempaka', '-3.4522', '114.8389', '08:00 - 18:00 WITA', 'buka', 0, '2026-06-10 10:34:22', '2026-07-14 03:11:21'),
(5, 2, 'Danau Seran', '1784027492_seran.jpg', 'Danau eks galian tambang intan dengan air jernih dan pulau buatan.', '[]', 'Jl. Danau Seran, Guntung Manggis, Kec. Landasan Ulin', '-3.4678', '114.7944', '08:00 - 18:00 WITA', 'buka', 0, '2026-06-10 10:34:22', '2026-07-14 03:11:32'),
(6, 3, 'Tahura Sultan Adam', '1784027512_tahura.jpg', 'Taman hutan raya dengan pemandangan perbukitan dan kolam Belanda.', '[\"Parkir Motor\", \"Parkir Mobil\", \"Toilet\", \"Spot Foto\"]', 'Jl. Ir. P. M. Noor, Mandiangin Timur, Karang Intan', '-3.5042', '114.9083', '08:00 - 17:00 WITA', 'buka', 1, '2026-06-10 10:34:22', '2026-07-14 03:11:52'),
(7, 3, 'Kiram Park', '1784027894_Bukit-Kiram-3.jpg', 'Wisata alam pegunungan dengan spot foto instagramable dan villa.', '[\"Parkir Motor\", \"Parkir Mobil\"]', 'Desa Kiram, Kec. Karang Intan', '-3.5283', '114.8964', '24 Jam', 'buka', 0, '2026-06-10 10:34:22', '2026-07-14 03:18:14'),
(8, 3, 'Pasar Terapung Lok Baintan', '1784042200_pasarterapung.jpg', 'Pasar terapung alami dan legendaris yang beroperasi saat subuh hari.', '[]', 'Desa Lok Baintan, Kec. Sungai Tabuk', '-3.2981', '114.6642', '05:00 - 09:00 WITA', 'buka', 1, '2026-06-10 10:34:22', '2026-07-14 07:16:40'),
(9, 4, 'Pantai Takisung', 'default.jpg', 'Pantai populer dengan pemandangan sunset yang indah dan wahana banana boat.', NULL, 'Desa Takisung, Kec. Takisung', '-3.8794', '114.6542', '24 Jam', 'buka', 0, '2026-06-10 10:34:22', '2026-06-10 10:34:22'),
(10, 4, 'Pantai Batakan Baru', 'default.jpg', 'Pantai luas dengan fasilitas camping ground dan dermaga.', NULL, 'Desa Batakan, Kec. Panyipatan', '-4.0019', '114.6853', '24 Jam', 'buka', 1, '2026-06-10 10:34:22', '2026-06-10 10:34:22'),
(11, 4, 'Air Terjun Bajuin', '1784027917_bajuin.png', 'Air terjun alami di kaki pegunungan Meratus dengan suasana sejuk.', '[\"Parkir Motor\", \"Parkir Mobil\", \"Toilet\", \"Warung Makan\", \"Spot Foto\"]', 'Desa Sungai Bakar, Kec. Bajuin', '-3.9011', '114.8524', '08:00 - 17:00 WITA', 'buka', 0, '2026-06-10 10:34:22', '2026-07-14 03:18:37'),
(12, 5, 'Pulau Kembang', 'default.jpg', 'Habitat kera ekor panjang dan bekantan di tengah delta sungai Barito.', NULL, 'Kec. Alalak, Tengah Sungai Barito', '-3.3031', '114.5622', '08:00 - 17:00 WITA', 'buka', 0, '2026-06-10 10:34:22', '2026-06-10 10:34:22'),
(13, 6, 'Goa Batu Hapu', '1784027928_44-Goa-Batu-Hapu-1-scaled-2.jpg', 'Wisata goa alam dengan ornamen stalaktit dan stalagmit yang memukau.', '[]', 'Desa Batu Hapu, Kec. Hatungun', '-3.1114', '115.1236', '08:00 - 16:30 WITA', 'buka', 0, '2026-06-10 10:34:22', '2026-07-14 03:18:48'),
(14, 7, 'Air Panas Tanuhi', '1784027940_tanuhi.jpg', 'Pemandian air panas alami di kawasan pegunungan Meratus.', '[]', 'Desa Hulu Banyu, Kec. Loksado', '-2.7936', '115.4853', '07:00 - 18:00 WITA', 'buka', 0, '2026-06-10 10:34:22', '2026-07-14 03:19:00'),
(15, 7, 'Bamboo Rafting Loksado', '1784027957_antarafoto-wisata-bamboo-rafting-di-kalimantan-selatan-231021-bay-3.jpg', 'Arung jeram menggunakan rakit bambu tradisional menyusuri sungai Amandit.', '[]', 'Sungai Amandit, Kec. Loksado', '-2.7911', '115.5022', '08:00 - 16:00 WITA', 'buka', 1, '2026-06-10 10:34:22', '2026-07-14 03:19:17'),
(16, 8, 'Pagat Batu Benawa', 'default.jpg', 'Wisata alam sungai jernih dan gua di kaki bukit batu.', NULL, 'Desa Pagat, Kec. Batu Benawa', '-2.6531', '115.4214', '08:00 - 17:00 WITA', 'buka', 0, '2026-06-10 10:34:22', '2026-06-10 10:34:22'),
(17, 12, 'Pantai Pagatan', 'default.jpg', 'Pantai panjang yang menjadi pusat pesta adat laut Mappanretasi.', NULL, 'Kel. Kota Pagatan, Kec. Kusan Hilir', '-3.5936', '115.9872', '24 Jam', 'buka', 0, '2026-06-10 10:34:22', '2026-06-10 10:34:22'),
(18, 12, 'Goa Liang Bangkai', '1784027971_liangbangkai.jpg', 'Situs goa prasejarah dengan pemandangan eksotis dan jejak manusia purba.', '[]', 'Desa Dukuh Rejo, Kec. Mantewe', '-3.2842', '115.7124', '08:00 - 17:00 WITA', 'buka', 0, '2026-06-10 10:34:22', '2026-07-14 03:19:31'),
(20, 13, 'Bukit Mamake', '1784027987_mamake.jpg', 'Bukit paralayang dengan pemandangan laut dan pulau-pulau kecil.', '[]', 'Desa Sarang Tiung, Kec. Pulau Laut Sigam', '-3.2872', '116.2711', '24 Jam', 'buka', 1, '2026-06-10 10:34:22', '2026-07-14 03:19:47'),
(22, 4, 'Gunung Kayangan', '1784027881_IMG-20240507-WA0053.jpg', 'Gn Jajahan Jepang', '[\"Parkir Motor\", \"Toilet\", \"Restoran\", \"Pusat Informasi\"]', 'Jl A Yani, Tanah Laut', '-3.717009', '-965.247907', '09:00 - 17:00 WITA', 'tutup', 0, '2026-06-10 11:33:45', '2026-07-19 14:53:32'),
(23, 3, 'Bukit Batu', '1784041960_bukit-batu.jpg', 'Bukit Batu merupakan salah satu destinasi wisata alam yang berada di Desa Tiwingan Baru, Kecamatan Aranio, Kabupaten Banjar, Kalimantan Selatan. Destinasi ini terletak di kawasan Waduk Riam Kanan dan menjadi bagian dari kawasan wisata alam yang dikelilingi oleh Pegunungan Meratus. Dari puncak bukit, pengunjung dapat menikmati panorama hamparan waduk berwarna biru kehijauan, gugusan pulau-pulau kecil, serta perbukitan hijau yang menciptakan pemandangan yang indah dan menenangkan.', '[\"Parkir Motor\", \"Parkir Mobil\", \"Toilet\", \"Mushola\", \"Gazebo\", \"Spot Foto\", \"Camping Ground\", \"Pos Keamanan\", \"Penyewaan Alat (Sewa Perahu/Sepeda)\"]', 'Tiwingan Lama, Kec. Aranio, Kabupaten Banjar, Kalimantan Selatan 70671', '-3.504760', '115.071297', '08:00 - 22:00', 'buka', 1, '2026-07-14 07:12:40', '2026-07-14 07:12:40');

-- --------------------------------------------------------

--
-- Table structure for table `pengunjungs`
--

CREATE TABLE `pengunjungs` (
  `id` bigint UNSIGNED NOT NULL,
  `nama` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_wa` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pengunjungs`
--

INSERT INTO `pengunjungs` (`id`, `nama`, `email`, `no_wa`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Muhammad Irwan Firmanto', 'irwan@mail.com', '085347619091', '$2y$12$br0IabE0UBBDYqTU9uQySeiYK5ovfFpkmhWDVyOnrPMANBplBpPl.', NULL, '2026-06-30 21:09:45', '2026-06-30 21:09:45'),
(2, 'Ahmad Subarjo', 'ahmad.s@contoh.com', '087896552010', '$2y$12$uMpO3y0fdj4O.L7Jo2bPreQIbsML0yPJKIpeDWGtw2a5yWG/2rtFS', NULL, '2026-07-08 04:52:19', '2026-07-08 04:52:19'),
(3, 'Ahmad Shawity', 'shawity@mail.com', '089845601233', '$2y$12$YAEIYO3uXOgT370RMsULguGYUW7pDyuYuFZFzso6qRh12xn5mRS7W', NULL, '2026-07-14 06:56:07', '2026-07-14 06:56:07');

-- --------------------------------------------------------

--
-- Table structure for table `pesanans`
--

CREATE TABLE `pesanans` (
  `id` bigint UNSIGNED NOT NULL,
  `id_pengunjung` bigint UNSIGNED DEFAULT NULL,
  `kode_pesanan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_pengunjung` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_wa` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_kunjungan` date NOT NULL,
  `id_objek` bigint UNSIGNED NOT NULL,
  `total_bayar` int NOT NULL,
  `diskon_persen` decimal(5,2) NOT NULL DEFAULT '0.00',
  `diskon_nominal` bigint NOT NULL DEFAULT '0',
  `id_voucher` bigint UNSIGNED DEFAULT NULL,
  `kode_voucher` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `diskon_voucher_nominal` bigint NOT NULL DEFAULT '0',
  `status_pembayaran` enum('Unpaid','Paid','Cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Unpaid',
  `status_tiket` enum('active','used') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `waktu_validasi` datetime DEFAULT NULL,
  `snap_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pesanans`
--

INSERT INTO `pesanans` (`id`, `id_pengunjung`, `kode_pesanan`, `nama_pengunjung`, `no_wa`, `email`, `tanggal_kunjungan`, `id_objek`, `total_bayar`, `diskon_persen`, `diskon_nominal`, `id_voucher`, `kode_voucher`, `diskon_voucher_nominal`, `status_pembayaran`, `status_tiket`, `waktu_validasi`, `snap_token`, `created_at`, `updated_at`) VALUES
(1, NULL, 'ORD-20260621-VUM2C', 'Muhammad Irwan Firmanto', '089845601233', 'irwan@mail.com', '2026-06-22', 1, 28000, '0.00', 0, NULL, NULL, 0, 'Paid', 'active', NULL, NULL, '2026-06-21 05:58:27', '2026-06-21 06:07:43'),
(2, NULL, 'ORD-20260621-9AQ74', 'Ahmad Saidun', '087896552010', 'said@mail.com', '2026-06-23', 2, 10000, '0.00', 0, NULL, NULL, 0, 'Unpaid', 'active', NULL, NULL, '2026-06-21 06:17:04', '2026-06-21 06:17:04'),
(3, NULL, 'ORD-20260621-NP15Z', 'Ahmad Saidun', '087896552010', 'said@mail.com', '2026-06-23', 2, 10000, '0.00', 0, NULL, NULL, 0, 'Paid', 'active', NULL, NULL, '2026-06-21 06:22:11', '2026-06-21 06:22:23'),
(4, NULL, 'ORD-20260621-KW99C', 'Ahmad Saidun', '087896552010', 'said@mail.com', '2026-06-23', 2, 10000, '0.00', 0, NULL, NULL, 0, 'Unpaid', 'active', NULL, NULL, '2026-06-21 06:47:20', '2026-06-21 06:47:20'),
(5, NULL, 'ORD-20260621-ZOJOU', 'Ahmad Saidun', '087896552010', 'said@mail.com', '2026-06-23', 2, 10000, '0.00', 0, NULL, NULL, 0, 'Unpaid', 'active', NULL, NULL, '2026-06-21 06:53:16', '2026-06-21 06:53:16'),
(6, NULL, 'ORD-20260621-5O70L', 'Ahmad Saidun', '087896552010', 'said@mail.com', '2026-06-23', 2, 10000, '0.00', 0, NULL, NULL, 0, 'Paid', 'active', NULL, NULL, '2026-06-21 06:58:38', '2026-06-21 07:02:12'),
(7, NULL, 'ORD-20260621-URGFZ', 'Ahmad Saidun', '087896552010', 'putra@mail.com', '2026-06-30', 3, 36000, '0.00', 0, NULL, NULL, 0, 'Paid', 'used', '2026-06-23 06:02:20', NULL, '2026-06-21 11:03:04', '2026-06-22 22:02:20'),
(8, NULL, 'ORD-20260622-QDQQJ', 'Saputri', '08456311201', 'saputri@mail.com', '2026-06-29', 1, 20000, '0.00', 0, NULL, NULL, 0, 'Paid', 'used', '2026-06-23 06:01:16', NULL, '2026-06-22 09:42:35', '2026-06-22 22:01:16'),
(9, NULL, 'ORD-20260623-PRWRB', 'Nur Sabila', '085412332005', 'putri@mail.com', '2026-06-23', 11, 5000, '0.00', 0, NULL, NULL, 0, 'Paid', 'used', '2026-06-23 05:46:08', NULL, '2026-06-22 21:45:27', '2026-06-22 21:46:08'),
(10, NULL, 'ORD-20260623-PM4EJ', 'Saputri', '089845601233', 'admin@mail.com', '2026-06-28', 20, 10000, '0.00', 0, NULL, NULL, 0, 'Paid', 'used', '2026-06-23 05:47:31', NULL, '2026-06-22 21:46:45', '2026-06-22 21:47:31'),
(11, NULL, 'ORD-20260624-CDO1Z', 'Muhammad Irwan Firmanto', '087896552010', 'irwan@mail.com', '2026-06-24', 1, 10000, '0.00', 0, NULL, NULL, 0, 'Paid', 'used', '2026-06-24 03:14:30', NULL, '2026-06-23 19:13:01', '2026-06-23 19:14:30'),
(12, 1, 'ORD-20260701-57MIZ', 'Muhammad Irwan Firmanto', '087896552010', 'irwan@mail.com', '2026-07-05', 11, 10000, '0.00', 0, NULL, NULL, 0, 'Paid', 'active', NULL, NULL, '2026-06-30 21:11:49', '2026-06-30 21:11:53'),
(13, NULL, 'ORD-20260702-UKLFK', 'SDN Komet 1 Banjarbaru', '087845654111', 'komet1@mail.com', '2026-07-12', 5, 144500, '15.00', 25500, NULL, NULL, 0, 'Paid', 'active', NULL, NULL, '2026-07-01 19:54:43', '2026-07-01 19:54:46'),
(14, 2, 'ORD-20260708-HXN08', 'Ahmad Subarjo', '087896552010', 'ahmad.s@contoh.com', '2026-07-08', 15, 10000, '0.00', 0, NULL, NULL, 0, 'Paid', 'active', NULL, NULL, '2026-07-08 04:52:55', '2026-07-08 04:52:58'),
(15, 1, 'ORD-20260709-AWUY4', 'Muhammad Irwan Firmanto', '087896552010', 'irwan@mail.com', '2026-07-09', 10, 130050, '15.00', 27000, 1, 'TALA26', 22950, 'Paid', 'active', NULL, NULL, '2026-07-08 22:17:47', '2026-07-08 22:17:52'),
(16, 1, 'ORD-20260709-7IFXM', 'Muhammad Irwan Firmanto', '087896552010', 'irwan@mail.com', '2026-07-09', 10, 20000, '0.00', 0, NULL, NULL, 0, 'Unpaid', 'active', NULL, 'b1e8d8db-1238-4b16-a8ea-21ee62ad18df', '2026-07-08 22:35:00', '2026-07-08 23:08:15'),
(17, 1, 'ORD-20260709-WDMT2', 'Muhammad Irwan Firmanto', '087896552010', 'junady@mail.com', '2026-07-09', 10, 10000, '0.00', 0, NULL, NULL, 0, 'Paid', 'active', NULL, NULL, '2026-07-08 22:41:21', '2026-07-08 23:01:19'),
(18, 1, 'ORD-20260709-I6ESJ', 'Muhammad Irwan Firmanto', '087896552010', 'irwan@mail.com', '2026-07-09', 14, 10000, '0.00', 0, NULL, NULL, 0, 'Unpaid', 'active', NULL, NULL, '2026-07-08 22:46:28', '2026-07-08 22:46:28'),
(19, 1, 'ORD-20260709-CLTCS', 'Ahmad Saidun', '089845601233', 'putra@mail.com', '2026-07-09', 3, 36000, '0.00', 0, NULL, NULL, 0, 'Paid', 'active', NULL, NULL, '2026-07-08 23:02:06', '2026-07-08 23:02:44'),
(20, 1, 'ORD-20260709-HZXDJ', 'Saputri', '08456311201', 'junady@mail.com', '2026-07-09', 20, 5000, '0.00', 0, NULL, NULL, 0, 'Unpaid', 'active', NULL, 'd28c7542-f48c-49c7-958e-18ab939effb6', '2026-07-08 23:09:53', '2026-07-08 23:09:55'),
(21, 1, 'ORD-20260709-FEMZF', 'SDN Komet 1 Banjarbaru', '089845601233', 'raihan@mail.com', '2026-07-09', 3, 10000, '0.00', 0, NULL, NULL, 0, 'Paid', 'active', NULL, 'b5bc7cca-2dd8-4e45-9872-77f040a92931', '2026-07-08 23:11:31', '2026-07-08 23:12:01'),
(22, 1, 'ORD-20260709-UMLFM', 'Nur Sabila', '08456311201', 'junady@mail.com', '2026-07-09', 15, 10000, '0.00', 0, NULL, NULL, 0, 'Paid', 'active', NULL, 'a8da4cb5-b025-4a87-ab9a-6b3f11600282', '2026-07-08 23:12:18', '2026-07-08 23:12:46'),
(23, 1, 'ORD-20260710-O9W5W', 'Muhammad Irwan Firmanto', '087896552010', 'irwanfrozen@gmail.com', '2026-07-11', 18, 5000, '0.00', 0, NULL, NULL, 0, 'Cancelled', 'active', NULL, '294e5cfc-7ded-45ef-a6e4-e8d103b6eb6b', '2026-07-10 06:17:50', '2026-07-17 21:49:56'),
(24, 1, 'ORD-20260710-VNUGJ', 'Muhammad Irwan Firmanto', '087896552010', 'irwanfrozen@gmail.com', '2026-07-11', 7, 10000, '0.00', 0, NULL, NULL, 0, 'Paid', 'active', NULL, '441deb50-8628-4218-9ef0-d1e82d0c6aa9', '2026-07-10 06:18:44', '2026-07-10 06:19:16'),
(25, 1, 'ORD-20260710-QSMK8', 'Muhammad Irwan Firmanto', '08456311201', 'irwanfrozen@gmail.com', '2026-07-10', 4, 20000, '0.00', 0, NULL, NULL, 0, 'Paid', 'active', NULL, '1596ea49-ba4d-465f-9a36-1c849161a3e3', '2026-07-10 06:37:10', '2026-07-10 06:37:40'),
(26, 1, 'ORD-20260710-GNYV9', 'Saputri', '089845601233', 'irwanfrozen@gmail.com', '2026-07-10', 20, 5000, '0.00', 0, NULL, NULL, 0, 'Paid', 'active', NULL, 'e60cb99a-34c3-45bf-b2ff-636a3749da57', '2026-07-10 06:39:04', '2026-07-10 06:40:07'),
(27, 1, 'ORD-20260710-MXHJN', 'Ahmad Saidun', '08456311201', 'irwanfrozen@gmail.com', '2026-07-10', 3, 10000, '0.00', 0, NULL, NULL, 0, 'Paid', 'active', NULL, '37bbbb8a-c47c-4434-bbaf-bba7fbb97b78', '2026-07-10 06:46:35', '2026-07-10 06:47:15'),
(28, 1, 'ORD-20260710-Y8XWF', 'Muhammad Irwan Firmanto', '089845601233', 'irwanfrozen@gmail.com', '2026-07-10', 14, 5000, '0.00', 0, NULL, NULL, 0, 'Paid', 'used', '2026-07-10 14:56:56', 'bb7a5544-7404-4e1c-aa4a-9292e6e92fc2', '2026-07-10 06:54:14', '2026-07-10 06:56:56'),
(29, 1, 'ORD-20260714-7OJJX', 'Muhammad Irwan Firmanto', '087896552010', 'irwanfrozen@gmail.com', '2026-07-14', 3, 10000, '0.00', 0, NULL, NULL, 0, 'Paid', 'active', NULL, '05063f90-e49e-461a-b3fd-8f0a2b052eb3', '2026-07-14 03:23:37', '2026-07-14 03:25:11'),
(30, 3, 'ORD-20260714-WBYJO', 'Ahmad Shawity', '089845601233', 'irwanfrozen@gmail.com', '2026-07-14', 15, 5000, '0.00', 0, NULL, NULL, 0, 'Paid', 'used', '2026-07-18 06:05:16', '7abe29f0-7055-4bb2-86a3-42e3107e11f4', '2026-07-14 07:45:01', '2026-07-17 22:05:16'),
(31, 3, 'ORD-20260714-2ON0J', 'Ahmad Shawity', '08456311201', 'irwanfrozen@gmail.com', '2026-07-14', 5, 20000, '0.00', 0, NULL, NULL, 0, 'Unpaid', 'active', NULL, '377a5fbb-db40-4aa2-a1a6-bc45fd9ec2c3', '2026-07-14 07:56:29', '2026-07-14 07:56:33'),
(32, NULL, 'ORD-20260719-UVH2K', 'Muhammad Irwan Firmanto', '089845601233', 'irwanfrozen@gmail.com', '2026-07-25', 20, 5000, '0.00', 0, NULL, NULL, 0, 'Unpaid', 'active', NULL, NULL, '2026-07-19 08:41:42', '2026-07-19 08:41:42'),
(33, 1, 'ORD-20260719-AVH9P', 'Muhammad Irwan Firmanto', '089845601233', 'irwanfrozen@gmail.com', '2026-07-20', 20, 5000, '0.00', 0, NULL, NULL, 0, 'Paid', 'active', NULL, 'a6a9abab-6f9e-4f0d-aa0b-042012b22d32', '2026-07-19 08:43:01', '2026-07-19 08:48:44'),
(34, 1, 'ORD-20260719-YROQQ', 'Ahmad Saidun', '089845601233', 'irwanfrozen@gmail.com', '2026-07-20', 3, 10000, '0.00', 0, NULL, NULL, 0, 'Unpaid', 'active', NULL, 'd552aede-2561-4b36-904e-107f40f5c060', '2026-07-19 08:50:36', '2026-07-19 08:50:41'),
(35, 1, 'ORD-20260719-AHDIA', 'Nur Sabila', '089845601233', 'irwanfrozen@gmail.com', '2026-07-20', 5, 10000, '0.00', 0, NULL, NULL, 0, 'Paid', 'active', NULL, '8478182a-a391-4bc0-91d3-7ab4269d6cd1', '2026-07-19 09:01:24', '2026-07-19 09:01:51'),
(36, NULL, 'ORD-20260719-ITNMD', 'Muhammad Irwan Firmanto', '087896552010', 'irwanfrozen@gmail.com', '2026-07-20', 13, 5000, '0.00', 0, NULL, NULL, 0, 'Paid', 'active', NULL, '8a55f8b2-a1f6-460e-afc9-e1f99fb600bf', '2026-07-19 14:21:21', '2026-07-19 14:21:44'),
(38, NULL, 'ORD-20260719-ETLWD', 'Muhammad Irwan Firmanto', '087896552010', 'irwanfrozen@gmail.com', '2026-07-20', 1, 0, '0.00', 0, NULL, NULL, 0, 'Paid', 'active', NULL, NULL, '2026-07-19 14:50:35', '2026-07-19 14:50:35'),
(39, NULL, 'ORD-20260719-MLPSO', 'Muhammad Irwan Firmanto', '087896552010', 'irwanfrozen@gmail.com', '2026-07-20', 11, 5000, '0.00', 0, NULL, NULL, 0, 'Paid', 'active', NULL, '0d1a7a38-0ca1-4e8d-952e-1b4c84e5c1a6', '2026-07-19 14:51:53', '2026-07-19 14:52:11');

-- --------------------------------------------------------

--
-- Table structure for table `pesanan_details`
--

CREATE TABLE `pesanan_details` (
  `id` bigint UNSIGNED NOT NULL,
  `id_pesanan` bigint UNSIGNED NOT NULL,
  `id_jenis_tiket` bigint UNSIGNED NOT NULL,
  `harga` int NOT NULL,
  `jumlah` int NOT NULL,
  `subtotal` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pesanan_details`
--

INSERT INTO `pesanan_details` (`id`, `id_pesanan`, `id_jenis_tiket`, `harga`, `jumlah`, `subtotal`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 10000, 2, 20000, '2026-06-22 17:28:57', '2026-06-22 17:28:57'),
(2, 1, 2, 8000, 1, 8000, '2026-06-22 17:28:57', '2026-06-22 17:28:57'),
(3, 2, 1, 10000, 1, 10000, '2026-06-22 17:28:57', '2026-06-22 17:28:57'),
(4, 3, 1, 10000, 1, 10000, '2026-06-22 17:28:57', '2026-06-22 17:28:57'),
(5, 4, 1, 10000, 1, 10000, '2026-06-22 17:28:57', '2026-06-22 17:28:57'),
(6, 5, 1, 10000, 1, 10000, '2026-06-22 17:28:57', '2026-06-22 17:28:57'),
(7, 6, 1, 10000, 1, 10000, '2026-06-22 17:28:57', '2026-06-22 17:28:57'),
(8, 7, 1, 10000, 2, 20000, '2026-06-22 17:28:57', '2026-06-22 17:28:57'),
(9, 7, 2, 8000, 2, 16000, '2026-06-22 17:28:57', '2026-06-22 17:28:57'),
(10, 8, 1, 10000, 2, 20000, '2026-06-22 09:42:35', '2026-06-22 09:42:35'),
(11, 9, 1, 5000, 1, 5000, '2026-06-22 21:45:27', '2026-06-22 21:45:27'),
(12, 10, 1, 5000, 2, 10000, '2026-06-22 21:46:45', '2026-06-22 21:46:45'),
(13, 11, 1, 10000, 1, 10000, '2026-06-23 19:13:02', '2026-06-23 19:13:02'),
(14, 12, 1, 5000, 2, 10000, '2026-06-30 21:11:50', '2026-06-30 21:11:50'),
(15, 13, 1, 10000, 5, 50000, '2026-07-01 19:54:44', '2026-07-01 19:54:44'),
(16, 13, 2, 8000, 15, 120000, '2026-07-01 19:54:44', '2026-07-01 19:54:44'),
(17, 14, 1, 5000, 2, 10000, '2026-07-08 04:52:56', '2026-07-08 04:52:56'),
(18, 15, 1, 10000, 10, 100000, '2026-07-08 22:17:47', '2026-07-08 22:17:47'),
(19, 15, 2, 8000, 10, 80000, '2026-07-08 22:17:47', '2026-07-08 22:17:47'),
(20, 16, 1, 10000, 2, 20000, '2026-07-08 22:35:00', '2026-07-08 22:35:00'),
(21, 17, 1, 10000, 1, 10000, '2026-07-08 22:41:21', '2026-07-08 22:41:21'),
(22, 18, 1, 5000, 2, 10000, '2026-07-08 22:46:28', '2026-07-08 22:46:28'),
(23, 19, 1, 10000, 2, 20000, '2026-07-08 23:02:06', '2026-07-08 23:02:06'),
(24, 19, 2, 8000, 2, 16000, '2026-07-08 23:02:06', '2026-07-08 23:02:06'),
(25, 20, 1, 5000, 1, 5000, '2026-07-08 23:09:53', '2026-07-08 23:09:53'),
(26, 21, 1, 10000, 1, 10000, '2026-07-08 23:11:31', '2026-07-08 23:11:31'),
(27, 22, 1, 5000, 2, 10000, '2026-07-08 23:12:19', '2026-07-08 23:12:19'),
(28, 23, 1, 5000, 1, 5000, '2026-07-10 06:17:50', '2026-07-10 06:17:50'),
(29, 24, 1, 10000, 1, 10000, '2026-07-10 06:18:44', '2026-07-10 06:18:44'),
(30, 25, 1, 10000, 2, 20000, '2026-07-10 06:37:10', '2026-07-10 06:37:10'),
(31, 26, 1, 5000, 1, 5000, '2026-07-10 06:39:04', '2026-07-10 06:39:04'),
(32, 27, 1, 10000, 1, 10000, '2026-07-10 06:46:35', '2026-07-10 06:46:35'),
(33, 28, 1, 5000, 1, 5000, '2026-07-10 06:54:14', '2026-07-10 06:54:14'),
(34, 29, 1, 10000, 1, 10000, '2026-07-14 03:23:37', '2026-07-14 03:23:37'),
(35, 30, 1, 5000, 1, 5000, '2026-07-14 07:45:01', '2026-07-14 07:45:01'),
(36, 31, 1, 10000, 2, 20000, '2026-07-14 07:56:29', '2026-07-14 07:56:29'),
(37, 32, 1, 5000, 1, 5000, '2026-07-19 08:41:43', '2026-07-19 08:41:43'),
(38, 33, 1, 5000, 1, 5000, '2026-07-19 08:43:02', '2026-07-19 08:43:02'),
(39, 34, 1, 10000, 1, 10000, '2026-07-19 08:50:36', '2026-07-19 08:50:36'),
(40, 35, 1, 10000, 1, 10000, '2026-07-19 09:01:24', '2026-07-19 09:01:24'),
(41, 36, 1, 5000, 1, 5000, '2026-07-19 14:21:21', '2026-07-19 14:21:21'),
(43, 38, 1, 0, 1, 0, '2026-07-19 14:50:35', '2026-07-19 14:50:35'),
(44, 39, 1, 5000, 1, 5000, '2026-07-19 14:51:54', '2026-07-19 14:51:54');

-- --------------------------------------------------------

--
-- Table structure for table `tikets`
--

CREATE TABLE `tikets` (
  `id` bigint UNSIGNED NOT NULL,
  `id_transaksi` bigint UNSIGNED NOT NULL,
  `kode_unik` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('valid','terpakai') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'valid',
  `waktu_validasi` datetime DEFAULT NULL,
  `id_petugas` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transaksis`
--

CREATE TABLE `transaksis` (
  `id` bigint UNSIGNED NOT NULL,
  `no_transaksi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tgl_transaksi` date NOT NULL,
  `total_bayar` decimal(15,2) NOT NULL,
  `diskon_persen` decimal(5,2) NOT NULL DEFAULT '0.00',
  `diskon_nominal` bigint NOT NULL DEFAULT '0',
  `bayar` decimal(15,2) NOT NULL,
  `kembali` decimal(15,2) NOT NULL,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'sukses',
  `waktu_validasi` datetime DEFAULT NULL,
  `id_kasir` bigint UNSIGNED NOT NULL,
  `id_objek` bigint UNSIGNED NOT NULL,
  `status_tiket` enum('active','used','batal') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transaksis`
--

INSERT INTO `transaksis` (`id`, `no_transaksi`, `tgl_transaksi`, `total_bayar`, `diskon_persen`, `diskon_nominal`, `bayar`, `kembali`, `status`, `waktu_validasi`, `id_kasir`, `id_objek`, `status_tiket`, `created_at`, `updated_at`) VALUES
(1, 'TRX-20251217185620-997', '2025-12-17', '20000.00', '0.00', 0, '50000.00', '30000.00', 'sukses', NULL, 1, 2, 'active', '2025-12-17 10:56:20', '2025-12-17 10:56:20'),
(2, 'TRX-20251217190954-189', '2025-12-17', '10000.00', '0.00', 0, '10000.00', '0.00', 'sukses', NULL, 1, 2, 'active', '2025-12-17 11:09:54', '2025-12-17 11:09:54'),
(3, 'TRX-20251217191126-708', '2025-12-17', '10000.00', '0.00', 0, '20000.00', '10000.00', 'sukses', NULL, 1, 3, 'active', '2025-12-17 11:11:26', '2025-12-17 11:11:26'),
(4, 'TRX-20251217191814-398', '2025-12-17', '10000.00', '0.00', 0, '10000.00', '0.00', 'sukses', '2025-12-17 19:24:39', 1, 2, 'active', '2025-12-17 11:18:14', '2025-12-17 11:24:39'),
(5, 'TRX-20251217192311-192', '2025-12-17', '10000.00', '0.00', 0, '20000.00', '10000.00', 'sukses', '2025-12-17 19:29:42', 1, 2, 'active', '2025-12-17 11:23:11', '2025-12-17 11:29:42'),
(6, 'TRX-20251217193020-898', '2025-12-17', '20000.00', '0.00', 0, '20000.00', '0.00', 'sukses', '2025-12-17 19:30:52', 1, 3, 'active', '2025-12-17 11:30:20', '2025-12-17 11:30:52'),
(7, 'TRX-20251217194041-263', '2025-12-17', '20000.00', '0.00', 0, '50000.00', '30000.00', 'sukses', NULL, 1, 2, 'active', '2025-12-17 11:40:41', '2025-12-17 11:40:41'),
(8, 'TRX-20251217194227-264', '2025-12-17', '10000.00', '0.00', 0, '10000.00', '0.00', 'sukses', NULL, 1, 3, 'active', '2025-12-17 11:42:27', '2025-12-17 11:42:27'),
(9, 'TRX-20251225132136-287', '2025-12-25', '8000.00', '0.00', 0, '10000.00', '2000.00', 'sukses', '2025-12-25 13:25:46', 1, 14, 'active', '2025-12-25 05:21:36', '2025-12-25 05:25:46'),
(10, 'TRX-20251229115040-353', '2025-12-29', '10000.00', '0.00', 0, '50000.00', '40000.00', 'sukses', NULL, 7, 2, 'active', '2025-12-29 03:50:40', '2025-12-29 03:50:40'),
(11, 'TRX-20260205161613-502', '2026-02-05', '28000.00', '0.00', 0, '50000.00', '22000.00', 'batal', '2026-02-05 16:17:44', 1, 4, 'active', '2026-02-05 08:16:14', '2026-06-11 00:32:04'),
(12, 'TRX-20260205163046-122', '2026-02-05', '5000.00', '0.00', 0, '10000.00', '5000.00', 'sukses', '2026-02-05 16:31:12', 1, 12, 'active', '2026-02-05 08:30:46', '2026-02-05 08:31:12'),
(13, 'TRX-20260205170833-102', '2026-02-05', '20000.00', '0.00', 0, '20000.00', '0.00', 'sukses', '2026-02-05 17:09:22', 1, 17, 'active', '2026-02-05 09:08:33', '2026-02-05 09:09:22'),
(14, 'TRX-20260205171038-393', '2026-02-05', '20000.00', '0.00', 0, '20000.00', '0.00', 'sukses', '2026-02-05 17:11:01', 1, 5, 'active', '2026-02-05 09:10:38', '2026-02-05 09:11:01'),
(15, 'TRX-20260622081435-698', '2026-06-22', '10000.00', '0.00', 0, '10000.00', '0.00', 'used', '2026-06-23 06:01:32', 1, 1, 'used', '2026-06-22 00:14:35', '2026-06-22 22:01:32'),
(16, 'TRX-20260702035200-965', '2026-07-02', '178500.00', '15.00', 31500, '200000.00', '21500.00', 'sukses', NULL, 1, 6, 'active', '2026-07-01 19:52:00', '2026-07-01 19:52:00'),
(17, 'TRX-20260714114610-268', '2026-07-14', '18000.00', '0.00', 0, '20000.00', '2000.00', 'sukses', '2026-07-14 11:47:43', 1, 1, 'used', '2026-07-14 03:46:10', '2026-07-14 03:47:43'),
(18, 'TRX-20260714155140-397', '2026-07-14', '90000.00', '10.00', 10000, '100000.00', '10000.00', 'sukses', '2026-07-14 15:53:36', 9, 10, 'used', '2026-07-14 07:51:40', '2026-07-14 07:53:36'),
(19, 'TRX-20260718060412-854', '2026-07-18', '90000.00', '10.00', 10000, '100000.00', '10000.00', 'sukses', '2026-07-18 06:04:37', 1, 6, 'used', '2026-07-17 22:04:12', '2026-07-17 22:04:37'),
(20, 'TRX-20260718062502-884', '2026-07-18', '90000.00', '10.00', 10000, '100000.00', '10000.00', 'sukses', '2026-07-18 06:25:13', 1, 6, 'used', '2026-07-17 22:25:02', '2026-07-17 22:25:13');

-- --------------------------------------------------------

--
-- Table structure for table `transaksi_details`
--

CREATE TABLE `transaksi_details` (
  `id` bigint UNSIGNED NOT NULL,
  `id_transaksi` bigint UNSIGNED NOT NULL,
  `id_jenis_tiket` bigint UNSIGNED NOT NULL,
  `jumlah` int NOT NULL,
  `harga_snapshot` decimal(10,2) NOT NULL,
  `subtotal` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ulasans`
--

CREATE TABLE `ulasans` (
  `id` bigint UNSIGNED NOT NULL,
  `id_pengunjung` bigint UNSIGNED NOT NULL,
  `id_objek` bigint UNSIGNED NOT NULL,
  `id_pesanan` bigint UNSIGNED DEFAULT NULL,
  `rating` tinyint UNSIGNED NOT NULL,
  `komentar` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ulasans`
--

INSERT INTO `ulasans` (`id`, `id_pengunjung`, `id_objek`, `id_pesanan`, `rating`, `komentar`, `created_at`, `updated_at`) VALUES
(2, 1, 10, 15, 5, 'Bersih, Bagus', '2026-07-08 22:18:31', '2026-07-08 22:18:31'),
(3, 3, 15, 30, 5, 'Bersih dan Bagus', '2026-07-14 07:58:51', '2026-07-14 07:58:51');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','kadis_provinsi','kadis_kabkota','kasir','petugas') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'kasir',
  `nip` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_kabupaten` bigint UNSIGNED DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nama`, `username`, `password`, `role`, `nip`, `id_kabupaten`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Administrator', 'admin', '$2y$12$8MPSBtgYpzhANJuiboOIOe6GqK/DVCVKssVn2g/IgjefJivtG8n02', 'admin', NULL, NULL, NULL, '2025-12-17 01:59:55', '2025-12-20 02:12:52'),
(7, 'Muhammad Irwan Firmanto', 'irwan', '$2y$12$fHBqft6OS0nTOksVanNdzO2aP9GChbVTPv7tvLeC6QX10Fhst11hC', 'admin', NULL, NULL, NULL, '2025-12-20 02:13:56', '2025-12-20 02:13:56'),
(8, 'Ahmad Said', 'said', '$2y$12$iLkELP0mK.cWlLhcNB0Hsu/UCrU6vOXN51JMlQQSk5ytU1QJ2Ikp.', 'petugas', NULL, NULL, NULL, '2026-02-05 08:36:54', '2026-02-05 08:36:54'),
(9, 'Junady', 'junady', '$2y$12$yLWFIs14g7Atw9mO9R266eIqqir0CuI02lQmTEe8TzHbiSQApnSEC', 'kasir', NULL, NULL, NULL, '2026-02-05 08:37:13', '2026-02-05 08:37:13'),
(10, 'Iwan Fitriadi, SH., MH', 'kadis.provinsi', '$2y$12$nDNyuVGr07Aa5uP3/x9.tuu/les5iegPoT4T8vlRAsdUho3HTj7de', 'kadis_provinsi', '19612251998031004', NULL, NULL, '2026-07-01 05:24:30', '2026-07-17 21:31:44'),
(11, 'Kepala Dinas Pariwisata Kota Banjarmasin', 'kadis.banjarmasin', '$2y$12$nDNyuVGr07Aa5uP3/x9.tuu/les5iegPoT4T8vlRAsdUho3HTj7de', 'kadis_kabkota', NULL, 1, NULL, '2026-07-01 05:24:30', '2026-07-01 05:24:30'),
(12, 'Kepala Dinas Pariwisata Kota Banjarbaru', 'kadis.banjarbaru', '$2y$12$nDNyuVGr07Aa5uP3/x9.tuu/les5iegPoT4T8vlRAsdUho3HTj7de', 'kadis_kabkota', NULL, 2, NULL, '2026-07-01 05:24:30', '2026-07-01 05:24:30'),
(13, 'Kepala Dinas Pariwisata Kabupaten Banjar', 'kadis.banjar', '$2y$12$nDNyuVGr07Aa5uP3/x9.tuu/les5iegPoT4T8vlRAsdUho3HTj7de', 'kadis_kabkota', NULL, 3, NULL, '2026-07-01 05:24:30', '2026-07-01 05:24:30'),
(14, 'Kepala Dinas Pariwisata Kabupaten Barito Kuala', 'kadis.batola', '$2y$12$nDNyuVGr07Aa5uP3/x9.tuu/les5iegPoT4T8vlRAsdUho3HTj7de', 'kadis_kabkota', NULL, 5, NULL, '2026-07-01 05:24:30', '2026-07-01 05:24:30'),
(15, 'Kepala Dinas Pariwisata Kabupaten Tapin', 'kadis.tapin', '$2y$12$nDNyuVGr07Aa5uP3/x9.tuu/les5iegPoT4T8vlRAsdUho3HTj7de', 'kadis_kabkota', NULL, 6, NULL, '2026-07-01 05:24:30', '2026-07-01 05:24:30'),
(16, 'Kepala Dinas Pariwisata Kabupaten Hulu Sungai Selatan', 'kadis.hss', '$2y$12$nDNyuVGr07Aa5uP3/x9.tuu/les5iegPoT4T8vlRAsdUho3HTj7de', 'kadis_kabkota', NULL, 7, NULL, '2026-07-01 05:24:30', '2026-07-01 05:24:30'),
(17, 'Kepala Dinas Pariwisata Kabupaten Hulu Sungai Tengah', 'kadis.hst', '$2y$12$nDNyuVGr07Aa5uP3/x9.tuu/les5iegPoT4T8vlRAsdUho3HTj7de', 'kadis_kabkota', NULL, 8, NULL, '2026-07-01 05:24:30', '2026-07-01 05:24:30'),
(18, 'Kepala Dinas Pariwisata Kabupaten Hulu Sungai Utara', 'kadis.hsu', '$2y$12$nDNyuVGr07Aa5uP3/x9.tuu/les5iegPoT4T8vlRAsdUho3HTj7de', 'kadis_kabkota', NULL, 9, NULL, '2026-07-01 05:24:30', '2026-07-01 05:24:30'),
(19, 'Kepala Dinas Pariwisata Kabupaten Balangan', 'kadis.balangan', '$2y$12$nDNyuVGr07Aa5uP3/x9.tuu/les5iegPoT4T8vlRAsdUho3HTj7de', 'kadis_kabkota', NULL, 10, NULL, '2026-07-01 05:24:30', '2026-07-01 05:24:30'),
(20, 'Kepala Dinas Pariwisata Kabupaten Tabalong', 'kadis.tabalong', '$2y$12$nDNyuVGr07Aa5uP3/x9.tuu/les5iegPoT4T8vlRAsdUho3HTj7de', 'kadis_kabkota', NULL, 11, NULL, '2026-07-01 05:24:30', '2026-07-01 05:24:30'),
(21, 'Kepala Dinas Pariwisata Kabupaten Kotabaru', 'kadis.kotabaru', '$2y$12$nDNyuVGr07Aa5uP3/x9.tuu/les5iegPoT4T8vlRAsdUho3HTj7de', 'kadis_kabkota', NULL, 13, NULL, '2026-07-01 05:24:30', '2026-07-01 05:24:30'),
(22, 'Kepala Dinas Pariwisata Kabupaten Tanah Laut', 'kadis.tala', '$2y$12$nDNyuVGr07Aa5uP3/x9.tuu/les5iegPoT4T8vlRAsdUho3HTj7de', 'kadis_kabkota', NULL, 4, NULL, '2026-07-01 05:24:30', '2026-07-01 05:24:30'),
(23, 'Kepala Dinas Pariwisata Kabupaten Tanah Bumbu', 'kadis.tanbu', '$2y$12$nDNyuVGr07Aa5uP3/x9.tuu/les5iegPoT4T8vlRAsdUho3HTj7de', 'kadis_kabkota', NULL, 12, NULL, '2026-07-01 05:24:30', '2026-07-01 05:24:30');

-- --------------------------------------------------------

--
-- Table structure for table `video_terbaru`
--

CREATE TABLE `video_terbaru` (
  `id` bigint UNSIGNED NOT NULL,
  `judul` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `youtube_url` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `video_terbaru`
--

INSERT INTO `video_terbaru` (`id`, `judul`, `youtube_url`, `created_at`, `updated_at`) VALUES
(1, '7 WISATA PALING MENARIK DI BANJARMASIN KALIMANTAN SELATAN', 'https://youtu.be/j7HP13iqwXE?si=kn-bpXcbgxa-29Uu', '2026-07-07 02:22:41', '2026-07-14 07:42:04');

-- --------------------------------------------------------

--
-- Table structure for table `vouchers`
--

CREATE TABLE `vouchers` (
  `id` bigint UNSIGNED NOT NULL,
  `kode` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipe_diskon` enum('persen','nominal') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nilai_diskon` decimal(12,2) NOT NULL,
  `minimal_pembelian` bigint DEFAULT NULL,
  `maks_diskon` bigint DEFAULT NULL,
  `tanggal_mulai` date DEFAULT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  `limit_pemakaian` int DEFAULT NULL,
  `jumlah_terpakai` int NOT NULL DEFAULT '0',
  `status` enum('aktif','nonaktif') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'aktif',
  `id_user` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vouchers`
--

INSERT INTO `vouchers` (`id`, `kode`, `tipe_diskon`, `nilai_diskon`, `minimal_pembelian`, `maks_diskon`, `tanggal_mulai`, `tanggal_selesai`, `limit_pemakaian`, `jumlah_terpakai`, `status`, `id_user`, `created_at`, `updated_at`) VALUES
(1, 'TALA26', 'persen', '15.00', 75000, 50000, '2026-07-09', '2026-07-20', 100, 1, 'aktif', 1, '2026-07-08 22:15:16', '2026-07-08 22:17:48'),
(2, 'TAHUNBARUKALSEL', 'persen', '15.00', 250000, 150000, '2026-07-14', '2026-07-31', NULL, 0, 'aktif', 1, '2026-07-14 07:33:33', '2026-07-14 07:33:33');

-- --------------------------------------------------------

--
-- Table structure for table `wishlists`
--

CREATE TABLE `wishlists` (
  `id` bigint UNSIGNED NOT NULL,
  `id_pengunjung` bigint UNSIGNED NOT NULL,
  `id_objek` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wishlists`
--

INSERT INTO `wishlists` (`id`, `id_pengunjung`, `id_objek`, `created_at`, `updated_at`) VALUES
(1, 2, 11, '2026-07-08 05:13:23', '2026-07-08 05:13:23'),
(2, 1, 14, '2026-07-14 03:29:27', '2026-07-14 03:29:27'),
(3, 1, 11, '2026-07-14 03:29:30', '2026-07-14 03:29:30'),
(4, 1, 15, '2026-07-14 03:29:34', '2026-07-14 03:29:34');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `banners`
--
ALTER TABLE `banners`
  ADD PRIMARY KEY (`id`),
  ADD KEY `banners_id_user_foreign` (`id_user`);

--
-- Indexes for table `beritas`
--
ALTER TABLE `beritas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `beritas_slug_unique` (`slug`),
  ADD KEY `beritas_id_kabupaten_foreign` (`id_kabupaten`),
  ADD KEY `beritas_id_user_foreign` (`id_user`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `detail_transaksis`
--
ALTER TABLE `detail_transaksis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `detail_transaksis_id_transaksi_foreign` (`id_transaksi`);

--
-- Indexes for table `diskon_rombongans`
--
ALTER TABLE `diskon_rombongans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `events_id_user_foreign` (`id_user`),
  ADD KEY `events_id_objek_foreign` (`id_objek`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `galeri_wisatas`
--
ALTER TABLE `galeri_wisatas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `galeri_wisatas_id_objek_foreign` (`id_objek`);

--
-- Indexes for table `harga_tikets`
--
ALTER TABLE `harga_tikets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `harga_tikets_id_objek_foreign` (`id_objek`),
  ADD KEY `harga_tikets_id_jenis_tiket_foreign` (`id_jenis_tiket`);

--
-- Indexes for table `jenis_tikets`
--
ALTER TABLE `jenis_tikets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kabupatens`
--
ALTER TABLE `kabupatens`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `objek_wisatas`
--
ALTER TABLE `objek_wisatas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `objek_wisatas_id_kabupaten_foreign` (`id_kabupaten`);

--
-- Indexes for table `pengunjungs`
--
ALTER TABLE `pengunjungs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pengunjungs_email_unique` (`email`);

--
-- Indexes for table `pesanans`
--
ALTER TABLE `pesanans`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pesanans_kode_pesanan_unique` (`kode_pesanan`),
  ADD KEY `pesanans_id_objek_foreign` (`id_objek`),
  ADD KEY `pesanans_id_pengunjung_foreign` (`id_pengunjung`),
  ADD KEY `pesanans_id_voucher_foreign` (`id_voucher`);

--
-- Indexes for table `pesanan_details`
--
ALTER TABLE `pesanan_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_pesanan` (`id_pesanan`),
  ADD KEY `id_jenis_tiket` (`id_jenis_tiket`);

--
-- Indexes for table `tikets`
--
ALTER TABLE `tikets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tikets_kode_unik_unique` (`kode_unik`),
  ADD KEY `tikets_id_transaksi_foreign` (`id_transaksi`),
  ADD KEY `tikets_id_petugas_foreign` (`id_petugas`);

--
-- Indexes for table `transaksis`
--
ALTER TABLE `transaksis`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `transaksis_no_transaksi_unique` (`no_transaksi`);

--
-- Indexes for table `transaksi_details`
--
ALTER TABLE `transaksi_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transaksi_details_id_transaksi_foreign` (`id_transaksi`),
  ADD KEY `transaksi_details_id_jenis_tiket_foreign` (`id_jenis_tiket`);

--
-- Indexes for table `ulasans`
--
ALTER TABLE `ulasans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ulasans_id_pengunjung_foreign` (`id_pengunjung`),
  ADD KEY `ulasans_id_objek_foreign` (`id_objek`),
  ADD KEY `ulasans_id_pesanan_foreign` (`id_pesanan`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD KEY `users_id_kabupaten_foreign` (`id_kabupaten`);

--
-- Indexes for table `video_terbaru`
--
ALTER TABLE `video_terbaru`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vouchers`
--
ALTER TABLE `vouchers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `vouchers_kode_unique` (`kode`),
  ADD KEY `vouchers_id_user_foreign` (`id_user`);

--
-- Indexes for table `wishlists`
--
ALTER TABLE `wishlists`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `wishlists_pengunjung_objek_unique` (`id_pengunjung`,`id_objek`),
  ADD KEY `wishlists_id_objek_foreign` (`id_objek`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `banners`
--
ALTER TABLE `banners`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `beritas`
--
ALTER TABLE `beritas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `detail_transaksis`
--
ALTER TABLE `detail_transaksis`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `diskon_rombongans`
--
ALTER TABLE `diskon_rombongans`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `galeri_wisatas`
--
ALTER TABLE `galeri_wisatas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `harga_tikets`
--
ALTER TABLE `harga_tikets`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `jenis_tikets`
--
ALTER TABLE `jenis_tikets`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kabupatens`
--
ALTER TABLE `kabupatens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `objek_wisatas`
--
ALTER TABLE `objek_wisatas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `pengunjungs`
--
ALTER TABLE `pengunjungs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `pesanans`
--
ALTER TABLE `pesanans`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `pesanan_details`
--
ALTER TABLE `pesanan_details`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `tikets`
--
ALTER TABLE `tikets`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transaksis`
--
ALTER TABLE `transaksis`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `transaksi_details`
--
ALTER TABLE `transaksi_details`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ulasans`
--
ALTER TABLE `ulasans`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `video_terbaru`
--
ALTER TABLE `video_terbaru`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `vouchers`
--
ALTER TABLE `vouchers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `wishlists`
--
ALTER TABLE `wishlists`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `banners`
--
ALTER TABLE `banners`
  ADD CONSTRAINT `banners_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `beritas`
--
ALTER TABLE `beritas`
  ADD CONSTRAINT `beritas_id_kabupaten_foreign` FOREIGN KEY (`id_kabupaten`) REFERENCES `kabupatens` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `beritas_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `detail_transaksis`
--
ALTER TABLE `detail_transaksis`
  ADD CONSTRAINT `detail_transaksis_id_transaksi_foreign` FOREIGN KEY (`id_transaksi`) REFERENCES `transaksis` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_id_objek_foreign` FOREIGN KEY (`id_objek`) REFERENCES `objek_wisatas` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `events_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `galeri_wisatas`
--
ALTER TABLE `galeri_wisatas`
  ADD CONSTRAINT `galeri_wisatas_id_objek_foreign` FOREIGN KEY (`id_objek`) REFERENCES `objek_wisatas` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `harga_tikets`
--
ALTER TABLE `harga_tikets`
  ADD CONSTRAINT `harga_tikets_id_jenis_tiket_foreign` FOREIGN KEY (`id_jenis_tiket`) REFERENCES `jenis_tikets` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `harga_tikets_id_objek_foreign` FOREIGN KEY (`id_objek`) REFERENCES `objek_wisatas` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `objek_wisatas`
--
ALTER TABLE `objek_wisatas`
  ADD CONSTRAINT `objek_wisatas_id_kabupaten_foreign` FOREIGN KEY (`id_kabupaten`) REFERENCES `kabupatens` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pesanans`
--
ALTER TABLE `pesanans`
  ADD CONSTRAINT `pesanans_id_objek_foreign` FOREIGN KEY (`id_objek`) REFERENCES `objek_wisatas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pesanans_id_pengunjung_foreign` FOREIGN KEY (`id_pengunjung`) REFERENCES `pengunjungs` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `pesanans_id_voucher_foreign` FOREIGN KEY (`id_voucher`) REFERENCES `vouchers` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `pesanan_details`
--
ALTER TABLE `pesanan_details`
  ADD CONSTRAINT `pesanan_details_ibfk_1` FOREIGN KEY (`id_pesanan`) REFERENCES `pesanans` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pesanan_details_ibfk_2` FOREIGN KEY (`id_jenis_tiket`) REFERENCES `jenis_tikets` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tikets`
--
ALTER TABLE `tikets`
  ADD CONSTRAINT `tikets_id_petugas_foreign` FOREIGN KEY (`id_petugas`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `tikets_id_transaksi_foreign` FOREIGN KEY (`id_transaksi`) REFERENCES `transaksis` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transaksi_details`
--
ALTER TABLE `transaksi_details`
  ADD CONSTRAINT `transaksi_details_id_jenis_tiket_foreign` FOREIGN KEY (`id_jenis_tiket`) REFERENCES `jenis_tikets` (`id`),
  ADD CONSTRAINT `transaksi_details_id_transaksi_foreign` FOREIGN KEY (`id_transaksi`) REFERENCES `transaksis` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `ulasans`
--
ALTER TABLE `ulasans`
  ADD CONSTRAINT `ulasans_id_objek_foreign` FOREIGN KEY (`id_objek`) REFERENCES `objek_wisatas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ulasans_id_pengunjung_foreign` FOREIGN KEY (`id_pengunjung`) REFERENCES `pengunjungs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ulasans_id_pesanan_foreign` FOREIGN KEY (`id_pesanan`) REFERENCES `pesanans` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_id_kabupaten_foreign` FOREIGN KEY (`id_kabupaten`) REFERENCES `kabupatens` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `vouchers`
--
ALTER TABLE `vouchers`
  ADD CONSTRAINT `vouchers_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `wishlists`
--
ALTER TABLE `wishlists`
  ADD CONSTRAINT `wishlists_id_objek_foreign` FOREIGN KEY (`id_objek`) REFERENCES `objek_wisatas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `wishlists_id_pengunjung_foreign` FOREIGN KEY (`id_pengunjung`) REFERENCES `pengunjungs` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
