CREATE TABLE `UserPrivileges` (
  `UserPrivilegeId` int(11) NOT NULL,
  `BeingId` int(11) NOT NULL,
  `PrivilegeId` int(11) NOT NULL,
  PRIMARY KEY (`UserPrivilegeId`),
  KEY `BeingId` (`BeingId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 PACK_KEYS=0;

ALTER TABLE `UserPrivileges`
  ADD CONSTRAINT `FK_UserPrivileges_Users` FOREIGN KEY (`BeingId`) REFERENCES `Users` (`BeingId`) ON DELETE CASCADE;

