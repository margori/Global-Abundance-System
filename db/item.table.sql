CREATE TABLE IF NOT EXISTS `item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` text NOT NULL COMMENT 'includes tags prefixed with #',
  `shared` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'false means this item is need for an user, true means served.',
  `user_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL COMMENT '< 0is infinite quantity',
  `original_description` text NOT NULL,
  `expiration_date` date NOT NULL,
  `notified` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

ALTER TABLE `item`
  ADD CONSTRAINT `item_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;
