-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 03 May 2025, 22:25:46
-- Sunucu sürümü: 10.4.32-MariaDB
-- PHP Sürümü: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `kiosk`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `counter`
--

CREATE TABLE `counter` (
  `id` int(11) NOT NULL,
  `departman` varchar(50) DEFAULT NULL,
  `number` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `personel` varchar(50) NOT NULL,
  `status` enum('waiting','called','finished') NOT NULL DEFAULT 'waiting',
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `counter`
--

INSERT INTO `counter` (`id`, `departman`, `number`, `created_at`, `personel`, `status`, `updated_at`, `deleted_at`) VALUES
(1, 'hasar', 100, '2025-05-02 20:12:29', '', 'finished', '2025-05-03 22:04:59', NULL),
(2, 'mekanik', 300, '2025-05-02 20:18:01', 'Selçuk', 'finished', '2025-05-03 22:04:59', NULL),
(3, 'hasar', 101, '2025-05-02 20:24:05', '', 'finished', '2025-05-03 22:04:59', NULL),
(4, 'mekanik', 301, '2025-05-02 20:24:09', 'Selçuk', 'finished', '2025-05-03 22:04:59', NULL),
(5, 'mekanik', 302, '2025-05-02 21:24:29', 'Selçuk', 'finished', '2025-05-03 22:04:59', NULL),
(6, 'mekanik', 303, '2025-05-03 10:08:22', 'Selçuk', 'finished', '2025-05-03 22:04:59', NULL),
(7, 'hasar', 100, '2025-05-03 10:08:27', '', 'finished', '2025-05-03 22:04:59', NULL),
(8, 'hasar', 101, '2025-05-03 10:12:23', '', 'finished', '2025-05-03 22:04:59', NULL),
(9, 'hasar', 102, '2025-05-03 10:12:25', '', 'finished', '2025-05-03 22:04:59', NULL),
(10, 'hasar', 103, '2025-05-03 10:12:27', '', 'finished', '2025-05-03 22:04:59', NULL),
(11, 'hasar', 104, '2025-05-03 10:12:29', '', 'finished', '2025-05-03 22:04:59', NULL),
(12, 'hasar', 105, '2025-05-03 10:34:25', '', 'finished', '2025-05-03 22:04:59', NULL),
(13, 'hasar', 106, '2025-05-03 10:34:27', '', 'finished', '2025-05-03 22:04:59', NULL),
(14, 'hasar', 107, '2025-05-03 10:34:29', '', 'finished', '2025-05-03 22:04:59', NULL),
(15, 'hasar', 108, '2025-05-03 10:56:39', '', 'finished', '2025-05-03 22:04:59', NULL),
(16, 'hasar', 109, '2025-05-03 10:56:41', '', 'finished', '2025-05-03 22:04:59', NULL),
(17, 'hasar', 110, '2025-05-03 11:08:11', '', 'finished', '2025-05-03 22:04:59', NULL),
(18, 'hasar', 111, '2025-05-03 11:08:14', '', 'finished', '2025-05-03 22:04:59', NULL),
(19, 'hasar', 112, '2025-05-03 11:24:18', '', 'finished', '2025-05-03 22:04:59', NULL),
(20, 'hasar', 113, '2025-05-03 11:24:22', 'Ebru Yılmaz', 'finished', '2025-05-03 22:04:59', NULL),
(21, 'hasar', 114, '2025-05-03 17:47:56', 'Ebru Yılmaz', 'finished', '2025-05-03 22:04:59', NULL),
(22, 'mekanik', 304, '2025-05-03 17:54:44', 'Selçuk', 'finished', '2025-05-03 22:04:59', NULL),
(23, 'hasar', 115, '2025-05-03 17:54:47', 'Ebru Yılmaz', 'finished', '2025-05-03 22:04:59', NULL),
(24, 'onarim', 500, '2025-05-03 18:01:49', 'Mehmet Yılmaz', 'finished', '2025-05-03 22:04:59', NULL),
(25, 'onarim', 501, '2025-05-03 18:01:51', 'Mehmet Yılmaz', 'finished', '2025-05-03 22:04:59', NULL),
(26, 'onarim', 502, '2025-05-03 18:01:53', 'Mehmet Yılmaz', 'finished', '2025-05-03 22:04:59', NULL),
(27, 'onarim', 503, '2025-05-03 18:08:48', 'Mehmet Yılmaz', 'finished', '2025-05-03 22:04:59', NULL),
(28, 'mekanik', 305, '2025-05-03 18:08:50', 'Selçuk', 'finished', '2025-05-03 22:04:59', NULL),
(29, 'hasar', 116, '2025-05-03 18:08:52', 'Ebru Yılmaz', 'finished', '2025-05-03 22:04:59', NULL),
(30, 'hasar', 117, '2025-05-03 18:08:54', 'Ebru Yılmaz', 'finished', '2025-05-03 22:04:59', NULL),
(31, 'onarim', 504, '2025-05-03 18:09:09', 'Mehmet Yılmaz', 'finished', '2025-05-03 22:04:59', NULL),
(32, 'onarim', 505, '2025-05-03 18:09:11', 'Mehmet Yılmaz', 'finished', '2025-05-03 22:04:59', NULL),
(33, 'hasar', 118, '2025-05-03 18:09:23', 'Ebru Yılmaz', 'finished', '2025-05-03 22:04:59', NULL),
(34, 'hasar', 119, '2025-05-03 18:09:49', 'Ebru Yılmaz', 'finished', '2025-05-03 22:04:59', NULL),
(35, 'hasar', 120, '2025-05-03 18:10:10', 'Ebru Yılmaz', 'finished', '2025-05-03 22:04:59', NULL),
(36, 'mekanik', 306, '2025-05-03 18:10:34', 'Selçuk', 'finished', '2025-05-03 22:04:59', NULL),
(37, 'hasar', 121, '2025-05-03 18:19:27', 'Ebru Yılmaz', 'finished', '2025-05-03 22:04:59', NULL),
(38, 'hasar', 122, '2025-05-03 18:23:18', 'Ebru Yılmaz', 'finished', '2025-05-03 22:04:59', NULL),
(39, 'onarim', 506, '2025-05-03 18:30:30', 'Mehmet Yılmaz', 'finished', '2025-05-03 22:04:59', NULL),
(40, 'mekanik', 307, '2025-05-03 18:33:22', 'Selçuk', 'finished', '2025-05-03 22:04:59', NULL),
(41, 'hasar', 123, '2025-05-03 18:34:35', 'Ebru Yılmaz', 'finished', '2025-05-03 22:04:59', NULL),
(42, 'onarim', 507, '2025-05-03 18:35:38', 'Mehmet Yılmaz', 'finished', '2025-05-03 22:04:59', NULL),
(43, 'onarim', 508, '2025-05-03 18:35:40', 'Mehmet Yılmaz', 'finished', '2025-05-03 22:04:59', NULL),
(44, 'hasar', 124, '2025-05-03 18:36:36', 'Ebru Yılmaz', 'finished', '2025-05-03 22:04:59', NULL),
(45, 'mekanik', 308, '2025-05-03 18:36:38', 'Selçuk', 'finished', '2025-05-03 22:04:59', NULL),
(46, 'onarim', 509, '2025-05-03 18:36:40', 'Mehmet Yılmaz', 'finished', '2025-05-03 22:04:59', NULL),
(47, 'hasar', 125, '2025-05-03 18:37:01', 'Ebru Yılmaz', 'finished', '2025-05-03 22:04:59', NULL),
(48, 'hasar', 126, '2025-05-03 18:37:02', 'Ebru Yılmaz', 'finished', '2025-05-03 22:04:59', NULL),
(49, 'hasar', 127, '2025-05-03 18:37:04', 'Ebru Yılmaz', 'finished', '2025-05-03 22:04:59', NULL),
(50, 'hasar', 128, '2025-05-03 18:37:06', 'Ebru Yılmaz', 'finished', '2025-05-03 22:04:59', NULL),
(51, 'hasar', 129, '2025-05-03 18:37:08', 'Ebru Yılmaz', 'finished', '2025-05-03 22:04:59', NULL),
(52, 'hasar', 130, '2025-05-03 18:37:09', 'Ebru Yılmaz', 'finished', '2025-05-03 22:04:59', NULL),
(53, 'hasar', 131, '2025-05-03 18:37:11', 'Ebru Yılmaz', 'finished', '2025-05-03 22:04:59', NULL),
(54, 'hasar', 132, '2025-05-03 18:37:12', 'Ebru Yılmaz', 'finished', '2025-05-03 22:04:59', NULL),
(55, 'mekanik', 309, '2025-05-03 18:37:17', 'Selçuk', 'finished', '2025-05-03 22:04:59', NULL),
(56, 'mekanik', 310, '2025-05-03 18:37:19', 'Selçuk', 'finished', '2025-05-03 22:04:59', NULL),
(57, 'mekanik', 311, '2025-05-03 18:37:21', 'Selçuk', 'finished', '2025-05-03 22:04:59', NULL),
(58, 'mekanik', 312, '2025-05-03 18:37:22', 'Selçuk', 'finished', '2025-05-03 22:04:59', NULL),
(59, 'mekanik', 313, '2025-05-03 18:37:23', 'Selçuk', 'finished', '2025-05-03 22:04:59', NULL),
(60, 'mekanik', 314, '2025-05-03 18:37:25', 'Selçuk', 'called', '2025-05-03 22:04:59', NULL),
(61, 'onarim', 510, '2025-05-03 18:37:27', 'Mehmet Yılmaz', 'finished', '2025-05-03 22:04:59', NULL),
(62, 'onarim', 511, '2025-05-03 18:37:29', 'Mehmet Yılmaz', 'finished', '2025-05-03 22:04:59', NULL),
(63, 'onarim', 512, '2025-05-03 18:37:31', 'Mehmet Yılmaz', 'finished', '2025-05-03 22:04:59', NULL),
(64, 'onarim', 513, '2025-05-03 18:37:32', 'Mehmet Yılmaz', 'finished', '2025-05-03 22:04:59', NULL),
(65, 'onarim', 514, '2025-05-03 18:37:34', 'Mehmet Yılmaz', 'finished', '2025-05-03 22:04:59', NULL),
(66, 'onarim', 515, '2025-05-03 18:37:35', 'Mehmet Yılmaz', 'finished', '2025-05-03 22:04:59', NULL),
(67, 'onarim', 516, '2025-05-03 18:37:37', 'Mehmet Yılmaz', 'finished', '2025-05-03 22:04:59', NULL),
(68, 'onarim', 517, '2025-05-03 19:01:43', 'Mehmet Yılmaz', 'finished', '2025-05-03 22:04:59', NULL),
(69, 'onarim', 518, '2025-05-03 19:01:45', 'Mehmet Yılmaz', 'called', '2025-05-03 22:04:59', NULL),
(70, 'hasar', 133, '2025-05-03 20:03:52', 'Ebru Yılmaz', 'finished', '2025-05-03 23:04:30', NULL),
(71, 'hasar', 134, '2025-05-03 20:04:17', 'Ebru Yılmaz', 'called', '2025-05-03 23:04:30', NULL),
(72, 'hasar', 135, '2025-05-03 20:06:50', '', 'waiting', '2025-05-03 23:06:50', NULL),
(73, 'hasar', 136, '2025-05-03 20:07:12', '', 'waiting', '2025-05-03 23:07:12', NULL),
(74, 'hasar', 137, '2025-05-03 20:24:17', '', 'waiting', '2025-05-03 23:24:17', NULL);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `department`
--

CREATE TABLE `department` (
  `id` int(11) NOT NULL,
  `departman_adi` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `department`
--

INSERT INTO `department` (`id`, `departman_adi`) VALUES
(1, 'hasar'),
(2, 'mekanik'),
(3, 'onarim');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `personnel`
--

CREATE TABLE `personnel` (
  `id` int(11) NOT NULL,
  `personel_adi` varchar(255) NOT NULL,
  `section_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `personnel`
--

INSERT INTO `personnel` (`id`, `personel_adi`, `section_id`) VALUES
(1, 'Ebru Yılmaz', 1),
(3, 'Selçuk', 2),
(4, 'Ahmet ', 3),
(5, 'Mehmet Yılmaz', 3);

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `counter`
--
ALTER TABLE `counter`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD UNIQUE KEY `id_2` (`id`);

--
-- Tablo için indeksler `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `personnel`
--
ALTER TABLE `personnel`
  ADD PRIMARY KEY (`id`),
  ADD KEY `section_id` (`section_id`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `counter`
--
ALTER TABLE `counter`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- Tablo için AUTO_INCREMENT değeri `department`
--
ALTER TABLE `department`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Tablo için AUTO_INCREMENT değeri `personnel`
--
ALTER TABLE `personnel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Dökümü yapılmış tablolar için kısıtlamalar
--

--
-- Tablo kısıtlamaları `personnel`
--
ALTER TABLE `personnel`
  ADD CONSTRAINT `personnel_ibfk_1` FOREIGN KEY (`section_id`) REFERENCES `department` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
