CREATE TABLE `Requests` (
  `RequestId` int(11) NOT NULL AUTO_INCREMENT,
  `Title` varchar(100) NOT NULL,
  `Description` text NOT NULL,
  `CreatorId` int(11) NOT NULL,
  PRIMARY KEY (`RequestId`),
  KEY `CreatorId` (`CreatorId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

ALTER TABLE `Requests`
  ADD CONSTRAINT `FK_Creator` FOREIGN KEY (`CreatorId`) REFERENCES `Beings` (`BeingId`) ON DELETE CASCADE ON UPDATE CASCADE;

