SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Base de datos: `gas`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `item`
--

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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `item_comment`
--

CREATE TABLE IF NOT EXISTS `item_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `comment` text NOT NULL,
  `item_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `item_id` (`item_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solution`
--

CREATE TABLE IF NOT EXISTS `solution` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '1-new,2-draft,3-completed,4-delivered',
  `item_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `item_id` (`item_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solution_archive`
--

CREATE TABLE IF NOT EXISTS `solution_archive` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solution_item`
--

CREATE TABLE IF NOT EXISTS `solution_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `solution_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `solution_id` (`solution_id`),
  KEY `item_id` (`item_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(250) NOT NULL,
  `password` varchar(50) NOT NULL,
  `real_name` varchar(200) DEFAULT NULL,
  `email` varchar(200) DEFAULT NULL,
  `default_tags` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UQ_username` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `item`
--
ALTER TABLE `item`
  ADD CONSTRAINT `item_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `item_comment`
--
ALTER TABLE `item_comment`
  ADD CONSTRAINT `item_comment_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `item_comment_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `item` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `solution`
--
ALTER TABLE `solution`
  ADD CONSTRAINT `solution_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `item` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `solution_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `solution_item`
--
ALTER TABLE `solution_item`
  ADD CONSTRAINT `solution_item_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `item` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `solution_item_ibfk_1` FOREIGN KEY (`solution_id`) REFERENCES `solution` (`id`) ON DELETE CASCADE;
