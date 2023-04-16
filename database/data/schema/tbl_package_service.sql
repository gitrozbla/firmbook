CREATE TABLE `tbl_package_service` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `value_type` smallint(6) NOT NULL DEFAULT '0',
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `description` text,
  `order_index` int(11) DEFAULT NULL,
  `role` varchar(40) NOT NULL,
  `instruction` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `role` (`role`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8