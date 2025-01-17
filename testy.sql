/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

CREATE TABLE IF NOT EXISTS `groups` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `description` varchar(100) NOT NULL,
  `owner` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='Aplikacja testy';

INSERT INTO `groups` (`id`, `name`, `description`, `owner`) VALUES
	(1, 'admin', 'Administrator', NULL),
	(10, 'members', 'Nauczyciel', NULL);

CREATE TABLE IF NOT EXISTS `questions` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `category_id` bigint(10) NOT NULL,
  `type` varchar(45) NOT NULL,
  `name` tinytext NOT NULL,
  `question` longtext NOT NULL,
  `rightanswer` longtext NOT NULL,
  `rate` int(11) NOT NULL DEFAULT 1,
  `random_answers` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `fk_questions_questions_categories1_idx` (`category_id`),
  KEY `fk_questions_questions_types1_idx` (`type`),
  CONSTRAINT `fk_questions_questions_categories1` FOREIGN KEY (`category_id`) REFERENCES `questions_categories` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `fk_questions_questions_types1` FOREIGN KEY (`type`) REFERENCES `questions_types` (`alias`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='Aplikacja testy';

INSERT INTO `questions` (`id`, `category_id`, `type`, `name`, `question`, `rightanswer`, `rate`, `random_answers`) VALUES
	(7, 3, 'short', 'Pytanie o ulubiony kolor', '<p>Jaki jest ulubiony kolor św. Mikołaja?</p>\r\n', 'czerwony;biały', 1, 0),
	(10, 1, 'short', 'ddddd', '<p>ddddd</p>\r\n', 'dddd;11111;fffff;cccggg', 1, 0),
	(27, 1, 'short', 'sssss', '<p>asdsad</p>\r\n', 'asdsad;sdsdsd;asdsdsd', 2, 0),
	(28, 1, 'test-multi', 'sPytanie multi', '<p>multi</p>\r\n', 'ccc', 2, 1),
	(29, 3, 'test-one', 'Poprawienie szybkości pracy komputera', '<p>Tw&oacute;j komputer spowalnia pracę podczas uruchamiania niekt&oacute;rych aplikacji. Kt&oacute;ra z następujących czynności najprawdopodobniej poprawi szybkość pracy komputera?</p>\r\n', 'Defragmentacja dysku', 1, 0),
	(32, 1, 'test-multi', 'Które zdania są prawdziwe?', '<p>Kt&oacute;re zdania są prawdziwe?</p>\r\n\r\n<p><span class="math-tex">\\(x = {-b \\pm \\sqrt{b^2-4ac} \\over 2a}\\)</span></p>\r\n', 'Kot lubi miałczeć;Pies lubi lizać się po jajkach', 1, 1),
	(33, 4, 'test-one', 'Pytanie 1', '<p>Treść pytania</p>\r\n', 'tak', 1, 0),
	(34, 4, 'test-multi', 'Pytanie 2', '<p>Treść pytania 2</p>\r\n', 'tak;może', 1, 0);

CREATE TABLE IF NOT EXISTS `questions_answers` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `questions_id` bigint(10) NOT NULL,
  `answer` longtext NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_questions_answers_questions1_idx` (`questions_id`),
  CONSTRAINT `fk_questions_answers_questions1` FOREIGN KEY (`questions_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=102 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='Aplikacja testy';

INSERT INTO `questions_answers` (`id`, `questions_id`, `answer`) VALUES
	(83, 29, 'Odkurzenie'),
	(84, 29, 'Defragmentacja dysku'),
	(85, 29, 'Dolanie wody do zasilacza'),
	(89, 28, 'aaa'),
	(90, 28, 'fff'),
	(91, 28, 'ccc'),
	(92, 32, 'Kot lubi miałczeć'),
	(93, 32, 'Mysz nie boi się kota'),
	(94, 32, 'Pies lubi lizać się po jajkach'),
	(95, 33, 'tak'),
	(96, 33, 'nie'),
	(97, 33, 'nie wiem'),
	(98, 34, 'tak'),
	(99, 34, 'nie'),
	(100, 34, 'nie wiem'),
	(101, 34, 'może');

CREATE TABLE IF NOT EXISTS `questions_categories` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `users_id` int(11) unsigned NOT NULL,
  `name` tinytext NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_question_categories_users1_idx` (`users_id`),
  CONSTRAINT `fk_question_categories_users1` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='Aplikacja testy';

INSERT INTO `questions_categories` (`id`, `users_id`, `name`) VALUES
	(1, 2, 'Kategoria 123'),
	(3, 2, 'Kategoria 2'),
	(4, 1, 'Kategoria 1');

CREATE TABLE IF NOT EXISTS `questions_has_tests` (
  `questions_id` bigint(10) NOT NULL,
  `tests_id` bigint(10) NOT NULL,
  `order` int(11) NOT NULL,
  PRIMARY KEY (`questions_id`,`tests_id`),
  KEY `fk_questions_has_tests_tests1_idx` (`tests_id`),
  KEY `fk_questions_has_tests_questions1_idx` (`questions_id`),
  CONSTRAINT `fk_questions_has_tests_questions1` FOREIGN KEY (`questions_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `fk_questions_has_tests_tests1` FOREIGN KEY (`tests_id`) REFERENCES `tests` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='Aplikacja testy';

INSERT INTO `questions_has_tests` (`questions_id`, `tests_id`, `order`) VALUES
	(7, 2, 1),
	(7, 8, 5),
	(10, 8, 1),
	(27, 8, 2),
	(28, 8, 3),
	(29, 2, 3),
	(29, 8, 6),
	(32, 2, 2),
	(32, 8, 4),
	(33, 9, 1),
	(34, 9, 2);

CREATE TABLE IF NOT EXISTS `questions_types` (
  `alias` varchar(45) NOT NULL,
  `type` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`alias`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='Aplikacja testy';

INSERT INTO `questions_types` (`alias`, `type`) VALUES
	('short', 'Krótka odpowiedź'),
	('test-multi', 'Test (wiele prawidłowych odpowiedzi)'),
	('test-one', 'Test (tylko jedna prawidłowa odpowiedź)');

CREATE TABLE IF NOT EXISTS `student_answers` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `questions_id` bigint(10) NOT NULL,
  `test_attempts_id` bigint(10) NOT NULL,
  `answer` longtext DEFAULT NULL,
  `rate` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `fk_student_answers_test_attempts1_idx` (`test_attempts_id`),
  KEY `fk_student_answers_questions1_idx` (`questions_id`),
  CONSTRAINT `fk_student_answers_questions1` FOREIGN KEY (`questions_id`) REFERENCES `questions` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_student_answers_test_attempts1` FOREIGN KEY (`test_attempts_id`) REFERENCES `test_attempts` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='Aplikacja testy';

INSERT INTO `student_answers` (`id`, `questions_id`, `test_attempts_id`, `answer`, `rate`) VALUES
	(4, 7, 34, 'czerwony', 1),
	(5, 29, 34, 'Odkurzenie', 0),
	(6, 32, 34, 'Kot lubi miałczeć;Pies lubi lizać się po jajkach', 1),
	(7, 10, 35, 'aaa', 0),
	(8, 27, 35, 'fff', 0),
	(10, 32, 35, 'Pies lubi lizać się po jajkach;Kot lubi miałczeć', 1),
	(14, 29, 35, 'Odkurzenie', 0),
	(15, 27, 36, 'sss', 0),
	(16, 32, 36, 'Kot lubi miałczeć', 0),
	(17, 7, 36, 'czerwony', 1),
	(18, 29, 36, 'Defragmentacja dysku', 1),
	(19, 10, 37, 'aaa', 0),
	(20, 27, 37, 'ddd', 0),
	(21, 10, 38, 'ddd', 0),
	(22, 33, 39, 'tak', 1),
	(23, 34, 39, 'nie wiem;może', 0);

CREATE TABLE IF NOT EXISTS `tests` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `users_id` int(10) unsigned NOT NULL,
  `title` varchar(200) NOT NULL,
  `description` longtext DEFAULT NULL,
  `access_code` varchar(100) NOT NULL,
  `create` datetime NOT NULL,
  `visible_result` tinyint(1) NOT NULL DEFAULT 0,
  `random_questions` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `fk_tests_users1_idx` (`users_id`),
  CONSTRAINT `fk_tests_users1` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='Aplikacja testy';

INSERT INTO `tests` (`id`, `users_id`, `title`, `description`, `access_code`, `create`, `visible_result`, `random_questions`) VALUES
	(2, 2, 'Test 1', '<p>Rozwiąż test teraz</p>\r\n', '112345', '2015-08-14 23:44:47', 0, 1),
	(8, 2, 'Test 2', '<p>opis</p>\r\n', '111', '2015-08-15 21:00:28', 1, 0),
	(9, 1, 'test 1', '<p>Opis testu</p>\r\n', '430d5547', '2019-02-18 12:23:04', 0, 0);

CREATE TABLE IF NOT EXISTS `test_attempts` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `tests_id` bigint(10) NOT NULL,
  `student` varchar(200) NOT NULL,
  `start_time` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL,
  `sumary` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `fk_test_attempts_tests1_idx` (`tests_id`),
  CONSTRAINT `fk_test_attempts_tests1` FOREIGN KEY (`tests_id`) REFERENCES `tests` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='Aplikacja testy';

INSERT INTO `test_attempts` (`id`, `tests_id`, `student`, `start_time`, `end_time`, `sumary`) VALUES
	(34, 2, 'Stefan Burczymucha', '2015-08-19 11:23:46', '2015-08-19 14:23:09', 2),
	(35, 8, 'Stefan Burczymucha', '2015-08-19 15:03:11', '2015-08-19 20:21:08', 1),
	(36, 8, 'Adam Słodowy', '2015-08-19 20:21:21', '2015-08-19 20:56:23', 2),
	(37, 8, 'Adam', '2015-08-19 21:04:30', '2015-08-19 21:04:56', 0),
	(38, 8, 'Ewa', '2015-08-19 21:05:20', NULL, 0),
	(39, 9, 'Stefan Burczymucha', '2019-02-18 12:27:45', '2019-02-18 12:28:08', 1);

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(15) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `salt` varchar(255) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `activation_code` varchar(40) DEFAULT NULL,
  `forgotten_password_code` varchar(40) DEFAULT NULL,
  `forgotten_password_time` int(11) unsigned DEFAULT NULL,
  `remember_code` varchar(40) DEFAULT NULL,
  `created_on` int(11) unsigned NOT NULL,
  `last_login` int(11) unsigned DEFAULT NULL,
  `active` tinyint(1) unsigned DEFAULT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `company` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='Aplikacja testy';

INSERT INTO `users` (`id`, `ip_address`, `username`, `password`, `salt`, `email`, `activation_code`, `forgotten_password_code`, `forgotten_password_time`, `remember_code`, `created_on`, `last_login`, `active`, `first_name`, `last_name`, `company`, `phone`) VALUES
	(1, '127.0.0.1', 'administrator', '$2y$08$mOjvY5zwBQT6E6hdpDQNR.ocksUVjPUkoyb6b.Szgm/IIHe/6FtlW', '', 'admin@admin.com', '', 'KJ01wb5Y0i22mufgt9Pb5u6c9a899bb263694bb9', 1429134390, NULL, 1268889823, 1736797377, 1, 'Admin', 'istrator', NULL, NULL),
	(2, '::1', 'aaa aaa', '$2a$08$oxH5VhPsDdxIKWqNqgUySe9uLWGQxoSmarLHIUDHoojajAIzAZZxq', NULL, 'aaa@aaa.pl', NULL, NULL, NULL, NULL, 1429214067, 1552910584, 1, 'aaa', 'aaa', NULL, NULL);

CREATE TABLE IF NOT EXISTS `users_groups` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `group_id` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uc_users_groups` (`user_id`,`group_id`),
  KEY `fk_users_groups_users1_idx` (`user_id`),
  KEY `fk_users_groups_groups1_idx` (`group_id`),
  CONSTRAINT `fk_users_groups_groups1` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `fk_users_groups_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=86 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='Aplikacja testy';

INSERT INTO `users_groups` (`id`, `user_id`, `group_id`) VALUES
	(83, 1, 1),
	(84, 1, 10),
	(85, 2, 10);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
