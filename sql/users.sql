-- phpMyAdmin SQL Dump
-- version 4.6.0-dev
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 06, 2016 at 11:21 PM
-- Server version: 5.5.46-0ubuntu0.14.04.2-log
-- PHP Version: 5.5.9-1ubuntu4.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `feli_users`
--

-- --------------------------------------------------------

--
-- Table structure for table `oauth_users`
--

CREATE TABLE IF NOT EXISTS `oauth_users` (
  `oauth_id` varchar(255) COLLATE utf8_bin NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`oauth_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nick` varchar(64) COLLATE utf8_bin NOT NULL,
  `mail` varchar(255) COLLATE utf8_bin NOT NULL,
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  `gender` enum('male','female','other') COLLATE utf8_bin DEFAULT NULL,
  `location` varchar(255) COLLATE utf8_bin NOT NULL,
  `country` varchar(255) COLLATE utf8_bin NOT NULL,
  `dob` date NOT NULL,
  `organization` varchar(255) COLLATE utf8_bin NOT NULL,
  `raw_attributes` text COLLATE utf8_bin NOT NULL,
  `email_verified` tinyint(1) NOT NULL,
  `resitration_status` enum('email_required','incomplete','complete') COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniqueNick` (`nick`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
