CREATE TABLE `Users` (
  `BeingId` int(11) NOT NULL,
  `UserName` varchar(50) NOT NULL,
  `Password` varchar(50) NOT NULL COMMENT 'MD5',
  PRIMARY KEY (`BeingId`),
  UNIQUE KEY `UserName` (`UserName`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `Users`
  ADD CONSTRAINT `FK_Users_Beings` FOREIGN KEY (`BeingId`) REFERENCES `Beings` (`BeingId`) ON DELETE CASCADE ON UPDATE CASCADE;

