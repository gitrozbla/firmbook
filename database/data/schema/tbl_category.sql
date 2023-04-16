CREATE TABLE `tbl_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `alias` varchar(64) DEFAULT NULL,
  `root` int(11) NOT NULL DEFAULT '0',
  `lft` int(11) NOT NULL,
  `rgt` int(11) NOT NULL,
  `level` int(11) NOT NULL,
  `imported` tinyint(1) NOT NULL DEFAULT '0',
  `in_menu` tinyint(1) NOT NULL DEFAULT '1',
  `order_index` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`,`root`,`lft`,`rgt`,`level`,`in_menu`),
  UNIQUE KEY `id` (`id`),
  UNIQUE KEY `alias` (`alias`),
  KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8