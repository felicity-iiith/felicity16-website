--
-- Table structure for table `futsal_teams`
--

CREATE TABLE `futsal_teams` (
  `id` int(11) NOT NULL,
  `team_name` varchar(64) NOT NULL,
  `contact_number` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `futsal_participants`
--

CREATE TABLE `futsal_participants` (
  `team_id` int(11) NOT NULL,
  `nick` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
