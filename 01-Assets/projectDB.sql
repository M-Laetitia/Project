-- --------------------------------------------------------
-- Hôte:                         127.0.0.1
-- Version du serveur:           8.0.30 - MySQL Community Server - GPL
-- SE du serveur:                Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Listage de la structure de la base pour project
CREATE DATABASE IF NOT EXISTS `project` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `project`;

-- Listage de la structure de table project. area
CREATE TABLE IF NOT EXISTS `area` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `detail` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `quote` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `nb_rooms` int NOT NULL,
  `type` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `access` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table project.area : ~15 rows (environ)
REPLACE INTO `area` (`id`, `name`, `slug`, `description`, `detail`, `quote`, `start_date`, `end_date`, `nb_rooms`, `type`, `status`, `access`) VALUES
	(1, 'Expo 01', 'expo01', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'Lorem Ipsn gravida nibh vel velit auctor aliquet. Aene sollic consequat ipsutis sem nibh id elit. Duis sed nibh vel a sit amet nibh vulputate. Lorem Ipsn vel velit auctor aliquet. Lorem Ipsn gravida nibh vel velit auctor aliquet. Aene sollic consequat ipsutis sem nibh id elit. Duis sed odio sit amet nibh vulputate. Lorem Ipsn gravida nibh vel melit auctor aliquet. Aene sollic consequat ipsutis sem nibh id elit. Duis sed odio sit amet nibh vulputate. Lorem Ipsn gravida nibh vel velit auct or aliquet. Aene sollic consequat ipsutis sem nibh id elit. Duis sed odio sit amet nibh vulputate. Duis sed nibh vel a sit amet nibh vulputate. Lorem Ipsn vel velit auctor aliquet. Lorem Ipsn gravida nibh vel velit auctor aliquet. Aene sollic consequat ipsutis sem nibh id elit. Aene sollic consequat ipsutis sem nibh id elit. Duis sed odio sit amet nibh vulputate. Duis sed nibh vel a sit amet nibh vulputate. Lorem Ipsn vel velit auctor aliquet. Lorem Ipsn gravida nibh vel velit auctor aliquet. Aene sollic consequat ipsutis sem nibh id elit.', 'Velit auctor aliquet. Aene sollic consequat ipsutis sem nibh id elit. Duis sed nibh vel a sit amet nibh', '2024-03-15 15:54:00', '2024-03-15 18:54:00', 4, 'EXPO', 'CLOSED', 'public'),
	(2, 'Expo 02', 'expo02', ' Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.', NULL, NULL, '2023-12-06 16:08:00', '2023-12-07 16:08:00', 10, 'EXPO', 'PENDING', 'public'),
	(3, 'Quattrocento art retrospective', 'quattrocento-art-retrospective', 'Discover masterpieces from the 15th century and explore the cultural significance of this influential period. Join us as we journey through history and celebrate the creativity that defined an unforgettable era of artistic brilliance.', 'Lorem Ipsn gravida nibh vel velit auctor aliquet. Aene sollic consequat ipsutis sem nibh id elit. Duis sed nibh vel a sit amet nibh vulputate. Lorem Ipsn vel velit auctor aliquet. Lorem Ipsn gravida nibh vel velit auctor aliquet. Aene sollic consequat ipsutis sem nibh id elit. Duis sed odio sit amet nibh vulputate. Lorem Ipsn gravida nibh vel melit auctor aliquet. Aene sollic consequat ipsutis sem nibh id elit. Duis sed odio sit amet nibh vulputate. Lorem Ipsn gravida nibh vel velit auct or aliquet. Aene sollic consequat ipsutis sem nibh id elit. Duis sed odio sit amet', 'Velit auctor aliquet. Aene sollic consequat ipsutis sem nibh id elit. Duis sed nibh vel a sit amet nibh', '2024-02-22 20:00:00', '2024-02-22 20:09:00', 12, 'EVENT', 'OPEN', 'private'),
	(4, 'Event 02', 'event-02', 'Vivamus in nibh condimentum, egestas purus id, pretium neque. In laoreet lectus ex, mattis fermentum leo accumsan et. Sed mi lacus, interdum sed suscipit sit amet,', NULL, NULL, '2024-02-06 16:10:05', '2024-02-07 16:10:06', 12, 'EVENT', 'ARCHIVED', 'public'),
	(8, 'Event 03', 'event-03', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam bibendum turpis sed efficitur blandit. Phasellus aliquet sem at euismod dignissim.', NULL, NULL, '2024-03-20 10:49:00', '2024-03-20 10:25:00', 10, 'EVENT', 'CLOSED', 'public'),
	(10, 'Event 05', 'event-05', 'Quisque dapibus arcu facilisis, vulputate ex non, faucibus purus. Vestibulum efficitur nunc nec ipsum volutpat interdum. Donec ultricies, nisl quis ornare sollicitudin,', NULL, NULL, '2024-12-16 17:26:09', '2024-12-16 17:26:10', 15, 'EVENT', 'PENDING', 'public'),
	(11, 'Quattrocento art retrospective', 'quattrocento-art-retrospective', 'uis ut urna quis turpis luctus vestibulum quis quis quam. Nullam vulputate feugiat fringilla. Etiam porttitor lectus porta erat vulputate consectetur', NULL, NULL, '2023-12-16 19:00:00', '2023-12-23 19:10:00', 10, 'EVENT', 'OPEN', 'public'),
	(12, 'Event10', 'event10', 'uis ut urna quis turpis luctus vestibulum quis quis quam. Nullam vulputate feugiat fringilla. Etiam porttitor lectus porta erat vulputate consectetur', NULL, NULL, '2023-12-13 19:15:00', '2023-12-21 19:15:00', 12, 'EVENT', 'ARCHIVED', 'public'),
	(13, 'expo 10', 'expo03', 'Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', NULL, NULL, '2023-12-18 00:06:00', '2023-12-30 00:06:00', 15, 'EXPO', 'ARCHIVED', 'public'),
	(15, 'teSstslug 02', 'tesstslug-02', 'errrrrrrrrrrrrerrrrrrrrrrrrrerrrrrrrrrrrrrerrrrrrrrrrrrr', NULL, NULL, '2023-12-24 12:15:00', '2023-12-28 12:15:00', 3, 'EVENT', 'ARCHIVED', 'public'),
	(16, 'event 30', 'event30', 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', NULL, NULL, '2025-12-23 23:15:17', '2025-12-27 23:15:20', 20, 'EVENT', 'ARCHIVED', 'public'),
	(17, 'testimage', 'testimage', 'azzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzz', NULL, NULL, '2024-03-09 19:21:00', '2024-03-31 19:21:00', 23, 'EVENT', 'OPEN', 'public'),
	(18, 'testimage', 'testimage', 'azzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzz', NULL, NULL, '2024-03-09 19:21:00', '2024-03-31 19:21:00', 23, 'EVENT', 'OPEN', 'public'),
	(19, 'test1121212', 'test1121212', 'azzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzz', NULL, NULL, '2024-04-05 19:28:00', '2024-04-12 19:29:00', 15, 'EVENT', 'OPEN', 'public'),
	(20, 'test220', 'test220', 'azzzzzzzzzzzzzzzzzzzzzzzzzzzzz', 'azzzzzzzzzzzzzzzzzzzzzzzzzzzzzazzzzzzzzzzzzzzzzzzzzzzzzzzzzzazzzzzzzzzzzzzzzzzzzzzzzzzzzzzazzzzzzzzzzzzzzzzzzzzzzzzzzzzz', 'azzzzzzzzzzzzzzzzzzzzzzzzzzzzz', '2024-03-24 08:01:00', '2024-04-02 08:02:00', 6, 'EVENT', 'OPEN', 'public');

-- Listage de la structure de table project. area_area_category
CREATE TABLE IF NOT EXISTS `area_area_category` (
  `area_id` int NOT NULL,
  `area_category_id` int NOT NULL,
  PRIMARY KEY (`area_id`,`area_category_id`),
  KEY `IDX_DC7F5F32BD0F409C` (`area_id`),
  KEY `IDX_DC7F5F32397D3792` (`area_category_id`),
  CONSTRAINT `FK_DC7F5F32397D3792` FOREIGN KEY (`area_category_id`) REFERENCES `area_category` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_DC7F5F32BD0F409C` FOREIGN KEY (`area_id`) REFERENCES `area` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table project.area_area_category : ~12 rows (environ)
REPLACE INTO `area_area_category` (`area_id`, `area_category_id`) VALUES
	(3, 1),
	(4, 2),
	(8, 2),
	(11, 2),
	(11, 3),
	(12, 1),
	(13, 1),
	(13, 2),
	(15, 2),
	(17, 2),
	(18, 2),
	(19, 2),
	(20, 2);

-- Listage de la structure de table project. area_category
CREATE TABLE IF NOT EXISTS `area_category` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table project.area_category : ~3 rows (environ)
REPLACE INTO `area_category` (`id`, `name`) VALUES
	(1, 'Panel discussion'),
	(2, 'Installation'),
	(3, 'Screening'),
	(5, 'Artist talk');

-- Listage de la structure de table project. area_participation
CREATE TABLE IF NOT EXISTS `area_participation` (
  `id` int NOT NULL AUTO_INCREMENT,
  `firstname` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `lastname` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `inscription_date` datetime NOT NULL,
  `area_id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_B269FA74BD0F409C` (`area_id`),
  KEY `IDX_B269FA74A76ED395` (`user_id`),
  CONSTRAINT `FK_B269FA74A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_B269FA74BD0F409C` FOREIGN KEY (`area_id`) REFERENCES `area` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table project.area_participation : ~10 rows (environ)
REPLACE INTO `area_participation` (`id`, `firstname`, `lastname`, `start_date`, `end_date`, `inscription_date`, `area_id`, `user_id`) VALUES
	(1, 'test', 'test', NULL, NULL, '2023-12-08 08:25:31', 11, 22),
	(4, '0202', '3023', NULL, NULL, '2023-12-09 16:13:37', 4, 19),
	(17, 'John', 'John', NULL, NULL, '2023-12-16 16:16:21', 1, 19),
	(20, 'Cath', 'Laurier', NULL, NULL, '2023-12-16 16:33:45', 2, 19),
	(23, 'Cath', 'Laurier', NULL, NULL, '2023-12-17 11:43:20', 3, 21),
	(24, 'Cath', 'Laurier', NULL, NULL, '2023-12-18 10:17:12', 1, 11),
	(25, 'audra', 'audra', NULL, NULL, '2024-03-09 13:43:49', 3, 18),
	(26, 'Cath', 'Laurier', NULL, NULL, '2024-03-31 14:06:10', 3, 11),
	(27, 'Cath', 'Laurier', NULL, NULL, '2024-03-31 14:06:21', 3, 14),
	(28, 'Cath', 'Laurier', NULL, NULL, '2024-03-31 14:06:31', 8, 8);

-- Listage de la structure de table project. contact
CREATE TABLE IF NOT EXISTS `contact` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_4C62E638A76ED395` (`user_id`),
  CONSTRAINT `FK_4C62E638A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table project.contact : ~6 rows (environ)
REPLACE INTO `contact` (`id`, `name`, `icon`, `url`, `user_id`) VALUES
	(1, 'Instagram', '<i class="fa-brands fa-instagram"></i>', 'https://www.instagram.com/audraauclair', 8),
	(2, 'Behance', '<i class="fa-brands fa-square-behance"></i>', 'https://www.behance.com/audraauclair', 8),
	(3, 'Twitter', '<i class="fa-brands fa-facebook"></i>', 'test@twitter.com', 21),
	(4, 'Twitter', '<i class="fa-brands fa-facebook"></i>', 'test@instagram.com', 21),
	(5, 'Dribbble', '<i class="fa-brands fa-dribbble"></i>', 'https://www.dribble.com/audraauclair', 22),
	(6, 'Twitter', '<i class="fa-brands fa-twitter"></i>', 'https://www.twitter.com/audraauclair', 8);

-- Listage de la structure de table project. doctrine_migration_versions
CREATE TABLE IF NOT EXISTS `doctrine_migration_versions` (
  `version` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- Listage des données de la table project.doctrine_migration_versions : ~14 rows (environ)
REPLACE INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
	('DoctrineMigrations\\Version20231128103716', '2023-11-28 10:38:01', 35),
	('DoctrineMigrations\\Version20231128130222', '2023-11-28 13:02:40', 28),
	('DoctrineMigrations\\Version20231128140112', '2023-11-28 14:59:14', 23),
	('DoctrineMigrations\\Version20231128141930', '2023-11-28 14:59:14', 5),
	('DoctrineMigrations\\Version20231128142027', '2023-11-28 14:59:14', 6),
	('DoctrineMigrations\\Version20231128142457', '2023-11-28 15:25:33', 21),
	('DoctrineMigrations\\Version20231128142645', '2023-11-28 15:25:33', 11),
	('DoctrineMigrations\\Version20231128204452', '2023-11-28 20:45:22', 78),
	('DoctrineMigrations\\Version20231130130604', '2023-11-30 13:06:15', 72),
	('DoctrineMigrations\\Version20231130131647', '2023-11-30 13:16:53', 9),
	('DoctrineMigrations\\Version20231204174731', '2023-12-04 17:47:39', 53),
	('DoctrineMigrations\\Version20231206140357', '2023-12-06 14:04:17', 75),
	('DoctrineMigrations\\Version20231207143339', '2023-12-07 14:33:52', 47),
	('DoctrineMigrations\\Version20231211095807', '2023-12-11 09:58:19', 36);

-- Listage de la structure de table project. exposition_proposal
CREATE TABLE IF NOT EXISTS `exposition_proposal` (
  `id` int NOT NULL AUTO_INCREMENT,
  `proposal_date` datetime NOT NULL,
  `status` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `area_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_31AD3D5A76ED395` (`user_id`),
  KEY `IDX_31AD3D5BD0F409C` (`area_id`),
  CONSTRAINT `FK_31AD3D5A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_31AD3D5BD0F409C` FOREIGN KEY (`area_id`) REFERENCES `area` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table project.exposition_proposal : ~4 rows (environ)
REPLACE INTO `exposition_proposal` (`id`, `proposal_date`, `status`, `user_id`, `area_id`) VALUES
	(7, '2023-12-06 15:32:25', 'pending', 7, 2),
	(14, '2023-12-17 00:56:01', NULL, 10, 1),
	(15, '2023-12-17 00:56:15', NULL, 7, 1),
	(25, '2023-12-17 01:45:18', 'pending', 8, 1);

-- Listage de la structure de table project. lesson
CREATE TABLE IF NOT EXISTS `lesson` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `lesson_category_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_F87474F3E39F76E9` (`lesson_category_id`),
  CONSTRAINT `FK_F87474F3E39F76E9` FOREIGN KEY (`lesson_category_id`) REFERENCES `lesson_category` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table project.lesson : ~3 rows (environ)
REPLACE INTO `lesson` (`id`, `name`, `description`, `lesson_category_id`) VALUES
	(1, 'Module Name', 'Egestas tellus rutrum tellus pellentesque eu tincidunt tortor. Massa ultricies mi quis hendrerit dolor magna eget. Magna fringilla urna porttitor rhoncus dolor purus non enim praesent. ', 1),
	(2, 'Module Name', ' Eget duis at tellus at urna. Bibendum neque egestas congue quisque egestas. Suspendisse ultrices gravida dictum fusce ut.', 2),
	(3, 'Module Name', 'Turpis egestas integer eget aliquet. Elit at imperdiet dui accumsan sit amet nulla facilisi morbi. Leo vel fringilla est ullamcorper eget nulla facilisi. ', 1),
	(4, 'Module Name', ' Eget duis at tellus at urna. Bibendum neque egestas congue quisque egestas. Suspendisse ultrices gravida dictum fusce ut.', 2);

-- Listage de la structure de table project. lesson_category
CREATE TABLE IF NOT EXISTS `lesson_category` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table project.lesson_category : ~2 rows (environ)
REPLACE INTO `lesson_category` (`id`, `name`) VALUES
	(1, 'category01'),
	(2, 'category 02');

-- Listage de la structure de table project. messenger_messages
CREATE TABLE IF NOT EXISTS `messenger_messages` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `body` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `headers` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue_name` varchar(190) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `available_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `delivered_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`id`),
  KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  KEY `IDX_75EA56E016BA31DB` (`delivered_at`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table project.messenger_messages : ~14 rows (environ)
REPLACE INTO `messenger_messages` (`id`, `body`, `headers`, `queue_name`, `created_at`, `available_at`, `delivered_at`) VALUES
	(1, 'O:36:\\"Symfony\\\\Component\\\\Messenger\\\\Envelope\\":2:{s:44:\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0stamps\\";a:1:{s:46:\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\";a:1:{i:0;O:46:\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\":1:{s:55:\\"\\0Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\0busName\\";s:21:\\"messenger.bus.default\\";}}}s:45:\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0message\\";O:51:\\"Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\":2:{s:60:\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0message\\";O:39:\\"Symfony\\\\Bridge\\\\Twig\\\\Mime\\\\TemplatedEmail\\":4:{i:0;s:41:\\"registration/confirmation_email.html.twig\\";i:1;N;i:2;a:3:{s:9:\\"signedUrl\\";s:167:\\"http://127.0.0.1:8000/verify/email?expires=1701179873&signature=roBRP%2F8h4Hygo9LtvTQlh6LcJZue3tqlIleZJg2Fsr8%3D&token=MMhuAWs1uuH3ry2PBhrTU41zyvqTvf1dYHTbU%2Facbl4%3D\\";s:19:\\"expiresAtMessageKey\\";s:26:\\"%count% hour|%count% hours\\";s:20:\\"expiresAtMessageData\\";a:1:{s:7:\\"%count%\\";i:1;}}i:3;a:6:{i:0;N;i:1;N;i:2;N;i:3;N;i:4;a:0:{}i:5;a:2:{i:0;O:37:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\":2:{s:46:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0headers\\";a:3:{s:4:\\"from\\";a:1:{i:0;O:47:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\":5:{s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\";s:4:\\"From\\";s:56:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\";i:76;s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\";N;s:53:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\";s:5:\\"utf-8\\";s:58:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\";a:1:{i:0;O:30:\\"Symfony\\\\Component\\\\Mime\\\\Address\\":2:{s:39:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\";s:17:\\"admin@exemple.com\\";s:36:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\";s:13:\\"Admin Website\\";}}}}s:2:\\"to\\";a:1:{i:0;O:47:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\":5:{s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\";s:2:\\"To\\";s:56:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\";i:76;s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\";N;s:53:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\";s:5:\\"utf-8\\";s:58:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\";a:1:{i:0;O:30:\\"Symfony\\\\Component\\\\Mime\\\\Address\\":2:{s:39:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\";s:16:\\"Lisa@exemple.com\\";s:36:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\";s:0:\\"\\";}}}}s:7:\\"subject\\";a:1:{i:0;O:48:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\":5:{s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\";s:7:\\"Subject\\";s:56:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\";i:76;s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\";N;s:53:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\";s:5:\\"utf-8\\";s:55:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\0value\\";s:25:\\"Please Confirm your Email\\";}}}s:49:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0lineLength\\";i:76;}i:1;N;}}}s:61:\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0envelope\\";N;}}', '[]', 'default', '2023-11-28 12:57:53', '2023-11-28 12:57:53', NULL),
	(2, 'O:36:\\"Symfony\\\\Component\\\\Messenger\\\\Envelope\\":2:{s:44:\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0stamps\\";a:1:{s:46:\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\";a:1:{i:0;O:46:\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\":1:{s:55:\\"\\0Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\0busName\\";s:21:\\"messenger.bus.default\\";}}}s:45:\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0message\\";O:51:\\"Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\":2:{s:60:\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0message\\";O:39:\\"Symfony\\\\Bridge\\\\Twig\\\\Mime\\\\TemplatedEmail\\":4:{i:0;s:41:\\"registration/confirmation_email.html.twig\\";i:1;N;i:2;a:3:{s:9:\\"signedUrl\\";s:173:\\"http://127.0.0.1:8000/verify/email?expires=1701180772&signature=%2Bv6Q4IngvLtA%2F99idCpHj%2BwMvbS6aexKio9SzxYa5iE%3D&token=jS%2F9PWHYKAhQUuaKsH9QUIc6WZVGtTFfiQrCC51Yk%2Bk%3D\\";s:19:\\"expiresAtMessageKey\\";s:26:\\"%count% hour|%count% hours\\";s:20:\\"expiresAtMessageData\\";a:1:{s:7:\\"%count%\\";i:1;}}i:3;a:6:{i:0;N;i:1;N;i:2;N;i:3;N;i:4;a:0:{}i:5;a:2:{i:0;O:37:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\":2:{s:46:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0headers\\";a:3:{s:4:\\"from\\";a:1:{i:0;O:47:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\":5:{s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\";s:4:\\"From\\";s:56:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\";i:76;s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\";N;s:53:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\";s:5:\\"utf-8\\";s:58:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\";a:1:{i:0;O:30:\\"Symfony\\\\Component\\\\Mime\\\\Address\\":2:{s:39:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\";s:17:\\"admin@exemple.com\\";s:36:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\";s:13:\\"Admin Website\\";}}}}s:2:\\"to\\";a:1:{i:0;O:47:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\":5:{s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\";s:2:\\"To\\";s:56:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\";i:76;s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\";N;s:53:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\";s:5:\\"utf-8\\";s:58:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\";a:1:{i:0;O:30:\\"Symfony\\\\Component\\\\Mime\\\\Address\\":2:{s:39:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\";s:18:\\"Hannah@exemple.com\\";s:36:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\";s:0:\\"\\";}}}}s:7:\\"subject\\";a:1:{i:0;O:48:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\":5:{s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\";s:7:\\"Subject\\";s:56:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\";i:76;s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\";N;s:53:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\";s:5:\\"utf-8\\";s:55:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\0value\\";s:25:\\"Please Confirm your Email\\";}}}s:49:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0lineLength\\";i:76;}i:1;N;}}}s:61:\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0envelope\\";N;}}', '[]', 'default', '2023-11-28 13:12:52', '2023-11-28 13:12:52', NULL),
	(3, 'O:36:\\"Symfony\\\\Component\\\\Messenger\\\\Envelope\\":2:{s:44:\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0stamps\\";a:1:{s:46:\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\";a:1:{i:0;O:46:\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\":1:{s:55:\\"\\0Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\0busName\\";s:21:\\"messenger.bus.default\\";}}}s:45:\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0message\\";O:51:\\"Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\":2:{s:60:\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0message\\";O:39:\\"Symfony\\\\Bridge\\\\Twig\\\\Mime\\\\TemplatedEmail\\":4:{i:0;s:41:\\"registration/confirmation_email.html.twig\\";i:1;N;i:2;a:3:{s:9:\\"signedUrl\\";s:171:\\"http://127.0.0.1:8000/verify/email?expires=1701181341&signature=glhgIdaZAOWHvyI2X%2F0oIl8ZSg4oGxqaPbEEe9EqkGg%3D&token=mMBMhHkfMGt5L41%2BwRxrPh%2BKL6gM2k%2Fr3TXus644IRs%3D\\";s:19:\\"expiresAtMessageKey\\";s:26:\\"%count% hour|%count% hours\\";s:20:\\"expiresAtMessageData\\";a:1:{s:7:\\"%count%\\";i:1;}}i:3;a:6:{i:0;N;i:1;N;i:2;N;i:3;N;i:4;a:0:{}i:5;a:2:{i:0;O:37:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\":2:{s:46:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0headers\\";a:3:{s:4:\\"from\\";a:1:{i:0;O:47:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\":5:{s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\";s:4:\\"From\\";s:56:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\";i:76;s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\";N;s:53:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\";s:5:\\"utf-8\\";s:58:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\";a:1:{i:0;O:30:\\"Symfony\\\\Component\\\\Mime\\\\Address\\":2:{s:39:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\";s:17:\\"admin@exemple.com\\";s:36:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\";s:13:\\"Admin Website\\";}}}}s:2:\\"to\\";a:1:{i:0;O:47:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\":5:{s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\";s:2:\\"To\\";s:56:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\";i:76;s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\";N;s:53:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\";s:5:\\"utf-8\\";s:58:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\";a:1:{i:0;O:30:\\"Symfony\\\\Component\\\\Mime\\\\Address\\":2:{s:39:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\";s:14:\\"01@exemple.com\\";s:36:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\";s:0:\\"\\";}}}}s:7:\\"subject\\";a:1:{i:0;O:48:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\":5:{s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\";s:7:\\"Subject\\";s:56:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\";i:76;s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\";N;s:53:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\";s:5:\\"utf-8\\";s:55:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\0value\\";s:25:\\"Please Confirm your Email\\";}}}s:49:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0lineLength\\";i:76;}i:1;N;}}}s:61:\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0envelope\\";N;}}', '[]', 'default', '2023-11-28 13:22:21', '2023-11-28 13:22:21', NULL),
	(4, 'O:36:\\"Symfony\\\\Component\\\\Messenger\\\\Envelope\\":2:{s:44:\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0stamps\\";a:1:{s:46:\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\";a:1:{i:0;O:46:\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\":1:{s:55:\\"\\0Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\0busName\\";s:21:\\"messenger.bus.default\\";}}}s:45:\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0message\\";O:51:\\"Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\":2:{s:60:\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0message\\";O:39:\\"Symfony\\\\Bridge\\\\Twig\\\\Mime\\\\TemplatedEmail\\":4:{i:0;s:41:\\"registration/confirmation_email.html.twig\\";i:1;N;i:2;a:3:{s:9:\\"signedUrl\\";s:165:\\"http://127.0.0.1:8000/verify/email?expires=1701181371&signature=DAGGcRW9erWR9SgGWenJV18CuKw9w0ZhkTYbhtcyxZo%3D&token=r2JMCHehT6uYFnkPXVbRzztVYwPbXja0a%2BvzGIbQ4bU%3D\\";s:19:\\"expiresAtMessageKey\\";s:26:\\"%count% hour|%count% hours\\";s:20:\\"expiresAtMessageData\\";a:1:{s:7:\\"%count%\\";i:1;}}i:3;a:6:{i:0;N;i:1;N;i:2;N;i:3;N;i:4;a:0:{}i:5;a:2:{i:0;O:37:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\":2:{s:46:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0headers\\";a:3:{s:4:\\"from\\";a:1:{i:0;O:47:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\":5:{s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\";s:4:\\"From\\";s:56:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\";i:76;s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\";N;s:53:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\";s:5:\\"utf-8\\";s:58:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\";a:1:{i:0;O:30:\\"Symfony\\\\Component\\\\Mime\\\\Address\\":2:{s:39:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\";s:17:\\"admin@exemple.com\\";s:36:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\";s:13:\\"Admin Website\\";}}}}s:2:\\"to\\";a:1:{i:0;O:47:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\":5:{s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\";s:2:\\"To\\";s:56:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\";i:76;s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\";N;s:53:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\";s:5:\\"utf-8\\";s:58:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\";a:1:{i:0;O:30:\\"Symfony\\\\Component\\\\Mime\\\\Address\\":2:{s:39:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\";s:14:\\"02@exemple.com\\";s:36:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\";s:0:\\"\\";}}}}s:7:\\"subject\\";a:1:{i:0;O:48:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\":5:{s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\";s:7:\\"Subject\\";s:56:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\";i:76;s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\";N;s:53:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\";s:5:\\"utf-8\\";s:55:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\0value\\";s:25:\\"Please Confirm your Email\\";}}}s:49:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0lineLength\\";i:76;}i:1;N;}}}s:61:\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0envelope\\";N;}}', '[]', 'default', '2023-11-28 13:22:51', '2023-11-28 13:22:51', NULL),
	(5, 'O:36:\\"Symfony\\\\Component\\\\Messenger\\\\Envelope\\":2:{s:44:\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0stamps\\";a:1:{s:46:\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\";a:1:{i:0;O:46:\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\":1:{s:55:\\"\\0Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\0busName\\";s:21:\\"messenger.bus.default\\";}}}s:45:\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0message\\";O:51:\\"Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\":2:{s:60:\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0message\\";O:39:\\"Symfony\\\\Bridge\\\\Twig\\\\Mime\\\\TemplatedEmail\\":4:{i:0;s:41:\\"registration/confirmation_email.html.twig\\";i:1;N;i:2;a:3:{s:9:\\"signedUrl\\";s:171:\\"http://127.0.0.1:8000/verify/email?expires=1701184410&signature=0g6zRKvVS1sIjQb4pB2%2B4OfJSos%2FLjxIqFHho5Fymqo%3D&token=k%2F765KENeZl2agXOb1K96SPDv%2FqCfP3Yc4SFhvJzGu0%3D\\";s:19:\\"expiresAtMessageKey\\";s:26:\\"%count% hour|%count% hours\\";s:20:\\"expiresAtMessageData\\";a:1:{s:7:\\"%count%\\";i:1;}}i:3;a:6:{i:0;N;i:1;N;i:2;N;i:3;N;i:4;a:0:{}i:5;a:2:{i:0;O:37:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\":2:{s:46:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0headers\\";a:3:{s:4:\\"from\\";a:1:{i:0;O:47:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\":5:{s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\";s:4:\\"From\\";s:56:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\";i:76;s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\";N;s:53:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\";s:5:\\"utf-8\\";s:58:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\";a:1:{i:0;O:30:\\"Symfony\\\\Component\\\\Mime\\\\Address\\":2:{s:39:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\";s:17:\\"admin@exemple.com\\";s:36:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\";s:13:\\"Admin Website\\";}}}}s:2:\\"to\\";a:1:{i:0;O:47:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\":5:{s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\";s:2:\\"To\\";s:56:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\";i:76;s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\";N;s:53:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\";s:5:\\"utf-8\\";s:58:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\";a:1:{i:0;O:30:\\"Symfony\\\\Component\\\\Mime\\\\Address\\":2:{s:39:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\";s:14:\\"03@exemple.com\\";s:36:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\";s:0:\\"\\";}}}}s:7:\\"subject\\";a:1:{i:0;O:48:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\":5:{s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\";s:7:\\"Subject\\";s:56:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\";i:76;s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\";N;s:53:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\";s:5:\\"utf-8\\";s:55:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\0value\\";s:25:\\"Please Confirm your Email\\";}}}s:49:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0lineLength\\";i:76;}i:1;N;}}}s:61:\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0envelope\\";N;}}', '[]', 'default', '2023-11-28 14:13:30', '2023-11-28 14:13:30', NULL),
	(6, 'O:36:\\"Symfony\\\\Component\\\\Messenger\\\\Envelope\\":2:{s:44:\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0stamps\\";a:1:{s:46:\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\";a:1:{i:0;O:46:\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\":1:{s:55:\\"\\0Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\0busName\\";s:21:\\"messenger.bus.default\\";}}}s:45:\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0message\\";O:51:\\"Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\":2:{s:60:\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0message\\";O:39:\\"Symfony\\\\Bridge\\\\Twig\\\\Mime\\\\TemplatedEmail\\":4:{i:0;s:41:\\"registration/confirmation_email.html.twig\\";i:1;N;i:2;a:3:{s:9:\\"signedUrl\\";s:169:\\"http://127.0.0.1:8000/verify/email?expires=1701184649&signature=2LEWy8hgNndPEVC4FRS8dU%2FdZOqseVc7zJBo2RO9xPw%3D&token=dg3fN67H%2BzvULc3PumwuvsIGdUHvt4weUqCnsHTn%2Bak%3D\\";s:19:\\"expiresAtMessageKey\\";s:26:\\"%count% hour|%count% hours\\";s:20:\\"expiresAtMessageData\\";a:1:{s:7:\\"%count%\\";i:1;}}i:3;a:6:{i:0;N;i:1;N;i:2;N;i:3;N;i:4;a:0:{}i:5;a:2:{i:0;O:37:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\":2:{s:46:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0headers\\";a:3:{s:4:\\"from\\";a:1:{i:0;O:47:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\":5:{s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\";s:4:\\"From\\";s:56:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\";i:76;s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\";N;s:53:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\";s:5:\\"utf-8\\";s:58:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\";a:1:{i:0;O:30:\\"Symfony\\\\Component\\\\Mime\\\\Address\\":2:{s:39:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\";s:17:\\"admin@exemple.com\\";s:36:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\";s:13:\\"Admin Website\\";}}}}s:2:\\"to\\";a:1:{i:0;O:47:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\":5:{s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\";s:2:\\"To\\";s:56:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\";i:76;s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\";N;s:53:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\";s:5:\\"utf-8\\";s:58:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\";a:1:{i:0;O:30:\\"Symfony\\\\Component\\\\Mime\\\\Address\\":2:{s:39:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\";s:14:\\"04@exemple.com\\";s:36:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\";s:0:\\"\\";}}}}s:7:\\"subject\\";a:1:{i:0;O:48:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\":5:{s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\";s:7:\\"Subject\\";s:56:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\";i:76;s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\";N;s:53:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\";s:5:\\"utf-8\\";s:55:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\0value\\";s:25:\\"Please Confirm your Email\\";}}}s:49:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0lineLength\\";i:76;}i:1;N;}}}s:61:\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0envelope\\";N;}}', '[]', 'default', '2023-11-28 14:17:29', '2023-11-28 14:17:29', NULL),
	(7, 'O:36:\\"Symfony\\\\Component\\\\Messenger\\\\Envelope\\":2:{s:44:\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0stamps\\";a:1:{s:46:\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\";a:1:{i:0;O:46:\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\":1:{s:55:\\"\\0Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\0busName\\";s:21:\\"messenger.bus.default\\";}}}s:45:\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0message\\";O:51:\\"Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\":2:{s:60:\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0message\\";O:39:\\"Symfony\\\\Bridge\\\\Twig\\\\Mime\\\\TemplatedEmail\\":4:{i:0;s:41:\\"registration/confirmation_email.html.twig\\";i:1;N;i:2;a:3:{s:9:\\"signedUrl\\";s:169:\\"http://127.0.0.1:8000/verify/email?expires=1701189061&signature=JadWWMM4QlKsiXpgWlCWAHNSQoXSJZIfM77NhXA3JSo%3D&token=RrXhyGnJX94eiuGBb3s%2B6p4LtJiQ34c65%2Bzl%2BIPY9HU%3D\\";s:19:\\"expiresAtMessageKey\\";s:26:\\"%count% hour|%count% hours\\";s:20:\\"expiresAtMessageData\\";a:1:{s:7:\\"%count%\\";i:1;}}i:3;a:6:{i:0;N;i:1;N;i:2;N;i:3;N;i:4;a:0:{}i:5;a:2:{i:0;O:37:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\":2:{s:46:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0headers\\";a:3:{s:4:\\"from\\";a:1:{i:0;O:47:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\":5:{s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\";s:4:\\"From\\";s:56:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\";i:76;s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\";N;s:53:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\";s:5:\\"utf-8\\";s:58:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\";a:1:{i:0;O:30:\\"Symfony\\\\Component\\\\Mime\\\\Address\\":2:{s:39:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\";s:17:\\"admin@exemple.com\\";s:36:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\";s:13:\\"Admin Website\\";}}}}s:2:\\"to\\";a:1:{i:0;O:47:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\":5:{s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\";s:2:\\"To\\";s:56:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\";i:76;s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\";N;s:53:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\";s:5:\\"utf-8\\";s:58:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\";a:1:{i:0;O:30:\\"Symfony\\\\Component\\\\Mime\\\\Address\\":2:{s:39:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\";s:14:\\"05@exemple.com\\";s:36:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\";s:0:\\"\\";}}}}s:7:\\"subject\\";a:1:{i:0;O:48:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\":5:{s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\";s:7:\\"Subject\\";s:56:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\";i:76;s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\";N;s:53:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\";s:5:\\"utf-8\\";s:55:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\0value\\";s:25:\\"Please Confirm your Email\\";}}}s:49:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0lineLength\\";i:76;}i:1;N;}}}s:61:\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0envelope\\";N;}}', '[]', 'default', '2023-11-28 15:31:01', '2023-11-28 15:31:01', NULL),
	(8, 'O:36:\\"Symfony\\\\Component\\\\Messenger\\\\Envelope\\":2:{s:44:\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0stamps\\";a:1:{s:46:\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\";a:1:{i:0;O:46:\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\":1:{s:55:\\"\\0Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\0busName\\";s:21:\\"messenger.bus.default\\";}}}s:45:\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0message\\";O:51:\\"Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\":2:{s:60:\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0message\\";O:39:\\"Symfony\\\\Bridge\\\\Twig\\\\Mime\\\\TemplatedEmail\\":4:{i:0;s:41:\\"registration/confirmation_email.html.twig\\";i:1;N;i:2;a:3:{s:9:\\"signedUrl\\";s:175:\\"http://127.0.0.1:8000/verify/email?expires=1701189193&signature=C5OZY8m98SbFfAMEs%2Fd%2B34o%2FPY9mnaPtf3eletxwgfA%3D&token=sArltWHx2o1mS49nKRV%2FYMyc1%2FgqUYQUw%2BwYEYZon5Y%3D\\";s:19:\\"expiresAtMessageKey\\";s:26:\\"%count% hour|%count% hours\\";s:20:\\"expiresAtMessageData\\";a:1:{s:7:\\"%count%\\";i:1;}}i:3;a:6:{i:0;N;i:1;N;i:2;N;i:3;N;i:4;a:0:{}i:5;a:2:{i:0;O:37:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\":2:{s:46:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0headers\\";a:3:{s:4:\\"from\\";a:1:{i:0;O:47:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\":5:{s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\";s:4:\\"From\\";s:56:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\";i:76;s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\";N;s:53:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\";s:5:\\"utf-8\\";s:58:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\";a:1:{i:0;O:30:\\"Symfony\\\\Component\\\\Mime\\\\Address\\":2:{s:39:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\";s:17:\\"admin@exemple.com\\";s:36:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\";s:13:\\"Admin Website\\";}}}}s:2:\\"to\\";a:1:{i:0;O:47:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\":5:{s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\";s:2:\\"To\\";s:56:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\";i:76;s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\";N;s:53:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\";s:5:\\"utf-8\\";s:58:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\";a:1:{i:0;O:30:\\"Symfony\\\\Component\\\\Mime\\\\Address\\":2:{s:39:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\";s:16:\\"Lisa@exemple.com\\";s:36:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\";s:0:\\"\\";}}}}s:7:\\"subject\\";a:1:{i:0;O:48:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\":5:{s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\";s:7:\\"Subject\\";s:56:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\";i:76;s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\";N;s:53:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\";s:5:\\"utf-8\\";s:55:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\0value\\";s:25:\\"Please Confirm your Email\\";}}}s:49:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0lineLength\\";i:76;}i:1;N;}}}s:61:\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0envelope\\";N;}}', '[]', 'default', '2023-11-28 15:33:13', '2023-11-28 15:33:13', NULL),
	(9, 'O:36:\\"Symfony\\\\Component\\\\Messenger\\\\Envelope\\":2:{s:44:\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0stamps\\";a:1:{s:46:\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\";a:1:{i:0;O:46:\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\":1:{s:55:\\"\\0Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\0busName\\";s:21:\\"messenger.bus.default\\";}}}s:45:\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0message\\";O:51:\\"Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\":2:{s:60:\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0message\\";O:39:\\"Symfony\\\\Bridge\\\\Twig\\\\Mime\\\\TemplatedEmail\\":4:{i:0;s:30:\\"reset_password/email.html.twig\\";i:1;N;i:2;a:1:{s:10:\\"resetToken\\";O:58:\\"SymfonyCasts\\\\Bundle\\\\ResetPassword\\\\Model\\\\ResetPasswordToken\\":4:{s:65:\\"\\0SymfonyCasts\\\\Bundle\\\\ResetPassword\\\\Model\\\\ResetPasswordToken\\0token\\";s:40:\\"0yCrz2ZvMYVONtWbtcx1xPR1r02XIqKyKG418lM7\\";s:69:\\"\\0SymfonyCasts\\\\Bundle\\\\ResetPassword\\\\Model\\\\ResetPasswordToken\\0expiresAt\\";O:17:\\"DateTimeImmutable\\":3:{s:4:\\"date\\";s:26:\\"2023-11-28 21:45:36.257217\\";s:13:\\"timezone_type\\";i:3;s:8:\\"timezone\\";s:3:\\"UTC\\";}s:71:\\"\\0SymfonyCasts\\\\Bundle\\\\ResetPassword\\\\Model\\\\ResetPasswordToken\\0generatedAt\\";i:1701204336;s:73:\\"\\0SymfonyCasts\\\\Bundle\\\\ResetPassword\\\\Model\\\\ResetPasswordToken\\0transInterval\\";i:1;}}i:3;a:6:{i:0;N;i:1;N;i:2;N;i:3;N;i:4;a:0:{}i:5;a:2:{i:0;O:37:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\":2:{s:46:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0headers\\";a:3:{s:4:\\"from\\";a:1:{i:0;O:47:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\":5:{s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\";s:4:\\"From\\";s:56:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\";i:76;s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\";N;s:53:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\";s:5:\\"utf-8\\";s:58:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\";a:1:{i:0;O:30:\\"Symfony\\\\Component\\\\Mime\\\\Address\\":2:{s:39:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\";s:18:\\"mailer@website.com\\";s:36:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\";s:13:\\"Acme Mail Bot\\";}}}}s:2:\\"to\\";a:1:{i:0;O:47:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\":5:{s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\";s:2:\\"To\\";s:56:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\";i:76;s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\";N;s:53:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\";s:5:\\"utf-8\\";s:58:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\";a:1:{i:0;O:30:\\"Symfony\\\\Component\\\\Mime\\\\Address\\":2:{s:39:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\";s:18:\\"Lisa02@exemple.com\\";s:36:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\";s:0:\\"\\";}}}}s:7:\\"subject\\";a:1:{i:0;O:48:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\":5:{s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\";s:7:\\"Subject\\";s:56:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\";i:76;s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\";N;s:53:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\";s:5:\\"utf-8\\";s:55:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\0value\\";s:27:\\"Your password reset request\\";}}}s:49:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0lineLength\\";i:76;}i:1;N;}}}s:61:\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0envelope\\";N;}}', '[]', 'default', '2023-11-28 20:45:36', '2023-11-28 20:45:36', NULL),
	(10, 'O:36:\\"Symfony\\\\Component\\\\Messenger\\\\Envelope\\":2:{s:44:\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0stamps\\";a:1:{s:46:\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\";a:1:{i:0;O:46:\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\":1:{s:55:\\"\\0Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\0busName\\";s:21:\\"messenger.bus.default\\";}}}s:45:\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0message\\";O:51:\\"Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\":2:{s:60:\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0message\\";O:39:\\"Symfony\\\\Bridge\\\\Twig\\\\Mime\\\\TemplatedEmail\\":4:{i:0;s:30:\\"reset_password/email.html.twig\\";i:1;N;i:2;a:1:{s:10:\\"resetToken\\";O:58:\\"SymfonyCasts\\\\Bundle\\\\ResetPassword\\\\Model\\\\ResetPasswordToken\\":4:{s:65:\\"\\0SymfonyCasts\\\\Bundle\\\\ResetPassword\\\\Model\\\\ResetPasswordToken\\0token\\";s:40:\\"sYqwagSlN2cXgUno24xRtd8d7SeRtxfwPVMNvNvj\\";s:69:\\"\\0SymfonyCasts\\\\Bundle\\\\ResetPassword\\\\Model\\\\ResetPasswordToken\\0expiresAt\\";O:17:\\"DateTimeImmutable\\":3:{s:4:\\"date\\";s:26:\\"2023-11-28 23:34:57.428408\\";s:13:\\"timezone_type\\";i:3;s:8:\\"timezone\\";s:3:\\"UTC\\";}s:71:\\"\\0SymfonyCasts\\\\Bundle\\\\ResetPassword\\\\Model\\\\ResetPasswordToken\\0generatedAt\\";i:1701210897;s:73:\\"\\0SymfonyCasts\\\\Bundle\\\\ResetPassword\\\\Model\\\\ResetPasswordToken\\0transInterval\\";i:1;}}i:3;a:6:{i:0;N;i:1;N;i:2;N;i:3;N;i:4;a:0:{}i:5;a:2:{i:0;O:37:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\":2:{s:46:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0headers\\";a:3:{s:4:\\"from\\";a:1:{i:0;O:47:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\":5:{s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\";s:4:\\"From\\";s:56:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\";i:76;s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\";N;s:53:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\";s:5:\\"utf-8\\";s:58:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\";a:1:{i:0;O:30:\\"Symfony\\\\Component\\\\Mime\\\\Address\\":2:{s:39:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\";s:18:\\"mailer@website.com\\";s:36:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\";s:13:\\"Acme Mail Bot\\";}}}}s:2:\\"to\\";a:1:{i:0;O:47:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\":5:{s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\";s:2:\\"To\\";s:56:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\";i:76;s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\";N;s:53:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\";s:5:\\"utf-8\\";s:58:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\";a:1:{i:0;O:30:\\"Symfony\\\\Component\\\\Mime\\\\Address\\":2:{s:39:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\";s:18:\\"Lisa02@exemple.com\\";s:36:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\";s:0:\\"\\";}}}}s:7:\\"subject\\";a:1:{i:0;O:48:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\":5:{s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\";s:7:\\"Subject\\";s:56:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\";i:76;s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\";N;s:53:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\";s:5:\\"utf-8\\";s:55:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\0value\\";s:27:\\"Your password reset request\\";}}}s:49:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0lineLength\\";i:76;}i:1;N;}}}s:61:\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0envelope\\";N;}}', '[]', 'default', '2023-11-28 22:34:57', '2023-11-28 22:34:57', NULL),
	(11, 'O:36:\\"Symfony\\\\Component\\\\Messenger\\\\Envelope\\":2:{s:44:\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0stamps\\";a:1:{s:46:\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\";a:1:{i:0;O:46:\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\":1:{s:55:\\"\\0Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\0busName\\";s:21:\\"messenger.bus.default\\";}}}s:45:\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0message\\";O:51:\\"Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\":2:{s:60:\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0message\\";O:39:\\"Symfony\\\\Bridge\\\\Twig\\\\Mime\\\\TemplatedEmail\\":4:{i:0;s:41:\\"registration/confirmation_email.html.twig\\";i:1;N;i:2;a:3:{s:9:\\"signedUrl\\";s:163:\\"http://127.0.0.1:8000/verify/email?expires=1701217695&signature=1PaMv7zgWs5HKlW0w0PXs3rdo9tWNFOMwlIu1M5Ssuc%3D&token=3NntQeTbAaQcLqjIdsnflhcdXdgo0PavT9PP9NvUh7s%3D\\";s:19:\\"expiresAtMessageKey\\";s:26:\\"%count% hour|%count% hours\\";s:20:\\"expiresAtMessageData\\";a:1:{s:7:\\"%count%\\";i:1;}}i:3;a:6:{i:0;N;i:1;N;i:2;N;i:3;N;i:4;a:0:{}i:5;a:2:{i:0;O:37:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\":2:{s:46:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0headers\\";a:3:{s:4:\\"from\\";a:1:{i:0;O:47:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\":5:{s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\";s:4:\\"From\\";s:56:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\";i:76;s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\";N;s:53:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\";s:5:\\"utf-8\\";s:58:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\";a:1:{i:0;O:30:\\"Symfony\\\\Component\\\\Mime\\\\Address\\":2:{s:39:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\";s:17:\\"admin@exemple.com\\";s:36:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\";s:13:\\"Admin Website\\";}}}}s:2:\\"to\\";a:1:{i:0;O:47:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\":5:{s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\";s:2:\\"To\\";s:56:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\";i:76;s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\";N;s:53:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\";s:5:\\"utf-8\\";s:58:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\";a:1:{i:0;O:30:\\"Symfony\\\\Component\\\\Mime\\\\Address\\":2:{s:39:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\";s:14:\\"02@exemple.com\\";s:36:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\";s:0:\\"\\";}}}}s:7:\\"subject\\";a:1:{i:0;O:48:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\":5:{s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\";s:7:\\"Subject\\";s:56:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\";i:76;s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\";N;s:53:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\";s:5:\\"utf-8\\";s:55:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\0value\\";s:25:\\"Please Confirm your Email\\";}}}s:49:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0lineLength\\";i:76;}i:1;N;}}}s:61:\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0envelope\\";N;}}', '[]', 'default', '2023-11-28 23:28:15', '2023-11-28 23:28:15', NULL),
	(12, 'O:36:\\"Symfony\\\\Component\\\\Messenger\\\\Envelope\\":2:{s:44:\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0stamps\\";a:1:{s:46:\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\";a:1:{i:0;O:46:\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\":1:{s:55:\\"\\0Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\0busName\\";s:21:\\"messenger.bus.default\\";}}}s:45:\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0message\\";O:51:\\"Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\":2:{s:60:\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0message\\";O:39:\\"Symfony\\\\Bridge\\\\Twig\\\\Mime\\\\TemplatedEmail\\":4:{i:0;s:41:\\"registration/confirmation_email.html.twig\\";i:1;N;i:2;a:3:{s:9:\\"signedUrl\\";s:177:\\"http://127.0.0.1:8000/verify/email?expires=1701218155&signature=NrOXasYXs%2BPx%2BBr2CnP%2FVmJhDLH6e1B8iFGe02seENU%3D&token=mr5%2FTJa2w0%2FH%2BWsYg4WoxLk3kEcujfPXHKbs66p%2B9bQ%3D\\";s:19:\\"expiresAtMessageKey\\";s:26:\\"%count% hour|%count% hours\\";s:20:\\"expiresAtMessageData\\";a:1:{s:7:\\"%count%\\";i:1;}}i:3;a:6:{i:0;N;i:1;N;i:2;N;i:3;N;i:4;a:0:{}i:5;a:2:{i:0;O:37:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\":2:{s:46:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0headers\\";a:3:{s:4:\\"from\\";a:1:{i:0;O:47:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\":5:{s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\";s:4:\\"From\\";s:56:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\";i:76;s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\";N;s:53:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\";s:5:\\"utf-8\\";s:58:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\";a:1:{i:0;O:30:\\"Symfony\\\\Component\\\\Mime\\\\Address\\":2:{s:39:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\";s:17:\\"admin@exemple.com\\";s:36:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\";s:13:\\"Admin Website\\";}}}}s:2:\\"to\\";a:1:{i:0;O:47:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\":5:{s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\";s:2:\\"To\\";s:56:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\";i:76;s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\";N;s:53:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\";s:5:\\"utf-8\\";s:58:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\";a:1:{i:0;O:30:\\"Symfony\\\\Component\\\\Mime\\\\Address\\":2:{s:39:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\";s:14:\\"04@exemple.com\\";s:36:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\";s:0:\\"\\";}}}}s:7:\\"subject\\";a:1:{i:0;O:48:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\":5:{s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\";s:7:\\"Subject\\";s:56:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\";i:76;s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\";N;s:53:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\";s:5:\\"utf-8\\";s:55:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\0value\\";s:25:\\"Please Confirm your Email\\";}}}s:49:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0lineLength\\";i:76;}i:1;N;}}}s:61:\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0envelope\\";N;}}', '[]', 'default', '2023-11-28 23:35:55', '2023-11-28 23:35:55', NULL),
	(13, 'O:36:\\"Symfony\\\\Component\\\\Messenger\\\\Envelope\\":2:{s:44:\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0stamps\\";a:1:{s:46:\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\";a:1:{i:0;O:46:\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\":1:{s:55:\\"\\0Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\0busName\\";s:21:\\"messenger.bus.default\\";}}}s:45:\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0message\\";O:51:\\"Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\":2:{s:60:\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0message\\";O:39:\\"Symfony\\\\Bridge\\\\Twig\\\\Mime\\\\TemplatedEmail\\":4:{i:0;s:30:\\"reset_password/email.html.twig\\";i:1;N;i:2;a:1:{s:10:\\"resetToken\\";O:58:\\"SymfonyCasts\\\\Bundle\\\\ResetPassword\\\\Model\\\\ResetPasswordToken\\":4:{s:65:\\"\\0SymfonyCasts\\\\Bundle\\\\ResetPassword\\\\Model\\\\ResetPasswordToken\\0token\\";s:40:\\"EFncbJpeySXXOfGsRBBTTlMlJubckoExW9CmEbpn\\";s:69:\\"\\0SymfonyCasts\\\\Bundle\\\\ResetPassword\\\\Model\\\\ResetPasswordToken\\0expiresAt\\";O:17:\\"DateTimeImmutable\\":3:{s:4:\\"date\\";s:26:\\"2023-11-29 10:20:52.879191\\";s:13:\\"timezone_type\\";i:3;s:8:\\"timezone\\";s:3:\\"UTC\\";}s:71:\\"\\0SymfonyCasts\\\\Bundle\\\\ResetPassword\\\\Model\\\\ResetPasswordToken\\0generatedAt\\";i:1701249652;s:73:\\"\\0SymfonyCasts\\\\Bundle\\\\ResetPassword\\\\Model\\\\ResetPasswordToken\\0transInterval\\";i:1;}}i:3;a:6:{i:0;N;i:1;N;i:2;N;i:3;N;i:4;a:0:{}i:5;a:2:{i:0;O:37:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\":2:{s:46:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0headers\\";a:3:{s:4:\\"from\\";a:1:{i:0;O:47:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\":5:{s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\";s:4:\\"From\\";s:56:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\";i:76;s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\";N;s:53:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\";s:5:\\"utf-8\\";s:58:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\";a:1:{i:0;O:30:\\"Symfony\\\\Component\\\\Mime\\\\Address\\":2:{s:39:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\";s:18:\\"mailer@website.com\\";s:36:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\";s:13:\\"Acme Mail Bot\\";}}}}s:2:\\"to\\";a:1:{i:0;O:47:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\":5:{s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\";s:2:\\"To\\";s:56:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\";i:76;s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\";N;s:53:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\";s:5:\\"utf-8\\";s:58:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\";a:1:{i:0;O:30:\\"Symfony\\\\Component\\\\Mime\\\\Address\\":2:{s:39:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\";s:18:\\"Lisa02@exemple.com\\";s:36:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\";s:0:\\"\\";}}}}s:7:\\"subject\\";a:1:{i:0;O:48:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\":5:{s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\";s:7:\\"Subject\\";s:56:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\";i:76;s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\";N;s:53:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\";s:5:\\"utf-8\\";s:55:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\0value\\";s:27:\\"Your password reset request\\";}}}s:49:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0lineLength\\";i:76;}i:1;N;}}}s:61:\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0envelope\\";N;}}', '[]', 'default', '2023-11-29 09:20:53', '2023-11-29 09:20:53', NULL),
	(14, 'O:36:\\"Symfony\\\\Component\\\\Messenger\\\\Envelope\\":2:{s:44:\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0stamps\\";a:1:{s:46:\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\";a:1:{i:0;O:46:\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\":1:{s:55:\\"\\0Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\0busName\\";s:21:\\"messenger.bus.default\\";}}}s:45:\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0message\\";O:51:\\"Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\":2:{s:60:\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0message\\";O:39:\\"Symfony\\\\Bridge\\\\Twig\\\\Mime\\\\TemplatedEmail\\":4:{i:0;s:41:\\"registration/confirmation_email.html.twig\\";i:1;N;i:2;a:3:{s:9:\\"signedUrl\\";s:167:\\"http://127.0.0.1:8000/verify/email?expires=1701253595&signature=b9TMxZgMtY6GVncIUBwL9jchofXL0XUZ2GXtU6zCpNI%3D&token=witsJt824HAWgV0OyxwBoI7CiCpzeZA3avXD5f%2B1Z%2Bw%3D\\";s:19:\\"expiresAtMessageKey\\";s:26:\\"%count% hour|%count% hours\\";s:20:\\"expiresAtMessageData\\";a:1:{s:7:\\"%count%\\";i:1;}}i:3;a:6:{i:0;N;i:1;N;i:2;N;i:3;N;i:4;a:0:{}i:5;a:2:{i:0;O:37:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\":2:{s:46:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0headers\\";a:3:{s:4:\\"from\\";a:1:{i:0;O:47:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\":5:{s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\";s:4:\\"From\\";s:56:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\";i:76;s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\";N;s:53:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\";s:5:\\"utf-8\\";s:58:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\";a:1:{i:0;O:30:\\"Symfony\\\\Component\\\\Mime\\\\Address\\":2:{s:39:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\";s:17:\\"admin@exemple.com\\";s:36:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\";s:13:\\"Admin Website\\";}}}}s:2:\\"to\\";a:1:{i:0;O:47:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\":5:{s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\";s:2:\\"To\\";s:56:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\";i:76;s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\";N;s:53:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\";s:5:\\"utf-8\\";s:58:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\";a:1:{i:0;O:30:\\"Symfony\\\\Component\\\\Mime\\\\Address\\":2:{s:39:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\";s:14:\\"01@exemple.com\\";s:36:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\";s:0:\\"\\";}}}}s:7:\\"subject\\";a:1:{i:0;O:48:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\":5:{s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\";s:7:\\"Subject\\";s:56:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\";i:76;s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\";N;s:53:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\";s:5:\\"utf-8\\";s:55:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\0value\\";s:25:\\"Please Confirm your Email\\";}}}s:49:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0lineLength\\";i:76;}i:1;N;}}}s:61:\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0envelope\\";N;}}', '[]', 'default', '2023-11-29 09:26:35', '2023-11-29 09:26:35', NULL);

-- Listage de la structure de table project. picture
CREATE TABLE IF NOT EXISTS `picture` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `alt_description` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `is_selected` tinyint(1) DEFAULT NULL,
  `area_id` int DEFAULT NULL,
  `studio_id` int DEFAULT NULL,
  `workshop_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_16DB4F89A76ED395` (`user_id`),
  KEY `IDX_16DB4F89BD0F409C` (`area_id`),
  KEY `IDX_16DB4F89446F285F` (`studio_id`),
  KEY `IDX_16DB4F891FDCE57C` (`workshop_id`),
  CONSTRAINT `FK_16DB4F891FDCE57C` FOREIGN KEY (`workshop_id`) REFERENCES `workshop` (`id`),
  CONSTRAINT `FK_16DB4F89446F285F` FOREIGN KEY (`studio_id`) REFERENCES `studio` (`id`),
  CONSTRAINT `FK_16DB4F89A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_16DB4F89BD0F409C` FOREIGN KEY (`area_id`) REFERENCES `area` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=68 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table project.picture : ~19 rows (environ)
REPLACE INTO `picture` (`id`, `title`, `path`, `alt_description`, `type`, `user_id`, `is_selected`, `area_id`, `studio_id`, `workshop_id`) VALUES
	(33, 'zeezezez', '9dddfbc1ade9a1a9d74739fa7ce54d4b.jpg', 'eezzeezezzze', 'banner', 19, NULL, NULL, NULL, NULL),
	(38, 'aaaaaaaa', '84589384a2766ed0ead4af48a4c99c16.jpg', 'eezzeezezzze', 'work', 19, NULL, NULL, NULL, NULL),
	(44, 'aaaaaaaa', '5165187b7a3f2020a03f560f966e0a98.jpg', 'eezzeezezzze', 'work', 19, NULL, NULL, NULL, NULL),
	(45, 'bbbb6666', '080923a66169dbd79bb882bb4f90d65a.webp', 'aaaaaannnnnnn', 'banner', 8, 0, NULL, NULL, NULL),
	(47, 'Vertige', '3762aa46e1f2e47c7c6c2e5572793a69.webp', 'Painting of a woman with green skin and three red demon birds around her.', 'work', 8, 0, NULL, NULL, NULL),
	(48, 'In Disguise', '45c6cb6f5f11307813f82c4f1be6b611.webp', 'Painting of a portrait of a female demon with two pink pigtails.', 'work', 8, 1, NULL, NULL, NULL),
	(49, 'Blossom', '9ae8bd3821888e44e06897584044a2d3.webp', 'Painting of a woman with a red cape and flowers.', 'work', 8, 0, NULL, NULL, NULL),
	(50, 'Little witch', 'a36bd41b21eba83732e7b632745b24ba.webp', 'description', 'work', 8, 0, NULL, NULL, NULL),
	(51, 'Past Memories', 'cdc02e2367126fb1bdf0579dde839a3c.webp', 'descrption', 'work', 8, 0, NULL, NULL, NULL),
	(52, 'Envol', '7cd353d4dfb494d3f0c4dcc2e0745b08.webp', 'description', 'work', 8, 0, NULL, NULL, NULL),
	(53, 'Regret', '88b25aa06df98c8ca9c00a61d656a060.webp', 'description', 'work', 8, 0, NULL, NULL, NULL),
	(54, 'Murmure', 'bd878c2d4b9c8abd9dd998351055da10.webp', 'description', 'work', 8, 0, NULL, NULL, NULL),
	(55, 'Puppet', '4aa7f4672aa08e986c70c7f039947216.webp', 'description', 'work', 8, 0, NULL, NULL, NULL),
	(56, 'Ghost', '25ca203c9a9224b0d406a8c790eba075.webp', 'description', 'work', 8, 0, NULL, NULL, NULL),
	(57, 'Earth', 'fd34f797b7c139e935f5456499f4896f.webp', 'description', 'work', 8, 0, NULL, NULL, NULL),
	(58, 'Renaissance', 'ee89ace162fc06434e19e00f623f1942.webp', 'description', 'work', 8, 0, NULL, NULL, NULL),
	(64, 'bbbbbbbb', '04d017ba0495119ebb5eb1d0e6c2f319.webp', 'bbbbbbbbb', 'banner', NULL, NULL, 20, NULL, NULL),
	(65, 'aaaaa', '5aa381cdb872deee3a3c507c0f225329.webp', 'aaaaaaaaaaaa', 'preview', NULL, NULL, 20, NULL, NULL),
	(67, 'aaaaa', '2b16053a0734a46fc98bd18a4f9689b7.webp', 'aaaaaaa', 'picture', NULL, NULL, 20, NULL, NULL);

-- Listage de la structure de table project. programme
CREATE TABLE IF NOT EXISTS `programme` (
  `id` int NOT NULL AUTO_INCREMENT,
  `workshop_id` int NOT NULL,
  `lesson_id` int NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_3DDCB9FF1FDCE57C` (`workshop_id`),
  KEY `IDX_3DDCB9FFCDF80196` (`lesson_id`),
  CONSTRAINT `FK_3DDCB9FF1FDCE57C` FOREIGN KEY (`workshop_id`) REFERENCES `workshop` (`id`),
  CONSTRAINT `FK_3DDCB9FFCDF80196` FOREIGN KEY (`lesson_id`) REFERENCES `lesson` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table project.programme : ~5 rows (environ)
REPLACE INTO `programme` (`id`, `workshop_id`, `lesson_id`, `start_date`, `end_date`) VALUES
	(3, 2, 1, '2024-03-05 10:00:00', '2024-03-05 12:15:00'),
	(4, 2, 2, '2024-03-05 14:00:00', '2024-03-05 16:00:00'),
	(5, 2, 3, '2024-03-06 15:00:00', '2024-03-06 18:00:00'),
	(6, 2, 4, '2024-03-07 09:00:00', '2024-03-07 11:00:00'),
	(7, 2, 2, '2024-03-05 19:23:53', '2024-03-05 19:23:53');

-- Listage de la structure de table project. reset_password_request
CREATE TABLE IF NOT EXISTS `reset_password_request` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `selector` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `hashed_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `requested_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `expires_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`id`),
  KEY `IDX_7CE748AA76ED395` (`user_id`),
  CONSTRAINT `FK_7CE748AA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table project.reset_password_request : ~0 rows (environ)
REPLACE INTO `reset_password_request` (`id`, `user_id`, `selector`, `hashed_token`, `requested_at`, `expires_at`) VALUES
	(7, 8, 'PJKIPDSiGdlcolQAgRIN', '+zHao2AFBpPjpWvSOwRna5UUyumUuPF2EOdzqvLBPvA=', '2023-12-06 12:58:36', '2023-12-06 13:58:36');

-- Listage de la structure de table project. studio
CREATE TABLE IF NOT EXISTS `studio` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `detail` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `picture` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nb_rooms` int NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `equipment` json DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table project.studio : ~4 rows (environ)
REPLACE INTO `studio` (`id`, `name`, `description`, `detail`, `picture`, `nb_rooms`, `slug`, `title`, `equipment`) VALUES
	(1, 'Studio Calliope', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', NULL, 5, 'studio-calliope', 'incididunt ut labore et dolore magna', '["1", "2", "3"]'),
	(2, 'Studio 02 ', 'Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', NULL, NULL, 8, 'studio02', 'incididunt ut labore et dolore magna', NULL),
	(3, 'Studio 03', 'Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. ', NULL, NULL, 10, 'studio03', 'incididunt ut labore et dolore magna', NULL),
	(4, 'Studio Thalie', 'lalalalalala', NULL, NULL, 5, 'studio-thalie', 'incididunt ut labore et dolore magna', NULL);

-- Listage de la structure de table project. subscription
CREATE TABLE IF NOT EXISTS `subscription` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `subscription_type_id` int NOT NULL,
  `payment_date` datetime NOT NULL,
  `infos_user` json NOT NULL,
  `infos_subscription` json NOT NULL,
  `total` decimal(5,2) NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_A3C664D3A76ED395` (`user_id`),
  KEY `IDX_A3C664D3B6596C08` (`subscription_type_id`),
  CONSTRAINT `FK_A3C664D3A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_A3C664D3B6596C08` FOREIGN KEY (`subscription_type_id`) REFERENCES `subscription_type` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=88 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table project.subscription : ~6 rows (environ)
REPLACE INTO `subscription` (`id`, `user_id`, `subscription_type_id`, `payment_date`, `infos_user`, `infos_subscription`, `total`, `is_active`) VALUES
	(1, 21, 1, '2024-03-01 18:17:06', '{"address": "20 rue des Peupliers 67200 Strasbourg", "lastname": "Kit", "firstname": "Cath"}', '{"name": "Type 01", "price": "30.00", "duration": 30}', 30.00, 0),
	(3, 19, 1, '2021-12-22 02:56:46', '{"address": "20 rue des Peupliers 67200 Strasbourg", "lastname": "Kit", "firstname": "Cath"}', '{"name": "Type 01", "price": "30.00", "duration": 30}', 30.00, 0),
	(4, 19, 1, '2023-12-22 02:56:46', '{"address": "20 rue des Peupliers 67200 Strasbourg", "lastname": "Kit", "firstname": "Cath"}', '{"name": "Type 02", "price": "60.00", "duration": 60}', 60.00, 0),
	(83, 8, 1, '2024-03-21 12:13:06', '{"address": "zzaazaz", "lastname": "aaz", "firstname": "azzaaz"}', '{"name": "Type 01", "price": "30.00", "duration": 30}', 30.00, 0),
	(84, 8, 2, '2024-03-21 13:48:43', '{"address": null, "lastName": null, "firstName": null}', '{"name": "Type 02", "price": "75.00", "duration": 90}', 75.00, 0);

-- Listage de la structure de table project. subscription_type
CREATE TABLE IF NOT EXISTS `subscription_type` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(5,2) NOT NULL,
  `duration` int NOT NULL,
  `due_date` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table project.subscription_type : ~3 rows (environ)
REPLACE INTO `subscription_type` (`id`, `name`, `price`, `duration`, `due_date`, `slug`) VALUES
	(1, 'Type 01', 30.00, 30, 'month', 'type01'),
	(2, 'Type 02', 75.00, 90, 'trimester', 'type02'),
	(3, 'Type 03', 280.00, 365, 'year', 'type03');

-- Listage de la structure de table project. timeslot
CREATE TABLE IF NOT EXISTS `timeslot` (
  `id` int NOT NULL AUTO_INCREMENT,
  `date` date DEFAULT NULL,
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `studio_id` int DEFAULT NULL,
  `user_id` int NOT NULL,
  `time_slot_availability_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_3BE452F7446F285F` (`studio_id`),
  KEY `IDX_3BE452F7A76ED395` (`user_id`),
  KEY `IDX_3BE452F7B18451A5` (`time_slot_availability_id`),
  CONSTRAINT `FK_3BE452F7446F285F` FOREIGN KEY (`studio_id`) REFERENCES `studio` (`id`),
  CONSTRAINT `FK_3BE452F7A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_3BE452F7B18451A5` FOREIGN KEY (`time_slot_availability_id`) REFERENCES `time_slot_availability` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table project.timeslot : ~9 rows (environ)
REPLACE INTO `timeslot` (`id`, `date`, `start_date`, `end_date`, `studio_id`, `user_id`, `time_slot_availability_id`) VALUES
	(1, '2024-05-15', '2024-02-12 10:00:00', '2024-02-12 11:00:00', 2, 11, 1),
	(3, '2023-12-12', '2023-12-12 09:00:35', '2023-12-12 10:00:36', 1, 11, 2),
	(5, '2024-02-14', '2024-02-14 14:39:32', '2024-02-14 15:39:40', 3, 12, 1),
	(6, '2024-02-15', '2024-02-15 10:00:00', '2024-02-15 11:00:00', 2, 11, 3),
	(7, '2024-02-15', '2024-02-15 16:00:00', '2024-02-15 17:00:00', 2, 10, 3),
	(8, '2024-03-13', '2024-03-13 09:00:00', '2024-03-13 10:00:00', 3, 8, 2),
	(9, '2024-01-30', '2024-01-30 09:00:00', '2024-01-30 10:00:00', 2, 8, 2),
	(10, '2024-04-04', '2024-04-04 09:00:00', '2024-04-04 10:00:00', 1, 8, 2),
	(11, '2024-05-28', '2024-05-28 10:00:00', '2024-05-28 11:00:00', 1, 8, 3);

-- Listage de la structure de table project. time_slot_availability
CREATE TABLE IF NOT EXISTS `time_slot_availability` (
  `id` int NOT NULL AUTO_INCREMENT,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table project.time_slot_availability : ~4 rows (environ)
REPLACE INTO `time_slot_availability` (`id`, `start_time`, `end_time`) VALUES
	(1, '08:00:00', '09:00:00'),
	(2, '09:00:00', '10:00:00'),
	(3, '10:00:00', '11:00:00'),
	(4, '11:00:00', '12:00:00'),
	(5, '12:00:00', '13:00:00');

-- Listage de la structure de table project. user
CREATE TABLE IF NOT EXISTS `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` json NOT NULL,
  `is_verified` tinyint(1) NOT NULL,
  `avatar` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `registration_date` datetime DEFAULT NULL,
  `last_login_date` datetime DEFAULT NULL,
  `last_profil_edit_date` datetime DEFAULT NULL,
  `artist_infos` json DEFAULT NULL,
  `is_published` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table project.user : ~13 rows (environ)
REPLACE INTO `user` (`id`, `email`, `username`, `slug`, `password`, `roles`, `is_verified`, `avatar`, `registration_date`, `last_login_date`, `last_profil_edit_date`, `artist_infos`, `is_published`) VALUES
	(7, '05@exemple.com', 'test05', 'test05', '$2y$13$JLmhaHe/j7US1RkvTZUi0uK9UUHnvl81GhrIBUsftIp7fwzPc3IE.', '["ROLE_USER", "ROLE_ARTIST"]', 0, NULL, '2023-11-29 00:28:28', '2023-12-06 17:52:56', NULL, '{"address": {"city": "Strasbourg", "street": "13 rue des cèdres", "country": "France", "postalCode": "67200"}, "category": "Illustration", "emailPro": "CathCreator@example.com", "artistName": "John Creation", "discipline": "Handcrafted Wood Artisan"}', 0),
	(8, 'cath@exemple.com', 'Audra Auclair', 'cath', '$2y$13$Ug3Z3HtKEEnoNZycntkXve85NQ.tuqMvZERCdU3DnAp5jkhCynXEK', '["ROLE_USER", "ROLE_ADMIN", "ROLE_ARTIST", "ROLE_SUPERVISOR"]', 1, '65663409444ba.jpg', '2023-10-29 09:37:39', '2024-04-02 14:49:24', NULL, '{"bio": "Lorem Ipsn gravida nibh vel velit auctor aliquet. Aene sollic consequat ipsutis sem nibh id elit. Duis sed nibh vel a sit amet nibh vulputate. Lorem Ipsn vel velit auctor aliquet. Lorem Ipsn gravida nibh vel velit auctor aliquet. Aene sollic consequat ipsutis sem nibh id elit. Duis sed odio sit amet nibh vulputate. Lorem Ipsn gravida nibh vel melit auctor aliquet. Aene sollic consequat ipsutis sem nibh id elit. Duis sed odio sit amet nibh vulputate. Lorem Ipsn gravida nibh vel velit auct or aliquet. Aene sollic consequat ipsutis sem nibh id elit. Duis sed odio sit amet nibh vulputate. Duis sed nibh vel a sit amet nibh vulputate. Lorem Ipsn vel velit auctor aliquet. Lorem Ipsn gravida nibh vel velit auctor aliquet. Aene sollic consequat ipsutis sem nibh id elit.", "city": "Strasbourg", "shop": null, "quote": "Velit auctor aliquet. Aene sollic consequat ipsutis sem nibh id elit. Duis sed nibh vel a sit amet nibh", "street": "12 rue des machin", "address": {"city": "Strasbourg", "street": "13 rue des Cèdres", "country": "France", "postalCode": "67200"}, "website": "http://www.audraauclair.com", "category": "illustration", "emailPro": "AudraAuclair@exemple.com", "artistName": "Audra Auclair", "discipline": "Illustrator"}', 1),
	(9, 'hannah@exemple.com', 'Hannah', 'hannah', '$2y$13$.OQKZwD7aDzGhq6LGwYi/eQo6jrfQ8pHHYpRRw4V9Dwm2/zWHOGQq', '["ROLE_USER", "ROLE_ARTIST"]', 0, NULL, '2023-12-18 09:29:52', '2023-12-16 16:11:33', NULL, '{"address": {"city": "Strasbourg", "street": "13 rue des cèdres", "country": "France", "postalCode": "67200"}, "category": "Design", "emailPro": "CathCreator@example.com", "artistName": "John Creation", "discipline": "Illustrator"}', 1),
	(10, '04@exemple.com', 'Mark', 'mark', '$2y$13$s10jodTWxJhEIdIsMTFSXuSs/KV90NTRc0t8XCciuQXe4JnHU0fwK', '["ROLE_USER", "ROLE_ARTIST"]', 0, NULL, '2023-11-28 23:35:55', '2023-12-15 13:04:12', NULL, '{"address": {"city": "Strasbourg", "street": "14 rue Paul Janet", "country": "France", "postalCode": "67000"}, "category": "Design", "emailPro": "CathCreator@exemple.com", "artistName": "Cath Creation", "discipline": "Handcrafted Wood Artisan"}', -1),
	(11, '01@exemple.com', 'test', 'test', '$2y$13$wwZEBBpLdoitVV9DkGQ2UeA0a3sSqkNh/Oi.opinjdKgqqTypyZqK', '["ROLE_USER"]', 0, NULL, '2023-11-29 09:26:35', NULL, NULL, NULL, NULL),
	(12, '10@exemple.com', 'test10', 'test10', '$2y$13$om0bc3.EBPL04LSaeH8CYOkeEpeGvjp/HeK8vIs3rJhRfi/nY8iyC', '["ROLE_USER"]', 0, NULL, '2023-11-29 10:45:48', NULL, NULL, NULL, NULL),
	(13, 'luna@exemple.com', 'Luna', 'luna', '$2y$13$YPpkZAQQhMN5TcoJOqTWyeztWa1Np1CsHdx60Mq/71g5d61FFw6Sm', '["ROLE_USER", "ROLE_ADMIN"]', 1, NULL, '2023-11-29 10:58:30', '2023-11-29 13:12:46', NULL, NULL, NULL),
	(14, 'test@gmal.com', 'test', 'test', '$2y$13$Wx42D/YpHmrLgu1xJpEmh.WUyo44805AnSmsutKxEZo0v8Uq0Sf4m', '["ROLE_USER"]', 0, NULL, '2023-12-20 21:38:46', NULL, NULL, NULL, NULL),
	(18, 'celeste@exemple.com', 'Céleste2', 'celeste2', '$2y$13$uafPhat5ZOa0ymd7YaFrbOuIy4QzFWWn/YwGi0CE2gSp295jSIp1q', '["ROLE_USER"]', 0, NULL, '2024-02-13 00:13:00', NULL, NULL, NULL, 0),
	(19, 'audra@gmail.com', 'Audra Auclair', 'audra-auclair', '$2y$13$Ug3sz4ttKQy7hnouNHxnJ.SveE4prtH2qK98H0qu0O3fmNZ3iP5De', '["ROLE_USER", "ROLE_ADMIN", "ROLE_ARTIST", "ROLE_SUPERVISOR"]', 1, '65cd0d0f1910b.jpg', '2024-02-13 00:14:24', '2024-03-10 22:45:53', NULL, '{"bio": "Lorem Ipsn gravida nibh vel velit auctor aliquet. Aene sollic consequat ipsutis sem nibh id elit. Duis sed nibh vel a sit amet nibh vulputate. Lorem Ipsn vel velit auctor aliquet. Lorem Ipsn gravida nibh vel velit auctor aliquet. Aene sollic consequat ipsutis sem nibh id elit. Duis sed odio sit amet nibh vulputate. Lorem Ipsn gravida nibh vel melit auctor aliquet. Aene sollic consequat ipsutis sem nibh id elit. Duis sed odio sit amet nibh vulputate. Lorem Ipsn gravida nibh vel velit auct or aliquet. Aene sollic consequat ipsutis sem nibh id elit. Duis sed odio sit amet nibh vulputate. Duis sed nibh vel a sit amet nibh vulputate. Lorem Ipsn vel velit auctor aliquet. Lorem Ipsn gravida nibh vel velit auctor aliquet. Aene sollic consequat ipsutis sem nibh id elit.", "city": "Strasbourg", "shop": null, "quote": "Velit auctor aliquet. Aene sollic consequat ipsutis sem nibh id elit. Duis sed nibh vel a sit amet nibh", "street": "12 rue des machin", "address": {"city": "Strasbourg", "street": "13 rue des Cèdres", "country": "France", "postalCode": "67200"}, "website": "http://www.audraauclair.com", "category": "illustration", "emailPro": "AudraAuclair@exemple.com", "artistName": "Audra Auclair", "discipline": "Illustrator"}', 1),
	(20, 'cerrleste@exemple.com', 'test', 'test', '$2y$13$wp7F/ThHJbt9AQd3WSjhauMJ0aKBZFCIomsJwMfPq.mYffMOWOqbK', '["ROLE_USER"]', 0, NULL, '2024-03-16 09:06:19', NULL, NULL, NULL, 0),
	(21, '0001@exemple.com', 'John', 'john', '$2y$13$OhkMxAaGNX3k6yEykI0iqOIPs7omuo4UGqetlUKTIe0EpyHTki3zy', '["ROLE_USER"]', 0, NULL, '2024-03-16 09:06:59', NULL, NULL, NULL, 0),
	(22, '000001@exemple.com', 'john', 'john', '$2y$13$ml/2Feje/sJazdD9HiU0eOKMiDGjpEbVpb6eGazaSTvIq2NgSDd2W', '["ROLE_USER", "ROLE_SUPERVISOR"]', 0, NULL, '2024-03-16 09:07:34', NULL, NULL, NULL, 0);

-- Listage de la structure de table project. workshop
CREATE TABLE IF NOT EXISTS `workshop` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `detail` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `quote` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `nb_rooms` int NOT NULL,
  `picture` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_9B6F02C4A76ED395` (`user_id`),
  CONSTRAINT `FK_9B6F02C4A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table project.workshop : ~5 rows (environ)
REPLACE INTO `workshop` (`id`, `name`, `description`, `detail`, `quote`, `start_date`, `end_date`, `nb_rooms`, `picture`, `status`, `user_id`, `slug`) VALUES
	(1, 'Workshop01', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Feugiat in fermentum posuere urna nec tincidunt. Sit amet mauris commodo quis imperdiet massa tincidunt. Penatibus et magnis dis parturient montes.', ' Hendrerit dolor magna eget est lorem ipsum dolor. Penatibus et magnis dis parturient montes nascetur ridiculus mus. Elit duis tristique sollicitudin nibh sit amet. Amet justo donec enim diam. Velit ut tortor pretium viverra suspendisse potenti nullam ac tortor. Nulla pellentesque dignissim enim sit amet. Sit amet mauris commodo quis imperdiet. Fermentum posuere urna nec tincidunt praesent semper feugiat. Nisi vitae suscipit tellus mauris a. Egestas tellus rutrum tellus pellentesque eu tincidunt tortor. Massa ultricies mi quis hendrerit dolor magna eget. Magna fringilla urna porttitor rhoncus dolor purus non enim praesent. Ac tortor vitae purus faucibus ornare suspendisse sed. ', 'Velit auctor aliquet. Aene sollic consequat ipsutis sem nibh id elit. Duis sed nibh vel a sit amet nibh', '2024-02-25 10:06:39', '2024-02-28 10:06:42', 10, NULL, 'OPEN', 8, 'workshop01'),
	(2, 'Workshop02', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Feugiat in fermentum posuere urna nec tincidunt. Sit amet mauris commodo quis imperdiet massa tincidunt. Penatibus et magnis dis parturient montes.', 'Hendrerit dolor magna eget est lorem ipsum dolor. Penatibus et magnis dis parturient montes nascetur ridiculus mus. Elit duis tristique sollicitudin nibh sit amet. Amet justo donec enim diam. Velit ut tortor pretium viverra suspendisse potenti nullam ac tortor. Nulla pellentesque dignissim enim sit amet. Sit amet mauris commodo quis imperdiet. Fermentum posuere urna nec tincidunt praesent semper feugiat. Nisi vitae suscipit tellus mauris a. Egestas tellus rutrum tellus pellentesque eu tincidunt tortor. Massa ultricies mi quis hendrerit dolor magna eget. Magna fringilla urna porttitor rhoncus dolor purus non enim praesent. Ac tortor vitae purus faucibus ornare suspendisse sed. Eget mi proin sed libero enim sed faucibus turpis in. Sem et tortor consequat id porta nibh venenatis. Dolor sit amet consectetur adipiscing elit pellentesque habitant. Eget duis at tellus at urna. Bibendum neque egestas congue quisque egestas. Suspendisse ultrices gravida dictum fusce ut.', NULL, '2023-12-20 10:07:44', '2023-12-25 10:07:45', 8, NULL, 'OPEN', 8, 'workshop02'),
	(3, 'workshop 002', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Feugiat in fermentum posuere urna nec tincidunt. Sit amet mauris commodo quis imperdiet massa tincidunt. Penatibus et magnis dis parturient montes.', 'Hendrerit dolor magna eget est lorem ipsum dolor. Penatibus et magnis dis parturient montes nascetur ridiculus mus. Elit duis tristique sollicitudin nibh sit amet. Amet justo donec enim diam. Velit ut tortor pretium viverra suspendisse potenti nullam ac tortor. Nulla pellentesque dignissim enim sit amet. Sit amet mauris commodo quis imperdiet. Fermentum posuere urna nec tincidunt praesent semper feugiat. Nisi vitae suscipit tellus mauris a. Egestas tellus rutrum tellus pellentesque eu tincidunt tortor. Massa ultricies mi quis hendrerit dolor magna eget. Magna fringilla urna porttitor rhoncus dolor purus non enim praesent. Ac tortor vitae purus faucibus ornare suspendisse sed. Eget mi proin sed libero enim sed faucibus turpis in. Sem et tortor consequat id porta nibh venenatis. Dolor sit amet consectetur adipiscing elit pellentesque habitant. Eget duis at tellus at urna. Bibendum neque egestas congue quisque egestas. Suspendisse ultrices gravida dictum fusce ut.', NULL, '2023-11-30 00:00:00', '2023-12-29 00:00:00', 3, NULL, 'PENDING', 8, 'workshop002'),
	(4, 'workshop10', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Feugiat in fermentum posuere urna nec tincidunt. Sit amet mauris commodo quis imperdiet massa tincidunt. Penatibus et magnis dis parturient montes.', 'Hendrerit dolor magna eget est lorem ipsum dolor. Penatibus et magnis dis parturient montes nascetur ridiculus mus. Elit duis tristique sollicitudin nibh sit amet. Amet justo donec enim diam. Velit ut tortor pretium viverra suspendisse potenti nullam ac tortor. Nulla pellentesque dignissim enim sit amet. Sit amet mauris commodo quis imperdiet. Fermentum posuere urna nec tincidunt praesent semper feugiat. Nisi vitae suscipit tellus mauris a. Egestas tellus rutrum tellus pellentesque eu tincidunt tortor. Massa ultricies mi quis hendrerit dolor magna eget. Magna fringilla urna porttitor rhoncus dolor purus non enim praesent. Ac tortor vitae purus faucibus ornare suspendisse sed. Eget mi proin sed libero enim sed faucibus turpis in. Sem et tortor consequat id porta nibh venenatis. Dolor sit amet consectetur adipiscing elit pellentesque habitant. Eget duis at tellus at urna. Bibendum neque egestas congue quisque egestas. Suspendisse ultrices gravida dictum fusce ut.', NULL, '2023-11-29 00:00:00', '2023-12-29 00:00:00', 4, NULL, 'CLOSED', 8, 'workshop10'),
	(5, 'workshop 20', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Feugiat in fermentum posuere urna nec tincidunt. Sit amet mauris commodo quis imperdiet massa tincidunt. Penatibus et magnis dis parturient montes.', 'Hendrerit dolor magna eget est lorem ipsum dolor. Penatibus et magnis dis parturient montes nascetur ridiculus mus. Elit duis tristique sollicitudin nibh sit amet. Amet justo donec enim diam. Velit ut tortor pretium viverra suspendisse potenti nullam ac tortor. Nulla pellentesque dignissim enim sit amet. Sit amet mauris commodo quis imperdiet. Fermentum posuere urna nec tincidunt praesent semper feugiat. Nisi vitae suscipit tellus mauris a. Egestas tellus rutrum tellus pellentesque eu tincidunt tortor. Massa ultricies mi quis hendrerit dolor magna eget. Magna fringilla urna porttitor rhoncus dolor purus non enim praesent. Ac tortor vitae purus faucibus ornare suspendisse sed. Eget mi proin sed libero enim sed faucibus turpis in. Sem et tortor consequat id porta nibh venenatis. Dolor sit amet consectetur adipiscing elit pellentesque habitant. Eget duis at tellus at urna. Bibendum neque egestas congue quisque egestas. Suspendisse ultrices gravida dictum fusce ut.', NULL, '2023-12-01 00:00:00', '2023-12-21 00:00:00', 2, NULL, 'ARCHIVED', 8, 'workshop20');

-- Listage de la structure de table project. workshop_registration
CREATE TABLE IF NOT EXISTS `workshop_registration` (
  `id` int NOT NULL AUTO_INCREMENT,
  `firstname` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `lastname` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `registration_date` datetime NOT NULL,
  `user_id` int DEFAULT NULL,
  `workshop_id` int DEFAULT NULL,
  `timeslot_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_F3F1945B1FDCE57C` (`workshop_id`),
  KEY `IDX_F3F1945BA76ED395` (`user_id`),
  KEY `IDX_F3F1945BF920B9E9` (`timeslot_id`),
  CONSTRAINT `FK_F3F1945B1FDCE57C` FOREIGN KEY (`workshop_id`) REFERENCES `workshop` (`id`),
  CONSTRAINT `FK_F3F1945BA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_F3F1945BF920B9E9` FOREIGN KEY (`timeslot_id`) REFERENCES `timeslot` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table project.workshop_registration : ~8 rows (environ)
REPLACE INTO `workshop_registration` (`id`, `firstname`, `lastname`, `registration_date`, `user_id`, `workshop_id`, `timeslot_id`) VALUES
	(6, 'test', 'test', '2023-12-11 10:48:08', 19, 1, NULL),
	(7, '4242', '424242', '2023-12-11 10:54:25', 19, 5, NULL),
	(11, 'Machin', 'truc', '2023-12-13 22:35:17', 11, NULL, 1),
	(14, 'Paul', 'Paul', '2023-12-17 12:42:42', 10, 3, NULL),
	(15, 'Machin', 'Truc', '2023-12-17 12:43:05', 9, 3, NULL),
	(16, 'Cath', 'Laurier', '2023-12-17 11:47:16', 8, 2, NULL),
	(17, 'Cath', 'Laurier', '2023-12-17 14:31:26', 19, NULL, 3),
	(22, 'Cath', 'Laurier', '2023-12-22 08:24:45', 19, NULL, 1);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
