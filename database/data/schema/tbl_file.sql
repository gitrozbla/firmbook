CREATE TABLE `tbl_file` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `class` varchar(16) NOT NULL,
  `data_id` int(11) NOT NULL DEFAULT '0',
  `hash` varchar(16) NOT NULL,
  `extension` varchar(4) NOT NULL,
  `small` tinyint(1) NOT NULL DEFAULT '0',
  `medium` tinyint(1) NOT NULL DEFAULT '0',
  `large` tinyint(1) NOT NULL DEFAULT '0',
  `original` tinyint(1) NOT NULL DEFAULT '0',
  `position` int(10) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`,`class`,`data_id`),
  UNIQUE KEY `id` (`id`),
  KEY `position` (`position`),
  KEY `hash` (`hash`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8