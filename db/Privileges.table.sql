CREATE TABLE `Privileges` (
  `PrivilegeId` int(11) NOT NULL AUTO_INCREMENT,
  `PrivilegeKey` varchar(20) NOT NULL,
  `PrivilegeName` varchar(100) NOT NULL,
  PRIMARY KEY (`PrivilegeId`),
  UNIQUE KEY `UQ_Privileges` (`PrivilegeKey`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 PACK_KEYS=0;
