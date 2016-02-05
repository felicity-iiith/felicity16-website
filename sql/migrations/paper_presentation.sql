--
-- Table structure for table `paper_presentation`
--

CREATE TABLE `paper_presentation` (
  `nick` varchar(64) NOT NULL,
  `contact_number` varchar(15) NOT NULL,
  `paper_link` varchar(512) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `paper_presentation`
--
ALTER TABLE `paper_presentation`
  ADD PRIMARY KEY (`nick`);
