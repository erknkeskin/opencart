-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: mysql
-- Üretim Zamanı: 12 Nis 2020, 19:10:00
-- Sunucu sürümü: 5.7.22
-- PHP Sürümü: 7.4.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `opcard_db1`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `ek_notice`
--

CREATE TABLE `ek_notice` (
  `notice_id` bigint(20) NOT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  `sort` int(11) NOT NULL DEFAULT '0',
  `date_added` datetime NOT NULL,
  `date_modified` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Tablo döküm verisi `ek_notice`
--

INSERT INTO `ek_notice` (`notice_id`, `status`, `sort`, `date_added`, `date_modified`) VALUES
(1, 1, 3, '2020-04-06 00:00:00', '2020-04-08 09:53:05'),
(2, 1, 2, '2020-04-06 22:25:26', '2020-04-06 23:00:06');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `ek_notice_description`
--

CREATE TABLE `ek_notice_description` (
  `notice_description_id` bigint(20) NOT NULL,
  `notice_id` bigint(20) NOT NULL,
  `language_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `summary` text NOT NULL,
  `description` longtext NOT NULL,
  `meta_title` varchar(160) NOT NULL,
  `meta_keyword` varchar(160) NOT NULL,
  `meta_description` varchar(160) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Tablo döküm verisi `ek_notice_description`
--

INSERT INTO `ek_notice_description` (`notice_description_id`, `notice_id`, `language_id`, `title`, `summary`, `description`, `meta_title`, `meta_keyword`, `meta_description`) VALUES
(8, 2, 1, 'yeni duyuru 2', 'yeni duyuru özet 2', '&lt;p&gt;asdasd 222&lt;/p&gt;\r\n', 'ddd', 'asd, asd', 'aaa'),
(10, 1, 1, 'New Notice 1', 'New Notice 1 Summary', '&lt;p&gt;New Notice 1 Description 222&lt;/p&gt;\r\n', '', '', '');

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `ek_notice`
--
ALTER TABLE `ek_notice`
  ADD PRIMARY KEY (`notice_id`);

--
-- Tablo için indeksler `ek_notice_description`
--
ALTER TABLE `ek_notice_description`
  ADD PRIMARY KEY (`notice_description_id`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `ek_notice`
--
ALTER TABLE `ek_notice`
  MODIFY `notice_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Tablo için AUTO_INCREMENT değeri `ek_notice_description`
--
ALTER TABLE `ek_notice_description`
  MODIFY `notice_description_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
