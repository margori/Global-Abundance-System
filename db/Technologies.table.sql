CREATE TABLE IF NOT EXISTS `Technologies` (
  `TechnologyId` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(200) NOT NULL,
  `Description` text,
  PRIMARY KEY (`TechnologyId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
