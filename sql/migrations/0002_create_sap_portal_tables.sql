CREATE TABLE IF NOT EXISTS `sap_missions` (
  `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT,
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
