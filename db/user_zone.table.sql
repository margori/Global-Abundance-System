CREATE TABLE IF NOT EXISTS `user_zone` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `top` double NOT NULL COMMENT 'latitude',
  `right` double NOT NULL COMMENT 'longitude',
  `bottom` double NOT NULL COMMENT 'latitude',
  `left` double NOT NULL COMMENT 'longitude',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;


ALTER TABLE `user_zone`
  ADD CONSTRAINT `user_zone_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;