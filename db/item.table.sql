CREATE TABLE IF NOT EXISTS `item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` text NOT NULL COMMENT 'includes tags prefixed with #',
  `shared` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'false means this item is need for an user, true means served.',
  `user_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL COMMENT '< 0 is infinite quantity',
  `original_description` text NOT NULL,
  `expiration_date` date NOT NULL,
  `creation_date` date NOT NULL,
  `notified` tinyint(1) NOT NULL DEFAULT '0',
  `project_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `project_id` (`project_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

ALTER TABLE `item`
  ADD CONSTRAINT `item_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `item_ibfk_3` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;