-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 02 May 2025, 13:56:00
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
-- Veritabanı: `numarator`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `hasar`
--

CREATE TABLE `hasar` (
  `id` int(11) NOT NULL,
  `number` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `status` varchar(50) DEFAULT 'waiting',
  `personel` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `hasar`
--

INSERT INTO `hasar` (`id`, `number`, `created_at`, `status`, `personel`) VALUES
(4, 100, '2025-05-02 14:46:40', 'waiting', NULL),
(5, 101, '2025-05-02 14:46:45', 'waiting', NULL),
(6, 102, '2025-05-02 14:53:13', 'waiting', NULL),
(7, 103, '2025-05-02 14:53:25', 'waiting', NULL);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `mekanik`
--

CREATE TABLE `mekanik` (
  `id` int(11) NOT NULL,
  `number` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `status` varchar(50) DEFAULT 'bekliyor',
  `personel` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `mekanik`
--

INSERT INTO `mekanik` (`id`, `number`, `created_at`, `status`, `personel`) VALUES
(3, 200, '2025-05-02 14:46:50', 'bekliyor', NULL);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `onarim`
--

CREATE TABLE `onarim` (
  `id` int(11) NOT NULL,
  `number` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `status` varchar(50) DEFAULT 'bekliyor',
  `personel` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `onarim`
--

INSERT INTO `onarim` (`id`, `number`, `created_at`, `status`, `personel`) VALUES
(2, 300, '2025-05-02 14:47:42', 'bekliyor', NULL);

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `hasar`
--
ALTER TABLE `hasar`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `mekanik`
--
ALTER TABLE `mekanik`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `onarim`
--
ALTER TABLE `onarim`
  ADD PRIMARY KEY (`id`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `hasar`
--
ALTER TABLE `hasar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Tablo için AUTO_INCREMENT değeri `mekanik`
--
ALTER TABLE `mekanik`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Tablo için AUTO_INCREMENT değeri `onarim`
--
ALTER TABLE `onarim`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
