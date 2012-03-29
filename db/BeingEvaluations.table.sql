CREATE TABLE `BeingEvaluations` (
  `BeingEvaluationId` int(11) NOT NULL AUTO_INCREMENT,
  `BeingId` int(11) NOT NULL,
  `EvaluationId` int(11) NOT NULL,
  `Date` datetime NOT NULL,
  `NextDate` datetime NOT NULL,
  PRIMARY KEY (`BeingEvaluationId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

