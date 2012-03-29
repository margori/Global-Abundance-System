CREATE TABLE `Evaluations` (
  `EvaluationId` int(11) NOT NULL AUTO_INCREMENT,
  `Key` varchar(50) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Content` text NOT NULL,
  PRIMARY KEY (`EvaluationId`),
  UNIQUE KEY `EvaluationKey` (`Key`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

