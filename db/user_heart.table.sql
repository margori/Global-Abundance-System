CREATE TABLE IF NOT EXISTS `user_heart` (
  `from_user_id` int(11) NOT NULL,
  `to_user_id` int(11) NOT NULL,
  `love` int(11) NOT NULL,
  PRIMARY KEY (`from_user_id`,`to_user_id`),
  KEY `to_user_id` (`to_user_id`),
  KEY `from_user_id` (`from_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `user_heart`
  ADD CONSTRAINT `user_heart_ibfk_4` FOREIGN KEY (`to_user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_heart_ibfk_3` FOREIGN KEY (`from_user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
