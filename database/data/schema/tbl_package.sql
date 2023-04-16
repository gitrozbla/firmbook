CREATE TABLE `tbl_package` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(56) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `description` text,
  `price` decimal(6,2) DEFAULT '0.00',
  `order_index` int(11) DEFAULT NULL,
  `css_name` varchar(8) NOT NULL,
  `badge_css` text NOT NULL,
  `item_css` text NOT NULL,
  `stats_color` varchar(8) NOT NULL,
  `color` varchar(6) DEFAULT NULL,
  `test_period` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8