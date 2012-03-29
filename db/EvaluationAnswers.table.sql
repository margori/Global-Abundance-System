CREATE TABLE `EvaluationAnswers` (
  `EvaluationAnswerId` int(11) NOT NULL AUTO_INCREMENT,
  `EvaluationQuestionId` int(11) NOT NULL,
  `Answer` varchar(1000) NOT NULL,
  `Correct` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`EvaluationAnswerId`),
  UNIQUE KEY `EvaluatioAnswerId` (`EvaluationAnswerId`),
  KEY `EvaluationQuestionId` (`EvaluationQuestionId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

ALTER TABLE `EvaluationAnswers`
  ADD CONSTRAINT `FK_Answers_Questions` FOREIGN KEY (`EvaluationQuestionId`) REFERENCES `EvaluationQuestions` (`EvaluationQuestionId`) ON DELETE CASCADE ON UPDATE CASCADE;

