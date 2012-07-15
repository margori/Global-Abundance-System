CREATE TABLE IF NOT EXISTS `unread_comment` (
  `comment_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`comment_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `unread_comment` ADD FOREIGN KEY (  `comment_id` ) REFERENCES  `gas`.`item_comment` (
`id`
) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `unread_comment` ADD FOREIGN KEY (  `user_id` ) REFERENCES  `gas`.`user` (
`id`
) ON DELETE CASCADE ON UPDATE CASCADE;