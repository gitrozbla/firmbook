CREATE TABLE `tbl_ad_box` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `alias` varchar(16) NOT NULL,
  `label` varchar(20) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `size` varchar(10) DEFAULT NULL,
  `description` text,
  `period` int(11) NOT NULL,
  `price` decimal(6,2) DEFAULT '0.00',
  `carousel` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8