CREATE TABLE IF NOT EXISTS `solution_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `solution_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `solution_id` (`solution_id`),
  KEY `item_id` (`item_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

ALTER TABLE `solution_item`
  ADD CONSTRAINT `solution_item_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `item` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `solution_item_ibfk_1` FOREIGN KEY (`solution_id`) REFERENCES `solution` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
