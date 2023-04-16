CREATE TABLE `tbl_message` (
  `id` int(11) NOT NULL DEFAULT '0',
  `language` varchar(16) NOT NULL DEFAULT '',
  `translation` text,
  PRIMARY KEY (`id`,`language`),
  KEY `language` (`language`),
  FULLTEXT KEY `translation` (`translation`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8