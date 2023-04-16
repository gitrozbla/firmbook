CREATE TABLE `tbl_movie` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `item_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `url` varchar(100) DEFAULT NULL,
  `source` tinyint(1) NOT NULL DEFAULT '1',
  `title` text,
  `description` text,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `item_id` (`item_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `tbl_movie_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `tbl_user` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tbl_movie_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `tbl_item` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8