CREATE TABLE `tbl_ad` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` varchar(16) NOT NULL,
  `type` varchar(8) NOT NULL DEFAULT 'image',
  `resource` text,
  `text` text,
  `text_css` text,
  `alt` text,
  `link` text NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_from` date DEFAULT NULL,
  `date_to` date DEFAULT NULL,
  `order_id` int(10) unsigned DEFAULT NULL,
  `no_limit` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`,`group_id`,`enabled`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8