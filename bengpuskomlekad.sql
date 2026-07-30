-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 29, 2026 at 08:36 AM
-- Server version: 10.4.32-MariaDB-log
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bengpuskomlekad`
--

-- --------------------------------------------------------

--
-- Table structure for table `berita`
--

CREATE TABLE `berita` (
  `id` int(11) NOT NULL,
  `judul` varchar(500) NOT NULL,
  `kategori` varchar(100) DEFAULT 'Umum',
  `tanggal` date NOT NULL,
  `gambar` varchar(500) DEFAULT NULL,
  `isi` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `berita`
--

INSERT INTO `berita` (`id`, `judul`, `kategori`, `tanggal`, `gambar`, `isi`, `created_at`) VALUES
(1, 'Drone Interceptor', 'LITBANG', '2026-06-12', 'assets/images/elektronika.jpeg', 'BENGPUSKOMLEKAD sebagai unsur pelaksana pusat kecabangan berpartisipasi dalam gelar manuver lapangan yang merupakan bagian dari program Pendidikan Komponen Cadangan. Kegiatan ini merupakan bagian dari upaya modernisasi alutsista dan pengembangan teknologi pertahanan nasional.', '2026-07-15 05:57:31'),
(2, 'Modernisasi Fasilitas Bengkel Elektronika dengan Peralatan Terkini', 'Teknologi', '2026-06-08', 'assets/images/gedung-bengpus.jpeg', 'Investasi besar dalam peralatan modern untuk mendukung pemeliharaan sistem elektronika pertahanan generasi terbaru.', '2026-07-15 05:57:31'),
(3, 'Penandatanganan MoU dengan Industri Pertahanan Australia', 'Kerjasama', '2026-06-01', 'assets/images/sumga.jpeg', 'Kerjasama strategis dengan mitra internasional untuk bertukar ilmu pengetahuan seputar Teknologi.', '2026-07-15 05:57:31'),
(4, 'Kedatangan Kapus Komlekad', 'LITBANG', '2026-01-15', 'assets/images/berita/berita_1784095477_3049.png', 'Pemaparan kegiatan litbang di bengpus puskomlekad', '2026-07-15 06:04:37');

-- --------------------------------------------------------

--
-- Table structure for table `berita_db`
--

CREATE TABLE `berita_db` (
  `id` int(11) NOT NULL,
  `judul` varchar(500) NOT NULL,
  `kategori` varchar(100) DEFAULT 'Umum',
  `tanggal` date NOT NULL,
  `gambar` varchar(500) DEFAULT NULL,
  `isi` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `berita_db`
--

INSERT INTO `berita_db` (`id`, `judul`, `kategori`, `tanggal`, `gambar`, `isi`, `created_at`) VALUES
(1, 'Kedatangan Kapus Komlekad', 'Kegiatan', '2026-07-15', 'assets/images/berita/berita_1784102170_9966.png', 'demonstrasi proyek bengpus puskomlekad', '2026-07-15 07:56:10'),
(2, 'Modernisasi Fasilitas Bengkel Elektronika dengan Peralatan Terkini', 'Teknologi', '2026-06-08', 'assets/images/gedung-bengpus.jpeg', 'Investasi besar dalam peralatan modern untuk mendukung pemeliharaan sistem elektronika pertahanan generasi terbaru guna memastikan kesiapan penuh operasional satuan.', '2026-07-15 08:09:13'),
(3, 'Penandatanganan MoU dengan Industri Pertahanan Australia', 'Kerjasama', '2026-06-01', 'assets/images/sumga.jpeg', 'Kerjasama strategis dengan mitra internasional untuk bertukar ilmu pengetahuan seputar Teknologi Elektronika Militer dan pengembangan kapabilitas personel Bengpuskomlekad.', '2026-07-15 08:09:13'),
(4, 'halooooooooooo', 'Kerjasama', '2026-07-15', 'assets/images/berita/berita_1784103885_5503.png', 'selamat pagi dunia \r\n\r\nprint (\"hello world\")', '2026-07-15 08:24:46');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `subjek` varchar(255) DEFAULT NULL,
  `pesan` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`id`, `nama`, `email`, `subjek`, `pesan`, `is_read`, `created_at`) VALUES
(1, 'mesi', 'halo@gmiaal.ovm', NULL, '🏆 Hall of Fame sebagai Institusi & PenghargaanIstilah ini paling sering digunakan untuk merujuk pada penghargaan tertinggi atau museum yang mengabadikan tokoh-tokoh legendaris:Olahraga: Contohnya Pro Football Hall of Fame di Canton, Ohio, dan WWE Hall of Fame untuk gulat profesional.Musik: Terdapat Rock & Roll Hall of Fame yang merayakan musisi paling berpengaruh di dunia.Hiburan: Dikenal juga dengan Walk of Fame (seperti di Hollywood) yang mengabadikan nama selebritas di trotoar.🎵 Lagu \"Hall of Fame\" oleh The Script ft. will.i.amLagu ini dirilis sebagai singel utama dari album ketiga mereka, bertajuk #3.Makna: Lagu ini menyampaikan pesan motivasi yang kuat, mendorong pendengarnya untuk menjadi sosok yang hebat, kuat, dan meninggalkan jejak positif dalam sejarah.Platform: Anda bisa mendengarkan lagu ini melalui Apple Music atau membaca liriknya di Spotify.', 1, '2026-06-24 15:33:32');

-- --------------------------------------------------------

--
-- Table structure for table `pimpinan_db`
--

CREATE TABLE `pimpinan_db` (
  `id` int(11) NOT NULL,
  `nama` varchar(300) NOT NULL,
  `masa_jabatan` varchar(200) NOT NULL,
  `gambar` varchar(500) DEFAULT NULL,
  `is_current` tinyint(1) DEFAULT 0,
  `urutan` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pimpinan_db`
