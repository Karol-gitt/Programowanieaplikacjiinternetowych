-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Wersja serwera:               10.1.34-MariaDB - mariadb.org binary distribution
-- Serwer OS:                    Win32
-- HeidiSQL Wersja:              11.0.0.5919
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Zrzut struktury tabela testy2.groups
CREATE TABLE IF NOT EXISTS `groups` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `description` varchar(100) NOT NULL,
  `owner` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- Zrzucanie danych dla tabeli testy2.groups: ~4 rows (około)
/*!40000 ALTER TABLE `groups` DISABLE KEYS */;
INSERT INTO `groups` (`id`, `name`, `description`, `owner`) VALUES
	(1, 'admin', 'Administrator', NULL),
	(6, 'Groupson', '', NULL),
	(7, 'Group', '', NULL),
	(10, 'members', 'Nauczyciel', NULL);
/*!40000 ALTER TABLE `groups` ENABLE KEYS */;

-- Zrzut struktury tabela testy2.login_attempts
CREATE TABLE IF NOT EXISTS `login_attempts` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(15) NOT NULL,
  `login` varchar(100) NOT NULL,
  `time` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Zrzucanie danych dla tabeli testy2.login_attempts: ~0 rows (około)
/*!40000 ALTER TABLE `login_attempts` DISABLE KEYS */;
/*!40000 ALTER TABLE `login_attempts` ENABLE KEYS */;

