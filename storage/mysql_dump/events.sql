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
DROP TABLE IF EXISTS `events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `events` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `concert_hall_id` int(11) unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `start` timestamp NULL DEFAULT NULL,
  `end` timestamp NULL DEFAULT NULL,
  `rate` int(11) NOT NULL,
  `released` enum('0','1') COLLATE utf8_unicode_ci DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `pk_concert_halls__id____events__concert_hall_id` FOREIGN KEY (`concert_hall_id`) REFERENCES `concert_halls` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of events
-- ----------------------------
BEGIN;
INSERT INTO `events` (`title`, `start`, `concert_hall_id`, `rate`, `released`, `created_at`, `updated_at`)
VALUES ('ГАСО России им. Светланова. Дирижер Дмитрий Крюков', '2018-02-08 19:00:00', 1, 1, '1', NOW(), NOW());
INSERT INTO `events` (`title`, `start`, `concert_hall_id`, `rate`, `released`,  `created_at`, `updated_at`)
VALUES ('Концерт Бенце Петер (фортепиано)', '2018-02-08 19:00:00', 2, 2, '1', NOW(), NOW());
INSERT INTO `events` (`title`, `start`, `concert_hall_id`, `rate`, `released`,  `created_at`, `updated_at`)
VALUES ('Государственный симфонический оркестр Республики Татарстан. Дирижер Александр Сладковский', '2018-02-07 19:00:00', 3, 3, '1', NOW(), NOW());
INSERT INTO `events` (`title`, `start`, `concert_hall_id`, `rate`, `released`,  `created_at`, `updated_at`)
VALUES ('Anna Rakita Project', '2018-02-08 19:00:00', 4, 4, '1', NOW(), NOW());
INSERT INTO `events` (`title`, `start`, `concert_hall_id`, `rate`, `released`,  `created_at`, `updated_at`)
VALUES ('Камерный оркестр Musica Viva. Дирижер и солист Александр Рудин (виолончель)', '2018-02-06 19:00:00', 1, 5, '1', NOW(), NOW());
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
