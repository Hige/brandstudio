SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT;
SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS;
SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION;
SET NAMES utf8mb4;
SET @OLD_TIME_ZONE=@@TIME_ZONE;
SET @@session.TIME_ZONE='+03:00';
SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO';
SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0;

SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for articles
-- ----------------------------
DROP TABLE IF EXISTS `concert_halls`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `concert_halls` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `metro` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `work_time` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `rate` int(11) NOT NULL,
  `released` enum('0','1') COLLATE utf8_unicode_ci DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of concert_halls
-- ----------------------------
BEGIN;
INSERT INTO `concert_halls` (`title`, `phone`, `address`, `metro`, `work_time`, `url`, `rate`, `released`, `created_at`, `updated_at`)
VALUES ('Концертный зал Чайковского Московской филармонии', '+7 (495) 232 04 00, +7 (495) 232 53 53, +7 (495) 699 22 62', 'г. Москва, Тверская, 31/4', 'м. Маяковская', 'пн-вс 9.00–22.00 (касса)', 'http://meloman.ru/', 0, '1', NOW(), NOW());
INSERT INTO `concert_halls` (`title`, `phone`, `address`, `metro`, `work_time`, `url`, `rate`, `released`,  `created_at`, `updated_at`)
VALUES ('Дом музыки', '+7 (495) 730 10 11, +7 (495) 730 18 60', 'г. Москва, Космодамианская наб., 52, стр. 8', 'м. Павелецкая', 'пн-вс 10.00–21.00 (касса) ', 'http://mmdm.ru/', 1, '1', NOW(), NOW());
INSERT INTO `concert_halls` (`title`, `phone`, `address`, `metro`, `work_time`, `url`, `rate`, `released`,  `created_at`, `updated_at`)
VALUES ('Большой зал Консерватории', '+7 (495) 629 94 01', 'г. Москва, ул. Б.Никитская, 13/6', 'м. Охотный Ряд, Библиотека им. Ленина', 'пн-вс 10.00–20.00 (касса)', 'http://mosconsv.ru/', 2, '1', NOW(), NOW());
INSERT INTO `concert_halls` (`title`, `phone`, `address`, `metro`, `work_time`, `url`, `rate`, `released`,  `created_at`, `updated_at`)
VALUES ('Союз композиторов', '+7 (495) 629 65 63', 'г. Москва, Брюсов пер., 8/10, стр. 2', 'м. Охотный Ряд, Тверская, Театральная, Площадь Революции, Пушкинская, Чеховская', 'пн-вс 12.00–0.00', 'http://ucclub.ru/', 3, '1', NOW(), NOW());
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