-- Zrzut struktury tabela testy2.questions
CREATE TABLE IF NOT EXISTS `questions` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `category_id` bigint(10) NOT NULL,
  `type` varchar(45) NOT NULL,
  `name` tinytext NOT NULL,
  `question` longtext NOT NULL,
  `rightanswer` longtext NOT NULL,
  `rate` int(11) NOT NULL DEFAULT '1',
  `random_answers` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_questions_questions_categories1_idx` (`category_id`),
  KEY `fk_questions_questions_types1_idx` (`type`),
  CONSTRAINT `fk_questions_questions_categories1` FOREIGN KEY (`category_id`) REFERENCES `questions_categories` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `fk_questions_questions_types1` FOREIGN KEY (`type`) REFERENCES `questions_types` (`alias`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8;

-- Zrzucanie danych dla tabeli testy2.questions: ~6 rows (około)
/*!40000 ALTER TABLE `questions` DISABLE KEYS */;
INSERT INTO `questions` (`id`, `category_id`, `type`, `name`, `question`, `rightanswer`, `rate`, `random_answers`) VALUES
	(7, 3, 'short', 'Pytanie o ulubiony kolor', '<p>Jaki jest ulubiony kolor św. Mikołaja?</p>\r\n', 'czerwony;biały', 1, 0),
	(10, 1, 'short', 'ddddd', '<p>ddddd</p>\r\n', 'dddd;11111;fffff;cccggg', 1, 0),
	(27, 1, 'short', 'sssss', '<p>asdsad</p>\r\n', 'asdsad;sdsdsd;asdsdsd', 2, 0),
	(28, 1, 'test-multi', 'sPytanie multi', '<p>multi</p>\r\n', 'ccc', 2, 1),
	(29, 3, 'test-one', 'Poprawienie szybkości pracy komputera', '<p>Tw&oacute;j komputer spowalnia pracę podczas uruchamiania niekt&oacute;rych aplikacji. Kt&oacute;ra z następujących czynności najprawdopodobniej poprawi szybkość pracy komputera?</p>\r\n', 'Defragmentacja dysku', 1, 0),
	(32, 1, 'test-multi', 'Które zdania są prawdziwe?', '<p>Kt&oacute;re zdania są prawdziwe?</p>\r\n\r\n<p><span class="math-tex">\\(x = {-b \\pm \\sqrt{b^2-4ac} \\over 2a}\\)</span></p>\r\n', 'Kot lubi miałczeć;Pies lubi lizać się po jajkach', 1, 1);
/*!40000 ALTER TABLE `questions` ENABLE KEYS */;

-- Zrzut struktury tabela testy2.questions_answers
CREATE TABLE IF NOT EXISTS `questions_answers` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `questions_id` bigint(10) NOT NULL,
  `answer` longtext NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_questions_answers_questions1_idx` (`questions_id`),
  CONSTRAINT `fk_questions_answers_questions1` FOREIGN KEY (`questions_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=95 DEFAULT CHARSET=utf8;

-- Zrzucanie danych dla tabeli testy2.questions_answers: ~9 rows (około)
/*!40000 ALTER TABLE `questions_answers` DISABLE KEYS */;
INSERT INTO `questions_answers` (`id`, `questions_id`, `answer`) VALUES
	(83, 29, 'Odkurzenie'),
	(84, 29, 'Defragmentacja dysku'),
	(85, 29, 'Dolanie wody do zasilacza'),
	(89, 28, 'aaa'),
	(90, 28, 'fff'),
	(91, 28, 'ccc'),
	(92, 32, 'Kot lubi miałczeć'),
	(93, 32, 'Mysz nie boi się kota'),
	(94, 32, 'Pies lubi lizać się po jajkach');
/*!40000 ALTER TABLE `questions_answers` ENABLE KEYS */;

-- Zrzut struktury tabela testy2.questions_categories
CREATE TABLE IF NOT EXISTS `questions_categories` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `users_id` int(11) unsigned NOT NULL,
  `name` tinytext NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_question_categories_users1_idx` (`users_id`),
  CONSTRAINT `fk_question_categories_users1` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- Zrzucanie danych dla tabeli testy2.questions_categories: ~2 rows (około)
/*!40000 ALTER TABLE `questions_categories` DISABLE KEYS */;
INSERT INTO `questions_categories` (`id`, `users_id`, `name`) VALUES
	(1, 2, 'Kategoria 123'),
	(3, 2, 'Kategoria 2');
/*!40000 ALTER TABLE `questions_categories` ENABLE KEYS */;

-- Zrzut struktury tabela testy2.questions_has_tests
CREATE TABLE IF NOT EXISTS `questions_has_tests` (
  `questions_id` bigint(10) NOT NULL,
  `tests_id` bigint(10) NOT NULL,
  `order` int(11) NOT NULL,
  PRIMARY KEY (`questions_id`,`tests_id`),
  KEY `fk_questions_has_tests_tests1_idx` (`tests_id`),
  KEY `fk_questions_has_tests_questions1_idx` (`questions_id`),
  CONSTRAINT `fk_questions_has_tests_questions1` FOREIGN KEY (`questions_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `fk_questions_has_tests_tests1` FOREIGN KEY (`tests_id`) REFERENCES `tests` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Zrzucanie danych dla tabeli testy2.questions_has_tests: ~9 rows (około)
/*!40000 ALTER TABLE `questions_has_tests` DISABLE KEYS */;
INSERT INTO `questions_has_tests` (`questions_id`, `tests_id`, `order`) VALUES
	(7, 2, 1),
	(7, 8, 5),
	(10, 8, 1),
	(27, 8, 2),
	(28, 8, 3),
	(29, 2, 3),
	(29, 8, 6),
	(32, 2, 2),
	(32, 8, 4);
/*!40000 ALTER TABLE `questions_has_tests` ENABLE KEYS */;

-- Zrzut struktury tabela testy2.questions_types
CREATE TABLE IF NOT EXISTS `questions_types` (
  `alias` varchar(45) NOT NULL,
  `type` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`alias`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Zrzucanie danych dla tabeli testy2.questions_types: ~3 rows (około)
/*!40000 ALTER TABLE `questions_types` DISABLE KEYS */;
INSERT INTO `questions_types` (`alias`, `type`) VALUES
	('short', 'Krótka odpowiedź'),
	('test-multi', 'Test (wiele prawidłowych odpowiedzi)'),
	('test-one', 'Test (tylko jedna prawidłowa odpowiedź)');
/*!40000 ALTER TABLE `questions_types` ENABLE KEYS */;

-- Zrzut struktury tabela testy2.student_answers
CREATE TABLE IF NOT EXISTS `student_answers` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `questions_id` bigint(10) NOT NULL,
  `test_attempts_id` bigint(10) NOT NULL,
  `answer` longtext,
  `rate` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_student_answers_test_attempts1_idx` (`test_attempts_id`),
  KEY `fk_student_answers_questions1_idx` (`questions_id`),
  CONSTRAINT `fk_student_answers_questions1` FOREIGN KEY (`questions_id`) REFERENCES `questions` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_student_answers_test_attempts1` FOREIGN KEY (`test_attempts_id`) REFERENCES `test_attempts` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;

-- Zrzucanie danych dla tabeli testy2.student_answers: ~11 rows (około)
/*!40000 ALTER TABLE `student_answers` DISABLE KEYS */;
INSERT INTO `student_answers` (`id`, `questions_id`, `test_attempts_id`, `answer`, `rate`) VALUES
	(4, 7, 34, 'czerwony', 1),
	(5, 29, 34, 'Odkurzenie', 0),
	(6, 32, 34, 'Kot lubi miałczeć;Pies lubi lizać się po jajkach', 1),
	(23, 10, 43, 'ffff', 0),
	(24, 10, 44, 'dd', 0),
	(25, 10, 46, 'aaa', 0),
	(26, 27, 46, 'ccc', 0),
	(27, 28, 46, 'ccc', 2),
	(28, 32, 46, 'Mysz nie boi się kota', 0),
	(29, 7, 46, 'fff', 0),
	(30, 29, 46, 'Dolanie wody do zasilacza', 0);
/*!40000 ALTER TABLE `student_answers` ENABLE KEYS */;

-- Zrzut struktury tabela testy2.tests
CREATE TABLE IF NOT EXISTS `tests` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `users_id` int(10) unsigned NOT NULL,
  `title` varchar(200) NOT NULL,
  `description` longtext,
  `access_code` varchar(100) NOT NULL,
  `time` int(11) NOT NULL DEFAULT '0',
  `create` datetime NOT NULL,
  `visible_result` tinyint(1) NOT NULL DEFAULT '0',
  `random_questions` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_tests_users1_idx` (`users_id`),
  CONSTRAINT `fk_tests_users1` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- Zrzucanie danych dla tabeli testy2.tests: ~2 rows (około)
/*!40000 ALTER TABLE `tests` DISABLE KEYS */;
INSERT INTO `tests` (`id`, `users_id`, `title`, `description`, `access_code`, `time`, `create`, `visible_result`, `random_questions`) VALUES
	(2, 2, 'Test 1', '<p>Rozwiąż test teraz</p>\r\n', '112345', 0, '2015-08-14 23:44:47', 0, 1),
	(8, 2, 'Test 2', '<p>opis</p>\r\n', '111', 5, '2015-08-15 21:00:28', 1, 0);
/*!40000 ALTER TABLE `tests` ENABLE KEYS */;

-- Zrzut struktury tabela testy2.test_attempts
CREATE TABLE IF NOT EXISTS `test_attempts` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `tests_id` bigint(10) NOT NULL,
  `student` varchar(200) NOT NULL,
  `start_time` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL,
  `sumary` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_test_attempts_tests1_idx` (`tests_id`),
  CONSTRAINT `fk_test_attempts_tests1` FOREIGN KEY (`tests_id`) REFERENCES `tests` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8;

-- Zrzucanie danych dla tabeli testy2.test_attempts: ~5 rows (około)
/*!40000 ALTER TABLE `test_attempts` DISABLE KEYS */;
INSERT INTO `test_attempts` (`id`, `tests_id`, `student`, `start_time`, `end_time`, `sumary`) VALUES
	(34, 2, 'Stefan Burczymucha', '2015-08-19 11:23:46', '2015-08-19 14:23:09', 2),
	(43, 8, 'Stefan Burczymucha', '2020-03-20 14:06:06', NULL, 0),
	(44, 8, '23', '2020-03-20 14:08:12', '2020-03-20 14:08:31', 0),
	(45, 8, '12', '2020-03-20 14:09:06', NULL, 0),
	(46, 8, '12', '2020-03-20 14:16:59', '2020-03-20 14:22:48', 2);
/*!40000 ALTER TABLE `test_attempts` ENABLE KEYS */;

-- Zrzut struktury tabela testy2.users
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
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- Zrzucanie danych dla tabeli testy2.users: ~4 rows (około)
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `ip_address`, `username`, `password`, `salt`, `email`, `activation_code`, `forgotten_password_code`, `forgotten_password_time`, `remember_code`, `created_on`, `last_login`, `active`, `first_name`, `last_name`, `company`, `phone`) VALUES
	(1, '127.0.0.1', 'administrator', '$2y$08$mOjvY5zwBQT6E6hdpDQNR.ocksUVjPUkoyb6b.Szgm/IIHe/6FtlW', '', 'admin@admin.com', '', 'KJ01wb5Y0i22mufgt9Pb5u6c9a899bb263694bb9', 1429134390, NULL, 1268889823, 1584707823, 1, 'Admin', 'istrator', NULL, NULL),
	(2, '::1', 'aaa aaa', '$2a$08$oxH5VhPsDdxIKWqNqgUySe9uLWGQxoSmarLHIUDHoojajAIzAZZxq', NULL, 'aaa@aaa.pl', NULL, NULL, NULL, NULL, 1429214067, 1584708746, 1, 'aaabbb', 'aaabbb', NULL, NULL),
	(3, '::1', 'aaa aaa1', '$2y$08$tj3jXxp/u6s/TC3NApFsPOoQgEp7YArrbQfvoYuupihZ1iOlnT9LO', NULL, 'aaaaaa@aaa.pl', NULL, 'k1BPMZT4K.-f1oPh.YZaQ.7f84f61c5ba97252c5', 1434550168, NULL, 1429214111, NULL, 1, 'aaa', 'aaa', NULL, NULL),
	(10, '::1', 'bbb bbb', '$2y$08$zm.POLMjn2EbOmhfOwzIb.AwG8sHOBIMVWXxCiaMjvjhUWm4/et3K', NULL, 'bbb@bbb.pl', NULL, NULL, NULL, NULL, 1437493688, 1439568824, 1, 'bbb', 'bbb', NULL, NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

-- Zrzut struktury tabela testy2.users_groups
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
) ENGINE=InnoDB AUTO_INCREMENT=89 DEFAULT CHARSET=utf8;

-- Zrzucanie danych dla tabeli testy2.users_groups: ~5 rows (około)
/*!40000 ALTER TABLE `users_groups` DISABLE KEYS */;
INSERT INTO `users_groups` (`id`, `user_id`, `group_id`) VALUES
	(83, 1, 1),
	(84, 1, 10),
	(81, 2, 10),
	(80, 3, 10),
	(88, 10, 10);
/*!40000 ALTER TABLE `users_groups` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
