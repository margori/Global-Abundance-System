CREATE TABLE `Stocks` (
  `StockId` int(11) NOT NULL AUTO_INCREMENT,
  `ItemId` int(11) NOT NULL,
  `WarehouseId` int(11) NOT NULL,
  `Balance` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`StockId`),
  KEY `ItemId` (`ItemId`),
  KEY `WarehouseId` (`WarehouseId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

ALTER TABLE `Stocks`
  ADD CONSTRAINT `FK_Items` FOREIGN KEY (`ItemId`) REFERENCES `Items` (`ItemId`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_Warehouses` FOREIGN KEY (`WarehouseId`) REFERENCES `Warehouses` (`WarehouseId`) ON DELETE CASCADE ON UPDATE CASCADE;

