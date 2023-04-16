CREATE TABLE `tbl_creators_page` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `website_id` int(11) NOT NULL,
  `position` int(11) NOT NULL,
  `type` varchar(8) NOT NULL DEFAULT 'custom',
  `items_per_page` int(11) NOT NULL DEFAULT '20',
  `title` text CHARACTER SET utf8 NOT NULL,
  `alias` varchar(64) NOT NULL,
  `content` text CHARACTER SET utf8,
  PRIMARY KEY (`id`),
  UNIQUE KEY `website_id_2` (`website_id`,`alias`),
  KEY `website_id` (`website_id`),
  KEY `position` (`position`),
  CONSTRAINT `tbl_creators_page_ibfk_1` FOREIGN KEY (`website_id`) REFERENCES `tbl_creators_website` (`company_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1