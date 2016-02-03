--
-- Table structure for table `webdev_registrations`
--

CREATE TABLE `webdev_registrations` (
  `nick` varchar(64) NOT NULL,
  `contact_number` varchar(16) NOT NULL,
  `stream` varchar(32) NOT NULL,
  `year` varchar(16) NOT NULL,
  `experience` varchar(512) NOT NULL,
  `why_join` varchar(512) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `webdev_registrations`
--
ALTER TABLE `webdev_registrations`
  ADD PRIMARY KEY (`nick`);
