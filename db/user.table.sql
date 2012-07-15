CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(250) NOT NULL,
  `password` varchar(50) NOT NULL,
  `real_name` varchar(200) DEFAULT NULL,
  `email` varchar(200) DEFAULT NULL,
  `default_tags` varchar(1000) DEFAULT NULL,
  `language` varchar(2) NOT NULL DEFAULT 'en',
  `last_login` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UQ_username` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

