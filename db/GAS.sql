SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


CREATE TABLE `BeingEvaluations` (
  `BeingEvaluationId` int(11) NOT NULL AUTO_INCREMENT,
  `BeingId` int(11) NOT NULL,
  `EvaluationId` int(11) NOT NULL,
  `Date` datetime NOT NULL,
  `NextDate` datetime NOT NULL,
  PRIMARY KEY (`BeingEvaluationId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `Beings` (
  `BeingId` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(250) NOT NULL,
  PRIMARY KEY (`BeingId`),
  UNIQUE KEY `being_name` (`Name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE `EvaluationAnswers` (
  `EvaluationAnswerId` int(11) NOT NULL AUTO_INCREMENT,
  `EvaluationQuestionId` int(11) NOT NULL,
  `Answer` varchar(1000) NOT NULL,
  `Correct` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`EvaluationAnswerId`),
  UNIQUE KEY `EvaluatioAnswerId` (`EvaluationAnswerId`),
  KEY `EvaluationQuestionId` (`EvaluationQuestionId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE `EvaluationQuestions` (
  `EvaluationQuestionId` int(11) NOT NULL AUTO_INCREMENT,
  `EvaluationId` int(11) NOT NULL,
  `Question` varchar(1000) NOT NULL,
  PRIMARY KEY (`EvaluationQuestionId`),
  KEY `EvaluationId` (`EvaluationId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE `Evaluations` (
  `EvaluationId` int(11) NOT NULL AUTO_INCREMENT,
  `Key` varchar(50) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Content` text NOT NULL,
  PRIMARY KEY (`EvaluationId`),
  UNIQUE KEY `EvaluationKey` (`Key`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE `Items` (
  `ItemId` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(200) NOT NULL,
  `Code` text,
  PRIMARY KEY (`ItemId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE `Privileges` (
  `PrivilegeId` int(11) NOT NULL AUTO_INCREMENT,
  `PrivilegeKey` varchar(20) NOT NULL,
  `PrivilegeName` varchar(100) NOT NULL,
  PRIMARY KEY (`PrivilegeId`),
  UNIQUE KEY `UQ_Privileges` (`PrivilegeKey`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 PACK_KEYS=0;

CREATE TABLE `Requests` (
  `RequestId` int(11) NOT NULL AUTO_INCREMENT,
  `Title` varchar(100) NOT NULL,
  `Description` text NOT NULL,
  `CreatorId` int(11) NOT NULL,
  PRIMARY KEY (`RequestId`),
  KEY `CreatorId` (`CreatorId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE `Stocks` (
  `StockId` int(11) NOT NULL AUTO_INCREMENT,
  `ItemId` int(11) NOT NULL,
  `WarehouseId` int(11) NOT NULL,
  `Balance` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`StockId`),
  KEY `ItemId` (`ItemId`),
  KEY `WarehouseId` (`WarehouseId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE `Technologies` (
  `TechnologyId` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(200) NOT NULL,
  `Description` text,
  PRIMARY KEY (`TechnologyId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE `UserPrivileges` (
  `UserPrivilegeId` int(11) NOT NULL,
  `BeingId` int(11) NOT NULL,
  `PrivilegeId` int(11) NOT NULL,
  PRIMARY KEY (`UserPrivilegeId`),
  KEY `BeingId` (`BeingId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 PACK_KEYS=0;

CREATE TABLE `Users` (
  `BeingId` int(11) NOT NULL,
  `UserName` varchar(50) NOT NULL,
  `Password` varchar(50) NOT NULL COMMENT 'MD5',
  PRIMARY KEY (`BeingId`),
  UNIQUE KEY `UserName` (`UserName`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `Warehouses` (
  `WarehouseId` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(200) NOT NULL,
  `Address` varchar(1000) DEFAULT NULL,
  `Geoposition` point DEFAULT NULL,
  PRIMARY KEY (`WarehouseId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;


ALTER TABLE `EvaluationAnswers`
  ADD CONSTRAINT `FK_Answers_Questions` FOREIGN KEY (`EvaluationQuestionId`) REFERENCES `EvaluationQuestions` (`EvaluationQuestionId`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `EvaluationQuestions`
  ADD CONSTRAINT `FK_Questions_Evaluations` FOREIGN KEY (`EvaluationId`) REFERENCES `Evaluations` (`EvaluationId`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `Requests`
  ADD CONSTRAINT `FK_Creator` FOREIGN KEY (`CreatorId`) REFERENCES `Beings` (`BeingId`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `Stocks`
  ADD CONSTRAINT `FK_Items` FOREIGN KEY (`ItemId`) REFERENCES `Items` (`ItemId`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_Warehouses` FOREIGN KEY (`WarehouseId`) REFERENCES `Warehouses` (`WarehouseId`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `UserPrivileges`
  ADD CONSTRAINT `FK_UserPrivileges_Users` FOREIGN KEY (`BeingId`) REFERENCES `Users` (`BeingId`) ON DELETE CASCADE;

ALTER TABLE `Users`
  ADD CONSTRAINT `FK_Users_Beings` FOREIGN KEY (`BeingId`) REFERENCES `Beings` (`BeingId`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
