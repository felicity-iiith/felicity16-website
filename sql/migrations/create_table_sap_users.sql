CREATE TABLE IF NOT EXISTS `sap_users` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` varchar(64) COLLATE utf8_bin NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT '0',
  `registration_id` int(10) UNSIGNED NOT NULL,
  `password_hash` char(60) COLLATE utf8_bin NOT NULL,
  `score` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
