CREATE TABLE `tbl_source_message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` varchar(32) DEFAULT NULL,
  `object_id` int(11) NOT NULL,
  `message` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `category_2` (`category`,`object_id`),
  KEY `category` (`category`),
  KEY `object_id` (`object_id`),
  FULLTEXT KEY `message` (`message`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8