--

INSERT INTO `pimpinan_db` (`id`, `nama`, `masa_jabatan`, `gambar`, `is_current`, `urutan`, `created_at`) VALUES
(1, 'Kolonel Cke Setyo Budi Nugroho, S.Sos.', '2025 - Sekarang', 'assets/images/kabeng.jpeg', 1, 100, '2026-07-15 05:57:31'),
(2, 'Kolonel Cke Muh. Hatta, M.P.M., MCap.Mgt.', '2023 - 2025', 'assets/images/kabeng-hatta.jpg', 0, 90, '2026-07-15 05:57:31'),
(3, 'Kolonel Chb Moch. Sholeh, SH., M.M.', '2023 - 2023', 'assets/images/kabeng-sholeh.jpg', 0, 80, '2026-07-15 05:57:31'),
(4, 'Kolonel Chb Umang Arfan Latsusmintarto, S.Si', '2022 - 2023', 'assets/images/kabeng-umang.jpg', 0, 70, '2026-07-15 05:57:31'),
(5, 'Kolonel Chb Anang Murtioso, S.Si.', '2020 - 2022', NULL, 0, 60, '2026-07-15 05:57:31'),
(6, 'Kolonel Chb Try Haryono, S.sos., M.M.', '2019 - 2020', 'assets/images/kabeng-haryono.jpg', 0, 55, '2026-07-15 05:57:31'),
(7, 'Kolonel Cke Ir.Agus Budi Prayitno', '2018 - 2019', 'assets/images/kabeng-prayitno.jpg', 0, 50, '2026-07-15 05:57:31'),
(8, 'Kolonel Chb Drs. Leo Yunaidy Wibisono, M.A.P.', '2016 - 2018', 'assets/images/kabeng-leo.jpg', 0, 45, '2026-07-15 05:57:31'),
(9, 'Kolonel Chb Zakaria', '2015 - 2016', 'assets/images/kabeng-zakaria.jpg', 0, 40, '2026-07-15 05:57:31'),
(10, 'Kolonel Chb Totok', '2014 - 2015', 'assets/images/kabeng-totok.jpg', 0, 35, '2026-07-15 05:57:31'),
(11, 'Kolonel Chb Sasmito Yupitoro, S.T.', '2011 - 2014', 'assets/images/kabeng-sasmito.jpg', 0, 30, '2026-07-15 05:57:31'),
(12, 'Kolonel Chb Harijono, S.T.', '2006 - 2011', 'assets/images/kabeng-harijono.jpg', 0, 25, '2026-07-15 05:57:31'),
(13, 'Kolonel Chb Sumarno', '2003 - 2006', 'assets/images/kabeng-sumarno.jpg', 0, 20, '2026-07-15 05:57:31'),
(14, 'Kolonel Chb E. Supribadio. TE', '1997 - 2003', 'assets/images/kabeng-supribadio.jpg', 0, 18, '2026-07-15 05:57:31'),
(15, 'Kolonel Chb Wiyono', '1991 - 1997', 'assets/images/kabeng-wiyono.jpg', 0, 16, '2026-07-15 05:57:31'),
(16, 'Kolonel Chb Widoyo', '1987 - 1991', 'assets/images/kabeng-widoyo.jpg', 0, 14, '2026-07-15 05:57:31'),
(17, 'Kolonel Chb Priyambodo', '1980 - 1987', 'assets/images/kabeng-priyambodo.jpg', 0, 12, '2026-07-15 05:57:31'),
(18, 'Kolonel Chb R. Karnoto (alm)', '1972 - 1980', 'assets/images/kabeng-karnoto.jpg', 0, 10, '2026-07-15 05:57:31'),
(19, 'Letnan Kolonel Chb Harmono (alm)', '1970 - 1972', 'assets/images/kabeng-harmono.jpg', 0, 8, '2026-07-15 05:57:31'),
(20, 'Letnan Kolonel Chb Drondio (alm)', '1966 - 1970', 'assets/images/kabeng-drondio.jpg', 0, 6, '2026-07-15 05:57:31'),
(21, 'Letnan Kolonel Chb Poedjadi (alm)', '1961 - 1966', 'assets/images/kabeng-poedjadi.jpg', 0, 4, '2026-07-15 05:57:31'),
(22, 'Letnan Satu Chb Harjadi (alm)', '1950 - 1961', 'assets/images/kabeng-harjadi.jpg', 0, 2, '2026-07-15 05:57:31');

