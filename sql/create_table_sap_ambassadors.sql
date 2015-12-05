-- phpMyAdmin SQL Dump
-- version 4.4.8
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 05, 2015 at 04:24 PM
-- Server version: 5.5.46-0ubuntu0.14.04.2
-- PHP Version: 5.5.9-1ubuntu4.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `felicity`
--

-- --------------------------------------------------------

--
-- Table structure for table `sap_ambassadors`
--

CREATE TABLE IF NOT EXISTS `sap_ambassadors` (
  `id` int(10) unsigned NOT NULL,
  `name` varchar(64) NOT NULL,
  `email` varchar(64) NOT NULL,
  `phone_number` varchar(32) NOT NULL,
  `college` varchar(128) NOT NULL,
  `program_of_study` varchar(128) NOT NULL,
  `year_of_study` char(8) NOT NULL,
  `facebook_profile_link` varchar(64) NOT NULL,
  `why_apply` text NOT NULL,
  `about_you` text NOT NULL,
  `organised_event` text,
  `registration_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `sap_ambassadors`
--
ALTER TABLE `sap_ambassadors`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `sap_ambassadors`
--
ALTER TABLE `sap_ambassadors`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
