CREATE TABLE `EvaluationQuestions` (
  `EvaluationQuestionId` int(11) NOT NULL AUTO_INCREMENT,
  `EvaluationId` int(11) NOT NULL,
  `Question` varchar(1000) NOT NULL,
  PRIMARY KEY (`EvaluationQuestionId`),
  KEY `EvaluationId` (`EvaluationId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

ALTER TABLE `EvaluationQuestions`
  ADD CONSTRAINT `FK_Questions_Evaluations` FOREIGN KEY (`EvaluationId`) REFERENCES `Evaluations` (`EvaluationId`) ON DELETE CASCADE ON UPDATE CASCADE;


