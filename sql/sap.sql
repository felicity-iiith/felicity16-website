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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
