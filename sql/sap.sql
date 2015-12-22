SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


CREATE TABLE IF NOT EXISTS `sap_ambassadors` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(64) COLLATE utf8_bin NOT NULL,
  `email` varchar(64) COLLATE utf8_bin NOT NULL,
  `phone_number` varchar(32) COLLATE utf8_bin NOT NULL,
  `college` varchar(128) COLLATE utf8_bin NOT NULL,
  `program_of_study` varchar(128) COLLATE utf8_bin NOT NULL,
  `year_of_study` char(8) COLLATE utf8_bin NOT NULL,
  `facebook_profile_link` varchar(64) COLLATE utf8_bin NOT NULL,
  `why_apply` text COLLATE utf8_bin NOT NULL,
  `about_you` text COLLATE utf8_bin NOT NULL,
  `organised_event` text COLLATE utf8_bin,
  `registration_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `hash_for_ceating_password` CHAR(42) NULL DEFAULT NULL UNIQUE,
  `has_activated` BOOLEAN NOT NULL DEFAULT FALSE,
  `is_removed` BOOLEAN NOT NULL DEFAULT FALSE,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE IF NOT EXISTS `sap_missions` (
  `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT,
  `published` BOOLEAN NOT NULL DEFAULT FALSE,
  `level` smallint(5) UNSIGNED NOT NULL,
  `points` smallint(5) UNSIGNED NOT NULL,
  `title` varchar(128) COLLATE utf8_bin NOT NULL,
  `description` text COLLATE utf8_bin,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE IF NOT EXISTS `sap_tasks` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `mission_id` smallint(5) UNSIGNED NOT NULL,
  `description` text COLLATE utf8_bin,
  `has_text_answer` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mission_id` (`mission_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE IF NOT EXISTS `sap_task_submissions` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `task_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `answer` text COLLATE utf8_bin,
  `done` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `task_id` (`task_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE IF NOT EXISTS `sap_users` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `email` varchar(64) COLLATE utf8_bin NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT '0',
  `registration_id` int(10) UNSIGNED NOT NULL,
  `password_hash` char(60) COLLATE utf8_bin NOT NULL,
  `score` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


ALTER TABLE `sap_tasks`
  ADD CONSTRAINT `sap_tasks_ibfk_1` FOREIGN KEY (`mission_id`) REFERENCES `sap_missions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `sap_task_submissions`
  ADD CONSTRAINT `sap_task_submissions_ibfk_1` FOREIGN KEY (`task_id`) REFERENCES `sap_tasks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