-- --------------------------------------------------------

--
-- Table structure for table `struktur_organisasi_db`
--

CREATE TABLE `struktur_organisasi_db` (
  `id` int(11) NOT NULL,
  `unsur` varchar(200) NOT NULL,
  `jabatan` varchar(200) NOT NULL,
  `nama` varchar(300) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `urutan` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `struktur_organisasi_db`
--

INSERT INTO `struktur_organisasi_db` (`id`, `unsur`, `jabatan`, `nama`, `parent_id`, `urutan`, `created_at`) VALUES
(1, 'pimpinan', 'KEPALA', 'Nama Kepala', NULL, 10, '2026-07-20 09:13:51'),
(2, 'pimpinan', 'WAKIL KEPALA', 'Nama Wakil Kepala', NULL, 20, '2026-07-20 09:13:51'),
(3, 'pembantu_pimpinan', 'KABAGUM', 'Nama Kabagum', NULL, 30, '2026-07-20 09:13:51'),
(4, 'pembantu_pimpinan', 'KABAGRENDAL', 'Nama Kabagrendal', NULL, 40, '2026-07-20 09:13:51'),
(5, 'pelayanan', 'PASITUUD', 'Nama Pasituud', NULL, 50, '2026-07-20 09:13:51'),
(6, 'pelaksana_kabeng', 'KABENG SISKOM', 'Nama Kabeng Siskom', NULL, 60, '2026-07-20 09:13:51'),
(7, 'pelaksana_subbeng_siskom', 'SUBBENG RADIO DIGILOG', 'Nama Kasubbeng', NULL, 61, '2026-07-20 09:13:51'),
(8, 'pelaksana_subbeng_siskom', 'SUBBENG ALKOMSAL & MULTIMEDIA', 'Nama Kasubbeng', NULL, 62, '2026-07-20 09:13:51'),
(9, 'pelaksana_subbeng_siskom', 'SUBBENG ALKOMSAT', 'Nama Kasubbeng', NULL, 63, '2026-07-20 09:13:51'),
(10, 'pelaksana_kabeng', 'KABENG SISLEK', 'Nama Kabeng Sislek', NULL, 70, '2026-07-20 09:13:51'),
(11, 'pelaksana_subbeng_sislek', 'SUBBENG ALDALLEK', 'Nama Kasubbeng', NULL, 71, '2026-07-20 09:13:51'),
(12, 'pelaksana_subbeng_sislek', 'SUBBENG ALPERNIKA', 'Nama Kasubbeng', NULL, 72, '2026-07-20 09:13:51'),
(13, 'pelaksana_subbeng_sislek', 'SUBBENG MATINDRALEK', 'Nama Kasubbeng', NULL, 73, '2026-07-20 09:13:51'),
(14, 'pelaksana_subbeng_sislek', 'SUBBENG MEKATRONIKA', 'Nama Kasubbeng', NULL, 74, '2026-07-20 09:13:51'),
(15, 'pelaksana_kabeng', 'KABENG JARINGAN DAN TIK', 'Nama Kabeng Jarnet TIK', NULL, 80, '2026-07-20 09:13:51'),
(16, 'pelaksana_subbeng_jarnet', 'SUBBENG JARKABEL', 'Nama Kasubbeng', NULL, 81, '2026-07-20 09:13:51'),
(17, 'pelaksana_subbeng_jarnet', 'SUBBENG JARNIRKABEL', 'Nama Kasubbeng', NULL, 82, '2026-07-20 09:13:51'),
(18, 'pelaksana_subbeng_jarnet', 'SUBBENG TIK', 'Nama Kasubbeng', NULL, 83, '2026-07-20 09:13:51'),
(19, 'pelaksana_kabeng', 'KABENG INTEGRASI & POWER SYSTEM', 'Nama Kabeng Integrasi', NULL, 90, '2026-07-20 09:13:51'),
(20, 'pelaksana_subbeng_integrasi', 'SUBBENG INTEGRASI', 'Nama Kasubbeng', NULL, 91, '2026-07-20 09:13:51'),
(21, 'pelaksana_subbeng_integrasi', 'SUBBENG POWER SYSTEM', 'Nama Kasubbeng', NULL, 92, '2026-07-20 09:13:51'),
(22, 'pelaksana_kabeng', 'KAGUD', 'Nama Kagud', NULL, 100, '2026-07-20 09:13:51');

-- --------------------------------------------------------

--
-- Table structure for table `struktur_organisasi_image_db`
--

CREATE TABLE `struktur_organisasi_image_db` (
  `id` int(11) NOT NULL,
  `gambar` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `struktur_organisasi_image_db`
--

INSERT INTO `struktur_organisasi_image_db` (`id`, `gambar`) VALUES
(1, 'assets/images/struktur/struktur_organisasi_1784729239.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `video_terkait_db`
--

CREATE TABLE `video_terkait_db` (
  `id` int(11) NOT NULL,
  `judul` varchar(500) NOT NULL,
  `url_video` varchar(500) NOT NULL,
  `thumbnail` varchar(500) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `video_terkait_db`
--

INSERT INTO `video_terkait_db` (`id`, `judul`, `url_video`, `thumbnail`, `created_at`) VALUES
(1, 'print (\'halooo\')', 'https://www.youtube.com/watch?v=3Bu3vUMmVfs', 'assets/images/video/video_1784541367_6778.png', '2026-07-20 09:56:07'),
(2, 'ini video keren', 'assets/videos/video_1784778740_9047.mp4', 'assets/images/video/video_thumb_1784778740_9615.jpeg', '2026-07-23 03:52:20'),
(3, 'PRASPA 2026', 'https://www.youtube.com/watch?v=XmO5g8QL_yY', 'assets/images/video/video_1785297128_7689.jpg', '2026-07-29 03:52:08');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `berita`
--
ALTER TABLE `berita`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `berita_db`
--
ALTER TABLE `berita_db`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pimpinan_db`
--
ALTER TABLE `pimpinan_db`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `struktur_organisasi_db`
--
ALTER TABLE `struktur_organisasi_db`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `struktur_organisasi_image_db`
--
ALTER TABLE `struktur_organisasi_image_db`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `video_terkait_db`
--
ALTER TABLE `video_terkait_db`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `berita`
--
ALTER TABLE `berita`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `berita_db`
--
ALTER TABLE `berita_db`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pimpinan_db`
--
ALTER TABLE `pimpinan_db`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `struktur_organisasi_db`
--
ALTER TABLE `struktur_organisasi_db`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `video_terkait_db`
--
ALTER TABLE `video_terkait_db`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
