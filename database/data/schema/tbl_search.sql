CREATE TABLE `tbl_search` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(8) COLLATE utf8_polish_ci NOT NULL,
  `action` varchar(4) COLLATE utf8_polish_ci NOT NULL,
  `datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `search` varchar(8) COLLATE utf8_polish_ci NOT NULL,
  `query` varchar(32) COLLATE utf8_polish_ci NOT NULL,
  `category` varchar(64) COLLATE utf8_polish_ci DEFAULT NULL,
  PRIMARY KEY (`id`,`type`,`action`,`datetime`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci