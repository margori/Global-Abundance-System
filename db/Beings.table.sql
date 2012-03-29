CREATE TABLE `Beings` (
  `BeingId` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(250) NOT NULL,
  PRIMARY KEY (`BeingId`),
  UNIQUE KEY `being_name` (`Name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

