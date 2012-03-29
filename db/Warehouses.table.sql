CREATE TABLE `Warehouses` (
  `WarehouseId` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(200) NOT NULL,
  `Address` varchar(1000) DEFAULT NULL,
  `Geoposition` point DEFAULT NULL,
  PRIMARY KEY (`WarehouseId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

